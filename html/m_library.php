<?
$lib = array(
	'html' => '',
	'px'   => 200
);

$sp = mysql_query('SELECT * FROM `library_menu` WHERE `delete` = 0 ORDER BY `position` ASC');
while($pl = mysql_fetch_array($sp)) {
	if( $pl['type'] == 0 ) {
		$lib['html'] .= '<span class="lib-title">'.$pl['name'].'</span>';
		$lib['px'] += 33;
	}else{
		$lib['html'] .= '<a href="http://xcombats.com'.$pl['url'].'" class="lib-rgo">&bull; '.$pl['name'].'</a>';
		$lib['px'] += 23;
	}
}


?>
<div class="lib-ground" style="height:<?=$lib['px']?>px">
<div class="lib-zero">
    <div class="lib-one">
        <div class="lib-two">
            <div class="lib-three">
                <div class="lib-home">
                	<a href="http://xcombats.com/library/new/" class="enter-post-53-l">&nbsp;</a>
               		<div class="lib-bg">
					<?=$lib['html']?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>