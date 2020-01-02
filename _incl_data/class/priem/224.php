<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Разделить кровь
*/
$pvr = array();
if( isset($this->ue['id']) ) {
	$btl->users[$btl->uids[$this->ue['id']]]['tactic6'] += 1;
	$btl->stats[$btl->uids[$this->ue['id']]]['tactic6'] += 1;
	if( $this->ue['id'] == $u->info['id'] ) {
		$u->info['tactic6'] += 1;
		$u->stats['tactic6'] += 1;
	}
	mysql_query('UPDATE `stats` SET `tactic6` = "'.mysql_real_escape_string($btl->users[$btl->uids[$this->ue['id']]]['tactic6']).'" WHERE `id` = "'.$this->ue['id'].'" LIMIT 1');
	echo '<font color=red><b>Вы успешно использовали прием &quot;Разделить кровь&quot; на &quot;'.$this->ue['login'].'&quot;</b></font>';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'Разделить кровь',
		'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' на {u2}',
		($btl->hodID)
	);
}
//Отнимаем тактики
$this->mintr($pl);
unset($pvr);
?>