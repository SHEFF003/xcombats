<?
if(!defined('GAME'))
{
	die();
}

if($e['bm_a1']=='wpchr21' && $end > 0)
{
	$hpmin = $this->atacks[$end]['uid_'.$uid1.'_t1']+$this->atacks[$end]['uid_'.$uid1.'_t4']+$this->atacks[$end]['uid_'.$uid1.'_t5'];
	if(rand(0,10000) < 100 && $hpmin > 0)
	{	
		//
		$e['imposed_name'] = str_replace('&nbsp;',' ',$e['imposed_name']);
		//	
		$pvr['test'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$uid2.'" AND `delete` = 0 AND `name` = "'.$e['imposed_name'].'" LIMIT 1'));
		//Чары неуклюжести 1%
		if( !isset($pvr['test']['id']) ) {
			//
			mysql_query("
			INSERT INTO `eff_users` ( `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
			( 22, '".$uid2."', 'Чары неуклюжести 1', 'add_s2=-30', 0, 77, 0, '".$uid1."', 0, 'priem', 319, 'enhp_3_codex1.gif', 1, 5, 'чарынеуклюжести1', 0, 0, '', 0, 0, 0, 1, 0);
			");
			//
			$vLog = 'time1='.time().'||s1='.$this->users[$this->uids[$uid1]]['sex'].'||t1='.$this->users[$this->uids[$uid1]]['team'].'||login1='.$this->users[$this->uids[$uid1]]['login'].'||s2='.$this->users[$this->uids[$uid2]]['sex'].'||t2='.$this->users[$this->uids[$uid2]]['team'].'||login2='.$this->users[$this->uids[$uid2]]['login'].'';
			$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
			$sax = '';
			if( $this->users[$this->uids[$uid2]]['sex'] == 1 ) {
				$sax = 'а';
			}
			$mas1['text'] = '{tm1} {u2} получил'.$sax.' магическое ослабление &quot;<b>'.$e['imposed_name'].'</b>&quot;. (Ловкость: -30 на 5 ходов)';	
			$this->add_log($mas1);
		}
	}
}

?>