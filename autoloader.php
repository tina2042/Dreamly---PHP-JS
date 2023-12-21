<?php

spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    
    $includePaths = [
        __DIR__ . '/src/controllers/',
        __DIR__.'/src/models/',
        __DIR__.'/',
        __DIR__.'/src/repository/'
        
    ];

    // Iteracja przez dodatkowe ścieżki
    foreach ($includePaths as $path) {
        $filePath = $path . $classPath . '.php';

        // Sprawdź, czy plik istnieje, a następnie go załaduj
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }

});

