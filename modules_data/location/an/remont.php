 <?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='an/remont')
{
	//�������� ������� ����� ��������� (���)
	$itm_podgon = array(
		784,
		788,
		1714,
		1239,
		1240
	);
	
	//��������� �������
	$com_podgon = array(
		'�������� ���������' => true,
		'�������� ������� ������' => true,
		'�������� ��������� ������' => true,
		'�������� �����' => true,
		'�������� ���������' => true,
		'�������� �������� ����' => true
	);
	
	$r = 1;
	if(isset($_GET['r']))
	{
		$r = (int)$_GET['r'];
		if($r!=1 && $r!=2 && $r!=3 && $r!=4 && $r!=5 && $r!=6 && $r!=7 && $r!=8 && $r!=9)
		{
			$r = 1;
		}
		if( $r == 6 || $r == 7 || $r == 4 || $r == 3 ) {
			$r = 1;
		}
	}
	
	if(isset($_GET['upgrade']) && true == false) {
		//��������� ��������
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`data` LIKE "%modif%" AND `iu`.`data` NOT LIKE "%upgrade=5%" AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['upgrade']).'" LIMIT 1'));
		if(isset($ir['id'])) {
			$po = array();
			$po = $u->lookStats($ir['data']);
			if($ir['price1']>$ir['1price']) {
				$ir['1price'] = $ir['price1'];
			}
			$pcena = $ir['1price']/2.37;
			
			if($ir['price2']>$ir['2price']) {
				$ir['2price'] = $ir['price2'];
			}
			$pcena2 = ($ir['2price']/2.37)*30;
			if($pcena2 > $pcena) {
				$pcena = $pcena2;
			}
			if(!isset($po['add_s1']) && !isset($po['add_s2']) && !isset($po['add_s3']) && !isset($po['add_s5'])) {
				$pcena = $pcena/2.37;
			}
			$pcena = round($pcena+$pcena/100*(37.795*($po['upgrade']+1)));
			if($pcena == 0) {
				$re = '<div align="left">������ ������� �� ��������...</div>';
			}elseif($pcena <= $u->info['money']) {
				if($po['upgrade'] < 5) {
					$fadd = array(0,0,0,0);
					$faddp = 0;
					if(!isset($po['upgrade']) || $po['upgrade']==0) {
						$ir['1price'] += floor($ir['1price']/100*20);
						$faddp = 6;
					}elseif($po['upgrade'] == 1) {
						$ir['1price'] += floor($ir['1price']/100*30);
						$faddp = 7;
					}elseif($po['upgrade'] == 2) {
						$ir['1price'] += floor($ir['1price']/100*40);
						$faddp = 8;
					}elseif($po['upgrade'] == 3) {
						$ir['1price'] += floor($ir['1price']/100*70);
						$faddp = 10;
					}elseif($po['upgrade'] == 4) {
						$ir['1price'] += floor($ir['1price']/100*10);
						$faddp = 16;
					}
					
					$fadd[0] = $po['add_s1']+$po['add_s2']+$po['add_s3']+$po['add_s5']+$po['mf_stats'];
					$fadd[1] = $po['add_m1']+$po['add_m2']+$po['add_m4']+$po['add_m5']+$po['mf_mod'];
					$fadd[2] = round(($po['add_mab1']+$po['add_mab2']+$po['add_mab3']+$po['add_mab4'])/4+$po['mf_mib']);
					$fadd[3] = $po['add_hpAll'];
					
					if($po['upgrade'] <= 3) {
						$fadd = array(
							round($fadd[0]/100*$faddp), //�����
							floor($fadd[1]/100*$faddp), //��.
							floor($fadd[2]/100*$faddp), //�����
							ceil($fadd[3]/100*$faddp)  //��
						);
					}else{
						$fadd = array(
							ceil($fadd[0]/100*$faddp), //�����
							ceil($fadd[1]/100*$faddp), //��.
							ceil($fadd[2]/100*$faddp), //�����
							ceil($fadd[3]/100*$faddp)  //��
						);
					}
					
					$po['mf_stats']	+= $fadd[0];
					$po['mf_mod']	+= $fadd[1];
					$po['mf_mib']	+= $fadd[2];
					$po['add_hpAll']	+= $fadd[3];
									
					$po['upgrade']++;
					$re = '<div align="left">������� &quot;'.$ir['name'].'&quot; ��� ������� ������� ('.$po['upgrade'].'/5) �� '.$pcena.' ��.</div>';	

					$u->addDelo(2,$u->info['id'],'&quot;<font color="#4863A0">System.remont.itemUpgrade</font>&quot;: ������� &quot;'.$ir['name'].'&quot; [itm:'.$ir['id'].'] ��� ������� ������� ('.$po['upgrade'].'/5) �� '.$pcena.' ��..',time(),$u->info['city'],'System.remont.itemUpgrade',0,0);
					
					$po = $u->impStats($po);					
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'",`1price` = "'.$ir['1price'].'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `money` = `money` - "'.$pcena.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->info['money'] -= $pcena;
					
				}else{
					$re = '<div align="left">������� ������� �� ���������</div>';
				}
			}else{
				$re = '<div align="left">� ��� �� ���������� ������� ��� ����������� ��������</div>';
			}
		}else{
			$re = '<div align="left">���������� ������� �� ������ � ���������</div>';
		}
	}elseif(isset($_GET['modif']) && true == false) {
		//�����������
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND
		`iu`.`data` NOT LIKE "%modif%" AND `im`.`type` > 0 AND `im`.`type` < 16 AND	(`iu`.`data` LIKE "%add_s1%" OR `iu`.`data` LIKE "%add_s2%" OR `iu`.`data` LIKE "%add_s3%" OR `iu`.`data` LIKE "%add_s5%" OR `iu`.`data` LIKE "%add_hpAll%" OR `iu`.`data` LIKE "%add_mib%")
		AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['modif']).'" LIMIT 1'));
		if(isset($ir['id'])) {
			$po = array();
			$po = $u->lookStats($ir['data']);
			if($ir['price1']>$ir['1price']) {
				$ir['1price'] = $ir['price1'];
			}
			$pcena = $ir['1price']/2;
			
			if($ir['price2']>$ir['2price']) {
				$ir['2price'] = $ir['price2'];
			}
			$pcena2 = ($ir['2price']/2.37)*30;
			if($pcena2 > $pcena) {
				$pcena = $pcena2;
			}
			
			if(!isset($po['add_s1']) && !isset($po['add_s2']) && !isset($po['add_s3']) && !isset($po['add_s5'])) {
				$pcena = $pcena/2;
			}
			$pcena = round($pcena);
			if($pcena == 0) {
				$re = '<div align="left">������ ������� �� ��������...</div>';
			}elseif($pcena <= $u->info['money']) {
				if($u->stats['s5'] > 24) {
					$fadd = array(0,0,0);
					
					//��������� �����
					if(isset($po['add_s1']) || isset($po['add_s2']) || isset($po['add_s3']) || isset($po['add_s5'])) {
						$rnd1 = rand(0,(1000-$u->stats['s5']));
						if($rnd1 > 500) {
							$rnd2 = rand(0,(1000-$u->stats['s5']));
							if($rnd2 > 500) {
								$rnd3 = rand(0,(1000-$u->stats['s5']));
								if($rnd3 > 500) {
									//3
									$fadd[0] = rand(0,1);
								}else{
									//1
									$fadd[0] = 0;
								}
							}else{
								//2
								$fadd[0] = 0;								
							}
						}else{
							//1
							$fadd[0] = 0;
						}
					}
					
					//��������� ��
					if(isset($po['add_m1']) || isset($po['add_m2']) || isset($po['add_m4']) || isset($po['add_m5'])) {
						$rnd1 = rand(0,(1000-$u->stats['s5']));
						if($rnd1 > 500) {
							$rnd2 = rand(0,(1000-$u->stats['s5']));
							if($rnd2 > 500) {
								$rnd3 = rand(0,(1000-$u->stats['s5']));
								if($rnd3 > 500) {
									//3
									$fadd[1] = 10;
								}else{
									//1
									$fadd[1] = 1;
								}
							}else{
								//2
								$fadd[1] = 7;								
							}
						}else{
							//1
							$fadd[1] = 1;
						}
						$fadd[1] = rand($fadd[1],20);
					}
					
					//��������� �����
					if(isset($po['add_mib1']) || isset($po['add_mib2']) || isset($po['add_mib3']) || isset($po['add_mib4'])) {
						$rnd1 = rand(0,(1000-$u->stats['s5']));
						if($rnd1 > 500) {
							$rnd2 = rand(0,(1000-$u->stats['s5']));
							if($rnd2 > 500) {
								$rnd3 = rand(0,(1000-$u->stats['s5']));
								if($rnd3 > 500) {
									//3
									$fadd[2] = 3;
								}else{
									//1
									$fadd[2] = 1;
								}
							}else{
								//2
								$fadd[2] = 2;								
							}
						}else{
							//1
							$fadd[2] = 1;
						}
					}					
					
					if($fadd[0] > 0) {
						//�����
						$po['mf_stats'] += $fadd[0];
					}
					if($fadd[1] > 0) {
						//��
						$po['add_hpAll'] += $fadd[1];
					}
					if($fadd[2] > 0) {
						//�����
						$po['mf_mib'] += $fadd[2];
					}					
					$po['modif'] = 1;					
					$po = $u->impStats($po);
					
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'",`1price` = "'.$ir['1price'].'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `money` = `money` - "'.$pcena.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->info['money'] -= $pcena;
					
					$re = '<div align="left">������� &quot;'.$ir['name'].'&quot; ��� ������� ������������� (��������������: +'.$fadd[0].', ��������: +'.$fadd[1].', �����: +'.$fadd[2].') �� '.$pcena.' ��.</div>';
					
				}else{
					$re = '<div align="left">��� ��������������� �������� ��������� �������������� ��������: 25</div>';
				}
			}else{
				$re = '<div align="left">� ��� �� ���������� ������� ��� ����������� ��������</div>';
			}
		}else{
			$re = '<div align="left">���������� ������� �� ������ � ���������</div>';
		}
	}elseif(isset($_GET['ubeff']) && true == false) {
		// ����� � ���������� ������� �������.
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`data` LIKE "%upatack_id%" AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['ubeff']).'" LIMIT 1'));
		
		// ������� ����������.
		if(isset($ir['id'])) {
			$po = array();
			$po = $u->lookStats($ir['data']); // ��� �� ������ �������������� ��������.
			if($po['tr_lvl'] > $ir['level']) {
				$pl['level'] = $po['tr_lvl']; // ���� ������� ��������, ������ ��� ������� ���������� � �������������, �� ���� ���������� ������ ��������.
			}
			
			$pcena = 5*$pl['level']+35; // ���� �������������, ��� ������� � ���. ���.
			
			if(isset($po['rune_id'])) { // ���� � �������� ���������� ����, ����������� ����.
				$pcena += 3;
			}
			
			if(isset($po['upatack_id'])) { // ���� � �������� ������� �������, ����������� ����.
				$pcena += 14;
			}
			/*
			if($po['rune_id'] > 0){ // ������������������ ��������.
				$re = '<div align="left">��� ������������� ������� ��������� ����</div>';
			} else
			*/
			if($u->info['money'] >= 100) { // ��������, ������� �� �������.
				$pcena -= 14; // ��������� ���� ����� ����������� �������.
				if(isset($po['upatack_id'])) { // ��������� ������� � ���������. 
					// ����� � ���������� ������ �������, ������� ��������� � ��������.
					$upattack = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.mysql_real_escape_string((int)$po["upatack"]).'" LIMIT 1'));
					if(isset($upattack) && $upattack['item_id'] && $upattack['delete']>0){ // ���� ������ ������� �� ������ �� ����, �� ��� ���������������.
						mysql_query('UPDATE `items_users` SET `delete` = "'.mysql_real_escape_string(0).'" WHERE `id` = "'.$po["upatack"].'" LIMIT 1');
						mysql_query('UPDATE `items_users` SET `data` = CONCAT(`data`,"|nosale=1") WHERE `id` = "'.$po["upatack"].'" LIMIT 1');
					} else { // ���� ������ ������� �� ����������, ������� �����.
						$u->addItem($po['upatack_id'],$u->info['id'],'|fromshop=1|nosale=1', NULL, NULL, true);
						$irs .= ','.$po['upatack_name']; 
					}
				}
				
				if(!isset($upattack['data'])) {
					$upattack = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string((int)$po["upatack_id"]).'" LIMIT 1'));
				}
				
				
				// �������� ���� � ����������� ������ - $ir['item_id']
				$item_default = mysql_fetch_array(mysql_query('SELECT `data` FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string((int)$ir['item_id']).'" LIMIT 1'));
				$item_default = $u->lookStats($item_default['data']);
				$upattack = $u->lookStats($upattack['data']);
				if( $upattack['uptype'] == 22 ) {
					$po['add_m11'] -= $upattack['upatack']*2;
					$po['tr_lvl'] = $item_default['tr_lvl'];
					if(isset($po['add_m11']) && $po['add_m11']==0) unset($po['add_m11']);
				}else{
					$po['sv_yron_min'] -= $upattack['upatack'];
					$po['sv_yron_max'] -= $upattack['upatack'];
					$po['tr_lvl'] = $item_default['tr_lvl'];
				}
				unset($po["upatack"]);
				unset($po["upatack_id"]);
				unset($po["upatack_name"]);
				unset($po["upatack_lvl"]);
				unset($item_default);
				unset($upattack);
				$po = $u->impStats($po);
				
				// ������� tr_lvl=4|tr_s5=10|tr_mg7=4|uptype=21|upatack=4|tr_a4=4|srok=432000
				/*
				if(isset($po['spell_id'])) {
					//���������� �����
					$u->addItem($po['spell_id'],$u->info['id']);
					$irs .= ','.$po['spell_name'];
				}
				
				//��������� �������� (����� ��������� , ����������)
				
				$pon = '';
				if(isset($po['gravi'])) {
					$pon .= '|gravi='.$po['gravi'].'|gravic='.$po['gravic'].'';
				}
				if(isset($po['nosale'])) {
					$pon .= '|nosale='.$po['nosale'].'';
				}
				if(isset($po['frompisher'])) {
					$pon .= '|frompisher='.$po['frompisher'].'';
				}
				if(isset($po['fromlaba'])) {
					$pon .= '|fromlaba='.$po['fromlaba'].'';
				}
				if(isset($po['noremont'])) {
					$pon .= '|noremont='.$po['noremont'].'';
				}
				if(isset($po['sudba'])) {
					$pon .= '|sudba='.$po['sudba'].'';
				}
				if(isset($po['zazuby'])) {
					$pon .= '|zazuby='.$po['zazuby'].'';
				}
				if(isset($po['fromshop'])) {
					$pon .= '|fromshop='.$po['fromshop'].'';
				}
				if(isset($po['icos'])) {
					$pon .= '|icos='.$po['icos'].'';
				}
				*/
				
				/*
				$iidis = $u->addItem($ir['item_id'],$u->info['id'],$pon);
				mysql_query('UPDATE `items_users` SET 
				`iznosMAX` = "'.$ir['iznosMAX'].'",
				`iznosNOW` = "'.$ir['iznosNOW'].'",
				`1price` = "'.$ir['1price'].'",
				`2price` = "'.$ir['2price'].'",
				`3price` = "'.$ir['3price'].'",
				`gift` = "'.$ir['gift'].'",
				`gtxt1` = "'.$ir['gtxt1'].'",
				`gtxt2` = "'.$ir['gtxt2'].'",
				`maidin` = "'.$ir['maidin'].'",
				`time_create` = "'.$ir['time_create'].'"
				WHERE `id` = "'.$iidis.'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
				
				$u->info['money'] -= $pcena;
				$re = '<div align="left">������� &quot;'.$ir['name'].'&quot; ��� ������� ��������������� ('.$ir['name'].''.$irs.') �� '.$pcena.' ��.</div>';
				*/
				var_info($po);
				mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
				mysql_query('UPDATE `users` SET `money` = `money` - 100 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				
			}else{
				$re = '<div align="left">� ��� �� ���������� ������� ��� �������������</div>';
			}
		}else{
			$re = '<div align="left">���������� ������� �� ������ � ���������</div>';
		}
	}elseif(isset($_GET['unrune']) && true == false) {
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`data` LIKE "%rune_id%" AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['unrune']).'" LIMIT 1'));
		if(isset($ir['id']) ) {
			$po = array();
			$po = $u->lookStats($ir['data']);
			if(isset($po['noremont']) or isset($st['frompisher'])){
				$re2 = '������� �� �������� ���������� ���.';
			} else {
				if($po['tr_lvl'] > $ir['level']) {
					$ir['level'] = $po['tr_lvl'];
				}
				
				$pcena = 10*$ir['level']+40;
				if($pcena <= $u->info['money']) {
					$iro = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$po['rune_id'].'" LIMIT 1'));
					$ro = $u->lookStats($iro['data']);
					$restat = array();
					
					$i = 0;
					while($i<count($u->items['add'])) {
						if(isset($ro['add_'.$u->items['add'][$i]])) {
							$po['add_'.$u->items['add'][$i]] -= $ro['add_'.$u->items['add'][$i]];
							if($po['add_'.$u->items['add'][$i]] == 0) {
								unset($po['add_'.$u->items['add'][$i]]);
							}
						}
						$i++;
					}	
						
					$u->addItem($po['rune_id'],$u->info['id']);
					$re = '<div align="left">���� &quot;'.$po['rune_name'].'&quot; ���� ������� ��������� �� �������� &quot;'.$ir['name'].'&quot; �� '.$pcena.' ��.</div>';
					$u->addDelo(2,$u->info['id'],'&quot;<font color="#4863A0">System.remont.unrune</font>&quot;: ���� &quot;'.$po['rune_name'].'&quot; ���� ������� ��������� �� �������� &quot;'.$ir['name'].'&quot; [itm:'.$ir['id'].'] �� '.$pcena.' ��.',time(),$u->info['city'],'System.remont.unrune',0,0);
					unset($po['rune'],$po['rune_id'],$po['rune_name'],$po['rune_lvl']);
					$po = $u->impStats($po);
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `money` = `money` - "'.$pcena.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->info['money'] -= $pcena;
					
				}else{
					$re = '<div align="left">� ��� �� ���������� ������� ��� ����������</div>';
				}
			}
		}else{
			$re = '<div align="left">���������� ������� �� ������ � ���������</div>';
		}
	} elseif(isset($_GET['un_grav'])) {
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`im`.`type` = "18" OR `im`.`type` = "19" OR `im`.`type` = "20" OR `im`.`type` = "21" OR `im`.`type` = "22" OR `im`.`type` = "23" OR `im`.`type` = "24" OR `im`.`type` = "26" OR `im`.`type` = "27" OR `im`.`type` = "28") AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['un_grav']).'" LIMIT 1'));
	if(isset($ir['id'])) {
			$pcena = 30;			
			if($ir['type'] == 22) {
				$pcena = 35;
			}elseif($ir['type'] == 18) {
				$pcena = 15;
			}
	  if($pcena <= $u->info['money']) {
				$po = array();
				$po = $u->lookStats($ir['data']);
				$po['gravi'] = substr($_GET['grav_text'],0, 20);
				$po['gravic'] = $u->info['city'];
				$po['gravi'] = str_replace('=','',$po['gravi']);
				$po['gravi'] = str_replace('|','',$po['gravi']);
				$tst = str_replace(' ','',$po['gravi']);
				$tst = str_replace('	','',$po['gravi']);
				$po['gravi'] = preg_replace("/[^a-zA-Z�-��-�0-9\s]/", "", $po['gravi']);
				if($po['gravi'] != '' && $tst != '') {
					$po = $u->impStats($po);
					mysql_query('UPDATE `users` SET `money` = `money` - "'.$pcena.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
					$re = '<div align="left">�� �������� &quot;'.$ir['name'].'&quot; ��� ������� ������������ ����� �� '.$pcena.' ��.</div>';
				}else{
					$re = '<div align="left">������ �����, ���� ������� �� �������� ������� ������ ������������</div>';
				}
			}else{
				$re = '<div align="left">� ��� �� ���������� ������� ��� ����������</div>';
			}
		}else{
			$re = '<div align="left">���������� ������� �� ������ � ���������</div>';
		}
	}elseif(isset($_GET['grav'])) {
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`im`.`type` = "18" OR `im`.`type` = "19" OR `im`.`type` = "20" OR `im`.`type` = "21" OR `im`.`type` = "22" OR `im`.`type` = "23" OR `im`.`type` = "24" OR `im`.`type` = "26" OR `im`.`type` = "27" OR `im`.`type` = "28") AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['grav']).'" LIMIT 1'));
		if(isset($ir['id'])) {
			$pcena = 30;			
			if($ir['type'] == 22) {
				$pcena = 35;
			}elseif($ir['type'] == 18) {
				$pcena = 15;
			}
			if($pcena <= $u->info['money']) {
				$po = array();
				$po = $u->lookStats($ir['data']);
				$po['gravi'] = substr($_GET['grav_text'],0, 20);
				$po['gravic'] = $u->info['city'];
				$po['gravi'] = str_replace('=','',$po['gravi']);
				$po['gravi'] = str_replace('|','',$po['gravi']);
				$tst = str_replace(' ','',$po['gravi']);
				$tst = str_replace('	','',$po['gravi']);
				$po['gravi'] = preg_replace("/[^a-zA-Z�-��-�0-9\s]/", "", $po['gravi']);
				if($po['gravi'] != '' && $tst != '') {
					$po = $u->impStats($po);
					mysql_query('UPDATE `users` SET `money` = `money` - "'.$pcena.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
					$re = '<div align="left">�� �������� &quot;'.$ir['name'].'&quot; ��� ������� ������������ ����� �� '.$pcena.' ��.</div>';
				}else{
					$re = '<div align="left">������ �����, ���� ������� �� �������� ������� ������ ������������</div>';
				}
			}else{
				$re = '<div align="left">� ��� �� ���������� ������� ��� ����������</div>';
			}
		}else{
			$re = '<div align="left">���������� ������� �� ������ � ���������</div>';
		}
	}elseif(isset($_GET['podgon'])) {
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `im`.`inslot` = 5 AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['podgon']).'" LIMIT 1'));
		if(isset($ir['id'])) {
			$po = array();
			$po = $u->lookStats($ir['data']);
			if(!isset($po['podgon'])) {
				if($po['tr_lvl']>$ir['level']) {
					$ir['level'] = $po['tr_lvl'];
				}
				$pcena = 5*$ir['level']+10;
				if($pcena <= $u->info['money']) {
					$prhp = 6*$ir['level']+6;
					$po['podgon'] = 1;
					$po['add_hpAll'] += $prhp;
					$po['sudba'] = $u->info['login'];
					$po = $u->impStats($po);
					mysql_query('UPDATE `users` SET `money` = `money` - "'.$pcena.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `items_users` SET `data` = "'.$po.'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
					$re = '<div align="left">������� &quot;'.$ir['name'].'&quot; ��� ������� �������� �� '.$pcena.' ��. (��������� +'.$prhp.'HP)</div>';
				}else{
					$re = '<div align="left">� ��� �� ���������� ������� ��� ��������</div>';
				}
			}else{
				$re = '<div align="left">������� ��� ��� ��������</div>';
			}
		}else{
			$re = '<div align="left">���������� ������� �� ������ � ���������</div>';
		}
	}elseif(isset($_GET['remon']))
	{
		$t = 1;
		if($_GET['t']==2)
		{
			$t = 2;	
		}elseif($_GET['t']==3)
		{
			$t = 3;	
		}
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`iznosNOW` >= 1 AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['remon']).'" LIMIT 1'));
		
		$rem_price=round($ir['price1']*0.06/100,2);//���� ������� �� 1�� �������
		$rem_all=round($ir['price1']*$ir['iznosNOW']*0.06/100,2);//���� ������� full
		
		if( $u->stats['silver'] >= 5 ) {
			$rem_price = round($rem_price/100*50,2);
			$rem_all = round($rem_all/100*50,2);
		}
		
		if($rem_price<0.01){$rem_price=0.01;} //��� �������
		if($rem_all<0.01){$rem_all=0.01;} //��� �������
		
		if(isset($ir['id']))
		{
			$po = $u->lookStats($ir['data']);
			if(isset($po['noremont']))
			{
				$re2 = '������� �� �������� ������� � ���� ����������.';
			}else{
				if($t == 1)
				{
				    $rem_cell=$rem_price;
					$t = 0;
					$rm = 1;
					$re2 .= ' 1 ��.';
				}elseif($t == 2)
				{
					$t = 0;
					$rm = 10;
					$rem_cell=$rem_price*10;
					$re2 .= ' 10 ��.';
				}elseif($t == 3)
				{
				    $rem_cell=$rem_all;
					$t = 0;
					$rm = $ir['iznosNOW'];
					$re2 .= $rm. ' ��.';
				}
				//$priceRemAll=round($rem_price*$rm,2);
				if($rem_cell>$u->info['money'])
				{
					$re2 = '� ��� ������������ ������ ��� �������.';
				}else{
					if($rm > $ir['iznosNOW'])
					{
						$rm = $ir['iznosNOW'];
						//$priceRemAll=round($rem_price*$rm,2);
					}
					$ir['iznosNOW'] -= $rm;
					if($ir['iznosNOW']<0)
					{
						$ir['iznosNOW'] = 0;
					}
					$u->info['money'] -= $rem_cell;	
					$upd = mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');	
					if($upd)
					{
						$il = $ir['iznosMAX'];
						//$ir['iznosMAX'] -= $ir['iznosMAX']/100000*rand(10,700);
						if( rand(0,100) < 11 ) {
							$ir['iznosMAX'] -= 1;
						}else{
							$ir['iznosMAX'] -= $ir['iznosMAX']/100000*rand(10,700);
						}
						if($ir['iznosMAX']<1)
						{
							$ir['iznosMAX'] = 1;
						}
						mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$ir['iznosNOW'].'",`iznosMAX` = "'.$ir['iznosMAX'].'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');	
						$re2 = '������ ���������� ������ '.$re2.' �������� &quot;'.$ir['name'].'&quot; �� '.$rem_cell.' ��.';	
						$dop = 0;
						if(ceil($il)>ceil($ir['iznosMAX']))
						{
							$re2 .= '<br>� ���������, ������������ ������������� �������� ��-�� ������� �����������.';
							$dop = 1;
						}
						$u->addDelo(2,$u->info['id'],'&quot;<font color="grey">System.remont</font>&quot;: ������� &quot;'.$ir['name'].'&quot; [itm:'.$ir['id'].'] ��� <b>��������������</b>, ������������ ������������� �����������: '.$dop.' ('.$rm.' ��. �� '.$t.' ��.).',time(),$u->info['city'],'System.remont',0,0);
					}else{
						$re2 = '���-�� ����� �� ���...';
					}
				}
			}
		}else{
			$re2 = '������� ���������� ��� ������� �� ������ � ���������';	
		}
	}elseif(isset($_GET['remonz']))
	{
		$t = 1;
		if($_GET['t']==2)
		{
			$t = 2;	
		}elseif($_GET['t']==3)
		{
			$t = 3;	
		}
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`iznosNOW` >= 1 AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['remonz']).'" LIMIT 1'));
		
		$rem_price=round($ir['price1']*0.06/100,2);//���� ������� �� 1�� �������
		$rem_all=round($ir['price1']*$ir['iznosNOW']*0.06/100,2);//���� ������� full
		if($rem_price<0.01){$rem_price=0.01;} //��� �������
		
		if(isset($ir['id']))
		{
			$po = $u->lookStats($ir['data']);
			if(isset($po['noremont']))
			{
				$re2 = '������� �� �������� ������� � ���� ����������.';
			}else{
				
				if( $ir['1price'] > 0 ) {
					$po['zazuby'] = $ir['1price'];
				}else{
					$po['zazuby'] = $ir['price1'];
				}
				$po['nosale'] = 1;
				
				if($t == 1)
				{
				    $rem_cell=$rem_price;
					$t = 0;
					$rm = 1;
					$re2 .= ' 1 ��.';
				}elseif($t == 2)
				{
					$t = 0;
					$rm = 10;
					$rem_cell=$rem_price*10;
					$re2 .= ' 10 ��.';
				}elseif($t == 3)
				{
				    $rem_cell=$rem_all;
					$t = 0;
					$rm = $ir['iznosNOW'];
					$re2 .= $rm. ' ��.';
				}
				//$priceRemAll=round($rem_price*$rm,2);
				if($rem_cell>$u->info['money4'])
				{
					$re2 = '� ��� ������������ ����� ��� �������.';
				}else{
					if($rm > $ir['iznosNOW'])
					{
						$rm = $ir['iznosNOW'];
						//$priceRemAll=round($rem_price*$rm,2);
					}
					$ir['iznosNOW'] -= $rm;
					if($ir['iznosNOW']<0)
					{
						$ir['iznosNOW'] = 0;
					}
					$u->info['money4'] -= $rem_cell;	
					$upd = mysql_query('UPDATE `users` SET `money4` = "'.$u->info['money4'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');	
					if($upd)
					{
						$il = $ir['iznosMAX'];
						//$ir['iznosMAX'] -= $ir['iznosMAX']/100000*rand(10,700);
						if( rand(0,100) < 11 ) {
							$ir['iznosMAX'] -= 1;
						}else{
							$ir['iznosMAX'] -= $ir['iznosMAX']/100000*rand(10,700);
						}
						if($ir['iznosMAX']<1)
						{
							$ir['iznosMAX'] = 1;
						}
						$po = $u->impStats($po);
						mysql_query('UPDATE `items_users` SET `data` = "'.$po.'",`iznosNOW` = "'.$ir['iznosNOW'].'",`iznosMAX` = "'.$ir['iznosMAX'].'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');	
						$re2 = '������ ���������� ������ '.$re2.' �������� &quot;'.$ir['name'].'&quot; �� '.$u->zuby($rem_cell,1).'.';	
						$dop = 0;
						if(ceil($il)>ceil($ir['iznosMAX']))
						{
							$re2 .= '<br>� ���������, ������������ ������������� �������� ��-�� ������� �����������.';
							$dop = 1;
						}
						//
						//
						$u->addDelo(2,$u->info['id'],'&quot;<font color="grey">System.remont</font>&quot;: ������� &quot;'.$ir['name'].'&quot; [itm:'.$ir['id'].'] ��� <b>��������������</b>, ������������ ������������� �����������: '.$dop.' ('.$rm.' ��. �� '.$t.' �����.).',time(),$u->info['city'],'System.remont',0,0);
					}else{
						$re2 = '���-�� ����� �� ���...';
					}
				}
			}
		}else{
			$re2 = '������� ���������� ��� ������� �� ������ � ���������';	
		}
	}elseif(isset($_GET['upgradelvl'])) {
		$ir = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string((int)$_GET['upgradelvl']).'" LIMIT 1'));
		if(isset($ir['id'])) {
			$ur = mysql_fetch_array(mysql_query('SELECT * FROM `items_upgrade` WHERE `iid` = "'.$ir['item_id'].'" LIMIT 1'));
			if(isset($ur['id'])) {
				if($ur['price1'] > 0 && $u->info['money'] < $ur['price1']) {
					$re2 = '������������ ��. � ���������';
				}elseif($ur['price2'] > 0 && $u->bank['money2'] < $ur['price2']) {
					$re2 = '������������ ���. �� �����, ��������� ���������� ����';
				}else{
					$ui1 = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$ur['iup'].'" LIMIT 1'));
					$ui2 = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.$ur['iup'].'" LIMIT 1'));
					if(isset($ui1['id'],$ui2['id'])) {
						mysql_query('UPDATE `items_users` SET `item_id` = "'.$ui1['id'].'",`1price` = "'.$ui1['price1'].'",`2price` = "'.$ui1['price2'].'",`data` = "'.$ui2['data'].'" WHERE `id` = "'.$ir['id'].'" LIMIT 1');
						$re2 = '������� &quot;'.$ir['name'].'&quot; ��� ������� ������� �� ���������� ������ �� '.$ur['price2'].' ���.';
						$u->bank['money2'] -= $ur['price2'];
						mysql_query('UPDATE `bank` SET `money2` = "'.$u->bank['money2'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
					}else{
						$re2 = '��������� �������� ������ �������';
					}
				}
			}else{
				$re2 = '������� ���������� ��� ��������� �� ������';
			}
		}else{
			$re2 = '������� ���������� ��� ��������� �� ������ � ���������';
		}
	}
	
	$see = '';
	if($r==1){
		//������ ���������
		$see = $u->genInv(4,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`iznosNOW` >= 1 ORDER BY `lastUPD` DESC');
		$see = $see[2];
	}elseif($r==5){
		//�������� �����
		$see = $u->genInv(56,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `im`.`inslot` = "5" AND `iu`.`data` NOT LIKE "%podgon%" ORDER BY `lastUPD` DESC');
		$see = $see[2];
	}elseif($r==2){
		//���������� ������
		$see = $u->genInv(57,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`im`.`type` = "18" OR `im`.`type` = "19" OR `im`.`type` = "20" OR `im`.`type` = "21" OR `im`.`type` = "22" OR `im`.`type` = "23" OR `im`.`type` = "24" OR `im`.`type` = "26" OR `im`.`type` = "27" OR `im`.`type` = "28") ORDER BY `lastUPD` DESC');
		$see = $see[2];
	}elseif($r==4){
		//������������ ���
		$see = $u->genInv(58,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`data` LIKE "%rune_id%" ORDER BY `lastUPD` DESC');
		$see = $see[2];
	}elseif($r==3){
		//�������������
		$see = $u->genInv(59,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`iu`.`data` LIKE "%upatack_id%") ORDER BY `lastUPD` DESC');
		$see = $see[2];
	}elseif($r==7){
		//�����������
		$see = $u->genInv(60,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`data` NOT LIKE "%modif%" AND `im`.`type` > 0 AND `im`.`type` < 16 AND
		(`iu`.`data` LIKE "%add_s1%" OR `iu`.`data` LIKE "%add_s2%" OR `iu`.`data` LIKE "%add_s3%" OR `iu`.`data` LIKE "%add_s5%" OR `iu`.`data` LIKE "%add_hpAll%" OR `iu`.`data` LIKE "%add_mib%") ORDER BY `lastUPD` DESC');
		$see = $see[2];
	}elseif($r==6) {
		//��������
		$see = $u->genInv(61,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`data` LIKE "%modif%" AND `iu`.`data` NOT LIKE "%upgrade=5%" ORDER BY `lastUPD` DESC');
		$see = $see[2];
	}elseif($r==8) {
		if(isset($u->bank['id'])) {
			//���������
			$see = $u->genInv(62,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (SELECT `id` FROM `items_upgrade` WHERE `iid` = `iu`.`item_id` AND `activ` = 1 LIMIT 1) > 0 ORDER BY `lastUPD` DESC');
			$see = $see[2];
		}
	}elseif($r==9) {
		if(isset($u->bank['id']) && !isset($_GET['upgradelvlcom'])) {
			//�������� ��� ��������
			$itmos = '';
			$i = 0;
			while( $i < count($itm_podgon) ) {
				$itmos .= ' OR `iu`.`item_id` = "'.$itm_podgon[$i].'"';
				$i++;
			}
			//$itmos = ltrim($itmos,' OR ');
			$see = $u->genInv(63,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`iu`.`data` LIKE "%|art=1%" '.$itmos.')');
			$see = $see[2];
		}else{
			//�������� ��� ��������
			$itmos = '';
			$i = 0;
			while( $i < count($itm_podgon) ) {
				$itmos .= ' OR `iu`.`item_id` = "'.$itm_podgon[$i].'"';
				$i++;
			}
			//$itmos = ltrim($itmos,' OR ');
			$see = $u->genInv(64,'`iu`.`id` = "'.mysql_real_escape_string($_GET['upgradelvlcom']).'" AND `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`iu`.`data` LIKE "%|art=1%" '.$itmos.')');
			$see = $see[2];
		}
	}
	
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
    
	<style type="text/css"> 
	
	.pH3 { COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}	
	td {
	text-align: center;
}
    </style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3">��������� ����������</div>
	<?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td><table width="100%" border="0" cellpadding="2" cellspacing="0" bgcolor="d2d2d2">
	      <tr>
	        <td><b>&nbsp;&nbsp;����:&nbsp;</b></td>
	        <td nowrap="nowrap" align="center" <? if($r==1){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==1){ echo '<b>������</b>'; }else{ echo '<a href="?r=1&rnd='.$code.'">������</a>'; } ?>&nbsp;&nbsp;</td>
	        <td nowrap="nowrap" align="center" <? if($r==2){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==2){ echo '<b>����������</b>'; }else{ echo '<a href="?r=2&rnd='.$code.'">����������</a>'; } ?>&nbsp;&nbsp;</td>
			<?
			//if($u->info['admin']>0){
		/*?>
			<td nowrap="nowrap" align="center" <? if($r==3){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==3){ echo '<b>�������������</b>'; }else{ echo '<a href="?r=3&rnd='.$code.'">�������������</a>'; } ?>&nbsp;&nbsp;</td>
		<?*/
          /*
		    ?>
            <td nowrap="nowrap" align="center" <? if($r==6){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==6){ echo '<B>��������</B>'; }else{ echo'<a href="?r=6&rnd='.$code.'">��������</a>';}?>&nbsp;&nbsp;</td>
            <td nowrap="nowrap" align="center" <? if($r==7){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==7){ echo '<B>�����������</B>'; }else{ echo'<a href="?r=7&rnd='.$code.'">�����������</a>';}?>&nbsp;&nbsp;</td>
	       
			
	        <td nowrap="nowrap" align="center" <? if($r==4){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==4){ echo '<b>����</b>'; }else{ echo '<a href="?r=4&rnd='.$code.'">����</a>'; } ?>&nbsp;&nbsp;</td>
            
            <?
			*/
			
			?>
			<td nowrap="nowrap" align="center" <? if($r==5){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==5){ echo '<b>��������</b>'; }else{ echo '<a href="?r=5&rnd='.$code.'">��������</a>'; } ?>&nbsp;&nbsp;</td>
	        <td nowrap="nowrap" align="center" <? if($r==8){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==8){ echo '<b>���������</b>'; }else{ echo '<a href="?r=8&rnd='.$code.'">���������</a>'; } ?>&nbsp;&nbsp;</td>
            <td nowrap="nowrap" align="center" <? if($r==9){ echo 'bgcolor="#A5A5A5"'; } ?> >&nbsp;&nbsp;<? if($r==9){ echo '<b>�������� ���������</b>'; }else{ echo '<a href="?r=9&rnd='.$code.'">�������� ���������</a>'; } ?>&nbsp;&nbsp;</td>
            <td width="90%">&nbsp;</td>
	        </tr>
	      </table></td>
	    </tr>
	  <tr>
	    <?
		$rn = array(
			1 => '������� ������������ ���������',
			2 => '��������� �������� �� ������ (20 ��������)',
			3 => '���������� �� �������� ����� ���������� ���������',
			4 => '���������� ���',
			5 => '��������� �����',
			6 => '��������� ���������������� ���������',
			7 => '����������� ���������',
			8 => '��������� ������ ��������',
			9 => '�������� �������� ��� ��������'
		);
		$rn2 = array(
			1 => '� ��� � ������� ��� ������������ ���������',
			2 => '� ��� � ������� ��� ������, �� ������� ����� ������� ����������',
			3 => '� ��� � ������� ��� ���������� ���������',
			4 => '� ��� � ������� ��� ���������� ���������',
			5 => '� ��� � ������� ��� ���������� ���������',
			6 => '� ��� � ������� ��� ���������� ���������',
			7 => '� ��� � ������� ��� ���������� ���������',
			8 => '� ��� � ������� ��� ���������� ���������',
			9 => '� ��� � ������� ��� ���������� ���������'
		);
		?>
        <td bgcolor="#A5A5A5"><small><b><? echo $rn[$r]; ?></b></small></td>
	    </tr>
	  <tr>
	    <td style="border:1px solid #A5A5A5;padding:0px;">
        <? 
		if($r == 2) {
			echo '<div style="background-color:#c8c8c8;border-bottom:1px solid #a5a5a5;padding:2px;"><i><b>����� �����</b> �������������, ��� �� ����������� ��� �������������� ������� �� ������ ��������.</i></div>';
		}elseif($r == 8) {
			echo '<div style="background-color:#c8c8c8;border-bottom:1px solid #a5a5a5;padding:2px;"><i>��� �������� �������� (�������, ����, ����� � �.�.) ���������� ��� ����������� ��������</i></div>';
		}elseif($r == 5) {
			echo '<div style="background-color:#c8c8c8;border-bottom:1px solid #a5a5a5;padding:2px;"><i>��������! ����� ����� ������� � ���� ����� �������!</i></div>';
		}elseif($r == 3) {
			//echo '<div style="background-color:#c8c8c8;border-bottom:1px solid #a5a5a5;padding:2px;"><i><b>��������!</b> ��� ������������� ��������� ������ �������, ���� � ����������� �����������.</i></div>';
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<?
		if(($r == 8 || $r == 9) && !isset($u->bank['id'])) {
			//��������� ��������� �� ��.\���.
		?>
        <?
		if(isset($_POST['bank']) && isset($u->bank['id']))
		{
			echo '<font color="red"><b>���������� ���� ����, ���� � ������� ��������</b></font>';
		}elseif(isset($_POST['bank']) && !isset($u->bank['id']))
		{
			echo '<font color="red"><b>�������� ������ �� ����������� �����.</b></font>';
		}
		?>
        <form name="F1" method="post">
        <br /><center>������� �� ���� ���������� ����, ��� ��������� ��������� ��������� ��������� ���.</center>
        <div>
          <table style="padding-bottom:20px;" align="center" width="300" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td bgcolor="#B1A996"><div align="center"><strong>���� � �����</strong></div></td>
            </tr>
            <tr>
              <td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>�������� ���� � ������� ������<br />
                        <select name="bank" id="bank">
						<?
                        $scet = mysql_query('SELECT `id` FROM `bank` WHERE `block` = "0" AND `uid` = "'.$u->info['id'].'"');
                        while ($num_scet = mysql_fetch_array($scet)) 
                        {
                       		 echo "<option>".$u->getNum($num_scet['id'])."</option>";
                        }
						?>
                        </select>
                        <input style="margin-left:5px;" type="password" name="bankpsw" id="bankpsw" />
                        <label></label>
                  </small>
                      <input style="margin-left:3px;" type="submit" name="button" id="button" value=" ok " />
              </div></td>
            </tr>
          
        </div>
        </form>
        <br />
		<?
		}else{
			if($r == 9 && isset($_GET['upgradelvlcom']) && $see != '') {
				$itmu = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `id` = "'.mysql_real_escape_string($_GET['upgradelvlcom']).'" LIMIT 1'));
				$itmm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$itmu['item_id'].'" LIMIT 1'));
					if( $itmm['price2'] == 0 ) {
						$itmm['price2'] = $itmm['price1']/5;
					}
					$sts = explode('|',$itmu['data']);
					$i = 0; $ste = ''; $sti = array();
					while($i<count($sts))
					{
						$ste = explode('=',$sts[$i]);
						if(isset($ste[1]))
						{
							if(!isset($sti[$ste[0]])) {
								$sti[$ste[0]] = 0;
							}
							$sti[$ste[0]] += intval($ste[1]);
						}
						$i++;
					}
				  if(isset($_POST['com2'],$_POST['work2'])) {
					 $epr = 0;
					 if($sti['sudba'] > 0 || $_POST['work2'] == '1') {
						$wrk = 1;
						$epr =  round($itmm['price2']*0.2,2);
					 }else{
						 $wrk = 2;
						$epr =  round($itmm['price2']*0.4,2); 
					 }
					$sel = array();
					$sp = mysql_query('SELECT * FROM `complects`');
					while( $pl = mysql_fetch_array($sp) ) {
						if(!isset($sel[$pl['com']]) && $com_podgon[$pl['name']] == true) {
							$sel[$pl['com']] = true;
							if($pl['com'] == $_POST['com2']) {
								$com2 = $pl;
							}
						}
					}
					if( $com2['com'] > 0 && ($sti['complect'] == $com2['com'] || $sti['complect2'] == $com2['com']) ) {
						$er2 = '������� ��� ���������� ������ ��������.';
					}elseif( !isset($com2['id']) ) {
						$er2 = '�� �� ������ ��������� ������� ��� ������ ��������.';
					}elseif( $epr > $u->bank['money2'] ) {
					 	$er2 = '� ��� ������������ ���., ��������� '.$epr.' ���.';
					 }else{
						$sti['complect2'] = $com2['com'];
						if($wrk == 1 && ($sti['sudba'] == '0' || !isset($sti['sudba']))) {
							$sti['sudba'] = $u->info['login'];
						}
						$sti_imp = $u->impStats($sti);
						mysql_query('UPDATE `bank` SET `money2` = `money2` - "'.$epr.'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
						mysql_query('UPDATE `items_users` SET `data` = "'.mysql_real_escape_string($sti_imp).'" WHERE `id` = "'.$itmu['id'].'" LIMIT 1');
					 	$er2 = '������� &quot;'.$itmm['name'].'&quot; ������� �������� ��� &quot;'.$com2['name'].'&quot; �� '.$epr.' ���.'; 
					 }
				  }
				?>
                  <tr>
                    <td bgcolor="#c4c4c4" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"><?=$see?></table></td>
                    <td width="13" valign="middle" bgcolor="#a4a6a4"><img src="http://img.xcombats.com/imgr.jpg" width="13" height="63"></td>
                    <td width="300" align="left" valign="middle" bgcolor="#c4c4c4">
                    	<div align="left" style="padding:10px;">
                        <form method="post" action="main.php?r=9&upgradelvlcom=<?=$itmu['id']?>">
						<?
						if(isset($er2)) {
							echo '<font color=red>'.$er2.'</font><hr>';
						}
						$html = ''; $sel = array();
						$sp = mysql_query('SELECT * FROM `complects`');
						while( $pl = mysql_fetch_array($sp) ) {
							if(!isset($sel[$pl['com']]) && $com_podgon[$pl['name']] == true) {
								$sel[$pl['com']] = true;
								$html .= '<label><input type="radio" name="com2" value="'.$pl['com'].'" />'.$pl['name'].'</label><br>';
							}
						}
						echo $html;
						?><br /><br />��� �����:<br />
                        <label><input type="radio" name="work2" value="1" />������ <img src="http://img.xcombats.com/i/desteny.gif" title="������� ����� ������ ����� ������� � ����" width="16" height="18" /> �� <?=round($itmm['price2']*0.2,2)?> ���. </label><br>
                        <? if(!isset($sti['sudba'])) { ?>
                        <label><input type="radio" name="work2" value="2" />����� �� <?=round($itmm['price2']*0.4,2)?> ���. </label><br>
                        <? } ?>
                        <center><br /><input type="submit" value="�����������" class="btnnew" /></center>
                        </form>
                        </div>
                    </td>
                  </tr>
                <?
			}elseif($see == '')
			{
				echo $rn2[$r];
			}else{
				echo $see;	
			}
		}
		?>
        </table>
        </td>
	    </tr>
	  </table>
	<br />

	<td width="280" valign="top">
    <TABLE cellspacing="0" cellpadding="0"><TD width="100%">&nbsp;</TD><TD>
	<table  border="0" cellpadding="0" cellspacing="0">
	<tr align="right" valign="top">
	<td>
	<!-- -->
	<? echo $goLis; ?>
	<!-- -->
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td nowrap="nowrap">
	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
	<tr>
	<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=2.180.0.234&rnd=<? echo $code; ?>';" title="<? thisInfRm('2.180.0.234',1); ?>">����������� �������</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div><br />
      <div align="right">
      <small>
	  �����: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	  � ��� � �������: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> ��.</b> &nbsp;
      </small>
      </div>
      	<p><small>
      	<?php
        if(isset($re2)){ echo '<b style="color:red">'.$re2.'</b>'; }
        ?>
      	</small></p>
      	<p>
      	  <br /><BR>
   	    </p>
    </div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>