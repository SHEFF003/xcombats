<?
if(!defined('GAME'))
{
	die();
}
if($u->info['admin']>0 || ($u->info['align']>=3 && $u->info['align']<4))
{
	$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
	$ust = $u->getStats($uu['id'],0);
	if(isset($uu['id']))
	{
		if($uu['id'] == $u->info['id'])
		{
			$uer = '�� �� ������ ������ ������ ����';
		}elseif($u->info['battle']>0)
		{
			$uer = '�� �� ������ ������ � ���';
		}elseif($ust['hpNow']<($ust['hpAll']/100*15))
		{
			$uer = '�� �� ������ ������� ����� ���������, ������ ������� �����';
		}elseif($uu['level']>$u->info['level'])
		{
			$uer = '�� �� ������ ������ ���������� ������ ��� �� ������';
		}elseif(date('H',time())>6 && date('H',time())<21 && $u->info['admin']==0)
		{
			$uer = '������� �� ����� �������� ����';
		}elseif($u->stats['hpNow'] >= ($u->stats['hpAll']/100*67) && $u->info['admin']==0)
		{
			$uer = '�� �� ���������� � ����, ���� �������� ������������� ���� ...';
		}elseif(floor($uu['align'])==3 && $u->info['admin']==0)
		{
			$uer = '�� �� ������ ������ ������';
		}elseif($uu['online']<time()-120)
		{
			$uer = '�������� ������ �������';
		}elseif($uu['room']!=$u->info['room'])
		{
			$uer = '�� ������ ��������� � ����� ������� � �������';
		}elseif($uu['battle']>0)
		{
			$uer = '�������� ��������� � ���';
		}else{
			$sx = ''; $sx2 = '��';
			if($u->info['sex']==1)
			{
				$sx = '�'; $sx2 = '��';
			}
			$itm1 = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$uu['id'].'" AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `iznosNOW` <= `iznosMAX` AND `item_id` = 1164 LIMIT 1'));
			if(isset($itm1['id']))
			{
				$uer = '�� ������� ������ ������� "'.$uu['login'].'", � ��������� ��� ��� ���� ��� ������ ��������. ������� ��� � �������!<br>';
				mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW`+1 WHERE `id` = "'.$itm2['id'].'" LIMIT 1');
			}else{
				$itm2 = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$uu['id'].'" AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `iznosNOW` <= `iznosMAX` AND `item_id` = 1163 LIMIT 1'));
				if(isset($itm2['id']))
				{
					$sx = '����'; $sx2 = '��';
					if($uu['sex']==1)
					{
						$sx = '��'; $sx2 = '��';
					}
					$uer = '�� ������� ������ ������� "'.$uu['login'].'", � ��������� ��� ��� ���� <span title="'.ceil($itm2['iznosNOW']).'/'.floor($itm2['iznosMAX']).'">������</span> ������ ��������. �� �������� ��� ��������.<br>';
					mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW`+1 WHERE `id` = "'.$itm2['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `hpNow` = "1",`regHP` = "'.time().'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$rtxt = '[img[items/chesnok2.gif]] ������ &quot;'.$u->info['cast_login'].'&quot; �������� ������'.$sx.' �.�. � &quot;'.$uu['login'].'&quot; ��� ��� ���� ������';
				}else{
					$rtxt = '[img[items/vampir.gif]] ���������'.$sx2.' &quot;'.$u->info['cast_login'].'&quot; �����'.$sx.' �� ��������� &quot;'.$uu['login'].'&quot; � �����'.$sx.' ��� �������.';
					$u->stats['hpNow'] += $ust['hpNow'];
					if($u->stats['hpNow']>$u->stats['hpAll'])
					{
						$u->stats['hpNow'] = $u->stats['hpAll'];	
					}
					mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpAll'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `hpNow` = "1",`regHP` = "'.time().'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$uer = '�� ������� ������ ��� ����� � ��������� "'.$uu['login'].'".<br>';
				}
			}
			unset($itm1,$itm2);
		}
	}else{
		$uer = '�������� �� ������ � ���� ������';
	}
}else{
	$uer = '� ��� ��� ���� �� ������������� ������� ������';
}	
?>