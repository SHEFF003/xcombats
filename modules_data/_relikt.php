<?
if(!defined('GAME')) {
  die();
}

?>
<style>
.fs { width: 350px; }
.fr { margin-bottom: 35px; }
div.item, div.thingTailContainer > div.item {
  border: 1px solid black;
  border-radius: 8px;
  float: left;
  height: 120px;
  margin: 0 0 30px 30px;
  width: 120px;
  position: relative;
}
div.item > div.title, div.thingTailContainer > div.item > div.title {
  font-size: 75%;
  font-weight: bold;
  text-align: center;
  color: maroon;
}
div.item > div.img, div.thingTailContainer > div.item > div.img {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0px;
  left: 0px;
}
div.item > div.control {
  padding: 0 3px;
  font-size: 90%;
  position: absolute;
  bottom: 0px;
  width: 100%;
  overflow: hidden;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
}
img.slot.valign[slottype="12"] {
  margin-top: -12px;
}
img.slot.halign[slottype="12"] {
  margin-left: -20px;
}
img.slot.valign {
  position: absolute;
  top: 50%;
}
img.slot.halign {
  position: absolute;
  left: 50%;
}
img.slot[slottype="12"] {
  height: 25px;
}
img.slot[slottype="12"] {
  width: 40px;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!--<script type="text/javascript" src="js/jquery.js"></script>-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
$(function() {
  $("#dialog").dialog({
    autoOpen: false,
    height: 300,
    width: 510,
    modal: true
  });
  $("#opener").click(function() {
    $("#dialog").dialog("open");
  });
  $(".ui-button-text").attr('title', 'Закрыть');
});
</script>
<div id="dialog" title="Персональные реликты">
  <div class="item">
    <div class="title">Заклятие молчания</div>
    <div class="img"><img class="slot valign halign" slottype="12" src="http://img.xcombats.com/i/items/spell_starenergy.gif"></div>
    <div class="control">
      Цена: 1.00 екр.
	  <div style="text-align: center;"><a href="javascript: void(0);">купить 1 шт.</a></div>
    </div>
  </div>
  <div class="item">
    <div class="title">Заклятие молчания</div>
    <div class="img"><img class="slot valign halign" slottype="12" src="http://img.xcombats.com/i/items/spell_starenergy.gif"></div>
    <div class="control">
      Цена: 1.00 екр.
	  <div style="text-align: center;"><a href="javascript: void(0);">купить 1 шт.</a></div>
    </div>
  </div>
  <div class="item">
    <div class="title">Заклятие молчания</div>
    <div class="img"><img class="slot valign halign" slottype="12" src="http://img.xcombats.com/i/items/spell_starenergy.gif"></div>
    <div class="control">
      Цена: 1.00 екр.
	  <div style="text-align: center;"><a href="javascript: void(0);">купить 1 шт.</a></div>
    </div>
  </div>
  <div class="item">
    <div class="title">Заклятие молчания</div>
    <div class="img"><img class="slot valign halign" slottype="12" src="http://img.xcombats.com/i/items/spell_starenergy.gif"></div>
    <div class="control">
      Цена: 1.00 екр.
	  <div style="text-align: center;"><a href="javascript: void(0);">купить 1 шт.</a></div>
    </div>
  </div>
  <div class="item">
    <div class="title">Заклятие молчания</div>
    <div class="img"><img class="slot valign halign" slottype="12" src="http://img.xcombats.com/i/items/spell_starenergy.gif"></div>
    <div class="control">
      Цена: 1.00 екр.
	  <div style="text-align: center;"><a href="javascript: void(0);">купить 1 шт.</a></div>
    </div>
  </div>
  <div class="item">
    <div class="title">Заклятие молчания</div>
    <div class="img"><img class="slot valign halign" slottype="12" src="http://img.xcombats.com/i/items/spell_starenergy.gif"></div>
    <div class="control">
      Цена: 1.00 екр.
	  <div style="text-align: center;"><a href="javascript: void(0);">купить 1 шт.</a></div>
    </div>
  </div>
</div>

<table cellspacing=0 cellpadding=2 width=100%>
  <tr>
    <td style="width: 40%; vertical-align: top; "><br />
	  <table cellspacing=0 cellpadding=2 style="width: 100%; ">
	    <tr>
		  <td align=center><h4>Панель Реликтов</h4></td>
		</tr>
		
		<tr>
		  <td>
		    <p>Персональные реликты <sup><a href="javascript: void(0);" id="opener">[Купить]</a></sup></p>
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
		  </td>
		</tr>
		<tr><td><div style="height: 1px; background-color: #999999; margin: 3px; width; 40%;"></div></td></tr>
		<tr>
		  <td>
		    <p>Клановые реликты <sup><a href="javascript: void(0);">[Купить]</a></sup></p>
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
			<img src="http://img.xcombats.com/i/items/spell_starenergy.gif" />
			<img src="http://img.xcombats.com/i/items/spell_protect2.gif" />
		  </td>
		</tr>
		<tr><td><div style="height: 1px; background-color: #999999; margin: 3px; width; 40%;"></div></td></tr>
	  </table>
	</td>
	<td style="width: 5%; vertical-align: top; ">&nbsp;</td>
    <td style="width: 25%; vertical-align: top; text-align: right; ">
	  <input class="btnnew" type='button' value='Обновить' style='width: 75px' onclick='location="/main.php?relikt=1&rnd=<? echo $code; ?>"';' />
	  <input type="button" value="Вернуться" class="btnnew" style="width: 75px" onclick='location="/main.php"'>
	</td>
  </tr>
</table>