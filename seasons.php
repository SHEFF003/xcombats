<?php
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}
function var_info($vars, $d = false){
    echo "<pre style='border: 1px solid gray;border-radius: 5px;padding: 3px 6px;background: #cecece;color: black;font-family: Arial;font-size: 12px;'>\n";
    var_dump($vars);
    echo "</pre>\n";
    if ($d) exit();
}
define('IP',GetRealIp());

die();

include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__magic.php');
include('_incl_data/class/__user.php');
include('_incl_data/class/__filter_class.php');
include('_incl_data/class/__quest.php');
include('_incl_data/class/__seasons.php');

if(isset($_GET['upi'])) {
	$k = explode(',',$_GET['upi']);
	$i = 0;
	while( $i < count($k) ) {
		//
		$i1 = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($k[$i-1]).'" LIMIT 1'));
		$i2 = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($k[$i]).'" LIMIT 1'));
		$i3 = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($k[$i+1]).'" LIMIT 1'));
		if(isset($i2['id'])) {
			//Проводим работу
			if(isset($i3['id'])) {
				//Добавляем апгрейд i2 -> i3
				$test = mysql_fetch_array(mysql_query('SELECT * FROM `items_upgrade` WHERE `iid` = "'.$i2['id'].'" AND `iup` = "'.$i3['id'].'" LIMIT 1'));
				if(!isset($test['id'])) {
					mysql_query('INSERT INTO `items_upgrade` (`iid`,`iup`,`price1`,`price2`) VALUES (
						"'.$i2['id'].'","'.$i3['id'].'","0","'.($i3['price2']-$i2['price2']).'"
					)');
					echo '['.$i2['id'].']->['.$i3['id'].'] за '.($i3['price2']-$i2['price2']).'екр.<br>';
				}
			}
			if(isset($i1['id']) || !isset($i3['id'])) {
				//Удаляем i2 из магазина
				echo '[Удаляем '.$i2['id'].' с прилавка]<br>';
				mysql_query('UPDATE `items_shop` SET `kolvo` = 0 WHERE `item_id` = "'.$i2['id'].'"');
			}
		}
		//
		$i++;
	}
}else{
	die();
}

$tjs = '';

//if( $u->info['admin'] == 0 ) {
//	header('location: main.php');
//	die();
//}

if($u->info['bithday'] == '01.01.1800' && $u->info['inTurnirnew'] == 0) {
	header('location: main.php');
	die();
}

/*if( !eregi("combatz\.ru", $_SERVER['HTTP_REFERER']) ) { 
	//die('Перезайдите в игру, сессия закрыта.<br>last_page:%'.$_SERVER['HTTP_REFERER'].'');
}*/

if( $u->info['id'] == 1000001 ) {
	$u->info['admin'] = 0;
}

#--------для общаги, и позже для почты
if($u->info['online'] < time()-60)
{
	$filter->setOnline($u->info['online'],$u->info['id'],0);
	$u->onlineBonus();	
	mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");	
}elseif($u->info['timeMain'] < time()-60)
{
	mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");	
}

if(!isset($u->info['id']) || ($u->info['joinIP']==1 && $u->info['ip']!=$_SERVER['HTTP_X_REAL_IP']) || $u->info['banned']>0)
{
	die($c['exit']);
}

/* */
$rz = 1;
if( isset($_GET['rz']) ) {
	if( $_GET['rz'] == 2 ) {
		$rz = 2;	
	}
}
/* */
season::$date['Y'] = date('Y');
season::$date['m'] = date('m');
season::$date['d'] = date('d');
season::$yy = season::$yy[season::$date['m']];


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<link href="/sss_css.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top:0px; margin-top:7px; background-color:#E2E0E0;"><script type="text/javascript" src="js/jquery.js"></script>
<h3 style="font-size:25px;"><?=season::$yy[3]?> сезон, <?=season::$date['Y']?></h3>
<table width="912" border="0" align="center" cellpadding="0" cellspacing="0" class="sss_bg<?=season::$date['m']?>">
  <tr>
    <td style="min-height:450px;">
    <!-- -->
    <?
	if( $rz == 1 ) {
		//Сезонная линейка
		$html = '';
		$sp = mysql_query('SELECT * FROM `sss_m` WHERE `s` = "'.mysql_real_escape_string(season::$date['m']).'"');
		while( $pl = mysql_fetch_array($sp) ) {
			if( $pl['dd'] > season::$date['d'] ) {
				//Миссия еще закрыта
				$html .= '<div onClick="location.href=\'seasons.php?rz=2&mis='.$pl['id'].'\';"	 style="background-image:url(http://img.xcombats.com/ss_boss0.png);" class="sss_block_m">'.
				'<div class="sss_block_m_in2" align="center"><small>Дата открытия</small><br>'.$pl['dd'].'.'.season::$date['m'].'.'.season::$date['Y'].'</div>'.
				'</div>';
			}else{
				//Открытая миссия
				$html .= '<div onClick="location.href=\'seasons.php?rz=2&mis='.$pl['id'].'\';" style="background-image:url(http://img.xcombats.com/'.$pl['img'].');" class="sss_block_m">'.
				'<div class="sss_block_m_in">Приступить!</div>'.
				'</div>';
			}
		}
		if( $html == '' ) {
			$html = 'Нет миссий для текущего сезона';	
		}
		echo '<div align="center">' . $html . '</div>';
	}elseif( $rz == 2 ) {
		//Открытая миссия
		$html = '';
		season::data( $_GET['mis'] );
		if( !isset(season::$m['id']) ) {
			$html = 'Миссия для текущего сезона не найдена';	
		}else{
			if( season::$m['dd'] > season::$date['d'] ) {
				//Миссия еще закрыта
				$html .= '<div onClick="location.href=\'seasons.php?rz=2&mis='.season::$m['id'].'\';" style="background-image:url(http://img.xcombats.com/ss_boss0.png);" class="sss_block_m">'.
				'<div class="sss_block_m_in2" align="center"><small>Дата открытия</small><br>'.season::$m['dd'].'.'.season::$date['m'].'.'.season::$date['Y'].'</div>'.
				'</div>';
			}else{
				//Открытая миссия
				$html .= '<div onClick="location.href=\'seasons.php?rz=2&mis='.season::$m['id'].'\';" style="float:left;background-image:url(http://img.xcombats.com/'.season::$m['img'].');" class="sss_block_m">'.
				'<div class="sss_block_m_in3">&nbsp;<small>Выполнено заданий:<br>0 / ??</small></div>'.
				'</div>';
				//Задания в миссии
				$sp = mysql_query('SELECT * FROM `sss_q` WHERE `m` = "'.season::$m['id'].'"');
				$ends = array();
				$i = 0;
				$lstms = mysql_fetch_array(mysql_query('SELECT * FROM `sss_f` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
				while( $pl = mysql_fetch_array($sp) ) {
					
					$qe = 0;
					$tss = strtotime('01-'.season::$m['s'].'-'.season::$date['Y'].'');
					if(isset($lstms['id'])) {
						$tss = strtotime(''.date('d',$lstms['time']).'-'.date('m',$lstms['time']).'-'.date('Y',$lstms['time']).'');
					}
					//
					if( $pl['type'] == 1 ) {
						//Сбор ресурсов
						$c1 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `time_create` > "'.$tss.'" AND `uid` = "'.$u->info['id'].'" AND `delete` < 1001 AND `item_id` = "'.$pl['value'].'" LIMIT 1'));
						$qe = $c1[0];
					}elseif( $pl['type'] == 2 || $pl['type'] == 3 ) {
						$c1 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `actions` WHERE `time` > "'.$tss.'" AND `uid` = "'.$u->info['id'].'" AND `vars` LIKE "%win_bot_'.$pl['value'].'" LIMIT 1'));
						$qe = $c1[0];
					}
					//
					if( $qe > $pl['var'] ) {
						$qe = $pl['var'];
					}
					$f = 0;
					//
					$nogo = false;
					if( !isset($ends[$i-1]) && $i != 0 ) {
						$nogo = true;
					}
					//
					$plf = mysql_fetch_array(mysql_query('SELECT * FROM `sss_f` WHERE `uid` = "'.$u->info['id'].'" AND `m` = "'.$pl['id'].'" AND `y` = "'.season::$date['Y'].'" LIMIT 1'));
					if(isset($plf['id'])) {
						$f = 1;
					}
					//				
					$html .= '<div style="float:right;padding-top:25px;"';
					if( $nogo == true ) {
						$html .= ' class="graysc"';
					}
					$html .= '>';
					//
					$html .= '<div align="left" class="sss_mis_div">';
					$html .= '<img src="http://img.xcombats.com/ss_ico'.$pl['type'].'.png">';
					$html .= '<div style="float:right;width:530px;color:#efefef;" align="left">';
											
					$html .= '<div style="width:500px;margin-top:10px;height:50px;"><small>' . $pl['info'] . '</small></div>';
						
					if( $nogo == true ) {
						//Еще не готов выполнять
					}elseif( $f == 1 ) {
						//Выполнено
					}elseif( $nogo == false && $f == 0 && isset($_GET['endq']) && $_GET['endq'] == $pl['id'] && $qe == $pl['var'] ) {
						$f = 1;
						mysql_query('INSERT INTO `sss_f` (`m`,`y`,`uid`,`time`) VALUES (
							"'.$pl['id'].'",
							"'.season::$date['Y'].'",
							"'.$u->info['id'].'",
							"'.time().'"
						)');
					}else{
						$html .= '<div class="sss_line1"><div style="width:'.round($qe/$pl['var']*300).'px;" class="sss_line2"></div></div>';
						if( $qe == $pl['var'] ) {
							$html .= ' <button onclick="location.href=\'seasons.php?rz=2&mis='.round((int)$_GET['mis']).'&endq='.$pl['id'].'\';" class="sss_btn1">Завершить</button>';
						}
						$html .= ' &nbsp; '.$qe.' / '.$pl['var'].'';
					}
					
					if( $f == 1 ) {
						$ends[$i] = true;
						$html .= '<b style="color:#efefef"><img src="http://img.xcombats.com/i/ico/wins.gif"> Задание выполнено.</b>';
					}elseif( $nogo == true ) {
						$html .= '<i style="color:#ffa5a5"><img src="http://img.xcombats.com/i/ico/draw.gif"> Требует выполнение предыдущего задания.</i>';
					}
						
					$html .= '</div>';
					$html .= '</div>';
					//
					$html .= '</div>';
					$i++;
				}
			}
		}
		echo '<div align="center">' . $html . '</div>';
	}
	?>
    <!-- -->
    </td>
  </tr>
</table>
</body>
</html>
