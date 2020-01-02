<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='poklon') {
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
                    <div style="position:relative; cursor: pointer; width: 500px;" id="ione"><img src="http://img.xcombats.com/city/poklon_ang.jpg" id="img_ione" width="500" height="268"  border="1"/>
					<!--Надписи локаций под картинками-->
				  <a bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></a>
				  <a bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.415&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.417',1); ?>">Памятник Справедливости</a>
				  <a bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></a>
				  <a bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.414&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.418',1); ?>">Памятник Милосердию</a>
				  <a bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></a>
				  <a bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.404&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.416',1); ?>">Памятник Падальщика</a><br>
				  <a bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></a>
				  <a bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">Центральная Площадь</a>
                      <div style="position: absolute; left: 4px; right: 0px; z-index: 1500; top: 5px;">
                      	<div id="frvrks" style="position:relative;"></div>
                      </div>              
                      <div style="position: absolute; left: 10px; top: 141px; width: 48px; height: 94px; z-index: 90;"><img <? thisInfRm('1.180.0.416'); ?> src="http://img.xcombats.com/city/cap_160_stat2d.gif" width="48" height="94" title="" class="aFilter" /></div>                    
                      <div style="position: absolute; left: 350px; top: 75px; width: 77px; height: 165px; z-index: 90;"><img <? thisInfRm('1.180.0.418'); ?> src="http://img.xcombats.com/city/miloserdie.gif" width="77" height="165" title="" class="aFilter" /></div>                    
                      <div style="position: absolute; left: 190px; top: 65px; width: 127px; height: 170px; z-index: 10;"><img <? thisInfRm('1.180.0.417'); ?> src="http://img.xcombats.com/city/spravedlivost.gif" width="127" height="170" title="" class="aFilter" /></div>
                      <div style="position: absolute; left: 450px; top: 175px; width: 38px; height: 55px; z-index: 90;"><img <? thisInfRm('1.180.0.9'); ?> src="http://img.xcombats.com/city/arr_right_png2.png" width="38" height="55" title="" class="aFilter" /></div>
                      <div id="snow"></div>
                      <?php echo $goline; ?>
                      <div id="buttons_on_image" style="cursor:pointer; position:absolute; bottom:5px; right:25px; font-weight:bold; color:#D8D8D8; font-size:10px;">
                      <a style="color:#D8D8D8" href="http://forum.xcombats.com/" target="_blank">Форум</a>
                      </div>
                    </div>
                    </td>
                  </tr>
                </table>   
                <div style="display:none; height:0px " id="moveto"></div>     
              <div align="right" style="padding: 3px;"></div></td>
          <td>
          </td>
        </tr>
      </table>
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