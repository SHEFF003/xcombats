<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'propuskpsh' ) {
	$test = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "propuskpsh" AND `time` > "'.(time()-300).'" LIMIT 1'));
	if( $u->info['align'] != 2 ) {
		if( isset($test['id']) ) {
			$u->error = '�������� �� ������, ��� '.$u->timeOut($test['time']-time()+300);
		}else{
			$u->addAction(time(),'propuskpsh','');
			$u->error = '��� ������ �������, �������� � ������ �����.';
			if($itm['id'] == 4802) {
				mysql_query('UPDATE `actions` SET `time` = "'.(time()-43200).'" WHERE `uid` = '.$u->info['id'].' AND `time` > "'.(time()-43200).'" AND `vars` LIKE "psh%" AND `vars` != "psh102" AND `vars` NOT LIKE "psh\_%"');
			}else{
				mysql_query('UPDATE `actions` SET `time` = "'.(time()-21600).'" WHERE `uid` = '.$u->info['id'].' AND `time` > "'.(time()-43200).'" AND `vars` LIKE "psh%" AND `vars` != "psh102" AND `vars` NOT LIKE "psh\_%"');
			}
			// ��������� ������ ��:
			// 1) ��� ������ ������ 12 �����. (����� ����� ������ ������ �� ������ ����� ������ 4 ������, � ����� �����, ������� ������� ������������).
			// 2) �� ��������� �� ������ ��������. 
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		}
	}else{
		$u->error = '�������� �� ����� ������������ ���������!';
	}
}
?>