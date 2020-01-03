<?
if(!defined('GAME')){
	die();
}

if($u->room['file']=='dungeon'){
$pd = array(
	1 =>0,
	2 =>0,
	3 =>0,
	4 =>0,
	5 =>0,
	6 =>0,
	7 =>0,
	8 =>0,
	9 =>0, //передняя стенка, в 2-х шагах
	10=>0,
	11=>0,
	12=>0,
	13=>0,
	14=>0,
	15=>0,
	16=>0,
	17=>0,
	18=>0,
	19=>0,
	20=>0,
	21=>0,
	22=>0,
	23=>0,
	/* Растояние: 1 шаг */
	24=>0, //стена прямо слева от персонажа (1)
	25=>0, //стена прямо справа от персонажа (1)
	26=>0, //стена прямо перед персонажем (1)
	27=>0, //стена слева от персонажа (1)
	28=>0  //стена справа от персонажа (1)
);

if(isset($_POST['go_to_admin']) && $u->info['admin'] == 1) {
  mysql_query('UPDATE `stats` SET `x` = "'.round((int)$_POST['g__x']).'", `y` = "'.round((int)$_POST['g__y']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
  if($_POST['g__restorebot']) {
	mysql_query('UPDATE `dungeon_bots` SET `delete` = "0" WHERE `dn` = "'.$u->info['dnow'].'" AND `delete` = 1 LIMIT 50');
  }
  header('Location: /main.php');
}

if(isset($_GET['back'])) {
	$dp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_now` WHERE `id` = "'.$u->info['dnow'].'" LIMIT 1'));
	$dp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$dp['id2'].'" AND `x` = "'.$u->info['x'].'" AND `y` ="'.$u->info['y'].'" LIMIT 1'));
	if( $dp['file']!='0' ) {
		$file = explode('=',$dp['file']);
		if(isset($file[1])){
			if($file[3]<1 || $file[3]>4){
				$file[3] = 1;	
			}
			mysql_query('UPDATE `stats` SET `x` = "'.$file[1].'",`y` = "'.$file[2].'",`s` = "'.$file[3].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['x'] = $file[1];
			$u->info['y'] = $file[2];
			$u->info['s'] = $file[3];
		}
	}
	unset($dp);
}

include('_incl_data/class/__dungeon.php');

if(!isset($d->info['id'])) {
  $_GET['exitd'] = true;
}

$dies = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$u->info['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `vars` = "die" LIMIT 1'));
$dies = $dies[0];

$d->point = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$d->info['id2'].'" AND `x` = "'.$u->info['x'].'" AND `y` ="'.$u->info['y'].'" LIMIT 1'));

if(!isset($d->point['id'])) {
  $d->point['css'] = 'css';	
}

if(isset($_GET['new_leader'])) {
  echo $d->n_lead($_GET['new_leader'], $u->info['id']);
}

if(isset($_GET['go_from_psh'])) {
  echo $d->go_to_hell($_GET['go_from_psh'], $u->info['id']);
}


if($u->info['dnow']==0){
	//выкидываем из пещеры
	die('Ошибки инициализации');
}else{
	
	if($d->info['id2'] == 15) {
		//
		$sb = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `item_id` = 4910 LIMIT 1'));
		//
		if(isset($_GET['atackpuck'])) {
			//Атакуем!
			$shbtu = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`battle` FROM `users` WHERE `id` = "'.mysql_real_escape_string($_GET['atackpuck']).'" LIMIT 1'));
			if(isset($shbtu['id'])) {
				$shbts = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`y`,`dnow` FROM `stats` WHERE `id` = "'.$shbtu['id'].'" LIMIT 1'));
				if($shbts['dnow'] == $u->info['dnow']) {
					$tm11 = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$u->info['id'].'" LIMIT 1'));
					$tm22 = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$shbtu['id'].'" LIMIT 1'));
					if($tm11['team'] == $tm22['team']) {
						$d->error = 'Вы не можете атаковать игрока из своей команды!';
					}elseif($shbts['x'] == $u->info['x'] || $shbts['x'] == $u->info['x']-1 || $shbts['x'] == $u->info['x']+1) {
						if($shbts['x'] == $u->info['x'] || $shbts['x'] == $u->info['x']-1 || $shbts['x'] == $u->info['x']+1) {
							//
							if($shbtu['battle'] > 0) {
								$d->error = 'Вмешиваемся в бой против &quot;'.$shbtu['login'].'&quot;!';
							}else{
								$d->error = 'Атакуем &quot;'.$shbtu['login'].'&quot; прямо сейчас!';
							}
							//
						}else{
							$d->error = '&quot;'.$shbtu['login'].'&quot; находится далеко от вас для паса!';
						}
					}else{
						$d->error = '&quot;'.$shbtu['login'].'&quot; находится далеко от вас для паса!';
					}
				}else{
					$d->error = 'Игрок не найден на хоккейном поле...';
				}
			}else{
				$d->error = 'Игрок не найден на хоккейном поле!';
			}
		}elseif(isset($_GET['addpuck']) && isset($sb['id'])) {
			//Передаем шайбу
			$shbtu = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`battle` FROM `users` WHERE `id` = "'.mysql_real_escape_string($_GET['addpuck']).'" LIMIT 1'));
			if(isset($shbtu['id'])) {
				$shbts = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`y`,`dnow` FROM `stats` WHERE `id` = "'.$shbtu['id'].'" LIMIT 1'));
				if($shbts['dnow'] == $u->info['dnow']) {
					$tm11 = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$u->info['id'].'" LIMIT 1'));
					$tm22 = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$shbtu['id'].'" LIMIT 1'));
					if($tm11['team'] != $tm22['team']) {
						$d->error = 'Вы не можете дать пас сопернику!';
					}elseif($shbts['x'] == $u->info['x'] || $shbts['x'] == $u->info['x']-1 || $shbts['x'] == $u->info['x']+1) {
						if($shbts['x'] == $u->info['x'] || $shbts['x'] == $u->info['x']-1 || $shbts['x'] == $u->info['x']+1) {
							//
							if($shbtu['battle'] > 0) {
								$d->error = '&quot;'.$shbtu['login'].'&quot; находится в конфликте с соперником!';
							}else{
								mysql_query('UPDATE `items_users` SET `uid` = "'.$shbtu['id'].'" WHERE `id` = "'.$sb['id'].'" LIMIT 1');
								unset($sb);
								$d->error = '&quot;'.$shbtu['login'].'&quot; получает пас и ведёт шайбу!';
								$d->sys_chat('<b>'.$u->info['login'].'</b> передаем пас игроку <b>'.$shbtu['login'].'</b>!');
							}
							//
						}else{
							$d->error = '&quot;'.$shbtu['login'].'&quot; находится далеко от вас для паса!';
						}
					}else{
						$d->error = '&quot;'.$shbtu['login'].'&quot; находится далеко от вас для паса!';
					}
				}else{
					$d->error = 'Игрок не найден на хоккейном поле...';
				}
			}else{
				$d->error = 'Игрок не найден на хоккейном поле!';
			}
		}
		//Каток
		$tm1win = mysql_fetch_array(mysql_query('SELECT SUM(`win`) FROM `katok_now` WHERE `team` = 1'));
		$tm2win = mysql_fetch_array(mysql_query('SELECT SUM(`win`) FROM `katok_now` WHERE `team` = 2'));
		$tm1win = 0+$tm1win[0];
		$tm2win = 0+$tm2win[0];
		$tmwin = 0;
		if($tm1win >= 2) {
			$tmwin = 1;
		}elseif($tm2win >= 2) {
			$tmwin = 2;
		}
		if($tmwin > 0) {
			$sp = mysql_query('SELECT * FROM `katok_now`');
			while( $pl = mysql_fetch_array($sp) ) {
				//Портируем персонажа обратно
				mysql_query('UPDATE `users` SET `inUser` = 0, `room` = 409 WHERE `inUser` = "'.$pl['clone'].'" LIMIT 1');

				//Удаляем текущего бота и инвентарь
				mysql_query('DELETE FROM `users` WHERE `id` = "'.$pl['clone'].'" LIMIT 1');
				mysql_query('DELETE FROM `stats` WHERE `id` = "'.$pl['clone'].'" LIMIT 1');
				mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pl['clone'].'"');
				mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pl['clone'].'"');
				
				//
				mysql_query('DELETE FROM `katok_now` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				//
				if( $pl['team'] == $tmwin ) {
					//Выдаем награду за победу!
					
				}
				//
			}
			header('location: http://xcombats.com/main.php');
			die('Матч закончился! Победила команда №'.$tmwin.'');
		}
	}
	
	if($d->info['bsid']==0 && $d->info['id2'] != 106){ 
		if(isset($_GET['exitd'])) {
			//Удаляем обьекты и т.д. из старых пещер
			$rb = 321; // Магический портал
			if($u->info['room']==304){
				$rb = 209; // Вход в ледяную пещеру
			}elseif($u->info['room']==396){
				$rb = 395; // Канализация (Ангелс)
			}elseif($u->info['room']==398){
				$rb = 397; // Шахты (Ангелс)
			}elseif($u->info['room']==405){
				$rb = 321; // Все пещеры
			}elseif($d->info['id2']==3){
				$rb = 293; // Вход в Катакомбы
			}elseif($d->info['id2']==1){
				$rb = 188; // Вход в Канализацию
			}elseif($d->info['id2']==13){
				$rb = 275; // Гора Легиона
			}elseif($d->info['id2']==12){
				$rb = 372; // Вход в Пещеру Тысячи Проклятий
			}elseif($d->info['id2']==101){
				$rb = 242; // Вход в Бездну
			}elseif($d->info['id2']==104){
				$rb = 2; // Вход в Шахты (зал воинов)
			}
			$sp = mysql_query('SELECT * FROM `dungeon_now` WHERE `time_finish` = "0" LIMIT 50');
			while($pl = mysql_fetch_array($sp)) {
				$cn = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dnow` = "'.$pl['id'].'" LIMIT 1'));
				if(!isset($cn['id'])) {
					mysql_query('DELETE FROM `dungeon_bots` WHERE `dn` = "'.$pl['id'].'" AND `for_dn` = "0"');
					mysql_query('DELETE FROM `dungeon_obj` WHERE `dn` = "'.$pl['id'].'" AND `for_dn` = "0"');
					mysql_query('DELETE FROM `dungeon_items` WHERE `dn` = "'.$pl['id'].'" AND `for_dn` = "0"');
					mysql_query('DELETE FROM `dungeon_bots` WHERE `dn` = "'.$pl['id'].'" AND `for_dn` = "0"');
					mysql_query('DELETE FROM `dungeon_actions` WHERE `dn` = "'.$pl['id'].'"');
					mysql_query('UPDATE `dungeon_now` SET `time_finish` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
			}
			$cn = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dnow` = "'.$d->info['id'].'" AND `id` != "'.$u->info['id'].'" ORDER BY `exp` DESC LIMIT 1'));
			if(isset($cn['id'])) {
				if( $d->info['uid'] == $u->info['id'] ) {
					$cn = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`sex` FROM `users` WHERE `id` = "'.$cn['id'].'" LIMIT 1'));
					mysql_query('UPDATE `dungeon_now` SET `uid` = "'.$cn['id'].'" WHERE `id` = "'.$d->info['id'].'" LIMIT 1');	
					if( $cn['sex'] == 0 ) {
						if( $u->info['sex'] == 0 ) {
							$d->sys_chat('<b>'.$u->info['login'].'</b> покинул подземелье, новым лидером группы стал <b>'.$cn['login'].'</b>');
						}else{
							$d->sys_chat('<b>'.$u->info['login'].'</b> покинула подземелье, новым лидером группы стал <b>'.$cn['login'].'</b>');
						}
					}else{
						if( $u->info['sex'] == 0 ) {
							$d->sys_chat('<b>'.$u->info['login'].'</b> покинул подземелье, новым лидером группы стала <b>'.$cn['login'].'</b>');
						}else{
							$d->sys_chat('<b>'.$u->info['login'].'</b> покинула подземелье, новым лидером группы стала <b>'.$cn['login'].'</b>');
						}
					}
				}else{
					if( $u->info['sex'] == 0 ) {
						$d->sys_chat('<b>'.$u->info['login'].'</b> покинул подземелье!');
					}else{
						$d->sys_chat('<b>'.$u->info['login'].'</b> покинула подземелье!');
					}
				}
			}
			
			$city = mysql_fetch_assoc(mysql_query('SELECT `id`, `city` FROM `room` WHERE `id` = "'.$rb.'" LIMIT 1')); 
			mysql_query('UPDATE `stats` SET `dnow` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('UPDATE `users` SET `room` = "'.$rb.'", `city`="'.$city['city'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			//удаляем все предметы которые пропадают после выхода из пещеры
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `dn_delete` = "1" LIMIT 1000');
			
            mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = "1189" OR `item_id` = "4447" OR `item_id` = "1174") LIMIT 1000');
            
            header('location: main.php');
			die();
		}
	}
}

if( $d->point['fileadd']==1 && $d->point['file']!='0' && $d->point['file']!=''){
	$file = explode('=',$d->point['file']);
	if( file_exists('modules_data/location/'.$file[0]) ) {
		$information = '';
		include_once('modules_data/location/'.$file[0]);
		#header('Location: /main.php');
	} else {
		if( $file[3]<1 || $file[3]>4 ) {
			$file[3] = 1;	
		}
		echo '<br><br><center>Локация &quot;'.str_replace('.php','',$file[0]).'&quot; не определена, вернуться <a href="main.php?rnd='.$code.'">назад</a></center>';
		mysql_query('UPDATE `stats` SET `x` = "'.$file[1].'",`y` = "'.$file[2].'",`s` = "'.$file[3].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');	
	}
}

if( $d->point['fileadd']==0 && $d->point['file']!='0' && $d->point['file']!=''){
	$file = explode('=',$d->point['file']);
	if( file_exists('modules_data/location/'.$file[0]) ) {
		include('modules_data/location/'.$file[0]);
	} else {
		if( $file[3]<1 || $file[3]>4 ) {
			$file[3] = 1;	
		}
		echo '<br><br><center>Локация &quot;'.str_replace('.php','',$file[0]).'&quot; не определена, вернуться <a href="main.php?rnd='.$code.'">назад</a></center>';
		mysql_query('UPDATE `stats` SET `x` = "'.$file[1].'",`y` = "'.$file[2].'",`s` = "'.$file[3].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');	
	}
} else {
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
<? if(!isset($_GET['ajax'])) { ?>
$('body').keydown(function( event ) {
  if( event.which == 38 || event.which == 87  ) {
  	 goToLoca(1,'вперед');
  }else if( event.which == 37 || event.which == 65  ) {
  	 goToLoca(4,'налево');
  }else if( event.which == 39 || event.which == 68  ) {
  	 goToLoca(3,'направо');
  }else if( event.which == 40 || event.which == 83  ) {
  	 goToLoca(2,'вниз');
  }
  if( event.which == 81  ) {
  	 Fast(1);
  }else if( event.which == 69  ) {
	  Fast(2);
  }
});
<? } ?>

function dialogMenu(id,atk,talk,look,take,e,tmkt)
{
	var d = document.getElementById('deMenu');
	if(d!=undefined)
	{
		if(e == undefined)
    	{
       		e = window.e;
			
    	}
		d.innerHTML = '';
		var t = '';
		if(talk>0)
		{
			t += '<a href="main.php?talk='+talk+'&rnd=<? echo $code; ?>">Диалог</a><br>';
		}
		if(atk==1)
		{
			if( tmkt == 0 ) {
				t += '<a href="main.php?atack='+id+'&rnd=<? echo $code; ?>">Напасть</a><br>';
			}else{
				if( tmkt == 1 ) {
					//кенты
					<? if(isset($sb['id'])) { ?>
					t += '<a href="main.php?addpuck='+id+'&rnd=<? echo $code; ?>">Передача шайбы</a><br>';
					<? }else{ ?>
					t += '<i>Нет действий</i>';
					<? } ?>
				}else{
					//фраера
					t += '<a href="main.php?atack='+id+'&rnd=<? echo $code; ?>">Напасть</a><br>';
				}
			}
			
		}
		if(look==1)
		{
			t += 'Просмотр<br>';
		}
		if(take==1)
		{
			t += 'Поднять<br>';
		}
		d.innerHTML = t+'<small style="float:right;"><button style="border: solid 1pt #B0B0B0; font-family: MS Sans Serif; font-size: 10px; color: #191970; MARGIN-BOTTOM: 2px; MARGIN-TOP: 1px;" type="button" onClick="exitDem();">x</button></center>';
		d.style.display = '';
		if(e.x == undefined)
		{
			e.x = e.clientX;
			e.y = e.clientY;
		}
		d.style.top = e.y+'px';				
		if(e.x>320)
		{
			d.style.right = (document.body.offsetWidth-e.x)+'px';
		}else{
			d.style.right = (-e.x+540)+'px';
		}
	}
}

function exitDem()
{
	var d = document.getElementById('deMenu');
	if(d!=undefined)
	{
		d.innerHTML = '';
		d.style.display = 'none';
		d.style.top = '0px';
		d.style.right = '0px';
	}
}

var objects = {};
//i:{id,login,mapPoint,sex,obraz,type,users_p},
var users   = {<? echo $d->genUsers(); ?>};
var objs   = {<? echo $d->genObjects(); ?>};
var items   = {};
var actions = {};
var dsee = <? echo 0+$d->gs; ?>;
var mapp = {1:'0_0f',2:'0_0f',3:'0_0f',4:'1_1f',5:'1_1f',6:'1_1f'
		   ,7:'2_1f',8:'2_1f',9:'2_1f'
		   ,11:'3_1l',12:'3_1f',13:'3_1r'}
var zmap = {5:894,8:0,12:0}
var zfloor0 = {1:'',2:'',3:'',4:'',5:''};
function genMap()
{
	var i = 0, m = false, mz = false;
	while(i<users['count'])
	{
		if(users[i]!=undefined)
		{
			mz = mapp[users[i][2]];		
			if(document.getElementById(mz)!=undefined)
			{
				m = document.getElementById(mz);
				m.innerHTML = addUser(users[i],mz)+m.innerHTML;
			}
		}
		i++;
	}
	var i = 0, m = false, mz = false;
	while(i<objs['count'])
	{
		if(objs[i]!=undefined)
		{
			mz = mapp[objs[i][2]];	
			if(objs[i][5]==dsee && (objs[i][2]==5 || objs[i][2]==2 || objs[i][2]==8 || objs[i][2]==12 || objs[i][2]==15))
			{
				mz = mapp[objs[i][2]-3];
			}
			if(document.getElementById(mz)!=undefined)
			{
				m = document.getElementById(mz);
				m.innerHTML = addObj(objs[i],mz)+m.innerHTML;
			}
		}
		i++;
	}
	var i = 5;
	while(i>=1)
	{
		if(zfloor0[i]!='')
		{
			document.getElementById('Floor0').innerHTML += zfloor0[i];
		}
		i--;
	}
}
var dConfig={2:{1:{'top':54,'left':140,'w':80,'h':147},2:{'top':56,'left':92,'w':75,'h':137},3:{'top':51,'left':186,'w':75,'h':137},4:{'top':49,'left':165,'w':80,'h':147},5:{'top':49,'left':105,'w':80,'h':147},6:{'top':53,'left':140,'w':80,'h':147},7:{'top':53,'left':87,'w':80,'h':147},8:{'top':53,'left':190,'w':80,'h':147}},3:{1:{'top':60,'left':152,'w':53,'h':97},2:{'top':58,'left':110,'w':53,'h':97},3:{'top':58,'left':188,'w':53,'h':97},4:{'top':61,'left':168,'w':53,'h':97},5:{'top':61,'left':128,'w':53,'h':97},6:{'top':62,'left':153,'w':53,'h':97},7:{'top':62,'left':113,'w':53,'h':97},8:{'top':62,'left':193,'w':53,'h':97}},4:{1:{'top':70,'left':158,'w':35,'h':64},2:{'top':68,'left':125,'w':35,'h':64},3:{'top':68,'left':193,'w':35,'h':64},4:{'top':71,'left':173,'w':35,'h':64},5:{'top':71,'left':137,'w':35,'h':64},6:{'top':73,'left':158,'w':35,'h':64},7:{'top':73,'left':129,'w':35,'h':64},8:{'top':73,'left':193,'w':35,'h':64}}}
var dConfigObj = {
	1: {
		    0: {
				'top':65,
				'left':110,
				'w':1,
				'h':1	
			}
	}
	,2: {
		    0: {
				'top':65,
				'left':110,
				'w':0.65,
				'h':0.65	
			}
	},
	3: {
		    0: {
				'top':65,
				'left':110,
				'w':0.48,
				'h':0.48
			}
	},
	4: {
		    0: {
				'top':65,
				'left':110,
				'w':0.35,
				'h':0.35
			}
	}
}

var prob = { 0: {1:1, 2:0.25, 3:-0.10, 4:-0.38}, 1: {1:0.90, 2:0.50, 3:0.23, 4:0.05} };

function addObj(v, mz) {
  var r = '', rz = 0;

  if(v[2] >= 1 && v[2] <= 3) { rz = 1; }
  if(v[2] >= 4 && v[2] <= 6) { rz = 2; }
  if(v[2] >= 7 && v[2] <= 9) { rz = 3; }
  if(v[2] >= 10 && v[2] <= 14) { rz = 4; }
  if(v[2] >= 15 && v[2] <= 19) { rz = 5; }	

  if(v[5] == dsee) {
	rz -= 1;
  }

  if(dConfigObj[rz] != undefined && dConfigObj[rz][v[6]] != undefined) {
	new_w = v[7]*dConfigObj[rz][v[6]]['w'];
	new_h = v[8]*dConfigObj[rz][v[6]]['h'];
	new_left = dConfigObj[rz][v[6]]['left']-Math.round((v[7]*prob[0][rz])/4);
	new_top = dConfigObj[rz][v[6]]['top']-Math.round((v[8]*prob[1][rz])/4);	
	if(v[2] == 6) { new_left += 195; new_top -= 5; }
	if(v[2] == 4) { new_left -= 195; new_top -= 5; }
	if(v[2] == 9) { new_left -= 140; new_top -= 2; }
	if(v[2] == 7) { new_left += 140; new_top -= 2; }
	if(v[2] == 13) { new_left += 100; new_top -= 1; }
	if(v[2] == 11) { new_left -= 100; new_top -= 0; }

	if(v[9] != 0) {
	  new_left += Math.round(new_left/(100+(rz-1)*10)*v[9]+rz*0.25);
	}

	if(v[10] != 0) {
	  new_top += Math.round(new_h/2+new_top/(100+(rz-1)*50)*v[10]-rz*3.3);
	}	

	if(rz == 4) {
	  new_top += 3;
	}

	if(v[11]!=0) {
	  if(v[11]['t'+rz] != undefined) {
		new_top += v[11]['t'+rz];
	  }
	  
	  if(v[11]['l'+rz] != undefined) {
		new_left += v[11]['l'+rz];
	  }

	  if(v[11]['w'+rz] != undefined) {
		new_w += v[11]['w'+rz];
	  }

	  if(v[11]['h'+rz] != undefined) {
		new_h += v[11]['h'+rz];
	  }

	  if(v[11]['rt'+rz] != undefined) {
		new_top = v[11]['rt'+rz];
	  }

	  if(v[11]['rl'+rz] != undefined) {
		new_left = v[11]['rl'+rz];
	  }
	}

	if(rz>=1 && rz<=2){
	  actionNow = '';
	  if(v[11]['use'] != undefined) {
		if(v[11]['use'] == 'exit') {
		  actionNow = 'alert(\'Выход из подземелья\');';
		} else if(v[11]['use'] == 'takeit') {
		  actionNow = 'takeObj('+v[0]+')';
		}
	  }

	  zfloor0[rz] = '<img title="'+v[1]+'" onclick="'+actionNow+'" src="http://img.xcombats.com/1x1.gif" style="cursor: pointer; position: absolute; top: '+new_top+'px; left: '+new_left+'px; width: '+new_w+'px; height: '+new_h+'px;" />'+zfloor0[rz];			
	} else {
	  zfloor0[rz] = '<img title="'+v[1]+'" src="http://img.xcombats.com/1x1.gif" style="position: absolute; top: '+new_top+'px; left: '+new_left+'px; width: '+new_w+'px; height: '+new_h+'px;" />'+zfloor0[rz];
	}
	r = '<img title="obj" src="http://img.xcombats.com/i/sprites/'+v[4]+'" class="dObj" style="position: absolute; top: '+new_top+'px; left: '+new_left+'px; width: '+new_w+'px; height: '+new_h+'px;" />';
	}
	return r;
}

function addUser(v,mz){
	var r = '';
	var rz = 0; //растояние до пользователя
	if(v[2]>=1 && v[2]<=3){ rz = 1; }
	if(v[2]>=4 && v[2]<=6){ rz = 2; }
	if(v[2]>=7 && v[2]<=9){ rz = 3; }
	if(v[2]>=10 && v[2]<=14){ rz = 4; }
	if(v[2]>=15 && v[2]<=19){ rz = 5; }	
	if(dConfig[rz]!=undefined && dConfig[rz][v[6]]!=undefined){
		new_w = dConfig[rz][v[6]]['w'];
		new_h = dConfig[rz][v[6]]['h'];
		new_left = dConfig[rz][v[6]]['left'];
		new_top = dConfig[rz][v[6]]['top'];	
		if(v[2]==6){ new_left += 215; new_top -= 5; }
		if(v[2]==4){ new_left -= 215; new_top -= 5; }
		if(v[2]==9){ new_left -= 155; new_top -= 2; }
		if(v[2]==7){ new_left += 155; new_top -= 2; }
		if(v[2]==13){ new_left += 115; new_top -= 1; }
		if(v[2]==11){ new_left -= 115; new_top -= 1; }
		if(v[2]>=11 && v[2]<=13){
			new_top += 5;
		}
		if(rz>=1 && rz<=2){
			action = '';
			if(v[5]=='bot' || <?=$d->info['bsid'];?> > 0){
				action = 'dialogMenu('+v[0]+','+v[8]+','+v[7]+',0,0,event,'+v[9]+');';
			}
			zfloor0[rz] += '<img title="'+v[1]+'" onClick="'+action+'" src="http://img.xcombats.com/1x1.gif" style="cursor:pointer;position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />';			
		}else{
			zfloor0[rz] += '<img title="'+v[1]+'" src="http://img.xcombats.com/1x1.gif" style="position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />';
		}
		r = '<img title="user" src="http://img.xcombats.com/chars/'+v[3]+'/'+v[4]+'.png" class="dUser" style="position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />';
	}
	return r;
}

var speedLoc  = 0, sLoc1 = 0, sLoc2 = 0, tgo  = 0, tgol = 0, gotoup777 = 0, gotext777 = '';

function cancelgoto() {
  document.getElementById('gotext777').innerHTML = '';
  gotoup777 = 0;
  gotext777 = '';
}

function goToLoca(id, ttl) {
  if(tgo < 1) {
	 location.href = "main.php?go="+id;
	/*$.ajax({
      url: "main.php?go="+id+"&rnd=<?=$code;?>&ajax=1",
      success: function(html) {
        $("body").html(html);
      }
    });*/
  } else {
	gotoup777 = id;
	gotext777 = ttl;
  }
}

function takeObj(objId) {
	 location.href = "main.php?take_obj="+objId;
 /* $.ajax({
    url: "main.php?take_obj="+objId+"&rnd=<?=$code;?>&ajax=1",
    cache: false,
    success: function(html) {
      $("body").html(html);
    }
  });*/
}

function takeItem(itemId) {
	location.href = "main.php?take="+itemId;
  /*$.ajax({
    url: "main.php?take="+itemId+"&rnd=<?=$code;?>&ajax=1",
    cache: false,
    success: function(html) {
      $("body").html(html);
    }
  });*/
}

function Fast(look) {
	location.href = "main.php?look="+look;
  /*$.ajax({
    url: "main.php?look="+look+"&rnd=<?=$code;?>&ajax=1",
    cache: false,
    success: function(html) {
      $("body").html(html);
    }
  });*/
}

function locGoLineDng() {
  var line = document.getElementById('pline1');
  if(line != undefined) {
	prc = 100-Math.floor(tgo/tgol*100);
	sLoc1 = 108/100*prc;
	if(sLoc1 < 1) {
	  sLoc1 = 1;
	}

	if(sLoc1 > 108) {
	  sLoc1 = 108;
	}

	line.style.width = sLoc1+'px';

	if(tgo > 0) {
	  tgo -= 1;
	  setTimeout('locGoLineDng()', 100);
	} else {
	  if(gotoup777 > 0) {
		  location.href = "main.php?go="+gotoup777;
		/*$.ajax({
          url: "main.php?go="+gotoup777+"&rnd=<?=$code;?>&ajax=1",
          success: function(html) {
            $("body").html(html);
          }
        });*/
	  }
	}
	
	
	if(gotoup777 > 0 && gotext777 != '' && document.getElementById('gotext777').innerHTML != 'Вы перейдете <b>'+gotext777+'</b> (<a href="javascript:void(0)" onclick="cancelgoto()">отмена</a>)') {
			document.getElementById('gotext777').innerHTML = 'Вы перейдете <b>'+gotext777+'</b> (<a href="javascript:void(0)" onclick="cancelgoto()">отмена</a>)';
		}else if(document.getElementById('gotext777').innerHTML != '' && gotoup777 == 0 && gotext777 == '') {
			document.getElementById('gotext777').innerHTML = '';
		}
	}
}
<?
$tmGo  = $u->info['timeGo']-time(); //сколько секунд осталось
$tmGol = $u->info['timeGo']-$u->info['timeGoL']; //сколько секунд идти всего
echo 'var tgo = '.($tmGo*10).'; var tgol = '.($tmGol*10).';'; ?>
</script>
<link href="http://img.xcombats.com/css/dungeon_<? echo $d->point['css']; ?>.css" rel="stylesheet" type="text/css" />
<style>
.hintDm {
	position:absolute;
	background-color:#E4E4E4;
	padding:5px;
	border:1px solid #999;	
	z-index:1;
	width:70px;
}
.dUser { 
	max-height:220px;
	max-width:120px;
	min-width:30px;
	min-height:55px;
	border:		0px solid;
	padding:	0px;
	margin:		0px;
}
.dObj {
	border:		0px solid;
	padding:	0px;
	margin:		0px;
}
.test1 {
	text-align: right;
}
#pline1 {
	background-image:url(http://img.xcombats.com/wait3.gif);
	height:9px;
	z-index:1000;
}
</style>
<div id="deMenu" class="hintDm" style="display:none;z-index:5000px;"></div>
<div style="margin-right:1px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<p style="float:left;">&nbsp;<? if(isset($d->error)){ echo '<font color="red">'.$d->error.'</font><br>'; } ?></p>
<? if($d->info['bsid']==0 && $d->info['id2'] != 106){ ?><p style="float:right;"><a onClick="if(confirm('Выйти из пещеры?')){ location.href = '/main.php?exitd=<? echo $code; ?>'; }" href="javascript:void()">Выйти</a></p><? } ?>
    </td>
    </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><div align="left">
              <div id="users"><? if($d->info['bsid']==0){ echo $d->usersDng();
		    if( $dies > 0 ) {
			echo '<H4>Кол-во смертей: '.$dies.'</H4>';
		    }
		}else{
		    if($d->info['id2'] != 15) {
				//Живые участники и архивариусы
		    	echo '<H4>Живые участники:</H4>';
		    	echo $d->usersDng();
			}else{
				echo '<H4>Игровой счет. Красные: <font color=red>'.(0+$tm1win).'</font> - Синие: <font color=blue>'.(0+$tm2win).'</font></H4>';
				if( $dies > 0 ) {
					echo '<H4>Кол-во смертей: '.$dies.'</H4>';
				}
				if(isset($sb['id'])) {
					echo '<H4><font color=blue>Шайба у вас, забейте её в ворота противника!</font></h4>';
				}
			}
		}
		//Генерируем список текущих квестов
		$qsee = '';
		
		$dungeon_enter = mysql_fetch_array( mysql_query('SELECT * FROM `dungeon_room` WHERE `dungeon_room` = "'.$u->info['room'].'" LIMIT 1') ); 
		$sp = mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` LIKE "%start_quest%" AND `vals` = "go" LIMIT 100');
		while($pl = mysql_fetch_array($sp)){
		    $pq = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$pl['vars']).'" AND (`tr_date` LIKE "%tr_dn:=:'.$d->info['id2'].'%" OR `tr_date` NOT LIKE "%tr_dn:=:%") LIMIT 1'));
		    if(isset($pq['id'])) {
				$qsee .= '<small>Задание: &nbsp; '.$pq['name'].' '.$q->infoDng($pq).'</small><br>';
		    	$qx++;
			}
		}
		$sp = mysql_query('SELECT * FROM `dialog_act` WHERE `uid` = "'.$u->info['id'].'" AND `val` = 0 AND `info` != ""');
		while($pl = mysql_fetch_array($sp)) {
			$xrz = $pl['now'];
			$r = $pl['max'];
			if($xrz > $r) {
				$xrz = $r;
			}
			$r = '<table style="display:inline-block;" border="0" cellspacing="0" cellpadding="0" height="10"><tr><td valign="middle" width="120" style="padding-top:12px">
  <div style="position:relative;"><div id="vhp-1234600000'.$pl['id'].'" title="Выполнение задания" align="left" class="seehp" style="position:absolute; top:-10px; width:120px; height:10px; z-index:12;"> '.$xrz.'/'.$r.'</div>
  <div title="Выполнение задания" class="hpborder" style="position:absolute; top:-10px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
  <div class="hp_3 senohp" style="height:9px; width:120px; position:absolute; top:-10px; z-index:11;" id="lhp-1234600000'.$pl['id'].'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
  <div title="Выполнение задания" class="hp_none" style="position:absolute; top:-10px; width:120px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div>
</div></td></tr></table><br><script>top.startHpRegen("main",-1234600000'.$pl['id'].','.$xrz.','.$r.',0,0,0,0,0,0,1);</script>';

			$qsee .= '<small>Задание: &nbsp; '.$pl['info'].' '.$r.'</small><br>';
		}
		if( $qsee != '' ) {
		    echo '<br><br>'.$qsee;
		}
		?></div>
              <div id="items"><? echo $d->itemsMap(); ?></div>
			  <div id="information"><? if(isset($d->information)){ echo $d->information; } ?></div>
            </div>
			<?
			if($u->info['admin'] > 0){
				#echo $d->genObjects();  
			}
			?>
			</td>
            <td width="530" height="260" valign="top" style="background-image:url(http://img.xcombats.com/maze_layer.jpg); background-repeat:no-repeat;"><div style="position:relative;">
			
              <div style="position:absolute;z-index:1;left:397px;top:0px;">
                <div id="pline1" style="width:1px;"> </div>
              </div>
              <div style="position:absolute; z-index:50; left: 374px; top: 110px;">
              	<img src="http://img.xcombats.com/podzem-map.png" />
              </div>
              <div id="minimap" style="position:absolute; left: 374px; top: 110px;">
                <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #333333">
					<?
					$i = 1;
					$htmlv = '';
					while($i<=8)
					{
						$htmlv .= '<tr>';
						$j = 1;
						$htmlv2 = '';
						while($j<=9)
						{
							$htmlv2 = '<td width="15" height="15" align="center" valign="middle" align="center" style="margin:1px;" id="min_'.($u->info['x']+(4-$j)).'_'.($u->info['y']+(4-$i)).'"></td>'.$htmlv2;
							$j++;
						}
                        $htmlv .= $htmlv2.'</tr>';
						$i++;
					}
					echo $htmlv;
					unset($htmlv,$htmlv2);
					?>
                    <style>
					.u_rot1 {
						-webkit-transform: rotate(-180deg);
						-moz-transform: rotate(-180deg);
						filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=6);
						-o-transform: rotate(60deg);
					}
					.u_rot4 {
						-webkit-transform: rotate(-90deg);
						-moz-transform: rotate(-90deg);
						filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
						-o-transform: rotate(30deg);
					}
					.u_rot3 {
						
					}
					.u_rot2 {
						-webkit-transform: rotate(-270deg);
						-moz-transform: rotate(-270deg);
						filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=9);
						-o-transform: rotate(90deg);
					}
					.dBot {
						display:inline-block; 
						height: 18px;
						width:10px;
						z-index:99;
						position:absolute;
						top: -8px;
						left: -6px;
						//background-image:url("http://img.xcombats.com/drgn/bg/r.gif");
					}
					</style>
                </table>
                  <script>
                  <?
				  //выводим мини-карту
				$i = 0;
				$uxy = array();
				if($u->room['name']!='Башня Смерти'){
					$sp = mysql_query('SELECT `u`.`login`,`u`.`id`,`s`.`x`,`s`.`y`,`s`.`s` FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `s`.`dnow` = "'.$u->info['dnow'].'" AND `u`.`id` != "'.$u->info['id'].'" LIMIT 10');
					while($pl = mysql_fetch_array($sp)){
						$uxy[$pl['x'].'_'.$pl['y']] = $pl;
					}
				}
if( $u->info['admin'] > 0 ||  $d->id_dng == 106 ) {
				$bxy = array();
				if($u->room['name']!='Башня Смерти'){
$sp = mysql_query('SELECT `db`.*, `tb`.`login`, `tb`.`obraz`, `tb`.`sex` FROM `dungeon_bots` as `db` LEFT JOIN `test_bot` as `tb` ON `tb`.`id`=`db`.`id_bot`
				  WHERE `db`.`dn` = "'.$u->info['dnow'].'" AND `db`.`delete`=0 AND
				  (`db`.`x` >= '.($u->info['x']-5).' AND `db`.`x` <= '.($u->info['x']+5).') AND
				  (`db`.`y` >= '.($u->info['y']-5).' AND `db`.`y` <= '.($u->info['y']+5).') 
				  LIMIT 100');
					while($pl = mysql_fetch_array($sp)){
						$bxy[$pl['x'].'_'.$pl['y']] = $pl;
					} 
				}
}
				
				$sp = mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$d->id_dng.'" AND (`x` >= '.($u->info['x']-5).' AND `x` <= '.($u->info['x']+5).') AND (`y` >= '.($u->info['y']-5).' AND `y` <= '.($u->info['y']+5).')  ORDER BY `y` ASC , `x` ASC LIMIT 100');
				
				$rzn = array(0=>'top',3=>'right',2=>'bottom',1=>'left');

				$sb1 = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_obj` WHERE `name` LIKE "%Шайба%" AND `dn` = "'.$d->info['id'].'" LIMIT 1'));
				
				while($pl = mysql_fetch_array($sp)) {
					$css  = '"background-image":"url(http://img.xcombats.com/fon555.png)",';
					$j = 0;
					
					while($j<=4){
						if($pl['st'][$j]==1){
							$css .= '"border-'.$rzn[$j].'":"1px solid #303030",';
						}else{
							$css .= '"margin-'.$rzn[$j].'":"1px",';
						}
						$j++;
					}
					
					if( $sb1['x'] == $pl['x'] && $sb1['y'] == $pl['y'] ) {
						echo '$("#min_'.$pl['x'].'_'.$pl['y'].'").html("<img class=\"u_rot'.$u->info['s'].'\" title=\"Шайба!\" style=\"margin:2px 3px 3px 2px;background-image:url(http://img.xcombats.com/i/move/shb.gif)\" src=\"http://img.xcombats.com/1x1.gif\" width=\"7\" height=\"7\">");';
					}elseif( $u->info['x'] == $pl['x'] && $u->info['y'] == $pl['y'] ) {
						if($d->info['id2'] == 15 ) {
							$tmbth = 4;
							$tm = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$u->info['id'].'" LIMIT 1'));
							if($tm['team'] == 1) {
								$tmbth = 1;
							}elseif($tm['team'] == 2) {
								$tmbth = 2;
							}
							echo '$("#min_'.$pl['x'].'_'.$pl['y'].'").html("<img class=\"u_rot'.$u->info['s'].'\" title=\"Это Вы\" style=\"margin:2px 3px 3px 2px;background-image:url(http://img.xcombats.com/i/move/p'.$tmbth.'/d0.gif)\" src=\"http://img.xcombats.com/1x1.gif\" width=\"7\" height=\"7\">");';
						}else{
							echo '$("#min_'.$pl['x'].'_'.$pl['y'].'").html("<img class=\"u_rot'.$u->info['s'].'\" title=\"Это Вы\" style=\"margin:2px 3px 3px 2px;background-image:url(http://img.xcombats.com/i/move/p1/d0.gif)\" src=\"http://img.xcombats.com/1x1.gif\" width=\"7\" height=\"7\">");';
						}
					} elseif($d->info['id2'] == 15 ) {
						$tmbth = 4;
						$tm = mysql_fetch_array(mysql_query('SELECT * FROM `katok_now` WHERE `clone` = "'.$uxy[$pl['x'].'_'.$pl['y']]['id'].'" LIMIT 1'));
						if($tm['team'] == 1) {
							$tmbth = 1;
						}elseif($tm['team'] == 2) {
							$tmbth = 2;
						}
						$tbshin = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$uxy[$pl['x'].'_'.$pl['y']]['id'].'" AND `item_id` = 4910 AND `delete` = 0 LIMIT 1'));
						if(isset($tbshin['id'])) {
							echo '$("#min_'.$pl['x'].'_'.$pl['y'].'").html("<img class=\"u_rot'.$u->info['s'].'\" title=\"Шайба!\" style=\"margin:2px 3px 3px 2px;background-image:url(http://img.xcombats.com/i/move/shb.gif)\" src=\"http://img.xcombats.com/1x1.gif\" width=\"7\" height=\"7\">");';
						}else{
							echo '$("#min_'.$uxy[$pl['x'].'_'.$pl['y']]['x'].'_'.$uxy[$pl['x'].'_'.$pl['y']]['y'].'").html("<img class=\"u_rot'.$uxy[$pl['x'].'_'.$pl['y']]['s'].'\" title=\"'.$uxy[$pl['x'].'_'.$pl['y']]['login'].'\" style=\"margin:2px 3px 3px 2px;background-image:url(http://img.xcombats.com/i/move/p'.$tmbth.'/d0.gif)\" src=\"http://img.xcombats.com/1x1.gif\" width=\"7\" height=\"7\">");';
						}
					}elseif( isset($uxy[$pl['x'].'_'.$pl['y']]) ) {
						echo '$("#min_'.$uxy[$pl['x'].'_'.$pl['y']]['x'].'_'.$uxy[$pl['x'].'_'.$pl['y']]['y'].'").html("<img class=\"u_rot'.$uxy[$pl['x'].'_'.$pl['y']]['s'].'\" title=\"'.$uxy[$pl['x'].'_'.$pl['y']]['login'].'\" style=\"margin:2px 3px 3px 2px;background-image:url(http://img.xcombats.com/i/move/p4/d0.gif)\" src=\"http://img.xcombats.com/1x1.gif\" width=\"7\" height=\"7\">");';
					} elseif( isset($bxy[$pl['x'].'_'.$pl['y']]) ) {
						?> 
$("#min_<?=$bxy[$pl['x'].'_'.$pl['y']]['x']?>_<?=$bxy[$pl['x'].'_'.$pl['y']]['y']?>").html("<div style='position:relative; display:inline-block; width:1px; height:1px;'><img class='dBot' title='<?=$bxy[$pl['x'].'_'.$pl['y']]['login']?>'  src='http://img.xcombats.com/chars/<?=$bxy[$pl['x'].'_'.$pl['y']]['sex']?>/<?=str_replace('.gif','.png',$bxy[$pl['x'].'_'.$pl['y']]['obraz'])?>'></div>");
						<?
					}
					$css  = rtrim($css,',');
					echo '$("#min_'.$pl['x'].'_'.$pl['y'].'").css({'.$css.'});';
					$i++;
				}
				?>
				</script>
			</div>
			<div style="position:absolute; z-index:50; left: 374px; top: 110px;">
			  <img src="http://img.xcombats.com/podzem-map2.png" />
			</div>
            <img src="http://img.xcombats.com/g1.jpg" title="Обновить, X:<?=$u->info['x']?> ,Y:<?=$u->info['y']?>" width="31" height="18" id="g1" style="position: absolute; cursor:pointer; left: 435px; top: 53px;" />
			<? if($d->testGone(3) > 0) { ?>
			<img src="http://img.xcombats.com/g2.jpg" onclick="goToLoca(3,'направо');" width="27" height="48" id="g2" style="position:absolute;cursor:pointer; left: 492px; top: 40px;" />
			<? } if($d->testGone(4)>0){ ?>
			<img src="http://img.xcombats.com/g3.jpg" onclick="goToLoca(4,'налево');" width="28" height="46" id="g3" style="position:absolute;cursor:pointer; left: 382px; top: 40px;" />
			<? } if($d->testGone(2)>0){ ?>
			<img src="http://img.xcombats.com/g4.jpg" onclick="goToLoca(2,'назад');" width="45" height="25" id="g4" style="position:absolute;cursor:pointer; left: 428px; top: 72px;" />
			<? } if($d->testGone(1)>0){ ?>
			<img src="http://img.xcombats.com/g5.jpg" onclick="goToLoca(1,'вперед');" width="46" height="26" id="g5" style="position:absolute;cursor:pointer; left: 428px; top: 26px;" />
			<? } ?>
            <img src="http://img.xcombats.com/g6.jpg" width="30" height="19" id="g6" style="position:absolute;cursor:pointer; left: 399px; top: 28px;" />
		    <img src="http://img.xcombats.com/g7.jpg" width="24" height="19" id="g7" style="position:absolute;cursor:pointer; left: 476px; top: 28px;" />
			<div id="Dungeon" class="Dungeon" align="center" style="width:352px;height:240px;padding:0px;margin:10px;">
                <!-- / MAP \ -->
                <div id="Floor0" class="Floor0">
                  <div class="Floor1">
                    <div class="<? if($pd[1]==1){ echo 'LeftSide4_1'; } ?>">
                      <div class="<? if($pd[2]==1){ echo 'RightSide4_1'; } ?>">
                        <div id="4_0r" class="<? if($pd[3]==1){ echo 'RightSide4_0'; } ?>">
                          <div id="4_0l" class="<? if($pd[4]==1){ echo 'LeftSide4_0'; } ?>">
                            <div id="3_2l" class="<? if($pd[5]==1){ echo 'LeftFront3_2'; } ?>">
                              <div id="3_2r" class="<? if($pd[6]==1){ echo 'RightFront3_2'; } ?>">
                                <div class="<? if($pd[7]==1){ echo 'LeftFront3_1'; } ?>">
                                  <div class="<? if($pd[8]==1){ echo 'RightFront3_1'; } ?>">
                                    <div id="3_1l" class="<? if($pd[10]==1){ echo 'LeftFront3_1'; } ?>">
									  <div id="3_1f" class="<? if($pd[9]==1){ echo 'LeftFront3_0'; } ?>">
                                        <div id="3_1r" class="<? if($pd[11]==1){ echo 'RightFront3_1'; } ?>">
                                          <div class="<? if($pd[12]==1){ echo 'LeftSide3_0'; } ?>">
                                            <div id="3_0l" class="<? if($pd[13]==1){ echo 'RightSide3_0'; } ?>">
                                              <div id="2_1l" class="<? if($pd[14]==1){ echo 'LeftFront2_1'; } ?>">
                                                <div id="2_1r" class="<? if($pd[15]==1){ echo 'RightFront2_1'; } ?>">
                                                  <div id="2_1f" class="<? if($pd[16]==1){ echo 'LeftFront2_0'; } ?>">
                                                    <div class="<? if($pd[17]==1){ echo 'LeftSide2_0'; } ?>">
                                                      <div id="2_0l" class="<? if($pd[18]==1){ echo 'RightSide2_0'; } ?>">
                                                        <div id="1_1l" class="<? if($pd[19]==1){ echo 'LeftFront1_1'; } ?>">
                                                          <div id="1_1r" class="<? if($pd[20]==1){ echo 'RightFront1_1'; } ?>">
                                                            <div id="1_1f" class="<? if($pd[21]==1){ echo 'LeftFront1_0'; } ?>">
                                                              <div class="<? if($pd[22]==1){ echo 'LeftSide1_0'; } ?>">
                                                                <div id="1_0l" class="<? if($pd[23]==1){ echo 'RightSide1_0'; } ?>">
                                                                  <div sid="0_1l" class="<? if($pd[24]==1){ echo 'LeftFront0_1'; } ?>">
                                                                    <div id="0_1r" class="<? if($pd[25]==1){ echo 'RightFront0_1'; } ?>">
                                                                      <div id="0_0f" class="<? if($pd[26]==1){ echo 'LeftFront0_0'; } ?>">
                                                                        <div class="<? if($pd[27]==1){ echo 'LeftSide0_0'; } ?>">
                                                                          <div id="0_0l" class="<? if($pd[28]==1){ echo 'RightSide0_0'; } ?>">
                                                                            <? if($u->info['admin']==0){ ?>
                                                                            <div><img src="http://img.combats.ru/i/1x1.gif" usemap="#ObjectsMap" border="0" /></div>
                                                                          </div>
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- / MAP \ -->
                <span class="<? if($pd[28]==1){ echo 'RightSide0_0'; } ?>">
                <? } ?>
                </span></div>
            </div>
            </td>
          </tr>
        </table>
        <div id="gotext777" style="padding-top:5px;float:right;">&nbsp;</div>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
<script>
genMap();
locGoLineDng();

$("document").ready(function() {

  $('#g1').click(function() {
    location.href = "main.php";
	/*$.ajax({
      url: "main.php?ajax=1",
      cache: false,
      success: function(html) {
        $("body").html(html);
      }
    });*/
  });
  
  $('#g6').click(function() {
    location.href = "main.php?look=1";
   /* $.ajax({
      url: "main.php?look=1&rnd=<?=$code;?>&ajax=1",
      cache: false,
      success: function(html) {
        $("body").html(html);
      }
    });*/
  });
  
  $('#g7').click(function() {
	  location.href = "main.php?look=2";
   /* $.ajax({
      url: "main.php?look=2&rnd=<?=$code;?>&ajax=1",
      cache: false,
      success: function(html) {
        $("body").html(html);
      }
    });*/
  });
  
});
</script>
<br /><br />
<?
if($u->info['admin'] == 1) {
  echo '<form method="POST">X - <input type="text" name="g__x" autocomplete="off" value="'.$u->info['x'].'" size="4" /> Y - <input type="text" name="g__y" autocomplete="off" value="'.$u->info['y'].'" size="4" /> <input type="submit" value="Перейти" name="go_to_admin" /><br/><input id="restorbot" type="checkbox" name="g__restorebot" autocomplete="off" /> <label for="restorbot">Восстановить павших ботов </label></form>';
}
?>
<? } } ?>