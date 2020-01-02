var key_actions = {};
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
function unpx(v)
{
	if( v != undefined ) {
		return Number(v.replace('px',''));
	}
}

//Окна
var wind = {
	winc:{}, //координаты окон
	wsdr:null,
	scor:{}, //начальные координаты
	openw:function(id,title,text,date,type,style)
	{
		if($('#win_'+id).attr('id')==undefined)
		{
			//Создаем новое окно
			this.add(id,title,text,date,type,1,'');
		}
	},
	WstartDrag:function(id)
	{
		$('#wupbox').css({'display':'block','cursor':'move'});	
		this.wsdr = id;
		$('.w1').css({'z-index':1102});		
		$('#win_'+id).css({'z-index':1103});
		delete cm;
	},
	WmoveDrag:function(e)
	{
		//Сохраняем начальные координаты
		var x = mousePageXY(e)['x'],y = mousePageXY(e)['y'];		
		if(this.scor.x==undefined)
		{
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
	WstopDrag:function()
	{
		$('#wupbox').css({'display':'none','cursor':'move'});
		this.wsdr = null;
		this.scor = {};
	},
	add:function(id,title,text,date,type,style,css)
	{
		var nw = '';
		if($('#win_'+id).attr('id') == undefined)
		{	
			var acts = {};
			
			if(date.usewin != undefined)
			{
				acts[0] = 'onmouseup="'+date.usewin+'"';
			}
			
			//нижняя часть
			if(date.n != undefined)
			{
				text += '<div style="margin-left:4px;">'+date.n+'</div>';
			}
			var kyps = ['',''];
			//Вывод главных данных
			if(type==0)
			{
				nw = text;
			}else if(type==1)
			{
				//Просто вывод данных
				nw = text;
			}else if(type==2)
			{
				//Да \ Нет
				nw = '<div>'+text+'</div><div style="padding:5px"><div style="float:left"><button onClick="'+date.a1+';wind.closew(\''+id+'\');" style="width:100px">Да</button></div><div style="float:right"><button onClick="wind.closew(\''+id+'\')" style="width:100px">Нет</button></div><br></div>';
				kyps[0] = ''+date.a1+';top.wind.closew(\\\''+id+'\\\');top.wind.addaction(0,\\\'\\\');';
			}else if(type==3)
			{
				//Да \ Нет , изображения
				nw = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>'+text+'</td><td width="40" align="center" valign="middle"><img style="margin-top:5px;cursor:pointer" onClick="'+date.a1+';wind.closew(\''+id+'\');" src="http://'+cfg.img+'/i/b__ok.gif" width="25" height="18"><br><img onClick="wind.closew(\''+id+'\')" style="cursor:pointer" src="http://'+cfg.img+'/i/b__cancel.jpg" width="25" height="18"></td></tr></table>';
				kyps[0] = ''+date.a1+';top.wind.closew(\\\''+id+'\\\');top.wind.addaction(0,\\\'\\\');';
			}else if(type==4)
			{
				//Тройной блок
				nw = text[0];
			}
			
			//Если есть вторая информация
			if(date.d!=undefined)
			{
				nw = nw+date.d;
			}
			
			nw = '<div style="margin:2px;'+css+'">'+nw+'</div>';
			
			//Заголовок окна
			if(title != '')
			{
				nw = '<div class="wi'+style+'s10" onselectstart="return false">'+
					 '<table width="100%" border="0" cellspacing="0" cellpadding="0">'+
					 '<tr>'+
				     '<td style="cursor:move" onmousedown="wind.WstartDrag(\''+id+'\')" '+acts[0]+'><b>'+title+'</b></td>'+
					 '<td width="15" align="right"><img style="display:block" onClick="wind.closew(\''+id+'\')" src="http://'+cfg.img+'images/clear.gif" width="13" height="13"></td>'+
					 '</tr>'+
					 '</table>'+
				     '</div>'+nw;
			}
			
			//Собираем каркас
			nw = '<table onclick="top.wind.addaction(0,\''+kyps[0]+'\')" border="0" cellspacing="0" cellpadding="0">'+
				  '<tr>'+
					'<td class="wi'+style+'s0"></td>'+
					'<td class="wi'+style+'s1"></td>'+
					'<td class="wi'+style+'s2"></td>'+
				  '</tr>'+
				  '<tr>'+
					'<td class="wi'+style+'s3"><img src="http://'+cfg.img+'/1x1.gif" width="5" height="1"></td>'+
					'<td class="wi'+style+'s7" id="win_main_'+id+'">'+nw+'</td>'+
					'<td class="wi'+style+'s4"><img src="http://'+cfg.img+'/1x1.gif" width="5" height="1"></td>'+
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