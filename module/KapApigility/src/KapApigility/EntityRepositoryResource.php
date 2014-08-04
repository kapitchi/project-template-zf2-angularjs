<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapApigility;


use ZF\Rest\AbstractResourceListener;

class EntityRepositoryResource extends AbstractResourceListener
{
    /**
     * @var EntityRepositoryInterface
     */
    protected $repository;
    
    public function __construct(EntityRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }
    
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return $this->getRepository()->create((array)$data);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $ret = $this->getRepository()->remove($id);
        
        return true;
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        $criteria = $params->get('query', []);
        $orderBy = $params->get('order_by', []);
        
        $adapter = $this->getRepository()->getPaginatorAdapter($criteria, $orderBy);
        return new $this->collectionClass($adapter);
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return $this->getRepository()->update($id, (array)$data);
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $data = (array)$data;
        return $this->getRepository()->update($id, $data);
    }

    /**
     * @param \KapApigility\EntityRepositoryInterface $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \KapApigility\EntityRepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }
} 