<?
if(!defined('GAME'))
{
	die();
}
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
	
 if($pl['id'] == 214)
 {
	 //Божественная аура
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,3,$u->info['id'],1,'божественнаяаура',7,1);
 }elseif($pl['id']>=175 && $pl['id']<=179)
 {
	 //Силовое поле
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'силовоеполе',7,1);
 }elseif($pl['id']==24)
{
	//Медитация
	//Восстанавливаем 10% маны
	$rg = round($btl->stats[$btl->uids[$u->info['id']]]['mpAll']/10);
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
	$mas1['text'] = '{tm1} {u1} {1x16x0} заклинание &quot;<b>'.$pl['name'].'</b>&quot; и восстановил магические силы. <b><font color=#006699>'.$rg.'</font></b> ['.floor($btl->stats[$btl->uids[$u->info['id']]]['mpNow']).'/'.$btl->stats[$btl->uids[$u->info['id']]]['mpAll'].'] (Мана)';	
	$btl->add_log($mas1);
}elseif($pl['id']==154){
	//Магический луч [4]
	$hpmin = 40;
 }elseif($pl['id']==155){
	//Магический луч [7]
	$hpmin = 55;
 }elseif($pl['id']==156){
	//Магический луч [8]
	$hpmin = 60;
 }elseif($pl['id']==157){
	//Магический луч [9]
	$hpmin = 65;
 }elseif($pl['id']==158){
	//Магический луч [10]
	$hpmin = 70;
 }elseif($pl['id']==159){
	//Магический луч [11]
	$hpmin = 75;
 }elseif($pl['id']==160){
	//Силовой луч [7]
	$hpmin = $u->stats['s5'];
 }elseif($pl['id']>=194 && $pl['id']<=197)
 {
	 //магический барьер
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'магичческийбарьер',7,1);
 }elseif($pl['id']==206)
 {
	 //магический барьер
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'магичческийбарьер',7,1);
 }elseif($pl['id']==207)
 {
	 //магический барьер
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'магичческийбарьер',7,1);
 }elseif($pl['id']==208)
 {
	 //магический барьер
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'магичческийбарьер',7,1);
 }elseif($pl['id']==209)
 {
	 //магический барьер
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'магичческийбарьер',7,1);
 }elseif($pl['id']==210)
 {
	 //магический барьер
	 $re = $this->addPriem($u->info['id'],$pl['id'],-1,0,77,-2,$u->info['id'],1,'магичческийбарьер',7,1);
 }

	//отнимаем НР у противника
	if($hpmin>0)
	{
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicAtack($ue,$hpmin,7,$pl,$eff);	
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
?>