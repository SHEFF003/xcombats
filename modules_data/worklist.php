<?
if(!defined('GAME'))
{
	die();
}

if( $u->info['admin'] == 0 ) {
	die('Раздел для администраторов, ты чего здесь забыл?');
}

if(isset($_POST['wtext'])) {
	$txt = $_POST['wtext'];
	$typ = (int)$_POST['select'];
	if( $typ < 0 ) { $typ = 0; }
	if( $typ > 5 ) { $typ = 5; }
	mysql_query('INSERT INTO `worklist` (
		`time`,`uid`,`text`,`complete`,`delete`,`prior`
	) VALUES (
		"'.time().'","'.$u->info['id'].'","'.mysql_real_escape_string($txt).'","0","0","'.mysql_real_escape_string($typ).'"
	)');
	header('location: /main.php?worklist&addgood');
	die();
}elseif(isset($_GET['addgood'])) {
	echo '<div><b><font color="red">Задание успешно добавлено. Теперь осталось его выполнить!</font></b></div>';
}

?>
<table width="100%" cellspacing="0" cellpadding="0">
  <form method="post" action="main.php?inv">
    <tr>
      <td valign="top" align="left"><img src="http://img.xcombats.com/1x1.gif" alt="" width="1" height="5" /><br />
      &nbsp;&nbsp; </td>
      <td align="center"><h3>Текущий план по захвату Мира! :)</h3></td>
      <td align="right">&nbsp;
        <input class="btnnew" type="button" onclick="location.href='main.php?worklist'" name="edit" value="Обновить" />
        <input class="btnnew" type="submit" name="edit" value="Вернуться" />
        <br />
      </td></tr>
    <tr>
      <td align="left"><hr /></td>
      <td align="center"><hr /></td>
      <td align="right"><hr /></td></tr>
  </form>
  <form id="form1" name="form1" method="post" action="">
    <tr>
      <td align="left"></td>
      <td align="left">Добавить новую запись:<br /><textarea name="wtext" style="width:100%;height:100px;"></textarea></td>
      <td align="left"> &nbsp; &nbsp;<input class="btnnew" type="submit" value="Добавить" /><br />
       &nbsp; &nbsp;Приоритет: 
         <label for="select"></label>
         <select name="select" id="select">
           <option value="0" selected="selected">Не важно</option>
           <option value="1">Мелочь</option>
           <option value="2">Недочет</option>
           <option value="3">Баг</option>
           <option value="4">Серьезный баг</option>
           <option value="5">ВАЖНО!!!</option>
         </select>
      </td></tr></form>
  <?
  $sp = mysql_query('SELECT * FROM `worklist` WHERE `delete` = 0 ORDER BY `prior` DESC, `time` ASC');
  while( $pl = mysql_fetch_array($sp) ) {
	  if(isset($_GET['del']) && $pl['id'] == $_GET['del']) {
		  mysql_query('UPDATE `worklist` SET `delete` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	  }else{
		  $pl['text'] = str_replace("\n",'<br>',$pl['text']);
		  $plp = array(
			'<font color=grey>Не важно</font>','<font color=maroon>Не важно</font>','<font color=green>Недочет</font>','<font color=blue>Не важно</font>','<font color=red>Не важно</font>','<b><font color=red>ВАЖНО!!!</font></b>',''
		  );
		  echo '<tr>
		  <td width="230" valign="top" align="left">#'.$pl['id'].' &nbsp; <small style="color:#adadad">|</small> &nbsp; '.date('d.m.Y H:i',$pl['time']).'<br>Приоритет: '.$plp[$pl['prior']].'</td>
		  <td align="left">'.$pl['text'].'</td>
		  <td align="right">&nbsp;<input onclick="location.href=\'/main.php?worklist&del='.$pl['id'].'\';" class="btnnew" type="button" value="X" />
			<br />
		  </td></tr><tr>
		  <td align="left"><hr /></td>
		  <td align="center"><hr /></td>
		  <td align="right"><hr /></td></tr>';
	  }
  }
  ?>
</table>