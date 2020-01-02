	var sml = new Array("smile",18,18,  "laugh",15,15,  "fingal",22,15,  "eek",15,15,  "smoke",20,20,  "hi",31,28,  "bye",15,15,
	"king",21,22, "king2",28,24, "boks2",28,21, "boks",62,28,  "gent",15,21,  "lady",15,19,  "tongue",15,15,  "smil",16,16,  "rotate",15,15,
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

function buyShopNow(id,url)
{
	var i = top.frames['main'].document.getElementById('shpcolvo'+id);
	if(i!=undefined)
	{
		url += '&x='+i.value;
	}
	top.frames['main'].location = url;
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

/* Выкинуть предмет */
function drop(id,img,name,x,date,r,fdfdf)
{
	if(id>0)
	{
		win.add('idrop'+id,'Выбросить предмет?',date,{'a1':'top.del('+id+','+r+');','n':'<small><input type="checkbox" name="checkbox" id="checkbox"> <label for="checkbox">Все предметы данного вида</label></small>'},2,1,'width:300px;');
	}
}
function del(id,r)
{
	top.getUrl('main','main.php?inv&otdel='+r+'&delete='+id+'&sd4='+top.sd4key);
}

/* Использование предмета */
function useiteminv(id,img,name,x,date,r)
{
	if(id>0)
	{
		win.add('iuse'+id,'Подтверждение',date,{'a1':'top.useitminv('+id+','+r+');'},2,1,'width:300px;');
	}
}
function useitminv(id,r)
{
	top.getUrl('main','main.php?inv&otdel='+r+'&use_pid='+id+'&sd4='+top.sd4key);
}

/* Использовать предмет на */
function useMagic(name,id,img,type,urlUse)
{
	win.add('iusemg'+id,'Используем &quot;'+name+'&quot; &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.useMagicGoGo(\''+urlUse+'\',\''+id+'\');','usewin':'top.chat.inObj = $(\'#useMagicLogin'+id+'\');$(\'#useMagicLogin'+id+'\').focus()','d':'<center><input style="width:96%; margin:5px;" id="useMagicLogin'+id+'" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#useMagicLogin'+id);
}
function useMagicGoGo(url,id)
{
	top.getUrl('main',url+'&login='+$('#useMagicLogin'+id).val()+'&sd4='+top.sd4key);
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
function priemOnUser(pr,id,nm)
{
	win.add('iusepr'+pr,'Используем &quot;'+nm+'&quot; &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small></center>',{'a1':'top.usePriemNow(\''+pr+'\');','usewin':'top.chat.inObj = $(\'#usePriemLogin'+pr+'\');$(\'#usePriemLogin'+pr+'\').focus()','d':'<center><input style="width:96%; margin:5px;" id="usePriemLogin'+pr+'" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
	top.chat.inObj = $('#usePriemLogin'+pr);
}

function usePriemNow(id)
{
	top.frames['main'].use_on_pers = $('#usePriemLogin'+id).val();
	top.frames['main'].usepriem(id,1);
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
function btlclearlog()
{
	if(top.frames['main'].document.getElementById('battle_logg')!=undefined)
	{
		top.frames['main'].document.getElementById('battle_logg').innerHTML = '';	
	}
}
function r_page(a){
top.frames['main'].location.reload();
}