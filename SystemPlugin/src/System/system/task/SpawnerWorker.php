<?php
/*
 _  _ ___ ___  ___  _  _____
| || |_ _| _ \/ _ \| |/ /_ _|
| __ || ||   / (_) | ' < | |
|_||_|___|_|_\\___/|_|\_\___|

 ___ _   _   _  ___ ___ _  _
| _ \ | | | | |/ __|_ _| \| |
|  _/ |_| |_| | (_ || || .` |
|_| |____\___/ \___|___|_|\_|

*/
namespace System\system\task;

use pocketmine\scheduler\Task;

use System\system\SpawnerSystem;

class SpawnerWorker extends Task{
  public function __construct(SpawnerSystem $system){
    $this->system = $system;
  }

  public function onRun($currentTick){
    $this->system->onUpdate();
  }
}
