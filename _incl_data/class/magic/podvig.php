<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'podvig' ) {
	$test = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "podvig" AND `time` > "'.(time()).'" LIMIT 1'));
	if( $u->info['align'] != 2 ) {
		if( isset($test['id']) ) {
			$u->error = '�������� �� ������, ��� '.$u->timeOut($test['time']-time());
		}else{
			//
			$dngcity = array(
				6035 => array('angelscity','������'),
				6036 => array('capitalcity','������ ������ ���������'),
				6037 => array('demonscity','���������'),
				6038 => array('mooncity','���� �������'),
				6039 => array('suncity','��������'),
				6040 => array('sandcity','������ ����')
			);
			//
			$dngcity = $dngcity[$itm['item_id']];
			//
			$hgo1 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > "'.(time()-86400).'" AND `vars` = "psh_qt_'.$dngcity[0].'" LIMIT 1',1);
			if(!isset($hgo1['id'])) {
				$u->error = '��� �������� ��� ���������� '.$dngcity[1].'.';
			}else{
				//
				$u->addAction(time(),'podvig','');
				//$u->error = '��� ������ �������, �������� �� ��������� ������� � ������ '.$dngcity[1].' �����.';
				$u->error = '������� ����������� ������ ����� �� ������ ('.$dngcity[1].')';
				mysql_query('UPDATE `actions` SET `time` = "'.(time()-86401).'" WHERE `id` = "'.($hgo1['id']).'" LIMIT 1');
				mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			}
		}
	}else{
		$u->error = '�������� �� ����� ������������ ���� �������!';
	}
}
?>