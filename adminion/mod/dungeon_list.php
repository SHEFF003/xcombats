<?
if(!defined('GAME')){
	die();
}
if(isset($_GET['delete_dungeon_id'])){
	$delete_dungeon_id = intval($_GET['delete_dungeon_id']);
	mysql_query("DELETE FROM `dungeon_room` WHERE `id`='".$delete_dungeon_id."'");
	die("<script>window.location = 'index.php?mod=dungeon_list';</script>");
}


$Query = mysql_query("SELECT * FROM dungeon_room ORDER BY  active, dungeon_id ASC");
$dungeon_list = '';
while($row = mysql_fetch_assoc($Query)){
	$dungeon_list .= '<tr>
	<td align="center">' . $row['dungeon_id'] . '</td>
	<td><a href="?mod=dungeon_edit&dungeon_id=' . $row['id'] . '">' . $row['dungeon_name'] . '</a></td>
	<td align="center">' . $row['id'] . '</td>
	<td align="center">' . ( $row['dungeon_room'] == 0 ? '' : $row['dungeon_room']) . '</td>
	<td align="center">' . ( $row['shop'] == 0 ? '' : $row['shop']) . '</td>
	<td>' . ( $row['active'] == 1 ? '<span style="color:green">���</span>' : '<span style="color:brown">����</span>') . '</td>
	<td>' . ( $row['quest'] == 1 ? '<span style="color:green">���</span>' : '<span style="color:brown">����</span>'). '</td>
    <td><a href="?mod=dungeon_list&delete_dungeon_id=' . $row['id'] . '">�������</a></td>
    <td><a href="?mod=dungeon_edit&dungeon_id=' . $row['id'] . '">��������</a></td>
    <td><a target="_blank" href="?mod=dungeon&r=' . $row['dungeon_id'] . '">�����</a></td>
  </tr>';
}
?>
<div align="left">
  <h3 style="text-align:left;">������ �����</h3>
</div>
<div style="margin-left:8px; margin-bottom:15px"> ������:
  <table class="tblbr2" border="1" cellspacing="0" cellpadding="5" bordercolor="#C1E1EE">
    <tr>
      <td bgcolor="#C0C2C0"><div align="center"><strong>������</strong></div></td>
      <td bgcolor="#C0C2C0"><div align="center"><strong>�������� ������</strong></div></td>
      <td bgcolor="#C0C2C0"><div align="center"><strong>�������<br/>�����</strong></div></td>
      <td bgcolor="#C0C2C0"><div align="center"><strong>�������<br/>������</strong></div></td>
	  <td bgcolor="#C0C2C0"><div align="center"><strong>���������<br/>�������</strong></div></td>
      <td bgcolor="#C0C2C0"><div align="center"><strong>������</strong></div></td>
      <td bgcolor="#C0C2C0"><div align="center"><strong>������</strong></div></td>
	  
      <td bgcolor="#C0C2C0" colspan="3"><div align="center">�����������</div></td>
    </tr>
    <?php echo $dungeon_list; ?>
  </table>
</div>
<a href="../../���������/adminion/mod/?mod=dungeon_edit" title="�������� ������">�������� ������</a>