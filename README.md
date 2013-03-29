Zf2LdapAuth
===========

Zend Framework 2 Basic LDAP Authentication Module provides an easy to use LDAP authentication system.

## Features
- Login callback to store ldap data in a function of your choice.
- Fully functional login form
- Fully customizable ldap configuration based on Zend\Ldap
- Multi LDAP server configurations based on Zend\Ldap server array

## Setup

The following steps are necessary to get this module working

Following steps are necessary to get this module working (considering a zf2-skeleton or very similar application)

  1. Run `php composer.phar require nitecon/zf2-ldap-auth:1.*`
  2. Add `Zf2LdapAuth` to the enabled modules list
  3. Copy the config/ldap.config.php file to your ApplicationRoot/config/autoload/ldap.config.php
     And modify as required for your installation.  Please make sure to move passwords for ldap servers etc
     out of the ldap.conf.php and into your local.php so that you do not store these files in your vcs.
  4. Create an optional callback function to store your data / initialize a session
  5. If using a callback function create 2 static methods (setData / destroyData) in a class of your choice,
     and add the class name to the callback_class option.  Remember to set use_callback_function to true.

## Addditional Information

The module uses by default /user/login & /user/logout in the router configuration to trigger login.

To test the application (once previous steps are completed) Just point your browser to your application and 
set the URI to /user/login.  It also includes a logout page /user/logout  To effectively use /user/logout, you
must set a callback function so the cata can be destroyed properly.

## Final notes

Please make sure to read through the ldap.conf.php file comments and make sure that you fully understand what the options do.

Enjoy and if you find bugs or issues please add pull requests for the module.
