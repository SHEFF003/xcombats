<?
		if(!defined('GAME'))
		{
			die();
		
		}
		$res = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."' LIMIT 1"));
		$cpr = explode('|',$u->info['clan_prava']);
		if(isset($_POST['invite']) && ($u->info['clan_prava']=='glava' || $cpr[0]==1))
		{
			$data = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."'"));
			if($u->testAlign( $res['align'] , $data['id'] ) == 0 ) {
				echo 'У персонажа стоит ограничение на смену склонности. Вы не можете выдать данную склонность!';
			}elseif($data['palpro'] < time()) {
				echo 'Нельзя принимать в клан без проверки...';
			}elseif($data['clan']=='0' && $data['align']=='0') {
				$u->insertAlign( $res['align'] , $data['id'] );
				mysql_query("UPDATE `users` SET `align` = '".$res['align']."',`clan` = '".(int)$u->info['clan']."' WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."';");
				if($res['money1']>=100)
				{
					$res['money1'] -= 100;
					mysql_query("UPDATE `clan` SET `money1` = `money1` - 100 WHERE `id` = '".$res['id']."'");	
				}else{
					mysql_query("UPDATE `users` SET `money` = `money` - 100 WHERE `id` = '".$u->info['id']."'");		
				}
			}else{
				echo 'Не выйдет...';
			}
		}
		if(isset($_POST['dissmis']) && ($u->info['clan_prava']=='glava' || $cpr[1]==1))
		{
			$data = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."'"));
			if($data['clan']==$u->info['clan'] && $data['clan_prava']!='glava') {
				$u->deleteAlign( $data['align'] , $data['id'] );
				mysql_query("UPDATE `users` SET `clan` = '0',`align` = '0',`mod_zvanie` = '' WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."';");
				if($res['money1']>=30)
				{
					$res['money1'] -= 30;
					mysql_query("UPDATE `clan` SET `money1` = `money1` - 30 WHERE `id` = '".$res['id']."'");	
				}else{
					mysql_query("UPDATE `users` SET `money` = `money` - 30 WHERE `id` = '".$u->info['id']."'");		
				}
			}else{
				echo 'Не выйдет...';
			}
		}
		if(isset($_POST['rang']) && ($u->info['clan_prava']=='glava' || $cpr[2]==1))
		{
			$data = mysql_fetch_array(mysql_query("SELECT `clan_prava`,`clan` FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."'"));
			if($data['clan']==$u->info['clan'] && $data['clan_prava']!='glava') {
				mysql_query("UPDATE `users` SET `mod_zvanie` = '".mysql_real_escape_string($_POST['rang'])."' WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."';");
			}else{
				echo 'Не выйдет...';
			}
		}
		if(isset($_POST['persedit']) && ($u->info['clan_prava']=='glava' || $cpr[2]==1)) {
			$data = mysql_fetch_array(mysql_query("SELECT `clan` FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."';"));
			if($data['clan']==$u->info['clan']) {
			$edit=1;
			$prava = mysql_fetch_array(mysql_query("SELECT `clan_prava`,`mod_zvanie` FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['logingo'])."';"));
			$edcpr = explode('|',$prava[0]);
			}else{
				echo 'Не выйдет...';
			}
		}
		if($_POST['save']  && ($u->info['clan_prava']=='glava' || $cpr[2]==1)) {
			
			$st = strip_tags($_POST['status']);
			/*
			$st = str_replace('<scr>','<script>',$st);
			$st = str_replace('</scr>','</script>',$st);
			*/
				
			if ($_POST['priem']=='on') { $ecpr[0]=1; } else { $ecpr[0]=0;}
			if ($_POST['vigon']=='on') { $ecpr[1]=1; } else { $ecpr[1]=0;}
			if ($_POST['editpriv']=='on') { $ecpr[2]=1; } else { $ecpr[2]=0;}
			if ($_POST['givekazna']=='on') { $ecpr[3]=1; } else { $ecpr[3]=0;}
			if ($_POST['usekazna']=='on') { $ecpr[4]=1; } else { $ecpr[4]=0;}

			$igogo = implode('|',$ecpr);
			mysql_query("UPDATE `users` SET `mod_zvanie` = '".mysql_real_escape_string($st)."' WHERE `login` = '".mysql_real_escape_string($_POST['login'])."' AND `clan` = '".mysql_real_escape_string($res['id'])."' ORDER BY `id` ASC LIMIT 1");
			
			$prava['mod_zvanie'] = $st;
		if ($cpr[2]==1 || $u->info['clan_prava']=='glava') {
			mysql_query("UPDATE `users` SET `clan_prava` = '".$igogo."' WHERE `login` = '".mysql_real_escape_string($_POST['login'])."' AND `clan` = '".mysql_real_escape_string($res['id'])."' AND `clan_prava` != 'glava' ORDER BY `id` ASC LIMIT 1");
			echo 'Права успешно изменены.';
		}
	}
		if($_POST['igogo'] && $_POST['lojit'] && ($u->info['clan_prava']=='glava' || $cpr[3]==1) ) {
			$_POST['igogo'] = round($_POST['igogo'],2);
			if($_POST['igogo']<0 OR $_POST['igogo']>$u->info['money']){echo'не-а';}else{
				if(mysql_query("UPDATE `clan` SET `money1` = `money1`+'".mysql_real_escape_string($_POST['igogo'])."' WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."';")){echo'Успешно';
					mysql_query("UPDATE `users` SET `money`=`money`-'".mysql_real_escape_string($_POST['igogo'])."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."'");
					$u->addDelo(1,$u->info['id'],'&quot;<font color=#C65F00>Clan'.$u->info['clan'].'.'.$u->info['city'].'</font>&quot;: Положено <b>'.mysql_real_escape_string($_POST['igogo']).'</b> кр. в казну клана',time(),$u->info['city'],'Clan'.$u->info['clan'].'.'.$u->info['city'].'',0,0);
				}else{echo'Что-то не так...';}
				$res['money1']+=$_POST['igogo'];
			}
		}
		if($_POST['igogo'] && $_POST['zabrat'] && ($u->info['clan_prava']=='glava' || $cpr[4]==1)) {
			$_POST['igogo'] = round($_POST['igogo'],2);
			if($_POST['igogo']<0){echo'не-а';}else{
				if($res['money1']<$_POST['igogo']){echo'не-а';}else{
					if(mysql_query("UPDATE `clan` SET `money1` = `money1`-'".mysql_real_escape_string($_POST['igogo'])."' WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."';")){echo'Успешно';
						mysql_query("UPDATE `users` SET `money`=`money`+'".mysql_real_escape_string($_POST['igogo'])."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."'");
						$u->addDelo(1,$u->info['id'],'&quot;<font color=#C65F00>Clan'.$u->info['clan'].'.'.$u->info['city'].'</font>&quot;: Взято <b>'.mysql_real_escape_string($_POST['igogo']).'</b> кр. из казны клана',time(),$u->info['city'],'Clan'.$u->info['clan'].'.'.$u->info['city'].'',0,0);
					}else{echo'Что-то не так...';}
					$res['money1']-=$_POST['igogo'];
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
<center><h3><img src="http://<?=$c['img']?>/i/align/align<?=$res['align'];?>.gif"> <img title="<?=$res['name'];?>" src="http://<?=$c['img']?>/i/clan/<?=$res['name_mini'];?>.gif"> <?=$res['name'];?></h3></center>
<input style="float:right;margin:1px" type="button" value="Вернуться" onClick="document.location='main.php'"><input style="float:right;margin:1px" type="button" value="Обновить" onClick="document.location='main.php?clan&rnd=<?=$code;?>'"><br>

<TABLE width="100%" cellpadding="5" cellspacing="10">
<TR><TD valign=top>
<small>Казна клана: <b><span style="color:green"><?=round($res['money1']);?></span> кр.</b></small>
<form method=post>
<input type=text value="0.00" style='width:40px;' name=igogo>
<?if($cpr[3]==1 || $u->info['clan_prava']=='glava'){?>
<input type=submit value="Положить" name=lojit>
<?}if($cpr[4]==1 || $u->info['clan_prava']=='glava'){?>
<input type=submit value="Забрать" name=zabrat>
<?}?>
</form>
<br><br>
<? if($u->info['clan_prava'] == 'glava' || $cpr[0]==1) { ?>
<input type="button" style="width:144px;" value="Принять в клан" onClick="openMod('<b>Введите логин</b>','<form action=\'main.php?clan=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'invite\' value=\'Принять\'></form>');"> 
(Это обойдется вам в <b>100 кр.</b>)<br>
<small>(Перед приемом в клан,персонаж должен пройти проверку у паладинов)</small><br>
<? } if($u->info['clan_prava'] == 'glava' || $cpr[1]==1) { ?>
<input type="button" style="width:144px;" value="Выгнать из клана" onClick="openMod('<b>Введите логин</b>','<form action=\'main.php?clan=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'dissmis\' value=\'Выгнать\'></form>');"> 
(Это обойдется вам в <b>30 кр.</b>)<br>
<? } if($u->info['clan_prava'] == 'glava' || $cpr[2]==1) { ?>
<!--<input type="button" style="width:144px;" value="Редактировать права" onClick="openMod('<b>Введите логин</b>','<form action=\'main.php?clan=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: &nbsp;<input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>Звание: <input type=\'text\' style=\'width:144px;\' id=\'rang\' name=\'rang\'><br> <input style=\'float:right;\' type=\'submit\' name=\'rerang\' value=\'Сменить звание\'></form>');"><br>-->
<input type="button" style="width:144px;" value="Редактировать" onClick="openMod('<b>Введите логин</b>','<form action=\'main.php?clan=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'persedit\' value=\'Редактировать\'></form>');"><br><br><br>
<? } ?>
<?
if($edit==1){

echo'
<form method=post>
Редактирование прав <b>'.htmlspecialchars($_POST['logingo'],NULL,'cp1251').'</b><br>
Звание в клане <input type=text value="',$prava['mod_zvanie'],'" name=status><BR>
<input type=checkbox name=priem';if ($edcpr[0]==1) { echo ' checked ';} echo'>Прием в клан<BR>
<input type=checkbox name=vigon';if ($edcpr[1]==1) { echo ' checked ';} echo'>Изгнание из клана<BR>
<input type=checkbox name=editpriv';if ($edcpr[2]==1) { echo ' checked ';} echo'>Редактирование прав<BR>
<input type=checkbox name=givekazna';if ($edcpr[3]==1) { echo ' checked ';} echo'>Пополнение казны клана<BR>
<input type=checkbox name=usekazna';if ($edcpr[4]==1) { echo ' checked ';} echo'>Использование казны клана<BR>

<input type=hidden value="'.htmlspecialchars($_POST['logingo'],NULL,'cp1251').'" name=login><input type=submit value="Сохранить" name=save>
</form>';
}
?>
</TD><TD valign=top align=right>

<TABLE cellspacing=0 cellpadding=2 width="300"><TR><TD>
<center>
<h4><a href="javascript:void(0)" onClick="top.chat.addto('klan','private')"><IMG border=0 SRC=http://<?=$c['img']?>/i/lock.gif WIDTH=20 HEIGHT=15 ALT="Приватно"></a> Соклановцы</H4>
		<table bgcolor=#eeeeee>
			<tr>
			<td align=left>
<?
$res1 = mysql_query("SELECT `login`,`id`,`align`,`level`,`mod_zvanie`,`online`,`clan_prava` FROM `users` WHERE `clan` = '".mysql_real_escape_string($u->info['clan'])."' ORDER BY `online` DESC");
while($data = mysql_fetch_array($res1)) {
				if ($data['online']>time()-120) {
					echo '<A href="javascript:void(0)" onClick="top.chat.addto(\''.$data['login'].'\',\'private\')"><img src="http://'.$c['img'].'/i/lock.gif" width=20 height=15></A>
					<img title="'.$res['name'].'" src="http://'.$c['img'].'/i/clan/'.$res['name_mini'].'.gif"><b>'.$data['login'].'</b> ['.$data['level'].']<a href="info/'.$data['id'].'" target="_blank"><img title="Инф. о '.$data['login'].'" src="http://'.$c['img'].'/i/inf_capitalcity.gif"></a>';
					if ($data['clan_prava'] == 'glava') {
						echo ' - <b>Глава клана</b>';
					} else {
							echo ' - ',$data['mod_zvanie'],'';
					}
					echo '<BR>';
					}
					elseif ($data['online']<time()-120) {
							echo '<img src="http://'.$c['img'].'/i/offline.gif" width=20 height=15>
							<img title="'.$res['name'].'" src="http://'.$c['img'].'/i/clan/'.$res['name_mini'].'.gif"><font color=grey><b>'.$data['login'].'</b> ['.$data['level'].']<a href="info/'.$data['id'].'" target="_blank"><img title="Инф. о '.$data['login'].'" src="http://'.$c['img'].'/inf_dis.gif"></a>';
							if ($data['clan_prava'] == 'glava') {
								echo ' - <b>Глава клана</b>';
							} else {
								echo ' - ',$data['mod_zvanie'],'</font>';
							}
							echo '<BR>';
						}
//echo '<img title="Свет" src="http://'.$c['img'].'/i/align/align'.$data['align'].'.gif"><img title="'.$res['name'].'" src="http://'.$c['img'].'/i/clan/'.$res['name_mini'].'.gif"><b>'.$data['login'].'</b> ['.$data['level'].']<a href="info/'.$data['id'].'" target="_blank"><img title="Инф. о '.$data['login'].'" src="http://'.$c['img'].'/i/inf_capitalcity.gif"></a> - '.$data['mod_zvanie'].'<br>';
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
<hr>
</body>