<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Искра
*/
$pvr = array();
	$pvr['hp11'] = 1;
	$pvr['hp22'] = 60;
	if( rand(0,100) < 50 ) {
		$pvr['rx'] = 1;
		$pvr['xx'] = 0;
		$pvr['ix'] = 0;
		while( $pvr['ix'] < count($btl->users) ) {
			if( $btl->stats[$pvr['ix']]['hpNow'] >= 1 && $btl->users[$pvr['ix']]['team'] == $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id'] ) {
				//
				$pvr['uid'] = $btl->users[$pvr['ix']]['id'];
				$pvr['hp'] = floor(rand($pvr['hp11'],$pvr['hp22']));
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
				$btl->priemAddLogFast( $u->info['id'], $pvr['uid'], "<font color^^^^#".$pvr['color2'].">Искра</font>",
					'{tm1} '.$btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
				1, time() );
				//
				$pvr['xx']++;
			}
			$pvr['ix']++;
		}
	}else{
		//
		$pvr['hp_0'] = rand($pvr['hp11'],$pvr['hp22']);
		//Действие при клике	
		//$pvr['rx'] = rand(80,80);
		//$pvr['rx'] = floor($pvr['rx']/10);
		$pvr['uen'] = $this->ue['id'];
		$pvr['rx'] = 1;
		$pvr['xx'] = 0;
		$pvr['ix'] = 0;
		while( $pvr['ix'] < count($btl->users) ) {
			if( $btl->stats[$pvr['ix']]['hpNow'] > 0 && $btl->users[$pvr['ix']]['team'] != $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id'] ) {
				//
				$pvr['uid'] = $btl->users[$pvr['ix']]['id'];
				$pvr['hp'] = floor(rand($pvr['hp11'],$pvr['hp22']));
				$pvr['hp'] = $this->magatack( $u->info['id'], $pvr['uid'], $pvr['hp'], 'воздух', 0 );
				$pvr['promah_type'] = $pvr['hp'][3];
				$pvr['promah'] = $pvr['hp'][2];
				$pvr['krit'] = $pvr['hp'][1];
				$pvr['hp']   = $pvr['hp'][0];
				$pvr['hpSee'] = '--';
				$pvr['hpNow'] = floor($btl->stats[$btl->uids[$pvr['uid']]]['hpNow']);
				$pvr['hpAll'] = $btl->stats[$btl->uids[$pvr['uid']]]['hpAll'];
					
				//Используем проверку на урон приемов
				$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $pvr['uid'], 21, $pvr['hp'], 6, true );
					
				$pvr['hpSee'] = '-'.$pvr['hp'];
				$pvr['hpNow'] -= $pvr['hp'];
				$btl->priemYronSave($u->info['id'],$pvr['uid'],$pvr['hp'],0);
				
				$this->mg2static_points( $pvr['uid'] , $btl->stats[$btl->uids[$pvr['uid']]] );
					
				if( $pvr['hpNow'] > $pvr['hpAll'] ) {
					$pvr['hpNow'] = $pvr['hpAll'];
				}elseif( $pvr['hpNow'] < 0 ) {
					$pvr['hpNow'] = 0;
				}
					
				$btl->stats[$btl->uids[$pvr['uid']]]['hpNow'] = $pvr['hpNow'];
					
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$pvr['uid']]]['hpNow'].'" WHERE `id` = "'.$pvr['uid'].'" LIMIT 1');
				
				//
				if( $pvr['promah'] == false ) {
					if( $pvr['krit'] == false ) {
						$prv['color2'] = '006699';
						if(isset($btl->mcolor[$btl->mname['воздух']])) {
							$prv['color2'] = $btl->mcolor[$btl->mname['воздух']];
						}
						$prv['color'] = '000000';
						if(isset($btl->mncolor[$btl->mname['воздух']])) {
							$prv['color'] = $btl->mncolor[$btl->mname['воздух']];
						}
					}else{
						$prv['color2'] = 'FF0000';
						$prv['color'] = 'FF0000';
					}
				}else{
					$prv['color2'] = '909090';
					$prv['color'] = '909090';
				}
				//
				
				//
				//$prv['color2'] = $btl->mcolor[$btl->mname['земля']];
				$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
				if( $pvr['promah_type'] == 2 ) {
					$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
				}else{
					$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
				}
				$btl->priemAddLog( $id, 1, 2, $u->info['id'], $pvr['uid'],
					'<font color^^^^#'.$prv['color2'].'>Искра</font>',
					$prv['text2'],
					($btl->hodID + 1)
				);
				
				//Добавляем прием
				//$this->addEffPr($pl,$id);
				//$this->addPriem($pvr['uid'],$pl['id'],'atgm='.floor($pvr['hp']/5).'',0,77,5,$u->info['id'],1,'ядовитоеоблако',0,0,1);
				
				//Отнимаем тактики
				//$this->mintr($pl);
				//
				$pvr['xx']++;
			}
			$pvr['ix']++;
		}
	}
	
	//Добавляем прием
	//$this->addEffPr($pl,$id);
	//$this->addPriem($this->ue['id'],$pl['id'],'atgm='.($pvr['hp']/16).'',2,77,4,$u->info['id'],3,'оледенение',0,0,1);
	
	//Отнимаем тактики
	//$this->mintr($pl);

unset($pvr);
?>