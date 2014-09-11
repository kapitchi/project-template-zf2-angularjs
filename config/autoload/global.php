<?php
return array(
    'router' => array(
        'routes' => array(
            'oauth' => array(
                'options' => array(
                    'route' => '/oauth',
                ),
            ),
        ),
    ),
    'zf-oauth2' => array(
        'options' => array(
            'always_issue_new_refresh_token' => true,
        ),
    ),
);
