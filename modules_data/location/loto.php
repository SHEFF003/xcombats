<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='loto')
{
	$loto = mysql_fetch_array(mysql_query('SELECT * FROM `loto_info` WHERE `time_start` <= "'.time().'" AND (`time_finish` > '.time().' OR `finished` = 0) LIMIT 1'));
	//
	if( $loto['time_finish'] < time() && $loto['finished'] == 0 ) {
		//Завершаем розыгрыш
		$loto['finished'] = 1;
		mysql_query('UPDATE `loto_info` SET `finished` = "'.$loto['finished'].'" WHERE `id` = "'.$loto['finished'].'" LIMIT 1');
		$b = $loto['buy'];
		$sp = mysql_query('SELECT * FROM `loto_itm` WHERE `lid` = "'.$loto['id'].'"');
		while( $pl = mysql_fetch_array($sp) ) {
			$i = 0;
			while( $i < $pl['colvo'] ) {
				$winner = rand(1,$b);
				//echo 'Победитель: Билет №'.$winner.'<br>Приз: '.$pl['text'].'<hr>';
				$uwin = mysql_fetch_array(mysql_query('SELECT `id`,`uid` FROM `items_users` WHERE `data` LIKE "%Тираж №'.$loto['id'].', Билет №'.$winner.'<br>%" LIMIT 1'));
				if(isset($uwin['id'])) {
					$uwin = $uwin['uid'];
				}else{
					$uwin = -1;
				}
				mysql_query('INSERT INTO `loto_win` (`lid`,`priz`,`uid`,`time`,`bilet`) VALUES (
					"'.$loto['id'].'","'.$pl['id'].'","'.$uwin.'","'.time().'","'.$winner.'"
				) ');
				$i++;
			}
		}
	}
	//
	if( isset($_GET['buyloto']) && isset($loto['id']) && $loto['finished'] == 0 ) {
		if( $loto['buy'] >= $loto['tiraj'] ) {
			$error = 'Билетов больше нет.';
		}elseif( $loto['price'] > $u->info['money'] ) {
			$error = 'У вас недостаточно кредитов';
		}else{
			$error = 'Вы успешно приобрели лотерейный билет, тираж №'.$loto['id'].'.';
			$u->addItem(4539,$u->info['id'],'|sudba='.$u->info['login'].'|info=Лотерея &quot;'.$loto['name'].'&quot;<br>Тираж №'.$loto['id'].', Билет №'.($loto['buy']+1).'<br><b>Розыгрыш завершится '.date('d.m.Y H:i',$loto['time_finish']).'</b>');
			$u->info['money'] -= $loto['price'];
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			//
			$loto['buy']++;
			mysql_query('UPDATE `loto_info` SET `buy` = `buy` + 1 WHERE `id` = "'.$loto['id'].'" LIMIT 1');
		}
	}
	//
	if( $loto['finished'] == 1 ) {
		unset( $loto );
	}
?>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3"><h3>Лото Бойцовского Клуба</h3></div>
	<?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
	<br />
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%" valign="top">
        	<center><h3>Текущая лотерея<? if(isset($loto['id'])) { echo ' &quot;'.$loto['name'].'&quot;'; } ?></h3></center>
            <? if(!isset($loto['id'])) { ?><center>Розыгрышей нет</center><? }else{ ?>
            <div style="padding:20px;">
            Начало розыгрыша: <span class="date"><?=date('d.m.Y H:i',$loto['time_start'])?></span><br />
            Завершение розыгрыша: <span class="date"><?=date('d.m.Y H:i',$loto['time_finish'])?></span><br />
            Куплено билетов: <?=$loto['buy']?> из <b><?=$loto['tiraj']?></b><br />
            Стоимость лотерейного билета: <b><?=$loto['price']?>.00 кр.</b><br />
            <br />
            <? if( $loto['finished'] == 0 ) { ?>
            <input onclick="location.href='main.php?buyloto=1'" type="button" value="Купить лотерейный билет" /><? } ?><br />
            <small>(Один игрок может купить несколько билетов)</small><hr />
            <h3>Призы в текущем розыгрыше</h3>
            <?
			$sp = mysql_query('SELECT * FROM `loto_itm` WHERE `lid` = "'.$loto['id'].'"');
			while( $pl = mysql_fetch_array($sp) ) {
				$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl['item_id'].'" LIMIT 1'));
				if(isset($itm['id'])) {
					echo '[itm]<hr>';
				}else{
					echo ''.$pl['text'].', <b>Количество: '.$pl['colvo'].'</b><hr>';
				}
			}
			?>
            <br />
            <?
			$usrs = array();
			$bilets = array();
			$sp = mysql_query('SELECT `id`,`uid` FROM `items_users` WHERE `item_id` = "4539"');
			while( $pl = mysql_fetch_array($sp) ) {
				if(!isset($bilets[$pl['uid']])) {
					$usrs[] = $pl['uid'];
					$bilets[$pl['uid']] = 0;
				}
				$bilets[$pl['uid']]++;
			}
			echo '<center><h3>Участники текущего розыгрыша ('.count($usrs).')</h3></center>';
			$i = 0;
			function declOfNum($number, $titles) {
				$cases = array (2, 0, 1, 1, 1, 2);
				return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
			}
			while( $i < count($usrs) ) {
				$tks = $bilets[$usrs[$i]];
				if( $usrs[$i] < 0 ) {
					$usrs[$i] = str_replace('-91','',$usrs[$i]);
					$usrs[$i] = str_replace('-92','',$usrs[$i]);
				}
				echo '<div><span style="float:left">'.($i+1).'. '.$u->microLogin($usrs[$i],1).'</span><span style="float:right"><small>Билетов: <div style="width:54px;display:inline-block;">'.$tks.'</div></small></span><br></div><hr>';
				$i++;
			}
			?>
            </div>
            <? } ?>
        </td>
        <td valign="top">
			<?
			$last_loto = mysql_fetch_array(mysql_query('SELECT * FROM `loto_info` WHERE `finished` = 1 ORDER BY `id` DESC LIMIT 1'));
            ?>
        	<center><h3>Победители прошлого розыгрыша<? if(isset($last_loto['id'])){ echo ' &quot;'.$last_loto['name'].'&quot;'; } ?></h3></center>
            <?
			$i = 0;
			$sp = mysql_query('SELECT * FROM `loto_win` WHERE `lid` = "'.$last_loto['id'].'" ORDER BY `id` DESC');
			while( $pl = mysql_fetch_array($sp) ) {
				if( $pl['uid'] < 0 ) {
					$pl['uid'] = str_replace('-91','',$pl['uid']);
					$pl['uid'] = str_replace('-92','',$pl['uid']);
				}
				if( $pl['uid'] == -1 ) {
					echo 'Билет №'.$pl['bilet'].'. <i>(Персонаж был заблокирован, либо удален)</i>';
				}else{
					$witm = mysql_fetch_array(mysql_query('SELECT * FROM `loto_itm` WHERE `id` = "'.$pl['priz'].'" LIMIT 1'));
					echo 'Билет №'.$pl['bilet'].'. Персонаж '.$u->microLogin($pl['uid'],1).' выиграл &quot;<b>'.$witm['text'].'</b>&quot;!';
				}
				echo '<hr>';
				$i++;
			}
			if( $i == 0 ) {
			?>
            <center>Победителей нет</center>
            <? } ?>
        </td>
        </tr>
    </table>
<TABLE width="100%" cellspacing="0" cellpadding="4">
  <TR>
	<form name="F1" method="post">
	<TD valign="top" align="left"><!--Магазин--></TD>
	</FORM>
	</TR>
	</TABLE>	
	<td width="280" valign="top">
    <TABLE cellspacing="0" cellpadding="0"><TD width="100%">&nbsp;</TD><TD>
	<table  border="0" cellpadding="0" cellspacing="0">
	<tr align="right" valign="top">
	<td>
	<!-- -->
	<? echo $goLis; ?>
	<!-- -->
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td nowrap="nowrap">
	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
	<tr>
	<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">Центральная площадь</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div><br />
	</div></td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>