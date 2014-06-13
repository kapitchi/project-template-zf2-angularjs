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
            if(empty($data)) {
                $data = $this->getInputFilter()->getValues();
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
            if($phpFile) {
                //file
                $manager->get($filesystemName)->writeStream($path, fopen($phpFile['tmp_name'], 'r'), array('data'));
            }
            else {
                //folder
                $manager->get($filesystemName)->createDir($path);
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
        $paginator = $this->fetchAll(['parent_id' => $id]);
        foreach($paginator->getIterator() as $item) {
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
