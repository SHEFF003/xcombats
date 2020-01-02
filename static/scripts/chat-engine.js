 var chat = {
	 
	sml:new Array("smile",18,18,  "laugh",15,15,  "fingal",22,15,  "eek",15,15,  "smoke",20,20,  "hi",31,28,  "bye",15,15,
	"king",21,22, "king2",28,24, "smile",18,18, "boks2",28,21, "boks",62,28,  "gent",15,21,  "lady",15,19,  "tongue",15,15,  "smil",16,16,  "rotate",15,15,
	"ponder",21,15,  "bow",15,21,  "angel",42,23, "angel2",26,25,  "hello",25,27,  "dont",26,26,  "idea",26,27,  "mol",27,22,  "super",26,28,
	"beer",15,15,  "drink",19,17,  "baby",15,18,  "tongue2",15,15,  "sword",49,18,  "agree",37,15,
	"loveya",27,15,  "kiss",15,15,  "kiss2",15,15,  "kiss3",15,15,  "kiss4",37,15,  "rose",15,15,  "love",27,28,
	"love2", 55,24, 
	"confused",15,22,  "yes",15,15,  "no",15,15,  "shuffle",15,20,  "nono",22,19,  "maniac",70,25,  "privet",27,29,  "ok",22,16,  "ninja",15,15,
	"pif",46,26,  "smash",30,26,  "alien",13,15,  "pirate",23,19,  "gun",40,18,  "trup",20,20,
	"mdr",56,15,  "sneeze",15,20,  "mad",15,15,  "friday",57,28,  "cry",16,16,  "grust",15,15,  "rupor",38,18,
	"fie",15,15,  "nnn",82,16,  "row",36,15,  "red",15,15,  "lick",15,15,
	"help",23,15,  "wink",15,15, "jeer",26,16, "tease",33,19, "nunu",43,19,
	"inv",80,20,  "duel",100,34,  "susel",70,34,  "nun",40,28,  "kruger",34,27, "flowers",28,29, "horse",60,40, "hug",48,20, "str",35,25,
	"alch",39,26, "pal", 25, 21, "mag", 37, 37, "sniper", 37,37, "vamp", 27,27,  "doc", 37,37, "doc2", 37,37, "sharp", 37,37, 
	"naem", 37,37, "naem2", 37,37, "naem3", 37,37, "invis", 32,23,  "chtoza", 33, 37,
	"beggar", 33,27, "sorry", 25,25, "sorry2", 25,25,
	"creator", 39, 25, "grace", 39, 25, "dustman", 30, 21, "carreat", 40, 21, "lordhaos", 30, 21,
	"ura", 31, 36, "elix", 30, 35, "dedmoroz", 32,32, "snegur", 45,45, "showng", 50, 35, "superng", 45,41,
	"podz", 31,27, "sten", 44, 30, "devil", 29, 20, "cat", 29, 27, "owl", 29,20, "lightfly", 29,20, "snowfight", 51, 24,
	"rocket", 43,35, "dance1", 45,23, "radio1", 36, 24, "victory", 51, 35, "dance2", 41, 31, "radio2", 29, 29, 
	"nail", 32, 26, "rev", 40, 25, "obm", 37, 22, "yar", 40, 36, "rom", 38, 33, "sad", 23, 23),
	
	timers:[],
	timers0:'noTimer',
	chat_r:30,
	locks:[0,0],
	inObj:false,
	lmid:0,
	cid:[0],
	mxlm:100,
	canal_open:0,
	
	
	refleshData:{},
	refleshChat:function( auto ) {
		if(this.chat_r < 30) {
			this.chat_r = 30;
		}
		if(this.locks[0] == 0) {			
			delete this.refleshData.message;
			this.refleshData.lmid = this.lmid;
			this.refleshData.auto = auto;
			this.refleshData.sys = this.system;
			this.refleshData.ftr = this.filter;
			this.refleshData.pgo = this.onlinePageSelect;	
			this.refleshData.r2 = $('#online_r2').attr('checked');
					
			if( auto == true ) {
				this.refleshData.r1 = $('#online_r1').attr('checked');
			}else{
				this.refleshData.r1 = 'checked';
				$(window).stopTime('tmchon');
			}			
			$.ajax({
			  url: 'http://'+cfg.host+'/chat/',
			  type: 'POST',
			  dataType: 'json',
			  data: this.refleshData,
			  success: function(data){ chat.readData(data); }
			});
		}
		$(window).oneTime( ( 1000 * this.chat_r ), 'tmchon', function(i) {
				$(window).stopTime('tmchon');
				chat.refleshChat(true);
		});
	},
	
	tmrMsg:null,
	formSendMessage:function() {
		if( $('#msg_text').val() == '' ) {
			alert( 'Нельзя отправлять пустое сообщение!' );
		}else if(this.locks[1] == 0) {
			//clearTimeout(this.tmrMsg);
			this.locks[1] = 1;
			
			//this.tmrMsg = setTimeout(function(){chat.locks[1] = 0;},850);
			
			if( this.translit == true ) {
				$('#msg_text').val( this.convert2( $('#msg_text').val() ) );	
			}
			
			this.refleshData.message = $('#msg_text').val();
			this.refleshData.lmid = this.lmid;
			this.refleshData.auto = false;
			this.refleshData.sys = this.system;
			//this.refleshData.pgo = this.onlinePageSelect;	
			//this.refleshData.r2 = $('#online_r2').attr('checked');
			//this.refleshData.r1 = $('#online_r1').attr('checked');
			
			$.ajax({
			  url: 'http://'+cfg.host+'/chat/',
			  type: 'POST',
			  dataType: 'json',
			  data: this.refleshData,
			  success: function(data){ chat.locks[1]=0; $('#msg_text').val(""); chat.readData(data); }
			});
		}else{
			alert('Внимание! Нельзя отправлять сообщения так часто!');
		}
	},
	
	testKeyPress:function(event) {
		if(event.keyCode==10 || event.keyCode==13) {
			chat.formSendMessage();
		}
	},
	
	/* Очистить чат */
	clearChatline:function( type ) {
		if( $('#msg_text').val() == '' && type == false) {
			if( confirm('Очистить весь чат?') ) {
				this.clearChat();
			}
		}else{
			$('#msg_text').val('');
			$('#msg_text').focus();
		}
	},
	
	/* включить фильтр чата */
	filter:false,
	filterChatLine:function() {
		if( this.filter == false ) {
			$('#btn3c').attr('class','btn3c2');
			$('#btn3c').attr('title','(Включено) Показывать в чате только сообщения адресованные мне');
			this.filter = true;			
		}else{
			$('#btn3c').attr('class','btn3c');
			$('#btn3c').attr('title','(Выключено) Показывать в чате только сообщения адресованные мне');
			this.filter = false;
		}
	},
	
	/* включить системные сообщения чата */
	system:false,
	systemChatLine:function() {
		if( this.system == false ) {
			$('#btn4c').attr('class','btn4c2');
			$('#btn4c').attr('title','(Включено) Показывать в чате системные сообщения');
			this.system = true;			
		}else{
			$('#btn4c').attr('class','btn4c');
			$('#btn4c').attr('title','(Выключено) Показывать в чате системные сообщения');
			this.system = false;
		}	
	},
	
	/* включить медленное обновление чата */
	speedchat:1,
	speedChatLine:function() {
		if( this.speedchat == 3 ) {
			$('#btn5c').attr('class','btn5c2');
			$('#btn5c').attr('title','Обновление чата выключено!');
			this.chat_r = 31536000;
			clearTimeout(this.timers[0]);
			this.speedchat = 2;			
		}else if( this.speedchat == 2 ) {
			$('#btn5c').attr('class','btn5c');
			$('#btn5c').attr('title','(Выключено) Медленное обновление чата (раз в минуту)');
			this.chat_r = 30;
			clearTimeout(this.timers[0]);
			this.timers[0] = setTimeout('chat.refleshChat(true)',1000 * this.chat_r);
			this.speedchat = 1;
		}else{
			$('#btn5c').attr('class','btn5c3');
			$('#btn5c').attr('title','(Включено) Медленное обновление чата (раз в минуту)');
			this.chat_r = 60;
			clearTimeout(this.timers[0]);
			this.timers[0] = setTimeout('chat.refleshChat(true)',1000 * this.chat_r);
			this.speedchat = 3;
		}	
	},
	
	/* включить транслит чата */
	translit:false,
	translitChatLine:function() {
		if( this.translit == false ) {
			$('#btn6c').attr('class','btn6c2');
			$('#btn6c').attr('title','(Включено) Преобразовать транслит в русский текст (правила перевода см. в энциклопедии)');
			this.translit = true;			
		}else{
			$('#btn6c').attr('class','btn6c');
			$('#btn6c').attr('title','(Выключено) Преобразовать транслит в русский текст (правила перевода см. в энциклопедии)');
			this.translit = false;
		}	
	},
	
	convert2:function(txt)
	{
		var trn = new Array();
		trn = txt.split(' ');
		for(var i=0;i<trn.length;i++) {
			if(trn[i].indexOf("http://") < 0 && trn[i].indexOf('@') < 0 && trn[i].indexOf("www.") < 0 && !(trn[i].charAt(0)==":" && trn[i].charAt(trn[i].length-1)==":")) {
				if ((i<trn.length-1)&&(trn[i]=="to" || trn[i]=="private")&&(trn[i+1].charAt(0)=="[")) {
					while ( (i<trn.length-1) && (trn[i].charAt(trn[i].length-1)!="]") ) i++;
				} else { trn[i] = this.convert(trn[i]); }
			}
		}
		return trn.join(' ');
	},
	map_en:Array('s`h','S`h','S`H','s`Х','sh`','Sh`','SH`',"'o",'yo',"'O",'Yo','YO','zh','w','Zh','ZH','W','ch','Ch','CH','sh','Sh','SH','e`','E`',"'u",'yu',"'U",'Yu',"YU","'a",'ya',"'A",'Ya','YA','a','A','b','B','v','V','g','G','d','D','e','E','z','Z','i','I','j','J','k','K','l','L','m','M','n','N','o','O','p','P','r','R','s','S','t','T','u','U','f','F','h','H','c','C','`','y','Y',"'"),
	map_ru:Array('сх','Сх','СХ','сХ','щ','Щ','Щ','ё','ё','Ё','Ё','Ё','ж','ж','Ж','Ж','Ж','ч','Ч','Ч','ш','Ш','Ш','э','Э','ю','ю','Ю','Ю','Ю','я','я','Я','Я','Я','а','А','б','Б','в','В','г','Г','д','Д','е','Е','з','З','и','И','й','Й','к','К','л','Л','м','М','н','Н','о','О','п','П','р','Р','с','С','т','Т','у','У','ф','Ф','х','Х','ц','Ц','ъ','ы','Ы','ь'),
	
	convert:function(str)
	{	
		var p1 = new RegExp("private\\s*\\[(.*?)\\]","");
		var t1 = new RegExp("to\\s*\\[(.*?)\\]","");
		var newstr = '';
		if(str.match(p1)!=null)
		{
			newstr = str.match(p1)[0];
			str = str.replace(str.match(p1)[0],'');
		}else if(str.match(t1)!=null)
		{
			newstr = str.match(t1)[0];
			str = str.replace(str.match(t1)[0],'');
		}
		
		for(var i=0;i<this.map_en.length;++i) while(str.indexOf(this.map_en[i])>=0) str = str.replace(this.map_en[i],this.map_ru[i]);
		newstr += str;
		return newstr;
	},
	
	/* смайлики */
	smileschat:false,
	smilesChatLine:function() {
		if( this.smileschat == false ) {
			$('#ttSmiles').css('display','');
			$('#btn7c').attr('class','btn7c2');
			$('#btn7c').attr('title','(Включено) Смайлики');
			this.smileschat = true;			
		}else{
			$('#ttSmiles').css('display','none');
			$('#btn7c').attr('class','btn7c');
			$('#btn7c').attr('title','Смайлики');
			this.smileschat = false;
		}
	},
	addSmile:function(id)
	{
		if( top.mob_version != undefined ) {
			$('#chat_message').val($('#chat_message').val()+' :'+id+': ');
			//$('#chat_message').focus();
		}else{
			$('#msg_text').val($('#msg_text').val()+' :'+id+': ');
			$('#msg_text').focus();
		}
	},
	refleshSmiles:function() {
		//генерируем смайлики
		var i = 0, j = '';
		while(i!=-1)
		{
			if(this.sml[i]!=undefined)
			{
				j += '<img style="cursor:pointer" onclick="chat.addSmile(\''+this.sml[i]+'\')" src="http://'+cfg['img']+'images/smile/'+this.sml[i]+'.gif" width="'+this.sml[i+1]+'" height="'+this.sml[i+2]+'" title=":'+this.sml[i]+':" /> ';
			}else{
				i = -4;
			}
			i += 3;
		}
		$('#smilesDiv').html(j);
		delete i;
		delete j;
	},
	
	testKey:function(m,v)
	{
		var i = 0, r = v;
		v = false;
		while(i!=-1)
		{
			if(m != undefined && m[i]!=undefined)
			{
				if(m[i]==r)
				{
					v = i;
					i = -2;
				}
			}else{
				i = -2;
			}
			i++;
		}
		delete r,m;
		return v;
	},
	isNumber:function(s)
	{
		if(!isNaN(s))
		{
			s = true;
		}else{
			s = false;
		}
		return s;
	},
	
	testSmile:function(txt)
	{
		txr = txt.split(':');
		var i = 1, j = 0, smid = 0;
		while(i <= txr.length) {
			if( txr[i] != undefined) {
				smid = this.testKey(this.sml,txr[i]);
				imsml = txr[i].split('-');
				if(((smid != false || smid == 0) && this.isNumber(txr[i]) != true) || (imsml != undefined && imsml[0] == '%usersmile%')) {
					if(j < 3 && this.isNumber(this.sml[smid]) != true && (this.sml[smid] != undefined || imsml[0] == '%usersmile%')) {
						if(imsml[0] == '%usersmile%') {
							txt = txt.replace("\:%usersmile%-"+imsml[1]+"\:",'<img class="cml" src="http://'+top.cfg.img+'images/smile/'+(imsml[1].toLowerCase())+'.gif" title="Именной смайлик">');
						}else{
							txt = txt.replace("\:"+txr[i]+"\:",'<img class="cml" src="http://'+top.cfg.img+'images//smile/'+(txr[i].toLowerCase())+'.gif" style="cursor:pointer" width="'+this.sml[smid+1]+'" height="'+this.sml[smid+2]+'" onclick="chat.addSmile(\''+(txr[i].toLowerCase())+'\')">');
						}
						j++;
					}
				}
			}
			i++;
		}
		return txt;
	},
	
	/* звук */
	soundchat:0,
	soundChatLine:function() {
		if(pluginlist.indexOf("Flash") != -1) {
			if( this.soundchat == 0 ) {
				$('#btn8c').attr('class','btn8c2');
				$('#btn8c').attr('title','(Включено \'тихо\') Звуковые уведомления');
				this.soundchat = 1;			
			}else if( this.soundchat == 1 ){
				$('#btn8c').attr('class','btn8c3');
				$('#btn8c').attr('title','(Включено \'громко\') Звуковые уведомления');
				this.soundchat = 2;
			}else{
				$('#btn8c').attr('class','btn8c');
				$('#btn8c').attr('title','(Выключено) Звуковые уведомления');
				this.soundchat = 0;
			}
		}else{
			if(!! document.createElement('video').canPlayType ) {
				//html5 уведомления
				alert('Поддержка звуковых уведомлений временно заблокировано на стороне сервера.');
				/*
					$('<audio id="chatAudio"><source src="notify.ogg" type="audio/ogg"><source src="notify.mp3" type="audio/mpeg"><source src="notify.wav" type="audio/wav"></audio>').appendTo('body');
					$('#chatAudio')[0].play();
				*/
			}else{
				alert("Ваш браузер не поддерживает Flash-ролики, а так-же HTML5\nДля звуковых уведомлений установите плагин Adobe Flash Player, либо обновите браузер (Подробнее см. в энциклопедии)");
			}
			//$('#soundTableTd').css({'display':'none'});
		}
	},
	
	getSwf:function(val) {
		var M$ =  navigator.appName.indexOf("Microsoft")!=-1
		return (M$ ? window : document)[val]
	},
	sendSound:function(s)
	{
		var svolm = 100;
		if(this.soundchat == 0) {
			svolm = 0;
		}else if(this.soundchat == 1) {
			svolm = 25;
		}else if(this.soundchat == 2) {
			svolm = 100;
		}
		
		/*if(window.Sound) {
			window.document["Sound"].SetVariable("Volume", svolm);
			window.document["Sound"].SetVariable("Sndplay", s);
		}else if(document.Sound) {
			document.Sound.SetVariable("Volume", svolm);
			document.Sound.SetVariable("Sndplay", s);
		}*/
		
		var M$ =  navigator.appName.indexOf("Microsoft")!=-1
		if(this.getSwf('Sound').SetVariable == undefined && !M$) {
			document.getElementById('Sound2').SetVariable("Volume",svolm);
			document.getElementById('Sound2').SetVariable("Sndplay",s);
		}else{
			window.document["Sound"].SetVariable("Volume", svolm);
			window.document["Sound"].SetVariable("Sndplay", s);
		}
	},
		
	clearChat:function() {
		if( this.canal_open == 0 ) {
			this.cid[this.canal_open] = 0;
			$('#canal'+this.canal_open).html('&nbsp;');
		}
		return true;
	},
	
	clearInput:function() {
		$('#msg_text').val('');
	},	
	iKeys:{
		'r':null,
		'u':null,
		'm':null,
		't':0
	},
	users_u_city:0,
	readData:function(data) {
		
		//Чистим данные передачи
		this.refleshData = {};
		
		if( data.online_list != undefined && data.online_list != 0 ) {
			//Выводим список онлайна
			this.readDataOnlineList( data.online_list , data.ura );
		}
		if( data.room_name != undefined && data.room_name != 0 ) {
			//Обновляем название комнаты
			if( $('#online_r2').attr('checked') == 'checked' ) {
				$('#online_room').html( data.room_name + '<br><small>Общий онлайн: ' + data.ura + '</small>' );
			}else{
				$('#online_room').html( data.room_name + ' (' + data.ura + ')' );
			}
		}
		if( data.chat_list != undefined && data.chat_list != 0 ) {
			//Выводим чат
			this.readDataChatList( data.chat_list );
			this.testScrollMessages();
		}
	},
	
	testScrollMessages:function() {
		if( $('#chat_list').get(0).scrollHeight > $('#chat_list').height() ) {
			this.messageGenXdel++;
			$('#msg' + this.messageGenXdel ).css('display','none');
			this.testScrollMessages();
		} 
	},
	
	playSoundMsg:false,
	readDataChatList:function( data ) {
		var i = 0, html = "", j = 0;
		while( i != -1 ) {
			if( data[i] != undefined ) {
				//
				if( parseInt(data[i][0]) > this.lmid ) {
					this.lmid = parseInt(data[i][0]);
					html = html + "" + this.messageGen( data[i] ) + "";
				}
				//
				j++;
			}else{
				if( this.playSoundMsg == true ) {
					this.sendSound(1);
				}
				this.playSoundMsg = false;
				i = -2;
			}
			i++;
		}
		$('#canal0').html( $('#canal0').html() + html );
	},
	
	messageGenX:0,
	messageGenXdel:0,
	messageGen:function( msg ) {
		var html = '';
		//
		this.messageGenX++;
		//
		//html += "<b>" + msg[2] + "</b>: " + this.replaceText( this.testSmile( msg[3] ) );
		//
		var foryou = 0;
		if( this.msgForyou( msg ) == true ) {
			foryou = 1;
			this.playSoundMsg = true;
		}
				
		html += this.msgTime( msg, foryou );
		html += this.msgFrom( msg );
		html += this.msgTo( msg );
		html += this.msgText( msg );
		//
		html = '<div id="msg' + this.messageGenX + '">' + html + '</div>';
		return html;
	},
	
	onlinePageSelect:1,
	
	readDataOnlineList:function( data , ura ) {
		var i = 0, html = '', j = 0;
		while( i != -1 ) {
			if( data[i] != undefined ) {
				if( data[i][0] > 0 ) {
					html = html + this.loginLine( data[i][0] , data[i][1] , data[i][2] , data[i][3] , data[i][4] , data[i][5] , data[i][6] ) + '<br>';
				}else{
					html = html + this.loginLine( 0 , 'Невидимка' , '??' , 'capitalcity' , 0 , 0 , data[i][1] ) + '<br>';
				}
				j++;
			}else{
				i = -2;
			}
			i++;
		}
		var pa = Math.ceil(ura/50) , i = 1 , pgh = '';
		
		if( this.onlinePageSelect > pa ) {
			this.onlinePageSelect = pa;
		}
		if( j < ura ) {
			//Выводим страницы
			while( i <= pa ) {
				if( this.onlinePageSelect == i ) {
					pgh += '[<b style="text-decoration:underline;color:#8f0000">' + i + '</b>]';
				}else{
					pgh += '[<a onclick="chat.selectOnlinePage(' + i + ')" class="cp">' + i + '</a>]';
				}
				i++;
			}
			pgh = '<div style="padding:10px 0 10px 0;">' + pgh + '</div>';
			html = pgh + '<div>' + html + '</div>';
			
		}
		$('#online_users').html( html );
	},
	
	selectOnlinePage:function( id ) {
		this.onlinePageSelect = id;
		this.refleshChat( false );
	},
	
	loginLine:function( id , login , level , cityreg , align , clan , fight ) {
		var r = '';
		
		if( fight > 0 ) {
			r += '<a class="cp" onClick="chat.toUser(\''+login+'\',\'private\')"><img src="/static/images/lock1.gif" width="20" height="15"></a>';
		}else{
			r += '<a class="cp" onClick="chat.toUser(\''+login+'\',\'private\')"><img src="/static/images/lock.gif" width="20" height="15"></a>';
		}
		r += '<img width="12" height="15" src="/static/images/align/align' + align + '.gif">';
		if( clan > 0 ) {
			r += '<img width="24" height="15" src="http://img.xcombats.com/i/clan/' + clan + '.gif">';
		}
		if( id == 0 ) {
			r += '<a class="cp" onClick="chat.toUser(\''+login+'\',\'to\')"><i>' + login + '</i></a>[' + level + ']';
		}else{
			r += '<a class="cp" onClick="chat.toUser(\''+login+'\',\'to\')">' + login + '</a>[' + level + ']';
		}
		r += '<a target="_blank" title="Инф. о ' + login + '" href="/userinfo/' + id + '"><img width="12" height="11" src="/static/images/inf.gif"></a>'; 
		
		return r;
	},
	
	readMessagesSound:false,
	readMessages:function(data) {
		var i = 0;
		this.readMessagesSound = false;
		while( i != -1 ) {
			if(data[i] != undefined) {	
				var ch = 0; //Канал чата
				if( this.lmid < data[i][0] ) {
					this.lmid = data[i][0];
				}
				this.addMessage( '#canal' + ch , data[i] );
				i++;
			}else{
				if( i > 0 ) {
					this.scrollChatTo(0,0);
				}
				i = -1;
			}
		}	
		if( this.readMessagesSound == true ) {
			this.sendSound(1);
			this.readMessagesSound = false;
		}
	},
	
	addMessage:function(ch, data) {
		if($('#msg_' + data[0]).attr('id') == undefined) {
				var msg = '', foryou = this.msgForyou( data );
			if( this.filter == false || this.msgTestyou(data) == true || foryou == true) {
				if(foryou) {
					this.readMessagesSound = true;
				}
				msg += this.msgTime( data, foryou );
				msg += this.msgFrom( data );
				msg += this.msgTo( data );
				msg += this.msgText( data );
				msg = '<div id="msg_' + data[0] + '">' + msg + '</div>';
				$(ch).html( $(ch).html() + msg );
			}
		}		
	},	
	msgTestyou:function(data) {
		if( user.info['login'] == data[1] ) {
			return true;
		}else{
			return false;
		}
	},
	msgForyou:function(data) {
		var r = false;

		if( user.info.login != data[2] ) {
			var i = 0, ua = data[4].split( ',' ), to = '';
			while( i < ua.length ) {
				if( ua[i] != undefined ) {
					ua[i] = GameEngine.trim(ua[i]);
					if(user.info.login == ua[i]) {
						r = true;
					}
				}
				i++;
			}
		}	
		
		return r;
	},
	msgTime:function(data, foryou) {
		var r = '';
		if( data[7] == 1 ) {
			r += GameEngine.data( 'd.m.Y H:i', data[1] );
			r = '&nbsp; <span class="date">' + r + '</span>';
		}else if( data[7] == 2 ) {
			r += GameEngine.data( 'H:i', data[1] );
			r = '&nbsp; <span class="sysdate">' + r + '</span>';
		}else if( data[7] == 3 ) {
			r += GameEngine.data( 'd.m.Y H:i', data[1] );
			r = '&nbsp; <span class="sysdate">' + r + '</span>';
		}else{
			r += GameEngine.data( 'H:i', data[1] );
			if( foryou == true || data[5] == 4 ) {
				r = '&nbsp; <span class="date2">' + r + '</span>';
			}else{
				r = '&nbsp; <span class="date">' + r + '</span>';
			}
		}
		return r;
	},	
	msgFrom:function(data) {
		var r = '';
		if(data[5] >= 1 && data[5] < 4) {
			if( data[8] != 0 ) {
				r += ' [<b><i>' + data[2] + '</i></b>]';
			}else{
				r += ' [' + this.loginChat(data[2],'to',null) + ']';
			}			
		}
		return r;
	},	
	msgTo:function(data) {
		var r = '';
		if( data[5] > 1 && data[5] < 4 ) {
			if( data[5] == 2 ) {
				r = ' to [' + data[4] + ']';
			}else if( data[5] == 3 ) {
				
				var i = 0, ua = data[4].split( ',' ), to = '';
				while( i < ua.length ) {
					if( ua[i] != undefined ) {
						ua[i] = GameEngine.trim(ua[i]);
						/*if(user.info['login'] == ua[i]) {
							to += this.loginChat( data[1], 3 );
							//foryou
						}else{*/
							to += this.loginChat( ua[i], 3 );
						//}
						if( i+1 < ua.length ) {
							to += ', ';
						}
					}
					i++;
				}
				
				r = ' <span class="private cp">private [' + to + ']</span>';
			}
			if( data[5] == 2 ) {
				r = ' <font color="' + data[6] + '">' + r + '</font>';
			}
		}
		return r;
	},	
	replaceAll:function(t,v,s)
	{
		return t.split(v).join(s);
	},
	msgText:function(data) {
		var r = '';
		var text = data[3];
		
		/*  */
		var reg = text.match(/<login>(.*?)<\/login>/g);		
		if( reg != null && reg[0] != undefined ) {
			var i = 0;
			while( i != -1 ) {
				if( reg[i] != undefined ) {
					text = this.replaceAll( text , reg[i] , this.loginChat(this.replaceAll( this.replaceAll( reg[i] , '</login>' , '' ) , '<login>' , '' ), 'to', null) );
					i++;
				}else{
					i = -1;
				}
			}
		}
		
		if( data[4] == 4 || data[4] == 5 ) {
			text = this.replaceAll( text , '{w}' , '<font color=red>Внимание!</font>');
		}
			
		text = this.testSmile(text);	
			
		r += ' <font color="' + data[6] + '">' + text + '</font>';
		return r;
	},
	
	readOnlineList:function(data) {
		var r = '';
		
		var i = 0;
		while( i != -1 ) {
			if(data[i] != undefined) {
				r += this.userInfo(data[i][0],data[i][1],data[i][2],data[i][3],data[i][4],data[i][5]) + '<br>';
				i++;
			}else{
				if( top.mob_version != undefined ) {
					mob.chat.html_return['ou'] = i;
				}else{
					$('#online_room_count').html( '(' + i + ')' );
				}
				i = -1;
			}
		}
		
		return r;
	},
	
	align_name:{
		0:'',
		1:'Светлый',
		2:'Хаосник',
		3:'Тёмный',
		7:'Нейтральный'
	},
	
	userInfo:function(id,login,level,align,clan,device) {
		var r = '';
		
		if( user.info.level > 0 ) {
			r += '<img onclick="chat.toUser(\'' + login + '\',\'private\')" title="Приват" class="onlpr cp" src="http://' + cfg.img + 'images/lock.gif">';
		}
		
		r += '<img title="' + this.align_name[Math.floor(align)] + '" class="onlal" src="http://' + cfg.img + 'images/align/align' + align + '.gif">';
		r += '<a oncontextmenu="chat.contextMenu(\''+login+'\',event); return false;" onclick="chat.toUser(\'' + login + '\',\'to\')" class="cp">' + login + '</a>';
		r += '[' + level + ']';
		r += '<a href="/userinfo/' + id + '" title="Инф. о ' + login + '" target="_blank"><img class="onlinf" src="http://' + cfg.img + 'images/inf.gif" ></a>';
		if( device != 0 ) {
			var name_device = [
			'Компьютер',
			'',
			'Apple iPhone',
			'',
			'',
			'Android Phone'
			];
			r += ' <img title="Персонаж сидит с ' + name_device[device] + '" class="onlal" src="http://' + cfg.img + 'images/m/devico_' + device + '.png" width="12" height="12">';
		}
		return r;
	},
	
	replaceText:function(txt) {
		
		txt = GameEngine.str_replace("[s2;]","'",txt);
		txt = GameEngine.str_replace("[s1;]","&quot;",txt);
		txt = GameEngine.str_replace("[s3;]","&lt;",txt);
		txt = GameEngine.str_replace("[s4;]","&gt;",txt);
		txt = GameEngine.str_replace("[s6;]","&frasl;",txt);
		txt = GameEngine.str_replace("[s5;]","&#92;",txt);
		
		return txt;
	},
	
	loginChat:function(login,typeTo,login2) {
		login = GameEngine.trim(login);
		if(login2 == null) {
			login2 = login;
		}
		if(typeTo == 3) {
			typeTo = 'private'
		}else if(typeTo == 2) {
			typeTo = 'to';
		}
		login = '<a onclick="chat.toUser(\''+login2+'\',\''+typeTo+'\')" class="cp" oncontextmenu="chat.contextMenu(\''+login+'\',event); return false;">'+login+'</a>';
		return login;
	},
	
	context_open:false,
	context_global_open:false,
	context_lock:[0,0],
	contextMenuGlobal:function(fm,e) {
		
		if( this.context_lock[1] == 0 ) {
			
			this.contextmenu_close();
						
			if (!e) e = window.event;	
			
			fm = 0;
			
			var menu = null;
			
			if(fm == 'main') {
				
			}else if(fm == 'chat') {
				
			}else if(fm == 'online') {
				
			}else{
				menu = [
					[1,'Настройки меню',''],
					[0],
					[1,'Закрыть','chat.contextmenu_global_close();']
				];
			}
			
			var i = 0, menu_html = '';
			while( i < menu.length ) {
				
				if( menu[i][0] == 0 ) {
					
					menu_html += '<hr>';
					
				}else if( menu[i][0] == 1 ) {
					
					menu_html += '<div onclick="'+menu[i][2]+'">'+menu[i][1]+'</div>';
					
				}
				
				i++;
			}
			
			if( menu_html != '' ) {
				var x = e.clientX-1,y = e.clientY-1;
				/* pageX , pageY */
				$('#context_menu_global').html( '<span class="pr db" oncontextmenu="return false">'+menu_html+'</span>' );
				$('#context_menu_global').css({'display':'block','top':y+'px','left':x+'px'});
				GameEngine.divxyBanned('#context_menu_global');
				clearTimeout( this.timers[3] );
				this.context_lock[0] = 1;
				this.timers[3] = setTimeout( 'chat.context_global_open=true;chat.context_lock[0]=0;', 25 );
			}else{
				return false;	
			}
		}
	},
	
	contextmenu_global_close:function() {
		$('#context_menu_global').html('');
		$('#context_menu_global').css({'display':'none'});
		clearTimeout( this.timers[3] );
		this.timers[3] = setTimeout( 'chat.context_global_open=false;', 25 );
	},
	
	contextMenu:function(login,e) {
		
		if( this.context_lock[0] == 0 ) {
			
			this.contextmenu_global_close();
			
			if (!e) e = window.event;
			
			if (pluginlist.indexOf("Flash")!=-1) {
				$('#context_menu').html(
				'<span class="pr db" oncontextmenu="return false">'+
					'<div onclick="chat.toUser(\''+login+'\',\'to\');chat.contextmenu_close();">TO</div>' +
					'<div onclick="chat.toUser(\''+login+'\',\'private\');chat.contextmenu_close();">PRIVATE</div>' +
					'<a class="db" href="/userinfo/'+login+'" onclick="setTimeout(function(){chat.contextmenu_close();},0);" target="_blank"><div>INFO</div></a>' +
					/*'<div onclick="alert(\'Функция временно не работает\');chat.contextmenu_close();">TO FRIENDS</div>' +*/
					'<div id="copyline" name="copyline" data-clipboard-text="'+login+'">COPY</div>'+
				'</span>'
				);
				$('#context_menu').css({'display':'block'});
				this.contextMenubegin();	
			}else{
				$('#context_menu').html(
				'<span class="pr db" oncontextmenu="return false">'+
					'<div onclick="chat.toUser(\''+login+'\',\'to\')">TO</div>' +
					'<div onclick="chat.toUser(\''+login+'\',\'private\')">PRIVATE</div>' +
					'<a class="db" href="/userinfo/'+login+'" target="_blank"><div>INFO</div></a>' +
					/*'<div onclick="alert(\'Функция временно не работает\')">TO FRIENDS</div>' +*/
				'</span>'
				);
			}
			var x = e.clientX-1,y = e.clientY-1;
			/* pageX , pageY */			
			$('#context_menu').css({'display':'block','top':y+'px','left':x+'px'});
			GameEngine.divxyBanned('#context_menu');
			clearTimeout( this.timers[2] );
			this.context_lock[1] = 1;
			this.timers[2] = setTimeout( 'chat.context_open=true;chat.context_lock[1]=0;', 25 );
		}
	},
	
	contextmenu_close:function() {
		$('#context_menu').html('');
		$('#context_menu').css({'display':'none'});
		clearTimeout( this.timers[2] );
		this.timers[2] = setTimeout( 'chat.context_open=false;', 25 );
	},
	
	clip:false,
	contextMenubegin:function() {
		this.clip = new ZeroClipboard( document.getElementById("copyline"), {
		  moviePath: "http://" + cfg.img + "flash/ZeroClipboard.swf"
		} );
		
		this.clip.on( 'load', function(client) {
		  // alert( "movie is loaded" );
		} );
		
		this.clip.on( 'complete', function(client, args) {
		  chat.contextmenu_close();
		} );
		
		this.clip.on( 'mouseover', function(client) {
		   $('#copyline').attr('class','context_menudivhover');
		} );
		
		this.clip.on( 'mouseout', function(client) {
		   $('#copyline').attr('class','');
		} );
		
		this.clip.on( 'mousedown', function(client) {
			$('#copyline').attr('class','context_menudivhover');
		} );
		
		this.clip.on( 'mouseup', function(client) {
			$('#copyline').attr('class','context_menudivhover');
		} );
	},
	
	scrlst:0,
	scrollChatTo:function(val,par) {
		$('#chat_list').stop();
		$('#chat_list').animate({ scrollTop: $('#chat_list')[0].scrollHeight }, par);
	},
	
	user:function(id,login,level,align,clan) {
		var r = '';
		if(level < 1) {
			level = 0;
		}
		r += '<div><img class="db fl cp" onClick="chat.toUser(\''+login+'\',\'private\')" src="/i/i/lock.gif" title="Написать в приват" width="20" height="15"><img class="db fl" src="/i/i/align/align'+align+'.gif" width="12" height="15"><tp1><a onClick="chat.toUser(\''+login+'\',\'to\')" oncontextmenu="chat.contextMenu(\''+login+'\',event); return false;" class="cp">'+login+'</a> ['+level+']</tp1><a target="_blank" href="/userinfo/'+id+'"><img class="ii" src="/i/i/inf.gif"></a></div>';
		return r;
	},
	
	toUserAll:function(users,type) {
		var i = 0, ua = users.split( ',' );
		while( i < ua.length ) {
			if( ua[i] != undefined ) {
				ua[i] = GameEngine.trim(ua[i]);
				this.toUser(ua[i],type);
			}
			i++;
		}
	},
	
	toUser:function(login,type2) {
		var loginaddT = login;
		var s = '';
		if( top.mob_version != undefined ) {
			$('#chat_message').focus();
			s = $('#chat_message').val();
		}else{
			if($('#'+$(this.inObj).attr('id')).attr('id') == undefined)
			{		
				$('#msg_text').focus();
				s = $('#msg_text').val();
			}else{
				$(this.inObj).focus();
				//s = $(inObj).val();
			}
		}

		var reg555 = new RegExp("private\\s*\\[(.*?)\\]","");
		var reg551 = new RegExp("to\\s*\\[(.*?)\\]","");
		
		var test1 = s.match(reg555);
		if(s.match(reg555)==null)
		{
			type = "to";
		}else if(s.match(reg551)==null)
		{
			type = "private";
		}
		
		var type3 = 'to';
				
		var reg2 = new RegExp(""+type+"(\\s*)\\[(.*?)\\]","");
		var cs = s.replace(reg2,""+type+"$1[,$2,]");
		var slogin = login.replace(/([\^.*{}$%?\[\]+|\/\(\)])/g,"\\$1");
		var reg = new RegExp(""+type+"\\s*\\[.*,\\s*"+slogin+"\\s*,.*\\]","");
		var result = '';
		var reg3 = new RegExp(""+type+"\\s*\\[(.*?)\\]","");				
		while (res = s.match(reg3))
		{
			result += res[1]+',';
			s = s.replace(reg3,'');
		}
		result = result.replace(/,$/,'');
		var prar = result.split(',');
		for (i=0;i<prar.length;i++)
		{
			prar[i] = prar[i].replace(/^\s+/,'');
			prar[i] = prar[i].replace(/\s+$/,'');
		}
		var str = prar.join(', ');
		if (str) login += ', ';
		space = '';
		if (!s.match(/^\s+/)) space = ' ';
		var prob = '';
		if($('#'+$(this.inObj).attr('id')).attr('id') == undefined && (this.inObj == null || $('#main').contents().find('#'+this.inObj.id).attr('id') == undefined))
		{
			if (!cs.match(reg))
			{
				if(type2=='to')
				{
					if(test1!=null)
					{
						type2 = 'private';
				}
				}
				s = type2+' ['+prob+''+login+str+''+prob+']'+space+s;
			} else {				
				if(type3=='to')
				{
					type3 = "private";
				}
				s = type3+' ['+prob+''+str+''+prob+']'+space+s;
			}
		}else{
			s = login+str;
		}
		
		if( top.mob_version != undefined ) {
			$('#chat_message').val(s);
		}else if($('#'+$(this.inObj).attr('id')).attr('id') == undefined)	{		
			$('#msg_text').val(s);
		}else{
			$(this.inObj).val(s);
		}
	}
 };