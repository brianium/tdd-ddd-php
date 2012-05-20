<?php
namespace Test\Integration\Infrastructure\Persistence;
use \Doctrine\DBAL\Schema\Table;
use Infrastructure\Persistence\FishRepository;
use Domain\Entities\Fish;
/**
* @author Brian Scaturro
*/
class FishRepositoryTest extends PersistenceTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = new FishRepository();
    }

    public function testFetchAll()
    {
        $fish = $this->repo->all();
        $this->assertEquals(3,count($fish));
    }

    public function testFetchAllWhenNoneIsEmptyArray()
    {
        $this->repo->delete(1);
        $this->repo->delete(2);
        $this->repo->delete(3);
        $all = $this->repo->all();
        $this->assertEquals(0,count($all));
    }

    public function testFetchSingle()
    {
        $fish = $this->repo->fetch(1);
        $this->assertEquals(1,$fish->getId());
    }

    public function testDeleteSingle()
    {
        $this->repo->delete(1);
        $this->assertEquals(2,count($this->repo->all()));
    }

    public function testDeleteReturnsEntity()
    {
        $fish = $this->repo->delete(1);
        $this->assertEquals(2,count($this->repo->all()));
        $this->assertInstanceOf('Domain\Entities\Fish',$fish);
    }

    public function testSaveNewFish()
    {
        $fish = new Fish();
        $this->repo->store($fish);
        $this->assertEquals(4,count($this->repo->all()));
    }

    public function testUpdateExistingFish()
    {
        $fish = $this->repo->fetch(1);
        $this->repo->store($fish);
        $this->assertEquals(3,count($this->repo->all()));
    }

    public function testDeleteUnknownReturnsNull()
    {
        $this->assertNull($this->repo->delete(99));
    }

    public function getTableDefinition()
    {
        $table = new Table('fish');
        $table->addColumn('id','integer',array('unsigned' => true,'autoincrement' => true));
        $table->setPrimaryKey(array('id'));
        return $table;
    }
	
}