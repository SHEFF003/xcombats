<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'esfer' ) {
	
	
	
	$pvr = array();
	
	//�������� ��� �����
	if( $u->stats['hpNow'] < 1 ) {
		$u->error = '<font color=red><b>�� �������� � �� ������ ��������������� �������...</b></font>';
	}elseif( isset($btl->info['id']) ) {
		
		if( $btl->info['noinc'] > 0 ) {
			$u->error = '<font color=red><b>��� ��� ���������� �����</b></font>';	
		}else{			
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
				'',
				'{tm1} {u1} ���������� ��� �� �������� ����������� ����... ',
				($btl->hodID)
			);			
			$u->error = '<font color=red><b>��������� ����� �������� ���� ��� �� ���������� ����...  </b></font>';		
			mysql_query('UPDATE `battle` SET `noinc` = 1 WHERE `id` = '.$btl->info['id'].' LIMIT 1');
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