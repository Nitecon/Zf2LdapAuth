<?php

/**
 * This file is part of the Zf2LdapAuth Module (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * Copyright (c) 2013 Will Hattingh (https://github.com/Nitecon/Zf2LdapAuth)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.txt that was distributed with this source code.
 */

namespace Zf2LdapAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Zf2LdapAuth\Forms\Login;

class LoginController extends AbstractActionController {

    public function loginAction() {
        $view = new ViewModel();
        $login = new Login();
        $builder = new AnnotationBuilder();
        $form = $builder->createForm($login);
        $request = $this->getRequest();
        $ldap = $this->getServiceLocator()->get('Zf2LdapAuth\Client\Ldap');
        if ($request->isPost()) {
            $form->bind($login);
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();

                try {
                    $auth = $ldap->authenticate($data->name, $data->password);
                    if ($auth === TRUE) {
                        $view->data = "Authentication Successful, Redirecting...";
                        if ($ldap->useCallBack()) {
                            $userData = $ldap->getUserObj($data->name);
                            if ($userData !== FALSE) {
                                $callBackFunction = $ldap->getCallBackFunction();
                                $callBackFunction::setData($userData);
                            }
                        }

                        /* User already has a session so redirect back */
                        return $this->redirect()->toUrl($ldap->getRedirectLocation());
                    } else {
                        $view->error = $auth;
                        $view->form = $form;
                        return $view;
                    }
                } catch (\Exception $exc) {
                    $view->data = $exc->getMessage();
                    return $view;
                }
            }
        } else {
            $view->form = $form;
            return $view;
        }
    }

}

?>
