<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ���������� ����� [6]
*/
$pvr = array();
if( isset($pr_used_this) && isset($pr_moment) ) {
	//������ ���
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- ������ ������
		global $u, $btl, $priem;	
		//
		//��������� ������
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
			
			//��������� ������
			$prv['j_priem'] = $btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][0];
			$prv['priem_th'] = $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'];
			
			//��������
			$pvr['hp'] = 1;
			//
			$pvr['data'] = $priem->lookStatsArray($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data']);
			$pvr['di'] = 0;
			$pvr['dc'] = count($pvr['data']['atgm']);
			$pvr['rd'] = 0;
			$pvr['redata'] = '';
			while( $pvr['di'] < 4 ) {
				if( isset($pvr['data']['atgm'][($pvr['dc']-$pvr['di'])]) ) {
					if( $pvr['rd'] < 3 ) {
						$pvr['hp'] += $pvr['data']['atgm'][($pvr['dc']-$pvr['di'])];
						$pvr['redata'] = 'atgm='.$pvr['data']['atgm'][($pvr['dc']-$pvr['di'])].'|'.$pvr['redata'];
						$pvr['rd']++;
					}
				}
				$pvr['di']++;
			}
			//
			$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data'] = $pvr['redata'];
			mysql_query('UPDATE `eff_users` SET `data` = "'.$pvr['redata'].'" WHERE `id` = "'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'].'" LIMIT 1');
			//
			$pvr['hp'] = floor($pvr['hp']*5);
			$pvr['proc-hp'] = array( 5 , 10 , 15 , 20 , 50 );
			$pvr['proc-hp'] = $pvr['proc-hp'][5-$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['hod']];
			$pvr['hp'] = round($pvr['hp']/100*$pvr['proc-hp']);
			//
			/*$pvr['hp'] = $priem->magatack( $u2, $u1, $pvr['hp'], '�����', 0 );
			$pvr['promah_type'] = $pvr['hp'][3];
			$pvr['promah'] = $pvr['hp'][2];
			$pvr['krit'] = $pvr['hp'][1];
			$pvr['hp']   = $pvr['hp'][0];*/
			//
			$pvr['hpSee'] = '--';
			$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u1]]['hpNow']);
			$pvr['hpAll'] = $btl->stats[$btl->uids[$u1]]['hpAll'];
			
			//���������� �������� �� ���� �������
			$pvr['hp'] = $btl->testYronPriem( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['user_use'], $u1, 12, $pvr['hp'], 7, true, false, 1 );
			
			$pvr['hpSee'] = '-'.$pvr['hp'];
			$pvr['hpNow'] -= $pvr['hp'];
			$btl->priemYronSave($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['user_use'],$u1,$pvr['hp'],0);
				
			if( $pvr['hpNow'] > $pvr['hpAll'] ) {
				$pvr['hpNow'] = $pvr['hpAll'];
			}elseif( $pvr['hpNow'] < 0 ) {
				$pvr['hpNow'] = 0;
			}
			
			$btl->stats[$btl->uids[$u1]]['hpNow'] = $pvr['hpNow'];	
				
			mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u1]]['hpNow'].'" WHERE `id` = "'.$u1.'" LIMIT 1');
			//$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u2]]['sex'] , NULL);
			$prv['text'] = '{u2} ������� �������� �� &quot;{pr}&quot;';
			
			//���� ������
			if( $pvr['promah'] == false ) {
				if( $pvr['krit'] == false ) {
					$prv['color2'] = '000000';
					$prv['color'] = '008000';
				}else{
					$prv['color2'] = 'FF0000';
					$prv['color'] = 'FF0000';
				}
			}else{
				$prv['color2'] = '909090';
				$prv['color'] = '909090';
			}
			
			$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
			if( $pvr['promah_type'] == 2 ) {
				//$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u2]]['sex'] , NULL);
				$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
			}
			$prv['xx'] = '';
			if( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['x'] > 1 ) {
				//$prv['xx'] = ' x'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['x'].'';
			}
			$btl->priemAddLog( $id, 1, 2, $u2, $u1,
				'<font color^^^^#'.$prv['color2'].'>���������� ����� [6]'.$prv['xx'].'</font>',
				$prv['text2'],
				($btl->hodID)
			);
		}
		// -- ����� ������
		return $at;
	};
	unset( $pr_used_this );
}else{
	$pvr['hp'] = floor(53);
	$pvr['hp'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], '�����', 1 );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	//
	$prv['color2'] = '000000';
	if( $pvr['promah'] == false ) {
		if( $pvr['krit'] == false ) {
			$prv['color2'] = '000000';
		}else{
			$prv['color2'] = 'FF0000';
		}
	}else{
		$prv['color2'] = '909090';
	}
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
	$prv['text2'] = '{tm1} '.$prv['text'].'.';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>���������� ����� [6]</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	//��������� �����
	//$this->addEffPr($pl,$id);
	$this->addPriem($this->ue['id'],$pl['id'],'atgm='.floor($pvr['hp']/5).'',0,77,5,$u->info['id'],1,'���������������',0,0,1);
	
	//�������� �������
	//$this->mintr($pl);
}

unset($pvr);
?>