<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Шокирующий удар
	Противнику нельзя набирать тактики и использовать приемы 2 хода
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
}elseif( isset($pr_used_this) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl, $priem;	
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
		
		$pvr['x5'] = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`hod` FROM `eff_users` WHERE `uid` = "'.$u2.'" AND `v2` = 191 AND `delete` = 0 LIMIT 1'));
		$pvr['x4'] = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`hod` FROM `eff_users` WHERE `uid` = "'.$u2.'" AND `v2` = 236 AND `delete` = 0 LIMIT 1'));

		if( $a > 0 ) {
			$j = 0; $k = 0; $wp = 3;
			while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 )) {
					if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {					
						if( $pvr['x5']['x'] > 0 ) {
							$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
								"Шокирующий удар",
								"{tm1} '.$btl->addlt($a , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).'",
							'.($btl->hodID + 1).' );';	
							//
							$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
							$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
							//
							mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
							unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
							$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
							//
						}else{
							if( isset($btl->stats[$btl->uids[$uid1]]['antishock']) && $btl->stats[$btl->uids[$uid1]]['antishock'] > 0 && $pvr['x5']['x'] >= 3) { 
								$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
									"Шокирующий удар",
									"{tm1} '.$btl->addlt($a , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).' (Цель полностью защищена от шока)",
								'.($btl->hodID + 1).' );';
								if( isset($pvr['x5']['id']) ) {
									mysql_query('UPDATE `eff_users` SET `hod` = 5,`x` = ( `x` + 1 ) WHERE `id` = "'.$pvr['x5']['id'].'" LIMIT 1');
								}
							}else{
								$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->deleffm(235,'.(0+$uid).','.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].');
									$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
									"Шокирующий удар",
									"{tm1} '.$btl->addlt($a , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).'",
								'.($btl->hodID + 1).' );';	
								mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$pvr['x4']['id'].'" LIMIT 1');
								if( $pvr['x5']['x'] > 1 ) {
									$pvr['x5']['x'] = 1;
								}
								$priem->addPriem($u2,236,'add_notactic=1|add_nousepriem=1',0,77,(2-$pvr['x5']['x']),$u1,5,'шокирующийудар',0,0,0,0,1);
								if( !isset($pvr['x5']['id']) ) {
									$priem->addPriem($u2,191,'add_antishock=1',0,77,5,$u1,5,'иммунитеткошеломить',0,0,0,0,1);		
								}else{
									mysql_query('UPDATE `eff_users` SET `hod` = 5,`x` = ( `x` + 1 ) WHERE `id` = "'.$pvr['x5']['id'].'" LIMIT 1');
								}		
							}
							//
							$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
							$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
							//
							mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
							unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
							$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
							//
						}
					}
				}
				$j++;
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