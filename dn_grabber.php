<?

die('Что-то тут не так...');

$dn = htmlspecialchars($_GET['dn']);
//
if(!mkdir('./dnew/'.$dn.'',0777,true)) {
	echo '[Директория '.$dn.' не создана]<br>';
}
//
if(!mkdir('./dnew/'.$dn.'/Left',0777,true)) {
	echo '[Директория '.$dn.'/Left не создана]<br>';
}
if(!mkdir('./dnew/'.$dn.'/Right',0777,true)) {
	echo '[Директория '.$dn.'/Right не создана]<br>';	
}
//
if(!mkdir('./dnew/'.$dn.'/Left/Front',0777,true)) {
	echo '[Директория '.$dn.'/Left/Front не создана]<br>';	
}
if(!mkdir('./dnew/'.$dn.'/Right/Front',0777,true)) {
	echo '[Директория '.$dn.'/Right/Front не создана]<br>';	
}

function save_img($img) {
	global $dn;
	$s = file_get_contents('http://img.combats.ru/i/sprites/'.$dn.'/'.$img);
	file_put_contents('./dnew/'.$dn.'/'.$img , $s);
}

save_img('bg0.gif');
save_img('bg1.gif');

save_img('Left/4_1.gif');
save_img('Right/4_1.gif');
save_img('Left/4_0.gif');
save_img('Right/4_0.gif');
save_img('Left/Front/4_2.gif');
save_img('Right/Front/4_2.gif');
save_img('Left/Front/4_1.gif');
save_img('Right/Front/4_1.gif');
save_img('Left/Front/4_0.gif');
save_img('Left/3_1.gif');
save_img('Right/3_1.gif');
save_img('Left/3_0.gif');
save_img('Right/3_0.gif');
save_img('Left/Front/3_1.gif');
save_img('Right/Front/3_1.gif');
save_img('Left/Front/3_0.gif');
save_img('Left/2_0.gif');
save_img('Right/2_0.gif');
save_img('Left/Front/2_1.gif');
save_img('Right/Front/2_1.gif');
save_img('Left/Front/2_0.gif');
save_img('Left/1_0.gif');
save_img('Right/1_0.gif');
save_img('Left/Front/1_1.gif');
save_img('Right/Front/1_1.gif');
save_img('Left/Front/1_0.gif');
save_img('Left/0_0.gif');
save_img('Right/0_0.gif');

echo '[Подземелье '.$dn.' скопировано!]';
?>