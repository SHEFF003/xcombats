<?
if(!defined('GAME'))
{
	die();
}

	$cgo = $_POST['city'];
	if(!isset($u->city_name[$cgo]))
	{
		$uer = '����� "'.$cgo.'" �� ������.';
	}else{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['id']!=$u->info['id'] && $u->info['admin']==0)
			{
				$uer = '�� ������ ��������������� ������ ����';
			}elseif($uu['battle']>0)
			{
				$uer = '�������� ��������� � ���';
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ��������������� ������� � ������ �����';
			}elseif($uu['city']!=$u->info['city'] && $u->info['admin']==0){
				$uer = '�������� ��������� � ������ ������';
			}elseif(floor($uu['align'])==$a && $uu['align']>$u->info['align'] && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ��������������� ������� �� ������';
			}else{
				$rid = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "������" AND `city` = "'.mysql_real_escape_string($cgo).'" LIMIT 1'));
				if(!isset($rid['id']))
				{
					$uer = '������������ � "'.$u->city_name[$cgo].'" ���������!';
				}else{
					$upd = mysql_query('UPDATE `users` SET `city` = "'.mysql_real_escape_string($cgo).'",`room` = "'.$rid['id'].'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
					if($upd)
					{
						$sx = '';
						if($u->info['sex']==1)
						{
							$sx = '�';
						}
						$rtxt = '[img[items/teleport.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; ��������������'.$sx.' ��������� &quot;'.$uu['login'].'&quot; � [img[city_ico/'.$cgo.'.gif]] '.$u->city_name[$cgo].'.';
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
						$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; ��������������'.$sx.' � &quot;<b>'.$u->city_name[$cgo].'</b>&quot;.';
						mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',5)");
						$uer = '�� ������� ��������������� ��������� "'.$uu['login'].'" � <b>'.$u->city_name[$cgo].'</b>.';
					}else{
						$uer = '�� ������� ������������ ������ ��������';
					}
				}
			}
		}else{
			$uer = '�������� �� ������ � ���� ������';
		}
	}
	
?>