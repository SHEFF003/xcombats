<?php
define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

$url = explode('?',$_SERVER["REQUEST_URI"]);
$url = explode('/',$url[0]);

$ru = mysql_fetch_array(mysql_query('SELECT * FROM `ruine_now` WHERE `id` = "'.mysql_real_escape_string($url[2]).'" LIMIT 1'));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8t" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<title>Лог Руин Старого Замка</title>
</head>
<body style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede;">
<script type="text/javascript" src="js/jquery.js"></script>

<center style="float:right"><a class="btnnew" href="http://xcombats.com/ruins/<?=$ru['id']?>">Обновить</a></center>

<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#<?=$ru['id']?> Руинный турнир в Старом Замке</h3>
<?
$html = ''; $i = 0;
$sp = mysql_query('SELECT * FROM `ruine_logs` WHERE `tid` = "'.$ru['id'].'" ORDER BY `time` DESC');
while( $pl = mysql_fetch_array($sp) ) {
	$i++;
	$html .= '<span class="date">'.date('d.m.Y H:i',$pl['time']).'</span> '.$pl['text'];
	$html .= '<br>';
}
if( $html == '' ) {
	$html = '<center>Лог был потерян, либо турнир не найден</center>';
}
echo $html; $html = '';
?>

<br><br>
<div align="right"><font style="color:#999;" size="1" face="verdana" color="black"><hr style="border-color:#CCC;">Старый Бойцовский Клуб &copy; <?=date('Y')?>, «www.xcombats.com»™ &nbsp; &nbsp; </font></div>
<br /><br />

<div align="right"><div style="display:none"><!--LiveInternet counter--><script type="text/javascript"><!--
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
        <!-- //Rating@Mail.ru logo --></div></div></body>
</html>
