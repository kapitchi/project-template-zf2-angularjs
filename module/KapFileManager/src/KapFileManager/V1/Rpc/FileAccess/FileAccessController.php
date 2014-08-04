<?php
namespace KapFileManager\V1\Rpc\FileAccess;

use KapFileManager\FileRepositoryInterface;
use KapFileManager\FilesystemManager;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class FileAccessController extends AbstractActionController
{
    protected $manager;
    protected $repository;
    
    public function __construct(FilesystemManager $manager, FileRepositoryInterface $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function fileAccessAction()
    {
        $event = $this->getEvent();

        $data = $event->getParam('ZFContentNegotiationParameterData');
        
        $id = $data->getQueryParam('id');
        $forceDownload = (bool)$data->getQueryParam('download', false);
        
        $file = $this->repository->find($id);
        if(!$file) {
            //return new ApiProblem(404, 'No file exists');
            return $this->notFoundAction();
        }
        
        if($file['type'] !== 'FILE') {
            return $this->notFoundAction();
        }
        
        if($file['filesystem_error']) {
            return $this->notFoundAction();
        }
        
        $filesystemName = $file['filesystem'];

        $filesystem = $this->manager->get($filesystemName);

        $response = new \Zend\Http\Response\Stream();
        $response->setStream($filesystem->readStream($file['filesystem_path']));
        $response->setStatusCode(200);

        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', $file['mime_type']);
        
        if($forceDownload) {
            $headers->addHeaderLine('Content-Disposition', 'attachment; filename="' . $file['name'] . '"');
            //$headers->addHeaderLine('Content-Length', 7687);//$file['size']);//todo fix size
        }

        $response->setHeaders($headers);
        return $response;
    }
}
