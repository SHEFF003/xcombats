<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Рывок
*/
$pvr = array();
	
	echo '<font color=red><b>Вы успешно использовали прием &quot;Рывок&quot;</b></font>';
	
	$this->maxtr(1,3);
	$this->addEffPr($pl,$id);
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
					$prv['inf'] .= $u->is[$prv['vi']].': +'.($prv['j'][$prv['v'][$prv['i']]]).', ';
				}elseif($prv['j'][$prv['v'][$prv['i']]]<0){
					$prv['inf'] .= $u->is[$prv['vi']].': '.($prv['j'][$prv['v'][$prv['i']]]).', ';	
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
		'<font color^^^^#'.$prv['color2'].'>Рывок</font>',
		$prv['text2'],
		($btl->hodID + 0)
	);
unset($pvr);
?>