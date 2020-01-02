<?
if( isset($s[1]) && $s[1] == '3/musor' ) {
	/*
		Куча мусора
	*/
	//Все переменные сохранять в массиве $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = 'Ничего не произошло...<br>';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		$rn = array(
    1 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("427","'.$u->info['id'].'","Грязная кровь","add_speedhp=-40|nofastfinisheff=1","64","'.time().'") ' ,
    2 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("428","'.$u->info['id'].'","Скрытая слабость","add_s1=-2|add_s2=-2|add_s3=-2|nofastfinisheff=1","65","'.time().'") ' ,
    3 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("429","'.$u->info['id'].'","Трясущиеся Руки","add_s1=-6|nofastfinisheff=1","66","'.time().'") ' ,
    4 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("430","'.$u->info['id'].'","Желудочный Грипп","add_s2=-6|nofastfinisheff=1","67","'.time().'") ',
    5 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("431","'.$u->info['id'].'","Гудящая Голова","add_s3=-6|nofastfinisheff=1","68","'.time().'") ',
    6 => 'INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`) VALUES ("432","'.$u->info['id'].'","Ранимость","add_za=-10|nofastfinisheff=1","69","'.time().'") ');
        $rn = $rn[rand(1,6)];
        mysql_query($rn);
		$r = 'На вас наложено заклятие!<br>';
	}
	
	unset($vad);
}

?>