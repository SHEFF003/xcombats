<?  
/*if($_POST['item_id']){
	include_once('../../_incl_data/__config.php'); 
	define('GAME',true);
	include_once('../../_incl_data/class/__db_connect.php');
	$item = mysql_fetch_array(mysql_query("SELECT name FROM items_main WHERE id = '".$_POST['item_id']."' LIMIT 1"));
	echo $item['name']." [".$_POST['item_id']."]";
}
if($_POST['getListItems'] == true){
	include_once('../../_incl_data/__config.php'); 
	define('GAME',true);
	include_once('../../_incl_data/class/__db_connect.php');
	$items = mysql_query("SELECT id, info FROM items_main WHERE `info` LIKE 'Предмет для ботов%'");
	while($item = mysql_fetch_array($items)){
		$item['info'] = explode(':',$item['info']);
		echo $item['info'][1]." [".$item['id']."]";
	}
}*/
?>