<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Лечение [8]
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
	
				$pvr['hp_0'] = 219;
				$pvr['hp_1'] = 219+$this->ue['level'];
									
				//
				$pvr['hp'] = floor(rand($pvr['hp_0'],$pvr['hp_1']));
				
				if( $u->info['id'] == $this->ue['id'] ) {
					$pvr['hp'] = $pvr['hp']*1.25;
				}
				
				$pvr['hp'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], 'свет', 1 );
				$pvr['promah_type'] = $pvr['hp'][3];
				$pvr['promah'] = $pvr['hp'][2];
				$pvr['krit'] = $pvr['hp'][1];
				$pvr['hp']   = $pvr['hp'][0];
				//
				$pvr['hpSee'] = 0;
				$pvr['hpNow'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']);
				$pvr['hpAll'] = $btl->stats[$btl->uids[$this->ue['id']]]['hpAll'];
				$pvr['hpTr'] = $pvr['hpAll'] - $pvr['hpNow'];
				
				
				$pvr['hp'] = $btl->hphe( $this->ue['id'] , $pvr['hp'] );
				
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
				//$btl->users[$btl->uids[$u->info['id']]]['last_hp'] = $pvr['hp'];
				//
				$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] = $pvr['hpNow'];
				$btl->users[$btl->uids[$this->ue['id']]]['hpNow'] = $pvr['hpNow'];				
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'].'" WHERE `id` = "'.$this->ue['id'].'" LIMIT 1');
				//
				//Цвет приема
				if( $pvr['promah'] == false ) {
					if( $pvr['krit'] == false ) {
						$pvr['color2'] = '006699';
						if(isset($btl->mcolor[$btl->mname['свет']])) {
							$pvr['color2'] = $btl->mcolor[$btl->mname['свет']];
						}
						$pvr['color'] = '006699';
						if(isset($btl->mncolor[$btl->mname['свет']])) {
							$pvr['color'] = $btl->mncolor[$btl->mname['свет']];
						}
					}else{
						$pvr['color2'] = 'FF0000';
						$pvr['color'] = 'FF0000';
					}
				}else{
					$pvr['color2'] = '909090';
					$pvr['color'] = '909090';
				}
				//
				$btl->priemAddLogFast( $u->info['id'], $this->ue['id'], "<font color^^^^#".$pvr['color2'].">Лечение [8]</font>",
					'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' на {u2}. <font Color=#'.$pvr['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
				1, time() );					
				//
	//
	echo '<font color=red><b>Вы успешно использовали прием &quot;Лечение [8]&quot;</b></font>';
	//$this->addEffPr($pl,$id);
	$this->mintr($pl);
}

unset($pvr);
?>