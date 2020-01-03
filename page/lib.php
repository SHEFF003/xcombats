<?php
if( !isset($url[2]) || $url[2] == '' ) {
	$url[2] = 'home';
}
?>
    <table style="margin-left:9px;" width="830" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="270" valign="top">
        <!-- -->
        <div class="reting_div">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <div class="lib_up">&nbsp;</div>
            </td>
            </tr>
          <tr>
            <td class="reting_bg">
            <div class="lib-menu">
              <?
				$sp = mysql_query('SELECT * FROM `library_menu` WHERE `delete` = 0 ORDER BY `position` ASC');
				while($pl = mysql_fetch_array($sp)) {
					$pl['url'] = str_replace('library','lib',$pl['url']);
					if( $pl['type'] == 0 ) {
						$lib['html'] .= '<span class="lib-title">'.$pl['name'].'</span>';
						$lib['px'] += 33;
					}else{
						$lib['html'] .= '<a href="http://xcombats.com'.$pl['url'].'" class="lib-rgo">&bull; '.$pl['name'].'</a>';
						$lib['px'] += 23;
					}
				}
				echo $lib['html'];
			  ?>
            </div>
            </td>
          </tr>
          <tr>
            <td class="reting_footer">&nbsp;</td>
          </tr>
        </table>
        </div>
        <!-- -->
        </td>
        <td align="left" valign="top">
        <!-- -->
        <div class="lib-main">
		<?
		if( $url[2] == 'upload' ) {
			
			$html = '';
			
			if( $u->info['activ'] == 1 ) {
				$html = 'Чтобы начать публиковать изображения - Активируйте Вашего персонажа.';
			}elseif( $u->info['molch1'] > time() ) {
				$html = 'Персонажи с молчанкой не могут публиковать изображения.';
			}elseif( $u->info['banned'] > 0 ) {
				$html = 'Заблокированные персонажи не могут публиковать изображения.';
			}elseif( $u->info['align'] == 2 ) {
				$html = 'Хаосники не могут публиковать изображения.';
			}elseif( !isset($u->info['id']) ) {
				$html = '<center><br>Загружать изображения могут только зарегистрированные пользователи</center>';
			}elseif( ($url[3] == 'me' || ($url[3] == 'all' && $u->info['admin'] > 0)) ) {
				if( $url[3] == 'me' ) {
					$sp = mysql_query('SELECT * FROM `upload_images` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1000');
				}elseif( $url[3] == 'all' ) {
					$sp = mysql_query('SELECT * FROM `upload_images` ORDER BY `id` DESC LIMIT 1000');
				}
				$html .= '<b>Левая Кнопка Мыши</b> - Открыть изображение в новом окне<br>
						  <b>Правая Кнопка Маши</b> - Удалить изображение с сервера<hr>';
				$i = 0;
				$usrs = array();
				while($pl = mysql_fetch_array($sp)) {
					if( $url[4] == 'delete' && $url[5] == $pl['id'] ) {
						unlink('ui/'.$pl['img'].'.'.$pl['type'].'');
						mysql_query('DELETE FROM `upload_images` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					}else{
						if( !isset($usrs[$pl['uid']]) ) {
							$usrs[$pl['uid']] = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
							if(!isset($usrs[$pl['uid']]['id'])) {
								$usrs[$pl['uid']]['login'] = '!НЕТ АВТОРА!';
							}
							$usrs[$pl['uid']] = $usrs[$pl['uid']]['login'];
						}
						$html .= '<a title="'.$usrs[$pl['uid']]."\n".date('d.m.Y H:i',$pl['time']).'" oncontextmenu="if(confirm(\'Вы уверены?\')){ top.location=\'http://xcombats.com/lib/upload/'.htmlspecialchars($url[3],NULL,'cp1251').'/delete/'.$pl['id'].'/\'; }return false;" target="_blank" href="http://xcombats.com/ui/'.$pl['img'].'.'.$pl['type'].'"><img src="http://xcombats.com/ui/'.$pl['img'].'.'.$pl['type'].'" class="imgo"></a>';
					}
					$i++;
				}
				if( $i == 0 ) {
					$html .= 'Нет загруженных изображений на сервере';
				}
			}else{
				
				 if( isset($_FILES['filename']) ) {
					 include('html/class.upload.php');
					 $handle = new upload($_FILES['filename']);
					 $count = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.(time()-60).' LIMIT 1'));
					 if( $count > 3 && $u->info['admin'] == 0 ) {
						 $html = 'Вы не можете так часто заливать изображения на сервер';
					 }elseif ($handle->uploaded) {
						
						if( $handle->file_src_name_ext == 'png' || $handle->file_src_name_ext == 'jpg' || $handle->file_src_name_ext == 'gif' ) {
							$fname = 'u'.$u->info['id'].'_'.time();	
							$handle->file_new_name_body = $fname;	
							
							$handle->image_convert         = $handle->file_src_name_ext;
							
							/*
							$handle->image_unsharp         = true;
							$handle->image_border          = '0 0 0 0';
							$handle->image_border_color    = '#000000';
							$handle->image_text            = "";
							$handle->image_text_font       = 2;
							$handle->image_text_position   = 'B';
							$handle->image_text_padding_y  = 2;
							*/
							
							if( $u->info['admin'] == 0 ) {
								$handle->image_max_width  = 1800;
								$handle->image_max_height = 1800;
								$handle->src_size_mb 	  = 5;
							}
									
							$handle->process('ui/');				
							if ($handle->processed) 
							{
								mysql_query('INSERT INTO `upload_images` (`uid`,`time`,`img`,`type`) VALUES (
									"'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($fname).'","'.mysql_real_escape_string($handle->file_src_name_ext).'"
								) ');
								$html .= 'Файл <a href="http://xcombats.com/ui/'.$fname.'.'.$handle->file_src_name_ext.'" target="_blank">http://xcombats.com/ui/'.$fname.'.'.$handle->file_src_name_ext.'</a> был успешно загружен.';
		
								$handle->clean();
							} else {
								$html .= 'Возникла ошибка при добавлении файла.';
							}
						}else{
							$html .= 'Возникла ошибка при добавлении файла!';
						}
					}
				 }else{		
					 $html .= '
					  <br>
					  <b>Правила публикации изображений:</b><br>
					  &bull; Изображение не должно нарушать правила проекта<br>
					  &bull; Изображение должно весить не более 1000 Кб<br>
					  &bull; Изображение не должно быть более 800px х 800px<br>
					  &bull; Форматы изображения: JPEG , GIF , PNG<hr>
					  <center>
					  <form action="http://xcombats.com/lib/upload/" method="post" enctype="multipart/form-data">
					  <input type="file" name="filename"> 
					  <input class="btn2" type="submit" value="Загрузить"><hr>';
					  
					  if( $u->info['admin'] > 0 ) {
						 $html .= '<a href="http://xcombats.com/lib/upload/all/">[ Все изображения ]</a> ';
					  }
					  $html .= '<a href="http://xcombats.com/lib/upload/me/">[ Мои изображения ]</a>';
					  
					  $html .= '</form>
					  </center>';
				 }
				
			}
			echo '<div style="padding-left:20px;padding-top:20px;"><h3>Загрузка изображений</h3>'.$html.'</div>';
		}elseif( $url[2] == 'list' ) {
			$sp = mysql_query('SELECT * FROM `library_content` WHERE `delete` = 0 AND `moder` = 0 AND `uid` > 0 ORDER BY `id` ASC');
			$html = '';
			$i = 1;
			while($pl = mysql_fetch_array($sp)) {
				$html .= '<a target="_blank" href="http://xcombats.com/lib/'.$pl['url_name'].'/">&gt;&gt; '.$pl['title'].'</a><br>Автор: '.$u->microLogin($pl['uid'],1).' / Дата публикации: '.date('d.m.Y',$pl['time']).'<hr>';
				$i++;
			}
			if( $html == '' ) {
				$html = 'В настоящий момент непроверенных статей нет.<br>
				<br>Если Вы хотите написать свою статью - <a target="_blank" href="http://xcombats.com/lib/new/">http://xcombats.com/lib/new/</a><br>
				<br>Более подробная информация - <a href="http://xcombats.com/lib/public/">http://xcombats.com/lib/public/</a>';
			}
			echo '<div style="padding-left:20px;padding-top:20px;"><h3>Список непроверенных статей:</h3>'.$html.'</div>';
		}elseif( $url[2] == 'new' && !isset($u->info['id']) ) {
			echo '<div style="padding:50px;">Для публикации статьи Вы должны авторизироваться своим персонажем.<br><b>Гостям</b> данный раздел недоступен.</div>';
		}elseif( $url[2] == 'new' && isset($u->info['id']) ) {
		?>
		<!-- -->
		<script src="/inx/ckeditor/ckeditor.js"></script>
		<!-- -->
		<div class="lib-txt-title">Публикация статьи</div>
		<div class="lib-txt">
		<?
		if(isset($_POST['save']) && isset($u->info['id'])) {
			$_POST['lib_title'] = htmlspecialchars($_POST['lib_title'],NULL,'cp1251');
			$mbpage_last = mysql_fetch_array(mysql_query('SELECT `time` FROM `library_content` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
			
			if( $u->info['activ'] == 1 ) {
				echo 'Чтобы начать публиковать статьи - Активируйте Вашего персонажа.';
			}elseif( $u->info['molch1'] > time() ) {
				echo 'Персонажи с молчанкой не могут публиковать статьи.';
			}elseif( $u->info['banned'] > 0 ) {
				echo 'Заблокированные персонажи не могут публиковать статьи.';
			}elseif( $u->info['align'] == 2 ) {
				echo 'Хаосники не могут публиковать статьи.';
			}elseif( isset($mbpage_last['time']) && $mbpage_last['time'] > time() - 3600 && $u->info['admin'] == 0 ) {
				echo 'Нельзя публиковать статьи чаще одного раза в час.<br>Вы можете опубликовать статью через <b>'.$u->timeOut(($mbpage_last['time']+3600-time())).'</b>.';
			}elseif( isset($_POST['hide_id']) ) {
				$mbpage = mysql_fetch_array(mysql_query('SELECT * FROM `library_content` WHERE `url_name` = "'.mysql_real_escape_string($_POST['hide_id']).'" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
				if(isset($mbpage['id'])) {
					if(isset($mbpage['id']) && ($mbpage['uid'] == $u->info['id'] || $u->info['admin'] > 0) && ($mbpage['moder'] == 0 || $u->info['admin'] > 0) ) {
						mysql_query('UPDATE `library_content` SET `time` = "'.time().'",`title` = "'.mysql_real_escape_string($_POST['lib_title']).'",`text` = "'.mysql_real_escape_string($_POST['con_text']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
						$sid = $mbpage['id'];
						if( $sid > 0 ) {
		?>
				<b>Уважаем<? if( $u->info['sex'] == 0 ) { echo 'ый'; }else{ echo 'ая'; } ?></b> <?=$u->info['login']?>, благодарим Вас за дополнение статьи!<br />
				<br />
				Название статьи: &quot;<b><?=$_POST['lib_title']?></b>&quot;<br />
				Ссылка для просмотра: <a target="_blank" href="http://xcombats.com/lib/<?=$mbpage['url_name']?>/">http://xcombats.com/lib/<?=$mbpage['url_name']?>/</a>
				<hr />
				Мы ценим проделанную Вами работу и постараемся как можно скорее рассмотреть заявку на добавление данной статьи
				<br /><br /><br /><br /><br /><br /><br /><br />
				, с уважением<br />
				Администрация Старого Бойцовского Клуба.
		<?
						}else{
							echo 'Произошла ошибка изменения статьи.';
						}
					}else{
						echo 'Произошла ошибка изменения статьи!<br><b>Статья не найдена, либо у Вас нет прав для её изменения.</b>';
					}
				}else{
					echo 'Произошла ошибка изменения статьи.<br><b>Статья не найдена, либо у Вас нет прав для её изменения.</b>';
				}
			}else{
				$sid = 0;
				mysql_query('INSERT INTO `library_content` (`type`,`uid`,`time`,`title`,`url_name`,`text`) VALUES (
					"0","'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($_POST['lib_title']).'","id'.time().'","'.mysql_real_escape_string($_POST['con_text']).'"
				)');
				$sid = mysql_insert_id();
				if($sid > 0) {
					mysql_query('UPDATE `library_content` SET `url_name` = "id'.$sid.'" WHERE `id` = "'.$sid.'" LIMIT 1');
			?>
				<b>Уважаем<? if( $u->info['sex'] == 0 ) { echo 'ый'; }else{ echo 'ая'; } ?></b> <?=$u->info['login']?>, благодарим Вас за добавление статьи!<br />
				<br />
				Название статьи: &quot;<b><?=$_POST['lib_title']?></b>&quot;<br />
				Номер Вашей статьи: #<?=$sid?><br />
				Ссылка для просмотра: <a target="_blank" href="http://xcombats.com/lib/id<?=$sid?>/">http://xcombats.com/lib/id<?=$sid?>/</a>
				<hr />
				Мы ценим проделанную Вами работу и постараемся как можно скорее рассмотреть заявку на добавление данной статьи
				<br /><br /><br /><br /><br /><br /><br /><br />
				, с уважением<br />
				Администрация Старого Бойцовского Клуба.
			<?
				}else{
					echo 'Произошла ошибка добавления статьи.<br><b>Обратитесь к Администрации!</b>';
				}
			}
		}else{
			if( isset($url[3]) && $url[3] != '' ) {
				$mbpage = mysql_fetch_array(mysql_query('SELECT * FROM `library_content` WHERE `url_name` = "'.mysql_real_escape_string($url[3]).'" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
				if(isset($mbpage['id']) && ($mbpage['uid'] == $u->info['id'] || $u->info['admin'] > 0) && ($mbpage['moder'] == 0 || $u->info['admin'] > 0) ) {
					//all okey
				}else{
					unset($mbpage);
					echo '<div align="center" style="background-color:#e8b8b8;border:1px solid #b93939;color:#b93939;padding:5px;"><small>';
					echo 'Данная статья не найдена. Либо у Вас нет правд для её редактирования.';
					echo '</small></div>';
				}
			}
		?>
		<form method="post" action="http://xcombats.com/lib/new/<?=$mbpage['url_name']?>">
		<?
		if( isset($mbpage['id']) ) {
			
			if( isset($_POST['save2']) ) {
				//Изменения
				if( $u->info['admin'] > 0 ) {
					$red500 = false;
					if( isset($_POST['lib_urlname']) && $_POST['lib_urlname'] != '' && $_POST['lib_urlname'] != $mbpage['url_name'] ) {
						mysql_query('UPDATE `library_content` SET `delete` = "'.time().'" WHERE `url_name` = "'.$mbpage['url_name'].'" AND `id` != "'.$mbpage['id'].'"');
						$mbpage['url_name'] = htmlspecialchars($_POST['lib_urlname'],NULL,'cp1251');
						mysql_query('UPDATE `library_content` SET `url_name` = "'.mysql_real_escape_string($mbpage['url_name']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
						$red500 = true;
					}
					if( isset($_POST['lib_prov']) && $_POST['lib_prov'] == '1' && $_POST['lib_prov'] != '' && $_POST['lib_prov'] != '0') {
						$mbpage['moder2'] = $u->info['id'];
					}else{
						$mbpage['moder2'] = 0;
					}
					if( $mbpage['moder2'] != $mbpage['moder'] ) {
						mysql_query('UPDATE `library_content` SET `delete` = "'.time().'" WHERE `url_name` = "'.$mbpage['url_name'].'" AND `id` != "'.$mbpage['id'].'"');
						mysql_query('UPDATE `library_content` SET `moder` = "'.mysql_real_escape_string($mbpage['moder2']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
						$mbpage['moder'] = $mbpage['moder2'];
					}
					if( $red500 == true ) {
						echo '<script>top.location.href="http://xcombats.com/lib/new/'.$mbpage['url_name'].'/"</script>';
					}
				}
				if( $u->info['admin'] > 0 || $u->info['id'] == $mbpage['uid'] ) {
					if( $_POST['lib_title'] != $mbpage['title'] || $_POST['con_text'] != $mbpage['text'] ) {
						$mbpage['title'] = $_POST['lib_title'];
						$mbpage['text'] = $_POST['con_text'];
						$mbpage['time'] = time();
						mysql_query('UPDATE `library_content` SET `time` = "'.time().'",`title` = "'.mysql_real_escape_string(htmlspecialchars($mbpage['title'],NULL,'cp1251')).'",`text` = "'.mysql_real_escape_string($mbpage['text']).'" WHERE `id` = "'.$mbpage['id'].'" LIMIT 1');
					}
				}
			}
			
			?>
			<input name="hide_id" value="<?=$mbpage['url_name']?>" type="hidden" />
			<?
		}
		?>
		  <table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="150">Название статьи:</td>
					  <td><input style="width:350px;" name="lib_title" type="text" id="lib_title" maxlength="100" value="<?=$mbpage['title']?>" /></td>
					</tr>
				  <?
				  if( $u->info['admin'] > 0 ) { 
				  ?>
					<tr>
					  <td width="150">URL-NAME:</td>
					  <td><input style="width:350px;" name="lib_urlname" type="text" id="lib_urlname" maxlength="100" value="<?=$mbpage['url_name']?>" /></td>
					</tr>
					<tr>
					  <td width="150">Проверенная статья:</td>
					  <td><input type="checkbox" <? if($mbpage['moder'] > 0){ echo 'checked="checked"'; } ?> name="lib_prov" id="lib_prov" value="1" /> <?
					  if($mbpage['moder']>0) {
						 echo $u->microLogin($mbpage['moder'],1); 
					  }
					  ?></td>
					</tr>
				  <?
				  }
				  ?>
				  </table></td>
			</tr>
			<tr>
			  <td>
				<div style="padding:10px;width:480px;border:1px solid black;">
					<textarea class="w100p" name="con_text" id="con_text" cols="45" rows="5">
					<?=$mbpage['text']?>
					</textarea>
				</div>
			  </td>
			</tr>
			<tr>
			  <td><table width="98%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="150">Дата публикации:</td>
				  <td>
				  <? 
				  if(isset($mbpage['id'])) { echo date('d.m.Y',$mbpage['time']); }else{ echo date('d.m.Y'); }
				  if($u->info['admin'] > 0 || $u->info['id'] == $mbpage['uid']) {
				  ?>
				  <button name="save2" type="submit" style="float:right">Сохранить</button>
				  <? } ?>
				  <button name="save" type="submit" style="float:right">Опубликовать</button>
				  </td>
				</tr>
				<tr>
				  <td>Автор:</td>
				  <td><?=$u->microLogin($mbpage['uid'],1)?></td>
				</tr>
			  </table></td>
			</tr>
		  </table>
		</form>
		<script>
			CKEDITOR.inline( 'con_text' );
		</script>
		<?
			}
		?>
		</div>
		<?
		}else{
			$pl = mysql_fetch_array(mysql_query('SELECT * FROM `library_content` WHERE `url_name` = "'.mysql_real_escape_string($url[2]).'" AND `delete` = "0" ORDER BY `id` DESC LIMIT 1'));
			if( isset($pl['id']) && $url[3] == 'delete' && $u->info['admin'] > 0) {
				mysql_query('UPDATE `library_content` SET `delete` = "'.time().'" WHERE `url_name` = "'.mysql_real_escape_string($url[2]).'"');
				unset($pl);
			}
			if( isset($pl['id']) ) {
				$pl['text'] = str_replace('combatz.ru','origina;combats.com',$pl['text']);
				$pl['text'] = str_replace('combatz','СБК',$pl['text']);
				$pl['text'] = str_replace('CombatZ','СБК',$pl['text']);
				if($pl['moder'] == 0) {
					echo '<div align="center" style="background-color:#e8b8b8;border:1px solid #b93939;color:#b93939;padding:5px;"><small>Данная статья не прошла проверку и информация на ней не подтверждена!</small></div>';
				}
				echo '<div class="lib-txt-title"><h3>'.$pl['title'].'</h3></div><div class="lib-txt">'.$pl['text'].'</div>';
				if($pl['uid'] > 0 || $u->info['admin'] > 0) {
					echo '<hr><small><div> &nbsp; Дата публицации: '.date('d.m.Y',$pl['time']).' &nbsp; / &nbsp; Автор: '.$u->microLogin($pl['uid'],1).'';
					if($pl['uid'] == $u->info['id'] || $u->info['admin'] > 0) {
						echo ' &nbsp; / &nbsp; <a target="_blank" href="http://xcombats.com/lib/new/'.$pl['url_name'].'/">Изменить</a>';
						if( $u->info['admin'] > 0 ) {
							echo ' &nbsp; / &nbsp; <a href="http://xcombats.com/lib/'.$pl['url_name'].'/delete/">Удалить</a>';
						}
					}
					echo '</div></small>';
				}
			}else{
				echo '<div align="center" style="background-color:#e8b8b8;border:1px solid #b93939;color:#b93939;padding:5px;"><small>Статья не найдена. Скорее всего она была удалена, либо еще не создана.</small></div>';
			}
		}
		?>
        </div>
        <!-- -->
        </td>
      </tr>
    </table>