<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Собраться
*/
$pvr = array();

//Действие при клике
if( isset($pr_used_this) && isset($pr_moment) ) { 
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
			if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {
				//
				$pvr['hp'] = 50;
				$pvr['hpSee'] = 0;
				$pvr['hpNow'] = floor($btl->stats[$btl->uids[$uid]]['hpNow']);
				$pvr['hpAll'] = $btl->stats[$btl->uids[$uid]]['hpAll'];
				$pvr['hpTr'] = $pvr['hpAll'] - $pvr['hpNow'];
				
				$pvr['hp'] = $btl->hphe( $uid , $pvr['hp'] , true );
				
				if( $pvr['hpTr'] > 0 ) {
					//Требуется хилл
					if( $pvr['hpTr'] < $pvr['hp'] ) {
						$pvr['hp'] = $pvr['hpTr'];
					}
					$pvr['hpSee'] = '+'.$pvr['hp'];
					$pvr['hpNow'] += $pvr['hp'];
				}					
				if( $pvr['hpNow'] > $pvr['hpAll'] ) {
					$pvr['hpNow'] = $pvr['hpAll'];
				}elseif( $pvr['hpNow'] < 0 ) {
					$pvr['hpNow'] = 0;
				}
				//
				$btl->users[$btl->uids[$uid]]['last_hp'] = $pvr['hp'];
				//
				$btl->stats[$btl->uids[$uid]]['hpNow'] = $pvr['hpNow'];					
				mysql_query('UPDATE `stats` SET `last_hp` = "'.$btl->users[$btl->uids[$uid]]['last_hp'].'",`hpNow` = "'.$btl->stats[$btl->uids[$uid]]['hpNow'].'" WHERE `id` = "'.$uid.'" LIMIT 1');
				//
				
				$btl->priemAddLogFast( $uid, 0, "Собраться",
					'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$uid]]['sex'] , NULL).' на <font Color=green><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
				1, time() );					
				//
			}	
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	if( $u->stats['items'][$u->stats['wp14id']]['type'] == 13 ) {
		//Действие при клике
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
			'Собраться',
			'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'',
			($btl->hodID)
		);
		echo '<font color=red><b>Вы успешно использовали прием &quot;Собраться&quot;</b></font>';
		$this->addEffPr($pl,$id);
	}else{
		echo '<font color=red><b>Для использования &quot;Собраться&quot; требуется наличие щита</b></font>';	
	}
}

unset($pvr);
?>