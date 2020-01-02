<?
$frm = array(
	'html' => '',
	'px'   => 200
);

$rz = 1;
if( $url[2] > 0 ) {
	$rz = $url[2];
}

$rz2 = 0;
if( $url[3] > 0 ) {
	$rz2 = $url[3];
}

$sp = mysql_query('SELECT * FROM `forum_menu` WHERE `parent` = "0" ORDER BY `id` ASC');
while($pl = mysql_fetch_array($sp)) {
	if( $rz == $pl['id'] && $rz2 == 0 ) {
		$frm['html'] .= '<span class="frm-title-sel">&laquo;'.$pl['name'].'&raquo;</span>';
	}else{
		$frm['html'] .= '<a href="http://xcombats.com/forum/'.$pl['id'].'/" class="frm-title">&laquo;'.$pl['name'].'&raquo;</a>';
	}
	$sp2 = mysql_query('SELECT * FROM `forum_menu` WHERE `parent` = "'.$pl['id'].'" ORDER BY `id` ASC');
	while($pl2 = mysql_fetch_array($sp2)) {
		$pl2['name'] = '&gt; '.$pl2['name'].'';
		if( $rz2 == $pl2['id'] ) {
			$frm['html'] .= '<span class="frm-title-sel2">'.$pl2['name'].'</span>';
		}else{
			$frm['html'] .= '<a href="http://xcombats.com/forum/'.$pl['id'].'/'.$pl2['id'].'/" class="frm-title2">'.$pl2['name'].'</a>';
		}
		$frm['px'] += 23;
	}
	$frm['px'] += 23;
}

?>
<div class="lib-ground" style="height:<?=$frm['px']?>px">
<div class="lib-zero">
    <div class="lib-one">
        <div class="top-two">
            <div class="top-three">
                <div class="lib-home">
               		<div class="top-bg">
					<?=$frm['html']?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>