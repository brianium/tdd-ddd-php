<?php
namespace Test\Unit\Domain\Services;
use Domain\Services\Pond;
use Domain\Repositories\IFishRepository;
use Domain\Services\PondStocker;
use Domain\Entities\Fish;

/**
 * @author Brian Scaturro
 */
class PondStockerTest extends \PHPUnit_Framework_TestCase
{
    protected $repoMock;

    public function setUp()
    {
        $this->repoMock = $this->getMock('Domain\Repositories\IFishRepository');
        $this->stocker = new PondStocker(new Pond($this->repoMock));
    }

    public function testGetPondReturnsPondInstance()
    {
        $this->assertInstanceOf('Domain\Services\Pond',$this->stocker->getPond());
    }

    public function testStockNewFishWithEmptyRepoStocksPond()
    {
        $this->repoAllWillReturn(array());

        $this->repoMock->expects($this->exactly(3))
                       ->method('store');

        $this->stocker->stock(3);

        $this->assertEquals(3,$this->stocker->getPond()->getFishCount());
    }

    public function testStockNewFishWithExistingFishStocksPond()
    {
        $fixture = array(new Fish(),new Fish(), new Fish());

        $this->repoAllWillReturn($fixture);

        $this->stocker->stock(3);

        $this->assertEquals(6,$this->stocker->getPond()->getFishCount());
    }

    public function testPondIsEmptyReturnsTrueWhenNone()
    {
        $this->assertTrue($this->stocker->pondIsEmpty());
    }

    public function testPondIsEmptyFalsWhenFishInPond()
    {
        $this->repoAllWillReturn(array());

        $this->stocker->stock(3);

        $this->assertFalse($this->stocker->pondIsEmpty());
    }

    private function repoAllWillReturn($returnValue)
    {
        $this->repoMock->expects($this->once())
            ->method('all')
            ->will($this->returnValue($returnValue));
    }
}
