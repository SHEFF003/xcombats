<?
if(!defined('GAME'))
{
	die();
}
$hpadd_pr_color = '';

if(isset($hod)) {
/*ПРИЕМЫ КАЖДЫЙ ХОД*/
	$krituetli = 0;
	
	if($pr['id']==231 && isset($hod))
	{
		//Глухая защита (восстанавливаем НР)
		$hpadd_pr = floor(round($u->stats['hpAll']/100*15)/6);
		$hpadd_pr_color = 'green';
		$trduh = 1;
		$btl->users[$btl->uids[$ue['id']]]['tactic7'] -= round((1.5/6),2);
		if($btl->users[$btl->uids[$ue['id']]]['tactic7'] < 0) {
			$btl->users[$btl->uids[$ue['id']]]['tactic7'] = 0;
		}
		mysql_query('UPDATE `stats` SET `tactic7` = "'.$btl->users[$btl->uids[$ue['id']]]['tactic7'].'" WHERE `id` = "'.$ue['id'].'" LIMIT 1');
	}
	
	if($hpadd > 0)
	{
		$pl['name'] = $pr['name'];
	}
		
/*ПРИЕМЫ КАЖДЫЙ ХОД*/
}elseif($pl['id']==231) {
	//Глухая защита
	$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,6,$u->info['id'],1,'глухаязащита',0,1);
}elseif($pl['id']==3)
{
	//прием собрать зубы
	$hpadd = rand(2,5); $trduh = 1;
}elseif($pl['id']==5)
{
	//прием утереть пот
	$hpadd = $u->info['level']*2;
}elseif($pl['id']==6)
{
	//прием воля к победе
	$hpadd = round($u->info['level']*5+7);
	if($btl->stats[$btl->uids[$u->info['id']]]['hpNow']<($btl->stats[$btl->uids[$u->info['id']]]['hpAll']*0.33))
	{
		$hpadd += ceil($hpadd*0.25);
	}
}elseif($pl['priem']['id']==189)
{
	//Ошеломить
	$imun = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u2['id'].'" and `v2`="191" and `delete`="0" LIMIT 1'));
	//echo $u2['id'];
	if($imun){
		$cup = true;
		$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'||s2='.$this->users[$this->uids[$u2['id']]]['sex'].'||t2='.$this->users[$this->uids[$u2['id']]]['team'].'||login2='.$this->users[$this->uids[$u2['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;, но у {u2} иммунитет к ошеломлению.';	
	}elseif($this->stats[$this->uids[$u2['id']]]['hpNow']>=1)	{
		//ошеломить
		$re = $priem->addPriem($u2['id'],230,'add_m10=-100|add_m11=-100',0,77,2,$u1['id'],2,'ошеломить');
		$re = $priem->addPriem($u2['id'],191,'',0,77,6,$u1['id'],5,'иммунитеткошеломить');
		if($re==false)
		{
			echo '[Er::Ошеломить[xX]]';
		}
		$sx = '';
		if($u1['sex']==1)
		{
			$sx = 'а';
		}
		$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'||s2='.$this->users[$this->uids[$u2['id']]]['sex'].'||t2='.$this->users[$this->uids[$u2['id']]]['team'].'||login2='.$this->users[$this->uids[$u2['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и ошеломил'.$sx.' {u2} на два хода.';	
		$pz = $this->users[$this->uids[$u2['id']]]['priems_z'];
		$p_id = $this->users[$this->uids[$u2['id']]]['priems'];
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
		$this->users[$this->uids[$u2['id']]]['priems_z'] = $pz;
		unset($pz);
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$this->users[$this->uids[$u2['id']]]['priems_z'].'" WHERE `id` = "'.$u2['id'].'" LIMIT 1');
		//$this->add_log($mas1);
		$pz[(int)$id] = 1;
	}
}elseif($pl['priem']['id']==235)
{
	//Шокирующий удар
	$imun = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u2['id'].'" and `v2`="191" and `delete`="0" LIMIT 1'));
	//echo $u2['id'];
	if($imun){
		$cup = true;
		$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'||s2='.$this->users[$this->uids[$u2['id']]]['sex'].'||t2='.$this->users[$this->uids[$u2['id']]]['team'].'||login2='.$this->users[$this->uids[$u2['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;, но у {u2} иммунитет к ошеломлению.';	
	}elseif($this->stats[$this->uids[$u2['id']]]['hpNow']>=1)	{
		//ошеломить
		$re = $priem->addPriem($u2['id'],236,'add_notactic=1',0,77,2,$u1['id'],2,'шокирующийудар');
		$re = $priem->addPriem($u2['id'],191,'',0,77,6,$u1['id'],5,'иммунитеткошеломить');
		if($re==false)
		{
			echo '[Er::ШокирующийУдар[xX]]';
		}
		$sx = '';
		if($u1['sex']==1)
		{
			$sx = 'а';
		}
		$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'||s2='.$this->users[$this->uids[$u2['id']]]['sex'].'||t2='.$this->users[$this->uids[$u2['id']]]['team'].'||login2='.$this->users[$this->uids[$u2['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и ошеломил'.$sx.' {u2} на два хода.';	
		$pz = $this->users[$this->uids[$u2['id']]]['priems_z'];
		$p_id = $this->users[$this->uids[$u2['id']]]['priems'];
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
		$this->users[$this->uids[$u2['id']]]['priems_z'] = $pz;
		unset($pz);
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$this->users[$this->uids[$u2['id']]]['priems_z'].'" WHERE `id` = "'.$u2['id'].'" LIMIT 1');
		//$this->add_log($mas1);
		$pz[(int)$id] = 1;
	}
}elseif($pl['priem']['id']==237)
{
	//Разведка боем
		$imun = mysql_fetch_array(mysql_query('SELECT `id` FROM `eff_users` WHERE `uid` = "'.$u2['id'].'" and `v2`="237" and `delete`="0" LIMIT 1'));
		if(isset($imun['id'])) {
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$imun['id'].'" LIMIT 1');
		}
		//Разведка боем
		$re = $priem->addPriem($u2['id'],238,'add_notactic=1',0,77,4,$u1['id'],5,'разведкабоем');
		if($re==false)
		{
			echo '[Er::РазведкаБоем[xX]]';
		}
		$sx = '';
		if($u1['sex']==1)
		{
			$sx = 'а';
		}
		$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'||s2='.$this->users[$this->uids[$u2['id']]]['sex'].'||t2='.$this->users[$this->uids[$u2['id']]]['team'].'||login2='.$this->users[$this->uids[$u2['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и раскрыл'.$sx.' тактику {u2} на пять ходов.';	
}elseif($pl['priem']['id']==239)
{
	//Поступь смерти
	$pl['data_re'] = $u->lookStats($pl['data']);
	if($pl['data_re']['step'] < 10) {
		$pl['data_re']['add_maxAtack'] += $this->users[$this->uids[$pl['uid']]]['level'];
		$pl['data_re']['step']++;
	}
	$pl['data'] = 'add_maxAtack='.$pl['data_re']['add_maxAtack'].'|step='.$pl['data_re']['step'].'';
	$pl['hod'] = 2;
	$this->rehodeff[$pl['id']] = $pl['hod'];
	mysql_query('UPDATE `eff_users` SET `hod` = "'.$pl['hod'].'",`data` = "'.$pl['data'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	
	unset($pl['data_re']);
	
}elseif($pl['priem']['id']==240)
{
	//Хлебнуть Крови
	$pl['data_re'] = $u->lookStats($pl['data']);
	if(isset($pl['data_re']['step']) || $pl['data_re']['step'] == 0) {
		//Добавляем силу
		if($this->users[$this->uids[$u2['id']]]['level'] == 7) {
			$pl['data_re']['add_s1'] = 10;
		}elseif($this->users[$this->uids[$u2['id']]]['level'] == 8) {
			$pl['data_re']['add_s1'] = 13;
		}elseif($this->users[$this->uids[$u2['id']]]['level'] >= 9) {
			$pl['data_re']['add_s1'] = 14;
		}
	}	
	$pl['data_re']['step']++;	
	if($pl['data_re']['add_s1'] > 0) {
		$pl['data'] = 'add_s1='.$pl['data_re']['add_s1'].'|atck_krit_to_atck=1|step='.$pl['data_re']['step'].'';
	}else{
		$pl['data'] = 'atck_krit_to_atck=1|step='.$pl['data_re']['step'];
	}
	
	if($pl['hod'] == -1) {
		$pl['hod'] = 4;
		$this->rehodeff[$pl['id']] = $pl['hod'];
		//Хиляемся 
		$hpadd_pl = $yrn*0.679;
		$pl['data_re']['step']++;
	}elseif($pl['data_re']['step'] == 2 || $pl['data_re']['step'] == 3) {
		//Хиляемся еще 2 хода от любых ударов
		$hpadd_pl = $yrn*0.573;
		$pl['data_re']['step']++;
	}else{
		//$hpadd_pl = $yrn;
	}
	
	if($hpadd_pl > 0) {
		if($this->users[$this->uids[$u2['id']]]['level'] <= 8) {
			if($hpadd_pl > 107) {
				$hpadd_pl = 107;
			}
		}elseif($this->users[$this->uids[$u2['id']]]['level'] == 9) {
			if($hpadd_pl > 128) {
				$hpadd_pl = 128;
			}
		}elseif($this->users[$this->uids[$u2['id']]]['level'] >= 10) {
			if($hpadd_pl > 154) {
				$hpadd_pl = 154;
			}
		}
		$plname = $pl['name'];
		$hid = $u1['id'];
	}
	
	$this->pr_reset['data'][$pl['id']] = $pl['data'];
	
	mysql_query('UPDATE `eff_users` SET `hod` = "'.$pl['hod'].'",`data` = "'.$pl['data'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	unset($pl['data_re']);
	
}elseif($pl['id']==192){

		$i=0;
		$add_where='';
		while($i<count($btl->users)){
			if($btl->users[$i]['team']==$btl->users[$btl->uids[$u->info['id']]]['team']){
				$add_where.=' AND `user_use`!="'.$btl->users[$i]['id'].'"';
			}
			$i++;
		}

		//Ограничиваем конкретными приемами
		/*
		приёмом "Очиститься кровью" можно снять: пожирающее пламя, переохлаждение, ядовитое облако, кристаллизация, отравление, цель огня, цель воды, цель воздуха и цель земли. 
		*/
		$add_where .= ' AND (`name` LIKE "Цель Воды%" OR `name` LIKE "Цель Огня%" OR `name` LIKE "Цель Воздуха%" OR `name` LIKE "Цель Земли%" 
OR `name` LIKE "Пожирающее Пламя%" OR `name` LIKE "Переохлаждение%" OR `name` LIKE "Ядовитое Облако%" OR `name` LIKE "Кристаллизация%" OR `name` LIKE "%Отравление%" OR `name` LIKE "Искалечить%") ';

        $dell = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `user_use`!= "" and `delete`="0" and `uid`="'.$u->info['id'].'" and `v1`="priem" '.$add_where.'  LIMIT 1'));
		
		if($dell){
			$dell['priem']['id']=$dell['id'];
		if($dell['x']==1){
			$btl->delPriem($dell,$u->info,99);
		}else{
			$i=0;
			$e = explode('|',$dell['data']);
			while($i<count($e)){
			    $f = explode('=',$e[$i]);
			    $stack=$f[1]/$dell['x'];//вычисляем влятельность заряда на х-ки 
			    $f[1]-=$stack;// отнимаем заряд
				$e[$i] = implode('=',$f);
				$i++;
			}
			$dell['data'] = implode('|',$e);
			$dell['x']--;
			
			mysql_query('UPDATE `eff_users` SET `data` = "'.$dell['data'].'", `x`="'.$dell['x'].'"  WHERE `id` = "'.$dell['id'].'"');
				$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
				$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				$mas1['text'] = '{u1} Ослабил эфект &quot;<b>'.$dell['name'].'</b>&quot; с помощью <b>Очиститься Кровью</b> .';
				$btl->add_log($mas1);
			}
  	    }
}
if(isset($hpadd))
{
	if($u->stats['hpNow']+$hpadd > $u->stats['hpAll'])
	{
		$hpadd = $u->stats['hpAll']-$u->stats['hpNow'];
	}
	if($trduh==1)
	{
		if($btl->users[$btl->uids[$u->info['id']]]['tactic7']<=0)
		{
			$hpadd = 0;
		}
	}
	
	if(isset($btl->stats[$btl->uids[$u->info['id']]]['min_heal_proc'])) {
		if($btl->stats[$btl->uids[$u->info['id']]]['min_heal_proc'] > 100) {
			$btl->stats[$btl->uids[$u->info['id']]]['min_heal_proc'] = 100;
		}
		$hpadd = round($hpadd/100*(100+$btl->stats[$btl->uids[$u->info['id']]]['min_heal_proc']));
	}
	
	$u->info['hpNow'] += $hpadd;
	$u->stats['hpNow'] += $hpadd;
	$btl->users[$btl->uids[$u->info['id']]]['hpNow'] += $hpadd;
	$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] += $hpadd;
	$upd = mysql_query('UPDATE `stats` SET `hpNow` = '.$u->info['hpNow'].' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	if($upd)
	{
		$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		if($hpadd>0)
		{
			$hpadd = '+'.ceil($hpadd);
		}else{
			$hpadd = '--';
		}
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и {1x17x0} здоровье. <b><font color=#006699>'.$hpadd.'</font></b> ['.ceil($u->info['hpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['hpAll'].']';	
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;
	}else{
		echo 'Can`t update table `user`';
	}
	unset($hpadd,$mas1,$trduh);
}elseif(isset($hpadd_pr))
{
	if($btl->stats[$btl->uids[$ue['id']]]['hpNow']+$hpadd_pr > $btl->stats[$btl->uids[$ue['id']]]['hpAll'])
	{
		$hpadd_pr = $btl->stats[$btl->uids[$ue['id']]]['hpAll']-$btl->stats[$btl->uids[$ue['id']]]['hpNow'];
	}
	if($trduh==1)
	{
		if($btl->users[$btl->uids[$ue['id']]]['tactic7']<=0)
		{
			$hpadd_pr = 0;
		}
	}
	
	if(isset($btl->stats[$btl->uids[$ue['id']]]['min_heal_proc'])) {
		if($btl->stats[$btl->uids[$ue['id']]]['min_heal_proc'] > 100) {
			$btl->stats[$btl->uids[$ue['id']]]['min_heal_proc'] = 100;
		}
		$hpadd_pr = round($hpadd_pr/100*(100+$btl->stats[$btl->uids[$ue['id']]]['min_heal_proc']));
	}
	
	if($u->info['id'] == $btl->users[$btl->uids[$ue['id']]]['id']) {
		$u->info['hpNow'] += $hpadd;
		$u->stats['hpNow'] += $hpadd;
	}
	$btl->users[$btl->uids[$ue['id']]]['hpNow'] += $hpadd_pr;
	$btl->stats[$btl->uids[$ue['id']]]['hpNow'] += $hpadd_pr;
	$upd = mysql_query('UPDATE `stats` SET `hpNow` = '.$btl->stats[$btl->uids[$ue['id']]]['hpNow'].' WHERE `id` = "'.$btl->users[$btl->uids[$ue['id']]]['id'].'" LIMIT 1');
	if($upd)
	{
		$vLog = 'time1='.time().'||s1='.$btl->users[$btl->uids[$ue['id']]]['sex'].'||t1='.$btl->users[$btl->uids[$ue['id']]]['team'].'||login1='.$btl->users[$btl->uids[$ue['id']]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		if($hpadd_pr>0)
		{
			$hpadd_pr = '+'.ceil($hpadd_pr);
		}else{
			$hpadd_pr = '--';
		}
		if($hpadd_pr_color == '') {
			$hpaa_pr_color = '#006699';
		}
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pr['name'].'</b>&quot; и {1x17x0} здоровье. <b><font color='.$hpaa_pr_color.'>'.$hpadd_pr.'</font></b> ['.ceil($btl->stats[$btl->uids[$ue['id']]]['hpNow']).'/'.$btl->stats[$btl->uids[$ue['id']]]['hpAll'].']';	
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;
	}else{
		echo 'Can`t update table `user`';
	}
	unset($hpadd_pr,$mas1,$trduh);
}elseif(isset($hpadd_pl))
{
	if($this->stats[$this->uids[$hid]]['hpNow']+$hpadd_pl > $this->stats[$this->uids[$hid]]['hpAll'])
	{
		$hpadd_pr = $this->stats[$this->uids[$hid]]['hpAll']-$this->stats[$this->uids[$hid]]['hpNow'];
	}
	if($trduh==1)
	{
		if($this->users[$this->uids[$hid]]['tactic7']<=0)
		{
			$hpadd_pl = 0;
		}
	}
	
	if(isset($this->stats[$this->uids[$hid]]['min_heal_proc'])) {
		if($this->stats[$this->uids[$hid]]['min_heal_proc'] > 100) {
			$this->stats[$this->uids[$hid]]['min_heal_proc'] = 100;
		}
		$hpadd_pl = round($hpadd_pl/100*(100+$this->stats[$this->uids[$hid]]['min_heal_proc']));
	}
	
	if($u->info['id'] == $this->users[$this->uids[$hid]]['id']) {
		$u->info['hpNow'] += $hpadd_pl;
		$u->stats['hpNow'] += $hpadd_pl;
	}
	$this->users[$this->uids[$hid]]['hpNow'] += $hpadd_pl;
	$this->stats[$this->uids[$hid]]['hpNow'] += $hpadd_pl;
	$upd = mysql_query('UPDATE `stats` SET `hpNow` = '.$this->stats[$this->uids[$hid]]['hpNow'].' WHERE `id` = "'.$this->users[$this->uids[$hid]]['id'].'" LIMIT 1');
	if($upd)
	{
		$vLog = 'time1='.time().'||s1='.$this->users[$this->uids[$hid]]['sex'].'||t1='.$this->users[$this->uids[$hid]]['team'].'||login1='.$this->users[$this->uids[$hid]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		if($hpadd_pl>0)
		{
			$hpadd_pl = '+'.ceil($hpadd_pl);
		}else{
			$hpadd_pl = '--';
		}
		if($hpadd_pr_color == '') {
			$hpaa_pr_color = '#006699';
		}
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$plname.'</b>&quot; и {1x17x0} здоровье. <b><font color='.$hpaa_pr_color.'>'.$hpadd_pl.'</font></b> ['.ceil($this->stats[$this->uids[$hid]]['hpNow']).'/'.$this->stats[$this->uids[$hid]]['hpAll'].']';	
		$this->add_log($mas1);
		$pz[(int)$id] = 1;
	}else{
		echo 'Can`t update table `user`';
	}
	unset($hpadd_pl,$mas1,$trduh);
}
?>