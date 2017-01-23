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

use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\item\Item;

use pocketmine\utils\TextFormat;

use pocketmine\math\Vector3;

use pocketmine\inventory\ChestInventory;

use pocketmine\tile\Tile;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

use System\utilsHirokiFormat;

class StorageBox{

	public function __construct(Main $main){

		$this->main = $main;
		
	}
	
	public function open(Player $player, Tile $tile){
	
	}
	
	public function close(Player $player, Tile $tile){
	
	}

}
