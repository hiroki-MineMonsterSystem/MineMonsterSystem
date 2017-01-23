<?php

namespace System\system;

use pocketmine\Player;

use pocketmine\utils\Config;

use System\Main;

class TapToDo{

	private $main;
	private $flags;
	private $data;
	private $command;

	public function __construct(Main $main){
		$this->main = $main;
		$this->config = new Config($this->main->getDataFolder() . "TapToDo.yml", Config::YAML);
		foreach ($this->config->getAll() as $pos => $command) {
			$this->data[$pos][] = $command;
		}
	}

	public function addCommand(Player $player, int $x, int $y, int $z, $command) {
		$level  = $player->getLevel()->getName();
		$pos = $x." . ".$y." . ".$z." . ".$level;
		$this->setFlag($player, "");
		$this->data[$pos][] = $command;
		$this->config->set($this->data[$pos], $command);
		$this->config->save();
		$player->sendMessage("§a>>§b/".$command." を登録しました");
	}

	public function delCommand(Player $player, int $x, int $y, int $z) {
		$level  = $player->getLevel()->getName();
		$pos = $x." . ".$y." . ".$z." . ".$level;
		$this->setFlag($player, "");
		if (array_key_exists($pos, $this->data)) {
			$this->config->remove($this->data[$pos]);
			$this->config->save();
			unset($this->data[$pos]);
			$player->sendMessage("§a>>§bその場所のコマンドを削除しました");
			return true;
		}else{
			$player->sendMessage("§a>>§bその場所にはコマンドが登録されていません");
			return false;
		}
	}

	public function executionCommand(Player $player, int $x, int $y, int $z) {
		$level = $player->getLevel()->getName();
		$pos = $x." . ".$y." . ".$z." . ".$level;
		if (in_array($pos, $this->data)) {
			foreach ($this->data[$pos] as $command) {
				$command = str_replace("%p", $player->getName(), $command);
				$command = str_replace("%x", $player->x, $command);
				$command = str_replace("%y", $player->y, $command);
				$command = str_replace("%z", $player->z, $command);
				$command = str_replace("%l", $level, $command);
				if (strstr($command, "%op")){
					$player->setOp(true);
					$this->plugin->getServer()->dispatchCommand($player, $command);
					$player->setOp(false);
				}else{
					$this->plugin->getServer()->dispatchCommand($player, $command);
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
}