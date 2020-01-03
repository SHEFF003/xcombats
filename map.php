<?php
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}
define('IP',GetRealIp());
include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

if($u->info['banned'] > 0 || !isset($u->info['id'])) {
	header('location: /index.php');
	die();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede; background-image:url(/i/bgmain.jpg);">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td align="center" valign="middle">
    <!-- -->
    <br><br>
    <h3>Тестовая локация</h3>
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="800" height="400" id="FlashID" title="test_map">
      <param name="movie" value="map/main.swf">
      <param name="quality" value="high">
      <param name="wmode" value="opaque">
      <param name="swfversion" value="15.0.0.0">
      <!-- Этот тег param предлагает пользователям Flash Player 6.0 r65 и более поздних версий загрузить последнюю версию Flash Player. Удалите его, если не хотите, чтобы пользователи видели запрос. -->
      <param name="expressinstall" value="../../Scripts/expressInstall.swf">
      <!-- Следующий тег object не поддерживается браузером Internet Explorer. Поэтому скройте его от Internet Explorer при помощи IECC. -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="map/main.swf" width="800" height="400">
        <!--<![endif]-->
        <param name="quality" value="high">
        <param name="wmode" value="opaque">
        <param name="swfversion" value="15.0.0.0">
        <param name="expressinstall" value="../../Scripts/expressInstall.swf">
        <!-- Браузер отображает следующее альтернативное содержимое для пользователей Flash Player 6.0 и более старых версий. -->
        <div>
          <h4>Для содержимого этой страницы требуется более новая версия Adobe Flash Player.</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Получить проигрыватель Adobe Flash Player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object>
    <!-- -->
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>