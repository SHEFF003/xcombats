<?php

function er($e)
{
	 global $c;
	 die('<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><meta http-equiv="Content-Language" content="ru"><TITLE>Произошла ошибка</TITLE></HEAD><BODY text="#FFFFFF"><p><font color=black>Произошла ошибка: <pre>'.$e.'</pre><b><p><a href="http://'.$c[0].'/">Назад</b></a><HR><p align="right">(c) <a href="http://'.$c[0].'/">'.$c[1].'</a></p></body></html>');
}

session_start();

define('GAME',true);
function GetRealIp()
{
 if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
 {
   $ip=$_SERVER['HTTP_CLIENT_IP'];
 }
 elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
 {
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }
 else
 {
   $ip=$_SERVER['REMOTE_ADDR'];
 }
 return $ip;
}
define('IP',GetRealIp());

include_once('_incl_data/__config.php');
include_once('_incl_data/class/__db_connect.php');
include_once('_incl_data/class/__user.php');
include_once('_incl_data/class/__filter_class.php');
include_once('_incl_data/class/__chat_class.php');

if(isset($_GET['showcode']))
{
	include('show_reg_img/security.php');
	die();
}

if($u->info['joinIP']==1 && $u->info['ip']!=IP)
{
	er('#Пожалуйста авторизируйтесь с главной страницы');
}elseif(isset($_GET['exit']))
{
	setcookie('login','',time()-60*60*24*30,'',$c['host']);
	setcookie('pass','',time()-60*60*24*30,'',$c['host']);
	setcookie('login','',time()-60*60*24*30);
	setcookie('pass','',time()-60*60*24*30);
	mysql_query('UPDATE `users` SET `online` = "'.(time()-520).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	die('<script>top.location = "http://'.$c['host'].'/";</script>');
}elseif(!isset($u->info['id']))
{
	
	/*setcookie('login','',time()-60*60*24*30,'',$c['host']);
	setcookie('pass','',time()-60*60*24*30,'',$c['host']);
	setcookie('login','',time()-60*60*24*30);
	setcookie('pass','',time()-60*60*24*30);*/
	
	er('Возникла проблема с определением id персонажа<br>Авторизируйтесь с главной страницы.');
}

if($u->info['online'] < time()-60)
{
	$filter->setOnline($u->info['online'],$u->info['id'],0);
	mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");	
}

$u->stats = $u->getStats($u->info['id'],0);

if($u->info['activ']>0) {
	include('activnew.php');
	die();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Старый Бойцовский Клуб</title>
<meta name="description" content="<?=$c['desc']?>" />
<meta name="keywords" content="<?=$c['keys']?>" />
<meta name="author" content="<?=$c['title3']?>"/>
<noscript><meta http-equiv="refresh" content="0; URL=/badbrowser.html"></noscript>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/clu0b.css" />
<link rel="stylesheet" type="text/css" href="css/windows.css" />
<link rel="stylesheet" type="text/css" href="css/hack.css" />
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script>
var c = { 
	noEr:0,
	noErTmr:0,
	url:'<?=$c['host']?>',
	img:'img.xcombats.com',
	uid:<?=(0+$u->info['id'])?>,
	login:'<?=$u->info['login']?>',
	city:'<?=$u->info['city']?>',
	lvl:<?=$u->info['level']?>,
	rnd:'<?=$code?>',
	filter:0,
	time:<?=time()?>,
	pl:0,
	align:<?=$u->info['align']?>,
	clan:<?=$u->info['clan']?>,
	admin:<?=$u->info['admin']?>,
	sound:0,
	money:<?=$u->info['money']?>
}, sd4key = "<?=$u->info['nextAct']?>", lafstReg = {},enterUse = 0;

function ctest(city) {
	if(city != c['city']) {
		top.location = '/bk';
	}
}

function testKey(event)
{
	if(event.keyCode==10 || event.keyCode==13)
	{
		if(top.enterUse == 0)
		{
			chat.subSend();
			top.enterUse = 1;
			setTimeout('top.enterUse = 0',1000);
		}
	}
}
setInterval('c.time++',1000);
</script>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
$.ajaxSetup({cache: false});
$(window).error(function(){
  return true;
});
var iusrno = {};
function ignoreUser(u) {
	if( iusrno[u] == undefined || iusrno[u] == 0 ) {
		//top.iusrno[u] = 1;
		$('#main').attr({'src':'main.php?friends=1&ignore=' + u + ''});
	}else{
		//top.iusrno[u] = 0;
		$('#main').attr({'src':'main.php?friends=1&ignore=' + u + ''});
	}
}
</script>
<script type="text/javascript" src="js/jqueryrotate.js"></script>
<script type="text/javascript" src="js/jquery.zclip.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/title.js"></script>
<script type="text/javascript" src="js/gameEngine.js?<?=time()?>"></script>
<script type="text/javascript" src="js/interface.js"></script>
<script type="text/javascript" src="js/dataCenter.js"></script>
<script type="text/javascript" src="js/onlineList.js"></script>
<script type="text/javascript" src="js/hpregen.js"></script>
<script type="text/javascript" src="js/jquery-fireHint.js"></script>
<?
//
$fpi = mysql_fetch_array(mysql_query('SELECT * FROM `fastpanel` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
if(isset($fpi['id'])) {
	$i = 0;
	$fpv = explode('|',$fpi['data']);
	$fph = '';
	while( $i <= 10 ) {
		$id = $fpv[$i];
		$id = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `id` = "'.mysql_real_escape_string($id).'" AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
		if(isset($id['id'])) {
			//есть итем
			$idm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($id['item_id']).'" LIMIT 1'));
			$idd = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($idm['id']).'" LIMIT 1'));
			//$po = ;
			$fph .= 'top.addfastpanel(\''.$idm['id'].'\',\''.$idm['name'].'\',\''.$idm['type'].'\',\''.$id['1price'].'\',\''.$id['2price'].'\',\''.$u->city_name[$id['maidin']].'\',\''.$idm['img'].'\',\''.$id['item_id'].'\',\''.$idm['iznosNOW'].'\',\''.$idm['iznosMAX'].'\',\'1\',\'1\',\'1\',\'1\');';
		}else{
			//пусто
		}
		$i++;
	}
	echo '<script>'.$fph.'</script>';
}
//
if(  !isset($_COOKIE['d1c']) ) {
	include('_incl_data/class/mobile.php');
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$_COOKIE['d1c'] = $deviceType;
	setcookie('d1c',$deviceType,(time()+864000));
}else{
	$deviceType = $_COOKIE['d1c'];
}

if( $deviceType == 'tablet' || $deviceType == 'phone' ) {
	echo '<script type="text/javascript" src="js/jquery.nicescroll.js"></script>';
?>
<style type="text/css">
#touchmain {
	padding: 0px;
	border: 0;
	overflow: auto;
	margin: 0px;
}
</style>

<script>
  $(document).ready(function() { 
    $("#touchmain").niceScroll("#main",{autohidemode:false,boxzoom:false});	
  });
</script>
<?
}
?>
	<script>
	function chatHello() {
		chat.sendMsg(["new","<?=time()?>","1","","<?=$u->info['login']?>","global:<b><font color=red>XCombats</font></b>: Добро пожаловать в игру! следите за новостями :ok:","Black","1","1","0"]);
	}
	
    </script>
	<style type="text/css">
		/* Additional classes examples */
		.woman a {
			color:#C33;
		}
		.woman a:hover {
			color:#ff0000;
		}
		img { vertical-align:bottom; }
		#tgf_loadingLine {
			height:18px;
			width:100%;
			color:#776b4a;
			background-color:#ddd5bf;
			position:relative;
		}
		.tfpgs {
			padding:5px;
			background-color:#d4cbb4;
			border-bottom:1px solid #988e73;
			border-top:1px solid #eae3d0;
			cursor:default;
			text-align:center;
		}
		.tgf_msg0 { 
			padding:5px;
			background-color:#c6b893;
			border-bottom:1px solid #988e73;
			border-top:1px solid #eae3d0;
			cursor:default;
		}
		.tgf_msg1 { 
			padding:5px;
			background-color:#d4cbb4;
			border-bottom:1px solid #988e73;
			border-top:1px solid #eae3d0;
			cursor:default;
		}
		.tgf_msgt {
			color:#988e73;
			padding-left:2px;
			padding-right:2px;
			border-right:1px solid #b1a993;
		}
		.tf_btn1 {
			background-color:#ddd5bf;
			padding-left:10px;
			padding-right:10px;
			padding-bottom:3px;
			padding-top:3px;
			margin:1px;
			color:#988e73;
			cursor:pointer;
		    -moz-border-radius: 4px;
		    -webkit-border-radius: 4px;
		    border-radius: 4px;
		}
		
		.tf_btn1:hover {
			background-color:#b7ae96;
			color:#ddd5bf;
			cursor:pointer;
		}
		
		.tf_btn11 {
			background-color:#988e73;
			padding-left:10px;
			padding-right:10px;
			padding-bottom:3px;
			padding-top:3px;
			margin:1px;
			color:#ddd5bf;
			cursor:pointer;
		    -moz-border-radius: 4px;
		    -webkit-border-radius: 4px;
		    border-radius: 4px;
		}
		.qel0 {
			dispaly:none;
			position:absolute;
			z-index:100000;
			border:4px solid #f5cc50;
			border-top-left-radius: 4px;
			border-top-right-radius: 4px;
			border-bottom-right-radius: 4px;
			border-bottom-left-radius: 4px;
		}
	</style>
</head>

<body onLoad="bodyLoaded();chatHello();">
<div style="display:none" class="qel0" id="qel0"></div>
<noscript><center>В вашем браузере отсутствует поодержка <b>javascript</b></center></noscript>
<style>
/* цвета команд */
.CSSteam0	{ font-weight: bold; cursor:pointer; }
.CSSteam1	{ font-weight: bold; color: #6666CC; cursor:pointer; }
.CSSteam2	{ font-weight: bold; color: #B06A00; cursor:pointer; }
.CSSteam3 	{ font-weight: bold; color: #269088; cursor:pointer; }
.CSSteam4 	{ font-weight: bold; color: #A0AF20; cursor:pointer; }
.CSSteam5 	{ font-weight: bold; color: #0F79D3; cursor:pointer; }
.CSSteam6 	{ font-weight: bold; color: #D85E23; cursor:pointer; }
.CSSteam7 	{ font-weight: bold; color: #5C832F; cursor:pointer; }
.CSSteam8 	{ font-weight: bold; color: #842B61; cursor:pointer; }
.CSSteam9 	{ font-weight: bold; color: navy; cursor:pointer; }
.CSSvs 		{ font-weight: bold; }
.buttons:hover { background-color:#EFEFEF; }
.buttons:active { color:#777777; }
.buttons { background-color:#E9E9E9; }
.menutop2{color:#003366;} .menutop2:hover{
	color:#446B93;
}
.klan { font-weight:bold; color: green; background-color: #99FFCC;}
.redColor {
	color: #FF0000;
	font-weight: bold;
}
.borderWhite {
	border: 1px solid #f2f0f0;
}
.date21	{
	font-family: Courier;
	font-size: 8pt;
	text-decoration:underline;
	font-weight:normal;
	color: #007000;
	background-color: #00FFAA
}


.zoneCh_no {
	float:left;
	overflow:hidden;
	height: 18px;
	width: 18px;
}

.inpBtl {
	color: #000000;
	text-decoration: none;
	background-color: #ECE9D8;
	border: 1px solid #000000;    
}

.zoneCh_yes {
	float:left;
	overflow:hidden;
	height: 18px;
	width: 18px;
	background-color: #A9AFB1;
}
	
body {
	background-color: #e8e8e8;
}
.st1222 {
	font-size: 18px;
	color: #990000;
	font-weight: bold;
}
.crop {
	float:left;
	overflow:hidden;
	height: 18px;
	width: 18px;
}

.radio_off {
    margin-left:0px;
}

.radio_on {
    margin-left:-18px;
}

.battle_hod_style {
    border-bottom-width: 1px;
    border-bottom-style: solid;
    border-bottom-color: #AEAEAE;
}
.zbtn1l{	width:9px;	height:18px;	background: url(http://xcombats.com/tab.png) 0px 0px repeat-x;}
.zbtn1r {	width:9px;	height:18px;	background: url(http://xcombats.com/tab.png) -18px 0px repeat-x;}
.zbtn1r2 {	width:9px;	height:18px;	background: url(http://xcombats.com/tab.png) 18px 0px repeat-x;}
.zbtn2l{	width:9px;	height:18px;	background: url(http://xcombats.com/tab.png) -36px 0px repeat-x;}
.zbtn2r {	width:9px;	height:18px;	background: url(http://xcombats.com/tab.png) -54px 0px repeat-x;}
.zbtn2r2 {	width:9px;	height:18px;	background: url(http://xcombats.com/tab.png) -90px 0px repeat-x;}
.zbtn2r3 {	width:9px;	height:18px;	background: url(http://xcombats.com/tab.png) 54px 0px repeat-x;}
.zbtn1c{	background-color: #808080;	border-top-width: 1px;	border-bottom-width: 1px;	border-top-style: solid;	border-bottom-style: solid;	border-top-color: #000000;	border-bottom-color: #000000;	color: #FFFFFF;	cursor:default;	padding-left:5px;	padding-right:5px;	FONT-FAMILY: Verdana, Arial, Helvetica, Tahoma, sans-serif;}
.zbtn2c{
	background-color: #D5D2C9;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #000000;
	color: #000000;
	cursor:default;
	padding-left:5px;
	padding-right:5px;
	FONT-FAMILY: Verdana, Arial, Helvetica, Tahoma, sans-serif;
	font-weight: bold;
}
</style>
<?
/*$yes = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "user_yes" LIMIT 1',1);
if(!isset($yes['id']))
{
	//Пользовательское соглашение
	if(isset($_GET['yes']))
	{
		//согласен
		$u->addAction(time(),'user_yes','');
		header('location: http://'.$c[$u->info['city']].'/bk');
		die();
	}
	/*echo '<table width="100%" height="100%" style="position:absolute;z-index:100000;background-color:#d6d6d6;color:#333333" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle"><table width="700" border="0" cellspacing="0" cellpadding="0"><tr><td>
<div align="left">
<b>Наверное Вы удивлены что это за текст?<br>Пожалуйста уделите одну минуту Вашего драгоценного времени и прочитайте его:</b><br><br>
&nbsp; Добрый день, вечер или даже ночь! Вы наверное играли в Бойцовский Клуб? Да, точно играли, по крайней мере в проекты которые
себя называют "Лучший Клон БК" и т.д., а по факту, простите, это хуйня из под коня. Сейчас много проектов
которые создаются любителями, которые пользуются готовыми движками. Скачать и установить такой сайт
может любой школьник, даже безграмотный! Наш проект не такой! Постоянное развитие, улучшение, экшен,
да и к тому-же уникальность нашего движка не сможет оспорить ни один человек. Надеемся что Вам понравится
наш проект и Вы будете играть здесь до последнего дня проекта!<br>
&nbsp; Соглашаясь с этим текстом Вы отказывается от всего этого дерьма в интернете и вступаете в круг людей которые
за качество и отсутствие однообразия! Вас ждет множество удивительных вещей, в то время как другие потакают некачественному продукту.
<br><br><center><a href="club.php?yes='.$code.'">Я полностью согласен с написанным выше текстом</a></center><br><br>Если по каким-либо причинам Вы не согласны, то пожалуйста закройте наш сайт и более не открывайте! Спасибо! ;-)
</div></td></tr></table></td></tr></table>';*/
//}
?>
<script>
if(window.top !== window.self)
{
	document.write = "";
	window.top.location = window.self.location;
	setTimeout(function(){ document.body.innerHTML='Ошибка доступа.'; },500);
	window.self.onload=function(evt){
	document.body.innerHTML='Ошибка доступа.';};
}
function cc(el) {
	$(window).resize(function(){
		$(el).css({
				position:'absolute',
				left: ($(document).width() - $(el).outerWidth())/2,
				top: ($(document).height() - $(el).outerHeight())/2
		});
	});		
	$(window).resize();
}
</script>
<? 
	if($u->info['bithday'] == '01.01.1800') {
?>
<script>
function startRegistration() {
	//if( goodread >= 9 ) {
		$.post( 'reg.php' , {
			'ajax_reg':true,
			'reg_login':$('#reg_login').val(),
			'reg_mail':$('#reg_mail').val(),
			'reg_dd':$('#reg_dd').val(),
			'reg_mm':$('#reg_mm').val(),
			'reg_yy':$('#reg_yy').val(),
			'reg_sex':$('#reg_sex').val(),
			'reg_pass':$('#reg_pass').val(),
			'reg_pass2':$('#reg_pass2').val(),
			'mail_post':$('#mail_post').attr('checked')
		} , function(data) { 
			$('#errorreg').html( data );
		});
	/*}else{
		alert('Ознакомьтесь с "Первыми шагами новичка" справа! \n Эта информация будет полезна Вам! \n Вы прочитали '+goodread+' из 9 страниц!');
	}*/
}
</script>
<style>
.proza {
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=87); /* IE 5.5+*/
	-moz-opacity: 0.87; /* Mozilla 1.6 и ниже */
	-khtml-opacity: 0.87; /* Konqueror 3.1, Safari 1.1 */
	opacity: 0.87; /* CSS3 - Mozilla 1.7b +, Firefox 0.9 +, Safari 1.2+, Opera 9+ */
}
.nobtn12 {
	border:0;
	bottom:80px;
	left:225px;
	position:absolute;
}
.nobtn12:active {
	bottom:79px;
}
.inpreg1 {
	padding:2px;
	font-size:14px;
	color:#003144;
	background-color:#f6f4ec;
	border:1px solid #d1cdb8;
	width:178px;
}
.inpreg2 {
	font-size:12px;
	color:#003144;
	background-color:#f6f4ec;
	border:1px solid #d1cdb8;
}
#errorreg {
	position:absolute;
	width:680px;
	top:0;
	left:0;
	text-align:center;
	color:red;
	font-weight:bold;
}
</style>
<script>
	cc('#regblock');
</script>
<?
	}elseif($u->info['active']!='' && $u->info['mail']=='No E-mail') {
		if($error!='') {
			echo '<script>alert("'.$error.'");</script>';
		}	
?>
<!-- ACTIVE -->
<?
}
?>
<style>
#qsst {
	position:absolute;
	z-index:10000000;
	cursor:default;
	display:none;
}
#onbon {
	position:absolute;
	z-index:100;
	cursor:default;
	display:none;
	bottom:30px;
	left:18px;
}
#mini_qsst {
	position:absolute;
	z-index:100;
	cursor:default;
	display:none;
	bottom:10px;
	right:18px;
}
</style>
<div class="radioblock" id="rdo" style="display:none">
	radio loading...
</div>
<audio style="display:none;" id="radio_audio" src=""></audio>
<div id="qsst"></div>
<div id="ttl" class="ttl_css" style="display:none;z-index:1111;" /></div>
<div id="nfml" style="display:none;position:absolute;" /></div>
<div id="persmenu" style="display:none;z-index:1110;" /></div>
<div id="windows" style="position:absolute;z-index:1101;"></div>
<div id="wupbox" onmouseup="win.WstopDrag()" onmousemove="win.WmoveDrag(event)" onselectstart="return false"></div>
<div id="chconfig">
<center><b>Настройки чата</b></center>
<img title="Эпическая линия (o_O)" src="http://<?=$c['img'];?>/1x1.gif" class="eLine"><br>
Скорость обновления: <SELECT id="chcf0"><OPTION value='-1'>никогда</OPTION><OPTION value='1'>15 сек.</OPTION><OPTION selected value='2'>30 сек.</OPTION><OPTION value='3'>1 мин.</OPTION><OPTION value='4'>5 мин.</OPTION></SELECT><br>
<div>Сортировка списка онлайн: <SELECT id="chcf8"><OPTION value='0' selected>По логину</OPTION><OPTION value='1'>По уровню</OPTION><OPTION value='2'>По склоности</OPTION><OPTION value='3'>По клану</OPTION></SELECT>
<input name="chcf9" type="checkbox" id="chcf9" value="1"><small>По убыванию</small></div>
<? if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4) || $u->info['align']==50) { ?>
<div><input name="chcf11" type="checkbox" id="chcf11" value="1"> Глобальный чат</div>
<? } ?>
<div><input name="chcf12" type="checkbox" id="chcf12" value="1"> Экономия трафика</div>
<div style="display:<? if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)) { echo ''; }else{ echo 'none;'; } ?>"><input name="chcf7" type="checkbox" id="chcf7" value="1"> <span title="Получать сообщения от персонажей на которых наложено заклятие молчания">Сообщения с молчанкой</span></div>
<img title="Эпическая линия (o_O)" src="http://<?=$c['img'];?>/1x1.gif" class="eLine">
<div>&nbsp; &nbsp;&nbsp; <span><a href="javascript:void(0)" onclick="chat.ignorListOpen();chconf();">Список игнорируемых</a></span></div>
</div>
<!-- <div id="counters"></div> -->
<!-- ресайзы -->
<div id="actionDiv" style="position:absolute;"></div>
<div id="reline1" onselectstart="return false">
	<img src="http://img.xcombats.com/1x1.gif" width="9" height="4" style="float:left; display:block; position:absolute; background-image:url(http://img.xcombats.com/i/lite/_top_24.gif);">
	<img src="http://img.xcombats.com/1x1.gif" width="10" height="4" style="float:right; display:block; background-image:url(http://img.xcombats.com/i/lite/_top_28.gif);">
</div>
<div id="reline2" onselectstart="return false"></div>
<!-- ресайзы -->
<div id="upbox" onselectstart="return false"></div>
    <div style="position:absolute; top:0; left:0; height:37px; width:100%;" onselectstart="return false">
    <div title="Новая почта" style="display:none; position:absolute; left: 198px; top: 13px; width:24px; height:15px; background-image:url(http://img.xcombats.com/i/mail2.gif);" class="postdiv" id="postdiv"></div>
    <div style="background: url(http://img.xcombats.com/i/lite/<?=$u->info['city']?>/top_lite_cap_11.gif) repeat-x bottom; ">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: url(http://img.xcombats.com/i/lite/top_lite_cap_03.gif) repeat-x top; ">
        <tr>
          <td align="left"><img src="http://img.xcombats.com/i/lite/<?=$u->info['city']?>/top_lite_cap_01.gif" height="14" class="db" /></td>
          <td align="right" class="main_text" style="position: relative"><table cellspacing="0" cellpadding="0" border="0" width="460">
            <tr valign="bottom" align="center">
              <td width="31" height="14"><img class="db" height="14" src="http://img.xcombats.com/i/lite/mennu112_06_lite.gif" width="31" /></td>
              <td align="center"><table height="14" cellspacing="0" cellpadding="0" width="100%" background="http://img.xcombats.com/i/lite/mennu112_06.gif" border="0">
                <tr align="middle">
                  <td id="el1" class="main_text" onClick="this.style.backgroundColor='#404040'; this.style.color='#FFFFFF'; showtable('1');" align="center">Знания</td>
                  <td width="1"><img class="db" height="11" src="http://img.xcombats.com/i/lite/mennu112_09.gif" width="1" /></td>
                  <td id="el2" class="main_text" onClick="this.style.backgroundColor='#404040'; this.style.color='#FFFFFF'; showtable('2');" align="center">Общение</td>
                  <td width="1"><img class="db" height="11" src="http://img.xcombats.com/i/lite/mennu112_09.gif" width="1" /></td>
                  <td id="el3" class="main_text" onClick="this.style.backgroundColor='#404040'; this.style.color='#FFFFFF'; showtable('3');" align="center">Безопасность</td>
                  <td width="1"><img class="db" height="11" src="http://img.xcombats.com/i/lite/mennu112_09.gif" width="1" /></td>
                  <td id="el4" class="main_text" onClick="this.style.backgroundColor='#404040'; this.style.color='#FFFFFF'; showtable('4');" style="background:#404040; color:#FFFFFF;" align="center">Персонаж</td>
                  <td width="1"><img class="db" height="11" src="http://img.xcombats.com/i/lite/mennu112_09.gif" width="1" /></td>
                  <td id="el5" class="main_text" onClick="if(confirm('Выйти из игры?')){ top.location = '/bk?exit&rnd=<?=$code?>'; }"  align="center">Выход&nbsp;</td>
                </tr>
              </table></td>
              <td width="38"><img class="db" height="14" src="http://img.xcombats.com/i/lite/mennu112_04_lite.gif" width="37" /></td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td align="left"><img style="display:block; float:left;" src="http://img.xcombats.com/i/lite/top_lite_cap_07.gif" width="15" height="17" /><img class="db" src="http://img.xcombats.com/i/lite/<?=$u->info['city']?>/top_lite_cap_08.gif" height="17" /></td>
          <td align="right">
          <table cellspacing="0" cellpadding="0" width="460" style="background-image:url(http://img.xcombats.com/i/lite/top_lite_cap_15.gif);" border="0">
            <tr>
              <td align="right" class="menutop"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="20"><img class="db" src="http://img.xcombats.com/i/lite/top_lite_13.gif" width="20" height="17" /></td>
                    <td align="right" height="15" valign="top" background="http://img.xcombats.com/i/lite/top_lite_low_15.gif" style="font-size:10px;">
                        <span style="display:none;" id="menu1"> <a href="http://xcombats.com/lib/zakon/" target="_blank" class="menutop">&nbsp;Законы&nbsp;</a> | <a href="http://xcombats.com/lib/polzovatelskoe-soglashenie/" target="_blank" class="menutop">&nbsp;Соглашения&nbsp;</a>&nbsp;</span>
                        <span style="display:none;" id="menu2"><a onClick="top.telegraf(); return false" href="javascript:void(0)" target="_blank" class="menutop">&nbsp;Телеграммы&nbsp;</a> | <a href="http://xcombats.com/lib/zakon/" target="_blank" class="menutop">&nbsp;Библиотека&nbsp;</a> | <a href="http://xcombats.com/news/" target="_blank" class="menutop">&nbsp;События&nbsp;</a> | <a href="http://xcombats.com/forum" target="_blank" class="menutop">&nbsp;Форум&nbsp;</a> |  <a href="http://xcombats.com/rating" target="_blank" class="menutop">&nbsp;Рейтинг&nbsp;</a>&nbsp;</span>
                        <span style="display:none;" id="menu3"> <a href="main.php?act_sec=1" target="main" class="menutop">&nbsp;Отчеты&nbsp;</a> | <a href="http://xcombats.com/lib/zakon/" target="_blank" class="menutop">&nbsp;Правила&nbsp;</a> | <a href="http://xcombats.com/main.php?security&rnd=<?=$c[9]?>" target="main" class="menutop">&nbsp;Настройки&nbsp;</a> | <a href="main.php?security&rnd=<?=$c[9]?>" target="main" class="menutop">&nbsp;Смена пароля&nbsp;</a>&nbsp;</span>
                        <span style="display:;" id="menu4"><a href="/main.php?referals=1&rn=<?=$c[9]?>" target="main" class="menutop" style="color:red">Заработок</a> | <a href="/main.php?inv=1&rn=<?=$c[9]?>" target="main" class="menutop">&nbsp;Инвентарь&nbsp;</a> | <a href="/main.php?skills=1&side=5" target="main" class="menutop">&nbsp;Умения&nbsp;</a> | <a href="/main.php?act_trf=1" target="main" class="menutop">&nbsp;Отчеты&nbsp;</a> | <a href="/main.php?zayvka=1" target="main" class="menutop">&nbsp;Поединки&nbsp;</a><!-- | <a href="/seasons.php" target="main" class="menutop">Сезоны</a>--> | <a href="/main.php?anketa=1" target="main" class="menutop">&nbsp;Анкета&nbsp;</a>&nbsp;</span>
                    </td>
                    <td width="22"><img class="db" src="http://img.xcombats.com/i/lite/top_lite_18.gif" width="22" height="17" /></td>
                  </tr>             
                </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15" height="6"><img class="db" src="http://img.xcombats.com/i/lite/_lit_20.gif" width="15" height="6" /></td>
        <td background="http://img.xcombats.com/i/lite/_top_20s.gif"><img class="db" src="http://img.xcombats.com/i/lite/<?=$u->info['city']?>/cap_lit_21.gif" width="79" height="6" /></td>
        <td width="24" height="6"><img class="db" src="http://img.xcombats.com/i/lite/_lit_27.gif" width="24" height="6" /></td>
      </tr>
    </table>
    <!-- -->
</div>
<table id="globalMain" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="31" width="9" bgcolor="#D6D6D6"></td>
    <td height="31" bgcolor="#D6D6D6">&nbsp;</td>
    <td height="31" width="12" bgcolor="#D6D6D6"></td>
  </tr>
  <tr>
    <td bgcolor="#D6D6D6" background="http://img.xcombats.com/i/lite/_top_24.gif"></td>
    <td valign="top" bgcolor="#e2e0e0" id="main_td">
    <div id="touchmain" style="margin-top:3px;">
    	<iframe id="main" name="main" src="main.php" frameborder="0" style="display:block;padding-top:0px;padding:0;margin:0;width:100%;border:0;" scrolling="auto"></iframe>
    </div>
    </td>
    <td bgcolor="#D6D6D6" background="http://img.xcombats.com/i/lite/_top_28.gif"></td>
  </tr>
  <tr>
    <td bgcolor="#D6D6D6" background="http://img.xcombats.com/i/lite/_top_24.gif"></td>
    <td id="chat" valign="top" height="40%" bgcolor="#eeeeee">
    <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #CCCCCC">
      <tr>
        <td valign="top" id="chat_block" style="position:relative;display:block;border-top:1px solid #808080">
            <div id="mini_qsst" onClick="top.qn_slk()" style="cursor:pointer"></div>
            <div id="onbon"></div>
            <div id="chat_menus" unselectable="on" onselectstart="return false;" style="display:;position:absolute; right:0px; top:3px; padding-right:20px; height:18px; text-align:right; white-space:nowrap;">
              	<!-- -->
                <table border="0" style="margin-top:-3px;" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <div id="chat_menu" style="text-align:right; white-space:nowrap;">
                            <? /*<div id="addbs" style="float:left;display:;"><a href="javascript:top.add_cb(0,'new',0,0);"><img src="addmn.gif" width="16" height="16" title="Добавить новую вкладку" /></a></div>*/ ?>
                        </div>
                      </td>
                      <td style="display:none;" id="scroll_none" width="3"></td>
                    </tr>
                </table>
                <!-- -->
              </div>

        <div id="ttSmiles" onselectstart="return false" style="display:none;z-index:1100;" />
        	<div id="smilesDiv" style="margin:5px;">Загрузка смайликов</div>
            <div align="left"><button class="btnnew" style="width:350px;" onClick="chat.lookSmiles()">Закрыть</button></div>
        </div>
        
          <div id="chat_list" style="cursor:default;">
                <div id="canals">
                	<div id="canal5"></div>
                    <div id="canal6" style="display:none;"></div>
                </div>
          </div>        
        </td>
        <td width="240" valign="top" bgcolor="#faf2f2" style="border-left:2px solid #CCCCCC;border-top:1px solid #808080" id="online">
        <div id="online_list" style="cursor:default;">
          <div align="center" style="margin-top:5px;">
          <button class="btnnew" id="robtn" onClick="chat.reflesh()">Обновить</button>
          </div>
          <font class="db" style="padding:0px 0 8px 0;font-size: 10pt; color:#8f0000;"><b id="roomName"></b></font>
          <div id="onlist"></div>
          <div style="border-top:#cac2c2 solid 1px;padding:5px;margin-top:5px;">
              <div><label><input type="checkbox" value="1" <? if( $u->info['level'] < 8 ) { ?>checked<? } ?> id="autoRefOnline">Обновлять автомат.</label></div>
              <? if( $u->info['admin'] > 0 ) { ?>
              <div><label><input name="chcf10" type="checkbox" id="chcf10" <? if( $u->info['level'] < 8 ) { ?>checked<? } ?> value="0">Показать всех игроков</label></div>
          	  <? } ?>
          </div>
        </div>
      </td>
      </tr>
    </table>
    </td>
    <td bgcolor="#D6D6D6" background="http://img.xcombats.com/i/lite/_top_28.gif"></td>
  </tr>
  <tr>
    <td height="30" valign="bottom"><img class="db" src="http://img.xcombats.com/i/lite/bkf_l_r1_02.gif" width="9" height="30"></td>
    <td height="30" bgcolor="#E9E9E9" background="http://img.xcombats.com/i/buttons/chat_bg.gif">
    <table width="100%" height="26" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="30"><img onclick="radioClick();" id="radiobtn1" class="db radiobtn" src="http://img.xcombats.com/1x1.gif" width="30" height="30" title="Радио (Выключено)"></td>
        <td style="display:none;" width="100" align="center" id="trader"><div id="trader" style="border:1px solid #CCCCCC;padding:2px; margin-left:-2px; width:90%;" class="klan"><small id="moneyGM"><b>Торговый чат</b></small></div></td>
        <td><input type="hidden" name="trader" id="trader_val" value="0"><input onmouseup="top.chat.inObj=undefined;" type="text" name="textmsg" id="textmsg" maxlength="240" onKeyPress="top.testKey(event)" style="width:100%;font-size:9pt;margin-bottom:2px;" /></td>
        <td width="6">&nbsp;</td>
        <td width="30"><img onClick="chat.subSend();" src="http://img.xcombats.com/1x1.gif" class="db cp chatBtn2_1"></td>
        <td width="5">
        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="Sound" align="middle">
			<param name="allowScriptAccess" value="always" />
			<param name="movie" value="flash/Sound2.1.swf" />
			<param name="quality" value="high" />
			<param name="scale" value="noscale" />
			<param name="wmode" value="transparent" />
			<embed src="flash/Sound2.1.swf" quality="high" scale="noscale" wmode="transparent" width="1" height="1" name="Sound" id="Sound2" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
        </object>
        </td>
        <td width="30"><img onClick="chat.clear();" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn3.gif"></td>
        <td width="30"><img onClick="chat.filterMsg();" id="chbtn1" src="http://img.xcombats.com/1x1.gif" class="db cp chatBtn1_1"></td>
        <td width="30"><img onClick="chat.systemMsg();" id="chbtn4" src="http://img.xcombats.com/1x1.gif" class="db cp chatBtn4_<? if(isset($_COOKIE['citySys']) && $_COOKIE['citySys']==1){ echo 2; }else{ echo 1; } ?>"></td>
        <td width="30"><img id="chbtn6" onClick="chat.translitChat()" src="http://img.xcombats.com/1x1.gif" class="db cp chatBtn6_1"></td>
        <td width="30"><img id="chbtn7" onClick="chat.soundChat()" src="http://img.xcombats.com/1x1.gif" class="db cp chatBtn7_1"></td>
        <td width="10">&nbsp;</td>
        <td width="30"><img id="chbtn8" class="db cp chatBtn8_1" onClick="chat.lookSmiles()" src="http://img.xcombats.com/1x1.gif"></td>
        <td width="30"><img id="chbtn8" class="cp" title="Панель быстрого доступа" onClick="fastpanel()" src="http://img.xcombats.com/b___cl1.gif"></td>
        <td width="16" bgcolor="#BAB7B3"><img src="http://img.xcombats.com/i/buttons/chat_explode.gif" width="16" height="30" class="db" /></td>
        <td width="30"><img onclick="top.getUrl('main','main.php?inv=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn13.gif"></td>
		<?   if($u->info['level']>3){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?transfer=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn16.gif"></td>
        <? } if($u->info['level']>=0){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?alh=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn9.gif"></td>
        <td width="30"><img onClick="top.getUrl('main','bank.php');" class="db cp" src="http://img.xcombats.com/bank1.gif"></td>
        <? } if($u->info['align']==50 || $u->info['admin'] == 1) {?>
		<td width="30"><img onClick="top.getUrl('main','main.php?alhp=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn20.gif"></td>
		<? }  if($u->info['align']>=1 && $u->info['align']<2 ){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?light=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn15.gif"></td>
        <? } if($u->info['align']>=3 && $u->info['align']<4){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?dark=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn10.gif"></td>
        <? } if($u->info['vip']>time() || $u->stats['silvers']>0 || $u->stats['bronze']>0 || $u->stats['gold']>0 ){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?vip=1&rnd='+c.rnd);" class="db cp"  src="http://img.xcombats.com/i/buttons/chatBtn17.gif" onMouseOver="top.hi(this,'<b>Vip Панель персонажа <?=$u->info['login']?></b>',event,3,0,1,0,'');" onMouseOut="top.hic();" onMouseDown="top.hic();"> </td></td>
        <? } if($u->info['level']>-1){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?friends=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn12.gif"></td>
        <? } if($u->info['admin'] > 0 || $u->info['align'] == 50){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?notepad=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/b_notepad.gif"></td>
		<? } if($u->info['level']>-1){ ?>

        <? } if($u->info['clan']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?clan=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn14.gif"></td>
        <? } if($u->info['admin']>0){ ?>
        <td width="30"><img onClick="top.getUrl('main','main.php?admin=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn19.gif"></td>
        <? } ?>
        <!--
        <td width="30"><img onClick="top.getUrl('main','main.php?bagreport=1&rnd='+c.rnd);" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtnBugs.gif"></td>
        -->
        <td width="30"><img onClick="if(confirm('Выйти из игры?')){ top.location = '/bk?exit&rnd=<?=$code?>'; }" class="db cp" src="http://img.xcombats.com/i/buttons/chatBtn11.gif"></td>
        <td width="70">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="70" height="25">
           		<param name="movie" value="flash/clock.swf?hours=<?=date("H")?>&amp;minutes=<?=date("i")?>&amp;sec=<?=date("s")?>">
            	<param name="quality" value="high">
            	<embed src="flash/clock.swf?hours=<?=date("H")?>&minutes=<?=date("i")?>&sec=<?=date("s")?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="70" height="25"></embed>
            </object>
        </td>
      </tr>
    </table>
    </td>
    <td height="30" align="right" bgcolor="#D6D6D6"><img class="db" src="http://img.xcombats.com/i/lite/bkf_l_r1_06.gif" width="9" height="30"></td>
  </tr>
  <tr>
    <td height="5" bgcolor="#D6D6D6" style="background:url(http://img.xcombats.com/sand_mid_31.png);"></td>
    <td height="5" bgcolor="#D6D6D6" style="background:url(http://img.xcombats.com/sand_mid_31.png);"><!-- iFrames zone --></td>
    <td height="5" bgcolor="#D6D6D6" style="background:url(http://img.xcombats.com/sand_mid_31.png);"></td>
  </tr>
</table>
<?
if($u->info['active']!='' && $u->info['mail']!='No E-mail')
{
	$yes = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "user_active_good" LIMIT 1',1);
	$yes2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "user_active_send" LIMIT 1',1);
	if($u->info['login'] != '-LEL-')
	{
		mysql_query('UPDATE `stats` SET `active` = "" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
	
	/*
	function send_mime_mail($name_from, // имя отправителя
                       $email_from, // email отправителя
                       $name_to, // имя получателя
                       $email_to, // email получателя
                       $data_charset, // кодировка переданных данных
                       $send_charset, // кодировка письма
                       $subject, // тема письма
                       $body // текст письма
                       )
	   {
	  $to = mime_header_encode($name_to, $data_charset, $send_charset)
					 . ' <' . $email_to . '>';
	  $subject = mime_header_encode($subject, $data_charset, $send_charset);
	  $from =  mime_header_encode($name_from, $data_charset, $send_charset)
						 .' <' . $email_from . '>';
	  if($data_charset != $send_charset) {
		$body = iconv($data_charset, $send_charset, $body);
	  }
	  $headers = "From: $from\r\n";
	  $headers .= "Content-type: text/plain; charset=$send_charset\r\n";
	
	  return mail($to, $subject, $body, $headers);
	}
	
	function mime_header_encode($str, $data_charset, $send_charset) {
	  if($data_charset != $send_charset) {
		$str = iconv($data_charset, $send_charset, $str);
	  }
	  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
	}

	if(!isset($yes2['id']))
	{
		//отправляем письмо
		echo '<script>chat.sendMsg(["new","'.time().'","6","","'.$u->info['login'].'","<small>На Ваш почтовый ящик <b>'.$u->info['mail'].'</b> отправлено письмо с инструкцией по активации аккаунта. (Письмо прийдти в течении 15 минут, а так-же проверьте раздел &quot;Спам&quot;)</small>","Black","1","1","0"]);</script>';
		//$u->addAction(time(),'user_active_send',$u->info['mail']);
		// получатели		
		send_mime_mail('www.xcombats.com',
               'support@xcombats.com',
               ''.$u->info['login'].'',
               $u->info['mail'],
               'CP1251',  // кодировка, в которой находятся передаваемые строки
               'KOI8-R', // кодировка, в которой будет отправлено письмо
               'Активация персонажа '.$u->info['login'].'',
               "Здравствуйте! Мы очень рады новому персонажу в нашем Мире! \r\n Ваш персонаж: ".$u->info['login']." [0] \r\n Ссылка для активации: http://capitalcity.xcombats.com/bk?active=".$u->info['active'].".\r\n\r\nС уважением, Администрация xcombats.com!");
		$u->addAction(time(),'user_active_send',$u->info['mail']);
		
	}elseif(!isset($yes['id']))
	{
		//Пользовательское соглашение
		if(isset($_GET['active']) && $u->info['active'] == $_GET['active'])
		{
			//согласен
			$u->addAction(time(),'user_active_good',$u->info['mail']);
			mysql_query('UPDATE `stats` SET `active` = "" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>top.location = "http://'.$c[$u->info['city']].'/bk";</script>');
		}
	}
	*/
}
?>
<script>
startEngine();
chat.testTimer(false);
/*top.add_cb(6,'Торговый',1,'ch6','<br>');
top.add_cb(5,'чат',1,'ch5','<br>');
top.open_cb(5,null);
top.open_cb(cb_rdate[5],null);*/
top.add_cb(3,'Лог',1,'ch3','<br><div id="battle_logg"></div>');
top.add_cb(4,'Системные сообщения',1,'ch4','<br>');
top.add_cb(5,'Чат',1,'ch5','<br>');
</script>
</body>
</html>
<?
unset($db);
?>