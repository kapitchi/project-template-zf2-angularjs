<?php
namespace KapSecurity\V1\Rpc\Authenticate;

use KapSecurity\Authentication\Adapter\AdapterManager;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;

class AuthenticateController extends AbstractActionController
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

    public function authenticateAction()
    {
        $event = $this->getEvent();
        
        $inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
        $type = $inputFilter->getValue('type');

        $this->adapterManager->setMvcEvent($this->getEvent());
        $adapter = $this->adapterManager->get($type);

        $result = $this->authenticationService->authenticate($adapter);

        return $this->createResponse($result);
    }

    private function createResponse(Result $result)
    {
        return [
            'code' => $result->getCode(),
            'is_valid' => $result->isValid(),
            'identity' => $result->getIdentity(),
            'messages' => $result->getMessages()
        ];

    }
}
