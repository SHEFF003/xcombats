<?
if(!defined('GAME'))
{
	die();
}
?>
<style>
	.row {
		cursor:pointer;
	}
</style>
<SCRIPT>
var nlevel=0;
var from = Array('+', ' ', '#');
var to = Array('%2B', '+', '%23');

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
</SCRIPT>
</HEAD>
<body leftmargin=5 topmargin=5 marginwidth=0 marginheight=0 bgcolor=#e2e0e0><div id=hint4 class=ahint></div>
<table width=100%>
<tr>
	<td align=right>
		<INPUT TYPE="button" onClick="location.href='main.php';" value="Вернуться" title="Вернуться">
	</td>
</tr>
<tr>
	<td valign=top>
		<center>
			<h3><A HREF="javascript:void(0)" onClick="top.chat.addto('paladins','private')"><img src="http://img.xcombats.com/i/lock.gif" width=20 height=15></A> Орден Света</h3>		
		<table>
<?					$data=mysql_query("SELECT `id`,`login`,`level`,`align`,`room`,`online`,`city`, `battle`,
					(select `name` from `room` WHERE `id` = users.`room`) as `room`
					FROM `users` WHERE `align`>1 AND `align`<2 ORDER BY `align` DESC, `online` DESC;");
					while ($us = mysql_fetch_array($data)) {
if($u->info['city']==$us['city']){$us['city']='';}
if ($us['online']>(time()-120)) {
$rrm = $us['room'];
}else{
$rrm = '';
}
echo"<TR valign=\"top\">
	<TD bgcolor=efeded nowrap>
<SCRIPT>w('".$us['login']."','".$us['id']."','".$us['align']."','','".$us['level']."','".$rrm."','".$us['city']."','".$us['battle']."');</SCRIPT></TD></TR>";
}?>

			
			
		</table>
		</center>
	</td>

</tr>
</table>
</body>
</html>