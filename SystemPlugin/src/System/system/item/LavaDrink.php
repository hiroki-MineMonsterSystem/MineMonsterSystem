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
namespace System\system\item;

use pocketmine\Server;

use pocketmine\item\Item;

use pocketmine\nbt\tag\IntTag;

use pocketmine\inventory\ShapelessRecipe;

use System\Main;


class LavaDrink{

	public static function getName() : string{

		return "マグマドリンク";

	}

	public static function getSpId() : int{

		return 3;//0または、詳細な設定がない場合は-1

	}

	public static function getEvId() : int{

		return 0;//詳細な設定がない場合は-1 UseItemPacket:0 ItemEat:1 Attack:2 Damage:3

	}

	public static function registerRecipe(){

		$recipe = new ShapelessRecipe(self::getCustomItem());
		$lists = [Item::get(322,0,1),Item::get(322,0,1),Item::get(322,0,1),
						Item::get(322,0,1),Item::get(325,10,1),Item::get(322,0,1),
						Item::get(322,0,1),Item::get(322,0,1),Item::get(322,0,1)];

		foreach($lists as $list){
			$recipe->addIngredient($list);
		}

		Server::getInstance()->addRecipe($recipe);

	}

	public static function getCustomItem() : Item{

		$item = Item::get(373,13,1);

		$item->setCustomName(self::getName());
		$tag = $item->getNamedTag();
		$tag->Sp = new IntTag("Sp", self::getSpId());
		$tag->Ev = new IntTag("Ev", self::getEvId());

		$item->setCompoundTag($tag);

		return $item;

	}

}
