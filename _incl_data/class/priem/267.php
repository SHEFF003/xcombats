<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Кристаллизация
*/
$pvr = array();
if( isset($pr_used_this) && isset($pr_moment) ) {
	//Каждый ход
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl, $priem;	
		
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	//Действие при клике
	//$pvr['hp'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], 'вода', 1 );
	$pvr['promah_type'] = 0;
	$pvr['promah'] = false;
	$pvr['krit'] = false;
	$pvr['hp']   = $btl->stats[$btl->uids[$this->ue['id']]]['s1'];
	if( $pvr['hp'] > 10*$u->info['level'] ) {
		$pvr['hp'] = $u->info['level']*10;
	}
	$pvr['hpSee'] = '--';
	$pvr['hpNow'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']);
	$pvr['hpAll'] = $btl->stats[$btl->uids[$this->ue['id']]]['hpAll'];
		
	//Используем проверку на урон приемов
	$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $this->ue['id'], 21, $pvr['hp'], 7, true );
		
	$pvr['hpSee'] = '-'.$pvr['hp'];
	$pvr['hpNow'] -= $pvr['hp'];
	$btl->priemYronSave($u->info['id'],$this->ue['id'],$pvr['hp'],0);
		
	if( $pvr['hpNow'] > $pvr['hpAll'] ) {
		$pvr['hpNow'] = $pvr['hpAll'];
	}elseif( $pvr['hpNow'] < 0 ) {
		$pvr['hpNow'] = 0;
	}
		
	$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] = $pvr['hpNow'];
		
	mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'].'" WHERE `id` = "'.$this->ue['id'].'" LIMIT 1');
		
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
	
	//Цвет приема
	if( $pvr['promah'] == false ) {
		if( $pvr['krit'] == false ) {
			$prv['color2'] = '006699';
			if(isset($btl->mcolor[$btl->mname['вода']])) {
				$prv['color2'] = $btl->mcolor[$btl->mname['вода']];
			}
			$prv['color'] = '000000';
			if(isset($btl->mncolor[$btl->mname['вода']])) {
				$prv['color'] = $btl->mncolor[$btl->mname['вода']];
			}
		}else{
			$prv['color2'] = 'FF0000';
			$prv['color'] = 'FF0000';
		}
	}else{
		$prv['color2'] = '909090';
		$prv['color'] = '909090';
	}
	
	$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
	if( $pvr['promah_type'] == 2 ) {
		$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
		$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
	}
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>Кристаллизация</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	//Добавляем прием
	//$this->addEffPr($pl,$id);
	$this->addPriem($this->ue['id'],268,'|add_s1=-'.$btl->stats[$btl->uids[$u->info['id']]]['mg3'].'|add_s2=-'.$btl->stats[$btl->uids[$u->info['id']]]['mg3'].'',2,77,3,$u->info['id'],3,'кристаллизация',0,0,1);
	
	//Отнимаем тактики
	//$this->mintr($pl);
}

unset($pvr);
?>