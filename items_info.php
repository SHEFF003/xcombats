<?php
define('GAME',time());

include('_incl_data/class/__db_connect.php');

$u = mysql_fetch_array(mysql_query('SELECT `id`,`admin` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
if(!isset($u['id']) || $u['admin'] == 0) {
	$admin = 0;
}else{
	$admin = 1;
}
unset($u);

if(!isset($_GET['id'])) {
	$urla = explode('?',$_SERVER["REQUEST_URI"]);
	$url = explode('/',$urla[0]);

	$_GET['id'] = round((int)$url[2]);
}

/* ������� */
$rz = array(
	array('alls','������'),
	array('crutch','�������'), 					// 1
	array('potion','��������'),					// 2
	array('scrollattack','���������� ������'),	// 3
	array('armor','�����'),						// 4
	array('venok','�����'),						// 5
	array('naruchi','������'),					// 6
	array('shoes','�����'),						// 7
	array('glove','��������'),					// 8
	array('Cloak','�����'),						// 9
	array('leg','������'),						// 10
	array('belt','�����'),						// 11
	array('shirt','������'),					// 12
	array('helmet','�����'),					// 13
	array('el_sm','����'),						// 14
	array('flail','������ � ������'),			// 15
	array('knuckleduster','���� � �������'),	// 16
	array('sword','����'),						// 17
	array('staff','������'),					// 18
	array('pole-axe','������ � ������'),		// 19
	array('flower','����� � ������'),			// 20
	array('component','����������'),			// 21
	array('magicitem','���������� ��������'),	// 22
	array('gift','�������'),					// 23
	array('shield','����'),						// 24
	array('ring','������'),						// 25
	array('necklace','��������'),				// 26
	array('earring','������'),					// 27 
	array('other','������')					// 28 
);

// ���������� 3� �����������? True - ���������� � ����� ������, False - ������ ���� ����������.
$img3dShow = false; 

/* ���� ��������� �� �������� */
$rt = array(
	22	=> 0,
	26	=> 1,
	30	=> 2,
	29	=> 3,
	5	=> 4,
	6	=> 4,
	2	=> 5,
	3	=> 6,
	15	=> 7,
	12	=> 8,
	7	=> 9,
	14	=> 10,
	8	=> 11,
	4	=> 12,
	1	=> 13,
		//28	=> 14,
	20	=> 15,
	18	=> 16,
	21	=> 17,
	22	=> 18,
	19	=> 19,
	28	=> 20,
		//00	=> 21,
		//00	=> 22,
	38	=> 23,
	39	=> 23,
	37	=> 23,
	13	=> 24,
	11	=> 25,
	10	=> 26,
	9	=> 27,	
	62	=> 22,	
	67	=> 28,	// 67 ������	
	32	=> 22,	// 32 �����������, ���.��������
	34	=> 28	// 34 �����, ������
);

/* ������������ */
$u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));

/* ������� */
$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($_GET['id']).'" LIMIT 1'));


	function timeOut($ttm) {
	    $out = '';
		$time_still = $ttm;
		$tmp = floor($time_still/2592000);
		$id=0;
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
			$time_still = $time_still-$tmp*2592000;
		}
		/*
		$tmp = floor($time_still/604800);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
			$time_still = $time_still-$tmp*604800;
		}
		*/
		$tmp = floor($time_still/86400);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ��. ";}
			$time_still = $time_still-$tmp*86400;
		}
		$tmp = floor($time_still/3600);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." �. ";}
			$time_still = $time_still-$tmp*3600;
		}
		$tmp = floor($time_still/60);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
		}
		if($out=='')
		{
			if($time_still<0)
			{
				$time_still = 0;
			}
			$out = $time_still.' ���.';
		}
		return $out;
	}

function lookStats($m) {
	$ist = array();
	$di = explode('|',$m);
	$i = 0; $de = false;
	while($i<count($di))
	{
		$de = explode('=',$di[$i]);
		if(isset($de[0]))
		{
			$ist[$de[0]] = $de[1];
		}
		$i++;
	}
	return $ist;
}

if(!isset($itm['id'])) {
	$itd = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($_GET['id']).'" LIMIT 1'));
	if(!isset($itd['id'])) {
		$itd = array();
	}else{
		$itd = lookStats($itd['data']);
	}
}else{
	$itd = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1'));
	$itd = lookStats($itd['data']);
}

$items = array(
					'tr'  => array('lvl','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','a1','a2','a3','a4','a5','a6','a7','mg1','mg2','mg3','mg4','mg5','mg6','mg7','mall','m2all','aall'),
					'add' => array('min_heal_proc','no_yv1','no_bl1','no_pr1','no_yv2','no_bl2','no_pr2','silver','pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','yron_min','yron_max','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack'),
					'sv' => array('pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','min_use_mp','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack')
					);
					
$is = array('oza'=>'������ �� �����','oza1'=>'������ �� �������� �����','oza2'=>'������ �� �������� �����','oza3'=>'������ �� ��������� �����','oza4'=>'������ �� �������� �����','hpAll'=>'������� ����� (HP)','mpAll'=>'������� ����','sex'=>'���','lvl'=>'�������','s1'=>'����','s2'=>'��������','s3'=>'��������','s4'=>'������������','s5'=>'��������','s6'=>'��������','s7'=>'����������','s8'=>'����','s9'=>'������� ����','s10'=>'��������������','m1'=>'��. ������������ ����� (%)','m2'=>'��. ������ ������������ ����� (%)','m3'=>'��. �������� ������������ ����� (%)','m4'=>'��. ����������� (%)','m5'=>'��. ������ ����������� (%)','m6'=>'��. ���������� (%)','m7'=>'��. ����������� (%)','m8'=>'��. ����� ����� (%)','m9'=>'��. ������ ����� (%)','m14'=>'��. ���. ������������ ����� (%)','m15'=>'��. ���. ����������� (%)','m16'=>'��. ���. ����������� (%)','m17'=>'��. ���. ���������� (%)','m18'=>'��. ���. ����� ����� (%)','m19'=>'��. ���. ���������� ������ (%)','m20'=>'��. ����� (%)','a1'=>'���������� �������� ������, ���������','a2'=>'���������� �������� ��������, ��������','a3'=>'���������� �������� ��������, ��������','a4'=>'���������� �������� ������','a5'=>'���������� �������� ����������� ��������','a6'=>'���������� �������� ������','a7'=>'���������� �������� ����������','aall'=>'���������� �������� �������','mall'=>'���������� �������� ������ ������','m2all'=>'���������� �������� ������','mg1'=>'���������� �������� ������ ����','mg2'=>'���������� �������� ������ �������','mg3'=>'���������� �������� ������ ����','mg4'=>'���������� �������� ������ �����','mg5'=>'���������� �������� ������ �����','mg6'=>'���������� �������� ������ ����','mg7'=>'���������� �������� ����� ������','tj'=>'������� �����','lh'=>'������ �����','minAtack'=>'����������� ����','maxAtack'=>'������������ ����','m10'=>'��. �������� �����','m11'=>'��. �������� ����� ������','m11a'=>'��. �������� �����','pa1'=>'��. �������� �������� �����','pa2'=>'��. �������� �������� �����','pa3'=>'��. �������� �������� �����','pa4'=>'��. �������� ������� �����','pm1'=>'��. �������� ����� ����','pm2'=>'��. �������� ����� �������','pm3'=>'��. �������� ����� ����','pm4'=>'��. �������� ����� �����','pm5'=>'��. �������� ����� �����','pm6'=>'��. �������� ����� ����','pm7'=>'��. �������� ����� �����','za'=>'������ �� �����','zm'=>'������ �� ����� ������','zma'=>'������ �� �����','za1'=>'������ �� �������� �����','za2'=>'������ �� �������� �����','za3'=>'������ �� �������� �����','za4'=>'������ �� ������� �����','zm1'=>'������ �� ����� ����','zm2'=>'������ �� ����� �������','zm3'=>'������ �� ����� ����','zm4'=>'������ �� ����� �����','zm5'=>'������ �� ����� �����','zm6'=>'������ �� ����� ����','zm7'=>'������ �� ����� �����','pza'=>'��������� ������ �� �����','pzm'=>'��������� ������ �� �����','pza1'=>'��������� ������ �� �������� �����','min_heal_proc'=>'������ ������� (%)','silver'=>'�������','notravma'=>'������ �� �����','yron_min'=>'����������� ����','yron_max'=>'������������ ����','pza2'=>'��������� ������ �� �������� �����','pza3'=>'��������� ������ �� ��������� �����','pza4'=>'��������� ������ �� �������� �����','pzm1'=>'��������� ������ �� ����� ����','pzm2'=>'��������� ������ �� ����� �������','pzm3'=>'��������� ������ �� ����� ����','pzm4'=>'��������� ������ �� ����� �����','pzm5'=>'��������� ������ �� ����� �����','pzm6'=>'��������� ������ �� ����� ����','pzm7'=>'��������� ������ �� ����� �����','speedhp'=>'����������� �������� (��)','speedmp'=>'����������� ���� (��)','tya1'=>'������� �����','tya2'=>'������� �����','tya3'=>'�������� �����','tya4'=>'������� �����','tym1'=>'�������� �����','tym2'=>'������������� �����','tym3'=>'������� �����','tym4'=>'�������� �����','tym5'=>'����� �����','tym6'=>'����� ����','tym7'=>'����� �����','min_use_mp'=>'��������� ������ ����','pog'=>'���������� �����','maxves'=>'����������� ������');
 
if( !file_exists('../img.xcombats.com/i/encicl/pict_'.$rz[$rt[$itm['type']]][0].'.jpg') == true ) {
	//subject
	$rz[$rt[$itm['type']]][0] = 'subject';
}

if( !isset($rz[$rt[$itm['type']]][1])) {
	$rz[$rt[$itm['type']]][1] = '������ ��������';
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>������ ���������� ���� | ����������
<? if(isset($itm['id'])) { ?>
|
<?=$rz[$rt[$itm['type']]][1]?>
|
<?=$itm['name']?>
<? } ?>
</title>
<link href="/main1.css" rel="stylesheet" type="text/css">
<style type="text/css">
.style6 {	color: #DFD3A3;
	font-size: 9px;
}
.inup3 {
	border: 1px dashed #D3CAA0;
	font-size: 12px;

}
.inup3 {
	border: 1px dashed #D3CAA0;
	font-size: 12px;

}
A:link {
	FONT-WEIGHT: bold; COLOR: #5B3E33; TEXT-DECORATION: none
}
A:visited {
	FONT-WEIGHT: bold; COLOR: #633525; TEXT-DECORATION: none
}
A:active {
	FONT-WEIGHT: bold; COLOR: #77684d; TEXT-DECORATION: none
}
A:hover {
	COLOR: #000000; TEXT-DECORATION: underline
}
img {
	border:none;
}
</style>
</head>
<body bgcolor="#000000" topmargin=0 leftmargin=0 marginheight=0 marginwidth=0>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr valign=top>
    <td><table width="100%" height="135"  border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td background="http://xcombats.com/forum_script/img/line_capitalcity.jpg" scope="col" align=center><img style="display:block" src="http://xcombats.com/inx/newlogo.jpg" width="364" height="135" border=0></td>
        </tr>
      </table></td>
  </tr>
</table>
<? if(isset($itm['id'])) { ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor='#3D3D3B'>
  <tr valign=top>
    <td></td>
    <td align=center><SCRIPT>
wsize=document.body.clientWidth;
if (wsize >= 800 ) wsize=Math.floor(wsize*0.8);
if (wsize < 700) wsize=700;
document.write('<table cellspacing=0 cellpadding=0 bgcolor=#f2e5b1 border=0 width='+(wsize-20)+'>');
</SCRIPT>
  <tr valign=top>
    <td width="29" rowspan=2 background="http://xcombats.com/forum_script/img/leftground.jpg"><img src="http://img.xcombats.com/i/encicl/pictlr_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width="29" height="256"></td>
    <td width="118" align="left"><img id="imleft" src="http://img.xcombats.com/i/encicl/pictl_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width="118" height="257"><BR>
    </td>
    <td rowspan=2 align="left">     
      <p><b>�</b> <a href="http://xcombats.com/item/">��������</a> / <?=$rz[$rt[$itm['type']]][1]?> / <b><i><?=$itm['name']?></i></b>  
      <h2><?=$itm['name']?></h2>
      <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
      </p>
    <? if( $img3dShow==false && (file_exists('../img.xcombats.com/i/big/3d'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true) OR $img3dShow==true ) { ?>  
	<div align="center"> 
        <table width="504"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"  width="12" height="10"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
            <td width="100%" background="http://img.xcombats.com/i/encicl/ram12_34.gif"></td>
            <td width="12" height="10" align="right"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
          </tr>
          <tr>
            <td width="12" background="http://img.xcombats.com/i/encicl/line_left_13.gif">&nbsp;</td>
            <td width="480"><img style="display:block" src="<? if( file_exists('../img.xcombats.com/i/big/3d'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true ) { ?>http://img.xcombats.com/i/big/3d<?=str_replace('.gif','',str_replace('.png','',$itm['img']))?>.jpg<? }else{ ?>http://img.xcombats.com/i/big/back.jpg<? } ?>" alt="" width="480" height="360" border=1 id="bigim"></td>
            <td width="12" background="http://img.xcombats.com/i/encicl/line_right_13.gif">&nbsp;</td>
          </tr>
          <tr>
           		  <td align="left" width="12" height="10"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
                  <td width="100%" style="background-image:url(http://img.xcombats.com/i/encicl/ram12_34down.gif); background-position:bottom"></td>
                  <td width="12" align="right"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
          </tr>
        </table>
	</div>
	<? } ?>
      <p align="center">&nbsp;</p>
      <BR>
      <table width="504" border="0" align=center cellpadding="0" cellspacing="0">
        <tr>
          <td align=left valign="top">
	
<table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="inup3">
<tr>
<td width="100%">
<A HREF="?id=<?=$itm['id']?>" target=_blank><?=$itm['name']?></A>
<IMG SRC="http://img.xcombats.com/i/align/align0.gif" WIDTH=12 HEIGHT=15>  
<?
if($admin == 1) {
	$crd = '<small><a href="javascript:window.open(\'http://xcombats.com/item_edit_data.php?edit_item_data='.$itm['id'].'\',\'winEdi1\',\'width=850,height=400,top=400,left=500,resizable=no,scrollbars=yes,status=no\');" target="_blank">������������� �������</a> </small> &nbsp; &nbsp;';
}
echo $crd;

if($itm['massa']>0) {
	echo '(�����: '.$itm['massa'].') ';
}
if(isset($itd['art'])) {
	echo '<IMG SRC=http://img.xcombats.com/i/artefact.gif WIDTH=18 HEIGHT=16 ALT="����������� ����"> ';
}

				if(isset($po['sudba']))
				{
					echo '<img title="���� ������� ����� ������ ����� ������� � ������, ��� ������� ���. ����� ������ �� ������ ��� ������������." src="http://img.xcombats.com/i/destiny0.gif"> ';
				}

if($itm['price1'] > 0) {
	echo '<br><b>����: '.$itm['price1'].' ��.</b>';
}

if($itm['price2'] > 0) {
	echo '<br><b style="color:SaddleBrown ">����: '.$itm['price2'].' ���.</b>';
}

if($itm['iznosMAXi'] >= 999999999) {
	echo '<br>�������������: <font color="brown">�����������</font >';
}elseif($itm['iznosMAXi'] > 0) {
	echo '<br>�������������: 0/'.$itm['iznosMAXi'].'';
}


				//���� �������� ��������
				if($itd['srok'] > 0)
				{
					$itm['srok'] = $itd['srok'];
				}
				if($itm['srok'] > 0)
				{
					echo '<br>���� ��������: '.timeOut($itm['srok']);
				}
				
				//����������������� �������� �����:
				if((int)$itm['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.((int)$itm['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						echo '<br>����������������� ��������: '.timeOut($efi['actionTime']);
					}
				}


/* ���������� */
$tr = '';

$t = $items['tr'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['tr_'.$n])) {
		$tr .= '<br>� ';
		$tr .= $is[$n].': '.$itd['tr_'.$n];
	}
	$x++;
}

if($tr != '') {
	echo '<br><B>��������� �����������:</B>'.$tr;
}

/* ��������� �� */
$tr = '';

$t = $items['add'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['add_'.$n]) && isset($is[$n])) {
		$z = '+';
		if($itd['add_'.$n] < 1) {
			$z = '';
		}
		$tr .= '<br>&bull; '.$is[$n].': '.$z.$itd['add_'.$n];
	}
	$x++;
}

				//��������� �� (�����)
				$i = 1; $bn = array(1=>'������',2=>'�������',3=>'�����',4=>'���');
				while($i<=4)
				{
					if(isset($itd['add_mab'.$i]))
					{
						if($itd['add_mab'.$i]==$itd['add_mib'.$i] && $itm['geniration']==1)
						{
							$z = '+';
							if($itd['add_mab'.$i]<0)
							{
								$z = '';
							}
							$tr .= '<br>&bull; ����� '.$bn[$i].': '.$z.''.$itd['add_mab'.$i];
						}else{
							$tr .= '<br>&bull; ����� '.$bn[$i].': '.$itd['add_mib'.$i].'-'.$itd['add_mab'.$i];
						}
					}
					$i++;
				}

if($tr != '') {
	echo '<br><B>��������� ��:</B>'.$tr;
}

/* �������� �������� */
$tr = '';

				if(isset($itd['sv_yron_min'],$itd['sv_yron_max']))
				{
					$tr .= '<br>� ����: '.$itd['sv_yron_min'].' - '.$itd['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($itd['sv_'.$n]))
					{
						$z = '+';
						if($itd['sv_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>� '.$is[$n].': '.$z.''.$itd['sv_'.$n];
					}
					$x++;
				}
				if($itm['2too']==1)
				{
					$tr .= '<br>� ������ ������';
				}
				if($itm['2h']==1)
				{
					$tr .= '<br>� ��������� ������';
				}
				if(isset($itd['zonb']))
				{
					$tr .= '<br>� ���� ������������: ';
					if($itd['zonb']>0)
					{
						$x = 1;
						while($x<=$itd['zonb'])
						{
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '�';
					}
				}

if($tr != '') {
	echo '<br><B>�������� ��������:</B>'.$tr;
}

/* ����������� */
$tr = '';

				$x = 1;
				while($x<=4)
				{
					if($itd['tya'.$x]>0)
					{
						$tyc = '�������� �����';
						if($itd['tya'.$x]>6)
						{
							$tyc = '�����';
						}
						if($itd['tya'.$x]>14)
						{
							$tyc = '����';
						}
						if($itd['tya'.$x]>34)
						{
							$tyc = '���������';
						}
						if($itd['tya'.$x]>79)
						{
							$tyc = '���������';
						}
						if($itd['tya'.$x]>89)
						{
							$tyc = '�����';
						}
						if($itd['tya'.$x]>=100)
						{
							$tyc = '������';
						}
						$tr .= '<br>&bull; '.$is['tya'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if($itd['tym'.$x]>0)
					{
						$tyc = '�������� �����';
						if($itd['tym'.$x]>6)
						{
							$tyc = '�����';
						}
						if($itd['tym'.$x]>14)
						{
							$tyc = '����';
						}
						if($itd['tym'.$x]>34)
						{
							$tyc = '���������';
						}
						if($itd['tym'.$x]>79)
						{
							$tyc = '���������';
						}
						if($itd['tym'.$x]>89)
						{
							$tyc = '�����';
						}
						if($itd['tym'.$x]>=100)
						{
							$tyc = '������';
						}
						$tr .= '<br>&bull; '.$is['tym'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x <= 4)
				{
					if($itd['add_oza'.$x]>0)
					{
						$tyc = '������';
						if($itd['add_oza'.$x] == 4)
						{
							$tyc = '��������������';
						}
						if($itd['add_oza'.$x] == 2)
						{
							$tyc = '����������';
						}
						if($itd['add_oza'.$x] == 3)
						{
							$tyc = '�������';
						}
						if($itd['add_oza'.$x] == 5)
						{
							$tyc = '������������';
						}
						if($tyc != '') {
							$tr .= '<br>&bull; '.$is['oza'.$x].': '.$tyc;
						}
					}
					$x++;
				}

				if(isset($itd['free_stats']) && $itd['free_stats']>0)
				{
					echo '<br><b>��������� �������������:</b><br>&bull; ��������� �������������: '.$itd['free_stats'];
				}

if($tr != '') {
	echo '<br><B>�����������:</B>'.$tr;
}
				$is2 = '';

				if(isset($itd['complect']))
				{
					$is2 .= '<br><i>�������������� ����������:</i>';
				}
				if(isset($itd['complect']))
				{
					//�� ������������
					$com1 = array('name'=>'����������� ��������','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT * FROM `complects` WHERE `com` = "'.$itd['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($itmc = mysql_fetch_array($spc))
					{
						$com1['name'] = $itmc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$itmc['x'].'</font>: ';
						//�������� ���������
						$i1c = 0; $i2c = 0;
						$i1e = lookStats($itmc['data']);
						while($i1c<count($items['add']))
						{
							if(isset($i1e[$items['add'][$i1c]]))
							{
								$i3c = $i1e[$items['add'][$i1c]];
								if($i3c>0)
								{
									$i3c = '+'.$i3c;
								}
								if($i2c>0)
								{
									$com1['text'] .= '&nbsp; &nbsp; '.$is[$items['add'][$i1c]].': '.$i3c;
								}else{
									$com1['text'] .= $is[$items['add'][$i1c]].': '.$i3c;
								}								
								$com1['text'] .= '<br>';
								$i2c++;
							}
							$i1c++;
						}
						unset($i1c,$i2c,$i3c);
						$com1['x']++;
					}
					$is2 .= '<br>&bull; ����� ���������: <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				$is2 .= '<small style="font-size:10px;">';
				
				if($itm['info']!='')
				{
					$is2 .= '<div><b>��������:</b></div><div>'.$itm['info'].'</div>';
				}
				
				if($itd['info']!='')
				{
					$is2 .= '<div>'.$itd['info'].'</div>';                                        
				}
				
				if($itm['max_text']-$itm['use_text'] > 0) {
					$is2 .= '<div>���������� ��������: '.($itm['max_text']-$itm['use_text']).'</div>';
				}
				
				if(isset($itd['noremont']))
				{
					$is2 .= '<div style="color:brown;">������� �� �������� �������</div>';
				}
				
				if(isset($itd['frompisher']) && $itd['frompisher']>0)
				{
					$is2 .= '<div style="color:brown;">������� �� ����������</div>';
				}
				
				if($itm['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">������� ����� ������ ��� ������ �� ����������</div>';
				}				
				
				$is2 .= '</small>';
				
echo $is2;

?>
<BR>



<? /*

<B>��������� �����������:</B><BR>&bull; �������: 4<BR>&bull; ������������: 16<BR>&bull; ����: 16<BR>
<B>��������� ��:</B><BR>&bull; ��. ����������� (%): +30<BR>&bull; ��������: +3<BR>&bull; ����: +5<BR>
<B>�������� ��������:</B><BR>&bull; ����: 8 - 18<BR>&bull; ��. ������ ����������� (%): 30<BR>&bull; ��. ������������ ����� (%): 50<BR>&bull; ���������� �������� ������: 2<BR>&bull; ���� ������������: +<BR>

<B>�����������:</B><BR>&bull; ������� �����: ����<BR>&bull; ������� �����: ���������<BR>&bull; �������� �����: �����<BR>&bull; ������� �����: ����<BR>



<small>�������� �������� �� 10�� ������</small><BR>

*/
?>

</td>
<td align="center" valign="top" style='padding: 0,2,0,5'>
<a title="<?=$itm['name']?>" href="/item/<?=$itm['id']?>"><SPAN style='background-color: #E0E0E0'><img src="http://img.xcombats.com/i/items/<?=$itm['img']?>" alt="<?=$itm['name']?>" name="image" border="0"></SPAN></a></td>
</tr>
</table>

	</tr>
      </table>
      <p>
      <!-- End of text -->
    <td style='padding-left: 3' align=right><img id="imright" height=144 src="http://img.xcombats.com/i/encicl/pict_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width=139 border=0></td>
    <td valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr valign=top>
    <td></td>
    <td valign=center style="padding-bottom:50" align="right"><IMG height=236 src="http://img.xcombats.com/i/encicl/pictr_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width=128 border=0></td>
    <td width="23" valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;</td>
  </tr>
</table>
<? }else{
	
	$rv = explode('i',$url[2]);
	$rv = (int)$rv[1];
	//
	$rname = '���������� ��������� ����������� �����';
	$html = '';
	//
	if( $rv > 0 ) {
		
		if($rv == 1) {
			//�������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 26');
		}elseif($rv == 3) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 29 AND `name` NOT LIKE "%��������������%"');
		}elseif($rv == 2) {
			//��������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 30');
		}elseif($rv == 6) {
			//����� �������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 6');
		}elseif($rv == 32) {
			//����� ������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 5');
		}elseif($rv == 7) {
			//����� �� ������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 2');
		}elseif($rv == 8) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 3');
		}elseif($rv == 9) {
			//�������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 15');
		}elseif($rv == 10) {
			//��������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 12');
		}elseif($rv == 11) {
			//�����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 7');
		}elseif($rv == 12) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 14');
		}elseif($rv == 13) {
			//�����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 8');
		}elseif($rv == 14) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 4');
		}elseif($rv == 15) {
			//�����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 1');
		}elseif($rv == 16) {
			//����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `name` LIKE "%����%"');
		}elseif($rv == 17) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 20');
		}elseif($rv == 18) {
			//�������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 18');
		}elseif($rv == 19) {
			//����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 21');
		}elseif($rv == 20) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 19');
		}elseif($rv == 21) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 22');
		}elseif($rv == 22) {
			//�����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 32 AND `img` LIKE "f_%"');
		}elseif($rv == 23) {
			//����������
			//$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 2');
		}elseif($rv == 24) {
			//���.��������
			//$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 2');
		}elseif($rv == 25) {
			//�������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 38 OR `type` = 63');
		}elseif($rv == 26) {
			//����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 31');
		}elseif($rv == 27) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 32');
		}elseif($rv == 28) {
			//����
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 13');
		}elseif($rv == 29) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 11');
		}elseif($rv == 30) {
			//��������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 10');
		}elseif($rv == 31) {
			//������
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 9');
		}
		
	}
	
	
	
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor='#3D3D3B'>
  <tr valign=top>
    <td></td>
    <td align=center><SCRIPT>
wsize=document.body.clientWidth;
if (wsize >= 800 ) wsize=Math.floor(wsize*0.8);
if (wsize < 700) wsize=700;
document.write('<table cellspacing=0 cellpadding=0 bgcolor=#f2e5b1 border=0 width='+(wsize-20)+'>');
    </SCRIPT>
  <tr valign=top>
      <td width="29" rowspan=2 background="http://xcombats.com/forum_script/img/leftground.jpg"><img src="http://img.xcombats.com/i/encicl/pictlr_subject.jpg" width="29" height="256"></td>
      <td width="278" height="257" align="left"><img id="imleft2" src="http://img.xcombats.com/i/encicl/pictl_subject.jpg" width="118" height="257"><BR></td>
    <td rowspan=2 align="left"><p><b>&raquo;</b> <a href="http://xcombats.com/item/">��������</a> /
      <h2><?=$rname?></h2>
        <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
        </p>
	  <?
	  if( $rv == 0 ) {
		 echo '�������� ���� �� �������� �����, ����� ���������� ��������<br>'; 
	  }else{
		
		
		while( $itm = mysql_fetch_array($sp) ) {
			
		//if(!isset($itm['id'])) {
			$itd = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1'));
			$itd = lookStats($itd['data']);			
			
			?><br>
	  <table width="100%" border="0" align=center cellpadding="0" cellspacing="0">
	    <tr>
	      <td align=left valign="top"><table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="inup3">
	        <tr>
	          <td width="100%"><A HREF="/item/<?=$itm['id']?>" target=_blank>
	            <?=$itm['name']?>
	            </A> <IMG SRC="http://img.xcombats.com/i/align/align0.gif" WIDTH=12 HEIGHT=15>
	            <?
				if(isset($_GET['e1'])) {
					$crd = '<small><a href="javascript:window.open(\'http://xcombats.com/item_edit_data.php?edit_item_data='.$itm['id'].'\',\'winEdi1\',\'width=850,height=400,top=400,left=500,resizable=no,scrollbars=yes,status=no\');" target="_blank">������������� �������</a> </small> &nbsp; &nbsp;';
				}
				echo $crd;
				
				if($itm['massa']>0) {
					echo '(�����: '.$itm['massa'].') ';
				}
				if(isset($itd['art'])) {
					echo '<IMG SRC=http://img.xcombats.com/i/artefact.gif WIDTH=18 HEIGHT=16 ALT="����������� ����"> ';
				}

				if(isset($po['sudba']))
				{
					echo '<img title="���� ������� ����� ������ ����� ������� � ������, ��� ������� ���. ����� ������ �� ������ ��� ������������." src="http://img.xcombats.com/i/destiny0.gif"> ';
				}

				if($itm['price1'] > 0) {
					echo '<br><b>����: '.$itm['price1'].' ��.</b>';
				}
				
				if($itm['price2'] > 0) {
					echo '<br><b style="color:SaddleBrown ">����: '.$itm['price2'].' ���.</b>';
				}
				
				if($itm['iznosMAXi'] >= 999999999) {
					echo '<br>�������������: <font color="brown">�����������</font >';
				}elseif($itm['iznosMAXi'] > 0) {
					echo '<br>�������������: 0/'.$itm['iznosMAXi'].'';
				}


				//���� �������� ��������
				if($itd['srok'] > 0)
				{
					$itm['srok'] = $itd['srok'];
				}
				if($itm['srok'] > 0)
				{
					echo '<br>���� ��������: '.timeOut($itm['srok']);
				}
				
				//����������������� �������� �����:
				if((int)$itm['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.((int)$itm['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						echo '<br>����������������� ��������: '.timeOut($efi['actionTime']);
					}
				}


/* ���������� */
$tr = '';

$t = $items['tr'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['tr_'.$n])) {
		$tr .= '<br>&bull; ';
		$tr .= $is[$n].': '.$itd['tr_'.$n];
	}
	$x++;
}

if($tr != '') {
	echo '<br><B>��������� �����������:</B>'.$tr;
}

/* ��������� �� */
$tr = '';

$t = $items['add'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['add_'.$n]) && isset($is[$n])) {
		$z = '+';
		if($itd['add_'.$n] < 1) {
			$z = '';
		}
		$tr .= '<br>&bull; '.$is[$n].': '.$z.$itd['add_'.$n];
	}
	$x++;
}

				//��������� �� (�����)
				$i = 1; $bn = array(1=>'������',2=>'�������',3=>'�����',4=>'���');
				while($i<=4)
				{
					if(isset($itd['add_mab'.$i]))
					{
						if($itd['add_mab'.$i]==$itd['add_mib'.$i] && $itm['geniration']==1)
						{
							$z = '+';
							if($itd['add_mab'.$i]<0)
							{
								$z = '';
							}
							$tr .= '<br>&bull; ����� '.$bn[$i].': '.$z.''.$itd['add_mab'.$i];
						}else{
							$tr .= '<br>&bull; ����� '.$bn[$i].': '.$itd['add_mib'.$i].'-'.$itd['add_mab'.$i];
						}
					}
					$i++;
				}

if($tr != '') {
	echo '<br><B>��������� ��:</B>'.$tr;
}

/* �������� �������� */
$tr = '';

				if(isset($itd['sv_yron_min'],$itd['sv_yron_max']))
				{
					$tr .= '<br>&bull; ����: '.$itd['sv_yron_min'].' - '.$itd['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($itd['sv_'.$n]))
					{
						$z = '+';
						if($itd['sv_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>&bull; '.$is[$n].': '.$z.''.$itd['sv_'.$n];
					}
					$x++;
				}
				if($itm['2too']==1)
				{
					$tr .= '<br>&bull; ������ ������';
				}
				if($itm['2h']==1)
				{
					$tr .= '<br>&bull; ��������� ������';
				}
				if(isset($itd['zonb']))
				{
					$tr .= '<br>&bull; ���� ������������: ';
					if($itd['zonb']>0)
					{
						$x = 1;
						while($x<=$itd['zonb'])
						{
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '&mdash;';
					}
				}

if($tr != '') {
	echo '<br><B>�������� ��������:</B>'.$tr;
}

/* ����������� */
$tr = '';

				$x = 1;
				while($x<=4)
				{
					if($itd['tya'.$x]>0)
					{
						$tyc = '�������� �����';
						if($itd['tya'.$x]>6)
						{
							$tyc = '�����';
						}
						if($itd['tya'.$x]>14)
						{
							$tyc = '����';
						}
						if($itd['tya'.$x]>34)
						{
							$tyc = '���������';
						}
						if($itd['tya'.$x]>79)
						{
							$tyc = '���������';
						}
						if($itd['tya'.$x]>89)
						{
							$tyc = '�����';
						}
						if($itd['tya'.$x]>=100)
						{
							$tyc = '������';
						}
						$tr .= '<br>&bull; '.$is['tya'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if($itd['tym'.$x]>0)
					{
						$tyc = '�������� �����';
						if($itd['tym'.$x]>6)
						{
							$tyc = '�����';
						}
						if($itd['tym'.$x]>14)
						{
							$tyc = '����';
						}
						if($itd['tym'.$x]>34)
						{
							$tyc = '���������';
						}
						if($itd['tym'.$x]>79)
						{
							$tyc = '���������';
						}
						if($itd['tym'.$x]>89)
						{
							$tyc = '�����';
						}
						if($itd['tym'.$x]>=100)
						{
							$tyc = '������';
						}
						$tr .= '<br>&bull; '.$is['tym'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x <= 4)
				{
					if($itd['add_oza'.$x]>0)
					{
						$tyc = '������';
						if($itd['add_oza'.$x] == 4)
						{
							$tyc = '��������������';
						}
						if($itd['add_oza'.$x] == 2)
						{
							$tyc = '����������';
						}
						if($itd['add_oza'.$x] == 3)
						{
							$tyc = '�������';
						}
						if($itd['add_oza'.$x] == 5)
						{
							$tyc = '������������';
						}
						if($tyc != '') {
							$tr .= '<br>&bull; '.$is['oza'.$x].': '.$tyc;
						}
					}
					$x++;
				}

				if(isset($itd['free_stats']) && $itd['free_stats']>0)
				{
					echo '<br><b>��������� �������������:</b><br>&bull; ��������� �������������: '.$itd['free_stats'];
				}

if($tr != '') {
	echo '<br><B>�����������:</B>'.$tr;
}
				$is2 = '';

				if(isset($itd['complect']))
				{
					$is2 .= '<br><i>�������������� ����������:</i>';
				}
				if(isset($itd['complect']))
				{
					//�� ������������
					$com1 = array('name'=>'����������� ��������','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT * FROM `complects` WHERE `com` = "'.$itd['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($itmc = mysql_fetch_array($spc))
					{
						$com1['name'] = $itmc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$itmc['x'].'</font>: ';
						//�������� ���������
						$i1c = 0; $i2c = 0;
						$i1e = lookStats($itmc['data']);
						while($i1c<count($items['add']))
						{
							if(isset($i1e[$items['add'][$i1c]]))
							{
								$i3c = $i1e[$items['add'][$i1c]];
								if($i3c>0)
								{
									$i3c = '+'.$i3c;
								}
								if($i2c>0)
								{
									$com1['text'] .= '&nbsp; &nbsp; '.$is[$items['add'][$i1c]].': '.$i3c;
								}else{
									$com1['text'] .= $is[$items['add'][$i1c]].': '.$i3c;
								}								
								$com1['text'] .= '<br>';
								$i2c++;
							}
							$i1c++;
						}
						unset($i1c,$i2c,$i3c);
						$com1['x']++;
					}
					$is2 .= '<br>&bull; ����� ���������: <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				$is2 .= '<small style="font-size:10px;">';
				
				if($itm['info']!='')
				{
					$is2 .= '<div><b>��������:</b></div><div>'.$itm['info'].'</div>';
				}
				
				if($itd['info']!='')
				{
					$is2 .= '<div>'.$itd['info'].'</div>';                                        
				}
				
				if($itm['max_text']-$itm['use_text'] > 0) {
					$is2 .= '<div>���������� ��������: '.($itm['max_text']-$itm['use_text']).'</div>';
				}
				
				if(isset($itd['noremont']))
				{
					$is2 .= '<div style="color:brown;">������� �� �������� �������</div>';
				}
				
				if(isset($itd['frompisher']) && $itd['frompisher']>0)
				{
					$is2 .= '<div style="color:brown;">������� �� ����������</div>';
				}
				
				if($itm['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">������� ����� ������ ��� ������ �� ����������</div>';
				}				
				
				$is2 .= '</small>';
				
echo $is2;

?>
	            <BR>
	            <? /*

<B>��������� �����������:</B><BR>&bull; �������: 4<BR>&bull; ������������: 16<BR>&bull; ����: 16<BR>
<B>��������� ��:</B><BR>&bull; ��. ����������� (%): +30<BR>&bull; ��������: +3<BR>&bull; ����: +5<BR>
<B>�������� ��������:</B><BR>&bull; ����: 8 - 18<BR>&bull; ��. ������ ����������� (%): 30<BR>&bull; ��. ������������ ����� (%): 50<BR>&bull; ���������� �������� ������: 2<BR>&bull; ���� ������������: +<BR>

<B>�����������:</B><BR>&bull; ������� �����: ����<BR>&bull; ������� �����: ���������<BR>&bull; �������� �����: �����<BR>&bull; ������� �����: ����<BR>



<small>�������� �������� �� 10�� ������</small><BR>

*/
?></td>
	          <td align="center" valign="top" style='padding: 0,2,0,5'><a title="<?=$itm['name']?>" href="/item/<?=$itm['id']?>"><SPAN style='background-color: #E0E0E0'><img src="http://img.xcombats.com/i/items/<?=$itm['img']?>" alt="<?=$itm['name']?>" name="image" border="0"></SPAN></a></td>
	          </tr>
	        </table>
        </tr>
      </table>
	  <?
		} 
	  }
	  ?>
      <p align="center">&nbsp;</p>
        <BR>
        <p>
          <!-- End of text -->
    <td style='padding-left: 3' align=right><img id="imright2" height=144 src="http://img.xcombats.com/i/encicl/pict_subject.jpg" width=139 border=0></td>
      <td valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr valign=top>
    <td valign="top">
    
    <!-- -->
    
    <b><span style="COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 11pt;">��������</span></b><br>
    <table width="100%" height="11" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td width="12" align="left"><img src="http://img.xcombats.com/ram12_33.gif" width="12" height="11"></td>
		<td style="background-image:url(http://img.xcombats.com/ram12_34.gif); background-repeat:repeat-x; background-position:0 2px;"></td>
		<td width="13" align="right"><img src="http://img.xcombats.com/ram12_35.gif" width="13" height="11"></td>
	</tr>
    </table><br>
    <b>��������</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i1">�������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i2">�������� � ���</a>&nbsp;<br>
    <b>����������</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i3">����������</a>&nbsp;<br>
    <b>������</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i6">������� �����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i32">������ �����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i7">�����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i8">������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i9">�����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i10">��������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i11">�����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i12">������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i13">�����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i14">������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i15">�����</a>&nbsp;<br>
    <b>������</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i16">����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i17">������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i18">�������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i19">����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i20">������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i21">������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i22">����� � ������</a>&nbsp;<br>
    <b>������</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i23">����������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i24">���������� ��������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i25">�������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i26">����</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i27">������ ��������</a>&nbsp;<br>
    <b>����</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i28">����</a>&nbsp;<br> 
    <b>��������� ������</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i29">������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i30">��������</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i31">������</a>&nbsp;<br>
    <br><br><br> 
    <!-- -->
    
    </td>
    <td valign="bottom" style="padding-bottom:50" align="right"><IMG height=236 src="http://img.xcombats.com/i/encicl/pictr_subject.jpg" width=128 border=0></td>
    <td width="23" valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;</td>
  </tr>
</table>
<? } ?>
</td>
<td></td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor=#000000>
  <TR>
    <TD colspan=3 width="100%" height=13 background="http://img.xcombats.com/i/encicl/ln_footer.jpg"></TD>
  </TR>
  <tr valign=top>
    <td width="20%"><div align="center">
&nbsp;
      </div></td>
    <td align=center valign=middle><div align="center" style="padding: 5px 0px; height: 32px; box-sizing: border-box;"><NOBR><span class="style6">Copyright � <?=date('Y')?> �www.xcombats.com�</span></NOBR><br><Br></div></td>
    <td width="20%"></td>
  </tr>
</table>
</body>
</html>
