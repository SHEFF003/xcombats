<?php

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

if(!isset($_GET['kill'])) {
	if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }
	if(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '91.228.154.180') {
		die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}

function changeSleep($uid,$sleep_action){
	global $u;
	if( $sleep_action == 1 ){
		//
		mysql_query('INSERT INTO `sleep` (`uid`,`time`,`sleep`) VALUES ("'.$uid.'","'.time().'","1")');
		//
		mysql_query("UPDATE `eff_users` SET `sleeptime`=".time().",`deactiveLast` = ( `deactiveTime` - ".time()." ) WHERE `uid`='".mysql_real_escape_string($uid)."' AND `no_Ace` = 0 AND `delete` = 0");
		mysql_query('UPDATE `items_users` SET `time_sleep` = "'.time().'" WHERE `uid` = "'.$uid.'" AND `delete` < 1001 AND `data` LIKE "%|sleep_moroz=1%"');
		$u->addAction(time(),'sleep',$u->info['city']);
		//
	} elseif( $sleep_action == 2 ){
		//
		mysql_query('INSERT INTO `sleep` (`uid`,`time`,`sleep`) VALUES ("'.$uid.'","'.time().'","2")');
		//
		$sp = mysql_query('SELECT * FROM `items_users` WHERE `time_sleep` > 0 AND `uid` = "'.$uid.'" AND `delete` < 1001 AND `data` LIKE "%|sleep_moroz=1%"');
		while( $pl = mysql_fetch_array($sp) ) {
			$tm_add = time() - $pl['time_sleep'];
			mysql_query('UPDATE `items_users` SET `time_sleep` = "0",`time_create` = "'.($pl['time_create'] + $tm_add).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		$sp = mysql_query('SELECT `id`,`deactiveTime`,`deactiveLast` FROM `eff_users` WHERE `v1` LIKE "pgb%" AND `delete` = "0" AND `deactiveTime` > 0 AND `uid` = "'.$uid.'" ORDER BY `timeUse` DESC');
		while($pl = mysql_fetch_array($sp)) {
			mysql_query("UPDATE `eff_users` SET `deactiveTime` = ".(time()+$pl['deactiveLast'])." WHERE `id`='".$pl['id']."' ");						
		}
		$sp = mysql_query('SELECT `id`,`sleeptime`,`timeUse` FROM `eff_users` WHERE `uid`="'.mysql_real_escape_string($uid).'" AND `no_Ace` = 0 AND `sleeptime` > 0 AND `delete` = 0');
		while($pl = mysql_fetch_array($sp)) {
			$timeUsen = time()-($pl['sleeptime']-$pl['timeUse']);
			mysql_query("UPDATE `eff_users` SET `timeUse`='".$timeUsen."',`sleeptime`='0' WHERE `id`='".$pl['id']."' ");
		}
		mysql_query('UPDATE `actions` SET `vars` = "unsleep",`val` = "'.time().'" WHERE `id` = "'.$sleep['id'].'" LIMIT 1');
	}
	//$sleep = $u->testAction('`vars` = "sleep" AND `uid` = "'.$uid.'" LIMIT 1', 1);
}

//Время рестарта
$cnfg = array(
	'time_restart' => 2,
	'time_puti' => 240
);

echo '#start#';

define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
include('_incl_data/class/__magic.php');

$itbs = array(
                0 => 1,1 => 131,2 => 130,3 => 6,4 => 7,5 => 129,6 => 128,7 => 127,8 => 126,9 => 125,10 => 124,11 => 123,12 => 122,13 => 121,14 => 120,15 => 119,16 => 118,17 => 117,18 => 116,19 => 115,20 => 114,21 => 113,22 => 112,23 => 111,24 => 110,25 => 109,26 => 108,27 => 107,28 => 106,29 => 105,30 => 104,31 => 103,32 => 102,33 => 101,34 => 100,35 => 99,36 => 98,37 => 97,38 => 96,39 => 95,40 => 94,41 => 93,42 => 92,43 => 91,44 => 90,45 => 89,46 => 88,47 => 87,48 => 84,49 => 85,50 => 86,51 => 73,52 => 83,55 => 82,56 => 81,57 => 132,58 => 133,59 => 134,60 => 135,61 => 136,62 => 137,63 => 138,64 => 139,65 => 140,66 => 141,67 => 142,68 => 143,69 => 144,70 => 145,71 => 146,72 => 147,73 => 148,74 => 149,75 => 150,76 => 151,77 => 152,78 => 153,79 => 154,80 => 155,81 => 156,82 => 157,83 => 158,84 => 159,85 => 160,86 => 161,87 => 162,88 => 163,89 => 164,90 => 165,91 => 166,92 => 167,93 => 168,94 => 169,95 => 170,96 => 171,97 => 172,98 => 173,99 => 174,100 => 175,101 => 176,102 => 177,103 => 178,104 => 179,105 => 180,106 => 181,107 => 182,108 => 183,109 => 184,110 => 185,111 => 186,112 => 187,113 => 188,114 => 189,115 => 190,116 => 191,117 => 192,118 => 193,119 => 194,120 => 195,121 => 196,122 => 197,123 => 198,124 => 199,125 => 200,126 => 201,127 => 202,128 => 203,129 => 204,130 => 205,131 => 206,132 => 207,133 => 208,134 => 209,135 => 210,136 => 211,137 => 212,138 => 213,139 => 214,140 => 215,141 => 216,142 => 217,143 => 218,144 => 219,145 => 220,146 => 221,147 => 222,148 => 223,149 => 224,150 => 225,151 => 226,152 => 227,153 => 228,154 => 229,155 => 230,156 => 231,157 => 232,158 => 233,159 => 234,160 => 235,161 => 236,162 => 237,163 => 238,164 => 239,165 => 240,166 => 241,167 => 242,168 => 243,169 => 244,170 => 245,171 => 246,172 => 247,173 => 248,174 => 249,175 => 250,176 => 251,177 => 252,178 => 253,179 => 254,180 => 255,181 => 256,182 => 257,183 => 258,184 => 259,185 => 260,186 => 261,187 => 262,188 => 263,189 => 264,190 => 265,191 => 266,192 => 267,193 => 268,194 => 269,195 => 270,196 => 271,197 => 272,198 => 273,199 => 274,200 => 275,201 => 276,202 => 277,203 => 278,204 => 279,205 => 280,206 => 281,207 => 282,208 => 283,209 => 284,210 => 285,211 => 286,212 => 287,213 => 288,214 => 289,215 => 290,216 => 291,217 => 292,218 => 293,219 => 294,220 => 295,221 => 296,222 => 297,223 => 298,224 => 299,225 => 300,226 => 301,227 => 302,228 => 304,229 => 305,230 => 306,231 => 307,232 => 308,233 => 309,234 => 310,235 => 311,236 => 312,237 => 313,238 => 314,239 => 315,240 => 316,241 => 317,242 => 318,243 => 319,244 => 320,245 => 321,246 => 322,247 => 323,248 => 324,249 => 325,250 => 326,251 => 327,252 => 328,253 => 329,254 => 330,255 => 331,256 => 332,257 => 333,258 => 334,259 => 335,260 => 336,261 => 337,262 => 338,263 => 339,264 => 340,265 => 341,266 => 342,267 => 343,268 => 344,269 => 345,270 => 346,271 => 347,272 => 348,273 => 349,274 => 350,275 => 351,276 => 352,277 => 353,278 => 354,279 => 355,280 => 356,281 => 357,282 => 358,283 => 359,284 => 360,285 => 361,286 => 362,287 => 363,288 => 364,289 => 365,290 => 366,291 => 367,292 => 368,293 => 369,294 => 370,295 => 371,296 => 372,297 => 373,298 => 374,299 => 375,300 => 376,301 => 377,302 => 378,303 => 379,304 => 380,305 => 381,306 => 382,307 => 383,308 => 384,309 => 385,310 => 386,311 => 387,312 => 388,313 => 389,314 => 390,315 => 391,316 => 392,317 => 393,318 => 394,319 => 395,320 => 396,321 => 397,322 => 398,323 => 399,324 => 400,325 => 401,326 => 402,327 => 403,328 => 404,329 => 405,330 => 406,331 => 407,332 => 408,333 => 409,334 => 410,335 => 411,336 => 412,337 => 413,338 => 414,339 => 415,340 => 416,341 => 417,342 => 418,343 => 419,344 => 420,345 => 421,346 => 422,347 => 423,348 => 424,349 => 425,350 => 426,351 => 427,352 => 428,353 => 429,354 => 430,355 => 431,356 => 432,357 => 433,358 => 434,359 => 435,360 => 436,361 => 437,362 => 438,363 => 439,364 => 440,365 => 441,366 => 442,367 => 443,368 => 444,369 => 445,370 => 446,371 => 447,372 => 448,373 => 449,374 => 450,375 => 451,376 => 452,377 => 453,378 => 454,379 => 455,380 => 456,381 => 457,382 => 458,383 => 459,384 => 460,385 => 461,386 => 462,387 => 463,388 => 464,389 => 465,390 => 466,391 => 467,392 => 468,393 => 469,394 => 470,395 => 471,396 => 472,397 => 473,398 => 474,399 => 475,400 => 476,401 => 477,402 => 478,403 => 479,404 => 480,405 => 481,406 => 482,407 => 483,408 => 484,409 => 485,410 => 486,411 => 487,412 => 488,413 => 489,414 => 490,415 => 491,416 => 492,417 => 493,418 => 494,419 => 495,420 => 496,421 => 497,422 => 498,423 => 499,424 => 500,425 => 501,426 => 502,427 => 503,428 => 504,429 => 505,430 => 506,431 => 507,432 => 508,433 => 509,434 => 510,435 => 511,436 => 512,437 => 513,438 => 514,439 => 515,440 => 516,441 => 517,442 => 518,443 => 519,444 => 520,445 => 521,446 => 522,447 => 523,448 => 524,449 => 525,450 => 526,451 => 527,452 => 528,453 => 529,454 => 530,455 => 531,456 => 532,457 => 533,458 => 534,459 => 535,460 => 536,461 => 537,462 => 538,463 => 539,464 => 540,465 => 541,466 => 542,467 => 543,468 => 544,469 => 545,470 => 546,471 => 547,472 => 548,473 => 549,474 => 550,475 => 1015,632          
);

function microLogin2($bus) {
	$bus['login_BIG']  = '<b>';
	if( $bus['align'] > 0 ) {
		$bus['login_BIG'] .= '<img src=http://img.xcombats.com/i/align/align'.$bus['align'].'.gif width=12 height=15 >';
	}
	if( $bus['clan'] > 0 ) {
		$bus['login_BIG'] .= '<img src=http://img.xcombats.com/i/clan/'.$bus['clan'].'.gif width=24 height=15 >';
	}
	$bus['login_BIG'] .= ''.$bus['login'].'</b>['.$bus['level'].']<a target=_blank href=http://xcombats.com/info/'.$bus['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
	return $bus['login_BIG'];
}

function addItem($id,$uid,$md = NULL,$dn = NULL,$mxiznos = NULL) {
		$rt = -1;
		$i = mysql_fetch_array(mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp` FROM `items_main` AS `im` WHERE `im`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(isset($i['id']))
		{
			$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));		
			//новая дата
			$data = $d['data'];	
			if($i['ts']>0)
			{
				$ui = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1'));
				$data .= '|sudba='.$ui['login'];
			}
			if($md!=NULL)
			{
				$data .= $md;
			}	
			
	
			if($dn!=NULL)
			{
				//предмет с настройками из подземелья
				if($dn['del']>0)
				{
					$i['dn_delete'] = 1;
				}
			}
			if($mxiznos > 0) {
				$i['iznosMAXi'] = $mxiznos;
			}
			$ins = mysql_query('INSERT INTO `items_users` (`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`,`dn_delete`) VALUES (
											"'.$i['overTypei'].'",
											"'.$i['id'].'",
											"'.$uid.'",
											"'.$data.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geni'].'",
											"'.$i['magic_inci'].'",
											"capitalcity",
											"'.time().'",
											"'.time().'",
											"'.$i['dn_delete'].'")');
			if($ins)
			{
				$rt = mysql_insert_id();
			}else{
				$rt = 0;	
			}			
	}
	return $rt;
}


function timeOut($ttm) {
	    $out = '';
		$time_still = $ttm;
		$tmp = floor($time_still/2592000);
		$id=0;
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." мес. ";}
			$time_still = $time_still-$tmp*2592000;
		}
		/*
		$tmp = floor($time_still/604800);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." нед. ";}
			$time_still = $time_still-$tmp*604800;
		}
		*/
		$tmp = floor($time_still/86400);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." дн. ";}
			$time_still = $time_still-$tmp*86400;
		}
		$tmp = floor($time_still/3600);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ч. ";}
			$time_still = $time_still-$tmp*3600;
		}
		$tmp = floor($time_still/60);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." мин. ";}
		}
		if($out=='')
		{
			if($time_still<0)
			{
				$time_still = 0;
			}
			$out = $time_still.' сек.';
		}
		return $out;
}

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=#cb0000>'.mysql_real_escape_string($t).'</font>","capitalcity","","6","1","'.time().'")');
}

function e2($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=#cb0000>'.mysql_real_escape_string($t).'</font>","capitalcity","Мусорщик","6","1","-1")');
}

//Персонаж 1 нападает на 2
function bs_atack($bs,$u1,$u2) {
	global $magic;
	if( isset($u1['id'],$u2['id']) ) {
		$btl_id = $magic->atackUser($u1['id'],$u2['id'],$u2['team'],$u2['battle']);
		if( $btl_id > 0 ) {
			mysql_query('UPDATE `battle` SET `inTurnir` = "'.$bs['id'].'" WHERE `id` = "'.$btl_id.'" LIMIT 1');
		}
		$usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `login` = "'.$u2['login'].'" AND `inUser` = "'.$u2['id'].'" LIMIT 1'));
		if( !isset($usr_real['id']) ) {
			$usr_real = $u2;
		}
		$me_real = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u1['id'].'" AND `login` = "'.$u1['login'].'" LIMIT 1'));
		if( !isset($me_real['id']) ) {
			$me_real = $u1;
		}
		if( $u2['battle'] > 0 ) {
			$u2['battle'] = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$u2['battle'].'" AND `team_win` = "-1" LIMIT 1'));	
			if( isset($u2['battle']['id']) ) {
				$u2['battle'] = $u2['battle']['id'];
			}else{
				$u2['battle'] = 0;
			}
		}
		if( $u2['battle'] > 0 ) {
			//Заносим в лог БС
			if( $u1['sex'] == 0 ) {
				$text = '{u1} вмешался в поединок против {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
			}else{
				$text = '{u1} вмешалась в поединок против {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
			}
		}else{
			//Заносим в лог БС
			if( $u1['sex'] == 0 ) {
				$text = '{u1} напал на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
			}else{
				$text = '{u1} напала на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
			}
		}
		if( isset($usr_real['id'])) {
			$usrreal = '';
			if( $usr_real['align'] > 0 ) {
				$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >';
			}
			if( $usr_real['clan'] > 0 ) {
				$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >';
			}
			$usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
		}else{
			$mereal = '<i>Невидимка</i>[??]';
		}
		if( isset($me_real['id']) ) {
			$mereal = '';
			if( $me_real['align'] > 0 ) {
				$mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >';
			}
			if( $me_real['clan'] > 0 ) {
				$mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >';
			}
			$mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
		}else{
			$mereal = '<i>Невидимка</i>[??]';
		}
		$text = str_replace('{u1}',$mereal,$text);
		$text = str_replace('{u2}',$usrreal,$text);
		//Добавляем в лог БС
		mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
			"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
			"'.round($bs['money']*0.85,2).'","'.$i.'"
		)');
	}
}

//Турнир не состоялся
function nostart($pl) {
	global $cnfg;
	$r = false;
	if( $pl['users'] < 2 ) {
		//Недостаточно игроков
		$r = true;
		$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
		if( $pl['users'] > 0 ) {
			e('Турнир для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b> не начался по причине: Недостаточно участников. Начало следующего турнира через '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
		}else{
			//if( timeOut($pl['time_start']-time()) != '44 мин.' ) {
				e('Начало турнира для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b> через '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>), текущий призовой фонд: 0.00 кр., заявок: 0');
			//}
		}
		//Возврат вкладов игроков
		$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$pl['id'].'" AND `finish` = "0"');
		while( $pu = mysql_fetch_array($sp) ) {
			mysql_query('UPDATE `users` SET `money` = `money` + "'.$pu['money'].'" WHERE `id` = "'.$pu['uid'].'" LIMIT 1');
			mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `id` = "'.$pu['id'].'" LIMIT 1');
		}
		//Обновление турнира
		mysql_query('UPDATE `bs_turnirs` SET `ch1` = "0",`ch2` = "0", `status` = "0", `money` = "0", `time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	}
	return $r;
}

//Добавление "архивариуса"
function add_arhiv($pl,$user) {
	$return = 0;
	mysql_query('INSERT INTO `users` (`login`,`pass`,`level`,`inTurnir`,`sex`,`obraz`,`name`,`online`,`city`,`room`,`align`,`clan`,`cityreg`,`bithday`,`activ`) VALUES (
		"'.$user['login'].'","bstowerbot","'.$user['level'].'","'.$pl['id'].'","'.$user['sex'].'","'.$user['obraz'].'","'.$user['login'].'","'.(time()+60*60*24).'","'.$user['city'].'","'.$user['room'].'","'.$user['align'].'","'.$user['clan'].'","capitalcity","01.02.2003","0"
	)');
	$return = mysql_insert_id();
	if( $return > 0 ) {
		$ins = mysql_query('INSERT INTO `stats` (`id`,`stats`,`bot`,`x`,`y`,`upLevel`) VALUES (
			"'.$return.'","s1=30|s2=31|s3=33|s4=30|s5=30|s6=1|s7=25|rinv=40|m9=5|m6=10","2","'.$user['x'].'","'.$user['y'].'","98"
		)');
		if(!$ins) {
			mysql_query('DELETE FROM `users` WHERE `id` = "'.$return.'" LIMIT 1');
			$return = 0;
		}
	}
	return $return;
}

//Завершаем текущий турнир
function backusers($pl) {
	$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$pl['id'].'" AND `off` = "0" AND `inBot` > 0');
	while( $pu = mysql_fetch_array($sp) ) {
		//Удаление клона
		mysql_query('DELETE FROM `users` WHERE `id` = "'.$pu['inBot'].'" LIMIT 1');
		mysql_query('DELETE FROM `stats` WHERE `id` = "'.$pu['inBot'].'" LIMIT 1');
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$pu['inBot'].'"');
		mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pu['inBot'].'"');
		mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pu['inBot'].'"');
		mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$pu['inBot'].'"');
		//Обновление персонажа
		mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$pu['uid'].'" LIMIT 1');
		//Обновляем заявку
		mysql_query('UPDATE `bs_zv` SET `off` = "'.time().'" WHERE `id` = "'.$pu['id'].'" LIMIT 1');
	}
	//Архивариусы
	$sp = mysql_query('SELECT * FROM `users` WHERE `pass` = "bstowerbot" AND `inTurnir` = "'.$pl['id'].'" AND `room` = "362"');
	while( $pu = mysql_fetch_array($sp) ) {
		mysql_query('DELETE FROM `users` WHERE `id` = "'.$pu['id'].'" LIMIT 1');
		mysql_query('DELETE FROM `stats` WHERE `id` = "'.$pu['id'].'" LIMIT 1');
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$pu['id'].'"');
		mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pu['id'].'"');
		mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pu['id'].'"');
		mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$pu['id'].'"');
	}
	//Удаляем предметы раскиданные по БС
	mysql_query('DELETE FROM `bs_items` WHERE `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'"');
	//Удаляем события в БС
	mysql_query('DELETE FROM `bs_actions` WHERE `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'"');
	//Удаляем ловушки в БС
	mysql_query('DELETE FROM `bs_trap` WHERE `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'"');
}

$exp2 = array(
	1=>30000,
	2=>300000
);
$st2s = array(
	7=>array(
		0=>10,
		1=>64,
		2=>8	
	),
	8=>array(
		0=>11,
		1=>78,
		2=>9	
	)
);

$sp = mysql_query('SELECT * FROM `bs_turnirs`');
while( $pl = mysql_fetch_array($sp) ) {
	//
	$pl['to_lvl'] = $pl['level'];
	if( $pl['level'] != $pl['level_max'] ) {
		$pl['to_lvl'] .= '-'.$pl['level_max'].'';
	}
	$pl['to_lvl'] = 'всех';
	if( $pl['status'] == 1 ) {
		//
		$tcu = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `inTurnir` = "'.$pl['id'].'" AND `room` = "362"'));
		$tcu = $tcu[0];
		//
		if( $pl['users'] != $tcu ) {
			//Что-то сбилось
			$pl['users'] = $tcu;
		}
		//		
		//Турнир идет, проверяем живых игроков, либо завершаем через 6 часов
		if( $pl['time_start'] < time() - 6*60*60 ) {
			//Завершаем турнир по тайму
			//Добавляем в лог БС
			$text = 'Турнир завершен. Победитель: <i>Отсутствует</i> (Турнир завершился по таймауту). Призовой фонд: <b>'.round($pl['money']*0.85,2).'</b> кр.';
			mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
				"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
				"'.round($pl['money']*0.85,2).'","'.$i.'"
			)');
			//
			//Сохраняем статистику
			mysql_query('INSERT INTO `bs_statistic` (`bsid`,`count`,`time_start`,`time_finish`,`time_sf`,`type_bs`,`money`,`wlogin`,`wuid`,`walign`,`wclan`) VALUES (
				"'.$pl['id'].'","'.$pl['count'].'","'.$pl['time_start'].'","'.time().'","'.(time()-$pl['time_start']).'","'.$pl['type_btl'].'","'.round($pl['money']*0.85,2).'",
				"2","0","0","0"
			)');
			$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
			e('Турнир для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b> завершился по таймауту. Начало нового турнира через '.timeOut($pl['time_start']-time()-3600).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
			backusers($pl);
			$pl['count']++;
			mysql_query('UPDATE `bs_turnirs` SET `money` = "0",`count` = "'.$pl['count'].'",`status` = "0",`time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0",`ch1` = "0",`arhiv` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}else{
			mysql_query('UPDATE `users` SET `online` = "'.(time()+60*60*6).'" WHERE `inTurnir` = "'.$pl['id'].'" OR (`room` >= 362 AND `room` <= 366)  LIMIT '.($pl['users']+$pl['arhiv']));
			//Проверяем живых игроков
			if(  $pl['users'] < 2 ) {
				mysql_query('DELEE FROM `users` WHERE `login` LIKE "%(клон%" AND `inTurnir` = "'.$pl['id'].'" AND `room` = "362"');
				if(  $pl['users'] == 1 ) {
					$pl['usersn'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `inTurnir` = "'.$pl['id'].'" AND `room` = "362" LIMIT 1'));
					$pl['usersn'] = $pl['usersn'][0];
					if( $pl['users'] != $pl['usersn'] ) {
						//$pl['users'] = $pl['usersn'];
					}
				}
				//Доп. проверка живых
				if(  $pl['users'] == 1 ) {
					//Завершаем турнир, есть 1 победитель
					if( $pl['arhiv'] == 0 ) {
						//Архивариуса нет, завершаем турнир
						$uwin_bot = mysql_fetch_array(mysql_query('SELECT `id`,`money`,`login`,`level`,`align`,`clan` FROM `users` WHERE `inTurnir` = "'.$pl['id'].'" AND `room` = "362" LIMIT 1'));
						$swin_bot = mysql_fetch_array(mysql_query('SELECT `id`,`exp` FROM `stats` WHERE `id` = "'.$uwin_bot['id'].'" LIMIT 1'));
						$uwin = mysql_fetch_array(mysql_query('SELECT `id`,`money`,`login`,`level`,`align`,`clan` FROM `users` WHERE `inUser` = "'.$uwin_bot['id'].'" AND `real` = "1" LIMIT 1'));
						$swin = mysql_fetch_array(mysql_query('SELECT `id`,`exp` FROM `stats` WHERE `id` = "'.$uwin['id'].'" LIMIT 1'));
						
						//Опыт
						$swin_bot['exp'] -= 30000;
						$swin_bot['exp'] = round($swin_bot['exp']/2);
						if( $swin_bot['exp'] < 0 ) {
							$swin_bot['exp'] = 0;
						}
						$swin_bot['exp'] += 1500;
						
						//Сохраняем статистику
						mysql_query('INSERT INTO `bs_statistic` (`bsid`,`count`,`time_start`,`time_finish`,`time_sf`,`type_bs`,`money`,`wlogin`,`wuid`,`walign`,`wclan`,`wlevel`) VALUES (
							"'.$pl['id'].'","'.$pl['count'].'","'.$pl['time_start'].'","'.time().'","'.(time()-$pl['time_start']).'","'.$pl['type_btl'].'","'.round($pl['money']*0.85,2).'",
							"'.$uwin['login'].'","'.$uwin['id'].'","'.$uwin['align'].'","'.$uwin['clan'].'","'.$uwin['level'].'"
						)');
						$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
						if( isset($uwin['id']) ) {
							mysql_query('UPDATE `users` SET `money` = "'.($uwin['money']+round($pl['money']*0.85,2)).'" WHERE `login` = "'.$uwin['login'].'"');
							mysql_query('UPDATE `stats` SET `exp` = "'.($swin['exp']+$swin_bot['exp']).'" WHERE `id` = "'.$uwin['id'].'" LIMIT 1');
							e('#'.$pl['usersn'].' Турнир для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b> завершился. Победитель: '.microLogin2($uwin).' (id'.$uwin['id'].'). Приз: <b>'.round($pl['money']*0.85,2).'</b> кр. и <b>'.round($swin_bot['exp']).'</b> опыта. Начало нового турнира через '.timeOut($pl['time_start']-time()-3600).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
						}
						//Добавляем в лог БС
						$text = 'Турнир завершен. Победитель: '.microLogin2($uwin).' ['.$uwin.'*'.$uwin_bot['login'].']. Приз: <b>'.round($pl['money']*0.85,2).'</b> кр. и <b>'.round($swin_bot['exp']).'</b> опыта.';
						mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
							"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
							"'.round($pl['money']*0.85,2).'","'.$i.'"
						)');
						//
						backusers($pl);
						$pl['count']++;
						mysql_query('UPDATE `bs_turnirs` SET `money` = "0",`count` = "'.$pl['count'].'",`status` = "0",`time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0",`ch1` = "0",`arhiv` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					}else{
						//Ожидаем пока игрок убьет Архивариуса
						
					}
				}else{
					//Сохраняем статистику
					mysql_query('INSERT INTO `bs_statistic` (`bsid`,`count`,`time_start`,`time_finish`,`time_sf`,`type_bs`,`money`,`wlogin`,`wuid`,`walign`,`wclan`) VALUES (
						"'.$pl['id'].'","'.$pl['count'].'","'.$pl['time_start'].'","'.time().'","'.(time()-$pl['time_start']).'","'.$pl['type_btl'].'","'.round($pl['money']*0.85,2).'",
						"1","0","0","0"
					)');
					//Просто завершаем турнир, ничья
					$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
					//Добавляем в лог БС
					$text = 'Турнир завершен. Победитель: <i>Отсутствует</i> (Никто не остался в живых). Призовой фонд: <b>'.round($pl['money']*0.85,2).'</b> кр.';
					mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
						"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
						"'.round($pl['money']*0.85,2).'","'.$i.'"
					)');
					//
					backusers($pl);
					$pl['count']++;
					e('Турнир для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b> завершился. Победитель: <i>Отсутствует</i> (Никто не остался в живых). Призовой фонд <b>'.round($pl['money']*0.85,2).'</b> кр. Начало нового турнира через '.timeOut($pl['time_start']-time()-3600).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
					mysql_query('UPDATE `bs_turnirs` SET `money` = "'.round($pl['money']*0.85,2).'",`count` = "'.$pl['count'].'",`status` = "0",`time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0",`ch1` = "0",`arhiv` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
			}else{
				//Все живы
				if( $pl['arhiv'] > 0 ) {
					$a_sp = mysql_query('SELECT `s`.`timeGo`,`u`.`align`,`u`.`clan`,`u`.`sex`,`u`.`pass`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`battle`,`s`.`x`,`s`.`y` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `u`.`pass` = "bstowerbot" AND `u`.`inTurnir` = "'.mysql_real_escape_string($pl['id']).'" AND `u`.`room` = "362" LIMIT 10');
					while( $a_pl = mysql_fetch_array($a_sp) ) {
						$xy = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `x` = "'.$a_pl['x'].'" AND `y` = "'.$a_pl['y'].'" LIMIT 1'));
						if( isset($xy['id']) ) {
							if( $a_pl['battle'] == 0 ) {
								//Поднимаем предметы
								$sp_itm = mysql_query('SELECT * FROM `bs_items` WHERE `x` = "'.$a_pl['x'].'" AND `y` = "'.$a_pl['y'].'" AND `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'" LIMIT 20');
								while( $pl_itm = mysql_fetch_array( $sp_itm ) ) {
									if( rand(0,100) < 21 ) {
										//Поднимаем текущий предмет
										$itm_id = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl_itm['item_id'].'" LIMIT 1'));
										if( isset($itm_id['id']) ) {
											$itm_id['odevaem'] = addItem($itm_id['id'],$a_pl['id']);
											mysql_query('DELETE FROM `bs_items` WHERE `id` = "'.$pl_itm['id'].'" LIMIT 1');
											if( $itm_id['level'] <= $a_pl['level'] && $itm_id['odevaem'] > 0 ) {
												//надеваем
												if( $itm_id['inslot'] == 10 ) {
													$itm_id['inslot'] = rand(10,12);
												}
												mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `inOdet` = "'.$itm_id['inslot'].'" AND `uid` = "'.$a_pl['id'].'" LIMIT 1');
												mysql_query('UPDATE `items_users` SET `inOdet` = "'.$itm_id['inslot'].'" WHERE `id` = "'.$itm_id['odevaem'].'" LIMIT 1');
											}
										}
									}
								}
								unset($itm_id,$sp_itm,$pl_itm);
								//Нападаем/Вмешиваемся в поединок
								if( $pl['time_start'] < time() - $cnfg['time_puti'] ) {
									$sp_usr = mysql_query('SELECT `u`.`id`,`u`.`battle`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`u`.`sex`,`s`.`team` FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `s`.`x` = "'.$a_pl['x'].'" AND `u`.`pass` != "'.$a_pl['pass'].'" AND `s`.`y` = "'.$a_pl['y'].'" ORDER BY `s`.`timeGo` ASC LIMIT 5');
									while( $pl_usr = mysql_fetch_array($sp_usr) ) {
										if( rand(0,100) < 31 && $a_pl['battle'] == 0 ) {
											$pl_usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`login`,`level`,`clan`,`align`,`battle` FROM `users` WHERE `inUser` = "'.$pl_usr['id'].'" LIMIT 1'));
											if( isset($pl_usr_real['id']) ) {
												mysql_query('UPDATE `stats` SET `hpNow` = `hpNow` + 10 WHERE `id` = "'.$a_pl['id'].'" LIMIT 1');
												mysql_query('UPDATE `stats` SET `hpNow` = `hpNow` + 10 WHERE `id` = "'.$pl_usr['id'].'" LIMIT 1');
												bs_atack($pl,$a_pl,$pl_usr);
												$a_pl['battle'] = 1;
											}
										}
									}
									unset($sp_usr,$pl_usr);
								
									if( $a_pl['battle'] == 0 && rand(0,100) < 71 && $a_pl['timeGo'] < time()) {
										//Передвигаемся
										$stor = array();
										if( $xy['up'] > 0 ) {
											$stor[] = 'up';
										}
										if( $xy['down'] > 0 ) {
											$stor[] = 'down';
										}
										if( $xy['left'] > 0 ) {
											$stor[] = 'left';
										}
										if( $xy['right'] > 0 ) {
											$stor[] = 'right';
										}
										$stor = $stor[rand(0,count($stor)-1)];
										if( $stor == 'up' ) {
											$stgo = $xy[$stor];
										}elseif( $stor == 'down' ) {
											$stgo = $xy[$stor];
										}elseif( $stor == 'left' ) {
											$stgo = $xy[$stor];
										}elseif( $stor == 'right' ) {
											$stgo = $xy[$stor];
										}
										if( $stgo == 1 ) {
											if( $stor == 'up' ) {
												$a_pl['x']--;
											}elseif( $stor == 'down' ) {
												$a_pl['x']++;
											}elseif( $stor == 'left' ) {
												$a_pl['y']--;
											}elseif( $stor == 'right' ) {
												$a_pl['y']++;
											}
										}else{
											$stgo = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `id` = "'.$stgo.'" LIMIT 1'));
											if( isset($stgo['id']) ) {
												$a_pl['x'] = $stgo['x'];
												$a_pl['y'] = $stgo['y'];	
											}
										}
										mysql_query('UPDATE `stats` SET `x` = "'.$a_pl['x'].'",`y` = "'.$a_pl['y'].'" WHERE `id` = "'.$a_pl['id'].'" LIMIT 1');									
										unset($stor,$stgo);
									}
								}
								
							}else{
								//Сражаемся
								
							}
						}
					}
				}
			}
		}
	}elseif( $pl['status'] == 0 && $pl['time_start'] < time() ) {
		//Начинаем турнир
		if( nostart( $pl ) == false ) {
						
			//Начинаем турнир!
			$spm = mysql_query('SELECT `x`,`y` FROM `bs_map` WHERE `mid` = "'.$pl['type_map'].'"');
			$maps = array( );
			while( $plm = mysql_fetch_array($spm) ) {
				$maps[] = array($plm['x'],$plm['y']);
			}
			$i = 0; $j = 0; $usrlst = array();
			$ubss = '';
			$sp_u = mysql_query('SELECT * FROM `bs_zv` WHERE `finish` = "0" AND `bsid` = "'.$pl['id'].'" ORDER BY `money` DESC');
			//
			//Создаем поход
			mysql_query('INSERT INTO `dungeon_now` (
				`id2` , `name` , `time_start` , `time_finish` , `uid` , `city` , `type` , `bsid`
			) VALUES (
				"6" , "Башня Смерти" , "'.$pl['time_start'].'" , "0" , "0" , "'.$pl['city'].'" , "0" , "'.$pl['id'].'"
			)');
			$dnew = mysql_insert_id();	
			//Добавляем обьекты
            $vls32 = '';
            $sphj = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "6"');
            while($plhj = mysql_fetch_array($sphj))
            {
                $vls32 .= '("'.$dnew.'","'.$plhj['name'].'","'.$plhj['img'].'","'.$plhj['x'].'","'.$plhj['y'].'","'.$plhj['action'].'","'.$plhj['type'].'","'.$plhj['w'].'","'.$plhj['h'].'","'.$plhj['s'].'","'.$plhj['s2'].'","'.$plhj['os1'].'","'.$plhj['os2'].'","'.$plhj['os3'].'","'.$plhj['os4'].'","'.$plhj['type2'].'","'.$plhj['top'].'","'.$plhj['left'].'","'.$plhj['date'].'"),';
            }
            $vls32 = rtrim($vls32,',');
            if($vls32!='')
            {
                $ins232 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls32.'');
            }
			unset($vls32,$ins232);
			//Добавляем предметы
			$map = array();
			$mapsp = mysql_query('SELECT `x`,`y` FROM `dungeon_map` WHERE `id_dng` = 6');
			while( $mappl = mysql_fetch_array($mapsp) ) {
				$map[] = array( 'x' => $mappl['x'] , 'y' => $mappl['y'] );
			}			
			$mapu = $map;
			//
			$ii1 = 0;
			while($ii1 < count($map)) {
				//На каждой клетке в среднем 2 предмета
				$itbsrnd = $itbs[rand(0,count($itbs)-1)];
				$mp = rand(0,count($map)-1);
				//
				$x1 = $map[$mp]['x'];
				$y1 = $map[$mp]['y'];
				//
				mysql_query('INSERT INTO `dungeon_items` (`dn`,`item_id`,`time`,`x`,`y`) VALUES (
					"'.$dnew.'","'.$itbsrnd.'","'.(time()-600).'","'.$x1.'","'.$y1.'"
				)');				
				//
				$ii1++;
			}
			
			//Добавляем чеки на кр. и на екр. на карту
			$m1 = $maps[rand(0,count($maps)-1)];
			$x1 = round($m1[0]);
			$y1 = round($m1[1]);
			//$itm1 = array( 4174 , 4175 , 4176 , 4177 , 4178 , 4179 , 4180 ); //Перечисление кр. чеков
			$itm1 = array( 4176 , 4177 , 4178 , 4179 , 4180 ); //Перечисление кр. чеков
			$itm1 = $itm1[rand(0,count($itm1)-1)];
			if( $itm1 > 0 ) {
				//
				$mp = rand(0,count($map)-1);
				//
				$x1 = $map[$mp]['x'];
				$y1 = $map[$mp]['y'];
				//
				mysql_query('INSERT INTO `dungeon_items` (`dn`,`item_id`,`time`,`x`,`y`) VALUES (
					"'.$dnew.'","'.$itm1.'","'.(time()-600).'","'.$x1.'","'.$y1.'"
				)');				
				//
			}
			
			//Добавляем монстров (Архивариусов)
			$vls0 = '';
            $zi1 = 0;
         	$id_bots = array(159,160,161);
            while($zi1 < count($id_bots)) {
                if(isset($id_bots[$zi1])) {
                    $mp = rand(0,count($map)-1);
					$x1 = $map[$mp]['x'];
					$y1 = $map[$mp]['y'];
                    $vls0 .= '("'.(time()+150).'","'.$dnew.'","'.$id_bots[$zi1].'","1","","'.$x1.'","'.$y1.'","0",""),';
                }
                $zi1++;
            }
            $vls0 = rtrim($vls0,',');
            $ins1 = mysql_query('INSERT INTO `dungeon_bots` (`go_bot`,`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`) VALUES '.$vls0.'');
			
			//
			while( $pl_u = mysql_fetch_array($sp_u) ) {
				if( $i < 40 && !isset($usrlst[$pl_u['uid']]) ) {
					//Действующие участники
					$usrlst[$pl_u['uid']] = true;
					$bus = mysql_fetch_array(mysql_query('SELECT `align`,`chatColor`,`molch1`,`molch2`,`id`,`login`,`clan`,`align`,`level`,`sex`,`online`,`room` FROM `users` WHERE `id` = "'.mysql_real_escape_string($pl_u['uid']).'" LIMIT 1'));
					
					//Замораживаем эффекты
					//changeSleep($bus['id'],1);
					//mysql_query('UPDATE `eff_users` SET `sleeptime` = "'.time().'",`bs` = "1" WHERE `uid` = "'.$bus['id'].'" AND `delete` = "0" AND `no_Ace` = "0"');
					//
					
					$bus['login_BIG']  = '<b>';
					if( $bus['align'] > 0 ) {
						$bus['login_BIG'] .= '<img src=http://img.xcombats.com/i/align/align'.$bus['align'].'.gif width=12 height=15 >';
					}
					if( $bus['clan'] > 0 ) {
						$bus['login_BIG'] .= '<img src=http://img.xcombats.com/i/clan/'.$bus['clan'].'.gif width=24 height=15 >';
					}
					$bus['login_BIG'] .= ''.$bus['login'].'</b>['.$bus['level'].']<a target=_blank href=http://xcombats.com/info/'.$bus['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
					$ubss .= ', '.$bus['login_BIG'];
					//
					//Вселяем персонажей в ботов
					if( $bus['align'] >= 1 && $bus['align'] < 2 ) {
						$bus['align'] = 1;
					}elseif( $bus['align'] >= 3 && $bus['align'] < 4 ) {
						$bus['align'] = 3;
					}elseif( $bus['align'] == 7 ) {
						$bus['align'] = 7;
					}else{
						$bus['align'] = 0;
					}
					mysql_query('INSERT INTO `users` (`chatColor`,`align`,`inTurnir`,`molch1`,`molch2`,`activ`,`login`,`room`,`name`,`sex`,`level`,`bithday`) VALUES (
						"'.$bus['chatColor'].'","'.$bus['align'].'","'.$pl['id'].'","'.$bus['molch1'].'","'.$bus['molch2'].'","0","'.$bus['login'].'","362","'.$bus['name'].'","'.$bus['sex'].'","'.$pl['level'].'","'.date('d.m.Y').'")');
					//
					$inbot = mysql_insert_id(); //айди бота
					if( $inbot > 0 ) {
						//Бот
						$mp = rand(0,count($mapu)-1);
						//
						$x1 = $mapu[$mp]['x'];
						$y1 = $mapu[$mp]['y'];
						unset($mapu[$mp]);
						//
						mysql_query('UPDATE `dailybonus` SET `bsgo` = `bsgo` + 1 WHERE `uid` = "'.$bus['id'].'" LIMIT 1');
						//
						mysql_query('INSERT INTO `stats` (`timeGo`,`timeGoL`,`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`)
						VALUES (
							"'.(time()+$cnfg['time_puti']).'","'.(time()+$cnfg['time_puti']).'","98","'.$dnew.'","'.$inbot.'",
							"s1=3|s2=3|s3=3|s4='.$st2s[$pl['level']][0].'|s5=0|s6=0|rinv=40|m9=5|m6=10","'.$exp2[$pl['level']].'",
							"'.$st2s[$pl['level']][1].'","'.$st2s[$pl['level']][2].'","'.$x1.'","'.$y1.'"
						)');
						mysql_query('UPDATE `users` SET `inUser` = "'.$inbot.'" WHERE `id` = "'.$bus['id'].'" LIMIT 1');
					}
					//Добавляем путы
					//
					mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`img2`) VALUES (
						"2","'.$inbot.'","Путы","add_speedhp=30000|add_speedmp=30000|puti='.(time()+$cnfg['time_puti']).'","1","'.(time()+$cnfg['time_puti']).'","chains.gif"
					) ');
					//
					//Обновляем данные заявки БС
					mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'",`inBot` = "'.$inbot.'" WHERE `id` = "'.$pl_u['id'].'" LIMIT 1');
					//
					unset($bus['login_BIG']);
					$i++;
				}
				$j++;
			}
			unset($sp_u,$pl_u,$bus,$usrlst);
			//Выбираем тип БС
			$pl['type_btl'] = 0;
			//
			$m1 = $maps[rand(0,count($maps)-1)];
			$x1 = round($m1[0]);
			$y1 = round($m1[1]);
			//
			unset($mis,$m1,$x1,$y1,$i2);
			//
			$ubss = ltrim($ubss,', ');			
			//
			//Обновление статуса Башни Смерти и удаление заявок
			mysql_query('UPDATE `bs_turnirs` SET `type_btl` = "'.$pl['type_btl'].'", `status` = "1", `users` = "'.$i.'", `arhiv` = "'.$pl['arhiv'].'", `users_finish` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `bsid` = "'.$pl['id'].'" AND `finish` = "0" AND `inBot` = "0"');
			//Добавляем в лог БС
			$text = 'Начало турнира. Участники: '.$ubss;
			mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
				"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
				"'.round($pl['money']*0.85,2).'","'.$i.'"
			)');
			//
			e('Начался турнир для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b>. Участники: '.$ubss.'.');
		}
	}else{
		//Оповещаем участников о начале турнира за 60 мин., а так-же за 10 мин.
		if( $pl['status'] == 0 ) {
			if( $pl['ch1'] == 0 && $pl['time_start'] - 60*60 < time()) {
				mysql_query('UPDATE `bs_turnirs` SET `ch1` = `ch1` + 1 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				//e('Начало турнира для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b> через '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>), текущий призовой фонд: '.round($pl['money']*0.85,2).' кр., заявок: '.$pl['users'].'');
			}elseif( $pl['ch1'] == 1 && $pl['time_start'] - 10*60 < time()) {
				mysql_query('UPDATE `bs_turnirs` SET `ch1` = `ch1` + 1 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				e('Начало турнира для '.$pl['to_lvl'].' уровней в <b>Башне Смерти</b> через '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>), текущий призовой фонд: '.round($pl['money']*0.85,2).' кр., заявок: '.$pl['users'].'');
			}
		}
	}
}
echo '#finish#';
?>