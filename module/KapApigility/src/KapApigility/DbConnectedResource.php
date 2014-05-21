<?php
/**
 * Kapitchi Zend Framework 2 Modules
 *
 * @copyright Copyright (c) 2012-2014 Kapitchi Open Source Community (http://kapitchi.com/open-source)
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace KapApigility;

use Zend\Db\Sql\Predicate\Literal;
use Zend\Paginator\Adapter\DbTableGateway;

class DbConnectedResource extends \ZF\Apigility\DbConnectedResource {
    
    public function fetchAll($data = array())
    {
        $data = (array)$data;
        
        //needed for integer values
        array_walk($data, function(&$item, $key) {
            if(is_int($item)) {
                $item = new Literal($item);
            }
        });
        
        $adapter = new DbTableGateway($this->table, $data);
        return new $this->collectionClass($adapter);
    }

} 