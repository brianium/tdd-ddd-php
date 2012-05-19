<?php
define('DS',DIRECTORY_SEPARATOR);
function load_classes($name)
{
    $root = dirname(dirname(__FILE__));
    $vendorRoot = $root . DS . 'Infrastructure' . DS . 'Vendors';
    $path = str_replace('\\', DS, $name);
    $file = $root . DS . $path . '.php';
    $vendorFile = $vendorRoot . DS . $path . '.php';
    if(file_exists($file))
        require_once $file;
    elseif(file_exists($vendorFile))
        require_once $vendorFile;
}
spl_autoload_register('load_classes');

define('DBAL_XML',dirname(dirname(__FILE__)) . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'xml');

/**
 * Define environment for PDO
 */
putenv("DB_DSN=mysql:dbname=letsgofishing;host=127.0.0.1");
putenv("DB_USER=root");
putenv("DB_PASSWD=root");
putenv("DB_DBNAME=letsgofishing");
