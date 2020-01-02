<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='a_fontan')
{
	
	if(isset($_GET['useloc1'])) {
		//Восстанавливаем НР и МР
		$allmn = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `fontan_hp` WHERE `date` = "'.date('dmY').'" AND `city` = "'.$u->info['city'].'" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 1'));
	    $allmn = $allmn[0];
		if($allmn > 0) {
			$re = 'Вы уже пили из фонтана сегодня...';
		}else{
			$re = 'Вы попили воды и почувствовали прилив энергии!';
			$u->stats['hpNow'] = $u->stats['hpAll'];
			$u->stats['mpNow'] = $u->stats['mpAll'];
			mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpNow'].'",`mpNow` = "'.$u->stats['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('INSERT INTO `fontan_hp` (`uid`,`date`,`time`,`city`) VALUES ("'.$u->info['id'].'","'.date('dmY').'","'.time().'","'.$u->info['city'].'")');
		}
	}elseif(isset($_GET['luckloc1'])) {
		//бросаем 1 кр.
		if($u->info['level'] > 3) {
			if($u->info['money'] < 1) {
				$re = 'Требуется 1 кр.';
			}else{
			    $allmn = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `fontan` WHERE `date` = "'.date('dmY').'" AND `city` = "'.$u->info['city'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 50'));
			    $allmn = $allmn[0];
				if($allmn > 49) {
					$re = 'В фонтан возможно кинуть не более 50 монеток в сутки';
				}else{
					$u->info['money'] -= 1;
					$rmn = 0;
					if(rand(0,100000) < 3890) {
						$rmn = floor(rand(200,3978)/100);
						$re = 'Фортуна на вашей стороне! Вы выиграли '.$rmn.'кр.';
						$u->info['money'] += $rmn;
					}else{
						$re = 'Вы бросили монетку, но ничего не произошло :(';
					}
					mysql_query('INSERT INTO `fontan` (`uid`,`time`,`date`,`win`,`money`,`city`) VALUES ("'.$u->info['id'].'","'.time().'","'.date('dmY').'","'.$rmn.'","1","'.$u->info['city'].'")');
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				}
			}
		}else{
			$re = 'Уровень маловат ;)';
		}
	}
?>
<style>
body
{
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div>
        <?
		if($re != '') {
			echo '<font style="float:left" color="red"><b>'.$re.'</b></font>';
		}
		?>
    </td>
    <td width="200"><div align="right">
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
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="javascript:void(0)" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">Центральная площадь</a></td>
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
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td align="center">
    <table border="0" width="900">
      <tr>
        <td height="100" colspan="5" align="center" valign="top">&nbsp;
          <p><a href="?luckloc1=1">Бросить монетку</a> (<strong>1</strong>кр.) <span style="margin-left:400px;margin-right:20px;"><a href="?useloc1=1">Выпить воды</a></span></p><br /><br /></td>
      </tr>
      <tr>
        <td width="80">&nbsp;</td>
        <td width="310" align="left" valign="top"> В сутки можно бросить в фонтан не больше 50 монеток.<br />
          <br />
          <?
		  $allmn = mysql_fetch_array(mysql_query('SELECT SUM(`win`) FROM `fontan` WHERE `delete` = "0"'));
		  $allmn = 0+$allmn[0];
		  ?>
          Всего выиграно: <b><?=$allmn?></b>кр.<br />
          <br />
          <b>20</b> последних выигрышей:<br />
          <?
		  $sp = mysql_query('SELECT * FROM `fontan` WHERE `delete` = "0" AND `win` > 0 ORDER BY `id` DESC LIMIT 20');
		  while($pl = mysql_fetch_array($sp)) {
			  echo $u->microLogin($pl['uid'],1).' - '.$pl['win'].'кр.<br>';
		  }
		  ?>
          <br /></td>
        <td width="90">&nbsp;</td>
        <td width="300" valign="top"><br />
          <table style="
							white-space: pre-wrap;
							white-space: -moz-pre-wrap;
							white-space: -pre-wrap;
							white-space: -o-pre-wrap;
							word-wrap: break-word;">
              	<?
				if(isset($_GET['delm'])) {
					if($u->info['admin'] > 0 || ($u->info['align'] > 1 && $u->info['align'] < 2)) {
						mysql_query('UPDATE `fontan_text` SET `delete` = "'.$u->info['id'].'" WHERE `id` = "'.mysql_real_escape_string($_GET['delm']).'" LIMIT 1');
						echo '<font color=red><b>Сообщение стерто</b></font>';
					}
				}
				if(isset($_POST['message'])) {
					$tstm = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `fontan_text` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.(time()-10).' LIMIT 1'));
					if($u->info['molch1'] < time() && $u->info['level'] > 0 && $u->info['align'] != 2 && $tstm[0] < 1) {
						if(str_replace(' ','',str_replace('	','',$_POST['message']))) {
							mysql_query('INSERT INTO `fontan_text` (`uid`,`time`,`text`) VALUES ("'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string(htmlspecialchars($_POST['message'],NULL,'cp1251')).'")');
							echo '<font color=red><b>Сообщение добавлено</b></font>';
						}else{
							echo '<font color=red><b>Пустое сообщение!</b></font>';
						}
					}else{
						echo '<font color=red><b>Вам пока-что запрещено оставлять пожелания!</b></font>';
					}
				}
				echo '<br>&nbsp; &nbsp; &nbsp; <b>Пожелания!</b><br><br>';
				$sp = mysql_query('SELECT * FROM `fontan_text` WHERE `city` = "'.$u->info['city'].'" AND `delete` = "0" ORDER BY `id` DESC LIMIT 10');
				while($pl = mysql_fetch_array($sp)){
				?>
                <tr>
                <td align="left" valign="top" style="
							white-space: pre-wrap;
							white-space: -moz-pre-wrap;
							white-space: -pre-wrap;
							white-space: -o-pre-wrap;
							word-wrap: break-word;"><div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;"><?=$u->microLogin($pl['uid'],1)?>:<?
                            if($u->info['admin'] > 0 || ($u->info['align'] > 1 && $u->info['align'] < 2)) {
							echo ' <a href="?delm='.$pl['id'].'"><small>стереть</small></a>';	
							}
							?><br /><?=$pl['text']?></div></td>
                </tr>
				<?
				}
				?>
          </table>
          <center>
            <!-- pages -->
            
            <!-- -->
          </center>
          <form action='main.php' method='post'>
            Оставить сообщение:<br />
            <input type="text" name="message" size="35" value="" maxlength="150" />
            <br />
            <input type="submit" name="add" value="Добавить" />
          </form>
          <div id="hint3" class="ahint"></div></td>
        <td width="70">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
<p>
  <? } ?>
</p>