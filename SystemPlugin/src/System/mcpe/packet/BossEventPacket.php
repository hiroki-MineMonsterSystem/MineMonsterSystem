<?php

namespace System\mcpe\packet;

#include <rules/DataPacket.h>

use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info;

use pocketmine\network\mcpe\NetworkSession;

class BossEventPacket extends DataPacket{
	const NETWORK_ID = Info::BOSS_EVENT_PACKET;

	/* S2C: Shows the boss-bar to the player. */
	const TYPE_SHOW = 0;
	/* C2S: Registers a player to a boss fight. */
	const TYPE_REGISTER_PLAYER = 1;
	/* S2C: Removes the boss-bar from the client. */
	const TYPE_HIDE = 2;
	/* C2S: Unregisters a player from a boss fight. */
	const TYPE_UNREGISTER_PLAYER = 3;
	/* S2C: Appears not to be implemented. Currently bar percentage only appears to change in response to the target entity's health. */
	const TYPE_HEALTH_PERCENT = 4;
	/* S2C: Also appears to not be implemented. Title client-side sticks as the target entity's nametag, or their entity type name if not set. */
	const TYPE_TITLE = 5;
	/* S2C: Misc flags affecting various things. TODO: find flag values. */
	const TYPE_FLAGS = 6;
	/* S2C: Not implemented :( Intended to alter bar appearance, but these currently produce no effect on client-side whatsoever. */
	const TYPE_TEXTURE = 7;

	public $bossEid;
	public $eventType;

	/** @var int (long) */
	public $playerEid;
	/** @var float */
	public $healthPercent;
	/** @var string */
	public $title;
	/** @var int */
	public $flags;
	/** @var int */
	public $unknownVarint1; //unsigned
	/** @var int */
	public $unknownVarint2; //unsigned

	public function decode(){
		$this->bossEid = $this->getEntityId();
		$this->eventType = $this->getUnsignedVarInt();
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
				$this->playerEid = $this->getEntityUniqueId();
				break;
			case self::TYPE_SHOW:
				$this->title = $this->getString();
				$this->healthPercent = $this->getLFloat();
			case self::TYPE_FLAGS:
				$this->flags = $this->getLShort($this->flags);
			case self::TYPE_TEXTURE:
				//Colour and overlay respectively. No idea which way round, they appear to not work currently.
				$this->unknownVarint1 = $this->getUnsignedVarInt();
				$this->unknownVarint2 = $this->getUnsignedVarInt();
				break;
			case self::TYPE_HEALTH_PERCENT:
				$this->healthPercent = $this->getLFloat();
				break;
			case self::TYPE_TITLE:
				$this->title = $this->getString();
			default:
				break;
		}
	}

	public function encode(){
		$this->reset();
		$this->putEntityId($this->bossEid);
		$this->putUnsignedVarInt($this->eventType);
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
				$this->putEntityUniqueId($this->playerEid);
				break;
			case self::TYPE_SHOW:
				$this->putString($this->title);
				$this->putLFloat($this->healthPercent);
			case self::TYPE_FLAGS:
				$this->putLShort($this->flags);
			case self::TYPE_TEXTURE:
				$this->putUnsignedVarInt($this->unknownVarint1);
				$this->putUnsignedVarInt($this->unknownVarint2);
				break;
			case self::TYPE_HEALTH_PERCENT:
				$this->putLFloat($this->healthPercent);
				break;
			case self::TYPE_TITLE:
				$this->putString($this->title);
				break;
			default:
				break;
		}
	}
}
