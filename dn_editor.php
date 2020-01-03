<?php
session_start();

function er($e)
{
	 global $c;
	 die('<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><meta http-equiv="Content-Language" content="ru"><TITLE>Произошла ошибка</TITLE></HEAD><BODY text="#FFFFFF"><p><font color=black>Произошла ошибка: <pre>'.$e.'</pre><b><p><a href="http://'.$c[0].'/">Назад</b></a><HR><p align="right">(c) <a href="http://'.$c[0].'/">'.$c[1].'</a></p></body></html>');
}

function GetRealIp()
{
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

define('IP',GetRealIp());
define('GAME',true);

include_once('_incl_data/__config.php');
include_once('_incl_data/class/__db_connect.php');
include_once('_incl_data/class/__user.php');

if($u->info['admin'] == 0) {
	die('<meta http-equiv="refresh" content="0; URL=http://xcombats.com/">');
}

if(isset($_GET['id'])) {
	$_POST['id'] = (int)$_GET['id'];
}

if(isset($_POST['id_dng'])) {
	$id = (int)$_POST['id_dng'];
	if($id > 0) {
		$test_id = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(!isset($test_id['id'])) {
			unset($test_id);
		}
	}else{
		$id = 0;
	}
}elseif(isset($_POST['id'])) {
	$id = (int)$_POST['id'];
	if($id > 0) {
		$test_id = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(!isset($test_id['id'])) {
			unset($test_id);
		}
	}else{
		$id = 0;
	}
}

	$u->info['psevdo_x'] = 0;
	$u->info['psevdo_y'] = 0;
	$u->info['psevdo_s'] = 1;
	
	if(isset($_POST['x'])) {
		$u->info['psevdo_x'] = (int)$_POST['x'];
		$u->info['psevdo_y'] = (int)$_POST['y'];
		$u->info['psevdo_s'] = (int)$_POST['s'];
	}
	
	if(isset($_GET['x'])) {
		$u->info['psevdo_x'] = (int)$_GET['x'];
		$u->info['psevdo_y'] = (int)$_GET['y'];
		$u->info['psevdo_s'] = (int)$_GET['s'];
	}
	
if(isset($_POST['saveObjPosition'])) {
	echo 'START#';
	$_POST['saveObjPosition'] = str_replace('obj_true_','',$_POST['saveObjPosition']);
	$_POST['saveObjPosition'] = floor((int)$_POST['saveObjPosition']);
	$obj = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_obj` WHERE `id` = "'.mysql_real_escape_string($_POST['saveObjPosition']).'" LIMIT 1'));
	if(isset($obj['id'])) {
		$cor = array(0,0);
		$data = explode(',',ltrim(rtrim($obj['date'],'\}'),'\{'));
		$da = array();
		$i = 0;
		while($i < count($data)) {
			$data[$i] = explode(':',$data[$i]);
			$da[$data[$i][0]] = $data[$i][1];
			echo '['.$data[$i][0].' = '.$data[$i][1].']';
			$i++;
		}
		$obj['top'] = 0;
		$obj['left'] = 0;
		$rs = (int)$_POST['rz'];
		$da['rt'.$rs] = 0+$_POST['objy'];
		$da['rl'.$rs] = 0+$_POST['objx'];
		
		$dak = array_keys($da);
		$dav = $da;
		$da = '';
		$i = 0;
		while($i < count($dak)) {
			$da .= $dak[$i].':'.$dav[$dak[$i]].',';
			$i++;
		}
		$da = rtrim($da,',');
		$da = '{'.$da.'}';
		$upd = mysql_query('UPDATE `dungeon_obj` SET `top` = "'.mysql_real_escape_string($obj['top']).'", `left` = "'.mysql_real_escape_string($obj['left']).'", `date` = "'.mysql_real_escape_string($da).'" WHERE `id` = "'.$obj['id'].'" LIMIT 1');
		if($upd) {
			echo 'Данные успешно сохранены '.$da;
		}else{
			echo '#!Ошибка';
		}
	}else{
			echo '#Ошибка';
	}
	die('#END');
}elseif(isset($_POST['ore_id'])) {
	$id = (int)$_POST['id_dng'];
	echo '[START# ';
	$_POST['ore_id'] = floor((int)$_POST['ore_id']);
	$obj = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_obj` WHERE `id` = "'.mysql_real_escape_string($_POST['ore_id']).'" LIMIT 1'));
	$_POST['ore_name'] = iconv("UTF-8", "cp1251",$_POST['ore_name']);
	if(isset($obj['id'])) {
		mysql_query('UPDATE `dungeon_obj` SET
				`name` = "'.mysql_real_escape_string($_POST['ore_name']).'",
				`img` = "'.mysql_real_escape_string($_POST['ore_img']).'",
				`w` = "'.mysql_real_escape_string($_POST['ore_w']).'",
				`h` = "'.mysql_real_escape_string($_POST['ore_h']).'",
				`x` = "'.mysql_real_escape_string($_POST['ore_x']).'",
				`y` = "'.mysql_real_escape_string($_POST['ore_y']).'",
				
				`type2` = "'.mysql_real_escape_string($_POST['ore_type2']).'",
				`s` = "'.mysql_real_escape_string($_POST['ore_s']).'",
				`s2` = "'.mysql_real_escape_string($_POST['ore_s2']).'",
				`os1` = "'.mysql_real_escape_string($_POST['ore_os1']).'",	
				`os2` = "'.mysql_real_escape_string($_POST['ore_os2']).'",
				`os3` = "'.mysql_real_escape_string($_POST['ore_os3']).'",
				`os4` = "'.mysql_real_escape_string($_POST['ore_os4']).'",
				`fix_x_y` = "'.mysql_real_escape_string($_POST['ore_fix_x_y']).'",
				
				`type` = "'.mysql_real_escape_string($_POST['ore_type']).'" WHERE `id` = "'.$obj['id'].'" LIMIT 1		
		');
		echo 'Данные сохранены';
	}else{
		//создаем обьект
		/*
	$('#ore_id').val(op[0]);
	$('#ore_img').val(op[4]);
	$('#ore_name').val(op[1]);
	$('#ore_x').val(op['x']);
	$('#ore_y').val(op['y']);
	$('#ore_t').val(top.obi['t']);
	$('#ore_l').val(top.obi['l']);
	$('#ore_w').val(op[7]);
	$('#ore_h').val(op[8]);
		*/
		if(isset($_POST['ore_img'])) {
			echo 'Объект создан';
			if(mysql_query('INSERT INTO `dungeon_obj` (`for_dn`,`name`,`img`,`w`,`h`,`x`,`y`,`type2`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`fix_x_y`,`type`,`date`) VALUES (
				"'.mysql_real_escape_string($id).'",
				"'.mysql_real_escape_string($_POST['ore_name']).'",
				"'.mysql_real_escape_string($_POST['ore_img']).'",
				"'.mysql_real_escape_string($_POST['ore_w']).'",
				"'.mysql_real_escape_string($_POST['ore_h']).'",
				"'.mysql_real_escape_string($_POST['ore_x']).'",
				"'.mysql_real_escape_string($_POST['ore_y']).'",				
				"'.mysql_real_escape_string($_POST['ore_type2']).'",
				"'.mysql_real_escape_string($_POST['ore_s']).'",
				"'.mysql_real_escape_string($_POST['ore_s2']).'",
				"'.mysql_real_escape_string($_POST['ore_os1']).'",	
				"'.mysql_real_escape_string($_POST['ore_os2']).'",
				"'.mysql_real_escape_string($_POST['ore_os3']).'",
				"'.mysql_real_escape_string($_POST['ore_os4']).'",
				"'.mysql_real_escape_string($_POST['ore_fix_x_y']).'",
				"'.mysql_real_escape_string($_POST['ore_type']).'",				
				"{use:\'takeit\'}"
				)')) {
					echo '+';
				}else{
					echo '-';
				}
		}
	}
	die(' #END]');
}elseif(isset($_POST['ore_delete_id'])) {
	mysql_query('UPDATE `dungeon_obj` SET `delete` = "'.$u->info['id'].'",`for_dn` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($_POST['ore_delete_id']).'" LIMIT 1');
	die('[START# Объект удален #END]');
}

if($id > 0) {
	//работа с пещерой
	$pd = array(
	1 =>0,
	2 =>0,
	3 =>0,
	4 =>0,
	5 =>0,
	6 =>0,
	7 =>0,
	8 =>0,
	9 =>0, //передняя стенка, в 2-х шагах
	10=>0,
	11=>0,
	12=>0,
	13=>0,
	14=>0,
	15=>0,
	16=>0,
	17=>0,
	18=>0,
	19=>0,
	20=>0,
	21=>0,
	22=>0,
	23=>0,
	/* Растояние: 1 шаг */
	24=>0, //стена прямо слева от персонажа (1)
	25=>0, //стена прямо справа от персонажа (1)
	26=>0, //стена прямо перед персонажем (1)
	27=>0, //стена слева от персонажа (1)
	28=>0  //стена справа от персонажа (1)
	);
		
	include('dn_editor_class.php');
	
	$d->point = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$d->info['id2'].'" AND `x` = "'.$u->info['x'].'" AND `y` ="'.$u->info['y'].'" LIMIT 1'));
	if(!isset($d->point['id']))
	{
		$d->point['css'] = 'css';	
	} 
}

if(!isset($_GET['look'])) {
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Визуальный редактор Лабиринтов &copy; xcombats.com</title>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jqueryrotate.js"></script>
<script type="text/javascript" src="js/jquery.zclip.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/title.js"></script>

<link href="http://img.xcombats.com/css/dungeon_<? echo $d->point['css']; ?>.css" rel="stylesheet" type="text/css">

<script>


//редактирование обьектов

var obi = {'obj':0,
	'w':0,
	'h':0,
	't':0,
	'l':0,
	'position':0,
	'val':0};

function editObjResize(objs,vl,iii) {
	$(top.obi['obj']).css({'background-color':''});	
	top.obi = {
		'obj':objs,
		'id':$(top.obi['obj']).attr('id'),
		'w':$(objs).width(),
		'h':$(objs).height(),
		't':$(objs).css('top'),
		'l':$(objs).css('left'),
		'position':0,
		'val':0
	};

	$('#eo').css({
		'display':'',
		'position':'absolute',
		'top':$(objs).css('top'),
		'left':$(objs).css('left'),
		'width':$(objs).width()+'px',
		'height':$(objs).height()+'px'
	});
	resizeObj1(event,iii);
	//$('#eo').bind('mousedown',function(event){ moveObj1(event); });
	//$(top.obi['obj']).css({'background-color':'red'});	
}

function resizeObj1(e,iii) {
	$('#object_resize_editor').css({'display':''});
	$('#Dungeon2').css({'display':''});
	$('#Dungeon2').bind('mousemove',function(event){ resizeObj(event); });
	$('#Dungeon2').bind('click',function(event){ moveObjEnd2(); });
	
	//ore_save_obj
	var op = objs[iii];
	$('#ore_id').val(op[0]);
	$('#ore_img').val(op[4]);
	$('#ore_name').val(op[1]);
	$('#ore_x').val(op['x']);
	$('#ore_y').val(op['y']);
	$('#ore_t').val(top.obi['t']);
	$('#ore_l').val(top.obi['l']);
	$('#ore_w').val(op[7]);
	$('#ore_h').val(op[8]);
	$('#ore_w2').val(op[7]);
	$('#ore_h2').val(op[8]);
		
	$('#ore_type').val(op[12]);
	$('#ore_type2').val(op[13]);
	$('#ore_s').val(op[14]);
	$('#ore_s2').val(op[15]);
	$('#ore_os1').val(op[16]);
	$('#ore_os2').val(op[17]);
	$('#ore_os3').val(op[18]);
	$('#ore_os4').val(op[19]);
	$('#ore_fix_x_y').val(op[20]);
	saveCord();
	
	var npos = $('#'+$(top.obi['obj']).attr('id')+'_2').offset();
	
	$('#'+$(top.obi['obj']).attr('id')+'_2').css({'background-color':'blue','opacity':'0.25'});
}

function ore_save_obj() {
		$.post('dn_editor.php?look=1',{
			ore_id:$('#ore_id').val(),
			ore_img:$('#ore_img').val(),
			ore_name:$('#ore_name').val(),
			ore_x:$('#ore_x').val(),
			ore_y:$('#ore_y').val(),
			ore_t:$('#ore_t').val(),
			ore_l:$('#ore_l').val(),
			ore_w:$('#ore_w').val(),
			ore_h:$('#ore_h').val(),
			
			ore_type:$('#ore_type').val(),
			ore_type2:$('#ore_type2').val(),
			ore_s:$('#ore_s').val(),
			ore_s2:$('#ore_s2').val(),
			ore_os1:$('#ore_os1').val(),
			ore_os2:$('#ore_os2').val(),
			ore_os3:$('#ore_os3').val(),
			ore_os4:$('#ore_os4').val(),
			ore_fix_x_y:$('#ore_fix_x_y').val(),
			
			id_dng:<?=(0+$_POST['id_dng'])?>
		},function(data){ $('#textAjaxGo').html('['+data+']'); top.goPix(top.sel_id,top.sel_x,top.sel_y); });
}

function ore_delete_obj() {
		$.post('dn_editor.php?look=1',{
			ore_delete_id:$('#ore_id').val()
		},function(data){ $('#textAjaxGo').html('['+data+']'); top.goPix(top.sel_id,top.sel_x,top.sel_y); });
}

function moveObjEnd2() {
	$('#Dungeon2').unbind('mousemove');
	$('#Dungeon2').unbind('click');
	
	$('#'+$(top.obi['obj']).attr('id')+'_2').css({'background-color':'','opacity':'1.0'});
	//$(top.obi['obj']).css({'top':(y+2)+'px','left':x+'px'});
	$('#Dungeon2').css({'display':'none'});

	$('#eo').css({
		'display':'none'
	});
	$('#object_resize_editor').css({
		'display':'none'
	});

	var obi = {'obj':0,
		'w':0,
		'h':0,
		't':0,
		'l':0,
		'position':0,
		'val':0};
}

function editObj(objs,vl) {
	$(top.obi['obj']).css({'background-color':''});	
	top.obi = {
		'obj':objs,
		'id':$(top.obi['obj']).attr('id'),
		'w':$(objs).width(),
		'h':$(objs).height(),
		't':$(objs).css('top'),
		'l':$(objs).css('left'),
		'position':0,
		'val':0
	};

	$('#eo').css({
		'display':'',
		'position':'absolute',
		'top':$(objs).css('top'),
		'left':$(objs).css('left'),
		'width':$(objs).width()+'px',
		'height':$(objs).height()+'px'
	});
	moveObj1(event);
	//$('#eo').bind('mousedown',function(event){ moveObj1(event); });
	//$(top.obi['obj']).css({'background-color':'red'});	
}

function moveObj1(e) {
	$('#Dungeon2').css({'display':''});
	$('#Dungeon2').bind('mousemove',function(event){ moveObj(event); });
	$('#Dungeon2').bind('click',function(event){ moveObjEnd(event); });
	$('#'+$(top.obi['obj']).attr('id')+'_2').css({'background-color':'red','opacity':'0.21'});
}

function moveObj(e) {
	if (!e) e = window.event;
	var x = e.pageX, y = e.pageY;
	var xm = parseFloat($('#Dungeon2').offset().left), ym = parseFloat($('#Dungeon2').offset().top);
		Math.round(x = x-xm-($(top.obi['obj']).width()/2));
		Math.round(y = y-ym-($(top.obi['obj']).height()/2));
	
	
	$('#'+$(top.obi['obj']).attr('id')+'_2').css({'top':y+'px','left':x+'px'});
	
	$(top.obi['obj']).css({'top':(y+2)+'px','left':x+'px'});
	$('#eo').css({'top':y+'px','left':x+'px'});
}

function moveObjEnd(e) {
	$('#Dungeon2').unbind('mousemove');
	$('#Dungeon2').unbind('click');
	if (!e) e = window.event;
	var x = e.pageX, y = e.pageY;
	var xm = parseFloat($('#Dungeon2').offset().left), ym = parseFloat($('#Dungeon2').offset().top);
		x = Math.round(x-xm-($(top.obi['obj']).width()/2));
		y = Math.round(y-ym-($(top.obi['obj']).height()/2));
	
	$('#'+$(top.obi['obj']).attr('id')+'_2').css({'background-color':'','opacity':'1.0'});
	//$(top.obi['obj']).css({'top':(y+2)+'px','left':x+'px'});
	
	$('#eo').css({'top':y+'px','left':x+'px'});
	$('#Dungeon2').css({'display':'none'});


	//Сохраняем позицию обьекта
	$.post('dn_editor.php',{saveObjPosition:$(top.obi['obj']).attr('id'),rz:$(top.obi['obj']).attr('rz'),objx:x,objy:y,x:top.sel_x,y:top.sel_y,s:top.sel_s},function(data){ $('#textAjaxGo').html('['+data+']'); });

	$('#eo').css({
		'display':'none'
	});

	var obi = {'obj':0,
		'w':0,
		'h':0,
		't':0,
		'l':0,
		'position':0,
		'val':0};
}


var sel_id = 0 , sel_x = 0 , sel_y = 0 , sel_s = 1;
function goPix(id,x,y) {
	
	$('#dngseemap').html('');
	
	if(top.sel_s < 1) {
		top.sel_s = 4;
	}
	
	if(top.sel_s > 4) {
		top.sel_s = 1;
	}
	
	if(top.sel_id > 0) {
		$('#px_'+top.sel_id).css({'background-color':''});
		$('#px_'+top.sel_id).attr({'className':'cq'});
	}
	
	$('#px_'+id).css({'background-color':'#ffd5d5'});
	
	top.obi = {'obj':0,
	'w':0,
	'h':0,
	't':0,
	'l':0,
	'position':0,
	'val':0};
	
	top.sel_id = id;
	top.sel_x = x;
	top.sel_y = y;
	
	$('#fm1').attr("src","dn_editor_bots.php?id_dn=<?=$id?>&xx="+x+"&&yy="+y+"");
	$.post('dn_editor.php?look=1',{id:<?=$id?>,id_p:id,x:x,y:y,s:top.sel_s},function(data){ $('#dngseemap').html(data); });
}

function dialogMenu(id,atk,talk,look,take,e)
{
	var d = document.getElementById('deMenu');
	if(d!=undefined)
	{
		if(e == undefined)
    	{
       		e = window.e;
			
    	}
		d.innerHTML = '';
		var t = '';
		if(talk>0)
		{
			t += '<a href="main.php?talk='+talk+'&rnd=0.28626200682069150">Диалог</a><br>';
		}
		if(atk==1)
		{
			t += '<a href="main.php?atack='+id+'&rnd=0.28626200682069150">Напасть</a><br>';
		}
		if(look==1)
		{
			t += 'Просмотр<br>';
		}
		if(take==1)
		{
			t += 'Поднять<br>';
		}
		d.innerHTML = t+'<small style="float:right;"><button style="border: solid 1pt #B0B0B0; font-family: MS Sans Serif; font-size: 10px; color: #191970; MARGIN-BOTTOM: 2px; MARGIN-TOP: 1px;" type="button" onClick="exitDem();">x</button></center>';
		d.style.display = '';
		if(e.x == undefined)
		{
			e.x = e.clientX;
			e.y = e.clientY;
		}
		d.style.top = e.y+'px';				
		if(e.x>320)
		{
			d.style.right = (document.body.offsetWidth-e.x)+'px';
		}else{
			d.style.right = (-e.x+540)+'px';
		}
	}
}

function exitDem()
{
	var d = document.getElementById('deMenu');
	if(d!=undefined)
	{
		d.innerHTML = '';
		d.style.display = 'none';
		d.style.top = '0px';
		d.style.right = '0px';
	}
}

<? } if($id > 0) { ?>
<? if(isset($_GET['look'])){ echo '<script>'; } if(!isset($_GET['look'])){ echo 'var'; }?> objects = {};
//i:{id,login,mapPoint,sex,obraz,type,users_p},
<? if(!isset($_GET['look'])){ echo 'var'; }?> users   = {};
<? if(!isset($_GET['look'])){ echo 'var'; }?> objs   = {<? echo $d->genObjects(); ?>};
<? if(!isset($_GET['look'])){ echo 'var'; }?> items   = {};
<? if(!isset($_GET['look'])){ echo 'var'; }?> actions = {};
<? if(!isset($_GET['look'])){ echo 'var'; }?> dsee = <? echo 0+$d->gs; ?>;
<? if(!isset($_GET['look'])){ echo 'var'; }?> mapp = {1:'0_0f',2:'0_0f',3:'0_0f',4:'1_1f',5:'1_1f',6:'1_1f'
		   ,7:'2_1f',8:'2_1f',9:'2_1f'
		   ,11:'3_1l',12:'3_1f',13:'3_1r'}
<? if(!isset($_GET['look'])){ echo 'var'; }?> zmap = {5:894,8:0,12:0}
<? if(!isset($_GET['look'])){ echo 'var'; }?> zfloor0 = {1:'',2:'',3:'',4:'',5:''};
<? } if(isset($_GET['look'])){ echo '</script>'; }else{ ?>
function genMap(){
	var i = 0, m = false, mz = false;
	while(i<users['count'])
	{
		if(users[i]!=undefined)
		{
			mz = mapp[users[i][2]];		
			if(document.getElementById(mz)!=undefined)
			{
				m = document.getElementById(mz);
				m.innerHTML = addUser(users[i],mz)+m.innerHTML;
			}
		}
		i++;
	}
	 var i = 0, m = false, mz = false;
	 while(i<objs['count']) {
		  if(objs[i]!=undefined){
			   mz = mapp[objs[i][2]];	
			   if(objs[i][5]==dsee && (objs[i][2]==5 || objs[i][2]==2 || objs[i][2]==8 || objs[i][2]==12 || objs[i][2]==15)) {
					mz = mapp[objs[i][2]-3]; 
			   }
			   if(document.getElementById(mz)!=undefined){
					m = document.getElementById(mz);
					m.innerHTML = addObj(objs[i],mz,i)+m.innerHTML;  
			   }
		  }
		  i++;
	 }
	var i = 5;
	while(i>=1)
	{
		if(zfloor0[i]!='')
		{
			document.getElementById('Floor0').innerHTML += zfloor0[i];
		}
		i--;
	}
}
var dConfig={
	2:{
		1:{'top':50,'left':140,'w':80,'h':147},
		2:{'top':45,'left':87,'w':80,'h':147},
		3:{'top':45,'left':192,'w':80,'h':147},
		4:{'top':49,'left':165,'w':80,'h':147},
		5:{'top':49,'left':105,'w':80,'h':147},
		6:{'top':53,'left':140,'w':80,'h':147},
		7:{'top':53,'left':87,'w':80,'h':147},
		8:{'top':53,'left':190,'w':80,'h':147}
	},
	3:{
		1:{'top':60,'left':152,'w':53,'h':97},
		2:{'top':58,'left':110,'w':53,'h':97},
		3:{'top':58,'left':188,'w':53,'h':97},
		4:{'top':61,'left':168,'w':53,'h':97},
		5:{'top':61,'left':128,'w':53,'h':97},
		6:{'top':62,'left':153,'w':53,'h':97},
		7:{'top':62,'left':113,'w':53,'h':97},
		8:{'top':62,'left':193,'w':53,'h':97}},
	4:{
		1:{'top':70,'left':158,'w':35,'h':64},
		2:{'top':68,'left':125,'w':35,'h':64},
		3:{'top':68,'left':193,'w':35,'h':64},
		4:{'top':71,'left':173,'w':35,'h':64},
		5:{'top':71,'left':137,'w':35,'h':64},
		6:{'top':73,'left':158,'w':35,'h':64},
		7:{'top':73,'left':129,'w':35,'h':64},
		8:{'top':73,'left':193,'w':35,'h':64}
	}
}
var dConfigObj = {
	1: {
		    0: {
				'top':65,
				'left':110,
				'w':1,
				'h':1	
			}
	}
	,2: {
		    0: {
				'top':65,
				'left':110,
				'w':0.65,
				'h':0.65	
			}
	},
	3: {
		    0: {
				'top':65,
				'left':110,
				'w':0.48,
				'h':0.48
			}
	},
	4: {
		    0: {
				'top':65,
				'left':110,
				'w':0.35,
				'h':0.35
			}
	}
}
var prob = {
	0: {
		1:1,
		2:0.25,
		3:-0.10,
		4:-0.38
	}, 
	1: {
		1:0.90,
		2:0.50,
		3:0.23,
		4:0.05
	}
};

function addObj(v,mz,iii){
	var r = '';
	//355*245 window
	var rz = 0; //растояние до пользователя
	if(v[2]>=1 && v[2]<=3) { rz = 1; }
	if(v[2]>=4 && v[2]<=6) { rz = 2; }
	if(v[2]>=7 && v[2]<=9) { rz = 3; }
	if(v[2]>=10 && v[2]<=14) { rz = 4; }
	if(v[2]>=15 && v[2]<=19) { rz = 5; }	
	if(v[5]==dsee) { rz -= 1; }
	if(dConfigObj[rz]!=undefined && dConfigObj[rz][v[6]]!=undefined) {
		new_w = v[7]*dConfigObj[rz][v[6]]['w'];
		new_h = v[8]*dConfigObj[rz][v[6]]['h'];
		new_left = dConfigObj[rz][v[6]]['left']-Math.round((v[7]*prob[0][rz])/4);
		new_top = dConfigObj[rz][v[6]]['top']-Math.round((v[8]*prob[1][rz])/4);	
		if(v[2]==6) { new_left += 195; new_top -= 5; }
		if(v[2]==4) { new_left -= 195; new_top -= 5; }
		if( v[2]==9) { new_left -= 140; new_top -= 2; }
		if( v[2]==7){ new_left += 140; new_top -= 2; }
		if( v[2]==13){ new_left += 100; new_top -= 1; }
		if( v[2]==11){ new_left -= 100; new_top -= 0; }
		if( v[9]!=0){ new_left += Math.round(new_left/(100+(rz-1)*10)*v[9]+rz*0.25); }
		if( v[10]!=0){ new_top += Math.round(new_h/2+new_top/(100+(rz-1)*50)*v[10]-rz*3.3); }
		if( rz == 4 ){	new_top += 3; }
		if( v[11] != 0 ) {
			if(v[11]['t'+rz]!=undefined) { new_top += v[11]['t'+rz]; }
			if(v[11]['l'+rz]!=undefined) { new_left += v[11]['l'+rz]; }
			if(v[11]['w'+rz]!=undefined) { new_w += v[11]['w'+rz]; }
			if(v[11]['h'+rz]!=undefined) { new_h += v[11]['h'+rz]; }
			if(v[11]['rt'+rz]!=undefined) { new_top = v[11]['rt'+rz]; }
			if(v[11]['rl'+rz]!=undefined) { new_left = v[11]['rl'+rz]; }
		}
		////i:{0:id,1:name,2:mapPoint,3:action,4:img,5:type},
		if( rz >= 1 && rz <= 2 ) {
			actionNow = '';
			if( v[11]['use'] != undefined ) {
				if( v[11]['use'] == 'exit' ) {
					actionNow = 'alert(\'Выход из подземелья\');';
				} else if( v[11]['use'] == 'takeit' ) {
					actionNow = 'location=\'main.php?take_obj='+v[0]+'&rnd='+0.28626200682069150+'\';';
				}
			}
			zfloor0[rz] = '<img id="obj_true_'+v[0]+'_2" rz="'+rz+'" oncontextmenu="top.editObjResize(\'#obj_true_'+v[0]+'\',\''+v+'\',\''+iii+'\'); return false" title="'+v[1]+'" onClick="top.editObj(\'#obj_true_'+v[0]+'\',\''+v+'\')" src="http://img.xcombats.com/1x1.gif" style="cursor:pointer;position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />'+zfloor0[rz];			
		} else {
			zfloor0[rz] = '<img id="obj_true_'+v[0]+'_2" rz="'+rz+'" oncontextmenu="top.editObjResize(\'#obj_true_'+v[0]+'\',\''+v+'\',\''+iii+'\'); return false" title="'+v[1]+'" onClick="top.editObj(\'#obj_true_'+v[0]+'\',\''+v+'\')" src="http://img.xcombats.com/1x1.gif" style="position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />'+zfloor0[rz];
		}
		r = '<img id="obj_true_'+v[0]+'" rz="'+rz+'" oncontextmenu="top.editObjResize(\'#obj_true_'+v[0]+'\',\''+v[1]+'\',\''+iii+'\'); return false" onClick="top.editObj(\'#obj_true_'+v[0]+'\',\''+v[1]+'\')" title="obj" src="http://img.xcombats.com/i/sprites/'+v[4]+'" class="dObj" style="position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />';
	}
	return r;
}
function addUser(v,mz) {
	var r = '';
	var rz = 0; //растояние до пользователя
	if(v[2]>=1 && v[2]<=3){ rz = 1; }
	if(v[2]>=4 && v[2]<=6){ rz = 2; }
	if(v[2]>=7 && v[2]<=9){ rz = 3; }
	if(v[2]>=10 && v[2]<=14){ rz = 4; }
	if(v[2]>=15 && v[2]<=19){ rz = 5; }	
	if(dConfig[rz]!=undefined && dConfig[rz][v[6]]!=undefined) {
		new_w = dConfig[rz][v[6]]['w'];
		new_h = dConfig[rz][v[6]]['h'];
		new_left = dConfig[rz][v[6]]['left'];
		new_top = dConfig[rz][v[6]]['top'];	
		if(v[2]==6) { new_left += 215; new_top -= 5; }
		if(v[2]==4) { new_left -= 215; new_top -= 5; }
		if(v[2]==9) { new_left -= 155; new_top -= 2; }
		if(v[2]==7) { new_left += 155; new_top -= 2; }
		if(v[2]==13) { new_left += 115; new_top -= 1; }
		if(v[2]==11) { new_left -= 115; new_top -= 1; }
		if(v[2]>=11 && v[2]<=13) { new_top += 5; }
		if(rz>=1 && rz<=2) {
			action = '';
			if( v[5]=='bot' || 0 > 0 ) { action = 'dialogMenu('+v[0]+',1,'+v[7]+',0,0,event);'; }
			zfloor0[rz] += '<img title="'+v[1]+'" onClick="'+action+'" src="http://img.xcombats.com/1x1.gif" style="cursor:pointer;position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />';			
		} else {
			zfloor0[rz] += '<img title="'+v[1]+'" src="http://img.xcombats.com/1x1.gif" style="position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />';
		}
		r = '<img title="user" src="http://img.xcombats.com/chars/'+v[3]+'/'+v[4]+'.png" class="dUser" style="position:absolute;top:'+new_top+'px;left:'+new_left+'px;width:'+new_w+'px;height:'+new_h+'px;" />';
	}
	return r;
}
speedLoc  = 0;
sLoc1 = 0;
sLoc2 = 0;
tgo  = 0;
tgol = 0;
gotoup777 = 0;
gotext777 = '';
function cancelgoto() {
	document.getElementById('gotext777').innerHTML = '';
	gotoup777 = 0;
	gotext777 = '';
}
function goToLoca(id,ttl) {
	if(tgo < 1) {
		location = 'main.php?go='+id;
	}else{
		gotoup777 = id;
		gotext777 = ttl;
	}
}
function locGoLineDng()
{
	var line = document.getElementById('pline1');
	if(line!=undefined)
	{
		
		prc = 100-Math.floor(tgo/tgol*100);
		sLoc1 = 108/100*prc;
		if(sLoc1<1)
		{
			sLoc1 = 1;
		}

		if(sLoc1>108)
		{
			sLoc1 = 108;
		}

		line.style.width = sLoc1+'px';
		if(tgo>0)
		{
			tgo -= 1;
			setTimeout('locGoLineDng()',100);
		}else{
			if(gotoup777 > 0) {
				location = "main.php?go="+gotoup777;
			}
		}
		if(gotoup777 > 0 && gotext777 != '' && document.getElementById('gotext777').innerHTML != 'Вы перейдете <b>'+gotext777+'</b> (<a href="javascript:void(0)" onclick="cancelgoto()">отмена</a>)') {
			//document.getElementById('gotext777').style.display = 'block';
			document.getElementById('gotext777').innerHTML = 'Вы перейдете <b>'+gotext777+'</b> (<a href="javascript:void(0)" onclick="cancelgoto()">отмена</a>)';
		}else if(document.getElementById('gotext777').innerHTML != '' && gotoup777 == 0 && gotext777 == '') {
			//document.getElementById('gotext777').style.display = 'none';
			document.getElementById('gotext777').innerHTML = '';
		}
	}
}


</script>

<style>
.cq { background-color:#F2F2F2; }
.cq:hover { background-color:#d6e4c6; }
.hintDm {
	position:absolute;
	background-color:#E4E4E4;
	padding:5px;
	border:1px solid #999;	
	z-index:1;
	width:70px;
}
.dUser { 
	max-height:220px;
	max-width:120px;
	min-width:30px;
	min-height:55px;
	border:		0px solid;
	padding:	0px;
	margin:		0px;
}
*[onselectstart="return false"] {  
	-moz-user-select: none;  
	-o-user-select:none;  
	-khtml-user-select: none;  
	-webkit-user-select: none;  
	-ms-user-select: none;  
	user-select: none; 
}
.dObj {
	border:		0px solid;
	padding:	0px;
	margin:		0px;
}
.test1 {
	text-align: right;
}
#pline1 {
	background-image:url(http://img.xcombats.com/wait3.gif);
	height:9px;
	z-index:1000;
}

</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td height="40">
    <div style="padding:10px;">
        <form method="post" action="?<?=time()?>">
        <small style="margin:5px; background:#999999;"><span style="color:#CCCCCC">&nbsp; Пещера# <b><?=$id?></b> &nbsp;</span> </small>
        <select name="id_dng" id="id_dng">
        <option value="0">Выберите номер пещеры</option>
        <? 	$i = 0;
            while($i <= 200) {
            $sp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$i.'" LIMIT 1'));
            if(isset($sp['id'])) {
        ?>
        <option <? if($id == $i){ echo 'selected'; } ?> value="<?=$i?>"><? if($id == $i){ echo '*'.$i.'*'; }else{ echo $i; } ?></option>
        <? 
            }
            $i++;
        }
        ?>
      </select>
      <button type="submit">Отправить</button>
      </form>
      <div style="height:23px;" id="textAjaxGo"></div>
    </div>
    </td>
  </tr>
  <tr>
    <td height="300" align="center" bgcolor="#F2F2F2">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="500" height="600" align="center" valign="top" id="dngseemap">
		<? } ?>
        <a href="dn_editor_bots.php?id_dn=<?=$id?>&xx=<?=$u->info['psevdo_x']?>&&yy=<?=$u->info['psevdo_y']?>" target="fm1">Редактировать ботов</a>
        <div>
            <div style="float:left"><a href="javascript:void(0)" onClick="top.sel_s++;top.goPix(top.sel_id,top.sel_x,top.sel_y);">Налево</a></div>
            <div style="float:right"><a href="javascript:void(0)" onClick="top.sel_s--;top.goPix(top.sel_id,top.sel_x,top.sel_y);">Направо</a></div>
        </div>
        <!-- ---------------------------------------------------- -->
              
            <div id="Dungeon" class="Dungeon" align="center" style="width:352px;height:240px;padding:0px;margin:10px;">
            <div id="Dungeon2" onselectstart="return false" style="z-index:10005000; display:none; position:absolute; width: 352px; height: 240px; left: 0px; top: 0px;"></div>
            <div id="eo" onselectstart="return false" style="display:none;z-index:10000000"></div>
                <!-- / MAP \ -->
              <div id="Floor0" class="Floor0">
                  <div class="Floor1">
                    <div class="<? if($pd[1]==1){ echo 'LeftSide4_1'; } ?>">
                      <div class="<? if($pd[2]==1){ echo 'RightSide4_1'; } ?>">
                        <div id="4_0r" class="<? if($pd[3]==1){ echo 'RightSide4_0'; } ?>">
                          <div id="4_0l" class="<? if($pd[4]==1){ echo 'LeftSide4_0'; } ?>">
                            <div id="3_2l" class="<? if($pd[5]==1){ echo 'LeftFront3_2'; } ?>">
                              <div id="3_2r" class="<? if($pd[6]==1){ echo 'RightFront3_2'; } ?>">
                                <div class="<? if($pd[7]==1){ echo 'LeftFront3_1'; } ?>">
                                  <div class="<? if($pd[8]==1){ echo 'RightFront3_1'; } ?>">
                                    <div id="3_1f" class="<? if($pd[9]==1){ echo 'LeftFront3_0'; } ?>">
                                      <div id="3_1l" class="<? if($pd[10]==1){ echo 'LeftFront3_1'; } ?>">
                                        <div id="3_1r" class="<? if($pd[11]==1){ echo 'RightFront3_1'; } ?>">
                                          <div class="<? if($pd[12]==1){ echo 'LeftSide3_0'; } ?>">
                                            <div id="3_0l" class="<? if($pd[13]==1){ echo 'RightSide3_0'; } ?>">
                                              <div id="2_1l" class="<? if($pd[14]==1){ echo 'LeftFront2_1'; } ?>">
                                                <div id="2_1r" class="<? if($pd[15]==1){ echo 'RightFront2_1'; } ?>">
                                                  <div id="2_1f" class="<? if($pd[16]==1){ echo 'LeftFront2_0'; } ?>">
                                                    <div class="<? if($pd[17]==1){ echo 'LeftSide2_0'; } ?>">
                                                      <div id="2_0l" class="<? if($pd[18]==1){ echo 'RightSide2_0'; } ?>">
                                                        <div id="1_1l" class="<? if($pd[19]==1){ echo 'LeftFront1_1'; } ?>">
                                                          <div id="1_1r" class="<? if($pd[20]==1){ echo 'RightFront1_1'; } ?>">
                                                            <div id="1_1f" class="<? if($pd[21]==1){ echo 'LeftFront1_0'; } ?>">
                                                              <div class="<? if($pd[22]==1){ echo 'LeftSide1_0'; } ?>">
                                                                <div id="1_0l" class="<? if($pd[23]==1){ echo 'RightSide1_0'; } ?>">
                                                                  <div sid="0_1l" class="<? if($pd[24]==1){ echo 'LeftFront0_1'; } ?>">
                                                                    <div id="0_1r" class="<? if($pd[25]==1){ echo 'RightFront0_1'; } ?>">
                                                                      <div id="0_0f" class="<? if($pd[26]==1){ echo 'LeftFront0_0'; } ?>">
                                                                        <div class="<? if($pd[27]==1){ echo 'LeftSide0_0'; } ?>">
                                                                          <div id="0_0l" class="<? if($pd[28]==1){ echo 'RightSide0_0'; } ?>">
                                                                            <? if($u->info['admin']==0){ ?>
                                                                            <div><img src="http://img.combats.ru/i/1x1.gif" usemap="#ObjectsMap" border="0" /></div>
                                                                          </div>
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
                <!-- / MAP \ -->
                <span class="<? if($pd[28]==1){ echo 'RightSide0_0'; } ?>">
                <? } ?>
                </span></div>
            </div>
			<script>
            genMap();
            </script>
        <!-- ---------------------------------------------------- -->
        <? if(!isset($_GET['look'])) { ?>
        </td>
        <td valign="top">
        <iframe id="fm1" name="fm1" frameborder="0" src="" width="500px" height="500px"></iframe>
        <script>
		var audW = 0,audH = 0;
		function autoDext(id) {
			if(id == 0) {
				var aW = 0,aW = 0, aR = 0;
				aW = parseInt(top.audW);
				mW = parseInt($('#ore_w2').val());			
				aR = aW / 100;
				aR = (mW / aR - 100);
				var aR2 = parseInt(audH) / 100;
				aR = Math.ceil(parseInt(audH) + (aR * aR2)); 								
				$('#ore_h2').val( aR );
			}else{
				
			}
		}
		function saveCord() {
			top.audW = parseInt($('#ore_w2').val());
			top.audH = parseInt($('#ore_h2').val());
		}
		var utmr = 0;
		var utmrp = 0;
		function autoDext2() {
			//clearTimeout(top.utmr);
			//top.utmr = setTimeout('autoDext2('+utmrp+')',20); 
			top.autoDext(utmrp);
		}
		</script>
        <div id="object_resize_editor" style="display:none">
        <b>Редактор объекта</b><br>
        <div>
        	<div style="display:inline-block;width:100px;">ID:</div>
            <input id="ore_id" type="text" value=""> (для нового указывать: 0)
        </div>
        <div>
        	<div style="display:inline-block;width:100px;">название:</div>
            <input id="ore_name" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:100px;">изображение:</div>
            <input id="ore_img" type="text" value="">
        </div>
        ------[Для текущей позиции]-----<br>
        <div>
        	<div style="display:inline-block;width:70px;">ширина:</div>
            <input id="ore_w" type="text" value=""> (общая) <input id="ore_w2" onKeyUp="utmrp=0;autoDext2();" type="text" value=""> (<a href="javascript:void(0)" onClick="saveCord();">Новый расчет</a>)
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">высота:</div>
            <input id="ore_h" type="text" value=""> (общая) <input id="ore_h2" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">top:</div>
            <input id="ore_t" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">left:</div>
            <input id="ore_l" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">X:</div>
            <input id="ore_x" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">Y:</div>
            <input id="ore_y" type="text" value="">
        </div>
        ------[Детали отображения]-----<br>
        <div>
        	<div style="display:inline-block;width:70px;">TYPE:</div>
            <input id="ore_type" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">TYPE2:</div>
            <input id="ore_type2" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">S:</div>
            <input id="ore_s" type="text" value="">
        </div>
        <div>
        	<div style="display:inline-block;width:70px;">S2:</div>
            <input id="ore_s2" type="text" value="">
        </div>
        <? $i = 1; while($i <= 4) { ?>
        <div>
        	<div style="display:inline-block;width:70px;">OS<?=$i?>:</div>
            <input id="ore_os<?=$i?>" type="text" value="">
        </div>
        <? $i++; } ?>
        <div>
        	<div style="display:inline-block;width:70px;">FIX_X_Y:</div>
            <input id="ore_fix_x_y" type="text" value="">
        </div>
        <a onClick="ore_save_obj()" href="javascript:void(0)">Сохранить / Добавить объект</a><br>
        -------------------------------------<br>
        <a onClick="ore_delete_obj()" href="javascript:void(0)">Удалить объект</a>        
        </div>
        </td>
        <td><div style="overflow:auto; height:410px">
          <?
    if(!isset($test_id)) {
        echo '<br><br><br><br><br><center>Пещера не существует</center>';
    }else{
        //Пещера существует
		$min_x = mysql_fetch_array(mysql_query('SELECT `x` FROM `dungeon_map` WHERE `id_dng` = "'.mysql_real_escape_string($id).'" ORDER BY `x` ASC LIMIT 1'));
		$max_x = mysql_fetch_array(mysql_query('SELECT `x` FROM `dungeon_map` WHERE `id_dng` = "'.mysql_real_escape_string($id).'" ORDER BY `x` DESC LIMIT 1'));
		$min_y = mysql_fetch_array(mysql_query('SELECT `y` FROM `dungeon_map` WHERE `id_dng` = "'.mysql_real_escape_string($id).'" ORDER BY `y` ASC LIMIT 1'));
		$max_y = mysql_fetch_array(mysql_query('SELECT `y` FROM `dungeon_map` WHERE `id_dng` = "'.mysql_real_escape_string($id).'" ORDER BY `y` DESC LIMIT 1'));
		
		$min_x = $min_x[0];
		$max_x = $max_x[0];
		$min_y = $min_y[0];
		$max_y = $max_y[0];
		
		$map = array();
		$stl = array();
		
		$sp = mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.mysql_real_escape_string($id).'" AND `x` >= '.$min_x.' AND `x` <= '.$max_x.' AND `y` >= '.$min_y.' AND `y` <= '.$max_y.'');
		while($pl = mysql_fetch_array($sp)) {
			$style  = 'vertical-align:bottom;';			
			
			if($pl['st'][2] == 1) {
				$style .= 'border-bottom:1px solid #6e6e6e;';	
			}else{
				$style .= 'padding-bottom:1px;';
			}
			if($pl['st'][1] == 1) {
				$style .= 'border-left:1px solid #6e6e6e;';	
			}else{
				$style .= 'padding-left:1px;';
			}			
			if($pl['st'][0] == 1) {
				$style .= 'border-top:1px solid #6e6e6e;';	
			}else{
				$style .= 'padding-top:1px;';
			}			
			if($pl['st'][3] == 1) {
				$style .= 'border-right:1px solid #6e6e6e;';	
			}else{
				$style .= 'padding-right:1px;';
			}
									
			$map[$pl['x']][$pl['y']] = '<img class="cq" onClick="top.goPix('.$pl['id'].','.$pl['x'].','.$pl['y'].');" id="px_'.$pl['id'].'" style="'.$style.'" width="20" height="20" src="http://img.xcombats.com/1x1.gif" title="X: '.$pl['x'].', Y: '.$pl['y'].'">';
		}
		
        echo '<center><b>Карта пещеры</b> (X: ['.$min_x.'] - ['.$max_x.'] , Y: ['.$min_y.'] - ['.$max_y.'])</center><br><br>';
		
		$r = '<table border="0" cellspacing="0" cellpadding="0">';		
		$i = $max_y;
		while($i >= $min_y) {
			$j = $min_x;
			$r .= '<tr>';
			while($j <= $max_x) {
				if(isset($map[$j][$i])) {
					$r .= '<td>'.$map[$j][$i].'</td>';
				}else{	
					$r .= '<td><img style="vertical-align:bottom" width="1" height="1" src="http://img.xcombats.com/1x1.gif"></td>';	
				}
				$j++;
			}
			$r .= '</tr>';	
			$i--;
		}
		$r .= '</table>';
		
		echo $r;
    }
    ?>
        </div></td>
      </tr>
      </table>
        
    </td>
  </tr>
</table>
</body>
</html>
<? } ?>