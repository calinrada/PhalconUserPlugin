<?php
namespace Phalcon\UserPlugin\Connectors;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

/**
 * Phalcon\UserPlugin\Connectors\FacebookConnector
 */
class FacebookConnector
{
    private $di;

    private $fb_session;

    private $helper;

    private $url;

    public function __construct($di)
    {
        $this->di  = $di;
        $fbConfig  = $di->get('config')->pup->connectors->facebook;
        $protocol  = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
        $this->url = $protocol.$_SERVER['HTTP_HOST'].'/user/loginWithFacebook';

        FacebookSession::setDefaultApplication($fbConfig->appId, $fbConfig->secret);
    }

    public function getLoginUrl($scope = [])
    {
        $this->helper = new FacebookRedirectLoginHelper($this->url);

        return $this->helper->getLoginUrl($scope);
    }

    /**
     * Get facebook user details
     * @return unknown|boolean
     */
    public function getUser()
    {
        try {
            $this->helper     = new FacebookRedirectLoginHelper($this->url);
            $this->fb_session = $this->helper->getSessionFromRedirect();
        } catch (FacebookRequestException $ex) {
            $this->di->flashSession->error($ex->getMessage());
        } catch (\Exception $ex) {
            $this->di->flashSession->error($ex->getMessage());
        }

        if ($this->fb_session) {
            $request  = new FacebookRequest($this->fb_session, 'GET', '/me');
            $response = $request->execute();
            $fb_user  = $response->getGraphObject()->asArray();

            return $fb_user;
        }

        return false;
    }
}
