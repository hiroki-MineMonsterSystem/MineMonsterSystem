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

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;

use pocketmine\math\Vector3;

use pocketmine\item\Item;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerAchievementAwardedEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\event\server\RemoteServerCommandEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerExperienceChangeEvent;
use pocketmine\event\player\PlayerLoginEvent;

use pocketmine\network\protocol\UseItemPacket;
use pocketmine\network\protocol\InteractPacket;
use pocketmine\network\protocol\PlayerActionPacket;
use pocketmine\network\protocol\PlayerInputPacket;
use pocketmine\network\protocol\UpdateTradePacket;
use pocketmine\network\protocol\UpdateAttributesPacket;
use pocketmine\network\protocol\ContainerOpenPacket;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;

use System\mcpe\packet\MapInfoRequestPacket;

use System\mcpe\Inventory\TestInventory;

use pocketmine\inventory\ChestInventory;

use System\Main;

use System\entity\Rideable;

use System\system\SystemText;

use System\event\EnemyKill;
use System\event\UseItem;
use System\event\UseMap;

use System\system\INN;
use System\system\StorageBox;
use System\system\MapRenderer;
use System\system\SpawnerSystem;
use System\system\HealthBar;
use System\system\TradeSystem;

use System\system\SystemParmission;


class EventListener implements Listener{

	private $main;

	public function __construct(Main $main){

		$this->main = $main;
		$this->text = new SystemText();

	}

	public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $e){

		$player = $e->getPlayer();
		$name = $player->getName();
		$mes = $e->getMessage();
		$ex = explode(" ",$mes);
		$cancel = true;

		if(!$this->main->getLoginSystem()->hasLogin($player)){
			if($this->main->getLoginSystem()->loginCheck($player) == 3){
				switch($ex[0]){
					case "/authcode":
					case "./authcode":

						$cancel = false;
						break;
				}
				if($cancel){
					$e->setCancelled();
				}
			}else{
				switch($ex[0]){
					case "/login":
					case "./login":
					case "/register":
					case "./register":

						$cancel = false;
						break;
				}
				if($cancel){
					$e->setCancelled();
				}
			}
		}

		if(!SystemParmission::hasChangeParm($name)){
			switch($ex[0]){
				//Todo...
			}
		}

	}

	public function onPlayerJoin(PlayerJoinEvent $e){

		$player = $e->getPlayer();
		$name = $player->getName();

		if(!$this->main->hasSave($name)){
			if(!$this->main->initSave($name)){
				return $player->kick($this->text->systemSpaceText("kick.1"));
			}
		}

		$this->main->getLoginSystem()->loginAuth($player);

		$player->sendMessage($this->text->systemSpaceText("login.1"));

		$this->main->getStorageBox()->initData($player);

		$this->main->checkSave($name);

	}

	public function onQuit(PlayerQuitEvent $event){

		$player = $event->getPlayer();

		foreach($this->main->getStorageBox()->players as $p => $i){
			if($p == $player->getName()){
				$this->main->getStorageBox()->invClose(new InventoryCloseEvent($i, $player));
				$id = $this->main->getStorageBox()->chestId($i);
				unset($this->main->getStorageBox()->chests[$id]);
				unset($this->main->getStorageBox()->players[$player->getName()]);
			}
		}
	}

	public function onPlayerMove(PlayerMoveEvent $e){

		$player = $e->getPlayer();
		$name = $player->getName();

		if(!$this->main->getLoginSystem()->hasLogin($player)){
			$this->main->getLoginSystem()->loginAuth($player);
			$e->setCancelled();
			return;
		}

		//MapRenderer::setDecoration($player, 1, [["rot" => 180, "img" => 0, "xOffset" => intval($player->x) - 256, "yOffset" => intval($player->z) - 256, "label" => "Player", "color" => 0xffffffff]]);

	}

	public function onPlayerInteract(PlayerInteractEvent $e){

		$player = $e->getPlayer();
		$block = $e->getBlock();
		$name = $player->getName();
		$h = $player->getInventory()->getItemInHand()->getID();

		if(!$this->main->getLoginSystem()->hasLogin($player)){
			$this->main->getLoginSystem()->loginAuth($player);
			$e->setCancelled();
			return;
		}

		if(!SystemParmission::hasChangeParm($name)){
			if($h == 325 or $h == 259 or $h == 256 or $h == 269 or $h == 273 or $h == 277 or $h == 284 or $h == 290 or $h == 291 or $h == 292 or $h == 293 or $h == 294){

				$player->sendMessage("このワールドでは、このアイテムのタップを実行できません");
				$e->setCancelled();
				return;
			}
		}

		if($h == 347){
			$player->sendMessage("Pos: [" . $block->x . "-" . $block->y . "-" . $block->z . "]\nBlockData: [" . $block->getID() . ":" . $block->getDamage() . "]");
		}

		$taptodo = $this->main->getTapToDo();
		if($taptodo->getFlag($player) == "Del"){
			$taptodo->delCommand($player, $block->x, $block->y, $block->z);
		}elseif($taptodo->getFlag($player) == "Add"){
			$taptodo->addCommand($player, $block->x, $block->y, $block->z, $taptodo->getCommand($player));
		}else{
			$taptodo->executionCommand($player, $block->x, $block->y, $block->z);
		}

		if($block->getID() == 26){
			$inn = new INN($this->main);
			$inn->sleepINN($player);
			$e->setCancelled();
			return;
		}
/*
		$nbt = SpawnerSystem::createNBT($player, new Vector3(0,0,0));//Todo... ClientClash!

		$entity = Entity::createEntity("Villager", $player->level, $nbt);
		$entity->spawnToAll();
*/
	}

	public function onBlockBreak(BlockBreakEvent $e){

		$player = $e->getPlayer();
		$item = $e->getItem();
		$block = $e->getBlock();
		$name = $player->getName();

		if($block->getID() == 35){
			$this->main->getItemBox()->openBox($player);
		}

		if(!$this->main->getLoginSystem()->hasLogin($player) || !SystemParmission::hasChangeParm($name)){
			$e->setCancelled();
			return;
		}

	}

	public function onBlockPlace(BlockPlaceEvent $e){

		$player = $e->getPlayer();
		$item = $e->getItem();
		$name = $player->getName();

		if(!$this->main->getLoginSystem()->hasLogin($player) || !SystemParmission::hasChangeParm($name)){
			$e->setCancelled();
			return;
		}

	}

	public function onPlayerItemConsume(PlayerItemConsumeEvent $e){

		$player = $e->getPlayer();
		$item = $e->getItem();
		$name = $player->getName();

		if(isset($item->getNamedTag()->Ev)){

			if($item->getNamedTag()->Sp->getValue() == 1 && isset($item->getNamedTag()->Sp)){

				$id = $item->getNamedTag()->Sp->getValue();

				if($id == -1){
				}else{
					$class = new UseItem($this->main);
					$class->itemSP($player, $id);
					return;
				}

			}else{
				return;
			}

		}

	}

	public function onInventoryOpen(InventoryOpenEvent $e){
		$player = $e->getPlayer();
		$inv = $e->getInventory();

		if($e->isCancelled()){
			return;
		}

		$tile = $inv->getHolder();

		if($inv instanceof ChestInventory){

			if($tile->getName() == "StorageBox"){

				$this->main->getStorageBox()->open($e, $player, $tile);

			}

		}
	}

	public function onInventoryClose(InventoryCloseEvent $e){
		$player = $e->getPlayer();
 		$inv = $e->getInventory();

		$tile = $inv->getHolder();

		if($inv instanceof ChestInventory){

			if($tile->getName() == "StorageBox"){

				$this->main->getStorageBox()->close($e, $player, $tile);

			}

		}
	}

	public function onEntityDeath(EntityDeathEvent $ev){

		$e = $ev->getEntity()->getLastDamageCause();
		$exp = $this->main->getExpClass();

		if($e instanceof EntityDamageByEntityEvent){

			$entity = $ev->getEntity();
			$tag = $entity->getNameTag();
			$player = $e->getDamager();
			$name = $player->getName();

			if($player instanceof Player){
				if($entity instanceof Player){

					//ToDo PvP用

				}else{

					$class = new EnemyKill($this->main);
					if(!$class->onEnemyKill($player,$entity)){//敵を倒した時
						echo "Non LvTag\n";
					}
				}


			}

		}

	}

	public function onDataPacketReceive(DataPacketReceiveEvent $e){

		$pk = $e->getPacket();
		$player = $e->getPlayer();
		$name = $player->getName();

		if($pk instanceof PlayerActionPacket){

			if($pk->action === PlayerActionPacket::ACTION_START_SNEAK && $player->isSurvival()){

				//ToDo...

			}

		}elseif($pk instanceof InteractPacket){

			if($pk->action == InteractPacket::ACTION_RIGHT_CLICK){

				$entity = $player->level->getEntity($pk->target);

				if($entity instanceof Rideable){
					if($entity->isLinked){
					}else{
						$entity->rideEntity($entity, $player);
					}
				}else{
					//TradeSystem::startTrade($player, $entity, TradeSystem::getTradeData());
				}
			}elseif($pk->action == InteractPacket::ACTION_LEAVE_VEHICLE){

				$entity = $player->level->getEntity($pk->target);

				if($entity instanceof Rideable){
					if($entity->isLinked){
						$entity->unrideEntity($entity, $player);
					}else{
					}
				}
			}

		}elseif($pk instanceof PlayerInputPacket){

			if($player->isLinked){
				$player->linkedentity->setMovement($pk->motionX, $pk->motionY);
				if($pk->unknownBool1){
					$player->linkedentity->setJump();
				}
			}

		}elseif($pk instanceof UseItemPacket){

			$magic = $this->main->getMagicManager()->getMagic($name);

			if($pk->face == -1){

				if(isset($pk->item->getNamedTag()->Ev)){

					if($pk->item->getNamedTag()->Sp->getValue() == 0 && isset($pk->item->getNamedTag()->Sp)){

						$id = $pk->item->getNamedTag()->Sp->getValue();

						if($id == -1){
						}else{
							$class = new UseItem($this->main);
							$class->itemSP($player, $id);
							return;
						}

					}else{
						return;
					}

				}

				switch($pk->item->getID()){

					case 267:
					case 268:
					case 272:
					case 276:
					case 280:
					case 283:
						return;
						break;

					case 358:
						$class = new UseMap($this->main);
						$class->sendData($player);
						return;
						break;

				}

				if($magic != null){

					$this->main->getMagicManager()->shotMagic($player, $magic);
					return;

				}

			}

		}elseif($pk instanceof MapInfoRequestPacket){
			MapRenderer::mapRender($player, 1, $this->main->getDataFolder(), "Map1");
		}elseif($pk instanceof UpdateTradePacket){
			print("Trade\n");
		}
	}
}
