<?
header( 'Expires: Mon, 26 Jul 1970 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
header( 'Content-Type: text/html; charset=windows-1251' );

define('GAME',true);
include('../_incl_data/class/__db_connect.php');
mysql_query('SET NAMES utf8');

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	$user = mysql_fetch_array(mysql_query('SELECT `id`,`battle` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass` = "'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
	if( isset($user['id']) && $user['battle'] > 0 ) {
		//
		function json_fix_cyr($json_str) { 
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
		//
		$r = array();
		$p = array();
		$p['u'] = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`align`,`clan`,`sex`,`obraz` FROM `users` WHERE `id` = "'.mysql_real_escape_string($_GET['uid']).'" LIMIT 1'));
		if(isset($p['u']['id'])) {
			$r = array(
				'id' => $p['u']['id'],
				'login' => $p['u']['login'],
				'level' => $p['u']['level'],
				'sex' => $p['u']['sex'],
				'obraz' => $p['u']['obraz'],
				'align' => $p['u']['align'],
				'clan' => $p['u']['clan']
			);
		}else{
			$r['error'] = 1;
		}
		//
		echo json_fix_cyr(json_encode($r));
		//echo json_encode($r);
	}else{
		echo 'false';
	}
}
?>