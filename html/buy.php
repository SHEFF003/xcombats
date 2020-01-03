<?
$bp = 75;
if( isset($_GET['bp']) ) {
	$bp = round((int)$_GET['bp']);
}

$pgd = mysql_fetch_array(mysql_query('SELECT * FROM `library_content` WHERE `id` = "'.mysql_real_escape_string($bp).'" LIMIT 1'));
if( isset($pgd['id']) ) {
	echo '<div style="padding:10px;">';
	if( $bp != 75 ) {
		echo '<h3><a href="http://xcombats.com/buy/">Услуги</a> &nbsp; &raquo; &nbsp; '.$pgd['title'].'</h3><br>';
	}else{
		echo '<h3>'.$pgd['title'].'</h3><br>';
	}
	echo $pgd['text'];
	echo '</div>';
}else{
	echo 'Страница не надена!';
}
?>