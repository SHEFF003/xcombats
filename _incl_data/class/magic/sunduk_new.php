<?
if(!defined('GAME'))
{
	die();
}

	$add_zb = 0;
	$add_nas = 0;
	
	$refer = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned`,`admin`,`level` FROM `users` WHERE `id` = "'.mysql_real_escape_string($this->info['host_reg']).'" LIMIT 1'));
	
	
	if($tr['var_id'] == 1) {
		// ����� [0]
		$add_zb = 5;
		
			$add_nas = 1;
			
			//�������
			$i3 = $this->addItem(3200,$this->info['id'],'|nosale=1|noremont=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//�������
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������
			$i3 = $this->addItem(3201,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}		
			
			if($io == '') {
				$io = '��������� ������������: 1 �������, ������� ������������, ������� ��������������';	
			}
		
	}elseif($tr['var_id'] == 2) {
		// ����� [1]
		$add_zb = 10;
		
			$add_nas = 1;
		
			//������ 3209
			$i3 = $this->addItem(3209,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� 3210
			$i3 = $this->addItem(3210,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//�������
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������
			$i3 = $this->addItem(3202,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = '��������� ������������: 2 �������, ������ ������������, ����� ������������, ������� ��������������';	
			}
		
	}elseif($tr['var_id'] == 3) {
		// ����� [2]
		$add_zb = 20;
		
			$add_nas = 1;
		
			//�������� 3211
			$i3 = $this->addItem(3211,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//������ 3212
			$i3 = $this->addItem(3212,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//�������
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������
			$i3 = $this->addItem(3203,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = '��������� ������������: 3 �������, ������ ������������, �������� ������������, ������� ��������������';	
			}
		
	}elseif($tr['var_id'] == 4) {
		// ����� [3]
		$add_zb = 30;
		
			$add_nas = 1;		
		
			//������ 3213
			$i3 = $this->addItem(3213,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//���� 3214
			$i3 = $this->addItem(3214,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//���� 3215
			$i3 = $this->addItem(3215,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//�������
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������
			$i3 = $this->addItem(3204,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = '��������� ������������: 4 �������, ������ ������������, ����� ������������, ���� ������������, ������� ��������������';	
			}
		
	}elseif($tr['var_id'] == 5) {
		// ����� [4]
		$add_zb = 40;
		
			$add_nas = 1;
		
			//������ 3216
			$i3 = $this->addItem(3216,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//������ 3217
			$i3 = $this->addItem(3217,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//������ 3218
			$i3 = $this->addItem(3218,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			$i3 = $this->addItem(3218,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			$i3 = $this->addItem(3218,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//�������
			$i3 = $this->addItem(2418,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������
			$i3 = $this->addItem(3205,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = '��������� ������������: 5 �������, ������ ������������, ������ ������������, ������ ������������ (�3), ������� ��������������';	
			}
		
	}elseif($tr['var_id'] == 6) {
		// ����� [5]
		$add_zb = 50;
		
			$add_nas = 1;
		
			//����� 4002
			$i3 = $this->addItem(4002,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//������� ������������ 4004
			$i3 = $this->addItem(4004,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������������ 4003
			$i3 = $this->addItem(4003,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//������
			mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `img` = "ref_obr1.gif" LIMIT 2');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("0","ref_obr1.gif","5","'.$this->info['id'].'","'.time().'")');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("1","ref_obr1.gif","5","'.$this->info['id'].'","'.time().'")');
			
			//����� ������
			$i3 = $this->addItem(3206,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = '��������� ������������: 6 �������, ����� ������������, ����� ������������, ������� ������������, ����� ������������';	
			}
		
	}elseif($tr['var_id'] == 7) {
		// ����� [6]
		$add_zb = 60;
			
			$add_nas = 2;
			
			//������� ������������ 4004
			$i3 = $this->addItem(4004,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������������ 4003
			$i3 = $this->addItem(4003,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//���������
			$i3 = $this->addItem(865,$this->info['id'],'|nosale=1',NULL,50);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
						
			//����� ������
			$i3 = $this->addItem(3207,$this->info['id']);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			if($io == '') {
				$io = '��������� ������������: 7 �������, ������� ������������, ����� ������������, ���������';	
			}
		
	}elseif($tr['var_id'] == 8) {
		// ����� [7]
		$add_zb = 70;
			
			$add_nas = 3;
			
			//������� ������������ 4004
			$i3 = $this->addItem(4004,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//����� ������������ 4003
			$i3 = $this->addItem(4003,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//���������
			$i3 = $this->addItem(865,$this->info['id'],'|nosale=1',NULL,50);
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//��������������
			$i3 = $this->addItem(2712,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			$i3 = $this->addItem(2712,$this->info['id'],'|nosale=1');
			if($i3 > 0) {
				mysql_query('UPDATE `items_users` SET `gift` = "���������" WHERE `id` = "'.$i3.'" LIMIT 1');
			}
			
			//������
			mysql_query('DELETE FROM `obraz` WHERE `uid` = "'.$this->info['id'].'" AND `img` = "ref_obr2.gif" LIMIT 2');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("0","ref_obr2.gif","7","'.$this->info['id'].'","'.time().'")');
			mysql_query('INSERT INTO `obraz` (`sex`,`img`,`level`,`uid`,`usr_add`) VALUES ("1","ref_obr2.gif","7","'.$this->info['id'].'","'.time().'")');
			
			if($io == '') {
				$io = '������� ������������, ����� ������������, ���������, ����� ������������, �������������� ������� 900HP (�2)';	
			}
		
	}
	
	if($add_zb > 0 && $this->info['level'] < 8) {
		$this->info['money4'] += $add_zb;
		mysql_query('UPDATE `users` SET `money4` = "'.$this->info['money4'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$this->info['login']."',' � &quot;��������� ������������&quot; �� ���������� ����: <small>".$this->zuby($add_zb,1)."</small>. ','-1','6','0')");
	}
	
	if($add_nas > 0 && $this->info['level'] < 8 && isset($refer['id'])) {
		$ino = 0;
		while($ino < $add_nas) {
			$this->addItem(4005,$refer['id']);
			$ino++;
		}
		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$refer['login']."',' ��� ����������� &quot;".$this->info['login']."&quot; �������� ������ <b>������ ����������</b> (<small>x".(0+$add_nas)."</small>). ','-1','6','0')");
	}
	
	unset($i3,$add_zb,$refer,$add_nas);
?>