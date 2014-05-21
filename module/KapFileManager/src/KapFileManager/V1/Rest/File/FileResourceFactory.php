<?php
namespace KapFileManager\V1\Rest\File;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\Apigility\DbConnectedResourceAbstractFactory;

class FileResourceFactory extends DbConnectedResourceAbstractFactory
{
    public function canCreateServiceWithName(ServiceLocatorInterface $services, $name, $requestedName)
    {
        if($requestedName !== 'KapFileManager\\V1\\Rest\\File\\FileResource') {
            return false;
        }
        
        return parent::canCreateServiceWithName($services, $name, $requestedName);
    }
    

    public function createServiceWithName(ServiceLocatorInterface $services, $name, $requestedName)
    {
        $manager = $services->get('KapFileManager\\FilesystemManager');
        $config        = $services->get('Config');
        $config        = $config['zf-apigility']['db-connected'][$requestedName];
        $table         = $this->getTableGatewayFromConfig($config, $requestedName, $services);
        $identifier    = $this->getIdentifierFromConfig($config);
        $collection    = $this->getCollectionFromConfig($config, $requestedName);

        $fileresource = new FileResource($table, $identifier, $collection, $manager);
        
//        $ret = $fileresource->sync();
//        echo __FILE__ . ' Line: ' . __LINE__; var_dump($ret); exit; //XXX
        
        return $fileresource;
    }
    
}
