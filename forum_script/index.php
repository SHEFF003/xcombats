<?php

define('GAME',true);
include('../_incl_data/__config.php');
include('../_incl_data/class/__db_connect.php');
include('../_incl_data/class/__filter_class.php');
include('_forum.class.php');

$url = explode('?',$_SERVER["REQUEST_URI"]);
if(isset($url[1])) {
	$i = 0;
	$x = explode('&',$url[1]);
	while( $i < count($x) ) {
		$x2 = explode('=',$x[$i]);
		if(is_array($x2)) {
			if(!isset($x2[1])) {
				$x2[1] = true;
			}
			$_GET[$x2[0]] = $x2[1];
		}else{
			$_GET[$x[$i]] = true;
		}
		$i++;
	}
}

if(isset($_GET['page'])) {
	$_GET['page'] = round((int)$_GET['page']);
	if($_GET['page'] < 1) {
		$_GET['page'] = 1;
	}
}else{
	$_GET['page'] = 1;
}


$f->startForum();

if(($f->user['align']>1 && $f->user['align']<2) || ($f->user['align']>3 && $f->user['align']<4) || $f->user['admin']>0)
{
	if(isset($_GET['mod_use']))
	{
		if(!isset($_COOKIE['mod']))
		{
			setcookie('mod',true,time()+60*60*24*30);
			$_COOKIE['mod'] = true;
		}else{
			setcookie('mod',true,time()-60*60*24*30);
			unset($_COOKIE['mod']);
		}
	}elseif(isset($_GET['mod_use2']) && $f->user['admin']>0)
	{
		if(!isset($_COOKIE['mod2']))
		{
			setcookie('mod2',true,time()+60*60*24*30);
			$_COOKIE['mod2'] = true;
		}else{
			setcookie('mod2',true,time()-60*60*24*30);
			unset($_COOKIE['mod2']);
		}
	}
		
	$mod  = 'on';
	$mod2 = 'off';
	
	if(isset($_COOKIE['mod']))
	{
		$mod = 'off';
		$f->mod = 1;
	}
	if(isset($_COOKIE['mod2']) && $f->user['admin']>0)
	{
		$mod2 = 'on';
		$f->user['admin'] = 0;
	}
}

if(isset($f->user['id']))
{
	if(isset($_POST['add_otv_adm']) && (($f->user['align']>1 && $f->user['align']<2) || ($f->user['align']>3 && $f->user['align']<4) || $f->user['admin']>0)) {
		
		$f->admintopmsg(round((int)$_GET['read']),round((int)$_POST['add_otv_adm']),$_POST['text2_adm'],'Red',round((int)$_POST['adminname_adm']));
		
	}elseif(isset($_POST['add_top']))
	{
		//if($f->gd[$f->fm['id']]==3 || $f->gd[$f->fm['id']]==4 || $f->user['admin']>0)
		//{
			$add = $f->addnewtop($_POST['title'],$_POST['text'],$_POST['icon'],time(),$f->user['login'],$f->user['id'],$_POST['add_top'],-1);
			if($add>0)
			{
				$fnt = 'На форуме, в разделе &quot;Новости&quot; опубликована новая статья &quot;<b>'.$_POST['title'].'</b>&quot;. <a href=http://'.$c['host'].'/forum?read='.$add.' target=_blank \>Читать далее</a>';
				mysql_query('INSERT INTO `chat` (`type`,`time`,`text`) VALUES ("45","'.time().'","'.$fnt.'")');
			}
		//}
	}elseif(isset($_POST['add_otv']))
	{
		$addTo = mysql_fetch_array(mysql_query('SELECT `id`,`time`,`delete`,`fid` FROM `forum_msg` WHERE `id` = "'.mysql_real_escape_string($_POST['add_otv']).'" LIMIT 1'));
		if(isset($addTo['id']))
		{
			//if($f->gd[$addTo['fid']]==2 || $f->gd[$addTo['fid']]==4 || $f->user['admin']>0)
			//{
				$add = $f->addnewtop('',$_POST['text2'],0,time(),$f->user['login'],$f->user['id'],$addTo['fid'],$addTo['id']);
			//}
		}
	}
}
if(isset($_GET['read']) && $f->mod==1)
{
	
	if(isset($_GET['trm']) && (($f->user['align']>1 && $f->user['align']<2) || ($f->user['align']>3 && $f->user['align']<4) || $f->user['admin']>0)) {
		$f->actionSee(9);
	}elseif(isset($_GET['delete_msg']) && (($f->user['align']>=1.5 && $f->user['align']<2) || ($f->user['align']>=3.05 && $f->user['align']<4) || $f->user['admin']>0)) {
		$f->actionSee(8);
	}elseif(isset($_GET['delete']) && isset($f->see['id']) && (($f->user['align']>=1.5 && $f->user['align']<2) || ($f->user['align']>=3.05 && $f->user['align']<4) || $f->user['admin']>0))
	{
		$f->actionSee(1);
	}elseif(isset($_GET['nocomment']) && isset($f->see['id']) && (($f->user['align']>=1.5 && $f->user['align']<2) || ($f->user['align']>=3.05 && $f->user['align']<4) || $f->user['admin']>0))
	{
		$f->actionSee(2);	
	}elseif(isset($_GET['fixed']) && isset($f->see['id']) && (($f->user['align']>=1.5 && $f->user['align']<2) || ($f->user['align']>=3.05 && $f->user['align']<4) || $f->user['admin']>0))
	{
		$f->actionSee(7);	
	}elseif(isset($_GET['onlyadmin']) && isset($f->see['id']) && $f->user['admin']>0)
	{
		$f->actionSee(3);	
	}elseif(isset($_GET['onlymoder']) && isset($f->see['id']) && (($f->user['align']>=3.05 && $f->user['align']<4) || ($f->user['admin']>0 && $_GET['onlymoder']==2)))
	{
		$f->actionSee(5);	
	}elseif(isset($_GET['onlymoder']) && isset($f->see['id']) && (($f->user['align']>=1.5 && $f->user['align']<2) || ($f->user['admin']>0 && $_GET['onlymoder']==1)))
	{
		$f->actionSee(4);	
	}elseif(isset($_GET['onlyall']) && isset($f->see['id']) && (($f->user['align']>=1.5 && $f->user['align']<2) || ($f->user['align']>=3.05 && $f->user['align']<4) || $f->user['admin']>0))
	{
		$f->actionSee(6);	
	}
}

$dost = array(0=>'всем пользователям',1=>'только чтение',2=>'только для Ангелов',3=>'только для Паладинов',4=>'только для Тарманов');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo $c['title']; ?></title>
<script type="text/javascript" src="http://<?=$c['host']?>/js/jquery.js"></script>
<script type="text/javascript" src="http://<?=$c['host']?>/js/jquery.zclip.js"></script>
<script>
$.ajaxSetup({cache: false});
$(window).error(function(){
  return true;
});
</script>
<script type="text/javascript" src="http://<?=$c['host']?>/js/interface.js"></script>
<script type="text/javascript" src="http://<?=$c['host']?>/js/jqueryrotate.js"></script>

<link rel="stylesheet" type="text/css" href="http://<?=$c['host']?>/css/windows.css" />
<style type="text/css">
<!--
html {
	width:100%;
	height:100%;
}
img {
	border:none;
}
body {
	width:100%;
	height:100%;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #3D3D3B;
	font-size: 10pt; font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif; 
}
#header {
	width:100%;
	height:135px;
	text-align:center;
	background-image:url(http://xcombats.com/forum_script/img/line_capitalcity.jpg);
}
#footer {
	width:100%;
	text-align:center;
	background-image:url(http://xcombats.com/forum_script/img/footer_capitalcity.jpg);
	background-repeat:repeat-x;
	padding-top:13px;
	background-color:#000000;
}
#main {
	width:100%;
	text-align:center;
}
#leftground {
	background-image:url(http://xcombats.com/forum_script/img/leftground.jpg);
}
.text {
    font-weight: normal;
    /* font-size: 13px; */
    font-size: 10pt; 
    color: #000000;
    font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif;
}

H3 {
    font-weight: bold;
    /* font-size: 16px; */
    font-size: 12pt; 
    color: #8f0000; 
    font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif; 
    text-align: center;
}

.answ1 {
	
}
.answ1:hover {
    background-color: #EBDCA0;
    background-image:  url(http://xcombats.com/forum_script/img/ao.png);
	cursor:pointer;
}

.answ1h {
    background-color: #EBDCA0;
    background-image:  url(http://xcombats.com/forum_script/img/ao.png);
	cursor:pointer;
}

H4 {
    font-weight: bold;
    /* font-size: 15px; */
    font-size: 11pt; 
    margin-bottom: 5px;
    color: #8f0000; 
    font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif;
}
A:link {
    font-weight: normal;
    color: #524936;
    text-decoration: none;
}
a:visited {
    font-weight: normal;
    color: #633525; 
    text-decoration: none;
}
a:active {
    font-weight: normal;
    color: #77684d;
    text-decoration: none;
}
a:hover {
	color: #1E1E1E;
	text-decoration: underline;
}
.date {
    font-weight: normal; 
    /* font-size: 11px; */
    font-size: 8pt; 
    color: #007000; 
    font-family: Courier, Verdana, Helvetica, Arial, Tahoma, sans-serif;
    text-decoration: none; 
}
.line1 {
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #837B5C;
	width:100%;	
	margin-top:7px;
	margin-bottom:7px;
}
.line2 {
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #C4BFAA;
	width:100%;
	margin-top:9px;
	margin-bottom:9px;
}
.text1 {
	color:#8F0000;
	font-size:12px;
}
.inup {
	border-right: #302F2A 1px double;
	border-top: #302F2A 1px double;
	/* font-size: 11px; */
	font-size: 8pt; 
	border-left: #302F2A 1px double;
	color: #000000;
	border-bottom: #302F2A 1px double;
	font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif;
	background-color: #DED7BD;
}
.text {
	font-weight: normal;
	/* font-size: 13px; */
	font-size: 10pt; 
	color: #000000;
	font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif;
}
SELECT {
	border-right: #b0b0b0 1pt solid; border-top: #b0b0b0 1pt solid; margin-top: 1px; font-size: 10px; 
	margin-bottom: 2px; border-left: #b0b0b0 1pt solid; color: #191970; border-bottom: #b0b0b0 1pt solid; 
	font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif;
}
TEXTAREA {
	border-right: #b0b0b0 1pt solid; border-top: #b0b0b0 1pt solid; margin-top: 1px; font-size: 10px; 
	margin-bottom: 2px; border-left: #b0b0b0 1pt solid; color: #191970; border-bottom: #b0b0b0 1pt solid; 
	font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif;
}
INPUT {
	border-right: #b0b0b0 1pt solid; border-top: #b0b0b0 1pt solid; margin-top: 1px; font-size: 10px; 
	margin-bottom: 2px; border-left: #b0b0b0 1pt solid; color: #191970; border-bottom: #b0b0b0 1pt solid; 
	font-family: Verdana, Helvetica, Arial, Tahoma, sans-serif;
}
pages a {
	color:#5b3e33;
	padding: 1px 3px 1px 3px;
}
pages u {
	padding: 1px 3px 1px 3px;
	color: #6f0000;
	font-weight: bold;
}
pages a:hover {
	background-color:#FFFFFF;
}
div.fixed_topik {
    background-color: #EBDCA0;
    background-image:  url(http://xcombats.com/forum_script/img/ao.png);
    background-repeat: repeat-x;
    -webkit-border-radius:8px;
    -moz-border-radius:8px;
    border-radius:8px;
    -ms-border-radius:8px;
    margin-top: 2px;
    margin-bottom: 2px;
	padding:5px;

}
div.fixed_topik_in {
    background-color: #EBDCA0;
    background-image:  url(http://xcombats.com/forum_script/img/ao.png);
    background-repeat: repeat-x;
    margin-right: 120px;
    -webkit-border-radius:8px;
    -moz-border-radius:8px;
    border-radius:8px;
    -ms-border-radius:8px;
	padding:5px;
}
.btnAdm {
	display:inline-block;
	background-color:#ebdda4;
	border:1px solid #cabb80;
	margin:1px 1px -5px 1px;
}
.btnAdm img {
	padding:5px;
	display:block;
	float:left;
}
.btnAdm img:hover {
	background-color:#c1b278;
}
.forumfiximg {
	max-width:640px;
	max-height:640px;
	padding:3px;
	margin-top:5px;
	border:10px solid #d7c88e;
}
-->
</style>
<script>
var c = { 
	url:'<?=$c['host']?>',
	img:'<?=$c['img']?>',
	uid:<?=(0+$f->user['id'])?>,
	login:'<?=$f->user['login']?>',
	city:'<?=$f->user['city']?>',
	lvl:<?=(0+$f->user['level'])?>,
	rnd:'1',
	filter:0,
	time:<?=time()?>,
	pl:0,
	align:<?=(0+$f->user['align'])?>,
	clan:<?=(0+$f->user['clan'])?>,
	admin:<?=(0+$f->user['admin'])?>,
	sound:0,
	money:0}, sd4key = "0f27a8a6a79921703aee0ba6ff02e4c2", lafstReg = {},enterUse = 0;

function ctest(city) {
	if(city != c['city']) {
		top.location = 'club';
	}
}
var key_actions = {};
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function bbcodearea(area,bb) {
	if( bb == 'a' ) {
		bb = '[url=http://]Название ссылки[/url]';
	}else if( bb == 'img' ) {
		bb = '[img]http://[/img]';
	}else{
		bb = '['+bb+'][/'+bb+']';	
	}
	$('#'+area).val($('#'+area).val()+bb);
}

function acma(id) {
	var html = '';
	
	 html += '<form method="post" action="http://xcombats.com/forum?read=<?=round((int)$_GET['read'])?>&page=<?=$_GET['page']?>">'+
                     '<table align="center">'+
                       '<tr>'+
                         '<td><h4>Текст вашей записи:</h4></td>'+
                       '</tr>'+
                       '<tr>'+
                         '<td><textarea rows="8" class="inup" name="text2_adm" id="text2_adm" cols="85" wrap="virtual"></textarea></td>'+
                         '<td><input onclick="bbcodearea(\'text2_adm\',\'b\');" style="width:55px;" name="add2" type="button" class="btn" value=" Ж " title="Жирный">'+
                             '<br />'+
                             '<input onclick="bbcodearea(\'text2_adm\',\'i\');" style="width:55px;" name="add2" type="button" class="btn" value=" К " title="Наклонный">'+
                             '<br />'+
                             '<input onclick="bbcodearea(\'text2_adm\',\'u\');" style="width:55px;" name="add2" type="button" class="btn" value=" Ч " title="Подчеркнутый">'+
                             '<br />'+
                             '<input onclick="bbcodearea(\'text2_adm\',\'code\');" style="width:55px;" name="add2" type="button" class="btn" value="Код" title="Текст программы">'+
							 '<br />'+
                             '<input onclick="bbcodearea(\'text2_adm\',\'a\');" style="width:55px;" name="add2" type="button" class="btn" value="Ссылка">'+
							 '<br />'+
                             '<input onclick="bbcodearea(\'text2_adm\',\'img\');" style="width:55px;" name="add2" type="button" class="btn" value="Картинка"></td>'+
                       '</tr>'+
                       '<tr>'+
                         '<td colspan="2"><table width="100%">'+
                             '<tr>'+
                               <? if($f->user['admin'] > 0 ) { ?>
							   '<td><div align="left"><input name="adminname_adm" id="adminname_adm" type="checkbox" value="1" /><label for="adminname_adm"> От имени Администрации проекта.</label></div><br><br /></td>'+
                               <? } ?>
							   '<td width="120" align="right"><input type="submit" class="btn" value="Добавить" name="add2_adm" />'+
                                   '<input type="hidden" id="add_otv_adm" name="add_otv_adm" value="'+id+'" /></td>'+
                             '</tr>'+
                         '</table></td>'+
                       '</tr>'+
                     '</table>'+
                   '</form>';

	
	win.add('cmments1forum','Оставить запись к комментарию',html,{'a1':'alert('+id+');'},0,1,'width:630px;');
}
//-->
</script>
</head>

<body>
<div id="windows" style="position:absolute;z-index:1101;"></div>
<? /*
<div id="wupbox" style="position:absolute;z-index:10101;width:100%;height:100%;" onmouseup="win.WstopDrag()" onmousemove="win.WmoveDrag(event)" onselectstart="return false"></div>
*/ ?>
<div id="header"><img src="http://xcombats.com/inx/newlogo.jpg" width="360" height="135"></div>
<div id="main">
  <table width="<? if($f->r==-1){ echo '80%'; }else{ echo '80%'; } ?>" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="29" background="http://xcombats.com/forum_script/img/leftground.jpg">&nbsp;</td>
      <td width="1" bgcolor="#F2E5B1">&nbsp;</td>
      <td valign="top" bgcolor="#F2E5B1" align="left">
      <!-- -->
      <?
	  if(isset($f->error) && $f->error!='' && $f->r!=-2)
	  {
	  	 echo '<font color="red"><b>'.$f->error.'</b></font>';
	  }
	  if($f->user['admin']>0 && isset($_GET['aem']))
	  {
	  ?>
      <div style="margin:30px;">
        <p><a href="javascript:void(0);" onClick="history.back();">Вернуться назад</a></p>
        <br>
        <?
		$ed = mysql_fetch_array(mysql_query('SELECT * FROM `forum_msg` WHERE `id` = "'.((int)$_GET['aem']).'" LIMIT 1'));
		if(!isset($ed['id']))
		{
			echo '<br><br><center>Данные для редактирования не найдены</center><br><br>';
		}else{
			$fm = mysql_fetch_array(mysql_query('SELECT * FROM `forum_menu` WHERE `id` = "'.$ed['fid'].'" LIMIT 1'));
			echo 'ID: '.$ed['id'].'<br>Раздел: <b>'.$fm['name'].'</b><br>';
		}
		?>
        </div>
        <?
	  }elseif(isset($_GET['search'])) {
	  //Ищем на форуме
	  if(isset($_POST['search'])) {
		 $_GET['search'] = $_POST['search']; 
	  }else{
		 $_POST['search'] = $_GET['search']; 
	  }
	  $word = $_POST['search'];
	  $word = htmlspecialchars($word,NULL,'cp1251');
	  $limw = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `forum_msg` WHERE ( `text` LIKE "%'.mysql_real_escape_string($_POST['search']).'%" OR `title` LIKE "%'.mysql_real_escape_string($_POST['search']).'%" OR `login` LIKE "%'.mysql_real_escape_string($_POST['search']).'%" ) AND `topic` < "0" AND `delete` = "0"'));
	  $limw = $limw[0];
	  ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="210" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><img src="http://xcombats.com/forum_script/img/fone1.jpg" width="118" height="257" style="margin-left:-15px;"></td>
              </tr>
            <tr>
              <td><div align="center"><? if($f->user==false){ echo 'Вы не авторизованы<br><a href="http://'.$c['host'].'/">Войти на персонажа</a>'; }else{ echo 'Вы вошли как: <br>'.$f->user['sl'].'<br><br>'; } ?>
                <? if(($f->user['align']>1 && $f->user['align']<2) || ($f->user['align']>3 && $f->user['align']<4) || $f->user['admin']>0){ echo '<br><a href="?r='.$_GET['r'].'&mod_use&rnd='.$code.'">«Модерирование» ['.$mod.']</a>'; }
				   if($f->user['admin']>0 || $mod2=='on'){ echo '<br><a href="?r='.$_GET['r'].'&mod_use2&rnd='.$code.'">«Администрирование» ['.$mod2.']</a>'; }  ?>
              </div></td>
            </tr>
            <tr>
              <td height="50" valign="bottom">
              <div align="center" class="text1">
                <div align="left"><b>Конференция</b></div>
                <div class="line1"></div>
              </div>              </td>
            </tr>
            <tr>
              <td>
			  <? echo $f->menu; ?>
              <div class="line1"></div>              </td>
            </tr>
            <tr>
              <td><div align="center"><img src="http://xcombats.com/forum_script/img/icon7.gif" width="15" height="15" title="Смайлики"> <a href="?smiles=1">Смайлики</a></div>
                <br><br><br></td>
            </tr>
          </table></td>
          <td valign="top"><div align="center">
            <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>
                <td><div align="center">
                  <? echo '<H3>Результаты поиска по запросу: &quot;'.$word.'&quot;. Найдено записей '.$limw.' шт.</H3><br>'; ?></div><div align="left"></div></td>
              </tr>
              <tr>
                <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
				  <?
				  $p_list=$f->paginator(1);
				  ?>
                    <td><div align="left"><img src="http://xcombats.com/forum_script/img/news.gif" width="16" height="16"> Страницы:  <?echo $p_list;?></div></td>
                    <td width="250"><div class="line2"></div><form method="post" action="?search">Поиск: <input type="text" value="<?=$word?>" name="search"/> <input type="submit" value="найти"></form></td>
                  </tr>
                </table>
                <div class="line2"></div></td>
              </tr>
              <tr>
                <td valign="top"><div align="left">
                  <? $f->forumData(); ?>
                </div></td>
              </tr>
              <tr>
                <td><div align="left" style="margin-top:5px;"><img src="http://xcombats.com/forum_script/img/news.gif" width="16" height="16"> Страницы:  <?echo $p_list;?></div></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div></td>
        </tr>
      </table>
	  <?
	  }elseif($f->r>=1){
	  //смотрим разделы
	  ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="210" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><img src="http://xcombats.com/forum_script/img/fone1.jpg" width="118" height="257" style="margin-left:-15px;"></td>
              </tr>
            <tr>
              <td><div align="center"><? if($f->user==false){ echo 'Вы не авторизованы<br><a href="http://'.$c['host'].'/">Войти на персонажа</a>'; }else{ echo 'Вы вошли как: <br>'.$f->user['sl'].'<br><br>'; } ?>
                <? if(($f->user['align']>1 && $f->user['align']<2) || ($f->user['align']>3 && $f->user['align']<4) || $f->user['admin']>0){ echo '<br><a href="?r='.$_GET['r'].'&mod_use&rnd='.$code.'">«Модерирование» ['.$mod.']</a>'; }
				   if($f->user['admin']>0 || $mod2=='on'){ echo '<br><a href="?r='.$_GET['r'].'&mod_use2&rnd='.$code.'">«Администрирование» ['.$mod2.']</a>'; }  ?>
              </div></td>
            </tr>
            <tr>
              <td height="50" valign="bottom">
              <div align="center" class="text1">
                <div align="left"><b>Конференция</b></div>
                <div class="line1"></div>
              </div>              </td>
            </tr>
            <tr>
              <td>
			  <? echo $f->menu; ?>
              <div class="line1"></div>              </td>
            </tr>
            <tr>
              <td><div align="center"><img src="http://xcombats.com/forum_script/img/icon7.gif" width="15" height="15" title="Смайлики"> <a href="?smiles=1">Смайлики</a></div>
                <br><br><br></td>
            </tr>
          </table></td>
          <td valign="top"><div align="center">
            <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>
                <td><div align="center">
                  <? echo '<H3>Конференция "'.$f->fm['name'].'"</H3><br><br>'.$f->fm['opisan']; ?></div><div align="left"></div></td>
              </tr>
              <tr>
                <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
				  <?
				  $p_list=$f->paginator(1);
				  ?>
                    <td><div align="left"><img src="http://xcombats.com/forum_script/img/news.gif" width="16" height="16"> Страницы:  <?echo $p_list;?></div></td>
                    <td width="250"><div class="line2"></div><form method="post" action="?search">Поиск: <input type="text" value="" name="search"/> <input type="submit" value="найти"></form></td>
                  </tr>
                </table>
                <div class="line2"></div></td>
              </tr>
              <tr>
                <td valign="top"><div align="left">
                  <? $f->forumData(); ?>
                </div></td>
              </tr>
              <tr>
                <td><div align="left" style="margin-top:5px;"><img src="http://xcombats.com/forum_script/img/news.gif" width="16" height="16"> Страницы:  <?echo $p_list;?></div></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>
                <? if(isset($f->user['id'])){ ?>
                <div style="border:1px solid #FFFFFF; margin:21px;">
                <? 
				if($f->gd[$f->fm['id']]!=2 && $f->gd[$f->fm['id']]!=4 && $f->user['admin']==0)
				{
					echo '<center><br>Вы не можете создавать топики в этой конференции<br><br></center>';
				}elseif($f->lst['time']<time()-60){ ?>
                <form method="post" action="http://xcombats.com/forum?r=<? echo $f->r.'&rnd='.$code.''; ?>">
                <table align="center">
                  <tr>
                    <td colspan="2"><h4>Добавить свой вопрос в форум</h4>
                      Тема сообщения
                      <input type="text" class="inup" name="title" size="57" maxlength="70" value="" /></td>
                  </tr>
                  <tr>
                    <td><textarea rows="8" class="inup" name="text" id="text" cols="85" wrap="virtual"></textarea>
                    </td>
                    <td><input onclick="bbcodearea('text','b');" name="add" style="width:55px;" type="button" class="btn" value=" Ж " title="Жирный">
                        <br />
                        <input onclick="bbcodearea('text','i');" name="add" style="width:55px;" type="button" class="btn" value=" К " title="Наклонный">
                      <br />
                        <input onclick="bbcodearea('text','u');" name="add" style="width:55px;" type="button" class="btn" value=" Ч " title="Подчеркнутый">
                      <br />
                        <input onclick="bbcodearea('text','code');" name="add" style="width:55px;" type="button" class="btn" value="Код" title="Текст программы">
                      <br />
                        <input onclick="bbcodearea('text','a');" name="add" style="width:55px;" type="button" class="btn" value="Ссылка">
                      <br />
                        <input onclick="bbcodearea('text','img');" name="add" style="width:55px;" type="button" class="btn" value="Картинка">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%">
                        <tr>
                          <td><?
					  if($f->user['admin']>0)
					  {
					  	echo '<div align="left"><input name="adminname" id="adminname" type="checkbox" value="1" /><label for="adminname"> От имени Администрации проекта.</label></div><br>';
					  }
					  ?>
                            <input type="radio" name="icon" value="13" checked>
                              <img src="http://xcombats.com/forum_script/img/icon13.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="14">
                            <img src="http://xcombats.com/forum_script/img/icon14.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="6">
                            <img src="http://xcombats.com/forum_script/img/icon6.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="9">
                            <img src="http://xcombats.com/forum_script/img/icon9.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="1">
                            <img src="http://xcombats.com/forum_script/img/icon1.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="10">
                            <img src="http://xcombats.com/forum_script/img/icon10.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="11">
                            <img src="http://xcombats.com/forum_script/img/icon11.gif" height="15" width="15"><BR>
                              <input type="radio" name="icon" value="12">
                            <img src="http://xcombats.com/forum_script/img/icon12.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="2">
                            <img src="http://xcombats.com/forum_script/img/icon2.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="3">
                            <img src="http://xcombats.com/forum_script/img/icon3.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="4">
                            <img src="http://xcombats.com/forum_script/img/icon4.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="5">
                            <img src="http://xcombats.com/forum_script/img/icon5.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="7">
                            <img src="http://xcombats.com/forum_script/img/icon7.gif" height="15" width="15"> &nbsp;
                              <input type="radio" name="icon" value="8">
                            <img src="http://xcombats.com/forum_script/img/icon8.gif" height="15" width="15"> <br /></td>
                          <td align="right" valign="top">
                          	  <input type="submit" class="btn" value="Добавить" name="add" />
                              <input type="hidden" id="key" name="key" value="<? echo $f->user['nextAct']; ?>" />
                              <input type="hidden" id="add_top" name="add_top" value="<? echo $f->fm['id']; ?>" />                          </td>
                        </tr>
                    </table></td>
                  </tr>
                </table>
                </form>
                <? }else{ echo '<br><center>Временное ограничение на создание топиков.<br> Осталось подождать '.round($f->lst['time']+61-time()).' сек.</center><br>'; } ?>
                </div>
                <? } ?>
                </td>
              </tr>
            </table>
          </div></td>
        </tr>
      </table>
     <? }elseif($f->r==-1){
	  //оставляем комментарий
	  ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="118" valign="top"><img src="http://xcombats.com/forum_script/img/fone1.jpg" width="118" height="257" style="margin-left:-15px;"></td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="center">
              	<?
				$url1 = mysql_fetch_array(mysql_query('SELECT `id`,`title` FROM `forum_msg` WHERE `fid` = "'.$f->see['fid'].'" AND `topic` = "-1" AND `delete` = "0" AND `id` > '.$f->see['id'].' ORDER BY `id` ASC LIMIT 1'));
				$url2 = mysql_fetch_array(mysql_query('SELECT `id`,`title` FROM `forum_msg` WHERE `fid` = "'.$f->see['fid'].'" AND `topic` = "-1" AND `delete` = "0" AND `id` < '.$f->see['id'].' ORDER BY `id` DESC LIMIT 1'));
				
				if(isset($url1['id'])) {
					$url1 = '<a href="?read='.$url1['id'].'" title="'.$url1['title'].'"><b>&laquo; предыдущая ветвь</b></a>';
				}else{
					$url1 = '&laquo; предыдущая ветвь';
				}
				if(isset($url2['id'])) {
					$url2 = '<a href="?read='.$url2['id'].'" title="'.$url2['title'].'"><b>следующая ветвь &raquo;</b></a>';
				}else{
					$url2 = 'следующая ветвь &raquo;';
				}
				
				?>
                  <DIV align="center"><?=$url1?> | <A href="?r=<? echo $f->fm['id']; ?>" title="Конференция &quot;<? echo $f->fm['name']; ?>&quot;"><b>форум</b></A> | <?=$url2?><BR>
                  </DIV>
              </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
			<?
			$p_list=$f->paginator(2);
			?>
              <td>Страницы: <?echo $p_list;?></td>
            </tr>
            <tr>
              <td style="padding-top:11px; padding-bottom:11px;" align="left">
              <div style="float:left"><h4><img src="http://xcombats.com/forum_script/img/icon<? echo $f->see['ico']; ?>.gif"> <a href="?r=<? echo $f->fm['id'].'&rnd='.$code; ?>"><b><? echo $f->fm['name']; ?></b></a> &gt; <? echo $f->see['title']; ?></h4></div>
              <? if(($f->user['align']>1.5 || $f->user['align']>3.05 || $f->user['admin']>0) && $f->mod == 1){ ?>
              <div style="float:right;">
                  Доступ: <? echo $dost[$f->see['nocom']]; ?><br>
                  <select name="jumpTopic" id="jumpTopic" onChange="MM_jumpMenu('parent',this,0)">
                    <option selected="selected">--------- переместить ---------</option>
                    <?
					$rtn = '';
					$sp = mysql_query('SELECT * FROM `forum_menu`');
					while($pl = mysql_fetch_array($sp)) {
						if($pl['only_admin'] == 0 || $f->user['admin'] > 0) {
							$rtn .= '<option value="?read='.$_GET['read'].'&trm='.$pl['id'].'">'.$pl['name'].'</option>';
						}
					}
					echo $rtn;
					?>
                  </select><br>
                  <select name="actionTopic" id="actionTopic" onChange="MM_jumpMenu('parent',this,0)">
                    <option selected>----------- действия -----------</option>
                    <? if($f->see['nocom']==0) { ?>
                    <option value="?read=<? echo $_GET['read'].'&nocomment=1&rnd='.$code.''; ?>">Запретить оставлять ответы</option>
                    <? } ?>
                    <option value="?read=<? echo $_GET['read'].'&delete=1&rnd='.$code.''; ?>">удалить топик</option>
                    <? if($f->see['fixed']==0) { ?>
                    <option value="?read=<? echo $_GET['read'].'&fixed=1&rnd='.$code.''; ?>">Зафиксировать топик</option>
                    <? }else{ ?>
                    <option value="?read=<? echo $_GET['read'].'&fixed=1&rnd='.$code.''; ?>">Убрать фиксацию топика</option>
                    <?
					}
                    if($f->see['nocom']==0)
					{
				    if($f->user['admin']>0)
					{
					?>
                    <option value="?read=<? echo $_GET['read'].'&onlyadmin=1&rnd='.$code.''; ?>">только для Ангелов</option>
                    <?
					}
					if(($f->user['align']>1.5 && $f->user['align']<2) || $f->user['admin']>0)
					{
					?>
                    <option value="?read=<? echo $_GET['read'].'&onlymoder=1&rnd='.$code.''; ?>">только для Паладинов</option>
                    <?
					}
					if(($f->user['align']>3.05 && $f->user['align']<4) || $f->user['admin']>0)
					{
					?>
                    <option value="?read=<? echo $_GET['read'].'&onlymoder=2&rnd='.$code.''; ?>">только для Тарманов</option>
                    <?
					}
					}else{
					?>
                    <option value="?read=<? echo $_GET['read'].'&onlyall=1&rnd='.$code.''; ?>">разрешить оставлять ответ</option>
                    <?
					}
					?>
                  </select>
                </div>
              <? } ?>
              </td>
            </tr>
            <tr>
            	<td><? $f->seeTopic(); ?></td>
            </tr>
            <tr>
              <td style="padding-top:3px; padding-bottom:3px;" align="left">Страницы:  <?echo $p_list;?></td>
            </tr>
            <tr>
              <td style="padding-top:3px; padding-bottom:3px;" align="left"><? if(isset($f->user['id']) || !isset($f->user['id'])){ ?>
                <div style="border:1px solid #EFEFEF; margin:21px;">
                  <?
				    if($f->see['nocom']>0)
					{
						echo '<center><br><font color="red"><b>Обсуждение закрыто</b></font><br><br></center>';
					}elseif($f->see['goodAdd']!=1 || $f->pravasee()!=1)
					{
						echo '<center><br>Вы не можете оставлять ответы в этом топике.<br>Попробуйте через '.round($f->lst['time']+62-time()).' сек.<br><br></center>';
					}/*elseif($f->gd[$f->see['fid']]!=3 && $f->gd[$f->see['fid']]!=4 && $f->user['admin']==0)
					{
						echo '<center><br>Вы не можете оставлять ответы в этом топике<br><br></center>';
					}*/elseif($f->lst['time']<time()-60){ ?>
                  <form method="post" action="http://xcombats.com/forum?read=<? echo $f->see['id'].'&rnd='.$code.''; ?>">
                    <table align="center">
                      <tr>
                        <td colspan="2"><h4>Добавить свой ответ</h4>                      </td>
                      </tr>
                      <tr>
                        <td><textarea rows="8" class="inup" id="text2" name="text2" cols="85" wrap="virtual"></textarea>                        </td>
                        <td><input onclick="bbcodearea('text2','b');" style="width:55px;" name="add2" type="button" class="btn" value=" Ж " title="Жирный">
                            <br />
                            <input onclick="bbcodearea('text2','i');" style="width:55px;" name="add2" type="button" class="btn" value=" К " title="Наклонный">
                            <br />
                            <input onclick="bbcodearea('text2','u');" style="width:55px;" name="add2" type="button" class="btn" value=" Ч " title="Подчеркнутый">
                            <br />
                            <input onclick="bbcodearea('text2','code');" style="width:55px;" name="add2" type="button" class="btn" value="Код" title="Текст программы">
                            <br />
                            <input onclick="bbcodearea('text2','a');" style="width:55px;" name="add2" type="button" class="btn" value="Ссылка">
                            <br />
                            <input onclick="bbcodearea('text2','img');" style="width:55px;" name="add2" type="button" class="btn" value="Картинка">
                         </td>
                      </tr>
                      <tr>
                        <td colspan="2"><table width="100%">
                            <tr>
                              <td><?
					  if($f->user['admin']>0)
					  {
					  	echo '<div align="left"><input name="adminname" id="adminname" type="checkbox" value="1" /><label for="adminname"> От имени Администрации проекта.</label></div><br>';
					  }
					  ?>                                <br /></td>
                              <td width="120" align="right"><input type="submit" class="btn" value="Добавить" name="add2" />
                                  <input type="hidden" id="key2" name="key2" value="<? echo $f->user['nextAct']; ?>" />
                                  <input type="hidden" id="add_otv" name="add_otv" value="<? echo $f->see['id']; ?>" />                              </td>
                            </tr>
                        </table></td>
                      </tr>
                    </table>
                  </form>
                  <? }else{ echo '<br><center>Временное ограничение на добавление ответов.</center><br>'; } ?>
                </div>
                <? } ?></td>
            </tr>
          </table></td>
          <td width="118" valign="top">&nbsp;</td>
        </tr>
      </table>
      <? }elseif($f->r==-2){
	  //МЕГА-выводим ошибку
	  ?>
      <table width="100%" height="500" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="middle" align="center"><strong>Ошибка:</strong> <? echo $f->error; ?><br><a href="http://xcombats.com/forum?rnd=<? echo $code; ?>">Вернуться на форум</a></td>
        </tr>
      </table>
	  <? } ?>
      <!-- -->
      </td>
      <td width="1" bgcolor="#F2E5B1">&nbsp;</td>
      <td width="24" background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;</td>
    </tr>
  </table>
</div>
<div id="footer">
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="10%" scope="col"><DIV> <? echo $c['counters']; ?> </DIV></td>
      <td width="80%" scope="col"><div align="center"><? echo $c['copyright']; ?></div></td>
      <td width="10%" scope="col">&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>