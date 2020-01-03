<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='kareta')
{
	$cs = ''; $sos = '';
	$car = mysql_fetch_array(mysql_query('SELECT * FROM `vokzal` WHERE `name` = "'.$u->room['name'].'" LIMIT 1'));
	if(isset($car['id']))
	{
		$sp = mysql_query('SELECT * FROM `vokzal` WHERE `city` = "'.$u->info['city'].'" OR `tocity` = "'.$u->info['city'].'"');
		while($pl = mysql_fetch_array($sp))
		{
			$vz1 = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "Вокзал" AND `city` = "'.$pl['city'].'" LIMIT 1'));
			$vz2 = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "Вокзал" AND `city` = "'.$pl['tocity'].'" LIMIT 1'));		
			$crm = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "'.$pl['name'].'" LIMIT 1'));
			//period 0 - прибытие в город (стоянка), 1 - движение, 3 - прибытие в другой город (стоянка), 4 - движение (из tocity)
			if($pl['time_start_go']==0)
			{
				//Это новая карета обновляем данные
				mysql_query('UPDATE `vokzal` SET `time_start_go` = "'.(time()+$pl['timeStop']*60).'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['time_start_go'] = time()+$pl['timeStop']*60;
				$pl['time_finish_go'] = $pl['time_start_go']+$pl['time_go']*60;
			}
			$see = 0;
			if($u->info['admin']>0)
			{
				$see = 1;
			}
			$plc = $pl['tocity'];
			if($pl['time_start_go']-600<time() && $pl['time_start_go']>time())
			{
				//можно знанимать места в карете
				if(isset($crm['id']))
				{
					$sr = mysql_query('SELECT `uid`,`id` FROM `items_users` WHERE `secret_id` = "'.$pl['time_start_go'].'_b'.$pl['id'].'" AND `delete` = "0" LIMIT 100');
					while($pr = mysql_fetch_array($sr))
					{
						$upd1 = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$pr['id'].'" LIMIT 1');
						if($upd1)
						{
							mysql_query('UPDATE `users` SET `room` = "'.$crm['id'].'" WHERE `online` > '.(time()-120).' AND `id` = "'.$pr['uid'].'" LIMIT 1');
						}
					}
				}
			}
				//отправляем карету в другой город
				if($pl['time_finish_go']<time())
				{
					//прибыли
					if($pl['period']==0)
					{
						//Прибыли в город, время стоянки закончилось, и поехали
						mysql_query('UPDATE `vokzal` SET `period` = "1",`citygo` = "'.$pl['tocity'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						$pl['period'] = 1;
					}elseif($pl['period']==1)
					{
						//приехалис в другой город, делаем там стоянку выкидываем людей
						if(isset($crm['id']))
						{
							mysql_query('UPDATE `users` SET `city` = "'.$pl['tocity'].'",`room` = "'.$vz2['id'].'" WHERE `room` = "'.$crm['id'].'" LIMIT '.$pl['bilets_default'].'');
						}
						mysql_query('UPDATE `vokzal` SET `bilets` = "'.$pl['bilets_default'].'",`citygo`="'.$pl['city'].'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'",`time_start_go` = "'.(time()+$pl['timeStop']*60).'",`period` = "3" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						$pl['period'] = 3;
					}elseif($pl['period']==3)
					{
						//Прибыли в город, время стоянки закончилось, и поехали
						mysql_query('UPDATE `vokzal` SET `period` = "4" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						$pl['period'] = 4;
					}elseif($pl['period']==4)
					{
						//приехалис в другой город, делаем там стоянку и выкидываем людей
						if(isset($crm['id']))
						{
							mysql_query('UPDATE `users` SET `city` = "'.$pl['city'].'",`room` = "'.$vz1['id'].'" WHERE `room` = "'.$crm['id'].'" LIMIT '.$pl['bilets_default'].'');
						}
						mysql_query('UPDATE `vokzal` SET `bilets` = "'.$pl['bilets_default'].'",`citygo`="'.$pl['tocity'].'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'",`time_start_go` = "'.(time()+$pl['timeStop']*60).'",`period` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
						$pl['period'] = 0;
					}else{
						echo '[?]';
					}
				}
		}
	}
	
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
	<tr><td valign="top"><div align="center" class="pH3"><? echo $u->room['name']; ?></div>
	<?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
    <center>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="400" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150"><div align="left"></div></td>
              <td><div align="left"></div></td>
            </tr>
            <tr>
              <td><div align="left">Место отбытия:</div></td>
              <td><div align="left"><? echo $u->city_name[$u->info['city']]; ?></div></td>
            </tr>
            <tr>
              <td><div align="left">Место прибытия:</div></td>
              <td><div align="left"><? echo $u->city_name[$car['citygo']]; ?></div></td>
            </tr>
            <tr>
              <td><div align="left">Прибытие:</div></td>
              <td><div align="left"><? echo date('H:i',$car['time_start_go']+$car['time_go']*60); ?></div></td>
            </tr>
            <tr>
              <td><div align="left"></div></td>
              <td><div align="left"></div></td>
            </tr>
            <tr>
              <td><div align="left"></div></td>
              <td><div align="left"></div></td>
            </tr>
            <tr>
              <td><div align="left"></div></td>
              <td><div align="left"></div></td>
            </tr>
            <tr>
              <td><div align="left"></div></td>
              <td><div align="left"></div></td>
            </tr>
          </table>
            
            <div align="left">
              <?
			/*
			img.combats-world.com/i/kareta1.swf - весна (ночь) 1 март
			img.combats-world.com/i/kareta2.swf - весна (день)
			img.combats-world.com/i/kareta3.swf - зима (ночь) 1 декабря
			img.combats-world.com/i/kareta4.swf - зима (день)
			img.combats-world.com/i/kareta5.swf - лето (ночь) 1 июня
			img.combats-world.com/i/kareta6.swf - лето (день) 
			img.combats-world.com/i/kareta7.swf - осень (ночь) 1 сентября
			img.combats-world.com/i/kareta8.swf - осень (день)
			1 - весна
			2 - лето
			3 - осень
			4 - зима
			*/
			$outimg = array(1=>4,2=>4,3=>1,4=>1,5=>1,6=>2,7=>2,8=>2,9=>3,10=>3,11=>3,12=>4);
			$imgo = array(1=>1,2=>5,3=>7,4=>3);
			$outimg = $imgo[$outimg[round(date('m',time()))]];
			if(date('H',time())<22 && date('H',time())>6)
			{
				$outimg++;
			}
			?>
              <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="220" height="175">
                <param name="movie" value="http://img.xcombats.com/i/kareta<? if($outimg==''){ $outimg = 1; } echo $outimg; ?>.swf" />
                <param name="quality" value="high" />
                <param name="SCALE" value="exactfit" />
                <embed src="http://img.xcombats.com/i/kareta<? echo $outimg; ?>.swf" width="220" height="175" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" scale="exactfit"></embed>
              </object>
            </div></td>
          <td valign="middle"><p>&nbsp;</p>
            <div align="center">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="670" height="428">
              <param name="movie" value="http://img.xcombats.com/flash/puzzle<? echo rand(1,13); ?>.swf" />
              <param name="quality" value="high" /><param name="SCALE" value="exactfit" />
              <embed src="http://img.xcombats.com/flash/puzzle<? echo rand(1,13); ?>.swf" width="670" height="428" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" scale="exactfit"></embed>
            </object>
            </div></td>
        </tr>
      </table>
      <b><br />
    </b><small style="color:#999999;"><br />
    </small>
	</center>
	<td width="280" valign="top"><div>

        <div align="right">
          <input onclick="location='main.php?rnd=<? echo $code; ?>';" type="button" value="обновить" />
          <input type="button" value="выйти из кареты" />
          <br />
        </div>
	      <br />
	  <br />
	  </div></td>
</table>
<br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>