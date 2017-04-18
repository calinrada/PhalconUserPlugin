<?php

namespace Phalcon\UserPlugin\Connectors;

use Google_Client;
use Google_Service_Oauth2 as Oauth2Service;

/**
 * Phalcon\UserPlugin\Connectors\GoogleConnector.
 */
class GoogleConnector
{
    private $config;

    private $scopes = array(
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    );

    final public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect($di)
    {
        $session = $di->get('session');
        $response = $di->get('response');
        $request = $di->get('request');

        $client = $this->getClient();
        $oauth2 = new Oauth2Service($client);

        if ($request->get('code')) {
            $client->fetchAccessTokenWithAuthCode($request->get('code'));
            $session->set('googleToken', $client->getAccessToken());
            $redirect = $this->config['redirect_uri'];

            return array('status' => 0, 'redirect' => filter_var($redirect, FILTER_SANITIZE_URL));
        }

        if ($session->has('googleToken')) {
            $client->setAccessToken($session->get('googleToken'));
        }

        if ($client->getAccessToken()) {
            $service = new \Google_Service_Oauth2($client);
            $userinfo = $service->userinfo->get();
            $session->set('googleToken', $client->getAccessToken());

            return array('status' => 1, 'userinfo' => $userinfo);
        } else {
            $authUrl = $client->createAuthUrl();

            return array('status' => 0, 'redirect' => $authUrl);
        }
    }

    /**
     * Get client.
     *
     * @return Google_Client
     */
    public function getClient()
    {
        $client = new Google_Client();
        $client->setScopes($this->scopes);
        $client->setApplicationName($this->config['application_name']);
        $client->setClientId($this->config['client_id']);
        $client->setClientSecret($this->config['client_secret']);
        $client->setRedirectUri($this->config['redirect_uri']);
        $client->setDeveloperKey($this->config['developer_key']);

        return $client;
    }
}
