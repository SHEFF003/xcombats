<?
if(!defined('GAME'))
{
	die();
}
if($p['marry']==1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		$uu2 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo2']).'" LIMIT 1'));
		if(isset($uu['id']) && isset($uu2['id']))
		{
			
			if($uu['sex'] == $uu2['sex']) {
				$uer = '���������� ��������� ��������� ����, ������ ����� ������������� � ������ �� ������ ;)';
			}elseif($uu['marry']>0)
			{
				$uer = '�������� ��� ��������� � �����<br>';
			}elseif($uu['marry']>0)
			{
				$uer = '�������� ��� ��������� � �����<br>';
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ����������� �������� �� �������';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = '�������� ��������� � ������ ������';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = '�� �� ������ ������������ �� ������ ����';
			}elseif($uu2['admin']>0 && $u->info['admin']==0)
			{
				$uer = '�� �� ������ ����������� �������� �� �������';
			}elseif($uu2['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = '�������� ��������� � ������ ������';
			}elseif($uu2['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = '�� �� ������ ������������ �� ������ ����';
			}else{
				$uu['palpro'] = time()+60*60*24*7;
				$upd = mysql_query('UPDATE `users` SET `marry` = "'.$uu2['id'].'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				$upd = mysql_query('UPDATE `users` SET `marry` = "'.$uu['id'].'" WHERE `id` = "'.$uu2['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = '�';
					}
					$rtxt = '[img[items/marry.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; ����������'.$sx.' ���������� ����� ����� &quot;'.$uu['login'].'&quot; � &quot;'.$uu2['login'].'&quot;.';
					
					mysql_query("UPDATE `chat` SET `delete` = 1 WHERE `login` = '".$uu['login']."' LIMIT 1000");
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; ����������'.$sx.' ���������� ����� � '.$uu2['id'].'.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; ����������'.$sx.' ���������� ����� � '.$uu['id'].'.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu2['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					
					$u->addItem(76,$uu['id'],'sudba='.$uu['login'].'|noremont=1|notransfer=1');
					$u->addItem(76,$uu2['id'],'sudba='.$uu2['login'].'|noremont=1|notransfer=1');
					
					$uer = '�� ������� ������������� ���� "'.$uu['login'].'" � "'.$uu2['login'].'".';
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