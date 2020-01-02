<?
if(!defined('GAME'))
{
	die();
}
$tattack = '';

if($u->room['file']=='cp')
{
	if(date("H")>=6 && date("H")<22) {
	$now = 'day';
	} else { $now = 'night'; }
	if($u->info['level'] >= 4)
	{
		if(date("H")>=6 && date("H")<22) 
		{
		$tattack = '<span onMouseMove="this.runtimeStyle.color = \'white\';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="">Нападение доступно с 22 до 6 ч.</span>';
		} else {
		if(isset($_POST['attack'])) 
		{
		$magic->magicCentralAttack();
		}
		$tattack = '<span onMouseMove="this.runtimeStyle.color = \'white\';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="findlogin(\'Напасть\',\'attack\',\'\',\'\');">Напасть</span>';
		}
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; }  ?>
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
                      <img src="http://img.xcombats.com/i/images/300x225/capital/<? echo $now; ?>/city_capres1.jpg" alt="" name="img_ione" width="500" height="268" border="1" id="img_ione"/>
                      <div id="buttons_on_image" style="cursor:pointer; font-weight:bold; color:#D8D8D8; font-size:10px;">
                          <? echo $tattack; ?>
                          &nbsp;
                          <span onMouseMove="this.runtimeStyle.color = 'white';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="window.open('http://xcombats.com/forum', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')">Форум</span> &nbsp;
                      </div>
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
                        <div style="position:absolute; left:142px; top:17px; width:236px; height:157px; z-index:90;"><img <? thisInfRm('1.180.0.3'); ?> onClick="location='main.php?loc=1.180.0.3';" src="http://img.xcombats.com/i/images/300x225/capital/2klub.gif" width="236" height="157"  class="aFilter" /></div>    
                                 
                        <div style="position:absolute; left:195px; top:143px; width:73px; height:47px; z-index:100;"><img  <? thisInfRm('1.180.0.10'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2shop.gif" width="73" height="47"  class="aFilter" /></div>
        				
                        <div style="position:absolute; left:80px; top:197px; width:42px; height:35px; z-index:100;"><img  <? thisInfRm('1.180.0.293'); ?> src="http://img.xcombats.com/i/images/300x225/demons/dm_dungeon.gif" width="42" height="35"  class="aFilter" /></div>
        
                        <div style="position:absolute; left:50px; top:143px; width:48px; height:36px; z-index:91;"><img   <? thisInfRm('1.180.0.272'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2comission.gif" width="48" height="36"  class="aFilter" /></div>
        
                        <div style="position:absolute; left:244px; top:170px; width:71px; height:45px; z-index:91;"><img  <? thisInfRm('1.180.0.210'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2remont.gif" width="71" height="45"  class="aFilter" /></div>
        
                        <div style="position:absolute; left:303px; top:150px; width:79px; height:88px; z-index:93;"><img  <? thisInfRm('1.180.0.x'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2cerkov.gif" width="79" height="88" /></div>
        
                        <div style="position:absolute; left:330px; top:130px; width:111px; height:72px; z-index:92;"><img <? thisInfRm('1.180.0.226'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2pochta.gif" width="111" height="72" class="aFilter" /></div>
        
                        <div style="position:absolute; left:73px; top:135px; width:92px; height:62px; z-index:92;"><img   <? thisInfRm('1.180.0.14'); ?>  onclick="alert('Не работает. Находится на реконструкции.')"  onMouseOver="this.className='aFilterhover';" onMouseOut="this.className='aFilter';" src="http://img.xcombats.com/i/images/300x225/capital/2vokzal.gif" width="92" height="62" class="aFilter" /></div>
                        
                        <!-- <div style="position:absolute; left:166px; top:149px; width:27px; height:55px; z-index:99;"><img  src="http://img.xcombats.com/i/images/300x225/capital/2pm.gif" width="27" height="55" title="Памятник Мироздателю" /></div> -->
        

                        <div style="position:absolute; left:140px; top:119px; width:75px; height:90px; z-index:101;"><img src="http://img.xcombats.com/i/elka.gif" width="75" height="90" <? thisInfRm('1.180.0.208'); ?> onClick="location='main.php?loc=1.180.0.208';" class="aFilter" /></div>

                        <div style="position:absolute; left:446px; top:153px; width:30px; height:54px; z-index:94;"><img <? thisInfRm('1.180.0.11'); ?> src="http://img.xcombats.com/i/images/300x225/capital/2strelka.gif" width="30" height="54" title="Страшилкина улица" class="aFilter" /></div>
        
                        <div style="position:absolute; left:16px; top:155px; width:30px; height:54px; z-index:910;"><img  src="http://img.xcombats.com/i/images/300x225/capital/3strelka.gif" width="30" height="53" title="Парк развлечений" /></div>
                        <div id="snow"></div>
                        <? echo $goline; ?>
                      </div>
                    </td>
                  </tr>
                </table>        
              <div align="right" style="padding: 3px;"><small>&laquo;<? echo $c['title3']; ?>&raquo; приветствует Вас, <b><? echo $u->info['login']; ?></b>.<br />
              </small></div></td>
          <td><div style="visibility:hidden; height:0px " id="moveto"></div>
              <!-- <br /><span class="menutop"><nobr>Комната для новичков</nobr></span>-->
          </td>
        </tr>
      </table>
      	<small>
        <HR>
        <? $hgo = $u->testHome(); if(!isset($hgo['id'])){ ?><INPUT onclick="location.href='main.php?homeworld=<? echo $code; ?>';" class="btn" value="Возврат" type="button" name="combats2"><? } unset($hgo); ?>
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