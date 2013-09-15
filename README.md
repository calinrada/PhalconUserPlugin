# Phalcon User Plugin (alpha)

It is a plugin based on Vokuro ACL idea. This is an alpha version and i do not recommend you to use it in 
a production environment.

### Features

- Protect different areas from your website, where a user must be loged in, in order to have access
- Protect different actions, based on the ACL list for each user
- Login / Register with Facebook account

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

### Configuration

You must add configuration keys to your config.php file. If you are using a multimodule application, i recommend 
you to set up the configuration separately for each module.

#### Configuration examples

In the exampe bellow, you will treat your website as public, EXCEPT the actions ACCOUNT and PROFILE from the USER 
controller:  

```php

    'pup' => array(
        'resources' => array(
            'redirect' => 'user/login',
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
        'resources' => array(
            'redirect' => 'user/login',
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
        'resources' => array(
            'redirect' => 'user/login',
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
        'resources' => array(
            'redirect' => 'user/login',
            'type' => 'private',
            'resources' => array(
                'user' => array('account', 'profile')
            )
        )
    )

```

Configuration example with facebook:

```php

    'pup' => array( // phalcon-user-plugin
        'resources' => array(
            'type' => 'public',
            'redirect' => 'user/login',
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
             )
         )
    )

```


