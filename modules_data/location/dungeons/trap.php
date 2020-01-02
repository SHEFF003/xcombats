<?php
if(!defined('GAME'))die();

if(isset($file) && $file[0]=='dungeons/trap.php'){
	
	$actions = array();
	$action = explode('|',$file[1]);
	//id_bot:col | 
	foreach( $action as $value ) {
		$temp = explode(':',$value);
		$actions[$temp[0]]= $temp[1];
	}
	# attackBot:1|left=1|right=1
	if( isset($actions['attackBot']) && $actions['attackBot'] != '' ) {
		$attackBot = array();
		if( isset($actions['left']) ) $attackBot[] = array( 'x' => (int)$u->info['x']-1, 'y' => (int)$u->info['y'] );
		if( isset($actions['right']) ) $attackBot[] = array( 'x' => (int)$u->info['x']+1, 'y' => (int)$u->info['y'] );
		if( isset($actions['top']) ) $attackBot[] = array( 'x' => (int)$u->info['x'], 'y' => (int)$u->info['y']+1 );
		if( isset($actions['bottom']) ) $attackBot[] = array( 'x' => (int)$u->info['x'], 'y' => (int)$u->info['y']-1 );
		$action = '';
		foreach ($attackBot as $temp) {
			if($action!='') $action .= ' OR ';
			$action .= '(`x` = "'.$temp['x'].'" AND `y` = "'.$temp['y'].'")';
		}
		#$test = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_nakova2_use" LIMIT 1'));
		$temp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `dn` = "'.$u->info['dnow'].'" AND ('.$action.') AND `delete`=\'0\' AND `inBattle`=\'0\' LIMIT 10');
		#echo '<INPUT TYPE="button" value="ќбновить" onclick="location =\''.$_SERVER['REQUEST_URI'].'\';"> ≈сли страница не сработала, обновитесь ';
		while($t = mysql_fetch_array($temp)){ 
			if( isset($t['id_bot']) ) $d->botAtack($t,$u->info,2); 
		}
		/*
		if( $u->info['sex'] == 0 ) {
			$d->sys_chat('<b>'.$u->info['login'].'</b> сделав необдуманый шаг, подвергс€ нападению ');
		}else{
			$d->sys_chat('<b>'.$u->info['login'].'</b> воспользовалась &quot;Ќаковальней&quot;, другим данный обьект стал недоступен');
		}
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`time`,`vars`) VALUES (
			"'.$u->info['dnow'].'","'.$u->info['id'].'","'.time().'","obj_nakova2_use"
		)');
		*/
		#var_info($attackBot);
	} elseif(isset($actions['lossLife']) && $actions['lossLife'] != '' ) {
		if( (int)$actions['lossLife'] > 1 ) $actions['lossLife'] = $actions['lossLife']; else $actions['lossLife'] = 1;
		$vad['count_uses'] = mysql_fetch_array(mysql_query('SELECT COUNT(*), id, vals FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" AND `vars` = "trap_act" LIMIT '.$actions['lossLife'].''));
		if( (int)$actions['lossLife'] > 1 && (int)$actions['lossLife'] >= $vad['count_uses'][0]  ) {
				$vad['count_uses'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) , id, vals FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `uid` = "'.$u->info['id'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" AND `vars` = "trap_act" LIMIT 1'));
		}
		$vad['count_uses']['vals'] = explode(':', $vad['count_uses']['vals']); 
		if( $vad['count_uses'][0]  == 0 ) {
			if( isset($actions['hp']) && $actions['hp'] !='' ){
				$vad['hp'] = $actions['hp'];
			} elseif( isset($actions['hpMax'])) {
				if( !isset($actions['hpMin']) ) $actions['hpMin'] = 0;
				$vad['hp'] = rand($actions['hpMin'],$actions['hpMax']);
			}

			if(isset($vad['hp']) && $vad['hp'] != '' ){
				$vad['hp'] = round($u->stats['hpAll']*($vad['hp']/100));
				if(($u->info['hpNow']-$vad['hp']) > 0){
					mysql_query('UPDATE `stats` SET `hpNow` = "'.($u->info['hpNow']-$vad['hp']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$vad['text']='damage:'.$vad['hp'].'';
				} else {
					mysql_query('UPDATE `stats` SET `hpNow` = "-1000" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$vad['text']='die:'.$vad['hp'].'';
				}
				mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES ("'.$u->info['dnow'].'","'.time().'","'.$u->info['x'].'","'.$u->info['y'].'","'.$u->info['id'].'","trap_act","ready_'.$vad['text'].'")');
				header('location: main.php');
			}
		} elseif( isset($vad['count_uses']) && ( $vad['count_uses']['vals'][0]=='ready_die' OR $vad['count_uses']['vals'][0]=='ready_damage')) {
			if($vad['count_uses']['vals'][0]=='ready_damage'){
				$d->error = '¬ы попали в ловушку и получили повреждени€ на -'.$vad['count_uses']['vals'][1].'HP...'; 
			}
			if($u->info['sex'] == 0) {
				$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> угодил в ловушку оставленную одним из обитателей подземель€. <b>-'. $vad['count_uses']['vals'][1].'HP</b>';
			}else{
				$vad['text'] = '[img[items/trap.gif]] <b>'.$u->info['login'].'</b> угодила в ловушку оставленную одним из обитателей подземель€. <b>-'. $vad['count_uses']['vals'][1].'HP</b>';	
			}
			$d->sys_chat($vad['text']);
			mysql_query('UPDATE `dungeon_actions` SET `vals` = "end" WHERE `id` = "'.$vad['count_uses']['id'].'" LIMIT 1');
			if($vad['count_uses']['vals'][0]=='ready_die'){ 
				header('location: main.php');
			}
		} else {
			$d->testDie(); 
		}
	}
	unset($temp,$actions,$r,$vad);
	
}