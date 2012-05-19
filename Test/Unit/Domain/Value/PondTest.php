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
    public function setUp()
    {
        $this->repo = $this->getMock('Domain\Repository\IFishRepository');
    }

    public function testConstructWithFishRepository()
    {
        $pond = $this->getPond();

        $this->assertInstanceOf('Domain\Repository\IFishRepository',$pond->getRepo());
    }

    public function testPondStockCallsFishRepoStoreWithNewFishEntity()
    {
        $fish = new Fish();

        $this->repo->expects($this->once())
             ->method('store')
             ->with($this->equalTo($fish));

        $pond = $this->getPond();

        $pond->stock($fish);
    }

    public function testPondStockIncrementsCount()
    {
        $fish = new Fish();

        $pond = $this->getPond();

        $pond->stock($fish);

        $this->assertEquals(1,$pond->getFishCount());
    }

    public function testPondRemoveCallsFishRepoDeleteAndReturnsFish()
    {
        $fish = $this->getFishWithId(1);

        $this->repo->expects($this->once())
             ->method('delete')
             ->with($this->equalTo(1))
             ->will($this->returnValue($fish));

        $pond = $this->getPond();

        $pond->stock($fish);

        $fish = $pond->remove(1);

        $this->assertEquals(1,$fish->getId());
    }

    public function testPondRemoveAltersCountToBeOneLess()
    {
        $fish = $this->getFishWithId(1);

        $pond = $this->getPond();

        $pond->stock($fish);

        $this->assertEquals(1,$pond->getFishCount());

        $pond->remove(1);

        $this->assertEquals(0,$pond->getFishCount());
    }

    public function testRemoveNonExistentFishDoesntCallDeleteAndReturnsNull()
    {
        $this->repo->expects($this->once())
             ->method('delete')
             ->with($this->equalTo(99))
             ->will($this->returnValue(null));

        $pond = $this->getPond();
        $fish = $pond->remove(99);
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

        $pond = $this->getPond();
        $removed = $pond->remove();
        $this->assertInstanceOf('Domain\Entity\Fish',$removed);
    }

    private function getPond()
    {
        $pond = new Pond($this->repo);
        return $pond;
    }
}