<?php
if(!defined('GAME'))
{
	die();
}

if(isset($_GET['r']))
{
	$_GET['r'] = (int)$_GET['r'];
}else{
	$_GET['r'] = NULL;	
}

$u->info['referals'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `register_code` WHERE `uid` = "'.$u->info['id'].'" AND `time_finish` > 0 AND `end` = 0 LIMIT 1000'));			 
$u->info['referals'] = $u->info['referals'][0];

include('_incl_data/class/__zv.php');

?>
<script> var zv_Priem = 0; </script>
<style> 
.m {background: #d2d2d2;text-align: center;}
.s {background: #b4b4b4;text-align: center;}
</style>
<TABLE width=100% cellspacing=1 cellpadding=3>
<TR><TD colspan=8 align=right>
<div style="float:left">
	<?
	echo ' &nbsp; &nbsp; <b style="color:grey">(Удаленный просмотр)</b>';
	?>
</div>
<div style="float:right">
  <INPUT TYPE=button value="Вернуться" onClick="location.href='main.php?rnd=<? echo $code; ?>';">
</div>
</TD></TR>
<TR>
<TD class=m width=40>&nbsp;<B>Бои:</B></TD>
<TD width="15%" class="<? if($_GET['r']==1){ echo 's'; }else{ echo 'm'; } ?>"><A HREF="main.php?zayvka=1&r=1&rnd=<? echo $code; ?>">Новички</A></TD>
<TD width="15%" class="<? if($_GET['r']==2){ echo 's'; }else{ echo 'm'; } ?>"><A HREF="main.php?zayvka=1&r=2&rnd=<? echo $code; ?>">Физические</A></TD>
<TD width="14%" class="<? if($_GET['r']==3){ echo 's'; }else{ echo 'm'; } ?>"><A HREF="main.php?zayvka=1&r=3&rnd=<? echo $code; ?>">Договорные</A></TD>
<TD width="14%" class="<? if($_GET['r']==4){ echo 's'; }else{ echo 'm'; } ?>"><A HREF="main.php?zayvka=1&r=4&rnd=<? echo $code; ?>">Групповые</A></TD>
<TD width="14%" class="<? if($_GET['r']==5){ echo 's'; }else{ echo 'm'; } ?>"><A HREF="main.php?zayvka=1&r=5&rnd=<? echo $code; ?>">Хаотичные</A></TD>
<TD width="14%" class="<? if($_GET['r']==6){ echo 's'; }else{ echo 'm'; } ?>"><A HREF="main.php?zayvka=1&r=6&rnd=<? echo $code; ?>">Текущие</A></TD>
<TD width="14%" class="<? if($_GET['r']==7){ echo 's'; }else{ echo 'm'; } ?>"><A HREF="main.php?zayvka=1&r=7&rnd=<? echo $code; ?>">Завершенные</A></TD>
</TR></TR></TABLE>
<table style="padding:2px;" width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><? echo $zv->see(); ?></td>
  </tr>
  <tr>
    <td><? $zv->seeZv(); ?></td>
  </tr>
</table><br />
<DIV align="right">
<?
echo $c['counters'];
?>
</DIV>
