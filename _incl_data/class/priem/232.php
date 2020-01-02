<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Выжить, сжирает все тактики, за сердца 0.5 ед. за остальное 1 ед. НР
*/
$pvr = array();
//Действие при клике

$pvr['hp'] = round( 1+$btl->users[$btl->uids[$u->info['id']]]['tactic1']+$btl->users[$btl->uids[$u->info['id']]]['tactic2']+$btl->users[$btl->uids[$u->info['id']]]['tactic3']+$btl->users[$btl->uids[$u->info['id']]]['tactic4']+$btl->users[$btl->uids[$u->info['id']]]['tactic5']+$btl->users[$btl->uids[$u->info['id']]]['tactic6']*0.5 );
if( $pvr['hp'] > 25 ) {
	$pvr['hp'] = 25;
}
$pvr['hp'] = round($btl->stats[$btl->uids[$u->info['id']]]['hpAll']/100*$pvr['hp']);
$pvr['hpSee'] = '--';
$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']);
$pvr['hpAll'] = $btl->stats[$btl->uids[$u->info['id']]]['hpAll'];
$pvr['hp'] = $btl->hphe( $u->info['id'] , $pvr['hp'] , true );
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
	
while($pvr['i'] <= 6) {
	$btl->users[$btl->uids[$u->info['id']]]['tactic'.$pvr['i']] = 0;
	$btl->stats[$btl->uids[$u->info['id']]]['tactic'.$pvr['i']] = 0;
	$u->info['tactic'.$pvr['i']] = 0;
	$u->stats['tactic'.$pvr['i']] = 0;
	$pvr['i']++;
}
	
mysql_query('UPDATE `stats` SET 
`last_hp` = "'.$btl->users[$btl->uids[$u->info['id']]]['last_hp'].'",
`hpNow` = "'.$u->info['hpNow'].'",
`tactic1` = "0",
`tactic2` = "0",
`tactic3` = "0",
`tactic4` = "0",
`tactic5` = "0",
`tactic6` = "0"
WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	
$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
	'Выжить',
	'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
	($btl->hodID)
);

//Отнимаем тактики
$this->mintr($pl);

unset($pvr);
?>