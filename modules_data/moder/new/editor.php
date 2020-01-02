<?	if(isset($_POST['q_name']))
		{
			$qd = array();
			/* Array ([q_act_atr_1] => 0 [q_act_val_1] => [q_tr_atr_1] => 0 [q_tr_val_1] => [q_ng_atr_1] => 0 [q_ng_val_1] => [q_nk_atr_NaN] => 0
			[q_nk_val_NaN] => [q_info] => test описание [q_line1] => 1 [q_line2] => 1 [q_fast] => 1 [q_fast_city] => capitalcity [q_align1] => 1 [q_align2] => 1 [q_align3] => 1 ) */
			$qd['name'] = $_POST['q_name'];
			$qd['lvl'] = explode('-',$_POST['q_lvl']);
			$qd['info'] = $_POST['q_info'];
			if($_POST['q_line1']==1)
			{
				$qd['line'] = $_POST['q_line2'];
			}
			if($_POST['q_fast']==1)
			{
				$qd['city'] = $_POST['q_fast_city'];
				$gd['fast'] = 1;
			}
			if($_POST['align1']==1)
			{
				$qd['align'] = 1;
			}elseif($_POST['align2']==1)
			{
				$qd['align'] = 3;
			}elseif($_POST['align3']==1)
			{
				$qd['align'] = 7;
			}elseif($_POST['align4']==1)
			{
				$qd['align'] = 2;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_act_atr_'.$i]))
				{
					if($_POST['q_act_val_'.$i]!='')
					{
						$qd['act_date'] .= $_POST['q_act_atr_'.$i].':=:'.$_POST['q_act_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['act_date'] = trim($qd['act_date'],':|:');
				}
				$i++;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_tr_atr_'.$i]))
				{
					if($_POST['q_tr_val_'.$i]!='')
					{
						$qd['tr_date'] .= $_POST['q_tr_atr_'.$i].':=:'.$_POST['q_tr_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['tr_date'] = trim($qd['tr_date'],':|:');
				}
				$i++;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_ng_atr_'.$i]))
				{
					if($_POST['q_ng_val_'.$i]!='')
					{
						$qd['win_date'] .= $_POST['q_ng_atr_'.$i].':=:'.$_POST['q_ng_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['win_date'] = trim($qd['win_date'],':|:');
				}
				$i++;
			}
			$i = 1;
			while($i!=-1)
			{
				if(isset($_POST['q_nk_atr_'.$i]))
				{
					if($_POST['q_nk_val_'.$i]!='')
					{
						$qd['lose_date'] .= $_POST['q_nk_atr_'.$i].':=:'.$_POST['q_nk_val_'.$i].':|:';
					}
				}else{
					$i = -2;
					$qd['lose_date'] = trim($qd['lose_date'],':|:');
				}
				$i++;
			}
			mysql_query('INSERT INTO `quests` (`name`,`min_lvl`,`max_lvl`,`tr_date`,`act_date`,`win_date`,`lose_date`,`info`,`line`,`align`,`city`,`fast`) VALUES (
			"'.mysql_real_escape_string($qd['name']).'","'.mysql_real_escape_string($qd['lvl'][0]).'","'.mysql_real_escape_string($qd['lvl'][1]).'",
			"'.mysql_real_escape_string($qd['tr_date']).'","'.mysql_real_escape_string($qd['act_date']).'","'.mysql_real_escape_string($qd['win_date']).'",
			"'.mysql_real_escape_string($qd['lose_date']).'","'.mysql_real_escape_string($qd['info']).'","'.mysql_real_escape_string($qd['line']).'",
			"'.mysql_real_escape_string($qd['align']).'","'.mysql_real_escape_string($qd['city']).'","'.mysql_real_escape_string($qd['fast']).'")');
		}
?>
<script>
function nqst(){ if(document.getElementById('addNewquest').style.display == ''){ document.getElementById('addNewquest').style.display = 'none'; }else{ document.getElementById('addNewquest').style.display = ''; } }
var adds = [0,0,0,0];
function addqact()
{
	var dd = document.getElementById('qact');
	adds[0]++;
	dd.innerHTML = 'Атрибут: <select name="q_act_atr_'+adds[0]+'" id="q_act_atr_'+adds[0]+'">'+
  '<option value="0"></option>'+
  '<option value="go_loc">перейти в локацию</option>'+
  '<option value="go_mod">перейти в модуль</option>'+
  '<option value="on_itm">одеть предмет</option>'+
  '<option value="un_itm">снять предмет</option>'+
  '<option value="use_itm">использовать предмет</option>'+
  '<option value="useon_itm">использовать предмет на</option>'+
  '<option value="dlg_nps">поговорить с NPS</option>'+
  '<option value="tk_itm">получить предмет</option>'+
  '<option value="del_itm">выкинуть предмет</option>'+
  '<option value="buy_itm">купить предмет</option>'+
  '<option value="kill_bot">убить монстра</option>'+
  '<option value="kill_you">убить клона</option>'+
  '<option value="kill_user">убить игрока</option>'+
  '<option value="all_stats">раставить статы</option>'+
  '<option value="all_skills">раставить умения</option>'+
  '<option value="all_navik">расставить навыки</option>'+
  '<option value="min_online">пробыть минут в онлайне</option>'+
  '<option value="min_btl">провести боев</option>'+
  '<option value="min_winbtl">провести боев (побед)</option>'+
  '<option value="tk_znak">получить значок</option>'+
  '<option value="end_quests">завершить квест</option>'+
  '<option value="end_qtime">время выполнения квеста (в минутах)</option>'+
'</select>, значение: <input style="width:100px" name="q_act_val_'+adds[0]+'" value=""><br>'+dd.innerHTML;
}
function addqtr()
{
	var dd = document.getElementById('qtr');
	adds[1]++;
	dd.innerHTML = 'Атрибут: <select name="q_tr_atr_'+adds[1]+'" id="q_tr_atr_'+adds[1]+'">'+
  '<option value="0"></option>'+
  '<option value="tr_endq">Завершить квесты</option>'+
  '<option value="tr_botitm">Из монстров падают предметы (в пещерах)</option>'+
  '<option value="tr_winitm">После победы падают предметы</option>'+
  '<option value="tr_zdr">Задержка между выполнением (в часах)</option>'+
  '<option value="tr_tm1">Переодичность квеста (начало)</option>'+
  '<option value="tr_tm2">Переодичность квеста (конец)</option>'+
  '<option value="tr_raz">Сколько раз можно проходить квест</option>'+
  '<option value="tr_raz2">Сколько попыток пройти квест</option>'+
  '<option value="tr_dn">Нахождение в пещере</option>'+
  '<option value="tr_x">Нахождение в координате X</option>'+
  '<option value="tr_y">Нахождение в координате Y</option>'+
'</select>, значение: <input style="width:100px" name="q_tr_val_'+adds[1]+'" value=""><br>'+dd.innerHTML;
}
function addqng()
{
	var dd = document.getElementById('qng');
	adds[2]++;
	dd.innerHTML = 'Атрибут: <select name="q_ng_atr_'+adds[2]+'" id="q_ng_atr_'+adds[2]+'">'+
  '<option value="0"></option>'+
  '<option value="add_cr">Добавить Кредиты</option>'+
  '<option value="add_ecr">Добавить Екредиты</option>'+
  '<option value="add_itm">Добавить предмет</option>'+
  '<option value="add_eff">Добавить эффект</option>'+
  '<option value="add_rep">Добавить репутации</option>'+
  '<option value="add_exp">Добавить опыта</option>'+
'</select>, значение: <input style="width:100px" name="q_ng_val_'+adds[2]+'" value=""><br>'+dd.innerHTML;
}
function addqnk()
{
	var dd = document.getElementById('qnk');
	adds[3]++;
	dd.innerHTML = 'Атрибут: <select name="q_nk_atr_'+adds[3]+'" id="q_nk_atr_'+adds[3]+'">'+
  '<option value="0"></option>'+
  '<option value="lst_eff">Добавить эффект</option>'+
'</select>, значение: <input style="width:100px" name="q_nk_val_'+adds[3]+'" value=""><br>'+dd.innerHTML;
}
</script>
<!-- Copyright 2000-2006 Adobe Macromedia Software LLC and its licensors. All rights reserved. -->
<title>Текстовое поле</title>

<table width="100%">
  <tr>
    <td align="center"><h3>Редактор заданий</h3></td>
    <td width="150" align="right"><input type="button" value="&gt;" onclick="location='main.php?<? echo $zv; ?>';" />
      <? if($u->info['admin']>0){ ?>
      <input type="button" value="<? if($a==1){ echo 'PAL'; }else{ echo 'ARM'; } ?>" onclick="location='main.php?go=2&amp;<? echo $zv; ?>&amp;remod=<? echo $a; ?>';" />
      <? } ?>
      <? if($p['trPass']!=''){ ?>
      <input type="button" value="X" title="Закрыть доступ" onclick="location='main.php?<? echo $zv.'&rnd='.$code; ?>&amp;exitMod=1';" />
      <? } ?></td>
  </tr>
  <tr>
    <td>
    <form method="post" action="main.php?go=2&amp;<? echo $zv; ?>&amp;remod=<? echo $a; ?>">
    <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
      <!-- -->
      <tr>
        <td style="border-bottom:1px solid #CCCCCC;"><div align="left" style="margin-left:11px;">
        	<a href="javascript:void(0)" onclick="nqst()">Добавить новое задание</a>
        </div>
          <div align="left"></div></td>
        </tr>
      <tr id="addNewquest" style="display:none;">
        <td bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><b>Панель добавления новых заданий:</b><br />
          <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td width="200" valign="top">Название задания</td>
              <td><input name="q_name" id="q_name" value="" size="60" maxlength="50"  /></td>
            </tr>
            <tr>
              <td valign="top">Уровень задания</td>
              <td><input name="q_lvl" id="q_lvl" value="0-21" size="10" maxlength="5"  /></td>
            </tr>
            <tr>
              <td valign="top">Действия</td>
              <td valign="top" id="qact"><a href="javascript:void(0)" onclick="addqact()"><small>[+] добавить</small></a></td>
            </tr>
            <tr>
              <td valign="top">Условия</td>
              <td valign="top" id="qtr"><a href="javascript:void(0)" onclick="addqtr()"><small>[+] добавить</small></a></td>
            </tr>
            <tr>
              <td valign="top">Награда</td>
              <td valign="top" id="qng"><a href="javascript:void(0)" onclick="addqng()"><small>[+] добавить</small></a></td>
            </tr>
            <tr>
              <td valign="top">Неудача</td>
              <td valign="top" id="qnk"><a href="javascript:void(0)" onclick="addqnk()"><small>[+] добавить</small></a></td>
            </tr>
            <tr>
              <td valign="top">Описание задания</td>
              <td><textarea name="q_info" id="q_info" style="width:90%" rows="7"></textarea></td>
            </tr>
            <tr>
              <td align="center" valign="top" bgcolor="#CBCBCB"><input name="q_line1" type="checkbox" id="checkbox3" value="1" />
                Линейное задание</td>
              <td bgcolor="#CBCBCB"><input name="q_line2" id="q_line3" value="" size="5" maxlength="3"  />
                , id линейного сюжета</td>
            </tr>
            <tr>
              <td align="center" valign="top" bgcolor="#CBCBCB"><input name="q_fast" type="checkbox" id="q_fast" value="1" />
                Быстрое задание&nbsp;</td>
              <td bgcolor="#CBCBCB"><input name="q_fast_city" id="q_fast_city" value="capitalcity" size="50" maxlength="50"  />
                , город которым ограничен квест <small>(стереть, если не ограничен)</small></td>
            </tr>
            <tr>
              <td align="center" valign="top" bgcolor="#CBCBCB">
              <small>
              <input name="q_align1" type="checkbox" id="q_align1" value="1" /> 
                Свет,
                
                <input name="q_align2" type="checkbox" id="q_align2" value="1" />
                Тьма,<br /> 
                <input name="q_align3" type="checkbox" id="q_align3" value="1" /> 
                Нейтрал,
                <input name="q_align4" type="checkbox" id="q_align4" value="1" /> 
                Хаос
              </small>
</td>
              <td bgcolor="#CBCBCB"><input type="submit" value="Добавить задание" /></td>
            </tr>
          </table></td>
      </tr>
      <!-- -->
    </table>
    </form>
    <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
      <!-- -->
      <?
	  if(isset($_GET['delq']))
	  {
		 mysql_query('UPDATE `quests` SET `delete` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($_GET['delq']).'" LIMIT 1'); 
	  }
	  $sp = mysql_query('SELECT * FROM `quests` WHERE `delete` = 0');
	  while($pl = mysql_fetch_array($sp))
	  {
	  ?>
      <tr>
        <td style="border-bottom:1px solid #CCCCCC;" width="300"><div align="left" style="margin-left:11px;"><?=$pl['name']?></div>
          <div align="left"></div></td>
        <td width="75" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><div align="center"><a href="main.php?go=2&amp;delq=<? echo $pl['id'].'&'.$zv; ?>">удалить</a></div></td>
        <td style="border-bottom:1px solid #CCCCCC;"><small><b>Описание:</b> <?=$pl['info']?></small></td>
      </tr>
      <? } ?>
      <!-- -->
  </table>
    </td>
  </tr>
</table>