<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='entersnow')
{

$dun = 20; //��� �������� 2-7 ���

$er = '';

$dzs = '';

$g111 = 1;

$g11 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh1" AND `time`>'.(time()-7200).' LIMIT 1',1);

$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));

if($u->info['dn']>0)
{
	$zv_dn = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `id`="'.$u->info['dn'].'" AND `dun` = "'.$dun.'" AND `delete` = "0" LIMIT 1'));
	if(!isset($zv_dn['id']))
	{
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
	}
}

if(isset($g11['id']))
{
	$g111 = 0;
	if(isset($_GET['start']))
	{
		$re = '����� � ������ �������� ���� ��� � ��� ����. �������� ���: '.$u->timeOut(7200-time()+$g11['time']);
	}
}
if(isset($_GET['start']) && $zv_dn['uid']==$u->info['id'] && $g111 == 1)
{	
	//�������� �����
	//�������� �����
	$ig = 1;
	if($ig>0)
	{
		//���������� ������� � ������
		//$u->addAction(time(),'psh1','');
		$ins = mysql_query('INSERT INTO `dungeon_now` (`city`,`uid`,`id2`,`name`,`time_start`)
		VALUES ("'.$zv_dn['city'].'","'.$zv_dn['uid'].'","'.$dun.'","������� ������","'.time().'")');
		if($ins)
		{
			$zid = mysql_insert_id();
			//��������� �������������
			$su = mysql_query('SELECT `u`.`id`,`st`.`dn` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$zv_dn['id'].'" LIMIT '.($zv_dn['team_max']+1).'');
			$ids = '';
			while($pu = mysql_fetch_array($su))
			{
				$ids .= ' `id` = "'.$pu['id'].'" OR';
				$u->addAction(time(),'psh1','',$pu['id']);
			}
			$ids = rtrim($ids,'OR');
			$upd1 = mysql_query('UPDATE `stats` SET `x`="0",`y`="0",`dn` = "0",`dnow` = "'.$zid.'" WHERE '.$ids.' LIMIT '.($zv_dn['team_max']+1).'');
			if($upd1)
			{
				$upd2 = mysql_query('UPDATE `users` SET `room` = "304" WHERE '.$ids.' LIMIT '.($zv_dn['team_max']+1).'');
				//��������� ����� � ������� � ������ $zid � for_dn = $dun
				//��������� �����
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$dun.'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['id_bot'].'","'.$pl['colvo'].'","'.$pl['items'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['dialog'].'","'.$pl['items'].'"),';
				}
				$vls = rtrim($vls,',');				
				$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`) VALUES '.$vls.'');
				//��������� �������
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "'.$dun.'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
				}
				$vls = rtrim($vls,',');	
				if($vls!='')
				{			
					$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
				}else{
					$ins2 = true;
				}
				if($upd2 && $ins1 && $ins2)
				{
					mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv_dn['id'].'" LIMIT 1');
					die('<script>location="main.php?rnd='.$code.'";</script>');
				}else{
					$re = '������ �������� � ����������...';
				}
			}else{
				$re = '������ �������� � ����������...';
			}
		}else{
			$re = '������ �������� � ����������...';
		}
	}
}elseif(isset($_POST['go'],$_POST['goid']) && $g111==1)
{
	if(!isset($zv_dn['id']))
	{
		$zv_dn = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `id`="'.mysql_real_escape_string($_POST['goid']).'" AND `dun` = "'.$dun.'" AND `delete` = "0" LIMIT 1'));
		if(isset($zv_dn['id']))
		{
			if($u->info['level']>5)
			{
				$row = 0;
				if(5>$row)
				{
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv_dn['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if(!$upd)
					{
						$re = '�� ������� �������� � ��� ������';
						unset($zv_dn);
					}else{
						$u->info['dn'] = $zv_dn['id'];
					}
				}else{
					$re = '� ������ ��� �����';
					unset($zv_dn);
				}
			}else{
				$re = '�� �� ��������� �� ������';
				unset($zv_dn);
			}
		}else{
			$re = '������ �� �������';
		}
	}else{
		$re = '�� ��� ���������� � ������';
	}
}elseif(isset($_POST['leave']) && isset($zv_dn['id']) && $g111 == 1)
{
	if($zv_dn['uid']==$u->info['id'])
	{
		//������ � ������ ������ ������������
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv_dn['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ld['id']))
		{
			$zv_dn['uid'] = $ld['id'];
			mysql_query('UPDATE `dungeon_zv` SET `uid` = "'.$zv_dn['uid'].'" WHERE `id` = "'.$zv_dn['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv_dn);
		}else{
			//������� ������ �������
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv_dn['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv_dn);
		}
	}else{
		//������ ������� � ������
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
		unset($zv_dn);
	}
}elseif(isset($_POST['add']) && $u->info['level']>1 && $g111 == 1)
{
	if($u->info['dn']==0)
	{
		$lmn = 6;
		$lmx = 21;
		$tmx = 5;
		
		//������ ������ ������
		$lmn = $u->info['level'];
		
		$ins = mysql_query('INSERT INTO `dungeon_zv`
		(`city`,`time`,`uid`,`dun`,`pass`,`com`,`lvlmin`,`lvlmax`,`team_max`) VALUES
		("'.$u->info['city'].'","'.time().'","'.$u->info['id'].'","'.$dun.'",
		"'.mysql_real_escape_string($_POST['pass']).'",
		"'.mysql_real_escape_string($_POST['text']).'",
		"'.$lmn.'",
		"'.$lmx.'",
		"'.$tmx.'")');
		if($ins)
		{
			$u->info['dn'] = mysql_insert_id();
			$zv_dn['id'] = $u->info['dn'];
			$zv_dn['uid'] = $u->info['id'];
			mysql_query('UPDATE `stats` SET `dn` = "'.$u->info['dn'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$re = '�� ������� ������� ������';
		}else{
			$re = '�� ������� ������� ������';
		}
	}else{
		$re = '�� ��� ���������� � ������';
	}
}

//���������� ������ �����
$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `dun` = "'.$dun.'" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');
while($pl = mysql_fetch_array($sp))
{
	$dzs .= '<div style="padding:2px;">';
	if($u->info['dn']==0)
	{
		$dzs .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	}
	$dzs .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	
	$pus = ''; //������
	$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
	while($pu = mysql_fetch_array($su))
	{
		$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" title="���. � '.$pu['login'].'"></a>';
		$pus .= ', ';
	}
	$pus = trim($pus,', ');
	
	$dzs .= $pus;
	
	if($pl['com']!='')
	{
		$dl = '';
		if(($moder['boi']==1 || $u->info['admin']>0) && $pl['dcom']==0)
		{
			$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">������� �����������</a>)';
			if(isset($_GET['delcom']) && $_GET['delcom']==$pl['id'] && $u->newAct($_GET['key'])==true)
			{
				mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['dcom'] = $u->info['id'];
			}
		}
		
		$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
		
		if($pl['dcom']>0)
		{
			$dl = ' <font color="grey"><i>����������� ������ �����������</i></font>';
		}	
		
		if($pl['dcom']>0)
		{
			if($moder['boi']==1 || $u->info['admin']>0)
			{
				$pl['com'] = '<font color="red">'.$pl['com'].'</font>';
			}else{
				$pl['com'] = '';
			}
		}
		
		$dzs .= '<small> | '.$pl['com'].''.$dl.'</small>';
	}
		
	$dzs .= '</div>';
}
?>
<style>
body
{
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div></td>
    <td width="200"><div align="right">
      <table cellspacing="0" cellpadding="0">
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
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.208&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.208',1); ?>">����</a></td>
                            </tr>
                        </table></td>
                      </tr>
                  </table></td>
              </tr>
          </table></td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<?
if($re!='')
{
	echo '<font color="red"><b>'.$re.'</b></font><br>';
}

//����������
if($dzs=='')
{
	$dzs = '';
}else{
	if(!isset($zv_dn['id']))
	{
		if($g111==1)
		{
			$pr = '<input name="go" type="submit" value="�������� � ������">';
		}
		$dzs = '<form action="main.php?rnd='.$code.'" method="post">'.$pr.'<br>'.$dzs.''.$pr.'</form>';
	}
	$dzs .= '<hr>';
}

echo $dzs;
if($g111 == 1)
{
if($u->info['dn']==0)
{
?>
<table width="350" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
    <form id="from" name="from" action="main.php?pz1=<? echo $code; ?>" method="post">
      <fieldset style='padding-left: 5; width=50%'>
      <legend><b> ������ </b> </legend>
        �����������
        <input type="text" name="text" maxlength="40" size="40" />
      <br />
        ������
  <input type="password" name="pass" maxlength="25" size="25" />
  <br />
  <input type="submit" name="add" value="������� ������" />
  &nbsp;<br />
      </fieldset>
    </form>
    </td>
  </tr>
</table>
<?
}else{
    $psh_start = '';
	if(isset($zv_dn['id']))
	{
		if($zv_dn['uid']==$u->info['id'])
		{
			$psh_start = '<INPUT type=\'button\' name=\'start\' value=\'������\' onClick="top.frames[\'main\'].location = \'main.php?start=1&rnd='.$code.'\'"> &nbsp;';
		}
		  
		echo '<br><FORM id="REQUEST" method="post" style="width:210px;" action="main.php?rnd='.$code.'">
		<FIELDSET style=\'padding-left: 5; width=50%\'>
		<LEGEND><B> ������ </B> </LEGEND>
		'.$psh_start.'
		<INPUT type=\'submit\' name=\'leave\' value=\'�������� ������\'> 
		</FIELDSET>
		</FORM>';
	}
}
?>

<?
}else{
	echo '����� � ������ �������� ���� ��� � ��� ����. �������� ���: '.$u->timeOut(7200-time()+$g11['time']).'<br><small style="color:grey">�� �� ������ ������ ���������� ���� �� ������� � ������ &quot;�������� �����&quot; � �������� ���� ;)</small>';
}
}

?>
