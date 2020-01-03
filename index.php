<?php

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
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="nimg/index.css">
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
        </div>
        <br></center>
    </td>
  </tr>
</table>
</body>
</html>