<?
if(!defined('GAME'))
{
	die();
}
?>
<br />
<br />
����: <a href="http://lib.xcombats.com/main/85" target="_blank"><?=$u->info['exp']?></a> (<?=$u->stats['levels']['exp']?>)<br />
�������: <?=$u->info['level']?><br />
�����: <?=$u->info['win']?><br />
���������: <?=$u->info['lose']?><br />
������: <?=$u->info['nich']?><br />
������: <b><?=$u->info['money']?></b> ��.
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
����: <?=$u->stats['s1'].$aba?><br />
��������: <?=$u->stats['s2'].$aba?><br />
��������: <?=$u->stats['s3'].$aba?><br />
������������: <?=$u->stats['s4'].$aba?><br />
<? if( $u->info['level'] > 3 || $u->stats['s5'] != 0 ) { ?>��������: <?=$u->stats['s5'].$aba?><br /><? } ?>
<? if( $u->info['level'] > 6 || $u->stats['s6'] != 0 ) { ?>��������: <?=$u->stats['s6'].$aba?><br /><? } ?>
<font color=green>��������� ����������: <?=$u->info['ability']?> </font>
<hr />
����: <?=str_replace('-',' - ',$u->inform('yron'))?><br />
������������<br />
&nbsp;&nbsp;&nbsp;������:  <?=$u->inform('m4')?>% <br />
&nbsp;&nbsp;&nbsp;����������:  <?=$u->inform('m5')?>% <br />
&nbsp;&nbsp;&nbsp;����:  <?=$u->inform('m1')?>% <br />
&nbsp;&nbsp;&nbsp;��������:  <?=$u->inform('m2')?>% <br />
�����<br />
&nbsp;&nbsp;&nbsp;������:  <?=$u->stats['mab1']?><br />
&nbsp;&nbsp;&nbsp;�������:  <?=$u->stats['mab2']?><br />
&nbsp;&nbsp;&nbsp;�����:  <?=$u->stats['mab3']?><br />
&nbsp;&nbsp;&nbsp;���:  <?=$u->stats['mab4']?><br />
<hr />
���������� ��������:<br />
&nbsp;&nbsp;&nbsp;������ � ���������: <?=(0+$u->stats['a1'])?><?=$skls?><br />
&nbsp;&nbsp;&nbsp;������: <?=(0+$u->stats['a4'])?><?=$skls?><br />
&nbsp;&nbsp;&nbsp;��������, ��������: <?=(0+$u->stats['a3'])?><?=$skls?><br />
&nbsp;&nbsp;&nbsp;�������� � ��������: <?=(0+$u->stats['a2'])?><?=$skls?><br />
<font color=navy>��������� ����������: <?=$u->info['skills']?></font>