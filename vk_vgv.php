<?php
//
require_once('VK.php');
//

// git clone https://github.com/Vastly/vkontakte-php-sdk
require_once('VK.php');

$accessToken = '';
$vkAPI = new \BW\Vkontakte(array('access_token' => $accessToken));
$publicID = 110317203;


if ($vkAPI->postToPublic($publicID, "������ ����!", '/tmp/habr.png', array('��������� api', '�����������', '������ ����'))) {

    echo "���! �� ��������, ���� ��������\n";

} else {

    echo "����, ���� �� ��������(( ����� ������\n";
}
?>