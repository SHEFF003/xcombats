<?php
if(!defined('GAME'))
{
	die();
}

//Forum class
class forum 
{
	public $mod = 0,$see = array(), $lst = array(),$gd = array(),$menu = '',$pages = '',$r ,$user = false,$fm = false,$error,$acs = array(0=>'Доступ закрыт',1=>'Только чтение',2=>'Разрешено добавлять ответы',3=>'Разрешено создовать топики',4=>'Разрешено добавлять ответы и создавать топики');

	
	public function paginator($t,$pagers=0){
	    if(isset($_GET['search'])) {
			$where = '( `text` LIKE "%'.mysql_real_escape_string($_GET['search']).'%" OR `title` LIKE "%'.mysql_real_escape_string($_GET['search']).'%" OR `login` LIKE "%'.mysql_real_escape_string($_GET['search']).'%" ) AND `topic` < "0" AND `delete` = "0"' ;
			$pre_url='search='.htmlspecialchars($_GET['search'],NULL,'cp1251').'&read='.$pagers.'&';
			$idpaginator=$pagers;
		}elseif($pagers!=0){
		    $where = '`topic` = "'.$pagers.'" AND `delete` = "0"' ;
			$pre_url='read='.$pagers.'&';
			$idpaginator=$pagers;
	    }elseif($t==1){
            $where = '`topic` < "0" AND `fid` = "'.$this->r.'" AND `delete` = "0"';
			$pre_url='r='.$this->r.'&';
			$idpaginator=$this->r;
		}elseif($t==2){
		    $where = '`topic` = "'.$this->see['id'].'" AND `delete` = "0"' ;
			$pre_url='read='.$this->see['id'].'&';
			$idpaginator=$this->see['id'];
		}
		    $q="SELECT count(*) FROM forum_msg WHERE ".$where;
            $res=mysql_query($q);
            $row=mysql_fetch_row($res);
            $total_rows=$row[0];
			$num_pages=ceil($total_rows/20);
			$plist='';
			for($i=1;$i<=$num_pages;$i++) {
				if( (!isset($_GET['page']) || round((int)$_GET['page']) < 1) && $i == 1 && ($t!=2 || isset($_GET['read']))) {
					$plist.='<u>'.$i."</u>";
				}elseif( ( $_GET['page']!=$i || $pagers!=0 ) ){
              		$plist.='<a href="'.$_SERVER['PHP_SELF'].'?'.$pre_url.'page='.$i.'"><b>'.$i."</b></a>";
			  	}else{
			  		$plist.='<u>'.$i.'</u>';
			  	}
            }
			if($plist == '') {
				$plist = '<u>1</u>';
			}
		return '<pages>'.$plist.'</pages>';
	}
	
	public function testAnswer($text) {
		
		$r = str_replace('[/?]','[?]',$text);
		$r = explode('[?]',$r);
		$i = 1;
		$pr_us_all = 0;
		while($i != -1) {
			if(isset($r[$i])) {
				$ra = explode('[:]',$r[$i]);
				$j = 0;
				if(isset($this->user['id'])) {
					$yg = mysql_fetch_array(mysql_query('SELECT * FROM `forum_answers` WHERE `msg_id` = "'.$this->see['id'].'" AND `q_id` = "'.$i.'" AND `uid` = "'.$this->user['id'].'" AND `delete` = "0" LIMIT 1'));
					if(!isset($yg['id'],$_GET['q_now'],$_GET['answer_now']) && $_GET['q_now'] == $i) {
						$_GET['answer_now'] = round((int)$_GET['answer_now']);
						$_GET['q_now'] = round((int)$_GET['q_now']);
						if(isset($ra[$_GET['answer_now']])) {
							mysql_query('INSERT INTO `forum_answers` (`uid`,`msg_id`,`q_id`,`answer`,`time`,`city`) VALUES 
							("'.$this->user['id'].'","'.$this->see['id'].'","'.mysql_real_escape_string($_GET['q_now']).'","'.mysql_real_escape_string($_GET['answer_now']).'",
							"'.time().'","'.$this->user['city'].'")');
							die('<meta http-equiv="refresh" content="0; URL=http://xcombats.com/forum?read='.round((int)$_GET['read']).'&page='.round((int)$_GET['page']).'">');
						}
					}
				}
				$pr_all = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `forum_answers` WHERE `msg_id` = "'.$this->see['id'].'" AND `q_id` = "'.$i.'" AND `delete` = "0" LIMIT 1'));
				$pr_all = $pr_all[0];
				$pr_us_all += $pr_all;
				$pr_lst = 0;
				$pr_asw = 0;
				while($j != -1) {
					if(isset($ra[$j]) && $ra[$j] != '') {
						if($j > 0) {
							
							$prc = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `forum_answers` WHERE `msg_id` = "'.$this->see['id'].'" AND `answer` = "'.$j.'" AND `q_id` = "'.$i.'" AND `delete` = "0" LIMIT 1'));
							$prc = 0+$prc[0];
							$pr_asw += $prc;
							if( $pr_asw == $pr_all ) {
								$prc = 100-$pr_lst;
								$pr_lst += $prc;
								if($prc > 0) {
									$prc = '<b>'.$prc.'</b>';
								}
							}else{
								$prc = floor($prc/$pr_all*100);
								if($prc > 0) {
									$pr_lst += $prc;
									$prc = '<b>'.$prc.'</b>';
								}
							}
							if(isset($this->user['id'])) {
								if(isset($yg['id'])) {
									if($yg['answer'] == $j) {
										$zm = '<tr class="answ1h"><td>&bull; <b>'.$ra[$j].'</b></td><td> &nbsp; &nbsp; - &nbsp; '.$prc.'% &nbsp; <small style="color:red">(Ваш голос)</small></td></tr>';
									}else{
										$zm = '<tr><td>&bull; '.$ra[$j].'</td><td> &nbsp; &nbsp; - &nbsp; '.$prc.'%</td></tr>';
									}
								}else{
									$zm = '<tr onclick="location.href=\'?read='.round((int)$_GET['read']).'&page='.round((int)$_GET['page']).'&q_now='.$i.'&answer_now='.$j.'\'" class="answ1" title="Голосовать за этот вариант"><td>&bull; '.$ra[$j].'</td><td> &nbsp; &nbsp; - &nbsp; '.$prc.'%</td></tr>';
								}
							}else{
								$zm = '<tr><td>&bull; '.$ra[$j].'</td><td> &nbsp; &nbsp; - &nbsp; '.$prc.'%</td></tr>';
							}
							
							$text = str_replace('[:]'.$ra[$j],$zm,$text);
						}else{
							$zm = '<h4>'.$ra[$j].'</h4><table>';
							$text = str_replace(str_replace('<br>','',$ra[$j]),$zm,$text);
						}
					}else{
						$j = -2;
					}
					$j++;
				}	
				$text = str_replace('[?]','',$text);
			}else{
				$i = -2;
			}
			$i++;
		}
		$text = str_replace('[/?]','</table><br><br>Всего проголосовало людей '.$pr_us_all.'<div class="line2"></div>',$text);	
		return $text;
	}
	
	public function startForum()
	{
		//Выделяем пользователя
		$ufr = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" AND `banned` = "0" LIMIT 1'));
		if(!isset($ufr['id']) || $ufr['banned']>0)
		{
			unset($u);
		}else{
			$ufr['sl'] = '<b>'.$ufr['login'].'</b> ['.$ufr['level'].']';
			$this->user = $ufr;
		}
		
		//Выделяем раздел который просматривает пользователь
		if(isset($_GET['read']))
		{
			//читаем сообщение
			$see = mysql_fetch_array(mysql_query('SELECT * FROM `forum_msg` WHERE `id` = "'.mysql_real_escape_string($_GET['read']).'" AND `delete` = "0" LIMIT 1'));
			if(!isset($see['id']) || ($see['fid']==65 && $this->user['admin']==0 && ($this->user['align']<=1 || $this->user['align']>=2)) || ($see['fid']==75 && $this->user['admin']==0 && ($this->user['align']<=3 || $this->user['align']>=4)))
			{
				$this->r = -2;
				$this->error = 'Топик форума не найден.';
			}else{
				$this->r = -1;
				$see['goodAdd'] = 1;
				if($see['nocom']>0)
				{
					if($this->user['align']>1 && $this->user['align']<2)
					{
						if($see['nocom']==2 || $see['nocom']==4)
						{
							$see['goodAdd'] = 0;
						}
					}
					if($this->user['align']>3 && $this->user['align']<4)
					{
						if($see['nocom']==2 || $see['nocom']==3)
						{
							$see['goodAdd'] = 0;
						}
					}
					if($this->user['admin']>0)
					{
						$see['goodAdd'] = 1;
					}
				}
				$this->see = $see;
				$this->fm = mysql_fetch_array(mysql_query('SELECT * FROM `forum_menu` WHERE `id` = "'.mysql_real_escape_string($see['fid']).'" LIMIT 1'));
			}
		}else{
			if(isset($_GET['r']))
			{
				$this->r = (int)$_GET['r'];
			}else{
				$this->r = 1;
			}
			
			$fm = mysql_fetch_array(mysql_query('SELECT * FROM `forum_menu` WHERE `id` = "'.mysql_real_escape_string($this->r).'" LIMIT 1'));
			if(!isset($fm['id']) || ($fm['id']==65 && $this->user['admin']==0 && ($this->user['align']<=1 || $this->user['align']>=2)) || ($fm['id']==75 && $this->user['admin']==0 && ($this->user['align']<=3 || $this->user['align']>=4)) )
			{
				$this->r = -2;
				$this->fm = false;
				$this->error = 'Раздел форума не найден.';
			}else{
				$this->fm = $fm;
			}
		}
		
		$this->genMenu();
		
		$this->lst = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `forum_msg` WHERE `uid` = "'.$this->user['id'].'"  AND `delete` = "0" ORDER BY `time` DESC LIMIT 1'));
		
		//Проверяем форум на доступность
		if($this->gd[$this->fm['id']]==0 && $ufr['admin']==0)
		{
			//просмотр закрыт
			$this->r = -2;
			$this->error = 'Вы не можете читать данную конференцию.';
		}elseif($this->fm['only_admin']==1 && $this->user['admin']==0)
		{
			//просмотр закрыт
			$this->r = -2;
			$this->error = 'Вы не можете читать данную конференцию.';
		}
	}
	
	public function seeTopic()
	{
		global $c;
	$fd_limit=20;
	if(isset($_GET['page'])){
	$start=$_GET['page']*$fd_limit-$fd_limit;
	}else{
	$start=0;
	//echo 'SELECT * FROM `forum_msg` WHERE `topic` < "0" AND `fid` = "'.$this->r.'" AND `delete` = "0" ORDER BY `id` DESC LIMIT '.$start.','.$fd_limit;
	}
		if($this->see['fid']==65 && $this->user['admin']==0 && ($this->user['align']<=1 || $this->user['align']>=2)){
			
		}elseif($this->see['fid']==75 && $this->user['admin']==0 && ($this->user['align']<=3 || $this->user['align']>=4)){
			
		}elseif(isset($this->see['id']))
		{
			//Выводим заголовок
			echo $this->genuser($this->see['uid'],$this->see['login'],$this->see['level'],$this->see['align'],$this->see['clan'],$this->see['invis'],$this->see['city'],$this->see['cityreg']);
			if($this->user['admin']>0 || ($this->user['align'] > 1 && $this->user['align'] < 2) || ($this->user['align'] > 3 && $this->user['align'] < 4))
			{
				echo '&nbsp; <div class="btnAdm">';
				if($this->user['admin']>0) {
					echo '<a href="?aem='.$this->see['id'].'"><img title="Редактировать" src="http://xcombats.com/forum_script/img/ic_acc3.gif" width="10" height="10"></a>';
				}
				echo '<a href="?delete_msg='.$this->see['id'].'&read='.$this->see['id'].'"><img title="Стереть запись" src="http://xcombats.com/forum_script/img/ic_acc0.gif" width="10" height="10"></a>';
				echo '<img onclick="acma('.$this->see['id'].');" title="Комментировать" src="http://xcombats.com/forum_script/img/ic_acc2.gif" width="10" height="10" style="cursor:pointer">';
				echo '</div>';
			}
			echo ' &nbsp; &nbsp; (<span class="date">'.date('d.m.Y H:i',$this->see['time']).'</span>)<br>';
			$this->see['text'] = str_replace("\n", "<br>", $this->see['text']);
			$this->see['text'] = $this->testAnswer($this->see['text']);
			$this->see['text'] = $this->parse_bb_code($this->see['text']);
			/*
			if($this->see['id'] ==1608) {
				$uslist = ''; $xu1 = 0;
				$su1 = mysql_query('SELECT `i`.`uid`,`u`.`login`,`u`.`level`,`u`.`online`,`u`.`clan`,`u`.`align` FROM `items_users` AS `i` LEFT JOIN `users` AS `u` ON `i`.`uid` = `u`.`id` WHERE `i`.`item_id` = "2852" LIMIT 25');
				$usrs = array();
				while($pu1 = mysql_fetch_array($su1)) {
					if(!isset($usrs[$pu1['uid']])) {
						$xu1++;
						$usrs[$pu1['uid']] = $xu1;
						$uslist .= $xu1.'. <b>'.$pu1['login'].'</b>['.$pu1['level'].']';
						if($pu1['online']>time()-520) {
							$uslist .= ' <small>online</small>';
						}
						$uslist .= '<br>';
					}
				}
				$this->see['text'] = str_replace("{users-list}", '<b style="color:green">Список участников</b> ['.$xu1.'/25]:<br>'.$uslist.'<br>', $this->see['text']);
				unset($uslist,$pu1,$su1,$xu1,$usrs);
			}*/
			
			$rtrn = '';
			$rtrn .= '<div';
			if($this->see['fixed'] > 0) {
				$rtrn .= ' class="fixed_topik"';
			}
						
			$rtrn .= ' style="margin-left:31px;">';
			
			if($this->see['clear']==0){
				$rtrn .= $this->see['text'];
			}else{
				if($this->user['admin'] > 0) {
					$rtrn .= '<div style="margin-left:31px;color:#9d9472;padding:10px;border:1px solid #ebdca0;">'.$this->see['text'].'</div>';
				}
				$rtrn .= '<div style="margin-left:31px;"><font color=red>Запись была удалена';
				if($this->user['del_admin'] == 0) {
					$rtrn .= ', <img src="http://'.$c['img'].'/i/align/align'.$this->see['del_align'].'.gif"><b>'.$this->see['del_login'].'</b>';
				}
				$rtrn .= '</font></div>';
			}
			
			//Записи модераторов и ангелов
			$sp1 = mysql_query('SELECT * FROM `forum_msg_mod` WHERE `msg_id` = "'.$this->see['id'].'"');
			while($pl1 = mysql_fetch_array($sp1)) {
				$rtrn .= '<div style="margin-left:31px;color:'.$pl1['color'].';">';
				if($pl1['from_admin'] == 1) {
					$pl1['login'] = '<Администрация>';
					$pl1['city'] = '';
					$pl1['align'] = 0;
					$pl1['cityreg'] = '';
					$pl1['clan'] = '';
					$pl1['level'] = '??';
					$pl1['uid'] = 0;
				}
				$rtrn .= $this->genuser($pl1['uid'],$pl1['login'],$pl1['level'],$pl1['align'],$pl1['clan'],$pl1['invis'],$pl1['city'],$pl1['cityreg']);
				$rtrn .= ' ('.date('d.m.y H:i',$pl1['time']).'): ';
				$pl1['text'] = str_replace("\n", "<br>", $pl1['text']);	
				$rtrn .= $this->link_it($pl1['text']).'</div>';
			}
			
			$rtrn .= '</div><div class="line2"></div>';
			//Выводим комментарии
			$sp = mysql_query('SELECT * FROM `forum_msg` WHERE `topic` = "'.$this->see['id'].'" AND `delete` = "0" ORDER BY `time` ASC LIMIT '.$start.','.$fd_limit );
			while($pl = mysql_fetch_array($sp))
			{
			  
				$rtrn .= $this->genuser($pl['uid'],$pl['login'],$pl['level'],$pl['align'],$pl['clan'],$pl['invis'],$pl['city'],$pl['cityreg']);
				if($this->user['admin']>0 || ($this->user['align'] > 1 && $this->user['align'] < 2) || ($this->user['align'] > 3 && $this->user['align'] < 4))
				{
					$rtrn .= '&nbsp; <div class="btnAdm">';
					if($this->user['admin']>0) {
						$rtrn .= '<a href="?aem='.$pl['id'].'"><img title="Редактировать" src="http://xcombats.com/forum_script/img/ic_acc3.gif" width="10" height="10"></a>';
					}
					$rtrn .= '<a href="?delete_msg='.$pl['id'].'&read='.$pl['topic'].'"><img title="Стереть комментарий" src="http://xcombats.com/forum_script/img/ic_acc0.gif" width="10" height="10"></a>';
					$rtrn .= '<img onclick="acma('.$pl['id'].');" title="Комментировать" src="http://xcombats.com/forum_script/img/ic_acc2.gif" width="10" height="10" style="cursor:pointer">';
					$rtrn .= '</div>';
				}
				$rtrn .= ' &nbsp; &nbsp; (<span class="date">'.date('d.m.Y H:i',$pl['time']).'</span>)<br>';
				$pl['text'] = $this->parse_bb_code($pl['text']);
				$pl['text'] = str_replace("\n", "<br>", $pl['text']);				
				if($pl['clear']==0){
					$rtrn .= '<div style="margin-left:31px;">'.$pl['text'].'</div>';
				}else{
					if($this->user['admin'] > 0) {
						$rtrn .= '<div style="margin-left:31px;color:#9d9472;padding:10px;border:1px solid #ebdca0;">'.$pl['text'].'</div>';
					}
					$rtrn .= '<div style="margin-left:31px;"><font color=red>Комментарий удален';
					if($this->user['del_admin'] == 0) {
						$rtrn .= ', <img src="http://'.$c['img'].'/i/align/align'.$pl['del_align'].'.gif"><b>'.$pl['del_login'].'</b>';
					}
					$rtrn .= '</font></div>';
				}
				
				//Записи модераторов и ангелов
				$sp1 = mysql_query('SELECT * FROM `forum_msg_mod` WHERE `msg_id` = "'.$pl['id'].'"');
				while($pl1 = mysql_fetch_array($sp1)) {
					$rtrn .= '<div style="margin-left:31px;color:'.$pl1['color'].';">';
					if($pl1['from_admin'] == 1) {
						$pl1['login'] = '<Администрация>';
						$pl1['align'] = 0;
						$pl1['city'] = '';
						$pl1['cityreg'] = '';
						$pl1['clan'] = '';
						$pl1['level'] = '??';
						$pl1['uid'] = 0;
					}
					$rtrn .= $this->genuser($pl1['uid'],$pl1['login'],$pl1['level'],$pl1['align'],$pl1['clan'],$pl1['invis'],$pl1['city'],$pl1['cityreg']);
					$rtrn .= ' ('.date('d.m.y H:i',$pl1['time']).'): ';
					$pl1['text'] = str_replace("\n", "<br>", $pl1['text']);	
					$rtrn .= $this->link_it($pl1['text']).'</div>';
				}
				
				$rtrn .= '<div class="line2"></div>';	
			}
			echo $rtrn;	
			
		}
	}
	
	public function link_it($text) {
		$text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a target=\"_blank\" href=\"$3\" >$3</a>", $text);
		$text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a target=\"_blank\" href=\"http://$3\" >$3</a>", $text);
		$text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a target=\"_blank\" href=\"mailto:$2@$3\">$2@$3</a>", $text);
		return($text);
	}

	
	public function genuser($id,$login,$level,$align,$clan,$invis,$city,$cityreg)
	{
		global $c,$code;
		$ufr = '';
		if($align>0 && $login != '<Администрация>')
		{
			$u .= '<img src="http://'.$c['img'].'/i/align/align'.$align.'.gif" />';
		}
		if($clan>0 && $login != '<Администрация>')
		{
			$clan = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.((int)$clan).'" LIMIT 1'));
			if(isset($clan['id']))
			{
				$u .= '<img src="http://'.$c['img'].'/i/clan/'.$clan['name_mini'].'.gif" />';
			}
		}
		
		if($login == '<Администрация>')
		{
			$login = 'Администрация';
		}
		
		$u .= '<b>'.$login.'</b> ['.$level.']<a href="http://'.$c['host'].'/info/'.$id.'" target="_blank" title="Инф. о '.$login.'"><img src="http://'.$c['img'].'/i/inf_capitalcity.gif"></a>';
		
		if($city!='' && $login != '<Администрация>')
		{
			$ufr = '<img title="'.$city.'" src="http://xcombats.com/forum_script/img/city/'.$city.'.gif" width="17" height="15"> &nbsp; '.$u;
		}
		return $u;
	}
	
	public function pravasee()
	{
		$prava = 1; //можно добавлять и просматривать
		if($this->see['fid']==65 && $this->user['admin']==0 && ($this->user['align']<=1 || $this->user['align']>=2))
		{
			//топик ОС
			$prava = 0;
		}elseif($this->see['fid']==75 && $this->user['admin']==0 && ($this->user['align']<=3 || $this->user['align']>=4))
		{
			//топик Армады
			$prava = 0;
		}elseif(isset($this->see['id']) && $this->see['nocom']==3 && $this->user['admin']==0 && ($this->user['align']<=3 || $this->user['align']>=4))
		{
			$prava = 0;
		}elseif(isset($this->see['id']) && $this->see['nocom']==2 && $this->user['admin']==0 && ($this->user['align']<=1 || $this->user['align']>=2))
		{
			$prava = 0;
		}elseif(isset($this->see['id']) && $this->see['nocom']==1 && $this->user['admin']==0)
		{
			$prava = 0;
		}elseif($this->user['level']<1)
		{
			$prava = 0;
		}elseif($this->lst['time']>time()-60)
		{
			$prava = 0;
		}
		return $prava;
	}
	
	public function admintopmsg($read,$id,$text,$color,$adm) {
		
		$msg = mysql_fetch_array(mysql_query('SELECT `id` FROM `forum_msg` WHERE `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(!isset($msg['id'])) {
			$this->error = 'Сообщение не найдено';
		}elseif(trim($text,' ')=='' || iconv_strlen(trim($text,' '),'cp1251')<2)
		{
			$this->error = 'Минимальная длина сообщения должна быть не менее 2-х символов';
		}elseif($this->user['molch2']>time())
		{
			$this->error = 'Вы не можете писать сообщения и создавать топики на форуме, на вас наложено заклятие молчания';
		}elseif(isset($this->see['id']) && $this->see['nocom']==3 && $this->user['admin']==0 && ($this->user['align']<=3 || $this->user['align']>=4))
		{
			$this->error = 'В этом топике запрещено оставлять ответы';
		}elseif(isset($this->see['id']) && $this->see['nocom']==2 && $this->user['admin']==0 && ($this->user['align']<=1 || $this->user['align']>=2))
		{
			$this->error = 'В этом топике запрещено оставлять ответы';
		}elseif(isset($this->see['id']) && $this->see['nocom']==1 && $this->user['admin']==0)
		{
			$this->error = 'В этом топике запрещено оставлять ответы';			
		}elseif($this->user['level'] < 4)
		{
			$this->error = 'Вы не можете писать сообщения и создавать топики на форуме, это возможно со 4-го уровня';
		}else{
			mysql_query('INSERT INTO `forum_msg_mod` (`uid`,`login`,`align`,`level`,`admin`,`clan`,`time`,`text`,`msg_id`,`delete`,`color`,`city`,`cityreg`,`from_admin`) VALUES 
			("'.$this->user['id'].'","'.$this->user['login'].'","'.$this->user['align'].'","'.$this->user['level'].'","'.$this->user['admin'].'","'.$this->user['clan'].'",
			 "'.time().'","'.mysql_real_escape_string($text).'","'.mysql_real_escape_string($msg['id']).'","0","red","'.$this->user['city'].'","'.$this->user['cityreg'].'",
			 "'.mysql_real_escape_string(round((int)$adm)).'") ');
			if(!isset($this->see['id'])) {
				header('location: ?r='.round((int)$_GET['r']).'&page='.round((int)$_GET['page']));
			}else{
				header('location: ?read='.$this->see['id'].'&page='.round((int)$_GET['page']));
			}
		}
		
	}
	
	public function parse_bb_code($text)	{
		$text = preg_replace('/\[(\/?)(b|i|u|s)\s*\]/', "<$1$2>", $text);
		
		$text = preg_replace('/\[code\]/', '<pre><code>', $text);
		$text = preg_replace('/\[\/code\]/', '</code></pre>', $text);
		
		$text = preg_replace('/\[(\/?)quote\]/', "<$1blockquote>", $text);
		$text = preg_replace('/\[(\/?)quote(\s*=\s*([\'"]?)([^\'"]+)\3\s*)?\]/', "<$1blockquote>Цитата $4:<br>", $text);
		
		//$text = preg_replace('/\[url\](?:http:\/\/)?([a-z0-9-.]+\.\w{2,4})\[\/url\]/', "<a href=\"http://$1\">$1</a>", $text);
		$text = preg_replace('/\[url=(.+?)\](.+?)\[\/url\]/', "<a target=\"_blank\" href=\"$1\">$2</a>", $text);
		$text = preg_replace('/\[url\s?=\s?([\'"]?)(?:http:\/\/)?([a-z0-9-.]+\.\w{2,4})\1\](.*?)\[\/url\]/', "<a href=\"http://$2\">$3</a>", $text);
		
		
		$text = preg_replace('/\[img\s*\]([^\]\[]+)\[\/img\]/', "<a target='_blank' href='$1'/><img class='forumfiximg' src='$1'/></a>", $text);
		$text = preg_replace('/\[img\s*=\s*([\'"]?)([^\'"\]]+)\1\]/', "<img class='forumfiximg' src='$2'/>", $text);
		
		$text = $this->close_dangling_tags($text);
		
		return $text;
	}
	
	// $s - строка, в которой необходимо закрыть теги
	// $tags - список тегов для закрытия через символ | (b|u|i)
	public function close_dangling_tags($html){
		  #put all opened tags into an array
		  preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU",$html,$result);
		  $openedtags=$result[1];
		 
		  #put all closed tags into an array
		  preg_match_all("#</([a-z]+)>#iU",$html,$result);
		  $closedtags=$result[1];
		  $len_opened = count($openedtags);
		  # all tags are closed
		  if(count($closedtags) == $len_opened){
			return $html;
		  }
		 
		  $openedtags = array_reverse($openedtags);
		  # close tags
		  for($i=0;$i < $len_opened;$i++) {
			if (!in_array($openedtags[$i],$closedtags)){
				if( $openedtags[$i] != 'br' ) {
			  		$html .= '</'.$openedtags[$i].'>';
				}
			} else {
			  unset($closedtags[array_search($openedtags[$i],$closedtags)]);
			}
		  }
		  return $html;
	}
	
	public function addnewtop($title,$text,$ico,$time,$login,$uid,$fid,$topic)
	{
		if(trim($text,' ')=='' || iconv_strlen(trim($text,' '),'cp1251')<5)
		{
			$this->error = 'Минимальная длина сообщения должна быть не менее 5-ти символов';
		}elseif($this->user['molch2']>time())
		{
			$this->error = 'Вы не можете писать сообщения и создавать топики на форуме, на вас наложено заклятие молчания';
		}elseif((trim($title,' ')=='' || iconv_strlen(trim($title,' '),'cp1251')<5) && $topic == -1)
		{
			$this->error = 'Минимальная длина заголовка должна быть не менее 5-ти символов';
		}elseif(isset($this->see['id']) && $this->see['nocom']==3 && $this->user['admin']==0 && ($this->user['align']<=3 || $this->user['align']>=4))
		{
			$this->error = 'В этом топике запрещено оставлять ответы';
		}elseif(isset($this->see['id']) && $this->see['nocom']==2 && $this->user['admin']==0 && ($this->user['align']<=1 || $this->user['align']>=2))
		{
			$this->error = 'В этом топике запрещено оставлять ответы';
		}elseif(isset($this->see['id']) && $this->see['nocom']==1 && $this->user['admin']==0)
		{
			$this->error = 'В этом топике запрещено оставлять ответы';
		}elseif($this->user['level'] < 4)
		{
			$this->error = 'Общение на форуме доступно с 4-го уровня';
		}elseif($this->gd[$fid]>0 && $this->lst['time']<time()-60)
		{
			$ico= (int)$ico;
			if($ico<1 || $ico>14)
			{
				$ico = 13;
			}
			$tl = array();
			if(isset($_POST['adminname']) && $this->user['admin']>0)
			{
				$tl['login'] = '<Администрация>';
				$tl['level'] = '??';
				$tl['align'] = '0';
				$tl['clan']  = '0';
				$tl['cityreg'] = 'newvillage';
				$tl['city'] = 'questcity';
				$tl['id'] = '0';
			}else{
				$tl['login'] = $this->user['login'];
				$tl['level'] = $this->user['level'];
				$tl['align'] = $this->user['align'];
				$tl['clan']  = $this->user['clan'];
				$tl['cityreg'] = $this->user['cityreg'];
				$tl['city'] = $this->user['city'];
				$tl['id'] = $uid;
			}
			$lst = mysql_fetch_array(mysql_query('SELECT * FROM `forum_msg` WHERE `topic` = "'.mysql_real_escape_string($topic).'" OR (`topic` = "-1" AND `id` = "'.mysql_real_escape_string($topic).'") ORDER BY `time` DESC LIMIT 1'));
			$ins = mysql_query('INSERT INTO `forum_msg` (`cityreg`,`city`,`align`,`clan`,`level`,`login`,`fid`,`title`,`topic`,`ico`,`text`,`time`,`ip`,`uid`) VALUES ("'.mysql_real_escape_string($tl['cityreg']).'","'.mysql_real_escape_string($tl['city']).'","'.mysql_real_escape_string($tl['align']).'","'.mysql_real_escape_string($tl['clan']).'","'.mysql_real_escape_string($tl['level']).'","'.mysql_real_escape_string($tl['login']).'","'.mysql_real_escape_string($fid).'","'.mysql_real_escape_string(htmlspecialchars($title, NULL , 'cp1251')).'","'.mysql_real_escape_string($topic).'","'.mysql_real_escape_string($ico).'","'.mysql_real_escape_string(htmlspecialchars($text, NULL , 'cp1251')).'","'.mysql_real_escape_string($time).'","'.$_SERVER['HTTP_X_REAL_IP'].'","'.$tl['id'].'")');
			if(!$ins)
			{
				$this->error = 'Ошибка создания топика';
				return false;
			}else{
				if($topic != -1) {
					//Если автор предыдущего сообщения не текущий юзер - отправляем в чат сообщение о новом ответе
					if($lst['uid']+1!=$this->user['id']) {
						$fnt = '<b>'.date('d.m.Y H:i').'</b> На форуме опубликован новый ответ в обсуждении, в котором вы принимали участие. <a href=http://xcombats.com/forum?read='.$topic.' target=_blank \>Читать далее</a>';
						$ins = ''; $ll = array();
						$sp = mysql_query('SELECT `u`.`city`,`u`.`id`,`u`.`login`,`f`.`uid`,`f`.`login` FROM `forum_msg` AS `f` LEFT JOIN `users` AS `u` ON `f`.`uid` = `u`.`id` WHERE `f`.`delete` = "0" AND (`f`.`topic` = "'.mysql_real_escape_string($topic).'" OR (`f`.`topic` = "-1" AND `f`.`id` = "'.mysql_real_escape_string($topic).'")) LIMIT 1');
						while($pl = mysql_fetch_array($sp)) {
							if(!isset($ll[$pl['uid']]) && $pl['uid']!=$this->user['id']) {
								$ins .= '("'.$pl['city'].'","1","'.$pl['login'].'","6","-1","'.$fnt.'"),';
								$ll[$pl['uid']] = true;
							}
						}
						unset($ll);
						$ins = trim($ins,',');
						if($ins!='') {
							mysql_query('INSERT INTO `chat` (`city`,`new`,`to`,`type`,`time`,`text`) VALUES '.$ins.'');
						}
					}
				}
				$fid = mysql_insert_id();
				$this->lst['time'] = time();
				if(isset($this->see['id'])) {
					header('location: ?read='.$this->see['id'].'&page='.round((int)$_GET['page']));
				}else{
					header('location: ?r='.round((int)$_GET['r']).'&page='.round((int)$_GET['page']));
				}
				return $fid;
			}
		}elseif($this->lst['time']>time()-60)
		{
			$this->error = 'Вы не можете оставлять ответы и создавать топики так быстро';
		}else{
			$this->error = 'Вы не можете оставлять ответы и создавать топики в этой конференции';
		}
	}
	
	public function forumData()
	{
		$fd_limit = 20;
		if(isset($_GET['page'])){
			$start=$_GET['page']*$fd_limit-$fd_limit;
		}else{
			$start=0;
		//echo 'SELECT * FROM `forum_msg` WHERE `topic` < "0" AND `fid` = "'.$this->r.'" AND `delete` = "0" ORDER BY `id` DESC LIMIT '.$start.','.$fd_limit;
		}
		global $code,$c,$filter;
		//отображаем топики данной конференции
		$re = '';
		if(isset($_GET['search'])) {
			$sp = mysql_query('SELECT * FROM `forum_msg` WHERE ( `text` LIKE "%'.mysql_real_escape_string($_GET['search']).'%" OR `title` LIKE "%'.mysql_real_escape_string($_GET['search']).'%" OR `login` LIKE "%'.mysql_real_escape_string($_GET['search']).'%" ) AND `topic` < "0" AND `delete` = "0" ORDER BY `id` DESC LIMIT '.$start.','.$fd_limit );
		}else{
			$sp = mysql_query('SELECT * FROM `forum_msg` WHERE `topic` < "0" AND `fid` = "'.$this->r.'" AND `delete` = "0" ORDER BY `fixed` DESC,`id` DESC LIMIT '.$start.','.$fd_limit );
		}
		while($pl = mysql_fetch_array($sp))
		{
			$lstDT = $pl['time'];
			$row = mysql_num_rows(mysql_query('SELECT `id` FROM `forum_msg` WHERE `topic` = "'.$pl['id'].'" AND `delete` = "0"'));
			$plist='<small>'.$this->paginator(2,$pl['id']).'</small>';
			if($plist!=''){
			$plist='<img src="http://xcombats.com/forum_script/img/p.gif" width="10" height="12"> '.$plist.'';}
			
			$re .= '<div';
			$fxd = '';
			if($pl['fixed'] > 0) {
				$fxd = '<img src="http://xcombats.com/forum_script/img/fixed.gif"> ';
				$re .= ' class="fixed_topik"';
			}
			$re .= ' style="margin-top:10px;">';
			$re .= '<div>'.$fxd.'<img style="border:0px;" src="http://xcombats.com/forum_script/img/icon'.$pl['ico'].'.gif"> <a href="?read='.$pl['id'].'&rnd='.$code.'"><b>'.$pl['title'].'</b></a> '.$plist.' &nbsp; '.$this->genuser($pl['uid'],$pl['login'],$pl['level'],$pl['align'],$pl['clan'],$pl['invis'],$pl['city'],$pl['cityreg']).'</div>';
			$re .= '<div style="margin-left:21px;"><small class="date">'.date('d.m.Y H:i',$pl['time']).'</small> &raquo; <small style="color:#606060;">';
			if($pl['fid'] == 24) {
				$re .= $filter->str_count(str_replace('[?]','Вопрос: ',str_replace('[/?]','',str_replace('[:]',', ',$pl['text']))),250);
			}else{
				$re .= $filter->str_count(str_replace('[?]','Вопрос: ',str_replace('[/?]','',str_replace('[:]',', ',$pl['text']))),250);
			}
			$re .= '</small></div>';
			$re .= '<div style="margin-left:21px;"><small>Ответов: <b>'.$row.'</b> ... ';
			
			$pku = mysql_query('SELECT `login`,`invis`,`time` FROM `forum_msg` WHERE `topic` = "'.$pl['id'].'" AND `delete` = 0 ORDER BY `id` DESC LIMIT 10');		
			$pkusr = '';
			while($plku = mysql_fetch_array($pku)) {
				$pkusr = $plku['login'].', '.$pkusr;
				$lstDT = $plku['time'];
			}
			$pkusr = rtrim($pkusr,', ');
			$re .= $pkusr;
			unset($pkusr);			
			
			$re .= ' &nbsp;  &nbsp; ('.date('d.m.Y H:i',$lstDT).')</small></div>';
			$re .= '</div>';
			$re .="\n";
		}
		echo $re;
	}
	
	public function history($id,$uid,$act,$text)
	{
		 if($type==1)
		 {
		 	
		 }
	}
	
	public function actionSee($id)
	{
		if($id==1)
		{
			//удалить топик \ коммент
			$up = mysql_query('UPDATE `forum_msg` SET `delete` = "'.time().'",`del_login`="'.$this->user['login'].'" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
			$this->see['delete'] = time();
		}elseif($id==2)
		{
			//запретить комментировать
			mysql_query('UPDATE `forum_msg` SET `nocom` = "1" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
			$this->see['nocom'] = 1;
		}elseif($id==3)
		{
			//разрешить комментировать только Ангелам
			mysql_query('UPDATE `forum_msg` SET `nocom` = "2" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
			$this->see['nocom'] = 2;
		}elseif($id==4)
		{
			//разрешить комментировать только паладинам
			mysql_query('UPDATE `forum_msg` SET `nocom` = "3" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
			$this->see['nocom'] = 3;
		}elseif($id==5)
		{
			//разрешить комментировать только тарманам
			mysql_query('UPDATE `forum_msg` SET `nocom` = "4" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
			$this->see['nocom'] = 4;
		}elseif($id==6)
		{
			//разрешить комментировать всем
			mysql_query('UPDATE `forum_msg` SET `nocom` = "0" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
			$this->see['nocom'] = 0;
		}elseif($id==9)
		{
			//разрешить комментировать всем
			$rzn = mysql_fetch_array(mysql_query('SELECT * FROM `forum_menu` WHERE `id` = "'.mysql_real_escape_string($_GET['trm']).'" LIMIT 1'));
			if(isset($rzn['id'])) {
				mysql_query('UPDATE `forum_msg` SET `fid` = "'.mysql_real_escape_string($rzn['id']).'" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
			}
		}elseif($id==7) {
			//фиксация топика или сообщения
			if( $this->see['fixed'] == 0 ) {
				$this->see['fixed'] = time();
			}else{
				$this->see['fixed'] = 0;
			}
			mysql_query('UPDATE `forum_msg` SET `fixed` = "'.$this->see['fixed'].'" WHERE `id` = "'.$this->see['id'].'" LIMIT 1');
		}elseif($id==8) {
			//удаление сообщения
			$tpdms = mysql_fetch_array(mysql_query('SELECT `id`,`clear`,`delete` FROM `forum_msg` WHERE `id` = "'.mysql_real_escape_string(round((int)$_GET['delete_msg'])).'" LIMIT 1' ));
			if(isset($tpdms['id'])) {
			//	if(isset($this->see['id'])) {
					if( $tpdms['clear'] == 0 ) {
						$tpdms['clear'] = time();
					}else{
						$tpdms['delete'] = $this->user['id'];
					}
					mysql_query('UPDATE `forum_msg` SET `clear` = "'.$tpdms['clear'].'",`delete` = "'.$tpdms['delete'].'",`del_login` = "'.$this->user['login'].'",`del_align` = "'.$this->user['align'].'",`del_clan` = "'.$this->user['clan'].'",`del_admin` = "'.$this->user['admin'].'" WHERE `id` = "'.$tpdms['id'].'" LIMIT 1');
			//	}
			}
		}
		if(!isset($this->see['id'])) {
			header('location: ?r='.round((int)$_GET['r']).'&page='.round((int)$_GET['page']));
		}else{
			header('location: ?read='.$this->see['id'].'&page='.round((int)$_GET['page']));
		}
	}
	
	public function genRz($pl)
	{
		global $code;
		$rt = '';
		if(isset($pl['id']))
		{
			//0 - доступ закрыт, нелья даже читать, 1 - только чтение, 2 - разрешено добавлять ответы, 3 - разрешено создавать топики, 4 - разрешено создавать топики и добавлять ответы
			$ico = 4;
			if($pl['level']>$this->user['level'])
			{
				$ico = 1;
			}
			//проверяем уровень доступа		
			$this->gd[$pl['id']] = $ico;	
			$ico = '<img width="10" height="10" title="'.$this->acs[$ico].'" src="http://xcombats.com/forum_script/img/ic_acc'.$ico.'.gif">';
			$rt = ''.$ico.' <a href="?r='.$pl['id'].'&rnd='.$code.'"><b>'.$pl['name'].'</b></a>';
		}else{
			$rt = 'Раздел не найден';
		}
		return $rt;
	}
	
	public function genMenu()
	{
		$m = '';
		
		
		$sp = mysql_query('SELECT * FROM `forum_menu` WHERE `parent` = "0" ORDER BY `pos` DESC');
		while($pl = mysql_fetch_array($sp))
		{
			if( $pl['id'] == 65 && $this->user['admin'] == 0 && ($this->user['align'] <= 1 ||$this->user['align'] >= 2) ) {
				
			}elseif( $pl['id'] == 75 && $this->user['admin'] == 0 && ($this->user['align'] <= 3 ||$this->user['align'] >= 4) ) {
				
			}elseif($pl['only_admin']==0 || $this->user['admin']>0)
			{
				$m .= $this->genRz($pl).'<br>';
				if($this->r==$pl['id'] || $this->fm['parent']==$pl['id'])
				{
					$sp2 = mysql_query('SELECT * FROM `forum_menu` WHERE `parent` = "'.$pl['id'].'" AND `parent2`="0"');
					while($pl2 = mysql_fetch_array($sp2))
					{
						$m .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->genRz($pl2).'<br>';
						if($this->r==$pl2['id'] || $this->fm['parent2']==$pl2['id'])
						{
							$sp3 = mysql_query('SELECT * FROM `forum_menu` WHERE `parent2` = "'.$pl2['id'].'"');
							while($pl3 = mysql_fetch_array($sp3))
							{
								$m .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->genRz($pl3).'<br>';
							}
						}
					}
				}
			}
		}		
		
		$this->menu = $m;
	}	
}

$f = new forum;
?>