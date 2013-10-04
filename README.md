# Phalcon User Plugin (alpha)

It is a plugin based on Vokuro ACL idea. This is an alpha version and i do not recommend you to use it in 
a production environment.

### Features

- Protect different areas from your website, where a user must be loged in, in order to have access
- Protect different actions, based on the ACL list for each user
- Login / Register with Facebook account
- Login / Register with LinkedIn account

### Installation

The recommended installation is via compososer. Just add the following line to your composer.json:

```json
{
    "crada/phalcon-user-plugin": "@dev"
}
```

```bash
$ php composer.phar update
```

### Plug it

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

    $di['mail'] = function() {
        return new \Phalcon\UserPlugin\Acl\Acl();
    };

    $di['acl'] = function() {
        return new \Phalcon\UserPlugin\Mail\Mail();
    };
```

### Configuration

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
         )
    )

```

### Example controller

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


