<?
if(!defined('GAME'))
{
	die();
}

if( $u->info['clan'] == 0 ) {
	$u->error = '�� �� �������� � �����!';
	}elseif( $itm['id'] > 0 ) {
	$u->deleteItem($itm['id']);
    mysql_query("UPDATE `clan` SET `exp`=`exp`+'2500' WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."' LIMIT 1");
	$u->error = '�� ��������� �������� ���� +2500';
	mysql_query('INSERT INTO `clan_news` (
		`clan`,`time`,`ddmmyyyy`,`uid`,`login`,`title`,`text`
	) VALUES (
		"'.$u->info['clan'].'","'.time().'","'.date('d.m.Y').'","0","�������������","��������� ��������� �����","'.$u->microLogin2($u->info).' ������� �������� ���� ��� ������ ������ �� +2500 ��."
	)');
	}
?>