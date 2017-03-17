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


class LoginSystem{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function loginAuth($player){
		$check = $this->loginCheck($player);
		switch($check){

			case 0:

				$player->sendMessage($this->text->systemSpaceText("system.register"));
				break;

			case 1:

				$player->sendMessage($this->text->systemSpaceText("system.login.ok"));
				break;

			case 2:

				$player->sendMessage($this->text->systemSpaceText("system.login"));
				break;

			case 3:

				$player->sendMessage($this->text->systemSpaceText("system.login.authcode"));
				break;

		}
	}

	public function register($player, $pass){
		if(preg_match("/^[a-zA-Z0-9]+$/", $pass)){
			if(strlen($pass) >= 6 && strlen($pass) <= 64){
				$hash = hash("sha512", $pass);
				$this->main->initLogin($player, $hash);
				$data = $this->main->getLogin($player->getName());
				$player->sendMessage($this->text->systemSpaceText("system.register.ok", ["pass" => $pass, "code" => $data["authcode"]]));
			}else{
				$player->sendMessage($this->text->systemSpaceText("system.register.lenght"));
			}
		}else{
			$player->sendMessage($this->text->systemSpaceText("system.register.twobyte"));
		}
	}

	public function login($player, $pass){
		if(preg_match("/^[a-zA-Z0-9]+$/", $pass)){
			if(strlen($pass) >= 6 && strlen($pass) <= 64){
				$hash = hash("sha512", $pass);
				$data = $this->main->getLogin($player->getName());

				if($data["pass"] == $hash){
					$player->sendMessage($this->text->systemSpaceText("system.login.ok"));
					$data["ip"] = $player->getAddress();
					$data["cid"] = $player->getClientId();

					$data = $this->main->setLogin($player->getName(), $data);
					$this->main->login->save();
				}else{
					$player->sendMessage($this->text->systemSpaceText("system.login.ng"));
				}
			}else{
				$player->sendMessage($this->text->systemSpaceText("system.login.ng"));
			}
		}else{
			$player->sendMessage($this->text->systemSpaceText("system.login.ng"));
		}
	}

	public function authCode($player, $code){
		if(preg_match("/^[a-zA-Z0-9]+$/", $pass)){
			if(strlen($pass) == 8){
				$data = $this->main->getLogin($player->getName());
				if($data["authcode"] == $pass){
					$player->sendMessage($this->text->systemSpaceText("system.login.authok"));
					$data["ip"] = $player->getAddress();
					$data["cid"] = $player->getClientId();

					$data = $this->main->setLogin($player->getName(), $data);
					$this->main->login->save();
				}else{
					$player->sendMessage($this->text->systemSpaceText("system.login.authng"));
				}
			}else{
				$player->sendMessage($this->text->systemSpaceText("system.login.authng"));
			}
		}else{
			$player->sendMessage($this->text->systemSpaceText("system.login.authng"));
		}
	}

	public function hasLogin($player) : bool{
		$check = $this->loginCheck($player);
		if($check == 1){
			return true;
		}
		return false;
	}

	public function loginCheck($player) : int{

		$name = $player->getName();

		if(!$this->main->hasLogin($name)){
			return 0;
		}else{
			$data = $this->main->getLogin($name);

			$uuid = $data["uuid"];
			$ip = $data["ip"];
			$cid = $data["cid"];

			if($player->getAddress() == $ip && $player->getClientId() == $cid){
				return 1;
			}elseif($player->getAddress() != $ip && $player->getClientId() == $cid){
				return 2;
			}elseif($player->getAddress() == $ip && $player->getClientId() != $cid){
				return 2;
			}else{
				return 3;
			}

		}

	}

}
