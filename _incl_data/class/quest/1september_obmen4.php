<?
/*
	�������� ��������� ����� (����� �� +10 ��������)
	�������, ������� � ������ - �������� ��������� �����
	//
	4745 - �������
	4746 - �����
	4747 - �������
	4748 - ������
	4749 - �������
	4750 - ����������
	4751 - ���������
*/
$test = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND (`item_id` = 4740 OR `item_id` = 4741 OR `item_id` = 4742 OR `item_id` = 4743 OR `item_id` = 4744) LIMIT 1'));

if(isset($test['id'])) {
	$txt .= '<br><b><font color=red>�� ��� �������� ���� �� ����, ������ �������� ��� ���� � ���� ����...</b></font>';
}else{
	$pvr = array();
	$pvr['ch'] = 15; //����
	$pvr['tr'] = array( array(4745,1) , array(4747,1) , array(4748,1) );
	
	$pvr['i'] = 0;
	while( $pvr['i'] < count($pvr['tr']) ) {
		if( isset($pvr['tr'][$pvr['i']]) && $pvr['tr'][$pvr['i']] > 0 ) {
			$itm = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($pvr['tr'][$pvr['i']][0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT 1'));
			if( $itm[0] < $pvr['tr'][$pvr['i']][1] ) {
				$pvr['bad_itm']++;
			}
		}
		$pvr['i']++;
	}
	
	if( isset($pvr['bad_itm']) && $pvr['bad_itm'] > 0 ) {
		$txt .= '<br><b><font color=red>� ��� ��� ���������� ��������� ��� ������...</b></font>';
	}else{
		//
		$pvr['i'] = 0;
		while( $pvr['i'] < count($pvr['tr']) ) {
			if( isset($pvr['tr'][$pvr['i']]) && $pvr['tr'][$pvr['i']] > 0 ) {
				mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id` = "'.mysql_real_escape_string($pvr['tr'][$pvr['i']][0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inOdet` = "0" LIMIT '.mysql_real_escape_string($pvr['tr'][$pvr['i']][1]));
			}
			$pvr['i']++;
		}
		//��� ��
		if( rand(0,100) < $pvr['ch'] ) {
			$txt .= '<br><b><font color=red>�� �������� &quot;����� ��� ������&quot;</b></font>';
			$pvr['itm'] = $u->addItem(4743,$u->info['id']);
			//
			mysql_query('UPDATE `items_users` SET `gift` = "������� ���������" WHERE `id` = "'.mysql_real_escape_string($pvr['itm']).'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
			//
		}else{
			$txt .= '<br><b><font color=red>��������� �����������</b></font>';
		}
	}
	
	unset($pvr);
}
?>