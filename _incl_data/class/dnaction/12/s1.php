<?
if(isset($s[1]) && $s[1] == '12/s1') {
  $vad = array('go' => true);
  $vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" LIMIT 1'));
  if($vad['test1'][0] > 0) {
	$r = 'Кто-то обыскал &quot;'.$obj['name'].'&quot; раньше вас...';
	$vad['go'] = false;
  }
  if($vad['go'] == true) {
	$vad['items'] = array(1175);
	$vad['items'] = $vad['items'][rand(0, count($vad['items'])-1)];
	if($vad['items'] != 0 && rand(0, 100) < 80) {
	  mysql_query('INSERT INTO `dungeon_actions` (`dn`, `time`, `x`, `y`, `uid`, `vars`, `vals`) VALUES ("'.$u->info['dnow'].'", "'.time().'", "'.$obj['x'].'", "'.$obj['y'].'", "'.$u->info['id'].'", "obj_act'.$obj['id'].'", "")');
	  $vad['i'] = 0;
	  $vad['items'] = array(array(888, 15), array(4759, 15), array(4523, 8), array(4524, 8), array(4525, 8), array(4526, 8), array(4527, 8), array(4528, 8), array(4529, 8), array(4530, 8), array(4531, 8));
	  if(rand(0,100) < 50) {
		$r = 'Вы ничего не нашли...';
	  } else {
		while($vad['i'] < count($vad['items'])) {
		  $vad['j'] = $vad['items'][$vad['i']];
		  if(isset($vad['j']) && !isset($vad['k'])) {
		    if(rand(0, 100) < $vad['j'][1]) {
			  $this->pickitem($obj,$vad['j'][0],0);
			  $vad['k']++;
			}
		  }
		  $vad['i']++;
		}
		if($vad['k'] == 0) {
		  $vad['itm'] = $vad['items'][rand(0, count(($vad['items']))-1)];
		  $this->pickitem($obj, $vad['itm'][0], 0);
		}
		$r = 'Вы обнаружили предметы...';
	  }		
	} else {
	  $r = 'Вы не нашли ничего полезного...';
	}
  }
  unset($vad);
}
?>