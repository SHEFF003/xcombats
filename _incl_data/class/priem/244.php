<?
if(!defined('GAME')) {
	die();
}
/*
	�����: �������� ������
*/
$pvr = array();
$pvr['mg'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$this->ue['id']]]['id'].'" AND `bj` = "���������������" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
if( $btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] > round($btl->stats[$btl->uids[$this->ue['id']]]['hpAll']/100*33) ) {
	echo '<font color=red><b>������� �������� ���� ������ ���� ���� 33%</b></font>';
	$cup = true;
}elseif( isset($pvr['mg']['id']) ) {	
	//�������� ��� �����
	$pvr['hp'] = 0;
	$pvr['data'] = $this->lookStatsArray($pvr['mg']['data']);
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
	
	$pvr['hp23'] = $pvr['hp'];
	
	$pvr['hp'] = round(((5*$pvr['hp'])/100)*150);
	
	$pvr['hp24'] = $pvr['hp'];
	
	//$pvr['hp'] = floor($pvr['hp']/20*$u->stats['mg3']);//������
	//$pvr['hp'] = floor($pvr['hp']/200*$u->stats['s5']);//��������
	//if( $btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] < floor($btl->stats[$btl->uids[$this->ue['id']]]['hpAll']/100*30) ) {
	//$pvr['hp'] = floor( $pvr['hp'] + ($pvr['hp']/100*(50*$pvr['mg']['x'])) );
	//}
	
	$pvr['hp_test'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], '�����', 1 );
	$pvr['promah_type'] = 0;
	$pvr['promah'] = false;
	$pvr['krit'] = $pvr['hp_test'][1];
	if( $pvr['krit'] == true ) {
		$pvr['hp'] = round($pvr['hp']*2);
	}
	$pvr['hpSee'] = '--';
	$pvr['hpNow'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']);
	$pvr['hpAll'] = $btl->stats[$btl->uids[$this->ue['id']]]['hpAll'];
		
	$pvr['hp25'] = $pvr['hp'];
		
	//���������� �������� �� ���� �������
	$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $this->ue['id'], 21, $pvr['hp'], 7, true );
		
	$pvr['hp26'] = $pvr['hp'];
		
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
		'<font color^^^^#'.$prv['color2'].'>�������� ������</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	//��������� �����
	//$this->addEffPr($pl,$id);
	//$this->addPriem($this->ue['id'],242,'add_notactic=1|add_nousepriem=1',2,77,2,$u->info['id'],3,'������������',0,0,1);
	
	//������� ����������
	$pvr['mg']['priem']['id'] = $pvr['mg']['id'];
	$btl->delPriem($pvr['mg'],$btl->users[$btl->uids[$this->ue['id']]],2);
	
	//�������� �������
	$this->mintr($pl);
}else{
	echo '<font color=red><b>�� ��������� ��� ����������� ������� (������ ��������)</b></font>';
	$cup = true;
}
unset($pvr);
?>