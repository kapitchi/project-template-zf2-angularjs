<?php
return array(
    'router' => array(
        'routes' => array(
            'kap-album.rest.album' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/album[/:album_id]',
                    'defaults' => array(
                        'controller' => 'KapAlbum\\V1\\Rest\\Album\\Controller',
                    ),
                ),
            ),
            'kap-album.rest.album-item' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/album_item[/:album_item_id]',
                    'defaults' => array(
                        'controller' => 'KapAlbum\\V1\\Rest\\AlbumItem\\Controller',
                    ),
                ),
            ),
            'kap-album.rest.album-items' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/album_items[/:album_items_id]',
                    'defaults' => array(
                        'controller' => 'KapAlbum\\V1\\Rest\\AlbumItems\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'kap-album.rest.album',
            1 => 'kap-album.rest.album-item',
            2 => 'kap-album.rest.album-items',
        ),
    ),
    'zf-rest' => array(
        'KapAlbum\\V1\\Rest\\Album\\Controller' => array(
            'listener' => 'KapAlbum\\V1\\Rest\\Album\\AlbumResource',
            'route_name' => 'kap-album.rest.album',
            'route_identifier_name' => 'album_id',
            'collection_name' => 'album',
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
            'entity_class' => 'KapAlbum\\V1\\Rest\\Album\\AlbumEntity',
            'collection_class' => 'KapAlbum\\V1\\Rest\\Album\\AlbumCollection',
            'service_name' => 'album',
        ),
        'KapAlbum\\V1\\Rest\\AlbumItem\\Controller' => array(
            'listener' => 'KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemResource',
            'route_name' => 'kap-album.rest.album-item',
            'route_identifier_name' => 'album_item_id',
            'collection_name' => 'album_item',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'query',
            ),
            'page_size' => 25,
            'page_size_param' => 'page_size',
            'entity_class' => 'KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemEntity',
            'collection_class' => 'KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemCollection',
            'service_name' => 'album_item',
        ),
        'KapAlbum\\V1\\Rest\\AlbumItems\\Controller' => array(
            'listener' => 'KapAlbum\\V1\\Rest\\AlbumItems\\AlbumItemsResource',
            'route_name' => 'kap-album.rest.album-items',
            'route_identifier_name' => 'album_items_id',
            'collection_name' => 'album_items',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'query',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'KapAlbum\\V1\\Rest\\AlbumItems\\AlbumItemsEntity',
            'collection_class' => 'KapAlbum\\V1\\Rest\\AlbumItems\\AlbumItemsCollection',
            'service_name' => 'album_items',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'KapAlbum\\V1\\Rest\\Album\\Controller' => 'HalJson',
            'KapAlbum\\V1\\Rest\\AlbumItem\\Controller' => 'HalJson',
            'KapAlbum\\V1\\Rest\\AlbumItems\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'KapAlbum\\V1\\Rest\\Album\\Controller' => array(
                0 => 'application/vnd.kap-album.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItem\\Controller' => array(
                0 => 'application/vnd.kap-album.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItems\\Controller' => array(
                0 => 'application/vnd.kap-album.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'KapAlbum\\V1\\Rest\\Album\\Controller' => array(
                0 => 'application/vnd.kap-album.v1+json',
                1 => 'application/json',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItem\\Controller' => array(
                0 => 'application/vnd.kap-album.v1+json',
                1 => 'application/json',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItems\\Controller' => array(
                0 => 'application/vnd.kap-album.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'KapAlbum\\V1\\Rest\\Album\\AlbumEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-album.rest.album',
                'route_identifier_name' => 'album_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapAlbum\\V1\\Rest\\Album\\AlbumCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-album.rest.album',
                'route_identifier_name' => 'album_id',
                'is_collection' => true,
            ),
            'KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-album.rest.album-item',
                'route_identifier_name' => 'album_item_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-album.rest.album-item',
                'route_identifier_name' => 'album_item_id',
                'is_collection' => true,
            ),
            'KapAlbum\\V1\\Rest\\AlbumItems\\AlbumItemsEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-album.rest.album-items',
                'route_identifier_name' => 'album_items_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItems\\AlbumItemsCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-album.rest.album-items',
                'route_identifier_name' => 'album_items_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'db-connected' => array(
            'KapAlbum\\V1\\Rest\\Album\\AlbumResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'album',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapAlbum\\V1\\Rest\\Album\\Controller',
                'entity_identifier_name' => 'id',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'album_item',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapAlbum\\V1\\Rest\\AlbumItem\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'KapAlbum\\V1\\Rest\\AlbumItem\\AlbumItemResource\\Table',
            ),
            'KapAlbum\\V1\\Rest\\AlbumItems\\AlbumItemsResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'album_items',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapAlbum\\V1\\Rest\\AlbumItems\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'KapAlbum\\V1\\Rest\\AlbumItems\\AlbumItemsResource\\Table',
            ),
        ),
    ),
    'zf-content-validation' => array(
        'KapAlbum\\V1\\Rest\\AlbumItem\\Controller' => array(
            'input_filter' => 'KapAlbum\\V1\\Rest\\AlbumItem\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'KapAlbum\\V1\\Rest\\AlbumItem\\Validator' => array(
            0 => array(
                'name' => 'type',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            1 => array(
                'name' => 'title',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            2 => array(
                'name' => 'description',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            3 => array(
                'name' => 'url',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            4 => array(
                'name' => 'file_id',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
        ),
    ),
);
