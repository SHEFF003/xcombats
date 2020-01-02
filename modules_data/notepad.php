<?php
if(!defined('GAME'))
{
	die();
}
$notepad = mysql_fetch_array(mysql_query('SELECT * FROM `notepad` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
if(isset($_POST['notepad'])) {
	$_POST['text'] = htmlspecialchars($_POST['text']);
	if(!isset($notepad['id'])) {
		mysql_query('INSERT INTO `notepad` (`uid`,`time`,`text`) VALUES ("'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($_POST['notepad']).'")');
	}else{
		mysql_query('UPDATE `notepad` SET `time` = "'.time().'",`text` = "'.mysql_real_escape_string($_POST['notepad']).'" WHERE `id` = "'.$notepad['id'].'" LIMIT 1');
	}
	$notepad['text'] = $_POST['notepad'];
	$notepad['time'] = time();
}
?>
<script type="text/javascript" src="js/jquery.js"></script>
<TABLE cellspacing=0 cellpadding=2 width="100%">
<TR>
<TD valign="top" style="vertical-align: top; ">
<center>
<h3>Блокнот персонажа &quot;<?=$u->info['login']?>&quot;</h3><br>
<form method="post" action="main.php?notepad=1">
<textarea style="width:80%; font-size:14px; height:380px" name="notepad" id="notepad">
<?=$notepad['text']?>
</textarea><br>
<input class="btn1" type="submit" value="Сохранить изменения">
<br><div id="informtxt"></div>
<? if($notepad['time'] > 0) { ?>
<br><span style="color:#999">Дата последнего изменения: <?=date('d.m.Y H:i',$notepad['time'])?></span>
<? } ?>
</form>
</center>
</TD>
<TD width="200" valign="top"><TABLE align="right" cellpadding=2 cellspacing=0>
  <TR>
  <TD style="vertical-align: top; text-align: right; "><INPUT type='button' value='Обновить' style='width: 75px' onclick='location="main.php?notepad=1"'>
  &nbsp;<INPUT TYPE=button value="Вернуться" style='width: 75px' onClick="location='main.php';"></TD>
  </TR>
</TABLE></TD>
</TR>
</TABLE>
<script>
var maxLen = 50000;
$('#notepad').keyup( function(){
	var $this = $(this);
	if($this.val().length >= maxLen) {
		$this.val($this.val().substr(0, maxLen));			
	}
	$('#informtxt').html('(Осталось символов: ' + (maxLen - $this.val().length) + ')');
});
$('#notepad').keydown( function(){
	var $this = $(this);
	if($this.val().length >= maxLen) {
		$this.val($this.val().substr(0, maxLen));			
	}
	$('#informtxt').html('(Осталось символов: ' + (maxLen - $this.val().length) + ')');			
});
$('#informtxt').html('(Осталось символов: ' + (maxLen - $('#notepad').val().length) + ')');
</script>