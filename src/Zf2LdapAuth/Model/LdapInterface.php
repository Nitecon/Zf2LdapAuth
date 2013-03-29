<?php

/**
 * This file is part of the Zf2LdapAuth Module (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * Copyright (c) 2013 Will Hattingh (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.txt that was distributed with this source code.
 */

namespace Zf2LdapAuth\Model;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream as LogWriter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\Ldap as AuthAdapter;
use Zend\Ldap\Exception\LdapException;

class LdapInterface {

    private $config;
    private $logger;
    protected $ldap;

    public function __construct($config) {
        $this->config = $config;
        if (isset($this->config['log_dir'])) {
            if (!is_dir($this->config['log_dir'])) {
                try {
                    mkdir($this->config['log_dir']);
                } catch (Exception $exc) {
                    echo "<h1>Could not create log directory " . $this->config['log_dir'] . "</h1>";
                    echo $exc->getMessage();
                }
            }
        } else {
            $this->config['log_dir'] = "/tmp/ldap";
            if (!is_dir($this->config['log_dir'])) {
                try {
                    mkdir($this->config['log_dir']);
                } catch (Exception $exc) {
                    echo "<h1>Could not create log directory " . $this->config['log_dir'] . "</h1>";
                    echo $exc->getMessage();
                }
            }
        }

        $logger = new Logger;
        $writer = new LogWriter($config['log_dir'] . '/ldap.log');
        $logger->addWriter($writer);
        Logger::registerErrorHandler($logger);
        $this->logger = $logger;
        $this->logger->log(Logger::INFO, 'Starting LDAP Bind');
        $this->bind();
    }

    public function bind() {
        $options = $this->config['zend_ldap_config'];
        try {
            $this->ldap = new \Zend\Ldap\Ldap($options['server1']);
            $this->ldap->bind();
            $this->logger->log(Logger::INFO, 'LDAP Bind successful');
        } catch (LdapException $exc) {
            $this->logger->log(Logger::CRIT, 'Error During Bind Operation: ' . $exc->getMessage());
        }
    }

    public function getUserEntry($username) {
        $entryDN = "uid=$username," . $this->config['zend_ldap_config']['server1']['baseDn'];
        try {
            $hm = $this->ldap->getEntry($entryDN);
            $this->logger->log(Logger::INFO, 'getUserEntry data: ' . PHP_EOL . var_export($hm, TRUE));
            return $hm;
        } catch (LdapException $exc) {
            $this->logger->log(Logger::CRIT, 'Error During getUserEntry Operation: ' . $exc->getMessage());
            return FALSE;
        }
    }
    public function getUserObj($username){
        $ldapData = $this->getUserEntry($username);
        if ($ldapData !== false){
            $retObj = new \stdClass();
            foreach($ldapData as $k => $v){
                if (is_array($v) && count($v) < 2){
                    $retObj->$k = $v['0'];
                }else{
                    $retObj->$k = $v;
                }
            }
            return $retObj;
        }else{
            return FALSE;
        }

    }
    public function getRedirectLocation(){
        return $this->config['default_location'];
    }
    public function getCallBackFunction(){
        return $this->config['callback_class'];
    }
    public function useCallBack(){
        return $this->config['use_callback_function'];
    }
    public function authenticate($username, $password) {
        $options = $this->config['zend_ldap_config'];
        $auth = new AuthenticationService();
        try {
            $adapter = new AuthAdapter($options, $username, $password);
            $result = $auth->authenticate($adapter);
            if ($result->isValid()) {
                $this->logger->log(Logger::INFO, "$username logged in successfully");
                return true;
            } else {
                $this->logger->log(Logger::INFO, "$username failed to log in:");
                $messages = $result->getMessages();
                $this->logger->log(Logger::INFO, var_export($messages,true));
                return $messages['0'];
            }
        } catch (LdapException $exc) {
            return $exc->getMessage();
        }
    }

}

?>
