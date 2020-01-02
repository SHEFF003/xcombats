<?

die();

$sd4 = 'admin';
$psw = md5('tip:'.$_SERVER['REMOTE_ADDR'].'t'.date('dh',time()).'t'.$sd4);
$psw = $psw[7].$psw[3].$psw[0].$psw[1].$psw[5];
$auth = false;

$_POST['psw'] = $psw;

if(isset($_COOKIE['pass3']) && $_COOKIE['pass3']==$psw){
	$auth = true;
}
if(isset($_GET['code'])){
	$tpsw = md5('tip:'.$_SERVER['REMOTE_ADDR'].'t'.$_GET['code'].'t'.$sd4);
	$tpsw = $tpsw[7].$tpsw[3].$tpsw[0].$tpsw[1].$tpsw[5];
	die($tpsw);
}elseif(isset($_POST['psw'])){
	if($_POST['psw']==$psw)	{
		setcookie('pass3',$_POST['psw'],time()+36000);
		$_COOKIE['pass3'] = $_POST['psw'];
		$auth = true;
	}
}elseif(isset($_GET['exit'])){
	if($_COOKIE['pass3']==$psw){
		setcookie('pass3',false,time()-3600);
		unset($_COOKIE['pass3']);
		$auth = false;
	}
}

include_once('../_incl_data/__config.php');
define('GAME',true);
include_once('../_incl_data/class/__db_connect.php');
include_once('../_incl_data/class/__user.php');
if($u->info['admin']==0){
	header('location: http://xcombats.com/');
	die('');
}

if(!isset($_GET['ajax'])) {
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<title>Центр управления "Adminion"</title>
<link href="http://<?=$c['img']?>/css/main.css" rel="stylesheet" type="text/css">
<style>
.tblbr2 {
	border-left:1px solid #AEAFAE;
	border-top:1px solid #AEAFAE;
	border-bottom:1px solid #EEEFEE;
	border-right:1px solid #EEEFEE;
}
.tblbr {
	border-left:1px solid #EEEFEE;
	border-top:1px solid #EEEFEE;
	border-bottom:1px solid #AEAFAE;
	border-right:1px solid #AEAFAE;
}
.стиль1 {border-left: 1px solid #AEAFAE; border-top: 1px solid #AEAFAE; border-bottom: 1px solid #EEEFEE; border-right: 1px solid #EEEFEE; font-size: 12px; }
.стиль2 {
	font-size: 12px;
	color: #999999;
}
.стиль5 {font-size: 12px}
.test a {
	font-weight: normal;	
}
</style>
<script src="http://<? echo $c['img']; ?>/js/jx/jquery.js" type="text/javascript"></script>
</head>
<body style="padding-top:0px; margin-top:2px; background-color:#dedfde;">
<table class="tblbr" width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="стиль1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Adminion v0.0.0
        <? if($auth==true){
	$la = sys_getloadavg();
	$la[0]=$la[0]/4;
	$la[1]=$la[1]/4;
	$la[2]=$la[2]/4;
		?>
         / Время сервера: <?=date('H:i')?> ( <?=time()?> ) / <? 
		 	echo "Нагрузка: ".round($la[0]*100,2)."% ";
	if ($la[1] < 0.16) {
		echo "<font color=green>низкая</font>";
	} elseif ($la[1] < 0.25) {
		echo "<font color=orange>средняя</font>";
	} elseif ($la[1] > 0.25) {
		echo "<font color=red>высокая</font>";
	}
		 ?>
        <? }
		$online = 0;
		$sp = mysql_query('SELECT `id`,`room`,`city` FROM `users` WHERE `online` > ('.time().'-600)');
		while($pl = mysql_fetch_array($sp))
		{
			$online++;
		}
		?> / Онлайн: <?=$online?> / Нагрузка USI: <?=round((round($la[2]*100,2)/$online),2)?>%</td>
        <td>&nbsp;</td>
        <td><? if($auth==true){ ?><div align="right"><a href="../adminion/?exit=<?=$code?>">Выйти</a></div><? } ?></td>
      </tr>
      
    </table></td>
  </tr>
  <tr>
    <td valign="top">
    <div align="center">
      <?
    if(!isset($_COOKIE['pass3']) || $_COOKIE['pass3']!=$psw){
	?>
      <form action="../adminion/index.php" method="post"><center><br><br>
        <span class="стиль5"><br>
        Для входа в панель требуется пароль</span>
        <hr>
        <span class="стиль5">Введите пароль: 
        <input value="" name="psw" type="password">
        <input type="submit" value="ок" />
        </span>
      </form>
</div>
    <?
	}else{
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="200" height="18" valign="top"><table class="test" width="100%" border="0" align="left" cellpadding="2" cellspacing="0">
          <tr>
            <td bgcolor="#C0C2C0"><div align="left" class="tblbr"><strong style="margin-left:10px;">Общие настройки</strong></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Настройка сервера</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Настройки модулей</a></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#C0C2C0"><div align="left" class="tblbr"><strong style="margin-left:10px;">Персонажи</strong></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Поиск персонажей</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Работа с персонажем</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Работа с ботом</a></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#C0C2C0"><div align="left" class="tblbr"><strong style="margin-left:10px;">Предметы</strong></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Поиск предмета</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Работа с предметом</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Предметы у персонажей</a></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#C0C2C0"><div align="left" class="tblbr"><strong style="margin-left:10px;">Локации</strong></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Поиск локации</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Работа с локацией</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Добавить локацию</a></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#C0C2C0"><div align="left" class="tblbr"><strong style="margin-left:10px;">Действия</strong></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="/adminion/?mod=chatsee">Наблюдать за чатом</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="/adminion/?mod=chatsys">Системный лог</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="/adminion/?mod=realbucks">Реальщики</a></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#C0C2C0"><div align="left" class="tblbr"><strong style="margin-left:10px;">Поединки</strong></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Поиск поединка</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Настройки баланса</a></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#C0C2C0"><div align="left" class="tblbr"><strong style="margin-left:10px;">Пещеры</strong></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="../adminion/?mod=dungeon_list">Список пещер</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="../adminion/?mod=dungeon&r=1">Редактор лабиринтов</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="../adminion/?mod=dungeon_bots">Редактор ботов</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="../adminion/?mod=dungeon_editor">Редактор пещер</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="../adminion/?mod=dobj&r=1">Работа с обьектами</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Создать пещеру</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Работа с квестами</a></div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="#">Создать квест</a></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
        <td valign="top" style="padding:10px;">
        <?
        if(isset($_GET['mod'])){
			if(file_exists('../adminion/mod/'.htmlspecialchars($_GET['mod'],NULL,'cp1251').'.php')){
				include('../adminion/mod/'.htmlspecialchars($_GET['mod'],NULL,'cp1251').'.php');
			}else{
				if(!isset($_GET['ajax'])) {
					echo '<center>У вас нет доступа к данному разделу</center>';
				}else{
					echo '{"error":"-1"}';
				}
			}
		}else{
			if(!isset($_GET['ajax'])) {
				echo '<center>Выберите раздел</center>';
			}else{
				echo '{"error":"-2"}';
			}
		}
		?></td>
      </tr>
    </table>    
    <?
    }
	?></td>
  </tr>
  <tr>
    <td><div align="center" class="стиль2">Панель управления СБК, xcombats.com &copy; <?=date('Y')?><hr>Pluon, PluGameCMS © 2011-2012<BR>
    All rights reserved.</div></td>
  </tr>
</table>
</body>
</html>
<? }else{
        if(isset($_GET['mod'])){
			if(file_exists('../adminion/mod/'.htmlspecialchars($_GET['mod'],NULL,'cp1251').'.php')){
				include('../adminion/mod/'.htmlspecialchars($_GET['mod'],NULL,'cp1251').'.php');
			}else{
				echo '{"error":"-1"}';
			}
		}else{
			echo '{"error":"-2"}';
		}	
}?>