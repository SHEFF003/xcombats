<?
if(!defined('GAME'))
{
	die();
}

$re = '';
if(isset($u->error2))
{
	$re = $u->error2;
}

function thisInfRm($id,$tp = NULL)
{
	global $u;
	$rm = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `code` = "'.mysql_real_escape_string($id).'" AND `city` = "'.$u->info['city'].'" LIMIT 1'));
	$inf = 'Здание было разрушено';
	if(isset($rm['id']))
	{
		$rown = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `online` > '.(time()-120).' AND `city` = "'.$u->info['city'].'" AND `room` = "'.$rm['id'].'"'));
		if($tp==NULL)
		{
			$inf = '<b>'.$rm['name'].'</b><br>Сейчас в комнате '.(0+$rown[0]).' чел.';
		}else{
			$inf = ''.$rm['name'].'
Сейчас в комнате '.(0+$rown[0]).' чел.';
		}
	}
	if($tp==NULL)
	{
		echo 'onMouseOver="top.hi(this,\'<div align=right>'.$inf.'</div>\',event,0,1,1,1,\'max-height:240px\');" onMouseOut="top.hic();" onMouseDown="top.hic();" onClick="goLocal(\'main.php?loc='.$rm['code'].'\',\''.$rm['name'].'\');"';
	}else{
		echo $inf;
	}
}

if(isset($_GET['loc']))
{
	$go = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `code` = "'.mysql_real_escape_string($_GET['loc']).'" AND `city` = "'.$u->info['city'].'" LIMIT 1'));
							if($u->info['id']=='340379' or $u->info['id']=='399105'){
							//print_r($u->info);
							}
	if($u->info['inTurnir'] > 0)
	{
		$re = 'Вы не можете перемещаться, Вы приняли заявку на турнир ...';
	}elseif($u->aves['now']>=$u->aves['max'] && $u->room['name']!='Общежитие' && $u->room['name']!='Общ. Этаж 1' && $u->room['name']!='Общ. Этаж 2' && $u->room['name']!='Общ. Этаж 3')
	{
		$re = 'Вы не можете перемещаться, рюкзак переполнен ...';
	}elseif($u->room['name']=='Комната для новичков' && $u->info['active']!='')
	{
		echo '<script>alert("Для того чтобы перейти в город Вы должны активировать персонажа. На '.$u->info['mail'].' отправлено письмо с инструкцией по активации.");</script>';
	}elseif(isset($go['id']))
	{
		$rmgo = array();
		$rg = explode(',',$u->room['roomGo']);
		$mlvl = explode('-',$go['level']);
		$i = 0;
		while($i<count($rg))
		{
			if($rg[$i]>=0)
			{
				$rmgo[$rg[$i]] = 1;	
		
			}
			$i++;
		}
		$sleep = $u->testAction('`vars` = "sleep" AND `uid` = "'.$u->info['id'].'" LIMIT 1',1);
		if(isset($sleep['id']) && $sleep['vars']=='sleep')
		{
			$re = 'Вы моежете перемещаться только когда бодрствуете.';
		}elseif($u->info['timeGo']>=time())
		{
			$re = 'Вы не можете перемещаться еще '.($u->info['timeGo']-time()).' сек.';
		}elseif($rmgo[$go['id']]==1 || $u->info['admin']>0)
		{
			if($rmgo[$go['id']]!=1)
			{
				echo '<script>alert("Данный проход не существует, но Ангелам нет преград в перемещении");</script>';
			}
			$alg = explode('-',$go['align']);
			if(($alg[0] > $u->info['align'] || $alg[1] < $u->info['align']) && $go['align']!=0)
			{
				$re = 'Вы не можете попасть в эту комнату';
			}elseif($u->info['zv']>0)
			{
				$re = 'Подали заявку и убегаем?.. Не хорошо!';
			}elseif($go['sex']>0 && $go['sex']-1!=$u->info['sex'])
			{
				$re = 'Вы не можете попасть в эту комнату';
			}elseif($mlvl[0]>$u->info['level'] && $u->info['admin']==0)
			{
				$re = 'Вы не можете попасть в эту комнату, уровень маловат ;)';
			}elseif($mlvl[1]<$u->info['level'] && $u->info['admin']==0)
			{
				$re = 'Вы не можете попасть в эту комнату, уровень высоковат ;)';
			}elseif($go['close']==0 || $u->info['admin']>0)
			{
			$travms = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" and (`v1`="2" or `v1`="3") and `delete`=0 ORDER by v1 DESC'));
                
				//замедление перемешения при травмах
				$plus_timeGo=0; // добавочное время при травме
				$tr_nogo=false; // нету запрещения перемещения по травме
				if($travms['v1']==3){
				// тяжелая травма
				    $kostyls = mysql_query('SELECT * FROM `items_users` WHERE `uid`="'.$u->info['id'].'" and `inOdet`!="0" and (`item_id`="630" or `item_id`="631")');
							if(mysql_num_rows($kostyls)==2){
							$plus_timeGo=30;
							}else{
							$tr_nogo=true;
							}
				
				}elseif($travms['v1']==2){
				//средняя
				$plus_timeGo=20;
				}
				//end freez time go
				
				if($tr_nogo==false){
				$u->info['timeGo'] = time()+$go['timeGO']+$plus_timeGo;
				$u->info['timeGoL'] = time();
				$upd = mysql_query('UPDATE `stats` SET `timeGo` = "'.$u->info['timeGo'].'",`timeGoL` = "'.$u->info['timeGoL'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				}
				if($upd)
				{				
					$upd2 = mysql_query('UPDATE `users` SET `room` = "'.$go['id'].'",`online` = "'.time().'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if($upd2)
					{
						
						$smt = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time`>='.(time()-600).' AND `vars` = "create_snowball_cp" LIMIT 1',1);
						if(isset($smt['id']))
						{
							mysql_query('DELETE FROM `actions` WHERE `id` = "'.$smt['id'].'" LIMIT 1');
						}
						mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id_eff` = "24" AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 24');
						
						$u->info['room'] = $go['id'];
						$u->room = $go;
						echo '<script>top.chat.reflesh();</script>';
					}else{
						$re = 'Вы не смогли перейти в локацию, возможно она была разрушена';
					}
				}elseif($tr_nogo==true){
				$re = 'Вы травмированны. Не возможно с такими увечиями передвигатся без костылей.';
				}else{
					$re = 'Вы не смогли перейти в локацию';
				}
			}elseif($go['destroy']==1)
			{
				$re = 'Здание было разрушено, в данный момент оно реставрируется';
			}else{
				$re = 'Временно закрыто';
			}
		}else{
			$re = 'Проход не существует';
		}
	}else{
		$re = 'Проход не существует';
	}
}

if($u->info['room']==209)
{
	include('_incl_data/class/__zv.php');
}

if($re!=''){ $re = $re.'&nbsp;'; }

?>
<style>
.MoveLine {
	background:url(http://<? echo $c['img']; ?>/i/move/wait2.gif) 0px 0px repeat-y;
}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
var speedLoc  = 0;
var sLoc1 = 0;
var sLoc2 = 0;
var tgo  = 0;
var tgol = 0;
var rgo_url = 0;
var rgo_nm = '';
function locGoLine()
{
	var line = document.getElementById('MoveLine');
	if(line!=undefined)
	{
		prc = 100-Math.floor(tgo/tgol*100);
		sLoc1 = 64/100*prc;
		if(sLoc1<0)
		{
			sLoc1 = 0;
		}
		if(sLoc1>64)
		{
			sLoc1 = 64;
		}
		line.style.width = sLoc1+'px';
		if(tgo>0)
		{			
			tgo -= 1;
			setTimeout('locGoLine()',100);
		}
		
		if(document.getElementById('moveto') != null && document.getElementById('moveto') != undefined) {
			if(rgo_nm != '') {
				document.getElementById('moveto').innerHTML = 'Вы перейдете в: <b>' + nm + '</b> (<a onclick="gotoLocationCancel();" href="javascript:void(0)">отмена</a>)';
			}else{
				document.getElementById('moveto').innerHTML = '';
			}
		}
	}
}
function goLocal(id,nm) {
	rgo_url = id;
	rgo_nm = nm;	
}
</script>
<?
if(date('m') == 12 || date('m') == 1 || date('m') == 2) {
	$rsnow = array(
		234 => 1,
		267 => 1,
		9	=> 1,
		286	=> 1,
		11	=> 1,
		236	=> 1,
		213	=> 1,
		252	=> 1
	);
	if($rsnow[$u->room['id']]==1) {
	?>
<script>
var no = 50; // snow number
var speed = 17; // smaller number moves the snow faster
var sp_rel = 1.4; //speed relevation
var snowflake1 = "/i/itimeges/snow1.gif";
var snowflake2 = "/i/itimeges/snow2.gif";

var i, doc_width, doc_height;

dx = new Array();
xp = new Array();
yp = new Array();
am = new Array();
stx = new Array();
sty = new Array();

Array.prototype.exists = function(el)
{
    for(var i=0;i<this.length;i++)
	if(this[i]==el)
	    return true;
    return false;
}

var rooms = ['1.100', '1.107', '1.111', '1.120'];

function SetVariable(c) {
	dx[c] = 0;                        // set coordinate variables
	am[c] = Math.random()*15;         // set amplitude variables
	xp[c] = Math.random()*(doc_width-35) + 0 + am[c];  // set position variables
	yp[c] = 0;
	stx[c] = 0.02 + Math.random()/10; // set step variables
	sty[c] = 0.7 + Math.random();     // set step variables
}

function DrawWeather(room) {

    doc_width = document.getElementById('img_ione').width;
    doc_height = document.getElementById('img_ione').height;

	var div = '';
	for (i = 0; i < no; ++ i) {
		SetVariable(i);
		div += "<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: 30" + i +"; VISIBILITY: visible; TOP: " + 0 + "px; LEFT: " + 0 + "px;\"><img id=\"im"+ i +"\" src=\"" + (sty[i]<sp_rel ? snowflake2 : snowflake1 ) + "\" border=\"0\" alt=\"Снежинка\"></div>";
	}

	document.getElementById('snow').innerHTML = div;
	return 1;
}

function WeatherBegin() {  // IE main animation function

    for (i = 0; i < no; ++ i) {  // iterate for every dot
        yp[i] += sty[i] < sp_rel ? sty[i]/2 : sty[i];
        if (yp[i] > doc_height-40) {
            SetVariable(i);
            var im = document.getElementById('im'+i);
            im.src = (sty[i] < sp_rel) ? snowflake2 : snowflake1;
        }
        dx[i] += stx[i];
        document.getElementById('dot'+i).style.top = yp[i]+'px';
        document.getElementById('dot'+i).style.left = xp[i] +  am[i]*Math.sin(dx[i])+'px';
    }
    setTimeout('WeatherBegin()', speed);
}


</script>
    <?
	}
}

if(isset($u->room['id']))
{
	$tmGo  = $u->info['timeGo']-time(); //сколько секунд осталось
	$tmGol = $u->info['timeGo']-$u->info['timeGoL']; //сколько секунд идти всего
	if($tmGo<0)
	{
		$tmGo = 0;
	}
	if($tmGol<1)
	{
		$tmGol = 1;
	}
	//онлайн в этой комнате
	$goLis = '<table height="15" border="0" cellspacing="0" cellpadding="0">
               <tr>
               <td rowspan="3" valign="bottom"><a href="main.php?rnd='.$code.'"><img style="display:block;" src="http://'.$c['img'].'/i/move/rel_1.gif" width="15" height="16" title="Обновить" border="0" /></a></td>
               <td colspan="3"><img style="display:block;" src="http://'.$c['img'].'/i/move/navigatin_462s.gif" width="80" height="4" /></td>
               </tr>
               <tr>
               <td><img style="display:block;" src="http://'.$c['img'].'/i/move/navigatin_481.gif" width="9" height="8" /></td>
               		<td width="64" bgcolor="black"><img src="http://'.$c['img'].'/1x1.gif" style="display:block;" id="MoveLine" height="8" class="MoveLine" style="width:33px;" /></td>
               <td><img style="display:block;" src="http://'.$c['img'].'/i/move/navigatin_50.gif" width="7" height="8" /></td>
               </tr>
               <tr>
              		<td colspan="3"><img style="display:block;" src="http://'.$c['img'].'/i/move/navigatin_tt1_532.gif" width="80" height="5" /></td>
               </tr>
               </table>
			   <div id="test"></div><script>var tgo = '.($tmGo*10).'; var tgol = '.($tmGol*10).';locGoLine();</script>';
			   
	$goline = '<div style="position:absolute; top:0px; z-index:101; left:405px; width:80px;">
				'.$goLis.'
               </div>';
	$rowonmax = '';
	$rowonmax2 = 0;
	/*
	$sil = 1;
	while($sil<=count($u->city_unid))
	{
		$rowon = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `online` > '.(time()-520).' AND `city` = "'.$u->city_unid[$sil].'"'));
		$rowon = $rowon[0];
		$rowonmax2 += $rowon;
		$rowonmax .= '<img src="http://'.$c['img'].'/i/city_ico/'.$u->city_unid[$sil].'.gif" width="17" height="15"> '.$u->city_name[$u->city_unid[$sil]].': <b>'.$rowon.'</b> чел., ';	
		$sil++;
	}
	*/
	$rowonmax2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `online` > '.(time()-520).' AND `city` = "'.$u->info['city'].'" LIMIT 200'));
	$rowonmax = 'Сейчас в городе: '.$rowonmax2[0].' чел.';
	$rowonmax =  ''.$rowonmax.'';
	unset($sil,$pil,$rowonmax2);	
	if($u->room['file']!='')
	{
		include_once('modules_data/location/'.$u->room['file'].'.php');
		if((date('m') == 12 || date('m') == 1 || date('m') == 2) && $rsnow[$u->room['id']]==1) {
			echo '<script>DrawWeather(31);WeatherBegin();</script>';
		}
		echo '<div align="right">'.$c['counters'].'</div>';
	}
}else{
	echo 'Location is lost.';
}

?>