<?php
namespace KapFileManager;

use KapFileManager\FilesystemManager;
use KapFileManager\V1\Rest\File\FileEntity;
use KapFileManager\V1\Rest\File\FileResource;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Config;
use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements ApigilityProviderInterface, ServiceProviderInterface
{
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

        $hal->getEventManager()->attach(['renderEntity'], array($this, 'onRenderEntity'));
    }

    public function onRenderEntity($e)
    {
        $halEntity = $e->getParam('entity');
        $entity = $halEntity->entity;
        
        if(!$entity instanceof FileEntity) {
            return;
        }
        
        $filesystems = $this->sm->get('KapFileManager\FilesystemManager');
        $filesystem = $filesystems->get($entity['filesystem']);

        try {
            $url = $filesystem->getUrl($entity['filesystem_path']);
            $halEntity->getLinks()->add(\ZF\Hal\Link\Link::factory(array(
                'rel' => 'file',
                'url' => $url
            )));
        } catch(\LogicException $e) {
            //can't find URL plugin for this file
        }
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

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'KapFileManager\\FilesystemManager' => function($sm) {
                        $config = $sm->get('Config');
                        if(empty($config['file-manager']) || empty($config['file-manager']['filesystems'])) {
                            throw new \Exception("\$config['file-manager']['filesystems'] config needs to be set");
                        }
                        return new FilesystemManager(new Config($config['file-manager']['filesystems']));
                    }
            ]
        ];
    }
}
