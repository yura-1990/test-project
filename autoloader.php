<?php

function myAutoloader(): void
{
    spl_autoload_register(function ($className) {
        $namespace = 'App\\';
        $baseDir = __DIR__ . '/';

        $className = ltrim($className, '\\');
        $fileName = '';

        if (strpos($className, $namespace) === 0) {
            $className = substr($className, strlen($namespace));
            $fileName = $baseDir . str_replace('\\', '/', $className) . '.php';

            if (file_exists($fileName)) {
                require $fileName;
            }
        }
    });
}

spl_autoload_register('myAutoloader');