<?
if(!defined('GAME'))
{
	die();
}

if( $u->info['clan'] == 0 ) {
	$u->error = 'Вы не состоите в клане!';
	}elseif( $itm['id'] > 0 ) {
	$u->deleteItem($itm['id']);
    mysql_query("UPDATE `clan` SET `exp`=`exp`+'5000' WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."' LIMIT 1");
	$u->error = 'Вы увеличили клановый опыт +5000';
	mysql_query('INSERT INTO `clan_news` (
		`clan`,`time`,`ddmmyyyy`,`uid`,`login`,`title`,`text`
	) VALUES (
		"'.$u->info['clan'].'","'.time().'","'.date('d.m.Y').'","0","Администрация","Повышение кланового опыта","'.$u->microLogin2($u->info).' повысил клановый опыт при помощи свитка на +5000 ед."
	)');
	}
?>