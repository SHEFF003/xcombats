<?
if(!defined('GAME'))
{
	die();
}

if($pl['id']==3)
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
}elseif($pl['id']==189)
{
	if($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']>=1)
	{
		//ошеломить
		$this->addPriem($u->info['enemy'],$pl['id'],'',0,77,2,$u->info['id'],5,'ошеломить');
		$this->addPriem($u->info['enemy'],191,'',0,77,5,$u->info['id'],5,'иммунтитеошеломить');
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
		$mas1['text'] = '{tm1} {u1} {1x16x0} прием &quot;<b>'.$pl['name'].'</b>&quot; и ошеломил'.$sx.' {u2} на два хода.';	
		$pz = $btl->users[$btl->uids[$u->info['enemy']]]['priems_z'];
		$pz = explode('|',$pz);
		$i = 0;
		while($i<=30)
		{
			$pz[$i] += 2;
			$i++;
		}
		$pz = implode('|',$pz);
		$btl->users[$btl->uids[$u->info['enemy']]]['priems_z'] = $pz;
		unset($pz);
		mysql_query('UPDATE `stats` SET `priems_z` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['priems_z'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
		$btl->add_log($mas1);
		$pz[(int)$id] = 1;
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
}
?>