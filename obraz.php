<?php
define('GAME',time());

include('_incl_data/class/__db_connect.php');

if(!isset($_GET['id'])) {
	$urla = explode('?',$_SERVER["REQUEST_URI"]);
	$url = explode('/',$urla[0]);

	$_GET['id'] = round((int)$url[2]);
}

/* разделы */
$rz = array(
	'',
	'Мужские стандартные',
	'Женские стандартные' 
);

// Отображать 3Д изображение? True - отображать в любом случае, False - только если существует.
$img3dShow = true; 

/* Пользователь */
$u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));

/* Предмет */
$itm = mysql_fetch_array(mysql_query('SELECT * FROM `obraz` WHERE `id` = "'.mysql_real_escape_string($_GET['id']).'" LIMIT 1'));


	function timeOut($ttm) {
	    $out = '';
		$time_still = $ttm;
		$tmp = floor($time_still/2592000);
		$id=0;
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." мес. ";}
			$time_still = $time_still-$tmp*2592000;
		}
		/*
		$tmp = floor($time_still/604800);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." нед. ";}
			$time_still = $time_still-$tmp*604800;
		}
		*/
		$tmp = floor($time_still/86400);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." дн. ";}
			$time_still = $time_still-$tmp*86400;
		}
		$tmp = floor($time_still/3600);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ч. ";}
			$time_still = $time_still-$tmp*3600;
		}
		$tmp = floor($time_still/60);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." мин. ";}
		}
		if($out=='')
		{
			if($time_still<0)
			{
				$time_still = 0;
			}
			$out = $time_still.' сек.';
		}
		return $out;
	}

function lookStats($m) {
	$ist = array();
	$di = explode('|',$m);
	$i = 0; $de = false;
	while($i<count($di))
	{
		$de = explode('=',$di[$i]);
		if(isset($de[0]))
		{
			$ist[$de[0]] = $de[1];
		}
		$i++;
	}
	return $ist;
}

$itd = lookStats($itm['tr']);
if( $itm['name'] == '' ) {
	$itm['name'] = 'Образ №'.$itm['id'].'';
}
if( $itm['history'] == '' ) {
	$itm['history'] = 'Летописи Алхимика о нем умалчивают...';
}

$items = array(
					'tr'  => array('lvl','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','a1','a2','a3','a4','a5','a6','a7','mg1','mg2','mg3','mg4','mg5','mg6','mg7','mall','m2all','aall'),
					'add' => array('min_heal_proc','no_yv1','no_bl1','no_pr1','no_yv2','no_bl2','no_pr2','silver','pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','yron_min','yron_max','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack'),
					'sv' => array('pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','min_use_mp','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack')
					);
					
$is = array('oza'=>'Защита от урона','oza1'=>'Защита от колющего урона','oza2'=>'Защита от рубящего урона','oza3'=>'Защита от дробящего урона','oza4'=>'Защита от режущего урона','hpAll'=>'Уровень жизни (HP)','mpAll'=>'Уровень маны','sex'=>'Пол','lvl'=>'Уровень','s1'=>'Сила','s2'=>'Ловкость','s3'=>'Интуиция','s4'=>'Выносливость','s5'=>'Интелект','s6'=>'Мудрость','s7'=>'Духовность','s8'=>'Воля','s9'=>'Свобода духа','s10'=>'Божественность','m1'=>'Мф. критического удара (%)','m2'=>'Мф. против критического удара (%)','m3'=>'Мф. мощности критического удара (%)','m4'=>'Мф. увертывания (%)','m5'=>'Мф. против увертывания (%)','m6'=>'Мф. контрудара (%)','m7'=>'Мф. парирования (%)','m8'=>'Мф. блока щитом (%)','m9'=>'Мф. пробоя брони (%)','m14'=>'Мф. абс. критического удара (%)','m15'=>'Мф. абс. увертывания (%)','m16'=>'Мф. абс. парирования (%)','m17'=>'Мф. абс. контрудара (%)','m18'=>'Мф. абс. блока щитом (%)','m19'=>'Мф. абс. магический промах (%)','m20'=>'Мф. удача (%)','a1'=>'Мастерство владения ножами, кинжалами','a2'=>'Мастерство владения топорами, секирами','a3'=>'Мастерство владения дубинами, молотами','a4'=>'Мастерство владения мечами','a5'=>'Мастерство владения магическими посохами','a6'=>'Мастерство владения луками','a7'=>'Мастерство владения арбалетами','aall'=>'Мастерство владения оружием','mall'=>'Мастерство владения магией стихий','m2all'=>'Мастерство владения магией','mg1'=>'Мастерство владения магией огня','mg2'=>'Мастерство владения магией воздуха','mg3'=>'Мастерство владения магией воды','mg4'=>'Мастерство владения магией земли','mg5'=>'Мастерство владения магией Света','mg6'=>'Мастерство владения магией Тьмы','mg7'=>'Мастерство владения серой магией','tj'=>'Тяжелая броня','lh'=>'Легкая броня','minAtack'=>'Минимальный урон','maxAtack'=>'Максимальный урон','m10'=>'Мф. мощности урона','m11'=>'Мф. мощности магии стихий','m11a'=>'Мф. мощности магии','pa1'=>'Мф. мощности колющего урона','pa2'=>'Мф. мощности рубящего урона','pa3'=>'Мф. мощности дробящий урона','pa4'=>'Мф. мощности режущий урона','pm1'=>'Мф. мощности магии огня','pm2'=>'Мф. мощности магии воздуха','pm3'=>'Мф. мощности магии воды','pm4'=>'Мф. мощности магии земли','pm5'=>'Мф. мощности магии Света','pm6'=>'Мф. мощности магии Тьмы','pm7'=>'Мф. мощности серой магии','za'=>'Защита от урона','zm'=>'Защита от магии стихий','zma'=>'Защита от магии','za1'=>'Защита от колющего урона','za2'=>'Защита от рубящего урона','za3'=>'Защита от дробящий урона','za4'=>'Защита от режущий урона','zm1'=>'Защита от магии огня','zm2'=>'Защита от магии воздуха','zm3'=>'Защита от магии воды','zm4'=>'Защита от магии земли','zm5'=>'Защита от магии Света','zm6'=>'Защита от магии Тьмы','zm7'=>'Защита от серой магии','pza'=>'Понижение защиты от урона','pzm'=>'Понижение защиты от магии','pza1'=>'Понижение защиты от колющего урона','min_heal_proc'=>'Эффект лечения (%)','silver'=>'Премиум','notravma'=>'Защита от травм','yron_min'=>'Минимальный урон','yron_max'=>'Максимальный урон','pza2'=>'Понижение защиты от рубящего урона','pza3'=>'Понижение защиты от дробящего урона','pza4'=>'Понижение защиты от режущего урона','pzm1'=>'Понижение защиты от магии огня','pzm2'=>'Понижение защиты от магии воздуха','pzm3'=>'Понижение защиты от магии воды','pzm4'=>'Понижение защиты от магии земли','pzm5'=>'Понижение защиты от магии Света','pzm6'=>'Понижение защиты от магии Тьмы','pzm7'=>'Понижение защиты от серой магии','speedhp'=>'Регенерация здоровья (НР)','speedmp'=>'Регенерация маны (МР)','tya1'=>'Колющие атаки','tya2'=>'Рубящие атаки','tya3'=>'Дробящие атаки','tya4'=>'Режущие атаки','tym1'=>'Огненные атаки','tym2'=>'Электрические атаки','tym3'=>'Ледяные атаки','tym4'=>'Земляные атаки','tym5'=>'Атаки Света','tym6'=>'Атаки Тьмы','tym7'=>'Серые атаки','min_use_mp'=>'Уменьшает расход маны','pog'=>'Поглощение урона','maxves'=>'Увеличивает рюкзак');
 
if( !file_exists('../img.xcombats.com/i/encicl/pict_'.$rz[$rt[$itm['type']]][0].'.jpg') == true ) {
	//subject
	$rz[$rt[$itm['type']]][0] = 'subject';
}

if( !isset($rz[$rt[$itm['type']]][1])) {
	$rz[$rt[$itm['type']]][1] = '';
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Старый Бойцовский клуб | Библиотека <? if(isset($itm['id'])) { ?>| <?=$rz[$rt[$itm['type']]][1]?> | <?=$itm['name']?><? } ?></title>
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
<? if(isset($itm['id'])) { ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor='#3D3D3B'>
  <tr valign=top>
    <td></td>
    <td align=center><SCRIPT>
wsize=document.body.clientWidth;
if (wsize >= 800 ) wsize=Math.floor(wsize*0.8);
if (wsize < 700) wsize=700;
document.write('<table cellspacing=0 cellpadding=0 bgcolor=#f2e5b1 border=0 width='+(wsize-20)+'>');
</SCRIPT>
  <tr valign=top>
    <td width="29" rowspan=2 background="http://xcombats.com/forum_script/img/leftground.jpg"><img src="http://img.xcombats.com/i/encicl/pictlr_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width="29" height="256"></td>
    <td width="118" align="left"><img id="imleft" src="http://img.xcombats.com/i/encicl/pictl_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width="118" height="257"><BR>
    </td>
    <td rowspan=2 align="left">     
      <p><b>»</b> <a href="http://xcombats.com/shadow/">Образы</a> / <b><i><?=$itm['name']?></i></b>  
      <h2><?=$itm['name']?></h2>
      <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
      </p>
    <? if( $img3dShow==false && (file_exists('../img.xcombats.com/chars/enc/big'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true) OR $img3dShow==true ) { ?>  
	<div align="center"> 
        <table width="323"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"  width="12" height="10"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
            <td width="100%" background="http://img.xcombats.com/i/encicl/ram12_34.gif"></td>
            <td width="12" height="10" align="right"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
          </tr>
          <tr>
            <td width="12" background="http://img.xcombats.com/i/encicl/line_left_13.gif">&nbsp;</td>
            <td width="323"><img style="display:block" src="<? if( file_exists('../img.xcombats.com/chars/enc/'.$itm['sex'].'/big'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true ) { ?>http://img.xcombats.com/chars/enc/<?=$itm['sex']?>/big<?=str_replace('.gif','',str_replace('.png','',$itm['img']))?>.jpg<? }else{ ?>http://img.xcombats.com/chars/enc/back.jpg<? } ?>" alt="" width="323" height="600" border=1 id="bigim"></td>
            <td width="12" background="http://img.xcombats.com/i/encicl/line_right_13.gif">&nbsp;</td>
          </tr>
          <tr>
           		  <td align="left" width="12" height="10"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
                  <td width="100%" style="background-image:url(http://img.xcombats.com/i/encicl/ram12_34down.gif); background-position:bottom"></td>
                  <td width="12" align="right"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
          </tr>
        </table>
	</div>
	<? } ?>
      <p align="center">&nbsp;</p>
      <BR>
      <table width="504" border="0" align=center cellpadding="0" cellspacing="0">
        <tr>
          <td align=left valign="top">
	
<table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="inup3">
<tr>
<td width="100%">
<A HREF="?id=<?=$itm['id']?>" target=_blank><?=$itm['name']?></A>
<BR><BR>
<u>История образа:</u><br><?=$itm['history']?>
</td>
</tr>
</table>

	</tr>
      </table>
      <p>
      <!-- End of text -->
    <td style='padding-left: 3' align=right><img id="imright" height=144 src="http://img.xcombats.com/i/encicl/pict_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width=139 border=0></td>
    <td valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr valign=top>
    <td></td>
    <td valign=center style="padding-bottom:50" align="right"><IMG height=236 src="http://img.xcombats.com/i/encicl/pictr_<?=$rz[$rt[$itm['type']]][0]?>.jpg" width=128 border=0></td>
    <td width="23" valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;</td>
  </tr>
</table>
<? }else{
	
	$rv = explode('i',$url[2]);
	$rv = (int)$rv[1];
	//
	$rname = 'Библиотека образов Бойцовского Клуба';
	$html = '';
	//
	if( $rv > 0 ) {
		
		if($rv == 1) {
			//Мужские стандартные
			$sp = mysql_query('SELECT * FROM `obraz` WHERE `sex` = 0 AND `standart` = 1');
		}elseif($rv == 2) {
			//Женские стандартные
			$sp = mysql_query('SELECT * FROM `obraz` WHERE `sex` = 1 AND `standart` = 1');
		}
		
	}
	
	
	
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor='#3D3D3B'>
  <tr valign=top>
    <td></td>
    <td align=center><SCRIPT>
wsize=document.body.clientWidth;
if (wsize >= 800 ) wsize=Math.floor(wsize*0.8);
if (wsize < 700) wsize=700;
document.write('<table cellspacing=0 cellpadding=0 bgcolor=#f2e5b1 border=0 width='+(wsize-20)+'>');
    </SCRIPT>
 	  <tr valign=top>
      <td width="29" rowspan=2 background="http://xcombats.com/forum_script/img/leftground.jpg"><img src="http://img.xcombats.com/i/encicl/obrzz_08.jpg" width="29" height="256"></td>
      <td width="218" height="257" align="left"><img id="imleft2" src="http://img.xcombats.com/i/encicl/obrzz_04.jpg" width="118" height="257"><BR></td>
    <td rowspan=2 align="left"><p><b>&raquo;</b> <a href="http://xcombats.com/shadow/">Образы</a> /
      <h2><?=$rname?></h2>
        <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
        </p>
	  <?
	  if( $rv == 0 ) {
		 echo 'Выберите один из разделов слева, чтобы отобразить образы<br>'; 
	  }else{
		
		
		while( $itm = mysql_fetch_array($sp) ) {
			if( $itm['name'] == '' ) {
				$itm['name'] = 'Образ №'.$itm['id'];
			}
			?>
	  <table width="120" style="float:left;" border="0" align=center cellpadding="0" cellspacing="0">
	    <tr>
	      <td align=left valign="top"><table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="inup3">
	        <tr>
	          <td align="center" valign="top" style='padding: 0,2,0,5'><A HREF="/shadow/<?=$itm['id']?>" target=_blank><?=$itm['name']?><br><br></A><a title="<?=$itm['name']?>" href="/shadow/<?=$itm['id']?>"><SPAN style='background-color: #E0E0E0'><img src="http://img.xcombats.com/i/obraz/<?=$itm['sex']?>/<?=$itm['img']?>" alt="<?=$itm['name']?>" name="image" border="0"></SPAN></a></td>
	          </tr>
	        </table>
        </tr>
      </table>
	  <?
		} 
	  }
	  ?>
      <p align="center">&nbsp;</p>
        <BR>
        <p>
          <!-- End of text -->
    <td style='padding-left: 3' align=right><img id="imright2" height=144 src="http://img.xcombats.com/i/encicl/obrzz1.jpg" width=139 border=0></td>
      <td valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr valign=top>
    <td valign="top">
    
    <!-- -->
    
    <b><span style="COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 11pt;">Образы персонажей</span></b><br>
    <table width="100%" height="11" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td width="12" align="left"><img src="http://img.xcombats.com/ram12_33.gif" width="12" height="11"></td>
		<td style="background-image:url(http://img.xcombats.com/ram12_34.gif); background-repeat:repeat-x; background-position:0 2px;"></td>
		<td width="13" align="right"><img src="http://img.xcombats.com/ram12_35.gif" width="13" height="11"></td>
	</tr>
    </table><br>
    <b>Мужские</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/shadow/i1">Стандартные</a>&nbsp;<br>
    <b>Женские</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/shadow/i2">Стандартные</a>&nbsp;<br>
    <br><br><br> 
    <!-- -->
    
    </td>
    <td valign="bottom" style="padding-bottom:50" align="right"><IMG height=236 src="http://img.xcombats.com/i/encicl/pictr_subject.jpg" width=128 border=0></td>
    <td width="23" valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;</td>
  </tr>
</table>
<? } ?>
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
    <td align=center valign=middle><div align="center" style="padding: 5px 0px; height: 32px; box-sizing: border-box;"><NOBR><span class="style6">Copyright © <?=date('Y')?> «www.xcombats.com»</span></NOBR><br><Br></div></td>
    <td width="20%"></td>
  </tr>
</table>
</body>
</html>
