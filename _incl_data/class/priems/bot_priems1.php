<?
if(!defined('GAME')) { die(); }

if($e['bm_a1'] == 'bot_priems1') {
  $pr_use = 0;
  $pr_vars = array('hp_u1' => $this->users[$this->uids[$uid1]]['hpNow'], 'hp_u2' => $this->users[$this->uids[$uid2]]['hpNow']);

  if(!function_exists('rand_user_team')) {
	function rand_user_team($tm, $tp) {
	  global $btl;
	  $r = array();
	  $i = 0;
	  while($i < count($btl->users)) {
		if($btl->users[$i]['team'] == $tm && $tp == 1) {
		  $r[] = $btl->users[$i]['id'];
		} elseif($btl->users[$i]['team'] != $tm && $tp == 2) {
		  $r[] = $btl->users[$i]['id'];
		}			
		$i++;
	  }
	  if(count($r) == 0) {
		$r = 0;	
	  } else {
		$r = rand(0,count($r)-1);
	  }
	  return $r;
	}
  }
  
  
	//����� �����
	if($this->users[$this->uids[$uid1]]['bot_id'] == 416) {
		$pr_use = 1;
		#1#
		$pr_vars['priem_use'][0]['chance'] = 50;
		$pr_vars['priem_use'][0]['name'] = '������ ���';
		$pr_vars['priem_use'][0]['id'] = 291;
		$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
		#1#
	}
  
###Start (����� �����)###

if($this->users[$this->uids[$uid1]]['bot_id'] == 118 || $this->users[$this->uids[$uid1]]['bot_id'] == 119) {
###��������� ������ [8] / ��������� ������ [9]
	$pr_use = 1;
  
	$pr_vars['priem_use'][0]['chance'] = 30;
	$pr_vars['priem_use'][0]['name'] = '������� ����';
	$pr_vars['priem_use'][0]['id'] = 4;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
	//����������
	$pr_vars['priem_use'][0]['chance'] = 15;
	$pr_vars['priem_use'][0]['name'] = '���������� ����';
	$pr_vars['priem_use'][0]['id'] = 236;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
  
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 50 || $this->users[$this->uids[$uid1]]['bot_id'] == 53) {
	//���� ��� ������� ����
	//priem team x type hp hp_dmg
	  $pr_use = 1;
	  
	  //������� ����
	  $pr_vars['priem_team_f'][] = array(
		  'chance' => 25,
		  'name' => '������� ����',
		  'x' => 1,
		  'type' => 7,	  
		  'hp' => 0,
		  'hp_dmg' => 2,	  
		  'priem' => 164,
		  'team' => $this->users[$this->uids[$uid2]]['team'],
		  'on' => $uid,
		  'nomf' => 1,
		  'fiz' => 1,
		  'krituet' => false 
	  );
	  
	  //����������
	  $pr_vars['priem_use'][] = array(
		  'chance' => 25,
		  'name' => '����������',
		  'id' => 8,
		  'on' => $this->users[$this->uids[$uid1]],
		  'no_chat' => true
	  );
	  
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 54) {
	//��������������� ���
	  $pr_use = 1;
	  //�������� ����
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
	  $pr_vars['priem_use'][0]['id'] = 1;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //������ ����
	  $pr_vars['priem_use'][1]['chance'] = 10;
	  $pr_vars['priem_use'][1]['name'] = '������ ����';
	  $pr_vars['priem_use'][1]['id'] = 2;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 57) {
	//��������� �����
	  $pr_use = 1;
	  //������� ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������� ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 7;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 2;
	$pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 1;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = false;
	  //������ ����
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = '������ ����';
	  $pr_vars['priem_use'][0]['id'] = 2;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 58) {
	//��������� ��������
	  $pr_use = 1;
	  //������� ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������ ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 3;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 16;
	  $pr_vars['priem_team_f'][0]['priem'] = 73;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 0;
	  $pr_vars['priem_team_f'][0]['fiz'] = 0;
	  $pr_vars['priem_team_f'][0]['krituet'] = true;
	  
	  //������ ����
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = '������ ����';
	  $pr_vars['priem_use'][0]['id'] = 2;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //��������
	  $pr_vars['priem_regen']['hp'] = 8;
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = '��������';
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 56) {
	//��������� ���
	  $pr_use = 1;
	  //������� ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������� ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 7;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 2;	  
	  $pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 1;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = false;
	  
	  //�����������
	  $pr_vars['priem_regen']['hp'] = rand(8,12);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = '�����������';
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 55) {
	//������ ��������
	  $pr_use = 1;
	  //������� ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������� ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 7;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 2;	  
	  $pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 1;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = false;
	  //��������� �������
	  $pr_vars['priem_team_f'][1]['chance'] = 10;
	  $pr_vars['priem_team_f'][1]['name'] = '��������� �������';
	  $pr_vars['priem_team_f'][1]['x'] = 1;
	  $pr_vars['priem_team_f'][1]['type'] = 3;	  
	  $pr_vars['priem_team_f'][1]['hp'] = 0;
	  $pr_vars['priem_team_f'][1]['hp_dmg'] = 32;
	  $pr_vars['priem_team_f'][1]['priem'] = 73;
	  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][1]['on'] = $uid;
	  $pr_vars['priem_team_f'][1]['nomf'] = 0;
	  $pr_vars['priem_team_f'][1]['fiz'] = 0;
	  $pr_vars['priem_team_f'][1]['krituet'] = true;	  
	  //�����������
	  $pr_vars['priem_regen']['hp'] = rand(8,12);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = '�����������';
	  //���������
	  $pr_vars['priem_use'][0]['chance'] = 50;
	  $pr_vars['priem_use'][0]['name'] = '���������';
	  $pr_vars['priem_use'][0]['id'] = 293;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 52) {
	//������
	  $pr_use = 1;
	  //������ ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������ ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 3;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 32;
	  $pr_vars['priem_team_f'][0]['priem'] = 73;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 0;
	  $pr_vars['priem_team_f'][0]['fiz'] = 0;
	  $pr_vars['priem_team_f'][0]['krituet'] = true;	  
	  //�����������
	  $pr_vars['priem_regen']['hp'] = rand(8,12);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = '�����������';
	  //�������� ����
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
	  $pr_vars['priem_use'][0]['id'] = 1;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //������ ����
	  $pr_vars['priem_use'][1]['chance'] = 10;
	  $pr_vars['priem_use'][1]['name'] = '������ ����';
	  $pr_vars['priem_use'][1]['id'] = 2;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  //���������
	  if( rand(0,100) < 10 ) {
		  //��������� ����
		  $pr_vars['priem_team_f'][1]['chance'] = 100;
		  $pr_vars['priem_team_f'][1]['name'] = '��������� ����';
		  $pr_vars['priem_team_f'][1]['x'] = 1;
		  $pr_vars['priem_team_f'][1]['type'] = 3;	  
		  $pr_vars['priem_team_f'][1]['hp'] = 0;
		  $pr_vars['priem_team_f'][1]['hp_dmg'] = 2;	  
		  $pr_vars['priem_team_f'][1]['priem'] = 164;
		  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][1]['on'] = $uid;
		  $pr_vars['priem_team_f'][1]['nomf'] = 1;
		  $pr_vars['priem_team_f'][1]['fiz'] = 1;
		  $pr_vars['priem_team_f'][1]['krituet'] = false;
		  //
		  $pr_vars['priem_use'][2]['chance'] = 100;
		  $pr_vars['priem_use'][2]['name'] = '��������� ����';
		  $pr_vars['priem_use'][2]['id'] = 294;
		  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid2]];
		  $pr_vars['priem_use'][2]['no_chat'] = true;
	  }
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 64) {
	//��������������� ����
	  $pr_use = 1;
	  //������� ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������� ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 7;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = rand(10,13);
	  $pr_vars['priem_team_f'][0]['priem'] = 73;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 0;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = true;	  
	  //���������
	  if( rand(0,100) < 15 ) {
		  //���������
		  $pr_vars['priem_use'][0]['chance'] = 100;
		  $pr_vars['priem_use'][0]['name'] = '���������';
		  $pr_vars['priem_use'][0]['id'] = 295;
		  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
		  //$pr_vars['priem_use'][2]['no_chat'] = true;
	  }
	  //������� ����
	  if( rand(0,100) < 15 ) {
		  $pr_vars['priem_team_f'][1]['chance'] = 100;
		  $pr_vars['priem_team_f'][1]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][1]['x'] = 1;
		  $pr_vars['priem_team_f'][1]['type'] = 7;	  
		  $pr_vars['priem_team_f'][1]['hp'] = 0;
		  $pr_vars['priem_team_f'][1]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][1]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][1]['hp_dmg']['y'],$pr_vars['priem_team_f'][1]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][1]['priem'] = 73;
		  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][1]['on'] = $uid;
		  $pr_vars['priem_team_f'][1]['nomf'] = 0;
		  $pr_vars['priem_team_f'][1]['fiz'] = 1;
		  $pr_vars['priem_team_f'][1]['krituet'] = true;
		  //
		  $pr_vars['priem_team_f'][2]['chance'] = 100;
		  $pr_vars['priem_team_f'][2]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][2]['x'] = 1;
		  $pr_vars['priem_team_f'][2]['type'] = 7;	  
		  $pr_vars['priem_team_f'][2]['hp'] = 0;
		  $pr_vars['priem_team_f'][2]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][2]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][2]['hp_dmg']['y'],$pr_vars['priem_team_f'][2]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][2]['priem'] = 73;
		  $pr_vars['priem_team_f'][2]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][2]['on'] = $uid;
		  $pr_vars['priem_team_f'][2]['nomf'] = 0;
		  $pr_vars['priem_team_f'][2]['fiz'] = 1;
		  $pr_vars['priem_team_f'][2]['krituet'] = true;
	  }
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 62) {
	//���������� ���������
	  $pr_use = 1; 
	  //���������
	  if( rand(0,100) < 15 ) {
		  //���������
		  $pr_vars['priem_use'][0]['chance'] = 100;
		  $pr_vars['priem_use'][0]['name'] = '������ �����';
		  $pr_vars['priem_use'][0]['id'] = 296;
		  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
	  }
	  //
  	  $pr_vars['priem_use'][] = array(
		 'chance' => 10,
		 'name' => '�������� ����',
		 'id' => 7,
		 'on' => $this->users[$this->uids[$uid1]],
		 'no_chat' => true
	  );
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 65) {
	//�������� �����
	  $pr_use = 1;
	  //���������
	  $pr_vars['priem_use'][0]['chance'] = 40;
	  $pr_vars['priem_use'][0]['name'] = '���������';
	  $pr_vars['priem_use'][0]['id'] = 293;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
	  //�������
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '�������';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 7;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = rand(1,13);	  
	  $pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 1;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = false;
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 61) {
	//������� ������
	  $pr_use = 1;
	  //�������
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '�������';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 7;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = rand(1,14);	  
	  $pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 1;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = false;
	  //
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = '������ ����';
  	  $pr_vars['priem_use'][0]['id'] = 236;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
	  //����������
	  $pr_vars['priem_use'][1]['chance'] = 15;
	  $pr_vars['priem_use'][1]['name'] = '����������';
	  $pr_vars['priem_use'][1]['id'] = 8;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 63) {
	//�������� ���������
	  $pr_use = 1;
	  //�������� ����
  	 $pr_vars['priem_use'][0]['chance'] = 10;
  	 $pr_vars['priem_use'][0]['name'] = '�������� ����';
  	 $pr_vars['priem_use'][0]['id'] = 7;
  	 $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	 $pr_vars['priem_use'][0]['no_chat'] = true;
	  //���������
	  $pr_vars['priem_use'][1]['chance'] = 50;
	  $pr_vars['priem_use'][1]['name'] = '���������';
	  $pr_vars['priem_use'][1]['id'] = 293;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid2]];
	  //���������
	  if( rand(0,100) < 10 ) {
		  //���������
		  $pr_vars['priem_use'][2]['chance'] = 100;
		  $pr_vars['priem_use'][2]['name'] = '������ �����';
		  $pr_vars['priem_use'][2]['id'] = 296;
		  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid2]];
	  }
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 68) {
	//�������� 
	  $pr_use = 1;
	  //������� ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������� ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 3;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 37;
	  $pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 0;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = true;
	  //������� ����
	  $pr_vars['priem_team_f'][1]['chance'] = 10;
	  $pr_vars['priem_team_f'][1]['name'] = '����� �����';
	  $pr_vars['priem_team_f'][1]['x'] = 1;
	  $pr_vars['priem_team_f'][1]['type'] = 3;	  
	  $pr_vars['priem_team_f'][1]['hp'] = 0;
	  $pr_vars['priem_team_f'][1]['hp_dmg'] = 22;
	  $pr_vars['priem_team_f'][1]['priem'] = 164;
	  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][1]['on'] = $uid;
	  $pr_vars['priem_team_f'][1]['nomf'] = 0;
	  $pr_vars['priem_team_f'][1]['fiz'] = 1;
	  $pr_vars['priem_team_f'][1]['krituet'] = true;
	  //�������� ����
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  /* if( rand(0,100) < 25 ) {
		  //������� ����
		  if( !isset($this->stats[$this->uids[$uid2]]['noeffectbattle1'])) {
			  $pr_vars['priem_team_f'][1]['chance'] = 100;
			  $pr_vars['priem_team_f'][1]['name'] = '����������';
			  $pr_vars['priem_team_f'][1]['x'] = 1;
			  $pr_vars['priem_team_f'][1]['type'] = 3;	  
			  $pr_vars['priem_team_f'][1]['hp'] = 0;
			  $pr_vars['priem_team_f'][1]['hp_dmg'] = rand(1,5);
			  $pr_vars['priem_team_f'][1]['priem'] = 164;
			  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
			  $pr_vars['priem_team_f'][1]['on'] = $uid;
			  $pr_vars['priem_team_f'][1]['nomf'] = 0;
			  $pr_vars['priem_team_f'][1]['fiz'] = 1;
			  $pr_vars['priem_team_f'][1]['krituet'] = true;
			  mysql_query('INSERT INTO `battle_actions` (`btl`,`uid`,`time`,`vars`,`vals`) VALUES (
				"'.$this->info['id'].'","'.$uid2.'","'.time().'","noeffectbattle1","'.$uid1.'"
			  )');
		  }
	  }*/
	 
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 66) {
	//������� ������ 
	  $pr_use = 1;
	  //����� �����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '����� �����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 3;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 24;
	  $pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 0;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = true;
	  //�������� ����
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //�������� ����
  	  $pr_vars['priem_use'][1]['chance'] = 10;
  	  $pr_vars['priem_use'][1]['name'] = '�������� ������';
  	  $pr_vars['priem_use'][1]['id'] = 7;
  	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  //�����������
	  $pr_vars['priem_regen']['hp'] = rand(1,10);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = '�����������';
	  //��������
	  $pr_vars['priem_team_f'][1]['chance'] = 10;
	  $pr_vars['priem_team_f'][1]['name'] = '��������';
	  $pr_vars['priem_team_f'][1]['x'] = 1;
	  $pr_vars['priem_team_f'][1]['type'] = 3;	  
	  $pr_vars['priem_team_f'][1]['hp'] = 25;
	  $pr_vars['priem_team_f'][1]['hp_dmg'] = 25;
	  $pr_vars['priem_team_f'][1]['priem'] = 164;
	  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][1]['on'] = $uid;
	  $pr_vars['priem_team_f'][1]['nomf'] = 0;
	  $pr_vars['priem_team_f'][1]['fiz'] = 1;
	  $pr_vars['priem_team_f'][1]['krituet'] = true;
	  $pr_vars['priem_team_f'][1]['hpregen'] = true;
	 
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 59) {
	//������� ������ 
	  $pr_use = 1;
	  //�������� ����
  	  $pr_vars['priem_use'][0]['chance'] = 15;
  	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //�������� ������
  	  $pr_vars['priem_use'][1]['chance'] = 15;
  	  $pr_vars['priem_use'][1]['name'] = '�������� ������';
  	  $pr_vars['priem_use'][1]['id'] = 7;
  	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  //���������
  	  $pr_vars['priem_use'][2]['chance'] = 15;
  	  $pr_vars['priem_use'][2]['name'] = '���������';
  	  $pr_vars['priem_use'][2]['id'] = 297;
  	  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][2]['no_chat'] = true;
	  //������ ��������
  	  $pr_vars['priem_use'][3]['chance'] = 5;
  	  $pr_vars['priem_use'][3]['name'] = '������ ��������';
  	  $pr_vars['priem_use'][3]['id'] = 298;
  	  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid2]];
	  //�����������
	  $pr_vars['priem_regen']['hp'] = rand(1,10);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = '�����������';
	  //������� ����
	  if( rand(0,100) < 15 ) {
		  //
		  $pr_vars['priem_team_f'][0]['chance'] = 100;
		  $pr_vars['priem_team_f'][0]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][0]['x'] = 1;
		  $pr_vars['priem_team_f'][0]['type'] = 7;	  
		  $pr_vars['priem_team_f'][0]['hp'] = 0;
		  $pr_vars['priem_team_f'][0]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][0]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][0]['hp_dmg']['y'],$pr_vars['priem_team_f'][0]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][0]['priem'] = 73;
		  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][0]['on'] = $uid;
		  $pr_vars['priem_team_f'][0]['nomf'] = 0;
		  $pr_vars['priem_team_f'][0]['fiz'] = 1;
		  $pr_vars['priem_team_f'][0]['krituet'] = true;
		  //
		  $pr_vars['priem_team_f'][1]['chance'] = 100;
		  $pr_vars['priem_team_f'][1]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][1]['x'] = 1;
		  $pr_vars['priem_team_f'][1]['type'] = 7;	  
		  $pr_vars['priem_team_f'][1]['hp'] = 0;
		  $pr_vars['priem_team_f'][1]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][1]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][1]['hp_dmg']['y'],$pr_vars['priem_team_f'][1]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][1]['priem'] = 73;
		  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][1]['on'] = $uid;
		  $pr_vars['priem_team_f'][1]['nomf'] = 0;
		  $pr_vars['priem_team_f'][1]['fiz'] = 1;
		  $pr_vars['priem_team_f'][1]['krituet'] = true;
	  }
	 
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 67) {
	//������ ����������� 
	  $pr_use = 1;
	  //������� ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������� ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 3;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = rand(35,40);
	  $pr_vars['priem_team_f'][0]['priem'] = 164;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 0;
	  $pr_vars['priem_team_f'][0]['fiz'] = 1;
	  $pr_vars['priem_team_f'][0]['krituet'] = true;
	  //�������� ����
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //$pr_vars['priem_use'][1]['no_chat'] = true;
	  if( rand(0,100) < 10 ) {
		  //������� ����
		  if( !isset($this->stats[$this->uids[$uid2]]['noeffectbattle1'])) {
			  $pr_vars['priem_team_f'][1]['chance'] = 100;
			  $pr_vars['priem_team_f'][1]['name'] = '����������';
			  $pr_vars['priem_team_f'][1]['x'] = 1;
			  $pr_vars['priem_team_f'][1]['type'] = 3;	  
			  $pr_vars['priem_team_f'][1]['hp'] = 0;
			  $pr_vars['priem_team_f'][1]['hp_dmg'] = rand(1,5);
			  $pr_vars['priem_team_f'][1]['priem'] = 164;
			  $pr_vars['priem_team_f'][1]['team'] = $this->users[$this->uids[$uid2]]['team'];
			  $pr_vars['priem_team_f'][1]['on'] = $uid;
			  $pr_vars['priem_team_f'][1]['nomf'] = 0;
			  $pr_vars['priem_team_f'][1]['fiz'] = 1;
			  $pr_vars['priem_team_f'][1]['krituet'] = true;
			  mysql_query('INSERT INTO `battle_actions` (`btl`,`uid`,`time`,`vars`,`vals`) VALUES (
				"'.$this->info['id'].'","'.$uid2.'","'.time().'","noeffectbattle1","'.$uid1.'"
			  )');
		  }
	  }
	  //������� ����
	  if( rand(0,100) < 10 ) {
		  //
		  $cnt1 = count($pr_vars['priem_team_f']);
		  $pr_vars['priem_team_f'][$cnt1]['chance'] = 100;
		  $pr_vars['priem_team_f'][$cnt1]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][$cnt1]['x'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['type'] = 7;	  
		  $pr_vars['priem_team_f'][$cnt1]['hp'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][$cnt1]['hp_dmg']['y'],$pr_vars['priem_team_f'][$cnt1]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][$cnt1]['priem'] = 73;
		  $pr_vars['priem_team_f'][$cnt1]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][$cnt1]['on'] = $uid;
		  $pr_vars['priem_team_f'][$cnt1]['nomf'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['fiz'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['krituet'] = true;
		  //
		  $cnt1++;
		  $pr_vars['priem_team_f'][$cnt1]['chance'] = 100;
		  $pr_vars['priem_team_f'][$cnt1]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][$cnt1]['x'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['type'] = 7;	  
		  $pr_vars['priem_team_f'][$cnt1]['hp'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][$cnt1]['hp_dmg']['y'],$pr_vars['priem_team_f'][$cnt1]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][$cnt1]['priem'] = 73;
		  $pr_vars['priem_team_f'][$cnt1]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][$cnt1]['on'] = $uid;
		  $pr_vars['priem_team_f'][$cnt1]['nomf'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['fiz'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['krituet'] = true;
	  }
	 
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 60) {
	  //�������-����� 
	  $pr_use = 1;
	  //�������� ����
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //����������������
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = '����������������';
  	  $pr_vars['priem_use'][0]['id'] = 141;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //
	  /*if( rand(0,100) < 25 ) {
		  //������� ����
		  if( !isset($this->stats[$this->uids[$uid2]]['noeffectbattle1'])) {
			  $pr_vars['priem_team_f'][0]['chance'] = 100;
			  $pr_vars['priem_team_f'][0]['name'] = '����������';
			  $pr_vars['priem_team_f'][0]['x'] = 1;
			  $pr_vars['priem_team_f'][0]['type'] = 3;	  
			  $pr_vars['priem_team_f'][0]['hp'] = 0;
			  $pr_vars['priem_team_f'][0]['hp_dmg'] = rand(1,5);
			  $pr_vars['priem_team_f'][0]['priem'] = 164;
			  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
			  $pr_vars['priem_team_f'][0]['on'] = $uid;
			  $pr_vars['priem_team_f'][0]['nomf'] = 0;
			  $pr_vars['priem_team_f'][0]['fiz'] = 1;
			  $pr_vars['priem_team_f'][0]['krituet'] = true;
			  mysql_query('INSERT INTO `battle_actions` (`btl`,`uid`,`time`,`vars`,`vals`) VALUES (
				"'.$this->info['id'].'","'.$uid2.'","'.time().'","noeffectbattle1","'.$uid1.'"
			  )');
		  }
	  }*/
	  //������� ����
	  if( rand(0,100) < 10 ) {
		  //
		  $cnt1 = count($pr_vars['priem_team_f']);
		  $pr_vars['priem_team_f'][$cnt1]['chance'] = 100;
		  $pr_vars['priem_team_f'][$cnt1]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][$cnt1]['x'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['type'] = 7;	  
		  $pr_vars['priem_team_f'][$cnt1]['hp'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][$cnt1]['hp_dmg']['y'],$pr_vars['priem_team_f'][$cnt1]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][$cnt1]['priem'] = 73;
		  $pr_vars['priem_team_f'][$cnt1]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][$cnt1]['on'] = $uid;
		  $pr_vars['priem_team_f'][$cnt1]['nomf'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['fiz'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['krituet'] = true;
		  //
		  $cnt1++;
		  $pr_vars['priem_team_f'][$cnt1]['chance'] = 100;
		  $pr_vars['priem_team_f'][$cnt1]['name'] = '������� ����';
		  $pr_vars['priem_team_f'][$cnt1]['x'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['type'] = 7;	  
		  $pr_vars['priem_team_f'][$cnt1]['hp'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = $this->yronGetrazmen($uid1,$uid2,3,rand(1,5));
		  $pr_vars['priem_team_f'][$cnt1]['hp_dmg'] = floor(1+rand($pr_vars['priem_team_f'][$cnt1]['hp_dmg']['y'],$pr_vars['priem_team_f'][$cnt1]['hp_dmg']['m_y']));
		  $pr_vars['priem_team_f'][$cnt1]['priem'] = 73;
		  $pr_vars['priem_team_f'][$cnt1]['team'] = $this->users[$this->uids[$uid2]]['team'];
		  $pr_vars['priem_team_f'][$cnt1]['on'] = $uid;
		  $pr_vars['priem_team_f'][$cnt1]['nomf'] = 0;
		  $pr_vars['priem_team_f'][$cnt1]['fiz'] = 1;
		  $pr_vars['priem_team_f'][$cnt1]['krituet'] = true;
	  }
	 
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 51) {
	//����
	  $pr_use = 1;
	  //������ ����
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = '������ ����';
	  $pr_vars['priem_team_f'][0]['x'] = 1;
	  $pr_vars['priem_team_f'][0]['type'] = 3;	  
	  $pr_vars['priem_team_f'][0]['hp'] = 0;
	  $pr_vars['priem_team_f'][0]['hp_dmg'] = 32;
	  $pr_vars['priem_team_f'][0]['priem'] = 73;
	  $pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	  $pr_vars['priem_team_f'][0]['on'] = $uid;
	  $pr_vars['priem_team_f'][0]['nomf'] = 0;
	  $pr_vars['priem_team_f'][0]['fiz'] = 0;
	  $pr_vars['priem_team_f'][0]['krituet'] = true;	  
	  //�������� ����
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = '�������� ����';
	  $pr_vars['priem_use'][0]['id'] = 1;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //������ ����
	  $pr_vars['priem_use'][1]['chance'] = 10;
	  $pr_vars['priem_use'][1]['name'] = '������ ����';
	  $pr_vars['priem_use'][1]['id'] = 2;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 123) {
###������� ���� [8]
###To Do: ����������� ����
  $pr_use = 1;

  $pr_vars['priem_regen']['hp'] = rand(50,350);
  $pr_vars['priem_regen']['chance'] = 15;
  $pr_vars['priem_regen']['name'] = '�������';

  $pr_vars['priem_use'][0]['chance'] = 25;
  $pr_vars['priem_use'][0]['name'] = '�������� ������';
  $pr_vars['priem_use'][0]['id'] = 7;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

  /*$pr_vars['priem_use'][1]['chance'] = 3;
  $pr_vars['priem_use'][1]['name'] = '���������';
  $pr_vars['priem_use'][1]['id'] = 13;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][2]['chance'] = 3;
  $pr_vars['priem_use'][2]['name'] = '������';
  $pr_vars['priem_use'][2]['id'] = 14;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];*/
}


if($this->users[$this->uids[$uid1]]['bot_id'] == 124) {
###��������� ���� [9]
  $pr_use = 1;
  
  $pr_vars['priem_use'][0]['chance'] = 25;
  $pr_vars['priem_use'][0]['name'] = '������ �������';
  $pr_vars['priem_use'][0]['id'] = 49;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][1]['chance'] = 25;
  $pr_vars['priem_use'][1]['name'] = '���� ������';
  $pr_vars['priem_use'][1]['id'] = 219;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 25;
  $pr_vars['priem_use'][2]['name'] = '�������� ������';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 25;
  $pr_vars['priem_use'][3]['name'] = '������� ����';
  $pr_vars['priem_use'][3]['id'] = 15;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][4]['chance'] = 25;
  $pr_vars['priem_use'][4]['name'] = '������ �����';
  $pr_vars['priem_use'][4]['id'] = 47;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][5]['chance'] = 25;
  $pr_vars['priem_use'][5]['name'] = '���������';
  $pr_vars['priem_use'][5]['id'] = 13;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][6]['chance'] = 25;
  $pr_vars['priem_use'][6]['name'] = '������';
  $pr_vars['priem_use'][6]['id'] = 14;
  $pr_vars['priem_use'][6]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_regen']['hp'] = 45;
  $pr_vars['priem_regen']['chance'] = 15;
  $pr_vars['priem_regen']['name'] = '���� � ������';

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 125) {
###����� ������� [9]
	$pr_use = 1;

	$pr_vars['priem_use'][0]['chance'] = 25;
	$pr_vars['priem_use'][0]['name'] = '���� ������ ������';
	$pr_vars['priem_use'][0]['id'] = 216;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = '������� ����';
	$pr_vars['priem_use'][1]['id'] = 11;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][2]['chance'] = 25;
	$pr_vars['priem_use'][2]['name'] = '�������� ����';
	$pr_vars['priem_use'][2]['id'] = 213;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = '���������';
	$pr_vars['priem_use'][3]['id'] = 13;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][4]['chance'] = 25;
	$pr_vars['priem_use'][4]['name'] = '������';
	$pr_vars['priem_use'][4]['id'] = 14;
	$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_regen']['hp'] = 45;
	$pr_vars['priem_regen']['chance'] = 15;
	$pr_vars['priem_regen']['name'] = '���� � ������';
}


/*
* ������ ������ (������)
* */
if($this->users[$this->uids[$uid1]]['bot_id'] == 356) {
###����� ������ [9] - ������ 1 ����
	$pr_use = 1;

	$pr_vars['priem_use'][0]['chance'] = 25;
	$pr_vars['priem_use'][0]['name'] = '���� ������ ������';
	$pr_vars['priem_use'][0]['id'] = 216;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = '������� ����';
	$pr_vars['priem_use'][1]['id'] = 11;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][2]['chance'] = 25;
	$pr_vars['priem_use'][2]['name'] = '�������� ����';
	$pr_vars['priem_use'][2]['id'] = 213;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = '���������';
	$pr_vars['priem_use'][3]['id'] = 13;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][4]['chance'] = 25;
	$pr_vars['priem_use'][4]['name'] = '������';
	$pr_vars['priem_use'][4]['id'] = 14;
	$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_regen']['hp'] = 45;
	$pr_vars['priem_regen']['chance'] = 15;
	$pr_vars['priem_regen']['name'] = '���� � ������';
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 355) {
### ������ ������ [12] - ������ 3 ����

	$pr_use = 1;
	// ���������� ��� - ������ ������ ������
	$pr_vars['priem_use'][0]['chance'] = 20;
	$pr_vars['priem_use'][0]['name'] = '���������� ���';
	$pr_vars['priem_use'][0]['id'] = 45;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// ��������� - ������ ������� ����.
	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = '���������';
	$pr_vars['priem_use'][1]['id'] = 216;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// ���� ������� - ������� ������������ ����.
	$pr_vars['priem_team_f'][0]['chance'] = 25;
	$pr_vars['priem_team_f'][0]['name'] = '���� �������';
	$pr_vars['priem_team_f'][0]['x'] = 1;
	$pr_vars['priem_team_f'][0]['type'] = 3;
	$pr_vars['priem_team_f'][0]['hp'] = 0;
	$pr_vars['priem_team_f'][0]['hp_dmg'] = rand(35,40);
	$pr_vars['priem_team_f'][0]['priem'] = 164;
	$pr_vars['priem_team_f'][0]['team'] = $this->users[$this->uids[$uid2]]['team'];
	$pr_vars['priem_team_f'][0]['on'] = $uid;
	$pr_vars['priem_team_f'][0]['nomf'] = 0;
	$pr_vars['priem_team_f'][0]['fiz'] = 1;
	$pr_vars['priem_team_f'][0]['krituet'] = true;

	$pr_vars['priem_use'][2]['chance'] = 25;
	$pr_vars['priem_use'][2]['name'] = '���������';
	$pr_vars['priem_use'][2]['id'] = 13;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = '������';
	$pr_vars['priem_use'][3]['id'] = 14;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 152) {
### ������� ���� [10] - ������ 2 ����
/*
* 1 ����, 3 �����. ���������� ����: ��������, ��������� �����: �����.
� ������ ��� - ��������� ���� - ������ ������������ ������ ��� ��������� ����� - ������ ������������ �������
������� ����,
�������� ������
������������,
���������
���������,
������

��� ���������� 85%�� �������� ����������� ���������, � ������ ��������� 1%�� ��������� ������������� �� 1% - ��-�� ��������� ���� ���������, �������� �� 33%.
� ����� ��� ���������� ��������� ���� - ������� ������ � ������� - ������� � ����� 8 ��� 10. � ��� ������� 8 ��� 10 ����� ���� ����� ���, ����� �� ����� ���� ������ � �����.
*/

	// ������� ����.
	$pr_vars['priem_use'][0]['chance'] = 20;
	$pr_vars['priem_use'][0]['name'] = '������� ����';
	$pr_vars['priem_use'][0]['id'] = 4;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// �������� ������.
	$pr_vars['priem_use'][1]['chance'] = 20;
	$pr_vars['priem_use'][1]['name'] = '�������� ������';
	$pr_vars['priem_use'][1]['id'] = 7;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// ���������.
	$pr_vars['priem_use'][2]['chance'] = 20;
	$pr_vars['priem_use'][2]['name'] = '���������';
	$pr_vars['priem_use'][2]['id'] = 13;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	// ������.
	$pr_vars['priem_use'][3]['chance'] = 20;
	$pr_vars['priem_use'][3]['name'] = '������';
	$pr_vars['priem_use'][3]['id'] = 14;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	// ������������, ����������� ���� � ���� ������ �������� ������ 30-��...
	if( (int)$u->lookStats($this->users[$this->uids[$uid2]]['stats'])['s2'] > 30) {
		$pr_vars['priem_use'][4]['chance'] = 22;
		$pr_vars['priem_use'][4]['name'] = '������������';
		$pr_vars['priem_use'][4]['id'] = 204;
		$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
	}
	//���������, ����������� ���� � ���� ������� �������� ������ 10-��...
	if( (int)$u->lookStats($this->users[$this->uids[$uid2]]['stats'])['s5'] > 10) {
		$pr_vars['priem_use'][4]['chance'] = 22;
		$pr_vars['priem_use'][4]['name'] = '���������';
		$pr_vars['priem_use'][4]['id'] = 189;
		$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
	}

	$pr_vars['hp'] = mysql_fetch_array(mysql_query('SELECT hp as `Now`, hpAll as `All` FROM `battle_users` WHERE `uid` = "'.$uid1.'" LIMIT 1'));
	$pr_vars['hp']['Ustalost'] = round(85 - ($pr_vars['hp']['Now'] / ($pr_vars['hp']['All']/100))) ;
	// ���������
	if( $pr_vars['hp']['Now'] != 0 AND $pr_vars['hp']['Now'] / ($pr_vars['hp']['All']/100) < 85  ) {
		if( $pr_vars['hp']['Ustalost'] > 0 ){
			if($pr_vars['hp']['Ustalost'] > 33){
				$pr_vars['hp']['Ustalost'] = 33;
			}
			$pr_vars['hp']['exist'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `delete` = "0" AND `id_eff` = "5" AND `uid` = "'.$uid1.'" LIMIT 1'));
			if($pr_vars['hp']['exist']) { // ���� ����������
				mysql_query('UPDATE `eff_users` SET `data` = "add_m10=-'.$pr_vars['hp']['Ustalost'].'0", `name` = "��������� -'.$pr_vars['hp']['Ustalost'].'%" WHERE `delete` = "0" AND `id_eff` = "5" AND `uid` = "'.$uid1.'" LIMIT 1');
			} else { // ���� �� ����������
				mysql_query('INSERT INTO `eff_users` (`id_eff`, `uid`, `img2`, `name`, `data`, `user_use`,`timeUse`, `delete`, `v1`, `v2`, `x`, `no_Ace`) VALUES (5, '.$uid1.', "eff_travma.gif", "��������� -'.$pr_vars['hp']['Ustalost'].'%", "add_m10=-'.$pr_vars['hp']['Ustalost'].'0", "'.$uid1.'","77", "0", "priem", "292", "1", "1")');
			}
		}
	}
	unset($pr_vars['hp']);
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 156) {
### ������� [12] - ������ 3 ����
	/*
	 * ������ ���
	 * */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 158) {
### ���� ����� �������� [9] - ������ 3 ����

	// �������� ����� - ������ ������ ������
	$pr_vars['priem_use'][0]['chance'] = 16;
	$pr_vars['priem_use'][0]['name'] = '�������� �����';
	$pr_vars['priem_use'][0]['id'] = 240;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// ������ �����.
	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = '������ �����';
	$pr_vars['priem_use'][1]['id'] = 47;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// ������ - ������� �� 3000HP.
	$pr_vars['priem_regen']['hp'] = 3000;
	$pr_vars['priem_regen']['chance'] = 4;
	$pr_vars['priem_regen']['name'] = '������';

	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 261) {
### ������ ������ [9] - ������ 1-3 ����
/*  (650HP)
����: 50
��������: 15
��������: 60
������������: 30
���������: 5
��������: 0
1 ���� 2 �����. ���������� ����: �������, ��������� �����: ���� (� 8�).
* */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 345) {
### ������ ������ [8] - ������ 1-3 ����
/* (500HP)
����: 30
��������: 25
��������: 50
������������: 30
���������: 0
��������: 0
1 ���� 2 �����. ���������� ����: �������, ��������� �����: ���� (� 8�).
* */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 346) {
### �������� [7] - ������ 1-3 ����
/* (450HP)
����: 50
��������: 25
��������: 20
������������: 30
���������: 0
��������: 0
1 ����, 2 �����. ���������� ����: ��������.
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 347) {
### �������� [8] - ������ 1-3 ����
/* (500HP)
����: 30
��������: 30
��������: 50
������������: 30
���������: 0
��������: 0
1 ����, 2 �����. ���������� ����: ��������.
����� ��������: ������
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 348) {
### ����������� ������ [9] - ������ 1-3 ����
/*(600HP)
����: 50
��������: 50
��������: 20
������������: 30
���������: 10
��������: 0
1 ���� 2 �����. ���������� ����: �������, ��������� �����: ���� (� 8�).
 */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 349) {
### ����������� ������ [8] - ������ 1-3 ����
/* (375HP)
����: 40
��������: 50
��������: 35
������������: 30
���������: 0
��������: 0
1 ���� 2 �����. ���������� ����: �������, ��������� �����: ���� (� 8�).
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 350) {
### ��������� ������ [8] - ������ 1-3 ����
/*
 * (500HP)
� ����������: 1 ��.
����: 30
��������: 30
��������: 50
������������: 30
���������: 0
��������: 0
1 ���� 2 �����. ���������� ����: �������, ��������� �����: ����.
���������� �����:
"��������� ������" - ���� ������ ����.
"���� � ������"
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 351) {
### ��������� ������ [9] - ������ 1-3 ����
/* (750HP)
� ����������: 1 ��.
����: 50
��������: 15
��������: 15
������������: 50
���������: 0
��������: 0
1 ���� 2 �����. ���������� ����: �������, ��������� �����: ����.
���������� �����:
"��������� ������" - ���� ������ ����.
"���� � ������"
 */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 352) {
### ������� ������� ����� [9] - ������ 3 ����
	// ������� ����.
	$pr_vars['priem_use'][0]['chance'] = 25;
	$pr_vars['priem_use'][0]['name'] = '������� ����';
	$pr_vars['priem_use'][0]['id'] = 11;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// �������� ������.
	$pr_vars['priem_use'][1]['chance'] = 20;
	$pr_vars['priem_use'][1]['name'] = '�������� ������';
	$pr_vars['priem_use'][1]['id'] = 7;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// ���������.
	$pr_vars['priem_use'][2]['chance'] = 20;
	$pr_vars['priem_use'][2]['name'] = '���������';
	$pr_vars['priem_use'][2]['id'] = 13;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	// ������.
	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = '������';
	$pr_vars['priem_use'][3]['id'] = 14;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 353) {
### ��������� ������ [9] - ������ 3 ����
/* (1200HP)
����: 50
��������: 15
��������: 15
������������: 50
���������: 0
��������: 0
1 ���� 2 ���� ������. ���������� ����: ��������, ��������� �����: �����.
���������� �����:
"��������� ������" - ���� ������ ����.
"��������" - ���� ������ �����, �� ��� �������.
"���� ��������" - ������ "���������� ������"
"���� � ������" .
 * */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 355) {
### ��������� ������ [8] - ������ 2 ����
/* (800HP)
� ����������: 8 ��.
����: 80
��������: 3
��������: 3
������������: 40
���������: 0
��������: 0
1 ���� 2 �����. ���������� ����: ��������, ��������� �����: �����. ���������� (�������� ����� ��� ����� ������).
�������� ������� �����.
 */
	$pr_use = 1;
}

/*
* ����� ������ (������)
* */

if($this->users[$this->uids[$uid1]]['bot_id'] == 126) {
###���� ����������� [8]
  $pr_use = 1; 
  
  $pr_vars['priem_use'][0]['chance'] = 15;
  $pr_vars['priem_use'][0]['name'] = '���� �������';
  $pr_vars['priem_use'][0]['id'] = 216;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][1]['chance'] = 15;
  $pr_vars['priem_use'][1]['name'] = '������ �����';
  $pr_vars['priem_use'][1]['id'] = 47;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 15;
  $pr_vars['priem_use'][2]['name'] = '�������� ������';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 15;
  $pr_vars['priem_use'][3]['name'] = '���������';
  $pr_vars['priem_use'][3]['id'] = 13;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][4]['chance'] = 15;
  $pr_vars['priem_use'][4]['name'] = '������';
  $pr_vars['priem_use'][4]['id'] = 14;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 132) {
###�������� ����� [9]
###To Do : ���������, ����������
  $pr_use = 0;

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 133) {
###���-��������� [10]
###To Do : ���������� �������
  $pr_use = 0;

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 134) {
###��������� ���� [10]
###To Do : ���������� ����, ���� ����
  $pr_use = 1;

  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = '����';
  $pr_vars['priem_use'][0]['id'] = 141;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 135) {
###������� [9]
###To Do : ��������
  $pr_use = 0;
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 136) {
###������������ ����� [9]
###To Do : �������, ������
  $pr_use = 1;
  
  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = '���������';
  $pr_vars['priem_use'][0]['id'] = 212;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 139) {
###����� �������� [11]
###To Do : ����������, ��������� ������, ���������, ���������� �����
  $pr_use = 0;

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 141) {
###������������ ������ [10]
###To Do : �����, �������� ����
  $pr_use = 0;
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 142) {
###������ ���� [11]
###To Do : �������� ���, ���������� �����, ������� �����, ������, �������� �����, ���������������, ������� ����, ���������� ����
  $pr_use = 0;
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 143) {
###������������ �������� [10]
###To Do : �������, ������
  $pr_use = 1;
  
  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = '���������';
  $pr_vars['priem_use'][0]['id'] = 212;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 376) {
###������� ��������� ������ [9]
  $pr_use = 1; 

  $pr_vars['priem_regen']['hp'] = 18;
  $pr_vars['priem_regen']['chance'] = 7;
  $pr_vars['priem_regen']['name'] = '������� ���';

  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = '���������� ���';
  $pr_vars['priem_use'][0]['id'] = 45;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][1]['chance'] = 3;
  $pr_vars['priem_use'][1]['name'] = '���������';
  $pr_vars['priem_use'][1]['id'] = 216;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 3;
  $pr_vars['priem_use'][2]['name'] = '�������� ������';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 3;
  $pr_vars['priem_use'][3]['name'] = '������� ����';
  $pr_vars['priem_use'][3]['id'] = 11;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][4]['chance'] = 3;
  $pr_vars['priem_use'][4]['name'] = '���������';
  $pr_vars['priem_use'][4]['id'] = 13;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][5]['chance'] = 3;
  $pr_vars['priem_use'][5]['name'] = '������';
  $pr_vars['priem_use'][5]['id'] = 14;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];
  
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 284) {
###����
  $pr_use = 1; 

  $pr_vars['priem_regen']['hp'] = 18;
  $pr_vars['priem_regen']['chance'] = 25;
  $pr_vars['priem_regen']['name'] = '������� ���';
  
  $pr_vars['priem_regen']['hp'] = 45;
  $pr_vars['priem_regen']['chance'] = 25;
  $pr_vars['priem_regen']['name'] = '���� � ������';
  
  $pr_vars['priem_use'][2]['chance'] = 10;
  $pr_vars['priem_use'][2]['name'] = '�������� ������';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 5;
  $pr_vars['priem_use'][3]['name'] = '������� ����';
  $pr_vars['priem_use'][3]['id'] = 11;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 30;
  $pr_vars['priem_use'][3]['name'] = '����������� ������';
  $pr_vars['priem_use'][3]['id'] = 211;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][4]['chance'] = 10;
  $pr_vars['priem_use'][4]['name'] = '���������';
  $pr_vars['priem_use'][4]['id'] = 13;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][5]['chance'] = 10;
  $pr_vars['priem_use'][5]['name'] = '������';
  $pr_vars['priem_use'][5]['id'] = 14;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][5]['chance'] = 10;
  $pr_vars['priem_use'][5]['name'] = '������';
  $pr_vars['priem_use'][5]['id'] = 14;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];
  
  
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 254) {
###�������
  $pr_use = 1; 
  
  $pr_vars['priem_regen']['hp'] = 75;
  $pr_vars['priem_regen']['chance'] = 30;
  $pr_vars['priem_regen']['name'] = '�����������';
  
  $pr_vars['priem_use'][0]['chance'] = 95;
  $pr_vars['priem_use'][0]['name'] = '������� ����';
  $pr_vars['priem_use'][0]['id'] = 11;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
 
  
  $pr_vars['priem_use'][1]['chance'] = 25;
  $pr_vars['priem_use'][1]['name'] = '������';
  $pr_vars['priem_use'][1]['id'] = 14;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 60;
  $pr_vars['priem_use'][2]['name'] = '���������� �����';
  $pr_vars['priem_use'][2]['id'] = 219;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
}
	
  if($pr_use > 0) {	  
  	//priem_use , priem_team_f , priem_regen ��� ���������� ������ 1 �����
  	//if( count($pr_vars['priem_use']) > 0 && count($pr_vars['priem_team_f']) > 0 ) {
		if( rand(0,1) == 1 || !isset($pr_vars['priem_team_f']) ) {
			$pr_vars['priem_use'] = $pr_vars['priem_use'][rand(0,count($pr_vars['priem_use'])-1)];
			$pr_vars['priem_use'] = array( 0 => $pr_vars['priem_use'] );
			unset($pr_vars['priem_team_f']);
		}else{
			$pr_vars['priem_team_f'] = $pr_vars['priem_team_f'][rand(0,count($pr_vars['priem_team_f'])-1)];
			$pr_vars['priem_team_f'] = array( 0 => $pr_vars['priem_team_f'] );
			unset($pr_vars['priem_use']);
		}
	//}
  
	$i = 0;
	while($i < count($pr_vars['priem_team_f'])) {
	  if($pr_vars['priem_team_f'][$i]['chance']*10000 >= rand(0, 1000000)) {
	    $xx = 0; $ix = 0;
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `activ` != "-1" AND `id` = "'.$pr_vars['priem_team_f'][$i]['priem'].'" LIMIT 1'));
		while($ix < count($this->users)) {
		  if($this->stats[$ix]['hpNow'] > 0 && $this->users[$ix]['team'] == $pr_vars['priem_team_f'][$i]['team'] && $xx < $pr_vars['priem_team_f'][$i]['x']) {
		    if(isset($pl['id'])) {
		      $pr_vars['priem_team_f'][$i]['hp_dmg'] = $this->testYronPriem( $uid1, $uid2, 12, $pr_vars['priem_team_f'][$i]['hp_dmg'], -1, true );
			  $as = $priem->magicAtack($this->users[$ix], $pr_vars['priem_team_f'][$i]['hp_dmg'], $pr_vars['priem_team_f'][$i]['type'], $pl, array('user_use' => $this->users[$this->uids[$uid1]]['id']), 0, 0, $pr_vars['priem_team_f'][0]['fiz'], $pr_vars['priem_team_f'][$i]['nomf'], $pr_vars['priem_team_f'][0]['krituet'], $this->users[$this->uids[$uid1]]['id'],$pr_vars['priem_team_f'][$i]['name']);
			  ###������������ ������������� �� ����� ����������� �����. (��������������)
              if($as && isset($pr_vars['priem_team_f'][$i]['hpregen'])) {
                if(isset($pr_vars['priem_team_f'][$i]['hp']) || isset($pr_vars['priem_team_f'][$i]['hp_dmg'])) {
                  if(isset($pr_vars['priem_team_f'][$i]['hp_dmg'])) {
                    $n_hp = $this->stats[$this->uids[$uid1]]['hpNow']+$as[0];
                    $hp_vis = '+'.$as[0];
                  } else {
                    $n_hp = $this->stats[$this->uids[$uid1]]['hpNow']+$pr_vars['priem_team_f'][$i]['hp'];
                    $hp_vis = '+'.$pr_vars['priem_team_f'][$i]['hp'];
                  }
                  if($n_hp > $this->stats[$this->uids[$uid1]]['hpAll']) {
                    $n_hp = $this->stats[$this->uids[$uid1]]['hpAll'];
                  }
                  $uid_b = $this->users[$this->uids[$uid1]]['id'];
                  $this->users[$this->uids[$uid1]]['hpNow'] = $n_hp;
                  $this->stats[$this->uids[$uid1]]['hpNow'] = $n_hp;
                  mysql_query('UPDATE `stats` SET `hpNow` = '.$n_hp.' WHERE `id` = "'.$uid_b.'" LIMIT 1');
                  $pr_vars['mas']['text'] = '{tm1} {u1} ����������� ����� &quot;<b>'.$pr_vars['priem_team_f'][$i]['name'].'</b>&quot; � ����������� ���� ��������. <b><font color=#006699>'.$hp_vis.'</font></b> ['.$this->users[$this->uids[$uid1]]['hpNow'].'/'.$this->stats[$this->uids[$uid1]]['hpAll'].']';
                  $pr_vars['mas']['vLog'] = 'time1='.time().'||s1='.$this->users[$this->uids[$uid1]]['sex'].'||t1='.$this->users[$this->uids[$uid1]]['team'].'||login1='.$this->users[$this->uids[$uid1]]['login'].'||s2='.$this->users[$this->uids[$uid2]]['sex'].'||t2='.$this->users[$this->uids[$uid2]]['team'].'||login2='.$this->users[$this->uids[$uid2]]['login'].'';
                  $pr_vars['mas'] = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>$pr_vars['mas']['text'],'vars'=>$pr_vars['mas']['vLog'],'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
                  $this->add_log($pr_vars['mas']);
                }
			  }
              ###
            }
			$xx++;
		  }
		  $ix++;
		}
	  }
	  $i++;	
	}

    if(isset($pr_vars['priem_regen']) && $pr_vars['priem_regen']['chance']*10000 >= rand(0, 1000000)) {
      $pr_vars['hp_u1'] += $pr_vars['priem_regen']['hp'];
	  if($pr_vars['priem_regen']['hp'] > 0) {
		$pr_vars['priem_regen']['hp'] = '+'.$pr_vars['priem_regen']['hp'];
	  }
	  $this->users[$this->uids[$uid1]]['hpNow'] = $pr_vars['hp_u1'];
	  $this->stats[$this->uids[$uid1]]['hpNow'] = $pr_vars['hp_u1'];
      mysql_query('UPDATE `stats` SET `hpNow` = '.$pr_vars['hp_u1'].' WHERE `id` = "'.$uid2.'" LIMIT 1');
	  if( $pr_vars['hp_u1'] > $this->stats[$this->uids[$uid1]]['hpAll'] ) {
		 $pr_vars['hp_u1'] = $this->stats[$this->uids[$uid1]]['hpAll'];
	  }
	  $pr_vars['mas']['text'] = '{tm1} {u1} ����������� ����� &quot;<b>'.$pr_vars['priem_regen']['name'].'</b>&quot; � ����������� ���� ��������. <b><font color=#006699>'.$pr_vars['priem_regen']['hp'].'</font></b> ['.ceil($pr_vars['hp_u1']).'/'.$this->stats[$this->uids[$uid1]]['hpAll'].']';
	  $pr_vars['mas']['vLog'] = 'time1='.time().'||s1='.$this->users[$this->uids[$uid1]]['sex'].'||t1='.$this->users[$this->uids[$uid1]]['team'].'||login1='.$this->users[$this->uids[$uid1]]['login'].'||s2='.$this->users[$this->uids[$uid2]]['sex'].'||t2='.$this->users[$this->uids[$uid2]]['team'].'||login2='.$this->users[$this->uids[$uid2]]['login'].'';
	  $pr_vars['mas'] = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID),'text'=>$pr_vars['mas']['text'],'vars'=>$pr_vars['mas']['vLog'],'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
	  $this->add_log($pr_vars['mas']);
    }
	
	$i = 0;
	while($i < count($pr_vars['priem_use'])) {
	  if(isset($pr_vars['priem_use'][$i]) && $pr_vars['priem_use'][$i]['chance'] >= rand(0, 100)) {
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `activ` != "-1" AND `id` = "'.mysql_real_escape_string($pr_vars['priem_use'][$i]['id']).'" LIMIT 1'));
		
        if(isset($pl['id']) && $pl['id'] == 290) {
          $priem->magicAtack($pr_vars['priem_use'][$i]['on'], 100, $pr_vars['priem_use'][$i]['type'], $pl, array('user_use' => $uid1), 0, 0, 0, 0, true, $this->users[$this->uids[$uid1]]['id'], $pl['name']);
        }
        
        if(isset($pl['id'])) {
		  $rcu = false;
	      $j = $u->lookStats($pl['date2']);		
		  $mpr = false; $addch = 0;
		  $uid = $this->users[$this->uids[$uid1]]['id'];
		  if(isset($pr_vars['priem_use'][$i]['on']['id'])) {
			$uid = $pr_vars['priem_use'][$i]['on']['id'];
		  }
		  if(isset($j['onlyOne'])) {
			$mpr = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `v2` = "'.$pl['id'].'" AND `uid` = "'.$uid.'" AND `delete` = 0 LIMIT 1'));
		  }
		  $pld = array(0 => ''); $nc = 0;
		  if(isset($mpr['id']) && $j['onlyOne'] == 1) {
			$addch = 1;
			//$priem->mintr($pl);
			$priem->uppz($pl, $id);
			if(isset($pr_vars['priem_use'][$i]['on']['id'])) {
			  $this->stats[$this->uids[$uid]] = $u->getStats($pr_vars['priem_use'][$i]['on'], 0);
			} else {
			  $this->stats[$this->uids[$uid]] = $u->getStats($this->users[$this->uids[$uid1]], 0);	
			}
			$nc = 1;
		  } elseif(!isset($mpr['id'])) {
			$data = '';
			if(isset($j['date3Plus'])) {
			  $data = $priem->redate($pl['date3'], $this->users[$this->uids[$uid1]]['id']);
			}
			$hd1 = -1;
			if($pl['limit'] > 0) {
			  $tm = 77;
			  $hd1 = $pl['limit'];
			} else {
			  $tm = 77;
			}
			mysql_query('INSERT INTO `eff_users` (`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`) VALUES ("'.$hd1.'", "'.$pl['id'].'", "'.$pl['img'].'.gif", 22, "'.$uid.'", "'.$pr_vars['priem_use'][$i]['name'].'", "'.$data.'", 0, "'.$tm.'", "priem", "'.$this->users[$this->uids[$uid1]]['id'].'")');
			unset($hd1);
			$addch = 1; $rcu = true; $nc = 1;
			//$priem->mintr($pl);
			$priem->uppz($pl,$id);
		  } elseif($j['onlyOne'] > 1) {
			if($mpr['x'] < $j['onlyOne']) {
			  if(isset($j['date3Plus'])) {
				$j1 = $u->lookStats($mpr['data']);
				$j2 = $u->lookStats($priem->redate($pl['date3'], $this->users[$this->uids[$uid1]]['id']));
				$v = $u->lookKeys($priem->redate($pl['date3'], $this->users[$this->uids[$uid1]]['id']), 0);
				$i56 = 0; $inf = '';
				while($i56 < count($v)) {
				  $j1[$v[$i56]] += $j2[$v[$i56]];
				  $vi = str_replace('add_', '', $v[$i56]);
				  if($u->is[$vi] != '') {
					if($j2[$v[$i56]] > 0) {
					  $inf .= $u->is[$vi].': +'.($j2[$v[$i56]]*(1+$mpr['x'])).', ';
					} elseif($j2[$v[$i56]] < 0) {
					  $inf .= $u->is[$vi].': '.($j2[$v[$i56]]*(1+$mpr['x'])).', ';	
					}
				  }
				  $i56++;	
				}
				$inf = rtrim($inf, ', ');
				$j1 = $u->impStats($j1);
				$pld[0] = ' x'.($mpr['x']+1);
				$upd = mysql_query('UPDATE `eff_users` SET `data` = "'.$j1.'", `x` = `x`+1 WHERE `id` = "'.$mpr['id'].'" LIMIT 1');
				if($upd) {
				  //$priem->mintr($pl);
				  $priem->uppz($pl, $id);
				  $addch = 1;
				  $rcu = true;
				  $nc = 1;
				}
			  }				
			}				
		  }
		}
		if($rcu == true && !isset($pr_vars['priem_use'][$i]['no_chat'])) {
		  if( $inf != '' ) {
			 $inf = '('.$inf.')'; 
		  }
		  if($this->users[$this->uids[$uid1]]['id'] != $uid) {
			if(isset($inf)) {
			  $pr_vars['mas']['text'] = '{tm1} {u1} ����������� ����� &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot; �� ��������� {u2}. <small>'.$inf.'</small>';
			} else {
			  $pr_vars['mas']['text'] = '{tm1} {u1} ����������� ����� &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot;  �� ��������� {u2}.';
		    }
		  } else {
			if(isset($inf)) {
			  $pr_vars['mas']['text'] = '{tm1} {u1} ����������� ����� &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot;. <small>'.$inf.'</small>';
			} else {
			  $pr_vars['mas']['text'] = '{tm1} {u1} ����������� ����� &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot;.';
			}
		  }
		  $pr_vars['mas']['vLog'] = 'time1='.time().'||s1='.$this->users[$this->uids[$uid1]]['sex'].'||t1='.$this->users[$this->uids[$uid1]]['team'].'||login1='.$this->users[$this->uids[$uid1]]['login'].'||s2='.$this->users[$this->uids[$uid2]]['sex'].'||t2='.$this->users[$this->uids[$uid2]]['team'].'||login2='.$this->users[$this->uids[$uid2]]['login'].'';
		  $pr_vars['mas'] = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID),'text'=>$pr_vars['mas']['text'],'vars'=>$pr_vars['mas']['vLog'],'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		  $this->add_log($pr_vars['mas']);
		}
	  }
	  $i++;
	}
	unset($pr_use, $pr_vars);
  }
}
?>