<?php

spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);


    $includePaths = [
        __DIR__ . '/src/controllers/',
        __DIR__ . '/src/models/',
        __DIR__ . '/',
        __DIR__ . '/src/repository/',
        __DIR__ . 'src/views'

    ];

    foreach ($includePaths as $path) {
        $filePath = $path . $classPath . '.php';


        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }

});

