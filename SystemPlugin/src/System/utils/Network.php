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


class Network{

	//FTP
	public static function ftp_uploader(Main $main, string $filename, string $dir) : bool{//ftp(通信が暗号化されていないので傍受される可能性がある。)
	
		if(Utils::getOS() == 'win')return false;//テスト環境では送信させないため。Linuxは許可
		
		$target = ftp_connect("minemonster.web.fc2.com", 21, 20);
		
		$yaml = $main->ftp->getAll();
		
		if(!$target){
			$this->getLogger()->info("サーバーが見つかりません。");
			return false;
		}
		
		$login = ftp_login($target, $yaml["user"], $yaml["password"]);
		
		if(!$login){
			$this->getLogger()->info("ログインに失敗しました。");
			return false;
		}
		
		$main->getServer()->broadcastMessage(TextFormat::GREEN . "サーバーにログインしました。");

		ftp_pasv($target, true);
		
		$file = $this->getDataFolder() . $filename;

		if(ftp_put($target, $dir, $file, FTP_ASCII)){
			$this->getLogger()->info("送信が完了しました。");
		}else{
			$this->getLogger()->info("送信に失敗しました。");
			$this->getServer()->broadcastMessage(TextFormat::RED . "送信に失敗しました。");
			return false;
		}

		$this->getServer()->broadcastMessage(TextFormat::GREEN . "送信が完了しました。");

		ftp_close($target);
		
		return true;
	
	}

}