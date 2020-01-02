<?
header( 'Expires: Mon, 26 Jul 1970 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
header( 'Content-Type: text/html; charset=windows-1251' );

define('GAME',true);
include('../_incl_data/class/__db_connect.php');
include('../_incl_data/class/__user.php');
mysql_query('SET NAMES utf8');

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	if( isset($u->info['id']) && $u->info['battle'] > 0 ) {
		//
		function json_fix_cyr($json_str) { 
			$cyr_chars = array ( 
				'\u0430' => 'à', '\u0410' => 'À', 
				'\u0431' => 'á', '\u0411' => 'Á', 
				'\u0432' => 'â', '\u0412' => 'Â', 
				'\u0433' => 'ã', '\u0413' => 'Ã', 
				'\u0434' => 'ä', '\u0414' => 'Ä', 
				'\u0435' => 'å', '\u0415' => 'Å', 
				'\u0451' => '¸', '\u0401' => '¨', 
				'\u0436' => 'æ', '\u0416' => 'Æ', 
				'\u0437' => 'ç', '\u0417' => 'Ç', 
				'\u0438' => 'è', '\u0418' => 'È', 
				'\u0439' => 'é', '\u0419' => 'É', 
				'\u043a' => 'ê', '\u041a' => 'Ê', 
				'\u043b' => 'ë', '\u041b' => 'Ë', 
				'\u043c' => 'ì', '\u041c' => 'Ì', 
				'\u043d' => 'í', '\u041d' => 'Í', 
				'\u043e' => 'î', '\u041e' => 'Î', 
				'\u043f' => 'ï', '\u041f' => 'Ï', 
				'\u0440' => 'ð', '\u0420' => 'Ð', 
				'\u0441' => 'ñ', '\u0421' => 'Ñ', 
				'\u0442' => 'ò', '\u0422' => 'Ò', 
				'\u0443' => 'ó', '\u0423' => 'Ó', 
				'\u0444' => 'ô', '\u0424' => 'Ô', 
				'\u0445' => 'õ', '\u0425' => 'Õ', 
				'\u0446' => 'ö', '\u0426' => 'Ö', 
				'\u0447' => '÷', '\u0427' => '×', 
				'\u0448' => 'ø', '\u0428' => 'Ø', 
				'\u0449' => 'ù', '\u0429' => 'Ù', 
				'\u044a' => 'ú', '\u042a' => 'Ú', 
				'\u044b' => 'û', '\u042b' => 'Û', 
				'\u044c' => 'ü', '\u042c' => 'Ü', 
				'\u044d' => 'ý', '\u042d' => 'Ý', 
				'\u044e' => 'þ', '\u042e' => 'Þ', 
				'\u044f' => 'ÿ', '\u042f' => 'ß', 
				
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
		$p['sp'] = mysql_query('SELECT
			`u`.`id`,`u`.`login`,`u`.`login2`,`u`.`align`,`u`.`level`,
			`s`.`hpNow`,`s`.`mpNow`,`s`.`team`
		FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `u`.`battle` = "'.$u->info['battle'].'"
		');
		while( $p['pl'] = mysql_fetch_array($p['sp']) ) {
			$p['st'] = $u->getStats($p['pl']['id']);
			$r[] = array(
				'id' => $p['pl']['id'],
				'login' => $p['pl']['login'],
				'team' => $p['pl']['team'],
				'hpNow' => $p['pl']['hpNow'],
				'mpNow' => $p['pl']['mpNow'],
				'hpAll' => $p['st']['hpAll'],
				'mpAll' => $p['st']['mpAll'],
				'align' => $p['pl']['align'],
				'clan' => $p['pl']['clan'],
				'level' => $p['pl']['level']				
			);
		}
		unset($p);
		//
		echo json_fix_cyr(json_encode($r));
		//echo json_encode($r);
	}else{
		echo 'false';
	}
}
?>