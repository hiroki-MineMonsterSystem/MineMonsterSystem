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
namespace System\system\item;

//このクラスではuseの使用は禁止です。

class ItemManager{

	private $main;

	public function __construct(\System\Main $main){

		$this->main = $main;

	}

	public function registerItem(){
		
		$path = str_replace("ItemManager.php", "",__FILE__);
		
		//echo $path;

		$classfiles = scandir($path, 1);

		foreach($classfiles as $file){
			
			if(is_dir($file))continue;

			if($file == "ItemManager.php")continue;

			$class = DIRECTORY_SEPARATOR . __NAMESPACE__ . DIRECTORY_SEPARATOR . str_replace(".php", "", $file);
			echo "[RegisterClass]" . $class . "\n";
			$item = $class::getCustomItem();//???

			$class::registerRecipe();

			\pocketmine\item\Item::addCreativeItem($item);

		}

   }
}