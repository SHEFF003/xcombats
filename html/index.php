<?
$sp = mysql_query('SELECT * FROM `events_news` WHERE `comment` = "0" AND `delete` = "0" AND `r` = "1" AND `clear` = "0" ORDER BY `id` DESC LIMIT 4');
while( $pl = mysql_fetch_array($sp) ) {
?>
<div class="news-zero">
	<div class="news-one">
		<div class="news-two">
			<div class="news-three">
				<div class="news-home">
<span class="news-home-title">
	<?=$pl['title']?>
</span>
<span class="news-home-text">
	<small>
    <?=$pl['text']?>
    </small>
    <span style="float:right"><b><?=date('d.m.Y',$pl['time'])?></b></span>
</span>
				</div>
            </div>
        </div>
    </div>
</div>
<? } ?>