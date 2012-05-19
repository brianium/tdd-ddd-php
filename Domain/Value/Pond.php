<?php
namespace Domain\Value;
use Domain\Repository\IFishRepository;
use Domain\Entity\Fish;
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

	public function remove($fishId = 0)
	{
		if(!$fishId)
		    $fishId = $this->getRandomFish()->getId();

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

	protected function removeFish($fishId)
	{
		$fish = $this->repo->delete($fishId);
		$this->count--;
        return $fish;
	}
}