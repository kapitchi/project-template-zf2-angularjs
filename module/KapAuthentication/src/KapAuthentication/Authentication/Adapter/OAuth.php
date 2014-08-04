<?php

namespace KapAuthentication\Authentication\Adapter;

use OAuth\OAuth2\Service\ServiceInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Mvc\MvcEvent;

class OAuth implements AdapterInterface {
    
    protected $mvcEvent;
    protected $service;
    
    public function __construct(ServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $code = $this->getMvcEvent()->getRequest()->getQuery('code');
        if($code) {
            $token = $this->getService()->requestAccessToken($code);
            echo __FILE__ . ' Line: ' . __LINE__; var_dump($token); exit; //XXX
        }
        
        $response = $this->getMvcEvent()->getResponse();

        $url = $this->getService()->getAuthorizationUri();
        $response->setStatusCode();
        
        return new \Zend\Authentication\Result(
            \Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID,
            array(),
            array('Redirecting t')
        );
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return ServiceInterface
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $mvcEvent
     */
    public function setMvcEvent(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;
    }

    /**
     * @return MvcEvent
     */
    public function getMvcEvent()
    {
        return $this->mvcEvent;
    }
    
} 