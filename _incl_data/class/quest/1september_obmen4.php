<?
/*
	Сущность понимания Хаоса (Книга на +10 интуиция)
	Красный, голубой и желтый - Сущность понимания Хаоса
	//
	4745 - красный
	4746 - синий
	4747 - голубой
	4748 - желтый
	4749 - зеленый
	4750 - фиолетовый
	4751 - оранжевый
*/
$test = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = 4740 OR `item_id` = 4741 OR `item_id` = 4742 OR `item_id` = 4743 OR `item_id` = 4744) LIMIT 1'));

if(isset($test['id'])) {
	$txt .= '<br><b><font color=red>Вы уже получили одну из книг, нельзя получить еще одну в этом году...</b></font>';
}else{
	$pvr = array();
	$pvr['ch'] = 15; //шанс
	$pvr['tr'] = array( array(4745,1) , array(4747,1) , array(4748,1) );
	
	$pvr['i'] = 0;
	while( $pvr['i'] < count($pvr['tr']) ) {
		if( isset($pvr['tr'][$pvr['i']]) && $pvr['tr'][$pvr['i']] > 0 ) {
			$itm = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($pvr['tr'][$pvr['i']][0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT 1'));
			if( $itm[0] < $pvr['tr'][$pvr['i']][1] ) {
				$pvr['bad_itm']++;
			}
		}
		$pvr['i']++;
	}
	
	if( isset($pvr['bad_itm']) && $pvr['bad_itm'] > 0 ) {
		$txt .= '<br><b><font color=red>У вас нет подходящих предметов для обмена...</b></font>';
	}else{
		//
		$pvr['i'] = 0;
		while( $pvr['i'] < count($pvr['tr']) ) {
			if( isset($pvr['tr'][$pvr['i']]) && $pvr['tr'][$pvr['i']] > 0 ) {
				mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id` = "'.mysql_real_escape_string($pvr['tr'][$pvr['i']][0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.mysql_real_escape_string($pvr['tr'][$pvr['i']][1]));
			}
			$pvr['i']++;
		}
		//все ок
		if( rand(0,100) < $pvr['ch'] ) {
			$txt .= '<br><b><font color=red>Вы получили &quot;Синий Том Знаний&quot;</b></font>';
			$pvr['itm'] = $u->addItem(4743,$u->info['id']);
			//
			mysql_query('UPDATE `items_users` SET `gift` = "Гильдия Алхимиков" WHERE `id` = "'.mysql_real_escape_string($pvr['itm']).'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
			//
		}else{
			$txt .= '<br><b><font color=red>Неудачный эксперимент</b></font>';
		}
	}
	
	unset($pvr);
}
?>