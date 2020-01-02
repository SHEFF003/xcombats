/*
el - блок
txt - текст
event --
tp - тип
st - стили
sm - мелкий текст
*/

function pageX(elem) {
       return  elem.offsetParent ?
               elem.offsetLeft + pageX( elem.offsetParent ) :
               elem.offsetLeft;
}

function pageY(elem) {
       return  elem.offsetParent ?
               elem.offsetTop + pageY( elem.offsetParent ) :
               elem.offsetTop;
}

var xyfmn = 0;
function himove(e) {
	if (!e) e = window.event;
	$('#ttl').css({'top':'0px','left':'0px'});
	//el,txt,e,tp,st,sm,fm,css
	var x = e.pageX, y = e.pageY;
	var fm = top.xyfmn[6];
	var el = top.xyfmn[0];
	var max_x = ($(window).width()-10),max_y = ($(window).height()-10);
	if($('#main').attr('id') != null && $('#main').attr('id') != undefined) {
		if(fm == 1)
		{
			//main
			y += 52-$(top.frames['main']).scrollTop()+8;
			x += 8;
		}else if(fm == 2)
		{
			//online
			y += 20-$('#online_list').scrollTop();
			x += 10;
		}else if(fm == 3)
		{
			//chat
			y += 20-$('#chat_list').scrollTop();
			x += 2;
		}
	}else{
		//all
		y += 31;
		x += 2;
	}
	
	if( x + $('#ttl').width() > max_x) {
		x = max_x - $('#ttl').width();
	}
	
	if( y + $('#ttl').height() > max_y) {
		y = max_y - $('#ttl').height();
	}
	
	$('#ttl').css({'top':y+'px','left':x+'px'});
}

function hi(el,txt,e,tp,st,sm,fm,css)
{	
	if (!e) e = window.event;
	top.xyfmn = [el,txt,e,tp,st,sm,fm,css];
	var x = e.pageX, y = e.pageY;
	x += 0; y -= 8;
	$('#ttl').css({'top':y+'px','left':x+'px'});
	
	var rhtml = txt;
	
	if(css!='')
	{
		rhtml = '<div style="'+css+'">'+rhtml+'</div>';		
	}
	
	if(st==1)
	{
		rhtml = '<div style="white-space:nowrap;">'+rhtml+'</div>';
	}
	
	if(sm==1)
	{
		rhtml = '<small>'+rhtml+'</small>';
	}
	
	$('#ttl').html(rhtml);
	$('#ttl').css({'display':''});
	
	if(tp>0)
	{
		var ec = $(el).offset();
		
			ec.top = pageY(el);
			ec.left = pageX(el);
		
		if(ec['top']!=0)
		{
			if(tp==1)
			{
				y = ec['top']-$(el).height()-8;
				x = ec['left'];
			}else if(tp==2)
			{
				y = ec['top']+$(el).height()+8;
				x = ec['left'];
			}else if(tp==3)
			{
				y = ec['top'];
				x = ec['left']+$(el).width()+8;
			}else if(tp==4)
			{
				y = ec['top'];
				x = ec['left']-$(el).width()-8;
			}else if(tp==5)
			{
				y = ec['top']-$('#ttl').height()-8;
				x = ec['left'];
			}
			if($('#main').attr('id') != null && $('#main').attr('id') != undefined) {
				if(fm == 1)
				{
					//main
					y += 34-$(top.frames['main']).scrollTop();
					x += 2;
				}else if(fm == 2)
				{
					//online
					y += 0-$('#online_list').scrollTop();
					x += 2;
				}else if(fm == 3)
				{
					//chat
					y += 0-$('#chat_list').scrollTop();
					x += 2;
				}
			}else{
				//all
				y += 31;
				x += 2;
			}
		}
		$('#ttl').css({'top':y+'px','left':x+'px'});
	}else{
		//плавающее
		
	}
	
	if($('#globalMain').attr('id')!=undefined)
	{
		if((x+$('#ttl').width())-$('#globalMain').width()>=-8)
		{
			x = $('#globalMain').width()-$('#ttl').width()-8;
			if(tp==3)
			{
				y = ec['top']+$(el).height()+8;
			}else if(tp==4)
			{
				y = ec['top']+$(el).height()+8;
			}
			if(fm == 1)
			{
				//main
				y += 36-$(top.frames['main']).scrollTop();
				x += 2;
			}else if(fm == 2)
			{
				//online
				y += 0-$('#online_list').scrollTop();
				x += 2;
			}else if(fm == 3)
			{
				//chat
				y += 0-$('#chat_list').scrollTop();
				x += 2;
			}
			
			$('#ttl').css({'top':y+'px','left':x+'px'});
		}
	}else{
		y -= 30;
		x -= 8;
		$('#ttl').css({'top':y+'px','left':x+'px'});
	}
	if(tp == 0) {
		$(el).bind('mousemove', function(e){ top.himove(e); });
	}
}

function hic()
{
	$('#ttl').css({'display':'none'});
	$('#ttl').css({'top':'-5px','left':'-5px'});
	$('#ttl').html(' ');
}