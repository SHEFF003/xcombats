<?
if(!defined('GAME'))
{
	die();
}

$krituetli = true;
if(isset($hod))
{
	$krituetli = 0;
	$heal = 0;
	if($pr['id'] == 253) {
		//�������� �����
		
	}elseif($pr['id']==252) {
		//����������: �����
		$minmp = 5;
		
		//�������� ����
		$this->minMana($eff['uid'],(9.5*$eff['x']),4);
		
	}elseif($pr['id']==251) {
		//����������: ����
		$minmp = 5;
		
		//���� ��
		$hpmin = round(2.7*$eff['x']);
		$heal = -1;
		$krituetli = false;
		
	}elseif($pr['id']==250) {
		//����������: ����
		$minmp = 5;
	}elseif($pr['id']==31)
	{
		//���� �����
		$minmp = $u->info['level'];		
	}elseif($pr['id']==42)
	{
		//�������� [6]
		if($eff['hod']==0)
		{
			$hpmin = rand(46,49);
			$krituetli = false;
		}
	}elseif($pr['id']==121)
	{
		//�������� [7]
		if($eff['hod']==0)
		{
			$hpmin = rand(55,59);
			$krituetli = false;
		}
	}elseif($pr['id']==122)
	{
		//�������� [8]
		if($eff['hod']==0)
		{
			$hpmin = rand(66,71);
			$krituetli = false;
		}
	}elseif($pr['id']==123)
	{
		//�������� [9]
		if($eff['hod']==0)
		{
			$hpmin = rand(79,86);
			$krituetli = false;
		}
	}elseif($pr['id']==124)
	{
		//�������� [10]
		if($eff['hod']==0)
		{
			$hpmin = rand(95,103);
			$krituetli = false;
		}
	}elseif($pr['id']==125)
	{
		//�������� [11]
		if($eff['hod']==0)
		{
			$hpmin = rand(114,124);
			$krituetli = false;
		}
	}
	//�������� �� � ����������
	if($hpmin>0)
	{
		$re = $this->magicAtack($ue,$hpmin,4,$pr,$eff,1,0,0,0,$krituetli,$heal);
	}
	if(isset($minmp))
	{
		//�������� �� � �������
		if($this->minMana($eff['user_use'],$minmp,4)==false)
		{
			//������� ������, ���� �����������
			$btl->delPriem($eff,$btl->users[$btl->uids[$eff['uid']]],2);	
		}
	}
}else{
	$uen = $u->info['enemy']; //�� ���� ���������� �����	
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
		
	if($pl['id'] == 253 && $fast_use_priem == 1) {
		//�������� ����� , ��������� � ����������� ������ ������
		$id = mysql_fetch_array(mysql_query('SELECT `id` FROM `test_bot` WHERE `login` = "�������� ����� (�����)" LIMIT 1'));
		$b = $u->addNewbot($id['id'],NULL,NULL);
		if($b>0 && $b!=false)
		{
			$xznm = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `battle` = "'.$btl->info['id'].'" AND `login` LIKE "�������� �����%" LIMIT 1'));
			if($xznm[0] > 0) {
				$xznm = ' ('.($xznm[0]).')';
			}else{
				$xznm = '';
			}
			mysql_query('UPDATE `users` SET `login` = "�������� �����'.$xznm.'",`obraz` = "0.gif",`battle` = "'.$btl->info['id'].'" WHERE `id` = "'.$b['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `team` = "'.$u->info['team'].'" WHERE `id` = "'.$b['id'].'" LIMIT 1');
			mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`user_use`,`name`,`timeUse`,`v1`,`v2`,`img2`) VALUES ("22","'.$b['id'].'","'.$u->info['id'].'","�������� �����: ��������","77","priem","254","wis_earth_summon") ');
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			if($u->info['sex'] == 1) {
				$mas1['text'] = '{tm1} {u1} ��������� ���� �� ����� � �������� &quot;<b>�������� �����'.$xznm.'&quot;</b>.';
			}else{
				$mas1['text'] = '{tm1} {u1} �������� ���� �� ����� � �������� &quot;<b>�������� �����'.$xznm.'&quot;</b>.';
			}
			$btl->add_log($mas1);	
			mysql_query('UPDATE `eff_users` SET `tr_life_user` = "'.$b['id'].'",`user_use` = "'.$b['id'].'" WHERE `v2` = "'.$pl['id'].'" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 1');		
		}
	}elseif($pl['priem']['id'] == 253) {
		//�������� �����
		//echo $yrn;
		//$ks = mysql_fetch_array(mysql_query('SELECT `uid` FROM `eff_users` WHERE `name` = "�������� �����: ��������" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
		//if(isset($ks['uid'])) {
			$ks = mysql_fetch_array(mysql_query('SELECT `id`,`hpNow` FROM `stats` WHERE `id` = "'.$pl['tr_life_user'].'" LIMIT 1'));
			if(isset($ks['id']) && $ks['hpNow'] >= 1) {
				$this->pr_yrn = array(-100001);
				
				$vLog = 'time1='.time().'||s1='.$this->users[$this->uids[$pl['uid']]]['sex'].'||t1='.$this->users[$this->uids[$pl['uid']]]['team'].'||login1='.$this->users[$this->uids[$pl['uid']]]['login'].'||s2='.$this->users[$this->uids[$pl['user_use']]]['sex'].'||t2='.$this->users[$this->uids[$pl['user_use']]]['team'].'||login2='.$this->users[$this->uids[$pl['user_use']]]['login'].'';
				$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				$ycrn = '#006699';
				if($yrnt == 3 || $yrnt == 4) {
					$ycrn = 'red';
				}
				
				$hpmin_pl = -$yrn;
				$hid = $pl['user_use'];
				
				$mas1['text'] = '{tm1} {u2} ��������� � ���������� � ������ ���� ������������ � {u1} �� ����. <b><font color='.$ycrn.'>-'.$yrn.'</font></b> ['.floor($this->stats[$this->uids[$pl['user_use']]]['hpNow']-$yrn).'/'.$this->stats[$this->uids[$pl['user_use']]]['hpAll'].']';
				$this->add_log($mas1);
				unset($mas1,$vLog,$ycrn);
				
			}else{
				//����������� ��������
				mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			}
		//}else{
			//����������� ��������
			//mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		//}
		//$this->pr_yrn = array(-100001);
	}elseif($pl['id']==246)
	{
		//������ �����
		//��������������� 5% HP + 5% ����
		$rg = round($btl->stats[$btl->uids[$u->info['id']]]['mpAll']*0.05);
		$rg2 = round($btl->stats[$btl->uids[$u->info['id']]]['hpAll']*0.05);
		
		$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] += $rg;		
		if($btl->stats[$btl->uids[$u->info['id']]]['mpNow']>$btl->stats[$btl->uids[$u->info['id']]]['mpAll'])
		{
			$rg -= floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']-$btl->stats[$btl->uids[$u->info['id']]]['mpAll']);
			$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpAll'];
		}
		
		$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] += $rg2;
		if($btl->stats[$btl->uids[$u->info['id']]]['hpNow']>$btl->stats[$btl->uids[$u->info['id']]]['hpAll'])
		{
			$rg2 -= floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']-$btl->stats[$btl->uids[$u->info['id']]]['hpAll']);
			$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpAll'];
		}
		
		$u->info['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
		$u->stats['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
		$btl->users[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
		
		$u->info['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpNow'];
		$u->stats['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpNow'];
		$btl->users[$btl->uids[$u->info['id']]]['hpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['hpNow'];
		
		if(mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['id']]]['hpNow'].'",`mpNow` = "'.$btl->stats[$btl->uids[$u->info['id']]]['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1')) {
		
			//������� � ��� ���
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			if($rg>0)
			{
				$rg = '+'.$rg;
			}else{
				$rg = '--';
			}
			if($rg2>0)
			{
				$rg2 = '+'.$rg2;
			}else{
				$rg2 = '--';
			}
			$mas1['text'] = '{tm1} {u1} {1x16x0} ���������� &quot;<b>'.$pl['name'].'</b>&quot; � ����������� �������� <b><font color=#006699>'.$rg2.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['hpAll'].'], � ���-�� ���������� ����. <b><font color=#006699>'.$rg.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['mpAll'].'] (����)';	
			$btl->add_log($mas1);
		
		}else{
			echo '������ �������������.';
		}
	}elseif($pl['id']==247){
	
			$i=0;
			$add_where='';
			while($i<count($btl->users)){
				if($btl->users[$i]['team']==$btl->users[$btl->uids[$u->info['id']]]['team']){
					$add_where.=' and `user_use`!="'.$btl->users[$i]['id'].'"';
				}
				$i++;
			}
	
			$dell = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `user_use`!= "" and `delete`="0" and `uid`="'.$u->info['id'].'" and `v1`="priem" '.$add_where.'  LIMIT 1'));
			
			if($dell) {
				$dell['priem']['id']=$dell['id'];
				//if($dell['x']==1){
					$btl->delPriem($dell,$u->info,99);
				/*}else{
				$i=0;
					$e = explode('|',$dell['data']);
					while($i<count($e)){
						$f = explode('=',$e[$i]);
						$stack=$f[1]/$dell['x'];//��������� ������������ ������ �� �-�� 
						$f[1]-=$stack;// �������� �����
						$e[$i] = implode('=',$f);
						$i++;
					}
					$dell['data'] = implode('|',$e);
					$dell['x']--;
					
					mysql_query('UPDATE `eff_users` SET `data` = "'.$dell['data'].'", `x`="'.$dell['x'].'"  WHERE `id` = "'.$dell['id'].'"');
						$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
						$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>$btl->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						$mas1['text'] = '{u1} ������� ����� &quot;<b>'.$dell['name'].'</b>&quot; � ������� <b>���������� ������</b> .';
						$btl->add_log($mas1);
				}*/
	  		}
		//��������������� 5% ����
		$rg = round($btl->stats[$btl->uids[$u->info['id']]]['mpAll']*0.05);
		
		$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] += $rg;		
		if($btl->stats[$btl->uids[$u->info['id']]]['mpNow']>$btl->stats[$btl->uids[$u->info['id']]]['mpAll'])
		{
			$rg -= floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']-$btl->stats[$btl->uids[$u->info['id']]]['mpAll']);
			$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpAll'];
		}
		
		$u->info['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
		$u->stats['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
		$btl->users[$btl->uids[$u->info['id']]]['mpNow'] = $btl->stats[$btl->uids[$u->info['id']]]['mpNow'];
		
		if(mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->stats[$btl->uids[$u->info['id']]]['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1')) {
		
			//������� � ��� ���
			$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'||s2='.$btl->users[$btl->uids[$u->info['enemy']]]['sex'].'||t2='.$btl->users[$btl->uids[$u->info['enemy']]]['team'].'||login2='.$btl->users[$btl->uids[$u->info['enemy']]]['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$btl->info['id'],'id_hod'=>($btl->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			if($rg>0)
			{
				$rg = '+'.$rg;
			}else{
				$rg = '--';
			}
			$mas1['text'] = '{tm1} {u1} {1x16x0} ���������� &quot;<b>'.$pl['name'].'</b>&quot; � ����������� ���������� ����. <b><font color=#006699>'.$rg.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['mpAll'].'] (����)';	
			$btl->add_log($mas1);
		
		}else{
			echo '������ �������������.';
		}
	}elseif($pl['id']==19)
	{
		//�������� [4]
		$hpmin = rand(17,19);
	}elseif($pl['id']==20)
	{
		//�������� [5]
		$hpmin = rand(20,23);
	}elseif($pl['id']==111)
	{
		//�������� [6]
		$hpmin = rand(24,28);
	}elseif($pl['id']==112)
	{
		//�������� [7]
		$hpmin = rand(29,34);
	}elseif($pl['id']==113)
	{
		//�������� [8]
		$hpmin = rand(35,41);
	}elseif($pl['id']==114)
	{
		//�������� [9]
		$hpmin = rand(43,49);
	}elseif($pl['id']==115)
	{
		//�������� [10]
		$hpmin = rand(51,59);
	}elseif($pl['id']==116)
	{
		//�������� [11]
		$hpmin = rand(62,71);	
	}elseif($pl['id']==31)
	{
		//���� �����
		$re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'���������',1,1);
	}elseif($pl['id']==40)
	{
		//���������� [7]
		$hpmin = round($ue['hpNow']*0.10);	
		if($hpmin<0)
		{
			$hpmin = 0;
		}
		if($hpmin>170)
		{
			$hpmin = 170;
		}
		$this->magicAtack($ue,$hpmin,4,$pl,$eff,0,170,0,1,true);
		unset($hpmin);
	}elseif($pl['id']==117)
	{
		//���������� [8]
		$hpmin = round($ue['hpNow']*0.10);	
		if($hpmin<0)
		{
			$hpmin = 0;
		}
		if($hpmin>204)
		{
			$hpmin = 204;
		}
		$this->magicAtack($ue,$hpmin,4,$pl,$eff,0,204,0,1,true);
		unset($hpmin);
	}elseif($pl['id']==118)
	{
		//���������� [9]
		$hpmin = round($ue['hpNow']*0.10);	
		if($hpmin<0)
		{
			$hpmin = 0;
		}
		if($hpmin>244)
		{
			$hpmin = 244;
		}
		$this->magicAtack($ue,$hpmin,4,$pl,$eff,0,244,0,1,true);
		unset($hpmin);
	}elseif($pl['id']==119)
	{
		//���������� [10]
		$hpmin = round($ue['hpNow']*0.10);	
		if($hpmin<0)
		{
			$hpmin = 0;
		}
		if($hpmin>293)
		{
			$hpmin = 293;
		}
		$this->magicAtack($ue,$hpmin,4,$pl,$eff,0,293,0,1,true);
		unset($hpmin);
	}elseif($pl['id']==120)
	{
		//���������� [11]
		$hpmin = round($ue['hpNow']*0.10);	
		if($hpmin<0)
		{
			$hpmin = 0;
		}
		if($hpmin>352)
		{
			$hpmin = 352;
		}
		$this->magicAtack($ue,$hpmin,4,$pl,$eff,0,352,0,1,true);
		unset($hpmin);
	}elseif($pl['id']==41)
	{
		//��� ������� [5] 5 ����� + ���
		$rx = 5;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$u->info['team'] && $xx<$rx && $u->info['id']!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],11,4,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],22,4,$pl,$eff,0,0,false);
	}elseif($pl['id']==132)
	{
		//��� ������� [6] 5 ����� + ���
		$rx = 5;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$u->info['team'] && $xx<$rx && $u->info['id']!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],13,4,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],22,4,$pl,$eff,0,0,false);
	}elseif($pl['id']==133)
	{
		//��� ������� [7] 5 ����� + ���
		$rx = 5;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$u->info['team'] && $xx<$rx && $u->info['id']!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],16,4,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],22,4,$pl,$eff,0,0,false);
	}elseif($pl['id']==134)
	{
		//��� ������� [8] 5 ����� + ���
		$rx = 5;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$u->info['team'] && $xx<$rx && $u->info['id']!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],19,4,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],22,4,$pl,$eff,0,0,false);
	}elseif($pl['id']==135)
	{
		//��� ������� [9] 5 ����� + ���
		$rx = 5;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$u->info['team'] && $xx<$rx && $u->info['id']!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],23,4,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],22,4,$pl,$eff,0,0,false);
	}elseif($pl['id']==136)
	{
		//��� ������� [10] 5 ����� + ���
		$rx = 5;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$u->info['team'] && $xx<$rx && $u->info['id']!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],28,4,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],22,4,$pl,$eff,0,0,false);
	}elseif($pl['id']==137)
	{
		//��� ������� [11] 5 ����� + ���
		$rx = 5;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']==$u->info['team'] && $xx<$rx && $u->info['id']!=$btl->users[$ix]['id'])
			{
				$this->magicRegen($btl->users[$ix],34,4,$pl,$eff,0,0,false);
				$xx++;
			}
			$ix++;
		}
		$this->magicRegen($btl->users[$btl->uids[$u->info['id']]],22,4,$pl,$eff,0,0,false);
	}if($pl['id']==42)
	{
		//�������� [6]
		$this->addPriem($uen,$pl['id'],'',0,77,3,$u->info['id'],1,'��������',4,1);
	}elseif($pl['id']==121)
	{
		//�������� [7]
		$this->addPriem($uen,$pl['id'],'',0,77,3,$u->info['id'],1,'��������',4,1);
	}elseif($pl['id']==122)
	{
		//�������� [8]
		$this->addPriem($uen,$pl['id'],'',0,77,3,$u->info['id'],1,'��������',4,1);
	}elseif($pl['id']==123)
	{
		//�������� [9]
		$this->addPriem($uen,$pl['id'],'',0,77,3,$u->info['id'],1,'��������',4,1);
	}elseif($pl['id']==124)
	{
		//�������� [10]
		$this->addPriem($uen,$pl['id'],'',0,77,3,$u->info['id'],1,'��������',4,1);
	}elseif($pl['id']==125)
	{
		//�������� [11]
		$this->addPriem($uen,$pl['id'],'',0,77,3,$u->info['id'],1,'��������',4,1);
	}elseif($pl['id']==43)
	{
		//�������� ����� [5] 8 �����
		$rx = 7;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx) // && $uen!=$btl->users[$ix]['id'] ���� ����� ���� ��� ���
			{
				$this->magicAtack($btl->users[$ix],7,4,$pl,$eff,0,0,0,0,false); // false ���� � ��� ��� ��������� �� ����� �����
				$xx++;
			}
			$ix++;
		}
		//$this->magicAtack($ue,10,4,$pl,$eff,0,0,0,0,false);	//������ ����� ���� �� ��� ���	
	}elseif($pl['id']==126)
	{
		//�������� ����� [6] 8 �����
		$rx = 7;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx)
			{
				$this->magicAtack($btl->users[$ix],9,4,$pl,$eff,0,0,0,0,false);
				$xx++;
			}
			$ix++;
		}
		//$this->magicAtack($ue,10,4,$pl,$eff,0,0,0,0,false);		
	}elseif($pl['id']==127)
	{
		//�������� ����� [7] 8 �����
		$rx = 7;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx)
			{
				$this->magicAtack($btl->users[$ix],11,4,$pl,$eff,0,0,0,0,false);
				$xx++;
			}
			$ix++;
		}
		//$this->magicAtack($ue,10,4,$pl,$eff,0,0,0,0,false);		
	}elseif($pl['id']==128)
	{
		//�������� ����� [8] 8 �����
		$rx = 7;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx)
			{
				$this->magicAtack($btl->users[$ix],13,4,$pl,$eff,0,0,0,0,false);
				$xx++;
			}
			$ix++;
		}
		//$this->magicAtack($ue,10,4,$pl,$eff,0,0,0,0,false);		
	}elseif($pl['id']==129)
	{
		//�������� ����� [9] 8 �����
		$rx = 7;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx)
			{
				$this->magicAtack($btl->users[$ix],16,4,$pl,$eff,0,0,0,0,false);
				$xx++;
			}
			$ix++;
		}
		//$this->magicAtack($ue,10,4,$pl,$eff,0,0,0,0,false);		
	}elseif($pl['id']==130)
	{
		//�������� ����� [10] 8 �����
		$rx = 7;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx)
			{
				$this->magicAtack($btl->users[$ix],19,4,$pl,$eff,0,0,0,0,false);
				$xx++;
			}
			$ix++;
		}
		//$this->magicAtack($ue,10,4,$pl,$eff,0,0,0,0,false);		
	}elseif($pl['id']==131)
	{
		//�������� ����� [11] 8 �����
		$rx = 7;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx)
			{
				$this->magicAtack($btl->users[$ix],23,4,$pl,$eff,0,0,0,0,false);
				$xx++;
			}
			$ix++;
		}
		//$this->magicAtack($ue,10,4,$pl,$eff,0,0,0,0,false);		
	}elseif($pl['id']==44)
	{
		//�������� ������ [8] 4 ����
		$rx = 3;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],19,4,$pl,$eff);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,58,4,$pl,$eff);
		$pzi = explode('|',$btl->users[$btl->uids[$ue['id']]]['priems_z']);
		$zi = 0;
		$zzi = 1;
		while($zi<count($pzi))
		{
			if($pzi[$zi]<$zzi)
			{
				$pzi[$zi] = $zzi;
			}
			$zi++;
		}
		$pzi = implode('|',$pzi);
		$btl->users[$btl->uids[$ue['id']]]['priems_z'] = $pzi;
		$btl->stats[$btl->uids[$ue['id']]]['priems_z'] = $pzi;			
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$pzi.'" WHERE `id` = "'.$ue['id'].'" LIMIT 1');
		unset($zzi,$zi,$pzi);
	}elseif($pl['id']==151)
	{
		//�������� ������ [9] 4 ����
		$rx = 3;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],23,4,$pl,$eff);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,69,4,$pl,$eff);
		$pzi = explode('|',$btl->users[$btl->uids[$ue['id']]]['priems_z']);
		$zi = 0;
		$zzi = 1;
		while($zi<count($pzi))
		{
			if($pzi[$zi]<$zzi)
			{
				$pzi[$zi] = $zzi;
			}
			$zi++;
		}
		$pzi = implode('|',$pzi);
		$btl->users[$btl->uids[$ue['id']]]['priems_z'] = $pzi;
		$btl->stats[$btl->uids[$ue['id']]]['priems_z'] = $pzi;			
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$pzi.'" WHERE `id` = "'.$ue['id'].'" LIMIT 1');
		unset($zzi,$zi,$pzi);
	}elseif($pl['id']==152)
	{
		//�������� ������ [10] 4 ����
		$rx = 3;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],29,4,$pl,$eff);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,83,4,$pl,$eff);
		$pzi = explode('|',$btl->users[$btl->uids[$ue['id']]]['priems_z']);
		$zi = 0;
		$zzi = 1;
		while($zi<count($pzi))
		{
			if($pzi[$zi]<$zzi)
			{
				$pzi[$zi] = $zzi;
			}
			$zi++;
		}
		$pzi = implode('|',$pzi);
		$btl->users[$btl->uids[$ue['id']]]['priems_z'] = $pzi;
		$btl->stats[$btl->uids[$ue['id']]]['priems_z'] = $pzi;			
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$pzi.'" WHERE `id` = "'.$ue['id'].'" LIMIT 1');
		unset($zzi,$zi,$pzi);
	}elseif($pl['id']==153)
	{
		//�������� ������ [11] 4 ����
		$rx = 3;
		$xx = 0;
		$ix = 0;
		while($ix<count($btl->users))
		{
			
			if($btl->stats[$ix]['hpNow']>0 && $btl->users[$ix]['team']!=$u->info['team'] && $xx<$rx && $uen!=$btl->users[$ix]['id'])
			{
				$this->magicAtack($btl->users[$ix],34,4,$pl,$eff);
				$xx++;
			}
			$ix++;
		}
		$this->magicAtack($ue,101,4,$pl,$eff);
		$pzi = explode('|',$btl->users[$btl->uids[$ue['id']]]['priems_z']);
		$zi = 0;
		$zzi = 1;
		while($zi<count($pzi))
		{
			if($pzi[$zi]<$zzi)
			{
				$pzi[$zi] = $zzi;
			}
			$zi++;
		}
		$pzi = implode('|',$pzi);
		$btl->users[$btl->uids[$ue['id']]]['priems_z'] = $pzi;
		$btl->stats[$btl->uids[$ue['id']]]['priems_z'] = $pzi;			
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$pzi.'" WHERE `id` = "'.$ue['id'].'" LIMIT 1');
		unset($zzi,$zi,$pzi);
	}
	elseif($pl['id']==166 || $pl['id']==167 || $pl['id']==168 || $pl['id']==169 || $pl['id']==170 || $pl['id']==171 || $pl['id']==172 ||$pl['id']==173)
	{
		/*$hpmxx = array(
			166 => 94,
			167 => 114,
			168 => 137,
			169	=> 166,
			170	=> 199,
			171	=> 241,
			172	=> 290,
			173	=> 345
		);
		
		$hpmxx = $hpmxx[$pl['id']];*/
		$hpmxx = round($u->info['level']*18.75);
		//�������� ����
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
		//echo 'weq';
			$re = $this->magicAtack($ue,$hpmxx,4,$pl,$eff,0,0,3);
		}else{
			$cup = true; //�� ������� ������������ �����	
		}
	}
	
	//�������� �� � ����������
	if($hpmin>0)
	{
		if(isset($hpmin) && $uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicAtack($ue,$hpmin,4,$pl,$eff,0,0,0,0,$krituetli);	
		}else{
			$cup = true; //�� ������� ������������ �����	
		}
	}
}

if(isset($hpmin_pl))
{

	if($this->stats[$this->uids[$hid]]['hpNow']+$hpmin_pl > $this->stats[$this->uids[$hid]]['hpAll'])
	{
		$hpmin_pl = $this->stats[$this->uids[$hid]]['hpAll']-$this->stats[$this->uids[$hid]]['hpNow'];
	}
	
	if($u->info['id'] == $this->users[$this->uids[$hid]]['id']) {
		$u->info['hpNow'] += $hpmin_pl;
		$u->stats['hpNow'] += $hpmin_pl;
	}
	
	$this->users[$this->uids[$hid]]['hpNow'] += $hpmin_pl;
	$this->stats[$this->uids[$hid]]['hpNow'] += $hpmin_pl;
	$upd = mysql_query('UPDATE `stats` SET `hpNow` = '.$this->stats[$this->uids[$hid]]['hpNow'].' WHERE `id` = "'.$this->users[$this->uids[$hid]]['id'].'" LIMIT 1');
	unset($hpmin_pl);
}
?>