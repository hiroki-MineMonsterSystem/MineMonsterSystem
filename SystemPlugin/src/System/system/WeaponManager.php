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

use pocketmine\item\Item;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;

use System\Main;


class WeaponManager{

	private $main;

	public function __construct(Main $main){
		$this->main = $main;
		$lists = $this->main->getWeapon();

		$c = 0;

		foreach($lists as $list){
			if($c == 0){
				++$c;
			}else{
				$item = Item::get((int)$list[1], (int)$list[2], 1);
				$tag = $list[3] . "\n§bWID: " . $list[0] . "\n§eRank: " . $list[4] . "\n§c攻撃力: +" . $list[5] . "\n§4会心率: +" . $list[6] . "%";
				$item->setCustomName($tag);

				$nbt = $item->getNamedTag();
				$nbt->WeaponData = new CompoundTag("WeaponData", [
										"WID" => new IntTag("WID", (int)$list[0]),
										"Attack" => new IntTag("Attack", (int)$list[5]),
										"Kaisin" => new FloatTag("Kaisin", (float)$list[6])
				]);

				$item->setNamedTag($nbt);

				Item::addCreativeItem($item);
				++$c;
			}
		}
	}

	public function getIndexByItem(int $index) : Item{

		$list = $this->main->getWeapon()[$index];

		$item = Item::get((int)$list[1], (int)$list[2], 1);
		$tag = $list[3] . "\n§bWID: " . $list[0] . "\n§eRank: " . $list[4] . "\n§c攻撃力: +" . $list[5] . "\n§4会心率: +" . $list[6] . "%";
		$item->setCustomName($tag);

		$nbt = $item->getNamedTag();
		$nbt->WeaponData = new CompoundTag("WeaponData", [
								"WID" => new IntTag("WID", (int)$list[0]),
								"Attack" => new IntTag("Attack", (int)$list[5]),
								"Kaisin" => new FloatTag("Kaisin", (float)$list[6])
		]);

		$item->setNamedTag($nbt);

		return $item;

	}

	public function getWID(Item $item) : int{
		$nbt = $item->getNamedTag();

		if(isset($nbt->WeaponData)){
			if(isset($nbt->WeaponData->WID)){
				return $nbt->WeaponData->WID->getValue();
			}
		}
		return -1;
	}

	public function getAttack(Item $item) : int{
		$nbt = $item->getNamedTag();

		if(isset($nbt->WeaponData)){
			if(isset($nbt->WeaponData->Attack)){
				return $nbt->WeaponData->Attack->getValue();
			}
		}
		return 0;
	}

	public function getKaisin(Item $item) : float{
		$nbt = $item->getNamedTag();

		if(isset($nbt->WeaponData)){
			if(isset($nbt->WeaponData->Kaisin)){
				return $nbt->WeaponData->Kaisin->getValue();
			}
		}
		return 0;
	}

}
