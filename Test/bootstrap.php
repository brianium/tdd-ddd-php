<?php
define('DS',DIRECTORY_SEPARATOR);
require_once dirname(dirname(__FILE__)) . DS . 'Infrastructure' . DS . 'Services' . DS . 'Autoloader.php';
use Infrastructure\Services\Autoloader;

Autoloader::register();

define('DBAL_XML',dirname(dirname(__FILE__)) . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'xml');

/**
 * Define environment for PDO
 */
putenv("DB_DSN=mysql:dbname=letsgofishing;host=127.0.0.1");
putenv("DB_USER=root");
putenv("DB_PASSWD=root");
putenv("DB_DBNAME=letsgofishing");
