<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='entercat')
{

if(isset($_GET['rz'])) {
	$rz = 1;
}else{
	$rz = 0;
}

$dun = 3; //для новичков 2-7 лвл

$er = '';

$dzs = '';

$g111 = 1;
$g11 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh1" AND `time`>'.(time()-7200).' LIMIT 1',1);

$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));

if($u->info['dn']>0)
{
	$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `id`="'.$u->info['dn'].'" AND `delete` = "0" LIMIT 1'));
	if(!isset($zv['id']))
	{
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
	}
}

if(isset($g11['id']))
{
	$g111 = 0;
	if(isset($_GET['start']))
	{
		$re = 'Поход в пещеры разрешен один раз в два часа. Осталось еще: '.$u->timeOut(7200-time()+$g11['time']);
	}
}
if(isset($_GET['start']) && $zv['uid']==$u->info['id'] && $g111 == 1)
{	
	//начинаем поход
	//начинаем поход
	$ig = 1;
	if($ig>0)
	{
		//перемещаем игроков в пещеру
		//$u->addAction(time(),'psh1','');
		$ins = mysql_query('INSERT INTO `dungeon_now` (`city`,`uid`,`id2`,`name`,`time_start`)
		VALUES ("'.$zv['city'].'","'.$zv['uid'].'","'.$dun.'","Катакомбы","'.time().'")');
		if($ins)
		{
			$zid = mysql_insert_id();
			//обновляем пользователей
			$su = mysql_query('SELECT `u`.`id`,`st`.`dn` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$zv['id'].'" LIMIT '.($zv['team_max']+1).'');
			$ids = '';
			while($pu = mysql_fetch_array($su))
			{
				$ids .= ' `id` = "'.$pu['id'].'" OR';
				$u->addAction(time(),'psh1','',$pu['id']);
			}
			$ids = rtrim($ids,'OR');
			$upd1 = mysql_query('UPDATE `stats` SET `s`="0",`res_s`="0",`x`="0",`y`="0",`res_x`="0",`res_y`="0",`dn` = "0",`dnow` = "'.$zid.'" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
			if($upd1)
			{
				$upd2 = mysql_query('UPDATE `users` SET `room` = "19" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
				//Добавляем ботов и обьекты в пещеру $zid с for_dn = $dun
				//Добавляем ботов
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$dun.'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['id_bot'].'","'.$pl['colvo'].'","'.$pl['items'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['dialog'].'","'.$pl['items'].'"),';
				}
				$vls = rtrim($vls,',');				
				$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`) VALUES '.$vls.'');
				//Добавляем обьекты
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "'.$dun.'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
				}
				$vls = rtrim($vls,',');	
				if($vls!='')
				{			
					$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
				}else{
					$ins2 = true;
				}
				if($upd2 && $ins1 && $ins2)
				{
					mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
					die('<script>location="main.php?rnd='.$code.'";</script>');
				}else{
					$re = 'Ошибка перехода в подземелье...';
				}
			}else{
				$re = 'Ошибка перехода в подземелье...';
			}
		}else{
			$re = 'Ошибка перехода в подземелье...';
		}
	}
}elseif(isset($_POST['go'],$_POST['goid']) && $g111==1)
{
	if(!isset($zv['id']))
	{
		$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `id`="'.mysql_real_escape_string($_POST['goid']).'" AND `delete` = "0" LIMIT 1'));
		if(isset($zv['id']))
		{
			if($u->info['level']>7)
			{
				$row = 0;
				if(5>$row)
				{
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if(!$upd)
					{
						$re = 'Не удалось вступить в эту группу';
						unset($zv);
					}else{
						$u->info['dn'] = $zv['id'];
					}
				}else{
					$re = 'В группе нет места';
					unset($zv);
				}
			}else{
				$re = 'Вы не подходите по уровню';
				unset($zv);
			}
		}else{
			$re = 'Заявка не найдена';
		}
	}else{
		$re = 'Вы уже находитесь в группе';
	}
}elseif(isset($_POST['leave']) && isset($zv['id']) && $g111 == 1)
{
	if($zv['uid']==$u->info['id'])
	{
		//ставим в группу нового руководителя
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ld['id']))
		{
			$zv['uid'] = $ld['id'];
			mysql_query('UPDATE `dungeon_zv` SET `uid` = "'.$zv['uid'].'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}else{
			//удаляем группу целиком
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}
	}else{
		//просто выходим с группы
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
		unset($zv);
	}
}elseif(isset($_POST['add']) && $u->info['level']>1 && $g111 == 1)
{
	if($u->info['dn']==0)
	{		
		$ins = mysql_query('INSERT INTO `dungeon_zv`
		(`city`,`time`,`uid`,`dun`,`pass`,`com`,`lvlmin`,`lvlmax`,`team_max`) VALUES
		("'.$u->info['city'].'","'.time().'","'.$u->info['id'].'","'.$dun.'",
		"'.mysql_real_escape_string($_POST['pass']).'",
		"'.mysql_real_escape_string($_POST['text']).'",
		"8",
		"21",
		"5")');
		if($ins)
		{
			$u->info['dn'] = mysql_insert_id();
			$zv['id'] = $u->info['dn'];
			$zv['uid'] = $u->info['id'];
			mysql_query('UPDATE `stats` SET `dn` = "'.$u->info['dn'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$re = 'Вы успешно создали группу';
		}else{
			$re = 'Не удалось создать группу';
		}
	}else{
		$re = 'Вы уже находитесь в группе';
	}
}

//Генерируем список групп
$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `dun` = "3" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');
while($pl = mysql_fetch_array($sp))
{
	$dzs .= '<div style="padding:2px;">';
	if($u->info['dn']==0)
	{
		$dzs .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	}
	$dzs .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	
	$pus = ''; //группа
	$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
	while($pu = mysql_fetch_array($su))
	{
		$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" title="Инф. о '.$pu['login'].'"></a>';
		$pus .= ', ';
	}
	$pus = trim($pus,', ');
	
	$dzs .= $pus;
	
	if($pl['com']!='')
	{
		$dl = '';
		if(($moder['boi']==1 || $u->info['admin']>0) && $pl['dcom']==0)
		{
			$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">удалить комментарий</a>)';
			if(isset($_GET['delcom']) && $_GET['delcom']==$pl['id'] && $u->newAct($_GET['key'])==true)
			{
				mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['dcom'] = $u->info['id'];
			}
		}
		
		$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
		
		if($pl['dcom']>0)
		{
			$dl = ' <font color="grey"><i>комментарий удален модератором</i></font>';
		}	
		
		if($pl['dcom']>0)
		{
			if($moder['boi']==1 || $u->info['admin']>0)
			{
				$pl['com'] = '<font color="red">'.$pl['com'].'</font>';
			}else{
				$pl['com'] = '';
			}
		}
		
		$dzs .= '<small> | '.$pl['com'].''.$dl.'</small>';
	}
		
	$dzs .= '</div>';
}
?>
<style>
body
{
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div></td>
    <td width="200"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td>
          <? if($rz==0) { ?>
          <table  border="0" cellpadding="0" cellspacing="0">
              <tr align="right" valign="top">
                <td><!-- -->
                    <? echo $goLis; ?>
                    <!-- -->
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                            <tr>
                              <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=<? if($u->info['city']=='fallenearth'){ echo '6.180.0.102'; } else {echo '1.180.0.321'; }?>&rnd=<? echo $code; ?>';" title="<? 
							  if($u->info['city']=='fallenearth'){
								thisInfRm('6.180.0.102',1); 
							  }else {
								thisInfRm('1.180.0.321',1);
							  }
							  ?>"><?
							  if($u->info['city']=='fallenearth'){
								echo "Темный Портал";
							  }else {
								echo "Магический Портал";
							  }
							  ?></a></td>
                            </tr>
                        </table>
						</td>
                      </tr>
                  </table></td>
              </tr>
          </table>
          <? } ?>
          </td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
                          <? if($rz == 1) { ?>
                          <div align="center" style="float:right;width:100px;">
                            <p>
                              <input type='button' onclick='location="main.php?rz=1"' value="Обновить" />
                              <br />
                              <input type='button' onclick='location="main.php"' value="Вернуться" />
                            </p>
                          </div>
                          <? }else{ ?>
<div align="center" style="float:right;width:100px;">
                            <p>
                              <input type='button' onclick='location="main.php"' value="Обновить" />
                              <br />
                              <input type='button' onclick='location="main.php?rz=1"' value="Задания" />
                            </p>
                          </div>
                          <? } ?>
<?
if($re!='')
{
	echo '<font color="red"><b>'.$re.'</b></font><br>';
}

//отображаем
if($dzs=='')
{
	$dzs = '';
}else{
	if(!isset($zv['id']))
	{
		if($g111==1)
		{
			$pr = '<input name="go" type="submit" value="Вступить в группу">';
		}
		$dzs = '<form autocomplete="off" action="main.php?rnd='.$code.'" method="post">'.$pr.'<br>'.$dzs.''.$pr.'</form>';
	}
	$dzs .= '<hr>';
}

if($rz==0) { echo $dzs; }
if($rz == 1) {
?>
<div>
      <form autocomplete="off" action='/main.php' method="post" name="F1" id="F1">
<?
$qsee = '';
$qx = 0;
$hgo = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_demonscity" LIMIT 1',1);
if(isset($_GET['add_quest'])) {
	if(isset($hgo['id'])) {
		echo '<font color="red"><b>Нельзя получать задания чаще одного раза в сутки</b></font><br>';
	}else{
		
		$sp = mysql_query('SELECT * FROM `quests` WHERE `line` = 3');
		$dq_add = array();
		while($pl = mysql_fetch_array($sp)) {
			if($u->rep['repdemonscity'] == 9999) {
				//квет, рыцарского задания
				if( $pl['kin'] == 1 ) {
					$dq_add = array( 0 => $pl );
				}
			}elseif($u->rep['repdemonscity'] == 24999) {
				//квет, рыцарского задания
				if( $pl['kin'] == 2 ) {
					$dq_add = array( 0 => $pl );
				}
			}else{
				if( $pl['kin'] == 0 ) {
					$dq_add[count($dq_add)] = $pl;
				}
			}
		}
		
		$dq_add = $dq_add[rand(0,count($dq_add)-1)];
		
		if($q->testGood($dq_add)==1)
		{
			$q->startq_dn($dq_add['id']);
			echo '<font color="red"><b>Вы успешно получили новое задание &quot;'.$dq_add['name'].'&quot;.</b></font><br>';
			$hgo['id'] = 1;
			$u->addAction(time(),'psh_qt_demonscity',$dq_add['id']);
		}else{
			echo '<font color="red"><b>Не удалось получить задание &quot;'.$dq_add['name'].'&quot;. Попробуйте еще...</b></font><br>';
		}
		unset($dq_add);
	}
}

//Генерируем список текущих квестов
$sp = mysql_query('SELECT * FROM `actions` WHERE `vars` LIKE "%start_quest%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 100');
while($pl = mysql_fetch_array($sp))
{
	$pq = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$pl['vars']).'" LIMIT 1'));
	$qsee .= '<a href="main.php?rz=1&end_qst_now='.$pq['id'].'"><img src="http://img.xcombats.com/i/clear.gif" title="Отказаться от задания"></a> <b>'.$pq['name'].'</b><div style="padding-left:15px;padding-bottom:5px;border-bottom:1px solid grey"><small>'.$pq['info'].'<br>'.$q->info($pq).'</small></div><br>';
	$qx++;
}

if($qsee == '')
{
	$qsee = 'К сожалению у вас нет ни одного задания';
}
?>
<Br />
<FIELDSET>
<LEGEND><B>Текущие задания: </B>[<?=$qx?>/28]</LEGEND>
<?=$qsee?>
<span style="padding-left: 10">
<?
if(!isset($hgo['id'])) {
?>
<br />
<input type='button' value='Получить задание' onclick='location="main.php?rz=1&add_quest=1"' />
<?
}else{ 
	echo 'Получить новое задание можно <b>'.date('d.m.Y H:i',$hgo['time']+60*60*24).'</b> <font color="grey">( Через '.$u->timeOut($hgo['time']+60*60*24-time()).' )</font>';
}
?>
</span>
</FIELDSET>
      </form>
      <br />
      <?
	  //Начисление бонуса награды
	   if(isset($_GET['buy1'])) {
		  $dungeon = array(1=>'capitalcity',2=>'demonscity',3=>'angelscity');
		  if($_GET['buy1']==1) {
			  //покупаем статы
			  $price = 2000+($u->rep['add_stats']*100);
			  $cur_price = array('price'=>0);
			  if(25-$u->rep['add_stats']>0 && $u->rep['allrep']-$u->rep['allnurep']>=$price) { // Характеристики!
				  
				  foreach($dungeon as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  }
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>Вы успешно приобрели 1 способность за '.$price.' ед. награды</b></font><br>';
					$u->info['ability']  += 1;
					$u->rep['add_stats'] += 1;
					
					foreach($dungeon as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_stats` = "'.$u->rep['add_stats'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `ability` = "'.$u->info['ability'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo 'Недостаточно репутации.';
			  } else {
				 echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			  }
		  } elseif($_GET['buy1']==2) { // Умения!
			  $price = 2000+(2000*$u->rep['add_skills']);
			  $cur_price = array('price'=>0); 
			  if(10-$u->rep['add_skills']>0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // Умения!
				   foreach($dungeon as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  }
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>Вы успешно приобрели 1 умение за '.$price.' ед. награды</b></font><br>';
					$u->info['skills']  += 1;
					$u->rep['add_skills'] += 1;
					
					foreach($dungeon as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_skills` = "'.$u->rep['add_skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `skills` = "'.$u->info['skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo 'Недостаточно репутации.';
			  } else {
				 echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			  }
		  }elseif($_GET['buy1']==3) { // Кредиты
			  $price = 100;
			  $cur_price = array('price'=>0); 
			  if( $u->rep['allrep'] - $u->rep['allnurep'] >= $price) { // Покупаем кредиты
				  foreach($dungeon as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  }
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>Вы успешно приобрели 10 кр. за '.$price.' ед. награды</b></font><br>';
					$u->info['money']  += 10;
					$u->rep['add_money'] += 10;
					
					foreach($dungeon as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_money` = "'.$u->rep['add_money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo 'Недостаточно репутации.'; 
			  }else{
				 echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			  }
		  }elseif( $_GET['buy1'] == 4 ) { // Особенности
			  $price = 3000;
			  $cur_price = array('price'=>0);
			  if( 5 - $u->rep['add_skills2'] > 0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // Особенности
				  foreach($dungeon as $key=>$val){
					if(!($cur_price['price'] >= $price)){
					  if( $u->rep['rep'.$val] - $u->rep['nu_'.$val] > $price ){
						$cur_price['price'] 	= $price;
						$cur_price['nu_'.$val] 	= $price;
					  } elseif( $u->rep['rep'.$val] - $u->rep['nu_'.$val] < $price ){
						$cur_price['price']	+= $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1))); 
						$cur_price['nu_'.$val] = $cur; 
					  }
					}
				  }
				  if($price==$cur_price['price']) {
					echo '<font color="red"><b>Вы успешно приобрели 1 особенность за '.$price.' ед. награды</b></font><br>';
					$u->info['nskills']  += 1;
					$u->rep['add_skills2'] += 1;
					
					foreach($dungeon as $key=>$val){
					  if($key!='price'){
						$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
						mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					  }
					}
					mysql_query('UPDATE `rep` SET `add_skills2` = "'.$u->rep['add_skills2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `nskills` = "'.$u->info['nskills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				  } else echo 'Недостаточно репутации.';  
			  }else{
				 echo '<font color="red"><b>Ничего не получилось...</b></font><br>'; 
			  }
	  	}
	  }
	  ?>
      <fieldset style='padding: 5,5,5,5'>
        <legend>Награда: <b>
        <?=($u->rep['allrep']-$u->rep['allnurep'])?> 
        ед.</b></legend>
        <table>
          <tr>
            <td>Способность (еще <?=(25-$u->rep['add_stats'])?>)</td>
            <td style='padding-left: 10px'>за <?=2000+($u->rep['add_stats']*100);?> ед.</td>
            <td style='padding-left: 10px'><input type='button' value='Купить'
onclick="if (confirm('Купить: Способность?\n\nКупив способность, Вы сможете увеличить характеристики персонажа.\nНапример, можно увеличить силу.')) {location='main.php?rz=1&buy1=1'}" /></td>
          </tr>
          <tr>
            <td>Умение (еще <?=(10-$u->rep['add_skills'])?>)</td>
            <td style='padding-left: 10px'>за <?=2000+(2000*$u->rep['add_skills']);?> ед.</td>
            <td style='padding-left: 10px'><input type='button' value='Купить'
onclick="if (confirm('Купить: Умение?\n\nУмение даёт возможность почуствовать себя мастером меча, топора, магии и т.п.')) {location='main.php?rz=1&buy1=2'}" /></td>
          </tr>
          <tr>
            <td>Деньги (10 кр.)</td>
            <td style='padding-left: 10px'>за 100 ед.</td>
            <td style='padding-left: 10px'><input type='button' value='Купить'
onclick="if (confirm('Купить: Деньги (10 кр.)?\n\nНаграду можно получить полновесными кредитами.')) {location='main.php?rz=1&buy1=3'}" /></td>
          </tr>
          <tr>
            <td>Особенность (еще <?=(5-$u->rep['add_skills2'])?>)</td>
            <td style='padding-left: 10px'>за 3000 ед.</td>
            <td style='padding-left: 10px'><input type='button' value='Купить'
onclick="if (confirm('Купить: Особенность?\n\nОсобенность - это дополнительные возможности персонажа, не дающие преимущества в боях.\nНапример, можно увеличить скорость восстановления HP')) {location='main.php?rz=1&buy1=4'}" /></td>
          </tr>
        </table>
        <p><span style="padding-left: 10">
        <? 
		$chk = mysql_fetch_array(mysql_query('SELECT COUNT(`u`.`id`),SUM(`m`.`price1`) FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `u`.`item_id` = `m`.`id` WHERE `m`.`type` = "61" AND `u`.`delete` = "0" AND `u`.`inOdet` = "0" AND `u`.`inShop` = "0" AND `u`.`inTransfer` = "0" AND `u`.`uid` = "'.$u->info['id'].'" LIMIT 1000'));
		if(isset($_GET['buy777']) && $chk[0]>0) {
			 $chk_cl = mysql_query('SELECT `u`.`id`,`m`.`price1` FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `u`.`item_id` = `m`.`id` WHERE `m`.`type` = "61" AND `u`.`delete` = "0" AND `u`.`inOdet` = "0" AND `u`.`inShop` = "0" AND `u`.`inTransfer` = "0" AND `u`.`uid` = "'.$u->info['id'].'" LIMIT 1000');
			 while($chk_pl = mysql_fetch_array($chk_cl)) {
				 if(mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$chk_pl['id'].'" LIMIT 1'));
				 { 
				 	$x++; $prc += $chk_pl['price1'];
				 }
			 }
			 $u->info['money'] += $prc;
			 mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			 echo '<font color="red"><b>Вы успешно сдали чеки в количестве '.$x.' шт. на сумму '.$prc.' кр.</b></font><br>'; 
			 $chk[0] = 0;
		
		}
		if($chk[0]>0) {
		?>
          <input type='button' value='Сдать чеки'
onclick="if (confirm('Сдать все чеки (<?=$chk[0]?> шт.) находящиеся у Вас в инвентаре за <?=$chk[1]?> кр. ?')) {location='main.php?rz=1&buy777=1'}" />
		<? } ?>
        </span></p>
      </fieldset>
	  <fieldset style='margin-top:15px;'>
		<table> 
		  <tr>
			<td width="200">Репутация в Capital city:</td>
			<td><?=$u->rep['repcapitalcity']?> ед. </td>
		  </tr>
          <tr>
			<td>Репутация в Demons city:</td>
			<td><?=$u->rep['repdemonscity']?> ед.</td>
		  </tr>
          <tr>
			<td>Репутация в Angels city:</td>
			<td><?=$u->rep['repangelscity']?> ед. </td>
		  </tr> 
        </table>
        <legend>Текущая репутация:</legend> 
      </fieldset>
</div>
<?
}else{
if($g111 == 1)
{
if($u->info['dn']==0)
{
?>
<table width="350" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
    <form id="from" autocomplete="off" name="from" action="main.php?pz1=<? echo $code; ?>" method="post">
      <fieldset style='padding-left: 5; width=50%'>
      <legend><b> Группа </b> </legend>
        Комментарий
        <input type="text" name="text" maxlength="40" size="40" />
      <br />
        Пароль
  <input type="password" name="pass" maxlength="25" size="25" />
  <br />
  <input type="submit" name="add" value="Создать группу" />
  &nbsp;<br />
      </fieldset>
    </form>
    </td>
  </tr>
</table>
<?
}else{
    $psh_start = '';
	if(isset($zv['id']))
	{
		if($zv['uid']==$u->info['id'])
		{
			$psh_start = '<INPUT type=\'button\' name=\'start\' value=\'Начать\' onClick="top.frames[\'main\'].location = \'main.php?start=1&rnd='.$code.'\'"> &nbsp;';
		}
		  
		echo '<br><FORM autocomplete="off" id="REQUEST" method="post" style="width:210px;" action="main.php?rnd='.$code.'">
		<FIELDSET style=\'padding-left: 5; width=50%\'>
		<LEGEND><B> Группа </B> </LEGEND>
		'.$psh_start.'
		<INPUT type=\'submit\' name=\'leave\' value=\'Покинуть группу\'> 
		</FIELDSET>
		</FORM>';
	}
}
?>

<?
}else{
	echo 'Поход в пещеры разрешен один раз в два часа. Осталось еще: '.$u->timeOut(7200-time()+$g11['time']).'<br><small style="color:grey">Но Вы всегда можете приобрести ключ от прохода у любого &quot;копателя пещер&quot; в Торговом зале ;)</small>';
}
}
}
?>
