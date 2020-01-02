<?
if(!defined('GAME'))
{
	die();
}	
	if($tr['var_id'] != '') {
		 
		$io = '';
		
		/*
			1. Требует 8 любых ключа
		*/
		$trgos = false;
		/*
			$key1 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4460 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
			$key2 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4461 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
			$key3 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4462 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
			$key4 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4463 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		*/
		$keyall = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE (`item_id` = 4460 OR `item_id` = 4461 OR `item_id` = 4462 OR `item_id` = 4463) AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		if( $keyall[0] >= 8 ) {
			mysql_query('DELETE FROM `items_users` WHERE (`item_id` = 4460 OR `item_id` = 4461 OR `item_id` = 4462 OR `item_id` = 4463) AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 8');
			//mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = '.$key1['id'].' OR `id` = '.$key2['id'].' OR `id` = '.$key3['id'].' OR `id` = '.$key4['id'].' LIMIT 4');
			$trgos = true;
		}
		
		if( $trgos == true ) {
			//
			$io .= '<i>Целебный пирог (х3)</i>, ';
			$this->addItem(1028,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			$this->addItem(1028,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//
			$io .= '<i>Запас маны (х3)</i>, ';
			$this->addItem(1029,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			$this->addItem(1029,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//
			$io .= '<i>Восстановление энергии 1500HP</i>, ';
			$this->addItem(4022,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//
			$io .= '<i>Восстановление энергии 1500MP</i>, ';
			$this->addItem(4034,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//
			$io .= '<i>Пропуск Забытых</i>, ';
			$this->addItem(2412,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//
			$io .= '<i>Нападение</i>, ';
			$this->addItem(865,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//
			$io .= '<i>Зелье Жизни</i>, ';
			$this->addItem(724,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//mysql_query('UPDATE `items_users` SET `iznosMAX` = 5 WHERE `id` = "'.$iidtm.'" LIMIT 1');
			//
			$io .= '<i>Зелье Маны</i>, ';
			$this->addItem(1187,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			//mysql_query('UPDATE `items_users` SET `iznosMAX` = 5 WHERE `id` = "'.$iidtm.'" LIMIT 1');
			//
			$io .= '<i>Звездное Сияние</i>, ';
			$iidtm = $this->addItem(1463,$this->info['id'],'|nosale=1|sudba='.$this->info['login']);
			mysql_query('UPDATE `items_users` SET `iznosMAX` = 1 WHERE `id` = "'.$iidtm.'" LIMIT 1');
			//
		}else{
			$no_open_itm = true;		
			$this->error = 'Требуется 8 ключей с поля битвы.';
		}
		
	}
	unset($i5,$i3,$i4);
?>