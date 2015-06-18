<?php

function load($namespace) {
    
    if(class_exists($namespace))
    {
        return;
    }
    
    $projectRootPath = __DIR__ .  DIRECTORY_SEPARATOR;
    $file =  $projectRootPath . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . ".php";
    
    if (file_exists($file))
        include $file;
    else{                
        throw new \Exception("Class not found: " . $file);
    }
}

spl_autoload_register(__NAMESPACE__ . '\load');