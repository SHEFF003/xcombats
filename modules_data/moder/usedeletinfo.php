<?
if(!defined('GAME'))
{
	die();
}
if($p['deletInfo']==1)
{
	$tm = (int)$_POST['time'];
	if($tm!=1 && $tm!=7 && $tm!=14 && $tm!=30 && $tm!=60)
	{
		$uer = '������� ������� ������';
	}else{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['info_delete']!=1 && $uu['info_delete']<time())
			{
				$srok = array(
					1=>'���������',
					7=>'������',
					14=>'��� ������',
					30=>'�����',
					60=>'��� ������'
				);
				$srok = $srok[$tm];
				if($tm==1)
				{
					$tm = '`info_delete` = "1"';
				}elseif($tm==7)
				{
					$tm = '`info_delete` = "'.(time()+7*86400).'"';
				}elseif($tm==14)
				{
					$tm = '`info_delete` = "'.(time()+14*86400).'"';
				}elseif($tm==30)
				{
					$tm = '`info_delete` = "'.(time()+30*86400).'"';
				}elseif($tm==60)
				{
					$tm = '`info_delete` = "'.(time()+60*86400).'"';
				}
				$upd = mysql_query('UPDATE `users` SET '.$tm.' WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = '�';
					}
					$rtxt = '[img[items/cui.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; �����������'.$sx.' �������� ������������� �� &quot;'.$uu['login'].'&quot; ������ '.$srok;
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; �����������'.$sx.' �������� &quot;<b>�������������</b>&quot;, ������ '.$srok.'.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = '�� ������� ������������ �������� ������������� �� ��������� "'.$uu['login'].'".<br>';
				}else{
					$uer = '�� ������� ������������ ������ ��������';
				}
			}else{
				$uer = '�������� ��� ���������';
			}
		}else{
			$uer = '�������� �� ������ � ���� ������';
		}
	}
}else{
	$uer = '� ��� ��� ���� �� ������������� ������� ��������';
}	
?>