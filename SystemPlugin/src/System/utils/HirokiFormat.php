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

use pocketmine\item\Item;

use pocketmine\utils\Utils;

use System\Main;


class HirokiFormat{

	//HirokiFormat
	public static function h_Item_Encode(Item $item) : string{
		$encode = $item->getID() . "#" . $item->getDamage() . "#" . $item->getCount() . "#" . $item->getCompoundTag();
		return $encode;
	}
	
	public static function h_Item_Decode(string $data) : Item{
		$decode = explode("#", $data);
		if(isset($decode[3])){
			$item = Item::get($decode[0], $decode[1], $decode[2], $decode[3]);
			if($item instanceof Item){ 
				return $item;
			}
		}
		$item = Item::get(0);
		return $item;
	}

}