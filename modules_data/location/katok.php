<?
if(!defined('GAME')) { die(); }
if($u->room['file']=='katok') {
	
$ku = mysql_fetch_array(mysql_query('SELECT * FROM `katok_zv` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
//
$tcount = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `katok_zv` LIMIT 1'));
$tcount = 0 + $tcount[0];
//	
if(isset($_POST['join'])) {
	if($tcount >= 12) {
		$u->error = '������ ������������! ����� �������� ���� ������ � �� ������� ������ ������ �� �����!';
	}elseif(!isset($ku['id'])) {
		//
		$team = 0; //�� ���������������� ������
		//
		mysql_query('INSERT INTO `katok_zv` (
			`uid`,`time`,`team`
		) VALUES (
			"'.$u->info['id'].'","'.time().'","'.$team.'"
		)');
		//
		$ku = mysql_fetch_array(mysql_query('SELECT * FROM `katok_zv` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ku['id'])) {
			$tcount++;
		}
		//
		$u->error = '�� ������� ������� ������ �� ������� � �������!';
	}
}elseif(isset($_POST['cancel'])) {
	if(isset($ku['id'])) {
		mysql_query('DELETE FROM `katok_zv` WHERE `uid` = "'.$u->info['id'].'"');
		unset($ku);
		$tcount--;
		$u->error = '�� �������� ������ �� ������� � �������.';
	}
}

if($tcount >= 6 ) {
	
	//������� ������
	mysql_query('INSERT INTO `dungeon_now` (
		`id2` , `name` , `time_start` , `time_finish` , `uid` , `city` , `type` , `bsid`
	) VALUES (
		"15" , "������" , "'.time().'" , "0" , "0" , "'.$u->info['city'].'" , "0" , "2015"
	)');
	$dnew = mysql_insert_id();	
	
	//����������� �������: �������, ������ + ����� (������ ���� ���������) , ������ , �����
	//��������� �������
    $vls32 = '';
	$sphj = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "15"');
	while($plhj = mysql_fetch_array($sphj)) {
		$vls32 .= '("'.$dnew.'","'.$plhj['name'].'","'.$plhj['img'].'","'.$plhj['x'].'","'.$plhj['y'].'","'.$plhj['action'].'","'.$plhj['type'].'","'.$plhj['w'].'","'.$plhj['h'].'","'.$plhj['s'].'","'.$plhj['s2'].'","'.$plhj['os1'].'","'.$plhj['os2'].'","'.$plhj['os3'].'","'.$plhj['os4'].'","'.$plhj['type2'].'","'.$plhj['top'].'","'.$plhj['left'].'","'.$plhj['date'].'"),';
	}
	$vls32 = rtrim($vls32,',');
	if($vls32!='') {
		$ins232 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls32.'');
	}
	unset($vls32,$ins232);

	
	//����������� ��������
	
	
	//����������� ������� (������� ����� � ������ �� �� �������) � ������� ������� + ������������ � ������
	$sp = mysql_query('SELECT * FROM `katok_zv`');
	$tmr = rand(1,2);
	while($pl = mysql_fetch_array($sp)) {
		$bus = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
		if(isset($bus['id'])) {
			if( $tmr == 1 ) {
				$tmr = 2;
			}else{
				$tmr = 1;
			}
			//
			$pl['team'] = $tmr;
			//������� ���� + ������ ��������
			if( $bus['align'] >= 1 && $bus['align'] < 2 ) {
				$bus['align'] = 1;
			}elseif( $bus['align'] >= 3 && $bus['align'] < 4 ) {
				$bus['align'] = 3;
			}elseif( $bus['align'] == 7 ) {
				$bus['align'] = 7;
			}else{
				$bus['align'] = 0;
			}
			//
			if( $pl['team'] == 1 ) {
				$obraz = 'ih59.gif';
			}elseif( $pl['team'] == 2 ) {
				$obraz = 'ih60.gif';
			}
			//
			mysql_query('INSERT INTO `users` (`obraz`,`chatColor`,`align`,`inTurnir`,`molch1`,`molch2`,`activ`,`login`,`room`,`name`,`sex`,`level`,`bithday`) VALUES (
				"'.$obraz.'","'.$bus['chatColor'].'","'.$bus['align'].'","'.$pl['id'].'","'.$bus['molch1'].'","'.$bus['molch2'].'","0","'.$bus['login'].'","411","'.$bus['name'].'","'.$bus['sex'].'","4","'.date('d.m.Y').'")');
			//
			$inbot = mysql_insert_id(); //���� ����
			if( $inbot > 0 ) {
				//���
				$mp = rand(0,count($mapu)-1);
				// X: 9,Y: 14 ��� 1 , 0
				if( $pl['team'] == 1 ) {
					$x1 = 1;
					$y1 = 0;
					$rx = 1;
					$ry = 0;
				}else{
					$x1 = 9;
					$y1 = 14;
					$rx = 9;
					$ry = 14;
				}
				unset($mapu[$mp]);
				//
				mysql_query('INSERT INTO `stats` (`res_x`,`res_y`,`timeGo`,`timeGoL`,`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`)
				VALUES (
					"'.$rx.'","'.$ry.'",
					"'.(time()+180).'","'.(time()+180).'","98","'.$dnew.'","'.$inbot.'",
					"s1=3|s2=3|s3=3|s4=7|s5=0|s6=0|rinv=40|m9=5|m6=10","0",
					"39","5","'.$x1.'","'.$y1.'"
				)');
				//������ ��������
				$u->addItem(4815,$inbot);
				if($pl['team'] == 1) {
					$u->addItem(4816,$inbot);
					$u->addItem(4818,$inbot);
					$u->addItem(4820,$inbot);
					$u->addItem(4822,$inbot);
					$u->addItem(4824,$inbot);
				}elseif($pl['team'] == 2) {
					$u->addItem(4817,$inbot);
					$u->addItem(4819,$inbot);
					$u->addItem(4821,$inbot);
					$u->addItem(4823,$inbot);
					$u->addItem(4825,$inbot);
				}
				//
				mysql_query('UPDATE `users` SET `room` = "410", `inUser` = "'.$inbot.'" WHERE `id` = "'.$bus['id'].'" LIMIT 1');
				//
			}
			//��������� ����
			//
			mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`img2`) VALUES (
				"2","'.$inbot.'","����","add_speedhp=30000|add_speedmp=30000|puti='.(time()+180).'","1","'.(time()+180).'","chains.gif"
			) ');
			//			
		}
		//������� ������
		mysql_query('DELETE FROM `katok_zv` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		//
		mysql_query('INSERT INTO `katok_now` (
			`uid`,`time`,`team`,`clone`
		) VALUES (
			"'.$pl['uid'].'","'.time().'","'.$pl['team'].'","'.$inbot.'"
		)');
		//
	}	
	die('<font color=red>������ �����...</font><script>setTimeout("location.href=\'/main.php\';",2000);</script>');
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
    <td>
    <div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div><?
    if($u->error != '') {
		echo '<font color="red"><b>'.$u->error.'</b></font><br>';
	}
    if($re != '') {
		echo '<font color="red"><b>'.$re.'</b></font><br>';
	}
	?><br />
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><form id="from" autocomplete="off" name="from" action="main.php?join=<? echo $code; ?>" method="post">
          <fieldset style='padding-left: 5; width=50%'>
            <legend><b> ������ </b></legend>
            <b>����� ������:</b> <?=$tcount?> &nbsp; <? if(!isset($ku['id'])){ ?><input type="submit" name="join" value="�������." /><? }else{ ?><input type="submit" name="cancel" value="��������" /><? } ?> &nbsp; <input onclick="location.href='/main.php';" type="button" name="cancel" value="��������" />
          </fieldset>
        </form></td>
      </tr>
    </table>
    <br />
    <b>������� �������:</b><br />
    <br /><font color=red>&bull; �� ����� ������ ��������� 6 ������� ��� ������!</font><br />
	&bull; ��� ������ ���� ���������� ������� 12 ������ - ��� 2 ������� �� 6 ����������. ������� ������� �������� �� ����� �.�. � ������ ���� ��� ��������� ������������ � ����� ���� [4] ������. 
	<Br />&bull; ��� ������ ���������� 12 ������, ������������� �������� 2 ������� � ���������� ����. ������ �������������� � ������� ��������.
    <br />&bull; �������� �� ��������� 1 ��� ����� ������ � ������, ���� �� ����� �� ����� ����, ����� �� �� ������� �������� ����� ��� 4 ����.
    <br />&bull; �� �� ������� ����� � ����� ���� �� ���������� ����
    </td>
    <td width="200" valign="top"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td>
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
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.323&rnd=<? echo $code; ?>';">����� � �����</a></td>
                            </tr>
                        </table>
						</td>
                      </tr>
                  </table></td>
              </tr>
          </table>
          </td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<? } ?>
