<?php
if(isset($_GET['md5'])) {
	die(md5($_GET['md5']));	
}
header('Content-Type: text/html; charset=cp1251');
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

function utf8_win($s) {
	$s=str_replace("%D0%B0","а",$s); $s=str_replace("%D0%90","А",$s);
	$s=str_replace("%D0%B1","б",$s); $s=str_replace("%D0%91","Б",$s);
	$s=str_replace("%D0%B2","в",$s); $s=str_replace("%D0%92","В",$s);
	$s=str_replace("%D0%B3","г",$s); $s=str_replace("%D0%93","Г",$s);
	$s=str_replace("%D0%B4","д",$s); $s=str_replace("%D0%94","Д",$s);
	$s=str_replace("%D0%B5","е",$s); $s=str_replace("%D0%95","Е",$s);
	$s=str_replace("%D1%91","ё",$s); $s=str_replace("%D0%81","Ё",$s);
	$s=str_replace("%D0%B6","ж",$s); $s=str_replace("%D0%96","Ж",$s);
	$s=str_replace("%D0%B7","з",$s); $s=str_replace("%D0%97","З",$s);
	$s=str_replace("%D0%B8","и",$s); $s=str_replace("%D0%98","И",$s);
	$s=str_replace("%D0%B9","й",$s); $s=str_replace("%D0%99","Й",$s);
	$s=str_replace("%D0%BA","к",$s); $s=str_replace("%D0%9A","К",$s);
	$s=str_replace("%D0%BB","л",$s); $s=str_replace("%D0%9B","Л",$s);
	$s=str_replace("%D0%BC","м",$s); $s=str_replace("%D0%9C","М",$s);
	$s=str_replace("%D0%BD","н",$s); $s=str_replace("%D0%9D","Н",$s);
	$s=str_replace("%D0%BE","о",$s); $s=str_replace("%D0%9E","О",$s);
	$s=str_replace("%D0%BF","п",$s); $s=str_replace("%D0%9F","П",$s);
	$s=str_replace("%D1%80","р",$s); $s=str_replace("%D0%A0","Р",$s);
	$s=str_replace("%D1%81","с",$s); $s=str_replace("%D0%A1","С",$s);
	$s=str_replace("%D1%82","т",$s); $s=str_replace("%D0%A2","Т",$s);
	$s=str_replace("%D1%83","у",$s); $s=str_replace("%D0%A3","У",$s);
	$s=str_replace("%D1%84","ф",$s); $s=str_replace("%D0%A4","Ф",$s);
	$s=str_replace("%D1%85","х",$s); $s=str_replace("%D0%A5","Х",$s);
	$s=str_replace("%D1%86","ц",$s); $s=str_replace("%D0%A6","Ц",$s);
	$s=str_replace("%D1%87","ч",$s); $s=str_replace("%D0%A7","Ч",$s);
	$s=str_replace("%D1%88","ш",$s); $s=str_replace("%D0%A8","Ш",$s);
	$s=str_replace("%D1%89","щ",$s); $s=str_replace("%D0%A9","Щ",$s);
	$s=str_replace("%D1%8A","ъ",$s); $s=str_replace("%D0%AA","Ъ",$s);
	$s=str_replace("%D1%8B","ы",$s); $s=str_replace("%D0%AB","Ы",$s);
	$s=str_replace("%D1%8C","ь",$s); $s=str_replace("%D0%AC","Ь",$s);
	$s=str_replace("%D1%8D","э",$s); $s=str_replace("%D0%AD","Э",$s);
	$s=str_replace("%D1%8E","ю",$s); $s=str_replace("%D0%AE","Ю",$s);
	$s=str_replace("%D1%8F","я",$s); $s=str_replace("%D0%AF","Я",$s);
	return $s;
}

$uplogin = utf8_win($uplogin);

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


$utf8Login  = iconv("UTF-8", "windows-1251",$uplogin);

$utf8Login2 = iconv("UTF-8", "windows-1251",$_GET['login']);

if($uplogin == 'delete' || $utf8Login == 'delete' || $utf8Login2 == 'delete') {
	
}else{
	$inf = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id`=`st`.`id`) WHERE ((`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($utf8Login2).'" OR `u`.`login`="'.mysql_real_escape_string($utf8Login).'" OR `u`.`login`="'.mysql_real_escape_string($uplogin).'") ) LIMIT 1'));
	if(!isset($inf['id'])) {
		$inf = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id`=`st`.`id`) WHERE (`u`.`id`="'.mysql_real_escape_string($_GET['id']).'" OR `u`.`id`="'.mysql_real_escape_string($uplogin).'") LIMIT 1'));
	}
	if($inf['login'] == 'delete' || $inf['login'] == 'DELETE') {
		unset($inf);
	}else{
		if( $inf['info_delete'] > 1 && $inf['info_delete'] < time() ) {
			$inf['info_delete'] = 0;
			mysql_query('UPDATE `users` SET `info_delete` = 0 WHERE `id` = "'.$inf['id'].'" LIMIT 1');
		}
	}
}

if(!isset($inf['id']))
{
	unset($inf);
}else{
	if($inf['inTurnir'] > 0) {
		//$inf['online'] = time();
	}
	if(isset($_GET['restartmonster']) && $u->info['admin'] > 0) {
		mysql_query('UPDATE `stats` SET `res_x` = 0 WHERE `id` = "'.mysql_real_escape_string($inf['id']).'" LIMIT 1');
		$inf['res_x'] = 0;
	}
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

if( $inf['room'] == 303 && $u->info['admin'] == 0 ) {
	unset($inf);
}
	
if(!isset($inf['id']))
{
	die('<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="ru">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<TITLE>Произошла ошибка</TITLE></HEAD><BODY text="#FFFFFF" style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede; background-image:url(/i/bgmain.jpg);"><p><font color=black>
	Произошла ошибка: <pre>Указанный персонаж не найден...</pre>
	<b><p><a href = "javascript:window.history.go(-1);">Назад</b></a>
	<HR>
	<p align="right">(c) <a href="http://xcombats.com/">'.$c['title'].'</a></p>
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
-2=>"#550000",
-1=>"#990000",
0 =>"#000000",
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
		
		if($st[$s] <- 0) {
			$si = -1;
		}elseif($st[$s] <= round($st2[$s])) {
			$si = -2;
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
	$sh .= 'sex='.$inf['sex'].$n;
	$sh .= 'city='.$inf['city'].$n;
	$sh .= 'city_reg='.$inf['cityreg'].$n;
	$sh .= 'room_name='.$room['name'].$n;
	$sh .= 'online='.$o.$n;
	die($sh);
}

$nopal = false;

if( $u->info['align'] < $inf['align'] && $inf['align'] > 1 && $inf['align'] < 2 && $u->info['admin'] == 0 ) {
	$nopal = true;
}elseif( $u->info['admin'] == 0 && $inf['admin'] > 0 ) {
	$nopal = true;
}

if( $inf['inTurnir'] > 0 && ($u->info['inTurnir'] == $inf['inTurnir'] || $u->info['admin'] > 0) ) {
	$bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `id` = "'.$inf['inTurnir'].'" LIMIT 1'));
	if( isset($bs['id']) && ( $bs['users'] <= 2 || $bs['type_btl'] == 1 ) ) {
		$bs_rm = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$bs['type_map'].'" AND `x` = "'.$inf['x'].'" AND `y` = "'.$inf['y'].'" LIMIT 1'));
		if( isset($bs_rm['id']) ) {
			$room['name'] .= ' - '.$bs_rm['name'];
		}
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><? echo $inf['login']; ?>&mdash; Старый Бойцовский Клуб</title>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.zclip.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/js/title.js"></script>
<script type="text/javascript" src="/js/hpregen.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.scroll2.js"></script>

<link rel="stylesheet" href="/css/skin.css"  type="text/css"/>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<style>
body { background-color:#dedede; margin:5px;  }
hr { height:1px; }
img {border:0px;}
button	{ border: solid 1pt #B0B0B0; font-family: MS Sans Serif; font-size: 11px; color: #191970; padding:2px 7px 2px 7px;}
button:active { padding:3px 6px 1px 8px; }
.ttl_css
{
	position: absolute;
	padding-left: 3px;
	padding-right: 3px;
	padding-top: 2px;
	padding-bottom: 2px;
	background-color: #ffffcc;
	border: 1px solid #6F6B5E;	
	border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;	
	box-shadow: 0 0 1px #6F6B5E;
	-webkit-box-shadow: 0 0 1px #6F6B5E;
	-moz-box-shadow: 0 0 1px #6F6B5E;
	
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
	background-color:#fcfef3;
	border:1px solid #6e6960;
	font-size:12px;
	max-width:300px;
	min-height:100px;
	min-width:150px;
}
.pg_btn1 {
	background-image:url(http://img.xcombats.com/pg_btn_left.png);
	width:27px;
	height:78px;
	cursor:pointer;
	display:block;
}
.pg_btn1:hover {
	background-position:-27px;
	cursor:pointer;
}
.pg_btn1:active {
	background-position:-55px;
	cursor:pointer;
}
.pg_btn2 {
	background-image:url(http://img.xcombats.com/pg_btn_right.png);
	background-position:-54px;
	width:27px;
	height:78px;
	cursor:pointer;
	display:block;
}
.pg_btn2:hover {
	background-position:-27px;
	cursor:pointer;
}
.pg_btn2:active {
	background-position:1px;
	cursor:pointer;
}
</style>
<script type="text/javascript" language="javascript">
var lafstReg = {};
function lookGift(e,id,nm,img,txt,from) {
  if(from == 1) {
	 from = '<i>Невидимка</i>'; 
  }
  if(!e) { e = window.event; }
  var body2 = document.body;
  mX = e.x;
  mY = e.y+(body2 && body2.scrollTop || 0); 	
  var gf = document.getElementById('gi');
  if(gf != undefined) {
		gf.style.top = mY+'px';
		gf.style.left = mX+'px';
		if( from == '<i>Анонима</i>' ) {
			from = '</a><i>Анонима</i><a>';
		}
		gf.innerHTML = '<b><span style="float:left;">'+nm+'</span> <span style="float:right;">&nbsp; <a href="javascript:void(0);" onClick="closeGift();">X</a></span></b><br><div align="center" style="padding:5px;background-color:#dcdedc;"><img src="http://img.xcombats.com/i/items/'+img+'"></div>'+txt+'<div>Подарок от <a target="_blank" href="/info/login='+from+'">'+from+'</a></div>';
		gf.innerHTML = '<small>'+gf.innerHTML+'</small>';
		gf.style.display = '';
	}
}

function closeGift() {
  var gf = document.getElementById('gi');
  if(gf!=undefined) {
	gf.innerHTML = '';
	gf.style.display = 'none';
  }
}
function tstlgnthm() {
	if( window.opener && ( window.opener.textmsg != undefined || window.opener.parent.textmsg != undefined ) ) {
		/*
		<img onclick="window.opener.chat.toUser(\'' + data.login + '\',\'private\');" title="Приват" width="20" height="15" class="cp" src="http://' + cfg.img + '/images/lock3.gif">
		*/
		if( window.opener.textmsg != undefined ) {
			$('#lgnthm').html( '<img onclick="window.opener.chat.addto(\'<?=$inf['login']?>\',\'private\');" style="cursor:pointer" title="Написать персонажу" src="http://img.xcombats.com/i/lock3.gif" width="20" height="15">' + $('#lgnthm').html() );
		}else{
			$('#lgnthm').html( '<img onclick="window.opener.parent.chat.addto(\'<?=$inf['login']?>\',\'private\');" style="cursor:pointer" title="Написать персонажу" src="http://img.xcombats.com/i/lock3.gif" width="20" height="15">' + $('#lgnthm').html() );
		}
	}
}
</script>
</head>
<body style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede;">
<div id="ttl" class="ttl_css" style="display:none;z-index:1111;" /></div>
<div id="gi" class="gifin" style="display:none;z-index:1110;"></div>
<?
if(isset($uer)){ echo '<div align="left"><font color=\'red\'>'.$uer.'</font></div><br>'; } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="255" valign="top">
        <div align="center"><?
		$st = array(); $st2 = array(); $st = $u->getStats($inf['id'],1,0,true); $st2 = $st[1]; $st = $st[0]; 
		
        //if( $u->info['admin'] > 0 ) {
		//	echo '<div>Мощность персонажа: '.$st['reting'].'</div>';
		//}
		
		$rgd = $u->regen($inf['id'],$st,1); $us = $u->getInfoPers($inf['id'],1,$st); if( isset($bs['id']) && $bs['type_btl'] == 2 && ($u->info['x'] != $inf['x'] || $u->info['y'] != $inf['y'])) { $us[0] = '<div align="center" style="width:255px;height300px;"><br><br><br><br><br><br><br><br>Информация скрыта,<br>Вы в разных комнатах.<br><br><br><br><br><br><br></div>'; } if($us!=false){ echo $us[0]; }else{ echo 'information is lost.'; } ?></div>
        <!-- pos2 -->
        <? /*if( $u->info['admin'] > 0 ) { ?>
        <center>
        <table width="238" height="78" background="http://img.xcombats.com/pd_bg.png" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="27"><a onselectstart="return false" onclick="$('#mycarousel').jcarousel('prev');" class="cp pg_btn1">&nbsp;</a></td>
            <td align="center" valign="middle">
            <div id="pd_box">
              
              <div id="mycarousel" class="jcarousel-skin-bk">
                <ul>
                  
                  <?
				  //<li><img width="60" height="60" src="http://img.xcombats.com/honor_death_victory_1.gif" /></li>
				  $sp = mysql_query('SELECT * FROM `users_ico` WHERE `uid` = "'.$inf['id'].'" AND (`endTime` = 0 OR `endTime` > '.time().') AND `type` = 2 AND `see` = 1');
				  $i = 0;
				  while($pl = mysql_fetch_array($sp))
				  {
					 $stlico = '';
					 
					 if( $pl['type'] == 2 ) {
						 $stlico .= 'width:60px;height:60xp;';
					 }
					 
					 if($stlico != '') {
						$stlico = 'style="'.$stlico.'"';
					 }
					 $icon = ' <img '.$stlico.' src="http://img.xcombats.com/'.$pl['img'].'" onMouseOver="top.hi(this,\''.$pl['text'].'\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">';
					 
					 if( $pl['href'] != '#' && $pl['href'] != '' ) {
					 	$htmlr .= '<li><a target="_blank" href="'.$pl['href'].'">'.$icon.'</a></li>';
					 }else{
						$htmlr .= '<li>'.$icon.'</li>';
					 }
					 $i++;
				  }
				  //
				  if( $i < 3 ) {
						while( $i < 3 ) {
							$htmlr .= '<li><img width="60" height="60" src="http://img.xcombats.com/honor_none.gif" /></li>';
							$i++;
						}
				  }
				  //
				  echo $htmlr;
				  //
				  unset($htmlr);
				  ?>
                  
                </ul>
              </div>              
            </div>
            <script>
			$('#mycarousel').jcarousel({
				   scroll:1, //листать по 1 элементу
				   wrap:"circular", //после последнего показывать первый слайд
				   auto:0, //время, автоматической смены слайдов
			});
			</script>
            </td>
            <td width="27"><a onselectstart="return false" onclick="$('#mycarousel').jcarousel('next');" class="cp pg_btn2">&nbsp;</a></td>
          </tr>
        </table>
        </center>
        <? }*/ ?>
        <div align="left"></div><div align="left"></div><script>tstlgnthm();lafstReg[<? echo $inf['id']; ?>] = 1;
		<?
		if ($inf['align']==9){
			$st['hpNow'] = $st['hpNow']/($st['hpAll']/100); 
			$st['hpAll'] = '100%';
			$rgd[0] = '0.28';
		}
		?>
		startHpRegen(<? echo '"top",'.$inf['id'].','.(0+$st['hpNow']).','.(0+$st['hpAll']).','.(0+$st['mpNow']).','.(0+$st['mpAll']).','.(time()-$inf['regHP']).','.(time()-$inf['regMP']).','.(0+$rgd[0]).','.(0+$rgd[1]).''; ?>,1);</script>
<?
$kp = array(
	0 => 1,
	1 => 1,
	2 => 3,
	3 => 3,
	4 => 3,
	5 => 7,
	6 => 7,
	7 => 7,
	8 => 14,
	9 => 14,
	10 => 30,
	11 => 30,
	12 => 30,
	13 => 30,
	14 => 30,
	15 => 30,
	16 => 60,
	17 => 60,
	18 => 60,
	19 => 60,
	20 => 60,
	21 => 60
);


/*$onj = floor((time()-$inf['online'])/60/60/24);
//if( $kp[$inf['level']]/2 < $onj && $inf['admin'] == 0 ) {
if( $onj > 6 && $inf['admin'] == 0 ) {
	echo '<small><div style="margin-left:6px;width:236px;padding:5px;" align="center" class="private">';
	if( ( (3*2) - $onj) < 1 ) {
		echo '<b>Персонаж будет стерт с базы<br>сегодня в полночь</b>';
	}else{
		echo '<b>Персонаж будет стерт с базы<br>через '.( ($kp[$inf['level']]*2) - $onj).' дн.</b>';
	}
	echo '</div></small>';
}*/

?>
<!-- pos1 -->
<?
		//Персонаж онлайн
		if( $inf['room'] != 303 ) {
			echo '<center style="padding-top:3px;"><b>'.$u->city_name[$inf['city']].'</b><br><small>';
			 
			
			if($inf['online']>time()-520 && $inf['banned']==0 && $inf['invis']!=1 && $inf['invis'] < time())
			{
				echo 'Персонаж сейчас находится в клубе.<br><b>"'.$room['name'].'"</b>';
			}else{
				if($inf['admin']==0 || $inf['admin']==2)
				{
					if($inf['online']==0)
					{
						$inf['online'] = $inf['timeREG'];
					}
					echo 'Персонаж не в клубе';
					if(date('Y',$inf['online']) == date('Y')) {
						echo ', но был тут:<br>'.date('d.m.Y H:i',$inf['online']).'<img title="Время сервера" src="http://img.xcombats.com/i/clok3_2.png">';
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
					}
				}elseif($inf['admin']>0)
				{
					echo 'Персонаж не в клубе.';
				}
			}
			if($inf['inUser']>0 AND $inf['id']!=12059 )
			{
				echo '<br>Персонаж вселился в <a target="_blank" href="/info/'.$inf['inUser'].'">бота</a>';
			}
			if($inf['battle']>0)
			{
				$btl3 = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = '.$inf['battle'].''));  
				if(isset($btl3['id']) && $btl3['time_over']==0)
				{
					echo '<br>Персонаж сейчас в <a target="_blank" href="/logs.php?log='.$btl3['id'].'">поединке</a>';
				}
			}
			echo '</small></center>';
		}else{
			if( $inf['res_x']-time() > 0 ) {
				echo '<center style="padding-top:3px;">Появится через <b>'.($u->timeOut(($inf['res_x']-time()))).'</b><br><small></center>';
			}else{
				echo '<center style="padding-top:3px;">Появится в ближайшее время...<br><small></center>';
			}
		}
		//Эффект админ
		if($u->info['admin'] > 0) {
			
			if(isset($_GET['cancel_eff'])) {
				mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string((int)$_GET['cancel_eff']).'" AND `uid` = "'.$inf['id'].'" LIMIT 1');
				die('<script>top.location.href="/info/'.$inf['id'].'"</script>');
			}
			
			function eff_adm($pl) {
				global $u;
				$r = '';
				if($pl['v1'] != 'priem') {
					$eff = mysql_fetch_array(mysql_query('SELECT `id2`,`img`,`actionTime` FROM `eff_main` WHERE `id2` = "'.$pl['id_eff'].'" LIMIT 1'));
					$pl['img2'] = $eff['img'];
					$pl['name'] .= "\r".'Действует еще '.$u->timeOut($pl['timeUse']-time()+$eff['actionTime']).'';
				}
				$r .= '<img onDblClick="location.href=\'/info/'.$pl['uid'].'&cancel_eff='.$pl['id'].'\'" style="padding:1px;" title="'.$pl['name'].'" src="http://img.xcombats.com/i/eff/'.$pl['img2'].'" width="40" height="25">';
				return $r;
			}
			
			$eff_adm = '';
			$sp = mysql_query('SELECT `id_eff`,`uid`,`id`,`name`,`img2`,`v1`,`v2`,`timeUse` FROM `eff_users` WHERE `uid` = "'.$inf['id'].'" AND `delete` = "0"');
			while($pl = mysql_fetch_array($sp)) {
				$eff_adm .= eff_adm($pl);
			}
			if($eff_adm != '') {
				echo '<br><small style="display:block;text-align:center;">Эффекты на персонаже:<br>'.$eff_adm.'</small>';
			}
		}
		echo '<br><center><form method="GET" action="http://xcombats.com/inf.php"><input name="login" type="text" autocomplete="off" placeholder="Поиск персонажа" /> <input type="submit" value="Поиск" /></form></center>';
		if($u->info['admin'] > 0) {
		?>
        <br>
        <script>
		function mf_admin_statsfx() {
			if($('#mf_admin_stats').css('display') == 'none') {
				$('#mf_admin_stats').css('display','');
				$('#mf_admin_statstxt').html('Скрыть');
			}else{
				$('#mf_admin_stats').css('display','none');
				$('#mf_admin_statstxt').html('Показать');
			}
		}
		</script>
<div id="mf_admin_stats" style="display:none;">
	<div style="height:1px; width:240px; background-color:#999999; margin:3px;" align="center"></div>
	<div style="padding:5px;">
    <small>
    	<?
		$pr = $u->items['add']; 
		$i = 0;
		$apbr = array(
			'Защита от урона'=>1,
			'Сила'=>1,
			'Мастерство владения оружием'=>1,
			'Мастерство владения магией огня'=>1,
			'Уровень жизни (HP)'=>1,
			'Мф. критического удара (%)'=>1,
			'Мф. мощности магии огня'=>1,
			'Регенерация здоровья (%)'=>1,
			'Мф. мощности урона'=>1
		);
		while($i < count($pr)) {
			if($st[$pr[$i]] != 0 && $u->is[$pr[$i]] != '') {
				$vls = $st[$pr[$i]];
				if($vls > 0) {
					$vls = '+'.$vls;
				}
				if($apbr[$u->is[$pr[$i]]] == 1) {
					echo '<div style="height:1px; width:230px; background-color:#999999; margin:3px;" align="center"></div>';
				}
				echo '&bull; '.$u->is[$pr[$i]].': '.$vls.' <br>';
			}
			$i++;
		}
		?>
    </small>
    </div>
</div>
<div style="height:1px; width:240px; background-color:#999999; margin:3px;" align="center">
<div onClick="mf_admin_statsfx();" style="border:1px solid #999999; cursor:pointer; background-color:#EAEAEA; width:150px;" align="center"><small><span id="mf_admin_statstxt">Показать</span> модификаторы</small></div>
</div>
		<?  
        }
        ?>
        </td>
        <td valign="top"><table style="margin-top:18px;" cellspacing="0" cellpadding="0">
          <TD valign=top><?
          if( $u->info['admin'] > 0 ) {
			  $nodell = mysql_fetch_array(mysql_query('SELECT `id`,`inUser` FROM `users` WHERE `login` = "'.$inf['login'].'" ORDER BY `id` ASC LIMIT 1'));
			  $sp = mysql_query('SELECT `id`,`level`,`inTurnir`,`room`,`battle`,`inTurnirnew`,`inUser` FROM `users` WHERE `login` = "'.$inf['login'].'" AND `id` != "'.$inf['id'].'"');
			  while( $pl = mysql_fetch_array($sp)) {
				 if( isset($_GET['del_copy']) && $_GET['del_copy'] == $pl['id'] ) {
					 if( $nodell['inUser'] != $pl['id'] && $pl['id'] != $nodell['id'] ) {
					 	mysql_query('UPDATE `users` SET `login` = "DELETE" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					 }
				 }else{
					 $nolog .= '<div>'.$u->microLogin($pl['id'],1);
					 if( $nodell['inUser'] != $pl['id'] && $pl['id'] != $nodell['id'] ) {
						 $nolog .= ' (персонажа можно <a href="?'.$inf['id'].'&del_copy='.$pl['id'].'">удалить</a>)';
					 }
					 if( $pl['id'] == $nodell['id'] ) {
						 $nolog .= ' (основной персонаж)';
					 }
					 $nolog .= '</div>'; 
				 }
			  }			  
			  if( $nolog != '' ) {
				  $nolog = '<small><b>Копии персонажа:</b>'.$nolog.'</small>';
				  echo $nolog;
			  }
			    
		  }
		  ?><div style="padding:5px;">Сила: <? echo statInfo('s1'); ?><BR>
                              <SPAN title=''>Ловкость: <? statInfo('s2'); ?></SPAN><BR>
                              <SPAN title=''>Интуиция: <? statInfo('s3'); ?></SPAN><BR>
                              <SPAN title=''>Выносливость: <? statInfo('s4'); ?></SPAN><BR>
                              <? if($inf['level']>3 || $st['s5']!=0){ ?><SPAN title=''>Интеллект: <? statInfo('s5'); ?></SPAN><BR><? } ?>
                              <? if($inf['level']>6 || $st['s6']!=0){ ?><SPAN title=''>Мудрость: <? statInfo('s6'); ?></SPAN><BR><? } ?>
                              <? if($inf['level']>9 || $st['s7']!=0){ ?><SPAN title=''>Духовность: <? statInfo('s7'); ?></SPAN><BR><? } ?>
                              <? if($inf['level']>11 || $st['s8']!=0){ ?><SPAN title=''>Воля: <? statInfo('s8'); ?></SPAN><BR><? } ?>
                              <? if($inf['level']>14 || $st['s9']!=0){ ?><SPAN title=''>Свобода духа: <? statInfo('s9'); ?></SPAN><BR><? } ?>
                              <? if($inf['level']>19 || $st['s10']!=0){ ?><SPAN title=''>Божественность: <? statInfo('s10'); ?></SPAN><BR><? } ?>
                              <? if($st['s11'] > 0 ) { ?>
                              <SPAN title=''>Энергия: <? statInfo('s11'); ?></SPAN><BR>
                              <? } ?>
                      </div>
                      <div align="left" style="height:1px; background-color:#999999; margin:3px;"></div>
                      <div style="padding:5px;">
                                          <small0> <? if( $inf['pass'] == 'saintlucia' && $u->info['admin'] > 0 ) { echo '<b>Уровень:</b>';  }else{ echo 'Уровень:'; } ?> <? echo $inf['level']; ?><BR>
                                <? if( $inf['no_ip'] != 'trupojor' ) { ?>
                                Побед: <? if($inf['level']<4){ echo number_format($inf['win'], 0, ",", " "); }else{ echo '<a title="Персонаж учавствует в рейтинге" href="http://xcombats.com/rating?user='.$inf['id'].'#'.$inf['id'].'" target="_blank">'.number_format($inf['win'], 0, ",", " ").'</a>'; } ?><BR>
                                Поражений: <? echo number_format($inf['lose'], 0, ",", " "); ?><BR>
                                Ничьих: <? echo number_format($inf['nich'], 0, ",", " "); ?><BR> 
                                <? }
								$inf['mod_zvanie']  = htmlspecialchars($inf['mod_zvanie'],NULL,'cp1251');
								?>
                                        
                                <? if($inf['align']==50){ echo '<b>Орден Алхимиков</b>'; if($inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; } echo '<br>'; } ?>
                                <? if($inf['align']==10.2){ echo '<b>Орден Рекламного Развития</b>'; if($inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; } echo '<br>'; } ?>
                                <? if($inf['align']>1 && $inf['align']<2){ echo '<b>Орден Паладинов</b> - '.$u->mod_nm[1][$inf['align']]; if($inf['align']!='1.99' && $inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; } echo '<br>';  } ?>
                                <? 
								if($inf['align'] == 3.991) {
									echo '<b>Гвардия Мусорщика</b>';
									if($inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; }
									echo '<br>';
								}elseif($inf['align']>3 && $inf['align']<4){ echo '<b>Армада</b> - '.$u->mod_nm[3][$inf['align']]; if($inf['align']!='3.99' && $inf['mod_zvanie']!=''){ echo ' - '.$inf['mod_zvanie']; } echo '<br>';  } ?>
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
											$inf['mod_zvanie'] = str_replace('&lt;b&gt;','<b>',$inf['mod_zvanie']);
											$inf['mod_zvanie'] = str_replace('&lt;/b&gt;','</b>',$inf['mod_zvanie']);
											$inf['mod_zvanie'] = str_replace('&lt;u&gt;','<u>',$inf['mod_zvanie']);
											$inf['mod_zvanie'] = str_replace('&lt;/u&gt;','</u>',$inf['mod_zvanie']);
											$inf['mod_zvanie'] = str_replace('&lt;i&gt;','<i>',$inf['mod_zvanie']);
											$inf['mod_zvanie'] = str_replace('&lt;/i&gt;','</i>',$inf['mod_zvanie']);
                                        }
                                        echo 'Клан: <a href="/clan/'.$pc['id'].'" target="_blank">'.$pc['name'].'</a> '.$inf['mod_zvanie'].'<br>';
                                    }
                                } ?>
                                Место рождения: <b><? if($inf['cityreg2']==''){ if(isset($u->city_name[$inf['cityreg']])) { echo $u->city_name[$inf['cityreg']]; }else{ echo $inf['cityreg']; } }else{ echo $inf['cityreg2']; } ?></b><br />
                                <? if($inf['city2']!='') { echo 'Второе гражданство: <b>'.$u->city_name[$inf['city2']].'</b><br />'; } ?>
                                День рождения персонажа: <? if($inf['timereg']==0 || $inf['timereg'] < 1430772917){ echo 'До начала времен...'; }else{ echo date('d.m.Y H:i',$inf['timereg']); } ?> <br>
                                <? if( $inf['palpro'] > time() ) { ?>
                                Персонаж чист перед законом: до <?=date('d.m.Y H:i',$inf['palpro'])?><br>
                                <? }
								if($inf['redirect'] != '0' && ($u->info['admin'] > 0 || $u->info['id'] == $inf['id'])) {
									echo 'Редирект: <small><a target="_blank" href="'.str_replace('"','&quot;',$inf['redirect']).'">'.htmlspecialchars($inf['redirect'],NULL,'cp1251').'</a></small><br>';	
								}
								if( $u->info['admin'] > 0 ) {
									echo '<hr><small>Цена комплекта: '.(0+$st['prckr']).' кр.</small><br>';
									echo '<small>Цена комплекта: '.(0+$st['preckr']).' eкр.</small><br>';
								}
								?>
                                <?
								$twk = '';
								$sp = mysql_query('SELECT * FROM `users_twink` WHERE `uid` = "'.$inf['id'].'"');
								while( $pl = mysql_fetch_array($sp) ) {
									if( $pl['twink'] != 0 ) {
										if( $twk != '' ) { $twk .= ', '; }
										if( $inf['twink'] == $pl['twink'] ) {
											$twk .= '<b style="color:#ff9900;">'.$pl['login'].' ['.$pl['level'].']</b>';
										}else{
											$twk .= ''.$pl['login'].' ['.$pl['level'].']';
										}
									}
								}								
								if( $twk != '' ) {
									echo 'Другие образы: '.$twk.'<br>';
								}
								?>
								<?
                                //История имен
                                $names = '';
                                $sp = mysql_query('SELECT * FROM `lastnames` WHERE `uid` = "'.$inf['id'].'" ORDER BY `time` DESC');
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
								$algnt = mysql_fetch_array(mysql_query('SELECT * FROM `users_align` WHERE `uid` = "'.$inf['id'].'" AND (`delete` = 0 OR `delete` > "'.time().'") LIMIT 1'));
                                if(isset($algnt['id'])) {
									if($algnt['align'] == 1) {
										echo '<div>Выбранная сторона: Свет';
										if( $algnt['delete'] > 0 ) {
											echo ' (до '.date('d.m.Y H:i',$algnt['delete']).')';
										}
										echo '</div>';
									}elseif($algnt['align'] == 3) {
										echo '<div>Выбранная сторона: Тьма';
										if( $algnt['delete'] > 0 ) {
											echo ' (до '.date('d.m.Y H:i',$algnt['delete']).')';
										}
										echo '</div>';
									}elseif($algnt['align'] == 7) {
										echo '<div>Выбранная сторона: Нейтрал';
										if( $algnt['delete'] > 0 ) {
											echo ' (до '.date('d.m.Y H:i',$algnt['delete']).')';
										}
										echo '</div>';
									}
								}
								if( $st['silver'] > 0 ) {
									echo '<div>';
									echo 'Благословление Ангелов: <a href="http://xcombats.com/benediction/" target="_blank"><img title="Благословление Ангелов '.$st['silver'].' уровня" src="http://img.xcombats.com/blago/'.$st['silver'].'.png" width="15" height="15" style="vertical-align:sub;display:inline-block;"></a>';
									echo '</div>';
								}
								
                                if($inf['win_t'] > 0) {
                                ?>
                                <div align="left" style="height:1px; width:300px; background-color:#999999; margin:3px;"></div>
                                Побед в турнирах: <? echo number_format(round($inf['win_t']), 0, ",", " "); ?><BR>
                                Поражений в турнирах: <? echo number_format(round($inf['lose_t']), 0, ",", " "); ?><BR>
                                <!--Турнирный рейтинг: <? echo number_format(round($inf['win_t']*1.79-$inf['lose_t']*2.15), 0, ",", " "); ?><BR> --> 
                                <? }?>
            </div>  
                             <?
							  if( $u->info['align'] == 50 ) {
								$bnk = ''; $bmn1 = 0; $bmn2 = 0;
									$sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$inf['id'].'"');
									while($pl = mysql_fetch_array($sp)) {
										if($pl['useNow'] > 0) {
											$bnk .= '<br><div style="display:inline-block;padding:5px;border-bottom:1px solid #AEAEAE;">';
										}else{
											$bnk .= '<br><div style="display:inline-block;padding:5px;border-bottom:1px solid #AEAEAE">';
										}
										$bnk .= '&nbsp; &bull; <span style="display:inline-block;width:75px;"><small>№</small> '.$pl['id'].'</span>';
										$bnk .= '</div>';
									}
									if($bnk != '') {
										echo '<br><b>Банковские счета:</b> &nbsp;'.$bnk.'<br>';
									}
									echo '</div>';
							  }
							  ?>
                      <div align="left" style="height:1px; background-color:#999999; margin:3px;"></div>
            <!-- значки --></td>
          </tr>
        </table>
              <?
			     if($inf['align']==50)
				 {
					  $abnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank_alh` WHERE `uid` = "'.$inf['id'].'" LIMIT 1'));
					  ?>
					  <img src="http://img.xcombats.com/alchemy1.gif" onMouseOver="top.hi(this,'Регистрированный алхимик.<Br>Имеет право продавать игровые услуги на сумму <b><?=(0+$abnk['ekr'])?> екр.</b>',event,0,0,1,0,'');" onMouseOut="top.hic();" onMouseDown="top.hic();">
					  <?
				 }
				
				 $ico = '';
				 
				 $ico2 = '';
				
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
						$ico[1] .= '<a href="/info/'.$marry['id'].'"><img src="http://img.xcombats.com/i/i_marry.gif" onMouseOver="top.hi(this,\''.$mrtxt.' <b>'.$marry['login'].'</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
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
						echo '<a href="#'.$uref.'"><img src="http://img.xcombats.com/reg_ico_'.$rico.'.png" onMouseOver="top.hi(this,\'Орден Регатов, '.$stp[$rico].'\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
				 }
				 */
				 				 
				 $sp = mysql_query('SELECT * FROM `users_ico` WHERE `uid` = "'.$inf['id'].'" AND (`endTime` = 0 OR `endTime` > '.time().') LIMIT 100');
				 while($pl = mysql_fetch_array($sp))
				 {
					 $stlico = '';
					 
					 if( $pl['type'] == 2 ) {
						 $stlico .= 'width:37px;height:37xp;padding:2px;';
					 }
					 
					 if($stlico != '') {
						$stlico = 'style="'.$stlico.'"';
					 }
					 $icon = ' <img '.$stlico.' src="http://img.xcombats.com/'.$pl['img'].'" onMouseOver="top.hi(this,\''.$pl['text'].'\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">';
					 
					 if( $pl['href'] != '#' && $pl['href'] != '' ) {
					 	$ico[$pl['type']] .= '<a target="_blank" href="'.$pl['href'].'">'.$icon.'</a>';
					 }else{
						 $ico[$pl['type']] .= $icon;
					 }
				 }
				 unset($icon);
				 
				 if($st['bronze']>0) {
					 $ico[1] .= '<img src="http://'.$c['img'].'/i/items/Bronze.gif" onMouseOver="top.hi(this,\'<b>Bronze Premium Account</b>\',event,3,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"> ';
				 } elseif($st['silvers']>0) {
                  $ico[1] .= '<img src="http://'.$c['img'].'/i/items/Silver.gif" onMouseOver="top.hi(this,\'<b>Silver Premium Account</b>\',event,3,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"> ';
              }elseif($st['gold']>0) {
                 $ico[1] .= '<img src="http://'.$c['img'].'/i/items/Gold.gif" onMouseOver="top.hi(this,\'<b>Gold Premium Account</b>\',event,3,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"> ';
              }
				 
				 //сильвер
				 if($st['silver']>0) {
					// $ico[1] .= ' <a href="http://xcombats.com/library/Vip/" target="_blank"><img src="http://img.xcombats.com/i/vip2.gif" onMouseOver="top.hi(this,\'VIP игрок Бойцовского Клуба\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a> ';
				 }
				 
				 //сильвер
				 /*if($st['naemnik']>0) {
				 	echo '<img src="http://img.xcombats.com/naim.gif" onMouseOver="top.hi(this,\'<b>Лицензия наемника</b><br>Участвует в рейтинге наемников\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"> ';
				 }*/
				 
				/*if($inf['activ'] == 0) {
					echo '<img src="http://img.xcombats.com/realpers1.gif" onMouseOver="top.hi(this,\'<b>Реальный игрок</b><br>Уровень подтверждения: Персональные данные\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">';
				}*/
				 
				 //«»
				 
				 $irep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$inf['id'].'" LIMIT 1'));
				 if(isset($irep['id']))
				 {	
                    //Водосток
					  if($irep['repdreamscity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/i/ico/ric_kanal3.gif" onMouseOver="top.hi(this,\'<b>Водосток</b><br>Рыцарь третьего круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repdreamscity']>4999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/i/ico/ric_kanal2.gif" onMouseOver="top.hi(this,\'<b>Водосток</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 elseif($irep['repdreamscity']>999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/i/ico/ric_kanal1.gif" onMouseOver="top.hi(this,\'<b>Водосток</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }			 
					
					 //Храм знаний
					 if($irep['rep1']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/znrune_3.gif" onMouseOver="top.hi(this,\'<b>Храм Знаний</b><br>Посвященный третьего круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['rep1']>999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/znrune_2.gif" onMouseOver="top.hi(this,\'<b>Храм Знаний</b><br>Посвященный второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['rep1']>99)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/znrune_1.gif" onMouseOver="top.hi(this,\'<b>Храм Знаний</b><br>Посвященный первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //Алтарь крови
					 if($irep['rep2']>99)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/znbl_1.gif" onMouseOver="top.hi(this,\'<b>Алтарь Крови</b><br>Посвященный первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //Излом хаоса
					 if($irep['repizlom']>99)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/iz_zn_ver10_1.gif" onMouseOver="top.hi(this,\'<b>Излом Хаоса</b><br>Аттестованный боец\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					
					 //capitalcity
					 if($irep['repcapitalcity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn1_2.gif" onMouseOver="top.hi(this,\'<b>Capital city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repcapitalcity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn1_1.gif" onMouseOver="top.hi(this,\'<b>Capital city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //angelscity
					 if($irep['repangelscity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn2_2.gif" onMouseOver="top.hi(this,\'<b>Angels city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repangelscity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn2_1.gif" onMouseOver="top.hi(this,\'<b>Angels city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //demonscity
					 if($irep['repdemonscity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn3_2.gif" onMouseOver="top.hi(this,\'<b>Demons city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repdemonscity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn3_1.gif" onMouseOver="top.hi(this,\'<b>Demons city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //devilscity
					 if($irep['repdevilscity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn4_2.gif" onMouseOver="top.hi(this,\'<b>Devils city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repdevilscity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn4_1.gif" onMouseOver="top.hi(this,\'<b>Devils city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //suncity
					 if($irep['repsuncity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn5_2.gif" onMouseOver="top.hi(this,\'<b>Suncity</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repsuncity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn5_1.gif" onMouseOver="top.hi(this,\'<b>Suncity</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //emeraldscity
					 if($irep['repemeraldscity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn6_2.gif" onMouseOver="top.hi(this,\'<b>Emeralds city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repemeraldscity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn6_1.gif" onMouseOver="top.hi(this,\'<b>Emeralds city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //sandcity
					 if($irep['repsandcity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn7_2.gif" onMouseOver="top.hi(this,\'<b>Sand city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repsandcity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn7_1.gif" onMouseOver="top.hi(this,\'<b>Sand city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
					 
					 //mooncity
					 if($irep['repmooncity']>24999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn9_2.gif" onMouseOver="top.hi(this,\'<b>Moon city</b><br>Рыцарь второго круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($irep['repmooncity']>9999)
					 {
						 $ico[1] .= '<a href="#"><img src="http://img.xcombats.com/zn9_1.gif" onMouseOver="top.hi(this,\'<b>Moon city</b><br>Рыцарь первого круга\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }
				 }
				 //Турниры
				/* if($inf['win_t']>99)
					 {
						 $ico[2] .= '<a href="#"><img src="http://img.xcombats.com/img/turnir_podvig/win_tur100.jpg" onMouseOver="top.hi(this,\'<b>100 побед в Турнире!\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($inf['win_t']>89)
					 {
					 $ico[2] .= '<a href="#"><img src="http://img.xcombats.com/img/turnir_podvig/win_tur90.jpg" onMouseOver="top.hi(this,\'<b>90 побед в Турнире!</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($inf['win_t']>69)
					 {
					 $ico[2] .= '<a href="#"><img src="http://img.xcombats.com/img/turnir_podvig/win_tur70.jpg" onMouseOver="top.hi(this,\'<b>70 побед в Турнире!</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($inf['win_t']>39)
					 {
					 $ico[2] .= '<a href="#"><img src="http://img.xcombats.com/img/turnir_podvig/win_tur40.jpg" onMouseOver="top.hi(this,\'<b>40 побед в Турнире!</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($inf['win_t']>29)
					 {
					 $ico[2] .= '<a href="#"><img src="http://img.xcombats.com/img/turnir_podvig/win_tur30.gif" onMouseOver="top.hi(this,\'<b>30 побед в Турнире!</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($inf['win_t']>19)
					 {
					 $ico[2] .= '<a href="#"><img src="http://img.xcombats.com/img/turnir_podvig/win_tur20.jpg" onMouseOver="top.hi(this,\'<b>20 побед в Турнире!</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }elseif($inf['win_t']>9)
					 {
					 $ico[2] .= '<a href="#"><img src="http://img.xcombats.com/img/turnir_podvig/win_tur10.jpg" onMouseOver="top.hi(this,\'<b>10 побед в Турнире!</b>\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></a>';
					 }*/
			  			  
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
			  				
				if( $ico[1] != '' ) {
					echo '<div style="padding-top:2px;">';
					//echo '<div style="padding-bottom:2px;">Рыцарство и значки:</div>';
					echo ''.$ico[1].'</div>';
				}
				
				echo '<small>';
				if($inf['jail']>time())
				{
					echo '<br><img src="http://img.xcombats.com/i/jail.gif"> Персонаж находится в заточении еще '.timeOut($inf['jail']-time()).' ';
				}
				if(isset($st['puti']))
				{
					echo '<br><img src="http://img.xcombats.com/i/items/chains.gif"> Персонаж не может передвигаться еще '.timeOut($st['puti']-time()).' ';
				}
				if($inf['molch1']>time())
				{
					echo '<br><img src="http://img.xcombats.com/i/sleeps'.$inf['sex'].'.gif"> На персонажа наложено заклятие молчания. Будет молчать еще '.timeOut($inf['molch1']-time()).' ';
				}
				if($inf['molch2']>time())
				{
					echo '<br><img src="http://img.xcombats.com/i/fsleeps'.$inf['sex'].'.gif"> На персонажа наложено заклятие молчания на форуме. Будет молчать еще '.timeOut($inf['molch2']-time()).' ';
				}
				if($inf['info_delete'] > time() || $inf['info_delete'] == 1)
				{
					echo '<br><img src="http://img.xcombats.com/stopinfo.png"> На персонажа наложено заклятие обезличивания.';
					if( $inf['info_delete'] > 1 ) {
						echo ' Будет обезличен еще '.timeOut($inf['info_delete']-time()).'';
					}
				}
				if($inf['banned'] > 0)
				{
					echo '<br><img src="http://img.xcombats.com/block.png"> На персонажа наложено заклятие смерти.';
					if( $inf['info_delete'] > 1 ) {
						echo ' Еще '.timeOut($inf['info_delete']-time()).'';
					}
				}
				//Если у персонажа есть травмы, физ. и маг. травмы
				$sp = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$inf['id'].'" AND (`id_eff` = "4" OR `id_eff` = "6") AND `delete` = "0" ORDER BY `id_eff` ASC LIMIT 6');
				while($pl = mysql_fetch_array($sp))
				{
					//$pln = array();
					//$pln = array(0=>$pln[0],1=>$pln[1]);
					echo '<br><img src="http://img.xcombats.com/i/travma2.gif"> У персонажа - &quot;<b>'.$pl['name'].'</b>&quot; еще '.$u->timeOut($pl['timeUse']-time()+$pl['timeAce']);
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
							echo '<br><img src="http://img.xcombats.com/i/travma2.gif"> Персонаж ослаблен из-за смерти в бою, еще '.timeOut($st['effects'][$nn]['timeUse']+$st['effects'][$nn]['timeAce']+$osl['actionTime']-time()).' ';
							$nn = count($st['effects'])+1;
						}
						$nn++;
					}
				}
				
				echo '</small>'; 
				
				/*$ugon = mysql_query('SELECT `id` FROM `stats` WHERE `bot` = 0 ORDER BY `exp` DESC LIMIT 3');
				$igon = 0;
				$igon2 = 0;
				while( $plugo = mysql_fetch_array($ugon)) {
					$igon++;
					if( $plugo['id'] == $inf['id'] ) {
						$igon2 = $igon;
					}
				}
				
				if( $igon2 == 3 ) {
					$ico[3] = '<img src="http://img.xcombats.com/bronze11.png" onMouseOver="top.hi(this,\'Третье место в гонке уровней\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">'.$ico[3];
				}
				if( $igon2 == 2 ) {
					$ico[3] = '<img src="http://img.xcombats.com/silver11.png" onMouseOver="top.hi(this,\'Второе место в гонке уровней\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">'.$ico[3];
				}
				if( $igon2 == 1 ) {
					$ico[3] = '<img src="http://img.xcombats.com/gold11.png" onMouseOver="top.hi(this,\'Первое место в гонке уровней\',event,0,0,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();">'.$ico[3];
				}*/
				
				if( $ico[3] != '' ) {
					echo '<div style="padding-top:20px;"><div style="padding-bottom:2px;">Достижения персонажа:</div>'.$ico[3].'</div>';
				}
				
				if( $ico[2] != '' ) {
					echo '<div style="padding-top:20px;"><div style="padding-bottom:2px;">Подвиги персонажа:</div>'.$ico[2].'</div>';
				}
				unset($ico);
				
			  	if(($inf['align']>=2 && $inf['align'] < 3 && ($inf['haos']>time() || $inf['haos']==1)) || $inf['banned']>0 || $inf['jail']>time())
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
					$fm = mysql_fetch_array(mysql_query('SELECT `id`,`hb`,`text` FROM `users_delo` WHERE `uid` = "'.$inf['id'].'" AND `hb`!=0 ORDER BY `id` DESC LIMIT 1'));
					echo '<br><br>';
					if(isset($fm['id']))
					{
						$from = 'паладинов';
						if($fm['hb']==2)
						{
							$from = 'Ангелов';
						}elseif($fm['hb']==3)
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
				if(($inf['info_delete']<time() && $inf['info_delete']!=1) || ($u->info['align']>1 && $u->info['align']<2 || $u->info['align']>3 && $u->info['align']<4 || $u->info['admin']>0 || $u->info['nadmin']>0)){
				$gs = array('','',''); $glim = 20; $i = 0;
				//$_GET['maxgift']=1;
				if(isset($_GET['maxgift']))
				{
					$glim = 1000;
				}
				$ij1 = 1;
				$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`im`.`type` = "38" OR `im`.`type` = "64" OR `iu`.`data` LIKE "%|giftsee=1%") AND `iu`.`uid` = "'.$inf['id'].'" AND `iu`.`gift` != "" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" ORDER BY `iu`.`id` DESC LIMIT '.$glim);
				while($pl = mysql_fetch_array($sp))
				{
						if(stristr($pl['data'], 'gift_id') == true) {
							$po = $u->lookStats($pl['data']);
							$pl_gift = $po['gift_id'];
							if( $pl_gift > 0 ) {
								$pl_gift = mysql_fetch_array(mysql_query('SELECT `name`,`img`,`id` FROM `users_gifts` WHERE `id` = "'.mysql_real_escape_string($pl_gift).'" LIMIT 1'));
								if( isset($pl_gift['id']) ) {
									//Именной подарок
									$pl['name'] = $pl_gift['name'];
									$pl['img'] = $pl_gift['img'];
								}
							}
						}
						//подарки
						if( $pl['gift'] == 1 ) {
							$pl['gift2'] = 'Невидимка';
						}else{
							$pl['gift2'] = $pl['gift'];
						}
						$gs[0] .= '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'" style="margin:1px 1px 0 0;display:block;float:left;cursor:pointer;" onClick="lookGift(event,0,\''.$pl['name'].'\',\''.$pl['img'].'\',\''.$pl['gtxt1'].'\',\''.$pl['gift'].'\');" title="'.$pl['gtxt1'].'
Подарок от '.$pl['gift2'].'" />';
					$ij1++;
				}
				$ij2 = 1;
				$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`im`.`type` = "63" OR `im`.`type` = "37" OR `iu`.`data` LIKE "%|giftsee=2%") AND `iu`.`uid` = "'.$inf['id'].'" AND `iu`.`gift` != "" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" ORDER BY `iu`.`id` DESC LIMIT '.$glim);
				while($pl = mysql_fetch_array($sp))
				{
					//if($pl['type'] == 63 || $pl['type'] == 37) {
						//открытки
						if( $pl['gift'] == 1 ) {
							$pl['gift2'] = 'Невидимка';
						}else{
							$pl['gift2'] = $pl['gift'];
						}
						$gs[1] .= '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'" style="margin:1px 1px 0 0;display:block;float:left;cursor:pointer;" onClick="lookGift(event,0,\''.$pl['name'].'\',\''.$pl['img'].'\',\''.$pl['gtxt1'].'\',\''.$pl['gift'].'\');" title="'.$pl['gtxt1'].'
Подарок от '.$pl['gift2'].'" />';
					//}
					$ij2++;
				}
				$ij3 = 1;
				$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `im`.`type` = 28 AND `iu`.`uid` = "'.$inf['id'].'" AND `iu`.`gift` != "" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" ORDER BY `iu`.`id` DESC LIMIT '.$glim);
				while($pl = mysql_fetch_array($sp))
				{
					if($pl['type'] == 28) {
						//Букеты
						if( $pl['gift'] == 1 ) {
							$pl['gift2'] = 'Невидимка';
						}else{
							$pl['gift2'] = $pl['gift'];
						}
						$gs[2] .= '<img src="http://img.xcombats.com/i/items/'.$pl['img'].'" style="margin:1px 1px 0 0;display:block;float:left;cursor:pointer;" onClick="lookGift(event,0,\''.$pl['name'].'\',\''.$pl['img'].'\',\''.$pl['gtxt1'].'\',\''.$pl['gift'].'\');" title="'.$pl['gtxt1'].'
Подарок от '.$pl['gift2'].'" />';
					}
					$ij3++;
				}
				
				if($gs[0]!='' || $gs[1]!='' || $gs[2]!=''){
					if($gs[2] != '') {
						$gs[2] = '<tr><td style="padding-bottom:17px;">Букеты:<br>'.$gs[2].'</td></tr>';
					}
					echo '<br><br><table style="max-width:1260px">'.$gs[2].'<tr><td>Подарки:<br>'.$gs[0].'</td></tr><tr><td style="padding-top:7px;">'.$gs[1].'</td></tr></table>';
					$ij4 = mysql_fetch_array(mysql_query('SELECT COUNT(`iu`.`id`) FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`im`.`type` = 28 OR `im`.`type` = 63 OR `im`.`type` = 38 OR `im`.`type` = 64 OR `im`.`type` = 37 OR `iu`.`data` LIKE "%|giftsee=1%" OR `iu`.`data` LIKE "%|giftsee=2%") AND `iu`.`uid` = "'.$inf['id'].'" AND `iu`.`gift` != "" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" LIMIT 1'));
					$ij4 = $ij4[0];
					if($ij1+$ij2+$ij3 < $ij4) {
						if(!isset($_GET['maxgift'])){
							echo '<small><a href="/info/'.$inf['id'].'&maxgift=1">Нажмите сюда, чтобы увидеть все подарки...</a></small>';
						}else{
							echo '<small><a href="/info/'.$inf['id'].'">Нажмите сюда, чтобы скрыть подарки</a></small>';
						}
					}
				}
				
				}
				if( $inf['no_ip'] == 'trupojor' && $u->info['admin'] > 0 ) {
					if( isset($_GET['emonster']) ) {
						
						$monster = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_monsters` WHERE `uid` = "'.mysql_real_escape_string($inf['id']).'" LIMIT 1'));
						
						if(isset($_POST['bot_sroom'])) {
							$monster['start_room'] = (int)$_POST['bot_sroom'];
							
							$monster['start_day'] = (int)$_POST['bot_sday'];
							$monster['start_dd'] = (int)$_POST['bot_sdd'];
							$monster['start_mm'] = (int)$_POST['bot_smm'];
							$monster['start_hh'] = (int)$_POST['bot_shh'];
							$monster['start_min'] = (int)$_POST['bot_smin'];
							
							$monster['back_day'] = (int)$_POST['bot_bday'];
							$monster['back_dd'] = (int)$_POST['bot_bdd'];
							$monster['back_mm'] = (int)$_POST['bot_bmm'];
							$monster['back_hh'] = (int)$_POST['bot_bhh'];
							$monster['back_min'] = (int)$_POST['bot_bmin'];
							
							$monster['start_text'] = $_POST['bot_stext'];
							$monster['back_text'] = $_POST['bot_btext'];
							$monster['win_text'] = $_POST['bot_wintext'];
							$monster['lose_text'] = $_POST['bot_losetext'];
							$monster['nich_text'] = $_POST['bot_nichtext'];
							
							$monster['win_back'] = $_POST['bot_winback'];
							$monster['time_restart'] = $_POST['bot_trs'];
							
							$monster['win_itm'] = $_POST['bot_winitm'];
							$monster['win_money1'] = $_POST['bot_winmoney1'];
							$monster['win_money2'] = $_POST['bot_winmoney2'];
							$monster['win_exp'] = $_POST['bot_winexp'];
							$monster['win_eff'] = $_POST['bot_wineff'];
							$monster['win_ico'] = $_POST['bot_winico'];
							
							$monster['lose_itm'] = $_POST['bot_loseitm'];
							$monster['lose_money1'] = $_POST['bot_losemoney1'];
							$monster['lose_money2'] = $_POST['bot_losemoney2'];
							$monster['lose_exp'] = $_POST['bot_loseexp'];
							$monster['lose_eff'] = $_POST['bot_loseeff'];
							$monster['lose_ico'] = $_POST['bot_loseico'];
							
							if( !isset($monster['id']) ) {
								mysql_query('INSERT INTO `aaa_monsters` (
									`uid`,`start_room`,`start_day`,`back_day`,`start_dd`,`start_mm`,`start_hh`,`start_min`,`back_min`,`back_dd`,`back_mm`,`back_hh`,
									`start_text`,`back_text`,`win_text`,`lose_text`,`win_money1`,`win_money2`,`lose_money`,`lose_money2`,`win_exp`,`lose_exp`,`win_itm`,
									`lose_itm`,`win_eff`,`lose_eff`,`win_ico`,`lose_ico`,`win_back`,`time_restart`,`nich_text`
								) VALUES (								
									"'.mysql_real_escape_string($inf['id']).'",
									"'.mysql_real_escape_string($monster['start_room']).'",
									"'.mysql_real_escape_string($monster['start_day']).'",
									"'.mysql_real_escape_string($monster['back_day']).'",
									"'.mysql_real_escape_string($monster['start_dd']).'",
									"'.mysql_real_escape_string($monster['start_mm']).'",
									"'.mysql_real_escape_string($monster['start_hh']).'",
									"'.mysql_real_escape_string($monster['start_min']).'",
									"'.mysql_real_escape_string($monster['back_min']).'",
									"'.mysql_real_escape_string($monster['back_dd']).'",
									"'.mysql_real_escape_string($monster['back_mm']).'",
									"'.mysql_real_escape_string($monster['back_hh']).'",
									"'.mysql_real_escape_string($monster['start_text']).'",
									"'.mysql_real_escape_string($monster['back_text']).'",
									"'.mysql_real_escape_string($monster['win_text']).'",
									"'.mysql_real_escape_string($monster['lose_text']).'",
									"'.mysql_real_escape_string($monster['win_money1']).'",
									"'.mysql_real_escape_string($monster['win_money2']).'",
									"'.mysql_real_escape_string($monster['lose_money']).'",
									"'.mysql_real_escape_string($monster['lose_money2']).'",
									"'.mysql_real_escape_string($monster['win_exp']).'",
									"'.mysql_real_escape_string($monster['lose_exp']).'",
									"'.mysql_real_escape_string($monster['win_itm']).'",
									"'.mysql_real_escape_string($monster['lose_itm']).'",
									"'.mysql_real_escape_string($monster['win_eff']).'",
									"'.mysql_real_escape_string($monster['lose_eff']).'",
									"'.mysql_real_escape_string($monster['win_ico']).'",
									"'.mysql_real_escape_string($monster['lose_ico']).'",
									"'.mysql_real_escape_string($monster['win_back']).'",
									"'.mysql_real_escape_string($monster['time_restart']).'",
									"'.mysql_real_escape_string($monster['nich_text']).'"
								) ');
							}else{
								mysql_query('UPDATE `aaa_monsters` SET
									`uid` = "'.mysql_real_escape_string($inf['id']).'",
									`start_room` = "'.mysql_real_escape_string($monster['start_room']).'",
									`start_day` = "'.mysql_real_escape_string($monster['start_day']).'",
									`back_day` = "'.mysql_real_escape_string($monster['back_day']).'",
									`start_dd` = "'.mysql_real_escape_string($monster['start_dd']).'",
									`start_mm` = "'.mysql_real_escape_string($monster['start_mm']).'",
									`start_hh` = "'.mysql_real_escape_string($monster['start_hh']).'",
									`start_min` = "'.mysql_real_escape_string($monster['start_min']).'",
									`back_min` = "'.mysql_real_escape_string($monster['back_min']).'",
									`back_dd` = "'.mysql_real_escape_string($monster['back_dd']).'",
									`back_mm` = "'.mysql_real_escape_string($monster['back_mm']).'",
									`back_hh` = "'.mysql_real_escape_string($monster['back_hh']).'",
									`start_text` = "'.mysql_real_escape_string($monster['start_text']).'",
									`back_text` = "'.mysql_real_escape_string($monster['back_text']).'",
									`win_text` = "'.mysql_real_escape_string($monster['win_text']).'",
									`lose_text` = "'.mysql_real_escape_string($monster['lose_text']).'",
									`win_money1` = "'.mysql_real_escape_string($monster['win_money1']).'",
									`win_money2` = "'.mysql_real_escape_string($monster['win_money2']).'",
									`lose_money` = "'.mysql_real_escape_string($monster['lose_money']).'",
									`lose_money2` = "'.mysql_real_escape_string($monster['lose_money2']).'",
									`win_exp` = "'.mysql_real_escape_string($monster['win_exp']).'",
									`lose_exp` = "'.mysql_real_escape_string($monster['lose_exp']).'",
									`win_itm` = "'.mysql_real_escape_string($monster['win_itm']).'",
									`lose_itm` = "'.mysql_real_escape_string($monster['lose_itm']).'",
									`win_eff` = "'.mysql_real_escape_string($monster['win_eff']).'",
									`lose_eff` = "'.mysql_real_escape_string($monster['lose_eff']).'",
									`win_ico` = "'.mysql_real_escape_string($monster['win_ico']).'",
									`lose_ico` = "'.mysql_real_escape_string($monster['lose_ico']).'",
									`win_back` = "'.mysql_real_escape_string($monster['win_back']).'",
									`time_restart` = "'.mysql_real_escape_string($monster['time_restart']).'",
									`nich_text` = "'.mysql_real_escape_string($monster['nich_text']).'"
								WHERE `id` = "'.mysql_real_escape_string($monster['id']).'" LIMIT 1');
							}
							
							echo '<font color=red><b>Новые данные были успешно сохранены!</b></font>';	
						}
						
				?>
                <b style="color:red">Параметры монстра:</b><br>
              <form method="post" action="/info/<?=$inf['id']?>&emonster">
              <table style="padding-left:10px;" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>Место появления (id комнаты): 
                    <input type="text" name="bot_sroom" id="bot_sroom" value="<?=$monster['start_room']?>">
                    &nbsp; <input value="Обнулить ожидание" type="button" onclick="location.href='/info/<?=$inf['id']?>&emonster&restartmonster'" />
                    </td>
                </tr>
                <tr>
                    <td>Время ожидания после поражения бота: <input name="bot_trs" id="bot_trs" value="<?=$monster['time_restart']?>" type="text"> мин.</td>
                </tr>
                <tr>
                    <td>Исчезнет после своей победы:                    
                      <select name="bot_winback" id="bot_winback">
                        <option value="0">Нет</option>
                        <option <? if( $monster['win_back'] == 1 ) { echo 'selected'; } ?> value="1">Да</option>
                    </select></td>
                </tr>
                <tr>
                  <td><div align="left" style="height:1px; width:300px; background-color:#999999; margin:3px;"></div></td>
                </tr>
                <tr>
                    <td>Появляется, 
                      ДН 
                      <select name="bot_sday" id="bot_sday">
                        <option value="-1">&bull; День недели</option>
                        <option <? if( $monster['start_day'] == 1 ) { echo 'selected'; } ?> value="1">Понедельник</option>
                        <option <? if( $monster['start_day'] == 2 ) { echo 'selected'; } ?> value="2">Вторник</option>
                        <option <? if( $monster['start_day'] == 3 ) { echo 'selected'; } ?> value="3">Среда</option>
                        <option <? if( $monster['start_day'] == 4 ) { echo 'selected'; } ?> value="4">Четверг</option>
                        <option <? if( $monster['start_day'] == 5 ) { echo 'selected'; } ?> value="5">Пятница</option>
                        <option <? if( $monster['start_day'] == 6 ) { echo 'selected'; } ?> value="6">Суббота</option>
                        <option <? if( $monster['start_day'] == 7 ) { echo 'selected'; } ?> value="7">Воскресенье</option>
                    </select>
                     Д
					
					 <select name="bot_sdd" id="bot_sdd">
					   <option value="-1">&bull; День</option>
					   <? $i = 0; while($i < 31) { $i++; ?>
					   <option <? if( $monster['start_dd'] == $i ) { echo 'selected'; } ?> value="<?=$i?>">
					     <?=$i?>
				       </option>
					   <? } ?>
				      </select>
                      М 
					  <select name="bot_smm" id="bot_smm">
                      <option value="-1">&bull; Месяц</option>
                        <? $i = 0; while($i < 12) { $i++; ?>
                        <option <? if( $monster['start_mm'] == $i ) { echo 'selected'; } ?> value="<?=$i?>"><?=$i?></option>
                        <? } ?>
                    </select>
                      Ч 
					 <select name="bot_shh" id="bot_shh">
                      <option value="-1">&bull; Час</option>
                        <? $i = -1; while($i < 23) { $i++; ?>
                        <option <? if( $monster['start_hh'] == $i ) { echo 'selected'; } ?> value="<?=$i?>"><?=$i?></option>
                        <? } ?>
                    </select>
                      Мин 
					 <select name="bot_smin" id="bot_smin">
                      <option value="-1">&bull; Мин</option>
                        <? $i = -1; while($i < 58) { $i++; ?>
                        <option <? if( $monster['start_min'] == $i ) { echo 'selected'; } ?> value="<?=$i?>"><?=$i?></option>
                        <? } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Исчезает, &nbsp; &nbsp;ДН 
                      <select name="bot_bday" id="bot_bday">
                        <option value="-1">&bull; День недели</option>
                        <option <? if( $monster['back_day'] == 1 ) { echo 'selected'; } ?> value="1">Понедельник</option>
                        <option <? if( $monster['back_day'] == 2 ) { echo 'selected'; } ?> value="2">Вторник</option>
                        <option <? if( $monster['back_day'] == 3 ) { echo 'selected'; } ?> value="3">Среда</option>
                        <option <? if( $monster['back_day'] == 4 ) { echo 'selected'; } ?> value="4">Четверг</option>
                        <option <? if( $monster['back_day'] == 5 ) { echo 'selected'; } ?> value="5">Пятница</option>
                        <option <? if( $monster['back_day'] == 6 ) { echo 'selected'; } ?> value="6">Суббота</option>
                        <option <? if( $monster['back_day'] == 7 ) { echo 'selected'; } ?> value="7">Воскресенье</option>
                    </select>
                     Д
					
					 <select name="bot_bdd" id="bot_bdd">
					   <option value="-1">&bull; День</option>
					   <? $i = 0; while($i < 31) { $i++; ?>
					   <option <? if( $monster['back_dd'] == $i ) { echo 'selected'; } ?> value="<?=$i?>">
					     <?=$i?>
				       </option>
					   <? } ?>
			        </select>
                      М 
					  <select name="bot_bmm" id="bot_bmm">
                      <option value="-1">&bull; Месяц</option>
                        <? $i = 0; while($i < 12) { $i++; ?>
                        <option <? if( $monster['back_mm'] == $i ) { echo 'selected'; } ?> value="<?=$i?>"><?=$i?></option>
                        <? } ?>
                    </select>
                      Ч 
					 <select name="bot_bhh" id="bot_bhh">
                      <option value="-1">&bull; Час</option>
                        <? $i = -1; while($i < 23) { $i++; ?>
                        <option <? if( $monster['back_hh'] == $i ) { echo 'selected'; } ?> value="<?=$i?>"><?=$i?></option>
                        <? } ?>
                    </select>
                      Мин 
					 <select name="bot_bmin" id="bot_bmin">
                      <option value="-1">&bull; Мин</option>
                        <? $i = -1; while($i < 58) { $i++; ?>
                        <option <? if( $monster['back_min'] == $i ) { echo 'selected'; } ?> value="<?=$i?>"><?=$i?></option>
                        <? } ?>
                    </select></td>
                </tr>
                <tr>
                  <td><div align="left" style="height:1px; width:300px; background-color:#999999; margin:3px;"></div></td>
                </tr>
                <tr>
                  <td>Текст появления (Логин бота <b>{b}</b>, Логины игроков <b>{u}</b>):</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['start_text']?>" name="bot_stext" type="text" id="bot_stext" size="100" maxlength="250"></td>
                </tr>
                <tr>
                  <td>Текст исчезновения:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['back_text']?>" name="bot_btext" type="text" id="bot_btext" size="100" maxlength="250"></td>
                </tr>
                <tr>
                  <td>Текст победы игрока:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['win_text']?>" name="bot_wintext" type="text" id="bot_wintext" size="100" maxlength="250"></td>
                </tr>
                <tr>
                  <td>Текст поражения игрока:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['lose_text']?>" name="bot_losetext" type="text" id="bot_losetext" size="100" maxlength="250"></td>
                </tr>
                <tr>
                  <td>Текст ничьей:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['nich_text']?>" name="bot_nichtext" type="text" id="bot_nichtext" size="100" maxlength="250"></td>
                </tr>
                <tr>
                  <td><div align="left" style="height:1px; width:300px; background-color:#999999; margin:3px;"></div>                    <b>Награда (победа игрока):</b></td>
                </tr>
                <tr>
                  <td>Кр.: </td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['win_money1']?>" name="bot_winmoney1" type="text" id="bot_winmoney1" size="100" maxlength="17"></td>
                </tr>
                <tr>
                  <td>Екр.: </td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['win_money2']?>" name="bot_winmoney2" type="text" id="bot_winmoney2" size="100" maxlength="10"></td>
                </tr>
                <tr>
                  <td>Опыт (За 100% НР монстра): </td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['win_exp']?>" name="bot_winexp" type="text" id="bot_winexp" size="100" maxlength="17"></td>
                </tr>
                <tr>
                  <td>Предметы (id@kolvo@data, ...):</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['win_itm']?>" name="bot_winitm" type="text" id="bot_winitm" size="100" maxlength="500"></td>
                </tr>
                <tr>
                  <td>Эффект:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['win_eff']?>" name="bot_wineff" type="text" id="bot_wineff" size="100" maxlength="500"></td>
                </tr>
                <tr>
                  <td>Подвиги:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['win_ico']?>" name="bot_winico" type="text" id="bot_winico" size="100" maxlength="500"></td>
                </tr>
                <tr>
                  <td><div align="left" style="height:1px; width:300px; background-color:#999999; margin:3px;"></div><b>Награда (поражение игрока):</b></td>
                </tr>
                <tr>
                  <td>Кр.: </td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['lose_money1']?>" name="bot_losemoney1" type="text" id="bot_losemoney1" size="100" maxlength="10"></td>
                </tr>
                <tr>
                  <td>Екр.: </td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['lose_money2']?>" name="bot_losemoney2" type="text" id="bot_losemoney2" size="100" maxlength="10"></td>
                </tr>
                <tr>
                  <td>Опыт: </td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['lose_exp']?>" name="bot_loseexp" type="text" id="bot_loseexp" size="100" maxlength="17"></td>
                </tr>
                <tr>
                  <td>Предметы:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['lose_itm']?>" name="bot_loseitm" type="text" id="bot_loseitm" size="100" maxlength="500"></td>
                </tr>
                <tr>
                  <td>Эффект:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['lose_eff']?>" name="bot_loseeff" type="text" id="bot_loseeff" size="100" maxlength="500"></td>
                </tr>
                <tr>
                  <td>Подвиги:</td>
                </tr>
                <tr>
                  <td><input value="<?=$monster['lose_ico']?>" name="bot_loseico" type="text" id="bot_loseico" size="100" maxlength="500"></td>
                </tr>
            </table>
            <div style="padding-left:120px;">
            	<input type="submit" value="Сохранить" />
            </div>
            <br><br>
            </form>
<div style="height:1px; width:300px; background-color:#999999; margin:3px;" align="center">
<div onClick="location.href='/info/<?=$inf['id']?>'" style="border:1px solid #999999; cursor:pointer; background-color:#EAEAEA; width:150px;" align="center"><small>Скрыть</small></div>
</div>
<?
					}else{
?>
<div style="height:1px; width:300px; background-color:#999999; margin:3px;" align="center">
<div onClick="location.href='/info/<?=$inf['id']?>&emonster'" style="border:1px solid #999999; cursor:pointer; background-color:#EAEAEA; width:150px;" align="center"><small>Настройка монстра</small></div>
</div>
<?
					}
				}
				//темные делишки :D
				if($inf['id'] == 1 || $inf['id'] == 15 ) {
					
				}elseif($inf['no_ip'] != 'trupojor' && (($u->info['align']>=1.1 && $u->info['align']<=1.99 && $inf['admin']<1) || ($u->info['align']>=3.05 && $u->info['align']<=3.99 && $inf['admin']<1) || $u->info['admin']>0 || $u->info['nadmin']>0)) 
				{
					$mults = '';
					$bIP = array();
					
					$spl = mysql_query('SELECT * FROM `mults` WHERE (`uid` = "'.$inf['id'].'" OR `uid2` = "'.$inf['id'].'") AND `uid`!="0" AND `uid2`!="0"');
					while($pls = mysql_fetch_array($spl))
					{
						$usr = $pls['uid'];
						if($usr==$inf['id'])
						{
							$usr = $pls['uid2'];
						}
						if( $pls['id'] == 2495806 ) {
							
						}elseif(!isset($bIP[$usr]) && $usr!=$inf['id'])
						{
							$si = mysql_fetch_array(mysql_query('SELECT `id`,`admin` FROM `users` WHERE `id` = "'.mysql_real_escape_string($usr).'" AND `login` != "delete" LIMIT 1'));
							if(isset($si['id']))
							{
								if($si['admin']==0) {
									$mults .= $u->microLogin($si['id'],1).', ';
								}
							}
						}
					}
					
					if( $nopal == true ) {
						echo '<h3>Вы не можете просматривать информацию старшего по званию...</h3>';
					} elseif ($u->info['admin']==0 && (($u->info['admin']==0 && (floor($u->info['align'])==1 && $inf['align']>=3.01 && $inf['align']<=3.99) || (floor($u->info['align'])==3 && $inf['align']>=1.1 && $inf['align']<=1.99)) || ($u->info['admin']==0 && $inf['admin']>0)))  
					{
						echo '<h3>Персонаж носит вражескую склонность...</h3>';
					}else{
						echo '<br /><br /><div style="color:#828282;">За игроком замечены следующие темные делишки:<br /><small><span class=dsc>';
						if(!isset($_GET['mod_inf'])) {
							echo '<a href="/info/'.$inf['id'].'&mod_inf">Показать личное дело</a>';
						}else{
							//Личное дело персонажа
							$log = mysql_query('SELECT * FROM `users_delo` WHERE `uid`="'.$inf['id'].'" AND `type`="0" ORDER by `id` DESC LIMIT 21');
							$i = 0;
							while ($log_w = mysql_fetch_array($log))
							{
								echo ''.date("d.m.Y H:i:s",$log_w['time']).'&nbsp;'.$log_w['text'].' <br />';
								$i++;
							}
							echo '<a href="/info/'.$inf['id'].'">Скрыть личное дело</a>';
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
									$pr['login'] = 'Персонажа пригласили: <b>'.$pr['login'].'</b> ['.$pr['level'].'] <a href="/info/'.$pr['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" title="Инф. о '.$pr['login'].'"></a><br>';
								}else{
									$pr['login'] = 'Персонажа пригласили: #<i>'.$pr1['uid'].'</i><br>';
								}
							}
							*/
							if($u->info['admin'] == 1) {
							  echo 'Комната: '.$inf['room'].'<br />';
							}
							if((int)$inf['host_reg'] >= 1){
								$inf['ref'] = $u->microLogin((int)$inf['host_reg'],1);
							}else{
								$inf['ref'] = '--';
							}
							if(!isset($inf['ipReg'])){ $inf['ipReg'] = '--'; }	
							echo '
							<br />
							<b style="color:red"><u>Только для '.$rang.'</u></b><br />
							<i>День рождения: '.$inf['bithday'].'<br />';
							
							if($u->info['admin'] > 0) {
								  if( $inf['activ'] == 0 ) {
									 echo '<font color=green><b>'; 
								  }
								  
								  $mls = mysql_fetch_array(mysql_query('SELECT * FROM `users_rbk` WHERE `email` = "'.mysql_real_escape_string($inf['mail']).'" LIMIT 1'));
								  if(isset($mls['name'])) {
									 echo '<u>E-mail: '.$inf['mail'].'</u> (РАССЫЛКА)'; 
								  }else{
									 echo 'E-mail: '.$inf['mail'].'';
								  }
								  if( $inf['activ'] == 0 ) {
									  echo ' &nbsp; & &nbsp; '.$inf['send'].'<br />';
									 echo '</b></font>'; 
								  }else{
									 echo '<br />'; 
								  }
							}else{
								  if( $inf['activ'] == 0 ) {
									 echo '<font color=green><b>Персонаж активирован</b></font>';  
								  }else{
									 echo '<font color=red><b>Персонаж не активирован</b></font>'; 
								  }
								  echo '<br>';
							}
							if( $inf['no_ip'] != 0 ) {
								$inf['ipReg'] = $inf['no_ip'];
								$inf['ip'] = $inf['no_ip'];
							}
							echo 'Персонажа пригласили: '.$inf['ref'].'<br />
							Последний раз заходил в клуб: '.date('d.m.Y H:i',$inf['online']).'<br />
							'.$pr['login'].'IP при регистрации: <img width="16" height="16" title="'.$inf['country_reg'].'" src="/flat/16/'.$inf['country_reg'].'.png"> '.$inf['ipReg'].'<br />';
							if($inf['no_ip'] == '' || $u->info['admin']>0) {
								echo 'IP последние: <img width="16" height="16" title="'.$inf['country'].'" src="/flat/16/'.$inf['country'].'.png"> <b>'.$inf['ip'].'</b>';
								$auth = mysql_query('SELECT * FROM `logs_auth` WHERE `uid`="'.$inf['id'].'" AND `type`="1" ORDER by `id` DESC LIMIT 10');
								$country = '';
								while ($auth_w = mysql_fetch_array($auth)) {
									echo '<br /><img width="16" height="16" title="'.$auth_w['country'].'" src="/flat/16/'.$auth_w['country'].'.png"> '.$auth_w['ip'].' <small><b>('.date('d.m.Y H:i',$auth_w['time']).')</b></small>';
								}
							}else{
								echo 'IP последние: <img width="16" height="16" title="??" src="/flat/16/??.png"> <b>'.$inf['no_ip'].'</b>';
							}
							
							
function user_browser($agent) {
	preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info); // регулярное выражение, которое позволяет отпределить 90% браузеров
        list(,$browser,$version) = $browser_info; // получаем данные из массива в переменную
        if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera)) return 'Opera '.$opera[1]; // определение _очень_старых_ версий Оперы (до 8.50), при желании можно убрать
        if ($browser == 'MSIE') { // если браузер определён как IE
                preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie); // проверяем, не разработка ли это на основе IE
                if ($ie) return $ie[1].' based on IE '.$version; // если да, то возвращаем сообщение об этом
                return 'IE '.$version; // иначе просто возвращаем IE и номер версии
        }
        if ($browser == 'Firefox') { // если браузер определён как Firefox
                preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff); // проверяем, не разработка ли это на основе Firefox
                if ($ff) return $ff[1].' '.$ff[2]; // если да, то выводим номер и версию
        }
        if ($browser == 'Opera' && $version == '9.80') return 'Opera '.substr($agent,-5); // если браузер определён как Opera 9.80, берём версию Оперы из конца строки
        if ($browser == 'Version') return 'Safari '.$version; // определяем Сафари
        if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko'; // для неопознанных браузеров проверяем, если они на движке Gecko, и возращаем сообщение об этом
        return $browser.' '.$version; // для всех остальных возвращаем браузер и версию
}
							
							
							echo'
							<br />
							Браузер: <b style="color:#0E0F0E">'.user_browser($inf['dateEnter']).'</b><br />
							';
														
							if($inf['no_ip'] == '' || $u->info['admin']>0) {
								if($mults!='' && $inf['admin']==0){
									$mults = trim($mults,', ');
									echo 'Другие ники этого бойца:  '.$mults.'<br />';
								}
							}
							
							$referalos = '';
							$rfs = 0;
							
							$sp = mysql_query('SELECT `id`,`login`,`ip`,`ipreg`,`timereg`,`online`,`country_reg`,`country` FROM `users` WHERE `host_reg` = "'.$inf['id'].'" AND `activ` = "0" ORDER BY `timereg` DESC');
							while( $pl = mysql_fetch_array($sp) ) {
								$referalos .= '<br><img width="16" height="16" title="'.$pl['country'].'" src="/flat/16/'.$pl['country'].'.png"> Дата рег.: '.date('d.m.Y H:i',$pl['timereg']).' / Был тут: '.date('d.m.Y H:i',$pl['online']).' &nbsp; '.$u->microLogin($pl['id'],1).' <small>( '.$pl['ip'].' , '.$pl['ipreg'].' )</small>';
								$rfs++;
							}
							
							if( $referalos == '' ) {
								$referalos = '<i>Отсутствуют</i>';
							}
							echo '<hr><b>Рефералы бойца:</b>'.$referalos.'<hr>';
							if($u->info['admin']>0)
							{
								echo 'Доп. возможности: <small><a href="/info/'.$inf['id'].'&wipe&sd4='.$u->info['nextAct'].'">сбросить характеристики</a></small><br>';
								$on1 = mysql_fetch_array(mysql_query('SELECT `time_all`,`time_today` FROM `online` WHERE `uid` = "'.$inf['id'].'" LIMIT 1'));
								echo 'Время в онлайне (всего): '.timeOut($on1['time_all']).'<br>Время в онлайне (сегодня): '.timeOut($on1['time_today']).'<br>';
							}	
							if($inf['molch3']<time() && $inf['molch1'] > time())
							{
								echo '<small><a href="/info/'.$inf['id'].'&molchMax&sd4='.$u->info['nextAct'].'">Запретить персонажу отправлять сообщения с молчанкой</a></small><br>';
							}
							
							echo'	
							Опыт: '.$inf['exp'].' <br />
							Число неиспользованных UP-ов: '.$inf['ability'].' <br />
							<b>Кредитов:</b> '.$inf['money'].'<br><b>Зубов:</b> <small>'.$u->zuby($inf['money4']).'</small>';
							$bnk = ''; $bmn1 = 0; $bmn2 = 0;
							$sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$inf['id'].'"');
							while($pl = mysql_fetch_array($sp)) {
								if($pl['useNow'] > 0) {
									$bnk .= '<div style="padding:5px;border-bottom:1px solid #AEAEAE;background-color:#efefef;">';
								}else{
									$bnk .= '<div style="padding:5px;border-bottom:1px solid #AEAEAE">';
								}
								$bnk .= '&nbsp; &bull; <span style="display:inline-block;width:75px;"><small>№</small> '.$pl['id'].'</span>';
								$bnk .= '<span style="display:inline-block;width:100px;"><small>'.$pl['money1'].' кр.</small></span>';
								$bnk .= '<span style="display:inline-block;width:100px;"><small>'.$pl['money2'].' екр.</small></span>';
								if($u->info['admin'] > 0) {
									$bnk .= '<span style="display:inline-block;"><small>Пароль: '.htmlspecialchars($pl['pass'],NULL,'cp1251').'</small></span>';
								}
								if($pl['moneyBuy'] >= 0) {
								  $ds = '<b><i><u>Персонаж покупал еврокредиты</u></i></b>&nbsp;&nbsp;Сумма : '.$pl['moneyBuy'].'<br />';
								}
								$ds .= '<b><i><u>Заработано екр. (не покупал)</u></i></b>&nbsp;&nbsp;Сумма : '.$pl['shara'].'<br />';
								$bmn1 += $pl['money1'];
								$bmn2 += $pl['money2'];
								$bnk .= '</div>';
							}
							if($bnk != '') {
								echo '<br><b>Банковские счета:</b>'.$bnk.' &nbsp; <small><b>Деньги (Всего в банке):</b> &nbsp; '.$bmn1.' кр. &nbsp; &nbsp; '.$bmn2.' екр.</small><br/>'.$ds;
							}
							if($u->info['admin']>0 && $inf['admin']>0) {
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
        <td width="111" align=right valign=top >        
        <div style="float:right">
        <table cellspacing=0 cellpadding=0>
          <tr>
            <td width="111" style='text-align: center; padding-bottom: 18; '><!-- Зодиак -->
                  <img width="100" height="99" title="<? echo $name_zodiak; ?>" style="margin-bottom: 25; padding:5px;" src='http://img.xcombats.com/i/zodiac/<? echo $id_zodiak; ?>.gif' border=0><BR>
                  <BR>
              	  
                    <? if($inf['id'] == 0) { ?>
                    
                    <? }elseif($inf['align']>1 && $inf['align']<2) { ?>
                  <A href='http://paladins.<? echo $c['host']; ?>/' target='_blank'><img width="100" height="97" style="padding:5px;" src='http://img.xcombats.com/i/flag_light.gif' border=0></A><BR>
                  <A href='http://paladins.<? echo $c['host']; ?>/' target='_blank'><small>Орден Света</small></A>
                    <? }elseif($inf['align']>=3.01 && $inf['align']<=3.99) { ?>
                  <A target='_blank' href='http://tarmans.<? echo $c['host']; ?>/'><img width="100" height="97" style="padding:5px;" src='http://img.xcombats.com/i/flag_dark.gif' border=0></A><BR>
                  <A href='http://tarmans.<? echo $c['host']; ?>/' target='_blank'><small>Армада</small></A>
                    <? }elseif($inf['align']>=2 && $inf['align']<3) { ?>
                  <A target='_blank' href='http://chaos.<? echo $c['host']; ?>/'><img width="100" height="97" style="padding:5px;" src='http://img.xcombats.com/i/flag_haos.gif' border=0></A><BR>
                  <A href='http://chaos.<? echo $c['host']; ?>/' target='_blank'><small>Цитадель Хаоса</small></A>
                    <? }else{ ?>
                  <A target='_blank' href='http://xcombats.com/news/'> <img width="100" height="97" style="padding:5px;" src='http://img.xcombats.com/i/flag_gray.gif'></a><BR>
                  <A href='http://xcombats.com/news/' target='_blank'><small>Новости</small></A><br>
                    <? } ?>
            </td>
          </tr>
        </table></div></td>
      </table>
      </td>
  </tr>
</table>
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
<hr style="border-color:#999999;">
<? if($inf['info_delete']==0){ ?><H3 align="center" style="color:#8f0000">Анкетные данные</H3><? } ?>
<?
if($inf['name'] != '') {
?>
<b style="color:#444">Имя:</b> <? echo $inf['name']; ?><BR><? } ?>
<b style="color:#444">Пол:</b>
<? $sex[0] = 'Мужской'; $sex[1] = 'Женский'; echo $sex[$inf['sex']]; ?><BR>
<? if($inf['city_real']!=''){ ?>
<b style="color:#444">Город:</b> <? echo $inf['city_real']; ?><BR><? } ?>
<? if($inf['icq']>0 && $inf['icq_hide']==0){ echo 'ICQ: '.$inf['icq'].' <img style="display:inline-block;vertical-align:bottom;" height="13" width="13" title="Статус ICQ" src="http://web.icq.com/whitepages/online?img=26&icq='.$inf['icq'].'"><br />'; } ?>
<? if($inf['skype']!='' && $inf['skype_hide']==0){ echo 'Skype: '.$inf['skype'].' <img height="13" width="13" style="display:inline-block;vertical-align:bottom;" title="Статус Skype" src="http://mystatus.skype.com/smallicon/'.$inf['skype'].'"><br />'; } ?>
<? if(isset($inf['homepage']) && $inf['homepage']!='' && $inf['level']>4) { 
$url = ((substr($inf['homepage'],0,4)=='http'?"":"http://").$inf['homepage']);
?>
<b style="color:#444">Домашняя страница:</b> <A HREF="<? echo $url; ?>" target="_blank"><? echo $url; ?></A><BR> <? } ?>
<? if($inf['deviz']!=''){ ?>
<b style="color:#444">Девиз:</b> <code><? echo $inf['deviz']; ?></code><BR>  <? } ?>
<? if($inf['hobby']!=''){ ?>  
<b style="color:#444">Увлечения / хобби:</b><BR>
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
<div align="right"><br>
Старый БК &copy; <?=date('Y')?>, «www.xcombats.com» &nbsp; &nbsp;</div>
<br /><br />
</body>
</html>
