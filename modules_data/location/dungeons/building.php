<?php
if(!defined('GAME'))die();

// dungeons/building.php
// name:"Строительство портала"|resource:898x1000,891x500,877x250,900x400,899x400,890x200,876x100,889x100,1035x100

if(isset($file) && $file[0]=='dungeons/building.php' /* && $u->info['admin']  > 0 */ ) {
	
	$need_count = 0;
	$actions = array();
	$resource = array();
	$action = explode('|',$file[1]);
	foreach( $action as $value ) {
		$temp = explode(':',$value);
		$actions[$temp[0]]= $temp[1];
	}
	if( isset($actions['resource']) ){
		$action = explode( ',',$actions['resource'] ); 
		foreach( $action as $value ) { 
			$temp = explode( 'x', $value );
			$resource[] = array(
				'item' => (int)$temp[0],
				'count' => (int)$temp[1]
			);
			$need_count = $need_count+(int)$temp[1];
		}	
	}
	$actions['resource'] = $resource;
	unset($action, $temp, $value, $resource);
	
	# Обьекты для строительства
	if(isset($_GET['buildend'])) {
		//identid
		$obj = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_obj` WHERE `id` = "'.mysql_real_escape_string($actions['identid']).'" LIMIT 1'));
		$txt = '';
		if( !isset($obj['id']) ) {
			$txt = 'Объект строительства был поврежден темной магией...';
		}else{
			if( $obj['x'] == $u->info['x'] && $obj['y'] == $u->info['y'] ) {
				$txt = 'Объект уже был построен ранее! Вернитесь на клетку назад для использования объекта!';
			}else{
				mysql_query('UPDATE `dungeon_obj` SET `x` = "'.$u->info['x'].'", `y` = "'.$u->info['y'].'" WHERE `id` = "'.$obj['id'].'" LIMIT 1');
				mysql_query('UPDATE `dungeon_obj` SET `x` = "'.$u->info['x'].'", `y` = "'.$u->info['y'].'" WHERE `dn` > 0 AND `name` = "'.$obj['name'].'" AND `img` = "'.$obj['img'].'" AND `action` = "'.$obj['action'].'" AND `x` = "'.$obj['x'].'" AND `y` = "'.$obj['y'].'"');
				$txt = 'Строительство &quot;'.$obj['name'].'&quot; завершилось, вернитесь на клетку назад...';
				$u->send('','','','','','Игрок &quot;<b>' . $u->info['login'] . '</b>&quot; завершил строительство объекта &quot;'.mysql_real_escape_string($actions['name']).'&quot;.',time(),6,0,0,0,1,0);
			}
		}
		echo '<div align="left"><font color="red"><b>'.$txt.'</b></font></div>';
	}
	
	# пожертвование
	if( isset($_POST['itemid'],$_POST['action']) ){
		$help = mysql_fetch_assoc(mysql_query('SELECT COUNT(`id`) as count, item_id FROM `items_users` WHERE `item_id` = '.$_POST['itemid'].'  AND `uid` = '.$u->info['id'].' AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" LIMIT 1'));
		if($help){ 
			$item	= mysql_fetch_assoc(mysql_query('SELECT im.`name`, im.`img` FROM `items_main` as im  WHERE im.`id` = '.$help['item_id'].' LIMIT 1')); 
			if( isset($item) && $item['name'] != '' ) { 
				foreach( $actions['resource'] as $key=>$val) { 
					if($_POST['itemid'] == $val['item']){
						$current	= mysql_fetch_assoc(mysql_query('SELECT SUM(b.`count`) as count, im.`name`, im.`img` FROM `items_main` as im LEFT JOIN `building` as `b` ON b.item_id = im.id WHERE b.`building` = "'.$actions['ident'].'" AND im.`id` = '.$val['item'].' LIMIT 1'));
						if($current['count']==NULL) $current['count']=0; else $current['count']=(int)$current['count'];
						if($current['count']+(int)$help['count'] <= $val['count']) {
							# Если ваших ресурсов меньше чем требуется.
						} else {
							# Если ваших ресурсов больше чем требуется.
							$help['count'] = +($val['count']-$current['count']);
						}
						if($help['count'] > 0) {
							mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id` = '.$help['item_id'].' AND `uid` = '.$u->info['id'].' AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" ORDER BY `delete` DESC LIMIT '.$help['count'].'');
							 mysql_query('INSERT INTO building (id, building, uid, clan_id, item_id, count) VALUES (NULL, "'.$actions['ident'].'", '.$u->info['id'].', '.$u->info['clan'].', '.$help['item_id'].', '.$help['count'] .')');
							$d->error = 'Вы пожертвовали на строительство ['.$item['name'].'] в количестве '.$help['count'].'шт.';
						}
					}
				}
				#
				# mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES ("'.$u->info['dnow'].'","'.time().'","'.$u->info['x'].'","'.$u->info['y'].'","'.$u->info['id'].'","building_resource","count_'.$help.'")');
				# 
				#("'.$u->info['dnow'].'","'.time().'","'.$u->info['x'].'","'.$u->info['y'].'","'.$u->info['id'].'","building_resource","count_'.$help.'")');
			}
		}
	}
	unset($_POST,$help, $item);

	
	# отображение
	$count	= mysql_fetch_array(mysql_query('SELECT SUM(count) as count FROM `building` WHERE `building` = "'.$actions['ident'].'" LIMIT 1'));
	if( isset($count[0]) and $count[0]!=NULL ) $count = $count[0]; else $count = 0;
	 $r = '<table width="520" style="border:1px solid gray;padding:4px 4px;" cellpadding="0" cellspacing="0"><tr>
	 <td colspan="2" style="border:1px solid gray;padding:3px 6px;border-right:0px;"><strong>Прогресс строительства %</strong></td>
	 <td align="right" style="border:1px solid gray;padding:3px 6px;border-left:0px;"><table style="display:inline-block;" border="0" cellspacing="0" cellpadding="0" height="10"><tr><td valign="middle" width="120" style="padding-top:12px"><div style="position:relative;"><div id="vhp-1234500000" title="Выполнение строительства" align="left" class="seehp" style="position:absolute; top:-10px; width:120px; height:10px; z-index:12;"> '.round($count/($need_count/100)).'/'.(100).'</div><div title="Выполнение строительства" class="hpborder" style="position:absolute; top:-10px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div><div class="hp_3 senohp" style="height:9px; width:120px; position:absolute; top:-10px; z-index:11;" id="lhp-1234500000"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div><div title="Выполнение строительства" class="hp_none" style="position:absolute; top:-10px; width:120px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div></div></td></tr></table><br><script>top.startHpRegen("main",-1234500000,'.round($count/($need_count/100)).','.( 100 ).',0,0,0,0,0,0,1);</script>';
	$r .= '</td></tr>'; 
	# Текущее состояние строительства.
	foreach( $actions['resource'] as $key=>$val) {
		if($count==0){
			$current	= mysql_fetch_assoc(mysql_query('SELECT im.`name`, im.`img` FROM `items_main` as im WHERE im.`id` = '.$val['item'].' LIMIT 1'));
			$current['count'] = 0;
		} else {
			$current	= mysql_fetch_assoc(mysql_query('SELECT SUM(b.`count`) as count, im.`name`, im.`img` FROM `items_main` as im LEFT JOIN `building` as `b` ON b.item_id = im.id
				WHERE b.`building` = "'.$actions['ident'].'" AND im.`id` = '.$val['item'].' LIMIT 1'));
		}
		if( isset($current) && $current['name'] != '' ) { 
			$actions['resource'][$key]['current'] = (int)$current['count'];
			$actions['resource'][$key]['name'] = $current['name'];
			$actions['resource'][$key]['img'] = $current['img'];
		} else {
			$actions['resource'][$key]['current'] = 0;
			$actions['resource'][$key]['name'] = "Неизвестный предмет";
			$actions['resource'][$key]['img'] = "";
		}
	}
	
	unset($key,$val,$current);
	
	foreach( $actions['resource'] as $row) {
		$current_uid	= mysql_fetch_assoc(mysql_query('SELECT COUNT(`id`) as count FROM `items_users` WHERE `item_id` = '.$row['item'].'  AND `uid` = '.$u->info['id'].' AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0"  LIMIT 1'));
		if(isset($current_uid) && $current_uid['count'] == NULL) $current_uid['count'] = 0; // Сколько можем внести
		
		$r .= '<tr><td style="padding:1px 2px 1px 6px;">['.$row['name'].']</td>';
		if($row['current'] >= $row['count']){
			$input = '';
		} else{
			$input = '<form method="post" id="te'.$row['item'].'"><input style="min-width:24px; border:0px; font-weight:bold; cursor:pointer;cursor:hand; text-align:center;" type="hidden" name="itemid" value="'.$row['item'].'"> <input style="min-width:90px; border:0px; font-weight:bold; cursor:pointer;cursor:hand; text-align:left;" type="SUBMIT" name="action" title="У вас есть '.$current_uid['count'].'шт" value="Пожертвовать"></form>';	
		}
		$r .= '<td style="padding:1px 3px 1px 3px; text-align:right;">'.$input.'</td><td width="125" style="padding:1px 6px 1px 2px; text-align:right;">';
		$r .='<table style="display:inline-block;" border="0" cellspacing="0" cellpadding="0" height="10"><tr><td valign="middle" width="120" style="padding-top:12px"><div style="position:relative;"><div id="vhp-123450'.$row['item'].'" title="'.$row['name'].'" align="left" class="seehp" style="position:absolute; top:-10px; width:120px; height:10px; z-index:12;"> '.round($row['current']/($row['count']/100)).'/'.(100).'</div><div title="'.$row['name'].'" class="hpborder" style="position:absolute; top:-10px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div><div class="hp_3 senohp" style="height:9px; width:120px; position:absolute; top:-10px; z-index:11;" id="lhp-123450'.$row['item'].'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div><div title="'.$row['name'].'" class="hp_none" style="position:absolute; top:-10px; width:120px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div></div></td></tr></table><br><script>top.startHpRegen("main",-123450'.$row['item'].','.round($row['current']/($row['count']/100)).','.(100).',0,0,0,0,0,0,1);</script>';
		$r .= '</td></tr>';
		unset($input,$current_uid);
	}
	
	if( round($count/($need_count/100)) >= 100 ) {
		$r .= '<tr><td style="padding:1px 3px 1px 3px; text-align:center;"><br><input style="margin-left:120px;" class="btnnew2" onclick="location.href=\'/main.php?buildend\';" value="Завершить строительство" type="button"></td></tr>';
	}
	
	$r .= '</table>';
	unset($row);

	
/*
 *	<table width="480" style="border:1px solid gray;padding:4px 4px;" cellpadding="0" cellspacing="0"><tr>
	 <td style="border:1px solid gray;padding:3px 6px;border-right:0px;"><strong>Прогресс строительства</strong></td>
	 <td align="right" style="border:1px solid gray;padding:3px 6px;border-left:0px;">
	 */
	# Идет строительство "'.$actions['name'].'", но нам нехватает ресурсов, помоги добрый странник. 
	$d->information = '<br/>'.$r;
	
	
}