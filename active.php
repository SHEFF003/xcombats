<?php
/*

	���������� ������ � ����� ��������� ������
����
*/

die();

define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

function error($e)
{
	 global $c;
	 die('<html><head>
	 <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	 <meta http-equiv="Content-Language" content="ru"><TITLE>��������� �������� ��������</TITLE></HEAD>
	 <BODY text="#FFFFFF"><p><font color=black>
	 ��������� �������: <pre>'.$e.'</pre><b><p><a href="http://xcombats.com/">�����</b></a><HR>
	 <p align="right">(c) <a href="http://'.$c['host'].'/">'.$c['name'].'</a></p>
	 <!--Rating@Mail.ru counter--><!--// Rating@Mail.ru counter-->
	 </body></html>');
}

$u  = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login`="'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass`="'.mysql_real_escape_string($_COOKIE['pass']).'"'));
if(isset($u['id'])) {
	if($u['send'] == '0') {
		error('���������� ������ <b>e-mail</b> ��� ��������� ���������.<br>�������� ��� ������ � ��������, � ������� �� ��������������� �� ������ ���������.');
	}elseif($u['activ'] == 0) {
		error('�������� &quot;'.$u['login'].'&quot; ��� ����������� �����.');
	}else{
		if($_GET['code'] == md5($u['login'].'&[xcombats.com]') || $_GET['code'] == 'ILIKECOMBATS') {
			mysql_query('UPDATE `users` SET `activ` = "0" WHERE `id` = "'.$u['id'].'" LIMIT 1');
			error('�� ������� ������������ ���������, ������� � ����� ����!');
		}else{
			error('�� ������ ��� ���������.');
		}
	}
}else{
	error('<form method="post" action="enter.php">'.
    '������� ����� � ������ �� ���������:<br>'.
    '�����: &nbsp;<input name="login" type="text" style="width:200px;"><br />'.
    '������: <input name="pass" type="password" style="width:200px;">'.
    '<input name="active_code_key" type="hidden" value="'.htmlspecialchars($_GET['code'],NULL,'cp1251').'" /><br />'.
    '<input value="������������ ���������" type="submit" />'.
    '</form>');
}
?>