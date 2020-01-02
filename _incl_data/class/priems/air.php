<?
if(!defined('GAME'))
{
	die();
}

function mg2static_points($uid,$st) {	
	global $u;
	if(isset($st['mg2static_points'])) {
		$mg = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$uid.'" AND `data` LIKE "%add_mg2static_points%" ORDER BY `id` DESC LIMIT 1'));
		if(isset($mg['id'])) {
			$mg['data'] = $u->lookStats($mg['data']);
			$mg['data']['add_mg2static_points']++;
			$mg['data']['add_zm2proc']--;
			$mg['data'] = $u->impStats($mg['data']);
			mysql_query('UPDATE `eff_users` SET `data` = "'.$mg['data'].'" WHERE `id` = "'.$mg['id'].'" LIMIT 1');
		}
	}	
}

if(isset($hod))
{
	if($pr['id'] == 258) {
		
		//Энергия воздуха
		$minmp = round($btl->stats[$btl->uids[$eff['uid']]]['mpAll']/100);
		$minmp = rand($minmp,$minmp*10);
		$minmp = -round($minmp);
		
	}elseif($pr['id']==30)
	{
		//Знак воздуха
		$minmp = -$u->info['level'];		
	}
	if(isset($minmp))
	{
		//отнимаем МР у кастера
		if($this->minMana($eff['user_use'],$minmp,2)==false && $minmp>0)
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
	
	if($pl['id'] == 261 || $pl['id'] == 262 || $pl['id'] == 263) {
		//Заряд: Поражение
		if(isset($btl->stats[$btl->uids[$uen]]['mg2static_points']) && $btl->stats[$btl->uids[$uen]]['mg2static_points'] > 0) {
			$mg = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$uen]]['id'].'" AND `data` LIKE "%add_mg2static_points%" ORDER BY `id` DESC LIMIT 1'));
			if(isset($mg['id'])) {
				$mg['data'] = $u->lookStats($mg['data']);
				
				if($pl['id'] == 261) {
					$hpmin = round($btl->stats[$btl->uids[$uen]]['hpAll']/100*rand(1,3));
					$hpmin = round($hpmin*$mg['data']['add_mg2static_points']);
					if($u->info['level'] <= 9 && $hpmin > 250) {
						$hpmin = 250;
					}elseif($u->info['level'] == 10 && $hpmin > 300) {
						$hpmin = 300;
					}elseif($u->info['level'] >= 11 && $hpmin > 350) {
						$hpmin = 350;
					}
				}elseif($pl['id'] == 262) {					
					$this->addPriem($btl->users[$btl->uids[$uen]]['id'],264,'',0,77,$mg['data']['add_mg2static_points'],$u->info['id'],1,'зарядшок');
					$mgd = mysql_fetch_array(mysql_query('SELECT `a`.`id` FROM `eff_users` AS `a` JOIN `priems` AS `b` ON `b`.`id` = `a`.`v2` WHERE `a`.`uid` = "'.$btl->users[$btl->uids[$uen]]['id'].'" AND `a`.`v1` = "priem" AND `a`.`delete` = "0" AND `b`.`neg` = 0 ORDER BY `id` ASC LIMIT 1'));
					if(isset($mgd['id'])) {
						mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$mgd['id'].'" LIMIT 1');
					}
				}elseif($pl['id'] == 263) {
					
					//Медитация
					$rg = round(3*$u->info['level']*$btl->stats[$btl->uids[$uen]]['mg2static_points']);
					
					$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] += $rg;
					if($btl->stats[$btl->uids[$u->info['id']]]['mpNow']>$btl->stats[$btl->uids[$u->info['id']]]['mpAll'])
					{
						$rg -= floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']-$btl->stats[$btl->uids[$u->info['id']]]['mpAll']);
						$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpAll'];
					}
					$u->info['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
					$u->stats['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
					$btl->users[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
					mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->stats[$btl->uids[$u->info['id']]]['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					//заносим в лог боя
					$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
					$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
					if($rg>0)
					{
						$rg = '+'.$rg;
					}else{
						$rg = '--';
					}
					$mas1['text'] = '{tm1} {u1} {1x16x0} заклинание &quot;<b>'.$pl['name'].'</b>&quot; на {u2} и восстановил магические силы. <b><font color=#006699>'.$rg.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['mpAll'].'] (Мана)';	
					$btl->add_log($mas1);

					
					$mgd = mysql_fetch_array(mysql_query('SELECT `a`.`id` FROM `eff_users` AS `a` JOIN `priems` AS `b` ON `b`.`id` = `a`.`v2` WHERE `a`.`uid` = "'.$u->info['id'].'" AND `a`.`v1` = "priem" AND `a`.`delete` = "0" AND `b`.`neg` = 1 ORDER BY `id` ASC LIMIT 1'));
					if(isset($mgd['id'])) {
						mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$mgd['id'].'" LIMIT 1');
					}
				}
								
				$mg['data']['add_mg2static_points'] = 0;
				$mg['data']['add_zm2proc'] = 0;
				$mg['data'] = $u->impStats($mg['data']);
				
				echo '<font color=red><b>Прием &quot;'.$pl['name'].'&quot; успешно использован.</b></font>';				
				mysql_query('UPDATE `eff_users` SET `data` = "'.$mg['data'].'" WHERE `id` = "'.$mg['id'].'" LIMIT 1');
			}else{
				echo '<font color=red><b>На цели нет приема &quot;Статика&quot;, либо нет уровня заряда (Воздух)</b></font>';
				$cup = true;
			}
		}else{
			echo '<font color=red><b>На цели нет приема &quot;Статика&quot;, либо нет уровня заряда (Воздух)</b></font>';
			$cup = true;
		}
	}elseif($pl['id']==255)
	{
		 //Силовое поле
		 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'силовоеполе',2,1);
	}elseif($pl['id']==17)
	{
		//молния [4]
		$hpmin = rand(1,42);
	}elseif($pl['id']==18)
	{
		//молния [5]
		$hpmin = rand(1,50);
	}elseif($pl['id']==91)
	{
		//молния [6]
		$hpmin = rand(1,60);
	}elseif($pl['id']==92)
	{
		//молния [7]
		$hpmin = rand(1,73);
	}elseif($pl['id']==93)
	{
		//молния [8]
		$hpmin = rand(1,87);
	}elseif($pl['id']==94)
	{
		//молния [9]
		$hpmin = rand(1,105);
	}elseif($pl['id']==95)
	{
		//молния [10]
		$hpmin = rand(1,126);
	}elseif($pl['id']==96)
	{
		//молния [11]
		$hpmin = rand(1,151);
	}elseif($pl['id']==30)
	{
	
		//Знак воздуха
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'знаквоздуха',1,1);
	}elseif($pl['id']==37)
	{
		//Цепь Исцеления [5] 1-3 цели
		$rx = rand(0,20);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],rand(1,95),2,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($ue,rand(1,95),2,$pl,$eff,0,0,false);
	}elseif($pl['id']==102)
	{
		//Цепь Исцеления [6] 1-3 цели
		$rx = rand(0,20);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],rand(1,115),2,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($ue,rand(1,115),2,$pl,$eff,0,0,false);
	}elseif($pl['id']==103)
	{
		//Цепь Исцеления [7] 1-3 цели
		echo 1;
		$rx = rand(0,20);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],rand(1,138),2,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($ue,rand(1,138),2,$pl,$eff,0,0,false);
	}elseif($pl['id']==104)
	{
		//Цепь Исцеления [8] 1-3 цели
		$rx = rand(0,20);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],rand(1,165),2,$pl,$eff,0,0,false); // не критует
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($ue,rand(1,165),2,$pl,$eff,0,0,false);
	}elseif($pl['id']==105)
	{
		//Цепь Исцеления [9] 1-3 цели
		$rx = rand(0,20);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],rand(1,198),2,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($ue,rand(1,198),2,$pl,$eff,0,0,false);
	}elseif($pl['id']==106)
	{
		//Цепь Исцеления [10] 1-3 цели
		$rx = rand(0,20);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],rand(1,238),2,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($ue,rand(1,238),2,$pl,$eff,0,0,false);
	}elseif($pl['id']==107)
	{
		//Цепь Исцеления [11] 1-3 цели
		$rx = rand(0,20);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],rand(1,286),2,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($ue,rand(1,286),2,$pl,$eff,0,0,false);
	}elseif($pl['id']==38)
	{
		//Цепь Молний [6] 2-5 целей
		$rx = rand(10,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,35),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,rand(1,35),2,$pl,$eff,0,0,0,0,false);
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);
	}elseif($pl['id']==97)
	{
		//Цепь Молний [7] 2-5 целей
		$rx = rand(10,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,42),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,rand(1,42),2,$pl,$eff,0,0,0,0,false);
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);
	}elseif($pl['id']==98)
	{
		//Цепь Молний [8] 2-5 целей
		$rx = rand(10,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,51),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,rand(1,51),2,$pl,$eff,0,0,0,0,false);	
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);
	}elseif($pl['id']==99)
	{
		//Цепь Молний [9] 2-5 целей
		$rx = rand(10,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,61),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,rand(1,61),2,$pl,$eff,0,0,0,0,false);
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);
	}elseif($pl['id']==100)
	{
		//Цепь Молний [10] 2-5 целей
		$rx = rand(10,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,73),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,rand(1,73),2,$pl,$eff,0,0,0,0,false);
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);
	}elseif($pl['id']==101)
	{
		//Цепь Молний [11] 2-5 целей
		$rx = rand(10,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,88),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,rand(1,88),2,$pl,$eff,0,0,0,0,false);
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);		
	}elseif($pl['id']==259)
	{
		//Искра
		if(rand(0,1) == 1) {
			//удар по противнику
			$rx = 1;
			$xx = 0;
			$ix = 0;
			while($ix<count($btl->users))
			{
				
				if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $u->info['enemy']!=$btl->users[$ix]['id'])
				{
					$this->magicAtack($btl->users[$ix],rand(1,51),2,$pl,$eff,0,0,0,0,false);
					mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
					$xx++;
				}
				$ix++;
			}			
			if($xx == 0) {
				$this->magicAtack($btl->users[$btl->uids[$u->info['enemy']]],rand(1,41),2,$pl,$eff,0,0,0,0,false);	
				mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);
			}
		}else{
			//восстановление
			$rx = 1;
			$xx = 0;
			$ix = 0;
			while($ix<count($btl->users))
			{			
				if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$ue['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
				{
					$this->magicRegen($btl->users[$ix],rand(1,151),2,$pl,$eff,0,0,false);
					$xx++;
				}
				$ix++;
			}
			if($xx == 0) {
				$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],rand(1,151),2,$pl,$eff,0,0,false);
			}
		}
	}elseif($pl['id']==39)
	{
		//Искры [8] 1-7 целей
		$rx = rand(0,60);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $u->info['enemy']!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,41),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($btl->users[$btl->uids[$u->info['enemy']]],rand(1,41),2,$pl,$eff,0,0,0,0,false);
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);		
	}elseif($pl['id']==108)
	{
		//Искры [9] 1-7 целей
		$rx = rand(0,60);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $u->info['enemy']!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,50),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($btl->users[$btl->uids[$u->info['enemy']]],rand(1,50),2,$pl,$eff,0,0,0,0,false);	
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);	
	}elseif($pl['id']==109)
	{
		//Искры [10] 1-7 целей
		$rx = rand(0,60);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $u->info['enemy']!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,60),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($btl->users[$btl->uids[$u->info['enemy']]],rand(1,60),2,$pl,$eff,0,0,0,0,false);
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);		
	}elseif($pl['id']==110)
	{
		//Искры [11] 1-7 целей
		$rx = rand(0,60);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{

			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $u->info['enemy']!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],rand(1,72),2,$pl,$eff,0,0,0,0,false);
				mg2static_points($btl->users[$ix]['id'],$btl->stats[$ix]);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($btl->users[$btl->uids[$u->info['enemy']]],rand(1,72),2,$pl,$eff,0,0,0,0,false); 
		mg2static_points($btl->users[$btl->uids[$u->info['enemy']]]['id'],$btl->stats[$btl->uids[$u->info['enemy']]]);
	}


	
	//отнимаем НР у противника
	if($hpmin>0)
	{
		if(isset($hpmin) && $uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicAtack($ue,$hpmin,2,$pl,$eff,0);
			mg2static_points($uen,$btl->stats[$btl->uids[$uen]]);
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
}
?>