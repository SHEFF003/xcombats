var sml_img = { };

Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = 0, len = this.length; i < len; i++) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

var chat = {
	key:'',
	room:'',
	count:0,
	time:0,
	t:null, //timer
	t2:null, //timer 2
	t_all:{}, //time molch
	r:0,
	g:0,
	rtime:37,
	ct:{ '-1':0, '1':15, '2':30, '3':60, '4':300},
	saveData:null,
	msg_id:0,
	nrg:0,
	nozpros:0,
	newmsg:0,
	sound:0,
	translit:0,
	filter:0,
	globalMsg:0,
	ignoreList:{x:0,nms:[]},
	citySys:0,
	inObj:null,
	efftxt:function(id,txt) {
		var r = txt;
		/*if( id == 'fire' ) {
			r = '<svg viewBox="0 0 600 300"><pattern id="p-fire" viewBox="10 100 186 200" patternUnits="userSpaceOnUse" width="216" height="200" x="-70" y="35"><image xlink:href="img/fire.gif" width="256" height="300"/></pattern><text text-anchor="middle" x="50%" y="50%" dy=".35em" class="text">';
			r += txt;
			r += '</text></svg>';
		}*/
		return r;
	},
	ignore:function(login)
	{
		if(this.ignoreList[login]!=undefined)
		{
			if($('#ignr_alu').attr('id')!=undefined)
			{
				$('#ignr_u_'+this.ignoreList[login]).remove();
			}
			delete this.ignoreList.nms[this.ignoreList[login]];
			delete this.ignoreList[login];	
			//msg
			
		}else{ 
			this.ignoreList.x++;
			this.ignoreList[login] = this.ignoreList.x;
			this.ignoreList.nms[this.ignoreList.x] = login;
			if($('#ignr_alu').attr('id')!=undefined)
			{
				$('#ignr_alu').html($('#ignr_alu').html()+'<div id="ignr_u_'+this.ignoreList.x+'">    <b>'+login+'</b> <a target="_blank" href="http://xcombats.com/info/'+login+'"><img src="http://'+top.c.img+'/i/inf_capitalcity.gif" title="Инф. о '+login+'"></a>   <small><a href="javascript:void(0)" onclick="chat.ignorUn('+this.ignoreList.x+')">Clear</a></small>  </div>');
			}
			//msg
			
		}
	},
	getRandom:function(a, b){
	 
		return a + ( (b-a) * Math.random() );
	 
	},
	feerverk_id:0,
	feerverk:function(name) {
		if( name == 'fw04' ) {
			var frc = {
				'name':'fw04',
				'x':19,
				'top':this.getRandom(1,35),
				'left':this.getRandom(0,365),
				'width':135,
				'sound':this.getRandom(8,10),
				'height':99,
				'zad':3	
			};
			frc.left -= 35;
		}
		
		if( frc.name != undefined ) {
			this.sendSound( frc.sound );
			var obj = top.frames.main.document.getElementById('frvrks');
			if( obj != undefined ) {
				var newhtml = '';
				var i = 1;
				while( i <= frc.x ) {
					newhtml += '<img style="display:none" id="frvanim_'+this.feerverk_id+'_img'+i+'" width="'+frc.width+'" height="'+frc.height+'" src="http://img.xcombats.com/fw/'+frc.name+'/'+i+'.gif">';
					i++;
				}
				newhtml = '<div id="frvanim_'+this.feerverk_id+'" style="z-index:5000;position:absolute;width:'+frc.width+'px;height:'+frc.height+'px;left:'+frc.left+'px;top:'+frc.top+'px;">'+newhtml+'</div>';
				$(obj).append(newhtml);
				this.feerverk_go( this.feerverk_id , frc.x-1 , frc.name , frc.x , frc.zad );
				this.feerverk_id++;
			}
		}else{
			//alert('Эффект не опознан!');
		}
	},
	feerverk_go:function(id,time_back,img,x,zad) {
		if( zad > 0 ) {
			setTimeout('chat.feerverk_go('+id+','+time_back+',"'+img+'",'+x+',0);',500*zad);
		}else{
			time_back--;
			var obj = top.frames.main.document.getElementById('frvanim_'+id);
			if( time_back > 0 ) {
				var img1 = top.frames.main.document.getElementById('frvanim_'+id+'_img'+(x-time_back+1)); //текущая
				if( img1 != undefined ) {
					img1.style.display = 'none';
				}
				var img2 = top.frames.main.document.getElementById('frvanim_'+id+'_img'+(x-time_back+2)); //следующая
				if( img2 != undefined ) {
					img2.style.display = '';
				}
				setTimeout('chat.feerverk_go('+id+','+time_back+',"'+img+'",'+x+',0);',50);
			}else{
				top.frames.main.document.getElementById('frvanim_'+id).remove();
			}
		}
	},
	ignorUn:function(x)
	{
		$('#ignr_u_'+x).remove();
		this.ignore(this.ignoreList.nms[x]);
	},
	ignorListOpen:function()
	{
		var date = '',i = 0;
		var j = 1;
		while(j<=this.ignoreList.x)
		{
			if(this.ignoreList[this.ignoreList.nms[j]]!=undefined)
			{
				date += '<div id="ignr_u_'+j+'">    <b>'+this.ignoreList.nms[j]+'</b> <a target="_blank" href="http://xcombats.com/info/'+this.ignoreList.nms[j]+'"><img src="http://'+top.c.img+'/i/inf_capitalcity.gif" title="Инф. о '+this.ignoreList.nms[j]+'"></a>   <small><a href="javascript:void(0)" onclick="chat.ignorUn('+j+')">Clear</a></small>  </div>';
			}
			j++;
		}
		win.add('ignorListWin','Список игнорируемых','<div id="ignr_alu">'+date+'</div>',{},0,1,'min-width:200px;');
		delete date;
	},
	addSmile:function(id)
	{
		$('#textmsg').val($('#textmsg').val()+' :'+id+': ');
		$('#textmsg').focus();
	},
	lookSmiles:function()
	{
		if($('#chbtn8').attr('class')=='db cp chatBtn8_1')
		{
			$('#ttSmiles').css('display','');
			$('#chbtn8').attr('class','db cp chatBtn8_2');
		}else{
			$('#ttSmiles').css('display','none');
			$('#chbtn8').attr('class','db cp chatBtn8_1');
		}
	},
	filterMsg:function()
	{
		if($('#chbtn1').attr('class')=='db cp chatBtn1_1')
		{
			$('#chbtn1').attr('class','db cp chatBtn1_2'); this.filter = 1;
		}else{
			$('#chbtn1').attr('class','db cp chatBtn1_1'); this.filter = 0;
		}
	},
	systemMsg:function()
	{
		if($('#chbtn4').attr('class')=='db cp chatBtn4_1')
		{
			$('#chbtn4').attr('class','db cp chatBtn4_2'); this.citySys = 1; $.cookie('citySys',1);
		}else{
			$('#chbtn4').attr('class','db cp chatBtn4_1'); this.citySys = 0; $.cookie('citySys',0);
		}
	},
	soundChat:function()
	{
		if($('#chbtn7').attr('class')=='db cp chatBtn7_1')
		{
			$('#chbtn7').attr('class','db cp chatBtn7_2'); this.sound = 1;
		}else if($('#chbtn7').attr('class')=='db cp chatBtn7_2'){
			$('#chbtn7').attr('class','db cp chatBtn7_3'); this.sound = 2;
		}else{
			$('#chbtn7').attr('class','db cp chatBtn7_1'); this.sound = 0;
		}
	},
	translitChat:function()
	{
		if($('#chbtn6').attr('class')=='db cp chatBtn6_1')
		{
			$('#chbtn6').attr('class','db cp chatBtn6_2'); this.translit = 1;
		}else{
			$('#chbtn6').attr('class','db cp chatBtn6_1'); this.translit = 0;
		}
	},subValSend:'',
	subSend:function()
	{
		if($('#textmsg').val()!='')
		{
			if($('#textmsg').val()!=this.subValSend)
			{
				if(this.nozpros == 0) {
				this.trmb();
				$.post('online.php?r'+c.rnd+'&cas'+((new Date().getTime()) + Math.random()),{msg:$('#textmsg').val(),trader:$('#trader_val').val(),key:this.key,mid:this.msg_id,rndo:c.rnd,tgfs:tgf_status},function(data){ chat.clearText(); chat.fc(); chat.genchatData(data,1); });
				this.subValSend = $('#textmsg').val();
				this.nozpros=1;
				setTimeout('chat.subValSend="";chat.nozpros=0;',1000);
				}
			}
		}
	},
	addto:function(login, type2) {
		var loginaddT = login;
		var s = '';
		if($('#'+$(this.inObj).attr('id')).attr('id') == undefined) {		
			$('#textmsg').focus();
			s = $('#textmsg').val();
		} else {
			$(this.inObj).focus();
		}
		var reg555 = new RegExp("private\\s*\\[(.*?)\\]","");
		var reg551 = new RegExp("to\\s*\\[(.*?)\\]","");
		var test1 = s.match(reg555);
		if(s.match(reg555) == null) {
			type = "to";
		} else if(s.match(reg551) == null) {
			type = "private";
		}
		var type3 = 'to';	
		var reg2 = new RegExp(""+type+"(\\s*)\\[(.*?)\\]","");
		var cs = s.replace(reg2,""+type+"$1[,$2,]");
		var slogin = login.replace(/([\^.*{}$%?\[\]+|\/\(\)])/g,"\\$1");
		var reg = new RegExp(""+type+"\\s*\\[.*,\\s*"+slogin+"\\s*,.*\\]","");
		var result = '';
		var reg3 = new RegExp(""+type+"\\s*\\[(.*?)\\]","");				
		while(res = s.match(reg3)) {
			result += res[1]+',';
			s = s.replace(reg3,'');
		}
		result = result.replace(/,$/,'');
		var prar = result.split(',');
		for(i = 0; i < prar.length; i++) {
			prar[i] = prar[i].replace(/^\s+/,'');
			prar[i] = prar[i].replace(/\s+$/,'');
		}
		var str = prar.join(', ');
		if(str) login += ', ';
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
				if(type3 == 'to') {
					type3 = "private";
				}
				s = type3+' ['+prob+''+str+''+prob+']'+space+s;
			}
		} else {
			s = login+str;
		}
		
		if(this.inObj != null && $('#main').contents().find('#'+this.inObj.id).attr('id') != undefined) {
			$('#main').contents().find('#'+this.inObj.id).val(s);
		} else if($('#'+$(this.inObj).attr('id')).attr('id') == undefined) {		
			$('#textmsg').val(s);
		} else {
			$(this.inObj).val(s);
		}
	},
	clearText:function()
	{
		$('#textmsg').val('');
	},
	reflesh:function()
	{
		this.time = 0;
		this.testTimer(true);
	},
	reMoney:function()
	{
		$('#moneyGM').html(top.c.money+' кр.');
	},
	btl:0
	,
	testTimer:function(n)
	{
		clearTimeout(this.t);
		/*if(top.c.money < 100)
		{
			$('#moneyGM').height('50px');
		}else if(top.c.money < 1000)
		{
			$('#moneyGM').height('70px');
		}else{
			$('#moneyGM').height('100px');
		}*/
		if($.cookie('nasta') == 'tester') {
			top.nastavnikNew();
		}
		if($.cookie('btl') != this.btl) {
			if($.cookie('btl') > 0) {
				this.sendSound(2);
				if(top.frames['main'].smnpty != undefined) {
					
				}else{
					top.frames['main'].location.href="main.php";
				}
			}
			this.btl = $.cookie('btl');
		}
		if(this.rtime!=this.ct[$.cookie('chatCfg0')] && this.ct[$.cookie('chatCfg0')]!=undefined)
		{
			this.rtime = this.ct[$.cookie('chatCfg0')];
			if(this.time>this.rtime)
			{
				this.time = this.rtime;
			}
		}
		if(this.rtime>=30 || this.r==0)
		{
			if(this.time < 1)
			{
				var aot = {
					0:0,
					1:1,
					2:0
				};
				if($('#chcf10').attr('checked')==true)
				{
					aot[2] = 1;
				}
				if($('#autoRefOnline').attr('checked')==true || this.r==0 || n!=false)
				{
					aot[0] = 1;
				}
				//alert('chat.reflesh.undefined()');
				if(this.nozpros == 0) {
					$.getJSON('online.php?r'+c.rnd+'&cas'+((new Date().getTime()) + Math.random()),{key:this.key,mid:this.msg_id,r1:aot[0],r2:aot[1],r3:aot[2],rndo:c.rnd},function(data){if(data.rnd!=null){chat.genchatData(data);if(data.key!=undefined){chat.saveData=data;}this.g++;c.rnd = data.rnd;}});
					this.nozpros=1;
					setTimeout('chat.nozpros=0;',1000);
				}
				this.time = this.rtime; this.r++;
			}else{
				this.time--;				
			}
			this.t = setTimeout('chat.testTimer(false);clearTimeout(this.t);',1000);
		}
	},
	gUser:function(data,ol)
	{
		var rt = '';
		if(data[1]!=undefined)
		{
			rt = data[1];
			if( rt == 'Шаман' ) {
				rt = this.efftxt('fire',rt);
			}
			if(ol==true)
			{
				rt = '<a href="javascript:void(0)" onClick="chat.addto(\''+data[1]+'\',\'to\')">'+rt+'</a>';
			}else{
				rt = '<b>'+rt+'</b>';
			}
			if(data[13]!=0)
			{
				rt = '<span class="uCss'+data[13]+'">'+rt+'</span>';
			}
			
			if(data[10]>0)
			{
				rt = '<s onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\'Персонаж был заблокирован\',event,3,1,1,2,\'\')">'+rt+'</s>';
			}
			
			if(data[8]!=0)
			{				
				data[8] = this.replaceAll(data[8],"\\",'\\\\');
				data[8] = this.replaceAll(data[8],"[s1;]",'"');
				data[8] = this.replaceAll(data[8],"[s2;]",'`');
				data[8] = this.replaceAll(data[8],"[s3;]",'');
				data[8] = this.replaceAll(data[8],"[s4;]",'');
				data[8] = this.replaceAll(data[8],"<",'');
				data[8] = this.replaceAll(data[8],">",'');
				rt = ' <small onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\' '+data[8]+' \',event,3,1,1,2,\'\')"><b>afk</b></small> '+rt;
			}else if(data[9]!=0)
			{
				data[9] = this.replaceAll(data[9],"\\",'\\\\');
				data[9] = this.replaceAll(data[9],"[s1;]",'"');
				data[9] = this.replaceAll(data[9],"[s2;]",'`');
				data[9] = this.replaceAll(data[9],"[s3;]",'');
				data[9] = this.replaceAll(data[9],"[s4;]",'');
				data[9] = this.replaceAll(data[9],"<",'');
				data[9] = this.replaceAll(data[9],">",'');
				rt = ' <small onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\' '+data[9]+' \',event,3,1,1,2,\'\')"><b>dnd</b></small> '+rt;
			}

			if(data[4]!=0)
			{
				rt = '<a href="/clan/'+data[4]+'" title="'+data[4]+'" target="_blank"><img width="24" height="15" src="http://'+c.img+'/i/clan/'+data[4]+'.gif"></a>'+rt;
			}
			
			rt = '<img height="15" src="http://'+top.c.img+'/i/align/align'+data[3]+'.gif">'+rt;
						
			if(c.lvl>-1)
			{
				if(c.city==data[6])
				{
					if(data[12]>0)
					{
						rt = '<a href="javascript:void(0)" onClick="chat.addto(\''+data[1]+'\',\'private\')"><img title="Персонаж сражается" src="http://'+c.img+'/i/lock1.gif" width="20" height="15"></a>'+rt;	
					}else{
						rt = '<a href="javascript:void(0)" onClick="chat.addto(\''+data[1]+'\',\'private\')"><img src="http://'+c.img+'/i/lock.gif" width="20" height="15"></a>'+rt;	
					}
				}else{
					rt = '<img style="padding-right:3px;" onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\'Персонаж сейчас в '+data[6]+'\',event,3,1,1,2,\'\')" src="http://'+c.img+'/i/city_ico/'+data[6]+'.gif" width="17" height="15">'+rt;
				}
			}
			rt += '['+data[2]+']<a href="http://'+c.url+'/info/'+data[0]+'" target="_blank"><img style="vertical-align:baseline" width="12" height="11" src="http://'+top.c.img+'/i/inf_'+data[5]+'.gif" title="Инф. о '+data[1]+'"></a>';
			if(data[11]>top.c.time)
			{
				rt +=  ' <img id="img_molch'+data[1]+'" width="24" height="15" style="cursor:help" src="http://'+c.img+'/i/sleep2.gif" onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\'На персонажа наложено заклятие молчания.<br>Будет молчать еще <span id=\\\'molch'+data[0]+'\\\'>'+this.timeOut(data[11])+'</span>\',event,3,1,1,2,\'\');chat.justRefMolch('+data[0]+')">';
				this.addRefMolch(data[0],data[11]);
			}
			if(data[14]!="")
			{
				rt +=  ' <img width="24" height="15" style="cursor:help" src="http://'+c.img+'/i/travma2.gif" onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\'У персонажа '+data[14]+'\',event,3,1,1,2,\'\');">';
			}
			if(data[13] > 0)
			{
				if( data[13] == 2 ) {
					rt += ' <a target="main" href="/main.php?atak_user='+data[0]+'" title="Кровавое нападение на '+data[1]+'"><img width="13" height="13" src="http://'+c.img+'/i/clear.gif"></a>';
				}else{
					rt += ' <a target="main" href="/main.php?atak_user='+data[0]+'" title="Напасть на '+data[1]+'"><img width="13" height="13" src="http://'+c.img+'/i/curse_attack.gif"></a>';
				}
			}
			if( data[15] == 1 ) {
				rt = '<span class=woman >'+rt+'</span>'
			}else{
				//rt = ' M';
			}
		}else{
			rt = '<i>невидимка</i>[??]';
		}
		return rt;
	},mlch:{},
	justRefMolch:function(id)
	{

		$('#molch'+id).html(this.timeOut(this.mlch[id]));
	},
	addRefMolch:function(id,tm)
	{
		this.t_all[id] = setTimeout('chat.refMolch('+id+')',1000);
		this.mlch[id] = tm;
	},
	refMolch:function(id)
	{		
		clearTimeout(this.t_all[id]);
		if(this.mlch[id]>0)
		{
			$('#molch'+id).html(this.timeOut(this.mlch[id]));
			this.t_all[id] = setTimeout('chat.refMolch('+id+')',1000);
		}else{
			$('#img_molch'+id).remove();
			delete this.mlch[id];
			delete this.t_all[id];
		}
	},
	fc:function()
	{
		$('#textmsg').focus();
	},
	timeOut:function(v)
	{
        		
		msPerDay = '';
		
		dt = new Date(); 
   		dt.setTime((v-c.time)*1000); 
    	
		m1   = dt.getUTCMonth();
		d1   = dt.getUTCDay();
		h1   = dt.getUTCHours();
		min1 = dt.getUTCMinutes();
		sec  = dt.getUTCSeconds();

		if(m1>0)
		{
			msPerDay = m1+' мес. ';
		}
		if(d1>0 && Math.floor((v-c.time)/(60*60*24)) == d1)
		{
			msPerDay = d1+' д. ';
		}
		if(h1>0)
		{
			msPerDay += h1+' ч. ';
		}
		if(min1>0)
		{
			msPerDay += min1+' мин. ';
		}
		if(sec>0 && msPerDay != '')
		{
			msPerDay += sec+' сек. ';
		}
		if(msPerDay == '')
		{
			msPerDay = 'меньше минуты.';
		}
		
		delete m1;
		delete d1;
		delete h1;
		delete min1;
		delete sec;
		
		return msPerDay;
	},
	deleteMessage:function(id,fc)
	{
		//$('#msg_'+id).hide('slow'); setTimeout("$('#msg_'+id).remove();",1000);
		if(fc == 1) {
			top.msgdeleted(id);
		}else{
			$('#msg_'+id).remove();
			$('#msg_'+id+'_sys').remove();
			if(top.c.admin>0)
			{
				$.post('online.php?jack='+c.rnd+'&cas'+((new Date().getTime()) + Math.random()),{delMsg:id});
			}
		}
	},
	clear:function()
	{
		if($('#textmsg').val()=='')
		{
			if(confirm('Очистить окно чата?'))
			{
				//if(top.cb_date[top.cb_select] == 4 || top.cb_date[top.cb_select] == 5) {
					$('#canal'+top.cb_date[top.cb_select]).html('');
					$('#textmsg').focus();
				//};
			}
		}else{
			$('#textmsg').val('');
		}
	},
	scrollNow:function(id)
	{
		if(top.cb_date[top.cb_select] == 5 || top.cb_date[top.cb_select] == 4) {
			//alert(id);
			$('#chat_list').stop();
			$('#chat_list').animate({ scrollTop: $('#chat_list')[0].scrollHeight }, 1000);
			//$('#chat_list').scrollTop();
		}
	},
	msgcount:0,
	sendMsg:function(data)
	{
		var msg_see = 1;
		
		var global_type = 0;
		
		if( data[5] != undefined ) {
			if( data[5].substring(0,7) == 'global:' ) {
				global_type = 1;
				data[5] = data[5].substring(7);
			}
		}
		
		if(data[0]=='new')
		{
			data[0] = 'new_msg_'+this.newmsg; this.newmsg++;
		}
		if(data[2] == 'delete')
		{
			this.deleteMessage(data[0]);
		}else if(data['d']>0)
		{
			this.deleteMessage(data['d']);
		}else if(data['s']>0)
		{
			this.deleteMessage(data['s']);
		}else if(data[0]!=undefined && top.document.getElementById('msg_'+data[0]) == undefined)
		{
			var msg  = '';
				if(data[0]!=0)
				{
					if(top.c.admin > 0) {
						if(data[12] == 1) {
							msg += '<small style="color:red;text-decoration:blink"> <b>un</b>active </small>';
						}
					}
					if(data[3]!='')
					{
						if(data[16] > 0) {
							msg += '[<a href="javascript:void(0)" oncontextmenu="top.infoMenu(\'Невидимка\',event,\'chat\'); return false;" onClick="chat.addto(\'Невидимка\',\'to\')">'+data[3]+'</a>]';
						}else{
							msg += '[<a href="javascript:void(0)" oncontextmenu="top.infoMenu(\''+data[3]+'\',event,\'chat\'); return false;" onClick="chat.addto(\''+data[3]+'\',\'to\')">'+data[3]+'</a>]';
						}
						if(chat.ignoreList[data[3]]!=undefined)
						{
							msg_see = 0;
						}
					}
					if(data[4]!='')
					{
						var forYou = 0;
						//тот кто писал
						
						//кому написали, разбор массива
						if(data[4]!='')
						{
							var to = '',to2 = '',arr = data[4].split(','),i = 0,vl = '';								
							//тем кому писали
							while(i!=-1)
							{
								if(arr[i]!=undefined)
								{
									vl = this.trim(arr[i]);
									if(vl.toLowerCase() == top.c.login.toLowerCase())
									{
										forYou++;
									}
									if(vl.toLowerCase() == top.c.login.toLowerCase())
									{
										vl = this.trim(data[3]);
									}
									if(i>0)
									{
										to += ', ';
										to2 += ', ';
									}
									if(data[3]!='')
									{
										to += '<span style="cursor:pointer" onclick="chat.addto(\''+vl+'\',\'private\');" oncontextmenu="top.infoMenu(\''+this.trim(arr[i])+'\',event,\'chat\'); return false;">'+this.trim(arr[i])+'</span>';
										if(this.trim(arr[i].toLowerCase()) != top.c.login.toLowerCase())
										{
											to2 += this.trim(arr[i]);
										}else{
											if(data[2]==2)
											{
												to2 += this.trim(arr[i]);
											}else{
												to2 += this.trim(vl);
											}
										}
									}
								}else{
									i = -2;
								}
								i++;
							}
						}
						
						if(data[2] == 6 || data[2] == 8) {
							var zmlogin = new RegExp("\\[login:(.*?)\\]","");	
							var reflcd = new RegExp("\\[reflesh_main_zv_priem:(.*?)\\]","");	
							if(data[5].match(zmlogin)!=null)
							{
								zmlogin = data[5].match(zmlogin)[1];
								data[5] = data[5].replace('[login:'+zmlogin+']','<a onMouseDown="top.loginGo(\''+zmlogin+'\',event);" oncontextmenu="top.infoMenu(\''+zmlogin+'\',event,\'chat\'); return false;" title="'+zmlogin+'" style="cursor:pointer;" onClick="chat.multiAddto(\''+zmlogin+'\',\'to\');">'+zmlogin+'</a>');
							}
							if(data[5].match(reflcd)!=null)
							{
								reflcd = data[5].match(reflcd)[1];
								data[5] = data[5].replace('[reflesh_main_zv_priem:'+reflcd+']','');
							}	
						}
						
						//Собираем массив кому адресовано сообщение
						if(data[2]==6)
						{
							//личная системка, внимание
							msg += ' <span style="color:red">Внимание!</span> ';
						}else if(data[2]==2)
						{
							if(forYou>0)
							{
								msg += ' <span style="color:'+data[6]+'"><b>to ['+to2+']</b></span>';
							}else{
								msg += ' <span style="color:'+data[6]+'">to ['+to2+']</span>';
							}
						}else if(data[2]==3)
						{
							if(this.trim(data[3].toLowerCase()) == top.c.login.toLowerCase())
							{
								forYou++;
							}
							if(data[3]!='')
							{
								if(data[4]=='klan' && data[2]==3)
								{
									msg += ' <span class="klan"><span style="cursor:pointer" onclick="chat.multiaddto(\'klan\',\'private\');">private [klan]</span></span>';
								}else if(data[4]=='paladins' && data[2]==3)
								{
									msg += ' <span class="klan"><span style="cursor:pointer" onclick="chat.multiaddto(\'paladins\',\'private\');">private [paladins]</span></span>';
								}else if(data[4]=='tarmans' && data[2]==3)
								{
									msg += ' <span class="klan"><span style="cursor:pointer" onclick="chat.multiaddto(\'tarmans\',\'private\');">private [tarmans]</span></span>';
								}else{
									
									msg += ' <span class="private"><span style="cursor:pointer" onclick="chat.multiaddto(\''+to2+'\',\'private\');">private [ </span>'+to+'<span style="cursor:pointer" onclick="chat.multiAddto(\''+to2+'\',\'private\');"> ]</span></span>';
								}
							}
						}						
					}
					msg += ' ';
				}
				
				data[5] = this.replaceAll(data[5],"[s1;]",'"');
				data[5] = this.replaceAll(data[5],"[s2;]",'\'');
				data[5] = this.replaceAll(data[5],"[s3;]",'<');
				data[5] = this.replaceAll(data[5],"[s4;]",'>');
				
				if($.cookie('chatCfg2')!=0)
				{
					data[5] = chat.testSmile(data[5]);
				}
				
				if(data[6]!='Black' && data[6]!='')
				{
					msg += '<font color="'+data[6]+'">'+data[5]+'</font>';
				}else{
					msg += data[5];
				}
				
				if(data[2] == 21) {						
					//e text
					var text = '[loginfrom] '+data[5];
					var ftps = '<i><a href="javascript:void(0)" oncontextmenu="top.infoMenu(\''+data[3]+'\',event,\'chat\'); return false;" onClick="chat.addto(\''+data[3]+'\',\'to\')">'+data[3]+'</a></i>';
					var mblogin = new RegExp("\\[login:(.*?)\\]","");
					text = text.replace('[loginfrom]',ftps);
					if(text.match(mblogin)!=null)
					{
						mblogin = text.match(mblogin)[1];
						text = text.replace('[login:'+mblogin+']','<i><a href="javascript:void(0)" oncontextmenu="top.infoMenu(\''+mblogin+'\',event,\'chat\'); return false;" onClick="chat.addto(\''+mblogin+'\',\'to\')">'+mblogin+'</a></i>');
					}
					msg = '<i>'+text+'</i>';			
				}
				
				if(data[1]>0)
				{
					var td = new Date((parseInt(data[1]))*1000);
					td = [td,null,null,null];
					td[1] = td[0].getHours();
					td[2] = td[0].getMinutes();
					td[3] = td[0].getSeconds(); 
					td[4] = td[0].getDay(); 
					td[5] = td[0].getMonth();
					td[6] = td[0].getYear();
					var j = 1;while(j<6){if(td[j]<10){td[j]='0'+td[j];}j++;}
					var cls = '';
					if(forYou>0)
					{
						cls = 'date2';
					}else{
						cls = 'date';
					}
					if(data[11]>0)
					{
						msg = '<font style="cursor:help" color="red" onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\'Отправитель наказан за нарушение правил общения<br>Отключить отображение подобных сообщений можно в настройках чата.\',event,3,1,1,3,\'\')"> <b>!</b> </font>'+msg;
					}
					if(data[10]>0)
					{
						msg = '<font style="cursor:help" color="green" onmouseout="top.hic()" onmousedown="top.hic()" onmouseover="top.hi(this,\'Это глобальное сообщение, оно может быть отправлено из любой локации<br>Отключить отображение подобных сообщений можно в настройках чата.\',event,3,1,1,3,\'\')"> <b>G</b> </font>'+msg;
					}
					var msg22  = '<span ';
					if(top.c.admin > 0) {
						msg22 += 'oncontextmenu="chat.deleteMessage('+data[0]+');return false;" ';	
					}else{
						//msg22 += 'oncontextmenu="chat.deleteMessage('+data[0]+',1);return false;" ';
					}
					if( data[8] == 2 ) {
						//msg22 += 'class="'+cls+'">'+td[4]+'.'+td[5]+'.'+(td[6]+1900)+' '+td[1]+':'+td[2]+'</span> ';
						if( data[14] == undefined ) {
							data[14] = '--:--';
						}
						msg22 += 'class="'+cls+'">'+data[14]+'</span> ';
					}else{
						//msg22 += 'class="'+cls+'">'+td[1]+':'+td[2]+'</span> ';
						if( data[13] == undefined ) {
							data[13] = '--:--';
						}
						msg22 += 'class="'+cls+'">'+data[13]+'</span> ';
					}
					msg = msg22+msg;
				}
				
			this.msgcount++;
						
			msg = '<span class="m0c1" id="msg_'+data[0]+'">'+msg+'<br></span>';			
			
			if(forYou > 0 && this.sound > 0 && this.trim(data[3].toLowerCase()) != top.c.login.toLowerCase())
			{
				this.sendSound(1);
			}
			
			if(msg_see == 1)
			{
				if(this.trim(data[3].toLowerCase()) == top.c.login.toLowerCase() || forYou == 1 || this.filter == 0)
				{
					if( data[9] > 0 ) {
						this.sendSound(data[9]);
					}
					if( data[15] != undefined && data[15] != 0 ) {
						this.feerverk( data[15] );
						this.feerverk( data[15] );
					}
					
					if( data[2] < 4 || global_type == 1 ) {
						//обычное сообщение
						if( data[2] == 1 && data[4] == 'trade' ) {
							$('#canal6').html($('#canal6').html()+''+msg);
						}else{
							$('#canal5').html($('#canal5').html()+''+msg);
						}
						top.blueTextSee(5);
						if( global_type == 1 ) {
							//Системный чат
							$('#canal4').html($('#canal4').html()+''+msg);
							top.blueTextSee(4);
						}
					}else{
						//Системный чат
						//$('#canal5').html($('#canal5').html()+''+msg);
						$('#canal4').html($('#canal4').html()+''+msg);
						top.blueTextSee(4);
						top.blueTextSee(5);
					}
					
					/*
					if(data[2] == 6 || data[2] == 5) {
						//системное сообщение
						$('#canal5').html($('#canal5').html()+''+msg);
						//$('#canal4').html($('#canal4').html()+'<span id="msg_'+data[0]+'_sys">'+msg+'</span>');
						//if(top.cb_rdate[top.cb_select] != 4) {
							//top.blueTextSee(5);
						//}
					}else if(data[2] == 7){
						//системное сообщение (только)
						//$('#canal'+top.cb_select).html($('#canal'+top.cb_select).html()+''+msg);
						//$('#canal4').html($('#canal4').html()+'<span id="msg_'+data[0]+'_sys">'+msg+'</span>');
						//top.blueTextSee(4);
					}else if(data[2] == 8){
						//системное сообщение (только ТАП)
						//$('#canal'+top.cb_select).html($('#canal'+top.cb_select).html()+''+msg);
						//$('#canal6').html($('#canal6').html()+'<span id="msg_'+data[0]+'_tap">'+msg+'</span>');
						//top.blueTextSee(6);
					}else{
						//обычное сообщение
						if( data[2] == 1 && data[4] == 'trade' ) {
							$('#canal6').html($('#canal6').html()+''+msg);
						}else{
							$('#canal5').html($('#canal5').html()+''+msg);
						}
						//top.blueTextSee(5);
					}*/
				}
			}
			this.scrollNow(this.msgcount);
			delete forYou;
			delete cls;
			delete msg_see;
			delete msg;
			delete arr;
		}
	},
	testKey:function(m,v)
	{
		var i = 0, r = v;
		v = false;
		while(i!=-1)
		{
			if(m[i]!=undefined)
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
		/*if(top.c.admin < 1) {
			txr = txt.split(':');
			var i = 1, j = 0, smid = 0;
			while(i!=-1)
			{
				if(txr[i]!=undefined && j < 3)
				{				
					smid = this.testKey(top.sml,txr[i].toLowerCase());
					if(smid!=false && this.isNumber(txr[i]) != true)
					{				
						if(smid!=false)
						{
							txt = txt.replace("\:"+txr[i]+"\:",'<img src="http://'+top.c.img+'/i/smile/'+(txr[i].toLowerCase())+'.gif" style="cursor:pointer" width="'+top.sml[smid+1]+'" height="'+top.sml[smid+2]+'" onclick="chat.addSmile(\''+(txr[i].toLowerCase())+'\')">');
						}
						j++;
					}
				}else{
					i = -3;
				}
				i += 2;
			}
			delete smid;
			delete j;
			delete txr;
		}else{*/
			txr = txt.split(':');
			var i = 1, j = 0, smid = 0;
			while(i <= txr.length) {
				if( txr[i] != undefined) {
					smid = this.testKey(top.sml,txr[i]);
					imsml = txr[i].split('-');
					if(((smid != false || smid == 0) && this.isNumber(txr[i]) != true) || (imsml != undefined && imsml[0] == '%usersmile%')) {
						if(j < 3 && this.isNumber(top.sml[smid]) != true && (top.sml[smid] != undefined || imsml[0] == '%usersmile%')) {
							if(imsml[0] == '%usersmile%') {
								//txt = txt.replace("\:%usersmile%-"+imsml[1]+"\:",'<img src="http://'+top.c.img+'/i/smile/'+(imsml[1].toLowerCase())+'.gif" width="'+top.sml_img[imsml[1]][0]+'" height="'+top.sml[imsml[1]][1]+'" title="Именной смайлик">');
								txt = txt.replace("\:%usersmile%-"+imsml[1]+"\:",'<img src="http://'+top.c.img+'/i/smile/'+(imsml[1].toLowerCase())+'.gif" title="Именной смайлик">');
							}else{
								txt = txt.replace("\:"+txr[i]+"\:",'<img src="http://'+top.c.img+'/i/smile/'+(txr[i].toLowerCase())+'.gif" style="cursor:pointer" width="'+top.sml[smid+1]+'" height="'+top.sml[smid+2]+'" onclick="chat.addSmile(\''+(txr[i].toLowerCase())+'\')">');
							}
							j++;
						}
					}
				}
				i++;
			}
		//}
		return txt;
	},
	trmb:function()
	{
		if(this.translit==1)
		{
			$('#textmsg').val(this.convert2($('#textmsg').val()));
		}
	},
	replaceAll:function(t,v,s)
	{
		return t.split(v).join(s);
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
	addSm:function(i)
	{
		$('#textmsg').focus();
		top.document.textmsg.value += ' :'+i+': ';		
	},
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
	sendSound:function(s)
	{
		var svolm = 100;
		if(this.sound == 0) {
			svolm = 0;
		}else if(this.sound == 1) {
			svolm = 25;
		}else if(this.sound == 2) {
			svolm = 100;
		}
		var M$ =  navigator.appName.indexOf("Microsoft")!=-1
		if(!M$ && this.getSwf('Sound').SetVariable == undefined) {
			document.getElementById('Sound2').SetVariable("Volume",svolm);
			document.getElementById('Sound2').SetVariable("Sndplay",s);
		}else{
			window.document["Sound"].SetVariable("Volume", svolm);
			window.document["Sound"].SetVariable("Sndplay", s);
		}
		/*if(window.Sound) window.document["Sound"].SetVariable("Sndplay", s)
		if(document.Sound) document.Sound.SetVariable("Sndplay", s)
		if(window.Sound) window.document["Sound"].SetVariable("Volume", svolm)
		if(document.Sound) document.Sound.SetVariable("Volume", svolm)*/
	},
	getSwf:function(val) {
		var M$ =  navigator.appName.indexOf("Microsoft")!=-1
		return (M$ ? window : document)[val]
	},
	trim:function(s)
	{
		return this.rtrim(this.ltrim(s));
	},	
	ltrim:function(s)
	{
		return s.replace(/^\s+/, ''); 
	},	
	rtrim:function(s)
	{
		return s.replace(/\s+$/, ''); 
	},
	multiaddto:function(users,tp)
	{
		var arr = users.split(',');
		var i = arr.length;
		while(i>=0)
		{
			if(arr[i]!=undefined)
			{
				this.addto(arr[i],tp);
			}
			i--;
		}
	},
	osize:function(obj) {
		var size = 0, key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) size++;
		}
		return size;
	},
	//Генерируем данные
	genchatData:function(data,prs)
	{
		if(prs == 1) {
			data = $.parseJSON(data);
		}
		if(data == null && this.saveData!=null)
		{
			data = this.saveData;
			data.js = '';
			data.rn = undefined;
			data.key = undefined;
		}
		//Получаем сообщение
		if(data.msg!=undefined)
		{
			var ms = $.parseJSON(data.msg);
			if(ms['ld']>this.msg_id)
			{
				this.msg_id = ms['ld'];
			}
			var i = 0;
			//while(i <= this.osize(ms.length)+10)
			while(i <= ms['id'])
			{
				if(ms['m'+i]!=undefined)
				{
					this.sendMsg(ms['m'+i]);
				}
				i++;
			}
		}
		//Если есть JS
		if(data.js!='')
		{
			eval(data.js);
		}
		if(data.rnd!=undefined){ c.rnd = data.rnd; }
		if(data.rn!=undefined){	if($('#chcf10').attr('checked')==true){ $('#roomName').html(data.rn+'<br><small>Общий онлайн: '+data.xu+'</small>'); }else{ $('#roomName').html(data.rn+' ('+data.xu+')'); } }
		if(data.key!=undefined){	this.key = data.key;	}
		if(data.list!=undefined)
		{
			var i = 0, ji = $.parseJSON(data.list), onll = '', fSort = {}, flSort = {},flSortSee = '"Служба Поддержки"';
			//сортируем данные
			while(i<=data.xu)
			{
				if(ji[i]!=undefined)
				{
					/*if($.cookie('chatCfg8')==1)
					{
						jj = '['+(ji[i][2]*0.01)+']'+i; //по уровню
					}else if($.cookie('chatCfg8')==2)
					{
						jj = '['+ji[i][3]+']'+i; //по склонности
					}else if($.cookie('chatCfg8')==3)
					{
						jj = '['+ji[i][4]+']'+i; //по клану
					}else{
						jj = ji[i][1]; //по логину
					}*/
					jj = ji[i][1].toLowerCase(); //по логину
					fSort[jj] = i;
					flSort[i] = jj;
					flSortSee += ',"'+flSort[i]+'"';
				}
				i++;
			}
			flSortSee = eval('['+flSortSee+']');
			if($.cookie('chatCfg9')==1){flSortSee.sort(game.sort2);}else{flSortSee.sort(game.sort1);}
			//Выводим данные
			i = 0;
			var onll_alh = '';
			while(i<=data.xu)
			{
				if(fSort[flSortSee[i]]!=undefined)
				{
					if( ji[fSort[flSortSee[i]]][3] == 50 ) {
						onll_alh += '<span class="m0c1">'+this.gUser(ji[fSort[flSortSee[i]]],true)+'</span>';
					}else{
						onll += '<span class="m0c2">'+this.gUser(ji[fSort[flSortSee[i]]],true)+'</span>';
					}
				}
				i++;
			}
			$('#onlist').html(onll_alh + '' + onll);
		}
	}
}