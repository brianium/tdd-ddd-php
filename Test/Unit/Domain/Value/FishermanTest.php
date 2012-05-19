<?php
namespace Test\Unit\Domain\Value;
use Domain\Value\Pond;
use Domain\Value\Fisherman;
/**
* @author Brian Scaturro
*/
class FishermanTest extends FishingTestCase
{
    private $pond;
    private $fisherman;
    private $fishFixture;

    public function setUp()
    {
        $this->repo = $this->getMock('Domain\Repository\IFishRepository');
        $this->pond = new Pond($this->repo);
        $this->fisherman = new Fisherman($this->pond);

        $this->fishFixture = array(
            $this->getFishWithId(1),
            $this->getFishWithId(2),
            $this->getFishWithId(3)
        );

        foreach($this->fishFixture as $fish) {
            $this->pond->stock($fish);
        }
    }

    public function testConstructWithPond()
    {
        $this->assertInstanceOf('Domain\Value\Pond',$this->fisherman->getPond());
    }

    public function testCastMethodIncrementsCastCount()
    {
        $this->fisherman->cast();
        $this->assertEquals(1,$this->fisherman->getCastCount());
    }

    public function testThirdCastCallsRepoDeleteViaPondRemoveAndReturnsFish()
    {
        $this->setUpCatch(1);

        $this->setUpRepoAll($this->once(),$this->fishFixture);

        $one = $this->fisherman->cast();
        $this->assertNull($one);

        $two = $this->fisherman->cast();
        $this->assertNull($two);

        $three = $this->fisherman->cast();
        $this->assertInstanceOf('Domain\Entity\Fish',$three);
    }

    public function testACatchDecrementsPondCount()
    {
        $this->setUpCatch(1);

        $this->setUpRepoAll($this->any(),$this->fishFixture);

        $this->assertEquals(3,$this->pond->getFishCount());
        $this->cast(3);

        $this->assertEquals(2,$this->pond->getFishCount());
    }

    public function testEveryThirdCastCallsRepoDeleteViaPondRemoveAndReturnsFish()
    {
        $this->setUpCatch(1);

        $this->setUpRepoAll($this->any(),$this->fishFixture);

        $i = 1;
        while($i < 7) {
            $fish = $this->fisherman->cast();
            if($i % 3 == 0) {
                $this->assertInstanceOf('Domain\Entity\Fish',$fish);
            }
            $i++;
        }

        $this->assertEquals(1,$this->pond->getFishCount());
    }

    public function testCatchAddsToFish()
    {
        $this->setUpCatch(1);

        $this->setUpRepoAll($this->any(),$this->fishFixture);

        $this->assertEquals(0,count($this->fisherman->getFish()));

        $this->cast(3);

        $this->assertEquals(1,count($this->fisherman->getFish()));
    }

    protected function cast($numCasts)
    {
        $i = 0;
        while ($i < $numCasts) {
            $this->fisherman->cast();
            $i++;
        }
    }

    protected function setUpCatch($id) {
        $fish = $this->getFishWithId($id);
        $this->repo->expects($this->any())
             ->method('delete')
             ->with($this->greaterThan(0))
             ->will($this->returnValue($fish));
    }
}