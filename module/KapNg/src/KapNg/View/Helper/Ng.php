<?php

namespace KapNg\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class Ng extends AbstractHelper implements FactoryInterface
{
    protected $apps = [];

    /**
     * @TODO
     * Factory for itself
     * @param ServiceLocatorInterface $serviceLocator
     * @return \self
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $helper = new self;
        return $helper;
    }
    
    public function __invoke($target = null, $modules = null)
    {
        if($target !== null) {
            return $this->bootstrap($target, $modules);
        }
        
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function bootstrap($target, $modules)
    {
        $this->apps[$target] = (array)$modules;
    }

    public function render()
    {
        return $this->getView()->partial(
            'kap-ng/loader',
            array(
                'apps' => $this->apps
            )
        );
    }

    
}
