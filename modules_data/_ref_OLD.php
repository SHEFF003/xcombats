<?php
if(!defined('GAME') || !isset($_GET['referals']))
{
	die();
}

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
$sp = mysql_query('SELECT `s`.`active`,`u`.`online`,`u`.`id`,`u`.`level` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`host_reg` = "'.$u->info['id'].'" AND `u`.`mail` != "No E-Mail" ORDER BY `u`.`level` DESC LIMIT '.$rfs['count']);
while($pl = mysql_fetch_array($sp))
{
	$rfs['c2'] = '&nbsp; '.$rfs['c'].'. &nbsp; '.$u->microLogin($pl['id'],1).'';
	if($pl['active']!='')
	{
		$rfs['c2'] = '<font color="grey">'.$rfs['c2'].' &nbsp; <small>�� �����������</small></font>';
	}elseif($pl['level']>5)
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
	$rfs['see'] = '<b>� ��������� � ��� ��� ���������</b>';
}
?>
<table cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td style="vertical-align: top; "><table cellspacing="0" cellpadding="2" width="100%">
      <tr>
        <td colspan="4" align="center"><h4>��� ���������� ������� ������ � �������� ������ � ��:</h4></td>
      </tr>
      <tr>
        <td colspan="4">

<u><font color=#003388><b>��������� ���������� ��������</b>:</font></u><br>
<form style="padding:10px;" method="post" action="main.php?referals">
�����: <input type="text" value="" name="va_num" /> &nbsp; ������: <input type="password" name="va_psw" value="" /><button type="submit"><small>������������</small></button><br />������ �� ������: <input style="width:280px" type="text" name="va_url" value="" /><br>
</form>
<small><b>������� ���������� �������:</b>
<br />- ������ ������ ���� �������� � ���������� �����, ���� ������ ������ � ��������� ����������� �� ��� �������������
<br />- �� ������ ���������� �� ��������� ������ �� ����� �����
<br />- ������� �� ������ �������� �������� � ������� 24 �. (������ �� "��������")
<br />- ��� �������� ������������ ������� ��������� �� ������: <a href="� ����������">� ����������</a>
</small>
<br><br>
 
<u><font color=#003388><b>�������</b> ����� ��������:</font></u> <br>
- ������� ���� � ���� � ���������� �� ���� � ������� � ������������ � <a href=http://lib.xcombats.com/main/85 target=_blank>�������� �����</a> (�������� �� ����� ������)<br>
-  � �������: ������ ������� � �������<br>
- � ������� <b>����������� �������</b>, ������� ������� ���� (�������� �� ����� ������)<br>
- �������� � ������� ����������� ��������  (�������� � 4 ������)<br>
- ��������� (�������� � 4 ������)<br>
 - � ����� ������: ��������� � ����������� ��������� � ����� ��� (�������� � 1 ������)<br>
 <br><br>

<u><font color=#003388><b>�����������</b> ����� ��������:</font></u><br>
- � ������� <b>����������� �������</b>, ������� ������� ���� (�������� �� ����� ������)<br>
- ����� ����������� � ����������� ������� ��<br>
<br>  
<br>

<u><font color=#003388><b>�������� ������</b> ����� ��������:</font></u><br>
- � ������� <b>����������� ��������� ��</b>.<br>
<br><br>

<b>����������� �������</b> - ��� ����������� ������ <b>��������������� ���������</b> � ����. ��� �������� ����� � �����, �� ������������� ��������� ������ <b>����������� ������</b>, ������� ������ ������� ����� ������� � ��������.<br><br>

<b>������ ��������</b>, �������������������� � �� �� ����� ����������� ������, �� ���������� �� <b>7��</b> ������ ������ ��������� ��� <b>�������������� ���������</b>.<br>
<br>

��� ���������� ����� ���������:<br>
<b>&nbsp;2��</b> ������ - <b>0.50 ��</b>.<br>
<b>&nbsp;4��</b> ������ - <b>1.00 ��</b>.<br>
<b>&nbsp;5��</b> ������ - <b>25.00 ��</b>.<br>
<b>&nbsp;6��</b> ������ - <b>35.00 ��</b>.<br>
<b>&nbsp;7��</b> ������ - <b>75.00 ��</b>.<br>
<b>&nbsp;8��</b> ������, ��� ������������� ����� ���������� ��  ���� <b>1 ���</b>.<br>
<b>&nbsp;9��</b> ������ - <b>5 ���</b>.<br>
<b>10��</b> ������ - <b>15 ���</b>.<br>
<b>11��</b> ������ - <b>35 ���</b>.<br>
<b>12��</b> ������ - <b>50 ���</b>.<br>
        
          <p>&nbsp;</p>
          <p>���� ���������� ������ 
            <input style="background-color:#FBFBFB; border:1px solid #EFEFEF; padding:5px;" size="45" value="xcombats.com/register.php?ref=<?=$u->info['id']?>"  /> ��� <input style="background-color:#FBFBFB; border:1px solid #EFEFEF; padding:5px;" size="45" value="xcombats.com/r<?=$u->info['id']?>"  />
          </p></td>
      </tr>
      <tr>
        <td colspan="4"><p>&nbsp;</p>
          <ul>
            � ����������� ������� ������������ ��������� ��������� �����������
            ������� ������������ �� ����������� ����� ��������� � ���������� �������
          </ul>
          </td>
      </tr>
      <tr>
        <td colspan="4">���������� ���������: <b><?=$rfs['count']?></b> ��.</td>
      </tr>
      <tr>
        <td colspan="4"><?=$rfs['see']?></td>
      </tr>
    </table></td>
    <td style="width: 5%; vertical-align: top; ">&nbsp;</td>
    <td style="width: 30%; vertical-align: top; "><table width="100%" cellpadding="2" cellspacing="0">
      <tr>
        <td style="width: 25%; vertical-align: top; text-align: right; "><input type='button' value='��������' style='width: 75px' onclick='location=&quot;main.php?referals&quot;' />
          &nbsp;
          <input type="button" value="���������" style='width: 75px' onclick='location=&quot;main.php&quot;' /></td>
      </tr>
      <tr>
        <td align="center"><h4>��������� ����������� �������</h4></td>
      </tr>
      <tr>
        <td style="text-align:left;"><form method="post" action="main.php?referals"><table width="100%" border="0" cellspacing="5" cellpadding="0">
          <tr>
            <td width="200">���� ���������� ���.:</td>
            <td>
            <? $bsees = '';
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
			?>
            <select name="r_bank" id="r_bank">
            	<? 
					echo $bsees;
				?>
            </select>
            <? }else{
				echo '<b>��� ������ �������� ���� � ����� �� ������������ �����.</b>';
			}?>
            </td>
          </tr>
          <tr>
            <td align="right"><input type="submit" name="button" id="button" value="��������� ���������" /></td>
            <td>&nbsp;</td>
          </tr>
          </table></form></td>
      </tr>
    </table></td>
  </tr>
</table>
