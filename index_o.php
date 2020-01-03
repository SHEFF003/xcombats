<?php
if($_SERVER['HTTP_HOST'] == '91.228.152.236' || $_SERVER['HTTP_HOST'] == '91.228.152.24') {
  die('Internet Server Error.');
}

include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

function  microLogin($id,$t,$nnz = 1) {
	$inf = $id;
	$id = $inf['id'];
	$r = '';
	$r .= '<a href="/inf.php?'.$inf['id'].'" target="_blank">'.$inf['login'].' ['.$inf['level'].']</a>';
	return $r;
}

if($_SERVER['HTTP_HOST'] != 'xcombats.com') {
  header('Location: ht'.'tp'.':/'.'/b'.'k'.'2'.'ru'.$_SERVER['REQUEST_URI'].'');
}

$page = 'index';
$big_top = '';

$url = $_SERVER['REDIRECT_URL'];
$url = explode('/', $url);

if($url[1] == 'lib') {
  $page = 'lib';
} elseif($url[1] == 'commerce') {
  $page = 'pay';
} elseif($url[1] == 'benediction') {
  $page = 'ben';
}

if($page != 'index') {
  $big_top = '_small';
}

if(isset($_GET['exit'])) {
  setcookie('login', '', time()-86400);
  setcookie('pass', '', time()-86400);
  header('Location: http://xcombats.com/');
  die();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Старый Бойцовский Клуб - Бесплатная, современная браузерная онлайн игра</title>
<meta property="og:image" content="http://www.xcombats.com/inx/image1.jpg"/>
<meta name="keywords" content="бойцовский клуб игра, бойцовский клуб играть, игра бойцовский клуб, старый бойцовский Клуб, бойцовский клуб онлайн игра, бойцовский клуб браузерная игра, играть в бойцовский клуб, игра бк, бк игра, старый бк, играть в бк, бк онлайн игра, новый бк, легендарный бойцовский клуб, легендарный бк, combats, комбатс, combats ru, combats com, OldBK, oldbk ru, oldbk com, олдбк, old bk, олд бк, mycombats, rebk, recombats, oldcombats, obk2">
<meta name="description" content="«Старый Бойцовский Клуб» – это бесплатная увлекательная онлайн игра, в которой сконцентрировано все самое лучшее от современных онлайн игр. В этой браузерной игре заложены самые интересные традиции всем известной онлайн игры под названием «Combats 2004-2009», которая, кстати, стала первооткрывателем всех браузерных игр.">
<meta name="wot-verification" content="cf732dbe0c27d0d30c26"/>
<link rel="stylesheet" href="/inx/main.css">
<script type="text/javascript" src="/js/jquery.js"></script>
<script>
function sk_ico1hover(type) {
  if(type == 1) {
	$('#sk_ico1d').fadeOut('slow');
	type = 2;
  } else {
	$('#sk_ico1d').fadeIn('slow');
	type = 1;
  }
  setTimeout('sk_ico1hover(' + type + ')', 1200);
}

function rtng(id) {
  var i = 1;
  while(i <= 4) {
	$('#rtng'+i+'i').removeClass('btninx_sel');
	$('#rblock'+i).css({'display':'none'});
	i++;
  }
  $('#rtng'+id+'i').addClass('btninx_sel');
  $('#rblock'+id).css({'display':''});
}
</script>
</head>

<body>
<!-- -->
<table width="742" style="padding-top:250px;" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    <!-- -->
    <small style="text-align:center">
    <div class="topmenu" style="width:540px;">
    <div>
    <div>
	<ul class="lcolomn reset" style="margin-left: 130px;">
    	<li style="position:relative;width:96px;"><a href="http://xcombats.com/register.php">Регистрация<img style="position:absolute;top:3px;left:-20px;" src="http://img.xcombats.com/1x1.gif" width="122" height="38" class="btn1inx"></a></li>
        <li><a href="http://xcombats.com/">Главная</a></li> 
        <li><a href="http://xcombats.com/lib/">Библиотека</a></li> 
        <li><a href="http://xcombats.com/lib/zakon/">Законы</a></li>
        <li><a href="http://xcombats.com/lib/polzovatelskoe-soglashenie/">Соглашение</a></li> 
        <li><a href="http://xcombats.com/news">Новости</a></li>
        <li><a href="http://xcombats.com/forum">Форум</a></li>
        <li><a href="http://xcombats.com/commerce/">Услуги</a></li>
        <li><a href="http://xcombats.com/top">Рейтинг</a></li>
        <li><a href="http://xcombats.com/">Поддержка</a></li>
	</ul>
    </div>
    </div>
    </div>
    </small>
    <!-- -->
    </td>
  </tr>
  <? if($page == 'index') { ?>
  <tr>
    <td class="topvik"></td>
  </tr>
  <? } ?>
  <tr>
    <td>
    <? if($page == 'ben') { include('pay/ben.php'); }elseif( $page == 'pay' ) { include('pay/main.php'); }elseif( $page == 'index' ) { ?>
    <table width="742" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="537" valign="top" class="mainvik" style="text-align:justify">        
        <h3>&laquo;Старый Бойцовский Клуб&raquo;: бесплатная ролевая онлайн игра</h3>
        <a href="/register.php" title="Начать игру!" style="position:relative">
        <img src="http://img.xcombats.com/1x1.gif" width="181" height="220" style="float:left;padding:10px;">
        <img class="sk_ico1" src="http://img.xcombats.com/1x1.gif" width="181" height="220" style="position:absolute;top:1px;left:-201px;padding:10px;">
        <img id="sk_ico1d" class="sk_ico1 sk_ico1hover" src="http://img.xcombats.com/1x1.gif" width="181" height="220" style="display:none;position:absolute;top:1px;left:-201px;padding:10px;">
        </a>
        Старый Бойцовский Клуб (СБК) - это бесплатная увлекательная онлайн игра, в которой сконцентрировано все самое лучшее от современных онлайн игр. В этой браузерной игре заложены самые интересные традиции всем известной онлайн игры под названием «Combats 2004-2008», которая, кстати, стала первооткрывателем всех браузерных игр.
        <br><br>
        В бесплатную браузерную игру версии вошли предыдущие стратегии и прибавились новые технические разработки, которые сделали эту mmorpg игру еще более увлекательной!
        <hr class="hr2"><br><br>
        <!--<center><br><h3>Новостная лента не подключена! Ожидайте новостей! </h3></center> -->
        <?
		$i = 0;
		$html = '';
		$sp = mysql_query('SELECT * FROM `events_news` WHERE `comment` = 0 AND `r` = 1 AND `delete` = 0 ORDER BY `id` DESC LIMIT 10');
		while( $pl = mysql_fetch_array($sp) ) {			
			if($i > 0) {
				$html .= '<br><br><hr class="hr2">';
			}
			$html .= '<div class="newsline"><b><span style="float: left;">'.$pl['title'].'</span><small style="float: right;">'.date('d-m-Y H:i',$pl['time']).'</small></b></div><br />'.$pl['text'];
			$html .= '<br /><br /><small style="float: left;"><a href="http://xcombats.com/news/'.$pl['id'].'" target="_blank"
			style="text-decoration: none;">[Комментарии ('.$pl['comments'].')]</a></small>';
			$i++;
		}
		if($html != '') {
			echo '<h3>Последние новости игры:</h3>';
		}
		echo '<div>'.$html.'</div>';
		?>      
        </td>
        <td valign="top">
        <!-- -->
        <table width="206" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" class="main2vik">
              <form style="position:relative" id="form_enter1" name="form_enter1" action="/enter.php" method="post">
              	<? if(date('d.m') == '09.05') { ?>
                <img style="position: absolute; top: -24px; left: 147px;" src="http://img.xcombats.com/1945.png" title="<?=(date('Y')-1945)?> лет спустя. Великая Победа Человечности, Жизни и Мира. ">
                <? } ?>
              	<center><h3>Войти в игру: &nbsp;</h3><hr class="hr1">
                <small>Логин персонажа:</small><br><input name="login" type="text" class="enter_fi1"><br>
                <small>Пароль:</small><br><input name="pass" type="password" class="enter_fi1"><br><br>
                </center>
                <div style="float:left">
                	<input style="display:none;" type="submit" value="Войти" />
                    <img onClick="$('#form_enter1').submit();" src="http://img.xcombats.com/1x1.gif" width="53" height="26" class="btn3inx">
                </div>
                <div style="float:right">
                	<img onclick="location.href='/register.php';" src="http://img.xcombats.com/1x1.gif" width="92" height="26" class="btn2inx">
                </div><br></form><br>
                <center>
                	<small><a href="/repass.php">Забыли пароль от перса?</a></small>
                </center>
              </td>
            </tr>
            <tr>
              <td class="main2vik2">&nbsp;</td>
            </tr>
          </table>
          <!-- -->
          <a href="http://xcombats.com/"><img title="Старый Бойцовский Клуб - СБК" class="bg1hvr milogo" src="http://img.xcombats.com/1x1.gif" width="199" height="47"></a>
          <br>
          <table width="206" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" class="main2viktop">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" class="main2vik">
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
              <center><h3>Статистика игры: &nbsp;</h3></center><hr class="hr1">
              <div>
				<small>
                	Игроков за сегодня: <?=$cnt1?><br>
                    Игроков онлайн: <?=$cnt2?><br>
                    <hr class="hr1">
                    Регистраций за сегодня: <?=$cnt4?><br>
                    Регистраций за час: <?=$cnt5?><br>
                    Регистраций всего: <?=$cnt3?><br>
                </small>
              </div>
			  </td>
            </tr>
            <tr>
              <td class="main2vik2">&nbsp;</td>
            </tr>
          </table>
          <br>
          <table width="206" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" class="main2viktop">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" class="main2vik">
              <center><h3>Игровые рейтинги: &nbsp;</h3></center><hr class="hr1">
              <center>
              	<img id="rtng1i" onClick="rtng(1);" title="Рейтинг персонажей" src="http://img.xcombats.com/1x1.gif" width="36" height="26" class="btn4inx btninx_sel"><img id="rtng2i" onClick="rtng(2);" title="Рейтинг кланов" src="http://img.xcombats.com/1x1.gif" width="36" height="26" class="btn5inx"><img id="rtng3i" onClick="rtng(3);" title="Рейтинг рефералов" src="http://img.xcombats.com/1x1.gif" width="36" height="26" class="btn6inx"><img id="rtng4i" onClick="rtng(4);" title="Рейтинг богачей" src="http://img.xcombats.com/1x1.gif" width="36" height="26" class="btn7inx">
              </center>
              <hr class="hr1">
              <div id="rblock1">
			  <?
				$r = '';
				
				//рейтинг персонажей
				$r = '';
				$i = 1;
				$j = 0;
				$sp = mysql_query('SELECT `id`,`uid`,`dmy`,`last` FROM `users_rating` ORDER BY `rating` DESC');
				while( $pl = mysql_fetch_array($sp) ) {
					$user = mysql_fetch_array(mysql_query('SELECT `u`.`id` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id`  WHERE `s`.`bot` = 0 AND `u`.`id` = "'.$pl['uid'].'" AND `u`.`pass` NOT LIKE "%saintlucia%" LIMIT 10'));
					if(!isset($user['id'])) {
						mysql_query('DELETE FROM `users_rating` WHERE `uid` = "'.$pl['uid'].'"');
					}else{
						if( $pl['dmy'] != date('dmY') ) {
							mysql_query('UPDATE `users_rating` SET `dmy` = "'.date('dmY').'",`last` = "'.($j+1).'",`last2` = "'.$pl['last'].'",`now` = `rating` WHERE `uid` = "'.$pl['uid'].'" LIMIT 1');
						}
						$j++;
					}
				}
				
				$p = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`sex`,`clan`,`align`,`city`,`cityreg` FROM `users` WHERE `id` = "'.mysql_real_escape_string($_GET['user']).'" LIMIT 1'));
				
				$rt_type = 'now';
				//if(isset($_GET['type']) && $_GET['type'] == 2) {
					$rt_type = 'rating';
				//}
				
				$sp = mysql_query('SELECT * FROM `users_rating` ORDER BY `'.$rt_type.'` DESC LIMIT 10');
				while( $pl = mysql_fetch_array($sp) ) {
					$user = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`level`,`u`.`login`,`u`.`align`,`u`.`clan` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id`  WHERE `s`.`bot` = 0 AND `u`.`id` = "'.$pl['uid'].'" AND `u`.`pass` NOT LIKE "%saintlucia%" LIMIT 1000'));
					if(!isset($user['id'])) {
						mysql_query('DELETE FROM `users_rating` WHERE `uid` = "'.$pl['uid'].'"');
					}else{
						$r .= '<tr>';
						//№
						$numb = '';
						$numbi = '';
						if($i != $pl['last'] && ($j+$pl['last']-$i) != 0 && ($pl['last']-$i+$j) != 0) {
							$numb .= '<sup>&nbsp;<small>';
							if($pl['last'] > $i) {
								$numbi .= '<img style="padding-bottom:4px;" src="http://img.xcombats.com/uprt2.png" width="7" height="7">';
								$numb .= '<font color=green><b>+'.($pl['last']-$i).'</b></font>';
							}else{
								$numbi .= '<img style="padding-bottom:4px;" src="http://img.xcombats.com/uprt.png" width="7" height="7">';
								$numb .= '<font color=maroon><b>'.($pl['last']-$i).'</b></font>';
							}
							$numb .= '</small></sup>';
						}
						$r .= '<td style="font-size:12px" align=center valign="top" class="mystrong"><small>&nbsp;'.$i.$numbi.'&nbsp;</small></td>';
						//login
						$r .= '<td align=left valign="top" class="mystrong">&nbsp;&nbsp;'.microLogin($user,1).'&nbsp;</td>';
						//
						$r .= '</tr>';	
					}
					$i++;
				}
				
				if($r == '') {
					$r = '<tr>';
					//№
					$r .= '<td align=right valign="top" class="mystrong"></td>';
					//login
					$r .= '<td align=center valign="top" class="mystrong"><small>К сожалению рейтинг пуст</small></td>';
					//рейтинг
					$r .= '<td align=right valign="top" class="mystrong"></td>';
					//
					$r .= '</tr>';	
				}
				
				echo '<small><table>'.$r.'</table></small>';
			  ?>
              </div>
              <div style="display:none" id="rblock2">
              	<small><center>Рейтинг пуст</center></small>
              </div>
              <div style="display:none" id="rblock3">
              	<small>
                <?
				$r = '';
				$i = 1;
				$j = 0;
				$sp = mysql_query('SELECT * FROM `users` WHERE `real` = 1 AND `admin` = 0 AND `align` != 2 AND `banned` = 0 ORDER BY `referals` DESC LIMIT 10');
				while( $pl = mysql_fetch_array($sp) ) {
					$r .= '<tr>';
					//№
					$numb = '';
					$numbi = '';
					$r .= '<td style="font-size:12px" align=center valign="top" class="mystrong"><small>&nbsp;'.$i.'&nbsp;</small></td>';
					//login
					$r .= '<td align=left valign="top" class="mystrong">&nbsp;&nbsp;'.microLogin($pl,1).'&nbsp;</td>';
					//
					$r .= '</tr>';	
					$i++;
				}
				
				if($r == '') {
					$r = '<tr>';
					//№
					$r .= '<td align=right valign="top" class="mystrong"></td>';
					//login
					$r .= '<td align=center valign="top" class="mystrong"><small>К сожалению рейтинг пуст</small></td>';
					//рейтинг
					$r .= '<td align=right valign="top" class="mystrong"></td>';
					//
					$r .= '</tr>';	
				}
				
				echo '<table>'.$r.'</table>';
				?>
                </small>
              </div>
              <div style="display:none" id="rblock4">
              	<small>
                <?
				//SELECT * FROM `users` WHERE `id` IN (SELECT `uid` FROM `bank` WHERE `moneyBuy` > 0 ORDER BY `moneyBuy` DESC) AND `admin` = 0 AND `real` = 1 LIMIT 10
				$r = '';
				$i = 1;
				$j = 0;
				$sp = mysql_query('SELECT `a`.* FROM `users` AS `a` LEFT JOIN `bank` AS `b` ON `b`.`uid` = `a`.`id` WHERE `real` = 1 AND `banned` = 0 AND `align` != 2 AND `admin` = 0 ORDER BY `b`.`moneyBuy` DESC LIMIT 10');
				while( $pl = mysql_fetch_array($sp) ) {
					$r .= '<tr>';
					//№
					$numb = '';
					$numbi = '';
					$r .= '<td style="font-size:12px" align=center valign="top" class="mystrong"><small>&nbsp;'.$i.'&nbsp;</small></td>';
					//login
					$r .= '<td align=left valign="top" class="mystrong">&nbsp;&nbsp;'.microLogin($pl,1).'&nbsp;</td>';
					//
					$r .= '</tr>';	
					$i++;
				}
				
				if($r == '') {
					$r = '<tr>';
					//№
					$r .= '<td align=right valign="top" class="mystrong"></td>';
					//login
					$r .= '<td align=center valign="top" class="mystrong"><small>К сожалению рейтинг пуст</small></td>';
					//рейтинг
					$r .= '<td align=right valign="top" class="mystrong"></td>';
					//
					$r .= '</tr>';	
				}
				
				echo '<table>'.$r.'</table>';
				?>
                </small>
              </div>
			  </td>
            </tr>
            <tr>
              <td class="main2vik2">&nbsp;</td>
            </tr>
          </table>
          <br>
          <table width="206" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" class="main2viktop">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" class="main2vik">
              <center><h3>Социальные сети: &nbsp;</h3></center><hr class="hr1">
                <ul id="social-links">
                    <li>
                        <script type="text/javascript" src="http://vkontakte.ru/js/api/share.js?5" charset="windows-1251"></script>
                        <script type="text/javascript">
                            document.write(VK.Share.button(false,{type: "round", text: "В Контакте", image: "http://xcombats.com/inx/image1.jpg"}));
                        </script>
                    </li>
                    <li>
                        <a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php"></a>
                        <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
                    </li>
                    <li>
                        <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru">Твитнуть</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </li>
                    <li>
                        <link href="http://stg.odnoklassniki.ru/share/odkl_share.css" rel="stylesheet" />
                        <script src="http://stg.odnoklassniki.ru/share/odkl_share.js" type="text/javascript" ></script>
                        <a class="odkl-klass-stat" href="http://xcombats.com" onclick="ODKL.Share(this);return false;" ><span>0</span></a>
                        <script type="text/javascript">
                            jQuery(function() {
                                ODKL.init();
                            });
                            jQuery('.odkl-klass-stat').attr('href', location.href);
                        </script>
                    </li>
                </ul>
                
			  </td>
            </tr>
            <tr>
              <td class="main2vik2">&nbsp;</td>
            </tr>
          </table>
          <br>
          <!-- -->
          </td>
      </tr>
    </table>
    <? }else{ ?>
    <script src="/inx/ckeditor/ckeditor.js"></script>
    <div class="topvik3" style="width:533px;margin-left:206px;">&nbsp;</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="206" valign="top">
        <table width="206" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="main2viktop">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" class="main2vik"><center>
              <h3>Библиотека</h3>
              <?
				$sp = mysql_query('SELECT * FROM `library_menu` WHERE `delete` = 0 ORDER BY `position` ASC');
				while($pl = mysql_fetch_array($sp)) {
					$pl['url'] = str_replace('library','lib',$pl['url']);
					if( $pl['type'] == 0 ) {
						$lib['html'] .= '<span class="lib-title">'.$pl['name'].'</span>';
						$lib['px'] += 33;
					}else{
						$lib['html'] .= '<a href="http://xcombats.com'.$pl['url'].'" class="lib-rgo">&bull; '.$pl['name'].'</a>';
						$lib['px'] += 23;
					}
				}
				echo $lib['html'];
			  ?>
			</td>
          </tr>
          <tr>
            <td class="main2vik2">&nbsp;</td>
          </tr>
        </table></td>
        <td valign="top" class="mainvik2" style="text-align:justify">
<?
if( !isset($url[2]) || $url[2] == '' ) {
	$url[2] = 'home';
}

if( $url[2] == 'upload' ) {
	
	$html = '';
	
	if( $u->info['activ'] == 1 ) {
		$html = 'Чтобы начать публиковать изображения - Активируйте Вашего персонажа.';
	}elseif( $u->info['molch1'] > time() ) {
		$html = 'Персонажи с молчанкой не могут публиковать изображения.';
	}elseif( $u->info['banned'] > 0 ) {
		$html = 'Заблокированные персонажи не могут публиковать изображения.';
	}elseif( $u->info['align'] == 2 ) {
		$html = 'Хаосники не могут публиковать изображения.';
	}elseif( !isset($u->info['id']) ) {
		$html = '<center><br>Загружать изображения могут только зарегистрированные пользователи</center>';
	}elseif( ($url[3] == 'me' || ($url[3] == 'all' && $u->info['admin'] > 0)) ) {
		if( $url[3] == 'me' ) {
			$sp = mysql_query('SELECT * FROM `upload_images` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1000');
		}elseif( $url[3] == 'all' ) {
			$sp = mysql_query('SELECT * FROM `upload_images` ORDER BY `id` DESC LIMIT 1000');
		}
		$html .= '<b>Левая Кнопка Мыши</b> - Открыть изображение в новом окне<br>
				  <b>Правая Кнопка Маши</b> - Удалить изображение с сервера<hr>';
		$i = 0;
		$usrs = array();
		while($pl = mysql_fetch_array($sp)) {
			if( $url[4] == 'delete' && $url[5] == $pl['id'] ) {
				unlink('ui/'.$pl['img'].'.'.$pl['type'].'');
				mysql_query('DELETE FROM `upload_images` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			}else{
				if( !isset($usrs[$pl['uid']]) ) {
					$usrs[$pl['uid']] = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
					if(!isset($usrs[$pl['uid']]['id'])) {
						$usrs[$pl['uid']]['login'] = '!НЕТ АВТОРА!';
					}
					$usrs[$pl['uid']] = $usrs[$pl['uid']]['login'];
				}
				$html .= '<a title="'.$usrs[$pl['uid']]."\n".date('d.m.Y H:i',$pl['time']).'" oncontextmenu="if(confirm(\'Вы уверены?\')){ top.location=\'http://xcombats.com/lib/upload/'.htmlspecialchars($url[3],NULL,'cp1251').'/delete/'.$pl['id'].'/\'; }return false;" target="_blank" href="http://xcombats.com/ui/'.$pl['img'].'.'.$pl['type'].'"><img src="http://xcombats.com/ui/'.$pl['img'].'.'.$pl['type'].'" class="imgo"></a>';
			}
			$i++;
		}
		if( $i == 0 ) {
			$html .= 'Нет загруженных изображений на сервере';
		}
	}else{
		
		 if( isset($_FILES['filename']) ) {
			 include('html/class.upload.php');
			 $handle = new upload($_FILES['filename']);
			 $count = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.(time()-60).' LIMIT 1'));
			 if( $count > 3 && $u->info['admin'] == 0 ) {
				 $html = 'Вы не можете так часто заливать изображения на сервер';
			 }elseif ($handle->uploaded) {
				
				if( $handle->file_src_name_ext == 'png' || $handle->file_src_name_ext == 'jpg' || $handle->file_src_name_ext == 'gif' ) {
					$fname = 'u'.$u->info['id'].'_'.time();	
					$handle->file_new_name_body = $fname;	
					
					$handle->image_convert         = $handle->file_src_name_ext;
					
					/*
					$handle->image_unsharp         = true;
					$handle->image_border          = '0 0 0 0';
					$handle->image_border_color    = '#000000';
					$handle->image_text            = "";
					$handle->image_text_font       = 2;
					$handle->image_text_position   = 'B';
					$handle->image_text_padding_y  = 2;
					*/
					
					if( $u->info['admin'] == 0 ) {
						$handle->image_max_width  = 1800;
						$handle->image_max_height = 1800;
						$handle->src_size_mb 	  = 5;
					}
							
					$handle->process('ui/');				
					if ($handle->processed) 
					{
						mysql_query('INSERT INTO `upload_images` (`uid`,`time`,`img`,`type`) VALUES (
							"'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($fname).'","'.mysql_real_escape_string($handle->file_src_name_ext).'"
						) ');
						$html .= 'Файл <a href="http://xcombats.com/ui/'.$fname.'.'.$handle->file_src_name_ext.'" target="_blank">http://xcombats.com/ui/'.$fname.'.'.$handle->file_src_name_ext.'</a> был успешно загружен.';

						$handle->clean();
					} else {
						$html .= 'Возникла ошибка при добавлении файла.';
					}
				}else{
					$html .= 'Возникла ошибка при добавлении файла!';
				}
			}
		 }else{		
			 $html .= '
			  <br>
			  <b>Правила публикации изображений:</b><br>
			  &bull; Изображение не должно нарушать правила проекта<br>
			  &bull; Изображение должно весить не более 1000 Кб<br>
			  &bull; Изображение не должно быть более 800px х 800px<br>
			  &bull; Форматы изображения: JPEG , GIF , PNG<hr>
			  <center>
			  <form action="http://xcombats.com/lib/upload/" method="post" enctype="multipart/form-data">
			  <input type="file" name="filename"> 
			  <input class="btn2" type="submit" value="Загрузить"><hr>';
			  
			  if( $u->info['admin'] > 0 ) {
				 $html .= '<a href="http://xcombats.com/lib/upload/all/">[ Все изображения ]</a> ';
			  }
			  $html .= '<a href="http://xcombats.com/lib/upload/me/">[ Мои изображения ]</a>';
			  
			  $html .= '</form>
			  </center>';
		 }
		
	}
	echo '<div style="padding-left:20px;padding-top:20px;"><h3>Загрузка изображений</h3>'.$html.'</div>';
}elseif( $url[2] == 'list' ) {
	$sp = mysql_query('SELECT * FROM `library_content` WHERE `delete` = 0 AND `moder` = 0 AND `uid` > 0 ORDER BY `id` ASC');
	$html = '';
	$i = 1;
	while($pl = mysql_fetch_array($sp)) {
		$html .= '<a target="_blank" href="http://xcombats.com/lib/'.$pl['url_name'].'/">&gt;&gt; '.$pl['title'].'</a><br>Автор: '.$u->microLogin($pl['uid'],1).' / Дата публикации: '.date('d.m.Y',$pl['time']).'<hr>';
		$i++;
	}
	if( $html == '' ) {
		$html = 'В настоящий момент непроверенных статей нет.<br>
		<br>Если Вы хотите написать свою статью - <a target="_blank" href="http://xcombats.com/lib/new/">http://xcombats.com/lib/new/</a><br>
		<br>Более подробная информация - <a href="http://xcombats.com/lib/public/">http://xcombats.com/lib/public/</a>';
	}
	echo '<div style="padding-left:20px;padding-top:20px;"><h3>Список непроверенных статей:</h3>'.$html.'</div>';
}elseif( $url[2] == 'new' && !isset($u->info['id']) ) {
	echo '<div style="padding:50px;">Для публикации статьи Вы должны авторизироваться своим персонажем.<br><b>Гостям</b> данный раздел недоступен.</div>';
}elseif( $url[2] == 'new' && isset($u->info['id']) ) {
?>
<!-- -->
<script src="/inx/ckeditor/ckeditor.js"></script>
<!-- -->
<div class="lib-txt-title">Публикация статьи</div>
<div class="lib-txt">
<?
if(isset($_POST['save']) && isset($u->info['id'])) {
	$_POST['lib_title'] = htmlspecialchars($_POST['lib_title'],NULL,'cp1251');
	$mbpage_last = mysql_fetch_array(mysql_query('SELECT `time` FROM `library_content` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
	
	if( $u->info['activ'] == 1 ) {
		echo 'Чтобы начать публиковать статьи - Активируйте Вашего персонажа.';
	}elseif( $u->info['molch1'] > time() ) {
		echo 'Персонажи с молчанкой не могут публиковать статьи.';
	}elseif( $u->info['banned'] > 0 ) {
		echo 'Заблокированные персонажи не могут публиковать статьи.';
	}elseif( $u->info['align'] == 2 ) {
		echo 'Хаосники не могут публиковать статьи.';
	}elseif( isset($mbpage_last['time']) && $mbpage_last['time'] > time() - 3600 && $u->info['admin'] == 0 ) {
		echo 'Нельзя публиковать статьи чаще одного раза в час.<br>Вы можете опубликовать статью через <b>'.$u->timeOut(($mbpage_last['time']+3600-time())).'</b>.';
	}elseif( isset($_POST['hide_id']) ) {
		$mbpage = mysql_fetch_array(mysql_query('SELECT * FROM `library_content` WHERE `url_name` = "'.mysql_real_escape_string($_POST['hide_id']).'" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
		if(isset($mbpage['id'])) {
			if(isset($mbpage['id']) && ($mbpage['uid'] == $u->info['id'] || $u->info['admin'] > 0) && ($mbpage['moder'] == 0 || $u->info['admin'] > 0) ) {
				mysql_query('UPDATE `library_content` SET `time` = "'.time().'",`title` = "'.mysql_real_escape_string($_POST['lib_title']).'",`text` = "'.mysql_real_escape_string($_POST['con_text']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
				$sid = $mbpage['id'];
				if( $sid > 0 ) {
?>
		<b>Уважаем<? if( $u->info['sex'] == 0 ) { echo 'ый'; }else{ echo 'ая'; } ?></b> <?=$u->info['login']?>, благодарим Вас за дополнение статьи!<br />
		<br />
		Название статьи: &quot;<b><?=$_POST['lib_title']?></b>&quot;<br />
		Ссылка для просмотра: <a target="_blank" href="http://xcombats.com/lib/<?=$mbpage['url_name']?>/">http://xcombats.com/lib/<?=$mbpage['url_name']?>/</a>
		<hr />
		Мы ценим проделанную Вами работу и постараемся как можно скорее рассмотреть заявку на добавление данной статьи
		<br /><br /><br /><br /><br /><br /><br /><br />
		, с уважением<br />
		Администрация Старого Бойцовского Клуба.
<?
				}else{
					echo 'Произошла ошибка изменения статьи.';
				}
			}else{
				echo 'Произошла ошибка изменения статьи!<br><b>Статья не найдена, либо у Вас нет прав для её изменения.</b>';
			}
		}else{
			echo 'Произошла ошибка изменения статьи.<br><b>Статья не найдена, либо у Вас нет прав для её изменения.</b>';
		}
	}else{
		$sid = 0;
		mysql_query('INSERT INTO `library_content` (`type`,`uid`,`time`,`title`,`url_name`,`text`) VALUES (
			"0","'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($_POST['lib_title']).'","id'.time().'","'.mysql_real_escape_string($_POST['con_text']).'"
		)');
		$sid = mysql_insert_id();
		if($sid > 0) {
			mysql_query('UPDATE `library_content` SET `url_name` = "id'.$sid.'" WHERE `id` = "'.$sid.'" LIMIT 1');
	?>
		<b>Уважаем<? if( $u->info['sex'] == 0 ) { echo 'ый'; }else{ echo 'ая'; } ?></b> <?=$u->info['login']?>, благодарим Вас за добавление статьи!<br />
		<br />
		Название статьи: &quot;<b><?=$_POST['lib_title']?></b>&quot;<br />
		Номер Вашей статьи: #<?=$sid?><br />
		Ссылка для просмотра: <a target="_blank" href="http://xcombats.com/lib/id<?=$sid?>/">http://xcombats.com/lib/id<?=$sid?>/</a>
		<hr />
		Мы ценим проделанную Вами работу и постараемся как можно скорее рассмотреть заявку на добавление данной статьи
		<br /><br /><br /><br /><br /><br /><br /><br />
		, с уважением<br />
		Администрация Старого Бойцовского Клуба.
	<?
		}else{
			echo 'Произошла ошибка добавления статьи.<br><b>Обратитесь к Администрации!</b>';
		}
	}
}else{
	if( isset($url[3]) && $url[3] != '' ) {
		$mbpage = mysql_fetch_array(mysql_query('SELECT * FROM `library_content` WHERE `url_name` = "'.mysql_real_escape_string($url[3]).'" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
		if(isset($mbpage['id']) && ($mbpage['uid'] == $u->info['id'] || $u->info['admin'] > 0) && ($mbpage['moder'] == 0 || $u->info['admin'] > 0) ) {
			//all okey
		}else{
			unset($mbpage);
			echo '<div align="center" style="background-color:#e8b8b8;border:1px solid #b93939;color:#b93939;padding:5px;"><small>';
			echo 'Данная статья не найдена. Либо у Вас нет правд для её редактирования.';
			echo '</small></div>';
		}
	}
?>
<form method="post" action="http://xcombats.com/lib/new/<?=$mbpage['url_name']?>">
<?
if( isset($mbpage['id']) ) {
	
	if( isset($_POST['save2']) ) {
		//Изменения
		if( $u->info['admin'] > 0 ) {
			$red500 = false;
			if( isset($_POST['lib_urlname']) && $_POST['lib_urlname'] != '' && $_POST['lib_urlname'] != $mbpage['url_name'] ) {
				mysql_query('UPDATE `library_content` SET `delete` = "'.time().'" WHERE `url_name` = "'.$mbpage['url_name'].'" AND `id` != "'.$mbpage['id'].'"');
				$mbpage['url_name'] = htmlspecialchars($_POST['lib_urlname'],NULL,'cp1251');
				mysql_query('UPDATE `library_content` SET `url_name` = "'.mysql_real_escape_string($mbpage['url_name']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
				$red500 = true;
			}
			if( isset($_POST['lib_prov']) && $_POST['lib_prov'] == '1' && $_POST['lib_prov'] != '' && $_POST['lib_prov'] != '0') {
				$mbpage['moder2'] = $u->info['id'];
			}else{
				$mbpage['moder2'] = 0;
			}
			if( $mbpage['moder2'] != $mbpage['moder'] ) {
				mysql_query('UPDATE `library_content` SET `delete` = "'.time().'" WHERE `url_name` = "'.$mbpage['url_name'].'" AND `id` != "'.$mbpage['id'].'"');
				mysql_query('UPDATE `library_content` SET `moder` = "'.mysql_real_escape_string($mbpage['moder2']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
				$mbpage['moder'] = $mbpage['moder2'];
			}
			if( $red500 == true ) {
				echo '<script>top.location.href="http://xcombats.com/lib/new/'.$mbpage['url_name'].'/"</script>';
			}
		}
		if( $u->info['admin'] > 0 || $u->info['id'] == $mbpage['uid'] ) {
			if( $_POST['lib_title'] != $mbpage['title'] || $_POST['con_text'] != $mbpage['text'] ) {
				$mbpage['title'] = $_POST['lib_title'];
				$mbpage['text'] = $_POST['con_text'];
				$mbpage['time'] = time();
				mysql_query('UPDATE `library_content` SET `time` = "'.time().'",`title` = "'.mysql_real_escape_string(htmlspecialchars($mbpage['title'],NULL,'cp1251')).'",`text` = "'.mysql_real_escape_string($mbpage['text']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
			}
		}
	}
	
	?>
    <input name="hide_id" value="<?=$mbpage['url_name']?>" type="hidden" />
    <?
}
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150">Название статьи:</td>
              <td><input style="width:350px;" name="lib_title" type="text" id="lib_title" maxlength="100" value="<?=$mbpage['title']?>" /></td>
            </tr>
          <?
		  if( $u->info['admin'] > 0 ) { 
		  ?>
            <tr>
              <td width="150">URL-NAME:</td>
              <td><input style="width:350px;" name="lib_urlname" type="text" id="lib_urlname" maxlength="100" value="<?=$mbpage['url_name']?>" /></td>
            </tr>
            <tr>
              <td width="150">Проверенная статья:</td>
              <td><input type="checkbox" <? if($mbpage['moder'] > 0){ echo 'checked="checked"'; } ?> name="lib_prov" id="lib_prov" value="1" /> <?
              if($mbpage['moder']>0) {
				 echo $u->microLogin($mbpage['moder'],1); 
			  }
			  ?></td>
            </tr>
          <?
		  }
		  ?>
          </table></td>
    </tr>
    <tr>
      <td>
        <div style="padding:10px;width:480px;border:1px solid black;">
        	<textarea class="w100p" name="con_text" id="con_text" cols="45" rows="5">
            <?=$mbpage['text']?>
            </textarea>
        </div>
      </td>
    </tr>
    <tr>
      <td><table width="98%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="150">Дата публикации:</td>
          <td>
		  <? 
		  if(isset($mbpage['id'])) { echo date('d.m.Y',$mbpage['time']); }else{ echo date('d.m.Y'); }
		  if($u->info['admin'] > 0 || $u->info['id'] == $mbpage['uid']) {
		  ?>
          <button name="save2" type="submit" style="float:right">Сохранить</button>
          <? } ?>
          <button name="save" type="submit" style="float:right">Опубликовать</button>
          </td>
        </tr>
        <tr>
          <td>Автор:</td>
          <td><?=$u->microLogin($mbpage['uid'],1)?></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<script>
	CKEDITOR.inline( 'con_text' );
</script>
<?
	}
?>
</div>
<?
}else{
	/*
	echo '<script>function bigimg(obj){ alert($(\'aid\'+obj).html(\'href\')); }</script>';
	*/
	$pl = mysql_fetch_array(mysql_query('SELECT * FROM `library_content` WHERE `url_name` = "'.mysql_real_escape_string($url[2]).'" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
	if( isset($pl['id']) && $url[3] == 'delete' && $u->info['admin'] > 0) {
		mysql_query('UPDATE `library_content` SET `delete` = "'.time().'" WHERE `url_name` = "'.mysql_real_escape_string($url[2]).'"');
		unset($pl);
	}
	if( isset($pl['id']) ) {
		$pl['text'] = str_replace('combatz.ru','origina;combats.com',$pl['text']);
		$pl['text'] = str_replace('combatz','СБК',$pl['text']);
		$pl['text'] = str_replace('CombatZ','СБК',$pl['text']);
		if($pl['moder'] == 0) {
			echo '<div align="center" style="background-color:#e8b8b8;border:1px solid #b93939;color:#b93939;padding:5px;"><small>Данная статья не прошла проверку и информация на ней не подтверждена!</small></div>';
		}
		echo '<div class="lib-txt-title"><h3>'.$pl['title'].'</h3></div><div class="lib-txt">'.$pl['text'].'</div>';
		if($pl['uid'] > 0 || $u->info['admin'] > 0) {
			echo '<hr><small><div> &nbsp; Дата публицации: '.date('d.m.Y',$pl['time']).' &nbsp; / &nbsp; Автор: '.$u->microLogin($pl['uid'],1).'';
			if($pl['uid'] == $u->info['id'] || $u->info['admin'] > 0) {
				echo ' &nbsp; / &nbsp; <a target="_blank" href="http://xcombats.com/lib/new/'.$pl['url_name'].'/">Изменить</a>';
				if( $u->info['admin'] > 0 ) {
					echo ' &nbsp; / &nbsp; <a href="http://xcombats.com/lib/'.$pl['url_name'].'/delete/">Удалить</a>';
				}
			}
			echo '</div></small>';
		}
	}else{
		echo '<div align="center" style="background-color:#e8b8b8;border:1px solid #b93939;color:#b93939;padding:5px;"><small>Статья не найдена. Скорее всего она была удалена, либо еще не создана.</small></div>';
	}
}
?>
        </td>
      </tr>
    </table>
    <div class="btmvik2" style="width:535px;margin-left:206px;">&nbsp;</div>
	<? } ?>
    </td>
  </tr>
  <? if( $page == 'index' ) { ?>
  <tr>
    <td class="btmvik"></td>
  </tr>
  <? } ?>
  <tr>
    <td>
    <div class="footer" style="text-align:justify;<? if( $page == 'pay' || $page == 'ben' ) { echo 'width:690px;'; }elseif( $page != 'index' ) { echo 'margin-left:212px;'; } ?>">
    	<small>
        <img class="o18i" src="http://img.xcombats.com/1x1.gif" width="64" height="64">
        Бесплатная MMORPG xcombats.com (Старый Бойцовский Клуб - СБК) Лучший проект в своем роде с отличной боевой системой и множеством интересных заданий, загадок и тайн. Наш мир ждет Вас!<br><b><center>Наш проект значительно отличается от других!</center></b><hr class="hr1">
        Многие online игры предлагают поиграть бесплатно, а потом ставят в невыносимые условия, в &laquo;СБК&raquo; каждый сам делает свой выбор, здесь важно то как и с кем ты сражаешься, а не размер кошелька. <br>
        <u>Внимание! Сайт использует Cookies.</u>
    	</small>
    </div>
    </td>
  </tr>
</table>
<!-- -->
<div align="center">
<div style="width:680px; color:#777; display:inline-block; text-align:justify;" align="rig">
    	<small>
        Счетчики:         <!-- Top.Roleplay.Ru -->
        <script type="text/javascript" language="javascript">
        var topRPGc="<img src='http://s01.rpgtop.su/cgi-bin-mod/iv.cgi?a=ins&id=23535&rnd=" + Math.random();
        topRPGc += "&r="+escape(document.referrer)+"' width='1' height='1' border='0'><a href='http://top.roleplay.ru/23535' title='Рейтинг Ролевых Ресурсов - RPG TOP' target='top_'>"+
        "<img style='display:inline-block; vertical-align:bottom;' src='http://img.rpgtop.su/top8015_10.gif' alt='Рейтинг Ролевых Ресурсов - RPG TOP' border='0' width='80' height='15'></a> ";
        document.write(topRPGc);
        </script>
        <noscript>
        <img style="display:inline-block; vertical-align:bottom;" src='http://s01.rpgtop.su/cgi-bin-mod/iv.cgi?a=ins&id=23535' width='1' height='1' border='0'><a href='http://top.roleplay.ru/23535' target='_top'><img src='http://img.rpgtop.su/top8015_10.gif' alt='Рейтинг Ролевых Ресурсов - RPG TOP' border='0' width='80' height='15'></a>
        </noscript>
        <!-- /Top.Roleplay.Ru -->
        <!--LiveInternet counter--><script type="text/javascript"><!--
        document.write("<a href='//www.liveinternet.ru/click' "+
        "target=_blank><img style='display:inline-block; vertical-align:bottom;' src='//counter.yadro.ru/hit?t25.10;r"+
        escape(document.referrer)+((typeof(screen)=="undefined")?"":
        ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
        screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
        ";"+Math.random()+
        "' alt='' title='LiveInternet: показано число посетителей за"+
        " сегодня' "+
        "border='0' width='88' height='15'><\/a>")
        //--></script><!--/LiveInternet-->
        <!-- Rating@Mail.ru counter -->
        <script type="text/javascript">
        var _tmr = _tmr || [];
        _tmr.push({id: "2658385", type: "pageView", start: (new Date()).getTime()});
        (function (d, w, id) {
          if (d.getElementById(id)) return;
          var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
          ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
          var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
          if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
        })(document, window, "topmailru-code");
        </script><noscript><div style="position:absolute;left:-10000px;">
        <img src="//top-fwz1.mail.ru/counter?id=2658385;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
        </div></noscript>
        <!-- //Rating@Mail.ru counter -->
        <!-- Rating@Mail.ru logo -->
        <a href="http://top.mail.ru/jump?from=2658385">
        <img src="//top-fwz1.mail.ru/counter?id=2658385;t=317;l=1" 
        style="border:0;dispaly:inline-block; vertical-align:bottom;" height="15" width="88" alt="Рейтинг@Mail.ru" /></a>
        <!-- //Rating@Mail.ru logo -->
        <br>
        По всем вопросам и предложениям обращайтесь в раздел <a href="#">поддержки</a> или по e-mail: <a href="mailto:support@xcombats.com">support@xcombats.com</a><br>
        <center>
        	Бесплатная браузерная онлайн игра Старый Бойцовский Клуб &copy; <?=date('Y')?><br><br>
        </center>
        </small>
</div>
</div>
<script>
sk_ico1hover(2);
</script>
</body>
</html>