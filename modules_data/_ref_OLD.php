<?php
if(!defined('GAME') || !isset($_GET['referals']))
{
	die();
}

$rfs = array();
$rfs['count'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `host_reg` = "'.$u->info['id'].'" AND `mail` != "No E-Mail" LIMIT 1000'));
$rfs['count'] = 0+$rfs['count'][0];
$rfs['c'] = 1;
$rfs['data'] = explode('|',$u->info['ref_data']);
if(isset($_POST['r_bank']) || isset($_POST['r_type']))
{
	$bnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.mysql_real_escape_string($_POST['r_bank']).'" AND `uid` = "'.$u->info['id'].'" AND `block` = "0" LIMIT 1'));
	if(!isset($bnk['id']))
	{
		
	}else{
		if($_POST['r_type']==1){ $_POST['r_type'] = 1; }else{ $_POST['r_type'] = 2; }
		$u->info['ref_data'] = $bnk['id'].'|'.$_POST['r_type'];
		$rfs['data'] = explode('|',$u->info['ref_data']);
		mysql_query('UPDATE `stats` SET `ref_data` = "'.mysql_real_escape_string($u->info['ref_data']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
}
$rfs['see']   = '';
$sp = mysql_query('SELECT `s`.`active`,`u`.`online`,`u`.`id`,`u`.`level` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`host_reg` = "'.$u->info['id'].'" AND `u`.`mail` != "No E-Mail" ORDER BY `u`.`level` DESC LIMIT '.$rfs['count']);
while($pl = mysql_fetch_array($sp))
{
	$rfs['c2'] = '&nbsp; '.$rfs['c'].'. &nbsp; '.$u->microLogin($pl['id'],1).'';
	if($pl['active']!='')
	{
		$rfs['c2'] = '<font color="grey">'.$rfs['c2'].' &nbsp; <small>не активирован</small></font>';
	}elseif($pl['level']>5)
	{
		$rfs['c2'] = '<font color="green">'.$rfs['c2'].'</font>';
	}
	if($pl['online'] >time()-520) {
		$rfs['c2'] .= '<font color="green"> &nbsp; <small>ONLINE</small></font>';
	}
	$rfs['see'] .= $rfs['c2'].'<br>';
	$rfs['c']++;
}
if($rfs['see']=='')
{
	$rfs['see'] = '<b>К сожалению у Вас нет рефералов</b>';
}
?>
<table cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td style="vertical-align: top; "><table cellspacing="0" cellpadding="2" width="100%">
      <tr>
        <td colspan="4" align="center"><h4>Как заработать игровую валюту и реальные деньги в БК:</h4></td>
      </tr>
      <tr>
        <td colspan="4">

<u><font color=#003388><b>Активация подарочных ваучеров</b>:</font></u><br>
<form style="padding:10px;" method="post" action="main.php?referals">
Номер: <input type="text" value="" name="va_num" /> &nbsp; Пароль: <input type="password" name="va_psw" value="" /><button type="submit"><small>Активировать</small></button><br />Ссылка на ваучер: <input style="width:280px" type="text" name="va_url" value="" /><br>
</form>
<small><b>Правила размещения ваучера:</b>
<br />- Ваучер должен быть размещен в социальных сетях, либо других сайтах с подробной информацией по его использованию
<br />- Он должен находиться на указанном адресе не менее суток
<br />- Награду за ваучер возможно получить в течении 24 ч. (Защита от "накрутки")
<br />- Для создания собственного ваучера перейдите по ссылке: <a href="В разработке">В разработке</a>
</small>
<br><br>
 
<u><font color=#003388><b>Кредиты</b> можно получить:</font></u> <br>
- набирая опыт в боях и поднимаясь по апам и уровням в соответствии с <a href=http://lib.xcombats.com/main/85 target=_blank>Таблицей Опыта</a> (доступно на любом уровне)<br>
-  в Пещерах: продав ресурсы в Магазин<br>
- с помощью <b>Реферальной системы</b>, которая описана ниже (доступно на любом уровне)<br>
- лечением и другими магическими услугами  (доступно с 4 уровня)<br>
- торговлей (доступно с 4 уровня)<br>
 - в Башне Смерти: обналичив у Архивариуса найденный в башне чек (доступно с 1 уровня)<br>
 <br><br>

<u><font color=#003388><b>Еврокредиты</b> можно получить:</font></u><br>
- с помощью <b>Реферальной системы</b>, которая описана ниже (доступно на любом уровне)<br>
- купив еврокредиты у официальных дилеров БК<br>
<br>  
<br>

<u><font color=#003388><b>Реальные деньги</b> можно получить:</font></u><br>
- с помощью <b>Партнерской программы БК</b>.<br>
<br><br>

<b>Реферальная система</b> - это возможность Вашего <b>дополнительного заработка</b> в игре. При открытии счета в банке, Вы автоматически получаете личную <b>реферальную ссылку</b>, которую можете раздать своим друзьям и знакомым.<br><br>

<b>Каждый персонаж</b>, зарегистрировавшийся в БК по Вашей реферальной ссылке, по достижению им <b>7го</b> уровня начнет приносить Вам <b>дополнительный заработок</b>.<br>
<br>

При достижении Вашим рефералом:<br>
<b>&nbsp;2го</b> уровня - <b>0.50 кр</b>.<br>
<b>&nbsp;4го</b> уровня - <b>1.00 кр</b>.<br>
<b>&nbsp;5го</b> уровня - <b>25.00 кр</b>.<br>
<b>&nbsp;6го</b> уровня - <b>35.00 кр</b>.<br>
<b>&nbsp;7го</b> уровня - <b>75.00 кр</b>.<br>
<b>&nbsp;8го</b> уровня, Вам автоматически будет переведено на  счет <b>1 екр</b>.<br>
<b>&nbsp;9го</b> уровня - <b>5 екр</b>.<br>
<b>10го</b> уровня - <b>15 екр</b>.<br>
<b>11го</b> уровня - <b>35 екр</b>.<br>
<b>12го</b> уровня - <b>50 екр</b>.<br>
        
          <p>&nbsp;</p>
          <p>Ваша уникальная ссылка 
            <input style="background-color:#FBFBFB; border:1px solid #EFEFEF; padding:5px;" size="45" value="xcombats.com/register.php?ref=<?=$u->info['id']?>"  /> или <input style="background-color:#FBFBFB; border:1px solid #EFEFEF; padding:5px;" size="45" value="xcombats.com/r<?=$u->info['id']?>"  />
          </p></td>
      </tr>
      <tr>
        <td colspan="4"><p>&nbsp;</p>
          <ul>
            В реферальной системе отображаются персонажи прошедшие регистрацию
            Выплаты производятся по банковскому счету указаному в настройках системы
          </ul>
          </td>
      </tr>
      <tr>
        <td colspan="4">Количество рефералов: <b><?=$rfs['count']?></b> шт.</td>
      </tr>
      <tr>
        <td colspan="4"><?=$rfs['see']?></td>
      </tr>
    </table></td>
    <td style="width: 5%; vertical-align: top; ">&nbsp;</td>
    <td style="width: 30%; vertical-align: top; "><table width="100%" cellpadding="2" cellspacing="0">
      <tr>
        <td style="width: 25%; vertical-align: top; text-align: right; "><input type='button' value='Обновить' style='width: 75px' onclick='location=&quot;main.php?referals&quot;' />
          &nbsp;
          <input type="button" value="Вернуться" style='width: 75px' onclick='location=&quot;main.php&quot;' /></td>
      </tr>
      <tr>
        <td align="center"><h4>Настройка реферальной системы</h4></td>
      </tr>
      <tr>
        <td style="text-align:left;"><form method="post" action="main.php?referals"><table width="100%" border="0" cellspacing="5" cellpadding="0">
          <tr>
            <td width="200">Счет зачисления Екр.:</td>
            <td>
            <? $bsees = '';
					$sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `block` = "0" LIMIT 1');
					while($pl = mysql_fetch_array($sp))
					{
						if($rfs['data'][0]==$pl['id'])
						{
							$bsees .= '<option selected="selected" value="'.$pl['id'].'">№ '.$pl['id'].'</option>';
						}else{
							$bsees .= '<option value="'.$pl['id'].'">№ '.$pl['id'].'</option>';
						}
					}
					if($bsees != '') {
			?>
            <select name="r_bank" id="r_bank">
            	<? 
					echo $bsees;
				?>
            </select>
            <? }else{
				echo '<b>Для начала откройте счет в банке на страшилкиной улице.</b>';
			}?>
            </td>
          </tr>
          <tr>
            <td align="right"><input type="submit" name="button" id="button" value="сохранить изменения" /></td>
            <td>&nbsp;</td>
          </tr>
          </table></form></td>
      </tr>
    </table></td>
  </tr>
</table>
