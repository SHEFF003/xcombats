<?
if(!defined('GAME'))
{
	die();
}

if($p['usealign3']==1 && $u->info['admin'] > 0)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($u->testAlign( 3 , $uu['id'] ) == 0 ) {
				$uer = '� ��������� ����� ����������� �� ����� ����������. �� �� ������ ������ ������ ����������!<br>';
			}elseif($uu['clan'] > 0) {
				$uer = '�� �� ������ ������������ ������ �������� �� ���������� � ������.<br>';
			}elseif($uu['align'] > 0)
			{
				$uer = '�� �� ������ ������������ ������ �������� �� ���������� �� �����������.<br>';
			}else{
				$upd = mysql_query('UPDATE `users` SET `align` = "3" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$u->insertAlign( 3 , $uu['id'] );
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = '�';
					}
					$rtxt = '[img[items/pal_button[dark].gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; ��������'.$sx.' ������ ���������� ��������� &quot;'.$uu['login'].'&quot;';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; ���������'.$sx.' ������ ���������� ���������.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = '�� ������� ��������� ������ ���������� ��������� "'.$uu['login'].'".';
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