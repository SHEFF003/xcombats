<?php
header("Content-type: image/png"); 
// ������� �������� �������� 172X52
$img = imagecreatetruecolor(107, 26) or die('Cannot create image');
$orange = imageColorAllocate($img, 107, 26, 64);
// ��������� ��� �������� 
imagefill($img, 0, 0, 0x616161); 
$x=-20;
$i = 1;
$sum = "";
//���� ������
$color_RGB = rand(180,200);
while ($i++ <=5000) 
{
 imageSetPixel($img, rand(0,105), rand(0,24),0x515151);
}

//������ 2 �����
imageLine($img, rand(0,10), rand(0,50), rand(95,105), rand(0,26), 0x909090);
imageLine($img, rand(0,10), rand(0,50), rand(95,105), rand(0,26), 0x909090);

//�����
imageRectangle($img,0,0,105,24,0x343434);

$fonts = array ('fonts/FRSCRIPT.ttf','fonts/CHILLER.ttf','fonts/Bradley Hand ITC.ttf','fonts/de_Manu_2_Regular.ttf','fonts/Edgar_da_cool_Regular.ttf','fonts/Hurryup_Hurryup.ttf','fonts/Fh_Script_Regular.ttf','fonts/Gabo4_Gabo4.ttf','fonts/JAMI_Regular.ttf','fonts/Justy1_Regular.ttf');
$font = '../'.$fonts[rand(0, sizeof($fonts)-1)];

  // ���������� ������
session_start();
// ������� ���� ����� �� ���� ������ ����� (����� 6 ����)
$i = 1;
while ($i++ <= 4)
{
   // ������� ����� ������ ��������
   imagettftext($img, 15, 0, $x=$x+25, 20, 
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
$_SESSION['code'] = $sum;
?>