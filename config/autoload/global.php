<?php
return array(
    'router' => array(
        'routes' => array(),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            // Toggle the following to true to change the ACL creation to
            // require an authenticated user by default, and thus selectively
            // allow unauthenticated users based on the rules.
            'deny_by_default' => false,
        )
    ),
);
