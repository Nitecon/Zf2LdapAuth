<?php
/**
 * This file is part of the Zf2LdapAuth Module (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * Copyright (c) 2013 Will Hattingh (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.txt that was distributed with this source code.
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'Zf2LdapAuth\Controller\Login' => 'Zf2LdapAuth\Controller\LoginController',
            'Zf2LdapAuth\Controller\Logout' => 'Zf2LdapAuth\Controller\LogoutController',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Zf2LdapAuth\Client\Ldap' => 'Zf2LdapAuth\ServiceFactory\LdapServiceFactory',
        )
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);

