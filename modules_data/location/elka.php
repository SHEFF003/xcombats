<?php
if(!defined('GAME'))
{
 die();
}
//id Новогоднего подарка
$pidid = 4008;

$dy = 1;
if((date('n',time())==2 && date('j',time())<=15))
{
	$dy = 0;
}
$dt = date('Y',time())+$dy;
$dt = 2017;

if($u->room['file']=='elka')
{
	if(isset($_GET['take_gift']) && (date('m') == 12 || (date('m') == 1 && date('d') < 11)))
	{
		//получаем свой новогодний подарок за текущий год addItem($id,$uid)
		$smt = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "take_gift_'.$dt.'" LIMIT 1',1);
		if(!isset($smt['id']))
		{
			$pid = $u->addItem($pidid,$u->info['id']);
			if($pid>0)
			{
				mysql_query('UPDATE `items_users` SET `gift` = "Администрация",`gtxt1` = "Поздравляем Вас с Новым Годом!" WHERE `id` = "'.$pid.'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
				$u->addAction(time(),'take_gift_'.$dt.'',$u->info['city']);
				echo '<font color=red>Предмет находится у Вас в инвентаре, в разделе "прочее"</font>';
			}else{
				echo '<font color=red>Не удалось получить подарок...</font>';
			}
		}else{
			echo '<font color=red>Вы уже получили свой подарок ;)</font>';
		}
	}elseif(isset($_GET['del']))
	{
	  if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4))
	  {
		if($u->info['admin']==0)
		{
		  $pInfo = ''.$u->info['align'].'|'.$u->info['clan'].'|'.$u->info['login'].'|'.$u->info['level'].'|'.$u->info['cityreg'].'';
		}else{
		  $pInfo = '1';
		}
		mysql_query("UPDATE `elka` SET `delete`='".$pInfo."' WHERE `id`='".mysql_real_escape_string($_GET['del'])."'");
	  }
	}elseif(isset($_GET['use_cup']))
	{
		$smt = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.(time()-600).' AND `vars` = "use_cupNewYear" LIMIT 1',1);
		if(!isset($smt['id']))
		{
			$u->addAction(time(),'use_cupNewYear','');
			mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpAll'].'",`mpNow` = "'.$u->stats['mpAll'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			echo '<font color=red>Успешно использован эликсир "Полное восстановление"</font>';
		}
	}elseif(isset($_POST['message']))
	{
	  $_POST['message'] = htmlspecialchars($_POST['message'],NULL,'cp1251');
	  if($_POST['message']!='')
	  {
	   $dy = 1;
	   if((date('n',time())==1 && date('j',time())<=15))
	   {
		 $dy = 0;
	   }
	   $u->info['ET'] = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.(time()-600).' AND `vars` = "send_elka" LIMIT 1',1);
	   if(isset($u->info['ET']['id']))
	   {
		 echo '<font color=red>Оставлять надписи на стволе ёлки можно не чаще одного раза в 10 минут</font>';
	   }else{
			$pInfo = ''.$u->info['align'].'|'.$u->info['clan'].'|'.$u->info['login'].'|'.$u->info['level'].'|'.$u->info['cityreg'].'|'.$u->info['id'].'';
			mysql_query("INSERT INTO `elka` (`year`,`time`,`pers`,`text`,`city`) VALUES (".(date('Y',time())+$dy).",".time().",'".$pInfo."','".mysql_real_escape_string($_POST['message'])."','".$u->info['city']."'); ");
			$u->addAction(time(),'send_elka','');
		}
	  }
	}
	
	?>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}
	
	</style>
    <div align="right"><? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?></div>
	<div id="hint3" style="visibility:hidden"></div>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3">Новогодняя елка <?
	 echo $dt; ?>!</div>
	<br />
	<!-- Подарки -->
	<?
	$sg = 1;
	//Если есть подарки		
	if((date('n',time())==12 || date('n',time())<=2) && $sg==1)
	{
	?>
    <div style="padding-left:10px;">
	<span class="pH3"><small>Подарки:</small></span>
    <div>
    <?
    $smt = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.(time()-600).' AND `vars` = "use_cupNewYear" LIMIT 1',1);
	?>
    <a href="?use_cup=<? echo $code; ?>" <? if(isset($smt['id'])){ echo 'onClick="alert(\'Использовать Новогодний кубок можно не чаще одного раза в 10 минут\');return false;"'; } ?> /><img src="http://img.xcombats.com/cup2012.gif" style="padding:10px;<? if(isset($smt['id'])){ echo 'filter: alpha(opacity=35); -moz-opacity: 0.35; -khtml-opacity: 0.35; opacity: 0.35;'; } ?>" title="Выпить из`Новогодний кубок`"></a>
    <? 
	if( date('m') == 12 || (date('m') == 1 && date('d') < 11) ) {
	$pd = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.(time()-600).' AND `vars` = "take_gift'.$dt.'" LIMIT 1',1);
	?>
	<a href="?take_gift=<? echo $code; ?>" <? if(isset($pd['id'])){ echo 'onClick="return false;"'; } ?> /><img src="http://img.xcombats.com/i/items/<? echo 'podarok2014'; ?>.gif" style="padding:10px;<? if(isset($pd['id'])){ echo 'filter: alpha(opacity=35); -moz-opacity: 0.35; -khtml-opacity: 0.35; opacity: 0.35;'; } ?>" title="Взять `Новогодний подарок <? echo $dt; ?>`"></a>
    <? } ?>
    </div>
	</div>
    <hr>
	<?
	}
	
	if(isset($_GET['page']))
	{
	 $fpage = round($_GET['page']);
	 if($fpage<=0)
	 {
	   $fpage = 1;
	 }
	}else{
	 $fpage = 1;
	}
	$limit1 = ($fpage-1)*20+$fpage-1;
	$limit2 = 21;
	
			  $i = mysql_fetch_array(mysql_query('SELECT COUNT(`year`) FROM `elka` WHERE `year` = "'.$dt.'" AND (`delete` = "0" OR '.$u->info['admin'].' > 0) ORDER BY `id` DESC'));
			  $i = $i[0];
			  $d = ceil($i/21);
			  if($i>0)
			  {
			   if($d<13)
			   {
				$j=0;
				$pagesN = '';
				while($i>=0)
				{
				  $i -= 21;
				  if($i!=0)
				  {			  
				   $j++;
				   $r2 = '';
				   if($j<=$d)
				   {
					if(isset($r))
					{
					  $r2 = '&r='.$r;
					}
					$jt = $j;
					if($fpage==$j)
					{
					 $jt = '<span class="number">'.$j.'</span>';
					}
					$pagesN .= ' <a href="?id='.$post['id'].'&d='.$_GET['d'].'&page='.$j.'" title="Перейти на страницу №'.$j.'">'.$jt.'</a> ';
				   }
				  }
				}
				$pages .= ' '.$pagesN.' ';
			   }else{
				$j = $fpage-6;
				$i = 0;
				$pagesN = '';
				while($k<13)
				{
				 if($j>0)
				 {
				  if($j<=$d)
				  {
				   $jt = $j;
				   if($fpage==$j)
				   {
					 $jt = '<span class="number">'.$j.'</span>';
				   }
				   $pagesN .= ' <a href="?id='.$post['id'].'&d='.$_GET['d'].'&page='.$j.'" title="Перейти на страницу №'.$j.'">'.$jt.'</a> ';
				  }
				  $k++;
				 }
				 $j++;
				}
				$prpage = $fpage-12;
				$nxpage = $fpage+12;
				if($prpage<=0)
				{
				  $prpage = 1;
				}
				if($nxpage>$d)
				{
				  $nxpage = $d;
				}
				$_GET['d'] = (int)$_GET['d'];
				if($fpage-7>0)
				{
				 $pages .= '<a href="?id='.$post['id'].'&d='.$_GET['d'].'&page=1" title="Первая страница">«</a> <a href="?id='.$post['id'].'&d='.$_GET['d'].'&page='.$prpage.'" title="Показать предыдущие страницы">...</a> ';
				}
				$pages .= ' '.$pagesN.' ';
				if($fpage<$d-5)
				{
				 $pages .= '<a href="?id='.$post['id'].'&d='.$_GET['d'].'&page='.$nxpage.'" title="Показать следующие страницы">...</a> <a href="?id='.$post['id'].'&d='.$_GET['d'].'&page='.$d.'" title="Последняя страница">»</a>';
				}
			   }		   
			  }else{
				$pages = '';
			  }
	?>
	<U>Посетители оставили надписи на стволе елки:</U> <? echo $pages; ?><br>
    <div style="padding:5px;">
	<?
	$sp = mysql_query('SELECT * FROM `elka` WHERE `year`="'.$dt.'" AND `city`="'.$u->info['city'].'" AND (`delete` = "0" OR '.$u->info['admin'].' > 0) ORDER BY `time` DESC LIMIT '.$limit1.','.$limit2.'');
	$page = floor((int)$_POST['page']);
	if($page<1){ $page = 1; }elseif($page>300){ $page==300; }
	while($pl = mysql_fetch_array($sp))
	{
	  $prs = explode('|',$pl['pers']); $pers = '';
	  if($prs[0]!=0)
	  {
		$pers .= '<img src="http://img.xcombats.com/i/align/align'.$prs[0].'.gif">';
	  }
	  if($prs[1]!=0)
	  {
		$clanPrs = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id`="'.$prs[1].'" LIMIT 1'));
		$pers .= '<img src="http://img.xcombats.com/i/clan/'.$clanPrs['name_mini'].'.gif">';
	  }
	  $pers .= '<b>'.$prs[2].'</b>['.$prs[3].']<a href="http://xcombats.com/info/'.$prs[5].'" title="Инф. о '.$prs[2].'" target="blank"><img src="http://img.xcombats.com/i/inf_'.$prs[4].'.gif"></a>';
	  if($pl['delete']!='0')
	  {
		if($pl['delete']=='1')
		{
		  if($u->info['admin']>0)
		  {
			$pl['text'] = '<font color=red><i>Сообщение стерто</i></font> <font color=grey><small>('.$pl['text'].')</small></font>';
		  }else{
		   $pl['text'] = '<font color=red><i>Сообщение стерто</i></font>';
		  }
		}else{
	  $prs = explode('|',$pl['delete']); $pers2 = '';
	  if($prs[0]!=0)
	  {
		$pers2 .= '<img src="http://img.xcombats.com/i/align/align'.$prs[0].'.gif">';
	  }
	  if($prs[1]!=0)
	  {
		$clanPrs = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id`="'.$prs[1].'" LIMIT 1'));
		$pers2 .= '<img src="http://img.xcombats.com/i/clan/'.$clanPrs['img'].'.gif">';
	  }
	  $pers2 .= '<a href="javascript:top.toUser(\''.$prs[2].'\',\'private\');"><b>'.$prs[2].'</b></a>['.$prs[3].']<a href="http://xcombats.com/info/'.$prs[2].'" title="Инф. о '.$prs[2].'" target="blank"><img src="http://img.xcombats.com/i/inf_'.$prs[4].'.gif"></a>';
	  
		  if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4))
		  {
			$pl['text'] = '<i><font color=red>Сообщение стерто персонажем</font> '.$pers2.'</i> <font color=grey><small>('.$pl['text'].')</small></font>';
		  }else{
		   $pl['text'] = '<i><font color=red>Сообщение стерто персонажем</font> '.$pers2.'</i>';
		  }
		}
	  }
	  if(($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)) && $pl['delete']=='0')
	  {
		$dl = ' <a href="main.php?page='.$_POST['page'].'&del='.$pl['id'].'"><small>Стереть</small></a>';
	  }else{
		$dl = '';
	  }
	  echo '<font class=date>'.date('d.m.Y H:i',$pl['time']).'</font> '.$pers.' - '.$pl['text'].''.$dl.'<BR>';
	}
	?>
    </div>
	Страницы: <? echo $pages; ?><br>
	<FORM method="post" action="main.php">
	 Оставить сообщение: <INPUT type=text name=message maxlength=150 size=50>&nbsp;<INPUT type=submit name=addmessage value='Добавить'>
	</FORM>
	  <td width="280" valign="top"><table cellspacing="0" cellpadding="0">
		<tr>
		  <td width="100%">&nbsp;</td>
		  <td><table  border="0" cellpadding="0" cellspacing="0">
			  <tr align="right" valign="top">
				<td><!-- -->
					<? echo $goLis; ?>
					<!-- -->
					<table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
							<tr>
							  <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
							  <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&amp;rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">Центральная Площадь</a></td>
							</tr>
						</table></td>
					  </tr>					  <tr>
						<td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
							<tr>
							  <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
							  <td bgcolor="#D3D3D3" nowrap="nowrap"><div align="left"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.209&amp;rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.209',1); ?>">Ледяная пещера</a></div></td>
							</tr>
						</table></td>
					  </tr>
				  </table></td>
			  </tr>
		  </table></td>
		</tr>
	  </table>
		<br />
	<center></center></td>
	</table>
	<div id="textgo" style="visibility:hidden;"></div>						  
<?
}
?>