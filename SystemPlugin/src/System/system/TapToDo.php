<?php

namespace System\system;

use pocketmine\Player;

use pocketmine\utils\Config;

use System\Main;

use System\system\SystemText;

class TapToDo{

	private $main;
	private $flags;
	private $data;
	private $command;

	public function __construct(Main $main){
		$this->main = $main;
		$this->data = new Config($this->main->getDataFolder() . "TapToDo.yml", Config::YAML);
		$this->text = new SystemText();
	}

	public function addCommand(Player $player, int $x, int $y, int $z, string $command) {
		$level  = $player->getLevel()->getName();
		$pos = $x." . ".$y." . ".$z." . ".$level;
		$this->setFlag($player, "");

		if(isset($this->data->getAll()[$pos])){
			$datas = $this->data->getAll()[$pos];
			$datas[] = $command;
		}else{
			$datas[] = $command;
		}
		$this->data->set($pos, $datas);
		$this->data->save();
		$player->sendMessage($this->text->systemSpaceText("taptodo.add", ["command" => $command]));
	}

	public function delCommand(Player $player, int $x, int $y, int $z) {
		$level  = $player->getLevel()->getName();
		$pos = $x." . ".$y." . ".$z." . ".$level;
		$this->setFlag($player, "");
		if (array_key_exists($pos, $this->data->getAll())) {
			$this->data->remove($pos);
			$this->data->save();
			$player->sendMessage($this->text->systemSpaceText("taptodo.del.ok"));
			return true;
		}else{
			$player->sendMessage($this->text->systemSpaceText("taptodo.del.ng"));
			return false;
		}
	}

	public function executionCommand(Player $player, int $x, int $y, int $z) {
		$level = $player->getLevel()->getName();
		$pos = $x." . ".$y." . ".$z." . ".$level;
		if (isset($this->data->getAll()[$pos])) {
			foreach ($this->data->get($pos) as $command) {
				$command = str_replace("%p", $player->getName(), $command);
				$command = str_replace("%x", $player->x, $command);
				$command = str_replace("%y", $player->y, $command);
				$command = str_replace("%z", $player->z, $command);
				$command = str_replace("%l", $level, $command);
				if (strstr($command, "%op")){
					$player->setOp(true);
					$this->main->getServer()->dispatchCommand($player, $command);
					while($player->isOp()){
						$player->setOp(false);
					}
				}else{
					$this->main->getServer()->dispatchCommand($player, $command);
				}
			}
		}
	}

	public function setFlag(Player $player, string $func, string $command = null){
		$this->flag[$player->getName()] = $func;

		switch($func){

			case "Add":

				$this->command[$player->getName()] = $command;
				break;
		}
	}

	public function getFlag(Player $player){
		if(isset($this->flag[$player->getName()])){
			return $this->flag[$player->getName()];
		}else{
			return null;
		}
	}

	public function getCommand(Player $player){
		if(isset($this->command[$player->getName()])){
			return $this->command[$player->getName()];
		}else{
			return null;
		}
	}
}
