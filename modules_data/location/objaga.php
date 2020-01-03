<?
if(!defined('GAME')){ die(); }
if($u->info['admin'] > 0){
	include 'room_hostel.php';
}else {

session_start();
$_SESSION['objaga'] = 'load ';

if( $u->info['admin'] > 0 ) {
	$user_new_pers = true;
}else{
	$user_new_pers = false;
}

$objaga = mysql_fetch_array(mysql_query("SELECT * FROM `house` WHERE `owner` = '".mysql_real_escape_string($u->info['id'])."';"));

$ar_lvl=0;
$sleep_mod=0;
$ar['base']=1;
$ar['advanced']=2;
$ar['advanced2']=3;
if($objaga){$test_arenda=1;$ar_lvl=$ar[$objaga['type']];}else{$test_arenda=0;}
#---пара проверок ;)
if(isset($objaga['id'])) {
	$sleep = $u->testAction('`vars` = "sleep" AND `uid` = "'.$u->info['id'].'" LIMIT 1',1);
	if( $sleep[0] > 0 ) {
		if( $u->info['room'] != 217 && $u->info['room'] != 218 && $u->info['room'] != 219 ) {
			mysql_query('UPDATE `users` SET `room` = 217 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['room'] = 217;
		}
	}
}
if($u->info['clan']=='0'){$u->info['clan']='';}
if(!$objaga){$level=1;$nazv="Общежитие";}else{$level=2;$nazv="Общежитие";
	if(isset($_GET['obj_add'])){
		$u->obj_addItem($_GET['obj_add']);
	}elseif(isset($_GET['obj_take'])){
		$u->obj_takeItem($_GET['obj_take']);
	}
}
if($u->info['room']=='217' /*&& $objaga['type']=='base'*/){$level=3;$nazv="Общ. Этаж 1";}//else{$err = '<FONT COLOR=red><B>Вы ничего не арендуете на этом этаже<BR></B></FONT><BR>';$nazv="Общ. Этаж 1";}
if($u->info['room']=='218' /*&& $objaga['type']!='base'*/){$level=3;$nazv="Общ. Этаж 2";}
if($u->info['room']=='219' /*&& $objaga['type']!='base'*/){$level=3;$nazv="Общ. Этаж 3";}
#---аренда помещений
if($_GET['arenda']=="base") {
	if($u->info['money']>=1) {
		mysql_query("INSERT INTO `house`(`owner`,`type`,`starttime`,`endtime`,`balance`,`weekcost`) VALUES ('".mysql_real_escape_string($u->info['id'])."','".mysql_real_escape_string($_GET['arenda'])."','".time()."','".(time()+604800)."','1','1')");
		mysql_query("UPDATE `users` SET `money` = `money`-1 WHERE `id` = '".mysql_real_escape_string($u->info['id'])."';");
		$level = 2;
		$u->info['money']-=1;
		$objaga['balance']=1;
		$objaga['endtime']=time()+604800;
		$err = "<FONT COLOR=red><B>Вы арендовали 'Койку в общежитии' за 1 кр.<BR></B></FONT><BR>";
		$ar_lvl=1;
	}else{
			$err = '<FONT COLOR=red><B>У вас недостаточно денег<BR></B></FONT><BR>';
		}
}
if($_GET['arenda']=="advanced") {
	if($u->info['money']>=3) {
		mysql_query("INSERT INTO `house`(`owner`,`type`,`starttime`,`endtime`,`balance`,`weekcost`) VALUES ('".mysql_real_escape_string($u->info['id'])."','".mysql_real_escape_string($_GET['arenda'])."','".time()."','".(time()+604800)."','3','3')");
		mysql_query("UPDATE `users` SET `money` = `money`-3 WHERE `id` = '".mysql_real_escape_string($u->info['id'])."';");
		$level = 2;
		$u->info['money']-=3;
		$objaga['balance']=3;
		$objaga['endtime']=time()+604800;
		$err = "<FONT COLOR=red><B>Вы арендовали 'Койку с тумбочкой' за 3 кр.<BR></B></FONT><BR>";
		$ar_lvl=2;
	}else{
			$err = '<FONT COLOR=red><B>У вас недостаточно денег<BR></B></FONT><BR>';
		}
}
if($_GET['arenda']=="advanced2") {
	if($u->info['money']>=10) {
		mysql_query("INSERT INTO `house`(`owner`,`type`,`starttime`,`endtime`,`balance`,`weekcost`) VALUES ('".mysql_real_escape_string($u->info['id'])."','".mysql_real_escape_string($_GET['arenda'])."','".time()."','".(time()+604800)."','10','10')");
		mysql_query("UPDATE `users` SET `money` = `money`-10 WHERE `id` = '".mysql_real_escape_string($u->info['id'])."';");
		$level = 2;
		$u->info['money']-=10;
		$objaga['balance']=10;
		$objaga['endtime']=time()+604800;
		$err = "<FONT COLOR=red><B>Вы арендовали 'Койку со шкафом' за 10 кр.<BR></B></FONT><BR>";
		$ar_lvl=3;
	}else{
			$err = '<FONT COLOR=red><B>У вас недостаточно денег<BR></B></FONT><BR>';
		}
}
#---Сменить аренду
if($_GET['changelist']==1 && $objaga['id']>0) {
$level=4;
}
if($_GET['changearenda']=="base" && $objaga['id']>0) {
	if($objaga['balance']>=2) {
		$endtime = time() + (($objaga['balance']-1)/1)*604800;
		//$endtime = time() + 604800;
		mysql_query("UPDATE `house` SET `starttime` = ".time().", `endtime` = ".$endtime.", `type` = 'base', `weekcost` = '1' WHERE `owner` = '".mysql_real_escape_string($u->info['id'])."';");
		$err = '<FONT COLOR=red><B>Смена арендуемого помещения произведена успешно<BR></B></FONT><BR>';
		$objaga['type']="base";
		$objaga['endtime'] = $endtime;
	}else{
			$cam = 2-$objaga['balance'];
			$err = '<FONT COLOR=red><B>На балансе не хватает '.$cam.' кр. для смены арендуемого помещения<BR></B></FONT><BR>';
		}
}
if($_GET['changearenda']=="advanced" && $objaga['id']>0) {
	if($objaga['balance']>=6) {
		$endtime = time() + (($objaga['balance']-3)/3)*604800;
		//$endtime = time() + 604800;
		mysql_query("UPDATE `house` SET `starttime` = ".time().", `endtime` = ".$endtime.", `type` = 'advanced', `weekcost` = '3' WHERE `owner` = '".mysql_real_escape_string($u->info['id'])."';");
		$err = '<FONT COLOR=red><B>Смена арендуемого помещения произведена успешно<BR></B></FONT><BR>';
		$objaga['type']="advanced";
		$objaga['endtime'] = $endtime;
	}else{
		$cam = 6-$objaga['balance'];
		$err = '<FONT COLOR=red><B>На балансе не хватает '.$cam.' кр. для смены арендуемого помещения<BR></B></FONT><BR>';
	}
}
if($_GET['changearenda']=="advanced2" && $objaga['id']>0) {
	if($objaga['balance']>=20) {
		$endtime = time() + (($objaga['balance']-10)/10)*604800;
		//$endtime = time() + 604800;
		mysql_query("UPDATE `house` SET `starttime` = ".time().", `endtime` = ".$endtime.", `type` = 'advanced2', `weekcost` = '10' WHERE `owner` = '".mysql_real_escape_string($u->info['id'])."';");
		$err = '<FONT COLOR=red><B>Смена арендуемого помещения произведена успешно<BR></B></FONT><BR>';
		$objaga['type']="advanced2";
		$objaga['endtime'] = $endtime;
	}else{
			$cam = 20-$objaga['balance'];
			$err = '<FONT COLOR=red><B>На балансе не хватает '.$cam.' кр. для смены арендуемого помещения<BR></B></FONT><BR>';
	}
}
#---возможности комнаты которую арендовали
if($objaga['type']=="base"){
	$name = "Койку в общежитии";
	$maxbox = "25";
	$max_p_box = "50";
}
if($objaga['type']=="advanced"){
	$name = "Койку с тумбочкой";
	$maxbox = "40";
	$max_p_box = "150";
}
if($objaga['type']=="advanced2"){
	$name = "Койку со шкафом";
	$maxbox = "70";
	$max_p_box = "200";
}

if(!isset($objaga['type']) && $u->info['room']!='214') {
	$u->info['room'] = 214;
	mysql_query('UPDATE `users` SET `room` = "'.$u->info['room'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	die('(Обновите экран)');
}

#---Прекращаем аренду
if($_GET['closearenda']==1) {
	mysql_query("UPDATE `items_users` SET `inShop` = '0' WHERE `uid` = '".mysql_real_escape_string($u->info['id'])."' AND `inShop` = '1';");
	mysql_query("DELETE FROM `house` WHERE `owner` = '".mysql_real_escape_string($u->info['id'])."';");
	$err = '<FONT COLOR=red><B>Вы отказались арендовать "'.$name.'"<BR></B></FONT><BR>';
	$level=1;
}
#---Продлить аренду
if($_POST['payarenda']) {
	if($_POST['payarenda']>=1) {
		if($u->info['money']>0 && (int)$_POST['payarenda']>0 && ((int)$_POST['payarenda']<=$u->info['money'])) {
			$paytime = ($_POST['payarenda']/$objaga['weekcost'])*604800;
			mysql_query("UPDATE `house`,`users` SET `house`.`endtime` = `house`.`endtime`+'".$paytime."', `house`.`balance` = `house`.`balance`+'".mysql_real_escape_string($_POST['payarenda'])."',`users`.`money`=`users`.`money`-'".mysql_real_escape_string($_POST['payarenda'])."' WHERE `house`.`owner` = `users`.`id` AND `house`.`owner` = '".mysql_real_escape_string($u->info['id'])."';");
			$err = '<FONT COLOR=red><B>Вы положили на счет '.htmlspecialchars($_POST['payarenda'],NULL,'cp1251').'.00 кр.<BR></B></FONT><BR>';
			$u->info['money'] -=$_POST['payarenda'];
			$objaga['balance'] +=$_POST['payarenda'];
			$objaga['endtime'] += $paytime;
		}else{
				$err = '<FONT COLOR=red><B>У вас недостаточно денег<BR></B></FONT><BR>';
			}
	}else{
			$err = '<FONT COLOR=red><B>Минимальная сумма: 1кр.<BR></B></FONT><BR>';
		}
}
#---обновляем баланс
$balance=round(($objaga['weekcost']*(floor(($objaga['endtime']-time())/24/3600)))/7, 2);
mysql_query("UPDATE `house` SET `balance` = '".$balance."' WHERE `owner` = '".mysql_real_escape_string($u->info['id'])."';");
$objaga['balance'] = $balance;
#---Остаток аренды
if($objaga['id']>0) {
	if(time()<$objaga['endtime']) {
		$showdate = ''.date("d.m.y H:i",$objaga['endtime']).' (баланс '.$objaga['balance'].' кр.)';
	}else{
		$showdate = '<FONT color=red>'.date("d.m.y H:i",$objaga['endtime']).' (баланс '.$objaga['balance'].' кр.)</FONT>';
		$level=2;
	}
}

#---Переключатель комнат
		if(isset($sleep['id'])) {$_GET['room']=4;$sleep_mod=1;}#---Если спим, то никуда не выбратся
		if(!$_GET['room'] and $test_arenda==1) {$_GET['room'] = 1;} #---Комната (если ничего не выбрано)
		if($_GET['room']==1 and $test_arenda==1) {$room = 1;} #---Комната
		if($_GET['room']==2 and $test_arenda==1) {$room = 2;} #---Сундук
		if($_GET['room']==6 and $test_arenda==1) {$room = 6;} #---Сувениры
		if($_GET['room']==3 and $test_arenda==1) {$room = 3;} #---Животные
		if($_GET['room']==4 and $test_arenda==1) {$room = 4;} #---Сон
		if($_GET['room']==7 and $test_arenda==1) {$room = 7;} #---Персонажи
#---Записи
if($_POST['savenotes']) {
	$_POST['notes'] = str_replace(" \\n","\n",$_POST['notes']); 
	$simbolcount = strlen($_POST['notes']);
	if($simbolcount>10000) {
		$err = "<FONT COLOR=red><B>Слишком много текста... такое не сохранить.<BR></B></FONT><BR>";
	}else{
		mysql_query("UPDATE `house` SET `notes` = '".mysql_real_escape_string($_POST['notes'])."' WHERE `owner` = '".mysql_real_escape_string($u->info['id'])."';");
		$objaga['notes'] = $_POST['notes'];
		$err = "<FONT COLOR=red><B>Сохранено (".$simbolcount.")<BR></B></FONT><BR>";
	}
}
#---Сон
if(isset($_GET['to_sleep']) && $sleep['vars'] != 'sleep'){
	mysql_query("UPDATE `eff_users` SET `sleeptime`=".time().",`deactiveLast` = ( `deactiveTime` - ".time()." ) WHERE `uid`='".mysql_real_escape_string($u->info['id'])."' AND `no_Ace` = 0 AND `delete` = 0");
	mysql_query('UPDATE `items_users` SET `time_sleep` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` < 1001 AND `data` LIKE "%|sleep_moroz=1%"');
	$u->addAction(time(),'sleep',$u->info['city']);
	$sleep['vars']='sleep';
	$sleep_mod=1;
}elseif(isset($_GET['to_awake']) && $sleep['vars'] == 'sleep'){

		$sp = mysql_query('SELECT * FROM `items_users` WHERE `time_sleep` > 0 AND `uid` = "'.$u->info['id'].'" AND `delete` < 1001 AND `data` LIKE "%|sleep_moroz=1%"');
		while( $pl = mysql_fetch_array($sp) ) {
			$tm_add = time() - $pl['time_sleep'];
			mysql_query('UPDATE `items_users` SET `time_sleep` = "0",`time_create` = "'.($pl['time_create'] + $tm_add).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}

		$sp = mysql_query('SELECT `id`,`deactiveTime`,`deactiveLast` FROM `eff_users` WHERE `v1` LIKE "pgb%" AND `delete` = "0" AND `deactiveTime` > 0 AND `uid` = "'.$u->info['id'].'" ORDER BY `timeUse` DESC');
		while($pl = mysql_fetch_array($sp)) {
			//$timeUsen=$pl['deactiveTime']+(time()-$pl['sleeptime']);
			mysql_query("UPDATE `eff_users` SET `deactiveTime` = ".(time()+$pl['deactiveLast'])." WHERE `id`='".$pl['id']."' ");						
		}

		$sp = mysql_query('SELECT `id`,`sleeptime`,`timeUse` FROM `eff_users` WHERE `uid`="'.mysql_real_escape_string($u->info['id']).'" AND `no_Ace` = 0 AND `sleeptime` > 0 AND `delete` = 0');
		while($pl = mysql_fetch_array($sp)) {
			$timeUsen=time()-($pl['sleeptime']-$pl['timeUse']);
			mysql_query("UPDATE `eff_users` SET `timeUse`='".$timeUsen."',`sleeptime`='0',`delete`='0' WHERE `id`='".$pl['id']."' ");
		}
		
	//mysql_query("UPDATE `eff_users` SET `timeUse`=(".time()."-`sleeptime`),`sleeptime`='' WHERE `uid`='".mysql_real_escape_string($u->info['id'])."' AND `id_eff`>=1 AND `id_eff`<=28 AND `id_eff`!=2 AND `id_eff`!=3 AND `id_eff`!=4 AND `id_eff`!=5 AND `id_eff`!=6 AND `id_eff`!=24 AND `sleeptime`>0");
    mysql_query('UPDATE `actions` SET `vars` = "unsleep" WHERE `id` = "'.$sleep['id'].'" LIMIT 1');
	$sleep['vars']='unsleep';
	$sleep_mod=0;
}
#---Животные
/*if($_GET['pet_id']<0) {
	$id = str_replace("-", "",$_GET['pet_id']);
	$cageid = mysql_fetch_array(mysql_query("SELECT `pet_in_cage` FROM `users_animal` WHERE `pet_in_cage` = '1' AND `uid` = '".mysql_real_escape_string($u->info['id'])."'"));
	if(!$cageid) {$petcage=1;}else{
		$cageid = mysql_fetch_array(mysql_query("SELECT `pet_in_cage` FROM `users_animal` WHERE `pet_in_cage` = '2' AND `uid` = '".mysql_real_escape_string($u->info['id'])."'"));
		if(!$cageid) {
			$petcage=2;
		}else{
			$petcage=0;
		}
	}
	if( $petcage > 0 ) {
		mysql_query("UPDATE `users_animal` SET `pet_in_cage` = '".$petcage."' WHERE `pet_in_cage` = '0' AND `id` = '".mysql_real_escape_string($id)."'");
		mysql_query("UPDATE `users` SET `animal` = '0' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."'");
	}else{
		$err = '<FONT COLOR=red><B>Все слоты зверя заняты!<BR></B></FONT><BR>';
	}
}
if($_GET['pet_id']>0) {
	if($u->info['animal']==0) {
		mysql_query("UPDATE `users_animal` SET `pet_in_cage` = '0' WHERE (`pet_in_cage` = '1' OR `pet_in_cage` = '2') AND `id` = '".mysql_real_escape_string($_GET['pet_id'])."'");
		mysql_query("UPDATE `users` SET `animal` = '".mysql_real_escape_string($_GET['pet_id'])."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."'");
	}else{
		$cageid = mysql_fetch_array(mysql_query("SELECT `pet_in_cage` FROM `users_animal` WHERE `pet_in_cage` = '1' AND `id` = '".mysql_real_escape_string($id)."'"));
		mysql_query("UPDATE `users_animal` SET `pet_in_cage` = '".$cageid['pet_in_cage']."' WHERE `pet_in_cage` = '0' AND `id` = '".mysql_real_escape_string($_GET['pet_id'])."'");
		mysql_query("UPDATE `users_animal` SET `pet_in_cage` = '0' WHERE (`pet_in_cage` = '1' OR `pet_in_cage` = '2') AND `id` = '".mysql_real_escape_string($_GET['pet_id'])."'");
		mysql_query("UPDATE `users` SET `animal` = '".mysql_real_escape_string($_GET['pet_id'])."' WHERE `id` = '".mysql_real_escape_string($u->info['id'])."'");
		//$err = '<FONT COLOR=red><B>У Вас уже есть зверь ;)<BR></B></FONT><BR>';
	}
}*/
$pet = mysql_fetch_array(mysql_query("SELECT `id`, `sex`, `name`, `level`, `obraz` FROM `users_animal` WHERE `pet_in_cage` = '0' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' AND `delete` = 0 LIMIT 1;"));
if( $pet['id'] != $u->info['animal'] ) {
	$u->info['animal'] = $pet['id'];
	mysql_query('UPDATE `users` SET `animal` = "'.$pet['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}
if( isset($_GET['pet_id']) ) {
	$id = (int)$_GET['pet_id'];
	if( $_GET['pet_id'] < 0 ) {
		$id = -$id;
		//Помещаем зверя в общагу
		$cageid = mysql_fetch_array(mysql_query("SELECT `id`,`name` FROM `users_animal` WHERE `pet_in_cage` = '0' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' AND `id` = '".mysql_real_escape_string($id)."' LIMIT 1"));
		if( isset($cageid['id']) ) {
			$cageid1 = mysql_fetch_array(mysql_query("SELECT `id` FROM `users_animal` WHERE `pet_in_cage` = '1' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1"));
			if( isset($cageid1['id']) ) {
				$cageid2 = mysql_fetch_array(mysql_query("SELECT `id` FROM `users_animal` WHERE `pet_in_cage` = '2' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' LIMIT 1"));
				if( isset($cageid2['id']) ) {
					mysql_query('UPDATE `users_animal` SET `pet_in_cage` = "0" WHERE `id` = "'.$cageid2['id'].'" LIMIT 1');
					mysql_query('UPDATE `users_animal` SET `pet_in_cage` = "2" WHERE `id` = "'.$cageid['id'].'" LIMIT 1');
				}else{
					mysql_query('UPDATE `users_animal` SET `pet_in_cage` = "2" WHERE `id` = "'.$cageid['id'].'" LIMIT 1');
				}
			}else{
				mysql_query('UPDATE `users_animal` SET `pet_in_cage` = "1" WHERE `id` = "'.$cageid['id'].'" LIMIT 1');
			}
			$err = '<FONT COLOR=red><B>Вы успешно поместили &quot;'.$cageid['name'].'&quot; в общежитие!<BR></B></FONT><BR>';
		}else{
			$err = '<FONT COLOR=red><B>Зверь не найден в инвентаре!<BR></B></FONT><BR>';
		}
	}else{
		//Забираем зверя из общаги
		$cageid = mysql_fetch_array(mysql_query("SELECT `id`,`name`,`pet_in_cage` FROM `users_animal` WHERE `pet_in_cage` > '0' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' AND `id` = '".mysql_real_escape_string($id)."' LIMIT 1"));
		if( isset($cageid['id']) ) {
			if( $u->info['animal'] > 0 ) {
				mysql_query('UPDATE `users_animal` SET `pet_in_cage` = "'.$cageid['pet_in_cage'].'" WHERE `id` = "'.$u->info['animal'].'" LIMIT 1');
				mysql_query('UPDATE `users_animal` SET `pet_in_cage` = "0" WHERE `id` = "'.$cageid['id'].'" LIMIT 1');
			}else{
				mysql_query('UPDATE `users_animal` SET `pet_in_cage` = "0" WHERE `id` = "'.$cageid['id'].'" LIMIT 1');
			}
			$err = '<FONT COLOR=red><B>Вы успешно забрали &quot;'.$cageid['name'].'&quot; из общежитие!<BR></B></FONT><BR>';
		}else{
			$err = '<FONT COLOR=red><B>Зверь не найден в общежитии!<BR></B></FONT><BR>';
		}
	}
}
$pet = mysql_fetch_array(mysql_query("SELECT `id`, `sex`, `name`, `level`, `obraz` FROM `users_animal` WHERE `pet_in_cage` = '0' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' AND `delete` = 0 LIMIT 1;"));
if( $pet['id'] != $u->info['animal'] ) {
	mysql_query('UPDATE `users` SET `animal` = "'.$pet['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}
$cage1 = mysql_fetch_array(mysql_query("SELECT `id`, `sex`, `name`, `level`, `obraz`,`pet_in_cage` FROM `users_animal` WHERE `pet_in_cage` = '1' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' AND `delete` = 0 LIMIT 1;"));
$cage2 = mysql_fetch_array(mysql_query("SELECT `id`, `sex`, `name`, `level`, `obraz`,`pet_in_cage` FROM `users_animal` WHERE `pet_in_cage` = '2' AND `uid` = '".mysql_real_escape_string($u->info['id'])."' AND `delete` = 0 LIMIT 1;"));
#------------------
#---Спим/бодрствуем
	if($sleep['vars']=='sleep'){
		$div = "<DIV style='background-color: #A0A0A0'>";
		$status = "спите";
		$link = "awake";
		$button = "Проснуться";
		$div1 = "</DIV>";
	}else{
		$status = "бодрствуете";
		$link = "sleep";
		$button = "Уснуть";
	}
#---Переходы по комнатам
if($objaga['type']=="base" && $_GET['loc']=='1.180.0.218') {
$err = "<FONT COLOR=red><B>У вас нет на это прав.<BR></B></FONT><BR>";
}

if( isset($_GET['changearenda']) ) {
	header('location: main.php');
	die();
}

?>
<STYLE>
.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
</STYLE>
<SCRIPT>
var sd4 = "<?=$u->info['id'];?>";
</SCRIPT>
<script type="text/javascript" language="javascript" src='http://img.xcombats.com/js/commoninf.js'></script>
<SCRIPT LANGUAGE="JavaScript" SRC="http://img.xcombats.com/js/sl2.27.js"></SCRIPT>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 bgcolor=e2e0e0>
<div id=hint4 class=ahint></div>
<? if($u->error!=''){ echo '<b style="color:red">'.$u->error.'</b><br>'; } ?>
<TABLE width=100%><TD valign=top height=100%>
<TABLE width=100% cellspacing=0 cellpadding=4 bgcolor=d2d2d2>
<FORM METHOD=POST name=F1>
<tr><td class='pH3'>&nbsp;&nbsp;&nbsp;&nbsp;<?=$nazv;?></FONT></td><td align=right valign=top><SCRIPT>drwfl("<?=$u->info['login'];?>",<?=$u->info['id'];?>,"<?=$u->info['level'];?>",<?=$u->info['align'];?>,"<?=$u->info['clan'];?>")</SCRIPT> &nbsp;</td></tr></table>
<?=$err?>
<?switch ($level){
		case 1:#-----Вход?>
<TABLE cellpadding=0 cellspacing=0>
<TR><TD>&nbsp;Правила: Нет нападениям. Нет телепортации. Нет передаче предметов. Нет использованию магии и распитию эликсиров.</TD>
<TR><TD align=right><i>Комендант</i></TD></TR></TABLE><BR>
Арендовать Койку в общежитии<BR>
Цена: 1 кр. + 1 кр. в неделю.<BR>

 &bull; Размер сундука: 25 вещей<BR>
&bull; Сувениры: 50 шт.<BR>
 &bull; Койка<BR>
<A href="?arenda=base&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 1 кр.?')">Арендовать</A> <A href="?arenda=base&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 5 зубов?')">Арендовать за <?=$u->zuby(5,1)?></A>

<HR>
Арендовать Койку с тумбочкой<BR>
Цена: 3 кр. + 3 кр. в неделю.<BR>

 &bull; Размер сундука: 40 вещей<BR>
&bull; Сувениры: 150 шт.<BR>
 &bull; Койка<BR>

<A href="?arenda=advanced&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 3 кр.?')">Арендовать</A> <A href="?arenda=base&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 15 зубов?')">Арендовать за <?=$u->zuby(15,1)?></A>

<HR>

Арендовать Койку со шкафом<BR>
Цена: 10 кр. + 10 кр. в неделю.<BR>

 &bull; Размер сундука: 70 вещей<BR>
&bull; Сувениры: 200 шт.<BR>
 &bull; Мест для животных: 2 <BR>
 &bull; Койка<BR>

<A href="?arenda=advanced2&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 10 кр.?')">Арендовать</A> <A href="?arenda=base&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 50 зубов?')">Арендовать за <?=$u->zuby(50,1)?></A>

<HR>
<?		;break;
        case 2:#-----Арендованная койка?>
<TABLE cellpadding=0 cellspacing=0>
<TR><TD>Правила: Нет нападениям. Нет телепортации. Нет передаче предметов. Нет использованию магии и распитию эликсиров.</TD>
<TR><TD align=right><i>Комендант</i></TD></TR></TABLE><BR>

Вы арендовали <?=$name;?><BR>
Начало аренды: <?=date("d.m.y H:i",$objaga['starttime']);?><BR>
Оплачено до: <?=$showdate;?> <IMG src="http://img.combats.ru/i/up.gif" width=11 height=11 title="Оплатить" onClick="usescript('Оплата аренды','main.php', 'payarenda', '<?=$objaga['weekcost']?>', 'Сумма оплаты:<BR>',0, '<INPUT type=hidden name=sd4 value='+sd4+'>')" style="cursor:hand"><BR>
Цена в неделю: <?=$objaga['weekcost'];?> кр.<BR>

&nbsp;&bull; Размер сундука: <?=$maxbox;?> вещей<BR>
&nbsp;&bull; Сувениры: <?=$max_p_box;?> шт.<BR>
<?if($objaga['type']=="advanced2") {echo'&nbsp;&bull; Мест для животных: 2 <BR>';}?>
&nbsp&bull; Койка<BR>

<BR>
<A href="?closearenda=1&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите прекратить аренду?')">Прекратить аренду</A><BR>
<SMALL>
При отмене аренды, все вещи из сундука переносятся в ваш инвентарь.<BR>
Ваши животные передаются вам. Если у вас уже есть другое животное, то выпускаются на волю.<BR>
Остаток средств не возвращается.<BR>
Если вы должны оплатить аренду, то ваш долг удваивается и вы не сможете воспользоваться арендой, пока не оплатите долг.<BR>
</SMALL>
<A href="?changelist=1&sd4=<?=$u->info['id'];?>">Сменить аренду</A><BR>
<SMALL>
Для смены аренды на вашем балансе должна быть необходимая сумма.<BR>
Смена аренды, оплаченной зубами на кредитную аренду запрещена.<BR>
Количество вещей, сувениров и животных на вашем складе не должно превышать допустимое значение для выбранного арендуемого помещения.<BR>
</SMALL>
		<?;break;
        case 3:#-----Комната
	switch ($room){
		case 1:#-----Заметки?>
Вы находитесь в своей комнате. Первое, что вы видите - записная книжка.<BR>
Вы можете оставить нужные вам записи общим объемом не более 10000 символов.
<TEXTAREA rows=15 style='width: 90%;' name='notes'><?=$objaga['notes'];?></TEXTAREA><BR>
<INPUT type='hidden' name='room' value='1'>
<INPUT type='submit' name='savenotes' value='Сохранить текст'>
<?		;break;
        case 2:#-----Сундук

	
//if( $u->info['admin'] > 0 ) { // для админа
	
	$chest = $u->genInv(7,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `im`.`type` != "28" AND `im`.`type` != "38" AND `im`.`type` != "39" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="1" ORDER BY `lastUPD` DESC');
	$invertory = $u->genInv(8,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `im`.`type` != "28" AND `im`.`type` != "38" AND `im`.`type` != "39" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD` DESC');
?>
<script>
	function moveAnimate(element, newParent){element = $(element); newParent= $(newParent);var oldOffset = element.offset();element.appendTo(newParent);var newOffset = element.offset();var temp = element.clone().appendTo('body');temp.css('position', 'absolute').css('left', oldOffset.left).css('top', oldOffset.top).css('width', element.width()).css('zIndex', 250);element.hide();temp.animate( {'top': newOffset.top, 'left':newOffset.left}, 'slow', function(){element.show();temp.remove();}); var count_invertory = $('#invertory .item').length;var count_chest = $('#chest .item').length; if (parseInt(count_chest)==0) { $('#chest_null').show(); } else {  $('#chest_null').hide(); }if (parseInt(count_invertory)==0) { $('#invertory_null').show(); } else { $('#invertory_null').hide(); }}
	function obj_recount(action){var count = parseInt($('#in_chest').text());var count22 = parseInt($('#in_chest22').text());if (action==1) {$('#in_chest').text(count+1);$('#in_chest22').text(count22-1);}if (action==2) {$('#in_chest').text(count-1);$('#in_chest22').text(count22-1);}}
	
	function obj_add(id, room, rnd, t){
		
		if( parseInt($('#in_chest22').text()) > 0 ) {
		$(t).text('В рюкзак');
		
		t = $(t).parents('.item').first();
		if (rnd=='' || rnd ==undefined || rnd =='undefined') {var rnd = 1;}
		$.ajax({
			type: "GET",
			url: "main.php?obj_add="+id+"&room="+room+"&rnd="+rnd,cache: false,success: function () {
				obj_recount(1);
				moveAnimate(t, $('#chest').children('tbody'));
			}
		});
		}else{
			alert('Лимит передач на сегодня исчерпан.');
		}
		
	}
	function obj_take(id, room, rnd, t){
		if( parseInt($('#in_chest22').text()) > 0 ) {
		$(t).text('В сундук');
		t = $(t).parents('.item').first();if (rnd=='' || rnd ==undefined || rnd =='undefined') {var rnd = 1;}$.ajax({type: "GET",url: "main.php?obj_take="+id+"&room="+room+"&rnd="+rnd,cache: false,success: function () {obj_recount(2);moveAnimate(t, $('#invertory').children('tbody'));}});
		}else{
			alert('Лимит передач на сегодня исчерпан.');
		}
		}
		
		
	$(document).ready(function(){
		$('.obj_take').live('click', function(){
			var id = $(this).attr('rel');
			var room = $(this).attr('data-room');
			$(this).attr('class','obj_add');
			var rnd = $(this).attr('data-code');
			obj_take(id, room, rnd, $(this));
		});
		$('.obj_add').live('click', function(){
			var id = $(this).attr('rel');
			var room = $(this).attr('data-room');
			$(this).attr('class','obj_take');
			var rnd = $(this).attr('data-code');
			obj_add(id, room, rnd, $(this));
		});
	});
	</script>
	Сундук: <span id="in_chest"><?=$chest['collich']?></span> / <?=$maxbox?>, передач: <span id="in_chest22"><?=$u->info['transfers']?></span><BR><BR>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr bgcolor="#A0A0A0"> 
				<td width=50%><b>&nbsp;В сундуке</b></td>
				<td><b>&nbsp;В рюкзаке</b></td>
			</tr>
			<? if ($u->info['admin'] > 0) { /*?>
			<tr bgcolor="#A0A0A0">
				<td>
					<form id="line_filter" style="display:inline;" onsubmit="return false;" prc_adsf="true">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td  width="160" align="center" style="border-right:#A5A5A5 1px solid; padding:5px;">Поиск по имени:</td>
								<td>
									<div style="display:inline-block; position:relative; ">
										<input type="text" id="inpFilterName"  placeholder="Введите название предмета..."  autofocus="autofocus" 	size="44" autocomplete="off"><img style="position:absolute; cursor:pointer; right: 2px; top: 3px; width: 12px; height: 12px;" onclick="document.getElementById(\'inpFilterName\').value=\'\';" title="Убрать фильтр (клавиша Esc)"  src="http://img.xcombats.com/i/clear.gif"><input type="submit" style="display: none" id="inpFilterName_submit" value="Фильтр" onclick="return false"><div class="autocomplete-suggestions" style="position: absolute; display: none;top: 15px; left:0px; margin:0px auto; right: 0px; font-size:12px; font-family: Tahoma; max-height: 300px; z-index: 9999;"></div>
									</div>
								</td>
							</tr>
						</table>
					</form>
				</td>
				<td>
					<form id="line_filter" style="display:inline;" onsubmit="return false;" prc_adsf="true">
						<table>
							<tr>
								<td>Поиск по имени:</td>
								<td>
									<div style="display:inline-block; position:relative; ">
										<input type="text" id="inpFilterName"  placeholder="Введите название предмета..."  autofocus="autofocus" 	size="44" autocomplete="off"><img style="position:absolute; cursor:pointer; right: 2px; top: 3px; width: 12px; height: 12px;" onclick="document.getElementById(\'inpFilterName\').value=\'\';" title="Убрать фильтр (клавиша Esc)"  src="http://img.xcombats.com/i/clear.gif"><input type="submit" style="display: none" id="inpFilterName_submit" value="Фильтр" onclick="return false"><div class="autocomplete-suggestions" style="position: absolute; display: none;top: 15px; left:0px; margin:0px auto; right: 0px; font-size:12px; font-family: Tahoma; max-height: 300px; z-index: 9999;"></div>
									</div>
								</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
			<? */} ?>
			<tr>
				<td width='50%' valign='top'><div <? if($chest[0]!=0){ echo 'style="display:none;"'; }?> id="chest_null"> ПУСТО</div><table id="chest" width='100%' cellpadding='2' BGCOLOR='A5A5A5' cellspacing='1'><tbody><? if($chest[0]!=0){ echo $chest[2]; } ?></tbody></table></td>
				<td width='50%' valign='top'><div <? if($invertory[0]!=0){ echo 'style="display:none;"';}?> id="invertory_null"> ПУСТО</div><table id="invertory" width='100%' cellpadding='2' BGCOLOR='A5A5A5' cellspacing='1'><tbody><? if($invertory[0]!=0){ echo $invertory[2]; } ?></tbody></table></td>
			</tr>
		</table>

<?
/*
} else { // для юзверя
	$itmAll = ''; // Все вещи
	$itmAllSee = ''; // Все вещи для просмотра?
	$itmAll = $u->genInv(7,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `im`.`type` != "28" AND `im`.`type` != "38" AND `im`.`type` != "39" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="1" ORDER BY `iu`.`lastUPD` DESC');
	//$box_count= count($itmAll);
			
			?>
	Сундук: <?=$itmAll['collich']?> / <?=$maxbox?>, передач: <?=$u->info['transfers']?><BR><BR>
	<TABLE width=100% cellpadding=0 cellspacing=0><TR bgcolor=#A0A0A0>
	<TD width=50%>&nbsp;В сундуке</TD><TD>В рюкзаке</TD>
	<TR>
	<TD valign=top><!--Рюкзак-->
	<TABLE WIDTH=100% CELLSPACING=1 CELLPADDING=2 BGCOLOR=A5A5A5>
	<?
	$itmAll = ''; $itmAllSee = '';
	$itmAll = $u->genInv(7,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `im`.`type` != "28" AND `im`.`type` != "38" AND `im`.`type` != "39" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="1" ORDER BY `iu`.`lastUPD` DESC');
	if($itmAll[0]==0)
	{
		echo '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
	}else{
		echo $itmAll[2];
	}
	?>
	</TABLE>
	</TD><TD valign=top>
	
	
	<!--Рюкзак-->
	
	
	<TABLE WIDTH=100% CELLSPACING=1 CELLPADDING=2 BGCOLOR=A5A5A5>
	<?
	$itmAll = ''; $itmAllSee = '';
	$itmAll = $u->genInv(8,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `im`.`type` != "28" AND `im`.`type` != "38" AND `im`.`type` != "39" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `iu`.`lastUPD` DESC');
	if($itmAll[0]==0)
	{
		echo '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
	}else{
		echo $itmAll[2];
	}
	?>
	</TABLE>
	</TD>
	</TR></TABLE>
	<?
}*/
;break;
        case 6:#-----Сувениры
$itmAll = ''; $itmAllSee = '';
$itmAll = $u->genInv(10,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "39") AND `iu`.`inOdet`="0" AND `iu`.`inShop`="1" ORDER BY `lastUPD` DESC');
		?>
Сувениры: <?=$itmAll['collich']?> / <?=$max_p_box?>, передач: <?=$u->info['transfers']?><BR><BR>
<TABLE width=100% cellpadding=0 cellspacing=0><TR bgcolor=#A0A0A0>
<TD width=50%>&nbsp;На сохранении</TD><TD>В рюкзаке</TD>
<TR>
<TD valign=top><!--Рюкзак-->
<TABLE WIDTH=100% CELLSPACING=1 CELLPADDING=2 BGCOLOR=A5A5A5>
<?
$itmAll = ''; $itmAllSee = '';
$itmAll = $u->genInv(10,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "39") AND `iu`.`inOdet`="0" AND `iu`.`inShop`="1" ORDER BY `lastUPD` DESC');
if($itmAll[0]==0)
{
	echo '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
}else{
	echo $itmAll[2];
}
?>
</TABLE>
</TD><TD valign=top><!--Рюкзак-->
<TABLE WIDTH=100% CELLSPACING=1 CELLPADDING=2 BGCOLOR=A5A5A5>
<?
$itmAll = ''; $itmAllSee = '';
$itmAll = $u->genInv(9,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "39") AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD` DESC');
if($itmAll[0]==0)
{
	echo '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
}else{
	echo $itmAll[2];
}
?>
</TABLE>
</TD>
</TR></TABLE>
<?		;break;
        case 7:#-----Персонажи?>

<h3>Работа с персонажами</h3>
<?
$p_now = mysql_fetch_array(mysql_query('SELECT * FROM `users_twink` WHERE `uid` = "'.$u->info['id'].'" AND `twink` = "'.$u->info['twink'].'" LIMIT 1'));
if(!isset($p_now['id'])) {
	mysql_query('INSERT INTO `users_twink` (
		`uid`,`twink`,`login`,`level`,`exp`,`upLevel`,`stats`,`time`,`ability`,`skills`,`sskills`,`nskills`,`priems`,`obraz`,`win`,`lose`,`nich`
	) VALUES (
		"'.$u->info['id'].'","0","'.$u->info['login'].'","'.$u->info['level'].'",
		"'.$u->info['exp'].'","'.$u->info['upLevel'].'","'.$u->info['stats'].'","'.time.'",
		"'.$u->info['ability'].'","'.$u->info['skills'].'","'.$u->info['sskills'].'","'.$u->info['nskills'].'","'.$u->info['priems'].'",
		"'.$u->info['obraz'].'","'.$u->info['win'].'","'.$u->info['lose'].'","'.$u->info['nich'].'"
	) ');
	$p_now = mysql_fetch_array(mysql_query('SELECT * FROM `users_twink` WHERE `uid` = "'.$u->info['id'].'" AND `twink` = "'.$u->info['twink'].'" LIMIT 1'));
}else{
	//Обновляем текущие данные
	$p_now['login'] = $u->info['login'];
	$p_now['level'] = $u->info['level'];
	$p_now['exp'] = $u->info['exp'];
	$p_now['upLevel'] = $u->info['upLevel'];
	$p_now['stats'] = $u->info['stats'];
	$p_now['time'] = time();
	$p_now['ability'] = $u->info['ability'];
	$p_now['skills'] = $u->info['skills'];
	$p_now['sskills'] = $u->info['sskills'];
	$p_now['nskills'] = $u->info['nskills'];
	$p_now['priems'] = $u->info['priems'];
	$p_now['obraz'] = $u->info['obraz'];
	$p_now['win'] = $u->info['win'];
	$p_now['lose'] = $u->info['lose'];
	$p_now['nich'] = $u->info['nich'];
	$p_now['stopexp'] = $u->info['stopexp'];
	mysql_query('UPDATE `users_twink` SET
		`login` = "'.$u->info['login'].'",
		`level` = "'.$u->info['level'].'",
		`exp` = "'.$u->info['exp'].'",
		`upLevel` = "'.$u->info['upLevel'].'",
		`stats` = "'.$u->info['stats'].'",
		`time` = "'.$u->info['time'].'",
		`ability` = "'.$u->info['ability'].'",
		`skills` = "'.$u->info['skills'].'",
		`sskills` = "'.$u->info['sskills'].'",
		`nskills` = "'.$u->info['nskills'].'",
		`priems` = "'.$u->info['priems'].'",
		`obraz` = "'.$u->info['obraz'].'",
		`win` = "'.$u->info['win'].'",
		`lose` = "'.$u->info['lose'].'",
		`nich` = "'.$u->info['nich'].'",
		`stopexp` = "'.$u->info['stopexp'].'"
	WHERE `id` = "'.$p_now['id'].'" LIMIT 1');
}
if(isset($_GET['change_pers'])) {
	$p_sel = mysql_fetch_array(mysql_query('SELECT * FROM `users_twink` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.mysql_real_escape_string($_GET['change_pers']).'" LIMIT 1'));
	if(isset($p_sel['id']) && $p_sel['id'] != $p_now['id']) {
		//Заходим на персонажа
		mysql_query('UPDATE `users` SET
			`level` = "'.$p_sel['level'].'",
			`obraz` = "'.$p_sel['obraz'].'",
			`twink` = "'.$p_sel['twink'].'",
			`win` = "'.$p_sel['win'].'",
			`lose` = "'.$p_sel['lose'].'",
			`nich` = "'.$p_sel['nich'].'",
			`stopexp` = "'.$p_sel['stopexp'].'"
		WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		//
		mysql_query('UPDATE `stats` SET
			`upLevel` = "'.$p_sel['upLevel'].'",
			`stats` = "'.$p_sel['stats'].'",			
			`ability` = "'.$p_sel['ability'].'",
			`skills` = "'.$p_sel['skills'].'",
			`sskills` = "'.$p_sel['sskills'].'",
			`nskills` = "'.$p_sel['nskills'].'",
			`priems` = "'.$p_sel['priems'].'",			
			`exp` = "'.$p_sel['exp'].'"
		WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		//
		if( $p_sel['twink'] > 0 ) {
			//Возвращаем предметы с основного персонажа
			mysql_query('UPDATE `items_users` SET `uid` = "-91'.$u->info['id'].'" WHERE `uid` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('UPDATE `items_users` SET `uid` = "'.$u->info['id'].'" WHERE `uid` = "-92'.$u->info['id'].'" LIMIT 1');
		}else{
			//Возвращаем вещи с твинка
			mysql_query('UPDATE `items_users` SET `uid` = "-92'.$u->info['id'].'" WHERE `uid` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('UPDATE `items_users` SET `uid` = "'.$u->info['id'].'" WHERE `uid` = "-91'.$u->info['id'].'" LIMIT 1');
		}
		//
		mysql_query('UPDATE `items_users` SET `uid` = "'.$u->info['id'].'" WHERE (`uid` = "-91'.$u->info['id'].'" OR `uid` = "-92'.$u->info['id'].'") AND `inShop` = 1');
		if( $u->info['admin'] > 0 ) {
			die('UPDATE `items_users` SET `uid` = "'.$u->info['id'].'" WHERE (`uid` = "-91'.$u->info['id'].'" OR `uid` = "-92'.$u->info['id'].'") AND `inShop` = 1');
		}
		//
		die('<script>location.href="main.php?room=7";</script>');
	}else{
		echo '<b><font color=red>Персонаж не может быть выбран</font></b><br>';
	}
}elseif(isset($_GET['add_new_chars']) && $user_new_pers == true ) {
	$p_count = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users_twink` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
	$p_count = $p_count[0];
	if( $p_count > 9 ) {
		echo '<b><font color=red>Вы не можете создавать более десяти персонажей</font></b><br>';
	}else{
		mysql_query('INSERT INTO `users_twink` (
			`uid`,`twink`,`login`,`level`,`exp`,`upLevel`,`stats`,`time`,`ability`,`skills`,`sskills`,`nskills`,`priems`,`obraz`,`stopexp`
		) VALUES (
			"'.$u->info['id'].'","'.$p_count.'","'.$u->info['login'].'","4",
			"2500","22","s1=3|s2=3|s3=3|s4=7|rinv=40|m9=5|m6=10","'.time.'",
			"34","5","4","5","0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0",
			"0.gif","0"
		) ');
	}
}
$sp = mysql_query('SELECT * FROM `users_twink` WHERE `uid` = "'.$u->info['id'].'"');
$r = '';
while( $pl = mysql_fetch_array($sp) ) {
	if( $pl['id'] != $p_now['id'] ) {
		$r .= '<a href="main.php?room=7&change_pers='.$pl['id'].'">';
	}
	$r .= '<div style="';
	if( $pl['id'] == $p_now['id'] ) {
		$r .= 'border:5px solid green;';
	}else{
		$r .= 'border:5px solid #E2E0E0;';
	}
	$r .= 'position:relative;display:inline-block;width:120px;height:220px;background-image:url(\'http://img.xcombats.com/i/obraz/'.$u->info['sex'].'/'.$pl['obraz'].'\')">';
	$r .= '<div align="center" style="position:absolute;bottom:0px;left:0px;width:114px;background-color:#E2E0E0;margin:3px;"><b>'.$pl['login'].'</b> ['.$pl['level'].']<img width="12" height="11" src="http://img.xcombats.com/i/inf_capitalcity.gif"></div>';	
	$r .= '</div>';
	if( $pl['id'] != $p_now['id'] ) {
		$r .= '</a>';
	}
}
if( $user_new_pers == true ) {
	$r .= '<div style="';
	$r .= 'border:5px solid #E2E0E0;';
	$r .= 'position:relative;display:inline-block;width:120px;height:220px;background-image:url(\'http://img.xcombats.com/chars/'.$u->info['sex'].'/0.png\')">';
	$r .= '<div align="center" style="position:absolute;bottom:0px;left:0px;width:114px;background-color:#E2E0E0;margin:3px;"><a href="?add_new_chars=1&room=7">Добавить персонажа</a></div>';	
	$r .= '</div>';
}
echo $r;
?>

<?		;break;
        case 3:#-----Животные?>
<TABLE width=100% cellpadding=0 cellspacing=0 valign=top><TR style='padding-top: 10'>
<TD>

<TABLE cellpadding=0 cellspacing=0>
<?
if($cage1['pet_in_cage']==1) {
echo'<TD width=150 align=center><nobr><B>'.$cage1['name'].'</B> ['.$cage1['level'].']</nobr>
<A href="/main.php?pet_id='.$cage1['id'].'&sd4='.$u->info['id'].'&room=3&0.'.rand(0,9999999999999999).'" alt="Оставить"><IMG src="http://img.xcombats.com/i/obraz/'.$cage1['sex'].'/'.$cage1['obraz'].'.gif" width=120 height=220>';
}else{
echo'<TD width=150 align=center><nobr><B>свободно</B></nobr><BR>
<IMG src="http://img.xcombats.com/i/obraz/0/null.gif" width=120 height=220>';
}
?></A></TD>
<?if($cage2['pet_in_cage']==2) {
echo'<TD width=150 align=center><nobr><B>'.$cage2['name'].'</B> ['.$cage2['level'].']</nobr>
<A href="/main.php?pet_id='.$cage2['id'].'&sd4='.$u->info['id'].'&room=3&0.'.rand(0,9999999999999999).'" alt="Оставить"><IMG src="http://img.xcombats.com/i/obraz/'.$cage2['sex'].'/'.$cage2['obraz'].'.gif" width=120 height=220>';
}else{
echo'<TD width=150 align=center><nobr><B>свободно</B></nobr><BR>
<IMG src="http://img.xcombats.com/i/obraz/0/null.gif" width=120 height=220>';
}
?></A></TD>
</TABLE>
</TD>
<TD>&nbsp;</TD>
<TD width=150 align=center>
<?
if(!$pet) {
echo'<nobr><B>свободно</B></nobr>
<IMG src="http://img.xcombats.com/i/obraz/0/null.gif" width=120 height=220>';
}else{
echo'<nobr><B>'.$pet['name'].'</B> ['.$pet['level'].']</nobr>
<A href="/main.php?pet_id=-'.$pet['id'].'&sd4='.$u->info['id'].'&room=3&0.'.rand(0,9999999999999999).'" alt="Оставить"><IMG src="http://img.xcombats.com/i/obraz/'.$pet['sex'].'/'.$pet['obraz'].'.gif" width=120 height=220>';
}?>
</A>
</TD></TR></TABLE>
<?		;break;
        case 4:#-----Сон?>
Вы можете заснуть, забыв о внешнем мире.<BR>
Во время сна все временные эффекты на вас приостанавливаются. Это касается как, например, эликсиров, так и травм.<BR>
Сон не влияет на состояние предметов с ограниченным сроком существования<BR><BR>
<?=$div?>
Состояние: <B>Вы <?=$status;?></B><BR>
<?
if($sleep_mod==1){
echo '<font color=red><b>Во время сна нельзя перемещаться.</b></font><br>';
}
?>
<A href="?to_<?=$link;?>=1&sd4=<?=$u->info['id'];?>&room=4&0.<?=rand(0,9999999999999999);?>" ><?=$button;?></A><BR>
<?=$div1?>
</SMALL>
		<?}#--Конец свича $room
		;break;
			  case 4:#-----------------------------------------------смена аренды----------------------------------------?>
Вы можете сменить арендуемое помещение:<BR><BR>
<SMALL>
Для смены аренды на вашем балансе должна быть необходимая сумма.<BR>
Смена аренды, оплаченной зубами на кредитную аренду запрещена.<BR>
Количество вещей, сувениров и животных на вашем складе не должно превышать допустимое значение для выбранного арендуемого помещения.<BR>
</SMALL>
<?
if($objaga['type']!="base"){?>
<HR>
Арендовать Койку в общежитии<BR>
Цена: 1 кр. + 1 кр. в неделю.<BR>

 &bull; Размер сундука: 25 вещей<BR>
&bull; Сувениры: 50 шт.<BR>
 &bull; Койка<BR>
 
<A href="?changearenda=base&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 1 кр. за смену помещения?')">Сменить помещение</A>
<?}
if($objaga['type']!="advanced"){?>
<HR>
Арендовать Койку с тумбочкой<BR>
Цена: 3 кр. + 3 кр. в неделю.<BR>

 &bull; Размер сундука: 40 вещей<BR>
&bull; Сувениры: 150 шт.<BR>
 &bull; Койка<BR>


<A href="?changearenda=advanced&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 3 кр. за смену помещения?')">Сменить помещение</A>
<?}
if($objaga['type']!="advanced2"){?>
<HR>
Арендовать Койку со шкафом<BR>
Цена: 10 кр. + 10 кр. в неделю.<BR>

 &bull; Размер сундука: 70 вещей<BR>
&bull; Сувениры: 200 шт.<BR>
 &bull; Мест для животных: 2 <BR>
 &bull; Койка<BR>


<A href="?changearenda=advanced2&sd4=<?=$u->info['id'];?>" onClick="return confirm('Вы уверены, что хотите заплатить 10 кр. за смену помещения?')">Сменить помещение</A>
<?}
echo'<HR>';
}
?>
</TD>

	<td width="280" valign="top">
    <TABLE cellspacing="0" cellpadding="0"><TD width="100%">&nbsp;</TD><TD>
	<table  border="0" cellpadding="0" cellspacing="0">
	<tr align="right" valign="top">
	<td>

<!-- -->
<? echo $goLis; ?>
<!-- -->

<table width="100"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td nowrap="nowrap">
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
<? if($u->info['room'] == 214 && $sleep_mod == 0) { ?>
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.11&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.11',1); ?>">Страшилкина Улица</a></td>
</tr>
<?
if($ar_lvl>=1){?>
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.217&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.217',1); ?>">Общ. Этаж 1</a></td>
</tr>
<? } } ?>
<? if($u->info['room']=='217' and $ar_lvl>=1 and $sleep_mod==0) { ?>
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.214&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.214',1); ?>">Общежитие</a></td>
</tr>
<?
}if($u->info['room']=='217' and $ar_lvl>=2 and $sleep_mod==0){
?>
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.218&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.218',1); ?>">Общ. Этаж 2</a></td>
</tr>
<?}?>
<?if($u->info['room']=='218' and $ar_lvl>=2 and $sleep_mod==0){?>
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.217&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.217',1); ?>">Общ. Этаж 1</a></td>
</tr>
<?
}
if($u->info['room']=='218' and $ar_lvl>=3 and $sleep_mod==0){
?>
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.219&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.219',1); ?>">Общ. Этаж 3</a></td>
</tr>
<? }
if($u->info['room']=='219' and $ar_lvl>=3 and !isset($sleep['id'])){
?>
<tr>
<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.218&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.218',1); ?>">Общ. Этаж 2</a></td>
</tr>
<? } ?>
</table>
</td>
</tr>
</table>
<!-- <br /><span class="menutop"><nobr>Общежитие</nobr></span>-->
</td>
</tr>
</table>
<div id="mmoves" style="background-color:#FFFFCC; visibility:hidden; overflow:visible; position:absolute; border-color:#666666; border-style:solid; border-width: 1px; padding: 2px; white-space: nowrap; z-index:200"></div>
</HTML>
</TD></TR>
<TR><TD colspan=2>
<NOBR>
Деньги: <?=$u->info['money'];?> кр.<BR><BR>
<? if($u->info['room']=="214"){?>
<B>Аренда</B>
<? }else{
//Коридор
//$room_array = array (1=>'Комната',2=>'Сундук',3=>'Сувениры',4=>'Животные',5=>'Сон');
   if($_GET['room']!=1){echo"<a href='?room=1&0.".$code."'><B>Комната</B></a>";}else{echo"<B>Комната</B>";}?><BR>
<? if($_GET['room']!=2){echo"<A href='?room=2&0.".$code."'>Сундук</A>";}else{echo"<B>Сундук</B>";}?><BR>
<? if($_GET['room']!=6){echo"<A href='?room=6&0.".$code."'>Сувениры</A>";}else{echo"<B>Сувениры</B>";}?><BR>
<? if($_GET['room']!=7){echo"<A href='?room=7&0.".$code."'>Персонажи</A>";}else{echo"<B>Персонажи</B>";}?><BR>
<?
if($objaga['type']=="advanced2") {
   if($_GET['room']!=3){echo"<A href='?room=3&0.".$code."'>Животные</A><BR>";}else{echo"<B>Животные</B><BR>";}
}?>
<? if($_GET['room']!=4){echo"<A href='?room=4&0.".$code."'>Сон</A>";}else{echo"<B>Сон</B>";}?>
<? } ?>
<?
} #end hostel
?>