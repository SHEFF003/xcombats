<?
if(!defined('GAME'))
{
	die();
}

session_start();

$rang = '';
if(floor($u->info['align'])==1) 
{ 
	$rang = '�������'; 
}elseif(floor($u->info['align'])==3) 
{
	$rang = '������'; 
}elseif($u->info['admin']>0){ 
	$rang = '�����'; 
}else{
	$rang = '<i>����������� ��������</i>';
}

/*
if($u->info['admin'] == 0) {
	if(
			($u->info['city'] == 'capitalcity' && $rang == '������') ||
			($u->info['city'] == 'newcapitalcity' && $rang == '�������')
	) {
		die('<center><br>��������� ������������ �������������� ��������� �� ��������� ����������.</center>');
	}
}
*/

if(isset($_GET['exitMod']))
{
	unset($_SESSION['palpsw']);
}

if(isset($_GET['remod']))
{
	if($_GET['remod']==1)
	{
		$_SESSION['remod'] = 3;
	}else{
		$_SESSION['remod'] = 1;
	}
}

$zv = array(1=>'light',2=>'admin',3=>'dark');

$merror = '';

if($u->info['admin']>0)
{
	if($_SESSION['remod']==3 || !isset($_SESSION['remod']))
	{
		$u->info['align'] = '3.99';
	}elseif($_SESSION['remod']==1)
	{
		$u->info['align'] = '1.99';
	}
}

$mod_login = $u->info['login'];

if($u->info['invise'] > 0) {
	$mod_login = "<i>���������</i>";
}

//����������� (�����������)
$vz_id = array(
0=>'m1',
1=>'mm1',
2=>'m2',
3=>'mm2',
4=>'sm1',
5=>'sm2',
6=>'citym1',
7=>'citym2',
8=>'citysm1',
9=>'citysm2',
10=>'addld',
11=>'cityaddld',
12=>'seeld',
13=>'telegraf',
14=>'f1',
15=>'f2',
16=>'f3',
17=>'f4',
18=>'f5',
19=>'f6',
20=>'f7',
21=>'f8',
22=>'boi',
23=>'elka',
24=>'haos',
25=>'haosInf',
26=>'deletInfo',
27=>'zatoch',
28=>'banned',
29=>'unbanned',
30=>'readPerevod',
31=>'provItm',
32=>'provMsg',
33=>'trPass',
34=>'shaos',
35=>'szatoch',
36=>'editAlign',
37=>'priemIskl',
38=>'proverka',
39=>'marry',
40=>'ban0',
41=>'useunnoper',
42=>'usenoper',
43=>'useunalign',
44=>'usealign1',
45=>'usealign3',
46=>'usealign7',
47=>'useuntravm',
48=>'heal',
49=>'invis',
50=>'attack',
51=>'sex',
52=>'unbtl',
53=>'nick',
54=>'testchat',
55=>'newuidinv');
//�������� ������������
$vz = array(
'm1'=>'�������� ��������',
'mm1'=>'�������� �������� (3 ��.)',
'm2'=>'�������� ��������� ��������',
'mm2'=>'�������� ��������� �������� (3 ��.)',
'sm1'=>'����� ��������',
'sm2'=>'����� �������� ��������',
'citym1'=>'�������� �������� (�������������)',
'citym2'=>'�������� ��������� �������� (�������������)',
'citysm1'=>'����� �������� (�������������)',
'citysm2'=>'����� �������� �������� (�������������)',
'addld'=>'�������� ������ � ������ ����',
'cityaddld'=>'�������� ������ � ������ ���� (�������������)',
'seeld'=>'�������� ������� ����',
'telegraf'=>'��������',
'f1'=>'�����. ����� � ������',
'f2'=>'�����. �������� ������',
'f3'=>'�����. �������������� ����',
'f4'=>'�����. �������� ����',
'f5'=>'�����. ����������� ����',
'f6'=>'�����. ������������ / ����������� ����',
'f7'=>'�����. ������������� ����������',
'f8'=>'�����. �������� ����������',
'boi'=>'��������� ����',
'elka'=>'��������� ����',
'haos'=>'����',
'haosInf'=>'���� (���������)',
'deletInfo'=>'����� / �������� �������������',
'zatoch'=>'��������� ���������',
'banned'=>'���������� ���������',
'unbanned'=>'������������� ���������',
'readPerevod'=>'�������� ���������',
'provItm'=>'�������� ���������',
'provMsg'=>'�������� ���������',
'trPass'=>'������� ������',
'shaos'=>'����� ����',
'szatoch'=>'��������� �� ���������',
'editAlign'=>'������� ����������',
'priemIskl'=>'����� / ����������',
'proverka'=>'�������� �� �������',
'marry'=>'��������� / ��������',
'ban0'=>'���������� [0] �������',
'useunnoper'=>'����� ������ �� ��������',
'usenoper'=>'������ �� ��������',
'useunalign'=>'����� ����������\����',
'usealign1'=>'������ ������� ����������',
'usealign3'=>'������ ������ ����������',
'usealign7'=>'������ ����������� ����������',
'useuntravm'=>'�������� ������ ( 1000 ��. � ����� �� ���� )',
'heal'=>'������ �������������� ( 1000 ��. � ����� �� ���� )',
'invis'=>'������ ���������',
'attack'=>'������ ��������� ( 1000 ��. � ����� �� ���� )',
'sex'=>'����� ����',
'unbtl'=>'�������� ��������� �� ���',
'nick'=>'����� ������',
'testchat'=>'��������� ���������',
'newuidinv'=>'�������� ���������');

echo '<script type="text/javascript" src="js/jquery.js"></script>';

$p = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
if(isset($p['id']) || $u->info['align']==1 || $u->info['align']==3)
{
	
	if($u->info['admin']>0)
	{
		$p['editAlign'] = 1;
	}
	
	if(isset($_GET['enter']) && $p['trPass']!='')
	{
		if($u->info['admin']>0 && $_POST['psw']=='admin$enter')
		{
			$_POST['psw'] = $p['trPass'];
		}else{
			$_POST['psw'] = md5($_POST['psw']);
		}
		if($_POST['psw']==$p['trPass'])
		{
			$_SESSION['palpsw'] = $_POST['psw'];
		}else{
			$merror = '<br><center><font color="red"><b>�������� ������.</b></font></center><br>';
		}
	}
	
	$a = floor($p['align']);
	if($u->info['admin']>0)
	{
		$zv = $zv[2];
	}else{
		$zv = $zv[$a];
	}
	if($_SESSION['palpsw']==$p['trPass'] || $p['trPass'] == '')
	{	
	
	//���������� ������ ����������
	$go = 0;
	if(isset($_GET['go']))
	{
		$go = round($_GET['go']);
	}
	
	if(isset($_POST['newuidinv'],$_POST['pometka52017'])) {
		$_GET['newuidinv'] = $_POST['newuidinv'];
	}
	
	if(isset($_GET['newuidinv'])) {	
		$_GET['newuidinv'] = htmlspecialchars($_GET['newuidinv']);
		include('_inv_moder.php');
		die();
	}
	if($go == 3 && $u->info['admin'] > 0) {
	?>
<table width="100%">
  <tr>
    <td align="center"><h3>�������������� ��������� �������</h3></td>
    <td width="150" align="right"><input type="button" value="&gt;" onclick="location='main.php?<? echo $zv; ?>';" />
      <input type="button" value="���������" onclick="location='main.php?go=3&amp;<? echo $zv; ?>';" />
	  <? if($u->info['admin']>0){ ?>
      <input type="button" value="<? if($a==1){ echo 'PAL'; }else{ echo 'ARM'; } ?>" onclick="location='main.php?go=1&amp;<? echo $zv; ?>&amp;remod=<? echo $a; ?>';" />
      <? } ?>
      <? if($p['trPass']!=''){ ?>
      <input type="button" value="X" title="������� ������" onclick="location='main.php?<? echo $zv.'&rnd='.$code; ?>&amp;exitMod=1';" />
      <? } ?></td>
  </tr>
  <tr>
    <td><? 
		if($merror!='')
		{
			echo '<font color="red">'.$merror.'</font>';
		}
		?>
        <?
		$sx = array('�������','�������','�����');
		if(isset($_GET['eq'])) {
			$pl = mysql_fetch_array(mysql_query('SELECT * FROM `an_quest` WHERE `id` = "'.mysql_real_escape_string($_GET['eq']).'" LIMIT 1'));
			if(isset($pl['id'])) {
				if(isset($_POST['pl_name'])) {
					$pl['name'] = $_POST['pl_name'];
					$pl['sex'] = $_POST['pl_sex'];
					$pl['ico_bot'] = $_POST['pl_ico_bot'];
					$pl['name_bot'] = $_POST['pl_name_bot'];
					$pl['info'] = $_POST['pl_info'];
					$pl['act'] = $_POST['pl_act'];
					$pl['next'] = $_POST['pl_next'];
					$pl['win'] = $_POST['pl_win'];
					$pl['data'] = $_POST['pl_data'];
					$pl['room'] = $_POST['pl_room'];
					$pl['module'] = $_POST['pl_module'];
					mysql_query('UPDATE `an_quest` SET 
					`name` = "'.mysql_real_escape_string($pl['name']).'",
					`sex` = "'.mysql_real_escape_string($pl['sex']).'",
					`ico_bot` = "'.mysql_real_escape_string($pl['ico_bot']).'",
					`name_bot` = "'.mysql_real_escape_string($pl['name_bot']).'",
					`info` = "'.mysql_real_escape_string($pl['info']).'",
					`act` = "'.mysql_real_escape_string($pl['act']).'",
					`next` = "'.mysql_real_escape_string($pl['next']).'",
					`win` = "'.mysql_real_escape_string($pl['win']).'",
					`data` = "'.mysql_real_escape_string($pl['data']).'",
					`room` = "'.mysql_real_escape_string($pl['room']).'",
					`module` = "'.mysql_real_escape_string($pl['module']).'"
					WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl = mysql_fetch_array(mysql_query('SELECT * FROM `an_quest` WHERE `id` = "'.mysql_real_escape_string($_GET['eq']).'" LIMIT 1'));
				}
			?>
            <form method="post" action="main.php?<?=$zv?>&go=3&eq=<?=$pl['id']?>">
            #id: <?=$pl['id']?><br />
            �������� ������: <input style="width:200px;" name="pl_name" type="text" value="<?=$pl['name']?>" /><br />
            <hr />
            ���: <input style="width:20px;" name="pl_sex" type="text" value="<?=$pl['sex']?>" /><br />
            �������� ����: <input style="width:216px;" name="pl_ico_bot" type="text" value="<?=$pl['ico_bot']?>" /><br />
            ��� ����: <input style="width:253px;" name="pl_name_bot" type="text" value="<?=$pl['name_bot']?>" />
            <hr />
            <br />
            ����������:<br /><textarea style="width:330px;" name="pl_info"><?=$pl['info']?></textarea><br />
            ��������: <input style="width:255px;" name="pl_act" type="text" value="<?=$pl['act']?>" /><br />
            ��������� �����: <input style="width:200px;" name="pl_next" type="text" value="<?=$pl['next']?>" /><br />
            �������: <input style="width:200px;" name="pl_win" type="text" value="<?=$pl['win']?>" /> (����|��|���|��������)<br />
            ����: <input style="width:200px;" name="pl_data" type="text" value="<?=$pl['data']?>" /><br />
            <hr />
            ������� (�������): <input name="pl_room" type="text" value="<?=$pl['room']?>" /><br />
            ������, �������� (�������): <input name="pl_module" type="text" value="<?=$pl['module']?>" /><hr />
            <input type="submit" value="��������� �����" />
            </form>
            <?
			}else{
				echo '<center>����� �� ������.</center>';
			}
		}else{
			echo '<a href="main.php?'.$zv.'&go=3&add=1">�������� ����� �����</a><hr>';
			if(isset($_GET['del'])) {
				mysql_query('DELETE FROM `an_quest` WHERE `id` = "'.mysql_real_escape_string($_GET['del']).'" LIMIT 1');
			}elseif(isset($_GET['add'])) {
				mysql_query('INSERT INTO `an_quest` (`sex`) VALUES ("0") ');
			}
			$sp = mysql_query('SELECT * FROM `an_quest`');
			while( $pl = mysql_fetch_array($sp) ) {
				echo '<div><span style="width:50px;display:inline-block;">#'.$pl['id'].'</span><b><span style="width:250px;display:inline-block;">'.$pl['name'].' ('.$sx[$pl['sex']].')</span></b> &nbsp; <a href="main.php?'.$zv.'&go=3&eq='.$pl['id'].'">��������</a> <a href="main.php?'.$zv.'&go=3&del='.$pl['id'].'">�������</a></div><hr>';
			}
		}
		?>
        </td>
  </tr>
</table>
<?
	}elseif($go==2 && $u->info['admin']>0)
	{
		if(isset($_POST['q_name']))
		{
			$qd = array();
			/* Array ([q_act_atr_1] => 0 [q_act_val_1] => [q_tr_atr_1] => 0 [q_tr_val_1] => [q_ng_atr_1] => 0 [q_ng_val_1] => [q_nk_atr_NaN] => 0
			[q_nk_val_NaN] => [q_info] => test �������� [q_line1] => 1 [q_line2] => 1 [q_fast] => 1 [q_fast_city] => capitalcity [q_align1] => 1 [q_align2] => 1 [q_align3] => 1 ) */
			$qd['name'] = $_POST['q_name'];
			$qd['lvl'] = explode('-',$_POST['q_lvl']);
			$qd['info'] = $_POST['q_info'];
			if($_POST['q_line1']==1)
			{
				$qd['line'] = $_POST['q_line2'];
			}
			if($_POST['q_fast']==1)
			{
				$qd['city'] = $_POST['q_fast_city'];
				$gd['fast'] = 1;
			}
			if($_POST['align1']==1)
			{
				$qd['align'] = 1;
			}elseif($_POST['align2']==1)
			{
				$qd['align'] = 3;
			}elseif($_POST['align3']==1)
			{
				$qd['align'] = 7;
			}elseif($_POST['align4']==1)
			{
				$qd['align'] = 2;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_act_atr_'.$i]))
				{
					if($_POST['q_act_val_'.$i]!='')
					{
						$qd['act_date'] .= $_POST['q_act_atr_'.$i].':=:'.$_POST['q_act_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['act_date'] = trim($qd['act_date'],':|:');
				}
				$i++;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_tr_atr_'.$i]))
				{
					if($_POST['q_tr_val_'.$i]!='')
					{
						$qd['tr_date'] .= $_POST['q_tr_atr_'.$i].':=:'.$_POST['q_tr_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['tr_date'] = trim($qd['tr_date'],':|:');
				}
				$i++;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_ng_atr_'.$i]))
				{
					if($_POST['q_ng_val_'.$i]!='')
					{
						$qd['win_date'] .= $_POST['q_ng_atr_'.$i].':=:'.$_POST['q_ng_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['win_date'] = trim($qd['win_date'],':|:');
				}
				$i++;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_nk_atr_'.$i]))
				{
					if($_POST['q_nk_val_'.$i]!='')
					{
						$qd['lose_date'] .= $_POST['q_nk_atr_'.$i].':=:'.$_POST['q_nk_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['lose_date'] = trim($qd['lose_date'],':|:');
				}
				$i++;
			}
			mysql_query('INSERT INTO `quests` (`name`,`min_lvl`,`max_lvl`,`tr_date`,`act_date`,`win_date`,`lose_date`,`info`,`line`,`align`,`city`,`fast`) VALUES (
			"'.mysql_real_escape_string($qd['name']).'","'.mysql_real_escape_string($qd['lvl'][0]).'","'.mysql_real_escape_string($qd['lvl'][1]).'",
			"'.mysql_real_escape_string($qd['tr_date']).'","'.mysql_real_escape_string($qd['act_date']).'","'.mysql_real_escape_string($qd['win_date']).'",
			"'.mysql_real_escape_string($qd['lose_date']).'","'.mysql_real_escape_string($qd['info']).'","'.mysql_real_escape_string($qd['line']).'",
			"'.mysql_real_escape_string($qd['align']).'","'.mysql_real_escape_string($qd['city']).'","'.mysql_real_escape_string($qd['fast']).'")');
		}
?>
<script>
function nqst(){ if(document.getElementById('addNewquest').style.display == ''){ document.getElementById('addNewquest').style.display = 'none'; }else{ document.getElementById('addNewquest').style.display = ''; } }
var adds = [0,0,0,0];
function addqact()
{
	var dd = document.getElementById('qact');
	adds[0]++;
	dd.innerHTML = '�������: <select name="q_act_atr_'+adds[0]+'" id="q_act_atr_'+adds[0]+'">'+
  '<option value="0"></option>'+
  '<option value="go_loc">������� � �������</option>'+
  '<option value="go_mod">������� � ������</option>'+
  '<option value="on_itm">����� �������</option>'+
  '<option value="un_itm">����� �������</option>'+
  '<option value="use_itm">������������ �������</option>'+
  '<option value="useon_itm">������������ ������� ��</option>'+
  '<option value="dlg_nps">���������� � NPS</option>'+
  '<option value="tk_itm">�������� �������</option>'+
  '<option value="del_itm">�������� �������</option>'+
  '<option value="buy_itm">������ �������</option>'+
  '<option value="kill_bot">����� �������</option>'+
  '<option value="kill_you">����� �����</option>'+
  '<option value="kill_user">����� ������</option>'+
  '<option value="all_stats">��������� �����</option>'+
  '<option value="all_skills">��������� ������</option>'+
  '<option value="all_navik">���������� ������</option>'+
  '<option value="min_online">������� ����� � �������</option>'+
  '<option value="min_btl">�������� ����</option>'+
  '<option value="min_winbtl">�������� ���� (�����)</option>'+
  '<option value="tk_znak">�������� ������</option>'+
  '<option value="end_quests">��������� �����</option>'+
  '<option value="end_qtime">����� ���������� ������ (� �������)</option>'+
'</select>, ��������: <input style="width:100px" name="q_act_val_'+adds[0]+'" value=""><br>'+dd.innerHTML;
}
function addqtr()
{
	var dd = document.getElementById('qtr');
	adds[1]++;
	dd.innerHTML = '�������: <select name="q_tr_atr_'+adds[1]+'" id="q_tr_atr_'+adds[1]+'">'+
  '<option value="0"></option>'+
  '<option value="tr_endq">��������� ������</option>'+
  '<option value="tr_botitm">�� �������� ������ �������� (� �������)</option>'+
  '<option value="tr_winitm">����� ������ ������ ��������</option>'+
  '<option value="tr_zdr">�������� ����� ����������� (� �����)</option>'+
  '<option value="tr_tm1">������������� ������ (������)</option>'+
  '<option value="tr_tm2">������������� ������ (�����)</option>'+
  '<option value="tr_raz">������� ��� ����� ��������� �����</option>'+
  '<option value="tr_raz2">������� ������� ������ �����</option>'+
  '<option value="tr_dn">���������� � ������</option>'+
  '<option value="tr_x">���������� � ���������� X</option>'+
  '<option value="tr_y">���������� � ���������� Y</option>'+
'</select>, ��������: <input style="width:100px" name="q_tr_val_'+adds[1]+'" value=""><br>'+dd.innerHTML;
}
function addqng()
{
	var dd = document.getElementById('qng');
	adds[2]++;
	dd.innerHTML = '�������: <select name="q_ng_atr_'+adds[2]+'" id="q_ng_atr_'+adds[2]+'">'+
  '<option value="0"></option>'+
  '<option value="add_cr">�������� �������</option>'+
  '<option value="add_ecr">�������� ��������</option>'+
  '<option value="add_itm">�������� �������</option>'+
  '<option value="add_eff">�������� ������</option>'+
  '<option value="add_rep">�������� ���������</option>'+
  '<option value="add_exp">�������� �����</option>'+
'</select>, ��������: <input style="width:100px" name="q_ng_val_'+adds[2]+'" value=""><br>'+dd.innerHTML;
}
function addqnk()
{
	var dd = document.getElementById('qnk');
	adds[3]++;
	dd.innerHTML = '�������: <select name="q_nk_atr_'+adds[3]+'" id="q_nk_atr_'+adds[3]+'">'+
  '<option value="0"></option>'+
  '<option value="lst_eff">�������� ������</option>'+
'</select>, ��������: <input style="width:100px" name="q_nk_val_'+adds[3]+'" value=""><br>'+dd.innerHTML;
}
</script>
<!-- Copyright 2000-2006 Adobe Macromedia Software LLC and its licensors. All rights reserved. -->
<title>��������� ����</title>

<table width="100%">
  <tr>
    <td align="center"><h3>�������� �������</h3></td>
    <td width="150" align="right"><input type="button" value="&gt;" onclick="location='main.php?<? echo $zv; ?>';" />
      <? if($u->info['admin']>0){ ?>
      <input type="button" value="<? if($a==1){ echo 'PAL'; }else{ echo 'ARM'; } ?>" onclick="location='main.php?go=2&amp;<? echo $zv; ?>&amp;remod=<? echo $a; ?>';" />
      <? } ?>
      <? if($p['trPass']!=''){ ?>
      <input type="button" value="X" title="������� ������" onclick="location='main.php?<? echo $zv.'&rnd='.$code; ?>&amp;exitMod=1';" />
      <? } ?></td>
  </tr>
  <tr>
    <td>
    <form method="post" action="main.php?go=2&amp;<? echo $zv; ?>&amp;remod=<? echo $a; ?>">
    <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
      <!-- -->
      <tr>
        <td style="border-bottom:1px solid #CCCCCC;"><div align="left" style="margin-left:11px;">
        	<a href="javascript:void(0)" onclick="nqst()">�������� ����� �������</a>
        </div>
          <div align="left"></div></td>
        </tr>
      <tr id="addNewquest" style="display:none;">
        <td bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>������ ���������� ����� �������:</b><br />
          <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td width="200" valign="top">�������� �������</td>
              <td><input name="q_name" id="q_name" value="" size="60" maxlength="50"  /></td>
            </tr>
            <tr>
              <td valign="top">������� �������</td>
              <td><input name="q_lvl" id="q_lvl" value="0-21" size="10" maxlength="5"  /></td>
            </tr>
            <tr>
              <td valign="top">��������</td>
              <td valign="top" id="qact"><a href="javascript:void(0)" onclick="addqact()"><small>[+] ��������</small></a></td>
            </tr>
            <tr>
              <td valign="top">�������</td>
              <td valign="top" id="qtr"><a href="javascript:void(0)" onclick="addqtr()"><small>[+] ��������</small></a></td>
            </tr>
            <tr>
              <td valign="top">�������</td>
              <td valign="top" id="qng"><a href="javascript:void(0)" onclick="addqng()"><small>[+] ��������</small></a></td>
            </tr>
            <tr>
              <td valign="top">�������</td>
              <td valign="top" id="qnk"><a href="javascript:void(0)" onclick="addqnk()"><small>[+] ��������</small></a></td>
            </tr>
            <tr>
              <td valign="top">�������� �������</td>
              <td><textarea name="q_info" id="q_info" style="width:90%" rows="7"></textarea></td>
            </tr>
            <tr>
              <td align="center" valign="top" bgcolor="#CBCBCB"><input name="q_line1" type="checkbox" id="checkbox3" value="1" />
                �������� �������</td>
              <td bgcolor="#CBCBCB"><input name="q_line2" id="q_line3" value="" size="5" maxlength="3"  />
                , id ��������� ������</td>
            </tr>
            <tr>
              <td align="center" valign="top" bgcolor="#CBCBCB"><input name="q_fast" type="checkbox" id="q_fast" value="1" />
                ������� �������&nbsp;</td>
              <td bgcolor="#CBCBCB"><input name="q_fast_city" id="q_fast_city" value="capitalcity" size="50" maxlength="50"  />
                , ����� ������� ��������� ����� <small>(�������, ���� �� ���������)</small></td>
            </tr>
            <tr>
              <td align="center" valign="top" bgcolor="#CBCBCB">
              <small>
              <input name="q_align1" type="checkbox" id="q_align1" value="1" /> 
                ����,
                
                <input name="q_align2" type="checkbox" id="q_align2" value="1" />
                ����,<br /> 
                <input name="q_align3" type="checkbox" id="q_align3" value="1" /> 
                �������,
                <input name="q_align4" type="checkbox" id="q_align4" value="1" /> 
                ����
              </small>
</td>
              <td bgcolor="#CBCBCB"><input type="submit" value="�������� �������" /></td>
            </tr>
          </table></td>
      </tr>
      <!-- -->
    </table>
    </form>
    <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
      <!-- -->
      <?
	  if(isset($_GET['delq']))
	  {
		 mysql_query('UPDATE `quests` SET `delete` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($_GET['delq']).'" LIMIT 1'); 
	  }
	  $sp = mysql_query('SELECT * FROM `quests` WHERE `delete` = 0');
	  while($pl = mysql_fetch_array($sp))
	  {
	  ?>
      <tr>
        <td style="border-bottom:1px solid #CCCCCC;" width="300"><div align="left" style="margin-left:11px;"><?=$pl['name']?></div>
          <div align="left"></div></td>
        <td width="75" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><div align="center"><a href="main.php?go=2&amp;delq=<? echo $pl['id'].'&'.$zv; ?>">�������</a></div></td>
        <td style="border-bottom:1px solid #CCCCCC;"><small><b>��������:</b> <?=$pl['info']?></small></td>
      </tr>
      <? } ?>
      <!-- -->
  </table>
    </td>
  </tr>
</table>
<?
	}elseif($go==1 && $p['editAlign']==1)
	{
		if(isset($_GET['save'],$_POST['alignSave']))
		{
			//��������� ������
			$sv = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `id` = "'.mysql_real_escape_string($_POST['alignSave']).'" LIMIT 1'));
			if(isset($sv['id']) && ($sv['align'] < $u->info['align'] || $u->info['admin']>0))
			{
				$ud = '';
				$i = 0;
				while($i<count($vz_id))
				{
					if($vz_id[$i]!='editAlign' || $u->info['admin']>0)
					{
						if(isset($sv[$vz_id[$i]]))
						{
							if(isset($_POST[$vz_id[$i]]))
							{
								if($i==33)
								{
									//������ �� ������������� ������
									if($_POST['trPassText']!='')
									{
										$ud .= '`'.$vz_id[$i].'`="'.mysql_real_escape_string(md5($_POST['trPassText'])).'",';
									}
								}else{
									$ud .= '`'.$vz_id[$i].'`="1",';
								}
							}else{
								if($i==33)
								{
									//������ �� ������������� ������
									$ud .= '`'.$vz_id[$i].'`="",';
								}else{
									$ud .= '`'.$vz_id[$i].'`="0",';
								}
							}
						}
					}
					$i++;
				}
				$ud = rtrim($ud,',');
				$upd = mysql_query('UPDATE `moder` SET '.$ud.' WHERE `id` = "'.$sv['id'].'" LIMIT 1');
				if($upd)
				{
					$merror = '��������� ���� ���������';
				}else{
					$merror = '������ ����������';
				}
			}else{
				$merror = '������. � ��� ��� �������';
			}
		}
?>
<table width="100%">
  <tr>
    <td align="center"><h3>������� ����������</h3></td>
    <td width="150" align="right"><input type="button" value=">" onclick="location='main.php?<? echo $zv; ?>';" />
      <? if($u->info['admin']>0){ ?><input type="button" value="<? if($a==1){ echo 'PAL'; }else{ echo 'ARM'; } ?>" onclick="location='main.php?go=1&<? echo $zv; ?>&remod=<? echo $a; ?>';" /><? } ?><? if($p['trPass']!=''){ ?>
    <input type="button" value="X" title="������� ������" onclick="location='main.php?<? echo $zv.'&rnd='.$code; ?>&amp;exitMod=1';" /><? } ?></td>
  </tr>
  <tr>
    <td>
        <? 
		if($merror!='')
		{
			echo '<font color="red">'.$merror.'</font>';
		}
		?>
        <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
	    <?
		$sp = mysql_query('SELECT * FROM `moder` WHERE `align`<='.$u->info['align'].' && `align`>'.$a.' ORDER BY `align` DESC LIMIT 20');
		while($pl = mysql_fetch_array($sp))
		{
		?>
            <tr>
              <td style="border-bottom:1px solid #CCCCCC;" width="250"><div align="left" style="margin-left:11px;"><? echo '<img src="http://img.xcombats.com/i/align/align'.$pl['align'].'.gif"> <small><b>'.$u->mod_nm[$a][$pl['align']].'</b></small>' ?></div><div align="left"></div></td>
              <td width="50" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><div align="center"><? if($u->info['align']>$pl['align'] || $u->info['admin']>0){ ?><a href="main.php?go=1&edit=<? echo $pl['id'].'&'.$zv; ?>">���.</a><? }else{ echo '<b style="color:grey;">���.</b>'; } ?></div></td>
              <td style="border-bottom:1px solid #CCCCCC;">�����������: <? 
			  $voz = '';
			  $i = 0;
			  while($i<count($vz_id))
			  {
			  	if($pl[$vz_id[$i]]>0)
				{
					$voz .= '<b>'.$vz[$vz_id[$i]].'</b>, ';
				}
				$i++;
			  }
			  $voz = trim($voz,', ');
			  if($voz=='')
			  {
			  	$voz = '�������� ������ :-)';
			  }
			  echo '<small><font color="grey">'.$voz.'</font></small>';
			  
			   ?></td>
            </tr>
            <? if(isset($_GET['edit']) && $pl['id']==$_GET['edit']){ ?>
            <tr>
              <td valign="top" bgcolor="#F3F3F3" style="border-bottom:1px solid #CCCCCC; color:#757575;">��������� ������������:<Br /><a href="main.php?<? echo $zv; ?>&go=1" onClick="document.getElementById('saveDate').submit(); return false;">��������� ���������</a><br /><a href="main.php?<? echo $zv; ?>&go=1">������ ������</a></td>
              <td valign="top" bgcolor="#F3F3F3" style="border-bottom:1px solid #CCCCCC;"></td>
              <td valign="top" bgcolor="#F3F3F3" style="border-bottom:1px solid #CCCCCC;">
              <form id="saveDate" name="saveDate" method="post" action="main.php?<? echo $zv.'&go=1&save='.$code; ?>">
              <?
			  $voz = '';
			  $i = 0;
			  while($i<count($vz_id))
			  {
				if($vz_id[$i]!='editAlign' || $u->info['admin']>0)
				{
					if($pl[$vz_id[$i]]>0)
					{
						$voz .= '<input name="'.$vz_id[$i].'" type="checkbox" value="1" checked>';
					}else{
						$voz .= '<input name="'.$vz_id[$i].'" type="checkbox" value="1">';
					}
					$voz .= ' '.$vz[$vz_id[$i]];
					if($i==33)
					{
						$voz .= ': <input name="trPassText" value="" type="password">';
					}
					$voz .= '<br>';
				}
				$i++;
			  }
			  echo $voz;
			  ?>
              <input name="alignSave" type="hidden" id="alignSave" value="<? echo $pl['id']; ?>" />
              </form>              </td>
            </tr>
        <?
			}
		}
	    ?>
      </table>    </td>
  </tr>
</table>
<?
	}else{
?>
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
		top.chat.inObj = top.frames['main'].document.getElementById('logingo');
		top.frames['main'].document.getElementById('logingo').focus();
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
<div id="useMagic" style="display:none; position:absolute; border:solid 1px #776f59; left: 50px; top: 186px;" class="modpow">
<div class="mt" id="modtitle"></div><div class="md" id="moddata"></div></div>
<table width="100%">
  <tr>
    <td align="center">
    <? if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)){ ?>
    <h3>������ <? if($a==1){ echo '��������'; }elseif($a==3){ echo '�������'; }else{ echo '������'; } ?></h3>
    <? }else{ ?><h3>������ <? if($u->info['align']==1){ echo '�����'; }elseif($u->info['align']==3){ echo '����'; } ?></h3><? } ?>
    </td>
    <td width="150" align="right"><input type="button" value=">" onclick="location='main.php';" />
      <? if($u->info['admin']>0){ ?>
      <input type="button" value="<? if($a==1){ echo 'PAL'; }else{ echo 'ARM'; } ?>" onclick="location='main.php?<? echo $zv; ?>&amp;remod=<? echo $a; ?>';" />
      <? } ?><? if($p['trPass']!=''){ ?><input type="button" value="X" title="������� ������" onclick="location='main.php?<? echo $zv.'&rnd='.$code; ?>&amp;exitMod=1';" /><? } ?></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
  </tr>
</table>
<form action="main.php?<? echo $zv.'&rnd='.$code; ?>" method="post" name="F1" id="F1">
  <table width="100%">
    <tr>
      <td align="center"></td>
      <td align="right"></td>
      <td valign="top" align="right"></td>
    </tr>
  </table>
  <?
  $uer = '';
  //���������� ��������
	if(isset($_GET['usemod']))
	{
		$srok = array(5=>'5 �����',15=>'15 �����',30=>'30 �����',60=>'���� ���',180=>'��� ����',360=>'����� �����',720=>'���������� �����',1440=>'���� �����',4320=>'���� �����');
		$srokt = array(1=>'1 ����',3=>'3 ���',7=>'������',14=>'2 ������',30=>'�����',60=>'2 ������',365=>'���',24=>'���������',6=>'�����');

		//���������� ��������
		if(isset($_POST['usevampir']))
		{
			include('moder/usevampir.php');	
		}elseif(isset($_POST['usem1']))
		{
			include('moder/usem1.php');					
		}elseif(isset($_POST['usem2']))
		{
			include('moder/usem2.php');					
		}elseif(isset($_POST['usesm']))
		{
			include('moder/usesm.php');	
		}elseif(isset($_POST['useban']))
		{
			include('moder/useban.php');
		}elseif(isset($_POST['useunban']))
		{
			include('moder/useunban.php');
		}elseif(isset($_POST['usehaos']))
		{
			include('moder/usehaos.php');
		}elseif(isset($_POST['useshaos']))
		{
			include('moder/useshaos.php');
		}elseif(isset($_POST['teleport'])){
			include('moder/teleport.php');
		}elseif(isset($_POST['teleport-cometome'])){
			include('moder/teleport-cometome.php');
		}elseif(isset($_POST['usedeletinfo']))
		{
			include('moder/usedeletinfo.php');
		}elseif(isset($_POST['unusedeletinfo']))
		{
			include('moder/unusedeletinfo.php');
		}elseif(isset($_POST['unmoder']))
		{
			include('moder/unmoder.php');
		}elseif(isset($_POST['gomoder']))
		{
			include('moder/moder.php');
		}elseif(isset($_POST['use_carcer'])){
		    include('moder/use_carcer.php');
		}elseif(isset($_POST['v_carcer'])){
		    include('moder/v_carcer.php');
		}elseif(isset($_POST['usepro'])){
		    include('moder/usepro.php');
		}elseif(isset($_POST['usemarry'])){
		    include('moder/usemarry.php');
		}elseif(isset($_POST['useunmarry'])){
		    include('moder/useunmarry.php');
		}elseif(isset($_POST['usenoper'])) {
			include('moder/usenoper.php');
		}elseif(isset($_POST['useunnoper'])) {
			include('moder/useunnoper.php');
		}elseif(isset($_POST['usenoper2'])) {
			include('moder/usenoper2.php');
		}elseif(isset($_POST['useunnoper2'])) {
			include('moder/useunnoper2.php');
		}elseif(isset($_POST['useunalign'])) {
			include('moder/useunalign.php');
		}elseif(isset($_POST['usehpa'])) {
			include('moder/usehpa.php');
		}elseif(isset($_POST['usempa'])) {
			include('moder/usempa.php');
		}elseif(isset($_POST['usenevid'])) {
			include('moder/usenevid.php');
		}elseif(isset($_POST['usepro2'])) {
			include('moder/usepro2.php');
		}elseif(isset($_POST['useunfight'])) {
			include('moder/useunfight.php');
		}elseif(isset($_POST['usesex'])) {
			include('moder/usesex.php');
		}elseif(isset($_POST['uselogin'])) {
			include('moder/uselogin.php');
		}elseif(isset($_POST['usealign7'])) {
			include('moder/usealign7.php');
		}elseif(isset($_POST['usealign3'])) {
			include('moder/usealign3.php');
		}elseif(isset($_POST['usealign1'])) {
			include('moder/usealign1.php');
		}elseif(isset($_POST['useuntravm'])) {
			include('moder/useuntravm.php');
		}elseif(isset($_POST['useatack'])) {
			include('moder/useatack.php');
		}elseif(isset($_POST['100kexp'])) {
			include('moder/100kexp.php');
		}
	}
	
	if(isset($_POST['use_itm_']) && $u->info['admin'] > 0 && $u->info['id'] != 2332207) {
    $usr = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['log_itm_']).'" LIMIT 1'));
    $giv_itm = mysql_fetch_array(mysql_query("SELECT * FROM `items_main` WHERE `id` = '$_POST[itm_id]'"));
    if($giv_itm['id'] <= 0) { $uer = "���� ����� ����"; }
    if(!$usr['id']) { $uer = "��������  $_POST[log_itm] �� ������."; }
    if($giv_itm['id'] > 0 && $usr['id'] > 0) { 
        $u->addItem($giv_itm['id'], $usr['id']); 
        $uer = "��������� $_POST[log_itm] ������ ���� $giv_itm[name]."; 
        $rtxt = $rang.' &quot;'.$u->info['login'].'&quot; �����'.$sx.'  ��������� &quot;'.$user_teleport['login'].'&quot; ���� &quot;<b>'.$giv_itm['name'].'</b>&quot;.';  
    }
}
	
	if($u->info['admin'] > 0 || $u->info['align'] == 1.99 ) {
		echo '<hr><b>�����-����������: </b>'.
				'<input onclick="location.href=\'main.php?'.$zv.'&blockip_list=1\'" class="btn1" type="button" value="�������� ��������������� IP"> '.
				'<hr>';
		if(isset($_GET['block_ip'])) {
			$_GET['block_ip'] = htmlspecialchars($_GET['block_ip']);
			$blockip = mysql_fetch_array(mysql_query('SELECT * FROM `block_ip` WHERE `ip` = "'.mysql_real_escape_string($_GET['block_ip']).'" LIMIT 1'));
			if(isset($blockip['id'])) {
				//��� ����
				echo '<font color="red"><b>IP% '.$_GET['block_ip'].' ������� ������������! (�����)</b></font><br>';
			}else{
				//���������
				echo '<font color="red"><b>IP% '.$_GET['block_ip'].' ������� ������������!</b></font><br>';
				mysql_query('INSERT INTO `block_ip` (`uid`,`time`,`ip`) VALUES (
					"'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($_GET['block_ip']).'"
				)');
			}
		}elseif(isset($_GET['unblock_ip'])){
			$_GET['unblock_ip'] = htmlspecialchars($_GET['unblock_ip']);
			$blockip = mysql_fetch_array(mysql_query('SELECT * FROM `block_ip` WHERE `ip` = "'.mysql_real_escape_string($_GET['unblock_ip']).'" LIMIT 1'));
			if(isset($blockip['id'])) {
				//�������
				echo '<font color="green"><b>IP% '.$_GET['unblock_ip'].' ������� �������������!</b></font><br>';
				mysql_query('DELETE FROM `block_ip` WHERE `ip` = "'.mysql_real_escape_string($blockip['ip']).'"');
			}else{
				//��� �������
				echo '<font color="green"><b>IP% '.$_GET['unblock_ip'].' ������� �������������! (�����)</b></font><br>';
			}
		}
			if(isset($_GET['blockip_list'])) {
				$plbipl = '';
				$spbip = mysql_query('SELECT * FROM `block_ip`');
				while($plbip = mysql_fetch_array($spbip)) {
					$plbipl .= '<span class="date1">'.date('d.m.Y H:i',$plbip['time']) . '</span> - ' . $plbip['ip'] . ' ('.$u->microLogin($plbip['uid'],1).') <input onclick="location.href=\'main.php?'.$zv.'&unblock_ip='.htmlspecialchars($plbip['ip']).'&blockip_list=1\'" type="button" value="&nbsp; - &nbsp;"><br>';
				}
				if($plbipl!='') {
					echo '<b>������ ��������������� IP:</b><br>'.$plbipl;
				}else{
					echo '<b>������ ��������������� IP:</b> <i>������ ����</i>';
				}
				echo '<hr>';
			}
	}
	
	echo '<font color="red">'.$uer.'</font>';
			//������ �������, �������� ����!)
		?>
<br />
<div style="padding-left:20px;">
<h4>�����������</h4>

<? 	if($u->info['align']>=3 && $u->info['align']<4) { ?>
<a href="#" onClick="openMod('<b>&quot;���� �������&quot;</b>','<form action=\'main.php?<?=$zv?>&usemod=<? echo $code; ?>\' method=\'post\'>����� ������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/vampir.gif" title="�������" /></a>
<? } ?>
<? if( $p['heal'] == 1 || $u->info['admin'] > 0) { ?>
<a href="#" onClick="openMod('<b>&quot;������������ �������� ���������&quot;</b>','<form action=\'main.php?<?=$zv?>&usehpa=1&usemod=<? echo $code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:140px;\' id=\'logingo\' name=\'logingo\'>&nbsp;<input style=\'float:right;\' type=\'submit\' name=\'usehpa\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/cureHP120.gif" title="������������ �������� ���������" /></a>
<a href="#" onClick="openMod('<b>&quot;������������ ���� ���������&quot;</b>','<form action=\'main.php?<?=$zv?>&usempa=1&usemod=<? echo $code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:140px;\' id=\'logingo\' name=\'logingo\'>&nbsp;<input style=\'float:right;\' type=\'submit\' name=\'usempa\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/cureMana1000.gif" title="������������ ���� ���������" /></a>
<? } ?>
	<? if( $p['invis'] == 1 || $u->info['admin'] > 0) { ?>
	<? if($u->info['invis'] != 1 && $u->info['invis'] < time()) { ?>
   		<a href="#" onClick="openMod('<b>&quot;�������� ���������&quot;</b>','<form action=\'main.php?<?=$zv?>&usenevid=1&usemod=<? echo $code; ?>\' method=\'post\'><input style=\'float:right;\' type=\'submit\' name=\'usenevid\' value=\'�������� ���������\'></form>');"><img src="http://img.xcombats.com/i/items/pal_buttona.gif" title="�������� ���������" /></a>
    <? }else{ ?>
    	<a href="#" onClick="openMod('<b>&quot;��������� ���������&quot;</b>','<form action=\'main.php?<?=$zv?>&usenevid=1&usemod=<? echo $code; ?>\' method=\'post\'><input style=\'float:right;\' type=\'submit\' name=\'usenevid\' value=\'��������� ���������\'></form>');"><img src="http://img.xcombats.com/i/items/pal_buttonm.gif" title="��������� ���������" /></a>
    <? } } ?>
   <? if( $p['useuntravm'] == 1 ) { ?>
   <a href="#" onclick="openMod('&lt;b&gt;������� ������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'useuntravm\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/cure3.gif" title="������� ������" /></a>
   <? } ?>
   <? if( $p['attack'] == 1 ) { ?>
   <a href="#" onclick="openMod('&lt;b&gt;������� �� ���������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'useatack\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/pal_button8.gif" title="���������" /></a>
   <? } ?>
</div>
<?	
	if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4))
	{
  ?>
  <div style="padding:10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>��������/����� ��������</h4>
  <table width="100%">
   <tr>
     <td>
		<? if($u->info['admin']>0){ echo '<a href="main.php?'.$zv.'&go=2"><img width="40" height="25" title="������������� ������, ������� � ��������� ���������" src="http://img.xcombats.com/editor2.gif"></a> <a href="main.php?'.$zv.'&go=3"><img width="40" height="25" title="�������������� ������� ��� ����������" src="http://img.xcombats.com/editor2.gif"></a>'; } ?>
		<? if($p['editAlign']==1){ echo '<a href="main.php?'.$zv.'&go=1"><img title="������������� ����������� �����������" src="http://img.xcombats.com/editor.gif"></a>'; } ?>
        &nbsp;&nbsp;&nbsp;
		<? if($p['m1']==1 || $p['citym1']==1){ ?> <a href="#" onClick="openMod('<b>�������� ��������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'5\'>5 �����</option><option value=\'15\'>15 �����</option><option value=\'30\'>30 �����</option><option value=\'60\'>1 ���</option><option value=\'180\'>3 ����</option><option value=\'360\'>6 �����</option><option value=\'720\'>12 �����</option><option value=\'1440\'>�����</option></select> <input type=\'submit\' name=\'usem1\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/sleep.gif" title="�������� ��������" /></a> <? } ?>
        <? if($p['m2']==1 || $p['citym2']==1){ ?> <a href="#" onClick="openMod('<b>�������� ��������� ��������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'30\'>30 �����</option><option value=\'60\'>1 ���</option><option value=\'180\'>3 ����</option><option value=\'360\'>6 �����</option><option value=\'720\'>12 �����</option><option value=\'1440\'>�����</option></select> <input type=\'submit\' name=\'usem2\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/sleepf.gif" title="�������� ��������� ��������" /></a> <? } ?>
        <? if($p['sm1']==1 || $p['sm2']==1 || $p['citysm1']==1 || $p['citysm2']==1){ ?>
        <a href="#" onClick="openMod('<b>�������� ��������� ��������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'1\'>���</option><option value=\'2\'>�����</option><option value=\'3\'>��� + �����</option></select> <input type=\'submit\' name=\'usesm\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/sleep_off.gif" title="����� �������� ��������" /></a> <? } ?>
        &nbsp;&nbsp;&nbsp;
		<? if($p['banned']==1 || $p['ban0']==1){ ?> <a href="#" onClick="openMod('<b>�������� ������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useban\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/pal_button6.gif" title="�������� ������" /></a> <? } ?>
        <? if($p['unbanned']==1){ ?> <a href="#" onClick="openMod('<b>����� �������� ������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useunban\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/pal_button7.gif" title="����� �������� ������" /></a> <? } ?>
		&nbsp;&nbsp;&nbsp;
		<? if($p['haos']==1){ ?> <a href="#" onClick="openMod('<b>��������� � ����</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'7\'>������</option><option value=\'14\'>2 ������</option><option value=\'30\'>�����</option><option value=\'60\'>2 ������</option><? if($p['haosInf']==1){ ?><option value=\'1\'>���������</option><? } ?> <input type=\'submit\' name=\'usehaos\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/pal_button4.gif" title="��������� � ����" /></a> <? } ?>
        <? if($p['shaos']==1){ ?> <a href="#" onClick="openMod('<b>��������� �� �����</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useshaos\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/pal_button5.gif" title="��������� �� �����" /></a> <? } ?>
        &nbsp;&nbsp;&nbsp;
		<? if($p['deletInfo']==1){ ?> <a href="#" onClick="openMod('<b>�������������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ��������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'7\'>������</option><option value=\'14\'>2 ������</option><option value=\'30\'>�����</option><option value=\'60\'>2 ������</option><option value=\'1\'>���������</option> <input type=\'submit\' name=\'usedeletinfo\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/cui.gif" title="�������������" /></a>
                                      <a href="#" onClick="openMod('<b>����� �������� �������������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'unusedeletinfo\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/uncui.gif" title="����� �������������" /></a> <? } ?>
        &nbsp;&nbsp;&nbsp;
		<? if($p['priemIskl']==1 && $a==1){ ?>
        <a href="#" onClick="openMod('<b>������� � ��</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>������: &nbsp; <select style=\'margin-left:2px;\' name=\'zvanie\'><option value=\'1.1\'> ������� ����������</option><option value=\'1.4\'>���������� �������</option><option value=\'1.5\'>������� ��������� ������</option><option value=\'1.6\'>����������</option><option value=\'1.7\'>������� �������� ����</option><option value=\'1.75\'>�������-���������</option><option value=\'1.9\'>������� ����</option><option value=\'1.91\'>������� ������� ����</option><option value=\'1.92\'>������� ������</option><input type=\'submit\' name=\'gomoder\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/pal.gif" title="������� � �� (��������)" /></a>
        <a href="#" onClick="openMod('<b>������� �� ��</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'unmoder\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/unpal.gif" title="������� �� ��" /></a> <? } ?>  
        <? if($p['priemIskl']==1 && $a==3){ ?>
        <a href="#" onClick="openMod('<b>������� � ������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>������: &nbsp; <select style=\'margin-left:2px;\' name=\'zvanie\'><option value=\'3.01\'> ������-���������</option><option value=\'3.05\'>������-�����������</option><option value=\'3.06\'>��������</option><option value=\'3.07\'>������-������</option><option value=\'3.075\'>������-���������</option><option value=\'3.09\'>������-�����</option><option value=\'3.091\'>������-�������</option><input type=\'submit\' name=\'gomoder\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/palt.gif" title="������� � ������ (��������)" /></a>
        <a href="#" onClick="openMod('<b>������� �� ������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'unmoder\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/unpalt.gif" title="������� �� ��" /></a> <? } ?>  
		&nbsp;&nbsp;&nbsp;
		<? if($p['proverka']==1){ ?> <a href="#" onclick="openMod('<b>�������� �� �������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><input type=\'submit\' name=\'usepro\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/check.gif" title="�������� �� �������" /></a> <? } ?>
		<? if($p['proverka']==1){ ?> <a href="#" onclick="openMod('<b>����� �������� �� �������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><input type=\'submit\' name=\'usepro2\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/pal_buttont.gif" title="����� �������� �� �������" /></a> <? } ?>
        &nbsp;&nbsp;&nbsp;
		<? if($p['proverka']==1){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;������ �������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'usenoper\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/mod/magic2.gif" title="������ �� ��������" /></a>
        <? } ?>
        <? if($p['proverka']==1){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;����� ������ �������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'useunnoper\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/mod/magic9.gif" title="����� ������ �� ��������" /></a>
        <? } ?>
        <? if($p['proverka']==1){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;������ ������ �������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'usenoper2\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/mod/magic2.gif" title="������ ������ �� ��������" /></a>
        <? } ?>
        <? if($p['proverka']==1){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;����� ������ ������ �������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'useunnoper2\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/mod/magic9.gif" title="����� ������ ������ �� ��������" /></a>
        <? } ?>	
		<?
		if( $u->info['admin'] > 0 ) {
			if($p['usealign3']==1){ ?>
			&nbsp;&nbsp;&nbsp; <a href="#" onclick="openMod('&lt;b&gt;������ ������ ����������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'usealign3\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/pal_button[dark].gif" title="������ ������ ����������" /></a>
			<? } if($p['usealign1']==1){ ?>
			<a href="#" onclick="openMod('&lt;b&gt;������ ������� ����������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'usealign1\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/pal_button1.gif" title="������ ������� ����������" /></a>
			<? } if($p['usealign7']==1){ ?>
			<a href="#" onclick="openMod('&lt;b&gt;������ ����������� ����������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'usealign7\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/palbuttonneutralsv3.gif" title="������ ����������� ����������" /></a>
			<? }
		}
		?>
		<? if($p['proverka']==1){ ?>
        &nbsp;&nbsp;&nbsp; <a href="#" onclick="openMod('&lt;b&gt;����� ����������\����&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'useunalign\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/palbuttondarkhc1.gif" title="����� ����������\����" /></a>
		<? } ?>
		
        
        &nbsp;&nbsp;&nbsp;
        <? if($p['unbtl']==1){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;�������� �� ��������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'useunfight\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/pal_button[battle_end].gif" title="�������� �� ��������" /></a>
        <? } if($p['sex']==1){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;������� ��� ���������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;&lt;input type=\'submit\' name=\'usesex\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/male.png" title="������� ��� ���������" /></a>
        <? } if($p['nick']==1){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;������� ����� ���������&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;<br>����� �����: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo2\' name=\'logingo2\'&gt;&lt;input type=\'submit\' name=\'uselogin\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/nick.gif" title="������� ����� ���������" /></a>
        <? } if($u->info['admin'] > 0){ ?>
        <a href="#" onclick="openMod('&lt;b&gt;�������� �����&lt;/b&gt;','&lt;form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'&gt;����� ���������: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'&gt;<br>���������� ����: &lt;input type=\'text\' style=\'width:144px;\' id=\'logingo2\' name=\'logingo2\'&gt;&lt;input type=\'submit\' name=\'100kexp\' value=\'���-��\'&gt;&lt;/form&gt;');"><img src="http://img.xcombats.com/i/items/100kexp.gif" title="�������� �����" /></a>
        <? } ?> 
        &nbsp;&nbsp;&nbsp;     
		<? if($p['zatoch']==1){ ?> <a href="#"  onClick="openMod('<b>��������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ���������: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'1\'>1 ����</option><option value=\'3\'>3 ���</option><option value=\'7\'>������</option><option value=\'14\'>14 ����</option><option value=\'30\'>30 ����</option><option value=\'365\'>365 ����</option><option value=\'24\'>���������</option><option value=\'6\'>�����</option><input type=\'submit\' name=\'use_carcer\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/jail.gif" title="���������" /></a> <? } ?>
        <? if($p['szatoch']==1){ ?> <a href="#" onClick="openMod('<b>��������� �� ���������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'v_carcer\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/jail_off.gif" title="��������� �� ���������" /></a> <? } ?>
        &nbsp;&nbsp;&nbsp;
		<? if($p['marry']==1){ ?>
        <a href="#" onclick="openMod('<b>�������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo2\' name=\'logingo2\'><br><input type=\'submit\' name=\'usemarry\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/marry.gif" title="����" /></a>
        <a href="#" onclick="openMod('<b>����������� ����</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><input type=\'submit\' name=\'useunmarry\' value=\'���-��\'></form>');"><img src="http://img.xcombats.com/i/items/unmarry.gif" title="����������� ����" /></a>
		<? } ?>
        &nbsp; &nbsp;<? if($u->info['admin']>0){ ?> <a onClick="openMod('<b>������������</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\' value=\'<? echo $u->info['login']; ?>\'><br>�����: &nbsp; <select style=\'margin-left:2px;\' name=\'city\'><option value=\'capitalcity\'>capitalcity</option><option value=\'angelscity\'>angelscity</option><option value=\'demonscity\'>demonscity</option><option value=\'devilscity\'>devilscity</option><option value=\'suncity\'>suncity</option><option value=\'emeraldscity\'>emeraldscity</option><option value=\'sandcity\'>sandcity</option><option value=\'mooncity\'>mooncity</option><option value=\'eastcity\'>eastcity</option><option value=\'abandonedplain\'>abandonedplain</option><option value=\'dreamscity\'>dreamscity</option><option value=\'lowcity\'>devilscity</option><option value=\'oldcity\'>devilscity</option><option value=\'newcapitalcity\'>newcapital</option></select> <input type=\'submit\' name=\'teleport\' value=\'���-��\'></form>');" href="#"><img src="http://img.xcombats.com/i/items/teleport.gif" title="������������" /></a>
		     <a onClick="openMod('<b>��������� ������ � ����</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ���������: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\' value=\'\'><br>�����: &nbsp; <select style=\'margin-left:2px;\' name=\'cometome\'><option value=\'to-room\'>� ����</option><option value=\'to-fight\'>� ���� � � ���</option><option value=\'to-dungeon\'>� ���� � ������</option></select> <input type=\'submit\' name=\'teleport-cometome\' value=\'���-��\'></form>');" href="#"><img src="http://img.xcombats.com/i/items/teleport-cometome.gif" title="��������� ������ � ����" /></a>
		     &nbsp; &nbsp;
		     <a href="#" onclick="openMod('<center><b>������ ���� �� Id</b></center>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>����� ��������� : <input type=\'text\' style=\'width:144px;\' id=\'log_itm_\' name=\'log_itm_\'><br />Id ���� : &nbsp; <input type=\'text\' name=\'itm_id\' /><br /><center><input type=\'submit\' name=\'use_itm_\' value=\'����\'></center></form>');"><img src="http://img.xcombats.com/i/items/bad_present_dfighter.gif" title="������ ������" /></a>
	     <? } ?></td>
   </tr>
  </table>
  </div>
  <? }
  if($p['seeld']==1) {
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>��������� �� ����� ip-������</h4>  
  ������� ip-�����  <input name="loginLD51" type="text" id="loginLD51" size="30" /> <input type="submit" name="pometka51" id="pometka51" value="��������" />
  </div>
<?
  if(isset($_POST['pometka51'])) {
	 $sp = mysql_query('SELECT * FROM `logs_auth` WHERE `ip` = "'.mysql_real_escape_string($_POST['loginLD51']).'" AND `type` != 3 GROUP BY `uid`'); 
	 $i = 1;
	 $r = '';
	 $ursz = array();
	 while($pl = mysql_fetch_array($sp)) {
		 $tst = mysql_fetch_array(mysql_query('SELECT `id`,`admin`,`no_ip` FROM `users` WHERE `no_ip` != "" AND `id` = "'.$pl['uid'].'" LIMIT 1'));
		 if(isset($tst['id']) && $tst['admin'] == 0 && ($tst['no_ip'] == 0 || $tst['no_ip'] == '')) {
			 if(!isset($ursz[$pl['uid']])) {
				$ursz[$pl['uid']] = $u->microLogin($pl['uid'],1); 
			 }
			 $de = mysql_fetch_array(mysql_query('SELECT min(`time`),max(`time`) FROM `logs_auth` WHERE `uid` = "'.mysql_real_escape_string($pl['uid']).'" GROUP BY `uid` LIMIT 1'));
			 $r .= '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">';
			 $r .= '<span style="display:inline-block;width:30px">'.$i.'.</span> <span style="display:inline-block;width:250px">'.$ursz[$pl['uid']].'</span>';
			 
			 $r .= ' &nbsp; <small>(������ �����������: '.date('d.m.Y H:i',$de[0]).' - '.date('d.m.Y H:i',$de[1]).')</small>';
			 
			 $r .= '</div>';
			 $i++;
		 }
	 }
	 
	 if( $u->info['admin'] == 0 && $u->info['align'] != 1.99 ) {
	 	echo '&nbsp;&nbsp; <font color="red">������ ���������� � ip-������:<b>'.$_POST['loginLD51'].'</b></font><br>';
	 }else{
		$block = mysql_fetch_array(mysql_query('SELECT * FROM `block_ip` WHERE `ip` = "'.mysql_real_escape_string($_POST['loginLD51']).'" LIMIT 1')); 
	 	if(!isset($block['id'])) {
			echo '&nbsp;&nbsp; <font color="green">������ ���������� � ip-������:<b>'.$_POST['loginLD51'].'</b></font>';
			echo ' <input onclick="location.href=\'main.php?'.$zv.'&block_ip='.htmlspecialchars($_POST['loginLD51']).'\'" type="button" value="������������� IP">';
			echo '<br>';
		}else{
			echo '&nbsp;&nbsp; <font color="red">������ ���������� � ip-������:<b>'.$_POST['loginLD51'].'</b></font>';
			echo ' <input onclick="location.href=\'main.php?'.$zv.'&unblock_ip='.htmlspecialchars($_POST['loginLD51']).'\'" type="button" value="�������������� IP">';
			echo '<br>';
		}
	 }
	 
	 
	 if($r == '') {
		 echo '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">��������� � ������ ip-������� �� �������</div>';
	 }else{
		 echo $r;
	 }	 
	 unset($r);
  }
  
  }
  if($u->info['admin'] > 0) {
	  
			$types = array(
				1  => array('�����',120,220,100),
				2  => array('�������� (�����)',120,40,15),
				3  => array('�������� (������)',120,20,5),
				4  => array('����',60,60,25),
				5  => array('������',60,40,25),
				6  => array('����� ����',60,60,25),
				7  => array('������ ����',60,60,25),
				8  => array('�����',60,80,25),
				9  => array('����',60,40,25),
				10 => array('�������',60,40,25),
				11 => array('������',60,80,25),
				12 => array('��������',60,40,25),
				13 => array('������ �1',20,20,10),
				14 => array('�����',60,20,25),
				15 => array('������',60,20,25),						
				16 => array('�������� ��� ���������� � ���������',244,287,5),						
				17 => array('������ �2',20,20,10),
				18 => array('������ �3',20,20,10)					
			);
	  
	  if(isset($_GET['grood_img'])) {
		  
		  $imgid = round((int)$_GET['grood_img']);
		  if(mysql_query('UPDATE `reimage` SET `good` = "'.$u->info['id'].'" WHERE `id` = "'.mysql_real_escape_string($imgid).'" AND `good` = "0" AND `bad` = "0" LIMIT 1')) {
			  //��������� �����������			  
			  $vr = mysql_fetch_array(mysql_query('SELECT * FROM `reimage` WHERE `id` = "'.mysql_real_escape_string($imgid).'" LIMIT 1'));
			  $vr['format'] = explode('.',$vr['src']);
			  $vr['format'] = $vr['format'][2];
			  copy('clan_prw/'.$vr['src'],'../img.xcombats.com/rimg/r'.$vr['id'].'.'.$vr['format']);
			  mysql_query('UPDATE `reimage` SET `format` = "'.$vr['format'].'" WHERE `id` = "'.mysql_real_escape_string($imgid).'" LIMIT 1');
			  
		 	  if($vr['clan'] == 0) {
				//���������� ��������
		  		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
		  		'1','capitalcity','0','','".$vr['login']."','<font color=red>��������!</font> ".date("d.m.y H:i")." ���������� �� �������������: \'��� �������� ����������� -".$types[$vr['type']][0]."-, ���������� ����������� �������� � ���������, � ������� &quot;�������&quot;\'.','-1','5','0')");
			  }else{
				//���������� ��������
		  		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
		  		'1','capitalcity','0','','".$vr['login']."','<font color=red>��������!</font> ".date("d.m.y H:i")." ���������� �� �������������: \'��� �������� �������� ����������� -".$types[$vr['type']][0]."-, ���������� ����������� �������� � ���������, � ������� &quot;�������&quot;\'.','-1','5','0')");
			  }
		  }
		  
	  }elseif(isset($_GET['bad_img'])) {
		  
		  $imgid = round((int)$_GET['bad_img']);
		  if(mysql_query('UPDATE `reimage` SET `bad` = "'.$u->info['id'].'" WHERE `id` = "'.mysql_real_escape_string($imgid).'" AND `good` = "0" AND `bad` = "0" LIMIT 1')) {
			  //���������� 90% ���. �� �����
			  $vr = mysql_fetch_array(mysql_query('SELECT * FROM `reimage` WHERE `id` = "'.mysql_real_escape_string($imgid).'" LIMIT 1'));
			  $vr['money2'] = round($vr['money2']/100*9);
			  
			  if($vr['clan'] > 0) {
				  //������� ��� �����				  
				  mysql_query('UPDATE `clan` SET `money2` = `money2` + '.$vr['money2'].' WHERE `id` = "'.$vr['clan'].'" LIMIT 1');
		 			//���������� ��������
		  			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
		  			'1','capitalcity','0','','".$vr['login']."','<font color=red>��������!</font> ".date("d.m.y H:i")." ���������� �� �������������: \'��� ���� �������� � ����������� ��������� ����������� -".$types[$vr['type']][0]."- , ".$vr['money2']." ���. ���� ���������� � ����� �����\'.','-1','5','0')");

			  }else{
				  //������� ��� ������ � ����
				  $bnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$vr['uid'].'" AND `block` = "0" ORDER BY `id` DESC LIMIT 1'));
				  if(isset($bnk['id'])) {
				  	mysql_query('UPDATE `bank` SET `money2` = `money2` + '.$vr['money2'].' WHERE `id` = "'.$bnk['id'].'" LIMIT 1');
				  }
		 			//���������� ��������
		  			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
		  			'1','capitalcity','0','','".$vr['login']."','<font color=red>��������!</font> ".date("d.m.y H:i")." ���������� �� �������������: \'��� ���� �������� � ����������� ����������� -".$types[$vr['type']][0]."- , ".$vr['money2']." ���. ���� ���������� �� ��� ���������� ���� �".(0+$bnk['id'])."\'.','-1','5','0')");
			  
			  }
		  }
		  
	  }
	  
	  $zvr = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `reimage` WHERE `good` = "0"'));
	  if($zvr[0] > 0) {
		  		  
	?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <div style="padding:10px;"><b>������ �� ����������� ����������� ��� ���������:</b> &nbsp; <?
  
  ?>
  </div>
  <script>
  function imresize(e,h,w) {
	 if($(e).height() == 20) {		 
		  $(e).animate({'height':h+'px'},100,null,function(){
			if($(e).width() != w) {
			 	$(e).css({'border-color':'red'});
		 	}else{
				$(e).css({'border-color':'green'}); 
		 	}	  
		  }); 
	 }else{		 
		 $(e).animate({'height':'20px'},100);
		 $(e).css({'border-color':'blue'});	
		 $(e).width(false);	 
	 }
  }
  </script>
  <?
  			
			$sp = mysql_query('SELECT * FROM `reimage` WHERE `good` = "0" AND `bad` = "0" ORDER BY `id` ASC LIMIT 10');
			$i = 1;
			
			$va = array('���','��');
			
			$rt = '';
			while($pl = mysql_fetch_array($sp)) {
				if($pl['bag'] > 0) {
					$rt .= '<font color=red><b>(!)</b>';
				}
				
				$plcln = 0;
				if($pl['clan'] > 0) {
					$plcln = 1;
				}
				
				$rt .= '<div style="border-top:1px solid grey;padding:5px;">'.$i.'. <span class="date1">'.date('d.m.y H:i',$pl['time']).'</span> <b>'.$u->microLogin($pl['uid'],1).'</b> , &quot;'.$types[$pl['type']][0].'&quot; , ��������: <b>'.$va[$pl['animation']].'</b> , ����������� ��� �����: <b>'.$va[$plcln].'</b> , <img onclick="imresize(this,'.$types[$pl['type']][2].','.$types[$pl['type']][1].');" style="border:1px solid blue;cursor:pointer;" src="/clan_prw/'.$pl['src'].'" height="20">';
				
				$rt .= ' <input onclick="location.href=\'main.php?admin=1&grood_img='.$pl['id'].'\'" type="button" value="�������" style="background:#E2EDD8"> <input type="button" onclick="location.href=\'main.php?admin=1&bad_img='.$pl['id'].'\'" style="background:#FCC9CA" value="��������"> <br>';
				
				$rt .= '</div>';
				
				if($pl['bag'] > 0) {
					$rt .= '</font>';
				}
				$i++;
			}
			echo $rt;
			
  ?>
  </div>
<? 
	  }
	  
	  $zvr = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `_clan` WHERE `admin_time` = "0"'));
	  if($zvr[0] > 0) {
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <div style="padding:10px;"><b>������ �� ����������� ������:</b> &nbsp; <?
  if(isset($_GET['goodClan'])) {
	 //����������
	 $cl = mysql_fetch_array(mysql_query('SELECT * FROM `_clan` WHERE `admin_time` = "0" AND `id` = "'.mysql_real_escape_string($_GET['goodClan']).'" LIMIT 1')); 
	 if(isset($cl['id'])) {
		 $pu = mysql_fetch_array(mysql_query('SELECT `id`,`city`,`room`,`clan`,`login`,`align`,`level`,`sex`,`money`,`banned` FROM `users` WHERE `id` = "'.mysql_real_escape_string($cl['uid']).'" LIMIT 1'));
		 $tc = mysql_fetch_array('SELECT `id`,`name` FROM `clan` WHERE `name` = "'.mysql_real_escape_string($cl['name']).'" OR `name` = "'.mysql_real_escape_string($cl['name2']).'" OR `name_mini` = "'.mysql_real_escape_string($cl['name']).'" OR `name_mini` = "'.mysql_real_escape_string($cl['name2']).'" OR `name_rus` = "'.mysql_real_escape_string($cl['name']).'" OR `name_rus` = "'.mysql_real_escape_string($cl['name2']).'" LIMIT 1');
		 if(!isset($pu['id'])) {
			 echo '<font color=red><b>�������� ����������� � ���� ����� ����� �� ������, id '.$cl['uid'].'</b></font><br>';
		 }elseif($pu['clan'] > 0 || $pu['align'] > 0 || $pu['banned'] > 0) {
			 echo '<font color=red><b>�������� ����������� � ���� ����� ����� ��� ��������� � �����, ���� ����� ����������, ���� ������������</b></font><br>';
		 }elseif($u->testAlign( $cl['align'] , $pu['id'] ) == 0 ) {
			 echo '<font color=red><b>�������� ����������� � ���� ����� ����� �� ����� ��������� ���� � ������ �����������!</b></font><br>';
		 }elseif(isset($tc['id'])) {
			 echo '<font color=red><b>������ ���� ��� ��������������� �����, ����� �'.$tc['id'].' ('.$tc['name'].').</b></font><br>';
		 }else{
			 mysql_query('UPDATE `_clan` SET `admin_time` = "'.time().'",`admin_ok` = "'.$u->info['id'].'" WHERE `id` = "'.$cl['id'].'" LIMIT 1');
			 //��������� ����������� � img.xcombats.com/i/clan/{name}.gif / {name}_big.gif / {id}.gif / {id}.gif			 
			 //��������� ������
			 if(copy('clan_prw/'.$cl['img1'],'../img.xcombats.com/i/clan/'.$cl['name2'].'.gif')) {
				 $ins = mysql_query('INSERT INTO `clan` (`name`,`name_rus`,`name_mini`,`site`,`align`,`time_reg`) VALUES (
					"'.$cl['name2'].'",
					"'.$cl['name'].'",
					"'.$cl['name2'].'",
					"'.str_replace('http://','',$cl['site']).'",
					"'.$cl['align'].'",
					"'.time().'"
				 )');
				 if( $ins ) {
					 //
					 $cl['_id'] = mysql_insert_id();
					 $u->insertAlign( $cl['align'] , $pu['id'] );
					 mysql_query('INSERT INTO `clan_info` (`id`,`info`) VALUES (
					 "'.$cl['_id'].'",
					 "'.mysql_real_escape_string($cl['info']).'"
					 )');
					 copy('clan_prw/'.$cl['img1'],'../img.xcombats.com/i/clan/'.$cl['_id'].'.gif');
					 copy('clan_prw/'.$cl['img2'],'../img.xcombats.com/i/clan/'.$cl['_id'].'_big.gif');
					 copy('clan_prw/'.$cl['img2'],'../img.xcombats.com/i/clan/cln'.$cl['_id'].'.gif');
					 copy('clan_prw/'.$cl['img2'],'../img.xcombats.com/i/clan/'.$cl['name2'].'_big.gif');
					 mysql_query('UPDATE `users` SET `clan` = "'.$cl['_id'].'",`clan_prava` = "glava",`align` = "'.$cl['align'].'" WHERE `id` = "'.$pu['id'].'" LIMIT 1');
					 
					 echo '<font color=red><b>�� �������� ����������� ����� &quot;'.$cl['name'].'&quot;</b></font><br>';
				 }else{
					  echo '<font color=red><b>�� ������� ��������� ������</b></font><br>';
				 }
			 }else{
				 echo '<font color=red><b>�� ������� ��������� ������</b></font><br>';
			 }
			 //���������� �������� ����� �����
		  	mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
		  	'1','".$pu['city']."','0','','".$pu['login']."','<font color=red>��������!</font> ".date("d.m.y H:i")." ���������� �� �������������: \'����������� ��� � ������������ ����� &quot;".mysql_real_escape_string($cl['name'])."&quot;, ������ �������! ���������� ������ ������ ���� � �������� ��������� ��� ��������.\' .','-1','5','0')");

		 }
	 }
  }elseif(isset($_GET['badClan'])) {
	 //�����
	 $cl = mysql_fetch_array(mysql_query('SELECT * FROM `_clan` WHERE `admin_time` = "0" AND `id` = "'.mysql_real_escape_string($_GET['badClan']).'" LIMIT 1')); 
	 if(isset($cl['id'])) {
		  $pu = mysql_fetch_array(mysql_query('SELECT `id`,`city`,`room`,`clan`,`login`,`align`,`level`,`sex`,`money`,`banned` FROM `users` WHERE `id` = "'.mysql_real_escape_string($cl['uid']).'" LIMIT 1')); 
		  echo '<font color=red><b>�� �������� � ����������� ����� &quot;'.$cl['name'].'&quot;</b></font><br>';
		  mysql_query('UPDATE `_clan` SET `admin_time` = "'.time().'",`admin_ca` = "'.$u->info['id'].'" WHERE `id` = "'.$cl['id'].'" LIMIT 1');
		  //���������� �������� ���������
		  mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
		  '1','".$pu['city']."','0','','".$pu['login']."','<font color=red>��������!</font> ".date("d.m.y H:i")." ���������� �� �������������: \'� ��������� ��� �������� � ����������� ����� &quot;".mysql_real_escape_string($cl['name'])."&quot;, ���� �� ��������� ������� �����������. ��� ���������� �� ����� ".round($cl['money']*1,2)." ��.\' .','-1','5','0')");
	 	  
		  //���������� �����
			mysql_query("INSERT INTO `items_users`(`item_id`,`1price`,`uid`,`delete`,`lastUPD`)VALUES('1220','".mysql_real_escape_string(round($cl['money']*1,2))."','-51".$pu['id']."','0','".time()."');");
					
			$txt = '������ �� �������������: '.round($cl['money']*1,2).' ��. ��������: '.date('d.m.Y H:i',time()).'';
			mysql_query('INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES("'.$pu['id'].'","0","'.time().'",
			"'.mysql_real_escape_string(round($cl['money']*1,2)).'","'.mysql_real_escape_string($txt).'")');

			//���
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
			'1','".$pu['city']."','0','','".$pu['login']."','<font color=red>��������!</font> �������� ����� ����� �� �������������','-1','5','0')");
		  
	 }
  }
  ?></div>
  <script>
  function imgResize1(id) {
	  if($('#'+id).width() == 16) {
		  $('#'+id).animate({'height':'99px','width':'100px'},'fast');
	  }else{
		  $('#'+id).animate({'height':'15px','width':'16px'},'fast');
	  }
  }
  function seeClanINfo(id) {
	 if( $('#'+id).css('display') == 'block') {
		 $('#'+id).fadeOut('fast');
	 }else{
		 $('#'+id).fadeIn('fast');
	 }
  }
  </script>
  <?
  		$sp = mysql_query('SELECT * FROM `_clan` WHERE `admin_time` = "0" ORDER BY `time` ASC LIMIT 10');
		while($pl = mysql_fetch_array($sp)) {
			echo '<div style="border-top:1px solid grey;padding:5px;">
			#'.$pl['id'].' <font color="#cac9c7">|</font>
			'.date('d.m.y H:i',$pl['time']).' / '.$pl['money'].'.00 ��.
			<font color="#cac9c7">|</font>
			<img style="border:1px solid grey;display:inline-block;vertical-align:bottom;margin:0;padding:1px;" src="http://xcombats.com/clan_prw/'.$pl['img1'].'" width="24" height="15">'.
			'<span id="img'.$pl['id'].'clan2"><img id="img'.$pl['id'].'clan" style="border:1px solid blue;border-left:0;display:inline-block;vertical-align:bottom;margin:0;padding:0;" src="http://xcombats.com/clan_prw/'.$pl['img1'].'">'.
			'<script>$("#img'.$pl['id'].'clan").ready(function(){$("#img'.$pl['id'].'clan2").html(" "+$("#img'.$pl['id'].'clan").width()+"x"+$("#img'.$pl['id'].'clan").height()); });</script>
			</span>
			<font color="#cac9c7">|</font>
			<img id="img'.$pl['id'].'clan30" style="border:1px solid grey;display:inline-block;cursor:pointer;vertical-align:bottom;margin:0;padding:1px;width:16px;height:15px;" onclick="imgResize1(\'img'.$pl['id'].'clan30\')" src="http://xcombats.com/clan_prw/'.$pl['img2'].'">'.
			'<span id="img'.$pl['id'].'clan4"><img id="img'.$pl['id'].'clan3" style="border:1px solid blue;border-left:0;display:inline-block;vertical-align:bottom;margin:0;padding:0;" src="http://xcombats.com/clan_prw/'.$pl['img2'].'">'.
			'<script>$("#img'.$pl['id'].'clan3").ready(function(){$("#img'.$pl['id'].'clan4").html(" "+$("#img'.$pl['id'].'clan3").width()+"x"+$("#img'.$pl['id'].'clan3").height()); });</script>
			</span>
			<font color="#cac9c7">|</font>
			'.$u->microLogin($pl['uid'],1).'
			<font color="#cac9c7">|</font>
			<span style="display:inline-block;background:white;padding:2px 20px 2px 20px;text-align:center;">'.$pl['name'].'</span>
			<font color="#cac9c7">|</font>
			<span style="display:inline-block;background:white;padding:2px 20px 2px 20px;text-align:center;">'.$pl['name2'].'</span> (EN)
			<font color="#cac9c7">|</font>
			<img src="http://img.xcombats.com/i/align/align'.$pl['align'].'.gif">
			<font color="#cac9c7">|</font>
			<a href="javascript:void(0)" onClick="seeClanINfo(\'clndiv'.$pl['id'].'\');">���� � ��������</a>
			<font color="#cac9c7">|</font>
			&nbsp;<input onclick="location.href=\'?admin=1&goodClan='.$pl['id'].'\'" type="button" value="���������"> &nbsp;<font color="#cac9c7">|</font>&nbsp; <input onclick="location.href=\'?admin=1&badClan='.$pl['id'].'\'" type="button" value="��������">
				<div id="clndiv'.$pl['id'].'" style="padding:10px;display:none">
					<b>���� �����:</b> <a target="_blank" href="'.$pl['site'].'">'.$pl['site'].'</a><br><Br>
					�������� ����� (��� ����������):<br>
					<div style="max-width:620px;margin:10px;padding:10px;background:white;">
					<img src="http://xcombats.com/clan_prw/'.$pl['img2'].'" width="100" height="99" style="float:right">
					<center><h3>'.$pl['name'].'</h3></center>
					<br><div style="text-align:justify;">'.$pl['info'].'</div></div>
					<div style="width:600px;" align="center"><a href="javascript:void(0)" onClick="seeClanINfo(\'clndiv'.$pl['id'].'\');">(������ ��������� ����� � ��������)</a></div>					
				</div>
			</div>';
		}
  ?>
  </div>
  <? 
	  }
  }
	
  if($u->info['admin'] > 0) {
	  if(isset($_POST['add_item_to_user2'])) {
		 $uad = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['add_item_to_login']).'" LIMIT 1'));
		 if( isset($uad['id'])) {
		 	$u->addItem(round((int)$_POST['add_item_to_user']),$uad['id']);
			mysql_query('INSERT INTO `users_delo` (`onlyAdmin`,`hb`,`uid`,`time`,`city`,`text`,`login`,`ip`) VALUES ("1","0","'.$uad['id'].'","'.time().'","'.$uad['city'].'","'.$rang.' &quot;'.$u->info['login'].'&quot; <font color=red>����� �������</font>: �'.round((int)$_POST['add_item_to_user']).' ��������� <b>'.$uad['login'].'</b>.","'.$u->info['login'].'","'.$u->info['ip'].'")');
			echo '<font color=red><b>������� ��� ��������� � ���������</b></font>';
		 }else{
			 echo '<font color=red><b>�������� �� ������</b></font>';
		 }
	  }
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  ������ ������� <input name="add_item_to_user" value="" /> ��������� <input name="add_item_to_login" value="<?if(isset($_POST['add_item_to_login']))echo $_POST['add_item_to_login'];?>" />
  <input type="submit" name="add_item_to_user2" id="add_item_to_user2" value="������" />
  </div>
  <? 
  }
	
  if($p['addld']==1 || $p['cityaddld']==1){ ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
    �������� � "����" ������ ������� � ��������� ������, �������� � ��.<br />
	<?
	  	if(isset($_POST['pometka']))
		{
			$er = '';
			$usr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`city`,`admin`,`align` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['loginLD']).'" LIMIT 1'));
			if(isset($usr['id']))
			{
				if(($u->info['align']>1 && $u->info['align']<2 && $usr['align']>3 && $usr['align']<4) || ($usr['align']>1 && $usr['align']<2 && $u->info['align']>3 && $u->info['align']<4) || $usr['admin']>$u->info['admin'])
				{
					$er = '�������� "'.$_POST['loginLD'].'" ����� ��������� ����������.';	
				}else{
					//������� ������ � ��
					$lastD = mysql_fetch_array(mysql_query('SELECT `id` FROM `users_delo` WHERE `login` = "'.$u->info['login'].'" AND `time`>'.(time()-3).' LIMIT 1'));
					if(!isset($lastD['id']))
					{
						$hbld = 0;
						$hbld2 = 0;
						if(isset($_POST['hbld']))
						{
							$hbld = $a;
						}
						if(isset($_POST['hbldt'])) {
							$hbld2 = 1;
						}
						$ins = mysql_query('INSERT INTO `users_delo` (`onlyAdmin`,`hb`,`uid`,`time`,`city`,`text`,`login`,`ip`) VALUES ("'.$hbld2.'","'.$hbld.'","'.$usr['id'].'","'.time().'","'.$usr['city'].'","'.$rang.' &quot;'.$mod_login.'&quot; <b>��������</b>: '.mysql_real_escape_string(htmlspecialchars($_POST['textLD'],NULL,'cp1251')).'","'.$u->info['login'].'","'.$u->info['ip'].'")');
						if(!$ins)
						{
							$er = '������ ������ � ������ ����';
						}else{
							$er = '������ � ������ ���� ������ �������';
						}
					}else{
						$er = '������ ������� � ������ ���� ����� �� ���� ������ ���� � 3 �������.';
					}
				}
			}else{
				$er = '�������� � ������� "'.$_POST['loginLD'].'" �� ������.';
			}
			if($er!='')
			{
				echo '<font color="red"><b>'.$er.'</b></font><br>';
			}
		}
	  ?>
    ������� �����
  <input name="loginLD" type="text" id="loginLD" size="30" maxlength="30" />
    ���������
  <input name="textLD" type="text" id="textLD" size="70" maxlength="500" /> <input type="submit" name="pometka" id="pometka" value="��������" />
  <br />
  <label>
  <input name="hbld" type="checkbox" id="hbld" value="1" />  
    ��������, ��� ������� �������� � ����\����������
  </label>
  <? if($u->info['admin'] > 0) { ?>
  <br /><label>
  <input name="hbldt" type="checkbox" id="hbldt" value="1" />  
    �������� � ��������� ���� (����� ������ ��������� � �������������)
  </label>
  <? }
  }
  
  if($p['readPerevod']==1){
  if(isset($_POST['itemID1b'])) {
	  $its = '';
	  $its = $u->genInv(1,'`iu`.`id` = "'.mysql_real_escape_string($_POST['itemID1']).'" LIMIT 1');
	  if($its[0] == 0) {
		 $its = '������� �� ������.'; 
	  }else{
		  $its = $its[2];
	  }
	  echo '<br><br><b>������� <u>id'.$_POST['itemID1'].'</u>:</b><br>'.$its;
  }
  ?><div style="padding-top:10px;">
    ��������� ������� �������� � ��������� <small>(�� �����������)</small> 
      <input name="itemID1login" type="text" id="itemID1login" size="30" maxlength="30" />
    , id �������� 
      <input name="itemID1" type="text" id="itemID1" size="30" maxlength="30" />
      <input type="submit" name="itemID1b" id="itemID1b" value="���������" />
    </div>
  </div>
  <?
	$dsee = array();
	$dsee['login'] = $_POST['loginacts1'];
	$dsee['date'] = date('d.m.Y',time());
	if(isset($_POST['datesee']))
	{
		$dsee['date'] = $_POST['datesee'];
	}
	$dsee['date'] = explode('.',$dsee['date']);
	$dsee['date'] = $dsee['date'][2].'-'.$dsee['date'][1].'-'.$dsee['date'][0];
	$dsee['t1'] = strtotime($dsee['date'].' 00:00:00');
	$dsee['t2'] = strtotime($dsee['date'].' 23:59:59');
	$dsee['date'] = date('d.m.Y',$dsee['t1']);	
	 $i = 2;
	 while($i<=8)
	 {
		 if($_POST['hbld'.$i]==1)
		 {
		 	$dsee[$i] = 1;
		 }else{
			$dsee[$i] = 0; 
		 }
		 $i++;
	 }
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
    <h4>�������� �������� ��������/�����</h4>
    �������� �������� ���������
      <input name="loginacts1" type="text" id="loginacts1" value="<?=$dsee['login']?>" size="30" maxlength="30" />
      <div style="display:none">
      <br /> 
      <input name="hbld2" type="checkbox" id="hbld2" value="1" checked="checked" <? if($dsee[2]==1){ echo 'checked="checked"'; } ?> />
    �������� 
      
  , 
    <input name="hbld3" type="checkbox" id="hbld3" value="1" checked="checked" <? if($dsee[3]==1){ echo 'checked="checked"'; } ?> />
    ����

    , 
    <input name="hbld4" type="checkbox" id="hbld4" value="1" checked="checked" <? if($dsee[4]==1){ echo 'checked="checked"'; } ?> />
    ������� / ������

    , 
    <input name="hbld5" type="checkbox" id="hbld5" value="1" checked="checked" <? if($dsee[5]==1){ echo 'checked="checked"'; } ?> />
    ������ � ����������

    ,
    <input name="hbld6" type="checkbox" id="hbld6" value="1" checked="checked" <? if($dsee[6]==1){ echo 'checked="checked"'; } ?> />
�������� ,
    <input name="hbld7" type="checkbox" id="hbld7" value="1" checked="checked" <? if($dsee[7]==1){ echo 'checked="checked"'; } ?> /> ���������� ���������,
	
    <input name="hbld8" type="checkbox" id="hbld8" value="1" checked="checked" <? if($dsee[8]==1){ echo 'checked="checked"'; } ?> /> ����� <br />
    </div>
    �� ����  
    <input name="delosee_1" onclick="document.getElementById('datesee').value='<?=date('d.m.Y',($dsee['t1']-86400))?>';" type="submit" value="&laquo;" />
    <input name="datesee" type="text" id="datesee" value="<?=$dsee['date']?>" size="15" maxlength="10" />
    <input name="delosee_2" onclick="document.getElementById('datesee').value='<?=date('d.m.Y',($dsee['t1']+86400))?>';" type="submit" value="&raquo;" />
    <input type="submit" name="delosee" id="delosee" value="���������" />
    <?
	if(isset($_POST['delosee']) || isset($_POST['delosee_1']) || isset($_POST['delosee_2'])) {
	?>
    <div style="padding:0 0 5px 0; border-bottom:1px solid #cac9c7;">
    <small>���� �����: <?=$dsee['date']?>, �����: <?=$dsee['login']?></small>
    </div>
    <?
	$dsee['inf'] = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($dsee['login']).'" LIMIT 1'));
	if(isset($dsee['inf']['id']) && ($dsee['inf']['admin']==0 || $u->info['admin']>0))
	{
		$sp = mysql_query('SELECT * FROM `users_delo` WHERE `uid` = "'.$dsee['inf']['id'].'" AND `time` >= "'.$dsee['t1'].'" AND `time` <= "'.$dsee['t2'].'" ORDER BY `time` DESC LIMIT 10000');
		while($pl = mysql_fetch_array($sp))
		{
			$dl = explode('.',$pl['login']);
			$se = 1;
			     if($dl[0]=='AddItems' && $dsee[7]==0){ $se = 0;
			}elseif($dl[0]=='Bank' && $dsee[3]==0){ $se = 0;
			}elseif(($dl[0]=='Shop' || $dl[0]=='EkrShop') && $dsee[4]==0){ $se = 0;
			}elseif($dl[1]=='remont' && $dsee[4]==0){ $se = 0;
			}elseif($dl[1]=='shop' && $dsee[4]==0){ $se = 0; 
			}elseif($dl[1]=='inventory' && $dsee[5]==0){ $se = 0;
			}elseif($dl[1]=='transfer' && $dsee[2]==0){ $se = 0;
			}
			if($se==1)
			{
				$dsee['dv'] .= '<small>'.date('d.m.Y H:i',$pl['time']).' / <b>'.$pl['login'].'</b>:</small> '.$pl['text'];
				$dsee['dv'] .= '<br>';
			}
		}
		if($dsee[8]==1){
			//$sp1 = mysql_query('SELECT * FROM `post` WHERE  `uid` = "'.$dsee['inf']['id'].'" AND `time` >= "'.$dsee['t1'].'" AND `time` <= "'.$dsee['t2'].'" OR `sender_id` = "'.$dsee['inf']['id'].'" AND `time` >= "'.$dsee['t1'].'" AND `time` <= "'.$dsee['t2'].'" OR `sender_id` = "-'.$dsee['inf']['id'].'" AND `time` >= "'.$dsee['t1'].'" AND `time` <= "'.$dsee['t2'].'" LIMIT 10000');
			$sp1 = mysql_query('SELECT * FROM `post` WHERE  `uid` = "'.$dsee['inf']['id'].'" AND `time` >= "'.$dsee['t1'].'" AND `time` <= "'.$dsee['t2'].'" ORDER BY `time` DESC LIMIT 10000');
			echo '<hr/>';
			while($pl1 = mysql_fetch_array($sp1))
			{
				if (!$pl1['item_id']==0) {$dseetext = "[item:#".$pl1['item_id']."]";}
				$dsee['dv'] .= '<small>'.date('d.m.Y H:i',$pl1['time']).' / <b>�������� �������</b>:</small>'.$pl1['text'].' '.$dseetext;
				$dsee['dv'] .= '<br>';
				$dseetext="";
			} 
		}
		$sp1 = mysql_query('SELECT * FROM `clan_operations` WHERE  `uid` = "'.$dsee['inf']['id'].'" AND `time` >= "'.$dsee['t1'].'" AND `time` <= "'.$dsee['t2'].'" ORDER BY `time` DESC LIMIT 10000');
		echo '<hr/>';
		while($pl1 = mysql_fetch_array($sp1))
		{
			$pl1['text'] = ' �������� ';
			if( $pl1['type'] == 1 ) {
				$pl1['text'] .= '<b>���� �������</b> � ����� �����: '.$pl1['val'].' ��.';
			}elseif( $pl1['type'] == 2 ) {
				$pl1['text'] .= '<b>������� �������</b> � ����� �����: '.$pl1['val'].' ��.';
			}elseif( $pl1['type'] == 5 ) {
				$pl1['text'] .= '<b>����</b> ������� &quot;'.$pl1['val'].'&quot; �� ��������� �����.';
			}elseif( $pl1['type'] == 4 ) {
				$pl1['text'] .= '<b>�����������</b> ������� &quot;'.$pl1['val'].'&quot; � ��������� �����.';
			}elseif( $pl1['type'] == 7 ) {
				$pl1['text'] .= '<b>�������</b> ������� &quot;'.$pl1['val'].'&quot; �� ��������� �����. (��������������� �����)';
			}elseif( $pl1['type'] == 8 ) {
				$pl1['text'] .= '<b>�������</b> ������� &quot;'.$pl1['val'].'&quot; �� ��������� �����. (��� ������ �� �����)';
			}elseif( $pl1['type'] == 3 ) {
				$pl1['text'] .= '<b>������</b> ������� &quot;'.$pl1['val'].'&quot; � ��������� �����.';
			} elseif( $pl1['type'] == 6 ) {
				$pl1['text'] .= '<b>�����</b> ������� &quot;'.$pl1['val'].'&quot;.';
			} elseif( $pl1['type'] == 9 ) {
				$pl1['text'] .= '<b>������</b> ������� &quot;'.$pl1['val'].'&quot;. [����� �� ����� (������� ����� �� ������������� ���������)]';
			}else{
				$pl1['text'] .= '<u>����������� ������. ���: '.$pl1['val'].' / '.$pl1['type'].'</u>';
			}
			$dsee['dv'] .= '<small>'.date('d.m.Y H:i',$pl1['time']).' / <b style="color:green">�������� �����</b>:</small>'.$pl1['text'].' '.$dseetext;
			$dsee['dv'] .= '<br>';
			$dseetext="";
		} 
		
		if($dsee['dv']=='')
		{
			echo '<font color="red"><b>�������� � ��������� �� <B>'.$dsee['date'].'</B> �� �������.</b></font>';
		}else{
			echo $dsee['dv'];
		}
	}else{
		echo '<font color="red"><b>�������� �� ������, ���� ��� ���� ������ �������������...</b></font>';
	}
	?>
    <? } ?>
  </div>
  <? } 
  
  if($p['priemIskl']==1){
	  if(isset($_POST['pometka52015'])) {
	  	$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['loginLD52015']).'" ORDER BY `id` ASC LIMIT 1'));
	  	if(!isset($uu['id'])) {
			echo '<div><font color=red><b>�������� �� ������!</b></font></div>';
		}elseif($u->info['admin'] == 0 && $uu['align'] > 1 && $uu['align'] < 2 && $a != 1) {
			echo '<div><font color=red><b>�� �� ������ �������� ������ ����� ��������</b></font></div>';
		}elseif($u->info['admin'] == 0 && $uu['align'] > 3 && $uu['align'] < 4 && $a != 3) {
			echo '<div><font color=red><b>�� �� ������ �������� ������ ����� �������</b></font></div>';
		}elseif( $a == 1 && ($uu['align'] <= 1 || $uu['align'] >=2 || ($uu['admin'] > 0 && $u->info['admin'] == 0) || ($uu['align'] > $u->info['align'] && $u->info['admin'] == 0)) ) {
			echo '<div><font color=red><b>�� �� ������ �������� ������ ����� ���������!</b></font></div>';
		}elseif( $a == 3 && ($uu['align'] <= 3 || $uu['align'] >=4 || ($uu['admin'] > 0 && $u->info['admin'] == 0) || ($uu['align'] > $u->info['align'] && $u->info['admin'] == 0)) ) {
			echo '<div><font color=red><b>�� �� ������ �������� ������ ����� ���������</b></font></div>';
		}else{
			$sx = '';
			if($u->info['sex']==1)
			{
				$sx = '�';
			}
			if( $a == 1 ) {
				$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; �������'.$sx.' ������ �������� ('.$uu['align'].') �� &quot;'.htmlspecialchars($_POST['textLD52015']).'&quot;.';
			}elseif( $a == 3 ) {
				$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; �������'.$sx.' ������ ������� ('.$uu['align'].') �� &quot;'.htmlspecialchars($_POST['textLD52015']).'&quot;.';	
			}
			mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".mysql_real_escape_string($rtxt)."','".$u->info['login']."',0)");
			echo '<div><font color=red><b>�� ������� �������� ������ ���������!</b></font></div>';	
			mysql_query('UPDATE `users` SET `mod_zvanie` = "'.mysql_real_escape_string($_POST['textLD52015']).'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
		}
	  }
	?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>�������� ������ <? if( $a == 1 ) { echo '��������'; }elseif( $a == 3 ) { echo '�������'; } ?></h4>  
  ������� �����  <input name="loginLD52015" type="text" id="loginLD52015" size="30" maxlength="30" /> ����� ������ <input name="textLD52015" type="text" id="textLD52015" size="70" maxlength="30" /> <input type="submit" name="pometka52015" id="pometka52015" value="���������" />
  </div>
    <?  
  }
  if($p['newuidinv']==1){	
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>�������� ��������� ���������</h4>  
  ������� �����  <input name="newuidinv" type="text" id="newuidinv" size="30" maxlength="30" /> <input type="submit" name="pometka52017" id="pometka52017" value="���������" />
  </div>
  <?  
  }
  if($p['testchat']==1){	
	  if(isset($_POST['pometka52016'])) {
		  $ret = '';
		  $sp = mysql_query('SELECT * FROM `chat` WHERE `text` LIKE "%' . mysql_real_escape_string($_POST['textLD52016']) . '%"');
		  while( $pl = mysql_fetch_array($sp)) {
			 if( date('H:i',$pl['time']) == $_POST['loginLD52016'] ) {
				 if( $pl['type'] == 3 ) {
					 $pl['type'] = 'to';
				 }else{
					 $pl['type'] = 'private';
				 }
				 $ret = '<div><span class=date2>'.date('d.m.Y H:i',$pl['time']).'</span> [<b>'.$pl['login'].'</b>] '.$pl['type'].' [<b>'.$pl['to'].'</b>] <font color="'.$pl['color'].'">'.$pl['text'].'</font></div>';
			 }
		  }
		  if($ret != '') {
			 echo '<div><font color="red"><b>��������� �������:</b></font><br>'.$ret.'</div>'; 
		  }else{
			 echo '<div><font color="red"><b>��������� �� �������.</b> �������� ��� ���� �������.</font></div>';  
		  }
	  }
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>�������� ���������</h4>  
  ������� ����� HH:ii (���:������, ������ <?=date('H:i')?>)  <input name="loginLD52016" type="text" id="loginLD52016" size="30" maxlength="30" /> ����� ��������� <input name="textLD52016" type="text" id="textLD52016" size="70" maxlength="30" /> <input type="submit" name="pometka52016" id="pometka52016" value="���������" />
  </div>
  <?  
  }
  
  if($p['telegraf']==1) {
	  if(isset($_POST['pometka5'])) {
		 $tous = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['loginLD5']).'" LIMIT 1')); 
		 if(isset($tous['id'])) {
			 if($u->info['align'] > 1 && $u->info['align'] < 2) {
				 $zvnt = '������� <b>'.$mod_login.'</b> ��������';
				 $zvno = '����� �����';
			 }elseif($u->info['align'] > 3 && $u->info['align'] < 4) {
				 $zvnt = '������ <b>'.$mod_login.'</b> ��������';
				 $zvno = '������';
			 }elseif($u->info['admin'] > 0) {
				 $zvnt = '������������� ��������';
				 $zvno = '�������������';
			 }else{
				 $zvnt = '������������� ��������.';
				 $zvno = '�������������';
			 }
			 mysql_query('INSERT INTO `telegram` (`uid`,`from`,`tema`,`text`,`time`) VALUES ("'.$tous['id'].'","<b><font color=red>'.$zvno.'</font></b>","'.$zvnt.'","'.mysql_real_escape_string(htmlspecialchars($_POST['textLD5'],NULL,'cp1251')).'","'.time().'")');
		 	 echo '<font color="red"><b>��������� ������� ����������</b></font>';
		 }else{
			 echo '<font color="red"><b>�������� �� ������...</b></font>';
		 }
	  }
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>��������� ��������</h4>  
  ������� �����  <input name="loginLD5" type="text" id="loginLD5" size="30" maxlength="30" /> ��������� <input name="textLD5" type="text" id="textLD5" size="70" maxlength="1000" /> <input type="submit" name="pometka5" id="pometka5" value="��������" />
  </div>
  <?  
  }
  
  if(($u->info['align'] > 1 && $u->info['align'] < 2) || ($u->info['align'] > 3 && $u->info['align'] < 4) || $u->info['admin'] > 0) {
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>�������� ������ ���������</h4>  
  <?
	if(isset($_POST['pometka587'])) {
		$sp = mysql_query('SELECT `id`,`login` FROM `users` WHERE `invis` = 1 OR `invis` > "'.time().'"');
		$html = '';
		while( $pl = mysql_fetch_array($sp) ) {
			$html .=  $u->microLogin($pl['id'],1) . ' -> <b>'.$pl['login'].'</b> (id '.$pl['id'].')<br>';
		}
		if($html == '') {
			$html = '<b style="color:red">��� ����������-���������</b>';
		}
		echo $html.'<br>';
	}
  ?>
  <input type="submit" name="pometka587" id="pometka587" value="�������� ������ ���������" />
  </div>
  <?  
  }
  
  if($p['telegraf']==1) {
		if($u->info['align'] > 1 && $u->info['align'] < 2  && $u->info['admin'] == 0) {
			$zvnt = '������� <b>'.$mod_login.'</b> ��������:';
			$zvno = '����� �����';
		}elseif($u->info['align'] > 3 && $u->info['align'] < 4  && $u->info['admin'] == 0) {
			$zvnt = '������ <b>'.$mod_login.'</b> ��������:';
			$zvno = '������';
		}elseif($u->info['admin'] > 0) {
			$zvnt = '������������� <b>'.$mod_login.'</b> ��������:';
			$zvno = '�������������';
		}else{
			$zvnt = '������������� ��������:';
			$zvno = '�������������';
		}
	  if(isset($_POST['pometka577'])) {
		 //$tous = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['loginLD5']).'" LIMIT 1')); 
		 //if(isset($tous['id'])) {
			 //mysql_query('INSERT INTO `telegram` (`uid`,`from`,`tema`,`text`,`time`) VALUES ("'.$tous['id'].'","<b><font color=red>'.$zvno.'</font></b>","'.$zvnt.'","'.mysql_real_escape_string(htmlspecialchars($_POST['textLD5'],NULL,'cp1251')).'","'.time().'")');
				if(isset($_POST['hbld577'])) {
					$_POST['textLD577'] = ''.$zvnt.' '.$_POST['textLD577'];
				}else{
					$_POST['textLD577'] = '<b>'.$zvno.'</b> ��������: '.$_POST['textLD577'];
				}
				mysql_query('INSERT INTO `chat` (`invis`,`da`,`delete`,`molch`,`new`,`login`,`to`,`city`,`room`,`time`,`type`,`spam`,`text`,`toChat`,`color`,`typeTime`,`sound`,`global`) VALUES (
				"'.$u->info['invis'].'",
				"1",
				"0",
				"0",
				"1",
				"",
				"",
				"'.$u->info['city'].'",
				"0",
				"'.time().'",
				"6",
				"0",
				"'.mysql_real_escape_string($_POST['textLD577']).'",
				"0",
				"red",
				"0",
				"0",
				"0")');
			 echo '<font color="red"><b>��������� ������� ����������</b></font>';
		 //}else{
		 //	 echo '<font color="red"><b>�������� �� ������...</b></font>';
		 //}
	  }
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>�������</h4>  
  ��������� <input name="textLD577" type="text" id="textLD577" size="70" maxlength="1000" /> <input type="submit" name="pometka577" id="pometka577" value="��������" />
  <br />
  <input name="hbld577" type="checkbox" id="hbld577" value="1" /> ��������� ��������� �� ������ ������ &quot;<?=$zvnt?>&quot;
  </div>
  <?  
  }
  
  if($p['seeld']==1) {
  $pld520 = date('d.m.Y');
  if( isset($_POST['loginLD520']) ) {
	  $pld520 = $_POST['loginLD520'];
  }
  $pld520TS = strtotime(str_replace(".", "-", $pld520));
  $pld520 = date('d.m.Y',$pld520TS);
  ?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>����������� ����������</h4>  
  ���� �����������  
  <input name="pometka520" onclick="document.getElementById('loginLD520').value='<?=date('d.m.Y',($pld520TS-86400))?>';" type="submit" value="&laquo;" />
  <input value="<?=$pld520?>" name="loginLD520" type="text" id="loginLD520" size="20" maxlength="10" /> 
  <input name="pometka520" onclick="document.getElementById('loginLD520').value='<?=date('d.m.Y',($pld520TS+86400))?>';" type="submit" value="&raquo;" />
  <input type="submit" name="pometka520" id="pometka520" value="��������" />
  <?
  if( isset($_POST['pometka520'])) {
	  $sp = mysql_query('SELECT `users`.`id`,`users`.`mail`,`users`.`host_reg`,`users`.`banned`,`users`.`battle`,`users`.`online`,`users`.`molch1`,`users`.`bithday` FROM `users` LEFT JOIN `stats` ON `stats`.`id` = `users`.`id` WHERE `users`.`bithday` != "01.01.1800" AND `stats`.`bot` = 0 AND `users`.`timereg` >= '.$pld520TS.' AND `users`.`timereg` < '.($pld520TS+86400).' ORDER BY `users`.`id` ASC');
	  $i = 1;
	  echo '<br><b><font color=red>��������� ������������������ '.$pld520.'</font></b>';
	  while( $pl = mysql_fetch_array($sp) ) {
		 $urt5202 = '<br>'.$i.'. '.$u->microLogin($pl['id'],1).''; 
		 
		 if( $pl['banned'] > 0 ) {
			 $urt5202 = '<font color=red>'.$urt5202.'</font>';
		 }elseif( $pl['online'] > time()-520 ) {
			 $urt5202 = '<font color=green>'.$urt5202.'</font>';
		 }
		 if( $pl['molch1'] > time() ) {
			 $urt5202 .= ' <img title="�� ��������� ��������" src=http://img.xcombats.com/i/sleep2.gif width=24 height=15>';
		 }
		 if( $pl['battle'] > 0 ) {
			 $urt5202 .= ' <a href="/logs.php?log='.$pl['battle'].'" target="_blank"><img src=http://img.xcombats.com/i/fighttype0.gif title="�������� � ��������"></a>';
		 }
		 if( $pl['host_reg'] > 0 ) {
			 $urt5202 .= ' &nbsp; <small>(������� ��������� '.$u->microLogin($pl['host_reg'],1).')</small>';
		 }else{
			 $tstm = mysql_fetch_array(mysql_query('SELECT `id` FROM `users_mail1` WHERE `mail` = "'.$pl['mail'].'" LIMIT 1'));
			 if(isset($tstm['id'])) {
				 $urt5202 .= ' &nbsp; <small style="color:blue">(�������� � �������� �1)</small>';
			 }
		 }
		 $urt520 .= $urt5202;
		 $i++;
	  }
	  echo $urt520;
	  unset($urt520,$i,$pl,$sp);
  }
  ?>
  </div>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>����������� � ip-������ (��������� 100)</h4>  
  ������� ip-�����  <input name="loginLD52" type="text" id="loginLD52" size="30" maxlength="30" /> <input type="submit" name="pometka52" id="pometka52" value="��������" />
  <input type="submit" name="pometka53" id="pometka53" value="�������� (���������)" />
  </div>
  <?
	  if(isset($_POST['pometka52']) || isset($_POST['pometka53'])) {
		 if(isset($_POST['pometka53'])) {
		 	$sp = mysql_query('SELECT * FROM `logs_auth` WHERE `ip` = "'.mysql_real_escape_string($_POST['loginLD52']).'" AND `type` = "3" ORDER BY `id` DESC LIMIT 100'); 		 
		 }else{
		 	$sp = mysql_query('SELECT * FROM `logs_auth` WHERE `ip` = "'.mysql_real_escape_string($_POST['loginLD52']).'" ORDER BY `id` DESC LIMIT 100'); 
		 }
		 $i = 1;
		 $r = '';
		 $ursz = array();
		 while($pl = mysql_fetch_array($sp)) {
			$tst = mysql_fetch_array(mysql_query('SELECT `id`,`admin`,`no_ip` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
			if(isset($tst['id']) && $tst['admin'] == 0 && ($tst['no_ip'] == '' && $tst['no_ip'] == 0)) {
				 if(!isset($ursz[$pl['uid']])) {
					$ursz[$pl['uid']] = $u->microLogin($pl['uid'],1); 
				 }
				 $r .= '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">';
				 $r .= '<span style="display:inline-block;width:30px">'.$i.'.</span> <span style="display:inline-block;width:250px">'.$ursz[$pl['uid']].'</span>';
				 if($pl['type']==3) {
					 $r .= '<span style="display:inline-block;width:100px;color:red;">��������</span>';
				 }else{
					 $r .= '<span style="display:inline-block;width:100px;color:green;">�������</span>';
				 }
				 $r .= ' &nbsp; '.date('d.m.Y H:i',$pl['time']).'';
				 
				 $r .= '</div>';
				 $i++;
			}
		 }
		 
		 echo '&nbsp;&nbsp; <font color="red">������ ��������� 100 ����������� � ip-�������:<b>'.$_POST['loginLD51'].'</b></font><br>';
		 if($r == '') {
			 if(isset($_POST['pometka53'])) {
			 	echo '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">����������� � ������ ip-������� �� ������� (���������)</div>';
			 }else{
				echo '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">����������� � ������ ip-������� �� �������</div>'; 
			 }
		 }else{
			 echo $r;
		 }	 
		 unset($r);
	  }
  }
  
  if($u->info['admin'] > 0 || $u->info['align'] == 1.99){
	$dsee = array();
	if(!isset($_POST['smod1'])) {
		$_POST['smod1'] = date('d.m.Y');
	}
	$dsee['date'] = explode('.',$_POST['smod1']);
	$dsee['date'] = $dsee['date'][2].'-'.$dsee['date'][1].'-'.$dsee['date'][0];
	$dsee['t1'] = strtotime($dsee['date'].' 00:00:00');
	$dsee['t2'] = strtotime($dsee['date'].' 23:59:59');
	$dsee['date'] = date('d.m.Y',$dsee['t1']);	  
	?>
  <div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
  <h4>�������� ��� �������� �����������</h4>
  
  �������� �������� �� <input name="smod1" type="text" id="smod1" value="<?=$_POST['smod1']?>" size="11" maxlength="10" />
  ����� ���������� <input name="smod2" type="text" id="smod2" value="<?=$_POST['smod2']?>" size="30" maxlength="30" />
  <input type="submit" name="delosee3" id="delosee3" value="�����" />
  </div>
  <?
	  if(isset($_POST['delosee3'])) {
		  $sp = mysql_query('SELECT * FROM `users_delo` WHERE `login` = "'.mysql_real_escape_string($_POST['smod2']).'" AND `time` >= '.$dsee['t1'].' AND `time` <= '.$dsee['t2'].'');
		  $rdl = '';
		  while($pl = mysql_fetch_array($sp)) {
			 $rdl .= '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">'; 
			 $rdl .= '<div style="display:inline-block;width:150px;color:green">'.date('d.m.Y H:i:s',$pl['time']).'</div>';
			 $rdl .= $pl['text'].' ��������� '.$u->microLogin($pl['uid'],1);
			 $rdl .= '</div>';
		  }
		  if($rdl == '') {
			 $rdl = '��������� �� �������� �������� �� ������ �����'; 
		  }
		  echo $rdl;
	  }
  } ?>
  
</form>
<?
	}
	//���������� ������ ����������
	}else{
		echo $merror.'<form action="main.php?'.$zv.'&enter='.$code.'" method="post"><center><br><br><br>��� ����� � ������ ��������� ������<hr>������� ������: <input value="" name="psw" type="password"><input type="submit" value="��" /><br><small style="color:grey;">���� �� �� �������� ������ ������ ���� ���<br>������ � ������ ����� ������������ �� �����.</small></form>';
	}
}

?>