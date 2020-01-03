<?
if(!defined('GAME')) { die(); }
if($u->room['file']=='enterdrago') {

if(isset($_GET['rz'])) $roomSection = 1; // Получаем Задание
	else $roomSection = 0;  // Собираем группу для похода
	$error = ''; // Собираем ошибки.
	$dungeonGroupList = ''; // Сюда помещаем список Групп.
	$dungeonGo = 1; // По умолчанию, мы идем в пещеру. 

$dungeon = mysql_fetch_assoc( mysql_query('SELECT `id` as room, city, `dungeon_room` as d_room, city, `shop`, `dungeon_id` as id, `dungeon_name` as name FROM `dungeon_room` WHERE `id`="'.$u->room['id'].'" LIMIT 1') );
//var_info($dungeon);
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

$dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh'.$dungeon['id'].'" AND `time` > '.(time()-60*60*20).' LIMIT 1',1);
//if($u->info['admin']>0) unset($dungeon_timeout); // $dungeon_timeout - задержка на посещение пещеры.
if(isset($dungeon_timeout['id'])) // Кто-то передумал и не пойдет в пещеру, так-как уже там был.
{
	$dungeonGo = 0;
	if(isset($_GET['start'])){
		$error = 'До следующего похода осталось еще: '.$u->timeOut(60*60*20-time()+$dungeon_timeout['time']);
	}
} 

if( isset($_GET['start']) && $zv['uid']==$u->info['id'] && $dungeonGo == 1 ) { //начинаем поход
	//Генирируем случайный поход
	//$level = $u->info['level'];
	if( $zv['type'] == 0 ) {
		$level = 5;
	}elseif( $zv['type'] == 1 ) {
		$level = 8;
	}elseif( $zv['type'] == 2 ) {
		$level = 10;
	}

	$k=floor(2.5*$level); // k=maze size
	$s=$k*2+3; // s=array size
	$r=10; // r=cell size (in pixels)
	$count=1; // count=cells visited.
	
	$a=array(); // A [Row][Col] array of cells (0=open, 1=filled) of the maze.
	for($y=0;$y<$s;$y++) { // Initialize array to all 1 (green).
		for($x=0;$x<$s;$x++) {
			$a[$y][$x]=1;
		}
	}
	for($x=0;$x<$s;$x++) { // Clear rim to 0, to serve as a barricade.
		$a[0][$x]=0;$a[$s-1][$x]=0;
		$a[$x][0]=0;$a[$x][$s-1]=0;
	}
	$x=$y=($k-1)>>1;$a[$y*2+2][$x*2+2]=0; // start from center
	while(1) { // Open paths for k^2 cells.
		do { // Dig as far as possible until we reach a cul-de-sac.
			$d=rand(0,3); // Pick initial direction raqndomly.
			for($i=0;$i<4;$i++) { // Look for a good direction.
				if($d==0 && $a[$y*2+2][$x*2+4]) { // right
					$a[$y*2+2][$x++*2+3]=0;$a[$y*2+2][$x*2+2]=0;
					$i=5;$count++;break; // found
				} elseif($d==1 && $a[$y*2][$x*2+2]) { // up
					$a[$y--*2+1][$x*2+2]=0;$a[$y*2+2][$x*2+2]=0;
					$i=5;$count++;break; // found
				} elseif($d==2 && $a[$y*2+2][$x*2]) { // left
					$a[$y*2+2][$x--*2+1]=0;$a[$y*2+2][$x*2+2]=0;
					$i=5;$count++;break; // found
				} elseif($d==3 && $a[$y*2+4][$x*2+2]) { // down
					$a[$y++*2+3][$x*2+2]=0;$a[$y*2+2][$x*2+2]=0;
					$i=5;$count++;break; // found
				}
				$d=($d+1)%4; // wrap right->up->left->down->right
			} // for
		} while ($i!=4); // i=4 means we are stuck
		if($count>=$k*$k) { // Did we visit k^2 cells?
			break; // while(1) The entire maze has been created.
		}
		$x=rand(0,$k-1);$y=rand(0,$k-1); // Pick random cell on maze.
		// Scan left-to-right top-to-botton for a dug cell with at least
		while($a[$y*2+2][$x*2+2] || // one expandable neighbor
			!$a[$y*2+2][$x*2+4] && !$a[$y*2][$x*2+2] &&
			!$a[$y*2+2][$x*2] && !$a[$y*2+4][$x*2+2]) {
			if(++$x>=$k) { // wrap right edge to left edge
				$x=0;
				if(++$y>=$k) { // wrap bottom to top
					$y=0;
				}
			}
		}
	} // while(1)
	$rnds = array(
		rand(1,3), //start
		rand(1,3)  //end
	);
	$srg = array();
	$objects = array();
	
	if( $rnds[0] == 1 ) {
		//вход сверху
		$a[2][1]=0;
		$a[2][2]=0;
		$srg = array( 2,1 );
		$objects[2][1] = '<div title="Вход в подземелье" class="ddpStart"></div>';
	}elseif( $rnds[0] == 2 ) {
		//вход по центру
		$a[$k][1]=0;
		$a[$k][2]=0;
		$srg = array( $k,1 );
		$objects[$k][1] = '<div title="Вход в подземелье" class="ddpStart"></div>';
	}elseif( $rnds[0] == 3 ) {
		//вход снизу
		$a[$k*2][1]=0;
		$a[$k*2][2]=0;
		$srg = array( $k*2,1 );
		$objects[$k*2][1] = '<div title="Вход в подземелье" class="ddpStart"></div>';
	}
	
	if( $rnds[1] == 1 ) {
		//вход сверху
		$a[2][$k*2+1]=0;
		$a[2][$k*2]=0;
		$objects[2][$k*2+1] = '<div title="Выход из подземелье" class="ddpExit"></div>';
	}elseif( $rnds[1] == 2 ) {
		//вход по центру
		$a[$k][$k*2+1]=0;
		$a[$k][$k*2]=0;
		$objects[$k][$k*2+1] = '<div title="Выход из подземелье" class="ddpExit"></div>';
	}elseif( $rnds[1] == 3 ) {
		//вход снизу
		$a[$k*2][$k*2+1]=0;
		$a[$k*2][$k*2]=0;
		$objects[$k*2][$k*2+1] = '<div title="Выход из подземелье" class="ddpExit"></div>';
	}
	//$a[1][2]=0;
	//$a[$k*2+1][$k*2]=0; // Draw entrance and exit.
?>
<style>
.ddp0 { 
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/o.gif");
}
.ddp1 { 
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/m.gif");
}
.ddpStart {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/os.gif");
}
.ddpExit {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/of.gif");
}
.ddp1s {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/s.gif");
}
.ddp1m {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/r.gif");
}
.ddp1h {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/h.gif");
}
.ddp1l {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/b.gif");
}
.ddp1p {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.xcombats.com/drgn/bg/p.gif");
}
</style>
<?
	//Генирация обьектов
	$map = array();
	$i = 1;
	while( $i < count($a) - 1 ) {
		$j = 1;
		while( $j < count($a[$i]) - 1 ) {
			if( $a[$i][$j] == 0 && $i > 1 && $j > 1 && $i <= $k*2 && $j <= $k*2 ) {
				$map[] = array($i,$j);				
			}
			$j++;
		}
		$i++;
	}
	
	//Раскидываем обьекты (XX %)
	/*
	$proc1 = 10; //Сколько % занимают сундуки
	$proc2 = 15; //Сколько % занимают монстры
	$proc3 = 10; //Сколько % занимают хилки
	$proc4 = 5;  //Сколько % занимают ловушки
	$proc5 = 2;  //Сколько % занимают пандоры*/
	$proc1 = round(1.7*$level); //Сколько % занимают сундуки
	$proc2 = round(2.5*$level); //Сколько % занимают монстры
	$proc3 = round(1.35*$level); //Сколько % занимают хилки
	$proc4 = round(0.5*$level);  //Сколько % занимают ловушки
	$proc5 = round(0.1*$level);  //Сколько % занимают пандоры
	//
	$proc1 = round($proc1/2);
	$proc1 = round(count($map)/100*$proc1);
	$proc2 = round($proc2/2);
	$proc2 = round(count($map)/100*$proc2);
	$proc3 = round($proc3/2);
	$proc3 = round(count($map)/100*$proc3);
	$proc4 = round($proc4/2);
	$proc4 = round(count($map)/100*$proc4);
	$proc5 = round($proc5/2);
	$proc5 = round(count($map)/100*$proc5);
	//
	//if( $u->info['id'] == 1002 ) {
		
		$usi = 0;
		
		//Сохраняем карту
		mysql_query('INSERT INTO `laba_now` ( `time`,`uid`,`users`,`map_id`,`end`,`type` ) VALUES (
			"'.time().'","'.$u->info['id'].'","-1","0","0","'.$zv['type'].'"
		)');
		$ding = mysql_insert_id();
		$sp = mysql_query( 'SELECT `id`,`dn` FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 4' );
		while( $pl = mysql_fetch_array( $sp ) ) {
			$u->addAction(time(),'psh102','',$pl['id']);		
			mysql_query('UPDATE `users` SET `room` = "370" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0",`dnow` = "'.$ding.'",`x` = "'.$srg[0].'",`y` = "'.$srg[1].'",`res_x` = "'.$srg[0].'",`res_y` = "'.$srg[1].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			$usi++;
		}
		mysql_query('UPDATE `laba_now` SET `users` = "'.$usi.'" WHERE `id` = "'.$ding.'" LIMIT 1');
		mysql_query('INSERT INTO `laba_map` ( `id`,`data`,`update` ) VALUES (
			"'.$ding.'","'.json_encode($a).'","'.time().'"
		)');
		mysql_query('DELETE FROM `dungeon_zv` WHERE `id` = "'.$zv['id'].'" LIMIT 1');	
	//}
	//
	$i = 1;
	while( $i <= 5 ) {
		if( ${'proc'.$i} > 0 ) {
			$j = 0;
			while( $j < ${'proc'.$i} ) {
				$cord = $map[rand(0,count($map)-1)];
				$obj = '';
				if( $i == 1 ) {
					//сундуки
					$obj = 'ddp1s';
				}elseif( $i == 2 ) {
					//монстры
					$obj = 'ddp1m';
				}elseif( $i == 3 ) {
					//хилки
					$obj = 'ddp1h';
				}elseif( $i == 4 ) {
					//ловушки
					$obj = 'ddp1l';
				}elseif( $i == 5 ) {
					//пандора
					$obj = 'ddp1p';
				}
				$test = mysql_fetch_assoc(mysql_query(' SELECT id FROM `laba_obj` WHERE `img`="'.$obj.'" AND `x`= "'.$cord[0].'" AND `y`= "'.$cord[1].'" AND `lib` = "'.$ding.'" '));
				if( !isset($test['id']) ){
					mysql_query('INSERT INTO `laba_obj` ( `lib`,`x`,`y`,`type`,`img`,`name`,`time`,`use` ) VALUES (
						"'.$ding.'","'.$cord[0].'","'.$cord[1].'","'.$i.'","'.$obj.'","{standart}","'.time().'","0"
					)');
					$j++;
				}
				//$objects[$cord[0]][$cord[1]] = $obj;
				
			}
		}
		$i++;
	}
	
	die('<script>location.href="/main.php"</script>');	
	
	//Генирация карты
	/*
	$i = 1;
	while( $i < count($a) - 1 ) {
		$j = 1;
		while( $j < count($a[$i]) - 1 ) {		
			if( $a[$i][$j] == 0 ) {
				echo '<div class="ddp0">'.$objects[$i][$j].'</div>';
			}else{
				echo '<div class="ddp1"></div>';
			}
			$j++;
		}
		echo '<br>';
		$i++;
	}
	*/
	
}elseif(isset($_POST['go'],$_POST['goid']) && $dungeonGo==1){
	if(!isset($zv['id'])){
		$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `id`="'.mysql_real_escape_string($_POST['goid']).'" AND `delete` = "0" LIMIT 1'));
		if(isset($zv['id']) && $u->info['dn'] == 0){
			if( $zv['pass'] != '' && $_POST['pass_com'] != $zv['pass'] ) {
				$error = 'Вы ввели неправильный пароль';				
			}elseif($u->info['level'] > 3 && $u->info['level'] == $zv['lvlmin']){
				$row = 0;
				if(4 > $row){
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if(!$upd){
						$error = 'Не удалось вступить в эту группу';
						unset($zv);
					}else{
						$u->info['dn'] = $zv['id'];
					}
				}else{
					$error = 'В группе нет места';
					unset($zv);
				}
			}else{
				$error = 'Вы не подходите по уровню';
				unset($zv);
			}
		}else{
			$error = 'Заявка не найдена';
		}
	}else{
		$error = 'Вы уже находитесь в группе';
	}
}elseif( isset($_POST['leave']) && isset($zv['id']) && $dungeonGo == 1 ) {
	if($zv['uid']==$u->info['id'])
	{
		//ставим в группу нового руководителя
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ld['id'])){
			$zv['uid'] = $ld['id'];
			mysql_query('UPDATE `dungeon_zv` SET `uid` = "'.$zv['uid'].'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}else{
			//удаляем группу целиком
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}
	}else{
		//просто выходим с группы
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
		unset($zv);
	}
}elseif(isset($_POST['add']) && $u->info['level']>1 && $dungeonGo == 1){
	if($u->info['dn']==0){		
		if( $_POST['type_gors'] == 1 ) {
			$_POST['type_gors'] = 1;
		}elseif( $_POST['type_gors'] == 2 ) {
			$_POST['type_gors'] = 2;
		}else{
			$_POST['type_gors'] = 0;
		}
		if( $_POST['type_gors'] == 2 && ($u->info['level'] < 9 || $u->rep['rep3'] < 20000) ) {
			$error = 'Создавать турниры для Легендарных могут персонажи страше 8-го уровня<br>Так-же у персонажа должно быть более 20000 воинственности!';
		}elseif( $_POST['type_gors'] == 1 && $u->info['level'] < 8 ) {
			$error = 'Создавать турниры для Опытных могут персонажи страше 7-го уровня';
		}elseif( $_POST['type_gors'] == 0 && $u->info['level'] > 7 ) {
			$error = 'Создавать турниры для Новичков могут персонажи младше 8-го уровня';
		}else{
			$ins = mysql_query('INSERT INTO `dungeon_zv`
			(`city`,`time`,`uid`,`dun`,`pass`,`com`,`lvlmin`,`lvlmax`,`team_max`,`type`) VALUES
			("'.$u->info['city'].'","'.time().'","'.$u->info['id'].'","'.$dungeon['id'].'",
			"'.mysql_real_escape_string($_POST['pass']).'",
			"'.mysql_real_escape_string($_POST['text']).'",
			"'.$u->info['level'].'",
			"21",
			"5",
			"'.mysql_real_escape_string($_POST['type_gors']).'")');
			if($ins)
			{
				$u->info['dn'] = mysql_insert_id();
				$zv['id'] = $u->info['dn'];
				$zv['uid'] = $u->info['id'];
				mysql_query('UPDATE `stats` SET `dn` = "'.$u->info['dn'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				$error = 'Вы успешно создали группу';
			}else{
				$error = 'Не удалось создать группу';
			}
		}
	}else{
		$error = 'Вы уже находитесь в группе';
	}
}

//Генерируем список групп
$pltype = array(
	0 => 'Новичок',
	1 => 'Опытный',
	2 => 'Старый'
);

$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `lvlmin` = "'.$u->info['level'].'" AND `dun` = "'.$dungeon['id'].'" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');

while( $pl = mysql_fetch_array( $sp ) ) {
	$dungeonGroupList .= '<div style="padding:2px;">'; 
	if( $u->info['dn'] == 0 ) $dungeonGroupList .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	$dungeonGroupList .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	$dungeonGroupList .= 'Тип: <b style="color:green">'.$pltype[$pl['type']].'</b> | ';
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
body
{
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/drgn/bg/1.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <?
	if($u->error!='') {
		echo '<font color=red><b>'.$u->error.'</b></font>';
	}elseif($re!='') {
		echo '<font color=red><b>'.$re.'</b></font>';
	}
	?>
    <div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div>
    Если вы не найдете выход из пещеры, то любые найденные вами ресурсы (даже в предыдущих походах) - исчезнут. То же самое, если вы умрёте 3 раза.
    </td>
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
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=<? if($u->info['city']=='fallenearth'){ echo '6.180.0.102'; } else {echo '1.180.0.321'; }?>&rnd=<? echo $code; ?>';" title="<? 
							  if($u->info['city']=='fallenearth'){
								thisInfRm('6.180.0.102',1); 
							  }else {
								thisInfRm('1.180.0.321',1);
							  }
							  ?>"><?
							  if($u->info['city']=='fallenearth'){
								echo "Темный Портал";
							  }else {
								echo "Магический Портал";
							  }
							  ?></a></td>
                            </tr>
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
                          <? if($roomSection == 1) { ?>
                          <div align="center" style="float:right;width:100px;">
                            <p>
                              <input type='button' onclick='location="main.php?rz=1"' value="Обновить" />
                              <br />
                              <input type='button' onclick='location="main.php"' value="Вернуться" />
                            </p>
                          </div>
                          <? }else{ ?>
<div align="center" style="float:right;width:100px;">
                            <p>
                              <input type='button' onclick='location="main.php"' value="Обновить" />
                             <? // <br /> <input type='button' onclick='location="main.php?rz=1"' value="Задания" /> ?> 
                            </p>
                          </div>
                          <? } ?>
<?
if($error!='')
{
	echo '<font color="red"><b>'.$error.'</b></font><br>';
}

//отображаем
if($dungeonGroupList=='')
{
	$dungeonGroupList = '';
}else{
	if(!isset($zv['id']) || $u->info['dn'] == 0)
	{
		if($dungeonGo==1 || $u->info['dn'] == 0)
		{
			$pr = '<input name="go" type="submit" value="Вступить в группу">';
		}
		$dungeonGroupList = '<form autocomplete="off" action="main.php?rnd='.$code.'" method="post">'.$pr.'<br>'.$dungeonGroupList.''.$pr.'</form>';
	}
	$dungeonGroupList .= '<hr>';
}

if($roomSection==0) { echo $dungeonGroupList; }
	if($roomSection == 1) {
	?>
	<div>
		  <form autocomplete="off" action='/main.php' method="post" name="F1" id="F1">
	<?
		$qsee = ''; 
		$hgo = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_'.$dungeon['city'].'" LIMIT 1',1);
		$qc=0; // Quest Count
		//Генерируем список текущих квестов
		$sp = mysql_query('SELECT * FROM `actions` WHERE `vars` LIKE "%start_quest%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 100');
		while( $pl = mysql_fetch_array( $sp ) ) {
			if($pl['room'] == $u->info['room']){
				$pq = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$pl['vars']).'" LIMIT 1'));
				$qsee .= '<a href="main.php?rz=1&end_qst_now='.$pq['id'].'"><img src="http://img.xcombats.com/i/clear.gif" title="Отказаться от задания"></a> <b>'.$pq['name'].'</b><div style="padding-left:15px;padding-bottom:5px;border-bottom:1px solid grey"><small>'.$pq['info'].'<br>'.$q->info($pq).'</small></div><br>';
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
					} elseif( $u->rep['rep'.$dungeon['city']] == 19999 ) {
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
					} elseif( $u->rep['rep'.$dungeon['city']] == 19999 ) {
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
							if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
								$cur_price['price'] 	= $price;
								$cur_price['nu_'.$val] 	= $price;
							} elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
								$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); $cur_price['nu_'.$val] = $cur; 
							}
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
				$price = 2000+(2000*$u->rep['add_skills']);
				$cur_price = array('price'=>0); 
				if(10-$u->rep['add_skills']>0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // Умения!
					foreach($dungeon['list'] as $key=>$val){
						if( !( $cur_price['price'] >= $price ) ) {
							if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
								$cur_price['price'] 	= $price;
								$cur_price['nu_'.$val] 	= $price;
							} elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
								$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); $cur_price['nu_'.$val] = $cur; 
							}
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
						if(!($cur_price['price'] >= $price)){
							if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
								$cur_price['price'] = $price; $cur_price['nu_'.$val] = $price;
							} elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ) {
								$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); $cur_price['nu_'.$val] = $cur; 
							}
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
						if(!($cur_price['price'] >= $price)){
							if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
								$cur_price['price'] 	= $price;
								$cur_price['nu_'.$val] 	= $price;
							} elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
								$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); $cur_price['nu_'.$val] = $cur; 
							}
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
		  <fieldset style='padding: 5,5,5,5'>
			<legend>Награда: <b><?=($u->rep['allrep']-$u->rep['allnurep'])?> ед.</b></legend>
			 <table>
			  <tr>
				<td>Способность (еще <?=(25-$u->rep['add_stats'])?>)</td>
				<td style='padding-left: 10px'>за <?=2000+($u->rep['add_stats']*100);?> ед.</td>
				<td style='padding-left: 10px'><input type='button' value='Купить'
	onclick="if (confirm('Купить: Способность?\n\nКупив способность, Вы сможете увеличить характеристики персонажа.\nНапример, можно увеличить силу.')) {location='main.php?rz=1&buy1=1'}" /></td>
			  </tr>
			  <tr>
				<td>Умение (еще <?=(10-$u->rep['add_skills'])?>)</td>
				<td style='padding-left: 10px'>за <?=2000+(2000*$u->rep['add_skills']);?> ед.</td>
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
			<p><span style="padding-left: 10">
			<? 
			$chk = mysql_fetch_array(mysql_query('SELECT COUNT(`u`.`id`),SUM(`m`.`price1`) FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `u`.`item_id` = `m`.`id` WHERE `m`.`type` = "61" AND `u`.`delete` = "0" AND `u`.`inOdet` = "0" AND `u`.`inShop` = "0" AND `u`.`inTransfer` = "0" AND `u`.`uid` = "'.$u->info['id'].'" LIMIT 1000'));
			if(isset($_GET['buy777']) && $chk[0]>0) {
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
			
			}
			if($chk[0]>0) {
			?>
			  <input type='button' value='Сдать чеки'
	onclick="if (confirm('Сдать все чеки (<?=$chk[0]?> шт.) находящиеся у Вас в инвентаре за <?=$chk[1]?> кр. ?')) {location='main.php?rz=1&buy777=1'}" />
			<? } ?>
			</span></p>
		  </fieldset> 
		  <fieldset style='margin-top:15px;'>
			<table> 
			  <tr>
				<td width="200">Репутация в Dragons city:</td>
				<td><?=$u->rep['repdragonscity']?> ед.</td>
			  </tr>
			  <tr>
				<td width="200">Репутация в Capital city:</td>
				<td><?=$u->rep['repcapitalcity']?> ед.</td>
			  </tr>
			  <tr>
				<td>Репутация в Demons city:</td>
				<td><?=$u->rep['repdemonscity']?> ед.</td>
			  </tr>
			  <tr>
				<td>Репутация в Angels city:</td>
				<td><?=$u->rep['repangelscity']?> ед.</td>
			  </tr> 
			</table>
			<legend>Текущая репутация:</legend> 
		  </fieldset>
	</div>
	<?
	}else{
		if($dungeonGo == 1){
			if($u->info['dn']==0){
			?>
			<table width="350" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td valign="top">
				<form id="from" autocomplete="off" name="from" action="main.php?pz1=<? echo $code; ?>" method="post">
				  <fieldset style='padding-left: 5; width=50%'>
				  <legend><b> Группа </b> </legend>
					Тип похода:  
					<select name="type_gors" style="margin-left:7px;" id="type_gors">
					  <option value="0">Новичок</option>
					  <option value="1">Опытный</option>
					  <option value="2">Старый</option>
					</select>
					<br />
					Комментарий
					<input type="text" name="text" maxlength="40" size="40" />
				  <br />
					Пароль
			  <input type="password" name="pass" maxlength="25" size="25" />
			  <br />
			  <input type="submit" name="add" value="Создать группу" />
			  &nbsp;<br />
				  </fieldset>
				</form>
				</td>
			  </tr>
			</table>
			<?
			}else{
				$psh_start = '';
				if(isset($zv['id']))
				{
					if($zv['uid']==$u->info['id'])
					{
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
		} else {
			echo 'Поход в пещеры разрешен один раз в двадцать часов. Осталось еще: '.$u->timeOut(60*60*20-time()+$dungeon_timeout['time']).'<br><small style="color:grey">Но Вы всегда можете приобрести ключ от прохода у любого &quot;копателя пещер&quot; в Торговом зале ;)</small>';
		}
	}
}
?>
