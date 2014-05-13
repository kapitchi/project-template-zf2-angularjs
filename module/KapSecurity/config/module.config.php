<?php
return array(
    'router' => array(
        'routes' => array(
            'kap-security.authentication-callback' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/authentication-callback',
                    'defaults' => array(
                        'controller' => 'KapSecurity\\Controller\\AuthenticationCallbackController',
                        'action' => 'authenticationCallback',
                    ),
                ),
            ),
            'kap-security.rest.authentication-service' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/authentication-service[/:authentication_service_id]',
                    'defaults' => array(
                        'controller' => 'KapSecurity\\V1\\Rest\\AuthenticationService\\Controller',
                    ),
                ),
            ),
            'kap-security.rpc.authenticate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/authenticate',
                    'defaults' => array(
                        'controller' => 'KapSecurity\\V1\\Rpc\\Authenticate\\Controller',
                        'action' => 'authenticate',
                    ),
                ),
            ),
            'kap-security.rest.identity-authentication' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/identity-authentication[/:identity_authentication_id]',
                    'defaults' => array(
                        'controller' => 'KapSecurity\\V1\\Rest\\IdentityAuthentication\\Controller',
                    ),
                ),
            ),
            'kap-security.rest.identity' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/identity[/:identity_id]',
                    'defaults' => array(
                        'controller' => 'KapSecurity\\V1\\Rest\\Identity\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'kap-security.rest.authentication-service',
            1 => 'kap-security.rpc.authenticate',
            2 => 'kap-security.rpc.authentication-callback',
            3 => 'kap-security.rest.identity-authentication',
            4 => 'kap-security.rest.identity',
        ),
    ),
    'zf-rest' => array(
        'KapSecurity\\V1\\Rest\\AuthenticationService\\Controller' => array(
            'listener' => 'KapSecurity\\V1\\Rest\\AuthenticationService\\AuthenticationServiceResource',
            'route_name' => 'kap-security.rest.authentication-service',
            'route_identifier_name' => 'authentication_service_id',
            'collection_name' => 'authentication_service',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'KapSecurity\\V1\\Rest\\AuthenticationService\\AuthenticationServiceEntity',
            'collection_class' => 'KapSecurity\\V1\\Rest\\AuthenticationService\\AuthenticationServiceCollection',
            'service_name' => 'AuthenticationService',
        ),
        'KapSecurity\\V1\\Rest\\IdentityAuthentication\\Controller' => array(
            'listener' => 'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationResource',
            'route_name' => 'kap-security.rest.identity-authentication',
            'route_identifier_name' => 'identity_authentication_id',
            'collection_name' => 'identity_authentication',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationEntity',
            'collection_class' => 'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationCollection',
            'service_name' => 'identity_authentication',
        ),
        'KapSecurity\\V1\\Rest\\Identity\\Controller' => array(
            'listener' => 'KapSecurity\\V1\\Rest\\Identity\\IdentityResource',
            'route_name' => 'kap-security.rest.identity',
            'route_identifier_name' => 'identity_id',
            'collection_name' => 'identity',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'KapSecurity\\V1\\Rest\\Identity\\IdentityEntity',
            'collection_class' => 'KapSecurity\\V1\\Rest\\Identity\\IdentityCollection',
            'service_name' => 'identity',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'KapSecurity\\V1\\Rest\\AuthenticationService\\Controller' => 'HalJson',
            'KapSecurity\\V1\\Rpc\\Authenticate\\Controller' => 'Json',
            'KapSecurity\\V1\\Rpc\\AuthenticationCallback\\Controller' => 'Json',
            'KapSecurity\\V1\\Rest\\IdentityAuthentication\\Controller' => 'HalJson',
            'KapSecurity\\V1\\Rest\\Identity\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'KapSecurity\\V1\\Rest\\AuthenticationService\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'KapSecurity\\V1\\Rpc\\Authenticate\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'KapSecurity\\V1\\Rpc\\AuthenticationCallback\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'KapSecurity\\V1\\Rest\\IdentityAuthentication\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'KapSecurity\\V1\\Rest\\Identity\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'KapSecurity\\V1\\Rest\\AuthenticationService\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/json',
            ),
            'KapSecurity\\V1\\Rpc\\Authenticate\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/json',
            ),
            'KapSecurity\\V1\\Rpc\\AuthenticationCallback\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/json',
            ),
            'KapSecurity\\V1\\Rest\\IdentityAuthentication\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/json',
            ),
            'KapSecurity\\V1\\Rest\\Identity\\Controller' => array(
                0 => 'application/vnd.kap-security.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'KapSecurity\\V1\\Rest\\AuthenticationService\\AuthenticationServiceEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-security.rest.authentication-service',
                'route_identifier_name' => 'authentication_service_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapSecurity\\V1\\Rest\\AuthenticationService\\AuthenticationServiceCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-security.rest.authentication-service',
                'route_identifier_name' => 'authentication_service_id',
                'is_collection' => true,
            ),
            'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-security.rest.identity-authentication',
                'route_identifier_name' => 'identity_authentication_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-security.rest.identity-authentication',
                'route_identifier_name' => 'identity_authentication_id',
                'is_collection' => true,
            ),
            'KapSecurity\\V1\\Rest\\Identity\\IdentityEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-security.rest.identity',
                'route_identifier_name' => 'identity_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapSecurity\\V1\\Rest\\Identity\\IdentityCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-security.rest.identity',
                'route_identifier_name' => 'identity_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'db-connected' => array(
            'KapSecurity\\V1\\Rest\\AuthenticationService\\AuthenticationServiceResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'authentication_service',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapSecurity\\V1\\Rest\\AuthenticationService\\Controller',
                'entity_identifier_name' => 'id',
            ),
            'KapSecurity\\V1\\Rest\\IdentityAuthentication\\IdentityAuthenticationResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'identity_authentication',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapSecurity\\V1\\Rest\\IdentityAuthentication\\Controller',
                'entity_identifier_name' => 'id',
            ),
            'KapSecurity\\V1\\Rest\\Identity\\IdentityResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'identity',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapSecurity\\V1\\Rest\\Identity\\Controller',
                'entity_identifier_name' => 'id',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'KapSecurity\\V1\\Rpc\\Authenticate\\Controller' => 'KapSecurity\\V1\\Rpc\\Authenticate\\AuthenticateControllerFactory',
            'KapSecurity\\Controller\\AuthenticationCallbackController' => 'KapSecurity\\Controller\\AuthenticationCallbackControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'KapSecurity\\V1\\Rpc\\Authenticate\\Controller' => array(
            'service_name' => 'Authenticate',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'kap-security.rpc.authenticate',
        ),
    ),
    'service_manager' => array(
        'factories' => array(),
    ),
);
