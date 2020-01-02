<?
$u->stats = $u->getStats($u->info['id'],0);
?>
<link rel="stylesheet" href="/pay/main.css">
<script type="text/javascript" src="/scripts/psi.js"></script>
<div class="pm" style="background-color:#eccb4d">
	<b style="float:left;">Благословление Ангелов:</b>
    <span style="float:right"><? if($u->info['id']>0){ echo $u->microLogin($u->info['id'],1); } ?></span>
</div>
<div class="pm2" style="background-color:#f6e7ab">
	<center>
    	Если вы не нашли подходящий раздел или услугу, вы можете обратиться к Администрации через e-mail:<br />
    	<a href="mailto:support@xcombats.com">support@xcombats.com</a>, в теме письма напишите "Благословление Ангелов".
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
	$('#prc'+id).html( val + '.00 екр.' );
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
	$day = array( '7 дней','30 дней','1 год' );
	
	if($u->bank['id'] < 1 ) {
		$er = 'Авторизируйтесь в банке прежде чем приобрести Благославление';
	}elseif( $id <= $u->stats['silver'] ) {
		$er = 'На вас уже есть Благословление Ангелов такого же уровня или хуже';
	}elseif(isset($prc) || $prc <= 0) {
		if( $prc <= $u->bank['money2'] ) {
			$er = 'Вы успешно приобрели Благословление Ангелов '.$id.' уровня на '.$day[$val].' за '.$prc.'.00 екр.';
			//
			$u->bank['money2'] -= $prc;
			//
			$timeuse = time(); //1 день
			$days = 6; //неделя
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
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `name` = "Благословление Ангелов" AND `uid` = "'.$u->info['id'].'" AND `delete` = 0');
			//}
			//
			mysql_query('INSERT INTO `eff_users` (
				`id_eff` , `uid` , `name` , `data` , `overType` , `timeUse` , `no_Ace`
			) VALUES (
				"'.$mid.'", "'.$u->info['id'].'", "Благословление Ангелов", "add_silver='.$id.'|timesilver='.$days.'", "30" ,"'.$timeuse.'","1"
			)');
			//
			mysql_query('UPDATE `bank` SET `money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
		}else{
			$er = 'Пополните баланс. У вас недостаточно екр., требуется '.$prc.'.00 екр.';
		}
	}else{
		$er = 'Выбранное Благословление Ангелов не найдено';
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
    <b>Состояние счета:</b> <?=$u->bank['money2'].' екр.'?><br />
    № <?=$u->bank['id']?> <a href="/pay.back.php?buy_ekr" target="_blank"><img title="Купить Еврокредиты онлайн" style="vertical-align:sub;display:inline-block;cursor:pointer" src="/buy_ekr.png"></a>
    <? }else{ echo '<center><b style="color:red">Авторизируйтесь в банке</b></center>'; } ?>
    </td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago1.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago2.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago3.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago4.gif" /></td>
    <td style="border-left:1px solid #eccb4d" align="center" valign="middle"><img src="http://img.xcombats.com/blago/blago5.gif" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Скорость передвижения&nbsp;<strong>+20%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Скорость восстановления Здоровья и Маны&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Скидка в государственном магазине&nbsp;<strong>+5%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Шанс выпадения зубов&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Нет ослабления после боя</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Бонус к получаемому опыту&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Бонус к получаемой репутации в подземельях<strong> +50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Задержка на посещение подземелий&nbsp;<strong>-30%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Дополнительный&nbsp;<strong>бросок</strong>&nbsp;вероятности на выпадение дропа в подземельях</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Уменьшение задержки на телепортацию между городами на&nbsp;<strong>50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Экипировка&nbsp;<strong>не ломается</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Грузоподьемность <b>+50%</b></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Комиссия на аукционе&nbsp;<strong>2.5%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Скидка на ремонт&nbsp;<strong>50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Бонус к получаемому опыту&nbsp;<strong>+50% (дополнительно)</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Увеличение лимита получаемого опыта&nbsp;<strong>+100%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Бонус к получаемому клановому опыту&nbsp;<strong>+50%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">Скидка в магазинах при покупке за еврокредиты<strong> +5%</strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;"><strong>Удваивает</strong> получаемые зубы за бой<strong></strong></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">&nbsp;</td>
    <td align="center" valign="middle" bgcolor="#fae492" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><img src="http://img.xcombats.com/good.png" width="15" height="15" /></td>
  </tr>
  <tr class="selz1">
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc1">4.00 екр.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc2">8.00 екр.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc3">14.00 екр.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc4">30.00 екр.</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;" id="prc5">60.00 екр.</td>
  </tr>
  <tr>
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">&nbsp;</td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
    <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(1);" name="sel1" id="sel1">
        <option value="0">7 дней</option>        
        <option value="1">30 дней</option>        
        <option value="2">1 год</option>
    </select>
    </td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(2);" name="sel2" id="sel2">
        <option value="0">7 дней</option>
        <option value="1">30 дней</option>
        <option value="2">1 год</option>
      </select>
    </span></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(3);" name="sel3" id="sel3">
        <option value="0">7 дней</option>
        <option value="1">30 дней</option>
        <option value="2">1 год</option>
      </select>
    </span></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(4);" name="sel4" id="sel4">
        <option value="0">7 дней</option>
        <option value="1">30 дней</option>
        <option value="2">1 год</option>
      </select>
    </span></td>
    <td align="center" valign="middle" class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><span class="selz2" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;">
      <select style="background-color:#eccb4d;color:#895a00;" onchange="testPrice(5);" name="sel5" id="sel5">
        <option value="0">7 дней</option>
        <option value="1">30 дней</option>
        <option value="2">1 год</option>
      </select>
    </span></td>
  </tr>
  <tr>
    <td style="border-top:1px solid #eccb4d;padding-top:10px;padding-bottom:10px;">&nbsp;</td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input class="btnnew" type="submit" name="button1" id="button1" value="Купить" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button2" id="button2" class="btnnew" value="Купить" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button3" id="button3" class="btnnew" value="Купить" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button4" id="button4" class="btnnew" value="Купить" /></td>
    <td align="center" valign="middle" style="border-left:1px solid #eccb4d;border-top:1px solid #eccb4d;"><input type="submit" name="button5" id="button5" class="btnnew" value="Купить" /></td>
  </tr>
</table>
</form>
<?
	}else{
		echo '<center><br><br>Для просмотра раздела необходимо авторизироваться с <a href="/">Главной страницы</a><br><br></center>';
	}
	?>
    </div>
</div>