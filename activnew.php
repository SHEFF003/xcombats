<?php

		function zmgo($v) {
			if($v > 1000) {
				$v = 1000;
			}
			$r = 0;
			$r = (1-( pow(0.5, ($v/250) ) ))*100;		
			//$r = round($v/800*100);
			//$r = round($r/80*100);
			return $r;
		}
		
		$i = 1;
		while( $i <= 100 ) {
			echo (1010-$i*10).' = '.round(zmgo(1010-$i*10),2).'%<br>';
			$i++;
		}

die();


$refUrl = mysql_fetch_array(mysql_query('SELECT * FROM `referal_url` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
if(!isset($refUrl['id'])) {
	mysql_query('INSERT INTO `referal_url` (`uid`,`url`) VALUES (
		"'.$u->info['id'].'","xcombats.com/r'.$u->info['id'].'"
	)');
	$refUrl = mysql_fetch_array(mysql_query('SELECT * FROM `referal_url` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
}

function ref_url($r) {
	$r = str_replace('0','A',$r);
	$r = str_replace('1','b',$r);
	$r = str_replace('2','C',$r);
	$r = str_replace('3','d',$r);
	$r = str_replace('4','E',$r);
	$r = str_replace('5','f',$r);
	$r = str_replace('6','D',$r);
	$r = str_replace('7','g',$r);
	$r = str_replace('8','H',$r);
	$r = str_replace('9','s',$r);
	return $r;
}
//$refUrl['url'] = ref_url($refUrl['url']);

$pf = 0;

$html_ref = '';
$i_ref = 0;

$sp = mysql_query('SELECT `id`,`online`,`activ`,`cityreg` FROM `users` WHERE `host_reg` = "'.$u->info['id'].'"');
while( $pl = mysql_fetch_array($sp) ) {
	
	$sp2 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `host_reg` = "'.$pl['id'].'" LIMIT 1'));
	$sp2 = 0+$sp2[0];
	$html_ref .= ($i_ref + 1).'. '.$u->microLogin($pl['id'],1).'';
	if($sp2==0) {
		$html_ref .= ' <font color=grey>['.$sp2.'/1] �������� ������ ���������� ������ ��������</font>';
	}else{
		$html_ref .= ' <font color=green>[1/1] ���������!</font>';
		$pf += 10;
	}
	$pf += 23;
	$html_ref .= '<br>';
	$i_ref++;
}

if( $pf >= 99 ) {
	$pf = 100;
}

if($html_ref == '') {
	$html_ref = '<center>�� ����� ������ ��� ����� �� �����������������</center>';
}

if(isset($_GET['activated'])) {
	if($pf == 100) {
		$er = '�� ������ ��������� �� E-mail � ������ ���� ��������, � ��������� ������ ������ ������������ ���������!';
	}else{
		$er = '������� ��������� �� <small>'.$pf.'</small>/100%! ��������� ������� ���������!';
	}
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>�����: ������ ���������� ����</title>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
</head>

<body style="height:100%; background-color:#E2E0E0;">
<div align="center" style="font-size:18px;">
  <div style="position:relative"><b><img src="http://img.xcombats.com/i/align/align1.gif" width="12" height="15"> ��������� ��������� <img src="http://img.xcombats.com/i/align/align3.gif" width="12" height="15"></b></div>
  <div><img src="http://img.xcombats.com/img/banner.png" width="568" height="74"></div>
</div>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">
    <b style="color:#550000">
    	��� ��������� ��������� ���������� ���������� 3 �������<br>
        <small>(������ ������� ������ ���������� ���� �� ������ ������)</small>
    </b><br>
    <div id=hint5 style='visibility: hidden'></div>
    </td>
  </tr>
  <tr>
    <td>
    <?
	if(isset($er) && $er != '') {
		echo '<hr style="border-color:#CCC;"><center><font color="#CC0000"><b>'.$er.'</b></font></center>';
	}
	?>
    <hr style="border-color:#CCC;">
    <div style="padding:10px;">
    ��������������� ������: <input name="urlref" style="font-size:12px;width:200px;text-align:center;" type="text" value="<?=$refUrl['url']?>" ><br>
    <small>�������� � ������ ���������� ��� ip �� �����������, � ������ ������� ������ ���������� �������� ��� ��������� ������ �������, ������ ����� ������ ����� � �������� �������.</small>
    <br><br>
        <div><?=$html_ref?></div>
    </div>
    <br>
    <div style="border:1px solid #CCC;position:relative;">
    	<div style="position:absolute;left:10px;top:1px;">������� ��������� �� <?=$pf?>%</div>
    	<img src="http://img.xcombats.com/1x1.gif" style="background-color:#DEE;height:20px;width:<?=$pf?>%;display:inline-block;vertical-align:bottom;">
    </div>
    <br>
    <center><? if( $pf == 100 ) { ?>
    	<input onClick="location.href='/bk?activated'" type="submit" class="btnnew3" value="������������ ���������!"><? }else{ ?>
        <input onClick="alert('�� ������ ���������� ���� ��������� � ������ �� ��� ������ ���������� ���� �� �� ������ ��������!');" type="submit" class="btnnew3" value="������������ ���������!"><? } ?>
    </center>
    </td>
  </tr>
  <tr>
    <td align="center"><hr style="border-color:#CCC;">������ ���������� ���� &copy; 2014-<?=date('Y')?>, �www.xcombats.com�� &nbsp; &nbsp; </font></td>
  </tr>
</table>

</body>
</html>