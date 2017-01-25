<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace System\entity;

use pocketmine\level\format\Chunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\entity\Projectile;
use pocketmine\entity\Item as EntityItem;
use pocketmine\entity\Entity;
use pocketmine\level\Explosion;
use pocketmine\event\entity\ExplosionPrimeEvent;

class Magic extends Projectile{
	const NETWORK_ID = 64;

	public $width = 0.5;
	public $length = 0.5;
	public $height = 0.5;

	protected $gravity = 0;
	protected $drag = 0;

	protected $damage = 0;
	protected $attribute = 0;

	protected $isCritical;
	
	protected $time;

	public function __construct(Chunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null, $critical = true, $item, $damage = 2, $attribute = 0, $time = 200){
		$this->isCritical = (bool) $critical;
		$this->attribute = $attribute;
		$this->time = $time;
		
		if($item instanceof Item){
			$this->item = $item;
		}else{
			$this->item = Item::get(1, 0, 1);
		}

		$this->damage = $damage;
		
		parent::__construct($chunk, $nbt, $shootingEntity);

	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		$this->checkNearEntities($currentTick);

		$this->timings->stopTiming();

		return $hasUpdate;
	}
	
	protected function checkNearEntities($tickDiff){
		foreach($this->level->getNearbyEntities($this->boundingBox->grow(1, 0.5, 1), $this) as $entity){
			$entity->scheduleUpdate();

			if(!$entity->isAlive()){
				continue;
			}
			
			if($entity instanceof Player){
				continue;
			}else{
				//...Todo
			}
		}
	}


	public function spawnTo(Player $player){
	
		if($this->item instanceof Item){
		}else{
			$this->item = Item::get(1, 0, 1);
		}
	
		$pk = new AddItemEntityPacket();
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->item = $this->item;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}