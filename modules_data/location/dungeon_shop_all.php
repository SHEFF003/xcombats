<?php
if( !defined('GAME') ) die(); 
if( $u->room['file'] == 'dungeon_shop_all' ) {

	if(!isset($_GET['otdel'])){
		$_GET['otdel'] = 1;
	}

	$dungeon = mysql_fetch_array( mysql_query('SELECT * FROM `dungeon_room` WHERE `shop`="'.$u->room['id'].'" LIMIT 1') );
	$getout_room = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `id` = "'.$dungeon['id'].'" LIMIT 1'));

	$sid = 400; // ����� ��������� �������
	$error = '';
	
	if(isset($_GET['buy'])){
		if($u->newAct($_GET['sd4'])==true){
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = '�� ������� ��� ������ ������ ���� �������?';
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
		
		$otdels_small_array = array (
			1 => '<b>�����&nbsp;&quot;������ ������ ���������&quot;</b>', 
			2 => '<b>�����&nbsp;&quot;������&quot;</b>',
			3 => '<b>�����&nbsp;&quot;������ ����&quot;</b>',
			4 => '<b>�����&nbsp;&quot;���������&quot;</b>',
			5 => '<b>�����&nbsp;&quot;��������� ����&quot;</b>',
			6 => '<b>�����&nbsp;&quot;��������&quot;</b>',
			
			7 => '<b>�����&nbsp;&nbsp;�������� ������&nbsp;</b>',
			
			8 => '<b>�����&nbsp;&quot;������ �������� ����������&quot;</b>'
		
		); 
		if(isset($otdels_small_array[$_GET['otdel']]))
		{
			echo $otdels_small_array[$_GET['otdel']];	
		}
	?>
	</tr>
	<tr><td>
	<!--������ / ��������-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
    <?php
		//������� ���� � �������� ��� �������
		$u->shopItems($sid);
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
	<?
		if( isset($getout_room) ){
			echo '<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location=\'main.php?loc=1.180.0.321&rnd='.$code.'\'" title="';
			echo thisInfRm('1.180.0.321',1);
			echo '">���������� ������</a></td>';
		}
	?> 
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
      <?
	  if($u->info['level'] < 8) {
		?>
        <br />�����: <?=$u->zuby($u->info['money4'])?> &nbsp; &nbsp;
        <?  
	  }
	  ?>
      </small>
      </div>
	  <br />
    <INPUT class="btnnew" style="display:inline-block; vertical-align:baseline;" TYPE="button" value="��������" onclick="location = '<? echo str_replace('item','',str_replace('buy','',$_SERVER['REQUEST_URI'])); ?>';"><BR>
	  </div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>������ ��������</B></center></div>
	<div style="line-height:17px;">
	<?
		/*�������� �������� (������)*/ 
		$otdels_array = array (
			1=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/capitalcity.gif"> ������ ������ ���������',
			2=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/angelscity.gif"> ������',
			3=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/sandcity.gif"> ������ ����',
			4=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/demonscity.gif"> ���������',
			5=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/emeraldscity.gif"> ��������� ����',
			6=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/suncity.gif"> ��������',
			7=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/devilscity.gif"> �������� ������',
			8=>'<img style="display:inline-block;vertical-align:bottom; width="34" height="19" src="http://img.xcombats.com/i/city_ico2/10.gif"> ������ ...'
		);
		if($u->rep['rep'.$dungeon['city']] >= 10000){
			if($sid==802) $otdels_array[8] = '�����';
			if($sid==802) $otdels_array[14] = '�����';
			if($sid==801) $otdels_array[9] = '������';
			if($sid==803) $otdels_array[12] = '������ �����';
			if($sid==803) $otdels_array[13] = '������� �����';
			if($sid==804) $otdels_array[10] = '��������';
			$otdels_array[22] = '����������';
			$otdels_array[7] = '�������';
		}
		foreach($otdels_array as $key=>$val){
			if(isset($key) && isset($val)){
				if(isset($_GET['otdel']) && $_GET['otdel']==$key) {
					$color = 'C7C7C7';	
				} else {
					$color = 'e2e0e0';
				}
				echo '<A HREF="?otdel='.$key.'"><DIV style="background-color: #'.$color.'">'.$otdels_array[$key].'</A></DIV>';
			} 
		}
		
		if(isset($_GET['gifts'])) 
		{
			$color = 'C7C7C7';	
		}else{
			$color = 'e2e0e0';
		}
		echo '</DIV>';
	?>
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>