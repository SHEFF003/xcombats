<?
if(!defined('GAME'))
{
	die();
}
?>
<TABLE width=99% cellspacing=0 cellpadding=0 align=center>
<FORM METHOD=POST ACTION="main.php?anketa=1" name='FORM1' id="FORM1"><INPUT type=hidden name="sd4" value="<?=$u->info['id']?>">
<TR>
<TD width=100%><h3>Анкета персонажа "<?=$u->info['login']?>"</TD>
<TD valign=top>
<!-- <INPUT TYPE=button value="Подсказка" style="background-color:#A9AFC0" onclick="window.open('/encicl/help/psw.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')">&nbsp;--><INPUT TYPE=button value="Вернуться" style='width: 75px' onclick='location="/main.php"'>
</TD>
</TR></TABLE>
<?
$dateofbirth  = '';
if(isset($_POST['name']))
{
	$_POST['name'] = htmlspecialchars($_POST['name'],NULL,'cp1251');
	if($filter->spamFiltr($_POST['name'])!='0' || $filter->spamFiltr($_POST['city'])!='0' || $filter->spamFiltr($_POST['city2'])!='0' || $filter->spamFiltr($_POST['homepage'])!='0' || $filter->spamFiltr($_POST['about'])!='0' || $filter->spamFiltr($_POST['hobby'])!='0')
	{
		mysql_query('UPDATE `users` SET `info_delete` = "'.(time()+2592000).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
	$_POST['city'] = htmlspecialchars($_POST['city'],NULL,'cp1251');
	$_POST['city2'] = htmlspecialchars($_POST['city2'],NULL,'cp1251');
	$_POST['icq'] = htmlspecialchars($_POST['icq'],NULL,'cp1251');
	if(!isset($_POST['mat'])) { $_POST['mat'] == NULL; }
	if(!isset($_POST['hide_icq'])){ $_POST['hide_icq'] = NULL; }
	$_POST['skype'] = htmlspecialchars($_POST['skype'],NULL,'cp1251');
	if(!isset($_POST['hide_skype'])){ $_POST['hide_skype'] = NULL; }else{ $_POST['hide_skype'] = 1; }
	$_POST['hide_icq'] = htmlspecialchars($_POST['hide_icq'],NULL,'cp1251');
	$_POST['homepage'] = htmlspecialchars($_POST['homepage'],NULL,'cp1251');
	$_POST['about'] = htmlspecialchars($_POST['about'],NULL,'cp1251');
	if( $u->info['admin'] == 0 ) {
		$_POST['hobby'] = htmlspecialchars($_POST['hobby'],NULL,'cp1251');
	}
	$_POST['ChatColor'] = htmlspecialchars($_POST['ChatColor'],NULL,'cp1251');
	
	$_POST['hobby']= str_replace("\\n","<BR>",$_POST['hobby']);
	$_POST['hobby']= str_replace("\\r","",$_POST['hobby']);
	$_POST['hobby']= str_replace("&lt;BR&gt;","<BR>",$_POST['hobby']);
	$simbolcount = strlen($_POST['hobby']);
	if (isset($_POST['ChatColor']) && $u->info['admin']=='0' && !($_POST['ChatColor'] == "Black" || $_POST['ChatColor'] == "Blue" || $_POST['ChatColor'] == "Lilac" ||  $_POST['ChatColor'] == "Fuchsia" || $_POST['ChatColor'] == "Gray" || $_POST['ChatColor'] == "Green" || $_POST['ChatColor'] == "Maroon" || $_POST['ChatColor'] == "Navy" || $_POST['ChatColor'] == "Olive" || $_POST['ChatColor'] == "Purple" || $_POST['ChatColor'] == "Teal" ||  $_POST['ChatColor'] == "Orange" ||  $_POST['ChatColor'] == "Chocolate" || $_POST['ChatColor'] == "DarkKhaki" || $_POST['ChatColor'] == "SandyBrown")) {
						echo "<div align=\"left\" style=\"color:#FF0000 \">Возможно использовать цвета указанные только в меню анкеты ! </div>";
						$_POST['ChatColor'] = "Black";
	}
	if(!$_POST['city']){$city=$_POST['city2'];}
	elseif($_POST['city'] && $_POST['city2']){$city=$_POST['city2'];}
	else{$city=$_POST['city'];}
}
if(isset($_POST['saveanketa'])) {
if( $u->info['level'] <= 1 ) {
	$dt = explode('.',$_POST['0day']);
	if(isset($dt[0],$dt[1],$dt[2]))
	{
		$erd = 0;
		$dt[0] = round($dt[0]);
		$dt[1] = round($dt[1]);
		$dt[2] = round($dt[2]);
		if($dt[0]<1 || $dt[0]>31)
		{
			$erd = 1;
		}
		if($dt[1]<1 || $dt[1]>12)
		{
			$erd = 2;
		}
		if($dt[2]<1920 || $dt[2]>2006)
		{
			$erd = 3;
		}
		if($erd==0)
		{
			$_POST['0day'] = $dt[0].'.'.$dt[1].'.'.$dt[2];
			$dateofbirth = "`bithday` = '".mysql_real_escape_string($_POST['0day'])."',";
		}else{
			//ошибка
			
		}
	}
	
	$u->info['bithday'] = $_POST['0day'];
}
$st = $u->lookStats($u->info['stats']);
$maxsimbols = 1024+($st['os6']*200);
	if($_POST['mat'] == 1 ) {
		$_POST['mat'] = 1;
	}else{
		$_POST['mat'] = 0;
	}
	if($simbolcount>$maxsimbols && $u->info['admin'] == 0) {
		echo '<div align="left" style="color:#FF0000 ">Максимальный размер поля "Увлечения / хобби" - '.$maxsimbols.' символов.</div>';
	}elseif(mysql_query("UPDATE 
						`users` 
					SET 
						`name` = '".mysql_real_escape_string($_POST['name'])."',
						`city_real` = '".mysql_real_escape_string($_POST['city2'])."',
						`icq` = '".mysql_real_escape_string((int)$_POST['icq'])."',
						`icq_hide` = '".mysql_real_escape_string($_POST['hide_icq'])."',
						`skype` = '".mysql_real_escape_string($_POST['skype'])."',
						`skype_hide` = '".mysql_real_escape_string($_POST['hide_skype'])."',
						`homepage` = '".mysql_real_escape_string($_POST['homepage'])."',
						".$dateofbirth."
						`deviz` = '".mysql_real_escape_string($_POST['about'])."',
						`hobby` = '".mysql_real_escape_string($_POST['hobby'])."',
						`chatColor` = '".mysql_real_escape_string($_POST['ChatColor'])."',
						`mat` = '".mysql_real_escape_string((int)$_POST['mat'])."'
					WHERE 
						`id` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1;"))
{echo '<div align="left" style="color:#FF0000 ">Все прошло удачно...</div>';
$u->info['name'] = $_POST['name'];
$u->info['city_real'] = $city;
$u->info['icq'] = $_POST['icq'];
$u->info['icq_hide'] = $_POST['hide_icq'];
$u->info['skype'] = $_POST['skype'];
$u->info['skype_hide'] = $_POST['hide_skype'];
$u->info['homepage'] = $_POST['homepage'];
$u->info['deviz'] = $_POST['about'];
$u->info['hobby'] = $_POST['hobby'];
$u->info['chatColor'] = $_POST['ChatColor'];
$u->info['mat'] = $_POST['mat'];
}
else{echo 'что-то не так...';}
}
?>

<table width="95%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#B2B2B2" name="F1">
<tr bgcolor="#D3D2D0">
<td>Ваше реальное имя: </td>
<td><table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
<td><input name="name" value="<?=$u->info['name']?>" class="inup" size="45" maxlength="90" />
<div style="float:right"><font color="#999999">ID Персонажа:</font> <?=$u->info['id']?>&nbsp;</div>
</td>
</tr>
</table></td>
</tr>
<? if($u->info['level']<=1) { ?>
<tr bgcolor="#D3D2D0">
<td>День рождения:</td>
<td><script language="JavaScript" type="text/javascript">
function procdays (month) {
var selected = document.getElementById('dd').value;
if (selected == "") selected=1;
document.getElementById('dd').length = 0;
var days = new Array(3,0,3,2,3,2,3,3,2,3,2,3);
if (Math.round(document.getElementById('yyyy').value/4) == document.getElementById('yyyy').value/4) {days[1]=1;}
var ind = parseFloat(month.value)-1;
if (ind < 0) ind=0;
var base = 29 + days[ind];
if (selected>(base-1)) {selected=1;}
for (var i=1; i<base; i++) {
var myday = document.createElement("option");
myday.value = i;
myday.text = i;
document.getElementById('dd').add(myday);
}
document.getElementById('dd').value = selected;
genZerodate();
return true;
}
function genZerodate () {
var ss=document.getElementById('dd').value;
if (ss.length < 2) ss='0'+ss;
var str = ss+'.'+document.getElementById('mm').value+'.'+document.getElementById('yyyy').value;
document.getElementById('nhya').value = str;
return true;
}
</script>
День:
<select name="DD" id="dd" class="inup" onchange="genZerodate();">
<script>
var s="";
for (i=1; i<=31; i++) {
s+='<option value="'+i+'">'+i+'</option>';
}
document.write(s);
</script>
</select>
Месяц:
<select name="MM" onchange="procdays(this);"  class="inup" id="mm">
<option value="01" selected="selected">Январь</option>
<option value="02">Февраль</option>
<option value="03">Март</option>
<option value="04">Апрель</option>
<option value="05">Май</option>
<option value="06">Июнь</option>
<option value="07">Июль</option>
<option value="08">Август</option>
<option value="09">Сентябрь</option>
<option value="10">Октябрь</option>
<option value="11">Ноябрь</option>
<option value="12">Декабрь</option>
</select>
Год:
<select name="YYYY" class="inup" onchange="procdays(document.getElementById('mm'));" id="yyyy">
<script>
var s="";
for (i=<?=(date('Y')-10)?>; i>=<?=(date('Y')-80)?>; i--) {
s+='<option value="'+i+'">'+i+'</option>';
}
document.write(s);
</script>
</select>
<input type="text" name="0day" id="nhya" value="<?=$u->info['bithday']?>" style="width:0px; height:0px; visibility:hidden" />
<script>
var s=document.getElementById('nhya');
s=s.value.split(".");
if (s.length > 0) {
s[0]=parseFloat(s[0]);
FORM1.DD.value=s[0];
}
if (s.length > 1) {
s[1]=parseFloat(s[1]);
if (s[1] < 10 ) s[1]='0'+s[1];
FORM1.MM.value=s[1];
}
if (s.length > 2) {
s[2]=parseFloat(s[2]);
if (s[2] < 10 ) {s[2]='200'+s[2];} else {
if (s[2] < 100 ) s[2]='19'+s[2];
}
FORM1.YYYY.value=s[2];
}
procdays(document.getElementById('mm'));
</script>
<small><BR><span class="style5">Внимание! </span><span class="style7">Дата рождения должна быть правильной, она используется в игровом процессе. Анкеты с неправильной датой будут удаляться без предупреждения.</span></small>
</td>
</tr>

<?}?>
<tr bgcolor="#D3D2D0">
  <td>Город: </td>
  <td><select name="city" class="inup">
    <option selected="selected"></option>
    <option>Москва</option>
    <option>Санкт-Петербург</option>
    <option>Абакан (Хакасия)</option>
    <option>Азов</option>
    <option>Аксай (Ростовская обл.)</option>
    <option>Алания</option>
    <option>Альметьевск</option>
    <option>Амурск</option>
    <option>Анадырь</option>
    <option>Анапа</option>
    <option>Ангарск (Иркутская обл.)</option>
    <option>Апатиты</option>
    <option>Армавир</option>
    <option>Архангельск</option>
    <option>Асбест</option>
    <option>Астрахань</option>
    <option>Балашиха</option>
    <option>Барнаул</option>
    <option>Белгород</option>
    <option>Беломорск (Карелия)</option>
    <option>Березники (Пермская обл.)</option>
    <option>Бийск</option>
    <option>Биробиджан</option>
    <option>Благовещенск</option>
    <option>Большой камень</option>
    <option>Борисоглебск</option>
    <option>Братск</option>
    <option>Бронницы</option>
    <option>Брянск</option>
    <option>Ванино</option>
    <option>Великие Луки</option>
    <option>Великий Устюг</option>
    <option>Верхняя Салда</option>
    <option>Владивосток</option>
    <option>Владикавказ</option>
    <option>Владимир</option>
    <option>Волгоград</option>
    <option>Волгодонск</option>
    <option>Волжск</option>
    <option>Вологда</option>
    <option>Волхов (С.Птрбрг обл.)</option>
    <option>Воронеж</option>
    <option>Воскресенск</option>
    <option>Воткинск</option>
    <option>Выборг</option>
    <option>Вязьма (Смоленская обл.)</option>
    <option>Вятские Поляны</option>
    <option>Гаврилов-Ям</option>
    <option>Геленджик</option>
    <option>Георгиевск</option>
    <option>Голицино (Московская обл.)</option>
    <option>Губкин</option>
    <option>Гусь-Хрустальный</option>
    <option>Дзержинск (Нижгрдск обл.)</option>
    <option>Димитровград</option>
    <option>Долгопрудный</option>
    <option>Дубна</option>
    <option>Дудинка (Эвенкская АО)</option>
    <option>Ейск</option>
    <option>Екатеринбург</option>
    <option>Елабуга (Татарстан)</option>
    <option>Елец (Липецкая обл.)</option>
    <option>Елизово</option>
    <option>Железногорск</option>
    <option>Жуков (Калужской обл.)</option>
    <option>Жуковский</option>
    <option>Заречный</option>
    <option>Звенигород</option>
    <option>Зеленогорск</option>
    <option>Зеленоград</option>
    <option>Зеленодольск</option>
    <option>Златоуст</option>
    <option>Иваново</option>
    <option>Ивантеевка (Мсквск обл.)</option>
    <option>Ижевск</option>
    <option>Иркутск</option>
    <option>Ишим</option>
    <option>Йошкар-Ола</option>
    <option>Казань</option>
    <option>Калининград</option>
    <option>Калуга</option>
    <option>Каменск-Уральский</option>
    <option>Карталы</option>
    <option>Кемерово</option>
    <option>Кинешма (Ивановская обл.)</option>
    <option>Кириши ( С.Птрбрг обл.)</option>
    <option>Киров</option>
    <option>Кирово-Чепецк</option>
    <option>Кисловодск</option>
    <option>Ковров</option>
    <option>Когалым</option>
    <option>Коломна</option>
    <option>Комсомольск-на-Амуре</option>
    <option>Королев</option>
    <option>Костомукша</option>
    <option>Кострома</option>
    <option>Красногорск</option>
    <option>Краснодар</option>
    <option>Красноярск</option>
    <option>Кронштадт</option>
    <option>Кропоткин</option>
    <option>Кумертау (Башкортостан)</option>
    <option>Курган</option>
    <option>Курск</option>
    <option>Кустанай</option>
    <option>Кызыл</option>
    <option>Липецк</option>
    <option>Лыткарино (Московская обл.)</option>
    <option>Люберцы</option>
    <option>Магадан</option>
    <option>Магнитогорск</option>
    <option>Майкоп</option>
    <option>Малоярославец</option>
    <option>Махачкала</option>
    <option>Медвежьегорск</option>
    <option>Междуреченск (Кмрвск обл.)</option>
    <option>Менделеевск</option>
    <option>Миасс</option>
    <option>Миллерово (Ростовская обл.)</option>
    <option>Минеральные Воды</option>
    <option>Мичуринск (Тамбовская обл.)</option>
    <option>Мурманск</option>
    <option>Муром</option>
    <option>Мытищи</option>
    <option>Набережные Челны</option>
    <option>Надым</option>
    <option>Нальчик</option>
    <option>Находка</option>
    <option>Невинномысск</option>
    <option>Нефтекамск</option>
    <option>Нефтеюганск</option>
    <option>Нижневартовс</option>
    <option>Нижнекамск</option>
    <option>Нижний Новгород</option>
    <option>Нижний Тагил</option>
    <option>Николаевск-на-Амуре</option>
    <option>Николаевск</option>
    <option>Новгород</option>
    <option>Новокузнецк</option>
    <option>Новомосковск</option>
    <option>Новороссийск</option>
    <option>Новосибирск</option>
    <option>Новоуральск</option>
    <option>Новочеркасск</option>
    <option>Новый Уренгой</option>
    <option>Норильск</option>
    <option>Ноябрьск</option>
    <option>Нягань</option>
    <option>Обнинск</option>
    <option>Одинцово</option>
    <option>Омск</option>
    <option>Онега</option>
    <option>Орел</option>
    <option>Оренбург</option>
    <option>Орск</option>
    <option>Пенза</option>
    <option>Первоуральск</option>
    <option>Переславль-Залесский</option>
    <option>Пермь</option>
    <option>Петрозаводск</option>
    <option>Петропавловск-Камч.</option>
    <option>Пластун (Приморский край)</option>
    <option>Подольск</option>
    <option>Полевской</option>
    <option>Полярные Зори</option>
    <option>Протвино</option>
    <option>Псков</option>
    <option>Пущино</option>
    <option>Пятигорск</option>
    <option>Радужный (Тюменская обл.)</option>
    <option>Ревда</option>
    <option>Ржев</option>
    <option>Ростов-на-Дону</option>
    <option>Ростов-Ярославский</option>
    <option>Рубцовск</option>
    <option>Рязань</option>
    <option>Салехард</option>
    <option>Самара</option>
    <option>Саранск</option>
    <option>Саратов</option>
    <option>Саров</option>
    <option>Сасово</option>
    <option>Себеж (Псковская обл.)</option>
    <option>Северодвинск</option>
    <option>Северск (Томская обл.)</option>
    <option>Сегежа</option>
    <option>Семикаракорск</option>
    <option>Сергиев Посад</option>
    <option>Серов</option>
    <option>Серпухов</option>
    <option>Сестрорецк (С.Птрбрг обл.)</option>
    <option>Смоленск</option>
    <option>Снежинск</option>
    <option>Советская Гавань</option>
    <option>Советский (Тюменская обл.)</option>
    <option>Солнечногорск</option>
    <option>Сосновый Бор</option>
    <option>Сосновый Бор (С.Птрбрг обл.)</option>
    <option>Сочи</option>
    <option>Ставрополь</option>
    <option>Старая Русса</option>
    <option>Старый Оскол</option>
    <option>Стерлитамак (Башкортостан)</option>
    <option>Стрежевой (Томская обл.)</option>
    <option>Строгино</option>
    <option>Сургут</option>
    <option>Сызрань</option>
    <option>Сыктывкар</option>
    <option>Таганрог</option>
    <option>Тамбов</option>
    <option>Таруса</option>
    <option>Тверь</option>
    <option>Тольятти</option>
    <option>Томск</option>
    <option>Трехгорный</option>
    <option>Троицк</option>
    <option>Туапсе</option>
    <option>Тула</option>
    <option>Тюмень</option>
    <option>Удомля (Тверская обл.)</option>
    <option>Улан-Удэ</option>
    <option>Ульяновск</option>
    <option>Уссурийск</option>
    <option>Усть-Лабинск (Крсндрскй край)</option>
    <option>Уфа</option>
    <option>Ухта</option>
    <option>Фрязино</option>
    <option>Хабаровск</option>
    <option>Ханты-Мансийск</option>
    <option>Химки</option>
    <option>Холмск</option>
    <option>Чебаркуль</option>
    <option>Чебоксары</option>
    <option>Челябинск</option>
    <option>Череповец</option>
    <option>Черкесск</option>
    <option>Черноголовка</option>
    <option>Чернушка (Пермская обл.)</option>
    <option>Черняховск (Клннгрдск обл.)</option>
    <option>Чита</option>
    <option>Шадринск (Курганская обл.)</option>
    <option>Шатура</option>
    <option>Шахты</option>
    <option>Щелково (Московская обл.)</option>
    <option>Электросталь</option>
    <option>Элиста</option>
    <option>Энгельс</option>
    <option>Южно-Сахалинск</option>
    <option>Южноуральск</option>
    <option>Юрга</option>
    <option>Якутск</option>
    <option>Ярославль</option>
    <option>Азербайджан</option>
    <option>Беларусь</option>
    <option>Грузия</option>
    <option>Казахстан</option>
    <option>Кыргызстан</option>
    <option>Армения</option>
    <option>Латвия</option>
    <option>Литва</option>
    <option>Таджикистан</option>
    <option>Туркменистан</option>
    <option>Узбекистан</option>
    <option>Украина</option>
    <option>Эстония</option>
    <option>Германия/Germany</option>
    <option>Израиль/Israel</option>
    <option>Канада/Canada</option>
    <option>США/USA</option>
    </select>
    &nbsp;&nbsp;&nbsp;другой&nbsp;&nbsp;&nbsp;
    <input type="text" value="<?=$u->info['city_real']?>" name="city2" size="20" maxlength="40" class="inup" /></td>
</tr>
<tr bgcolor="#D3D2D0">
  <td>ICQ:</td>
  <td><input value="<? if($u->info['icq']>0) {echo $u->info['icq'];}?>" name="icq" class="inup" size="9" maxlength="20" />
    <input type="checkbox" name='hide_icq' value="1" <?if($u->info['icq_hide']==1){echo'checked';}?> />
    не отображать в инф. о персонаже.</td>
</tr>
<tr bgcolor="#D3D2D0">
  <td>Домашняя страница:</td>
  <td><input value="<?=$u->info['homepage']?>" name="homepage" class="inup" size="35" maxlength="60" /></td>
</tr>
<tr bgcolor="#D3D2D0">
<td>Девиз:</td>
<td><input value="<?=$u->info['deviz']?>" name="about" class="inup" size="60" maxlength="160" /></td>
</tr>
<tr bgcolor="#D3D2D0">
<td colspan="2" align="left">Увлечения / хобби <small>(не более 60 слов)</small><BR>
<textarea name="hobby" cols="60" rows="7" class="inup" style='width:95%'><?=$u->info['hobby']?></textarea></td>
</tr>
<tr bgcolor="#D3D2D0">
<td>Цвет сообщений в чате:</td>
<td><select name="ChatColor" class="inup">

<option
style="BACKGROUND: #f2f0f0; COLOR: black" value="Black"
selected="selected">Black</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: blue"
value="Blue">Blue</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: fuchsia"
value="Fuchsia">Fuchsia</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: gray"
value="Gray">Grey</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: green"
value="Green">Green</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: maroon"
value="Maroon">Maroon</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: navy"
value="Navy">Navy</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: olive"
value="Olive">Olive</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: purple"
value="Purple">Purple</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: teal"
value="Teal">Teal</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: orange"
value="Orange">Orange</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: chocolate"
value="Chocolate">Chocolate</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: darkkhaki"
value="DarkKhaki">DarkKhaki</option>
<option
style="BACKGROUND: #f2f0f0; COLOR: sandybrown"
value="SandyBrown">SandyBrown</option>
<?
if($u->info['admin']>0){
echo '<option
style="BACKGROUND: #f2f0f0; COLOR: red"
value="Red">Red</option>';
}
?>
</select>
<script language="javascript" type="text/javascript">FORM1.ChatColor.value="<?=$u->info['chatColor']?>";</script></td>
</tr>
<tr bgcolor="#D3D2D0">
  <td colspan="2" align="center"><p align="center">
  <input name="saveanketa" type="submit" value="Сохранить изменения" />
  </p>
</tr>
</form>
</table>
<DIV><!--тут рейтинг--></DIV>
</body>