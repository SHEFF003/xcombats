<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='berezka')
{	

	/*
	if( isset($u->bank['id']) && $u->bank['moneyBuy'] > 0 ) {
		$u->bank = array();
	}
	*/

	if(isset($u->stats['shopSaleEkr'],$_GET['sale'])){
		$bns = 0+$u->stats['shopSaleEkr'];
		if($bns!=0){
			if($bns>0){
				$bns = '+'.$bns;
			}
			//$shopProcent = $u->shopSaleM( $shopProcent , $itm );
			if($shopProcent>99){ $shopProcent = 99; }
			if($shopProcent<1){ $shopProcent = 1; }
			echo '<div style="color:grey;">&nbsp; <b>� ��� ��������� ����� ��� �������: '.$bns.'%</b></div>';
		}
	}

	if(!isset($_GET['otdel'])) 
	{
		$_GET['otdel'] = 1;
	}
	
	$sid = 2;

	$sale_ekr = true;
	if( $c['shop_type2'] == 0 ) {
		$sale_ekr = false;
	}
	//if( $u->stats['silver'] > 0 ) {
	//	$sale_ekr = true;
	//}else{
	//	if( isset($_GET['sale']) ) {
	//		unset($_GET['sale']);	
	//	}
	//}

	$error = '';
	
	if(isset($_GET['buy']) && isset($u->bank['id'])){
		if($u->newAct($_GET['sd4'])==true)
		{
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = '�� ������� ��� ������ ������ ���� �������?';
		}
	}elseif(isset($_GET['sale']) && isset($_GET['item']) && $u->newAct($_GET['sd4']) && isset($u->bank['id']) && $sale_ekr == true ) {
		$id = (int)$_GET['item'];
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.*, count(`iuu`.id) as inGroupCount
			FROM `items_users` AS `iu`
			LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`)
			LEFT JOIN `items_users` as `iuu` ON (`iuu`.inGroup = `iu`.inGroup AND `iuu`.item_id = `im`.id )
			WHERE `iuu`.`uid`="'.$u->info['id'].'" AND `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`data` NOT LIKE "%|zazuby=%" AND `im`.`price2` > 0 AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		$po = $u->lookStats($itm['data']);	
		if($u->info['allLock'] > time()) {
			$po['nosale'] = 1;
		}
		
		//$effvip = mysql_fetch_array(mysql_query('SELECT `id`,`timeUse` FROM `eff_users` WHERE `data` LIKE "%add_silver=%" AND `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
		//$cblim = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `ekr_sale` WHERE `uid` = "'.$u->info['id'].'" AND `time` >= '.$effvip['timeUse'].' LIMIT 1'));
		
		/*if( $cblim[0] >= $u->stats['silver'] * 5 ) {
			$error = '����� ������ ��������� � ������� ��������, �������� VIP �������';
		}else*/
		if( ($itm['gift'] != '' && $itm['gift'] != '0') && (  $itm['type'] == 37 || $itm['type'] ==  38 || $itm['type'] == 39 || $itm['type'] == 63 ) ) {
			$error = '������ ��������� �������, ��� ������ ���������� �� ������! :)';
		}elseif(isset($po['nosale'])){
			$error = '�� ������� ������� ������� ...';
		}elseif(isset($po['fromshop']) && ($po['fromshop'] != 777 && $po['fromshop'] != 2)){
			$error = '������� �� ��� ���������� �� ����-�������, ��� ������ ������� �����...';
		}elseif($itm['gift'] != '0' && $itm['gift'] != '') {
				$error = '�� ������� ������� ������� ... ���-���� ������� ;)';
		}elseif(isset($po['frompisher'])){
			$error = '�� ������� ������� ������� ... ������� �� ����������';
		}elseif(isset($po['srok'])){
			$error = '�������� �� ������ �������� ��������� ������ ...';
		}elseif(isset($itm['id'])){
			if($itm['2price']>0){
				$itm['price2'] = $itm['2price'];
			}
			$shpCena = round($itm['price2'],2);
			$plmx = 0;
			if($itm['iznosMAXi']!=ceil($itm['iznosMAX']) && ceil($itm['iznosMAX'])!=0){
				$plmx = ceil($itm['iznosMAX']);
			}else{
				$plmx = $itm['iznosMAXi'];
			}
			if($itm['iznosNOW']>0){
				$prc1 = floor($itm['iznosNOW'])/ceil($plmx)*100;
			}else{
				$prc1 = 0;
			}
			$shpCena = $u->shopSaleM( $shpCena , $itm );
			$shpCena = $shpCena/100*(100-$prc1);
			if(ceil($itm['iznosMAX'])>0 && $itm['iznosMAXi']>0 && $itm['iznosMAXi']>ceil($itm['iznosMAX'])){
				$shpCena = $shpCena/100*(ceil($itm['iznosMAX'])/$itm['iznosMAXi']*100);
			}
			if( isset($po['art']) ) {
				$shpCena = $u->round2($shpCena*$u->berezCena()); // ������� ������� 35%
			}else{
				$shpCena = $u->round2($shpCena*$u->berezCena()); // ������� ������� 35%
			}
			if($shpCena < 0){
				$shpCena = 0;
			}
			$col = $u->itemsX($itm['id']);	
			if($col>0){
				$shpCena = $shpCena*$col;
			}
			if($shpCena<0){
				$shpCena = 0;
			}
			$upd2 = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			if($upd2){
				if($col>1){
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id`="'.$itm['item_id'].'" AND `uid`="'.$itm['uid'].'" AND `inGroup` = "'.$itm['inGroup'].'" LIMIT '.$col.'');	
				}
				$u->bank['money2'] += $shpCena;
				$upd = mysql_query('UPDATE `bank` SET `money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
				if($upd){
					mysql_query('INSERT INTO `ekr_sale` (`uid`,`time`,`money2`) VALUES ("'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($shpCena).'")');
					$u->info['catch'] += $shpCena;
					mysql_query('UPDATE `users` SET `catch` = "'.$u->info['catch'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$error = '�� ������� ������� ������� &quot;'.$itm['name'].' [x'.$col.']&quot; �� '.$shpCena.' ���.';
					mysql_query('UPDATE `items_users` SET `inGroup` = "0",`delete` = "'.time().'" WHERE `inGroup` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT '.$itm['group_max'].'');
					$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.Ekrshop</font>&quot;: ������� &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] ��� ������ � ������� �� <B>'.$shpCena.' ���.</B>.',time(),$u->info['city'],'System.Ekrshop',0,$shpCena);
				}else{
					$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.Ekrshop</font>&quot;: ������� &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] ��� ������ � ������� �� <B>'.$shpCena.' ���.</B> (������� �� ����������).',time(),$u->info['city'],'System.Ekrshop',0,0);
					$error = '�� ������� ������� ������� ...';
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
	function AddCount(name, txt){
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
  .shop_menu_txt { background-color: #d5d5d5; }
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
    <? if(isset($u->bank['id']) && ($u->bank['money2']>=0.00 || $u->info['admin']>0)){ ?>
	<!--�������-->
	<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#a5a5a5">
	<div id="hint3" style="visibility:hidden"></div>
	<tr>
	<td align="center" height="21">
	<?php 
		/*�������� �������� (������)*/
		if(!isset($_GET['sale']) && isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (1=>'<b>�����&nbsp;&quot;������: �������,����&quot;</b>',2=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',3=>'<b>�����&nbsp;&quot;������: ������,������&quot;</b>',4=>'<b>�����&nbsp;&quot;������: ����&quot;</b>',5=>'<b>�����&nbsp;&quot;������: ���������� ������&quot;</b>',6=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',7=>'<b>�����&nbsp;&quot;������: ��������&quot;</b>',8=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',28=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',9=>'<b>�����&nbsp;&quot;������: ������ �����&quot;</b>',10=>'<b>�����&nbsp;&quot;������: ������� �����&quot;</b>',11=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',12=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',13=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',14=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',15=>'<b>�����&nbsp;&quot;����&quot;</b>',16=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',17=>'<b>�����&nbsp;&quot;��������� ������: ��������&quot;</b>',18=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',19=>'<b>�����&nbsp;&quot;����������: �����������&quot;</b>',20=>'<b>�����&nbsp;&quot;����������: ������ � ��������&quot;</b>',21=>'<b>�����&nbsp;&quot;��������&quot;</b>',22=>'<b>�����&nbsp;&quot;��������: ��������&quot;</b>',23=>'<b>�����&nbsp;&quot;�������&quot;</b>',24=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',25=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',26=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',27=>'<b>�����&nbsp;&quot;�������: ����������&quot;</b>',29=>'<b>�������� �������</b>');
			if(isset($otdels_small_array[$_GET['otdel']]))
			{
				echo $otdels_small_array[$_GET['otdel']];	
			}
			
		} elseif (isset($_GET['sale'])) 
		{
			echo '
			<B>�����&nbsp;&quot;������� ���������&quot;</B>';	
		}
	?>
	</tr>
	<tr><td>
	<!--������ / ��������-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
	<?php
		//������� ���� � �������� ��� �������
		if(isset($_GET['sale'])) {
			//������� ���� � ��������� ��� �������
			$itmAll = $u->genInv(16,'`iu`.`uid`="'.$u->info['id'].'" AND
			
			`iu`.`delete`="0" AND `im`.`price2` > 0 AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD` DESC');
			if($itmAll[0]==0)
			{
				$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">�����</td></tr>';
			}else{
				$itmAllSee = $itmAll[2];
			}
			//echo '<tr><td align="center" bgcolor="#e2e0e0"><small>������� ��������� ��������� �� ���. �������������� � ������ ������ ��������, � ���-�� ������ �� �������.<br><b>������� ��������� ���� 0-7 ������� ��� 99%, ���� 8-�� ������ ��� 95%, ���� 9-�� ������, � ��� �� ������ � �������� ����� ����� � ������� ��� 70%.</b><br><font color=red><b>��������!</b></font> ��� ���������, �������, ����, ����������� ��������� �� ������ � ��������� �������� ��� �������! </small></td></tr>'.$itmAllSee;
			echo '<tr><td align="center" bgcolor="#e2e0e0"><small>������� ��������� ��������� �� ���. �������������� � ������ ������ ��������, � ���-�� ������ �� �������.<br><font color=red><b>��������!</b></font> ��� ���������, �������, ����, ����������� ��������� �� ������ � ��������� �������� ��� �������! </small></td></tr>'.$itmAllSee;
		}else{
			$u->shopItems($sid);
		}
	?>
	</TABLE>	 
	</TD></TR>
	</TABLE>
    <div align="center">
      <? }else{ ?>
      <div align="center">������� �������� ��������, �� ������ ����� ������ ���� �����������. ������� ����� ������ ����� � ����� � ������ � ����.<br />
        <br />
        <?
		/*
		if(isset($_POST['bank']) && isset($u->bank['id']))
		{
			echo '<font color="red"><b>���������� ���� ����, ���� � ������� ��������</b></font>';
		}elseif(isset($_POST['bank']) && !isset($u->bank['id']))
		{
			echo '<font color="red"><b>�������� ������ �� ����������� �����.</b></font>';
		}
		*/
		?>
        <br /><br />
        <style>
		.w1{position:absolute;z-index:1102;}.1x1 {
	width:1px;
	height:1px;
	display:block;
}.wi1s0{width:5px;height:6px;background:url(http://img.xcombats.com/i/bneitral_03.gif) 1px 0px no-repeat;}.wi1s1{height:6px;background:url(http://img.xcombats.com/i/bneitral_05.gif);}.wi1s2{width:5px;height:6px;background:url(http://img.xcombats.com/i/bneitral_03.gif) -2px 0px no-repeat;}.wi1s3{width:5px;background:url(http://img.xcombats.com/i/bneitral_17.gif) 1px 0px repeat-y;}.wi1s4{width:5px;background:url(http://img.xcombats.com/i/bneitral_19.gif) -1px 0px repeat-y;background-position:right;background-color:#675600;}.wi1s5{width:5px;background:url(http://img.xcombats.com/i/bneitral_19.gif) 0px 0px repeat-y;}.wi1s6{height:6px;background:url(http://img.xcombats.com/i/bneitral_05.gif);}.wi1s7{background:#ddd5bf;}.wi1s8{background:url(http://img.xcombats.com/i/bneitral_05.gif);}.wi1s9{width:5px;height:6px;background:url(http://img.xcombats.com/i/bneitral_19.gif);}.wi1s10{background-color:#b1a993;white-space:nowrap;color:#003388;padding:2px 2px 2px 7px;min-width:140px;}.wi1s10 text{cursor:move;}.wi1s10 img{cursor:pointer;}.wi1s11{}.wi1s12{}
		</style>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="wi1s0"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s1"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s2"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
          </tr>
          <tr>
            <td class="wi1s3"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td>            
    		<table width="300" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td bgcolor="#B1A996"><div align="center"><strong>���� � �����</strong></div></td>
                </tr>
                <tr>
                  <td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>�������� ���� � ������� ������<br />
                            <select name="bank" id="bank">
                            <?
                            $scet = mysql_query('SELECT `id` FROM `bank` WHERE `block` = "0" AND `uid` = "'.$u->info['id'].'"');
                            while ($num_scet = mysql_fetch_array($scet)) 
                            {
                                 echo "<option>".$u->getNum($num_scet['id'])."</option>";
                            }
                            ?>
                            </select>
                            <input style="margin-left:5px;" type="password" name="bankpsw" id="bankpsw" />
                            <label></label>
                      </small>
                          <input class="btnnew" style="margin-left:3px;" type="submit" name="button" id="button" value=" ok " />
                  </div></td>
                </tr>
            </table>
            
            </td>
            <td class="wi1s4"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
          </tr>
          <tr>
            <td class="wi1s5"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s6"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s8"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
          </tr>
          </table>        
        <br />
      </div>
      <? } ?>
    </div></TD>
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.11&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.11',1); ?>">����������� �����</a></td>
	</tr>

	<tr>
	<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.298&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.298',1); ?>">������� ����������</a></td>
	</tr>

	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div><br />
     <? if(isset($u->bank['id'])){ ?>
      <div align="right">
      <small>
	  �����: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	 	�<? echo $u->getNum($u->bank['id']); ?>: <b><? echo $u->bank['money1']; ?></b>��. <b><? echo $u->bank['money2']; ?></b>���. <a href="main.php?bank_exit=<? echo $code; ?>"><img src="http://img.xcombats.com/i/close_bank.gif" style="cursor:pointer;" title="������� ������ �� ������"></a></small>
      </small>
      </div>
	  <br />
      <center>
	<?php
	if(isset($u->bank['id']) && ($u->bank['money2']>=0.00 || $u->info['admin']>0)){
		/*��������*/
		if($sale_ekr == true) {
			if(!isset($_GET['sale'])){
				//if( $u->stats['silver'] > 0 ) { 
					echo '<INPUT class="btnnew" TYPE="button" value="������� ����" onclick="location=\'?otdel='.$_GET['otdel'].'&sale=1\'">&nbsp;';
				//}else{
				//	echo '<INPUT style="color:grey" TYPE="button" value="������� ����" onclick="alert(\'������ ��� ������� VIP\');">&nbsp;';
				//}
			} else {
				echo '<INPUT class="btnnew" TYPE="button" value="������ ����" onclick="location=\'?otdel='.$_GET['otdel'].'\'">&nbsp;';
			}
		}
	}
	?>
    <INPUT class="btnnew" TYPE="button" value="��������" onclick="location = '<? echo str_replace('exit','bait',$_SERVER['REQUEST_URI']); ?>';">
    </center>
    <BR>
	  </div>
      <? if(isset($u->bank['id']) && ($u->bank['money2']>=0.00 || $u->info['admin']>0)){ ?>
	<div style="background-color:#A5A5A5;padding:1"><center><B>������ ��������</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*�������� �������� (������)*/
		$otdels_array = array (1=>'&nbsp;&nbsp;�������,����',2=>'&nbsp;&nbsp;������',3=>'&nbsp;&nbsp;������,������',4=>'&nbsp;&nbsp;����',5=>'&nbsp;&nbsp;���������� ������',6=>'&nbsp;&nbsp;������',7=>'&nbsp;&nbsp;��������',8=>'&nbsp;&nbsp;������',9=>'&nbsp;&nbsp;������ �����',10=>'&nbsp;&nbsp;������� �����',11=>'&nbsp;&nbsp;�����',12=>'&nbsp;&nbsp;������',13=>'&nbsp;&nbsp;�����',14=>'&nbsp;&nbsp;������',15=>'&nbsp;&nbsp;����',16=>'&nbsp;&nbsp;������',17=>'&nbsp;&nbsp;��������',18=>'&nbsp;&nbsp;������',19=>'&nbsp;&nbsp;�����������',20=>'&nbsp;&nbsp;������ � ��������',21=>'&nbsp;&nbsp;��������',22=>'&nbsp;&nbsp;��������',23=>'&nbsp;&nbsp;�������',24=>'&nbsp;&nbsp;��������',25=>'&nbsp;&nbsp;��������',26=>'&nbsp;&nbsp;��������',27=>'&nbsp;&nbsp;����������',28=>'&nbsp;&nbsp;����� � �������', 29=>'&nbsp;&nbsp;���������� �����������',29=>'&nbsp;&nbsp;�������� �������'/*,30=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������� ����� �����'*/);
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
				if($i == 1) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/1.png"> <b>������:</b></div>';
			} elseif($i == 6) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/2.png"> <b>������:</b></div>';
			} elseif($i == 15) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/3.png"> <b>����:</b></div>';
			} elseif($i == 16) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/4.png"> <b>��������� ������:</b></div>';
			} elseif($i == 19) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/6.png"> <b>����������:</b></div>';
			} elseif($i == 21) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/7.png"> <b>��������:</b></div>';
			} elseif($i == 22) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/5.png"> <b>��������:</b></div>';
			} elseif($i == 23) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/8.png"> <b>�������:</b></div>';
			} elseif($i == 28) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/9.png"> <b>�������������:</b></div>';
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
		echo '</DIV>';
	}
	?>
	</div>
    <? } ?>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>