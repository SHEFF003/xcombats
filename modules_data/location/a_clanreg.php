<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='a_clanreg')
{
	
// ����� �������� ������ 

class upload {
 
protected function __construct() { }
 
static $save_path = 'clan_prw/';
static $error = '';
 
static function saveimg($name,$max_mb = 2,$exts = 'jpg|png|jpeg|gif',$cnm = '') {
    if (isset($_FILES[$name])) {
        $f = &$_FILES[$name];
        if (($f['size'] <= $max_mb*1024*1024) && ($f['size'] > 0)) {
            if (
                (preg_match('/\.('.$exts.')$/i',$f['name'],$ext))&&
                (preg_match('/image/i',$f['type']))
            ) {

				$ext[1] = strtolower($ext[1]);
                $fn = uniqid('f_',true).'.'.$ext[1];
                $fn2 = uniqid('f_',true).'.gif';
                if (move_uploaded_file($f['tmp_name'], self::$save_path . $fn)) {
                    // ������� ��������� ������� , ��������� Rimage
                    //Rimage::resize(self::$save_path . $fn, self::$save_path . $fn2);
                    //@unlink(self::$save_path . $fn); // �������� �����
                    return array($fn2,$fn,self::$save_path . $fn);
                } else {
                    self::$error = '������ �������� �����';
                }
            } else {
                self::$error = '�������� ��� �����. ���������� ���� : <b>'.$exts.'</b>';
            }
        } else {
            self::$error = '�������� ������ �����. ������������ ������ ����� <b>'.$max_mb.' ��</b>';
        }
    } else {
        self::$error = '���� �� ������';
    }
    return false;
} // end saveimg
 
} // end class

$lzv = mysql_fetch_array(mysql_query('SELECT * FROM `_clan` WHERE `uid` = "'.$u->info['id'].'" AND `admin_time` = "0" LIMIT 1'));

/* ����������� ����� */
if(isset($_POST['clan_name'])) {
	/*if($_POST['clan_align'] != 0) {
		$_POST['clan_align'] = 0;
	}*/
	
	$tr_money = 0;
	if($_POST['clan_align'] == 1) {
		$tr_money = 1500;
		$_POST['clan_align'] = 1;
	}elseif($_POST['clan_align'] == 3) {
		$tr_money = 1500;
		$_POST['clan_align'] = 3;
	}elseif($_POST['clan_align'] == 7) {
		$tr_money = 1500;
		$_POST['clan_align'] = 7;
	}else{
		$tr_money = 750;
		$_POST['clan_align'] = 0;
	}
	
	if(true == false) {
		$re = '����������� ������ �������� �� ��������.';
	}elseif(isset($lzv['id'])) {
		$re = '�� ��� ������ ������ �� ����������� �����, �������� ������ �� �������������';
	}elseif( $u->testAlign($_POST['clan_align'],$u->info['id']) == 0 ) {
		$re = '�� �� ������ ���������������� ���� � ������ �����������. (��������� ����������� �� ����� ���������� ��� ������ ���������)';
	}elseif($tr_money > $u->info['money']) {
		$re = '� ��� �� ������� �����, ��������� '.$tr_money.'��.';
	}elseif($u->info['palpro'] < time() && $u->info['admin'] == 0) {
		$re = '��� ���������� ������ �������� � ���������/��������';
	}elseif($u->info['clan'] > 0){
		$re = '�� �������� � ����� �� ������, ��������� �������� ���';
	}elseif($u->info['align'] > 0){
		$re = '��������� �� ����������� �� ����� ��������� ����';
	}else{
		/* ������� ������ � ���� */
		$clan_name = substr(htmlspecialchars($_POST['clan_name2'],NULL,'cp1251'), 0, 30);
		$clan_name = str_replace('.','',$clan_name);
		$clan_name = str_replace(' ','',$clan_name);
		$clan_name = str_replace('	','',$clan_name);
		if($file = upload::saveimg('clan_img1',0.3,'gif',$clan_name)) {
			if($file2 = upload::saveimg('clan_img2',0.5,'gif',$clan_name)) {
				if($tr_money < 0) {
					$tr_money = 0;
				}
				$u->info['money'] -= $tr_money;
				
				mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query('INSERT INTO `_clan` (`uid`,`time`,`city`,`name`,`name2`,`site`,`img1`,`img2`,`info`,`money`,`align`) VALUES (
					"'.$u->info['id'].'","'.time().'",
					"'.$u->info['city'].'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_name'],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_name2'],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_site'],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($file[1],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($file2[1],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(str_replace("\r\n",'<br>',htmlspecialchars($_POST['clan_info'],NULL,'cp1251'))).'",
					"'.$tr_money.'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_align'],NULL,'cp1251')).'"
				)');
				$lzv = array(
					'id' => mysql_insert_id(),
					'name' => htmlspecialchars($_POST['clan_name'],NULL,'cp1251'),
					'time' => time()
				);
				$re = '�� ������� ������ ������ �� ����������� ����� &quot;'.htmlspecialchars($_POST['clan_name'],NULL,'cp1251').'&quot;. ('.$tr_money.'��.)';
			}else{
				@unlink($file[2]); // �������� �����
				$re = '������� ������: '.upload::$error;
			}
		}else{
			$re = '��������� ������: '.upload::$error;
		}
	}
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
    </div>
        <?
		if($re != '') {
			echo '<font style="float:left" color="red"><b>'.$re.'</b></font>';
		}
		?>
    </td>
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
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="javascript:void(0)" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.11&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.11',1); ?>">����������� �����</a></td>
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
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td>��� ����������� ������ ����� ���������� �����:<br />
    <br />
    &bull; <b>���� �����</b> (��������� �����, �����, �������������� ������ �����, ������� mail.ru <small>(������ ����)</small>)
    .<br />&bull; <b>��������, ����� � �������� ����� ��� ������������</b>, ������� �� ����� ���������: ���������� ������� �� ��������� ����, �������������� �������� �� ��������� ����, ���������� ���� �������������� �������� ������� (������������� �������, �������������� ������������, ���������� ����������, �����, *������, ������������������ ������������, ������������� ������ ���� [�� �������, �����������, ������� �������������� � �.�.] � �.�.)
    <br />&bull; <b>������</b>: <br /> &nbsp; &nbsp; &nbsp;
    ��������� ������ ����� (������������ ����� � ����� ��������� � ����, �� ������, � ��� � �.�.):<br />
    &nbsp; &nbsp; &nbsp; - gif ��������<br />
    &nbsp; &nbsp; &nbsp; - ���������� ���<br />
    &nbsp; &nbsp; &nbsp; - ������ 24x15<br />
    &nbsp; &nbsp; &nbsp; - �� ����� ��� 32 �����<br />
    <br /> &nbsp; &nbsp; &nbsp;
    ������� ������ ����� (��� ������������):<br />
    &nbsp; &nbsp; &nbsp; - gif ��������<br />
    &nbsp; &nbsp; &nbsp; - ���������� ���<br />
    &nbsp; &nbsp; &nbsp; - ������ 100x99<br />
    &nbsp; &nbsp; &nbsp; - ���������� ������ ���� ��������� � �����<br />
    <br /> 
    &bull; ����� ������� ������, ������� ����� ����� ������ ������ �������� � ����������� (���������, ��������), � ���-�� �������� ������� ����.<br />
    <b style="color:red">&bull; ����������� ���� ����� �������� � ��������������� ��������������� ����� ��� �����������, ������������ ����� ����� ����������. (������� �� ������� � ����� �������� �����������)</b>
    <br />
    <b style="color:red">&bull; ��� ����������� �����, ����� ����� � ������� 60 ���� �� ����� ����� �������� ��� ���������� ������� ������.</b>
    </td>
  </tr>
  <tr>
    <td><form action="main.php?go_psh=1" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <fieldset style="line-height:1.5em;">
        <legend><h3>������</h3></legend>
        <? if(!isset($lzv['id'])) { ?>
        �������� �����: <input name="clan_name" type="text" value="" size="50" maxlength="50" />
        <br />
        ���������� ������������ (������ ���������� �����, ���� �����) <input name="clan_name2" type="text" value="" size="30" maxlength="30" />
        <br />
        ������ �� ����������� ���� �����: <input name="clan_site" type="text" value="http://" size="40" maxlength="80" />
        <br />
        ��������� ������ <input type="file" name="clan_img1" id="clan_img1" />
		<br />
        ������� ������ <input type="file" name="clan_img2" id="clan_img2" />
        <br />
        ���������� ����� 
        <select name="clan_align">
          <option value="0">����� (750 ��.)</option>
          <option value="7">����������� (1500 ��.)</option>
          <option value="3">������ (1500 ��.)</option>
          <option value="1">������� (1500 ��.)</option>
        </select>
        <br />
        �������� ����� ��� ������������:<br />
        <textarea cols="104" name="clan_info" id="clan_info" rows="6"></textarea>
        <br />
        <small style="color:red">� ������ ������ � ����������� (�� ����� �������) ������������ 100% �� ��������� �����.<br />
        ������������� � ����� �������� � ����������� ��� ���������� �������.</small>
        <br />
        <input type="submit" name="button" id="button" class="knopka" value="������ ������" />
        <? }else{ ?>
        <?=date('d.m.Y H:i',$lzv['time'])?> &nbsp; &nbsp; �� ��� ������ ������ �� ����������� ����� &quot;<b><?=$lzv['name']?></b>&quot;. �������� ������ �� ������������� �� �����.
        <? } ?>
      </fieldset>
    </form></td>
  </tr>
</table>
<p>
  <? } ?>
</p>