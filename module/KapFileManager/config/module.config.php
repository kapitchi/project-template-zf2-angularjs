<?php
return array(
    'controllers' => array(
        'factories' => array(
            'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => 'KapFileManager\\V1\\Rpc\\FilesystemSync\\FilesystemSyncControllerFactory',
            'KapFileManager\\V1\\Rpc\\FileAccess\\Controller' => 'KapFileManager\\V1\\Rpc\\FileAccess\\FileAccessControllerFactory',
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
            'kap-file-manager.rpc.file-access' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/file-access',
                    'defaults' => array(
                        'controller' => 'KapFileManager\\V1\\Rpc\\FileAccess\\Controller',
                        'action' => 'fileAccess',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'kap-file-manager.rest.file',
            1 => 'kap-file-manager.rpc.filesystem-sync',
            2 => 'kap-file-manager.rpc.file-access',
        ),
    ),
    'zf-rpc' => array(
        'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => array(
            'service_name' => 'FilesystemSync',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'kap-file-manager.rpc.filesystem-sync',
        ),
        'KapFileManager\\V1\\Rpc\\FileAccess\\Controller' => array(
            'service_name' => 'FileAccess',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'kap-file-manager.rpc.file-access',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'KapFileManager\\V1\\Rest\\File\\Controller' => 'HalJson',
            'KapFileManager\\V1\\Rpc\\FilesystemSync\\Controller' => 'Json',
            'KapFileManager\\V1\\Rpc\\FileAccess\\Controller' => 'Json',
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
            'KapFileManager\\V1\\Rpc\\FileAccess\\Controller' => array(
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
            'KapFileManager\\V1\\Rpc\\FileAccess\\Controller' => array(
                0 => 'application/vnd.kap-file-manager.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(),
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
        'KapFileManager\\V1\\Rpc\\FileAccess\\Controller' => array(
            'input_filter' => 'KapFileManager\\V1\\Rpc\\FileAccess\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'KapFileManager\\V1\\Rest\\File\\Validator' => array(
            0 => array(
                'name' => 'name',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
                'description' => '[optional] file/directory name

If not provided random name is generated.',
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            1 => array(
                'name' => 'parent_id',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
                'description' => '[optional] Either filesystem or this value has to be specified.',
            ),
            2 => array(
                'name' => 'filesystem',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
                'description' => '[optional] Either filesystem or this value has to be specified.

If filesystem is specified file/directory is created under root filesystem folder.',
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
        'KapFileManager\\V1\\Rpc\\FileAccess\\Validator' => array(
            0 => array(
                'name' => 'id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
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
