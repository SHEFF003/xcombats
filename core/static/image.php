<?php
header("Content-type: image/png"); 
// создаем картинку размером 172X52
$img = imagecreatetruecolor(172, 52) or die('Cannot create image');
$orange = imageColorAllocate($img, 255, 128, 64);
$FIGURES = array('50','70','90','110','130','150','170','190','210');

// заполняем фон картинки 
//0x919191
imagefill($img, 0, 0, imagecolorallocate($img,255,255,255)); 
$x=0;
$i = 1;
$sum = "";

//цвет текста
$color_RGB = rand(180,200);
while ($i++ <=5000) {
	imageSetPixel($img, rand(0,170), rand(0,50),imagecolorallocate($img,245,245,245));
}

//рисуем 2 линии
imageLine($img, rand(0,10), rand(0,50), rand(110,170), rand(0,50), imagecolorallocate($img,150,150,150));
imageLine($img, rand(0,10), rand(0,50), rand(110,170), rand(0,50), imagecolorallocate($img,150,150,150));

//рамка
imageRectangle($img,1,1,170,51,imagecolorallocate($img,220,220,220));

$fonts = array ('fonts/FRSCRIPT.ttf','fonts/CHILLER.ttf','fonts/Bradley Hand ITC.ttf','fonts/de_Manu_2_Regular.ttf','fonts/Edgar_da_cool_Regular.ttf','fonts/Hurryup_Hurryup.ttf','fonts/Fh_Script_Regular.ttf','fonts/Gabo4_Gabo4.ttf','fonts/JAMI_Regular.ttf','fonts/Justy1_Regular.ttf');
$font = $fonts[rand(0, sizeof($fonts)-1)];
$font = 'fonts/ARESSENCE.ttf';
// Инициируем сессию
session_start();
// выводим одну цифру за один проход цикла (всего 6 цифр)
$i = 1;
while ($i++ <= 6) {
   $color = imagecolorallocatealpha($img,$FIGURES[rand(0,sizeof($FIGURES)-1)],$FIGURES[rand(0,sizeof($FIGURES)-1)],$FIGURES[rand(0,sizeof($FIGURES)-1)],rand(10,30));
   // выводим текст поверх картинки
   imagettftext($img, rand(20,25), rand(-35,35), $x=$x+25, 30+rand(0,10), 
   $color, $font, $rnd = rand(0,9)); 
   // Собираем в одну строку все символы на картинке
   $sum = $sum.(string)$rnd;
}

// выводим текст поверх картинки
  // imagettftext($img, 8, 0, 110, 52, imagecolorallocate($img,10,10,10), 'fonts/cour.ttf' , 'new.combatz.ru');
  imagettftext($img, 8, 0, 7, 50, imagecolorallocate($img,77,77,77), 'fonts/cour.ttf' , 'new.combatz.ru &copy; 2013-'.date('Y'));

 
//Не забудьте $sum записать в таблицу как STR1
 
// выводим готовую картинку в формате PNG
imagepng($img);
// освобождаем память, выделенную для картинки
imagedestroy($img);
// Помещаем защитный код в сессию
$_SESSION['code'] = $sum;
?>