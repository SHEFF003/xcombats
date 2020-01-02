var cfg = {
	'host':'worldofcombats.ru',
	'img':'worldofcombats.ru/static/'
};
 
var GameEngine = {
	
	start:function() {
		ReLine.start();
		ReLine.rebase();
		
		chat.refleshChat(false);
		chat.refleshSmiles();
		
		this.actionSecondStart();
		
		this.timeStempReflesh();
		
		/* онлайн меню */
		this.onlinebtn('Локация');
		this.onlinebtn('Друзья');
		this.onlinebtn('Модераторы');
		this.onlinebtn('Дилеры');
		
		//mod.start();
		
		$('#preloader').remove();
	},
	hasFlashVar:'none',
	hasFlash:function() {
		if(this.hasFlashVar == 'none') {
			this.hasFlashVar = (typeof navigator.plugins == "undefined" || navigator.plugins.length == 0) ? !!(new ActiveXObject("ShockwaveFlash.ShockwaveFlash")) : navigator.plugins["Shockwave Flash"];
		}
		return this.hasFlashVar;
	},
	ch10:function(v) {
		if( v == 'checked' ) {
			return 1;
		}else{
			return 0;
		}
	},
	timeStempReflesh:function() {
		
		if( this.hasFlash() ) {
			$('#timeStemp').html(
				   '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="70" height="25">'+
						'<param name="movie" value="http://' + cfg.host + '/static/flash/clock.swf?hours=' + this.data('H') + '&amp;minutes=' + this.data('i') + '&amp;sec=' + this.data('s') + '">'+
						'<param name="quality" value="high">'+
						'<embed src="http://' + cfg.host + '/static/flash/clock.swf?hours=' + this.data('H') + '&minutes=' + this.data('i') + '&sec=' + this.data('s') + '" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="70" height="25"></embed>'+
					'</object>'
			);
		}else{
			$('#timeStemp').html('').css('display','none');
		}
	},
	onlinebtnsel:function(id) {
		var i = 1;
		while(i <= this.onbtn_id) {
			$('#onbtn_'+i).removeClass('onbtn_1');
			$('#onbtn_'+i).removeClass('onbtn_2');
			$('#onbtn_'+i).removeClass('onbtn_3');
			$('#onbtn_'+i).addClass('onbtn_2');
			i++;
		}
		$('#onbtn_'+id).removeClass('onbtn_2');
		$('#onbtn_'+id).addClass('onbtn_1');
	},
	onbtn_id:0,
	onlinebtn:function(title) {
		this.onbtn_id++;
		var stl = 1;
		if(this.onbtn_id > 1) {
			stl = 2;
		}
		var html = '<span id="onbtn_' + this.onbtn_id + '" onclick="GameEngine.onlinebtnsel('+this.onbtn_id+')" class="onbtn_' + stl + '" title="' + title + '">'+
		'<img src="http://' + cfg.img + 'images/oico' + this.onbtn_id + '.png" width="38" height="29">' +
		'</span>';
		$('#omnav').html( $('#omnav').html() + html );
	},
	actSec:[false, 0, 'stop', 0, {}],
	addAction:function( name, value, timeDelete ) {
		this.actSec[4][name] = value;
		
		if(timeDelete > 0) {
			setTimeout(function(){ GameEngine.deleteAction(name); },1000*timeDelete);
		}
	},
	deleteAction:function( name ) {
		delete this.actSec[4][name];
	},
	tnln:function(n,t) {
		if(t == true) {
			return '<span class="rstmn rstmn'+n+'"></span>';
		}else{
			return '<small>' + n + '</small>';
		}
	},
	timeNowLook:function() {
		var html = '', rstime = '', rsmenu = '', rsh = '', rsm = '', rss = '';
		
		rsh = this.data('H');
		if(rsh < 10) {
			//rsh = [0,rsh];
		}
		
		rsm = this.data('i');
		if(rsm < 10) {
			//rsm = [0,rsm];
		}
		
		rss = this.data('s');
		if(rss < 10) {
			//rss = [0,rss];
		}

		rsh = this.tnln(rsh[0],true) + '' + this.tnln(rsh[1],true);
		rsm = this.tnln(rsm[0],true) + '' + this.tnln(rsm[1],true);
		rss = this.tnln(rss[0],false) + '' + this.tnln(rss[1],false);
		
		rstime = '<span class="rstm">' + rsh + '<span class="rstmn rstmnrz"></span>' + rsm + '</span><span class="rstmsec">' + rss + '</span>';
		
		rsmenu = '<div class="rsmn" align="center"><font color="red">server</font> time</div>';
		
		html = '<table class="rsTimeNow" width="70" height="25" border="0" cellspacing="0" cellpadding="0">'+
				  '<tr>'+
					'<td>'+
					'<table width="70" border="0" cellspacing="0" cellpadding="0">'+
					  '<tr>'+
						'<td height="20" align="left">'+
						rstime+
						'</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="5">'+
						rsmenu+
						'</td>'+
					  '</tr>'+
					'</table>'+
					'</td>'+
				  '</tr>'+
				'</table>';
		$('#timeNowLook').html( html );
		setTimeout('GameEngine.timeNowLook()',500);
	},
	actSecTimer:null,
	actionSecondStart:function() {
		//window.ref = setInterval(function(){ GameEngine.actionSecond('second'); },1000);
		/*$(window).everyTime(1000, 'timerAction', function(i) {
			GameEngine.actionSecond('second');
		});*/
	},
	actionSecond:function(type) {
		//clearInterval(window.ref);		
		if( type != 'stop' && type != 'pause' ) {
			
			//Выполняем действия каждую секунду
			var i = 0;			
			var widthRange = {'test':'test2','test3':4}; //массив
			for (var key in this.actSec[4]) {
				if (key === 'length' || !this.actSec[4].hasOwnProperty(key)) continue;
				
				var value = this.actSec[4][key];
				value();
				//eval( value );
											
			}	
					
			delete key;
			delete value;
			
		}
		if( ( type == 'stop' || type == 'start' || type == 'pause' ) && this.actSec[2] != type ) {
			this.actSec[2] = type;
		}
		//this.actionSecondStart();
	},
	error:function(v) {
		/*
			Функция вывода ошибок
		*/
		alert(v);
	},
	c:function(v) {
		/*
			мини-вариант функции this.console
		*/
		this.console(v);
	},
	console:function(v) {
		/*
			Добавление данных в консоль
		*/
		date = new Date();
		console.log("["+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+"] "+v);
	},
	fm:function( v , e) {
		
		if (!e) e = window.event;
		
		if( v == 'onclick' ) {
			
			if( chat.context_open == true ) {
				chat.contextmenu_close();
			}
			
			if( chat.context_global_open == true ) {
				chat.contextmenu_global_close();
			}
			
		}else if( v == 'contextmenu' ) {
			
			if( chat.context_open == true ) {
				//chat.contextmenu_close();
			}
			if( chat.context_global_open == true ) {
				//chat.contextmenu_global_close();
			}
			
		}else if( v == 'contextmenu_main' || v == 'contextmenu_chat' || v == 'contextmenu_online' ) {
			v = v.replace('contextmenu_','');
			chat.contextMenuGlobal( v , e);
		}
	},
	merge_arrays:function(arr) {
		var merged_array = arr;
		for (var i = 1; i < arguments.length; i++) {
			merged_array = merged_array.concat(arguments[i]);
		}
		return merged_array;
	},
	post_date:['',{},0],
	post:function(date, only) {
		//Какое-то обновление информации :)
		if( only == null ) {
			var arr = [
				'',
				$.extend(date[1],this.post_date[1]),
				function(data) {
					if(typeof(date[2])=='string') {
						//eval(date[2]+'(data)');
						eval('GameEngine.post_back(data,"'+date[2]+'",false)');
						if( GameEngine.post_date[2] != 0 ) {
							//eval(GameEngine.post_date[2]+'(data)');
							eval('GameEngine.post_back(data,"'+GameEngine.post_date[2]+'",false)');
						}
					}else{
						//eval(date[2][0]);
						eval('GameEngine.post_back(data,\''+date[2][0]+'\',true)');
					}
				}
			];
		}else{
			var arr = ['',date[1],date[2]];
		}
		
		//Параметры версий
		if( !arr[1]['version'] ) {
			arr[1]['version'] = this.version();
		}else if( arr[1]['version'] == 'delete' ) {
			delete arr[1]['version'];
		}
		
		$.post(date[0],arr[1],arr[2]);
		this.post_date = ['',{},0];	
	},
	core:function(data) {
		//Обработка событий ядра
	},
	post_back:function(data,fx,cl) {
		
		if( typeof(data) == 'string' ) {		
			da = $.parseJSON( data );			
			//Проводим операции
			if( da['core'] != undefined) {
				this.core(da['core']);			
				if( da['core']['back_arr'] == true ) {
					//Возвращаем массив
					data = '[' + this.to_array( da ) + ']';
				}
			}
			delete da;	
		}
		if( cl == false ) {
			eval(fx+'(data)');
		}else{
			eval(fx);		
		}
	},
	to_array:function (obj) {
		var arr = [];
		
		var i = 0;
		while( i > -1 ) {
			if(obj[i] == undefined) {
				i = -2;
			}else{
				//arr += '['+obj[i]+'],';
				if(typeof(obj[i]) == 'number') {
					arr[i] = obj[i];
				}else{
					arr[i] = '['+obj[i]+']';
				}
			}
			i++;
		}
		
		//arr = arr.substring(0, arr.length - 1);
		
		return arr;
	},
	trim:function(s) {
	  return this.rtrim(this.ltrim(s));
	},
	ltrim:function(s) {
	  return s.replace(/^\s+/, ''); 
	},
	rtrim:function(s) {
	  return s.replace(/\s+$/, ''); 
	},
	str_replace:function ( search, replace, subject ) {
		if(!(replace instanceof Array)){
			replace=new Array(replace);
			if(search instanceof Array){
				while(search.length>replace.length){
					replace[replace.length]=replace[0];
				}
			}
		}
		if(!(search instanceof Array))search=new Array(search);
		while(search.length>replace.length){
			replace[replace.length]='';
		}
		if(subject instanceof Array){
			for(k in subject){
				subject[k]=str_replace(search,replace,subject[k]);
			}
			return subject;
		}
		for(var k=0; k<search.length; k++){
			var i = subject.indexOf(search[k]);
			while(i>-1){
				subject = subject.replace(search[k], replace[k]);
				i = subject.indexOf(search[k],i);
			}
		}
		return subject;
	},
	timeSdvig:0,
	timeReson:0,
	timeSd:function( time ) {
		this.timeSdvig = this.timenow();
		this.timeReson = time;
	},
	timenow:function(){
		//return ( parseInt(new Date().getTime()/1000));
		return parseInt( this.timeReson + ( parseInt(new Date().getTime()/1000) - this.timeSdvig ) );
	},
	px:function( v ) {
		return parseInt( ( v ).replace( "px", "" ) );
	},
	divxyBanned:function( obj ) {
		//Блокировка элемента, если он выходит за пределы окна обзора
		var obj = $(obj);
		var bnpx = [25,33,3,1];
		//var win = this.getPageSize();
		
		//По Y 
		if( obj.height() + this.px( obj.css('top') ) + bnpx[1] > $('#fm').height() ) {
			obj.css({'top':( $('#fm').height() - bnpx[1] - obj.height() )+'px'});
		}else if( this.px( obj.css('top') ) - bnpx[0] < 0 ) {
			obj.css({'top':( bnpx[0] )+'px'});
		}
		
		//По X 
		if( obj.width() + this.px( obj.css('left') ) + bnpx[2] > $('#fm').width() ) {
			obj.css({'left':( $('#fm').width() - bnpx[2] - obj.width() )+'px'});
		}else if( this.px( obj.css('left') ) - bnpx[3] < 0 ) {
			obj.css({'left':( bnpx[3] )+'px'});
		}
	},
	getPageSize:function(){
		   var xScroll, yScroll;
	 
		   if (window.innerHeight && window.scrollMaxY) {
				   xScroll = document.body.scrollWidth;
				   yScroll = window.innerHeight + window.scrollMaxY;
		   } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
				   xScroll = document.body.scrollWidth;
				   yScroll = document.body.scrollHeight;
		   } else if (document.documentElement && document.documentElement.scrollHeight > document.documentElement.offsetHeight){ // Explorer 6 strict mode
				   xScroll = document.documentElement.scrollWidth;
				   yScroll = document.documentElement.scrollHeight;
		   } else { // Explorer Mac...would also work in Mozilla and Safari
				   xScroll = document.body.offsetWidth;
				   yScroll = document.body.offsetHeight;
		   }
	 
		   var windowWidth, windowHeight;
		   if (self.innerHeight) { // all except Explorer
				   windowWidth = self.innerWidth;
				   windowHeight = self.innerHeight;
		   } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
				   windowWidth = document.documentElement.clientWidth;
				   windowHeight = document.documentElement.clientHeight;
		   } else if (document.body) { // other Explorers
				   windowWidth = document.body.clientWidth;
				   windowHeight = document.body.clientHeight;
		   }
	 
		   // for small pages with total height less then height of the viewport
		   if(yScroll < windowHeight){
				   pageHeight = windowHeight;
		   } else {
				   pageHeight = yScroll;
		   }
	 
		   // for small pages with total width less then width of the viewport
		   if(xScroll < windowWidth){
				   pageWidth = windowWidth;
		   } else {
				   pageWidth = xScroll;
		   }
	 
		   return [pageWidth,pageHeight,windowWidth,windowHeight];
	},
	data:function(format,UNIX_timestamp){
		if(UNIX_timestamp == null) {
			UNIX_timestamp = this.timenow();
		}
		var a = new Date(UNIX_timestamp*1000);
		var months = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
		var year = a.getFullYear();
		var month = a.getMonth();
		var date = a.getDay();
		var hour = a.getHours();
		var min = a.getMinutes();
		var sec = a.getSeconds();
		var time = format;
		month += 1;
		if(date < 10) {
			date = '0'+date;
		}
		if(month < 10) {
			month = '0'+month;
		}
		if(hour < 10) {
			hour = '0'+hour;
		}
		if(min < 10) {
			min = '0'+min;
		}
		if(sec < 10) {
			sec = '0'+sec;
		}
		time = this.str_replace('d',''+date,time);
		time = this.str_replace('m',''+month,time);
		time = this.str_replace('Y',''+year,time);
		time = this.str_replace('H',''+hour,time);
		time = this.str_replace('i',''+min,time);
		time = this.str_replace('s',''+sec,time);
		return time;
	 },
	 loginLook:function(id,login,level,clan,align,type) {
		 var html = '';
		 
		 if( type == 1 ) {
			 html = '<a class="cp">' + login + '</a>';
		 }else if( type == 2 ) {
			 html = '<b>' + login + '</b>';
		 }
		 
		 html += ' [' + level + ']';
		 html += '<a target="_blank" title="Инф. о ' + login + '" href="http://' + cfg.host + '/userinfo/' + id + '"><img src="http://' + cfg.img + 'images/inf.gif" width="12" height="11"></a>';
		 
		 return html;
	 }
};

function showtable(id) {
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