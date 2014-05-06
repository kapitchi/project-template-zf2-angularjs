<?php

namespace KapRequireJs;

use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements ViewHelperProviderInterface
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
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'requireJs' => 'KapRequireJs\View\Helper\RequireJs'
            ]
        ];
    }
}
