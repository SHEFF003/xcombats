<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Цель воды
*/
$pvr = array();

$pvr['x5'] = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$this->ue['id'].'" AND `v2` = 25 AND `delete` = 0 LIMIT 1'));
if( $pvr['x5']['x'] < 5 ) {
	$prv['x'] = '';
	if( $pvr['x5']['x'] > 0 ) {
		$prv['x'] = ' x'.($pvr['x5']['x']+1).'';
	}
	//
	$this->addEffPr($pl,$id);
	//
	$prv['effx'] = '';
	if( $pvr['x5']['x'] > 0 ) {
		$prv['eff'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `id` = "'.$pvr['x5']['id'].'" LIMIT 1'));
		if( isset($prv['eff']['id']) ) {
			//Разбираем дату $prv['eff']['data']
			$prv['eda'] = $prv['eff']['data'];
		}
	}else{
		//Разбираем дату $pl['date3']
		$prv['eda'] = $pl['date3'];
	}
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
					$prv['inf'] .= $u->is[$prv['vi']].': +'.($prv['j'][$prv['v'][$prv['i']]]*(1+$pvr['x5']['x'])).', ';
				}elseif($prv['j'][$prv['v'][$prv['i']]]<0){
					$prv['inf'] .= $u->is[$prv['vi']].': '.($prv['j'][$prv['v'][$prv['i']]]*(1+$prv['x5']['x'])).', ';	
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
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
	$prv['text2'] = '{tm1} '.$prv['text'].'.'.$prv['effx'];
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>Цель воды'.$prv['x'].'</font>',
		$prv['text2'],
		($btl->hodID + 0)
	);
		
	//Добавляем прием
	//$this->addEffPr($pl,$id);
	//$this->addPriem($this->ue['id'],$pl['id'],'atgm='.floor($pvr['hp']/10).'',0,77,-1,$u->info['id'],5,'цельводы',0,0,1);
		
	//Отнимаем тактики
	//$this->mintr($pl);
}else{
	$cup = true;
	echo '<font color=red><b>На пероснаже достигнуто максиальное колличество целей</b></font>';
}

unset($pvr);
?>