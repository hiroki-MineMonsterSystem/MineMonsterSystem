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
namespace System\system;

use pocketmine\Player;

use System\Main;

use System\system\SystemText;


class INN{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function sleepINN(Player $player){
	
		$gold = $this->main->getMoneyClass();
		$name = $player->getName();
		$inn = 120;
		
		if($gold->moreThanGold($name, $inn)){
			$gold->useGold($name, $inn);
			$player->setHealth($player->getMaxHealth());
			$this->main->getMPClass()->setMP($name, $this->main->getMPClass()->getMaxMP($name));
			$player->sendMessage($this->text->systemSpaceText("inn.yes"));
		}else{
			$player->sendMessage($this->text->systemSpaceText("inn.no", ["gold" => $inn]));
		}
	
	}

}