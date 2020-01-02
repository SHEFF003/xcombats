<?
if(!defined('GAME'))
{
	die();
}

if($pl['id'] == 189) {
	$imun = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['enemy'].'" and `v2`="191" and `delete`="0" LIMIT 1'));
	//Ошеломить
	if($imun){
		echo '<font color=red><b>У персонажа иммунитет к ошеломляющим приемам еше '.$imun['hod'].' ход.</b></font>';
		$cup = true;
	}elseif($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']>=1)
	{
		//
		$re = $this->addPriem($u->info['enemy'],230,'',0,77,2,$u->info['id'],2,'ошеломить');
		$re = $this->addPriem($u->info['enemy'],191,'',0,77,6,$u->info['id'],5,'иммунитеткошеломить');
		if($re==false)
		{
			echo '[Er::Ошеломить[xX]]';
		}
		$sx = '';
		if($u->info['sex']==1)
		{
			$sx = 'а';
		}
		$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		
		/*$hp = $u->info['level']*2-$u->info['level'];
		
		if($hp < 0) {
			$hp = 0;
		}*/
		
		/*$btl->users[$btl->uids[$u->info['enemy']]]['hpNow'] -= $hp;
		$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] -= $hp;
		$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'] = -$hp;
		mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'].'",`last_hp` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
		
		if($hp < 1) {
			$hp = '--';
		}else{
			$hp = -$hp;
		}*/
		
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и ошеломил'.$sx.' персонажа {u2} на два хода.';	
		$pz = $btl->users[$btl->uids[$u->info['enemy']]]['priems_z'];
		$p_id = $btl->users[$btl->uids[$u->info['enemy']]]['priems'];
		$pz = explode('|',$pz);
		$p_id = explode('|',$p_id);
		$i = 0;
		while($i<=30)
		{
		  if($p_id[$i]>=195 and $p_id[$i]<=198){
				$pz[$i]=$pz[$i];
			}else{
				$pz[$i] += 2;
			}
		  $i++;
		}
		$pz = implode('|',$pz);
		$btl->users[$btl->uids[$u->info['enemy']]]['priems_z'] = $pz;
		unset($pz);
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['priems_z'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;
	}
}elseif($pl['id'] == 227) {
	$imun = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['enemy'].'" and `v2`="191" and `delete`="0" LIMIT 1'));
	//Контузия
	if($imun){
		echo '<font color=red><b>У персонажа иммунитет к ошеломляющим приемам еше '.$imun['hod'].' ход.</b></font>';
		$cup = true;
	}elseif($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']>=1)
	{
		//
		$re = $this->addPriem($u->info['enemy'],$pl['id'],'',0,77,4,$u->info['id'],1,'контузия');
		$re = $this->addPriem($u->info['enemy'],191,'',0,77,6,$u->info['id'],5,'иммунитеткошеломить');
		if($re==false)
		{
			echo '[Er::Контузия[xX]]';
		}
		$sx = '';
		if($u->info['sex']==1)
		{
			$sx = 'а';
		}
		$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		
		$hp = $u->info['level']*2-$u->info['level'];
		
		if($hp < 0) {
			$hp = 0;
		}
		
		$btl->users[$btl->uids[$u->info['enemy']]]['hpNow'] -= $hp;
		$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] -= $hp;
		$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'] = -$hp;
		mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'].'",`last_hp` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
		
		if($hp < 1) {
			$hp = '--';
		}else{
			$hp = -$hp;
		}
		
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и ошеломил'.$sx.' щитом персонажа {u2} на один ход. <b><font color=#006699>'.$hp.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']).'/'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpAll'].']';	
		$pz = $btl->users[$btl->uids[$u->info['enemy']]]['priems_z'];
		$p_id = $btl->users[$btl->uids[$u->info['enemy']]]['priems'];
		$pz = explode('|',$pz);
		$p_id = explode('|',$p_id);
		$i = 0;
		while($i<=30)
		{
		  if($p_id[$i]>=195 and $p_id[$i]<=198){
				$pz[$i]=$pz[$i];
			}else{
				$pz[$i] += 2;
			}
		  $i++;
		}
		$pz = implode('|',$pz);
		$btl->users[$btl->uids[$u->info['enemy']]]['priems_z'] = $pz;
		unset($pz);
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['priems_z'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;
	}
}elseif($pl['priem']['id'] == 226) {
	//Возмездие
	$hp = 6*$u1['level'];
	if($hp > 0) {			
		$this->users[$this->uids[$u2['id']]]['last_hp'] = -$hp;
		$this->stats[$this->uids[$u2['id']]]['hpNow'] -= $hp;
		$s2['hpNow'] = $this->stats[$this->uids[$u2['id']]]['hpNow'];
		$p2['hpNow'] = $this->stats[$this->uids[$u2['id']]]['hpNow'];
		$this->users[$this->uids[$u2['id']]]['hpNow'] = $this->stats[$this->uids[$u2['id']]]['hpNow'];
		mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$this->uids[$u2['id']]]['hpNow'].'",`last_hp` = "'.$this->users[$this->uids[$u2['id']]]['last_hp'].'" WHERE `id` = "'.$u2['id'].'" LIMIT 1');
		$this->stats[$this->uids[$u2['uid']]] = $u->getStats($u2['uid'],0);
		$hp = -$hp;
	}else{
		$hp = '--';
	}
	$mas1['text'] = '{tm1} {u2} утратил здоровье от приема &quot;<b>'.$pl['name'].'</b>&quot;. <b><font color=#006699>'.$hp.'</font></b> ['.floor($this->stats[$this->uids[$u2['id']]]['hpNow']).'/'.$this->stats[$this->uids[$u2['id']]]['hpAll'].']';	
}elseif($pl['id'] == 224) {
	if($btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] > 0) {
		$btl->stats[$btl->uids[$this->ue['id']]]['tactic6']++;
		$btl->users[$btl->uids[$this->ue['id']]]['tactic6']++;
		if($btl->users[$btl->uids[$this->ue['id']]]['id'] == $u->info['id']) {
			$u->info['tactic6']++;
		}
		mysql_query('UPDATE `stats` SET `tactic6` = "'.$btl->users[$btl->uids[$this->ue['id']]]['tactic6'].'" WHERE `id` = "'.$this->ue['id'].'" LIMIT 1');
		$vLog = 'time1='.time().'||s1='.$btl->users[$btl->uids[$u->info['id']]]['sex'].'||t1='.$btl->users[$btl->uids[$u->info['id']]]['team'].'||login1='.$btl->users[$btl->uids[$u->info['id']]]['login'].'||s2='.$btl->users[$btl->uids[$this->ue['id']]]['sex'].'||t2='.$btl->users[$btl->uids[$this->ue['id']]]['team'].'||login2='.$btl->users[$btl->uids[$this->ue['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; на персонажа {u2}.';	
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;
		unset($vLog,$mas1);
	}
}elseif($pl['priem']['id'] == 222) {
	//Последний удар
	if(!isset($this->del_val['eff'][$pl['priem']['id']])) {
		$hp = floor($this->stats[$this->uids[$pl['uid']]]['hpNow']);
		$this->stats[$this->uids[$pl['uid']]]['last_hp'] = -$hp;
		
		if($hp < 1) {
			$hp = '--';
		}else{
			$hp = -$hp;
		}
		
		$this->del_val['eff'][$pl['id']] = true;	
		$vLog = 'time1='.time().'||s1='.$this->users[$this->uids[$pl['uid']]]['sex'].'||t1='.$this->users[$this->uids[$pl['uid']]]['team'].'||login1='.$this->users[$this->uids[$pl['uid']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;.'; // <b><font color=#006699>'.$hp.'</font></b> [0/'.$this->stats[$this->uids[$u1['id']]]['hpAll'].']';	
		$this->add_log($mas1);
		$pz[(int)$id] = 1;
		unset($vLog,$mas1);
	}
}elseif($pl['id'] == 232) {
	//Выжить
		$hp = $btl->users[$btl->uids[$u->info['id']]]['tactic1']+$btl->users[$btl->uids[$u->info['id']]]['tactic2']+$btl->users[$btl->uids[$u->info['id']]]['tactic3']+$btl->users[$btl->uids[$u->info['id']]]['tactic4']+$btl->users[$btl->uids[$u->info['id']]]['tactic5']+$btl->users[$btl->uids[$u->info['id']]]['tactic6']*0.5;
		if($hp > 25) {
			$hp = 25;
		}
		$hp = floor($btl->stats[$btl->uids[$u->info['id']]]['hpAll']/100*$hp);
		
		if(floor($btl->stats[$btl->uids[$u->info['id']]]['hpAll']-$btl->stats[$btl->uids[$u->info['id']]]['hpNow']) < $hp) {
			$hp = floor($btl->stats[$btl->uids[$u->info['id']]]['hpAll']-$btl->stats[$btl->uids[$u->info['id']]]['hpNow']);
		}
		
		$i03 = 1;
		while($i03 <= 6) {
			$btl->users[$btl->uids[$u->info['id']]]['tactic'.$i03] = 0;
			$btl->stats[$btl->uids[$u->info['id']]]['tactic'.$i03] = 0;
			$u->info['tactic'.$i03] = 0;
			$u->stats['tactic'.$i03] = 0;
			$rstb = 1;
			$i03++;
		}
		unset($i03);
		
		$hp = -$hp;
		
		$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] -= $hp;
		
		if($btl->stats[$btl->uids[$u->info['id']]]['hpNow'] < 0)
		{
			$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = 0;
		}elseif($btl->stats[$btl->uids[$u->info['id']]]['hpNow']>$btl->stats[$btl->uids[$u->info['id']]]['hpAll'])
		{
			$hp = ceil($hp-($btl->stats[$btl->uids[$u->info['id']]]['hpNow']-$btl->stats[$btl->uids[$u->info['id']]]['hpAll']));
			$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpAll'];
		}
		
		if($hp < 0)
		{
			$hp = '+'.(-$hp);
		}elseif($hp == 0){
			$hp = '--';
		}else{
			$hp = '-'.$hp;
		}
		
		$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;. <b><font color=#006699>'.$hp.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['hpAll'].']';	
		$btl->add_log($mas1);		
		
		$btl->users[$btl->uids[$u->info['id']]]['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpNow'];	
		$u->info['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpNow'];
		
		mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['id']]]['hpNow'].'",`tactic1` ="0",`tactic2` ="0",`tactic3` ="0",`tactic4` ="0",`tactic5` ="0",`tactic6` ="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		
		unset($hp021);
}elseif($pl['id'] == 221) {
	//Отменить
		$hp = $btl->users[$btl->uids[$u->info['id']]]['last_hp'];

		$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] -= $hp;
		
		if($btl->stats[$btl->uids[$u->info['id']]]['hpNow'] < 0)
		{
			$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = 0;
		}elseif($btl->stats[$btl->uids[$u->info['id']]]['hpNow']>$btl->stats[$btl->uids[$u->info['id']]]['hpAll'])
		{
			$hp = ceil($hp-($btl->stats[$btl->uids[$u->info['id']]]['hpNow']-$btl->stats[$btl->uids[$u->info['id']]]['hpAll']));
			$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpAll'];
		}
		
		$btl->users[$btl->uids[$u->info['id']]]['last_hp'] = 0;
		
		if($hp < 0)
		{
			$hp = '+'.(-$hp);
		}elseif($hp == 0){
			$hp = '--';
		}else{
			$hp = '-'.$hp;
		}
		
		$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;. <b><font color=#006699>'.$hp.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['hpAll'].']';	
		$btl->add_log($mas1);		
		
		$btl->users[$btl->uids[$u->info['id']]]['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpNow'];	
		$u->info['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpNow'];
		
		mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['id']]]['hpNow'].'",`last_hp` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		
		unset($hp021);
}elseif($pl['priem']['id'] == 220) {
	//Ставка на опережение
	if(!isset($this->del_val['eff'][$pl['id']])) {
		$this->del_val['eff'][$pl['id']] = true;	
		$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'||s2='.$u2['sex'].'||t2='.$u2['team'].'||login2='.$u2['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; на персонажа {u2} и украл все активные приемы.';	
		$this->add_log($mas1);
		$pz[(int)$id] = 1;	
		$pl['delete'] = time();
		$sp031 = mysql_query('SELECT `id`,`name` FROM `eff_users` WHERE `uid` = "'.$u2['id'].'" AND `delete` = 0 AND `v1` = "priem" LIMIT 20');
		$pr78 = 0;
		while($pl031 = mysql_fetch_array($sp031)) {
			if(mysql_query('UPDATE `eff_users` SET `uid` = "'.$u1['id'].'" WHERE `id` = "'.$pl031['id'].'" LIMIT 1')) {
				//$mas1['text'] = '{tm1} {u1} Украл активный прием &quot;<b>'.$pl031['name'].'</b>&quot; у {u2}';	
				//$this->add_log($mas1);
				$pr78++;
			}
		}
		if($pr78 > 0) {
			$this->stats[$this->uids[$u1['id']]] = $u->getStats($u1['id'],0);
			$this->stats[$this->uids[$u2['id']]] = $u->getStats($u2['id'],0);
			$this->re_pd['restart'] = true;
		}
		unset($pr78,$vLog,$mas1,$pl031,$sp031);
	}
}elseif($pl['priem']['id'] == 217) {
	//разгадать тактику
	if(!isset($this->del_val['eff'][$pl['id']])) {
		$this->del_val['eff'][$pl['id']] = true;	
		$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'||s2='.$u2['sex'].'||t2='.$u2['team'].'||login2='.$u2['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; на персонажа {u2}.';	
		$this->add_log($mas1);
		$pz[(int)$id] = 1;	
		$pl['delete'] = time();
		$sp031 = mysql_query('SELECT `id`,`name` FROM `eff_users` WHERE `uid` = "'.$u2['id'].'" AND `delete` = 0 AND `v1` = "priem" AND `v2` != 201 AND `v2` != 211 LIMIT 20');
		$pr78 = 0;
		while($pl031 = mysql_fetch_array($sp031)) {
			if(mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'",`uid` = "0" WHERE `id` = "'.$pl031['id'].'" LIMIT 1')) {
				$mas1['text'] = '{tm1} Закончилось действие приема &quot;<b>'.$pl031['name'].'</b>&quot; для {u2}.';	
				$this->pr_not_use[$pl031['id']] = 1;
				$this->add_log($mas1);
				$pr78++;
			}
		}
		/*if($pr78 > 0) {
			$this->stats[$this->uids[$u1['id']]] = $u->getStats($u1['id'],0);
			$this->stats[$this->uids[$u2['id']]] = $u->getStats($u2['id'],0);
			$this->re_pd['restart'] = true;
		}*/
		unset($pr78,$vLog,$mas1,$pl031,$sp031);
	}
}elseif($pl['id']==12)
{
	//прием подлый удар
	$hpmin = $u->info['level']*5;
}elseif($pl['id']==271)
{
	//прием Прорыв 1\3 урона оружием
	$tp_atk = $btl->weaponTx($btl->stats[$btl->uids[$u->info['id']]]['items'][$btl->stats[$btl->uids[$u->info['id']]]['wp3id']]);
	//$tp_atk = 0;
	$yi_atk = $btl->weaponAt22($btl->stats[$btl->uids[$u->info['id']]]['items'][$btl->stats[$btl->uids[$u->info['id']]]['wp3id']],$btl->stats[$btl->uids[$u->info['id']]],$tp_atk);
	$hpmin = $btl->yrn($btl->stats[$btl->uids[$u->info['id']]],$btl->stats[$btl->uids[$u->info['enemy']]],$btl->users[$btl->uids[$u->info['id']]],$btl->users[$btl->uids[$u->info['enemy']]],$btl->stats[$btl->uids[$u->info['id']]]['lvl'],$btl->stats[$btl->uids[$u->info['enemy']]]['lvl'],$tp_atk,$yi_atk[0],$yi_atk[1],0,0,0,0,$btl->stats[$btl->uids[$u->info['id']]]['m3'],0,0,0);
	$hpmin = rand($hpmin['min'],$hpmin['max']);
}elseif($pl['id'] == 212) {
	//Ограниченный маневр
	$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
	$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
	$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; на персонажа {u2}';	
	$btl->add_log($mas1);
	$pz[(int)$id] = 1;	
	mysql_query('UPDATE `stats` SET `smena` = "-1" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
}elseif($pl['id']==46)
{
	//заносим в лог боя
	if(isset($this->ue['id'],$btl->users[$btl->uids[$this->ue['id']]]) && ($btl->users[$btl->uids[$this->ue['id']]]['team']!=$u->info['team'] && $btl->stats[$btl->uids[$this->ue['id']]]['hpNow']>=1))
	{
		$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$this->ue['id']]]['sex'].'||t2='.$btl->users[$btl->uids[$this->ue['id']]]['team'].'||login2='.$btl->users[$btl->uids[$this->ue['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;. (Новая цель: {u2})';	
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;	
		$u->info['enemy'] = $this->ue['id'];
		mysql_query('UPDATE `stats` SET `enemy` = "'.$u->info['enemy'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
}elseif($pl['priem']['id']==49)
{
	//восстанавливаем 5НР за каждый лвл противника
	$this->stats[$this->uids[$pl['uid']]]['hpNow'] += $this->users[$this->uids[$pl['uid']]]['level']*5;
	$hp = $u2['level']*5;
	if($this->stats[$this->uids[$pl['uid']]]['hpNow'] < 0)
	{
		$this->stats[$this->uids[$pl['uid']]]['hpNow'] = 0;
	}elseif($this->stats[$this->uids[$pl['uid']]]['hpNow']>$this->stats[$this->uids[$pl['uid']]]['hpAll'])
	{
		$hp = ceil($hp-($this->stats[$this->uids[$pl['uid']]]['hpNow']-$this->stats[$this->uids[$pl['uid']]]['hpAll']));
		$this->stats[$this->uids[$pl['uid']]]['hpNow'] = $this->stats[$this->uids[$pl['uid']]]['hpAll'];
	}
	
	if($hp > 0) {
		$btl->users[$btl->uids[$pl['uid']]]['last_hp'] = $hp;
	}
	
	if($hp>0)
	{
		$hp = '+'.$hp;
	}else{
		$hp = '--';
	}
	
	$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;. <b><font color=#006699>'.$hp.'</font></b> ['.floor($this->stats[$this->uids[$pl['uid']]]['hpNow']).'/'.$this->stats[$this->uids[$pl['uid']]]['hpAll'].']';	
	
	$this->users[$this->uids[$pl['uid']]]['hpNow'] = $this->stats[$this->uids[$pl['uid']]]['hpNow'];
	if($pl['uid']==$u->info['id'])
	{
		$u->info['hpNow'] = $this->stats[$this->uids[$pl['uid']]]['hpNow'];
	}
	mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$this->uids[$pl['uid']]]['hpNow'].'",`last_hp` = "'.$btl->users[$btl->uids[$pl['uid']]]['last_hp'].'" WHERE `id` = "'.$pl['uid'].'" LIMIT 1');
}elseif($pl['priem']['id'] == 211) {
	//Агрессивная защита
	$hp = 3*$u1['level'];
	if($hp > 0) {			
		$this->users[$this->uids[$u2['id']]]['last_hp'] = -$hp;
		$this->stats[$this->uids[$u2['id']]]['hpNow'] -= $hp;
		$s2['hpNow'] = $this->stats[$this->uids[$u2['id']]]['hpNow'];
		$p2['hpNow'] = $this->stats[$this->uids[$u2['id']]]['hpNow'];
		$this->users[$this->uids[$u2['id']]]['hpNow'] = $this->stats[$this->uids[$u2['id']]]['hpNow'];
		mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$this->uids[$u2['id']]]['hpNow'].'",`last_hp` = "'.$this->users[$this->uids[$u2['id']]]['last_hp'].'" WHERE `id` = "'.$u2['id'].'" LIMIT 1');
		$this->stats[$this->uids[$u2['uid']]] = $u->getStats($u2['uid'],0);
		$hp = -$hp;
	}else{
		$hp = '--';
	}
	$mas1['text'] = '{tm1} {u2} утратил здоровье от приема &quot;<b>'.$pl['name'].'</b>&quot;. <b><font color=#006699>'.$hp.'</font></b> ['.floor($this->stats[$this->uids[$u2['id']]]['hpNow']).'/'.$this->stats[$this->uids[$u2['id']]]['hpAll'].']';	
}

//отнимаем НР у противника
if(isset($hpmin) && $hpmin>0 && $u->info['enemy']>0)
{
	$hp2 = floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] - $hpmin);
	
	/* проверяем приемы защиты */
		//получаем массив с приемами противника
		$miny = 0; //на сколько едениц урон буде меньше (защита приема)
		$minu = 0;
		$sp1 = mysql_query('SELECT `e`.* FROM `eff_users` AS `e` WHERE `e`.`uid` = "'.$u->info['enemy'].'" AND `e`.`id_eff` = "22" AND `e`.`delete` = "0" AND `e`.`v1` = "priem" LIMIT 25');
		while($pl2 = mysql_fetch_array($sp1))
		{
			$pl2['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pl2['v2'].'" LIMIT 1'));
			if(isset($pl2['priem']['id']))
			{
				$dt1 = $u->lookStats($pl2['priem']['date2']);
				if(isset($dt1['yron_u2']))
				{
					$minu = getdr($dt1['yron_u2'],array(0=>'lvl1',1=>'yr1'),array(0=>$u->info['level'],1=>$hpmin));
					$miny -= $minu;
					$hpmin += $minu;
					$btl->delPriem($pl2,$btl->users[$btl->uids[$u->info['enemy']]]);	
				}
			}
			
		}
				
	/* проверяем приемы ослабления */
	
	//отнимаем НР
	if($hpmin > 0) {
		$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'] = -$hpmin;
	}
	$btl->users[$btl->uids[$u->info['enemy']]]['hpNow'] = $hp2;
	$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] = $hp2;
	$upd = mysql_query('UPDATE `stats` SET `hpNow` = '.$hp2.',`last_hp` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['last_hp'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
	
	//заносим в лог боя
	$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
	$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
	if($hpmin>0)
	{
		$hpmin = '-'.$hpmin;
	}else{
		$hpmin = '--';
	}
	$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и {1x5x1} по {u2}. <b><font color=#006699>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpAll'].']';	
	$btl->add_log($mas1);
	$pz[(int)$id] = 1;	
}
?>