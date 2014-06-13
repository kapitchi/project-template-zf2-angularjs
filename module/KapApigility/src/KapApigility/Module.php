<?php
namespace KapApigility;

use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;

class Module implements ControllerPluginProviderInterface
{
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
