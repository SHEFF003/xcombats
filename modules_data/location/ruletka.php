<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='ruletka')
{	
?>
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
	td {
	text-align: center;
}
    </style>
	<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3">�������</div>
      <br />
	  <div align="center">
	    <p>������� �������� � �������� ������. �� ������ �������� ��., ����������� ������ �� �����.</p>
	    <p>
          <object id="ruletka" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="940" height="340">
	        <param name="movie" value="casino/ruletka.swf" />
	        <param name="quality" value="high" />
	        <param name="wmode" value="opaque" />
	        <param name="swfversion" value="6.0.65.0" />
	        <!-- ���� ��� param ���������� ������������� Flash Player 6.0 r65 � ����� ������� ������ ��������� ��������� ������ Flash Player. ������� ���, ���� �� ������, ����� ������������ ������ ������. -->
	        <param name="expressinstall" value="Scripts/expressInstall.swf" />
	        <param name="BGCOLOR" value="#dedfde" />
	        <!-- ��������� ��� object �� �������������� ��������� Internet Explorer. ������� ������� ��� �� Internet Explorer ��� ������ IECC. -->
	        <!--[if !IE]>-->
	        <object type="application/x-shockwave-flash" data="casino/ruletka.swf" width="940" height="340">
	          <!--<![endif]-->
	          <param name="quality" value="high" />
	          <param name="wmode" value="opaque" />
	          <param name="swfversion" value="6.0.65.0" />
	          <param name="expressinstall" value="Scripts/expressInstall.swf" />
	          <param name="BGCOLOR" value="#dedfde" />
	          <!-- ������� ���������� ��������� �������������� ���������� ��� ������������� Flash Player 6.0 � ����� ������ ������. -->
	          <div>
	            <h4>��� ����������� ���� �������� ��������� ����� ����� ������ Adobe Flash Player.</h4>
	            <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="�������� ������������� Adobe Flash Player" /></a></p>
              </div>
	          <!--[if !IE]>-->
            </object>
	        <!--<![endif]-->
          </object>
	    </p>
	  </div>
	<td width="280" align="left" valign="top">
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.3&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.3',1); ?>">���������� ����</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
    <small>
    <p align="left">���� ������: <b><?
    $mn = 0; $mz = 0;
	
			
				$sm = $u->testAction('`city` = "'.$u->info['city'].'" AND `vars` = "casino_balance" LIMIT 1',1);
				if(!isset($sm['id'])) {			
					$u->addAction(time(),'casino_balance',0);
				}else{
					$mn += $sm['vals'];
				}
	
	$sp = mysql_query('SELECT `money`,`end` FROM `ruletka_coin` WHERE `money` > 0 AND `end` > 0');
	while($pl = mysql_fetch_array($sp))
	{
		if($pl['end']>0)
		{
			$mz += $pl['money'];
		}
	}
	echo ($mn);
	?> ��.</b></p></small></td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>
<script type="text/javascript">
swfobject.registerObject("ruletka");
</script>
