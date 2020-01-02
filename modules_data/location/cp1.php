<?
if(!defined('GAME'))
{
	die();
}

$tattack = '';

if( date('H') >= 22 || date('H') < 6 ) {
	$tattack = '<a style="color:#D8D8D8" style="cursor:pointer" onclick="top.useMagic(\'Нападение на персонажа\',\'night_atack\',\'pal_button8.gif\',1,\'main.php?nightatack=1\');">Напасть</a> &nbsp; ';
}

if($u->room['file']=='cp1')
{
	if(date("H")>=6 && date("H")<22) {
		$now = 'day';
	}else{
		$now = 'night';
	}
?>
<script>
<?
if(date("H")<6 || date("H")>=22) 
{
?>
function AtackNoWindow()
{
	var dt = document.getElementById('atackDiv');
	if(dt.style.display=='none')
	{
		dt.style.display = '';
	}else{
		dt.style.display = 'none';
	}
}
<?
}
?>
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } ?>
    </td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right">
      <table  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td><? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
            <table width="500" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div style="position:relative; cursor: pointer;" id="ione"> <img src="http://img.xcombats.com/city/capitalcity/<? echo $now; ?>/city_capres1.jpg" alt="" name="img_ione" width="500" height="268" border="1" id="img_ione"/>
                  <div id="buttons_on_image" style="cursor:pointer; font-weight:bold; color:#D8D8D8; font-size:10px;"> <? echo $tattack; ?> &nbsp; <span onMouseMove="this.runtimeStyle.color = 'white';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="window.open('http://xcombats.com/forum', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')">Форум</span> &nbsp; </div>
                  <script language="javascript" type="text/javascript">
                        <!--
                        if(document.getElementById('ione'))
                        {
                            document.getElementById('ione').appendChild(document.getElementById('buttons_on_image'));
                            document.getElementById('buttons_on_image').style.position = 'absolute';
                            document.getElementById('buttons_on_image').style.bottom = '8px';
                            document.getElementById('buttons_on_image').style.right = '23px';
                        }else{
                            document.getElementById('buttons_on_image').style.display = 'none';
                        }
                        -->
                      </script>
                  <div style="position:absolute; left:142px; top:17px; width:236px; height:157px; z-index:90;"><img <? thisInfRm('1.180.0.3'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2klub.gif" width="236" height="157"  class="aFilter" /></div>
                  <div style="position: absolute; left: 376px; top: 118px; width: 79px; height: 88px; z-index: 91;"><img  <? thisInfRm('1.180.0.371'); ?> src="http://img.xcombats.com/city/capitalcity/day/loto.gif" class="aFilter" width="86" height="63" /></div>
                  <div style="position: absolute; left: 178px; top: 135px; width: 73px; height: 47px; z-index: 100;"><img  <? thisInfRm('1.180.0.10'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2shop.gif" width="73" height="47"  class="aFilter" /></div>
                  <div style="position: absolute; left: 42px; top: 145px; width: 48px; height: 36px; z-index: 91;"><img   <? thisInfRm('1.180.0.272'); ?> src="http://img.xcombats.com/city/capitalcity/day/2comission.gif" width="48" height="36"  class="aFilter" /></div>
                  <div style="position: absolute; left: 236px; top: 143px; width: 71px; height: 45px; z-index: 91;"><img  <? thisInfRm('1.180.0.210'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2remont.gif" width="71" height="45"  class="aFilter" /></div>
                  <div style="position: absolute; left: 276px; top: 181px; width: 27px; height: 55px; z-index: 93;"><img  <? thisInfRm('1.180.0.x'); ?> src="http://img.xcombats.com/city/capitalcity/day/2pm.gif" class="aFilter" width="27" height="55" /></div>
                  <div style="position:absolute; left:300px; top:130px; width:111px; height:72px; z-index:92;"><img <? thisInfRm('1.180.0.226'); ?> src="http://img.xcombats.com/city/capitalcity/day/2pochta.gif" width="111" height="72" class="aFilter" /></div>
                  <div style="position:absolute; left:73px; top:135px; width:92px; height:62px; z-index:100;"><img  <? thisInfRm('1.180.0.14'); ?>  onclick="alert('Не работает. Находится на реконструкции.')"  onMouseOver="this.className='aFilterhover';" onMouseOut="this.className='aFilter';" src="http://img.xcombats.com/city/capitalcity/day/2vokzal.gif" width="92" height="62" class="aFilter" /></div>
                  <!-- <div style="position:absolute; left:166px; top:149px; width:27px; height:55px; z-index:99;"><img  src="http://img.xcombats.com/i/images/300x225/capital/2pm.gif" width="27" height="55" title="Памятник Мироздателю" /></div> -->
                  <!--
                  <div style="position: absolute; left: 142px; top: 129px; width: 75px; height: 90px; z-index: 99;"><img src="http://img.xcombats.com/i/images/300x225/capital/stellav.gif" width="75" height="90" <? thisInfRm('1.180.0.420'); ?> class="aFilter" /></div>
                  -->
                  <div style="position:absolute; left:446px; top:153px; width:30px; height:54px; z-index:94;"><img <? thisInfRm('1.180.0.11'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2strelka.gif" width="30" height="54" class="aFilter" /></div>
                  
                  <div style="position:absolute; left:16px; top:155px; width:30px; height:54px; z-index:910;"><img  src="http://img.xcombats.com/i/images/300x225/capital/3strelka.gif" width="30" height="53" <? thisInfRm('1.180.0.323'); ?> class="aFilter" /></div>
                  
					<?
					if( date('m') == 12 || date('m') == 1 ) {
						//Елка НГ
					?>
					<div style="position: absolute; left: 145px; top: 125px; width: 32px; height: 43px; z-index: 100;"><img <? thisInfRm('1.180.0.208'); ?> src="http://img.anticombats.com/newyear2014.png" width="60" height="90" title="" class="aFilter" /></div>
					<?
                    }
					?>
                  
                  <div id="snow"></div>
                  <? echo $goline; ?> </div></td>
              </tr>
            </table>
            <?
if(date("H")<6 || date("H")>=22) 
{
?>
            <div align="center" id="atackDiv" style="display:none;">
              <form method="post" action="main.php">
                <table width="300" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><div style="width:300px; padding:3px; margin:7px; background-color:#CCCCCC; border:1px solid #575757;"> Введите логин жертвы:<br />
                      <input name="atack" type="text" id="atack" size="35" maxlength="30" />
                      <input type="submit" name="button" id="button" class="btn" value="OK" />
                    </div></td>
                  </tr>
                </table>
              </form>
            </div>
            <?
}
?>
            <!-- -->
            <div style="width:500px; text-align:left; background-color:#D3D3D3;"> <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.3'); ?>>Бойцовский Клуб</a></span> <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.323'); ?>>Парковая улица</a></span> <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.10'); ?>>Магазин</a></span> <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.210'); ?>>Ремонтная мастерская</a></span> <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.272'); ?>>Комиссионный магазин</a></span> <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.11'); ?>>Страшилкина улица</a></span> <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.14'); ?>>Вокзал</a></span> 
            <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.371'); ?>>Лото</a></span>
            <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('1.180.0.226'); ?>>Почта</a></span>
            </div>
            <!-- -->
            <div style="display:none; height:0px " id="moveto"></div></td>
          <td><!-- <br /><span class="menutop"><nobr>Комната для новичков</nobr></span>--></td>
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