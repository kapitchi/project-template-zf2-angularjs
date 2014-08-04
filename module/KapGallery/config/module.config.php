<?php
return array(
    'router' => array(
        'routes' => array(
            'kap-gallery.rest.gallery-item' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/gallery_item[/:gallery_item_id]',
                    'defaults' => array(
                        'controller' => 'KapGallery\\V1\\Rest\\GalleryItem\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'kap-gallery.rest.gallery-item',
        ),
    ),
    'zf-rest' => array(
        'KapGallery\\V1\\Rest\\GalleryItem\\Controller' => array(
            'listener' => 'KapGallery\\V1\\Rest\\GalleryItem\\GalleryItemResource',
            'route_name' => 'kap-gallery.rest.gallery-item',
            'route_identifier_name' => 'gallery_item_id',
            'collection_name' => 'gallery_item',
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
            'entity_class' => 'KapGallery\\V1\\Rest\\GalleryItem\\GalleryItemEntity',
            'collection_class' => 'KapGallery\\V1\\Rest\\GalleryItem\\GalleryItemCollection',
            'service_name' => 'gallery_item',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'KapGallery\\V1\\Rest\\GalleryItem\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'KapGallery\\V1\\Rest\\GalleryItem\\Controller' => array(
                0 => 'application/vnd.kap-gallery.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'KapGallery\\V1\\Rest\\GalleryItem\\Controller' => array(
                0 => 'application/vnd.kap-gallery.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'KapGallery\\V1\\Rest\\GalleryItem\\GalleryItemEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-gallery.rest.gallery-item',
                'route_identifier_name' => 'gallery_item_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'KapGallery\\V1\\Rest\\GalleryItem\\GalleryItemCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'kap-gallery.rest.gallery-item',
                'route_identifier_name' => 'gallery_item_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'db-connected' => array(
            'KapGallery\\V1\\Rest\\GalleryItem\\GalleryItemResource' => array(
                'adapter_name' => 'DefaultDbAdapter',
                'table_name' => 'album_item',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'KapGallery\\V1\\Rest\\GalleryItem\\Controller',
                'entity_identifier_name' => 'id',
            ),
        ),
    ),
);
