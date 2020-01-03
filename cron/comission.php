<?php
define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/__config.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__db_connect.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__user.php');

/*

	CRON Комиссионного магазина
	Действия:
	1.  Если предмет висит уже 2 и более недель, продавать предмет за 50% от его стоимости с учетом износа
		деньги отсылаются на почту.

*/

$time_last = 86400 * 14; //2 недели

$sp = mysql_query('SELECT * FROM `items_com` WHERE `delete` = 0 AND `time` < "'.(time()-$time_last).'"');
while( $pl = mysql_fetch_array($sp) ) {
	$user = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
	//
	$itm_user = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `id` = "'.$pl['item_id'].'" LIMIT 1'));
	$itm_main = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl['items_id'].'" LIMIT 1'));
	//
	$shpCena = $itm_user['1price'];
	$plmx = 0;
	if($itm_main['iznosMAXi']!=$itm_user['iznosMAX'] && $itm_user['iznosMAX']!=0){
		$plmx = $itm_main['iznosMAX'];
	}else{
		$plmx = $itm_main['iznosMAXi'];
	}
	if($itm_user['iznosNOW']>0){
		$prc1 = $itm_user['iznosNOW']/$plmx*100;
	}else{
		$prc1 = 0;
	}
	$shpCena = $u->shopSaleM( $shpCena , $itm_user );
	$shpCena = $shpCena/100*(100-$prc1);
	if( $itm_user['iznosMAXi'] < 999999999 ) {
		if($itm_user['iznosMAX']>0 && $itm_main['iznosMAXi']>0 && $itm_main['iznosMAXi']>$itm_user['iznosMAX']){
			$shpCena = $shpCena/100*($itm_user['iznosMAX']/$itm_main['iznosMAXi']*100);
		}
	}
	$shpCena = $shpCena/2 * $pl['group'];
	$shpCena = $u->round2($shpCena/100*(100-$shopProcent));
	if($shpCena<0){
		$shpCena = 0;
	}
	//
	$itm_user['1price'] = $shpCena;
	//
	if( $itm_user['1price'] < 0.01 ) {
		$itm_user['1price'] = 0.01;
	}
	//
	mysql_query('UPDATE `items_com` SET `delete` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	mysql_query('INSERT INTO `items_users` (`delete`,`item_id`,`1price`,`uid`,`lastUPD`) VALUES ("0","1220","'.$itm_user['1price'].'","-51'.$user['id'].'","'.time().'")');
	mysql_query('INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES (
		"'.$user['id'].'","0","'.time().'","'.$itm_user['1price'].'","Комиссионный магазин: Предмет &quot;'.$itm_main['name'].''.$grp.'&quot; (Износ: '.ceil($itm_user['iznosNOW']).'/'.ceil($itm_user['iznosMAX']).') был продан за <b>'.$itm_user['1price'].' кр</b>."
	)');
	//
	$grp = '';
	if( $pl['group'] > 1 ) {
		$grp = ' (x'.$pl['group'].')';
	}
	$u->send('','','','<font color=#009966 >Комиссионный магазин</font>',$user['login'],'Предмет &quot;'.$itm_main['name'].''.$grp.'&quot; (Износ: '.ceil($itm_user['iznosNOW']).'/'.ceil($itm_user['iznosMAX']).') был продан в государственный магазин за <b>'.$itm_user['1price'].' кр.</b>. Деньги доставлены к вам на почту.',time(),6,0,0,0,1,0);
}

?>