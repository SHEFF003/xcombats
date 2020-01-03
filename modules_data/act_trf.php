<?php
if(!defined('GAME'))
{
	die();
}
$red = '';
$odate = time();
if(isset($_POST['date'])) {
	$_POST['date'] = explode('.',$_POST['date']);
	$odate = strtotime($_POST['date'][0].'-'.$_POST['date'][1].'-'.$_POST['date'][2].' 00:00:00');
	if($u->info['money'] >= 0.5) {
		$red = '<font color="red"><b>Отчеты о переводах за '.date('d.m.Y',$odate).' передан Вам и находится в разделе &quot;Заклятия&quot;.</b></font><br>';
		//создаем отчет
		$itm = $u->addItem(2435,$u->info['id'],'noodet=1|noremont=1');
		$u->info['money'] -= 0.5;
		mysql_query('UPDATE `users` SET `money` = `money` - 0.5 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		mysql_query('UPDATE `items_users` SET `use_text` = 100 WHERE `id` = "'.$itm.'" LIMIT 1');
		/* Заносим данные на Бумагу */
		mysql_query('INSERT INTO `items_text` (`item_id`,`time`,`login`,`text`,`city`,`x`,`type`) VALUES ("'.$itm.'","'.time().'","","'.mysql_real_escape_string("Действий и переводов персонажа <B>".$u->info['login']."</B> за <B>".date('d.m.Y',$odate)."</B>.").'","'.$pl['city'].'","1","1")');
		$sp = mysql_query('SELECT * FROM `users_delo` WHERE `uid` = "'.$u->info['id'].'" AND `time` >= "'.$odate.'" AND `time` <= "'.($odate+86399).'" LIMIT 10000');
		$zps = 0;
		while($pl = mysql_fetch_array($sp))
		{
			$dl = explode('.',$pl['login']);
			$se = 0;		
			if($dl[1]=='Shop' || $dl[1] == 'ComShop' || $dl[1]=='EkrShop' || $dl[1]=='EkrShop' || $dl[1]=='Bank' || $dl[1]=='remont' || $dl[1]=='inventory' || $dl[1]=='transfer') {
				$se = 1;
			}			
			if($se==1)
			{
				mysql_query('INSERT INTO `items_text` (`item_id`,`time`,`login`,`text`,`city`,`x`) VALUES ("'.$itm.'","'.$pl['time'].'","'.$pl['login'].'","'.mysql_real_escape_string($pl['text']).'","'.$pl['city'].'","1")');				
				$zps++;
			}
		}
		if($zps == 0)
		{
			mysql_query('INSERT INTO `items_text` (`item_id`,`time`,`login`,`text`,`city`,`x`,`type`) VALUES ("'.$itm.'","'.mysql_real_escape_string($odate).'","Архивариус","'.mysql_real_escape_string("Действий и переводы за данное число отсутствуют.").'","'.$pl['city'].'","1","1")');
		}
	}else{
		echo '<font color="red"><b>У вас недостаточно денег. Стоимость отчета составляет 0.5 кр.</b></font>';
	}
}
?>
<FORM ACTION="main.php?act_trf=1" METHOD=POST>
<P align=right><INPUT class="btnnew2" TYPE=button value="Подсказка" onclick="window.open('/encicl/help/schet.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')">
<INPUT TYPE="button" class="btnnew" onClick="top.frames['main'].location = 'main.php';" value="Вернуться" name=edit></P>
<H3>Отчет о переводах</H3>

Вы можете получить отчет о переводах кредитов/вещей от вас/к вам за указанный день. Услуга платная, стоит <B>0.5 кр.</B><BR>
У вас на счету: <FONT COLOR=339900><B><?=$u->info['money']?></B></FONT> кр.<BR>
Укажите дату, на которую хотите получить отчет: <INPUT TYPE=text NAME=date value="<?=date('d.m.Y',$odate)?>"> <INPUT class="btnnew" TYPE=submit name=schet value="Заказать отчет">
</FORM>
<?='<br>'.$red?>