<?
if(!defined('GAME')) { die(); }

class noob {
	
	//����������
	public static $info = array(); //������� �����
	
	/*
	�������� ��������
	*/
	public static function test1($var) {
		global $u;
		$r = false;
		$var = explode('=',$var);
		if( $var[0] == 'slot' ) {
			//���� �� � ���� �������
			$test = mysql_fetch_array(mysql_query('SELECT `id`,`item_id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `inOdet` = "'.mysql_real_escape_string($var[1]).'" AND `delete` = 0 LIMIT 1'));
			if(isset($test['id'])) {
				if($var[2] == 0 || $var[2] == $test['item_id'] ) {
					$r = true;
				}
			}
		}
		return $r;
	}
	
	/* 
	�������� ������
	*/
	public static function testAll() {
		global $u;
		//��� ��������
			$go = 1;
			//����
				$ex = explode('>',self::$info['module']);
				if( $ex[0] == 'exp' ) {
					if( $ex[1] < $u->info['exp'] ) {
						self::$info['module'] = '';
					}
				}
				$ex = explode('<',self::$info['module']);
				if( $ex[0] == 'exp' ) {
					if( $ex[1] < $u->info['exp'] ) {
						mysql_query('UPDATE `users` SET `fnq` = "'.self::$info['next'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						self::$info['module'] = '';
					}
				}
				//
				$go = 1;
				//���������
				$ex = explode('=',self::$info['module']);
				if( $ex[0] == 'slots' ) {
					//������� �������� � �����
					$ex2 = explode(',',$ex[1]);
					$i = 0; $gd = 1;
					while( $i < count($ex2) ) {
						if( !isset($u->stats['wp' . $ex2[$i] . 'id']) ) {
							$gd = 0;
						}
						$i++;
					}
					if( $gd == 1 ) {
						self::$info['module'] = '';
					}
				}elseif( $ex[0] == 'exp' ) {
					$gd = 1;
					if( $ex[1] == '>' ) {
						if( $ex[2] > $u->info['exp'] ) {
							$go = 0;
							$go = 0;
						}
					}elseif( $ex[1] == '<' ) {
						if( $ex[2] < $u->info['exp'] ) {
							$gd = 0;
							$go = 0;
						}
					}
					if( $gd == 1 ) {
						self::$info['module'] = '';
					}
				}elseif( $ex[0] == 'fights' ) {
					$gd = 1;
					if( $ex[1] == '<' ) {
						if( $ex[2] < $u->info['win'] + $u->info['lose'] + $u->info['nich'] ) {
							$gd = 0;
						}
					}elseif( $ex[1] == '>' ) {
						if( $ex[2] > $u->info['win'] + $u->info['lose'] + $u->info['nich'] ) {
							$gd = 0;
						}
					}
					if( $gd == 1 ) {
						self::$info['module'] = '';
					}
				}elseif( $ex[0] == 'room' ) {
					$gd = 1;
					if( $u->info['room'] != $ex[1] ) {
						$gd = 0;
					}
					if( $gd == 1 ) {
						self::$info['module'] = '';
					}
				}
				//
				//����������
				$e1 = explode('|',self::$info['tr']);
				$i = 0;
				while( $i < count($e1) ) {
					$ex = explode('=',$e1[$i]);
					//
					if( $ex[0] == 'exp' ) {
						if( $ex[1] == '<' ) {
							if( $ex[2] < $u->info['exp'] ) {
								$go = 0;
							}
						}elseif( $ex[1] == '>' ) {
							if( $ex[2] > $u->info['exp'] ) {
								$go = 0;
							}
						}
					}elseif( $ex[0] == 'fights' ) {
						if( $ex[1] == '<' ) {
							if( $ex[2] < $u->info['win'] + $u->info['lose'] + $u->info['nich'] ) {
								$go = 0;
							}
						}elseif( $ex[1] == '>' ) {
							if( $ex[2] > $u->info['win'] + $u->info['lose'] + $u->info['nich'] ) {
								$go = 0;
							}
						}
					}
					//
					$i++;
				}
			//
		//
		return $go;
	}
	
	
	/*
	������ �������� ��������
	*/
	public static function start() {
		global $u;	
		if( $u->info['fnq'] == 0 ) {
			$u->info['fnq'] = 1;
			mysql_query('UPDATE `users` SET `fnq` = "'.$u->info['fnq'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}elseif( $u->info['id'] != 1 ) {
			$u->info['fnq'] = -1;
			mysql_query('UPDATE `users` SET `fnq` = "'.$u->info['fnq'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}
		
		if( $u->info['id'] != 1 ) {
			$u->info['fnq'] = -1;
		}
				
		if( $u->info['fnq'] == 0 ) {
			//������ ��������
			/*$humor = array(
				0 => array(
					':maniac: ������ �� ����� ;)',':beggar: ����� �������������� - �����!',':pal: �������� �������!',
					':vamp: �������� ������!',':susel: ���� �� ������������ �
					':friday: �� ����� ����� �� ����� ������ ������������!',':doc: ������: �������! ��, ��! ��! ���� ���� ������� - � ������� ���� ������� �������!'
				),
				1 => array(
					':maniac: �������! ������� �� ���� ;)',':nail: ��� ������ �����, �� ���������� ��� ����� ;)',':pal: �������� �������!',
					':vamp: �������� ������!',':rev: ���� �� �������� ������ - ��� �������!',':hug: � ����� �� �������� ���� ��������!',
					':angel2: ����� ����� � �����...'
				)
			);
			$humor = $humor[$u->info['sex']];
			*/
			$u->info['fnq'] = 1;
			mysql_query('UPDATE `users` SET `fnq` = "'.$u->info['fnq'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			//���������� ��������� � ��� � �������
			//$u->send('','','','','','� ����� ���� �������� ����� ����� &quot;<b>' . $u->info['login'] . '</b>&quot;! '.$humor[rand(0,count($humor)-1)].'',time(),6,0,0,0,1,0);
		}else{
			self::$info = mysql_fetch_array(mysql_query('SELECT * FROM `an_quest` WHERE `id` = "'.$u->info['fnq'].'" LIMIT 1'));
			if(isset(self::$info['id'])) {
				//��������� �����
				$go = self::testAll();
				if( $go == 0 ) {
					//���������� �� ��������
					self::$info = array();
				}elseif( $u->info['marker'] == self::$info['module'] || self::$info['module'] == '' || (self::$info['module'] == 'next' && isset($_GET['nextfnq'])) || self::test1(self::$info['module']) == true ) {
					//����� ��������, �������� �������!
					if( self::$info['room'] == '' || self::$info['room'] == $u->room['name'] ) {
						//������ �������
						$nag = explode('|',self::$info['win']);
						if( $nag[3] != '0' ) {
							//������ �������
							$ng = explode(',',$nag[3]);
							$i = 0;
							while( $i < count($ng) ) {
								$ngi = explode(',',$ng[$i]);
								$j = 0;
								while( $j < count($ngi) ) {
									$ngj = explode('=',$ngi[$j]);
									// id = srok = data
									$ngjs = '';
									if( $ngj[1] > 0 ) {
										$ngjs .= '|srok='.$ngj[1].'';
									}
									$u->addItem($ngj[0], $u->info['id'], $ngjs);
									$j++;
							}
								$i++;
							}
						}
						if( $nag[4] != '0' ) {
							//������ ����
							$u->info['money4'] += $nag[4];
							mysql_query('UPDATE `users` SET `money4` = "'.$u->info['money4'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
						
						$u->info['fnq'] = self::$info['next'];
						mysql_query('UPDATE `users` SET `fnq` = "'.$u->info['fnq'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						self::$info = mysql_fetch_array(mysql_query('SELECT * FROM `an_quest` WHERE `id` = "'.$u->info['fnq'].'" LIMIT 1'));
						$go = self::testAll();
						if( $go == 0 || self::$info['act'] == 'deadline' ) {
							//���������� �� ��������
							self::$info = array();
						}
					}
				}else{
					//������� �����
				}
				
				if(isset(self::$info['id'])) {
					self::$info['info'] = str_replace('{login}',$u->info['login'],self::$info['info']);
					self::$info['info'] = str_replace('{level}',$u->info['level'],self::$info['info']);
					self::$info['info'] = str_replace("\r\n",'<br>',self::$info['info']);
					echo '<script>top.noob.takeData("'.self::$info['id'].'","'.self::$info['ico_bot'].'","'.self::$info['name_bot'].'","'.self::$info['name'].'","'.self::$info['act'].'","'.self::$info['next'].'","'.str_replace('"','&quot;',self::$info['info']).'");</script>';
				}else{
					echo '<script>top.noob.no();/* UN2 */</script>';
				}
			}else{
				//����� �� ������
				echo '<script>top.noob.no();/* UN1 */</script>';
			}
			//
		}
	}
	
}

?>