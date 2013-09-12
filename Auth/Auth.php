<?php
namespace Phalcon\UserPlugin\Auth;

use Phalcon\Mvc\User\Component,
    Phalcon\UserPlugin\Models\User\User,
    Phalcon\UserPlugin\Models\User\UserRememberTokens,
    Phalcon\UserPlugin\Models\User\UserSuccessLogins,
    Phalcon\UserPlugin\Models\User\UserFailedLogins;

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

        //Check if the user exist
        $user = User::findFirstByEmail($credentials['email']);
        if ($user == false)
        {
            $this->registerUserThrottling(0);
            throw new Exception('Wrong email/password combination');
        }

        //Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password))
        {
            $this->registerUserThrottling($user->id);
            throw new Exception('Wrong email/password combination');
        }

        //Check if the user was flagged
        $this->checkUserFlags($user);

        //Register the successful login
        $this->saveSuccessLogin($user);

        //Check if the remember me was selected
        if (isset($credentials['remember']))
        {
            $this->createRememberEnviroment($user);
        }

        $this->session->set('auth-identity', array(
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name
        ));
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Phalcon\UserPlugin\Models\User\User $user
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new UserSuccessLogins();
        $successLogin->setUserId($user->id);
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
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new UserRememberTokens();
        $remember->setUserId($user->id);
        $remember->setToken($token);
        $remember->setUserAgent($user_agent);
        $remember->token = $token;

        if ($remember->save() != false)
        {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
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
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = User::findFirstById($userId);
        if ($user)
        {
            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token)
            {
                $remember = UserRememberTokens::findFirst(array(
                    'usersId = ?0 AND token = ?1',
                    'bind' => array($user->id, $token)
                ));
                if ($remember)
                {
                    //Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt)
                    {
                        $this->checkUserFlags($user);
                        $this->session->set('auth-identity', array(
                            'id' => $user->id,
                            'name' => $user->name,
                            'profile' => $user->profile->name
                        ));
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('user/profile');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('user/login');
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param Phalcon\UserPlugin\Models\User\User $user
     */
    public function checkUserFlags(User $user)
    {
        if ($user->active <> 1)
        {
            throw new Exception('The user is inactive');
        }

        if ($user->banned <> 0)
        {
            throw new Exception('The user is banned');
        }

        if ($user->suspended <> 0)
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
        if ($this->cookies->has('RMU'))
        {
            $this->cookies->get('RMU')->delete();
        }

        if ($this->cookies->has('RMT'))
        {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
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
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name
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
}