<?
if(!defined('GAME'))
{
	die();
}

if($p['attack']==1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($u->room['noatack'] == 1) {
				$uer = '� ������ ������� ��������� ���������!';
			}elseif($uu['id'] == $u->info['id']) {
				$uer = '�������� �� ���� ������! :)';
			}elseif($uu['room'] != $u->info['room']) {
				$uer = '�� ���������� � ������ ��������<br>';
			}else{
				
				$ua = mysql_fetch_array(mysql_query('SELECT `s`.*,`u`.* FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `s`.`id` = `u`.`id` WHERE `s`.`id` = "'.mysql_real_escape_string($uu['id']).'" LIMIT 1'));
				if(isset($ua['id']) && $ua['online'] > time()-520) {
					
					$usta = $u->getStats($ua['id'],0); // ����� ����
					$minHp = floor($usta['hpAll']/100*33); // ����������� ����� �������� ���� ��� ������� ����� �������
					
					if( $ua['battle'] > 0 ) {
						$uabt = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$ua['battle'].'" AND `team_win` = "-1" LIMIT 1'));
						if(!isset($uabt['id'])) {
							$ua['battle'] = 0;
						}
					}
					
					if( $ua['battle'] == 0 && $minHp > $usta['hpNow'] ) {
						$uer = '������ �������, � ���������� �� �������������� ��������';
					}elseif( isset($uabt['id']) && $uabt['type'] == 500 && $ua['team'] == 1 ) {
						$uer = '������ ��������� �� ������� ��������!';
					}elseif( isset($uabt['id']) && $uabt['invis'] > 0 ) {
						$uer = '������ ����������� � ��������� ���!';
					}elseif( $magic->testAlignAtack( $u->info['id'], $ua['id'], $uabt) == false ) {
						$uer = '������ �������� ��������� �����������!';
					}elseif( $magic->testTravma( $ua['id'] , 3 ) == true ) {
						$uer = '��������� ������ �����������, ������ �������!';
					}elseif( $magic->testTravma( $u->info['id'] , 2 ) == true ) {
						$uer = '�� ������������, ������ �������!';
					}elseif($ua['room']==$u->info['room'] && ($minHp <= $usta['hpNow'] || $ua['battle'] > 0))
					{
					
						mysql_query('UPDATE `stats` SET `hpNow` = "'.$usta['hpNow'].'",`mpNow` = "'.$usta['mpNow'].'" WHERE `id` = "'.$usta['id'].'" LIMIT 1');
				
						$magic->atackUser($u->info['id'],$ua['id'],$ua['team'],$ua['battle'],$ua['bbexp'],$ua['type_pers']);
					
						$sx = '';
						if($u->info['sex']==1)
						{
							$sx = '�';
						}
						$rtxt = '[img[items/pal_button8.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; ��������'.$sx.' ��������� �� ��������� &quot;'.$uu['login'].'&quot;.';
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
						
						header('location: main.php');
					}
				}else{
					$uer = '�������� ������ ���������� � �������';
				}
				
				/*$upd = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$uu['id'].'" AND `name` LIKE "%������"');
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
				}*/
			}
		}else{
			$uer = '�������� �� ������ � ���� ������';
		}
}else{
	$uer = '� ��� ��� ���� �� ������������� ������� ��������';
}	
?>