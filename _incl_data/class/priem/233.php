<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Путь щита
*/
$pvr = array();

//Действие при клике
if( $u->stats['items'][$u->stats['wp14id']]['type'] == 13 ) {
	echo '<font color=red><b>Вы успешно использовали прием &quot;Путь щита&quot;</b></font>';
	$this->addEffPr($pl,$id);
	$pvr['x5'] = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `v2` = 233 AND `delete` = 0 LIMIT 1'));
	//
	$prv['effx'] = '';
	//Разбираем дату $pl['date3']
	$prv['eda'] = $pl['date3'];
	//
	if( isset($prv['eda']) ) {
		$prv['d'] = $u->lookStats($prv['eda']);
		$prv['j'] = $u->lookStats($this->redate($prv['eda'],$u->info['id']));
		$prv['v'] = $u->lookKeys($this->redate($prv['eda'],$u->info['id']),0); // ключи 2
		$prv['i'] = 0; $prv['inf'] = '';
		while($prv['i']<count($prv['v'])) {
			//$prv['j'][$prv['v'][$prv['i']]] += $prv['j'][$prv['v'][$prv['i']]];
			$prv['vi'] = str_replace('add_','',$prv['v'][$prv['i']]);
			if($u->is[$prv['vi']]!='') {
				if($prv['j'][$prv['v'][$prv['i']]]>0) {
					$prv['inf'] .= $u->is[$prv['vi']].': +'.($prv['j'][$prv['v'][$prv['i']]]*($pvr['x5']['x'])).', ';
				}elseif($prv['j'][$prv['v'][$prv['i']]]<0){
					$prv['inf'] .= $u->is[$prv['vi']].': '.($prv['j'][$prv['v'][$prv['i']]]*($pvr['x5']['x'])).', ';	
				}
			}
			$prv['i']++;	
		}
		$prv['effx'] = rtrim($prv['inf'],', ');
	}
	//
	if( $prv['effx'] != '' ) {
		$prv['effx'] = ' ('.$prv['effx'].')';
	}
	//
	$prv['color2'] = '000000';
	$prv['text'] = $btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
	$prv['text2'] = '{tm1} '.$prv['text'].'.'.$prv['effx'];
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>Путь Щита</font>',
		$prv['text2'],
		($btl->hodID + 0)
	);
}else{
	echo '<font color=red><b>Для использования &quot;Путь щита&quot; требуется наличие щита</b></font>';	
	$cup = true;
}

unset($pvr);
?>