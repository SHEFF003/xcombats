<?php
if(!defined('GAME') || !isset($_GET['referals']))
{
	die();
}

$tal = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "4005" AND `delete` < 1234567890'));

$rfs = array();
$rfs['count'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `host_reg` = "'.$u->info['id'].'" AND `mail` != "No E-Mail" LIMIT 1000'));
$rfs['count'] = 0+$rfs['count'][0];
$rfs['c'] = 1;
$rfs['data'] = explode('|',$u->info['ref_data']);
if(isset($_POST['r_bank']) || isset($_POST['r_type']))
{
	$bnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.mysql_real_escape_string($_POST['r_bank']).'" AND `uid` = "'.$u->info['id'].'" AND `block` = "0" LIMIT 1'));
	if(!isset($bnk['id']))
	{
		
	}else{
		if($_POST['r_type']==1){ $_POST['r_type'] = 1; }else{ $_POST['r_type'] = 2; }
		$u->info['ref_data'] = $bnk['id'].'|'.$_POST['r_type'];
		$rfs['data'] = explode('|',$u->info['ref_data']);
		mysql_query('UPDATE `stats` SET `ref_data` = "'.mysql_real_escape_string($u->info['ref_data']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
}
$rfs['see']   = '';
$sp = mysql_query('SELECT `s`.`active`,`u`.`activ`,`u`.`online`,`u`.`id`,`u`.`level`,`u`.`login` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`host_reg` = "'.$u->info['id'].'" AND `u`.`mail` != "No E-Mail" ORDER BY `u`.`level` DESC LIMIT '.$rfs['count']);
while($pl = mysql_fetch_array($sp))
{
	$rfs['c2'] = '&nbsp;<img onclick="top.chat.addto(\''.$pl['login'].'\',\'private\')" style="display:inline-block;cursor:pointer;" src="http://img.xcombats.com/i/lock.gif" width="20" height="15"> &nbsp; '.$u->microLogin($pl['id'],1).'';
	if($pl['activ'] != 0)
	{
		$rfs['c2'] = '<font color="grey">'.$rfs['c2'].' &nbsp; <small>�� �����������</small></font>';
	}elseif($pl['level']>7)
	{
		$rfs['c2'] = '<font color="green">'.$rfs['c2'].'</font>';
	}
	if($pl['online'] >time()-520) {
		$rfs['c2'] .= '<font color="green"> &nbsp; <small>ONLINE</small></font>';
	}
	$rfs['see'] .= $rfs['c2'].'<br>';
	$rfs['c']++;
}
if($rfs['see']=='')
{
	$rfs['see'] = '<center><b>� ���������, � ��� ��� �������������. ���������� ������ ������!</b></center>';
}
if(isset($_GET['nastanew'])) {
	/*$_GET['nastanew'] = htmlspecialchars($_GET['nastanew'],NULL,'cp1251');
	$upr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`online`,`admin`,`banned`,`level`,`host_reg` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_GET['nastanew']).'" ORDER BY `id` ASC LIMIT 1'));
	if(isset($upr['id']) && $upr['inUser'] == 0) {
		$ch1 = mysql_fetch_array(mysql_query('SELECT * FROM `chat` WHERE `type` = 90 AND `to` = "'.$upr['login'].'" AND `time` > '.(time()-3600).' AND `login` = "'.$u->info['login'].'" LIMIT 1'));
		if(isset($ch1['id'])) {
			$u->error = '�� ��� ���������� ����������� ��������� &quot;'.$upr['login'].'&quot;. (�� ���� ������ ���� � ���)';
		}elseif($upr['login'] == $u->info['login']) {
			$u->error = '����� :) �������� ����� ������ ��� ����?';
		}elseif($upr['level'] > 9 && $u->info['admin'] == 0) {
			$u->error = '������ ����� ����������� ��������� ������ 9-�� ������';
		}elseif($upr['id'] == $u->info['host_reg']) {
			$u->error = '������ ����� ������������� ������ ����������';
		}elseif($upr['online'] > time()-520) {
			if(is_int($upr['host_reg']) || $upr['host_reg'] > 0) {
				$u->error = '� ��������� &quot;'.$upr['login'].'&quot; ��� ���� ���������.';
			}else{
				$u->error = '�� ������� ����������� ��������� &quot;'.$upr['login'].'&quot; ����� ����� �������������.';	
				mysql_query('INSERT INTO `chat` (`login`,`to`,`type`,`new`,`time`) VALUES ("'.$u->info['login'].'","'.$upr['login'].'","90","1","'.time().'")');
			}
		}else{
			$u->error = '�������� &quot;'.$upr['login'].'&quot; ������ ���� � �������.';	
		}
	}else{
		$u->error = '�������� � ������� &quot;'.$_GET['nastanew'].'&quot; �� ������.';	
	}*/
}elseif(isset($_GET['nastayes'])) {
	/*$_GET['nastayes'] = htmlspecialchars($_GET['nastayes'],NULL,'cp1251');
	$upr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`online`,`admin`,`banned`,`level`,`host_reg` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_GET['nastayes']).'" LIMIT 1'));
	if(isset($upr['id'])) {
		$ch1 = mysql_fetch_array(mysql_query('SELECT * FROM `chat` WHERE `type` = 90 AND `to` = "'.$u->info['login'].'" AND `delete` > 0 AND `login` = "'.$upr['login'].'" LIMIT 1'));
		if(isset($ch1['id'])) {
			$myna = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.mysql_real_escape_string($u->info['host_reg']).'" LIMIT 1'));
			if(isset($myna['id'])) {
				$u->error = '� ��� ��� ���� ���������.';
			}else{
				$u->error = '�������� &quot;'.$_GET['nastayes'].'&quot; ���� ����� �����������!';
				mysql_query('UPDATE `users` SET `host_reg` = "'.$upr['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$upr['login']."',' �������� &quot;".$u->info['login']."&quot; ���������� ��� �� ��� �����������. �� ��������� <b>������ ����������</b> (<small>x1</small>).','-1','6','0')");
				$u->addItem(4005,$upr['id']);
			}
		}else{
			$u->error = '�������� &quot;'.$_GET['nastayes'].'&quot; �� ��������� ��� ������ ����������.';	
		}
	}else{
		$u->error = '�������� &quot;'.$_GET['nastayes'].'&quot; �� ��������� ��� ������ ����������.';	
	}*/
}

?>
<table cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td style="vertical-align: top; "><table cellspacing="0" cellpadding="2" width="100%">
      <tr>
        <td align="left">
        <?
		if($u->error != '') {
			echo '<font color=red>'.$u->error.'</font><br>';
		}
		/*
        <input type="button" onclick="top.nastavniknew()" value="���������� ��������������">
        */
		?> ������ ��� ������: <input disabled="disabled" style="background-color:#FBFBFB; width:100px; border:1px solid #EFEFEF; padding:5px;" size="45" value="xcombats.com/r<?=$u->info['id']?>"  />
        </td>
      </tr>
    </table>
      <table cellspacing="0" cellpadding="2" width="100%">
        <tr>
          <td align="center"><h4>��� ���������� ������� ������ � �������� ������ � ��:</h4></td>
        </tr>
        <tr>
          <td>
          <? if( true == false ) { ?>
          <u><font color="#003388"><b>��������� ���������� ��������</b>:</font></u><br />
            <form style="padding:10px;" method="post" action="main.php?referals">
              �����:
              <input type="text" value="" name="va_num" />
              &nbsp; ������:
              <input type="password" name="va_psw" value="" />
              <button type="submit" class="btnnew"><small>������������</small></button>
              <br />
              ������ �� ������:
              <input style="width:280px" type="text" name="va_url" value="" />
              <br />
            </form>
            <small><b>������� ���������� �������:</b> <br />
              - ������ ������ ���� �������� � ���������� �����, ���� ������ ������ � ��������� ����������� �� ��� ������������� <br />
              - �� ������ ���������� �� ��������� ������ �� ����� ����� <br />
              - ������� �� ������ �������� �������� � ������� 24 �. (������ �� &quot;��������&quot;) <br />
              - ��� �������� ������������ ������� ��������� �� ������: <a href="#">� ����������</a> </small> <br />
            <br />
            <? } ?>
            <?
			$rpgtop = mysql_fetch_array(mysql_query('SELECT * FROM `an_data` WHERE `var` = "rpgtop" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
			if(!isset($rpgtop['id'])) {
				if(isset($_GET['testtoprpg'])) {
					
					$html = file_get_contents('http://rpgtop.su/comm/t1/24296/1.htm');					
					$html = strripos($html,'*ID'.$u->info['id'].'*');
					
					if($html == false) {
						$html = file_get_contents('http://rpgtop.su/comm/t2/24296/1.htm');						
						$html = strripos($html,'*ID'.$u->info['id'].'*');
					}
					
					if($html == true) {
						echo '<div><b><font color=red>������� ��� ������������� �� ��� ������! ������ ��������� � ��� � ���������.</font></b></div>';
						mysql_query('INSERT INTO `an_data` (`uid`,`time`,`var`) VALUES (
							"'.$u->info['id'].'","'.time().'","rpgtop"
						)');
						$rpgtop['id'] = mysql_insert_id();
						// �������� ������ (+10) � ��6
						$u->addItem(1463,$u->info['id'],'|nosale=1|notransfer=1|sudba=1|noremont=1');
						$u->addItem(3101,$u->info['id'],'|nosale=1|notransfer=1|sudba=1|noremont=1');
						//
					}else{
						echo '<div><b><font color=red>��� ����� �� ������ � �������������.</font></b></div>';
					}					
				}
			}
			if(!isset($rpgtop['id'])) {
			?>
           <!-- <u><font color="#003388"><b>�������</b> �� ������������� ����� �� ����� <a href="http://rpgtop.su/comm/24296/1.htm" target="_blank">rpgtop.su</a>:</font></u><br />
            1. ��������� �� ������ <a href="http://rpgtop.su/comm/24296/1.htm" target="_blank">http://rpgtop.su/comm/24296/1.htm</a><br>
            2. �������� ������������� ����� � � ����� �������� ����� � ��������: " <b> *ID<?=$u->info['id']?>* </b> " (������ � �����������)<br>
            3. ������� �� ������ "��������� ��� �����"<br>
            4. ��� �������� ������������� �� �������� <a href="http://xcombats.com/item/1463" target="_blank"><img src="http://img.xcombats.com/i/items/spell_starshine.gif" height="20"> �������� ������</a> � <a href="http://xcombats.com/item/3101" target="_blank"><img src="http://img.xcombats.com/i/items/spell_powerHPup6.gif" height="20"> ����� ����� +6</a><br>
            <input onclick="location.href='http://xcombats.com/main.php?referals&testtoprpg';" type="button" class="btnnew" value="��������� ��� �����"><br><br>-->
            <?
			}
			?>
            <u><font color="#003388"><b>�������</b> ����� ��������:</font></u> <br />
            - ������� ���� � ���� � ���������� �� ���� � ������� � ������������ � <a href="http://xcombats.com/exp.php" target="_blank">�������� �����</a> (�������� �� ����� ������)<br />
            -  � �������: ������ ������� � �������<br />
            - � ������� <b>����������� �������</b>, ������� ������� ���� (�������� �� ����� ������)<br />
            - �������� � ������� ����������� ��������  (�������� � 4 ������)<br />
            - ��������� (�������� � 4 ������)<br />
            - � ����� ������: ��������� � ����������� ��������� � ����� ��� (�������� � 5 ������)<br />
            <br />
            <br />
            <u><font color="#003388"><b>�����������</b> ����� ��������:</font></u><br />
            - � ������� <b>����������� �������</b>, ������� ������� ���� (�������� �� ����� ������)<br />
            - ����� ����������� � ����������� ������� �� ��� ����� ������� ����������<br />
            <br />
            <br />
            <u><font color="#003388"><b>�������� ������</b> ����� ��������:</font></u><br />
            - � ������� <b>����������� ��������� ��</b>.<br />
            <br />
            <br />
            <b>����������� �������</b> - ��� ����������� ������ <b>��������������� ���������</b> � ����. ��� �������� ����� � �����, �� ������������� ��������� ������ <b>����������� ������</b>, ������� ������ ������� ����� ������� � ��������.<br />
            <br />
            <b>������ ��������</b>, �������������������� � �� �� ����� ����������� ������, �� ���������� �� <b>1��</b> ������ ������ ��������� ��� <b>�������������� ���������</b>.</td>
        </tr>
        <tr>
          <td><p>&nbsp;</p>
            <ul>
              � ����������� ������� ������������ ��������� ��������� �����������
              ������� ������������ �� ����������� ����� ��������� � ���������� �������
            </ul></td>
        </tr>
    </table></td>
    <td style="width: 5%; vertical-align: top; ">&nbsp;</td>
    <td style="width: 30%; vertical-align: top; "><table width="100%" cellpadding="2" cellspacing="0">
      <tr>
        <td style="width: 25%; vertical-align: top; text-align: right; "><input class="btnnew" type='button' value='��������' style='width: 75px' onclick='location=&quot;main.php?referals&quot;' />
          &nbsp;
          <input type="button" value="���������" style='width: 75px' class="btnnew" onclick='location=&quot;main.php&quot;' /></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><br />
		  <? if( $u->info['host_reg'] > 0 ) {
       			echo '��� ���������: '.$u->microLogin($u->info['host_reg'],1).'<hr />';/*
				$nas = mysql_fetch_array(mysql_query('SELECT `id`,`banned`,`room`,`login`,`align`,`level`,`city`,`room`,`online` FROM `users` WHERE `id` = "'.mysql_real_escape_string($u->info['host_reg']).'" LIMIT 1'));
		   		if(isset($nas['id'])) {
					$itm0 = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = 4004 AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
					$itm0 = $itm0[0];
				}
				if(isset($nas['id']) && $itm0 > 0) {
					if(isset($_GET['read_pr'])) {
						$itm0--;
					}
					echo '�� ������ ������� ������ ����������:<br><small>�������� <b>'.$itm0.'</b> <u>��������� ������������</u>.</small><br>';
					if($nas['banned'] > 0 || $nas['align'] == 2) {
						echo '<font color=red><b>��� ��������� � ����� ��� ������������.</b></font>';
					}elseif($nas['room'] != $u->info['room'] && $nas['online'] > time()-520 ) {
						echo '<font color=red><b>�� ������ ���������� � ����������� � ����� �������</b></font>';
					}else{
						$priz = '';
						$sp = mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$nas['id'].'" AND `vars` LIKE "%read%" AND `vals` > 1042 ORDER BY `vals` ASC');
						while($pl = mysql_fetch_array($sp)) {
							$tstsp = mysql_fetch_array(mysql_query('SELECT `id` FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` LIKE "%read%" AND `vals` = "'.$pl['vals'].'" LIMIT 1'));
							if(!isset($tstsp['id'])) {
								$prm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl['vals'].'" LIMIT 1'));
								if(isset($prm['id'])) {
									if(isset($_GET['read_pr']) && $_GET['read_pr'] == $prm['id']) {
										mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$u->info['login']."','�� ������� ������� ����� <b>".$prm['name']."</b> ��� ������ �������� ������������. ','".time()."','6','0')");
										mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$nas['login']."',' ��� ����������� &quot;".$u->info['login']."&quot; ������ ����� <b>".$prm['name']."</b> ��� ������ �������� ������������. <br><b>�� �������� ������ ���������� x3</b>','-1','6','0')");
										$u->addItem(4005,$nas['id']);
										$u->addItem(4005,$nas['id']);
										$u->addItem(4005,$nas['id']);
										mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`,`vals`,`val`) VALUES (
											"'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","read","'.$u->info['ip'].'",
											"'.$prm['id'].'",""
										)');
										mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = 4004 AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1');
										echo '<font color=red><b>����� &quot;'.$prm['name'].'&quot; ��� ������� ������!</b></font><br>';
									}else{
										$priz .= '<a href="?referals&read_pr='.$prm['id'].'"><img width="40" height="25" title="������� &quot;'.$prm['name'].'&quot;" src="http://img.xcombats.com/i/eff/'.$prm['img'].'"></a> ';
									}
								}
							}
						}
						if($priz == '') {
							echo '<font color=red><b>� ���������� ��� ��������� ������� ������� �� ����� �� ��������</b></font>';
						}else{
							echo $priz;
						}
					}
					echo '<hr>';
				}*/
		   }
	   ?>
          <div style="display:inline-block;width:300px;" align="left">
            <center>
              <b>��������� �� ���������</b>
              </center><br />
            <? $bsees = '<option value="0">�������� ����</option>';
					$sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `block` = "0" LIMIT 1');
					while($pl = mysql_fetch_array($sp))
					{
						if($rfs['data'][0]==$pl['id'])
						{
							$bsees .= '<option selected="selected" value="'.$pl['id'].'">� '.$pl['id'].'</option>';
						}else{
							$bsees .= '<option value="'.$pl['id'].'">� '.$pl['id'].'</option>';
						}
					}
					if($bsees != '') {
			?><center><form method="post" action="main.php?referals">
              ���� ��� ���.:
              <select name="r_bank" id="r_bank">
                <? 
					echo $bsees;
				?>
                </select>
              <input type="submit" name="button" class="btnnew" id="button" value="���������" /></form></center>
            <? }else{
				echo '<b style="color:red">��� ������ �������� ���� � ����� �� ������������ �����.</b>';
			}?><br />
            <?
	   $r = '<b><small>�� ���������� �������� �� ��������</small></b>:<br>';
	   $sp = mysql_query('SELECT * FROM `referal_bous` WHERE `type` = "1"');
	   while($pl = mysql_fetch_array($sp)) {
		   if($pl['add_bank'] > 0 || $pl['add_money'] > 0) {
		   		$r .= '<span style="display:inline-block;width:90px;">'.$pl['level'].' �������</span> - '; 
				if($pl['add_money'] > 0) { 
					$r .= '<span style="display:inline-block;width:90px;"> '.$pl['add_money'].' ��.</span>';
				}
				if($pl['add_bank'] > 0) {
					$r .= '<span style="display:inline-block;width:90px;"> '.$pl['add_bank'].' ekr.</span>';
				}
				$r .= '<br>';
		   }
	   }	   
	   echo $r;
	   /*
	   ?>
       		<br />
       		<b>�������� ������� �� beta-����</b><br />
            ������� ����� � ������ � beta-�����:<br />
            <?
			if(isset($_POST['betalogin'])) {
				$beta = mysql_fetch_array(mysql_query('SELECT * FROM `beta_testers` WHERE `login` = "'.mysql_real_escape_string($_POST['betalogin']).'" LIMIT 1'));
				$beta2 = mysql_fetch_array(mysql_query('SELECT * FROM `beta_testers` WHERE `active` = "'.$u->info['id'].'" LIMIT 1'));
				if(!isset($beta['id'])) {
					echo '<font color=red><b>����� beta-������� �� ������</b></font>';
				}elseif(md5($_POST['betapass']) != $beta['pass']) {
					echo '<font color=red><b>������� ������ ������� ��� �� ����� beta-�����</b></font>';
				}elseif(isset($beta2['id'])) {
					echo '<font color=red><b>�� ��� �������� ��������������!</b></font>';
				}else{
					echo '<font color=red><b>�� ������� �������� ��������������, ������ beta-�������.</b></font>';
					//������ � ����
					mysql_query('INSERT INTO `users_ico` (
						`uid`,`time`,`text`,`img`,`type`,`x`
					) VALUES (
						"'.$u->info['id'].'","'.time().'","<b>beta-������</b><br>������������� �� ������������� �������.","icn123.gif","1","1"
					)');
					//
					mysql_query('UPDATE `beta_testers` SET `active` = "'.$u->info['id'].'" WHERE `id` = "'.$beta['id'].'" LIMIT 1');
				}
			}
			?>
                <div align="center">
                <form style="width:144px;" method="post" action="main.php?referals=1">
                    <input style="width:144px;" name="betalogin" value="" type="text" /><br />
                    <input style="width:144px;" name="betapass" value="" type="password" /><br />
                    <input style="width:144px;" type="submit" class="btnnew" value="������� ��������������!" />
                </form>
                </div><? */ ?>
            </div>			
          </td>
      </tr>
      <tr>
        <td align="center"><h4>���� ������������ online:</h4></td>
      </tr>
      <tr>
        <td><?=$rfs['see']?></td>
      </tr>
      <tr>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
