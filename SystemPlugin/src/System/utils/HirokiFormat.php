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
namespace System\utils;

use pocketmine\utils\Utils;

use System\Main;


class HirokiFormat{

	//HirokiFormat
	public static function h_item_encode(Item $item){
		$encode = $item->getID() . "#" . $item->getDamage() . "#" . $item->getCount() . "#" . $item->getCompoundTag();
		return $encode;
	}

}