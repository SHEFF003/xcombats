<?
if(!defined('GAME')) { die(); }
if($u->room['file']=='enterdrago') {

if(isset($_GET['rz'])) $roomSection = 1; // �������� �������
	else $roomSection = 0;  // �������� ������ ��� ������
	$error = ''; // �������� ������.
	$dungeonGroupList = ''; // ���� �������� ������ �����.
	$dungeonGo = 1; // �� ���������, �� ���� � ������. 

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
//if($u->info['admin']>0) unset($dungeon_timeout); // $dungeon_timeout - �������� �� ��������� ������.
if(isset($dungeon_timeout['id'])) // ���-�� ��������� � �� ������ � ������, ���-��� ��� ��� ���.
{
	$dungeonGo = 0;
	if(isset($_GET['start'])){
		$error = '�� ���������� ������ �������� ���: '.$u->timeOut(60*60*20-time()+$dungeon_timeout['time']);
	}
} 

if( isset($_GET['start']) && $zv['uid']==$u->info['id'] && $dungeonGo == 1 ) { //�������� �����
	//���������� ��������� �����
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
		//���� ������
		$a[2][1]=0;
		$a[2][2]=0;
		$srg = array( 2,1 );
		$objects[2][1] = '<div title="���� � ����������" class="ddpStart"></div>';
	}elseif( $rnds[0] == 2 ) {
		//���� �� ������
		$a[$k][1]=0;
		$a[$k][2]=0;
		$srg = array( $k,1 );
		$objects[$k][1] = '<div title="���� � ����������" class="ddpStart"></div>';
	}elseif( $rnds[0] == 3 ) {
		//���� �����
		$a[$k*2][1]=0;
		$a[$k*2][2]=0;
		$srg = array( $k*2,1 );
		$objects[$k*2][1] = '<div title="���� � ����������" class="ddpStart"></div>';
	}
	
	if( $rnds[1] == 1 ) {
		//���� ������
		$a[2][$k*2+1]=0;
		$a[2][$k*2]=0;
		$objects[2][$k*2+1] = '<div title="����� �� ����������" class="ddpExit"></div>';
	}elseif( $rnds[1] == 2 ) {
		//���� �� ������
		$a[$k][$k*2+1]=0;
		$a[$k][$k*2]=0;
		$objects[$k][$k*2+1] = '<div title="����� �� ����������" class="ddpExit"></div>';
	}elseif( $rnds[1] == 3 ) {
		//���� �����
		$a[$k*2][$k*2+1]=0;
		$a[$k*2][$k*2]=0;
		$objects[$k*2][$k*2+1] = '<div title="����� �� ����������" class="ddpExit"></div>';
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
	//��������� ��������
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
	
	//����������� ������� (XX %)
	/*
	$proc1 = 10; //������� % �������� �������
	$proc2 = 15; //������� % �������� �������
	$proc3 = 10; //������� % �������� �����
	$proc4 = 5;  //������� % �������� �������
	$proc5 = 2;  //������� % �������� �������*/
	$proc1 = round(1.7*$level); //������� % �������� �������
	$proc2 = round(2.5*$level); //������� % �������� �������
	$proc3 = round(1.35*$level); //������� % �������� �����
	$proc4 = round(0.5*$level);  //������� % �������� �������
	$proc5 = round(0.1*$level);  //������� % �������� �������
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
		
		//��������� �����
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
					//�������
					$obj = 'ddp1s';
				}elseif( $i == 2 ) {
					//�������
					$obj = 'ddp1m';
				}elseif( $i == 3 ) {
					//�����
					$obj = 'ddp1h';
				}elseif( $i == 4 ) {
					//�������
					$obj = 'ddp1l';
				}elseif( $i == 5 ) {
					//�������
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
	
	//��������� �����
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
				$error = '�� ����� ������������ ������';				
			}elseif($u->info['level'] > 3 && $u->info['level'] == $zv['lvlmin']){
				$row = 0;
				if(4 > $row){
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if(!$upd){
						$error = '�� ������� �������� � ��� ������';
						unset($zv);
					}else{
						$u->info['dn'] = $zv['id'];
					}
				}else{
					$error = '� ������ ��� �����';
					unset($zv);
				}
			}else{
				$error = '�� �� ��������� �� ������';
				unset($zv);
			}
		}else{
			$error = '������ �� �������';
		}
	}else{
		$error = '�� ��� ���������� � ������';
	}
}elseif( isset($_POST['leave']) && isset($zv['id']) && $dungeonGo == 1 ) {
	if($zv['uid']==$u->info['id'])
	{
		//������ � ������ ������ ������������
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ld['id'])){
			$zv['uid'] = $ld['id'];
			mysql_query('UPDATE `dungeon_zv` SET `uid` = "'.$zv['uid'].'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}else{
			//������� ������ �������
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}
	}else{
		//������ ������� � ������
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
			$error = '��������� ������� ��� ����������� ����� ��������� ������ 8-�� ������<br>���-�� � ��������� ������ ���� ����� 20000 ��������������!';
		}elseif( $_POST['type_gors'] == 1 && $u->info['level'] < 8 ) {
			$error = '��������� ������� ��� ������� ����� ��������� ������ 7-�� ������';
		}elseif( $_POST['type_gors'] == 0 && $u->info['level'] > 7 ) {
			$error = '��������� ������� ��� �������� ����� ��������� ������ 8-�� ������';
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
				$error = '�� ������� ������� ������';
			}else{
				$error = '�� ������� ������� ������';
			}
		}
	}else{
		$error = '�� ��� ���������� � ������';
	}
}

//���������� ������ �����
$pltype = array(
	0 => '�������',
	1 => '�������',
	2 => '������'
);

$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `lvlmin` = "'.$u->info['level'].'" AND `dun` = "'.$dungeon['id'].'" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');

while( $pl = mysql_fetch_array( $sp ) ) {
	$dungeonGroupList .= '<div style="padding:2px;">'; 
	if( $u->info['dn'] == 0 ) $dungeonGroupList .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	$dungeonGroupList .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	$dungeonGroupList .= '���: <b style="color:green">'.$pltype[$pl['type']].'</b> | ';
	$pus = ''; //������
	$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
	while( $pu = mysql_fetch_array( $su ) ) {
		$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_'.$pu['city'].'.gif" title="���. � '.$pu['login'].'"></a>'; 
		$pus .= ', ';
	}
	$pus = trim( $pus, ', ' );
	
	$dungeonGroupList .= $pus; unset($pus);

	if( $pl['pass'] != '' && $u->info['dn'] == 0 ) $dungeonGroupList .= ' <small><input type="password" name="pass_com" value=""></small>';
	if( $pl['com'] != '' ) {
		$dl = '';
		// ���� ���������, ���� ����������� ������� ����������� � ������.
		$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
		if( ( $moder['boi'] == 1 || $u->info['admin'] > 0 ) && $pl['dcom'] == 0 ){
			$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">������� �����������</a>)';
			if( isset( $_GET['delcom'] ) && $_GET['delcom'] == $pl['id'] && $u->newAct( $_GET['key'] ) == true ) {
				mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['dcom'] = $u->info['id'];
			}
		}
		$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
		if( $pl['dcom'] > 0 ) {
			$dl = ' <font color="grey"><i>����������� ������ �����������</i></font>';
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
    ���� �� �� ������� ����� �� ������, �� ����� ��������� ���� ������� (���� � ���������� �������) - ��������. �� �� �����, ���� �� ����� 3 ����.
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
								echo "������ ������";
							  }else {
								echo "���������� ������";
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
                              <input type='button' onclick='location="main.php?rz=1"' value="��������" />
                              <br />
                              <input type='button' onclick='location="main.php"' value="���������" />
                            </p>
                          </div>
                          <? }else{ ?>
<div align="center" style="float:right;width:100px;">
                            <p>
                              <input type='button' onclick='location="main.php"' value="��������" />
                             <? // <br /> <input type='button' onclick='location="main.php?rz=1"' value="�������" /> ?> 
                            </p>
                          </div>
                          <? } ?>
<?
if($error!='')
{
	echo '<font color="red"><b>'.$error.'</b></font><br>';
}

//����������
if($dungeonGroupList=='')
{
	$dungeonGroupList = '';
}else{
	if(!isset($zv['id']) || $u->info['dn'] == 0)
	{
		if($dungeonGo==1 || $u->info['dn'] == 0)
		{
			$pr = '<input name="go" type="submit" value="�������� � ������">';
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
		//���������� ������ ������� �������
		$sp = mysql_query('SELECT * FROM `actions` WHERE `vars` LIKE "%start_quest%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 100');
		while( $pl = mysql_fetch_array( $sp ) ) {
			if($pl['room'] == $u->info['room']){
				$pq = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$pl['vars']).'" LIMIT 1'));
				$qsee .= '<a href="main.php?rz=1&end_qst_now='.$pq['id'].'"><img src="http://img.xcombats.com/i/clear.gif" title="���������� �� �������"></a> <b>'.$pq['name'].'</b><div style="padding-left:15px;padding-bottom:5px;border-bottom:1px solid grey"><small>'.$pq['info'].'<br>'.$q->info($pq).'</small></div><br>';
			$qc++;
			}
		}
		
		if( isset( $_GET['add_quest'] ) && $qc == 0 ) {
			if( isset( $hgo['id'] ) ) {
				echo '<font color="red"><b>������ �������� ������� ���� ������ ���� � �����</b></font><br>';
			} else {
				$sp = mysql_query('SELECT * FROM `quests` WHERE `line` = '.$dungeon['id'].'');
				$dq_add = array();
				while( $pl = mysql_fetch_array( $sp ) ) {
					if( $u->rep['rep'.$dungeon['city']] == 9999 ) {
						//����, ���������� �������
						if( $pl['kin'] == 1 ) {
							$dq_add = array( 0 => $pl );
						}
					} elseif( $u->rep['rep'.$dungeon['city']] == 19999 ) {
						//����, ���������� �������
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
					echo '<font color="red"><b>�� ������� �������� ����� ������� &quot;'.$dq_add['name'].'&quot;.</b></font><br>'; 
					$u->addAction(time(),'psh_qt_'.$dungeon['city'],$dq_add['id']);
				} else {
					if ( $u->rep['rep'.$dungeon['city']] == 9999 ) {
						//�����, ���������� �������
						echo '<font color="red"><b>�� ��� �������� ������� �� ���������� ������ ������!</b></font><br>';
					} elseif( $u->rep['rep'.$dungeon['city']] == 19999 ) {
						//�����, ���������� �������
						echo '<font color="red"><b>�� ��������� ��������� �����, �������� ����� �������!</b></font><br>';
					} else {
						echo '<font color="red"><b>�� ������� �������� ������� &quot;'.$dq_add['name'].'&quot;. ���������� ���...</b></font><br>';
					}	
				}
				unset( $dq_add );
			}
		} elseif( isset( $_GET['add_quest'] ) && $qc > 0 ) {
			echo '<font color="red"><b>���-�� ����� �� ���... ����������.. <br/><br/></b></font><br>';
		}
		if( $qsee == '' ) {
			$qsee = '� ��������� � ��� ��� �� ������ �������<br/><br/>';
		}
	?>
			<Br />
			<FIELDSET>
				<LEGEND><B>������� �������: </B></LEGEND>
				<?=$qsee?>
				<span style="padding-left: 10">
				<?
				if( $qc > 0 ){
					echo '�� ��� �� ���������� � ������� ��������.';
				} elseif( !isset( $hgo['id'] ) && $qc == 0 ) {
					?>
					<br />
					<input type='button' value='�������� �������' onclick='location="main.php?rz=1&add_quest=1"' />
					<?
				} else { 
					echo '�������� ����� ������� ����� <b>'.date('d.m.Y H:i',$hgo['time']+60*60*24).'</b> <font color="">( ����� '.$u->timeOut($hgo['time']+60*60*24-time()).' )</font>';
				}
				?>
				</span>
			</FIELDSET>
		</form>
		<br />
		  
		<? 
		//���������� ������ �������
		if( isset( $_GET['buy1'] ) ) {
			$rt = 1;
			if( $_GET['buy1'] == 1 ) {
				//�������� �����
				$price = 2000+($u->rep['add_stats']*100);
				$cur_price = array('price'=>0);
				if( 25 - $u->rep['add_stats'] > 0 && $u->rep['allrep'] - $u->rep['allnurep'] >= $price ) { // ��������������!
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
							echo '<font color="red"><b>�� ������� ��������� 1 ����������� �� '.$price.' ��. �������</b></font><br>';
						} else {
							echo '<font color="red"><b>������ �� ����������...</b></font><br>';
						}
					} else echo '������������ ���������.';
				} else {
				   echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
				}
			} elseif( $_GET['buy1'] == 2 ) { // ������!
				$price = 2000+(2000*$u->rep['add_skills']);
				$cur_price = array('price'=>0); 
				if(10-$u->rep['add_skills']>0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // ������!
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
							echo '<font color="red"><b>�� ������� ��������� 1 ������ �� '.$price.' ��. �������</b></font><br>';
						} else {
							echo '<font color="red"><b>������ �� ����������...</b></font><br>';
						}
					} else echo '������������ ���������.';
				} else {
					echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
				}
			} elseif( $_GET['buy1'] == 3 ) { // �������
				$price = 100;
				$cur_price = array('price'=>0); 
				if( $u->rep['allrep'] - $u->rep['allnurep'] >= $price) { // �������� �������
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
							echo '<font color="red"><b>�� ������� ��������� 10 ��. �� '.$price.' ��. �������</b></font><br>';
						} else {
							echo '<font color="red"><b>������ �� ����������...</b></font><br>';
						}
					} else echo '������������ ���������.';
				}else{
					echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
				}
			} elseif( $_GET['buy1'] == 4 ) { // �����������
				$price = 3000;
				$cur_price = array('price'=>0);
				if( 5 - $u->rep['add_skills2'] > 0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // �����������
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
							echo '<font color="red"><b>�� ������� ��������� 1 ����������� �� '.$price.' ��. �������</b></font><br>';
						} else {
							echo '<font color="red"><b>������ �� ����������...</b></font><br>';
						}
					} else echo '������������ ���������.';
					
				} else {
					echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
				}
			}
		}
		?>
		  <fieldset style='padding: 5,5,5,5'>
			<legend>�������: <b><?=($u->rep['allrep']-$u->rep['allnurep'])?> ��.</b></legend>
			 <table>
			  <tr>
				<td>����������� (��� <?=(25-$u->rep['add_stats'])?>)</td>
				<td style='padding-left: 10px'>�� <?=2000+($u->rep['add_stats']*100);?> ��.</td>
				<td style='padding-left: 10px'><input type='button' value='������'
	onclick="if (confirm('������: �����������?\n\n����� �����������, �� ������� ��������� �������������� ���������.\n��������, ����� ��������� ����.')) {location='main.php?rz=1&buy1=1'}" /></td>
			  </tr>
			  <tr>
				<td>������ (��� <?=(10-$u->rep['add_skills'])?>)</td>
				<td style='padding-left: 10px'>�� <?=2000+(2000*$u->rep['add_skills']);?> ��.</td>
				<td style='padding-left: 10px'><input type='button' value='������'
	onclick="if (confirm('������: ������?\n\n������ ��� ����������� ������������ ���� �������� ����, ������, ����� � �.�.')) {location='main.php?rz=1&buy1=2'}" /></td>
			  </tr>
			  <tr>
				<td>������ (10 ��.)</td>
				<td style='padding-left: 10px'>�� 100 ��.</td>
				<td style='padding-left: 10px'><input type='button' value='������'
	onclick="if (confirm('������: ������ (10 ��.)?\n\n������� ����� �������� ������������ ���������.')) {location='main.php?rz=1&buy1=3'}" /></td>
			  </tr>
			  <tr>
				<td>����������� (��� <?=(5-$u->rep['add_skills2'])?>)</td>
				<td style='padding-left: 10px'>�� 3000 ��.</td>
				<td style='padding-left: 10px'><input type='button' value='������'
	onclick="if (confirm('������: �����������?\n\n����������� - ��� �������������� ����������� ���������, �� ������ ������������ � ����.\n��������, ����� ��������� �������� �������������� HP')) {location='main.php?rz=1&buy1=4'}" /></td>
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
				 echo '<font color="red"><b>�� ������� ����� ���� � ���������� '.$x.' ��. �� ����� '.$prc.' ��.</b></font><br>'; 
				 $chk[0] = 0;
			
			}
			if($chk[0]>0) {
			?>
			  <input type='button' value='����� ����'
	onclick="if (confirm('����� ��� ���� (<?=$chk[0]?> ��.) ����������� � ��� � ��������� �� <?=$chk[1]?> ��. ?')) {location='main.php?rz=1&buy777=1'}" />
			<? } ?>
			</span></p>
		  </fieldset> 
		  <fieldset style='margin-top:15px;'>
			<table> 
			  <tr>
				<td width="200">��������� � Dragons city:</td>
				<td><?=$u->rep['repdragonscity']?> ��.</td>
			  </tr>
			  <tr>
				<td width="200">��������� � Capital city:</td>
				<td><?=$u->rep['repcapitalcity']?> ��.</td>
			  </tr>
			  <tr>
				<td>��������� � Demons city:</td>
				<td><?=$u->rep['repdemonscity']?> ��.</td>
			  </tr>
			  <tr>
				<td>��������� � Angels city:</td>
				<td><?=$u->rep['repangelscity']?> ��.</td>
			  </tr> 
			</table>
			<legend>������� ���������:</legend> 
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
				  <legend><b> ������ </b> </legend>
					��� ������:  
					<select name="type_gors" style="margin-left:7px;" id="type_gors">
					  <option value="0">�������</option>
					  <option value="1">�������</option>
					  <option value="2">������</option>
					</select>
					<br />
					�����������
					<input type="text" name="text" maxlength="40" size="40" />
				  <br />
					������
			  <input type="password" name="pass" maxlength="25" size="25" />
			  <br />
			  <input type="submit" name="add" value="������� ������" />
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
						$psh_start = '<INPUT type=\'button\' name=\'start\' value=\'������\' onClick="top.frames[\'main\'].location = \'main.php?start=1&rnd='.$code.'\'"> &nbsp;';
					}
					  
					echo '<br><FORM autocomplete="off" id="REQUEST" method="post" style="width:210px;" action="main.php?rnd='.$code.'">
					<FIELDSET style=\'padding-left: 5; width=50%\'>
					<LEGEND><B> ������ </B> </LEGEND>
					'.$psh_start.'
					<INPUT type=\'submit\' name=\'leave\' value=\'�������� ������\'> 
					</FIELDSET>
					</FORM>';
				}
			}
		} else {
			echo '����� � ������ �������� ���� ��� � �������� �����. �������� ���: '.$u->timeOut(60*60*20-time()+$dungeon_timeout['time']).'<br><small style="color:grey">�� �� ������ ������ ���������� ���� �� ������� � ������ &quot;�������� �����&quot; � �������� ���� ;)</small>';
		}
	}
}
?>
