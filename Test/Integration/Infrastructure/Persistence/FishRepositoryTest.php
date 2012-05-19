<?php
namespace Test\Integration\Infrastructure\Persistence;
use \Doctrine\DBAL\Schema\Table;
use Infrastructure\Persistence\FishRepository;
use Domain\Entity\Fish;
/**
* @author Brian Scaturro
*/
class FishRepositoryTest extends PersistenceTestCase
{
	public function testFetchAll()
	{
		$repo = new FishRepository();
		$fish = $repo->all();
		$this->assertEquals(3,count($fish));
	}

	public function testFetchAllWhenNoneIsEmptyArray()
	{
		$repo = new FishRepository();
		$repo->delete(1);
		$repo->delete(2);
		$repo->delete(3);
		$all = $repo->all();
		$this->assertEquals(0,count($all));
	}

	public function testFetchSingle()
	{
		$repo = new FishRepository();
		$fish = $repo->fetch(1);
		$this->assertEquals(1,$fish->getId());
	}

	public function testDeleteSingle()
	{
		$repo = new FishRepository();
		$repo->delete(1);
		$this->assertEquals(2,count($repo->all()));
	}

    public function testDeleteReturnsEntity()
    {
        $repo = new FishRepository();
        $fish = $repo->delete(1);
        $this->assertEquals(2,count($repo->all()));
        $this->assertInstanceOf('Domain\Entity\Fish',$fish);
    }

	public function testSaveNewFish()
	{
		$repo = new FishRepository();
		$fish = new Fish();
		$repo->store($fish);
		$this->assertEquals(4,count($repo->all()));
	}

	public function testUpdateExistingFish()
	{
		$repo = new FishRepository();
		$fish = $repo->fetch(1);
		$repo->store($fish);
		$this->assertEquals(3,count($repo->all()));
	}

	public function testDeleteUnknownReturnsNull()
	{
		$repo = new FishRepository();
		$this->assertNull($repo->delete(99));
	}

	public function getTableDefinition()
	{
		$table = new Table('fish');
		$table->addColumn('id','integer',array('unsigned' => true,'autoincrement' => true));
		$table->setPrimaryKey(array('id'));
		return $table;
	}
	
}