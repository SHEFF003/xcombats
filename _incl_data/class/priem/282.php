<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Ледяное спасение
	Маг восстанавливает здоровье, но за 5 ходов теряет 50% восстановленного НР
*/
$pvr = array();
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil, $inlog ) {
		return round($yron);
	};
}elseif( isset($pr_used_this) && isset($pr_moment) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		return $at;
	};
	unset( $pr_used_this );
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
			$prv['j_priem'] = $btl->stats[$btl->uids[$u2]]['u_priem'][$j_id][0];
			$prv['priem_th'] = $btl->stats[$btl->uids[$u2]]['effects'][$prv['j_priem']]['id'];
			//
			$pvr['hp'] = 1;
			$pvr['data'] = $priem->lookStatsArray($btl->stats[$btl->uids[$u2]]['effects'][$prv['j_priem']]['data']);
			$pvr['hp'] = floor($pvr['data']['add_atgm'][0]);
			//
			//$pvr['hp'] = floor($btl->stats[$btl->uids[$u2]]['hpAll']/50);
			if( $pvr['hp'] < 0 ) {
				$pvr['hp'] = 0;
			}
			$pvr['hpSee'] = '--';
			$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u2]]['hpNow']);
			$pvr['hpAll'] = floor($btl->stats[$btl->uids[$u2]]['hpAll']);
			$pvr['hpNow'] -= $pvr['hp'];
			//
			//Используем проверку на урон приемов
			//$pvr['hp'] = $btl->testYronPriem( $u1, $u2, 12, $pvr['hp'], 7, true );
			$pvr['hp'] = -($btl->hphe($u2,-($pvr['hp'])));
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

			$btl->priemAddLog( $id, $a, $b, $u1, $u2,
				"Ледяное Спасение",
				"{tm1} {u2} утратил здоровье от эффекта &quot;{pr}&quot;. <font color^^^^#006699><b>".$pvr['hpSee']."</b></font> [".$pvr['hpNow']."/".$pvr['hpAll']."] ",
			($btl->hodID + 1) );
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	$pvr['color2'] = $btl->mcolor[$btl->mname['вода']];
	//Действие при клике
	//$this->addEffPr($pl,$id);
	//
	$pvr['color'] = '006699';
	//
	$pvr['hp'] = 245;
	$pvr['hp'] = $this->magatack( $u->info['id'], $u->info['id'], $pvr['hp'], 'вода', 0 );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	if( $pvr['hp'] < 0 ) {
		$pvr['hp'] = 0;
	}
	$pvr['hp'] = $btl->hphe($u->info['id'],$pvr['hp']);
	$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']);
	$pvr['hpAll'] = floor($btl->stats[$btl->uids[$u->info['id']]]['hpAll']);
	$pvr['hpNow'] += $pvr['hp'];
	if( $pvr['hpNow'] < 0 ) {
		$pvr['hpNow'] = 0;
	}elseif( $pvr['hpNow'] > $pvr['hpAll'] ) {
		$pvr['hpNow'] = $pvr['hpAll'];
	}
	//
	$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = $pvr['hpNow'];	
	mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['id']]]['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	//
	$pvr['hpSee'] = '--';
	if($pvr['hp'] > 0) {
		$pvr['hpSee'] = '+'.$pvr['hp'];
	}
	//
	$this->addPriem($u->info['id'],$pl['id'],'add_atgm='.floor($pvr['hp']/10).'',0,77,5,$u->info['id'],1,'ледяноеспасение',0,0,1);
	//
	$prv['text'] = '{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'. <font color='.$pvr['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['id'],
		'<font color^^^^#'.$pvr['color2'].'>Ледяное Спасение</font>',
		$prv['text'],
		($btl->hodID + 1)
	);
	$this->mintr($pl);
}
unset($pvr);
?>