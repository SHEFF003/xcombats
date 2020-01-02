<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='an/bk2')
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } ?>
    </td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right">
      <? if($u->error!=''){ echo '<font color="red"><b>'.$u->error.'</b></font>'; } ?>
      <table  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td>
                <? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    <div style="position:relative; cursor: pointer; width: 500;" id="ione"><img src="http://img.xcombats.com/i/images/300x225/club/navig3.jpg" id="img_ione" width="500" height="240"  border="1"/>
                      <div style="position:absolute; left:162px; top:125px; width:16px; height:18px; z-index:90;"><img src="http://img.xcombats.com/i/images/300x225/fl1.gif" width="16" height="18" title="Вы находитесь в '<? echo $u->room['name']; ?>'" /></div>
					  <div style="position:absolute; left:281px; top:173px; width:122px; height:31px; z-index:90;"><img  <? thisInfRm('2.180.0.260'); ?> onclick="location='main.php?loc=2.180.0.260';" onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_sec5.gif" width="122" height="31"  class="aFilter"  /></div>
                      <div style="position:absolute; left:23px; top:116px; width:120px; height:35px; z-index:90;"><img onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_2stair.gif" width="120" height="35"  class="aFilter"  /></div>
<div style="position:absolute; left:118px; top:175px; width:101px; height:37px; z-index:90;"><img  onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_sec3.gif" width="101" height="37"  class="aFilter"  /></div>

                      <div style="position:absolute; left:36px; top:41px; width:63px; height:40px; z-index:90;"><img  <? thisInfRm('1.180.0.XX'); ?> onclick="location='main.php?loc=1.180.0.XX';" onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_sec2.gif" width="63" height="40"  class="aFilter"  /></div>
					  
                      <div style="position:absolute; left:24px; top:180px; width:91px; height:43px; z-index:90;"><img  <? thisInfRm('2.180.0.230'); ?> onclick="location='main.php?loc=2.180.0.230';" onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_sec1.gif" width="91" height="43"  class="aFilter"  /></div>
					  <div style="position:absolute; left:122px; top:52px; width:123px; height:39px; z-index:90;"><img onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_sec7.gif" width="123" height="39"  class="aFilter"  /></div>
					  <div style="position:absolute; left:305px; top:51px; width:123px; height:30px; z-index:90;"><img  <? thisInfRm('2.180.0.246'); ?> onclick="location='main.php?loc=2.180.0.246';" onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_sec6.gif" width="123" height="30"  class="aFilter"  /></div>
					  <div style="position:absolute; left:391px; top:120px; width:89px; height:32px; z-index:90;"><img onmouseover="this.className='aFilterhover';" onmouseout="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/map_sec4.gif" width="89" height="32"  class="aFilter"  /></div>
                      <div id="snow"></div>
                      <? echo $goline; ?>
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