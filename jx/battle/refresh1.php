<?php
			
	function e($t) {
		mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' ( ритическа€ ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","INFINITY","6","1","-1")');
	}
	
	$_POST['id'] = 'reflesh';
	//
	
	if(isset($_POST['atack'],$_POST['block']) || (isset($_POST['id']) && $_POST['id']=='reflesh') || isset($_POST['usepriem']) || isset($_POST['useitem']))
	{
		
		if(isset($_POST['useitemon'])) {
			$_POST['useitemon'] = iconv('UTF-8', 'windows-1251', $_POST['useitemon']);
		}
		session_start();
		$tm = microtime();
		$tm = explode(' ',$tm);
		$tm = $tm[0]+$tm[1];
		
		if(!isset($CRON_CORE)) {
			//include('../../_incl_data/__config.php');
			/*if($_SESSION['tbr']>$tm)
			{
				die('<script>ggcode="'.$code.'";if(t057!=null){clearTimeout(t057);}</script>');
			}else{
				$_SESSION['tbr'] = $tm+0.350;
			}*/
		}
		
		unset($tm);		
		$js = '';
		//include('../../_incl_data/class/__user.php');
		//include('../../_incl_data/class/__magic.php');
		//include('../../_incl_data/class/_cron_.php');	
		//include('../../_incl_data/class/__quest.php');
		
		if( $u->info['battle'] == 0 ) {
			$btl_last = mysql_fetch_array(mysql_query('SELECT `id`,`battle` FROM `battle_users` WHERE `uid` = "'.$u->info['id'].'" AND `finish` = "0" LIMIT 1'));
			if( isset($btl_last['id']) && $u->info['battle'] == 0 ) {
				echo '<script>document.getElementById(\'teams\').style.display=\'none\';var battleFinishData = "'.$u->info['battle_text'].'";</script>';
				$u->info['battle'] = $btl_last['id'];
				$u->info['battle_lsto'] = true;
				mysql_query('UPDATE `stats` SET `battle_text` = "",`last_b`="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query('UPDATE `battle_users` SET `finish` = 1 WHERE `uid` = "'.$u->info['id'].'"');
				echo '<script>alert("ѕоединок дл€ вас завершилс€.");location.href="main.php?finish=1";</script>';
			}
		}		
				
		if(!isset($CRON_CORE)) {
			if(!isset($u->info['id']) || ($u->info['joinIP']==1 && $u->info['ip']!=$_SERVER['HTTP_X_REAL_IP']))
			{
				die($c['exit']);
			}
		}
		
		function json_fix_cyr($json_str) { 
			return $json_str; 
		}
		
		//$u->stats = $u->getStats($u->info['id'],0);
		
		if(!isset($CRON_CORE)) {
			if($u->info['online']<time()-30)
			{
				mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");
			}
		}
		
		$btl->is = $u->is;
		$btl->items = $u->items;
		$btl->info = $btl->battleInfo($u->info['battle']);

		if(!isset($btl->info['id']))
		{
			if($u->info['battle']==-1)
			{
				//завершаем поединок
				$upd = mysql_query('UPDATE `users` SET `battle` = "0",`online` = "'.time().'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				if(!$upd)
				{
					if(!isset($CRON_CORE)) {
						die('ќшибка завершени€ поединка.');
					}
				}else{
					echo '<script>location="main.php";</script>';
				}
			}else{
				mysql_query('UPDATE `users` SET `battle` = "0" WHERE `battle` = "'.$u->info['battle'].'" LIMIT 100');
				if(!isset($CRON_CORE)) {
					die('<script>location="main.php";</script>');
				}
			}
		}else{
			//получаем массив с игроками в бою
				$btl->teamsTake();
				
			if(isset($_POST['useitem']) && $btl->testUsersLive() == true) {
				$magic->useItems((int)$_POST['useitem']);
				if($u->error!='') {
					echo '<font color=red><center><b>'.$u->error.'</b></center></font>';
				}
			}
				
			//заносим удары,приемы,эффекты и т.д.
				//удар
					if(isset($_POST['atack']) && isset($_POST['block']))
					{
						$btl->addAtack();
					}
				//прием
					if(isset($_POST['usepriem']) && $btl->testUsersLive() == true)
					{
						$priem->pruse($_POST['usepriem']);
					}
				//используем закл€тие / пирожки
					
					
			//проводим действи€ (удары, использование приемов, если есть возможность нанести удар или использовать прием)			
				//if(!isset($_POST['usepriem'])) {
					$btl->testActions();
				//}
			//авто-смена противника, либо просто смена противника
				if($u->stats['hpNow']>=1)
				{
					//ручна€ смена
					if(isset($_POST['smn']) && $_POST['smn']!='none')
					{
						/* ---------------- */
						$_POST['smn'] = iconv('UTF-8', 'windows-1251', $_POST['smn']);
						$uidz = mysql_fetch_array(mysql_query('SELECT `id`,`inUser` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['smn']).'" AND `battle` = "'.$u->info['battle'].'" LIMIT 1'));
						if($uidz['inUser']>0)
						{
							$uidz['id'] = $uidz['inUser'];
						}
						$rsm = $btl->smena($uidz['id'],false);
						if($rsm!=1)
						{
							echo '<font color=red><center><b>'.$rsm.'</b></center></font>';
						}
						unset($rsm);
						$js .= 'smena_login = \'none\';';
					}
					//авто-смена
					if($u->info['enemy']==0 || $btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']<=0 || isset($btl->ga[$u->info['id']][$u->info['enemy']]))
					{
						$btl->autoSmena();
					}
				}else{
					$btl->mainStatus = 3;
				}
			//получаем данные о поединке
				
			//получаем данные о логе бо€
				
			//≈сли бой сыгран - завершаем
			if(!isset($_POST['usepriem'])) {
				if($btl->info['team_win']==-1)
				{
					$btl->testFinish();
				}else{
					$btl->testFinish();
				}
			}
			if($btl->info['team_win']==-1)
			{
				$js .= $btl->genTeams($u->info['id']);
			}else{
				$btl->mainStatus = 3;
				$btl->e = $u->btl_txt;
			}
			
			if(!isset($CRON_CORE)) {
				$js .= $btl->myInfo($u->info['id'],1);
				//выводим данные	
				if($btl->e!='')
				{
					echo '<font color="red"><center><b>'.$btl->e.'</b></center></font>';
				}
				if(isset($btl->ga[$u->info['id']][$u->info['enemy']]))
				{
					if($u->info['hpNow']>=1) {
						$btl->mainStatus = 2;		
					}
				}else{
					if($u->info['enemy']!=0 && $btl->info['team_win']==-1 && $u->info['hpNow']>=1)
					{
						$js .= $btl->myInfo($u->info['enemy'],2);
					}
				}
				if($btl->info['izlom']>0)
				{
					$js .= 'volna('.(1+$btl->info['izlomRoundSee']).');';
				}
					$i = 1;
					while($i<=7)
					{
						if($btl->users[$btl->uids[$u->info['id']]]['tactic'.$i]<0)
						{
							$btl->users[$btl->uids[$u->info['id']]]['tactic'.$i] = 0;
						}
						if($btl->users[$btl->uids[$u->info['id']]]['tactic'.$i]>25 && $i<7)
						{
							$btl->users[$btl->uids[$u->info['id']]]['tactic'.$i] = 25;
						}
						$i++;
					}
				$atk1 = 0;
				if(!isset($CRON_CORE)) {$rsys = $u->sys_see(0);}
				if($rsys != '') {
					$js .= $rsys;
				}
				unset($rsys);
				if(isset($btl->ga[$u->info['enemy']][$u->info['id']]))
				{
					$atk1 = 1;
				}
			}
						
			$rehtml = '';
			if(!isset($CRON_CORE)) {
				$js .= '$("#priems").html("'.$priem->seeMy(2).'");';
				//if(!isset($_POST['usepriem'])) {
					$jslog = $btl->lookLog();
					if( $jslog != '' ) {
						$js .= '</script><font color=red><b>...</b></font><script>top.btlclearlog();'.$jslog;
					}
					unset($jslog);
				//}
			$rehtml .= '<script type="text/javascript">eatk='.$atk1.';
			if(document.getElementById("nabito")!=undefined)
			{
				document.getElementById("nabito").innerHTML = "'.(floor($btl->users[$btl->uids[$u->info['id']]]['battle_yron'])).'";
			}
			if(document.getElementById("expmaybe")!=undefined)
			{
				document.getElementById("expmaybe").innerHTML = "'.(floor($btl->users[$btl->uids[$u->info['id']]]['battle_exp'])).'";
			}
			if(document.getElementById("timer_out")!=undefined)
			{
				document.getElementById("timer_out").innerHTML = "'.round(($btl->info['timeout']/60),2).'";
			}
			$(\'#pers_magic\').html("'.$u->btlMagicList().'");
			g_iCount = 30;
			noconnect = 15;
			connect = 1;
			if(document.getElementById("go_btn")!=undefined)
			{
				document.getElementById("go_btn").disabled = "";
			}
			if(document.getElementById("reflesh_btn")!=undefined)
			{
				document.getElementById("reflesh_btn").disabled = "";
			}
			za = '.(0+$btl->stats[$btl->uids[$u->info['id']]]['zona']).'; genZoneAtack();
			zb = '.(0+$btl->testZonbVis()).'; genZoneBlock();
			refleshPoints();
			tactic(1,'.(0+$btl->users[$btl->uids[$u->info['id']]]['tactic1']).');
			tactic(2,'.(0+$btl->users[$btl->uids[$u->info['id']]]['tactic2']).');
			tactic(3,'.(0+$btl->users[$btl->uids[$u->info['id']]]['tactic3']).');
			tactic(4,'.(0+$btl->users[$btl->uids[$u->info['id']]]['tactic4']).');
			tactic(5,'.(0+$btl->users[$btl->uids[$u->info['id']]]['tactic5']).');
			tactic(6,'.(0+floor($btl->users[$btl->uids[$u->info['id']]]['tactic6'])).');
			smnpty='.(0+$u->info['smena']).';
			mainstatus('.$btl->mainStatus.');
			tactic(7,"'.(0+$btl->users[$btl->uids[$u->info['id']]]['tactic7']).'");
			smena_alls = "0";
			ggcode="'.$code.'";
			'.$js.'
			</script>';
			
			echo ($rehtml);
			
			if( $btl->cached == true ) {
				$btl->clear_cache_start();
			}
			
			unset($atk1);
		}
			echo '<script>ggcode="'.$code.'";if(t057!=null){clearTimeout(t057);}</script>';
		}
}
?>