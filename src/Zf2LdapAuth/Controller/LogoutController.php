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

class LogoutController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();

        $session = new \Zend\Session\Container('auth');
        $session->getManager()->destroy();
        return $view;
    }

}

?>
