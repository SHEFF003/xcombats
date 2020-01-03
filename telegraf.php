<?php
	header('Content-Type: text/html; charset=windows-1251');
	
	define('GAME',true);
	include('_incl_data/__config.php');	
	include('_incl_data/class/__db_connect.php');
	//include('_incl_data/class/__chat_class.php');	
	include('_incl_data/class/__user.php');
	function test_line($v) {
		$r = true;
		$v = str_replace(' ','',$v);
		$v = str_replace('	','',$v);
		if($v == '') {
			$r = false;
		}
		return $r;
	}
	
	function go_text($v) {		
		$v = str_replace('\x3C','<',$v);
		$v = str_replace('\x3','>',$v);
		//$v = $chat->expworld($v,32);
		$v = str_replace('\\','\\\\',$v);
		$v = str_replace('"','[s1;]',$v);
		$v = str_replace("'",'[s2;]',$v);
		$v = str_replace('<','[s3;]',$v);
		$v = str_replace('>','[s4;]',$v);
		$v = str_replace('	',' ',$v);
		$v = htmlspecialchars($v,NULL,'cp1251');
		return $v;
	}
		
	if(!isset($u->info['id']) || $u->info['banned']>0)
	{
		die('-1');
	}elseif($u->info['ip']!=$_SERVER['HTTP_X_REAL_IP'])
	{
		//die('-4');
	}
	
	$r = round((int)$_POST['r']);
	if($r != 1 && $r != 2 && $r != 3) {
		$r = 1;
	}
	$html = '';
	$js = '';
		
	if(isset($_POST['see_msg'])) {
		$msg = mysql_fetch_array(mysql_query('SELECT * FROM `telegram` WHERE `id` = "'.mysql_real_escape_string($_POST['see_msg']).'" AND (`uid` = "'.$u->info['id'].'" OR `from` = "'.$u->info['id'].'") LIMIT 1'));
		if(!isset($msg['id'])) {
			$html = '<br><br><br><br><br><br><br><br><center>Сообщение не найдено.</center>';
		}elseif($msg['uid'] == $u->info['id'] && ($msg['delete'] == 2 || $msg['delete'] == 3)) {
			$html = '<br><br><br><br><br><br><br><br><center>Сообщение было удалено.</center>';
		}elseif($msg['uid'] == $u->info['id'] && ($msg['delete'] == 2 || $msg['delete'] == 3)) {
			$html = '<br><br><br><br><br><br><br><br><center>Сообщение было удалено.</center>';
		}else{
			$to = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `id` = "'.$msg['uid'].'" LIMIT 1'));
			$from = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `id` = "'.$msg['from'].'" LIMIT 1'));
			if($msg['read'] == 0 || $msg['read'] == 1) {
				if($to['id'] == $u->info['id']) {
					mysql_query('UPDATE `telegram` SET `read` = `read` + 2 WHERE `id` = "'.$msg['id'].'" LIMIT 1');
				}
			}
			$html  = '<div style="padding:10px;">';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;border-bottom:1px solid #b7ae96"><b>От</b>: &nbsp;&nbsp; '.$from['login'].'<a style="float:right" onclick="top.tgf_closeMsg()" href="javascript:void(0)">Закрыть</a></div>';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;border-bottom:1px solid #b7ae96"><b>Кому</b>: '.$to['login'].'<span style="float:right">'.date('d.m.Y H:i',$msg['time']).'</span></div>';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;border-bottom:1px solid #b7ae96"><b>Тема</b>: '.$msg['tema'].'</div>';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;"><b>Сообщение</b>:<br><div style="width:496;margin-left:1px;overflow:auto;height:185px;">'.$msg['text'].'</div>';
			$html .= '<button style="float:right" onclick="top.tgf_rz(3,1,'.$msg['id'].');">Ответить</button>';
			$html .= '</div>';
			//$html .= '<div style="margin-bottom:3px;color:red;float:left;" align="left"><small><b id="trf_snd_error">'.$error.'</b></small></div><div style="margin-bottom:3px;" align="right"><button onClick="top.tgf_send()">Отправить сообщение</button></div>';
			$html .= '</div>';
		}
	}elseif($r == 3) {
		if($u->info['noreal'] == 1) {
			$html = '<br><br><br><br><br><br><br><br><center>Отправлять сообщения по телеграфу возможно только с основного персонажа</center>';
		}elseif($u->info['level'] < 4) {
			$html = '<br><br><br><br><br><br><br><br><center>Отправлять сообщения по телеграфу возможно с 4-го уровня.</center>';
		}elseif($u->info['molch1'] > time()){
			$html = '<br><br><br><br><br><br><br><br><center>Персонажи с молчанкой не могут пользоваться телеграфом.</center>';
		}else{
			if(isset($_POST['to'])) {
				$_POST['to'] = go_text(iconv('UTF-8', 'windows-1251', $_POST['to']));
				$_POST['text'] = go_text(iconv('UTF-8', 'windows-1251', $_POST['text']));
				$_POST['tema'] = go_text(iconv('UTF-8', 'windows-1251', $_POST['tema']));
								
				if(test_line($_POST['to']) == false) {
					$error = 'Пустое поле "Кому".';
				}elseif(test_line($_POST['text']) == false) {
					$error = 'Пустое поле "Сообщение".';
				}elseif(test_line($_POST['tema']) == false) {
					$error = 'Пустое поле "Тема".';
				}else{
					$to = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['to']).'" LIMIT 1'));
					if(!isset($to['id'])) {
						$error = 'Получатель не найден в базе.';
					}elseif($to['banned'] > 0) {
						$error = 'Получатель был заблокирован.';
					}elseif($to['id'] == $u->info['id']) {
						$error = 'Нельзя отправлять самому себе.';
					}else{
						$_POST['text'] = str_replace("\n",'<br>',$_POST['text']);
						$_POST['text'] = str_replace("\r",'<br>',$_POST['text']);
						mysql_query('INSERT INTO `telegram` (`uid`,`from`,`tema`,`text`,`time`,`ip`) VALUES ("'.$to['id'].'","'.$u->info['id'].'","'.mysql_real_escape_string($_POST['tema']).'","'.mysql_real_escape_string($_POST['text']).'","'.time().'","'.mysql_real_escape_string($u->info['ip']).'")');
						unset($_POST['to'],$_POST['text'],$_POST['tema']);
						$error = 'Сообщение доставлено "'.$to['login'].'".';
					}
				}
			}
			if(isset($_POST['re']) && $_POST['re'] > 0 && !isset($_POST['to'])) {
				$msg = mysql_fetch_array(mysql_query('SELECT * FROM `telegram` WHERE `id` = "'.mysql_real_escape_string($_POST['re']).'" AND (`uid` = "'.$u->info['id'].'" OR `from` = "'.$u->info['id'].'") LIMIT 1'));
				if(isset($msg['id'])) {
					$to = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `id` = "'.$msg['uid'].'" LIMIT 1'));
					$from = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned` FROM `users` WHERE `id` = "'.$msg['from'].'" LIMIT 1'));
					if($msg['from'] == $u->info['id']) {
						$_POST['to'] = $to['login'];
					}elseif($msg['uid'] == $u->info['id']) {
						$_POST['to'] = $from['login'];
					}else{
						unset($msg);
					}
					$_POST['tema'] = 'Re:'.str_replace('Re:','',$msg['tema']);
				}
			}
			$html  = '<div style="padding:10px;">';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;border-bottom:1px solid #b7ae96">От: &nbsp;&nbsp; <b>'.$u->info['login'].'</b></div>';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;border-bottom:1px solid #b7ae96">Кому: <input value="'.$_POST['to'].'" id="tgf_to" type="text" style="width:170px"></div>';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;border-bottom:1px solid #b7ae96">Тема: <input value="'.$_POST['tema'].'" id="tgf_tema" type="text" style="width:269px"></div>';
			$html .= '<div style="margin-bottom:3px;padding-bottom:3px;border-bottom:1px solid #b7ae96">Сообщение:<br><textarea rows="9" style="width:100%;resize:none;outline:none;" id="tgf_text">'.$_POST['text'].'</textarea></div>';
			$html .= '<div style="margin-bottom:3px;color:red;float:left;" align="left"><small><b id="trf_snd_error">'.$error.'</b></small></div><div style="margin-bottom:3px;" align="right"><button onClick="top.tgf_send()">Отправить сообщение</button></div>';
			$html .= '</div>';
		}
	}elseif($r == 1 || $r == 2) {
		if($r == 1) {
			mysql_query('UPDATE `telegram` SET `open` = "1" WHERE `uid` = "'.$u->info['id'].'" AND `open` = "0" LIMIT 100');
		}
		$pg = round((int)$_POST['p']);
		if($pg < 1) {
			$pg = 1;	
		}
		$pgssee = ceil(($pg-1)*9);
		if($r == 1) {
			if(isset($_POST['del_msg'])) {
				mysql_query('UPDATE `telegram` SET `delete` = `delete` + 2 WHERE `uid` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 1) AND `id` = "'.mysql_real_escape_string($_POST['del_msg']).'" LIMIT 1');
			}
			$sp = mysql_query('SELECT * FROM `telegram` WHERE `uid` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 1) ORDER BY `id` DESC LIMIT '.mysql_real_escape_string($pgssee).',10');
		}elseif($r == 2) {
			if(isset($_POST['del_msg'])) {
				mysql_query('UPDATE `telegram` SET `delete` = `delete` + 1 WHERE `from` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 2) AND `id` = "'.mysql_real_escape_string($_POST['del_msg']).'" LIMIT 1');
			}
			$sp = mysql_query('SELECT * FROM `telegram` WHERE `from` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 2) ORDER BY `id` DESC LIMIT '.mysql_real_escape_string($pgssee).',10');
		}
		$msgs = 0;
		while($pl = mysql_fetch_array($sp)) {
			if($msgs < 9) {
				if($r == 1) {
					$from = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$pl['from'].'" LIMIT 1'));
				}
				if($r == 2) {
					$from = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				}
				if(!isset($from['id'])) {
					$from = '<b>'.$pl['from'].'</b>';
				}else{
					$from = '<b>'.$from['login'].'</b>';
				}
				//if($r == 1) {
					if($pl['read'] == 0 || $pl['read'] == 1) {
						$pl['read'] = 0;
					}else{
						$pl['read'] = 1;
					}
				//}
				//if($r == 2) {
					//if($pl['read'] == 0 || $pl['read'] == 2) {
					//	$pl['read'] = 0;
					//}else{
					//	$pl['read'] = 1;
					//}
				//}
				
				if($r == 1) { 
					$html .= '
<div id="tgfm'.$pl['id'].'" onclick="top.tgf_openMsg('.$pl['id'].')" class="tgf_msg'.$pl['read'].'">
	<small class="tgf_msgt" title="'.date('H:i',$pl['time']).'">'.date('d.m.y',$pl['time']).'</small>
	<small>От: '.$from.'</small>
	<small>Тема: <a href="javascript:void(0)">'.$pl['tema'].'</a></small>
	<img src="http://img.xcombats.com/i/clear.gif" height="13" style="float:right;vertical-align:bottom;cursor:pointer;" onclick="top.del_tgf('.$r.','.$pg.','.$pl['id'].');">
</div>';
				}
				if($r == 2) { 
					$html .= '
<div id="tgfm'.$pl['id'].'" onclick="top.tgf_openMsg('.$pl['id'].')" class="tgf_msg'.$pl['read'].'">
	<small class="tgf_msgt" title="'.date('H:i',$pl['time']).'">'.date('d.m.y',$pl['time']).'</small>
	<small>Кому: '.$from.'</small>
	<small>Тема: <a href="javascript:void(0)">'.$pl['tema'].'</a></small>
	<img src="http://img.xcombats.com/i/clear.gif" height="13" style="float:right;vertical-align:bottom;cursor:pointer;" onclick="top.del_tgf('.$r.','.$pg.','.$pl['id'].');">
</div>';
				}
			}
			$msgs++;
		}
		
		if($msgs > 9 || $pg > 1) {
			if($html != '') {
				$html .= '<div class="tfpgs">';
				if($r == 1) {
					$pgs = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `telegram` WHERE `uid` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 1)'));
				}else if($r == 2) {
					$pgs = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `telegram` WHERE `from` = "'.$u->info['id'].'" AND (`delete` = 0 OR `delete` = 2)'));
				}
				$pgs = $pgs[0];
				$pga = ceil($pgs/9);
				$i = 1;
				while($i <= $pga) {
					if($i == $pg) {
						$html .= ' <small onclick="top.tgf_rz('.$r.','.$i.')" class="tf_btn11">'.$i.'</small>';
					}else{
						$html .= ' <small onclick="top.tgf_rz('.$r.','.$i.')" class="tf_btn1">'.$i.'</small>';
					}
					$i++;
				}
				$html .= '</div>';
			}else{
				$js .= 'top.tgf_rz('.$r.','.($pg-1).');';
			}
		}
	}
	
	$js .= 'top.tgf_loading(2)';	
	if($js != '') {
		$js = '<script>'.$js.'</script>';
	}
	if($html == '') {
		if($r == 1) {
			$html = '<br><br><br><br><br><br><br><br><center>У Вас нет сообщений от других пользователей</center>';
		}elseif($r == 2) {
			$html = '<br><br><br><br><br><br><br><br><center>У Вас нет сообщений отправленных другим пользователям</center>';
		}
	}
	
	echo $html.$js;
?>