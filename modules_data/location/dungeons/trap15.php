<?php
if(!defined('GAME'))die();

if(isset($file) && $file[0]=='dungeons/trap15.php'){
	
	$actions = array();
	$action = explode('|',$file[1]);
	//id_bot:col | 
	foreach( $action as $value ) {
		$temp = explode(':',$value);
		$actions[$temp[0]]= $temp[1];
	}
	# attackBot:1|left=1|right=1
	$vad = array();
	//
	$vad['rnd'] = rand(-3,5);
	if($vad['rnd'] == 1) {
		//������������ ��������� (��������: -5) 
		$vad['img'] = 'hockey_trap_agil';
		$vad['g'] = '������������ ���������';
		$vad['id_eff'] = 391;
		$vad['eff'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `id_eff` = "'.$vad['id_eff'].'" LIMIT 1'));
		$vad['data'] = 'add_s2=-'.(round($vad['eff']['x']+1)*5).'';
	}elseif($vad['rnd'] == 2) {
		//����������� ������� (��������: -5) 
		$vad['img'] = 'hockey_trap_inst';
		$vad['g'] = '����������� �������';
		$vad['id_eff'] = 392;
		$vad['eff'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `id_eff` = "'.$vad['id_eff'].'" LIMIT 1'));
		$vad['data'] = 'add_s3=-'.(round($vad['eff']['x']+1)*5).'';
	}elseif($vad['rnd'] == 3) {
		//�������� ��������� (������� ����� (��): -10%) 
		$vad['img'] = 'hockey_trap_hp';
		$vad['g'] = '�������� ���������';
		$vad['id_eff'] = 393;
		$vad['eff'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `id_eff` = "'.$vad['id_eff'].'" LIMIT 1'));
		$vad['data'] = 'add_hpProc=-'.(round($vad['eff']['x']+1)*10).'';
	}elseif($vad['rnd'] == 4) {
		//��������� �������� (����: -5) 
		$vad['img'] = 'hockey_trap_str';
		$vad['g'] = '��������� ��������';
		$vad['id_eff'] = 394;
		$vad['eff'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `id_eff` = "'.$vad['id_eff'].'" LIMIT 1'));
		$vad['data'] = 'add_s1=-'.(round($vad['eff']['x']+1)*5).'';
	}elseif($vad['rnd'] == 5) {
		//����������� �������� ������������ 
		$vad['img'] = 'hockey_trap_speed';
		$vad['g'] = '����������� �������� ������������';
		$vad['id_eff'] = 395;
		$vad['eff'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `id_eff` = "'.$vad['id_eff'].'" LIMIT 1'));
		$vad['data'] = 'add_speed_dungeon=-'.(round($vad['eff']['x']+1)*20).'';
	}else{
		$vad['g'] = '';
	}
	
	if( $vad['eff']['x'] > 4 ) {
		$d->error = '�� � ��� ����������, �� �� ��������� �� �������, � ������ ������� �� ����...';
	}elseif( $vad['g'] == '' ) {
		$d->error = '�� ����� �� �����, �� � ��������� ��� ��� ����� �� �������!';
	}else{
		$d->error = '�� ����� � �������� ���������� ������ &quot;'.$vad['g'].'&quot;...'; 
		//
		//��������� ����� �������
		if(!isset($vad['eff']['id'])) {
			mysql_query('INSERT INTO `eff_users` (
				`id_eff`,`uid`,`name`,`data`,`timeUse`
			) VALUES (
				"'.$vad['id_eff'].'","'.$u->info['id'].'","'.$vad['g'].'","'.$vad['data'].'","'.time().'"
			)');
			//
			if($u->info['sex'] == 0) {
				$vad['text'] = '[img[items/'.$vad['img'].'.gif]] <b>'.$u->info['login'].'</b> ������������� � ������� ������ &quot;'.$vad['g'].'&quot;!';
			}else{
				$vad['text'] = '[img[items/'.$vad['img'].'.gif]] <b>'.$u->info['login'].'</b> �������������� � �������� ������ &quot;'.$vad['g'].'&quot;!';	
			}
			$d->sys_chat($vad['text']);
		}else{
			mysql_query('UPDATE `eff_users` SET `data` = "'.$vad['data'].'" , `x` = `x`+1 WHERE `id` = "'.$vad['eff']['id'].'" LIMIT 1');
		}
	}
	//
	unset($temp,$actions,$r,$vad);
	
}