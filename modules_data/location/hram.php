<?php
if(!defined('GAME'))
{
 die();
}

if($u->room['file']=='hram')
{
	if(isset($_GET['itm']))
	{
		$_GET['itm'] = (int)$_GET['r'];
		if($_GET['itm']>0)
		{
			if($_GET['r']!=1)
			{
				//Переплавка вещей
				$resz = $u->plavka($_GET['itm'],1);
				echo '<font color=red><b>'.$resz.'</b></font>';
				unset($resz);
			}else{
				//Переплавка рун
				
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
	<tr><td valign="top"><div align="center" class="pH3">Храм Знаний <? if($_GET['r']==1){ echo ', Алтарь рун'; }else{ echo ', Алтарь предметов'; } ?></div>
	<td width="280" valign="top"><table align="right" cellpadding="0" cellspacing="0">
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
							  <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=3.180.0.267&rnd=<? echo $code; ?>';" title="<? thisInfRm('3.180.0.267',1); ?>">Центральная площадь</a></td>
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
		<br /><br />
	  <input type="button" value="Алтарь предметов" onclick="location = '?r=0';" /> &nbsp; <input type="button" value="Алтарь рун" onclick="location = '?r=1';" />
	</td>
	</table>
	<div id="textgo" style="visibility:hidden;"></div>
    <? if($_GET['r']!=1){
	$itmAll = ''; $itmAllSee = '';
	$itmAll = $u->genInv(11,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD` DESC');
	if($itmAll[0]==0)
	{
		$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО (нет подходящих предметов)</td></tr>';
	}else{
		$itmAllSee = $itmAll[2];
	}
	//Удачно растворен предмет "Укрепленный Костыль". Получена руна "Моно Бауни".
	?>
    <script>
	function takeItRun(img,id,vl)
	{
		if(id!=urlras)
		{
			urlras = id;
			document.getElementById('use_item').innerHTML = '<img src="http://<?=$c['img'];?>/i/items/'+img+'" title="Предмет для переплавки"/><br><a href="javascript:void(0);" onClick="cancelItRun()">Отменить</a>';
			document.getElementById('add_rep').innerHTML = ' + '+vl;
		}else{
			cancelItRun();
		}
	}
	function cancelItRun()
	{
		urlras = 0;
		document.getElementById('use_item').innerHTML = 'Предмет не выбран';
		document.getElementById('add_rep').innerHTML = '';
	}
	urlras = 0;
    </script>
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
   	  <tr>
    	    <td width="300" valign="top">
            <b>Репутация: <? echo 0+$u->info['rep1']; ?></b><span id="add_rep"></span>
            <br /><br /><center><span id="use_item">Предмет не выбран</span><br /><br />
            <input type="button" value="Растворить" onclick="location = '?r=<?=$_GET['r'].'&rnd='.$code.'&itm=';?>'+urlras;" /></center>
            <br />
            <br /><small>
            <font color="red">Внимание!</font><br />
			Предметы при растворении и руны при слиянии необратимо теряются.</small>
            </td>
    	    <td valign="top">
            <!-- -->
            <table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">
            <? if($u->info['invBlock']==0){ echo $itmAllSee; }else{ echo '<div align="center" style="padding:10px;background-color:#A5A5A5;"><form method="post" action="main.php?inv=1&otdel='.$_GET['otdel'].'&relockinvent"><b>Рюкзак закрыт.</b><br><img title="Замок для рюкзака" src="http://img.xcombats.com/i/items/box_lock.gif"> Введите пароль: <input id="relockInv" name="relockInv" type="password"><input type="submit" value="Открыть"></form></div>'; } ?>
            </table>
            <!-- -->
            </td>
      </tr>
    </table>
<? }else{ ?>
    	&nbsp; По всей видимости Алтарь рун был разрушен... <b>Лорд разрушитель</b> не дремлет...
<? } } ?>