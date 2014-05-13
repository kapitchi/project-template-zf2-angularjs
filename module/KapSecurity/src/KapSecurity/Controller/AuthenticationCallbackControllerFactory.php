<?php
namespace KapSecurity\Controller;

class AuthenticationCallbackControllerFactory
{
    public function __invoke($controllers)
    {
        $ins = new AuthenticationCallbackController(
            $controllers->getServiceLocator()->get('KapSecurity\Authentication\AuthenticationService'),
            $controllers->getServiceLocator()->get('KapSecurity\Authentication\Adapter\AdapterManager')
        );
        return $ins;
    }
}
