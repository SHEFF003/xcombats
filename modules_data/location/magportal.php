<?
if( !defined('GAME') ) { die(); }
if( $u->room['file'] == 'magportal' ) {
    //if($u->info['admin'] == 1)
    if(isset($_POST['p1'])) {
		if($_POST['p1'] == '���������� ��������') {
			/*
			mysql_query('UPDATE `users` SET `room` = "369" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');
			*/
		}elseif($_POST['p1'] == '������ ������ ���������') {
			/*mysql_query('UPDATE `users` SET `room` = "372" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}elseif($_POST['p1'] == '������') {
			/*mysql_query('UPDATE `users` SET `room` = "354" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}elseif($_POST['p1'] == '�����������') {
			/*mysql_query('UPDATE `users` SET `room` = "188" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}elseif($_POST['p1'] == '���������') {
			/*mysql_query('UPDATE `users` SET `room` = "293" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}elseif($_POST['p1'] == 'catacombs_new') {
			/*mysql_query('UPDATE `users` SET `room` = "378" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}elseif($_POST['p1'] == '������ ����') {
			/*mysql_query('UPDATE `users` SET `room` = "380" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}elseif($_POST['p1'] == '�������� �������') {
			/*mysql_query('UPDATE `users` SET `room` = "383" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}elseif($_POST['p1'] == '��������') {
			/*mysql_query('UPDATE `users` SET `room` = "18" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			die('<script>location="main.php";</script>');*/
		}
    }
?>
<style>
body {
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
#form1 label {
    padding:3px 0px;
    display:inline-block;
}
#form1 label > img {
    margin:0px 5px 0px 2px;
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
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.213&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.213',1); ?>">������� �������� �����</a></td>
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

<form id="form1" name="form1" method="post" action="main.php?go_psh=1">
	<fieldset>
	<legend><B>�� ������ ����������������� � ����� �� �����:</B></legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="10"> 
		<tr>
		<td>
			<label><input type="radio" name="p1" value="�����������" id="p1_5" /><img src="http://img.xcombats.com/i/city_ico2/dreamscity.gif" width="33" height="19" style="vertical-align:bottom" />����������� (Dreams City)</label><br/>
	
			<label><input name="p1" type="radio" id="p1_1" value="������" /><img src="http://img.xcombats.com/i/city_ico2/angelscity.gif" width="33" height="19" style="vertical-align:bottom" />������ (Angels City)</label><br />
	
			<label><input name="p1" type="radio" id="p1_0" value="������ ������ ���������" /><img src="http://img.xcombats.com/i/city_ico2/mooncity.gif" width="33" height="19" style="vertical-align:bottom" />������ ������ ��������� (Capital City)</label><br />
	
			<label><input name="p1" type="radio" id="p1_3" value="���������" /><img src="http://img.xcombats.com/i/city_ico2/demonscity.gif" width="33" height="19" style="vertical-align:bottom" />��������� (Demons City)</label><br /> 
	
			<label><input name="p1" type="radio" id="p1_0" value="���������� ��������" /><img src="http://img.xcombats.com/labaico.gif" width="33" height="19" style="vertical-align:bottom" />���������� ��������</label><br /> 
			<p style="margin-bottom:0px;"><input type="submit" name="button" id="button" value="�����������������" /></p>
		</td>
		</tr>
	</table> 
	</fieldset>
	
	<br/><br/>
	<? if($u->info['admin'] > 0) { ?>
	<fieldset>
		<legend><b>������ � ������ (������ �������)</b></legend>
		<table>
			<tr>
				<td>
					<label><input name="p1" type="radio" id="p1_3" value="catacombs_new" /><img src="http://img.xcombats.com/i/city_ico2/demonscity.gif" width="33" height="19" style="vertical-align:bottom" />103: ��������� (�����)</label><br /> 
	
					<label><input type="radio" name="p1" value="��������" id="p1_6" /><img src="http://img.xcombats.com/i/city_ico2/suncity.gif" width="33" height="19" style="vertical-align:bottom" />104: �������� (Sun City)</label><br />
					<label><input type="radio" name="p1" value="������ ����" id="p1_2" /><img src="http://img.xcombats.com/i/city_ico2/sandcity.gif" width="33" height="19" style="vertical-align:bottom" />105: ������ ���� (Sand City)</label><br />
					<label><input disabled="disabled" type="radio" name="p1" value="�������� ������" id="p1_8" /><img src="http://img.xcombats.com/i/city_ico2/devilscity.gif" width="33" height="19" style="vertical-align:bottom" />106: �������� ������ (Devils City)</label><br />
					<label><input disabled="disabled" type="radio" name="p1" value="���������� �����" id="p1_7" /><img src="http://img.xcombats.com/i/city_ico2/abandonedplain.gif" width="33" height="19" style="vertical-align:bottom" />107: ���������� ����� (Abandoned Plain)</label><br />
					<label><input disabled="disabled" type="radio" name="p1" value="���������� ����" id="p1_4" /><img src="http://img.xcombats.com/i/city_ico2/emeraldscity.gif" width="33" height="19" style="vertical-align:bottom" />108: ���������� ���� (Emeralds City)</label><br />
					<label><input disabled="disabled" type="radio" name="p1" value="���� �������" id="p1_9" /><img src="http://img.xcombats.com/i/city_ico2/legion.gif" width="33" height="19" style="vertical-align:bottom" />110: ���� ������� (Abandoned Plain)</label><br />
					<label><input disabled="disabled" type="radio" name="p1" value="������� ������" id="p1_10" /><img src="http://img.xcombats.com/i/city_ico2/snowcity.gif" width="33" height="19" style="vertical-align:bottom" />111: ������� ������ (Snow City)</label><br/>
					<label><input type="radio" name="p1" value="�������� �������" id="p1_10" /><img src="http://img.xcombats.com/i/city_ico2/abandonedplain.gif" width="33" height="19" style="vertical-align:bottom" />999: �������� �������</label><br/>
					<p style="margin-bottom:0px;"><input type="submit" name="button" id="button" value="�����������������" /></p>
				</td>
			</tr>
		</table>
	</fieldset>
	<? } ?> 
</form>

<? } ?>