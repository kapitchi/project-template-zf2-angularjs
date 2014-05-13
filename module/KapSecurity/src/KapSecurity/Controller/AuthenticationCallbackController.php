<?php
namespace KapSecurity\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AuthenticationCallbackController extends AbstractActionController
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authenticationService;

    /**
     * @var AdapterManager
     */
    protected $adapterManager;

    public function __construct($authenticationService, $adapterManager)
    {
        $this->authenticationService = $authenticationService;
        $this->adapterManager = $adapterManager;
    }
    
    public function authenticationCallbackAction()
    {
        $event = $this->getEvent();
        
        $type = $this->params()->fromQuery('type');

        $this->adapterManager->setMvcEvent($this->getEvent());
        $adapter = $this->adapterManager->get($type);

        $result = $this->authenticationService->authenticate($adapter);
        
        return [
            'result' => $result
        ];
    }
}
