<?php
//
require_once('VK.php');
//

// git clone https://github.com/Vastly/vkontakte-php-sdk
require_once('VK.php');

$accessToken = '';
$vkAPI = new \BW\Vkontakte(array('access_token' => $accessToken));
$publicID = 110317203;


if ($vkAPI->postToPublic($publicID, "Привет Хабр!", '/tmp/habr.png', array('вконтакте api', 'автопостинг', 'первые шаги'))) {

    echo "Ура! Всё работает, пост добавлен\n";

} else {

    echo "Фейл, пост не добавлен(( ищите ошибку\n";
}
?>