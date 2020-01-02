<?
if(!defined('GAME')) { die(); }
if($u->room['file']=='ruine_enter') {
	
	$loc_c = array(
		'users' => 2, // сколько человек нужно для старта 
		'money' => '0.00', // сколько кр. нужно для входа
		'free' => 125
	);
	
	if(isset($_GET['join'])) {
		$rz = mysql_fetch_array(mysql_query('SELECT * FROM `ruine_zv` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if(isset($rz['id'])) {
			$u->error = 'Вы уже принимаете участие в турнире';
		}else{
			$rc = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `ruine_zv` LIMIT 1'));
			if( $rc[0] >= $loc_c['users'] ) {
				$u->error = 'Вы опоздали, эта группа уже начала турнир. Попробуйте еще раз...';
			}else{
				if( $u->info['money'] < (int)$loc_c['money'] ) {
					$u->error = 'Для участия в турнире необходимо заплатить '.$loc_c['money'].' кр.';
				}else{
					mysql_query('INSERT INTO `ruine_zv` (
						`city`,`time`,`uid`,`money`
					) VALUES (
						"'.$u->info['city'].'","'.time().'","'.$u->info['id'].'","'.((int)$loc_c['money']).'"
					)');
					mysql_query('UPDATE `users` SET `money` = "'.($u->info['money'] - (int)$loc_c['money']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->error = 'Вы заплатили '.$loc_c['money'].' кр. за участие в турнире.';
				}
			}
			unset($rc);
		}
	}elseif(isset($_GET['cancel'])) {
		$rz = mysql_fetch_array(mysql_query('SELECT * FROM `ruine_zv` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if(!isset($rz['id'])) {
			$u->error = 'Вы не участвуете в турнире';
		}else{
			$rc = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `ruine_zv` LIMIT 1'));
			if( $rc[0] >= $loc_c['users'] ) {
				$u->error = 'Турнир уже начался...';
			}else{
				mysql_query('DELETE FROM `ruine_zv` WHERE `id` = "'.$rz['id'].'" LIMIT 1');
				mysql_query('UPDATE `users` SET `money` = "'.($u->info['money'] + $rz['money']).'" WHERE `id` = "'.$rz['uid'].'" LIMIT 1');
				$u->error = 'Вы вернули '.$loc_c['money'].' кр. и покинули турнирную заявку.';
			}
			unset($rc);
		}
	}
	
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div>
    </td>
    <td width="200" valign="top"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td>
          <table  border="0" cellpadding="0" cellspacing="0">
              <tr align="right" valign="top">
                <td><!-- -->
                    <? echo $goLis; ?>
                    <!-- -->
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                            <tr>
								<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.323&rnd=<? echo $code; ?>';">Большая парковая улица</a></td>
                            </tr>
                        </table>
						</td>
                      </tr>
                  </table></td>
              </tr>
          </table>
          </td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">
		<?
        if($u->error != '') {
            echo '<div><b><font color=red>'.$u->error.'</font></b></div><br>';
        }
        ?>
    	Всего одержано побед в руинах: 0<br><br>
        <?
		//
		$i = 0;
		$sp = mysql_query('SELECT * FROM `ruine_zv`');
		while( $pl = mysql_fetch_array($sp) ) {
			$usr = mysql_fetch_array(mysql_query('SELECT `online`,`money` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
			if( $usr['online'] < time()-600 ) {
				mysql_query('UPDATE `users` SET `money` = "'.($usr['money'] + $pl['money']).'" WHERE `id` = "'.$pl['uid'].'" LIMIT 1');
				mysql_query('DELETE FROM `ruine_zv` WHERE `id` = "'.$pl['id'].'" LIMIT 1');				
			}else{
				$i++;
			}
		}
		//
		if( $i >= $loc_c['users'] ) {
			//
			mysql_query('INSERT INTO `ruine_now` (
				`time_start`,`time_finish`,`t1w`,`t2w`,`tw`
			) VALUES (
				"'.time().'","0","0","0","0"
			)');
			$text_user = '';
			$rid = mysql_insert_id();
			//
			$team = rand(1,2);
			//
			if( $rid > 0 ) {
				//
				$sp = mysql_query('SELECT * FROM `ruine_zv`');
				while( $pl = mysql_fetch_array($sp) ) {
					//
					if( $team == 1 ) {
						$team = 2;
					}else{
						$team = 1;
					}
					//
					$bus = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
					if(isset($bus['id'])) {
						//Создаем бота
						$text_user[$team] .= $u->microLogin($bus['id'],1).', ';
						//
						mysql_query('INSERT INTO `users` (`obraz`,`chatColor`,`align`,`inTurnir`,`molch1`,`molch2`,`activ`,`login`,`room`,`name`,`sex`,`level`,`bithday`) VALUES (
							"'.$bus['obraz'].'","'.$bus['chatColor'].'","'.$bus['align'].'","'.$rid.'","'.$bus['molch1'].'","'.$bus['molch2'].'","0","'.$bus['login'].'","413","'.$bus['name'].'","'.$bus['sex'].'","10","'.date('d.m.Y').'")');
						//
						$inbot = mysql_insert_id(); //айди бота
						if( $inbot > 0 ) {
							//Создаем данные в руинах
							$rx = 0;
							$ry = 0;
							//
							$botst = mysql_fetch_array(mysql_query('SELECT * FROM `ruine_sets` WHERE `uid` = "'.$bus['id'].'" AND `use` = 1 LIMIT 1'));
							if(!isset($botst['id'])) {
								$botst = array(
									's1' => 0,'s2' => 0,'s3' => 0,'s4' => 0,'s5' => 0,'s6' => 0,
									'free' => $loc_c['free']
								);
							}
							//
							mysql_query('INSERT INTO `stats` (`res_x`,`res_y`,`timeGo`,`timeGoL`,`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`)
							VALUES (
								"'.$rx.'","'.$ry.'",
								"'.(time()+180).'","'.(time()+180).'","81","0","'.$inbot.'",
								"s1='.round($botst['s1']+3).'|s2='.round($botst['s2']+3).'|s3='.round($botst['s3']+3).'|s4='.round($botst['s4']+3).'|s5='.round($botst['s5']).'|s6='.round($botst['s6']).'|rinv=40|m9=5|m6=10","0",
								"'.$botst['free'].'","11","'.$rx.'","'.$ry.'"
							)');
							//
							mysql_query('UPDATE `users` SET `room` = "414", `inUser` = "'.$inbot.'" WHERE `id` = "'.$bus['id'].'" LIMIT 1');
							//
							mysql_query('INSERT INTO `ruine_users` (
								`tid`,`team`,`uid`,`bot`,`die`,`last_die`,`money`
							) VALUES (
								"'.$rid.'","'.$team.'","'.$bus['id'].'","'.$inbot.'","0","0","'.$pl['money'].'"
							)');
							//
							mysql_query('DELETE FROM `ruine_zv` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							//
						}
						//
					}
					//
				}
				//
			}
			//
			$text_user[1] = rtrim($text_user[1],', ');
			$text_user[2] = rtrim($text_user[2],', ');
			$text = 'Турнир между '.$text_user[1].' и '.$text_user[2].' начался.';
			//
			mysql_query('INSERT INTO `ruine_logs` (
				`tid`,`time`,`text`
			) VALUES (
				"'.$rid.'","'.time().'","'.mysql_real_escape_string($text).'"
			)');
			//
		}
		//
		$rz = mysql_fetch_array(mysql_query('SELECT * FROM `ruine_zv` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
		echo 'Группа с хаотичным распределением. Набрано '.$i.'/'.$loc_c['users'].' чел. ';
		if( isset($rz['id']) ) {
			echo '<input onClick="location.href=\'http://xcombats.com/main.php?cancel\';" type="button" value="Покинуть группу" class="btnnew">';
		}else{
			echo '<input onClick="location.href=\'http://xcombats.com/main.php?join\';" type="button" value="Присоединиться" class="btnnew">';
		}
		?>
    </td>
    <td align="right" valign="top">
    	<input type="button" value="Обновить" class="btnnew" onclick="location.href='http://xcombats.com/main.php';">
    	<input type="button" value="Профили характеристик" class="btnnew2" onclick="top.winframe('ruine_fm','Профили характеристик',590,480,'http://xcombats.com/ruin_characteristics.php');">
        <input type="button" value="Логи текущих турнира" class="btnnew">
    </td>
  </tr>
</table>
<? } ?>
