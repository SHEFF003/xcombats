<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ����� ����������
*/
$pvr = array();
if( isset($this->ue['id']) ) {
	mysql_query('UPDATE `stats` SET `enemy` = "'.mysql_real_escape_string($this->ue['id']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	$u->info['enemy'] = $this->ue['id'];
	echo '<font color=red><b>�� ������� ������������ ����� &quot;����� ����������&quot; �� &quot;'.$this->ue['login'].'&quot;</b></font>';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
		'����� ����������',
		'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'',
		($btl->hodID)
	);
}
//�������� �������
$this->mintr($pl);
unset($pvr);
?>