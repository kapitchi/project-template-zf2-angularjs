<?php
return array(
    'controllers' => array(
        'factories' => array(
            'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => 'KapFileManager\\V1\\Rpc\\FilesystemSync\\FilesystemSyncControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'kap-file-manager.rest.file' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/file[/:file_id]',
                    'defaults' => array(
                        'controller' => 'KapFileManager\\V1\\Rest\\File\\Controller',
                    ),
                ),
            ),
            'kap-file-manager.rpc.filesystem-sync' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/filesystem-sync',
                    'defaults' => array(
                        'controller' => 'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller',
                        'action' => 'filesystemSync',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'kap-file-manager.rest.file',
            1 => 'kap-file-manager.rpc.filesystem-sync',
        ),
    ),
    'zf-rpc' => array(
        'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => array(
            'service_name' => 'FilesystemSync',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'kap-file-manager.rpc.filesystem-sync',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'KapFileManager\\V1\\Rest\\File\\Controller' => 'HalJson',
            'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'KapFileManager\\V1\\Rest\\File\\Controller' => array(
                0 => 'application/vnd.kap-file-manager.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => array(
                0 => 'application/vnd.kap-file-manager.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'KapFileManager\\V1\\Rest\\File\\Controller' => array(
                0 => 'application/vnd.kap-file-manager.v1+json',
                1 => 'application/json',
            ),
            'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => array(
                0 => 'application/vnd.kap-file-manager.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'KapFileManager\\V1\\Rest\\File\\FileResource' => 'KapFileManager\\V1\\Rest\\File\\FileResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'KapFileManager\\V1\\Rest\\File\\Controller' => array(
            'listener' => 'KapFileManager\\V1\\Rest\\File\\FileResource',
            'route_name' => 'kap-file-manager.rest.file',
            'route_identifier_name' => 'file_id',
            'collection_name' => 'file',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'DELETE',
                2 => 'POST',
                3 => 'PUT',
                4 => 'PATCH',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'KapFileManager\\V1\\Rest\\File\\FileEntity',
            'collection_class' => 'KapFileManager\\V1\\Rest\\File\\FileCollection',
            'service_name' => 'file',
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'KapFileManager\\V1\\Rest\\File\\FileEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-file-manager.rest.file',
                'route_identifier_name' => 'file_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapFileManager\\V1\\Rest\\File\\FileCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-file-manager.rest.file',
                'route_identifier_name' => 'file_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-content-validation' => array(
        'KapFileManager\\V1\\Rest\\File\\Controller' => array(
            'input_filter' => 'KapFileManager\\V1\\Rest\\File\\Validator',
        ),
        'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => array(
            'input_filter' => 'KapFileManager\\V1\\Rpc\\FilesystemSync\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'KapFileManager\\V1\\Rest\\File\\Validator' => array(
            0 => array(
                'name' => 'name',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'description' => 'file/directory name',
            ),
            1 => array(
                'name' => 'parent_id',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
        ),
        'KapFileManager\\V1\\Rpc\\FilesystemSync\\Validator' => array(
            0 => array(
                'name' => 'filesystem',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
            ),
            1 => array(
                'name' => 'path',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'db-connected' => array(
            'KapFileManager\\V1\\Rest\\File\\FileResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'file',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapFileManager\\V1\\Rest\\File\\Controller',
                'entity_identifier_name' => 'id',
            ),
        ),
    ),
);
