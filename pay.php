<?
header('location: pay.back.php?buy_ekr');
die();
//30.05.2060 07:25:06
define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__chat_class.php');
include('_incl_data/class/__filter_class.php');
include('_incl_data/class/__user.php');

if(isset($_GET['showcode'])) {
	include('show_reg_img/security.php');
	die();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>&laquo;СБК&raquo; Коммерческий отдел</title>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/psi.js"></script>
<link rel="stylesheet" href="styles/register.css?<?=time()?>" type="text/css" media="screen"/>
</head>

<body>
<div align="center" style="color:red;" id="errorreg"></div>
<center>
<!-- test window -->
<br><br>
<div>
<table style="padding-top:70px;" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="62"><table width="100%" height="62" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="129" class="psi_tlimg">&nbsp;</td>
        <td align="center" class="psi_tline">
        	<div class="psi_fix">
          	  <div class="psi_logo">&nbsp;</div>
            </div>
        </td>
        <td width="129" class="psi_trimg">&nbsp;</td>
      </tr>
      </table></td>
    </tr>
  <tr>
    <td>
    <table class="psi_mainin" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="23" class="psi_mleft">&nbsp;</td>
        <td valign="top" width="682" height="401" class="psi_main_reg">
        <!-- main -->
        <div style="padding:20px;" align="center">
        .: <? if(!isset($u->info['id'])) { ?>test<? }else{ echo 'Добро пожаловать, <b>'.$u->microLogin($u->info['id'],1).'</b> '.
		':: <a href="http://xcombats.com/commerce/">Главная</a> :: <a href="http://xcombats.com/commerce/">Мои заявки</a>'.
		''; } ?> :.
        <hr style="border-color:#333;">
        </div>
        <!-- main -->	
        </td>
        <td width="23" class="psi_mright">&nbsp;</td>
      </tr>
      </table>
    </td>
    </tr>
  <tr>
    <td height="62"><table width="100%" height="62" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="129" class="psi_dlimg">&nbsp;</td>
        <td class="psi_dline">&nbsp;</td>
        <td width="129" class="psi_drimg">&nbsp;</td>
      </tr>
    </table></td>
    </tr>
</table>
<div style="width:660px;text-align:justify;">
	<small>
        <hr><br>
        «Старый Бойцовский Клуб» – это бесплатная увлекательная онлайн игра, в которой сконцентрировано все самое лучшее от современных онлайн игр. В этой браузерной игре заложены самые интересные традиции всем известной онлайн игры под названием «Combats 2004-2009», которая, кстати, стала первооткрывателем всех браузерных игр.
        <br><br>
        В бесплатную браузерную игру версии вошли предыдущие стратегии и прибавились новые технические разработки, которые сделали эту mmorpg игру еще более увлекательной!
        <br><br><hr>
    </small>
    <div style="float:left">
        <a href="http://xcombats.com/">Главная</a> &nbsp; &nbsp; 
        <a href="http://xcombats.com/news">Новости</a> &nbsp; &nbsp; 
        <a href="http://xcombats.com/forum/">Форум</a> &nbsp; &nbsp; 
        <a href="http://xcombats.com/lib/polzovatelskoe-soglashenie/">Соглашение</a>
    </div>
    <div style="float:right">Старый Бойцовский Клуб &copy; 2016-<?=date('Y')?></div>
</div>
</div>
<!-- test window -->
</center>
<br>
</body>
</html>
