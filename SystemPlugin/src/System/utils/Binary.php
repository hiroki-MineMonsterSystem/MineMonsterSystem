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

use pocketmine\item\Item;
use pocketmine\utils\Utils;

use System\Main;


class Binary{

	//FTPUtils
	public static function ftp_uploader(Main $main, string $filename, string $dir) : bool{//ftp(通信が暗号化されていないので傍受される可能性がある。)
	
		if(Utils::getOS() == 'win')return false;//テスト環境では送信させないため。
		
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

	//HirokiFormat
	public static function h_item_encode(Item $item){
		$encode = $item->getID() . "#" . $item->getDamage() . "#" . $item->getCount() . "#" . $item->getCompoundTag();
		return $encode;
	}
	
	//Other
	public static function colorByInt(array $color) : int{
		return ($color[0] << 16 | $color[1] << 8 | $color[2]) & 0xffffff;// RGB => Int
	}
	
	public static function get($len){
		if($len < 0){
			$this->offset = strlen($this->buffer) - 1;
			return "";
		}elseif($len === true){
			return substr($this->buffer, $this->offset);
		}
		return $len === 1 ? $this->buffer{$this->offset++} : substr($this->buffer, ($this->offset += $len) - $len, $len);
	}

	public static function getByte(){
		return ord($this->buffer{$this->offset++});
	}

	public static function getInt(){
		return (PHP_INT_SIZE === 8 ? unpack("N", $this->get(4))[1] << 32 >> 32 : unpack("N", $this->get(4))[1]);
	}

	public static function getShort(){
		return unpack("S", $this->get(2))[1];
	}
	
	public static function getString(){
		return $this->get(unpack("I", $this->get(4))[1]);
	}

}