<?
if(!defined('GAME'))
{
	die();
}
session_start();
$zv = array(1=>'light',2=>'admin',3=>'dark');
if($u->info['clan']>0){
$res = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."' LIMIT 1"));
$clan = $res['name'];
}else{
$clan = "";
}
if($_POST['enter'] && $_POST['pass']) {
	$data = mysql_fetch_array(mysql_query("SELECT `id` FROM `moder` WHERE `align` = '".mysql_real_escape_string($u->info['align'])."' AND `trPass` = '".md5($_POST['pass'])."' LIMIT 1;"));
		if($data){
			$_SESSION['moder'] = md5(md5($u->info['id']));
		}else{
			echo'������ �����.';
		}
}
if($u->info['admin']>0) {
	$atp = '����������� ���� �����';
}
if($u->info['align']=='0.99'){
	if ($u->info['sex'] == 0) {
		$atp = '����������� � ����, ������';
	}else{
		$atp = '����������� � ����, ������';
	}
}
if($u->info['align']>1 && $u->info['align']<2){
	if($u->info['sex'] == 0) {
		$atp = '�� �������� � ����� ����, ����';
	}else{
		$atp = '�� �������� � ����� ����, ������';
	}
}
if ($u->info['align'] == '3') {
	if ($u->info['sex'] == 0) {
		$atp = '�������� � ����, ������';
	}else{
		$atp = '�������� � ����, ������';
	}
}
if($u->info['align']>3 && $u->info['align']<4){
	if($u->info['sex'] == 0) {
		$atp = '�� �������� � ����� ����, ����';
	}else{
		$atp = '�� �������� � ����� ����, ������';
	}
}
$p = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
$zv = array(1=>'light',2=>'admin',3=>'dark');
	$a = floor($p['align']);
	if($u->info['admin']>0)
	{
		$zv = $zv[2];
	}else{
		$zv = $zv[$a];
	}

?>
<SCRIPT src='http://<?=$c['img'];?>/js/commoninf.js'></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="/js/sl2.22.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="http://<?=$c['img'];?>/js/keypad.js"></SCRIPT>
<style>
.modpow {
	background-color:#ddd5bf;
}
.mt {
	background-color:#b1a993;
	padding-left:10px;
	padding-right:10px;
	padding-top:5px;
	padding-bottom:5px;
}
.md {
	padding:10px;
}
</style>
<script>
function openMod(title,dat)
{
	var d = document.getElementById('useMagic');
	if(d!=undefined)
	{
		document.getElementById('modtitle').innerHTML = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">'+title+'</td><td width="30" valign="top"><div align="right"><a title="������� ����" onClick="closeMod(); return false;" href="#">x</a></div></td></tr></table>';
		document.getElementById('moddata').innerHTML = dat;
		d.style.display = '';
	}
}

function closeMod()
{
	var d = document.getElementById('useMagic');
	if(d!=undefined)
	{
		document.getElementById('modtitle').innerHTML = '';
		document.getElementById('moddata').innerHTML = '';
		d.style.display = 'none';
	}
}
</script>

<TABLE width=100%>
<tr>
<TD align=center><h3><?=$atp;?> <SCRIPT>drwfl("<?=$u->info['login']?>",<?=$u->info['id']?>,"<?=$u->info['level']?>",<?=$u->info['align']?>,"<?=$clan?>")</SCRIPT> !</h3>
<TD width=100 align=right><INPUT style="width=30;" TYPE=button value="&rarr;" onclick="location='main.php?';">
</tr>
<tr>
<table>
<tr><td>
<?	//���������� ������ ����������
	$go = 0;
if(isset($_GET['go'])){$go = round($_GET['go']);}
if($go==2 && $u->info['admin']>0){
	include('moder/new/editor.php');
}
if($go==1 && $p['editAlign']==1){
	include('moder/new/editalign.php');
}
?>
<div id="useMagic" style="display:none; position:absolute; border:solid 1px #776f59; left: 50px; top: 186px;" class="modpow">
<div class="mt" id="modtitle"></div><div class="md" id="moddata"></div></div>
<?if($go==0){?>
<?if($u->info['align']>=0.99 && $u->info['align']<2 || $u->info['admin']>0){?>
<a href="#" onClick="openMod('<b>&quot;���������&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>�����: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/invoke_spellcure.gif" title="���������" /></a>
<a href="#" onClick="openMod('<b>&quot;�������� ����&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>�����: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/ATTACK.GIF" title="�������� ����" /></a>
<?}?>
<?if($u->info['align']>=3 && $u->info['align']<4 || $u->info['admin']>0){?>
<a href="#" onClick="openMod('<b>&quot;���������&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>�����: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/vampir.gif" title="���������" /></a>
<a href="#" onClick="openMod('<b>&quot;������ ������� �������&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>�����: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/ATTACK.GIF" title="������ ������� �������" /></a>
<?}?>
</td></tr></table>
<?}if(!$_SESSION['moder'] && $p['trPass']!=''){
?>
<TABLE align=center><TR><FORM action="main.php?<?=$zv?>" name="F2" method=POST><TD>
<FIELDSET><LEGEND><B><font color=blue>�������� ������</font></B> </LEGEND>
<TABLE>
<TR><TD valign=top>
<TABLE>
<TR><TD>������</td><td> <INPUT style='width:90;' type=password value="" name=pass></td><TD style='padding: 0, 0, 3, 5'><img border=0 SRC="http://img.combats.com/i/misc/klav_transparent.gif" style='cursor: hand' onClick="KeypadShow(1, 'F2', 'pass', 'keypad2');"></TD></tr>
<TR><TD colspan=3 align=center><INPUT TYPE=submit value="�����" name=enter></td></tr>
</TABLE>
</TD>
<TD><div id="keypad2" align=center style="display: none;"></div></TD></TR>
</TABLE>
</FIELDSET>
</TD></TR></TABLE></FORM>
<?
}else{
														/*���������� ������� � ������� ;)*/
$uer = '';
	if(isset($_GET['usemod'])){
	$srok = array(5=>'5 �����',15=>'15 �����',30=>'30 �����',60=>'���� ���',180=>'��� ����',360=>'����� �����',720=>'���������� �����',1440=>'���� �����',4320=>'���� �����');
	$srokt = array(1=>'1 ����',3=>'3 ���',7=>'������',14=>'2 ������',30=>'�����',60=>'2 ������',365=>'���',24=>'���������',6=>'�����');
	if(isset($_POST['usem1'])){include('moder/usem1.php');}
	elseif(isset($_POST['usem2'])){include('moder/usem2.php');}
	elseif(isset($_POST['usesm'])){include('moder/usesm.php');}
	elseif(isset($_POST['useban'])){include('moder/useban.php');}
	elseif(isset($_POST['useunban'])){include('moder/useunban.php');}
	elseif(isset($_POST['usehaos'])){include('moder/usehaos.php');}
	elseif(isset($_POST['useshaos'])){include('moder/useshaos.php');}
	
	}
														/*���������� ������� � ������� ;)*/
	if($go==0) {
		if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)){?>
			<h4>��������/����� ��������</h4>
			<table width="100%">
				<tr>
					<td>
						<? if($u->info['admin']>0){ echo '<a href="main.php?'.$zv.'&go=2"><img width="40" height="25" title="������������� ������, ������� � ��������� ���������" src="http://img.xcombats.com/editor2.gif"></a>'; } ?>
						<? if($p['editAlign']==1){ echo '<a href="main.php?'.$zv.'&go=1"><img title="������������� ����������� �����������" src="http://img.xcombats.com/editor.gif"></a>'; } ?>
						<? if($p['m1']==1 || $p['citym1']==1){ ?> <a href="#" onClick="openMod('<b>�������� ��������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'5\'>5 �����</option><option value=\'15\'>15 �����</option><option value=\'30\'>30 �����</option><option value=\'60\'>1 ���</option><option value=\'180\'>3 ����</option><option value=\'360\'>6 �����</option><option value=\'720\'>12 �����</option><option value=\'1440\'>�����</option></select> <input type=\'submit\' name=\'usem1\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/sleep.gif" title="�������� ��������" /></a> <? } ?>
						<? if($p['m2']==1 || $p['citym2']==1){ ?> <a href="#" onClick="openMod('<b>�������� ��������� ��������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'30\'>30 �����</option><option value=\'60\'>1 ���</option><option value=\'180\'>3 ����</option><option value=\'360\'>6 �����</option><option value=\'720\'>12 �����</option><option value=\'1440\'>�����</option></select> <input type=\'submit\' name=\'usem2\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/sleepf.gif" title="�������� ��������� ��������" /></a> <? } ?>
						<? if($p['sm1']==1 || $p['sm2']==1 || $p['citysm1']==1 || $p['citysm2']==1){ ?><a href="#" onClick="openMod('<b>�������� ��������� ��������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'1\'>���</option><option value=\'2\'>�����</option><option value=\'3\'>��� + �����</option></select> <input type=\'submit\' name=\'usesm\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/sleep_off.gif" title="����� �������� ��������" /></a> <? } ?>
						<? if($p['banned']==1 || $p['ban0']==1){ ?> <a href="#" onClick="openMod('<b>�������� ������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useban\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button6.gif" title="�������� ������" /></a> <? } ?>
						<? if($p['unbanned']==1){ ?> <a href="#" onClick="openMod('<b>����� �������� ������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useunban\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button7.gif" title="����� �������� ������" /></a> <? } ?>
						<? if($p['haos']==1){ ?> <a href="#" onClick="openMod('<b>��������� � ����</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'7\'>������</option><option value=\'14\'>2 ������</option><option value=\'30\'>�����</option><option value=\'60\'>2 ������</option><? if($p['haosInf']==1){ ?><option value=\'1\'>���������</option><? } ?> <input type=\'submit\' name=\'usehaos\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button4.gif" title="��������� � ����" /></a> <? } ?>
						<? if($p['shaos']==1){ ?> <a href="#" onClick="openMod('<b>��������� �� �����</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useshaos\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button5.gif" title="��������� �� �����" /></a> <? } ?>
						<? if($p['deletInfo']==1){ ?> <a href="#" onClick="openMod('<b>�������������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'7\'>������</option><option value=\'14\'>2 ������</option><option value=\'30\'>�����</option><option value=\'60\'>2 ������</option><option value=\'1\'>���������</option> <input type=\'submit\' name=\'usedeletinfo\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/cui.gif" title="�������������" /></a>
                        <a href="#" onClick="openMod('<b>����� �������� �������������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'unusedeletinfo\' value=\'���-��\'></form>');"><img src="http://<?=$c['img'];?>/i/items/uncui.gif" title="����� �������������" /></a> <? } ?>
       
						
						
						
						
					</td>
				</tr>
			</table>
<?
		}
	}
}?>


