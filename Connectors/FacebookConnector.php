<?php
namespace Phalcon\UserPlugin\Connectors;

/**
 * Phalcon\UserPlugin\Connectors\FacebookConnector
 *
 * This is a modified class of the official class in order to use
 * Phalcon's session and cookie management
 *
 * @see https://github.com/facebook/facebook-php-sdk/blob/master/src/facebook.php
 */
class FacebookConnector extends \BaseFacebook
{
    private $di;

    const FBSS_COOKIE_NAME = 'fbss';

    const FBSS_COOKIE_EXPIRE = 31556926; // 1 year

    protected $sharedSessionID;

    protected static $kSupportedKeys = array('state', 'code', 'access_token', 'user_id');

    public function __construct($di)
    {
        $this->di = $di;

        $fbConfig = $di->get('config')->pup->connectors->facebook;

        $config['appId'] = $fbConfig->appId;
        $config['secret'] = $fbConfig->secret;

        parent::__construct($config);

        if (!empty($config['sharedSession'])) {
            $this->initSharedSession();
        }
    }

    protected function initSharedSession()
    {
        $o_cookies = $this->di->get('cookies');
        $cookie_name = $this->getSharedSessionCookieName();

        if ($o_cookies->has($cookie_name)) {
            $data = $this->parseSignedRequest($o_cookies->get($cookie_name)->getValue());
            if ($data && !empty($data['domain']) &&
                self::isAllowedDomain($this->getHttpHost(), $data['domain'])) {
                // good case
                $this->sharedSessionID = $data['id'];

                return;
            }
            // ignoring potentially unreachable data
        }
        // evil/corrupt/missing case
        $base_domain = $this->getBaseDomain();
        $this->sharedSessionID = md5(uniqid(mt_rand(), true));
        $cookie_value = $this->makeSignedRequest(
            array(
                'domain' => $base_domain,
                'id' => $this->sharedSessionID,
            )
        );
        $o_cookies->set($cookie_name, $cookie_value);

        if (!headers_sent()) {
            $expire = time() + self::FBSS_COOKIE_EXPIRE;
            //setcookie($cookie_name, $cookie_value, $expire, '/', '.'.$base_domain);
            $o_cookies->set($cookie_name, $cookie_value, $expire, '/', null, '.'.$base_domain);
        } else {
            // @codeCoverageIgnoreStart
            self::errorLog(
                'Shared session ID cookie could not be set! You must ensure you '.
                'create the Facebook instance before headers have been sent. This '.
                'will cause authentication issues after the first request.'
            );
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Provides the implementations of the inherited abstract
     * methods.  The implementation uses PHP sessions to maintain
     * a store for authorization codes, user ids, CSRF states, and
     * access tokens.
     */
    protected function setPersistentData($key, $value)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to setPersistentData.');

            return;
        }

        $session_var_name = $this->constructSessionVariableName($key);
        $this->di->get('session')->set($session_var_name, $value);
    }

    protected function getPersistentData($key, $default = false)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to getPersistentData.');

            return $default;
        }

        $session_var_name = $this->constructSessionVariableName($key);

        return $this->di->get('session')->has($session_var_name) ?
        $this->di->get('session')->get($session_var_name) : $default;
    }

    protected function clearPersistentData($key)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to clearPersistentData.');

            return;
        }

        $session_var_name = $this->constructSessionVariableName($key);
        $this->di->get('session')->remove($session_var_name);
    }

    protected function clearAllPersistentData()
    {
        foreach (self::$kSupportedKeys as $key) {
            $this->clearPersistentData($key);
        }
        if ($this->sharedSessionID) {
            $this->deleteSharedSessionCookie();
        }
    }

    protected function deleteSharedSessionCookie()
    {
        $cookie_name = $this->getSharedSessionCookieName();
        $this->di->get('cookies')->delete($cookie_name);
        $base_domain = $this->getBaseDomain();
    }

    protected function getSharedSessionCookieName()
    {
        return self::FBSS_COOKIE_NAME . '_' . $this->getAppId();
    }

    protected function constructSessionVariableName($key)
    {
        $parts = array('fb', $this->getAppId(), $key);
        if ($this->sharedSessionID) {
            array_unshift($parts, $this->sharedSessionID);
        }

        return implode('_', $parts);
    }
}
