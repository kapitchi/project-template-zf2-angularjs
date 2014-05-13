<?php
namespace KapSecurity\V1\Rest\AuthenticationService;

class AuthenticationServiceEntity extends \ArrayObject
{
    public function exchangeArray($input)
    {
        $input['enabled'] = (bool)$input['enabled'];
        parent::exchangeArray($input);
    }

}
