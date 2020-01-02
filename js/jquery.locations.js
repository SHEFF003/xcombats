var speedLoc  = 0;
var sLoc1 = 0;
var sLoc2 = 0;
var tgo  = 0;
var tgol = 0;
var rgo_url = 0;
var rgo_nm = '';

function AjaxlocGoLine(){
	var line = document.getElementById('MoveLine');
	if(line!=undefined){
		prc = 100-Math.floor(tgo/tgol*100);
		sLoc1 = 64/100*prc;
		if(sLoc1<0){
			sLoc1 = 0;
		}
		if(sLoc1>64){
			sLoc1 = 64;
		}
		line.style.width = sLoc1+'px';
		if(tgo>0){			
			tgo -= 1;
			setTimeout('AjaxlocGoLine()',100);
		}else{
			if(rgo_url != 0){
				AjaxGoTo(rgo_url);
			}
		}
		if($('#moveto') != null && $('#moveto') != undefined) {
			if(rgo_nm != '') {
				if( $('#moveto').html() == '' ) {
					$('#moveto').css({'display':'','height':'auto'});
					$('#moveto').html('<div onclick="AjaxgotoLocationCancel();" style="cursor:pointer;padding:5px;">Вы перейдете в: <b>' + rgo_nm + '</b> (<a onclick="AjaxgotoLocationCancel();" href="javascript:void(0)">отмена</a>)</div>');
				}
			}else{
				$('#moveto').css({'display':'none','height':'1px'});
				$('#moveto').html('');
			}
		}
	}
}

function AjaxgoLocal(id,nm) {
	rgo_url = id;
	rgo_nm = nm;
	if($('#moveto') != null && $('#moveto') != undefined && nm != undefined) {
		if(rgo_nm != '') {
			$('#moveto').css({'display':'','height':'auto'});
			$('#moveto').html('<div onclick="AjaxgotoLocationCancel(); return false;" style="cursor:pointer;padding:5px;">Вы перейдете в: <b>' + nm + '</b> (<a onclick="AjaxgotoLocationCancel();" href="javascript:void(0)">отмена</a>)</div>');
			if(sLoc1 == 64) {
				AjaxGoTo(rgo_url);
			}
		}else{
			$('#moveto').css({'display':'none','height':'1px'});
			$('#moveto').html('');
		}
	}
}

function AjaxgotoLocationCancel() {
	rgo_url = 0;
	rgo_nm = '';
	$('#moveto').css({'display':'none','height':'1px'});
	$('#moveto').html('');
}

function AjaxGoTo(url){
	$.ajax({
		url: url,
		cache: false,
		dataType: 'json',
		success: function (json) {
			if(json['status'] == 'success'){
				rgo_url = 0;
				rgo_nm = '';
				tgo = json['location']['tgo'];
				tgo1 = json['location']['tgo1'];
				ViewLocation(json);
				parent.chat.reflesh();
			}else if(json['status'] == 'update'){
				window.location = '/main.php'; 
			}
		}
	});
}

function ViewLocation(json){
	var html = '<div id="ione" class="' + json['location']['bg'] + '">';
	html += '<div class="fl1" style="left:' + json['location']['left'] + 'px;top:' + json['location']['top'] + 'px;" onMouseOver="top.hi(this,\'<div align=right>Вы находитесь в &quot;<b>' + json['location']['name'] + '</b>&quot;</div>\',event,0,1,1,1,\'max-height:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();"></div>';
	for(var i = 0;i < json['goto'].length; i++){
		html += '<div class="' + json['goto'][i]['id'] + ' aFilter"';
		if(json['goto'][i]['params'][0] && json['goto'][i]['params'][0] != 'false'){
			html += ' onMouseOver="top.hi(this,\'<div align=right><b>' + json['goto'][i]['params'][1] + '</b><br>Сейчас в комнате ' + json['goto'][i]['params'][2] + ' чел.</div>\',event,0,1,1,1,\'max-height:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();" onClick="AjaxgoLocal(\'main.php?mAjax=true&loc=' + json['goto'][i]['params'][0] + '\',\'' + json['goto'][i]['params'][1] + '\');" onClick="location=\'main.php?mAjax=true&loc=' + json['goto'][i]['params'][0] + '\';"';
		}else{
			html += ' onMouseOver="top.hi(this,\'<div align=right>' + json['goto'][i]['params'][1] + '</div>\',event,0,1,1,1,\'max-height:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();"';
		}
		html += '></div>';
	}
	html += '<div style="position:absolute;top:0px;z-index:101;right:12px;width:80px;"><table height="15" border="0" cellspacing="0" cellpadding="0"><tr><td id="locobobr" rowspan="3" valign="bottom"><a href="main.php?rnd="><img style="display:block;" src="http://img.xcombats.com/i/move/rel_1.gif" width="15" height="16" title="Обновить" border="0" /></a></td><td colspan="3"><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_462s.gif" width="80" height="4" /></td></tr><tr><td><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_481.gif" width="9" height="8" /></td><td width="64" bgcolor="black"><img src="http://img.xcombats.com/1x1.gif" style="display:block;width:33px;" id="MoveLine" height="8" class="MoveLine" /></td><td><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_50.gif" width="7" height="8" /></td></tr><tr><td colspan="3"><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_tt1_532.gif" width="80" height="4" /></td></tr></table><div id="test"></div></div>';
	html += '</div>';
	$('#ViewLocation').html(html);
//	$('#ViewLocation').html(tmpl("vLocation", json));
	AjaxlocGoLine();
}

(function(){
  var cache = {};
 
  this.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
      cache[str] = cache[str] ||
        tmpl(document.getElementById(str).innerHTML) :
     
      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +
       
        // Introduce the data as local variables using with(){}
        "with(obj){p.push('" +
       
        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("<%").join("\t")
          .replace(/((^|%>)[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)%>/g, "',$1,'")
          .split("\t").join("');")
          .split("%>").join("p.push('")
          .split("\r").join("\\'")
      + "');}return p.join('');");
   
    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();