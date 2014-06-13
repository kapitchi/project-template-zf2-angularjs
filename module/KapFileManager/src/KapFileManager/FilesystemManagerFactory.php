<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapFileManager;


use Aws\S3\S3Client;
use Dropbox\Client;
use League\Flysystem\Adapter;
use League\Flysystem\Filesystem;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FilesystemManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if(empty($config['file-manager']) || empty($config['file-manager']['filesystem-manager'])) {
            throw new \Exception("\$config['file-manager']['filesystem-manager'] config needs to be set");
        }

        $conf = $config['file-manager']['filesystem-manager'];

        //@todo refactor to lazy creation
        $ins = new FilesystemManager();
        foreach($conf['config'] as $handle => $options) {
            $filesystem = self::createFilesystem($options);
            $ins->setService($handle, $filesystem);
        }
        
        return $ins;
    }
    
    public static function createFilesystem($options)
    {
        if(empty($options['type'])) {
            throw new \RuntimeException("Type not known");
        }
        
        $config = $options['options'];
        
        switch($options['type']) {
            case 'dropbox':
                $adapter = new Adapter\Dropbox(
                    new Client($config['key'], $config['app']),
                    $config['path']
                );
                
                break;
            case 'local':
                $adapter = new Adapter\Local($config['path']);
                
                break;
            case 's3':
                $client = S3Client::factory(array(
                    'key'    => $config['key'],
                    'secret' => $config['secret'],
                ));
                $adapter = new Adapter\AwsS3($client, $config['bucket'], $config['path']);
                break;
            default:
                throw new \RuntimeException("Not implemented type: {$options['type']}");
        }
        
        $filesystem = new Filesystem($adapter);

        return $filesystem;
    }
    
} 