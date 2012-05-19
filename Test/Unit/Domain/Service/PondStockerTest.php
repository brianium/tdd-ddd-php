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
        $stocker = new PondStocker(new Pond($this->repoMock));
        $this->assertInstanceOf('Domain\Value\Pond',$stocker->getPond());
    }

    public function testStockNewFishWithEmptyRepoStocksPond()
    {
        $this->repoMock->expects($this->once())
                       ->method('all')
                       ->will($this->returnValue(array()));

        $this->repoMock->expects($this->exactly(3))
                       ->method('store');

        $stocker = new PondStocker(new Pond($this->repoMock));
        $stocker->stock(3);
        $this->assertEquals(3,$stocker->getPond()->getFishCount());
    }

    public function testStockNewFishWithExistingFishStocksPond()
    {
        $fixture = array(new Fish(),new Fish(), new Fish());
        $this->repoMock->expects($this->once())
                       ->method('all')
                       ->will($this->returnValue($fixture));
        $stocker = new PondStocker(new Pond($this->repoMock));
        $stocker->stock(3);
        $this->assertEquals(6,$stocker->getPond()->getFishCount());
    }
}
