<?php
if(!defined('GAME')) { die(); }

if(isset($_GET['r'])) {
  $_GET['r'] = (int)$_GET['r'];
} else {
  $_GET['r'] = null;	
}

if($u->info['inTurnir'] > 0 && $u->info['inUser'] == 0 && $u->info['room'] == 318) {
  die('<script>location="main.php";</script>');
}


//$u->info['referals'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `register_code` WHERE `uid` = "'.$u->info['id'].'" AND `time_finish` > 0 AND `end` = 0 LIMIT 1000'));			 
//$u->info['referals'] = $u->info['referals'][0];

include('_incl_data/class/__zv.php');

?>
<script> var zv_Priem = 0; </script>
<style> 
.m { background: #99CCCC; text-align: center; }
.s { background: #BBDDDD; text-align: center; }
</style>
<TABLE width=100% cellspacing=1 cellpadding=3>
<TR><TD colspan=8 align=right>
<div style="float:left">
	<? echo $zv->userInfo();
	echo '<script>top.lafstReg['.$u->info['id'].'] = 0; top.startHpRegen("main",'.$u->info['id'].','.(0+$u->stats['hpNow']).','.(0+$u->stats['hpAll']).','.(0+$u->stats['mpNow']).','.(0+$u->stats['mpAll']).','.(time()-$u->info['regHP']).','.(time()-$u->info['regMP']).','.(0+$u->rgd[0]).','.(0+$u->rgd[1]).',1);</script>';
	?>
</div>
<div style="float:right;">
  <INPUT class="btnnew" TYPE=button value="���������" onClick="location.href='main.php?rnd=<? echo $code; ?>';">
</div>
</td></tr>
<tr>
<? if( $u->info['level'] == 0 ) { ?>
<td width="13%" class="<? if($_GET['r'] == 1) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=1&rnd=<? echo $code; ?>">�������</a></td>
<td width="13%" class="<? if($_GET['r'] == 2) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=2&rnd=<? echo $code; ?>">����������</a></td>
<td width="12%" class="<? if($_GET['r'] == 3) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=3&rnd=<? echo $code; ?>">����������</a></td>
<td width="12%" class="<? if($_GET['r'] == 4) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=4&rnd=<? echo $code; ?>">���������</a></td>
<td width="12%" class="<? if($_GET['r'] == 5) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=5&rnd=<? echo $code; ?>">���������</a></td>
<td width="12%" class="<? if($_GET['r'] == 8) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=8&rnd=<? echo $code; ?>">�������</a></td>
<td width="12%" class="<? if($_GET['r'] == 6) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=6&rnd=<? echo $code; ?>">�������</a></td>
<td class="<? if($_GET['r'] == 7) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=7&rnd=<? echo $code; ?>">�����������</a></td>
<? }else{ ?>
<td width="14%" class="<? if($_GET['r'] == 2) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=2&rnd=<? echo $code; ?>">����������</a></td>
<td width="14%" class="<? if($_GET['r'] == 3) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=3&rnd=<? echo $code; ?>">����������</a></td>
<td width="14%" class="<? if($_GET['r'] == 4) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=4&rnd=<? echo $code; ?>">���������</a></td>
<td width="14%" class="<? if($_GET['r'] == 5) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=5&rnd=<? echo $code; ?>">���������</a></td>
<td width="14%" class="<? if($_GET['r'] == 8) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=8&rnd=<? echo $code; ?>">�������</a></td>
<td width="14%" class="<? if($_GET['r'] == 6) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=6&rnd=<? echo $code; ?>">�������</a></td>
<td class="<? if($_GET['r'] == 7) { echo 's'; } else { echo 'm'; } ?>"><a href="main.php?zayvka=1&r=7&rnd=<? echo $code; ?>">�����������</a></td>

<? } ?>
</tr></tr></table>
<script>
function console_clonelogin() {
	var s = prompt("������� ����� ��������� � ������� ������ ���������:", "");
	if ((s != null) && (s != '')) {
		location.href="main.php?zayvka=1&r=2&bot_clone="+s+"&rnd=1";
	}
}
</script>
<div style="padding:2px;">
<?
$zi = false;

if($u->info['battle'] == 0) {
  if(isset($_POST['add_new_zv'])) {
	$zv->add();
  } elseif(isset($_GET['bot']) && ( $u->info['level'] <= 7 || $u->info['admin'] > 0)) {
	$zv->addBot();
  } elseif(isset($_GET['bot_clone'])) {
	  $zvclone = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `admin` = 0 AND `real` = 1 AND `login` = "'.mysql_real_escape_string($_GET['bot_clone']).'" ORDER BY `id` ASC LIMIT 1'));
	  $zv->addBotClone($zvclone['id']);
  } elseif(isset($_GET['add_group'])) {
	$zv->add();
  } elseif(isset($_GET['start_haot'])) {
	$zv->add();
  }
}

if($u->info['zv'] != 0) {
  $zi = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id`="'.$u->info['zv'].'" /*AND `city` = "'.$u->info['city'].'"*/ AND `start` = "0" AND `cancel` = "0" AND (`time` > "'.(time()-60*60*2).'" OR `razdel` > 3) LIMIT 1'));
  if(!isset($zi['id'])) {
	$zi = false;
	$u->info['zv'] = 0;
	mysql_query('UPDATE `stats` SET `zv` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
  }
}

if($u->info['battle'] == 0) {
  if(isset($_POST['groupClick']) && !isset($zi['id'])) {
	$zg = mysql_fetch_array(mysql_query('SELECT * FROM `zayvki` WHERE `id` = "'.mysql_real_escape_string((int)$_POST['groupClick']).'" AND `cancel` = "0" AND `btl_id` = "0" /*AND `city` = "'.$u->info['city'].'"*/ AND `razdel` = "4" AND `start` = "0" AND `time` > "'.(time()-60*60*2).'" LIMIT 1'));
	if(!isset($zg['id'])) {
	  echo '<center><br /><br />������ �� ��������� ��� �� �������.</center>';
	} else {
	  $tm_start = floor(($zg['time']+$zg['time_start']-time())/6)/10;
	  $tm_start = $zv->rzv($tm_start);
	  $tm1 = ''; $tm2 = '';
	  $users = mysql_query('SELECT `u`.`id`, `u`.`login`, `u`.`level`, `u`.`align`, `u`.`clan`, `u`.`admin`, `st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `u`.`id` = `st`.`id` WHERE `st`.`zv` = "'.$zg['id'].'"');
	  while($s = mysql_fetch_array($users)) {
		${'tm'.$s['team']} .= '<b>'.$s['login'].'</b> ['.$s['level'].']<a href="info/'.$s['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" title="���. � '.$s['login'].'" /></a><br />';
	  }					
	  if($tm1 == '') {
		$tm1 = '������ ���� �� �������';
	  } else {
		$tm1 = rtrim($tm1, '<br />');
	  }
	  if($tm2 == '') {
		$tm2 = '������ ���� �� �������';
	  } else {
		$tm2 = rtrim($tm2, '<br />');
	  }
	  //
	  if( $zg['teams'] == 3 ) {
		  if($tm3 == '') {
			$tm3 = '������ ���� �� �������';
		  } else {
			$tm3 = rtrim($tm3, '<br />');
		  }
	  }
	  //
	  $sv1 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$zg['id'].'" AND `team` = "1" LIMIT 100'));
	  $sv2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$zg['id'].'" AND `team` = "2" LIMIT 100'));
	  $sv3 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `zv` = "'.$zg['id'].'" AND `team` = "3" LIMIT 100'));
	  $sv1 = $zg['tm1max']-$sv1[0];
	  $sv2 = $zg['tm2max']-$sv2[0];
	  //
	  if( $zg['teams'] == 3 ) {
	  	$sv3 = $zg['tm2max']-$sv3[0];
	  }
	  //
?></div>
<table style="margin-top:2px;" width="100%">
  <tr>
    <td> ��� �������� ����� <? echo $tm_start; ?> ���. </td>
    <td align="right">
    <input class="btnnew" type="button" value="���������" onclick="location.href='main.php?zayvka&r=<? echo $_GET['r']; ?>&rnd=<? echo $code; ?>';">
    </td>
  </tr>
</table>
<h3 align="center">�� ���� ������� ������ ���������?</h3>
<table align="center" cellspacing="4" cellpadding="1">
  <tr>
    <td bgcolor="99CCCC"><b>������ ����:</b><br />
      ������������ ���-��: <? echo $zg['tm1max']; ?><br />
      ����������� �� ������: <? echo $zg['min_lvl_1'].' - '.$zg['max_lvl_1']; ?></td>
    <td bgcolor="99CCCC"><b>������ ���:</b><br />
      ������������ ���-��: <? echo $zg['tm2max']; ?><br />
      ����������� �� ������: <? echo $zg['min_lvl_2'].' - '.$zg['max_lvl_2']; ?> </td>
      <?
      if( $zg['teams'] == 3 ) { 
		  ?>
        <td bgcolor="99CCCC"><b>������ ���:</b><br />
          ������������ ���-��: <? echo $zg['tm2max']; ?><br />
          ����������� �� ������: <? echo $zg['min_lvl_2'].' - '.$zg['max_lvl_2']; ?> </td>
          <?
	  }
	  ?>
  </tr>
  <tr>
    <td align="center"><? echo $tm1; ?>
    	<br />
    </td>
    <td align="center"><? echo $tm2; ?>
        <br />
    </td>
      <?
      if( $zg['teams'] == 3 ) { 
		  ?>
    <td align="center"><? echo $tm3; ?>
       <br />
    </td>
          <?
	  }
	  ?>
  </tr>
  <tr>
    <td align="center"><input class="btnnew" title="�� ������ ������ �������� ����: <? echo $sv1; ?>" onclick="location='main.php?r=<? echo $_GET['r']; ?>&zayvka&btl_go=<? echo $zg['id']; ?>&tm1=<? echo $code; ?>'" type="submit" name="confirm1" value="� �� ����!" /></td>
    <td align="center"><input class="btnnew" title="�� ������ ������ �������� ����: <? echo $sv2; ?>" onclick="location='main.php?r=<? echo $_GET['r']; ?>&zayvka&btl_go=<? echo $zg['id']; ?>&tm2=<? echo $code; ?>'" type="submit" name="confirm2" value="� �� ����!" /></td>
      <?
      if( $zg['teams'] == 3 ) { 
		  ?>
    <td align="center"><input class="btnnew" title="�� ������ ������ �������� ����: <? echo $sv3; ?>" onclick="location='main.php?r=<? echo $_GET['r']; ?>&zayvka&btl_go=<? echo $zg['id']; ?>&tm3=<? echo $code; ?>'" type="submit" name="confirm3" value="� �� ����!" /></td>
          <?
	  }
	  ?>
  </tr>
</table>
<?
	}
	} elseif(isset($_GET['cancelzv']) && !isset($_POST['add_new_zv'])) {
		$zv->cancelzv();
	} elseif(isset($_GET['startBattle']) && isset($zi['id']) && ($zi['razdel'] >= 1 || $zi['razdel'] <= 3)) {
		$zv->startBattle($zi['id']);
	} elseif($u->info['level'] >= 2 && $_GET['r'] == 4 && isset($_GET['new_group']) && !isset($zi['id'])) {
		//����� ������ ������ ��� ���������� ���
		?>
<div style="float:right;">
	<INPUT class="btnnew" onClick="location='main.php?zayvka&r=<? echo $_GET['r']; ?>&rnd=<? echo $code; ?>';" TYPE=button name=tmp value="��������">
</div>
<form method="post" action="main.php?zayvka&r=<? echo $_GET['r']; ?>&add_group&rnd=<? echo $code; ?>">
<table>
  <tr>
    <td><h3>������ ������ �� ��������� ���</h3>
      ������ ��� �����
      <select name="startime">
          <option value="300">5 ����� </option>
        <option value="600">10 ����� </option>
        <option value="900">15 ����� </option>
        <option value="1200">20 ����� </option>
        <option value="1800">30 ����� </option>
      </select>
      &nbsp;&nbsp;&nbsp;&nbsp;�������
      <select name="timeout">
        <option value="1">1 ���.</option>
        <option value="2">2 ���.</option>
        <option value="3">3 ���.</option>
        <option value="4">4 ���.</option>
        <option value="5">5 ���.</option>
      </select>
      <br />
      <br />
      ���� �������
      <input type="text" name="nlogin1" size="3" maxlength="2" />      
      ������
      <br />
      ������ ��������� &nbsp;&nbsp;
      <select name="levellogin1">
        <option value="0">����� </option>
        <option value="1">������ ����� � ���� </option>
        <option value="2">������ ���� ����� ������ </option>
        <option value="3">������ ����� ������ </option>
        <option value="4">�� ������ ���� ����� ��� �� ������� </option>
        <option value="5">�� ������ ���� ����� ��� �� ������� </option>
        <option value="6">��� ������� +/- 1 </option>
        <option value="99">��� ���� </option>
        <option value="98">��� ���������� </option>
      </select>
      <br />
      <br />
      ���������� &nbsp;&nbsp;
      <input type="text" name="nlogin2" size="3" maxlength="2" />
      ������<? /*, 
      <input type="checkbox" value="1" name="bots2" id="bots2" />
      ������� ������ � ������*/ ?><br />
      ������ �����������
      <select name="levellogin2">
        <option value="0">����� </option>
        <option value="1">������ ����� � ���� </option>
        <option value="2">������ ���� ����� ������ </option>
        <option value="3">������ ����� ������ </option>
        <option value="4">�� ������ ���� ����� ��� �� ������� </option>
        <option value="5">�� ������ ���� ����� ��� �� ������� </option>
        <option value="6">��� ������� +/- 1 </option>
        <option value="99">������ ���� </option>
        <option value="98">������ ���������� </option>
      </select>
      <br />
      <input type="checkbox" name="k" value="1" />
      �������� ���<br />
      <input type="checkbox" name="travma" />
      ��� ��� ������ (<font class="dsc">����������� ������� �������� ������������</font>)<br />
      <input type="checkbox" name="3align" />
      ������������� ��� (<font class="dsc">����� ��� ���� ������ ���� ������ ���������</font>)<br />
      <input type="checkbox" name="mut_clever" />
      ����������� ���� (<font class="dsc">����������� ���� ��� ��������� ����������</font>)<br />
      ����������� � ���
      <input type="text" name="cmt" maxlength="40" size="40" />
    </td>
  </tr>
  <tr>
    <td align="center"><input class="btnnew" type="submit" value="������ ��������! :)" name="open" />
    </td>
  </tr>
</table>
</form>
<?
	}
}

if(isset($_POST['btl_go'])) {
  $zv->go($_POST['btl_go']);
} elseif(isset($_GET['btl_go'])) {
	$zv->go($_GET['btl_go']);
}

if($zv->error != '') {
  echo '<font color="red"><b>'.$zv->error.'</b></font><br />';
}

if($test_s != '') {
  echo '<font color="red"><b>'.$test_s.'</b></font><br />';
}
?>
<table style="padding:2px;" width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><? echo $zv->see(); ?></td>
  </tr>
  <tr>
    <td><? $zv->seeZv(); ?></td>
  </tr>
</table><br />
<div align="right">
<? echo $c['counters']; ?>
</div>