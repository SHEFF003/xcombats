<?
if(!defined('GAME'))
{
	die();
}
if($p['haos']==1)
{
	$tm = (int)$_POST['time'];
	$tmban = array(7=>'���� ������',14=>'��� ������',30=>'���� �����',60=>'��� ������',1=>'���������');
	if($tm!=7 && $tm!=14 && $tm!=30 && $tm!=60 && ($tm!=1 || ($p['haosInf']==0 && $tm==1)))
	{
		$uer = '������� ������� ����� ���������';
	}else{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['align']>1 && $uu['align']<2 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ���������� �������� � ����';
			}elseif($uu['align']>3 && $uu['align']<4 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ���������� ������� � ����';
			}elseif($uu['align']==2)
			{
				$uer = '�������� ��� ����� ��������� � ����';
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ���������� ������� � ����';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = '�������� ��������� � ������ ������';
			}elseif(floor($uu['align'])==$a && $uu['align']>$u->info['align'] && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ����������� �������� �� ������� �� ������';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = '�� �� ������ ����������� �������� �� ������ ����';
			}else{
				$th = time()+($tm*24*60*60);
				if($tm==1)
				{
					$th = 1;
				}
				$upd = mysql_query('UPDATE `users` SET `align` = "2",`clan` = "0",`haos` = "'.mysql_real_escape_string($th).'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = '�';
					}
					mysql_query('UPDATE `users_delo` SET `hb` = "0" WHERE `uid` = "'.$uu['id'].'" AND `hb`!="0"');
					$rtxt = '[img[items/pal_button4.gif]] '.$rang.' &quot;'.$u->info['login'].'&quot; ��������'.$sx.' ��������� &quot;'.$uu['login'].'&quot; � ���� �� ����: '.$tmban[$tm].'';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; ��������'.$sx.' � &quot;<b>����</b>&quot; �� ����: '.$tmban[$tm].'.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = '�� ������� ��������� ��������� "'.$uu['login'].'" � ���� �� ����: '.$tmban[$tm].'.';
				}else{
					$uer = '�� ������� ������������ ������ ��������';
				}
			}
		}else{
			$uer = '�������� �� ������ � ���� ������';
		}
	}
}else{
	$uer = '� ��� ��� ���� �� ������������� ������� ��������';
}	
?>