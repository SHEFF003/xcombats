<?
if(!defined('GAME'))
{
	die();
}

$uShow = explode('|',$u->info['showmenu']);
if(isset($_GET['showmenu']))
{
	$_GET['showmenu'] = round($_GET['showmenu']);
	if($_GET['showmenu']>=1 && $_GET['showmenu']<=8)
	{
	 	if($uShow[$_GET['showmenu']-1]==0)
		{
			$uShow[$_GET['showmenu']-1] = 1;
		}else{
			$uShow[$_GET['showmenu']-1] = 0;
		}
		$u->info['showmenu'] = implode('|',$uShow);
		mysql_query('UPDATE `stats` SET `showmenu`="'.$u->info['showmenu'].'" WHERE `id`="'.$u->info['id'].'"');
	} 
}
?>
<style type="text/css">
.linestl1 {
	background-color: #E2E0E0;
	font-size: 10px;
	font-weight: bold;
}
</style>
<script>
function getLine(id,name,a,b,o,id2)
{
	var tss = '<td width="20"><img src="http://img.xcombats.com/i/minus.gif" style="display:block;cursor:pointer;margin-bottom:3px;" title="������" class="btn-slide" onClick="location=\'main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&showmenu='+id2+'&rnd=<? echo $code; ?>\';"></td>';
	if(o==0)
	{
		tss ='<td width="20"><img src="http://img.xcombats.com/i/plus.gif" style="display:block;cursor:pointer;margin-bottom:3px;" title="��������" class="btn-slide" onClick="location=\'main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&showmenu='+id2+'&rnd=<? echo $code; ?>\';"></td>';
	}
	var sts01 = '<a href="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&up='+id+'&rnd=<? echo $code; ?>"><img style="display:block;float:right; margin-bottom:3px;" src="http://img.xcombats.com/i/3.gif"></a>';
	if(id==0)
	{
		sts01 = '<img style="display:block;float:right;margin-bottom:3px;" src="http://img.xcombats.com/i/4.gif">';
	}
	var sts02 = '<a href="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&down='+id+'&rnd=<? echo $code; ?>"><img style="display:block; margin-bottom:3px; float:right;" src="http://img.xcombats.com/i/1.gif"></a>';
	if(id==7)
	{
		sts02 = '<img style="display:block;float:right;margin-bottom:3px;" src="http://img.xcombats.com/i/2.gif">';
	}
	var sts2 = '<td width="40"><div style="float:right;">'+sts01+''+sts02+'</div></td>';
	document.write('<table class="mroinv" width="100%" border="0" cellspacing="2" cellpadding="0">'+
	'<tr>'+tss+
    '<td style="font-size:9px;"><span class="linestl1">&nbsp;'+name+'&nbsp;</span></td>'+sts2+'</tr>'+ 
	'</table>');
}

function showDiv (id)
{
	var block = document.getElementById('block_'+id);
	block.style.display = 'block';	
}
function hiddenDiv (id)
{
	var block = document.getElementById('block_'+id);
	block.style.display = 'none';	
}
<?
$rb = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `bank` WHERE `block` = 0 AND `uid` = "'.$u->info['id'].'"'));
?>
function bank_info() {
<? if(!isset($u->bank['id']) && $rb[0]==0){ ?>
	alert('� ��� ��� �������� ������. \n\n �� ������ �������: �� ������ ������� ���� � ����� "<? echo $c['title3']; ?>",'+
		' �� ������������ �����*\n\n* ������ �������: ������ �������.');
<?
}elseif($rb[0]>0){ 
?>
				var ddtpswBank = '<div><form action="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&rnd=<? echo $code; ?>" method="post">'+
				        '<table style="border:1px solid #B1A996;" width="300" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#B1A996"><div align="center"><strong>���� � �����</strong><a href="javascript:void(0)" onclick="document.getElementById(\'chpassbank\').style.display=\'none\'" title="������� ����" style="float:right;padding-right:5px;">x</a></div></td></tr><tr><td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>�������� ���� � ������� ������<br />'+
                        '<select name="bank" id="bank">'+
						<?
                        $scet = mysql_query('SELECT `id` FROM `bank` WHERE `block` = "0" AND `uid` = "'.$u->info['id'].'"');
                        while ($num_scet = mysql_fetch_array($scet)) 
                        {
                       		 echo "'<option>".$u->getNum($num_scet['id'])."</option>'+";
                        }
						?>
                        '</select><input style="margin-left:5px;" type="password" name="bankpsw" id="bankpsw" /><label></label></small><input style="margin-left:3px;" type="submit" name="button" id="button" value=" ok " /></div></td></tr></table></form></div>';
						var ddtpsBankDiv = document.getElementById('chpassbank');
						if(ddtpsBankDiv!=undefined)
						{
							ddtpsBankDiv.style.display = '';
							ddtpsBankDiv.innerHTML = ddtpswBank;
						}
<?
}
?>
}
function save_com_can()
{
	var ddtpsBankDiv = document.getElementById('chpassbank');
	if(ddtpsBankDiv!=undefined)
	{
		ddtpsBankDiv.style.display = 'none';
		ddtpsBankDiv.innerHTML = '';
	}
}
function save_compl()
{
				var ddtpswBank = '<div><form action="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&rnd=<? echo $code; ?>" method="post">'+
				        '<table style="border:1px solid #B1A996;" width="250" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#B1A996"><div align="center"><strong>��������� ��������</strong></div></td></tr><tr><td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>������� �������� ������� ���������:<br />'+
                        '<input style="width:90%;" type="text" name="compname" value="" id="compname" /><label></label></small><br><input style="margin-left:3px;cursor:pointer;" type="submit" name="button" id="button" value=" ��������� " /><input style="margin-left:3px;cursor:pointer;" onClick="save_com_can();" type="button" value=" ������ " /></div></td></tr></table></form></div>';
						var ddtpsBankDiv = document.getElementById('chpassbank');
						if(ddtpsBankDiv!=undefined)
						{
							ddtpsBankDiv.style.display = '';
							ddtpsBankDiv.innerHTML = ddtpswBank;
						}
}
function za_block(id) {
	if($('#za_block_'+id).css('display') == 'none') {
		$('#za_block_'+id).css('display','');
	}else{
		$('#za_block_'+id).css('display','none');
	}
}
</script>
<style>
.mroinv {
	/*background-color:#e2e2e2;border-top:1px solid #eeeeee;border-left:1px solid #eeeeee;border-right:1px solid #a0a0a0;border-bottom:1px solid #a0a0a0;*/
	background:url(http://img.xcombats.com/i/back.gif) 0 2px;
}
.mroinv img {
	display:inline-block;
	border:0;
	padding-top:3px;
	padding-left:1px;
}
.dot {
	display:block;
	padding-bottom:2px;
    text-decoration: none; /* ������� ������������� */
    border-bottom: 1px dotted #080808; /* ��������� ���� ����� */
	cursor:pointer;
}
.dot:hover {
    border-bottom: 1px dotted #080808; /* ��������� ���� ����� */
	background-color:#BEBEBE;
}
</style>
<div id="chpassbank" style="display:none; position:absolute; top:50px; left:250px;"></div>
<?php
	
	$rz0 = '';
	$rz1 = '';
	$rz2 = '';
	$rz3 = '';
	$rz4 = '';
	$rz5 = '';
	$expbase = number_format($u->stats['levels']['exp'], 0, ",", " ");
	if( $expbase-1 == $u->info['exp'] && $c['nolevel'] == true ) {
		//��������� ���� �����
		$tlus = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `real` = 1 AND `level` = "'.$u->info['level'].'" LIMIT 1'));
		if($tlus[0] < $u->info['level']*10) {
			$expbase = '<strike>'.$expbase.'</strike>';
		}
		$expbase = '(<a href="http://xcombats.com/exp.php" target="_blank">'.$expbase.'</a>)';
		if(((1+$u->info['level']*10)-$tlus[0]) > 0) {
			$expbase .= ' <u><a onMouseOver="top.hi(this,\'<b>��������� '.$u->info['level'].' ������ �� �������� ����.</b><br>��� ������������� �� ������� <u>'.((1+$u->info['level']*10)-$tlus[0]).' ����������</u> ������� ������.\',event,0,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();" style="cursor:help">[?]</a></u>';
		}
	}else{
		$expbase = '(<a href="http://xcombats.com/exp.php" target="_blank">'.$expbase.'</a>)';
	}
	if($u->info['exp'] == 12499 && $c['infinity5level'] == true) {
		$trks5 = '<div align="center"><hr><font color=red>��� ��������� ���������� ������ ��� ��������� <a target="_blank" href="http://xcombats.com/item/1204">�������� ��������</a></font><hr></div>';
	}
	if( $u->info['exp_eco'] > 0 ) {
		if( $u->info['exp_eco'] > 17500 ) {
			$u->info['exp_eco'] = 17500;
		}
		$trks5 .= '<font color="navy">������������ ����: <b>'.number_format($u->info['exp_eco'], 0, ",", " ").'</b></font><br>';
	}
	echo '
	<table width="100%" border="0" cellspacing="3" cellpadding="0">
	<tr><td>&nbsp;</td></tr>
	<tr>
    <td height="15">
	<small>
	<div style="padding:5px 5px 0px 5px;">
	����:&nbsp;<span style="float0:right"><b>'.number_format($u->info['exp'], 0, ",", " ").'</b> '.$expbase.'</span><br>'.$trks5.'
	���:&nbsp;<span style="float0:right"><span title="�����: '.number_format($u->info['win'], 0, ",", " ").'"><b>'.number_format($u->info['win'], 0, ",", " ").' <img width="7" height="7" title="�����: '.number_format($u->info['win'], 0, ",", " ").'"
	src="http://img.xcombats.com/i/ico/wins.gif" style="display:inline-block;" /></b></span> &nbsp; <span title="���������: '.number_format($u->info['lose'], 0, ",", " ").'"><b>'.number_format($u->info['lose'], 0, ",", " ").' <img width="7" height="7" alt="���������: '.number_format($u->info['lose'], 0, ",", " ").'"
	src="http://img.xcombats.com/i/ico/looses.gif" style="display:inline-block;" /></b></span> &nbsp; <span title="������: '.number_format($u->info['nich'], 0, ",", " ").'"><b>'.number_format($u->info['nich'], 0, ",", " ").' <img width="7" height="7" alt="������: '.number_format($u->info['nich'], 0, ",", " ").'"
	src="http://img.xcombats.com/i/ico/draw.gif" style="display:inline-block;" /></b></span></span><br />
	<!--����� �����:&nbsp;'.(0+$u->info['swin']).'<br>
	����� ���������:&nbsp;'.(0+$u->info['slose']).'<br>-->
	</div><div style="padding:0px 5px 5px 5px;">
	������:&nbsp;<img src="/coin1.png" height="11">&nbsp;<b>'.$u->info['money'].'</b> ��.<br />
	<span style="padding-left:38px;">&nbsp;</span> &nbsp;<img src="/coin2.png" height="11">&nbsp;<b><font color=green>'.$u->bank['money2'].'</b> ���.</font><br />
	��������������:&nbsp;<img src="/voins.png" height="11">&nbsp;<b>'.$u->info['money3'].'</b>&nbsp;��.<br />
	';
	if($u->info['level'] < 8 && $c['zuby'] == true) {
		echo '����:&nbsp;'.$u->zuby($u->info['money4']).'<br>';
	}
	
	/*
	if($u->info['level']>0 && $u->info['inTurnir'] == 0 && !isset($u->info['noreal']))
	{
		echo'
		<br>����:&nbsp;
		';
		if(!isset($u->bank['id']))
		{ 
			if($rb[0]>0)
			{
				echo '<a href="javascript:bank_info();">������� ����</a>';
			}else{
				echo '<a href="javascript:bank_info();">����������</a>';
			}
		}else{
			echo '���� � <b>'.$u->bank['id'].'</b><br> <img src="/coin1.png" height="11">&nbsp;<b>'.$u->bank['money1'].'</b> ��. <img src="/coin2.png" height="11">&nbsp;<b>'.$u->bank['money2'].'</b> ���. <img style="display:inline-block;cursor:pointer;" src="http://img.xcombats.com/i/close_bank.gif" onClick="top.frames[\'main\'].location=\'main.php?inv=1&otdel='.$_GET['otdel'].'&bank_exit='.$code.'\';" title="��������� ������ �� ������" style="cursor:pointer">';
		}
		echo '<br>';
	}
	*/
	

	if( $u->info['inTurnir'] == 0 ) {
		/*if( $u->stats['silver'] > 0 ) {
			echo '<div style="padding-top:5px;padding-bottom:5px;color:#ad4421;">';
			echo '<a style="color:#ad4421;" href="/main.php?vip='.$u->stats['silver'].'">�������������� �������: <img title="�������������� ������� '.$u->stats['silver'].' ������" src="http://img.xcombats.com/blago/'.$u->stats['silver'].'.png" width="15" height="15" style="vertical-align:sub;display:inline-block;"></a>';
			echo '</div>';
		}else{
			echo '<div style="padding-top:5px;padding-bottom:5px;color:#ad4421;">';
			echo '<a style="color:#ad4421;" href="/benediction/" target="_blank">�������������� �������: <img title="������ �������������� ������� ����� ����� �����" src="http://img.xcombats.com/blago/1d.png" width="15" height="15" style="vertical-align:sub;display:inline-block;"></a>';
			echo '</div>';
		}*/
	}

	if( $u->info['level'] > 3 && $u->info['inTurnir'] == 0 && $c['bonusonline'] == true && !isset($u->info['noreal']) ) {
		$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.time().' LIMIT 1'));
		if(isset($_GET['takebns']) && $u->newAct($_GET['takebns'])==true && !isset($bns['id'])) {
			$u->takeBonus();
			$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.time().' AND `type` = 0 LIMIT 1'));
		}
		if(isset($bns['id'])) {
			echo '<button style="width:224px;margin-top:5px;" onclick="alert(\'�� ������� ����� ����� ����� '.$u->timeOut($bns['time']-time()).'\');" class="btnnew"> ����� '.$u->timeOut($bns['time']-time()).'  </button>';
		}else{
			//echo '�������� �����:<br><div align="left"><button class="btnnew" onclick="location.href=\'main.php?inv=1&takebns='.$u->info['nextAct'].'\';">25 ��.!</button>';
			//echo '<button class="btnnew" onclick="location.href=\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=2\';">'.($u->info['level']*3).' ����.!</button><button class="btnnew" onclick="location.href=\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\';">1 ���.</button></div>';
			//if( $u->info['level'] == 7 ) {
			//	echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'����� �� ������\',\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\')">�������� '.$u->zuby(round($u->info['level']*0.75,2),1).'</button></div>';
			//}elseif( $u->info['level'] >= 8 ) {
				if( $u->info['align'] == 0 || $u->info['align'] == 2 ) {
					$ngtxt = '�������� '.round($c['bonline'][0][$u->info['level']]*$c['bonusonline_kof'],2).' ��.';
				}elseif( $u->info['align'] > 0 ) {
					$ngtxt = '�������� '.round($c['bonline'][1][$u->info['level']]*$c['bonusonline_kof'],2).' ���.';
				}				
				echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'����� �� ������\',\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\')">'.$ngtxt.'</button>';
			//}else{
			//	$expad = round(50+($u->stats['levels']['exp']-$u->info['exp'])/2);
			//	if($expad > 100) {
			//		$expad = 100;
			//	}
			//	echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'����� �� ������\',\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\')">�������� '.$expad.' �����</button></div>';
			//}
		}
		echo '<button onclick="location.href=\'pay.php\';" style="width:224px;margin-top:5px;" class="btnnew"><img src="/coin2.png" height="12"><b><font color=green> ������� ���. ������</font></b></button>';
		echo '</div>';
	}
	
	//��������� ������ �������
	if( $u->info['level'] >= 0 && $u->info['inTurnir'] == 0 && $c['bonuslevel'] == true && !isset($u->info['noreal']) && $c['bonussocial'] == true ) {
		/*
[1] ����� ���������� �� ����� �� 1 ������.
[2]-[3]-[4] ����� ����������� e-mail ����� �������� �� 2 �� 4 ������� �� �����.
[5]-[6] ����� ����������� �������� ��������� ����� �������� 5 ��� 6 ������� �� �����.
[7] ����� �������� 1 �����.
[8] ����� �������� 3 ������ � �������� 3 �����.
[9] ����� �������� 5 ������ � �������� 50 ������.
[10] ����� �������� 7 ������ � �������� 100 ������.
		*/
		$mxlvl = mysql_fetch_array(mysql_query('SELECT `id`,`level` FROM `users` WHERE `real` = 1 AND `admin` = 0 AND `banned` = 0 ORDER BY `level` DESC LIMIT 1'));
		if(isset($mxlvl['id']) && $mxlvl['level'] > $u->info['level']+1) {
			$gd = 1;
			$gb = 1;
			$sl = $u->info['level'];
			$ml = $u->info['level']+1;
			//
			if(isset($_GET['takelevelplease'])) {
				$er8 = '';
				//
				if($ml <= 1) {
					$er8 = '��������� �� ����� �� 1 ������.';
				}elseif($ml <= 4) {
					$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mailconfirm" AND `ok` > 0 LIMIT 1'));
					if(!isset($mcf['id'])) {
						$er8 = '�� �� ����������� E-mail.';
					}else{
						if($ml == 2) {
							mysql_query('UPDATE `stats` SET `exp` = 420 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}elseif($ml == 3) {
							mysql_query('UPDATE `stats` SET `exp` = 1300 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}elseif($ml == 4) {
							mysql_query('UPDATE `stats` SET `exp` = 2500 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
						$er8 = '�� ������� �������� '.$ml.' �������!';
						$u->info['level']++;
						$ml++;
					}
				}elseif($ml <= 6) {
					$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND (`val` = "vkauth" OR `val` = "fbauth" OR `val` = "okauth") LIMIT 1'));
					if(!isset($mcf['id'])) {
						$er8 = '�� �� ����������� �������� � ���������� ����.';
					}else{
						$itmsv = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 1204 AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
						if($ml == 5) {
							mysql_query('UPDATE `stats` SET `exp` = 5000 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}elseif($ml == 6) {
							mysql_query('UPDATE `stats` SET `exp` = 12500 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
						if($ml == 6 && !isset($itmsv['id']) && $c['infinity5level'] == true) {
							$er8 = '��������� �������� ��������!';
						}else{
							if($ml == 6) {
								mysql_query('INSERT INTO `mini_actions` (
									`uid`,`time`,`val`,`var`
								) VALUES (
									"'.$u->info['id'].'","'.time().'","mbtnlvl6","0"
								)');
							}
							$er8 = '�� ������� �������� '.$ml.' �������!';
							$u->info['level']++;
							$ml++;
						}
					}
				}elseif($ml <= 7) {
					$tstlvl = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mbtnlvl6" LIMIT 1'));
					//$refs = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `level` >= 5 AND `timereg` > "'.(0+$tstlvl['time']).'" AND `real` = 1 AND `host_reg` = "'.$u->info['id'].'" LIMIT 1'));
					//$btls = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle` WHERE `time_start` > "'.(0+$tstlvl['time']).'" AND `id` IN (SELECT `battle_id` FROM `battle_last` WHERE `uid` = "'.$u->info['id'].'" AND `time` > "'.(0+$tstlvl['time']).'") LIMIT 1'));
					//
					$btls[0] = $u->info['win'];
					if( $btls[0] >= 50 ) {
						//
						mysql_query('UPDATE `stats` SET `exp` = 30000 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						//
						mysql_query('INSERT INTO `mini_actions` (
							`uid`,`time`,`val`,`var`
						) VALUES (
							"'.$u->info['id'].'","'.time().'","mbtnlvl7","0"
						)');
						$er8 = '�� ������� �������� '.$ml.' �������!';
						$u->info['level']++;
						$ml++;
					}else{
						$er8 = '�� �� ������� ��������� ���� (�������� '.(50-$btls[0]).' �����).';
					}
				}elseif($ml <= 8) {
					$tstlvl = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mbtnlvl7" LIMIT 1'));
					$refs = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `level` >= 5 AND `timereg` > "'.(0+$tstlvl['time']).'" AND `real` = 1 AND `host_reg` = "'.$u->info['id'].'" LIMIT 1'));
					$btls = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle` WHERE `time_start` > "'.(0+$tstlvl['time']).'" AND `razdel` = 5 AND `id` IN (SELECT `battle_id` FROM `battle_last` WHERE `uid` = "'.$u->info['id'].'" AND `time` > "'.(0+$tstlvl['time']).'") LIMIT 1'));
					//
					if($refs[0] < 3 || $btls[0] < 3) {
						$er8 = '�� �� ���������� '.(0+$refs[0]).'/3 ������ ��� �� ������� '.(0+$btls[0]).'/3 �����.';
					}else{
						//
						mysql_query('UPDATE `stats` SET `exp` = 300000 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						//
						mysql_query('INSERT INTO `mini_actions` (
							`uid`,`time`,`val`,`var`
						) VALUES (
							"'.$u->info['id'].'","'.time().'","mbtnlvl8","0"
						)');
						$er8 = '�� ������� �������� '.$ml.' �������!';
						$u->info['level']++;
						$ml++;
					}
				}elseif($ml <= 9) {
					$er8 = '�� �� ���������� 5 ������ ��� �� ������� 50 ������.';
					$er8 = '������ ������!';
				}elseif($ml <= 10) {
					$er8 = '�� �� ���������� 7 ������ ��� �� ������� 100 ������.';
					$er8 = '������ ������!';
				}else{
					$er8 = '���-�� ��� �� ���...';
					$er8 = '������ ������!';
				}
				//
				if($sl != $u->info['level']) {
					
					die('<script>location.href="/main.php?inv";</script>');
				}
				if(isset($mxlvl['id']) && ($mxlvl['level'] > $ml+1 || $u->info['admin'] > 0)) {
					//
				}else{
					if($sl != $u->info['level']) {
						$gd = 0;
					}
				}
			}
			//
			if($ml <= 1) {
				$zd = '<font color=red>����� ���������� �� ����� �� 1 ������.</font>';
				$gb = 1;
			}elseif($ml <= 4) {
				$zd = '����� ����������� E-mail, ����� �������� 2-4 �������.';
				$gb = 1;
			}elseif($ml <= 6) {
				$zd = '����� ����������� �������� � ���������� ����, ����� �������� 5-6 �������.';
				$gb = 1;
			}elseif($ml <= 7) {
				$zd = '����� <!--�������� ������ ����� �� ����������� ������ � -->�������� 50 ����, ����� �������� 7 �������.';
				$gb = 1;
			}elseif($ml <= 8) {
				$zd = '����� �������� 3 ������ �� ����������� ������ � �������� 3 ��������� ��������, ����� �������� 8 �������.';
				$gb = 1;
			}elseif($ml <= 9) {
				$zd = '����� �������� 5 ������ �� ����������� ������ � �������� 50 ��������� ���������, ����� �������� 9 �������.';
				$gb = 1;
			}elseif($ml <= 10) {
				$zd = '����� �������� 7 ������ �� ����������� ������ � �������� 100 ��������� ���������, ����� �������� 10 �������.';
				$gb = 1;
			}else{
				$zd = '';
				$gb = 0;
			}
			//
			if($gd == 1) {
				//
				if($zd!='') {
					echo '<hr><b><center>���������� ��������� �������:</center></b><div align="center" style="border:1px solid #aeaeae;background-color:#FFF;margin-top:3px;padding:4px;">'.$zd.'</div>';
				}else{
					echo '<hr>';
				}
				if($er8 != '') {
					echo '<div style="padding:3px;" align="center"><font color=red>'.$er8.'</font></div>';
				}
				//
				if($gb == 1 && $ml > 1) {
					//��������
					echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="location.href=\'/main.php?inv&takelevelplease=1\';">�������� '.($u->info['level']+1).' �������</button></div>';
				}
				//
			}
		}
	}
	
	//����� �� ����, �������
	if(!isset($u->info['noreal']) && $c['bonussocial'] == true) {
		$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mailconfirm" LIMIT 1'));
		if((isset($mcf['id']) && $mcf['ok'] == 0) || !isset($mcf['id'])) {
			if(isset($_GET['confmail'])) {
				//
				$gd = 0;
				$zdml = 3600; //���
				if(isset($mcf['id']) && $mcf['time'] > time() - $zdml ) {
					echo '<hr><center><font color="red">������ ������������ ��.����� ��� �����. �������� '.$u->timeOut($mcf['time']+$zdml-time()).'</font></center>';
					$gd = 1;
				}elseif(!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $_GET['confmail'])) {
					echo '<hr><center><font color="red">�� ������� ���� ��������� E-mail.</font></center>';
					$gd = 1;
				}
				
				if( $gd == 0 ) {
					if(isset($mcf['id'])) {
						mysql_query('UPDATE `mini_actions` SET `time` = "'.time().'",`var` = "'.mysql_real_escape_string($_GET['confmail']).'" WHERE `id` = "'.$mcf['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `mini_actions` (`uid`,`time`,`val`,`var`,`ok`) VALUES (
							"'.$u->info['id'].'","'.time().'","mailconfirm","'.mysql_real_escape_string($_GET['confmail']).'","0"
						)');
					}
					function sendmail($message,$keymd5,$mail) {
						global $u;
						//
						$md5mail = md5($keymd5.'+'.$mail);
						//
						$msgtxt = '��� ������������� ������ E-mail � ��������� <b>'.$u->info['login'].'</b> ��������� �� ������:';
						$msgtxt .= ' <a href="http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.'">������� ���</a> (http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.')';
						$msgtxt .= '<br>�������� �� ������, �� ������������� ���� ������� �������� ������� �������.';
						$msgtxt .= '<br>���� �� �� ������ ��������� � ����� ���� � �� ������ �������� ������, ����� ��������� �� ���� ������: <a href="http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.'&cancel">������� ���</a> (http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.'&cancel)<br><br>- - - - - - -<br><br>� ���������,<br>������������� ������������ ����������� �����';
						//
						$headers  = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=windows-1251\r\n";
						$headers .= "From: ������ �� <zahodite@xcombats.com> \r\n";		
						$to = $mail;
						//
						$subject = '���: '.$u->info['login'] . ' - ������������� ����� ��.�����';
						//
						if (mail($to, $subject, $msgtxt, $headers) == true) {
							return true;
						}else{
							return false;
						}
					}
					//
					$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mailconfirm" LIMIT 1'));
					//
					sendmail( '' , 'mailconf*15' , $mcf['var'] );
					//
					echo '<hr><center><font color="red">�� ��� E-mail ���������� ������.</font></center>';
					//
				}
			}
			$mcff = '����������� E-mail �� 1 ���.';
			if(isset($mcf['id'])) {
				$mcff = '<b>'.$mcf['var'].'</b><br><font color="grey"><small>(�� ���� ����� ���������� ������)</small></font>';
			}
			echo '<div align="center"><button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.mailConf();"><img src="http://img.xcombats.com/mini_mail.png" height="13" width="13"> '.$mcff.'</button></div>';
		}else{
			//echo '<div align="center"><button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.mailConf();"><img src="http://img.xcombats.com/mini_mail.png" height="13" width="13"> ����������� E-mail �� 1 ���.</button></div>';
		}
		
		$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND (`val` = "vkauth" OR `val` = "fbauth" OR `val` = "okauth") LIMIT 1'));
		if(!isset($mcf['id'])) {
			require_once('vk/VK.php');
			require_once('vk/VKException.php');
			$vk_config = array(
				'app_id'        => '5145826',
				'api_secret'    => 'V90yIzlgSglfgrnHw7Ny',
				'callback_url'  => 'http://xcombats.com/social.php?vkconnect',
				'api_settings'  => 'offline,friends,email'
			);
				
			if(isset($_GET['vkconnect'])) {
				echo '<hr>';				
				try {
					$vk = new VK\VK($vk_config['app_id'], $vk_config['api_secret']);
					
					if (!isset($_REQUEST['code'])) {
						/**
						 * If you need switch the application in test mode,
						 * add another parameter "true". Default value "false".
						 * Ex. $vk->getAuthorizeURL($api_settings, $callback_url, true);
						 */
						$authorize_url = $vk->getAuthorizeURL(
						$vk_config['api_settings'], $vk_config['callback_url']);
							
						/*echo '<script>location.href="'.$authorize_url.'";</script>';
							*/
						//echo '<a target="_blank" href="' . $authorize_url . '">���������� ���� ������� VK.com</a>';
					} else {
						$access_token = $vk->getAccessToken($_REQUEST['code'], $vk_config['callback_url']);
						
						echo 'access token: ' . $access_token['access_token']
							. '<br />expires: ' . $access_token['expires_in'] . ' sec.'
							. '<br />user id: ' . $access_token['user_id'] . '<br /><br />';
							
					}
				} catch (VK\VKException $error) {
					echo $error->getMessage();
				}
				
				echo '<hr>';		
			}else{
				$vk = new VK\VK($vk_config['app_id'], $vk_config['api_secret']);
				$authorize_url = $vk->getAuthorizeURL(
				$vk_config['api_settings'], $vk_config['callback_url']);
			}
			
			echo '<div align="center"><hr>';
			
			echo '����������� ���� �� ��������� � ���������� ����� �� 1 ���. � 150 ��.<br><br>';
			
			echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="window.open(\''.$authorize_url.'\', \'opener\', \'width=660,height=450\');"><img src="http://img.xcombats.com/vk.png" height="13" width="13"> ����������� ��������� &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</button>';
			
			echo '<br>���<br>';
			
			echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="window.open(\'http://xcombats.com/social.php?fbconnect\', \'opener\', \'width=660,height=450\');"><img src="http://img.xcombats.com/fb.png" height="13" width="13"> ����������� Facebook &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</button>';
			
			//echo '<br>���<br>';
			
			//echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="window.open(\''.$authorize_url.'\', \'opener\', \'width=660,height=450\');"><img src="http://img.xcombats.com/ok.png" height="13" width="13"> ����������� �������������</button>';
			
			echo '</div>';
		}
	}
	echo '</div>';
	$prt = explode('|',$u->info['prmenu']);
	if(isset($_GET['up']))
	{
		$i = 0;
		if(isset($prt[$_GET['up']],$prt[$_GET['up']-1]))
		{
			$prt1 = $prt[intval($_GET['up'])];
			$prt[$_GET['up']] = $prt[$_GET['up']-1];
			$prt[$_GET['up']-1] = $prt1;
			$prtNew = implode('|',$prt);
			$u->info['prmenu'] = $prtNew;
			mysql_query('UPDATE `stats` SET `prmenu`="'.mysql_real_escape_string($u->info['prmenu']).'" WHERE `id`="'.$u->info['id'].'" LIMIT 1');
			$prt = explode('|',$u->info['prmenu']);
		}
	}elseif(isset($_GET['down']))
	{
		$i = 0;
		if(isset($prt[$_GET['down']],$prt[$_GET['down']+1]))
		{
			$prt1 = $prt[intval($_GET['down'])];
			$prt[$_GET['down']] = $prt[$_GET['down']+1];
			$prt[$_GET['down']+1] = $prt1;
			$prtNew = implode('|',$prt);
			$u->info['prmenu'] = $prtNew;
			mysql_query('UPDATE `stats` SET `prmenu`="'.mysql_real_escape_string($u->info['prmenu']).'" WHERE `id`="'.$u->info['id'].'" LIMIT 1');
			$prt = explode('|',$u->info['prmenu']);
		}
	}
	
	$i = 0;
	while($i<count($prt))
	{
		$prtpos[$prt[$i]] = $i;
		$i++;
	}
	
	$rz0 = '<script>getLine('.$prtpos[0].',"�������������� ","0","0","'.$uShow[0].'",1);</script>';
	$rz0 .= '<font id="rz0">';
	if($uShow[0]==1){
		$i = 1;
		while( $i <= 10 ) {
			$u->stats['s'.$i] = 0+$u->stats['s'.$i];
			$i++;
		}
		$rz0 .= '
		����: <b>'.$u->stats['s1'].'</b><br />
		��������:&nbsp;<b>'.$u->stats['s2'].'</b><br />
		��������:&nbsp;<b>'.$u->stats['s3'].'</b><br />
		������������:&nbsp;<b>'.$u->stats['s4'].'</b><br />
		';
		if($u->info['level'] >= 4 || $u->stats['n5']!=0)
		{
			$rz0 .= '
			���������:&nbsp;<b>'.$u->stats['s5'].'</b><br />
			';
		}
		if($u->info['level'] >= 7 || (@isset($u->stats['n6']) && @$u->stats['n6']>0))
		{
			$rz0 .= '
			��������:&nbsp;<b>'.@$u->stats['s6'].'</b><br />
			';
		}
		if($u->info['level'] >= 10 || @$u->stats['s7']>0)
		{
			$rz0 .= '
			����������:&nbsp;<b>'.@$u->stats['s7'].'</b><br />
			';
		}
		if($u->info['level'] >= 14 || @$u->stats['s8']>0)
		{
			$rz0 .= '
			����:&nbsp;<b>'.@$u->stats['s8'].'</b><br />
			';
		}
		if($u->info['level'] >= 15 || @$u->stats['s9']>0)
		{
			$rz0 .= '
			������� ����:&nbsp;<b>'.@$u->stats['s9'].'</b><br />
			';
		}
		if($u->info['level'] >= 20 || @$u->stats['s10']>0)
		{
			$rz0 .= '
			������������:&nbsp;<b>'.@$u->stats['s10'].'</b><br />
			';
		}
			//$rz0 .= '
			//�������:&nbsp;<b>'.(0+$u->stats['s11']).'</b> &nbsp; <small>['.round($u->info['enNow'],3).'/'.$u->stats['enAll'].']</small><br />
			//';
		if($u->info['ability'] > 0)
		{ 
			$rz0 .= '&nbsp;<a href="main.php?skills=1&side=1">+ �����������</a>'; 
			if($u->info['skills'] != 0)
			{
				$rz0 .= '<br>';
			} 
		}

		if($u->info['skills'] > 0 && $u->info['level'] > 0)
		{ 
			$rz0 .= '&nbsp;&bull; <a href="main.php?skills=1&side=1">��������</a><br />'; 
		}
	}
	$rz0 .= '</font>';
	$rz1 = '<script>getLine('.$prtpos[1].',"������������ ","0","0",'.$uShow[1].',2);</script>';
	if($uShow[1]==1)
	{
		//if( $u->info['admin'] > 0 ) {
			$rz1 .= '����: '.$u->inform('yrontest').'';
			$rz1 .= '<br><font color=maroon>����. ����: '.$u->inform('yrontest-krit').'</font>';
		//}else{
		//	$rz1 .= '����: '.$u->inform('yron');
		//}
			$rz1 .= '
			<br>
			<span>��. ����. �����: '.$u->inform('m1').'';
			//if($u->inform('m3')!='0')
			//{
				$rz1 .='
				</span><br>
				<nobr>
				<span>��. �������� ����. �����: '.$u->inform('m3').'';
			//}
			if( $u->inform('antm3') != '0' && $u->info['admin'] > 0 ) {
				$rz1 .='
				</span><br>
				<nobr>
				<span>��. ������ �������� �����: '.$u->inform('antm3').'';
			}
			$rz1 .= '
			</span></nobr><br />
			<span>��. ������ ����. �����: '.$u->inform('m2').'';
			$rz1 .= '</span><br />
			<span>��. �����������: '.$u->inform('m4').'';
			$rz1 .= '</span><br>
			<nobr><span>��. ������ �����������: '.$u->inform('m5').'';
			$rz1 .= '</span></nobr><br>
			<span>��. ������ �����: '.$u->inform('m9').'';
			$rz1 .= '</span><br>
			<span>��. ����������: '.$u->inform('m6').'';
			$rz1 .='
			</span><br>
			<span>��. �����������: '.$u->inform('m7').'';
			$rz1 .= '</span><br />
			<span>��. ����� �����: '.$u->inform('m8').'';
			$rz1 .= '</span>';
		$rz1 .= '</nobr>';
	}
	$rz2 ='<script>getLine('.$prtpos[2].',"����� ","0","0",'.$uShow[2].',3);</script>';
	if($uShow[2]==1)
	{		
		$rz2 .= '
		����� ������: '.$u->stats['mib1'].'-'.$u->stats['mab1'].' ('.($u->stats['mib1']).'+d'.($u->stats['mab1']-($u->stats['mib1'])+1).')<br />
		����� �����: '.$u->stats['mib2'].'-'.$u->stats['mab2'].' ('.($u->stats['mib2']).'+d'.($u->stats['mab2']-($u->stats['mib2'])+1).')<br />
		����� ������: '.$u->stats['mib2'].'-'.$u->stats['mab2'].' ('.($u->stats['mib2']).'+d'.($u->stats['mab2']-($u->stats['mib2'])+1).')<br />
		����� �����: '.$u->stats['mib3'].'-'.$u->stats['mab3'].' ('.($u->stats['mib3']).'+d'.($u->stats['mab3']-($u->stats['mib3'])+1).')<br />
		����� ���: '.$u->stats['mib4'].'-'.$u->stats['mab4'].' ('.($u->stats['mib4']).'+d'.($u->stats['mab4']-($u->stats['mib4'])+1).')<br />';
	}
	$rz3 = '<script>getLine('.$prtpos[3].',"�������� ","0","0",'.$uShow[3].',4);</script>';
	if($uShow[3]==1)
	{
		$i = 1;
		while($i<=4)
		{
			$rz3 .= ucfirst(str_replace('��. ��������','�������� ',$u->is['pa'.$i].': '));
			if($u->stats['pa'.$i]>0)
			{
				//$rz3 .= '+';
			}
			//$rz3 .= $u->stats['pa'.$i].'<br />';
			$rz3 .= $u->inform('pa'.$i).'<br>';
			$i++;
		}
		$i = 1;
		while($i<=7)
		{
			$rz3 .= ucfirst(str_replace('��. �������� ','�������� ',$u->is['pm'.$i].': '));
			if($u->stats['pm'.$i]>0)
			{
				//$rz3 .= '+';
			}
			//$rz3 .= $u->stats['pm'.$i].'<br />';
			$rz3 .= $u->inform('pm'.$i).'<br>';
			$i++;
		}
	}
	
	$zi = array( //�������� �������� �� ����
		'n' => array(
			'','������','�����','�����','����','����'
		),
		1 => array( 1 , 8 , 9 , 52 ), //������
		2 => array( 4 , 5 , 6 ), //�����
		3 => array( 2 , 4 , 5 , 6 , 13 ), //�����
		4 => array( 7 , 16 , 10 , 11 , 12 ), //����
		5 => array( 17 )  //����
	);
		
	$rz4 = '<script>getLine('.$prtpos[4].',"������: ","0","0",'.$uShow[4].',5);</script>';
	if($uShow[4]==1)
	{
		function zago($v) {
			if($v > 1000) {
				$v = 1000;
			}
			$r = (1-( pow(0.5, ($v/250) ) ))*100;
			return $r;
		}
		//if($u->info['admin'] > 0 ) {
			//echo '<hr>';
			//print_r($u->stats['sv_']);
		//}
		$i = 1;
		while($i<=4)
		{
			$rz4 .= '<span onclick="za_block('.$i.')" class="dot">'.ucfirst(/*str_replace('������ �� ','',*/$u->is['za'.$i]/*)*/).': ';
			if($u->stats['za'.$i]>0)
			{
				//$rz4 .= '+';
			}
			$rz4 .= ''.$u->stats['za'.$i].' ('.round(zago($u->stats['za'.$i])).'%)</span>';
			$rz4 .= '<span style="display:none" id="za_block_'.$i.'">';
			$j = 1;
			while( $j <= 5 ) {
				$k = 0;
				$rk = $u->stats['za'.$i];
				while( $k < count($zi[$j]) ) {
					if( $u->stats['sv_']['z'][$zi[$j][$k]] > 0 ) {
						$rk += $u->stats['sv_']['z'][$zi[$j][$k]]['za']+$u->stats['sv_']['z'][$zi[$j][$k]]['za'.$i];
					}
					$k++;
				}
				$rz4 .= ' &nbsp; &nbsp; <span style="min-width:55px;display:inline-block;">&bull; '.$zi['n'][$j].':</span> '.$rk.' ('.round(zago($rk)).'%)<br>';
				$j++;
			}
			$rz4 .= '</span>';
			$i++;
		}
		$i = 1;
		while($i<=7)
		{
			/*$rz4 .= ucfirst(str_replace('������ �� ','',$u->is['zm'.$i])).': ';
			if($u->stats['zm'.$i]>0)
			{
				//$rz4 .= '+';
			}
			$rz4 .= $u->stats['zm'.$i].' ('.round(zago($u->stats['zm'.$i])).'%)<br />';
			$i++;*/
			$rz4 .= '<span onclick="za_block('.($i+4).')" class="dot">'.ucfirst(/*str_replace('������ �� ','',*/$u->is['zm'.$i]/*)*/).': ';
			if($u->stats['zm'.$i]>0)
			{
				//$rz4 .= '+';
			}
			$rz4 .= ''.$u->stats['zm'.$i].' ('.round(zago($u->stats['zm'.$i])).'%)</span>';
			$rz4 .= '<span style="display:none" id="za_block_'.($i+4).'">';
			$j = 1;
			while( $j <= 5 ) {
				$k = 0;
				$rk = $u->stats['zm'.$i];
				while( $k < count($zi[$j]) ) {
					if( $u->stats['sv_']['z'][$zi[$j][$k]] > 0 ) {
						$rk += $u->stats['sv_']['z'][$zi[$j][$k]]['zm']+$u->stats['sv_']['z'][$zi[$j][$k]]['zm'.$i];
					}
					$k++;
				}
				$rz4 .= ' &nbsp; &nbsp; <span style="min-width:55px;display:inline-block;">&bull; '.$zi['n'][$j].':</span> '.$rk.' ('.round(zago($rk)).'%)<br>';
				$j++;
			}
			$rz4 .= '</span>';
			$i++;
		}
	}
	$rz5 = '<script>getLine('.$prtpos[5].',"������ ","0","0",'.$uShow[5].',6);</script>';
	if($uShow[5]==1)
	{
		$rz5 .= '<center style="padding:5px;">';
		$rz5 .= '<input class="btnnew3" style="padding:3px 15px 3px 15px;" type="button" name="snatvso" value="����� ��" class="btn" onclick="top.frames[\'main\'].location=\'main.php?inv=1&remitem&otdel='.$_GET['otdel'].'\';"
		style="font-weight:bold;" />
		&nbsp;';
		$hgo = $u->testHome();
		if(!isset($hgo['id']))
		{
			$rz5 .=  '<input class="btnnew3" style="padding:3px 15px 3px 15px;" type="button" value="�������" class="btn" onclick="top.frames[\'main\'].location=\'main.php?homeworld&rnd='.$code.'\';" style="font-weight:bold;width: 90px" />';
		}
		unset($hgo);
		$rz5 .= '</center>';
		$rz5 .=  '';
	}
	
	$rz6 ='<script>getLine('.$prtpos[6].',"���������&nbsp;&nbsp;&nbsp;<a href=\"#\" onClick=\"save_compl();\">���������</a>&nbsp;","0","0",'.$uShow[6].',7);</script>';
	if($uShow[6]==1)
	{
		$rz6 .= '<div>';
		$sp = mysql_query('SELECT * FROM `save_com` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 10');
		while($pl = mysql_fetch_array($sp))
		{
			$rz6 .= '<a href="?inv=1&delc1='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&rnd='.$code.'"><img src="http://img.xcombats.com/i/close2.gif" title="������� ��������" width="9" height="9"></a> <small><a href="?inv=1&usec1='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&rnd='.$code.'">������ &quot;'.$pl['name'].'&quot;</a></small><br>';
		}
		$rz6 .= '</div>';
	}
	
	$rz7 ='<script>getLine('.$prtpos[7].',"������ &nbsp; &nbsp;<a href=\"/main.php?skills=1&rz=4&rnd='.$code.'\">���������</a>&nbsp;","0","0",'.$uShow[7].',8);</script>';
	if($uShow[7]==1)
	{
		$rz6 .= '<div>';
		$sp = mysql_query('SELECT * FROM `complects_priem` WHERE `uid` = "'.$u->info['id'].'" LIMIT 10');
		$rz7 .= '<small>';
		while($pl = mysql_fetch_array($sp)) {
			$rz7 .= '<a onclick="if(confirm(\'������� �����  ?\')){location=\'main.php?inv=1&otdel='.round((int)$_GET['otdel']).'&delcop='.$pl['id'].'\'}" href="javascript:void(0)"><img src="http://'.$c['img'].'/i/close2.gif" width="9" height="9"></a> <a href="main.php?inv=1&otdel='.round((int)$_GET['otdel']).'&usecopr='.$pl['id'].'">������������ &quot;'.$pl['name'].'&quot;</a><br>';
		}
		$rz7 .= '</small>';
		$rz6 .= '</div>';
	}
	
	$i = 0;
	while($i<count($prt))
	{
		if(isset(${'rz'.$prt[$i]}))
		{
			echo ${'rz'.$prt[$i]};
		}
		$i++;
	}
?>
</td>
</tr>
</table>