<?
if(!defined('GAME')) {
	die();
}
/*
	�����: �������� ������ [11]
*/
$pvr = array();
	$pvr['hp_0'] = 101;
	$pvr['hp_1'] = 34;
	//�������� ��� �����
	$pvr['hp'] = $pvr['hp_0'];
	$pvr['hp'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], '�����', 1 );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	$pvr['hpSee'] = '--';
	$pvr['hpNow'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']);
	$pvr['hpAll'] = $btl->stats[$btl->uids[$this->ue['id']]]['hpAll'];
		
	//���������� �������� �� ���� �������
	$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $this->ue['id'], 21, $pvr['hp'], 8, true );
		
	$pvr['hpSee'] = '-'.$pvr['hp'];
	$pvr['hpNow'] -= $pvr['hp'];
	$btl->priemYronSave($u->info['id'],$this->ue['id'],$pvr['hp'],0);
		
	if( $pvr['hpNow'] > $pvr['hpAll'] ) {
		$pvr['hpNow'] = $pvr['hpAll'];
	}elseif( $pvr['hpNow'] < 0 ) {
		$pvr['hpNow'] = 0;
	}
		
	$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] = $pvr['hpNow'];
		
	mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'].'" WHERE `id` = "'.$this->ue['id'].'" LIMIT 1');
		
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
	
	//���� ������
	if( $pvr['promah'] == false ) {
		if( $pvr['krit'] == false ) {
			$prv['color2'] = '006699';
			if(isset($btl->mcolor[$btl->mname['�����']])) {
				$prv['color2'] = $btl->mcolor[$btl->mname['�����']];
			}
			$prv['color'] = '000000';
			if(isset($btl->mncolor[$btl->mname['�����']])) {
				$prv['color'] = $btl->mncolor[$btl->mname['�����']];
			}
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
		$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
		$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
	}
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>�������� ������ [11]</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	$this->addPriem($this->ue['id'],275,'add_notactic=1|add_nousepriem=1',0,77,1,$u->info['id'],5,'���������');
	$this->addPriem($this->ue['id'],191,'add_antishock=1',0,77,5,$u->info['id'],5,'�������������������');	
	
	//$pvr['rx'] = rand(80,80);
	//$pvr['rx'] = floor($pvr['rx']/10);
	$pvr['uen'] = $this->ue['id'];
	$pvr['rx'] = 3;
	$pvr['xx'] = 0;
	$pvr['ix'] = 0;
	while( $pvr['ix'] < count($btl->users) ) {
		if( $btl->stats[$pvr['ix']]['hpNow'] > 0 && $btl->users[$pvr['ix']]['team'] != $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id'] ) {
			//
			$pvr['uid'] = $btl->users[$pvr['ix']]['id'];
			$pvr['hp'] = floor($pvr['hp_1']);
			$pvr['hp'] = $this->magatack( $u->info['id'], $pvr['uid'], $pvr['hp'], '�����', 0 );
			$pvr['promah_type'] = $pvr['hp'][3];
			$pvr['promah'] = $pvr['hp'][2];
			$pvr['krit'] = $pvr['hp'][1];
			$pvr['hp']   = $pvr['hp'][0];
			$pvr['hpSee'] = '--';
			$pvr['hpNow'] = floor($btl->stats[$btl->uids[$pvr['uid']]]['hpNow']);
			$pvr['hpAll'] = $btl->stats[$btl->uids[$pvr['uid']]]['hpAll'];
				
			//���������� �������� �� ���� �������
			$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $pvr['uid'], 21, $pvr['hp'], 8, true );
				
			$pvr['hpSee'] = '-'.$pvr['hp'];
			$pvr['hpNow'] -= $pvr['hp'];
			$btl->priemYronSave($u->info['id'],$pvr['uid'],$pvr['hp'],0);
				
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
					if(isset($btl->mcolor[$btl->mname['�����']])) {
						$prv['color2'] = $btl->mcolor[$btl->mname['�����']];
					}
					$prv['color'] = '000000';
					if(isset($btl->mncolor[$btl->mname['�����']])) {
						$prv['color'] = $btl->mncolor[$btl->mname['�����']];
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
			//$prv['color2'] = $btl->mcolor[$btl->mname['�����']];
			$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
			if( $pvr['promah_type'] == 2 ) {
				$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
			}else{
				$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
			}
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $pvr['uid'],
				'<font color^^^^#'.$prv['color2'].'>�������� ������ [11]</font>',
				$prv['text2'],
				($btl->hodID + 1)
			);
			
			//��������� �����
			//$this->addEffPr($pl,$id);
			//$this->addPriem($pvr['uid'],$pl['id'],'atgm='.floor($pvr['hp']/5).'',0,77,5,$u->info['id'],1,'��������������',0,0,1);
			
			//�������� �������
			//$this->mintr($pl);
			//
			$pvr['xx']++;
		}
		$pvr['ix']++;
	}
	
	//��������� �����
	//$this->addEffPr($pl,$id);
	//$this->addPriem($this->ue['id'],$pl['id'],'atgm='.($pvr['hp']/16).'',2,77,4,$u->info['id'],3,'����������',0,0,1);
	
	//�������� �������
	//$this->mintr($pl);

unset($pvr);
?>