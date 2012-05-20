<?php
namespace Domain\Repositories;
use Domain\Entities\Fish;
interface IFishRepository {
    function all();

    function fetch($fishId);

    function store(Fish $fish);

    function delete($fishId);
}