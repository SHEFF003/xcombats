<?php
if(!defined('GAME') || !isset($_GET['referals']))
{
	die();
}

$rfs = array();
$rfs['count'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `host_reg` = "'.$u->info['id'].'" AND `active` = "" LIMIT 1000'));
$rfs['count'] = 0+$rfs['count'][0];
$rfs['c'] = 1;
$rfs['see']   = '';
$sp = mysql_query('SELECT `id`,`level` FROM `users` WHERE `host_reg` = "'.$u->info['id'].'" AND `active` = "" ORDER BY `level` DESC LIMIT '.$rfs['count']);
while($pl = mysql_fetch_array($sp))
{
	$rfs['c2'] = '&nbsp; '.$rfs['c'].'. &nbsp; '.$u->microLogin($pl['id'],1).'<br>';
	if($pl['level']<1)
	{
		$rfs['c2'] = '<font color="grey">'.$rfs['c2'].'</font>';
	}elseif($pl['level']>5)
	{
		$rfs['c2'] = '<font color="green">'.$rfs['c2'].'</font>';
	}
	$rfs['see'] .= $rfs['c2'];
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
        <td colspan="4" align="center"><h4>Рефералы</h4></td>
      </tr>
      <tr>
        <td colspan="4">Ваша уникальная ссылка <input style="background-color:#FBF8E1; border:1px solid #EFDBB6; padding:5px;" size="25" value="xcombats.com/r<?=$u->info['id']?>"  /></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
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
        <td style="text-align:left;"><table width="100%" border="0" cellspacing="5" cellpadding="0">
          <tr>
            <td width="200">Счет зачисления Екр.:</td>
            <td><select name="r_bank" id="r_bank">
            	<? 
					$sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `block` = "0" LIMIT 1');
					while($pl = mysql_fetch_array($sp))
					{
						echo '<option value="'.$pl['id'].'">№ '.$pl['id'].'</option>';
					}
				?>
            </select></td>
          </tr>
          <tr>
            <td>Тип регистрации:</td>
            <td>
            <form method="post" action="main.php?referals">
            <select name="r_type" id="r_type">
              <option value="1">обычная</option>
              <option value="2">премиум</option>
            </select>
            </form></td>
          </tr>
          <tr>
            <td align="right"><input type="submit" name="button" id="button" value="сохранить изменения" /></td>
            <td>&nbsp;</td>
          </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
