<?
if(!defined('GAME')) { die(); }

if($u->room['file'] == 'bsenter') {
  $r = 2;
  if(isset($_GET['r'])) {
	$_GET['r'] = (int)$_GET['r'];
	if($_GET['r'] == 7 || $_GET['r'] == 8 || $_GET['r'] == 9 || $_GET['r'] == 10 || $_GET['r'] == 11) { $r = round($_GET['r']-6); }
	if($u->info['level'] < $r+6) { $r = 1; }
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

//Разморозка эффектов
/*$sp = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `sleeptime` > 0 LIMIT 1'));
if($sp[0] > 0) {
	changeSleep($u->info['id'],2);
}*/
/*$sp = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `bs` = 1');
while( $pl = mysql_fetch_array($sp)) {
	//$time = time()-$pl['sleeptime'];
	//if( $pl['sleeptime'] == 0 ) {
	//	$time = 0;
	//}
	//$time = $pl['timeUse'] + $time;
	//mysql_query('UPDATE `eff_users` SET `timeUse` = "'.$time.'",`sleeptime` = 0,`bs` = 0 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
}*/
//

$t = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `city` = "'.$u->info['city'].'" AND `level` = "'.((int)($r+6)).'" LIMIT 1'));
if(isset($t['id'])) {
  if($t['time_start'] > time())	{
	$tz = mysql_fetch_array(mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$t['id'].'" AND `time` = "'.$t['time_start'].'" AND `uid` = "'.$u->info['id'].'" AND `finish` = 0 LIMIT 1'));
	$tz_all = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `bs_zv` WHERE `bsid` = "'.$t['id'].'" AND `time` = "'.$t['time_start'].'" AND `uid` = "'.$u->info['id'].'" AND `finish` <= "'.$t['time_start'].'" LIMIT 1'));
  }
  if(isset($_POST['coin']) && $t['time_start'] > time() ) {
	$_POST['coin'] = round((int)$_POST['coin'],2);
	if((round((int)$tz['money'], 2) + $_POST['coin']) > 3*$u->info['level'] || $tz_all[0] > 3*$u->info['level']) {
	  $error = 'Вам нельзя делать ставку выше '.(3* $u->info['level']).' кр.';
	} elseif(($_POST['coin'] >= $t['min_money'] || (isset($tz['id']) && $_POST['coin'] >= 1)) && $u->info['money'] >= $_POST['coin']) {
	  $t['money'] += $_POST['coin'];
	  $u->info['money'] -= $_POST['coin'];
	  if(isset($tz['id'])) {
		$tz['money'] += $_POST['coin'];
		mysql_query('UPDATE `bs_zv` SET `money` = "'.$tz['money'].'" WHERE `id` = "'.$tz['id'].'" LIMIT 1');
	  } else {
				//создаем
				$tz_all = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `bs_zv` WHERE `bsid` = "'.$t['id'].'" AND `time` = "'.$t['time_start'].'" AND `uid` = "'.$u->info['id'].'" AND `finish` = "0" LIMIT 1'));
				$ins = mysql_query('INSERT INTO `bs_zv` (`bsid`,`money`,`time`,`uid`) VALUES ("'.$t['id'].'","'.mysql_real_escape_string($_POST['coin']).'","'.$t['time_start'].'","'.$u->info['id'].'")');
				if($ins) {
					$tz_all = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `bs_zv` WHERE `bsid` = "'.$t['id'].'" AND `time` = "'.$t['time_start'].'" AND `uid` = "'.$u->info['id'].'" AND `finish` = "0" LIMIT 1'));
					if( $tz_all[0] > 0 ) {
						$_POST['coin'] = $tz_all[0];
						$error = 'Ваша ставка была возвращена. ('.round($_POST['coin'],2).' кр.)';
					}
					$t['users']++;
					$tz = array('id'=>1, 'bsid'=>$t['id'], 'money'=>$_POST['coin'], 'time'=>$t['time_start'], 'finish'=>0);
				}
			}
			mysql_query('UPDATE `bs_turnirs` SET `money` = "'.$t['money'].'", `users` = "'.$t['users'].'" WHERE `id` = "'.$t['id'].'" LIMIT 1');
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}
	}
}
/*if($t['time_start']<=time() && $t['status']==0)
{
	if($t['users']>1)
	{
		//начало турнира
		mysql_query('UPDATE `bs_turnirs` SET `status` = "1" WHERE `id` = "'.$t['id'].'" LIMIT 1');
		$t['status'] = 1;
		//создаем поход
		mysql_query('INSERT INTO `dungeon_now` (`id2`,`name`,`time_start`,`time_finish`,`uid`,`city`,`type`,`bsid`) VALUES ("6","Башня смерти","'.$t['time_start'].'","0","'.$u->info['id'].'","'.$t['city'].'","0","'.$t['id'].'")');
		$zid = mysql_insert_id();
		//вселяем игроков в клонов и ставим на позиции
		
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
		
		//раскидываем вещи по карте
		$pid = 6; $map = array(); $obj = array(); $itms = array(); $usrs = array();
		$sp = mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$pid.'" LIMIT 300');
		while($pl = mysql_fetch_array($sp))
		{
			$map['date'][count($map['date'])] = $pl['id'];
			$map[$pl['x']][$pl['y']][count($map[$pl['x']][$pl['y']])] = $pl['id'];
			$map[$pl['id']] = $pl;
		}
		
		//Добавляем обьекты
		$vls = '';
		$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "'.$pid.'"');
		while($pl = mysql_fetch_array($sp))
		{
			$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
		}
		$vls = rtrim($vls,',');	
		if($vls!='')
		{			
			$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
		}
		
		$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$t['id'].'" AND `time` = "'.$t['time_start'].'" ORDER BY `money` DESC LIMIT 100');
		while($pl = mysql_fetch_array($sp))
		{
			$ur = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
			if(isset($ur['id']))
			{
				mysql_query('INSERT INTO `users` (`login`,`room`,`name`,`sex`,`level`) VALUES ("'.$ur['login'].'","264","'.$ur['name'].'","'.$ur['sex'].'","'.$t['level'].'")');
				$uri = mysql_insert_id();
				$iids = $map['date'][rand(0,count($map['date']))];
				$x1 = 0+$map[$iids]['x'];
				$y1 = 0+$map[$iids]['y'];
				mysql_query('INSERT INTO `stats` (`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`) VALUES ("98","'.$zid.'","'.$uri.'","s1=3|s2=3|s3=3|s4='.$st2s[$t['level']][0].'|s5=0|s6=0|rinv=40|m9=5|m6=10","'.$exp2[$t['level']].'","'.$st2s[$t['level']][1].'","'.$st2s[$t['level']][2].'",'.$x1.','.$y1.')');
				mysql_query('UPDATE `bs_zv` SET `inBot` = "'.$uri.'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				mysql_query('UPDATE `users` SET `inUser` = "'.$uri.'" WHERE `id` = "'.$pl['uid'].'" LIMIT 1');	
				//Добавляем эффекты скорость регена и запрет передвижения
									
			}
		}

		//раскидываем архивариусов по карте
		/*$vls0 = '';
		$zi = 0;
		$id_bots = array(0=>159,1=>160,2=>160,3=>160);
		while($zi < count($id_bots))
		{
			if(isset($id_bots[$zi]))
			{
				$iids = $map['date'][rand(0,count($map['date']))];
				$x1 = 0+$map[$iids]['x'];
				$y1 = 0+$map[$iids]['y'];
				$vls0 .= '("1","'.$zid.'","'.$id_bots[$zi].'","1","","'.$x1.'","'.$y1.'","0",""),';
			}
			$zi++;
		}
		$vls0 = rtrim($vls0,',');				
		$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`go_bot`,`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`) VALUES '.$vls0.'');
		*/
/*$itbs = array(
0 => 1,1 => 131,2 => 130,3 => 6,4 => 7,5 => 129,6 => 128,7 => 127,8 => 126,9 => 125,10 => 124,11 => 123,12 => 122,13 => 121,14 => 120,15 => 119,16 => 118,17 => 117,18 => 116,19 => 115,20 => 114,21 => 113,22 => 112,23 => 111,24 => 110,25 => 109,26 => 108,27 => 107,28 => 106,29 => 105,30 => 104,31 => 103,32 => 102,33 => 101,34 => 100,35 => 99,36 => 98,37 => 97,38 => 96,39 => 95,40 => 94,41 => 93,42 => 92,43 => 91,44 => 90,45 => 89,46 => 88,47 => 87,48 => 84,49 => 85,50 => 86,51 => 73,52 => 74,53 => 76,54 => 83,55 => 82,56 => 81,57 => 132,58 => 133,59 => 134,60 => 135,61 => 136,62 => 137,63 => 138,64 => 139,65 => 140,66 => 141,67 => 142,68 => 143,69 => 144,70 => 145,71 => 146,72 => 147,73 => 148,74 => 149,75 => 150,76 => 151,77 => 152,78 => 153,79 => 154,80 => 155,81 => 156,82 => 157,83 => 158,84 => 159,85 => 160,86 => 161,87 => 162,88 => 163,89 => 164,90 => 165,91 => 166,92 => 167,93 => 168,94 => 169,95 => 170,96 => 171,97 => 172,98 => 173,99 => 174,100 => 175,101 => 176,102 => 177,103 => 178,104 => 179,105 => 180,106 => 181,107 => 182,108 => 183,109 => 184,110 => 185,111 => 186,112 => 187,113 => 188,114 => 189,115 => 190,116 => 191,117 => 192,118 => 193,119 => 194,120 => 195,121 => 196,122 => 197,123 => 198,124 => 199,125 => 200,126 => 201,127 => 202,128 => 203,129 => 204,130 => 205,131 => 206,132 => 207,133 => 208,134 => 209,135 => 210,136 => 211,137 => 212,138 => 213,139 => 214,140 => 215,141 => 216,142 => 217,143 => 218,144 => 219,145 => 220,146 => 221,147 => 222,148 => 223,149 => 224,150 => 225,151 => 226,152 => 227,153 => 228,154 => 229,155 => 230,156 => 231,157 => 232,158 => 233,159 => 234,160 => 235,161 => 236,162 => 237,163 => 238,164 => 239,165 => 240,166 => 241,167 => 242,168 => 243,169 => 244,170 => 245,171 => 246,172 => 247,173 => 248,174 => 249,175 => 250,176 => 251,177 => 252,178 => 253,179 => 254,180 => 255,181 => 256,182 => 257,183 => 258,184 => 259,185 => 260,186 => 261,187 => 262,188 => 263,189 => 264,190 => 265,191 => 266,192 => 267,193 => 268,194 => 269,195 => 270,196 => 271,197 => 272,198 => 273,199 => 274,200 => 275,201 => 276,202 => 277,203 => 278,204 => 279,205 => 280,206 => 281,207 => 282,208 => 283,209 => 284,210 => 285,211 => 286,212 => 287,213 => 288,214 => 289,215 => 290,216 => 291,217 => 292,218 => 293,219 => 294,220 => 295,221 => 296,222 => 297,223 => 298,224 => 299,225 => 300,226 => 301,227 => 302,228 => 304,229 => 305,230 => 306,231 => 307,232 => 308,233 => 309,234 => 310,235 => 311,236 => 312,237 => 313,238 => 314,239 => 315,240 => 316,241 => 317,242 => 318,243 => 319,244 => 320,245 => 321,246 => 322,247 => 323,248 => 324,249 => 325,250 => 326,251 => 327,252 => 328,253 => 329,254 => 330,255 => 331,256 => 332,257 => 333,258 => 334,259 => 335,260 => 336,261 => 337,262 => 338,263 => 339,264 => 340,265 => 341,266 => 342,267 => 343,268 => 344,269 => 345,270 => 346,271 => 347,272 => 348,273 => 349,274 => 350,275 => 351,276 => 352,277 => 353,278 => 354,279 => 355,280 => 356,281 => 357,282 => 358,283 => 359,284 => 360,285 => 361,286 => 362,287 => 363,288 => 364,289 => 365,290 => 366,291 => 367,292 => 368,293 => 369,294 => 370,295 => 371,296 => 372,297 => 373,298 => 374,299 => 375,300 => 376,301 => 377,302 => 378,303 => 379,304 => 380,305 => 381,306 => 382,307 => 383,308 => 384,309 => 385,310 => 386,311 => 387,312 => 388,313 => 389,314 => 390,315 => 391,316 => 392,317 => 393,318 => 394,319 => 395,320 => 396,321 => 397,322 => 398,323 => 399,324 => 400,325 => 401,326 => 402,327 => 403,328 => 404,329 => 405,330 => 406,331 => 407,332 => 408,333 => 409,334 => 410,335 => 411,336 => 412,337 => 413,338 => 414,339 => 415,340 => 416,341 => 417,342 => 418,343 => 419,344 => 420,345 => 421,346 => 422,347 => 423,348 => 424,349 => 425,350 => 426,351 => 427,352 => 428,353 => 429,354 => 430,355 => 431,356 => 432,357 => 433,358 => 434,359 => 435,360 => 436,361 => 437,362 => 438,363 => 439,364 => 440,365 => 441,366 => 442,367 => 443,368 => 444,369 => 445,370 => 446,371 => 447,372 => 448,373 => 449,374 => 450,375 => 451,376 => 452,377 => 453,378 => 454,379 => 455,380 => 456,381 => 457,382 => 458,383 => 459,384 => 460,385 => 461,386 => 462,387 => 463,388 => 464,389 => 465,390 => 466,391 => 467,392 => 468,393 => 469,394 => 470,395 => 471,396 => 472,397 => 473,398 => 474,399 => 475,400 => 476,401 => 477,402 => 478,403 => 479,404 => 480,405 => 481,406 => 482,407 => 483,408 => 484,409 => 485,410 => 486,411 => 487,412 => 488,413 => 489,414 => 490,415 => 491,416 => 492,417 => 493,418 => 494,419 => 495,420 => 496,421 => 497,422 => 498,423 => 499,424 => 500,425 => 501,426 => 502,427 => 503,428 => 504,429 => 505,430 => 506,431 => 507,432 => 508,433 => 509,434 => 510,435 => 511,436 => 512,437 => 513,438 => 514,439 => 515,440 => 516,441 => 517,442 => 518,443 => 519,444 => 520,445 => 521,446 => 522,447 => 523,448 => 524,449 => 525,450 => 526,451 => 527,452 => 528,453 => 529,454 => 530,455 => 531,456 => 532,457 => 533,458 => 534,459 => 535,460 => 536,461 => 537,462 => 538,463 => 539,464 => 540,465 => 541,466 => 542,467 => 543,468 => 544,469 => 545,470 => 546,471 => 547,472 => 548,473 => 549,474 => 550,475 => 570,476 => 571,477 => 572,478 => 573,479 => 574,480 => 575,481 => 576,482 => 577,483 => 578,484 => 586,485 => 587,486 => 588,487 => 589,488 => 590,489 => 591,490 => 592,491 => 599,492 => 600,493 => 601,494 => 602,495 => 603,496 => 604,497 => 610,498 => 611,499 => 612,500 => 613,501 => 614,502 => 615,503 => 616,504 => 617,505 => 618,506 => 619,507 => 620,508 => 624,509 => 630,510 => 631,511 => 635,512 => 636,513 => 637,514 => 638,515 => 639,516 => 640,517 => 661,518 => 662,519 => 663,520 => 664,521 => 665,522 => 724,523 => 725,524 => 726,525 => 727,526 => 729,527 => 730,528 => 731,529 => 732,530 => 733,531 => 734,532 => 739,533 => 740,534 => 741,535 => 742,536 => 743,537 => 744,538 => 745,539 => 746,540 => 747,541 => 754,542 => 755,543 => 756,544 => 757,545 => 758,546 => 759,547 => 760,548 => 761,549 => 762,550 => 763,551 => 766,552 => 767,553 => 768,554 => 769,555 => 770,556 => 771,557 => 772,558 => 773,559 => 774,560 => 778,561 => 779,562 => 780,563 => 781,564 => 782,565 => 783,566 => 784,567 => 791,568 => 792,569 => 793,570 => 794,571 => 795,572 => 796,573 => 797,574 => 798,575 => 799,576 => 800,577 => 801,578 => 805,579 => 806,580 => 807,581 => 808,582 => 815,583 => 816,584 => 817,585 => 818,586 => 819,587 => 824,588 => 825,589 => 826,590 => 827,591 => 828,592 => 829,593 => 832,594 => 833,595 => 834,596 => 835,597 => 836,598 => 837,599 => 838,600 => 839,601 => 840,602 => 841,603 => 843,604 => 844,605 => 845,606 => 846,607 => 847,608 => 865,609 => 868,610 => 869,611 => 870,612 => 871,613 => 872,614 => 873,615 => 874,616 => 883,617 => 884,618 => 885,619 => 886,620 => 887,621 => 911,622 => 912,623 => 930,624 => 931,625 => 932,626 => 997,627 => 998,628 => 999,629 => 1000,630 => 1001,631 => 1015,632 => 1025,633 => 1028,634 => 1029,635 => 1030,636 => 1031,637 => 1032,638 => 1033,639 => 1034,640 => 1036,641 => 1037,642 => 1038,643 => 1039,644 => 1040,645 => 1041,646 => 1074,647 => 1044,648 => 1045,649 => 1046,650 => 1047,651 => 1048,652 => 1049,653 => 1050,654 => 1051,655 => 1052,656 => 1053,657 => 1054,658 => 1055,659 => 1056,660 => 1057,661 => 1058,662 => 1059,663 => 1060,664 => 1061,665 => 1062,666 => 1063,667 => 1064,668 => 1065,669 => 1066,670 => 1067,671 => 1068,672 => 1069,673 => 1070,674 => 1071,675 => 1072,676 => 1073,677 => 1075,678 => 1076,679 => 1077,680 => 1078,681 => 1080,682 => 1081,683 => 1082,684 => 1083,685 => 1084,686 => 1085,687 => 1086,688 => 1087,689 => 1088,690 => 1089,691 => 1090,692 => 1091,693 => 1092,694 => 1093,695 => 1094,696 => 1095,697 => 1096,698 => 1097,699 => 1098,700 => 1099,701 => 1100,702 => 1101,703 => 1102,704 => 1103,705 => 1104,706 => 1105,707 => 1106,708 => 1107,709 => 1108,710 => 1109,711 => 1110,712 => 1111,713 => 1112,714 => 1113,715 => 1114,716 => 1115,717 => 1116,718 => 1117,719 => 1118,720 => 1119,721 => 1120,722 => 1121,723 => 1122,724 => 1123,725 => 1124,726 => 1125,727 => 1126,728 => 1127,729 => 1128,730 => 1132,731 => 1141,732 => 1144,733 => 1163,734 => 1164,735 => 1165,736 => 1172,737 => 1173,738 => 1176,739 => 1182,740 => 1186,741 => 1187,742 => 1188,743 => 1190,744 => 1192
);
			
			$i = 0; $ii = 0; $cit = array(); $ins = '';
			while($i<count($map['date']))
			{
				if($map['date'][$i] > 0)
				{
					$id = $map['date'][$i];
					if(rand(0,10000)>3777)
					{
						$j = 1; $jr = rand(100,300); $jr = floor($jr/100);
						while($j<=$jr)
						{
							if(rand(0,2)==1)
							{
								$iid = rand(0,744);
							}elseif(rand(0,1)==1)
							{
								$iid = rand(144,544);
							}else{
								$iid = (744-rand(0,744));
							}
							if(!isset($cit[$iid]))
							{
								//добавляем предмет
								$ins .= '("'.$zid.'","'.$itbs[$iid].'","'.time().'","'.$map[$id]['x'].'","'.$map[$id]['y'].'"),';
							}
							$ii++;
							$j++;
						}
					}
				}
				$i++;
			}
			//dn,item_id,time,x,y
			$ins = rtrim($ins,',');
			mysql_query('INSERT INTO `dungeon_items` (`dn`,`item_id`,`time`,`x`,`y`) VALUES '.$ins.'');
		//раскидываем обьекты по карте
			
		//сообщение в чат о начале турнира
			
	}else{
		//завершаем турнир
		mysql_query('UPDATE `bs_turnirs` SET `status` = "0",`users_finish` = "0",`money` = "0",`time_start` = "'.(time()+$t['time_out']*60).'",`users` = "0" WHERE `id` = "'.$t['id'].'" LIMIT 1');
		unset($tz);
		$t['status'] = 0;
		$t['money'] = 0;
		$t['users'] = 0;
		$t['time_start'] = (time()+$t['time_out']*60);	
	}
}elseif($t['status']==1 && ($tn['time_finish']>0 || time()-$tn['time_start']>43200 || $t['users']-$t['users_finish']<2 || !isset($tn['id'])))
{
	//завершаем турнир
	mysql_query('UPDATE `bs_turnirs` SET `status` = "0",`users_finish` = "0",`money` = "0",`time_start` = "'.(time()+$t['time_out']*60).'",`users` = "0" WHERE `id` = "'.$t['id'].'" LIMIT 1');
	
	//удаляем ботов в которых вселились
	$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$t['id'].'" AND `time` = "'.$t['time_start'].'" AND `finish` = "0" ORDER BY `money` DESC LIMIT 100');	
	while($pl = mysql_fetch_array($sp))
	{
		$ur = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`room`,`name`,`sex`,`inUser` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
		if(isset($ur['id']))
		{
			//del
			mysql_query('DELETE FROM `users` WHERE `id` = "'.$ur['inUser'].'" LIMIT 1');
			mysql_query('DELETE FROM `stats` WHERE `id` = "'.$ur['inUser'].'" LIMIT 1');	
			mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$ur['inUser'].'" LIMIT 1');	
			mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$ur['inUser'].'" LIMIT 1');	
			//upd
			mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$pl['uid'].'" LIMIT 1');					
		}
	}
	mysql_query('DELETE FROM `dungeon_now` WHERE `bsid` = "'.$t['inUser'].'" AND `time_start` = "'.$t['time_start'].'" LIMIT 1');
	//Визуальные обновления
	unset($tz);
	$t['status'] = 0;
	$t['money'] = 0;
	$t['users'] = 0;
	$t['time_start'] = (time()+$t['time_out']*60);	
}*/
if($u->info['id'] != 7) {
?>
<style>
body {
  background-color:#dfdfdf;
  background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
  background-repeat:no-repeat;background-position:top right;
}
</style>
<?
if($re != '') {
  echo '<div style="float:right"><font color=red><b>'.$re.'</b></font></div>';
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div></td>
    <td width="200"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td><table  border="0" cellpadding="0" cellspacing="0">
              <tr align="right" valign="top">
                <td><!-- -->
                    <? echo $goLis; ?>
                    <!-- -->
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                            <tr>
                              <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.11&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.11',1); ?>">Страшилкина ул.</a></td>
                            </tr>
                        </table></td>
                      </tr>
                  </table></td>
              </tr>
          </table></td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<center><h4><font color=green>Поздравляем! Вы успешно допускаетесь к турниру!</font></h4></center><br>
<P align=right><INPUT class="btnnew" TYPE="button" onClick="location = 'main.php?rnd=<?=$code;?>';" value="Обновить"> &nbsp; </P>
<form method="post" action="main.php?rnd=<?=$code;?>">
<fieldset style="padding: 5px; border:1px solid #CCC;">
<legend style='font-weight:bold; color:#8F0000;'><h4><? if($t['status'] == 0) { ?>Прием заявок на следующий турнир <? } else { ?>Текущий турнир<? } ?></h4></legend>

<!--Уровень:
<select name="level" onChange="location='main.php?r='+this.value+'&rnd=<?=$code;?>';">
        <option value="11" <? if($r == 5) { echo 'selected'; } if($u->info['level'] < 11) { echo 'disabled'; } ?> disabled >11</option>
        <option value="10" <? if($r == 4) { echo 'selected'; } if($u->info['level'] < 10) { echo 'disabled'; } ?> disabled >10</option>
        <option value="9"  <? if($r == 3) { echo 'selected'; } if($u->info['level'] < 9) { echo 'disabled'; } ?> disabled >9</option>
        <option value="8"  <? if($r == 2) { echo 'selected'; } if($u->info['level'] < 8) { echo 'disabled'; } ?> >8</option>
        <option value="7"  <? if($r == 1) { echo 'selected'; } ?> >7</option>
</select><br>-->
<? if(!isset($t['id'])) { echo '<br><center>К сожалению турниры данного типа не проводятся в этом городе</center><br>'; } else {
if($t['status'] == 0) {
?>
Начало турнира: <span class=date><?=date('d.m.Y H:i:',$t['time_start']);?>59</span><BR>
Призовой фонд на текущий момент: <B><?=round(($t['money']-($t['money']/100*15)),2);?></B> кр.<BR>
Всего подано заявок: <B><?=$t['users'];?></B><BR>
<?
if($error != '') {
  echo '<font color=red><b>'.$error.'</b></font><Br>';
}
?>
<? if(!isset($tz['id'])) { ?>
Сколько ставите кредитов? (минимальная ставка <b><?=$t['min_money'];?>.00 кр.</B> у вас в наличии <b><? echo floor($u->info['money']); ?> кр.</b>)<BR><input type="text" name="coin" value="4.00" size="8"> <input
type="submit" value="Подать заявку" name="docoin" class="btnnew"><BR>
Чем выше ваша ставка, тем больше шансов принять участие в турнире.<BR>
<? }else{ ?>
Вы уже сделали ставку <b><? echo floor($tz['money']); ?> кр.</b> (У вас в наличии <b><? echo floor($u->info['money']); ?> кр.</b>) Сделать повторную ставку?<br />
<small><b><font color=red>Внимание! Покинув помещение Башни Смерти все Ваши ставки будут потеряны!</font></b></small><br>
<input type="text" name="coin" value="1.00" size="8" id="coin" />
<input type="submit" value="Увеличить ставку" name="docoin2" class="btnnew" />
<? } } elseif($t['status'] == 1) { ?>
<?
$r = ''; $p = ''; $b = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tbody>
    <tr valign="top">
      <td valign="bottom" nowrap="" title=""><input onClick="location=location;" style="padding:5px;" type="submit" name="analiz2" value="Обновить"></td>
    </tr>
  </tbody>
</table>';
$notowerlog = false;
$log = mysql_fetch_array(mysql_query('SELECT `id`,`count_bs`,`m` FROM `bs_logs` WHERE `count_bs` = "'.mysql_real_escape_string($t['count']).'" ORDER BY `id` ASC LIMIT 1'));
if(!isset($log['id'])) {
	$notowerlog = true;
	$r = '<div>Скорее всего Архивариус снова потерял пергамент с хрониками турниров ...</div>';
} else {
	$sp = mysql_query('SELECT * FROM `bs_logs` WHERE `count_bs` = "'.$log['count_bs'].'" ORDER BY `id` ASC');
	while( $pl = mysql_fetch_array($sp) ) {
		$datesb = '';
		if( $pl['type'] == 2 ) {
			$datesb = '2';
		}
		$r .= '<br><span class="date'.$datesb.'">'.date('d.m.y H:i',$pl['time']).'</span> '.$pl['text'].'';
	}
	$liveusers = '';
	$sp = mysql_query('SELECT `id` FROM `users` WHERE `inTurnir` = "'.$t['id'].'"');
	while( $plu = mysql_fetch_array($sp) ) {
		$pl = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`level` FROM `users` WHERE `inUser` = "'.$plu['id'].'"'));
		if( isset($pl['id']) ) {
			$alc = '';
			if( $pl['align'] > 0 ) {
				$alc .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$pl['align'].'.gif >';
			}
			if( $pl['clan'] > 0 ) {
				$alc .= '<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$pl['clan'].'.gif >';
			}
			$liveusers .= ', <b>'.$alc.$pl['login'].'</b> ['.$pl['level'].']<a href=/info/'.$plu['id'].' target=_blank><img src=http://img.xcombats.com/i/inf_capitalcity.gif width=12 height=11 ></a>';
		}
	}
	$r .= '<br><br>Всего живых участников на данный момент: <b>'.$t['users'].'</b> ('.ltrim($liveusers,', ').')';
	unset($liveusers,$alc);
}
if( $notowerlog == false ) {?>
Призовой фонд: <b><?=$log['m']?> кр.</b>
<? } echo $r; ?>
<? } } ?>
<BR>
</fieldset>
</form>
<h4>Победители 10-ти предыдущих турниров для <?=$t['level']?> уровней</h4>
<?
$sp = mysql_query('SELECT * FROM `bs_statistic` WHERE `wuid` > 0 ORDER BY `id` DESC LIMIT 10');
$i = 1;
while ($pl = mysql_fetch_array($sp)) {
	$wuser = '<b>'.$pl['wlogin'].'</b> ['.$pl['wlevel'].']<a href=/info/'.$pl['wuid'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
	if( $pl['wclan'] > 0 ) {
		$wuser = '<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$pl['wclan'].'.gif>'.$wuser;
	}
	if( $pl['walign'] > 0 ) {
		$wuser = '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$pl['walign'].'.gif>'.$wuser;
	}
	echo $i.'. Победитель: '.$wuser.' Начало турнира <span class=date>'.date('d.m.Y H:i',($pl['time_start']+60)).'</span> продолжительность <span class=date>'.$u->timeOut( $pl['time_sf'] ).'</span> приз: <b>'.$pl['money'].'</b> <a target=_blank href=/towerlog.php?towerid='.$pl['bsid'].'&id='.$pl['count'].' >История турнира »»</a><br>';
	$i++;
}
?>
<h4>Максимальный выигрыш для <?=$t['level']?> уровней</h4>
<?
$sp = mysql_query('SELECT * FROM `bs_statistic` WHERE `wuid` > 0 ORDER BY `money` DESC LIMIT 1');
$i = 1;
while ($pl = mysql_fetch_array($sp)) {
	$wuser = '<b>'.$pl['wlogin'].'</b> ['.$pl['wlevel'].']<a href=/info/'.$pl['wuid'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
	if( $pl['wclan'] > 0 ) {
		$wuser = '<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$pl['wclan'].'.gif>'.$wuser;
	}
	if( $pl['walign'] > 0 ) {
		$wuser = '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$pl['walign'].'.gif>'.$wuser;
	}
	echo 'Победитель: '.$wuser.' Начало турнира <span class=date>'.date('d.m.Y H:i',($pl['time_start']+60)).'</span> продолжительность <span class=date>'.$u->timeOut( $pl['time_sf'] ).'</span> приз: <b>'.$pl['money'].'</b> <a target=_blank href=/towerlog.php?towerid='.$pl['bsid'].'&id='.$pl['count'].' >История турнира »»</a><br>';
	$i++;
}
?>
<h4>Самый продолжительный турнир для <?=$t['level']?> уровней</h4>
<?
$sp = mysql_query('SELECT * FROM `bs_statistic` WHERE `wuid` > 0 ORDER BY `time_sf` DESC LIMIT 1');
$i = 1;
while ($pl = mysql_fetch_array($sp)) {
	$wuser = '<b>'.$pl['wlogin'].'</b> ['.$pl['wlevel'].']<a href=/info/'.$pl['wuid'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
	if( $pl['wclan'] > 0 ) {
		$wuser = '<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$pl['wclan'].'.gif>'.$wuser;
	}
	if( $pl['walign'] > 0 ) {
		$wuser = '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$pl['walign'].'.gif>'.$wuser;
	}
	echo 'Победитель: '.$wuser.' Начало турнира <span class=date>'.date('d.m.Y H:i',($pl['time_start']+60)).'</span> продолжительность <span class=date>'.$u->timeOut( $pl['time_sf'] ).'</span> приз: <b>'.$pl['money'].'</b> <a target=_blank href=/towerlog.php?towerid='.$pl['bsid'].'&id='.$pl['count'].' >История турнира »»</a><br>';
	$i++;
}
?>
<br /><br />
<? } } ?>