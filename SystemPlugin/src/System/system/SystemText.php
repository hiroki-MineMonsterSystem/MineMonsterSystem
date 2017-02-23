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

use System\Main;


class SystemText{

	public $classVersion = 1.2;//float

	public static function systemText(string $key) : string{//非推奨(削除予定)

		$datas = [
					"exp" => "経験値",
					"gold" => "ギル",
					"lv" => "レベル",
					"skill" => "スキル",
					"point" => "ポイント",

					"s.exp" => "Exp",
					"s.lv" => "LV",
					"s.point" => "P",

					"lvup" => "レベルアップ",

					"get" => "手に入れた。",
					"lost" => "失った。",
					"use" => "使った。",
					"give" => "渡した。",

					"quest" => "クエスト",
					"questboard" => "クエストボード",

					"quest.get" => "クエスト依頼が増えました。",
					"quest.set" => "クエストを受けました。",
					"quest.clear" => "クエストを報告しました。",
					"quest.kill" => "討伐",
					"quest.item" => "採取"
		];

		if(isset($datas[$key])){
			$text = $datas[$key];
		}else{
			$text = "§bテキストがありません。<#text not found " . $key . ">";
		}

		return $text;

	}

	public static function systemSpaceText(string $textkey, array $fillkeys = []) : string{

		$datas = [
					"system.login" => "§aこのサーバーで遊ぶには/login <password>でログインする必要があります。",
					"system.login.ng" => "§cログインに失敗しました。",
					"system.login.ok" => "§aログインに成功しました。",
					"system.login.authok" => "§a機種変更コードの認証に成功しました。",
					"system.login.authng" => "§a機種変更コードの認証に失敗しました。",

					"system.register" => "§aこのサーバーで遊ぶには/register <password>で登録する必要があります。",
					"system.register.lenght" => "§cパスワードは8文字以上64文字以内の複雑な物にして下さい。",
					"system.register.twobyte" => "§cパスワードに2バイト文字は使えません。",
					"system.register.ng" => "§c登録に失敗しました。<既に登録されています。>",
					"system.register.ok" => "§e登録しました。<pass: #pass# >\n§e<機種変更コード: #code# >\n§a必ずスクリーンショットを撮ってください。",
					"system.login.authcode" => "§ccid、ipが変更されました。§cログインには、機種変更コードが必要です。",

					"magic.usage" => "§e[魔法情報]\n§b<魔法名: #name#>\n§b<消費MP: #mp#>\n§b<属性: #att#>\n§b<威力: #power#>\n§b<魔法を習得しているか: #have#>",
					"magic.list" => "§a<魔法ID: #id#  魔法名: #name#>",
					"magic.nomp" => "§cMPが足りません。",
					"magic.use" => "§b#name#!",
					"magic.set" => "§b#name#をセットしました。",
					"magic.get" => "§e[セット中の魔法]\n§b<魔法名: #name#>\n§b<消費MP: #mp#>\n§b<属性: #att#>\n§b<威力: #power#>",
					"magic.notfound" => "§cこのIDの魔法はありません。",
					"magic.nothave" => "§c#name#という魔法は習得していません。",
					
					"taptodo.tap" => "§a>>§bブロックをタップして下さい。",
					"taptodo.add" => "§a>>§b/#command# を登録しました。",
					"taptodo.del.ok" => "§a>>§bその場所のコマンドを削除しました。",
					"taptodo.del.ng" => "§a>>§bその場所にはコマンドが登録されていません。",

					"inn.yes" => "§aHPとMPが全回復しました。",
					"inn.no" => "§c泊まるには#gold#ギル必要です。",

					"login.1" => "§a[info]MineMonsterServerへようこそ！",
					"login.2" => "§b[info]wikiを熟読して下さい。\n§eリンク: http://",

					"kick.1" => "§c[system]セーブデータの解析に失敗しました。",

					"event.1" => "§e[event]経験値、ギル2倍イベント開催中!",
					"event.2" => "§e[event]土日祝日! 経験値、ギル2倍イベント開催中!",

					"get.exp" => "§b#name#は、#value# Exp手に入れた。",
					"get.gold" => "§b#name#は、#value# ギル手に入れた。",
					"get.skillp" => "§b#name#は、スキルポイントを #value# P手に入れた。",
					"get.magic" => "§b#name#を覚えた。",

					"lvup" => "§a#name#は、レベルアップ!",
					"lvup.value" => "§a#value1# LV ->  #value2# LV",
					"joblvup" => "§a#name#の、ジョブレベルが上がった!",
					"joblvup.value" => "§a#value1# LV ->  #value2# LV",

					"get.item1" => "§a#name#は、#item#を手に入れた。",
					"get.item2" => "§e#name#は、#item#を手に入れた。",
					
					"parmission.security" => "§a警備員以上の権限が必要です。",
					"parmission.op" => "§aOP以上の権限が必要です。",
					"parmission.admin" => "§a管理者以上の権限が必要です。"
		];

		if(isset($datas[$textkey])){
			$text = $datas[$textkey];
		}else{
			$text = "§bテキストがありません。<#text not found " . $textkey . ">";
		}

		foreach($fillkeys as $key => $value){

			$replacekey = "#" . $key . "#";

			$text = str_replace($replacekey, $value, $text);

		}

		return $text;

	}

}
