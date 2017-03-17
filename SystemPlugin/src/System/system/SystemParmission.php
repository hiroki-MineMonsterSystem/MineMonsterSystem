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


class SystemParmission{

	private static $gamemaster = ["hiroki19990625"];

	private static $operation = ["a0nkym", "Akirasyatyo", "Squall0317", "tedo0627"];

	private static $suboperation = ["matunoki906", "getup1104", "tatakukutoto", "komepandas", "kuronoa_256"];

	private static $security = [];

	public static function hasGameMaster(string $name) : bool{

		foreach(self::$gamemaster as $list){

			if($list == $name){
				return true;
			}

		}

		return false;

	}

	public static function hasOp(string $name) : bool{

		foreach(self::$operation as $list){

			if($list == $name){
				return true;
			}

		}

		return false;

	}

	public static function hasSubOp(string $name) : bool{

		foreach(self::$suboperation as $list){

			if($list == $name){
				return true;
			}

		}

		return false;

	}

	public static function hasChangeParm(string $name) : bool{

		$bool = false;
		if(SystemParmission::hasGameMaster($name)){
			$bool = true;
		}elseif(SystemParmission::hasOp($name)){
			$bool = true;
		}elseif(SystemParmission::hasSubOp($name)){
			$bool = true;
		}

		return $bool;
	}

}
