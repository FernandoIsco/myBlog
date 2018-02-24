<?php

return array(
    'cors' => array(
        'origins' => array(
            '*'
        ),
        'methods' => array(
            'HEAD', 'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'DELETE', 'OPTIONS'
        ),
        'headers' => array(
            'Content-Type', 'Content-Length', 'Authorization', 'Accept', 'X-Requested-With', 'Token', 'X_REQUESTED_WITH'
        )
    )
);