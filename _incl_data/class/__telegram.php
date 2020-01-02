<?
if(!defined('GAME'))
{
	die();
}

class telegram
{
	private static $flag_one;
	
	public static function start()
	{
		if (!isset(self::$flag_one))
		{
			$c = __CLASS__;
			self::$flag_one = new $c($server);
		}
		return self::$flag_one;
	}
	
	public function send($to,$from,$text,$time,$fromType)
	{
		mysql_query('START TRANSACTION');
		$ins = mysql_query("INSERT INTO `telegram` (`uid`,`from`,`fromType`,`text`,`time`) VALUES ('".$to."','".$from."','".$fromType."','".mysql_real_escape_string(AddSlashes(HtmlSpecialChars($text,NULL,'cp1251')))."','".time()."')");
		mysql_query('COMMIT');
		if($ins)
		{
			return mysql_insert_id();
		}
	}
		
	public function readMsg($id,$uid)
	{
		$msg = mysql_fetch_array(mysql_query('SELECT * FROM `telegram` WHERE `id`="'.$id.'" AND `uid`="'.$uid.'" LIMIT 1 FOR UPDATE'));
		if(isset($msg['id']))
		{
			mysql_query('START TRANSACTION');
			$upd = mysql_query('UPDATE `telegram` SET `read`="1" WHERE `id` = "'.$id.'" AND `uid`="'.$uid.'" LIMIT 1');
			mysql_query('COMMIT');
			if($upd && $msg['lock']==0)
			{
				echo '<script>readGood('.$id.',"http://img.combats.ru/i/misc/read.gif"); nobtext('.$id.');</script>';
			}
		}
	}
	
	public function lockMsg($id,$uid)
	{
		$msg = mysql_fetch_array(mysql_query('SELECT * FROM `telegram` WHERE `id`="'.$id.'" AND `uid`="'.$uid.'" LIMIT 1 FOR UPDATE'));
		if(isset($msg['id']))
		{
			$lock = array(0=>1,1=>0);
			mysql_query('START TRANSACTION');
			$upd = mysql_query('UPDATE `telegram` SET `read`="1",`lock`="'.$lock[$msg['lock']].'" WHERE `id` = "'.$id.'" AND `uid`="'.$uid.'" LIMIT 1');
			mysql_query('COMMIT');
			if($upd)
			{
				if($lock[$msg['lock']]==1)
				{
					echo '<script>readGood('.$id.',"http://img.combats.ru/i/lock_message.gif"); nobtext('.$id.');</script>';
				}else{
					echo '<script>readGood('.$id.',"http://img.combats.ru/i/misc/read.gif");</script>';
				}
			}
		}
	}
	
	public function deleteMsgAll($uid,$pg)
	{
		mysql_query('START TRANSACTION');
		$upd = mysql_query('UPDATE `telegram` SET `delete`="1" WHERE `uid`="'.$uid.'" AND `read`="1" AND `lock`="0" AND `delete`="0"');
		mysql_query('COMMIT');
		if($upd)
		{
			echo '<script> getPage('.$pg.');</script>';
		}
	}
	
	public function deleteMsg($id,$uid,$pg)
	{
		$msg = mysql_fetch_array(mysql_query('SELECT * FROM `telegram` WHERE `id`="'.$id.'" AND `uid`="'.$uid.'" LIMIT 1 FOR UPDATE'));
		if(isset($msg['id']))
		{
			mysql_query('START TRANSACTION');
			$upd = mysql_query('UPDATE `telegram` SET `delete`="1" WHERE `id` = "'.$id.'" AND `uid`="'.$uid.'" LIMIT 1');
			mysql_query('COMMIT');
			if($upd && $msg['delete']==0 && $msg['lock']==0)
			{
				echo '<script> getPage('.$pg.');</script>';
			}
		}
	}
		
	private function addMsgTable($id,$from,$fromType,$ttl,$time,$lock,$read,$pg)
	{
		$i1 = '<img id="msgImg'.$id.'" src="http://img.combats.ru/i/misc/unread.gif">';
		if($read==1)
		{
			$i1 = '<img id="msgImg'.$id.'" src="http://img.combats.ru/i/misc/read.gif">';
		}
		
		if($lock==1)
		{
			$i1 = '<img id="msgImg'.$id.'" src="http://img.combats.ru/i/lock_message.gif">';
		}
		
		if($fromType==1)
		{
			$from = '<a href="info/login='.$from.'" target="_blank" title="Инф. о '.$from.'">'.$from.'</a>';
		}elseif($read==0)
		{
			$from = '<b id="tablePostTxtB'.$id.'">'.$from.'</b>';
		}
						
		echo '<table id="tablePost'.$id.'" width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="30" class="underLine2"><div align="center">'.$i1.'</div></td>
				<td width="200" id="tablePostTxt'.$id.'" class="underLine2">'.$from.'</td>
				<td width="290" class="underLine2"><a onClick="openPost('.$id.'); return false;" href="#read'.$id.'" title="Прочитать сообщение">'.substr($ttl,0,30).'...</a></td>
				<td width="120" class="underLine2">'.date('d.m.y H:i',$time).'</td>
				<td width="30" class="underLine2"><div align="center"><IMG style="cursor:pointer;" onClick="lockPost('.$id.');" title="Блокировка сообщения" src="http://img.combats.ru/i/locked.gif"></div></td>
				<td width="30" class="underLine2"><div align="center"><IMG style="cursor:pointer;" onClick="deletePost('.$id.','.$pg.');" title="Удалить сообщение" src="http://img.combats.ru/i/clear.gif" width="11" height="11"></div></td>
			  </tr>
			  </table>
			  <div id="readMSG'.$id.'" style="display:none;" class="unreadMSG">'.$ttl.'</div>';
	}
	
	public function seeMsg($uid,$page,$maxPages)
	{
		$page = ceil($page);
		echo '<div id="jx" style="display:none;"></div>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="30" class="underLine"><div align="center"></div></td>
				<td width="200" class="underLine"><b>От кого</b></td>
				<td width="290" class="underLine"><b>Заголовок сообщения</b></td>
				<td width="120" class="underLine"><b>Когда</b></td>
				<td width="30" class="underLine"><div align="center"><IMG alt="Блокировка сообщений" src="http://img.combats.ru/i/lock_message.gif"></div></td>
				<td width="30" class="underLine"><div align="center"><IMG style="cursor:pointer;" onClick="deletePostAll('.$page.');" alt="Удалить все прочитанные сообщения" src="http://img.combats.ru/i/clear.gif" width="11" height="11"></div></td>
			  </tr> 
			  </table>';
		
		$i = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `telegram` WHERE `uid`="'.$uid.'" AND `delete`="0"'));		
		$pg = ceil($i[0]/$maxPages);		
		if($page<0)
		{
			$page = 0;
		}elseif($page>$pg)
		{
			$page = $pg;
		}
		
		if($i[0]>0)
		{
			$sp = mysql_query('SELECT * FROM `telegram` WHERE `uid`="'.$uid.'" AND `delete`="0" ORDER BY `id` DESC  LIMIT '.(($page-1)*$maxPages).' , '.$maxPages.'');		
			while($pl = mysql_fetch_array($sp))
			{
				$this->addMsgTable($pl['id'],$pl['from'],$pl['fromType'],$pl['text'],$pl['time'],$pl['lock'],$pl['read'],$page);
			}
		}
		
		if($i[0]==0)
		{
			echo '<div class="noMsg" align="center">Сообщений нет</div>';
		}elseif($i[0]>$maxPages){				
			$pages = '';
			$i = 1;
			while($i<=$pg)
			{
				$cls = '';
				if($page==$i)
				{
					$cls = 'style="color:#6f0000; font-size:14px;"';
				}
				$pages .= '&nbsp;<a '.$cls.' onClick="getPage('.$i.'); return false;" href="#page'.$i.'">'.$i.'</a>';
				$i++;
			}
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="30"><div align="center"></div></td>
					<td width="200">Страницы: '.$pages.'</td>
					<td width="240"></td>
					<td width="170"></td>
					<td width="30"></td>
					<td width="30"></td>
				  </tr>
				  </table>';
		}
	}
	
	public function __clone()
	{
		trigger_error('Дублирование не допускается.', E_USER_ERROR);
	}
}

?>