<?php
include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
if($u->info['admin'] > 0) {
	if(isset($_POST['menu_title'])) {
		mysql_query('INSERT INTO `newlibrary_menu` (
			`time`,`title`,`type`,`url`,`pos`
		) VALUES (
			"'.time().'","'.mysql_real_escape_string($_POST['menu_title']).'","'.mysql_real_escape_string($_POST['menu_type']).'","'.mysql_real_escape_string($_POST['menu_url']).'","0"
		)');
	}elseif(isset($_POST['save_posmenu'])){
		$sp = mysql_query('SELECT `id` FROM `newlibrary_menu` WHERE `delete` = 0 ORDER BY `pos` ASC, `id` ASC');
		while( $pl = mysql_fetch_array($sp) ) {
			if(isset($_POST['menupos_'.$pl['id']])) {
				$vl = (int)$_POST['menupos_'.$pl['id']];
				mysql_query('UPDATE `newlibrary_menu` SET `pos` = "'.mysql_real_escape_string($vl).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			}
		}
		unset($vl);
	}
}
$u->info['admin'] = 0;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>Старый Бойцовский Клуб | Библиотека
</title>
<link href="/main1.css" rel="stylesheet" type="text/css">
<style type="text/css">
.style6 {	color: #DFD3A3;
	font-size: 9px;
}
.inup3 {
	border: 1px dashed #D3CAA0;
	font-size: 12px;

}
.inup3 {
	border: 1px dashed #D3CAA0;
	font-size: 12px;

}
A:link {
	FONT-WEIGHT: bold; COLOR: #5B3E33; TEXT-DECORATION: none
}
A:visited {
	FONT-WEIGHT: bold; COLOR: #633525; TEXT-DECORATION: none
}
A:active {
	FONT-WEIGHT: bold; COLOR: #77684d; TEXT-DECORATION: none
}
A:hover {
	COLOR: #000000; TEXT-DECORATION: underline
}
img {
	border:none;
}
</style>
</head>
<body bgcolor="#000000" topmargin=0 leftmargin=0 marginheight=0 marginwidth=0>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr valign=top>
    <td><table width="100%" height="135"  border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td background="http://xcombats.com/forum_script/img/line_capitalcity.jpg" scope="col" align=center><img style="display:block" src="http://xcombats.com/inx/newlogo.jpg" width="364" height="135" border=0></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor='#3D3D3B'>
  <tr valign=top>
    <td></td>
    <td align=center><SCRIPT>
wsize=document.body.clientWidth;
wsize=1000;
document.write('<table cellspacing=0 cellpadding=0 bgcolor=#f2e5b1 border=0 width='+(wsize-20)+'>');
    </SCRIPT>
  <tr valign=top>
      <td width="29" rowspan=2 background="http://xcombats.com/forum_script/img/leftground.jpg"><img src="http://img.xcombats.com/i/encicl/pictlr_subject.jpg" width="29" height="256"></td>
      <td width="278" height="257" align="left"><img id="imleft2" src="http://img.xcombats.com/i/encicl/pictl_subject.jpg" width="118" height="257"><BR></td>
    <td rowspan=2 align="left"><p><b>&raquo;</b> <a href="http://xcombats.com/library/">Библиотека</a> /
      <h2>Библиотека Анти Бойцовского Клуба</h2>
        <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
        </p>
	  Выберите один из разделов слева, чтобы отобразить содержимое<br>      <p align="center">&nbsp;</p>
        <BR>
        <p>
          <!-- End of text -->
    <td style='padding-left: 3' align=right><img id="imright2" height=144 src="http://img.xcombats.com/i/encicl/pict_other.jpg" width=139 border=0></td>
      <td valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr valign=top>
    <td valign="top">
    
    <!-- -->
    
    <b><span style="COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 11pt;">Меню Библиотеки</span></b><br>
    <table width="100%" height="11" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td width="12" align="left"><img src="http://img.xcombats.com/ram12_33.gif" width="12" height="11"></td>
		<td style="background-image:url(http://img.xcombats.com/ram12_34.gif); background-repeat:repeat-x; background-position:0 2px;"></td>
		<td width="13" align="right"><img src="http://img.xcombats.com/ram12_35.gif" width="13" height="11"></td>
	</tr>
    </table>
    <?
	$html = '';
	$sp = mysql_query('SELECT * FROM `newlibrary_menu` WHERE `delete` = 0 ORDER BY `pos` ASC, `id` ASC');
	while( $pl = mysql_fetch_array($sp) ) {
		$adm = '';
		if( $u->info['admin'] > 0 ) {
			$adm .= '<input style="width:30px;" type="text" value="'.$pl['pos'].'" name="menupos_'.$pl['id'].'">';
		}
		if( $pl['type'] == 0 ) {
			$html .= '<br>'.$adm.'<b>'.$pl['title'].'</b><br><br>';
		}elseif( $pl['type'] == 1 ) {
			$html .= ''.$adm.'&nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="'.$pl['url'].'">'.$pl['title'].'</a>&nbsp;<br>';
		}elseif( $pl['type'] == 2 ) {
			$html .= ''.$adm.'&nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="'.$pl['url'].'" target="_blank">'.$pl['title'].'</a>&nbsp;<br>';
		}else{
			$html .= $adm.$pl['title'].'<br>';
		}
	}	
	if($u->info['admin'] > 0) {
		//
		$html = '<form method="post" action="/library">'.$html.'<center><input type="submit" name="save_posmenu" value="Сохранить позиции"></center></form>';
		//
		$html .= '<hr><form method="post" action="/library"><div>';
		$html .= '<b>Добавить новое меню:</b><br>';
		$html .= 'Заголовок: <br><input style="width:210px" type="text" value="" name="menu_title"><br>';
		$html .= 'Ссылка: <br><input style="width:210px" type="text" value="" name="menu_url"><br>';
		$html .= 'Тип: <select name="menu_type"><option value="0">Заголовок</option><option value="1">Внутреняя ссылка</option><option value="2">Внешняя ссылка</option></select><br>';
		$html .= '<center><input type="submit" class="btnnew" value="Добавить"></center>';
		$html .= '</div></form>';
	}
	echo $html;
	?>
    <br><br><br> 
    <!-- -->
    
    </td>
    <td valign="bottom" style="padding-bottom:50" align="right"><IMG height=236 src="http://img.xcombats.com/i/encicl/pictr_subject.jpg" width=128 border=0></td>
    <td width="23" valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;</td>
  </tr>
</table>
</td>
<td></td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor=#000000>
  <TR>
    <TD colspan=3 width="100%" height=13 background="http://img.xcombats.com/i/encicl/ln_footer.jpg"></TD>
  </TR>
  <tr valign=top>
    <td width="20%"><div align="center">
&nbsp;
      </div></td>
    <td align=center valign=middle><div align="center" style="padding: 5px 0px; height: 32px; box-sizing: border-box;"><NOBR><span class="style6">Copyright © 2016-<?=date('Y')?> «www.xcombats.com»</span></NOBR><br><Br></div></td>
    <td width="20%"></td>
  </tr>
</table>
</body>
</html>