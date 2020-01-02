<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='stela')
{
	
	$dt = date('d.m.Y');
	if(isset($_GET['godate'])) {
		$dt = $_GET['godate'];
	}
	$dt = strtotime($dt); // сегодня
	
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}	
	</style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><br /><br />
	<div align="center" class="pH3">Стелла Выбора</div>
	<?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
	<br />
	<TABLE width="100%" cellspacing="0" cellpadding="4">
	<TR>
	<form name="F1" method="post">
	<TD valign="top" align="left">
    	<div align="center">
        	Здесь решается вопрос об исправлении ошибок, какую ошибку исправляем сегодня или что вводим новое. Каждый день, каждый персонаж может голосовать по одному разу, либо предложить свой вариант.
        </div>
        <div align="center">
        	<hr>
        	<a href="main.php?godate=<?=date('d.m.Y',($dt-86400))?>">&laquo; <?=date('d.m.Y',($dt-86400))?> &laquo;</a> &nbsp; &nbsp; Голосование за <B><?=date('d.m.Y',$dt)?></B> &nbsp; &nbsp; <? if( $dt+86400 > time() ) { echo '<font color="grey"><b>&raquo; '.date('d.m.Y',($dt+86400)).' &raquo;</b></font>'; }else{ ?><a href="main.php?godate=<?=date('d.m.Y',($dt+86400))?>">&raquo; <?=date('d.m.Y',($dt+86400))?> &raquo;</a><? } ?>
        </div>
        <?
		$html = '';
		
		if( $html == '' ) {
			echo '<hr><br><br><center>За этот день записи не найдены</center>';
		}
		?>
    </TD>
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
	<td bgcolor="#D3D3D3" nowrap><a href="javascript:void(0)" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">Центральная площадь</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table></td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>