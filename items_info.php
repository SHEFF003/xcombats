<?php
define('GAME',time());

include('_incl_data/class/__db_connect.php');

$u = mysql_fetch_array(mysql_query('SELECT `id`,`admin` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
if(!isset($u['id']) || $u['admin'] == 0) {
	$admin = 0;
}else{
	$admin = 1;
}
unset($u);

if(!isset($_GET['id'])) {
	$urla = explode('?',$_SERVER["REQUEST_URI"]);
	$url = explode('/',$urla[0]);

	$_GET['id'] = round((int)$url[2]);
}

/* разделы */
$rz = array(
	array('alls','Прочее'),
	array('crutch','Костыли'), 					// 1
	array('potion','Эликсиры'),					// 2
	array('scrollattack','Магические свитки'),	// 3
	array('armor','Броня'),						// 4
	array('venok','Венки'),						// 5
	array('naruchi','Наручи'),					// 6
	array('shoes','Обувь'),						// 7
	array('glove','Перчатки'),					// 8
	array('Cloak','Плащи'),						// 9
	array('leg','Поножи'),						// 10
	array('belt','Пояса'),						// 11
	array('shirt','Рубахи'),					// 12
	array('helmet','Шлемы'),					// 13
	array('el_sm','Елки'),						// 14
	array('flail','Дубины и Булавы'),			// 15
	array('knuckleduster','Ножи и Кастеты'),	// 16
	array('sword','Мечи'),						// 17
	array('staff','Посохи'),					// 18
	array('pole-axe','Топоры и Секиры'),		// 19
	array('flower','Цветы и Букеты'),			// 20
	array('component','Компоненты'),			// 21
	array('magicitem','Магические предметы'),	// 22
	array('gift','Подарки'),					// 23
	array('shield','Щиты'),						// 24
	array('ring','Кольца'),						// 25
	array('necklace','Ожерелья'),				// 26
	array('earring','Серьги'),					// 27 
	array('other','Разное')					// 28 
);

// Отображать 3Д изображение? True - отображать в любом случае, False - только если существует.
$img3dShow = false; 

/* Типы предметов по разделам */
$rt = array(
	22	=> 0,
	26	=> 1,
	30	=> 2,
	29	=> 3,
	5	=> 4,
	6	=> 4,
	2	=> 5,
	3	=> 6,
	15	=> 7,
	12	=> 8,
	7	=> 9,
	14	=> 10,
	8	=> 11,
	4	=> 12,
	1	=> 13,
		//28	=> 14,
	20	=> 15,
	18	=> 16,
	21	=> 17,
	22	=> 18,
	19	=> 19,
	28	=> 20,
		//00	=> 21,
		//00	=> 22,
	38	=> 23,
	39	=> 23,
	37	=> 23,
	13	=> 24,
	11	=> 25,
	10	=> 26,
	9	=> 27,	
	62	=> 22,	
	67	=> 28,	// 67 разное	
	32	=> 22,	// 32 ингридиенты, маг.предметы
	34	=> 28	// 34 ключи, разное
);

/* Пользователь */
$u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));

/* Предмет */
$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($_GET['id']).'" LIMIT 1'));


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

if(!isset($itm['id'])) {
	$itd = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($_GET['id']).'" LIMIT 1'));
	if(!isset($itd['id'])) {
		$itd = array();
	}else{
		$itd = lookStats($itd['data']);
	}
}else{
	$itd = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1'));
	$itd = lookStats($itd['data']);
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
	$rz[$rt[$itm['type']]][1] = 'Прочие предметы';
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>Старый Бойцовский Клуб | Библиотека
<? if(isset($itm['id'])) { ?>
|
<?=$rz[$rt[$itm['type']]][1]?>
|
<?=$itm['name']?>
<? } ?>
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
      <p><b>»</b> <a href="http://xcombats.com/item/">Предметы</a> / <?=$rz[$rt[$itm['type']]][1]?> / <b><i><?=$itm['name']?></i></b>  
      <h2><?=$itm['name']?></h2>
      <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
      </p>
    <? if( $img3dShow==false && (file_exists('../img.xcombats.com/i/big/3d'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true) OR $img3dShow==true ) { ?>  
	<div align="center"> 
        <table width="504"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"  width="12" height="10"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
            <td width="100%" background="http://img.xcombats.com/i/encicl/ram12_34.gif"></td>
            <td width="12" height="10" align="right"><img style="display:block" src="http://img.xcombats.com/i/encicl/ll12_30.gif" width="12" height="10"></td>
          </tr>
          <tr>
            <td width="12" background="http://img.xcombats.com/i/encicl/line_left_13.gif">&nbsp;</td>
            <td width="480"><img style="display:block" src="<? if( file_exists('../img.xcombats.com/i/big/3d'.str_replace('.gif','',str_replace('.png','',$itm['img'])).'.jpg') == true ) { ?>http://img.xcombats.com/i/big/3d<?=str_replace('.gif','',str_replace('.png','',$itm['img']))?>.jpg<? }else{ ?>http://img.xcombats.com/i/big/back.jpg<? } ?>" alt="" width="480" height="360" border=1 id="bigim"></td>
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
<IMG SRC="http://img.xcombats.com/i/align/align0.gif" WIDTH=12 HEIGHT=15>  
<?
if($admin == 1) {
	$crd = '<small><a href="javascript:window.open(\'http://xcombats.com/item_edit_data.php?edit_item_data='.$itm['id'].'\',\'winEdi1\',\'width=850,height=400,top=400,left=500,resizable=no,scrollbars=yes,status=no\');" target="_blank">Редактировать предмет</a> </small> &nbsp; &nbsp;';
}
echo $crd;

if($itm['massa']>0) {
	echo '(Масса: '.$itm['massa'].') ';
}
if(isset($itd['art'])) {
	echo '<IMG SRC=http://img.xcombats.com/i/artefact.gif WIDTH=18 HEIGHT=16 ALT="Артефактная вещь"> ';
}

				if(isset($po['sudba']))
				{
					echo '<img title="Этот предмет будет связан общей судьбой с первым, кто наденет его. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif"> ';
				}

if($itm['price1'] > 0) {
	echo '<br><b>Цена: '.$itm['price1'].' кр.</b>';
}

if($itm['price2'] > 0) {
	echo '<br><b style="color:SaddleBrown ">Цена: '.$itm['price2'].' екр.</b>';
}

if($itm['iznosMAXi'] >= 999999999) {
	echo '<br>Долговечность: <font color="brown">неразрушимо</font >';
}elseif($itm['iznosMAXi'] > 0) {
	echo '<br>Долговечность: 0/'.$itm['iznosMAXi'].'';
}


				//Срок годности предмета
				if($itd['srok'] > 0)
				{
					$itm['srok'] = $itd['srok'];
				}
				if($itm['srok'] > 0)
				{
					echo '<br>Срок годности: '.timeOut($itm['srok']);
				}
				
				//Продолжительность действия магии:
				if((int)$itm['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.((int)$itm['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						echo '<br>Продолжительность действия: '.timeOut($efi['actionTime']);
					}
				}


/* требования */
$tr = '';

$t = $items['tr'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['tr_'.$n])) {
		$tr .= '<br>• ';
		$tr .= $is[$n].': '.$itd['tr_'.$n];
	}
	$x++;
}

if($tr != '') {
	echo '<br><B>Требуется минимальное:</B>'.$tr;
}

/* действует на */
$tr = '';

$t = $items['add'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['add_'.$n]) && isset($is[$n])) {
		$z = '+';
		if($itd['add_'.$n] < 1) {
			$z = '';
		}
		$tr .= '<br>&bull; '.$is[$n].': '.$z.$itd['add_'.$n];
	}
	$x++;
}

				//действует на (броня)
				$i = 1; $bn = array(1=>'головы',2=>'корпуса',3=>'пояса',4=>'ног');
				while($i<=4)
				{
					if(isset($itd['add_mab'.$i]))
					{
						if($itd['add_mab'.$i]==$itd['add_mib'.$i] && $itm['geniration']==1)
						{
							$z = '+';
							if($itd['add_mab'.$i]<0)
							{
								$z = '';
							}
							$tr .= '<br>&bull; Броня '.$bn[$i].': '.$z.''.$itd['add_mab'.$i];
						}else{
							$tr .= '<br>&bull; Броня '.$bn[$i].': '.$itd['add_mib'.$i].'-'.$itd['add_mab'.$i];
						}
					}
					$i++;
				}

if($tr != '') {
	echo '<br><B>Действует на:</B>'.$tr;
}

/* свойства предмета */
$tr = '';

				if(isset($itd['sv_yron_min'],$itd['sv_yron_max']))
				{
					$tr .= '<br>• Урон: '.$itd['sv_yron_min'].' - '.$itd['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($itd['sv_'.$n]))
					{
						$z = '+';
						if($itd['sv_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>• '.$is[$n].': '.$z.''.$itd['sv_'.$n];
					}
					$x++;
				}
				if($itm['2too']==1)
				{
					$tr .= '<br>• Второе оружие';
				}
				if($itm['2h']==1)
				{
					$tr .= '<br>• Двуручное оружие';
				}
				if(isset($itd['zonb']))
				{
					$tr .= '<br>• Зоны блокирования: ';
					if($itd['zonb']>0)
					{
						$x = 1;
						while($x<=$itd['zonb'])
						{
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '—';
					}
				}

if($tr != '') {
	echo '<br><B>Свойства предмета:</B>'.$tr;
}

/* особенности */
$tr = '';

				$x = 1;
				while($x<=4)
				{
					if($itd['tya'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($itd['tya'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($itd['tya'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($itd['tya'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($itd['tya'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($itd['tya'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($itd['tya'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>&bull; '.$is['tya'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if($itd['tym'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($itd['tym'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($itd['tym'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($itd['tym'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($itd['tym'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($itd['tym'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($itd['tym'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>&bull; '.$is['tym'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x <= 4)
				{
					if($itd['add_oza'.$x]>0)
					{
						$tyc = 'Слабая';
						if($itd['add_oza'.$x] == 4)
						{
							$tyc = 'Посредственная';
						}
						if($itd['add_oza'.$x] == 2)
						{
							$tyc = 'Нормальная';
						}
						if($itd['add_oza'.$x] == 3)
						{
							$tyc = 'Хорошая';
						}
						if($itd['add_oza'.$x] == 5)
						{
							$tyc = 'Великолепная';
						}
						if($tyc != '') {
							$tr .= '<br>&bull; '.$is['oza'.$x].': '.$tyc;
						}
					}
					$x++;
				}

				if(isset($itd['free_stats']) && $itd['free_stats']>0)
				{
					echo '<br><b>Свободные распределения:</b><br>&bull; Возможных распределений: '.$itd['free_stats'];
				}

if($tr != '') {
	echo '<br><B>Особенности:</B>'.$tr;
}
				$is2 = '';

				if(isset($itd['complect']))
				{
					$is2 .= '<br><i>Дополнительная информация:</i>';
				}
				if(isset($itd['complect']))
				{
					//не отображается
					$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT * FROM `complects` WHERE `com` = "'.$itd['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($itmc = mysql_fetch_array($spc))
					{
						$com1['name'] = $itmc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$itmc['x'].'</font>: ';
						//действие комплекта
						$i1c = 0; $i2c = 0;
						$i1e = lookStats($itmc['data']);
						while($i1c<count($items['add']))
						{
							if(isset($i1e[$items['add'][$i1c]]))
							{
								$i3c = $i1e[$items['add'][$i1c]];
								if($i3c>0)
								{
									$i3c = '+'.$i3c;
								}
								if($i2c>0)
								{
									$com1['text'] .= '&nbsp; &nbsp; '.$is[$items['add'][$i1c]].': '.$i3c;
								}else{
									$com1['text'] .= $is[$items['add'][$i1c]].': '.$i3c;
								}								
								$com1['text'] .= '<br>';
								$i2c++;
							}
							$i1c++;
						}
						unset($i1c,$i2c,$i3c);
						$com1['x']++;
					}
					$is2 .= '<br>&bull; Часть комплекта: <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				$is2 .= '<small style="font-size:10px;">';
				
				if($itm['info']!='')
				{
					$is2 .= '<div><b>Описание:</b></div><div>'.$itm['info'].'</div>';
				}
				
				if($itd['info']!='')
				{
					$is2 .= '<div>'.$itd['info'].'</div>';                                        
				}
				
				if($itm['max_text']-$itm['use_text'] > 0) {
					$is2 .= '<div>Количество символов: '.($itm['max_text']-$itm['use_text']).'</div>';
				}
				
				if(isset($itd['noremont']))
				{
					$is2 .= '<div style="color:brown;">Предмет не подлежит ремонту</div>';
				}
				
				if(isset($itd['frompisher']) && $itd['frompisher']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
				}
				
				if($itm['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
				}				
				
				$is2 .= '</small>';
				
echo $is2;

?>
<BR>



<? /*

<B>Требуется минимальное:</B><BR>&bull; Уровень: 4<BR>&bull; Выносливость: 16<BR>&bull; Сила: 16<BR>
<B>Действует на:</B><BR>&bull; Мф. увертывания (%): +30<BR>&bull; Интуиция: +3<BR>&bull; Сила: +5<BR>
<B>Свойства предмета:</B><BR>&bull; Урон: 8 - 18<BR>&bull; Мф. против увертывания (%): 30<BR>&bull; Мф. критического удара (%): 50<BR>&bull; Мастерство владения мечами: 2<BR>&bull; Зоны блокирования: +<BR>

<B>Особенности:</B><BR>&bull; Колющие атаки: Малы<BR>&bull; Рубящие атаки: Временами<BR>&bull; Дробящие атаки: Редки<BR>&bull; Режущие атаки: Малы<BR>



<small>Возможно усиление до 10го уровня</small><BR>

*/
?>

</td>
<td align="center" valign="top" style='padding: 0,2,0,5'>
<a title="<?=$itm['name']?>" href="/item/<?=$itm['id']?>"><SPAN style='background-color: #E0E0E0'><img src="http://img.xcombats.com/i/items/<?=$itm['img']?>" alt="<?=$itm['name']?>" name="image" border="0"></SPAN></a></td>
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
	$rname = 'Библиотека предметов Бойцовского Клуба';
	$html = '';
	//
	if( $rv > 0 ) {
		
		if($rv == 1) {
			//Костыли
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 26');
		}elseif($rv == 3) {
			//Свитки
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 29 AND `name` NOT LIKE "%Кристаллизатор%"');
		}elseif($rv == 2) {
			//Эликсиры
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 30');
		}elseif($rv == 6) {
			//Броня тяжелая
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 6');
		}elseif($rv == 32) {
			//Броня легкая
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 5');
		}elseif($rv == 7) {
			//Венки на голову
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 2');
		}elseif($rv == 8) {
			//Наручи
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 3');
		}elseif($rv == 9) {
			//Ботинки
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 15');
		}elseif($rv == 10) {
			//Перчатки
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 12');
		}elseif($rv == 11) {
			//Плащи
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 7');
		}elseif($rv == 12) {
			//Поножи
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 14');
		}elseif($rv == 13) {
			//Пояса
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 8');
		}elseif($rv == 14) {
			//Рубахи
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 4');
		}elseif($rv == 15) {
			//Шлема
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 1');
		}elseif($rv == 16) {
			//Елки
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `name` LIKE "%Елка%"');
		}elseif($rv == 17) {
			//Дубины
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 20');
		}elseif($rv == 18) {
			//Кинжалы
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 18');
		}elseif($rv == 19) {
			//Мечи
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 21');
		}elseif($rv == 20) {
			//Топоры
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 19');
		}elseif($rv == 21) {
			//Посохи
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 22');
		}elseif($rv == 22) {
			//Цветы
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 32 AND `img` LIKE "f_%"');
		}elseif($rv == 23) {
			//Компоненты
			//$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 2');
		}elseif($rv == 24) {
			//Маг.предметы
			//$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 2');
		}elseif($rv == 25) {
			//Подарки
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 38 OR `type` = 63');
		}elseif($rv == 26) {
			//Руны
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 31');
		}elseif($rv == 27) {
			//Прочие
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 32');
		}elseif($rv == 28) {
			//Щиты
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 13');
		}elseif($rv == 29) {
			//Кольца
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 11');
		}elseif($rv == 30) {
			//Ожерелья
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 10');
		}elseif($rv == 31) {
			//Серьги
			$sp = mysql_query('SELECT * FROM `items_main` WHERE `type` = 9');
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
      <td width="29" rowspan=2 background="http://xcombats.com/forum_script/img/leftground.jpg"><img src="http://img.xcombats.com/i/encicl/pictlr_subject.jpg" width="29" height="256"></td>
      <td width="278" height="257" align="left"><img id="imleft2" src="http://img.xcombats.com/i/encicl/pictl_subject.jpg" width="118" height="257"><BR></td>
    <td rowspan=2 align="left"><p><b>&raquo;</b> <a href="http://xcombats.com/item/">Предметы</a> /
      <h2><?=$rname?></h2>
        <img src="http://img.xcombats.com/i/encicl/ln3.jpg" width="400" height="1">
        </p>
	  <?
	  if( $rv == 0 ) {
		 echo 'Выберите один из разделов слева, чтобы отобразить предметы<br>'; 
	  }else{
		
		
		while( $itm = mysql_fetch_array($sp) ) {
			
		//if(!isset($itm['id'])) {
			$itd = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1'));
			$itd = lookStats($itd['data']);			
			
			?><br>
	  <table width="100%" border="0" align=center cellpadding="0" cellspacing="0">
	    <tr>
	      <td align=left valign="top"><table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="inup3">
	        <tr>
	          <td width="100%"><A HREF="/item/<?=$itm['id']?>" target=_blank>
	            <?=$itm['name']?>
	            </A> <IMG SRC="http://img.xcombats.com/i/align/align0.gif" WIDTH=12 HEIGHT=15>
	            <?
				if(isset($_GET['e1'])) {
					$crd = '<small><a href="javascript:window.open(\'http://xcombats.com/item_edit_data.php?edit_item_data='.$itm['id'].'\',\'winEdi1\',\'width=850,height=400,top=400,left=500,resizable=no,scrollbars=yes,status=no\');" target="_blank">Редактировать предмет</a> </small> &nbsp; &nbsp;';
				}
				echo $crd;
				
				if($itm['massa']>0) {
					echo '(Масса: '.$itm['massa'].') ';
				}
				if(isset($itd['art'])) {
					echo '<IMG SRC=http://img.xcombats.com/i/artefact.gif WIDTH=18 HEIGHT=16 ALT="Артефактная вещь"> ';
				}

				if(isset($po['sudba']))
				{
					echo '<img title="Этот предмет будет связан общей судьбой с первым, кто наденет его. Никто другой не сможет его использовать." src="http://img.xcombats.com/i/destiny0.gif"> ';
				}

				if($itm['price1'] > 0) {
					echo '<br><b>Цена: '.$itm['price1'].' кр.</b>';
				}
				
				if($itm['price2'] > 0) {
					echo '<br><b style="color:SaddleBrown ">Цена: '.$itm['price2'].' екр.</b>';
				}
				
				if($itm['iznosMAXi'] >= 999999999) {
					echo '<br>Долговечность: <font color="brown">неразрушимо</font >';
				}elseif($itm['iznosMAXi'] > 0) {
					echo '<br>Долговечность: 0/'.$itm['iznosMAXi'].'';
				}


				//Срок годности предмета
				if($itd['srok'] > 0)
				{
					$itm['srok'] = $itd['srok'];
				}
				if($itm['srok'] > 0)
				{
					echo '<br>Срок годности: '.timeOut($itm['srok']);
				}
				
				//Продолжительность действия магии:
				if((int)$itm['magic_inci'] > 0)
				{
					$efi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.((int)$itm['magic_inci']).'" LIMIT 1'));
					if(isset($efi['id2']) && $efi['actionTime']>0)
					{
						echo '<br>Продолжительность действия: '.timeOut($efi['actionTime']);
					}
				}


/* требования */
$tr = '';

$t = $items['tr'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['tr_'.$n])) {
		$tr .= '<br>&bull; ';
		$tr .= $is[$n].': '.$itd['tr_'.$n];
	}
	$x++;
}

if($tr != '') {
	echo '<br><B>Требуется минимальное:</B>'.$tr;
}

/* действует на */
$tr = '';

$t = $items['add'];
$x = 0;
while($x<count($t)) {
	$n = $t[$x];
	if(isset($itd['add_'.$n]) && isset($is[$n])) {
		$z = '+';
		if($itd['add_'.$n] < 1) {
			$z = '';
		}
		$tr .= '<br>&bull; '.$is[$n].': '.$z.$itd['add_'.$n];
	}
	$x++;
}

				//действует на (броня)
				$i = 1; $bn = array(1=>'головы',2=>'корпуса',3=>'пояса',4=>'ног');
				while($i<=4)
				{
					if(isset($itd['add_mab'.$i]))
					{
						if($itd['add_mab'.$i]==$itd['add_mib'.$i] && $itm['geniration']==1)
						{
							$z = '+';
							if($itd['add_mab'.$i]<0)
							{
								$z = '';
							}
							$tr .= '<br>&bull; Броня '.$bn[$i].': '.$z.''.$itd['add_mab'.$i];
						}else{
							$tr .= '<br>&bull; Броня '.$bn[$i].': '.$itd['add_mib'.$i].'-'.$itd['add_mab'.$i];
						}
					}
					$i++;
				}

if($tr != '') {
	echo '<br><B>Действует на:</B>'.$tr;
}

/* свойства предмета */
$tr = '';

				if(isset($itd['sv_yron_min'],$itd['sv_yron_max']))
				{
					$tr .= '<br>&bull; Урон: '.$itd['sv_yron_min'].' - '.$itd['sv_yron_max'];
				}
				$x = 0;
				while($x<count($t))
				{
					$n = $t[$x];
					if(isset($itd['sv_'.$n]))
					{
						$z = '+';
						if($itd['sv_'.$n]<0)
						{
							$z = '';
						}
						$tr .= '<br>&bull; '.$is[$n].': '.$z.''.$itd['sv_'.$n];
					}
					$x++;
				}
				if($itm['2too']==1)
				{
					$tr .= '<br>&bull; Второе оружие';
				}
				if($itm['2h']==1)
				{
					$tr .= '<br>&bull; Двуручное оружие';
				}
				if(isset($itd['zonb']))
				{
					$tr .= '<br>&bull; Зоны блокирования: ';
					if($itd['zonb']>0)
					{
						$x = 1;
						while($x<=$itd['zonb'])
						{
							$tr .= '+';
							$x++;
						}
					}else{
						$tr .= '&mdash;';
					}
				}

if($tr != '') {
	echo '<br><B>Свойства предмета:</B>'.$tr;
}

/* особенности */
$tr = '';

				$x = 1;
				while($x<=4)
				{
					if($itd['tya'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($itd['tya'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($itd['tya'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($itd['tya'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($itd['tya'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($itd['tya'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($itd['tya'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>&bull; '.$is['tya'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x<=7)
				{
					if($itd['tym'.$x]>0)
					{
						$tyc = 'Ничтожно редки';
						if($itd['tym'.$x]>6)
						{
							$tyc = 'Редки';
						}
						if($itd['tym'.$x]>14)
						{
							$tyc = 'Малы';
						}
						if($itd['tym'.$x]>34)
						{
							$tyc = 'Временами';
						}
						if($itd['tym'.$x]>79)
						{
							$tyc = 'Регулярны';
						}
						if($itd['tym'.$x]>89)
						{
							$tyc = 'Часты';
						}
						if($itd['tym'.$x]>=100)
						{
							$tyc = 'Всегда';
						}
						$tr .= '<br>&bull; '.$is['tym'.$x].': '.$tyc;
					}
					$x++;
				}
				$x = 1;
				while($x <= 4)
				{
					if($itd['add_oza'.$x]>0)
					{
						$tyc = 'Слабая';
						if($itd['add_oza'.$x] == 4)
						{
							$tyc = 'Посредственная';
						}
						if($itd['add_oza'.$x] == 2)
						{
							$tyc = 'Нормальная';
						}
						if($itd['add_oza'.$x] == 3)
						{
							$tyc = 'Хорошая';
						}
						if($itd['add_oza'.$x] == 5)
						{
							$tyc = 'Великолепная';
						}
						if($tyc != '') {
							$tr .= '<br>&bull; '.$is['oza'.$x].': '.$tyc;
						}
					}
					$x++;
				}

				if(isset($itd['free_stats']) && $itd['free_stats']>0)
				{
					echo '<br><b>Свободные распределения:</b><br>&bull; Возможных распределений: '.$itd['free_stats'];
				}

if($tr != '') {
	echo '<br><B>Особенности:</B>'.$tr;
}
				$is2 = '';

				if(isset($itd['complect']))
				{
					$is2 .= '<br><i>Дополнительная информация:</i>';
				}
				if(isset($itd['complect']))
				{
					//не отображается
					$com1 = array('name'=>'Неизвестный Комплект','x'=>0,'text'=>'');
					$spc = mysql_query('SELECT * FROM `complects` WHERE `com` = "'.$itd['complect'].'" ORDER BY  `x` ASC LIMIT 20');
					while($itmc = mysql_fetch_array($spc))
					{
						$com1['name'] = $itmc['name'];
						$com1['text'] .= '&nbsp; &nbsp; &bull; <font color="green">'.$itmc['x'].'</font>: ';
						//действие комплекта
						$i1c = 0; $i2c = 0;
						$i1e = lookStats($itmc['data']);
						while($i1c<count($items['add']))
						{
							if(isset($i1e[$items['add'][$i1c]]))
							{
								$i3c = $i1e[$items['add'][$i1c]];
								if($i3c>0)
								{
									$i3c = '+'.$i3c;
								}
								if($i2c>0)
								{
									$com1['text'] .= '&nbsp; &nbsp; '.$is[$items['add'][$i1c]].': '.$i3c;
								}else{
									$com1['text'] .= $is[$items['add'][$i1c]].': '.$i3c;
								}								
								$com1['text'] .= '<br>';
								$i2c++;
							}
							$i1c++;
						}
						unset($i1c,$i2c,$i3c);
						$com1['x']++;
					}
					$is2 .= '<br>&bull; Часть комплекта: <b>'.$com1['name'].'</b><br><small>';
					$is2 .= $com1['text'];
					$is2 .= '</small>';
				}
				
				$is2 .= '<small style="font-size:10px;">';
				
				if($itm['info']!='')
				{
					$is2 .= '<div><b>Описание:</b></div><div>'.$itm['info'].'</div>';
				}
				
				if($itd['info']!='')
				{
					$is2 .= '<div>'.$itd['info'].'</div>';                                        
				}
				
				if($itm['max_text']-$itm['use_text'] > 0) {
					$is2 .= '<div>Количество символов: '.($itm['max_text']-$itm['use_text']).'</div>';
				}
				
				if(isset($itd['noremont']))
				{
					$is2 .= '<div style="color:brown;">Предмет не подлежит ремонту</div>';
				}
				
				if(isset($itd['frompisher']) && $itd['frompisher']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет из подземелья</div>';
				}
				
				if($itm['dn_delete']>0)
				{
					$is2 .= '<div style="color:brown;">Предмет будет удален при выходе из подземелья</div>';
				}				
				
				$is2 .= '</small>';
				
echo $is2;

?>
	            <BR>
	            <? /*

<B>Требуется минимальное:</B><BR>&bull; Уровень: 4<BR>&bull; Выносливость: 16<BR>&bull; Сила: 16<BR>
<B>Действует на:</B><BR>&bull; Мф. увертывания (%): +30<BR>&bull; Интуиция: +3<BR>&bull; Сила: +5<BR>
<B>Свойства предмета:</B><BR>&bull; Урон: 8 - 18<BR>&bull; Мф. против увертывания (%): 30<BR>&bull; Мф. критического удара (%): 50<BR>&bull; Мастерство владения мечами: 2<BR>&bull; Зоны блокирования: +<BR>

<B>Особенности:</B><BR>&bull; Колющие атаки: Малы<BR>&bull; Рубящие атаки: Временами<BR>&bull; Дробящие атаки: Редки<BR>&bull; Режущие атаки: Малы<BR>



<small>Возможно усиление до 10го уровня</small><BR>

*/
?></td>
	          <td align="center" valign="top" style='padding: 0,2,0,5'><a title="<?=$itm['name']?>" href="/item/<?=$itm['id']?>"><SPAN style='background-color: #E0E0E0'><img src="http://img.xcombats.com/i/items/<?=$itm['img']?>" alt="<?=$itm['name']?>" name="image" border="0"></SPAN></a></td>
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
    <td style='padding-left: 3' align=right><img id="imright2" height=144 src="http://img.xcombats.com/i/encicl/pict_subject.jpg" width=139 border=0></td>
      <td valign=top background="http://xcombats.com/forum_script/img/rightground.jpg">&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr valign=top>
    <td valign="top">
    
    <!-- -->
    
    <b><span style="COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 11pt;">Предметы</span></b><br>
    <table width="100%" height="11" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td width="12" align="left"><img src="http://img.xcombats.com/ram12_33.gif" width="12" height="11"></td>
		<td style="background-image:url(http://img.xcombats.com/ram12_34.gif); background-repeat:repeat-x; background-position:0 2px;"></td>
		<td width="13" align="right"><img src="http://img.xcombats.com/ram12_35.gif" width="13" height="11"></td>
	</tr>
    </table><br>
    <b>Амуниция</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i1">Костыли</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i2">Эликсиры и еда</a>&nbsp;<br>
    <b>Заклинания</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i3">Заклинания</a>&nbsp;<br>
    <b>Одежда</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i6">Тяжелая броня</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i32">Легкая броня</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i7">Венки</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i8">Наручи</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i9">Обувь</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i10">Перчатки</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i11">Плащи</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i12">Поножи</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i13">Пояса</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i14">Рубахи</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i15">Шлемы</a>&nbsp;<br>
    <b>Оружие</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i16">Ёлки</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i17">Дубины</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i18">Кинжалы</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i19">Мечи</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i20">Топоры</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i21">Посохи</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i22">Цветы и Букеты</a>&nbsp;<br>
    <b>Разное</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i23">Компоненты</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i24">Магические предметы</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i25">Подарки</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i26">Руны</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i27">Прочие предметы</a>&nbsp;<br>
    <b>Щиты</b><br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i28">Щиты</a>&nbsp;<br> 
    <b>Ювелирные товары</b><br> 
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i29">Кольца</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i30">Ожерелья</a>&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&middot;&nbsp;<a href="/item/i31">Серьги</a>&nbsp;<br>
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
