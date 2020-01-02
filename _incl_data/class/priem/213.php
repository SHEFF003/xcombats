<?
if(!defined('GAME')) {
	die();
}
/*
				if( $this->stats[$this->uids[$vrm['uid1']]]['yhod'] > 0 ) {
					$this->atacks[$id]['uid1'] = $vrm['uid1'];
				}elseif( $this->stats[$this->uids[$vrm['uid2']]]['yhod'] > 0 ) {
					$this->atacks[$id]['uid2'] = $vrm['uid2'];
				}
*/
/*
	Прием: Коварный уход
	Следующий удар противника по вам нанесет на 3 ед. меньше урона
*/
$pvr = array();
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil ) {
		return round($yron);
	};
}elseif( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid2 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid1 ) {
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
		global $u, $btl;	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//	
		//echo '$user::['.$uid.']->(&quot;Прикрыться&quot;);';		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid2 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid1 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( $a > 0 ) {
			if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {
				//						
				$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->deleffm(213,'.(0+$uid).','.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].');
					$this->priemAddLog( '.$id.', '.$b.', '.$a.', '.$u2.', '.$u1.',
					"Коварный уход",
					"{tm1} '.$btl->addlt($b , 17 , $btl->users[$btl->uids[$u2]]['sex'] , NULL).'",
				'.($btl->hodID + 1).' );';
				//
				$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
				$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
				//
				$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
			}
		}
		//
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