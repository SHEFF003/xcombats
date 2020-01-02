<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Духи льда
*/
$pvr = array();
if( isset($pr_momental_this_seven)) {
	$fx_moment_seven = function(  $uid, $enemy, $j_id, $yron, $profil, $inlog ) {
		global $u,$btl;
		/*if( $inlog == 0 ) {
			$pvr['test1'] = 1;
		}else{
			$pvr['test1'] = 0;
		}
		$btl->priemAddLogFast( $uid, 0, "Духи Льда",
			'{tm1} {u1} увидел бабочку и побежал за ней... ['.$yron.']',
		$pvr['test1'], time() );*/
		return $yron;	
	};
}elseif( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil, $inlog ) {
		//if(!isset($btl->stats[$btl->uids[$uid]]['um_priem'][$j_id])) {
			global $u, $btl;
			//$yron = 1;
			//
			if( $inlog == 0 ) {
				$pvr['test1'] = 1;
			}else{
				$pvr['test1'] = 0;
			}
			//
			$pvr['mp'] = round($yron*0.25);
			$pvr['mpSee'] = '--';
			$pvr['mpNow'] = $btl->stats[$btl->uids[$uid]]['mpNow'];
			$pvr['mpAll'] = $btl->stats[$btl->uids[$uid]]['mpAll'];
			//
			if( $pvr['mp'] > 0 ) {
				$pvr['mpSee'] = '+'.$pvr['mp'];
			}else{
				$pvr['mp'] = 0;
			}
			$pvr['mpNow'] += $pvr['mp'];
			if( $pvr['mpNow'] < 0 ) {
				$pvr['mpNow'] = 0;
			}elseif( $pvr['mpNow'] > $pvr['mpAll'] ) {
				$pvr['mpNow'] = $pvr['mpAll'];
			}
			//
			$btl->stats[$btl->uids[$uid]]['mpNow'] = $pvr['mpNow'];					
			mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->stats[$btl->uids[$uid]]['mpNow'].'" WHERE `id` = "'.$uid.'" LIMIT 1');
			//
			$btl->priemAddLogFast( $uid, 0, "Духи Льда",
				'{tm1} {u1} восстановил ману заклятием &quot;{pr}&quot;. <font color=green><b>'.$pvr['mpSee'].'</b></font> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (Мана)',
			$pvr['test1'], time() );
			if( $yron < 0 ) {
				$yron = 1;
			}
			$btl->stats[$btl->uids[$uid]]['um_priem'][$j_id] = true;
		//}
		return round($yron);
	};
}elseif( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		/*global $u, $btl;	
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
				//mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
				//unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
		}*/
		//
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) ) { 
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
			$j = 0; $k = 0; $wp = 3;
			while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 )) {
					if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {
						//
						/*
						$at['p'][$a]['atack'][$j]['yron']['y'] += 4;
						$at['p'][$a]['atack'][$j]['yron']['r'] -= 4;
						$at['p'][$a]['atack'][$j]['yron']['k'] += 4;
						$at['p'][$a]['atack'][$j]['yron']['m_y'] += 4;
						$at['p'][$a]['atack'][$j]['yron']['m_k'] += 4;
						*/
						//
						$pvr['mp'] = round($at['p'][$a]['atack'][$j]['yron']['y']*0.25);
						$pvr['mpSee'] = '--';
						$pvr['mpNow'] = $btl->stats[$btl->uids[$u1]]['mpNow'];
						$pvr['mpAll'] = $btl->stats[$btl->uids[$u1]]['mpAll'];
						//
						if( $pvr['mp'] > 0 ) {
							$pvr['mpSee'] = '+'.$pvr['mp'];
						}else{
							$pvr['mp'] = 0;
						}
						$pvr['mpNow'] += $pvr['mp'];
						if( $pvr['mpNow'] < 0 ) {
							$pvr['mpNow'] = 0;
						}elseif( $pvr['mpNow'] > $pvr['mpAll'] ) {
							$pvr['mpNow'] = $pvr['mpAll'];
						}
						//
						$btl->stats[$btl->uids[$u1]]['mpNow'] = $pvr['mpNow'];					
						mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->stats[$btl->uids[$u1]]['mpNow'].'" WHERE `id` = "'.$u1.'" LIMIT 1');
						//
						$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
							"Духи Льда",
							"{tm1} {u1} восстановил ману заклятием &quot;{pr}&quot;. <font color=green><b>'.$pvr['mpSee'].'</b></font> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (Мана)",
						'.($btl->hodID + 1).' );';						
						//
						$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
						$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
						//
						$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
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