<?php
namespace KapSecurity\V1\Rpc\Authenticate;

class AuthenticateControllerFactory
{
    public function __invoke($controllers)
    {
        return new AuthenticateController(
            $controllers->getServiceLocator()->get('KapSecurity\Authentication\AuthenticationService'),
            $controllers->getServiceLocator()->get('KapSecurity\Authentication\Adapter\AdapterManager')
        );
    }
}
