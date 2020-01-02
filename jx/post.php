<?php
header('Content-Type: text/html; charset=windows-1251');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
{
	define('GAME',true);
	include_once('../_incl_data/__config.php');
	include_once('../_incl_data/class/__db_connect.php');
	include('../_incl_data/class/__telegram.php');
	
	$u = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `login`="'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass`="'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
	if(!isset($u['id']) || ($u['joinIP']==1 && $u['ip']!=$_SERVER['HTTP_X_REAL_IP']))
	{
		die('<script>top.location = \'http://'.$c['host'].'/\';</script>');
	}else{
		if(isset($_POST['act']))
		{
			$post = telegram::start();
			if($_POST['act']=='read')
			{
				$post->readMsg(mysql_real_escape_string($_POST['msg']),$u['id']);
			}elseif($_POST['act']=='lock')
			{
				$post->lockMsg(mysql_real_escape_string($_POST['msg']),$u['id']);
			}elseif($_POST['act']=='delete')
			{
				$post->deleteMsg(mysql_real_escape_string($_POST['msg']),$u['id'],$_POST['pageGo']);
			}elseif($_POST['act']=='deleteAll')
			{
				$post->deleteMsgAll($u['id'],$_POST['pageGo']);
			}elseif($_POST['act']=='page')
			{
				$post->seeMsg($u['id'],$_POST['msg'],5);
			}
		}
	}
}
?>