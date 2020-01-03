<?php
/*

	Ядро для обработки данных.
	Обработка поединков, обработка заявок, обработка ботов, обработка пещер, обработка турниров, обработка временных генераций

*/

define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');


if( $u->info['admin'] == 0 && $u->info['id'] != 1001398 ) {
	header('location: /index.php');
}

if( isset($_POST['it_name']) ) {
	//Добавляем предмет
	$error = '';
	
/*
Array
(
[it_name] => Кастет -Когти медведя-
[it_img] => old/kastet2.gif
[it_type] => 18
[it_massa] => 2
[it_price1] => 3
[it_price2] =>
[it_iznos] => 20
[it_slot] => 3
[it_inRazdel] => 1
[it_info] =>
[it_group_max] =>
[it_geni] => 1
[it_srok] =>
[it_max_text] =>
[it_ndata] =>
[it_data_value] => |sv_minAtack=2|sv_maxAtack=4
[button] => Отправить предмет в базу
)
*/
	
	if( (int)$_POST['it_group_max'] > 0 ) {
		$_POST['it_group'] = 1;
	}
	
	$ins = mysql_query('INSERT INTO `items_main`
		(`name`,`img`,`type`,`massa`,`price1`,`price2`,`iznosMAXi`,`inslot`,
		 `inRazdel`,`info`,`group`,`group_max`,`geni`,`srok`,`max_text`,`2h`,`2too`) VALUES
		(
			"'.mysql_real_escape_string($_POST['it_name']).'",
			"'.mysql_real_escape_string($_POST['it_img']).'",
			"'.mysql_real_escape_string($_POST['it_type']).'",
			"'.mysql_real_escape_string($_POST['it_massa']).'",
			"'.mysql_real_escape_string($_POST['it_price1']).'",
			"'.mysql_real_escape_string($_POST['it_price2']).'",
			"'.mysql_real_escape_string($_POST['it_iznos']).'",
			"'.mysql_real_escape_string($_POST['it_slot']).'",
			"'.mysql_real_escape_string($_POST['it_inRazdel']).'",
			"'.mysql_real_escape_string($_POST['it_info']).'",
			"'.mysql_real_escape_string($_POST['it_group']).'",
			"'.mysql_real_escape_string($_POST['it_group_max']).'",
			"'.mysql_real_escape_string($_POST['it_geni']).'",
			"'.mysql_real_escape_string($_POST['it_srok']).'",
			"'.mysql_real_escape_string($_POST['it_max_text']).'",
			"'.mysql_real_escape_string($_POST['it_2h']).'",
			"'.mysql_real_escape_string($_POST['it_2too']).'"
		)');
		
		if( $ins ) {
			$iid = mysql_insert_id();
			$ins = mysql_query('INSERT INTO `items_main_data` (`items_id`,`data`) VALUES (
				"'.$iid.'","'.mysql_real_escape_string($_POST['it_data_value']).'"
			)');
			if( !$ins ) {
				$error = 'Неудалось добавить Er::(2)!';
			}else{
				$error = $iid.' Предмет добавлен!';
			}
		}else{
			$error = 'Неудалось добавить Er::(1)!';
		}
	
	
	if( $error == '' ) {
		$error = 'Что-то не так...';
	}
	die('<font color=red><b>'.$error.'</b></font>');
}


	//Редактор предметов
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Редактор предметов</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script>
var et = {
	is_par:[
	<?
	$html = ''; $html2 = '';
	$i = 0;
	$is_key = array_keys($u->is);
	while( $i < count($is_key) ) {
		$html  .= ',"'.$is_key[$i].'"';
		$html2 .= ',"'.$is_key[$i].'":"'.$u->is[$is_key[$i]].'"';
		$i++;
	}
	echo ltrim($html,',');	
	?>
	],	is_name:{
	<?=ltrim($html2,',')?>
	},
	data:{
		img:'w/w10.gif',
		name:'Название нового предмета'
	},
	complData:function() {
		var html = '';
		
		//Требует
		if( this.it_data_pr.tr != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.tr ) {
				var npar = $('#par_tr_'+i).val();
				if( npar != undefined && $('#val_tr_'+i).val() != '' ) {
					html += '|tr_'+npar+'='+$('#val_tr_'+i).val();
				}
				i++;
			}
		}
		//Действует на
		if( this.it_data_pr.add != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.add ) {
				var npar = $('#par_add_'+i).val();
				if( npar != undefined && $('#val_add_'+i).val() != '' ) {
					html += '|add_'+npar+'='+$('#val_add_'+i).val();
				}
				i++;
			}
		}
		//Свойства
		if( this.it_data_pr.sv != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.sv ) {
				var npar = $('#par_sv_'+i).val();
				if( npar != undefined  && $('#val_sv_'+i).val() != '') {
					html += '|sv_'+npar+'='+$('#val_sv_'+i).val();
				}
				i++;
			}
		}
		//Остальное
		if( this.it_data_pr.all != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.all ) {
				var npar = $('#par_all_'+i).val();
				if( npar != undefined && $('#val_all_'+i).val() != '' ) {
					html += '|'+npar+'='+$('#val_all_'+i).val();
				}
				i++;
			}
		}

		$('#it_data_value').val( html );
	},
	it_data_pr:{},
	newpar:function( id ) {
		var html = '';
		if( this.it_data_pr[id] == undefined ) {
			this.it_data_pr[id] = 0;
		}else{
			this.it_data_pr[id]++;
		}
		
		html += '<select id="par_'+id+'_'+this.it_data_pr[id]+'" name="par_'+id+'_'+this.it_data_pr[id]+'">';
		var i = 0;
		while( i <= this.is_par.length ) {
			if( this.is_par[i] != undefined ) {
				html += '<option value="'+this.is_par[i]+'">'+this.is_name[this.is_par[i]]+'</option>';
			}
			i++;
		}
		html += '</select><input id="val_'+id+'_'+this.it_data_pr[id]+'" name="val_'+id+'_'+this.it_data_pr[id]+'" type="text" value="" >';
		
		html = '<div id="new_par_'+id+'_'+this.it_data_pr[id]+'"> &nbsp; <a href="javascript:et.delpar(\''+id+'\','+this.it_data_pr[id]+')">&nbsp; x &nbsp;</a> &nbsp; ' + html + '</div>';
		$('#it_data_' + id).html( $('#it_data_' + id).html() + html );
	},
	delpar:function(id,num) {
		$('#new_par_'+id+'_'+num+'').remove();
	},
	compl:function() {
		var html = '',html_l = '',html_r = '';
		
		//Собираем данные
		this.data = {
			'name' : $('#it_name').val(),
			'img' : $('#it_img').val()
			
		};
		
		//Левая часть
		html_l += '<img src="http://img.xcombats.com/i/items/' + this.data.img + '">';
		
		//Правая часть
		html_r = '<a href="#">' + this.data.name + '</a>';
		
		//Собираем
		html = '<table style="border:#A5A5A5 1px solid;" width="100%" border="0" cellspacing="0" cellpadding="0">'+
					'<tr>'+
						'<td valign="top">' + 
						'<table width="100%" border="0" cellspacing="0" cellpadding="0">'+
						  '<tr>'+
							'<td width="20%" align="center" style="border-right:#A5A5A5 1px solid; padding:5px;">'+html_l+'</td>'+
							'<td valign="top" align="left" style="padding-left:3px; padding-bottom:3px; padding-top:7px;">'+html_r+'</td>'+
						  '</tr>'+
						'</table>'+
						'</td>'+
					'</tr>'+
				'</table>';
		
		$('#etitm').html( html );
	}
};
</script>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="50%">
    <!-- loading img -->
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr>
        <td width="50" align="center" valign="top">&nbsp;
        
        </td>
        <td valign="top">
        <form method="post" action="items_editor.php" target="F2">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="200" bgcolor="#FFCCCC">Название</td>
            <td bgcolor="#FFCCCC"><input style="width:100%" type="text" name="it_name" id="it_name"></td>
          </tr>
          <tr>
            <td>Изображение</td>
            <td><input style="width:100%" type="text" name="it_img" id="it_img"></td>
          </tr>
          <tr>
            <td bgcolor="#FFCCCC">Тип</td>
            <td bgcolor="#FFCCCC"><label for="it_type"></label>
              <select name="it_type" id="it_type">
                <option value="0">выберите тип</option>
                <option value="1">Шлем</option>
                <option value="2">Венок</option>
                <option value="3">Наручи</option>
                <option value="4">Рубашка</option>
                <option value="5">Легкая броня</option>
                <option value="6">Тяжелая броня</option>
                <option value="7">Плащ</option>
                <option value="8">Пояс</option>
                <option value="9">Серьги</option>
                <option value="10">Амулет</option>
                <option value="11">Кольцо</option>
                <option value="12">Перчатки</option>
                <option value="13">Щит</option>
                <option value="14">Поножи</option>
                <option value="15">Ботинки</option>
                <option value="16">Предмет для карманов</option>
                <option value="17">Предмет для смены</option>
                <option value="18">Нож \ Кинжал</option>
                <option value="19">Топор \ Секира</option>
                <option value="20">Молот \ Дубина</option>
                <option value="21">Меч \ Клинок</option>
                <option value="22">Магический посох</option>
                <option value="23">Лук</option>
                <option value="24">Арбалет</option>
                <option value="25">Боеприпасы \ Стреллы</option>
                <option value="26">Костыли</option>
                <option value="27">Легендарное оружие</option>
                <option value="28">Цветы \ Букеты \ Ёлки</option>
                <option value="29">Заклятие</option>
                <option value="30">Эликсир</option>
                <option value="31">Руна</option>
                <option value="32">Ресурс</option>
                <option value="33">Мусор</option>
                <option value="34">Прочее</option>
                <!--<option value="35">Сумка</option>-->
                <option value="36">Усиление</option>
                <option value="37">Упаковка</option>
                <option value="38">Подарок</option>
                <option value="39">Подарок (требует упаковку)</option>
                <option value="40">Книжный прием</option>
                <option value="41">Приглашение</option>
                <option value="42">Билет</option>
                <option value="43">Слот смены</option>
                <option value="44">Пергамент (с текстом)</option>
                <option value="45">Сумка</option>
                <option value="46">Заточка</option>
                <option value="47">Усиление 1</option>
                <option value="48">Усиление 2 (временное)</option>
                <option value="49">Корм для животного</option>
                <option value="60">Бумага</option>
                <option value="61">Чек</option>
                <option value="62">Чарка</option>
                <option value="63">Открытка</option>
              </select></td>
          </tr>
          <tr>
            <td>Масса</td>
            <td><input style="width:100%" type="text" name="it_massa" id="it_massa"></td>
          </tr>
          <tr>
            <td>Судьба</td>
            <td><input name="it_sudba" type="checkbox" id="it_sudba" value="1"></td>
          </tr>
          <tr>
            <td>Артефакт</td>
            <td><input name="it_art2" type="checkbox" id="it_art3" value="1"></td>
          </tr>
          <tr>
            <td>Двуручное</td>
            <td><input name="it_2h" type="checkbox" id="it_art4" value="1"></td>
          </tr>
          <tr>
            <td>В обе руки</td>
            <td><input name="it_2too2" type="checkbox" id="it_2too3" value="1"></td>
          </tr>
          <tr>
            <td>Цена (кр)</td>
            <td><input style="width:100%" type="text" name="it_price1" id="it_price1"></td>
          </tr>
          <tr>
            <td>Цена (екр)</td>
            <td><input style="width:100%" type="text" name="it_price2" id="it_price2"></td>
          </tr>
          <tr>
            <td>Долговечность</td>
            <td><input style="width:100%" type="text" name="it_iznos" id="it_iznos"></td>
          </tr>
          <tr>
            <td bgcolor="#FFCCCC">Слот</td>
            <td bgcolor="#FFCCCC"><select name="it_slot" id="it_slot">
              <option value="0">не надевается</option>
              <option value="1">Шлем</option>
              <option value="2">Наручи</option>
              <option value="3">Оружие (Правая рука)</option>
              <option value="4">Рубаха</option>
              <option value="5">Броня</option>
              <option value="6">Плащ</option>
              <option value="7">Пояс</option>
              <option value="8">Серьги</option>
              <option value="9">Амулет</option>
              <option value="10">Кольцо</option>
              <option value="13">Перчатки</option>
              <option value="14">Оружие \ Щит (Левая рука)</option>
              <option value="16">Поножи</option>
              <option value="17">Ботинки</option>
              <option value="18">Приём</option>
              <option value="40">Заклятия</option>
              <option value="51">Книга</option>
              <option value="52">Венок</option>
              <option value="53">Карман</option>
              <option value="55">Центральный карман</option>
              <option value="56">Смена оружия</option>
              <option value="59">Слот сумки</option>
			  </select></td>
          </tr>
          <tr>
            <td>Раздел инвентаря</td>
            <td><select name="it_inRazdel" id="it_inRazdel">
              <option value="1">Обмундирование</option>
              <option value="2">Заклятия</option>
              <option value="3">Эликсиры</option>
              <option value="6">Руны</option>
              <option value="4">Прочее</option>
            </select></td>
          </tr>
          <tr>
            <td>Информация</td>
            <td><input style="width:100%" type="text" name="it_info" id="it_info"></td>
          </tr>
          <tr>
            <td bgcolor="#FFCCCC">Группировка</td>
            <td bgcolor="#FFCCCC"><input style="width:100%" type="text" name="it_group_max" id="it_group_max"></td>
          </tr>
          <tr>
            <td>Поколение</td>
            <td><input style="width:100%" type="text" name="it_geni" id="it_geni"></td>
          </tr>
          <tr>
            <td>Срок годности (сек.)</td>
            <td><input style="width:100%" type="text" name="it_srok" id="it_srok"></td>
          </tr>
          <tr>
            <td>Макс. текст (символов)</td>
            <td><input style="width:100%" type="text" name="it_max_text" id="it_max_text"></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC">Доп.дата</td>
            <td bgcolor="#FFFFCC"><input style="width:100%" type="text" name="it_ndata" id="it_ndata"></td>
          </tr>
          <tr>
            <td align="center" valign="middle">ITEMS_MAIN_DATA:</td>
            <td><textarea name="it_data_value" cols="100" rows="10" id="it_data_value"></textarea></td>
          </tr>
          <tr>
            <td>
            <iframe id="F2" width="200" height="30" name="F2" frameborder="0" marginheight="0" marginwidth="0"></iframe>
            </td>
            <td><input type="submit" name="button" id="button" value=" Отправить предмет в базу "></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </form>
        </td>
      </tr>
    </table>
    <!-- loading img -->
    </td>
    <td valign="top" bgcolor="#C8C8C8">
    <button onClick="et.complData()">Собрать дату</button>
    <hr>
    
    <b>Требования: <a href="javascript:et.newpar('tr')">[+]</a></b>
    <div id="it_data_tr">
    
    </div>
    
    <b>Действует на: <a href="javascript:et.newpar('add')">[+]</a></b>
    <div id="it_data_add">
    
    </div>
    
    <b>Свойства: <a href="javascript:et.newpar('sv')">[+]</a></b>
    <div id="it_data_sv">
    
    </div>
    
    <b>Остальное: <a href="javascript:et.newpar('all')">[+]</a></b>
    <div id="it_data_all">
    
    </div>
    
    <hr>
    &nbsp;
    <div id="etitm"></div>
    </td>
  </tr>
  </table>
</body>
</html>