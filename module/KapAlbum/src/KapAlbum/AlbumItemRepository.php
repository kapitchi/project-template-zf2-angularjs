<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapAlbum;


use KapApigility\DbEntityRepository;
use KapApigility\Literal;
use KapFileManager\FileRepositoryInterface;
use KapFileManager\V1\Rest\File\FileEntity;
use Zend\Paginator\Adapter\DbSelect;

class AlbumItemRepository extends DbEntityRepository
{
    protected $fileRepository;
    
    public function __construct($table, $id, FileRepositoryInterface $fileRepository)
    {
        parent::__construct($table, $id);
        
        $this->fileRepository = $fileRepository;
    }
        
    public function find($id)
    {
        $entity = parent::find($id);
        
        //is this a file?
        if($entity['type'] === 'FILE') {
            $file = $this->fileRepository->find($entity['file_id']);
            $entity['file'] = $file;
        }
        
        return $entity;
    }
    
    public function getPaginatorAdapter(array $criteria, array $orderBy = null)
    {
        if(!empty($criteria['album_id'])) {
            $table = $this->getTable();
            $sql = $table->getSql();
            
            $select = $sql->select();
            $select->join('album_items', 'album_items.album_item_id = album_item.id', [], 'inner');
            
            $select->where([
                'album_items.album_id' => $criteria['album_id']
            ]);
            
//            $criteria['album_items.album_id'] = $criteria['album_id'];
//            unset($criteria['album_id']);

            $resultSetPrototype = $table->getResultSetPrototype();
            return new DbSelect($select, $sql, $resultSetPrototype);
        }
        
        return parent::getPaginatorAdapter($criteria, $orderBy);
    }

} 