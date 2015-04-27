# Phalcon User Plugin (v 2.0)

* [About](#about)
* [Features](#features)
* [Installation](#installation)
* [Plug it](#plug-it)
* [Configuration](#configuration)
* [Example controller](#example-controller)
* [Known issues](#known-issues)
* [Examples](#examples)
* [TODO](#todo)

### <a id="about"></a>About

It is a plugin based on Vokuro ACL idea. This is an alpha version and i do not recommend you to use it in
a production environment.

### <a id="features"></a>Features

- Login / Register with Facebook account
- Login / Register with LinkedIn account
- Login / Register with Twitter account
- Login / Register with Google account
- Change password
- Password recovery by email
- Protect different areas from your website, where a user must be loged in, in order to have access
- Protect different actions, based on the ACL list for each user
- User profile: birth date, birth location, current location, profile picture
- Locations - save locations using google API - see Wiki for examples
- Simple notifications system

### <a id="installation"></a>Installation

The recommended installation is via compososer. Just add the following line to your composer.json:

```json
{
    "crada/phalcon-user-plugin": "2.0"
}
```

```bash
$ php composer.phar update
```

### <a id="plug-it"></a>Plug it

Add the following lines where to your events manager:

```php

    $security = new \Phalcon\UserPlugin\Plugin\Security($di);
    $eventsManager->attach('dispatch', $security);

```

Full example code:

```php

    $di['dispatcher'] = function() use ($di) {
        $eventsManager = $di->getShared('eventsManager');
        $security = new \Phalcon\UserPlugin\Plugin\Security($di);
        $eventsManager->attach('dispatch', $security);

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        return $dispatcher;
    };

```

Register Auth, Mail and Acl services

```php
    $di['auth'] = function(){
        return new \Phalcon\UserPlugin\Auth\Auth();
    };

    $di['acl'] = function() {
        return new \Phalcon\UserPlugin\Acl\Acl();
    };

    $di['mail'] = function() {
        return new \Phalcon\UserPlugin\Mail\Mail();
    };
```

### <a id="configuration"></a>Configuration

You must add configuration keys to your config.php file. If you are using a multimodule application, i recommend
you to set up the configuration separately for each module.

#### Configuration examples

In the exampe bellow, you will treat your website as public, EXCEPT the actions ACCOUNT and PROFILE from the USER
controller:

```php

    'pup' => array(
        'redirect' => array(
            'success' => 'user/profile',
            'failure' => 'user/login'
        ),
        'resources' => array(
            'type' => 'public',
            'resources' => array(
                '*' => array( // All except
                    'user' => array('account', 'profile')
                ),
            )
         )
    )

```

In the exampe bellow, the ONLY PUBLIC resurces are the actions LOGIN and REGISTER from the USER controller:

```php

    'pup' => array(
        'redirect' => array(
            'success' => 'user/profile',
            'failure' => 'user/login'
        ),
        'resources' => array(
            'type' => 'public',
            'resources' => array(
                'user' => array('login', 'register')
            )
        )
    )

```

In the exampe bellow, you will treat your website as private, EXCEPT the actions LOGIN and REGISTER from the USER
controller:

```php

    'pup' => array(
        'redirect' => array(
            'success' => 'user/profile',
            'failure' => 'user/login'
        ),
        'resources' => array(
            'type' => 'private',
            'resources' => array(
                '*' => array( // All except
                    'user' => array('login', 'register')
                ),
            )
         )
    )

```

In the exampe bellow, the ONLY PRIVATE resurces are the actions ACCOUNT and PROFILE from the USER controller:

```php

    'pup' => array(
        'redirect' => array(
            'success' => 'user/profile',
            'failure' => 'user/login'
        ),
        'resources' => array(
            'type' => 'private',
            'resources' => array(
                'user' => array('account', 'profile')
            )
        )
    )

```

Configuration example with connectors:

```php

    'pup' => array( // phalcon-user-plugin
        'redirect' => array(
            'success' => 'user/profile',
            'failure' => 'user/login'
        ),
        'resources' => array(
            'type' => 'public',
            'resources' => array(
                '*' => array( // All except
                    'user' => array('account', 'profile')
                ),
            )
         ),
         'connectors' => array(
             'facebook' => array(
                 'appId' => 'YOUR_FACEBOOK_APP_ID',
                 'secret' => 'YOUR_FACEBOOK_APP_SECRET'
             ),
             'linkedIn' => array(
                 'api_key' => 'YOUR_LINKED_IN_APP_ID',
                 'api_secret' => 'YOUR_LINKED_IN_APP_SECRET',
                 'callback_url' => 'CALLBACK_URL'
             ),
             'twitter' => array(
                 'consumer_key' => 'TWITTER_CONSUMER_KEY',
                 'consumer_secret' => 'TWITTER_CONSUMER_SECRET',
                 'user_agent' => 'YOUR_APPLICATION_NAME', // Leave empty if you don't want to set it
             ),
             'google' => array(
                 'application_name' => 'YOUR_APPLICATION_NAME',
                 'client_id' => 'YOUR_CLIENT_ID',
                 'client_secret' => 'YOUR_CLIENT_SECRET',
                 'developer_key' => 'YOUR_DEVELOPER_KEY',
                 'redirect_uri' => 'YOUR_REDIRECT_URI'
             ),
         )
    )

```

### <a id="example-controller"></a>Example controller

* For a complete controller example read the Wiki page: https://github.com/calinrada/PhalconUserPlugin/wiki/Controller

```php
class UserController extends Controller
{
    /**
     * Login user
     * @return \Phalcon\Http\ResponseInterface
     */
    public function loginAction()
    {
        if(true === $this->auth->isUserSignedIn())
        {
            $this->response->redirect(array('action' => 'profile'));
        }

        $form = new LoginForm();

        try {
            $this->auth->login($form);
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * Login with Facebook account
     */
    public function loginWithFacebookAction()
    {
        try {
            $this->view->disable();
            return $this->auth->loginWithFacebook();
        } catch(AuthException $e) {
            $this->flash->error('There was an error connectiong to Facebook.');
        }
    }

    /**
     * Login with LinkedIn account
     */
    public function loginWithLinkedInAction()
    {
        try {
            $this->view->disable();
            $this->auth->loginWithLinkedIn();
        } catch(AuthException $e) {
            $this->flash->error('There was an error connectiong to LinkedIn.');
        }
    }

    /**
     * Login with Twitter account
     */
    public function loginWithTwitterAction()
    {
        try {
            $this->view->disable();
            $this->auth->loginWithTwitter();
        } catch(AuthException $e) {
            $this->flash->error('There was an error connectiong to Twitter.');
        }
    }

    /**
     * Login with Google account
     */
    public function loginWithGoogleAction()
    {
        try {
            $this->view->disable();
            $this->auth->loginWithGoogle();
        } catch(AuthException $e) {
            $this->flash->error('There was an error connectiong to Google.');
        }
    }

    /**
     * Logout user and clear the data from session
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function signoutAction()
    {
        $this->auth->remove();
        return $this->response->redirect('/', true);
    }
```

### <a id="known-issues"></a>Known issues
- Twitter does not provide us the email. We are generating a random email for the user. It is your choice how you handle this

### <a id="examples"></a>Examples

* [Notifications](https://github.com/calinrada/PhalconUserPlugin/wiki/Notifications)

### <a id="todo"></a>TODO
- Implement CRUD templates for ACl, UserManagement, etc

