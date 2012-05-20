<?php
namespace Domain\Values;
use Domain\Repositories\IFishRepository;
use Domain\Entities\Fish;
/**
* @author Brian Scaturro
*/
class Pond
{
    private $repo;
    private $count;

    public function __construct(IFishRepository $repo)
    {
        $this->repo = $repo;
        $this->count = 0;
    }

    public function getRepo()
    {
        return $this->repo;
    }

    public function stock(Fish $fish)
    {
        $this->repo->store($fish);
        $this->count++;
    }

    public function getFishCount()
    {
        return $this->count;
    }

    public function deplete($fishId = 0)
    {
        if(!$fishId && $fish = $this->getRandomFish())
            $fishId = $fish->getId();

        return $this->removeFish($fishId);
    }

    protected function getRandomFish()
    {
        $all = $this->repo->all();
        if(!$all)
            return;

        $randKey = array_rand($all);
        return $all[$randKey];
    }

    public function removeFish($fishId)
    {
        $fish = $this->repo->delete($fishId);
        $this->count--;
        return $fish;
    }
}