<?php
/*

	Ядро для обработки данных.
	Обработка поединков, обработка заявок, обработка ботов, обработка пещер, обработка турниров, обработка временных генераций

*/

define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

if(isset($_GET['bonus'])) {
	$sp = mysql_query('SELECT * FROM `users` WHERE `real` = 1 AND `banned` = 0 AND `id` = 75301');
	while( $pl = mysql_fetch_array($sp) ) {
		//
		//$u->addItem(730, $pl['id'], '|nosale=1|notransfer=1|sudba=1|halo1=1');
		//$u->addItem(1167, $pl['id'], '|nosale=1|notransfer=1|sudba=1|halo1=1');
		
		$i = 1;
		while( $i <= 25 ) {
			//$u->addItem(4913, $pl['id'], '|nosale=1|notransfer=1|sudba=1|halo1=1');
			if( $i <= 10 ) {
				//$u->addItem(4039, $pl['id'], '|nosale=1|notransfer=1|sudba=1|halo1=1');
			}
			//$u->addItem(4690, $pl['id'], '|nosale=1|notransfer=1|sudba=1|halo1=1');
			$i++;
		}		
		
		//
		/*$sp1 = mysql_query('SELECT `id` FROM `items_main` WHERE `magic_inci` = "tznanie" AND `name` NOT LIKE "%(том%" AND `name` NOT LIKE "%(секретный том%"');
		while( $pl1 = mysql_fetch_array($sp1) ) {
			mysql_query('INSERT INTO `actions` (
				`uid`,`time`,`city`,`room`,`vars`,`vals`
			) VALUES (
				"'.$pl['id'].'","'.time().'","capitalcity","213","read","'.$pl1['id'].'"
			)');
		}*/
		
	}
	die();
}

include('_incl_data/class/bot.logic.php');


if( $u->info['admin'] > 0 ) {
	
	
	if(isset($_POST['complect_saver'])) {
		
		$cs = $_POST['complect_saver'];
		
		$cs = json_decode($cs);		
				
		$v1 = '';
		$v2 = '';
		$eff = '0';
		$csa = $cs->{'lvl'};
		$csm = $cs->{'lvl'}+1;
		if($csa > 5) {
			$csa = 5;
		}
		if($csm > 10) {
			 $csm = 10;
		}
		
		$up_id = 0;
		
		$sp = mysql_query('SELECT * FROM `levels` ORDER BY `upLevel`');
		$ups = array();
		$all_ups = array();
		$lvl = 0;
		while($pl = mysql_fetch_array($sp)) {
			$all_ups[$lvl][(0+$ups[$lvl])] = ($pl['upLevel']-1);
			$ups[$lvl]++;
			if($lvl != $pl['nextLevel']) {
				$lvl = $pl['nextLevel'];
			}else{
			}
		}
		
		$up_id = $all_ups[$cs->{'lvl'}][$cs->{'up'}];
		
		$stats = 's1='.$cs->{'s1'}.'|s2='.$cs->{'s2'}.'|s3='.$cs->{'s3'}.'|s4='.$cs->{'s4'}.'|rinv=40|m9=5|m6=10|a1='.$csa.'|a2='.$csa.'|a3='.$csa.'|a4='.$csa.'|a5='.$csa.'|mg1='.$csm.'|mg2='.$csm.'|mg3='.$csm.'|mg4='.$csm.'|mg5='.$csm.'|mg6='.$csa.'|mg7='.$csa.'|s5='.$cs->{'s5'}.'|s6='.$cs->{'s6'}.'|os1=0|os2=0|os3=0|os4=0|os5=0|os6=0|os7=0|os8=0|os9=0|os10=0|os11=0|s7=0|s8=0|s9=0|s10=0|a6=5|a7=5';
		
		$i = 0;
		while( $i < 20 ) {
			
			if( $cs->{'w'.$i} > 0 ) {
				$v1 .= ',`w'.$i.'`';
				$v2 .= ',"'.$cs->{'w'.$i}.'"';
			}
			
			if( $cs->{'s'.$i} > 0 ) {
				$v1 .= ',`s'.$i.'`';
				$v2 .= ',"'.$cs->{'s'.$i}.'"';
			}
			
			if( $cs->{'e'.$i} > 0 ) {
				$v1 .= ',`e'.$i.'`';
				$v2 .= ',"'.$cs->{'e'.$i}.'"';
				$eff .= ','.$i;
			}
			
			if( $cs->{'p'.$i} > 0 ) {
				$v1 .= ',`p'.$i.'`';
				$v2 .= ',"'.$cs->{'p'.$i}.'"';
			}
			
			$i++;
		}
		mysql_query('INSERT INTO `a_bot_tree` (`delete`,`class`,`level`,`up`,`stats`,`eff`,`pr`'.$v1.') VALUES ("1","'.$cs->{'class'}.'","'.$cs->{'lvl'}.'","'.$up_id.'","'.$stats.'","'.$eff.'","'.$pr.'"'.$v2.') ');
		
		$_GET['good'] = mysql_insert_id();
		//header('location: admin.php?good');
	}
	
	if(isset($_GET['good'])) {
		$error = 'Комлпект был успешно сохранен!<br><br>Номер комплекта: '.$_GET['good'];
		die($error);
	}
	
	
	/* ПРЕДМЕТЫ */
	$w = array();
	$ws = array(
		0,
		1,
		2,
		3,
		4,
		5,
		6,
		7,
		8,
		9,
		10,
		10,
		10,
		13,
		14,
		15,
		16,
		17
	);
	
	$i = 1;
	while( $i <= 17 ) {
		if($i != 14) {
			$sp = mysql_query('SELECT `id`,`name`,`img` FROM `items_main` WHERE `inslot` = "'.$ws[$i].'" ORDER BY `type` ASC,`level` ASC');
			while($pl = mysql_fetch_array($sp)) {
				$w[$i] .= '['.$pl['id'].',"'.$pl['name'].'","'.$pl['img'].'"],';
			}
			$w[$i] = rtrim($w[$i],',');
		}
		$i++;
	}
	
	$sp = mysql_query('SELECT `id`,`name`,`img` FROM `items_main` WHERE `2too` = "1" OR `inslot` = "14" ORDER BY `type` ASC,`level` ASC');
	while($pl = mysql_fetch_array($sp)) {
		$w[14] .= '['.$pl['id'].',"'.$pl['name'].'","'.$pl['img'].'"],';
	}	
	$w[14] = rtrim($w[14],',');
	
	
	/* ПРИЕМЫ */
	$pr = '';

	$sp = mysql_query('SELECT `id`,`name`,`img` FROM `priems` ORDER BY `img` ASC');
	while($pl = mysql_fetch_array($sp)) {
		$pr .= '['.$pl['id'].',"'.$pl['name'].'","'.$pl['img'].'.gif"],';
	}	
	$pr = rtrim($pr,',');
	
	
	/* ЭФФЕКТЫ */
	$eff = '';
	
	$sp = mysql_query('SELECT `id2`,`mname`,`img` FROM `eff_main` WHERE `type1` != 23 ORDER BY `type1` ASC');
	while($pl = mysql_fetch_array($sp)) {
		$eff .= '['.$pl['id2'].',"'.$pl['mname'].'","'.$pl['img'].'"],';
	}	
	$eff = rtrim($eff,',');
	
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<title>Редактор обмундирования персонажей</title>

<script>
var admin = {
	com:{},
	start:function() {
		var i = 0;
		while( i <= 17 ) {	
			$('#w'+i).attr('IDreal',i);		
			$('#w'+i).bind('click', function(){ admin.select($(this).attr('IDreal')); } );			
			i++;
		}
		
		var i = 0;
		while( i <= 12 ) {	
			$('#e'+i).attr('IDreal',i);		
			$('#e'+i).bind('click', function(){ admin.select_eff($(this).attr('IDreal')); } );			
			i++;
		}
		
		var i = 0;
		while( i <= 18 ) {	
			$('#p'+i).attr('IDreal',i);		
			$('#p'+i).bind('click', function(){ admin.select_pr($(this).attr('IDreal')); } );			
			i++;
		}
	},
	save:function() {
		var r = this.finish_line();
		$('#save').html( '<b>Скопируйте в буфер обмена:</b><br><br><form name="f1" id="f1" action="admin.php" target="_blank" method="post"><textarea name="complect_saver" style="width:600px;" rows="5">'+r+'</textarea><br><button type="sumbit"></button></form>' );
		$('#f1').submit();
	},
	finish_line:function() {
		
		this.com['lvl'] = $('#lvl').val();
		this.com['up'] = $('#up').val();
		this.com['class'] = $('#pclss').val();
		
		var i = 1;
		while( i <= 6 ) {
			this.com['s'+i] = $('#s'+i).val();
			i++;
		}
		
		var r = JSON.stringify( this.com );		
		return r;
	
	},
	take:function( id, i ) {
		if( top.items[id][i] != undefined ) {
			this.com['w'+id] = top.items[id][i][0];
			$('#w'+id).attr({
					'src':'http://img.xcombats.com/i/items/'+top.items[id][i][2],
					'title':top.items[id][i][1]
				});
		}
	},
	take_eff:function( id, i ) {
		if( top.items['eff'][i] != undefined ) {
			this.com['e'+id] = top.items['eff'][i][0];
			$('#e'+id).attr({
					'src':'http://img.xcombats.com/i/eff/'+top.items['eff'][i][2],
					'title':top.items['eff'][i][1]
				});
		}
	},
	take_pr:function( id, i ) {
		if( top.items['pr'][i] != undefined ) {
			this.com['p'+id] = top.items['pr'][i][0];
			$('#p'+id).attr({
					'src':'http://img.xcombats.com/i/eff/'+top.items['pr'][i][2],
					'title':top.items['pr'][i][1]
				});
		}
	},
	select_pr:function (id) {
		var html = '';
		
		if( top.items['pr'] != undefined ) {
		
			var i = 0;
			
			while( i != -1 ) {
				
				if( top.items['pr'][i] == undefined ) {
					
					i = -2;
					
				}else{
					
					html += '<img width="40" height="25" onclick="admin.take_pr('+id+','+i+');" style="cursor:pointer;padding:2px;" title="'+top.items['pr'][i][1]+'" src="http://img.xcombats.com/i/eff/'+top.items['pr'][i][2]+'">';
				
				}
				
				i++;
			}
		
		}
		
		if( html == '' ) {
			html = 'Нет подходящих приемов';
		}
		
		$('#items_add').html( html );
	},
	select_eff:function (id) {
		var html = '';
		
		if( top.items['eff'] != undefined ) {
		
			var i = 0;
			
			while( i != -1 ) {
				
				if( top.items['eff'][i] == undefined ) {
					
					i = -2;
					
				}else{
					
					html += '<img width="40" height="25" onclick="admin.take_eff('+id+','+i+');" style="cursor:pointer;padding:2px;" title="'+top.items['eff'][i][1]+'" src="http://img.xcombats.com/i/eff/'+top.items['eff'][i][2]+'">';
				
				}
				
				i++;
			}
		
		}
		
		if( html == '' ) {
			html = 'Нет подходящих эффектов';
		}
		
		$('#items_add').html( html );
	},
	select:function( id ) {
		var html = '';
		
		if( top.items[id] != undefined ) {
		
			var i = 0;
			
			while( i != -1 ) {
				
				if( top.items[id][i] == undefined ) {
					
					i = -2;
					
				}else{
					
					html += '<img onclick="admin.take('+id+','+i+');" style="cursor:pointer;padding:2px;" title="'+top.items[id][i][1]+'" src="http://img.xcombats.com/i/items/'+top.items[id][i][2]+'">';
				
				}
				
				i++;
			}
		
		}
		
		if( html == '' ) {
			html = 'Нет подходящих предметов';
		}
		
		$('#items_add').html( html );
	}
};

var items = {
	<?
	$i = 1;
	while( $i <= 17 ) {
		echo $i.':['.$w[$i].'],';
		$i++;
	}
	echo '"eff":['.$eff.'],"pr":['.$pr.']';
	?>
};
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script> 

</head>

<body onLoad="admin.start()">
<table width="100%" border="0" cellpadding="25" cellspacing="0">
  <tr>
    <td width="250" valign="top"><div style="width:240px; padding:2px; border-bottom:1px solid #666666; border-right:1px solid #666666; border-left:1px solid #FFFFFF; border-top:1px solid #FFFFFF;">
      <div align="center">
        <!-- blocked -->
      </div>
      <table width="240" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="60" valign="top"><table width="60" height="280" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="60"><div style="position:relative"><img id="w1" style="display:block;" title="Пустой слот шлем" src="http://img.xcombats.com/i/items/w/w9.gif"></div></td>
            </tr>
            <tr>
              <td height="40"><img id="w2" style="display:block;" title="Пустой слот наручи" src="http://img.xcombats.com/i/items/w/w13.gif"></td>
            </tr>
            <tr>
              <td height="60"><img id="w3" style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w3.gif"></td>
            </tr>
            <tr>
              <td height="80"><img id="w5" style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w4.gif"></td>
            </tr>
            <tr>
              <td height="40"><img id="w7" style="display:block;" title="Пустой слот пояс" src="http://img.xcombats.com/i/items/w/w5.gif"></td>
            </tr>
          </table></td>
          <td height="280" valign="top"><table width="120" height="280" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="20" valign="top" bgcolor="#EDEDED"><!-- HP and MP -->
                <!-- --></td>
            </tr>
            <tr>
              <td valign="top" bgcolor="#333333">&nbsp;</td>
            </tr>
            <tr>
              <td height="40"><div align="center">
                <table width="120" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="40" height="20"><img style="display:block;" title="Пустой слот правый карман" src="http://img.xcombats.com/i/items/w/w15.gif"></td>
                    <td width="40" height="20"><img style="display:block;" title="Пустой слот центральный карман" src="http://img.xcombats.com/i/items/w/w15.gif"></td>
                    <td width="40" height="20"><img style="display:block;" title="Пустой слот левый карман" src="http://img.xcombats.com/i/items/w/w15.gif"></td>
                  </tr>
                  <tr>
                    <td width="40" height="20"><img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif"></td>
                    <td width="40" height="20"><img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif"></td>
                    <td width="40" height="20"><img style="display:block;" title="Пустой слот смена" src="http://img.xcombats.com/i/items/w/w20.gif"></td>
                  </tr>
                </table>
              </div></td>
            </tr>
          </table></td>
          <td width="60" valign="top"><table width="60" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="20"><img id="w8" style="display:block;" title="Пустой слот серьги" src="http://img.xcombats.com/i/items/w/w1.gif"></td>
            </tr>
            <tr>
              <td height="20"><img id="w9" style="display:block;" title="Пустой слот ожерелье" src="http://img.xcombats.com/i/items/w/w2.gif"></td>
            </tr>
            <tr>
              <td height="20"><table width="60" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="20" height="20"><img id="w10" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif"></td>
                  <td width="20"><img id="w11" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif"></td>
                  <td width="20"><img id="w12" style="display:block;" title="Пустой слот кольцо" src="http://img.xcombats.com/i/items/w/w6.gif"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="40"><img id="w13" style="display:block;" title="Пустой слот перчатки" src="http://img.xcombats.com/i/items/w/w11.gif"></td>
            </tr>
            <tr>
              <td height="60"><img id="w14" style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w10.gif"></td>
            </tr>
            <tr>
              <td height="80"><img id="w16" style="display:block;" title="Пустой слот щит" src="http://img.xcombats.com/i/items/w/w19.gif"></td>
            </tr>
            <tr>
              <td height="40"><img id="w17" style="display:block;" title="Пустой слот обувь" src="http://img.xcombats.com/i/items/w/w12.gif"></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <p><strong>Эффекты:</strong></p>
      <table width="240" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="40" height="25"><img id="e1" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="e2" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="e3" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="e4" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="e5" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="e6" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
        </tr>
        <tr>
          <td height="25"><img id="e7" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="e8" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="e9" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="e10" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="e11" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="e12" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
        </tr>
      </table>
      <p><strong>Приемы:</strong></p>
      <table width="240" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="40" height="25"><img id="p1" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="p2" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="p3" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="p4" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="p5" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td width="40"><img id="p6" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
        </tr>
        <tr>
          <td height="25"><img id="p7" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p8" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p9" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p10" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p11" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p12" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
        </tr>
        <tr>
          <td height="25"><img id="p13" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p14" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p15" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p16" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p17" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
          <td><img id="p18" title="Пустой слот заклинания" src="http://img.xcombats.com/i/items/w/w101.gif"></td>
        </tr>
      </table>
    </div>
    <p><a href="javascript:admin.save(1);">Сохранить комплект в базу</a></p></td>
    
        <td width="150" align="right" valign="top"><p><strong>Статы персонажа (родные):</strong></p>
        <p>
        Сила: 
          <input id="s1" style="width:35px" value="3">   <br>     
        Ловкость: 
        <input id="s2" style="width:35px" value="3">   <br> 
        Интуиция: 
        <input id="s3" style="width:35px" value="3">   <br> 
        Выносливость: 
        <input id="s4" style="width:35px" value="3">   <br> 
        Интелект: 
        <input id="s5" style="width:35px" value="0">   <br> 
        Мудрость: 
        <input id="s6" style="width:35px" value="0">
        </p>
        <p><strong>Выберите класс комплекта и апп: </strong></p>
        <p>
          Класс: 
          <select id="pclss">
            <option selected value="1">Танк</option>
            <option value="2">Крит</option>
            <option value="3">Уворот</option>
            <option value="4">Универсал</option>
            <option value="5">Маг огня</option>
            <option value="6">Маг воды</option>
            <option value="7">Маг земли</option>
            <option value="8">Маг воздуха</option>
          </select>
<br>
        Уровень:
          <input id="lvl" style="width:35px" value="0">
          <br>
          Апп:
          <input id="up" style="width:35px" value="0">
<br> 
        </p>
        
        <hr>
        </td>
    
    <td valign="top">
    <div id="save" style="border:1px solid red; padding:10px;margin:5px; background-color:#FCC; color:#333"></div>
    <br>
    <b>Предметы для слота:</b>
    <br>
    <div id="items_add">
    	
    </div>
    </td>
  </tr>
</table>
<script>
<?
	
	if(isset($error)) {
		echo 'alert("'.$error.'");';
	}
	
?>
</script>
</body>
</html>
<?
}else{
	echo 'Для получени доступа к данному разделу - обратитесь к Администрации проекта, получить доступ может каждый игрок, но окончательное решение зависит исключительно от Администрации проекта.';
}
?>