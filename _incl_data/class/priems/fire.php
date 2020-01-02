<?
if(!defined('GAME'))
{
	die();
}
$krituetli=true;
$dopyrn = 0;
if(isset($hod))
{
	if($pr['id']==29)
	{
		//Знак огня
		$minmp = $u->info['level'];		
	}elseif($pr['id']==33)
	{
		//Пожирающее пламя [6]
		$krituetli=false;
		$hpmin = 8;
	}elseif($pr['id']==56)
	{
		//Пожирающее пламя [7]
		$krituetli=false;
		$hpmin = 10;
	}elseif($pr['id']==57)
	{
		//Пожирающее пламя [8]
		$krituetli=false;
		$hpmin = 12;
	}elseif($pr['id']==58)
	{
		//Пожирающее пламя [9]
		$krituetli=false;
		$hpmin = 14;
	}elseif($pr['id']==59)
	{
		//Пожирающее пламя [10]
		$krituetli=false;
		$hpmin = 17;
	}elseif($pr['id']==60)
	{
		//Пожирающее пламя [11]
		$krituetli=false;
		$hpmin = 20;
	}
	
	if($hpmin>0)
	{
		$re = $this->magicAtack($ue,$hpmin,1,$pr,$eff,1,0,0,0,$krituetli);
	}
	if(isset($minmp))
	{
		//отнимаем МР у кастера
		if($this->minMana($eff['user_use'],$minmp,1)==false)
		{
			//снимаем эффект, мана закончилась
			$btl->delPriem($eff,$btl->users[$btl->uids[$eff['uid']]],2);	
		}
	}
}else{
	$uen = $u->info['enemy']; //на кого используем прием
	
	if(isset($_POST['useon']))
	{
		$ue = $this->ue;
		if(isset($ue['id']))
		{
			$uen = $ue['id'];
		}else{
			$uen = 0;	
		}
	}

	if($pl['priem']['id'] == 245) {
	//Огненный щит
		/*$hp = 6*$u1['level'];
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
		*/
		$mp = round($yrn/2);
		if($mp < 0) {
			$mp = 0;
		}
		
		
		
		if($mp == 0) {
			$mp = '--';
		}else{
			$mp = '+'.$mp;
		}
		$this->stats[$this->uids[$u1['id']]]['mpNow'] += $mp;
		$this->users[$this->uids[$u1['id']]]['mpNow'] = $this->stats[$this->uids[$u1['id']]]['mpNow'];
		
		if($u->info['id'] == $this->users[$this->uids[$u1['id']]]['id']) {
			$u->info['mpNow'] = $this->stats[$this->uids[$u1['id']]]['mpNow'];
			$u->stats['mpNow'] = $this->stats[$this->uids[$u1['id']]]['mpNow'];
		}
		
		mysql_query('UPDATE `stats` SET `mpNow` = "'.$this->stats[$this->uids[$u1['id']]]['mpNow'].'" WHERE `id` = "'.$this->info[$this->uids[$u1['id']]]['id'].'" LIMIT 1');
		
		$mas1['text'] = '{tm1} {u1} использовал заклинание &quot;<b>'.$pl['name'].'</b>&quot; и восстановил магические силы. <b><font color=#006699>'.$mp.'</font></b> ['.floor($this->stats[$this->uids[$u1['id']]]['mpNow']).'/'.$this->stats[$this->uids[$u1['id']]]['mpAll'].'] (Мана)';	
		
	}elseif($pl['id']==244)
	{
		//Пылающая Смерть
		$ptst = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`v2`,`hod`,`user_use` FROM `eff_users` WHERE `user_use` = "'.$u->info['id'].'" AND `uid` = "'.$ue['id'].'" AND `delete` = "0" AND `v1` = "priem" AND (`v2` = 58 OR `v2` = 57 OR `v2` = 56 OR `v2` = 33 OR `v2` = 60 OR `v2` = 59) LIMIT 1'));
		if(!isset($ptst['id'])) {
			echo '<font color=red><b>Цель &quot;'.$ue['login'].'&quot; не подвержена действию &quot;Пожирающее Пламя&quot;</b></font>';
			$cup = true;
		}elseif($btl->stats[$btl->uids[$ue['id']]]['hpNow'] > floor($btl->stats[$btl->uids[$ue['id']]]['hpAll']/3)) {
			echo '<font color=red><b>Цель &quot;'.$ue['login'].'&quot; имеет сильшком большой уровень жизни</b></font>';
			$cup = true;
		}else{
			$yn = 8;
			if($ptst['v2'] == 33) { //6
				$yn = 8;
			}elseif($ptst['v2'] == 56) { //7
				$yn = 10;
			}elseif($ptst['v2'] == 57) { //8
				$yn = 12;
			}elseif($ptst['v2'] == 58) { //9
				$yn = 14;
			}elseif($ptst['v2'] == 59) { //10
				$yn = 17;
			}elseif($ptst['v2'] == 60) { //11
				$yn = 20;
			}

			$yn = ($yn*$ptst['hod']);
			//$yn = $this->testPower($btl->stats[$btl->uids[$u->info['id']]],$btl->stats[$btl->uids[$ue['id']]],$yn,1,2);
			$yn = floor($yn*1.18);
			
			//заносим в лог боя			
			$this->magicAtack($ue,$yn,1,$pl,$eff,0,0,0,0,false);
			
			
			//$krituetli = true;
			//$hpmin = $yn;
				
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$ue['sex'].'||t2='.$ue['team'].'||login2='.$ue['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'",`uid` = "0" WHERE `id` = "'.$ptst['id'].'" LIMIT 1');
			
			//$re = $this->addPriem($ue['id'],242,'add_notactic=1|add_nousepriem=1',0,77,2,$u->info['id'],2,'шокирующийудар');
			
			$mas1['text'] = '{tm1} Закончилось действие приема &quot;<b>'.$ptst['name'].'</b>&quot; для {u2}.';	
			$btl->pr_not_use[$ptst['id']] = 1;
			$btl->add_log($mas1);
			
			echo '<font color=red><b>Вы высвободили энергию заклятия &quot;'.$ptst['name'].'&quot;</b></font>';
		}
	}elseif($pl['id']==243)
	{
		//Пылающий Взрыв
		$ptst = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`v2`,`hod`,`user_use` FROM `eff_users` WHERE `user_use` = "'.$u->info['id'].'" AND `uid` = "'.$ue['id'].'" AND `delete` = "0" AND `v1` = "priem" AND (`v2` = 58 OR `v2` = 57 OR `v2` = 56 OR `v2` = 33 OR `v2` = 60 OR `v2` = 59) LIMIT 1'));
		if(!isset($ptst['id'])) {
			echo '<font color=red><b>Цель &quot;'.$ue['login'].'&quot; не подвержена действию &quot;Пожирающее Пламя&quot;</b></font>';
			$cup = true;
		}else{
			$yn = 8;
			if($ptst['v2'] == 33) { //6
				$yn = 8;
			}elseif($ptst['v2'] == 56) { //7
				$yn = 10;
			}elseif($ptst['v2'] == 57) { //8
				$yn = 12;
			}elseif($ptst['v2'] == 58) { //9
				$yn = 14;
			}elseif($ptst['v2'] == 59) { //10
				$yn = 17;
			}elseif($ptst['v2'] == 60) { //11
				$yn = 20;
			}

			$yn = ($yn*$ptst['hod']);
			//$yn = $this->testPower($btl->stats[$btl->uids[$u->info['id']]],$btl->stats[$btl->uids[$ue['id']]],$yn,1,2);
			$yn = floor($yn*0.27);
			
			//заносим в лог боя
			
			$rx = 4;
			$xx = 0;
			$ix = 0;
			while($ix<count($btl->users))
			{
				
				if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
				{
					$this->magicAtack($btl->users[$ix],$yn,1,$pl,$eff,0,0,0,0,false);
					$xx++;
				}
				$ix++;
			}
			$this->magicAtack($ue,$yn,1,$pl,$eff,0,0,0,0,false);
			
			
			//$krituetli = true;
			//$hpmin = $yn;
				
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$ue['sex'].'||t2='.$ue['team'].'||login2='.$ue['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'",`uid` = "0" WHERE `id` = "'.$ptst['id'].'" LIMIT 1');
			
			//$re = $this->addPriem($ue['id'],242,'add_notactic=1|add_nousepriem=1',0,77,2,$u->info['id'],2,'шокирующийудар');
			
			$mas1['text'] = '{tm1} Закончилось действие приема &quot;<b>'.$ptst['name'].'</b>&quot; для {u2}.';	
			$btl->pr_not_use[$ptst['id']] = 1;
			$btl->add_log($mas1);
			
			echo '<font color=red><b>Вы высвободили энергию заклятия &quot;'.$ptst['name'].'&quot;</b></font>';
		}
	}elseif($pl['id']==241)
	{
		//Пылающий Ужас
		$ptst = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`v2`,`hod`,`user_use` FROM `eff_users` WHERE `user_use` = "'.$u->info['id'].'" AND `uid` = "'.$ue['id'].'" AND `delete` = "0" AND `v1` = "priem" AND (`v2` = 58 OR `v2` = 57 OR `v2` = 56 OR `v2` = 33 OR `v2` = 60 OR `v2` = 59) LIMIT 1'));
		if(!isset($ptst['id'])) {
			echo '<font color=red><b>Цель &quot;'.$ue['login'].'&quot; не подвержена действию &quot;Пожирающее Пламя&quot;</b></font>';
			$cup = true;
		}else{
			$yn = 8;
			if($ptst['v2'] == 33) { //6
				$yn = 8;
			}elseif($ptst['v2'] == 56) { //7
				$yn = 10;
			}elseif($ptst['v2'] == 57) { //8
				$yn = 12;
			}elseif($ptst['v2'] == 58) { //9
				$yn = 14;
			}elseif($ptst['v2'] == 59) { //10
				$yn = 17;
			}elseif($ptst['v2'] == 60) { //11
				$yn = 20;
			}

			$yn = ($yn*$ptst['hod']);
			//$yn = $this->testPower($btl->stats[$btl->uids[$u->info['id']]],$btl->stats[$btl->uids[$ue['id']]],$yn,1,2);
			$yn = round($yn/2);
			
			//заносим в лог боя
			$krituetli = true;
			$hpmin = $yn;
				
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$ue['sex'].'||t2='.$ue['team'].'||login2='.$ue['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'",`uid` = "0" WHERE `id` = "'.$ptst['id'].'" LIMIT 1');
			
			$re = $this->addPriem($ue['id'],242,'add_notactic=1|add_nousepriem=1',0,77,2,$u->info['id'],2,'шокирующийудар');
			
			$mas1['text'] = '{tm1} Закончилось действие приема &quot;<b>'.$ptst['name'].'</b>&quot; для {u2}.';	
			$btl->pr_not_use[$ptst['id']] = 1;
			$btl->add_log($mas1);
			
			echo '<font color=red><b>Вы высвободили энергию заклятия &quot;'.$ptst['name'].'&quot;</b></font>';
		}
	}elseif($pl['id']==15)
	{
		//испепеление [4]
		$hpmin = 21;
	}elseif($pl['id']==16)
	{
		//испепеление [5]
		$hpmin = 25;
	}elseif($pl['id']==29)
	{
		//Знак огня
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'знакогня',1,1);
	}elseif($pl['id']==33)
	{
		//Пожирающее пламя [6]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'пожирающеепламя',1,1);
		if($re==false)
		{
			echo '[Er::Пожирающее Пламя[xX]]';
		}
	}elseif($pl['id']==34)
	{
		//Вспышка [8]
		$hpmin = 33;
		//каждая вспышка +5% мощности
		$pwx = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$ue['id'].'" AND `v1` = "priem" AND `v2` = "26" AND `delete` = "0" LIMIT 25'));		
		$pwx = $pwx['x'];
		if($pwx>0)
		{
			$hpmin += round($hpmin/100*($pwx*5));
		}
		unset($pwx);
	}elseif($pl['id']==67)
	{
		//Вспышка [9]
		$hpmin = 39;
		//каждая вспышка +5% мощности
		$pwx = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$ue['id'].'" AND `v1` = "priem" AND `v2` = "26" AND `delete` = "0" LIMIT 25'));		
		$pwx = $pwx['x'];
		if($pwx>0)
		{
			$hpmin += round($hpmin/100*($pwx*5));
		}
		unset($pwx);
	}elseif($pl['id']==68)
	{
		//Вспышка [10]
		$hpmin = 47;
		//каждая вспышка +5% мощности
		$pwx = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$ue['id'].'" AND `v1` = "priem" AND `v2` = "26" AND `delete` = "0" LIMIT 25'));		
		$pwx = $pwx['x'];
		if($pwx>0)
		{
			$hpmin += round($hpmin/100*($pwx*5));
		}
		unset($pwx);
	}elseif($pl['id']==69)
	{
		//Вспышка [11]
		$hpmin = 57;
		//каждая вспышка +5% мощности
		$pwx = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$ue['id'].'" AND `v1` = "priem" AND `v2` = "26" AND `delete` = "0" LIMIT 25'));		
		$pwx = $pwx['x'];
		if($pwx>0)
		{
			$hpmin += round($hpmin/100*($pwx*5));
		}
		unset($pwx);
	}elseif($pl['id']==35)
	{
		//Тепло Жизни [7]
		$hpadd = rand(37,42);
	}elseif($pl['id']==50)
	{
		//испепеление [6]
		$hpmin = 30;
	}elseif($pl['id']==51)
	{
		//испепеление [7]
		$hpmin = 36;
	}elseif($pl['id']==52)
	{
		//испепеление [8]
		$hpmin = 44;
	}elseif($pl['id']==53)
	{
		//испепеление [9]
		$hpmin = 52;
	}elseif($pl['id']==54)
	{
		//испепеление [10]
		$hpmin = 63;
	}elseif($pl['id']==55)
	{
		//испепеление [11]
		$hpmin = 76;
	}elseif($pl['id']==56)
	{
		//Пожирающее пламя [7]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'пожирающеепламя',1,1);
		if($re==false)
		{
			echo '[Er::Пожирающее Пламя[xX]]';
		}
	}elseif($pl['id']==57)
	{
		//Пожирающее пламя [8]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'пожирающеепламя',1,1);
		if($re==false)
		{
			echo '[Er::Пожирающее Пламя[xX]]';
		}
	}elseif($pl['id']==58)
	{
		//Пожирающее пламя [9]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'пожирающеепламя',1,1);
		if($re==false)
		{
			echo '[Er::Пожирающее Пламя[xX]]';
		}
	}elseif($pl['id']==59)
	{
		//Пожирающее пламя [10]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'пожирающеепламя',1,1);
		if($re==false)
		{
			echo '[Er::Пожирающее Пламя[xX]]';
		}
	}elseif($pl['id']==60)
	{
		//Пожирающее пламя [11]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'пожирающеепламя',1,1);
		if($re==false)
		{
			echo '[Er::Пожирающее Пламя[xX]]';
		}
	}elseif($pl['id']==61)
	{
		//Тепло Жизни [6]
		$krituetli=true;
		$hpadd = rand(44,51);
		if($ue['id'] == $u->info['id']) {
			$dopyrn = $hpadd; 
		}
	}elseif($pl['id']==62)
	{
		//Тепло Жизни [7]
		$krituetli=true;
		$hpadd = rand(53,61);
		if($ue['id'] == $u->info['id']) {
			$dopyrn = $hpadd; 
		}
	}elseif($pl['id']==63)
	{
		//Тепло Жизни [8]
		$krituetli=true;
		$hpadd = rand(64,73);
		if($ue['id'] == $u->info['id']) {
			$dopyrn = $hpadd; 
		}
	}elseif($pl['id']==64)
	{
		//Тепло Жизни [9]
		$krituetli=true;
		$hpadd = rand(77,88);
		if($ue['id'] == $u->info['id']) {
			$dopyrn = $hpadd; 
		}
	}elseif($pl['id']==65)
	{
		//Тепло Жизни [10]
		$krituetli=true;
		$hpadd = rand(92,105);
		if($ue['id'] == $u->info['id']) {
			$dopyrn = $hpadd; 
		}
	}elseif($pl['id']==66)
	{
		//Тепло Жизни [11]
		$krituetli=true;
		$hpadd = rand(111,127);
		if($ue['id'] == $u->info['id']) {
			$dopyrn = $hpadd; 
		}
	}elseif($pl['id']==161 || $pl['id']==162 || $pl['id']==163)
	{
		//Язык пламени [8]
		$hpmin = round($btl->stats[$btl->uids[$ue['id']]]['hpAll']*0.03);	
		if($hpmin<0)
		{
			$hpmin = 0;
		}
		$hpmxx = array(
		161 => 204,
		162 => 244,
		163 => 293
		);
		//каждая Цель огня +2% мощности
		$pwi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$ue['id'].'" AND `v1` = "priem" AND `v2` = "26" AND `delete` = "0" LIMIT 25'));		
		$pwx = (int)(0+$pwi['x']);
		if($pwx>0)
		{
			$hpmin += round($btl->stats[$btl->uids[$ue['id']]]['hpAll']/100*($pwx*2));
		}
		if($hpmin>$hpmxx[$pl['id']])
		{
			$hpmin = $hpmxx[$pl['id']];
		}

		//Удаляем цели
		$pwi['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "26" LIMIT 1'));
		if(isset($pwi['id']))
		{
			$btl->delPriem($pwi,$btl->users[$btl->uids[$ue['id']]],2);
		}
		$this->magicAtack($ue,$hpmin,1,$pl,$eff,2,$hpmxx[$pl['id']]);
		unset($pwx,$pwi,$hpmin,$hpmxx);
		
	}elseif($pl['id']==165)
	{
		//Скрытое пламя
		$upd = $this->rezadEff($u->info['id'],'wis_fire');
		if($upd==false)
		{
			$cup = true;
		}
		unset($upd);
	}elseif($pl['id']==186)
	{
		if(round($u->stats['hpAll']/10) < floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']))
		{
			$this->magicAtack($btl->users[$btl->uids[$u->info['id']]],round($u->stats['hpAll']/10),1,$pl,$eff,1,0,0,1);	
			//Восстанавливаем 20% маны
			$rg = round($btl->stats[$btl->uids[$u->info['id']]]['mpAll']/5);
			$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] += $rg;
			if($btl->stats[$btl->uids[$u->info['id']]]['mpNow']>$btl->stats[$btl->uids[$u->info['id']]]['mpAll'])
			{
				$rg -= floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']-$btl->stats[$btl->uids[$u->info['id']]]['mpAll']);
				$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpAll'];
			}
			$btl->users[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
			mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->users[$btl->uids[$u->info['id']]]['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			//заносим в лог боя
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			if($rg>0)
			{
				$rg = '+'.$rg;
			}else{
				$rg = '--';
			}
			$mas1['text'] = '{tm1} {u1} {1x16x0} заклинание &quot;<b>'.$pl['name'].'</b>&quot; и восстановил магические силы. <b><font color=#006699>'.$rg.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['mpAll'].'] (Мана)';	
			$btl->add_log($mas1);
		}else{
			$cup = true;
		}
	}
	/////
	
	//добавляем НР к цели
	if($hpadd>0)
	{
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicRegen($ue,$hpadd,1,$pl,$eff,0,0,$krituetli,$dopyrn);	
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
	
	//отнимаем НР у противника
	if($hpmin>0)
	{
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicAtack($ue,$hpmin,1,$pl,$eff,0,0,0,0,$krituetli);	
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
}
?>