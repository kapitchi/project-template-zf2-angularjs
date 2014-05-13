<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function loginAction()
    {
        
    }

    public function loginDialogAction()
    {
        return [
            'redirectUrl' => (string)$this->getService('facebook')->getAuthorizationUri()
        ];
    }

    public function callbackAction()
    {
        // This was a callback request from facebook, get the token
        $service = $this->getService('facebook');
        $token = $service->requestAccessToken($_GET['code']);
        print_r($token); exit; //XXX
        return [
            'token' => $token,
            'response' => json_decode($service->request('/me'), true)
        ];
    }

    private function getService($serviceName)
    {
        $uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
        $currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
        $currentUri->setQuery('');

        $servicesCredentials = array(
            'facebook' => array(
                'key'       => '773430982669649',
                'secret'    => '842f3f8bbf89487f07959ad748348c39',
            ),
        );

        /** @var $serviceFactory \OAuth\ServiceFactory An OAuth service factory. */
        $serviceFactory = new \OAuth\ServiceFactory();

        $storage = new \OAuth\Common\Storage\Session();

        // Setup the credentials for the requests
        $credentials = new \OAuth\Common\Consumer\Credentials(
            $servicesCredentials[$serviceName]['key'],
            $servicesCredentials[$serviceName]['secret'],
            'http://myapp.local/application/index/callback'
        );

        /** @var $facebookService Facebook */
        $facebookService = $serviceFactory->createService($serviceName, $credentials, $storage, array());


        return $facebookService;
    }
    
}
