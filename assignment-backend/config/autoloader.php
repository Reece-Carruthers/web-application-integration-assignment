<?php

/**
 * @param $className
 * @return void
 * @throws Exception
 *
 * @author Reece Carruthers (W19011575) and module
 */
function autoloader($className): void
{
    $filename = $className . ".php";
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
    if (!is_readable($filename)) {
        throw new Exception("File '$filename' not found");
    }
    require $filename;
}