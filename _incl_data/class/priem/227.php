<?
if(!defined('GAME')) {
	die();
}
/*
	�����: �������� ���*2 - ��� ����������
*/
$pvr = array();
//�������� ��� �����

if( $u->info['enemy'] > 0 ) {

	$btl->testUserInfoBattle($u->info['enemy']);
	
	$pvr['hp'] = rand($u->info['level'],2*$u->info['level']);
	$pvr['hpSee'] = '--';
	$pvr['hpNow'] = 0+floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']);
	$pvr['hpAll'] = 0+$btl->stats[$btl->uids[$u->info['enemy']]]['hpAll'];
		
	//���������� �������� �� ���� �������
	$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $u->info['enemy'], 12, $pvr['hp'], -1, true );
		
	$pvr['hpSee'] = '-'.$pvr['hp'];
	$pvr['hpNow'] -= $pvr['hp'];
		
	if( $pvr['hpNow'] > $pvr['hpAll'] ) {
		$pvr['hpNow'] = $pvr['hpAll'];
	}elseif( $pvr['hpNow'] < 0 ) {
		$pvr['hpNow'] = 0;
	}
	
	$btl->takeYronNow($u->info['id'],$pvr['hp']);
		
	$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] = $pvr['hpNow'];
	$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'] = -$pvr['hp'];
	
	mysql_query('UPDATE `stats` SET `last_hp` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'].'", `hpNow` = "'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
		
	$prv['text'] = $btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
	
	$prv['text'] = $btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
	
	if( isset($btl->stats[$btl->uids[$u->info['enemy']]]['antishock']) ) {
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
			'��������',
			'{tm1} '.$prv['text'].' �� {u2}. (������ �� ����) <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> (id'.$u->info['enemy'].') ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
			($btl->hodID + 1)
		);
	}else{
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
			'��������',
			'{tm1} '.$prv['text'].' �� {u2}. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> (id'.$u->info['enemy'].') ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
			($btl->hodID + 1)
		);
		$this->addPriem($u->info['enemy'],275,'add_notactic=1|add_nousepriem=1',0,77,2,$u->info['id'],5,'���������');
		$this->addPriem($u->info['enemy'],191,'add_antishock=1',0,77,5,$u->info['id'],5,'�������������������');		
	}
	
	/*$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
		'��������',
		'{tm1} '.$prv['text'].' �� {u2}. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
		($btl->hodID + 1)
	);*/
	
	//�������� �������
	$this->mintr($pl);

}else{
	echo '����� �� ������.';
}

unset($pvr);
?>