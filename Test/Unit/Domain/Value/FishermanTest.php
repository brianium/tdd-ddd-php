<?php
namespace Test\Unit\Domain\Value;
use Domain\Value\Pond;
use Domain\Value\Fisherman;
/**
* @author Brian Scaturro
*/
class FishermanTest extends FishingTestCase
{
	private $repo;
	private $pond;
	private $fishFixture;

	public function setUp()
	{
		$this->repo = $this->getMock('Domain\Repository\IFishRepository');
		$this->pond = new Pond($this->repo);

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
		$fisherman = new Fisherman($this->pond);
		$this->assertInstanceOf('Domain\Value\Pond',$fisherman->getPond());
	}

	public function testCastMethodIncrementsCastCount()
	{
		$fisherman = new Fisherman($this->pond);
		$fisherman->cast();
		$this->assertEquals(1,$fisherman->getCastCount());
	}

	public function testThirdCastCallsRepoDeleteViaPondRemoveAndReturnsFish()
	{
		$this->setUpCatch(1);
		$fisherman = new Fisherman($this->pond);

		$this->repo->expects($this->once())
			 ->method('all')
			 ->will($this->returnValue($this->fishFixture));

		$one = $fisherman->cast();
		$this->assertNull($one);

		$two = $fisherman->cast();
		$this->assertNull($two);

		$three = $fisherman->cast();
		$this->assertInstanceOf('Domain\Entity\Fish',$three);
	}

	public function testACatchDecrementsPondCount()
	{
		$this->setUpCatch(1);
		$fisherman = new Fisherman($this->pond);

		$this->repo->expects($this->any())
			 ->method('all')
			 ->will($this->returnValue($this->fishFixture));

	    $this->assertEquals(3,$this->pond->getFishCount());
		$i = 0;
		while($i < 3) {
			$fisherman->cast();
			$i++;
		}

		$this->assertEquals(2,$this->pond->getFishCount());
	}

	public function testEveryThirdCastCallsRepoDeleteViaPondRemoveAndReturnsFish()
	{
		$this->setUpCatch(1);
		$fisherman = new Fisherman($this->pond);

		$this->repo->expects($this->any())
			 ->method('all')
			 ->will($this->returnValue($this->fishFixture));

		$i = 1;
		while($i < 7) {
			$fish = $fisherman->cast();
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
		$fisherman = new Fisherman($this->pond);

		$this->repo->expects($this->any())
			 ->method('all')
			 ->will($this->returnValue($this->fishFixture));

		$this->assertEquals(0,count($fisherman->getFish()));
		$i = 0;
		while ($i < 3) {
			$fisherman->cast();
			$i++;
		}
		$this->assertEquals(1,count($fisherman->getFish()));
	}

	protected function setUpCatch($id) {
		$fish = $this->getFishWithId($id);
		$this->repo->expects($this->any())
			 ->method('delete')
			 ->with($this->greaterThan(0))
			 ->will($this->returnValue($fish));
	}
}