<?php
namespace Test\Unit\Domain\Value;
use Domain\Entity\Fish;
use Domain\Value\Pond;
use Domain\Repository\FishRepository;
/**
* @author Brian Scaturro
*/
class PondTest extends FishingTestCase
{
	
	public function testConstructWithFishRepository()
	{
		$repo = $this->getMock('Domain\Repository\IFishRepository');
		$pond = new Pond($repo);
		$this->assertInstanceOf('Domain\Repository\IFishRepository',$pond->getRepo());
	}

	public function testPondStockCallsFishRepoStoreWithNewFishEntity()
	{
		$fish = new Fish();
		$repo = $this->getMock('Domain\Repository\IFishRepository');
		$repo->expects($this->once())
			 ->method('store')
			 ->with($this->equalTo($fish));

		$pond = new Pond($repo);
		$pond->stock($fish);
	}

	public function testPondStockIncrementsCount()
	{
		$fish = new Fish();
		$repo = $this->getMock('Domain\Repository\IFishRepository');

		$pond = new Pond($repo);
		$pond->stock($fish);
		$this->assertEquals(1,$pond->getFishCount());
	}

	public function testPondRemoveCallsFishRepoDeleteAndReturnsFish()
	{

		$fish = $this->getFishWithId(1);
		$repo = $this->getMock('Domain\Repository\IFishRepository');

		$repo->expects($this->once())
			 ->method('delete')
			 ->with($this->equalTo(1))
             ->will($this->returnValue($fish));

		$pond = new Pond($repo);
		$pond->stock($fish);
		$fish = $pond->remove(1);
		$this->assertEquals(1,$fish->getId());
	}

	public function testPondRemoveAltersCountToBeOneLess()
	{
		$fish = $this->getFishWithId(1);
		$repo = $this->getMock('Domain\Repository\IFishRepository');

		$pond = new Pond($repo);
		$pond->stock($fish);
		$this->assertEquals(1,$pond->getFishCount());
		$pond->remove(1);
		$this->assertEquals(0,$pond->getFishCount());
	}

	public function testRemoveNonExistentFishDoesntCallDeleteAndReturnsNull()
	{
		$repo = $this->getMock('Domain\Repository\IFishRepository');

		$repo->expects($this->once())
			 ->method('delete')
             ->with($this->equalTo(99))
             ->will($this->returnValue(null));

		$pond = new Pond($repo);
		$fish = $pond->remove(99);
		$this->assertNull($fish);
	}

	public function testRemoveWithNoIdCallsRepoAllAndRandomlyRemovesFish()
	{
		$repo = $this->getMock('Domain\Repository\IFishRepository');
		$fish = array(
			$this->getFishWithId(1),
			$this->getFishWithId(2),
			$this->getFishWithId(3)
		);

		$repo->expects($this->once())
			 ->method('all')
			 ->will($this->returnValue($fish));

        $repo->expects($this->once())
             ->method('delete')
             ->with($this->greaterThan(0))
             ->will($this->returnValue($fish[0]));

		$pond = new Pond($repo);
		$removed = $pond->remove();
		$this->assertInstanceOf('Domain\Entity\Fish',$removed);
	}
}