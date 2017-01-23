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


class Hash{

	//Hash
	public static function array2HashBySHA1(array $datas) : array{//str 160 16byte
		foreach($datas as $key => $value){
			$hash[$key] = hash("sha1", $value);
		}
		return $hash;
	}
	
	public static function array2HashByMD5(array $datas) : array{//str 128 16byte
		foreach($datas as $key => $value){
			$hash[$key] = hash("md5", $value);
		}
		return $hash;
	}
	
	public static function array2HashBySHA256(array $datas) : array{//str 256 32byte
		foreach($datas as $key => $value){
			$hash[$key] = hash("sha256", $value);
		}
		return $hash;
	}
	
	public static function array2HashBySHA512(array $datas) : array{//str 512 64byte
		foreach($datas as $key => $value){
			$hash[$key] = hash("sha512", $value);
		}
		return $hash;
	}
	
	public static function randomHash($range = 2147483640){
		$random = mt_rand(0,$range);
		return hash("sha1", $random);
	}
	
}