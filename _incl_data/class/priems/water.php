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
		//���������� [5]
		$krituetli=false;
		$hpmin = 6;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==21)
	{
		//���������� [4]
		$krituetli=false;
		$hpmin = 3;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==74)
	{
		//���������� [6] 
        $krituetli=false;
		$hpmin = 7;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==75)
	{
		//���������� [7] 
        $krituetli=false;
		$hpmin = 8;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==76)
	{
		//���������� [8] 
        $krituetli=false;
		$hpmin = 10;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==77)
	{
		//���������� [9] 
        $krituetli=false;
		$hpmin = 12;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==78)
	{
		//���������� [10] 
        $krituetli=false;
		$hpmin = 14;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==79)
	{
		//���������� [11] 
        $krituetli=false;
		$hpmin = 16;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==22)
	{
	
		//���������� [6]
		$krituetli=false;
		$hpmin = 6.1;
		$minmp = 3;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==80)
	{
		//���������� [7]
		$krituetli=false;
		$hpmin = 7.3;
		$minmp = 5;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==81)
	{
		//���������� [8]
		$krituetli=false;
		$hpmin = 8.8;
		$minmp = 7;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==82)
	{
		//���������� [9]
		$krituetli=false;
		$hpmin = 10.5;
		$minmp = 9;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==83)
	{
		//���������� [10]
		$krituetli=false;
		$hpmin = 12.7;
		$minmp = 11;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==84)
	{
		//���������� [11]
		$krituetli=false;
		$hpmin = 15.2;
		$minmp = 12;
		if($eff['x']>1)
		{
			$hpmin += ceil($hpmin/100*(5*$eff['x']));
		}
	}elseif($pr['id']==23)
	{
		//�������� ������ [8]
		$krituetli=false;
		$hpmin = 13;
	}elseif($pr['id']==32)
	{
		//���� ����
		$minmp = (int)$u->info['level'];
	}elseif($pr['id']==36)
	{
		//����������� [5] 
        $krituetli=false;
		$hpadd = 7.5;
		$mpmin = 4;
	}
	elseif($pr['id']==85)
	{
		//����������� [6] 
        $krituetli=false;
		$hpadd = 9;
		$mpmin = 6;
	}
	elseif($pr['id']==86)
	{
		//����������� [7] 
        $krituetli=false;
		$hpadd = 11;
		$mpmin = 8;
	}
	elseif($pr['id']==87)
	{
		//����������� [8] 
        $krituetli=false;
		$hpadd = 13;
		$mpmin = 10;
	}
	elseif($pr['id']==88)
	{
		//����������� [9] 
        $krituetli=false;
		$hpadd = 16;
		$mpmin = 12;
	}
	elseif($pr['id']==89)
	{
		//����������� [10] 
        $krituetli=false;
		$hpadd = 19;
		$mpmin = 14;
	}
	elseif($pr['id']==90)
	{
		//����������� [11] 
        $krituetli=false;
		$hpadd = 23;
		$mpmin = 16;
	}
	//��������� �� � ����
	if($hpadd>0)
	{
		if($btl->users[$btl->uids[$eff['uid']]]['hpNow']>0)
		{
			$this->magicRegen($ue,$hpadd,3,$pr,$eff,1,0,$krituetli);	
		}else{
			$cup = true; //�� ������� ������������ �����
		}
	}
	if($hpmin>0)
	{
		$re = $this->magicAtack($ue,$hpmin,3,$pr,$eff,1,0,0,0,$krituetli);
	}
	if(isset($minmp))
	{
		//�������� �� � �������
		if($this->minMana($eff['user_use'],$minmp,3)==false)
		{
			//������� ������, ���� �����������
			$btl->delPriem($eff,$btl->users[$btl->uids[$eff['uid']]],2);
		}
	}
}else{
	$uen = $u->info['enemy']; //�� ���� ���������� �����
	
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
		//��������������		
		$hpmin = $btl->stats[$btl->uids[$uen]]['s1'];
		if($hpmin > $u->info['level']*10) {
			$hpmin = $u->info['level']*10;
		}
		$this->magicAtack($btl->users[$btl->uids[$uen]],$hpmin,3,$pl,$eff,0,0,0,1,$krituetli);	
		unset($hpmin);		
		$this->addPriem($uen,268,'|add_s1=-'.$btl->stats[$btl->uids[$u->info['id']]]['mg3'].'|add_s2=-'.$btl->stats[$btl->uids[$u->info['id']]]['mg3'].'',1,77,4,$u->info['id'],1,'��������������');
	}elseif($pl['id'] == 265) {
		//����������: �������
		$mg = mysql_fetch_array(mysql_query('SELECT `id`,`x` FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$uen]]['id'].'" AND `bj` = "����������" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
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
			echo '<font color=red><b>����� &quot;'.$pl['name'].'&quot; ������� �����������.</b></font>';
			mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$mg['id'].'" LIMIT 1');
		}else{
			echo '<font color=red><b>�� ���� ��� ������ &quot;����������&quot;</b></font>';
			$cup = true;
		}
		
	}elseif($pl['id']==73)
	{
		//���������� [5]
		$hpmin = 23;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==21)
	{
		//���������� [4]
		$hpmin = 21;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==74)
	{
		//���������� [6]
		$hpmin = 27;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==75)
	{
		//���������� [7]
		$hpmin = 33;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==76)
	{
		//���������� [8]
		$hpmin = 39;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==77)
	{
		//���������� [9]
		$hpmin = 47;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==78)
	{
		//���������� [10]
		$hpmin = 57;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==79)
	{
		//���������� [11]
		$hpmin = 68;
		$re = $this->addPriem($uen,$pl['id'],'',0,77,4,$u->info['id'],3,'����������');
	}elseif($pl['id']==22)
	{
		//���������� [6]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'����������',3,1);
		if($re==false)
		{
			echo '[Er::����������[xX]]';
		}
	}elseif($pl['id']==80)
	{
		//���������� [7]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'����������',3,1);
		if($re==false)
		{
			echo '[Er::����������[xX]]';
		}
	}elseif($pl['id']==81)
	{
		//���������� [8]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'����������',3,1);
		if($re==false)
		{
			echo '[Er::����������[xX]]';
		}
	}elseif($pl['id']==82)
	{
		//���������� [9]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'����������',3,1);
		if($re==false)
		{
			echo '[Er::����������[xX]]';
		}
	}elseif($pl['id']==83)
	{
		//���������� [10]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'����������',3,1);
		if($re==false)
		{
			echo '[Er::����������[xX]]';
		}
	}elseif($pl['id']==84)
	{
		//���������� [11]
		$re = $this->addPriem($uen,$pl['id'],'',0,77,10,$u->info['id'],1,'����������',3,1);
		if($re==false)
		{
			echo '[Er::����������[xX]]';
		}
	}elseif($pl['id']==23)
	{
		//�������� ������ [8] 3-5 �����
		$rx = rand(20,40);
		$rx = floor($rx/10);
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$re = $this->addPriem($btl->users[$ix]['id'],$pl['id'],'',0,77,5,$u->info['id'],1,'��������������',3,1);
				if($re==false)
				{
					echo '[Er::��������������[xX]]';
				}
				$xx++;
			}
			$ix++;
		}
		$re = $this->addPriem($uen,$pl['id'],'',0,77,5,$u->info['id'],1,'��������������',3,1);
		if($re==false)
		{
			echo '[Er::��������������[xX]]';
		}
	}elseif($pl['id']==32)
	{
		//���� ����
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'��������',3,1);
	}elseif($pl['id']==36)
	{
		//����������� [5]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'�����������',3,1);
	}elseif($pl['id']==85)
	{
		//����������� [6]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'�����������',3,1);
	}elseif($pl['id']==86)
	{
		//����������� [7]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'�����������',3,1);
	}elseif($pl['id']==87)
	{
		//����������� [8]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'�����������',3,1);
	}elseif($pl['id']==88)
	{
		//����������� [9]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'�����������',3,1);
	}elseif($pl['id']==89)
	{
		//����������� [10]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'�����������',3,1);
	}elseif($pl['id']==90)
	{
		//����������� [11]
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,8,$u->info['id'],1,'�����������',3,1);
	}elseif($pl['id']==164)
	{
		//������ �����
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$re = $this->magicAtack($ue,150,3,$pl,$eff,0,0,1);
		}else{
			$cup = true; //�� ������� ������������ �����	
		}
	}
	elseif($pl['id']==174)
	{
		//������� ������
		$upd = $this->rezadEff($u->info['id'],'wis_water');
		if($upd==false)
		{
			$cup = true;
		}else{
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'];
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			$mas1['text'] = '{tm1} {u1} {1x16x0} ����� &quot;<b>'.$pl['name'].'</b>&quot;. <small>(����� �������� �� ����� ����)</small>';	
			$btl->add_log($mas1);
		}
		unset($upd);
	}
	elseif($pl['id'] == 205) {
	//������� ����
	
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
		//�������� �� � ����������
		if(isset($hpmin) && $hpmin>0 && $uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$re = $this->magicAtack($btl->users[$btl->uids[$uen]],$hpmin,3,$pl,$eff,0,0,0,0,$krituetli);
		}else{
			$cup = true; //�� ������� ������������ �����	
		}
	}
}
?>