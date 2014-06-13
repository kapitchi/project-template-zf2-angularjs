<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapFileManager;


use League\Flysystem\FilesystemInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceManager;

class FilesystemManager extends AbstractPluginManager {

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if(!$plugin instanceof FilesystemInterface) {
            throw new Exception\RuntimeException("Not a filesystem");
        }
    }
} 