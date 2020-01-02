<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='an/zalu')
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } ?>
    </td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right">
      <table  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td>
                <? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    <div style="position:relative; cursor: pointer;" id="ione">
                        <img src="http://img.xcombats.com/i/images/300x225/club/navig1.jpg" alt="" name="img_ione" width="500" height="240" border="1" id="img_ione"/>
                        <div style="position:absolute; left:337px; top:117px; width:16px; height:18px; z-index:90;"><img src="http://img.xcombats.com/i/images/300x225/fl1.gif" width="16" height="18" title="Вы находитесь в '<? echo $u->room['name']; ?>'"  /></div>
                        <div style="position:absolute; left:354px; top:115px; width:120px; height:35px; z-index:90;"><img src="http://img.xcombats.com/i/images/300x225/map_halls.gif" width="120" height="35" /></div>
                        <div style="position: absolute; left: 53px; top: 47px; width: 122px; height: 31px; z-index: 90;"><img <? thisInfRm('2.180.0.402'); ?> onClick="location='main.php?loc=2.180.0.402';" src="http://img.xcombats.com/i/images/300x225/map_klub5-1.gif" width="123" height="30" class="aFilter" /></div>
                        <div style="position:absolute; left:78px; top:24px; width:76px; height:18px; z-index:90;"><img src="http://img.xcombats.com/i/images/300x225/map_zalu6.gif" onclick="alert('Проход через зал паладинов');" width="76" height="18" class="aFilter"  /></div>	
                        <div style="position:absolute; left:17px; top:122px; width:79px; height:32px; z-index:90;"><img src="http://img.xcombats.com/i/images/300x225/map_zalu3.gif" width="78" height="33" onclick="alert('Проход через зал паладинов');"  /></div>
                        <div style="position:absolute; left:393px; top:170px; width:100px; height:35px; z-index:90;"><img <? thisInfRm('2.180.0.230'); ?> onClick="location='main.php?loc=2.180.0.230';" src="http://img.xcombats.com/i/images/300x225/map_zalu7.gif" width="100" height="35" class="aFilter" /></div> 
                        <? echo $goline; ?>
                        <div id="snow"></div>
                    </div>
                    </td>
                  </tr>
                </table>    
                <div style="display:none; height:0px " id="moveto"></div>    
              <div align="right" style="padding: 3px;"><small>&laquo;<? echo $c['title3']; ?>&raquo; приветствует Вас, <b><? echo $u->info['login']; ?></b>.<br />
			  	<?php
             	 if($u->info['level']<6) 
              	 {
               	 	echo '
                 	Вам все время кажется что за вами следят? Чудится, что случайный попутчик мечтает всадить вам топор в спину? При совершении очередной покупки в гос. магазине мучает ощущение, что вас обманули? Кажется, что симпатичная девушка напротив смотрит на вас как на пищу? Успокойтесь, это не паранойя. Это реалии Capital city. Города Тьмы.
                 	';
                 }else{
                 	echo 'Возможно, вы ошиблись этажом - настоящие сражения проходят этажом выше.';
                 } ?>
            </small></div></td>
          <td>
              <!-- <br /><span class="menutop"><nobr>Комната для новичков</nobr></span>-->
          </td>
        </tr>
      </table>
      	<small>
        <HR>
        <? $hgo = $u->testHome(); if(!isset($hgo['id'])){ ?><INPUT onclick="location.href='main.php?homeworld=<? echo $code; ?>';" class="btn" value="Возврат" type="button" name="combats2"><? } unset($hgo); ?>
          <INPUT onclick="location.href='main.php?clubmap=<? echo $code; ?>';" class="btn" value="Карта клуба" type="button" name="combats2">
          <INPUT id="forum" class="btn" onclick="window.open('http://xcombats.com/forum/', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')" value="Форум" type="button" name="forum">
          <INPUT class="btn" onclick="window.open('/encicl/help/top1.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')" value="Подсказка" type="button">
          <INPUT class="btn" value="Объекты" type="button">
        <br />
        <strong>Внимание!</strong> Никогда и никому не говорите пароль от своего персонажа. Не вводите пароль на других сайтах, типа "новый город", "лотерея", "там, где все дают на халяву". Пароль не нужен ни паладинам, ни кланам, ни администрации, <U>только взломщикам</U> для кражи вашего героя.<BR>
        <em>Администрация.</em></small> <BR>
       <? echo $rowonmax; ?><BR>
        
      </div></td>
  </tr>
</table>
<?
}

?>