<?
if(!defined('GAME'))
{
	die();
}
include('_incl_data/class/dialog.class.php');
$dialog->start($_GET['talk']);
?>
<style>
body {
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="300" valign="top"><div align="left"><? echo $dialog->youInfo; ?></div></td>
    <td valign="top"><div align="center"><H3><? echo $dialog->title; ?></H3></div>
    <p><?
	if($u->info['admin'] > 0) {
		$dpages = '';
		if(isset($_GET['add_new_page'])) {
			$dsp = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_dlg` WHERE `id_dg` = "'.mysql_real_escape_string((int)$_GET['talk']).'" AND `qid` = "0" ORDER BY `page` DESC LIMIT 1'));
			$dsp = ($dsp['page']+1);
			mysql_query('INSERT INTO `dungeon_dlg` (`id_dg`,`page`,`qid`,`type`) VALUES ("'.mysql_real_escape_string((int)$_GET['talk']).'","'.mysql_real_escape_string((int)$dsp).'","0","1")');
			die('<script>location.href="main.php?talk='.((int)$_GET['talk']).'&act='.((int)$_GET['act']).'"</script>');
		}
		$dsp = mysql_query('SELECT * FROM `dungeon_dlg` WHERE `id_dg` = "'.mysql_real_escape_string((int)$_GET['talk']).'" AND `qid` = "0"');
		while($dpl = mysql_fetch_array($dsp)) {
			$dact = mysql_fetch_array(mysql_query('SELECT `id` FROM `dungeon_dlg` WHERE `id_dg` = "'.mysql_real_escape_string((int)$_GET['talk']).'" AND `qid` > "0" AND `action` LIKE "%go|'.$dpl['page'].'%" LIMIT 1'));
			if($dact['id'] == $_GET['act']) {
				$dpages .= '[';
			}
			$dpages .= '<a href="main.php?talk='.((int)$_GET['talk']).'&act='.$dact['id'].'">'.$dpl['page'].'</a>';
			if($dact['id'] == $_GET['act']) {
				$dpages .= ']';
			}
			$dpages .= ' ';
		}
		$dpages .= ' &nbsp; <a href="main.php?talk='.((int)$_GET['talk']).'&act='.((int)$_GET['act']).'&add_new_page=1" title="Добавить новую страницу"><small>[+]</small></a>';
		echo '<small style="float:right;border-bottom:1px solid black;padding-bottom:2px;"><a href="javascript:window.open(\'http://xcombats.com/quest_dlg_edit.php?pid='.((int)$dialog->pg).'\',\'winEdi1\',\'width=850,height=400,top=400,left=500,resizable=no,scrollbars=yes,status=no\');" target="_blank">Редактировать текст</a> &nbsp; | &nbsp; Страницы: '.$dpages.'</small><br><br>';	
	}
	echo $dialog->dText; ?></p>
    <p><? echo $dialog->aText; ?></p></td>
    <td width="300" valign="top"><div align="right"><? echo $dialog->botInfo; ?></div></td>
  </tr>
</table>
