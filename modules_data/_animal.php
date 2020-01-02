<?php
if(!defined('GAME'))
{
	die();
}
$err = '';

$an = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `id` = "'.$u->info['animal'].'" LIMIT 1'));
if(!isset($an['id']))
{
	echo '<br><br><br><br><center>Зверь не найден...</center>';
}else{
if(isset($_GET['delete']) && $_GET['delete'] == $an['id']) {
	echo '<font color="red">Зверь был выгнан...</font>';
	mysql_query('UPDATE `users` SET `animal` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	mysql_query('UPDATE `users_animal` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.$an['id'].'" AND `delete` = "0" LIMIT 1');
}elseif(isset($_GET['anml_login']) && $an['rename'] == 0) {
	$n = 1;
	function en_ru($txt)
	{
		$g = false;
		$en = preg_match("/^(([a-zA-Z _-])+)$/i", $txt);
		$ru = preg_match("/^(([а-яА-Я _-])+)$/i", $txt);
		if(($ru && $en) || (!$ru && !$en))
		{
			$g = true;
		}
		return $g;
	}
	$nl = htmlspecialchars($_GET['anml_login'],NULL,'cp1251');
	$nl = str_replace(' ','',$nl);
	$nl = str_replace('	','',$nl);
	$sr = "!@#$%^&*()\+Ёё|/'`\"-_";
	if($nl == '' || strlen($nl) > 10 || strlen($nl) < 2 || en_ru($nl) == true || strpos($sr,$nl)) {
		$n = 0;
	}else{
		
	}
	
	
	if($n == 1) {
		mysql_query('UPDATE `users_animal` SET `name` = "'.mysql_real_escape_string($nl).'",`rename` = "1" WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.$an['id'].'" AND `delete` = "0" LIMIT 1');
		$an['rename'] = 1;
		echo '<font color="red">Вы успешно переименовали питомца в &quot;'.$nl.'&quot;</font>';
	}else{
		echo '<font color="red">Эта кличка не подходит</font>';
	}
}

$anl = mysql_fetch_array(mysql_query('SELECT `bonus` FROM `levels_animal` WHERE `type` = "'.$an['type'].'" AND `level` = "'.$an['level'].'" LIMIT 1'));
$anl = $anl['bonus'];
$anl = $u->lookStats($anl);

$nam = array(1=>'Кот',2=>'Сова',3=>'Светляк',4=>'Чертяка',5=>'Собака',6=>'Свинья',7=>'Дракон');
$sab = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$nam[$an['type']].' ['.$an['level'].']" LIMIT 1'));
$sa = $u->lookStats($an['stats']);
$sa['hpAll'] += 30+$sa['s4']*6+$sa['hpAll'];
$ne = '';

if(!isset($ne['id']))
{
	$ne['exp'] = '??';
	if($an['exp'] < 110) {
		$ne['exp'] = '110';
	}elseif($an['exp'] < 410) {
		$ne['exp'] = '410';
	}elseif($an['exp'] < 1300) {
		$ne['exp'] = '1300';
	}elseif($an['exp'] < 2500) {
		$ne['exp'] = '2500';
	}elseif($an['exp'] < 5000) {
		$ne['exp'] = '5000';
	}elseif($an['exp'] < 12500) {
		$ne['exp'] = '12500';
	}elseif($an['exp'] < 30000) {
		$ne['exp'] = '30000';
	}elseif($an['exp'] < 300000) {
		$ne['exp'] = '300000';
	}elseif($an['exp'] < 3000000) {
		$ne['exp'] = '3000000';
	}elseif($an['exp'] < 10000000) {
		$ne['exp'] = '10000000';
	}elseif($an['exp'] < 52000000) {
		$ne['exp'] = '52000000';
	}elseif($an['exp'] < 120000000) {
		$ne['exp'] = '120000000';
	}
}
 
if(isset($_GET['obj_corm'])) {
	$corm = mysql_fetch_array(mysql_query('SELECT `iu`.*,`im`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `iu`.`item_id` = `im`.`id` WHERE `im`.`type` = "49" AND `iu`.`delete` = "0" AND `iu`.`inShop` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inTransfer` = "0" AND `iu`.`id` = "'.mysql_real_escape_string($_GET['obj_corm']).'" LIMIT 1'));
	if(isset($corm['id'])) {
		//кормушка зверя
		$see1 = 1;
		if($an['type'] == 3 && substr_count($corm['img'],'wisp') == 0) {
			//светляк
			$see1 = 0;
		}elseif($an['type'] == 2 && substr_count($corm['img'],'owl') == 0) {
			//сова
			$see1 = 0;
		}elseif($an['type'] == 1 && substr_count($corm['img'],'cat') == 0) {
			//кот
			$see1 = 0;
		}elseif($an['type'] == 4 && substr_count($corm['img'],'chrt') == 0) {
			//чертяка
			$see1 = 0;
		}elseif($an['type'] == 5 && substr_count($corm['img'],'dog') == 0) {
			//собака
			$see1 = 0;
		}elseif($an['type'] == 6 && substr_count($corm['img'],'pig') == 0) {
			//свинья
			$see1 = 0;
		}elseif($an['type'] == 7 && substr_count($corm['img'],'dragon') == 0) {
			//дракон
			$see1 = 0;
		}
		if($see1 == 1) {
			if($an['yad'] > time()) {
				$err = '&quot;'.$an['name'].'&quot; отвернулся от еды...';
			}elseif($an['eda'] < 50) {
				$po = $u->lookStats($corm['data']);
				$corm['level'] = $po['tr_lvl'];
				if($an['level'] >= $corm['level']) {
					$rzc = $an['level']-$corm['level'];
					if($rzc <= 0) {
						$rzc = 20;
					}elseif($rzc == 1) {
						$rzc = 16;
					}elseif($rzc == 2) {
						$rzc = 12;
					}elseif($rzc == 3) {
						$rzc = 8;
					}elseif($rzc >= 4) {
						$rzc = 4;
					}elseif($rzc >= 5) {
						$rzc = 2;
					}
					$an['eda'] += $rzc;
					if($an['eda'] > 50) {
						$an['eda'] = 50;
					}
					mysql_query('UPDATE `users_animal` SET `eda` = "'.$an['eda'].'" WHERE `id` = "'.$an['id'].'" LIMIT 1');
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($_GET['obj_corm']).'" LIMIT 1');
					$err = 'Ваш питомец был успешно накормлен...';
				}else{
					$err = 'Ваш питомец пока-что не может употреблять подобную пищу ...';
				}				
			}else{
				$err = 'Ваш питомец сыт...';
			}
		}else{
			$err = 'Ваш питомец не употребляет подобную пищу...';
		}
	}else{
		$err = 'Предмет не найден';
	}
}
 
$itmAll = $u->genInv(15,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `im`.`type` = "49" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `name` ASC');
if($itmAll[0] != 0) {
	$itmAll = $itmAll[2];
}else{
	$itmAll = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="350" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="150" align="center" valign="top"><b><?=$an['name']?></b> [<?=$an['level']?>]<br><img src="http://img.xcombats.com/i/obraz/<?=$an['sex']?>/<?=$an['obraz']?>.gif" width="120" height="220"></td>
        <td valign="top"><p>HP: <?=$sa['hpAll']?></p>
          <p>Сила: <?=$sa['s1']?><br>Ловкость: <?=$sa['s2']?><br>Интуиция: <?=$sa['s3']?><br>Выносливость: <?=$sa['s4']?></p>
          <p>Уровень: <?=$an['level']?><br>Опыт: <?=$an['exp']?> / <?=$ne['exp']?><br>
          Сытость: <?=$an['eda']?></p>
          <p>
          Освоенные навыки:<br>
          &bull; <i>Отсутствуют</i></p>
          <p>
          Боевые бонусы:<br>
          <?
		  $ba = '';
			$i = 0;
			while($i<count($u->items['add'])) {
				if(isset($anl['add_'.$u->items['add'][$i]])) {
					if( $u->items['add'][$i] == 'mib1' ) {
						$ba .= '&bull; Броня головы: +'.$anl['add_'.$u->items['add'][$i]].'<br>';
					}elseif( $u->items['add'][$i] == 'mib2' ) {
						$ba .= '&bull; Броня корпуса: +'.$anl['add_'.$u->items['add'][$i]].'<br>';
					}elseif( $u->items['add'][$i] == 'mib3' ) {
						$ba .= '&bull; Броня пояса: +'.$anl['add_'.$u->items['add'][$i]].'<br>';
					}elseif( $u->items['add'][$i] == 'mib4' ) {
						$ba .= '&bull; Броня ног: +'.$anl['add_'.$u->items['add'][$i]].'<br>';
					}elseif( $u->items['add'][$i] == 'mab1' || $u->items['add'][$i] == 'mab2' || $u->items['add'][$i] == 'mab3' || $u->items['add'][$i] == 'mab4') {
						
					}else{
						$ba .= '&bull; '.$u->is[$u->items['add'][$i]].': +'.$anl['add_'.$u->items['add'][$i]].'<br>';
					}
				}
				$i++;
			}
		  
		  if($ba == '') {
			 $ba = '&bull; <i>Отсутствуют</i>'; 
		  }
		  echo $ba;
		  ?>
          </p>
          </td>
      </tr>
    </table></td>
    <td valign="top">
    <div>
    	<div style="float:left"><? if($an['rename'] == 0) { ?><input type="button" onclick="top.anren();" value="Кличка" /><? } ?> <input type="button" onclick="if(confirm('Выгнать зверя?')){top.frames['main'].location='main.php?pet=1&delete=<?=$an['id']?>&rnd=<?=$code?>'}" value="Выгнать" /></div>
    	<div style="float:right"><input type="button" onclick="top.frames['main'].location='main.php?pet=1&rnd=<?=$code?>'" value="Обновить" /> <input type="button" onclick="top.frames['main'].location='main.php?rnd=<?=$code?>'" value="Вернуться" /></div>
    </div>
    <div><br />
    <?
	if($err != '') {
		echo '<br><font color="red"><b>'.$err.'</b></font>';
	}
	?>
    <br />
    <table width="100%" style="border:1px solid #9E9E9E">
    <? echo $itmAll; ?>
    </table>
    </div>
    </td>
  </tr>
</table>
<? } ?>