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
namespace System\engine;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;

use pocketmine\level\Position;

use pocketmine\network\protocol\LevelSoundEventPacket;

use System\Main;

class SoundEngine{

  /*const SOUND_ITEM_USE_ON = 0;
  const SOUND_HIT = 1;
  const SOUND_STEP = 2;
  const SOUND_JUMP = 3;
  const SOUND_BREAK = 4;
  const SOUND_PLACE = 5;
  const SOUND_HEAVY_STEP = 6;
  const SOUND_GALLOP = 7;
  const SOUND_FALL = 8;
  const SOUND_AMBIENT = 9;
  const SOUND_AMBIENT_BABY = 10;
  const SOUND_AMBIENT_IN_WATER = 11;
  const SOUND_BREATHE = 12;
  const SOUND_DEATH = 13;
  const SOUND_DEATH_IN_WATER = 14;
  const SOUND_DEATH_TO_ZOMBIE = 15;
  const SOUND_HURT = 16;
  const SOUND_HURT_IN_WATER = 17;
  const SOUND_MAD = 18;
  const SOUND_BOOST = 19;
  const SOUND_BOW = 20;//-1,1
  const SOUND_SQUISH_BIG  = 21;
  const SOUND_SQUISH_SMALL = 22;
  const SOUND_FALL_BIG = 23;
  const SOUND_FALL_SMALL = 24;
  const SOUND_SPLASH = 25;
  const SOUND_FIZZ = 26;
  const SOUND_FLAP = 27;
  const SOUND_SWIM = 28;
  const SOUND_DRINK = 29;
  const SOUND_EAT = 30;
  const SOUND_TAKEOFF = 31;
  const SOUND_SHAKE = 32;
  const SOUND_PLOP = 33;
  const SOUND_LAND = 34;
  const SOUND_SADDLE = 35;
  const SOUND_ARMOR = 36;
  const SOUND_ADD_CHEST = 37;
  const SOUND_THROW = 38;//-1,319
  const SOUND_ATTACK = 39;
  const SOUND_ATTACK_NODAMAGE = 40;//-1,319
  const SOUND_WARN = 41;
  const SOUND_SHEAR = 42;
  const SOUND_MILK = 43;
  const SOUND_THUNDER = 44;//-1,93
  const SOUND_EXPLODE = 45;//-1,2
  const SOUND_FIRE = 46;
  const SOUND_IGNITE = 47;
  const SOUND_FUSE = 48;
  const SOUND_STARE = 49;
  const SOUND_SPAWN = 50;
  const SOUND_SHOOT = 51;
  const SOUND_BREAK_BLOCK = 52;
  const SOUND_REMEDY = 53;
  const SOUND_UNFECT = 54;
  const SOUND_LEVELUP = 55;
  const SOUND_BOW_HIT = 56;
  const SOUND_BULLET_HIT = 57;
  const SOUND_EXTINGUISH_FIRE = 58;
  const SOUND_ITEM_FIZZ = 59;
  const SOUND_CHEST_OPEN = 60;//-1,1
  const SOUND_CHEST_CLOSED = 61;//-1,1
  const SOUND_POWER_ON = 62;//-1,1
  const SOUND_POWER_OFF = 63;//-1,1
  const SOUND_ATTACH = 64;//-1,1
  const SOUND_DETACH = 65;//-1,1
  const SOUND_DENY = 66;//-1,1
  const SOUND_TRIPOD = 67;//-1,1
  const SOUND_POP = 68;//-1,1
  const SOUND_DROP_SLOT = 69;//-1,1
  const SOUND_NOTE = 70;
  const SOUND_THORNS = 71;//?
  const SOUND_PISTON_IN = 72;//-1,1
  const SOUND_PISTON_OUT = 73;//-1,1
  const SOUND_PORTAL = 74;//-1,1
  const SOUND_WATER = 75;//-1,1
  const SOUND_LAVA_POP = 76;//-1,1
  const SOUND_LAVA = 77;//-1,1
  const SOUND_BURP = 78;//-1,1
  const SOUND_BUCKET_FILL_WATER = 79;//-1,1
  const SOUND_BUCKET_FILL_LAVA = 80;//-1,1
  const SOUND_BUCKET_EMPTY_WATER = 81;//-1,1
  const SOUND_BUCKET_EMPTY_LAVA = 82;//-1,1
  const SOUND_GUARDIAN_FLOP = 83;//?
  const SOUND_ELDERGUARDIAN_CURSE = 84;//-1,319
  const SOUND_MOB_WARNING = 85;//?
  const SOUND_MOB_WARNING_BABY = 86;//?
  const SOUND_TELEPORT = 87;//?
  const SOUND_SHULKER_OPEN = 88;//?
  const SOUND_SHULKER_CLOSE = 89;//?
  const SOUND_DEFAULT = 90;//?
  const SOUND_UNDEFINED = 91;*/

  public static function playSoundByPositions(array $pos, int $id, int $volume = 100, int $pitch = 0){

    foreach($pos as $p){
      if($p instanceof Position){
        $pk = new LevelSoundEventPacket();

        $pk->sound = $id;
        $pk->x = (int)$p->x;
        $pk->y = (int)$p->y;
        $pk->z = (int)$p->z;
        $pk->volume = $volume;
        $pk->pitch = $pitch * 1000;

        Server::broadcastPacket($p->level->getPlayers(), $pk);
      }
    }

  }

  public static function playSound(Position $pos, int $id, int $volume = -1, int $pitch = 0){

    $pk = new LevelSoundEventPacket();

    $pk->sound = $id;
    $pk->x = (int)$pos->x;
    $pk->y = (int)$pos->y;
    $pk->z = (int)$pos->z;
    $pk->volume = (int)$volume;
    $pk->pitch = (int)$pitch;
    $pk->unknownBool = false;
    $pk->unknownBool2 = false;

    Server::broadcastPacket($pos->level->getPlayers(), $pk);

  }

  public static function playSoundByPlayers(array $players, int $id, int $volume = 100, int $pitch = 0){

    foreach($players as $player){
      if($player instanceof Player){
        $pk = new LevelSoundEventPacket();

        $pk->sound = $id;
        $pk->x = $player->x;
        $pk->y = $player->y;
        $pk->z = $player->z;
        $pk->volume = $volume;
        $pk->pitch = $pitch * 1000;

        $player->dataPacket($pk);
      }
    }

  }

  public static function playSoundByPlayer(Player $player, int $id, int $volume = 100, int $pitch = 0){

    $pk = new LevelSoundEventPacket();

    $pk->sound = $id;
    $pk->x = $player->x;
    $pk->y = $player->y;
    $pk->z = $player->z;
    $pk->volume = $volume;
    $pk->pitch = $pitch * 1000;

    $player->dataPacket($pk);

  }

}
