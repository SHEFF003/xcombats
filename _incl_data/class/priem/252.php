<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ����������: �����
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
			$pvr['mp'] = 1;
			//
			$pvr['data'] = $priem->lookStatsArray($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data']);
			$pvr['di'] = 0;
			$pvr['dc'] = count($pvr['data']['atgm']);
			$pvr['rd'] = 0;
			$pvr['redata'] = '';
			while( $pvr['di'] < 4 ) {
				if( isset($pvr['data']['atgm'][($pvr['dc']-$pvr['di'])]) ) {
					if( $pvr['rd'] < 3 ) {
						$pvr['mp'] += $pvr['data']['atgm'][($pvr['dc']-$pvr['di'])];
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
			$pvr['mp'] = floor($pvr['mp']*$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['x']);
			/*$pvr['hp'] = $priem->magatack( $u2, $u1, $pvr['hp'], '����', 0 );
			$pvr['promah_type'] = $pvr['hp'][3];
			$pvr['promah'] = $pvr['hp'][2];
			$pvr['krit'] = $pvr['hp'][1];
			$pvr['hp']   = $pvr['hp'][0];*/
			$pvr['mpSee'] = '--';
			$pvr['mpNow'] = floor($btl->stats[$btl->uids[$u1]]['mpNow']);
			$pvr['mpAll'] = $btl->stats[$btl->uids[$u1]]['mpAll'];
			
			//���������� �������� �� ���� �������
			//$pvr['mp'] = $btl->testYronPriem( $u2, $u1, 12, $pvr['mp'], 7, true, false, 1 );
			$pvr['mpSee'] = '-'.$pvr['mp'];
			$pvr['mpNow'] -= $pvr['mp'];
			//$btl->priemYronSave($u2,$u1,$pvr['mp'],0);
				
			if( $pvr['mpNow'] > $pvr['mpAll'] ) {
				$pvr['mpNow'] = $pvr['mpAll'];
			}elseif( $pvr['mpNow'] < 0 ) {
				$pvr['mpNow'] = 0;
			}
			
			$btl->stats[$btl->uids[$u1]]['mpNow'] = $pvr['mpNow'];	
				
			mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->stats[$btl->uids[$u1]]['mpNow'].'" WHERE `id` = "'.$u1.'" LIMIT 1');
			//$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u2]]['sex'] , NULL);
			$prv['text'] = '{u2} ������� ���� �� &quot;{pr}&quot;';
			
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
			
			$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['mpSee'].'</b></font> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (����)';
															
			if( $pvr['promah_type'] == 2 ) {
				//$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u2]]['sex'] , NULL);
				$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (����)';
			}
			$prv['xx'] = '';
			if( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['x'] > 1 ) {
				//$prv['xx'] = ' x'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['x'].'';
			}
			$btl->priemAddLog( $id, 1, 2, $u2, $u1,
				'<font color^^^^#'.$prv['color2'].'>����������: �����'.$prv['xx'].'</font>',
				$prv['text2'],
				($btl->hodID)
			);
		}
		// -- ����� ������
		return $at;
	};
	unset( $pr_used_this );
}else{
	$pvr['hp'] = floor(95);
	$pvr['hp'] = $this->magatack( $u2, $u1, $pvr['hp'], '�����', 0 );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	//
	$prv['color2'] = '000000';
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
	$prv['text2'] = '{tm1} '.$prv['text'].'.';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>����������: �����</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	//��������� �����
	//$this->addEffPr($pl,$id);
	$this->addPriem($this->ue['id'],$pl['id'],'atgm='.floor($pvr['hp']/10).'',0,77,10,$u->info['id'],5,'����������',0,0,1);
	
	//�������� �������
	//$this->mintr($pl);
}

unset($pvr);
?>