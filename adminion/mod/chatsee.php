<?
if(!defined('GAME'))
{
	die();
}

if(isset($_GET['ajax'])) {
	$r = array();
	
	$sp = mysql_query('SELECT * FROM `chat` WHERE `type` <= 3 ORDER BY `time` DESC LIMIT 100');
	while( $pl = mysql_fetch_array($sp) ) {
		$r['msg'] = '<div style="padding:3px;';
		if( $pl['time'] > time()-120 ) {
			$r['msg'] .= 'background-color:#efefef;';
		}
		$r['msg'] .= '">';
		if( $pl['spam'] > 0 ) {
			$r['msg'] .= '[<span class="private">Спам</span>]';
		}
		if( $pl['delete'] > 0 ) {
			$r['msg'] .= '[<span class="private"><a target="_blank" href="/info/'.$pl['delete'].'">Удалено</a></span>]';
		}
		$r['msg'] .= '<span class="date2" title="'.date('d.m.Y H:i:s',$pl['time']).'">'.date('H:i',$pl['time']).'</span>';
		//
		$r['msg'] .= ' [<a href="/info/'.$pl['login'].'" target="_blank">'.$pl['login'].'</a>]';
		if($pl['to'] != '') {
			if( $pl['type'] == 3 ) {
				$r['msg'] .= '<span class="private"> private ';
			}else{
				$r['msg'] .= ' to ';
			}
			$pl['to'] = explode(',',$pl['to']);
			$i = 0;
			$r['msg'] .= '[';
			while($i < count($pl['to'])) {
				$r['msg'] .= '<a href="/info/'.trim($pl['to'][$i],' ').'" target="_blank">'.trim($pl['to'][$i],' ').'</a>, ';
				$i++;
			}
			$r['msg'] = rtrim($r['msg'],', ');
			$r['msg'] .= ']';
			if( $pl['type'] == 3 ) {
				$r['msg'] .= '</span>';
			}
		}
		//
		$r['msg'] .= '<span style="color:'.$pl['color'].'">'.$pl['text'].'</span>';
		//
		$r['msg'] .= '</div>';
		//
		$r['html'] .= $r['msg'];
		unset($r['msg']);
		//
	}
	
	$r['html'] = iconv('cp1251','utf-8',$r['html']);
	$r['chattime'] = date('d.m.Y H:i:s');
	
	$r = json_encode($r);
	die( $r );
}

if( !isset($_GET['ajax'])) {
?>
<script>
var chatsee = {
	connect:function() {
		$.post('/adminion/?ajax=1&mod=chatsee', {'type':1}, function(data){
			data = $.parseJSON( data );
			$('#chat').html( data.html );	
			$('#chattime').html( data.chattime );
		});
	}
};
</script>
<a href="javascript:void(0);" onclick="chatsee.connect();">Обновить чат</a> <span id="chattime"><?=date('d.m.Y H:i:s')?></span><hr>
<div id="chat"></div>
<script>setInterval('chatsee.connect();',5000);</script>
<? } ?>