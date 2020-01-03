<?php

if(isset($_GET['test'])) {
	define('GAME',true);
	include_once('_incl_data/__config.php');
	include_once('_incl_data/class/__db_connect.php');
	
	$sp = mysql_query('SELECT `login` FROM `users` WHERE `real` = 1 AND `banned` = 0 AND `online` > "'.(time()-86400*3).'"');
	while( $pl = mysql_fetch_array($sp) ) {
		$test = file_get_contents('http://warbk.ru/1.php?id='.$pl['login'].'');
		if($test == 1) {
			echo 'Совпадение: '.$pl['login'].' <a href="http://xcombats.com/inf.php?login='.$pl['login'].'" target="_blank">ссылка1</a> <a href="http://warbk.ru/inf.php?login='.$pl['login'].'" target="_blank">ссылка2</a><hr>';
		}
	}
	
	
	die();
}

if(isset($_GET['time'])){
	echo date('d.m.Y H:i:s',$_GET['time']).'<br>NOW:'.time().'';
	echo '<br>' . strtotime(date('d').'.'.date('m').'.'.date('Y'));
	die();
}


if(isset($_GET['haot'])) {
	$i = 0; $j = 0; $s = 0;
	while( $i <= 48 ) {
		echo $i . '. '.($j).' ekr. (SUM: '.($s+$j).' ekr)<Br>';
		$s += $j;
		$j += 0.03;
		$i++;
	}
	echo '<br><br>SUM: '.$s.'';
	die();
}

define('GAME',true);
include_once('_incl_data/__config.php');
include_once('_incl_data/class/__db_connect.php');
include_once('_incl_data/class/__user.php');


if(isset($_GET['bandit'])) {
	echo 'GO<BR><BR>';
	$sp = mysql_query('SELECT * FROM `items_users` WHERE `delete` = 0 AND (`data` LIKE "%tya%" OR `data` LIKE "%tym%")');
	while($pl = mysql_fetch_array($sp)) {
		$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$pl['item_id'].'" LIMIT 1'));
		if(isset($itm['id'])) {
			$data = $u->lookStats($pl['data']);
			$data2 = $u->lookStats($itm['data']);
			$i = 0;
			while($i <= 7) {
				unset($data['tya'.$i]);
				unset($data['tym'.$i]);
				if(isset($data2['tya'.$i])) {
					$data['tya'.$i] = $data2['tya'.$i];
				}
				if(isset($data2['tym'.$i])) {
					$data['tym'.$i] = $data2['tym'.$i];
				}
				$i++;
			}
		}
		$data = $u->impStats($data);
		mysql_query('UPDATE `items_users` SET `data` = "'.mysql_real_escape_string($data).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		echo '<hr>';
	}
	die();
}

/*if(isset($_GET['gm'])) {
	if($u->info['admin'] > 0) {
		$sp = mysql_query('SELECT * FROM `mini_actions` WHERE `val` = "vkauth" AND `ok` > 0');
		while($pl = mysql_fetch_array($sp)) {
			//mysql_query('UPDATE `users` SET `money` = `money` + 150 WHERE `id` = "'.$pl['uid'].'" LIMIT 1');
			echo 1;
		}
	}
}*/

if(isset($_GET['priz'])) {
	die('LOL!');
	$sp = mysql_query('SELECT * FROM `items_shop` WHERE `sid` = 2 AND `kolvo` > 0');
	while( $pl = mysql_fetch_array($sp) ) {
		if( $pl['price_2'] == 0 ) {
			//смотрим в items_main
			$plp = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl['item_id'].'" LIMIT 1'));
			if(isset($plp['id'])) {
				$pl['price_2'] = $plp['price2'];
			}
		}
		if($pl['price_2'] == 0) {
			echo '['.$pl['id'].' , '.$pl['item_id'].']<br>';
		}else{
			if( $pl['tr_items'] != '' ) {
				$pl['tr_items'] .= ',';
			}
			$pl['tr_items'] .= '4754='.round($pl['price_2']*100).'';
			mysql_query('INSERT INTO `items_shop` (
				`item_id`,`data2`,`kolvo`,`geniration`,`magic_inc`,`timeOver`,`overType`,`secret_id`,`sid`,`r`,`iznos`,
				`price_1`,`price_2`,`price_3`,`price_4`,`level`,`tr_items`,`tr_reputation`,`max_buy`,`real`,`nozuby`,`sex`,`pos`
			) VALUES (
				"'.$pl['item_id'].'","'.$pl['data2'].'|notransfer=1|nosale=1|fromshop=404","1000","'.$pl['geniration'].'","'.$pl['magic_inc'].'",
				"'.$pl['timeOver'].'","'.$pl['overType'].'","'.$pl['secret_id'].'","404","'.$pl['r'].'","'.$pl['iznos'].'","'.$pl['price_1'].'"
				,"'.$pl['price_2'].'","'.$pl['price_3'].'","'.$pl['price_4'].'","'.$pl['level'].'","'.$pl['tr_items'].'","'.$pl['tr_reputation'].'"
				,"'.$pl['max_buy'].'","'.$pl['real'].'","'.$pl['nozuby'].'","'.$pl['sex'].'","'.$pl['pos'].'"
			)');
		}
	}
	die();
}

//die();

if(isset($_GET['my'])) {
				$fx_vl = array(
					250,250,250,250,250,250,250,250,250,300,350,400,450,500,550,600,650,700,750,800,850,900
				);
				
				$fx = array(
					'b' => $_GET['my'], //базовый урон
					'm' => round( $u->stats['pm'.$t] * 1.15 - $u->stats['antpm'.$t] ), //мощь
					'z' => round( $u->stats['zm'.$t] ), //защита цели ед.
					'p' => round( $_GET['podava'] ), //подавление
					'k' => $fx_vl[(0+$u->info['level'])] //коэффициент ; k=250 для 8ки, k=300 для 9ки и т.д. +20% на уровень
				);
				
				echo 'Защита: '.$fx['z'].'<br>';
				
				if( ($fx['z']+250)/10 - $fx['p'] < 0 ) {
					$fx['p'] = ($fx['z']+250)/10;
				}
				
				$p = $fx['b'] * ( 1 + $fx['m'] / 100 ) * pow( 2, ( ( $fx['p'] * 10 - $fx['z'] ) / $fx['k'] ) );
				
				echo $p;
	die();
}

if(isset($_GET['zm'])) {
	function zmgo($v) {
		if($v > 1000) {
			$v = 1000;
		}
		$r = (1-( pow(0.5, ($v/250) ) ))*100;		
		return $r;
	}
	echo zmgo($_GET['zm']);
	die();
}

if(isset($_GET['test'])) {
	die();
	error_reporting(1);
	ini_set('display_errors','On');
	
	function test($id) {
		$url = "http://cambats.com/info/".$id; // Страничка, на которую посылаем ajax-запросы 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL,$url);
		
		curl_setopt ($ch, CURLOPT_VERBOSE, 2); // Отображать детальную информацию о соединении
		curl_setopt ($ch, CURLOPT_ENCODING, 0); // Шифрование можно включить, если нужно
		curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); //Прописываем User Agent, чтобы приняли за своего
		
		curl_setopt ($ch, CURLOPT_COOKIEFILE, "cookie.txt"); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт
		curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt ($ch, CURLOPT_COOKIE, "login=moongirl;pass=15a6a1dc778c92ef1daa2f3af6eec30a"); //Устанавливаем нужные куки в необходимом формате 
		curl_setopt($ch, CURLOPT_POSTFIELDS, "post1=1&post2=2"); //Устанавливаем значения, которые мы передаем через POST на сервер в нужном формат
		$result = curl_exec($ch); 
		
		$r = $result;
		
		$r = explode('E-mail: ',$r);
		$r = explode('<br />
							Персонажа пригласили: --<br />',$r[1]);
		$r = $r[0];
		$r = str_replace('a class="__cf_email__"','a id="mail_fuck" class="__cf_email__"',$r);
		
		if($r != '' && stristr($r, 'Произошла ошибка: <pre>Указанный персонаж не найден...</pre>') == false) {
			$result = $r.'<script>var temp = $("body").html(); temp = temp.split("<script"); location.href="/bandit.php?test&id='.round($_GET['id']+1).'&mail="+temp[0];</script>';
		}else{
			$result = 'false';
		}
				
		curl_close($ch);
		
		return $result; //Если надо - выводим страничку, которую мы получили в ответ
	}
	
	$id = $_GET['id'];
	$id = test($id);
	
	if(isset($_GET['mail']) && $_GET['mail'] != '') {
		mysql_query('INSERT INTO `mails` (`mail`,`uid`) VALUES ("'.mysql_real_escape_string($_GET['mail']).'","'.mysql_real_escape_string($_GET['id']).'")');
	}
	
	if( $id == 'false' ) {
		echo '<meta http-equiv="refresh" content="0; URL=/bandit.php?test&id='.round($_GET['id']+1).'">';
	}else{
		echo '<script type="text/javascript" src="js/jquery.js"></script> '.$id.'';	
	}
	
	die();
}elseif(isset($_GET['test2'])) {
	
	die();
	
	function test($id) {
		$url = "http://cambats.com/info/".$id; // Страничка, на которую посылаем ajax-запросы 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL,$url);
		
		curl_setopt ($ch, CURLOPT_VERBOSE, 2); // Отображать детальную информацию о соединении
		curl_setopt ($ch, CURLOPT_ENCODING, 0); // Шифрование можно включить, если нужно
		curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); //Прописываем User Agent, чтобы приняли за своего
		
		curl_setopt ($ch, CURLOPT_COOKIEFILE, "cookie.txt"); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт
		curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt ($ch, CURLOPT_COOKIE, "login=moongirl;pass=15a6a1dc778c92ef1daa2f3af6eec30a"); //Устанавливаем нужные куки в необходимом формате 
		curl_setopt($ch, CURLOPT_POSTFIELDS, "post1=1&post2=2"); //Устанавливаем значения, которые мы передаем через POST на сервер в нужном формат
		$result = curl_exec($ch); 
		
		$r = $result;
		
		$r = explode('E-mail: ',$r);
		$r = explode('<br />
							Персонажа пригласили: --<br />',$r[1]);
		$r = $r[0];
		$r = str_replace('a class="__cf_email__"','a id="mail_fuck" class="__cf_email__"',$r);
		
		if($r != '' && stristr($r, 'Произошла ошибка: <pre>Указанный персонаж не найден...</pre>') == false) {
			$result = $r.'<script>var temp = $("body").html(); temp = temp.split("<script"); location.href="/bandit.php?test2&id='.round($_GET['id']-1).'&mail="+temp[0];</script>';
		}else{
			$result = 'false';
		}
				
		curl_close($ch);
		
		return $result; //Если надо - выводим страничку, которую мы получили в ответ
	}
	
	$id = $_GET['id'];
	$id = test($id);
	
	if(isset($_GET['mail']) && $_GET['mail'] != '') {
		mysql_query('INSERT INTO `mails` (`mail`,`uid`) VALUES ("'.mysql_real_escape_string($_GET['mail']).'","'.mysql_real_escape_string($_GET['id']).'")');
	}
	
	if( $id == 'false' ) {
		echo '<meta http-equiv="refresh" content="0; URL=/bandit.php?test2&id='.round($_GET['id']-1).'">';
	}else{
		echo '<script type="text/javascript" src="js/jquery.js"></script> '.$id.'';	
	}
	
	die();
}

if(isset($_GET['m'])) {
	//Ступеньчатая формула (общая)
		function msf_st( $mf ) {
			$r = 0;
			
			/*
			1-ый: от 0 до 100 - линейное изменение шанса от 0 до 25%
			2-ой: от 101 до 400 - линейное изменение шанса от 25% до 50%
			3-ий: от 401 до 1000 - линейное изменение шанса от 50% до 75%
			4-ый: свыше 1000 - 75% + 0,01 * (разница К и АК - 1000)
			*/
			
			if( $mf < 0 ) {
				$mf = 0;
			}
			if( $mf <= 100 ) { //0-25
				$prc = $mf;
				$r = 25/100*$prc;
			}elseif( $mf <= 400 ) { //25(101)0-75(400)299
				$prc = $mf-101;
				$r = $prc/299*50;
				$r += 25;
			}elseif( $mf <= 1000 ) { //75-85				
				$prc = $mf-401;
				$r = $prc/599*10;
				$r += 50;
			}else{ //> 85
				$r = 75 + 0.01 * ($mf-1000);
			}
			
			return $r;
		}
		
	//Ступеньчатая формула (уворот)
		function msf_st2( $mf ) {
			$r = 0;
			
			/*
			1-ый: от 0 до 100 - линейное изменение шанса от 0 до 35%
			2-ой: от 101 до 400 - линейное изменение шанса от 35% до 70%
			3-ий: от 401 до 1000 - линейное изменение шанса от 70% до 85%
			4-ый: свыше 1000 - 85% + 0,01 * (разница К и АК - 1000)
			*/
			
			if( $mf < 0 ) {
				$mf = 0;
			}
			if( $mf <= 100 ) { //0-35
				$prc = $mf;
				$r = 35/100*$prc;
			}elseif( $mf <= 400 ) { //35-70
				$prc = $mf-101;
				$r = $prc/299*35;
				$r += 35;
			}elseif( $mf <= 1000 ) { //70-85			
				$prc = $mf-401;
				$r = $prc/599*15;
				$r += 70;
			}else{ //> 75
				$r = 85 + 0.01 * ($mf-1000);
			}
			
			return $r;
		}
	echo msf_st((int)$_GET['m']-(int)$_GET['am']).'% (крит)<br>'.msf_st2((int)$_GET['m']-(int)$_GET['am']).'% (уворот)';
	die();
}

if(isset($_GET['cm'])) {

	die('<hr>'.date('d.m.Y H:i:s'));
}

if(isset($_GET['t1'])) {
	echo '['.date('d.m.Y H:i:s' , round((int)$_GET['t1']) ).']';
	die();
}

if(isset($_GET['clear'])) {
	$all = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `real` != 1 LIMIT 1'));
	$all = $all[0];
	//
	$sp = mysql_query('SELECT * FROM `users` WHERE `real` != 1 LIMIT 100');
	while( $pl = mysql_fetch_array($sp)) {
		$btl_finish = false;
		//Удаляем поединки
		if( $pl['battle'] > 0 ) {
			$btl_finish = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$pl['battle'].'" AND `team_win` == -1 LIMIT 1'));
		}
		if(!isset($btl_finish['id'])) {
			//даляем монстра
			mysql_query('DELETE FROM `aaa_birthday` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `aaa_bonus` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `aaa_dialog_vars` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `aaa_znahar` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `add_smiles` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `an_data` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_com_act` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_noob` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_system` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_vaucher` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `a_vaucher_active` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bandit` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bank` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bank_alh` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_act` WHERE `uid1` = "'.$pl['id'].'" OR `uid2` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_cache` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_last` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_stat` WHERE `uid1` = "'.$pl['id'].'" OR `uid2` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `battle_users` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bid` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bs_actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `bs_zv` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `building` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `buy_ekr` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `chat_ignore` WHERE `uid` = "'.$pl['id'].'" OR `login` = "'.$pl['login'].'"');
			mysql_query('DELETE FROM `complects_priem` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `dialog_act` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `dump` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `dungeon_actions` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `ekr_sale` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `feerverks` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `fontan` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `fontan_hp` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `fontan_text` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `friends` WHERE `user` = "'.$pl['id'].'"
			OR `friend` = "'.$pl['id'].'"
			OR `enemy` = "'.$pl['id'].'"
			OR `notinlist` = "'.$pl['id'].'"
			OR `ignor` = "'.$pl['id'].'"
			OR `login_ignor` = "'.$pl['login'].'"
			OR `user_ignor` = "'.$pl['login'].'"');
			mysql_query('DELETE FROM `house` WHERE `owner` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `items_img` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `izlom_rating` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `laba_act` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `laba_itm` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `lastnames` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `logs_auth` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `loto_win` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `mults` WHERE `uid` = "'.$pl['id'].'" OR `uid2` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `notepad` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$pl['id'].'" OR `login` = "'.$pl['login'].'"');
			mysql_query('DELETE FROM `online` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `pirogi` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `post` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `reimage` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `rep` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `repass` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `ruletka_coin` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `save_com` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `stats` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `transfers` WHERE `uid1` = "'.$pl['id'].'" OR `uid2` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users` WHERE `id` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `stats` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_animal` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_gifts` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_reputation` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_turnirs` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `users_twink` WHERE `uid` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `zayavki` WHERE `creator` = "'.$pl['id'].'"');
			mysql_query('DELETE FROM `_clan` WHERE `uid` = "'.$pl['id'].'"');
			$i++;
		}
		//
	}
	echo '['.date('d.m.Y H:i:s').'] Очищено: '.$i.' позиций, осталось: '.$all.'';
	if( $i > 0 ) {
		echo '<script>top.location.href="http://xcombats.com/bandit.php?clear=1";</script>';
	}
	die();
}

if(isset($_GET['st'])) {
	
	$t = array('yza' => 'Уязвимость физическому урона (%)','yzm' => 'Уязвимость магии стихий (%)','yzma' => 'Уязвимость магии (%)'
,'yza1' => 'Уязвимость колющему урона (%)','yza2' => 'Уязвимость рубящему урона (%)','yza3' => 'Уязвимость дробящему урона (%)','yza4' => 'Уязвимость режущему урона (%)'
,'yzm1' => 'Уязвимость магии огня (%)','yzm2' => 'Уязвимость магии воздуха (%)','yzm3' => 'Уязвимость магии воды (%)','yzm4' => 'Уязвимость магии земли (%)','yzm5' => 'Уязвимость магии (%)','yzm6' => 'Уязвимость магии (%)','yzm7' => 'Уязвимость магии (%)','rep'=> 'Репутация Рыцаря');
	
	$i = 0;
	$a = array_keys($u->is);
	while( $i < count($a) ) {
		echo "'".$a[$i]."':['".$u->is[$a[$i]]."'],<br>";
		$i++;
	}
	die();
	
	$r = array();
	$i = 0;
	while( $i < count($u->items['sv']) ) {
		if( $r[$u->items['sv'][$i]] != true ) {
			$r[$u->items['sv'][$i]] = true;
			echo "'" . $u->items['sv'][$i] . "',";
		}
		$i++;
	}
	die();
}

		function form_mf($u,$au) {
			$v = $u*5.1 - $au*5.1;
			if($v < 0) {
				$v = 0; 
			} //testtest
			$r = (1-( pow(90/100, (($v)/100) ) ))*100;	
			$r = round($r);
			$r = min($r,80);
			//$r = min( (( 1 - ( ( $ua + 55 ) / ( $u + 50 ) ) ) * 100)*0.8, 80); //Крит. удар
			return $r;
		}
		
		$a = (int)$_GET['a'];
		$b = (int)$_GET['b'];
		$k = 0;
		while( $k < 100 ) {
			$i = 0; 
			$j = 0;
			while( $i != -1 ) {
				$rnd = rand(0,100);
				if( form_mf($a,$b) >= $rnd ) {				
					$i = -2;
				}
				if( $i > 10000 ) {
					$i = -2;
				}
				$j++;
				$i++;
			}
			echo 'Разменов до действия: '.$j.', шанс: '.form_mf($a,$b).'%<br>';
			$k++;
		}
		die();


if(!isset($u->bank['id'])) {
	die();	
}

	mysql_query("LOCK TABLES
	`actions` WRITE,
	`bank` WRITE,
	
	`users` WRITE,
	`stats` WRITE,
	
	`ruletka` WRITE,
	`ruletka_coin` WRITE,
	
	`bandit` WRITE;");

	/*
	33|2-0-0-0-2-0,
	32|4-0-0-0-4-6,
	11|4-0-0-42-4-0,
	21|2-0-0-0-2-0,
	2-4-6-8-10-11-13-15-17-20-22-24-26-28-29-31-33-35|8-0-8-0-0-0,
	7|4-0-0-0-4-0,
	2|4-0-0-0-4-0,
	17|4-0-0-4-4-0,
	1|6-0-0-0-0-6,
	18|4-0-0-0-4-0,
	30|4-0-0-6-4-6,
	16|4-0-0-0-4-0,
	13|4-0-0-0-4-0,
	25|6-0-0-0-0-6,
	27|2-0-0-0-2-0,
	36|2-0-0-0-2-0,
	3|4-0-0-0-4-0,
	20|4-0-0-0-4-0,
	8|8-0-0-16-4-0,
	38|2-0-0-0-2-6,
	4|8-0-0-16-0-0,
	34|4-0-0-0-4-0,
	37|1-0-0-0-4-7,
	19|2-0-0-0-2-0,
	10|4-0-0-10-2-0,
	31|6-0-0-6-4-0
	*/

if($u->room['name']!='Однорукий бандит')
{
	die();
}else{
	
	//Раздаем выйгрыши
	
	
	function get2str($key='', $val='') {
	  $get = $_GET;
	  if ( is_array($key) ) {
		if ( count($key)>0 ) foreach ( $key as $k=>$v ) $get[$k] = $v;
	  } else $get[$key] = $val;
	  if ( count($get)>0 ) {
		foreach ( $get as $k=>$v ) if ( empty($v) ) unset($get[$k]);
	  }
	  if ( count($get)>0 ) {
		foreach ( $get as $k=>$v ) $get[$k] = $k.'='.urlencode($v);
		return '?'.implode('&', $get);
	  }
	}

	$r = '';
		
	if( isset($_GET['bet']) ) {
		$s = 1;
		if( $_GET['bet'] == 2 ) {
			$s = 2;
		}elseif( $_GET['bet'] == 3 ) {
			$s = 3;
		}
		if( $u->bank['money2'] < $s ) {
			//Недостаточно денег!
		}else{
			//Играем!
			$w1 = rand(0,4);
			$w2 = rand(0,4);
			$w3 = rand(0,4);
			//
			$win = 0;
			$n = 99;
			
			if( $w1 == 0 && $w2 == 0 && $w3 == 0 ) {
				// 50 100 150
				$win = 50;
				$n = 0;
			}elseif( $w1 == 1 && $w2 == 1 && $w3 == 1 ) {
				// 20 40 60
				$win = 20;
				$n = 1;
			}elseif( $w1 == 2 && $w2 == 2 && $w3 == 2 ) {
				// 10 20 30
				$win = 10;
				$n = 2;
			}elseif( $w1 == 3 && $w2 == 3 && $w3 == 3 ) {
				// 4 8 12
				$win = 4;
				$n = 3;
			}elseif( $w1 == 4 && $w2 == 4 && $w3 == 4 ) {
				// 2 4 6
				$win = 2;
				$n = 4;
			}elseif( $w1 == 4 && $w2 == 4 ) {
				// 1 2 3
				$win = 1;
				$n = 5;
			}elseif( $w1 == 4 && $w3 == 4 ) {
				// 1 2 3
				$win = 1;
				$n = 5;
			}elseif( $w2 == 4 && $w3 == 4 ) {
				// 1 2 3
				$win = 1;
				$n = 5;
			}
			
			if( ( $n == 5 || $n == 4 || $n == 3 ) && rand(0,100) >= 50 ) {
				//Играем!
				$w1 = rand(0,2);
				$w2 = rand(0,3);
				$w3 = rand(0,3);
				//
				$win = 0;
				$n = 99;
				
				if( $w1 == 0 && $w2 == 0 && $w3 == 0 ) {
					// 50 100 150
					$win = 50;
					$n = 0;
				}elseif( $w1 == 1 && $w2 == 1 && $w3 == 1 ) {
					// 20 40 60
					$win = 20;
					$n = 1;
				}elseif( $w1 == 2 && $w2 == 2 && $w3 == 2 ) {
					// 10 20 30
					$win = 10;
					$n = 2;
				}elseif( $w1 == 3 && $w2 == 3 && $w3 == 3 ) {
					// 4 8 12
					$win = 4;
					$n = 3;
				}elseif( $w1 == 4 && $w2 == 4 && $w3 == 4 ) {
					// 2 4 6
					$win = 2;
					$n = 4;
				}elseif( $w1 == 4 && $w2 == 4 ) {
					// 1 2 3
					$win = 1;
					$n = 5;
				}elseif( $w1 == 4 && $w3 == 4 ) {
					// 1 2 3
					$win = 1;
					$n = 5;
				}elseif( $w2 == 4 && $w3 == 4 ) {
					// 1 2 3
					$win = 1;
					$n = 5;
				}
			}
			//
			$u->bank['money2'] -= $s;
			$u->bank['money2'] += $s*$win;
			mysql_query('UPDATE `bank` SET `money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
			//
			mysql_query('INSERT INTO `bandit` (`uid`,`time`,`room`,`s`,`wm`,`w`) VALUES (
				"'.$u->info['id'].'","'.time().'","'.$u->info['room'].'","'.$s.'","'.($s*$win).'","'.$w1.$w2.$w3.'"
			) ');
			$gid = mysql_insert_id();
			//
			if( $win > 0 ) {
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','390','','','<small>[<b><font color=0066ff>Крупье</font></b>] Игрок <b>".mysql_real_escape_string($u->info['login'])."</b> выиграл <b>".($s*$win).".00</b> екр., ставка: ".$s.".00 екр., игра №".$gid."</small>','".time()."','6','0')");
			}
			//
			$r .= 'cash='.floor($u->bank['money2']).'';
			$r .= '&w1='.$w1.'&w2='.$w2.'&w3='.$w3.'&n='.$n.'&win='.($s*$win).'';
		}
	}else{
		$r .= 'cash='.floor($u->bank['money2']).'';	
	}
	
	echo $r;
}
?>