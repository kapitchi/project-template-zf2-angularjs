<?php
namespace KapSecurity;

use KapSecurity\Authentication\Adapter\OAuth2;
use KapSecurity\Authentication\Adapter\CallbackAdapterInterface;
use KapSecurity\Authentication\AuthenticationService;
use KapSecurity\V1\Rest\AuthenticationService\AuthenticationServiceEntity;
use KapSecurity\V1\Rest\IdentityAuthentication\IdentityAuthenticationResource;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator;
use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements ApigilityProviderInterface
{
    protected $sm;
    
    public function onBootstrap($e)
    {
        $app = $e->getTarget();
        $this->sm = $app->getServiceManager();
        
        $events   = $app->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 110);
    }
    
    public function onRender($e)
    {
        //this needs to be onRender otherwise it would throw an exception, it looks like hal can't be created before onRender
        //"Missing parameter "name" - Zend/Mvc/Router/Http/Segment.php(313) - Zend\Mvc\Router\Http\Segment->buildPath(Array, Array, true, true, Array)
        
        $helpers = $this->sm->get('ViewHelperManager');
        $hal = $helpers->get('hal');

        $hal->getEventManager()->attach(['renderEntity', 'renderCollection.entity'], array($this, 'onRenderEntity'));
    }
    
    public function onRenderEntity($e)
    {
        $entity = $e->getParam('entity');
        if (! $entity instanceof AuthenticationServiceEntity) {
            return;
        }
        
        $adapters = $this->sm->get('KapSecurity\Authentication\Adapter\AdapterManager');
        $adapter = $adapters->get($entity['system_adapter_service']);
        
        if($adapter instanceof CallbackAdapterInterface) {
            $entity['redirectUri'] = $adapter->getRedirectUri();
        }
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'KapSecurity\Authentication\Adapter\AdapterManager' => 'KapSecurity\Authentication\Adapter\AdapterManager',
                'KapSecurity\Authentication\AuthenticationService' => function($sm) {
                        return new AuthenticationService(
                            $sm->get('KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationResource'),
                            $sm->get('KapSecurity\\V1\\Rest\\Identity\\IdentityResource')
                        );
                    },
                'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationResource' => function($sm) {
                        $ins = new IdentityAuthenticationResource(
                            new TableGateway('identity_authentication', $sm->get('DefaultDbAdapter')),
                            'id',
                            'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationCollection'
                        );
                        return $ins;
                    },
            ]
        ];
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'ZF\Apigility\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
