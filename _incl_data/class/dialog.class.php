<?
if(!defined('GAME'))
{
	die();
}

class dialog
{
	public $info,$dText = '',$aText = '',$youInfo = '',$botInfo = '',$title = '',$p = 1,$pg = 1;
	
	public function trnObj($x,$j)
	{
		$r = array(0=>0,1=>0);
		$i = floor($x/$j);
		$r = array(0=>$i,1=>$x-($i*$j));
		return $r;
	}
	
	public function objLevel($t,$l)
	{
		$i = 1;
		$r = 0;		
		while($i<=$l)
		{
			if(isset($t[$i]))
			{
				$r = $t[$i];
			}
			$i++;	
		}		
		return $r;	
	}
	
	public function start($id)
	{
		global $u,$q,$c,$d,$code;
		$this->info = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_dialog` WHERE `id` = "'.mysql_real_escape_string((int)$id).'" LIMIT 1'));
		if(isset($this->info['id']))
		{
			$pg = 1;
			$go = 1;
			$txt = '';
			//Переход по страницам
			if(isset($_GET['act']))
			{
				$ta = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_dlg` WHERE `type` = "0" AND `id` = "'.mysql_real_escape_string((int)$_GET['act']).'" AND `id_dg` = "'.$this->info['id'].'" LIMIT 1'));
				if(isset($ta['id']))
				{
					$ta['action'] = $this->ltr($ta['action']);
					$act = explode('|',$ta['action']);
					$go1 = 1;
					$needRep = array();
					if($ta['tr'] != '') {
						$i = 0;
						$x = explode('|',$ta['tr']);
						while($i < count($x)) {
							//Требования
							$k = explode('=',$x[$i]);								
							if($k[0]=='data') {
								$date = explode('-',$k[1]);
								$dd1 = $date[0];
								$mm1 = $date[1];
								$dd2 = $date[2];
								$mm2 = $date[3];
								$date1 = strtotime($dd1.'-'.$mm1.'-'.date('Y'));
								$date2 = strtotime($dd2.'-'.$mm2.'-'.date('Y'));
								
								if( $date1 > time() || $date2 < time() ) {
									$go1 = 'delete';
									$i = count($x);
								}
								
							}elseif($k[0]=='diact') {
								//Действия
								//user_id # all # all # lukaqst1 # -1
								if($this->quest_act($k[1])==false) {
									$go1 = 'delete';
									$i = count($x);
								}								
							}elseif($k[0]=='quest_end') {
								//Квест можно выполнять несколько раз в текущей пещере
								$qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$k[1].'" ORDER BY `id` DESC LIMIT 1'));
								if(isset($qlst['id']) && $qlst['vals'] != 'win' && $qlst['vals'] != 'end' && $qlst['vals'] != 'bad') {
									$go1 = 0;
									$txt .= '<br><b><font color=red>Что-то не так, Вы уже взяли данное задание...</font>';
									$pg = $ta['page'];	
								}
							}elseif($k[0]=='quest_only_one') {
								//Квест можно выполнять только один раз
								$qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$k[1].'" ORDER BY `id` DESC LIMIT 1'));
								if(isset($qlst['id']) && ($qlst['vals'] == 'win' || $qlst['vals'] == 'bad')) {
									$go1 = 0;
									$txt .= '<br><b><font color=red>Что-то не так, Вы уже выполняли данное задание...</font>';
									$pg = $ta['page'];	
								}
							}elseif($k[0]=='quest_now') {
								//Квест должен быть взят
								$qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$k[1].'" ORDER BY `id` DESC LIMIT 1'));
								if(isset($qlst['id']) && $qlst['vals'] != 'win' && $qlst['vals'] != 'end' && $qlst['vals'] != 'bad'){}else{
									$go1 = 0;
									$txt .= '<br><b><font color=red>Что-то не так, требуется взять задание...</font>';
									$pg = $ta['page'];	
								}
							}elseif($k[0]=='tr_itm') {
								//Квест требует предмет
								$qlst = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "'.$k[1].'" AND `inOdet` = 0 AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if($qlst[0] < $k[2]){
									$go1 = 0;
									$itmqs = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$k[1].'" LIMIT 1'));
									if(isset($itmqs['id'])) {
										$txt .= '<br><b><font color=red>Требуется предмет &quot;'.$itmqs['name'].'&quot; (x'.$k[2].').</font>';
									}
									$pg = $ta['page'];	
								}
							}elseif($k[0]=='tr_itmodet') {
								//Квест требует предмет
								$k[1] = str_replace(',','" OR `item_id` = "',$k[1]);
								$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "'.$k[1].'") AND `inOdet` > 0 AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if(!isset($qlst['id'])){
									$go1 = 0;
									$itmqs = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$k[1].'" LIMIT 1'));
									if(isset($itmqs['id'])) {
										$txt .= '<br><b><font color=red>Требуется предмет &quot;'.$itmqs['name'].'&quot;.</font>';
									}
									$pg = $ta['page'];	
								}
							}elseif($k[0]=='tr_noitmodet') {
								//Квест требует предмет
								$k[1] = str_replace(',','" OR `item_id` = "',$k[1]);
								$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "'.$k[1].'") AND `inOdet` > 0 AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if(isset($qlst['id'])){
									$go1 = 0;
									$itmqs = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$k[1].'" LIMIT 1'));
									if(isset($itmqs['id'])) {
										$txt .= '<br><b><font color=red>У вас уже есть требуемый предмет &quot;'.$itmqs['name'].'&quot;.</font>';
									}
									$pg = $ta['page'];	
								}
							}elseif($k[0]=='tr_noitm') {
								//Квест требует предмет
								$k[1] = str_replace(',','" OR `item_id` = "',$k[1]);
								$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "'.$k[1].'") AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if(isset($qlst['id'])){
									$go1 = 0;
									$itmqs = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$k[1].'" LIMIT 1'));
									if(isset($itmqs['id'])) {
										$txt .= '<br><b><font color=red>У вас уже есть требуемый предмет &quot;'.$itmqs['name'].'&quot;.</font>';
									}
									$pg = $ta['page'];	
								}
							}elseif($k[0]=='del_itm') {
								//Квест удаляет предмет
								$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "'.$k[1].'" AND `inOdet` = 0 AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if(isset($qlst['id'])){
									$itmqs = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$k[1].'" LIMIT 1'));
									if(isset($itmqs['id'])) {
										if(mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$qlst['id'].'" LIMIT 1')) {
											$txt .= '<br><b><font color=red>Вы отдали &quot;'.$itmqs['name'].'&quot;</font>';
										}
									}
									$pg = $ta['page'];	
								}
							} elseif( $k[0] == 'needRep' ) {
								// разговор требует репутации в пещере.
								$temp = explode(',', $k[1]);
								$needRep = array('city' => $temp[0], 'rep' => (int)$temp[1]); 
								if( isset($needRep) ) { # Проверяем репутацию. 
									if($u->rep['rep'.$needRep['city']] < $needRep['rep'] ) {
										$swapActStatus = 0;
										$go1 = 0;
										$txt = '<font color=red>Я тебя раньше здесь не видел, уходи прочь негодник!</font>';
										$pg = $ta['page'];
									}
								}
							}
							$i++;
						}
					}
					if(isset($act[1]) && $go1 == 1){
						$act1 = explode('=',$act[1]);
						$act0 = explode('=',$act[0]);
						if( $act0[0] == 'fileqst' ) {
							if(file_exists('_incl_data/class/quest/'.htmlspecialchars($act0[1]).'.php')) {
								include('_incl_data/class/quest/'.htmlspecialchars($act0[1]).'.php');
							}else{
								$txt .= '<br><b><font color=red>Квест не найден в списках NPS...</b></font>';
							}
							$pg = $act[1];
						}elseif( $act[0]=='dialog_act_update') {
							$act33 = $this->dialog_act_update($act[2]);
							if( $act33[0] == false ) {
								if( $act33[1] == '' ) {
									$txt .= '<br><b><font color=red>Что-то пошло не так...</b></font>';
								}else{
									$txt .= '<br><b><font color=red>'.$act33[1].'</font></b>';
								}
							}else{
								$txt .= '<br><b><font color=red>'.$act33[1].'</font></b>';
							}
							$pg = $act[1];
						}elseif( $act[0]=='quest_act' ) {
							$txt .= '<br><b><font color=red>Вы получили новое задание.</b></font>';
							mysql_query('INSERT INTO `dialog_act` (
								`uid`,`city`,`time`,`var`,`val`,`btl_bot`,`itms`,`now`,`max`,`info`
							) VALUES (
								"'.$u->info['id'].'","'.$u->info['city'].'","'.time().'","'.mysql_real_escape_string($act1[0]).'","'.mysql_real_escape_string($act1[1]).'"
								,"'.mysql_real_escape_string($act1[3]).'","'.mysql_real_escape_string($act1[4]).'","'.mysql_real_escape_string($act1[5]).'","'.mysql_real_escape_string($act1[6]).'","'.mysql_real_escape_string($act1[7]).'"
							)');
							$pg = $act1[2];
							if( $act1[8] != 0 ) {
								//Выдаем предмет для квеста
								$itmb = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$act1[8].'" LIMIT 1'));
								if(isset($itmb['id'])) {
									$u->addItem($act1[8],$u->info['id'],'|nodelete=1');
									$txt .= '<br><b><font color=red>Вы получили предмет &quot;'.$itmb['name'].'&quot;</font></b>';
								}
							}
						}elseif( $act[0]=='buyitm' ) {
							$itmb = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$act1[0].'" LIMIT 1'));
							if(isset($itmb['id'])) {
								if( $u->info['money'] < $act1[1] ) {
									$txt .= '<br><b><font color=red>Вам не хватает денег для покупки &quot;'.$itmb['name'].'&quot;, требуется '.$act1[1].' кр.';
								}else{
									$act1dt = '';
									$txt .= '<br><b><font color=red>Вы успешно приобрели &quot;'.$itmb['name'].'&quot;';
									if($act1[1] > 0) {
										$txt .= ' за '.$act1[1].' кр.';
										$u->info['money'] -= $act1[1];
										mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									}
									if($act1[2] > 0) {
										$txt .= ' за '.$act1[2].' екр.';
									}
									if( $act1[3] > 0 ) {
										$txt .= ' на срок '.$u->timeOut($act1[3]).'';
										$act1dt .= '|srok='.$act1[3].'';
									}
									$txt .= '</font></b>';
									
									$u->addItem($itmb['id'],$u->info['id'],$act1dt);
									$pg = $act1[4];
								}
							}else{
								$txt .= '<br><b><font color=red>Неудалось приобрести предмет...</font></b>';
								$pg = 1;	
							}
						}elseif( $act1[0]=='quest' ) {
							$pg = $act1[1];	
							$act2 = explode('=',$act[1]);													
							if($act2[0] > 0 && $q->testGood($act2[0])==1) {
								//выдаем квест
								if($act2[1]!='0') {
									//Выдача предмета
									$ic1 = 0;
									$act21 = explode(',',$act2[1]);
									while($ic1 < count($act21)) {
										$act3 = explode('-',$act21[$ic1]);
										$itmqs = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$act3[0].'" LIMIT 1'));
										if(isset($itmqs['id'])) {
											if($act3[2] > 1) {
												//несколько
												$txt .= '<br><b><font color=red>Вы получили предмет &quot;'.$itmqs['name'].'&quot; (x'.$act3[2].' шт.).</font></b>';
											}else{
												//один
												$txt .= '<br><b><font color=red>Вы получили квестовый предмет &quot;'.$itmqs['name'].'&quot;.</font></b>';
											}
											$ic2 = 1;
											while($ic2 <= $act3[2]) {
												$u->addItem($itmqs['id'],$u->info['id'],'|quest_item=1',array('del'=>$act3[3]));
												$ic2++;
											}
										}
										$ic1++;
									}
								}
								$q->startq($act2[0]);
								$txt .= '<br><b><font color=red>'.$u->error.'.</font></b>';
								$u->error = '';
							} else {
								$txt .= '<br><b><font color=red>Не удалось получить задание, не соответствуют условия получения...</font></b>';	
							}
						} elseif($act[0]=='go' && $go1 == 1) {
							$pg = $act[1];	
						} elseif($act[0]=='atackbot' && $go1 == 1) {
							//Нападение на монстра
							if( round((int)$act[1]) > 0 ) {
								$btl_id = 0;
								//$expB = -77.77;
								$expB = 0;
								$btl = array(
									'players'=>'',
									'timeout'=>180,
									'type'=>0,
									'invis'=>0,
									'noinc'=>0,
									'travmChance'=>0,
									'typeBattle'=>0,
									'addExp'=>$expB,
									'money'=>0
								);
				
								$ins = mysql_query('INSERT INTO `battle` (`dungeon`,`dn_id`,`x`,`y`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`) VALUES (
																	"'.$d->info['id2'].'",
																	"'.$d->info['id'].'",
																	"'.$u->info['x'].'",
																	"'.$u->info['y'].'",
																	"'.$u->info['city'].'",
																	"'.time().'",
																	"'.$btl['players'].'",
																	"'.$btl['timeout'].'",
																	"'.$btl['type'].'",
																	"'.$btl['invis'].'",
																	"'.$btl['noinc'].'",
																	"'.$btl['travmChance'].'",
																	"'.$btl['typeBattle'].'",
																	"'.$btl['addExp'].'",
																	"'.$btl['money'].'")');
								$btl_id = mysql_insert_id();
								
								if($btl_id > 0) {
									//Добавляем ботов
									$k = $u->addNewbot(round((int)$act[1]),NULL,NULL,array());
									mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `x`="'.$u->info['x'].'",`y`="'.$u->info['y'].'",`team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
									mysql_query('UPDATE `users` SET `battle` = "'.$btl_id.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `team` = "1" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	
								}
								header('location: main.php');
							}else{
								echo '<b><font title="'.$act[1].'" color=red>Поединок почему-то не начался...</font></b>';
							}
							die();
						} elseif($act[0]=='goroom' && $go1 == 1) {
							$u->info['room'] = $act[1];
							mysql_query('UPDATE `users` SET `room` = "'.$u->info['room'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							header('location: main.php');
							die();	
						} elseif($act[0]=='transfer2' && $go1 == 1) {
							//Обменник тыквоголового
							$txt = '';
							
							$xtik = 0; //Требуется тыкв
							
							if( $act[1] == 1 && true == false ) {
								$txt .= 'Обмен 1...';
								$xtik = 10;
								$itik = 4465;
								
							}elseif( $act[1] == 2 ) {
								$txt .= 'Обмен 2...';
								$xtik = 15;
								$itik = 2143;
								
							}elseif( $act[1] == 3 ) {
								$txt .= 'Обмен 3...';
								$xtik = 15;
								$itik = 2144;
								
							}elseif( $act[1] == 4 && true == false ) {
								$txt .= 'Обмен 4...';
								$xtik = 6;
								//арт воина
								$itik = -1;
								
							}elseif( $act[1] == 5 && true == false ) {
								$txt .= 'Обмен 5...';
								$xtik = 6;
								//арт мага
								$itik = -2;
								
							}elseif( $act[1] == 6 ) {
								$txt .= 'Обмен 6...';
								$xtik = 50;
								//значок 1
								$itik = -3;
								
							}elseif( $act[1] == 7 ) {
								$txt .= 'Обмен 7...';
								$xtik = 70;
								//значок 2
								$itik = -4;
								
							}else{
								$txt .= 'Тыквоголовый не меняет этот хлам...';
							}
							
							$txt = 'Обменник начнет свою работу 3 ноября';
							
							$itms = array();
							$sp = mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "4504" AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" LIMIT 100');
							while($pl = mysql_fetch_array($sp))	{
								$itms[$pl['item_id']]++;	
							}
		
							$t = $this->trnObj($itms[4504],$xtik);
							if($t[0] > 0) {
								// $t[0] - сколько предметов награды даем,  $cn[$i]['add'][0] - item_id предмета награды
								//удаляем ингридиенты
	
								$gdtik = 1;
	
								if( $gdtik == 1 ) {
									$upd = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "4504" AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" ORDER BY `inGroup` ASC LIMIT '.$xtik.'');
									//echo 'UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "4504" AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" LIMIT '.$t[0].'';
									if($upd) {
										if( $itik > 0 ) {
											//Выдаем предметы
											$u->addItem($itik,$u->info['id'],'|nosale=1|srok='.(7*86400).'',NULL,1);
											$itm_nm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$itik.'" LIMIT 1'));
											$txt = 'Вы получили предмет &quot;'.$itm_nm['name'].'&quot;';
										}else{
											//Что-то уникальное
											if( $itik == -1 ) {
												//Артефакт воин
												$arts_1 = array();
												$arts_lvl = $u->info['level'];
												if( $arts_lvl < 4 ) {
													$arts_lvl = 4;
												}elseif( $arts_lvl > 10 ) {
													$arts_lvl = 10;
												}
												$sp1 = mysql_query('SELECT `items_id` FROM `items_main_data` WHERE `data` LIKE "%|art=%" AND `data` LIKE "%tr_lvl='.$arts_lvl.'%" AND `data` NOT LIKE "%|tr_s5=%" AND `data` NOT LIKE "%|add_s6=%" AND `data` NOT LIKE "%|tr_s6=%"');
												while( $pl1 = mysql_fetch_array($sp1) ) {
													$arts_1[] = $pl1['items_id'];
												}
												$arts_1 = $arts_1[rand(0,count($arts_1)-1)];
												if( $arts_1 > 0 ) {
													$u->addItem($arts_1,$u->info['id'],'|sroknext=1|nosale=1|sleep_moroz=1|srok='.(86400/2).'',NULL,100);
												}
												$itm_nm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$arts_1.'" LIMIT 1'));
											
												$txt = 'Вы получили артефакт для воина &quot;'.$itm_nm['name'].'&quot; на срок 12 часов.';
											}elseif( $itik == -2 ) {
												//Артефакт мага
												$arts_1 = array();
												$arts_lvl = $u->info['level'];
												if( $arts_lvl < 4 ) {
													$arts_lvl = 4;
												}elseif( $arts_lvl > 10 ) {
													$arts_lvl = 10;
												}
												$sp1 = mysql_query('SELECT `items_id` FROM `items_main_data` WHERE `data` LIKE "%|art=%" AND `data` LIKE "%tr_lvl='.$arts_lvl.'%" AND ( `data` LIKE "%|tr_s6=%" OR `data` LIKE "%|add_s6=%")');
												while( $pl1 = mysql_fetch_array($sp1) ) {
													$arts_1[] = $pl1['items_id'];
												}
												$arts_1 = $arts_1[rand(0,count($arts_1)-1)];
												if( $arts_1 > 0 ) {
													$u->addItem($arts_1,$u->info['id'],'|sroknext=1|nosale=1|sleep_moroz=1|srok='.(86400/2).'',NULL,100);
												}
												$itm_nm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$arts_1.'" LIMIT 1'));
											
												$txt = 'Вы получили артефакт для мага &quot;'.$itm_nm['name'].'&quot; на срок 12 часов.';
											}elseif( $itik == -3 ) {
												//Значок +1
												mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.$u->info['id'].'" AND (`img` = "helloween_2014m1.gif" OR `img` = "helloween_2014m2.gif")');
												mysql_query('INSERT INTO `users_ico` (`uid`,`time`,`text`,`img`,`endTime`,`bonus`,`type`,`x`) VALUES (
													"'.$u->info['id'].'",
													"'.time().'",
													"<b>Хэллоуин</b>`'.date('Y').'<br>Обыкновенный собиратель тыкв!",
													"helloween_2014m1.gif",
													"'.(time()+86400*365).'",
													"add_exp=1",
													"1",
													"1"
												)');
												$txt = 'Вы получили значок &quot;Хэллоуин`'.date('Y').' Обыкновенный&quot;';
											}elseif( $itik == -4 ) {
												//Значок +5	
												mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.$u->info['id'].'" AND (`img` = "helloween_2014m1.gif" OR `img` = "helloween_2014m2.gif")');
												mysql_query('INSERT INTO `users_ico` (`uid`,`time`,`text`,`img`,`endTime`,`bonus`,`type`,`x`) VALUES (
													"'.$u->info['id'].'",
													"'.time().'",
													"<b>Хэллоуин</b>`'.date('Y').'<br>Лучший собиратель тыкв!",
													"helloween_2014m2.gif",
													"'.(time()+86400*365).'",
													"add_exp=5",
													"1",
													"1"
												)');
												$txt = 'Вы получили значок &quot;Хэллоуин`'.date('Y').' Лучший&quot;';
											}
										}
									}
								}else{
									$txt = 'Неудалось совершить обмен...';
								}
							}else{
								$txt = 'Недостаточно тыкв для обмена...';
							}
							
							if($txt!=''){
								$txt = '<br><font color="red">'.$txt.'</font>';	
							}
							$pg = 3;
						} elseif($act[0]=='transfer1' && $go1 == 1) {
							//Меняем гайки и прочий мусор из канализации на жетоны
							//ИХ больше у тебя нету... Неси еще, Луке нужно больше ИХ!
							$pg = $act[1]; $itms = array();
							$sp = mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "1002" OR `item_id` = "1003" OR `item_id` = "1004" OR `item_id` = "1005" OR (`item_id` >= "1009" AND`item_id` <= "1014")) AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" LIMIT 250');
							while($pl = mysql_fetch_array($sp)) {
								$itms[$pl['item_id']]++;	
							}
							//Предметы
							$cn = array( 
								0 => 3, //кол-во классификаций
								1 => array(
									//гайка
									1     => array('n'=>'Гайка',0=>1002,1=>3,7=>9,8=>15),
									//болт
									2     => array('n'=>'Болт',0=>1003,1=>1,7=>3,8=>5),
									//вентиль
									3     => array('n'=>'Вентиль',0=>1005,1=>(1/3),7=>1,8=>2),
									'add' => array(0=>1006,1=>'Жетон') //предмет вознаграждения, бронзовый жетон					
									), //бронза
								2 => array(
									//гайка
									1     => array('n'=>'Чистая гайка',0=>1009,1=>3,7=>9,8=>15),
									//болт
									2     => array('n'=>'Длинный Болт',0=>1010,1=>1,7=>3,8=>5),
									//вентиль
									3     => array('n'=>'Чистый вентиль',0=>1011,1=>(1/3),7=>1,8=>2),
									'add' => array(0=>1007,1=>'Серебряный жетон') //предмет вознаграждения, бронзовый жетон					
								), //серебро
								3 => array(
									//гайка
									1     => array('n'=>'Гайка с Резьбой',0=>1012,1=>3,7=>9,8=>15),
									//болт
									2     => array('n'=>'Нужный болт',0=>1013,1=>1,7=>3,8=>5),
									//вентиль
									3     => array('n'=>'Рабочий вентиль',0=>1014,1=>(1/3),7=>1,8=>2),
									'add' => array(0=>1008,1=>'Золотой жетон') //предмет вознаграждения, бронзовый жетон		
								) //золото
								);
								
							$i = 1;
							while($i<=$cn[0])
							{
								$j = 1;
								while($j<count($cn[$i][$j]))
								{
									
									$t = $cn[$i][$j];
									$t = $this->trnObj($itms[$cn[$i][$j][0]],$this->objLevel($cn[$i][$j],$u->info['level']));
									if($t[0]>0)
									{
										// $t[0] - сколько предметов награды даем,  $cn[$i]['add'][0] - item_id предмета награды
										//удаляем ингридиенты
										$upd = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "'.$cn[$i][$j][0].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" LIMIT '.($t[0]*$this->objLevel($cn[$i][$j],$u->info['level'])).'');
										if($upd)
										{
											$data = '|frompisher='.$d->info['id2'];
											$e = 1;
											while($e<=$t[0])
											{
												$u->addItem($cn[$i]['add'][0],$u->info['id'],$data);
												$e++;
											}
											$txt .= $cn[$i][$j]['n'].' x'.($t[0]*$this->objLevel($cn[$i][$j],$u->info['level'])).' = '.$cn[$i]['add'][1].' x'.$t[0].'<br>';
										}else{
											$txt .= 'Не удалось обменять предмет &quot;'.$cn[$i][$j]['n'].'&quot;, что-то не так ...<br>';	
										}
									}
									$j++;	
								}
								$i++;
							}

							if($txt!='') {
								$txt = '<br><font color="red">'.$txt.'</font>';	
							}
						} elseif($act[0]=='transfer3' && $go1 == 1) {
							//ИХ больше у тебя нету... Неси еще, Луке нужно больше ИХ!
							//Серебро на золото, 3 к 1
							$pg = $act[1]; $itms = array();
							$sp = mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "1007" AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" LIMIT 250');
							while($pl = mysql_fetch_array($sp)) {
								$itms[$pl['item_id']]++;	
							}
							//Предметы
							$cn = array( 
								0 => 1, //кол-во классификаций
								1 => array(
									//гайка
									1     => array('n'=>'Серебряный жетон',0=>1007,1=>3,7=>3,8=>3),
									'add' => array(0=>1008,1=>'Золотой жетон') //предмет вознаграждения, бронзовый жетон					
									)
								);
								
							$i = 1;
							while($i<=$cn[0])
							{
								$j = 1;
								while($j<count($cn[$i][$j]))
								{
									
									$t = $cn[$i][$j];
									$t = $this->trnObj($itms[$cn[$i][$j][0]],$this->objLevel($cn[$i][$j],$u->info['level']));
									if($t[0]>0)
									{
										// $t[0] - сколько предметов награды даем,  $cn[$i]['add'][0] - item_id предмета награды
										//удаляем ингридиенты
										$upd = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "'.$cn[$i][$j][0].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" LIMIT '.($t[0]*$this->objLevel($cn[$i][$j],$u->info['level'])).'');
										if($upd)
										{
											$data = '|frompisher='.$d->info['id2'];
											$e = 1;
											while($e<=$t[0])
											{
												$u->addItem($cn[$i]['add'][0],$u->info['id'],$data);
												$e++;
											}
											$txt .= $cn[$i][$j]['n'].' x'.($t[0]*$this->objLevel($cn[$i][$j],$u->info['level'])).' = '.$cn[$i]['add'][1].' x'.$t[0].'<br>';
										}else{
											$txt .= 'Не удалось обменять предмет &quot;'.$cn[$i][$j]['n'].'&quot;, что-то не так ...<br>';	
										}
									}
									$j++;	
								}
								$i++;
							}

							if($txt!='') {
								$txt = '<br><font color="red">'.$txt.'</font>';	
							}
						} elseif($act[0]=='transfer4' && $go1 == 1) {
							//ИХ больше у тебя нету... Неси еще, мне нужно больше ИХ!
							//Засоры к золоту, 20 к 1
							$pg = $act[1]; $itms = array();
							$sp = mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "4728" AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" LIMIT 250');
							while($pl = mysql_fetch_array($sp)) {
								$itms[$pl['item_id']]++;	
							}
							//Предметы
							$cn = array( 
								0 => 1, //кол-во классификаций
								1 => array(
									//гайка
									1     => array('n'=>'Засоры',0=>4728,1=>20,7=>20,8=>20),
									'add' => array(0=>1008,1=>'Золотой жетон') //предмет вознаграждения, бронзовый жетон					
									)
								);
								
							$i = 1;
							while($i<=$cn[0])
							{
								$j = 1;
								while($j<count($cn[$i][$j]))
								{
									
									$t = $cn[$i][$j];
									$t = $this->trnObj($itms[$cn[$i][$j][0]],$this->objLevel($cn[$i][$j],$u->info['level']));
									if($t[0]>0)
									{
										// $t[0] - сколько предметов награды даем,  $cn[$i]['add'][0] - item_id предмета награды
										//удаляем ингридиенты
										$upd = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "'.$cn[$i][$j][0].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" LIMIT '.($t[0]*$this->objLevel($cn[$i][$j],$u->info['level'])).'');
										if($upd)
										{
											$data = '|frompisher='.$d->info['id2'];
											$e = 1;
											while($e<=$t[0])
											{
												$u->addItem($cn[$i]['add'][0],$u->info['id'],$data);
												$e++;
											}
											$txt .= $cn[$i][$j]['n'].' x'.($t[0]*$this->objLevel($cn[$i][$j],$u->info['level'])).' = '.$cn[$i]['add'][1].' x'.$t[0].'<br>';
										}else{
											$txt .= 'Не удалось обменять предмет &quot;'.$cn[$i][$j]['n'].'&quot;, что-то не так ...<br>';	
										}
									}
									$j++;	
								}
								$i++;
							}
							
							if( $txt == '' ) {
								$txt = 'Необходимо минимум 20 засоров для обмена.';
							}
							
							if($txt!='') {
								$txt = '<br><font color="red">'.$txt.'</font>';	
							}
						} elseif( $act[0] == 'swapItem' && $go1 == 1) {
							$txt = '';
							$swapItem = $act[1];
							$swapError = $act[2];
							$swapTrue = $act[3];
							# Обмениваем предметы у Забытого (Мастерская Забытых)
							if( !isset($swapActStatus) ) $swapActStatus = 1; # Все окей, проблем нет!
							$swapAct = array();  # Собираем данные об обмене.
							$temp = explode ("\\", $swapItem);
							foreach ( $temp as $t ) {
								$t = explode('=', $t); 
								if( $t[0] == 'tr'  ) { // Нужны для обмена
									$temp2 = explode(',', $t[1]); $tr_items = array();
									foreach( $temp2 as $t2 ) {
										$temp3 = explode('x', $t2);
										if( !isset($temp3[1]) ) $temp3[1] = 1; // Если количество не задано, задаем 1ед.
										if( isset($temp3[2]) && $temp3[2]=='del' ) $temp3[2] = true; else $temp3[2]=false; // Удаляем даже при неудачной попытке совершить обмен!
										$tr_items[] = array('item_id' => $temp3[0], 'colvo' => (int)$temp3[1], 'delete' => $temp3[2]);
									}
									$swapAct['tr'] = $tr_items;
									
								} elseif( $t[0] == 'needQuest' ){ // Если нужен какой-то квест для приобретения. 
									
								} elseif( $t[0] == 'needRep' ){ // Если нужна репутация в подземельи 
									$temp2 = explode(',', $t[1]); $need_rep = array();
									$need_rep[] = array('city' => $temp2[0], 'rep' => (int)$temp2[1]);
									
									$swapAct['need_rep'] = $need_rep;
									
								} elseif( $t[0] == 'add' ){ // Какие предметы даем.
									$temp2 = explode(',', $t[1]); $add_items = array();
									foreach($temp2 as $t2) {
										$temp3 = explode('x', $t2);
										if( !isset($temp3[1]) ) $temp3[1] = 1; // Если количество не задано, задаем 1ед.
										$add_items[] = array('item_id' => $temp3[0], 'colvo' => (int)$temp3[1]);
									}
									$swapAct['add'] = $add_items; 
								} elseif( $t[0] == 'uses' ){ // Если нужна репутация в подземельи
									$swapAct['uses'] = $t[1];
								}
							} // Цикл обработки данных разговора.
							unset($temp,$temp2,$temp3,$t2,$tr_items,$add_items);
							
							if( $swapActStatus == 0 ) {
								$txt = 'Я тебя раньше здесь не видел, уходи прочь негодник!';
								$pg = $swapError; 
							} elseif ( isset($swapAct['need_rep']) ) { # Проверяем репутацию.
								foreach($swapAct['need_rep'] as $rep) { # Если несколько репутаций
									if($u->rep['rep'.$rep['city']] < $rep['rep'] ) {
										$swapActStatus = 0;
										$txt = 'Я тебя раньше здесь не видел, уходи прочь негодник!';
										$pg = $swapError; 
									}
								}
							}

							if( isset($swapAct['uses']) ) { # Проверяем количество раз использований.
								#$swapAct['uses'] = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `room` = "'.$u->info['room'].'" AND `vals` = "masteryUses'.$u->info['dnow'].'" ORDER BY `id` DESC LIMIT '.$swapAct['uses'] .''));
								if( isset($swapAct['uses']['id']) ) {
									$swapActStatus = 0;
									$txt = 'Кто-то уже побывал здесь и испортил кузницу, ничего не получится...';
									$pg = 0; 
								}
							} 

							if( isset($swapAct['tr']) AND $swapActStatus == 1 ) { # Проверяем необходимые предметы.
								foreach($swapAct['tr'] as $item) { # Если несколько предметов. 
									$item_info = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$item['item_id'].'" LIMIT 1'));
									if( isset($item_info['id']) ) {
										$query = mysql_query('SELECT * FROM `items_users` WHERE `item_id` = "'.$item['item_id'].'" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.$item['colvo'].'');
										$j=0;
										while( $t = mysql_fetch_array($query) ) {
											$j++;
										}
										if( $j < $item['colvo'] ) { 
											$txt .= 'У вас недостаточно предметов "'.$item_info['name'].'"!  ('.$item['item_id'].')<br/>';
											$swapActStatus = 3;
											$pg = $swapError;
										} elseif( $swapActStatus != 3) {
											$swapActStatus = 2;
										}
									}
								}
							}

							if( isset($swapAct['tr']) AND $swapActStatus == 3 ) { # Забираем предметы del==true.
								$mess = 'Израсходованы ресурсы: ';
								$qsw = 0;
								foreach($swapAct['tr'] as $item) { # Если несколько предметов.
									if ( $item['delete'] == true ){
										$query = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id` = "'.$item['item_id'].'" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inShop` = "0" AND `inOdet` = "0" ORDER BY inGroup DESC LIMIT '.$item['colvo'].''); 
										if($query){
											$item_info = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$item['item_id'].'" LIMIT 1'));
											if( $qsw > 0 ) $mess .=', ';
											$mess .= '"'.$item_info['name'].'"';
											if( $item['colvo'] > 1 ) $mess .= '('.$item['item_id'].'шт)';
											$qsw++;	
										}
									}
								}
								$mess .= '.<br/>';
								if( $qsw > 0 ) $txt .= $mess;
							} elseif( isset($swapAct['tr']) AND $swapActStatus == 2 ) { # Забираем предметы все. 
								$mess = 'Израсходованы ресурсы: ';
								$qsw = 0;
								foreach($swapAct['tr'] as $item) {
									$query = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id` = "'.$item['item_id'].'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" ORDER BY inGroup DESC LIMIT '.$item['colvo'].'');
									if($query) {
										$item_info = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$item['item_id'].'" LIMIT 1'));
										if( $qsw > 0 ) $mess .=', ';
										$mess .= '"'.$item_info['name'].'"';
										if( $item['colvo'] > 1 ) $mess .= '('.$item['item_id'].'шт)';
										$qsw++;
									}
								}
								$mess .= '.<br/>';
								if( $qsw > 0 ) $txt .= $mess;
							}
							
							if( isset($swapAct['add']) AND $swapActStatus == 2 ) { # Выдаем предметы. 
								foreach($swapAct['add'] as $item) {
									$qsw = 0;
									while($qsw < $item['colvo']) {
										$txt .= '<br/>Вы получили предмет';
										$u->addItem($item['item_id'],$u->info['id']);
										$qsw++;
									}
								}
								
								mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`,`vals`) VALUES ("'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'", "","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'", "masteryUses'.$u->info['dnow'].'")');
								$pg = $swapTrue;
							}
							if( $txt != '' ) {
								$txt = '<br><font color="red">'.$txt.'</font>';	
							}
						}
					}
				}
			}
						
			if($this->info['tr_room']!=0 && $this->info['tr_room']!=$u->info['room'])
			{
				$go = 0;	
			}
						
			if($this->info['tr_dn']!=0) {
				//требует пещеру
				global $d;
				if($this->info['tr_dn']!=$d->info['id2'])
				{
					$go = 0;
				}elseif($this->info['x']!=0 || $this->info['y']!=0)
				{
					if($d->testLike($u->info['x'],$u->info['y'],$this->info['x'],$this->info['y'])!=1)
					{						
						$go = 0;
					}
					//если бот погиб
					$dbot = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_bots` WHERE `dn` = "'.$u->info['dnow'].'" AND `x` = "'.$this->info['x'].'" AND `y` = "'.$this->info['y'].'" LIMIT 1'));
					if(!isset($dbot['id2']))
					{
						$go = 0;
					}
				}
			}

			if($go==1) {
				//dlg_nps:=:3=7
				//квест (с наградой)
				$qs_sp = mysql_query('SELECT `id`,`act_date` FROM `quests` WHERE `act_date` LIKE "%dlg_nps:=:'.$this->info['id'].'='.$pg.'=1=e%" LIMIT 1');
				while($qs_pl = mysql_fetch_array($qs_sp)) {
					$q->endq($qs_pl['id'],'win');
					$gsex = explode('=e'.$this->info['id'].$pg.'=',$qs_pl['act_date']);
					if($gsex[1] > 0) {
						//выдаем добавочный квест
						$q->startq($gsex[1]);
						$txt .= '<br><font color="red"><b>Задание изменилось</b></font>';
					}
				}
				
				//квест (без наградой)
				$qs_sp = mysql_query('SELECT `id`,`act_date` FROM `quests` WHERE `act_date` LIKE "%dlg_nps:=:'.$this->info['id'].'='.$pg.'=0=e%" LIMIT 1');
				while($qs_pl = mysql_fetch_array($qs_sp)) {
					$q->endq($qs_pl['id'],'end');
					$gsex = explode('=e'.$this->info['id'].$pg.'=',$qs_pl['act_date']);
					if($gsex[1] > 0) {
						//выдаем добавочный квест $gsex[1]
						$q->startq($gsex[1]);
						$txt .= '<br><font color="red"><b>Задание изменилось</b></font>';
					}
				}
				
				$this->title = $this->info['text'];
				$this->youInfo = $u->getInfoPers($u->info['id'],1);
				$this->youInfo = $this->youInfo[0];
				$this->botInfo = $this->infoBot($this->info['bot_id']);
				//Диалог
				$qpl = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_dlg` WHERE `type` = "1" AND `qid` = "0" AND `id_dg` = "'.$this->info['id'].'" AND `page` = "'.((int)$pg).'" LIMIT 1'));
				if( !isset($qpl['id']) ) {
					if($txt == '' ){
						$qpl['text'] = 'Диалог не найден ...';	
					} else {
						$qpl['text'] = $txt.' (<a href="main.php?rnd='.$code.'">уйти</a>)';
						$txt = '';
					}
				} else {
					$this->pg = $qpl['id'];
				}
				
				if( $u->info['admin'] > 0 ) {
					if( isset($_GET['add_new_qid']) ) {
						mysql_query('INSERT INTO `dungeon_dlg` (`type`,`qid`,`id_dg`,`text`) VALUES ("0","'.$qpl['id'].'","'.$this->info['id'].'","<i>Новый вариант ответа</i>")');
					}
				}
				
				//Варианты ответа
				$a = '';
				$sp = mysql_query('SELECT * FROM `dungeon_dlg` WHERE `type` = "0" AND `qid` = "'.$qpl['id'].'" AND `id_dg` = "'.$this->info['id'].'" ORDER BY `sort` DESC LIMIT 25');
				while($pl = mysql_fetch_array($sp))
				{
					$pl['action'] = $this->ltr($pl['action']);
					$act = explode('|',$pl['action']);
					if(isset($act[1]))
					{
						$pl['action'] = 'main.php?talk='.$this->info['id'].'&act='.$pl['id'].'&rnd='.$code;	
					}
					$go1 = '';
					if($pl['tr'] != '') {
						$i = 0;
						$x = explode('|',$pl['tr']);
						while($i < count($x)) {
							//Требования
							$k = explode('=',$x[$i]);								
							if($k[0]=='data') {
								$date = explode('-',$k[1]);
								$dd1 = $date[0];
								$mm1 = $date[1];
								$dd2 = $date[2];
								$mm2 = $date[3];
								$date1 = strtotime($dd1.'-'.$mm1.'-'.date('Y'));
								$date2 = strtotime($dd2.'-'.$mm2.'-'.date('Y'));
								
								if( $date1 > time() || $date2 < time() ) {
									$go1 = 'delete';
									$i = count($x);
								}
								
							}elseif($k[0]=='diact') {
								//Действия
								//user_id # all # all # lukaqst1 # -1
								if($this->quest_act($k[1])==false) {
									$go1 = 'delete';
									$i = count($x);
								}
								
							}elseif($k[0]=='quest_end') {
								//Квест можно выполнять несколько раз в текущей пещере
								$qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$k[1].'" ORDER BY `id` DESC LIMIT 1'));
								if(isset($qlst['id']) && $qlst['vals'] != 'win' && $qlst['vals'] != 'end' && $qlst['vals'] != 'bad') {
									//$go1 .= "Вы уже взяли данное задание\n";
									$go1 = 'delete';
									$i = count($x);
								}
							}elseif($k[0]=='quest_only_one') {
								//Квест можно выполнять только один раз
								$qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$k[1].'" ORDER BY `id` DESC LIMIT 1'));
								if(isset($qlst['id']) && ($qlst['vals'] == 'win' || $qlst['vals'] == 'bad')) {
									//$go1 .= "Вы уже взяли данное задание\n";
									$go1 = 'delete';
									$i = count($x);
								}
							}elseif($k[0]=='quest_now') {
								//Квест должен быть взят
								$qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$k[1].'" ORDER BY `id` DESC LIMIT 1'));
								if(isset($qlst['id']) && $qlst['vals'] != 'win' && $qlst['vals'] != 'end' && $qlst['vals'] != 'bad'){}else{
									$go1 = 'delete';
									$i = count($x);
								}
							}elseif($k[0]=='tr_itm') {
								//Квест требует предмет
								$qlst = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "'.$k[1].'" AND `inOdet` = 0 AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if($qlst[0] < $k[2]){
									$go1 = 'delete';
									$i = count($x);
								}
							}elseif($k[0]=='tr_itmodet') {
								//Квест требует предмет
								$k[1] = str_replace(',','" OR `item_id` = "',$k[1]);
								$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "'.$k[1].'") AND `inOdet` > 0 AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if(!isset($qlst['id'])){
									$go1 = 'delete';
									$i = count($x);
								}
							}elseif($k[0]=='tr_noitmodet') {
								//Квест требует предмет
								$k[1] = str_replace(',','" OR `item_id` = "',$k[1]);
								$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "'.$k[1].'") AND `inTransfer` = 0 AND `inOdet` > 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if(isset($qlst['id'])){
									$go1 = 'delete';
									$i = count($x);
								}
							}elseif($k[0]=='tr_noitm') {
								//Квест требует предмет
								$k[1] = str_replace(',','" OR `item_id` = "',$k[1]);
								$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "'.$k[1].'") AND `inTransfer` = 0 AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
								if(isset($qlst['id'])){
									$go1 = 'delete';
									$i = count($x);
								}
							}
							$i++;
						}
					}
					if($u->info['admin'] > 0) {
						$a .= '<small>(<a href="javascript:window.open(\'http://xcombats.com/quest_dlg_edit.php?pid='.$pl['id'].'\',\'winEdi1\',\'width=850,height=400,top=400,left=500,resizable=no,scrollbars=yes,status=no\');" title="Редактировать вариант ответа">ред.</a>)</small> &nbsp; ';
					}
					if($go1 == '') {
						$a .= '&bull; <a href="'.$pl['action'].'">'.$pl['text'].'</a><br>';
					}elseif($go1 == 'delete') {
						if( $u->info['admin'] > 0 ) {
							$a .= '&bull; <a style="color:#aeaeae" href="'.$pl['action'].'">'.$pl['text'].'</a><br>';
						}
					}else{
						$a .= '<font color="#9A9A9A">&bull; <b>'.$pl['text'].'</b></font> <small title="'.$go1.'"> <b style="cursor:help"><font color=red>[?]</font></b></small><br>';
					}
				}
				
				
				if($u->info['admin'] > 0) {
					$a .= '<small style="border-top:1px solid #BABABA;padding-top:5px;display:block;margin-top:5px;"><a href="main.php?talk='.((int)$_GET['talk']).'&act='.((int)$_GET['act']).'&add_new_qid=1">Добавить вариант ответа</a></small>';
				}
				
				$this->dText = $qpl['text'].'<br>'.$txt;
				$this->aText = $a;				
			}else{
				$this->aText = '<center>Диалог не доступен, вернуться <a href="main.php?rnd='.$code.'">назад</a><br></center>';		
			}
		}else{
			$this->aText = '<center>Диалог не найден, вернуться <a href="main.php?rnd='.$code.'">назад</a><br><font color="white">'.((int)$id).'</font></center>';	
		}
	}
	
	public function dialog_act_update($data) {
		global $u;
		$r = array(false,'');
		$x = explode('#',$data);
		$i = 0;
		while( $i < count($x) ) {
			$k = explode('=',$x[$i]);
			$var = $k[0];
			$val = $k[1];
			if( $var == 'take_item' ) {
				//Забираем предмет
				$itms = array();
				$sp = mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "'.mysql_real_escape_string($val).'" AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = "0" AND `inShop` = "0" LIMIT 100');
				while($pl = mysql_fetch_array($sp))	{
					$itms[$pl['item_id']]++;	
				}
						
				if($itms[$val] >= $k[2]) {
					//$u->deleteItemID($val,$u->info['id'],$k[2]);
					
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id` = "'.mysql_real_escape_string($val).'" AND `uid` = "'.$u->info['id'].'" AND `inShop` = 0 AND `inTransfer` = 0 AND (`delete` = "0" OR `delete` = "1000") AND `inOdet` = 0 LIMIT '.round($k[2]));
				}else{
					$i = count($x);
					$r[0] = false;
					$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$val.'" LIMIT 1'));
					$r[1] = 'У вас нет нужного предмета! Требуется &quot;'.$itm['name'].'&quot;';
					if( $k[2] > 1 ) {
						$r[1] .= ' ('.$k[2].' шт.)';
					}
				}
				//
			}elseif( $var == 'add_item' ) {
				$data_itm = '|frompisher=1';
				$i1 = 0;
				while( $i1 < $k[2] ) {
					if( $val == 4797 ) {
						//Повестка
						if( $u->info['level'] >= 4 && $u->info['level'] <= 7 ) {
							$val = 4797;
						}elseif( $u->info['level'] >= 8 && $u->info['level'] <= 9 ) {
							$val = 4798;
						}else{
							$val = 4799;
						}
					}
					$u->addItem($val,$u->info['id'],$data_itm);
					$i1++;
				}
			}elseif( $var == 'up' ) {
				$tqst = mysql_fetch_array(mysql_query('SELECT * FROM `dialog_act` WHERE `uid` = "'.$u->info['id'].'" AND `var` = "'.mysql_real_escape_string($val).'" ORDER BY `id` DESC LIMIT 1'));
				if(!isset($tqst['id'])) {
					mysql_query('INSERT INTO `dialog_act` (
						`uid`,`city`,`time`,`var`,`val`
					) VALUES (
						"'.$u->info['id'].'","'.$u->info['city'].'","'.time().'","'.mysql_real_escape_string($val).'","'.mysql_real_escape_string($k[2]).'"
					)');
				}else{
					mysql_query('UPDATE `dialog_act` SET `val` = "'.mysql_real_escape_string($k[2]).'" WHERE `uid` = "'.$u->info['id'].'" AND `var` = "'.mysql_real_escape_string($val).'" ORDER BY `id` DESC LIMIT 1');	
				}
			}elseif( $var == 'finish' ) {
				$r[0] = true;
				$r[1] = $val;
			}
			$i++;
		}
		return $r;
	}
	
	public function quest_act($data) {
		global $u;
		//
		$r = true;
		//
		$f = explode('#',$data);
		$ql = mysql_fetch_array(mysql_query('SELECT * FROM `dialog_act` WHERE `var` = "'.mysql_real_escape_string($f[3]).'" AND `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
		if(isset($ql['id'])) {
			//user_id # all # all # lukaqst1 # -1
			if( $f[0] == 'user_id' ) {
				$f[0] = $u->info['id'];
			}	
			//
			if( $f[0] != $ql['uid'] && $f[0] != 'all' ) {
				$r = false;
			}elseif( $f[1] != $ql['city'] && $f[1] != 'all' ) {
				$r = false;
			}elseif( $f[2] != $ql['time'] && $f[2] != 'all' ) {
				$r = false;
			}elseif( $f[3] != $ql['var'] && $f[3] != 'all' ) {
				$r = false;
			}elseif( $f[4] == -1 ) {
				//Не должен взять задание
				$r = false;
			}elseif( $f[4] != $ql['val'] ) {
				$r = false;
			}elseif( $f[5] == 1 && $ql['now'] < $ql['max'] ) {
				$r = false;
			}
		}else{
			if( $f[4] != -1 ) {
				$r = false;
			}
		}
		//
		return $r;
	}
	
	public function ltr($v)
	{
		
		return $v;	
	}
	
	public function infoBot($id)
	{
		global $c,$code;
		$r = '';
		$bot = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.((int)$id).'" LIMIT 1'));
		if(isset($bot['id']))
		{
			//Характеристики от предметов и их изображение
			$witm    = array();
			$witm[1] = '<img width="60" height="60" style="display:block;" title="Пустой слот шлем" src="http://img.xcombats.com/i/items/w/w9.gif">';
			$witm[2] = '<img width="60" height="40" style="display:block;" title="Пустой слот наручи" src="http://img.xcombats.com/i/items/w/w13.gif">';
			$witm[3] = '<img width="60" height="60" style="display:block;" title="Пустой слот оружие" src="http://img.xcombats.com/i/items/w/w3.gif">';
			$witm[4] = '<img width="60" height="80" style="display:block;" title="Пустой слот броня" src="http://img.xcombats.com/i/items/w/w4.gif">';
			$witm[7] = '<img width="60" height="40" style="display:block;" title="Пустой слот пояс" src="http://img.xcombats.com/i/items/w/w5.gif">';
			$witm[8] = '<img width="60" height="20" style="display:block;" title="Пустой слот серьги" src="http://img.xcombats.com/i/items/w/w1.gif">';
			$witm[9] = '<img width="60" height="20" style="display:block;" title="Пустой слот ожерелье" src="http://img.xcombats.com/i/items/w/w2.gif">';
			$witm[10] = '<img width="20" height="20" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[11] = '<img width="20" height="20" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[12] = '<img width="20" height="20" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif">';
			$witm[13] = '<img width="60" height="40" style="display:block;" title="Пустой слот перчатки" src="http://img.xcombats.com/i/items/w/w11.gif">';
			$witm[14] = '<img width="60" height="60" style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w10.gif">';
			$witm[16] = '<img width="60" height="80" style="display:block;" title="Пустой слот поножи" src="http://img.xcombats.com/i/items/w/w19.gif">';
			$witm[17] = '<img width="60" height="40" style="display:block;" title="Пустой слот обувь" src="http://img.xcombats.com/i/items/w/w12.gif">';
			//40-52 слот под магию		
			$witm[53] = '<img style="display:block;" title="Пустой слот правый карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[54] = '<img style="display:block;" title="Пустой слот левый карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[55] = '<img style="display:block;" title="Пустой слот центральный карман" src="http://img.xcombats.com/i/items/w/w15.gif">';
			$witm[56] = '<img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$witm[57] = '<img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			$witm[58] = '<img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif">';
			
			$pb = '';
			$hpmp = '??&nbsp;';
			$eff = '';
			$anml = '';
			$oi = '';
			$msl = '<img width="120" height="40" style="display:block" src="http://img.xcombats.com/i/slot_bottom.gif">';
			$witmg = '';
			
			if( $bot['level'] < 0 ) {
				$bot['level'] = '??';
			}
			
			$r = '<div style="width:246px; padding:2px;" align="center"><b>'.$bot['login'].'</b> ['.$bot['level'].']<img src="http://img.xcombats.com/i/inf_.gif"></div>
			<div style="width:240px; padding:2px; border-bottom:1px solid #666666; border-right:1px solid #666666; border-left:1px solid #FFFFFF; border-top:1px solid #FFFFFF;">
			<div align="center"><!-- blocked -->'.$pb.'</div>
			<table width="240" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="60" valign="top">
				<table width="60" height="280" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="60">'.$witm[1].'</td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[2].'</td>
				  </tr>
				  <tr>
					<td height="60">'.$witm[3].'</td>
				  </tr>
				  <tr>
					<td height="80">'.$witm[4].'</td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[7].'</td>
				  </tr>
				</table>
				</td>
				<td height="280" valign="top">
				<table width="120" height="280" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="20" valign="top">
					<!-- HP and MP -->
					'.$hpmp.'
					<!-- -->
					</td>
				  </tr>
				  <tr>
					<td valign="top">
					<div style="position:relative;height:220px;">
						<!-- образ -->
							<div style="position:absolute; width:120px; height:220px; z-index:1;"><a href="#obraz_pers"><img width="120" height="220" src="http://img.xcombats.com/i/obraz/'.$bot['sex'].'/'.$bot['obraz'].'" '.$oi.'></a></div>
							<div style="position:absolute; width:120px; height:220px; z-index:3;" align="left">'.$eff.'</div>'.$anml.'
					</div>
					</td>
				  </tr>
				  <tr>
					<td height="40"><div align="center">'.$msl.'</div></td>
				  </tr>
				</table>
				</td>
				<td width="60" valign="top">
				<table width="60" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="20">'.$witm[8].'</td>
				  </tr>
				  <tr>
					<td height="20">'.$witm[9].'</td>
				  </tr>
				  <tr>
					<td height="20"><table width="60" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="20" height="20">'.$witm[10].'</td>
						  <td width="20">'.$witm[11].'</td>
						  <td width="20">'.$witm[12].'</td>
						</tr>
					</table></td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[13].'</td>
				  </tr>
				  <tr>
					<td height="60">'.$witm[14].'</td>
				  </tr>
				  <tr>
					<td height="80">'.$witm[16].'</td>
				  </tr>
				  <tr>
					<td height="40">'.$witm[17].'</td>
				  </tr>
				</table>
				</td>
			  </tr>
			</table>'.$witmg.'
			</div>';
		}else{
			$r = 'No information';	
		}
		return $r;
	}
}

$dialog = new dialog;
?>