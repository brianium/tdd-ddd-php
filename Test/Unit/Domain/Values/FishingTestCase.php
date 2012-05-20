<?php
namespace Test\Unit\Domain\Values;
use Domain\Entities\Fish;
/**
* @author Brian Scaturro
*/
abstract class FishingTestCase extends \PHPUnit_Framework_TestCase
{
    protected $repo;

    protected function getFishWithId($id)
    {
        $fish = new Fish();
        $reflected = new \ReflectionObject($fish);
        $idProp = $reflected->getProperty('id');
        $idProp->setAccessible(true);
        $idProp->setValue($fish,$id);
        return $fish;
    }

    protected function setUpRepoAll($matcher,$returnValue)
    {
        $this->repo->expects($matcher)
            ->method('all')
            ->will($this->returnValue($returnValue));
    }
}