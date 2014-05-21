<?php
namespace KapFileManager\V1\Rpc\FilesystemSync;

use KapFileManager\FilesystemManager;
use KapFileManager\V1\Rest\File\FileResource;
use Zend\Mvc\Controller\AbstractActionController;

class FilesystemSyncController extends AbstractActionController
{
    /**
     * @var FilesystemManager $manager
     */
    protected $manager;

    /**
     * @var FileResource
     */
    protected $resource;
    
    public function __construct(FilesystemManager $manager, FileResource $resource)
    {
        $this->setManager($manager);
        $this->setResource($resource);
    }
    
    public function filesystemSyncAction()
    {
        $filesystemName = $this->params()->fromQuery('filesystem');
        $path = $this->params()->fromQuery('path');
        
        //$filesystem = $this->getManager()->get($filesystemName);
        $resource = $this->getResource();

        return $resource->sync($filesystemName, $path);
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

    /**
     * @param \KapFileManager\V1\Rest\File\FileResource $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return \KapFileManager\V1\Rest\File\FileResource
     */
    public function getResource()
    {
        return $this->resource;
    }
    
}
