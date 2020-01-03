<?
if (!defined('GAME')) {
	die();
}

$action = $_POST['cometome'];
$toUser = mysql_real_escape_string($_POST['logingo']);
if (!isset($u->info['room']) OR !isset($u->info['id']) OR $toUser == '') {
	if($toUser == '') {
		$uer = 'Персонаж "' . $toUser . '" не найден.';
	} else $uer = 'Режим "' . $action . '" не найден.';
} else {
	if ($u->info['admin'] > 0) {
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.$toUser.'" LIMIT 1'));
		$uus = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `id` = "'.$uu['id'].'" LIMIT 1'));
		if (isset($uu['id'])) {
			if ($uu['id'] == $u->info['id']) {
				$uer = 'Вы не можете телепортировать себя к себе, у вас нет раздвоения личности! Если не согласы - обратитесь к психиатру! СРОЧНО! ';
			} elseif ($uu['admin'] > 0 && $u->info['admin'] == 0) {
				$uer = 'Вы не можете телепортировать Ангелов к себе.';
			} elseif (floor($uu['align']) == $a && $uu['align'] > $u->info['align'] && $u->info['admin'] == 0) {
				$uer = 'Вы не можете телепортировать старших по званию';
			} elseif ($u->info['battle'] == 0 && $action == 'to-fight') {
				$uer = 'Вы не можете переместить игрока, поединка не существует.';
			} elseif ($u->info['dnow'] == 0 && $action == 'to-dungeon') {
				$uer = 'Вы не можете переместить игрока, вы не в подземелье.';
			} elseif ($uu['city'] != $u->info['city'] && $u->info['admin'] == 0) {
				$uer = 'Персонаж находится в другом городе';
			} else {
				/* Если перемещаем в комнату - выбрасываем с поединка и\или подземелья. */
				if($action =='to-room' && $uus['dnow']){
					mysql_query('UPDATE `stats` SET `dnow` = "0" WHERE `id` = "' . $uu['id'] . '" LIMIT 1');
				}
				if($action =='to-room' && $uu['battle']){
					mysql_query('UPDATE `users` SET `battle` = "0" WHERE `id` = "' . $uu['id'] .'" LIMIT 1');
				}

				/* Переносим игрока по действию: В комнату, В комнату и поединок, В комнату подземелья*/
				if ($action == 'to-room') {
					$upd = mysql_query('UPDATE `users` SET `city` = "'.$u->info['city'].'", `room` = "'.$u->info['room'].'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				} elseif ($action == 'to-fight') {
					$upd = mysql_query('UPDATE `users` SET `city` = "' . $u->info['city'] . '",`room` = "' . $u->info['room'] . '",`battle` = "' . $u->info['battle'] . '" WHERE `id` = "' . $uu['id'] .'" LIMIT 1');
					$upd_d = mysql_query('UPDATE `stats` SET `team` = "' . $u->info['team'] . '" WHERE `id` = "' . $uu['id'] . '" LIMIT 1');
					if ($u->info['dnow'] > 0) {
						$upd_d = mysql_query('UPDATE `stats` SET `x` = "' . $u->info['x'] . '",`y` = "' . $u->info['y'] . '",`s` = "' . $u->info['s'] . '",`dnow` = "' . $u->info['dnow'] . '" WHERE `id` = "' . $uu['id'] . '" LIMIT 1');
					}
				} elseif ($action == 'to-dungeon') {
					$upd = mysql_query('UPDATE `users` SET `city` = "' . $u->info['city'] . '",`room` = "' . $u->info['room'] . '" WHERE `id` = "' . $uu['id'] . '" LIMIT 1');
					 $upd_d = mysql_query('UPDATE `stats` SET `x` = "' . $u->info['x'] . '",`y` = "' . $u->info['y'] . '",`s` = "' . $u->info['s'] . '",`dnow` = "' . $u->info['dnow'] . '" WHERE `id` = "' . $uu['id'] . '" LIMIT 1');
				} else {
					$uer = 'Ошибка, действие не выбрано.';
					$upd = false;
				}


				if ($upd) {
					if ($upd_d) $dngo = true;
					if ($u->info['sex'] == 1) $sx = 'а'; else $sx = '';
					$rtxt = '[img[items/teleport-cometome.gif]] ' . $rang . ' &quot;' . $u->info['cast_login'] . '&quot; телепортировал' . $sx . ' персонажа &quot;' . $uu['login'] . '&quot; к себе в  ' . ($action == 'to-fight' ? "поединок" . ($dngo == true ? " и пещеру." : "") : ($action == 'to-dungeon' ? "подземелье" : "комнату")) . '.';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'" . $u->info['city'] . "','" . $u->info['room'] . "','','','" . $rtxt . "','" . time() . "','6','0','1')");

					$uer = 'Вы успешно телепортировали к себе персонажа "' . $uu['login'] . '" в <b>' . ($action == 'to-fight' ? "поединок" : ($action == 'to-dungeon' ? "подземелье" : "комнату")) . '</b>.';
				} else {
					$uer = 'Не удалось использовать данное заклятие';
				}
			}
		} else {
			$uer = 'Персонаж не найден в этом городе';
		}
	} else {
		$uer = 'Заклинание, только для администрации';
	}
}

?>