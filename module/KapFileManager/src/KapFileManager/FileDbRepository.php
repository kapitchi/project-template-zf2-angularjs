<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapFileManager;


use KapApigility\DbEntityRepository;
use KapApigility\EntityRepository;
use KapFileManager\FileRepositoryInterface;
use KapFileManager\FilesystemManager;
use League\Flysystem\Directory;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemTests;
use ZF\MvcAuth\Identity\IdentityInterface;

class FileDbRepository extends DbEntityRepository implements FileRepositoryInterface
{
    
    protected function fetchAll(array $criteria)
    {
        $dbFiles = $this->getPaginatorAdapter($criteria)->getItems(0, 99999)->toArray();//todo get total items
        return $dbFiles;
    }

    public function createFileEntityFromPath(FilesystemManager $manager, $filesystemName, $path, IdentityInterface $identity)
    {
        $ownerId = $identity->getAuthenticationIdentity();

        $parent = $this->fetchParentByPath($filesystemName, $path);
        $parentId = $parent ? $parent['id'] : null;

        $meta = $manager->get($filesystemName)->getWithMetadata($path, ['mimetype', 'timestamp']);

        $data = [
            'filesystem' => $filesystemName,
            'filesystem_path' => $path,
            'parent_id' => $parentId,
            'owner_id' => $ownerId,
            'type' => strtoupper($meta['type']),
            'name' => $meta['basename'],
            'mime_type' => $meta['mimetype'],
            'create_time' => date(DATE_ATOM, $meta['timestamp'])
        ];
        
        if($meta['type'] == 'file') {
            $data['size'] = $meta['size'];
        }

        return $this->insertAndFetch($data);
    }

    public function fetchByPath($filesystemName, $path)
    {
        $entity = current($this->fetchAll([
            'filesystem' => $filesystemName,
            'filesystem_path' => $path
        ]));

        return $entity;
    }

    public function sync(FilesystemManager $manager, $filesystemName, IdentityInterface $identity = null, $syncPath = null)
    {
        //root folder sync
        if(empty($syncPath)) {
            $this->ensureRootFolder($filesystemName, $identity);
            $syncPath = '';
        }

        $parentItem = $this->fetchByPath($filesystemName, $syncPath);
        if(!$parentItem) {
            throw new \Exception("sync: DB item for path '$syncPath' doesn't exist");
        }

        $parentId = $parentItem['id'];

        $filesystem = $manager->get($filesystemName);
        $filesystemPaths = $filesystem->listPaths($syncPath);

        $dbFiles = $this->fetchAll([
            'filesystem' => $filesystemName,
            'parent_id' => $parentId
        ]);

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
            $deletedEntities[] = $this->update($id, ['filesystem_error' => 'NOT_EXISTS']);
        }

        $createdEntities = [];
        foreach($pathsToCreate as $path) {
            $createdEntities[] = $this->createFileEntityFromPath($manager, $filesystemName, $path, $identity);
        }

        $ret = [
            'created' => $createdEntities,
            'deleted' => $deletedEntities,
        ];

        foreach($dirPaths as $dirPath) {
            $ret = array_merge($this->sync($manager, $filesystemName, $identity, $dirPath), $ret);
        }

        return $ret;
    }

    protected function fetchParentByPath($filesystemName, $path)
    {
        $parentPath = dirname($path);
        if(empty($parentPath) || $parentPath === '.') {
            $parentPath = '';
        }

        return $this->fetchByPath($filesystemName, $parentPath);
    }

    protected function ensureRootFolder($filesystemName, IdentityInterface $identity)
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
            'create_time' => date(DATE_ATOM),
            'type' => 'DIR',
            'owner_id' => $identity->getAuthenticationIdentity()
        ];

        return $this->insertAndFetch($data);
    }

    protected function insertAndFetch(array $data)
    {
        //we need to call table directly because parent::create takes inputfilter even data array is set
        //$entity = parent::create($data);

        $this->table->insert($data);
        $id = $this->table->getLastInsertValue();
        return $this->find($id);
    }
    
} 