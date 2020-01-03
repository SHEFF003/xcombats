<?php

namespace Core;

class Item {
	
	public static function lookStats($m) {
		$ist = array();
		$di = explode('|',$m);
		$i = 0; $de = false;
		while($i<count($di)) {
			$de = explode('=',$di[$i]);
			if(isset($de[0],$de[1])) {
				if(!isset($ist[$de[0]])) {
					$ist[$de[0]] = 0;
				}
				$ist[$de[0]] = $de[1];
			}
			$i++;
		}
		return $ist;
	}
	
	public static function getItem( $id ) {
		$itm = \Core\Database::query( 'SELECT `a`.*,`b`.* FROM `items_main` AS `a` LEFT JOIN `items_main_data` AS `b` ON `b`.`items_id` = `a`.`id` WHERE `a`.`id` = :item_id LIMIT 1' , array(
			'item_id' 	=> $id
		) , true );
		return $itm;
	}
	
	public static function getItemUser( $id ) {
		//uiid - id предмета в items_users
		$itm = \Core\Database::query( 'SELECT `a`.*,`b`.*,`a`.`id` AS `uiid` FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `b`.`id` = `a`.`item_id` WHERE `a`.`id` = :item_id LIMIT 1' , array(
			'item_id' 	=> $id
		) , true );
		return $itm;
	}
	
	public static function infoItem( $itm ) {
		//$po = self::lookStats( $itm['data'] );
		//
		$r = array(
		//items_main
			$itm['id'],
			$itm['name'],
			$itm['img'],
			$itm['2h'],
			$itm['2too'],
			$itm['iznosMAXi'],
			$itm['price1'], //6
			$itm['price2'],
			$itm['magic_chance'],
			$itm['info'],
			$itm['massa'],
			$itm['geni'],
		//items_users 12
			$itm['1price'], //12
			$itm['2price'],
			$itm['use_text'],
			$itm['iznosNOW'],
			$itm['iznosMAX'],
			$itm['gift'],
			$itm['magic_inc'],
			$itm['maidin'],
		//data 20
			$itm['data'],
		//group items_user
			$itm['inGroup'], //21
			$itm['uiid'],
			//
			OK
		
		);
		return $r;
	}
		
}

?>