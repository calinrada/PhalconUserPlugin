<?php
namespace Phalcon\UserPlugin\Auth;

use Phalcon\Mvc\User\Component,
Phalcon\UserPlugin\Repository\User\UserRepository as User,
Phalcon\UserPlugin\Models\User\UserRememberTokens,
Phalcon\UserPlugin\Models\User\UserSuccessLogins,
Phalcon\UserPlugin\Models\User\UserFailedLogins;

use Phalcon\UserPlugin\Connectors\LinkedInConnector,
Phalcon\UserPlugin\Connectors\FacebookConnector,
Phalcon\UserPlugin\Connectors\GoogleConnector,
Phalcon\UserPlugin\Connectors\TwitterConnector;

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
     * @param  array  $credentials
     * @return boolan
     */
    public function check($credentials)
    {
        $user = User::findFirstByEmail(strtolower($credentials['email']));
        if ($user == false) {
            $this->registerUserThrottling(null);
            throw new Exception('Wrong email/password combination');
        }

        if (!$this->security->checkHash($credentials['password'], $user->getPassword())) {
            $this->registerUserThrottling($user->getId());
            throw new Exception('Wrong email/password combination');
        }

        $this->checkUserFlags($user);
        $this->saveSuccessLogin($user);

        if (isset($credentials['remember'])) {
            $this->createRememberEnviroment($user);
        }

        $this->setIdentity($user);
    }

    /**
     * Set identity in session
     *
     * @param object $user
     */
    private function setIdentity($user)
    {
        $st_identity = array(
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'name'  => $user->getName(),
        );

        if ($user->profile) {
            $st_identity['profile_picture'] = $user->profile->getPicture();
        }

        $this->session->set('auth-identity', $st_identity);
    }

    /**
     * Login user - normal way
     *
     * @param  \Phalcon\UserPlugin\Forms\User\LoginForm $form
     * @return \Phalcon\Http\ResponseInterface
     */
    public function login($form)
    {
        if (!$this->request->isPost()) {
            if ($this->hasRememberMe()) {
                return $this->loginWithRememberMe();
            }
        } else {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flashSession->error($message->getMessage());
                }
            } else {
                $this->check(array(
                    'email'    => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'remember' => $this->request->getPost('remember')
                ));

                $pupRedirect = $this->getDI()->get('config')->pup->redirect;

                return $this->response->redirect($pupRedirect->success);
            }
        }

        return false;
    }

    /**
     * Login with facebook account
     */
    public function loginWithFacebook()
    {
        $di = $this->getDI();
        $facebook = new FacebookConnector($di);
        $facebookUser = $facebook->getUser();

        if ($facebookUser) {
            try {
                $facebookUserProfile = $facebook->api('/me');
            } catch (\FacebookApiException $e) {
                $di->logger->begin();
                $di->logger->error($e->getMessage());
                $di->logger->commit();
                $facebookUser = null;
            }
        } else {
            $scope = array('scope' => 'email,user_birthday,user_location');

            return $this->response->redirect($facebook->getLoginUrl($scope), true);
        }

        if ($facebookUser) {
            $pupRedirect = $di->get('config')->pup->redirect;
            $email = isset($facebookUserProfile['email']) ? $facebookUserProfile['email'] : 'a@a.com';
            $user = User::findFirst(" email='$email' OR facebook_id='".$facebookUserProfile['id']."' ");

            if ($user) {
                $this->checkUserFlags($user);
                $this->setIdentity($user);
                if (!$user->getFacebookId()) {
                    $user->setFacebookId($facebookUserProfile['id']);
                    $user->setFacebookName($facebookUserProfile['name']);
                    $user->setFacebookData(serialize($facebookUserProfile));
                    $user->update();
                }

                $this->saveSuccessLogin($user);

                return $this->response->redirect($pupRedirect->success);
            } else {
                $password = $this->generatePassword();

                $user = new User();
                $user->setEmail($email);
                $user->setPassword($di->get('security')->hash($password));
                $user->setFacebookId($facebookUserProfile['id']);
                $user->setFacebookName($facebookUserProfile['name']);
                $user->setFacebookData(serialize($facebookUserProfile));
                $user->setMustChangePassword(0);
                $user->setGroupId(2);
                $user->setBanned(0);
                $user->setSuspended(0);
                $user->setActive(1);

                if (true == $user->create()) {
                    $this->setIdentity($user);
                    $this->saveSuccessLogin($user);

                    return $this->response->redirect($pupRedirect->success);
                } else {
                    $this->flash->error('Error on facebook');

                    return $this->response->redirect($pupRedirect->failure, true);
                }
            }
        }
    }

    /**
     * Login with LinkedIn account
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function loginWithLinkedIn()
    {
        $di = $this->getDI();
        $config = $di->get('config')->pup->connectors->linkedIn->toArray();
        $config['callback_url'] = $config['callback_url'].'user/loginWithLinkedIn';
        $li = new LinkedInConnector($config);

        $token = $this->session->get('linkedIn_token');
        $token_expires = $this->session->get('linkedIn_token_expires_on', 0);

        if ($token && $token_expires > time()) {
            $pupRedirect = $di->get('config')->pup->redirect;
            $li->setAccessToken($this->session->get('linkedIn_token'));
            $email = $li->get('/people/~/email-address');
            $info = $li->get('/people/~');

            preg_match('#id=\d+#', $info['siteStandardProfileRequest']['url'], $matches);
            $linkedInId = str_replace("id=", "", $matches[0]);

            $user = User::findFirst("email='$email' OR linkedin_id='$linkedInId'");

            if ($user) {
                $this->checkUserFlags($user);
                $this->setIdentity($user);
                $this->saveSuccessLogin($user);

                if (!$user->getLinkedinId()) {
                    $user->setLinkedinId($linkedInId);
                    $user->setLinkedinName($info['firstName'].' '.$info['lastName']);
                    $user->update();
                }

                return $this->response->redirect($pupRedirect->success);
            } else {
                $password = $this->generatePassword();

                $user = new User();
                $user->setEmail($email);
                $user->setPassword($di->get('security')->hash($password));
                $user->setLinkedinId($linkedInId);
                $user->setLinkedinName($info['firstName'].' '.$info['lastName']);
                $user->setLinkedinData(json_encode($info));
                $user->setMustChangePassword(0);
                $user->setGroupId(2);
                $user->setBanned(0);
                $user->setSuspended(0);
                $user->setActive(1);

                if (true == $user->create()) {
                    $this->setIdentity($user);
                    $this->saveSuccessLogin($user);

                    return $this->response->redirect($pupRedirect->success);
                } else {
                    foreach ($user->getMessages() as $message) {
                        $this->flashSession->error($message->getMessage());
                    }

                    return $this->response->redirect($pupRedirect->failure);
                }
            }

        } else { // If token is not set
            if ($this->request->get('code')) {
                $token = $li->getAccessToken($this->request->get('code'));
                $token_expires = $li->getAccessTokenExpiration();
                $this->session->set('linkedIn_token', $token);
                $this->session->set('linkedIn_token_expires_on', time() + $token_expires);
            }
        }

        $state = uniqid();
        $url = $li->getLoginUrl(array(LinkedInConnector::SCOPE_BASIC_PROFILE, LinkedInConnector::SCOPE_EMAIL_ADDRESS), $state);

        return $this->response->redirect($url, true);
    }

    /**
     * Login with Twitter account
     */
    public function loginWithTwitter()
    {
        $di          = $this->getDI();
        $pupRedirect = $di->get('config')->pup->redirect;
        $oauth       = $this->session->get('twitterOauth');
        $config      = $di->get('config')->pup->connectors->twitter->toArray();
        $config      = array_merge($config, array('token' => $oauth['token'], 'secret' => $oauth['secret']));

        $twitter = new TwitterConnector($config, $di);
        if ($this->request->get('oauth_token')) {
            $twitter->access_token();

            $code = $twitter->user_request(array(
                'url' => $twitter->url('1.1/account/verify_credentials')
            ));

            if ($code == 200) {
                $data = json_decode($twitter->response['response'], true);

                if ($data['screen_name']) {
                    $code = $twitter->user_request(array(
                        'url' => $twitter->url('1.1/users/show'),
                        'params' => array(
                            'screen_name' => $data['screen_name']
                        )
                    ));

                    if ($code == 200) {
                        $response = json_decode($twitter->response['response'], true);
                        $twitterId = $response['id'];
                        $user = User::findFirst("twitter_id='$twitterId'");

                        if ($user) {
                            $this->checkUserFlags($user);
                            $this->setIdentity($user);
                            $this->saveSuccessLogin($user);

                            return $this->response->redirect($pupRedirect->success);
                        } else {
                            $password = $this->generatePassword();
                            $email = $response['screen_name'].rand(100000,999999).'@domain.tld'; // Twitter does not prived user's email
                            $user = new User();
                            $user->setEmail($email);
                            $user->setPassword($di->get('security')->hash($password));
                            $user->setTwitterId($response['id']);
                            $user->setTwitterName($response['name']);
                            $user->setTwitterData(json_encode($response));
                            $user->setMustChangePassword(0);
                            $user->setGroupId(2);
                            $user->setBanned(0);
                            $user->setSuspended(0);
                            $user->setActive(1);

                            if (true == $user->create()) {
                                $this->setIdentity($user);
                                $this->saveSuccessLogin($user);
                                $this->flashSession->notice('Because Twitter does not provide an email address, we had randomly generated one: '.$email);

                                return $this->response->redirect($pupRedirect->success);
                            } else {
                                foreach ($user->getMessages() as $message) {
                                    $this->flashSession->error($message->getMessage());
                                }

                                return $this->response->redirect($pupRedirect->failure);
                            }
                        }
                    }
                }
            } else {
                $di->get('logger')->begin();
                $di->get('logger')->error(json_encode($twitter->response));
                $di->get('logger')->commit();
            }
        } else {
            return $this->response->redirect($twitter->request_token(), true);
        }
    }

    public function loginWithGoogle()
    {
        $di       = $this->getDI();
        $config   = $di->get('config')->pup->connectors->google->toArray();

        $pupRedirect            = $di->get('config')->pup->redirect;
        $config['redirect_uri'] = $config['redirect_uri'].'user/loginWithGoogle';

        $google = new GoogleConnector($config);

        $response = $google->connect($di);

        if ($response['status'] == 0) {
            return $this->response->redirect($response['redirect'], true);
        } else {
            $gplusId = $response['userinfo']['id'];
            $email   = $response['userinfo']['email'];
            $name    = $response['userinfo']['name'];
            $user    = User::findFirst("gplus_id='$gplusId' OR email = '$email'");

            if ($user) {
                $this->checkUserFlags($user);
                $this->setIdentity($user);

                if (!$user->getGplusId()) {
                    $user->setGplusId($gplusId);
                    $user->setGplusName($name);
                    $user->setGplusData(serialize($response['userinfo']));
                    $user->update();
                }

                $this->saveSuccessLogin($user);

                return $this->response->redirect($pupRedirect->success);
            } else {
                $password = $this->generatePassword();

                $user = new User();
                $user->setEmail($email);
                $user->setPassword($di->get('security')->hash($password));
                $user->setGplusId($gplusId);
                $user->setGplusName($name);
                $user->setGplusData(serialize($response['userinfo']));
                $user->setMustChangePassword(0);
                $user->setGroupId(2);
                $user->setBanned(0);
                $user->setSuspended(0);
                $user->setActive(1);

                if (true == $user->create()) {
                    $this->setIdentity($user);
                    $this->saveSuccessLogin($user);

                    return $this->response->redirect($pupRedirect->success);
                } else {
                    foreach ($user->getMessages() as $message) {
                        $this->flashSession->error($message->getMessage());
                    }

                    return $this->response->redirect($pupRedirect->failure);
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

        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the efectiveness of brute force attacks
     *
     * @param int $user_id
     */
    public function registerUserThrottling($user_id)
    {
        $failedLogin = new UserFailedLogins();
        $failedLogin->setUserId($user_id == null ? new \Phalcon\Db\RawValue('NULL') : $user_id);
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

        if ($remember->save() != false) {
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

        if ($user) {
            $userAgent = $this->request->getUserAgent();
            $token = md5($user->getEmail() . $user->getPassword() . $userAgent);

            if ($cookieToken == $token) {

                $remember = UserRememberTokens::findFirst(array(
                    'user_id = ?0 AND token = ?1',
                    'bind' => array($user->getId(), $token)
                ));

                if ($remember) {
                    if ((time() - (86400 * 30)) < $remember->getCreatedAt()) {
                        $this->checkUserFlags($user);
                        $this->setIdentity($user);
                        $this->saveSuccessLogin($user);

                        if (true === $redirect) {
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

        if (is_array($identity)) {
            if (isset($identity['id'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param Phalcon\UserPlugin\Models\User\User $user
     */
    public function checkUserFlags(User $user)
    {
        if (false === $user->isActive()) {
            throw new Exception('The user is inactive');
        }

        if (true === $user->isBanned()) {
            throw new Exception('The user is banned');
        }

        if (true === $user->isSuspended()) {
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
     * Returns the name of the user
     *
     * @return string
     */
    public function getUserName()
    {
        $identity = $this->session->get('auth-identity');

        return isset($identity['name']) ? $identity['name'] : false;
    }
    /**
     * Returns the id of the user
     *
     * @return string
     */
    public function getUserId()
    {
        $identity = $this->session->get('auth-identity');

        return isset($identity['id']) ? $identity['id'] : false;
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        $pupConfig = $this->getDI()->get('config')->pup;
        $fbAppId = $pupConfig->connectors->facebook->appId;

        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }

        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
        $this->session->remove('fb_'.$fbAppId.'_code');
        $this->session->remove('fb_'.$fbAppId.'_access_token');
        $this->session->remove('fb_'.$fbAppId.'_user_id');
        $this->session->remove('googleToken');
        $this->session->remove('linkedIn_token');
        $this->session->remove('linkedIn_token_expires_on');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     */
    public function authUserById($id)
    {
        $user = User::findFirstById($id);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }

        $this->checkUserFlags($user);
        $this->setIdentity($user);

        return true;
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return Phalcon\UserPlugin\Models\User\User
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');

        if (isset($identity['id'])) {
            $user = User::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception('The user does not exist');
            }

            return $user;
        }

        return false;
    }

    /**
     * Generate a random password
     *
     * @param  integer $length
     * @return string
     */
    public function generatePassword($length = 8)
    {
        $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789#@%_.";

        return substr(str_shuffle($chars),0,$length);
    }
}
