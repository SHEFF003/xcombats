<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Разгадать тактику
	Снимает все активные приемы на противнике
*/
$pvr = array();
if( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {	
				mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
				unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
		}
		//
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) && isset($pr_moment) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( $a > 0 ) {

			if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$u1][$j_id]) ) {
				//
				$pvr['nouse'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u2.'" AND `name` LIKE "%Агрессивная защита%" AND `delete` = "0" LIMIT 1'));
				if(isset($pvr['nouse']['id'])) {
					//
					$btl->priemAddLogFast( $u1, 0, "Разгадать тактику",
						'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).' (Противник защищен)',
					1, time() );					
					//
				}else{
					//
					$btl->priemAddLogFast( $u1, 0, "Разгадать тактику",
						'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).'',
					1, time() );					
					//
					mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][3].'" AND `uid` = "'.$u1.'" LIMIT 1');
					unset($btl->stats[$btl->uids[$u1]]['u_priem'][$j_id]);
					//Заканчиваем приемы на игроке
					/*$pvr['sp'] = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u2.'" AND `delete` = 0 AND `v1` = "priem" AND
						`v2` != 201 AND `v2` != 211 AND `v2` != 217 AND `v2` != 233 AND `v2` != 188 AND `v2` != 139
						AND `v2` != 191 AND `v2` != 229
						AND `v2` != 175 AND `v2` != 176 AND `v2` != 177 AND `v2` != 178 AND `v2` != 179
						AND `v2` != 206 AND `v2` != 207 AND `v2` != 208 AND `v2` != 209 AND `v2` != 210 AND `v2` != 284
						AND `v2` != 42 AND `v2` != 121 AND `v2` != 122 AND `v2` != 123 AND `v2` != 124 AND `v2` != 125
						AND `v2` != 260
						AND `v2` != 25 AND `v2` != 26 AND `v2` != 27 AND `v2` != 28
						AND `v2` != 22 AND `v2` != 80 AND `v2` != 81 AND `v2` != 82 AND `v2` != 83 AND `v2` != 84
						AND `v2` != 33 AND `v2` != 56 AND `v2` != 57 AND `v2` != 58 AND `v2` != 59 AND `v2` != 60
						AND `v2` != 21 AND `v2` != 73 AND `v2` != 74 AND `v2` != 75 AND `v2` != 76 AND `v2` != 77 AND `v2` != 78 AND `v2` != 79
						
						AND `v2` != 70 AND `v2` != 71 AND `v2` != 72 AND `v2` != 23
						
						AND `v2` != 280
						
						AND `v2` != 238
						
						AND `v2` != 242
						
						AND `v2` != 282
						
						AND `v2` != 268
						
						AND `v2` != 180 AND `v2` != 283
						
					LIMIT 20');*/
					/*$pvr['sp'] = mysql_query('SELECT `a`.* FROM `eff_users` AS `a` WHERE `a`.`uid` = "'.$u2.'" AND `a`.`delete` = 0 AND `a`.`v1` = "priem"
					
						AND `a`.`v2` != 201 AND `a`.`v2` != 211 AND `a`.`v2` != 217 AND `a`.`v2` != 233 AND `a`.`v2` != 188 AND `a`.`v2` != 139
						AND `a`.`v2` != 191 AND `a`.`v2` != 229
						AND `a`.`v2` != 175 AND `a`.`v2` != 176 AND `a`.`v2` != 177 AND `a`.`v2` != 178 AND `a`.`v2` != 179
						AND `a`.`v2` != 206 AND `a`.`v2` != 207 AND `a`.`v2` != 208 AND `a`.`v2` != 209 AND `a`.`v2` != 210 AND `a`.`v2` != 284
						AND `a`.`v2` != 42 AND `a`.`v2` != 121 AND `a`.`v2` != 122 AND `a`.`v2` != 123 AND `a`.`v2` != 124 AND `a`.`v2` != 125
						AND `a`.`v2` != 260
						AND `a`.`v2` != 25 AND `a`.`v2` != 26 AND `a`.`v2` != 27 AND `a`.`v2` != 28
						AND `a`.`v2` != 22 AND `a`.`v2` != 80 AND `a`.`v2` != 81 AND `a`.`v2` != 82 AND `a`.`v2` != 83 AND `a`.`v2` != 84
						AND `a`.`v2` != 33 AND `a`.`v2` != 56 AND `a`.`v2` != 57 AND `a`.`v2` != 58 AND `a`.`v2` != 59 AND `a`.`v2` != 60
						AND `a`.`v2` != 188 AND `a`.`v2` != 233
					
					AND ( SELECT `b`.`neg` FROM `priems` AS `b` WHERE `b`.`id` = `a`.`v2` ORDER BY `b`.`neg` DESC LIMIT 1 ) = 1 LIMIT 20');*/
					/*$pvr['sp'] = mysql_query('SELECT `a`.* FROM `eff_users` AS `a` WHERE `a`.`uid` = "'.$u2.'" AND `a`.`delete` = 0 AND `a`.`v1` = "priem"
					
						AND `a`.`v2` != 139
						AND `a`.`v2` != 188
						AND `a`.`v2` != 226
						AND `a`.`v2` != 211
						AND `a`.`v2` != 49
						AND `a`.`v2` != 233
						AND `a`.`v2` != 227
						AND `a`.`v2` != 220
						AND `a`.`v2` != 191
						AND `a`.`v2` != 235
						AND `a`.`v2` != 236
						
						AND `name` NOT LIKE "%Иммунитет%"
					
					AND ( SELECT `b`.`neg` FROM `priems` AS `b` WHERE `b`.`id` = `a`.`v2` ORDER BY `b`.`neg` DESC LIMIT 1 ) = 1 LIMIT 20');
					while($pvr['pl'] = mysql_fetch_array($pvr['sp'])) {
						$pvr['pl']['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pvr['pl']['v2'].'" LIMIT 1'));
						if( isset($pvr['pl']['priem']) ) {
							$btl->delPriem($pvr['pl'],$btl->users[$btl->uids[$u2]],100);
						}
					}*/
					//
				}
			}	
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	//Действие при клике
	$this->addEffPr($pl,$id);
}
unset($pvr);
?>