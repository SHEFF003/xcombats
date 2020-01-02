<?
define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

include('battle_cfg.php');

$i = 0;
while( $i < 100 ) {
	if(!isset($c['battle_cfg'][$i])) {
		$c['battle_cfg'][$i] = 0;
	}
	$i++;
}

if($u->info['admin'] > 0) {
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Настройка боевой системы</title>
<script src="http://img.xcombats.com/js/Lite/gameEngine.js" type="text/javascript"></script>
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/ion.rangeSlider.js"></script>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/normalize.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
<style type="text/css">
h3 {
	text-align: center;
}
.CSSteam	{ font-weight: bold; cursor:pointer; }
.CSSteam0	{ font-weight: bold; cursor:pointer; }
.CSSteam1	{ font-weight: bold; color: #6666CC; cursor:pointer; }
.CSSteam2	{ font-weight: bold; color: #B06A00; cursor:pointer; }
.CSSteam3 	{ font-weight: bold; color: #269088; cursor:pointer; }
.CSSteam4 	{ font-weight: bold; color: #A0AF20; cursor:pointer; }
.CSSteam5 	{ font-weight: bold; color: #0F79D3; cursor:pointer; }
.CSSteam6 	{ font-weight: bold; color: #D85E23; cursor:pointer; }
.CSSteam7 	{ font-weight: bold; color: #5C832F; cursor:pointer; }
.CSSteam8 	{ font-weight: bold; color: #842B61; cursor:pointer; }
.CSSteam9 	{ font-weight: bold; color: navy; cursor:pointer; }
.CSSvs 		{ font-weight: bold; }
</style>
</head>

<body bgcolor="#E2E0E0">
<H3>Настройка боевого балансa<br><br><a href="/battle_editor.php" class="btnnew">Обновить</a></H3>
<br>
<?
if( isset($_POST['val1']) ) {	
	$i = 1;
	$html = '<? $c["battle_cfg"] = array(0 => 0';
	while( $i < 100 ) {
		$val = 0;
		if(isset($_POST['val'.$i])) {
			$val = round((int)$_POST['val'.$i]);
		}
		$c['battle_cfg'][$i] = $val;
		$html .= ','.$i.' => '.$val.'';
		$i++;
	}
	$html .= '); ?>';

	$f = fopen("battle_cfg.php", "w");
	fwrite($f, $html); 
	fclose($f);

	echo '<div><b><font color="red">Успешно сохранено</font></b></div>';
}
?>
<hr>
<form method="post" action="battle_editor.php">
<center>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;Мф. уворота:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val1" id="val1"></div>
<script>
    $(function () {
        $("#val1").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][1]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;Мф. против уворота:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val2" id="val2"></div>
<script>
    $(function () {
        $("#val2").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][2]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<hr>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;Мф. крита:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val3" id="val3"></div>
<script>
    $(function () {
        $("#val3").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][3]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;Мф. против крита:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val4" id="val4"></div>
<script>
    $(function () {
        $("#val4").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][4]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<hr>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;<b>Урон воинов (в итоге)</b>:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val5" id="val5"></span></div>
<script>
    $(function () {
        $("#val5").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][5]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;<b>Урон магов (в итоге)</b>:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val6" id="val6"></span></div>
<script>
    $(function () {
        $("#val6").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][6]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<hr>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;Эффективность защиты от урона:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val7" id="val7"></div>
<script>
    $(function () {
        $("#val7").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][7]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;Эффективность защиты от магии:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val8" id="val8"></div>
<script>
    $(function () {
        $("#val8").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][8]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;">&nbsp;&nbsp;Эффективность брони:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val9" id="val9"></div>
<script>
    $(function () {
        $("#val9").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][9]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<hr>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Колющий урон:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val10"
id="val10"></span></div>
<script>
    $(function () {
        $("#val10").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][10]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Рубящий урон:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val11"
id="val11"></span></div>
<script>
    $(function () {
        $("#val11").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][11]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Режущий урон:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val12"
id="val12"></span></div>
<script>
    $(function () {
        $("#val12").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][12]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Дробящий урон:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val13"
id="val13"></span></div>
<script>
    $(function () {
        $("#val13").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][13]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<hr>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон магией Огня:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val14"
id="val14"></span></div>
<script>
    $(function () {
        $("#val14").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][14]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон магией Воды:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val15"
id="val15"></span></div>
<script>
    $(function () {
        $("#val15").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][15]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон магией Земли:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val16"
id="val16"></span></div>
<script>
    $(function () {
        $("#val16").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][16]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон магией Воздуха:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val17"
id="val17"></span></div>
<script>
    $(function () {
        $("#val17").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][17]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<hr>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Эффективность пробоя брони:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val18"
id="val18"></span></div>
<script>
    $(function () {
        $("#val18").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][18]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Эффективность парирования:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val19"
id="val19"></span></div>
<script>
    $(function () {
        $("#val19").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][19]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Эффективность контрудара:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val20"
id="val20"></span></div>
<script>
    $(function () {
        $("#val20").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][20]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Эффективность блока щитом:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val21"
id="val21"></span></div>
<script>
    $(function () {
        $("#val21").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][21]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<hr>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Зависимость урона от умелок:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val22"
id="val22"></span></div>
<script>
    $(function () {
        $("#val22").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: 0,
            max: 50,
            from: <?=$c['battle_cfg'][22]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<?
				/*
					18 - нож \ кинжал
					19 - топор \ секира
					20 - молот \ дубина
					21 - меч \ клинок
					22 - магический посох
					23 - лук
					24 - арбалет
					25 - боеприпасы \ стрелы
					26 - костыли
					27 - легендарное оружие
					28 - цветы \ букеты \ ёлки
				*/
?>
<br>
<hr>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу нож\кинжал:&nbsp;&nbsp; </span><div style="display:inline-block;width:500px;"><input type="text" name="val23"
id="val23"></span></div>
<script>
    $(function () {
        $("#val23").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][23]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу топор\секира:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val24"
id="val24"></span></div>
<script>
    $(function () {
        $("#val24").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][24]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу молот\дубина:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val25"
id="val25"></span></div>
<script>
    $(function () {
        $("#val25").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][25]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу меч\клинок:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val26"
id="val26"></span></div>
<script>
    $(function () {
        $("#val26").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][26]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу посох:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val27"
id="val27"></span></div>
<script>
    $(function () {
        $("#val27").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][27]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;-пустой параметр-:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val28"
id="val28"></span></div>
<script>
    $(function () {
        $("#val28").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][28]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;-пустой параметр-:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val29"
id="val29"></span></div>
<script>
    $(function () {
        $("#val29").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][29]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;-пустой параметр-:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val30"
id="val30"></span></div>
<script>
    $(function () {
        $("#val30").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][30]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу костыли:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val31"
id="val31"></span></div>
<script>
    $(function () {
        $("#val31").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][31]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу легендарное оружие:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val32"
id="val32"></span></div>
<script>
    $(function () {
        $("#val32").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][32]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br>
<span style="width:244px;text-align:center;display:inline-block;vertical-align:bottom;margin-bottom:23px;"
>&nbsp;&nbsp;Урон оружием по типу цветы\букеты\елки:&nbsp;&nbsp; </span>
<div style="display:inline-block;width:500px;"><input type="text" name="val33"
id="val33"></span></div>
<script>
    $(function () {
        $("#val33").ionRangeSlider({
            hide_min_max: false,
            keyboard: true,
            min: -100,
            max: 100,
            from: <?=$c['battle_cfg'][33]?>,
            type: 'single',
            step: 1,
            prefix: "% ",
            grid: true
        });

    });
</script>
<br><br><br>
<button class="btn" type="submit">Загрузить баланс в систему</button>
<br>
<br><br><br>
</form>
</center>
</body>
</html>
<? } ?>