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

namespace System\mcpe\packet;

#include <rules/DataPacket.h>

use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info;


class MapInfoRequestPacket extends DataPacket{
	const NETWORK_ID = Info::MAP_INFO_REQUEST_PACKET;

	public $mapid;

	public function decode(){
		$this->mapid = $this->getVarInt();
	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->mapid);
	}
}
