<?php

function load($namespace) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . ".php";  
    if (file_exists($file))
    {
        if(!class_exists($namespace))
            include $file;
    }
    else{                
        error_log("Class not found: " . $file);        
        throw new \Exception("Class not found: " . $file);
        //mail('leovolpatto@gmail.com', 'Classe nao encontrada', "Nao foi encontrado: $file   nem   $file1");
    }
}

spl_autoload_register(__NAMESPACE__ . '\load');
