<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Цепь Исцеления [8]
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
	
				$pvr['hp_0'] = 1;
				$pvr['hp_1'] = 165;
	
				//
				$pvr['hp'] = floor(rand($pvr['hp_0'],$pvr['hp_1']));
				$pvr['hp'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], 'воздух', 0 );
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
						if(isset($btl->mcolor[$btl->mname['воздух']])) {
							$pvr['color2'] = $btl->mcolor[$btl->mname['воздух']];
						}
						$pvr['color'] = '000000';
						if(isset($btl->mncolor[$btl->mname['воздух']])) {
							$pvr['color'] = $btl->mncolor[$btl->mname['воздух']];
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
				$btl->priemAddLogFast( $u->info['id'], $this->ue['id'], "<font color^^^^#".$pvr['color2'].">Цепь Исцеления [8]</font>",
					'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' на {u2}. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
				1, time() );					
				//
	$pvr['uen'] = $this->ue['id'];
	$pvr['rx'] = rand(0,2);
	$pvr['xx'] = 0;
	$pvr['ix'] = 0;
	while( $pvr['ix'] < count($btl->users) ) {
		if( $btl->stats[$pvr['ix']]['hpNow'] >= 1 && $btl->users[$pvr['ix']]['team'] == $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id'] ) {
			//
			$pvr['uid'] = $btl->users[$pvr['ix']]['id'];
			$pvr['hp'] = floor(rand($pvr['hp_0'],$pvr['hp_1']));
			$pvr['hp'] = $this->magatack( $u->info['id'], $pvr['uid'], $pvr['hp'], 'воздух', 0 );
			$pvr['promah_type'] = $pvr['hp'][3];
			$pvr['promah'] = $pvr['hp'][2];
			$pvr['krit'] = $pvr['hp'][1];
			$pvr['hp']   = $pvr['hp'][0];
			$pvr['hpSee'] = '--';
			$pvr['hpNow'] = floor($btl->stats[$btl->uids[$pvr['uid']]]['hpNow']);
			$pvr['hpAll'] = $btl->stats[$btl->uids[$pvr['uid']]]['hpAll'];
			//
			//
			$pvr['hpTr'] = $pvr['hpAll'] - $pvr['hpNow'];				
				
			$pvr['hp'] = $btl->hphe( $pvr['uid'] , $pvr['hp'] );
				
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
			$btl->stats[$btl->uids[$pvr['uid']]]['hpNow'] = $pvr['hpNow'];
			$btl->users[$btl->uids[$pvr['uid']]]['hpNow'] = $pvr['hpNow'];				
			mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->users[$btl->uids[$pvr['uid']]]['hpNow'].'" WHERE `id` = "'.$pvr['uid'].'" LIMIT 1');
			//
			//Цвет приема
			if( $pvr['promah'] == false ) {
				if( $pvr['krit'] == false ) {
					$pvr['color2'] = '006699';
					if(isset($btl->mcolor[$btl->mname['воздух']])) {
						$pvr['color2'] = $btl->mcolor[$btl->mname['воздух']];
					}
					$pvr['color'] = '000000';
					if(isset($btl->mncolor[$btl->mname['воздух']])) {
						$pvr['color'] = $btl->mncolor[$btl->mname['воздух']];
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
			$btl->priemAddLogFast( $u->info['id'], $pvr['uid'], "<font color^^^^#".$pvr['color2'].">Цепь Исцеления [8]</font>",
				'{tm1} '.$btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
			1, time() );
			//
			$pvr['xx']++;
		}
		$pvr['ix']++;
	}
	//
	echo '<font color=red><b>Вы успешно использовали прием &quot;Цепь Исцеления [8]&quot;</b></font>';
	//$this->addEffPr($pl,$id);
	$this->mintr($pl);
}

unset($pvr);
?>