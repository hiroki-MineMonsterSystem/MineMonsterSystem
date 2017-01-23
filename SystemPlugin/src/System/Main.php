<?php
/*
_ _ ___ ___ ___ _ _____
| || |_ _| _ \/ _ \| |/ /_ _|
| __ || || / (_) | ' < | |
|_||_|___|_|_\\___/|_|\_\___|

___ _ _ _ ___ ___ _ _
| _ \ | | | | |/ __|_ _| \| |
| _/ |_| |_| | (_ || || .` |
|_| |____\___/ \___|___|_|\_|

*/
namespace System;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\entity\Item as EntityItem;
use pocketmine\entity\Projectile;
use pocketmine\Achievement;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\entity\Effect;

use pocketmine\permission\ServerOperator;

use pocketmine\utils\Config;
use pocketmine\utils\Utils;

use pocketmine\tile\Chest as TileChest;
use pocketmine\tile\Tile;

use pocketmine\network\protocol\UseItemPacket;
use pocketmine\network\protocol\InteractPacket;
use pocketmine\network\protocol\PlayerActionPacket;

use pocketmine\scheduler\CallbackTask;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\PopSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\level\sound\AnvilBreakSound;

use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\level\particle\EnchantParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\InkParticle;
use pocketmine\level\particle\InstantEnchantParticle;
use pocketmine\level\particle\ItemBreakParticle;
use pocketmine\level\particle\LargeExplodeParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\RainSplashParticle;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\SplashParticle;
use pocketmine\level\particle\SporeParticle;
use pocketmine\level\particle\TerrainParticle;
use pocketmine\level\particle\WaterDripParticle;
use pocketmine\level\particle\WaterParticle;

use System\EventListener;

use System\system\Exp;
use System\system\Money;
use System\system\MP;

use System\system\LoginSystem;
use System\system\SystemText;

use System\system\item\ItemManager;

use System\system\MagicManager;
use System\system\JobManager;

use System\entity\Magic;

use System\utils\Binary;


class Main extends PluginBase{

	const GAME_VERSION = 1;//int
	const GAME_MIN_VERSION = 1.0;//float
	const GAME_BUILD = 2;//int

	private $listener;

	private static $instance;//インスタンスを保持

	public static function getInstance(){//インスタンスを取得
		return self::$instance;
	}


	public function onEnable(){//プラグインが有効になった時

		$this->listener = new EventListener($this);
		$this->getServer()->getPluginManager()->registerEvents($this->listener, $this);//イベントリスナー登録

		$this->getLogger()->info(TextFormat::GREEN . "SystemPluginが読み込まれました " . TextFormat::RED . "by dragon1330");

		$this->Ver = self::GAME_VERSION . "" . self::GAME_MIN_VERSION;//Stringバージョン
/*			未登録
		$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new CallbackTask([$this,"updateGame"]), 120, 20*1);//1秒に一回システムアップデート
		$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new CallbackTask([$this,"fileUpload"]), 120, 20*60*10);//10分に1回自動ファイルアップロード
		$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new CallbackTask([$this,"sendChestItem"]), 120, 20*60*2);//2分に1回チェストアイテムを配布
		$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new CallbackTask([$this,"sendPop"]), 120, 20*60*3);//3分に1回お知らせ
*/
		$this->dataFileRegister();

		$this->classInit();

		$this->item->registerItem();//itemclass登録

		//entityを登録
		Entity::registerEntity(Magic::class);

	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args){

		switch($command->getName()){

			case "login":
				if(!isset($args[0]) || !$this->hasLogin($sender->getName()) || $this->loginsystem->hasLogin($sender)){
					return false;
				}
				$this->loginsystem->login($sender, $args[0]);
				return true;
				break;

			case "register":
				if(!isset($args[0]) || $this->hasLogin($sender->getName())){
					return false;
				}
				$this->loginsystem->register($sender, $args[0]);
				return true;
				break;

			case "magic":
			case "ma":
				if(!isset($args[0])){
					return false;
				}

				switch($args[0]){

					case "help":
					case "h":

						break;

					case "info":
					case "i":

						if(!isset($args[1])){
							$sender->sendMessage("[help]/magic info <魔法ID>");
							return true;
						}
						$sender->sendMessage($this->magic->magicInfo($sender->getName(), $args[1]));
						return true;

						break;

					case "list":
					case "l":

						if(!isset($args[1])){
							$args[1] = 1;
						}
						$this->magic->listMagic($sender, $args[1]);
						return true;

						break;

					case "get":
					case "g":

						$sender->sendMessage($this->magic->getInfoMagic($sender->getName()));
						return true;

						break;

					case "set":
					case "s":

						if(!isset($args[1])){
							$sender->sendMessage("[help]/magic set <魔法ID>");
							return true;
						}

						if(!$this->magic->haveMagic($sender->getName(), $args[1])){
							$data = $this->magic->getData($args[1]);
							if($data[0] == "存在しません"){
								$sender->sendMessage($this->text->systemSpaceText("magic.notfound", ["name" => $data[0]]));
								return true;
							}
							$sender->sendMessage($this->text->systemSpaceText("magic.nothave", ["name" => $data[0]]));
							return true;
						}

						$data = $this->magic->getData($args[1]);

						$this->magic->setMagic($sender->getName(), $args[1]);
						$sender->sendMessage($this->text->systemSpaceText("magic.set", ["name" => $data[0]]));
						return true;
						break;

				}

				break;

			case "init":

				$this->initSave($sender->getName());
				$sender->getInventory()->clearAll();
				return true;

				break;

			case "invc":

				$sender->getInventory()->clearAll();
				return true;
				break;
		}
}

	public function getGameVersion(){
		return $this->Ver;
	}

	public function getExpClass() : Exp{
		return $this->exp;
	}

	public function getMoneyClass() : Money{
		return $this->gold;
	}

	public function getMPClass() : MP{
		return $this->mp;
	}

	public function getItemManager() : ItemManager{
		return $this->item;
	}

	public function getMagicManager() : MagicManager{
		return $this->magic;
	}

	public function getLoginSystem() : LoginSystem{
		return $this->loginsystem;
	}

	public function getJobManager() : JobManager{
		return $this->jobmanager;
	}

	public function initLogin(Player $player, $password) : bool{
		$authcode = Binary::randomHash();

		$data = ["user" => $player->getName(), "pass" => $password, "uuid" => "", "ip" => $player->getAddress(), "cid" => $player->getClientId(), "authcode" => substr($authcode, 0, 8)];

		$this->login->set($player->getName(), $data);

		$this->login->save();

		if($this->login->exists($player->getName())){
			return true;
		}else{
			return false;
		}
	}

	public function hasLogin(string $name) : bool{
		return $this->login->exists($name);
	}

	public function getLogin(string $name){

		$data = $this->login->get($name);

		if(isset($data)){
			return $data;
		}else{
			return null;
		}

	}

	public function setLogin(string $name, array $data){

		if(isset($data)){
			$this->login->set($name, $data);
			return true;
		}else{
			return false;
		}

	}

	public function hasSave(string $name) : bool{
		return $this->player->exists($name);
	}

	public function initSave(string $name) : bool{

		$data = ["level" => 1, "exp" => 0, "sub1" => 10, "sub2" => 10, "gold" => 0, "maxmp" => 10, "mp" => 10, "magiclist" => [0,1], "magic" => 0, "job" => "wa", "jobdata" => [], "skill" => 5, "skilldata" => [], "ver" => 1, "op" => false];

		$this->player->set($name, $data);

		$this->gold->addGold($name, 1500);//$this->getMoneyClass()でもokです。

		$this->player->save();

		if($this->player->exists($name)){
			return true;
		}else{
			return false;
		}

	}

	public function checkSave(string $name){

		$data = $this->player->get($name);
		$player = $this->getServer()->getPlayer($name);

		if($player instanceof Player){

			if($player->isOp() && !$data["op"]){

				$data["op"] = true;
				$this->setSave($name, $data);

			}

		}

	}

	public function getSave(string $name){

		$data = $this->player->get($name);

		if(isset($data)){
			return $data;
		}else{
			return null;
		}

	}

	public function setSave(string $name, array $data) : bool{

		if(isset($data)){
			$this->player->set($name, $data);
			return true;
		}else{
			return false;
		}

	}

	public function getXp(){

		$data = $this->xp->get("xp");

		if(isset($data)){
			return $data;
		}else{
			return null;
		}

	}

	public function setXp(int $value) : bool{

		if(isset($value)){
			$this->xp->set("xp", $value);
			return true;
		}else{
			return false;
		}

	}

	public function tagUpdate(Player $player){

	}

	public function uploadFile(int $type = 0) : bool{

		//Todo...
		return false;

	}

	public function dataFileRegister(){

		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder(), 0744, true);
			$this->player = new Config($this->getDataFolder() . "data.yml", Config::YAML, []);
			$this->xp = new Config($this->getDataFolder() . "xp.json", Config::JSON, ["xp" => 1]);
			$this->updata = new Config($this->getDataFolder() . "online.json", Config::JSON, []);
			$this->login = new Config($this->getDataFolder() . "login.yml", Config::YAML, []);
			$this->ftp = new Config($this->getDataFolder() . "ftp.yml", Config::YAML, []);
		}

        $this->player = new Config($this->getDataFolder() . "data.yml", Config::YAML, []);
		$this->xp = new Config($this->getDataFolder() . "xp.json", Config::JSON, []);
		$this->updata = new Config($this->getDataFolder() . "online.json", Config::JSON, []);
		$this->login = new Config($this->getDataFolder() . "login.yml", Config::YAML, []);
		$this->ftp = new Config($this->getDataFolder() . "ftp.yml", Config::YAML, []);

	}

	public function classInit(){

		//classを初期化
		$this->gold = new Money($this);
		$this->exp = new Exp($this);
		$this->mp = new MP($this);
		$this->item = new ItemManager($this);
		$this->magic = new MagicManager($this);
		$this->loginsystem = new LoginSystem($this);
		$this->text = new SystemText($this);
		$this->jobmanager = new JobManager($this);

	}

}
