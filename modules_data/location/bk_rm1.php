<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='bk_rm1')
{
	$i = 324;
	$rm_see2 = array();
	while($i <= 348) {
		$rm_see2[$i] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `room` = "'.$i.'" AND `online` > "'.(time()-520).'"'));
		$rm_see2[$i] = 0+$rm_see2[$i][0];
		$i++;
	}
	$rm_see = array();
	$rm_see[$u->room['id']] = ' <img style="display:inline-block;" width="20" height="20" src="http://img.xcombats.com/i/flag.gif" /> ';
?>
		<style>
		.ahm {
			FONT-WEIGHT: bold; COLOR: #003388; TEXT-DECORATION: none
		}
		.ahm:visited {
			FONT-WEIGHT: bold; COLOR: #003388; TEXT-DECORATION: none
		}
		.ahm:active {
			COLOR: #6f0000
		}
		.ahm:hover {
			COLOR: #0066ff
		}		
		</style>
        <?
		?>
		<form>
		<TABLE border=0 cellpadding="0" cellspacing="0"  width=100% style="padding:5px;">
		<INPUT TYPE="hidden" name="setch" value=1>
		<tr><td align=center><?=$u->microLogin($u->info,2)?></td><td>
        <?
		if($re != '') {
			echo '<font style="float:left" color="red"><b>'.$re.'</b></font>';
		}
		?>
        <div style="float:right">
        <INPUT TYPE="submit" class="knopka" name="setch" value="��������"> <INPUT TYPE=button name=combats value="��������" onClick="location.href='main.php?zayvka=1.php';" style="font-weight:bold;"> <INPUT TYPE=button title="��������� / ���������" value="��������� / ���������" onClick="location.href='main.php?inv=1; '"> <INPUT TYPE="button" onClick="location.href='main.php?skills=1&rz=5&all=&rnd=1'" value="���������" title="���������">
        <input type="button" onClick="location.href='main.php?referals'" style="background-color:#A9AFC0" value="���������" />
<INPUT TYPE=button value="���������" style="background-color:#A9AFC0" onClick="window.open('help/combats.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')"> <INPUT TYPE="button" onClick="location.href='main.php?inform=1&inv=1';" value="���������" title="���������">
        </div>
        </td></tr>
		</TABLE>
		<TABLE border=0 cellpadding="0" cellspacing="0"  width=100% style="padding:5px;">
		<TR><TD  align=center><h3>����� �����</h3></TD>
		<TD align="right" width=26%>
		<!--(<?=$rowonmaxc?>)<br>
		(<?=$rowonmax?>)--> 

		</td></TR>
		<TR><TD> </td><TD align="right"><? echo $rm_see[348]; if($rm_see[348] == ''){ ?><INPUT TYPE="button" value="����� � ���������� ����" onClick="location.href='main.php?loc=1.180.0.348';" align="right"><? }else{ ?><INPUT TYPE="button" value="����� �� ����������� �������" onClick="location.href='main.php?loc=1.180.0.9';" align="right"><? } ?> </td></TR>
		</table>
		<TABLE border=0 cellpadding="2" cellspacing="2"  width=100%>
		<TR>
			<TD align=center bgcolor="#99CC99" width="25%">������� ��� �������� (level 0)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.324';" TYPE="button" class="knopka" name="room1" value="�����"><?=$rm_see[324]?>			  &nbsp; <b>(<?=$rm_see2[324]?>) <a  onClick="inforoom(1,event);"><img style="cursor: pointer;" src="http://i.oldbk.com/i/inf.gif"></a></b></TD>
			<TD align=center bgcolor="#99CC99" width="25%">������� ��� �������� 2 (level 0)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.325';" TYPE="button" class="knopka" name="room2" value="�����">
			  <?=$rm_see[325]?>
			&nbsp; <b>(<?=$rm_see2[325]?>) <a  onClick="inforoom(2,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></b></TD>
			<TD align=center bgcolor="#99CC99" width="25%">������� ��� �������� 3 (level 0)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.326';" TYPE="button" class="knopka" name="room3" value="�����">
			  <?=$rm_see[326]?>
			&nbsp; <b>(<?=$rm_see2[326]?>) <a  onClick="inforoom(3,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></b></TD>
			<TD align=center bgcolor="#99CC99" width="25%">������� ��� �������� 4 (level 0)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.327';" TYPE="button" class="knopka" name="room4" value="�����">
			  <?=$rm_see[327]?>
			&nbsp; <b>(<?=$rm_see2[327]?>) <a  onClick="inforoom(4,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></b></TD>
		</TR>
		<TR>
			<TD align=center bgcolor="#99CC00" width="25%">��� ������ (level 1-3)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.328';" TYPE="button" class="knopka" name="room5" value="�����">
		    <?=$rm_see[328]?>			  &nbsp; <b>(<?=$rm_see2[328]?>) <a  onClick="inforoom(5,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></b></TD>
			<TD align=center bgcolor="#99CC00" width="25%">��� ������ 2 (level 1-3)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.329';" TYPE="button" class="knopka" name="room6" value="�����">
		    <?=$rm_see[329]?>			  &nbsp; <b>(<?=$rm_see2[329]?>) <a  onClick="inforoom(6,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></b></TD>
			<TD align=center bgcolor="#99CC00" width="25%">��� ������ 3 (level 1-3)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.330';" TYPE="button" class="knopka" name="room7" value="�����">
			  <?=$rm_see[330]?>
		    &nbsp; <b>(<?=$rm_see2[330]?>) <a  onClick="inforoom(7,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></b></TD>
			<TD align=center width="25%">�������� ��� (level 4-21)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.331';" TYPE="button" class="knopka" name="room8" value="�����">
		    <?=$rm_see[331]?>			  &nbsp; <b>(<?=$rm_see2[331]?>)</b> <a  onClick="inforoom(8,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
		</TR>
		<TR>
			<TD align=center bgcolor="#CC99FF" width="25%">��������� ��� (level 4-6)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.332';" TYPE="button" class="knopka" name="room9" value="�����">
			  <?=$rm_see[332]?> 
			&nbsp; <b>(<?=$rm_see2[332]?>)</b> <a  onClick="inforoom(9,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#00CCFF" width="25%">����� �������-����� (level 7-9)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.333';" TYPE="button" class="knopka" name="room10" value="�����">
		    <?=$rm_see[333]?>			  &nbsp; <b>(<?=$rm_see2[333]?>)</b> <a  onClick="inforoom(10,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#CCFFFF" width="25%">���������� ���  (level 10-12)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.334';" TYPE="button" class="knopka" name="level11" value="�����">
		    <?=$rm_see[334]?>			  &nbsp; <b>(<?=$rm_see2[334]?>)</b> <a  onClick="inforoom(11,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#FF0000" width="25%">����� ����� (level 13-15)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.335';" TYPE="button" class="knopka" name="level12" value="�����">
		    <?=$rm_see[335]?>			  &nbsp; <b>(<?=$rm_see2[335]?>)</b> <a  onClick="inforoom(12,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
		</TR>
		<TR>
			<TD align=center bgcolor="#FF9900" width="25%">���������� ����� (level 16-19)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.336';" TYPE="button" class="knopka" name="room13" value="�����">
		    <?=$rm_see[336]?>			  &nbsp; <b>(<?=$rm_see2[336]?>)</b> <a  onClick="inforoom(13,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#FFFF00" width="25%">�������� ��� (level 19-21)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.337';" TYPE="button" class="knopka" name="room14" value="�����">
		    <?=$rm_see[337]?>			  &nbsp; <b>(<?=$rm_see2[337]?>)</b> <a  onClick="inforoom(14,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#F3F3F3" width="25%">��� ���������<BR><INPUT onClick="location.href='main.php?loc=1.180.0.338';" TYPE="button" class="knopka" name="room15" value="�����">
		    <?=$rm_see[338]?>			  &nbsp; <b>(<?=$rm_see2[338]?>)</b> <a  onClick="inforoom(15,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#FFFFFF" width="25%">����� ������ ��������<BR><INPUT onClick="location.href='main.php?loc=1.180.0.339';" TYPE="button" class="knopka" name="room16" value="�����">
		    <?=$rm_see[339]?>			  &nbsp; <b>(<?=$rm_see2[339]?>)</b> <a  onClick="inforoom(16,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
		</TR>
		<TR>
			<TD align=center bgcolor="#C0C0C0" width="25%">��� ����<BR><INPUT onClick="location.href='main.php?loc=1.180.0.340';" TYPE="button" class="knopka" name="room17" value="�����">
			  <?=$rm_see[340]?> 
			&nbsp; <b>(<?=$rm_see2[340]?>)</b> <a  onClick="inforoom(17,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#909090" width="25%">������� ����<BR><INPUT onClick="location.href='main.php?loc=1.180.0.341';" TYPE="button" class="knopka" name="room18" value="�����">
		    <?=$rm_see[341]?>			  &nbsp; <b>(<?=$rm_see2[341]?>)</b> <a  onClick="inforoom(18,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#E7E3E3" width="25%">��� ������<BR><INPUT onClick="location.href='main.php?loc=1.180.0.342';" TYPE="button" class="knopka" name="room36" value="�����">
			  <?=$rm_see[342]?> 
			&nbsp; <b>(<?=$rm_see2[342]?>)</b> <a  onClick="inforoom(36,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#faf2f2" width="25%">������ (level 1-21)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.343';" TYPE="button" class="knopka" name="room19" value="�����">
			  <?=$rm_see[343]?> 
			&nbsp; <b>(<?=$rm_see2[343]?>)</b> <a  onClick="inforoom(19,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
		</TR>
		<TR>
			<TD align=center bgcolor="#EDEAC3" width="25%">��� �����<BR><INPUT onClick="location.href='main.php?loc=1.180.0.344';" TYPE="button" class="knopka" name="room54" value="�����">
		    <?=$rm_see[344]?>			  &nbsp; <b>(<?=$rm_see2[344]?>)</b> <a  onClick="inforoom(54,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#FFFDE5" width="25%">������� �����<BR><INPUT onClick="location.href='main.php?loc=1.180.0.345';" TYPE="button" class="knopka" name="room55" value="�����">
		    <?=$rm_see[345]?>			  &nbsp; <b>(<?=$rm_see2[345]?>)</b> <a  onClick="inforoom(55,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#BBC6E5" width="25%">������� ������<BR><INPUT onClick="location.href='main.php?loc=1.180.0.346';" TYPE="button" class="knopka" name="room56" value="�����">
		    <?=$rm_see[346]?>			  &nbsp; <b>(<?=$rm_see2[346]?>)</b> <a  onClick="inforoom(56,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD align=center bgcolor="#FB672D" width="25%">��� ������ (level 4-21)<BR><INPUT onClick="location.href='main.php?loc=1.180.0.347';" TYPE="button" class="knopka" name="room57" value="�����">
		    <?=$rm_see[347]?>			  &nbsp; <b>(<?=$rm_see2[347]?>)</b> <a  onClick="inforoom(57,event);"><img style="cursor: pointer;"  src="http://i.oldbk.com/i/inf.gif"></a></TD>
			<TD width="25%"></TD>
		</TR>
		<TR>

			
		</TR>

		</TABLE></form>
<?
}

?>