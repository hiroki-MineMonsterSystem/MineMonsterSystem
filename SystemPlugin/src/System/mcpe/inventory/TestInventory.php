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

namespace System\mcpe\inventory;

use pocketmine\level\Position;
use pocketmine\Player;

use pocketmine\inventory\ContainerInventory;
use pocketmine\inventory\FakeBlockMenu;
use pocketmine\inventory\InventoryType;

class TestInventory extends ContainerInventory{
	public function __construct(Position $pos){
		parent::__construct(new FakeBlockMenu($this, $pos), new InventoryType(2, "???", 15));
	}



	/**
	 * @return FakeBlockMenu
	 */
	public function getHolder(){
		return $this->holder;
	}

	public function onClose(Player $who){
		parent::onClose($who);
	}
}
