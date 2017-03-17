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


class CSVLoader{

	public static function load(string $filename, string $dir) : array{
	
		$array = [];

		$content = file_get_contents($dir . $filename . ".csv");
		
		if(!$content){
		
			return $array;
		
		}
		
		$db = explode("\n", $content);
		
		foreach($db as $d){
		
			$ex = explode(",", $d);
			if($ex[0] == ""){
			
			}else{
				$array[] = $ex;
			}
		
		}
		
		//print_r($array);
		
		return $array;

	}

}