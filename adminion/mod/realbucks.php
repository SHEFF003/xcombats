<?
if(!defined('GAME'))
{
	die();
}

echo 'Реальщики:<br><br>';
$html = '';
$sp = mysql_query('SELECT `id`,`login`,`level`,`host_reg` FROM `users` WHERE `admin` = 0 AND `id` IN ( SELECT `uid` FROM `items_users` WHERE `delete` = 0 AND `2price` > 5 ) ORDER BY `timereg` DESC');
while( $pl = mysql_fetch_array($sp) ) {
	$i = 0;
	$html1 = '';
	$html1 .= '<a href="/inf.php?'.$pl['id'].'" target="_blank">'.$pl['login'].'</a> ['.$pl['level'].']<br><small>';
	$sp1 = mysql_query('SELECT `a`.*,`b`.* FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`delete` = 0 AND `b`.`inSlot` < 20 AND `a`.`uid` = "'.$pl['id'].'" AND `a`.`2price` > 5');
	while( $pl = mysql_fetch_array($sp1) ) {
		$html1 .= '- '.$pl['name'].' ('.$pl['2price'].' екр.)<br>';
		$i++;
	}
	$html1 .= '</small><hr>';
	if( $i > 0 ) {
		$html .= $html1;
		$html1 = '';
	}
}

echo $html;
?>