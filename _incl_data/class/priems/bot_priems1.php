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
  
  
	//Излом хаоса
	if($this->users[$this->uids[$uid1]]['bot_id'] == 416) {
		$pr_use = 1;
		#1#
		$pr_vars['priem_use'][0]['chance'] = 50;
		$pr_vars['priem_use'][0]['name'] = 'Грация Боя';
		$pr_vars['priem_use'][0]['id'] = 291;
		$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
		#1#
	}
  
###Start (Приёмы ботам)###

if($this->users[$this->uids[$uid1]]['bot_id'] == 118 || $this->users[$this->uids[$uid1]]['bot_id'] == 119) {
###Пустынник Атаман [8] / Пустынник Атаман [9]
	$pr_use = 1;
  
	$pr_vars['priem_use'][0]['chance'] = 30;
	$pr_vars['priem_use'][0]['name'] = 'Сильный удар';
	$pr_vars['priem_use'][0]['id'] = 4;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
	//Шокирующий
	$pr_vars['priem_use'][0]['chance'] = 15;
	$pr_vars['priem_use'][0]['name'] = 'Шокирующий удар';
	$pr_vars['priem_use'][0]['id'] = 236;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
  
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 50 || $this->users[$this->uids[$uid1]]['bot_id'] == 53) {
	//Паук или Сточный паук
	//priem team x type hp hp_dmg
	  $pr_use = 1;
	  
	  //Быстрый удар
	  $pr_vars['priem_team_f'][] = array(
		  'chance' => 25,
		  'name' => 'Быстрый удар',
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
	  
	  //Отпрыгнуть
	  $pr_vars['priem_use'][] = array(
		  'chance' => 25,
		  'name' => 'Отпрыгнуть',
		  'id' => 8,
		  'on' => $this->users[$this->uids[$uid1]],
		  'no_chat' => true
	  );
	  
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 54) {
	//Канализационный жук
	  $pr_use = 1;
	  //Ослабить удар
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
	  $pr_vars['priem_use'][0]['id'] = 1;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //Мощный удар
	  $pr_vars['priem_use'][1]['chance'] = 10;
	  $pr_vars['priem_use'][1]['name'] = 'Мощный удар';
	  $pr_vars['priem_use'][1]['id'] = 2;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 57) {
	//Сантехник зомби
	  $pr_use = 1;
	  //Быстрый удар
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Быстрый удар';
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
	  //Мощный удар
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = 'Мощный удар';
	  $pr_vars['priem_use'][0]['id'] = 2;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 58) {
	//Обитатель подвалов
	  $pr_use = 1;
	  //Быстрый удар
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Мокрый удар';
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
	  
	  //Мощный удар
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = 'Мощный удар';
	  $pr_vars['priem_use'][0]['id'] = 2;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //Закусить
	  $pr_vars['priem_regen']['hp'] = 8;
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = 'Закусить';
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 56) {
	//Тунельный гад
	  $pr_use = 1;
	  //Быстрый удар
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Быстрый удар';
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
	  
	  //Регенерация
	  $pr_vars['priem_regen']['hp'] = rand(8,12);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = 'Регенерация';
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 55) {
	//Жуткая мерзость
	  $pr_use = 1;
	  //Быстрый удар
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Быстрый удар';
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
	  //Кислотное касание
	  $pr_vars['priem_team_f'][1]['chance'] = 10;
	  $pr_vars['priem_team_f'][1]['name'] = 'Кислотное касание';
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
	  //Регенерация
	  $pr_vars['priem_regen']['hp'] = rand(8,12);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = 'Регенерация';
	  //Надкусить
	  $pr_vars['priem_use'][0]['chance'] = 50;
	  $pr_vars['priem_use'][0]['name'] = 'Надкусить';
	  $pr_vars['priem_use'][0]['id'] = 293;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 52) {
	//Мартын
	  $pr_use = 1;
	  //Мокрый удар
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Мокрый удар';
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
	  //Регенерация
	  $pr_vars['priem_regen']['hp'] = rand(8,12);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = 'Регенерация';
	  //Ослабить удар
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
	  $pr_vars['priem_use'][0]['id'] = 1;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //Мощный удар
	  $pr_vars['priem_use'][1]['chance'] = 10;
	  $pr_vars['priem_use'][1]['name'] = 'Мощный удар';
	  $pr_vars['priem_use'][1]['id'] = 2;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  //Надкусить
	  if( rand(0,100) < 10 ) {
		  //Зловонная Вода
		  $pr_vars['priem_team_f'][1]['chance'] = 100;
		  $pr_vars['priem_team_f'][1]['name'] = 'Зловонная Вода';
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
		  $pr_vars['priem_use'][2]['name'] = 'Зловонная Вода';
		  $pr_vars['priem_use'][2]['id'] = 294;
		  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid2]];
		  $pr_vars['priem_use'][2]['no_chat'] = true;
	  }
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 64) {
	//Канализационный паук
	  $pr_use = 1;
	  //Быстрый удар
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Быстрый удар';
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
	  //Проткнуть
	  if( rand(0,100) < 15 ) {
		  //Проткнуть
		  $pr_vars['priem_use'][0]['chance'] = 100;
		  $pr_vars['priem_use'][0]['name'] = 'Проткнуть';
		  $pr_vars['priem_use'][0]['id'] = 295;
		  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
		  //$pr_vars['priem_use'][2]['no_chat'] = true;
	  }
	  //Двойной удар
	  if( rand(0,100) < 15 ) {
		  $pr_vars['priem_team_f'][1]['chance'] = 100;
		  $pr_vars['priem_team_f'][1]['name'] = 'Двойной удар';
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
		  $pr_vars['priem_team_f'][2]['name'] = 'Двойной удар';
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
	//Безголовый Сантехник
	  $pr_use = 1; 
	  //Проткнуть
	  if( rand(0,100) < 15 ) {
		  //Проткнуть
		  $pr_vars['priem_use'][0]['chance'] = 100;
		  $pr_vars['priem_use'][0]['name'] = 'Гнилая кровь';
		  $pr_vars['priem_use'][0]['id'] = 296;
		  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
	  }
	  //
  	  $pr_vars['priem_use'][] = array(
		 'chance' => 10,
		 'name' => 'Ослабить удар',
		 'id' => 7,
		 'on' => $this->users[$this->uids[$uid1]],
		 'no_chat' => true
	  );
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 65) {
	//Страшная крыса
	  $pr_use = 1;
	  //Надкусить
	  $pr_vars['priem_use'][0]['chance'] = 40;
	  $pr_vars['priem_use'][0]['name'] = 'Надкусить';
	  $pr_vars['priem_use'][0]['id'] = 293;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
	  //Укусить
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Укусить';
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
	//Летучая бестия
	  $pr_use = 1;
	  //Укусить
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Укусить';
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
  	  $pr_vars['priem_use'][0]['name'] = 'Облако тьмы';
  	  $pr_vars['priem_use'][0]['id'] = 236;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
	  //Отпрыгнуть
	  $pr_vars['priem_use'][1]['chance'] = 15;
	  $pr_vars['priem_use'][1]['name'] = 'Отпрыгнуть';
	  $pr_vars['priem_use'][1]['id'] = 8;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 63) {
	//Кровавый сантехник
	  $pr_use = 1;
	  //ослабить удар
  	 $pr_vars['priem_use'][0]['chance'] = 10;
  	 $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
  	 $pr_vars['priem_use'][0]['id'] = 7;
  	 $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	 $pr_vars['priem_use'][0]['no_chat'] = true;
	  //Надкусить
	  $pr_vars['priem_use'][1]['chance'] = 50;
	  $pr_vars['priem_use'][1]['name'] = 'Надкусить';
	  $pr_vars['priem_use'][1]['id'] = 293;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid2]];
	  //Проткнуть
	  if( rand(0,100) < 10 ) {
		  //Проткнуть
		  $pr_vars['priem_use'][2]['chance'] = 100;
		  $pr_vars['priem_use'][2]['name'] = 'Гнилая кровь';
		  $pr_vars['priem_use'][2]['id'] = 296;
		  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid2]];
	  }
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 68) {
	//Старожил 
	  $pr_use = 1;
	  //Метнуть болт
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Метнуть болт';
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
	  //Метнуть болт
	  $pr_vars['priem_team_f'][1]['chance'] = 10;
	  $pr_vars['priem_team_f'][1]['name'] = 'Взрыв грязи';
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
	  //ослабить удар
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  /* if( rand(0,100) < 25 ) {
		  //Метнуть болт
		  if( !isset($this->stats[$this->uids[$uid2]]['noeffectbattle1'])) {
			  $pr_vars['priem_team_f'][1]['chance'] = 100;
			  $pr_vars['priem_team_f'][1]['name'] = 'Прочистить';
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
	//Местный житель 
	  $pr_use = 1;
	  //Взрыв грязи
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Взрыв грязи';
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
	  //ослабить удар
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //ослабить удар
  	  $pr_vars['priem_use'][1]['chance'] = 10;
  	  $pr_vars['priem_use'][1]['name'] = 'Активная защита';
  	  $pr_vars['priem_use'][1]['id'] = 7;
  	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  //Регенерация
	  $pr_vars['priem_regen']['hp'] = rand(1,10);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = 'Регенерация';
	  //Закусить
	  $pr_vars['priem_team_f'][1]['chance'] = 10;
	  $pr_vars['priem_team_f'][1]['name'] = 'Закусить';
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
	//Старший прораб 
	  $pr_use = 1;
	  //ослабить удар
  	  $pr_vars['priem_use'][0]['chance'] = 15;
  	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //Активная защита
  	  $pr_vars['priem_use'][1]['chance'] = 15;
  	  $pr_vars['priem_use'][1]['name'] = 'Активная защита';
  	  $pr_vars['priem_use'][1]['id'] = 7;
  	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
	  //Собраться
  	  $pr_vars['priem_use'][2]['chance'] = 15;
  	  $pr_vars['priem_use'][2]['name'] = 'Собраться';
  	  $pr_vars['priem_use'][2]['id'] = 297;
  	  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][2]['no_chat'] = true;
	  //Приказ Слабости
  	  $pr_vars['priem_use'][3]['chance'] = 5;
  	  $pr_vars['priem_use'][3]['name'] = 'Приказ Слабости';
  	  $pr_vars['priem_use'][3]['id'] = 298;
  	  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid2]];
	  //Регенерация
	  $pr_vars['priem_regen']['hp'] = rand(1,10);
	  $pr_vars['priem_regen']['chance'] = 10;
	  $pr_vars['priem_regen']['name'] = 'Регенерация';
	  //Двойной удар
	  if( rand(0,100) < 15 ) {
		  //
		  $pr_vars['priem_team_f'][0]['chance'] = 100;
		  $pr_vars['priem_team_f'][0]['name'] = 'Двойной удар';
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
		  $pr_vars['priem_team_f'][1]['name'] = 'Двойной удар';
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
	//Хозяин канализации 
	  $pr_use = 1;
	  //Метнуть болт
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Метнуть болт';
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
	  //ослабить удар
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //$pr_vars['priem_use'][1]['no_chat'] = true;
	  if( rand(0,100) < 10 ) {
		  //Метнуть болт
		  if( !isset($this->stats[$this->uids[$uid2]]['noeffectbattle1'])) {
			  $pr_vars['priem_team_f'][1]['chance'] = 100;
			  $pr_vars['priem_team_f'][1]['name'] = 'Прочистить';
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
	  //Двойной удар
	  if( rand(0,100) < 10 ) {
		  //
		  $cnt1 = count($pr_vars['priem_team_f']);
		  $pr_vars['priem_team_f'][$cnt1]['chance'] = 100;
		  $pr_vars['priem_team_f'][$cnt1]['name'] = 'Двойной удар';
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
		  $pr_vars['priem_team_f'][$cnt1]['name'] = 'Двойной удар';
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
	  //Слесарь-зомби 
	  $pr_use = 1;
	  //ослабить удар
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
  	  $pr_vars['priem_use'][0]['id'] = 7;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //Бесчувственность
  	  $pr_vars['priem_use'][0]['chance'] = 10;
  	  $pr_vars['priem_use'][0]['name'] = 'Бесчувственность';
  	  $pr_vars['priem_use'][0]['id'] = 141;
  	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //
	  /*if( rand(0,100) < 25 ) {
		  //Метнуть болт
		  if( !isset($this->stats[$this->uids[$uid2]]['noeffectbattle1'])) {
			  $pr_vars['priem_team_f'][0]['chance'] = 100;
			  $pr_vars['priem_team_f'][0]['name'] = 'Прочистить';
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
	  //Двойной удар
	  if( rand(0,100) < 10 ) {
		  //
		  $cnt1 = count($pr_vars['priem_team_f']);
		  $pr_vars['priem_team_f'][$cnt1]['chance'] = 100;
		  $pr_vars['priem_team_f'][$cnt1]['name'] = 'Двойной удар';
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
		  $pr_vars['priem_team_f'][$cnt1]['name'] = 'Двойной удар';
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
	//Лука
	  $pr_use = 1;
	  //Мокрый удар
	  $pr_vars['priem_team_f'][0]['chance'] = 10;
	  $pr_vars['priem_team_f'][0]['name'] = 'Мокрый удар';
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
	  //Ослабить удар
	  $pr_vars['priem_use'][0]['chance'] = 10;
	  $pr_vars['priem_use'][0]['name'] = 'Ослабить удар';
	  $pr_vars['priem_use'][0]['id'] = 1;
	  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][0]['no_chat'] = true;
	  //Мощный удар
	  $pr_vars['priem_use'][1]['chance'] = 10;
	  $pr_vars['priem_use'][1]['name'] = 'Мощный удар';
	  $pr_vars['priem_use'][1]['id'] = 2;
	  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
	  $pr_vars['priem_use'][1]['no_chat'] = true;
}elseif($this->users[$this->uids[$uid1]]['bot_id'] == 123) {
###Дарьяна Корт [8]
###To Do: Вытягивание души
  $pr_use = 1;

  $pr_vars['priem_regen']['hp'] = rand(50,350);
  $pr_vars['priem_regen']['chance'] = 15;
  $pr_vars['priem_regen']['name'] = 'Лечение';

  $pr_vars['priem_use'][0]['chance'] = 25;
  $pr_vars['priem_use'][0]['name'] = 'Активная защита';
  $pr_vars['priem_use'][0]['id'] = 7;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

  /*$pr_vars['priem_use'][1]['chance'] = 3;
  $pr_vars['priem_use'][1]['name'] = 'Стойкость';
  $pr_vars['priem_use'][1]['id'] = 13;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][2]['chance'] = 3;
  $pr_vars['priem_use'][2]['name'] = 'Ярость';
  $pr_vars['priem_use'][2]['id'] = 14;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];*/
}


if($this->users[$this->uids[$uid1]]['bot_id'] == 124) {
###Изгнанник Мглы [9]
  $pr_use = 1;
  
  $pr_vars['priem_use'][0]['chance'] = 25;
  $pr_vars['priem_use'][0]['name'] = 'Второе дыхание';
  $pr_vars['priem_use'][0]['id'] = 49;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][1]['chance'] = 25;
  $pr_vars['priem_use'][1]['name'] = 'Удар серпом';
  $pr_vars['priem_use'][1]['id'] = 219;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 25;
  $pr_vars['priem_use'][2]['name'] = 'Активная защита';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 25;
  $pr_vars['priem_use'][3]['name'] = 'Сильный удар';
  $pr_vars['priem_use'][3]['id'] = 15;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][4]['chance'] = 25;
  $pr_vars['priem_use'][4]['name'] = 'Слепая удача';
  $pr_vars['priem_use'][4]['id'] = 47;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][5]['chance'] = 25;
  $pr_vars['priem_use'][5]['name'] = 'Стойкость';
  $pr_vars['priem_use'][5]['id'] = 13;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][6]['chance'] = 25;
  $pr_vars['priem_use'][6]['name'] = 'Ярость';
  $pr_vars['priem_use'][6]['id'] = 14;
  $pr_vars['priem_use'][6]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_regen']['hp'] = 45;
  $pr_vars['priem_regen']['chance'] = 15;
  $pr_vars['priem_regen']['name'] = 'Воля к победе';

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 125) {
###Страж Крантон [9]
	$pr_use = 1;

	$pr_vars['priem_use'][0]['chance'] = 25;
	$pr_vars['priem_use'][0]['name'] = 'Удар правым жвалом';
	$pr_vars['priem_use'][0]['id'] = 216;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = 'Удачный удар';
	$pr_vars['priem_use'][1]['id'] = 11;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][2]['chance'] = 25;
	$pr_vars['priem_use'][2]['name'] = 'Коварный уход';
	$pr_vars['priem_use'][2]['id'] = 213;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = 'Стойкость';
	$pr_vars['priem_use'][3]['id'] = 13;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][4]['chance'] = 25;
	$pr_vars['priem_use'][4]['name'] = 'Ярость';
	$pr_vars['priem_use'][4]['id'] = 14;
	$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_regen']['hp'] = 45;
	$pr_vars['priem_regen']['chance'] = 15;
	$pr_vars['priem_regen']['name'] = 'Воля к победе';
}


/*
* Начало Бездны (Бездна)
* */
if($this->users[$this->uids[$uid1]]['bot_id'] == 356) {
###Страж Дайтон [9] - Бездна 1 этаж
	$pr_use = 1;

	$pr_vars['priem_use'][0]['chance'] = 25;
	$pr_vars['priem_use'][0]['name'] = 'Удар правым жвалом';
	$pr_vars['priem_use'][0]['id'] = 216;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = 'Удачный удар';
	$pr_vars['priem_use'][1]['id'] = 11;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][2]['chance'] = 25;
	$pr_vars['priem_use'][2]['name'] = 'Коварный уход';
	$pr_vars['priem_use'][2]['id'] = 213;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = 'Стойкость';
	$pr_vars['priem_use'][3]['id'] = 13;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][4]['chance'] = 25;
	$pr_vars['priem_use'][4]['name'] = 'Ярость';
	$pr_vars['priem_use'][4]['id'] = 14;
	$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_regen']['hp'] = 45;
	$pr_vars['priem_regen']['chance'] = 15;
	$pr_vars['priem_regen']['name'] = 'Воля к победе';
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 355) {
### Кошмар Глубин [12] - Бездна 3 этаж

	$pr_use = 1;
	// Подставить лоб - аналог Полной Защите
	$pr_vars['priem_use'][0]['chance'] = 20;
	$pr_vars['priem_use'][0]['name'] = 'Подставить лоб';
	$pr_vars['priem_use'][0]['id'] = 45;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// Раздавить - аналог Скрытой Силе.
	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = 'Раздавить';
	$pr_vars['priem_use'][1]['id'] = 216;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// Удар Хвостом - наносит моментальный урон.
	$pr_vars['priem_team_f'][0]['chance'] = 25;
	$pr_vars['priem_team_f'][0]['name'] = 'Удар Хвостом';
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
	$pr_vars['priem_use'][2]['name'] = 'Стойкость';
	$pr_vars['priem_use'][2]['id'] = 13;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = 'Ярость';
	$pr_vars['priem_use'][3]['id'] = 14;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 152) {
### Ольгерт Вирт [10] - Бездна 2 этаж
/*
* 1 удар, 3 блока. Профильный урон: дробящий, стихийные атаки: огонь.
в начале боя - Отрицание Силы - аналог Неуязвимость оружию или Отрицание Слова - аналог Неуязвимость стихиям
Сильный удар,
Активная защита
Обречённость,
Ошеломить
Стойкость,
Ярость

При достижении 85%НР начинает действовать Усталость, с каждым потеряным 1%НР усталость увеличивается на 1% - из-за усталости урон снижается, максимум до 33%.
В конце боя использует Последняя Воля - Сгинуть вместе с врагами - зарядов у приёма 8 или 10. У вас остаётся 8 или 10 ходов чтоб убить его, иначе он убьёт всех вместе с собой.
*/

	// Сильный удар.
	$pr_vars['priem_use'][0]['chance'] = 20;
	$pr_vars['priem_use'][0]['name'] = 'Сильный удар';
	$pr_vars['priem_use'][0]['id'] = 4;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// Активная защита.
	$pr_vars['priem_use'][1]['chance'] = 20;
	$pr_vars['priem_use'][1]['name'] = 'Активная защита';
	$pr_vars['priem_use'][1]['id'] = 7;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// Стойкость.
	$pr_vars['priem_use'][2]['chance'] = 20;
	$pr_vars['priem_use'][2]['name'] = 'Стойкость';
	$pr_vars['priem_use'][2]['id'] = 13;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	// Ярость.
	$pr_vars['priem_use'][3]['chance'] = 20;
	$pr_vars['priem_use'][3]['name'] = 'Ярость';
	$pr_vars['priem_use'][3]['id'] = 14;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	// Обречённость, срабатывает если у цели родной ловкости больше 30-ти...
	if( (int)$u->lookStats($this->users[$this->uids[$uid2]]['stats'])['s2'] > 30) {
		$pr_vars['priem_use'][4]['chance'] = 22;
		$pr_vars['priem_use'][4]['name'] = 'Обречённость';
		$pr_vars['priem_use'][4]['id'] = 204;
		$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
	}
	//Ошеломить, срабатывает если у цели родного мудрости больше 10-ти...
	if( (int)$u->lookStats($this->users[$this->uids[$uid2]]['stats'])['s5'] > 10) {
		$pr_vars['priem_use'][4]['chance'] = 22;
		$pr_vars['priem_use'][4]['name'] = 'Ошеломить';
		$pr_vars['priem_use'][4]['id'] = 189;
		$pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
	}

	$pr_vars['hp'] = mysql_fetch_array(mysql_query('SELECT hp as `Now`, hpAll as `All` FROM `battle_users` WHERE `uid` = "'.$uid1.'" LIMIT 1'));
	$pr_vars['hp']['Ustalost'] = round(85 - ($pr_vars['hp']['Now'] / ($pr_vars['hp']['All']/100))) ;
	// Усталость
	if( $pr_vars['hp']['Now'] != 0 AND $pr_vars['hp']['Now'] / ($pr_vars['hp']['All']/100) < 85  ) {
		if( $pr_vars['hp']['Ustalost'] > 0 ){
			if($pr_vars['hp']['Ustalost'] > 33){
				$pr_vars['hp']['Ustalost'] = 33;
			}
			$pr_vars['hp']['exist'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `delete` = "0" AND `id_eff` = "5" AND `uid` = "'.$uid1.'" LIMIT 1'));
			if($pr_vars['hp']['exist']) { // Если существует
				mysql_query('UPDATE `eff_users` SET `data` = "add_m10=-'.$pr_vars['hp']['Ustalost'].'0", `name` = "Усталость -'.$pr_vars['hp']['Ustalost'].'%" WHERE `delete` = "0" AND `id_eff` = "5" AND `uid` = "'.$uid1.'" LIMIT 1');
			} else { // Если не существует
				mysql_query('INSERT INTO `eff_users` (`id_eff`, `uid`, `img2`, `name`, `data`, `user_use`,`timeUse`, `delete`, `v1`, `v2`, `x`, `no_Ace`) VALUES (5, '.$uid1.', "eff_travma.gif", "Усталость -'.$pr_vars['hp']['Ustalost'].'%", "add_m10=-'.$pr_vars['hp']['Ustalost'].'0", "'.$uid1.'","77", "0", "priem", "292", "1", "1")');
			}
		}
	}
	unset($pr_vars['hp']);
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 156) {
### Забытый [12] - Бездна 3 этаж
	/*
	 * ничего нет
	 * */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 158) {
### Гарл Йонни Салистон [9] - Бездна 3 этаж

	// Хлебнуть Крови - аналог Полной Защите
	$pr_vars['priem_use'][0]['chance'] = 16;
	$pr_vars['priem_use'][0]['name'] = 'Хлебнуть Крови';
	$pr_vars['priem_use'][0]['id'] = 240;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// Слепая удача.
	$pr_vars['priem_use'][1]['chance'] = 25;
	$pr_vars['priem_use'][1]['name'] = 'Слепая удача';
	$pr_vars['priem_use'][1]['id'] = 47;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// Ярость - лечится на 3000HP.
	$pr_vars['priem_regen']['hp'] = 3000;
	$pr_vars['priem_regen']['chance'] = 4;
	$pr_vars['priem_regen']['name'] = 'Ярость';

	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 261) {
### Рубака Глубин [9] - Бездна 1-3 этаж
/*  (650HP)
Сила: 50
Ловкость: 15
Интуиция: 60
Выносливость: 30
Интеллект: 5
Мудрость: 0
1 удар 2 блока. Профильный урон: рубящий, стихийные атаки: вода (у 8х).
* */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 345) {
### Рубака Глубин [8] - Бездна 1-3 этаж
/* (500HP)
Сила: 30
Ловкость: 25
Интуиция: 50
Выносливость: 30
Интеллект: 0
Мудрость: 0
1 удар 2 блока. Профильный урон: рубящий, стихийные атаки: вода (у 8х).
* */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 346) {
### Литейщик [7] - Бездна 1-3 этаж
/* (450HP)
Сила: 50
Ловкость: 25
Интуиция: 20
Выносливость: 30
Интеллект: 0
Мудрость: 0
1 удар, 2 блока. Профильный урон: дробящий.
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 347) {
### Литейщик [8] - Бездна 1-3 этаж
/* (500HP)
Сила: 30
Ловкость: 30
Интуиция: 50
Выносливость: 30
Интеллект: 0
Мудрость: 0
1 удар, 2 блока. Профильный урон: дробящий.
Место рождения: Бездна
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 348) {
### Надзиратель Глубин [9] - Бездна 1-3 этаж
/*(600HP)
Сила: 50
Ловкость: 50
Интуиция: 20
Выносливость: 30
Интеллект: 10
Мудрость: 0
1 удар 2 блока. Профильный урон: рубящий, стихийные атаки: вода (у 8х).
 */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 349) {
### Надзиратель Глубин [8] - Бездна 1-3 этаж
/* (375HP)
Сила: 40
Ловкость: 50
Интуиция: 35
Выносливость: 30
Интеллект: 0
Мудрость: 0
1 удар 2 блока. Профильный урон: рубящий, стихийные атаки: вода (у 8х).
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 350) {
### Служитель Глубин [8] - Бездна 1-3 этаж
/*
 * (500HP)
В количестве: 1 шт.
Сила: 30
Ловкость: 30
Интуиция: 50
Выносливость: 30
Интеллект: 0
Мудрость: 0
1 удар 2 блока. Профильный урон: рубящий, стихийные атаки: вода.
Использует приёмы:
"Проклятье Бездны" - каст магией тьмы.
"Воля К Победе"
*/
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 351) {
### Служитель Глубин [9] - Бездна 1-3 этаж
/* (750HP)
В количестве: 1 шт.
Сила: 50
Ловкость: 15
Интуиция: 15
Выносливость: 50
Интеллект: 0
Мудрость: 0
1 удар 2 блока. Профильный урон: рубящий, стихийные атаки: вода.
Использует приёмы:
"Проклятье Бездны" - каст магией тьмы.
"Воля К Победе"
 */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 352) {
### Большой Тяжелый Молот [9] - Бездна 3 этаж
	// Удачный удар.
	$pr_vars['priem_use'][0]['chance'] = 25;
	$pr_vars['priem_use'][0]['name'] = 'Удачный удар';
	$pr_vars['priem_use'][0]['id'] = 11;
	$pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];

	// Активная защита.
	$pr_vars['priem_use'][1]['chance'] = 20;
	$pr_vars['priem_use'][1]['name'] = 'Активная защита';
	$pr_vars['priem_use'][1]['id'] = 7;
	$pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];

	// Стойкость.
	$pr_vars['priem_use'][2]['chance'] = 20;
	$pr_vars['priem_use'][2]['name'] = 'Стойкость';
	$pr_vars['priem_use'][2]['id'] = 13;
	$pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];

	// Ярость.
	$pr_vars['priem_use'][3]['chance'] = 25;
	$pr_vars['priem_use'][3]['name'] = 'Ярость';
	$pr_vars['priem_use'][3]['id'] = 14;
	$pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 353) {
### Служитель Бездны [9] - Бездна 3 этаж
/* (1200HP)
Сила: 50
Ловкость: 15
Интуиция: 15
Выносливость: 50
Интеллект: 0
Мудрость: 0
1 удар 2 зоны блоков. Профильный урон: дробящий, стихийные атаки: огонь.
Использует приёмы:
"Проклятье Бездны" - каст магией тьмы.
"Камнепад" - каст магией земли, на всю команду.
"Аура Святости" - аналог "Призрачной Защиты"
"Воля К Победе" .
 * */
	$pr_use = 1;
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 355) {
### Проклятие Глубин [8] - Бездна 2 этаж
/* (800HP)
В количестве: 8 шт.
Сила: 80
Ловкость: 3
Интуиция: 3
Выносливость: 40
Интеллект: 0
Мудрость: 0
1 удар 2 блока. Профильный урон: дробящий, стихийная атака: огонь. Воскресают (примерно через час после смерти).
Обладает толстой бронёй.
 */
	$pr_use = 1;
}

/*
* Конец Бездны (Бездна)
* */

if($this->users[$this->uids[$uid1]]['bot_id'] == 126) {
###Маул Счастливчик [8]
  $pr_use = 1; 
  
  $pr_vars['priem_use'][0]['chance'] = 15;
  $pr_vars['priem_use'][0]['name'] = 'Удар Феникса';
  $pr_vars['priem_use'][0]['id'] = 216;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][1]['chance'] = 15;
  $pr_vars['priem_use'][1]['name'] = 'Слепая удача';
  $pr_vars['priem_use'][1]['id'] = 47;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 15;
  $pr_vars['priem_use'][2]['name'] = 'Активная защита';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 15;
  $pr_vars['priem_use'][3]['name'] = 'Стойкость';
  $pr_vars['priem_use'][3]['id'] = 13;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][4]['chance'] = 15;
  $pr_vars['priem_use'][4]['name'] = 'Ярость';
  $pr_vars['priem_use'][4]['id'] = 14;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 132) {
###Каменный страж [9]
###To Do : Раздавить, Сотрясение
  $pr_use = 0;

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 133) {
###Дух-Хранитель [10]
###To Do : Призрачное касание
  $pr_use = 0;

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 134) {
###Заблудшая Душа [10]
###To Do : Призрачный удар, Крик души
  $pr_use = 1;

  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = 'Приём';
  $pr_vars['priem_use'][0]['id'] = 141;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 135) {
###Механик [9]
###To Do : Починить
  $pr_use = 0;
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 136) {
###Механический Голем [9]
###To Do : Вспышка, Обнять
  $pr_use = 1;
  
  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = 'Оттеснить';
  $pr_vars['priem_use'][0]['id'] = 212;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 139) {
###Страж Сокровищ [11]
###To Do : Сотрясение, Проклятье стража, Раздавить, Сотрясение мозга
  $pr_use = 0;

}

if($this->users[$this->uids[$uid1]]['bot_id'] == 141) {
###Механический Убийца [10]
###To Do : Взрыв, Глубокая рана
  $pr_use = 0;
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 142) {
###Мастер Грит [11]
###To Do : Холодный Луч, Оюжигающее пламя, Тяжесть земли, Молния, Истинная форма, Самоуничтожение, Двойной удар, Оглушающий удар
  $pr_use = 0;
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 143) {
###Механический Охранник [10]
###To Do : Вспышка, Обнять
  $pr_use = 1;
  
  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = 'Оттеснить';
  $pr_vars['priem_use'][0]['id'] = 212;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid2]];
  
}

if($this->users[$this->uids[$uid1]]['bot_id'] == 376) {
###Древнее проклятие Глубин [9]
  $pr_use = 1; 

  $pr_vars['priem_regen']['hp'] = 18;
  $pr_vars['priem_regen']['chance'] = 7;
  $pr_vars['priem_regen']['name'] = 'Утереть пот';

  $pr_vars['priem_use'][0]['chance'] = 3;
  $pr_vars['priem_use'][0]['name'] = 'Подставить лоб';
  $pr_vars['priem_use'][0]['id'] = 45;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][1]['chance'] = 3;
  $pr_vars['priem_use'][1]['name'] = 'Раздавить';
  $pr_vars['priem_use'][1]['id'] = 216;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 3;
  $pr_vars['priem_use'][2]['name'] = 'Активная защита';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 3;
  $pr_vars['priem_use'][3]['name'] = 'Удачный удар';
  $pr_vars['priem_use'][3]['id'] = 11;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][4]['chance'] = 3;
  $pr_vars['priem_use'][4]['name'] = 'Стойкость';
  $pr_vars['priem_use'][4]['id'] = 13;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][5]['chance'] = 3;
  $pr_vars['priem_use'][5]['name'] = 'Ярость';
  $pr_vars['priem_use'][5]['id'] = 14;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];
  
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 284) {
###Жора
  $pr_use = 1; 

  $pr_vars['priem_regen']['hp'] = 18;
  $pr_vars['priem_regen']['chance'] = 25;
  $pr_vars['priem_regen']['name'] = 'Утереть пот';
  
  $pr_vars['priem_regen']['hp'] = 45;
  $pr_vars['priem_regen']['chance'] = 25;
  $pr_vars['priem_regen']['name'] = 'Воля к победе';
  
  $pr_vars['priem_use'][2]['chance'] = 10;
  $pr_vars['priem_use'][2]['name'] = 'Активная защита';
  $pr_vars['priem_use'][2]['id'] = 7;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 5;
  $pr_vars['priem_use'][3]['name'] = 'Удачный удар';
  $pr_vars['priem_use'][3]['id'] = 11;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][3]['chance'] = 30;
  $pr_vars['priem_use'][3]['name'] = 'Агрессивная Защита';
  $pr_vars['priem_use'][3]['id'] = 211;
  $pr_vars['priem_use'][3]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][4]['chance'] = 10;
  $pr_vars['priem_use'][4]['name'] = 'Стойкость';
  $pr_vars['priem_use'][4]['id'] = 13;
  $pr_vars['priem_use'][4]['on'] = $this->users[$this->uids[$uid1]];

  $pr_vars['priem_use'][5]['chance'] = 10;
  $pr_vars['priem_use'][5]['name'] = 'Ярость';
  $pr_vars['priem_use'][5]['id'] = 14;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][5]['chance'] = 10;
  $pr_vars['priem_use'][5]['name'] = 'Ярость';
  $pr_vars['priem_use'][5]['id'] = 14;
  $pr_vars['priem_use'][5]['on'] = $this->users[$this->uids[$uid1]];
  
  
}
if($this->users[$this->uids[$uid1]]['bot_id'] == 254) {
###берсерк
  $pr_use = 1; 
  
  $pr_vars['priem_regen']['hp'] = 75;
  $pr_vars['priem_regen']['chance'] = 30;
  $pr_vars['priem_regen']['name'] = 'Регенерация';
  
  $pr_vars['priem_use'][0]['chance'] = 95;
  $pr_vars['priem_use'][0]['name'] = 'Удачный удар';
  $pr_vars['priem_use'][0]['id'] = 11;
  $pr_vars['priem_use'][0]['on'] = $this->users[$this->uids[$uid1]];
 
  
  $pr_vars['priem_use'][1]['chance'] = 25;
  $pr_vars['priem_use'][1]['name'] = 'Ярость';
  $pr_vars['priem_use'][1]['id'] = 14;
  $pr_vars['priem_use'][1]['on'] = $this->users[$this->uids[$uid1]];
  
  $pr_vars['priem_use'][2]['chance'] = 60;
  $pr_vars['priem_use'][2]['name'] = 'Раздробить череп';
  $pr_vars['priem_use'][2]['id'] = 219;
  $pr_vars['priem_use'][2]['on'] = $this->users[$this->uids[$uid1]];
  
}
	
  if($pr_use > 0) {	  
  	//priem_use , priem_team_f , priem_regen БОТ ИСПОЛЬЗУЕТ ТОЛЬКО 1 ПРИЕМ
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
			  ###тестирование востановления ХП после нанесенного урона. (Востанавливает)
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
                  $pr_vars['mas']['text'] = '{tm1} {u1} использовал прием &quot;<b>'.$pr_vars['priem_team_f'][$i]['name'].'</b>&quot; и восстановил свое здоровье. <b><font color=#006699>'.$hp_vis.'</font></b> ['.$this->users[$this->uids[$uid1]]['hpNow'].'/'.$this->stats[$this->uids[$uid1]]['hpAll'].']';
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
	  $pr_vars['mas']['text'] = '{tm1} {u1} использовал прием &quot;<b>'.$pr_vars['priem_regen']['name'].'</b>&quot; и восстановил свое здоровье. <b><font color=#006699>'.$pr_vars['priem_regen']['hp'].'</font></b> ['.ceil($pr_vars['hp_u1']).'/'.$this->stats[$this->uids[$uid1]]['hpAll'].']';
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
			  $pr_vars['mas']['text'] = '{tm1} {u1} использовал прием &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot; на персонажа {u2}. <small>'.$inf.'</small>';
			} else {
			  $pr_vars['mas']['text'] = '{tm1} {u1} использовал прием &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot;  на персонажа {u2}.';
		    }
		  } else {
			if(isset($inf)) {
			  $pr_vars['mas']['text'] = '{tm1} {u1} использовал прием &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot;. <small>'.$inf.'</small>';
			} else {
			  $pr_vars['mas']['text'] = '{tm1} {u1} использовал прием &quot;<b>'.$pr_vars['priem_use'][$i]['name'].$pld[0].'</b>&quot;.';
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