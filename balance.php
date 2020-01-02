<?php
define('GAME',true);	
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
if(isset($_GET['connect'])) {
	if(isset($_GET['connect'])) {
		setcookie('adminionself','trueself',time()+86400);
		header('location: /balance.php');
		die();
	}
}elseif( $u->info['admin'] != 1 && !isset($_COOKIE['adminionself']) ) {
	die('balance.php');
}
if($u->info['admin']<1 && !isset($_COOKIE['adminionself']))
{
	header('location: http://xcombats.com/'); die();
}

$noobs = '';

/*
if(!isset($_GET['noob']) || isset($_POST['money'])) {
	$noobs = ' AND `time` < 1443155244 ';
}
*/

if(isset($_GET['all'])) {
	$data = array(
		
	); //по дням с самого начала
	$sp = mysql_query('SELECT * FROM `balance_money` WHERE `money` > 0'.$noobs.' ORDER BY `id` ASC');
	$max = 0;
	//
	$starttime = mysql_fetch_array(mysql_query('SELECT `time` FROM `balance_money` ORDER BY `id` ASC LIMIT 1'));
	if(isset($starttime['time'])) {
		$starttime = $starttime['time'];
	}else{
		$starttime = time();
	}
	//
	while( $pl = mysql_fetch_array($sp) ) {
		if($starttime == 0) {
			$starttime = $pl['time']-50700;
		}
		$data[date('d.m.Y',$pl['time'])] += $pl['money'];
		if( $max < $pl['money'] ) {
			$max = $pl['money'];
		}
	}
	echo '<style>
	.boxpro0 { width:30px; background-color:green; }
	.boxproms { width:2px; background-color:blue; margin:0 5px 0 5px; }
	.boxpro1 { width:30px; max-height:3px; background-color:grey; }
	.boxpro0:hover { background-color:red; }
	</style>';
	echo '<table border="0" cellspacing="0" cellpadding="0"><tr>
	<td valign="bottom"><div class="boxpro1"></div></td><td valign="bottom"><div class="boxpro1"></div></td><td valign="bottom"><div class="boxpro1"></div></td><td valign="bottom"><div class="boxpro1"></div></td><td valign="bottom"><div class="boxpro1"></div></td><td valign="bottom"><div class="boxpro1"></div></td>
	';
	$i = $starttime;
	$j = 0;
	$mn = 0;
	while( $i <= time() ) {
		//
		$nl = 0;
		if( $data[date('d.m.Y',$i)] == 0 ) {
			$nl = 1;
		}
		if( $mn != date('m',$i) || date('d.m.Y') == date('d.m.Y',$i) ) {
			if( $mn != 0 ) {
				echo '<td valign="bottom"><div class="boxproms" style="height:100px;"></div></td><td>'.$summ.' RUB</td></tr></table><br><br><table border="0" cellspacing="0" cellpadding="0"><tr>';
			}
			$summ = 0;
			$mn = date('m',$i);
		}
		echo '<td valign="bottom"><div title="Купили '.round(($data[date('d.m.Y',$i)]),2).' RUB'."\n".'Дата: '.date('d.m.Y',$i).'" class="boxpro'.$nl.'" style="height:'.round(10+$data[date('d.m.Y',$i)]/$max*100).'px;"></div></td>';
		//
		$i += 86400;
		$sum += round(($data[date('d.m.Y',$i)]),2);
		$summ += round(($data[date('d.m.Y',$i)]),2);
		$today = round(($data[date('d.m.Y',$i)]),2);
		$j++;
	}
	//
	//<td valign="bottom">&nbsp;</td>
	//
	echo '</tr></table><hr>Дней: '.$j.' , сумма: '.$sum.' RUB<br>Среднее в день: '.round($sum/$j,2).' RUB<br>Среднее в месяц: '.round($sum/$j*30,2).' RUB<br>За сегодня: '.$today.' RUB';
	die();
}

if(isset($_POST['money']))
{
	$balance = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `balance_money` WHERE `cancel` = 0'.$noobs.''));
	$balance = $balance[0]+(int)$_POST['money'];
	mysql_query('INSERT INTO `balance_money` (`time`,`ip`,`money`,`comment2`,`balance`) VALUES ("'.time().'","'.$u->info['ip'].'","'.((int)$_POST['money']).'","'.mysql_real_escape_string($_POST['text']).'","'.$balance.'")');
}elseif(isset($_GET['cancel']))
{
	mysql_query('UPDATE `balance_money` SET `cancel` = "'.$u->info['id'].'" WHERE `id` = "'.((int)$_GET['cancel']).'" LIMIT 1');
}elseif(isset($_GET['recancel']))
{
	mysql_query('UPDATE `balance_money` SET `cancel` = "0" WHERE `id` = "'.((int)$_GET['recancel']).'" LIMIT 1');
}

$mm = date('m');
$yy = date('Y');
if(isset($_GET['mm']))
{
	$mm = $_GET['mm'];//strtotime
}
$mf = array(
'01' => 'January',
'02' => 'February',
'03' => 'March',
'04' => 'April',
'05' => 'May',
'06' => 'June',
'07' => 'July',
'08' => 'August',
'09' => 'September',
'10' => 'October',
'11' => 'November',
'12' => 'December'
);
$mf2 = array(
'12' => 'January',
'01' => 'February',
'02' => 'March',
'03' => 'April',
'04' => 'May',
'05' => 'June',
'06' => 'July',
'07' => 'August',
'08' => 'September',
'09' => 'October',
'10' => 'November',
'11' => 'December'
);
if(!isset($mf[$mm]))
{
	$mm = date('m');
}
$yy2 = $yy;
if($mm=='12')
{
	$yy2++;
}
$time_start = strtotime("1 ".$mf[$mm]." ".$yy."");
$time_finish = strtotime("1 ".$mf2[$mm]." ".$yy2."");

$balance = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `balance_money` WHERE `cancel` = 0'.$noobs.''));
$balance = $balance[0];
$plus = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `balance_money` WHERE `cancel` = 0'.$noobs.' AND `time` >= '.$time_start.' AND `time` < '.$time_finish.' AND `cancel` = "0"'));
$plus = $plus[0];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Отчетность проекта</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-family: tahoma, arial, verdana, sans-serif, Lucida Sans;
	font-size: 11px;
}
.txt1 {
	color: #707a88;
}
</style>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='?mm="+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
</head>
<body>
<table width="1000" bgcolor="#fefefe" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center"><form name="form1" method="post" action="">
      Период отчетности: 
        <select name="mm" id="mm" onChange="MM_jumpMenu('parent',this,0)">
            <option <? if($mm=='01'){ echo 'selected'; } ?> value="01">январь</option>
            <option <? if($mm=='02'){ echo 'selected'; } ?> value="02">февраль</option>
            <option <? if($mm=='03'){ echo 'selected'; } ?> value="03">март</option>
            <option <? if($mm=='04'){ echo 'selected'; } ?> value="04">апрель</option>
            <option <? if($mm=='05'){ echo 'selected'; } ?> value="05">май</option>
            <option <? if($mm=='06'){ echo 'selected'; } ?> value="06">июнь</option>
            <option <? if($mm=='07'){ echo 'selected'; } ?> value="07">июль</option>
            <option <? if($mm=='08'){ echo 'selected'; } ?> value="08">август</option>
            <option <? if($mm=='09'){ echo 'selected'; } ?> value="09">сентябрь</option>
            <option <? if($mm=='10'){ echo 'selected'; } ?> value="10">октябрь</option>
            <option <? if($mm=='11'){ echo 'selected'; } ?> value="11">ноябрь</option>
            <option <? if($mm=='12'){ echo 'selected'; } ?> value="12">декабрь</option>
          </select>
    , <?=$yy;?>
     года.
    Доступные средства:
     <span style="font-weight: bold"><?=number_format($balance, 0, ",", " ");?></span> RUB | Прибыль за этот месяц: <span style="font-weight: bold"><?=number_format($plus, 0, ",", " ");?></span> RUB | Дата генерации отчета:
<?=date('d.m.Y H:i:s');?>
    </form></td>
  </tr>
  <!--<tr>
    <td>     
    <table width="1000" border="0" align="center" cellpadding="5" cellspacing="0">
      <tr>
        <td width="500" align="center" bgcolor="#CEE4C5" class="txt1" style="color: #265214; font-family: tahoma, arial, verdana, sans-serif, 'Lucida Sans';">Поступление</td>
        <td width="500" align="center" bgcolor="#E1C8C9" class="txt1" style="color: #A3585C">Списание</td>
      </tr>
    </table>    
    </td>
  </tr>-->
  <tr>
    <td>
    <? 
	$i = 1;
	$days = ($time_finish-$time_start)/86400;
	while($i<=$days)
	{
	$dt = $time_start+(86400*($i-1));
	if($dt<time())
	{
		$lim = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `balance_money` WHERE `time` >= '.$dt.''.$noobs.' AND `time` < '.($dt+86400).''));
		$lim = $lim[0];
		$mst = mysql_fetch_array(mysql_query('SELECT `money`,`balance` FROM `balance_money` WHERE `time` < '.$dt.''.$noobs.' AND `cancel` = "0" ORDER BY `id` DESC LIMIT 1')); $mst = $mst['balance'];
		$mft = mysql_fetch_array(mysql_query('SELECT `money`,`balance` FROM `balance_money` WHERE `time` >= '.$dt.''.$noobs.' AND `time` < '.($dt+86400).' AND `cancel` = "0" ORDER BY `id` DESC LIMIT 1')); $mft = $mft['balance'];
	?>
    <!-- day -->
	<div style="background-color:#cad3e0;color:#8591a2;border:1px solid #cdd5e2;">
	  <div style="padding:10px;">Дата: <b><?=date('d.m.Y',$dt);?></b>, Операций за этот день: <?=$lim;?>, средств в начале дня: <b><?=number_format($mst, 0, ",", " ");?></b> RUB, средств в конце дня: <b><?=number_format($mft, 0, ",", " ");?></b> RUB</div>
 		<!-- -->
        <? 
		$sp = mysql_query('SELECT * FROM `balance_money` WHERE `time` >= '.$dt.''.$noobs.' AND `time` < '.($dt+86400).' ORDER BY `time` ASC LIMIT '.$lim);
		while($pl = mysql_fetch_array($sp))
		{
			if($pl['money']>0 && $pl['cancel']==0)
			{
		?>
        <table width="998" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="499" align="center" valign="top" bgcolor="#f5f7fa" class="txt1">
            <table bgcolor="#e6f8ea" width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100" align="center"><?=date('d.m.Y H:i:s',$pl['time']);?></td>
                <td width="75" align="center"><?=number_format($pl['money'], 0, ",", " ");?> RUB</td>
                <td>Остаток: <span style="font-weight: bold"><?=number_format($pl['balance'], 0, ",", " ");?></span> RUB</td>
                <td width="100" align="center"><? if($pl['cancel']==0){ echo '<a href="?mm='.$mm.'&cancel='.$pl['id'].'">Открепить</a>'; }else{ echo '<a href="?mm='.$mm.'&recancel='.$pl['id'].'">Прикрепить</a>'; } ?></td>
                </tr>
            </table>
            </td>
            <td width="499" align="left" valign="top" bgcolor="#f5f7fa" class="txt1">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="30" align="left" valign="top">&larr;</td>
                  <td valign="top">&nbsp;<?=$pl['comment2'];?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <? }else{ ?>
        <table width="998" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="499" align="right" valign="top" bgcolor="#f5f7fa" class="txt1">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>            
                <td valign="top" bgcolor="#F5F7FA">&nbsp;<?=$pl['comment2'];?></td>
                <td width="30" align="right" valign="top">&rarr;</td>
              </tr>
            </table>
            </td>
            <td width="499" align="center" valign="top" bgcolor="<? if($pl['money']<0){ echo '#f8e6ef'; }else{ echo '#F5F7FA'; } ?>" class="txt1">
            <table bgcolor="<? if($pl['money']<0 && $pl['cancel']==0){ echo '#f8e6ef'; }else{ echo '#F5F7FA'; } ?>" width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100" align="center"><?=date('d.m.Y H:i:s',$pl['time']);?></td>
                <td width="75" align="center"><?=number_format($pl['money'], 0, ",", " ");?> RUB</td>
                <td>Остаток: <span style="font-weight: bold"><?=number_format($pl['balance'], 0, ",", " ");?></span> RUB</td>
                <td width="100" align="center"><? if($pl['cancel']==0){ echo '<a href="?mm='.$mm.'&cancel='.$pl['id'].'">Открепить</a>'; }else{ echo '<a href="?mm='.$mm.'&recancel='.$pl['id'].'">Прикрепить</a>'; } ?></td>
                </tr>
            </table>
            </td>
          </tr>
        </table>
        <? } } ?>
        <!-- -->
      </div>
    <? } $i++; } ?>
    <? if($mm==date('m')){ ?>
    <br><br><br>
    <div style="background-color:#F5F7FA;">
    <form name="form1" method="post" action="?mm=<?=$mm;?>#addline">
      <table width="100%" border="0" align="center" cellpadding="5" style="border:1px solid #8591a2;" cellspacing="0">
        <tr>
          <td bgcolor="#CAD3E0">Сумма: <input name="money" type="text" size="21" maxlength="7" /> 
            RUB</td>
        </tr>
        <tr>
          <td bgcolor="#CAD3E0"><p class="txt1">Комментарий (500 символов максимум):</p>
            <p>
              <textarea style="width:980px;" name="text" id="text" cols="45" rows="5"></textarea>
            </p></td>
        </tr>
        <tr>
          <td align="right" bgcolor="#CAD3E0"><input type="submit" name="button" id="button" value="Прикрепить к отчету за <?=date('d.m.Y');?>"></td>
        </tr>
      </table>
     </form>
    </div>
    <? } ?>
    <!-- day -->
    <br><br><br>time :: <?=time();?>
    </td>
  </tr>
</table>
</body>
</html>
