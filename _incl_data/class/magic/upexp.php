<?
if(!defined('GAME'))
{
	die();
}

if( $u->info['twink'] != 0 ) {
	$u->error = '���������� ������������ &quot;'.$itm['name'].'&quot;. ������� �� ��������� ���������!';
}elseif( $u->info['activ'] != 0 ) {
	$u->error = '���������� ������������ &quot;'.$itm['name'].'&quot;. ����������� ��������� ����� E-mail!';
}elseif( $itm['id'] > 0 ) {
	$st['expUpg'] += 0;
	$u->error = '�� ������������ &quot;'.$itm['name'].'&quot;. ������� ������ ����� ���������� �� '.$st['expUpg'].' ��.!';
	/*
	if( $u->info['exp'] < 12500 ) {
		//������ ��� � ��������
		$test_itm = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 1204 AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if( !isset($test_itm['id']) ) {
			$u->addItem(1204,$u->info['id']);
		}
	}
	*/
	$u->info['exp'] += $st['expUpg'];
	mysql_query('UPDATE `stats` SET `exp` = "'.$u->info['exp'].'" WHERE `id` = '.$u->info['id'].' LIMIT 1');
	mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
}
?>