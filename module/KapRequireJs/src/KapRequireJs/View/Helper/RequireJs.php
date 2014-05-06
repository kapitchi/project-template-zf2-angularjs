<?php

namespace KapRequireJs\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class RequireJs extends AbstractHelper implements FactoryInterface
{
    protected $loadModules = array();
    protected $buildConfigUrl;
    protected $requireJsUrl = 'vendor/requirejs/require.js';
    protected $configUrl = 'config.js';

    public function __invoke($module = null)
    {
        if ($module !== null) {
            return $this->loadModule($module);
        }
        return $this;
    }

    /**
     * Factory for itself
     * @param ServiceLocatorInterface $serviceLocator
     * @return \self
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('ApplicationConfig');
        if (empty($config['requirejs'])) {
            return new self;
        }

        $options = $config['requirejs'];

        $helper = new self;
        //$helper->setBuildConfigUrl($options['build_config_url']);
        return $helper;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Add a module to the array of modules to be loaded
     * @param $module
     * @return self
     */
    public function loadModule($module)
    {
        $module = (array)$module;
        $this->setLoadModules(array_merge($this->loadModules, $module));
        return $this;
    }

    public function render()
    {
        return $this->getView()->partial(
            'kap-require-js/loader',
            array(
                'requireJsUrl' => $this->getRequireJsUrl(),
                'configUrl' => $this->getConfigUrl(),
                'loadModules' => $this->getLoadModules(),
                'buildConfigUrl' => $this->getBuildConfigUrl(),
            )
        );
    }

    public function getLoadModules()
    {
        $loadModules = $this->loadModules;
        return $loadModules;
    }

    public function setLoadModules($loadModules)
    {
        $this->loadModules = $loadModules;
    }

    public function getBuildConfigUrl()
    {
        return $this->buildConfigUrl;
    }

    public function setBuildConfigUrl($buildConfigUrl)
    {
        $this->buildConfigUrl = $buildConfigUrl;
    }

    /**
     * @param string $requireJsUrl
     */
    public function setRequireJsUrl($requireJsUrl)
    {
        $this->requireJsUrl = $requireJsUrl;
    }

    /**
     * @return string
     */
    public function getRequireJsUrl()
    {
        return $this->requireJsUrl;
    }

    /**
     * @param string $configUrl
     */
    public function setConfigUrl($configUrl)
    {
        $this->configUrl = $configUrl;
    }

    /**
     * @return string
     */
    public function getConfigUrl()
    {
        return $this->configUrl;
    }

}
