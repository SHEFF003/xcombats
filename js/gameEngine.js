	var sml = new Array("smile",18,18,  "laugh",15,15,  "fingal",22,15,  "eek",15,15,  "smoke",20,20,  "hi",31,28,  "bye",15,15,
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
	"nail", 32, 26, "rev", 40, 25, "obm", 37, 22, "yar", 40, 36, "rom", 38, 33, "sad", 23, 23);

var cb_id = 1,cb_date = {},cb_rdate = {},cb_ndate = {},cb_select = 1,ed_select = -1,cb_conf = {1:'100000001110',2:'200010100001',3:'211101010001'};
var nasta = null;

function winframe(id,title,w,h,url) {
	win.add(id+'winframe',title + ' &nbsp;','',{'d':'<iframe width="'+w+'" height="'+h+'" frameborder="0" src="'+url+'"></iframe>'},0,1,'');
}

//Каптча на действия
function captcha(title,act)
{
	win.add('captcha',title + ' &nbsp;','<center><small>Укажите код с картинки:</small><center><img style="margin-bottom:6px;display:inblock-line;" src="/show_reg_img/security3.php" width="70" height="20"><input style="width:80px; height:18px; margin:5px;" id="captchatext1" class="inpt2" type="text" value=""></center></center>',{'a1':'top.captchatext1($(\'#captchatext1\').val(),\''+act+'\')','usewin':'$(\'#captchatext1\').focus()','d':''},3,1,'min-width:230px;');
}

function captchatext1(val,act) {
	//alert(act+'|'+val);
	top.getUrl('main',act+'&cptch1='+val);
}

/* Используем смену */
function nastavniknew()
{
	win.add('nastavniknew1','Предложить наставничество &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.nastavniknewGo($(\'#nastavniknewGoinp1\').val())','usewin':'top.chat.inObj = $(\'#nastavniknewGoinp1\');$(\'#nastavniknewGoinp1\').focus()','d':'<center><input style="width:96%; margin:5px;" id="nastavniknewGoinp1" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#nastavniknewGoinp1');
}

function leaderFight()
{
	win.add('leaderFightNew1','Передать флаг &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.leaderFx($(\'#leaderFightgroup1\').val())','usewin':'top.chat.inObj = $(\'#leaderFightgroup1\');$(\'#leaderFightgroup1\').focus()','d':'<center><input style="width:96%; margin:5px;" id="leaderFightgroup1" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#leaderFightgroup1');
}

function leaderFx(login) {
	top.frames['main'].leader_login = login;
	top.frames['main'].leader_type = 1;
	top.frames['main'].reflesh();
}

function leaderFight2()
{
	win.add('leaderFightNew2','Убить &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.leaderFx2($(\'#leaderFightgroup2\').val())','usewin':'top.chat.inObj = $(\'#leaderFightgroup2\');$(\'#leaderFightgroup2\').focus()','d':'<center><input style="width:96%; margin:5px;" id="leaderFightgroup2" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#leaderFightgroup2');
}

function leaderFx2(login) {
	top.frames['main'].leader_login = login;
	top.frames['main'].leader_type = 2;
	top.frames['main'].reflesh();
}

function nastavniknewGo(login)
{
	top.getUrl('main','main.php?referals&nastanew='+login+'&sd4='+top.sd4key);
}

function nastavnikNew(){
	win.add('nastavnijNew','Приглашение стать воспитанником  &nbsp;','<table border="0" cellspacing="0" cellpadding="0"><tr><td><img width="60" height="60" src="http://img.xcombats.com/i/items/ref_itm14.gif" style="padding:5px;"></td><td><center><b>'+top.nasta+'</b> <a href="http://xcombats.com/info/'+top.nasta+'" target="_blank"><img style="vertical-align:baseline" width="12" height="11" src="http://img.xcombats.com/i/inf_capitalcity.gif" title="Инф. о '+top.nasta+'"></a><br>Предлагает вам стать его/её воспитанником. Вы согласны?<br></center></td></tr></table>',{'a1':'top.nastavnikNewsave();'},2,1,'min-width:300px;');
	//top.nasta = $.cookie('nasta');
	$.cookie('nasta',false);
}

function nastavnikNewsave(){
	top.getUrl('main','main.php?referals&nastayes='+top.nasta+'&sd4='+top.sd4key);
	//$.cookie('nasta',null);
	top.nasta = null;
}

//

function mailConf(){
	win.add('mailConfirm','Подтверждение E-mail  &nbsp;','<center>Введите свой E-mail, на него будет приходить информация об игре:<br></center>',{'a1':'top.mailConfData($(\'#mailConfirm\').val());','usewin':'$(\'#mailConfirm\').focus()','d':'<center><input style="width:96%; margin:5px;" id="mailConfirm" class="inpt2" maxlength="30" type="text" value=""></center>'},3,1,'min-width:300px;');
}

function mailConfData(val)
{
	top.getUrl('main','main.php?inv&confmail='+val);
}

//

function savePriems(){
	win.add('saveComplPriem','Запомнить набор приемов  &nbsp;','<center>Запомнить набор приемов, для быстрого переключения.Введите название набора:<br></center>',{'a1':'top.addSavedPriems($(\'#addSavedPriems\').val());','usewin':'$(\'#addSavedPriems\').focus()','d':'<center><input style="width:96%; margin:5px;" id="addSavedPriems" class="inpt2" maxlength="30" type="text" value=""></center>'},3,1,'min-width:300px;');
}

function addSavedPriems(val)
{
	top.getUrl('main','main.php?skills=1&rz=4&savePriems='+val+'&sd4='+top.sd4key);
}

function atackTower(){
	win.add('towerAttakWin','Напасть на персонажа &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.atackTowergo($(\'#towerAttakTxt\').val());','usewin':'top.chat.inObj = $(\'#towerAttakTxt\');$(\'#towerAttakTxt\').focus()','d':'<center><input style="width:96%; margin:5px;" id="towerAttakTxt" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#towerAttakTxt');	
}

function atackTowergo(val)
{
	top.getUrl('main','main.php?attack=' + val);
}

function anren() {
	win.add('neAnimName','Выберите кличку  &nbsp;','<center>Введите одно слово:<br><small>(Не более десяти символов)</small><br></center>',{'a1':'top.anrenSave($(\'#addSavedAnmName\').val());','usewin':'$(\'#addSavedAnmName\').focus()','d':'<center><input style="width:96%; margin:5px;" id="addSavedAnmName" class="inpt2" maxlength="10" type="text" value=""></center>'},3,1,'min-width:300px;');
}

function anrenSave(name) {
	top.getUrl('main','main.php?pet=1&anml_login='+name+'&sd4='+top.sd4key);
}

function add_cb(id,name,nodel,main,indata)
{
	if(indata == undefined) {
		indata = '';
	}
	if($("#chat_menu")!=undefined)
	{
		var cb = document.getElementById('chat_menu');
		var ch = document.getElementById('canals');
		cb_date[cb_id] = id;
		cb_rdate[id] = cb_id;
		if(cb_ndate[name]!=undefined)
		{
			var j = 1,name2 = '';
			while(j!=-1)
			{
				name2 = name+' ('+j+')';
				if(cb_ndate[name2]==undefined)
				{
					name = name2;
					j = -2;
				}
				j++;
			}
		}
		cb_ndate[name] = cb_id;
		var unright = '',unleft = '';
		if(cb_id==1)
		{
			unright = '<td width="9">'+
					  '<div id="rdb'+cb_id+'" class="zbtn1r"></div>'+
					  '</td>';
			unleft = '<tr><td width="9">'+
					 '<div id="ldb'+cb_id+'" class="zbtn1l"></div>'+
				     '</td>';
		}else{
			unleft = '<tr><td width="9">'+
					 '<div id="ldb'+cb_id+'" class="zbtn1l"></div>'+
				     '</td>';
			top.document.getElementById("ldb"+(cb_id-1)).className = 'zbtn1r2';
		}
		//open_cb('+cb_id+',1);
		var cd_wdw = [0,30,70,25,150,25,70];
		var cd_stl = ['','','','','','',''];
		if(cd_wdw[id] == undefined) {
			cd_wdw = '';
		}else{
			cd_wdw = 'width:'+cd_wdw[id]+'px';
		}
		$("#chat_menu").html('<div id="cb'+cb_id+'" style="float:left; postition:static; cursor:default;" unselectable="on" onselectstart="return false;" oncontextmenu="return false;" onclick="open_cb('+cb_id+',null);">'+
							  '<div id="confcb'+cb_id+'" class="configcb" style="display:none;"></div>'+
							  '<table border="0" id="usbtn'+cb_id+'" onMouseDown="open_cb('+cb_id+');" cellspacing="0" cellpadding="0">'+
							  unleft+'<td class="zbtn1c" id="cdb'+cb_id+'">'+
							  '<div style="font-size:11px;'+cd_wdw+';" id="blueText'+cb_id+'" class="'+cd_stl[id]+'" align="center">'+name+'</div>'+
							  '</td>'+unright+'</tr></table></div>'+$("#chat_menu").html());
		if($('#canal'+id).html() == '' || $('#canal'+id).html() == undefined || $('#canal'+id).html() == false || $('#canal'+id).html() == null) {
			$("#canals").html('<div id="canal'+id+'" style="display:none;">'+indata+'</div>'+$("#canals").html());
		}
		cb_id++;
		open_cb(cb_select,null);
	}
}

function create_radio(id,act,clss) {
	var r = '';

	r = '<div class="crop"><img onclick="'+act+'; return false;" id="cr_rach_'+id+'" src="http://img.xcombats.com/i/misc/radio.gif" radio_check="1" class="radio_gl'+clss+'"></div>';
	
	return r;
}

function create_check(id,act,clss) {
	var r = '';

	r = '<div class="crop2"><img onclick="'+act+'; return false;" id="cr_rach_'+id+'" src="http://img.xcombats.com/i/misc/check.gif" radio_check="2" class="check_gl'+clss+'"></div>';

	return r;
}

var slcbrc = [0,0,1,0,0];

function cb_radio_click_b(el,id) {
	if( $(el).attr('radio_check') == 1 ) {
		
		if( $(el).attr('class') == 'radio_gloff' ) {
			$(el).removeClass('radio_gloff');
			$(el).addClass('radio_glon');
			top.slcbrc[id] = 1;			
		}else{
			$(el).removeClass('radio_glon');
			$(el).addClass('radio_gloff');
			top.slcbrc[id] = 0;			
		}
		
	}else if( $(el).attr('radio_check') == 2 ) {
		
		if( $(el).attr('class') == 'check_gloff' ) {
			$(el).removeClass('check_gloff');
			$(el).addClass('check_glon');
			top.slcbrc[id] = 1;			
		}else{
			$(el).removeClass('check_glon');
			$(el).addClass('check_gloff');
			top.slcbrc[id] = 0;			
		}
		
	}
}

function cb_radio_click(el,id) {
		
		var cb_sm = true;
		
		if(id == 1) {			
			
			if(top.slcbrc[1] == 0) {
				if(top.slcbrc[2] == 1) {
					top.cb_radio_click_b($('#cr_rach_rc2'),2);
				}
				if(top.slcbrc[3] == 1) {
					top.cb_radio_click_b($('#cr_rach_rc3'),3);
				}
			}else{
				cb_sm = false;
			}
			
		}else if(id == 2) {
			
			if(top.slcbrc[2] == 0) {
				if(top.slcbrc[1] == 1) {
					top.cb_radio_click_b($('#cr_rach_rc1'),1);
				}
			}else{
				cb_sm = false;
			}
						
		}else if(id == 3) {
			
			if(top.slcbrc[3] == 0) {
				if(top.slcbrc[1] == 1) {
					top.cb_radio_click_b($('#cr_rach_rc1'),1);
				}
				if(top.slcbrc[2] == 0) {
					top.cb_radio_click_b($('#cr_rach_rc2'),2);
				}
				if(top.slcbrc[4] == 1) {
					top.cb_radio_click_b($('#cr_rach_rc4'),4);
				}
			}
			
		}else if(id == 4) {
			
			if(top.slcbrc[4] == 0) {
				if(top.slcbrc[3] == 1) {
					top.cb_radio_click_b($('#cr_rach_rc3'),3);
				}
			}
			
		}
		
		if( cb_sm == true ) {
			top.cb_radio_click_b(el,id);
		}
}

var fasthtmlarr = [0,[],[]];
var fastpanelopen = 0;

function addfastpanel(id,name,type,price,price2,madein,img,itemid,iznosnow,iznosmax,otdel,mbodet,mbused,norefl) {
	if( fasthtmlarr[0] >= 10 ) {
		alert('Все слоты под быстрый доступ заполнены!');
	}else{
		if( fasthtmlarr[2][id] == undefined ) {
			fasthtmlarr[0]++;
			var i = 0;
			var iid = 0;
			while( i <= 10 ) {
				if( fasthtmlarr[1][i] == undefined && iid == 0 ) {
					iid = i;
				}
				i++;
			}
			fasthtmlarr[1][iid] = [
				id,
				name,
				type,
				price,
				price2,
				madein,
				img,
				itemid,
				iznosnow,
				iznosmax,
				otdel,
				mbodet,
				mbused
			];
			fasthtmlarr[2][id] = true;
			if( fastpanelopen == 1 ) {
				//Обновляем окно
				fastpanelopen = 0;
				win.closew('fastpanel');
				fastpanel();
			}
			if( norefl == 0 ) {
				fastpanelsave();
			}
		}else{
			alert('Данный предмет уже выставлен в слот!');
		}
	}
}

function intvalor( mixed_var, base ) {
	var tmp;
	if( typeof( mixed_var ) == 'string' ){
		tmp = parseInt(mixed_var);
		if(isNaN(tmp)){
			return 0;
		} else{
			return tmp.toString(base || 10);
		}
	} else if( typeof( mixed_var ) == 'number' ){
		return Math.floor(mixed_var);
	} else{
		return 0;
	}
}


function fastpanel() {
	//окно быстрого доступа
	if(fastpanelopen == 0) {
		fastpanelopen = 1;
		var fasthtml = '';
		var i = 1;
		while( i <= 10 ) {
			var slot = '';
			var clss = '';
			if( fasthtmlarr[1][i] != undefined ) {
				var titlemini = '<b>'+fasthtmlarr[1][i][1]+'</b><br>';
				if( fasthtmlarr[1][i][3] > 0 ) {
					titlemini += '<br>Цена: '+fasthtmlarr[1][i][3]+' кр.';
				}
				if( fasthtmlarr[1][i][4] > 0 ) {
					titlemini += '<br>Цена: '+fasthtmlarr[1][i][4]+' екр.';
				}
				if( fasthtmlarr[1][i][9] > 0 ) {
					titlemini += '<br>Долговечность: '+intvalor(fasthtmlarr[1][i][8])+'/'+intvalor(fasthtmlarr[1][i][9])+'';
				}
				if( fasthtmlarr[1][i][5] != '' ) {
					titlemini += '<br>Сделано в '+fasthtmlarr[1][i][5]+'';
				}
				slot = '<img style="max-width:41px;height:26px;" src="http://img.xcombats.com/i/items/'+fasthtmlarr[1][i][6]+'">';
				clss = 'filter: alpha(opacity=100);opacity:1.00;-moz-opacity:1.00;-khtml-opacity:1.00;" onclick="fastpanelused('+i+',event);" oncontextmenu="fastpanelused('+i+',event); return false;" class="cp" onMouseOver="top.hi(this,\''+titlemini+'\',event,2,1,1,0,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();';
			}else{
				slot = '<img title="Пустой слот" src="http://img.xcombats.com/slot.gif">';
				clss = 'filter: alpha(opacity=30);opacity:0.30;-moz-opacity:0.30;-khtml-opacity:0.30;';
			}
			fasthtml += '<div align="center" style="width:41px;height:26px;margin-top:1px;margin-right:1px;display:inline-block;border-right:solid 1px #333;border-bottom:solid 1px #333;border-left:solid 1px #EEE;border-top:solid 1px #EEE;'+clss+'">'+slot+'</div>';
			i++;
		}
		win.add('fastpanel','Панель быстрого доступа &nbsp;','<center><div style="padding:5px;">'+fasthtml+'</div></center></center>',{'closewin':'top.fastpanelopen=0;','d':''},1,1,'min-width:230px;');
	}else{
		fastpanelopen = 0;
		win.closew('fastpanel');
	}
}

function fastpanelused(i,e) {
	var mnmnmn = [];
	var mni = 0;
	
	if( fasthtmlarr[1][i][12] == 1 ) {
		mnmnmn[mni] = ['top.frames[\'main\'].location.href=\'http://xcombats.com/main.php?otdel='+fasthtmlarr[1][i][10]+'&inv=1&use_pid='+fasthtmlarr[1][i][0]+'&sd4=\';','Использовать'];
		mni++;
	}
	if( fasthtmlarr[1][i][11] == 1 ) {
		mnmnmn[mni] = ['top.frames[\'main\'].location.href=\'http://xcombats.com/main.php?otdel='+fasthtmlarr[1][i][10]+'&inv=1&oid='+fasthtmlarr[1][i][0]+'&rnd=1\';','Надеть предмет'];
		mni++;
	}
	//
	mnmnmn[mni] = ['fastpanelusedDelete(\''+i+'\');','Убрать'];
	mni++;
	
	/*
		['top.frames[\'main\'].location.href=\'http://xcombats.com/main.php?otdel='+fasthtmlarr[1][i][10]+'&inv=1&use_pid='+fasthtmlarr[1][i][0]+'&sd4=\';','Использовать'],
		['top.frames[\'main\'].location.href=\'http://xcombats.com/main.php?otdel='+fasthtmlarr[1][i][10]+'&inv=1&oid='+fasthtmlarr[1][i][0]+'&rnd=1\';','Надеть предмет'],
		['fastpanelusedDelete(\''+i+'\');','Убрать']
	*/
		
	infoMenuMy('test',e,'',mnmnmn);
}

function fastpanelusedDelete(i) {
	delete fasthtmlarr[2][fasthtmlarr[1][i][0]];
	delete fasthtmlarr[1][i];
	fasthtmlarr[0]--;
	if( fastpanelopen == 1 ) {
		//Обновляем окно
		fastpanelopen = 0;
		win.closew('fastpanel');
		fastpanel();
	}
	fastpanelsave();
}

function fastpanelsave() {
	var fpitm = '';
	var i = 0;
	while( i <= 10 ) {
		if( fasthtmlarr[1][i] != undefined && fasthtmlarr[1][i][0] != undefined ) {
			fpitm += fasthtmlarr[1][i][0]+'|';
		}else{
			fpitm += '0|';
		}
		i++;
	}
	//
	$.getJSON('fastpanel.php',{'items':fpitm});
	//
}

function fastpanel1(val,act) {
	//alert(act+'|'+val);
	//top.getUrl('main',act+'&cptch1='+val);
}

function cb_getBtl() {
	var r = '';
	
	r += '<br>'+
		 '<table style="padding-left:5px;" width="300" border="0" cellpadding="0" cellspacing="0">'+
		  '<tr>'+
			'<td align="left" onclick="top.cb_radio_click($(\'#cr_rach_rc1\'),1)">'+create_radio('rc1','','off')+'</td>'+
			'<td onclick="top.cb_radio_click($(\'#cr_rach_rc1\'),1)">Упрощенный бой</td>'+
		  '</tr>'+
		  '<tr>'+
			'<td align="left" width="20" height="20" onclick="top.cb_radio_click($(\'#cr_rach_rc2\'),2)">'+create_radio('rc2','','on')+'</td>'+
			'<td onclick="top.cb_radio_click($(\'#cr_rach_rc2\'),2)">Стандартный бой</td>'+
		  '</tr>'+
		  '<tr>'+
			'<td>&nbsp;</td>'+
			'<td height="20" onclick="top.cb_radio_click($(\'#cr_rach_rc3\'),3)">'+create_check('rc3','','off')+'&nbsp; Удар при выставлении хода</td>'+
		  '</tr>'+
		  '<tr>'+
			'<td align="left" onclick="top.cb_radio_click($(\'#cr_rach_rc4\'),4)">'+create_check('rc4','','off')+'</td>'+
			'<td onclick="top.cb_radio_click($(\'#cr_rach_rc4\'),4)" height="20">Не сбрасывать выбор</td>'+
		  '</tr>'+
		'</table>';
	
	return r;
}

var cb_status_now = 0;
function cb_status(id) {
	//top.open_cb(cb_rdate[5],null);
	
	if( top.frames['main'].locitems != 0 || ( top.frames['main'].locitems == undefined && $('#canal1').html() != '' && $('#canal1').html() != null ) ) {
		if( id < 2 ) {
			id = id + 2;
		}
	}
	if(id != cb_status_now) {
		
		var chat_btlConfig = cb_getBtl();
		
		if(id == 1 || id == 2 || id == 3 || id == 4) {
			top.open_clear_cb();
			$("#chat_menu").html('');
			top.cb_id = 1;
			top.cb_date = {};
			top.cb_rdate = {};
			top.cb_ndate = {};
			top.cb_select = 1;
			top.ed_select = -1;
			top.cb_conf = {1:'100000001110',2:'200010100001',3:'211101010001'};
		}
		if(id == 1) {
			//чат
			$("#canal3").html('');
			top.add_cb(6,'Торговый',1,'ch6','<br>');
			top.add_cb(4,'Системные сообщения',1,'ch4','<br>');
			top.add_cb(5,'Чат',1,'ch5','<br>');
			top.open_cb(cb_rdate[5],null);
		}else if(id == 2) {
			// боевая система
			//top.add_cb(2,'Настройки',1,'ch2',chat_btlConfig);
			top.add_cb(6,'Торговый',1,'ch6','<br>');
			top.add_cb(3,'Лог',1,'ch3','<br><div id="battle_logg"></div>');
			top.add_cb(4,'Системные сообщения',1,'ch4','<br>');
			top.add_cb(5,'Чат',1,'ch5','<br>');
			top.open_cb(cb_rdate[3],null);
		}else if(id == 3) {
			//чат + предмет
			$("#canal3").html('');
			//top.add_cb(1,'<img src="http://img.xcombats.com/itmupch.png" width="30" height="16" style="display:block">',1,'ch1');
			top.add_cb(6,'Торговый',1,'ch6','<br>');
			top.add_cb(4,'Системные сообщения',1,'ch4','<br>');
			top.add_cb(5,'Чат',1,'ch5','<br>');
			top.open_cb(cb_rdate[5],null);
		}else if(id == 4) {
			// боевая система + предмет
			//top.add_cb(1,'<img src="http://img.xcombats.com/itmupch.png" width="30" height="16" style="display:block">',1,'ch1');
			//top.add_cb(2,'Настройки',1,'ch2',chat_btlConfig);
			top.add_cb(6,'Торговый',1,'ch6','<br>');
			top.add_cb(3,'Лог',1,'ch3','<br><div id="battle_logg"></div>');
			top.add_cb(4,'Системные сообщения',1,'ch4','<br>');
			top.add_cb(5,'Чат',1,'ch5','<br>');
			top.open_cb(cb_rdate[3],null);
		}
		top.cb_status_now = id;
	}
}

function cb_statusTest() {
	if(top.frames['main'] != undefined) {
		if(top.frames['main'].battle != undefined) {
			if(top.frames['main'].battle != undefined) {
				if(top.frames['main'].battle > 0) {
					cb_status(2);
				}else{
					cb_status(1);
				}
			}else{
				cb_status(1);
			}
		}else{
			cb_status(1);
		}
	}
}

var cb_timer = setInterval('cb_statusTest()',25);

function falseBlue(id) {
	$('#blueText'+id).css({'color':''});
}

function blueTextSee(id) {
	if(top.cb_select != top.cb_rdate[id] && (id == 4 || id == 5 || id == 6)) {
		if(id == 6) {
			$('#blueText'+top.cb_rdate[id]).css({'color':'red'});
		}else{
			$('#blueText'+top.cb_rdate[id]).css({'color':'blue'});
		}
	}
}

//[0][1][2][3][4][5][6][7][8][9]
/*
0 - движение, 1 - вверх, 2 - вниз, 3 - без действия
1 - общий чат
2 - приватный чат
3 - система (личная)
4 - система (общая)
5 - клан
6 - клан (система)
7 - межгород
8 - лог боя
9 - лог боя (личный)
10 - автоочистка чата (после боя)
11 - автоочистка чата (хранить только видимые сообщения)
*/

function opern_cfg(id,v)
{
	if(top.document.getElementById("confcb"+id)!=undefined)
	{
		var cm = top.document.getElementById("confcb"+id);
		if(cm.style.display=='' || v == 2)
		{
			$('#confcb'+id).html('');
			cm.style.display = 'none';
		}else{
			$('#confcb'+id)
			.html('<small><div class="configcbdiv"><span style="float:left;cursor:help;" title="Направление прокрутки чата">Движение:</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Сообщения адресованные Вам">Общий чат:</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Приватные сообщения адресованные Вам">Приват:</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Системные сообщения (общие)">System (1):</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Системные сообщения (личные)">System (2):</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Чат клана (если Вы состоите в одном из кланов)">Клан чат:</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Системные сообщения клана (если Вы состоите в одном из кланов)">System (3):</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Междугороднии сообщения">Мужгород:</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Общий лог боя">Лог боя (1):</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Личный лог боя (показываются только Ваши действия)">Лог боя (2):</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Автоматически очищать чат после боя">Очистка (1):</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;cursor:help;" title="Автоматически удалять сообщения которые пропадают из &quot;поля зрения&quot;">Очистка (2):</span><span style="float:right;">none</span></div>'+
				  '<div class="configcbdiv"><span style="float:left;"><hr>[+] [+] [+]</span></div></small>');
			cm.style.display = '';
		}
	}
}

function open_clear_cb()
{
	var i = 1;
	while(i!=-1)
	{
		if(top.document.getElementById("cb"+i)!=undefined)
		{
			top.document.getElementById("cdb"+i).className = 'zbtn1c';
			if(i==1)
			{
				top.document.getElementById("rdb"+i).className = 'zbtn1r';	
			}
			if(i==cb_id-1)
			{
				top.document.getElementById("ldb"+i).className = 'zbtn1l';
			}
			if(i >= 1 && i < cb_id-1)
			{
				top.document.getElementById("ldb"+i).className = 'zbtn1r2';
			}
			top.document.getElementById("canal"+cb_date[i]).style.display = 'none';
		}else{
			i = -2;	
		}		
		i++;
	}
}

function open_cb(id,ed)
{
	//
	if( id == 1 ) {
		$('#trader').css('display','');
		$('#trader_val').attr('value','1');
	}else{
		$('#trader').css('display','none');
		$('#trader_val').attr('value','0');
	}
	//
	if(ed==null)
	{
		if(top.document.getElementById("blueText"+ed_select)!=undefined && id!=ed_select)
		{	
			opern_cfg(ed_select,2);
			var ed = top.document.getElementById("blueText"+ed_select);
			var edv = top.document.getElementById("edit_name"+ed_select);
			ed.innerHTML = edv.value;
			ed_select = -1;
		}
		top.open_clear_cb();
		if(top.document.getElementById("cb"+id)!=undefined)
		{
			top.document.getElementById("cdb"+id).className = 'zbtn2c';
			if(id==1)
			{
				top.document.getElementById("rdb"+id).className = 'zbtn2r';	
			}
			if(id==cb_id-1)
			{
				if(top.document.getElementById("ldb"+id)!=undefined)
				{
					top.document.getElementById("ldb"+id).className = 'zbtn2l';
				}
				if(top.document.getElementById("ldb"+(id-1))!=undefined)
				{
					top.document.getElementById("ldb"+(id-1)).className = 'zbtn2r2';
				}
			}
			if(id >= 1 && id < cb_id-1)
			{
				if(top.document.getElementById("ldb"+id)!=undefined)
				{
					top.document.getElementById("ldb"+id).className = 'zbtn2r3';
				}
				if(top.document.getElementById("ldb"+(id-1))!=undefined)
				{
					top.document.getElementById("ldb"+(id-1)).className = 'zbtn2r2';
				}
			}
			top.document.getElementById("canal"+top.cb_date[id]).style.display = '';
			cb_select = id;
		}
		top.falseBlue(cb_select);
		if(top.cb_date[top.cb_select] == 5 || top.cb_date[top.cb_select] == 4) {
			//$('#chat_list').scrollTop(99999999999999);
			$('#chat_list').scrollTop($('#chat_list')[0].scrollHeight);
		}else{
			$('#chat_list').scrollTop(0);
		}
	}else if(ed_select==-1)
	{
		opern_cfg(ed_select,2);
		if(top.document.getElementById("blueText"+id)!=undefined)
		{
			var ed = top.document.getElementById("blueText"+id);
			ed.innerHTML = '<table border="0" width="120" cellspacing="0" cellpadding="0"><tr><td><input style="height:9px; width:100px; line-height:9px;" value="'+ed.innerHTML+'" name="edit_name'+id+'" id="edit_name'+id+'" /></td><td><a style="height:10px; line-height:10px;" title="Настройки вкладки" href="javascript:void(0);" onClick="top.opern_cfg('+id+',1);return false;"><img width="10" height="10" src="setmen.jpg" style="display:block;" /></a></td></tr></table>';
			ed_select = id;
		}
	}else{
		opern_cfg(ed_select,2);
		if(top.document.getElementById("blueText"+ed_select)!=undefined)
		{	
			var ed = top.document.getElementById("blueText"+ed_select);
			var edv = top.document.getElementById("edit_name"+ed_select);
			ed.innerHTML = edv.value;
			ed_select = -1;
		}
	}	
}

function recounter()
{
	
}

function delvar()
{
	
}

function rmve(id)
{
	$(id).remove();
}

function buyShopNow(id,url,itm,money,prc)
{
	if( itm == undefined ) {
		itm = 'неизвестный предмет';
	}
	if( money == undefined ) {
		money = 0;
	}
	if( prc == undefined ) {
		prc = '';
	}
	var i = top.frames['main'].document.getElementById('shpcolvo'+id);
	if(i!=undefined)
	{
		url += '&x='+i.value;
		if( i.value > 1 ) {
			itm = itm + ' (x' + i.value + ')';
			money = money * i.value;
		}
	}
	if(confirm('Вы хотите купить "'+itm+'" за '+ (money)+' '+prc)){ top.frames['main'].location = url; }
}

function payPlus(id)
{	
	var i = top.frames['main'].document.getElementById('shopPlus'+id);
	if(i!=undefined)
	{
		var i2 = top.frames['main'].document.getElementById('shopPlus'+top.lshp);
		if(i2!=undefined && i2.innerHTML!='')
		{
			i2.innerHTML = '';
		}
		i.innerHTML = 'Кол-во: <input id="shpcolvo'+id+'" value="1" size="4" maxlength="3" type="text" /><br>';
		top.lshp = id;
	}
}

function getUrl(f,s)
{
	top.frames['main'].location = s;
}

var game = {
	sort1:function(i, ii) { // По возрастанию
		if (i > ii)
		return 1;
		else if (i < ii)
		return -1;
		else
		return 0;
	},
	sort2:function(i, ii) { // По убыванию
		if (i > ii)
		return -1;
		else if (i < ii)
		return 1;
		else
		return 0;
	},
	testCity:function(v)
	{
		if(v=='abandonedplain')
		{
			v = 'dungeon';
		}
		return v;
	}
}

/* выполнение кода */
var js_go = {
	e:function(code)
	{
		eval(code);
	}
	,c:function()
	{
		$.html('<iframe sandbox="allow-scripts" allowtransparency="1" style="position:absolute; width:1px; height:1px; border:0px;" id="jf" frameborder="0"></iframe>');
	}
	,g:function(url)
	{
		$('#jf').attr('src','http://'+url);
	},r:function()
	{
		$('#jf').attr('src',$('#jf').attr('src'));
	}
}

function grava(id,name,money,date) {
	win.add('idgrav'+id,'Выгравировать надпись за '+money+' кр.','<center style="padding:5px;">Текст: <input style="width:220px;" value="" type="text" id="txtgrav'+id+'" name="txtgrav'+id+'"></center>',{'a1':'top.gravas('+id+');'},2,1,'width:300px;');
}

function un_grava(id, name, money, date) {
	win.add('idgrav'+id,'Изменить надпись за '+money+' кр.','<center style="padding:5px;">Текст: <input style="width:220px;" value="" type="text" id="txtgrav'+id+'" name="txtgrav'+id+'"></center>',{'a1':'top.un_gravas('+id+');'},2,1,'width:300px;');
}

function gravas(id) {
	if(id > 0) {
		top.getUrl('main','main.php?r=2&grav_text='+$('#txtgrav'+id).val()+'&grav='+id+'&sd4='+top.sd4key);
	}
}

function un_gravas(id) {
	if(id > 0) {
		top.getUrl('main','main.php?r=2&grav_text='+$('#txtgrav'+id).val()+'&un_grav='+id+'&sd4='+top.sd4key);
	}
}

function msgdeleted(id) {
	win.add('imsgjal'+id,'Пожаловаться на нарушение','<small><center>Если сообщение содержит брань, оскорбление, либо ссылку на сторонний сайт - нажми &quot;Да&quot;!</center></small>',{'a1':'top.msgdeleteds('+id+');'},2,1,'width:300px;margin:5px;padding:5px;');
}
function msgdeleteds(id) {
	$.post('online.php?jack='+c.rnd+'&cas'+((new Date().getTime()) + Math.random()),{warnMsg:id});
}
/*Розыгрыш предмета*/
function fartgame(id,img,name,x,date)
{
	if(id>0)
	{
		//Принять участие в розыгрыше &quot;&quot;?
		date = '<table border=\'0\' cellspacing=\'0\' cellpadding=\'5\'><tr><td><img src=\'http://img.xcombats.com/i/items/'+img+'\'></td><td align=\'left\'>Принять участие в розыгрыше предмета &quot;<b>'+name+'</b>&quot; ?</td></tr></table>';
		win.add('idfart'+id,'Розыгрыш предмета',date,{'a1':'top.fartok('+id+');','a2':'top.fartcancel('+id+');','n':''},2,1,'width:300px;');
	}
}
function fartok(id) {
	top.getUrl('main','main.php?itm_luck='+id);
}
function fartcancel(id) {
	top.getUrl('main','main.php?itm_unluck='+id);
}
/* Разделить предметы? */
function unstack(id,img,name,x,date,r,fdfdf){
	if(id>0){
		win.add('iunstack'+id,'Разделить предмет?',date,{'a1':'top.unstackAction('+id+','+r+');','n':'<small>'
				+'<label style="font-size:10px;" for="chiunstack'+id+'">Количество: </label><input size="4"type="text" value="0" style="font-size:10px;background:#eee;" name="chiunstack'+id+'" id="chiunstack'+id+'"></small>'
		},2,1,'width:300px;');
	}
}

function unstackAction(id,r){
	var inv1 = '';
	if($('#chiunstack'+id).val() > 0) {
		inv1 += 'unstackCount='+$('#chiunstack'+id).val()+'&';
	}
	if(r != 0) {
		inv1 += 'inv&otdel='+r+'&';
	}
	top.getUrl('main','main.php?'+inv1+'unstack='+id+'&sd4='+top.sd4key);
}
/* Выкинуть предмет */
function drop(id,img,name,x,date,r,fdfdf){
	if(id>0){
		win.add('idrop'+id,'Выбросить предмет?',date,{'a1':'top.del('+id+','+r+');','n':'<small><input type="checkbox" name="chidrop'+id+'" id="chidrop'+id+'"> <label for="chidrop'+id+'">Все предметы данного вида</label></small>'},2,1,'width:300px;');
	}
}
function del(id,r){
	var inv1 = '';
	if($('#chidrop'+id).attr('checked') == true) {
		inv1 += 'deleteall7=1&';
	}
	if(r != 0) {
		inv1 += 'inv&otdel='+r+'&';
	} 
	top.getUrl('main','main.php?'+inv1+'delete='+id+'&sd4='+top.sd4key);
	
}

/* Использование предмета */
function useiteminv(id,img,name,x,date,r,inv){
	if(id>0){
		if(inv == 0) {
			r = 0;
		}
		win.add('iuse'+id,'Подтверждение',date,{'a1':'top.useitminv('+id+','+r+');'},2,1,'width:300px;');
	}
}
function useitminv(id,r){
	var inv1 = '';
	if(r != 0) {
		inv1 = 'inv&otdel='+r+'&';
	}
	top.getUrl('main','main.php?'+inv1+'use_pid='+id+'&sd4='+top.sd4key);
}

/* Бумага */
function addNewText(id,x,r) {
	win.add('iusemg'+id,'Сделать запись на предмете &nbsp;','<center>Введите текст сообщения:<br><small>(После добавления сообщение не удалить)</small><br></center>',{'a1':'top.useNewText(\''+id+'\',\''+r+'\');','usewin':'$(\'#iuseNewText'+id+'\').focus()','d':'<center><input style="width:96%; margin:5px;" id="iuseNewText'+id+'" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
}
function useNewText(id,r) {
	var inv1 = '';
	if(r != 0) {
		inv1 = 'inv=1&otdel='+r+'&';
	}
	top.getUrl('main','main.php?'+inv1+'itmid='+id+'&addtext='+$('#iuseNewText'+id).val()+'&sd4='+top.sd4key);
}

/* Использовать предмет в поединке */
function useMagicBattle(name,id,img,type,type_use,text,team)
{
	if(type_use == 1) {
		var onEnLogin = '';
		if( team == 1 ) {
			onEnLogin = top.c.login;
		}else{
			onEnLogin = '';
		}
		win.add('iusemg'+id,'Используем &quot;'+name+'&quot; &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.useMagicGoGoBattle(\'\',\''+id+'\',\''+type_use+'\');','usewin':'top.chat.inObj = $(\'#useMagicLoginBtl'+id+'\');$(\'#useMagicLoginBtl'+id+'\').focus()','d':'<center><input style="width:96%; margin:5px;" id="useMagicLoginBtl'+id+'" class="inpt2" type="text" value="'+onEnLogin+'"></center>'},3,1,'min-width:300px;');
		top.chat.inObj = $('#useMagicLoginBtl'+id);
	}else if(type_use == 2) {
		var txxt = '';
		var onEnLogin = top.c.login;
		txxt += "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='80' valign='middle'><div align='center'><img src='http://img.xcombats.com/i/eff/"+img+"'></div></td><td valign='middle' align='left'>&quot;<b>"+name+"</b>&quot;<br>Использовать сейчас?</td></tr></table>";
		win.add('iusemg'+id,'Используем &quot;'+name+'&quot; &nbsp;',txxt,{'a1':'top.useMagicGoGoBattle(\'' + onEnLogin + '\',\''+id+'\',\''+type_use+'\');'},2,1,'width:300px;');
	}
}
function useMagicGoGoBattle(url,id,type_use)
{

	top.frames['main'].useitem(id,1,$('#useMagicLoginBtl'+id).val());
}

/* Использовать предмет на */
function useMagic(name,id,img,type,urlUse)
{
	var onEnLogin = top.c.login;
	
	win.add('iusemg'+id,'Используем &quot;'+name+'&quot; &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.useMagicGoGo(\''+urlUse+'\',\''+id+'\');','usewin':'top.chat.inObj = $(\'#useMagicLogin'+id+'\');$(\'#useMagicLogin'+id+'\').focus()','d':'<center><input style="width:96%; margin:5px;" id="useMagicLogin'+id+'" class="inpt2" type="text" value="'+onEnLogin+'"></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#useMagicLogin'+id);
}
function useMagicGoGo(url,id)
{
	top.getUrl('main',url+'&login='+$('#useMagicLogin'+id).val()+'&sd4='+top.sd4key);
}

/* Использовать предмет на предмет */
function useRune(id,name,img,urlUse)
{
	win.add('iuseRN'+id,'Используем &quot;'+name+'&quot; &nbsp;','<center>Укажите название предмета:<br><small>(предмет должен находиться в инвентаре)</small></center>',{'a1':'top.useRuneGoGo(\''+urlUse+'\',\''+id+'\');','usewin':'$(\'#useRubeItem'+id+'\').focus()','d':'<center><input style="width:96%; margin:5px;" id="useRubeItem'+id+'" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
}

function useRuneGoGo(url,id)
{
	top.getUrl('main',url+'&item_rune='+$('#useRubeItem'+id).val()+'&sd4='+top.sd4key);
}

/* Используем смену */
function smena1()
{
	win.add('smena1_enemy','Смена противника &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.smena2($(\'#useSmena1_enemy\').val())','usewin':'top.chat.inObj = $(\'#useSmena1_enemy\');$(\'#useSmena1_enemy\').focus()','d':'<center><input style="width:96%; margin:5px;" id="useSmena1_enemy" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#useSmena1_enemy');
}

function smena2(login)
{
	top.frames['main'].smena_login = login;
	top.frames['main'].reflesh();
}

/* Использовать прием на */
function priemOnUser(pr,id,nm,onInUser)
{
	win.add('iusepr'+pr,'Используем &quot;'+nm+'&quot; &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.usePriemNow(\''+pr+'\');','usewin':'top.chat.inObj = $(\'#usePriemLogin'+pr+'\');$(\'#usePriemLogin'+pr+'\').focus()','d':'<center><input style="width:96%; margin:5px;" id="usePriemLogin'+pr+'" class="inpt2" type="text" onKeyPress="javascript: if (event.keyCode==13) { top.usePriemNow(\''+pr+'\');top.win.closew(\'iusepr'+pr+'\'); } " value="'+onInUser+'"></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#usePriemLogin'+pr);
	$('#usePriemLogin'+pr+'').focus();
}

function usePriemNow(id)
{
	top.frames['main'].use_on_pers = $('#usePriemLogin'+id).val();
	top.frames['main'].usepriem(id,1);
}
var datas = '';
/* системка о багах и предложениях */
function bagsandpartners(t)
//<textarea name="bgsaninp" rows="10" style="width:98%;resize:none;outline:none;" id="bgsaninp"></textarea>
{
	if(t == undefined) {
		top.datas = '';
	}
	//<div id="bgsaninp" name="bgsaninp" contenteditable="true" style="width:98%;resize:none;outline:none;margin-left:4px;height:150px;border:1px solid #b0b0b0;background:#f3efe6;"></div>
	win.add('bgsanwin1','Предложения и жалобы &nbsp;','<div style="padding:3px;height:20px;"><span style="float:left">Тип сообщения: <select name="bgsantp" id="bgsantp">  <option value="0"></option>  <option value="1">Предложение</option>  <option value="2">Жалоба</option>  <option value="3">Ошибка</option>  <option value="4">Сотрудничество</option>  <option value="5">Прочее</option></select></span><label style="float:right"><input style="vertical-align:middle" type="checkbox" name="bgsantp2" value="1" id="bgsantp2" /> <small>Срочное сообщение!</small></label></div>'+
	'<div id="bgsaninp" contenteditable="true" style="width:98%;resize:none;outline:none;margin-left:4px;height:200px;border:1px solid #b0b0b0;background:#f3efe6;">'+top.datas+'</div>'+
	'</div>',{'a1':'bgsanSend()','usewin':'$(\'#bgsaninp\').focus()','d':'<center><small><font color="#988e73">Ответ Вы получите по телеграфу в течении 24 часов после отправки.</font></small></center>'},3,1,'min-width:500px;');
	CKEDITOR.replace( 'bgsaninp' , {
		uiColor: '#b1a993',
				toolbar: [
					[ 'Bold', 'Italic', 'Underline' , 'RemoveFormat' , '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
					[ 'FontSize', 'TextColor', 'BGColor' , 'Image' , 'SpecialChar' , 'Maximize' ],
					[ 'UIColor' ]
				]

		} );
}

//Телеграф
function tgf_send() {
	//tgf_to , tgf_tema
	if($('#tgf_to').val() == '' || $('#tgf_text').val() == '' || $('#tgf_title').val() == '' || $('#tgf_to').val() == ' ' || $('#tgf_text').val() == ' ' || $('#tgf_title').val() == ' ') {
		$('#trf_snd_error').html('Все поля должны быть заполнены!');
	}else{
		top.tgf_lln = 0;
		top.tgf_txt = 'Отправка сообщения к <b>'+$('#tgf_to').val()+'</b>';
		top.tgf_loading(1);
		$.post('telegraf.php',{'r':3,'to':$('#tgf_to').val(),'tema':$('#tgf_tema').val(),'text':$('#tgf_text').val()},function(data){top.tgf_see(data)});
	}
}

function telegraf(t)
//<textarea name="bgsaninp" rows="10" style="width:98%;resize:none;outline:none;" id="bgsaninp"></textarea>
{
	top.tgf_lln = 0;
	top.tgf_tmr = null;
	top.tgf_txt='';
	var tg = '';
	tg += '<div style="width:500px;height:330px;position:relative"><div id="tgfmsgdiv" style="display:none;position:absolute;border:1px solid #d1c7ad;"></div><table width="100%" border="0" cellspacing="0" cellpadding="0">'+
  '<tr>'+
    '<td height="30"><table width="100%" border="0" cellspacing="1" cellpadding="0">'+
      '<tr>'+
        '<td width="33%" id="tgf_rz1" onclick="top.tgf_rz(1);" align="center" valign="middle" class="tf_btn11">&nbsp;Входящие&nbsp;</td>'+
        '<td align="center" id="tgf_rz2" onclick="top.tgf_rz(2);" valign="middle" class="tf_btn1">&nbsp;Отправленные&nbsp;</td>'+
        '<td width="33%" id="tgf_rz3" onclick="top.tgf_rz(3);" align="center" valign="middle" class="tf_btn1">&nbsp;Написать&nbsp;</td>'+
      '</tr>'+
    '</table></td>'+
  '</tr>'+
  '<tr>'+
    '<td height="280" align="left" valign="top" id="tgf_div">&nbsp;</td>'+
  '</tr>'+
  '<tr>'+
    '<td height="18" bgcolor="#b1a993"><div id="tgf_loadingLine">&nbsp;</div></td>'+
  '</tr>'+
'</table>'+
'</div>';
	
	win.add('telegraf1','Ваши сообщения &nbsp;',tg,{'usewin':'','d':'<center><small><font color="#988e73">Служба обмена сообщениями между персонажами</font></small></center>'},0,1,'min-width:500px;height:350px;');
	top.tgf_ico(0);
	top.tgf_rz(1);
}

var tgf_mo = 0;
function tgf_openMsg(id) {
	top.tgf_lln = 0;
	top.tgf_txt = 'Открываем сообщение №'+id;
	top.tgf_loading(1);
	top.tgf_mo = id;
	$('#tgfmsgdiv').css({'display':'','background-color':'#ebe4d0','width':'498px','height':'305'});
	$.post('telegraf.php',{'see_msg':id},function(data){$('#tgfmsgdiv').html(data);});
}

function tgf_closeMsg() {
	if(top.tgf_mo != 0) {
		$('#tgfm'+top.tgf_mo).attr({'className':'tgf_msg1'});
	}
	$('#tgfmsgdiv').css({'display':'none'});
	$('#tgfmsgdiv').html('');
}

var tgf_status = 0;
function tgf_ico(id) {
	if(id == 1) {
		$('#tgf_icon').css({'display':''});
	}else if(id == 0) {
		$('#tgf_icon').css({'display':'none'});
	}
	tgf_status = id;
}

function tgf_rz(id,pg,re) {
	if(pg == null) {
		pg = 1;
	}
	if(re == null) {
		re = 0;
	}
	top.tgf_lln = 0;
	top.tgf_txt = 'Загрузка данных по разделу';
	top.tgf_loading(1);
	$('#tgf_rz1').attr({'className':'tf_btn1'});
	$('#tgf_rz2').attr({'className':'tf_btn1'});
	$('#tgf_rz3').attr({'className':'tf_btn1'});
	$('#tgf_rz'+id).attr({'className':'tf_btn11'});
	$.post('telegraf.php',{'r':id,'p':pg,'re':re},function(data){top.tgf_see(data)});
}

function del_tgf(id,pg,idmsg) {
	top.tgf_ico(0);
	if(pg == null) {
		pg = 1;
	}
	top.tgf_lln = 0;
	top.tgf_txt = 'Удаление сообщения №'+idmsg;
	top.tgf_loading(1);
	$.post('telegraf.php',{'r':id,'p':pg,'del_msg':idmsg},function(data){top.tgf_see(data)});
}

function tgf_see(data) {
	$('#tgf_div').html(data);
	top.tgf_closeMsg();
}

function qn_win_cls() {
	$('#qsst').html('');
	$('#qsst').css('display','none');
}

//загрузка
var tgf_lln = 0,tgf_tmr,tgf_txt='';
function tgf_loading(id) {
	clearTimeout(top.tgf_tmr);
	if(id == 1) {
		//начинаем		
		top.tgf_lln += 5;
		var prc = Math.floor(top.tgf_lln/10000*500);
		if(prc < 0) {
			prc = 0;
		}else if(prc > 500) {
			prc = 500;
		}
		if(prc == 500) {
			alert('Не удалось отправить\получить запрос.');
		}
	}else{
		//завершаем
		top.tgf_lln += 275;
		var prc = Math.floor(top.tgf_lln/10000*500);
	}
	if(prc < 0) {
		prc = 0;
	}else if(prc > 500) {
		prc = 500;
	}
	$('#tgf_loadingLine').css({'width':prc+'px'});
	$('#tgf_loadingLine').html('<div style="position:absolute;white-space:nowrap;">&nbsp; &nbsp; '+top.tgf_txt+'</div>');
	if(top.tgf_lln < 10000) {
		top.tgf_tmr = setTimeout('top.tgf_loading('+id+')',10);
		$('#tgf_loadingLine').css({'background':'#9c9174'});
	}else{
		$('#tgf_loadingLine').html('&nbsp;');
		$('#tgf_loadingLine').css({'background':'#ddd5bf'});
		clearTimeout(top.tgf_tmr);
	}
}

function bgsanSend() {
	top.datas = CKEDITOR.instances.bgsaninp.getData();
	$.post('bag.php',{'text':datas,'type':$('#bgsantp').val(),'type2':$('#bgsantp2').val()},function(data){ top.bgsanSendReturn(data); });
}

function bgsanSendReturn(data) {
	if(data == 1) {
		//все отлично
		alert('Ваше сообщение успешно оправлено!');
		top.datas = '';
	}else{
		//ошибка отправки
		if(data == -1) {
			alert('Перезайдите на персонажа с главной');
			top.datas = '';
			top.location = "http://xcombats.com/";
		}else if(data == -2) {
			alert('Нельзя отправлять пустое сообщение');
		}else if(data == -3) {
			alert('Выберите корректный тип сообщения');
		}else if(data == -4) {
			alert('IP не соответствует действительному');
		}else{
			alert(' Сообщение не удалось отправить '+data);
		}
		
		if(data != -1) {
			top.bagsandpartners(true);
		}
	}
}


/*
				 * Adjust the behavior of the dataProcessor to avoid styles
				 * and make it look like FCKeditor HTML output.
				 */
				function configureHtmlOutput( ev ) {
					var editor = ev.editor,
						dataProcessor = editor.dataProcessor,
						htmlFilter = dataProcessor && dataProcessor.htmlFilter;

					// Out self closing tags the HTML4 way, like <br>.
					dataProcessor.writer.selfClosingEnd = '>';

					// Make output formatting behave similar to FCKeditor
					var dtd = CKEDITOR.dtd;
					for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
						dataProcessor.writer.setRules( e, {
							indent: true,
							breakBeforeOpen: true,
							breakAfterOpen: false,
							breakBeforeClose: !dtd[ e ][ '#' ],
							breakAfterClose: true
						});
					}

					// Output properties as attributes, not styles.
					htmlFilter.addRules( {
						elements: {
							$: function( element ) {
								// Output dimensions of images as width and height
								if ( element.name == 'img' ) {
									var style = element.attributes.style;

									if ( style ) {
										// Get the width from the style.
										var match = ( /(?:^|\s)width\s*:\s*(\d+)px/i ).exec( style ),
											width = match && match[ 1 ];

										// Get the height from the style.
										match = ( /(?:^|\s)height\s*:\s*(\d+)px/i ).exec( style );
										var height = match && match[ 1 ];

										if ( width ) {
											element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
											element.attributes.width = width;
										}

										if ( height ) {
											element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
											element.attributes.height = height;
										}
									}
								}

								// Output alignment of paragraphs using align
								if ( element.name == 'p' ) {
									style = element.attributes.style;

									if ( style ) {
										// Get the align from the style.
										match = ( /(?:^|\s)text-align\s*:\s*(\w*);/i ).exec( style );
										var align = match && match[ 1 ];

										if ( align ) {
											element.attributes.style = element.attributes.style.replace( /(?:^|\s)text-align\s*:\s*(\w*);?/i , '' );
											element.attributes.align = align;
										}
									}
								}

								if ( !element.attributes.style )
									delete element.attributes.style;

								return element;
							}
						},

						attributes: {
							style: function( value, element ) {
								// Return #RGB for background and border colors
								return CKEDITOR.tools.convertRgbToHex( value );
							}
						}
					});
				}



/* Поединки */
var bcl = Array();
var bclLast = Array();
var id_log_ar = Array();
bcl[1] = 0;
bcl[2] = 1;
bcl[3] = 0;
bcl[4] = 0;
function goSit(dd)
{
	if(top.frames['main']!=undefined)
	{
		if(top.frames['main'].document.getElementById('auto_battle')!=undefined)
		{
			top.frames['main'].document.getElementById('auto_battle').value = bcl[3];
		}
		if(top.frames['main'].document.getElementById('save_zones')!=undefined)
		{
			top.frames['main'].document.getElementById('save_zones').value = bcl[4];
		}
		if(top.frames['main'].document.getElementById('fast_battle')!=undefined)
		{
			top.frames['main'].document.getElementById('fast_battle').value = bcl[1];
		}
	}
}
var type_log = 0; //1 - в мейне, 0 - в чате
function btlclearlog()
{
	if(type_log == 1) {
		$(top.frames['main'].document.getElementById('battle_logg')).html('');
	}else{
		$(top.document.getElementById('battle_logg')).html('');
	}
}
function r_page(a){
top.frames['main'].location.reload();
}

var key_actions = ['','',1];
$(document).ready(function(){
	$('#globalMain').click(function(){top.win.addaction(2,0);});
	$(document).keypress(function(e){
		if(top.key_actions[2] == 1) {
			if(e.keyCode==13 && top.key_actions[0] != ''){
				//нажата клавиша enter
				eval(top.key_actions[0]);
			}
		}
	});
});

function qn_slk(obr) {
	if(top.qst_sml != '') {
		
		$('#qsst').fadeIn('fast');
		$('#mini_qsst').fadeOut('fast');
		$('#mini_qsst').html('');
		top.qst_sml = '';
		
	}else{
		
		$('#mini_qsst').html('<img title="Квестовое задание" src="http://xcombats.com/bot_q/mini_'+obr+'.png" width="50" height="50">');
		$('#qsst').fadeOut('fast');
		$('#mini_qsst').fadeIn('fast');
		top.qst_sml = obr;
		
	}
}

function qn_win(t,obr) {
	$('#qsst').html(
	'<table width="710" border="0" cellspacing="0" cellpadding="0">'+
		'<tr>'+
		  '<td width="12"><div style="position:relative;"> <img style="display:block; position:absolute; top:-27px; left:598px;" src="i/ric1_2g.png" width="147" height="72">'+
		  '<img style="display:block; position:absolute; top:-27px; left:-35px;" src="i/ric12g.png" width="147" height="72"></div></td>'+
		  '<td height="9" background="i/line_32g.png"><img src="http://img.xcombats.com/1x1.gif" style="display:block" height="1" width="1"></td>'+
		  '<td width="12"></td>'+
		'</tr>'+
		'<tr>'+
		  '<td background="i/line_12g.png">&nbsp;</td>'+
		  '<td bgcolor="#EBEBEB" style="padding:20px">'+
		  '<!-- enter -->'+
	'<div style="width:150px;float:left;text-align:center;"><img src="http://xcombats.com/bot_q/'+obr+'.jpg" width="140" height="170"><br><br><center><a href="javascript:void(0)" onclick="top.qn_slk(\''+obr+'\')">Свернуть</a></center></div><div style="width:485px;float:right;">'+t+'</div>'+
	'<!-- enter -->'+
	'</td>'+
      '<td background="i/line_22g.png">&nbsp;</td>'+
    '</tr>'+
    '<tr>'+
      '<td><div style="position:relative;"> <img style="display:block; position:absolute; top:-32px; left:606px;" src="i/ric2_2g.png" width="111" height="63"> <img style="display:block; position:absolute; top:-32px; left:-7px;" src="i/ric22g.png" width="111" height="63"> </div></td>'+
      '<td height="9" background="i/line_42g.png"><img style="display:block" height="1" width="1"></td>'+
      '<td></td>'+
    '</tr>'+
  '</table>'
	);
	if(top.qst_sml == '') {
		$('#qsst').css('display','block');
	}else{
		if(top.qst_sml != obr) {
			$('#mini_qsst').css('display','block');
			$('#mini_qsst').html('<img src="http://xcombats.com/bot_q/mini_'+obr+'.png" width="50" height="50">');
		}
	}
	$('#qsst').center();
}

var radioon = false;
var radiolist = [
	
];
function radioplay(tn,h,i) {
	//alert('Ищем человека который смог бы заняться радио ;)');
	//$('#radio_audio').attr('src','http://xcombats.com/audio/'+tn+'');
	//radioGoTime(h,i);
}
function radioGoTime(h,i) {
	//timeupdate
	//document.getElementById('radio_audio').timeupdate(30);
	document.getElementById('radio_audio').play();
}
function radiostop() {
	//$('#radio_audio').attr('src','');
	document.getElementById('radio_audio').pause();
}
function radioClick() {
	if( radioon == false ) {
		$('#radiobtn1').removeClass('radiobtn');
		$('#radiobtn1').addClass('radiobtn2');
		$('#radiobtn1').attr('title','Радио (Включено)');
		radioplay('track1.mp3',0,0);
		radioon = true;
		//$('#rdo').show();
	}else{
		$('#radiobtn1').removeClass('radiobtn2');
		$('#radiobtn1').addClass('radiobtn');
		$('#radiobtn1').attr('title','Радио (Выключено)');
		radiostop();
		radioon = false;
		//$('#rdo').hide();
	}
}

//Квестовое выделение
var qst_sml = true;

var noob = {
	takeData:function(id,bot_img,bot_name,title,action,next,inf) {
		if( action == 'next' ) {
			qn_win(inf + '<hr><a href="main.php?nextfnq=1" target="main">Далее &raquo;</a>','wm1');
		}else{
			qn_win(inf + '<hr><b>Необходимо сделать:</b><br>' + action + '','wm1');
		}
	},
	no:function() {
		$('#qsst').html('');
		$('#mini_qsst').css('display','none');
		$('#mini_qsst').html('');
	}
};