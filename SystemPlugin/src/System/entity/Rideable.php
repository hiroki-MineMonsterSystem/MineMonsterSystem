<?php

namespace System\entity;

use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Timings;
use pocketmine\level\Level;
use pocketmine\math\Math;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\network\protocol\SetEntityLinkPacket;
use pocketmine\Player;

abstract class Rideable extends Living{

  public $isLinked = false;
  public $linkedentity = null;

  public function rideEntity(Entity $target, Entity $rider){//Link用関数
    $this->isLinked = true;
		$this->linkedentity = $rider;
    $rider->isLinked = true;
		$rider->linkedentity = $target;

    if($target instanceof Rideable){
      $rider->setDataProperty(57, 8, $target->ridePos, true);
    }

    $pk = new SetEntityLinkPacket();
		$pk->from = $target->getId();
		$pk->to = $rider->getId();
		$pk->type = 2;

    $this->server->broadcastPacket($this->level->getPlayers(), $pk);
  }

  public function unrideEntity(Entity $target, Entity $rider){//link解除用関数
    $this->isLinked = false;
		$this->linkedentity = null;
    $rider->isLinked = false;
		$rider->linkedentity = null;

    $pk = new SetEntityLinkPacket();
		$pk->from = $target->getId();
		$pk->to = $rider->getId();
		$pk->type = 3;
		$this->server->broadcastPacket($this->level->getPlayers(), $pk);
  }

  public function updateMovement(){//Mobスムージング関連
    if(
      $this->lastX !== $this->x
      || $this->lastY !== $this->y
      || $this->lastZ !== $this->z
      || $this->lastYaw !== $this->yaw
      || $this->lastPitch !== $this->pitch
    ){
      $this->lastX = $this->x;
      $this->lastY = $this->y;
      $this->lastZ = $this->z;
      $this->lastYaw = $this->yaw;
      $this->lastPitch = $this->pitch;
    }
    $this->level->addEntityMovement($this->chunk->getX(), $this->chunk->getZ(), $this->id, $this->x, $this->y, $this->z, $this->yaw, $this->pitch);
  }

  public function move($dx, $dy, $dz) : bool{//Mobスムージング関連
	  Timings::$entityMoveTimer->startTiming();

	  $movX = $dx;
	  $movY = $dy;
	  $movZ = $dz;

	  $list = $this->level->getCollisionCubes($this, $this->level->getTickRate() > 1 ? $this->boundingBox->getOffsetBoundingBox($dx, $dy, $dz) : $this->boundingBox->addCoord($dx, $dy, $dz));
	  if(true){
	    foreach($list as $bb){
	      $dx = $bb->calculateXOffset($this->boundingBox, $dx);
	    }
	    $this->boundingBox->offset($dx, 0, 0);

	    foreach($list as $bb){
	      $dz = $bb->calculateZOffset($this->boundingBox, $dz);
	    }
	    $this->boundingBox->offset(0, 0, $dz);
	  }
	  foreach($list as $bb){
		  $dy = $bb->calculateYOffset($this->boundingBox, $dy);
	  }
	  $this->boundingBox->offset(0, $dy, 0);

	  $this->setComponents($this->x + $dx, $this->y + $dy, $this->z + $dz);
	  $this->checkChunks();

	  $this->checkGroundState($movX, $movY, $movZ, $dx, $dy, $dz);
	  $this->updateFallState($dy, $this->onGround);

	  Timings::$entityMoveTimer->stopTiming();
	  return true;
	}
}
