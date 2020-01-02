<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Ледяное сердце
*/
$pvr = array();

	//
	$prv['upd'] = $this->rezadEff($u->info['id'],'wis_water');
	if( $prv['upd'] == false ) {
		$cup = true;
	}else{
		$pvr['os']['sp23'] = mysql_query('SELECT * FROM `eff_users` WHERE `data` LIKE "%add_nousepriem=%" AND `data` NOT LIKE "%add_noshock_voda=%" AND `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `v1` = "priem" LIMIT 1');
		while( $pvr['os']['pl23'] = mysql_fetch_array($pvr['os']['sp23']) ) {
			if( isset($pvr['os']['pl23']['id']) ) {
				$pvr['os']['pl23']['data'] .= '|add_noshock_voda=1';
				mysql_query('UPDATE `eff_users` SET `data` = "'.$pvr['os']['pl23']['data'].'" WHERE `id` = "'.$pvr['os']['pl23']['id'].'" LIMIT 1');
			}
		}
		$prv['color2'] = '000000';
		$prv['text'] = $btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
		$prv['text2'] = '{tm1} '.$prv['text'].'.';
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], 0,
			'<font color^^^^#'.$prv['color2'].'>Ледяное сердце</font>',
			$prv['text2'],
			($btl->hodID + 1)
		);	
		//Отнимаем тактики
		$this->mintr($pl);
	}

unset($pvr);
?>