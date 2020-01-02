<?

if(isset($_GET['cloud'])) {
	print_r($_SERVER["HTTP_CF_CONNECTING_IP"].'<br>');
	print_r($_SERVER["HTTP_CF_IPCOUNTRY"].'<br>');
	print_r($_SERVER["HTTP_CF_RAY"].'<br>');
	print_r($_SERVER["HTTP_CF_VISITOR"].'<br>');
	die();
}

if( strripos($_SERVER['HTTP_REFERER'],'vk.com') == true ) {
	setcookie('from','vk.com',time()+86400);
	setcookie('ref_id','1',time()+86400);
}elseif(isset($_GET['from'])) {
	setcookie('from',htmlspecialchars($_GET['from'],NULL,'cp1251'),time()+86400);
}

$seson = 'summer';
if( date('m') >= 9 && date('m') <= 11 ) {
	//$seson = 'autumn';
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Старый Бойцовский Клуб &mdash; XCOMBATS.COM &mdash; Оригинальный БК2 Без дополнений!</title>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <link rel="stylesheet" type="text/css" href="nimg/index.css">
    <!-- Rating@Mail.ru counter -->
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
</head>
<body>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#001B38">
    	<div class="main">
        	<div class="box1"><form method="post" action="http://xcombats.com/enter.php">
            	<center style="color:#001435"><b>Войдите или зарегистрируйтесь</b></center><br>
            	<input onfocus="if ( 'Логин' == value ) { value = ''; } " onblur="if ( '' == value ) { value = 'Логин'; } " class="inp1" type="text" name="login" value="Логин">
                <input onfocus="if ( 'Пароль' == value ) { value = ''; } " onblur="if ( '' == value ) { value = 'Пароль'; } " class="inp1" type="password" name="pass" value="Пароль"><br>
                <input class="inp3" type="submit" value="Войти"> <input class="inp2" onClick="location.href='http://xcombats.com/register.php';" type="button" value="Регистрация">
            </form></div>
      </div><br>
        <center class="whitea"><small>
        	Старый бойцовский клуб <a href="http://xcombats.com/">xcombats.com</a> 2017. Проект находится в стадии разработки.<br>По всем вопросам и предложениям обращайтесь на эл.почту <a href="mailto:support@xcombats.com">support@xcombats.com</a>
        </small><br><br>
        <div>
        <a href="//www.free-kassa.com/"><img src="//www.free-kassa.ru/img/fk_btn/16.png"></a>
        <!-- Rating@Mail.ru logo -->
        <a href="http://top.mail.ru/jump?from=2147109">
        <img src="//top-fwz1.mail.ru/counter?id=2147109;t=502;l=1" 
        style="border:0;" height="31" width="88" alt="Рейтинг@Mail.ru" /></a>
        <!-- //Rating@Mail.ru logo -->
		<!--LiveInternet counter--><script type="text/javascript">document.write("<a href='//www.liveinternet.ru/click' target=_blank><img src='//counter.yadro.ru/hit?t11.2;r" + escape(top.document.referrer) + ((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + ";u" + escape(document.URL) + ";" + Math.random() + "' border=0 width=88 height=31 alt='' title='LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодня'><\/a>")</script><!--/LiveInternet-->
        </div>
        <br></center>
    </td>
  </tr>
</table>
</body>
</html>