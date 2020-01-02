<?
if(!defined('GAME')){
	die();
}

$stl = 15; //кол-во стилей клеток
?>
<script>
var refnow1 = 0;
var md = 0;
var xxx = 0;
var yyy = 0;
function loadDate(){
		$.post('../../jx/dungeon.php',{x:xxx,y:yyy,ddid:<?=(int)$_GET['r']?>,id:<? echo time(); ?>,mdf:md},function(data){$("#rd").html(data);});
		setTimeout('refnow1 = 0;',1000);
}

function startDunge(uid,login,level,hp,hpAll,x,y)
{
	
}

function er(t)
{
	document.getElementById('error').innerHTML = '<font color="red">'+t+'</font>';
}

function addPxMap(id,img,x,y,clk)
{
	var m = document.getElementById('map');
	if(m!=undefined)
	{
		
	}else{
		er('Ошибка построения карты...');
	}
}

function tc(v)
{
	v = v.split('px');
	v = Math.round(v[0]);	
	return v;
}

function getPix(v)
{
	v = v.split('_');
	var r = {'x':v[1],'y':v[2]};
	return r;
}

function goYou()
{
	var you = document.getElementById('map_you');
	if(you!=undefined)
	{ 
		var l1 = you.offsetLeft;  
		var t1 = you.offsetTop;  
		var pr = you.offsetParent;
		pr = getPix(pr.id);
		goPix(pr['x'],pr['y']);
	}else{
		er('Ошибка получения координат');
	}
}

var nowGo;
var mapNow = '';
var mapNew = '';
var obj = '';
var users = '';
//начальные координаты
var xn = 9;
var yn = 9;

function mapArray(v)
{
	var i=0,j = new Array,k = 0,vl = new Array;
	v = v.split('|');
	while(i!=-1)
	{
		if(v[i]!=undefined && v[i]!='end' && v[i]!='')
		{
			j = v[i].split('=');
			if(j[0]>0)
			{
				vl[j[1]+'_'+j[2]] = j;
				k++;
			}
		}else{
			i  = -2;
		}
		i++;
	}
		
	return vl;
}

function testGo(x,y)
{
	$.post('../../jx/dungeon.php',{x:xxx,y:yyy,ddid:<?=(int)$_GET['r']?>,id:<? echo time(); ?>,mdf:md,gox:x,goy:y},function(data){$("#rd").html(data);});
	setTimeout('refnow1 = 0;',500);
}

//перераспределение клеток
function refleshMapDate()
{
	var i = 0,j = 0,nx,ny,pix;
	var mapArr = mapArray(mapNew);
	var ob  = new Array();
	var ob2 = new Array();
	var ob3 = new Array();
	var ub  = new Array();
	var ub2 = new Array();
	var ub3 = new Array();
		
	//создаем обьекты на карте
	var k = 0; var o1 = obj.split('|#|'); var ok2 = 1;
	while(k!=-1)
	{
		if(o1[k]!=undefined && o1[k]!='')
		{
			var o2 = o1[k].split('|!|');
			ob[o2[0]] += ok2+'|$|';
			ob2[ok2] = o2;
			ob3[o2[5]+'!'+o2[6]] = ok2;
			ok2++;
		}else{
			k = -2;
		}
		k++;
	}
	
	//создаем юзеров на карте
	var k = 0; var u1 = users.split('|$|'); var uok2 = 1;

	while(k!=-1)
	{
		if(u1[k]!=undefined && u1[k]!='')
		{
			var u2 = u1[k].split('=');
			ub2[uok2] = u2;
			ub3[u2[3]+'!'+u2[4]] = uok2;
			uok2++;
		}else{
			k = -2;
		}
		k++;
	}

	var oid,ogo,objst;
	while(i<=17)
	{
		j = 0;
		while(j<=17)
		{
			
			pix = document.getElementById('map_'+j+'_'+i+'');
			
			nx = j-9;
			ny = i-9;
			nwx = xn+nx;
			nwy = yn+ny;
			
			objst = '';
			
			//обновляем обьекты
			if(ob3[''+nwx+'!'+nwy+'']!=undefined)
			{
				oid = ob3[''+nwx+'!'+nwy+''];
				ogo = ob2[oid];
				objst = '<img src="http://<? echo $c['img']; ?>/dn/'+ogo[3]+'" title="'+ogo[4]+'" />';
			}
			
			//обновляем юзеров
			if(ub3[''+nwx+'!'+nwy+'']!=undefined)
			{
				oid = ub3[''+nwx+'!'+nwy+''];
				ogo = ub2[oid];
				if(ogo[0]!=<? echo $u->info['id']; ?>)
				{
					if(ogo[5]==0)
					{
						//игрок
						objst = '<img width="16" height="16" src="http://<? echo $c['img']; ?>/dn/users_.png" title="'+ogo[1]+' ['+ogo[2]+']" />';
					}else{
						//бот
						objst = '<img width="28" height="28" src="http://<? echo $c['img']; ?>/dn/'+ogo[6]+'" title="'+ogo[1]+' ['+ogo[2]+'] (Бот)" />';
					}
				}
			}
			
			//обновляем клетку
			if(pix!=undefined)
			{
				//заменяем клетки
				thm = mapArr[''+nwx+'_'+nwy+''];
				if(thm!=undefined && thm[0]>0)
				{
					pix.className = 'dpix'+thm[3];
					pix.innerHTML = '<div style="height:32px;width:32px;cursor:pointer;" <? if($u->info['admin']>0 || $u->info['id']==1000010){  ?>oncontextmenu="adminion('+thm[1]+','+thm[2]+',event); return false;"<? } ?> onclick="testGo('+nwx+','+nwy+');" id="content_'+j+'_'+i+'">'+objst+'</div>';
				}else{
					<? if($u->info['admin']>0 || $u->info['id']==1000010){ ?>
					pix.className = '';
					pix.innerHTML = '<div class="newpix" style="height:30px;width:30px;cursor:pointer;" <? if($u->info['admin']>0 || $u->info['id']==1000010){  ?>onClick="testGo('+nwx+','+nwy+')" oncontextmenu="adminion('+nwx+','+nwy+',event); return false;"<? } ?>>'+objst+'</div>';
					<? }else{ ?>
					pix.className = '';
					pix.innerHTML = '';
					<? } ?>
				}
			}
			
			//обновляем обьекты на клетке
			
			j++;
		}
		i++;
	}
	mapNow = mapNew;
	goPix(9,9,true);
}

function takeItem(id)
{
	$.post('../../jx/dungeon.php',{x:xxx,y:yyy,ddid:<?=(int)$_GET['r']?>,id:<? echo time(); ?>,takeItem:id},function(data){$("#rd").html(data);});
}

<?
if($u->info['admin']>0 || $u->info['id']==1000010)
{
?>
function queryAdmin(act)
{
	$.post('../../jx/dungeon.php',{x:xxx,y:yyy,ddid:<?=(int)$_GET['r']?>,id:<? echo time(); ?>,adminion:1,action:act},function(data){$("#rd").html(data);});
}

function adminion(x,y,event)
{
	var ed = document.getElementById('editor');
	if(ed!=undefined)
	{
		var mapArr = mapArray(mapNow);
		var pix = mapArr[''+x+'_'+y+'']; 
		if(pix==undefined)
		{
			pix = new Array;
			pix[0] = 'нет';
			pix[3] = -1;
		}
		document.getElementById('map').style.display = 'none';
		ed.style.display = '';
		ed.innerHTML += '<Br>&nbsp; X: '+x+',Y: '+y+' <a href="#" title="Закрыть редактор" onClick="closeAdminion(); return false;">[x]</a>, ';
		ed.innerHTML += '&nbsp; ID: '+pix[0]+' <a href="#" onClick="queryAdmin(\'delete|$|'+x+'|!|'+y+'\'); return false;">удалить</a><br>';
		var chstyle = '';
		<? $i = 0; while($i<=$stl){ ?>
		if(<? echo $i; ?>==pix[3])
		{
			chstyle += '<div style="width:32px; height:32px; float:left; margin:2px;" class="dpix<? echo $i; ?>"><img title="Уже установленно" src="http://<? echo $c['img']; ?>/good.png"></div>';
		}else{
			chstyle += '<div style="width:32px; height:32px; cursor:pointer; float:left; margin:2px;" onClick="queryAdmin(\'select_image|$|<? echo $i; ?>|!|'+x+'|!|'+y+'\');" class="dpix<? echo $i; ?>"></div>';
		}
		<? $i++; } ?>
		gonbotch1 = '';
		gonbotch2 = '';
		gonbotch3 = '';
		gonbotch4 = '';
		goch1 = '';
		goch2 = '';
		goch3 = '';
		goch4 = '';
		goch5 = '';
		if(pix[5]==1){
			goch1 = 'checked';
		}
		if(pix[6]==1){
			goch2 = 'checked';
		}
		if(pix[7]==1){
			goch3 = 'checked';
		}
		if(pix[8]==1){
			goch4 = 'checked';
		}
		if(pix[9]==1){
			goch5 = 'checked';
		}
		if(pix[10]==1){
			gonbotch1 = 'checked';
		}
		if(pix[11]==1){
			gonbotch2 = 'checked';
		}
		if(pix[12]==1){
			gonbotch3 = 'checked';
		}
		if(pix[13]==1){
			gonbotch4 = 'checked';
		}
		console.log(pix[10]);
		ed.innerHTML += ' '+chstyle+'<br>';
		ed.innerHTML += ' <table><tr><td>Возможные движения:<br><table width="60" border="0" cellspacing="0" cellpadding="0">'+
  '<tr>'+
    '<td width="20" height="20">&nbsp;</td>'+
    '<td><input name="go3" type="checkbox" id="go3" '+goch3+'></td>'+
    '<td>&nbsp;</td>'+
  '</tr>'+
  '<tr>'+
    '<td><input name="go2" type="checkbox" id="go2" '+goch2+'></td>'+
    '<td width="20" height="20"><div align="center">'+
      '<input name="go5" type="checkbox" id="go5" '+goch5+'>'+
    '</div></td>'+
    '<td><input name="go1" type="checkbox" id="go1" '+goch1+'></td>'+
  '</tr>'+
  '<tr>'+
   '<td>&nbsp;</td>'+
    '<td><input name="go4" type="checkbox" id="go4" '+goch4+'></td>'+
   '<td width="20" height="20">&nbsp;</td>'+
  '</tr>'+
'</table></td><td>'+
'Запрет для ботов:<br><table width="60" border="0" cellspacing="0" cellpadding="0">'+
  '<tr>'+
    '<td width="20" height="20">&nbsp;</td>'+
    '<td><input name="nbot3" type="checkbox" id="nbot1" '+gonbotch1+'></td>'+
    '<td>&nbsp;</td>'+
  '</tr>'+
  '<tr>'+
    '<td><input name="nbot2" type="checkbox" id="nbot2" '+gonbotch2+'></td>'+
    '<td width="20" height="20"><div align="center">'+
    '</div></td>'+
    '<td><input name="nbot1" type="checkbox" id="nbot4" '+gonbotch4+'></td>'+
  '</tr>'+
  '<tr>'+
   '<td>&nbsp;</td>'+
    '<td><input name="nbot4" type="checkbox" id="nbot3" '+gonbotch3+'></td>'+
   '<td width="20" height="20">&nbsp;</td>'+
  '</tr>'+
'</table>'+
'</td></tr></table>'+
'<a href="#" onClick="queryAdmin(\'save_go|$|'+x+'|!|'+y+'|!|\'+document.getElementById(\'go1\').checked+\'|!|\'+document.getElementById(\'go2\').checked+\'|!|\'+document.getElementById(\'go3\').checked+\'|!|\'+document.getElementById(\'go4\').checked+\'|!|\'+document.getElementById(\'go5\').checked+\'|!|\'+document.getElementById(\'nbot1\').checked+\'|!|\'+document.getElementById(\'nbot2\').checked+\'|!|\'+document.getElementById(\'nbot3\').checked+\'|!|\'+document.getElementById(\'nbot4\').checked+\'\'); return false;">сохранить возможные движения</a>'; 
	}
}

function closeAdminion()
{
	document.getElementById('map').style.display = '';
	document.getElementById('editor').style.display = 'none';
	document.getElementById('editor').innerHTML = '';
	goPix(9,9,true);
}
<?
}
?>

function goPix(x,y,fast)
{
	clearTimeout(nowGo);
	var m = document.getElementById('dataMap'); 
	var g = document.getElementById('map_'+x+'_'+y); 
	if(m!=undefined && g!=undefined)
	{
		var fs1 = 0;
		var fs2 = 0;
				
		var x1 = tc(m.style.left);
		var x2 = g.offsetLeft;
		var y1 = tc(m.style.top);
		var y2 = g.offsetTop;	
		
		if(fast==true)
		{
			m.style.left = -Math.round(x2)+171-28+'px';
			m.style.top = -Math.round(y2)+122-28+'px';
		}else{
			if(x1 > -Math.round(x2)+171-28)
			{
				m.style.left = tc(m.style.left)-1;
			}else if(x1 < -Math.round(x2)+171-28){
				m.style.left = tc(m.style.left)+1;
			}else{
				fs1 = 1;
			}
			
			if(y1 > -Math.round(y2)+122-28)
			{
				m.style.top = tc(m.style.top)-1+'px';
			}else if(y1 < -Math.round(y2)+122-28){
				m.style.top = tc(m.style.top)+1+'px';
			}else{
				fs2 = 1;
			}
			
			if(fs1==0 || fs2==0)
			{
				nowGo = setTimeout('goPix('+x+','+y+','+fast+')',15);
			}else{
				//делаем смещение
				
			}
		}
	}else{
		er('Ошибка инициализации карты...');
	}
}

</script>

<style>
.newpix {
	
}
.newpix:hover {
	background-color:#E4F3DE;
	border: 1px dotted #00CC00;
}
<?
$i = 0;
while($i<=$stl)
{
?>
.dpix<? echo $i; ?> {
	background-image:url(http://<? echo $c['img']; ?>/dn/dpix<? echo $i; ?>.jpg);
}
.dpix<? echo $i; ?>:hover {
	background-image:url(http://<? echo $c['img']; ?>/dn/dpix<? echo $i; ?>.jpg);
	
}
<? $i++; } ?>
</style>

<table class="tblbr2" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="470" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top">
        <div align="left">
        <div id="users"></div>
        <div align="left"></div>
        <div id="items"></div>
        </div>
        </td>
        <td width="470" height="400" valign="top">
        <div id="rd" style="display:none"></div>
        <div id="error" style="display:none" align="center"></div>
        <div style="position:relative;">
            <div style="position:absolute; z-index:102; width:457px; height:48px; top:275px; left:0px;" align="center">
            	<img src="http://<? echo $c['img']; ?>/dn_btn_ref.png" title="Обновить" onClick="loadDate();" style="cursor:pointer;" /><br />
                Вы сейчас находитесь в &quot;<span id="locName">тестовая локация</span>&quot;.
            </div>
            <div style="position:absolute; z-index:101; width:457px; height:349px; top:0px; left:0px;">            
           		<? if($u->info['admin']>0){ ?> 
                <div id="editor" style="position:relative; display:none; background-color:#F7F7F7; width:323px; height:227px; overflow:scroll; margin-top:60px; margin-left:67px;"></div>
                <? } ?>
            <!-- карта -->
                <div id="map" style="position:relative; width:323px; height:227px; overflow:hidden; margin-top:60px; margin-left:67px;">
                <? $x = 17; $y = 17; 
                  //Первая загрузка карты
				  echo '<table id="dataMap" style="position:absolute; width:'.($x*32-32).'px; height:'.($y*32-32).'px; top:0px; left:0px;" border="0" cellspacing="0" cellpadding="0">';
                  $i = 1;
                  while($i<$y)
                  {
					echo '<tr>';
					$j = 1;
                    while($j<$x)
                    {
						$omap .= '<td align="center" valign="middle" id="obj_'.$j.'_'.(17-$i).'" width="32" height="32"></td>';
						echo '<td align="center" valign="middle" id="map_'.$j.'_'.(17-$i).'" width="32" height="32"></td>';
                        $j++;
                    }
                    echo '</tr>';
                    $i++;
                  }
				  echo '</table>';
                  ?>
                  <div style="position:absolute; width:16px; height:16px; top:98px; left:147px;"><img width="16" height="16" src="http://<? echo $c['img']; ?>/dn/users.png" title="Это Ваш персонаж" /></div>
                </div>
                <!-- -->
            </div>
            <div style="position:absolute; z-index:100; width:457px; height:349px; top:0px; left:0px; background-image: url(http://<? echo $c['img']; ?>/back_dunger_1.png);"></div>
            <div style="position:absolute; z-index:99; width:457px; height:349px; top:0px; left:0px; background-image: url(http://<? echo $c['img']; ?>/back_ground1.gif);"></div>
        </div>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
<script>
loadDate();
</script>
