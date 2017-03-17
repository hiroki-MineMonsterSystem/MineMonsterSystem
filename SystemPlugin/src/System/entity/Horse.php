<?php

namespace System\entity;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\entity\Attribute;
use pocketmine\entity\Creature;
use pocketmine\event\Timings;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\UpdateAttributesPacket;
use pocketmine\network\protocol\MobArmorEquipmentPacket;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class Horse extends Rideable{

	const NETWORK_ID = 23;

	const DATA_HORSE_TYPE = 19;

	const COOL_DOWN_TICK = 30;
	const AUTO_COOL_DOWN_TICK = 5;

	public $width = 0.6;
	public $length = 1.8;
	public $height = 1.8;

	public $speed = 0.33;
	public $jump_power = 3.5;
	public $ridePos = [-0.02, 2.3, 0.19];

	public $jump = false;
	public $coolDown = 0;

	public $typeList = [-1,0,1,2,6,7,8,9,10,12,14,15];//Type Array

	public function getName(){
		return "Horse";
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = self::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;

		//$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_SADDLED, true);//Lag! Client Bug??
		$this->setDataProperty(self::DATA_HORSE_TYPE, Entity::DATA_TYPE_BYTE, $this->typeList[mt_rand(0,count($this->typeList) - 1)]);

		$pk->metadata = $this->dataProperties;

		$player->dataPacket($pk);

		$this->sendAttribute($player);

		parent::spawnTo($player);

	}

	public function sendAttribute(Player $player){
		$entry = $this->getAttributeMap()->getAll();
		$entry[] = Attribute::addAttribute(12, "minecraft:horse.jump_strength", 0, 3, 0.6679779);

		$pk = new UpdateAttributesPacket();
		$pk->entries = $entry;
		$pk->entityId = $this->getId();
		$player->dataPacket($pk);

	}

	public function getSaveId(){
		$class = new \ReflectionClass(static::class);
		return $class->getShortName();
	}

	public function entityBaseTick($tickDiff = 1){
			Timings::$timerEntityBaseTick->startTiming();

			$hasUpdate = parent::entityBaseTick($tickDiff);

			if($this->jump){
				if($this->coolDown == self::COOL_DOWN_TICK){
					$this->jump = false;
					$this->coolDown = 0;
				}else{
					++$this->coolDown;
				}
			}

			if(!$this->isLinked){
				$this->motionX = 0;
				$this->motionZ = 0;
			}

			if($this->onGround){
        $this->motionY = 0;
      }else{
        $this->motionY -= 0.05;
      }
			if($this->move($this->motionX, $this->motionY, $this->motionZ)){
				$this->updateMovement();
			}

			Timings::$timerEntityBaseTick->startTiming();
			return $hasUpdate;
	}

	public function setMovement($motionX, $motionY){
		if($motionY == 1){
			$x = -sin($this->linkedentity->getYaw() / 180 * M_PI) * $this->speed;
		  $z = cos($this->linkedentity->getYaw() / 180 * M_PI) * $this->speed;
			$this->motionX = $x;
			$this->motionZ = $z;
			$this->yaw = $this->linkedentity->getYaw();
			if($this->move($this->motionX, 0, $this->motionZ)){
				$this->updateMovement();
			}
		}elseif($motionY == -1){
			$x = sin($this->linkedentity->getYaw() / 180 * M_PI) * ($this->speed - 0.22);
			$z = -cos($this->linkedentity->getYaw() / 180 * M_PI) * ($this->speed - 0.22);
			$this->motionX = $x;
			$this->motionZ = $z;
			$this->yaw = $this->linkedentity->getYaw();
			if($this->move($this->motionX, 0, $this->motionZ)){
				$this->updateMovement();
			}
		}else{
			$this->motionX = 0;
			$this->motionZ = 0;
		}
	}

	public function setJump(){
		if(!$this->jump){
			$this->motionY = $this->jump_power;
			if($this->move($this->motionX, $this->motionY, $this->motionZ)){
				$this->updateMovement();
			}
			$this->motionY = 0;
			$this->jump = true;
		}
	}
}
