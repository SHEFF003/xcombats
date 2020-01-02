<?php
if(!defined('GAME')) { die(); }

if(isset($file) && $file[0] == 'dungeons/trap_portal.php') {
  echo '<input type="button" value="Обновить" onclick="location =\''.$_SERVER['REQUEST_URI'].'\';" /> &nbsp;Вы должны быть перемещены, но портал еще не работает. ';
  $actions = array();
  $action = explode('|', $file[1]);
  foreach($action as $value) {
    $temp = explode(':', $value);
	$actions[$temp[0]] = $temp[1];
  }
  if(isset($actions['x'], $actions['y'])) {
	if(!isset($actions['s'])) { $actions['s'] = 1; }
    if(isset($actions['save_port'])) { $save = ", `res_x` = $actions[x], `res_y` = $actions[y]"; } else { $save = ""; }
	mysql_query('UPDATE `stats` SET `x` = "'.$actions['x'].'", `y` = "'.$actions['y'].'", `s` = "'.$actions['s'].'" '.$save.' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	header('location: main.php');
  }
}