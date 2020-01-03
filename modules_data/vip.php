<?php
if(!defined('GAME') || ($u->stats['bronze']<1 && $u->stats['silvers']<1 && $u->stats['gold']<1))
{
	die();
}

if($u->error!='')
{
	echo '<font color="red"><b>'.$u->error.'</b></font><br>';
}
$vt = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `data` LIKE "%account=%" LIMIT 1'));
if($u->stats['bronze']>0) $account = 'bronze';  //Bronze Premium Account
elseif($u->stats['silvers']>0) $account = 'silvers'; //Silver Premium Account
elseif($u->stats['gold']>0) $account = 'gold'; //Gold Premium Account
$vu = array(0,0,0,0,0,0,0);
//Абилки $ability
$ability = array(
    "bronze" => array(
	//Свитки Bronze
	array(4412,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4413,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4414,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(2412,2,0,0,0,'tr_lvl=4|noremont=1|usefromfile=1|musor=1|battleUseZd=300|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1463,1,0,0,0,'tr_lvl=4|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(3101,1,0,0,0,'useOnLogin=1|onlyOne=1|oneType=33|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4371,1,0,0,0,'onlyOne=1|oneType=36|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(2709,1,0,0,0,'tr_lvl=7|useOnLogin=1|musor=1|noremont=1|magic_hpNow=900|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(994,1,0,0,0, 'useOnLogin=1|onlyOne=1|oneType=44|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1001,1,0,0,0,'useOnLogin=1|onlyOne=1|oneType=7|tr_lvl=7|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1461,1,0,0,0,'tr_lvl=2|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1462,1,0,0,0,'tr_lvl=2|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
    ),
    "silvers" => array(
	//Свитки Silver
	array(4412,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4413,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4414,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(2412,2,0,0,0,'tr_lvl=4|noremont=1|usefromfile=1|musor=1|battleUseZd=300|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1463,1,0,0,0,'tr_lvl=4|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(3101,1,0,0,0,'useOnLogin=1|onlyOne=1|oneType=33|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4371,1,0,0,0,'onlyOne=1|oneType=36|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(2709,1,0,0,0,'tr_lvl=7|useOnLogin=1|musor=1|noremont=1|magic_hpNow=900|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(994,1,0,0,0, 'useOnLogin=1|onlyOne=1|oneType=44|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1001,1,0,0,0,'useOnLogin=1|onlyOne=1|oneType=7|tr_lvl=7|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1461,1,0,0,0,'tr_lvl=2|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1462,1,0,0,0,'tr_lvl=2|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4926,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4927,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4928,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4929,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4930,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
    ),
    "gold" => array(
	//Свитки Gold
array(4412,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4413,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4414,3,0,0,0,'useOnLogin=1|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(2412,2,0,0,0,'tr_lvl=4|noremont=1|usefromfile=1|musor=1|battleUseZd=300|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1463,1,0,0,0,'tr_lvl=4|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(3101,1,0,0,0,'useOnLogin=1|onlyOne=1|oneType=33|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4371,1,0,0,0,'onlyOne=1|oneType=36|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(2709,1,0,0,0,'tr_lvl=7|useOnLogin=1|musor=1|noremont=1|magic_hpNow=900|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(994,1,0,0,0, 'useOnLogin=1|onlyOne=1|oneType=44|tr_lvl=4|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1001,1,0,0,0,'useOnLogin=1|onlyOne=1|oneType=7|tr_lvl=7|noremont=1|musor=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1461,1,0,0,0,'tr_lvl=2|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(1462,1,0,0,0,'tr_lvl=2|nohaos=1|onlyOne=1|oneType=24|musor=2|noremont=1|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4926,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4927,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4928,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4929,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4930,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=53|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4936,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=58|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	array(4937,1,0,0,0,'tr_lvl=7|onlyOne=1|musor=2|noremont=1|oneType=59|sudba='.$u->info['login'].'|srok=86400|nosale=1',5),
	
    )
);


$vi = array(

);
?>
<table width="100%">
    <tr>
        <td align="center"><h3><img src="i/1.png">Добро пожаловать, <?=$u->info['login']?> [<?=$u->info['level']?>]<img src="i/2.png"></td></h3>
        <td width="150" align="right"><input type="button" class="btn" value="Обновить" onclick="location='main.php?vip=1';" />   </div>   <input type="button" class="btn" value="Вернуться" onclick="location='main.php';" /></td>
			<? if($account == 'bronze'){ ?>
            <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Восстановление жизни и манны +5%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Здроровье и мана восстанавливаются быстрее.</b></font></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Получаемый опыт в бою +5%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Повышение уровня персонажа станет быстрее.</b></font></td>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Скидка на ремонт +5%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Вы чините свои вещи на 5% дешевле.</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Увеличение рюкзака +10 </td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Увеличивает рюкзак на 10+ единиц.</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Бонус Жизни +1 </td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>1 Выносливость = 1 ХП</b></font></td>
					</tr>
			<?}?>
			<? if($account == 'silvers'){ ?>
            <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Восстановление жизни и манны +10%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Здроровье и мана восстанавливаются быстрее.</b></font></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Получаемый опыт в бою +10%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Повышение уровня персонажа станет быстрее.</b></font></td>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Скидка на ремонт +10%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Вы чините свои вещи на 10% дешевле.</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Увеличение рюкзака +20 </td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Увеличивает рюкзак на 20+ единиц.</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Скорость передвижения +5% </td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>По подземельям вы двигаетесь быстрее</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Бонус Жизни +2 </td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>1 Выносливость = 2 ХП</b></font></td>
					</tr>
			<?}?>
								<? if($account == 'gold'){ ?>
            <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Восстановление жизни и манны +25%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Здроровье и мана восстанавливаются быстрее.</b></font></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Получаемый опыт в бою +20%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Повышение уровня персонажа станет быстрее.</b></font></td>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Скидка на ремонт +15%</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Вы чините свои вещи на 15% дешевле.</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Увеличение рюкзака +50 </td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>Увеличивает рюкзак на 50+ единиц.</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Скорость передвижения +15% </td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>По подземельям вы двигаетесь быстрее</b></font></td>
					</tr>
					<tr>
                    <td style="border-bottom:1px solid #CCCCCC;">&bull; Бонус Жизни +3</td>
                    <td width="75" align="center" valign="middle" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Вечно</b></td>
                    <td style="border-bottom:1px solid #CCCCCC;"><font color=darkgreen><b>1 Выносливость = 3 ХП</b></font></td>
					</tr>
					<?}?>

             </fieldset>
            </table>
            <fieldset><p><b>Доступные предметы:</b><br /><small>(Чтобы забрать предмет просто нажмите на его изображение)</small></p>
            <p>
                <?
                $i = 0; $seet = '';
                $vnr = array(0 => 'на сегодня',1 => ' всего');
                while($i < count($ability[$account])) {

                    if($ability[$account][$i][1]>0) {

                        $itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$ability[$account][$i][0].'" LIMIT 1'));
                        if(isset($itm['id'])) {

                            $vix = 0;
                            if($ability[$account][$i][4] == 0) {
                                //за сегодня
                                $vix = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time`>='.strtotime('now 00:00:00').' AND `vars` = "vitm_'.$itm['id'].'"',2);
                                $vix = $vix[0];

                            }else{
                                //всего за действие премиума
                                $vix = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time`>='.$vt['timeUse'].' AND `vars` = "vitm_'.$itm['id'].'" ',2);
                                $vix = $vix[0];
                            }

                            if($ability[$account][$i][1]-$vix > 0) {
                                if(isset($_GET['take_item_vip']) && $_GET['take_item_vip'] == $itm['id']) {

                                    $nitm = $u->addItem($itm['id'],$u->info['id'],$ability[$account][$i][5]);
                                    if($ability[$account][6]>0) {
                                    }
                                    $u->addAction(time(),'vitm_'.$itm['id'],'');
                                    echo '<font color="red">Предмет &quot;<b>'.$itm['name'].'</b>&quot; перемещен к Вам в инвентарь!</font><br><br>';

                                }
                            }

                            $seet0 = '';
							//Вывод предметов вип
                            $seet0 .= '<img '.$vix.' title="'.$itm['name'].'
(Осталось '.$vnr[$ability[$i][4]].': '.($ability[$account][$i][1]-$vix).' шт.)" src="http://'.$c['img'].'/i/items/'.$itm['img'].'"> ';
                            if($ability[$account][$i][1]-$vix > 0) {
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
            </p></td></fieldset>
    </tr>
</table>
