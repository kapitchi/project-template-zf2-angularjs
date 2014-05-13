<?php

namespace KapSecurity\V1\Rest\IdentityAuthentication;

use Zend\Db\Sql\Literal;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbTableGateway as TableGatewayPaginator;
use ZF\Apigility\DbConnectedResource;

class IdentityAuthenticationResource extends DbConnectedResource
{
    public function fetchAll($data = array())
    {
        //todo https://bitbucket.org/matuszeman/creditors-drazobnik/src/a9d92c1bf018/module/KapitchiSearch/?at=master
        
        //needed for integer values
        array_walk($data, function(&$item, $key) {
            if(is_int($item)) {
                $item = new Literal($item);
            }
        });
        
        $adapter = new TableGatewayPaginator($this->table, $data);
        return new $this->collectionClass($adapter);
        
        return parent::fetchAll($data);
    }

}