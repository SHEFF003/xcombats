<?php
if(!defined('GAME'))die();

if(isset($file) && $file[0]=='nakova2.php'){
    if(isset($_GET['back'])) {
		mysql_query('UPDATE `stats` SET `x` = "3",`y` = "43",`s` = "3" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		header('location: main.php');
	}
	$test = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_nakova2_use" LIMIT 1'));
    if(isset($test['id']) && $test['uid'] != $u->info['id']) {
		mysql_query('UPDATE `stats` SET `x` = "3",`y` = "43",`s` = "3" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		header('location: main.php');
		die();
    }elseif(!isset($test['id'])) {
		if( $u->info['sex'] == 0 ) {
			$d->sys_chat('<b>'.$u->info['login'].'</b> �������������� &quot;�����������&quot;, ������ ������ ������ ���� ����������');
		}else{
			$d->sys_chat('<b>'.$u->info['login'].'</b> ��������������� &quot;�����������&quot;, ������ ������ ������ ���� ����������');
		}
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`time`,`vars`) VALUES (
			"'.$u->info['dnow'].'","'.$u->info['id'].'","'.time().'","obj_nakova2_use"
		)');
    }
    if(!isset($_GET['otdel'])){
		$_GET['otdel'] = 20;
    }

    $sid = 700;

    $error = '';
    
    if( isset($_GET['buy']) ) {
	if( $u->newAct($_GET['sd4']) == true ){
	    $re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x'],'sudba='.$u->info['login'].'|frompisher='.$d->info['id2'].'|nosale=1');
	}else{
	    $re = '�� ������� ��� ������ �������� ���� �������?';
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
	.pH3 { COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
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
		<tr>
			<td valign="top">
				<div align="center" class="pH3">����������</div><?php
				echo '<b style="color:red">'.$error.'</b>';
				?><br />
				<TABLE width="100%" cellspacing="0" cellpadding="4">
					<TR>
						<TD valign="top" align="left">
							<form name="F1" method="post">
								<!--�������-->
								<div id="hint3" style="visibility:hidden"></div>
								<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#a5a5a5">
									<tr>
										<td align="center" height="21">
<?php	
	/*�������� �������� (������)*/ 
	$otdels_small_array = array (
	//1 => '<b>�����&nbsp;&quot;������&quot;</b>', 
	//2 => '<b>�����&nbsp;&quot;������: �������,����&quot;</b>',
	//3 => '<b>�����&nbsp;&quot;������: ������&quot;</b>',
	//4 => '<b>�����&nbsp;&quot;������: ������,������&quot;</b>',
	//5 => '<b>�����&nbsp;&quot;������: ����&quot;</b>',
	//6 => '<b>�����&nbsp;&quot;������: ���������� ������&quot;</b>',
	//
	//7 => '<b>�����&nbsp;&nbsp;�������: ��������&nbsp;</b>',
	//
	//8 => '<b>�����&nbsp;&quot;������: ����� � �������&quot;</b>',
	//9 => '<b>�����&nbsp;&quot;������: ������&quot;</b>',
	//10 => '<b>�����&nbsp;&quot;������: ��������&quot;</b>',
	//11 => '<b>�����&nbsp;&quot;������: ������&quot;</b>',
	//12 => '<b>�����&nbsp;&quot;������: ������ �����&quot;</b>',
	//13 => '<b>�����&nbsp;&quot;������: ������� �����&quot;</b>',
	//14 => '<b>�����&nbsp;&quot;������: �����&quot;</b>',
	//15 => '<b>�����&nbsp;&quot;������: ������&quot;</b>',
	//16 => '<b>�����&nbsp;&quot;������: �����&quot;</b>',
	//17 => '<b>�����&nbsp;&quot;������: ������&quot;</b>',
	//
	//18 => '<b>�����&nbsp;&quot;����&quot;</b>',
	//
	19 => '<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',
	20 => '<b>�����&nbsp;&quot;��������� ������: ��������&quot;</b>'
	//21 => '<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',
	//
	//22 => '<b>�����&nbsp;&quot;����������: �����������&quot;</b>',
	//23 => '<b>�����&nbsp;&quot;����������: ������ � ��������&quot;</b>',
	//24 => '<b>�����&nbsp;&quot;����������: �������&quot;</b>',
	//25 => '<b>�����&nbsp;&quot;����������: ����������&quot;</b>',
	//26 => '<b>�����&nbsp;&quot;����������: �������&quot;</b>',
	//27 => '<b>�����&nbsp;&quot;����������: ��������������&quot;</b>',
	//28 => '<b>�����&nbsp;&quot;����������: �����������&quot;</b>',
	//29 => '<b>�����&nbsp;&quot;����������: ���������&quot;</b>',
	//
	//30 => '<b>�����&nbsp;&quot;��������&quot;</b>',
	//31 => '<b>�����&nbsp;&quot;��������&quot;</b>',
	//32 => '<b>�����&nbsp;&quot;�������&quot;</b>',
	//33 => '<b>�����&nbsp;&quot;�������: ��������&quot;</b>',
	//34 => '<b>�����&nbsp;&quot;�������: ��������&quot;</b>',
	//35 => '<b>�����&nbsp;&quot;�������: ��������&quot;</b>',
	//36 => '<b>�����&nbsp;&quot;�������: ����������&quot;</b>',
	//37 => '<b>�����&nbsp;&quot;�������� ������: �������&quot;</b>',
	//38 => '<b>�����&nbsp;&quot;��������������: ������&quot;</b>'
	
	); 
	if(isset($otdels_small_array[$_GET['otdel']])){
	echo $otdels_small_array[$_GET['otdel']];	
	} 
?>
										</td>
									</tr>
									<tr>
										<td>
											<!--������ / ��������-->
											<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5"><?php
												//������� ���� � �������� ��� �������
												$u->shopItems($sid);
											?></TABLE>	 
										</TD>
									</TR>
								</TABLE> 
							</FORM>
						</TD>
					</TR>
				</TABLE>
			</td>
			<td width="280" valign="top">
				<TABLE cellspacing="0" cellpadding="0">
					<tr>
						<TD width="100%">&nbsp;</TD>
						<TD>
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
															<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?back=1&rnd=<?=$code?>';">��������� �����</a></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div><br />
					<div align="right">
						<small>
							�����: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
							� ��� � �������: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> ��.</b> &nbsp;
						</small>
					</div>
					<br />
					<INPUT TYPE="button" value="��������" onclick="location = '<? echo $_SERVER['REQUEST_URI']; ?>';"><BR>
				</div>
				<div style="background-color:#A5A5A5;padding:1"><center><B>������ ��������</B></center></div>
				<div style="line-height:17px;">
<?php
	/*�������� �������� (������)*/
	$otdels_array = array (
	//2=>'������� � ����',
	//3=>'������',
	//4=>'������ � ������',
	//5=>'����',
	//6=>'���������� ������',
	//7=>'�������',
	//8=>'����� � ������� '
	//9=>'&nbsp;������',
	//10=>'��������',
	//11=>'������',
	//12=>'������ �����',
	//13=>'������� �����',
	//14=>'�����',
	//15=>'������',
	//16=>'�����',
	//17=>'������',
	//18=>'����',
	//19=>'��������� ������: ������',
	20=>'&nbsp;��������',
	21=>'&nbsp;������'
	
	//22=>'����������',		
	//23=>'������ � ��������',
	//24=>'�������',
	//25=>'����������',
	//26=>'�������',
	//27=>'��������������',
	//28=>'�����������',
	//29=>'���������',
	//
	//30=>'��������',
	//31=>'��������',
	//32=>'�������',
	//33=>'��������',
	//34=>'��������',
	//35=>'��������',
	//36=>'����������',
	//37=>'�������� ������: �������'
	);
	//$otdels_array = array (1=>'��������� ������: ��������',2=>' ������');
	
	foreach($otdels_array as $key=>$val){
	if(isset($key) && isset($val)){
		if(isset($_GET['otdel']) && $_GET['otdel']==$key) {
		$color = 'C7C7C7';	
		} else {
		$color = 'e2e0e0';
		}
		echo '<A HREF="?otdel='.$key.'"><DIV style="background-color: #'.$color.'">'.$otdels_array[$key].'</DIV></A>';
	} 
	} 
?>
				</div>
			</td>
		</tr>
    </table>
	<br>
    <div id="textgo" style="visibility:hidden;"></div>
<?
}
?>