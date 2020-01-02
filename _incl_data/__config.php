<?php

error_reporting(1);
ini_set('display_errors','On');

if(!isset($_SERVER["HTTP_CF_IPCOUNTRY"])) {
	$_SERVER["HTTP_CF_IPCOUNTRY"] = '0';
}

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

setlocale(LC_CTYPE ,"ru_RU.CP1251");

//die('Серевер временно не работает, минут 5-10');

$c = array(
	'ver' => '2.0.0.0'
);
/* Конфигурации игры */

$c['server_ip'] = '5.187.6.194';

$c['title']  = '«Старый Бойцовский клуб» - Бесплатная, современная браузерная онлайн игра'; //Название игры
$c['title2'] = ' - Бесплатная, современная онлайн игра посвященная сражениям и магии!';
$c['title3']  = 'Старый Бойцовский клуб';
$c['name']   =  'Старый Бойцовский клуб';
$c['keys']   = ''; //Ключевые слова META
$c['desc']   = ''; //Описание META

//Сервера
$c['host']        = 'xcombats.com';
$c['forum']       = 'forum.'.$c['host'];
$c['img']   	  = 'img.xcombats.com';
$c['thiscity']    = 'capitalcity';
$c['capitalcity'] = $c['host'];
$c['abandonedplain'] = $c['host'];
$c['exit']		  = '<script>top.location="http://'.$c['host'].'/";</script><noscript><meta http-equiv="refresh" content="0; URL=http://'.$c['host'].'/"></noscript>';

//Чат
$c['chat_level'] = 4; // с какого лвл писать можно, чтобы видели все

//Биржа
$c['birja_sale'] = 2;
$c['birja_buy'] = 2; 

//Валюта
$c['curency_name'] = 'RUB';
$c['curency_value'] = 30;

//Бот клон
$c['bot_level'] = 3; // до какого лвл бот (включительно)
$c['propsk_die'] = 0; //Смерть при пропуске ходов, 0 - выкл. , >= 1 - количество пропусков до смерти при нападении
$c['haotbot'] = 8; //до какого лвл бот в хаотах
$c['max_zv_analog'] = 3; // сколько аналогичных заявок можно подавать

//Скупка
$c['exp'] = 0; //бонус опыта
$c['shop_type1'] = 100; //в гос НЕ ТРОГАТЬ
$c['shop_type2'] = 100; //в березку НЕ ТРОГАТЬ
$c['shop_all'] = 100; //Скупка на все! , 0 - сделать для отключения скупки. 
$c['nosanich']	 = false; //Странички Саныча не выпадают - true , выпадают - false /до меня было тру
$c['zuby'] = false; //зубы
$c['limitedexp'] = false; //лимит опыта
$c['infinity5level'] = true; //вечные 5 уровни
$c['expstop'] = 3000000; //Опыт на котором останавливаемся
$c['noobgade'] = false; //нуб квест (пещера)
$c['bonusonline'] = true; //бонус за онлайн
$c['bonusonline_kof'] = 1; //коэфицент кр за лвл
$c['level_ransfer'] = 5; //С какого уровня разрешены передачи
$c['znahar'] = true; //бесплатный знахарь
$c['nolevel'] = false; //лимитирование уровней
$c['noitembuy'] = false; //Не требует ресурсы для покупки
$c['effz'] = 0; //Скольким секундам равен заряд
$c['money_haot'] = true; //кр за хаоты
$c['money_haot_proc'] = 0; //сколько процентов от фулла выдает в награду
$c['crtoecr'] = 0; //Курс обмена кр на екр (если 0, то выключено)
$c['ecrtocr'] = 50; //Курс обмена екр на кр
$c['bonuslevel'] = true; //Бонус уровня
$c['bonussocial'] = false; //Бонус социальный
$c['exp_limit_many'] = false; //Лимит опыта, если более 5 боев, то 0 опыта даст
$c['exp_mega'] = true; //Повышенный лимит опыта
$c['exp_mega_val'] = array(
	0 => 200,
	1 => 175,
	2 => 150,
	3 => 125,
	4 => 100,
	5 => 75,
	6 => 50,
	7 => 0,
	8 => 0,
	9 => 0,
	10 => 0,
	11 => 0,
	12 => 0,
	13 => 0,
	14 => 0,
	15 => 0,
	16 => 0,
	17 => 0,
	18 => 0,
	19 => 0,
	20 => 0,
	21 => 0
); //Повышенный лимит опыта


$c['bonline'] = array(
	array( 1 , 2 , 3 , 4 , 5 , 6 , 7 , 8 , 9 , 10 , 11 , 12 , 13 ), //награда кр
	array( 0.01 , 0.02 , 0.03 , 0.04 , 0.05 , 0.06 , 0.07 , 0.08 , 0.15 , 0.25 , 0.35 , 0.45 , 0.55 ) //награда екр, если есть склонность
);

$c['w'] = date('w');

//Игра
//$code = explode(' ',microtime());
//$code = $code[0].''.round($code[1]/rand(1,5));
$code = '1';
$c['counters']  = '<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "2147109", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2147109;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->
<!-- Rating@Mail.ru logo -->
<a target="_blank" href="http://top.mail.ru/jump?from=2147109">
<img src="//top-fwz1.mail.ru/counter?id=2147109;t=49;l=1" 
style="border:0;" height="31" width="88" alt="Рейтинг@Mail.ru" /></a>
<!-- //Rating@Mail.ru logo -->'."<!--LiveInternet counter--><script type=\"text/javascript\">document.write(\"<a href='//www.liveinternet.ru/click' target=_blank><img src='//counter.yadro.ru/hit?t11.3;r\" + escape(top.document.referrer) + ((typeof(screen)==\"undefined\")?\"\":\";s\"+screen.width+\"*\"+screen.height+\"*\"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + \";u\" + escape(document.URL) + \";\" + Math.random() + \"' border=0 width=88 height=31 alt='' title='LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодня'><\/a>\")</script><!--/LiveInternet-->
";
$c['securetime'] = 0; //Время последнего возможного взлома персов (подбор пароля по базам данных других игр)


//$c['counters'] = '';

//$c['counters'] = '';

if(isset($_GET['ajax'])) {
	$c['counters'] = '';
}

$c['copyright'] = 'Copyright © '.date('Y').' «Старый Бойцовский Клуб»';

$c['counters_noFrm']  = $c['counters'];

if(isset($_GET['version'])) {
	die('Version: '.$c['ver'].'');
}
?>