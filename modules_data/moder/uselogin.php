<?
if(!defined('GAME'))
{
	die();
}

if($p['nick']==1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['align']>1 && $uu['align']<2 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ������������ ������ �������� �� ���������.<br>';
			}elseif($uu['align']>3 && $uu['align']<4 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ������������ ������ �������� �� ��������.<br>';
			}elseif($uu['battle']>0)
			{
				$uer = '�������� ��������� � ��������.<br>';
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ����������� ������ ������� ������� �� �������';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = '�������� ��������� � ������ ������';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = '�� �� ������ ������� ����� ������ ����';
			}else{
				function en_ru($txt)
				{
					$g = false;
					$en = preg_match("/^(([a-zA-Z _-])+)$/i", $txt);
					$ru = preg_match("/^(([�-��-� _-])+)$/i", $txt);
					if(($ru && $en) || (!$ru && !$en))
					{
						$g = true;
					}
					return $g;
				}
				function test_login($login,$test) {
					$r = false;
					$blacklist = "!@#$%^&*()\+��|/'`\"";
					$sr = '_-���������������������������������1234567890';
					$i = 0;
					while($i<count($nologin))
					{
						if(preg_match("/".$nologin[$i]."/i",$filter->mystr($login)))
						{
							$error = '��������, ����������, ������ ���.'; $_POST['step'] = 1; $i = count($nologin);
						}
						$i++;
					}
					$login = str_replace('  ',' ',$login);
					//����� �� 2 �� 20 ��������
					if(strlen($login)>20) 
					{ 
						$error = '����� ������ ��������� �� ����� 20 ��������.'; $_POST['step'] = 1;
					}
					if(strlen($login)<2) 
					{ 
						$error = '����� ������ ��������� �� ����� 2 ��������.'; $_POST['step'] = 1;
					}
					//���� �������
					$er = en_ru($login);
					if($er==true)
					{
						$error = '� ������ ��������� ������������ ������ ����� ������ �������� �������� ��� �����������. ������ ���������.'; $_POST['step'] = 1;
					}
					//����������� �������
					if(strpos($sr,$login))
					{
						$error = '����� �������� ����������� �������.'; $_POST['step'] = 1;
					}				
					//��������� � ����
					$log = mysql_fetch_array(mysql_query('SELECT `id` from `users` where `login`="'.mysql_real_escape_string($login).'" LIMIT 1'));
					$log2 = mysql_fetch_array(mysql_query('SELECT `id` from `lastNames` where `login`="'.mysql_real_escape_string($login).'" LIMIT 1'));
					if(isset($log['id']) || isset($log2['id']))
					{
						$error = '����� '.$login.' ��� �����, �������� ������.'; $_POST['step'] = 1;
					}
					//�����������
					if(substr_count($login,' ')+substr_count($login,'-')+substr_count($login,'_')>2)
					{
						$error = '�� ����� ���� ������������ ������������ (������, ����, ������ �������������).'; $_POST['step'] = 1;
					}
					$login = trim($login,' ');	
					if($error != '') {
						$r = $error;
					}else{
						$r = 'good';
					}
					if( $test == true ) {
						
					}else{
						$r = $login;
					}
					return $r;
				}
				$uu['login_new'] = $_POST['logingo2'];
				if(test_login($uu['login_new'],true) == 'good') {
					$uer = '�� ������� ������� �����';
					$uu['login_last'] = $uu['login'];
					$uu['login'] = test_login($uu['login_new'],false);
					$upd = mysql_query('UPDATE `users` SET `login` = "'.$uu['login'].'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
					if($upd)
					{
						$sp = mysql_query('SELECT * FROM `items_users` WHERE `data` LIKE "%sudba='.mysql_real_escape_string($uu['login_last']).'%"');
						while( $pl = mysql_fetch_array($sp) ) {
							$pl['data'] = str_replace('sudba='.$uu['login_last'].'','sudba='.$uu['login'].'',$pl['data']);
							mysql_query('UPDATE `items_users` SET `data` = "'.$pl['data'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						}
						$sx = '';
						if($u->info['sex']==1)
						{
							$sx = '�';
						}
						$rtxt = '[img[items/nick.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; ������'.$sx.' ����� ��������� &quot;'.$uu['login_last'].'&quot; �� &quot;'.$uu['login'].'&quot;';
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
						$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; ������'.$sx.' ����� ���������� � &quot;'.$uu['login_last'].'&quot; �� &quot;'.$uu['login'].'&quot;.';
						mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
						$uer = '�� ������� ������� ����� ��������� &quot;'.$uu['login_last'].'&quot; �� '.$uu['login'].'.';
						mysql_query('INSERT INTO `lastnames` (`uid`,`login`,`newlogin`,`time`) VALUES (
							"'.$uu['id'].'","'.$uu['login_last'].'","'.$uu['login'].'","'.time().'"
						)');
					}else{
						$uer = '�� ������� ������������ ������ ��������';
					}
				}else{
					$uer = '�� ������� ������� �����: '.test_login($uu['login_new']);
				}
			}
		}else{
			$uer = '�������� �� ������ � ���� ������';
		}
}else{
	$uer = '� ��� ��� ���� �� ������������� ������� ��������';
}	
?>