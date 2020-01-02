<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Заряд: Шок
*/
$pvr = array();
$pvr['mg'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$this->ue['id']]]['id'].'" AND `v2` = "260" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
if( isset($pvr['mg']['id']) ) {	
	//Действие при клике
	//$pvr['hp'] = floor(144/3*$pvr['mg']['x']);
	/*$pvr['hp'] = 1;*/
			//
	$pvr['data'] = $this->lookStatsArray($pvr['mg']['data']);
			//
	/**/	
	if( $pvr['data']['add_mg2static_points'][0] < 1 ) {
		echo '<font color=red><b>Статика не собрала достаточного количества зарядов</b></font>';
		$cup = true;
	}else{
		$prv['text'] = $btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
		
		//Цвет приемаы
		if( $pvr['promah'] == false ) {
			if( $pvr['krit'] == false ) {
				$prv['color2'] = '006699';
				if(isset($btl->mcolor[$btl->mname['воздух']])) {
					$prv['color2'] = $btl->mcolor[$btl->mname['воздух']];
				}
				$prv['color'] = '000000';
				if(isset($btl->mncolor[$btl->mname['воздух']])) {
					$prv['color'] = $btl->mncolor[$btl->mname['воздух']];
				}
			}else{
				$prv['color2'] = 'FF0000';
				$prv['color'] = 'FF0000';
			}
		}else{
			$prv['color2'] = '909090';
			$prv['color'] = '909090';
		}
		//Запрещаем юзать прошлый прием кол-во ходов равно уровню заряда
		$this->addPriem($this->ue['id'],264,'add_notuse_last_pr='.($this->ue['last_pr']).'',0,77,$pvr['data']['add_mg2static_points'][0],$u->info['id'],1,'зарядшок',0,0,1);
		
		//Удаляем 1 негативный эффект
		$pvr['no'] = ' AND `a`.`v2` != 201';
		
		$pvr['sp'] = mysql_query('SELECT `a`.* FROM `eff_users` AS `a` LEFT JOIN `priems` AS `b` ON `b`.`id` = `a`.`v2` WHERE `a`.`uid` = "'.$this->ue['id'].'" AND `a`.`delete` = 0 AND `a`.`v1` = "priem" '.$pvr['no'].' AND `b`.`neg` = 0 LIMIT 1');
		$pvr['pl'] = mysql_fetch_array($pvr['sp']);
		$pvr['pl']['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pvr['pl']['v2'].'" LIMIT 1'));
		if( isset($pvr['pl']['priem']) ) {
			$btl->delPriem($pvr['pl'],$btl->users[$btl->uids[$this->ue['id']]],100);		
		}
		
		$prv['text2'] = '{tm1} '.$prv['text'].' на {u2}.';
		
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
			'<font color^^^^#'.$prv['color2'].'>Заряд: Шок</font>',
			$prv['text2'],
			($btl->hodID + 1)
		);
		
		//Добавляем прием
		//$this->addEffPr($pl,$id);
		//$this->addPriem($this->ue['id'],$pl['id'],'atgm='.($pvr['hp']/16).'',2,77,4,$u->info['id'],3,'оледенение',0,0,1);
		
		//Удаляем оледенение
		$pvr['mg']['priem']['id'] = $pvr['mg']['id'];
		$btl->delPriem($pvr['mg'],$btl->users[$btl->uids[$this->ue['id']]],2);
		
		//Отнимаем тактики
		$this->mintr($pl);
	}
}else{
	echo '<font color=red><b>На персонаже нет Статики (Вашего заклятия)</b></font>';
	$cup = true;
}
unset($pvr);
?>