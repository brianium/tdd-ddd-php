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

    public function testPondDepleteCallsFishRepoDeleteAndReturnsFish()
    {
        $fish = $this->getFishWithId(1);

        $this->repo->expects($this->once())
             ->method('delete')
             ->with($this->equalTo(1))
             ->will($this->returnValue($fish));

        $this->pond->stock($fish);

        $fish = $this->pond->deplete(1);

        $this->assertEquals(1,$fish->getId());
    }

    public function testPondDepleteAltersCountToBeOneLess()
    {
        $fish = $this->getFishWithId(1);

        $this->pond->stock($fish);

        $this->assertEquals(1,$this->pond->getFishCount());

        $this->pond->deplete(1);

        $this->assertEquals(0,$this->pond->getFishCount());
    }

    public function testDepleteNonExistentFishDoesntCallDeleteAndReturnsNull()
    {
        $this->repo->expects($this->once())
             ->method('delete')
             ->with($this->equalTo(99))
             ->will($this->returnValue(null));

        $fish = $this->pond->deplete(99);
        $this->assertNull($fish);
    }

    public function testDepleteWithNoIdCallsRepoAllAndRandomlyRemovesFish()
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

        $removed = $this->pond->deplete();
        $this->assertInstanceOf('Domain\Entity\Fish',$removed);
    }
}