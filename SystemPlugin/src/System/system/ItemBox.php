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

use System\system\WeaponManager;


class ItemBox{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->datas = $this->main->getWeapon();
		$this->par = $this->getWeaponPar();

	}

	private function getWeaponPar($end = -1){

		$array = [];
		$c = 0;

		foreach($this->datas as $data){
			if($c == 0){
				++$c;
				continue;
			}
			if($end == $c){
				return $array;
			}
			$array[$c] = (int)$data[8];
			++$c;
		}

		return $array;

	}

	private function getRange(array $array){

		$random = rand(1, array_sum($array));
		$sub = 0;
		foreach($array as $key => $value){
			$sub += $value;
			if($sub >= $random){
				return $key;
			}
		}

	}

	public function openBox(Player $player, int $type = 0, int $rank = 0){

			switch($type){
				case 0:

					switch ($rank) {
						case 0:
							$item = $this->main->getWeaponManager()->getIndexByItem($this->getRange($this->par));

							$player->getInventory()->addItem($item);
							break;
					}

					break;
			}

	}

}
