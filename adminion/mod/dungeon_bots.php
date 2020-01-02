<?
if(!defined('GAME')){
	die();
}
if(isset($_GET['delete_dungeon_id'])){
	$delete_dungeon_id = intval($_GET['delete_dungeon_id']);
	mysql_query("DELETE FROM `dungeon_room` WHERE `id`='".$delete_dungeon_id."'");
	die("<script>window.location = 'index.php?mod=dungeon_list';</script>");
}
if(isset($_GET['id_bot'])){
	$_POST['botSelect'] = $_GET['id_bot'];
}

$Query = mysql_query("SELECT id, login FROM test_bot ORDER BY id ASC");
$dungeon_bots = '';
while($row = mysql_fetch_assoc($Query)){
	$dungeon_bots .= '<option value="'.$row['id'].'" '.($row['id']==$_POST['botSelect'] ? 'selected' : '').'>'.$row['login'].'</option>';
}
$Query = mysql_query("SELECT id, dungeon_id, dungeon_name FROM dungeon_room ORDER BY  active, dungeon_id ASC");
$dungeon_list = '';
while($row = mysql_fetch_assoc($Query)){
	$dungeon_list .= '<option value="'.$row['dungeon_id'].'" '.($row['dungeon_id']==$_POST['dunSelect'] ? 'selected' : '').'>'.$row['dungeon_name'].'</option>';
}
?>
<div align="left">
  <h3 style="text-align:left;">Пещерные жители</h3>
</div>
<form method="post" action="../../adminion/mod/index.php?mod=dungeon_bots">
<table>
	<tbody>
		<tr>
			<td>По пещере:
				<select name="dunSelect">
					<option selected value="0">Все</option>
					<?php echo $dungeon_list; ?>
				</select>&nbsp; &nbsp; &nbsp; &nbsp; 
			</td>
			<td>
				 Бот:
				<select name="botSelect">
					<option disabled selected value="0">выберите нужного бота...</option>
					<?php echo $dungeon_bots; ?>
				</select>
			</td>
			<td>
				 <input type="submit" value="выбрать">
			</td>
		</tr>
	</tbody>
</table>
</form>
<?
if ( $_POST['botSelect'] != '0' ) {
	$sBot = mysql_fetch_assoc(mysql_query("SELECT * FROM test_bot WHERE id='".$_POST['botSelect']."' ORDER BY id ASC"));
}

if( isset($sBot) ){
?>
<script>
	var CharacterBased = ['minAtack','maxAtack','zona','zonb','s1','s2','s3','s4','hpAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m10','za','zm','zma','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','pza','pzm'];
	var CharacterInfo = {
		'exp' : 'Получаемый опыт (%)',
		'align_bs' : 'Служитель закона',
		'nopryh' : 'Прямое поподание',
		'puti':'Запрет перемещения',
		'align':'Склонность',
		'hpAll':'Уровень жизни (HP)',
		'mpAll':'Уровень маны',
		'enAll':'Уровень энергии',
		'sex':'Пол',
		'lvl':'Уровень',
		'zona':'Доп зоны атаки',
		'zonb':'Зон защиты всего',
		's1':'Сила',
		's2':'Ловкость',
		's3':'Интуиция',
		's4':'Выносливость',
		's5':'Интеллект',
		's6':'Мудрость',
		's7':'Духовность',
		's8':'Воля',
		's9':'Свобода духа',
		's10':'Божественность',
		's11':'Энергия',
		'm1':'Мф. критического удара (%)',
		'm2':'Мф. против критического удара (%)',
		'm3':'Мф. мощности крит. удара (%)',
		'm4':'Мф. увертывания (%)',
		'm5':'Мф. против увертывания (%)',
		'm6':'Мф. контрудара (%)',
		'm7':'Мф. парирования (%)',
		'm8':'Мф. блока щитом (%)',
		'm9':'Мф. удара сквозь броню (%)',
		'm10':'Мф. мощности урона',
		'm11':'Мф. мощности магии стихий',
		'm11a':'Мф. мощности магии',
		'm14':'Мф. абс. критического удара (%)',
		'm15':'Мф. абс. увертывания (%)',
		'm16':'Мф. абс. парирования (%)',
		'm17':'Мф. абс. контрудара (%)',
		'm18':'Мф. абс. блока щитом (%)',
		'm19':'Мф. абс. магический промах (%)',
		'm20':'Мф. удача (%)',
		'a1':'Мастерство владения ножами, кинжалами',
		'a2':'Мастерство владения топорами, секирами',
		'a3':'Мастерство владения дубинами, молотами',
		'a4':'Мастерство владения мечами',
		'a5':'Мастерство владения магическими посохами',
		'a6':'Мастерство владения луками',
		'a7':'Мастерство владения арбалетами',
		'aall':'Мастерство владения оружием',
		'mall':'Мастерство владения магией стихий',
		'm2all':'Мастерство владения магией',
		'mg1':'Мастерство владения магией огня',
		'mg2':'Мастерство владения магией воздуха',
		'mg3':'Мастерство владения магией воды',
		'mg4':'Мастерство владения магией земли',
		'mg5':'Мастерство владения магией Света',
		'mg6':'Мастерство владения магией Тьмы',
		'mg7':'Мастерство владения серой магией',
		'mib1':'Броня головы (мин)',
		'mab1':'Броня головы (макс)',
		'mib2':'Броня корпуса (мин)',
		'mab2':'Броня корпуса (макс)',
		'mib3':'Броня пояса (мин)',
		'mab3':'Броня пояса (макс)',
		'mib4':'Броня ног (мин)',
		'mab4':'Броня ног (макс)',
		'tj':'Тяжелая броня',
		'lh':'Легкая броня',
		'minAtack':'Минимальный урон',
		'maxAtack':'Максимальный урон',
		'pa1':'Мф. мощности колющего урона',
		'pa2':'Мф. мощности рубящего урона',
		'pa3':'Мф. мощности дробящий урона',
		'pa4':'Мф. мощности режущий урона',
		'pm1':'Мф. мощности магии огня',
		'pm2':'Мф. мощности магии воздуха',
		'pm3':'Мф. мощности магии воды',
		'pm4':'Мф. мощности магии земли',
		'pm5':'Мф. мощности магии Света',
		'pm6':'Мф. мощности магии Тьмы',
		'pm7':'Мф. мощности серой магии',
		'za':'Защита от урона',
		'zm':'Защита от магии стихий',
		'zma':'Защита от магии',
		'za1':'Защита от колющего урона',
		'za2':'Защита от рубящего урона',
		'za3':'Защита от дробящего урона',
		'za4':'Защита от режущего урона',
		'zm1':'Защита от магии огня',
		'zm2':'Защита от магии воздуха',
		'zm3':'Защита от магии воды',
		'zm4':'Защита от магии земли',
		'zm5':'Защита от магии Света',
		'zm6':'Защита от магии Тьмы',
		'zm7':'Защита от серой магии',
		'mg2static_points':'Уровень заряда (Воздух)',
		'magic_cast':'Дополнительный каст за ход',
		'min_heal_proc':'Эффект лечения (%)',
		'notravma':'Защита от травм', 
		'zaproc':'Защита от урона (%)',
		'zmproc':'Защита от магии стихий (%)',
		'zm2proc':'Защита от магии Воздуха (%)',
		'pza':'Понижение защиты от урона',
		'pzm':'Понижение защиты от магии',
		'pza1':'Понижение защиты от колющего урона',
		'pza2':'Понижение защиты от рубящего урона',
		'pza3':'Понижение защиты от дробящего урона',
		'pza4':'Понижение защиты от режущего урона',
		'pzm1':'Понижение защиты от магии огня',
		'pzm2':'Понижение защиты от магии воздуха',
		'pzm3':'Понижение защиты от магии воды',
		'pzm4':'Понижение защиты от магии земли',
		'pzm5':'Понижение защиты от магии Света',
		'pzm6':'Понижение защиты от магии Тьмы',
		'pzm7':'Понижение защиты от серой магии',
		'speedhp':'Регенерация здоровья (%)',
		'speedmp':'Регенерация маны (%)',
		'tya1':'Колющие атаки',
		'tya2':'Рубящие атаки',
		'tya3':'Дробящие атаки',
		'tya4':'Режущие атаки',
		'tym1':'Огненные атаки',
		'tym2':'Электрические атаки',
		'tym3':'Ледяные атаки',
		'tym4':'Земляные атаки',
		'tym5':'Атаки Света',
		'tym6':'Атаки Тьмы',
		'tym7':'Серые атаки',
		'pog':'Поглощение урона',
		'pog2':'Поглощение урона',
		'pog2p':'Процент поглощение урона',
		'pog2mp':'Цена поглощение урона',
		'maxves':'Увеличивает рюкзак',
		'min_use_mp':'Уменьшает расход маны',
		'bonusexp':'Увеличивает получаемый опыт',
		'speeden':'Регенерация энергии (%)',
		'yza' : 'Уязвимость физическому урона (%)',
		'yzm' : 'Уязвимость магии стихий (%)',
		'yzma' : 'Уязвимость магии (%)',
		'yza1' : 'Уязвимость колющему урона (%)',
		'yza2' : 'Уязвимость рубящему урона (%)',
		'yza3' : 'Уязвимость дробящему урона (%)',
		'yza4' : 'Уязвимость режущему урона (%)',
		'yzm1' : 'Уязвимость магии огня (%)',
		'yzm2' : 'Уязвимость магии воздуха (%)',
		'yzm3' : 'Уязвимость магии воды (%)',
		'yzm4' : 'Уязвимость магии земли (%)',
		'yzm5' : 'Уязвимость магии (%)',
		'yzm6' : 'Уязвимость магии (%)',
		'yzm7' : 'Уязвимость магии (%)',
		'rep': 'Репутация Рыцаря',
		'hpProc':'Уровень жизни (%)',
		'mpProc':'Уровень маны (%)'
	};
	function linkGif (th){
		$('#linkGo').attr('href', 'http://img.combatz.ru/i/obraz/0/'+$('input[name=obraz]').val()).html('Open '+$(th).val()).click();
	}
	function linkPng (th){
		var t = $('input[name=obraz]').val(); t = t.split('.');
		$('#linkGo').attr('href', 'http://img.combatz.ru/chars/0/'+t[0]+'.png').html('Open '+$(th).val()).click();
	}
</script>
<form method="post" action="../../adminion/mod/index.php?mod=dungeon_bots"><hr>
<table valign="top" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td valign="top" >
<table width="400px">
	<tbody>
		<tr>
			<td>ID: &nbsp; </td>
			<td><input name="id_bot" value="<?=$sBot['id'];?>" type="text"></td>
		</tr>
		<tr>
			<td>Логин: &nbsp; </td>
			<td><input name="login" value="<?=$sBot['login'];?>" type="text"></td>
		</tr>
		<tr>
			<td>Имя: &nbsp; </td>
			<td><input name="name" value="<?=$sBot['name'];?>" type="text"></td>
		</tr>
		<tr>
			<td>Уровень: &nbsp; </td>
			<td><input name="level" value="<?=$sBot['level'];?>" type="text"></td>
		</tr>
		<tr>
			<td>Склонность: &nbsp; </td>
			<td><select name="align">
				<option <? if($sBot['align']==0){ echo "selected"; } ?> value="0">Без склонности</option>
				<option <? if($sBot['align']==1){ echo "selected"; } ?> value="1">Светлый</option>
				<option <? if($sBot['align']==2){ echo "selected"; } ?> value="2">Хаосник</option>
				<option <? if($sBot['align']==3){ echo "selected"; } ?> value="3">Темный</option>
				<option <? if($sBot['align']==7){ echo "selected"; } ?> value="7">Нейтральный</option>
				<option <? if($sBot['align']==9){ echo "selected"; } ?> value="9">Марочный</option>
				<option <? if($sBot['align']==10){ echo "selected"; } ?> value="10">Защитник</option>
			</select></td>
		</tr>
		<tr>
			<td>Пол: &nbsp; </td>
			<td><select name="sex"><option <? if($sBot['sex']==0){ echo "selected"; } ?> value="0">Мужской</option><option <? if($sBot['sex']==1){ echo "selected"; } ?> value="1">Женский</option></select></td>
		</tr>
		<tr>
			<td>Образ: &nbsp; </td> <!-- Hors.gif -->
			<td><input name="obraz" value="<?=$sBot['obraz'];?>" type="text"> <input type="button" value="GIF" onclick="linkGif(this)"> <input type="button" value="PNG" onclick="linkPng(this)"> <a style="font-size:11px;" id="linkGo" target="_blank" href="javascript:void();">&nbsp;</a></td>
		</tr>
		<tr>
			<td>Город: &nbsp; </td>
			<td><input name="city_reg" value="<?=$sBot['city_reg'];?>" type="text"> {thiscity}</td>
		</tr>
		<tr>
			<td>Статы: &nbsp; </td>
			<td><textarea style="min-height:60px;width: 270px;" id="statsLoad" name="stats" type="text"><?=$sBot['stats'];?></textarea></td>
		</tr>
		<tr>
			<td>Используемые предметы: &nbsp; </td>
			<td><textarea style="min-height:42px;width: 270px;" id="useItemLoad" name="itemsUse" type="text"><?=$sBot['itemsUse'];?></textarea></td>
		</tr>
		<tr>
			<td>Используемые приемы: &nbsp; </td>
			<td><textarea style="min-height:42px;width: 270px;" name="priemUse" type="text"><?=$sBot['priemUse'];?></textarea></td>
		</tr>
		<tr>
			<td>Дроп: &nbsp; </td>
			<td><textarea style="min-height:42px;width: 270px;" id="dropLoad" name="p_items" type="text"><?=$sBot['p_items'];?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="botSelect" value="<?=$_POST['botSelect']?>">
				<input type="hidden" name="dunSelect" value="<?=$_POST['dunSelect']?>">
				<input type="submit" style="padding: 5px 12px;background: rgb(234, 234, 234);font-size: 14px;" value="Сохранить">
			</td>
		</tr>
	</tbody>
</table>
			</td>
			<td valign="top" width="55%" align="left">
				<style>.brown {
					border-color:#9C8265;
					color:brown;
					font-weight:bold;
				}</style>
				<input type="button" id="editStat" value="Редактировать характеристик"> <input type="button" id="editDrop" value="Редактировать дроп"> <input type="button" id="editUseItem" value="Обмундирование">
				<div id="editorStats"></div>
				<div id="editorDrop"></div>
				<div id="editorUseItem"></div>
				
				<script>
					function loadItemInfo(th){
						$.post('/adminion/lib/loadItemInfo.php', {item_id:$(th).parent('td').children('.items').val()}, function(data){
							$(th).html(data);
						});
					}
					
					function loadListItems(th){
						$.post('/adminion/lib/loadItemInfo.php', {getListItems:true}, function(data){
							$(th).html(data);
						});
					}
					
					function StatsUp(){
						var s1 = parseInt($('.stats[name=s1]').val()) * 6;
						var s2 = parseInt($('.stats[name=s2]').val()) * 6;
						var s3 = parseInt($('.stats[name=s3]').val()) * 4;
						var s4 = (parseInt($('.stats[name=s4]').val()) * 6)+30;
						var s5 = (parseInt($('.stats[name=s5]').val()) * 1);
						var s6 = (parseInt($('.stats[name=s6]').val()) * 10);
						var hpAll = parseInt($('.stats[name=hpAll]').val())+s4;
						var mpAll = parseInt($('.stats[name=mpAll]').val())+s6;

						var maxAtack = parseInt($('.stats[name=maxAtack]').val());
						var minAtack = parseInt($('.stats[name=minAtack]').val());

						var m1 = parseInt($('.stats[name=m1]').val())+s3;
						var m2 = parseInt($('.stats[name=m2]').val())+s3;
						var m3 = parseInt($('.stats[name=m3]').val());
						var m4 = parseInt($('.stats[name=m4]').val())+s2;
						var m5 = parseInt($('.stats[name=m5]').val())+s2;
						var m7 = parseInt($('.stats[name=m7]').val());
						var m10 = parseInt($('.stats[name=m10]').val());
						var m11 = parseInt($('.stats[name=m11]').val());
						var m14 = parseInt($('.stats[name=m14]').val());
						var m15 = parseInt($('.stats[name=m15]').val());

						var za = parseInt($('.stats[name=s4]').val()) * 1.5;
						var zm = parseInt($('.stats[name=s4]').val()) * 1.5;
						var zma = parseInt($('.stats[name=s4]').val()) * 1.5;

						if (s1 >= 100) m10 += 25;
						else if (s1 >= 75) m10 += 17;
						else if (s1 >= 50) m10 += 10;
						else if (s1 >= 25) m10 += 5;
						if (s1 >= 125) {
							maxAtack += 10;
							minAtack += 10;
						}

						if (s5 >= 125) m11 += 35;
						else if (s5 >= 100) m11 += 25;
						else if (s5 >= 75) m11 += 17;
						else if (s5 >= 50) m11 += 10;
						else if (s5 >= 25) m11 += 5;
						
						if (parseInt($('.stats[name=s4]').val()) >= 100) hpAll += 250;
						else if (parseInt($('.stats[name=s4]').val()) >= 75) hpAll += 175;
						else if (parseInt($('.stats[name=s4]').val()) >= 50) hpAll += 100;
						else if (parseInt($('.stats[name=s4]').val()) >= 25) hpAll += 50;


						if (parseInt($('.stats[name=s6]').val()) >= 100) mpAll += 250;
						else if (parseInt($('.stats[name=s6]').val()) >= 75) mpAll += 175;
						else if (parseInt($('.stats[name=s6]').val()) >= 50) mpAll += 100;
						else if (parseInt($('.stats[name=s6]').val()) >= 25) mpAll += 50;
						
						if (parseInt($('.stats[name=s2]').val()) >= 125) {
							m7 += 15;
							m4 += 105;
							m2 += 40;
							m15 += 5;
						} else if (parseInt($('.stats[name=s2]').val()) >= 100) {
							m7 += 15;
							m4 += 105;
							m2 += 40;
						} else if (parseInt($('.stats[name=s2]').val()) >= 75) {
							m7 += 15;
							m4 += 35;
							m2 += 15;
						} else if (parseInt($('.stats[name=s2]').val()) >= 50) {
							m7 += 5;
							m4 += 35;
							m2 += 15;
						} else if (parseInt($('.stats[name=s2]').val()) >= 25) {
							m7 += 5;
						}

						if (parseInt($('.stats[name=s3]').val()) >= 125) {
							m3 += 25;
							m1 += 105;
							m5 += 45;
							m14 += 5;
						} else if (parseInt($('.stats[name=s3]').val()) >= 100) {
							m3 += 25;
							m1 += 105;
							m5 += 45;
						} else if (parseInt($('.stats[name=s3]').val()) >= 75) {
							m3 += 25;
							m1 += 35;
							m5 += 15;
						} else if (parseInt($('.stats[name=s3]').val()) >= 50) {
							m3 += 10;
							m1 += 35;
							m5 += 15;
						} else if (parseInt($('.stats[name=s3]').val()) >= 25) {
							m3 +=10;
						}

						if (parseInt($('.stats[name=s4]').val()) >= 125) za += 25;

						$('.stats[name=minAtack]').val(minAtack);
						$('.stats[name=maxAtack]').val(maxAtack);
						$('.stats[name=m1]').val(m1);
						$('.stats[name=m2]').val(m2);
						$('.stats[name=m3]').val(m3);
						$('.stats[name=m4]').val(m4);
						$('.stats[name=m5]').val(m5);
						$('.stats[name=m7]').val(m7);
						$('.stats[name=m10]').val(m10);
						$('.stats[name=hpAll]').val(hpAll);
						$('.stats[name=mpAll]').val(mpAll);
					}
					function StatsDown(){
						
					}
					// Характеристики 
					$('#add_save').live('click', function(){
						StatsDown();
						result = '';
						$('#editorStats #listInputs .stats').each(function(){
							if (result != '') result +='|';
							result += $(this).attr('name')+'='+$(this).val()+'';
						});
						$('#statsLoad').val(result);
						$('#editorStats').html('');
					});
					$('#add_attr').live('click', function(){
						$('#listInputs .need').append('<tr><td><span style="font-size:11px;" class="'+$('#add_list').val() +'">'+ CharacterInfo[$('#add_list').val()] +':</span></td><td> <input class="stats" value="0" type="text" name="'+ $('#add_list').val() +'"/><input type="button" name="'+$('#add_list').val() +'" class="delete" value="X"><br/></td></tr>');
					});
					
					$('#listInputs .delete').live('click', function() {
						$(this).parent().parent().remove();
					});
					
					$('#editStat').live('click', function() {
						if ($('#editorStats').html() != '') {
							$('#editorStats').html('');
						} else {
							var text = '';
							$.each(CharacterBased, function( n, row ){
								if (row=='zonb') {
									text += '<tr><td><span style="font-size:11px;" class="'+row+'">'+CharacterInfo[row] + ':</span></td><td> <select class="stats brown" name="'+row+'"><option value="1">3</option><option value="0">2</option><option value="-1">1</option></select> <input type="button" name="'+row+'" class="delete" value="X"><br/></td></tr>';
								} else {
									text += '<tr><td><span style="font-size:11px;" class="'+row+'">'+CharacterInfo[row] + ':</span></td><td> <input class="stats brown" value="1" type="text" name="'+row+'"><input type="button" name="'+row+'" class="delete" value="X"><br/></td></tr>';
								}
							});
							var add_list = '';
							$.each(CharacterInfo, function( k , v ){
								add_list += '<option value="'+k+'">'+v+'</option>';
							});
							$('#editorStats').html(' <div id="listInputs" ><table class="need" width="400px" valign="top" align="left" border="0" cellspacing="0" cellpadding="0"> '+text + ' </table><table><tr><td colspan="2"><hr></td></tr><tr><td><input type="button" value="Добавить" name="add" id="add_attr"></td><td> <select id="add_list">'+add_list+'</select> <br/> </td></tr><tr><td colspan="2"><hr></td></tr><tr><td colspan="2"><input type="button" value="Внести изменения" style="color:brown;" name="add" id="add_save"></td></tr></table></div> ');
							text='';
							stats = $('#statsLoad').val();
							stats = stats.split('|');
							stats.sort();
							$.each(stats, function( n, row ){
								row = row.split('=');
								
								if ( $('input[name='+row[0]+'].stats').val() > 0 || row[0]=='zonb'){
									
										
									if (row[0]=='zonb') {  
										$('select[name='+row[0]+'].stats').val(row[1]);
										$('select[name='+row[0]+'].stats').removeClass('brown');
									} else {
										$('input[name='+row[0]+'].stats').val(row[1]);
										$('input[name='+row[0]+'].stats').removeClass('brown');
									}
								} else {
									text += '<tr><td><span style="font-size:11px;" class="'+row[0]+'">'+CharacterInfo[row[0]] + ':</span></td><td> <input class="stats" value="' + row[1] + '" type="text" name="'+row[0]+'"><input type="button" name="'+row[0]+'" class="delete" value="X"><br/></td></tr>';
								}
							});
							$('#listInputs .need').append(text);
							StatsUp();
						}
					});
					
					
					// Дроп предметов
					$('#add_saveDrop').live('click', function(){
						result = '';
						$('#editorDrop #listInputs td').each(function() {
							if (result != '') result +='|';
							result += $(this).children('input[name=itemsId]').val()+'='+$(this).children('input[name=itemsPerc]').val()+'';
							if ($(this).children('input[name=itemsQuest]').val()) result += '='+$(this).children('input[name=itemsQuest]').val()+'';
						});
						$('#dropLoad').val(result);
						$('#editorDrop').html('');
					});
					
					$('#editDrop').live('click', function() {
						if ($('#editorDrop').html() != '') {
							$('#editorDrop').html('');
						} else {
							var text = '';
							drop = $('#dropLoad').val();
							drop = drop.split('|');
							drop.sort();
							$.each(drop, function( n, row ){
								row = row.split('='); //
								if (row[1] == undefined) row[1] = '';
								if (row[2] == undefined) row[2] = '';
								if (row[0] !='') {
									text += '<tr><td><input class="items" value="' + row[0] + '" type="text" name="itemsId" size="7"> <input size="5" class="itemsPerc" value="' + row[1] + '" type="text" name="itemsPerc"><input class="itemsQuest" size="14" value="' + row[2] + '" type="text" name="itemsQuest"><input type="button" name="delete" class="delete" value="X"> <a target="_blank" href="javascript:void(0);" id="'+ row[0] +'" onclick="loadItemInfo(this);">[load]</a> <br/></td></tr>';
								}
							});
							$('#editorDrop').html(' <div id="listInputs" ><table class="need" width="100%" valign="top" align="left" border="0" cellspacing="0" cellpadding="0"> '+text + ' </table><br/><hr/><input type="button" value="Внести изменения" style="color:brown;" name="add" id="add_saveDrop"> </div> ');
						}
					});

					// Обмундирование
					$('#add_saveUseItem').live('click', function(){
						result = '';
						$('#editorUseItem #listInputs td').each(function() {
							if (result != '') result +=',';
							result += $(this).children('input[name=itemsId]').val();
						});
						$('#useItemLoad').val(result);
						$('#editorUseItem').html('');
					});
					
					$('#add_useitem').live('click', function(){
						$('#editorUseItem #listInputs .need').append('<tr><td><input class="items useItem" value="0" type="text" name="itemsId" size="7"> <input type="button" name="delete" class="delete" value="X">  <br/></td></tr>');
					});
					
					$('#editUseItem').live('click', function() {
						if ($('#editorUseItem').html() != '') {
							$('#editorUseItem').html('');
						} else {
							var text = '';
							useItem = $('#useItemLoad').val();
							useItem = useItem.split(',');
							useItem.sort();
							$.each(useItem, function( n, row ){
								text += '<tr><td><input class="items useItem" value="' + row + '" type="text" name="itemsId" size="7"> <input type="button" name="delete" class="delete" value="X"> <a href="javascript:void(0);" id="'+ row +'" onclick="loadItemInfo(this);">[load]</a> or <a target="_blank" href="http://lib.combatz.ru/items_info.php?id='+ row +'" id="'+ row +'">[lib]</a>  <br/></td></tr>';
							});
							$('#editorUseItem').html(' <div id="listInputs" ><table class="need" width="100%" valign="top" align="left" border="0" cellspacing="0" cellpadding="0"> '+text + ' </table><hr><input type="button" value="Добавить еще предмет" name="add" id="add_useitem"> <br/><hr/><input type="button" value="Внести изменения" style="color:brown;" name="add" id="add_saveUseItem"> </div> ');
						}
					});
					
					$(document).ready(function(){
					});
				</script>
			</td>
		</tr>
		<tr>
			<td align="right">
			</td>
			<td align="right">
				<div id="testS"></div>
			</td>
		</tr>
	</tbody>
</table>
</form>
<?
}
?>