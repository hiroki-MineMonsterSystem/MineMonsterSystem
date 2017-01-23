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

use System\system\SystemText;


class MP{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function getMP(string $name){

		$data = $this->main->getSave($name);

		if(isset($data)){
			return $data["mp"];
		}else{
			return null;
		}

	}

	public function setMP(string $name, int $value) : bool{

		$data = $this->main->getSave($name);
		
		if($value >= $this->getMaxMP($name)){
			$value = $this->getMaxMP($name);
		}

		if(isset($data)){
			$data["mp"] = $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}

	}

	public function addMP(string $name, int $value) : bool{

		$data = $this->main->getSave($name);
		
		if($data["mp"] + $value >= $this->getMaxMP($name)){
			$value = 0;
		}

		if(isset($data)){
			$data["mp"] = $data["mp"] + $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}

	}
	
	public function useMP(string $name, int $value) : bool{

		$data = $this->main->getSave($name);
		
		if($data["mp"] - $value < 0){
			return false;
		}

		if(isset($data)){
			$data["mp"] = $data["mp"] - $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}

	}
	
	public function getMaxMP(string $name){

		$data = $this->main->getSave($name);

		if(isset($data)){
			return $data["maxmp"];
		}else{
			return null;
		}

	}

	public function setMaxMP(string $name, int $value) : bool{

		$data = $this->main->getSave($name);

		if(isset($data)){
			$data["maxmp"] = $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}

	}

	public function addMaxMP(string $name, int $value) : bool{

		$data = $this->main->getSave($name);

		if(isset($data)){
			$data["maxmp"] = $data["maxmp"] + $value;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}

	}

}
