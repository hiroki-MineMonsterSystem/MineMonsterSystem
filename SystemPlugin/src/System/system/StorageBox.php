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

use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryCloseEvent;

use pocketmine\item\Item;

use pocketmine\utils\TextFormat;

use pocketmine\math\Vector3;

use pocketmine\inventory\ChestInventory;

use pocketmine\tile\Tile;
use pocketmine\tile\Chest;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

use pocketmine\utils\Config;

use System\Main;
use System\utils\HirokiFormat;

class StorageBox{

	public function __construct(Main $main){

		$this->main = $main;
		$this->data = new Config($this->main->getDataFolder() . "DB.yml", Config::YAML, []);
		$this->userdb = new Config($this->main->getDataFolder() . "User.json", Config::JSON, []);
		
		$item = Item::get(54)->setCustomName("StorageBox");
		
		Item::addCreativeItem($item);

		$this->chests = [];
		$this->players = [];

	}

	public function open(InventoryOpenEvent $event, Player $player, Tile $tile){

		$inv = $event->getInventory();
		$id = $this->chestId($inv);

		if(isset($this->chests[$id])){
  			$player->sendMessage(TextFormat::RED . "現在使用中です!");
  			$event->setCancelled(true);
  			return;
 		}

 		$data = $this->data->get($player->getName());

		if(!isset($data)){
			$d = $this->data->get($player->getName());
			if(!isset($d)){
				$this->data->set($player->getName(), []);
			}
			if($index > $user["MaxIndex"])return $player->sendMessage(TextFormat::RED . "あなたのボックスの最大ページは" . $user["MaxIndex"] . "ページです。");
			$d[$index] = [];
			$this->data->set($player->getName(), $d);

			$data = $this->data->get($player->getName())[$index];
		}

		$this->loadData($player, $inv);

 		$this->chests[$id] = true;
 		$this->players[$player->getName()] = $inv;

	}

	public function close(InventoryCloseEvent $event, Player $player, Tile $tile){

		$inv = $event->getInventory();
		$id = $this->chestId($inv);

		$this->saveData($player, $inv);

		unset($this->chests[$id]);
		unset($this->players[$player->getName()]);
	}

	protected function loadData(Player $player, ChestInventory $inv){
		$index = $this->userdb->get($player->getName())["Index"];
		$data = $this->data->get($player->getName());

		$id = $this->chestId($inv);

		if(isset($this->chests[$id])){
			$player->sendMessage(TextFormat::RED . "現在使用中です!(-1)");
		  return;
		}

		$inv->clearAll();

		$co = count($data[$index]);
		if($co == 0)return;

		$slot = 0;

		foreach($data[$index] as $item){
		  $itemo = HirokiFormat::h_Item_Decode($item);

		  if(!$itemo instanceof Item){
				echo "Noitem\n";
		  }else{
				$inv->setItem($slot, $itemo);
				++$slot;
		  }
		}
	}

	protected function saveData(Player $player, ChestInventory $inv){
		$index = $this->userdb->get($player->getName())["Index"];
	  $data = $this->data->get($player->getName());

	  $data[$index] = [];

	  $co = count($inv->getContents());
	  if($co == 0){
			$this->data->set($player->getName(), $data);
			$this->data->save();
			return;
	  }

	  foreach($inv->getContents() as $slot => $item){
			$data[$index][] = [HirokiFormat::h_Item_Encode($item)];
	  }

	  $this->data->set($player->getName(), $data);

	  $inv->clearAll();
	  $this->data->save();
	}

	protected static function chestId($obj) {
		if ($obj instanceof ChestInventory) $obj = $obj->getHolder();
		if ($obj instanceof Chest) $obj = $obj->getBlock();
		return implode(":",[$obj->getLevel()->getName(),(int)$obj->getX(),(int)$obj->getY(),(int)$obj->getZ()]);
	}

}
