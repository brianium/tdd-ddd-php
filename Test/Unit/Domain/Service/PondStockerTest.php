<?php
namespace Test\Unit\Domain\Service;
use Domain\Value\Pond;
use Domain\Repository\IFishRepository;
use Domain\Service\PondStocker;
use Domain\Entity\Fish;

/**
 * @author Brian Scaturro
 */
class PondStockerTest extends \PHPUnit_Framework_TestCase
{
    protected $repoMock;

    public function setUp()
    {
        $this->repoMock = $this->getMock('Domain\Repository\IFishRepository');
    }

    public function testConstructorTakesPond()
    {
        $stocker = $this->getPondStocker();

        $this->assertInstanceOf('Domain\Value\Pond',$stocker->getPond());
    }

    public function testStockNewFishWithEmptyRepoStocksPond()
    {
        $this->repoAllWillReturn(array());

        $this->repoMock->expects($this->exactly(3))
                       ->method('store');

        $stocker = $this->getPondStocker();

        $stocker->stock(3);

        $this->assertEquals(3,$stocker->getPond()->getFishCount());
    }

    public function testStockNewFishWithExistingFishStocksPond()
    {
        $fixture = array(new Fish(),new Fish(), new Fish());

        $this->repoAllWillReturn($fixture);

        $stocker = $this->getPondStocker();

        $stocker->stock(3);

        $this->assertEquals(6,$stocker->getPond()->getFishCount());
    }

    public function testPondIsEmptyReturnsTrueWhenNone()
    {
        $stocker = $this->getPondStocker();

        $this->assertTrue($stocker->pondIsEmpty());
    }

    public function testPondIsEmptyFalsWhenFishInPond()
    {
        $this->repoAllWillReturn(array());

        $stocker = $this->getPondStocker();

        $stocker->stock(3);

        $this->assertFalse($stocker->pondIsEmpty());
    }

    private function repoAllWillReturn($returnValue)
    {
        $this->repoMock->expects($this->once())
            ->method('all')
            ->will($this->returnValue($returnValue));
    }

    private function getPondStocker()
    {
        $stocker = new PondStocker(new Pond($this->repoMock));
        return $stocker;
    }
}
