<?php
namespace KapAlbum;

use KapAlbum\V1\Rest\AlbumItem\AlbumItemEntity;
use KapApigility\DbEntityRepository;
use Zend\Mvc\MvcEvent;
use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements ApigilityProviderInterface
{
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'KapAlbum\\AlbumItemRepository' => function($sm) {
                        $ins = new AlbumItemRepository(
                            $sm->get('KapAlbum\V1\Rest\AlbumItem\AlbumItemResource\Table'),
                            'id',
                            $sm->get('KapFileManager\\FileRepository')
                        );
                        return $ins;
                    },
                "KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemResource" => function($sm) {
                        $ins = new \KapApigility\EntityRepositoryResource(
                            $sm->get('KapAlbum\\AlbumItemRepository')
                        );
                        return $ins;
                    }
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
