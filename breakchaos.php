<?php
define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

/*
<tr height="20">
<td>1</td>
<td><a target="_blank" href="http://capitalcity.combats.com/encicl/alignment.html" title="Повелитель Огня"><img src="http://img.combats.ru/i/align20.gif" width="12" height="15" border="0" /></a><a target="_blank" href="http://dungeon.combats.com/clans_inf.pl?Mercenaries" title="Информация о клане Mercenaries"><img src="http://img.combats.ru/i/klan/Mercenaries.gif" width="24" height="15" border="0" /></a><strong>SeDuCeR</strong>&nbsp;[12]<a href="http://dungeon.combats.com/inf.pl?1069870465" title="Информация о SeDuCeR" target="_blank"><img src="http://img.combats.ru/i/inf.gif" width="12" height="11" border="0" /></a></td>
<td>243</td>
<td>47748</td>
<td><a href="http://dungeon.combats.com/logs.pl?log=1425817576.94166">&raquo;&raquo;</a></td>
</tr>
*/

$r1 = '';
$r2 = '';
$lvl = 8;
$lvl_name = 'Любители';
if( $_GET['level'] == 9 ) {
	$lvl = 9;
	$lvl_name = 'Бывалые';
}elseif( $_GET['level'] == 10 ) {
	$lvl = 10;
	$lvl_name = 'Профессионалы';
}elseif( $_GET['level'] == 11 ) {
	$lvl = 11;
	$lvl_name = 'Жители';
}

$i = 1;
$j = 1;

$sp = mysql_query('SELECT `id`,`uid`,`level`,`time` FROM `izlom_rating` WHERE `level` = "' . $lvl . '" GROUP BY `uid` ORDER BY SUM(`obr`) DESC');
while( $pl = mysql_fetch_array($sp) ) {
	//
	$ret = mysql_fetch_array(mysql_query('SELECT SUM(`obr`) FROM `izlom_rating` WHERE `uid` = "'.$pl['uid'].'" AND `level` = "'.$pl['level'].'" LIMIT 1'));
	$ret = round($ret[0]*(154.97));
	//
	$pl2 = mysql_fetch_array(mysql_query('SELECT * FROM `izlom_rating` WHERE `uid` = "'.$pl['uid'].'" AND `level` = "'.$pl['level'].'" ORDER BY `time` DESC LIMIT 1'));
	//
	$r1 .= '<tr height="20">
<td>' . $i . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl2['voln'] . '</td>
<td>'.$ret.'</td>
<td>&raquo;&raquo;</td>
</tr>';
	//
	if( date('d.m.Y') == date('d.m.Y',$pl2['time']) ) { 
		$r2 .= '<tr height="20">
<td>' . $j . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl2['voln'] . '</td>
<td>'.$ret.'</td>
<td>&raquo;&raquo;</td>
</tr>';
		$j++;
	}
	$i++;
}

/*$sp = mysql_query('SELECT * FROM `izlom_rating` WHERE `level` = "' . $lvl . '" GROUP BY `uid` ORDER BY MAX(`time`) DESC');
while( $pl = mysql_fetch_array($sp) ) {
	//
	$ret = mysql_fetch_array(mysql_query('SELECT SUM(`obr`) FROM `izlom_rating` WHERE `uid` = "'.$pl['uid'].'" AND `level` = "'.$pl['level'].'" LIMIT 1'));
	$ret = round($ret[0]*(154.97));
	//
	$r1 .= '<tr height="20">
<td>' . $i . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl['voln'] . '</td>
<td>'.$ret.'</td>
<td>&raquo;&raquo;</td>
</tr>';

if( $pl['uid'] == 1000000 ) {
	echo date('d.m.Y',$pl['time']).'<br>';
}
	if( date('d.m.Y') == date('d.m.Y',$pl['time']) ) { 
	$r2 .= '<tr height="20">
<td>' . $j . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl['voln'] . '</td>
<td>' . $ret . '</td>
<td>&raquo;&raquo;</td>
</tr>';
		$j++;
	}
	$i++;
}*/

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Архив: Поединки</title>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#E2E0E0">
<h4>
  <center>
    Излом Хаоса
      <table width="100%">
        <tbody>
          <tr>
            <td valign="top"><fieldset>
              <legend>
              <h5><?=$lvl_name?> - лучшие за месяц</h5>
              </legend>
              <? if( $r1 != '' ) { ?>
              <table align="center">
                <tbody>
                  <tr>
                    <td><h6>Место</h6></td>
                    <td><h6>Боец</h6></td>
                    <td><h6>Последняя волна</h6></td>
                    <td><h6>Рейтинг</h6></td>
                    <td><h6>Лог боя</h6></td>
                  </tr>
                  <?=$r1?>
                </tbody>
              </table>
              <? }else{
				echo 'История пуста, скорее всего не нашлось смельчаков...';  
			  } ?>
            </fieldset></td>
            <td valign="top"><fieldset>
              <legend>
              <h5>Бывалые - лучшие за день</h5>
              </legend>
              <? if( $r2 != '' ) { ?>
              <table align="center">
                <tbody>
                  <tr>
                    <td><h6>Место</h6></td>
                    <td><h6>Боец</h6></td>
                    <td><h6>Последняя волна</h6></td>
                    <td><h6>Рейтинг</h6></td>
                    <td><h6>Лог боя</h6></td>
                  </tr>
                  <?=$r2?>
                </tbody>
              </table>
              <? }else{
				echo 'История пуста, скорее всего не нашлось смельчаков...';  
			  } ?>
            </fieldset></td>
          </tr>
        </tbody>
      </table>
  </center>
</h4>
</body>
</html>