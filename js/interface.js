$(document).ready(function(){
    $('a#copy-description').zclip({
        path:'js/ZeroClipboard.swf',
        copy:$('p#description').text()
    });
    $('a#copy-dynamic').zclip({
        path:'js/ZeroClipboard.swf',
        copy:function(){return $('input#dynamic').val();}
    });
});

function bodyLoaded()
{
	top.recounter();
	//генерируем смайлики
	var i = 0, j = '';
	while(i!=-1)
	{
		if(top.sml[i]!=undefined)
		{
			j += '<img style="cursor:pointer" onclick="chat.addSmile(\''+top.sml[i]+'\')" src="http://'+c['img']+'/i/smile/'+top.sml[i]+'.gif" width="'+top.sml[i+1]+'" height="'+top.sml[i+2]+'" title=":'+top.sml[i]+':" /> ';
		}else{
			i = -4;
		}
		i += 3;
	}
	$('#smilesDiv').html(j);
	delete i;
	delete j;
}

function startEngine()
{
	//стандартные настройки
	if($.cookie('chatCfg0')==undefined)
	{
		$.cookie('chatCfg0',2,{expires:320});
		$.cookie('chatCfg1','Black',{expires:320});
	}
	
	$('#reline1').mousedown(function(){resizeStart()});
	$('#reline2').mousedown(function(){nresizeStart()});
	$(window).resize(function(){resizeFinish()});
	resizeFinish();
	//Добавляем действия
	var i = 0;
	while(i!=-1)
	{
		if($('#chcf'+i).attr('id')!=undefined)
		{
			$('#chcf'+i).change(function(){saveChatConfig();if(this.id=='chcf10'){chat.reflesh()}});
			if(i>1)
			{
				if($.cookie('chatCfg'+i)==1)
				{
					$('#chcf'+i).attr('checked',true);
					if(i==11)
					{
						chat.globalMsg = 1;
					}
				}else if($.cookie('chatCfg'+i)==0)
				{
					$('#chcf'+i).attr('checked',false);					
				}
			}
		}else{
			i = -2;
		}
		i++;
	}
	//загрузка сохраненных настроек
	if ($('#chcf0').val().length >= 1) {
		srcv = $.cookie('chatCfg0');
		$('#chcf0 option:selected').each(function(id){
			$(this).removeAttr('selected');
		});
		$('#chcf0 option').each(function(){
			if ($(this).val().indexOf(srcv) > -1) {
				$(this).attr('selected','yes');
			}
		});
	}
	if ($('#chcf8').val().length >= 1) {
		srcv = $.cookie('chatCfg8');
		$('#chcf8 option:selected').each(function(id){
			$(this).removeAttr('selected');
		});
		$('#chcf8 option').each(function(){
			if ($(this).val().indexOf(srcv) > -1) {
				$(this).attr('selected','yes');
			}
		});
	}
	/* if ($('#chcf1').val().length >= 1) {
		srcv = $.cookie('chatCfg1');
		$('#chcf1 option:selected').each(function(id){
			$(this).removeAttr('selected');
		});
		$('#chcf1 option').each(function(){
			if ($(this).val().indexOf(srcv) > -1) {
				$(this).attr('selected','yes');
			}
		});
	}
	*/
}

function saveChatConfig()
{
	var i = 0;
	while(i!=-1)
	{
		if($('#chcf'+i).attr('id')!=undefined)
		{
			if(i<2 || i==8)
			{
				$.cookie('chatCfg'+i,$('#chcf'+i).val(),{expires:320});
			}else{
				if($('#chcf'+i).attr('checked')==true)
				{
					$.cookie('chatCfg'+i,1,{expires:320});
				}else{
					$.cookie('chatCfg'+i,0,{expires:320});
				}
			}
		}else{
			i = -2;
		}
		i++;
	}
	if($('#chcf11').attr('checked')==true)
	{
		$('#globalMode').css({'display':''});
		if(chat.globalMsg==0)
		{
			//alert('У Вас включен "Глобальный чат", одно сообщение стоит 0.05 кр.');
		}
		chat.globalMsg = 1;		
	}else{
		$('#globalMode').css({'display':'none'});
		chat.globalMsg = 0;
	}
	chat.genchatData(null);
}

function unpx(v)
{
	return Number(v.replace('px',''));
}

/* RESIZE LINE 1 */
function resizeStart()
{
	$('resize1').css({'z-index':1003});
	$('#upbox').css({'display':'block'});
	$('#upbox').mousemove(function(event){resizeNow(event);resizeStop();});
	$('#reline1').mouseup(function(){resizeStop2()});
	$('#upbox').mouseup(function(){resizeStop2()});
}

function resizeNow(e)
{
	if(e.clientY>31 && e.clientY<$(window).height()-40)
	{
		$('#reline1').css({'top':e.clientY});
	}
}

function resizeStop() {
	j = 35;
	if($.browser.msie==true){ j += 2;}	
	$('#chat').css({'height':Math.round(($(window).height()-unpx($('#reline1').css('top'))-j)/$(window).height()*100)+'%'});	
	resizeFinish();
}

function resizeStop2()
{
	$('#upbox').css({'display':'none'});
	j = 35;
	if($.browser.msie==true)
	{
		j += 2;
	}
	
	$('#chat').css({'height':Math.round(($(window).height()-unpx($('#reline1').css('top'))-j)/$(window).height()*100)+'%'});
	
	resizeFinish();
	$('resize1').css({'z-index':1001});
	$('#upbox').unbind('mouseup');
	$('#upbox').unbind('mousemove');
	$('#reline1').unbind('mouseup');
}

/* RESIZE LINE 2 */
function nresizeStart()
{
	$('resize2').css({'z-index':1003});
	$('#upbox').css({'display':'block'});
	$('#upbox').mousemove(function(event){nresizeNow(event);nresizeStop();});
	$('#reline2').mouseup(function(){nresizeStop2()});
	$('#upbox').mouseup(function(){nresizeStop2()});
}

function nresizeNow(e)
{
	if(e.clientX>40 && e.clientX<$(window).width()-40)
	{
		$('#reline2').css({'left':e.clientX});
	}
}

function nresizeStop() {
	j = 18;
	if($.browser.msie==true){ j += 2;	}
	$('#online').css({'width':Math.round(($(window).width()-unpx($('#reline2').css('left'))-j)/$(window).width()*100)+'%'});
	resizeFinish();
}

function nresizeStop2()
{
	$('#upbox').css({'display':'none'});
	//j = 18;
	//if($.browser.msie==true)
	//{
	//	j += 2;
	//}
	//$('#online').css({'width':Math.round(($(window).width()-unpx($('#reline2').css('left'))-j)/$(window).width()*100)+'%'});

	resizeFinish();
	$('resize2').css({'z-index':1000});
	$('#upbox').unbind('mouseup');
	$('#upbox').unbind('mousemove');
	$('#resize2').unbind('mouseup');
}

function resizeFinish()
{
	j = 30; i = 3;
	if($.browser.msie==true)
	{
		j += 1;
		i += 2;
	}
	
	$('#main').css({'width':'20px','height':'20px'});
	$('#touchmain').css({'width':'20px','height':'20px'});
	
	$('#chat_list').css({'width':'20px','height':'20px'});
	$('#online_list').css({'width':'20px','height':'20px'});
		
	$('#chat_list').css({'width':($('#chat').width()-$('#online').width()-4),'height':$('#online').height()});
	$('#online_list').css({'width':$('#online').width()-5,'height':$('#online').height()});
	$('#main').css({'height':($(window).height()-j-$('#chat_block').height()-42),'width':($(window).width()-19)});
	$('#touchmain').css({'height':($(window).height()-j-$('#chat_block').height()-42),'width':($(window).width()-19)});
	$('#reline1').css({'top':($(window).height()-j-$('#chat').height()-6)+'px'});
	$('#reline2').css({'left':($(window).width()-i-$('#online').width()-9)+'px','height':($('#chat').height())+'px','top':($('#main_td').height()+j+2)+'px'});
}

function showtable(id)
{
	hidesel(id);
	hidemenu(0);
	document.getElementById('menu'+id).style.display = '';
}

function hidemenu (time) {
	for (var i=1;i<=4;i++) {
		document.getElementById('menu'+i).style.display = 'none';
	}	
}

function hidesel (id) {
	for (var i=1;i<=5;i++) {
		if (i!=id) {document.getElementById('el'+i).style.backgroundColor='';document.getElementById('el'+i).style.color='';}
	}	
}

function chconf()
{
	if($('#chconfig').css('display')=='none')
	{
		$('#brnchcf').attr('class','db cp chatBtn18_2');
		$('#chconfig').css('display','block');
	}else{
		$('#chconfig').hide("fast");
		$('#brnchcf').attr('class','db cp chatBtn18_1');
	}
}

var doTest;
function MBcMenu()
{
	T_cm = setTimeout("if(doTest){ top.infoMenuClose(1); clearTimeout(T_cm);}", 100);
}

function getNameBrouser()
{
 var ua = navigator.userAgent.toLowerCase();
 if (ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1 && ua.indexOf("webtv") == -1) {
   return "msie"
 }
 if (ua.indexOf("opera") != -1) {
   return "opera"
 }
 if (ua.indexOf("gecko") != -1) {
   return "gecko";
 }
 if (ua.indexOf("safari") != -1) {
   return "safari";
 }
 if (ua.indexOf("konqueror") != -1) {
   return "konqueror";
 }
 return "unknown";
} 

function mousePageXY(e)
{
	var x = 0, y = 0;
	if (e.pageX || e.pageY)
	{
		x = e.pageX;
		y = e.pageY;
	}  else if (e.clientX || e.clientY) {
		x = e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft) - document.documentElement.clientLeft;
		y = e.clientY + (document.documentElement.scrollTop || document.body.scrollTop) - document.documentElement.clientTop;
	}
	return {"x":x, "y":y};
}

function infoMenu(u,e,f)
{
	if (!e) e = window.event;
	var d = top.document.getElementById('persmenu');
	var d2  = top.document.getElementById('upbox');
	if(d!=undefined)
	{
		var dptop = -5;
		var dpleft = -5;
		var dp = top;
		var s = d.style;
		d2.style.display = '';
		d.className = 'topusermenu';
		s.display = ''; 
		var obtop = mousePageXY(e)['y']+dptop;
		if(getNameBrouser()=='msie')
		{
			obtop -= dp.document.body.scrollTop;
		}else{
			obtop -= dp.document.documentElement.scrollTop+dp.document.body.scrollTop;	
		}
		var obleft = mousePageXY(e)['x']+dpleft;
		d.style.top = obtop+'px';
		d.style.left = obleft+'px';
		var hmmmt = '<DIV onmouseover="top.doTest = false;" onmouseout="top.doTest = true; top.MBcMenu();">'
						+'<div onClick="top.chat.addto(\''+u+'\',\'to\'); infoMenuClose(4);" class="topusermenuBtn">TO</div>'
						+'<div onClick="top.chat.addto(\''+u+'\',\'private\'); infoMenuClose(4);" class="topusermenuBtn">PRIVATE</div>'
						+'<div onClick="window.open(\'info/'+u+'\'); infoMenuClose(4);" class="topusermenuBtn">INFO</div>'
						+'<div onClick="top.infoMenuClose(4);" class="topusermenuBtn"><div id="d_clip_button">COPY</div></div>';
					if( top.iusrno[u] == undefined || top.iusrno[u] == 0 ) {	
						hmmmt = hmmmt+'<div onClick="top.ignoreUser(\'' + u + '\'); infoMenuClose(4);" class="topusermenuBtn">IGNORE</div>';
					}else{
						hmmmt = hmmmt+'<div onClick="top.ignoreUser(\'' + u + '\'); infoMenuClose(4);" class="topusermenuBtn">- IGNORE</div>';
					}
					hmmmt = hmmmt+'</DIV>';	
		d.innerHTML = hmmmt;
					
		var bdx = document.body.clientWidth;
		var bdy = document.body.clientHeight;
		var obx = d.clientWidth;
		var oby = d.clientHeight;
		var ots = bdy-obtop-oby;
		var ols = bdx-obleft-obx;
		if(ots<10)
		{
			obtop = bdy-10-oby;
			d.style.top = obtop+'px';
		}
		if(ols<10)
		{
			obleft = bdx-10-obx;
			d.style.left = obleft+'px';
		}
		$("#d_clip_button").zclip({		
				path: "js/ZeroClipboard.swf",		
			copy: function(){		
				return u;		
			}		
		});
	}
}

function infoMenuClose(id)
{
	var d = top.document.getElementById('persmenu');
	var d2  = top.document.getElementById('upbox');
	if(d!=undefined)
	{
		if(id>0 && id<5)
		{
			d.className = 'topusermenu inviseMen'+id;
			T_mn = setTimeout('top.infoMenuClose('+(id+1)+')',30);
		}else if(id==5)
		{
			var s = d.style;
			s.display = 'none';
			d2.style.display = 'none';
			d.innerHTML = '';
			d.className = '';
			d.style.left = '-1000px';
			clearTimeout(T_mn);	
		}
	}
}

function infoMenuMy(u,e,f,dtm)
{
	if (!e) e = window.event;
	var d = top.document.getElementById('persmenu');
	var d2  = top.document.getElementById('upbox');
	if(d!=undefined)
	{
		var dptop = -5;
		var dpleft = -5;
		var dp = top;
		var s = d.style;
		d2.style.display = '';
		d.className = 'topusermenu';
		s.display = ''; 
		var obtop = mousePageXY(e)['y']+dptop;
		if(getNameBrouser()=='msie')
		{
			obtop -= dp.document.body.scrollTop;
		}else{
			obtop -= dp.document.documentElement.scrollTop+dp.document.body.scrollTop;	
		}
		var obleft = mousePageXY(e)['x']+dpleft;
		d.style.top = obtop+'px';
		d.style.left = obleft+'px';
		var hmmmt = '<DIV onmouseover="top.doTest = false;" onmouseout="top.doTest = true; top.MBcMenu();">';
		var i = 0;
		while( i != -1 ) {
			if(dtm[i] != undefined) {
				hmmmt += '<div onClick="'+dtm[i][0]+' infoMenuClose(4);" class="topusermenuBtn">'+dtm[i][1]+'</div>';
				i++;
			}else{
				i = -1;
			}
		}
		hmmmt = hmmmt+'</DIV>';	
		d.innerHTML = hmmmt;
					
		var bdx = document.body.clientWidth;
		var bdy = document.body.clientHeight;
		var obx = d.clientWidth;
		var oby = d.clientHeight;
		var ots = bdy-obtop-oby;
		var ols = bdx-obleft-obx;
		if(ots<10)
		{
			obtop = bdy-10-oby;
			d.style.top = obtop+'px';
		}
		if(ols<10)
		{
			obleft = bdx-10-obx;
			d.style.left = obleft+'px';
		}
		$("#d_clip_button").zclip({		
				path: "js/ZeroClipboard.swf",		
			copy: function(){		
				return u;		
			}		
		});
	}
}

//Окна
var win = {
	winc:{}, //координаты окон
	wsdr:null,
	scor:{}, //начальные координаты
	openw:function(id,title,text,date,type,style){
		if($('#win_'+id).attr('id')==undefined)
		{
			//Создаем новое окно
			this.add(id,title,text,date,type,1,'');
		}
	},
	WstartDrag:function(id){
		$('#wupbox').css({'display':'block','cursor':'move'});	
		this.wsdr = id;
		$('.w1').css({'z-index':1102});		
		$('#win_'+id).css({'z-index':1103});
		delete cm;
	},
	WmoveDrag:function(e){
		//Сохраняем начальные координаты
		var x = mousePageXY(e)['x'],y = mousePageXY(e)['y'];		
		if(this.scor.x==undefined){
			this.scor.x = x;
			this.scor.y = y;
			this.scor.x2 = unpx($('#win_'+this.wsdr).css('left'));
			this.scor.y2 = unpx($('#win_'+this.wsdr).css('top'));
		}		
		x = x-this.scor.x;
		y = y-this.scor.y;		
		x += this.scor.x2;
		y += this.scor.y2;		
		if(x < 9){ x = 9; }
		if(x + $('#win_'+this.wsdr).width() > $(window).width() - 9 ){ x = $(window).width() - 9 - $('#win_'+this.wsdr).width(); }		
		if(y<35){ y = 35; }
		if(y + $('#win_'+this.wsdr).height() > $(window).height() - 35 ){ y = $(window).height() - 35 - $('#win_'+this.wsdr).height(); }
		$('#win_'+this.wsdr).css({'top':y+'px','left':x+'px'});
	},
	WstopDrag:function(){
		$('#wupbox').css({'display':'none','cursor':'move'});
		this.wsdr = null;
		this.scor = {};
	},
	add:function(id,title,text,date,type,style,css){
		var nw = '';
		if($('#win_'+id).attr('id') == undefined){	
			var acts = {};
			
			if(date.usewin != undefined){
				acts[0] = 'onmouseup="'+date.usewin+'"';
			}
			
			if(date.closewin != undefined) {
				acts[9] = date.closewin;
			}
			
			//нижняя часть
			if(date.n != undefined){
				text += '<div style="margin-left:11px;">'+date.n+'</div>';
			}
			var kyps = ['',''];
			//Вывод главных данных
			if(type==0){
				nw = text;
			}else if(type==1){
				//Просто вывод данных
				nw = text;
			}else if(type==2){
				//Да \ Нет
				nw = '<div>'+text+'</div><div style="padding:5px"><div style="float:left;padding-bottom:3px"><button onClick="'+date.a1+';win.closew(\''+id+'\');'+acts[9]+'" class="btnnew" id="winyesbtnfox'+id+'" style="width:100px">Да</button></div><div style="float:right"><button class="btnnew" onClick="'+date.a2+';win.closew(\''+id+'\')" style="width:100px">Нет</button></div><br></div>';
				kyps[0] = ''+date.a1+';top.win.closew(\\\''+id+'\\\');'+acts[9]+'top.win.addaction(0,\\\'\\\');';
			}else if(type==3){
				//Да \ Нет , изображения
				nw = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>'+text+'</td><td width="40" align="center" valign="middle"><img style="margin-top:5px;cursor:pointer" onClick="'+date.a1+';win.closew(\''+id+'\');" src="http://'+c.img+'/i/b__ok.gif" width="25" height="18"><br><img onClick="win.closew(\''+id+'\')" style="cursor:pointer" src="http://'+c.img+'/i/b__cancel.jpg" width="25" height="18"></td></tr></table>';
				kyps[0] = ''+date.a1+';top.win.closew(\\\''+id+'\\\');'+acts[9]+'top.win.addaction(0,\\\'\\\');';
			}else if(type==4){
				//Тройной блок
				nw = text[0];
			}
			
			//Если есть вторая информация
			if(date.d!=undefined){
				nw = nw+date.d;
			}
			
			nw = '<div style="margin:2px;'+css+'">'+nw+'</div>';
			
			//Заголовок окна
			if(title != ''){
				nw = '<div class="wi'+style+'s10" onselectstart="return false">'+
					 '<table width="100%" border="0" cellspacing="0" cellpadding="0">'+
					 '<tr>'+
				     '<td rowspan="2" style="cursor:move" onmousedown="win.WstartDrag(\''+id+'\')" '+acts[0]+'><b>'+title+'</b></td>'+
					 '<td width="15" align="right"><img style="display:block" onClick="win.closew(\''+id+'\');'+acts[9]+'" src="http://'+c.img+'/i/clear.gif" width="13" height="13"></td>'+
					 '</tr>'+
					 '</table>'+
				     '</div>'+nw;
			}
			
			//Собираем каркас
			nw = '<table onclick="top.win.addaction(0,\''+kyps[0]+'\')" border="0" cellspacing="0" cellpadding="0">'+
				  '<tr>'+
					'<td class="wi'+style+'s0"></td>'+
					'<td class="wi'+style+'s1"></td>'+
					'<td class="wi'+style+'s2"></td>'+
				  '</tr>'+
				  '<tr>'+
					'<td class="wi'+style+'s3"><img src="http://'+c.img+'/1x1.gif" width="5" height="1"></td>'+
					'<td class="wi'+style+'s7" id="win_main_'+id+'">'+nw+'</td>'+
					'<td class="wi'+style+'s4"><img src="http://'+c.img+'/1x1.gif" width="5" height="1"></td>'+
				  '</tr>'+
				  '<tr>'+
					'<td class="wi'+style+'s5"></td>'+
					'<td class="wi'+style+'s6"></td>'+
					'<td class="wi'+style+'s8"><div id="win_a_'+id+'" class="wi'+style+'s9"></div></td>'+
				  '</tr>'+
				  '</table>';
			
			//Вешаем окно
			nw  = '<div class="w1" '+acts[0]+' id="win_'+id+'">'+nw+'</div>';
			
			$('#windows').html($('#windows').html()+nw);
			$('#win_'+id).center();
			if(type == 2 || type == 3) {
				if(type == 2) {
					$('#winyesbtnfox'+id).focus();
				}
			}
		}
		delete nw;
	},
	addaction:function(nm,vl) {
		top.key_actions[nm] = vl;
		if(nm != 2) {
			top.key_actions[2] = 1;
		}
	},
	closew:function(id)
	{
		$('#win_'+id).html('');
		$('#win_'+id).remove();
	}
}