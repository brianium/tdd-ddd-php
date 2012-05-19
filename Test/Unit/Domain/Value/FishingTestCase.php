<?php
namespace Test\Unit\Domain\Value;
use Domain\Entity\Fish;
/**
* @author Brian Scaturro
*/
abstract class FishingTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getFishWithId($id)
    {
        $fish = new Fish();
        $reflected = new \ReflectionObject($fish);
        $idProp = $reflected->getProperty('id');
        $idProp->setAccessible(true);
        $idProp->setValue($fish,$id);
        return $fish;
    }
}