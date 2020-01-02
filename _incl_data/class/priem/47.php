<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Слепая удача
	Следующий удар по противнику будет критическим
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
				if( (isset($at['p'][$a]['atack'][$j]['yron']) || isset($at['p'][$a]['atack'][$j]['block'])) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 3 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 )) {
					if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) && $btl->stats[$btl->uids[$u2]]['enemy_am1'] < 100 ) {
						//
						if( $at['p'][$a]['atack'][$j][1] == 3 ) {
							$at['p'][$a]['atack'][$j][1] = 4;
							//Пробив блок
							$at['p'][$a]['atack'][$j]['yron'] = $at['p'][$a]['atack'][$j]['block'];
							$at['p'][$a]['atack'][$j]['yron']['y'] = round(1+$at['p'][$a]['atack'][$j]['yron']['k']/2);
							$at['p'][$a]['atack'][$j]['yron']['r'] = -round(1+$at['p'][$a]['atack'][$j]['yron']['k']/2);
						}else{
							$at['p'][$a]['atack'][$j][1] = 5;
							//Просто крит
							$at['p'][$a]['atack'][$j]['yron']['y'] = $at['p'][$a]['atack'][$j]['yron']['k'];
							$at['p'][$a]['atack'][$j]['yron']['r'] = -($at['p'][$a]['atack'][$j]['yron']['k']);
						}
						$at['p'][$a]['atack'][$j]['notactic2'] = true;
						//
						$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->deleffm(47,'.(0+$uid).','.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].');
							$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
							"Слепая удача",
							"{tm1} '.$btl->addlt($a , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).'",
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
		return $at;
	};
	unset( $pr_used_this );
}else{
	//Действие при клике
	$this->addEffPr($pl,$id);
}
unset($pvr);
?>