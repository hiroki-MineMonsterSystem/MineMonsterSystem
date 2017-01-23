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
namespace System\event;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
use pocketmine\item\Item;

use pocketmine\math\Vector3;

use System\Main;

use System\system\SystemText;
use System\system\MapLocation;


class UseMap{

	private $main;

	public function __construct(Main $main){//void

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function sendData(Player $player){//void

		$location = MapLocation::mapData($player->x, $player->y, $player->z, $player->level->getName());

		if($location != null){

			$player->sendMessage("現在のロケーション: " . $location);

		}else{

			$player->sendMessage("現在のロケーション: ???");

		}
	}
}
