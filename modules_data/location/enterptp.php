<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='enterptp'){

if(isset($_GET['rz'])) $roomSection = 1; // �������� �������
	else $roomSection = 0;  // �������� ������ ��� ������
	
$dun = 0;
/*
    if($u->room['id']==372){ $dun = 12; } // ���� � ������ ������ ��������� , CapitalCity
elseif($u->room['id']==369){ $dun = 102; } // ���� � ���������� ��������, 
elseif($u->room['id']==354){ $dun = 101; } // ���� � ������ , AngelsCity
elseif($u->room['id']==293){ $dun = 3; } // ���� � ��������� , DemonsCity
elseif($u->room['id']==296){ $dun = 11; } // ���� � ��������� , ��� �������� 2-7 ���
elseif($u->room['id']==18){  $dun = 10; } // ���� � �������� , ��� �������� 2-7 ��� SandCity
elseif($u->room['id']==209){ $dun = 20; } // ���� � ������� ������, 
elseif($u->room['id']==188){ $dun = 1; } // ���� � ���� � ����������� 
*/
$dungeon = mysql_fetch_assoc(mysql_query('SELECT `id` as room, `dungeon_id` as id, `dungeon_name` as name FROM `dungeon_room` WHERE `id`="'.$u->room['id'].'" LIMIT 1'));
$dungeon['list'] = array(
			// ������ ����������, ������� ������������ ��� ������ ������������ ���������.
			// ��� ���������� � ������ ������ ������, ������� ��������� ������� ����� � ������� `rep`.
			// ���-�� ������� �������, ��� � __user.php � ������ ���������, ��������� ����������\�������, ����.
			// �� � ������, ����� ����������, ��� ���� ���������� ��������� ��� �����, �� ���� ��� ���������� �� ����� - ���������� ��� ������. ���-��� ��� ��� capitalcity.
			1=>'capitalcity',
			2=>'demonscity',
			3=>'angelscity',
			4=>'devilscity',
			5=>'suncity');


$er = ''; // �������� ������.
$dungeonGroupList = ''; // ���� �������� ������ �����.
$dungeonGo = 1; // �� ���������, �� ���� � ������.

if($u->info['dn']>0){ // ���� �� ����� ������, ��� ��� � �����!
	$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `id`="'.$u->info['dn'].'" AND `delete` = "0" LIMIT 1'));
	if(!isset($zv['id'])){
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
	}
}

$dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh'.$dungeon['id'].'" AND `time` > '.(time()-60*60*3).' LIMIT 1',1); // �������� - ��������� �����?
// if($u->info['admin']>0) unset($dungeon_timeout); // $dungeon_timeout - �������� �� ��������� ������.
if(isset($dungeon_timeout['id'])) // ���-�� ��������� � �� ������ � ������, ���-��� ��� ��� ���.
{
	$dungeonGo = 0;
	if(isset($_GET['start']))
	{
		$re = '�� ���������� ������ �������� ���: '.$u->timeOut(60*60*3-time()+$dungeon_timeout['time']);
	}
}

if(isset($_GET['start']) && $zv['uid']==$u->info['id'] && $dungeonGo == 1){	
	//�������� �����
	//�������� �����
	$ig = 1;
	if($ig>0)
	{
		//���������� ������� � ������
		//$u->addAction(time(),'psh'.$dun,'');
		$ins = mysql_query('INSERT INTO `dungeon_now` (`city`,`uid`,`id2`,`name`,`time_start`)
		VALUES ("'.$zv['city'].'","'.$zv['uid'].'","'.$dungeon['id'].'","������","'.time().'")');
		if($ins)
		{
			$zid = mysql_insert_id();
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			//��������� �������������
			$su = mysql_query('SELECT `u`.`id`,`st`.`dn` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$zv['id'].'" LIMIT '.($zv['team_max']+1).'');
			$ids = '';
			
			$map_locs = array();
			$spm2 = mysql_query('SELECT `id`,`x`,`y` FROM `dungeon_map` WHERE `id_dng` = "'.$dungeon['id'].'"');
			while( $plm2 = mysql_fetch_array($spm2)) {
				$map_locs[] = array($plm2['x'],$plm2['y']);
			}
			unset($spm2,$plm2);
			
			while($pu = mysql_fetch_array($su))
			{
				$ids .= ' `id` = "'.$pu['id'].'" OR';
				$u->addAction(time(),'psh'.$dungeon['id'],'',$pu['id']);
				//��������� ��������� ������� ��� ����������
				$sp = mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` LIKE "%start_quest%" AND `vals` = "go" LIMIT 100');
				while($pl2 = mysql_fetch_array($sp))
				{
					$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.(str_replace('start_quest','',$pl2['vars'])).'" AND `line` = "'.$dungeon['id'].'" LIMIT 1'));
					if( isset($pl['id']) ) {
						$act = explode(',',$pl['act_date']);
						$i = 0;
						while( $i < count($act) ) {
							$xr = explode(':=:',$act[$i]);
							//���� ��������
							if( $xr[0] == 'tk_itm' ) {
								$xr2 = explode('=',$xr[1]);
								if( $xr2[2] == 0 ) {
									//��������� ������ ��� �����
									$j = 0;
									while( $j < $xr2[1] ) {
										$cord = $map_locs[rand(0,count($map_locs)-1)];
										if( $cord[0] != 0 || $cord[1] != 0 ) {
											mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`,`onlyfor`,`quest`) VALUES (
												"'.$zid.'","'.$u->info['id'].'","'.$xr2[0].'","'.time().'","'.$cord[0].'","'.$cord[1].'","'.$u->info['id'].'","'.$pl['id'].'"
											)');
										}
										$j++;
									}
								}else{
									//������� ��������� � ���������� �����
									mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`,`onlyfor`,`quest`) VALUES (
										"'.$zid.'","'.$u->info['id'].'","'.$xr2[0].'","'.time().'","'.$xr2[2].'","'.$xr2[3].'","'.$u->info['id'].'","'.$pl['id'].'"
									)');
								}
								//
							}
							$i++;
						}
					}
				}
				//
			}
			$ids = rtrim($ids,'OR');
			$upd1 = mysql_query('UPDATE `stats` SET `s`="1",`res_s`="1",`x`="0",`y`="0",`res_x`="0",`res_y`="0",`dn` = "0",`dnow` = "'.$zid.'" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
			if($upd1)
			{
				$upd2 = mysql_query('UPDATE `users` SET `room` = "374" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
				//��������� ����� � ������� � ������ $zid � for_dn = $dungeon['id']
				//��������� �����
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$dungeon['id'].'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['id_bot'].'","'.$pl['colvo'].'","'.$pl['items'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['dialog'].'","'.$pl['items'].'","'.$pl['go_bot'].'"),';
				}
				$vls = rtrim($vls,',');				
				$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`,`go_bot`) VALUES '.$vls.'');
				//��������� �������
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "'.$dungeon['id'].'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
				}
				$vls = rtrim($vls,',');	
				if($vls!='')
				{			
					$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
				}else{
					$ins2 = true;
				}
				if($upd2 && $ins1 && $ins2)
				{
					die('<script>location="main.php?rnd='.$code.'";</script>');
				}else{
					$re = '������ �������� � ����������...';
				}
			}else{
				$re = '������ �������� � ����������...';
			}
		}else{
			$re = '������ �������� � ����������...';
		}
	}
}elseif(isset($_POST['go'],$_POST['goid']) && $dungeonGo==1)
{
	if(!isset($zv['id']))
	{
		$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `id`="'.mysql_real_escape_string($_POST['goid']).'" AND `delete` = "0" LIMIT 1'));
		if(isset($zv['id']) && $u->info['dn'] == 0)
		{
			if( $zv['pass'] != '' && $_POST['pass_com'] != $zv['pass'] ) {
				$re = '�� ����� ������������ ������';				
			}elseif($u->info['level'] > 7)
			{
				$row = 0;
				if(5 > $row)
				{
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if(!$upd)
					{
						$re = '�� ������� �������� � ��� ������';
						unset($zv);
					}else{
						$u->info['dn'] = $zv['id'];
					}
				}else{
					$re = '� ������ ��� �����';
					unset($zv);
				}
			}else{
				$re = '�� �� ��������� �� ������';
				unset($zv);
			}
		}else{
			$re = '������ �� �������';
		}
	}else{
		$re = '�� ��� ���������� � ������';
	}
}elseif(isset($_POST['leave']) && isset($zv['id']) && $dungeonGo == 1)
{
	if($zv['uid']==$u->info['id'])
	{
		//������ � ������ ������ ������������
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ld['id']))
		{
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
} elseif( isset($_POST['add']) && $u->info['level'] > 1 && $dungeonGo == 1 ) {
	if($u->info['dn']==0)
	{		
		$ins = mysql_query('INSERT INTO `dungeon_zv`
		(`city`,`time`,`uid`,`dun`,`pass`,`com`,`lvlmin`,`lvlmax`,`team_max`) VALUES
		("'.$u->info['city'].'","'.time().'","'.$u->info['id'].'","'.$dungeon['id'].'",
		"'.mysql_real_escape_string($_POST['pass']).'",
		"'.mysql_real_escape_string($_POST['text']).'",
		"8",
		"21",
		"5")');
		if($ins)
		{
			$u->info['dn'] = mysql_insert_id();
			$zv['id'] = $u->info['dn'];
			$zv['uid'] = $u->info['id'];
			mysql_query('UPDATE `stats` SET `dn` = "'.$u->info['dn'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$re = '�� ������� ������� ������';
		}else{
			$re = '�� ������� ������� ������';
		}
	}else{
		$re = '�� ��� ���������� � ������';
	}
}

//���������� ������ �����
$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `dun` = "'.$dungeon['id'].'" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');
while($pl = mysql_fetch_array($sp)){
	$dungeonGroupList .= '<div style="padding:2px;">';
	if($u->info['dn']==0) $dungeonGroupList .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	$dungeonGroupList .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	
	$pus = ''; //������
	$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
	while($pu = mysql_fetch_array($su)){
		$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" title="���. � '.$pu['login'].'"></a>';
		$pus .= ', ';
	}
	$pus = trim($pus,', ');
	
	$dungeonGroupList .= $pus; unset($pus);
	
	if( $pl['pass'] != '' && $u->info['dn'] == 0 ) $dungeonGroupList .= ' <small><input type="password" name="pass_com" value=""></small>';
	
	if($pl['com']!='')
	{
		$dl = '';
		// ���� ���������, ���� ����������� ������� ����������� � ������.
		$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
		if(($moder['boi']==1 || $u->info['admin']>0) && $pl['dcom']==0)
		{
			$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">������� �����������</a>)';
			if(isset($_GET['delcom']) && $_GET['delcom']==$pl['id'] && $u->newAct($_GET['key'])==true)
			{
				mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['dcom'] = $u->info['id'];
			}
		}
		
		$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
		
		if($pl['dcom']>0)
		{
			$dl = ' <font color="grey"><i>����������� ������ �����������</i></font>';
		}	
		
		if($pl['dcom']>0)
		{
			if($moder['boi']==1 || $u->info['admin']>0)
			{
				$pl['com'] = '<font color="red">'.$pl['com'].'</font>';
			}else{
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
                            <tr>
                              <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.373&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.373',1); ?>">��������� �������</a></td>
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
		<br />
		<input type='button' onclick='location="main.php?rz=1"' value="�������" />
	  </p>
	</div>
<? } ?>
<?
if($re!='')echo '<font color="red"><b>'.$re.'</b></font><br>';

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
$qx = 0;
//if($u->info['admin']==0){
	$hgo = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_capitalcity" LIMIT 1',1);
//}
if(isset($_GET['add_quest'])){
	if(isset($hgo['id'])) {
		echo '<font color="red"><b>������ �������� ������� ���� ������ ���� � �����</b></font><br>';
	} else {
		
//if($u->info['admin']>0){
//	$sp = mysql_query('SELECT * FROM `quests` WHERE `id` = "236" && `tr_date` LIKE "%tr_dn:=:'.$dun.'%"'); // ������ ����� 
//} else {
	$sp = mysql_query('SELECT * FROM `quests` WHERE `line` = '.$dungeon['id'].'');
//}

		$dq_add = array();
		while($pl = mysql_fetch_array($sp)) {
			if($u->rep['repcapitalcity'] == 9999) {
				//����, ���������� �������
				if( $pl['kin'] == 1 ) {
					$dq_add = array( 0 => $pl );
				}
			} elseif($u->rep['repcapitalcity'] == 24999) {
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
		
		
		if( $q->testGood($dq_add) == 1 && $dq_add > 0 )
		{
			$q->startq_dn($dq_add['id']);
			echo '<font color="red"><b>�� ������� �������� ����� ������� &quot;'.$dq_add['name'].'&quot;.</b></font><br>'; 
			$u->addAction(time(),'psh_qt_capitalcity',$dq_add['id']);
		} else {
			if( $u->rep['repcapitalcity'] == 9999 ) {
				//����, ���������� �������
				echo '<font color="red"><b>�� ��� �������� ������� �� ���������� ������ ������!</b></font><br>';
			}elseif( $u->rep['repcapitalcity'] == 24999 ) {
				//����, ���������� �������
				echo '<font color="red"><b>�� ��������� ��������� �����, �������� ����� �������!</b></font><br>';
			}else{
				echo '<font color="red"><b>�� ������� �������� ������� &quot;'.$dq_add['name'].'&quot;. ���������� ���...</b></font><br>';
			}	
		}
		unset($dq_add);
	}
}

//���������� ������ ������� �������
$sp = mysql_query('SELECT * FROM `actions` WHERE `vars` LIKE "%start_quest%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 100');
while($pl = mysql_fetch_array($sp))
{
	$pq = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$pl['vars']).'" LIMIT 1'));
	$qsee .= '<a href="main.php?rz=1&end_qst_now='.$pq['id'].'"><img src="http://img.xcombats.com/i/clear.gif" title="���������� �� �������"></a> <b>'.$pq['name'].'</b><div style="padding-left:15px;padding-bottom:5px;border-bottom:1px solid grey"><small>'.$pq['info'].'<br>'.$q->info($pq).'</small></div><br>';
	$qx++;
}

if($qsee == '')
{
	$qsee = '� ��������� � ��� ��� �� ������ �������';
}
?>
<Br />
<FIELDSET>
<LEGEND><B>������� �������: </B>[<?=$qx?>/28]</LEGEND>
<?=$qsee?>
<span style="padding-left: 10">
<?
if(!isset($hgo['id'])) {
?>
<br />
<input type='button' value='�������� �������' onclick='location="main.php?rz=1&add_quest=1"' />
<?
}else{ 
	echo '�������� ����� ������� ����� <b>'.date('d.m.Y H:i',$hgo['time']+60*60*24).'</b> <font color="">( ����� '.$u->timeOut($hgo['time']+60*60*24-time()).' )</font>';
}
?>
</span>
</FIELDSET>
	</form>
	<br />
	<? 
	//���������� ������ �������
	if(isset($_GET['buy1'])) { 
		  if($_GET['buy1']==1) {
			  //�������� �����
			  $price = 2000+($u->rep['add_stats']*100);
			  $cur_price = array('price'=>0);
			  if(25-$u->rep['add_stats']>0 && $u->rep['allrep']-$u->rep['allnurep']>=$price) { // ��������������!
				  
				  foreach($dungeon['list'] as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  }
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>�� ������� ��������� 1 ����������� �� '.$price.' ��. �������</b></font><br>';
					$u->info['ability']  += 1;
					$u->rep['add_stats'] += 1;
					
					foreach($dungeon['list'] as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_stats` = "'.$u->rep['add_stats'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `ability` = "'.$u->info['ability'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo '������������ ���������.';
			  } else {
				 echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			  }
		  } elseif($_GET['buy1']==2) { // ������!
			  $price = 2000+(2000*$u->rep['add_skills']);
			  $cur_price = array('price'=>0); 
			  if(10-$u->rep['add_skills']>0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // ������!
				   foreach($dungeon['list'] as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  }
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>�� ������� ��������� 1 ������ �� '.$price.' ��. �������</b></font><br>';
					$u->info['skills']  += 1;
					$u->rep['add_skills'] += 1;
					
					foreach($dungeon['list'] as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_skills` = "'.$u->rep['add_skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `skills` = "'.$u->info['skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo '������������ ���������.';
			  } else {
				 echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			  }
		  }elseif($_GET['buy1']==3) { // �������
			  $price = 100;
			  $cur_price = array('price'=>0); 
			  if( $u->rep['allrep'] - $u->rep['allnurep'] >= $price) { // �������� �������
				  foreach($dungeon['list'] as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  } 
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>�� ������� ��������� 10 ��. �� '.$price.' ��. �������</b></font><br>';
					$u->info['money']  += 10;
					$u->rep['add_money'] += 10;
					
					foreach($dungeon['list'] as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_money` = "'.$u->rep['add_money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo '������������ ���������.'; 
			  }else{
				 echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			  }
		  }elseif( $_GET['buy1'] == 4 ) { // �����������
			  $price = 3000;
			  $cur_price = array('price'=>0);
			  if( 5 - $u->rep['add_skills2'] > 0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // �����������
				  foreach($dungeon['list'] as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  }
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>�� ������� ��������� 1 ����������� �� '.$price.' ��. �������</b></font><br>';
					$u->info['sskills']  += 1;
					$u->rep['add_skills2'] += 1;
					
					foreach($dungeon['list'] as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_skills2` = "'.$u->rep['add_skills2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `sskills` = "'.$u->info['sskills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo '������������ ���������.'; 
				  
				 // echo '<font color="red"><b>�� ������� ��������� 1 ����������� �� 3000 ��. �������</b></font><br>'; 
				 // $u->info['nskills']  += 1;
				 // $u->rep['nu_capitalcity'] += 3000;
				 // $u->rep['add_skills2'] += 1;
				 // mysql_query('UPDATE `rep` SET `add_skills2` = `add_skills2`+1,`nu_capitalcity` = "'.$u->rep['nu_capitalcity'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				 // mysql_query('UPDATE `stats` SET `nskills` = "'.$u->info['nskills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			  }else{
				 echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			  }
	  	}
	  }
	  ?>
      <fieldset>
        <legend>�������: <b>
        <?=($u->rep['allrep']-$u->rep['allnurep'])?> 
        ��.</b></legend>
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
        <p><span style="padding-left: 10px">
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
			<td width="200">��������� � Capital city:</td>
			<td><?=$u->rep['repcapitalcity']?> ��. </td>
		  </tr>
          <tr>
			<td>��������� � Demons city:</td>
			<td><?=$u->rep['repdemonscity']?> ��.  </td>
		  </tr>
          <tr>
			<td>��������� � Angels city:</td>
			<td><?=$u->rep['repangelscity']?> ��. </td>
		  </tr> 
        </table>
        <legend>������� ���������:</legend> 
      </fieldset>
</div>
<?
	} else {
		if($dungeonGo == 1){
			if($u->info['dn']==0){
			?>
			<table width="350" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td valign="top">
				<form id="from" autocomplete="off" name="from" action="main.php?pz1=<? echo $code; ?>" method="post">
				  <fieldset style='padding-left: 5; width=50%'>
				  <legend><b> ������ </b> </legend>
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
			} else {
				$psh_start = '';
				if(isset($zv['id'])){
					if($zv['uid']==$u->info['id']){
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
		}else{
			echo '����� � ������ �������� ���� ��� � ��� ����. �������� ���: '.$u->timeOut(60*60*3-time()+$dungeon_timeout['time']).'<br><small style="color:grey">�� �� ������ ������ ���������� ���� �� ������� � ������ &quot;�������� �����&quot; � �������� ���� ;)</small>';
		}
	}
}
?>
