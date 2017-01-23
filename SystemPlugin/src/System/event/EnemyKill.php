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

use System\Main;

use System\system\SystemText;


class EnemyKill{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function onEnemyKill(Player $player, Entity $entity) : bool{
	
		if(isset($entity->namedtag["LV"])){

			$name = $player->getName();
			$lv = $entity->namedtag["LV"];
			$exp = intval($lv * 1.5);
			$gold = intval($lv * 1.2);

			$this->main->getExpClass()->addExp($name,$exp);
			$this->main->getMoneyClass()->addGold($name,$gold);
			
			$player->sendMessage($this->text->systemSpaceText("get.exp", ["name" => $name, "value" => $exp]));
			$player->sendMessage($this->text->systemSpaceText("get.gold", ["name" => $name, "value" => $gold]));

			return true;

		}

		return false;

	}

}