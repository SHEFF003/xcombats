<?
if(!defined('GAME'))
{
	die();
}

if($e['bm_a1']=='wpchrr1' && $end > 0)
{
	$hpmin = $this->atacks[$end]['uid_'.$uid2.'_t1']+$this->atacks[$end]['uid_'.$uid2.'_t4']+$this->atacks[$end]['uid_'.$uid2.'_t5'];
	if(rand(0,10000)<250 && $hpmin > 0)
	{
		//наносим урон магическим кольцом
		$hpmin = 10;
		//
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
		
		$hp1 = $this->stats[$this->uids[$uid1]]['hpNow'];
		
		//расчет урона стихий
		
		$hp1 += $hpmin;
		if($hp1<0)
		{
			$hp1 = 0;
		}elseif($hp1>$this->stats[$this->uids[$uid1]]['hpAll'])
		{
			$hp1 = $this->stats[$this->uids[$uid1]]['hpAll'];
		}
		
		$this->takeExp($u->info['id'],$hpmin,$uid1,$uid2);
		
		//отнимаем НР
		$this->users[$this->uids[$uid2]]['hpNow'] = $hp2;
		$this->stats[$this->uids[$uid2]]['hpNow'] = $hp2;
		mysql_query('UPDATE `stats` SET `hpNow` = '.$hp2.' WHERE `id` = "'.$uid2.'" LIMIT 1');
		
		$this->users[$this->uids[$uid1]]['hpNow'] = $hp1;
		$this->stats[$this->uids[$uid1]]['hpNow'] = $hp1;
		mysql_query('UPDATE `stats` SET `hpNow` = '.$hp1.' WHERE `id` = "'.$uid1.'" LIMIT 1');
		
		//заносим в лог боя
		$vLog = 'time1='.time().'||s1='.$this->users[$this->uids[$uid1]]['sex'].'||t1='.$this->users[$this->uids[$uid1]]['team'].'||login1='.$this->users[$this->uids[$uid1]]['login'].'||s2='.$this->users[$this->uids[$uid2]]['sex'].'||t2='.$this->users[$this->uids[$uid2]]['team'].'||login2='.$this->users[$this->uids[$uid2]]['login'].'';
		$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		if($hpmin>0)
		{
			$hpmin1 = '+'.$hpmin;
			$hpmin = '-'.$hpmin;
		}else{
			$hpmin1 = '--';
			$hpmin = '--';
		}
		$mas1['text'] = '{tm1} {u1} вытянул здоровье у {u2} при помощи &quot;<b>'.$itm['name'].'</b> (Вытягивание души [1])&quot;. <b title=Тип&nbsp;урона:&nbsp;тьма ><font color=Purple>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$this->stats[$this->uids[$uid2]]['hpAll'].'] / <b title=Тип&nbsp;урона:&nbsp;тьма ><font color=green>'.$hpmin1.'</font></b> ['.ceil($hp1).'/'.$this->stats[$this->uids[$uid1]]['hpAll'].']';	
		$this->add_log($mas1);
	}
}

?>