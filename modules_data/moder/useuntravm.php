<?
if(!defined('GAME'))
{
	die();
}

if($p['useuntravm']==1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			$trvm = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE (`id_eff` = "4" OR `id_eff` = "5") AND `uid` = "'.$uu['id'].'" AND `delete` = "0" LIMIT 1'));
			if($uu['battle'] > 0) {
				$uer = '�������� ��������� � ��������.<br>';
			}elseif(!isset($trvm['id'])) {
				$uer = '� ��������� ��� ������.<br>';
			}else{
				$upd = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE (`id_eff` = "4" OR `id_eff` = "5") AND `uid` = "'.$uu['id'].'" AND `delete` = "0"');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = '�';
					}
					$rtxt = '[img[items/cure3.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; �������'.$sx.' ��������� &quot;'.$uu['login'].'&quot; �� �����.';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; �������'.$sx.' �� �����';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = '�� ������� �������� ��������� "'.$uu['login'].'" �� �����.';
				}else{
					$uer = '�� ������� ������������ ������ ��������';
				}
			}
		}else{
			$uer = '�������� �� ������ � ���� ������';
		}
}else{
	$uer = '� ��� ��� ���� �� ������������� ������� ��������';
}	
?>