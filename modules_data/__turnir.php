<?
if(!defined('GAME'))
{
	die();
}

class turnir {
	
	public $info,$user,$name = array(
						0 => '������ ����� �����',
						1 => '������ ��� �� ����',
						2 => '������ �����'				
					);
	
	public function start() {
		global $c,$u;
		$this->info = mysql_fetch_array(mysql_query('SELECT * FROM `turnirs` WHERE `id` = "'.$u->info['inTurnir'].'" LIMIT 1'));
		$this->user = mysql_fetch_array(mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$u->info['inTurnir'].'" AND `bot` = "'.$u->info['id'].'" LIMIT 1'));
	}
	
	public function startTurnir() {	
		global $c,$u;	
		$row = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `win` = "0" AND `lose` = "0" AND `nich` = "0"'));
		if($row[0] > 0 && $this->info['status'] != 3) {
			//�������� ��������
			mysql_query('INSERT INTO `battle` (`city`,`time_start`,`timeout`,`type`,`turnir`) VALUES ("'.$u->info['city'].'","'.time().'","60","1","'.$this->info['id'].'")');
			$uri = mysql_insert_id();
			//���������� ���������� � ��������
			mysql_query('UPDATE `users` SET `battle` = "'.$uri.'" WHERE `inUser` = "0" AND `inTurnir` = "'.$this->info['id'].'"');
			//���������� ���������� ������� ��� ������
			mysql_query('UPDATE `turnirs` SET `status` = "3" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			die('��������� � ������ "��������"...');
		}else{
			if($this->info['status'] == 3) {
				$this->finishTurnir();
			}
		}
	}
	
	public function finishTurnir() {
		global $c,$u;
		$this->info = mysql_fetch_array(mysql_query('SELECT * FROM `turnirs` WHERE `id` = "'.$u->info['inTurnir'].'" LIMIT 1'));
		//mysql_query('UPDATE `users` SET `inUser` = 0, `inTurnir` = 0 WHERE `inTurnir` = '.$this->info['id'].' AND `inUser` > 0 LIMIT '.$this->info['users_in']);
		if($this->info['status'] == 3) {
			$win = '';
			
			$sp = mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'" ORDER BY `points` DESC LIMIT '.$this->info['users_in']);
			while($pl = mysql_fetch_array($sp)) {
				$inf = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				$bot = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['bot'].'" LIMIT 1'));
				if(isset($inf['id'],$bot['id'])) {
					//������ ����� � �.�	
					mysql_query('DELETE FROM `users` WHERE `id` = "'.$bot['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `stats` WHERE `id` = "'.$bot['id'].'" LIMIT 1');	
					mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$bot['id'].'" LIMIT 1000');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$bot['id'].'" LIMIT 1000');											
				}
				
				if($bot['win'] > 0 && $bot['lose'] < 1) {
					$win .= '<b>'.$inf['login'].'</b>, ';
				}
			}
			mysql_query('UPDATE `users` SET `inUser` = "0",`inTurnir` = "0" WHERE `inTurnir` = "'.$this->info['id'].'" LIMIT '.$this->info['users_in']);
			mysql_query('UPDATE `turnirs` SET `users_in` = 0,`status` = 0,`winner` = -1,`step` = 0,`time` = "'.(time()+$this->info['time2']).'",`count` = `count` + 1 WHERE `id` = '.$this->info['id'].' LIMIT 1');
			
			if($win != '') {
				$win = rtrim($win,', ');
				$win = '���������� �������: '.$win.'. ��������� ������ �������� ����� '.$u->timeOut($this->info['time2']).' ('.date('d.m.Y H:i',(time()+$this->info['time2'])).').';
			}else{
				$win = '���������� ������� �����������. ��������� ������ �������� ����� '.$u->timeOut($this->info['time2']).' ('.date('d.m.Y H:i',(time()+$this->info['time2'])).').';
			}
			$r = '<font color=black><b>������ &laquo;'.$this->name[$this->info['type']].' ['.$this->info['level'].'] �'.$this->info['count'].'&raquo; ����������!</b></font> '.$win;				
			mysql_query('DELETE FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'" LIMIT '.$this->info['users_in']);
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','','','','<small>".$r."</small>','".time()."','6','0')");
		}
	}
	
	public function locationSee() {
		global $c,$u;
		
		$r = '';
		
		$tm1 = '';
		
		$tm2 = '';
		
		if($this->info['step'] != 3 && $this->info['step'] != 0) {
			//��������� ���������
			if(isset($_GET['gocomplect']) && $this->user['points'] < 2) {
				
				$aso = array();
				$noitm = array(
					869 => 1
				);
				$sp = mysql_query('SELECT `id`,`price1`,`inslot`,`price2` FROM `items_main` WHERE `inslot` > 0 AND `inslot` <= 26 AND `inslot` != 25 AND `inslot` != 24 AND `inslot` != 23 AND `inslot` != 16 AND `inslot` != 17 AND `inslot` != 2 AND `price2` = 0');
				while($pl = mysql_fetch_array($sp)) {
					if(!isset($noitm[$pl['id']])) {
						$aso[$pl['inslot']][count($aso[$pl['inslot']])] = $pl;
					}
				}
				
				$i = 0; $com = array();
				while($i <= 17) {
					if($i == 16) {
						//����� ����
						
					}elseif($i == 17) {
						//������ ����
						
					}else{
						//��������������
						$com[$i] = $aso[$i][rand(0,count($aso[$i]))];
					}
					if($com[$i]['id'] > 0) {
						$u->addItem($com[$i]['id'],$u->info['id']);
						$this->user['points'] += $com[$i]['price1'];
					}
					echo ','.$com[$i];
					$i++;
				}
				mysql_query('UPDATE `users_turnirs` SET `points` = "'.$this->user['points'].'" WHERE `bot` = "'.$u->info['id'].'" LIMIT 1');
				$this->info['step'] == 0;
			}
		}
		
		if($this->info['step'] == 3) {
			$this->finishTurnir();
		}elseif($this->info['step'] == 0) {
			//������������ �������
			$po = array(0,0);
			$sp = mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'" ORDER BY `points` DESC LIMIT '.$this->info['users_in']);
			$tmr = rand(1,2);
			if($tmr == 1) {
				$tmr = array(2,1);
			}else{
				$tmr = array(1,2);
			}
			while($pl = mysql_fetch_array($sp)) {
				$inf = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
				$bot = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['bot'].'" LIMIT 1'));
				if(isset($inf['id'],$bot['id'])) {
					if($po[1] == $po[2]) {
						$tm = rand(1,2);
					}elseif($po[1] > $po[2]) {
						$tm = 2;
					}else{
						$tm = 1;
					}
					//$tm = $tmr[$tm];
					$bot['team'] = $tm;
					$po[$bot['team']] += $pl['points'];
					mysql_query('UPDATE `stats` SET `team` = "'.$bot['team'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
					mysql_query('UPDATE `users_turnirs` SET `team` = "'.$bot['team'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
			}
			mysql_query('UPDATE `turnirs` SET `step` = "1",`time` = "'.(time() + $this->info['time3']).'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
		}
	
		$sp = mysql_query('SELECT * FROM `users_turnirs` WHERE `turnir` = "'.$this->info['id'].'" LIMIT '.$this->info['users_in']);
		$po = array(0,0);
		while($pl = mysql_fetch_array($sp)) {
			$inf = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
			$bot = mysql_fetch_array(mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `u`.`id` = `st`.`id` WHERE `u`.`id` = "'.$pl['bot'].'" LIMIT 1'));
			if(isset($inf['id'],$bot['id'])) {
				$po[$bot['team']] += $pl['points'];
				${'tm'.$bot['team']} .= '<b>'.$bot['login'].'</b> ['.$bot['level'].'], ';
			}
		}
		$r .= '<style>/* ����� ������ */
.CSSteam0	{ font-weight: bold; cursor:pointer; }
.CSSteam1	{ font-weight: bold; color: #6666CC; cursor:pointer; }
.CSSteam2	{ font-weight: bold; color: #B06A00; cursor:pointer; }
.CSSteam3 	{ font-weight: bold; color: #269088; cursor:pointer; }
.CSSteam4 	{ font-weight: bold; color: #A0AF20; cursor:pointer; }
.CSSteam5 	{ font-weight: bold; color: #0F79D3; cursor:pointer; }
.CSSteam6 	{ font-weight: bold; color: #D85E23; cursor:pointer; }
.CSSteam7 	{ font-weight: bold; color: #5C832F; cursor:pointer; }
.CSSteam8 	{ font-weight: bold; color: #842B61; cursor:pointer; }
.CSSteam9 	{ font-weight: bold; color: navy; cursor:pointer; }
.CSSvs 		{ font-weight: bold; }</style>';
		$r 	.= '<h3>&laquo;'.$this->name[$this->info['type']].'&raquo;</h3><br>������ ������� ����� '.$u->timeOut($this->info['time'] - time()).'! ';
		
		if($this->user['points'] < 3) {
			//��� �� �������� ��������������
			if($this->user['points'] < 2) {
				$r .= '<INPUT class=\'btn_grey\' onClick="location=\'main.php?gocomplect=1\';" TYPE=button name=tmp value="�������� ��������������">';
			}else{
				$r .= ' <INPUT class=\'btn_grey\' onClick="location=\'main.php\';" TYPE=button name=tmp value="� �����';
				if($u->info['sex'] == 1) {
					$r .= '�';
				}
				$r .= '!">';
			}
		}else{
			$r .= '<small><b>�� ���������� � �������!</b></small>';
		}
		$r	.= '<div style="float:right"><INPUT onClick="location=\'main.php\';" TYPE=button name=tmp value="��������"></div><hr>';
		$r .= '<b class="CSSteam1">������� �1</b>: '.rtrim($tm1,', ');		
		$r .= '<br><b class="CSSteam2">������� �2</b>: '.rtrim($tm2,', ');
		
		if( ($this->info['time'] - time() < 0) && $this->info['step'] == 1) {
			//�������� ������
			$this->startTurnir();
		}
		
		echo $r;
	}
	
}
$tur = new turnir;
$tur->start();
?>