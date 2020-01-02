<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Вспышка [8]
*/
$pvr = array();

$pvr['x5'] = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$this->ue['id'].'" AND `v2` = 26 AND `delete` = 0 LIMIT 1'));
$pvr['hp11'] = 39+round(39/100*(5*$pvr['x5']['x']));
$pvr['hp22'] = $pvr['hp11'];
$pvr['hp_0'] = rand($pvr['hp11'],$pvr['hp22']);
$pvr['rx'] = 1;
$pvr['xx'] = 0;
$pvr['ix'] = $btl->uids[$this->ue['id']];
			
if($btl->stats[$pvr['ix']]['hpNow'] > 0 && $btl->users[$pvr['ix']]['team'] != $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id']) {
  $pvr['uid'] = $btl->users[$pvr['ix']]['id'];
  $pvr['hp'] = floor(rand($pvr['hp11'],$pvr['hp22']));
  $pvr['hp'] = $this->magatack( $u->info['id'], $pvr['uid'], $pvr['hp'], 'огонь', 1 );
  $pvr['promah_type'] = $pvr['hp'][3];
  $pvr['promah'] = $pvr['hp'][2];
  $pvr['krit'] = $pvr['hp'][1];
  $pvr['hp']   = $pvr['hp'][0];
  $pvr['hpSee'] = '--';
  $pvr['hpNow'] = floor($btl->stats[$btl->uids[$pvr['uid']]]['hpNow']);
  $pvr['hpAll'] = $btl->stats[$btl->uids[$pvr['uid']]]['hpAll'];
  $pvr['hp'] = $btl->testYronPriem( $u->info['id'], $pvr['uid'], 21, $pvr['hp'], 5, true );
  $pvr['hpSee'] = '-'.$pvr['hp'];
  $pvr['hpNow'] -= $pvr['hp'];
  $btl->priemYronSave($u->info['id'],$pvr['uid'],$pvr['hp'],0);
  $this->mg2static_points( $pvr['uid'] , $btl->stats[$btl->uids[$pvr['uid']]] );
  if( $pvr['hpNow'] > $pvr['hpAll'] ) {
    $pvr['hpNow'] = $pvr['hpAll'];
  }elseif( $pvr['hpNow'] < 0 ) {
	$pvr['hpNow'] = 0;
  }
  $btl->stats[$btl->uids[$pvr['uid']]]['hpNow'] = $pvr['hpNow'];
  mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$pvr['uid']]]['hpNow'].'" WHERE `id` = "'.$pvr['uid'].'" LIMIT 1');
  if( $pvr['promah'] == false ) {
	if( $pvr['krit'] == false ) {
	  $prv['color2'] = '006699';
	  if(isset($btl->mcolor[$btl->mname['огонь']])) {
		$prv['color2'] = $btl->mcolor[$btl->mname['огонь']];
	  }
	  $prv['color'] = '000000';
	  if(isset($btl->mncolor[$btl->mname['огонь']])) {
		$prv['color'] = $btl->mncolor[$btl->mname['огонь']];
	  }
	} else {
	  $prv['color2'] = 'FF0000';
	  $prv['color'] = 'FF0000';
	}
  } else {
	$prv['color2'] = '909090';
	$prv['color'] = '909090';
  }
  $prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
  if( $pvr['promah_type'] == 2 ) {
	$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
  }else{
	$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
  }
  $btl->priemAddLog( $id, 1, 2, $u->info['id'], $pvr['uid'],
					'<font color^^^^#'.$prv['color2'].'>Вспышка [8]</font>',
					$prv['text2'],
					($btl->hodID + 1)
				);
  $pvr['xx']++;
}
unset($pvr);
?>