<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ������������ ������
*/
$pvr = array();
if(isset($btl->users[$btl->uids[$u->info['enemy']]]['login'])) {
	echo '<font color=red><b>�� ������� ������������ ����� &quot;������������ ������&quot; �� &quot;'.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'&quot;</b></font>';
	mysql_query('UPDATE `stats` SET `smena` = 0 WHERE `id` = "'.mysql_real_escape_string($u->info['id']).'" LIMIT 1');
	$btl->priemAddLogFast( $u->info['id'], $u->info['enemy'], "������������ ������",
		'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$uid]]['sex'] , NULL).' �� {u2}.',
	1, time() );
}
unset($pvr);
?>