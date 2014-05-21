<?php
namespace KapFileManager\V1\Rest\File;

use KapApigility\DbConnectedResource;
use KapFileManager\FilesystemManager;
use League\Flysystem\Directory;
use League\Flysystem\MountManager;
use Zend\Db\TableGateway\TableGatewayInterface as TableGateway;
use ZF\ApiProblem\ApiProblem;

class FileResource extends DbConnectedResource
{
    /**
     * @var FilesystemManager $manager
     */
    protected $manager;
    
    public function __construct(TableGateway $table, $identifierName, $collectionClass, FilesystemManager $manager)
    {
        parent::__construct($table, $identifierName, $collectionClass);
        
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
            $filesystemName = 'dropbox';
            
            $event = $this->getEvent();

            $values = $this->getInputFilter()->getValues();

            $parent = $this->fetch($values['parent_id']);
            if(!$parent) {
                throw \Exception("Can't find item '{$values['parent_id']}'");
            }
            
            $filesystemName = $parent['filesystem'];
            $path = $parent['filesystem_path'] ? $parent['filesystem_path'] . '/' : '';
            $path .= $values['name'];
            
            $phpFile = null;
            
            //no event? we can't create uploaded files obviously as there are no sent
            if($event) {
                $request = $event->getRequest();

                $phpFiles = $request->getFiles()->toArray();
                if(count($phpFiles) > 1) {
                    //todo fix response code etc.
                    return new ApiProblem(400, 'More than one files?');
                }

                $phpFile = current($phpFiles);
            }

            $manager = $this->getManager();
            if($phpFile) {
                $manager->get($filesystemName)->writeStream($path, fopen($phpFile['tmp_name'], 'r'), array('data'));
            }
            else {
                $manager->get($filesystemName)->createDir($path);
            }
            
            return $this->createFileEntityFromPath($filesystemName, $path);

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
        if($item['type'] === 'FILE') {
            $filesystem->delete($path);
        }
        else {
            $filesystem->deleteDir($path);
        }
        
        return parent::delete($id);
    }
    
    public function sync($filesystemName, $syncPath = null)
    {
        $ownerId = 1;
        
        //root folder sync
        if(empty($syncPath)) {
            $this->ensureRootFolder($filesystemName, $ownerId);
            $syncPath = '';
        }

        $parentItem = $this->fetchByPath($filesystemName, $syncPath);
        if(!$parentItem) {
            throw new \Exception("sync: DB item for path '$syncPath' doesn't exist");
        }

        $parentId = $parentItem['id'];
        
        $filesystem = $this->getManager()->get($filesystemName);
        $filesystemPaths = $filesystem->listPaths($syncPath);

        $dbFiles = $this->fetchAll([
            'filesystem' => $filesystemName,
            'parent_id' => $parentId
        ])->getCurrentItems()->toArray();

        $toDeleteEntityIds = [];
        $dbPaths = [];
        foreach($dbFiles as $dbFile) {
            $dbPaths[] = $dbFile['filesystem_path'];
            
            if($dbFile['filesystem_path'] && !in_array($dbFile['filesystem_path'], $filesystemPaths)) {
                $toDeleteEntityIds[] = $dbFile['id'];
            }
        }
        
        $pathsToCreate = [];
        $dirPaths = [];
        foreach($filesystemPaths as $path) {
            if($filesystem->get($path) instanceof Directory) {
                $dirPaths[] = $path;
            }
            
            if(!in_array($path, $dbPaths)) {
                $pathsToCreate[] = $path;
            }
        }

        $deletedEntities = [];
        foreach($toDeleteEntityIds as $id) {
            //$deletedEntities[] = $this->fetch($id);
            //$this->delete($id);
            $deletedEntities[] = $this->patch($id, ['filesystem_error' => 'NOT_EXISTS']);
        }
        
        $createdEntities = [];
        foreach($pathsToCreate as $path) {
            $createdEntities[] = $this->createFileEntityFromPath($filesystemName, $path);
        }
        
        $ret = [
            'created' => $createdEntities,
            'error' => $deletedEntities,
        ];
        
        foreach($dirPaths as $dirPath) {
            $ret = array_merge($this->sync($filesystemName, $dirPath), $ret);
        }
        
        return $ret;
    }

    protected function ensureRootFolder($filesystemName, $ownerId)
    {
        $rootItem = $this->fetchByPath($filesystemName, '');
        if($rootItem) {
            return $rootItem;
        }

        $data = [
            'filesystem' => $filesystemName,
            'filesystem_path' => '',
            'name' => '',
            'parent_id' => null,
            'created_time' => date(DATE_ATOM),
            'type' => 'ROOT',
            'owner_id' => 1
        ];

        return $this->insertAndFetch($data);
    }

    protected function fetchParentByPath($filesystemName, $path)
    {
        $parentPath = dirname($path);
        if(empty($parentPath) || $parentPath === '.') {
            $parentPath = '';
        }
        
        return $this->fetchByPath($filesystemName, $parentPath);
    }
    
    protected function fetchByPath($filesystemName, $path)
    {
        $entity = $this->fetchAll([
            'filesystem' => $filesystemName,
            'filesystem_path' => $path
        ])->getCurrentItems()->current();
        
        return $entity;
    }
    
    protected function createFileEntityFromPath($filesystemName, $path)
    {
        $ownerId = 1;
        
        $parent = $this->fetchParentByPath($filesystemName, $path);
        $parentId = $parent ? $parent['id'] : null;
        
        $meta = $this->getManager()->get($filesystemName)->getWithMetadata($path, ['mimetype', 'timestamp']);

        $data = [
            'filesystem' => $filesystemName,
            'filesystem_path' => $path,
            'parent_id' => $parentId,
            'owner_id' => $ownerId,
            'type' => strtoupper($meta['type']),
            'name' => $meta['basename'],
            'mime_type' => $meta['mimetype'],
            'created_time' => date(DATE_ATOM, $meta['timestamp'])
        ];

        return $this->insertAndFetch($data);
    }
    
    protected function insertAndFetch(array $data)
    {
        //we need to call table directly because parent::create takes inputfilter even data array is set
        //$entity = parent::create($data);

        $this->table->insert($data);
        $id = $this->table->getLastInsertValue();
        return $this->fetch($id);
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
}
