<?
if(!defined('GAME'))
{
	die();
}
			$tp = array(
				1  => array('�����',120,220,100),
				2  => array('�������� (�����)',120,40,15),
				3  => array('�������� (������)',120,20,5),
				4  => array('����',60,60,25),
				5  => array('������',60,40,25),
				6  => array('����� ����',60,60,25),
				7  => array('������ ����',60,60,25),
				8  => array('�����',60,80,25),
				9  => array('����',60,40,25),
				10 => array('�������',60,40,25),
				11 => array('������',60,80,25),
				12 => array('��������',60,40,25),
				13 => array('������ �1',20,20,10),
				14 => array('�����',60,20,25),
				15 => array('������',60,20,25),						
				16 => array('�������� ��� ���������� � ���������',244,287,5),						
				17 => array('������ �2',20,20,10),
				18 => array('������ �3',20,20,10)					
			);
			
	
	if(isset($_GET['select'])) {
		
		$img = mysql_fetch_array(mysql_query('SELECT * FROM `reimage` WHERE `id` = "'.mysql_real_escape_string($_GET['select']).'" AND ((`uid` = "'.$u->info['id'].'" AND `clan` = "0") OR (`clan` = "'.$u->info['clan'].'" AND `clan` > 0)) AND `good` > 0 AND `bad` = "0" LIMIT 1'));
		if(isset($img['id'])) {
			
			$rtm = mysql_fetch_array(mysql_query('SELECT * FROM `items_img` WHERE `uid` = "'.$u->info['id'].'" AND `type` = "'.$img['type'].'" LIMIT 1'));
			if(!isset($rtm['id'])) {
				$error = '����������� ���� ������� ������������';
				echo '<meta http-equiv="refresh" content="0; URL=/main.php?galery&error=1">';
				mysql_query('INSERT INTO `items_img` (`uid`,`img_id`,`type`) VALUES ("'.$u->info['id'].'","'.$img['id'].'","'.$img['type'].'")');
			}elseif($rtm['img_id']!=$img['id']){
				$error = '����������� ���� ������� ������������';
				echo '<meta http-equiv="refresh" content="0; URL=/main.php?galery&error=1">';
				mysql_query('UPDATE `items_img` SET `img_id` = "'.$img['id'].'" WHERE `id` = "'.$rtm['id'].'" LIMIT 1');
			}elseif($rtm['img_id']==$img['id']){
				$error = '����������� ���� ������� �����';
				echo '<meta http-equiv="refresh" content="0; URL=/main.php?galery&error=2">';
				mysql_query('UPDATE `items_img` SET `img_id` = "0" WHERE `id` = "'.$rtm['id'].'" LIMIT 1');
			}
		}else{
			$error = '�� �� ������ ���������� ������ �����������';
		}
	}
	
	if(isset($_GET['error'])) {
		if($_GET['error'] == 1) {
			$error = '����������� ���� ������� ������������';
		}else{
			$error = '����������� ���� ������� �����';
		}
	}
		
?>
<style>
.ntr {
	margin:1px;
	padding:1px;
}

.ntr:hover {
	background-color:#BEBEBE;
	padding:0px;
	border:1px solid #9e9e9e;
}

.nsdj {
	margin:1px;
	padding:0px;
	background-color:#DEF0CE;
	border:1px solid #0C6;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top" align="right"><div align="center"><? $usee = $u->getInfoPers($u->info['id'],0,0,1); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } ?></div></td>
  	<td valign="top">
    	<h3>��������� �����������</h3>
       
       	<? 
		if($error != '') {
			echo '&nbsp; &nbsp; <b><font color=red>'.$error.'</font></b><br>';
		}
		
		$i = 1; $j = 0;
		while($i <= count($tp)) {
			
			$html = '';
			
			$k = 0;
			$sp = mysql_query('SELECT * FROM `reimage` WHERE ((`uid` = "'.$u->info['id'].'" AND `clan` = "0") OR (`clan` = "'.$u->info['clan'].'" AND `clan` > 0)) AND `type` = "'.$i.'" AND `good` > 0 AND `bad` = "0"');
			while($pl = mysql_fetch_array($sp)) {
				$html .= '<a href="?galery&select='.$pl['id'].'"><img class="ntr';
				
				if($u->stats['items_img'][$i] == $pl['id'].'.'.$pl['format']) {
					$html .= ' nsdj';
				}
				
				$html .= '" src="http://img.xcombats.com/rimg/r'.$pl['id'].'.'.$pl['format'].'"></a>';
				$k++;
			}
			
			if($html != '') {
		?>
        <div style="padding:20px 20px 20px 20px;border-bottom:1px solid #AEAEAE;">
        	<div style="padding:5px;border-bottom:1px solid #CECECE;"><b><?=$tp[$i][0].' ('.$k.')'?></b></div>
        	<?=$html?>
        </div>
        <? $j++; } $i++; } 
		if($j == 0) {
			echo '<center style="padding:20px;">� ��� ��� ��������� �����������.</center>';
		}
		?>
    </td>
    <td width="175" valign="top"><div style="padding:20px">
    <input value="��������" onclick="location.href='main.php?galery';" type="button" /> <input value="���������" onclick="location.href='main.php?inv=1';" type="button" />
    </div></td>
  </tr>
</table>