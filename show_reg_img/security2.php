<?php
header("Content-type: image/png"); 
// ������� �������� �������� 172X52
$img = imagecreatetruecolor(70, 20) or die('Cannot create image');
$orange = imageColorAllocate($img, 255, 128, 64);
// ��������� ��� �������� 
imagefill($img, 0, 0, 0x616161); 
$x=0;
$i = 1;
$sum = "";
//���� ������
$color_RGB = rand(180,200);
while ($i++ <=5000) 
{
 imageSetPixel($img, rand(0,170), rand(0,50),0x515151);
}

//������ 2 �����
imageLine($img, rand(0,10), rand(0,50), rand(110,170), rand(0,50), 0x909090);
imageLine($img, rand(0,10), rand(0,50), rand(110,170), rand(0,50), 0x909090);

//�����
imageRectangle($img,0,0,170,50,0x343434);

$fonts = array ('fonts/CHILLER.ttf');
$font = '../'.$fonts[rand(0, sizeof($fonts)-1)];

  // ���������� ������
session_start();
// ������� ���� ����� �� ���� ������ ����� (����� 6 ����)
$i = 1;
while ($i++ <= 5)
{
   // ������� ����� ������ ��������
   imagettftext($img, rand(18,18), 0, $x=$x+10, 16+rand(0,4), 
   imagecolorallocate($img, $color_RGB,$color_RGB,$color_RGB), $font, $rnd = rand(0,9)); 
   // �������� � ���� ������ ��� ������� �� ��������
   $sum = $sum.(string)$rnd;
}

 
//�� �������� $sum �������� � ������� ��� STR1
 
// ������� ������� �������� � ������� PNG
imagepng($img);
// ����������� ������, ���������� ��� ��������
imagedestroy($img);
// �������� �������� ��� � ������
$_SESSION['code2'] = $sum;
?>