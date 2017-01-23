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

use System\Main;

use System\system\SystemText;


class UseItem{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function itemSP(Player $player, int $id) : bool{
	
		switch($id){
		
			case 1:
			
				$effects = [
					Effect::getEffect(5)->setDuration(600)->setAmplifier(1),
					Effect::getEffect(9)->setDuration(800)
				];
				
				foreach($effects as $effect){
					$player->addEffect($effect);
				}
				
				$player->getInventory()->setItemInHand(Item::get(0));
				
				return true;
				break;
				
			case 2:
			
				$effects = [
					Effect::getEffect(5)->setDuration(600)->setAmplifier(1),
					Effect::getEffect(Effect::REGENERATION)->setDuration(600)->setAmplifier(4),
					Effect::getEffect(Effect::ABSORPTION)->setDuration(2400),
					Effect::getEffect(Effect::DAMAGE_RESISTANCE)->setDuration(6000),
					Effect::getEffect(Effect::FIRE_RESISTANCE)->setDuration(6000)
				];
				
				foreach($effects as $effect){
					$player->addEffect($effect);
				}
				
				$player->getInventory()->setItemInHand(Item::get(0));
				
				return true;
				break;
				
			case 3:
			
				$effects = [
					Effect::getEffect(Effect::FIRE_RESISTANCE)->setDuration(6000)
				];
				
				foreach($effects as $effect){
					$player->addEffect($effect);
				}
				
				$player->getInventory()->setItemInHand(Item::get(0));
				
				return true;
				break;
		
		}

		return false;

	}

}