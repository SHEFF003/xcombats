<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='shop2_')
{
	$shopProcent = 1;
	
	unset($_GET['sale']);
		
	if(!isset($_GET['otdel'])) 
	{
		$_GET['otdel'] = 1;
	}

	$sid = 609;

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
		if($u->info['allLock'] > time()) {
			$po['nosale'] = 1;
		}
		if(isset($po['nosale']))
		{
			$error = '�� ������� ������� �������, ������ ������� ������� �������� ...';
		}elseif($pl['type']<29 && ($po['srok'] > 0 || $pl['srok'] > 0))
		{
			$error = '�� ������� ������� �������, ����� ���� �������� ...';
		}elseif(isset($po['frompisher']))
		{
			$error = '�� ������� ������� �������, ������� �� ���������� ...';
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
				mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `inGroup` = "'.$itm['id'].'"');
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
		if(!isset($_GET['sale']) && isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (
			'',
			'<b>�����&nbsp;&quot;������: �������,����&quot;</b>',
			'<b>�����&nbsp;&quot;������: ������&quot;</b>',
			'<b>�����&nbsp;&quot;������: ������,������&quot;</b>',
			'<b>�����&nbsp;&quot;������: ����&quot;</b>',
			'<b>�����&nbsp;&quot;������: ���������� ������&quot;</b>',
			
			'<b>�����&nbsp;&nbsp;�������: ��������&nbsp;</b>',
			
			'<b>�����&nbsp;&quot;������: ����� � �������&quot;</b>',
			
			'<b>�����&nbsp;&quot;����������: �����������&quot;</b>',
			'<b>�����&nbsp;&quot;������: ��������&quot;</b>',
			'<b>�����&nbsp;&quot;������: ������&quot;</b>',
			'<b>�����&nbsp;&quot;������: ������ �����&quot;</b>',
			'<b>�����&nbsp;&quot;������: ������� �����&quot;</b>',
			'<b>�����&nbsp;&quot;������: �����&quot;</b>',
			'<b>�����&nbsp;&quot;������: ������&quot;</b>',
			'<b>�����&nbsp;&quot;������: �����&quot;</b>',
			'<b>�����&nbsp;&quot;������: ������&quot;</b>',
			'<b>�����&nbsp;&quot;����&quot;</b>',
			'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',
			'<b>�����&nbsp;&quot;��������� ������: ��������&quot;</b>',
			'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',
			
			'<b>�����&nbsp;&quot;����������: �����������&quot;</b>',
			'<b>�����&nbsp;&quot;����������: ������ � ��������&quot;</b>'
			,'<b>�����&nbsp;&quot;����������: �������&quot;</b>'
			,'<b>�����&nbsp;&quot;����������: ����������&quot;</b>'
			,'<b>�����&nbsp;&quot;����������: �������&quot;</b>'
			,'<b>�����&nbsp;&quot;����������: ��������������&quot;</b>'
			,'<b>�����&nbsp;&quot;����������: �����������&quot;</b>'
			,'<b>�����&nbsp;&quot;����������: ���������&quot;</b>'
			
			,'<b>�����&nbsp;&quot;��������&quot;</b>',
			'<b>�����&nbsp;&quot;��������&quot;</b>',
			'<b>�����&nbsp;&quot;�������&quot;</b>',
			'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',
			'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',
			'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',
			'<b>�����&nbsp;&quot;�������: ����������&quot;</b>',
			'<b>�����&nbsp;&quot;�������� ������: �������&quot;</b>',
			'<b>�����&nbsp;&quot;��������������: ������&quot;</b>');
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
		}
	?>
	</tr>
	<tr><td>
	<!--������ / ��������-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
	<?
		if(isset($_GET['gifts']))
		{
			$itmAll = $u->genInv(3,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "63" OR `im`.`type` = "64") AND `iu`.`gift` = "" ORDER BY `lastUPD`  DESC');
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.10&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.10',1); ?>">��������������� �������</a></td>
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
	  � ��� � �������: <span class="icos_WL"><b><?php echo round($u->rep['rep3']-$u->rep['rep3_buy']); ?> ��������������</small></span></b> &nbsp;
      </small>
      </div>
	  <br />
	  <?php
	/*��������*/
	/*if(!isset($_GET['sale']))
	{
	echo '
	<INPUT TYPE="button" value="������� ����" onclick="location=\'?otdel='.$_GET['otdel'].'&sale=1\'">&nbsp;
	';
	} else {*/
	//echo '
	//<INPUT TYPE="button" value="������ ����" onclick="location=\'?otdel='.$_GET['otdel'].'\'">&nbsp;
	//';
	//}
	?>
    <INPUT TYPE="button" value="��������" onclick="location = '<? echo str_replace('item','',str_replace('buy','',$_SERVER['REQUEST_URI'])); ?>';"><BR>
	  </div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>������ ��������</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*�������� �������� (������)*/
		$otdels_array = array (
		'',
		'������: �������,����',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������,������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���������� ������',
		'�������: ��������&nbsp;',
		'������: ����� � �������&nbsp;',
		'����������: �����������'
		/*'������: ������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ �����',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������� �����',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',
		'����',
		'��������� ������: ������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',
		
		'����������: �����������',		
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ � ��������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���������',
		
		'��������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',
		'�������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����������',
		'�������� ������: �������'*/);
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
	?>
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>