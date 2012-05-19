<?php
namespace Domain\Repository;
use Domain\Entity\Fish;
interface IFishRepository {
    function all();

    function fetch($fishId);

    function store(Fish $fish);

    function delete($fishId);
}