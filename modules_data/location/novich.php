<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='novich')
{
	/*
	$tst = mysql_fetch_array(mysql_query('SELECT * FROM `dialog_act` WHERE `uid` = "'.$u->info['id'].'" AND `var` = "noobqst1" LIMIT 1'));
	if(!isset($tst['id'])) {
		if(isset($_GET['noobgo']) || $c['noobgade'] == false) {
			if($_GET['noobgo'] == 1 && $c['noobgade'] == true) {
				//���������� (������� ������ � ������������� ����
				
				$ins = mysql_query('INSERT INTO `dungeon_now` (`city`,`uid`,`id2`,`name`,`time_start`)
				VALUES ("'.$u->info['city'].'","'.$u->info['id'].'","106","�������� ��������","'.time().'")');
				if($ins){
					$zid = mysql_insert_id();
					//��������� �������������
					$su = mysql_query('SELECT `u`.`id`,`st`.`dn` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`id`="'.$u->info['id'].'"');
					$ids = '';
					
					$map_locs = array();
					$spm2 = mysql_query('SELECT `id`,`x`,`y` FROM `dungeon_map` WHERE `id_dng` = "106"');
					while( $plm2 = mysql_fetch_array( $spm2 ) ) {
						$map_locs[] = array($plm2['x'],$plm2['y']);
					}
					unset( $spm2 , $plm2 );
					
					$pxd = 0;
					while( $pu = mysql_fetch_array($su) ) {
						$pxd++;
						$ids .= ' `id` = "'.$pu['id'].'" OR';						
					}
					$ids = rtrim($ids,'OR');
					$snew = 0;
					$upd1 = mysql_query('UPDATE `stats` SET `s`="4",`res_s`="1",`x`="0",`y`="0",`res_x`="0",`res_y`="0",`dn` = "0",`dnow` = "'.$zid.'" WHERE '.$ids.'');
					if( $upd1 ){
						$upd2 = mysql_query('UPDATE `users` SET `room` = "391" WHERE '.$ids.'');
						//��������� ����� � ������� � ������ $zid � for_dn = $dungeon['id']
						//��������� �����
						$vls = '';
						$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "106"');
						while( $pl = mysql_fetch_array( $sp ) ) {
							if( $pl['id_bot'] == 0 && $pl['bot_group'] !=''){
								$bots = explode( ',', $pl['bot_group'] );
								$pl['id_bot'] = (int)$bots[rand(0, count($bots)-1 )];
							}
							if( $pl['id_bot'] > 0 )$vls .= '("'.$zid.'","'.$pl['id_bot'].'","'.$pl['colvo'].'","'.$pl['items'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['dialog'].'","'.$pl['items'].'","'.$pl['go_bot'].'","'.$pl['noatack'].'"),';
							unset($bots);
						}
						$vls = rtrim($vls,',');				
						$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`,`go_bot`,`noatack`) VALUES '.$vls.'');
						//��������� �������
						$vls = '';
						$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "106"');
						while($pl = mysql_fetch_array($sp))
						{
							$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
						}
						//
						$vls = rtrim($vls,',');	
						if( $vls != '' ) {			
							$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
						} else {
							$ins2 = true;
						}
						if( $upd2 && $ins1 && $ins2 ){
							die('<script>location="main.php?rnd='.$code.'";</script>');
						} else {
							$error = '������ �������� � ����������...';
						}
					} else {
						$error = '������ �������� � ����������...';
					}
				} else {
					$error = '������ �������� � ����������...';
				}			
				//
				//header('location: main.php');
				die();
			}else{
				//��������� (��������� �����, �������� � ����� ���)
				mysql_query('INSERT INTO `dialog_act` (
					`uid`,`var`,`time`
				) VALUES (
					"'.$u->info['id'].'","noobqst1","'.time().'"
				)');
				
				//������ ��������
				$humor = array(
					0 => array(
						''
					),
					1 => array(
						''
					)
				);
				$humor = $humor[$u->info['sex']];
				//$u->info['fnq'] = 1;
				//mysql_query('UPDATE `users` SET `fnq` = "'.$u->info['fnq'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//���������� ��������� � ��� � �������
				//mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `item_id` = 4703');
				//mysql_query('UPDATE `users` SET `room` = 4 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//mysql_query('UPDATE `stats` SET `hpNow` = 1000,`mpNow` = 1000,`dn` = 0 , `dnow` = 0 , `x` = 0 , `y` = 0 , `s` = 0 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//$u->send('','','','','','� ����� ���� �������� ����� ����� &quot;<b>' . $u->info['login'] . '</b>&quot;! '.$humor[rand(0,count($humor)-1)].'',time(),6,0,0,0,1,0);

				//echo '<div><font color=red><b>�� ���������� �� ��������, ������ ������� ������ �� �����!</b></font></div>';
			}
		}else{
			echo '<script>
			function qstnoobsstart() {
				top.win.add(\'qstnoobsstart\',\'�� ������ ������ ��������?\',\'\',{\'a1\':\'top.frames[\\\'main\\\'].location.href=\\\'main.php?noobgo=1\\\';\',\'a2\':\'top.frames[\\\'main\\\'].location.href=\\\'main.php?noobgo=2\\\';\',\'n\':\'<center><small>����������� �� �� �������� �������!</small></center>\'},2,1,\'width:300px;\');
			}
			qstnoobsstart();
			</script>';
		}
	}*/
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } ?>
    </td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right">
      <table width="510"  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td>
                <? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    <div style="position:relative; cursor: pointer; width: 500px;" id="ione"><img src="http://img.xcombats.com/i/images/300x225/club/navig2.jpg" id="img_ione" width="500" height="240"  border="1"/>
                      <div style="position:absolute; left:264px; top:106px; width:175px; height:37px; z-index:90;"><img src="http://img.xcombats.com/i/images/subimages/map_zal2.gif" width="175" height="37" id="mo_1.180.0.0" /></div>
                      <div style="position:absolute; left:47px; top:120px; width:135px; height:29px; z-index:90;"><img <? thisInfRm('1.180.0.1'); ?> src="http://img.xcombats.com/i/images/subimages/map_zal3.gif" width="135" height="29"  class="aFilter" id="mo_1.180.0.1" /></div>
                      <div style="position:absolute; left:81px; top:102px; width:88px; height:15px; z-index:90;"><img src="http://img.xcombats.com/i/images/subimages/map_zal1.gif" width="88" height="15" title="���� ����� ������� ��������" id="mo_1.180.0.2" class="aFilter" onclick="alert('���� ����� ������� ��������')"  /></div>
                      <div style="position:absolute; left:349px; top:139px; width:16px; height:18px; z-index:90;"><img src="http://img.xcombats.com/i/images/subimages/fl1.gif" width="16" height="18" title="�� ���������� � ������� ��� ��������"    /></div>
                      <div id="snow"></div>
                      <? echo $goline; ?>
                    </div>
                    </td>
                  </tr>
                </table> 
                <div style="display:none; height:0px " id="moveto"></div>       
              <div align="right" style="text-align:justify; padding: 3px;"><small>&laquo;<? echo $c['title3']; ?>&raquo; ������������ ���, <b><? echo $u->info['login']; ?></b>.<br />
                ����� ��������� � ���������� �� ������, ��� ����� ������������ ��������� ��������������.<br />
                ��� ����� ������� �� <a href='/main.php?skills=1&amp;side=1'>�����������</a>, � �����, ������� �� <img src="http://img.xcombats.com/i/plus.gif" width="9" height="9" /> / <img src="http://img.xcombats.com/i/minus.gif" width="9" height="9" />, ����������� ������ ���������.<br />
                ��������� � �������� ������������� ����� ������ � <b>����������</b>.<br />
                ����������� ��� �������������� ������� �� ������
                <input type="button" class="btn" value='���������' onclick="location.href='main.php?inv'" />
                <br />
                ��� ���������� ��� ������� �� ������
                <input onclick="location.href='main.php?zayvka'" type='button' value='��������' class="btn" />
                <br />
                �������� ������ &quot;�������&quot;.<br />
                ����� �������� � ��������� ����� ��������� � <b>����������</b><br />
            </small></div></td>
          <td>
              <!-- <br /><span class="menutop"><nobr>������� ��� ��������</nobr></span>-->
          </td>
        </tr>
      </table>
      	<small>
        <HR>
          <INPUT onclick="location.href='main.php?zayvka'" class="btn" value="��������" type="button" name="combats">
          <? $hgo = $u->testHome(); if(!isset($hgo['id'])){ ?><INPUT onclick="location.href='main.php?homeworld=<? echo $code; ?>';" class="btn" value="�������" type="button" name="combats2"><? } unset($hgo); ?>
          <INPUT onclick="location.href='main.php?clubmap=<? echo $code; ?>';" class="btn" value="����� �����" type="button" name="combats2">
          <INPUT id="forum" class="btn" onclick="window.open('http://xcombats.com/forum/', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')" value="�����" type="button" name="forum">
          <INPUT class="btn" onclick="window.open('/encicl/help/top1.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')" value="���������" type="button">
          <INPUT class="btn" value="�������" type="button">
        <br />
        <strong>��������!</strong> ������� � ������ �� �������� ������ �� ������ ���������. �� ������� ������ �� ������ ������, ���� "����� �����", "�������", "���, ��� ��� ���� �� ������". ������ �� ����� �� ���������, �� ������, �� �������������, <U>������ ����������</U> ��� ����� ������ �����.<BR>
        <em>�������������.</em></small> <BR>
       <? echo $rowonmax; ?><BR>        
      </div></td>
  </tr>
</table>
<?
}
?>