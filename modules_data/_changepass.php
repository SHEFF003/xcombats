<?
if(!defined('GAME'))
{
	die();
}
?>
<TABLE width=100% cellspacing=0 cellpadding=0>
<FORM ACTION="main.php?security" METHOD=POST>
<TR>
	<TD><h3>������� ������/email ��� ��������� "<?=$u->info['login']?>"</h3></TD>
<TD valign=top align=right>
<INPUT TYPE=button value="���������" style="background-color:#A9AFC0" onclick="window.open('/encicl/help/psw.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')">&nbsp;<INPUT TYPE=button value="���������" onClick="location.href='main.php';"></TD>
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
echo "<font color=red><b>������ ������ ��������.<br></b></font>";
$u->info['pass2']='';
}else{
echo "<font color=red><b>������ �� ������ ������ ������!<br></b></font>";
}


                                 }



if ($_POST['num_count']) {
		if($_POST['num_count']==4){$pass2=rand(1000,9999);}elseif($_POST['num_count']==6){$pass2=rand(100000,999999);}else{$pass2=rand(10000000,99999999);}


	if(mysql_query("UPDATE `users` SET `pass2` = '".md5(array2HStr(md5m($pass2)))."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;")){
echo "<font color=red><b>������ ������: $pass2.<br>��������� ��� ��������, �.�. �� �� ���������� �� email � ��� ������ ���-���� ������. ������� ������ ������, �� ��������� ���������!<br>���� ������ ������ �� ��� email.<br></b></font><br>";
$u->info['pass2']=md5(array2HStr(md5m($pass2)));


		$headers  = "Mime-Version: 1.1 \r\n";
		$headers .= "Date: ".date("r")." \r\n";
		$headers .= "Content-type: text/html; charset=cp1251 \r\n";
		$headers .= "From: ���������� ���� <support@xcombats.com>\r\n";

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
					<title>������ ������ �� ��������� '.$u->info['login'].'.</title>
				</head>
				<body>
					����, � IP ������ - '.$ip.', ��� ���������� ������ ������ � ���� ���������� ����.<br>
					���� ��� ���� �� ��, ��������� � �������������� �����.<br>
					<br>
					------------------------------------------------------------------<br>
					��� �����    | '.$u->info['login'].'<br>
					������ ������ | '.$pass2.'<br>
					------------------------------------------------------------------<br>
					<br>
					<br>
					������ ��� �������� ����. <BR><BR>

                                        <i>�������������</i>
				</body>
			</html>';

		mail($u->info['mail'],"������ ������ �� ��������� \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);


	}

}
/*-------����� ������--------*/
	if ($_POST['oldpass'] && $_POST['npass'] && $_POST['npass2']) {
		if($u->info['securetime']>$time) {echo"<font color=red>������ ������ �� ����� ���� ����� ����� ������ �������������, ������ ��� email.</font><br>";}
		elseif($u->info['emailconfirmation']!=-1123) {
		if ($u->info['pass'] == md5($_POST['oldpass'])) {
			if($_POST['npass'] == $_POST['npass2']) {
				if(mysql_query("UPDATE `users` SET `pass` = '".md5($_POST['npass'])."' , `repass` = 0, `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"))
				{echo "<font color=red>������ ������ ������.</font><br>"; $_COOKIE['pass'] = md5($_POST['npass']);}
			} else{echo "<font color=red>�� ��������� ����� ������.</font><br>";}
		}else{echo "<font color=red>�������� ������ ������.</font><br>";}
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
				//////////////////////////////////////////�������� �� �����///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: ���������� ���� <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>����� ������</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								���-�� � IP: '.$ip.' �������� ������� ������ � ��������� "'.$u->info['login'].'" ['.$u->info['level'].'].<br>
								�.�. � ������ � ����� ��������� ������ email: '.$u->info['mail'].', �� �� � �������� ��� ������.<br>
								login: '.$u->info['login'].'<br>
								������� ������ (��� �������): "'.$_POST['oldpass'].'"<br>
								����� ������ (��� �������): "'.$_POST['npass'].'"<br>
								<br>
								��� ���� ����� ����������� ����� ������, �� ������ ����� �� ������:<br>
								http://'.$u->info['city'].'.xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								���������� ���� http://www.xcombats.com<br>
								������������� ����������� �����: support@xcombats.com<br>
								P.S. ������ ������ ������������� �������������, �������� �� ���� �� �����.
					
							</body>
						</html>';

				mail($u->info['mail'],"����� ������ � ��������� \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo"<font color=red><b>�� ��� email ������� ������ � �������� ����������� �������� ����� ������</b></font><BR>";
				}
			}else{echo "<font color=red><b>�� ��������� ����� ������.</b></font>";}
		}else{ echo "<font color=red><b>�������� ������ ������.</b></font>"; }

		}
		#-------------------------------------
	}
	/*-----����� e-mail------*/
	if ($_POST['oldpsw'] && $_POST['oldemail'] && $_POST['newemail']) {
		if($u->info['securetime']>$time) {echo"<font color=red>������ ������ �� ����� ���� ����� ����� ������ �������������, ������ ��� email.</font><br>";}
		elseif($u->info['pass'] != md5($_POST['oldpsw'])) {echo "<font color=red>�������� ������.</font><br>";}
		elseif($u->info['mail'] != $_POST['oldemail']) {echo "<font color=red>�������� ������ E-Mail.</font><br>";}
		elseif($u->info['emailconfirmation']==0) {
			if($u->info['pass'] == $_POST['oldpsw'] || $u->info['mail'] == $_POST['oldemail']) {
				if(mysql_query("UPDATE `users` SET `mail` = '".mysql_real_escape_string($_POST['newemail'])."', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"))
				{
				echo "<font color=red>E-mail ������ �������.</font><br>";
				}
				}
				else{echo "<font color=red>E-Mail �� �������.</font><BR>";}
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
				//////////////////////////////////////////�������� �� �����///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: ���������� ���� <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>����� email</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								���-�� � IP: '.$ip.' �������� ������� email � ��������� "'.$u->info['login'].'" ['.$u->info['level'].'].<br>
								�.�. � ������ � ����� ��������� ������ email: '.$u->info['mail'].', �� �� � �������� ��� ������.<br>
								login: '.$u->info['login'].'<br>
								������� email (��� �������): "'.$_POST['oldemail'].'"<br>
								����� email (��� �������): "'.$_POST['newemail'].'"<br>
								<br>
								��� ���� ����� ����������� ����� email, �� ������ ����� �� ������:<br>
								http://xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								���������� ���� http://www.xcombats.com<br>
								������������� ����������� �����: support@xcombats.com<br>
								P.S. ������ ������ ������������� �������������, �������� �� ���� �� �����.
					
							</body>
						</html>';

				mail($u->info['mail'],"����� email � ��������� \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo"<font color=red><b>�� ��� email ������� ������ � �������� ����������� �������� ����� email</b></font><BR>";
				}
		}else{ echo "<font color=red><b>�������� ������ ������ ��� email.</b></font><br>"; }

		}
		#-------------------------------------
	}
	/*----������/�����------*/
	if ($_POST['oldpsw1']) {
		$ops = mysql_fetch_array(mysql_query("SELECT `pass`, `securetime`, `emailconfirmation` FROM `users` WHERE `id` = '".mysql_real_escape_string($u->info['id'])."'"));
		if($u->info['securetime']>$time) {echo"<font color=red>������ ������ �� ����� ���� ����� ����� ������ �������������, ������ ��� email.</font><br>";}
		elseif($u->info['emailconfirmation']==0) {
			if ($u->info['pass'] == md5($_POST['oldpsw1'])) {
				if(mysql_query("UPDATE `users` SET `a1` = '".mysql_real_escape_string($_POST['secretquestion'])."', `q1` = '".mysql_real_escape_string($_POST['secretanswer'])."', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"))
				{
				$u->info['secretquestion'] = $_POST['secretquestion'];
				echo "<font color=red>����� ��������� ������ / ����� �������.</font><br>";
				}
				}
				else{echo "<font color=red>�������� ������ ������.</font><BR>";}
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
				//////////////////////////////////////////�������� �� �����///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: ���������� ���� <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>����� ���������� ������� � ������</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								���-�� � IP: '.$ip.' �������� ������� ��������� ������ / ����� � ��������� "'.$u->info['login'].'" ['.$u->info['level'].'].
								�.�. � ������ � ����� ��������� ������ email: '.$u->info['mail'].', �� �� � �������� ��� ������.<br>
								login: '.$u->info['login'].'<br>
								������� ��������� ������: '.$u->info['secretquestion'].'<br>
								������� ��������� �����: '.$u->info['secretanswer'].'<br>
								����� ��������� ������: '.$_POST['secretquestion'].'<br>
								����� ��������� �����: '.$_POST['secretanswer'].'<br>
								��� ���� ����� ����������� �����, �� ������ ����� �� ������:<br>
								http://'.$u->info['city'].'.xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								���������� ���� http://www.xcombats.com<br>
								������������� ����������� �����: support@xcombats.com<br>
								P.S. ������ ������ ������������� �������������, �������� �� ���� �� �����.
					
							</body>
						</html>';

				mail($u->info['mail'],"����� ���������� ������� � ������ � ��������� \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo"<font color=red>�� ��� email ������� ������ � �������� ����������� �������� ����� ���������� ������� � ������</font><BR>";
				}
		}else{ echo "<font color=red>�������� ������ ������.</font><br>"; }

		}
		#-------------------------------------
		$u->info['a1'] = $_POST['secretquestion'];
	}
	/*----������������� �� e-mail----*/
	if ($_POST['email'] && $_POST['set_on_emailconfirmation']) {
			if($u->info['securetime']>$time) {echo"<font color=red>������ ������ �� ����� ���� ����� ����� ������ �������������, ������ ��� email.</font><BR>";}
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
				//////////////////////////////////////////�������� �� �����///////////////////////////////////////
				$headers  = "Mime-Version: 1.1 \r\n";
				$headers .= "Date: ".date("r")." \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: ���������� ���� <support@xcombats.com>\r\n";

				$headers = trim($headers);
				$headers = stripslashes($headers);
	
					$aa='<html>
								<head>
									<title>������������� ������</title>
								</head>
							<body>
								'.date("d.m.y H:i").'<br>
								������ �������� � IP: '.$ip.'<br>
								�.�. � ������ � ����� ��������� ������ email: '.$u->info['mail'].', �� �� � �������� ��� ������.<br>
								��� ���� ����� �������� ������� ������������� ����� ������ � email ����� �����, �� ������ ����� �� ������:<br>
								http://'.$u->info['city'].'.xcombats.com/confirm.php?id='.$u->info['id'].'&code='.$code.'<br>
								<br>
								--<br>
								���������� ���� http://www.xcombats.com<br>
								������������� ����������� �����: support@xcombats.com<br>
								P.S. ������ ������ ������������� �������������, �������� �� ���� �� �����.
					
							</body>
						</html>';

				mail($u->info['mail'],"����� ������������� � ��������� \"".$u->info['login']."\" [".$u->info['level']."]",$aa,$headers);
				//////////////////////////////////////////////////////////////////////////////////////////////////
				echo "<font color=red>�� ��� email ������� ������ � �������� ����������� ��������.</font><BR>";
			}
		}
				else{echo "<font color=red>email ������ �������.</font><BR>";}
	}
	if ($_POST['email'] && $_POST['set_off_emailconfirmation']) { //���������� ������������� �� email
			if($u->info['securetime']>$time) {echo"<font color=red>������ ������ �� ����� ���� ����� ����� ������ �������������, ������ ��� email.</font><BR>";}	
	/*���������� */	
	}
?>
��� ���� ������� ������ ���������, ��� ������ � ���� �������� �� ������� �������, ���������� � ���������. ����� ������� �� ��������� � ��������, ����� �� ��� �� ������� ����� ��� ����� ����������, �������� ��������� (������� ����!) ��������, ������ ��� ������ �������, email �������, ��� ��������/����/�������... ��� ��� ������ ����������� ������... ���� � ����������� �����������, ���������� ��������� ������������ ���� ����������������. � ������:<br>
1. �������, �� ��� ����� ���������, ������ �� �������� ���� ������. �� ���������, �� ������������� �� ����� ����� ��� ������.<br>
2. ������� ����� � ������ ������ �� ��������� �������� <a href=http://www.xcombats.com target="_blank">www.xcombats.com</a> �� �� ����� ������ ������, ������� ����� ��� ��� ����� ������� �� ���, � ���� ��� �������� ������ �� ������ �������� � �������, �� ������� ���� ������! ����� �� �������� �������� ������ ���������.<br>
������������ ����������� �������� ������� <A HREF="http://lib.xcombats.com/" target=_blank>���� ������ � ���������� �����</A>.<BR>
<BR>���� �� ������� �� �������� ���� ��� ������������� �����, ��� ���� ���� ���������� ����� �������, ����������� �������� ������ ������� ������ (��. ����)<br><br>
<fieldset>
<legend><b>������� ������</b></legend>
<table>
	<tr><td align=right>������ ������:</td><td><input type=password name="oldpass"></td></tr>
	<tr><td align=right>����� ������:</td><td><input type=password name="npass"></td></tr>
	<tr><td align=right>����� ������ (��� ���):</td><td><input type=password name="npass2"></td></tr>
	<tr><td align=right><input type=submit value="������� ������" name="changepsw"></td><td></td></tr>
</table>
</fieldset>
</FORM>
<FORM ACTION="main.php?security" METHOD=POST>
<FIELDSET><LEGEND><B> ������� email </B> </LEGEND>

<TABLE>
	<TR><TD align=right>��� ������:</TD><TD><INPUT TYPE=password NAME=oldpsw  size=15 maxlength=31></TD></TR>
	<TR><TD align=right>������� email:</TD><TD><INPUT TYPE=text NAME=oldemail  size=20 maxlength=50></TD></TR>
	<TR><TD align=right>����� email:</TD><TD><INPUT TYPE=text NAME=newemail  size=20 maxlength=50></TD></TR>
	<TR><TD align=center colspan=2><INPUT TYPE=submit value="������� email" name=changeemail></TD></TR>
</TABLE>
</FIELDSET>
</FORM>
<FORM ACTION="main.php?security" METHOD=POST>
<FIELDSET><LEGEND><B> ������� ��������� ������/�����</B> </LEGEND>
<TABLE>
	<TR><TD align=right>��� ������:</TD><TD><INPUT TYPE=password NAME=oldpsw1 size=15 maxlength=31></TD></TR>
	<TR><TD align=right>������ ������:</TD><TD><B><?=htmlspecialchars($u->info['a1'],NULL,'cp1251')?></B></TD></TR>
	<TR><TD align=right>����� ������:</TD><TD><INPUT TYPE=text NAME=secretquestion  size=40 maxlength=50></TD></TR>
	<TR><TD align=right>����� �����:</TD><TD><INPUT TYPE=text NAME=secretanswer  size=40 maxlength=50></TD></TR>
	<TR><TD align=center colspan=2><INPUT TYPE=submit value="�������" name=changesecretqa></TD></TR>
</TABLE>
</FIELDSET>
</FORM>

<U>������</U>, �� �� ������ ������������ �������� email, ������ ��� ��������� ������/�����. ������ ������ ���������� �� ����� ���� �����.<BR>
<FORM ACTION="main.php?security" METHOD=POST>
<FIELDSET><LEGEND><B> ������ ������� ������ </B> </LEGEND>
<?
echo"���� �� ������� � ����� email, ��� �� �������� (������, �������� ����� �� �������� ���� mail.ru hotmail.com � �.�. ����� ������������), �� ��� �� \"��������\", �� �� �������� ��� ����� ���������� � �.�., ����� �� ������ ����������� ������ ���������, ������� ����� ������������� ����� ������/email ����� �����. ��� ������� ������� ������, email ��� ��������� ���� �����, �� ��� email ���������� ������ � �������� ����������� ��� ��������. ����� �������, ���� �����, ���-�� ����� ��� ������, �� �� ������ ������� ���, � �� ������ ������� ����� ��� ����� ����������.<BR>";
if($u->info['emailconfirmation']==0) {?>
��� email <INPUT TYPE=text NAME=email size=20 maxlength=50><BR>
<INPUT TYPE=submit name=set_on_emailconfirmation value="�������� ����� ������������� ����� ������ ��� email ����� �����">
<?}elseif($u->info['emailconfirmation']==1){?>
<BR><B>����� ������������� ����� email �������.</B><BR><BR>
��� email <INPUT TYPE=text NAME=email size=20 maxlength=50> <INPUT TYPE=submit name=set_off_emailconfirmation value="��������� ����� ������������� ����� email"><BR>
<small>���� ��� email ������ �� ��������, �� ������ ��������� ��� ����� ��� �������������, � ������� <b>����</b> ����� ��������� �������� �� ������� � ���������.</small>
<?}?>
</FIELDSET>
</FORM>
<FORM METHOD=POST ACTION="main.php?security">
<FIELDSET><LEGEND><B> ������ ������� ������ </B> </LEGEND>
����������� ��� ������������, ���� �� ������� �� �������� ���� ��� ������������� �����.<BR>
�� ���������� ����� ���� ���������� ������������ �����, ������� ���������� ��� ������� ������, ����� �������, ����� ������ ��� ������.<BR>
��������, � ���� ����������� ���������� "������� ������", ��������������� ��� �������� ������, ������� ����� ������� ��� ������. ����� ����������� ����, �� ������ ���������� ������ ��������� ������ ������, ������� ����� ������� ��� ������ ����� (������������ ������� �� �����������) � ������� ���������� �� ������� ������ � ������������� ����, �� ����������� ����������� ("������� ������" �� ������ ����������� ���).<BR>
��� ������� ������ ��������� ���������� Flash 6! (<I>���� ���� ������ � ������ ������ ��������� ������, ������ � ��� ��� � ������� :</I>)<BR>
<U>������ �����������!</U> ������ ������ ������ �������� �� email ��� ������ ���-���� ���. ���� �� ��� ��������/���������, �� �� ������� ����� � ���������� ���� ����� ����������!<BR>

<?
if(!empty($u->info['pass2'])){echo"<BR><B>������ ������ ����������.</B><BR><BR>������� ������ ������ <INPUT TYPE=password NAME=oldpsw2 size=10 maxlength=8> <INPUT TYPE=submit name=changepsw value=\"��������� ������ ������\" onclick=\"return confirm('��������� ������ ������� ������ ��� ����� � C��?')\">";}else{
?>


����� ������:<BR>
<INPUT TYPE=radio NAME="num_count" value=4> 4 �����<BR>
<INPUT TYPE=radio NAME="num_count" checked value=6> 6 ������<BR>
<INPUT TYPE=radio NAME="num_count" value=8> 8 ������<BR>
<INPUT TYPE=submit name=changepsw value="���������� ������ ������" onclick="return confirm('������� ���� ��������� ��� ������ ������, �� ����� ������� �� ���� ��������, ����� ����, ��� �� ������� OK � ������������� �� email, ��������� � ������. ������ �����������.\n���������� ������ ������?')"><BR>
<?
}
?>

</FIELDSET>
</FORM>