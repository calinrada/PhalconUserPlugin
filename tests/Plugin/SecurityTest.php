<?php

class SecurityTest extends \UnitTestCase
{
    public function setUp(Phalcon\DiInterface $di = null, Phalcon\Config\Config $config = null): void
    {
        parent::setUp($di, $config);
    }

    public function testBeforeDispatchLoopRedirect()
    {
        $event = $this->getMockBuilder('Phalcon\Events\Event')
            ->setConstructorArgs(array('test', new stdClass()))
            ->getMock();

        $di = $this->getMockBuilder('Phalcon\Di\Di')
            ->setConstructorArgs(array('get'))
            ->getMock();

        $di->expects($this->any())
            ->method('get')
            ->with('config')
            ->will($this->returnValue($this->getConfig()));

        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->onlyMethods(array('getDI', 'getActionName', 'getControllerName'))
            ->getMock();

        $dispatcher->expects($this->any())
            ->method('getDI')
            ->will($this->returnValue($di));

        $dispatcher->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('login'));

        $dispatcher->expects($this->any())
            ->method('getControllerName')
            ->will($this->returnValue('user'));

        $auth = $this->getMockBuilder('Phalcon\UserPlugin\Auth\Auth')
            ->onlyMethods(array('hasRememberMe', 'loginWithRememberMe', 'isUserSignedIn', 'getIdentity'))
            ->getMock();

        $auth->expects($this->any())
            ->method('hasRememberMe')
            ->will($this->returnValue(true));

        $auth->expects($spy = $this->any())
            ->method('loginWithRememberMe');

        $auth->expects($this->any())
            ->method('isUserSignedIn')
            ->will($this->returnValue(true));

        $security = new Phalcon\UserPlugin\Plugin\Security();
        $security->setAuth($auth);

        $response = $security->beforeDispatchLoop($event, $dispatcher);

        // loginWithRememberMe was called
        $invocations = $spy->getInvocations();
        $this->assertEquals(1, count($invocations));

        // Redirect to profile on sign in
        $this->assertEquals('/bin/user/profile', $response->getHeaders()->get('Location'));
    }

    public function testBeforeDispatchLoopFail()
    {
        $event = $this->getMockBuilder('Phalcon\Events\Event')
            ->setConstructorArgs(array('test', new stdClass()))
            ->getMock();

        $di = $this->getMockBuilder('Phalcon\Di\Di')
            ->setConstructorArgs(array('get'))
            ->getMock();

        $di->expects($this->any())
            ->method('get')
            ->with('config')
            ->will($this->returnValue($this->getConfig()));

        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->onlyMethods(array('getDI', 'getActionName', 'getControllerName'))
            ->getMock();

        $dispatcher->expects($this->any())
            ->method('getDI')
            ->will($this->returnValue($di));

        $dispatcher->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('notLogin'));

        $dispatcher->expects($this->any())
            ->method('getControllerName')
            ->will($this->returnValue('notUser'));

        $auth = $this->getMockBuilder('Phalcon\UserPlugin\Auth\Auth')
            ->onlyMethods(array('hasRememberMe', 'isUserSignedIn', 'getIdentity'))
            ->getMock();

        $auth->expects($this->any())
            ->method('hasRememberMe')
            ->will($this->returnValue(false));

        $auth->expects($this->any())
            ->method('isUserSignedIn')
            ->will($this->returnValue(false));

        $auth->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue('notArray'));

        $view = $this->getMockBuilder('Phalcon\Mvc\View')
            ->onlyMethods(array('disable'))
            ->getMock();

        $view->expects($spy = $this->any())
            ->method('disable');

        $security = new Phalcon\UserPlugin\Plugin\Security();
        $security->setAuth($auth);
        $security->setView($view);

        /* @var Phalcon\Http\Response $response */
        $response = $security->beforeDispatchLoop($event, $dispatcher);
        $this->expectOutputString('<div class="noticeMessage">Private area. Please login.</div>'."\n");

        // $view->disable()
        $invocations = $spy->getInvocations();
        $this->assertEquals(1, count($invocations));

        $this->assertEquals('/user/login', $response->getHeaders()->get('Location'));
    }

    public function testNeedsIdentity()
    {
        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->getMock();
            //->setConstructorArgs(array('getActionName', 'getControllerName'))

        $dispatcher->expects($this->any())
            ->method('getActionName')
            ->will($this->returnValue('login'));

        $dispatcher->expects($this->any())
            ->method('getControllerName')
            ->will($this->returnValue('user'));

        $config = $this->getConfig()->toArray();

        $security = new Phalcon\UserPlugin\Plugin\Security();

        // TODO test each route (checkPublicResources & checkPrivateResources), verify the methods were called.
        $this->assertFalse($this->invokeMethod($security, 'needsIdentity', array($config['pup']['resources'], $dispatcher)));
    }

    public function testCheckPublicResources()
    {
        $security = new Phalcon\UserPlugin\Plugin\Security();

        $resources = array(
            '*' => array(
                'user' => array('login', 'logout', 'register'),
            ),
        );

        $this->assertTrue($this->invokeMethod($security, 'checkPublicResources', array($resources, 'login', 'user')));
        $this->assertFalse($this->invokeMethod($security, 'checkPublicResources', array($resources, 'invalidAction', 'user')));

        $resources = array(
            'user' => array('login', 'logout', 'register'),
        );

        $this->assertTrue($this->invokeMethod($security, 'checkPublicResources', array($resources, 'register', 'user')));
        $this->assertFalse($this->invokeMethod($security, 'checkPublicResources', array($resources, 'invalidAction', 'user')));

        // TODO, if (isset($controller['*'])) is not tested
    }

    public function testCheckPrivateResources()
    {
        $security = new Phalcon\UserPlugin\Plugin\Security();

        $resources = array(
            '*' => array(
                'user' => array('login', 'logout', 'register'),
            ),
        );

        $this->assertFalse($this->invokeMethod($security, 'checkPrivateResources', array($resources, 'login', 'user')));
        $this->assertTrue($this->invokeMethod($security, 'checkPrivateResources', array($resources, 'invalidAction', 'user')));

        $resources = array(
            'user' => array('login', 'logout', 'register'),
        );

        $this->assertTrue($this->invokeMethod($security, 'checkPrivateResources', array($resources, 'register', 'resources')));
        $this->assertTrue($this->invokeMethod($security, 'checkPrivateResources', array($resources, 'invalidAction', 'user')));

        // TODO, if (isset($controller['*'])) is not tested
    }

    /**
     * @group getConfigureStructure
     * @expectedException           Phalcon\UserPlugin\Exception\UserPluginException
     * @expectedExceptionCode       0
     * @expectedExceptionMessage    Configuration error: I couldn't find the configuration key "pup"
     */
    public function testGetConfigStructureEmptyConfig()
    {
        $config = new Phalcon\Config\Config();
        $security = new Phalcon\UserPlugin\Plugin\Security();
        $dispatcher = new Phalcon\Mvc\Dispatcher();

        $this->expectException(Phalcon\UserPlugin\Exception\UserPluginException::class);
        $this->expectExceptionMessageMatches('/Configuration error: I couldn\'t find the configuration key "pup"/');
        $this->invokeMethod($security, 'getConfigStructure', array($config, $dispatcher));
    }

    /**
     * @group getConfigureStructure
     * @expectedException           Phalcon\UserPlugin\Exception\UserPluginException
     * @expectedExceptionCode       0
     * @expectedExceptionMessage    Wrong configuration for key "type" or the key does not exists
     */
    public function testGetConfigStructureTypeNotSet()
    {
        $config = $this->getConfig();
        unset($config->pup->resources->type);
        $security = new Phalcon\UserPlugin\Plugin\Security();
        $dispatcher = new Phalcon\Mvc\Dispatcher();

        $this->expectException(Phalcon\UserPlugin\Exception\UserPluginException::class);
        $this->expectExceptionMessageMatches('/Wrong configuration for key "type" or the key does not exists/');
        $this->invokeMethod($security, 'getConfigStructure', array($config, $dispatcher));
    }

    /**
     * @group getConfigureStructure
     * @expectedException           Phalcon\UserPlugin\Exception\UserPluginException
     * @expectedExceptionCode       0
     * @expectedExceptionMessage    Wrong configuration for key "type" or the key does not exists
     */
    public function testGetConfigStructureTypeNotInArray()
    {
        $config = $this->getConfig();
        $config->pup->resources->type = 'NotInArray';
        $security = new Phalcon\UserPlugin\Plugin\Security();
        $dispatcher = new Phalcon\Mvc\Dispatcher();

        $this->expectException(Phalcon\UserPlugin\Exception\UserPluginException::class);
        $this->expectExceptionMessageMatches('/Wrong configuration for key "type" or the key does not exists/');
        $this->invokeMethod($security, 'getConfigStructure', array($config, $dispatcher));
    }

    /**
     * @group getConfigureStructure
     * @expectedException           Phalcon\UserPlugin\Exception\UserPluginException
     * @expectedExceptionCode       0
     * @expectedExceptionMessage    Resources key must be an array
     */
    public function testGetConfigStructureResourceNotSet()
    {
        $config = $this->getConfig();
        unset($config->pup->resources->resources);
        $security = new Phalcon\UserPlugin\Plugin\Security();
        $dispatcher = new Phalcon\Mvc\Dispatcher();

        $this->expectException(Phalcon\UserPlugin\Exception\UserPluginException::class);
        $this->expectExceptionMessageMatches('/Resources key must be an array/');
        $this->invokeMethod($security, 'getConfigStructure', array($config, $dispatcher));
    }

    /**
     * @group getConfigureStructure
     * @expectedException           Phalcon\UserPlugin\Exception\UserPluginException
     * @expectedExceptionCode       0
     * @expectedExceptionMessage    Resources key must be an array
     */
    public function testGetConfigStructureResourceNotInArray()
    {
        $config = $this->getConfig();
        $config->pup->resources->resources = 'NoSuchResource';
        $security = new Phalcon\UserPlugin\Plugin\Security();
        $dispatcher = new Phalcon\Mvc\Dispatcher();

        $this->expectException(Phalcon\UserPlugin\Exception\UserPluginException::class);
        $this->expectExceptionMessageMatches('/Resources key must be an array/');
        $this->invokeMethod($security, 'getConfigStructure', array($config, $dispatcher));
    }

    /**
     * @group getConfigureStructure
     *
     * @throws \Phalcon\UserPlugin\Exception\UserPluginException
     */
    public function testGetConfigStructure()
    {
        $config = $this->getConfig();
        $security = new Phalcon\UserPlugin\Plugin\Security();
        $dispatcher = new Phalcon\Mvc\Dispatcher();

        $matchConfig = array(
            'type' => 'private',
            'resources' => array(
                'user' => array('login', 'logout', 'register'),
            ),
        );
        $this->assertEquals($matchConfig, $this->invokeMethod($security, 'getConfigStructure', array($config, $dispatcher)));
    }

    /**
     * @return \Phalcon\Config\Config
     */
    public function getConfig()
    {
        $config = new \Phalcon\Config\Config(array(
            'pup' => array(
                'redirect' => array(
                    'success' => 'user/profile',
                    'failure' => 'user/login',
                ),
                'resources' => array(
                    'type' => 'private',
                    'resources' => array(
                        'user' => array('login', 'logout', 'register'),
                    ),
                ),
            ),
        ));

        return $config;
    }
}
