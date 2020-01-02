<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Иней 8
	Следующий удар противника по вам нанесет на 25% меньше урона
*/
$pvr = array();
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil ) {
		if(!isset($btl->stats[$btl->uids[$uid]]['um_priem'][$j_id])) {
			global $u, $btl;
			$prv['color2'] = $btl->mcolor[$btl->mname['вода']];
			$yron = $yron*0.75;
			$btl->priemAddLogFast( $uid, 0, "<font color^^^^#".$prv['color2'].">Иней [8]</font>",
				'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$uid]]['sex'] , NULL).'',
			0, time() );
			if( $yron < 0 ) {
				$yron = 1;
			}
			$btl->stats[$btl->uids[$uid]]['um_priem'][$j_id] = true;
		}
		return round($yron);
	};
}elseif( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;
		$prv['color2'] = $btl->mcolor[$btl->mname['вода']];	
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
				//mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
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
		$prv['color2'] = $btl->mcolor[$btl->mname['вода']];	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//$prv['j_priem'] = $btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][0];
		//$prv['priem_th'] = $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'];
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
		if( $a > 0 ) {
			$j = 0; $k = 0; $wp = 3;
			while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 )) {
					//if( !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {
						//
						$at['p'][$a]['atack'][$j]['yron']['y'] = round($at['p'][$a]['atack'][$j]['yron']['y']*0.75);
						$at['p'][$a]['atack'][$j]['yron']['r'] = round($at['p'][$a]['atack'][$j]['yron']['r']*0.75);
						$at['p'][$a]['atack'][$j]['yron']['k'] = round($at['p'][$a]['atack'][$j]['yron']['k']*0.75);
						$at['p'][$a]['atack'][$j]['yron']['m_k'] = round($at['p'][$a]['atack'][$j]['yron']['m_k']*0.75);
						$at['p'][$a]['atack'][$j]['yron']['m_y'] = round($at['p'][$a]['atack'][$j]['yron']['m_y']*0.75);
						//
						if( $at['p'][$a]['atack'][$j]['yron']['y'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['y'] = 1; }
						if( $at['p'][$a]['atack'][$j]['yron']['r'] >= 0 ) { $at['p'][$a]['atack'][$j]['yron']['r'] = -1; }
						if( $at['p'][$a]['atack'][$j]['yron']['k'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['k'] = 1; }
						if( $at['p'][$a]['atack'][$j]['yron']['m_k'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['m_k'] = 1; }
						if( $at['p'][$a]['atack'][$j]['yron']['m_y'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['m_y'] = 1; }
						//						
						if( $pvr['used'] == 0 ) {
							$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->priemAddLog( '.$id.', '.$b.', '.$a.', '.$u2.', '.$u1.',
								"<font color^^^^#'.$prv['color2'].'>Иней [8]</font>",
								"{tm1} '.$btl->addlt($b , 19 , $btl->users[$btl->uids[$u2]]['sex'] , NULL).'.",
							'.($btl->hodID + 1).' );';
						}
						//
						$priem->addPriem($u1,278,'|add_yzm3=-'.($btl->users[$btl->uids[$u2]]['level']).'',2,77,2,$u2,100,'инейкасание',0,0,1);
						//
						$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
						$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
						//
						$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
					//}
				}
				$j++;
			}	
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	$prv['color2'] = $btl->mcolor[$btl->mname['вода']];
	//Действие при клике
	$this->addEffPr($pl,$id);
	$prv['text'] = '{tm1} ' . $btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'.';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['id'],
		'<font color^^^^#'.$prv['color2'].'>Иней [8]</font>',
		$prv['text'],
		($btl->hodID + 1)
	);
}
unset($pvr);
?>