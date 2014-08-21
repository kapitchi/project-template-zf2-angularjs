<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapApigility;


use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbTableGateway;

class DbEntityRepository implements EntityRepositoryInterface
{
    protected $identifierName = 'id';

    /**
     * @var TableGateway
     */
    protected $table;
    
    public function __construct(TableGateway $table, $identifierName = null)
    {
        $this->table = $table;
    }

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data)
    {
        $this->table->insert($data);
        $id = $this->table->getLastInsertValue();
        
        $entity = $this->find($id);
        if(!$entity) {
            throw new \Exception("Entity not created?");
        }
        
        return $entity;
    }

    public function update($id, array $data)
    {
        $this->table->update($data, array($this->identifierName => $id));

        $entity = $this->find($id);
        if(!$entity) {
            throw new \Exception("Entity not updated?");
        }

        return $entity;
    }

    public function remove($id)
    {
        $item = $this->table->delete(array($this->identifierName => $id));
        return ($item > 0);
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param int $id The identifier.
     * @return object|null
     */
    public function find($id)
    {
        $resultSet = $this->table->select(array($this->identifierName => $id));
        return $resultSet->current();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return \Zend\Paginator\Adapter\AdapterInterface
     */
    public function getPaginatorAdapter(array $criteria, array $orderBy = null)
    {
        
        $data = (array)$criteria;

        //needed for integer values
        array_walk($data, function(&$item, $key) {
            if(is_int($item)) {
                $item = new Literal($item);
            }
        });
        
        return new DbTableGateway($this->table, $criteria);
    }
    
    /**
     * @param \Zend\Db\TableGateway\TableGateway $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTable()
    {
        return $this->table;
    }
    
}