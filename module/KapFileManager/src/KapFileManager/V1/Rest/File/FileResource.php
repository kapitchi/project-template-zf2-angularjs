<?php
namespace KapFileManager\V1\Rest\File;

use KapApigility\DbConnectedResource;
use KapApigility\EntityRepositoryResource;
use KapFileManager\FileRepositoryInterface;
use KapFileManager\FilesystemManager;
use League\Flysystem\Directory;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\MountManager;
use SebastianBergmann\Exporter\Exception;
use Zend\Db\TableGateway\TableGatewayInterface as TableGateway;
use Zend\Math\Rand;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class FileResource extends EntityRepositoryResource
{
    /**
     * @var FilesystemManager $manager
     */
    protected $manager;
    
    public function __construct(FileRepositoryInterface $repository, FilesystemManager $manager)
    {
        parent::__construct($repository);
        
        $this->setManager($manager);
    }


    /**
     * Create a resource
     * 
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        try {
            $repository = $this->getRepository();
                
            if(empty($data)) {
                $data = $this->getInputFilter()->getValues();
            }

            //if parent is not set but filesystem is
            if(empty($data->parent_id)) {
                if(empty($data->filesystem)) {
                    return new ApiProblem(422, "Either parent_id or filesystem must be specified");
                }

                $root = $repository->fetchByPath($data->filesystem, '');
                if(!$root) {
                    return new ApiProblem(422, "Couldn't find a root item for a filesystem specified '$data->filesystem'");
                }
                $data->parent_id = $root['id'];

                $items = $repository->getPaginatorAdapter(['filesystem' => $data->filesystem, 'filesystem_path'])->getItems(0, 1);
            }

            //name not specified -- generate random number
            if(empty($data->name)) {
                $data->name = Rand::getString(32);
            }
            
            $phpFile = null;
            
            //no event? we can't create uploaded files obviously
            $event = $this->getEvent();
            if($event && $event->getRequest()) {
                $request = $event->getRequest();

                $phpFiles = $request->getFiles()->toArray();
                if(count($phpFiles) > 1) {
                    //todo fix response code etc.
                    return new ApiProblem(400, 'More than one file?');
                }

                $phpFile = current($phpFiles);
            }
            
            $parent = $this->fetch($data->parent_id);
            if(!$parent) {
                throw \Exception("Can't find item '{$data->parent_id}'");
            }

            $filesystemName = $parent['filesystem'];
            $path = $parent['filesystem_path'] ? $parent['filesystem_path'] . '/' : '';
            
            $path .= $data->name;

            $manager = $this->getManager();
            $filesystem = $manager->get($filesystemName);
            if($phpFile) {
                //file
                $filesystem->writeStream($path, fopen($phpFile['tmp_name'], 'r'), array('data'));
            }
            else {
                //folder
                $filesystem->createDir($path);
                $filesystem->setVisibility($path, 'public');
            }
            
            return $this->getRepository()->createFileEntityFromPath($this->getManager(), $filesystemName, $path, $this->getIdentity());

        } catch(\Exception $e) {
            throw $e;
            //todo fix response code etc.
            return new ApiProblem(400, $e->getMessage());
        }
    }

    /**
     * Implements filters:
     * - recursive
     * 
     * @param array $data
     * @return mixed
     */
    public function fetchAll($data = array())
    {
        if(isset($data['recursive'])) {
            //todo
            unset($data['recursive']);
        }
        return parent::fetchAll($data);
    }


    public function delete($id)
    {
        $items = $this->getRepository()->getPaginatorAdapter(['parent_id' => $id])->getItems(0, 9999);
        foreach($items as $item) {
            $this->delete($item['id']);
        }
        
        $item = $this->fetch($id);
        
        $filesystemName = $item['filesystem'];
        $path = $item['filesystem_path'];
        $filesystem = $this->getManager()->get($filesystemName);
        
        try {
            if($item['type'] === 'FILE') {
                $filesystem->delete($path);
            }
            else {
                $filesystem->deleteDir($path);
            }
        } catch (FileNotFoundException $e) {
            //intentional - we remove file index when file doesn't exists in the storage 
        }
        
        return parent::delete($id);
    }
    
    /**
     * @param FilesystemManager $manager
     */
    public function setManager(FilesystemManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return FilesystemManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return FileRepositoryInterface
     */
    public function getRepository()
    {
        return parent::getRepository();
    }


}
