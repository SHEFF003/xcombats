<?
if(!defined('GAME')){
	die();
}

die('Ошибка, смотри файл');

$dungeon_id = isset($_GET['dungeon_id']) ? intval($_GET['dungeon_id']) : '';

$dungeon_params = mysql_fetch_assoc(mysql_query("SELECT * FROM `dungeon_room` WHERE `id`='".$dungeon_id."'"));
if($_POST['id']){
	$mysql_query = '';
	if($dungeon_id){
		$mysql_query .= "UPDATE `dungeon_room` SET ";
		$mysql_query .= "`id`='".$_POST['id']."',";
		$mysql_query .= "`dungeon_id`='".$_POST['dungeon_id']."',";
		$mysql_query .= "`dungeon_name`='".$_POST['dungeon_name']."' ";
		$mysql_query .= "`dungeon_room`='".$_POST['dungeon_room']."' ";
		$mysql_query .= "`dungeon_tag`='".$_POST['dungeon_tag']."' ";
		$mysql_query .= "`city`='".$_POST['city']."' ";
		$mysql_query .= "`shop`='".$_POST['shop']."' ";
		$mysql_query .= "`roomLeave`='".$_POST['roomLeave']."' ";
		$mysql_query .= "`active`='".$_POST['active']."' ";
		$mysql_query .= "`quest`='".$_POST['quest']."' "; 
		$mysql_query .= "WHERE `id`='".$dungeon_id."'";	
	}else{
		$mysql_query .= "INSERT INTO `dungeon_room` ";
		$mysql_query .= "(`id`,`dungeon_id`,`dungeon_name`,`dungeon_room`,`dungeon_tag`,`shop`,`roomLeave`,`active`,`quest`,`city`)"; 
		$mysql_query .= " VALUES ";
		$mysql_query .= "('".$_POST['id']."','".$_POST['dungeon_id']."','".$_POST['dungeon_name']."','".$_POST['dungeon_room']."','".$_POST['dungeon_tag']."','".$_POST['shop']."','".$_POST['roomLeave']."','".$_POST['active']."','".$_POST['quest']."','".$_POST['city']."');";
	}	
	mysql_query($mysql_query);
	die("<script>window.location = 'index.php?mod=dungeon_list';</script>");
}
?>
<div align="left">
  <h3 style="text-align:left;"><?php echo $dungeon_params['id'] ? 'Изменить' : 'Добавить'; ?> пещеру</h3>
</div>
<form method="post" action="">
  <table border="0" cellpadding="0" cellspacing="1">
    <tbody>
      <tr>
        <td><font color="red">*</font>ID подземелья: &nbsp; </td>
        <td><input name="dungeon_id" type="text" value="<?php echo $dungeon_params['dungeon_id']; ?>" size="5" maxlength="255"></td>
      </tr>
      <tr>
        <td><font color="red">*</font>Название пещеры: &nbsp; </td>
        <td><input name="dungeon_name" type="text" value="<?php echo $dungeon_params['dungeon_name']; ?>" size="30" maxlength="255"></td>
      </tr>
      <tr>
        <td><font color="red">*</font>Город: &nbsp; </td>
        <td><input name="city" type="text" value="<?php echo $dungeon_params['city']; ?>" size="30" maxlength="255"></td>
      </tr>
      <tr>
        <td><font color="red">*</font>Тэг: &nbsp; </td>
        <td><input name="dungeon_tag" type="text" value="<?php echo $dungeon_params['dungeon_tag']; ?>" size="30" maxlength="255"></td>
      </tr>
      <tr>
        <td><font color="red">*</font>ID локации (вход в подземелье): &nbsp; </td>
		<? if($dungeon_params['id'] != 0) $room = mysql_fetch_assoc(mysql_query("SELECT id,name FROM `room` WHERE `id`='".$dungeon_params['id']."'")); else $room['name']=''; ?>
        <td><input name="id" type="text" value="<?php echo $dungeon_params['id']; ?>" size="5" maxlength="255"><i style="font-size:11px;color: brown;">&nbsp; <?=$room['name']?></i></td>
      </tr>
      <tr>
        <td><font color="red">*</font>ID локации (подземелье): &nbsp; </td>
		<? if($dungeon_params['dungeon_room'] != 0) $room = mysql_fetch_assoc(mysql_query("SELECT id,name FROM `room` WHERE `id`='".$dungeon_params['dungeon_room']."'")); else $room['name']=''; ?>
        <td><input name="dungeon_room" type="text" value="<?php echo $dungeon_params['dungeon_room']; ?>" size="5" maxlength="255"><i style="font-size:11px;color: brown;">&nbsp; <?=$room['name']?></i></td>
      </tr>
      <tr>
        <td>ID Рыцарского магазина: &nbsp; </td>
		<? if($dungeon_params['shop'] != 0) $room = mysql_fetch_assoc(mysql_query("SELECT id,name FROM `room` WHERE `id`='".$dungeon_params['shop']."'")); else $room['name']=''; ?>
        <td><input name="shop" type="text" value="<?php echo $dungeon_params['shop']; ?>" size="5" maxlength="255"><i style="font-size:11px;color: brown;">&nbsp; <?=$room['name']?></i></td>
      </tr>
      <tr>
        <td>ID комнаты для выхода: &nbsp; </td>
		<? if($dungeon_params['roomLeave'] != 0) $room = mysql_fetch_assoc(mysql_query("SELECT id,name FROM `room` WHERE `id`='".$dungeon_params['roomLeave']."'")); else $room['name']=''; ?>
        <td><input name="roomLeave" type="text" value="<?php echo $dungeon_params['roomLeave']; ?>" size="5" maxlength="255"></td>
      </tr>
      <tr>
        <td>Активна: &nbsp; </td>
        <td><select name="active"><option <? if($dungeon_params['active']=='1') echo' selected="selected" '; ?>value="1">Да</option><option <? if($dungeon_params['active']!='1') echo' selected="selected" ';?>value="0">Нет</option></select></td>
      </tr>
      <tr>
        <td>Включены квесты: &nbsp; </td>
        <td><select name="quest"><option <? if($dungeon_params['quest']=='1') echo' selected="selected" '; ?>value="1">Да</option><option <? if($dungeon_params['quest']!='1') echo' selected="selected" ';?>value="0">Нет</option></select></td>
      </tr>
    </tbody>
  </table>
  <p></p>
  <input name="submit" type="submit" value="Сохранить" style="width: 150px"/>
  <input name="cancel" type="submit" onclick="document.location='index.php?mod=dungeon_list'; return false;" value="Отмена" />
  <p><font color="red">*</font> - Обязательные поля </p>
</form>
