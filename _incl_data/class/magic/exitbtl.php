<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'exitbtl' ) {
	
	
	
	$pvr = array();
	
	//�������� ��� �����
	if( $u->stats['hpNow'] < 1 ) {
		$u->error = '<font color=red><b>�� �������� � �� ������ ��������������� �������...</b></font>';
	}elseif( isset($btl->info['id']) ) {
		
		if( $btl->info['dn_id'] > 0 || $btl->info['izlom'] > 0 ) {
			$u->error = '<font color=red><b>����� �� ��������� � ������� � �������� ��������...</b></font>';	
		}elseif( $btl->info['noinc'] > 0 ) {
			$u->error = '<font color=red><b>��� ���������� � �� �� ������ ��� ��������</b></font>';	
		}else{			
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
				'',
				'{tm1} {u1} ������ � ���� ���... ',
				($btl->hodID)
			);			
			$u->error = '<font color=red><b>�� ������� � ���� ��� � �������� ��� �������...</b></font>';
			//		
			mysql_query('UPDATE `stats` SET `battle_yron` = 0, `battle_exp` = 0, `hpNow` = 0, `mpNow` = 0 , `tactic1` = 0 , `tactic2` = 0 , `tactic3` = 0 , `tactic4` = 0 , `tactic5` = 0 , `tactic6` = 0 , `tactic7` = -1 , `last_pr` = 0 , `last_hp` = -1 WHERE `id` = '.$u->info['id'].' LIMIT 1');
			mysql_query('UPDATE `users` SET `battle` = 0, `lose` = `lose` + 1 WHERE `id` = '.$u->info['id'].' LIMIT 1');
			//
			mysql_query('DELETE FROM `eff_users` WHERE `v1` = "priem" AND `uid` = "'.$u->info['id'].'" AND `delete` = 0');
			//
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
		}
		
	}else{
		$u->error = '<font color=red><b>������ �������� ������������ ������ � ���</b></font>';
	}
	
	//�������� �������
	//$this->mintr($pl);
	
	unset($pvr);
}
?>