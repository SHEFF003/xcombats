<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Прорыв
*/
$pvr = array();
//Действие при клике

$pvr['uid1'] = $u->info['id'];
$pvr['uid2'] = $u->info['enemy'];
$pvr['witm_data'] = array(  );
$pvr['vladenie']  = 1;
$pvr['bron'] = array( 0 , 0 );
$pvr['power'] = 1;

$pvr['yron'] = $btl->yronGetrazmen($pvr['uid1'],$pvr['uid2'],3,rand(1,5));

$pvr['hp'] = floor(1+rand($pvr['yron']['y'],$pvr['yron']['m_y'])/3);
$pvr['hpSee'] = '--';
$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']);
$pvr['hpAll'] = $btl->stats[$btl->uids[$u->info['enemy']]]['hpAll'];
	
//Используем проверку на урон приемов
$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $u->info['enemy'], 12, $pvr['hp'], -1, true );
	
$pvr['hpSee'] = '-'.$pvr['hp'];
$pvr['hpNow'] -= $pvr['hp'];
	
if( $pvr['hpNow'] > $pvr['hpAll'] ) {
	$pvr['hpNow'] = $pvr['hpAll'];
}elseif( $pvr['hpNow'] < 0 ) {
	$pvr['hpNow'] = 0;
}
	
$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] = $pvr['hpNow'];
	
mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
	
$prv['text'] = $btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);

$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
	'Прорыв',
	'{tm1} '.$prv['text'].' на {u2}. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
	($btl->hodID + 1)
);

//Отнимаем тактики
$this->mintr($pl);

unset($pvr);
?>