<?php
namespace Phalcon\UserPlugin\Plugin;

use Phalcon\Events\Event,
Phalcon\Mvc\Dispatcher,
Phalcon\Mvc\User\Plugin;

use Phalcon\UserPlugin\Exception\UserPluginException as Exception;

/**
 * Phalcon\UserPlugin\Plugin\Security
 */
class Security extends Plugin
{
    /**
     * Allowed resource types for the configuration file
     * @var array
     */
    private $st_resourceTypes = array(
        'public',
        'private'
    );

    /**
     * beforeDispatchLoop
     *
     * @param  Event                           $event
     * @param  Dispatcher                      $dispatcher
     * @return \Phalcon\Http\ResponseInterface
    */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        if ($this->auth->hasRememberMe()) {
            $this->auth->loginWithRememberMe(false);
        }

        if ($this->auth->isUserSignedIn()) {
            $actionName     = $dispatcher->getActionName();
            $controllerName = $dispatcher->getControllerName();

            if ($controllerName == 'user' && $actionName == 'login') {
                return $this->response->redirect($config->pup->redirect->success);
            }
        }

        $config = $dispatcher->getDI()->get('config');
        $pupConfig = $this->getConfigStructure($config);

        $needsIdentity = $this->needsIdentity($pupConfig, $dispatcher);

        $identity = $this->auth->getIdentity();

        if (true === $needsIdentity) {
            if (!is_array($identity)) {
                $this->flash->notice('Private area. Please login.');

                $this->view->disable();

                return $this->response->redirect($config->pup->redirect->failure)->send();
            }
        }

        $this->view->setVar('identity', $identity);
    }

    /**
     * Check if the controller / action needs identity
     *
     * @param  array      $config
     * @param  Dispatcher $dispatcher
     * @return boolean
     */
    private function needsIdentity($config, Dispatcher $dispatcher)
    {
        $actionName     = $dispatcher->getActionName();
        $controllerName = $dispatcher->getControllerName();

        if ($config['type'] == 'public') { // all except ..

            return $this->checkPublicResources($config['resources'], $actionName, $controllerName);
        } else {
            return $this->checkPrivateResources($config['resources'], $actionName, $controllerName);
        }

        return false;
    }

    /**
     * Check for public resources
     *
     * @param  array   $resources
     * @param  string  $actionName
     * @param  string  $controllerName
     * @return boolean
     */
    private function checkPublicResources($resources, $actionName, $controllerName)
    {
        $resources = isset($resources['*']) ? $resources['*'] : $resources;

        foreach ($resources as $controller => $actions) {
            if ($controller == $controllerName) {
                if (isset($controller['*'])) {
                    return true;
                } else {
                    if (in_array($actionName, $actions) || $actions[0] == '*') {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check for private resources
     *
     * @param  array   $resources
     * @param  string  $actionName
     * @param  string  $controllerName
     * @return boolean
     */
    private function checkPrivateResources($resources, $actionName, $controllerName)
    {
        $resources = isset($resources['*']) ? $resources['*'] : $resources;

        foreach ($resources as $controller => $actions) {
            if ($controller == $controllerName) {
                if (isset($controller['*'])) {
                    return true;
                } else {
                    if (in_array($actionName, $actions)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get the configuration structure for the plugin
     *
     * @param  \Phalcon\Config $config
     * @throws Exception
     */
    private function getConfigStructure(\Phalcon\Config $config)
    {
        if (isset($config->pup)) {
            $config = $config->pup->resources->toArray();

            if (!isset($config['type']) || (isset($config['type']) && !in_array($config['type'], $this->st_resourceTypes))) {
                throw new Exception('Wrong configuration for key "type" or the key does not exists');
            }

            if (!isset($config['resources']) || (isset($config['resources']) && !is_array($config['resources']))) {
                throw new Exception('Resources key must be an array');
            }

            return $config;
        } else {
            throw new Exception('Configuration error: I couldn\'t find the configuration key "pup" ');
        }
    }
}
