<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapApigility;


interface EntityRepositoryInterface {

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data);

    public function update($id, array $data);

    public function remove($id);

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param int $id The identifier.
     * @return object The object.
     */
    public function find($id);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return \Zend\Paginator\Adapter\AdapterInterface
     */
    public function getPaginatorAdapter(array $criteria, array $orderBy = null);
}