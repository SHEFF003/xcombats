<?php

namespace Core;
class Utils {

	/*
	@ ����� ��������� �� ������ ��������
	*/
	public function redirect( $url ) {
		header( 'location: ' . $url );
	}
	
	/*
	@ ����� ���������� ����������
	@
	*/
	public static function lookStats($m) {
		$ist = array();
		$di = explode('|',$m);
		$i = 0; $de = false;
		while($i<count($di))
		{
			$de = explode('=',$di[$i]);
			if(isset($de[0],$de[1]))
			{
				if(!isset($ist[$de[0]])) {
					$ist[$de[0]] = 0;
				}
				$ist[$de[0]] = $de[1];
			}
			$i++;
		}
		return $ist;
	}

	/*
	@ ����� ������ ����� �����
	@ TYPE: 0 - ����� �����
	*/
	public function num( $val, $type ) {
		if( $type == 0 ) {
			$val = floor((int)$val);
		}
		return $val;
	}
	
	/*
	@ ����� ����������� ������ �������� ��� ���
	@ TYPE: 0 - �����, 1 - �����
	*/
	public function emptyVal( $val, $type ) {
		$r = true;
		if( !isset($val) ) {
			$r = false;
		}elseif( $type == 0 ) {
			if( $val == 0 ) {
				$r = false;
			}
		}elseif( $type == 1 ) {
			$val = str_replace( ' ', '', str_replace( '	', '', $val ) );			
			if( $val == '' ) {
				$r = false;
			}
		}
		return $r;
	}
	
	/*
	@ ����� ���������� ������ (��� POST ��� GET)
	*/
	public function fs( $val ) {
		$val = htmlspecialchars( $val ) ;
		return $val;
	}
	
	/*
	@ ����� �������� ���� � ��������
	*/
	public function cookie( $name , $value = NULL , $time = NULL ) {
		if( $value == NULL ) {
			return $_COOKIE[$name];
		}elseif( $value != false ) {		
			if( $time == NULL ) {
				$time = 86400;
			}
			return setcookie( $name , $value , OK + $time , '/' );
		}else{
			return setcookie( $name , '' , OK - 86400 , '/' );
		}
	}
	
	/*
	@ ����� ������ ������
	*/
	public function ses_start() {
		if ( session_id() ) return true;
		else return session_start();
	}
	
	/*
	@ ������������� ������
	*/
	public function testVal( $val , $min , $max , $sym , $nosym , $nostart , $noend , $data ) {
		$r = true;
		if( mb_strlen($val,'UTF-8') < $min || mb_strlen($val,'UTF-8') > $max ) {
			$r = false;
		}else{
			//���������� �������
			if( $sym != false ) {
				$i = 0;
				$new_val = mb_strtolower($val,'UTF-8');
				while( $i < mb_strlen($val,'UTF-8') ) {
					$j = 0;
					$k = 0;
					$k2 = 0;
					while( $j < mb_strlen($sym,'UTF-8') ) {
						if( mb_strtolower($val[$i],'UTF-8') == mb_strtolower($sym[$j],'UTF-8') ) {
							$k++;
						}else{
							if( isset($data['noXsym']) ) {
								//������ ������������ ����� X �������� ������
								$l = 0;
								$notxt = '';
								while( $l < $data['noXsym'] ) {
									$notxt .= mb_strtolower($sym[$j],'UTF-8');
									$l++;
								}
								if( mb_strpos($new_val,$notxt,NULL,'UTF-8') !== false ) {
									$k2++;
								}
							}
						}
						$j++;
					}
					if( $k == 0 || $k2 > 0 ) {
						$i = mb_strlen($val,'UTF-8');
						$r = false;
					}
					$i++;
				}
			}
			//�� ���������� �������
			if( $nosym != false ) {
				$i = 0;
				$new_val = '';
				while( $i < count($nosym) ) {
					if( mb_strpos(mb_strtolower($val,'UTF-8'),mb_strtolower($nosym[$i],'UTF-8'),NULL,'UTF-8') !== false ) {
						$i = count($nosym);
						$r = false;
					}
					$i++;
				}
			}
			//�� ���������� ������
			if( $nostart != false ) {
				$i = 0;
				$new_val = '';
				while( $i < count($nostart) ) {
					if( mb_substr( $val, 0, mb_strlen($nostart[$i],'UTF-8'),'UTF-8') == $nostart[$i] ) {
						$i = count($nostart);
						$r = false;
					}
					$i++;
				}
			}
			//�� ���������� �����
			if( $noend != false ) {
				$i = 0;
				$new_val = '';
				while( $i < count($noend) ) {
					if( mb_substr( $val, ( mb_strlen($val,'UTF-8') - mb_strlen($noend[$i],'UTF-8') ) , 0 , 'UTF-8') == $noend[$i] ) {
						$i = count($noend);
						$r = false;
					}
					$i++;
				}
			}
		}
		return $r;
	}
	
	/*
	@ ����� "����������" ������
	*/
	public function ses_end() {
		if ( session_id() ) {
			// ���� ���� �������� ������, ������� ���� ������,
			setcookie(session_name(), session_id(), time()-60*60*24);
			// � ���������� ������
			session_unset();
			session_destroy();
		}
	}
	
	/*
	@ ����� ����������� ���� ������, �����, ��.�����
	*/
	public function takeType( $val ) {
		
		if( preg_match( "|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $val ) ) {
				//��.�����
				return 2;
		}else{
			preg_match_all( '([0-9])', $val, $matches );
			$res = implode( NULL, $matches[0] );
			if( mb_strlen( $val, 'UTF-8' ) > mb_strlen( $res, 'UTF-8' ) ) {
				//������
				return 1;
			}else{
				//�����
				return 3;
			}
		}
	}
	
	/*
	@ ����� �������� ������������ �������
	*/
	public function testPass( $psw1, $psw2 ) {
		if( $psw1 == $psw2 ) {
			return true;
		}else{
			return false;
		}
	}
	
	/*
	@ ����� ��������� ����� �����������
	*/
	public function createAuth( $par ) {
		if( !isset( $par['rand'] ) ) {
			$par['rand'] = rand(10000000,90000000);
		}
		$r = $par['rand'] . md5( $par['ip'] . '+' . $par['id'] . '+' . $par['pass'] . '+' . $par['rand'] );
		return $r;
	}
	
	/*
	@ ����� �������� ����� �����������
	*/
	public function testAuth( $auth, $par ) {
		$par['rand'] = substr( $auth, 0, 8 );
		if( $auth == self::createAuth( $par ) ) {
			return true;
		}else{
			return false;
		}
	}
	
	/*
	@ ����� ������ JSON ������
	*/
	public function JSON_Headers() {
		header('Expires: Mon, 26 Jul 1970 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Content-Type: application/json; charset=utf-8');
		return true;
	}
	
	/*
	@ ����� �������������� ������� � JSON
	*/
	public function jsonencode( $val ) {
		array_walk_recursive( $val, function( &$value, $key ) {
		   $value = iconv( "CP1251", "UTF-8", $value );
		});
		return json_encode( $val );
		//return json_encode( $val );
		//return self::json_fix_cyr( json_encode( $val ) );
	}
	
	/*
	@ ����� �������������� JSON � ������
	*/
	public function jsondecode( $val ) {
		return json_decode( $val );
	}
		
	/*
	@ ����� ����� ������������� ��������
	*/
	public function json_fix_cyr($json_str) { 
		$cyr_chars = array ( 
			'\u0430' => '�', '\u0410' => '�', 
			'\u0431' => '�', '\u0411' => '�', 
			'\u0432' => '�', '\u0412' => '�', 
			'\u0433' => '�', '\u0413' => '�', 
			'\u0434' => '�', '\u0414' => '�', 
			'\u0435' => '�', '\u0415' => '�', 
			'\u0451' => '�', '\u0401' => '�', 
			'\u0436' => '�', '\u0416' => '�', 
			'\u0437' => '�', '\u0417' => '�', 
			'\u0438' => '�', '\u0418' => '�', 
			'\u0439' => '�', '\u0419' => '�', 
			'\u043a' => '�', '\u041a' => '�', 
			'\u043b' => '�', '\u041b' => '�', 
			'\u043c' => '�', '\u041c' => '�', 
			'\u043d' => '�', '\u041d' => '�', 
			'\u043e' => '�', '\u041e' => '�', 
			'\u043f' => '�', '\u041f' => '�', 
			'\u0440' => '�', '\u0420' => '�', 
			'\u0441' => '�', '\u0421' => '�', 
			'\u0442' => '�', '\u0422' => '�', 
			'\u0443' => '�', '\u0423' => '�', 
			'\u0444' => '�', '\u0424' => '�', 
			'\u0445' => '�', '\u0425' => '�', 
			'\u0446' => '�', '\u0426' => '�', 
			'\u0447' => '�', '\u0427' => '�', 
			'\u0448' => '�', '\u0428' => '�', 
			'\u0449' => '�', '\u0429' => '�', 
			'\u044a' => '�', '\u042a' => '�', 
			'\u044b' => '�', '\u042b' => '�', 
			'\u044c' => '�', '\u042c' => '�', 
			'\u044d' => '�', '\u042d' => '�', 
			'\u044e' => '�', '\u042e' => '�', 
			'\u044f' => '�', '\u042f' => '�', 
			
			'\r' => '', 
			'\n' => '<br />', 
			'\t' => '' 
		);
		foreach ($cyr_chars as $cyr_char_key => $cyr_char) { 
			$json_str = str_replace($cyr_char_key, $cyr_char, $json_str); 
		} 
		return $json_str; 
	}
	
	public static function timeOut($ttm)
	{
		 $out = '';
		$time_still = $ttm;
		$tmp = floor($time_still/2592000);
		$id=0;
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
			$time_still = $time_still-$tmp*2592000;
		}
		$tmp = floor($time_still/86400);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ��. ";}
			$time_still = $time_still-$tmp*86400;
		}
		$tmp = floor($time_still/3600);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." �. ";}
			$time_still = $time_still-$tmp*3600;
		}
		$tmp = floor($time_still/60);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
		}
		if($out=='')
		{
			if($time_still<0)
			{
				$time_still = 0;
			}
			$out = $time_still.' ���.';
		}
		return $out;
	}
	
}

?>