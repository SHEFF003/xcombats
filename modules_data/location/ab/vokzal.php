<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='ab/vokzal')
{
	$cs = ''; $cbuy = array(); $tmref = 0;
	$sp = mysql_query('SELECT * FROM `vokzal` WHERE `city` = "'.$u->info['city'].'" OR `tocity` = "'.$c['city'].'"');
	while($pl = mysql_fetch_array($sp))
	{
		$vz1 = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "������" AND `city` = "'.$pl['city'].'" LIMIT 1'));
		$vz2 = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "������" AND `city` = "'.$pl['tocity'].'" LIMIT 1'));	
		$crm = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "'.$pl['name'].'" LIMIT 1'));
		//period 0 - �������� � ����� (�������), 1 - ��������, 3 - �������� � ������ ����� (�������), 4 - �������� (�� tocity)
		if($pl['time_start_go']==0)
		{
			//��� ����� ������ ��������� ������
			mysql_query('UPDATE `vokzal` SET `time_start_go` = "'.(time()+$pl['timeStop']*60).'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			$pl['time_start_go'] = time()+$pl['timeStop']*60;
			$pl['time_finish_go'] = $pl['time_start_go']+$pl['time_go']*60;
		}
		$see = 1;
		$plc = $pl['tocity'];
		$col = 'e6e6e6" style="color:#B7B7B7;"';	
		$tmgo = '<small>(�������� � <b>'.date('H:i',$pl['time_finish_go']).'</b>)</small>';
		$bl = '--';
		$bb = '������� ���';
		if($pl['time_start_go']-600<time() && $pl['time_start_go']>time())
		{
			//����� ��������� ����� � ������
			if(isset($crm['id']))
			{
				$sr = mysql_query('SELECT `uid`,`id` FROM `items_users` WHERE `secret_id` = "'.$pl['time_start_go'].'_b'.$pl['id'].'" AND `delete` = "0" LIMIT 100');
				while($pr = mysql_fetch_array($sr))
				{
					$upd1 = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$pr['id'].'" LIMIT 1');
					if($upd1)
					{
						mysql_query('UPDATE `users` SET `room` = "'.$crm['id'].'" WHERE `online` > '.(time()-120).' AND `id` = "'.$pr['uid'].'" LIMIT 1');
					}
				}
			}
		}
		if((($pl['period']==0 && $u->info['city']==$pl['city']) || ($pl['period']==3 && $u->info['city']==$pl['tocity'])) && $pl['time_start_go']>time() && $pl['citygo']!=$u->info['city'])
		{
			$see = 1;
			$tmgo = date('d.m.Y � H:i',$pl['time_start_go']);
			$col = 'c9c9c9';
			$bl = $pl['bilets'];
			$bb = '<input type="button" onClick="location=\'main.php?buy='.$pl['id'].'&sd4='.$u->info['nextAct'].'\'" value="������ �����">';
			if($pl['bilets']<=0)
			{
				$bb = '������� ���';
			}
		}else{
			//���������� ������ � ������ �����
			if($pl['time_finish_go']<time())
			{
				//�������
				if($pl['period']==0)
				{
					//������� � �����, ����� ������� �����������, � �������
					mysql_query('UPDATE `vokzal` SET `period` = "1",`citygo` = "'.$pl['tocity'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 1;
				}elseif($pl['period']==1)
				{
					//��������� � ������ �����, ������ ��� �������
					if(isset($crm['id']))
					{
						mysql_query('UPDATE `users` SET `city` = "'.$pl['tocity'].'",`room` = "'.$vz2['id'].'" WHERE `room` = "'.$crm['id'].'" LIMIT '.$pl['bilets_default'].'');
					}
					mysql_query('UPDATE `vokzal` SET `bilets` = "'.$pl['bilets_default'].'",`citygo`="'.$pl['city'].'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'",`time_start_go` = "'.(time()+$pl['timeStop']*60).'",`period` = "3" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 3;
				}elseif($pl['period']==3)
				{
					//������� � �����, ����� ������� �����������, � �������
					mysql_query('UPDATE `vokzal` SET `period` = "4" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 4;
				}elseif($pl['period']==4)
				{
					//��������� � ������ �����, ������ ��� �������
					if(isset($crm['id']))
					{
						mysql_query('UPDATE `users` SET `city` = "'.$pl['city'].'",`room` = "'.$vz1['id'].'" WHERE `room` = "'.$crm['id'].'" LIMIT '.$pl['bilets_default'].'');
					}
					mysql_query('UPDATE `vokzal` SET `bilets` = "'.$pl['bilets_default'].'",`citygo`="'.$pl['tocity'].'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'",`time_start_go` = "'.(time()+$pl['timeStop']*60).'",`period` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 0;
				}else{
					echo '[?]';
				}
			}
		}
		
		if($see==1)
		{	
			if($pl['period']==0 || $pl['period']==1)
			{
				$plc = $pl['tocity'];
			}else{
				$plc = $pl['city'];
			}
			$cs .= '<tr>
			<td height="30" bgcolor="#'.$col.'" align="center">'.$tmgo.'</td>
			<td bgcolor="#'.$col.'" align="center">'.$u->city_name[$plc].'</td>
			<td bgcolor="#'.$col.'" align="center">'.$pl['time_go'].' ���.</td>
			<td bgcolor="#'.$col.'" align="center">'.$pl['price1'].' ��.</td>
			<td bgcolor="#'.$col.'" align="center"> ��� </td>
			<td bgcolor="#'.$col.'" align="center">'.$bl.'</td>
			<td bgcolor="#'.$col.'" align="center">'.$bb.'</td>
		    </tr>';
			if($pl['time_start_go']-time()<$tmref)
			{
				$tmref = $pl['time_start_go']-time();
			}
			if($bl!='--' && $bl>0 && $pl['citygo']!=$u->info['city'])
			{
				$cbuy[$pl['id']] = 1;
			}
		}
	}
	
	if(isset($_GET['buy']) && $u->newAct($_GET['sd4'])==true)
	{
		$buy = mysql_fetch_array(mysql_query('SELECT * FROM `vokzal` WHERE `time_start_go` > "'.time().'" AND `citygo` != "'.$u->info['city'].'" AND `id` = "'.mysql_real_escape_string($_GET['buy']).'" LIMIT 1'));
		if(isset($buy['id']) && isset($cbuy[$buy['id']]))
		{
			if($buy['bilets']<=0)
			{
				$error = '������� ������ ���, ��������� �����';
			}elseif($u->info['money']>=$buy['price1'])
			{
				$u->info['money'] -= $buy['price1'];
				$upd = mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				if($upd)
				{
					//�������� ����� ���������
					$error = '�� ��������� '.$buy['price1'].' ��. �� ����� � '.$u->city_name[$buy['tocity']].'<br>�������� � '.date('d.m.Y H:i',$buy['time_start_go']).' �� �������';
					$ib = '���� ����������� ������ � '.$u->city_name[$buy['tocity']].': '.date('d.m.Y � H:i',$buy['time_start_go']).'<br>����� �� ���: <b>'.$u->info['login'].'</b>';
					$ins = mysql_query('INSERT INTO `items_users` (`1price`,`maidin`,`data`,`uid`,`item_id`,`iznosMAX`,`lastUPD`,`secret_id`,`time_create`) VALUES ("'.$buy['price1'].'","'.$u->info['city'].'","info='.$ib.'|noodet=1","'.$u->info['id'].'","866","1","'.time().'","'.$buy['time_start_go'].'_b'.$buy['id'].'","'.time().'")');
					if($ins)
					{
						$error .= '<br>������� &quot;�����&quot; ��� ��������� � ��� � ���������, � ������ &quot;������&quot;.';
						mysql_query('UPDATE `vokzal` SET `bilets` = "'.($buy['bilets']-1).'" WHERE `id` = "'.$buy['id'].'" LIMIT 1');
					}else{
						$error = '�� ������� ���������� �����';
					}
				}else{
					$u->info['money'] += $buy['price1'];
					$error = '�� ������� ���������� �����';
				}
			}else{
				$error = '� ��� ������������ �����';
			}
		}else{
			$error = '�� ������� ���������� �����';
		}
	}
	
	$zd = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` >= '.time().' AND `vars` = "teleport" LIMIT 1',1);
	if(isset($_GET['teleport']) && !isset($zd['id']))
	{
		$tp = mysql_fetch_array(mysql_query('SELECT * FROM `teleport` WHERE `city` = "'.$u->info['city'].'" AND `cancel` = "0" AND `id` = "'.((int)$_GET['teleport']).'" LIMIT 1'));
		if(isset($tp['id']))
		{
			if($u->info['money']>=$tp['price1'])
			{
				$rm = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "������" AND `city` = "'.$tp['toCity'].'" LIMIT 1'));
				if(isset($rm['id']))
				{
					$u->info['money'] -= $tp['price1'];
					$u->info['city'] = $tp['toCity'];
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'",`city` = "'.$u->info['city'].'",`room` = "'.$rm['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if( $u->stats['silver'] >= 3 ) {
						$tp['time'] = round($tp['time']/2);
					}
					$u->addAction((time()+$tp['time']*60),'teleport',$tp['toCity']);
					die('<script>location="main.php";</script>');
				}else{
					$error = '������ �����������������, �������� ������ � ���� ������ ��� ��������...';
				}
			}else{
				$error = '� ��� ������������ ������';
			}
		}else{
			$error = '������ �����������������...';
		}
	}
	
	$cst = '';
	$sp = mysql_query('SELECT * FROM `teleport` WHERE `city` = "'.$u->info['city'].'" AND `cancel` = "0"');
	while($pl = mysql_fetch_array($sp))
	{
		$col = 'e6e6e6" style="color:#B7B7B7;"';
		if(!isset($zd['id']))
		{
			$col = 'c9c9c9';
		}
		$cst .= '<tr>
			<td bgcolor="#'.$col.'" align="center">'.$pl['toCity'].'</td>
			<td bgcolor="#'.$col.'" align="center">'.$u->timeOut($pl['time']*60).'</td>
			<td bgcolor="#'.$col.'" align="center">'.$pl['price1'].' ��.</td>';
			if(isset($zd['id']))
			{
				$cst .= '<td bgcolor="#'.$col.'" align="center">�������� ��� '.$u->timeOut($zd['time']-time()).'</td>';
			}else{
				$cst .= '<td bgcolor="#'.$col.'" align="center"><a href="?teleport='.$pl['id'].'">�������!</a></td>';
			}
		    $cst .= '</tr>';
	}
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}	
	</style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3"><? echo $u->room['name'].' "'.$u->city_name[$u->info['city']].'"'; ?></div>
	<?php
	echo '<b style="color:red">'.$error.'</b>';
	if($cst!='')
	{
	?>
    <center>
	<b>������������ � ������ ������<? if(isset($zd['id'])){ echo ' </b>(�������� ����� '.$u->timeOut($zd['time']-time()).')<b>'; } ?></b>
	</center>
	<? if(!isset($zd['id'])){ ?>
    <br />
	<table width="100%" border="0" cellspacing="1" cellpadding="0">
	  <tr>
	    <td width="25%" bgcolor="#81888e"><div align="center">����� ����������</div></td>
	    <td width="25%" bgcolor="#81888e"><div align="center">����� �������� ������������</div></td>
	    <td width="25%" bgcolor="#81888e"><div align="center">���� ������������</div></td>
	    <td width="25%" bgcolor="#81888e"><div align="center">�����������������</div></td>
	    </tr>
	  <? echo $cst; ?>
	  </table>
	<br />
    <? } } unset($zd); ?>
    <center><b><br />���������� �������� ����� �� �������</b></center>
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="16%" bgcolor="#81888e"><div align="center">����� �����������</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">����� ����������</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">����� � ����</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">���� ������</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">��������� ����</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">�������� �������</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">���������� �����</div></td>
      </tr>
      <? echo $cs; ?>
    </table>
    <? if($tmref>600 && $tmref>0){ echo '<script>setTimer(\'location = location;\','.(1000*$tmref-600).');</script>'; } if($cs==''){ echo '<center><br>������� ��� ��������� ����� ��� ����������� � ������ ������</center>'; } ?>
    <br /><br />
    <small style="color:#999999;">
    - ��� ����������� � ������ ����� �� ������ ���� ������ ����� ����� ������������ ������<br />
    - ���� �� �������� �� ������, ����� ����� ����� ����� � ������� �� �������� ��� ���������<br />
    </small>
	<td width="280" valign="top">
    <TABLE cellspacing="0" cellpadding="0"><TD width="100%">&nbsp;</TD><TD>
	<table  border="0" cellpadding="0" cellspacing="0">
	<tr align="right" valign="top">
	<td>
	<!-- -->
	<? echo $goLis; ?>
	<!-- -->
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td nowrap="nowrap">
	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
	<tr>
	<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=3.180.0.267&rnd=<? echo $code; ?>';" title="<? thisInfRm('3.180.0.267',1); ?>">����������� �������</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div>
	  <br />
      <div align="right">
      <small>
	  �����: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	  � ��� � �������: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> ��.</b> &nbsp;
      </small>
      </div>
	  <br />
    <br />
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>