<?
if(!defined('GAME'))
{
	die();
}
if($p['banned']==1 || $p['ban0']==1)
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
			}elseif($uu['banned']>0)
			{
				$uer = '�������� ��� ������������ �����';
			}elseif($p['banned']==0 && $uu['level']>0)
			{
				$uer = '�� �� ������ ����������� �������� ������ �� ���������� ������ 0-�� ������';
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ����������� �������� ������ �� �������';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = '�������� ��������� � ������ ������';
			}elseif(floor($uu['align'])==$a && $uu['align']>$u->info['align'] && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ����������� �������� ������ �� ������� �� ������';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = '�� �� ������ ����������� �������� ������ �� ������ ����';
			}else{
				$upd = mysql_query('UPDATE `users` SET `banned` = "'.time().'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = '�';
					}
					$rtxt = '[img[items/pal_button6.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; �������'.$sx.' �������� ������ �� &quot;'.$uu['login'].'&quot;';
					mysql_query("UPDATE `chat` SET `delete` = 1 WHERE `login` = '".$uu['login']."' LIMIT 1000");
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; �������'.$sx.' �������� &quot;<b>������</b>&quot;.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = '�� ������� �������� �������� ������ �� ��������� "'.$uu['login'].'".';
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