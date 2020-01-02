<?
if(!defined('GAME'))
{
	die();
}

if($e['bm_a1']=='itm129' && $end > 0)
{
	$hpmin = $this->atacks[$end]['uid_'.$uid1.'_t1']+$this->atacks[$end]['uid_'.$uid1.'_t4']+$this->atacks[$end]['uid_'.$uid1.'_t5'];
	if(rand(0,10000)<3500 && $hpmin > 0)
	{
		//наносим урон магическим кольцом
		$hpmin = rand(5,8);
		$hpmin += round($this->stats[$this->uids[$uid1]]['mg1']*1.35);
		//$hpmin = $priem->testPower($this->stats[$this->uids[$uid1]],$this->stats[$this->uids[$uid2]],$hpmin,1,2);	
		//
		$hpmin = $priem->magatack($uid1,$uid2,$hpmin,'огонь',false);
		$hpmin = $hpmin[0];
		//
		$hpmin = floor(1+$hpmin);
		if($hpmin < 0) { $hpmin = 0; }
		$hp2 = $this->stats[$this->uids[$uid2]]['hpNow'];
		
		//расчет урона стихий
		
		$hp2 -= $hpmin;
		if($hp2<0)
		{
			$hp2 = 0;
		}elseif($hp2>$this->stats[$this->uids[$uid2]]['hpNow'])
		{
			$hp2 = $this->stats[$this->uids[$uid2]]['hpNow'];
		}
		
		$this->takeExp($u->info['id'],$hpmin,$uid1,$uid2);
		
		//отнимаем НР
		$this->users[$this->uids[$uid2]]['hpNow'] = $hp2;
		$this->stats[$this->uids[$uid2]]['hpNow'] = $hp2;
		mysql_query('UPDATE `stats` SET `hpNow` = '.$hp2.' WHERE `id` = "'.$uid2.'" LIMIT 1');
		
		//заносим в лог боя
		$vLog = 'time1='.time().'||s1='.$this->users[$this->uids[$uid1]]['sex'].'||t1='.$this->users[$this->uids[$uid1]]['team'].'||login1='.$this->users[$this->uids[$uid1]]['login'].'||s2='.$this->users[$this->uids[$uid2]]['sex'].'||t2='.$this->users[$this->uids[$uid2]]['team'].'||login2='.$this->users[$this->uids[$uid2]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		if($hpmin>0)
		{
			$hpmin = '-'.$hpmin;
		}else{
			$hpmin = '--';
		}
		$mas1['text'] = '{tm1} {u2} получил повреждение от магического предмета &quot;<b>'.$itm['name'].'</b>&quot;. <b title=Тип&nbsp;урона:&nbsp;огненный ><font color=#A00000>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$this->stats[$this->uids[$uid2]]['hpAll'].']';	
		$this->add_log($mas1);
	}
}

?>