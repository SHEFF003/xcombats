<?
$lib = array(
	'html' => '',
	'px'   => 200
);

$rz = 1;

if ($url[2] == 'referal') {
	$rz = 2;
}elseif($url[2] == 'clans') {
	$rz = 3;
}

if( $rz == 1 ) {
	$lib['html'] .= '<span class="top-title-sel">Рейтинг воинов</span>';
}else{
	$lib['html'] .= '<a href="http://xcombats.com/top/warriors/" class="top-title">Рейтинг воинов</a>';
}

if( $rz == 2 ) {
	$lib['html'] .= '<span class="top-title-sel">Рейтинг рефералов</span>';
}else{
	$lib['html'] .= '<a href="http://xcombats.com/top/referal/" class="top-title">Рейтинг рефералов</a>';
}

if( $rz == 3 ) {
	$lib['html'] .= '<span class="top-title-sel">Рейтинг кланов</span>';
}else{
	$lib['html'] .= '<a href="http://xcombats.com/top/clans/" class="top-title">Рейтинг кланов</a>';
}


$lib['px'] += 23*2;

?>
<div class="lib-ground" style="height:<?=$lib['px']?>px">
<div class="lib-zero">
    <div class="lib-one">
        <div class="top-two">
            <div class="top-three">
                <div class="lib-home">
               		<div class="top-bg">
					<?=$lib['html']?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>