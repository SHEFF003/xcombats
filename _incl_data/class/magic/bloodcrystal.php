<?
if(!defined('GAME'))
{
	die();
}

//������� ������� (1 �� ����) , � ���-�� ��������� + 1 � ���������
if($u->info['room'] == 322) {
	$u->deleteItem($itm['id']);
	mysql_query('UPDATE `rep` SET `rep2` = `rep2` + 1 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	$u->error = '�� ������� ������������ &quot;'.$itm['name'].'&quot;...';
}else{
	$u->error = '������ ������������ � ���� �������...';
}
?>