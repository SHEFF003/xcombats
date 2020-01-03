<?php
if(!defined('GAME') || !isset($_GET['referals']))
{
	die();
}

$tal = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "4005" AND `delete` < 1234567890'));

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
$sp = mysql_query('SELECT `s`.`active`,`u`.`activ`,`u`.`online`,`u`.`id`,`u`.`level`,`u`.`login` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`host_reg` = "'.$u->info['id'].'" AND `u`.`mail` != "No E-Mail" ORDER BY `u`.`level` DESC LIMIT '.$rfs['count']);
while($pl = mysql_fetch_array($sp))
{
	$rfs['c2'] = '&nbsp;<img onclick="top.chat.addto(\''.$pl['login'].'\',\'private\')" style="display:inline-block;cursor:pointer;" src="http://img.xcombats.com/i/lock.gif" width="20" height="15"> &nbsp; '.$u->microLogin($pl['id'],1).'';
	if($pl['activ'] != 0)
	{
		$rfs['c2'] = '<font color="grey">'.$rfs['c2'].' &nbsp; <small>не активирован</small></font>';
	}elseif($pl['level']>7)
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
	$rfs['see'] = '<center><b>К сожалению, у Вас нет воспитанников. Пригласите друзей сейчас!</b></center>';
}
if(isset($_GET['nastanew'])) {
	/*$_GET['nastanew'] = htmlspecialchars($_GET['nastanew'],NULL,'cp1251');
	$upr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`online`,`admin`,`banned`,`level`,`host_reg` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_GET['nastanew']).'" ORDER BY `id` ASC LIMIT 1'));
	if(isset($upr['id']) && $upr['inUser'] == 0) {
		$ch1 = mysql_fetch_array(mysql_query('SELECT * FROM `chat` WHERE `type` = 90 AND `to` = "'.$upr['login'].'" AND `time` > '.(time()-3600).' AND `login` = "'.$u->info['login'].'" LIMIT 1'));
		if(isset($ch1['id'])) {
			$u->error = 'Вы уже отправляли приглашение персонажу &quot;'.$upr['login'].'&quot;. (Не чаще одного раза в час)';
		}elseif($upr['login'] == $u->info['login']) {
			$u->error = 'Хитро :) Наверное долго думали над этим?';
		}elseif($upr['level'] > 9 && $u->info['admin'] == 0) {
			$u->error = 'Нельзя стать наставником персонажа старше 9-го уровня';
		}elseif($upr['id'] == $u->info['host_reg']) {
			$u->error = 'Нельзя стать воспитанником своего наставника';
		}elseif($upr['online'] > time()-520) {
			if(is_int($upr['host_reg']) || $upr['host_reg'] > 0) {
				$u->error = 'У персонажа &quot;'.$upr['login'].'&quot; уже есть наставник.';
			}else{
				$u->error = 'Вы выслали приглашение персонажу &quot;'.$upr['login'].'&quot; стать вашим воспитанником.';	
				mysql_query('INSERT INTO `chat` (`login`,`to`,`type`,`new`,`time`) VALUES ("'.$u->info['login'].'","'.$upr['login'].'","90","1","'.time().'")');
			}
		}else{
			$u->error = 'Персонаж &quot;'.$upr['login'].'&quot; должен быть в онлайне.';	
		}
	}else{
		$u->error = 'Персонаж с логином &quot;'.$_GET['nastanew'].'&quot; не найден.';	
	}*/
}elseif(isset($_GET['nastayes'])) {
	/*$_GET['nastayes'] = htmlspecialchars($_GET['nastayes'],NULL,'cp1251');
	$upr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`online`,`admin`,`banned`,`level`,`host_reg` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_GET['nastayes']).'" LIMIT 1'));
	if(isset($upr['id'])) {
		$ch1 = mysql_fetch_array(mysql_query('SELECT * FROM `chat` WHERE `type` = 90 AND `to` = "'.$u->info['login'].'" AND `delete` > 0 AND `login` = "'.$upr['login'].'" LIMIT 1'));
		if(isset($ch1['id'])) {
			$myna = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.mysql_real_escape_string($u->info['host_reg']).'" LIMIT 1'));
			if(isset($myna['id'])) {
				$u->error = 'У вас уже есть наставник.';
			}else{
				$u->error = 'Персонаж &quot;'.$_GET['nastayes'].'&quot; стал вашим наставником!';
				mysql_query('UPDATE `users` SET `host_reg` = "'.$upr['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$upr['login']."',' Персонаж &quot;".$u->info['login']."&quot; подтвердил что он ваш воспитанник. Вы получаете <b>Талант Наставника</b> (<small>x1</small>).','-1','6','0')");
				$u->addItem(4005,$upr['id']);
			}
		}else{
			$u->error = 'Персонаж &quot;'.$_GET['nastayes'].'&quot; не отправлял вам заявок наставника.';	
		}
	}else{
		$u->error = 'Персонаж &quot;'.$_GET['nastayes'].'&quot; не отправлял вам заявок наставника.';	
	}*/
}

?>
<table cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td style="vertical-align: top; "><table cellspacing="0" cellpadding="2" width="100%">
      <tr>
        <td align="left">
        <?
		if($u->error != '') {
			echo '<font color=red>'.$u->error.'</font><br>';
		}
		/*
        <input type="button" onclick="top.nastavniknew()" value="Предложить наставничество">
        */
		?> Ссылка для друзей: <input disabled="disabled" style="background-color:#FBFBFB; width:100px; border:1px solid #EFEFEF; padding:5px;" size="45" value="xcombats.com/r<?=$u->info['id']?>"  />
        </td>
      </tr>
    </table>
      <table cellspacing="0" cellpadding="2" width="100%">
        <tr>
          <td align="center"><h4>Как заработать игровую валюту и реальные деньги в БК:</h4></td>
        </tr>
        <tr>
          <td>
          <? if( true == false ) { ?>
          <u><font color="#003388"><b>Активация подарочных ваучеров</b>:</font></u><br />
            <form style="padding:10px;" method="post" action="main.php?referals">
              Номер:
              <input type="text" value="" name="va_num" />
              &nbsp; Пароль:
              <input type="password" name="va_psw" value="" />
              <button type="submit" class="btnnew"><small>Активировать</small></button>
              <br />
              Ссылка на ваучер:
              <input style="width:280px" type="text" name="va_url" value="" />
              <br />
            </form>
            <small><b>Правила размещения ваучера:</b> <br />
              - Ваучер должен быть размещен в социальных сетях, либо других сайтах с подробной информацией по его использованию <br />
              - Он должен находиться на указанном адресе не менее суток <br />
              - Награду за ваучер возможно получить в течении 24 ч. (Защита от &quot;накрутки&quot;) <br />
              - Для создания собственного ваучера перейдите по ссылке: <a href="#">В разработке</a> </small> <br />
            <br />
            <? } ?>
            <?
			$rpgtop = mysql_fetch_array(mysql_query('SELECT * FROM `an_data` WHERE `var` = "rpgtop" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
			if(!isset($rpgtop['id'])) {
				if(isset($_GET['testtoprpg'])) {
					
					$html = file_get_contents('http://rpgtop.su/comm/t1/24296/1.htm');					
					$html = strripos($html,'*ID'.$u->info['id'].'*');
					
					if($html == false) {
						$html = file_get_contents('http://rpgtop.su/comm/t2/24296/1.htm');						
						$html = strripos($html,'*ID'.$u->info['id'].'*');
					}
					
					if($html == true) {
						echo '<div><b><font color=red>Спасибо что проголосовали за наш проект! Свитки добавлены к вам в инвентарь.</font></b></div>';
						mysql_query('INSERT INTO `an_data` (`uid`,`time`,`var`) VALUES (
							"'.$u->info['id'].'","'.time().'","rpgtop"
						)');
						$rpgtop['id'] = mysql_insert_id();
						// Звездное сияние (+10) и ЖЖ6
						$u->addItem(1463,$u->info['id'],'|nosale=1|notransfer=1|sudba=1|noremont=1');
						$u->addItem(3101,$u->info['id'],'|nosale=1|notransfer=1|sudba=1|noremont=1');
						//
					}else{
						echo '<div><b><font color=red>Ваш отзыв не найден в положительных.</font></b></div>';
					}					
				}
			}
			if(!isset($rpgtop['id'])) {
			?>
           <!-- <u><font color="#003388"><b>Награда</b> за положительный отзыв на сайте <a href="http://rpgtop.su/comm/24296/1.htm" target="_blank">rpgtop.su</a>:</font></u><br />
            1. Перейдите по ссылке <a href="http://rpgtop.su/comm/24296/1.htm" target="_blank">http://rpgtop.su/comm/24296/1.htm</a><br>
            2. Оставьте положительный отзыв и в конце допишите текст в ковычках: " <b> *ID<?=$u->info['id']?>* </b> " (вместе с звездочками)<br>
            3. Нажмите на кнопку "Проверить мой отзыв"<br>
            4. При успешном подтверждении вы получите <a href="http://xcombats.com/item/1463" target="_blank"><img src="http://img.xcombats.com/i/items/spell_starshine.gif" height="20"> Звездное Сияние</a> и <a href="http://xcombats.com/item/3101" target="_blank"><img src="http://img.xcombats.com/i/items/spell_powerHPup6.gif" height="20"> Жажда Жизни +6</a><br>
            <input onclick="location.href='http://xcombats.com/main.php?referals&testtoprpg';" type="button" class="btnnew" value="Проверить мой отзыв"><br><br>-->
            <?
			}
			?>
            <u><font color="#003388"><b>Кредиты</b> можно получить:</font></u> <br />
            - набирая опыт в боях и поднимаясь по апам и уровням в соответствии с <a href="http://xcombats.com/exp.php" target="_blank">Таблицей Опыта</a> (доступно на любом уровне)<br />
            -  в Пещерах: продав ресурсы в Магазин<br />
            - с помощью <b>Реферальной системы</b>, которая описана ниже (доступно на любом уровне)<br />
            - лечением и другими магическими услугами  (доступно с 4 уровня)<br />
            - торговлей (доступно с 4 уровня)<br />
            - в Башне Смерти: обналичив у Архивариуса найденный в башне чек (доступно с 5 уровня)<br />
            <br />
            <br />
            <u><font color="#003388"><b>Еврокредиты</b> можно получить:</font></u><br />
            - с помощью <b>Реферальной системы</b>, которая описана ниже (доступно на любом уровне)<br />
            - купив еврокредиты у официальных дилеров БК или через систему автооплаты<br />
            <br />
            <br />
            <u><font color="#003388"><b>Реальные деньги</b> можно получить:</font></u><br />
            - с помощью <b>Партнерской программы БК</b>.<br />
            <br />
            <br />
            <b>Реферальная система</b> - это возможность Вашего <b>дополнительного заработка</b> в игре. При открытии счета в банке, Вы автоматически получаете личную <b>реферальную ссылку</b>, которую можете раздать своим друзьям и знакомым.<br />
            <br />
            <b>Каждый персонаж</b>, зарегистрировавшийся в БК по Вашей реферальной ссылке, по достижению им <b>1го</b> уровня начнет приносить Вам <b>дополнительный заработок</b>.</td>
        </tr>
        <tr>
          <td><p>&nbsp;</p>
            <ul>
              В реферальной системе отображаются персонажи прошедшие регистрацию
              Выплаты производятся по банковскому счету указаному в настройках системы
            </ul></td>
        </tr>
    </table></td>
    <td style="width: 5%; vertical-align: top; ">&nbsp;</td>
    <td style="width: 30%; vertical-align: top; "><table width="100%" cellpadding="2" cellspacing="0">
      <tr>
        <td style="width: 25%; vertical-align: top; text-align: right; "><input class="btnnew" type='button' value='Обновить' style='width: 75px' onclick='location=&quot;main.php?referals&quot;' />
          &nbsp;
          <input type="button" value="Вернуться" style='width: 75px' class="btnnew" onclick='location=&quot;main.php&quot;' /></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><br />
		  <? if( $u->info['host_reg'] > 0 ) {
       			echo 'Ваш наставник: '.$u->microLogin($u->info['host_reg'],1).'<hr />';/*
				$nas = mysql_fetch_array(mysql_query('SELECT `id`,`banned`,`room`,`login`,`align`,`level`,`city`,`room`,`online` FROM `users` WHERE `id` = "'.mysql_real_escape_string($u->info['host_reg']).'" LIMIT 1'));
		   		if(isset($nas['id'])) {
					$itm0 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = 4004 AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
					$itm0 = $itm0[0];
				}
				if(isset($nas['id']) && $itm0 > 0) {
					if(isset($_GET['read_pr'])) {
						$itm0--;
					}
					echo 'Вы можете изучить приемы наставника:<br><small>Осталось <b>'.$itm0.'</b> <u>Учебников воспитанника</u>.</small><br>';
					if($nas['banned'] > 0 || $nas['align'] == 2) {
						echo '<font color=red><b>Ваш наставник в хаосе или заблокирован.</b></font>';
					}elseif($nas['room'] != $u->info['room'] && $nas['online'] > time()-520 ) {
						echo '<font color=red><b>Вы должны находиться с наставником в одной комнате</b></font>';
					}else{
						$priz = '';
						$sp = mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$nas['id'].'" AND `vars` LIKE "%read%" AND `vals` > 1042 ORDER BY `vals` ASC');
						while($pl = mysql_fetch_array($sp)) {
							$tstsp = mysql_fetch_array(mysql_query('SELECT `id` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` LIKE "%read%" AND `vals` = "'.$pl['vals'].'" LIMIT 1'));
							if(!isset($tstsp['id'])) {
								$prm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl['vals'].'" LIMIT 1'));
								if(isset($prm['id'])) {
									if(isset($_GET['read_pr']) && $_GET['read_pr'] == $prm['id']) {
										mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$u->info['login']."','Вы успешно изучили прием <b>".$prm['name']."</b> при помощи Учебника воспитанника. ','".time()."','6','0')");
										mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$nas['login']."',' Ваш воспитанник &quot;".$u->info['login']."&quot; изучил прием <b>".$prm['name']."</b> при помощи Учебника воспитанника. <br><b>Вы получили Талант Наставника x3</b>','-1','6','0')");
										$u->addItem(4005,$nas['id']);
										$u->addItem(4005,$nas['id']);
										$u->addItem(4005,$nas['id']);
										mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`,`vals`,`val`) VALUES (
											"'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","read","'.$u->info['ip'].'",
											"'.$prm['id'].'",""
										)');
										mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = 4004 AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1');
										echo '<font color=red><b>Прием &quot;'.$prm['name'].'&quot; был успешно изучен!</b></font><br>';
									}else{
										$priz .= '<a href="?referals&read_pr='.$prm['id'].'"><img width="40" height="25" title="Изучить &quot;'.$prm['name'].'&quot;" src="http://img.xcombats.com/i/eff/'.$prm['img'].'"></a> ';
									}
								}
							}
						}
						if($priz == '') {
							echo '<font color=red><b>У наставника нет изученных приемов которые Вы могли бы получить</b></font>';
						}else{
							echo $priz;
						}
					}
					echo '<hr>';
				}*/
		   }
	   ?>
          <div style="display:inline-block;width:300px;" align="left">
            <center>
              <b>Заработок на рефералах</b>
              </center><br />
            <? $bsees = '<option value="0">Выберите счет</option>';
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
			?><center><form method="post" action="main.php?referals">
              Счет для екр.:
              <select name="r_bank" id="r_bank">
                <? 
					echo $bsees;
				?>
                </select>
              <input type="submit" name="button" class="btnnew" id="button" value="сохранить" /></form></center>
            <? }else{
				echo '<b style="color:red">Для начала откройте счет в банке на страшилкиной улице.</b>';
			}?><br />
            <?
	   $r = '<b><small>За достижения реферала вы получите</small></b>:<br>';
	   $sp = mysql_query('SELECT * FROM `referal_bous` WHERE `type` = "1"');
	   while($pl = mysql_fetch_array($sp)) {
		   if($pl['add_bank'] > 0 || $pl['add_money'] > 0) {
		   		$r .= '<span style="display:inline-block;width:90px;">'.$pl['level'].' уровень</span> - '; 
				if($pl['add_money'] > 0) { 
					$r .= '<span style="display:inline-block;width:90px;"> '.$pl['add_money'].' кр.</span>';
				}
				if($pl['add_bank'] > 0) {
					$r .= '<span style="display:inline-block;width:90px;"> '.$pl['add_bank'].' ekr.</span>';
				}
				$r .= '<br>';
		   }
	   }	   
	   echo $r;
	   /*
	   ?>
       		<br />
       		<b>Получить награду за beta-тест</b><br />
            Введите логин и пароль с beta-теста:<br />
            <?
			if(isset($_POST['betalogin'])) {
				$beta = mysql_fetch_array(mysql_query('SELECT * FROM `beta_testers` WHERE `login` = "'.mysql_real_escape_string($_POST['betalogin']).'" LIMIT 1'));
				$beta2 = mysql_fetch_array(mysql_query('SELECT * FROM `beta_testers` WHERE `active` = "'.$u->info['id'].'" LIMIT 1'));
				if(!isset($beta['id'])) {
					echo '<font color=red><b>Логин beta-тестера не найден</b></font>';
				}elseif(md5($_POST['betapass']) != $beta['pass']) {
					echo '<font color=red><b>Укажите пароль который был во время beta-теста</b></font>';
				}elseif(isset($beta2['id'])) {
					echo '<font color=red><b>Вы уже получали вознаграждение!</b></font>';
				}else{
					echo '<font color=red><b>Вы успешно получили вознаграждение, значок beta-тестера.</b></font>';
					//значок в инфо
					mysql_query('INSERT INTO `users_ico` (
						`uid`,`time`,`text`,`img`,`type`,`x`
					) VALUES (
						"'.$u->info['id'].'","'.time().'","<b>beta-тестер</b><br>Благодарность от Администрации проекта.","icn123.gif","1","1"
					)');
					//
					mysql_query('UPDATE `beta_testers` SET `active` = "'.$u->info['id'].'" WHERE `id` = "'.$beta['id'].'" LIMIT 1');
				}
			}
			?>
                <div align="center">
                <form style="width:144px;" method="post" action="main.php?referals=1">
                    <input style="width:144px;" name="betalogin" value="" type="text" /><br />
                    <input style="width:144px;" name="betapass" value="" type="password" /><br />
                    <input style="width:144px;" type="submit" class="btnnew" value="Забрать вознаграждение!" />
                </form>
                </div><? */ ?>
            </div>			
          </td>
      </tr>
      <tr>
        <td align="center"><h4>Ваши воспитанники online:</h4></td>
      </tr>
      <tr>
        <td><?=$rfs['see']?></td>
      </tr>
      <tr>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
