<?

if( $u->info['admin'] == 0 ) {
	die('Продажа ЕКР отключена.');
}

if(!defined('GAME')) {
  die();
}


$crs = mysql_fetch_array(mysql_query('SELECT * FROM `bank_table` ORDER BY `time` DESC LIMIT 1'));

?>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
var nlevel = 0;
var from = Array('+', ' ', '#');
var to = Array('%2B', '+', '%23');

function w(login,id,align,klan,level,online,city,battle){
	var s='';
	if(online != "") {
		if (city!="") {
			s+='<img src=http://img.xcombats.com/1x1.gif width="20" height="15" alt="В другом городе" />';
		} else {
			s+='<a href="javascript:top.chat.addto(\''+login+'\',\'private\');"><img src=http://img.xcombats.com/i/lock.gif width="20" height="15" alt="Приватно" /></a>';
		}
		if (city!="") {
			s+='<img title="'+city+'" src="http://img.xcombats.com/i/city_ico/'+city+'.gif" width="17" height="15" />';
		}
		s+=' <img src="http://img.xcombats.com/i/align/align'+align+'.gif" width="12" height="15" />';

		if (klan!='') {s+='<a href="/encicl/klan/'+klan+'.html" target="_blank"><img src="http://img.xcombats.com/i/clan/'+klan+'.gif" width="24" height="15" /></a>'}
		s+='<a href="javascript:top.chat.addto(\''+login+'\',\'to\');">'+login+'</a>['+level+']<a href="http://xcombats.com/info/'+id+'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" width="12" height="11" alt="Информация о персонаже" /></a>';
		if (city!="") {
			s+=" - нет в этом городе";
		} else {
			s+=' - '+online;
		}
	} else {
		s+='<img src="http://img.xcombats.com/i/offline.gif" width="20" height="15" border="0" alt="Нет в клубе" />';
		if (city!="") {
			s+='<img title="'+city+'" src="http://img.xcombats.com/i/city_ico/'+city+'.gif" width="17" height="15" />';
		}
		if (align == "") align="0";
		s+=' <img src="http://img.xcombats.com/i/align/align'+align+'.gif" width="12" height="15" />';
		if (klan!='') {s+='<a href="http://<? echo $c['host']; ?>/encicl/clan/'+klan+'.html" target="_blank"><img src="http://img.xcombats.com/i/klan/'+klan+'.gif" width="24" height="15" /></a>'}
		if (level) {
			if (nlevel==0) {
				nlevel=1; s="<br />"+s;
			}
			s+='<font color=gray><b>'+login+'</b>['+level+']<a href="http://xcombats.com/info/'+id+'" target="_blank"><img src="http://img.xcombats.com/i/inf.gif" width="12" height="11" alt="Информация о персонаже" /></a> - Нет в клубе';
		} else {
			if (nlevel==1) {
				nlevel=2; s="<br />"+s;
			}
			mlogin = login;
			for(var i=0;i<from.length;++i) while(mlogin.indexOf(from[i])>=0)  mlogin= mlogin.replace(from[i],to[i]);
			s+='<font color=gray><i>'+login+'</i> <a href="http://xcombats.com/info/'+mlogin+'" target="_blank"><img src="http://img.xcombats.com/i/inf_.gif" width="12" height="11" alt="Информация о персонаже" /></a> - нет в этом городе';
		}
		s+='</font>';

	}
	document.write(s+'<br />');
}
</script>
<div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="160"></td>
      <td align="center"><h4>Покупка ЕвроКредитов</h4></td>
      <td width="160" align="right"><span style="vertical-align: top; text-align: right; ">
        <INPUT class="btnnew" type="button" value="Обновить" onclick="location='/main.php?alh&rnd=<?=$code?>'">
        <INPUT TYPE="button" value="Вернуться" class="btnnew" onclick='location="/main.php"'>
      </span></td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="520" align="left" valign="top">
             
        <fieldset>
        <legend><b><span style='color:#8F0000;'>Покупка ЕКР онлайн:</span></b></legend>
        <form target="_blank" method="post" id="ekrform" action="pay.back.php" onsubmit="if(document.getElementById('ch_1').checked==false) {alert('Вы не согласились с пользовательским соглашением.');return false;} else {if(document.getElementById('ch_2').checked==false) {alert('Вы не согласились с условиями оплаты.');return false;};}; if(document.getElementById('ekr').value<1) {alert('Нельзя купить менее 1 ЕКР!');return false;};">
            Сумма ЕКР: <input style="padding:3px;" type="text" name="ekr" id="ekr" value="10.00"> <input type="submit" class="btnnew" value="Пополнить баланс"><hr>
            
            <label><input type="checkbox" name="ch1" id="ch_1" /> Внимание! При пополнении баланса вы соглашаетесь с <small><a href="http://xcombats.com/" target="_blank">соглашением о предоставлении сервиса игры &laquo;Старый Бойцовский Клуб&raquo;</a></small>.</label>
            <br />
            <label><input type="checkbox" name="ch2" id="ch_2" /> Все комиссии платёжных систем Вы оплачиваете за свой счёт.</label>
		</form>
        <hr>
        При оплате могут возникать задержки на пополнение, обычно не более 1 часа. Если по истечению нескольких часов деньги так и не поступили на ваш баланс, то необходимо обратиться в <a href="http://www.free-kassa.ru/support.php" target="_blank">службу поддержки FREE-KASSA</a>.
        <hr>
        Если Вы очень хотели бы положить деньги на игровой счёт, но нет подходящей платёжной системы, можем посоветовать воспользоваться <a href="http://www.bestchange.ru" target="_blank">обменными пунктами</a>, либо воспользоваться услугами <b>Алхимиков</b>.
        <hr>
        <b>Пополняя свой игровой счёт, Вы тем самым спонсируете проект. ВСЕ ваши вложенные деньги в игру будут идти ТОЛЬКО на её же развитие.</b>
        
        </fieldset>
              
      </td>
      <td align="left" valign="top">
      
        <fieldset>
        <legend><b><span style='color:#8F0000;'>Ваш накопительный бонус:</span> 0% (0 ЕКР)</b></legend>
        Курс покупки ЕвроКредитов: <b>1</b> eкр. = <b>
        <?=round($crs['cur'],2)?>
        </b>руб.<br>
        Курс обмена ЕвроКредитов: <b>1</b> екр = <b>
        <?=$c['ecrtocr']?>
        </b> кр.<br>
        <?
        if($c['crtoecr']>0) {  
        ?>
        Курс обмена Кредитов:
        <?=$c['crtoecr']?>
        кр = 1 екр.
        <? } ?>
        </fieldset>
        <fieldset>
              <legend><b><span style='color:#8F0000;'>Курс еврокредита к мировой валюте</span></b></legend>
              <table width="100%" border="0" cellpadding="2" cellspacing="0">
                <?
			if(isset($pl['id'])) {
			?>
                <tr>
                  <td><small>Данные на <b>
                    <?=date('d.m.y H:i',$pl['time'])?>
                  </b> без учета комиссий</small></td>
                </tr>
                <?
				$pl['RUB'] = 1;
				
				$i = 0;
				$true = array(
					array('USD', 'долларов США'),
					array('EUR', 'ЕВРО'),
					array('RUB','российских рублей'),
					array('UAH','укр. гривен'),
					array('BYR','белорусских рублей'),
					array('AZN','азербайджанских манат'),
					array('GBP','англ. фунтов стерлингов')
				);
				while($i < count($true)) {
			?>
                <tr>
                  <td><span>1 екр. = </span><span style="display:inline-block;width:100px"><b>
                    <?=round( ($pl['cur']/$pl[$true[$i][0]]) , 4 )?>
                    </b></span><span>
                      <?=$true[$i][1]?>
                    </span></td>
                </tr>
                <?
					$i++;
				}
			}else{
			?>
                <tr>
                  <td><small>
                    <center>
                      <font color=grey>Не удалось получить информацию</font>
                    </center>
                  </small></td>
                </tr>
                <? } ?>
              </table>
            </fieldset>
            
       </td>
    </tr>
  </table>
</div>
<DIV>
  <? echo $c['counters']; ?>
</DIV> 
