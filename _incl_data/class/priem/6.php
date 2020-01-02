<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Воля к победе 5*(лвл) + 7 НР , эффект увеличен на 25% , если НР ниже 33%
	Игрок восстанавливает 2-5НР
*/
$pvr = array();
//Действие при клике

$pvr['hp'] = round( (5 * $btl->users[$btl->uids[$u->info['id']]]['level']) + 7 );
if( floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']) < round($btl->stats[$btl->uids[$u->info['id']]]['hpAll']*0.33) ) {
	$pvr['hp'] += round($pvr['hp']*0.25);
}
$pvr['hp'] = $btl->hphe( $u->info['id'] , $pvr['hp'] , true );
$pvr['hpSee'] = '--';
$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']);
$pvr['hpAll'] = $btl->stats[$btl->uids[$u->info['id']]]['hpAll'];
	
$pvr['hpTr'] = $pvr['hpAll'] - $pvr['hpNow'];
if( $pvr['hpTr'] > 0 ) {
	//Требуется хилл
	if( $pvr['hpTr'] < $pvr['hp'] ) {
		$pvr['hp'] = $pvr['hpTr'];
	}
	$pvr['hpSee'] = '+'.$pvr['hp'];
	$pvr['hpNow'] += $pvr['hp'];
}
	
if( $pvr['hpNow'] > $pvr['hpAll'] ) {
	$pvr['hpNow'] = $pvr['hpAll'];
}elseif( $pvr['hpNow'] < 0 ) {
	$pvr['hpNow'] = 0;
}

$btl->users[$btl->uids[$u->info['id']]]['last_hp'] = $pvr['hp'];
	
$u->info['hpNow'] = $pvr['hpNow'];
$u->stats['hpNow'] = $pvr['hpNow'];
$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = $pvr['hpNow'];
	
mysql_query('UPDATE `stats` SET `last_hp` = "'.$btl->users[$btl->uids[$u->info['id']]]['last_hp'].'",`hpNow` = "'.$u->info['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	
$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
	'Воля к победе',
	'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
	($btl->hodID)
);

//Отнимаем тактики
$this->mintr($pl);

unset($pvr);
?>