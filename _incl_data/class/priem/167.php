<?
if(!defined('GAME')) {
	die();
}
/*
	�����: �������� ����
*/
$pvr = array();
	//�������� ��� �����
	$pvr['hp'] = 77;
	//$pvr['hp'] = floor($pvr['hp']*0.95);
	$pvr['hp'] = $this->magatackfiz( $u->info['id'], $this->ue['id'], $pvr['hp'], '����' /* �������� ���� - 3 */, 1 , '�����' );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	$pvr['hpSee'] = '--';
	$pvr['hpNow'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']);
	$pvr['hpAll'] = $btl->stats[$btl->uids[$this->ue['id']]]['hpAll'];
		
	//���������� �������� �� ���� �������
	$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $this->ue['id'], 21, $pvr['hp'], 3 /* �������� ���� - 3 */, true );
		
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
		'<font color^^^^#'.$prv['color2'].'>�������� ���� [5]</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	$this->mintr($pl);

unset($pvr);
?>