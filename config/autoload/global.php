<?php
return array(
    'router' => array(
        'routes' => array(),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            // Toggle the following to true to change the ACL creation to
            // require an authenticated user by default, and thus selectively
            // allow unauthenticated users based on the rules.
            'deny_by_default' => false,
        )
    ),
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
);
