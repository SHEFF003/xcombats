<?
if( isset($s[1]) && $s[1] == '101/laba1' ) {
	/*
		������: �����������
		* ����� ������� ��������� �������, �� �� ����� 3 �� �������� �� ����� � �� ����� 10 �� �������
		* 897 - ������ ��������� ����
		* 903 - ������������ ������
		* 888 - ����� ���
		* 892 - �������� �������
		* 950 - ���� ������ �����
		* 904 - �������� ������
		* 878 - �������� �����
		* 880 - �������� �������
		* 879 - �������
		* 899 - ������ �������� ������
		* 882 - ��������� ������
		* 908 - ������ ���������� ������
		* 909 - �������� ���������� �����
		* 902 - ���� �������� ������
		* 881 - �������� �����
		* 893 - �������� ������� �����
		* 898 - ����������
		* 890 - ������� �������
		* 907 - �������� ������������
		* 905 - ���������
		-- ���
		4243 - 897 �3
		4244 - 903 �2
		4245 - 888 �2
		4246 - 892 �1
		4247 - 879 �1 , 892 �1
		-- ������
		4248 - 950 �3
		4249 - 904 �2
		4250 - 878 �2
		4251 - 880 �1
		4252 - 880 �1 , 892 �1
		-- �����
		4253 - 899 �3
		4254 - 882 �2
		4255 - 908 �2
		4256 - 909 �1
		4257 - 909 �1 , 892 �1
		-- ������
		4258 - 899 �3
		4259 - 902 �2
		4260 - 881 �2
		4261 - 893 �1
		4262 - 893 �1 , 892 �1
		-- ���������
		4263 - 898 �3
		4264 - 890 �2
		4265 - 907 �2
		4266 - 905 �1
		4267 - 905 �1 , 892 �1
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	
	$vad['recept'] = array(
		//�
		array( 897, 3 ),
		array( 903, 2 ),
		array( 888, 2 ),
		array( 892, 1 ),
		array( 892, 1, 892, 1 ),
		//�
		array( 950, 3 ),
		array( 904, 2 ),
		array( 878, 2 ),
		array( 880, 1 ),
		array( 880, 1, 892, 1 ),
		//�
		array( 899, 3 ),
		array( 882, 2 ),
		array( 908, 2 ),
		array( 909, 1 ),
		array( 909, 1, 892, 1 ),
		//������
		array( 899, 3 ),
		array( 902, 2 ),
		array( 881, 2 ),
		array( 893, 1 ),
		array( 893, 1, 892, 1 ),
		//���������
		array( 898, 3 ),
		array( 890, 2 ),
		array( 907, 2 ),
		array( 905, 1 ),
		array( 905, 1, 892, 1 )
	);
	
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_lab" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	$vad['test2'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'_lab" LIMIT 1'));
	
	$vad['i'] = 0;
	while( $vad['i'] < count($vad['recept']) ) {
		//4243 + $vad['i']
		$vad['tr_itm'] = $vad['recept'][$vad['i']][0]; 
		if( $vad['tr_itm'] > 0 ) {
			$vad['tr_itm'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.$vad['recept'][$vad['i']][0].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inTransfer` = "0" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
			if( $vad['tr_itm'][0] >= $vad['recept'][$vad['i']][1] ) {
				$vad['tr_itm'] = true;
			}else{
				$vad['tr_itm'] = false;
			}
		}
		if( $vad['recept'][$vad['i']][2] > 0 && $vad['tr_itm'] == true ) {
			$vad['tr_itm'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.$vad['recept'][$vad['i']][2].'" AND (`delete` = "0" OR `delete` = "1000") AND `inShop` = "0" AND `inTransfer` = "0" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
			if( $vad['tr_itm'][2] >= $vad['recept'][$vad['i']][3] ) {
				//��� ��
			}else{
				$vad['tr_itm'] = false;
			}
		}
		if( $vad['tr_itm'] == true ) {
			$vad['itm'][] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.(4243 + $vad['i']).'" LIMIT 1'));
			$vad['tr'][(4243 + $vad['i'])] = array( $vad['recept'][$vad['i']][0] , $vad['recept'][$vad['i']][1] , $vad['recept'][$vad['i']][2] , $vad['recept'][$vad['i']][3] );
		}
		$vad['i']++;
	}
	
	$vad['itm'] = $vad['itm'][rand(0,count($vad['itm'])-1)];
	
	if( $vad['test2'][0] >= 10 ) {
		$r = '�� ������� ��������������� ������������, �� ����� 10 ��� �� ������� �� ���� �����';
		$vad['go'] = false;
	}elseif( $vad['test1'][0] >= 3 ) {
		$r = '�� ������� ��������������� ������������, �� ����� 3 ��� �� ��������� �� ���� �����';
		$vad['go'] = false;
	}elseif(!isset($vad['itm']['id'])) {
		$r = '������������ ������������...';
		$vad['go'] = false;
	}
	
	
	
	if( $vad['go'] == true ) {
		//������ �������
		if( $vad['tr'][$vad['itm']['id']][1] > 0 ) {
			$u->deleteItemID($vad['tr'][$vad['itm']['id']][0],$u->info['id'],$vad['tr'][$vad['itm']['id']][1]);
		}
		if( $vad['tr'][$vad['itm']['id']][3] > 0 ) {
			$u->deleteItemID($vad['tr'][$vad['itm']['id']][2],$u->info['id'],$vad['tr'][$vad['itm']['id']][3]);
		}
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`time`,`vars`,`x`,`y`) VALUES (
			"'.$u->info['dnow'].'","'.$u->info['id'].'","'.time().'","obj_act'.$obj['id'].'_lab","'.$obj['x'].'","'.$obj['y'].'"
		)');
		$u->addItem($vad['itm']['id'],$u->info['id'],'|frompisher=101');
		$r = '�� ������� ������� &quot;'.$vad['itm']['name'].'&quot;! ��������� ������� ...';
		if($u->info['sex'] == 0) {
			$vad['text'] = '<b>'.$u->info['login'].'</b> ������ ������� &quot;'.$vad['itm']['name'].'&quot; ��� ������ &quot;'.$obj['name'].'&quot;.';
		}else{
			$vad['text'] = '<b>'.$u->info['login'].'</b> ������� ������� &quot;'.$vad['itm']['name'].'&quot; ��� ������ &quot;'.$obj['name'].'&quot;.';
		}
		$this->sys_chat($vad['text']);
	}	
}
?>