<?php
/*

	���� ��� ��������� ������.
	��������� ���������, ��������� ������, ��������� �����, ��������� �����, ��������� ��������, ��������� ��������� ���������

*/

define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');


if( $u->info['admin'] == 0 && $u->info['id'] != 1001398 ) {
	header('location: /index.php');
}

if( isset($_POST['it_name']) ) {
	//��������� �������
	$error = '';
	
/*
Array
(
[it_name] => ������ -����� �������-
[it_img] => old/kastet2.gif
[it_type] => 18
[it_massa] => 2
[it_price1] => 3
[it_price2] =>
[it_iznos] => 20
[it_slot] => 3
[it_inRazdel] => 1
[it_info] =>
[it_group_max] =>
[it_geni] => 1
[it_srok] =>
[it_max_text] =>
[it_ndata] =>
[it_data_value] => |sv_minAtack=2|sv_maxAtack=4
[button] => ��������� ������� � ����
)
*/
	
	if( (int)$_POST['it_group_max'] > 0 ) {
		$_POST['it_group'] = 1;
	}
	
	$ins = mysql_query('INSERT INTO `items_main`
		(`name`,`img`,`type`,`massa`,`price1`,`price2`,`iznosMAXi`,`inslot`,
		 `inRazdel`,`info`,`group`,`group_max`,`geni`,`srok`,`max_text`,`2h`,`2too`) VALUES
		(
			"'.mysql_real_escape_string($_POST['it_name']).'",
			"'.mysql_real_escape_string($_POST['it_img']).'",
			"'.mysql_real_escape_string($_POST['it_type']).'",
			"'.mysql_real_escape_string($_POST['it_massa']).'",
			"'.mysql_real_escape_string($_POST['it_price1']).'",
			"'.mysql_real_escape_string($_POST['it_price2']).'",
			"'.mysql_real_escape_string($_POST['it_iznos']).'",
			"'.mysql_real_escape_string($_POST['it_slot']).'",
			"'.mysql_real_escape_string($_POST['it_inRazdel']).'",
			"'.mysql_real_escape_string($_POST['it_info']).'",
			"'.mysql_real_escape_string($_POST['it_group']).'",
			"'.mysql_real_escape_string($_POST['it_group_max']).'",
			"'.mysql_real_escape_string($_POST['it_geni']).'",
			"'.mysql_real_escape_string($_POST['it_srok']).'",
			"'.mysql_real_escape_string($_POST['it_max_text']).'",
			"'.mysql_real_escape_string($_POST['it_2h']).'",
			"'.mysql_real_escape_string($_POST['it_2too']).'"
		)');
		
		if( $ins ) {
			$iid = mysql_insert_id();
			$ins = mysql_query('INSERT INTO `items_main_data` (`items_id`,`data`) VALUES (
				"'.$iid.'","'.mysql_real_escape_string($_POST['it_data_value']).'"
			)');
			if( !$ins ) {
				$error = '��������� �������� Er::(2)!';
			}else{
				$error = $iid.' ������� ��������!';
			}
		}else{
			$error = '��������� �������� Er::(1)!';
		}
	
	
	if( $error == '' ) {
		$error = '���-�� �� ���...';
	}
	die('<font color=red><b>'.$error.'</b></font>');
}


	//�������� ���������
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>�������� ���������</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script>
var et = {
	is_par:[
	<?
	$html = ''; $html2 = '';
	$i = 0;
	$is_key = array_keys($u->is);
	while( $i < count($is_key) ) {
		$html  .= ',"'.$is_key[$i].'"';
		$html2 .= ',"'.$is_key[$i].'":"'.$u->is[$is_key[$i]].'"';
		$i++;
	}
	echo ltrim($html,',');	
	?>
	],	is_name:{
	<?=ltrim($html2,',')?>
	},
	data:{
		img:'w/w10.gif',
		name:'�������� ������ ��������'
	},
	complData:function() {
		var html = '';
		
		//�������
		if( this.it_data_pr.tr != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.tr ) {
				var npar = $('#par_tr_'+i).val();
				if( npar != undefined && $('#val_tr_'+i).val() != '' ) {
					html += '|tr_'+npar+'='+$('#val_tr_'+i).val();
				}
				i++;
			}
		}
		//��������� ��
		if( this.it_data_pr.add != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.add ) {
				var npar = $('#par_add_'+i).val();
				if( npar != undefined && $('#val_add_'+i).val() != '' ) {
					html += '|add_'+npar+'='+$('#val_add_'+i).val();
				}
				i++;
			}
		}
		//��������
		if( this.it_data_pr.sv != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.sv ) {
				var npar = $('#par_sv_'+i).val();
				if( npar != undefined  && $('#val_sv_'+i).val() != '') {
					html += '|sv_'+npar+'='+$('#val_sv_'+i).val();
				}
				i++;
			}
		}
		//���������
		if( this.it_data_pr.all != undefined ) {
			var i = 0;
			while( i <= this.it_data_pr.all ) {
				var npar = $('#par_all_'+i).val();
				if( npar != undefined && $('#val_all_'+i).val() != '' ) {
					html += '|'+npar+'='+$('#val_all_'+i).val();
				}
				i++;
			}
		}

		$('#it_data_value').val( html );
	},
	it_data_pr:{},
	newpar:function( id ) {
		var html = '';
		if( this.it_data_pr[id] == undefined ) {
			this.it_data_pr[id] = 0;
		}else{
			this.it_data_pr[id]++;
		}
		
		html += '<select id="par_'+id+'_'+this.it_data_pr[id]+'" name="par_'+id+'_'+this.it_data_pr[id]+'">';
		var i = 0;
		while( i <= this.is_par.length ) {
			if( this.is_par[i] != undefined ) {
				html += '<option value="'+this.is_par[i]+'">'+this.is_name[this.is_par[i]]+'</option>';
			}
			i++;
		}
		html += '</select><input id="val_'+id+'_'+this.it_data_pr[id]+'" name="val_'+id+'_'+this.it_data_pr[id]+'" type="text" value="" >';
		
		html = '<div id="new_par_'+id+'_'+this.it_data_pr[id]+'"> &nbsp; <a href="javascript:et.delpar(\''+id+'\','+this.it_data_pr[id]+')">&nbsp; x &nbsp;</a> &nbsp; ' + html + '</div>';
		$('#it_data_' + id).html( $('#it_data_' + id).html() + html );
	},
	delpar:function(id,num) {
		$('#new_par_'+id+'_'+num+'').remove();
	},
	compl:function() {
		var html = '',html_l = '',html_r = '';
		
		//�������� ������
		this.data = {
			'name' : $('#it_name').val(),
			'img' : $('#it_img').val()
			
		};
		
		//����� �����
		html_l += '<img src="http://img.xcombats.com/i/items/' + this.data.img + '">';
		
		//������ �����
		html_r = '<a href="#">' + this.data.name + '</a>';
		
		//��������
		html = '<table style="border:#A5A5A5 1px solid;" width="100%" border="0" cellspacing="0" cellpadding="0">'+
					'<tr>'+
						'<td valign="top">' + 
						'<table width="100%" border="0" cellspacing="0" cellpadding="0">'+
						  '<tr>'+
							'<td width="20%" align="center" style="border-right:#A5A5A5 1px solid; padding:5px;">'+html_l+'</td>'+
							'<td valign="top" align="left" style="padding-left:3px; padding-bottom:3px; padding-top:7px;">'+html_r+'</td>'+
						  '</tr>'+
						'</table>'+
						'</td>'+
					'</tr>'+
				'</table>';
		
		$('#etitm').html( html );
	}
};
</script>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="50%">
    <!-- loading img -->
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr>
        <td width="50" align="center" valign="top">&nbsp;
        
        </td>
        <td valign="top">
        <form method="post" action="items_editor.php" target="F2">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="200" bgcolor="#FFCCCC">��������</td>
            <td bgcolor="#FFCCCC"><input style="width:100%" type="text" name="it_name" id="it_name"></td>
          </tr>
          <tr>
            <td>�����������</td>
            <td><input style="width:100%" type="text" name="it_img" id="it_img"></td>
          </tr>
          <tr>
            <td bgcolor="#FFCCCC">���</td>
            <td bgcolor="#FFCCCC"><label for="it_type"></label>
              <select name="it_type" id="it_type">
                <option value="0">�������� ���</option>
                <option value="1">����</option>
                <option value="2">�����</option>
                <option value="3">������</option>
                <option value="4">�������</option>
                <option value="5">������ �����</option>
                <option value="6">������� �����</option>
                <option value="7">����</option>
                <option value="8">����</option>
                <option value="9">������</option>
                <option value="10">������</option>
                <option value="11">������</option>
                <option value="12">��������</option>
                <option value="13">���</option>
                <option value="14">������</option>
                <option value="15">�������</option>
                <option value="16">������� ��� ��������</option>
                <option value="17">������� ��� �����</option>
                <option value="18">��� \ ������</option>
                <option value="19">����� \ ������</option>
                <option value="20">����� \ ������</option>
                <option value="21">��� \ ������</option>
                <option value="22">���������� �����</option>
                <option value="23">���</option>
                <option value="24">�������</option>
                <option value="25">���������� \ �������</option>
                <option value="26">�������</option>
                <option value="27">����������� ������</option>
                <option value="28">����� \ ������ \ ����</option>
                <option value="29">��������</option>
                <option value="30">�������</option>
                <option value="31">����</option>
                <option value="32">������</option>
                <option value="33">�����</option>
                <option value="34">������</option>
                <!--<option value="35">�����</option>-->
                <option value="36">��������</option>
                <option value="37">��������</option>
                <option value="38">�������</option>
                <option value="39">������� (������� ��������)</option>
                <option value="40">������� �����</option>
                <option value="41">�����������</option>
                <option value="42">�����</option>
                <option value="43">���� �����</option>
                <option value="44">��������� (� �������)</option>
                <option value="45">�����</option>
                <option value="46">�������</option>
                <option value="47">�������� 1</option>
                <option value="48">�������� 2 (���������)</option>
                <option value="49">���� ��� ���������</option>
                <option value="60">������</option>
                <option value="61">���</option>
                <option value="62">�����</option>
                <option value="63">��������</option>
              </select></td>
          </tr>
          <tr>
            <td>�����</td>
            <td><input style="width:100%" type="text" name="it_massa" id="it_massa"></td>
          </tr>
          <tr>
            <td>������</td>
            <td><input name="it_sudba" type="checkbox" id="it_sudba" value="1"></td>
          </tr>
          <tr>
            <td>��������</td>
            <td><input name="it_art2" type="checkbox" id="it_art3" value="1"></td>
          </tr>
          <tr>
            <td>���������</td>
            <td><input name="it_2h" type="checkbox" id="it_art4" value="1"></td>
          </tr>
          <tr>
            <td>� ��� ����</td>
            <td><input name="it_2too2" type="checkbox" id="it_2too3" value="1"></td>
          </tr>
          <tr>
            <td>���� (��)</td>
            <td><input style="width:100%" type="text" name="it_price1" id="it_price1"></td>
          </tr>
          <tr>
            <td>���� (���)</td>
            <td><input style="width:100%" type="text" name="it_price2" id="it_price2"></td>
          </tr>
          <tr>
            <td>�������������</td>
            <td><input style="width:100%" type="text" name="it_iznos" id="it_iznos"></td>
          </tr>
          <tr>
            <td bgcolor="#FFCCCC">����</td>
            <td bgcolor="#FFCCCC"><select name="it_slot" id="it_slot">
              <option value="0">�� ����������</option>
              <option value="1">����</option>
              <option value="2">������</option>
              <option value="3">������ (������ ����)</option>
              <option value="4">������</option>
              <option value="5">�����</option>
              <option value="6">����</option>
              <option value="7">����</option>
              <option value="8">������</option>
              <option value="9">������</option>
              <option value="10">������</option>
              <option value="13">��������</option>
              <option value="14">������ \ ��� (����� ����)</option>
              <option value="16">������</option>
              <option value="17">�������</option>
              <option value="18">����</option>
              <option value="40">��������</option>
              <option value="51">�����</option>
              <option value="52">�����</option>
              <option value="53">������</option>
              <option value="55">����������� ������</option>
              <option value="56">����� ������</option>
              <option value="59">���� �����</option>
			  </select></td>
          </tr>
          <tr>
            <td>������ ���������</td>
            <td><select name="it_inRazdel" id="it_inRazdel">
              <option value="1">��������������</option>
              <option value="2">��������</option>
              <option value="3">��������</option>
              <option value="6">����</option>
              <option value="4">������</option>
            </select></td>
          </tr>
          <tr>
            <td>����������</td>
            <td><input style="width:100%" type="text" name="it_info" id="it_info"></td>
          </tr>
          <tr>
            <td bgcolor="#FFCCCC">�����������</td>
            <td bgcolor="#FFCCCC"><input style="width:100%" type="text" name="it_group_max" id="it_group_max"></td>
          </tr>
          <tr>
            <td>���������</td>
            <td><input style="width:100%" type="text" name="it_geni" id="it_geni"></td>
          </tr>
          <tr>
            <td>���� �������� (���.)</td>
            <td><input style="width:100%" type="text" name="it_srok" id="it_srok"></td>
          </tr>
          <tr>
            <td>����. ����� (��������)</td>
            <td><input style="width:100%" type="text" name="it_max_text" id="it_max_text"></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC">���.����</td>
            <td bgcolor="#FFFFCC"><input style="width:100%" type="text" name="it_ndata" id="it_ndata"></td>
          </tr>
          <tr>
            <td align="center" valign="middle">ITEMS_MAIN_DATA:</td>
            <td><textarea name="it_data_value" cols="100" rows="10" id="it_data_value"></textarea></td>
          </tr>
          <tr>
            <td>
            <iframe id="F2" width="200" height="30" name="F2" frameborder="0" marginheight="0" marginwidth="0"></iframe>
            </td>
            <td><input type="submit" name="button" id="button" value=" ��������� ������� � ���� "></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </form>
        </td>
      </tr>
    </table>
    <!-- loading img -->
    </td>
    <td valign="top" bgcolor="#C8C8C8">
    <button onClick="et.complData()">������� ����</button>
    <hr>
    
    <b>����������: <a href="javascript:et.newpar('tr')">[+]</a></b>
    <div id="it_data_tr">
    
    </div>
    
    <b>��������� ��: <a href="javascript:et.newpar('add')">[+]</a></b>
    <div id="it_data_add">
    
    </div>
    
    <b>��������: <a href="javascript:et.newpar('sv')">[+]</a></b>
    <div id="it_data_sv">
    
    </div>
    
    <b>���������: <a href="javascript:et.newpar('all')">[+]</a></b>
    <div id="it_data_all">
    
    </div>
    
    <hr>
    &nbsp;
    <div id="etitm"></div>
    </td>
  </tr>
  </table>
</body>
</html>