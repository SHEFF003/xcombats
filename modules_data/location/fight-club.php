<?php
die();
include('modules_data/location/fight-club.database.php');
if(!defined('GAME')){
	die();
}
?><script type="text/javascript" src="/js/jquery.locations.js"></script>
<link href="/css/fightclub.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top"><?php
      $usee = $u->getInfoPers($u->info['id'],0);
	  if($usee!=false){
		  echo $usee[0];
	  }else{
		  echo 'information is lost.';
	  }
	?></td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right"><?php
    if($u->error!=''){
		echo '<font color="red"><b>'.$u->error.'</b></font>';
	}
	?><table  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td><?php
          if($re!=''){
			  echo '<font color="red"><b>'.$re.'</b></font>';
		  }
		  ?><table width="500" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td id="ViewLocation"><script type="text/javascript">
					var json = <?php echo json_encode($Response); ?>;
					var tgo = <?php echo ($tmGo*10); ?>;
					var tgol = <?php echo ($tmGol*10); ?>;
					ViewLocation(json);
					</script></td>
                  </tr>
                </table>   
              <div style="display:none; height:0px " id="moveto"></div>     
              <div align="right" style="padding: 3px;"><small>&laquo;<?php echo $c['title3']; ?>&raquo; приветствует Вас, <b><?php echo $u->info['login']; ?></b>.<br /><?php
             	 if($u->info['level']<6){
               	 	echo '
                 	Вам все время кажется что за вами следят? Чудится, что случайный попутчик мечтает всадить вам топор в спину? При совершении очередной покупки в гос. магазине мучает ощущение, что вас обманули? Кажется, что симпатичная девушка напротив смотрит на вас как на пищу? Успокойтесь, это не паранойя. Это реалии Capital city. Города Тьмы.
                 	';
                 }else{
                 	echo 'Возможно, вы ошиблись этажом - настоящие сражения проходят этажом выше.';
                 }
			?></small></div></td>
        </tr>
      </table>
      	<small>
        <HR><div id="ajaxButtons"<?php
        $hgo = $u->testHome(); if(!isset($hgo['id'])){
			echo'<INPUT onclick="location.href=\'main.php?homeworld=' . $code . '\';" class="btn" value="Возврат" type="button" name="combats2">';
        }
        unset($hgo);
        ?><input <?php thisInfRm('1.180.0.225'); ?> onClick="location='main.php?loc=1.180.0.225';" class="btn" value="Казино" type="button" name="combats" />
          <INPUT onclick="location.href='main.php?clubmap=<? echo $code; ?>';" class="btn" value="Карта клуба" type="button" name="combats2">
          <INPUT id="forum" class="btn" onclick="window.open('http://xcombats.com/forum/', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')" value="Форум" type="button" name="forum">
          <INPUT class="btn" onclick="window.open('/encicl/help/top1.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')" value="Подсказка" type="button">
          <INPUT class="btn" value="Объекты" type="button">
        </div><br />
        <strong>Внимание!</strong> Никогда и никому не говорите пароль от своего персонажа. Не вводите пароль на других сайтах, типа "новый город", "лотерея", "там, где все дают на халяву". Пароль не нужен ни паладинам, ни кланам, ни администрации, <U>только взломщикам</U> для кражи вашего героя.<BR>
        <em>Администрация.</em></small> <BR>
        <?php echo $rowonmax; ?><BR>
      </div></td>
  </tr>
</table>