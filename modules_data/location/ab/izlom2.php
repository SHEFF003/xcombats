<?php
if(!defined('GAME'))
{
 die();
}

if($u->room['file']=='ab/izlom2')
{
if(isset($_POST['level']))
{
	$dop_lvl = 8; //максимально допустимый лвл
	if((int)$_POST['level'] <= $u->info['level'] && (int)$_POST['level'] <= $dop_lvl)
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
	}else{
		echo '<font color="red"><b>Вы не можете начать поход... Выполните больше заданий у Шейлы!</b></font>';
	}
}

?>
	<style type="text/css"> 
	body
	{
		/*background-color:#E2E2E2;*/
		background-image: url(http://img.xcombats.com/p_portal23.jpg);
		background-repeat:no-repeat;background-position:top right;
	}
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
	<TABLE style="" width="100%" cellspacing="0" cellpadding="0">
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
    <input checked="checked" type="radio" name="level" value="8" <? if($u->info['level']<7){ echo 'disabled'; } ?>>
    Проход для любителей (<a href="breakchaos.php?level=8" target="_blank">посмотреть рейтинг</a>)<br/>
    <input type="radio" name="level" value="9" <? if($u->info['level']<7){ echo 'disabled'; } ?>>
    Проход для бывалых (<a href="breakchaos.php?level=9" target="_blank">посмотреть рейтинг</a>)<br/>
    <input type="radio" name="level" value="10" <? if($u->info['level']<7){ echo 'disabled'; } ?>>
    Проход для профессионалов (<a href="breakchaos.php?level=10" target="_blank">посмотреть рейтинг</a>)<br/>
    <input type="radio" name="level" value="11" <? if($u->info['level']<7){ echo 'disabled'; } ?>>
    Проход для жителей (<a href="breakchaos.php?level=11" target="_blank">посмотреть рейтинг</a>)<br/><br/>
    <INPUT type='submit' value='Начать поход'>
</FORM>	
  <!--<p><b>Рейтинг походов &laquo;Излом Хаоса&raquo;:</b><br />--><?
 /*- $i = 0; $sp = mysql_query('SELECT * FROM `izlom_rating` ORDER BY `voln` DESC LIMIT 50');
  $uidz = array();
  while($pl = mysql_fetch_array($sp))
  {
	  if(!isset($uidz[$pl['uid']]))
	  {
	  	$i++; $uidz[$pl['uid']] = $i;
	  	$text .= $i.'. <span class="date">'.date('d.m.Y H:i',$pl['time']).'</span>, Волна: <b>'.$pl['voln'].'</b>, '.$u->microLogin($pl['uid'],1).'<br>';
	  }
  }-*/
  /*if(!isset($text))
  {
	 $text = 'История пуста, скорее всего не нашлось смельчаков...'; 
  }
  echo $text;
  unset($text);*/
  echo '</p>';
  ?>				  
<?
}
?>