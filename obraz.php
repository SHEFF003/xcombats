<?php
define('GAME',time());

include('_incl_data/class/__db_connect.php');

if(!isset($_GET['id'])) {
	$urla = explode('?',$_SERVER["REQUEST_URI"]);
	$url = explode('/',$urla[0]);

	$_GET['id'] = round((int)$url[2]);
}

/* ������� */
$rz = array(
	'',
	'������� �����������',
	'������� �����������' 
);

// ���������� 3� �����������? True - ���������� � ����� ������, False - ������ ���� ����������.
$img3dShow = true; 

/* ������������ */
$u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));

/* ������� */
$itm = mysql_fetch_array(mysql_query('SELECT * FROM `obraz` WHERE `id` = "'.mysql_real_escape_string($_GET['id']).'" LIMIT 1'));


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

$itd = lookStats($itm['tr']);
if( $itm['name'] == '' ) {
	$itm['name'] = '����� �'.$itm['id'].'';
}
if( $itm['history'] == '' ) {
	$itm['history'] = '�������� �������� � ��� ����������...';
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
	$rz[$rt[$itm['type']]][1] = '';
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>������ ���������� ���� | ���������� <? if(isset($itm['id'])) { ?>| <?=$rz[$rt[$itm['type']]][1]?> | <?=$itm['name']?><? } ?></title>
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
      <p><b>�</b> <a href="http://xcombats.com/shadow/">������</a> / <b><i><?=$itm['name']?></i></b>  
      <h2><?=$itm['name']?></h2>
      <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
      </p>
    <? if( $img3dShow==false && (file_exists('../img.xcombats.com/chars/enc/big'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true) OR $img3dShow==true ) { ?>  
	<div align="center"> 
        <table width="323"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"  width="12" height="10"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
            <td width="100%" background="http://img.xcombats.com/i/encicl/ram12_34.gif"></td>
            <td width="12" height="10" align="right"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
          </tr>
          <tr>
            <td width="12" background="http://img.xcombats.com/i/encicl/line_left_13.gif">&nbsp;</td>
            <td width="323"><img style="display:block" src="<? if( file_exists('../img.xcombats.com/chars/enc/'.$itm['sex'].'/big'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true ) { ?>http://img.xcombats.com/chars/enc/<?=$itm['sex']?>/big<?=str_replace('.gif','',str_replace('.png','',$itm['img']))?>.jpg<? }else{ ?>http://img.xcombats.com/chars/enc/back.jpg<? } ?>" alt="" width="323" height="600" border=1 id="bigim"></td>
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
<BR><BR>
<u>������� ������:</u><br><?=$itm['history']?>
</td>
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
	$rname = '���������� ������� ����������� �����';
	$html = '';
	//
	if( $rv > 0 ) {
		
		if($rv == 1) {
			//������� �����������
			$sp = mysql_query('SELECT * FROM `obraz` WHERE `sex` = 0 AND `standart` = 1');
		}elseif($rv == 2) {
			//������� �����������
			$sp = mysql_query('SELECT * FROM `obraz` WHERE `sex` = 1 AND `standart` = 1');
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
      <td width="29" rowspan=2 background="http://xcombats.com/forum_script/img/leftground.jpg"><img src="http://img.xcombats.com/i/encicl/obrzz_08.jpg" width="29" height="256"></td>
      <td width="218" height="257" align="left"><img id="imleft2" src="http://img.xcombats.com/i/encicl/obrzz_04.jpg" width="118" height="257"><BR></td>
    <td rowspan=2 align="left"><p><b>&raquo;</b> <a href="http://xcombats.com/shadow/">������</a> /
      <h2><?=$rname?></h2>
        <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
        </p>
	  <?
	  if( $rv == 0 ) {
		 echo '�������� ���� �� �������� �����, ����� ���������� ������<br>'; 
	  }else{
		
		
		while( $itm = mysql_fetch_array($sp) ) {
			if( $itm['name'] == '' ) {
				$itm['name'] = '����� �'.$itm['id'];
			}
			?>
	  <table width="120" style="float:left;" border="0" align=center cellpadding="0" cellspacing="0">
	    <tr>
	      <td align=left valign="top"><table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="inup3">
	        <tr>
	          <td align="center" valign="top" style='padding: 0,2,0,5'><A HREF="/shadow/<?=$itm['id']?>" target=_blank><?=$itm['name']?><br><br></A><a title="<?=$itm['name']?>" href="/shadow/<?=$itm['id']?>"><SPAN style='background-color: #E0E0E0'><img src="http://img.xcombats.com/i/obraz/<?=$itm['sex']?>/<?=$itm['img']?>" alt="<?=$itm['name']?>" name="image" border="0"></SPAN></a></td>
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
    <td style='padding-left: 3' align=right><img id="imright2" height=144 src="http://img.xcombats.com/i/encicl/obrzz1.jpg" width=139 border=0></td>
      <td valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr valign=top>
    <td valign="top">
    
    <!-- -->
    
    <b><span style="COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 11pt;">������ ����������</span></b><br>
    <table width="100%" height="11" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td width="12" align="left"><img src="http://img.xcombats.com/ram12_33.gif" width="12" height="11"></td>
		<td style="background-image:url(http://img.xcombats.com/ram12_34.gif); background-repeat:repeat-x; background-position:0 2px;"></td>
		<td width="13" align="right"><img src="http://img.xcombats.com/ram12_35.gif" width="13" height="11"></td>
	</tr>
    </table><br>
    <b>�������</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/shadow/i1">�����������</a>&nbsp;<br>
    <b>�������</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/shadow/i2">�����������</a>&nbsp;<br>
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
