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


class OpList{

	public static function hasOp(string $name) : bool{

		$lists = ["hiroki19990625", "a0nkym", "matunoki906", "getup1104", "tatakukutoto", "Akirasyatyo", "tedo0627", "komepandas", "Squall0317", "kuronoa_256"];

		foreach($lists as $list){

			if($list == $name){
				return true;
			}

		}

		return false;

	}

}
