<?
if(!defined('GAME'))
{
	die();
}
if($u->room['file']=='an/znahar'){
if($c['znahar']==true){$raspst=99999;$kr=0;$price = 1;}else{$raspst=0;$kr=0;$price = 0;}

//Платно или бесплатно


if(date('w') == 6 || date('w') == 0) {
	$price = 1;
}

function add_narkoz($tm,$name) {
	global $u;
	if($name != '') {
		$name = ': '.$name;
	}
	//mysql_query('INSERT INTO `eff_users` (`uid`,`id_eff`,`name`,`data`,`overType`,`timeUse`) VALUES (
	//	"'.$u->info['id'].'","50","Наркоз'.mysql_real_escape_string($name).'",""
	//)');

	$name = 'Наркоз'.$name;
	$stat=rand(1, 3); // пока без духовности
	if($tm == 3) {
		$timeEnd=rand(2,5);// время травмы от 12 до 6 часов
		$timeEnd = $timeEnd * 3600;
	}elseif($tm == 1 || $tm == 2) {
		$timeEnd=rand(1,2);// время травмы от 5 до 10 мин
		$timeEnd = $timeEnd * (60*5);
	}else{
		$tm = round($tm*60);
	}
	
	$timeEnd = rand(1,3);// время травмы от 5 до 15 мин
	$timeEnd = $timeEnd * (60*5);
	//$timeEnd = 0;
	
	
	$data='add_s'.$stat.'=-'.($u->info['level']*rand(3,5));
	$v1=3;
	$img = 'eff_travma3.gif';
	mysql_query('INSERT INTO `eff_users` (`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES ("0","'.time().'","-1","'.$name.'","'.$data.'","'.$u->info['id'].'", "4", "'.$img.'","'.$timeEnd.'", "'.$v1.'")');
}

function test_skills() {
	global $u;
	$r = 0;
	$sp = mysql_query('SELECT * FROM `levels` WHERE `upLevel` < "'.$u->info['upLevel'].'" ORDER BY `upLevel` ASC');
	while( $pl = mysql_fetch_array($sp) ) {
		$r += $pl['skills'];
	}
	$r += $u->rep['add_skills'];
	return $r;
}

function test_skills2() {
	global $u;
	$r = 0;
	$sp = mysql_query('SELECT * FROM `levels` WHERE `upLevel` < "'.$u->info['upLevel'].'" ORDER BY `upLevel` ASC');
	while( $pl = mysql_fetch_array($sp) ) {
		$r += $pl['nskills'];
	}
	$r += $u->rep['add_skills2'];
	return $r;
}

function test_ability() {
	global $u;
	$r = 0;
	$sp = mysql_query('SELECT * FROM `levels` WHERE `upLevel` < "'.$u->info['upLevel'].'" ORDER BY `upLevel` ASC');
	while( $pl = mysql_fetch_array($sp) ) {
		$r += $pl['ability'];
	}
	//$r += 3*3;
	$r += $u->rep['add_stats'];
	return $r;
}

function test_s5() {
	global $u;
	$r = 0;
	$i = 0;
	$bns = array(
		3,1,1,1,1,1,1,1,1,2,3,5,30
	);
	while( $i <= $u->info['level'] ) {		
		$r += $bns[$i];
		$i++;
	}
	return $r;
}

$c['znahar1'] = 1; //сброс навыков
$c['znahar2'] = 1; //сброс особенностей
$c['znahar3'] = 1; //сброс статов
$c['znahar4'] = 1; //сброс пристрастия
$c['znahar5'] = 1; //перераспределение статов

if($price == 0) {

	$last_zn = mysql_fetch_array(mysql_query('SELECT `time` FROM `aaa_znahar` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `time` DESC LIMIT 1'));
	$last_zn = $last_zn['time'];
	
	$u->info['znahar'] = mysql_fetch_array(mysql_query('SELECT SUM(`point`) FROM `aaa_znahar` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
	$u->info['znahar'] = $u->info['znahar'][0];
	$u->info['znahar'] = 15 - $u->info['znahar'];
	
	$last_zn = (time()-$last_zn);
	$last_zn = floor($last_zn/( 60*60*24*7 ));
	
	$u->info['znahar'] += $last_zn;
	
	if($u->info['znahar'] > 15) {
		$u->info['znahar'] = 15;
	}
	
	$raspst = $u->info['znahar'];
	
	//
	$i = 1;
	$pr = array(0,
		25,
		15,
		75,
		25,
		5		
	);
	$pr_free = array(0,
		5,
		3,
		5,
		5,
		1				
	);
	while($i <= 5) {
		if( $pr_free[$i] > $u->info['znahar'] ) {
			$c['znahar'.$i] = 0;
		}
		$i++;
	}
}

function zact($i,$x = 1) {
	global $price, $c, $u, $pr, $pr_free;
	$point = 0;
	$price1 = 0;
	$r = 0;
	$bad = 0;
	
	if( $price == 1 ) {
		//бесплатно
		$point = 0;
		$price1 = 0;
	}else{
		//платно
		$point = $pr_free[$i]*$x;
		if($point > $u->info['znahar']) {
			$price1 = $pr[$i]*$x;
			$point = 0;
			if($price1 > $u->info['money']) {
				$bad = 1;
			}
		}
	}
	
	if($bad == 0) {	
		mysql_query('INSERT INTO `aaa_znahar` (`act`,`price`,`point`,`uid`,`time`,`city`) VALUES ("'.$i.'","'.$price1.'","'.$point.'","'.$u->info['id'].'","'.time().'","'.$u->info['city'].'")');
		if($price1 > 0) {
			if($price1 < 0) {
				$price1 = 0;
			}
			$u->info['money'] -= $price1;
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}
		$u->info['znahar'] -= $point;
		$r = array(1,$price1,$point);
		while($i <= 5) {
			if( $pr_free[$i] > $u->info['znahar'] ) {
				$c['znahar'.$i] = 0;
			}
			$i++;
		}
	}else{
		$r = array(0,'У вас недостаточно денег для этого действия.');
	}
	return $r;
}

#------------------Запросы и прочее
$st = $u->lookStats($u->info['stats']);
$vinos = array(
	/*   level,summvinos*/
	"0" => 3,
	"1" => 4,
	"2" => 5,
	"3" => 6,
	"4" => 7, 
	"5" => 8,
	"6" => 9,
	"7" => 10,
	"8" => 11,
	"9" => 13,
	"10" => 16,
	"11" => 21,
	"12" => 41,
	"21" => 41
);
//минимальный уровень для статов
$minlvl = array(
		1=>0,
		2=>0,
		3=>0,
		4=>0,
		5=>4,
		6=>7,
		7=>10,
		8=>12,
		9=>15,
		10=>20,
		11=>0
);

$tst_trvm = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE (`id_eff` = 4 OR `id_eff` = 5) AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 1'));
if(isset($tst_trvm['id'])) {
	$err = '<font color=red>У вас травма &quot;'.$tst_trvm['name'].'&quot;. Вы не можете пользоваться услугами знахаря!</font>';
}elseif(isset($_GET['unprist'])) {
	$epr = mysql_fetch_array(mysql_query('SELECT `a`.`id`,`b`.`mname` FROM `eff_users` AS `a` LEFT JOIN `eff_main` AS `b` ON `b`.`id2` = `a`.`id_eff` WHERE ((`a`.`id_eff` >= 301 AND `a`.`id_eff` <= 304) OR (`a`.`id_eff` >= 321 AND `a`.`id_eff` <= 332)) AND `a`.`uid` = "'.$u->info['id'].'" AND `a`.`id` = "'.mysql_real_escape_string((int)$_GET['unprist']).'" LIMIT 1'));
	if(isset($epr['id'])) {
		$z_na = zact(4);
		if($z_na[0] == 1) {
			//сброс пристрастия
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($epr['id']).'" LIMIT 1');
			$st = $u->lookStats($u->info['stats']);	
			add_narkoz(2,'Сброс пристрастий');
			$err = '<font color=red>Вы заплатили '.$kr.' кр. Все прошло успешно, но вам необходим отдых...</font>';
		}else{
			$err = '<font color=red>'.$z_na[1].'</font>';
		}
	}else{
		$err = '<font color=red>Пристрастие не найдено...</font>';
	}
}elseif(isset($_POST['dropstats']))
{
	$z_na = zact(3);
	if($z_na[0] == 1) {
		//сброс статов
		$st = $u->lookStats($u->info['stats']);
		$st['s1']  = 3;
		$st['s2']  = 3;
		$st['s3']  = 3;
		$st['s4']  = test_s5();
		$st['s5']  = 0;
		$st['s6']  = 0;
		$st['s7']  = 0;
		$st['s8']  = 0;
		$st['s9']  = 0;
		$st['s10'] = 0;
		$st['s11'] = 0;
		$st['s12'] = 0;
		$st['s13'] = 0;
		$st['s14'] = 0;
		$st['s15'] = 0;		
		$st = $u->impStats($st);
		$n1 = test_ability();
		$n2 = $u->info['skills'];
		$n3 = $u->info['sskills'];
		$n4 = $u->info['nskills'];
		mysql_query('UPDATE `stats` SET `wipe`="0",`stats`="'.$st.'",`ability`="'.$n1.'",`skills`="'.$n2.'",`sskills`="'.$n3.'",`nskills`="'.$n4.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		//$u->info['wipe'] = 2;
		//$u->wipe();
		$st = $u->lookStats($u->info['stats']);	
		add_narkoz(1,'Сброс характеристик');
		$err = '<font color=red>Вы заплатили '.$kr.' кр. Все прошло успешно, но вам необходим отдых...</font>';
	}else{
		$err = '<font color=red>'.$z_na[1].'</font>';
	}
}elseif(isset($_POST['dropmastery']))
{
	$z_na = zact(1);
	if($z_na[0] == 1) {
		//сброс статов
		$st = $u->lookStats($u->info['stats']);
		$st['a1']  = 0;
		$st['a2']  = 0;
		$st['a3']  = 0;
		$st['a4']  = 0;
		$st['a5']  = 0;
		$st['a6']  = 0;
		$st['mg1']  = 0;
		$st['mg2']  = 0;
		$st['mg3']  = 0;
		$st['mg4']  = 0;
		$st['mg5']  = 0;
		$st['mg6']  = 0;
		$st['mg7']  = 0;		
		$st = $u->impStats($st);
		$n1 = $u->info['ability'];
		$n2 = test_skills();
		$n3 = $u->info['sskills'];
		$n4 = $u->info['nskills'];
		mysql_query('UPDATE `stats` SET `wipe`="0",`stats`="'.$st.'",`ability`="'.$n1.'",`skills`="'.$n2.'",`sskills`="'.$n3.'",`nskills`="'.$n4.'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		//$u->info['wipe'] = 2;
		//$u->wipe();
		$st = $u->lookStats($u->info['stats']);	
		add_narkoz(1,'Сброс умений');
		$err = '<font color=red>Вы заплатили '.$kr.' кр. Все прошло успешно, но вам необходим отдых...</font>';
	}else{
		$err = '<font color=red>'.$z_na[1].'</font>';
	}
}elseif(isset($_POST['dropmastery']))
{
	//Старая версия
	$z_na = zact(1);
	if($z_na[0] == 1) {
		//сброс характеристик
		$u->info['wipe'] = 3;
		$u->wipe();
		$st = $u->lookStats($u->info['stats']);
		$err = '<font color=red>Умения покину вас...  вам плохо...</font>';
	}else{
		$err = '<font color=red>'.$z_na[1].'</font>';
	}
}elseif(isset($_POST['dropperks']))
{
	$z_na = zact(1);
	if($z_na[0] == 1) {
		//сброс особенностей
		$u->info['wipe'] = 3.5;
		$u->wipe();
		$st = $u->lookStats($u->info['stats']);	
		add_narkoz(1,'Сброс навыков');
		$err = '<font color=red>Особенности покинули вас... вам плохо...</font>';
	}else{
		$err = '<font color=red>'.$z_na[1].'</font>';
	}
}elseif(isset($_GET['movestat'])){
	//общее кол-во статов
	$allStats = $st['s1']+$st['s2']+$st['s3']+$st['s4']+$st['s5']+$st['s6']+$st['s7']+$st['s8']+$st['s9']+$st['s10'];
	$allStatsTesto = $st['s1']+$st['s2']+$st['s3']+$st['s4']+$st['s5']+$st['s6']+$st['s7']+$st['s8']+$st['s9']+$st['s10'];
	$no = 0; $st_l = 0;
	for($i=1;$i<=10;$i++){
		if(isset($_GET['s'.$i]) && ($st['s'.$i]+(int)$_GET['s'.$i]) > 0 && $i <= 10){
			if($minlvl[$i] > $u->info['level'] && $st['s'.$i] < (int)$_GET['s'.$i])
			{
				$no++;
			}else{
				if((int)$_GET['s'.$i] > 0) {
					$st_l += (int)$_GET['s'.$i];
				}
				$st['s'.$i] += (int)$_GET['s'.$i];
				$allStatsNew+=(int)$_GET['s'.$i];
				$allStatsTesto += (int)$_GET['s'.$i];
			}
		}
	}
	if( $allStatsTesto != $allStats ) {
		$no++;
	}
	//echo "Статы: <br>".$st['s1']."=3<br>".$st['s2']."=3<br>".$st['s3']."=3<br>".$st['s4']."=3<br>".$st['s5']."=0<br>".$st['s6']."=0<br>".$st['s7']."=0<br>".$st['s8']."=0<br>".$st['s9']."=0<br>".$st['s10']."=0<br>";
	if($no==0){
		
		$z_na = zact(5,(int)$st_l);
		if($z_na[0] == 1) {
			if( ($st['s5'] > 0 && $u->info['level'] < 4) || ($st['s6'] > 0 && $u->info['level'] < 7) || ($st['s7'] > 0 && $u->info['level'] < 9) || ($st['s8'] > 0 && $u->info['level'] < 11) || ($st['s9'] > 0 && $u->info['level'] < 12) || ($st['s10'] > 0 && $u->info['level'] < 13) ) {
				
			}elseif($st['s1']>=3 && $st['s2']>=3 && $st['s3']>=3 && $st['s4']>=test_s5() && $st['s5']>=0 && $st['s6']>=0 && $st['s7']>=0 && $st['s8']>=0 && $st['s9']>=0 && $st['s10']>=0){	
				//Желательно проверку на статы по уровню (можно ли этот стат поднимать на текущем уровне перса)
				$u->info['stats'] = $u->impStats($st);
				if(mysql_query('UPDATE `stats` SET `stats` = "'.mysql_real_escape_string($u->info['stats']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1'))
				{
					add_narkoz(2,'Переброс характеристик');
					//Надо добавить UPDATE в базе свободных статов				
					$u->stats = $u->getStats($u->info['id'],0,1);
					$u->testItems($u->info['id'],$u->stats,0);
					$st = $u->lookStats($u->info['stats']);
				}
			}
		}else{
			$err = '<font color=red>'.$z_na[1].'</font>';
		}
	}
}
#------------------Запросы и прочее
?>
<STYLE>
.H3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold;}
</STYLE>
<SCRIPT>
function gfastshow(dsc, dx, dy) { top.fullfastshow(document, mmoves3, window.event, dsc, dx, dy); }
function ghideshow() { top.fullhideshow(mmoves3); }
</SCRIPT>
</HEAD>
<body leftmargin=5 topmargin=5 marginwidth=5 marginheight=5 bgcolor=e2e0e0>
<div id="mmoves3" style="background-color:#FFFFCC; visibility:hidden; z-index: 101; overflow:visible; position:absolute; border-color:#666666; border-style:solid; border-width: 1px; padding: 2px;"></div>
<SCRIPT src='http://img.combats.ru/i/commoninf.js'></SCRIPT>
<TABLE width=100%>
<TR><TD><DIV class='H3' align=center>Комната Знахаря</DIV>
<?=$err;?><?
if($u->error != '') {
	echo '<br><font style="float:right" color=red>'.$u->error.'</font>';
}
 if($re!=''){ echo '<br><font style="float:right" color="red"><b>'.$re.'</b></font>'; }
?><BR>
<b><i>Запахи трав наполняют помещение, непонятные и пугающие предметы скрываются в пляшущих тенях...<BR>
Говорят, здесь можно изменить свою судьбу.
Стать кем-то иным... кем раньше был лишь в мечтах...</i></b><BR><BR>
Деньги: <B><?=$u->info['money'];?></B> кр.<BR>
Бесплатных перераспределений: <? if($price == 1) { ?>Сегодня - неограничено!<? }else{ echo round(0+$u->info['znahar']); } ?><BR>
</TD>
<TD width=1 valign=top>
<table  border="0" cellpadding="0" cellspacing="0">
<tr align="right" valign="top">
<td>
<!-- -->
<? echo $goLis; ?>
<!-- -->
<table  border="0" cellspacing="0" cellpadding="0">
<tr>
<td nowrap="nowrap">
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.combats.ru/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="javascript:void(0)" class="menutop" onClick="location='main.php?loc=2.180.0.260&rnd=<? echo $code; ?>';" title="<? thisInfRm('2.180.0.260',1); ?>">Торговый зал</a></td>
</tr>
</table>
</td>
</tr>
</table>
<!-- <br /><span class="menutop"><nobr>Комната Знахаря</nobr></span>-->
</td>
</tr>
</table>
<div id="mmoves" style="background-color:#FFFFCC; visibility:hidden; overflow:visible; position:absolute; border-color:#666666; border-style:solid; border-width: 1px; padding: 2px; white-space: nowrap;"></div>
</HTML>
</TD>
</TR>
</TABLE>
<TABLE>
<TR bgcolor=#D8D8D8>
<TD><IMG width=20 height=20 src='http://img.xcombats.com/i/strsmall.gif'> Характеристики</TD>
<TD><IMG width=20 height=20 src='http://img.xcombats.com/i/cureelixirsmall.gif'> Пристрастия</TD>
<TD><IMG width=20 height=20 src='http://img.xcombats.com/i/weaponsmall.gif'> Прочее</TD>
<TR>
<TD width=270 valign=top>
<SCRIPT>
var mylvl = <?=$u->info['level']; ?>;
//////////////Название,параметр,всего, +сколько, до скольки сбросить(макс возможное),сколько сбрасывать/прибавлять
var pr = new Array(
'Сила', 's1', <?=(0+$st['s1'])?>, <?=$u->stats['s1']-$st['s1'];?>, 3, 0, <?=$minlvl[1];?>,
'Ловкость', 's2', <?=(0+$st['s2'])?>, <?=$u->stats['s2']-$st['s2'];?>, 3, 0, <?=$minlvl[2];?>,
'Интуиция', 's3', <?=(0+$st['s3'])?>, <?=$u->stats['s3']-$st['s3'];?>, 3, 0, <?=$minlvl[3];?>,
'Выносливость', 's4', <?=(0+$st['s4'])?>, 0, <?=$vinos[$u->info['level']]?>, 0, <?=$minlvl[4];?>,
'Интеллект', 's5', <?=(0+$st['s5'])?>, <?=$u->stats['s5']-$st['s5'];?>, 0, 0, <?=$minlvl[5];?>,
'Мудрость', 's6', <?=(0+$st['s6'])?>, 0, 0, 0, <?=$minlvl[6];?>,
'Духовность', 's7',  <?=(0+$st['s7'])?>, 0, 0, 0, <?=$minlvl[7];?>,
'Воля', 's8', <?=(0+$st['s8'])?>, 0, 0, 0, <?=$minlvl[8];?>,
'Свобода Духа', 's9', <?=(0+$st['s9'])?>, 0, 0, 0, <?=$minlvl[9];?>,
'Божественность', 's10', <?=(0+$st['s10'])?>, 0, 0, 0, <?=$minlvl[10];?>
);
function getprstr(i) {
var ss= pr[i]+": "+(pr[i+2]+pr[i+3]+pr[i+5]);
if (pr[i+3] || pr[i+5]) {
ss+=" ("+pr[i+2];
if (pr[i+3]) {ss+=(pr[i+3]<0?"":"+")+pr[i+3]}
if (pr[i+5]) {
if (pr[i+5] > 0) {
ss+=" <font color=green>+"+pr[i+5];
} else {
ss+=" <font color=#8f0000>"+pr[i+5];
}
ss+="</font>";
}
ss+=")";
}
return ss;
}
var freepr = 0;
var freemoves = <?=$raspst;?>;
var movecost = 5;
function modpr(i, to) {
if (to == 1 && freepr==0) {
return;
}
if (to == -1 && pr[i+2] + pr[i+5] <= pr[i+4]) {
return;
}
pr[i+5] += to;
freepr -= to;
document.all["pr"+i].innerHTML = getprstr(i);
document.all["prfree"].innerHTML = freepr;
var moves = 0;
for (j=0; j<pr.length; j+=7) {
if (pr[j+5] > 0) {moves+=pr[j+5]};
}
movedonebutton.disabled=(freepr || moves==0?true:false);
document.all["prmoves"].innerHTML = moves+((freemoves < moves)?" за "+(movecost*(moves - freemoves))+" кр.":(moves?" / бесплатно":""));
}
function movedone() {
if (freepr) {
return;
}
var s="";
for (j=0; j<pr.length; j+=7) {
if (pr[j+5]) {
s+="&"+pr[j+1]+"="+pr[j+5];
};
}
if (!s) {return};
location="?movestat=0.<?=$code;?>&sd4=<?=$u->info['id']?>"+s;
}
var s="<TABLE>";
for (i=0; i<pr.length; i+=7) {
if (pr[i+2]!=-1 && mylvl>=pr[i+6]) {
s+="<TR><TD width=200 id=pr"+i+">"+getprstr(i) + "</TD>";
s+='<TD><img src=http://img.combats.com/i/minus.gif border=0 onclick="modpr('+i+',-1)" style="cursor: hand"> '
s+='<img src=http://img.combats.com/i/plus.gif  border=0 onclick="modpr('+i+',1)"  style="cursor: hand"></TR>';
}
}
s+="</TABLE>";
s+="<small>(осталось: <span id='prfree'>0</span>, распределений: <span id='prmoves'>0</span>)<BR>";
document.write(s);
</SCRIPT>
<input type=button onClick="movedone();" id='movedonebutton' value="Применить" disabled>
</TD>
<TD width=270 valign=top>
<?
$prs = '';
$sp = mysql_query('SELECT `a`.*,`b`.* FROM `eff_users` AS `a` LEFT JOIN `eff_main` AS `b` ON `b`.`id2` = `a`.`id_eff` WHERE ((`a`.`id_eff` >= 301 AND `a`.`id_eff` <= 304) OR (`a`.`id_eff` >= 321 AND `a`.`id_eff` <= 332)) AND `a`.`uid` = "'.$u->info['id'].'" AND `a`.`delete` = "0"');
$txtl = 'бесплатно';
if($c['znahar4'] == 0) {
	$txtl = ' за '.$pr[4].' кр.';
}
while($pl = mysql_fetch_array($sp)) {
	$prs .= '<img style="display:inline-block;vertical-align:bottom" title="'.$pl['mname'].''."\r\n".''.$pl['name'].'" height="20" src="http://img.xcombats.com/i/eff/'.$pl['img'].'"> <a href="main.php?unprist='.$pl['id'].'&rnd='.$code.'"> <small>вылечить '.$txtl.'</small> </a><br>';
}

if($prs == '') {
	$prs = '<BR><BR><BR><BR><small><center>У вас нет пристрастий</center></small>';
}
echo $prs;
?>
</TD>
<TD valign=top><BR>
Навыки владения оружием и магией (<?=test_skills()?>)<BR>
<form method=post><INPUT type=submit name='dropmastery' value='Сбросить <?echo $c['znahar1']==1? "бесплатно":"(".$pr[1]."кр.)"?>' onClick="return confirm('Вы действительно хотите сбросить умения?')"><HR style="border:0;border-bottom:1px solid grey"></form>
Особенности персонажа (<?=test_skills2()?>)<BR>
<form method=post><INPUT type=submit  name='dropperks' value='Сбросить <?echo $c['znahar2']==1? "бесплатно":"(".$pr[2]."кр.)"?>' onClick="return confirm('Вы действительно хотите сбросить особенности?')"><HR style="border:0;border-bottom:1px solid grey"></form>
Характеристики (<?=test_ability()?>/<?=(9+test_s5())?>)<BR>
<form method=post><INPUT type=submit name='dropstats' value='Сбросить <?echo $c['znahar3']==1? "бесплатно":"(".$pr[3]."кр.)"?>' onClick="return confirm('Вы действительно хотите сбросить все характеристики до минимального уровня?')"></form>
</TABLE>
<small>Каждые 7 дней после последнего использования комнаты знахаря вы получаете 1 бесплатное перераспределение, но не более 15<BR>
Исцеление пристрастия, cброс умений или особенностей стоят 5 перераспределений<BR>
Изменение Характеристик досрочно завершает некоторые эффекты<!--, например эффект свитка <B>Жажда Жизни</B>-->
</small>
<BR>
</TABLE>
<div>
<?//рейтинг?>
</div>
</BODY>
</HTML>
<?}?>