<?php
if(!defined('GAME'))
{
 die();
}

$qst_hram = mysql_fetch_array(mysql_query('SELECT * FROM `dialog_act` WHERE `uid` = "'.$u->info['id'].'" AND `var` = "qsthram1" AND `val` = 1 LIMIT 1'));
if(isset($qst_hram['id'])) {
	$qst_hram = true;
}else{
	$qst_hram = false;
	unset($_GET['r']);
}

if($u->room['file']=='ab/hram')
{
	if(isset($_GET['itm']) && $qst_hram == true)
	{
		
		if($_GET['itm']>0)
		{
			if($_GET['r']==1)
			{	
				$_GET['itm'] = (int)$_GET['itm'];
				//Переплавка вещей 
				$resz = $u->plavka($_GET['itm'],1);
				$re = '<font color=red><b>'.$resz.'</b></font>';
				unset($resz);
			}elseif( $_GET['r'] == 2 ){
				//Переплавка рун
				$resz = '';
				
				$itm123 = explode('x',$_GET['itm']);
				
				$itm1 = round((int)$itm123[0]);
				$itm2 = round((int)$itm123[1]);
				$itm3 = round((int)$itm123[2]);
				
				
				$itm1 = mysql_fetch_array(mysql_query('SELECT `a`.`id` AS `iid`,`a`.*,`b`.* FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON (`b`.`id` = `a`.`item_id` AND `b`.`type` = 31) WHERE `a`.`id` = "'.mysql_real_escape_string($itm1).'" AND `a`.`uid` = "'.$u->info['id'].'" AND `a`.`delete` = 0 AND `a`.`inShop` = 0 AND `a`.`inTransfer` = 0 LIMIT 1'));
				$itm2 = mysql_fetch_array(mysql_query('SELECT `a`.`id` AS `iid`,`a`.*,`b`.* FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON (`b`.`id` = `a`.`item_id` AND `b`.`type` = 31) WHERE `a`.`id` = "'.mysql_real_escape_string($itm2).'" AND `a`.`uid` = "'.$u->info['id'].'" AND `a`.`delete` = 0 AND `a`.`inShop` = 0 AND `a`.`inTransfer` = 0 LIMIT 1'));
				$itm3 = mysql_fetch_array(mysql_query('SELECT `a`.`id` AS `iid`,`a`.*,`b`.* FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON (`b`.`id` = `a`.`item_id` AND `b`.`type` = 31) WHERE `a`.`id` = "'.mysql_real_escape_string($itm3).'" AND `a`.`uid` = "'.$u->info['id'].'" AND `a`.`delete` = 0 AND `a`.`inShop` = 0 AND `a`.`inTransfer` = 0 LIMIT 1'));
				
				if($itm1['iid'] == $itm2['iid'] || $itm2['iid'] == $itm3['iid'] || $itm1['iid'] == $itm3['iid'] ) {
					$resz = 'Не удалось расплавить одну руну, она сгорела.';
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm3['iid'].'" OR `id` = "'.$itm2['iid'].'" OR `id` = "'.$itm1['iid'].'"');
				}elseif(!isset($itm1['id']) || !isset($itm2['id']) || !isset($itm3['id'])) {
					$resz = 'Недостаточно компонентов для преобразования.';
				}elseif( $itm1['level'] != $itm2['level'] || $itm1['level'] != $itm3['level'] ) {
					$resz = 'Руны должны быть одного уровня.';
				}elseif( $itm1['level'] == 1 ) {
					$resz = 'Унируны нельзя преобразовывать.';
				}else{
					$itm4 = array();
					$sp = mysql_query('SELECT * FROM `items_main` WHERE `level` = "'.$itm1['level'].'" AND `type` = 31
					AND `id` != "'.$itm1['item_id'].'" AND `id` != "'.$itm2['item_id'].'" AND `id` != "'.$itm3['item_id'].'"');
					while( $pl = mysql_fetch_array($sp) ) {
						$itm4[] = $pl;
					}
					$itm4 = $itm4[rand(0,count($itm4)-1)];
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm3['iid'].'" OR `id` = "'.$itm2['iid'].'" OR `id` = "'.$itm1['iid'].'"');
					$u->rep['rep1'] += 1;
					mysql_query('UPDATE `rep` SET `rep1` = "'.$u->rep['rep1'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->addItem($itm4['id'],$u->info['id']);
					$resz = 'Удачно преобразованы руны &quot;'.$itm1['name'].'&quot;,&quot;'.$itm2['name'].'&quot; и &quot;'.$itm3['name'].'&quot; в &quot;'.$itm4['name'].'&quot;.';
					$resz .= '<Br>Добавлена репутация Храма Знаний +1';
				}
				
				$re = '<font color=red><b>'.$resz.'</b></font>';
				unset($resz);
			}
		}
	}
?>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}
	
	</style>
	<div id="hint3" style="visibility:hidden"></div>
	<? if(isset($_GET['r'])) { ?>
    <TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top">
    <div align="center" class="pH3">Храм Знаний <? if($_GET['r']==2){ echo ', Алтарь рун'; }elseif($_GET['r']==1){ echo ', Алтарь предметов'; } ?></div>
    <div align="left"><? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?></div>
	<td width="280" valign="top" align="right"><table align="right" cellpadding="0" cellspacing="0">
		<tr>
		  <td width="100%">&nbsp;</td>
		  <td><table  border="0" cellpadding="0" cellspacing="0">
			  <tr align="right" valign="top">
				<td><!-- -->
					<? echo $goLis; ?>
					<!-- -->
					<table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
							<tr>
							  <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
							  <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=3.180.0.267&rnd=<? echo $code; ?>';" title="<? thisInfRm('3.180.0.267',1); ?>">Центральная площадь</a></td>
							</tr>
						</table></td>
					  </tr>					  <tr>
						<td nowrap="nowrap">&nbsp;</td>
					  </tr>
				  </table></td>
			  </tr>
		  </table></td>
		</tr>
	  </table>
		<br /><br />
	  <input type="button" value="Обновить" onclick="location.href = '<? if(isset($_GET['r'])) { echo 'main.php?r='.floor($_GET['r']); }else{ echo 'main.php'; } ?>';" /><? if(isset($_GET['r'])){ ?> &nbsp; <input type="button" value="Вернуться" onclick="location.href = 'main.php';" /><? } ?>
	</td>
	</table>
	<div id="textgo" style="visibility:hidden;"></div>
    <?
    } 	
	if(!isset($_GET['r'])) {
		//Диалоговый перс
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="250" valign="top"><? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; }  ?></td>
        <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
        <td valign="top"><div align="right">
          <table  border="0" cellpadding="0" cellspacing="0">
            <tr align="right" valign="top">
              <td><? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><div style="position:relative; cursor: pointer;" id="ione"> <img src="http://img.xcombats.com/city/ap_bg13_1.jpg" alt="" name="img_ione" width="500" height="268" border="1" id="img_ione"/>
                      <div id="buttons_on_image" style="cursor:pointer; font-weight:bold; color:#D8D8D8; font-size:10px;">&nbsp; <span onmousemove="this.runtimeStyle.color = 'white';" onmouseout="this.runtimeStyle.color = this.parentElement.style.color;" onclick="window.open('http://xcombats.com/forum', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')">Форум</span> &nbsp; </div>
                      <script language="javascript" type="text/javascript">
                        <!--
                        if(document.getElementById('ione'))
                        {
                            document.getElementById('ione').appendChild(document.getElementById('buttons_on_image'));
                            document.getElementById('buttons_on_image').style.position = 'absolute';
                            document.getElementById('buttons_on_image').style.bottom = '8px';
                            document.getElementById('buttons_on_image').style.right = '23px';
                        }else{
                            document.getElementById('buttons_on_image').style.display = 'none';
                        }
                        -->
                        </script>
                      <div style="position: absolute; left: 437px; top: 96px; width: 48px; height: 33px; z-index: 94;"><img <? thisInfRm('3.180.0.267'); ?> src="http://img.xcombats.com/city/ap_exit.gif" width="57" height="26" class="aFilter" /></div>
                      <div style="position: absolute; left: 191px; top: 12px; width: 75px; height: 68px; z-index: 94;"><img onclick="location.href='main.php?talk=11'" src="http://img.xcombats.com/city/1269_igsetee.png" width="120" height="220" class="aFilter" title="Диалог с Арквиерро" /></div>
                      <div style="position: absolute; left: 30px; top: 127px; width: 48px; height: 33px; z-index: 94;"><img <? if($qst_hram==false){ echo 'onclick="alert(\'Арквиерро: Вы не допущены к котлам! (Выполните задание)\');"'; }else{ ?> onclick="location.href='main.php?r=1'" <? } ?> title="Алтарь Предметов" src="http://img.xcombats.com/city/ap_altar1.gif" width="147" height="93" class="aFilter" /></div>
                      <div style="position: absolute; left: 333px; top: 129px; width: 48px; height: 33px; z-index: 94;"><img <? if($qst_hram==false){ echo 'onclick="alert(\'Арквиерро: Вы не допущены к котлам! (Выполните задание)\');"'; }else{ ?> onclick="location.href='main.php?r=2'" <? } ?> title="Алтарь Рун" src="http://img.xcombats.com/city/ap_altar2.gif" width="147" height="93" class="aFilter" /></div>
                      <div style="position: absolute; left: 437px; top: 96px; width: 48px; height: 33px; z-index: 94;"><img <? thisInfRm('3.180.0.267'); ?> src="http://img.xcombats.com/city/ap_exit.gif" width="57" height="26" class="aFilter" /></div>
                      
                      
                      <div id="snow"></div>
                    <? echo $goline; ?> </div></td>
                  </tr>
                </table>
                <div style="display:none; height:0px " id="moveto"></div>
                <div align="right" style="padding: 3px;"><small>&laquo;<? echo $c['title3']; ?>&raquo; приветствует Вас, <b><? echo $u->info['login']; ?></b>. Вы находить в Храме Знаний Abandoned Plain.<br />
                </small></div></td>
              <td><!-- <br /><span class="menutop"><nobr>Комната для новичков</nobr></span>--></td>
            </tr>
          </table>
          <small>
            <hr />
            <? $hgo = $u->testHome(); if(!isset($hgo['id'])){ ?>
            <input onclick="location.href='main.php?homeworld=<? echo $code; ?>';" class="btn" value="Возврат" type="button" name="combats2" />
            <? } unset($hgo); ?>
            <input id="forum" class="btn" onclick="window.open('http://xcombats.com/forum/', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')" value="Форум" type="button" name="forum" />
            <input class="btn" onclick="window.open('/encicl/help/top1.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')" value="Подсказка" type="button" />
            <input class="btn" value="Объекты" type="button" />
            <br />
            <strong>Внимание!</strong> Никогда и никому не говорите пароль от своего персонажа. Не вводите пароль на других сайтах, типа &quot;новый город&quot;, &quot;лотерея&quot;, &quot;там, где все дают на халяву&quot;. Пароль не нужен ни паладинам, ни кланам, ни администрации, <U>только взломщикам</U> для кражи вашего героя.<br />
            <em>Администрация.</em></small> <br />
          <? echo $rowonmax; ?><br />
        </div></td>
      </tr>
    </table>
    <?
	}elseif($_GET['r']==1){
	$itmAll = ''; $itmAllSee = '';
	$itmAll = $u->genInv(11,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD` DESC');
	if($itmAll[0]==0){
		$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО (нет подходящих предметов)</td></tr>';
	}else{
		$itmAllSee = $itmAll[2];
	}
	//Удачно растворен предмет "Укрепленный Костыль". Получена руна "Моно Бауни".
	?>
    <script>
	function takeItRun(img,id,vl)
	{
		if(id!=urlras)
		{
			urlras = id;
			document.getElementById('use_item').innerHTML = '<img src="http://<?=$c['img'];?>/i/items/'+img+'" title="Предмет для переплавки"/><br><a href="javascript:void(0);" onClick="cancelItRun()">Отменить</a>';
			document.getElementById('add_rep').innerHTML = ' + '+vl;
		}else{
			cancelItRun();
		}
	}
	function cancelItRun()
	{
		urlras = 0;
		document.getElementById('use_item').innerHTML = 'Предмет не выбран';
		document.getElementById('add_rep').innerHTML = '';
	}
	urlras = 0;
    </script>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
   	  <tr>
   	    <td align="center" valign="top" bgcolor="#D6D6D6"><?=$u->microLogin($u->info['id'],1)?></td>
   	    <td align="center" valign="top" bgcolor="#D6D6D6">Подходящие предметы в инвентаре</td>
      </tr>
      <tr>
    	    <td width="300" valign="top">
            <b>Репутация: <? echo 0+$u->rep['rep1']; ?></b><span id="add_rep"></span>
            <br /><br /><center><span id="use_item">Предмет не выбран</span><br /><br />
            <input type="button" value="Растворить" onclick="location = '?r=<?=$_GET['r'].'&rnd='.$code.'&itm=';?>'+urlras;" /></center>
            <br />
            <br /><small>
            <font color="red">Внимание!</font><br />
			Предметы при растворении и руны при слиянии необратимо теряются.</small>
            </td>
    	    <td valign="top">
            <!-- -->
            <table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">
            <? if($u->info['invBlock']==0){ echo $itmAllSee; }else{ echo '<div align="center" style="padding:10px;background-color:#A5A5A5;"><form method="post" action="main.php?inv=1&otdel='.$_GET['otdel'].'&relockinvent"><b>Рюкзак закрыт.</b><br><img title="Замок для рюкзака" src="http://img.xcombats.com/i/items/box_lock.gif"> Введите пароль: <input id="relockInv" name="relockInv" type="password"><input type="submit" value="Открыть"></form></div>'; } ?>
            </table>
            <!-- -->
            </td>
      </tr>
    </table>
<? }elseif( $_GET['r'] == 2 ) { 
	$itmAll = ''; $itmAllSee = '';
	$itmAll = $u->genInv(14,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `im`.`type` = 31 ORDER BY `lastUPD` DESC');
	if($itmAll[0]==0){
		$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО (нет подходящих предметов)</td></tr>';
	}else{
		$itmAllSee = $itmAll[2];
	}
	//Удачно растворен предмет "Укрепленный Костыль". Получена руна "Моно Бауни".
	?>
    <script>
	function takeItRun(img,id,vl)
	{
		if(id!=urlras)
		{
			urlras = id;
			document.getElementById('use_item').innerHTML = '<img src="http://<?=$c['img'];?>/i/items/'+img+'" title="Предмет для переплавки"/><br><a href="javascript:void(0);" onClick="cancelItRun()">Отменить</a>';
		}else{
			cancelItRun();
		}
	}
	function cancelItRun()
	{
		urlras = 0;
		document.getElementById('use_item').innerHTML = 'Пусто';
		document.getElementById('add_rep').innerHTML = '';
	}
	urlras = 0;
	//
	function takeItRun2(img,id,vl)
	{
		if(id!=urlras2)
		{
			urlras2 = id;
			document.getElementById('use_item2').innerHTML = '<img src="http://<?=$c['img'];?>/i/items/'+img+'" title="Предмет для переплавки"/><br><a href="javascript:void(0);" onClick="cancelItRun2()">Отменить</a>';
		}else{
			cancelItRun2();
		}
	}
	function cancelItRun2()
	{
		urlras2 = 0;
		document.getElementById('use_item2').innerHTML = 'Пусто';
	}
	urlras2 = 0;
	//
	function takeItRun3(img,id,vl)
	{
		if(id!=urlras3)
		{
			urlras3 = id;
			document.getElementById('use_item3').innerHTML = '<img src="http://<?=$c['img'];?>/i/items/'+img+'" title="Предмет для переплавки"/><br><a href="javascript:void(0);" onClick="cancelItRun3()">Отменить</a>';
		}else{
			cancelItRun3();
		}
	}
	function cancelItRun3()
	{
		urlras3 = 0;
		document.getElementById('use_item3').innerHTML = 'Пусто';
	}
	urlras3 = 0;
	//
	function massTakeItRun(img,id,vl) {
			if( urlras == id ) {
				takeItRun(img,id,vl);
			}else if( urlras2 == id ) {
				takeItRun2(img,id,vl);
			}else if( urlras3 == id ) {
				takeItRun3(img,id,vl);
			}else if( urlras == 0 ) {
				takeItRun(img,id,vl);
			}else if( urlras2 == 0 ) {
				takeItRun2(img,id,vl);
			}else{
				takeItRun3(img,id,vl);
			}
	}
    </script>
    <table width="100%" border="0" cellspacing="3" cellpadding="0">
   	  <tr>
   	    <td align="center" valign="top" bgcolor="#D6D6D6"><?=$u->microLogin($u->info['id'],1)?></td>
   	    <td align="center" valign="top" bgcolor="#D6D6D6">Подходящие предметы в инвентаре</td>
      </tr>
      <tr>
    	    <td width="300" valign="top">
            <b>Репутация: <? echo 0+$u->rep['rep1']; ?></b><span id="add_rep"></span>
            <br /><br /><center>
            
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="80" align="center"><span id="use_item">Пусто</span></td>
                <td width="80" align="center"><span id="use_item2">Пусто</span></td>
                <td width="80" align="center"><span id="use_item3">Пусто</span></td>
              </tr>
            </table>
            
            <br /><br />
            <input type="button" value="Преобразовать" onclick="location.href = '?r=<?=$_GET['r'].'&rnd='.$code.'&itm=';?>'+urlras+'x'+urlras2+'x'+urlras3;" /></center>
            <br />
            <br /><small>
            <font color="red">Внимание!</font><br />
			Предметы при растворении и руны при слиянии необратимо теряются.</small>
            </td>
    	    <td valign="top">
            <!-- -->
            <table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">
            <? if($u->info['invBlock']==0){ echo $itmAllSee; }else{ echo '<div align="center" style="padding:10px;background-color:#A5A5A5;"><form method="post" action="main.php?inv=1&otdel='.$_GET['otdel'].'&relockinvent"><b>Рюкзак закрыт.</b><br><img title="Замок для рюкзака" src="http://img.xcombats.com/i/items/box_lock.gif"> Введите пароль: <input id="relockInv" name="relockInv" type="password"><input type="submit" value="Открыть"></form></div>'; } ?>
            </table>
            <!-- -->
            </td>
      </tr>
    </table>

<? } } ?>