<?
if( isset($s[1]) && $s[1] == '12/sunduk_04_4stage' ) {
/*
		������: ���������� ������, ����� ����� ���� �������
		# �������
		# `id` IN ('4243', '4244', '4245', '4246', '4247', '4248', '4249', '4250', '4251', '4252', '4253', '4254', '4255', '4256', '4257', '4258', '4259', '4260', '4261', '4262', '4263', '4264', '4265', '4266', '4267')

		# ����� 15% 
		#'4514', '4515')
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" LIMIT 1'));
	$vad['test2'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `vars` = "obj_act'.$obj['id'].'" AND `dn` = "'.$u->info['dnow'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	if( $vad['test2'][0] > 0 ) {
		$r = '�� ��� �������� &quot;'.$obj['name'].'&quot;...';
		$vad['go'] = false;
	}elseif( $vad['test1'][0] > 0 ) {
		$r = '���-�� ������� &quot;'.$obj['name'].'&quot; ������ ���...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		if(rand(0,100) > 85){ # �����
			$vad['items'] = array(4514,4515);
		} else{ # �������
			$vad['repcent'] = rand(0,100);
			if( $vad['repcent'] <= 60 ) { # ������� [1,2] 60%
				$vad['items'] = array(4243, 4244, 4248, 4249, 4253, 4254, 4258, 4259, 4263, 4264);
			} elseif( $vad['repcent'] > 60 AND $vad['repcent'] <= 83 ) {# ������� [3] 23% 
				$vad['items'] = array(4245, 4250, 4255, 4260, 4265);
			} elseif( $vad['repcent'] > 83 AND $vad['repcent'] <= 95 ) {# ������� [4] 12%
				$vad['items'] = array(4246, 4251, 4256, 4261, 4266);
			}elseif( $vad['repcent'] > 95 ) { # ������� [5] 5%
				$vad['items'] = array(4247, 4252, 4257, 4262, 4267);
			}
			if($vad['repcent'] < 87){ # �����
				$vad['items'] = array_merge(array(4514,4515), $vad['items']);
			} 
		}
		
		$vad['items'] = $vad['items'][rand(0,count($vad['items'])-1)];
		if( $vad['items'] != 0 ) {
			//����������� �������
			mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
				"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'",""
			)');
			if( !isset($vad['dn_delete'][$vad['items']]) ) {
				$vad['dn_delete'][$vad['items']] = false;
			}
			if( $this->pickitem($obj,$vad['items'],$u->info['id'],'',$vad['dn_delete'][$vad['items']]) ) {
				$r = '�� ���������� ��������...';
			}else{
				$r = '���-�� ����� �� ���, �������� ������������...';
			}
		}else{
			$r = '�� �� ����� ������ ���������...';
		}
	}
	
	unset($vad);
}
?>