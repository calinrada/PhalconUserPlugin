# Phalcon User Plugin (v 3.0)

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

This is a plugin based on Vokuro ACL idea.
    
### <a id="features"></a>Features

- Login / Register with Facebook account
- Login / Register with LinkedIn account
- Login / Register with Twitter account
- Login / Register with Google account
- Change password
- Password recovery by email
- Protect different areas from your website, where a user must be logged in, in order to have access
- Protect different actions, based on the ACL list for each user
- User profile: birth date, birth location, current location, profile picture
- Locations - save locations using google API - see Wiki for examples
- Simple notifications system

### <a id="installation"></a>Installation

The recommended installation is via [Composer](https://getcomposer.org/).

For minor / bug releases (based on [semantic versioning](https://semver.org/)):
```bash
$ composer require crada/phalcon-user-plugin:^3.0.0
```

For all commits and most current version (unstable):
```bash
$ composer require --dev crada/phalcon-user-plugin:v3.0.0
```

For bug releases only (maximum stability):
```bash
$ composer require crada/phalcon-user-plugin:~3.0.0
```

Or manually by adding the following to your `composer.json`:

```json
{
    "require": {
        "crada/phalcon-user-plugin": "^3.0.0"
    }
}
```

and then updating composer:

```bash
$ composer update
```


### <a id="plug-it"></a>Plug it

Add the following lines where to your events manager:

```php

$security = new \Phalcon\UserPlugin\Plugin\Security($di);
$eventsManager->attach('dispatch', $security);

```

Full example code:

```php
use Phalcon\UserPlugin\Plugin\Security as SecurityPlugin;
use Phalcon\Mvc\Dispatcher;

$di->setShared(
    'dispatcher',
    function() use ($di) {
        $eventsManager = $di->getShared('eventsManager');

        $security = new SecurityPlugin($di);
        $eventsManager->attach('dispatch', $security);

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }
);
```

Register Auth, Mail and Acl services

```php
use Phalcon\UserPlugin\Auth\Auth;
use Phalcon\UserPlugin\Acl\Acl;
use Phalcon\UserPlugin\Mail\Mail;

$di->setShared(
    'auth'
    function() {
        return new Auth();
    }
);

$di->setShared(
    'acl'
    function() {
        return new Acl();
    }
);

$di->setShared(
    'mail'
    function() {
        return new Mail();
    }
);
```

### <a id="configuration"></a>Configuration

You must add configuration keys to your config.php file. If you are using a multimodule application, i recommend
you to set up the configuration separately for each module.

#### Configuration examples

In the example bellow, you will treat your website as public, EXCEPT the actions ACCOUNT and PROFILE from the USER
controller:

```php
'pup' => [
    'redirect' => [
        'success' => 'user/profile',
        'failure' => 'user/login'    
    ],
    'resources' => [
        'type' => 'public',
        'resources' => [
            '*' => [
                // All except
                'user' => ['account', 'profile']
            ]
        ]
    ]
];
```

In the example bellow, the ONLY PUBLIC resources are the actions LOGIN and REGISTER from the USER controller:

```php
'pup' => [
    'redirect' => [
        'success' => 'user/profile',
        'failure' => 'user/login'    
    ],
    'resources' => [
        'type' => 'public',
        'resources' => [
            'user' => [
                'user' => ['login', 'register']
            ]
        ]
    ]
];
```

In the example bellow, you will treat your website as private, EXCEPT the actions LOGIN and REGISTER from the USER
controller:

```php
'pup' => [
    'redirect' => [
        'success' => 'user/profile',
        'failure' => 'user/login'    
    ],
    'resources' => [
        'type' => 'private',
        'resources' => [
            '*' => [
                // All except
                'user' => ['login', 'register']
            ]
        ]
    ]
];
```

In the example bellow, the ONLY PRIVATE resources are the actions ACCOUNT and PROFILE from the USER controller:

```php
'pup' => [
    'redirect' => [
        'success' => 'user/profile',
        'failure' => 'user/login'    
    ],
    'resources' => [
        'type' => 'private',
        'resources' => [
            'user' => [
                'user' => ['account', 'profile']
            ]
        ]
    ]
];
```

Configuration example with connectors:

```php
// phalcon-user-plugin
'pup' => [
    'redirect' => [
        'success' => 'user/profile',
        'failure' => 'user/login'    
    ],
    'resources' => [
        'type' => 'public',
        'resources' => [
            '*' => [
                // All except
                'user' => ['account', 'profile']
            ]
        ]
    ],
    'connectors' => [
        'facebook' => [
            'appId' => 'YOUR_FACEBOOK_APP_ID',
            'secret' => 'YOUR_FACEBOOK_APP_SECRET'
        ],
        'linkedIn' => [
            'api_key' => 'YOUR_LINKED_IN_APP_ID',
            'api_secret' => 'YOUR_LINKED_IN_APP_SECRET',
            'callback_url' => 'CALLBACK_URL'
        ],
        'twitter' => [
            'consumer_key' => 'TWITTER_CONSUMER_KEY',
            'consumer_secret' => 'TWITTER_CONSUMER_SECRET',
            // Leave empty if you don't want to set it
            'user_agent' => 'YOUR_APPLICATION_NAME'
        ],
        'google' => [
            'application_name' => 'YOUR_APPLICATION_NAME',
            'client_id' => 'YOUR_CLIENT_ID',
            'client_secret' => 'YOUR_CLIENT_SECRET',
            'developer_key' => 'YOUR_DEVELOPER_KEY',
            'redirect_uri' => 'YOUR_REDIRECT_URI'
        ]
    ]
];
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
        if (true === $this->auth->isUserSignedIn()) {
            $this->response->redirect(['action' => 'profile']);
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
}
```

### <a id="known-issues"></a>Known issues
- Twitter does not provide us the email. We are generating a random email for the user. It is your choice how you handle this

### <a id="examples"></a>Examples

* [Notifications](https://github.com/calinrada/PhalconUserPlugin/wiki/Notifications)

### <a id="todo"></a>TODO
- Implement CRUD templates for ACl, UserManagement, etc

