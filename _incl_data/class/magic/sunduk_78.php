<?
if(!defined('GAME'))
{
	die();
}	
	if($tr['var_id'] != '') {
		 
		$io = '';
		
		/*
			1. Выпадает артефакт уровня персонажа - 1% , временный , не продается , судьба , 3 дн. срок годности
			2. Выпадает либо 5 екр. свиток, либо свиток +200% опыта на 3 часа (без заморозки)
			3. 1 из 4 эликсирова +15 (екр.)
			4. Чек 20 кр.
		*/
		$trgos = false;
		
		$key1 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4460 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		$key2 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4461 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		$key3 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4462 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		$key4 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4463 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		
		if( isset($key1['id']) && isset($key2['id']) && isset($key3['id']) && isset($key4['id']) ) {
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = '.$key1['id'].' OR `id` = '.$key2['id'].' OR `id` = '.$key3['id'].' OR `id` = '.$key4['id'].' LIMIT 4');
			$trgos = true;
		}
		
		if( $trgos == true ) {
			if( rand(0,100) == 57 || $this->info['admin'] > 0 ) {
				//Выдаем артефакт
				$io .= '<i>Временный артефакт</i>, ';
				$arts_1 = array();
				$arts_lvl = $this->info['level'];
				if( $arts_lvl < 4 ) {
					$arts_lvl = 4;
				}elseif( $arts_lvl > 10 ) {
					$arts_lvl = 10;
				}
				$sp1 = mysql_query('SELECT `items_id` FROM `items_main_data` WHERE `data` LIKE "%|art=%" AND `data` LIKE "%tr_lvl='.$arts_lvl.'%"');
				while( $pl1 = mysql_fetch_array($sp1) ) {
					$arts_1[] = $pl1['items_id'];
				}
				$arts_1 = $arts_1[rand(0,count($arts_1)-1)];
				if( $arts_1 > 0 ) {
					$this->addItem($arts_1,$this->info['id'],'|nosale=1|srok='.(3*86400).'',NULL,50);
				}
				//echo '['.$arts_1[rand(0,count($arts_1)-1)].'!'.count($arts_1).'!'.$arts_lvl.']';
			}
			
			if( rand(0,1) == 1 ) {
				//Выдаем чек +5 екр.
				$io .= '<i>Чек на 5 екр.</i>, ';
				$this->addItem(4465,$this->info['id'],'|sudba='.$this->info['login']);
			}else{
				//Выдаем свиток +200%
				$io .= '<i>Свиток +100% опыта</i>, ';
				$this->addItem(4466,$this->info['id'],'|sudba='.$this->info['login']);
			}
			
			$elik_1 = array(
				4037,4040,4038,4039
			);
			$elik_1 = $elik_1[rand(0,3)];
			if( $elik_1 > 0 ) {
				//Выдаем 1 из 4 эликов
				$io .= '<i>Эликсир +15 статов</i>, ';
				$this->addItem($elik_1,$this->info['id'],'|nosale=1|sudba='.$this->info['login'],NULL,1);
			}
			
			//Выдаем чек на 20 кр.
			$io .= '<i>Чек на 20 кр.</i> ';
			$this->addItem(4464,$this->info['id'],'|sudba='.$this->info['login']);
		}else{
			$no_open_itm = true;		
			$this->error = 'Требуется 4 различных ключа с поля битвы.';
		}
		
	}
	unset($i5,$i3,$i4);
?>