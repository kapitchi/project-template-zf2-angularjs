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
use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use ZF\MvcAuth\Identity\GuestIdentity;
use ZF\MvcAuth\MvcAuthEvent;

class Module implements ApigilityProviderInterface
{
    protected $sm;
    
    public function onBootstrap($e)
    {
        $app = $e->getTarget();
        $this->sm = $app->getServiceManager();
        
        $events   = $app->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 110);
        $events->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, array($this, 'onAuthenticationPost'), -100);
    }
    
    public function onRender($e)
    {
        //this needs to be onRender otherwise it would throw an exception, it looks like hal can't be created before onRender
        //"Missing parameter "name" - Zend/Mvc/Router/Http/Segment.php(313) - Zend\Mvc\Router\Http\Segment->buildPath(Array, Array, true, true, Array)
        
        $helpers = $this->sm->get('ViewHelperManager');
        $hal = $helpers->get('hal');

        $hal->getEventManager()->attach(['renderEntity'], array($this, 'onRenderEntity'));
    }
    
    public function onRenderEntity($e)
    {
        $halEntity = $e->getParam('entity');
        $entity = $halEntity->entity;

        if (! $entity instanceof AuthenticationServiceEntity) {
            return;
        }
        
        $adapters = $this->sm->get('KapSecurity\Authentication\Adapter\AdapterManager');
        $adapter = $adapters->get($entity['system_adapter_service']);

        $halEntity->getLinks()->add(\ZF\Hal\Link\Link::factory(array(
            'rel' => 'redirect_url',
            'url' => $adapter->getRedirectUri()
        )));
    }
    
    public function onAuthenticationPost(MvcAuthEvent $e)
    {
        /** @var AuthenticationService $authService */
        $authService = $this->sm->get('KapSecurity\Authentication\AuthenticationService');

        //not explicitly authenticated from apigility with known user session identity
        if($e->getIdentity() instanceof GuestIdentity && $authService->hasIdentity()) {
            echo __FILE__ . ' Line: ' . __LINE__; var_dump($authService); exit; //XXX
            $identityId = $authService->getIdentity();
            
            //todo this needs finishing - rbac permissions etc from what I understand rbac works like.
            $identity = new AuthenticatedIdentity($identityId);
            $identity->setName('user');
            
            $e->setIdentity($identity);
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
