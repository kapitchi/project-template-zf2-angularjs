<?php

namespace KapSecurity\Authentication;

use KapSecurity\Authentication\Adapter\AdapterInterface;
use KapSecurity\V1\Rest\IdentityAuthentication\IdentityAuthenticationResource;
use Zend\Authentication\Exception;
use ZF\Rest\AbstractResourceListener;

class AuthenticationService extends \Zend\Authentication\AuthenticationService {
    
    protected $identityAuthenticationResource;
    protected $identityResource;
    
    public function __construct(IdentityAuthenticationResource $identityAuthenticationResource, AbstractResourceListener $identityResource)
    {
        $this->identityAuthenticationResource = $identityAuthenticationResource;
        $this->identityResource = $identityResource;
    }
    
    public function authenticate(AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$adapter = $this->getAdapter()) {
                throw new Exception\RuntimeException('An adapter must be set or passed prior to calling authenticate()');
            }
        }
        $result = $adapter->authenticate();

        /**
         * ZF-7546 - prevent multiple successive calls from storing inconsistent results
         * Ensure storage has clean state
         */
        if ($this->hasIdentity()) {
            $this->clearIdentity();
        }

        if($result->isValid()) {
            $serviceId = $adapter->getId();
            $identity = $result->getIdentity();

            $data = array(
                'service_id' => $serviceId,
                'identity' => $identity
            );

            //todo this was throwing error 'Fatal error: Cannot use object of type Zend\Db\ResultSet\ResultSet as array in /Zend/Paginator/Paginator.php on line 531'
            //$authEntity = $this->identityAuthenticationResource->fetchAll($data)->getItem(1, 1);

            $authEntity = current($this->identityAuthenticationResource->fetchAll($data)->getCurrentItems()->toArray());
            
            if(!$authEntity) {
                $ret = $this->identityResource->create([
                    'enabled' => 0,
                    'authentication_enabled' => 0,
                    'registered_time' => date('Y-m-d H:i:s')
                ]);

                $data['owner_id'] = $ret['id'];
                $authEntity = $this->identityAuthenticationResource->create($data);
            }
            
            $result->setIdentityId($authEntity['owner_id']);
            
            $this->getStorage()->write($authEntity['owner_id']);
        }
        
        return $result;
    }

} 