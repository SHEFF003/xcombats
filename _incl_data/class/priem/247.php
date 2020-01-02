<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Коснуться Земли
*/
$pvr = array();

//Действие при клике
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil, $inlog ) {
		return round($yron);
	};
}elseif( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) && isset($pr_moment) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		return $at;
	};
	unset( $pr_used_this );
}else{
	//Действие при клике
	/*
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
		'Медитация',
		'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'',
		($btl->hodID+1)
	);
	*/
				//
				$pvr['mp'] = round($u->stats['mpAll']*0.03);
				$pvr['mpSee'] = 0;
				$pvr['mpNow'] = floor($u->stats['mpNow']);
				$pvr['mpAll'] = $u->stats['mpAll'];
				$pvr['mpTr'] = $pvr['mpAll'] - $pvr['mpNow'];
				
				//$pvr['mp'] = $btl->hphe( $u->info['id'] , $pvr['hp'] );
				
				if( $pvr['mpTr'] > 0 ) {
					//Требуется хилл
					if( $pvr['mpTr'] < $pvr['mp'] ) {
						$pvr['mp'] = $pvr['mpTr'];
					}
					$pvr['mpSee'] = '+'.$pvr['mp'];
					$pvr['mpNow'] += $pvr['mp'];
				}					
				if( $pvr['mpNow'] > $pvr['mpAll'] ) {
					$pvr['mpNow'] = $pvr['mpAll'];
				}elseif( $pvr['mpNow'] < 0 ) {
					$pvr['mpNow'] = 0;
				}
				if( $pvr['mpSee'] == 0 ) {
					$pvr['mpSee'] = '--';
				}
				//
				//$btl->users[$btl->uids[$u->info['id']]]['last_hp'] = $pvr['hp'];
				//
				$pvr['color2'] = $btl->mcolor[$btl->mname['земля']];
				//
				$u->stats['mpNow'] = $pvr['mpNow'];	
				$u->info['mpNow'] = $pvr['mpNow'];	
				$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] = $pvr['mpNow'];
				$btl->users[$btl->uids[$u->info['id']]]['mpNow'] = $pvr['mpNow'];
				//
				//$btl->users[$btl->uids[$u->info['id']]]['last_hp'] = $pvr['hp'];
				//
				$pvr['color2'] = $btl->mcolor[$btl->mname['земля']];
				//								
				mysql_query('UPDATE `stats` SET `mpNow` = "'.$u->stats['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//
				$btl->priemAddLogFast( $u->info['id'], 0, "<font color^^^^#".$pvr['color2'].">Коснуться Земли</font>",
					'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' на <font Color=#006699><b>'.$pvr['mpSee'].'</b></font> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (Мана)',
				1, time() );					
				//
				$pvr['sp'] = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `v1` = "priem" AND `v2` != 201 AND `v2` != 211 LIMIT 1');
				while($pvr['pl'] = mysql_fetch_array($pvr['sp'])) {
					$pvr['pl']['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pvr['pl']['v2'].'" AND `neg` > 0 LIMIT 1'));
					if( isset($pvr['pl']['priem']) ) {
						$btl->delPriem($pvr['pl'],$btl->users[$btl->uids[$u->info['id']]],100);
					}
				}
				//
	echo '<font color=red><b>Вы успешно использовали прием &quot;Жертва Земле&quot;</b></font>';
	//$this->addEffPr($pl,$id);
}

unset($pvr);
?>