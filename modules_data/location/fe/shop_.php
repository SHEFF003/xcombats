<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='fe/shop_')
{
	$shopProcent = 0;

	if(isset($_POST['itemgift']))
	{
		$to = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string('���').'" LIMIT 1'));
		if(isset($to['id']))
		{
			if($u->info['align'] == 2 || $u->info['haos'] > time()) {
				$re = '<div align="left">��������� ����������� ������ ������� ������ �������</div>';
			}elseif($to['id']==$u->info['id'])
			{
				$re = '<div align="left">����� ����� ������ ���-�� ������ ���� ;)</div>';
			}elseif($u->info['level']<1)
			{
				$re = '<div align="left">������ ������� ����� ������� � 1-�� ������</div>';
			}else{
				$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "63" OR `im`.`type` = "64") AND `iu`.`id` = "'.mysql_real_escape_string($_POST['itemgift']).'" AND `iu`.`uid` = "'.$u->info['id'].'" AND `iu`.`gift` = "" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" LIMIT 1'));
				if(isset($itm['id']))
				{
					//$itm['data'] = '';
					
					$itm['gtxt1'] = $_POST['podarok2'];
					$itm['gtxt2'] = $_POST['txt'];
										
					$itm['gtxt1'] = str_replace('\x3C','<',$itm['gtxt1']);
					$itm['gtxt1'] = str_replace('\x3','>',$itm['gtxt1']);	
					$itm['gtxt1'] = htmlspecialchars($itm['gtxt1'],NULL,'cp1251');
					$itm['gtxt2'] = str_replace('\x3C','<',$itm['gtxt2']);
					$itm['gtxt2'] = str_replace('\x3','>',$itm['gtxt2']);	
					$itm['gtxt2'] = htmlspecialchars($itm['gtxt2'],NULL,'cp1251');
					
					$upd = mysql_query('UPDATE `items_users` SET `data` = "'.$itm['data'].'",`gtxt1` = "'.mysql_real_escape_string($itm['gtxt1']).'",`gtxt2` = "'.mysql_real_escape_string($itm['gtxt2']).'",`uid` = "'.$to['id'].'",`gift` = "'.$u->info['login'].'",`time_create` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
					if($upd)
					{
						$re = '<div align="left">������� �� ��� ������� ��������� � &quot;'.$_POST['to_login'].'&quot;, �� ��� ��������� ��������������.</div>';
					}else{
						$re = '<div align="left">�� ������� ������� �������</div>';
					}
				}else{
					$re = '<div align="left">������� �� ������</div>';
				}
			}
		}else{
			$re = '<div align="left">�������� � ����� ������� �� ������</div>';
		}
	}
	
	if(isset($u->stats['shopSale'],$_GET['sale']))
	{
		$bns = 0+$u->stats['shopSale'];
		if($bns!=0)
		{
			if($bns>0)
			{
				$bns = '+'.$bns;
			}
			$shopProcent -= $bns;
			if($shopProcent>99){ $shopProcent = 99; }
			if($shopProcent<1){ $shopProcent = 1; }
			echo '<div style="color:grey;"><b>� ��� ��������� ����� ��� �������: '.$bns.'%</b><br><small>�� ������� ��������� �������� �� '.(100-$shopProcent).'% �� �� ���������</small></div>';
		}
	}
	
	if(!isset($_GET['otdel'])) 
	{
		$_GET['otdel'] = 1;
	}

	$sid = 300;
	/*
	if($u->info['id'] = 10001) {
		$sp = mysql_query('SELECT * FROM `items_shop` WHERE `sid` = 1 AND `kolvo` > 0');
		while($pl = mysql_fetch_array($sp)) {
			$test = mysql_fetch_array(mysql_query('SELECT `iid` FROM `items_shop` WHERE `sid` = 300 AND `item_id` = "'.$pl['item_id'].'" LIMIT 1'));
			if(!isset($test['iid'])) {
				//��������� �������
				mysql_query('INSERT INTO `items_shop` (`sid`,`item_id`,`kolvo`,
				`data2`,`magic_inc`,`timeOver`,`overType`,`secret_id`,`r`,`price_1`,`price_2`,`level`,`tr_items`,`max_buy`) VALUES (
									"300","'.$pl['item_id'].'","10000",
				"'.$pl['data2'].'","'.$pl['magic_inc'].'","'.$pl['timeOver'].'","'.$pl['overType'].'","'.$pl['secret_id'].'","'.$pl['r'].'","'.$pl['price_1'].'","'.$pl['price_2'].'","'.$pl['level'].'","'.$pl['tr_items'].'","'.$pl['max_buy'].'"
				)');
			}
		}
	}
	*/
	$error = '';
	
	if(isset($_GET['buy']))
	{
		if($u->newAct($_GET['sd4'])==true)
		{
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = '�� ������� ��� ������ ������ ���� �������?';
		}
	}elseif(isset($_GET['sale']) && isset($_GET['item']) && $u->newAct($_GET['sd4']))
	{
		$id = (int)$_GET['item'];
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		$po = $u->lookStats($itm['data']);		
		if(isset($po['nosale']))
		{
			$error = '�� ������� ������� ������� ...';
		}elseif($pl['type']<29 && ($po['srok'] > 0 || $pl['srok'] > 0))
		{
			$error = '�� ������� ������� ������� ...';
		}elseif(isset($po['frompisher']))
		{
			$error = '�� ������� ������� ������� ...';
		}elseif(isset($itm['id']))
		{
			if($itm['1price']>0)
			{
				$itm['price1'] = $itm['1price'];
			}
			$shpCena = $itm['price1'];
			
			$plmx = 0;
			if($itm['iznosMAXi']!=$itm['iznosMAX'] && $itm['iznosMAX']!=0)
			{
				$plmx = $itm['iznosMAX'];
			}else{
				$plmx = $itm['iznosMAXi'];
			}
			
			if($itm['iznosNOW']>0)
			{
				$prc1 = $itm['iznosNOW']/$plmx*100;
			}else{
				$prc1 = 0;
			}
			$shpCena = $shpCena/100*(100-$prc1);
			if($itm['iznosMAX']>0 && $itm['iznosMAXi']>0 && $itm['iznosMAXi']>$itm['iznosMAX'])
			{
				$shpCena = $shpCena/100*($itm['iznosMAX']/$itm['iznosMAXi']*100);
			}
			$shpCena = $u->round2($shpCena/100*(100-$shopProcent));
			if($shpCena<0)
			{
				$shpCena = 0;
			}
			$col = $u->itemsX($itm['id']);	
			if($col>0)
			{
				$shpCena = $shpCena*$col;
			}
			if($shpCena<0)
			{
				$shpCena = 0;
			}
			$upd2 = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			if($upd2)
			{
				$u->info['money'] += $shpCena;
				$upd = mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				if($upd)
				{
					$error = '�� ������� ������� ������� &quot;'.$itm['name'].' [x'.$col.']&quot; �� '.$shpCena.' ��.';
					mysql_query('UPDATE `items_users` SET `inGroup` = "0",`delete` = "'.time().'" WHERE `inGroup` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT '.$itm['group_max'].'');
					$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.shop</font>&quot;: ������� &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] ��� ������ � ������� �� <B>'.$shpCena.' ��.</B>.',time(),$u->info['city'],'System.shop',0,0);
				}else{
					$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.shop</font>&quot;: ������� &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] ��� ������ � ������� �� <B>'.$shpCena.' ��.</B> (������� �� ����������).',time(),$u->info['city'],'System.shop',0,0);
					$error = '�� ������� ������� �������...';
				}
			}else{
				$error = '�� ������� ������� �������...';
			}
		}else{
			$error = '������� �� ������ � ���������.';
		}
	}
	
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<script type="text/javascript">
	function AddCount(name, txt)
	{
		document.getElementById("hint4").innerHTML = '<table border=0 width=100% cellspacing=1 cellpadding=0 bgcolor="#CCC3AA"><tr><td align=center><B>������ ����. ����</td><td width=20 align=right valign=top style="cursor: pointer" onclick="closehint3();"><BIG><B>x</TD></tr><tr><td colspan=2>'+
		'<form method=post><table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor="#FFF6DD"><tr><INPUT TYPE="hidden" name="set" value="'+name+'"><td colspan=2 align=center><B><I>'+txt+'</td></tr><tr><td width=80% align=right>'+
		'���������� (��.) <INPUT TYPE="text" NAME="count" id=count size=4></td><td width=20%>&nbsp;<INPUT TYPE="submit" value=" �� ">'+
		'</TD></TR></form></TABLE></td></tr></table>';
		document.getElementById("hint4").style.visibility = 'visible';
		document.getElementById("hint4").style.left = '100px';
		document.getElementById("hint4").style.top = '100px';
		document.getElementById("count").focus();
	}
	function closehint3() {
	document.getElementById('hint4').style.visibility='hidden';
	Hint3Name='';
	}	
	</script>
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
	<tr><td valign="top"><?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
	<br />
	<TABLE width="100%" cellspacing="0" cellpadding="4">
	<TR>
	<form name="F1" method="post">
	<TD valign="top" align="left">
	<!--�������-->
	<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#a5a5a5">
	<div id="hint3" style="visibility:hidden"></div>
	<tr>
	<td align="center" height="21">
    <?php	
		/*�������� �������� (������)*/
		if(!isset($_GET['sale']) && !isset($_GET['gifts']) && isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (1=>'<b>�����&nbsp;&quot;������: �������,����&quot;</b>',2=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',3=>'<b>�����&nbsp;&quot;������: ������,������&quot;</b>',4=>'<b>�����&nbsp;&quot;������: ����&quot;</b>',5=>'<b>�����&nbsp;&quot;������: ���������� ������&quot;</b>',6=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',7=>'<b>�����&nbsp;&quot;������: ��������&quot;</b>',8=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',9=>'<b>�����&nbsp;&quot;������: ������ �����&quot;</b>',10=>'<b>�����&nbsp;&quot;������: ������� �����&quot;</b>',11=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',12=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',13=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',14=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',15=>'<b>�����&nbsp;&quot;����&quot;</b>',16=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',17=>'<b>�����&nbsp;&quot;��������� ������: ��������&quot;</b>',18=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',19=>'<b>�����&nbsp;&quot;����������: �����������&quot;</b>',20=>'<b>�����&nbsp;&quot;����������: ������ � ��������&quot;</b>',21=>'<b>�����&nbsp;&quot;��������&quot;</b>',22=>'<b>�����&nbsp;&quot;��������&quot;</b>',23=>'<b>�����&nbsp;&quot;�������&quot;</b>',24=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',25=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',26=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',27=>'<b>�����&nbsp;&quot;�������: ����������&quot;</b>');
			if(isset($otdels_small_array[$_GET['otdel']]))
			{
				echo $otdels_small_array[$_GET['otdel']];	
			}
			
		} elseif (isset($_GET['sale']) && $_GET['sale']) 
		{
			echo '
			<B>�����&nbsp;&quot;������&quot;</B><br>
			����� �� ������ ������� ���� ����, �� ������ �����...<br>
			� ��� � �������: 
			';
		} elseif (isset($_GET['gifts'])) 
		{
			echo '
			<B>�����&nbsp;&quot;������� �������&quot;</B>';	
		}
	?>
	</tr>
	<tr><td>
	<!--������ / ��������-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
    <?php
	if(isset($_GET['gifts']))
	{
	?>
    
<tr><td bgcolor="#D5D5D5">
�� ������ ������� ������� �������� ��������. ��� ������� ����� ������������ � ���������� � ���������.
<OL>
<LI>������� ����� ���������, �������� ������ ������� �������<BR>
����� 
<INPUT TYPE=text NAME=to_login value="">
<LI>���� �������. ����� ������������ � ���������� � ��������� (�� ����� 60 ��������)<BR>
<INPUT TYPE=text NAME=podarok2 value="" maxlength=60 size=50>
<LI>�������� ����� ���������������� ������� (� ���������� � ��������� �� ������������)<BR>
<TEXTAREA NAME=txt ROWS=6 COLS=80></TEXTAREA>
<LI>��������, �� ����� ����� �������:<BR>
<INPUT TYPE=radio NAME=from value=0 checked> <B><?=$u->info['login']?></B> [<?=$u->info['level']?>]<BR>
<INPUT TYPE=radio NAME=from value=1 > ��������<BR> 
<? if($u->info['clan']>0){ ?><INPUT TYPE=radio NAME=from value=2 > �� ����� �����<BR><? } ?>
<LI>������� ������ <B>��������</B> ��� ���������, ������� ������ ����������� � �������:<BR>
</OL>
<input name="itemgift" id="itemgift" type="hidden" value="0" />
</td></tr>
    <?php
	}
		if(isset($_GET['gifts']))
		{
			$itmAll = $u->genInv(3,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "63" OR `im`.`type` = "64") AND `iu`.`gift` = "" ORDER BY `iu`.`lastUPD`  DESC');
			if($itmAll[0]==0)
			{
				$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">� ��� ��� ���������� ���������</td></tr>';
			}else{
				$itmAllSee = $itmAll[2];
			}
			echo $itmAllSee;
		}elseif(!isset($_GET['sale']))
		{
			//������� ���� � �������� ��� �������
			$u->shopItems($sid);
		}else{
			//������� ���� � ��������� ��� �������
			$itmAll = $u->genInv(2,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `iu`.`lastUPD` DESC');
			if($itmAll[0]==0)
			{
				$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">�����</td></tr>';
			}else{
				$itmAllSee = $itmAll[2];
			}
			echo $itmAllSee;
		}
	?>
	</TABLE>	 
	</TD></TR>
	</TABLE>
	</TD>
	</FORM>
	</TR>
	</TABLE>	
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=6.180.0.100&rnd=<? echo $code; ?>';" title="<? thisInfRm('6.180.0.100',1); ?>">����������� �������</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div><br />
      <div align="right">
      <small>
	  �����: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	  � ��� � �������: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> ��.</b> &nbsp;
      </small>
      </div>
	  <br />
	  <?php
	/*��������*/
	if(!isset($_GET['sale']))
	{
	echo '
	<INPUT TYPE="button" value="������� ����" onclick="location=\'?otdel='.$_GET['otdel'].'&sale=1\'">&nbsp;
	';
	} else {
	echo '
	<INPUT TYPE="button" value="������ ����" onclick="location=\'?otdel='.$_GET['otdel'].'\'">&nbsp;
	';
	}
	?>
    <INPUT TYPE="button" value="��������" onclick="location = '<? echo $_SERVER['REQUEST_URI']; ?>';"><BR>
	  </div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>������ ��������</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*�������� �������� (������)*/
		$otdels_array = array (1=>'������: �������,����',2=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',3=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������,������',4=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����',5=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���������� ������',6=>'������: ������',7=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',8=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',9=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ �����',10=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������� �����',11=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����',12=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',13=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����',14=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',15=>'����',16=>'��������� ������: ������',17=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',18=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',19=>'����������: �����������',20=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ � ��������',21=>'��������',22=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',23=>'�������',24=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',25=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',26=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',27=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����������');
		$i=1;
		while ($i!=-1)
		{
			if(isset($otdels_array[$i]))
			{
				if(isset($_GET['otdel']) && $_GET['otdel']==$i) 
				{
				$color = 'C7C7C7';	
				} else {
				$color = 'e2e0e0';
				}
			echo '
			<A HREF="?otdel='.$i.'"><DIV style="background-color: #'.$color.'">
			'.$otdels_array[$i].'
			</A></DIV>
			';
			} else {
			$i = -2;
			}
			$i++;
		}
		
		if(isset($_GET['gifts'])) 
		{
		$color = 'C7C7C7';	
		} 
		echo '<A HREF="?otdel=32&gifts=1"><DIV style="background-color: #'.$color.'">������� �������</A></DIV>';
	?>
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>