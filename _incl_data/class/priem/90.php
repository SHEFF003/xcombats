<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Регенерация [11]
*/
$pvr = array();
//
if( isset($pr_used_this) && isset($pr_moment) ) {
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl, $priem;	
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
			if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) &&  floor($btl->stats[$btl->uids[$uid]]['hpNow']) >= 1 ) {
				//
				//Проверяем эффект
				$prv['j_priem'] = $btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][0];
				$prv['priem_th'] = $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'];
				//
				$pvr['hp'] = 1;
				//
				$pvr['data'] = $priem->lookStatsArray($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data']);
				$pvr['hp'] = floor($pvr['data']['add_atgm'][0]);
				$pvr['hp'] = $btl->hphe( $u1 , $pvr['hp'] );
				//				
				$pvr['hpSee'] = 0;
				$pvr['hpNow'] = floor($btl->stats[$btl->uids[$uid]]['hpNow']);
				$pvr['hpAll'] = $btl->stats[$btl->uids[$uid]]['hpAll'];
				$pvr['hpTr'] = $pvr['hpAll'] - $pvr['hpNow'];
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
				$btl->priemAddLogFast( $uid, 0, "Регенерация [11]",
					'{tm1} {u1} восстановил здоровье от &quot;{pr}&quot;. <font Color=green><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
				0, time() );					
				//
			}	
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	$pvr['hp'] = floor(181);
	$pvr['hp'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], 'вода', 1 );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	//
	$prv['color2'] = '000000';
	if( $pvr['krit'] == true ) {
		$prv['color2'] = 'FF0000';
	}
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
	$prv['text2'] = '{tm1} '.$prv['text'].'.';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>Регенерация [11]</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	//Добавляем прием
	//$this->addEffPr($pl,$id);
	$this->addPriem($this->ue['id'],$pl['id'],'add_atgm='.floor($pvr['hp']/8).'',0,77,8,$u->info['id'],1,'регенерация',0,0,1);
	
	//Отнимаем тактики
	$this->mintr($pl);
}
//
unset($pvr);
?>