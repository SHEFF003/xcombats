<?
if(!defined('GAME') || $u->stats['silver']<1)
{
	die();
}

if($u->error!='')
{
	echo '<font color="red"><b>'.$u->error.'</b></font><br>';
}

$vt = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `data` LIKE "%add_silver=%" LIMIT 1'));
$vu = array(0,0,0,0,0,0,0);
$vi = array(
/*	//лечение травм
	array(4412,array( 0 , 5 , 5 , 5 , 10 , 10 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=600',1),	
	array(4413,array( 0 , 5 , 5 , 5 , 10 , 10 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=600',1),
	array(4414,array( 0 , 5 , 5 , 5 , 10 , 10 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=600',1),
	
	//нападалки
	array(865, array( 0 ,  0 , 0 , 0 , 0 , 0 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=43200',1),
	array(2391,array( 0 ,  0 , 0 , 0 , 10 , 20 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=43200',2),
	
	//хилки
	array(2543,array( 0 , 0 , 0 , 0 , 0 , 0 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=21600|magic_hpNow=45',2),
	array(2544,array( 0 , 0  , 0 , 0 , 0 , 0 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=21600|magic_hpNow=60',2),
	array(2545,array( 0 , 0  , 0 , 0 , 0 , 0 ),0,0,1,'useOnLogin=1|musor=1|noremont=1|sudba='.$u->info['login'].'|srok=21600|magic_hpNow=600',2),
	
	//обкасты
	array(994, array( 0 , 0 , 0 , 0 , 5 , 10 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=6|sudba='.$u->info['login'].'|srok=600',1),
	array(1001,array( 0 , 0 , 0 , 0 , 5 , 10 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=7|sudba='.$u->info['login'].'|srok=600',1),
	array(1460,array( 0 , 0 , 0 , 0 , 5 , 10 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=25|sudba='.$u->info['login'].'|srok=600',1),
	array(3102,array( 0 , 0 , 0 , 0 , 5 , 10 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=33|sudba='.$u->info['login'].'|srok=600',1),
	array(4371,array( 5 , 5 , 5 , 5 , 5 , 5 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=36|sudba='.$u->info['login'].'|srok=600',1),
	
	//Сундуки
	array(2144,array( 0 , 0 , 0 , 0 , 0 , 3 ),0,0,1,'nohaos=1|onlyOne=1|oneType=12|musor=2|noremont=1|srok=600',1),
	array(2143,array( 0 , 0 , 0 , 0 , 0 , 3 ),0,0,1,'nohaos=1|onlyOne=1|oneType=7|musor=2|noremont=1|srok=600',1),
	//array(3101,array( 0 , 0 , 0 , 0 , 0 , 0 ),0,0,1,'onlyOne=1|oneType=33|noremont=1|musor=1|srok=600',1),
	
	//Екр.
	array(1461,array( 0 , 0 , 0 , 0 , 0 , 0 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=24|sudba='.$u->info['login'].'|srok=600',1),
	array(1462,array( 0 , 0 , 0 , 0 , 0 , 0 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=24|sudba='.$u->info['login'].'|srok=600',1),
	array(1463,array( 0 , 0 , 0 , 0 , 0 , 0 ),0,0,1,'musor=1|noremont=1|onlyOne=1|oneType=24|sudba='.$u->info['login'].'|srok=600',1)
		
	//array(2143,1,2,10,1,'musor=1|noremont=1|onlyOne=1|oneType=3|sudba='.$u->info['login'].'|srok=600',1),
	//array(2144,1,2,10,1,'musor=1|noremont=1|onlyOne=1|oneType=3|sudba='.$u->info['login'].'|srok=600',1)*/
);
?>
<table width="100%">
  <tr>
    <td align="center"><h3>Добро пожаловать, <?=$u->info['login']?>!</h3><center>У вас действует <img width="15" height="15" style="display:inline-block; vertical-align:text-bottom;" src="http://img.xcombats.com/blago/<?=$u->stats['silver']?>.png" /><b>Благословления Ангелов</b> <?=$u->stats['silver']?> уровня.</center></td>
    <td width="150" align="right"><input type="button" value="обновить" onclick="location='main.php?vip=1';" />      <input type="button" value="Вернуться" onclick="location='main.php';" /></td>
  </tr>
  <tr>
    <td><p><b>Доступные возможности:</b> <a href="http://xcombats.com/benediction/" target="_blank">Посмотреть возможности</a></p>
      <p><b>Доступные предметы:</b><br />
    <small>(Чтобы забрать предмет просто нажмите на его изображение)</small></p>
    <p>
    <?
	$i = 0; $seet = '';
	$vnr = array(0 => 'на сегодня',1 => ' всего');
	while($i < count($vi)) {
		if($vi[$i][1][$u->stats['silver']]>0) {
			$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$vi[$i][0].'" LIMIT 1'));
			if(isset($itm['id'])) {
				$vix = 0;
				if($vi[$i][4] == 0) {
					//за сегодня
						$vix = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time`>='.strtotime('now 00:00:00').' AND `vars` = "vitm_'.$itm['id'].'" LIMIT '.$vi[$i][1][$u->stats['silver']],2);
						$vix = $vix[0];
				}else{
					//всего за действие премиума
						$vix = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time`>='.$vt['timeUse'].' AND `vars` = "vitm_'.$itm['id'].'" LIMIT '.$vi[$i][1][$u->stats['silver']],2);
						$vix = $vix[0];
				}

				if($vi[$i][1][$u->stats['silver']]-$vix > 0) {
					if(isset($_GET['take_item_vip']) && $_GET['take_item_vip'] == $itm['id']) {
						$vix++;
						$nitm = $u->addItem($itm['id'],$u->info['id'],$vi[$i][5]);
						if($vi[$i][6]>0) {
							mysql_query('UPDATE `items_users` SET `data`="'.$vi[$i][5].'",`iznosMAX` = "'.$vi[$i][6].'",`1price` = "0.01" WHERE `id` = "'.$nitm.'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
						}
						$u->addAction(time(),'vitm_'.$itm['id'],'');
						echo '<font color="red">Предмет &quot;<b>'.$itm['name'].'</b>&quot; перемещен к Вам в инвентарь (Осталось '.$vnr[$vi[$i][4]].': '.($vi[$i][1][$u->stats['silver']]-$vix).' шт.).</font><br><br>';
					}
				}
				
				$seet0 = '';				
				$seet0 .= '<img '.$vix.' title="'.$itm['name'].'
(Осталось '.$vnr[$vi[$i][4]].': '.($vi[$i][1][$u->stats['silver']]-$vix).' шт.)" style="height:25px;" src="http://img.xcombats.com/i/items/'.$itm['img'].'"> ';
				if($vi[$i][1][$u->stats['silver']]-$vix > 0) {
					$seet0 = '<a href="main.php?vip=1&take_item_vip='.$itm['id'].'">'.$seet0.'</a>';
				}else{
					$seet0 = '<span style="filter: alpha(opacity=20); -moz-opacity: 0.20; -khtml-opacity: 0.20; opacity: 0.20;">'.$seet0.'</span>';
				}
				$seet .= $seet0;
			}
		}
		$i++;
	}
	echo $seet;
	?>
    </p>
    <font color=red><b>Внимание!</b> Срок годности выдаваемых предметов 10 мин.</font><Br /></td>
  </tr>
</table>
