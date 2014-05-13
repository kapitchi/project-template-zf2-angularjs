<?php

namespace KapSecurity\Authentication\Adapter;


use Zend\Authentication\Adapter\AdapterInterface as ZendAdapterInterface;

interface AdapterInterface extends ZendAdapterInterface
{
    public function getId();
}