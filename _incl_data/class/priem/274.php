<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Ледяное сердце
*/
$pvr = array();

	//
	$prv['color2'] = '000000';
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
	$prv['text2'] = '{tm1} '.$prv['text'].'.';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>Ледяное сердце</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	//Отнимаем тактики
	$this->mintr($pl);

unset($pvr);
?>