<?php

namespace KapSecurity\Authentication\Adapter;

use KapSecurity\Authentication\OAuth2Result;
use KapSecurity\Authentication\Result;
use KapSecurity\Authentication\UserProfile;
use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use Zend\Mvc\MvcEvent;

class OAuth2 implements AdapterInterface, CallbackAdapterInterface {
    protected $id;
    protected $code;
    protected $mvcEvent;
    /**
     * @var \League\OAuth2\Client\Provider\AbstractProvider
     */
    protected $service;
    protected $callbackUri;
    
    public function __construct($id, AbstractProvider $service)
    {
        $this->id = $id;
        $this->service = $service;
    }
    
    public function getId()
    {
        return $this->id;
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
        if(!$code) {
            return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                array(),
                array("No 'code' available")
            );
        }
        
        try {
            $service = $this->getService();
            $token = $service->getAccessToken('authorization_code', ['code' => $code]);
            $userProfile = $service->getUserDetails($token);
            
            $res = new OAuth2Result(
                Result::SUCCESS,
                $userProfile->uid
            );
            $res->setUserProfile($this->createUserProfile($userProfile));
            
            $res->setAccessToken($token->accessToken);
            $res->setRefreshToken($token->refreshToken);
            $res->setExpiresIn($token->expires);
            
            return $res;
            
        } catch(\Exception $e) {
            return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                $code,
                array($e->getMessage())
            );
        }
    }
    
    private function createUserProfile(User $user)
    {
        $orig = $user->getArrayCopy();
        $profile = new UserProfile();
        foreach([
            'nickname' => 'username',
            'name' => 'displayName',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'email' => 'email',
            'description' => 'description',
            'imageUrl' => 'imageUrl',
                ] as $from => $to) {
            
            if(!empty($orig[$from])) {
                $profile[$to] = $orig[$from];
            }
        }
        
        return $profile;
    }

    public function getRedirectUri()
    {
        return (string)$this->getService()->getAuthorizationUrl();
    }
    
    /**
     * @param string $callbackUri
     */
    public function setCallbackUri($callbackUri)
    {
        $this->getService()->redirectUri = $callbackUri;
    }

    /**
     * @param AbstractProvider $service
     */
    public function setService(AbstractProvider $service)
    {
        $this->service = $service;
    }

    /**
     * @return AbstractProvider
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param MvcEvent $mvcEvent
     */
    public function setMvcEvent(MvcEvent $mvcEvent = null)
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