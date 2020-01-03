<?php
header('Content-Type: text/html; charset=windows-1251');
if($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')
{
   if(isset($_POST['id']))
   {
		define('GAME',true);
		include_once('../_incl_data/__config.php');
		include_once('../_incl_data/class/__db_connect.php');
		include_once('../_incl_data/class/__user.php');
		if(isset($_POST['gox']) && isset($_POST['goy']))
		{
			echo 'Переходим на: X = '.$_POST['gox'].', Y = '.$_POST['goy'].'<script>top.xxx='.((int)$_POST['gox']).';top.yyy='.((int)$_POST['goy']).';top.xn='.((int)$_POST['gox']).';top.yn='.((int)$_POST['goy']).';</script>';
			$_POST['x'] = $_POST['gox'];
			$_POST['y'] = $_POST['goy'];
		}
		$u->info['x'] = 0+(int)$_POST['x'];
		$u->info['y'] = 0+(int)$_POST['y'];
		$dn['id2'] = (int)$_POST['ddid'];
		//бой с ботом
		function addBot($isd,$col,$dt)
		{
			global $u,$c,$code;
			$vrs = explode('&',$dt);
			$vr = array();
			$k = 0;
			while($k<count($vrs))
			{
				$ex = explode('=',$vrs[$k]);
				$vr[$ex[0]] = $ex[1];
				$k++;
			}
		}
				
		$md5 = 0;		
		$map = '';
		$obj = '';
		$objd = '';
		$usr = '';
		$js  = '';
		$gg  = 0;
		
		$pix = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.((int)$_POST['ddid']).'" AND `x`='.$u->info['x'].' AND `y`='.$u->info['y'].' LIMIT 1'));		
		if(!isset($_POST['adminion']))
		{
		
		}elseif($u->info['admin']>0)
		{
			//Админка
			$act = explode('|$|',$_POST['action']);
			if($act[0]=='save_go')
			{
				$act = explode('|!|',$act[1]);
				$mx = $act[0];
				$my = $act[1];
				$mpx = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$dn['id2'].'" AND `x`='.mysql_real_escape_string($mx).' AND `y`='.mysql_real_escape_string($my).' LIMIT 1'));
				if(isset($mpx['id']))
				{
					$i = 2;
					while($i<=10)
					{
						if($act[$i]=='true')
						{
							$act[$i] = 1;
						}else{
							$act[$i] = 0;
						}
						$i++;
					}
					mysql_query('UPDATE `dungeon_map` SET
					`go_1` = "'.mysql_real_escape_string($act[2]).'",
					`go_2` = "'.mysql_real_escape_string($act[3]).'",
					`go_3` = "'.mysql_real_escape_string($act[4]).'",
					`go_4` = "'.mysql_real_escape_string($act[5]).'",
					`go_5` = "'.mysql_real_escape_string($act[6]).'",
					`no_bot` = "'.mysql_real_escape_string($act[7]).mysql_real_escape_string($act[8]).mysql_real_escape_string($act[9]).mysql_real_escape_string($act[10]).'"
					WHERE `id` = "'.$mpx['id'].'" LIMIT 1');
					$js .= 'closeAdminion();';
				}
			}elseif($act[0]=='select_image')
			{
				//смена изображения клетки
				$act = explode('|!|',$act[1]);
				$img = $act[0];
				$mx  = $act[1];
				$my  = $act[2];
				$mpx = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$dn['id2'].'" AND `x`='.mysql_real_escape_string($mx).' AND `y`='.mysql_real_escape_string($my).' LIMIT 1'));
				if(isset($mpx['id']))
				{
					$mpx['style'] = $img;
					$cx  = array(0=>'1111',
								 1=>'1110',
								 2=>'1011',
								 3=>'1101',
								 4=>'0111',
								 5=>'1100',
								 6=>'0110',
								 7=>'1001',
								 8=>'0011',
								 9=>'0001',
								10=>'0100',
								11=>'0010',
								12=>'1000',
								13=>'1010',
								14=>'0101',
								15=>'0000',
								16=>'');
					mysql_query('UPDATE `dungeon_map` SET `style` = "'.mysql_real_escape_string($mpx['style']).'", `st` = "'.mysql_real_escape_string($cx[$mpx['style']]).'" WHERE `id` = "'.$mpx['id'].'" LIMIT 1');
					$js .= 'closeAdminion();';
				}else{
					//создаем клетку
					$go1 = 0;
					$go2 = 0;
					$go3 = 0;
					$go4 = 0;
					$go5 = 1;					
					
					$cx  = array(0=>'00000',
								 1=>'00001',
								 2=>'10001',
								 3=>'01001',
								 4=>'00011',
								 5=>'00101',
								 6=>'10011',
								 7=>'10101',
								 8=>'01011',
								 9=>'01101',
								10=>'01111',
								11=>'10111',
								12=>'11101',
								13=>'11011',
								14=>'11001',
								15=>'00111',
								16=>'11111');
								
					
					$go1 = $cx[$img+1][0];
					$go2 = $cx[$img+1][1];
					$go3 = $cx[$img+1][2];
					$go4 = $cx[$img+1][3];
					$go5 = $cx[$img+1][4];
					
					$cx  = array(0=>'1111',
								 1=>'1110',
								 2=>'1011',
								 3=>'1101',
								 4=>'0111',
								 5=>'1100',
								 6=>'0110',
								 7=>'1001',
								 8=>'0011',
								 9=>'0001',
								10=>'0100',
								11=>'0010',
								12=>'1000',
								13=>'1010',
								14=>'0101',
								15=>'0000',
								16=>'');

					$ins = mysql_query('INSERT INTO `dungeon_map` (`st`,`go_1`,`go_2`,`go_3`,`go_4`,`go_5`,`id_dng`,`x`,`y`,`style`) VALUES ("'.$cx[$img][0].$cx[$img][1].$cx[$img][2].$cx[$img][3].'","'.$go1.'","'.$go2.'","'.$go3.'","'.$go4.'","'.$go5.'","'.$dn['id2'].'","'.mysql_real_escape_string($mx).'","'.mysql_real_escape_string($my).'","'.mysql_real_escape_string($img).'")');
					if(!$ins)
					{
						echo 'Ошибка создания части лабиринта';
					}
				}
			}elseif($act[0]=='delete')
			{
				//Удаляем клетку
				$act = explode('|!|',$act[1]);
				$mx  = $act[0];
				$my  = $act[1];
				$mpx = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$dn['id2'].'" AND `x`='.mysql_real_escape_string($mx).' AND `y`='.mysql_real_escape_string($my).' LIMIT 1'));
				if(isset($mpx['id']))
				{
					mysql_query('DELETE FROM `dungeon_map` WHERE `id` = "'.$mpx['id'].'" LIMIT 1;');
					$js .= 'closeAdminion();';
				}
			}
			
		}

			$x = $u->info['x'];
			$y = $u->info['y'];	
			
			//генерируем карту
			
			$fmd5 = '';
							
			//пользователи
					
			//координаты игрока
			$fmd5 .= $x.'_'.$y.'=';		
			$sp = mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$dn['id2'].'" AND ((`x`<='.($x+5).' AND `x`>='.($x-5).') AND (`y`<='.($y+4).' AND `y`>='.($y-4).')) LIMIT 100');
			while($pl = mysql_fetch_array($sp))
			{
				$map .= $pl['id'].'='.$pl['x'].'='.$pl['y'].'='.$pl['style'].'='.$pl['go'].'='.$pl['go_1'].'='.$pl['go_2'].'='.$pl['go_3'].'='.$pl['go_4'].'='.$pl['go_5'].'='.$pl['no_bot'][0].'='.$pl['no_bot'][1].'='.$pl['no_bot'][2].'='.$pl['no_bot'][3].'|';
				$fmd5 .= $pl['id'].'=';
			}
			$map .= 'end';
		
		//предметы на клетке		
		$itms = '';
		$sp = mysql_query('SELECT * FROM `dungeon_items` WHERE `dn` = "'.$dn['id'].'" AND `x` = "'.$x.'" AND `y` = "'.$y.'" AND `take` = "0" LIMIT 100');
		while($pl = mysql_fetch_array($sp))
		{
			$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl['item_id'].'" LIMIT 1'));
			if(isset($itm['id']))
			{
				$itms .= '<a href=\\"#\\" onClick=\\"takeItem('.$pl['id'].'); return false;\\"><img style=\\"margin:3px;\\" src=\\"http://img.xcombats.com/i/items/'.$itm['img'].'\\" title=\\"Подобрать &quot;'.$itm['name'].'&quot;\\" \></a>';
				$fmd5 .= $pl['id'].'=';
			}
		}
		
		if($itms!='')
		{
			$itms = '<Br><b style=\\"color:#8f0000;\\">Предметы в локации:</b><br><br>'.$itms;
			$js .= 'document.getElementById(\'items\').innerHTML = "'.$itms.'";';
		}else{
			$js .= 'document.getElementById(\'items\').innerHTML = "";';
		}
		
		$fmd5 = md5($fmd5);
		if($fmd5!=$_POST['mdf'])
		{
			//обновляем данные			
			echo '<script> ';
			echo $js;
			echo ' users = "'.$mus.'";';
			echo ' obj = "'.$objd.'";';
			echo ' mapNew = "'.$map.'";';
			echo ' md = "'.$fmd5.'";';
			echo ' xn = '.$u->info['x'].'; yn = '.$u->info['y'].'; refleshMapDate();';
            echo '</script>';
		}else{
			//изменений нет
			
		}
	}
}
?>