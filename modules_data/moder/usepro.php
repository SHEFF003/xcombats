<?
if(!defined('GAME'))
{
	die();
}
if($p['proverka']==1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" ORDER BY `id` ASC LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['align']>1 && $uu['align']<2 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ������������ ������ �������� �� ���������.<br>';
			}elseif($uu['align']>3 && $uu['align']<4 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ������������ ������ �������� �� ��������.<br>';
			}elseif($uu['palpro']>time())
			{
				$uer = '�������� ��� ����� �������� �� ������� �� '.date('d.m.Y H:i',$uu['palpro']);
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ����������� �������� �� �������';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = '�������� ��������� � ������ ������';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = '�� �� ������ ������� �������� ������ ����';
			}else{
				$uu['palpro'] = time()+60*60*24*7;
				$upd = mysql_query('UPDATE `users` SET `palpro` = "'.$uu['palpro'].'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = '�';
					}
					$rtxt = '[img[items/check.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; ����������'.$sx.' ��� �������� &quot;'.$uu['login'].'&quot; ���� ����� �������. (�� '.date('d.m.Y H:i',$uu['palpro']).').';
					//mysql_query("UPDATE `chat` SET `delete` = 1 WHERE `login` = '".$uu['login']."' LIMIT 1000");
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; ����������'.$sx.' �������� �� ������� �� <b>'.date('d.m.Y H:i',$uu['palpro']).'</b>.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = '�� ������� ��������� ������� � ������� ��������� "'.$uu['login'].'".';
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