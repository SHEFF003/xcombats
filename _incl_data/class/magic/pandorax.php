<?
if(!defined('GAME'))
{
	die();
}


$sp0 = mysql_query('SELECT `items_id` FROM `items_main_data` WHERE `data` LIKE "%art=%"');
$rnd0 = array();
while($pl0 = mysql_fetch_array($sp0)) {
	$rnd0[count($rnd0)] = $pl0['items_id'];
}

$rnd0['itm1'] = $rnd0[rand(0,count($rnd0))];
$rnd0['itm2'] = $rnd0[rand(0,count($rnd0))];
$rnd0['itm3'] = $rnd0[rand(0,count($rnd0))];
$rnd0['itm1'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($rnd0['itm1']).'" LIMIT 1'));
$rnd0['itm2'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($rnd0['itm2']).'" LIMIT 1'));

$u->addItem($rnd0['itm1']['id'],$u->info['id'],'|srok=1209600|sudba='.$u->info['login']);
$u->addItem($rnd0['itm2']['id'],$u->info['id'],'|srok=1209600|sudba='.$u->info['login']);

if(rand(0,7) == 2) {
	$rnd0['itm3'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($rnd0['itm3']).'" LIMIT 1'));
}else{
	$u->addItem($rnd0['itm3']['id'],$u->info['id'],'|srok=1209600|sudba='.$u->info['login']);
}

$u->deleteItem($itm['id'],$u->info['id']);
$u->error = '�� ������� ������� &quot;'.$itm['name'].'&quot;...';

unset($rnd0,$sp0,$pl0);

//������ ��������, �� �� ������ 00:00:00 01-01-2013222
/*
$rnd = rand(0,1000000);
$rnd = floor($rnd/10000);

$rnd = rand(0,1000000);
$rnd = floor($rnd/10000);
if($rnd < 50) {
	//������ ��.
	$mn = rand(0,1000000);
	$mn = round($mn/rand(1000,10000),2);
	$u->error = '� &quot;'.$itm['name'].'&quot; �� ���������� ������: '.$mn.' ��. ...<br>';	
}
if($rnd < 25) {
	//������������� ������
	$rnd = rand(0,1000000);
	$rnd = floor($rnd/10000);
	if($rnd < 50) {
		//��������� ����
		$u->error .= '�� &quot;'.$itm['name'].'&quot; ��������� ������ ...';
	}else{
		//���������� ������ (������)
		$u->error .= '������� &quot;'.$itm['name'].'&quot; ������� ���������. ����������� ������ ...';
	}
}else{
	//������������� ������
	$rnd = rand(0,1000000);
	$rnd = floor($rnd/10000);
	if($rnd < 50) {
		//�������
		$u->error .= '� &quot;'.$itm['name'].'&quot; �� ���������� ������� ...';
	}else{
		//������
		$u->error .= '������� &quot;'.$itm['name'].'&quot; ������� ��� ����� �����. ����������� ������ ...';
	}
}
*/
?>