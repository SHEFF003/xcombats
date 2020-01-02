<?
if(!defined('GAME'))
{
	die();
}
$krituetli=true;
if(isset($hod))
{
	if($pr['id']==73)
	{
		//олединение [5]
		$krituetli=false;
		$hpmin = 6;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==21)
	{
		//олединение [4]
		$krituetli=false;
		$hpmin = 3;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==74)
	{
		//олединение [6] 
        $krituetli=false;
		$hpmin = 7;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==75)
	{
		//олединение [7] 
        $krituetli=false;
		$hpmin = 8;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==76)
	{
		//олединение [8] 
        $krituetli=false;
		$hpmin = 10;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==77)
	{
		//олединение [9] 
        $krituetli=false;
		$hpmin = 12;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==78)
	{
		//олединение [10] 
        $krituetli=false;
		$hpmin = 14;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==79)
	{
		//олединение [11] 
        $krituetli=false;
		$hpmin = 16;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==22)
	{
	
		//отравление [6]
		$krituetli=false;
		$hpmin = 6.1;
		$minmp = 3;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==80)
	{
		//отравление [7]
		$krituetli=false;
		$hpmin = 7.3;
		$minmp = 5;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==81)
	{
		//отравление [8]
		$krituetli=false;
		$hpmin = 8.8;
		$minmp = 7;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==82)
	{
		//отравление [9]
		$krituetli=false;
		$hpmin = 10.5;
		$minmp = 9;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==83)
	{
		//отравление [10]
		$krituetli=false;
		$hpmin = 12.7;
		$minmp = 11;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==84)
	{
		//отравление [11]
		$krituetli=false;
		$hpmin = 15.2;
		$minmp = 12;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==23)
	{
		//ядовитое облако [8]
		$krituetli=false;
		$hpmin = 13;
	}elseif($pr['id']==32)
	{
		//Знак воды
		$minmp = (int)$u->info['level'];
	}elseif($pr['id']==36)
	{
		//Регенерация [5] 
        $krituetli=false;
		$hpadd = 7.5;
		$mpmin = 4;
	}
	elseif($pr['id']==85)
	{
		//Регенерация [6] 
        $krituetli=false;
		$hpadd = 9;
		$mpmin = 6;
	}
	elseif($pr['id']==86)
	{
		//Регенерация [7] 
        $krituetli=false;
		$hpadd = 11;
		$mpmin = 8;
	}
	elseif($pr['id']==87)
	{
		//Регенерация [8] 
        $krituetli=false;
		$hpadd = 13;
		$mpmin = 10;
	}
	elseif($pr['id']==88)
	{
		//Регенерация [9] 
        $krituetli=false;
		$hpadd = 16;
		$mpmin = 12;
	}
	elseif($pr['id']==89)
	{
		//Регенерация [10] 
        $krituetli=false;
		$hpadd = 19;
		$mpmin = 14;
	}
	elseif($pr['id']==90)
	{
		//Регенерация [11] 
        $krituetli=false;
		$hpadd = 23;
		$mpmin = 16;
	}
	//добавляем НР к цели
	if($hpadd>0)
	{
		if($btl->users[$btl->uids[$eff['uid']]]['hpNow']>0)
		{
			$this->magicRegen($ue,$hpadd,3,$pr,$eff,1,0,$krituetli);	
		}else{
			$cup = true; //не удалось использовать прием
		}
	}
	if($hpmin>0)
	{
		$re = $this->magicAtack($ue,$hpmin,3,$pr,$eff,1,0,0,0,$krituetli);
	}
	if(isset($minmp))
	{
		//отнимаем МР у кастера
		if($this->minMana($eff['user_use'],$minmp,3)==false)
		{
			//снимаем эффект, мана закончилась
			$btl->delPriem($eff,$btl->users[$btl->uids[$eff['uid']]],2);
		}
	}
}else{
	$uen = $u->info['enemy']; //на кого используем прием
	
	if(isset($_POST['useon']) && $_POST['useon']!='' && $_POST['useon']!='none')
	{
		$ue = $this->ue;
		if(isset($ue['id']))
		{
			$uen = $ue['id'];
		}else{
			$uen = 0;	
		}
	}
	if($pl['id']==267) {
		//Кристаллизация		
		$hpmin = $btl->stats[$btl->uids[$uen]]['s1'];
		if($hpmin > $u->info['level']*10) {
			$hpmin = $u->info['level']*10;
		}
		$this->magicAtack($btl->users[$btl->uids[$uen]],$hpmin,3,$pl,$eff,0,0,0,1,$krituetli);	
		unset($hpmin);		
		$this->addPriem($uen,268,'|add_s1=-'.$btl->stats[$btl->uids[$u->info['id']]]['mg3'].'|add_s2=-'.$btl->stats[$btl->uids[$u->info['id']]]['mg3'].'',1,77,4,$u->info['id'],1,'кристаллизация');
	}elseif($pl['id'] == 265) {
		//олединение: разбить
		$mg = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$uen]]['id'].'" AND `bj` = "оледенение" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
		if(isset($mg['id'])) {
			
			$hpmin = 21;
			if($mg['v2'] == 73) {
				$hpmin = 23;
			}elseif($mg['v2'] == 74) {
				$hpmin = 27;
			}elseif($mg['v2'] == 75) {
				$hpmin = 33;
			}elseif($mg['v2'] == 76) {
				$hpmin = 39;
			}elseif($mg['v2'] == 77) {
				$hpmin = 47;
			}elseif($mg['v2'] == 78) {
				$hpmin = 57;
			}elseif($mg['v2'] == 79) {
				$hpmin = 68;
			}
			$hpmin += round($hpmin/100*(50*$mg['x']));
			echo '<font color=red><b>Прием &quot;'.$pl['name'].'&quot; успешно использован.</b></font>';
			mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$mg['id'].'" LIMIT 1');
		}else{
			echo '<font color=red><b>На цели нет приема &quot;Оледенение&quot;</b></font>';
			$cup = true;
		}
		
	}elseif($pl['id']==73)
	{
		//олединение [5]
		$hpmin = 23;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==21)
	{
		//олединение [4]
		$hpmin = 21;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==74)
	{
		//олединение [6]
		$hpmin = 27;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==75)
	{
		//олединение [7]
		$hpmin = 33;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==76)
	{
		//олединение [8]
		$hpmin = 39;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==77)
	{
		//олединение [9]
		$hpmin = 47;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==78)
	{
		//олединение [10]
		$hpmin = 57;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==79)
	{
		//олединение [11]
		$hpmin = 68;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'оледенение');
	}elseif($pl['id']==22)
	{
		//отравление [6]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'отравление',3,1);
		if($re==false)
		{
			echo '[Er::Отравление[xX]]';
		}
	}elseif($pl['id']==80)
	{
		//отравление [7]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'отравление',3,1);
		if($re==false)
		{
			echo '[Er::Отравление[xX]]';
		}
	}elseif($pl['id']==81)
	{
		//отравление [8]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'отравление',3,1);
		if($re==false)
		{
			echo '[Er::Отравление[xX]]';
		}
	}elseif($pl['id']==82)
	{
		//отравление [9]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'отравление',3,1);
		if($re==false)
		{
			echo '[Er::Отравление[xX]]';
		}
	}elseif($pl['id']==83)
	{
		//отравление [10]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'отравление',3,1);
		if($re==false)
		{
			echo '[Er::Отравление[xX]]';
		}
	}elseif($pl['id']==84)
	{
		//отравление [11]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'отравление',3,1);
		if($re==false)
		{
			echo '[Er::Отравление[xX]]';
		}
	}elseif($pl['id']==23)
	{
		//Ядовитое Облако [8] 3-5 целей
		$rx = rand(20,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$re = $this->addPriem($btl->users[$ix]['id'],$pl['id'],'',0,77,5,$u->info['id'],1,'ядовитоеоблако',3,1);
				if($re==false)
				{
					echo '[Er::ЯдовитоеОблако[xX]]';
				}
				$xx++;
			}
			$ix++;
		}
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'ядовитоеоблако',3,1);
		if($re==false)
		{
			echo '[Er::ЯдовитоеОблако[xX]]';
		}
	}elseif($pl['id']==32)
	{
		//Знак воды
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'знакводы',3,1);
	}elseif($pl['id']==36)
	{
		//Регенерация [5]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'регенерация',3,1);
	}elseif($pl['id']==85)
	{
		//Регенерация [6]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'регенерация',3,1);
	}elseif($pl['id']==86)
	{
		//Регенерация [7]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'регенерация',3,1);
	}elseif($pl['id']==87)
	{
		//Регенерация [8]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'регенерация',3,1);
	}elseif($pl['id']==88)
	{
		//Регенерация [9]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'регенерация',3,1);
	}elseif($pl['id']==89)
	{
		//Регенерация [10]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'регенерация',3,1);
	}elseif($pl['id']==90)
	{
		//Регенерация [11]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'регенерация',3,1);
	}elseif($pl['id']==164)
	{
		//Острая грань
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$re = $this->magicAtack($ue,150,3,$pl,$eff,0,0,1);
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
	elseif($pl['id']==174)
	{
		//Ледяное сердце
		$upd = $this->rezadEff($u->info['id'],'wis_water');
		if($upd==false)
		{
			$cup = true;
		}else{
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'];
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot;. <small>(Сняты задержки на магию Воды)</small>';	
			$btl->add_log($mas1);
		}
		unset($upd);
	}
	elseif($pl['id'] == 205) {
	//Чистота Воды
	
		$i=0;
		$add_where='';
		while($i<count($btl->users)){
		if($btl->users[$i]['team']==$btl->users[$btl->uids[$u->info['id']]]['team']){
		$add_where.=' and `user_use`!="'.$btl->users[$i]['id'].'"';
		}
		$i++;
		}

        $dell = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `user_use`!= "" and `delete`="0" and `uid`="'.$u->info['id'].'" and `v1`="priem" '.$add_where.'  LIMIT 1'));
		
		if($dell){
		$dell['priem']['id']=$dell['id'];
			$btl->delPriem($dell,$u->info,99,false,$pl['name']);
	  }
		
	}
	
	
	if($hpmin>0)
	{
		//отнимаем НР у противника
		if(isset($hpmin) && $hpmin>0 && $uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$re = $this->magicAtack($btl->users[$btl->uids[$uen]],$hpmin,3,$pl,$eff,0,0,0,0,$krituetli);
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
}
?>