<?
$step = 1;
$error = '';

if(isset($_GET['login'])) {
	$_POST['relogin'] = $_GET['login'];
}

//die('�������������� ������ ����������. �������� ������ ��������� � �������� ������ ����������.');

function GetRealIp()
{
 if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
 {
   $ip=$_SERVER['HTTP_CLIENT_IP'];
 }
 elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
 {
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }
 else
 {
   $ip=$_SERVER['REMOTE_ADDR'];
 }
 return $ip;
}

define('IP',GetRealIp());

			function send_mime_mail($name_from, // ��� �����������
							   $email_from, // email �����������
							   $name_to, // ��� ����������
							   $email_to, // email ����������
							   $data_charset, // ��������� ���������� ������
							   $send_charset, // ��������� ������
							   $subject, // ���� ������
							   $body // ����� ������
							   )
			   {
			  $to = mime_header_encode($name_to, $data_charset, $send_charset)
							 . ' <' . $email_to . '>';
			  $subject = mime_header_encode($subject, $data_charset, $send_charset);
			  $from =  mime_header_encode($name_from, $data_charset, $send_charset)
								 .' <' . $email_from . '>';
			  if($data_charset != $send_charset) {
				$body = iconv($data_charset, $send_charset, $body);
			  }
			  $headers = "From: $from\r\n";
			  $headers .= "Content-type: text/html; charset=$send_charset\r\n";
			
			  return mail($to, $subject, $body, $headers);
			}
			
			function mime_header_encode($str, $data_charset, $send_charset) {
			  if($data_charset != $send_charset) {
				$str = iconv($data_charset, $send_charset, $str);
			  }
			  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
			}
			
			function send_mail($to,$to_name,$from = 'support@xcombats.com',$name = '<b>���������� ����</b> 2',$title,$text) {
				send_mime_mail($name,
					   $from,
					   $to_name,
					   $to,
					   'CP1251',  // ���������, � ������� ��������� ������������ ������
					   'KOI8-R', // ���������, � ������� ����� ���������� ������
					   $title,
					   $text); // \r\n
			}

	if(isset($_POST['relogin'])) {
		$_POST['relogin'] = htmlspecialchars($_POST['relogin'],NULL,'cp1251');
		
		include('_incl_data/__config.php');
		define('GAME',true);
		include('_incl_data/class/__db_connect.php');
		
		$usr = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['relogin']).'" LIMIT 1'));
		if(isset($usr['id'])) {
			
			if($usr['admin'] == 0 && $usr['banned'] == 0) {
				$step = 2;
				if(isset($_POST['redate'])) {
					//������ ���
					$lst_psw = mysql_fetch_array(mysql_query('SELECT * FROM `repass` WHERE `uid` = "'.$usr['id'].'" AND `time` > '.(time()-60*60*24).' AND `type` = "1" LIMIT 1'));
					if(isset($lst_psw['id'])) {
						$error = '�������� ������ ����� �� ����� ������ ���� � �����.';
					}elseif(str_replace('0','',$_POST['redate']) == str_replace('0','',$usr['bithday']) && ($_POST['reanswer'] == $usr['q1'] || $usr['q1'] == '')) {
						$error = '<br><br><br>������ �� ��������� &quot;'.$usr['login'].'&quot; ��� ������� ������ �� E-mail ��������� ��� �����������!<br><br><br>';
						$re = mysql_fetch_array(mysql_query('SELECT * FROM `logs_auth` WHERE `uid` = "'.$usr['id'].'" AND `type` = "0" AND `depass` != "" ORDER BY `id` DESC LIMIT 1'));
						if($u['securetime'] < $c['securetime'] ) {
							unset($re);
						}
						if(!isset($re['id'])) {
							$sm = array('a','b','c','d','e','f','x','d','f','X','e','ER','XX','X');
							$re['depass'] = $sm[rand(0,12)].rand(0,9).$sm[rand(0,12)].rand(0,9).$sm[rand(0,12)].rand(0,9).$sm[rand(0,12)].rand(0,9).$sm[rand(0,12)].rand(0,9);
							//$error = '�������� �������� �� ��������.<br>������ �� ���������: </b>'.$re['depass'].'<b>';
						}else{
							//$error = '�������� �������� �� ��������.<br>������ �� ���������: </b>'.$re['depass'].'<b>';
						}
						$title = '�������������� ������ �� "'.$usr['login'].'".';
						$txt   = '������ ����.<br>';
						$txt  .= '� IP-������ - <b>'.IP.'</b>, ��� �������� ������ ��� ������ ���������.<br>���� ��� �� ��, ������ ������� ��� ������.<br><br>';
						$txt  .= '��� �����: <b>'.$usr['login'].'</b><br>';
						$txt  .= '��� ������: '.$re['depass'].'<br><br>';
						$txt  .= '�������� �� ������ ������ �� �����.<br><br>';
						$txt  .= '� ���������,<br>';
						$txt  .= '������������� ����������� �����';
						
						//if(send_mail($urs['mail'],$urs['login'],'support@xcombats.com','��2 - Support',$title,$txt)) {		
						if(send_mime_mail('���������� ���� - Support',
						   'support@xcombats.com',
						   ''.$usr['login'].'',
						   $usr['mail'],
						   'CP1251',  // ���������, � ������� ��������� ������������ ������
						   'KOI8-R', // ���������, � ������� ����� ���������� ������
						   $title,
						   $txt))
						{				
							mysql_query('UPDATE `users` SET `securetime` = "'.time().'" , `allLock`="'.(time()+60*60*24*0).'",`pass` = "'.mysql_real_escape_string(md5($re['depass'])).'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
							mysql_query('INSERT INTO `repass` (`uid`,`ip`,`type`,`time`) VALUES ("'.$usr['id'].'","'.mysql_real_escape_string(IP).'","1","'.time().'")');
							$step = 3;							
						}else{							
							$error = '�� ������� ��������� ���������. ���������� �����.';							
						}
		
					}else{
						$error = '�������� ����� �� ��������� ������ ��� ������� ������ ���� ��������.';
					}
				}
			}else{
				$error = '��������� "'.$_POST['relogin'].'" ��������� ������� ������!';
			}
		}else{
			$error = '����� "'.htmlspecialchars($_POST['relogin'],NULL,'cp1251').'" �� ������ � ����.';
		}
	}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>�������������� ������ �� ���������</title>
<meta name="keywords" content="����, ������, �������, ������,online, ��������, internet, RPG, fantasy, �������, ���, �����, �����, �����, ����, ����, �����, ������, ���������� ����, ���, �����, �����, ��������, �����������, ����������� ����������, ������, ���, ����������, ���, ������, �����, ����, ����, bk, games, ����, ����, �������, ����, ��, combats, ���������� ����, �������, �������� ��, �� 2003, ����� ������, ��������, ������ ����, ������ ��, ������ ���������� ����, ����������, antibk, antikombatz, online, online rpg, rpg">
<meta name="description" content="����� ���������� ��������������������� MMORPG ������ ���� ������� ���������� ���� - �� II�. ����� ���������� ������������ ����������� �����!"/>

<link href="homepage/index.css" type="text/css" rel="stylesheet">

</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="100" align="center" valign="middle"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="200" align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle"><p>&nbsp;</p>
          <p>&nbsp;</p></td>
        <td width="200">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="135" align="left" valign="middle" background="http://top.xcombats.com/images/sitebk_02.jpg" style="background-repeat:repeat-x">&nbsp;</td>
          <td width="428" height="135" align="center" valign="middle" background="http://top.xcombats.com/images/sitebk_02.jpg" style="background-repeat:repeat-x"><img src="http://xcombats.com/inx/newlogo.jpg" width="360" height="135"></td>
          <td width="135" align="right" valign="middle" background="http://top.xcombats.com/images/sitebk_02.jpg" style="background-repeat:repeat-x">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle"><img style="padding-left:20px;" src="homepage/18adult.gif" width="175" height="75"><br><br>
<!--LiveInternet counter-->

<!--/LiveInternet-->
          </td>
          <td height="150" align="center" valign="middle" class="menu">
          <p><b>������ ������ �� ������ ���������?</b></p>
          <p>&nbsp;<?
          if($error != '') {
		  	echo '<font color="red"><b>'.$error.'</b></font>';
		  }
		  ?></p>
          <form method="post" action="http://xcombats.com/repass.php">
         <?
		    if($step == 1){ ?>
                  <table width="400" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>������� ����� ���������:</td>
                      <td><input onfocus="if ( '�����' == value ) { value = ''; } " onblur="if ( '' == value ) { value = '�����'; } " value="�����" maxlength="40" style="padding:3px" name="relogin" type="text" class="inup" id="relogin"></td>
                    </tr>
              </table><br>
                    <input type="submit" class="btn" value="������� � ���������� ����">
            <? }elseif($step == 2){ ?>
                  <table width="400" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>����� ���������:</td>
                      <td><input value="<?=$_POST['relogin']?>" disabled maxlength="40" style="padding:3px" type="text" class="inup"><input type="hidden" name="relogin" value="<?=$_POST['relogin']?>"></td>
                    </tr>
                    <?
					if($usr['a1'] != ''){ ?>
                    <tr>
                      <td>��� ������:</td>
                      <td>&nbsp;<b><?=$usr['a1']?></b></td>
                    </tr>
                    <tr>
                      <td>��� �����:</td>
                      <td><input value="<?=$_POST['reanswer']?>" name="reanswer" maxlength="30" style="padding:3px" type="text" class="inup"></td>
                    </tr>
                    <? } ?>
                    <tr>
                      <td>��� ���� ��������:</td>
                      <td><input value="<?=$_POST['redate']?>" name="redate" maxlength="10" style="padding:3px" type="text" class="inup"></td>
                    </tr>
                    </table>
                    <small class="testro">(���� �������� �� ��������� ��� ����������� ��������� � ������� dd.mm.yyyy)</small>
                    <br>
                    <br>
                  <br>
              <input type="button" onclick="top.location.href='http://xcombats.com/repass.php'" class="btn" value="���������">
                    <input type="submit" class="btn" value="������� ������ �� E-mail">
            <? } ?>
            </form>
            <br><br><br><br>
            </td>
          <td align="right" valign="middle"><img style="padding-right:20px;" src="homepage/change_warn.gif" width="185" height="75"></td>
        </tr>
    </table>
      <div align="center" class="menu">
      	<a href="http://xcombats.com/lib/">����������</a>  &nbsp; 
        <a href="http://xcombats.com/lib/zakon/">������</a>  &nbsp; 
        <a href="http://xcombats.com/lib/polzovatelskoe-soglashenie/">����������</a>  &nbsp; 
        <a href="http://events.xcombats.com/">�������</a>  &nbsp; 
        <a href="http://xcombats.com/forum/">�����</a>  &nbsp; 
        <a href="http://top.xcombats.com/">�������</a>  &nbsp; 
        <a href="http://xcombats.com/">��������� ��������</a>
      </div>
    </td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td height="50" align="center"><span class="testro">&laquo;������ ���������� ����&raquo; &nbsp; &nbsp; 2014-<?=date('Y')?> &copy; ���������� ������ ����</span></td>
  </tr>
</table>
</body>
</html>
