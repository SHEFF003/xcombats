<?php
if(!defined('GAME')) { die(); }
//onmouseup="top.chat.inObj = trnLogin;"
if(!isset($u->tfer['id'])) {
?>
<script type="text/javascript" src="js/jquery.js"></script>
<form method="post" action="main.php?transfer&rnd=<? echo $code; ?>" onmouseup="$( document ).ready(function() { top.chat.inObj = top.frames['main'].document.getElementById('trnLogin'); $('#trnLogin').focus(); });">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="110" align="right">&nbsp;</td>
    <td align="center">Передача предметов/кредитов другому игроку</td>
    <td width="110" align="right"><input type="button" onClick="location='main.php?rnd=<? echo $code; ?>';" name="button" id="button" value="Вернуться"></td>
  </tr>
</table>
<?
if($u->error!='') {
	echo '<b><font color="red">'.$u->error.'</font></b>';
}
?>
<p>&nbsp;</p>
<div class="unos" id="unos">
<table border="0" align="center" cellpadding="0" style="border:1px solid #a5a5a5" cellspacing="0">
  <tr>
    <td id="LoginLayer" valign="top" colspan="2"><table width="300" border="0" align="center" cellpadding="2" cellspacing="0" bgcolor="#d4d2d2">
      <tbody>
        <tr>
          <td bgcolor="#a5a5a5" align="center"><strong>Укажите логин персонажа:</strong></td>
        </tr>
        <tr>
          <td align="center"><input id="trnLogin" type="text" style="width: 95%;" name="trnLogin" value="" />
            <div align="center"><small style="font-size:10px;">(можно щелкнуть по логину в чате)</small></div>
          </td>
        </tr>
        <tr>
          <td bgcolor="#a5a5a5" align="center"><strong>Пояснение</strong></td>
        </tr>
        <tr>
          <td align="center"><textarea style="width:95%;" name="textarea"></textarea>
            <div align="center"><small style="font-size:10px;">Максимальное число знаков: 200</small></div>
          </td>
        </tr>
        <tr>
          <td bgcolor="#a5a5a5" align="center"><button>Отправить приглашение</button></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
</table>
</div>
</form>
<?
}elseif($u->tfer['cancel1']==0 && $u->tfer['cancel2']==0)
{
	$rtdf = 1;
	if($u->tfer['uid2']==$u->info['id'])
	{
		$rtdf = 2;
	}
	if($u->tfer['r'.$rtdf]!=0 && $u->tfer['r'.$rtdf]==$u->tfer['r0'])
	{
		$u->tfer['r'.$rtdf] = 0;
		$u->tfer['good1'] = 0;
		$u->tfer['good2'] = 0;
		mysql_query('UPDATE `transfers` SET `r'.$rtdf.'` = "0", `good1`="0",`good2`="0" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
		unset($rtdf);
	}
/*	echo '[Передача]<br>Присутствие: ';
	if($u->tfer['start1']>0)
	{
		echo ' [U1: Присутствует]';		
	}
	if($u->tfer['start2']>0)
	{
		echo ' [U2: Присутствует]';		
	}
	echo '<br>Состояние: ';
	if($u->tfer['good1']>0)
	{
		echo ' [U1: Согласен]';		
	}else{
		echo ' [U1: Ожидание]';
	}
	if($u->tfer['good2']>0)
	{
		echo ' [U2: Согласен]';		
	}else{
		echo ' [U2: Ожидание]';
	}
	
	echo '<br><a href="main.php?transfer&exit_transfer='.$code.'">Выйти из передачи</a>';
*/
$az = array(1=>1,2=>2);
if($u->tfer['uid2']==$u->info['id'])
{
	$az = array(1=>2,2=>1);
}
$tu = array(
	1 => $u->microLogin($u->tfer['uid'.$az[1]],1),
	2 => $u->microLogin($u->tfer['uid'.$az[2]],1)
);
?>
<style>
.tfitm1 {
	background-color:#c7c7c7;
	border-bottom:1px solid #909090;
	padding:3px;
}
.tfitm2 {
	background-color:#d5d5d5;
	border-bottom:1px solid #909090;
	padding:3px;
}
.tfii {
	margin:3px;
	max-width:30px
}
.tfid {
	border-left:1px solid #FAFAFA;
}
.clr {
	float:right;
	cursor:pointer;
}
</style>
<script src="http://img.xcombats.com/js/jx/jquery.js" type="text/javascript"></script>
<script>
function gorazdel(id)
{
	if($('#invmn'+id).attr('id')=='invmn'+id)
	{
		var i = 1;
		while(i<5)
		{
			$('#invmn'+i).css({'background':'#D4D2D2'});
			$('#inv'+i).css({'display':'none'});
			i++;
		}	
		$('#inv'+id).css({'display':''});
		$('#invmn'+id).css({'background':'#A5A5A5'});
	}
}
var lastref = 0;
var fststart = 1;
var lastref2 = 0;
function refleshNow(idd)
{
	if(lastref==0)
	{
		$.post('transfer.php',{id:idd,money:$('#money2').val()},function(data){$("#refleshInv").html(data);});
		lastref = 1;
		if(fststart==1)
		{
			fststart = 0;
			setInterval('refleshNow("minireflesh");',7500);
		}
	}else{
		setTimeout('lastref=0;',1000);	
	}
}
function s2g()
{
	$('#s2g1').css({
		'background-color':'#c0c0c5',
		'color':'',
		'border-bottom-color':'#909090'
	});
	$('#s2g2').css({
		'background-color':'#D0D0D5',
		'color':''
	});
}
function refmoney(m1,m2)
{
	$('#money1').html('<b>'+m1+'</b>');
	if(m2><? echo $u->info['money']; ?>)
	{
		m2 = <? echo $u->info['money']; ?>;	
	}
	$('#money2').val(m2);
}
function saleitem(idd,v)
{
	if(lastref2==0)
	{
		$.post('transfer.php',{id:'sale',money:$('#money2').val(),itemid:idd,saletype:v},function(data){$("#refleshInv").html(data);});
		lastref2 = 1;
		setTimeout('lastref2=0;',350);
	}else{
		setTimeout('lastref2=0;',250);	
	}	
}
function cancelitm(idd)
{
	if(lastref2==0)
	{
		$.post('transfer.php',{id:'sale',money:$('#money2').val(),cancelid:idd},function(data){$("#refleshInv").html(data);});
		lastref2 = 1;
		setTimeout('lastref2=0;',350);
	}else{
		setTimeout('lastref2=0;',250);	
	}	
}
function clickBtn2()
{
	$.post('transfer.php',{id:'sale',money:$('#money2').val(),cancel2:'true'},function(data){$("#refleshInv").html(data);});
}
function clickBtn1()
{
	$.post('transfer.php',{id:'sale',money:$('#money2').val(),start2:'true'},function(data){$("#refleshInv").html(data);});
}
</script>
<div id="refleshInv" style="display:;"></div>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="110" align="right">&nbsp;</td>
        <td align="center">Передача предметов/кредитов между <? echo $tu[1].' и '.$tu[2]; ?></td>
        <td width="110" align="right"><input type="button" onclick="location='main.php?transfer&exit_transfer&rnd=<? echo $code; ?>';" name="button2" id="button2" value="Вернуться" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top">
      <table width="100%" height="100%" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td valign="top">
                <table width="100%" style="border:1px solid #909090;" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td id="s2g1" style="color:#BABABA;background-color:#DCDCDE; border-bottom:1px solid #D0D0D5; border-right:1px solid #909090;"><span style="border-bottom:1px solid #909090;"><img id="gd1" style="float:right;display:none;" width="13" height="13" src="http://img.xcombats.com/i/ready.gif" title="Персонаж готов к обмену" /></span>&nbsp;<? echo $tu[2]; ?> отдаёт:<br />&nbsp;<span id="money1"><b>0</b>.<small><i>00</i></small></span> кр.</td>
                    <td width="50%" bgcolor="#c0c0c5" style="border-bottom:1px solid #909090;"><img style="float:right;display:none;" width="13" height="13" id="gd2" src="http://img.xcombats.com/i/ready.gif" title="Персонаж готов к обмену" />&nbsp;Вы отдаёте:<br />&nbsp;<input id="money2" name="money2" type="text" style="width:37px;" value="0.00" /> кр. из <b><? echo $u->info['money']; ?></b></td>
                  </tr>
                  <tr>
                    <td valign="top" id="s2g2" style="background-color:#EEEEEE; border-right:1px solid #909090;">&nbsp;</td>
                    <td valign="top" bgcolor="#D0D0D5" id="s2g3">&nbsp;</td>
                  </tr>
                </table>
                <table width="100%" style="border-left:1px solid #909090;border-right:1px solid #909090;border-bottom:1px solid #909090;" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" valign="top" bgcolor="#D0D0D5"><button id="btn1" onClick="clickBtn1();">Готов к обмену</button> &nbsp; <button id="btn2" onClick="clickBtn2();">Отмена</button></td>
                  </tr>
                </table>
            </td>
            <td width="50%" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#d4d2d2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="25%" id="invmn1" onclick="gorazdel(1);" align="center" style="background-color:#A5A5A5;"><a href="#">Обмундирование</a></td>
                        <td width="25%" id="invmn2" onclick="gorazdel(2);" align="center"><a href="#">Заклятия</a></td>
                        <td width="25%" id="invmn3" onclick="gorazdel(3);" align="center"><a href="#">Эликсиры</a></td>
                        <td width="25%" id="invmn6" onclick="gorazdel(6);" align="center"><a href="#">Руны</a></td>
                        <td width="25%" id="invmn4" onclick="gorazdel(4);" align="center"><a href="#">Прочее</a></td>
                      </tr>
                    </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="#a5a5a5"><strong>Рюкзак (масса: 0/0, предметов: 0)</strong></td>
                  </tr>
                  <tr>
                    <td bgcolor="#D4D2D2" style="border:1px solid #a5a5a5;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="inv1"></table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="inv2" style="display:none;"></table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="inv3" style="display:none;"></table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="inv4" style="display:none;"></table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="inv6" style="display:none;"></table>
                    </td>
                  </tr>
                </table>
            </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<script>refleshNow('reflesh');</script>
<?
}else{
	if($u->tfer['uid1']==$u->info['id'])
	{
		mysql_query('UPDATE `transfers` SET `finish1` = "0" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
	}elseif($u->tfer['uid2']==$u->info['id'])
	{
		mysql_query('UPDATE `transfers` SET `finish2` = "0" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
	}
?>
{отображаем лог передач}
<? } ?>
<div align="right"><? echo $c['counters']; ?></div>