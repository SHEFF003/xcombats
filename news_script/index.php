<?php
define('GAME',time());

include('../_incl_data/class/__db_connect.php');

$url = explode('?',$_SERVER["REQUEST_URI"]);
$url = explode('/',$url[0]);

/* Пользователь */
$u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned`,`admin`,`clan`,`align`,`level`,`molch1`,`molch2` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));

if($url[2] > 0) {
	$_GET['st'] = $url[2];
}

if($url[2] == 'add') {
	$_GET['add_new_st'] = true;
	unset($_GET['st']);
}
if($url[2] == 'p' ) {
	$pal_al = array('1.1', '1.4', '1.5', '1.6', '1.7', '1.75', '1.9', '1.91', '1.92', '1.99');
	$tar_al = array('3.01', '3.05', '3.06', '3.07', '3.075', '3.09', '3.091', '3.092', '3.99');
	
	if($u['admin'] > 0 || in_array($u['align'], $pal_al) || in_array($u['align'], $tar_al)) {
		if( $url[4] == 'delete' ) {
			mysql_query('UPDATE `events_news` SET `delete` = "'.$u['id'].'" WHERE `id` = "'.mysql_real_escape_string($url[5]).'" AND `delete` = 0 LIMIT 1');
		}
	}
}

$add = array(1);

if($u['banned'] > 0 || $u['molch1'] > time() || $u['molch2'] > time()) {
  if($u['admin'] == 0 && $u['banned'] > 0) { unset($u); $add[0] = -1; } 
  $add[0] = -2;
}

if($u['level'] < 5) { $add[0] = -4; }
if($add[0]==1) {
	$pac = mysql_fetch_array(mysql_query('SELECT * FROM `events_news` WHERE `comment` > 0 AND `time` > "'.(time()-60).'" AND `uid` = "'.$u['id'].'" LIMIT 1'));
	if(isset($pac['id'])) {
		$add[0] = -3;
	}
	unset($pac);
}

if($u['admin']>0) {
	$add = array(1);
}

/* Страница и тип страницы */
if(isset($_GET['page_id'])) {
	$p = (int)$_GET['page_id'];
}
if($p != 1 && $p != 2 && $p != 3 && $p != 4 && $p != 5 && $p != 6 && $p != 7) {
	$p = 1; 
}
if($p==1) {$p_my= array(1,7);} 
if($p==4) {
	$p = 1;
}
if(isset($_GET['paged'])) {
	$pg = round((int)$_GET['paged']);
}
if($pg < 0) {
	$pg = 0;
}

$pal_al = array('1.1', '1.4', '1.5', '1.6', '1.7', '1.75', '1.9', '1.91', '1.92', '1.99');
$tar_al = array('3.01', '3.05', '3.06', '3.07', '3.075', '3.09', '3.091', '3.092', '3.99');

if($u['admin'] > 0 || in_array($u['align'], $pal_al) || in_array($u['align'], $tar_al)) {
	if(isset($_GET['delete']) && isset($_GET['del2'])) {
		if(mysql_query('UPDATE `events_news` SET `delete` = "'.$u['id'].'" WHERE `id` = "'.mysql_real_escape_string($_GET['delete']).'" AND `delete` = 0 LIMIT 1')) {
			mysql_query('UPDATE `events_news` SET `comments` = `comments` - 1 WHERE `id` = "'.mysql_real_escape_string($_GET['del2']).'" LIMIT 1');
		}
		$_GET['st'] = $_GET['del2'];
	}
	if($_POST['s_title'] == 'micronews1' || $_POST['s_title'] == 'micronews2') {
		$micid = 1;
		if($_POST['s_title'] == 'micronews1') {
			$micid = 1;
		}elseif($_POST['s_title'] == 'micronews2') {
			$micid = 2;
		}
		mysql_query('UPDATE `events_mini` SET `text` = "'.mysql_real_escape_string($_POST['s_text']).'" WHERE `id` = "'.$micid.'" LIMIT 1');
		//
	}elseif(isset($_GET['delete'])) {
		mysql_query('UPDATE `events_news` SET `delete` = "'.$u['id'].'" WHERE `id` = "'.mysql_real_escape_string($_GET['delete']).'" AND `delete` = 0 LIMIT 1');
	}elseif(isset($_POST['s_id'])) {
		if($_POST['s_id2'] > 0) {
			$_GET['st'] = $_POST['s_id2'];
		}
		mysql_query('UPDATE `events_news` SET `title` = "'.mysql_real_escape_string($_POST['s_title']).'",`text` = "'.mysql_real_escape_string($_POST['s_text']).'" WHERE `id` = "'.mysql_real_escape_string($_POST['s_id']).'" LIMIT 1');
		header('location: '.$_SERVER['REQUEST_URI']);
		die();
	}elseif(isset($_GET['add_new_st'])) {
		mysql_query('INSERT INTO `events_news` (`r`,`time`,`uid`,`title`,`text`) VALUES ("'.mysql_real_escape_string($p).'","'.time().'","'.$u['id'].'","Новая статья","Текст статьи в разработке...")');
		header('location:http://xcombats.com/news/p/'.$pg.'');
		die();
	}
}

$rname = array(
	1 => 'Новости',
	2 => 'Анонсы',
	3 => 'Конкурсы',
	5 => 'Сервера',
	6 => 'Партнерам',
	7 => 'Блог разработчиков'
);

?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" dir="ltr" lang="ru-RU">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" dir="ltr" lang="ru-RU">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" dir="ltr" lang="ru-RU">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html dir="ltr" lang="ru-RU">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width" />
<title>Новости &mdash; Старый Бойцовский Клуб</title>
<link rel="stylesheet" type="text/css" media="all" href="/news_script/style.css" />
<style>
hr {border:0;border-bottom:1px solid #aeaeae; }
</style>
<script type="text/javascript" src="/js/jquery.js"></script>
<script>
function drw_wfl(login, orden, rang, clan, level)
{
    var data = '';
	
	if(login == 'invisible') {
	    return "<b><i>невидимка</i></b>";
	}
	
    if(orden != '' && orden != 0)
    {
           data += '<img src="http://img.xcombats.com/i/align/align' + orden + '.gif"  border="0" />';
	}
    
    if(clan != '' && clan != undefined)
    {
        data += '<img src="http://img.xcombats.com/i/clan/' + clan + '.gif"  alt="" border="0" />';    }

    return data + '<b>' + login + '</b> ['+ level +']<a target="_blank" href="http://xcombats.com/inf.php?login='+login+'"><img src="http://img.xcombats.com/i/inf.gif" border="0"/></a>';  
}
</script>
<? if($u['admin']>0) { ?>
<script src="http://xcombats.com/inx/ckeditor/ckeditor.js"></script>
<link href="sample.css" rel="stylesheet">
<script>
function save_new(id,id2) {
	//blockmini1
	//alert($('#editable'+id).);
	if(id < 0) {
		id = -id;
		$('#save_form').attr('action','http://xcombats.com/news/');
		$('#s_title').val('micronews'+id);
		$('#s_text').val($('#blockmini'+id).html());
		$('#save_form').submit();
	}else{
		<? if(isset($_GET['st'])) { ?>
		$('#save_form').attr('action','http://xcombats.com/news/<?=$_GET['st']?>/');
		<? }else{ ?>
		$('#save_form').attr('action','http://xcombats.com/news/p/<?=$pg?>/');
		<? } ?>
		$('#s_title').val($('#editable_title'+id).html());
		$('#s_text').val($('#editable'+id).html());
		$('#s_id').val(id);
		$('#s_id2').val(id2);
		$('#save_form').submit();
	}
}
</script>
<? } ?>
<link rel='index' title='Сайт Событий' href='http://xcombats.com/news/' />
<link rel='canonical' href='http://xcombats.com/news/' />
</head>
<body class="home page page-id-166 page-parent page-template page-template-news-php singular two-column right-sidebar">
<?
if($u['admin']>0) {
?>
<form action="../news/?page_id=<?=$p?>&paged=<?=$pg?>&st=" id="save_form" method="post">
<input id="s_title" name="s_title" type="hidden">
<input id="s_text" name="s_text" type="hidden">
<input id="s_id" name="s_id" type="hidden">
<input id="s_id2" name="s_id2" type="hidden">
</form>
<?
}
?>
<div style="max-width:1000px;" id="page" class="hfeed">
	<header id="branding" role="banner">
			<div style="background: #5C0001 url('http://img.xcombats.comhttp://xcombats.com/news_script/events/top_nq_03.jpg'); height:76px;">
				<div style="height:76px;">
					<img src="http://xcombats.com/news_script/top_nq_011.jpg" width="681" height="77" />
				</div>
	  		</div>
			<div style="clear: both"></div>
			

			<nav style="float:none" id="access" role="navigation">
<div class="menu-container">
<ul id="menu" class="menu">
	<li id="menu-item-1" class="menu-item menu-item-type-post_type menu-item-object-page <? if($p == 1){ echo 'current-menu-item page_item page-item-1 current_page_item'; } ?> menu-item-1"><a href="http://xcombats.com/news/">Новости</a></li>
	<!--<li id="menu-item-2" class="menu-item menu-item-type-post_type menu-item-object-page <? if($p == 2){ echo 'current-menu-item page_item page-item-1 current_page_item'; } ?> menu-item-2"><a href="http://xcombats.com/news/announcement/">Анонсы</a></li>-->
    <!--
	<li id="menu-item-6" class="menu-item menu-item-type-post_type menu-item-object-page <? if($p == 6){ echo 'current-menu-item page_item page-item-1 current_page_item'; } ?> menu-item-6"><a href="http://xcombats.com/news/?page_id=6">Партнерам</a></li>
	<li id="menu-item-3" class="menu-item menu-item-type-post_type menu-item-object-page <? if($p == 3){ echo 'current-menu-item page_item page-item-1 current_page_item'; } ?> menu-item-3"><a href="http://xcombats.com/news/?page_id=3">Конкурсы</a></li>
	<? /* ?><li id="menu-item-4" class="menu-item menu-item-type-post_type menu-item-object-page <? if($p == 4){ echo 'current-menu-item page_item page-item-1 current_page_item'; } ?> menu-item-4"><a href="http://xcombats.com/news/?page_id=4">Фоторепортажи</a></li><? */ ?>
	<li id="menu-item-5" class="menu-item menu-item-type-post_type menu-item-object-page <? if($p == 5){ echo 'current-menu-item page_item page-item-1 current_page_item'; } ?> menu-item-5"><a href="http://xcombats.com/news/?page_id=5">Сервера</a></li>
	<li id="menu-item-7" class="menu-item menu-item-type-post_type menu-item-object-page <? if($p == 7){ echo 'current-menu-item page_item page-item-1 current_page_item'; } ?> menu-item-7"><a href="http://xcombats.com/news/?page_id=7">Блог разработчиков</a></li>
-->
</ul></div></nav><!-- #access -->
	</header><!-- #branding -->

<br>

	<div id="main">
		<div id="primary">
			<div id="content" role="main">
<? if($u['admin'] > 0) { ?>
<div style="font-size: 12pt; float: left;"><small><a href="/news/add/<?=$pg?>/<?=$p?>">Добавить статью</a></small></div>
<? } ?>
<div style="font-size: 12pt; font-weight: bold; text-align: right;"><?=$rname[$p]?></div>
<hr />
<?
if( $p != 2 && $p != 5 && $p != 6) {
?> 
	<!--<div align="right" id="search_box">
	<form method="post" id="searchform" action="http://xcombats.com/news_script/">
		<input type="text" class="field" name="sr" id="sr" placeholder="Поиск" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="Искать" />
	</form>
	</div>-->
<? } ?>
	  <div class="associated-posts">  
<?
if(isset($_GET['st'])) {
?>
<!-- pnews -->
<?
//Новостная лента
if ($p==1) {
	$sp = mysql_query('SELECT * FROM `events_news` WHERE (`r` = "'.mysql_real_escape_string($p_my[0]).'" or `r` = "'.mysql_real_escape_string($p_my[1]).'") AND `delete` = "0" AND `comment` = "0" AND `id` = "'.mysql_real_escape_string((int)$_GET['st']).'" LIMIT 1');
}elseif($p==2){
	$sp = mysql_query('SELECT * FROM `events_news` WHERE `r` = "'.mysql_real_escape_string($p).'" AND `delete` = "0" AND `comment` = "0" AND `id` = "'.mysql_real_escape_string((int)$_GET['st']).'" LIMIT 1');
} else {
	$sp = mysql_query('SELECT * FROM `events_news` WHERE `delete` = "0" AND `comment` = "0" AND `id` = "'.mysql_real_escape_string((int)$_GET['st']).'" LIMIT 1');
}
$pl = mysql_fetch_array($sp);

if(isset($pl['id'])) {
	if($url[3] == 'delete') {
		if($url[4] > 0) {
			//Удаляем комментарий
			mysql_query('UPDATE `events_news` SET `delete` = "1" WHERE `id` = "'.mysql_real_escape_string($url[4]).'" LIMIT 1');
			mysql_query('UPDATE `events_news` SET `comments` = `comments` - 1 WHERE `id` = "'.mysql_real_escape_string($url[2]).'" LIMIT 1');
		}elseif(!isset($url[4])){
			//Удаляем новость
			mysql_query('UPDATE `events_news` SET `delete` = "1" WHERE `id` = "'.mysql_real_escape_string($url[2]).'" LIMIT 1');
			unset($pl);
		}
	}
}

if(isset($pl['id'])){
if(isset($u['id']) && $add[0]==1) {
	if(isset($_POST['text_com']) && str_replace(' ','',str_replace('	','',$_POST['text_com'])) != '') {
		$_POST['text_com'] = htmlspecialchars($_POST['text_com'], NULL , 'cp1251');
		if($u['admin'] == 0) {
			$_POST['text_com'] = substr($_POST['text_com'], 0, 2048);
		}
		$_POST['text_com'] = str_replace("\n",'<br>',$_POST['text_com']);
		mysql_query('INSERT INTO `events_news` (`login`,`level`,`align`,`clan`,`ip`,`city`,`cityreg`,`r`,`time`,`uid`,`title`,`text`,`comment`) VALUES (
												"'.$u['login'].'",
												"'.$u['level'].'",
												"'.$u['align'].'",
												"'.$u['clan'].'",
												"'.$u['ip'].'",
												"'.$u['city'].'",
												"'.$u['cityreg'].'",												
												"'.$pl['r'].'","'.time().'","'.$u['id'].'","","'.mysql_real_escape_string($_POST['text_com']).'","'.$pl['id'].'")');
		mysql_query('UPDATE `events_news` SET `comments` = `comments` + 1 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		$pl['comments']++;
		//header('location: http://xcombats.com/news/'.$pl['id'].'/#cmnt');
		//die('<meta http-equiv="refresh" content="0; URL=http://xcombats.com/news/'.$pl['id'].'/#cmnt">');
		if ($p==1) {
			$sp = mysql_query('SELECT * FROM `events_news` WHERE (`r` = "'.mysql_real_escape_string($p_my[0]).'" or `r` = "'.mysql_real_escape_string($p_my[1]).'") AND `delete` = "0" AND `comment` = "0" AND `id` = "'.mysql_real_escape_string((int)$_GET['st']).'" LIMIT 1');
		}elseif($p==2){
			$sp = mysql_query('SELECT * FROM `events_news` WHERE `r` = "'.mysql_real_escape_string($p).'" AND `delete` = "0" AND `comment` = "0" AND `id` = "'.mysql_real_escape_string((int)$_GET['st']).'" LIMIT 1');
		} else {
			$sp = mysql_query('SELECT * FROM `events_news` WHERE `delete` = "0" AND `comment` = "0" AND `id` = "'.mysql_real_escape_string((int)$_GET['st']).'" LIMIT 1');
		}
		$pl = mysql_fetch_array($sp);
	}
}
?>
 <table width="98%" border="0" cellpadding="0" cellspacing="0" align="center">
 <tr>
  <td>
   <table width="100%" height="29" border="0" cellpadding="0" cellspacing="0">
    <tr>
     <td width="8" height="29" style="background: url('http://xcombats.com/news_script/events/evn_news_03.gif') no-repeat;"></td>
     <td align="left" style="background: url('http://xcombats.com/news_script/events/evn_news_05.gif') repeat-x;">
		<div style="float: left;"><a style="color: #FFF; font-weight: bold;" href="http://xcombats.com/news/<?=$pl['id']?>/" rel="bookmark" id="editable_title<?=$pl['id']?>"<? if($u['admin']>0){ ?>contenteditable="true"<? } ?>><?=$pl['title']?></a></div> 
		<div style="float: right;"><a href="http://xcombats.com/news/<?=$pl['id']?>/" title="<?=date('H:i',$pl['time'])?>" rel="bookmark"><time class="entry-date" datetime="<?=date('Y',$pl['time'])?>-<?=date('m',$pl['time'])?>-<?=date('d',$pl['time'])?>T<?=date('h',$pl['time'])?>:<?=date('i',$pl['time'])?>:<?=date('s',$pl['time'])?>+00:00" pubdate><?=date('d.m.Y',$pl['time'])?></time></a></div>
		<? if($u['admin']>0) { ?>
        <div style="float: right;"><a href="http://xcombats.com/news/<?=$pl['id']?>/delete">Удалить</a> &nbsp; </div>
        <div style="float: right;"><a href="javascript:void(0)" onClick="save_new(<?=$pl['id']?>,0)">Сохранить</a> &nbsp; </div>
        <? } ?>
        <div style="clear: both;"></div>
     </td>
     <td width="9" style="background: url('http://xcombats.com/news_script/events/evn_news_07.gif') no-repeat;"></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr height="100%">
  <td bgcolor="#EDE9DA">
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>    
     <td width="1%" style="background: url('http://xcombats.com/news_script/events/evn_news_12.gif') repeat-y;"><img src="http://xcombats.com/news_script/events/evn_news_12.gif" width="8" height="100%"></td>
     <td width="100%" height="50" style="padding-left: 10px;">
		<p<? if($u['admin']>0){ ?> id="editable<?=$pl['id']?>" contenteditable="true"<? } ?>>
        <?=$pl['text']?>
        </p>
	 </td>
	 <td width="1%" style="background: url('http://xcombats.com/news_script/events/evn_news_13.gif') repeat-y;"><img src="http://xcombats.com/news_script/events/evn_news_13.gif" width="9" height="14"></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td>
   <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background: url('http://xcombats.com/news_script/events/evn_news_17.gif') repeat-x;">
    <tr>
     <td align="left" width="8"><img src="http://xcombats.com/news_script/events/evn_news_16.gif" width="8" height="24"></td>
	     <td width="100%">
			<div style="float: left;position: relative; top: -2px;">
						<span class="comments-link"><a href="http://xcombats.com/news/<?=$pl['id']?>/" title="Прокомментировать запись">Комментарии (<b><?=$pl['comments']?></b>)</a></span>						
			</div>
			<div style="float: right;"></div>
			<div style="clear: both;"></div>
		 </td>
     <td align="right" width="9"><img src="http://xcombats.com/news_script/events/evn_news_18.gif" width="9" height="24"></td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<? if($pl['comments'] > 20) { ?>
<br>
<center><a href="javascript:void(0)">Показать еще комментарии</a></center>
<? } ?>
<br>
<div id="ref_comments"></div>
<!-- -->
<? 
$pxc = ($pl['comments']-20);
if($pxc < 1) { $pxc = 0; }
$spc = mysql_query('SELECT * FROM `events_news` WHERE `comment` = "'.$pl['id'].'" AND `delete` = "0" ORDER BY `id` ASC LIMIT '.$pxc.' , 20');
while($plc = mysql_fetch_array($spc)) {
?>
	<table cellspacing="0" cellpadding="0" width="620" style="margin-left:30" align="center" border="0">
	<tbody>
	<tr>
	<td bgcolor="#e7dfc4">
		<table cellspacing="1" cellpadding="3" width="100%" border="0">
		<tbody>
		<tr>
		<td bgcolor="#f7f3e7">

			<table cellspacing="0" cellpadding="1" width="100%" border="0">
			<tbody>
			<tr>
			<td width="620">
						<script type="text/javascript">document.write(drw_wfl('<?=$plc['login']?>', <?=(0+$plc['align'])?>, 0, <?=(0+$plc['clan'])?>, <?=(0+$plc['level'])?>));</script>
                        <? if($u['admin'] > 0 || in_array($u['align'], $pal_al) || in_array($u['align'], $pal_al)) { ?>
        				<div style="float: right;"><a href="http://xcombats.com/news/<?=$pl['id']?>/delete/<?=$plc['id']?>">Удалить</a> &nbsp; </div>
        				<div style="float: right;"><a href="javascript:void(0)" onClick="save_new(<?=$plc['id']?>,<?=$pl['id']?>)">Сохранить</a> &nbsp; </div>
                        <? } ?>
            </td>
			<td align="right" style="color:#CFC59C"><center><?=date('d.m.Y H:i',$plc['time'])?></center></td></tr></tbody>
			</table>

		</td>
		</tr>
		<tr>
		<td class="normaltext" bgcolor="#fbfaf6">
		<p<? if($u['admin']>0){ ?> id="editable<?=$plc['id']?>" contenteditable="true"<? } ?>><?=$plc['text']?></p>		
		</td>
		</tr>		
		</tbody>
		</table>
	</td>
	</tr>
	</tbody>
</table>
<br />
<?
if($u['admin'] > 0 ) {
?>
<script>
CKEDITOR.disableAutoInline = true;
CKEDITOR.inline( 'editable<?=$plc['id']?>' );
</script>
<?
}
?>
<? } ?>
<!-- -->
<br>
<? if($add[0] == 1) { ?>
<form method="post" action="http://xcombats.com/news/<?=$_GET['st']?>/">
	<table cellspacing="0" cellpadding="0" width="550" align="center" border="0">
	<tbody>
	<tr>
	<td bgcolor="#e7dfc4">
		<table cellspacing="1" cellpadding="3" width="550" border="0">
		<tbody>
		<tr>
		<td bgcolor="#f7f3e7">Добавить комментарий</td>
		</tr>
		<tr>
		<td bgcolor="#fbfaf6"><textarea name="text_com" id="text_com" style="border:1px #EDE9DA solid;width:548px" rows="4"></textarea></td>
		</tr>
		<tr>
		  <td align="right" bgcolor="#fbfaf6" class="normaltext"><input type="submit" style="border:1px grey solid;" name="sendbt" id="sendbt" value="Отправить" /></td>
		  </tr>		
		</tbody>
		</table>
	</td>
	</tr>
	</tbody>
</table>
</form>
<? } else {
	if($add[0] == -1) { 
		echo '<br><center>Необходимо авторизироваться</center>';
	} elseif($add[0] == -2) {
		echo '<br><center>Вы заблокированы, либо на Вас наложено заклятие молчания.</center>';
	} elseif($add[0] == -3) {
		echo '<br><center>Задержка на отправку комментариев. (Осталось: менее одной минуты)</center>';
	} elseif($add[0] == -4) {
		echo '<br><center>Оставлять комментарии разрешено только персонажам достигшим 5-го уровня</center>';
	}
	
}?>
<a id="cmnt" name="cmnt"></a>
<?
if($u['admin'] > 0 ) {
?>
<script>
CKEDITOR.disableAutoInline = true;
CKEDITOR.inline( 'editable<?=$pl['id']?>' );
</script>
<?
}
?>
<br />
<? }else{ echo 'Статья не найдена.'; } ?>
<!-- pnews -->
<?
}elseif($p != 5 && $p != 6) {
	/*
	<table cellspacing="0" cellpadding="0" width="98%" align="center" border="0">
	<tbody>
	<tr>
	<td bgcolor="#e7dfc4">
		<table cellspacing="1" cellpadding="3" width="100%" border="0">
		<tbody>
		<tr>
		<td bgcolor="#f7f3e7">

			<table cellspacing="0" cellpadding="1" width="100%" border="0">
			<tbody>
			<tr>
			<td width="80%"><b>Текущие клановые войны</b></td>
			<td align="right">&nbsp;</td></tr></tbody>
			</table>

		</td>
		</tr>
		<tr>
		<td class="normaltext" bgcolor="#fbfaf6">
			<table width="100%">
				<tr>
					<td style="font-weight: bold;">Атака</td>
					<td style="font-weight: bold;">Оборона</td>
					<td style="font-weight: bold;">Дата окончания</td>
				</tr>
	 <tr><td>(K) <a class="cw_url" href="http://xcombats.com/k10" target="_blank"> Mercenaries [0]</a></td><td>(K) <a class="cw_url" href="http://xcombats.com/k3" target="_blank"> Devils [0]</a></td><td>12:55 07.01.2013</td></tr>		</table>
		</td>
		</tr>		
		
		</tbody>
		</table>
	</td>
	</tr>
	</tbody>
</table><br />
	*/
?> 
<!-- pnews -->
<?
//Новостная лента
//$sp = mysql_query('SELECT * FROM `events_news` WHERE `r` = "'.mysql_real_escape_string($p).'" AND `delete` = "0" AND `comment` = "0" ORDER BY `time` DESC LIMIT '.((int)(10*$pg)).' , 10');
if ($p==1) { 
	$sp = mysql_query('SELECT * FROM `events_news` WHERE (`r` = "'.mysql_real_escape_string($p_my[0]).'" or `r` = "'.mysql_real_escape_string($p_my[1]).'")  AND `delete` = "0" AND `comment` = "0" ORDER BY `time` DESC LIMIT '.((int)(10*$pg)).' , 10');
}elseif($p==2){ 
	$sp = mysql_query('SELECT * FROM `events_news` WHERE `r` = "'.mysql_real_escape_string($p).'"  AND `delete` = "0" AND `comment` = "0" ORDER BY `time` DESC LIMIT '.((int)(10*$pg)).' , 10');
} else {
	$sp = mysql_query('SELECT * FROM `events_news` WHERE `delete` = "0" AND `comment` = "0" ORDER BY `time` DESC LIMIT '.((int)(10*$pg)).' , 10');
} 
while($pl = mysql_fetch_array($sp)) {
?>
 <table width="98%" border="0" cellpadding="0" cellspacing="0" align="center">
 <tr>
  <td>
   <table width="100%" height="29" border="0" cellpadding="0" cellspacing="0">
    <tr>
     <td width="8" height="29" style="background: url('http://xcombats.com/news_script/events/evn_news_03.gif') no-repeat;"></td>
     <td align="left" style="background: url('http://xcombats.com/news_script/events/evn_news_05.gif') repeat-x;">
		<div style="float: left;"><a style="color: #FFF; font-weight: bold;" href="http://xcombats.com/news/<?=$pl['id']?>/" rel="bookmark" id="editable_title<?=$pl['id']?>"<? if($u['admin']>0){ ?>contenteditable="true"<? } ?>><?=$pl['title']?></a></div> 
		<div style="float: right;"><a href="http://xcombats.com/news/<?=$pl['id']?>/" title="<?=date('H:i',$pl['time'])?>" rel="bookmark"><time class="entry-date" datetime="<?=date('Y',$pl['time'])?>-<?=date('m',$pl['time'])?>-<?=date('d',$pl['time'])?>T<?=date('h',$pl['time'])?>:<?=date('i',$pl['time'])?>:<?=date('s',$pl['time'])?>+00:00" pubdate><?=date('d.m.Y',$pl['time'])?></time></a></div>
		<? if($u['admin']>0) { ?>
        <div style="float: right;"><a href="http://xcombats.com/news/p/<?=(0+$pg)?>/delete/<?=$pl['id']?>">Удалить</a> &nbsp; </div>
        <div style="float: right;"><a href="javascript:void(0)" onClick="save_new(<?=$pl['id']?>,0)">Сохранить</a> &nbsp; </div>
        <? } ?>
        <div style="clear: both;"></div>
     </td>
     <td width="9" style="background: url('http://xcombats.com/news_script/events/evn_news_07.gif') no-repeat;"></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr height="100%">
  <td bgcolor="#EDE9DA">
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>    
     <td width="1%" style="background: url('http://xcombats.com/news_script/events/evn_news_12.gif') repeat-y;"><img src="http://xcombats.com/news_script/events/evn_news_12.gif" width="8" height="100%"></td>
     <td width="100%" height="50" style="padding-left: 10px;">
		<p<? if($u['admin']>0){ ?> id="editable<?=$pl['id']?>" contenteditable="true"<? } ?>>
        <?=$pl['text']?>
        </p>
	 </td>
	 <td width="1%" style="background: url('http://xcombats.com/news_script/events/evn_news_13.gif') repeat-y;"><img src="http://xcombats.com/news_script/events/evn_news_13.gif" width="9" height="14"></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td>
   <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background: url('http://xcombats.com/news_script/events/evn_news_17.gif') repeat-x;">
    <tr>
     <td align="left" width="8"><img src="http://xcombats.com/news_script/events/evn_news_16.gif" width="8" height="24"></td>
	     <td width="100%">
			<div style="float: left;position: relative; top: -2px;">
						<span class="comments-link"><a href="http://xcombats.com/news/<?=$pl['id']?>/" title="Прокомментировать запись">Комментарии (<b><?=$pl['comments']?></b>)</a></span>						
			</div>
			<div style="float: right;"></div>
			<div style="clear: both;"></div>
		 </td>
     <td align="right" width="9"><img src="http://xcombats.com/news_script/events/evn_news_18.gif" width="9" height="24"></td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<?
if($u['admin'] > 0 ) {
?>
<script>
CKEDITOR.disableAutoInline = true;
CKEDITOR.inline( 'editable<?=$pl['id']?>' );
</script>
<?
}
?>
<br />
<? } ?>
<!-- pnews -->
<? } ?>
</div>
<?
if($p != 5 && $p != 6) {
$cnt = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `events_news` WHERE `r` = "'.mysql_real_escape_string($p).'" AND `delete` = "0" AND `comment` = "0"'));
?> 
  		<nav id="nav-below">			
            <div class="nav-previous"><? if($pg+1 < ceil($cnt[0]/10)) { ?><a href="http://xcombats.com/news_script/?paged=<?=($pg+1)?>&page_id=<?=$p?>"><span class="meta-nav">&larr;</span> Предыдущие записи</a><? } ?></div>            
			<div class="nav-next"><? if($pg > 0) { ?><a href="http://xcombats.com/news_script/?paged=<?=($pg-1)?>&page_id=<?=$p?>">Следующие записи <span class="meta-nav">&rarr;</span></a><? } ?></div>			
        </nav><!-- #nav-above -->
<? } ?>
		</div><!-- #content -->
		</div><!-- #primary -->
		<div id="secondary" class="widget-area" role="complementary">
        	<?
			  $cnt1 = strtotime(date('Y').'-'.date('m').'-'.date('d'));
			  $cnt1 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `online` > '.($cnt1).' AND `real` > 0 LIMIT 1'));
			  $cnt1 = $cnt1[0];
			  //
			  $cnt2 = time()-600;
			  $cnt2 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `online` > '.($cnt2).' LIMIT 1'));
			  $cnt2 = $cnt2[0];
			  //
			  $cnt3 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `real` > 0 LIMIT 1'));
			  $cnt3 = $cnt3[0];
			  //
			  $cnt4 = strtotime(date('Y').'-'.date('m').'-'.date('d'));
			  $cnt4 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `timereg` > '.($cnt4).' AND `real` > 0 LIMIT 1'));
			  $cnt4 = $cnt4[0];
			  //
			  $cnt5 = time()-3600;
			  $cnt5 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `timereg` > '.($cnt5).' AND `real` > 0 LIMIT 1'));
			  $cnt5 = $cnt5[0];
			  //
			?>
			<div id="archives-3" class="widget">
				<div style="width:200px;" class="widget-title">Статистика за <?=date('d.m.Y')?></div>
				<div style="padding:10px;">
                	Игроков за сегодня: <?=$cnt1?><br>
                    Игроков online: <?=$cnt2?><br>
                    <hr class="hr1">
                    Регистраций за сегодня: <?=$cnt4?><br>
                    Регистраций за час: <?=$cnt5?><br>
                    Регистраций всего: <?=$cnt3?><br>
                </div>
			</div>
            <?
			$mcd1 = mysql_fetch_array(mysql_query('SELECT * FROM `events_mini` WHERE `id` = 1 LIMIT 1'));
			$mcd2 = mysql_fetch_array(mysql_query('SELECT * FROM `events_mini` WHERE `id` = 2 LIMIT 1'));
			?>
			<div id="archives-3" class="widget">
				<div style="width:200px;" class="widget-title">Текущие работы<? if($u['admin']>0){ ?>&nbsp;<a href="javascript:void(0)" onClick="save_new(-1,0)">save</a><? } ?></div>
				<div style="padding:10px;" id="blockmini1" contenteditable="true"><?=$mcd1['text']?>
                </div>
			</div>
			<div id="archives-3" class="widget"><div style="width:200px;" class="widget-title">Планы на будущее<? if($u['admin']>0){ ?>&nbsp;<a href="javascript:void(0)" onClick="save_new(-2,0)">save</a><? } ?></div>
				<div style="padding:10px;" id="blockmini2" contenteditable="true"><?=$mcd2['text']?>
                </div>
			</div>
		</div>
    </div>
	
<?
if($p != 5 && $p != 6) {
?> 
		<div id="secondary" class="widget-area" style="display:none;" role="complementary">
			<div id="archives-3" class="widget widget_archive"><div class="widget-title">Архивы</div>
                <ul>
                    <li><a href='http://xcombats.com/news_script/?m=201301&page_id=<?=$p?>' title='Январь 2013'>Январь 2013</a></li>
                </ul>
        	</div>
        </div><!-- #secondary .widget-area -->
<? } ?>
		<div style="clear: both"></div>
	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">			
	
	</footer><!-- #colophon -->	
</div><!-- #page -->
<div style="clear: both;"></div>
<div style="max-width:1000px;color:#FF0;" id="footer">
<center>Новостная лента - Старый Бойцовский Клуб (XCombats) / xcombats.com &copy;</center>
</div>	
</body>
</html>