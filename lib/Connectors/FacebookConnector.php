<?php

namespace Phalcon\UserPlugin\Connectors;

use \Facebook\FacebookRequest;

/**
 * Phalcon\UserPlugin\Connectors\FacebookConnector.
 */
class FacebookConnector
{
    private $di;

    private $fb;

    private $helper;

    private $url;

    private $scope;

    public function __construct($di, $scope)
    {
        $this->di = $di;
        $fbConfig = $di->get('config')->pup->connectors->facebook;
        $protocol = $di->get('request')->getScheme().'://';

        if (isset($fbConfig['route'])) {
            $this->url = $protocol.$_SERVER['HTTP_HOST'].$fbConfig['route'];
        } else {
            $this->url = $protocol.$_SERVER['HTTP_HOST'].'/user/loginWithFacebook';
        }

        $this->scope = $scope;

        $this->fb = new \Facebook\Facebook([
            'app_id' => $fbConfig->appId,
            'app_secret' => $fbConfig->secret,
            'default_graph_version' => 'v2.8',
        ]);
    }

    public function getLoginUrl($scope = [])
    {
        $helper = $this->fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl($this->url, count($scope) > 0 ? $scope : $this->scope);

        return $loginUrl;
    }

    /**
     * Get facebook user details.
     *
     * @return unknown|bool
     */
    public function getUser()
    {
        $helper = $this->fb->getRedirectLoginHelper();

        try {

            $accessToken = $helper->getAccessToken();

            if (!$accessToken) {
                $response = new \Phalcon\Http\Response();
                $response->redirect($this->getLoginUrl($this->scope), true);
                $response->send();
            }

            $response = $this->fb->get('/me?fields=id,name,friends,email', $accessToken->getValue());

            return $response->getGraphUser()->asArray();

        } catch (\Facebook\Exceptions\FacebookResponseException $ex) {
            $this->di->get('flashSession')->error($ex->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $ex) {
            $this->di->get('flashSession')->error($ex->getMessage());
        }
    }
}
