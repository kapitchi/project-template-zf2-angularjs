<?php
namespace KapGallery;

use KapApigility\DbEntityRepository;
use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements ApigilityProviderInterface
{
    
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'KapGallery\\GalleryItemRepository' => function($sm) {
                        $ins = new DbEntityRepository(
                            $sm->get('KapGallery\V1\Rest\GalleryItem\GalleryItemResource\Table')
                        );
                        return $ins;
                    },
                "KapGallery\\V1\\Rest\\GalleryItem\\GalleryItemResource" => function($sm) {
                        $ins = new \KapApigility\EntityRepositoryResource(
                            $sm->get('KapGallery\\GalleryItemRepository')
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
