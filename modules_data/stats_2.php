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
����: <? echo $u->stats['s1']; ?><br />
��������:&nbsp;<? echo $u->stats['s2']; ?><br />
��������:&nbsp;<? echo $u->stats['s3']; ?><br />
������������:&nbsp;<? echo $u->stats['s4']; ?><br />
<? if($u->info['level'] > 3){ ?>���������:&nbsp;<? echo $u->stats['s5']; ?><br /><? } ?>
<? if($u->info['level'] > 6){ ?>��������:&nbsp;<? echo $u->stats['s6']; ?><br /><? } ?>
<?
if($u->info['ability'] > 0) 
{ 
echo '<a href="main.php?skills=1&side=1">+ �����������</a><br />'; 
}
if($u->info['skills'] > 0 && $u->info['level'] > 0)
{ 
echo '&bull;&nbsp;<a href="main.php?skills=1&side=1">��������</a><br />'; 
} 
?>
&nbsp;<br />
����:&nbsp;<b><? echo $u->info['exp']; ?></b> (0)<br />
�������:&nbsp;<? echo $u->info['level']; ?><br />
�����:&nbsp;<? echo $u->info['win']; ?><br />
���������:&nbsp;<? echo $u->info['lose']; ?><br />
������:&nbsp;<? echo $u->info['nich']; ?><br />
������:&nbsp;<b><? echo $u->info['money']; ?></b>&nbsp;��.<br />
<? /* if($u->info['money3'] > 0) {*/ ?>
������:&nbsp;<b><? echo $u->info['money3']; ?></b>&nbsp;$<? /*}*/ ?>
<? if($u->rep['rep3'] >= 0) { ?>
��������������:&nbsp;<? echo $u->rep['rep3']; ?><? } ?></span>
</td>
</tr>
</table>
