<?php

error_reporting(1);
ini_set('display_errors','On');

if(!isset($_SERVER["HTTP_CF_IPCOUNTRY"])) {
	$_SERVER["HTTP_CF_IPCOUNTRY"] = '0';
}

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

setlocale(LC_CTYPE ,"ru_RU.CP1251");

//die('������� �������� �� ��������, ����� 5-10');

$c = array(
	'ver' => '2.0.0.0'
);
/* ������������ ���� */

$c['server_ip'] = '5.187.6.194';

$c['title']  = '������� ���������� ���� - ����������, ����������� ���������� ������ ����'; //�������� ����
$c['title2'] = ' - ����������, ����������� ������ ���� ����������� ��������� � �����!';
$c['title3']  = '������ ���������� ����';
$c['name']   =  '������ ���������� ����';
$c['keys']   = ''; //�������� ����� META
$c['desc']   = ''; //�������� META

//�������
$c['host']        = 'xcombats.com';
$c['forum']       = 'forum.'.$c['host'];
$c['img']   	  = 'img.xcombats.com';
$c['thiscity']    = 'capitalcity';
$c['capitalcity'] = $c['host'];
$c['abandonedplain'] = $c['host'];
$c['exit']		  = '<script>top.location="http://'.$c['host'].'/";</script><noscript><meta http-equiv="refresh" content="0; URL=http://'.$c['host'].'/"></noscript>';

//���
$c['chat_level'] = 4; // � ������ ��� ������ �����, ����� ������ ���

//�����
$c['birja_sale'] = 2;
$c['birja_buy'] = 2; 

//������
$c['curency_name'] = 'RUB';
$c['curency_value'] = 30;

//��� ����
$c['bot_level'] = 3; // �� ������ ��� ��� (������������)
$c['propsk_die'] = 0; //������ ��� �������� �����, 0 - ����. , >= 1 - ���������� ��������� �� ������ ��� ���������
$c['haotbot'] = 8; //�� ������ ��� ��� � ������
$c['max_zv_analog'] = 3; // ������� ����������� ������ ����� ��������

//������
$c['exp'] = 0; //����� �����
$c['shop_type1'] = 100; //� ��� �� �������
$c['shop_type2'] = 100; //� ������� �� �������
$c['shop_all'] = 100; //������ �� ���! , 0 - ������� ��� ���������� ������. 
$c['nosanich']	 = false; //��������� ������ �� �������� - true , �������� - false /�� ���� ���� ���
$c['zuby'] = false; //����
$c['limitedexp'] = false; //����� �����
$c['infinity5level'] = true; //������ 5 ������
$c['expstop'] = 3000000; //���� �� ������� ���������������
$c['noobgade'] = false; //��� ����� (������)
$c['bonusonline'] = true; //����� �� ������
$c['bonusonline_kof'] = 1; //��������� �� �� ���
$c['level_ransfer'] = 5; //� ������ ������ ��������� ��������
$c['znahar'] = true; //���������� �������
$c['nolevel'] = false; //������������� �������
$c['noitembuy'] = false; //�� ������� ������� ��� �������
$c['effz'] = 0; //�������� �������� ����� �����
$c['money_haot'] = true; //�� �� �����
$c['money_haot_proc'] = 0; //������� ��������� �� ����� ������ � �������
$c['crtoecr'] = 0; //���� ������ �� �� ��� (���� 0, �� ���������)
$c['ecrtocr'] = 50; //���� ������ ��� �� ��
$c['bonuslevel'] = true; //����� ������
$c['bonussocial'] = false; //����� ����������
$c['exp_limit_many'] = false; //����� �����, ���� ����� 5 ����, �� 0 ����� ����
$c['exp_mega'] = true; //���������� ����� �����
$c['exp_mega_val'] = array(
	0 => 200,
	1 => 175,
	2 => 150,
	3 => 125,
	4 => 100,
	5 => 75,
	6 => 50,
	7 => 0,
	8 => 0,
	9 => 0,
	10 => 0,
	11 => 0,
	12 => 0,
	13 => 0,
	14 => 0,
	15 => 0,
	16 => 0,
	17 => 0,
	18 => 0,
	19 => 0,
	20 => 0,
	21 => 0
); //���������� ����� �����


$c['bonline'] = array(
	array( 1 , 2 , 3 , 4 , 5 , 6 , 7 , 8 , 9 , 10 , 11 , 12 , 13 ), //������� ��
	array( 0.01 , 0.02 , 0.03 , 0.04 , 0.05 , 0.06 , 0.07 , 0.08 , 0.15 , 0.25 , 0.35 , 0.45 , 0.55 ) //������� ���, ���� ���� ����������
);

$c['w'] = date('w');

//����
//$code = explode(' ',microtime());
//$code = $code[0].''.round($code[1]/rand(1,5));
$code = '1';
$c['counters']  = '<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "2147109", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2147109;js=na" style="border:0;" height="1" width="1" alt="�������@Mail.ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->
<!-- Rating@Mail.ru logo -->
<a target="_blank" href="http://top.mail.ru/jump?from=2147109">
<img src="//top-fwz1.mail.ru/counter?id=2147109;t=49;l=1" 
style="border:0;" height="31" width="88" alt="�������@Mail.ru" /></a>
<!-- //Rating@Mail.ru logo -->'."<!--LiveInternet counter--><script type=\"text/javascript\">document.write(\"<a href='//www.liveinternet.ru/click' target=_blank><img src='//counter.yadro.ru/hit?t11.3;r\" + escape(top.document.referrer) + ((typeof(screen)==\"undefined\")?\"\":\";s\"+screen.width+\"*\"+screen.height+\"*\"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + \";u\" + escape(document.URL) + \";\" + Math.random() + \"' border=0 width=88 height=31 alt='' title='LiveInternet: �������� ����� ���������� �� 24 ����, ����������� �� 24 ���� � �� �������'><\/a>\")</script><!--/LiveInternet-->
";
$c['securetime'] = 0; //����� ���������� ���������� ������ ������ (������ ������ �� ����� ������ ������ ���)


//$c['counters'] = '';

//$c['counters'] = '';

if(isset($_GET['ajax'])) {
	$c['counters'] = '';
}

$c['copyright'] = 'Copyright � '.date('Y').' ������� ���������� ����';

$c['counters_noFrm']  = $c['counters'];

if(isset($_GET['version'])) {
	die('Version: '.$c['ver'].'');
}
?>