<?
if( !defined('GAME') || $u->room['file']!='birja' ) {
	die();
}
?>
<style type="text/css">
.pH3 {COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
</style>
<TABLE width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><div align="center" class="pH3">Валютная Биржа<br><font color="red"><b><small>(работает в тестовом режиме)</small></b></font></div>
    <?
	if( !isset($u->bank['id']) ) {
		?>
	<form name="F1" method="post">
        <div align="center">Укажите номер вашего счета в банке и пароль к нему.<br />
          <br />
          <?
            if(isset($_POST['bank']) && isset($u->bank['id']))
            {
                echo '<font color="red"><b>Банковский счет пуст, вход в магазин запрещен</b></font>';
            }elseif(isset($_POST['bank']) && !isset($u->bank['id']))
            {
                echo '<font color="red"><b>Неверный пароль от банковского счета.</b></font>';
            }
            ?>
          <br />
          <br />
          <style>
            .w1{position:absolute;z-index:1102;}.1x1 {
        width:1px;
        height:1px;
        display:block;
    }.wi1s0{width:5px;height:6px;background:url(http://img.anticombats.com/i/bneitral_03.gif) 1px 0px no-repeat;}.wi1s1{height:6px;background:url(http://img.anticombats.com/i/bneitral_05.gif);}.wi1s2{width:5px;height:6px;background:url(http://img.anticombats.com/i/bneitral_03.gif) -2px 0px no-repeat;}.wi1s3{width:5px;background:url(http://img.anticombats.com/i/bneitral_17.gif) 1px 0px repeat-y;}.wi1s4{width:5px;background:url(http://img.anticombats.com/i/bneitral_19.gif) -1px 0px repeat-y;background-position:right;background-color:#675600;}.wi1s5{width:5px;background:url(http://img.anticombats.com/i/bneitral_19.gif) 0px 0px repeat-y;}.wi1s6{height:6px;background:url(http://img.anticombats.com/i/bneitral_05.gif);}.wi1s7{background:#ddd5bf;}.wi1s8{background:url(http://img.anticombats.com/i/bneitral_05.gif);}.wi1s9{width:5px;height:6px;background:url(http://img.anticombats.com/i/bneitral_19.gif);}.wi1s10{background-color:#b1a993;white-space:nowrap;color:#003388;padding:2px 2px 2px 7px;min-width:140px;}.wi1s10 text{cursor:move;}.wi1s10 img{cursor:pointer;}.wi1s11{}.wi1s12{}
            </style>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="wi1s0"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
              <td class="wi1s1"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
              <td class="wi1s2"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
            </tr>
            <tr>
              <td class="wi1s3"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
              <td><table width="300" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td bgcolor="#B1A996"><div align="center"><strong>Счёт в банке</strong></div></td>
                </tr>
                <tr>
                  <td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>Выберите счёт и введите пароль<br />
                    <select name="bank" id="bank">
                      <?
                                $scet = mysql_query('SELECT `id` FROM `bank` WHERE `block` = "0" AND `uid` = "'.$u->info['id'].'"');
                                while ($num_scet = mysql_fetch_array($scet)) 
                                {
                                     echo "<option>".$u->getNum($num_scet['id'])."</option>";
                                }
                                ?>
                    </select>
                    <input style="margin-left:5px;" type="password" name="bankpsw" id="bankpsw" />
                    <label></label>
                    </small>
                    <input class="btnnew" style="margin-left:3px;" type="submit" name="button" id="button" value=" ok " />
                  </div></td>
                </tr>
              </table></td>
              <td class="wi1s4"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
            </tr>
            <tr>
              <td class="wi1s5"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
              <td class="wi1s6"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
              <td class="wi1s8"><img src="http://img.anticombats.com/1x1.gif" height="1"></td>
            </tr>
          </table>
          <br />
        </div>
    </form>
    <?
	}
	?>
    <td width="280" valign="top"><table cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%">&nbsp;</td>
        <td><table  border="0" cellpadding="0" cellspacing="0">
          <tr align="right" valign="top">
            <td><!-- -->
              <? echo $goLis; ?>
              <!-- -->
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                    <tr>
                      <td bgcolor="#D3D3D3"><img src="http://img.anticombats.com/i/move/links.gif" width="9" height="7" /></td>
                      <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.213&amp;rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.213',1); ?>">Большая торговая улица</a></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
      <br />
      <center>
      </center></td>
</table>
	<?
	if(isset($u->bank['id'])) {
		
		function testEkrbuy($ekr,$crs) {
			global $u,$c;
			//Обмен екр на кр
			$r = array(
				'o_ekr'	=> $ekr,
				'x'		=> 0
			);
			//
			$test = mysql_fetch_array(mysql_query('SELECT SUM(`buy_ekr`) FROM `birja` WHERE `curs_ekr` <= "'.$crs.'" AND `close` = 0 LIMIT 1'));
			if( $test[0] == 0 ) {
				//Нет подходящих сделок
				$r = false;
			}else{
				$sp = mysql_query('SELECT * FROM `birja` WHERE `curs_ekr` <= "'.$crs.'" AND `buy_ekr` > 0 AND `close` = 0 ORDER BY `buy_ekr` ASC');	
				while( $pl = mysql_fetch_array($sp) ) {
					if( $r['o_ekr'] > 0 ) {
						$r['x']++;
						//
						$bnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$pl['uid'].'" ORDER BY `id` DESC LIMIT 1'));
						//
						$mnbuy = 0;
						if( $pl['buy_ekr'] <= $r['o_ekr'] ) {
							$mnbuy = $pl['buy_ekr'];
							$pl['close'] = time();
						}else{
							$mnbuy = $r['o_ekr'];
						}
						//
						if( $pl['uid'] != $u->info['id'] ) {
							$r['o_ekr'] -= $mnbuy;
							$pl['buy_ekr'] -= $mnbuy;
							$u->bank['money1'] -= $mnbuy * $crs;
							$u->bank['money2'] += $mnbuy;
							$bnk['money1'] += $mnbuy * $pl['curs_ekr'];
						}else{
							$r['o_ekr'] -= $mnbuy;
							$pl['buy_ekr'] -= $mnbuy;
							$u->bank['money1'] -= $mnbuy * $crs;
							$u->bank['money2'] += $mnbuy;
							$u->bank['money1'] += $mnbuy * $pl['curs_ekr'];
						}
						//
						mysql_query('UPDATE `birja` SET `close` = "'.$pl['close'].'" , `buy_ekr` = "'.$pl['buy_ekr'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						//
						if( $pl['uid'] != $u->info['id'] ) {
							mysql_query('UPDATE `bank` SET `money1` = "'.$bnk['money1'].'", `money2` = "'.$bnk['money2'].'" WHERE `id` = "'.$bnk['id'].'" LIMIT 1');
						}
						mysql_query('UPDATE `bank` SET `money1` = "'.$u->bank['money1'].'",`money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
					}
				}
			}
			if( $r['o_ekr'] > 0 ) {
				$ost = $r['o_ekr'];
			}elseif( $r['x'] == 0 ) {
				$ost = $ekr;
			}
			$u->bank['money1'] -= $ost * $crs;
			$u->bank['money1'] -= ($ekr/100*($c['birja_buy']));
			mysql_query('UPDATE `bank` SET `money1` = "'.$u->bank['money1'].'",`money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
			if( $ost > 0 ) {
				mysql_query('INSERT INTO `birja` (`uid`,`time`,`buy_ekr`,`sale_ekr`,`curs_ekr`) VALUES ("'.$u->info['id'].'","'.time().'","0","'.$ost.'","'.round($crs,2).'")');
			}
			return $r;
		}
		
		function testEkrsale($ekr,$crs) {
			global $u,$c;
			//Обмен кр на екр
			$r = array(
				'o_ekr'	=> $ekr,
				'x'		=> 0
			);
			//
			$test = mysql_fetch_array(mysql_query('SELECT SUM(`sale_ekr`) FROM `birja` WHERE `curs_ekr` >= "'.$crs.'" AND `close` = 0 LIMIT 1'));
			if( $test[0] == 0 ) {
				//Нет подходящих сделок
				$r = false;
			}else{
				$sp = mysql_query('SELECT * FROM `birja` WHERE `curs_ekr` >= "'.$crs.'" AND `sale_ekr` > 0 AND `close` = 0 ORDER BY `sale_ekr` ASC');	
				while( $pl = mysql_fetch_array($sp) ) {
					if( $r['o_ekr'] > 0 ) {
						$r['x']++;
						//
						$bnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$pl['uid'].'" ORDER BY `id` DESC LIMIT 1'));
						//
						$mnbuy = 0;
						if( $pl['sale_ekr'] <= $r['o_ekr'] ) {
							$mnbuy = $pl['sale_ekr'];
							$pl['close'] = time();
						}else{
							$mnbuy = $r['o_ekr'];
						}
						//
						if( $pl['uid'] != $u->info['id'] ) {
							$r['o_ekr'] -= $mnbuy;
							$pl['sale_ekr'] -= $mnbuy;
							$u->bank['money2'] -= $mnbuy;
							$u->bank['money1'] += $mnbuy * $crs;
							$bnk['money2'] += $mnbuy;
						}else{
							$r['o_ekr'] -= $mnbuy;
							$pl['sale_ekr'] -= $mnbuy;
							$u->bank['money2'] -= $mnbuy;
							$u->bank['money1'] += $mnbuy * $crs;
							$u->bank['money2'] += $mnbuy;
						}
						//
						
						mysql_query('UPDATE `birja` SET `close` = "'.$pl['close'].'" , `sale_ekr` = "'.$pl['sale_ekr'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						//
						if( $pl['uid'] != $u->info['id'] ) {
							mysql_query('UPDATE `bank` SET `money1` = "'.$bnk['money1'].'",`money2` = "'.$bnk['money2'].'" WHERE `id` = "'.$bnk['id'].'" LIMIT 1');
						}
						mysql_query('UPDATE `bank` SET `money1` = "'.$u->bank['money1'].'",`money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
					}
				}
			}
			if( $r['o_ekr'] > 0 ) {
				$ost = $r['o_ekr'];
			}elseif( $r['x'] == 0 ) {
				$ost = $ekr;
			}
			$u->bank['money2'] -= $ost;
			$u->bank['money2'] -= ($ekr/100*($c['birja_sale']));
			mysql_query('UPDATE `bank` SET `money1` = "'.$u->bank['money1'].'",`money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
			if( $ost > 0 ) {
				mysql_query('INSERT INTO `birja` (`uid`,`time`,`buy_ekr`,`sale_ekr`,`curs_ekr`) VALUES ("'.$u->info['id'].'","'.time().'","'.$ost.'","0","'.round($crs,2).'")');
			}
			return $r;
		}
		
		if(!isset($_POST['good'])) {
			if(isset($_POST['buy_kr'])) {
				$mn = round($_POST['buy_kr'],2);
				$cr = round($_POST['curs_kr'],2);
				$htmlbox = '<form method="post" action="main.php">';
				$htmlbox .= '<input type="hidden" name="buy_kr" value="'.$mn.'">';
				$htmlbox .= '<input type="hidden" name="curs_kr" value="'.$cr.'">';
				$htmlbox .= '<legend>Подтверждение продажи екр.</legend>Вы предлагаете на продажу <b style="color:red">'.$mn.' екр.</b> по цене <b style="color:red">'.$cr.' кр.</b> за один екр. на сумму <b style="color:red">'.round($cr*$mn,2).' кр.</b> Биржа возьмёт комиссию в размере <b style="color:red">'.round(($mn/100*$c['birja_buy']),2).' екр.</b> (<b style="color:red">'.$c['birja_buy'].'%</b> от количества екр. на продажу). Всего с вашего счета будет списано <b style="color:red">'.(round(($mn/100*$c['birja_buy']),2)+$mn).' екр.</b>';
				$htmlbox .= '<hr><input type="submit" name="good" value="Подтверждаю" class="btnnew"> <input type="button" onClick="location.href=\'main.php\'" name="cancel" value="Отменить" class="btnnew">';
				$htmlbox .= '</form>';
			}elseif(isset($_POST['buy_ekr'])) {
				$mn = round($_POST['buy_ekr'],2);
				$cr = round($_POST['curs_ekr'],2);
				$htmlbox = '<form method="post" action="main.php">';
				$htmlbox .= '<input type="hidden" name="buy_ekr" value="'.$mn.'">';
				$htmlbox .= '<input type="hidden" name="curs_ekr" value="'.$cr.'">';
				$htmlbox .= '<legend>Подтверждение покупки екр.</legend>Вы хотите купить <b style="color:red">'.$mn.' екр.</b> по цене <b style="color:red">'.$cr.' кр.</b> за один екр. на сумму <b style="color:red">'.round($cr*$mn,2).' кр.</b> Биржа возьмёт комиссию в размере <b style="color:red">'.round(($cr/100*$c['birja_sale']),2).' кр.</b> (<b style="color:red">'.$c['birja_sale'].'%</b> от суммы сделки в кр.). Всего с вашего счета будет списано <b style="color:red">'.(round(($cr/100*$c['birja_sale']),2)+$cr).' кр.</b>';
				$htmlbox .= '<hr><input type="submit" name="good" value="Подтверждаю" class="btnnew"> <input type="button" onClick="location.href=\'main.php\'" name="cancel" value="Отменить" class="btnnew">';
				$htmlbox .= '</form>';
			}
		}elseif(isset($_POST['buy_ekr'])) {
			$mn = round($_POST['buy_ekr'],2);
			$cr = round($_POST['curs_ekr'],2);
			//
			if( $mn < 0.01 ) {
				$u->error = 'Минимальная сумма покупки 0.01 екр.';
			}elseif( $mn/100*(100+$c['birja_buy']) > $u->bank['money1'] ) {
				$u->error = 'У вас недостаточно кр. для этой сделки! (требуется: '.($mn/100*(100+$c['birja_buy'])).' кр.)';				
			}elseif( $cr <= $c['ecrtocr'] || $cr > 60 ) {
				$u->error = 'Нельзя выставлять курс ниже, чем в банке. (Курс банка: 1 екр = '.$c['ecrtocr'].' кр., не выше 60 кр.)';
			}else{
				//
				$u->addDelo(1,$u->info['id'],'<b>'.$u->info['login'].'</b> выставил '.$cr.' кр. для покупки екр. на бирже',time(),time());
				$r = testEkrbuy($mn,$cr);
				if( $r == false ) {
					$u->error = 'На бирже сейчас нет подходящих сделок. Ваша заявка помещена в список.';
				}else{
					if( $r['o_ekr'] == 0 ) {
						$u->error = 'Приобретено '.$mn.' екр. на сумму '.round($mn * $cr,2).' кр. Заявка выполнена полностью.';
					}else{
						$u->error = 'Приобретено '.($mn-$r['o_ekr']).' екр. на сумму '.round(($mn-$r['o_ekr']) * $cr,2).' кр. Заявка выполнена частично. Осталось обработать: '.$r['o_ekr'].' екр. (Деньги поступят на ваш счет как только появится подходящая сделка)';
					}
				}
				//
			}
			//
		}elseif(isset($_POST['buy_kr'])) {
			$mn = round($_POST['buy_kr'],2);
			$cr = round($_POST['curs_kr'],2);
			//
			if( $mn < 0.01 ) {
				$u->error = 'Минимальная сумма продажи 0.01 екр.';
			}elseif( $mn/100*(100+$c['birja_sale']) > $u->bank['money2'] ) {
				$u->error = 'У вас недостаточно екр. для этой сделки! (требуется: '.($mn/100*(100+$c['birja_sale'])).' екр.)';				
			}elseif( $cr <= $c['ecrtocr'] || $cr > 60 ) {
				$u->error = 'Нельзя выставлять курс ниже, чем в банке. (Курс банка: 1 екр = '.$c['ecrtocr'].' кр. , не выше 60 кр.)';
			}else{
				//
				$u->addDelo(1,$u->info['id'],'<b>'.$u->info['login'].'</b> выставил '.$mn.' екр. для покупки кр. на бирже',time(),time());
				$r = testEkrsale($mn,$cr);
				if( $r == false ) {
					$u->error = 'На бирже сейчас нет подходящих сделок. Ваша заявка помещена в список.';
				}else{
					if( $r['o_ekr'] == 0 ) {
						$u->error = 'Проданно '.$mn.' екр. на сумму '.round($mn * $cr,2).' кр. Заявка выполнена полностью.';
					}else{
						$u->error = 'Проданно '.($mn-$r['o_ekr']).' екр. на сумму '.round(($mn-$r['o_ekr']) * $cr,2).' кр. Заявка выполнена частично. Осталось обработать: '.$r['o_ekr'].' екр. (Деньги поступят на ваш счет как только появится подходящая сделка)';
					}
				}
				//
			}
			//
		}
		
	?>
    <br>
    <?
	if( isset($u->error) && $u->error != '' ) {
		echo '<div><b><font color="red">'.$u->error.'</font></b></div>';
	}
	?>
    <center><?='№'.$u->bank['id'].': <b>'.$u->bank['money1'].'</b> кр. <b>'.$u->bank['money2'].'</b> екр.'?><a href="main.php?bank_exit=1" title="Завершить работу со счетом"><img src="http://img.anticombats.com/i/close_bank.gif"></a></center>
    <?
	if(!isset($htmlbox) || $htmlbox == '') {
	?>
    <TABLE width=100%>
      <TR>
        <TD valign=top align="center" width=50%>
            <form method="post">
            <fieldset>
              <legend><b>Купить екр. (комиссия <?=$c['birja_buy']?>%)</b> </legend>
              Купить
              <input type="text" name="buy_ekr" id="buy_ekr" size="6" maxlength="10" />
              екр. по курсу 
              <input type="text" name="curs_ekr" id="curs_ekr" size="6" maxlength="10" />
              кр. или ниже<hr>
              <center><input class="btnnew" type="submit" value="Купить екр." /></center>
            </fieldset>
            </form>
        </TD>
        <TD valign=top align=center width=50%>
            <form method="post">
            <fieldset>
              <legend><b>Продать екр. (комиссия <?=$c['birja_sale']?>%)</b> </legend>
              Продать
              <input type="text" name="buy_kr" id="buy_kr" size="6" maxlength="10" />
              екр. по курсу 
              <input type="text" name="curs_kr" id="curs_kr" size="6" maxlength="10" />
              кр. или выше<hr>
              <center><input class="btnnew" type="submit" value="Продать екр." /></center>
            </fieldset>
            </form>
        </TD>
      </TR>
    </TABLE>
    <? }else{
		echo '<form method="post"><br><center><fieldset>'.$htmlbox.'</fieldset></center></form>';
	}
	?>
    <br>
<fieldset>
  <legend><b>Просмотр заявок</b> </legend>
  <center>
    <? if(!isset($_GET['myzv'])) { ?>
  	<input class="btnnew" type="button" value="Мои заявки" onclick="location.href='main.php?myzv'">
    <input class="btnnew" type="button" value="Обновить" onclick="location.href='main.php'">
    <? }else{ ?>
  	<input class="btnnew2" type="button" value="Все заявки" onclick="location.href='main.php'">
    <input class="btnnew" type="button" value="Обновить" onclick="location.href='main.php?myzv'">
    <? } ?>
  </center>  
</fieldset>
<? if(!isset($_GET['myzv'])) { ?>
    <br>
<fieldset>
  <legend><b>Биржевые ставки</b> </legend>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
     	<td align="center"><b>Продажи екр.</b></td>
        <td align="center"><b>Покупки екр.</b></td>
     </tr>
     <tr>
        <td width="50%" valign="top">
           <?
		    $i = 0;
			$sp = mysql_query('SELECT * FROM `birja` WHERE `close` = 0 AND `buy_ekr` > 0 ORDER BY `curs_ekr` ASC');
			$html = '';
			while( $pl = mysql_fetch_array($sp) ) {
				$i++;
				$html .= '<tr><td>'.$i . '</td><td>'.$pl['curs_ekr'].'</td><td>'.$pl['buy_ekr'].'</td></tr>';
			}
			if( $html == '' ) {
				echo '<br><center>Нет сделок</center><br>';
			}else{
				echo '<table width="100%"><tr bgcolor="#a5a5a5"><td align="center"><b>#</b></td><td align="center"><b>Курс</b></td><td align="center"><b>Кол-во</b></td></tr>'.$html.'</table>';
			}
			?>
        </td>
     <td width="50%" valign="top">
           <?
		    $i = 0;
			$sp = mysql_query('SELECT * FROM `birja` WHERE `close` = 0 AND `sale_ekr` > 0 ORDER BY `curs_ekr` DESC');
			$html = '';
			while( $pl = mysql_fetch_array($sp) ) {
				$i++;
				$html .= '<tr><td align="center">'.$i . '</td><td align="center">'.$pl['curs_ekr'].'</td><td align="center">'.$pl['sale_ekr'].'</td></tr>';
			}
			if( $html == '' ) {
				echo '<br><center>Нет сделок</center><br>';
			}else{
				echo '<table width="100%"><tr bgcolor="#a5a5a5"><td align="center"><b>#</b></td><td align="center"><b>Курс</b></td><td align="center"><b>Кол-во</b></td></tr>'.$html.'</table>';
			}
			?>
     </td>
    </tr>
  </table>
  </fieldset>
    <?
	}else{
?>
    <br>
<fieldset>
  <legend><b>Мои продажи</b> </legend>
  <table width="100%">
     <tr bgcolor="#a5a5a5">
     	<td width="20%" align="center"><b>Курс за 1 екр.</b></td>
        <td width="20%" align="center"><b>Осталось</b></td>
        <td width="20%" align="center"><b>Размещена</b></td>
        <td width="20%" align="center"><b>До возврата</b></td>
        <td width="20%" align="center"><b>Отозвать</b></td>
     </tr>
  </table>
  <?
  $html = '';
  $sp = mysql_query('SELECT * FROM `birja` WHERE `uid` = "'.$u->info['id'].'" AND `close` = 0 AND `buy_ekr` > 0');
  while( $pl = mysql_fetch_array($sp) ) {
	if(isset($_GET['otz1_g']) && $_GET['otz1_g'] == $pl['id']) {
		$html .= '<table width="100%">';
		$html .= '<tr bgcolor="#c74747"><td align="center">Заявка отозвана. Остаток в <b style="color:blue">'.$pl['buy_ekr'].' екр.</b> возвращен на счет '.$u->bank['id'].'</td><tr>';
		$html .= '</table>';
		mysql_query('UPDATE `birja` SET `buy_ekr` = 0,`close` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		$u->bank['money2'] += $pl['buy_ekr'];
		mysql_query('UPDATE `bank` SET `money1` = "'.$u->bank['money1'].'",`money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
	}else{
		$html .= '<table width="100%"><tr';
		if(isset($_GET['otz1']) && $_GET['otz1'] == $pl['id']) {
			$html .= ' bgcolor="#c74747"';
		}
		$html .= '><td width="20%" align="center">'.$pl['curs_ekr'].'</td><td width="20%" align="center">'.$pl['buy_ekr'].'</td><td width="20%" align="center">'.date('d.m.Y H:i',$pl['time']).'</td><td width="20%" align="center">'.$u->timeOut( $pl['time'] + 7*86400 - time() ).'</td><td width="20%" align="center">';
		if(isset($_GET['otz1']) && $_GET['otz1'] == $pl['id']) {
			$html .= '<a href="main.php?myzv&otz1_g='.$pl['id'].'">принять</a>';
		}else{
			$html .= '<a href="main.php?myzv&otz1='.$pl['id'].'">отозвать</a>';
		}
		$html .= '</td></tr></table>';
	}
  }
  if( $html == '' ) {
		$html .= '<table width="100%">';
		$html .= '<tr><td align="center">Заявок нет</td><tr>';
		$html .= '</table>';
  }
  echo $html;
  ?>
  </fieldset>
    <br>
<fieldset>
  <legend><b>Мои покупки</b> </legend>
  <table width="100%">
     <tr bgcolor="#a5a5a5">
     	<td width="20%" align="center"><b>Курс за 1 екр.</b></td>
        <td width="20%" align="center"><b>Осталось</b></td>
        <td width="20%" align="center"><b>Размещена</b></td>
        <td width="20%" align="center"><b>До возврата</b></td>
        <td width="20%" align="center"><b>Отозвать</b></td>
     </tr>
  </table>
  <?
  $html = '';
  $sp = mysql_query('SELECT * FROM `birja` WHERE `uid` = "'.$u->info['id'].'" AND `close` = 0 AND `sale_ekr` > 0');
  while( $pl = mysql_fetch_array($sp) ) {
	if(isset($_GET['otz2_g']) && $_GET['otz2_g'] == $pl['id']) {
		$html .= '<table width="100%">';
		$html .= '<tr bgcolor="#c74747"><td align="center">Заявка отозвана. Остаток в <b style="color:blue">'.($pl['sale_ekr']*$pl['curs_ekr']).' кр.</b> возвращен на счет '.$u->bank['id'].'</td><tr>';
		$html .= '</table>';
		mysql_query('UPDATE `birja` SET `buy_ekr` = 0,`close` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		$u->bank['money1'] += ($pl['sale_ekr']*$pl['curs_ekr']);
		mysql_query('UPDATE `bank` SET `money1` = "'.$u->bank['money1'].'",`money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
	}else{
		$html .= '<table width="100%"><tr';
		if(isset($_GET['otz2']) && $_GET['otz2'] == $pl['id']) {
			$html .= ' bgcolor="#c74747"';
		}
		$html .= '><td width="20%" align="center">'.$pl['curs_ekr'].'</td><td width="20%" align="center">'.($pl['sale_ekr']*$pl['curs_ekr']).'</td><td width="20%" align="center">'.date('d.m.Y H:i',$pl['time']).'</td><td width="20%" align="center">'.$u->timeOut( $pl['time'] + 7*86400 - time() ).'</td><td width="20%" align="center">';
		if(isset($_GET['otz2']) && $_GET['otz2'] == $pl['id']) {
			$html .= '<a href="main.php?myzv&otz2_g='.$pl['id'].'">принять</a>';
		}else{
			$html .= '<a href="main.php?myzv&otz2='.$pl['id'].'">отозвать</a>';
		}
		$html .= '</td></tr></table>';
	}
  }
  if( $html == '' ) {
		$html .= '<table width="100%">';
		$html .= '<tr><td align="center">Заявок нет</td><tr>';
		$html .= '</table>';
  }
  echo $html;
  ?>
  </fieldset>
<?
	}
	?>
<? } ?>