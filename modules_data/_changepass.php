<?
if(!defined('GAME'))
{
	die();
}
?>
<TABLE width=100% cellspacing=0 cellpadding=0>
<FORM ACTION="main.php?security" METHOD=POST>
<TR>
	<TD><h3>Сменить пароль/email для персонажа "<?=$u->info['login']?>"</h3></TD>
<TD valign=top align=right>
<INPUT TYPE=button value="Подсказка" style="background-color:#A9AFC0" onclick="window.open('/encicl/help/psw.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')">&nbsp;<INPUT TYPE=button value="Вернуться" onClick="location.href='main.php';"></TD>
</TR></TABLE>
<?
$time=time();
function md5m($src)
{
	
    $tar = Array(16);
    $res = Array(16);
$src = utf8_encode ($src);
    for ($i = 0; $i < strlen($src) || $i < 16; $i++)
    {
        $res[$i] = ord($src{$i}) ^ $i * 4;
    } 
    for ($i = 0; $i < 4; $i++)
    {
        for ($j = 0; $j < 4; $j++)
        {
            $tar[$i * 4 + $j] = ($res[$j * 4 + $i] + 256) % 256;
        } 
    } 
    return ($tar);
} 
function array2HStr($src)
{
    $hex = Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
    $res = "";
    for ($i = 0; $i < 16; $i++)
    {
        $res = $res . ($hex[$src[$i] >> 4] . $hex[$src[$i] % 16]);
    } 
    return ($res);
} 


	if ($_POST['oldpsw2']) {
$_POST['oldpsw2'] = addslashes($_POST['oldpsw2']);
$oldpsw2=md5(array2HStr(md5m($_POST['oldpsw2'])));
if($oldpsw2==$u->info['pass2']){
mysql_query("UPDATE `users` SET `pass2` = '' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;");
echo "<font color=red><b>Второй пароль выключен.<br></b></font>";
$u->info['pass2']='';
}else{
echo "<font color=red><b>Введен не верный второй пароль!<br></b></font>";
}


                                 }



if ($_POST['num_count']) {
		if($_POST['num_count']==4){$pass2=rand(1000,9999);}elseif($_POST['num_count']==6){$pass2=rand(100000,999999);}else{$pass2=rand(10000000,99999999);}


	if(mysql_query("UPDATE `users` SET `pass2` = '".md5(array2HStr(md5m($pass2)))."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;")){
echo "<font color=red><b>Второй пароль: $pass2.<br>Запомните или запишите, т.к. он не высылается на email и его нельзя как-либо узнать. Потеряв второй пароль, вы потеряете персонажа!<br>Этот пароль выслан на ваш email.<br></b></font><br>";
$u->info['pass2']=md5(array2HStr(md5m($pass2)));


		$headers  = "Mime-Version: 1.1 \r\n";
		$headers .= "Date: ".date("r")." \r\n";
		$headers .= "Content-type: text/html; charset=cp1251 \r\n";
		$headers .= "From: Бойцовский Клую <support@xcombats.com>\r\n";

		$headers = trim($headers);
		$headers = stripslashes($headers);
 				if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
			{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
			{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		else
			{
			$ip=$_SERVER['HTTP_X_REAL_IP'];
			}
		$aa='<html>
				<head>
					<title>Второй пароль от персонажа '.$u->info['login'].'.</title>
				</head>
				<body>
					Вами, с IP адреса - '.$ip.', был установлен второй пароль в игре Бойцовский Клуб.<br>
					Если это были не Вы, свяжитесь с администрацией сайта.<br>
					<br>
					------------------------------------------------------------------<br>
					Ваш логин    | '.$u->info['login'].'<br>
					Второй пароль | '.$pass2.'<br>
					------------------------------------------------------------------<br>
					<br>
					<br>
					Желаем Вам приятной игры. <BR><BR>

                                        <i>Администрация</i>
				</body>
			</html>';

		mail($u->info['mail'],"Второй пароль от персонажа \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);


	}

}
/*-------Смена пароля--------*/
	if ($_POST['oldpass'] && $_POST['npass'] && $_POST['npass2']) {
		if($u->info['securetime']>$time) {echo"<font color=red>Должно пройти не менее трех суток между сменой подтверждения, пароля или email.</font><br>";}
		elseif($u->info['emailconfirmation']!=-1123) {
		if ($u->info['pass'] == md5($_POST['oldpass'])) {
			if($_POST['npass'] == $_POST['npass2']) {
				if(mysql_query("UPDATE `users` SET `pass` = '".md5($_POST['npass'])."' , `repass` = 0, `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"))
				{echo "<font color=red>Пароль удачно сменен.</font><br>"; $_COOKIE['pass'] = md5($_POST['npass']);}
			} else{echo "<font color=red>Не совпадают новые пароли.</font><br>";}
		}else{echo "<font color=red>Неверный старый пароль.</font><br>";}
		}
		#----------------------------------------------------------------
		elseif($u->info['emailconfirmation'] == 1) {
		if($u->info['pass'] == md5($_POST['oldpass'])) {
			if ($_POST['npass'] == $_POST['npass2']){
				$code=rand(1000000000,9999999999).".".rand(10000,99999);
				if(mysql_query("INSERT INTO 
								`emailconfirmation` (
								`id`, 
								`code`, 
								`pa_em`,
								`pass`) 
							VALUES (
								'".mysql_real_escape_string($u->info['id'])."',
								'".mysql_real_escape_string($code)."',
								'".md5($_POST['npass'])."',
								1)"))
				{
				/////////////////////////////////////////////////IP///////////////////////////////////////////////
					if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
					{
						$ip=$_SERVER['HTTP_CLIENT_IP'];
					}
					elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
					{
						$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
					}
					else
					{
						$ip=$_SERVER['HTTP_X_REAL_IP'];
					}
				//////////////////////////////////////////отсылаем на почту///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: Бойцовский Клуб <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>Смена пароля</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								Кто-то с IP: '.$ip.' пытается сменить пароль к персонажу "'.$u->info['login'].'" ['.$u->info['level'].'].<br>
								Т.к. в анкете у этого персонажа указан email: '.$u->info['mail'].', то вы и получили это письмо.<br>
								login: '.$u->info['login'].'<br>
								Прежний пароль (без кавычек): "'.$_POST['oldpass'].'"<br>
								Новый пароль (без кавычек): "'.$_POST['npass'].'"<br>
								<br>
								Для того чтобы подтвердить смену пароля, вы должны зайти по ссылке:<br>
								http://'.$u->info['city'].'.xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								Бойцовский Клуб http://www.xcombats.com<br>
								Администрация Бойцовского Клуба: support@xcombats.com<br>
								P.S. Данное письмо сгенерировано автоматически, отвечать на него не нужно.
					
							</body>
						</html>';

				mail($u->info['mail'],"Смена пароля у персонажа \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo"<font color=red><b>На ваш email выслано письмо с просьбой подтвердить операцию смены пароля</b></font><BR>";
				}
			}else{echo "<font color=red><b>Не совпадают новые пароли.</b></font>";}
		}else{ echo "<font color=red><b>Неверный старый пароль.</b></font>"; }

		}
		#-------------------------------------
	}
	/*-----Смена e-mail------*/
	if ($_POST['oldpsw'] && $_POST['oldemail'] && $_POST['newemail']) {
		if($u->info['securetime']>$time) {echo"<font color=red>Должно пройти не менее трех суток между сменой подтверждения, пароля или email.</font><br>";}
		elseif($u->info['pass'] != md5($_POST['oldpsw'])) {echo "<font color=red>Неверный пароль.</font><br>";}
		elseif($u->info['mail'] != $_POST['oldemail']) {echo "<font color=red>Неверный старый E-Mail.</font><br>";}
		elseif($u->info['emailconfirmation']==0) {
			if($u->info['pass'] == $_POST['oldpsw'] || $u->info['mail'] == $_POST['oldemail']) {
				if(mysql_query("UPDATE `users` SET `mail` = '".mysql_real_escape_string($_POST['newemail'])."', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"))
				{
				echo "<font color=red>E-mail удачно изменен.</font><br>";
				}
				}
				else{echo "<font color=red>E-Mail не изменен.</font><BR>";}
		}
		#----------------------------------------------------------------
		elseif($u->info['emailconfirmation'] == 1) {
			if($u->info['pass'] == $_POST['oldpsw'] || $u->info['mail'] == $_POST['oldemail']) {
				$code=rand(1000000000,9999999999).".".rand(10000,99999);
				if(mysql_query("INSERT INTO 
								`emailconfirmation` (
								`id`, 
								`code`, 
								`pa_em`,
								`email`) 
							VALUES (
								'".mysql_real_escape_string($u->info['id'])."',
								'".mysql_real_escape_string($code)."',
								'".mysql_real_escape_string($_POST['newemail'])."',
								1)"))
				{
				/////////////////////////////////////////////////IP///////////////////////////////////////////////
					if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
					{
						$ip=$_SERVER['HTTP_CLIENT_IP'];
					}
					elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
					{
						$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
					}
					else
					{
						$ip=$_SERVER['HTTP_X_REAL_IP'];
					}
				//////////////////////////////////////////отсылаем на почту///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: Бойцовский Клуб <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>Смена email</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								Кто-то с IP: '.$ip.' пытается сменить email к персонажу "'.$u->info['login'].'" ['.$u->info['level'].'].<br>
								Т.к. в анкете у этого персонажа указан email: '.$u->info['mail'].', то вы и получили это письмо.<br>
								login: '.$u->info['login'].'<br>
								Прежний email (без кавычек): "'.$_POST['oldemail'].'"<br>
								Новый email (без кавычек): "'.$_POST['newemail'].'"<br>
								<br>
								Для того чтобы подтвердить смену email, вы должны зайти по ссылке:<br>
								http://xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								Бойцовский Клуб http://www.xcombats.com<br>
								Администрация Бойцовского Клуба: support@xcombats.com<br>
								P.S. Данное письмо сгенерировано автоматически, отвечать на него не нужно.
					
							</body>
						</html>';

				mail($u->info['mail'],"Смена email у персонажа \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo"<font color=red><b>На ваш email выслано письмо с просьбой подтвердить операцию смены email</b></font><BR>";
				}
		}else{ echo "<font color=red><b>Неверный старый пароль или email.</b></font><br>"; }

		}
		#-------------------------------------
	}
	/*----Вопрос/Ответ------*/
	if ($_POST['oldpsw1']) {
		$ops = mysql_fetch_array(mysql_query("SELECT `pass`, `securetime`, `emailconfirmation` FROM `users` WHERE `id` = '".mysql_real_escape_string($u->info['id'])."'"));
		if($u->info['securetime']>$time) {echo"<font color=red>Должно пройти не менее трех суток между сменой подтверждения, пароля или email.</font><br>";}
		elseif($u->info['emailconfirmation']==0) {
			if ($u->info['pass'] == md5($_POST['oldpsw1'])) {
				if(mysql_query("UPDATE `users` SET `a1` = '".mysql_real_escape_string($_POST['secretquestion'])."', `q1` = '".mysql_real_escape_string($_POST['secretanswer'])."', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"))
				{
				$u->info['secretquestion'] = $_POST['secretquestion'];
				echo "<font color=red>Новый секретный вопрос / ответ записан.</font><br>";
				}
				}
				else{echo "<font color=red>Неверный старый пароль.</font><BR>";}
		}
		#----------------------------------------------------------------
		elseif($u->info['emailconfirmation'] == 1) {
			if($u->info['pass'] == md5($_POST['oldpsw1'])) {
				$code=rand(1000000000,9999999999).".".rand(10000,99999);
				if(mysql_query("INSERT INTO 
								`emailconfirmation` (
								`id`, 
								`code`, 
								`question`,
								`answer`,
								`qu_an`)
							VALUES (
								'".mysql_real_escape_string($u->info['id'])."',
								'".mysql_real_escape_string($code)."',
								'".mysql_real_escape_string($_POST['secretquestion'])."',
								'".mysql_real_escape_string($_POST['secretanswer'])."',
								1)"))
				{
				/////////////////////////////////////////////////IP///////////////////////////////////////////////
					if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
					{
						$ip=$_SERVER['HTTP_CLIENT_IP'];
					}
					elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
					{
						$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
					}
					else
					{
						$ip=$_SERVER['HTTP_X_REAL_IP'];
					}
				//////////////////////////////////////////отсылаем на почту///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: Бойцовский Клуб <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>Смена секретного вопроса и ответа</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								Кто-то с IP: '.$ip.' пытается сменить секретный вопрос / ответ к персонажу "'.$u->info['login'].'" ['.$u->info['level'].'].
								Т.к. в анкете у этого персонажа указан email: '.$u->info['mail'].', то вы и получили это письмо.<br>
								login: '.$u->info['login'].'<br>
								Прежний секретный вопрос: '.$u->info['secretquestion'].'<br>
								Прежний секретный ответ: '.$u->info['secretanswer'].'<br>
								Новый секретный вопрос: '.$_POST['secretquestion'].'<br>
								Новый секретный ответ: '.$_POST['secretanswer'].'<br>
								Для того чтобы подтвердить смену, вы должны зайти по ссылке:<br>
								http://'.$u->info['city'].'.xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								Бойцовский Клуб http://www.xcombats.com<br>
								Администрация Бойцовского Клуба: support@xcombats.com<br>
								P.S. Данное письмо сгенерировано автоматически, отвечать на него не нужно.
					
							</body>
						</html>';

				mail($u->info['mail'],"Смена секретного вопроса и ответа у персонажа \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo"<font color=red>На ваш email выслано письмо с просьбой подтвердить операцию смены секретного вопроса и ответа</font><BR>";
				}
		}else{ echo "<font color=red>Неверный старый пароль.</font><br>"; }

		}
		#-------------------------------------
		$u->info['a1'] = $_POST['secretquestion'];
	}
	/*----Подтверждение на e-mail----*/
	if ($_POST['email'] && $_POST['set_on_emailconfirmation']) {
			if($u->info['securetime']>$time) {echo"<font color=red>Должно пройти не менее трех суток между сменой подтверждения, пароля или email.</font><BR>";}
		elseif ($u->info['mail'] == $_POST['email']) {
				$code=rand(1000000000,9999999999).".".rand(10000,99999);
			if(mysql_query("INSERT INTO 
									`emailconfirmation` (
								`id`, 
								`code`, 
								`active`) 
							VALUES (
								'".mysql_real_escape_string($u->info['id'])."',
								'".mysql_real_escape_string($code)."',
								1)"))
			{
				/////////////////////////////////////////////////IP///////////////////////////////////////////////
					if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
					{
						$ip=$_SERVER['HTTP_CLIENT_IP'];
					}
					elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
					{
						$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
					}
					else
					{
						$ip=$_SERVER['HTTP_X_REAL_IP'];
					}
				//////////////////////////////////////////отсылаем на почту///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: Бойцовский Клуб <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>Востановление пароля</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								Запрос поступил с IP: '.$ip.'<br>
								Т.к. в анкете у этого персонажа указан email: '.$u->info['mail'].', то вы и получили это письмо.<br>
								Для того чтобы включить функцию подтверждения смены пароля и email через почту, вы должны зайти по ссылке:<br>
								http://'.$u->info['city'].'.xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								Бойцовский Клуб http://www.xcombats.com<br>
								Администрация Бойцовского Клуба: support@xcombats.com<br>
								P.S. Данное письмо сгенерировано автоматически, отвечать на него не нужно.
					
							</body>
						</html>';

				mail($u->info['mail'],"Смена подтверждения у персонажа \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo "<font color=red>На ваш email выслано письмо с просьбой подтвердить операцию.</font><BR>";
			}
		}
				else{echo "<font color=red>email указан неверно.</font><BR>";}
	}
	if ($_POST['email'] && $_POST['set_off_emailconfirmation']) { //Отключение подтверждения на email
			if($u->info['securetime']>$time) {echo"<font color=red>Должно пройти не менее трех суток между сменой подтверждения, пароля или email.</font><BR>";}	
	/*НЕДОДЕЛАНО */	
	}
?>
Чем выше уровень вашего персонажа, тем больше к нему внимание со стороны хакеров, взломщиков и аферистов. Чтобы однажды не оказаться в ситуации, когда вы уже не сможете зайти под своим персонажем, которого развивали (которым жили!) месяцами, потому что пароль сменили, email сменили, все предметы/вещи/кредиты... все что нажито непосильным трудом... ушли в неизвестном направлении, необходимо соблюдать элементарные меры предосторожности. А именно:<br>
1. Никогда, ни под каким предлогом, никому не говорите свой пароль. Ни паладинам, ни администрации не нужно знать ваш пароль.<br>
2. Вводите логин и пароль только на титульной странице <a href=http://www.xcombats.com target="_blank">www.xcombats.com</a> Ни на каких других сайтах, которые будут как две капли похожие на наш, и куда вас зазывают обещая на халяву предметы и кредиты, не вводите свой пароль! Иначе вы рискуете потерять своего персонажа.<br>
Настоятельно рекомендуем прочесть заметку <A HREF="http://lib.xcombats.com/" target=_blank>Виды обмана в Бойцовском Клубе</A>.<BR>
<BR>Если вы играете из интернет кафе или компьютерного клуба, где шанс быть взломанным очень высокий, рекомендуем включить третий уровень защиты (см. ниже)<br><br>
<fieldset>
<legend><b>Сменить пароль</b></legend>
<table>
	<tr><td align=right>Старый пароль:</td><td><input type=password name="oldpass"></td></tr>
	<tr><td align=right>Новый пароль:</td><td><input type=password name="npass"></td></tr>
	<tr><td align=right>Новый пароль (еще раз):</td><td><input type=password name="npass2"></td></tr>
	<tr><td align=right><input type=submit value="Сменить пароль" name="changepsw"></td><td></td></tr>
</table>
</fieldset>
</FORM>
<FORM ACTION="main.php?security" METHOD=POST>
<FIELDSET><LEGEND><B> Сменить email </B> </LEGEND>

<TABLE>
	<TR><TD align=right>Ваш пароль:</TD><TD><INPUT TYPE=password NAME=oldpsw  size=15 maxlength=31></TD></TR>
	<TR><TD align=right>Прежний email:</TD><TD><INPUT TYPE=text NAME=oldemail  size=20 maxlength=50></TD></TR>
	<TR><TD align=right>Новый email:</TD><TD><INPUT TYPE=text NAME=newemail  size=20 maxlength=50></TD></TR>
	<TR><TD align=center colspan=2><INPUT TYPE=submit value="Сменить email" name=changeemail></TD></TR>
</TABLE>
</FIELDSET>
</FORM>
<FORM ACTION="main.php?security" METHOD=POST>
<FIELDSET><LEGEND><B> Сменить секретный вопрос/ответ</B> </LEGEND>
<TABLE>
	<TR><TD align=right>Ваш пароль:</TD><TD><INPUT TYPE=password NAME=oldpsw1 size=15 maxlength=31></TD></TR>
	<TR><TD align=right>Старый вопрос:</TD><TD><B><?=htmlspecialchars($u->info['a1'],NULL,'cp1251')?></B></TD></TR>
	<TR><TD align=right>Новый вопрос:</TD><TD><INPUT TYPE=text NAME=secretquestion  size=40 maxlength=50></TD></TR>
	<TR><TD align=right>Новый ответ:</TD><TD><INPUT TYPE=text NAME=secretanswer  size=40 maxlength=50></TD></TR>
	<TR><TD align=center colspan=2><INPUT TYPE=submit value="Сменить" name=changesecretqa></TD></TR>
</TABLE>
</FIELDSET>
</FORM>

<U>Учтите</U>, вы не можете одновременно поменять email, пароль или секретные вопрос/ответ. Период должен составлять не менее трех суток.<BR>
<FORM ACTION="main.php?security" METHOD=POST>
<FIELDSET><LEGEND><B> Второй уровень защиты </B> </LEGEND>
<?
echo"Если вы уверены в своем email, его не взломают (учтите, халявная почта на серверах типа mail.ru hotmail.com и т.п. легко взламывается), вы его не \"забудете\", он не пропадет при смене провайдера и т.п., тогда вы можете обезопасить своего персонажа, включив режим подтверждения смены пароля/email через почту. При попытке сменить пароль, email или выключить этот режим, на ваш email высылается письмо с просьбой подтвердить эту операцию. Таким образом, если хакер, как-то узнал ваш пароль, он не сможет сменить его, и вы всегда сможете войти под своим персонажем.<BR>";
if($u->info['emailconfirmation']==0) {?>
Ваш email <INPUT TYPE=text NAME=email size=20 maxlength=50><BR>
<INPUT TYPE=submit name=set_on_emailconfirmation value="Включить режим подтверждения смены пароля или email через почту">
<?}elseif($u->info['emailconfirmation']==1){?>
<BR><B>Режим подтверждения через email включен.</B><BR><BR>
Ваш email <INPUT TYPE=text NAME=email size=20 maxlength=50> <INPUT TYPE=submit name=set_off_emailconfirmation value="Выключить режим подтверждения через email"><BR>
<small>Если ваш email больше не работает, вы можете отключить его здесь без подтверждения, в течение <b>часа</b> после получения проверки на чистоту у паладинов.</small>
<?}?>
</FIELDSET>
</FORM>
<FORM METHOD=POST ACTION="main.php?security">
<FIELDSET><LEGEND><B> Третий уровень защиты </B> </LEGEND>
Рекомендуем его использовать, если вы играете из интернет кафе или компьютерного клуба.<BR>
На компьютере может быть установлен клавиатурный шпион, который записывает все нажатия клавиш, таким образом, могут узнать ваш пароль.<BR>
Возможно, в сети компьютеров установлен "сетевой снифер", перехватывающий все интернет пакеты, который легко покажет все пароли. Чтобы обезопасить себя, вы можете установить своему персонажу второй пароль, который можно вводить при помощи мышки (клавиатурным шпионом не перехватить) и который передается на игровой сервер в зашифрованном виде, не поддающимся расшифровке ("сетевой снифер" не сможет перехватить его).<BR>
Ваш браузер должен нормально отображать Flash 6! (<I>если наши часики в нижней строке нормально тикают, значит у вас все в порядке :</I>)<BR>
<U>Будьте внимательны!</U> Второй пароль нельзя получить на email или узнать как-либо еще. Если вы его забудете/потеряете, вы не сможете войти в Бойцовский Клуб своим персонажем!<BR>

<?
if(!empty($u->info['pass2'])){echo"<BR><B>Второй пароль установлен.</B><BR><BR>Введите второй пароль <INPUT TYPE=password NAME=oldpsw2 size=10 maxlength=8> <INPUT TYPE=submit name=changepsw value=\"Выключить второй пароль\" onclick=\"return confirm('Выключить запрос второго пароля при входе в CБК?')\">";}else{
?>


Длина пароля:<BR>
<INPUT TYPE=radio NAME="num_count" value=4> 4 знака<BR>
<INPUT TYPE=radio NAME="num_count" checked value=6> 6 знаков<BR>
<INPUT TYPE=radio NAME="num_count" value=8> 8 знаков<BR>
<INPUT TYPE=submit name=changepsw value="Установить второй пароль" onclick="return confirm('Система сама придумает вам второй пароль, он будет показан на этой странице, после того, как вы нажмете OK и продублирован на email, указанный в анкете. Будьте внимательны.\nУстановить второй пароль?')"><BR>
<?
}
?>

</FIELDSET>
</FORM>