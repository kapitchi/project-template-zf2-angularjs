<?php
namespace KapFileManager\V1\Rpc\FilesystemSync;

use KapFileManager\FileRepositoryInterface;
use KapFileManager\FilesystemManager;
use KapFileManager\V1\Rest\File\FileResource;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;
use ZF\Rest\RestController;

class FilesystemSyncController extends AbstractActionController
{
    /**
     * @var FilesystemManager $manager
     */
    protected $manager;

    /**
     * @var FileRepositoryInterface
     */
    protected $fileRepository;
    
    public function __construct(FilesystemManager $manager, FileRepositoryInterface $fileRepository)
    {
        $this->manager = $manager;
        $this->fileRepository = $fileRepository;
    }
    
    public function filesystemSyncAction()
    {
        $inputFilter = $this->getEvent()->getParam('ZF\ContentValidation\InputFilter');
        $data = $inputFilter->getValues();
        
        $filesystemName = $data['filesystem'];
        $path = $data['path'];
        
        return $this->fileRepository->sync($this->manager, $filesystemName, $this->getServiceLocator()->get('api-identity'), $path);
    }
    
    /**
     * @param \KapFileManager\FilesystemManager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \KapFileManager\FilesystemManager
     */
    public function getManager()
    {
        return $this->manager;
    }

}
