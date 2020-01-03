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
				'\u0430' => 'а', '\u0410' => 'А', 
				'\u0431' => 'б', '\u0411' => 'Б', 
				'\u0432' => 'в', '\u0412' => 'В', 
				'\u0433' => 'г', '\u0413' => 'Г', 
				'\u0434' => 'д', '\u0414' => 'Д', 
				'\u0435' => 'е', '\u0415' => 'Е', 
				'\u0451' => 'ё', '\u0401' => 'Ё', 
				'\u0436' => 'ж', '\u0416' => 'Ж', 
				'\u0437' => 'з', '\u0417' => 'З', 
				'\u0438' => 'и', '\u0418' => 'И', 
				'\u0439' => 'й', '\u0419' => 'Й', 
				'\u043a' => 'к', '\u041a' => 'К', 
				'\u043b' => 'л', '\u041b' => 'Л', 
				'\u043c' => 'м', '\u041c' => 'М', 
				'\u043d' => 'н', '\u041d' => 'Н', 
				'\u043e' => 'о', '\u041e' => 'О', 
				'\u043f' => 'п', '\u041f' => 'П', 
				'\u0440' => 'р', '\u0420' => 'Р', 
				'\u0441' => 'с', '\u0421' => 'С', 
				'\u0442' => 'т', '\u0422' => 'Т', 
				'\u0443' => 'у', '\u0423' => 'У', 
				'\u0444' => 'ф', '\u0424' => 'Ф', 
				'\u0445' => 'х', '\u0425' => 'Х', 
				'\u0446' => 'ц', '\u0426' => 'Ц', 
				'\u0447' => 'ч', '\u0427' => 'Ч', 
				'\u0448' => 'ш', '\u0428' => 'Ш', 
				'\u0449' => 'щ', '\u0429' => 'Щ', 
				'\u044a' => 'ъ', '\u042a' => 'Ъ', 
				'\u044b' => 'ы', '\u042b' => 'Ы', 
				'\u044c' => 'ь', '\u042c' => 'Ь', 
				'\u044d' => 'э', '\u042d' => 'Э', 
				'\u044e' => 'ю', '\u042e' => 'Ю', 
				'\u044f' => 'я', '\u042f' => 'Я', 
				
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