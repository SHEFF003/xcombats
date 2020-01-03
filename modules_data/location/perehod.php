<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='perehod')
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
                    <div style="position:relative; cursor: pointer; width: 500;" id="ione"><img src="http://img.xcombats.com/i/images/300x225/club/navig2.jpg" id="img_ione" width="500" height="240"  border="1"/>
                      <div style="position:absolute; left:264px; top:106px; width:175px; height:37px; z-index:90;"><img <? thisInfRm('1.180.0.0'); ?> src="http://img.xcombats.com/i/images/subimages/map_zal2.gif" id="mo_1.180.0.0" width="175" height="37" /></div>
                      <div style="position:absolute; left:47px; top:120px; width:135px; height:29px; z-index:90;"><img src="http://img.xcombats.com/i/images/subimages/map_zal3.gif" width="135" height="29"  class="aFilter" id="mo_1.180.0.1" /></div>
                      <div style="position:absolute; left:81px; top:102px; width:88px; height:15px; z-index:90;"><img <? thisInfRm('1.180.0.2'); ?> src="http://img.xcombats.com/i/images/subimages/map_zal1.gif" width="88" height="15" class="aFilter" id="mo_1.180.0.2" /></div>
                      <div style="position:absolute; left:167px; top:107px; width:16px; height:18px; z-index:90;"><img src="http://img.xcombats.com/i/images/subimages/fl1.gif" width="16" height="18" title="Вы находитесь в Комнате перехода"    /></div>
                      <div id="snow"></div>
                      <? echo $goline; ?>
                    </div>
                    </td>
                  </tr>
                </table>  
                <div style="display:none; height:0px " id="moveto"></div>      
              <div align="right" style="text-align:justify; padding: 3px;"><small>&laquo;<? echo $c['title3']; ?>&raquo; приветствует Вас, <b><? echo $u->info['login']; ?></b>.<br />
                Чтобы сражаться с остальными на равных, вам нужно распределить начальные характеристики.<br />
                Для этого нажмите на <a href='/main.php?skills=1&amp;side=1'>Способности</a>, а затем, нажимая на <img src="http://img.xcombats.com/i/plus.gif" width="9" height="9" /> / <img src="http://img.xcombats.com/i/minus.gif" width="9" height="9" />, сформируйте своего персонажа.<br />
                Подробнее о значении характеристик можно узнать в <b>Библиотеке</b>.<br />
                Распределив все характеристики нажмите на кнопку
                <input type="button" class="btn" value='Вернуться' disabled="disabled" />
                <br />
                Для проведения боя нажмите на кнопку
                <input type='button' value='Поединки' class="btn" disabled="disabled" />
                <br />
                Выберите раздел &quot;Новички&quot;.<br />
                Более подробно о поединках можно прочитать в <b>Библиотеке</b><br />
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