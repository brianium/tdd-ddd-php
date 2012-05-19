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
    private $pond;

    public function setUp()
    {
        $this->repo = $this->getMock('Domain\Repository\IFishRepository');
        $this->pond = new Pond($this->repo);
    }

    public function testConstructWithFishRepository()
    {
        $this->assertInstanceOf('Domain\Repository\IFishRepository',$this->pond->getRepo());
    }

    public function testPondStockCallsFishRepoStoreWithNewFishEntity()
    {
        $fish = new Fish();

        $this->repo->expects($this->once())
             ->method('store')
             ->with($this->equalTo($fish));


        $this->pond->stock($fish);
    }

    public function testPondStockIncrementsCount()
    {
        $fish = new Fish();

        $this->pond->stock($fish);

        $this->assertEquals(1,$this->pond->getFishCount());
    }

    public function testPondRemoveCallsFishRepoDeleteAndReturnsFish()
    {
        $fish = $this->getFishWithId(1);

        $this->repo->expects($this->once())
             ->method('delete')
             ->with($this->equalTo(1))
             ->will($this->returnValue($fish));

        $this->pond->stock($fish);

        $fish = $this->pond->remove(1);

        $this->assertEquals(1,$fish->getId());
    }

    public function testPondRemoveAltersCountToBeOneLess()
    {
        $fish = $this->getFishWithId(1);

        $this->pond->stock($fish);

        $this->assertEquals(1,$this->pond->getFishCount());

        $this->pond->remove(1);

        $this->assertEquals(0,$this->pond->getFishCount());
    }

    public function testRemoveNonExistentFishDoesntCallDeleteAndReturnsNull()
    {
        $this->repo->expects($this->once())
             ->method('delete')
             ->with($this->equalTo(99))
             ->will($this->returnValue(null));

        $fish = $this->pond->remove(99);
        $this->assertNull($fish);
    }

    public function testRemoveWithNoIdCallsRepoAllAndRandomlyRemovesFish()
    {
        $fish = array(
            $this->getFishWithId(1),
            $this->getFishWithId(2),
            $this->getFishWithId(3)
        );

        $this->setUpRepoAll($this->once(),$fish);

        $this->repo->expects($this->once())
             ->method('delete')
             ->with($this->greaterThan(0))
             ->will($this->returnValue($fish[0]));

        $removed = $this->pond->remove();
        $this->assertInstanceOf('Domain\Entity\Fish',$removed);
    }
}