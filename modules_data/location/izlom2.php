<?php
if(!defined('GAME'))
{
 die();
}

if($u->room['file']=='izlom2')
{
if(isset($_POST['level']))
{
	if((int)$_POST['level']<=$u->info['level'] && (int)$_POST['level']<=7)
	{
		$eff1 = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `id_eff` = "31" AND `delete` = "0" LIMIT 1'));
		if(!isset($eff1['id']))
		{
			if(file_exists('_incl_data/class/__zv.php')) {
			if(!isset($zv))
			{
				include('_incl_data/class/__zv.php');
			}			
			$zv->startIzlom(1,((int)$_POST['level']));
			}else{
				echo '...';
			}
		}else{
			echo '<font color="red"><b>Вы не можете начать новый поход пока действует эффект &quot;Касание Хаоса&quot;</b></font>';
		}
	}
}

?>
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
	<div align="right"><? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?></div>
	<div id="hint3" style="visibility:hidden"></div>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3">Излом Хаоса</div>
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
							  <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
							  <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=3.180.0.353&rnd=<? echo $code; ?>';" title="<? thisInfRm('3.180.0.353',1); ?>">Излом Хаоса - 16</a></td>
							</tr>
							<tr>
							  <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
							  <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=3.180.0.270&rnd=<? echo $code; ?>';" title="<? thisInfRm('3.180.0.270',1); ?>">Магазин Излома</a></td>
							</tr>
						</table></td>
					  </tr>					  <tr>
						<td nowrap="nowrap">&nbsp;</td>
					  </tr>
				  </table></td>
			  </tr>
		  </table></td>
		</tr>
	  </table>
		<br />
	<center></center></td>
	</table>
	<div id="textgo" style="visibility:hidden;"></div>
<FORM method="post">
    <input checked="checked" type="radio" name="level" value="7" <? if($u->info['level']<7){ echo 'disabled'; } ?>>
    Проход в &laquo;Излом Хаоса&raquo;<br/>
    <INPUT type='submit' value='Начать поход'>
</FORM>	
  <p><b>Рейтинг походов &laquo;Излом Хаоса&raquo;:</b><br /><?
  $i = 0; $sp = mysql_query('SELECT * FROM `izlom_rating` ORDER BY `voln` DESC LIMIT 50');
  $uidz = array();
  while($pl = mysql_fetch_array($sp))
  {
	  if(!isset($uidz[$pl['uid']]))
	  {
	  	$i++; $uidz[$pl['uid']] = $i;
	  	$text .= $i.'. <span class="date">'.date('d.m.Y H:i',$pl['time']).'</span>, Волна: <b>'.$pl['voln'].'</b>, '.$u->microLogin($pl['uid'],1).'<br>';
	  }
  }
  if(!isset($text))
  {
	 $text = 'История пуста, скорее всего не нашлось смельчаков...'; 
  }
  echo $text;
  unset($text);
  ?></p>				  
<?
}
?>