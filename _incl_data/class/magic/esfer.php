<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'esfer' ) {
	
	
	
	$pvr = array();
	
	//Действие при клике
	if( $u->stats['hpNow'] < 1 ) {
		$u->error = '<font color=red><b>Вы поглибли и не можете воспользоваться свитком...</b></font>';
	}elseif( isset($btl->info['id']) ) {
		
		if( $btl->info['noinc'] > 0 ) {
			$u->error = '<font color=red><b>Бой был изолирован ранее</b></font>';	
		}else{			
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
				'',
				'{tm1} {u1} изолировал бой от внешнего физического мира... ',
				($btl->hodID)
			);			
			$u->error = '<font color=red><b>Мерцающая сфера отделила всех вас от остального мира...  </b></font>';		
			mysql_query('UPDATE `battle` SET `noinc` = 1 WHERE `id` = '.$btl->info['id'].' LIMIT 1');
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		}
		
	}else{
		$u->error = '<font color=red><b>Свиток возможно использовать только в бою</b></font>';
	}
	
	//Отнимаем тактики
	//$this->mintr($pl);
	
	unset($pvr);
}
?>