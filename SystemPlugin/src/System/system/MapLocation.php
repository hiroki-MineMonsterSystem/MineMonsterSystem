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

use System\Main;


class MapLocation{

	public function mapData($x, $y, $z, $levelname){//Move to system/Location

		$pos = new Vector3($x, $y, $z);

		$datas = [
					"world:272#55#271:241#70#241" => "ƒŠƒX’n"
		];

		foreach($datas as $data => $value){
			$exp1 = explode(":", $data);
			$level = $exp1[0];
			$pos1 = explode("#", $exp1[1]);
			$pos2 = explode("#", $exp1[2]);

			$maxX = max($pos1[0], $pos2[0]);
			$maxY = max($pos1[1], $pos2[1]);
			$maxZ = max($pos1[2], $pos2[2]);
			$minX = min($pos1[0], $pos2[0]);
			$minY = min($pos1[1], $pos2[1]);
			$minZ = min($pos1[2], $pos2[2]);

			if($level == $levelname){
				if($minX <= $pos->x && $maxX >= $pos->x){
					if($minY <= $pos->y && $maxY >= $pos->y){
						if($minZ <= $pos->z && $maxZ >= $pos->z){
							return $value;
						}
					}
				}
			}
		}
		return null;

	}

}
