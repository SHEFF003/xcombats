<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ������ ���� 5*(���) ����� ����������
*/
$pvr = array();
//�������� ��� �����

$pvr['hp'] = 5*$u->info['level'];
$pvr['hpSee'] = '--';
$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']);
$pvr['hpAll'] = $btl->stats[$btl->uids[$u->info['enemy']]]['hpAll'];
	
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
	
mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
	
$prv['text'] = $btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);

$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
	'������ ����',
	'{tm1} '.$prv['text'].' �� {u2}. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
	($btl->hodID + 1)
);

//�������� �������
$this->mintr($pl);

unset($pvr);
?>