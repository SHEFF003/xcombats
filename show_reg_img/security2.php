<?php
header("Content-type: image/png"); 
// создаем картинку размером 172X52
$img = imagecreatetruecolor(70, 20) or die('Cannot create image');
$orange = imageColorAllocate($img, 255, 128, 64);
// заполняем фон картинки 
imagefill($img, 0, 0, 0x616161); 
$x=0;
$i = 1;
$sum = "";
//цвет текста
$color_RGB = rand(180,200);
while ($i++ <=5000) 
{
 imageSetPixel($img, rand(0,170), rand(0,50),0x515151);
}

//рисуем 2 линии
imageLine($img, rand(0,10), rand(0,50), rand(110,170), rand(0,50), 0x909090);
imageLine($img, rand(0,10), rand(0,50), rand(110,170), rand(0,50), 0x909090);

//рамка
imageRectangle($img,0,0,170,50,0x343434);

$fonts = array ('fonts/CHILLER.ttf');
$font = '../'.$fonts[rand(0, sizeof($fonts)-1)];

  // Инициируем сессию
session_start();
// выводим одну цифру за один проход цикла (всего 6 цифр)
$i = 1;
while ($i++ <= 5)
{
   // выводим текст поверх картинки
   imagettftext($img, rand(18,18), 0, $x=$x+10, 16+rand(0,4), 
   imagecolorallocate($img, $color_RGB,$color_RGB,$color_RGB), $font, $rnd = rand(0,9)); 
   // Собираем в одну строку все символы на картинке
   $sum = $sum.(string)$rnd;
}

 
//Не забудьте $sum записать в таблицу как STR1
 
// выводим готовую картинку в формате PNG
imagepng($img);
// освобождаем память, выделенную для картинки
imagedestroy($img);
// Помещаем защитный код в сессию
$_SESSION['code2'] = $sum;
?>