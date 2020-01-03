<?
if(!defined('GAME')) {
  die();
}

?>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
var nlevel = 0;
var from = Array('+', ' ', '#');
var to = Array('%2B', '+', '%23');

function w(login,id,align,klan,level,online,city,battle,blns){
	var s='';
	if(online != "") {
		if (city!="") {
			s+='<img src=http://img.xcombats.com/1x1.gif width="20" height="15" alt="В другом городе" />';
		} else {
			s+='<a href="javascript:top.chat.addto(\''+login+'\',\'private\');"><img src=http://img.xcombats.com/i/lock.gif width="20" height="15" alt="Приватно" /></a>';
		}
		if (city!="") {
			s+='<img title="'+city+'" src="http://img.xcombats.com/i/city_ico/'+city+'.gif" width="17" height="15" />';
		}
		s+=' <img src="http://img.xcombats.com/i/align/align'+align+'.gif" width="12" height="15" />';

		if (klan!='') {s+='<a href="/encicl/klan/'+klan+'.html" target="_blank"><img src="http://img.xcombats.com/i/clan/'+klan+'.gif" width="24" height="15" /></a>'}
		s+='<a href="javascript:top.chat.addto(\''+login+'\',\'to\');">'+login+'</a>['+level+']<a href="http://xcombats.com/info/'+id+'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" width="12" height="11" alt="Информация о персонаже" /></a>';
		if (city!="") {
			s+=" &mdash; нет в этом городе";
		} else {
			s+=' &mdash; '+online;
		}
		if( blns != '0' ) {
			s+= ' &mdash; Баланс: '+blns+' Екр.';
		}
	} else {
		s+='<img src="http://img.xcombats.com/i/offline.gif" width="20" height="15" border="0" alt="Нет в клубе" />';
		if (city!="") {
			s+='<img title="'+city+'" src="http://img.xcombats.com/i/city_ico/'+city+'.gif" width="17" height="15" />';
		}
		if (align == "") align="0";
		s+=' <img src="http://img.xcombats.com/i/align/align'+align+'.gif" width="12" height="15" />';
		if (klan!='') {s+='<a href="http://<? echo $c['host']; ?>/encicl/clan/'+klan+'.html" target="_blank"><img src="http://img.xcombats.com/i/klan/'+klan+'.gif" width="24" height="15" /></a>'}
		if (level) {
			if (nlevel==0) {
				nlevel=1; s="<br />"+s;
			}
			s+='<font color=gray><b>'+login+'</b>['+level+']<a href="http://xcombats.com/info/'+id+'" target="_blank"><img src="http://img.xcombats.com/i/inf.gif" width="12" height="11" alt="Информация о персонаже" /></a> &mdash; Нет в клубе';
		} else {
			if (nlevel==1) {
				nlevel=2; s="<br />"+s;
			}
			mlogin = login;
			for(var i=0;i<from.length;++i) while(mlogin.indexOf(from[i])>=0)  mlogin= mlogin.replace(from[i],to[i]);
			s+='<font color=gray><i>'+login+'</i> <a href="http://xcombats.com/info/'+mlogin+'" target="_blank"><img src="http://img.xcombats.com/i/inf_.gif" width="12" height="11" alt="Информация о персонаже" /></a> &mdash; нет в этом городе';
		}
		if( blns != '0' ) {
			s+= ' &mdash; Баланс: '+blns+' Екр.';
		}
		s+='</font>';

	}
	document.write(s+'<br />');
}
</script>
	<div id=hint4 class=ahint></div>
	<TABLE cellspacing=0 cellpadding=2 width=100%>
<TR>
<TD style="width: 40%; vertical-align: top; "><br />
<TABLE cellspacing=0 cellpadding=2 style="width: 100%; ">
  <TR>
<TD align=center><h4>Алхимики</h4></TD>
</TR>
<TR>
<TD bgcolor=efeded nowrap><SCRIPT>
<?
$data = mysql_query("SELECT `id`, `login`, `align`, `level`, `battle`, `online`, `city`, (select `name` from `room` WHERE `id` = users.`room`) as `room` FROM `users` WHERE `align` = '50' order by online DESC, login asc;");
$i = 0;
while($a = mysql_fetch_array($data))
{
	if ($a['online']>(time()-120))
	{
		$online = $a['room'];
		$id = $a['id'];
		$level = $a['level'];
		$battle = $a['battle'];
	}elseif($a['online']<(time()-120))
	{
		$online = '';
		$id = '';
		$level = '';
		$battle = '';
	}
	//w(       login,          id,       align,        klan,   level,     online,   city,    battle){
	$citya = $a['city']; 
	if($a['city']==$u->info['city'])
	{
		$citya = '';
	}
	$blns = mysql_fetch_array(mysql_query('SELECT `ekr` FROM `bank_alh` WHERE `uid` = "'.$a['id'].'" LIMIT 1'));
	if( $blns['ekr'] < 0 ) {
		$blns = 0;
	}else{
		$blns = $blns['ekr'];
	}
	echo 'w("'.$a['login'].'","'.$id.'","'.$a['align'].'","","'.$level.'","'.$online.'","'.$citya.'","'.$battle.'","'.$blns.'");';
	$i++;
}
$pl = mysql_fetch_array(mysql_query('SELECT * FROM `bank_table` ORDER BY `time` DESC LIMIT 1'));

?>
</SCRIPT>
<?
if( $i == 0 ) {
	echo '<center>Нет ни одного назначенного Алхимика</cetner>';
}
?>
<TR>
<TD style="text-align: left; "><hr><small>Курс покупки ЕвроКредитов: <b>1</b> eкр. = <b><?=round($pl['cur'],2)?> </b>руб.<br>
  Курс обмена ЕвроКредитов: <b>1</b> екр = <b><?=$c['ecrtocr']?></b> кр.<br>
  <?
	if($c['crtoecr']>0) {  
  ?>
  Курс обмена Кредитов: <?=$c['crtoecr']?> кр = 1 екр.<br>
  <?
	}
  ?>Продают еврокредиты и прочие платные услуги сервиса<BR>Вы можете отправить им личное сообщение, даже если Вы и Алхимик находитесь в разных городах</small></div></TD>
</TR>
</TABLE>
<hr>
<table>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>Курс еврокредита к мировой валюте</b> </legend>
          <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <?
			if(isset($pl['id'])) {
			?>
            <tr>
              <td><small>Данные на <b><?=date('d.m.y H:i',$pl['time'])?></b> без учета комиссий</small></td>
            </tr>
            <?
				$pl['RUB'] = 1;
				
				$i = 0;
				$true = array(
					array('USD', 'долларов США'),
					array('EUR', 'ЕВРО'),
					array('RUB','российских рублей'),
					array('UAH','укр. гривен'),
					array('AZN','азербайджанских манат'),
					array('GBP','англ. фунтов стерлингов')
				);
				while($i < count($true)) {
			?>
            <tr>
              <td><span>1 екр. = </span><span style="display:inline-block;width:100px"><b><?=round( ($pl['cur']/$pl[$true[$i][0]]) , 4 )?></b></span><span><?=$true[$i][1]?></span></td>
            </tr>
            <?
					$i++;
				}
			}else{
			?>
            <tr>
              <td><small><center><font color=grey>Не удалось получить информацию</font></center></small></td>
            </tr>
            <? } ?>
          </table>
        </fieldset></td>
  </tr>
</table>

</TD>
<TD style="vertical-align: top; ">&nbsp;</TD>
<TD width="165" style="vertical-align: top; text-align: right; "><INPUT class="btnnew" type='button' value='Обновить' onclick='location="/main.php?alh&rnd=<?=$code?>"';'>
&nbsp;<INPUT TYPE=button value="Вернуться" class="btnnew" onclick='location="/main.php"'></TD>
</TR>
</TABLE>
<DIV>
<? echo $c['counters']; ?>
</DIV>