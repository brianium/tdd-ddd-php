<?php
namespace Test\Integration\Infrastructure\Persistence;
use \Doctrine\DBAL\Configuration;
use \Doctrine\DBAL\DriverManager;
use \Doctrine\ORM\Tools\Setup;
use \Doctrine\DBAL\Schema\Table;
/**
 * @author Brian Scaturro
 */
abstract class PersistenceTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    static private $pdo = null;
    private $conn = null;
    private $dbal = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
            $this->initDbal();
        }

        return $this->conn;
    }

    public function getDataSet()
    {
        $this->setupTable();
        return $this->createXmlDataSet(dirname(__FILE__) . DS . 'dataset.xml');
    }

    public function getDbal()
    {
        return $this->dbal;
    }

    protected function initDbal()
    {
        $isDevMode = true;
        $config = Setup::createXMLMetadataConfiguration(array(DBAL_XML),$isDevMode);
        $this->dbal = DriverManager::getConnection(array('pdo' => self::$pdo),$config);
    }

    protected function setupTable()
    {
        $sm = $this->dbal->getSchemaManager();
        $table = $this->getTableDefinition();
        $sm->dropAndCreateTable($table);
    }

    /**
     * @return \Doctrine\DBAL\Schema\Table $table
     */
    abstract protected function getTableDefinition();
}