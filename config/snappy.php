<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => env('WKHTMLTOPDF_PATH'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
        'enable-javascript' => true,
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => env('WKHTMLTOPDF_PATH'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
