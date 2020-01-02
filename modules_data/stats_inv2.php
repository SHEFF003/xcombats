<?
if(!defined('GAME'))
{
	die();
}
?>
<br />
<br />
Опыт: <a href="http://lib.xcombats.com/main/85" target="_blank"><?=$u->info['exp']?></a> (<?=$u->stats['levels']['exp']?>)<br />
Уровень: <?=$u->info['level']?><br />
Побед: <?=$u->info['win']?><br />
Поражений: <?=$u->info['lose']?><br />
Ничьих: <?=$u->info['nich']?><br />
Деньги: <b><?=$u->info['money']?></b> кр.
<hr />
<?
$aba = '';
if( $u->info['ability'] > 0 ) {
	$aba = ' <a href="main.php?skills=1&side=5"><img src="http://img.xcombats.com/i/up.gif"></a>';
}
$skls = '';
if( $u->info['skills'] > 0 ) {
	$skls = ' <a href="main.php?skills=1&side=5"><img src="http://img.xcombats.com/i/up.gif"></a>';
}
?>
Сила: <?=$u->stats['s1'].$aba?><br />
Ловкость: <?=$u->stats['s2'].$aba?><br />
Интуиция: <?=$u->stats['s3'].$aba?><br />
Выносливость: <?=$u->stats['s4'].$aba?><br />
<? if( $u->info['level'] > 3 || $u->stats['s5'] != 0 ) { ?>Интелект: <?=$u->stats['s5'].$aba?><br /><? } ?>
<? if( $u->info['level'] > 6 || $u->stats['s6'] != 0 ) { ?>Мудрость: <?=$u->stats['s6'].$aba?><br /><? } ?>
<font color=green>Возможных увеличений: <?=$u->info['ability']?> </font>
<hr />
Урон: <?=str_replace('-',' - ',$u->inform('yron'))?><br />
Модификаторы<br />
&nbsp;&nbsp;&nbsp;уворот:  <?=$u->inform('m4')?>% <br />
&nbsp;&nbsp;&nbsp;антиуворот:  <?=$u->inform('m5')?>% <br />
&nbsp;&nbsp;&nbsp;крит:  <?=$u->inform('m1')?>% <br />
&nbsp;&nbsp;&nbsp;антикрит:  <?=$u->inform('m2')?>% <br />
Броня<br />
&nbsp;&nbsp;&nbsp;головы:  <?=$u->stats['mab1']?><br />
&nbsp;&nbsp;&nbsp;корпуса:  <?=$u->stats['mab2']?><br />
&nbsp;&nbsp;&nbsp;пояса:  <?=$u->stats['mab3']?><br />
&nbsp;&nbsp;&nbsp;ног:  <?=$u->stats['mab4']?><br />
<hr />
Мастерство владения:<br />
&nbsp;&nbsp;&nbsp;ножами и кастетами: <?=(0+$u->stats['a1'])?><?=$skls?><br />
&nbsp;&nbsp;&nbsp;мечами: <?=(0+$u->stats['a4'])?><?=$skls?><br />
&nbsp;&nbsp;&nbsp;дубинами, булавами: <?=(0+$u->stats['a3'])?><?=$skls?><br />
&nbsp;&nbsp;&nbsp;топорами и секирами: <?=(0+$u->stats['a2'])?><?=$skls?><br />
<font color=navy>Возможных увеличений: <?=$u->info['skills']?></font>