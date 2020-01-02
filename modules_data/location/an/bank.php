<?php
if(!defined('GAME'))
{
	die();
}
	if(!function_exists('send_mime_mail')) {
		function send_mime_mail($name_from, // ��� �����������
						   $email_from, // email �����������
						   $name_to, // ��� ����������
						   $email_to, // email ����������
						   $data_charset, // ��������� ���������� ������
						   $send_charset, // ��������� ������
						   $subject, // ���� ������
						   $body // ����� ������
						   )
		   {
		  $to = mime_header_encode($name_to, $data_charset, $send_charset)
						 . ' <' . $email_to . '>';
		  $subject = mime_header_encode($subject, $data_charset, $send_charset);
		  $from =  mime_header_encode($name_from, $data_charset, $send_charset)
							 .' <' . $email_from . '>';
		  if($data_charset != $send_charset) {
			$body = iconv($data_charset, $send_charset, $body);
		  }
		  $headers = "From: $from\r\n";
		  $headers .= "Content-type: text/html; charset=$send_charset\r\n";
		
		  return mail($to, $subject, $body, $headers);
		}
	
		function mime_header_encode($str, $data_charset, $send_charset) {
		  if($data_charset != $send_charset) {
			$str = iconv($data_charset, $send_charset, $str);
		  }
		  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
		}
	}

if($u->room['file']=='an/bank')
{
	$noc = 60; //120 kr = 1 ekr.
	$con = 20; //1 ���. = 30 ��.
	function getNum($v)
	{
		$plid = $v;
		$pi = iconv_strlen($plid);
		if($pi<5)
		{
			$i = 0;
			while($i<=5-$pi)
			{
				$plid = '0'.$plid;
				$i++;
			}
		}
		return $plid;
	}
	function getNumId($v)
	{
		$plid = $v;
		$array = str_split($plid);
		$ends=0;
		$result='';
		for($i=0,$end=(count($array)-1);$i<=$end;$i++){
		   if($array[$i]==0 and $ends==0){$array[$i]='';}else{$ends=1;}
		   $result.=$array[$i];
		}
		//print_r($array);
		return $result;
	}
	
	if($u->info['allLock'] > time()) {
		$u->bank = false;	
	}
	
	$re2 = '';
	if(isset($_GET['enter']) && !isset($u->bank['id']))
	{
		$bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.mysql_real_escape_string((int)$_POST['bank']).'" LIMIT 1'));
		if(!isset($bank['id']))
		{
			$re2 = '�������� ����� �����.';
		}elseif($bank['pass']!=$_POST['pass'])
		{
			$pl = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `time` > "'.(time()-60*60).'" AND `vars` = "bank_bad_pass_'.mysql_real_escape_string($bank['id']).'" LIMIT 5'));
			if($pl[0]>=3)
			{
				$re2 = '��� ���� ��� ������������ �� 1 ���';
			}else{
				if($pl[0]==0)
				{
					$re2 = '�������� ����� ����� ��� ������. ���� �� ������ ������� �������� ����� ����� ��� ������, ���� ����� ������������ �� ���';
				}else{
					$pp = array(0=>'��',1=>'��',2=>'��',3=>'��');
					$re2 = '�������� ����� ����� ��� ������. � ��� �������� '.(3-$pl[0]).' �����'.$pp[3-$pl[0]].', � ��������� ������ ���� ����� ������������ �� ���';
				}
				mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`) VALUES ("'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","bank_bad_pass_'.mysql_real_escape_string($bank['id']).'","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'")');
			}
		}else{
			
			if($u->info['allLock'] > time()) {
				echo '<script>setTimeout(function(){alert("��� ��������� ������������ �������� ����� �� '.date('d.m.y H:i',$u->info['allLock']).'")},250);</script>';
			}else{			
				//�����!
				$bank['useNow'] = time()+12*60*60;
				mysql_query('UPDATE `bank` SET `useNow` = "0" WHERE `id` != "'.$bank['id'].'" AND `uid` = "'.$u->info['id'].'" AND `useNow`!="0" LIMIT 1');
				mysql_query('UPDATE `bank` SET `useNow` = "'.$bank['useNow'].'" WHERE `id` = "'.$bank['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`) VALUES ("'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","bank_good_pass_'.mysql_real_escape_string($bank['id']).'","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'")');
				$u->bank = $bank;
			}
		}		
	}elseif(isset($_GET['res']))
	{
	//echo $_GET['schet'].'<br>';
		$b_pass = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.mysql_real_escape_string(getNumId($_GET['schet'])).'" ORDER BY `id` DESC LIMIT 1'));
		if($b_pass['repass'] >= time())
		{
			$re2 = '������ ������ � ������ � ��� ����� ������� ������ ���� ��� � �����';
		}else{
			mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`) VALUES ("'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","bank_res","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'")');
			$re2 = '������ ����� ����� � ������ �� email, ��������� � ������';
			mysql_query('UPDATE `bank` SET `repass` = "'.(time()+24*3600).'" WHERE `id` = "'.$b_pass['id'].'" LIMIT 1');
			send_mime_mail('���������� ���� - Support',
               'support@xcombats.com',
               ''.$u->info['login'].'',
               $u->info['mail'],
               'CP1251',  // ���������, � ������� ��������� ������������ ������
               'KOI8-R', // ���������, � ������� ����� ���������� ������
               '�������������� ������ �� ����� � ����� ��������� '.$u->info['login'].'',
               "����� �����: ".getNum($b_pass['id'])."<br>������: ".$b_pass['pass'].'<br><br>� ���������,<br>������������� ����������� �����');
			
		}
	}elseif(isset($_GET['open']) && !isset($u->bank['id']))
	{
		if( $_POST['rdn01'] == 2 && ($u->info['level'] >= 8 || $u->info['money4'] < 15 )) {
			$re2 = '������������ �����!';
		}elseif($u->info['money']>=3 || ($u->info['level'] < 8 && $u->info['money4'] >= 15 ))
		{
			if( $_POST['pass1'] == '' || $_POST['pass1'] == ' ' ) {
				$re2 = '�� �� ������� ������!';
			}elseif( $_POST['pass1'] != $_POST['pass2'] ) {
				$re2 = '������ �� ���������!';
			}elseif( $u->info['money'] - 3 < 0 && $_POST['rdn01'] != 2 ) {
				$re2 = '� ��� ������������ ��.';
			}elseif($u->info['align']!=2)
			{
				$pass = rand(10000,91191);
				$pass = htmlspecialchars($_POST['pass1'],NULL,'cp1251');
				$ins = mysql_query('INSERT INTO `bank` (`uid`,`create`,`pass`) VALUES ("'.$u->info['id'].'","'.time().'","'.$pass.'")');
				if($ins)
				{
					$bank = mysql_insert_id();
					if( $u->info['level'] < 8 && $_POST['rdn01'] == 2 ) {
						$u->info['money4'] -= 15;
					}else{
						$u->info['money'] -= 3;
					}
					$upd = mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'",`money4` = "'.$u->info['money4'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$re2 = '���� �<b>'.getNum($bank).'</b> ��� ������� ������.<br>������ �� �����: <b>'.$pass.'</b><br><small><br>(������� ������ ����� � ������� "���������� ������" ����� �����������)';
					$u->addDelo(3,$u->info['id'],'�� ������� ������� ���� �'.getNum($bank).'',time(),$u->info['city'],'Bank.System',3,0,'');
				}else{
					$re2 = '���� ������� � ��������� ����������� �����.';
				}				
			}else{
				$re2 = '�������� �� ����� ��������� ����� ����� � �����.';
			}
		}else{
			if( $u->info['level'] < 8 ) {
				$re2 = '��� �������� ����� ���������� ����� ��� ���� <b>3.00 ��.</b> ��� <b>'.$u->zuby(15).'</b>';
			}else{
				$re2 = '��� �������� ����� ���������� ����� ��� ���� <b>3.00 ��.</b>';
			}
		}
	}elseif(isset($_GET['exit']) && isset($u->bank['id']))
	{
		$u->bank = false;
		mysql_query('UPDATE `bank` SET `useNow` = "0" WHERE `uid` = "'.$u->info['id'].'" AND `useNow`!="0" LIMIT 1');
	}
	
	if($u->info['allLock'] > time()) {
		$u->bank = false;	
	}
	
	if(isset($u->bank['id']))
	{
		if(isset($_POST['sd4']) && $u->newAct($_POST['sd4']))
		{
			if(isset($_POST['transfer_kredit2']) && $u->info['admin']>0)
			{
				//������� ��������� � ������ ����� �� ������
				$ub = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.mysql_real_escape_string((int)$_POST['num2']).'" LIMIT 1'));
				if(isset($ub['id']) && $ub['id']!=$u->bank['id'])
				{
					$ut = mysql_fetch_array(mysql_query('SELECT `id`,`level`,`city`,`room`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($ub['uid']).'" LIMIT 1'));
					if($ut['level']>=0 || $ut['id']==$u->info['id'] || $u->info['admin']>0)
					{
						$mn = floor((int)($_POST['tansfer_sum2']*100));
						$mn = round(($mn/100),2);
						$prc = 0;
						$mn += $prc;
						if($u->bank['money2']>=$mn)
						{
							if($mn<0.01 || $mn>1000000000)
							{
								$re2 = '������� ������� �����';
							}else{
								$upd = mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string($u->bank['money2']-$mn).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
								if($upd)
								{
									$u->bank['money2'] -= $mn;
									$ub['money2'] += $mn-$prc;
									
									mysql_query('UPDATE `users` SET `catch` = `catch` + "'.floor($mn-$prc).'" WHERE `id` = "'.$ut['id'].'" LIMIT 1');
									mysql_query('UPDATE `users` SET `frg` = `frg` + '.floor($mn).' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									
									mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string($ub['money2']).'" WHERE `id` = "'.$ub['id'].'" LIMIT 1');
									$re2 = '�� ������ �������� <b>'.($mn-$prc).' ���.</b> (�������� <b>'.$prc.' ���.</b>) �� ���� �'.getNum($ub['id']).' ��������� &quot;<b>'.$ut['login'].'</b>&quot;';
									$u->addDelo(3,$ut['id'],'�������� <b>'.($mn-$prc).' ���.</b> �� ����� �'.getNum($u->bank['id']).' �� ��������� &quot;'.$u->info['login'].'&quot;, �������� <b>'.$prc.' ���.</b> <i>(�����: '.$ub['money1'].' ��., '.$ub['money2'].' ���.)</i>',time(),$ut['city'],'Bank.System',mysql_real_escape_string($mn-$prc),0,$ub['id']);
									$u->addDelo(3,$u->info['id'],'�������� <b>'.($mn-$prc).' ���.</b> �� ���� �'.getNum($ub['id']).' ��������� &quot;'.$ut['login'].'&quot;, �������� <b>'.$prc.' ���.</b> <i>(�����: '.$u->bank['money1'].' ��., '.$u->bank['money2'].' ���.)</i>',time(),$u->info['city'],'Bank.System',0,mysql_real_escape_string($mn),$u->bank['id']);
									$log = '&quot;'.$u->info['login'].'&quot;&nbsp;['.$u->info['level'].'] ������� �� ������ ����������� ����� �'.$u->bank['id'].' �� ���� �'.$ub['id'].' � ��������� &quot;'.$ut['login'].'&quot;&nbsp;['.$ut['level'].'] '.($mn-$prc).' ���.';
									$u->addDelo(1,$u->info['id'],$log,time(),$u->info['city'],'Bank.System',0,0,'');
									$u->addDelo(1,$ut['id'],$log,time(),$ut['city'],'Bank.System',0,0,'');
									if($ut['id']!=$u->info['id'])
									{
										$alg = '';
										if($u->info['align']==50)
										{
											$alg = '<img src=http://img.xcombats.com/i/align/align50.gif >';
										}
										$text = '&quot;'.$alg.'[login:'.$u->info['login'].']&quot; �������'.($u->info['sex']==0?"":"�").' ��� <b>'.($mn-$prc).' ���.</b> �� ������ ����������� ����� �'.getNum($u->bank['id']).' �� ��� ���������� ���� �'.getNum($ub['id']).'.';
										
										mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$ut['city']."','".$ut['room']."','','".$ut['login']."','".$text."','".time()."','12','0','1')");
									}
								}else{
									$re2 = '�� ������� ��������� ��������';
								}
							}
						}else{
							$re2 = '� ��� ��� <b>'.$mn.' ���.</b> �� �����';
						}
					}else{
						$re2 = '������ ��������� ������� �� ���� ����';
					}
				}else{
					$re2 = '������ ��������� ������� �� ���� ����';
				}
			}elseif(isset($_POST['transfer_kredit']) && $u->info['align']!=2)
			{
				//������� �������� � ������ ����� �� ������
				if($u->info['level']>=4 || $u->info['admin']>0)
				{
					$ub = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.mysql_real_escape_string((int)$_POST['num']).'" LIMIT 1'));
					if(isset($ub['id']) && $ub['id']!=$u->bank['id'])
					{
						$ut = mysql_fetch_array(mysql_query('SELECT `id`,`level`,`city`,`room`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($ub['uid']).'" LIMIT 1'));
						if($ut['level']>=4 || $ut['id']==$u->info['id'] || $u->info['admin']>0)
						{
							$mn = floor((int)($_POST['tansfer_sum']*100));
							$mn = round(($mn/100),2);
							$prc = round($mn*3/100,2);
							$mn += $prc;
							if($u->bank['money1']>=$mn)
							{
								if($mn<0.01 || $mn>1000000000)
								{
									$re2 = '������� ������� �����';
								}else{
									$upd = mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']-$mn).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
									if($upd)
									{
										$u->bank['money1'] -= $mn;
										$ub['money1'] += $mn-$prc;
										mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($ub['money1']).'" WHERE `id` = "'.$ub['id'].'" LIMIT 1');
										$re2 = '�� ������ �������� <b>'.($mn-$prc).' ��.</b> (�������� <b>'.$prc.' ��.</b>) �� ���� �'.getNum($ub['id']).' ��������� &quot;<b>'.$ut['login'].'</b>&quot;';
										$u->addDelo(3,$ut['id'],'�������� <b>'.($mn-$prc).' ��.</b> �� ����� �'.getNum($u->bank['id']).' �� ��������� &quot;'.$u->info['login'].'&quot;, �������� <b>'.$prc.' ��.</b> <i>(�����: '.$ub['money1'].' ��., '.$ub['money2'].' ���.)</i>',time(),$ut['city'],'Bank.System',mysql_real_escape_string($mn-$prc),0,$ub['id']);
										$u->addDelo(3,$u->info['id'],'�������� <b>'.($mn-$prc).' ��.</b> �� ���� �'.getNum($ub['id']).' ��������� &quot;'.$ut['login'].'&quot;, �������� <b>'.$prc.' ��.</b> <i>(�����: '.$u->bank['money1'].' ��., '.$u->bank['money2'].' ���.)</i>',time(),$u->info['city'],'Bank.System',0,mysql_real_escape_string($mn),$u->bank['id']);
										$log = '&quot;'.$u->info['login'].'&quot;&nbsp;['.$u->info['level'].'] ������� �� ������ ����������� ����� �'.$u->bank['id'].' �� ���� �'.$ub['id'].' � ��������� &quot;'.$ut['login'].'&quot;&nbsp;['.$ut['level'].'] '.($mn-$prc).' ��. ������������� ����� '.$prc.' ��. �� ������ �����.';
										$u->addDelo(1,$u->info['id'],$log,time(),$u->info['city'],'Bank.System',0,0,'');
										$u->addDelo(1,$ut['id'],$log,time(),$ut['city'],'Bank.System',0,0,'');
										if($ut['id']!=$u->info['id'])
										{
											$text = '&quot;[login:'.$u->info['login'].']&quot; �������'.($u->info['sex']==0?"":"�").' ��� <b>'.($mn-$prc).' ��.</b> �� ������ ����������� ����� �'.getNum($u->bank['id']).' �� ��� ���������� ���� �'.getNum($ub['id']).'.';
											mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$ut['city']."','".$ut['room']."','','".$ut['login']."','".$text."','".time()."','6','0','1')");
										}
									}else{
										$re2 = '�� ������� ��������� ��������';
									}
								}
							}else{
								$re2 = '� ��� ��� <b>'.$mn.' ��.</b> �� �����';
							}
						}else{
							$re2 = '������ ��������� ������� �� ���� ����';
						}
					}else{
						$re2 = '������ ��������� ������� �� ���� ����';
					}
				}else{
					$re2 = '�������� �������� �������� ������ � 4-�� ������';
				}
			}elseif($u->info['align']!=2 && $u->info['haos'] < time() && $u->info['haos'] != 1 && $u->info['align'] !=50 && isset($_POST['convert_kredit']) && 1 == 2) {
				//�������� ��. �� ���.
				if($u->info['palpro'] > time()) {
					$mn = ceil((int)($_POST['convert_sum2']*100));
					$mn = round(($mn/100),2);
					$mne = round($mn/$noc,2);
					$mn = round(($mn/100*103+5),2);
					$sm = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "bank_kr_to_ekr_['.date('d.m.Y',time()).']" ORDER BY `id` DESC LIMIT 1',1);
					$sm_lim = 50;
					if(isset($sm['id']) && $sm['vals']+$mne > $sm_lim) {
						if($sm['vals'] < $sm_lim) {
							$re2 = '�� ������� �� ������ �������� ��� �� <b>'.($sm_lim-$sm['vals']).' ���.</b>. (�������� '.round( ( ($sm_lim-$sm['vals'])*$noc ) ,2).' ��.), ������� ����� �� <b>'.$mne.' ���.</b>.';
						}else{
							$re2 = '�� ������� �� ��������� ���� ����� ������ ��. �� ���. ('.$sm_lim.' ���.)';
						}
					}elseif($mn > 0 && $mne > 0 && $mn >= round((0.01*($noc*1.03)+5),2)) {
						if($u->bank['money1'] >= $mn) {
							if(!isset($sm['id'])) {			
								$u->addAction(time(),'bank_kr_to_ekr_['.date('d.m.Y').']',$mne);
							}else{
								mysql_query('UPDATE `actions` SET `vals` = "'.($sm['vals']+$mne).'" WHERE `id` = "'.$sm['id'].'" LIMIT 1');
							}
							$re2 = '�� ������� �������� <b>'.$mn.' ��.</b> �� <b>'.$mne.' ���.</b>';
							$u->bank['money1'] -= $mn;
							$u->bank['money2'] += $mne;			
											
							mysql_query('UPDATE `users` SET `catch` = `catch` + "'.round($mne,2).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
												
							mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']).'", `money2` = "'.mysql_real_escape_string($u->bank['money2']).'" WHERE `id` = "'.mysql_real_escape_string($u->bank['id']).'" LIMIT 1');
										$log = '&quot;'.$u->info['login'].'&quot;&nbsp;['.$u->info['level'].'] ������� <b>'.$mn.' ��.</b> �� <b>'.$mne.' ���.</b>, ���������� ���� �'.$u->bank['id'].'.';
										$u->addDelo(1,$u->info['id'],$log,time(),$u->info['city'],'Bank.System',0,0,'');
										$u->addDelo(3,$u->info['id'],'�� ������� �������� <b>'.ceil((int)($_POST['convert_sum2']*100/100)).' ��.</b> �� <b>'.$mne.' ���.</b>, �������� <b>'.round((ceil((int)($_POST['convert_sum2']*100/100))/100*3+5),2).' ��.</b> <i>(�����: '.$u->bank['money1'].' ��., '.$u->bank['money2'].' ���.)</i>',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
						}else{
							$re2 = '� ��� ��� <b>'.$mn.' ��.</b> �� �����';
						}
					}else{
						$re2 = '����������� ����� ��� ������ ���������� '.round((0.01*($noc*1.03)+5),2).' ��.';
					}
				}else{
					$re2 = '�� ������ ������ �������� �� ������� � ��������� ��� ��������.';
				}
			}elseif(isset($_POST['convert_ekredit']))
			{
				//�������� ���. �� ��.
				$mn = ceil((int)($_POST['convert_sum']*100));
				$mn = round(($mn/100),2);
				if($u->bank['money2']>=$mn)
				{
					if($mn<0.01 || $mn>1000000000)
					{
						$re2 = '������� ������� �����';
					}else{
						$upd = mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']+($mn*$con)).'",`money2` = "'.mysql_real_escape_string($u->bank['money2']-$mn).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
						if($upd)
						{
							$u->bank['money1'] += $mn*$con;
							$u->bank['money2'] -= $mn;
							$u->addDelo(3,$u->info['id'],'�� �������� <b>'.$mn.' ���.</b> �� <b>'.($mn*$con).' ��.</b>, �������� <b>0 ��.</b> <i>(�����: '.$u->bank['money1'].' ��., '.$u->bank['money2'].' ���.)</i>',time(),$u->info['city'],'Bank.System',0,mysql_real_escape_string($mn*$con),$u->bank['id']);
							$re2 = '�� ������ �������� <b>'.$mn.' ���.</b> �� <b>'.($mn*$con).' ��.</b>';
						}else{
							$re2 = '�� ������� ��������� ��������';
						}
					}
				}else{
					$re2 = '� ��� ��� <b>'.$mn.' ���.</b> �� �����';
				}
			}elseif(isset($_POST['get_kredit']))			
			{
				//�������� ������ �� ����
				$mn = floor((int)($_POST['get_sum']*100));
				$mn = round(($mn/100),2);
				if($u->bank['money1']>=$mn)
				{
					if($mn<0.01 || $mn>1000000000)
					{
						$re2 = '������� ������� �����';
					}else{
						$upd = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string($u->info['money']+$mn).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if($upd)
						{
							$u->bank['money1'] -= $mn;
							$u->info['money']  += $mn;
							mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
							$u->addDelo(3,$u->info['id'],'�� ����� �� ����� <b>'.$mn.' ��.</b>, �������� <b>0 ��.</b> <i>(�����: '.$u->bank['money1'].' ��., '.$u->bank['money2'].' ���.)</i>',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
							$re2 = '�� ������ ����� �� ����� <b>'.$mn.' ��.</b>';
						}else{
							$re2 = '�� ������� ��������� ��������';
						}
					}
				}else{
					$re2 = '� ��� ��� <b>'.$mn.' ��.</b> �� �����';
				}
			}elseif(isset($_POST['add_kredit']))			
			{
				//�������� ������ �� ����
				$mn = floor((int)($_POST['add_sum']*100));
				$mn = round(($mn/100),2);
				if($u->info['money']>=$mn)
				{
					if($mn<0.01 || $mn>1000000000)
					{
						$re2 = '������� ������� �����';
					}else{
						$upd = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string($u->info['money']-$mn).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if($upd)
						{
							$u->bank['money1'] += $mn;
							$u->info['money']  -= $mn;
							mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
							$u->addDelo(3,$u->info['id'],'�� �������� �� ���� <b>'.$mn.' ��.</b>, �������� <b>0 ��.</b> <i>(�����: '.$u->bank['money1'].' ��., '.$u->bank['money2'].' ���.)</i>',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
							$re2 = '�� ������ �������� �� ���� ���� <b>'.$mn.' ��.</b>';
						}else{
							$re2 = '�� ������� ��������� ��������';
						}
					}
				}else{
					$re2 = '� ��� ��� ��� ���� <b>'.$mn.' ��.</b>';
				}
			}elseif(isset($_POST['change_psw2']))
			{
				//����� ������ �����
				$sm = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vals` = "id='.$u->bank['id'].'&new_pass='.$u->bank['pass'].'" AND `vars` = "bank_new_pass" AND `time` > "'.(time()-24*60*60).'" LIMIT 1',1);
				if($_POST['new_psw1']!=$_POST['new_psw2'])
				{
					$re2 = '������ �� ���������';
				}elseif(iconv_strlen($_POST['new_psw1'])<6 || iconv_strlen($_POST['new_psw1'])>32)
				{
					$re2 = '������ �� ����� ���� ������ 6 ��� ������ 32 ��������';
				}elseif(isset($sm['id']))
				{
					$re2 = '������ ������ ������ ���� ������ ���� � ����';
				}else{
					//������
					$upd = mysql_query('UPDATE `bank` SET `pass` = "'.mysql_real_escape_string($_POST['new_psw1']).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
					if($upd)
					{
						$u->addAction(time(),'bank_new_pass','id='.$u->bank['id'].'&new_pass='.$_POST['new_psw1'].'');
						$u->bank['pass'] = $_POST['new_psw1'];
						$re2 = '������ �� ����� �<b>'.getNum($u->bank['id']).'</b> ��� ������� �������<br>����� ������: <b>'.$u->bank['pass'].'</b>';
						$u->addDelo(3,$u->info['id'],'��� ������� ������ �� �����.',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
					}else{
						$re2 = '��� �������� � ����� ������';
					}
				}
			}	
		}
	}
	
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
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
	</style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><div align="center">
        <div align="center" class="pH3">
          <h3>����<br /></h3>
        </div>
        </div></td>
        <td width="200">
          <div style="float:right;">
          <table cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%">&nbsp;</td>
              <td><table  border="0" cellpadding="0" cellspacing="0">
                  <tr align="right" valign="top">
                    <td><!-- -->
                        <? echo $goLis; ?>
                        <!-- -->
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                                <tr>
                                  <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                                  <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=2.180.0.236&rnd=<? echo $code; ?>';" title="<? thisInfRm('2.180.0.236',1); ?>">����������� �����</a></td>
                                </tr>
                            </table></td>
                          </tr>
                      </table></td>
                  </tr>
              </table></td>
            </tr>
          </table>
        </div></td>
      </tr>
    </table>
    <TABLE width="100%" cellspacing="0" cellpadding="4">
	<TR>
	<form name="F1" method="post">
	<TD valign="top" align="left">
	<!--�������--></TD>
	</FORM>
	</TR>
	<TR>
	  <TD valign="top" align="left">
        <? if($re2!=''){ echo '<div align="left"><font color="red">'.$re2.'</font></div><br>'; }
		if(!isset($u->bank['id']))
		{
		?>
        �� ������������� ��������� ������:
        <OL>
        <LI>�������� �����<LI>����������� ��������/����� �������/����������� �� �����
        <LI>��������� �������/����������� � ������ ����� �� ������
        <LI>�������� �����. ����� ������������ �� �������
        </OL>
        <script type="text/javascript" src="js/jquery.js"></script>
		<script>
		function hidecreatefx() {
			if( $('#hidecreate').css('display') != 'none' ) {
				$('#hidecreate').css('display','none');
			}else{
				$('#hidecreate').css('display','');
			}
		}
		</script>
        <FORM action="main.php?open&rnd=<? echo $code; ?>" method="POST">
        ������ ������� ���� ����? ������ �������: <INPUT onclick="hidecreatefx();" TYPE="button" value="������� ����">
        <div id="hidecreate" style="display:none">
        <FIELDSET style="width:300px;"><LEGEND><B>�������� �����</B> </LEGEND>
        <small>
		<? if ($u->info['level'] < 8) { ?>
        <center>
        	<input name="rdn01" type="radio" value="1"> <b>3.00 ��.</b> &nbsp; &nbsp; <input name="rdn01" type="radio" value="2"> <?=$u->zuby(15)?> &nbsp; &nbsp; &nbsp;
        </center>
        <hr />
        <? }else{
		?>
        <center>
        	<input checked="checked" name="rdn01" type="radio" value="1"> <b>3.00 ��.</b> &nbsp; &nbsp; &nbsp;
        </center>
        <hr />
		<?
		} ?>
		<style>
        fieldset {
            border:1px solid #AEAEAE;
        }
        hr {
            border:0;
            border-bottom:1px solid #aeaeae;	
        }
        </style>
        <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>������ �����:</td>
            <td><INPUT style='width:90;' type="password" value="" name="pass1"></td>
          </tr>
          <tr>
            <td>��� ���:</td>
            <td><INPUT style='width:90;' type="password" value="" name="pass2"></td>
          </tr>
        </table>
        </small>
        <center>
        	<INPUT TYPE="submit" value="������� ����">
        </center>
        </FIELDSET>
        </div>
        </FORM>
        <form action="main.php?enter&rnd=<? echo $code; ?>" method="POST">
        <br />
        <FIELDSET style="width:300px;"><LEGEND><B>���������� ������</B> </LEGEND>
        <TABLE width="300">
        <TR><TD valign=top>
        <TABLE>
        <TR><TD>����� �����</td> <TD colspan=2><select name="bank" size=0 style="width: 90px">
        	<?
			$sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `block` = "0"');
			while($pl = mysql_fetch_array($sp))
			{
			?>
            <option value="<? echo $pl['id']; ?>" selected="selected"><? echo getNum($pl['id']); ?></option>
            <?
			}
			?>
        </select></td></tr>
        <TR><TD>������</td><td> <INPUT style='width:90;' type="password" value="" name="pass"></td>
        </tr>
        <TR><TD colspan=3 align=center><INPUT TYPE="submit" value="�����"></td></tr>
        </TABLE>
        </TD>
        </TABLE>
        </FIELDSET>
        </form>
		<form method=GET action='main.php'>
		<input type=hidden name='res' value=<? echo $code; ?>>
        <br />
        <br />
        ������ ������? ����� ��� ������� �� email, ����� �����:<input type=text name='schet'> <input type="submit" value="�������" /></TD>
	    </form>
	 </TR>
	</TABLE>	
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
    <?
	}else{
	
?>
<style>
.pay td {
	width:50px;
}
.pay td img{
	display:block;
	margin:1px 0 0 0;
}
.pay td:hover img{
	margin:0 0 1px 0;
}
.pay td:hover img {
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=80); /* IE 5.5+*/
	-moz-opacity: 0.8; /* Mozilla 1.6 � ���� */
	-khtml-opacity: 0.8; /* Konqueror 3.1, Safari 1.1 */
	opacity: 0.8; /* CSS3 - Mozilla 1.7b +, Firefox 0.9 +, Safari 1.2+, Opera 9 */
	cursor:pointer;
}
</style>
<!-- ���������� ������ -->
<FORM action="main.php" method="POST">
<INPUT TYPE=hidden name="sd4" value="<? echo $u->info['nextAct']; ?>">
<TABLE width=100%>
<TR>
<TD valign=top width=30%><H4>���������� ������</H4> &nbsp;
<b>���� �:</b> <? echo getNum($u->bank['id']); ?> <a href="?exit=<? echo $code; ?>" title="�������� ������ c ������� ������">[x]</a><br>
</TD>
<TD valign=top align=center width=40%>
<TABLE><TR><TD>
<FIELDSET><LEGEND><B>� ��� �� �����</B> </LEGEND>
<TABLE>
<TR><TD>��������:</TD><TD><B><? echo $u->round2($u->bank['money1']); ?></B></TD></TR>
<TR><TD>������������:</TD>
<TD><B><? echo $u->round2($u->bank['money2']); ?></B></TD>
</TR>
<TR><TD colspan=2><HR></TD></TR>
<TR><TD>��� ���� ��������:</TD><TD><B><? echo $u->round2($u->info['money']); ?> ��.</B></TD></TR>
</TABLE>
</FIELDSET>
</TD></TR></TABLE>
</TD>
<TD valign=top align=right width=30%><FONT COLOR=red>��������!</FONT> ��������� ������ ����� �������, � ������� ��������� �������� �������� � ��������������� �������.</TD>
</TR>
</TABLE>
<style>
fieldset {
	border:1px solid #AEAEAE;
}
hr {
	border:0;
	border-bottom:1px solid #aeaeae;	
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" valign="top"><table width="100%" cellspacing="5">
      <tr>
        <td width="50%" valign="top"><fieldset style="background-color:#DDEAD7"">
         <legend><img src="http://img.xcombats.com/i/align/align50.gif" width="12" height="15" /> <b style="color:#5F3710">������������ ���. ������</b> </legend>
<style>
#pay_btn {
	background-color:#0099FF;
	color:#0FF;
	cursor:pointer;	
}
#pay_btn:hover {
	background-color:#CCC;
	color:#FFF;
	cursor:pointer;	
}
</style>
����� ���.: <input id="pay_in" style="padding-left:2px;width:77px;" value="1.00">
<input id="pay_btn" name="pay_btn" value="��������" type="button" onclick="window.open('/pay.back.php?ekr='+$('#pay_in').val()+'&code=1&ref=0','_blank');" style="padding:5px;" />
</div>
<div id="pay_block_see" style="display:none;padding-top:5px;border-top:1px solid #AEAEAE;"></div>
</fieldset></td>
      </tr> 
      <tr>
        <td valign="top" width="50%"><fieldset>
         <legend><b>��������� ����</b> </legend>
          �����
          <input type="text" name="add_sum" id="add_sum" size="6" maxlength="10" />
          ��.
          <input type="submit" name="add_kredit" value="�������� ������� �� ����" onclick="if(Math.round(document.getElementById('add_sum')).value==0) {alert('������� ����� � ����� �����'); return false;} else {return confirm('�� ������ �������� �� ���� ���� '+(Math.floor(document.getElementById('add_sum').value*100)/100).toFixed(2)+' ��. ?')}" />
          <br />
        </fieldset></td>
      </tr>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>��������� ������� �� ������ ����</b> </legend>
          �����
          <input id="vl1" value="" type="text" name="tansfer_sum" size="6" maxlength="10" />
          ��.<br />
          ����� ����� ���� ��������� �������
          <input value="" type="text" id="vl2" name="num" size="12" maxlength="15" />
          <br />
          <input type="submit" name="transfer_kredit" value="��������� ������� �� ������ ����" onclick="if(Math.round(document.getElementById('vl1')).value==0 || Math.round(document.getElementById('vl2').value)==0) {alert('������� ����� � ����� �����'); return false;} else {return confirm('�� ������ ��������� �� ������ ����� '+(Math.floor(document.getElementById('vl1').value*100)/100).toFixed(2)+' ��. �� ���� ����� '+Math.floor(document.getElementById('vl2').value)+' ?')}" />
          <br />
          <small>�������� ���������� <b>3.00 %</b> �� �����, �� �� ����� <b>1.00 ��</b>.</small>
        </fieldset></td>
      </tr>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>�������� �����</b> </legend>
          �������� ����������� �� �������.<br />
          ���� <b>1 ���.</b> = <b><? echo $con; ?>.00 ��.</b><br />
          �����
          <input type="text" name="convert_sum" id="convert_sum" size="6" maxlength="10" />
          ���.
          <input type="submit" name="convert_ekredit" value="��������" <? /*onclick="return confirm('�� ������ �������� '+(Math.floor(document.getElementById('convert_sum').value*100)/100).toFixed(2)+' ���. �� '+(Math.floor(document.getElementById('convert_sum').value*100)/100*<? echo (0+$con); ?>).toFixed(2)+' ��. ?');" */ ?> />
        </fieldset></td>
      </tr>
      <? if($u->info['align']!=2 && $u->info['haos'] < time() && $u->info['haos'] != 1 && $u->info['align'] !=50 && 1 == 2) { ?>
      <tr>
        <td valign="top"><fieldset style="background-color:#DDEAD7">
          <legend><b>�������� �����</b> </legend>
          �������� ������� �� �����������.<br />
          ���� <b><? echo $noc; ?> ��.</b> = <b>1.00 ���.</b><br />
          �����
          <input type="text" name="convert_sum2" id="convert_sum2" size="6" maxlength="10" />
          ��.
          <br />
          <small>�������� ���������� <b>3.00 %</b> �� �����, � ���-�� <b>5.00 ��</b>.</small>
          <input type="submit" name="convert_kredit" value="��������" onclick="return confirm('�� ������ �������� '+(5+Math.floor((document.getElementById('convert_sum2').value)*103)/100).toFixed(2)+' ��. �� '+(Math.floor(document.getElementById('convert_sum2').value*100)/100/<? echo $noc; ?>).toFixed(2)+' ���. ?');" />
        </fieldset></td>
      </tr>
      <? }
      if($u->info['admin']>1000)
	  {
	  ?>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>��������� ����������� �� ������ ����</b> </legend>
          �����
          <input id="vl12" value="" type="text" name="tansfer_sum2" size="6" maxlength="10" />
          ���.<br />
          ����� ����� ���� ��������� �������
          <input value="" type="text" id="vl22" name="num2" size="12" maxlength="15" />
          <br />
          <input type="submit" name="transfer_kredit2" value="��������� ����������� �� ������ ����" onclick="if(Math.round(document.getElementById('vl12')).value==0 || Math.round(document.getElementById('vl22').value)==0) {alert('������� ����� � ����� �����'); return false;} else {return confirm('�� ������ ��������� �� ������ ����� '+(Math.floor(document.getElementById('vl12').value*100)/100).toFixed(2)+' ���. �� ���� ����� '+Math.floor(document.getElementById('vl22').value)+' ?')}" />
          <br />
          �������� ���������� <b>0.00 %</b> �� �����, �� �� ����� <b>0.01 ���</b>.
        </fieldset></td>
      </tr>
      <? } ?>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>���������</b> </legend>
          � ��� ��������� ������� ������ ����� � ������ �� email. ���� �� �� ������� � ����� email, ��� ��������, ��� �� �������� ���� ����� ����� � ������ � ����, �� ������ ��������� ������� ������ �� email. ��� �������� ��� �� ����� �������� � ������ ����� � ������ ������ ������ email. �� ���� �� ���� �������� ���� ����� ����� �/��� ������, ��� ��� ����� �� �������!<br />
          <input type="submit" name="stop_send_email2" value="��������� ������� ������ �� email" />
          <hr />
          <b>������� ������</b><br />
          <table>
            <tr>
              <td>����� ������</td>
              <td><input type="password" name="new_psw1" /></td>
            </tr>
            <tr>
              <td>������� ����� ������ ��������</td>
              <td><input type="password" name="new_psw2" /></td>
            </tr>
          </table>
          <input type="submit" name="change_psw2" value="������� ������" />
          <br />
          <div id="keypad4" align="center" style="display: none;"></div>
        </fieldset></td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
      </tr>
    </table>
    </td>
    <td width="50%" valign="top"><table width="100%" align="left" cellspacing="5">
      <tr>
        <td valign="top" width="50%"><fieldset>
          <legend><b>����� �� �����</b> </legend>
          �����
          <input type="text" name="get_sum" id="get_sum" size="6" maxlength="10" />
          ��.
          <input type="submit" name="get_kredit" value="����� ������� �� �����" onclick="if(Math.round(document.getElementById('get_sum')).value==0) {alert('������� ����� � ����� �����'); return false;} else {return confirm('�� ������ ����� �� ������ ����� '+(Math.floor(document.getElementById('get_sum').value*100)/100).toFixed(2)+' ��. ?')}" />
          <br />
        </fieldset></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>���� ����������� � ������� ������</b> </legend>
          <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <?
			$pl = mysql_fetch_array(mysql_query('SELECT * FROM `bank_table` ORDER BY `time` DESC LIMIT 1'));
			if(isset($pl['id'])) {
			?>
            <tr>
              <td><small>������ �� <b><?=date('d.m.y H:i',$pl['time'])?></b> ��� ����� ��������</small></td>
            </tr>
            <?
				$pl['RUB'] = 1;
				
				$i = 0;
				$true = array(
					array('USD', '�������� ���'),
					array('EUR', '����'),
					array('RUB','���������� ������'),
					array('UAH','���. ������'),
					array('BYR','����������� ������'),
					array('AZN','��������������� �����'),
					array('GBP','����. ������ ����������')
				);
				while($i < count($true)) {
			?>
            <tr>
              <td><span>1 ���. = </span><span style="display:inline-block;width:100px"><b><?=round( ($pl['cur']/$pl[$true[$i][0]]) , 4 )?></b></span><span><?=$true[$i][1]?></span></td>
            </tr>
            <?
					$i++;
				}
			}else{
			?>
            <tr>
              <td><small><center><font color=grey>�� ������� �������� ����������</font></center></small></td>
            </tr>
            <? } ?>
          </table>
        </fieldset></td>
        </tr><tr>
        <td valign="top"><fieldset>
          <legend><b>��������� ��������</b> </legend>
          <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <?
			$sp = mysql_query('SELECT * FROM `users_delo` WHERE `uid` = "'.$u->info['id'].'" AND `dop` = "'.$u->bank['id'].'" AND `type` = "3" ORDER BY `time` DESC LIMIT 21');
			while($pl = mysql_fetch_array($sp))
			{
			?>
            <tr>
              <td><small><? echo '<font color="green">'.date('d.m.Y H:i',$pl['time']).'</font> '; echo $pl['text']; ?></small></td>
            </tr>
            <?
			}
			?>
          </table>
        </fieldset></td>
      </tr>

    </table></td>
  </tr>
</table>
</FORM>
<small>����� ��������� � ���� ���������� � ����� ���������\����������� ����� �����������.</small>
<?
	}
}
?>