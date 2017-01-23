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
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\item\Item;

use System\Main;

use System\system\SystemText;

class JobManager{

	private $main;

	private $jobs = ["tr", "wa", "bw", "ww"];
	private $twobytejobs = ["旅人", "戦士", "黒魔導士", "白魔導士"];

	private $jobmagics = ["tr" => [], "wa" => [2 => 10], "bw" => [], "ww" => []];

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

  public function getJob(string $name){

		$data = $this->main->getSave($name);

		if(isset($data)){
			return $data["job"];
		}else{
			return null;
		}

	}

	public function setJob(string $name, string $jobname) : bool{

		$data = $this->main->getSave($name);

		if(!$this->hasJob($jobname)){
			return false;
		}

		if(isset($data)){
			$data["job"] = $jobname;
			$this->main->setSave($name, $data);
			return true;
		}else{
			return false;
		}

	}

	public function getJobExp(string $name){

		$data = $this->main->getSave($name);

		if(isset($data)){
			return $data["jobdata"][$data["job"]]["exp"];
		}else{
			return null;
		}

	}

	public function addJobExp(string $name, int $value) : bool{

		$data = $this->main->getSave($name);

		if(isset($data)){
			if(!isset($data["jobdata"][$data["job"]])){
				$data["jobdata"][$data["job"]] = ["exp" => $value, "level" => 1];
				$this->main->setSave($name, $data);
				$this->checkjobExp();
				return true;
			}else{
				$data["jobdata"][$data["job"]]["exp"] += $value;
				$this->main->setSave($name, $data);
				$this->checkjobExp();
				return true;
			}
		}else{
			return false;
		}

	}

	public function checkJobExp(string $name){

		$data = $this->main->getSave($name);

		$level = $data["jobdata"][$data["job"]]["level"];
		$exp = $data["jobdata"][$data["job"]]["exp"];

		$lvup = $level * 50;

		if($lvup <= $exp){
			$data["jobdata"][$data["job"]] = ["exp" => 0, "level" => ++$level];
			$magicdata = $this->getLvByMagicId($data["job"], ++$level);

			$this->main->setSave($name, $data);
			$this->main->player->save();

			$player = $this->main->getServer()->getPlayer($name);

			$player->sendMessage($this->text->systemSpaceText("joblvup", ["name" => $name]));
			$player->sendMessage($this->text->systemSpaceText("joblvup.value", ["value1" => $level, "value2" => ++$level]));

			if($magicdata != null){
				$player->sendMessage($this->text->systemSpaceText("get.magic", ["name" => $name]));
			}
		}

	}

	public function hasJob(string $job){

		$data = $this->main->getSave($name);

		if(isset($this->jobs[$job])){
			return true;
		}else{
			return false;
		}
	}

	public function getLvByMagicId(string $job, int $lv){//return string || null;

		$data = $this->main->getSave($name);

		$magic = $this->jobmagics;

		if(isset($magic[$job][$lv])){
			return $magic[$job][$lv];
		}else{
			return null;
		}

	}

	public function getJobStringByTwoByteString(string $job){

		$data = $this->main->getSave($name);

		if(isset($this->twobytejobs[$job])){
			return $this->twobytejobs[$job];
		}else{
			return null;
		}
	}

}
