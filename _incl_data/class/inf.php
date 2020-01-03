<?php
include_once('_incl_data/__config.php');
$c['inf'] = true;
define('GAME',true);
include_once('_incl_data/class/__db_connect.php');

include_once('_incl_data/class/__user.php');

define('LOWERCASE',3);
define('UPPERCASE',1);

$uplogin = explode('&',$_SERVER['QUERY_STRING']);
$uplogin = $uplogin[0];
$uplogin = preg_replace('/%20/'," ",$uplogin);

if(!isset($_GET['id']))
{
	$_GET['id'] = 0;
}

if(!isset($_GET['login']))
{
	$_GET['login'] = NULL;
}

if(!isset($upLogin)){ $upLogin = ''; }

$utf8Login = '';
$utf8Login2 = '';

$utf8Login  = iconv("utf-8", "windows-1251",$uplogin);

$utf8Login2 = iconv("utf-8", "windows-1251",$_GET['login']);

if($uplogin == 'delete' || $utf8Login == 'delete' || $utf8Login2 == 'delete') {
	
}else{
	$inf = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id`=`st`.`id`) WHERE (`u`.`id`="'.mysql_real_escape_string($_GET['id']).'" OR `u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($uplogin).'" OR `u`.`id`="'.mysql_real_escape_string($uplogin).'" OR `u`.`login`="'.mysql_real_escape_string($utf8Login2).'" OR `u`.`login`="'.mysql_real_escape_string($utf8Login).'") LIMIT 1'));
	if($inf['login'] == 'delete') {
		unset($inf);
	}
}

if(!isset($inf['id']))
{
	unset($inf);
}else{
	if($inf['haos']>1)
	{
		//снимаем хаос
		if($inf['haos']<time())
		{
			$inf['align'] = 0;
			mysql_query('UPDATE `users` SET `align` = "0",`haos` = "0" WHERE `id` = "'.$inf['id'].'" LIMIT 1');
		}
	}
	if($u->info['admin']>0)
	{
		if(isset($_GET['wipe']) && $u->newAct($_GET['sd4'])==true)
		{
			$upd = mysql_query('UPDATE `stats` SET `wipe` = "1" WHERE `id` = "'.$inf['id'].'" LIMIT 1');
			if($upd)
			{
				$uer = 'Сброс характеристик прошел успешно<br>';
			}else{
				$uer = 'Ошибка сброса...<br>';
			}
		}
	}
	if(($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4) || $u->info['admin']>0)
	{
		if(isset($_GET['molchMax']) && $u->newAct($_GET['sd4'])==true)
		{
			$upd = mysql_query('UPDATE `users` SET `molch3` = "'.$inf['molch1'].'" WHERE `id` = "'.$inf['id'].'" LIMIT 1');
			if($upd)
			{
				$uer = 'Все прошло успешно...<br>';
			}else{
				$uer = 'Ошибка...<br>';
			}
		}
	}
}
	
if(!isset($inf['id']))
{
	die('<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="ru">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<TITLE>Произошла ошибка</TITLE></HEAD><BODY text="#FFFFFF"><p><font color=black>
	Произошла ошибка: <pre>Указанный персонаж не найден...</pre>
	<b><p><a href = "javascript:window.history.go(-1);">Назад</b></a>
	<HR>
	<p align="right">(c) <a href="index.html">'.$c['title'].'</a></p>
	'.$c['counters'].'
	</body></html>');
}

if($u->info['align'] > 1 && $u->info['align'] < 2) {
	
}elseif($u->info['align'] > 3 && $u->info['align'] < 4) {
	
}elseif($inf['redirect'] != '0' && $u->info['admin'] == 0 && $u->info['id'] != $inf['id']) {
	header('location: '.$inf['redirect']);
	die();
}


function zodiak($d,$m,$y)
{
$dr = $d;
switch($m)
{
 case '03':
 $zodiac_id = 12;
 if($dr > 20) $zodiac_id = 1;
 break;
 case '04':
 $zodiac_id = 1;
 if($dr > 19) $zodiac_id = 2;
 break;
 case '05':
 $zodiac_id = 2;
 if($dr > 20) $zodiac_id = 3;
 break;
 case '06':
 $zodiac_id = 3;
 if($dr > 21) $zodiac_id = 4;
 break;
 case '07':
 $zodiac_id = 4;
 if($dr > 22) $zodiac_id = 5;
 break;
 case '08':
 $zodiac_id = 5;
 if($dr > 22) $zodiac_id = 6;
 break;
 case '09':
 $zodiac_id = 6;
 if($dr > 22) $zodiac_id = 7;
 break;
 case '10':
 $zodiac_id = 7;
 if($dr > 22) $zodiac_id = 8;
 break;
 case '11':
 $zodiac_id = 8;
 if($dr > 21) $zodiac_id = 9;
 break;
 case '12':
 $zodiac_id = 9;
 if($dr > 21) $zodiac_id = 10;
 break;
 case '01':
 $zodiac_id = 10;
 if($dr > 19) $zodiac_id = 11;
 break;
 case '02':
 $zodiac_id = 11;
 if($dr > 18) $zodiac_id = 12;
 break;
 }
 return $zodiac_id; 
}
$id_zodiak = null;
$bday = explode('.',$inf['bithday']);
if(isset($bday[0],$bday[1],$bday[2]))
{
$id_zodiak = zodiak($bday[0],$bday[1],$bday[2]);
}

if($id_zodiak==null)
{
  $id_zodiak = 1;
}

$name_zodiak = array(1=>'Овен',2=>'Телец',3=>'Близнецы',4=>'Рак',5=>'Лев',6=>'Дева',7=>'Весы',8=>'Скорпион',9=>'Стрелец',10=>'Козерог',11=>'Водолей',12=>'Рыбы');
$name_zodiak = $name_zodiak[$id_zodiak];

function statInfo($s)
{
	global $st,$st2;
	$st[$s]  = 0+$st[$s];
	$st2[$s] = 0+$st2[$s];
	if($st[$s]!=$st2[$s])
	{
		$s1 = '+';
		if($st2[$s]>$st[$s])
		{
			$s1 = '-';
		}
		
$cl = array(
0=>"#000000",
33=>"#004000",
34=>"#006000",
35=>"#006100",
36=>"#006200",
37=>"#006300",
38=>"#006400",
39=>"#006500",
40=>"#006600",
41=>"#006700",
42=>"#006800",
43=>"#006900",
44=>"#006A00",
45=>"#006B00",
46=>"#006C00",
47=>"#006D00",
48=>"#006E00",
49=>"#006F00",
50=>"#007000",
51=>"#007100",
52=>"#007100",
53=>"#007200",
54=>"#007300",
55=>"#007400",
56=>"#007500",
57=>"#007600",
58=>"#007700",
59=>"#007800",
60=>"#007900",
61=>"#007A00",
62=>"#007B00",
63=>"#007C00",
64=>"#007D00",
65=>"#007E00",
66=>"#007F00",
67=>"#008000",
68=>"#008100",
69=>"#008200",
70=>"#008300",
71=>"#008400",
72=>"#008500",
73=>"#008600",
74=>"#008700",
75=>"#008700",
76=>"#008800",
77=>"#008900",
78=>"#008A00",
79=>"#008B00",
80=>"#008C00",
81=>"#008D00",
82=>"#008E00",
83=>"#008F00",
84=>"#009000",
85=>"#009100",
86=>"#009200",
87=>"#009300",
88=>"#009400",
89=>"#009500",
90=>"#009600",
91=>"#009700",
92=>"#009800",
93=>"#009900",
94=>"#009A00",
95=>"#009B00",
96=>"#009C00",
97=>"#009D00",
98=>"#009E00",
99=>"#009F00",
100=>"#00A000"
);
		
		
		//$cl = array(0=>'#003C00',1=>'green',2=>'#0DAC0D',3=>'#752415',4=>'');
		$si = 4;
		if($s1=='-')
		{
			$si = 0;
		}
		$t = $st[$s];
		$j = $st[$s]-$st2[$s];
		$t = $t-$j;
		if($j>0)
		{
			if($t==0)
			{
				$t = 1;
			}
			if($t==0)
			{
				$t = 1;
			}
			$d = $j*100/$t;
			if($d<0 && $t+$j>=0)
			{
				$d = 100;
			}
			if($d < 33)
			{
				$si = 0;
			}elseif($d > 100)
			{
				$si = 100;
			}
		}elseif($j<0)
		{
			$si = 3;
		}
		echo '<b style="color:'.$cl[$si].'">'.$st[$s].'</b> <small>('.$st2[$s].' '.$s1.' '.abs($st[$s]-$st2[$s]).')</small>';
	}else{
		echo '<b>'.$st[$s].'</b>';
	}
}

$room = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `id`="'.$inf['room'].'" LIMIT 1'));

if($inf['clan']>0)
{
	$pc = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id`="'.$inf['clan'].'" LIMIT 1'));
}

if(isset($_GET['short']))
{
	$n = '
';
	$o = 0;
	if($inf['online']>time()-520)
	{
		$o = 1;
	}
	$sh = '';
	$sh .= 'id='.$inf['id'].$n;
	$sh .= 'login='.$inf['login'].$n;
	$sh .= 'level='.$inf['level'].$n;
	$sh .= 'align='.$inf['align'].$n;
	$sh .= 'clan='.$pc['name_mini'].$n;
	$sh .= 'city='.$inf['city'].$n;
	$sh .= 'city_reg='.$inf['cityreg'].$n;
	$sh .= 'room_name='.$room['name'].$n;
	$sh .= 'online='.$o.$n;
	die($sh);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Информация о <? echo $inf['login']; ?></title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.zclip.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/title.js"></script>
<script type="text/javascript" src="js/hpregen.js"></script>
<link href="http://<? echo $c['img']; ?>/css/main.css" rel="stylesheet" type="text/css">
<style>
body { background-color:#dedede; margin:5px;  }
hr { height:1px; }
img {border:0px;}
button	{ border: solid 1pt #B0B0B0; font-family: MS Sans Serif; font-size: 11px; color: #191970; padding:2px 7px 2px 7px;}
button:active { padding:3px 6px 1px 8px; }
.ttl_css
{
	position:absolute;
	padding-left:3px;
	padding-right:3px;
	padding-top:2px;
	padding-bottom:2px;
	background-color: #ffffcc;
	border: 1px solid #333333;
}
.findlg {
	filter: alpha(opacity=37);
    opacity:0.37;
    -moz-opacity:0.37;
    -khtml-opacity:0.37;
	margin-bottom:10px;
}
.findlg:hover {
	filter: alpha(opacity=100);
    opacity:1.00;
    -moz-opacity:1.00;
    -khtml-opacity:1.00;
}
.gifin {
	position:absolute;
	left: 112px;
	top: 428px;
	padding:5px;
	background-color:#FCFFD2;
	border:1px solid #686868;
	font-size:12px;
	max-width:300px;
}
</style>
<script>
var lafstReg = {};
function lookGift(e,id,nm,img,txt,from)
{
	if(!e){ e = window.event; }
	var body2 = document.body;
	mX = e.x;
 	mY = e.y+(body2 && body2.scrollTop || 0); 	

	var gf = document.getElementById('gi');
	if(gf!=undefined)
	{
		gf.style.top = mY+'px';
		gf.innerHTML = '<b><span style="float:left;">'+nm+'</span> <span style="float:right;">&nbsp; <a href="javascript:void(0);" onClick="closeGift();">X</a></span></b><br><div align="center" style="padding:5px;background-color:#f5f6ec;"><img src="http://<? echo $c['img']; ?>/i/items/'+img+'"></div>'+txt+'<div>Подарок от <a target="_blank" href="info/login='+from+'">'+from+'</a></div>';
		gf.innerHTML = '<small>'+gf.innerHTML+'</small>';
		gf.style.display = '';
	}
}

function closeGift()
{
	var gf = document.getElementById('gi');
	if(gf!=undefined)
	{
		gf.innerHTML = '';
		gf.style.display = 'none';
	}
}
</script>
</head>
<body>
<div id="ttl" class="ttl_css" style="display:none;z-index:1111;" /></div>
<div id="gi" class="gifin" style="display:none;"></div>
<? if(isset($uer)){ echo '<div align="left"><font color=\'red\'>'.$uer.'</font></div><br>'; } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="255" valign="top"><div align="left"><? $st = array(); $st2 = array(); $st = $u->getStats($inf['id'],1); $st2 = $st[1]; $st = $st[0]; $rgd = $u->regen($inf['id'],$st,1); $us = $u->getInfoPers($inf['id'],1,$st); if($us!=false){ echo $us[0]; }else{ echo 'information is lost.'; } ?></div>
        <div align="left"></div><div align="left"></div><script>lafstReg[<? echo $inf['id']; ?>] = 1;startHpRegen(<? echo '"top",'.$inf['id'].','.(0+$st['hpNow']).','.(0+$st['hpAll']).','.(0+$st['mpNow']).','.(0+$st['mpAll']).','.(time()-$inf['regHP']).','.(time()-$inf['regMP']).','.(0+$rgd[0]).','.(0+$rgd[1]).''; ?>,1);</script>
        <?
		//Персонаж онлайн
		echo '<center style="padding-top:3px;"><b>'.$u->city_name[$inf['city']].'</b><br><small>';
		if($inf['online']>time()-520 && $inf['banned']==0)
		{
			echo 'Персонаж сейчас находится в клубе.<br><b>"'.$room['name'].'"</b>';
		}else{
			if($inf['admin']==0 || $inf['admin']==2)
			{
				if($inf['online']==0)
				{
					$inf['online'] = $inf['timeREG'];
				}
				echo 'Персонаж не в клубе, но был тут:<br>'.date('d.m.Y H:i',$inf['online']).'<img title="Время сервера" src="http://'.$c['img'].'/i/clok3_2.png">';
				$out = '';
				$time_still = time()-$inf['online'];
				$tmp = floor($time_still/2592000);
				$id=0;
				if ($tmp > 0) { 
					$id++;
					if ($id<3) {$out .= $tmp." мес. ";}
					$time_still = $time_still-$tmp*2592000;
				}
				$tmp = floor($time_still/604800);
				if ($tmp > 0) { 
				$id++;
				if ($id<3) {$out .= $tmp." нед. ";}
				$time_still = $time_still-$tmp*604800;
				}
				$tmp = floor($time_still/86400);
				if ($tmp > 0) { 
					$id++;
					if ($id<3) {$out .= $tmp." дн. ";}
					$time_still = $time_still-$tmp*86400;
				}
				$tmp = floor($time_still/3600);
				if ($tmp > 0) { 
					$id++;
					if ($id<3) {$out .= $tmp." ч. ";}
					$time_still = $time_still-$tmp*3600;
				}
				$tmp = floor($time_still/60);
				if ($tmp > 0) { 
					$id++;
					if ($id<3) {$out .= $tmp." мин. ";}
				}
				if($out=='')
				{
					$out = $time_still.' сек.';
				}
				echo '<br>('.$out.' назад)';
			}elseif($inf['admin']>0)
			{
				echo 'Персонаж не в клубе.';
			}
		}
		if($inf['inUser']>0)
		{
			echo '<br>Персонаж вселился в <a target="_blank" href="info/'.$inf['inUser'].'">бота</a>';
		}
		if($inf['battle']>0)
		{
			$btl3 = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = '.$inf['battle'].''));  
			if(isset($btl3['id']) && $btl3['time_over']==0)
			{
				echo '<br>Персонаж сейчас в <a target="_blank" href="logs.php?log='.$btl3['id'].'">поединке</a>';
			}
		}
		echo '</small></center>';
		?>
        </td>
        <td valign="top"><table style="margin-top:18px;" cellspacing="0" cellpadding="0" width="100%">
          <TD valign=top>Сила: <? echo statInfo('s1'); ?><BR>
                      <SPAN title=''>Ловкость: <? statInfo('s2'); ?></SPAN><BR>
                      <SPAN title=''>Интуиция: <? statInfo('s3'); ?></SPAN><BR>
                      <SPAN title=''>Выносливость: <? statInfo('s4'); ?></SPAN><BR>
                      <? if($inf['level']>3 || $st['s5']!=0){ ?><SPAN title=''>Интеллект: <? statInfo('s5'); ?></SPAN><BR><? } ?>
                      <? if($inf['level']>6 || $st['s6']!=0){ ?><SPAN title=''>Мудрость: <? statInfo('s6'); ?></SPAN><BR><? } ?>
                      <? if($inf['level']>9 || $st['s7']!=0){ ?><SPAN title=''>Духовность: <? statInfo('s7'); ?></SPAN><BR><? } ?>
                      <? if($inf['level']>11 || $st['s8']!=0){ ?><SPAN title=''>Воля: <? statInfo('s8'); ?></SPAN><BR><? } ?>
                      <? if($inf['level']>14 || $st['s9']!=0){ ?><SPAN title=''>Свобода духа: <? statInfo('s9'); ?></SPAN><BR><? } ?>
                      <? if($inf['level']>19 || $st['s10']!=0){ ?><SPAN title=''>Божественность: <? statInfo('s10'); ?></SPAN><BR><? } ?>
                      <div align="left" style="height:1px; width:300px; background-color:#999999; margin:3px;"></div>
                      <small> Уровень: <? echo $inf['level']; ?><BR>
            Побед: <? if($inf['level']<4){ echo number_format($inf['win'], 0, ",", " "); }else{ echo '<a title="Персонаж учавствует в рейтинге" href="http://top.xcombats.com/?user='.$inf['id'].'&rnd='.$code.'" target="_blank">'.number_format($inf['win'], 0, ",", " ").'</a>'; } ?><BR>
            Поражений: <? echo number_format($inf['lose'], 0, ",", " "); ?><BR>
            Ничьих: <? echo number_format($inf['nich'], 0, ",", " "); ?><BR>
            <? if($inf['align']==50){ echo '<b>Орден Алхимиков</b>'; if($inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; } echo '<br>'; } ?>
			<? if($inf['align']>1 && $inf['align']<2){ echo '<b>Орден Паладинов</b> - '.$u->mod_nm[1][$inf['align']]; if($inf['align']!='1.99' && $inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; } echo '<br>';  } ?>
			<? if($inf['align']>3 && $inf['align']<4){ echo '<b>Армада</b> - '.$u->mod_nm[3][$inf['align']]; if($inf['align']!='3.99' && $inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; } echo '<br>';  } ?>
            <? if($inf['clan']>0)
			{
				$pc = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id`="'.$inf['clan'].'" LIMIT 1'));
				if(isset($pc['id']))
				{
					$pc['img'] = $pc['name_mini'].'.gif';
					if($inf['clan_prava']=='glava')
					{
						$inf['mod_zvanie'] = '- <font color="#008080"><b>глава клана</b></font>';
					}elseif($inf['mod_zvanie']!='')
					{
						$inf['mod_zvanie'] = '- '.htmlspecialchars($inf['mod_zvanie'],NULL,'cp1251');	
					}
					echo 'Клан: <a href="#">'.$pc['name'].'</a> '.$inf['mod_zvanie'].'<br>';
				}
			} ?>
            Место рождения: <b><? if($inf['cityreg2']==''){ echo $u->city_name[$inf['cityreg']]; }else{ echo $inf['cityreg2']; } ?></b><br />
 			<? if($inf['city2']!='') { echo 'Второе гражданство: <b>'.$u->city_name[$inf['city2']].'</b><br />'; } ?>
            День рождения персонажа: <? if($inf['timereg']==0){ echo 'До начала времен...'; }else{ echo date('d.m.Y H:i',$inf['timereg']); } ?> <br>
            <?
			//История имен
			$names = '';
			$sp = mysql_query('SELECT * FROM `lastNames` WHERE `uid` = "'.$inf['id'].'" ORDER BY `time` DESC');
			$i = 0;
			while($pl = mysql_fetch_array($sp))
			{
				if($i>0)
				{
					$names .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
				}
				$names .= '\''.$pl['login'].'\' до '.date('d.m.Y H:i',$pl['time']).'<br>';
				$i++;
			}
			if($names!='')
			{
				echo 'История имен: '.$names.'';
			}
			?>
            <div align="left" style="height:1px; width:300px; background-color:#999999; margin:3px;"></div>
            <!-- значки --></td>
          </tr>
        </table>
              <?
			     if($inf['align']==50)
				 {
			  ?>
              <a href="#"><img src="http://<? echo $c['img']; ?>/alhim1.gif" onMouseOver="top.hi(this,'Регистрированный алхимик.<Br>Имеет право продавать услуги «<? echo $c['title3']; ?>»',event,0,0,1,0,'');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>
              <?
				 }
				
				 if($inf['marry']!=0)
				 {
				 	$marry = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$inf['marry'].'" LIMIT 1'));
					if(isset($marry['id']))
					{
						$mrtxt = '';
						if($inf['sex']==0)
						{
							$mrtxt = 'Женат на';
						}else{
							$mrtxt = 'Замужем за';
						}
						echo '<a href="info/'.$marry['id'].'"><img src="http://'.$c['img'].'/i/i_marry.gif" onMouseOver="top.hi(this,\''.$mrtxt.' <b>'.$marry['login'].'</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					}
				 } 
				 
				 
				 //значок регистратора
				 /*$uref = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `host_reg` = "'.$inf['id'].'" AND `level` > 0 LIMIT 1000'));			 
				 $uref = $uref[0];
				 if($uref>9)
				 {
					 $rico = 0;
					 if($uref>=30){ $rico = 19; 
					 }elseif($uref>=20){ $rico = 20; 
					 }elseif($uref>=10){ $rico = 21; }
					 
					 if($rico>0)
					 {
						$stp = array(21=>'XXI степень<br><small>новичок</small>',
									 20=>'XX степень<br><small>новичок</small>',
									 19=>'IXX степень<br><small>новичок</small>');
						echo '<a href="#'.$uref.'"><img src="http://'.$c['img'].'/reg_ico_'.$rico.'.png" onMouseOver="top.hi(this,\'Орден Регатов, '.$stp[$rico].'\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
				 }
				 */
				 				 
				 $sp = mysql_query('SELECT * FROM `users_ico` WHERE `uid` = "'.$inf['id'].'" AND (`endTime` = 0 OR `endTime` > '.time().') LIMIT 50');
				 $ico = '';
				 //сильвер
				 if($st['silver']>0) {
					 echo '<img src="http://'.$c['img'].'/silver_a1.gif" onMouseOver="top.hi(this,\'<b>Premium Account</b><br>Серебряный\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"> ';
				 }
				 
				 //«»
				 while($pl = mysql_fetch_array($sp))
				 {
					 $ico .= '<a target="_blank" href="'.$pl['href'].'"><img src="http://'.$c['img'].'/'.$pl['img'].'" onMouseOver="top.hi(this,\''.$pl['text'].'<br><span style=float:right >'.date('<b>F</b>, Y',$pl['time']).'</span>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
				 }
				 echo $ico;
				 unset($ico);
				 
				 $irep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$inf['id'].'" LIMIT 1'));
				 if(isset($irep['id']))
				 {					 
					 //capitalcity
					 if($irep['repcapitalcity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn1_2.gif" onMouseOver="top.hi(this,\'<b>Capital city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repcapitalcity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn1_1.gif" onMouseOver="top.hi(this,\'<b>Capital city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //angelscity
					 if($irep['repangelscity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn2_2.gif" onMouseOver="top.hi(this,\'<b>Angels city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repangelscity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn2_1.gif" onMouseOver="top.hi(this,\'<b>Angels city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //demonscity
					 if($irep['repdemonscity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn3_2.gif" onMouseOver="top.hi(this,\'<b>Demons city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repdemonscity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn3_1.gif" onMouseOver="top.hi(this,\'<b>Demons city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //devilscity
					 if($irep['repdevilscity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn4_2.gif" onMouseOver="top.hi(this,\'<b>Devils city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repdevilscity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn4_1.gif" onMouseOver="top.hi(this,\'<b>Devils city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //suncity
					 if($irep['repsuncity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn5_2.gif" onMouseOver="top.hi(this,\'<b>Suncity</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repsuncity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn5_1.gif" onMouseOver="top.hi(this,\'<b>Suncity</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //emeraldscity
					 if($irep['repemeraldscity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn6_2.gif" onMouseOver="top.hi(this,\'<b>Emeralds city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repemeraldscity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn6_1.gif" onMouseOver="top.hi(this,\'<b>Emeralds city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //sandcity
					 if($irep['repsandcity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn7_2.gif" onMouseOver="top.hi(this,\'<b>Sand city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repsandcity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn7_1.gif" onMouseOver="top.hi(this,\'<b>Sand city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //mooncity
					 if($irep['repmooncity']>24999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn9_2.gif" onMouseOver="top.hi(this,\'<b>Moon city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repmooncity']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/zn9_1.gif" onMouseOver="top.hi(this,\'<b>Moon city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //Алтарь крови
					 if($irep['rep2']>99)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/znbl_1.gif" onMouseOver="top.hi(this,\'<b>Алтарь Крови</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //Храм знаний
					 if($irep['rep1']>9999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/znrune_3.gif" onMouseOver="top.hi(this,\'<b>Храм Знаний</b><br>Посвященный третьего круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['rep1']>999)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/znrune_2.gif" onMouseOver="top.hi(this,\'<b>Храм Знаний</b><br>Посвященный второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['rep1']>99)
					 {
						 echo '<a href="#"><img src="http://'.$c['img'].'/znrune_1.gif" onMouseOver="top.hi(this,\'<b>Храм Знаний</b><br>Посвященный первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
				 }
			  			  
				function timeOut($ttm,$travm=false)
				{
				   if($travm==false){
				    $out = '';
					$time_still = $ttm;
					$tmp = floor($time_still/2592000);
					$id=0;
					if ($tmp > 0) 
					{ 
						$id++;
						if ($id<3) {$out .= $tmp." мес. ";}
						$time_still = $time_still-$tmp*2592000;
					}
					$tmp = floor($time_still/604800);
					if ($tmp > 0) 
					{ 
						$id++;
						if ($id<3) {$out .= $tmp." нед. ";}
						$time_still = $time_still-$tmp*604800;
					}
					$tmp = floor($time_still/86400);
					if ($tmp > 0) 
					{ 
						$id++;
						if ($id<3) {$out .= $tmp." дн. ";}
						$time_still = $time_still-$tmp*86400;
					}
					$tmp = floor($time_still/3600);
					if ($tmp > 0) 
					{ 
						$id++;
						if ($id<3) {$out .= $tmp." ч. ";}
						$time_still = $time_still-$tmp*3600;
					}
					$tmp = floor($time_still/60);
					if ($tmp > 0) 
					{ 
						$id++;
						if ($id<3) {$out .= $tmp." мин. ";}
					}
					if($out=='')
					{
						if($time_still<0)
						{
							$time_still = 0;
						}
						$out = $time_still.' сек.';
					}
					}else{
					}
					return $out;
				}
			  
				echo '<small>';
				if($inf['jail']>time())
				{
					echo '<br><img src="http://'.$c['img'].'/i/jail.gif"> Персонаж находится в заточении еще '.timeOut($inf['jail']-time()).' ';
				}
				if($inf['molch1']>time())
				{
					echo '<br><img src="http://'.$c['img'].'/i/sleeps'.$inf['sex'].'.gif"> На персонажа наложено заклятие молчания. Будет молчать еще '.timeOut($inf['molch1']-time()).' ';
				}
				if($inf['molch2']>time())
				{
					echo '<br><img src="http://'.$c['img'].'/i/fsleeps'.$inf['sex'].'.gif"> На персонажа наложено заклятие молчания на форуме. Будет молчать еще '.timeOut($inf['molch2']-time()).' ';
				}
				//Если у персонажа есть травмы, физ. и маг. травмы
				$sp = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$inf['id'].'" AND (`id_eff` = "4" OR `id_eff` = "6") AND `delete` = "0" ORDER BY `id_eff` ASC LIMIT 6');
				while($pl = mysql_fetch_array($sp))
				{
					//$pln = array();
					//$pln = array(0=>$pln[0],1=>$pln[1]);
					echo '<br><img src="http://'.$c['img'].'/i/travma2.gif"> У персонажа - &quot;<b>'.$pl['name'].'</b>&quot; еще '.$u->timeOut($pl['timeUse']-time()+$pl['timeAce']);
				}
				
				//Персонаж ослаблен из-за смерти в бою, еще 4 мин. 24 сек. 
				if($inf['level']>=4)
				{
					$nn = 0;
					while($nn<count($st['effects']))
					{
						if($st['effects'][$nn]['id_eff']==5)
						{							
							
							$osl = mysql_fetch_array(mysql_query('SELECT `id2`,`actionTime` FROM `eff_main` WHERE `id2` = "5" LIMIT 1'));
							echo '<br><img src="http://'.$c['img'].'/i/travma2.gif"> Персонаж ослаблен из-за смерти в бою, еще '.timeOut($st['effects'][$nn]['timeUse']+$st['effects'][$nn]['timeAce']+$osl['actionTime']-time()).' ';
							$nn = count($st['effects'])+1;
						}
						$nn++;
					}
				}
				
				echo '</small>';
			  
			  	if(($inf['align']>=2 && $inf['align'] < 3 && ($inf['haos']>time() || $inf['haos']==1)) || $inf['banned']>0)
				{
					$to = '';
					if($inf['align']>=2 && $inf['align'] < 3 && ($inf['haos']>time() || $inf['haos']==1))
					{
						$to = 'хаос';
					}
					if($inf['banned']>0)
					{
						if($to='')
						{
							$to = 'блок';
						}else{
							$to = $to.'/блок';
						}
					}
					$fm = mysql_fetch_array(mysql_query('SELECT * FROM `users_delo` WHERE `uid` = "'.$inf['id'].'" AND `hb`!=0 ORDER BY `id` DESC LIMIT 1'));
					echo '<br><br>';
					if(isset($fm['id']))
					{
						$from = 'паладинов';
						if($fm['hd']==2)
						{
							$from = 'Ангелов';
						}elseif($fm['hd']==3)
						{
							$from = 'тарманов';
						}
						echo 'Сообщение от '.$from.' о причине отправки в '.$to.':<br>';
						//$fm['text'] = ltrim($fm['text'],"Ангел \&quot\;".$fm['login']."\&quot\; \<b\>сообщает\<\/b\>\:");
						echo '<font color="red" style="background-color:#fae0e0;"><b>'.$fm['text'].'</b></font><br>';
					}
					if($inf['align']>=2 && $inf['align'] < 3 && ($inf['haos']>time() || $inf['haos']==1))
					{
						if($inf['haos']==1)
						{
							echo 'Хаос <i>бессрочно</i>.';
						}else{
							echo 'Хаос еще <i>'.timeOut($inf['haos']-time()).'</i>';
						}
					}
				}
			  				
				//подарки
				if(($inf['info_delete']<time() && $inf['info_delete']!=1) || ($u->info['align']>1 && $u->info['align']<2 || $u->info['align']>3 && $u->info['align']<4 || $u->info['admin']>0)){
				$gs = array('','',''); $glim = 10; $i = 0;
				$_GET['maxgift']=1;
				if(isset($_GET['maxgift']))
				{
					$glim = 1000;
				}
				$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "63" OR `im`.`type` = "64") AND `iu`.`uid` = "'.$inf['id'].'" AND `iu`.`gift` != "" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" ORDER BY `iu`.`id` DESC LIMIT '.$glim);
				while($pl = mysql_fetch_array($sp))
				{
					if($pl['type'] == 28) {
						//Букеты
						$gs[2] .= '<img src="http://'.$c['img'].'/i/items/'.$pl['img'].'" style="margin:1px 1px 0 0;display:block;float:left;cursor:pointer;" onClick="lookGift(event,0,\''.$pl['name'].'\',\''.$pl['img'].'\',\''.$pl['gtxt1'].'\',\''.$pl['gift'].'\');" title="'.$pl['gtxt1'].'
Подарок от '.$pl['gift'].'" />';
					}elseif($pl['type'] == 63) {
						//открытки
						$gs[1] .= '<img src="http://'.$c['img'].'/i/items/'.$pl['img'].'" style="margin:1px 1px 0 0;display:block;float:left;cursor:pointer;" onClick="lookGift(event,0,\''.$pl['name'].'\',\''.$pl['img'].'\',\''.$pl['gtxt1'].'\',\''.$pl['gift'].'\');" title="'.$pl['gtxt1'].'
Подарок от '.$pl['gift'].'" />';
					}else{
						//подарки
						$gs[0] .= '<img src="http://'.$c['img'].'/i/items/'.$pl['img'].'" style="margin:1px 1px 0 0;display:block;float:left;cursor:pointer;" onClick="lookGift(event,0,\''.$pl['name'].'\',\''.$pl['img'].'\',\''.$pl['gtxt1'].'\',\''.$pl['gift'].'\');" title="'.$pl['gtxt1'].'
Подарок от '.$pl['gift'].'" />';
					}
				}
				
				if($gs[0]!='' || $gs[1]!='' || $gs[2]!=''){
					if($gs[2] != '') {
						$gs[2] = '<tr><td style="padding-bottom:17px;">Букеты:<br>'.$gs[2].'</td></tr>';
					}
					echo '<br><br><table>'.$gs[2].'<tr><td>Подарки:<br>'.$gs[0].'</td></tr><tr><td style="padding-top:7px;">'.$gs[1].'</td></tr></table>';
					if(!isset($_GET['maxgift'])){
						//echo '<small><a href="info/'.$inf['id'].'&maxgift=1">Нажмите сюда, чтобы увидеть все подарки...</a></small>';
					}else{
						//echo '<small><a href="info/'.$inf['id'].'">Нажмите сюда, чтобы скрыть подарки</a></small>';
					}
				}
				
				}
				//темные делишки :D
				if(($u->info['align']>=1.1 && $u->info['align']<=1.99 && $inf['admin']<1) || ($u->info['align']>=3.05 && $u->info['align']<=3.99 && $inf['admin']<1) || $u->info['admin']>0) 
				{
					$mults = '';
					$bIP = array();
					if($inf['id']!=42526 && $inf['id']!=1254){
					$spl = mysql_query('SELECT * FROM `mults` WHERE (`uid` = "'.$inf['id'].'" OR `uid2` = "'.$inf['id'].'") AND `uid`!="0" AND `uid2`!="0"');
					}
					while($pls = mysql_fetch_array($spl))
					{
						$usr = $pls['uid'];
						if($usr==$inf['id'])
						{
							$usr = $pls['uid2'];
						}
						if(!isset($bIP[$usr]) && $usr!=$inf['id'])
						{
							$si = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.mysql_real_escape_string($usr).'" LIMIT 1'));
							if(isset($si['id']))
							{
								if($si['admin']==0 || $u->info['admin']>0)
								{
									$mults .= $u->microLogin($si['id'],1).', ';
								}
							}
						}
					}
					
					if($inf['city']!=$u->info['city'] && $u->info['align']>=1.1 && $u->info['align']<1.9) 
					{
						echo '<h3>Персонаж в другом городе...</h3>';
					} elseif ($inf['city']!=$u->info['city'] && $u->info['align']>=3.01 && $u->info['align']<3.09) 
					{
						echo '<h3>Персонаж в другом городе...</h3>';
					} elseif ($u->info['admin']==0 && (($u->info['admin']==0 && (floor($u->info['align'])==1 && $inf['align']>=3.01 && $inf['align']<=3.99) || (floor($u->info['align'])==3 && $inf['align']>=1.1 && $inf['align']<=1.99)) || ($u->info['admin']==0 && $inf['admin']>0)))  
					{
						echo '<h3>Персонаж носит вражескую склонность...</h3>';
					}else{
						echo '<br /><br /><div style="color:#828282;">За игроком замечены следующие темные делишки:<br /><small><span class=dsc>';
						if(!isset($_GET['mod_inf'])) {
							echo '<a href="info/'.$inf['id'].'&mod_inf">Показать личноое дело</a>';
						}else{
							//Личное дело персонажа
							$log = mysql_query('SELECT * FROM `users_delo` WHERE `uid`="'.$inf['id'].'" AND `type`="0" ORDER by `id` DESC LIMIT 21');
							$i = 0;
							while ($log_w = mysql_fetch_array($log))
							{
								echo ''.date("d.m.Y H:i:s",$log_w['time']).'&nbsp;'.$log_w['text'].' <br />';
								$i++;
							}
							echo '<a href="info/'.$inf['id'].'">Скрыть личноое дело</a>';
						}
						echo '</small><br>';
						//Информация для паладинов\тарманов\ангелов
						if(($u->info['align']>=1.4 && $u->info['align']<=1.99 && $u->info['align']!=1.6 && $u->info['align']!=1.75 && $inf['admin']<1) || ($u->info['align']>=3.05 && $u->info['align']<=3.99 && $u->info['align']!=3.06 && $inf['admin']<1) || $u->info['admin']>0) 
						{
							if ((int)$u->info['align']==1) 
							{ 
								$rang = 'Паладинов'; 
							} elseif ((int)$u->info['align']==3) 
							{
								$rang = 'Тарманов'; 
							} else 
							{ 
								$rang = 'Ангелов'; 
							}					
							
							/*
							$pr1 = mysql_fetch_array(mysql_query('SELECT * FROM `register_code` WHERE `reg_id` = "'.$inf['id'].'" LIMIT 1'));
							$pr = array('login'=>'');
							if(isset($pr1['id']))
							{
								$pr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level` FROM `users` WHERE `id` = "'.$pr1['uid'].'" LIMIT 1'));
								if(isset($pr['id']))
								{
									$pr['login'] = 'Персонажа пригласили: <b>'.$pr['login'].'</b> ['.$pr['level'].'] <a href="info/'.$pr['id'].'" target="_blank"><img src="http://'.$c['img'].'/i/inf_capitalcity.gif" title="Инф. о '.$pr['login'].'"></a><br>';
								}else{
									$pr['login'] = 'Персонажа пригласили: #<i>'.$pr1['uid'].'</i><br>';
								}
							}
							*/
							
							if((int)$inf['host_reg'] >= 1){
								$inf['ref'] = $u->microLogin((int)$inf['host_reg'],1);
							}else{
								$inf['ref'] = '--';
							}
							if(!isset($inf['ipReg'])){ $inf['ipReg'] = '--'; }	
							echo '
							<br />
							<b style="color:red"><u>Только для '.$rang.'</u></b><br />
							<i>День рождения: '.$inf['bithday'].'<br />
							E-mail: '.$inf['mail'].'<br />
							Персонажа пригласили: '.$inf['ref'].'<br />
							Последний раз заходил в клуб: '.date('d.m.Y H:i',$inf['online']).'<br />
							'.$pr['login'].'IP при регистрации: '.$inf['ipReg'].'<br />';
							if($inf['no_ip'] == '' || $u->info['admin']>0) {
								echo 'IP последние: <b>'.$inf['ip'].'</b>';
								$auth = mysql_query('SELECT * FROM `logs_auth` WHERE `uid`="'.$inf['id'].'" AND `type`="1" ORDER by `id` DESC LIMIT 10');
								$country = '';
								while ($auth_w = mysql_fetch_array($auth)) {
									echo '<br />'.$auth_w['ip'].' <small><b>('.date('d.m.Y H:i',$auth_w['time']).')</b></small>';
								}
							}else{
								echo 'IP последние: <b>'.$inf['no_ip'].'</b>';
							}
							echo'
							<br />
							Браузер: '.$inf['dateEnter'].'<br />
							';
														
							if($inf['no_ip'] == '' || $u->info['admin']>0) {
								if($mults!='') 
								{
									$mults = trim($mults,', ');
									echo 'Другие ники этого бойца:  '.$mults.'<br />';
								}
							}
							
							if($u->info['admin']>0)
							{
								echo 'Доп. возможности: <small><a href="info/'.$inf['id'].'&wipe&sd4='.$u->info['nextAct'].'">сбросить характеристики</a></small><br>';
								$on1 = mysql_fetch_array(mysql_query('SELECT `time_all`,`time_today` FROM `online` WHERE `uid` = "'.$inf['id'].'" LIMIT 1'));
								echo 'Время в онлайне (всего): '.timeOut($on1['time_all']).'<br>Время в онлайне (сегодня): '.timeOut($on1['time_today']).'<br>';
							}	
							if($inf['molch3']<time())
							{
								echo '<small><a href="info/'.$inf['id'].'&molchMax&sd4='.$u->info['nextAct'].'">Запретить персонажу отправлять сообщения с молчанкой</a></small><br>';
							}
							echo'	
							Опыт: '.$inf['exp'].' <br />
							Число неиспользованных UP-ов: '.$inf['ability'].' <br />
							Кредитов: '.$inf['money'].'';
							if($u->info['admin']>0 && $inf['admin']>0)
							{
								echo '<br><small>admin: '.$inf['admin'].'</small>';
							}
							if($inf['active']!=''){
							echo '<br><font color=red>Внимание!Если персонаж не получает письма с активацией отправте ему письмо вручную.</red>';
							echo '<br><input type=text value="'.$inf['mail'].'">';
							echo "<br><textarea cols=60 rows=5>Здравствуйте! Мы очень рады новому персонажу в нашем Мире! \r\n Ваш персонаж: ".$inf['login']."  \r\n Ссылка для активации: http://capitalcity.xcombats.com/bk?active=".$inf['active'].".\r\n\r\nС уважением, Администрация xcombats.com!</textarea><br>";
							}
							echo '</div>';
						}
					}
				}
			  ?>
        <td align=right valign=top >
        <? /*
        <div style="float:right">
        <div class="findlg">
        <form action="inf.php" method="get">
          <table style="border:1px solid #AFAFAF" width="200" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td valign="middle" bgcolor="#D4D4D4" style="color: #333333">&nbsp;Поиск  по нику:</td>
              <td align="center" valign="middle" bgcolor="#D4D4D4">&nbsp;</td>
            </tr>
            <tr>
              <td width="150" align="center" valign="middle" bgcolor="#D4D4D4"><input style="width:145px" type="text" name="login" value=""></td>
              <td align="center" valign="middle" bgcolor="#D4D4D4"><button type="submit">Искать</button></td>
            </tr>
          </table>
          </form>
        </div>
		*/ ?>
        <table cellspacing=0 cellpadding=0>
          <tr>
            <td style='text-align: center; padding-bottom: 18; '><!-- Зодиак -->
                  <img title="<? //echo $name_zodiak; ?>" style="margin-bottom: 25; padding:5px;" src='http://<? echo $c['img']; ?>/i/zodiac/<? echo $id_zodiak; ?>.gif' border=0><BR>
                    <? if($inf['align']>1 && $inf['align']<2) { ?>
                  <A href='http://paladins.<? echo $c['host']; ?>/' target='_blank'><img style="padding:5px;" src='http://<? echo $c['img']; ?>/i/flag_light.gif' border=0></A><BR>
                  <A href='http://paladins.<? echo $c['host']; ?>/' target='_blank'><small>paladins.<? echo $c['host']; ?></small></A>
                    <? }elseif($inf['align']>=3.01 && $inf['align']<=3.99) { ?>
                  <A target='_blank' href='http://tarmans.<? echo $c['host']; ?>/'><img style="padding:5px;" src='http://<? echo $c['img']; ?>/i/flag_dark.gif' border=0></A><BR>
                  <A href='http://tarmans.<? echo $c['host']; ?>/' target='_blank'><small>tarmans.<? echo $c['host']; ?></small></A>
                    <? }elseif($inf['align']>=2 && $inf['align']<3) { ?>
                  <A target='_blank' href='http://<? echo $c['host']; ?>/'><img style="padding:5px;" src='http://<? echo $c['img']; ?>/i/flag_haos.gif' border=0></A><BR>
                  <A href='http://<? echo $c['host']; ?>.com/' target='_blank'><small><? echo $c['host']; ?></small></A>
                    <? }else{ ?>
                  <A target='_blank' href='http://events.<? echo $c['host']; ?>/'> <img style="padding:5px;" src='http://<? echo $c['img']; ?>/i/flag_gray.gif'></a><BR>
                  <A href='http://events.<? echo $c['host']; ?>/' target='_blank'><small>events.<? echo $c['host']; ?></small></A><br>
                    <? } ?>
            </td>
          </tr>
        </table></div></td>
      </table>
      </td>
  </tr>
</table>
<div align="left" style="height:1px; width:100%; background-color:#999999; margin-top:10px; margin-bottom:10px;"></div>
<?
if($inf['info_delete']!=0)
{
?>
<H3 align="center" style="color:#8f0000">Персонаж обезличен <? if($inf['info_delete']>1){ echo 'до '.date('d.m.Y H:i',$inf['info_delete']).'.'; }else{ echo '.'; } ?></H3>
<?
	if($u->info['align']>1 && $u->info['align']<2 || $u->info['align']>3 && $u->info['align']<4 || $u->info['admin']>0)
	{
		echo '<br><small style="color:grey;">';
	}
}
if($inf['info_delete']==0 || (($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4) || $u->info['admin']>0))
{
?> 
<div style="line-height:11pt;">   
<? if($inf['info_delete']==0){ ?><H3 align="center" style="color:#8f0000">Анкетные данные</H3><? } ?>
Имя: <? echo $inf['name']; ?><BR>
Пол: <? $sex[0] = 'Мужской'; $sex[1] = 'Женский'; echo $sex[$inf['sex']]; ?><BR>
<? if($inf['city_real']!=''){ ?>Город: <? echo $inf['city_real']; ?><BR><? } ?>
<? if($inf['icq']>0 && $inf['icq_hide']==0){ echo 'ICQ: '.$inf['icq'].' <img height="14" title="ICQ Status bar" src="http://web.icq.com/whitepages/online?img=9&icq='.$inf['icq'].'"><br />'; } ?>
<? if(isset($inf['homepage']) && $inf['homepage']!='' && $inf['level']>4) { 
$url = ((substr($inf['homepage'],0,4)=='http'?"":"http://").$inf['homepage']);
?>
 Домашняя страница: <A HREF="<? echo $url; ?>" target="_blank"><? echo $url; ?></A><BR> <? } ?>
<? if($inf['deviz']!=''){ ?>Девиз: <code><? echo $inf['deviz']; ?></code><BR>  <? } ?>
<? if($inf['hobby']!=''){ ?>  
Увлечения / хобби:<BR>
<? 
		echo str_replace("\n",'<br>',$inf['hobby']); 
	}
	if($inf['info_delete']!=0)
	{
		echo '</small>';
	}
}
echo '<br><br><div align="right">'.$c['counters_noFrm'].'</div>';
?>
</div>
<br /><br />
</body>
</html>
