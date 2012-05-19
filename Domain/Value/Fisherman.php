<?php
namespace Domain\Value;
/**
* @author Brian Scaturro
*/
class Fisherman
{
    private $pond;
    private $castCount;
    private $fish;

    public function __construct(Pond $pond)
    {
        $this->pond = $pond;
        $this->castCount = 0;
        $this->fish = array();
    }

    public function getPond()
    {
        return $this->pond;
    }

    public function cast()
    {
        $this->castCount++;
        if($this->castIsCatch())
            return $this->catchFish();
    }

    public function getCastCount()
    {
        return $this->castCount;
    }

    public function getFish()
    {
        return $this->fish;
    }

    protected function castIsCatch()
    {
        return $this->castCount % 3 == 0;
    }

    protected function catchFish()
    {
        $fish = $this->pond->remove();
        $this->fish[] = $fish;
        return $fish;
    }
}