<?php
//this might normally be an infrastructure service, but for ease we will reuse the test bootstrap
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'bootstrap.php';
use \Domain\Value\Pond;
use \Infrastructure\Persistence\FishRepository;
use \Domain\Service\PondStocker;
use \Domain\Value\Fisherman;

//lets stock our pond
$stocker = new PondStocker(new Pond(new FishRepository()));
$stocker->stock(3);

//our lone fisherman needs to start fishing
$fisherman = new Fisherman($stocker->getPond());

//this is the main application loop
fwrite(STDOUT,"It's time to go fishing! Pick from the options below:\n");
while($stocker->getPond()->getFishCount() > 0)
{
    fwrite(STDOUT,"[1] Cast, [2] Quit\n");
    $selection = trim(fgets(STDIN));
    if($selection == 1) {
        $fish = $fisherman->cast();
        if(!is_null($fish)) {
            fwrite(STDOUT,"You caught one!\n");
            if($stocker->getPond()->getFishCount() == 0) {
                fwrite(STDOUT,"Looks like this pond is out of fish.....\n");
                exit;
            }
        } else {
            fwrite(STDOUT,"Oh darn! Try casting one or two more times.\n");
        }
    } else if ($selection == 2) {
        fwrite(STDOUT,"Good call... there will probably be more fish when you come back.\n");
        exit;
    } else {
        fwrite(STDOUT,"That option isn't recognized. Try again.\n");
    }
}