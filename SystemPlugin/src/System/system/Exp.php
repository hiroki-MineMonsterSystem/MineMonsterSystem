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


class Exp{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function getExp(string $name){

		$data = $this->main->getSave($name);

		if(isset($data)){
			return $data["exp"];
		}else{
			return null;
		}

	}

	public function setExp(string $name, int $value) : bool{

		$data = $this->main->getSave($name);

		if(isset($data)){
			$data["exp"] = $value;
			$this->main->setSave($name, $data);
			$this->checkExp($name);
			return true;
		}else{
			return false;
		}

	}

	public function addExp(string $name, int $value) : bool{

		$data = $this->main->getSave($name);

		if(isset($data)){
			$data["exp"] = $data["exp"] + $value;
			$this->main->setSave($name, $data);
			$this->checkExp($name);
			return true;
		}else{
			return false;
		}

	}

	public function getLvUpExp(string $name){

		$data = $this->main->getSave($name);

		if(isset($data)){
			$exp = $data["exp"];
			$sexp1 = $data["sub1"];
			return $sexp1 - $exp;
		}else{
			return null;
		}

	}

	public function checkExp(string $name){

		$data = $this->main->getSave($name);

		$exp = $data["exp"];
		$sexp1 = $data["sub1"];

		if($sexp1 <= $exp){
			$skill = mt_rand(1,3);

			++$data["level"];
			$data["sub2"] = intval($data["sub2"] * 1.1);
			$data["sub1"] = $data["sub1"] + $data["sub2"];

			$data["maxmp"] += 2;
			$data["mp"] = $data["maxmp"];

			$data["skill"] += $skill;

			$h = intval(20 + ($data["level"] / 2));

			$player = $this->main->getServer()->getPlayer($name);

			$player->setMaxHealth($h);
			$player->setHealth($h);
			$player->sendMessage($this->text->systemSpaceText("lvup", ["name" => $name]));
			$player->sendMessage($this->text->systemSpaceText("lvup.value", ["value1" => $data["level"] - 1, "value2" => $data["level"]]));
			$player->sendMessage($this->text->systemSpaceText("get.skillp", ["name" => $name, "value" => $skill]));

			$this->main->tagUpdate($player);

			$this->main->setSave($name, $data);
			$this->main->player->save();
		}

	}

}
