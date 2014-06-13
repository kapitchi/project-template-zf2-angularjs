<?php
namespace KapFileManager;

use KapFileManager\V1\Rest\File\FileEntity;
use KapFileManager\V1\Rest\File\FileResource;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
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
                'KapFileManager\\FilesystemManager' => 'KapFileManager\\FilesystemManagerFactory',
                'KapFileManager\\FileRepository' => function($sm) {
                        $ins = new FileDbRepository(
                            $sm->get('KapFileManager\\V1\\Rest\\File\\FileResource\\Table'),
                            $sm->get('KapFileManager\\FilesystemManager')
                        );
                        return $ins;
                    },
                "KapFileManager\\V1\\Rest\\File\\FileResource" => function($sm) {
                        $ins = new FileResource(
                            $sm->get('KapFileManager\\FileRepository'),
                            $sm->get('KapFileManager\\FilesystemManager')
                        );
                        
                        return $ins;
                    }
            ]
        ];
    }

}
