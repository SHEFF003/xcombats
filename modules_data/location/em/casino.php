<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='em/casino')
{
	$rz = 0;
	
	if( $u->room['id'] == 388 ) {
		$rz = 0;
	}elseif( $u->room['id'] == 389 ) {
		$rz = 1;
	}elseif( $u->room['id'] == 390 ) {
		$rz = 2;
	}elseif( $u->room['id'] == 391 ) {
		$rz = 3;
	}elseif( $u->room['id'] == 392 ) {
		$rz = 4;
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
	
	.kzn {
		
	}
	
	.kzn1 {
		background-color:#ffffff;
	}
	
	.kzn0 {
		
	}
	
	.kzn0:hover {
		background-color:#a9afc0;
		cursor:pointer;
	}
	</style>
	<script src="../../../../../Scripts/swfobject_modified.js" type="text/javascript"></script>
<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3"><? echo $u->room['name'];
	if( $rz == 1 ) {
		echo ' &quot;Рулетка&quot;';
	}
	?></div>
	  <small style="color:#999999;"><br />
      </small>
      <div style="background-color:#d2d2d2">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td onclick="location.href='main.php?loc=7.180.0.388&rnd=1'" class="kzn0 kzn<? if( $rz == 0 ) { echo 1; } ?>" width="20%" align="center"><strong>Залы:</strong></td>
            <td onclick="location.href='main.php?loc=7.180.0.389&rnd=1'" class="kzn0 kzn<? if( $rz == 1 ) { echo 1; } ?>" width="20%" align="center">Рулетка</td>
            <td onclick="location.href='main.php?loc=7.180.0.390&rnd=1'" class="kzn0 kzn<? if( $rz == 2 ) { echo 1; } ?>" width="20%" align="center">Однорукий бандит</td>
            <!--
            <td onclick="location.href='main.php?loc=7.180.0.391&rnd=1'" class="kzn0 kzn<? if( $rz == 3 ) { echo 1; } ?>" width="20%" align="center">Блэкджек</td>
            <td onclick="location.href='main.php?loc=7.180.0.392&rnd=1'" class="kzn0 kzn<? if( $rz == 4 ) { echo 1; } ?>" width="20%" align="center">Девятка</td>
         	-->
          </tr>
        </table>
      </div>
		<?
		if( isset($u->bank['id']) ) {
		?>
        <br /><br /><center>
        Банковский счет для игры: №<b><?=$u->bank['id']?></b> <a href="main.php?bank_exit=1"><img style="vertical-align:bottom" src="http://img.xcombats.com/i/clear.gif" width="13" height="13" title="Сменить счет" /></a>
        </center>
		<?
		}
		if( !isset($u->bank['id']) ) {
		?>
        <form method="post" action="main.php">
        <br /><br />
        <center>
        <b>Перед началом игры выберите счет в банке:</b><br />
        Счет: &nbsp; &nbsp; <select name="bank" style="width:144px;">
          <?
		  $sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `block` = "0"');
		  while( $pl = mysql_fetch_array($sp)) {
          ?><option value="<?=$pl['id']?>"><?=$pl['id']?></option><? }?>
        </select><br />
        Пароль: <input name="bankpsw" type="password" style="width:144px;margin-left:2px;" /><br />
        <input type="submit" value="Воспользоваться счетом" />
        </center>
        <br /><br />
        </form>
        <?
		}elseif( $rz == 0 ) {
		?>
        <br /><center>
        Выберите игровой зал
        </center>
        <br /><br />
        <?
        }elseif( $rz == 1 ) {
		?>
        <center><br />
          <?
		 // if( $u->info['admin'] > 0 ) {
			$bnk1 = 0;
			$sm = $u->testAction('`city` = "'.$u->info['city'].'" AND `vars` = "casino_balance" LIMIT 1',1);
			$bnk1 += $sm['vals'];
			if( $bnk1 <= 0 ) {
		  		echo 'За все время выиграли '.(-$bnk1).'.00 екр.<br><br>'; 
			}else{
				echo 'За все время проиграли '.($bnk1).'.00 екр.<br><br>'; 
			}
			$sp = mysql_query('SELECT * FROM `ruletka_coin` ORDER BY `id`');
			$bag1d = array();
			while( $pl = mysql_fetch_array($sp)) {
				$bag1d[$pl['game_id']][$pl['uid']] += $pl['money'];
				if( $bag1d[$pl['game_id']][$pl['uid']] > 100 ) {
					echo '<br>'.date('d.m.Y H:i',$pl['time']).' <b>'.$u->microLogin($pl['uid'],1).'</b> сделал баговую ставку '.$bag1d[$pl['game_id']][$pl['uid']].' екр.';
				}
			}
		  //}
		  ?>
          <object id="ruletka" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="940" height="340">
            <param name="movie" value="casino/ruletka.swf" />
            <param name="quality" value="high" />
            <param name="wmode" value="opaque" />
            <param name="swfversion" value="6.0.65.0" />
            <!-- Этот тег param предлагает пользователям Flash Player 6.0 r65 и более поздних версий загрузить последнюю версию Flash Player. Удалите его, если не хотите, чтобы пользователи видели запрос. -->
            <param name="expressinstall" value="Scripts/expressInstall.swf" />
            <param name="BGCOLOR" value="#dedfde" />
            <!-- Следующий тег object не поддерживается браузером Internet Explorer. Поэтому скройте его от Internet Explorer при помощи IECC. -->
            <!--[if !IE]>-->
            <object type="application/x-shockwave-flash" data="casino/ruletka.swf" width="940" height="340">
              <!--<![endif]-->
              <param name="quality" value="high" />
              <param name="wmode" value="opaque" />
              <param name="swfversion" value="6.0.65.0" />
              <param name="expressinstall" value="Scripts/expressInstall.swf" />
              <param name="BGCOLOR" value="#dedfde" />
              <!-- Браузер отображает следующее альтернативное содержимое для пользователей Flash Player 6.0 и более старых версий. -->
              <div>
                <h4>Для содержимого этой страницы требуется более новая версия Adobe Flash Player.</h4>
                <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Получить проигрыватель Adobe Flash Player" /></a></p>
              </div>
              <!--[if !IE]>-->
            </object>
            <!--<![endif]-->
          </object><br /><br />
			<script type="text/javascript">
            swfobject.registerObject("ruletka");
            </script>
        </center>
        <?	
		}elseif( $rz == 2 ) {
		?>
        <center><br />
          <object id="bandit" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="800" height="340">
            <param name="movie" value="casino/castle.swf" />
            <param name="quality" value="high" />
            <param name="wmode" value="opaque" />
            <param name="swfversion" value="6.0.65.0" />
            <!-- Этот тег param предлагает пользователям Flash Player 6.0 r65 и более поздних версий загрузить последнюю версию Flash Player. Удалите его, если не хотите, чтобы пользователи видели запрос. -->
            <param name="expressinstall" value="Scripts/expressInstall.swf" />
            <param name="BGCOLOR" value="#dedfde" />
            <!-- Следующий тег object не поддерживается браузером Internet Explorer. Поэтому скройте его от Internet Explorer при помощи IECC. -->
            <!--[if !IE]>-->
            <object type="application/x-shockwave-flash" data="casino/castle.swf" width="800" height="340">
              <!--<![endif]-->
              <param name="quality" value="high" />
              <param name="wmode" value="opaque" />
              <param name="swfversion" value="6.0.65.0" />
              <param name="expressinstall" value="Scripts/expressInstall.swf" />
              <param name="BGCOLOR" value="#dedfde" />
              <!-- Браузер отображает следующее альтернативное содержимое для пользователей Flash Player 6.0 и более старых версий. -->
              <div>
                <h4>Для содержимого этой страницы требуется более новая версия Adobe Flash Player.</h4>
                <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Получить проигрыватель Adobe Flash Player" /></a></p>
              </div>
              <!--[if !IE]>-->
            </object>
            <!--<![endif]-->
          </object><br /><br />
        </center>
        <?	
		}elseif( $rz == 3 ) {
			echo '<center><br><br><b>Игровой стол временно закрыт</b><br><br></center>';
		}elseif( $rz == 3 ) {
			$min_s = 1;
			$max_s = 10;
		?>
        <center><br />
          <object id="bl" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="940" height="400">
            <param name="movie" value="casino/blackjack_v1.09.swf?minBet=<?=$min_s?>&maxBet=<?=$max_s?>" />
            <param name="quality" value="high" />
            <param name="wmode" value="opaque" />
            <param name="swfversion" value="6.0.65.0" />
            <!-- Этот тег param предлагает пользователям Flash Player 6.0 r65 и более поздних версий загрузить последнюю версию Flash Player. Удалите его, если не хотите, чтобы пользователи видели запрос. -->
            <param name="expressinstall" value="Scripts/expressInstall.swf" />
            <param name="BGCOLOR" value="#dedfde" />
            <!-- Следующий тег object не поддерживается браузером Internet Explorer. Поэтому скройте его от Internet Explorer при помощи IECC. -->
            <!--[if !IE]>-->
            <object type="application/x-shockwave-flash" data="casino/blackjack_v1.09.swf?minBet=<?=$min_s?>&maxBet=<?=$max_s?>" width="940" height="400">
              <!--<![endif]-->
              <param name="quality" value="high" />
              <param name="wmode" value="opaque" />
              <param name="swfversion" value="6.0.65.0" />
              <param name="expressinstall" value="Scripts/expressInstall.swf" />
              <param name="BGCOLOR" value="#dedfde" />
              <!-- Браузер отображает следующее альтернативное содержимое для пользователей Flash Player 6.0 и более старых версий. -->
              <div>
                <h4>Для содержимого этой страницы требуется более новая версия Adobe Flash Player.</h4>
                <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Получить проигрыватель Adobe Flash Player" /></a></p>
              </div>
              <!--[if !IE]>-->
            </object>
            <!--<![endif]-->
          </object><br /><br />
        </center>
        <?	
		}
		?>
        <br />
        <center>
        <font color="#A9a9a9">Внимание! Все игры проводятся на екр. и Администрация не несет ответственности за возможные исходы игры, включая сбои и т.д.</font>
        </center>
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=7.180.0.387&rnd=<? echo $code; ?>';" title="<? thisInfRm('7.180.0.387',1); ?>">Страшилкина улица</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div>
	  <br />
      <? if(isset($u->bank['id'])) { ?>
      <div align="right"><small>	  У вас в наличии: <b style="color:#339900;"><?php echo round($u->bank['money2'],2); ?> екр.</b> &nbsp;
      </small>      
      </div><? } ?>
	  <br />
    <br />
	</div>
	</td>
</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>
