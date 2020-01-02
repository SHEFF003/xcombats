<?
$rz = 1;

if($url[2] == 'clans') {
	$rz = 3;
}elseif($url[2] == 'referal') {
	$rz = 2;
}
?>

<h3>Рейтинг <? if( $rz == 3 ) { echo 'кланов'; }elseif( $rz == 2 ) { echo 'рефералов'; }else{ echo 'воинов'; }?></h3>
<div style="padding-left:30px;padding-right:30px;">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr>
      <td colspan="5"></td>
    </tr>
    <tr>
      <td align="center"><b>№</b></td>
      <td><b></b></td>
      <td align="center"><b>рейтинг </b></td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td width="8%"></td>
      <td width="60%"></td>
      <td width="32%"></td>
    </tr>
      <?php
	  $r = '';
	  $i = 1;
	  if( $rz == 1 ) {
		  $sp = mysql_query('SELECT `rep`.* FROM `rep` LEFT JOIN `users` ON `users`.`id` = `rep`.`id` WHERE `rep`.`rep3` > 0 AND `users`.`ip` != ""  AND `users`.`banned` = 0 AND `users`.`online` > "'.(time()-14*86400).'" AND `users`.`ip` != "0" ORDER BY `rep`.`rep3` DESC LIMIT 100');
		  while($pl = mysql_fetch_array($sp)) {
			  $r .= '<tr><td align="center">'.$i.'</td><td>'.$u->microLogin($pl['id'],1).'</td><td align="center">'.$pl['rep3'].'</td><td></td>';
			  $i++;
		  }
	  }elseif( $rz == 3 ) {
		  $sp = mysql_query('SELECT * FROM `clan` WHERE `exp` > 0 ORDER BY `exp` DESC LIMIT 100');
		  while($pl = mysql_fetch_array($sp)) {
			  $r .= '<tr><td align="center">'.$i.'</td><td><img src="http://img.xcombats.com/i/align/align'.$pl['align'].'.gif" width=12 height=15><img width=24 height=15 src="http://img.xcombats.com/i/clan/'.$pl['id'].'.gif"> <small>Ур. '.$pl['level'].'</small> <a target="_blank" href="http://xcombats.com/clans_info/'.$pl['name'].'">'.$pl['name'].'</a></td><td align="center">'.$pl['exp'].'</td><td></td>';
			  $i++;
		  }
	  }elseif( $rz == 2 ) {
		  $rf_i = array();
		  $rf1 = array();
		  $rf2 = array();
		  $mas = 0;
		  $sp =  mysql_query('SELECT `host_reg`,`level` FROM `users` WHERE `host_reg` > 0 AND `banned` = 0');
		  while( $pl = mysql_fetch_array($sp)) {
			 if( !isset($rf1[$pl['host_reg']])) {
				 $usr = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.$pl['host_reg'].'" LIMIT 1'));
				 if( isset($usr['id']) ) {
				 	$rf_i[] = $pl['host_reg'];
				 	$rf1[$pl['host_reg']] = 0;
				 	$rf2[$pl['host_reg']] = 0;
				 }
			 }
			 if( isset($usr['id']) ) {
				 $mas++;
			 	$rf1[$pl['host_reg']]++;
			 	$rf2[$pl['host_reg']] += $pl['level']; 
			 }
		  }
		  asort($rf2);
		  $rk = array_keys($rf2);
		  $i = 0;	
		  while( $i < count( $rk ) ) {
			 $r = '<tr><td align="center">'.(count( $rk )-$i).'</td><td>'.$u->microLogin($rk[$i],1).'</td><td align="center">'.$rf2[$rk[$i]].' ( '.$rf1[$rk[$i]].' чел. )</td><td></td>'.$r;
			 $i++; 
		  }
		  $r .= 'Активных рефералов: '.$mas.' чел.<hr>';
	  }
	  echo $r;
	  ?>
    <tr>
      <td colspan="4"><img src="http://top.xcombats.com/images/1x1.gif" width="1" height="1" border="0" alt="" /></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="5"><table width="100%"  border="0" cellpadding="0" cellspacing="0" background="http://top.xcombats.com/images/ram12_34.gif">
        <tr>
          <td align="left" scope="col"><img src="http://top.xcombats.com/images/ram12_33.gif" width="12" height="11" /></td>
          <td scope="col"></td>
          <td width="18" align="right" scope="col"><img src="http://top.xcombats.com/images/ram12_35.gif" width="13" height="11" /></td>
        </tr>
      </table>
      Рейтинг постоянно изменяется.
      </td>
    </tr>
  </table>
</div>