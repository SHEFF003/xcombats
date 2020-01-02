<?
if(!defined('GAME')) { die(); }
/*
	Прием: Жертва Воде
	Маг теряет 10% НР на протяжении 5 ходов, но заклятия стоят на 50% дешевле
*/
$pvr = array();
if(isset($pr_momental_this)) {
	$fx_moment = function($uid, $enemy, $j_id, $yron, $profil, $inlog) {
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
}elseif( isset($pr_used_this) && isset($pr_moment) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) && !isset($btl->info['id']) ) { 

}elseif( isset($pr_used_this) ) { 
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
		if( $a > 0 ) {
			$pvr['hp'] = floor($btl->stats[$btl->uids[$u2]]['hpAll']/50);
			if( $pvr['hp'] < 0 ) {
				$pvr['hp'] = 0;
			}
			$pvr['hpSee'] = '--';
			$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u2]]['hpNow']);
			$pvr['hpAll'] = floor($btl->stats[$btl->uids[$u2]]['hpAll']);
			$pvr['hpNow'] -= $pvr['hp'];
			//
			//Используем проверку на урон приемов
			$pvr['hp'] = $btl->testYronPriem( $u1, $u2, 12, $pvr['hp'], 7, true );
			//			
			if( $pvr['hpNow'] < 0 ) {
				$pvr['hpNow'] = 0;
			}elseif( $pvr['hpNow'] > $pvr['hpAll'] ) {
				$pvr['hpNow'] = $pvr['hpAll'];
			}
			if( $pvr['hp'] > 0 ) {
				$pvr['hpSee'] = '-'.$pvr['hp'];
			}
			
			$btl->stats[$btl->uids[$u2]]['hpNow'] = $pvr['hpNow'];
	
			mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u2]]['hpNow'].'" WHERE `id` = "'.$u2.'" LIMIT 1');

			$btl->priemAddLog( $id, $a, $b, $u2, $u1,
				"Жертва Воде",
				"{tm1} {u1} утратил здоровье от эффекта &quot;{pr}&quot;. <font color^^^^#006699><b>".$pvr['hpSee']."</b></font> [".$pvr['hpNow']."/".$pvr['hpAll']."] ",
			($btl->hodID + 1) );
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	$prv['color2'] = $btl->mcolor[$btl->mname['вода']];
	//Действие при клике
	$this->addEffPr($pl,$id);
	$prv['text'] = '{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'.';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['id'],
		'<font color^^^^#'.$prv['color2'].'>Жертва Воде</font>',
		$prv['text'],
		($btl->hodID + 1)
	);
}
unset($pvr);
?>