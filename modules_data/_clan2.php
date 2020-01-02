<?
if(!defined('GAME'))
{
	die();

}

if(isset($_POST['loginos'])) {
	if($u->info['admin'] > 0 || $u->info['align'] == 1.99) {
		$inf = mysql_fetch_array(mysql_query('SELECT `id`,`align` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['loginos']).'" LIMIT 1'));
		if(isset($inf['id']) && (floor($u->info['align']) == floor($inf['align']) || $u->info['admin'] > 0)) {
			mysql_query('UPDATE `users` SET `mod_zvanie` = "'.mysql_real_escape_string($_POST['loginos2']).'" WHERE `id` = "'.$inf['id'].'" LIMIT 1');
		}else{
			echo 'Нельзя изменить звание данному персонажу...';
		}
	}
}
		
?>
<body style="margin:10px; margin-top:5px;" bgcolor=e2e0e0>
<style>

.modpow {

background-color:#ddd5bf;

}

.mt {

background-color:#b1a993;

padding-left:10px;

padding-right:10px;

padding-top:5px;

padding-bottom:5px;

}

.md {

padding:10px;

}

</style>

<script>

function openMod(title,dat)

{

var d = document.getElementById('useMagic');

if(d!=undefined)

{

document.getElementById('modtitle').innerHTML = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">'+title+'</td><td width="30" valign="top"><div align="right"><a title="Закрыть окно" onClick="closeMod(); return false;" href="#">x</a></div></td></tr></table>';

document.getElementById('moddata').innerHTML = dat;

d.style.display = '';

}

}



function closeMod()

{

var d = document.getElementById('useMagic');

if(d!=undefined)

{

document.getElementById('modtitle').innerHTML = '';

document.getElementById('moddata').innerHTML = '';

d.style.display = 'none';

}

}

</script>

<div id="useMagic" style="display:none; position:absolute; border:solid 1px #776f59; left: 50px; top: 186px;" class="modpow">

<div class="mt" id="modtitle"></div><div class="md" id="moddata"></div></div>
<center>
  <h3><?
$ckln = '';
if(floor($u->info['align'])==1) {
	echo 'Орден Света';
}else{
	echo 'Армада';
}
?></h3></center>
<input style="float:right;margin:1px" type="button" value="Вернуться" onClick="document.location='main.php'"><input style="float:right;margin:1px" type="button" value="Обновить" onClick="document.location='main.php?clan&rnd=<?=$code;?>'"><br>

<TABLE width="100%" cellpadding="5" cellspacing="10">
<TR><TD valign=top>
<? if($u->info['align'] == '1.99' || $u->info['admin']>0) { ?>
<form action="main.php?clan=1" method="post">
<b>Редактирование звания:</b><br>
Логин: &nbsp; <input id="loginos" width="180" name="loginos" value=""><br>
Звание: <input style="margin-left:2px;" width="180" id="loginos2" name="loginos2" value="">
<br>
<input type="submit" value="Сохранить статус">
</form>
<? } ?>
</TD><TD valign=top align=right>

<TABLE cellspacing=0 cellpadding=2 width="300"><TR><TD>
<center>
<?
$ckln = '';
if(floor($u->info['align'])==1) {
	$ckln = 'paladins';
}else{
	$ckln = 'tarmans';
}
?>
<h4><a href="javascript:void(0)" onClick="top.chat.addto('<?=$ckln?>','private')"><IMG border=0 SRC=http://img.xcombats.com/i/lock.gif WIDTH=20 HEIGHT=15 ALT="Приватно"></a> Соклановцы</H4>
		<table bgcolor=#eeeeee>
			<tr>
			<td align=left>
<?
$res1 = mysql_query("SELECT `login`,`id`,`align`,`level`,`mod_zvanie`,`online`,`clan_prava` FROM `users` WHERE `align` > '".mysql_real_escape_string(floor($u->info['align']))."' AND `real` = 1  AND `align` < '".mysql_real_escape_string(ceil($u->info['align']))."' ORDER BY `online` DESC");
while($data = mysql_fetch_array($res1)) {
				if ($data['online']>time()-120) {
					echo '<A href="javascript:void(0)" onClick="top.chat.addto(\''.$data['login'].'\',\'private\')"><img src="http://img.xcombats.com/i/lock.gif" width=20 height=15></A>
					<img title="'.$res['name'].'" src="http://img.xcombats.com/i/align/align'.$data['align'].'.gif"><b>'.$data['login'].'</b> ['.$data['level'].']<a href="info/'.$data['id'].'" target="_blank"><img title="Инф. о '.$data['login'].'" src="http://img.xcombats.com/i/inf_capitalcity.gif"></a>';
					if ($data['clan_prava'] == 'glava') {
						echo ' - <b>Глава клана</b>';
					} else {
							echo ' - ',$data['mod_zvanie'],'';
					}
					echo '<BR>';
					}
					elseif ($data['online']<time()-120) {
							echo '<img src="http://img.xcombats.com/i/offline.gif" width=20 height=15>
							<img src="http://img.xcombats.com/i/align/align'.$data['align'].'.gif"><font color=grey><b>'.$data['login'].'</b> ['.$data['level'].']<a href="info/'.$data['id'].'" target="_blank"><img title="Инф. о '.$data['login'].'" src="http://img.xcombats.com/inf_dis.gif"></a>';
							if ($data['clan_prava'] == 'glava') {
								echo ' - <b>Глава клана</b>';
							} else {
								echo ' - ',$data['mod_zvanie'],'</font>';
							}
							echo '<BR>';
						}
//echo '<img title="Свет" src="http://img.xcombats.com/i/align/align'.$data['align'].'.gif"><img title="'.$res['name'].'" src="http://img.xcombats.com/i/clan/'.$res['name_mini'].'.gif"><b>'.$data['login'].'</b> ['.$data['level'].']<a href="info/'.$data['id'].'" target="_blank"><img title="Инф. о '.$data['login'].'" src="http://img.xcombats.com/i/inf_capitalcity.gif"></a> - '.$data['mod_zvanie'].'<br>';
}
?>
			</td>
			</tr>
		</table>
</center>

</TD></TR><TR><TD>
<small>(список обновляется <strike>в полночь</strike> каждый раз)</small>
</TD></TR></TABLE><br />
</TD>
</TR>
</TABLE>
</FORM>
</body>
<hr>