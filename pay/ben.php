<?
$u->stats = $u->getStats($u->info['id'],0);
?>
<link rel="stylesheet" href="/pay/main.css">
<script type="text/javascript" src="/scripts/psi.js"></script>
<div class="pm" style="background-color:#eccb4d">
	<b style="float:left;">�������������� �������:</b>
    <span style="float:right"><? if($u->info['id']>0){ echo $u->microLogin($u->info['id'],1); } ?></span>
</div>
<div class="pm2" style="background-color:#f6e7ab">
	<center>
    	���� �� �� ����� ���������� ������ ��� ������, �� ������ ���������� � ������������� ����� e-mail:<br />
    	<a href="mailto:support@xcombats.com">support@xcombats.com</a>, � ���� ������ �������� "�������������� �������".
    </center>
    <div>
	<?
	if($u->info['id'] > 0) {
?>
<br />
<style>
.selz1:hover { background-color:#fae492; }
.selz2:hover { background-color:#eccb4d; }
.btnnew {
	cursor:pointer;
	background-color:#dddddd;
	color:#191970;
	border:1px solid #b0b0b0;
	padding:2px 10px 2px 10px;
	margin:1px 3px 1px 3px;
}
.btnnew:hover {
	background-color:#d4d2d2;
}
</style>
<script>
function testPrice(id) {
	var val = 0;
	if( id == 1 ) {
		if( $('#sel'+id).val() == 0 ) {
			val = 4;
		}else if( $('#sel'+id).val() == 1 ) {
			val = 10;
		}else if( $('#sel'+id).val() == 2 ) {
			val = 100;
		}
	}else if( id == 2 ) {
		if( $('#sel'+id).val() == 0 ) {
			val = 8;
		}else if( $('#sel'+id).val() == 1 ) {
			val = 18;
		}else if( $('#sel'+id).val() == 2 ) {
			val = 180;
		}
	}else if( id == 3 ) {
		if( $('#sel'+id).val() == 0 ) {
			val = 14;
		}else if( $('#sel'+id).val() == 1 ) {
			val = 50;
		}else if( $('#sel'+id).val() == 2 ) {
			val = 500;
		}
	}else if( id == 4 ) {
		if( $('#sel'+id).val() == 0 ) {
			val = 30;
		}else if( $('#sel'+id).val() == 1 ) {
			val = 98;
		}else if( $('#sel'+id).val() == 2 ) {
			val = 980;
		}
	}else if( id == 5 ) {
		if( $('#sel'+id).val() == 0 ) {
			val = 60;
		}else if( $('#sel'+id).val() == 1 ) {
			val = 198;
		}else if( $('#sel'+id).val() == 2 ) {
			val = 1980;
		}
	}
	$('#prc'+id).html( val + '.00 ���.' );
}
</script>
<?
if( isset($_POST['sel1']) ) {
	$er = '';
	
	$id = 1;
	if(isset($_POST['button2'])) { $id = 2; }
	if(isset($_POST['button3'])) { $id = 3; }
	if(isset($_POST['button4'])) { $id = 4; }
	if(isset($_POST['button5'])) { $id = 5; }
	
	$val = round((int)$_POST['sel'.$id]);
	if($val == 0) { $val = 0; }
	if($val == 1) { $val = 1; }
	if($val == 2) { $val = 2; }
	
	$prc = array(
		1 => array(4,10,100),
		2 => array(8,18,180),
		3 => array(14,50,500),
		4 => array(30,98,980),
		5 => array(60,198,1980)	
	);
	
	$prc = $prc[$id][$val];
	$day = array( '7 ����','30 ����','1 ���' );
	
	if($u->bank['id'] < 1 ) {
		$er = '��������������� � ����� ������ ��� ���������� ��������������';
	}elseif( $id <= $u->stats['silver'] ) {
		$er = '�� ��� ��� ���� �������������� ������� ������ �� ������ ��� ����';
	}elseif(isset($prc) || $prc <= 0) {
		if( $prc <= $u->bank['money2'] ) {
			$er = '�� ������� ��������� �������������� ������� '.$id.' ������ �� '.$day[$val].' �� '.$prc.'.00 ���.';
			//
			$u->bank['money2'] -= $prc;
			//
			$timeuse = time(); //1 ����
			$days = 6; //������
			if($val == 1) { $days = 29; }
			if($val == 2) { $days = 364; }
			$timeuse += 86400 * $days;
			//
			if( $id == 1 ) {
				$mid = 276;
			}elseif( $id == 2 ) {
				$mid = 312;
			}elseif( $id == 3 ) {
				$mid = 313;
			}elseif( $id == 4 ) {
				$mid = 314;
			}elseif( $id == 5 ) {
				$mid = 315;
			}
			//
			//if( $u->stats['silver'] > 0 ) {
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `name` = "�������������� �������" AND `uid` = "'.$u->info['id'].'" AND `delete` = 0');
			//}
			//
			mysql_query('INSERT INTO `eff_users` (
				`id_eff` , `uid` , `name` , `data` , `overType` , `timeUse` , `no_Ace`
			) VALUES (
				"'.$mid.'", "'.$u->info['id'].'", "�������������� �������", "add_silver='.$id.'|timesilver='.$days.'", "30" ,"'.$timeuse.'","1"
			)');
			//
			mysql_query('UPDATE `bank` SET `money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
		}else{
			$er = '��������� ������. � ��� ������������ ���., ��������� '.$prc.'.00 ���.';
		}
	}else{
		$er = '��������� �������������� ������� �� �������';
	}	
	
	if($er != '') {
		echo '<div style="color:red" align="center"><b>'.$er.'</b></div><br>';
	}
}
?>
<form action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="350" bgcolor="#fae492">
    <? if( $u->bank['id'] > 0 ) { ?>
    <b>��������� �����:</b> <?=$u->bank['money2'].' ���.'?><br />
    � <?=$u->bank['id']?> <a href="/pay.back.php?buy_ekr" target="_blank"><img title="������ ����������� ������" style="vertical-align:sub;display:inline-block;cursor:pointer" src="/buy_ekr.png"></a>
    <? }else{ echo '<center><b style="color:red">��������������� � �����</b></center>'; } ?>
    </td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago1.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago2.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago3.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago4.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago5.gif" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">�������� ������������&nbsp;<strong>+20%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">�������� �������������� �������� � ����&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">������ � ��������������� ��������&nbsp;<strong>+5%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">���� ��������� �����&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">��� ���������� ����� ���</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">����� � ����������� �����&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">����� � ���������� ��������� � �����������<strong> +50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">�������� �� ��������� ����������&nbsp;<strong>-30%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">��������������&nbsp;<strong>������</strong>&nbsp;����������� �� ��������� ����� � �����������</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">���������� �������� �� ������������ ����� �������� ��&nbsp;<strong>50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">����������&nbsp;<strong>�� ��������</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">���������������� <b>+50%</b></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">�������� �� ��������&nbsp;<strong>2.5%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">������ �� ������&nbsp;<strong>50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">����� � ����������� �����&nbsp;<strong>+50% (�������������)</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">���������� ������ ����������� �����&nbsp;<strong>+100%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">����� � ����������� ��������� �����&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">������ � ��������� ��� ������� �� �����������<strong> +5%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;"><strong>���������</strong> ���������� ���� �� ���<strong></strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc1">4.00 ���.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc2">8.00 ���.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc3">14.00 ���.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc4">30.00 ���.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc5">60.00 ���.</td>
  </tr>
  <tr>
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
    <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(1);" name="sel1" id="sel1">
        <option value="0">7 ����</option>        
        <option value="1">30 ����</option>        
        <option value="2">1 ���</option>
    </select>
    </td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(2);" name="sel2" id="sel2">
        <option value="0">7 ����</option>
        <option value="1">30 ����</option>
        <option value="2">1 ���</option>
      </select>
    </span></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(3);" name="sel3" id="sel3">
        <option value="0">7 ����</option>
        <option value="1">30 ����</option>
        <option value="2">1 ���</option>
      </select>
    </span></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(4);" name="sel4" id="sel4">
        <option value="0">7 ����</option>
        <option value="1">30 ����</option>
        <option value="2">1 ���</option>
      </select>
    </span></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(5);" name="sel5" id="sel5">
        <option value="0">7 ����</option>
        <option value="1">30 ����</option>
        <option value="2">1 ���</option>
      </select>
    </span></td>
  </tr>
  <tr>
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">&nbsp;</td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input class="btnnew" type="submit" name="button1" id="button1" value="������" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button2" id="button2" class="btnnew" value="������" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button3" id="button3" class="btnnew" value="������" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button4" id="button4" class="btnnew" value="������" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button5" id="button5" class="btnnew" value="������" /></td>
  </tr>
</table>
</form>
<?
	}else{
		echo '<center><br><br>��� ��������� ������� ���������� ���������������� � <a href="/">������� ��������</a><br><br></center>';
	}
	?>
    </div>
</div>