<?
if(!defined('GAME'))
{
	die();
}
class quests {	
	public $free_x = 28,$data = array();	
	
	/* �������� �� ��������� ���*/	
	public function bfinuser($uid,$btl,$tmwin)
	{
		
	}
	
	/* �������� ����������� ������ */
	public function testGood($pl)
	{
	    global $c,$u;
	    
	    if(!is_array($pl)) {
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.$pl.'" LIMIT 1'));
	    }
	    
	    $r = 1; $d1 = 0;
	    $sp1 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `actions` WHERE `vars` LIKE "%start_quest%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 100'));
	    $pl1  = $pl1[0];
	    //���� ��� ����� 5 ������� �����
	    if($d1>=$this->free_x)
	    {
		$r = 0;
	    }
	    unset($d1,$pl1,$sp1);
	    //���� ����� ��� �����
	    $qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" ORDER BY `id` DESC LIMIT 1'));
	    if(isset($qlst['id']) && $qlst['vals'] != 'win' && $qlst['vals'] != 'end' && $qlst['vals'] != 'bad') {
			$r = 0;
	    }
	    unset($qlst,$qlst2);
    
    $d = $this->expl($pl['tr_date']);
    //��������� ������ [ 1,2,3,4,5 ...
	    if(isset($d['tr_endq']))
	    {
		$i = 0;
		$e = explode(',',$d['tr_endq']);
		while($i<count($e))
		{
		    $qlst = mysql_fetch_array(mysql_query('SELECT `id`,`vals` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$e[$i].'" AND `vals` = "win" ORDER BY `id` DESC LIMIT 1'));
		    if(!isset($qlst['id']))
		    {
			$r = 0;
		    }
		    $i++;
		}
		unset($qn,$qlst,$qlst2);
	    }
    //�������� ����� ����������� ������
	    if(isset($d['tr_zdr']))
	    {
		$qlst = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" AND `vals` != "go" ORDER BY `time` DESC LIMIT 1'));
		if($qlst['time']+($d['tr_zdr']*60*60)-time()>0)
		{
		    //$t .= '<small>(��������: '.$u->timeOut($qlst['time']+($d['tr_zdr']*60*60)-time()).' �.)</small>';
		    $r = 0;
		}
		unset($qlst);
	    }
    //������������� ������
	    if(isset($d['tr_tm1']))
	    {
		$d['tr_tm1'] = str_replace('d',date('d'),$d['tr_tm1']);
		$d['tr_tm1'] = str_replace('m',date('m'),$d['tr_tm1']);
		$d['tr_tm1'] = str_replace('y',date('y'),$d['tr_tm1']);	
		$d['tr_tm2'] = str_replace('d',date('d'),$d['tr_tm2']);
		$d['tr_tm2'] = str_replace('m',date('m'),$d['tr_tm2']);
		$d['tr_tm2'] = str_replace('y',date('y'),$d['tr_tm2']);
		//�������� ����������
	    }
    //������� ��� ����� ������ �����
	    if(isset($d['tr_raz']))
	    {
		$qlst = $u->testAction('SELECT `id` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" LIMIT '.$d['tr_raz'],2);
		if($d['tr_raz']>0 && $d['tr_raz']-$qlst[0] <= 0)
		{
		    $r = 0;
		}
		unset($qlst);
	    }
    //������� ������ �����
	    if(isset($d['tr_raz2']))
	    {
		$qlst = $u->testAction('SELECT `id` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" AND `vals` != "go" AND `vals` != "win"  LIMIT '.$d['tr_raz2'],2);
		if($d['tr_raz2']-$qlst[0] <= 0)
		{
		    $r = 0;
		}
		unset($qlst);
	    }
	    return $r;
	}
	
	
	public function onlyOnceQuest($quests, $uid)
	{ // ��������� ����������� ������
	    $result = array();
	    $rep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$uid.'" LIMIT 1'));
	    foreach($quests as $quest){
		$ok=true;
		$t = $this->expl($quest['tr_date']);
		if(isset($t['only_once']) && $t['only_once']=="1" ){
		    $ins = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) as sum FROM `actions` WHERE `uid` = "'.$uid.'" AND `vars` = "start_quest'.$quest['id'].'" AND (`vals` = "go" OR `vals` = "end")'));
		} else {
		    $ins = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) as sum FROM `actions` WHERE `uid` = "'.$uid.'" AND `vars` = "start_quest'.$quest['id'].'" AND `vals` = "go" '));
		    if($ins['sum']>0) $ins['sum']='delete';
		}
		if($ins['sum'] == 'delete'){
		    $ok=false;
		}elseif(isset($ins) && $ins['sum'] >= 1 && $rep['rep'.$quest['city']] < 10000) {
		    $ok=false;
		}elseif(isset($ins) && $ins['sum'] >= 2 && $rep['rep'.$quest['city']] <= 24999 && $rep['rep'.$quest['city']] >= 10000) {
		    $ok=false; 
		} else $result[] = $quest;
		unset($ins,$t,$ok);
	    } 
	    return $result;
	}
	/* ����� ����� */
	public function startq($id, $val=NULL)
	{
		global $c,$u;
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `delete` = "0" AND `min_lvl` <= '.$u->info['level'].' AND `max_lvl` >= '.$u->info['level'].' AND (`align` = "0" OR `align` = "'.floor($u->info['align']).'") AND `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(isset($pl['id']))
		{
			$u->addAction(time(),'start_quest'.$pl['id'],'go', NULL, $val);
			$u->error = '�� ������� �������� ������� &quot;'.$pl['name'].'&quot;';			
		}else{
			$u->error = '�� ������� �������� ������ �������';
		}
	}
	
	/* ����� ����� � ������ */
	public function startq_dn($id, $val=NULL)
	{
		global $c,$u;
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `delete` = "0" AND `min_lvl` <= '.$u->info['level'].' AND `max_lvl` >= '.$u->info['level'].' AND (`align` = "0" OR `align` = "'.floor($u->info['align']).'") AND `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(isset($pl['id']))
		{
			if($val==NULL)
			    $u->addAction(time(),'start_quest'.$pl['id'],'go');
			else 
			    $u->addAction(time(),'start_quest'.$pl['id'],'go', $u->info['id'], $val);
			$u->error = '�� ������� �������� ������� &quot;'.$pl['name'].'&quot;';			
		}else{
			$u->error = '�� ������� �������� ������ �������';
		}
	}
	
	/* ���������� �� ������ */
	public function endq($id,$tp)
	{
		global $u,$c;
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `delete` = "0" AND `min_lvl` <= '.$u->info['level'].' AND `max_lvl` >= '.$u->info['level'].' AND (`align` = "0" OR `align` = "'.floor($u->info['align']).'") AND `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		$qlst = mysql_fetch_array(mysql_query('SELECT `id` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" AND `vals` = "go" ORDER BY `id` DESC LIMIT 1'));
		if(isset($qlst['id']))
		{
			if($tp=='end')
			{
				mysql_query('UPDATE `actions` SET `vals` = "end" WHERE `id` = "'.$qlst['id'].'" LIMIT 1');
				$u->error = '�� ������� ���������� �� ������� &quot;'.$pl['name'].'&quot;';
			}elseif($tp=='win')
			{
				mysql_query('UPDATE `actions` SET `vals` = "win" WHERE `id` = "'.$qlst['id'].'" LIMIT 1');
			}
		}else{
			$u->error = '�� ������� ���������� �� ������� ';
		}
	}
	
	/* ���� � ������ � ������� */
	public function infoDng($pl) {
		$r = '';
		global $c,$u;
		$r = 0; $t = '';
		$xrz = 0;
		$qst = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" AND `vals` = "go" LIMIT 1'));
		//������� ������� ������� ������ ������������� ������� ���������������
			$d = $this->expl($pl['tr_date']);
			//�������� ������
			$d = $this->expl($pl['act_date']);
			
			//���������� � NPS
				if(isset($d['dlg_nps'])) {
					$i7 = 0;
					$x3 = explode(',',$d['dlg_nps']);
					while($i7 < count($x3)) {
						$x4 = explode('=',$x3[$i7]);
						if($x4[0] > 0) {
							$r++;
						}
						$i7++;
					}
					unset($x1,$x3,$x4,$i7);
				}
				
			//����� �������
				if(isset($d['kill_user']))
				{
					$x = 0;
					$r += $d['kill_user'];
					unset($x);
				}
				
			//����� �����
				if(isset($d['kill_bot'])){
					$x = '';
					$ex = explode(',',$d['kill_bot']);
					$i = 0;
					while($i<count($ex)){
						$x2 = 0;
						$ex2 = explode('=',$ex[$i]);					
						$bot2 = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$ex2[0].'" LIMIT 1'));
						if(isset($bot2['id'])){
							if(isset($qst['id'])){
								$x2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.$qst['time'].' AND `vars` = "win_bot_'.$ex2[0].'" LIMIT '.$ex2[1],2);
								$x2 = 0+$x2[0];
								
							}
							if(isset($d['all_kill']) && $d['all_kill']>0){
								$r = $d['all_kill'];
							} else {
								$r += $ex2[1];
							}
							$xrz += $x2;
						}
						$i++;
					}
					unset($x,$ex,$x2,$bot2,$ex2);
				} 

			//������� �������
			if(isset($d['tk_itm'])) {
				$ex = explode(',',$d['tk_itm']);
				$i = 0;
				$x = '';
				while($i < count($ex)) {
					$ex2 = explode('=',$ex[$i]);
					$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
					$x2[0] = (int)$x2[0]; 
					if( $x2[0] >= $ex2[1] ) {
						if( $x2[0] <= ($ex2[1] + round($ex2[1]*0.15)) ){
							$x2[0] = $x2[0];
						} else {
							$x2[0] = ($ex2[1] + round($ex2[1]*0.15));
						}
					}
					$r += $ex2[1];
					$xrz += $x2[0];
					$i++;
				}
			}

			//������� ������
			if(isset($d['tkill_itm'])) {
				$ex = explode(',',$d['tkill_itm']);
				$i = 0;
				$x = '';
				while($i < count($ex)) {
					$ex2 = explode('=',$ex[$i]);
					$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
					$x2[0] = (int)$x2[0]; 
					if( $x2[0] >= $ex2[1] ) {
						if( $x2[0] <= ($ex2[1] + round($ex2[1]*0.15)) ){
							$x2[0] = $x2[0];
						} else {
							$x2[0] = ($ex2[1] + round($ex2[1]*0.15));
						}
					}
					$bot2 = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($ex2[0]).'" LIMIT 1'));
					$r += $ex2[1];
					$xrz += $x2[0];
					$i++;
				}
			}

		if( $r < 1 ) {
			$r = '[0/1]';
		}else{
			if( $xrz < 0 ) {
				$xrz = 0;
			}
			if( $xrz > $r ) {
				$xrz = $r;
			}
			//$r = '['.$xrz.'/'.$r.']';
			$r = '<table style="display:inline-block;" border="0" cellspacing="0" cellpadding="0" height="10"><tr><td valign="middle" width="120" style="padding-top:12px">
  <div style="position:relative;"><div id="vhp-1234500000'.$pl['id'].'" title="���������� �������" align="left" class="seehp" style="position:absolute; top:-10px; width:120px; height:10px; z-index:12;"> '.$xrz.'/'.$r.'</div>
  <div title="���������� �������" class="hpborder" style="position:absolute; top:-10px; width:120px; height:9px; z-index:13;"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
  <div class="hp_3 senohp" style="height:9px; width:120px; position:absolute; top:-10px; z-index:11;" id="lhp-1234500000'.$pl['id'].'"><img src="http://img.xcombats.com/1x1.gif" height="9" width="1"></div>
  <div title="���������� �������" class="hp_none" style="position:absolute; top:-10px; width:120px; height:10px; z-index:10;"><img src="http://img.xcombats.com/1x1.gif" height="10"></div>
</div></td></tr></table><br><script>top.startHpRegen("main",-1234500000'.$pl['id'].','.$xrz.','.$r.',0,0,0,0,0,0,1);</script>';
		}
		return $r;
	}
	
	/* ���������� � ������ */
	public function info($pl) {
		global $c,$u;
		$r = ''; $t = '';
		$qst = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" AND `vals` = "go" LIMIT 1'));
		//������� ������� ������� ������ ������������� ������� ���������������
			$d = $this->expl($pl['tr_date']);
			//��������� ������ [ 1,2,3,4,5 ...
				if(isset($d['tr_endq']))
				{
					$t .= '��������� ������: ';
					$i = 0;
					$e = explode(',',$d['tr_endq']);
					while($i<=count($e))
					{
						if($e[$i]>0)
						{
							$qn = mysql_fetch_array(mysql_query('SELECT `name` FROM `quests` WHERE `id` = "'.$e[$i].'" LIMIT 1'));
							if(isset($qn['name']))
							{
								$t .= '&quot;'.$qn['name'].'&quot;, ';
							}
						}
						$i++;
					}
					$t = trim($t,', ');
					$t .= '<br>';
					unset($qn);
				}
			//�� ����� ������ �������� [ idbot-itm1=%,itm2=%|
				if(isset($d['tr_botitm']))
				{
					$t .= '�� ����� �������� ��������:<ul>';
					$e = explode('|',$d['tr_botitm']);
					$i = 0;
					while($i<count($e)) {
						$j = 0;
						$e2 = explode('-',$e[$i]);
						//$e2[0] - id ����
						if($e2[0]>0){
							$qn = mysql_fetch_array(mysql_query('SELECT `login` FROM `test_bot` WHERE `id` = "'.$e2[0].'" LIMIT 1'));
							$t .= '&nbsp; &nbsp; &bull; �� &quot;'.$qn['login'].'&quot; ��������: ';
						}else{
							$t .= '&nbsp; &nbsp; &bull; �� ����� ����� ��������: ';
						}
						//$e2[1] - ��������
						$j = 0;
						$e3 = explode(',',$e2[1]);
						while($j<count($e3))
						{
							$e4 = explode('=',$e3[$j]);
							//$e4[0] - ������� , $e4[1] - ����
							$qi = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img` FROM `items_main` WHERE `id` = "'.$e4[0].'" LIMIT 1'));
							$t .= '<img src="http://img.xcombats.com/i/items/'.$qi['img'].'" style="max-height:12px"> <a href="items_info.php?id='.$qi['id'].'" target="_blank">'.$qi['name'].'</a>, ';
							$j++;
						}
						$t = trim($t,', ');
						$i++;
					}
					$t .= '<br>';
					unset($qn,$qi,$e2,$e3,$e4);
				}
			//��� �������� ������ �������� [ type-itm1=%,itm2=%|
				if(isset($d['tr_winitm']))
				{
					$t .= '����� ������ �������� ��������:<br>';
					$e = explode('|',$d['tr_winitm']);
					$i = 0;
					while($i<count($e))
					{
						$j = 0;
						$e2 = explode('-',$e[$i]);
						$t .= '&nbsp; &nbsp; &bull; ';
						//$e2[0] - id ����
						if($e2[0]>0)
						{
							$t .= '�� ����� ��������: ';
						}else{
							$t .= '�� ����� ��������: ';
						}
						//$e2[1] - ��������
						$j = 0;
						$e3 = explode(',',$e2[1]);
						while($j<count($e3))
						{
							$e4 = explode('=',$e3[$j]);
							//$e4[0] - ������� , $e4[1] - ����
							$qi = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img` FROM `items_main` WHERE `id` = "'.$e4[0].'" LIMIT 1'));
							$t .= '<img src="http://img.xcombats.com/i/items/'.$qi['img'].'" style="max-height:12px"> <a href="items_info.php?id='.$qi['id'].'" target="_blank">'.$qi['name'].'</a>, ';
							$j++;
						}
						$t = trim($t,', ');
						$t .= '<br>';
						$i++;
					}
					unset($qn,$qi,$e2,$e3,$e4);
				}
			//�������� ����� ����������� ������
				if(isset($d['tr_zdr']))
				{
					$qlst = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" AND `vals` != "go" LIMIT 1'));
					$t .= '�������� ����� ����������� �������: '.$u->timeOut($d['tr_zdr']*60*60);
					if($qlst['time']+($d['tr_zdr']*60*60)-time()>0)
					{
						$t .= '<small>(��������: '.$u->timeOut($qlst['time']+($d['tr_zdr']*60*60)-time()).' �.)</small>';
					}
					$t .= '<br>';
					unset($qlst);
				}
			//������������� ������
				if(isset($d['tr_tm1']))
				{
					$d['tr_tm1'] = str_replace('d',date('d'),$d['tr_tm1']);
					$d['tr_tm1'] = str_replace('m',date('m'),$d['tr_tm1']);
					$d['tr_tm1'] = str_replace('y',date('y'),$d['tr_tm1']);	
									
					$d['tr_tm2'] = str_replace('d',date('d'),$d['tr_tm2']);
					$d['tr_tm2'] = str_replace('m',date('m'),$d['tr_tm2']);
					$d['tr_tm2'] = str_replace('y',date('y'),$d['tr_tm2']);
					
					$t .= '������ ������: '.$d['tr_tm1'].' - '.$d['tr_tm2'].'<br>';
				}
			//������� ��� ����� ������ �����
				if(isset($d['tr_raz']))
				{
					if($d['tr_raz']==-1)
					{
						$t .= '������� ��� ��� ����� ��������� �������: <b><small>����������</small></b><br>';
					}else{
						$qlst = $u->testAction('SELECT `id` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" LIMIT '.$d['tr_raz'],2);
						$t .= '������� ��� ����� ��������� �������: '.($d['tr_raz']-$qlst[0]).'<br>';
					}
					unset($qlst);
				}
			//������� ������ �����
				if(isset($d['tr_raz2']))
				{
					$qlst = $u->testAction('SELECT `id` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" AND `vals` != "go" AND `vals` != "win"  LIMIT '.$d['tr_raz2'],2);
					$t .= '�������� ������� ��������� �������: '.($d['tr_raz2']-$qlst[0]).'<br>';
					unset($qlst);
				}
				
			if($t!='')
			{
				$r .= '<b>������� �������:</b><br>'.$t.'<br>';
				$t = '';
			}
			
		//������� �� �����
			$d = $this->expl($pl['win_date']);
		
			if(isset($d['add_eff']))
			{
				$x5 = '';
				$x3 = explode(',',$d['add_eff']);
				$x4 = 0;
				while($x4 < count($x3)) {
					$x7 = explode('=',$x3[$x4]);
					$x6 = mysql_fetch_array(mysql_query('SELECT `id2`,`mname`,`img` FROM `eff_main` WHERE `id2` = "'.$x7[0].'" LIMIT 1'));
					if(isset($x6['id2'])) {
						$x5 .= '<img width="40" height="25" src="http://img.xcombats.com/i/eff/'.$x6['img'].'" title="'.$x6['mname'].'
����� ��������: '.$u->timeOut($x7[1]).'"> ';
					}
					$x4++;
				}
				if($x5 != '') {
					$t .= ''.$x5.'<br>';
				}
				unset($x3,$x4,$x5,$x6,$x7);
			}
			
			/*
			if(isset($d['add_rep']))
			{
				if($pl['city']!='')
				{
					$t .= '��������� '.$u->city_name[$pl['city']].': '.$d['add_rep'].'<br>';
				}
			}
			
			if(isset($d['add_cr']))
			{
				$t .= '������: '.$d['add_cr'].' ��.<br>';
			}
		
			if($t!='')
			{
				$r = '<b>�������:</b><br>'.$t.'<br>'.$r;
				$t = '';
			}
			*/
			
		//�������� ������
			$d = $this->expl($pl['act_date']);
			//���������� � NPS
			if(isset($d['dlg_nps'])) {
				$i7 = 0;
				$x3 = explode(',',$d['dlg_nps']);
				while($i7 < count($x3)) {
					$x4 = explode('=',$x3[$i7]);
					if($x4[0] > 0) {
						$x1 = mysql_fetch_array(mysql_query('SELECT `text` FROM `dungeon_dialog` WHERE `id` = "'.$x4[0].'" LIMIT 1'));
						if(!isset($x1['text'])) {
							$x1 = '<i>����������</i>';
						}else{
							$x1 = $x1['text'];
						}
						$x = 0;							
						$t .= '���������� � <b>'.$x1.'</b>: '.$x.'/1<br>';
					}
					$i7++;
				}
				unset($x1,$x3,$x4,$i7);
			}
				
			//����� �������
			if(isset($d['kill_user']))
			{
				$x = 0;
				
				$t .= '����� �������: '.$x.'/'.$d['kill_user'].'<br>';
				unset($x);
			}
				
			//����� �����
			if(isset($d['kill_bot'])){ 
			    
				
				if( isset($d['all_kill']) && (int)$d['all_kill'] > 0 ) {
					$x = '';
					$ex = explode(',',$d['kill_bot']); 
					$i = 0; # ���������� ������ ��� ������� ���� ����.
					$q='';
					while( $i < count($ex) ){
					   $ex2 = explode('=', $ex[$i]);
					   if($q != '') $q .= ' OR ';
					   $q .= ' (`uid` = "'.$u->info['id'].'" AND `time` > '.$qst['time'].' AND `vars` = "win_bot_'.$ex2[0].'" )';
					   $i++;
					}
					$x2 = $u->testAction($q, 2); $x2 = $x2[0]; 
					if($d['all_kill'] < $x2) $x2 = $d['all_kill']; 
					$bot2 = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$ex2[0].'" LIMIT 1'));
					$bot2['id'] = $bot2['id'];
					if(isset($bot2['id'])){
						$x .= '&nbsp; &nbsp; &bull; <b>'.$bot2['login'].'</b> ['.$x2.'/'.$d['all_kill'].']<br>';
					} else {
						$x .= '&nbsp; &nbsp; &bull; <b>'.$pl['name'].'</b> ['.$x2.'/'.$d['all_kill'].']<br>';
					}
			    } else {
					$i = 0;
					while($i<count($ex)) {

					   $x2 = 0;
					   $ex2 = explode('=',$ex[$i]);					
					   $bot2 = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$ex2[0].'" LIMIT 1'));
					   if(isset($bot2['id'])){

						   if(isset($qst['id'])) {
						   $x2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.$qst['time'].' AND `vars` = "win_bot_'.$ex2[0].'" LIMIT '.$ex2[1],2);
						   $x2 = 0+$x2[0];
						   }
						   
						   $x .= '&nbsp; &nbsp; &bull; <b>'.$bot2['login'].'</b> ['.$x2.'/'.$ex2[1].']<br>';
					   }
					   $i++;
			       }
			    }
			     
			    
			    if($x!='')
			    {
				$x = trim($x,', ');
				$t .= '����� �����: <br>'.$x.'';
			    }
			    unset($x,$ex,$x2,$bot2,$ex2);
			}
			
			//������� �������
				if(isset($d['tk_itm'])) {
					$ex = explode(',',$d['tk_itm']);
					$i = 0;
					$x = '';
					while($i < count($ex)) {
						$ex2 = explode('=',$ex[$i]);
						$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) as count FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
						$x2[0] = (int)$x2[0]; 
						if( $x2[0] >= $ex2[1] ) {
							if( $x2[0] <= ($ex2[1] + round($ex2[1]*0.15)) ){
								$x2[0] = $x2[0];
							} else {
								$x2[0] = ($ex2[1] + round($ex2[1]*0.15));
							}
						}
						$bot2 = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($ex2[0]).'" LIMIT 1'));
						$x .= '&nbsp; &nbsp; &bull; <a target=_blank href=http://xcombats.com/item/'.$bot2['id'].' >'.$bot2['name'].'</a> ['.$x2[0].'/'.$ex2[1].']<br>';
						$i++;
					}
					if($x!='')
					{
						$x = trim($x,', ');
						$t .= '������� �������: <br>'.$x.'';
					}
				}
			
			//��������� �������
				if( isset($d['tkill_itm']) ) {
					$ex = explode(',',$d['tkill_itm']);
					$i = 0;
					$x = '';
					while($i < count($ex)) {
						$ex2 = explode('=',$ex[$i]);
						$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
						$x2[0] = (int)$x2[0]; 
						if( $x2[0] >= $ex2[1] ) {
							if( $x2[0] <= ($ex2[1] + round($ex2[1]*0.15)) ){
								$x2[0] = $x2[0];
							} else {
								$x2[0] = ($ex2[1] + round($ex2[1]*0.15));
							}
						}
						$bot2 = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`img` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($ex2[0]).'" LIMIT 1'));
						$x .= '&nbsp; &nbsp; &bull; <a target=_blank href=http://xcombats.com/item/'.$bot2['id'].' >'.$bot2['name'].'</a> ['.$x2[0].'/'.$ex2[1].']<br>';
						$i++;
					}
					if($x!='')
					{
						$x = trim($x,', ');
						$t .= '������� ������: <br>'.$x.'';
					}
				}
					
			if($t!=''){
				$r = '<br><b>�������� �������:</b><br>'.$t.'<br>'.$r;
				$t = '';
			}
			
			
		if($r==''){
			$r = '�������������� ���������� �� ������� �����������';
		}
		return $r;
	}
	
	public function takeInfo($id)
	{
		global $u;
		$r = '';
		
		return $r;
	}
	
	public function testquest(){
		global $c, $u, $code;
		if($u->info['battle']==0 && $u->room['name']!='����� ������') {
			//$time = mysql_fetch_array( mysql_query('SELECT * FROM `dungeon_room` WHERE `dungeon_room` = "'.$u->info['room'].'" LIMIT 1') ); // �������� ������� � ����������!
			// AND `room` = '.$time['id'].' 
			$sp = mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` LIKE "%start_quest%" AND `vals` = "go" LIMIT 100');
			
			while($pl2 = mysql_fetch_array($sp)) {
				$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.(str_replace('start_quest','',$pl2['vars'])).'" LIMIT 1'));
				
				$g = 1;
				//�������� ������
					$d = $this->expl($pl['act_date']);
					
				//���������� � NPS
					if(isset($d['dlg_nps'])){
						$g = 0;
						unset($x);
					}
					
				//����� �������
				    if(isset($d['kill_user'])){
					    $x = 0;
					    if( $x < $d['kill_user']) {
						    $g = 0;
					    }
					    //$d['kill_user'] - �������� ����� �����
					    unset($x);
				    }
				//����� �����
				    if(isset($d['kill_bot'])) {
						$ex = explode(',',$d['kill_bot']);
						$ii = 0; // ���������� ������ ��� ������� ���� ����.
						$q='';
						while( $ii < count($ex) ) {
							$ex2 = explode('=', $ex[$ii]);
							if($q != '') $q .= ' OR ';
							$q .= ' (`uid` = "'.$u->info['id'].'" AND `time` > '.$pl2['time'].' AND `vars` = "win_bot_'.$ex2[0].'" )';
							$ii++;
						}
						if( isset($d['all_kill']) && $d['all_kill']>0 ) {
							$x2 = $u->testAction($q.' LIMIT '.$d['all_kill'], 2);
							if(isset($d['all_kill']) && $x2[0]<$d['all_kill']){
							$g = 0;
							}    
						} else {
							$x2 = $u->testAction($q.' LIMIT '.$ex2[1],2);
							if(!isset($d['all_kill']) && $x2[0]<$ex2[1]){
							$g = 0;
							}
						}
						unset($x,$ex,$x2,$x3,$bot2,$ex2);
				    }
				//������� �������
					if(isset($d['tk_itm'])) {
					    $ex = explode(',',$d['tk_itm']);
					    $i = 0;
					    while($i < count($ex)) {
							$ex2 = explode('=',$ex[$i]);
							$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
							if( $x2[0] < $ex2[1] ) {
								$g = 0;
							}
							$i++;
					    }
					}
				//��������� �������
					if(isset($d['tkill_itm'])) {
					    $ex = explode(',',$d['tkill_itm']);
					    $i = 0;
					    while($i < count($ex)) {
							$ex2 = explode('=',$ex[$i]);
							$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
							if( $x2[0] < $ex2[1] ) {
								$g = 0;
							}
							$i++;
					    }
					}
				if($g==1){
				    $pl['time'] = $pl2['time'];
				    #$this->endq($pl['id'],'win');
				    #$this->winQuest($pl);
				    //echo $pl['name'];
				}
			}
		}
	}
	
	# ������� �������� �� �������� �� ���������� ������� ��� ��������
	public function questCheckEnd( $pl ) {
		global $u, $c, $magic; 
		$quest = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "start_quest'.$pl['id'].'" ORDER BY `time` DESC LIMIT 1'));
		#��������� �����, �������� = 0 ���, 1 ��.
		$r = 0; 
		if($u->info['battle']==0 && $u->room['name']!='����� ������') { 
			$g = 1;
			# �������� ������
			$d = $this->expl($pl['act_date']);

			# ���������� � NPS
			if( isset($d['dlg_nps']) ) {
				$g = 0;
				unset($x);
			}

			# ����� �������
			if( isset($d['kill_user']) ) {
				$x = 0;
				if( $x < $d['kill_user']) {
					$g = 0;
				} 
				unset($x);
			}

			# ����� �����
			if( isset($d['kill_bot']) ) { 
				$ex = explode(',',$d['kill_bot']); 
				$i = 0; # ���������� ������ ��� ������� ���� ����.
				$q=''; 
				while( $i < count($ex) ){
				   $ex2 = explode('=', $ex[$i]);
				   if($q != '') $q .= ' OR ';
				   $q .= ' (`uid` = "'.$u->info['id'].'" AND `time` > '.$quest['time'].' AND `vars` = "win_bot_'.$ex2[0].'" )';
				   $i++;
				} 
				if( isset($d['all_kill']) && (int)$d['all_kill']>0 ){
					$x2 = $u->testAction($q, 2);
					if( (int)$d['all_kill'] <= $x2[0] ) {
						$x2 = (int)$d['all_kill'];
					} else {
						$x2 = $x2[0];
					}
					if($x2 < (int)$d['all_kill']){
						$g = 0;
					}
				} else {
					$x2 = $u->testAction($q.' LIMIT '.$ex2[1],2);
					if( $ex2[1] < $x2[0] ) {
						$x2 = $ex2[1];
					} else {
						$x2 = $x2[0];
					}
					if($x2 < $ex2[1]){
						$g = 0;
					}
				}
				unset($x,$ex,$x2,$x3,$bot2,$ex2);
			}
			# ������� �������
			if(isset($d['tk_itm'])) {
				$ex = explode(',',$d['tk_itm']);
				$i = 0;
				while( $i < count($ex) ) {
					$ex2 = explode('=',$ex[$i]);
					$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) as count FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
					$x2['count'] =  (int)$x2['count'];
					if( $x2['count'] >= $ex2[1] ) {
						if( $x2['count'] <= ($ex2[1] + round($ex2[1]*0.15)) ) {
							$ex2[1] = $x2['count']; 
						} else {
							$ex2[1] = ($ex2[1] + round($ex2[1]*0.15)); 
						}
					}
					if( $x2[0] < $ex2[1] ) {
						$g = 0;
					}
					$i++;
				}
			}
			# ��������� �������
			if( isset($d['tkill_itm']) ) {
				$ex = explode(',',$d['tkill_itm']);
				$i = 0;
				while( $i < count($ex) ) {
					$ex2 = explode('=',$ex[$i]);
					$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
					$x2['count'] =  (int)$x2['count'];
					if( $x2['count'] >= $ex2[1] ) {
						if( $x2['count'] <= ($ex2[1] + round($ex2[1]*0.15)) ) {
							$ex2[1] = $x2['count']; 
						} else {
							$ex2[1] = ($ex2[1] + round($ex2[1]*0.15)); 
						}
					}
					if( $x2[0] < $ex2[1] ) {
						$g = 0;
					}
					$i++;
				}
			}
			if( $g == 1 ) {
				$r = 1;
			} else $r = 0;
		}
		return $r;
	}
	
	public function questSuccesEnd( $quest , $action ) {
		global $u,$c,$magic;
		$r = '';
		
		if( isset($quest['id']) ) {
			$d = $this->expl($quest['act_date']);
			$d = array_merge($d, $this->expl($quest['win_date']));
			// �������� ������� ��� ������
			if( isset($d['tk_itm']) OR isset($d['tkill_itm']) ) {
				if( isset($d['tk_itm']) AND isset($d['tkill_itm']) ) {
					$ex[0] = explode(',',$d['tk_itm']);
					$ex[1] = explode(',',$d['tkill_itm']); 
				} elseif( isset($d['tk_itm']) ) {
					$ex = explode(',',$d['tk_itm']); 
				} elseif( isset($d['tkill_itm']) ) {
					$ex = explode(',',$d['tkill_itm']);
				}
				
				$i = 0;
				while( $i < count($ex) ) {
					$ex2 = explode('=',$ex[$i]);
					$x2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) as count  FROM `items_users` WHERE `item_id` = "'.mysql_real_escape_string($ex2[0]).'" AND `uid` = "'.$u->info['id'].'" AND (`delete` = 1000 OR `delete` = 0) AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1'));
					$x2['count'] = (int)$x2['count']; 
					if( $x2['count'] >= $ex2[1] ) {
						if( $x2['count'] <= ($ex2[1] + round($ex2[1]*0.15)) ){
							$ex2[1] = $x2['count'];
						} else {
							$ex2[1] = ($ex2[1] + round($ex2[1]*0.15));
						}
					} 
					$d['add_rep'] = (int)$d['add_rep'] * (int)$ex2[1];
					$u->deleteItemID($ex2[0], $u->info['id'], $ex2[1]);
					$i++;
				}
			}
			$t = '';
			
			# �������� ��������� �� �����,
			if( isset($d['add_rep']) ) {
				# ���� ��� �������� �����.
			    if( isset($d['kill_bot']) && isset($d['all_kill']) && $d['add_rep'] > 0 ){
					$ex = explode(',', $d['kill_bot']);
					$ii = 0;
					if($quest['kin'] == 0){
						$d['add_rep'] = 0;
						while( $ii < count($ex) ) {
							$i = 0;
							$ex2 = explode('=',$ex[$i]);
							while( $i < $d['all_kill'] ) {
								$x2 = 0;
								$d['add_rep'] = $d['add_rep'] + $ex2[1];
								$x2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.$action['time'].' AND `vars` = "win_bot_'.$ex2[0].'" LIMIT '.$d['all_kill'],2);
								$i++;
							}
							$ii++;
						}
					} else {
						$d['add_rep'] = 1;
					}
			    } 
			    
			    unset($x,$i,$ii,$ex,$x2,$bot2,$ex2);
			    if( $quest['city'] != '' ) {
					if( $u->stats['silver'] >= 2 ) {
						$d['add_rep'] += $d['add_rep']/100*50;
					}
					$t .= ''.$d['add_rep'].' ��. ��������� '.$u->city_name[$quest['city']].', ';
					$rep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$u->info['id'].'" LIMIT 1'));
					# �������
					if($rep['rep'.$quest['city']] < 10000 && $rep['rep'.$quest['city']] + $d['add_rep'] >= 10000 && $quest['kin'] != 1) {
						$rep['rep'.$quest['city']] = 9999;
					} elseif($rep['rep'.$quest['city']] < 24999 && $rep['rep'.$quest['city']] + $d['add_rep'] >= 24999 && $quest['kin'] != 2) {
						$rep['rep'.$quest['city']] = 24999;
					} else {
						$rep['rep'.$quest['city']] += $d['add_rep'];
					}
					mysql_query('UPDATE `rep` SET `rep'.$quest['city'].'` = "'.$rep['rep'.$quest['city']].'" WHERE `id` = "'.$rep['id'].'" LIMIT 1');
			    } 
			} 
			
			if( isset($d['add_repizlom']) ) {
				$t .= ''.$d['add_repizlom'].' ��. ��������� ������ �����, ';
				$rep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$u->info['id'].'" LIMIT 1'));
				$rep['repizlom'] += $d['add_repizlom'];
				if($rep['repizlom'] > 24999) {
					$rep['repizlom'] = 24999;
				}
				mysql_query('UPDATE `rep` SET `repizlom` = "'.$rep['repizlom'].'" WHERE `id` = "'.$rep['id'].'" LIMIT 1');
			}
			
			if(isset($d['add_eff'])) {
				$i = 0; $j = explode('=',$d['add_eff']);
				while($i < count($j)) {
					if($j[$i] > 0) {
						$magic->add_eff($u->info['id'],$j[$i],1);
					}
					$i++;
				}
			}
			
			if(isset($d['add_cr'])) {
				$t .= ''.$d['add_cr'].' ��., ';
				mysql_query('UPDATE `users` SET `money` = `money`+'.$d['add_cr'].' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}
			
			if($t!='') {
				$t = rtrim($t,', ');
				$r = '������� <b>'.$quest['name'].'</b> ���� ������� ���������! �� �������� �������: '.$t.'.';
				unset($t);
			} else {
				$r = '������� <b>'.$quest['name'].'</b> ���� ������� ���������!';
			}
			
			$r = '<small>'.$r.'</small>';
			//���������� ��������� � ���
			
			mysql_query('UPDATE `actions` SET `vals` = "win" WHERE `id` = "'.$action['id'].'" AND `vals` = "go" LIMIT 1');
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$r."','-1','5','0')");
		}
		
		return $r;
	}

	public function winQuest( $pl ) {
		global $u,$c,$magic;
		/*
		if( isset($pl['id']) ) { 
			$d = $this->expl($pl['act_date']);
			// �������� �������
			if( isset($d['tk_itm']) ) {
				$ex = explode(',',$d['tk_itm']);
				$i = 0;
				while( $i < count($ex) ) {
					$ex2 = explode('=',$ex[$i]);
						$ex2 = explode('=',$ex[$i]);					
						$x2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.$pl2['time'].' AND `vars` = "win_bot_'.$ex2[0].'" LIMIT '.$ex2[1],2);
					$u->deleteItemID($ex2[0],$u->info['id'],$ex2[1]);
					$i++;
				}
			}
			// �������� ������
			if( isset($d['tkill_itm']) ) {
				$ex = explode(',',$d['tkill_itm']);
				$i = 0;
				while( $i < count($ex) ) {
					$ex2 = explode('=',$ex[$i]);
						$ex2 = explode('=',$ex[$i]);					
						$x2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.$pl2['time'].' AND `vars` = "win_bot_'.$ex2[0].'" LIMIT '.$ex2[1],2);
					$u->deleteItemID($ex2[0],$u->info['id'],$ex2[1]);
					$i++;
				}
			}
			$d = array_merge($d, $this->expl($pl['win_date']));
			$t = '';
			if( isset($d['add_rep']) ) { // �������� ��������� �� �����, ���� ��� �������� �����.
			    if( isset($d['kill_bot']) && isset($d['all_kill']) && $d['add_rep'] > 0 ){
					$ex = explode(',', $d['kill_bot']);
					$ii = 0;
					$d['add_rep'] = 0;
					while( $ii < count($ex) ) {
						$i = 0;
						$ex2 = explode('=',$ex[$i]);
						while( $i < $d['all_kill'] ) {
							$x2 = 0;
							$d['add_rep'] = $d['add_rep'] + $ex2[1];
							$x2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.$pl2['time'].' AND `vars` = "win_bot_'.$ex2[0].'" LIMIT '.$d['all_kill'],2);
							$i++;
						}
						$ii++;
					}
			    }
			    unset($x,$i,$ii,$ex,$x2,$bot2,$ex2);
			    if($pl['city']!=''){
					$t .= ''.$d['add_rep'].' ��. ��������� '.$u->city_name[$pl['city']].', ';
					$rep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$u->info['id'].'" LIMIT 1'));
					// �������
					if($rep['rep'.$pl['city']] < 10000 && $rep['rep'.$pl['city']] + $d['add_rep'] >= 10000 && $pl['kin'] != 1) {
						$rep['rep'.$pl['city']] = 9999;
					} elseif($rep['rep'.$pl['city']] < 24999 && $rep['rep'.$pl['city']] + $d['add_rep'] >= 24999 && $pl['kin'] != 2) {
						$rep['rep'.$pl['city']] = 24999;
					} else {
						$rep['rep'.$pl['city']] += $d['add_rep'];
					}
					mysql_query('UPDATE `rep` SET `rep'.$pl['city'].'` = "'.$rep['rep'.$pl['city']].'" WHERE `id` = "'.$rep['id'].'" LIMIT 1');
			    } 
			}
			
			if(isset($d['add_repizlom'])){
				$t .= ''.$d['add_repizlom'].' ��. ��������� ������ �����, ';
				$rep = mysql_fetch_array(mysql_query('SELECT * FROM `rep` WHERE `id` = "'.$u->info['id'].'" LIMIT 1'));
				$rep['repizlom'] += $d['add_repizlom'];
				if($rep['repizlom'] > 24999) {
					$rep['repizlom'] = 24999;
				}
				mysql_query('UPDATE `rep` SET `repizlom` = "'.$rep['repizlom'].'" WHERE `id` = "'.$rep['id'].'" LIMIT 1');
			}
			
			if(isset($d['add_eff'])) {
				$i = 0; $j = explode('=',$d['add_eff']);
				while($i < count($j)) {
					if($j[$i] > 0) {
						$magic->add_eff($u->info['id'],$j[$i],1);
					}
					$i++;
				}
			}
			
			if(isset($d['add_cr'])) {
				$t .= ''.$d['add_cr'].' ��., ';
				mysql_query('UPDATE `users` SET `money` = `money`+'.$d['add_cr'].' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}
			
			if($t!='') {
				$t = rtrim($t,', ');
				$r = '������� <b>'.$pl['name'].'</b> ���� ������� ���������! �� �������� �������: '.$t.'.';
				unset($t);
			} else {
				$r = '������� <b>'.$pl['name'].'</b> ���� ������� ���������!';
			}
			$r = '<small>'.$r.'</small>';
			//���������� ��������� � ���
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$r."','-1','5','0')");
		}
		*/
	}
	
	public function expl($d) {
		$i = 0;
		$e = explode(':|:',$d);
		while($i<count($e))
		{
			$t = explode(':=:',$e[$i]);
			if(isset($t[0]))
			{
				$dr[$t[0]] = $t[1];
			}
			$i++;
		}
		unset($i,$e,$t);
		return $dr;
	}
}

$q = new quests;
?>