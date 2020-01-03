<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='fe/magportal')
{
	
	if(isset($_POST['p1'])) {
		if($_POST['p1'] == 'Бездна') {
			mysql_query('UPDATE `users` SET `room` = "354" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');
		}elseif($_POST['p1'] == 'Канализация') {
			mysql_query('UPDATE `users` SET `room` = "188" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');
		}elseif($_POST['p1'] == 'Катакомбы') {
			mysql_query('UPDATE `users` SET `room` = "293" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');
		} else {
			die('<script>location="main.php";</script>');
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
    </div></td>
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
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=6.180.0.100&rnd=<? echo $code; ?>';" title="<? thisInfRm('6.180.0.100',1); ?>">Центральная Площадь</a></td>
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
    <td>Вы можете телепортироваться в любую из пещер:</td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="post" action="main.php?go_psh=1">
      <p>
        
	  <? if($u->info['admin'] == 1) {?>
		<label>
          <input disabled="disabled" name="p1" type="radio" id="p1_0" value="Пещера Тысячи Проклятий" />
          <img src="http://img.xcombats.com/i/city_ico2/mooncity.gif" width="33" height="19" style="vertical-align:bottom" />
          Пещера Тысячи Проклятий (Moon City)</label>
        <br />
        <label>
		
          <input name="p1" type="radio" id="p1_1" value="Бездна" />
          <img src="http://img.xcombats.com/i/city_ico2/angelscity.gif" width="33" height="19" style="vertical-align:bottom" />
          Бездна (Angels City)</label>
        <br />
        <label>
          <input disabled="disabled" type="radio" name="p1" value="Пещеры Мглы" id="p1_2" />
          <img src="http://img.xcombats.com/i/city_ico2/sandcity.gif" width="33" height="19" style="vertical-align:bottom" />
          Пещеры Мглы (Sand City)</label>
        <br />
        <label>
          <input  disabled="disabled" type="radio" name="p1" value="Потерянный Вход" id="p1_4" />
          <img src="http://img.xcombats.com/i/city_ico2/emeraldscity.gif" width="33" height="19" style="vertical-align:bottom" />
          Потерянный Вход (Emeralds City)</label>
        <br />
        
        <label>
          <input disabled="disabled" type="radio" name="p1" value="Грибница" id="p1_6" />
          <img src="http://img.xcombats.com/i/city_ico2/suncity.gif" width="33" height="19" style="vertical-align:bottom" />
          Грибница (Sun City)</label>
        <br />
        <label>
          <input disabled="disabled" type="radio" name="p1" value="Сторожевая Башня" id="p1_7" />
          <img src="http://img.xcombats.com/i/city_ico2/abandonedplain.gif" width="33" height="19" style="vertical-align:bottom" />
          Сторожевая Башня (Abandoned Plain)</label>
        <br />
        <label>
          <input disabled="disabled" type="radio" name="p1" value="Туманные Низины" id="p1_8" />
          <img src="http://img.xcombats.com/i/city_ico2/devilscity.gif" width="33" height="19" style="vertical-align:bottom" />
          Туманные Низины (Devils City)</label>
        <br />
        <label>
          <input disabled="disabled" type="radio" name="p1" value="Гора Легиона" id="p1_9" />
          <img src="http://img.xcombats.com/i/city_ico2/legion.gif" width="33" height="19" style="vertical-align:bottom" />
          Гора Легиона (Abandoned Plain)</label>
        <br />
        <label>
          <input disabled="disabled" type="radio" name="p1" value="Ледяная Пещера" id="p1_10" />
          <img src="http://img.xcombats.com/i/city_ico2/snowcity.gif" width="33" height="19" style="vertical-align:bottom" />
          Ледяная Пещера (Snow City)</label>
		  <? }?>
		  <br/>
		  <label>
          <input type="radio" name="p1" value="Канализация" id="p1_5" />
          <img src="http://img.xcombats.com/i/city_ico2/dreamscity.gif" width="33" height="19" style="vertical-align:bottom" />
          Канализация (Dreams City)</label>
		  <label>
		  <br/>
          <input name="p1" type="radio" id="p1_3" value="Катакомбы" />
          <img src="http://img.xcombats.com/i/city_ico2/demonscity.gif" width="33" height="19" style="vertical-align:bottom" />
          Катакомбы (Demons City)</label> 
        </p>
      <p><br />
        <input type="submit" name="button" id="button" value="Телепортироваться" />
      </p>
    </form></td>
  </tr>
</table>
<p>
  <? } ?>
</p>