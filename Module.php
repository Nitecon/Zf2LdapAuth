<?php

/**
 * This file is part of the Zf2LdapAuth Module (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * Copyright (c) 2013 Will Hattingh (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.txt that was distributed with this source code.
 */

namespace Zf2LdapAuth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $config = $e->getApplication()->getServiceManager()->get('Config');
        $router = $e->getRouter();
        $router->addRoutes(array(
            'ldap-login-route' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => $config['zf2_ldap_config']['login_route'],
                    'defaults' => array(
                        '__NAMESPACE__' => 'Zf2LdapAuth\Controller',
                        'controller' => 'Login',
                        'action' => 'login',
                    ),
                ),
            ),
            'ldap-logout-route' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => $config['zf2_ldap_config']['logout_route'],
                    'defaults' => array(
                        '__NAMESPACE__' => 'Zf2LdapAuth\Controller',
                        'controller' => 'Logout',
                        'action' => 'index',
                    ),
                ),
            )));

        /*$router->addRoutes(
                array(
                        'userlogin' => array(
                            'type' => '\Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route' => '/user/login',
                                'defaults' => array(
                                    '__NAMESPACE__' => 'Zf2LdapAuth\Controller',
                                    'controller' => 'Login',
                                    'action' => 'login',
                                ),
                            ),
                        ),
                        'userlogout' => array(
                            'type' => '\Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route' => '/user/logout',
                                'defaults' => array(
                                    '__NAMESPACE__' => 'Zf2LdapAuth\Controller',
                                    'controller' => 'Logout',
                                    'action' => 'index',
                                ),
                            ),
                        )
                )
        );*/
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

}