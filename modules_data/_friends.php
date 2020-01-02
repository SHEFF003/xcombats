<?php
if(!defined('GAME'))
{
	die();
}

	if(isset($_GET['ignore'])) {
		$_POST['friendadd'] = $_GET['ignore'];
		$_POST['group'] = 3;
		$_POST['comment'] = 'Игнор из чата';
		$_POST['sd4'] = 1;
		$_POST['x'] = 4;
		$_POST['y'] = 6;
		$frd = mysql_fetch_array(mysql_query("SELECT id,login FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['friendadd'])."' ORDER BY `id` ASC LIMIT 1;"));
		$frd20 = mysql_fetch_array(mysql_query("SELECT id,enemy,friend,notinlist,ignor FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and (`friend`='".mysql_real_escape_string($frd['id'])."' or `enemy`='".mysql_real_escape_string($frd['id'])."' or `notinlist`='".mysql_real_escape_string($frd['id'])."' or `ignor`='".mysql_real_escape_string($frd['id'])."') LIMIT 1;"));
		if(isset($frd20['id'])) {
			mysql_query('DELETE FROM `friends` WHERE `id` = "'.mysql_real_escape_string($frd20['id']).'" LIMIT 1');
			if( $frd20['ignor'] == $frd['id'] ) {
				echo"<font color=red>Персонаж был успешно удален из списка игнорирования.</font>";
				echo '<script>top.iusrno["'.$_POST['friendadd'].'"] = 0;</script>';
				unset($_POST['friendadd'],$_POST['group'],$_POST['comment'],$_POST['sd4']);
			}
		}
	}

	$clr_fr = mysql_query('SELECT `id`,`friend`,`enemy`,`ignor` FROM `friends` WHERE `user` = "'.$u->info['id'].'"');
	while($clr_frd = mysql_fetch_array($clr_fr)) {
		if($clr_frd['friend'] > 0) {
			$usr_tst = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$clr_frd['friend'].'" LIMIT 1'));
		}elseif($clr_frd['enemy'] > 0) {
			$usr_tst = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$clr_frd['enemy'].'" LIMIT 1'));
		}elseif($clr_frd['ignor'] > 0) {
			$usr_tst = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$clr_frd['ignor'].'" LIMIT 1'));
		}
		if(!isset($usr_tst['id']) || $usr_tst['login'] == 'delete') {
			mysql_query('DELETE FROM `friends` WHERE `id` = "'.$clr_frd['id'].'" LIMIT 1');
		}
	}

$friend = mysql_fetch_array(mysql_query("SELECT * FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"));

$st = $u->lookStats($u->info['stats']);
$addfr = mysql_fetch_array(mysql_query("SELECT count(`id`) FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."';"));
$addf = 20;
if($st['os5']>0) {
	$addf = 20+($st['os5']*5);
}
if($addfr[0]<$addf){
	$canadd = 'onclick=\'findlogin2("Добавить в список", "main.php?friends", "friendadd", new Array("Друзья","Враги","Не в группе","Игнорирование"), new Array())\'';
}else{$canadd = 'disabled';}
if($_POST['sd4'] && $_POST['friendadd']){
	$_POST['friendadd']=htmlspecialchars($_POST['friendadd'],NULL,'cp1251');
	if(preg_match("/__/",$_POST['friendadd']) || preg_match("/--/",$_POST['friendadd'])){
		echo"<font color=red>Персонаж не найден.</font>";
	}else{
		$frd = mysql_fetch_array(mysql_query("SELECT `id`,`login` FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['friendadd'])."' LIMIT 1;"));
	}
	$_POST['comment']=htmlspecialchars($_POST['comment'],NULL,'cp1251');
	$frd2 = mysql_fetch_array(mysql_query("SELECT enemy,friend,notinlist,ignor FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and (`friend`='".mysql_real_escape_string($frd['id'])."' or `enemy`='".mysql_real_escape_string($frd['id'])."' or `notinlist`='".mysql_real_escape_string($frd['id'])."' or `ignor`='".mysql_real_escape_string($frd['id'])."') LIMIT 1;"));
	if(!$frd['id']){echo"<font color=red>Персонаж не найден.</font>";}
	elseif($frd['id']==$u->info['id']){echo"<font color=red>Себя добавить нельзя.</font>";}
	elseif(preg_match("/__/",$_POST['comment']) || preg_match("/--/",$_POST['comment'])){echo"<font color=red>Введен неверный текст.</font>";}
	elseif($frd2['enemy'] or $frd2['friend'] or $frd2['notinlist'] or $frd2['ignor']){
		echo"<font color=red>Персонаж уже есть в вашем списке.</font>";
	}
	else{
		$lign = '';
		$uign = '';
		if($_POST['group']==0){$notinlist=0; $friend=$frd['id']; $enemy=0; $ignor = 0;}
		elseif($_POST['group']==1){$notinlist=0; $friend=0; $enemy=$frd['id']; $ignor = 0;}
		elseif($_POST['group']==3){$notinlist=0; $friend=0; $enemy=0; $ignor = $frd['id']; $lign = $frd['login']; $uign = $u->info['login']; }
		else{$notinlist=$frd['id']; $friend=0; $enemy=0; $ignor = 0;}
		mysql_query("INSERT INTO `friends` (`user`, `friend`, `enemy`, `notinlist`, `comment`,`ignor`,`login_ignor`,`user_ignor`) VALUES(".mysql_real_escape_string($u->info['id']).", ".mysql_real_escape_string($friend).", ".mysql_real_escape_string($enemy).", ".mysql_real_escape_string($notinlist).", '".mysql_real_escape_string($_POST['comment'])."', '".mysql_real_escape_string($ignor)."','".$lign."','".$uign."');");
		echo"<font color=red>Персонаж <b>".$_POST['friendadd']."</b> добавлен.</font>";
		if( $ignor > 0 ) {
			echo '<script>top.iusrno["'.$_POST['friendadd'].'"] = 1;</script>';
		}
	}
}

if($_GET['friendremove']){
	$_GET['friendremove']=htmlspecialchars($_GET['friendremove'],NULL,'cp1251');
	if(preg_match("/__/",$_GET['friendremove']) || preg_match("/--/",$_GET['friendremove'])){
		echo"<font color=red>Персонаж не найден.</font>";
	}else{
		$frd = mysql_fetch_array(mysql_query("SELECT id FROM `users` WHERE `login` = '".mysql_real_escape_string($_GET['friendremove'])."' LIMIT 1;"));
	}
	if(!$frd['id']){echo"<font color=red>Персонаж не найден.</font>";}
	else{
		$frd2 = mysql_fetch_array(mysql_query("SELECT ignor,enemy,friend,notinlist FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and (`friend`='".mysql_real_escape_string($frd['id'])."' or `enemy`='".mysql_real_escape_string($frd['id'])."' or `notinlist`='".mysql_real_escape_string($frd['id'])."' or `ignor`='".mysql_real_escape_string($frd['id'])."') LIMIT 1;"));
		if(!$frd2['enemy'] && !$frd2['friend'] && !$frd2['ignor'] && !$frd2['notinlist']){echo"<font color=red>Персонаж не найден в вашем списке.</font>";
		}else{
			if($frd2['friend']>0){$per="`friend`='".$frd2['friend']."'";}
			if($frd2['enemy']>0){$per="`enemy`='".$frd2['enemy']."'";}
			if($frd2['notinlist']>0){$per="`notinlist`='".$frd2['notinlist']."'";}
			if($frd2['ignor']>0){$per="`ignor`='".$frd2['ignor']."'";}
			if(mysql_query("DELETE FROM `friends` WHERE `user`='".mysql_real_escape_string($u->info['id'])."' and ".$per.";")){echo"<font color=red>Данные контакта <b>".$_GET['friendremove']."</b> успешно удалены.</font>";echo '<script>top.iusrno["'.$frd['login'].'"] = 0;</script>';}
		}
	}
}



if($_POST['friendedit']){
	$_POST['friendedit']=htmlspecialchars($_POST['friendedit'],NULL,'cp1251');
	if(preg_match("/__/",$_POST['friendedit']) || preg_match("/--/",$_POST['friendedit'])){
		echo"<font color=red>Персонаж не найден.</font>";
	}else{
		$frd = mysql_fetch_array(mysql_query("SELECT id FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['friendedit'])."' LIMIT 1;"));
	}
	$_POST['comment']=htmlspecialchars($_POST['comment'],NULL,'cp1251');
	if(!$frd['id']){echo"<font color=red>Персонаж не найден.</font>";}
	elseif($frd['id']==$u->info['id']){echo"<font color=red>Себя отредактировать нельзя.</font>";}
	elseif(preg_match("/__/",$_POST['comment']) || preg_match("/--/",$_POST['comment'])){echo"<font color=red>Введен неверный текст.</font>";}
	else{
		if($_POST['group']==0){$notinlist=0; $friend=$frd['id']; $enemy=0; $ignor = 0;}
		elseif($_POST['group']==1){$notinlist=0; $friend=0; $enemy=$frd['id']; $ignor = 0;}
		elseif($_POST['group']==3){$notinlist=0; $friend=0; $enemy=0; $ignor = $frd['id'];}
		else{$notinlist=$frd['id']; $friend=0; $enemy=0; $ignor = 0;}
		$frd2 = mysql_fetch_array(mysql_query("SELECT ignor,enemy,friend,notinlist FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and (`friend`='".mysql_real_escape_string($frd['id'])."' or `enemy`='".mysql_real_escape_string($frd['id'])."' or `notinlist`='".mysql_real_escape_string($frd['id'])."' or `ignor`='".mysql_real_escape_string($frd['id'])."') LIMIT 1;"));
		if(!$frd2['enemy'] && !$frd2['friend'] && !$frd2['ignor'] && !$frd2['notinlist']){echo"<font color=red>Персонаж не найден в вашем списке.</font>";}
		else{
			if($frd2['friend']>0){$per="`friend`='".mysql_real_escape_string($frd2['friend'])."'";}
			if($frd2['enemy']>0){$per="`enemy`='".mysql_real_escape_string($frd2['enemy'])."'";}
			if($frd2['notinlist']>0){$per="`notinlist`='".mysql_real_escape_string($frd2['notinlist'])."'";}
			if($frd2['ignor']>0){$per="`ignor`='".$frd2['ignor']."'";}
			$comment = $_POST['comment'];
			mysql_query("UPDATE `friends` SET `friend` = '".mysql_real_escape_string($friend)."',`enemy` = '".mysql_real_escape_string($enemy)."',`notinlist` = '".mysql_real_escape_string($notinlist)."',`comment` = '".mysql_real_escape_string($comment)."',`ignor` = '".mysql_real_escape_string($ignor)."'  WHERE `user`='".mysql_real_escape_string($u->info['id'])."' and $per");
			echo"<font color=red>Данные контакта <b>".$_POST['friendedit']."</b> успешно изменены.</font>";
			if( $ignor > 0 ) {
				echo '<script>top.iusrno["'.$frd['login'].'"] = 1;</script>';
			}else{
				echo '<script>top.iusrno["'.$frd['login'].'"] = 0;</script>';
			}
		}
	}
}
?>
<SCRIPT src='http://img.xcombats.com/js/sl2.21.js'></SCRIPT>
<SCRIPT src='http://img.xcombats.com/commoninf.js'></SCRIPT>
<SCRIPT>
var nlevel=0;
var from = Array('+', ' ', '#');
var to = Array('%2B', '+', '%23');

function editcontact(title, script, name, login, flogin, group, groups, subgroup, subgroups, comment)
{   var s = '<table width=250 cellspacing=1 cellpadding=0 bgcolor=CCC3AA><tr><td align=center><B>'+title+'</td><td width=20 align=right valign=top style="cursor: pointer" onclick="closehint3();"><BIG><B>x</td></tr><tr><td colspan=2>';
    s +='<form action="'+script+'" method=POST><table width=250 cellspacing=0 cellpadding=4 bgcolor=FFF6DD><tr><td align=center>';
    s +='<table width=1% border=0 cellspacing=0 cellpadding=2 align=center><tr><td align=right>';
    flogin = flogin.replace( /^<SCRIPT>drwfl\((.*)\)<\/SCRIPT>$/i, 'drw($1)' );
    s +='<small><b>Контакт:</b></small></td><td><INPUT TYPE=hidden NAME="'+name+'" VALUE="'+login+'">'+( flogin.match(/^drw\(/) ? eval(flogin) : flogin )+'</td></tr>';
    if (groups && groups.length>0) {
        s+='<tr><td align=right><small><b>Группа:</b></small></td><td align><SELECT NAME=group style="width: 140px">';
        for(i=0; i< groups.length; i++) {
            s+='<option value="'+i+'"'+( group == i ? ' selected' : '' ) +'>'+groups[i];
        }
        s+='</SELECT></td></tr>';
    };

    s += '<tr><td align=right><small><b>Комментарий:</b></small></td><td width="1%"><INPUT TYPE=text NAME="comment" VALUE="'+comment+'" style="width: 105px">&nbsp;';
    s += '<INPUT type=image SRC=http://img.xcombats.com/i/b__ok.gif WIDTH=25 HEIGHT=18 ALT="Сохранить" style="border:0; vertical-align: middle"></TD></TR></TABLE><INPUT TYPE=hidden name=sd4 value=""></TD></TR></TABLE></form></td></tr></table>';
document.all("hint4").innerHTML = s;
document.all("hint4").style.visibility = "visible";
document.all("hint4").style.left = 100;
document.all("hint4").style.top = document.body.scrollTop+50;
document.all("comment").focus();
Hint3Name = '';
}
function findlogin2(title, script, name, groups, subgroups)
{   var s = '<table width=270 cellspacing=1 cellpadding=0 bgcolor=CCC3AA><tr><td align=center><B>'+title+'</td><td width=20 align=right valign=top style="cursor: pointer" onclick="closehint3();"><BIG><B>x</td></tr><tr><td colspan=2>';
s +='<form action="'+script+'" method=POST><table width=100% cellspacing=0 cellpadding=2 bgcolor=FFF6DD><tr><td align=center>';
s +='<table width=90% cellspacing=0 cellpadding=2 align=center><tr><td align=left colspan="2">';
s +='Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></td></tr>';
s += '<tr><td align=right><small><b>Логин:</b></small></td><td><INPUT TYPE=text name="'+name+'" id="'+name+'" style="width:140px"></td></tr>';
if (groups && groups.length>0) {
s+='<tr><td align=right><small><b>Группа:</b></small></td><td width=140><SELECT NAME=group style="width:140px">';
for(i=0; i< groups.length; i++) {
s+='<option value="'+i+'">'+groups[i];
}
s+='</SELECT></td></tr>';
};

s += '<tr><td align=right><small><b>Комментарий:</b></small></td><td><INPUT TYPE=text NAME="comment" VALUE="" style="width:105px">&nbsp;';
s += '<INPUT type=image SRC=http://img.xcombats.com/i/b__ok.gif WIDTH=25 HEIGHT=18 ALT="Добавить контакт" style="border:0; vertical-align: middle"></TD></TR></TABLE><INPUT TYPE=hidden name=sd4 value="1"></TD></TR></TABLE></form></td></tr></table>';
document.getElementById("hint4").innerHTML = s;
document.getElementById("hint4").style.visibility = "visible";
document.getElementById("hint4").style.left = 100;
document.getElementById("hint4").style.top = document.body.scrollTop+50;
Hint3Name = name;
document.getElementById(name).focus();
}
function w(login,id,align,klan,level,online, city, battle){
var s='';
if (online!="") {
if (city!="") {
s+='<IMG style="filter:gray()" SRC=http://img.xcombats.com/i/lock.gif WIDTH=20 HEIGHT=15 ALT="В другом городе">';
} else {
s+='<a href="javascript:void(0)" onclick="top.chat.addto(\''+login+'\',\'private\')"><IMG SRC=http://img.xcombats.com/i/lock.gif WIDTH=20 HEIGHT=15 ALT="Приватно"'+(battle!=0?' style="filter: invert()"':'')+'></a>';
}
if (city!="") {
s+='<img src="http://forum.img.xcombats.com/city/'+city+'.gif" width=17 height=15>';
}
s+=' <IMG SRC=http://img.xcombats.com/i/align/align'+align+'.gif WIDTH=12 HEIGHT=15>';
if (klan!='') {s+='<A HREF="/encicl/klan/'+klan+'.html" target=_blank><IMG SRC="http://img.xcombats.com/i/clan/'+klan+'.gif" WIDTH=24 HEIGHT=15 ALT=""></A>'}
s+='<a href="javascript:void(0)" onclick="top.chat.addto(\''+login+'\',\'to\')">'+login+'</a>['+level+']<a href=/info/'+id+' target=_blank><IMG SRC=http://img.xcombats.com/i/inf.gif WIDTH=12 HEIGHT=11 ALT="Информация о персонаже"></a>';
s+='</td><td bgcolor=efeded nowrap>';
if (city!="") {
s+="нет в этом городе";
} else {
s+=online;
}
}
else {
s+='<IMG SRC="http://img.xcombats.com/i/offline.gif" WIDTH=20 HEIGHT=15 BORDER=0 ALT="Нет в клубе">';
if (city!="") {
s+='<img src="http://forum.img.xcombats.com/city/'+city+'.gif" width=17 height=15>';
}
if (align == "") align="0";
s+=' <IMG SRC=http://img.xcombats.com/i/align/align'+align+'.gif WIDTH=12 HEIGHT=15>';
if (klan!='') {s+='<A HREF="/encicl/klan/'+klan+'.html" target=_blank><IMG SRC="http://img.xcombats.com/i/clan/'+klan+'.gif" WIDTH=24 HEIGHT=15 ALT=""></A>'}
if (level) {
if (nlevel==0) {
nlevel=1; //s="<BR>"+s;
}
s+='<FONT color=gray><b>'+login+'</b>['+level+']<a href=/info/'+id+' target=_blank><IMG SRC=http://img.xcombats.com/i/inf.gif WIDTH=12 HEIGHT=11 ALT="Информация о персонаже"></a></td><td bgcolor=efeded nowrap>Нет в клубе';
} else {
if (nlevel==1) {
nlevel=2; //s="<BR>"+s;
}
mlogin = login;
for(var i=0;i<from.length;++i) while(mlogin.indexOf(from[i])>=0)  mlogin= mlogin.replace(from[i],to[i]);
s+='<FONT color=gray><i>'+login+'</i> <a href=/info/'+mlogin+' target=_blank><IMG SRC=http://img.xcombats.com/i/inf_dis.gif WIDTH=12 HEIGHT=11 ALT="Информация о персонаже"></a></td><td bgcolor=efeded nowrap>нет в этом городе';
}
s+='</FONT>';
}
document.write(s+'<BR>');
}
function m(login,id,align,klan,level){
var s='';
s+='<a href="javascript:void(0)" onclick="top.chat.addto(\''+login+'\',\'private\')"><IMG SRC=http://img.xcombats.com/i/lock.gif WIDTH=20 HEIGHT=15 ALT="Приватно"></a>';
s+=' <IMG SRC=http://img.xcombats.com/i/align/align'+align+'.gif WIDTH=12 HEIGHT=15>';
if (klan!='') {
s+='<A HREF="/encicl/klan/'+klan+'.html" target=_blank><IMG SRC="http://img.xcombats.com/i/clan/'+klan+'.gif" WIDTH=24 HEIGHT=15 ALT=""></A>'
}
s+='<a href="javascript:void(0)" onclick="top.chat.addto(\''+login+'\',\'to\')">'+login+'</a>['+level+']<a href=/info/'+id+' target=_blank><IMG SRC=http://img.xcombats.com/i/inf.gif WIDTH=12 HEIGHT=11 ALT="Информация о персонаже"></a>';
document.write(s+'<BR>');
}
function drw(name, id, level, align, klan, img, sex)
{
var s="";
if (align!="0") s+="<A HREF='"+getalignurl(align)+"' target=_blank><IMG SRC='http://img.xcombats.com/i/align/align"+align+".gif' WIDTH=12 HEIGHT=15 ALT=\""+getalign(align)+"\"></A>";
if (klan) s+="<A HREF='claninfo/"+klan+"' target=_blank><IMG SRC='http://img.xcombats.com/i/clan/"+klan+".gif' WIDTH=24 HEIGHT=15 ALT=''></A>";
s+="<B>"+name+"</B>";
if (level!=-1) s+=" ["+level+"]";
if (id!=-1 && !img) s+="<A HREF='/info/"+id+"' target='_blank'><IMG SRC=http://img.xcombats.com/i/inf.gif WIDTH=12 HEIGHT=11 ALT='Инф. о "+name+"'></A>";
if (img) s+="<A HREF='http://capitalcity.combats.com/encicl/obraz_"+(sex?"w":"m")+"1.html?l="+img+"' target='_blank'><IMG SRC=http://img.xcombats.com/i/inf.gif WIDTH=12 HEIGHT=11 ALT='Образ "+name+"'></A>";
return s;
}
</SCRIPT>
</HEAD>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 bgcolor=e2e0e0>
<div id=hint4 class=ahint></div>
<TABLE cellspacing=0 cellpadding=2 width="100%">
<TR>
<TD style="vertical-align: top; "><TABLE cellspacing=0 cellpadding=2 width="100%">
<TR>
<TD colspan="4" align="center"><h4>Контакты</h4></TD>
</TR>
<?
					$data=mysql_query("SELECT `notinlist`,`comment` FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and `notinlist`>0;");
					while ($row = mysql_fetch_array($data)) {
					$us=mysql_fetch_array(mysql_query("SELECT `id`,`login`,`clan`,`level`,`align`,`room`,`online`,`city`, `battle`,
					(select `name_mini` from `clan` WHERE `id` = users.`clan`) as `klan`, 
					(select `name` from `room` WHERE `id` = users.`room`) as `room`
					FROM `users` WHERE `id` = '".mysql_real_escape_string($row['notinlist'])."';"));
//function w(login,id,align,klan,level,online, city, battle)
if ($us['online']>(time()-120)) {
$rrm = $us['room'];
}else{
$rrm = '';
}
if($u->info['city']==$us['city']){$us['city']='';}
?>
<TR valign="top">
<TD bgcolor=efeded nowrap><SCRIPT>w('<?=$us['login']?>','<?=$us['id']?>','<?=$us['align']?>','<?=$us['klan']?>','<?=$us['level']?>','<?=$rrm?>','<?=$us['city']?>','<?=$us['battle']?>');</SCRIPT></TD>
<TD bgcolor=efeded width="40%"><small><FONT class=dsc><i><?=$row['comment']?></i></FONT></small><TD>
<TD width="1%"><INPUT type=image SRC=http://img.xcombats.com/i/b__ok.gif WIDTH=25 HEIGHT=18 ALT="Редактировать" style="float: right" onclick='editcontact("Редактирование контакта", "main.php?friends", "friendedit", "<?=$us['login']?>", "<SCRIPT>drwfl(\"<?=$us['login']?>\",<?=$us['id']?>,\"<?=$us['level']?>\",<?=$us['align']?>,\"<?=$us['klan']?>\")</SCRIPT>", "2", new Array( "Друзья","Враги","Не в группе","Игнорирование" ), "", new Array(  ), "");'></TD>
</TR>
<?
}
					$data=mysql_query("SELECT `enemy`,`comment` FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and `enemy`>0;");
					while ($row = mysql_fetch_array($data)) {
					$us=mysql_fetch_array(mysql_query("SELECT `id`,`login`,`clan`,`level`,`align`,`room`,`online`,`city`,
					(select `name_mini` from `clan` WHERE `id` = users.`clan`) as `klan`,
					(select `name` from `room` WHERE `id` = users.`room`) as `room`
					FROM `users` WHERE `id` = '".mysql_real_escape_string($row['enemy'])."';"));
if($u->info['city']==$us['city']){$us['city']='';}
	$n++;
if($n==1){

?>
<TR>
<TD colspan="4" nowrap align="center" style="height: 40px" valign="bottom"><h4>Враги</h4></TD>
</TR>
<? } ?>
<TR valign="top">
<?
//function w(login,id,align,klan,level,online, city, battle)
if ($us['online']>(time()-120)) {
$rrm = $us['room'];
}else{
$rrm = '';
}
?>
<TD bgcolor=efeded nowrap><SCRIPT>w('<?=$us['login']?>','<?=$us['id']?>','<?=$us['align']?>','<?=$us['klan']?>','<?=$us['level']?>','<?=$rrm?>','<?=$us['city']?>','<?=$us['battle']?>');</SCRIPT></TD>
<TD bgcolor=efeded width="40%"><small><FONT class=dsc><i><?=$row['comment']?></i></FONT></small><TD>
<TD width="1%"><INPUT type=image SRC=http://img.xcombats.com/i/b__ok.gif WIDTH=25 HEIGHT=18 ALT="Редактировать" style="float: right" onclick='editcontact("Редактирование контакта", "main.php?friends", "friendedit", "<?=$us['login']?>", "<SCRIPT>drwfl(\"<?=$us['login']?>\",<?=$us['id']?>,\"<?=$us['level']?>\",<?=$us['align']?>,\"<?=$us['klan']?>\")</SCRIPT>", "1", new Array( "Друзья","Враги","Не в группе","Игнорирование" ), "", new Array(  ), "");'></TD>
</TR>
<?
}

					$data=mysql_query("SELECT `friend`,`comment` FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and `friend`>0;");
					while ($row = mysql_fetch_array($data)) {
					$us=mysql_fetch_array(mysql_query("SELECT `id`,`login`,`clan`,`level`,`align`,`room`,`online`,`city`, 
					(select `name_mini` from `clan` WHERE `id` = users.`clan`) as `klan`,
					(select `name` from `room` WHERE `id` = users.`room`) as `room`
					FROM `users` WHERE `id` = '".mysql_real_escape_string($row['friend'])."' ORDER BY online DESC, login ASC;"));
if($u->info['city']==$us['city']){$us['city']='';}
	$i++;
if($i==1){
?>
<TR>
<TD colspan="4" nowrap align="center" style="height: 40px" valign="bottom"><h4>Друзья</h4></TD>
</TR>
<?}?>
<TR valign="top">
<?
//function w(login,id,align,klan,level,online, city, battle)
if ($us['online']>(time()-120)) {
$rrm = $us['room'];
}else{
$rrm = '';
}
?>
<TD bgcolor=efeded nowrap><SCRIPT>w('<?=$us['login']?>','<?=$us['id']?>','<?=$us['align']?>','<?=$us['klan']?>','<?=$us['level']?>','<?=$rrm?>','<?=$us['city']?>','<?=$us['battle']?>');</SCRIPT></TD>
<TD bgcolor=efeded width="40%"><small><FONT class=dsc><i><?=$row['comment']?></i></FONT></small><TD>
<TD width="1%"><INPUT type=image SRC=http://img.xcombats.com/i/b__ok.gif WIDTH=25 HEIGHT=18 ALT="Редактировать" style="float: right" onclick='editcontact("Редактирование контакта", "main.php?friends", "friendedit", "<?=$us['login']?>", "<SCRIPT>drwfl(\"<?=$us['login']?>\",<?=$us['id']?>,\"<?=$us['level']?>\",<?=$us['align']?>,\"<?=$us['klan']?>\")</SCRIPT>", "0", new Array( "Друзья","Враги","Не в группе","Игнорирование" ), "", new Array(  ), "");'></TD>
</TR>
<?
}	$i = 0;

					$data=mysql_query("SELECT `ignor`,`comment` FROM `friends` WHERE `user` = '".mysql_real_escape_string($u->info['id'])."' and `ignor`>0;");
					while ($row = mysql_fetch_array($data)) {
					$us=mysql_fetch_array(mysql_query("SELECT `id`,`login`,`clan`,`level`,`align`,`room`,`online`,`city`, 
					(select `name_mini` from `clan` WHERE `id` = users.`clan`) as `klan`,
					(select `name` from `room` WHERE `id` = users.`room`) as `room`
					FROM `users` WHERE `id` = '".mysql_real_escape_string($row['ignor'])."' ORDER BY online DESC, login ASC;"));
if($u->info['city']==$us['city']){$us['city']='';}
	$i++;
if($i==1){
?>
<TR>
<TD colspan="4" nowrap align="center" style="height: 40px" valign="bottom"><h4>Игнорирование</h4></TD>
</TR>
<? } ?>
<TR valign="top">
<?
//function w(login,id,align,klan,level,online, city, battle)
if ($us['online']>(time()-120)) {
$rrm = $us['room'];
}else{
$rrm = '';
}
?>
<TD bgcolor=efeded nowrap><SCRIPT>w('<?=$us['login']?>','<?=$us['id']?>','<?=$us['align']?>','<?=$us['klan']?>','<?=$us['level']?>','<?=$rrm?>','<?=$us['city']?>','<?=$us['battle']?>');</SCRIPT></TD>
<TD bgcolor=efeded width="40%"><small><FONT class=dsc><i><?=$row['comment']?></i></FONT></small><TD>
<TD width="1%"><INPUT type=image SRC=http://img.xcombats.com/i/b__ok.gif WIDTH=25 HEIGHT=18 ALT="Редактировать" style="float: right" onclick='editcontact("Редактирование контакта", "main.php?friends", "friendedit", "<?=$us['login']?>", "<SCRIPT>drwfl(\"<?=$us['login']?>\",<?=$us['id']?>,\"<?=$us['level']?>\",<?=$us['align']?>,\"<?=$us['klan']?>\")</SCRIPT>", "0", new Array( "Друзья","Враги","Не в группе","Игнорирование" ), "", new Array(  ), "");'></TD>
</TR>
<?
}
?>
<TR>
<TD colspan="4"><INPUT type='button' style='width: 100px' value='Добавить' <?=$canadd?>>
&nbsp;&nbsp;&nbsp;
<INPUT type='button' style='width: 100px' value='Удалить' onclick='findlogin("Удалить из списка", "main.php?friends", "friendremove", "", 0)'></TD>
</TR>
</TABLE></TD>
<TD style="width: 5%; vertical-align: top; ">&nbsp;</TD>
<TD style="width: 30%; vertical-align: top; "><TABLE cellspacing=0 cellpadding=2>
<TR>
<TD style="width: 25%; vertical-align: top; text-align: right; "><INPUT type='button' value='Обновить' style='width: 75px' onclick='location="main.php?friends=1"'>
&nbsp;<INPUT TYPE=button value="Вернуться" style='width: 75px' onClick="location='main.php';"></TD>
</TR>
<?
$imen = '';

if( $imen != '' ) { ?>
<TR>
<TD align=center><h4>Именинник</h4></TD>
</TR>
<TR>
<TD align=center><?=$imen?></TD>
</TR>
<? } unset($imen); ?>

<TR>
<TD align=center><h4>Модераторы on-line</h4></TD>
</TR>
<TR>
<TD bgcolor=efeded nowrap style="text-align: left; "><table align="left">
  <tr><td>
<SCRIPT>
<?
$data=mysql_query("SELECT `id`, `login`, `level`, `align`, `clan` FROM `users` WHERE (`inUser` > 0 OR `online` > '".(time()-120)."') AND ((align>1 and align<2 and align!=1.2) or (align>3 and align<4)) AND `city` = '".mysql_real_escape_string($u->info['city'])."' order by align asc;");	
		while ($row = mysql_fetch_array($data)) {

//m(      login,            id,                align,       klan,         level)
echo"m('".$row['login']."','".$row['id']."','".$row['align']."','".$row['klan']."','".$row['level']."');";
}
?>
</SCRIPT>
<?
$chk=mysql_fetch_array(mysql_query("SELECT `id` FROM `users` WHERE `online` > '".(time()-120)."' AND ((align>1 and align<2 and align!=1.2) or (align>3 and align<4)) AND `city` = '".mysql_real_escape_string($u->info['city'])."' order by align asc;"));	
if(!$chk['id']) {echo'<font color=grey>К сожалению в данный момент никого из модераторов нет в городе.</font>';}?>
</TD></tr></table></TD>
</TR>
<TR>
<TD style="text-align: left; "><small>Уважаемые Игроки!<BR>Для более быстрого и эффективного решения Вашей проблемы просьба обращаться к тем паладинам или тарманам, ники которых находятся вверху списка «Модераторы on-line».<BR>Цените свое и чужое время!</small></div></TD>
</TR>
</TABLE></TD>
</TR>
</TABLE>
<DIV><?=$c['counters_noFrm'];?></DIV>
</HTML>
