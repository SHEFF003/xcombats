<?
if(!defined('GAME'))
{
	die();
}

if($st['usefromfile']=='pirogi' && $u->info['battle'] > 0 && $u->info['hpNow'] >= 1)
{
	if($btl->info['team_win'] != -1 ) {
		$u->error = '������������ ������� �������� ������ �� ����� ���';
	}else{
		$bu = mysql_fetch_array(mysql_query('SELECT * FROM `pirogi` WHERE `btl` = "'.$u->info['battle'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if(isset($bu['id'])) {
			$u->error = '������ ������������ ������� ��� �����! �������� �����: '.$bu['hod'].'';
		}else{			
			
			if( $itm['item_id'] == 4752 ) { //�������� � ����������
				//
				/*
				plain_1s_magic.gif - �������� � /eff/
				//
				������ ��������� �� ���������� ��������� ��� (�� ������ ���� ���)
				��������� �����:  
				� ����� "�������� ����" - ���������� ����. 
				*/
				mysql_query('INSERT INTO `pirogi` (`btl`,`uid`,`time`,`item_id`,`var`,`hod`) VALUES (
					"'.$u->info['battle'].'","'.$u->info['id'].'","'.time().'","'.$itm['item_id'].'","'.$itm['name'].'","1"
				)');
				//
				//$txt = '<font color=#006699><b>'.$txt.'</b></font> ['.$u->stats['hpNow'].'/'.$u->stats['hpAll'].'] ('.$txttest.' ��.)';
				
				$kst = rand(0,7);
				
				$sp81 = mysql_query('SELECT `id` FROM `users` WHERE `battle` = "'.$u->info['battle'].'"');
				while( $pl81 = mysql_fetch_array($sp81) ) {
					$pl82 = mysql_fetch_array(mysql_query('SELECT `id`,`hpNow`,`team` FROM `stats` WHERE `id` = "'.$pl81['id'].'" LIMIT 1'));
					if($pl82['hpNow'] >= 1) {
						$rand_user[] = $pl82['id'];
					}
				}
				
				$rand_user = $rand_user[rand(0,(count($rand_user)-1))];
				$piru = mysql_fetch_array(mysql_query('SELECT `a`.* , `b`.* FROM `users` AS `a` LEFT JOIN `stats` AS `b` ON `a`.`id` = `b`.`id`  WHERE `a`.`id` = "'.mysql_real_escape_string($rand_user).'" LIMIT 1'));
				//
				//$kst = 7;
				//
				if(!isset($piru['id'])) {
					$txt = '<i>(�� ���������, ��� ���������� ����)</i>';
				}elseif( $kst == 0 ) {
					//�������� "������������ �����" - �� ���� ��� ������� ���� 4�� ��������. ���� ������� �� ���-�� ����������. ��������� 1 ���.
					$mgp = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$piru['id'].'" AND `delete` = "0" AND `v2` = "300" LIMIT 1'));
					//
					$txt = '';
					if( isset($mgp['id']) ) {
						$mgp['x']++;
						$mgp['hod']++;
						$txt = ' (x'.$mgp['x'].')';
					}
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>������������ �����'.$txt.'</b>&quot;.';
					if(isset($mgp['id'])) {
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$mgp['hod'].'", `x` = "'.$mgp['x'].'" WHERE `id` = "'.$mgp['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `eff_users` (
						`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`
						) VALUES (
						"1", "300", "elemz.gif", 22, "'.$piru['id'].'", "������������ �����", "atgm='.rand(15,25).'", 0, "77", "priem", "'.$u->info['id'].'"
						)');
					}
				}elseif( $kst == 1 ) {
					//�������� "������ �������" - ������� ������� ���� � ����� ��� ��� ������ ������� ��������� ��� ��������� ���������� �����. ��������� 1 ���. 
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>������ �������</b>&quot;.';
					//
					$txt = '';
					if( isset($mgp['id']) ) {
						$mgp['x']++;
						$mgp['hod']++;
						$txt = ' (x'.$mgp['x'].')';
					}
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>������ �������'.$txt.'</b>&quot;.';
					if(isset($mgp['id'])) {
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$mgp['hod'].'", `x` = "'.$mgp['x'].'" WHERE `id` = "'.$mgp['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `eff_users` (
						`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`
						) VALUES (
						"1", "301", "gy_slickcadavre_dot.gif", 22, "'.$piru['id'].'", "������ �������", "atgm='.rand(15,25).'", 0, "77", "priem", "'.$u->info['id'].'"
						)');
					}
				}elseif( $kst == 2 ) {
					//�������� "�������� ����" - ������� ����, ������ ����� ���������� ����� ��� ����������. ��������� 3 ����. 
					$mgp = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$piru['id'].'" AND `delete` = "0" AND `v2` = "299" LIMIT 1'));
					//
					$txt = '';
					if( isset($mgp['id']) ) {
						$mgp['x']++;
						$mgp['hod']++;
						$txt = ' (x'.$mgp['x'].')';
					}
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>�������� ����'.$txt.'</b>&quot;.';
					if(isset($mgp['id'])) {
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$mgp['hod'].'", `x` = "'.$mgp['x'].'" WHERE `id` = "'.$mgp['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `eff_users` (
						`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`
						) VALUES (
						"3", "299", "gy_slickcadavre_dot.gif", 22, "'.$piru['id'].'", "�������� ����", "atgm='.rand(15,25).'", 0, "77", "priem", "'.$u->info['id'].'"
						)');
					}
				}elseif( $kst == 3 ) {
					//�������� "����� ����������" - ��������� ��������� ���������� ����. ��������� 1 ���. 
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>����� ����������</b>&quot;.';
					//
					$txt = '';
					if( isset($mgp['id']) ) {
						$mgp['x']++;
						$mgp['hod']++;
						$txt = ' (x'.$mgp['x'].')';
					}
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>����� ����������'.$txt.'</b>&quot;.';
					if(isset($mgp['id'])) {
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$mgp['hod'].'", `x` = "'.$mgp['x'].'" WHERE `id` = "'.$mgp['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `eff_users` (
						`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`
						) VALUES (
						"1", "303", "tnpb_magicshield.gif", 22, "'.$piru['id'].'", "����� ����������", "atgm='.rand(15,25).'", 0, "77", "priem", "'.$u->info['id'].'"
						)');
					}
					//
				}elseif( $kst == 4 ) {
					//����� "��������� ���������!" - �������� ����� (����: -10, ��������: -10, ��������: -10, ���������: -10).
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>��������� ���������!</b>&quot;.';
					//
					$mgp = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$piru['id'].'" AND `delete` = "0" AND `v2` = "304" LIMIT 1'));
					//
					$txt = '';
					if( isset($mgp['id']) ) {
						$mgp['x']++;
						$mgp['hod']++;
						$txt = ' (x'.$mgp['x'].')';
					}
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>��������� ���������!'.$txt.'</b>&quot;.';
					$rnds = rand(1,5);
					if( $rnds == 4 ) {
						$rnds = 1;
					}
					if(isset($mgp['id'])) {
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$mgp['hod'].'",`data` = "'.$mgp['data'].'|add_s'.$rnds.'=-10", `x` = "'.$mgp['x'].'" WHERE `id` = "'.$mgp['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `eff_users` (
						`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`
						) VALUES (
						"100", "304", "wis_dark_souleat.gif", 22, "'.$piru['id'].'", "��������� ���������!", "add_s'.$rnds.'=-10", 0, "77", "priem", "'.$u->info['id'].'"
						)');
					}
				}elseif( $kst == 5 ) {
					//����� "�������������" - ����� ��� ��� ���������� ����������� (���. ��. �����: +100). ��������� 3 �������.
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>�������������</b>&quot;.';
					//
					$mgp = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$piru['id'].'" AND `delete` = "0" AND `v2` = "305" LIMIT 1'));
					//
					$txt = '';
					if( isset($mgp['id']) ) {
						$mgp['x']++;
						$mgp['hod']++;
						$txt = ' (x'.$mgp['x'].')';
					}
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>�������������'.$txt.'</b>&quot;.';
					$rnds = rand(1,5);
					if( $rnds == 4 ) {
						$rnds = 1;
					}
					if(isset($mgp['id'])) {
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$mgp['hod'].'", `x` = "'.$mgp['x'].'" WHERE `id` = "'.$mgp['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `eff_users` (
						`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`
						) VALUES (
						"3", "305", "tnbt_bloodrage.gif", 22, "'.$piru['id'].'", "�������������", "add_m14=100", 0, "77", "priem", "'.$u->info['id'].'"
						)');
					}
				}elseif( $kst == 6 ) {
					//����� "���������" - �����. ��������� 1 ���. 
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>���������</b>&quot;.';
					//
					$txt = '';
					if( isset($mgp['id']) ) {
						$mgp['x']++;
						$mgp['hod']++;
						$txt = ' (x'.$mgp['x'].')';
					}
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>�����������'.$txt.'</b>&quot;.';
					if(isset($mgp['id'])) {
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$mgp['hod'].'", `x` = "'.$mgp['x'].'" WHERE `id` = "'.$mgp['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `eff_users` (
						`hod`, `v2`, `img2`, `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `v1`, `user_use`
						) VALUES (
						"1", "302", "gg_macropus_reward.gif", 22, "'.$piru['id'].'", "�����������", "atgm='.rand(15,25).'", 0, "77", "priem", "'.$u->info['id'].'"
						)');
					}
					
				}elseif( $kst == 7 ) {
					//����� "�������� ����" - ���������� ����. 
					ini_set('display_errors','On');
					$txt = ' � ���������� �������� &quot;{u2}&quot; ��� ��������� �������� &quot;<b>�������� ����</b>&quot;.';
					/*
						�����: �������� [10]
					*/
					global $priem;
					$pvr = array();
						//�������� ��� �����
						$pvr['hp'] = rand(15,35);
						$pvr['hp'] = $priem->magatack( $u->info['id'], $piru['id'], $pvr['hp'], '�����', 1 );
						$pvr['promah_type'] = $pvr['hp'][3];
						$pvr['promah'] = $pvr['hp'][2];
						$pvr['krit'] = $pvr['hp'][1];
						$pvr['hp']   = $pvr['hp'][0];
						$pvr['hpSee'] = '--';
						$pvr['hpNow'] = floor($btl->stats[$btl->uids[$piru['id']]]['hpNow']);
						$pvr['hpAll'] = $btl->stats[$btl->uids[$piru['id']]]['hpAll'];
							
						//���������� �������� �� ���� �������
						$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $piru['id'], 21, $pvr['hp'], 8, true );
							
						$pvr['hpSee'] = '-'.$pvr['hp'];
						$pvr['hpNow'] -= $pvr['hp'];
						$btl->priemYronSave($u->info['id'],$piru['id'],$pvr['hp'],0);
							
						if( $pvr['hpNow'] > $pvr['hpAll'] ) {
							$pvr['hpNow'] = $pvr['hpAll'];
						}elseif( $pvr['hpNow'] < 0 ) {
							$pvr['hpNow'] = 0;
						}
							
						$btl->stats[$btl->uids[$piru['id']]]['hpNow'] = $pvr['hpNow'];
							
						mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$piru['id']]]['hpNow'].'" WHERE `id` = "'.$piru['id'].'" LIMIT 1');
							
						$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
						
						//���� ������
						if( $pvr['promah'] == false ) {
							if( $pvr['krit'] == false ) {
								$prv['color2'] = '006699';
								if(isset($btl->mcolor[$btl->mname['�����']])) {
									$prv['color2'] = $btl->mcolor[$btl->mname['�����']];
								}
								$prv['color'] = '000000';
								if(isset($btl->mncolor[$btl->mname['�����']])) {
									$prv['color'] = $btl->mncolor[$btl->mname['�����']];
								}
							}else{
								$prv['color2'] = 'FF0000';
								$prv['color'] = 'FF0000';
							}
						}else{
							$prv['color2'] = '909090';
							$prv['color'] = '909090';
						}
						
						$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
						if( $pvr['promah_type'] == 2 ) {
							$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
							$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
						}
						$btl->priemAddLog( $id, 1, 2, $u->info['id'], $piru['id'],
							'<font color^^^^#'.$prv['color2'].'>�������� ����</font>',
							$prv['text2'],
							($btl->hodID + 1)
						);
						
						//��������� �����
						//$this->addEffPr($pl,$id);
						//$this->addPriem($this->ue['id'],$pl['id'],'atgm='.($pvr['hp']/16).'',2,77,4,$u->info['id'],3,'����������',0,0,1);
						
						//�������� �������
						//$this->mintr($pl);
					
					unset($pvr);
				}else{				
					$txt = '<i>(�� ���������, �������� �'.$kst.')</i>';
				}
				
				if($u->info['sex']==1) {
					$txt = '{u1} ��������� ���������� &quot;<b>'.$itm['name'].'</b>&quot;. '.$txt.'';
				}else{
					$txt = '{u1} �������� ���������� &quot;<b>'.$itm['name'].'</b>&quot;. '.$txt.'';
				}
				$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
				$id_hod = $lastHOD['id_hod'];
				if($lastHOD['type']!=6) {
					if( $kst != 7 ) {
						$id_hod++;
					}
				}
				mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||time1='.time().'||login2='.$piru['login'].'||t2='.$piru['team'].'||time2='.time().'","","","","","6")');
				//
				mysql_query('UPDATE `items_users` SET `iznosNow` = `iznosNow` + 1 WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				mysql_query('DELETE FROM `items_users` WHERE `iznosNOW` >= `iznosMAX` AND `id` = "'.$itm['id'].'" LIMIT 1');
			}elseif( $itm['item_id'] == 1028 ) { //��� ��� (�������� ����� +10% �� ����������� ��)
				//
				mysql_query('INSERT INTO `pirogi` (`btl`,`uid`,`time`,`item_id`,`var`,`hod`) VALUES (
					"'.$u->info['battle'].'","'.$u->info['id'].'","'.time().'","'.$itm['item_id'].'","'.$itm['name'].'","1"
				)');
				//
				$txt = mysql_fetch_array(mysql_query('SELECT SUM(`yrn`) FROM `battle_stat` WHERE `battle` = "'.$u->info['battle'].'" AND `uid2` = "'.$u->info['id'].'" AND `yrn` > 0 LIMIT 1'));
				$txttest = $txt[0];	
				$txt = floor($txt[0]/10);										
				if($u->stats['hpAll']-$u->stats['hpNow'] < $txt) {
					$txt = floor($u->stats['hpAll']-$u->stats['hpNow']);
				}
				if( $txt < 0 ) {
					$txt = 0;
				}
				//
				$u->stats['hpNow'] += $txt;
				if( $u->stats['hpNow'] > $u->stats['hpAll'] ) {
					$u->stats['hpNow'] = $u->stats['hpAll'];
				}
				//
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
				$id_hod = $lastHOD['id_hod'];
				if($lastHOD['type']!=6) {
					$id_hod++;
				}
				if( $txt == 0 ) {
					$txt = '--';
				}else{
					$txt = '+'.$txt;
				}
				$txt = '<font color=#006699><b>'.$txt.'</b></font> ['.$u->stats['hpNow'].'/'.$u->stats['hpAll'].'] ('.$txttest.' ��.)';
				if($u->info['sex']==1) {
					$txt = '{u1} ��������� ���������� &quot;<b>'.$itm['name'].'</b>&quot; � ����������� ��������. '.$txt.'';
				}else{
					$txt = '{u1} �������� ���������� &quot;<b>'.$itm['name'].'</b>&quot; � ����������� ��������. '.$txt.'';
				}
				mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||time1='.time().'","","","","","6")');
				//
				mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				mysql_query('DELETE FROM `items_users` WHERE `iznosNOW` >= `iznosMAX` AND `id` = "'.$itm['id'].'" LIMIT 1');
			}elseif( $itm['item_id'] == 1029 ) {
				//����� ���� (��������������� 100 ��. ����)
				//
				mysql_query('INSERT INTO `pirogi` (`btl`,`uid`,`time`,`item_id`,`var`,`hod`) VALUES (
					"'.$u->info['battle'].'","'.$u->info['id'].'","'.time().'","'.$itm['item_id'].'","'.$itm['name'].'","1"
				)');
				//
				$txt = 100;											
				if($u->stats['mpAll']-$u->stats['mpNow'] < $txt) {
					$txt = floor($u->stats['mpAll']-$u->stats['mpNow']);
				}
				if( $txt < 0 ) {
					$txt = 0;
				}
				//
				$u->stats['mpNow'] += $txt;
				if( $u->stats['mpNow'] > $u->stats['mpAll'] ) {
					$u->stats['mpNow'] = $u->stats['mpAll'];
				}
				//
				mysql_query('UPDATE `stats` SET `mpNow` = "'.$u->stats['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
				$id_hod = $lastHOD['id_hod'];
				if($lastHOD['type']!=6) {
					$id_hod++;
				}
				if( $txt == 0 ) {
					$txt = '--';
				}else{
					$txt = '+'.$txt;
				}
				if($u->stats['mpAll'] < 1) {
					$txt = '<font color=#006699><b>'.$txt.'</b></font> (���� �����������)';
				}else{
					$txt = '<font color=#006699><b>'.$txt.'</b></font> ['.$u->stats['mpNow'].'/'.$u->stats['mpAll'].']';
				}
				if($u->info['sex']==1) {
					$txt = '{u1} ��������� ���������� &quot;<b>'.$itm['name'].'</b>&quot; � ����������� ����. '.$txt.'';
				}else{
					$txt = '{u1} �������� ���������� &quot;<b>'.$itm['name'].'</b>&quot; � ����������� ����. '.$txt.'';
				}
				mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||time1='.time().'","","","","","6")');
				//
				mysql_query('UPDATE `items_users` SET `iznosNow` = `iznosNow` + 1 WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				mysql_query('DELETE FROM `items_users` WHERE `iznosNOW` >= `iznosMAX` AND `id` = "'.$itm['id'].'" LIMIT 1');
			}
			//
		}
	}
}

?>