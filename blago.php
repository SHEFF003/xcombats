<?php
if(!isset($_GET['kill'])) { die(); }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Благодать Алхимика</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script type="text/javascript" src="/7E6B1377EF26442EBB8571ECA242C7BE/AA871BD4-9841-8043-97E5-B6FB8495D28A/main.js" charset="UTF-8"></script><SCRIPT LANGUAGE="JavaScript" SRC="http://img.combats.ru/i/js/dialog_032_ru.js?v=1.168" charset="utf-8"></SCRIPT>
<script type="text/javascript" src='http://img.combats.ru/js/CombatsUI.js?v=1.168' charset='utf-8'></script>
<SCRIPT LANGUAGE="JavaScript" SRC="http://img.combats.ru/js/jquery.min.js?v=1.168"></SCRIPT>
<script type="text/javascript" src="http://img.combats.ru/i/premium/buypage/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="http://img.combats.ru/i/premium/buypage/jquery.fancybox.css?v=2.1.5" />
<style>
@font-face {
font-family: 'Linux Biolinum';
src: url('http://img.combats.ru/i/premium/buypage/fonts/linbiolinum.eot');
src: url('http://img.combats.ru/i/premium/buypage/fonts/linbiolinum.eot?#iefix') format('embedded-opentype'),
url('http://img.combats.ru/i/premium/buypage/fonts/linbiolinum.woff') format('woff'),
url('http://img.combats.ru/i/premium/buypage/fonts/linbiolinum.ttf') format('truetype'),
url('http://img.combats.ru/i/premium/buypage/fonts/linbiolinum.svg#LinBiolinumOB') format('svg');
font-weight: normal;
font-style: normal;
}
@font-face{
font-family: 'Circe Regular';
src: url('http://img.combats.ru/i/premium/buypage/fonts/circe-regular.eot');
src: url('http://img.combats.ru/i/premium/buypage/fonts/circe-regular.eot?#iefix') format('embedded-opentype'),
url('http://img.combats.ru/i/premium/buypage/fonts/circe-regular.woff') format('woff'),
url('http://img.combats.ru/i/premium/buypage/fonts/circe-regular.ttf') format('truetype'),
url('http://img.combats.ru/i/premium/buypage/fonts/circe-regular.svg#Circe-Regular') format('svg');
font-weight: normal;
font-style: normal;
}
body {
font-family: 'Circe Regular', Arial, Helvetica, sans-serif;
margin: 0;
padding: 0;
background-image: url('http://img.combats.ru/i/premium/buypage/bg.jpeg');
background-size: cover;
filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='http://img.combats.ru/i/premium/buypage/bg.jpeg', sizingMethod='scale');
-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='http://img.combats.ru/i/premium/buypage/bg.jpeg', sizingMethod='scale')";
}
div.wrapper {
width: 1020px;
height: 1900px;
margin: auto;
background-image: url('http://img.combats.ru/i/premium/buypage/table-bg1.png');
background-position: center top;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
border: 1px solid #5e4741;
-moz-box-shadow: 0px 0px 28px #000;
-webkit-box-shadow: 0px 0px 28px #000;
box-shadow: 0px 0px 28px #000;
-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000')";
filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000');
position: relative;
top: -4px;
}
div.header {
height: 210px;
color: #e9d8c9;
}
div.header .status {
height: 36px;
padding: 10px 24px 0;
font-size: 16px;
white-space: nowrap;
position: relative;
left: -50%;
border: 1px solid #6283a3;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
background-color: rgba(40,223,255,0.2);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#3228dfff', endColorstr='#3228dfff');
}
div.header .account-status {
text-align: left;
position: relative;
top: 170px;
padding-left: 20px;
}
div.header .account-status span {
display: inline-block;
text-align: center;
vertical-align: middle;
}
div.header .account-status .current-icon {
width: 54px;
height: 62px;
background-position: center center;
background-repeat: no-repeat;
}
div.header .account-status .current-icon:after {
display: none;
-webkit-transition: all 0.3s ease-out;
transition: all 0.3s ease-out;
}
div.header .account-status .current-icon:hover:after {
content: attr(data-date);
display: inline-block;
position: relative;
width: 140px;
top: 50px;
left: -44px;
}
div.header .account-status span button {
color: #e9d8c9;
font-family: "Linux Biolinum", Arial, Helvetica, sans-serif;
cursor: pointer;
text-align: center;
width: 220px;
height: 30px;
line-height: 0.4;
border: 1px solid #8e8078;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
text-transform: uppercase;
-webkit-transition: all 0.3s ease-out;
transition: all 0.3s ease-out;
background-color: rgba(189,144,139,.3);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#48bd908b', endColorstr='#48bd908b');
}
div.header .account-status span button:hover {
background-color: rgba(189,144,139,.5);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#80bd908b', endColorstr='#80bd908b');
}
table.premium_table {
width: 100%;
font-size: 12px!important;
}
table.premium_table tr {
height: 60px;
text-align: center;
color: #f7e2d0;
}
table.premium_table a {
color: #f7e2d0;
text-decoration: none;
}
table.premium_table tr td {
width: 72px;
background-color: rgba(189,144,139,.2);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#32BD908B', endColorstr='#32BD908B');
border-bottom: 1px solid #5e4741;
}
table.premium_table tr td:first-child {
width: 36%;
font-size: 14px;
padding: 6px 20px;
background-color: transparent;
filter: none;
border-bottom: 1px solid #48342f;
-webkit-border-radius: 0!important;
-moz-border-radius: 0!important;
border-radius: 0!important;
}
table.premium_table tr td.ok {
background-image: url('http://img.combats.ru/i/premium/buypage/ok.png');
background-position: 50% 50%;
background-repeat: no-repeat;
font-size: 0;
}
table.premium_table tr td.empty {
background-image: url('http://img.combats.ru/i/premium/buypage/1px.png');
background-position: 50% 50%;
background-repeat: repeat;
font-size: 0;
}
table.premium_table tr.icons td {
position: relative;
height: 82px;
border-bottom: none;
}
table.premium_table tr.icons div {
width: 148px;
height: 128px;
position: absolute;
top: -20px;
left: -13px;
background-position: center center;
}
table.premium_table tr.icons div.icon_1 {
background-image: url('http://img.combats.ru/i/premium/buypage/1.png');
-webkit-transition: all 0.2s ease-out;
transition: all 0.2s ease-out;
}
table.premium_table tr.icons td.hover div.icon_1 {
background-image: url('http://img.combats.ru/i/premium/buypage/1-hover.png');
}
table.premium_table tr.icons div.icon_2 {
background-image: url('http://img.combats.ru/i/premium/buypage/2.png');
-webkit-transition: all 0.2s ease-out;
transition: all 0.2s ease-out;
}
table.premium_table tr.icons td.hover div.icon_2 {
background-image: url('http://img.combats.ru/i/premium/buypage/2-hover.png');
}
table.premium_table tr.icons div.icon_3 {
background-image: url('http://img.combats.ru/i/premium/buypage/3.png');
-webkit-transition: all 0.2s ease-out;
transition: all 0.2s ease-out;
}
table.premium_table tr.icons td.hover div.icon_3 {
background-image: url('http://img.combats.ru/i/premium/buypage/3-hover.png');
}
table.premium_table tr.icons div.icon_4 {
background-image: url('http://img.combats.ru/i/premium/buypage/4.png');
-webkit-transition: all 0.2s ease-out;
transition: all 0.2s ease-out;
}
table.premium_table tr.icons td.hover div.icon_4 {
background-image: url('http://img.combats.ru/i/premium/buypage/4-hover.png');
}
table.premium_table tr.icons div.icon_5 {
background-image: url('http://img.combats.ru/i/premium/buypage/5.png');
-webkit-transition: all 0.2s ease-out;
transition: all 0.2s ease-out;
}
table.premium_table tr.icons td.hover div.icon_5 {
background-image: url('http://img.combats.ru/i/premium/buypage/5-hover.png');
}
table.premium_table tr.hover td, table.premium_table td.hover {
background-color: rgba(189,144,139,.3);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#48BD908B', endColorstr='#48BD908B');
}
table.premium_table tr td.delimiter {
width: 6px;
border-bottom: 1px solid #48342f;
background-color: transparent;
filter: none;
}
table.premium_table tr.no-bg td {
background: none;
border: none;
filter: none;
}
table.premium_table tr.round-corners td {
border-bottom: none;
-webkit-border-bottom-right-radius: 5px;
-webkit-border-bottom-left-radius: 5px;
-moz-border-radius-bottomright: 5px;
-moz-border-radius-bottomleft: 5px;
border-bottom-right-radius: 5px;
border-bottom-left-radius: 5px;
border-radius: 0px 0px 5px 5px;
}
table.premium_table tr td.bank-status {
border-bottom: 1px solid #48342f;
position: relative;
}
table.premium_table tr td.bank-status > span {
padding-left: 20px;
display: inline-block;
position: absolute;
top: -32px;
left: 0px;
width: 90%;
height: 120px;
}
table.premium_table tr td.bank-status > span p {
margin: 0 0 12px;
font-size: 16px;
}
table.premium_table tr td.bank-status > span  span {
display: block;
}
table.premium_table tr td.bank-status .left {
width: 150px;
text-align: left;
z-index: 1;
position: relative;
}
table.premium_table tr td.bank-status .bill-number {
font-family: "Linux Biolinum", Arial, Helvetica, sans-serif;
font-size: 20px;
}
table.premium_table tr td.bank-status .add-button {
font-family: "Linux Biolinum", Arial, Helvetica, sans-serif;
font-size: 12px;
cursor: pointer;
text-align: center;
width: 120px;
height: 30px;
margin-top: 12px;
line-height: 2.6;
border: 1px solid #8e8078;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
text-transform: uppercase;
-webkit-transition: all 0.3s ease-out;
transition: all 0.3s ease-out;
background-color: rgba(189,144,139,.3);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#48bd908b', endColorstr='#48bd908b');
}
table.premium_table tr td.bank-status .add-button:hover {
background-color: rgba(189,144,139,.5);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#80bd908b', endColorstr='#80bd908b');
}
table.premium_table tr td.bank-status .right {
text-align: right;
position: relative;
top: -74px;
}
table.premium_table tr td.bank-status .kredits {
font-size: 30px;
font-family: "Linux Biolinum", Arial, Helvetica, sans-serif;
}
table.premium_table select, .wrapper select {
font-size: 12px;
color: #f7e2d0;
cursor: pointer;
text-align: center;
-webkit-appearance: none;
vertical-align: middle;
width: 120px;
height: 30px;
padding-left: 34px;
background-color: #3a2724;
border: 1px solid #51423c;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
}
.select-wrapper {
width: 120px;
display: inline-block;
position: relative;
}
.select-wrapper:after {
content: '';
cursor: pointer;
pointer-events: none;
width: 9px;
height: 7px;
display: block;
position: absolute;
right: 9px;
top: 12px;
background: url('http://img.combats.ru/i/premium/buypage/select-arrow.png') 50% 50% no-repeat;
}
span.info {
width: 16px;
height: 16px;
display: inline-block;
background: url('http://img.combats.ru/i/premium/buypage/info.png') 50% 50% no-repeat;
cursor: pointer;
margin-left: 10px;
position: relative;
top: 3px;
}
span.info:hover {
background-image: url('http://img.combats.ru/i/premium/buypage/info-hover.png');
}
.footer {
height: 150px;
color: #f7e2d0;
padding-top: 40px;
}
.footer > p {
display: block;
margin: 52px 0 14px;
}
.footer .footer-info {
font-size: 14px;
text-align: left;
padding: 0 120px;
margin-bottom: 48px;
}
.footer .footer-info p {
margin: 12px 0;
}
.footer .copyright {
font-size: 12px;
position: absolute;
bottom: 0;
left: 40%;
}
.footer .copyright a {
color: #f7e2d0;
text-decoration: none;
}
.submit-wrapper {
position: relative;
height: 48px;
}
.auth-button {
display: inline-block;
border: 0;
-webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px;
background: url('http://img.combats.ru/i/premium/buypage/button-auth.png') no-repeat center center;
width: 200px;
height: 60px;
cursor: pointer;
-webkit-transition: all 0.3s ease-out;
transition: all 0.3s ease-out;
}
.auth-button:hover {
background-image: url('http://img.combats.ru/i/premium/buypage/button-auth_hover.png');
}
.buy_button {
border: 0;
-webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px;
background: url('http://img.combats.ru/i/premium/buypage/button-buy.png') no-repeat center top;
width: 140px;
height: 60px;
cursor: pointer;
position: absolute;
top: 0;
left: -9px;
-webkit-transition: all 0.3s ease-out;
transition: all 0.3s ease-out;
}
.buy_button:hover {
background-image: url('http://img.combats.ru/i/premium/buypage/button-buy_hover.png');
}
.number_view {
text-align: center;
font-weight: bold;
font-family: "Linux Biolinum", Arial, Helvetica, sans-serif;
font-size: 18px;
color: #f3d4b4;
}
.number_price {
text-align: center;
font-weight: bold;
font-family: "Linux Biolinum", Arial, Helvetica, sans-serif;
font-size: 18px;
color: #f3d4b4;
}
.prodlit_button {
cursor: pointer;
font-family: "Linux Biolinum", Arial, Helvetica, sans-serif;
text-align: center;
overflow: visible;
margin-right: 8px;
color: #e9d8c9;
width: 120px;
height: 30px;
line-height: 0.4;
border: 1px solid #8e8078;
-webkit-appearance: none;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
text-transform: uppercase;
-webkit-transition: all 0.3s ease-out;
transition: all 0.3s ease-out;
background-color: transparent;
background-color: rgba(189,144,139,.3);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#48bd908b', endColorstr='#48bd908b');
}
.prodlit_button:hover {
background-color: transparent;
background-color: rgba(189,144,139,.5);
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#80bd908b', endColorstr='#80bd908b');
}
.podarit_button {
border: 0;
display: inline-block;
-webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px;
background: url('http://img.combats.ru/i/premium/buypage/button-gift.png') no-repeat center center;
width: 240px;
height: 60px;
cursor: pointer;
-webkit-transition: all 0.3s ease-out;
transition: all 0.3s ease-out;
}
.podarit_button:hover {
background-image: url('http://img.combats.ru/i/premium/buypage/button-gift_hover.png');
}
td[onclick="closehint3();"] {
display: none;
}
input[type=submit]:focus { outline:none; }
input[type=submit]::-moz-focus-inner { border:0; }
</style>
</head>
<body>
<script>
var level_prices = [['2.00','5.00','50.00'],['4.00','9.00','90.00'],['7.00','25.00','250.00'],['15.00','49.00','490.00'],['30.00','99.00','990.00'],[]];
var level_durs   = [['7 дней','30 дней','1 год'],['7 дней','30 дней','1 год'],['7 дней','30 дней','1 год'],['7 дней','30 дней','1 год'],['7 дней','30 дней','1 год'],[]];
function change_dur_price(name, level, dur) {
var el = document.getElementById(name);
if (el) {
change_text(el, level_prices[Number(level)-1][dur]);
}
}
function remove_child_elements(el) {
while (el.firstChild) {
el.removeChild(el.firstChild);
}
}
function change_text(el, txt) {
remove_child_elements(el);
el.appendChild(document.createTextNode(txt));
}
function mconfirm(lvl, old_lvl, name) {
var txt;
var durel = document.getElementById(name);
var period;
var dur;
if (durel) {
dur = durel.value;
}
lvl = Number(lvl);
old_lvl = Number(old_lvl);
var price = level_prices[lvl-1][dur];
var period = level_durs[lvl-1][dur];
if (old_lvl && (old_lvl < lvl)) {
txt =
"У вас уже есть Благодать Алхимика "+old_lvl+". "
+"Вы уверены, что хотите купить Благодать Алхимика "+lvl+' на '+period+" за "+price+" екр? "
+"Ваша текущая Благодать Алхимика будет запомнена и продолжится после истечения срока новой.";
}
if (old_lvl && (old_lvl == lvl)) {
txt =
"Вы уверены, что хотите продлить Благодать Алхимика "+lvl+' на '+period+" за "+price+" екр?";
}
if (old_lvl && (old_lvl > lvl)) {
alert('Нельзя понизить Благодать Алхимика');
return false;
}
if (!old_lvl || (old_lvl == 0)) {
txt =
"Вы уверены, что хотите купить Благодать Алхимика "
+lvl+' на '+period+" за "+price+" екр?";
}
return confirm(txt);
}
function set_price() {
var level_select = document.getElementById('gift_level');
var span_el = document.getElementById('gift_price');
var dur_select = document.getElementById('gift_dur');
if (level_select && span_el && dur_select) {
change_text(span_el,
level_prices[level_select.value-1][dur_select.value]);
}
}
function set_durs() {
var level_select = document.getElementById('gift_level');
var span_el = document.getElementById('gift_price');
var dur_select = document.getElementById('gift_dur');
if (level_select && span_el && dur_select) {
var level = level_select.value;
change_text(span_el, level_prices[level-1][0]);
var dur_options = '';
remove_child_elements(dur_select);
for (var j=0; j<level_durs[level-1].length; j++) {
var opt = document.createElement('OPTION');
opt.value = j;
var txt = document.createTextNode(level_durs[level-1][j]);
opt.appendChild(txt);
dur_select.appendChild(opt);
}
}
}
function checklogin() {
var el = document.getElementById('gift_name');
if (el) {
var login = el.value;
if (login == '') {
alert('Введите имя получателя');
return false;
}
}
return true;
}
function make_gift() {
var el = document.getElementById('hint4');
if (!el) {
return;
}
var level_options = '';
var s =
'<input type="hidden" name="a" value="gift">'
+'<table>'
+'<tr><td>Выберите ранг: </td>'
+    '<td><select id="gift_level" name="level" onchange="set_durs();">'
for (var i = 0; i < level_prices.length-1; i++) {
level_options= level_options+'<option value="'+(i+1)+'">Ранг '+(i+1)+'</option>';
}
s=s+level_options+'</select></td></tr>';
s = s
+ '<tr><td>Выберите срок: </td>'
+     '<td><select id="gift_dur" name="dur" onchange="set_price();"></select></td></tr>';
s = s
+'<tr><td>Имя получателя: </td><td> <input id="gift_name" type="text" name="receiver" value=""></td></tr>'
+'<tr><td>Стоимость:</td><td><span id="gift_price">'+level_prices[0][0]+'</span> екр</td></tr>'
+'<tr><td colspan="2"><input type="submit" onclick="return checklogin();" value="Подарить Благодать Алхимика"></td></tr>'
+'</table>';
s =
'<form method="POST">'
+crtmagic('', 'Подарить', s, '', false)
+'</form>';
el.innerHTML = s;
el.style.visibility = 'visible';
el.style.zIndex = 200;
var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth
el.style.left = (width/2 - 120)+'px';
el.style.top  = (document.body.scrollTop+300)+'px';
set_durs();
}
$(document).ready(function() {
var table = $('.premium_table')[0],
rows = $('.premium_table .hoverable');
rows.on('mouseover', function (e) {
var cell = e.target.tagName == 'TD' ? $(e.target) : $(e.target).closest('td');
if (cell.parent().hasClass('hoverable')) {
var cellIndex = cell[0].cellIndex,
rowIndex = cell.parent()[0].rowIndex;
if (!cell.parent().hasClass('icons')) {
table.rows[rowIndex].className += ' hover';
}
for (var i = 0; i < table.rows.length; i++) {
if ($(table.rows[i]).hasClass('hoverable')) {
$(table.rows[i].cells[cellIndex]).addClass('hover');
}
}
}
});
rows.on('mouseout', function () {
$('.premium_table .hoverable, .premium_table .hoverable td').removeClass('hover');
});
$('.auth-button').fancybox({
closeBtn: false,
padding: 0
});
$('.podarit-fancybox').fancybox({
closeBtn: false,
padding: 0
});

var date = new Date(new Date().getTime() + 2372942*1000),
day = ('0' + date.getDate()).slice(-2),
month = ('0' + (date.getMonth() + 1)).slice(-2),
year = ('' + date.getFullYear()).slice(-2),
hours = ('0' + date.getHours()).slice(-2),
minutes = ('0' + date.getMinutes()).slice(-2);
var text = day + '.' + month + '.' + year + ' ' + hours + ':' + minutes;
$('.date').text(text);
});
</script>
<div style="position: absolute; left: -9999px; top: -9999px; visibility: hidden;">
<div id="hint4" class="ahint"></div>
</div>
<div class="wrapper" align="center">
<div class="header">
<div class="account-status">
<span style="width: 30%; text-align: left;">
<b>Активные Благодати Алхимика:</b>
</span>
<span style="width: 30%;">
Следующий пробный премиум будет доступен <span class="date"></span>
</span>
<span style="width: 32%;">
<p>Вы можете попробовать Благодать 3 ранга бесплатно только 1 раз в 30 дней.</p>
</span>

</div>
</div>
<table class="premium_table" cellspacing="0" cellpadding="0">
<tr></tr>
<tr class="icons hoverable">
<td class="bank-status">
<span>
<div id="hint4" style="visibility:hidden"></div>


<p style="text-align: left">Состояние счета:</p>
<span class="left">
<span class="bill-number">№ 3991930370</span>
<a href="/buy.pl" target="_blank"><span class="add-button">Пополнить</span></a>
</span>
<span class="right">
<span class="kredits"><b>0.00 кр</b></span>
<span class="kredits"><b>0.17 екр</b></span>
</span>


</span>
</td>
<td><div class="icon_1"></div></td>
<td class="delimiter"></td>
<td><div class="icon_2"></div></td>
<td class="delimiter"></td>
<td><div class="icon_3"></div></td>
<td class="delimiter"></td>
<td><div class="icon_4"></div></td>
<td class="delimiter"></td>
<td><div class="icon_5"></div></td>
<td class="delimiter" style="width: 20px;"></td>
</tr>
<tr class="hoverable">
<td align="left">Скорость передвижения <b class="number_view">+20%</b></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Скорость восстановления Здоровья и Маны <b class="number_view">+50%</b></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Ускоренное получение репутации Ангелов <b class="number_view">+100%</b></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Нет ослабления после боя</td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Бонус к получаемому опыту <b class="number_view">+50%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Бонус к получаемой репутации в подземельях <b class="number_view">+50%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Задержка на посещение подземелий <b class="number_view">-30%</b><br><b class="number_view">+1</b> дополнительный поход в сутки <span  onmouseout='CombatsUI.HideTooltip();' onmouseover='CombatsUI.ShowTooltip("Для подземелий, где ограничение по количеству походов в сутки",event)'><span class="info"></span></span></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Дополнительный <b class="number_view">бросок</b> вероятности на выпадение дропа в подземельях <span  onmouseout='CombatsUI.HideTooltip();' onmouseover='CombatsUI.ShowTooltip("Касается дропа с убийства ботов и некоторых сундуков и объектов. После убийства бота, проверятся шанс на выпадение дропа и независимо от результата(выпал дроп или нет), делается дополнительный бросок, таким образом можно получить двойной дроп с бота. Так же это распространяется и на некоторые сундуки и объекты. Обратите внимание, что требуемые вещи на обмен могут быть так же взяты в двойном размере.<br><br><b>Примеры:</b><br>У бота вероятность выпадения дропа - 100%: вы получите двойной дроп.<br>У бота вероятность выпадения 50%: вы можете получить двойной дроп, одинарный дроп или вообще ничего.",event)'><span class="info"></span></span></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Уменьшение задержки на телепортацию между городами на <b class="number_view">50%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Экипировка <b class="number_view">не ломается</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Дополнительный слот сумки</td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Комиссия на аукционе <b class="number_view">2.5%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Скидка на ремонт <b class="number_view">50%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Бонус к получаемому благородству <b class="number_view">+50%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="hoverable">
<td align="left">Бонус к получаемому клановому опыту <b class="number_view">+50%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="round-corners hoverable">
<td align="left">Скидка в магазинах при покупке за еврокредиты <b class="number_view">+5%</b></td>
<td class="empty">.</td>
<td class="delimiter"></td>

<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="empty">.</td>
<td class="delimiter"></td>
<td class="ok">.</td>
<td class="delimiter"></td>
</tr>
<tr class="no-bg">
<td align="right"></td>

<td><span id="price1" class="number_price">2.00</span> <b class="number_price">екр</b></td>
<td class="delimiter"></td>

<td><span id="price2" class="number_price">4.00</span> <b class="number_price">екр</b></td>
<td class="delimiter"></td>

<td><span id="price3" class="number_price">7.00</span> <b class="number_price">екр</b></td>
<td class="delimiter"></td>

<td><span id="price4" class="number_price">15.00</span> <b class="number_price">екр</b></td>
<td class="delimiter"></td>

<td><span id="price5" class="number_price">30.00</span> <b class="number_price">екр</b></td>
<td class="delimiter"></td>
</tr>
<tr class="duration no-bg">
<td align="right" valign="top"></td>

<td>
<form method="post">
<div class="select-wrapper">
<select name="dur" id="dur1" onchange="change_dur_price('price1', '1', this.value)">

<option value="0">7 дней</option>

<option value="1">30 дней</option>

<option value="2">1 год</option>
</select>
</div>
<input type="hidden" name="a" value="buy">
<input type="hidden" name="level" value="1">
<div class="submit-wrapper">
<input type="submit" class="buy_button" value="" onclick="return mconfirm('1', '0', 'dur1');">
</div>
</form>
</td>
<td class="delimiter"></td>

<td>
<form method="post">
<div class="select-wrapper">
<select name="dur" id="dur2" onchange="change_dur_price('price2', '2', this.value)">

<option value="0">7 дней</option>

<option value="1">30 дней</option>

<option value="2">1 год</option>
</select>
</div>
<input type="hidden" name="a" value="buy">
<input type="hidden" name="level" value="2">
<div class="submit-wrapper">
<input type="submit" class="buy_button" value="" onclick="return mconfirm('2', '0', 'dur2');">
</div>
</form>
</td>
<td class="delimiter"></td>

<td>
<form method="post">
<div class="select-wrapper">
<select name="dur" id="dur3" onchange="change_dur_price('price3', '3', this.value)">

<option value="0">7 дней</option>

<option value="1">30 дней</option>

<option value="2">1 год</option>
</select>
</div>
<input type="hidden" name="a" value="buy">
<input type="hidden" name="level" value="3">
<div class="submit-wrapper">
<input type="submit" class="buy_button" value="" onclick="return mconfirm('3', '0', 'dur3');">
</div>
</form>
</td>
<td class="delimiter"></td>

<td>
<form method="post">
<div class="select-wrapper">
<select name="dur" id="dur4" onchange="change_dur_price('price4', '4', this.value)">

<option value="0">7 дней</option>

<option value="1">30 дней</option>

<option value="2">1 год</option>
</select>
</div>
<input type="hidden" name="a" value="buy">
<input type="hidden" name="level" value="4">
<div class="submit-wrapper">
<input type="submit" class="buy_button" value="" onclick="return mconfirm('4', '0', 'dur4');">
</div>
</form>
</td>
<td class="delimiter"></td>

<td>
<form method="post">
<div class="select-wrapper">
<select name="dur" id="dur5" onchange="change_dur_price('price5', '5', this.value)">

<option value="0">7 дней</option>

<option value="1">30 дней</option>

<option value="2">1 год</option>
</select>
</div>
<input type="hidden" name="a" value="buy">
<input type="hidden" name="level" value="5">
<div class="submit-wrapper">
<input type="submit" class="buy_button" value="" onclick="return mconfirm('5', '0', 'dur5');">
</div>
</form>
</td>
<td class="delimiter"></td>
</tr>
</table>
<div class="footer">
<div class="footer-info">
<p>Кроме того, в красивую табличку не уместились:</p>
<p>- каждый ранг благодати уменьшает разброс получаемых параметров на выплавляемых рунах, что позволяет получить более качественный результат (на V ранге получается 4 параметра);</p>
<p>- обладателям благодати алхимика доступен особый прилавок в магазине Березка, там можно приобрести экипировку на очень выгодных условиях (5-9 уровень предметов для II+ ранга благодати, 10 уровень предметов для III+);</p>
<p>- чем выше ранг благодати тем больший приоритет ваш персонаж имеет, находясь в очереди в Бои Чести.</p>
</div>
<p>Любой из Рангов Благодати Алхимика, вы можете подарить своему другу, купив его в магазине Березка.</p>
<p class="copyright">&copy; 2002 - 2015, &laquo;<A href="http://www.combats.com" target="_blank">www.Combats.com</A>&raquo;&trade;<BR>All rights reserved</p>
</div>
</div>
<br><br>
<DIV id="counters_block"><script type="text/javascript">(function (d, w, c) {(w[c] = w[c] || []).push(function() {try {w.yaCounter27900837 = new Ya.Metrika({id:27900837});} catch(e) { }}); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }})(document, window, "yandex_metrika_callbacks");</script><script>(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","//www.google-analytics.com/analytics.js","ga");ga("create", "UA-58541122-1", "auto");ga("send", "pageview");</script></div>
</body>
</html>