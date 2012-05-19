<?php
namespace Infrastructure\Services;
/**
 * @author Brian Scaturro
 */
class Autoloader
{
    static public function register()
    {
        spl_autoload_register(array(new self,'autoload'));
    }

    static public function autoload($name)
    {
        $root = dirname(dirname(dirname(__FILE__)));
        $vendorRoot = $root . DIRECTORY_SEPARATOR . 'Infrastructure' . DIRECTORY_SEPARATOR . 'Vendors';
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $name);
        $file = $root . DIRECTORY_SEPARATOR . $path . '.php';
        $vendorFile = $vendorRoot . DIRECTORY_SEPARATOR . $path . '.php';
        if(file_exists($file))
            require_once $file;
        elseif(file_exists($vendorFile))
            require_once $vendorFile;
    }
}