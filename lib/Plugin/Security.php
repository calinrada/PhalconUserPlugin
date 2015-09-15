<?php

namespace Phalcon\UserPlugin\Plugin;

use Phalcon\Events\Event;
use Phalcon\Config;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Phalcon\UserPlugin\Auth\Auth;
use Phalcon\Mvc\View;
use Phalcon\UserPlugin\Exception\UserPluginException as Exception;

/**
 * Phalcon\UserPlugin\Plugin\Security.
 */
class Security extends Plugin
{
    /* @var Auth $auth */
    private $auth;

    /* @var View $view */
    private $view;

    /**
     * Allowed resource types for the configuration file.
     *
     * @var array
     */
    private $resourceTypes = array(
        'public',
        'private',
    );

    /**
     * @param Auth $auth
     *
     * @return $this
     */
    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * @param Dispatcher $dispatcher
     *
     * @return Auth
     */
    public function getAuth(Dispatcher $dispatcher)
    {
        return $dispatcher->getDI()->get('auth');
    }

    /**
     * @param View $view
     *
     * @return $this
     */
    public function setView(View $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @param Dispatcher $dispatcher
     *
     * @return View
     */
    public function getView(Dispatcher $dispatcher)
    {
        return $dispatcher->getDI()->getShared('view');
    }

    /**
     * beforeDispatchLoop.
     *
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->getAuth($dispatcher);
        $view = $this->getView($dispatcher);

        if ($auth->hasRememberMe()) {
            $auth->loginWithRememberMe(false);
        }

        $config = $dispatcher->getDI()->get('config');
        $pupConfig = $this->getConfigStructure($config);

        if ($auth->isUserSignedIn()) {
            $actionName = $dispatcher->getActionName();
            $controllerName = $dispatcher->getControllerName();

            if ($controllerName == 'user' && $actionName == 'login') {
                return $this->response->redirect($config->pup->redirect->success)->send();
            }
        }

        $needsIdentity = $this->needsIdentity($pupConfig, $dispatcher);
        $identity = $auth->getIdentity();

        if (true === $needsIdentity) {
            if (!is_array($identity)) {
                $this->flash->notice('Private area. Please login.');

                $this->view->disable();

                return $this->response->redirect($config->pup->redirect->failure)->send();
            }
        }

        $view->setVar('identity', $identity);
    }

    /**
     * Check if the controller / action needs identity.
     *
     * @param array      $config
     * @param Dispatcher $dispatcher
     *
     * @return bool
     */
    private function needsIdentity($config, Dispatcher $dispatcher)
    {
        $actionName = $dispatcher->getActionName();
        $controllerName = $dispatcher->getControllerName();

        if ($config['type'] == 'public') { // all except ..
            return $this->checkPublicResources($config['resources'], $actionName, $controllerName);
        } else {
            return $this->checkPrivateResources($config['resources'], $actionName, $controllerName);
        }
    }

    /**
     * Check for public resources.
     *
     * @param array  $resources
     * @param string $actionName
     * @param string $controllerName
     *
     * @return bool
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
     * Check for private resources.
     *
     * @param array  $resources
     * @param string $actionName
     * @param string $controllerName
     *
     * @return bool
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
     * Get the configuration structure for the plugin.
     *
     * @param \Phalcon\Config $config
     *
     * @return \Phalcon\Config
     *
     * @throws Exception
     */
    private function getConfigStructure(Config $config)
    {
        if (isset($config->pup)) {
            $config = $config->pup->resources->toArray();

            if (!isset($config['type']) || (isset($config['type']) && !in_array($config['type'], $this->resourceTypes))) {
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
