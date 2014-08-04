<?php
namespace KapApigility;

use Zend\EventManager\StaticEventManager;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;

class Module implements ControllerPluginProviderInterface
{
    public function onBootstrap($e)
    {
        $app = $e->getTarget();
        $this->sm = $app->getServiceManager();

        StaticEventManager::getInstance()->attach('ZF\Rest\RestController', 'getList.post', function($e) {
            $halCollection = $e->getParam('collection');
            $parameters = $e->getTarget()->queryParams();
            unset($parameters['page']);
            $halCollection->setCollectionRouteOptions(array(
                'query' => $parameters
            ));
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerPluginConfig()
    {
        return [
            'factories' => [
                'resource' => function(\Zend\Mvc\Controller\PluginManager $sm) {
                        $event = new ResourceEvent();
                        $event->setIdentity($sm->getServiceLocator()->get('api-identity'));
                        $sm->get('');
                    }
            ]
        ];
    }
}
