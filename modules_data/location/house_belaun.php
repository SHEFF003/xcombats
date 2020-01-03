<?
if( !defined('GAME') ) die();
if( $u->room['file'] == 'house_belaun' ) {
	$notify = array('type' => '', 'text' => ''); // Ошибки или Уведомления здесь!
	$action = '';
	$mode = '';
	$create_party = array(
		'name' => $_POST['dungeon_name'],
		'difficulty' => $_POST['dungeon_difficulty'],
		'password' => $_POST['dungeon_password'],
		'comment' => $_POST['dungeon_comment']
	);

	if( isset($_GET['action']) ) {
		$action = $_GET['action'];
		if( isset($_GET['mode']) ) {
			$mode = $_GET['mode'];
		}
	}
	if($create_party['name'] != ''){
		// Создаем поход в пещеру.
	}
}
?>
<style>
	.house_belaun {

	}
	.house_belaun .Menu {
		margin-bottom:-1px;
		height:28px;
	}
	.house_belaun .Menu .MenuList {
		padding: 0px 30px;
	}
	.house_belaun .Menu .MenuList .item {
		height: 28px;
		padding: 4px 14px 0px 14px;
		box-sizing: border-box;
		display: inline-block;
	}
	.house_belaun .Menu .MenuList .item.active {
		border:1px solid #a6a6a6;
		border-bottom:1px solid #d3d3d3;
		background-color: rgba(0,0,0,0.05);
	}
	.house_belaun .Content {
		background-color: rgba(0,0,0,0.05);
		width:100%;
		min-height:260px;
		border:1px solid #a6a6a6;
	}
	.notification {
		height:42px;
		box-sizing: border-box;
		padding: 12px 15px 0px 15px;
	}
	.notification.alert {
		color: #ef3c39;
		border-left: 4px solid #ef3c39;
		border-bottom: 1px solid #a6a6a6;
	}
	.notification.success {
		color: #2e6717;
		border-left: 4px solid #2e6717;
		border-bottom: 1px solid #a6a6a6;
	}
	.Content {
		padding:25px;
	}

	.box:after, .box:before { clear:both; content:"";display:table; }
	.box {
		width:330px;
		border-left: 1px solid #595959;
	}
	.box .body {
		padding: 12px;
		border-top: 1px solid #595959;
	}
	.box .heading {
		border-top: 1px solid #595959;
		padding: 12px 12px;
	}
	.box .button {
		border: 1px solid #595959;
		border-left: 0px;
	}
	.box input[type=submit] {
		cursor:pointer;
	}
	.box .button input {
		background-color: transparent;
		width: 100%;
		height: 40px;
		border: 0px;
		font-size: 13px;
		color: #444444;
		margin: 0px;
		display: inline-block;
	}
	.select {
		margin-bottom:12px;
		position:relative;
		width: 100%;
		box-sizing: border-box;
	}
	.selected {
		display: table;
		border: 1px solid #a6a6a6;
		height: 34px;
		width: 100%;
		box-sizing: border-box;
	}
	.select.in .options {
		display: table;
	}
	.select.in .arrow {
		background-image: url('http://img.xcombats.com/i/select-arrow-up.png');
	}

	.selected .opt1 {
		display: table-cell;
		padding: 0px 5px;
		box-sizing: border-box;
		background-color: #d4d4d4;
		text-align: center;
		vertical-align: middle;
		border-right: 1px solid #a6a6a6;
	}
	.selected div.opt2 {
		display: table-cell;
		vertical-align: middle;
		padding: 0px 10px 0px 13px;
		background-color: #eeeeee;
		letter-spacing: 0px;
		text-align: left;
		font-size: 13px;
		line-height: 14px;
		width: 90%;
	}
	.selected div.opt2i {
		display: table-cell;
	}
	.selected div.opt2i > input {
		display: block;
		vertical-align: middle;
		padding: 0px 8px;
		background-color: #eeeeee;
		letter-spacing: 0px;
		color: #222222;
		text-align: left;
		height: 100%;
		border: 0px;
		margin: 0px;
		outline:0;
		font-size: 14px;
		box-sizing: border-box;
		line-height: 32px;
		width: 100%;
	}
	.selected .arrow {
		border-left: 1px solid #a6a6a6;
		display: table-cell;
		width: 34px;
		min-width: 34px;
		box-sizing: border-box;
		background-color: #d4d4d4;
		vertical-align: middle;
		transition:all 0.3s ease;
		cursor: pointer;
		background-image: url('http://img.xcombats.com/i/select-arrow-down.png');
		background-repeat: no-repeat;
		background-position: center;
	}
	.selected .key:hover {
		background-image: url('http://img.xcombats.com/i/key-hover.gif');
	}
	.selected .key {
		border-left: 1px solid #a6a6a6;
		display: table-cell;
		width: 34px;
		min-width: 34px;
		box-sizing: border-box;
		background-color: #d4d4d4;
		vertical-align: middle;
		transition:all 0.2s ease;
		cursor: pointer;
		background-image: url('http://img.xcombats.com/i/key.gif');
		background-repeat: no-repeat;
		background-position: center;
	}

	.select .options {
		background-color: #eeeeee;
		border: 1px solid #a6a6a6;
		margin-top: -1px;
		width: 100%;
		z-index: 2;
		display: table;
		display: none;
		position: absolute;
		box-sizing: border-box;
		top: 100%;
	}
	.select .option + .option .opt1,
	.select .option + .option .opt2 {
		 border-top: 1px solid #a6a6a6;
	 }
	.select .option {
		width: 100%;
		cursor: pointer;
		box-sizing: border-box;
		display: table-row;
	}
	.select .option .opt1 {
		display: table-cell;
		text-align: center;
		padding: 10px 2px;
	}
	.select .option .opt2 {
		display: table-cell;
		text-align: left;
		padding: 10px 5px;
	}
</style>
<script>
	$(document).ready(function(){

		$('.select .selected .arrow').live('click', function(){
			$(this).parents('.select').toggleClass('in');
		});
		$('.select .option').live('click', function(){
			if( $(this).parents('.select').hasClass('in') ){
				$(this).parents('.select').toggleClass('in').children('input[type=hidden]').val($(this).data('value'));
				if( $(this).children('.opt1').html() != null) $(this).parents('.select').find('.selected .opt1').html($(this).children('.opt1').html());
				if( $(this).children('.opt2').html() != null) $(this).parents('.select').find('.selected .opt2').html($(this).children('.opt2').html());
			}
		});
		$('.select .key').live('click', function(){
			var l = $(this).parents('.selected').find("input[name=dungeon_password]");
			l.val( Math.floor(Math.random() * (9999 - 1000) + 1000) ).select();
			document.execCommand("copy");
		})

	});
</script>
<div class="house_belaun" style="box-sizing:border-box;width:100%; padding-right:300px;">
	<div class="Title">
		<h3><? echo $u->room['name']; ?></h3>
	</div>
	<div class="Menu">
		<div class="MenuList">
			<a href="/main.php?action=dialog&0.<?= rand(999,99999); ?>" class="item <?=($action=='dialog'? 'active':'')?>">Сер Белаун</a>
			<a href="/main.php?action=party&mode=create&0.<?= rand(999,99999); ?>" class="item <?=($action=='party' && $mode=='create'? 'active':'')?>">Подать</a>
			<a href="/main.php?action=party&0.<?= rand(999,99999); ?>" class="item <?=($action=='party' && $mode==''? 'active':'')?>">Стол заявок(группа)</a>
			<a href="/main.php?action=quest&0.<?= rand(999,99999); ?>" class="item <?=($action=='quest'? 'active':'')?>">Доска объявлений(задания)</a>
		</div>
	</div>
	<div class="Content">
<?
	if($notify['text'] != '') echo '<div class="notification '.$notify['type'].'">'.$notify['text'].'</div>';
	if($action == "dialog") {

	}
	if($action == "quest") {

	}
	if($action == "party" && $mode == '') {
		//Генерируем список групп
		$dungeonGroupList = '';
		$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "all" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');
		while( $pl = mysql_fetch_array( $sp ) ){
			$dungeonGroupList .= '<div style="padding:2px;">';
			if( $u->info['dn'] == 0 ) $dungeonGroupList .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
			$dungeonGroupList .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
			$dungeonGroupList .= '<span><img title="'.$dunname[$pl['dun']][1].'" style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/'.$dunname[$pl['dun']][0].'.gif" /></span> ';

			$pus = ''; //группа
			$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
			while( $pu = mysql_fetch_array( $su ) ) {
				$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_'.$pu['city'].'.gif" title="Инф. о '.$pu['login'].'"></a>';
				$pus .= ', ';
			}
			$pus = trim( $pus, ', ' );

			$dungeonGroupList .= $pus; unset($pus);

			if( $pl['pass'] != '' && $u->info['dn'] == 0 ) $dungeonGroupList .= ' <small><input type="password" name="pass_com" value=""></small>';

			if( $pl['com'] != '' ) {
				$dl = '';
				// Если модератор, даем возможность удалять комментарий к походу.
				$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
				if( ( $moder['boi'] == 1 || $u->info['admin'] > 0 ) && $pl['dcom'] == 0 ){
					$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">удалить комментарий</a>)';
					if( isset( $_GET['delcom'] ) && $_GET['delcom'] == $pl['id'] && $u->newAct( $_GET['key'] ) == true ) {
						mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						$pl['dcom'] = $u->info['id'];
					}
				}
				$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
				if( $pl['dcom'] > 0 ) {
					$dl = ' <font color="grey"><i>комментарий удален модератором</i></font>';
				}
				if( $pl['dcom'] > 0 ) {
					if( $moder['boi'] == 1 || $u->info['admin'] > 0 ) {
						$pl['com'] = '<font color="red">'.$pl['com'].'</font>';
					} else {
						$pl['com'] = '';
					}
				}
				$dungeonGroupList .= '<small> | '.$pl['com'].''.$dl.'</small>';
			}
			$dungeonGroupList .= '</div>';
		}
		var_info($dungeonGroupList);
	}
	if($action == "party" && $mode == 'create') {
?>
		<div class="box">
			<form action="/main.php?action=party&mode=create&0.<?=rand(999,99999);?>" method="post">
				<div class="heading">Куда вы хотите направиться?</div>
				<div class="body">
					<div class="select"><!-- КУДА НАПРАВЛЯЕМСЯ -->
						<input type="hidden" name="dungeon_name" value="capitalcity"/>
						<div class="selected"><div class="opt1"><img src="http://img.xcombats.com/i/city_ico3/1.gif"></div><div class="opt2">Пещера Тысячи Проклятий</div><div class="arrow"></div></div>
						<div class="options"><div class="option" data-value="capitalcity"><div class="opt1"><img src="http://img.xcombats.com/i/city_ico3/1.gif"></div><div class="opt2">Пещера Тысячи Проклятий</div></div><div class="option" data-value="angelscity"><div class="opt1"><img src="http://img.xcombats.com/i/city_ico3/2.gif"></div><div class="opt2">Бездна</div></div><div class="option" data-value="demonscity"><div class="opt1"><img src="http://img.xcombats.com/i/city_ico3/3.gif"></div><div class="opt2">Катакомбы</div></div></div>
					</div>

					<div class="select"><!-- СЛОЖНОСТЬ НАПРАВЛЕНИЯ -->
						<input type="hidden" name="dungeon_difficulty" value="normal"/>
						<div class="selected"><div class="opt1">Сложность:</div><div class="opt2">Нормальная</div><div class="arrow"></div></div>
						<div class="options"><div class="option" data-value="easy"><div class="opt2">Легкая</div></div><div class="option" data-value="normal"><div class="opt2">Нормальная</div></div><div class="option" data-value="hard"><div class="opt2">Сложная</div></div></div>
					</div>

					<div class="select"><!-- ПАРОЛЬ -->
						<div class="selected"><div class="opt1">Пароль:</div><div class="opt2i"><input type="text" name="dungeon_password" maxlength="6" value=""/></div><div class="key"></div></div>
					</div>
					<div class="select"><!-- КОММЕНТАРИЙ  -->
						<div class="selected"><div class="opt2i"><input type="text" name="dungeon_comment" maxlength="130" placeholder="Комментарий:" value=""/></div></div>
					</div>
				</div>
				<div class="button"><input type="submit" name="partyCreate" value="Создать группу"/></div>
			</form>
		</div>
<? } ?>
	</div>
</div>



<?

if($u->room['file']=='house_belaun' && false) {

	$error = ''; // Собираем ошибки.
	$dungeonGroupList = ''; // Сюда помещаем список Групп.
	$dungeonGo = 1; // По умолчанию, мы идем в пещеру.
	$dungeon = mysql_fetch_assoc( mysql_query('SELECT `id` as room, city, `dungeon_room` as d_room, city, `shop`, `dungeon_id` as id, `dungeon_name` as name, quest FROM `dungeon_room` WHERE `id`="'.$u->room['id'].'" LIMIT 1') );

$dunname = array(
	12 => array('capitalcity','Пещера Тысячи Проклятий'),
	101 => array('angelscity','Бездна'),
	//3 => array('demonscity','Катакомбы'),
	//105 => array('sandcity','Пещеры Мглы'),
	//108 => array('emeraldscity','Потерянный вход'),
	//10 => array('suncity','Грибница'),
	//106 => array('devilscity','Туманные Низины')
);

if( $dungeon['id'] == 104 && isset($_GET['freego'])) {
	if( $u->info['level'] > 7 ) {
		
	}elseif( $u->info['money4'] < $u->info['level'] * 5 ) {
		echo '<div><b style="color:#F00">Недостаточно зубов, необходимо '.$u->zuby( ($u->info['level'] * 5) ,1).'</b></div>';
	}else{
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "psh0" AND `time` >= '.(time()-60*60*3).' LIMIT 1');
		$u->info['money4'] -= $u->info['level'] * 5;
		mysql_query('UPDATE `users` SET `money4` = "'.$u->info['money4'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		echo '<div><b style="color:#F00">Вы успешно сняли задержку на поход за '.$u->zuby( ($u->info['level'] * 5) ,1).'</b></div>';
	}
}

if(isset($_GET['rz']) && $dungeon['quest'] == 1) $roomSection = 1; // Получаем Задание
	else $roomSection = 0;  // Собираем группу для похода
//if( $u->info['admin'] > 0 ) var_info($dungeon);

$all_dungeon = mysql_query('SELECT `city` FROM `dungeon_room` WHERE `city` IS NOT NULL AND `active`=1 ');
while( $t = mysql_fetch_array($all_dungeon) ) { $dungeon['list'][] = $t['city']; }
unset($all_dungeon);
 
if( $u->info['dn'] > 0 ) {
	$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `id`="'.$u->info['dn'].'" AND `delete` = "0" LIMIT 1'));
	if(!isset($zv['id'])){
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
	}
}

$dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh0" AND `time` > '.(time()-60*60*3).' LIMIT 1',1);

if($u->info['admin']>0) unset($dungeon_timeout); // $dungeon_timeout - задержка на посещение пещеры.
if(isset($dungeon_timeout['id'])) // Кто-то передумал и не пойдет в пещеру, так-как уже там был.
{
	$dungeonGo = 0;
	if(isset($_GET['start'])){
		$error = 'До следующего похода осталось еще: '.$u->timeOut(60*60*3-time()+$dungeon_timeout['time']);
	}
}

if( isset( $_GET['start'] ) && $zv['uid'] == $u->info['id'] && $dungeonGo == 1 ) {
	$ig = 1;
	if( $ig > 0 ){ //перемещаем игроков в пещеру
		//$u->addAction(time(),'psh'.$dun,'');
		
		//
		$ins = mysql_query('INSERT INTO `dungeon_now` (`city`,`uid`,`id2`,`name`,`time_start`)
		VALUES ("'.$zv['city'].'","'.$zv['uid'].'","'.$zv['dun'].'","'.$dunname[$zv['dun']][1].'","'.time().'")');
		if($ins){
			$zid = mysql_insert_id();
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			//обновляем пользователей
			$su = mysql_query('SELECT `u`.`id`,`st`.`dn` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$zv['id'].'" /*LIMIT '.($zv['team_max']+1).'*/');
			$ids = '';
			
			$map_locs = array();
			$spm2 = mysql_query('SELECT `id`,`x`,`y` FROM `dungeon_map` WHERE `id_dng` = "'.$zv['dun'].'"');
			while( $plm2 = mysql_fetch_array( $spm2 ) ) {
				$map_locs[] = array($plm2['x'],$plm2['y']);
			}
			unset( $spm2 , $plm2 );
			
			$pxd = 0;
			while( $pu = mysql_fetch_array($su) ) {
				$pxd++;
				$ids .= ' `id` = "'.$pu['id'].'" OR';
				if( $u->stats['silver'] >= 3 ) {
					$u->addAction( ( time() - ((60*60*3)/100*30) ) ,'psh0',$pu['id'],$pu['id']);
				}else{
					$u->addAction(time(),'psh0',$pu['id'],$pu['id']);
				}
				//Добавляем квестовые обьекты для персонажей 
				$sp = mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `room` = '.$dungeon['room'].' AND `vars` LIKE "%start_quest%" AND `vals` = "go" LIMIT 100');
				while($pl2 = mysql_fetch_array($sp)){
					$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.(str_replace('start_quest','',$pl2['vars'])).'" AND `line` = "'.$dungeon['id'].'" LIMIT 1')); 
					if( isset($pl['id']) ) {
						$act = explode(',',$pl['act_date']);
						$i = 0;
						while( $i < count($act) ) {
							$act_date = explode(':|:',$act[$i]);
							foreach($act_date as $key=>$val){ 
								$val = explode(':=:',$val);
								$actdate[$val[0]] = $val[1];
 							}
							//Сбор ресурсов
							if( isset($actdate['tk_itm']) && $actdate['tk_itm'] != '' ) {
								$xr2 = explode('=',$actdate['tk_itm']);
								if( $xr2[2] == 0 ) {
									if( isset($actdate['tk_itm_fromY']) && isset($actdate['tk_itm_toY']) ) {
										$actdate['tk_itm_fromY'] = (integer)$actdate['tk_itm_fromY'];
										$actdate['tk_itm_toY'] = (integer)$actdate['tk_itm_toY'];
									}
									$ml_arr = array();
									foreach($map_locs as $ml){ // tk_itm_fromY  tk_itm_toY  - отсеиваем позицию для дропа предметов.
										if( (isset($actdate['tk_itm_fromY']) && isset($actdate['tk_itm_toY'])) OR (!isset($actdate['tk_itm_fromY']) && isset($actdate['tk_itm_toY'])) ) {
											if( $ml[1] > $actdate['tk_itm_fromY'] && $actdate['tk_itm_toY'] > $ml[1] )$ml_arr[] = $ml;
											elseif( !isset($actdate['tk_itm_fromY']) && $actdate['tk_itm_toY'] > $ml[1] ) $ml_arr[] = $ml;
										} else $ml_arr[] = $ml;
									}
									if( isset($ml_arr) && count($ml_arr) == 0 ) $ml_arr = $map_locs; 
									//Добавляем обьект для юзера
									$j = 0;
									while( $j < $xr2[1] ){
										$cord = $ml_arr[rand(0,count($ml_arr)-1)];
										if( $cord[0] != 0 || $cord[1] != 0 ) {
											mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`,`onlyfor`,`quest`) VALUES (
												"'.$zid.'","'.$u->info['id'].'","'.$xr2[0].'","'.time().'","'.$cord[0].'","'.$cord[1].'","'.$u->info['id'].'","'.$pl['id'].'"
											)');
										}
										$j++;
									}
								}else{
									//Предмет находится в конкретном месте
									mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`,`onlyfor`,`quest`) VALUES (
										"'.$zid.'","'.$u->info['id'].'","'.$xr2[0].'","'.time().'","'.$xr2[2].'","'.$xr2[3].'","'.$u->info['id'].'","'.$pl['id'].'"
									)');
								}
							}
							$i++;
						}
					}
				}
				
			}
			$ids = rtrim($ids,'OR');
			$snew = 1;
			$upd1 = mysql_query('UPDATE `stats` SET `s`="'.$snew.'",`res_s`="1",`x`="0",`y`="0",`res_x`="0",`res_y`="0",`dn` = "0",`dnow` = "'.$zid.'" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
			if( $upd1 ){
				$upd2 = mysql_query('UPDATE `users` SET `room` = "405" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
				//Добавляем ботов и обьекты в пещеру $zid с for_dn = $dungeon['id']
				//Добавляем ботов
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$zv['dun'].'"');
				while( $pl = mysql_fetch_array( $sp ) ) {
					if( $pl['id_bot'] == 0 && $pl['bot_group'] !=''){
						$bots = explode( ',', $pl['bot_group'] );
						$pl['id_bot'] = (int)$bots[rand(0, count($bots)-1 )];
					}
					if( $pl['id_bot'] > 0 )$vls .= '("'.$zid.'","'.$pl['id_bot'].'","'.$pl['colvo'].'","'.$pl['items'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['dialog'].'","'.$pl['items'].'","'.$pl['go_bot'].'","'.$pl['noatack'].'"),';
					unset($bots);
				}
				$vls = rtrim($vls,',');				
				$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`,`go_bot`,`noatack`) VALUES '.$vls.'');
				//Добавляем обьекты
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "'.$zv['dun'].'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
				}
				//Добавление обьектов (день святого валентина)
				if( floor(date('m')) == 2 && floor(date('d')) >= 7 ) {
					if( floor(date('m')) == 2 && floor(date('d')) >= 14 ) {
						//Появляются мобы которые принимают цветы
						$vlsbts = '';
						$ins1bts = NULL;
						if( $zv['dun'] == 1 ) {
							//4 уровня (КАНАЛИЗАЦИЯ)
							$vlsbts .='("'.$zid.'","410","1","","-5","3","8","0","0"),';
							//4-7 уровня
							$vlsbts .='("'.$zid.'","413","1","","8","46","9","0","0"),';
						}elseif( $zv['dun'] == 12 ) {
							//(ПТП)
							//4-7 уровня
							$vlsbts .='("'.$zid.'","413","1","","-3","18","9","0","0"),';
							//4-9 уровня
							$vlsbts .='("'.$zid.'","414","1","","-2","29","10","0","0"),';
						}elseif( $zv['dun'] == 3 ) {
							//(КАТАКОМБЫ)
							//4-7 уровня
							$vlsbts .='("'.$zid.'","413","1","","15","8","9","0","0"),';
							//4-9 уровня
							$vlsbts .='("'.$zid.'","414","1","","3","35","10","0","0"),';
						}elseif( $zv['dun'] == 101 ) {
							//(бездна)
							//4-7 уровня
							$vlsbts .='("'.$zid.'","413","1","","-2","21","9","0","0"),';
							//4-9 уровня
							$vlsbts .='("'.$zid.'","414","1","","2","43","10","0","0"),';
						}
						
						if( $vlsbts != '' ) {
							$vlsbts = rtrim($vlsbts,',');
							$ins1bts = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`,`go_bot`) VALUES '.$vlsbts.'');
						}
						unset($vlsbts,$ins1bts);
					}
					//Раскидываем предметы по пещере (Блёклый подземник)
					$dcords = array();
					$c_sp = mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$zv['dun'].'"');
					while( $c_pl = mysql_fetch_array($c_sp)) {
						$dcords[] = array($c_pl['x'],$c_pl['y']);
					}
					$fcords = array();
					$i = 1;
					while($i <= $pxd) {
						$j = rand(1,10);
						while( $j >= 0 ) {
							$rndxy = rand(0,count($dcords)-1);
							$rndx = $dcords[$rndxy][0];
							$rndy = $dcords[$rndxy][1];
							$fcords[$rndx][$rndy] = true;
							unset($dcords[$rndxy]);
							$vls .= '("'.$zid.'","Блеклый подземник","vbig1.gif","'.$rndx.'","'.$rndy.'","fileact:vbig1","0","81","81","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt2:154,rl2:146,rt3:139,rl3:154,rt4:125,rl4:161}"),';
							$j--;
						}
						$i++;
					}
					//Раскидываем предметы по пещере (Черепичный подземник)
					$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$zv['dun'].'"');
					$test = array();
					$dcords2 = array();
					$dcords3 = array();
					while( $pl = mysql_fetch_array( $sp ) ) {
						if(!isset($test[$pl['id_bot']])) {
							$test[$pl['id_bot']] = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$pl['id_bot'].'" LIMIT 1'));
						}
						if( isset($test[$pl['id_bot']]['id']) && $test[$pl['id_bot']] != 2 ) {
							if( $test[$pl['id_bot']]['level'] > 6 ) {
								$dcords2[] = array($pl['x'],$pl['y']);
							}
							if( $test[$pl['id_bot']]['level'] >= 8 && $test[$pl['id_bot']]['align'] == 9 ) {
								$dcords3[] = array($pl['x'],$pl['y']);
							}
						}else{
							$test[$pl['id_bot']] = 2;
						}
					}
					$i = 1;
					while($i <= $pxd) {
						$j = rand(1,5);
						while( $j >= 0 ) {
							$rndxy = rand(0,count($dcords2)-1);
							$rndx = $dcords2[$rndxy][0];
							$rndy = $dcords2[$rndxy][1];
							if(!isset($fcords[$rndx][$rndy]) && isset($dcords2[$rndxy][0])) {
								$fcords[$rndx][$rndy] = true;
								unset($dcords2[$rndxy]);
								$vls .= '("'.$zid.'","Черепичный подземник","vbig2.gif","'.$rndx.'","'.$rndy.'","fileact:vbig2","0","81","81","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt2:154,rl2:146,rt3:139,rl3:154,rt4:125,rl4:161}"),';
							}
							$j--;
						}
						$i++;
					}
					$i = 1;
					while($i <= $pxd) {
						$j = rand(1,2);
						while( $j >= 0 ) {
							$rndxy = rand(0,count($dcords3)-1);
							$rndx = $dcords3[$rndxy][0];
							$rndy = $dcords3[$rndxy][1];
							if(!isset($fcords[$rndx][$rndy]) && isset($dcords3[$rndxy][0])) {
								$fcords[$rndx][$rndy] = true;
								unset($dcords3[$rndxy]);
								$vls .= '("'.$zid.'","Кровавый подземник","vbig3.gif","'.$rndx.'","'.$rndy.'","fileact:vbig3","0","81","81","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt2:154,rl2:146,rt3:139,rl3:154,rt4:125,rl4:161}"),';
							}
							$j--;
						}
						$i++;
					}
					unset($test);
				}
				//
				$vls = rtrim($vls,',');	
				if( $vls != '' ) {			
					$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
				} else {
					$ins2 = true;
				}
				if( $upd2 && $ins1 && $ins2 ){
					die('<script>location="main.php?rnd='.$code.'";</script>');
				} else {
					$error = 'Ошибка перехода в подземелье...';
				}
			} else {
				$error = 'Ошибка перехода в подземелье...';
			}
		} else {
			$error = 'Ошибка перехода в подземелье...';
		}
	}
} elseif( isset( $_POST['go'] , $_POST['goid'] ) && $dungeonGo == 1 ) {
	if(!isset($zv['id'])) {
		$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "all" AND `id`="'.mysql_real_escape_string($_POST['goid']).'" AND `delete` = "0" LIMIT 1'));
		if( isset( $zv['id'] ) && $u->info['dn'] == 0) {
			if( $zv['pass'] != '' && $_POST['pass_com'] != $zv['pass'] ) {
				$error = 'Вы ввели неправильный пароль';				
			} elseif( $u->info['level'] > 7 && $zv['dun'] != 1 ){
				$row = 0;
				if( 5 > $row ) {
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if( !$upd ){
						$error = 'Не удалось вступить в эту группу';
						unset($zv);
					} else {
						$u->info['dn'] = $zv['id'];
					}
				} else {
					$error = 'В группе нет места';
					unset($zv);
				}
			} elseif( $zv['dun'] == 1 ){
				//Канализация
				$row_max = 5;
				if( $u->info['level'] == 4 ) {
					$row_max = 4;
				}elseif( $u->info['level'] == 5 ) {
					$row_max = 3;
				}elseif( $u->info['level'] == 6 ) {
					$row_max = 2;
				}elseif( $u->info['level'] >= 7 ) {
					$row_max = 1;
				}
				$row = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row1 = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row2 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$row1['id'].'" LIMIT 1'));
				$row = $row[0];
				if( $row2['level'] != $u->info['level'] ) {
					$error = 'Вы не подходите по уровню';
				}elseif( $row_max > $row ) {
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if( !$upd ){
						$error = 'Не удалось вступить в эту группу';
						unset($zv);
					} else {
						$u->info['dn'] = $zv['id'];
					}
				} else {
					$error = 'В группе нет места';
					unset($zv);
				}
			} elseif( $u->info['level'] < 4 && $zv['dun'] == 104 ){
				//Шахты
				$row_max = 3;
				if( $u->info['level'] > 3 ) {
					$row_max = 1;
				}
				$row = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row1 = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row2 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$row1['id'].'" LIMIT 1'));
				$row = $row[0];
				if( $row2['level'] != $u->info['level'] && $u->info['level'] > 3 ) {
					$error = 'Вы не подходите по уровню';
				}elseif( $row_max > $row ) {
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if( !$upd ){
						$error = 'Не удалось вступить в эту группу';
						unset($zv);
					} else {
						$u->info['dn'] = $zv['id'];
					}
				} else {
					$error = 'В группе для вас нет места';
					unset($zv);
				}
			} else {
				$error = 'Вы не подходите по уровню';
				unset($zv);
			}
		} else {
			$error = 'Заявка не найдена';
		}
	} else {
		$error = 'Вы уже находитесь в группе';
	}
} elseif( isset( $_POST['leave'] ) && isset( $zv['id'] ) && $dungeonGo == 1 ) {
	if( $zv['uid'] == $u->info['id'] ) {
		//ставим в группу нового руководителя
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if( isset($ld['id']) ){
			$zv['uid'] = $ld['id'];
			mysql_query('UPDATE `dungeon_zv` SET `uid` = "'.$zv['uid'].'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		} else {
			//удаляем группу целиком
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}
	} else {
		//просто выходим с группы
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
		unset($zv);
	}
} elseif( isset($_POST['add']) && $u->info['level'] > 1 && $dungeonGo == 1 ) {
	if( $u->info['dn'] == 0 ) {	
		$dun5 = $dungeon['id'];	
		//
		if(isset($_POST['rpmg1'])) {
			if($_POST['rpmg1']==2) {
				$dun5 = 101; //Бездна
			}elseif($_POST['rpmg1']==3) {
				$dun5 = 105; //Пещера Мглы
			}elseif($_POST['rpmg1']==4) {
				$dun5 = 3; //Катакомбы
			}elseif($_POST['rpmg1']==5) {
				$dun5 = 108; //Потерянный вход
			}elseif($_POST['rpmg1']==6) {
				$dun5 = 10; //Грибница
			}elseif($_POST['rpmg1']==7) {
				$dun5 = 12; //Низины
			}else{
				$dun5 = 12; //ПТП
			}
		}else{
			$dun5 = 12; //ПТП
		}
		//
		$ins = mysql_query('INSERT INTO `dungeon_zv`
		(`city`,`time`,`uid`,`dun`,`pass`,`com`,`lvlmin`,`lvlmax`,`team_max`) VALUES
		("all","'.time().'","'.$u->info['id'].'","'.$dun5.'",
		"'.mysql_real_escape_string($_POST['pass']).'",
		"'.mysql_real_escape_string($_POST['text']).'",
		"8",
		"21",
		"5")');
		if( $ins ) {
			$u->info['dn'] = mysql_insert_id();
			$zv['id'] = $u->info['dn'];
			$zv['uid'] = $u->info['id'];
			mysql_query('UPDATE `stats` SET `dn` = "'.$u->info['dn'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$error = 'Вы успешно создали группу';
		} else {
			$error = 'Не удалось создать группу';
		}
	} else {
		$error = 'Вы уже находитесь в группе';
	}
}

//Генерируем список групп
$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "all" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');
while( $pl = mysql_fetch_array( $sp ) ){
	$dungeonGroupList .= '<div style="padding:2px;">';
	if( $u->info['dn'] == 0 ) $dungeonGroupList .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	$dungeonGroupList .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	
	$dungeonGroupList .= '<span><img title="'.$dunname[$pl['dun']][1].'" style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/'.$dunname[$pl['dun']][0].'.gif" /></span> ';
	
	$pus = ''; //группа
	$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
	while( $pu = mysql_fetch_array( $su ) ) {
		$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_'.$pu['city'].'.gif" title="Инф. о '.$pu['login'].'"></a>'; 
		$pus .= ', ';
	}
	$pus = trim( $pus, ', ' );
	
	$dungeonGroupList .= $pus; unset($pus);
	
	if( $pl['pass'] != '' && $u->info['dn'] == 0 ) $dungeonGroupList .= ' <small><input type="password" name="pass_com" value=""></small>';
	
	if( $pl['com'] != '' ) {
		$dl = '';
		// Если модератор, даем возможность удалять комментарий к походу.
		$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
		if( ( $moder['boi'] == 1 || $u->info['admin'] > 0 ) && $pl['dcom'] == 0 ){
			$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">удалить комментарий</a>)';
			if( isset( $_GET['delcom'] ) && $_GET['delcom'] == $pl['id'] && $u->newAct( $_GET['key'] ) == true ) {
				mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['dcom'] = $u->info['id'];
			}
		}
		$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
		if( $pl['dcom'] > 0 ) {
			$dl = ' <font color="grey"><i>комментарий удален модератором</i></font>';
		}
		if( $pl['dcom'] > 0 ) {
			if( $moder['boi'] == 1 || $u->info['admin'] > 0 ) {
				$pl['com'] = '<font color="red">'.$pl['com'].'</font>';
			} else {
				$pl['com'] = '';
			}
		}
		$dungeonGroupList .= '<small> | '.$pl['com'].''.$dl.'</small>';
	}
	$dungeonGroupList .= '</div>';
}
?>
<style>
body {
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div></td>
    <td width="200"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td>
          <? if($roomSection==0) { ?>
          <table  border="0" cellpadding="0" cellspacing="0">
              <tr align="right" valign="top">
                <td><!-- -->
                    <? echo $goLis; ?>
                    <!-- -->
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                            <tr>
								<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=<? if($u->info['city']=='abandonedplain') { echo '3.180.0.267'; } elseif($u->info['city']=='fallenearth') { echo '6.180.0.102'; } elseif($u->info['room']==188) { echo '1.180.0.4'; } elseif($u->info['room']==393) { echo '1.180.0.2'; } elseif($u->info['room']==372) { echo '1.180.0.323'; }elseif($u->info['room']==395) { echo '2.180.0.231'; }elseif($u->info['room']==397) { echo '2.180.0.229'; } elseif($u->info['room']==242) { echo '2.180.0.236'; } elseif($u->info['room']==321) { echo '1.180.0.213'; } else { echo '1.180.0.321'; } ?>&rnd=<? echo $code; ?>';" title="<? 
								if($u->info['city']=='fallenearth'){
									thisInfRm('6.180.0.102',1); 
								}elseif($u->info['city']=='abandonedplain'){
									thisInfRm('3.180.0.267',1); 
								}elseif($u->info['room']==188){
									thisInfRm('1.180.0.4',1); 
								}elseif($u->info['room']==393){
									thisInfRm('1.180.0.2',1); 
								}elseif($u->info['room']==372){
									thisInfRm('1.180.0.323',1); 
								}elseif($u->info['room']==395){
									thisInfRm('2.180.0.231',1); 
								}elseif($u->info['room']==397){
									thisInfRm('2.180.0.229',1); 
								}elseif($u->info['room']==242){
									thisInfRm('2.180.0.236',1); 
								}elseif($u->info['room']==321){
									thisInfRm('1.180.0.213',1); 
								}else {
									thisInfRm('1.180.0.321',1);
								}
								?>"><?
								if($u->info['city']=='fallenearth'){
								  echo "Темный Портал";
								} elseif($u->info['city']=='abandonedplain'){
								  echo "Центральная площадь";
								} elseif($u->info['room']==188){
								  echo "Зал воинов";
								} elseif($u->info['room']==393){
								  echo "Зал воинов 2";
								} elseif($u->info['room']==372){
								  echo "Большая парковая улица";
								} elseif($u->info['room']==395){
								  echo "Зал воинов";
								} elseif($u->info['room']==397){
								  echo "Зал воинов 2";
								} elseif($u->info['room']==242){
								  echo "Страшилкина улица";
								} elseif($u->info['room']==321){
								  echo "Большая торговая улица";
								} else {
								  echo "Магический Портал";
								}
								?></a></td>
                            </tr>
                            <? if( isset($dungeon['shop']) && $dungeon['shop'] > 0 ) {
								$shop = mysql_fetch_array( mysql_query('SELECT `id` as shop_id, `code` FROM `room` WHERE `id` = "'.$dungeon['shop'].'" LIMIT 1') );
								if( isset($shop['code']) ){?>
							<tr>
								<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=<?=$shop['code']?>&rnd=<? echo $code; ?>';" title="<? thisInfRm($shop['code'],1); ?>">Рыцарский магазин</a></td>
                            </tr>
								<? }
							} ?>
                        </table>
						</td>
                      </tr>
                  </table></td>
              </tr>
          </table>
          <? } ?>
          </td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<? if( $roomSection == 1 ) { ?>
	<div align="center" style="float:right;width:100px;">
	  <p>
		<input type='button' onclick='location="main.php?rz=1"' value="Обновить" />
		<br />
		<input type='button' onclick='location="main.php"' value="Вернуться" />
	  </p>
	</div>
	<? } else { ?>
	<div align="center" style="float:right;width:100px;">
	  <p>
		<input type='button' onclick='location="main.php"' value="Обновить" />
		<? if($dungeon['quest'] == 1){?>
		<br />
		<input type='button' onclick='location="main.php?rz=1"' value="Задания" />
		<? } ?>
	  </p>
	</div>
<? } ?>
<?
if($error!='')echo '<font color="red"><b>'.$error.'</b></font><br>';

//отображаем
if( $dungeonGroupList == '' ) {
	$dungeonGroupList = '';
} else {
	if( !isset( $zv['id'] ) || $u->info['dn'] == 0 ){
		if($dungeonGo==1 || $u->info['dn'] == 0 ){
			$pr = '<input name="go" type="submit" value="Вступить в группу">';
		}
		$dungeonGroupList = '<form autocomplete="off" action="main.php?rnd='.$code.'" method="post">'.$pr.'<br>'.$dungeonGroupList.''.$pr.'</form>';
	}
	$dungeonGroupList .= '<hr>';
}

if( $roomSection == 0 ) { echo $dungeonGroupList; }
if( $roomSection == 1 ) { 
	# endQuest завершаем задание по нажатию.
	if( isset( $_GET['endQuest'] ) && $_GET['endQuest'] != '' ){ 
		$action = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `uid` = '.$u->info['id'].' AND `id`="'.$_GET['endQuest'].'" AND `vals` = "go" LIMIT 1'));
		$quest = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$action['vars']).'" LIMIT 1'));
		if( $q->questCheckEnd($quest)==1 ){ 
			$q->questSuccesEnd($quest, $action);
		}
	}
?>
<div>
	<form autocomplete="off" action='/main.php' method="post" name="F1" id="F1">
<?
	$qsee = '';
	$hgo = $u->testAction('`uid` = "'.$u->info['id'].'" AND `room` = "'.$u->info['room'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_'.$dungeon['city'].'" LIMIT 1',1);
	$qc=0; // Quest Count
	//Генерируем список текущих квестов
	$sp = mysql_query('SELECT * FROM `actions` WHERE `vars` LIKE "%start_quest%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 100');
	while( $pl = mysql_fetch_array( $sp ) ) {
		if($pl['room'] == $u->info['room']){
			$pq = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$pl['vars']).'" LIMIT 1'));
			if( $q->questCheckEnd($pq)==1 ) $qsee2 = '<input style="margin-top:6px;" type="button" value="Завершить задание" onclick="location=\'main.php?rz=1&amp;endQuest='.$pl['id'].'\'">'; else $qsee2 = '';
			 
			$qsee .= '
			<a href="main.php?rz=1&end_qst_now='.$pq['id'].'"><img src="http://img.xcombats.com/i/clear.gif" title="Отказаться от задания"></a>
			<b>'.$pq['name'].'</b>
			<div style="padding-left:15px;padding-bottom:5px;border-bottom:1px solid grey"><small>'.$pq['info'].'<br>'.$q->info($pq).''.$qsee2.' </small></div>
			<br>';
			
			# 
			
			$qc++;
		}
	}
	
	if( isset( $_GET['add_quest'] ) && $qc == 0 ) {
		if( isset( $hgo['id'] ) ) {
			echo '<font color="red"><b>Нельзя получать задания чаще одного раза в сутки</b></font><br>';
		} else {
			$sp = mysql_query('SELECT * FROM `quests` WHERE `line` = '.$dungeon['id'].'');
			$dq_add = array();
			while( $pl = mysql_fetch_array( $sp ) ) {
				if( $u->rep['rep'.$dungeon['city']] == 9999 ) {
					//квет, рыцарского задания
					if( $pl['kin'] == 1 ) {
						$dq_add = array( 0 => $pl );
					}
				} elseif( $u->rep['rep'.$dungeon['city']] == 24999 ) {
					//квет, рыцарского задания
					if( $pl['kin'] == 2 ) {
						$dq_add = array( 0 => $pl );
					}
				} else {
					if( $pl['kin'] == 0 ) {
						$dq_add[count($dq_add)] = $pl;
					}
				}
			}
			$dq_add = $q->onlyOnceQuest($dq_add, $u->info['id']);
			$dq_add = $dq_add[rand(0,count($dq_add)-1)];
			
			
			if( $q->testGood($dq_add) == 1 && $dq_add > 0 ) {
				$q->startq_dn($dq_add['id']);
				echo '<font color="red"><b>Вы успешно получили новое задание &quot;'.$dq_add['name'].'&quot;.</b></font><br>'; 
				$u->addAction(time(),'psh_qt_'.$dungeon['city'],$dq_add['id']);
			} else {
				if ( $u->rep['rep'.$dungeon['city']] == 9999 ) {
					//квест, рыцарского задания
					echo '<font color="red"><b>Вы уже получили задание на достижение титула рыцаря!</b></font><br>';
				} elseif( $u->rep['rep'.$dungeon['city']] >= 24999 ) {
					//квест, рыцарского задания
					echo '<font color="red"><b>Вы завершили квестовую линию, ожидайте новых заданий!</b></font><br>';
				} else {
					echo '<font color="red"><b>Не удалось получить задание &quot;'.$dq_add['name'].'&quot;. Попробуйте еще...</b></font><br>';
				}	
			}
			unset( $dq_add );
		}
	} elseif( isset( $_GET['add_quest'] ) && $qc > 0 ) {
		echo '<font color="red"><b>Что-то пошло не так... осторожнее.. <br/><br/></b></font><br>';
	}
	if( $qsee == '' ) {
		$qsee = 'К сожалению у вас нет ни одного задания<br/><br/>';
	}
?>
<Br />
		<FIELDSET>
		<LEGEND><B>Текущие задания: </B></LEGEND>
		<?=$qsee?>
		<span style="padding-left: 10">
		<?
		if( $qc > 0 ){
			echo 'Вы еще не справились с текущим заданием.';
		} elseif( !isset( $hgo['id'] ) && $qc == 0 ) {
			?>
			<br />
			<input type='button' value='Получить задание' onclick='location="main.php?rz=1&add_quest=1"' />
			<?
		} else {
			echo 'Получить новое задание можно <b>'.date('d.m.Y H:i',$hgo['time']+60*60*24).'</b> <font color="">( Через '.$u->timeOut($hgo['time']+60*60*24-time()).' )</font>';
		}
		?>
		</span>
		</FIELDSET>
	</form>
	<br />
	<? 
	//Начисление бонуса награды
	if( isset( $_GET['buy1'] ) ) {
		$rt = 1;
		if( $_GET['buy1'] == 1 ) {
			//покупаем статы
			$price = 2000+($u->rep['add_stats']*100);
			$cur_price = array('price'=>0);
			if( 25 - $u->rep['add_stats'] > 0 && $u->rep['allrep'] - $u->rep['allnurep'] >= $price ) { // Характеристики!
				foreach( $dungeon['list'] as $key => $val ) {
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['ability']  += 1; $u->rep['add_stats'] += 1;
						mysql_query('UPDATE `rep` SET `add_stats` = "'.$u->rep['add_stats'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `ability` = "'.$u->info['ability'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>Вы успешно приобрели 1 способность за '.$price.' ед. награды</b></font><br>';
					} else {
						echo '<font color="red"><b>Ничего не получилось...</b></font><br>';
					}
				} else echo 'Недостаточно репутации.';
			} else {
			   echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			}
		} elseif( $_GET['buy1'] == 2 ) { // Умения!
			$price = 10000;
			$cur_price = array('price'=>0); 
			if(10-$u->rep['add_skills']>0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // Умения!
				foreach($dungeon['list'] as $key=>$val){
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['skills']  += 1; $u->rep['add_skills'] += 1;
						mysql_query('UPDATE `rep` SET `add_skills` = "'.$u->rep['add_skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `skills` = "'.$u->info['skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>Вы успешно приобрели 1 умение за '.$price.' ед. награды</b></font><br>';
					} else {
						echo '<font color="red"><b>Ничего не получилось...</b></font><br>';
					}
				} else echo 'Недостаточно репутации.';
			} else {
				echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			}
		} elseif( $_GET['buy1'] == 3 ) { // Кредиты
			$price = 100;
			$cur_price = array('price'=>0); 
			if( $u->rep['allrep'] - $u->rep['allnurep'] >= $price) { // Покупаем кредиты
				foreach($dungeon['list'] as $key=>$val){
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['money']  += 10; $u->rep['add_money'] += 10;
						mysql_query('UPDATE `rep` SET `add_money` = "'.$u->rep['add_money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>Вы успешно приобрели 10 кр. за '.$price.' ед. награды</b></font><br>';
					} else {
						echo '<font color="red"><b>Ничего не получилось...</b></font><br>';
					}
				} else echo 'Недостаточно репутации.';
			}else{
				echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			}
		} elseif( $_GET['buy1'] == 4 ) { // Особенности
			$price = 3000;
			$cur_price = array('price'=>0);
			if( 5 - $u->rep['add_skills2'] > 0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // Особенности
				foreach($dungeon['list'] as $key=>$val){
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['sskills']  += 1; $u->rep['add_skills2'] += 1;
						mysql_query('UPDATE `rep` SET `add_skills2` = "'.$u->rep['add_skills2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `sskills` = "'.$u->info['sskills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>Вы успешно приобрели 1 особенность за '.$price.' ед. награды</b></font><br>';
					} else {
						echo '<font color="red"><b>Ничего не получилось...</b></font><br>';
					}
				} else echo 'Недостаточно репутации.';
				
			} else {
				echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			}
	  	}
	}
	?>
	<fieldset>
        <legend>Награда: <b> <? echo ( isset( $rt ) && $rt == 1 ? ($u->rep['allrep']-$u->rep['allnurep'])-$cur_price['price'] : ($u->rep['allrep']-$u->rep['allnurep']) );?>
        ед.</b></legend>
        <table>
			<tr>
				<td>Способность (еще <?=(25-$u->rep['add_stats'])?>)</td>
				<td style='padding-left: 10px'>за <?=2000+($u->rep['add_stats']*100);?> ед.</td>
				<td style='padding-left: 10px'><input type='button' value='Купить'
	  onclick="if (confirm('Купить: Способность?\n\nКупив способность, Вы сможете увеличить характеристики персонажа.\nНапример, можно увеличить силу.')) {location='main.php?rz=1&buy1=1'}" /></td>
			</tr>
			<tr>
				<td>Умение (еще <?=(10-$u->rep['add_skills'])?>)</td>
				<td style='padding-left: 10px'>за 10000 ед.</td>
				<td style='padding-left: 10px'><input type='button' value='Купить'
	  onclick="if (confirm('Купить: Умение?\n\nУмение даёт возможность почуствовать себя мастером меча, топора, магии и т.п.')) {location='main.php?rz=1&buy1=2'}" /></td>
			</tr>
			<tr>
				<td>Деньги (10 кр.)</td>
				<td style='padding-left: 10px'>за 100 ед.</td>
				<td style='padding-left: 10px'><input type='button' value='Купить'
	  onclick="if (confirm('Купить: Деньги (10 кр.)?\n\nНаграду можно получить полновесными кредитами.')) {location='main.php?rz=1&buy1=3'}" /></td>
			</tr>
			<tr>
				<td>Особенность (еще <?=(5-$u->rep['add_skills2'])?>)</td>
				<td style='padding-left: 10px'>за 3000 ед.</td>
				<td style='padding-left: 10px'><input type='button' value='Купить'
	  onclick="if (confirm('Купить: Особенность?\n\nОсобенность - это дополнительные возможности персонажа, не дающие преимущества в боях.\nНапример, можно увеличить скорость восстановления HP')) {location='main.php?rz=1&buy1=4'}" /></td>
			</tr>
        </table>
	</fieldset>
        <? 
		$chk = mysql_fetch_array(mysql_query('SELECT COUNT(`u`.`id`),SUM(`m`.`price1`) FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `u`.`item_id` = `m`.`id` WHERE `m`.`type` = "61" AND `u`.`delete` = "0" AND `u`.`inOdet` = "0" AND `u`.`inShop` = "0" AND `u`.`inTransfer` = "0" AND `u`.`uid` = "'.$u->info['id'].'" LIMIT 1000'));
		if(isset($_GET['buy777']) && $chk[0]>0) {
			?>
	<fieldset style='margin-top:15px;'>
		<p><span style="padding-left: 10px">
			<?
			$chk_cl = mysql_query('SELECT `u`.`id`,`m`.`price1` FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `u`.`item_id` = `m`.`id` WHERE `m`.`type` = "61" AND `u`.`delete` = "0" AND `u`.`inOdet` = "0" AND `u`.`inShop` = "0" AND `u`.`inTransfer` = "0" AND `u`.`uid` = "'.$u->info['id'].'" LIMIT 1000');
			while($chk_pl = mysql_fetch_array($chk_cl)) {
				if(mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$chk_pl['id'].'" LIMIT 1'));
				{ 
					$x++; $prc += $chk_pl['price1'];
				}
			}
			$u->info['money'] += $prc;
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			echo '<font color="red"><b>Вы успешно сдали чеки в количестве '.$x.' шт. на сумму '.$prc.' кр.</b></font><br>'; 
			$chk[0] = 0;
		?>
        </span></p>
      </fieldset><?
		}
		if($chk[0]>0) {
		?>
          <input type='button' value='Сдать чеки'
onclick="if (confirm('Сдать все чеки (<?=$chk[0]?> шт.) находящиеся у Вас в инвентаре за <?=$chk[1]?> кр. ?')) {location='main.php?rz=1&buy777=1'}" />
		<? } ?>
	  <fieldset style='margin-top:15px;'>
		<table>
		<?
			foreach($dungeon['list'] as $key=>$val){
				//if( $u->rep['rep'.$val] > 0 ) {
					echo '<tr>
						<td width="200">Репутация в '.ucfirst(str_replace('city',' city',$val)).':</td>
						<td>'.$u->rep['rep'.$val].' ед. </td>
					</tr>';
				//}
			}
		?> 
        </table>
        <legend>Текущая репутация:</legend> 
      </fieldset>
</div>
<?
	} else {
		if($dungeonGo == 1){
			if($u->info['dn']==0){
			?>
			<table width="400" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
						<form id="from" autocomplete="0" name="from" action="main.php?pz1=<? echo $code; ?>" method="post">
							<fieldset style='padding-left: 5; width=50%'>
							<legend><b> Группа </b> </legend>                                
                                Выберите пещеру:<hr />
                              <label>
                                <input type="radio" name="rpmg1" id="rpmg1" checked="checked" value="1" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/capitalcity.gif"
                                 width="34" height="19" /> Пещера Тысячи Проклятий</label><br />
                                
                                <label><input type="radio" name="rpmg1" id="rpmg1" value="2" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/angelscity.gif"
                                 width="34" height="19" /> Бездна</label> <small><font color=red>(Ведутся работы)</font></small><br />
                                 
                                <label><input disabled="disabled" type="radio" name="rpmg1" id="rpmg1" value="3" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/sandcity.gif"
                                 width="34" height="19" /> <font color="#CCCCCC">Пещеры Мглы</font></label><br />
                                 
                                <label><input disabled="disabled" type="radio" name="rpmg1" id="rpmg1" value="4" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/demonscity.gif"
                                 width="34" height="19" /> <font color="#CCCCCC">Катакомбы</font></label><br />
                                 
                                <label><input disabled="disabled" type="radio" name="rpmg1" id="rpmg1" value="5" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/emeraldscity.gif"
                                 width="34" height="19" /> <font color="#CCCCCC">Потерянный вход</font></label><br />
                                 
                                <label><input disabled="disabled" type="radio" name="rpmg1" id="rpmg1" value="6" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/suncity.gif"
                                 width="34" height="19" /> <font color="#CCCCCC">Грибница</font></label>
                                <br />
                                 
                                <label><input disabled="disabled" type="radio" name="rpmg1" id="rpmg1" value="7" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/devilscity.gif"
                                 width="34" height="19" /> <font color="#CCCCCC">Туманные Низины</font></label>
                                
                                <br /><hr />
                                Комментарий
							  	<input autocomplete="off" type="text" name="text" maxlength="40" size="40" />
								<br />
								Пароль
								<input autocomplete="off" type="password" name="pass" maxlength="25" size="25" />
								<br />
								<input type="submit" name="add" value="Создать группу" />
								&nbsp;<br />
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
			<?
			} else {
				$psh_start = '';
				if(isset($zv['id'])){
					if($zv['uid']==$u->info['id']){
						$psh_start = '<INPUT type=\'button\' name=\'start\' value=\'Начать\' onClick="top.frames[\'main\'].location = \'main.php?start=1&rnd='.$code.'\'"> &nbsp;';
					}
					echo '<br><FORM autocomplete="off" id="REQUEST" method="post" style="width:210px;" action="main.php?rnd='.$code.'">
					<FIELDSET style=\'padding-left: 5; width=50%\'>
					<LEGEND><B> Группа </B> </LEGEND>
					'.$psh_start.'
					<INPUT type=\'submit\' name=\'leave\' value=\'Покинуть группу\'> 
					</FIELDSET>
					</FORM>';
				}
			}
		}else{
			echo 'Поход в пещеры разрешен один раз в три часа. Осталось еще: '.$u->timeOut(60*60*3-time()+$dungeon_timeout['time']).'<br><small style="color:grey">Но Вы всегда можете приобрести ключ от прохода у любого &quot;копателя пещер&quot; в Торговом зале ;)</small>';
			if( $dungeon['id'] == 104 ) {
				echo '<hr>Вы можете посетить подземелье без ожидания: <button  onClick="if(confirm(\'Вы уверены что хотите заплатить '.($u->info['level'] * 5).' зубов?\')){ location.href = \'/main.php?freego=1\'; }" class="btnnew">Снять задержку за '.$u->zuby(($u->info['level'] * 5),1).'</button>';
			}
		}
	}
}
?>
