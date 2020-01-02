<?php
define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__chat_class.php');
include('_incl_data/class/__filter_class.php');
include('_incl_data/class/__user.php');


$urla = explode('?',$_SERVER["REQUEST_URI"]);
$url = explode('/',$urla[0]);

if( $url[2] == 'js_data' ) {
	//Генерация JS контента, базы ВСЕГО!
	
	//
	die();	
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://xcombats.com/js/jquery.js"></script>
<!-- -->
<script type="text/javascript" src="http://xcombats.com/dress/js_data"></script>
<!-- -->
<script type="text/javascript" src="http://xcombats.com/dress.js"></script>
<script>
var u = {
	info:{
			'id':0,
			'login':'Логин персонажа',
			'level':0,
			'up':0,
			'align':'0',
			'clan':['','0.gif'],
			'shadow':'0.gif',
			'sex':0
		}
};
</script>
</head>
<body onLoad="dress.start();" style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="300" align="center" valign="top" id="main1">
        	логин, слоты, приемы, обкасты
        </td>
        <td valign="top" id="main2">
        	Статы, Умения, Статы за награду, зверь, склонка, образ, клан
        </td>
        <td valign="top" id="main3">
        	Основные, Модификаторы, Мощность, Защита, Прочее
        </td>
        <td valign="top" id="main4">
        	Стоимость, дополнительные настройки
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>Описание и благодарности.</td>
  </tr>
</table>
</body>
</html>