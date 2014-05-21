<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */ 

return [
    'authentication-adapter-manager' => array(
        'adapters' => [
            'factories' => array(
                'facebook' => function($sm) {
                        $ser = $sm->getServiceLocator()->get('authenticationFacebookAdapter');
                        $ins = new KapSecurity\Authentication\Adapter\OAuth2(1, $ser);
                        return $ins;
                    }
            ),
        ]
    ),
];