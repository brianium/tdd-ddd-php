<?php
define('DS',DIRECTORY_SEPARATOR);
require_once dirname(dirname(__FILE__)) . DS . 'Infrastructure' . DS . 'Services' . DS . 'Autoloader.php';
use Infrastructure\Services\Autoloader;

Autoloader::register();

define('DBAL_XML',dirname(dirname(__FILE__)) . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'xml');

require dirname(dirname(__FILE__)) . DS . 'environment.php';
