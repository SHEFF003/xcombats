<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='a_clanreg')
{
	
// класс загрузки файлов 

class upload {
 
protected function __construct() { }
 
static $save_path = 'clan_prw/';
static $error = '';
 
static function saveimg($name,$max_mb = 2,$exts = 'jpg|png|jpeg|gif',$cnm = '') {
    if (isset($_FILES[$name])) {
        $f = &$_FILES[$name];
        if (($f['size'] <= $max_mb*1024*1024) && ($f['size'] > 0)) {
            if (
                (preg_match('/\.('.$exts.')$/i',$f['name'],$ext))&&
                (preg_match('/image/i',$f['type']))
            ) {

				$ext[1] = strtolower($ext[1]);
                $fn = uniqid('f_',true).'.'.$ext[1];
                $fn2 = uniqid('f_',true).'.gif';
                if (move_uploaded_file($f['tmp_name'], self::$save_path . $fn)) {
                    // система изменения размера , требуется Rimage
                    //Rimage::resize(self::$save_path . $fn, self::$save_path . $fn2);
                    //@unlink(self::$save_path . $fn); // удаление файла
                    return array($fn2,$fn,self::$save_path . $fn);
                } else {
                    self::$error = 'Ошибка загрузки файла';
                }
            } else {
                self::$error = 'Неверный тип файла. Допустимые типы : <b>'.$exts.'</b>';
            }
        } else {
            self::$error = 'Неверный размер файла. Максимальный размер файла <b>'.$max_mb.' МБ</b>';
        }
    } else {
        self::$error = 'Файл не найден';
    }
    return false;
} // end saveimg
 
} // end class

$lzv = mysql_fetch_array(mysql_query('SELECT * FROM `_clan` WHERE `uid` = "'.$u->info['id'].'" AND `admin_time` = "0" LIMIT 1'));

/* Регистрация клана */
if(isset($_POST['clan_name'])) {
	/*if($_POST['clan_align'] != 0) {
		$_POST['clan_align'] = 0;
	}*/
	
	$tr_money = 0;
	if($_POST['clan_align'] == 1) {
		$tr_money = 1500;
		$_POST['clan_align'] = 1;
	}elseif($_POST['clan_align'] == 3) {
		$tr_money = 1500;
		$_POST['clan_align'] = 3;
	}elseif($_POST['clan_align'] == 7) {
		$tr_money = 1500;
		$_POST['clan_align'] = 7;
	}else{
		$tr_money = 750;
		$_POST['clan_align'] = 0;
	}
	
	if(true == false) {
		$re = 'Регистрация кланов временно не работает.';
	}elseif(isset($lzv['id'])) {
		$re = 'Вы уже подали заявку на регистрацию клана, ожидайте ответа от администрации';
	}elseif( $u->testAlign($_POST['clan_align'],$u->info['id']) == 0 ) {
		$re = 'Вы не можете зарегистрировать клан с данной склонностью. (Действует ограничение на выбор склонности для вашего персонажа)';
	}elseif($tr_money > $u->info['money']) {
		$re = 'У вас не хватает денег, требуется '.$tr_money.'кр.';
	}elseif($u->info['palpro'] < time() && $u->info['admin'] == 0) {
		$re = 'Вам необходимо пройти проверку у Паладинов/Тарманов';
	}elseif($u->info['clan'] > 0){
		$re = 'Вы состоите в одном из кланов, требуется покинуть его';
	}elseif($u->info['align'] > 0){
		$re = 'Персонажи со склонностью не могут создавать клан';
	}else{
		/* заносим данные в базу */
		$clan_name = substr(htmlspecialchars($_POST['clan_name2'],NULL,'cp1251'), 0, 30);
		$clan_name = str_replace('.','',$clan_name);
		$clan_name = str_replace(' ','',$clan_name);
		$clan_name = str_replace('	','',$clan_name);
		if($file = upload::saveimg('clan_img1',0.3,'gif',$clan_name)) {
			if($file2 = upload::saveimg('clan_img2',0.5,'gif',$clan_name)) {
				if($tr_money < 0) {
					$tr_money = 0;
				}
				$u->info['money'] -= $tr_money;
				
				mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query('INSERT INTO `_clan` (`uid`,`time`,`city`,`name`,`name2`,`site`,`img1`,`img2`,`info`,`money`,`align`) VALUES (
					"'.$u->info['id'].'","'.time().'",
					"'.$u->info['city'].'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_name'],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_name2'],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_site'],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($file[1],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(htmlspecialchars($file2[1],NULL,'cp1251')).'",
					"'.mysql_real_escape_string(str_replace("\r\n",'<br>',htmlspecialchars($_POST['clan_info'],NULL,'cp1251'))).'",
					"'.$tr_money.'",
					"'.mysql_real_escape_string(htmlspecialchars($_POST['clan_align'],NULL,'cp1251')).'"
				)');
				$lzv = array(
					'id' => mysql_insert_id(),
					'name' => htmlspecialchars($_POST['clan_name'],NULL,'cp1251'),
					'time' => time()
				);
				$re = 'Вы успешно подали заявку на регистрацию клана &quot;'.htmlspecialchars($_POST['clan_name'],NULL,'cp1251').'&quot;. ('.$tr_money.'кр.)';
			}else{
				@unlink($file[2]); // удаление файла
				$re = 'Большой значок: '.upload::$error;
			}
		}else{
			$re = 'Маленький значок: '.upload::$error;
		}
	}
}

?>
<style>
body
{
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div>
        <?
		if($re != '') {
			echo '<font style="float:left" color="red"><b>'.$re.'</b></font>';
		}
		?>
    </td>
    <td width="200"><div align="right">
      <table cellspacing="0" cellpadding="0">
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
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="javascript:void(0)" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.11&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.11',1); ?>">Страшилкина улица</a></td>
                            </tr>
                        </table></td>
                      </tr>
                  </table></td>
              </tr>
          </table></td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td>Для регистрации нового клана необходимо иметь:<br />
    <br />
    &bull; <b>Сайт клана</b> (Новостная лента, Устав, Предполагаемый состав клана, Счетчик mail.ru <small>(раздел игры)</small>)
    .<br />&bull; <b>Название, устав и описание клана для энциклопедии</b>, которые не могут содержать: упоминание событий из реального мира, географические названия из реального мира, информацию явно противоречащую правилам проекта (ненормативная лексика, оскорбительные высказывания, пропаганда наркотиков, порно, *расизм, националистические высказывания, дискриминация любого рода [по расовой, религиозной, половой принадлежности и т.д.] и т.д.)
    <br />&bull; <b>Значки</b>: <br /> &nbsp; &nbsp; &nbsp;
    Маленький значок клана (показывается рядом с ником персонажа в чате, на форуме, в бою и т.д.):<br />
    &nbsp; &nbsp; &nbsp; - gif картинка<br />
    &nbsp; &nbsp; &nbsp; - прозрачный фон<br />
    &nbsp; &nbsp; &nbsp; - размер 24x15<br />
    &nbsp; &nbsp; &nbsp; - не более чем 32 цвета<br />
    <br /> &nbsp; &nbsp; &nbsp;
    Большой значок клана (для энциклопедии):<br />
    &nbsp; &nbsp; &nbsp; - gif картинка<br />
    &nbsp; &nbsp; &nbsp; - прозрачный фон<br />
    &nbsp; &nbsp; &nbsp; - размер 100x99<br />
    &nbsp; &nbsp; &nbsp; - композиция должна быть исполнена в круге<br />
    <br /> 
    &bull; Перед подачей заявки, будущий глава клана должен пройти проверку у модераторов (паладинов, тарманов), а так-же покинуть текущий клан.<br />
    <b style="color:red">&bull; Заброшенные клан сайты приведут к автоматическому расформированию клана без компенсации, восстановить кланы будет невозможно. (Следите за сайтами и после успешной регистрации)</b>
    <br />
    <b style="color:red">&bull; При регистрации клана, глава клана в течение 60 дней не имеет право передать своё главенство другому игроку.</b>
    </td>
  </tr>
  <tr>
    <td><form action="main.php?go_psh=1" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <fieldset style="line-height:1.5em;">
        <legend><h3>Заявка</h3></legend>
        <? if(!isset($lzv['id'])) { ?>
        Название клана: <input name="clan_name" type="text" value="" size="50" maxlength="50" />
        <br />
        Английская аббревиатура (только английские буквы, одно слово) <input name="clan_name2" type="text" value="" size="30" maxlength="30" />
        <br />
        Ссылка на официальный сайт клана: <input name="clan_site" type="text" value="http://" size="40" maxlength="80" />
        <br />
        Маленький значок <input type="file" name="clan_img1" id="clan_img1" />
		<br />
        Большой значок <input type="file" name="clan_img2" id="clan_img2" />
        <br />
        Склонность клана 
        <select name="clan_align">
          <option value="0">серый (750 кр.)</option>
          <option value="7">нейтральный (1500 кр.)</option>
          <option value="3">темный (1500 кр.)</option>
          <option value="1">светлый (1500 кр.)</option>
        </select>
        <br />
        Описание клана для энциклопедии:<br />
        <textarea cols="104" name="clan_info" id="clan_info" rows="6"></textarea>
        <br />
        <small style="color:red">В случае отказа в регистрации (по любой причине) возвращается 100% от стоимости клана.<br />
        Администрация в праве отказать в регистрации без объяснения причины.</small>
        <br />
        <input type="submit" name="button" id="button" class="knopka" value="Подать заявку" />
        <? }else{ ?>
        <?=date('d.m.Y H:i',$lzv['time'])?> &nbsp; &nbsp; Вы уже подали заявку на регистрацию клана &quot;<b><?=$lzv['name']?></b>&quot;. Ожидайте ответа от Администрации по почте.
        <? } ?>
      </fieldset>
    </form></td>
  </tr>
</table>
<p>
  <? } ?>
</p>