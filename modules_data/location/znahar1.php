<?
if(!defined('GAME'))
{
	die();
}
if($u->room['file']=='znahar'){?>
<STYLE>
.H3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold;}
</STYLE>
<SCRIPT>
function gfastshow(dsc, dx, dy) { top.fullfastshow(document, mmoves3, window.event, dsc, dx, dy); }
function ghideshow() { top.fullhideshow(mmoves3); }
</SCRIPT>
</HEAD>
<body leftmargin=5 topmargin=5 marginwidth=5 marginheight=5 bgcolor=e2e0e0>
<div id="mmoves3" style="background-color:#FFFFCC; visibility:hidden; z-index: 101; overflow:visible; position:absolute; border-color:#666666; border-style:solid; border-width: 1px; padding: 2px;"></div>
<SCRIPT src='http://img.combats.ru/i/commoninf.js'></SCRIPT>
<TABLE width=100%>
<TR><TD><DIV class='H3' align=center>������� �������</DIV>
<BR>
<BR>
<b><i>������ ���� ��������� ���������, ���������� � �������� �������� ���������� � �������� �����...<BR>
�������, ����� ����� �������� ���� ������.
����� ���-�� ����... ��� ������ ��� ���� � ������...</i></b><BR><BR>
������: <B><?=$u->info['money'];?></B> ��.<BR>
���������� �����������������: ������� - ������������!<BR>
</TD>
<TD width=1 valign=top>
<?
if($c['znahar']==1){$raspst=99999;}else{$raspst=0;}
#------------------������� � ������
$st = $u->lookStats($u->info['stats']);
$vinos = array(
	/*   level,summvinos*/
	"0" => 3,
	"1" => 4,
	"2" => 5,
	"3" => 6,
	"4" => 7, 
	"5" => 8,
	"6" => 9,
	"7" => 10,
	"8" => 11,
	"9" => 13,
	"10" => 16,
	"11" => 21,
	"12" => 41,
	"21" => 41
);
#------------------������� � ������
?>
<table  border="0" cellpadding="0" cellspacing="0">
<tr align="right" valign="top">
<td>
<!-- -->
<? echo $goLis; ?>
<!-- -->
<table  border="0" cellspacing="0" cellpadding="0">
<tr>
<td nowrap="nowrap" id="moveto">
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">

<tr>
<td bgcolor="#D3D3D3"><img src="http://img.combats.ru/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="?rnd=0.454008319854562&path=1.100.1.6.5" onclick="return check_access();" class="menutop" title="����� ��������: 20 ���.
������ � ������� 0 ���.">���� 2</a></td>
</tr>

<tr>
<td bgcolor="#D3D3D3"><img src="http://img.combats.ru/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.221&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.221',1); ?>">�������� ���</a></td>
</tr>
</table>
</td>
</tr>
</table>
<!-- <br /><span class="menutop"><nobr>������� �������</nobr></span>-->
</td>
</tr>
</table>
<div id="mmoves" style="background-color:#FFFFCC; visibility:hidden; overflow:visible; position:absolute; border-color:#666666; border-style:solid; border-width: 1px; padding: 2px; white-space: nowrap;"></div>
</HTML>
</TD>
</TR>
</TABLE>
<TABLE>
<TR bgcolor=#D8D8D8>
<TD><IMG width=20 height=20 src='http://img.combats.ru/i/misc/strsmall.gif'> ��������������</TD>
<TD><IMG width=20 height=20 src='http://img.combats.ru/i/misc/cureelixirsmall.gif'> �����������</TD>
<TD><IMG width=20 height=20 src='http://img.combats.ru/i/misc/weaponsmall.gif'> ������</TD>
<TR>
<TD width=270 valign=top>
<SCRIPT>
//////////////��������,��������,�����, +�������, �� ������� ��������(���� ���������),������� ����������/����������
var pr = new Array(
'����', 's1', <?=$st['s1'];?>, <?=$u->stats['s1']-$st['s1'];?>, 3, 0,
'��������', 's2', <?=$st['s2'];?>, <?=$u->stats['s2']-$st['s2'];?>, 3, 0,
'��������', 's3', <?=$st['s3'];?>, <?=$u->stats['s3']-$st['s3'];?>, 3, 0,
'������������', 's4', <?=$st['s4'];?>, 0, <?=$vinos[$u->info['level']]?>, 0
<?if ($u->info['level'] > 3) {?>
,'���������', 's5', <?=$st['s5'];?>, <?=$u->stats['s5']-$st['s5'];?>, 0, 0
<?}if ($u->info['level'] > 6) {?>
,'��������', 's6', <?=$st['s6'];?>, 0, 0, 0
<?}if ($u->info['level'] > 9) {?>
,'����������', 's7',  <?=$st['s7'];?>, 0, 0, 0
<?}if ($u->info['level'] > 12) {?>
,'����', 's8', <?=$st['s8'];?>, 0, 0, 0
<?}if ($u->info['level'] > 15) {?>
,'������� ����', 's9', <?=$st['s9'];?>, 0, 0, 0
<?}if ($u->info['level'] > 18) {?>
,'��������������', 's10', <?=$st['s10'];?>, 0, 0, 0
<?}?>
);
function getprstr(i) {
var ss= pr[i]+": "+(pr[i+2]+pr[i+3]+pr[i+5]);
if (pr[i+3] || pr[i+5]) {
ss+=" ("+pr[i+2];
if (pr[i+3]) {ss+=(pr[i+3]<0?"":"+")+pr[i+3]}
if (pr[i+5]) {
if (pr[i+5] > 0) {
ss+=" <font color=green>+"+pr[i+5];
} else {
ss+=" <font color=#8f0000>"+pr[i+5];
}
ss+="</font>";
}
ss+=")";
}
return ss;
}
var freepr = 0;
var freemoves = <?=$raspst;?>;
var movecost = 5;
function modpr(i, to) {
if (to == 1 && freepr==0) {
return;
}
if (to == -1 && pr[i+2] + pr[i+5] <= pr[i+4]) {
return;
}
pr[i+5] += to;
freepr -= to;
document.all["pr"+i].innerHTML = getprstr(i);
document.all["prfree"].innerHTML = freepr;
var moves = 0;
for (j=0; j<pr.length; j+=6) {
if (pr[j+5] > 0) {moves+=pr[j+5]};
}
movedonebutton.disabled=(freepr || moves==0?true:false);
document.all["prmoves"].innerHTML = moves+((freemoves < moves)?" �� "+(movecost*(moves - freemoves))+" ��.":(moves?" / ���������":""));
}
function movedone() {
if (freepr) {
return;
}
var s="";
for (j=0; j<pr.length; j+=6) {
if (pr[j+5]) {
s+="&"+pr[j+1]+"="+pr[j+5];
};
}
if (!s) {return};
location="?movestat=0.<?=$code;?>&sd4=<?=$u->info['id']?>"+s;
}
var s="<TABLE>";
for (i=0; i<pr.length; i+=6) {
if (pr[i+2]!=-1) {
s+="<TR><TD width=200 id=pr"+i+">"+getprstr(i) + "</TD>";
s+='<TD><img src=http://img.combats.com/i/minus.gif border=0 onclick="modpr('+i+',-1)" style="cursor: hand"> '
s+='<img src=http://img.combats.com/i/plus.gif  border=0 onclick="modpr('+i+',1)"  style="cursor: hand"></TR>';
}
}
s+="</TABLE>";
s+="<small>(��������: <span id='prfree'>0</span>, �������������: <span id='prmoves'>0</span>)<BR>";
document.write(s);
</SCRIPT>
<input type=button onclick="movedone();" id='movedonebutton' value="���������" disabled>
</TD>
<TD width=270 valign=top>
<BR><BR><BR>
<small><center>� ��� ��� �����������</center></small>
</TD>
<TD valign=top><BR>
������ �������� ������� � ������<BR>

<form method=post><input type=hidden value='<?=$u->info['id'];?>' name='dropmastery'><INPUT type=submit value='�������� <?echo $c['znahar']==1? "���������":"(32��.)"?>' onclick="return confirm('�� ������������� ������ �������� ������?')"><HR color=black></form>
����������� ���������<BR>
<form method=post><input type=hidden value='<?=$u->info['id'];?>' name='dropperks'> <INPUT type=submit  value='�������� <?echo $c['znahar']==1? "���������":"(300��.)"?>' onclick="return confirm('�� ������������� ������ �������� �����������?')"><HR color=black></form>
��������������<BR>
<form method=post><input type=hidden value='<?=$u->info['id'];?>' name='dropstats'><INPUT type=submit value='�������� <?echo $c['znahar']==1? "���������":"(500��.)"?>' onclick="return confirm('�� ������������� ������ �������� ��� �������������� �� ������������ ������?')"></form>
</TABLE>
<small>������ 7 ���� ����� ���������� ������������� ������� ������� �� ��������� 1 ���������� �����������������, �� �� ����� 15<BR>
��������� �����������, c���� ������ ��� ������������ ����� 5 �����������������<BR>
��������� ������������� �������� ��������� ��������� �������, �������� ������ ������ <B>����� �����</B>
</small>
<BR>
</TABLE>
<div>
<?//�������?>
</div>
</BODY>
</HTML>
<?}?>