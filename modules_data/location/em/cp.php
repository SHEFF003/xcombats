<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='em/cp') {
	if( date('H') >= 22 || date('H') < 6 ) { $nd = 'night'; }else{ $nd = 'day'; }
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
                <?
                if( $u->error != '' ) {
					if( $re != '' ) {
						$re .= '<br>';
					}
					$re .= $u->error;
				}
				if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    <div style="position:relative; cursor: pointer; width: 500px;" id="ione"><img src="http://img.xcombats.com/i/images/300x225/emerald/<?=$nd?>/em_bg1_1.jpg" id="img_ione" width="500" height="268"  border="1"/>
                      <div style="position: absolute; left: 4px; right: 0px; z-index: 1500; top: 5px;">
                      	<div id="frvrks" style="position:relative;"></div>
                      </div>
                      <!-- <div style="position:absolute; left:166px; top:149px; width:27px; height:55px; z-index:99;"><img  src="http://img.xcombats.com/i/images/300x225/capital/2pm.gif" width="27" height="55" title="Памятник Мироздателю" /></div> -->
        
                        <!-- <div style="position:absolute; left:100px; top:146px; width:75px; height:90px; z-index:99;"><img src="http://img.xcombats.com/i/images/300x225/capital/stellav.gif" width="75" height="90" title="Стела выбора" class="aFilter" /></div> -->
                      <div style="position: absolute; left: 16px; top: 202px; width: 41px; height: 14px; z-index: 910;"><img class="aFilter"  src="http://img.xcombats.com/i/images/300x225/emerald/<?=$nd?>/em_left.gif" width="41" height="14" <? thisInfRm('1.180.0.x'); ?> /></div>
                      
                      <div style="position: absolute; left: 438px; top: 202px; width: 41px; height: 14px; z-index: 910;"><img class="aFilter"  src="http://img.xcombats.com/i/images/300x225/emerald/<?=$nd?>/em_right.gif" width="41" height="14" <? thisInfRm('7.180.0.387'); ?> /></div>
                      
                      <div style="position: absolute; left: 159px; top: 92px; width: 75px; height: 90px; z-index: 910;"><img class="aFilter"  src="http://img.xcombats.com/i/images/300x225/emerald/<?=$nd?>/em_portal.gif" width="75" height="90" <? thisInfRm('7.180.0.385'); ?> /></div>
                                            
                      <div id="snow"></div>
                        
                      <!-- -->
                      <?
					  //Праздничные здания
					  //if($u->info['admin'] > 0 ) {
						  //Хэллоуин
						 /* if( (date('m') == 11 && date('d') <= 6) || (date('m') == 10 && date('d') == 31) ) {
						?>
                        <div style="position: absolute; left: 230px; top: 162px; width: 32px; height: 43px; z-index: 91;"><img onclick="location.href='main.php?talk=5'" title="Диалог с Тыквоголовым" src="http://img.xcombats.com/loca/cp11/sun_pmd.gif" width="32" height="43" title="" class="aFilter" /></div>
                        <?  
						  }elseif( date('m') == 12 || date('m') == 1 ) {
							 //Елка НГ
						?>
						<div style="position: absolute; left: 212px; top: 133px; width: 32px; height: 43px; z-index: 91;"><img <? thisInfRm('1.180.0.208'); ?> src="http://img.xcombats.com/newyear2014.png" width="60" height="90" title="" class="aFilter" /></div>
						<?
                          }*/
					  //}
					  ?>
                      <!-- -->
                      
                      <div id="snow"></div>
                      <? echo $goline; ?>
                      <div id="buttons_on_image" style="cursor:pointer; position:absolute; bottom:5px; right:25px; font-weight:bold; color:#D8D8D8; font-size:10px;">
                     	 <?
						 if( date('H') >= 22 || date('H') < 6 ) {
							 echo '<a style="color:#D8D8D8" style="cursor:pointer" onclick="top.useMagic(\'Нападение на персонажа\',\'night_atack\',\'pal_button8.gif\',1,\'main.php?nightatack=1\');">Напасть</a> &nbsp; ';
						 }
						 ?>
                         <a style="color:#D8D8D8" href="http://xcombats.com/forum/" target="_blank">Форум</a>
                      </div>
                    </div>
                    </td>
                  </tr>
                </table>   
                <div style="display:none; height:0px " id="moveto"></div>     
              <div align="right" style="padding: 3px;"></div></td>
          <td>
              <!-- <br /><span class="menutop"><nobr>Комната для новичков</nobr></span>-->
          </td>
        </tr>
      </table>
      	<small>
        <HR>
        <strong>Внимание!</strong> Никогда и никому не говорите пароль от своего персонажа. Не вводите пароль на других сайтах, типа "новый город", "лотерея", "там, где все дают на халяву". Пароль не нужен ни паладинам, ни кланам, ни администрации, <U>только взломщикам</U> для кражи вашего героя.<BR>
        <em>Администрация.</em></small> <BR>
       <? echo $rowonmax; ?><BR>
        
      </div></td>
  </tr>
</table>
<?
}

?>