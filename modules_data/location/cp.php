<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='cp') {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } ?>
    </td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right">
      <table  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td>
                <?
                if( $u->error != '' ) {
					if( $re != '' ) {
						$re .= '<br>';
					}
					$re .= $u->error;
				}
				if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    <div style="position:relative; cursor: pointer; width: 500px;" id="ione"><img src="http://img.xcombats.com/loca/cp11/cp_main<? if( date('H') >= 22 || date('H') < 6 ) { echo 'night'; } ?>.jpg" id="img_ione" width="500" height="268"  border="1"/>
                      <div style="position: absolute; left: 4px; right: 0px; z-index: 1500; top: 5px;">
                      	<div id="frvrks" style="position:relative;"></div>
                      </div>
                                            
                      <div style="position: absolute; left: 127px; top: 21px; width: 233px; height: 152px; z-index: 90;"><img <? thisInfRm('1.180.0.3'); ?> src="http://img.xcombats.com/loca/cp11/bk.png" width="233" height="152" title="" class="aFilter" /></div>
                      <div style="position: absolute; left: 100px; top: 156px; width: 92px; height: 58px; z-index: 90;"><img <? thisInfRm('1.180.0.10'); ?> src="http://img.xcombats.com/loca/cp11/shop.png" width="92" height="58" title="" class="aFilter" /></div>
                      <div style="position: absolute; left: 192px; top: 139px; width: 75px; height: 46px; z-index: 90;"><img <? thisInfRm('1.180.0.210'); ?> src="http://img.xcombats.com/loca/cp11/repair.png" width="75" height="46" title="" class="aFilter" /></div>
                      <div style="position: absolute; left: 308px; top: 150px; width: 110px; height: 76px; z-index: 90;"><img <? thisInfRm('1.180.0.13'); ?> src="http://img.xcombats.com/loca/cp11/berezka.png" width="110" height="76" title="" class="aFilter" /></div>
					  <div style="position: absolute; left: 29px; top: 149px; width: 70px; height: 46px; z-index: 90;"><img <? thisInfRm('1.180.0.272'); ?> src="http://img.xcombats.com/loca/cp11/comission.png" width="70" height="46" title="" class="aFilter" /></div>
                      <div style="position: absolute; left: 383px; top: 99px; width: 112px; height: 76px; z-index: 89;"><img <? thisInfRm('1.180.0.226'); ?> src="http://img.xcombats.com/loca/cp11/post.png" width="112" height="76" title="" class="aFilter" /></div>
                      <div style="position: absolute; left: 416px; top: 162px; width: 75px; height: 52px; z-index: 90;">
                        <img <? thisInfRm('1.180.0.371'); ?> src="http://img.xcombats.com/loca/cp11/loto_png.png" width="75" height="52" title="" class="aFilter" />
                      </div>
                      <div style="position: absolute; left: 453px; top: 195px; width: 29px; height: 41px; z-index: 91;"><img <? thisInfRm('1.180.0.11'); ?> src="http://img.xcombats.com/loca/cp11/arr_right.png" width="29" height="41" title="" class="aFilter" /></div>
                      <div style="position: absolute; left: 21px; top: 195px; width: 29px; height: 41px; z-index: 91;"><img <? thisInfRm('1.180.0.323'); ?> src="http://img.xcombats.com/loca/cp11/arr_left.png" width="29" height="41" title="" class="aFilter" /></div>
                      
                      <!-- -->
                      <?
					  //Праздничные здания
					  //if($u->info['admin'] > 0 ) {
						  //Хэллоуин
						  if( (date('m') == 11 && date('d') <= 6) || (date('m') == 10 && date('d') == 31) ) {
						?>
                        <div style="position: absolute; left: 230px; top: 162px; width: 32px; height: 43px; z-index: 91;"><img onclick="location.href='main.php?talk=5'" title="Диалог с Тыквоголовым" src="http://img.xcombats.com/loca/cp11/sun_pmd.gif" width="32" height="43" title="" class="aFilter" /></div>
                        <?  
						  }elseif( date('m') == 12 || date('m') == 1 ) {
							 //Елка НГ
						?>
						<div style="position: absolute; left: 212px; top: 133px; width: 32px; height: 43px; z-index: 91;"><img <? thisInfRm('1.180.0.208'); ?> src="http://img.xcombats.com/newyear2014.png" width="60" height="90" title="" class="aFilter" /></div>
						<?
                          }
					  //}
					  ?>
                      <!-- -->
                      
                      <div id="snow"></div>
                      <? echo $goline; ?>
                      <div id="buttons_on_image" style="cursor:pointer; position:absolute; bottom:5px; right:25px; font-weight:bold; color:#D8D8D8; font-size:10px;">
                     	 <?
						 if( date('H') >= 22 || date('H') < 6 ) {
							 echo '<a style="color:#D8D8D8" style="cursor:pointer" onclick="top.useMagic(\'Нападение на персонажа\',\'night_atack\',\'pal_button8.gif\',1,\'main.php?nightatack=1\');">Напасть</a> &nbsp; ';
						 }
						 ?>
                         <a style="color:#D8D8D8" href="http://xcombats.com/forum/" target="_blank">Форум</a>
                      </div>
                    </div>
                    </td>
                  </tr>
                </table>   
                <div style="display:none; height:0px " id="moveto"></div>     
              <div align="right" style="padding: 3px;"></div></td>
          <td>
              <!-- <br /><span class="menutop"><nobr>Комната для новичков</nobr></span>-->
          </td>
        </tr>
      </table>
      	<small>
        <?
		function testMonster( $mon , $type ) {
			$r = true;
			if(isset($mon['id'])) {
				//
				if($type == 'start') {
					//День недели
					if( $mon['start_day'] != -1 ) {
						if( ($mon['start_day'] < 7 && $mon['start_day'] != date('w')) || $mon['start_day'] != 7 ) {
							$r = false;
						}
					}
					//Число
					if( $mon['start_dd'] != -1 ) {
						if( $mon['start_dd'] != date('j') ) {
							$r = false;
						}
					}
					//месяц
					if( $mon['start_mm'] != -1 ) {
						if( $mon['start_mm'] != date('n') ) {
							$r = false;
						}
					}
					//час
					if( $mon['start_hh'] != -1 ) {
						if( $mon['start_hh'] != date('G') ) {
							$r = false;
						}
						if( $mon['start_min'] != -1 ) {
							if( $mon['start_min'] < (int)date('i') ) {
								$r = false;
							}
						}
					}
				}elseif($type == 'back') {
					//День недели
					if( $mon['back_day'] != -1 ) {
						if( ($mon['back_day'] < 7 && $mon['back_day'] != date('w')) || $mon['back_day'] != 7 ) {
							$r = false;
						}
					}
					//Число
					if( $mon['back_dd'] != -1 ) {
						if( $mon['back_dd'] != date('j') ) {
							$r = false;
						}
					}
					//месяц
					if( $mon['back_mm'] != -1 ) {
						if( $mon['back_mm'] != date('n') ) {
							$r = false;
						}
					}
					//час
					if( $mon['back_hh'] != -1 ) {
						if( $mon['back_hh'] != date('G') ) {
							$r = false;
						}
						if( $mon['back_min'] != -1 ) {
							if( $mon['back_min'] < (int)date('i') ) {
								$r = false;
							}
						}
					}
				}else{
					//что-то другое
					$r = false;
				}
				//
			}
			return $r;
		}

		/*echo '<font color=red><b>Расписание атак монстров:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font><br>';
		$sp = mysql_query('SELECT * FROM `aaa_monsters` ORDER BY `start_hh`');
		while( $pl = mysql_fetch_array($sp) ) {
			$btc = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
			if( isset($btc['id']) ) {
				if( testMonster($pl,'start') == true ) {
					echo '<a href="/info/'.$btc['id'].'" target="_blank">'.$btc['login'].'</a> появится через <b>Уже был!</b><br>';
				}else{
					if( $pl['start_hh'] != -1 ) {
						if ($pl['start_mm'] == - 1 ) { 
							$pl['start_mm'] = '00';
						}
						echo '<a href="/info/'.$btc['id'].'" target="_blank">'.$btc['login'].'</a> появится в '.$pl['start_hh'].':'.$pl['start_mm'].'<br>';
					}
				}
			}
		}
		echo '';*/
		?>
        <HR>
        <strong>Внимание!</strong> Никогда и никому не говорите пароль от своего персонажа. Не вводите пароль на других сайтах, типа "новый город", "лотерея", "там, где все дают на халяву". Пароль не нужен ни паладинам, ни кланам, ни администрации, <U>только взломщикам</U> для кражи вашего героя.<BR>
        <em>Администрация.</em></small> <BR>
       <? echo $rowonmax; ?><BR>
        
      </div></td>
  </tr>
</table>
<?
}

?>