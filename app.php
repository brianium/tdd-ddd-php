<?php
use \Infrastructure\Services\Autoloader;
use \Infrastructure\Services\Console;
use \Domain\Values\Pond;
use \Infrastructure\Persistence\FishRepository;
use \Domain\Services\PondStocker;
use \Domain\Values\Fisherman;

define('DS',DIRECTORY_SEPARATOR);
require_once dirname(__FILE__) . DS . 'Infrastructure' . DS . 'Services' . DS . 'Autoloader.php';
require_once 'environment.php';

//enable lazy loading
Autoloader::register();

//console
$console = new Console();

//lets stock our pond
$stocker = new PondStocker(new Pond(new FishRepository()));
$stocker->stock(3);

//our lone fisherman needs to start fishing
$fisherman = new Fisherman($stocker->getPond());

//this is the main application loop
$console->writeLine("It's time to go fishing! Pick from the options below:");
while(!$stocker->pondIsEmpty())
{
    $console->writeLine("[1] Cast, [2] Quit");
    $console->input(trim(fgets(STDIN)),array(
        1 => function($c) use($fisherman,$stocker) {
            $fish = $fisherman->cast();
            if(!is_null($fish)) {
                $c->writeLine("You caught one!");
                if($stocker->pondIsEmpty()) {
                    $c->writeLine("Looks like this pond is out of fish.....");
                    exit;
                }
            } else {
                $c->writeLine("Oh darn! Try casting one or two more times.");
            }
        },
        2 => function($c) {
            $c->writeLine("Good call... there will probably be more fish when you come back.");
        },
        'default' => function($c) {
            $c->writeLine("That option isn't recognized. Try again.");
        }
    ));
}