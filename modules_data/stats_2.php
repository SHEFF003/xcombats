<?php
if(!defined('GAME'))
{
	die();
}
?>
<div id="hint4" class="ahint"></div>
<table width="100%" border="0" cellspacing="3" cellpadding="0">
<tr><td>&nbsp;</td></tr>
  <tr>
    <td>
<span style="font-size:12px;">
Сила: <? echo $u->stats['s1']; ?><br />
Ловкость:&nbsp;<? echo $u->stats['s2']; ?><br />
Интуиция:&nbsp;<? echo $u->stats['s3']; ?><br />
Выносливость:&nbsp;<? echo $u->stats['s4']; ?><br />
<? if($u->info['level'] > 3){ ?>Интеллект:&nbsp;<? echo $u->stats['s5']; ?><br /><? } ?>
<? if($u->info['level'] > 6){ ?>Мудрость:&nbsp;<? echo $u->stats['s6']; ?><br /><? } ?>
<?
if($u->info['ability'] > 0) 
{ 
echo '<a href="main.php?skills=1&side=1">+ Способности</a><br />'; 
}
if($u->info['skills'] > 0 && $u->info['level'] > 0)
{ 
echo '&bull;&nbsp;<a href="main.php?skills=1&side=1">Обучение</a><br />'; 
} 
?>
&nbsp;<br />
Опыт:&nbsp;<b><? echo $u->info['exp']; ?></b> (0)<br />
Уровень:&nbsp;<? echo $u->info['level']; ?><br />
Побед:&nbsp;<? echo $u->info['win']; ?><br />
Поражений:&nbsp;<? echo $u->info['lose']; ?><br />
Ничьих:&nbsp;<? echo $u->info['nich']; ?><br />
Деньги:&nbsp;<b><? echo $u->info['money']; ?></b>&nbsp;кр.<br />
<? /* if($u->info['money3'] > 0) {*/ ?>
Валюта:&nbsp;<b><? echo $u->info['money3']; ?></b>&nbsp;$<? /*}*/ ?>
<? if($u->rep['rep3'] >= 0) { ?>
Воинственность:&nbsp;<? echo $u->rep['rep3']; ?><? } ?></span>
</td>
</tr>
</table>
