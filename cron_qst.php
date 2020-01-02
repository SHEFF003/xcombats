<?php

define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

$html = '';

$x = 0;
$y = 0;

$sp = mysql_query('SELECT `id`,`battle`,`room` FROM `users` WHERE `real` = 1');
while( $pl = mysql_fetch_array($sp) ) {
	//
	$bot_kill = array();
	//
	$qp = mysql_query('SELECT `vars` FROM `actions` WHERE `uid` = "'.$pl['id'].'" AND `vars` LIKE "%start_quest%" AND `vals` = "go"');
	while( $ql = mysql_fetch_array($qp) ) {
		$qst_id = str_replace('start_quest','',$ql['vars']);
		$qst = mysql_fetch_array(mysql_query('SELECT `act_date` FROM `quests` WHERE `id` = "'.$qst_id.'" LIMIT 1'));
		if(isset($qst['act_date'])) {
			$act = explode(':|:',$qst['act_date']);
			$i = 0;
			while( $i < count($act) ) {
				$act2 = explode(':=:',$act[$i]);
				$bots = explode(',',$act2[1]);						
				$j = 0;
				while( $j < count($bots) ) {
					$bot = explode('=',$bots[$j]);
					$bot_kill[$bot[0]] = true;
					$j++;
				}
				$i++;
			}					
		}
	}
	//
	$delete = '';
	$wp = mysql_query('SELECT `id`,`vars` FROM `actions` WHERE `uid` = "'.$pl['id'].'" AND ( `vars` LIKE "win_bot_%" OR `vars` LIKE "lose_bot_%" OR `vars` LIKE "nich_bot_%" )');
	while( $wl = mysql_fetch_array($wp) ) {
		$wlb = str_replace('win_bot_','',$wl['vars']);
		$wlb = str_replace('lose_bot_','',$wlb);
		$wlb = str_replace('nich_bot_','',$wlb);
		if( !isset($bot_kill[$wlb]) ) {
			$delete .= 'OR `id` = "'.$wl['id'].'" ';
			$x++;
		}else{
			//активный квест
			$y++;
		}
	}
	$delete = ltrim($delete,'OR ');
	if( $delete != '' ) {
		mysql_query('DELETE FROM `actions` WHERE ' . $delete);
	}
	//
	if( $pl['battle'] == 0 ) {
		$x2 = mysql_query('SELECT COUNT(`id`) FROM `actions` WHERE `uid` = "'.$pl['id'].'" AND (
			`vars` LIKE "%use_priem_%" OR
			`vars` LIKE "%animal_use%" OR
			( `vars` LIKE "%takeit_%" AND `room` != "'.$pl['room'].'" )
		) LIMIT 1');
		$x += $x2[0];
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$pl['id'].'" AND (
			`vars` LIKE "%use_priem_%" OR
			`vars` LIKE "%animal_use%" OR
			( `vars` LIKE "%takeit_%" AND `room` != "'.$pl['room'].'" )
		)');
	}
	//
}

$html .= 'Бесполезных записей: '.$x.'<br>';
$html .= 'Активных записей: '.$y.'';

echo $html;

?>