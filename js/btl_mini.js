top.goSit(1);
var zas = new Array();
var priem_use = 0;
var magic_use = 0;
var use_on_pers = 'none';
var smena_login = 'none';
var leader_login = 'none';
var leader_type = 1;

    zas[1] = 0;
    zas[2] = 0;
    zas[3] = 0;
    zas[4] = 0;
    zas[5] = 0;
var zbs = new Array();
    zbs[1] = 0;
var sel_atack = 1;
var nos = 0;
var noconnect = 5;
var connect = 0;
var eatk = 0;
var ggcode = '2014';
var irn = 0;

function magic_div(id,name,img,title)
{
  
}

function testClearZone()
{
	if(top.slcbrc[4] == 0) {
		all_radio_off();
	}
}

function usepriem(id,t)
{
	if(t==1)
	{
		irn++;
		if( top.c.noEr == 0 ) {
			top.c.noEr = 1; clearTimeout(top.c.noErTmr); /*top.c.noErTmr = setTimeout('top.c.noEr = 0;',1000);*/
			$.post("jx/battle/refresh" + server_fight + ".php?rnd="+ggcode+"&irn="+irn,{idlog:top.id_log,usepriem:id,useon:use_on_pers},function(data){$("#ref").html(data);});
		}
		use_on_pers = 'none'; top.goSit(1);
	}else{
		alert('Not found');
	}
}

function useitem(id,t,use_item_on)
{
	if(t==1)
	{
		irn++;
		if( top.c.noEr == 0 ) {
			top.c.noEr = 1; clearTimeout(top.c.noErTmr); /*top.c.noErTmr = setTimeout('top.c.noEr = 0;',1000);*/
			$.post("jx/battle/refresh" + server_fight + ".php?rnd="+ggcode+"&irn="+irn,{idlog:top.id_log,useitem:id,useitemon:use_item_on},function(data){$("#ref").html(data);});
		}
		top.goSit(1);
	}else{
		alert('Not found');
	}
}

function volna(id)
{
	document.getElementById('volna').innerHTML = 'Волна: '+id;
}

function change_radioKeys(id,cper) {
	var z = 0, t = 0, k = 0;	
	var i = 1, j = 1;
	while(i <= za) {
		j = 1; jo = 0;
		while(j <= 5) {
			var radio = document.getElementById('atack_'+i+'_'+j);
			if(radio != undefined) {
				if(radio.className != null && radio.className == "radio_on") {
					jo++;
				}
			}
			j++;
		}
		if(jo == 0 && z == 0) {
			z = i;
			t = 'atack';
		}
		i++;
	}	

	if( z == 0 ) {
		var i = 1, jo = 0;
		while(i <= 5) {
			var radio = document.getElementById('block_1_'+i);
			if(radio != undefined) {
				if(radio.className != null && radio.className == "radio_on") {
					jo++;
				}
			}
			i++;
		}
		if(jo == 0 && z == 0) {
			z = 1;
			t = 'block';
		}	
	}
	
	if( ( z == 0 || id == 0 )  && cper == false) {
		all_radio_off();
		refleshPoints();
		//change_radioKeys(id,true);		
	}
	
	if(z > 0) {
		if(id == 0) {
			//автовыставление

		}else{
			change_radio(z,id,t,1);
		}
	}
}

function change_radio(id,zone,type,r)
{
  radio_off(id,zone,type);
  var radio = document.getElementById(type+'_'+id+'_'+zone);
  if(radio.className == "radio_on" && r==0)
  {
    radio.className = "radio_off";
	if(type=='atack')
	{
	 zas[id] = 0;
	}else{
	 zbs[id] = 0;
	}
  }else{
    radio.className = "radio_on";
	if(type=='atack')
	{
	 zas[id] = zone;
	}else{
	 zbs[id] = zone;
	}
  } 
  refleshPoints(); 
}

function tactic(id,value)
{
  document.getElementById('tac'+id).innerHTML = value;
}

function refleshPoints()
{
  clearZone();
  var i = 5;
  while(i>=1)
  {
    if(zas[i]==0)
	{
	  lineAtack(i);
	}
	i--;
  }
  if(zbs[1]==0)
  {
    lineBlock();
  }
}

function lineAtack(id)
{
  nos++;
  var j = 1;
  while(j<=5)
  {
	document.getElementById('zatack'+id+'_'+j+'').className='zoneCh_yes';	  
	j++;
  }
}

function lineBlock()
{
  nos++;
  var j = 1;
  while(j<=5)
  {
	document.getElementById('zblock1_'+j+'').className='zoneCh_yes';	  
	j++;
  }
}

function clearZone()
{
  nos = 0;
  var i = 1;
  while(i<=5)
  {
    var j = 1;
	while(j<=5)
	{
	  document.getElementById('zatack'+i+'_'+j+'').className='zoneCh_no';	  
	  j++;
	}
	i++;
  }
  var i = 1;
  while(i<=5)
  {
	document.getElementById('zblock1_'+i+'').className='zoneCh_no';	  
	i++;
  }
}

function select_atack(id,r)
{
	var i = 5;
	while(i>=1)
	{
		if(zas[i]==0)
		{
			if(i<=za)
			{
			sel_atack = i;
			}
		}
	i--;
   }
   if(sel_atack>za)
   {
     sel_atack = 1;
   }
   change_radio(sel_atack,id,'atack',r);
   sel_atack++;
}

function radio_off(id,zone,type)
{
  var i = 1;
  while(i<=5)
  {
    if(document.getElementById(type+'_'+id+'_'+i)!=undefined && i!=zone)
	{
	 document.getElementById(type+'_'+id+'_'+i).className = "radio_off";
	  if(type=='atack')
	  {
	   zas[id] = 0;
	  }else{
	   zbs[id] = 0;
	  }
	}
	i++;
  }
}
function all_radio_off()
{
	var i = 1;
	while(i<=5)
	{
		var j = 1;
		while(j<=5)
		{
		document.getElementById('atack_'+j+'_'+i).className = "radio_off";				
		j++;
		}
	document.getElementById('block_1_'+i).className = "radio_off";	
	zas[i] = 0;	
	i++;
	}
	zbs[1] = 0;
}
function genZoneBlock()
{ 
  var i = 1;
  while(i<=5)
  {
	var j = 1;
	while(j<=3)
	{
	  if(j==zb)
	  {
	  document.getElementById('txtb'+i+'_'+j+'').style.display = '';
	  }else{
	   document.getElementById('txtb'+i+'_'+j+'').style.display = 'none';
	  }
	  j++;
	}
	i++;
  }
}

function genZoneAtack()
{
	var i = 1;
	while(i<=5)
	{
		var j = 1;
		while(j<=5)
		{
			if(i<=za)
			{
			document.getElementById('zatack'+i+'_'+j+'').style.display = '';
			} else {
			document.getElementById('zatack'+i+'_'+j+'').style.display = 'none';
			}
		j++;
		}
	i++;
	}
}

function nocon()
{
	if(connect==0)
	{
		if(noconnect<0)
		{
			//document.getElementById('ref').innerHTML = '<font color=red><b><center>Подождите, идет инициализация...</center></b></font>';
			//g_iCount = 45;
		}
		//noconnect--;
		//setTimeout('nocon()',15000);
	}
}

function genteam(team)
{
document.getElementById('teams').innerHTML = team;
}
var t057 = null;
var battle_end = 0;
function reflesh(bl)
{
  irn++;
  if( battleFinishData != -1 ) {
	 mainstatus(0); 
  }
  if( ( battle_end==0 || bl!=null ) && battleFinishData == -1 )
  {
	//$('#pers_magic').html(battle_end+'|'+ggcode);
	//noconnect = 5; connect = 0;  
	if( top.c.noEr == 0 ) {
		top.c.noEr = 1; clearTimeout(top.c.noErTmr); /*top.c.noErTmr = setTimeout('top.c.noEr = 0;',1000);*/
		$.post("jx/battle/refresh" + server_fight + ".php?irn="+irn+"&rnd="+ggcode,{id:'reflesh',idlog:top.id_log,idpr:priem_use,mgid:magic_use,useon:use_on_pers,smn:smena_login,ldrl:leader_login,ldrt:leader_type},function(data){$("#ref").html(data);});	
	}
	if(g_iCount!=45)
    {
      g_iCount = 45;
    }
   
    if(document.getElementById('reflesh_btn')!=undefined)
    {
    	
    }
   
     
   }
}
function autobattle()
{
	var i = 1;
	while (i<=za)
	{
		if(zas[i] == 0) {
			zas[i] = Math.floor(Math.random(5)*5+1);
		}
		i++;	
	}
	if(zbs[1] == 0) {
		zbs[1] = Math.floor(Math.random(5)*5+1);
	}
}
function atack()
{
  if(top.slcbrc[3]==1)
  {
	autobattle();
  }
  var ago = ""+zas[1]+"_"+zas[2]+"_"+zas[3]+"_"+zas[4]+"_"+zas[5]+"";
  var bgo = zbs[1];
  if(eatk==0)
  {
  	//mainstatus(2);
  }
  irn++;
	if( top.c.noEr == 0 ) {
		top.c.noEr = 1; clearTimeout(top.c.noErTmr); /*top.c.noErTmr = setTimeout('top.c.noEr = 0;',1000);*/
  		$.post("jx/battle/refresh" + server_fight + ".php?irn="+irn+"&rnd="+ggcode,{atack:ago,block:bgo,idlog:top.id_log,idpr:priem_use,mgid:magic_use,useon:use_on_pers,smn:smena_login,ldrl:leader_login,ldrt:leader_type},function(data){$("#ref").html(data);});	
	}
}

var g_iCount = new Number();
var g_iCount = 45;
var tmr0057 = null;
function startCountdown()
{
 if(tmr0057 != null)
 {
 	clearTimeout(tmr0057);
 }
 if((g_iCount - 1) >= 0)
 { 
  g_iCount = g_iCount - 1;
  tmr0057 = setTimeout('startCountdown()',1000);
 }else{
  reflesh();
  tmr0057 = setTimeout('startCountdown()',1000);
 }
}

var img_battle = "<img src='http://"+top.c.img+"/i/battle/1.jpg'>";

function mainstatus(id)
{
  if(smnpty <= 0) {
	  $('#btn_down_img2').css({'display':'none'});
  }else{
	  $('#btn_down_img2').css({'display':''});
	  $('#btn_down_img2').attr('title','Смена противника ('+smnpty+')');
  }
  if( battleFinishData != -1 ) {
	document.getElementById('mainpanel2').style.display = '';
	document.getElementById('go_btn').style.display = 'none';
	document.getElementById('mainpanel').style.display = 'none';
	document.getElementById('mainpanel222').style.display = 'none';
	document.getElementById('reflesh_btn').style.display = 'none';
	document.getElementById('back_menu_down').style.display = '';
	document.getElementById('btn_down_img1').style.display = '';
	document.getElementById('btn_down_img2').style.display = 'none';
	document.getElementById("ref").innerHTML = "<center><font color='red'><b>" + battleFinishData + "</b></font></center>";
	rand_img();
	document.getElementById("player2").innerHTML = "<div style='margin-top:18px;' align='right'>"+img_battle+"</div>";
	document.getElementById('player2_login').style.display = 'none';
  }else if(id==1) //Можно ударить противника
  {
	document.getElementById('mainpanel').style.display = '';
	document.getElementById('player2_login').style.display = '';
	document.getElementById('mainpanel222').style.display = '';
	document.getElementById('mainpanel2').style.display = 'none';
	document.getElementById('go_btn').style.display = '';
   if(document.getElementById('reflesh_btn')!=undefined)
   {
	  document.getElementById('reflesh_btn').style.display = 'none';
   }
  }else if(id==2) //Ожидаем хода противника
  {
    document.getElementById('mainpanel').style.display = 'none';
	document.getElementById('mainpanel222').style.display = 'none';
	document.getElementById('mainpanel2').style.display = '';
	document.getElementById('go_btn').style.display = 'none';
	document.getElementById('reflesh_btn').style.display = '';
	rand_img();
	document.getElementById("player2").innerHTML = "<div style='margin-top:18px;' align='right'>"+img_battle+"</div>";
	document.getElementById('player2_login').style.display = 'none';
  }else if(id==3) // Проиграли. Ожидаем завершения поединка
  {
	document.getElementById('mainpanel2').style.display = '';
	document.getElementById('go_btn').style.display = 'none';
	document.getElementById('mainpanel').style.display = 'none';
	document.getElementById('mainpanel222').style.display = 'none';
	//document.getElementById('reflesh_btn').style.display = 'none';
	//document.getElementById('back_menu_down').style.display = '';
	//
	document.getElementById('back_menu_down').style.display = 'none';
	document.getElementById('reflesh_btn').style.display = '';
	//
	document.getElementById('btn_down_img1').style.display = '';
	document.getElementById('btn_down_img2').style.display = 'none';
	document.getElementById("ref").innerHTML = "<font color='red'><b>Вы повержены. Ожидайте пока поединок завершат другие бойцы...</b></font>";
	rand_img();
	document.getElementById("player2").innerHTML = "<div style='margin-top:18px;' align='right'>"+img_battle+"</div>";
	document.getElementById('player2_login').style.display = 'none';
  }
  if(document.getElementById('mainpanel').style.display == '') {
	 document.getElementById('mainpanel2').style.display = 'none'; 
  }
  top.goSit(1);
}
function rand_img()
{
	if(level<4)
	{
	img_battle = "<img src='http://img.xcombats.com/i/battle/"+(Math.floor(Math.random(2)*2))+".gif'>";
	} else {
	img_battle = "<img src='http://img.xcombats.com/i/battle/"+(Math.floor(Math.random(29)*29))+".jpg'>";
	}
}
var fstlh = 0;
var lsti = 0;
var lsthd = new Array();
var id_log_ar = new Array();
var id_log;
var type_log = top.type_log;
function add_log(id,foryou,text,hod_id,my,last_hod,vars)
{
	if( type_log == 1 ) {
		chsee = 'chsee2';
		if(my==1)
		{
			chsee = 'chsee3';
		}
		//if(id_log_ar[id]!=id)
		//{
			text = looklogrep(text,vars);
			id_log_ar[id] = id;
			id_log = id;
			if(top.frames['main'].document.getElementById("battle_log_"+hod_id+"")==undefined && hod_id!=1)
			{
				if(fstlh==0)
				{
					fstlh = hod_id;
				}
				lsthd[lsti] = hod_id; lsti++;
				top.frames['main'].document.getElementById('battle_logg').innerHTML = top.frames['main'].document.getElementById('battle_logg').innerHTML+'<div style="padding-top:2px;padding-bottom:2px;" id="battle_log_'+hod_id+'" class="battle_hod_style"></div>';
			} else if (top.frames['main'].document.getElementById("battle_log_"+hod_id+"")==undefined)
			{
				top.frames['main'].document.getElementById('battle_logg').innerHTML = top.frames['main'].document.getElementById('battle_logg').innerHTML+'<div style="padding-top:2px;padding-bottom:2px;" id="battle_log_'+hod_id+'"></div>';
			}
			top.frames['main'].document.getElementById("battle_log_"+hod_id+"").innerHTML = top.frames['main'].document.getElementById("battle_log_"+hod_id+"").innerHTML+'<span id="log_id_'+id+'" class="foryou'+foryou+'">'+text+'</span><br>';
		//}
		if(top.frames['main'].document.getElementById("battle_log_"+(hod_id-5))!=undefined)
		{
			//top.rmve('#battle_log_'+(hod_id-10));
		}
	}else{
		chsee = 'chsee2';
		if(my==1)
		{
			chsee = 'chsee3';
		}
		//if(id_log_ar[id]!=id)
		//{
			text = looklogrep(text,vars);
			id_log_ar[id] = id;
			id_log = id;
			if(top.document.getElementById("battle_log_"+hod_id+"")==undefined && hod_id!=1)
			{
				if(fstlh==0)
				{
					fstlh = hod_id;
				}
				lsthd[lsti] = hod_id; lsti++;
				top.document.getElementById('battle_logg').innerHTML = top.document.getElementById('battle_logg').innerHTML+'<div style="padding-top:2px;padding-bottom:2px;" id="battle_log_'+hod_id+'" class="battle_hod_style"></div>';
			} else if (top.document.getElementById("battle_log_"+hod_id+"")==undefined)
			{
				top.document.getElementById('battle_logg').innerHTML = top.document.getElementById('battle_logg').innerHTML+'<div style="padding-top:2px;padding-bottom:2px;" id="battle_log_'+hod_id+'"></div>';
			}
			top.document.getElementById("battle_log_"+hod_id+"").innerHTML = top.document.getElementById("battle_log_"+hod_id+"").innerHTML+'<span id="log_id_'+id+'" class="foryou'+foryou+'">'+text+'</span><br>';
		//}
		if(top.document.getElementById("battle_log_"+(hod_id-5))!=undefined)
		{
			//top.rmve('#battle_log_'+(hod_id-10));
		}

	}
}

var moveState = false;
// Переменные координат мыши в начале перемещения, пока неизвестны
var x0, y0;
// Начальные координаты элемента, пока неизвестны
var divX0, divY0;


function defPosition(event) {
    var x = y = 0;
    if (document.attachEvent != null) { // Internet Explorer & Opera
        x = window.event.clientX + documentElement.scrollLeft + document.body.scrollLeft;
        y = window.event.clientY + documentElement.scrollTop + document.body.scrollTop;
    }
    if (!document.attachEvent && document.addEventListener) { // Gecko
        x = event.clientX + window.scrollX;
        y = event.clientY + window.scrollY;
    }
    return {x:x, y:y};
}

function initMove(div, event) {
    var event = event || window.event;
    x0 = defPosition(event).x;
    y0 = defPosition(event).y;
    divX0 = parseInt(div.style.left);
    divY0 = parseInt(div.style.top);
    moveState = true;
}

document.onmouseup = function() {
    moveState = false;
}

// И последнее
// Функция обработки движения:
function moveHandler(div, event) {
    var event = event || window.event;
    if (moveState) {
        div.style.left = divX0 + defPosition(event).x - x0;
        div.style.top  = divY0 + defPosition(event).y - y0;
    }
}

function usePriem(id)
{
  priem_use = id;
  if(id!=0)
  {
    reflesh();
  }
}

//document.onkeydown=key;
function key()
{
	//window.status=event.keyCode;
	//if(event.keyCode==13){ atack(); }
}