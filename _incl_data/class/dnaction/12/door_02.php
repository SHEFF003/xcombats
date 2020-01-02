<?
if(isset($s[1]) && $s[1] == '12/door_02') {
  $vad = array('go' => false);
  $vad['sp'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "4522" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
  if(isset($vad['sp']['id'])) {
	if($vad['sp']['inGroup'] > 0) {
	  $r = 'Предмет не должен находиться в группе';
	} else {
	  $vad['pl'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$vad['sp']['item_id'].'" LIMIT 1'));
	  $vad['go'] = true;
	}
  }
  if($u->info['x'] == '0' && $u->info['y'] == '65' && $vad['go'] == true) { 
	mysql_query('UPDATE `stats` SET `x` = "0", `y` = "66" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');  
	header('Location: main.php');
  } elseif($u->info['x'] == '0' && $u->info['y'] == '66') {
	$r = 'Дверь уже открыта';
  } elseif(!isset($vad['sp']['id'])) {
	$r = 'Для прохода требуется предмет &quot;Отпирающая руна&quot;';
  }
  unset($vad);
}
?>