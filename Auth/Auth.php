<?php
namespace Phalcon\UserPlugin\Auth;

use Phalcon\Mvc\User\Component,
    Phalcon\UserPlugin\Models\User\User,
    Phalcon\UserPlugin\Models\User\UserRememberTokens,
    Phalcon\UserPlugin\Models\User\UserSuccessLogins,
    Phalcon\UserPlugin\Models\User\UserFailedLogins,
    Phalcon\UserPlugin\Connectors\FacebookConnector;

/**
 * Phalcon\UserPlugin\Auth\Auth
 *
 * Manages Authentication/Identity Management
 */
class Auth extends Component
{
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolan
     */
    public function check($credentials)
    {
        $user = User::findFirstByEmail($credentials['email']);
        if ($user == false)
        {
            $this->registerUserThrottling(0);
            throw new Exception('Wrong email/password combination');
        }

        if (!$this->security->checkHash($credentials['password'], $user->getPassword()))
        {
            $this->registerUserThrottling($user->getId());
            throw new Exception('Wrong email/password combination');
        }

        $this->checkUserFlags($user);
        $this->saveSuccessLogin($user);

        if (isset($credentials['remember']))
        {
            $this->createRememberEnviroment($user);
        }

        $this->session->set('auth-identity', array(
            'id' => $user->getId(),
            'email' => $user->getEmail()
        ));
    }

    /**
     * Login user - normal way
     *
     * @param \Phalcon\UserPlugin\Forms\User\LoginForm $form
     * @return \Phalcon\Http\ResponseInterface
     */
    public function login($form)
    {
        if (!$this->request->isPost())
        {
            if ($this->hasRememberMe())
            {
                return $this->loginWithRememberMe();
            }
        }
        else
        {
            if ($form->isValid($this->request->getPost()) == false)
            {
                foreach ($form->getMessages() as $message)
                 {
                    $this->flash->error($message);
                }
            }
            else
            {
                $this->check(array(
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                ));

                $pupRedirect = $this->getDI()->get('config')->pup->redirect;

                return $this->response->redirect($pupRedirect->success);
            }
        }
    }

    /**
     * Login with facebook account
     */
    public function loginWithFacebook()
    {
        $di = $this->getDI();
        $facebook = new FacebookConnector($di);
        $facebookUser = $facebook->getUser();

        if ($facebookUser)
        {
            try {
                $facebookUserProfile = $facebook->api('/me');
                error_log(json_encode($facebookUserProfile).PHP_EOL, 3, '/tmp/fblogin.log');
            } catch (\FacebookApiException $e) {
                $di->logger->begin();
                $di->logger->error($e->getMessage());
                $di->logger->commit();
                $facebookUser = null;
            }
        }
        else
        {
            return $this->response->redirect($facebook->getLoginUrl(), true);
        }

        if($facebookUser)
        {
            $pupRedirect = $di->get('config')->pup->redirect;

            $user = User::findFirstByFacebookId($facebookUserProfile['id']);

            if ($user)
            {
                $this->checkUserFlags($user);
                $this->session->set('auth-identity', array(
                        'id' => $user->getId(),
                        'email' => $user->getEmail()
                ));

                $this->saveSuccessLogin($user);

                return $this->response->redirect($pupRedirect->success);
            }
            else
            {
                $password = $this->generatePassword();
                error_log('Password: '.$password.PHP_EOL, 3, '/tmp/fblogin.log');
                $user = new User();
                $user->setEmail(isset($facebookUserProfile['email']) ? $facebookUserProfile['email'] : 'a@a.com');
                $user->setPassword($di->get('security')->hash($password));
                $user->setFacebookId($facebookUserProfile['id']);
                $user->setFacebookName($facebookUserProfile['name']);
                $user->setFacebookData(json_encode($facebookUserProfile));
                $user->setMustChangePassword(0);
                $user->setGroupId(2);
                $user->setBanned(0);
                $user->setSuspended(0);
                $user->setActive(1);

                if(true == $user->create())
                {
                    $this->session->set('auth-identity', array(
                            'id' => $user->getId(),
                            'email' => $user->getEmail()
                    ));

                    $this->saveSuccessLogin($user);

                    return $this->response->redirect($pupRedirect->success, true);
                }
                else
                {
                    $this->flash->error('Error on facebook');
                    return $this->response->redirect($pupRedirect->failure, true);
                }
            }
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Phalcon\UserPlugin\Models\User\User $user
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new UserSuccessLogins();
        $successLogin->setUserId($user->getId());
        $successLogin->setIpAddress($this->request->getClientAddress());
        $successLogin->setUserAgent($this->request->getUserAgent());

        if (!$successLogin->save())
        {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the efectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failedLogin = new UserFailedLogins();
        $failedLogin->setUserId($userId);
        $failedLogin->setIpAddress($this->request->getClientAddress());
        $failedLogin->setAttempted(time());
        $failedLogin->save();

        $attempts = UserFailedLogins::count(array(
            'ip_address = ?0 AND attempted >= ?1',
            'bind' => array(
                $this->request->getClientAddress(),
                time() - 3600 * 6
            )
        ));

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }

    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Phalcon\UserPlugin\Models\User\User $user
     */
    public function createRememberEnviroment(User $user)
    {
        $user_agent = $this->request->getUserAgent();
        $token = md5($user->getEmail() . $user->getPassword() . $user_agent);

        $remember = new UserRememberTokens();
        $remember->setUserId($user->getId());
        $remember->setToken($token);
        $remember->setUserAgent($user_agent);
        $remember->setCreatedAt(time());

        if ($remember->save() != false)
        {
            $expire = time() + 86400 * 30;
            $this->cookies->set('RMU', $user->getId(), $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the coookies
     *
     * @return Phalcon\Http\Response
     */
    public function loginWithRememberMe($redirect = true)
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = User::findFirstById($userId);

        $pupRedirect = $this->getDI()->get('config')->pup->redirect;

        if ($user)
        {
            $userAgent = $this->request->getUserAgent();
            $token = md5($user->getEmail() . $user->getPassword() . $userAgent);

            if ($cookieToken == $token)
            {

                $remember = UserRememberTokens::findFirst(array(
                    'user_id = ?0 AND token = ?1',
                    'bind' => array($user->getId(), $token)
                ));

                if ($remember)
                {
                    if ((time() - (86400 * 30)) < $remember->getCreatedAt())
                    {
                        $this->checkUserFlags($user);
                        $this->session->set('auth-identity', array(
                            'id' => $user->getId(),
                            'email' => $user->getEmail()
                        ));
                        $this->saveSuccessLogin($user);

                        if(true === $redirect)
                        {
                            return $this->response->redirect($pupRedirect->success);
                        }

                        return;
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect($pupRedirect->failure);
    }

    /**
     * Check if the user is signed in
     *
     * @return boolean
     */
    public function isUserSignedIn()
    {
        $identity = $this->getIdentity();
        if(!is_array($identity) || isset($identity['id']))
        {
            return false;
        }
        return true;
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param Phalcon\UserPlugin\Models\User\User $user
     */
    public function checkUserFlags(User $user)
    {
        if ($user->getActive() <> 1)
        {
            throw new Exception('The user is inactive');
        }

        if ($user->getBanned() <> 0)
        {
            throw new Exception('The user is banned');
        }

        if ($user->getSuspended() <> 0)
        {
            throw new Exception('The user is suspended');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        $pupConfig = $this->getDI()->get('config')->pup;
        $fbAppId = $pupConfig->connectors->facebook->appId;

        if ($this->cookies->has('RMU'))
        {
            $this->cookies->get('RMU')->delete();
        }

        if ($this->cookies->has('RMT'))
        {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
        $this->session->remove('fb_'.$fbAppId.'_code');
        $this->session->remove('fb_'.$fbAppId.'_access_token');
        $this->session->remove('fb_'.$fbAppId.'_user_id');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     */
    public function authUserById($id)
    {
        $user = User::findFirstById($id);
        if ($user == false)
        {
            throw new Exception('The user does not exist');
        }

        $this->checkUserFlags($user);

        $this->session->set('auth-identity', array(
            'id' => $user->getId(),
            'email' => $user->getEmail()
        ));
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return Phalcon\UserPlugin\Models\User\User
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id']))
        {
            $user = User::findFirstById($identity['id']);
            if ($user == false)
            {
                throw new Exception('The user does not exist');
            }

            return $user;
        }

        return false;
    }

    /**
     * Generate a random password
     *
     * @param integer $length
     * @return string
     */
    public function generatePassword($length = 8)
    {
        $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789#@%_.";
        return substr(str_shuffle($chars),0,$length);
    }
}