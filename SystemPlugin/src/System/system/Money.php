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


class Money{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		
	}

	public function getGold(string $name){
	
		$data = $this->main->getSave($name);
		
		if(isset($data)){
			return $data["gold"];
		}else{
			return null;
		}
	
	}

	public function setGold(string $name, int $value){
	
		$data = $this->main->getSave($name);
		
		if(isset($data)){
			$data["gold"] = $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}
	
	}
	
	public function addGold(string $name, int $value){
	
		$data = $this->main->getSave($name);
	
		if(isset($data)){
			$data["gold"] = $data["gold"] + $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}
	
	}
	
	public function useGold(string $name, int $value){
	
		$data = $this->main->getSave($name);
		
		if($data["gold"] - $value < 0){
			return false;
		}
	
		if(isset($data)){
			$data["gold"] = $data["gold"] - $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}
	
	}
	
	public function moreThanGold(string $name, int $value){
	
		$data = $this->main->getSave($name);
		
		if(isset($data)){
			if($data["gold"] >= $value){ 
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	
	}

}