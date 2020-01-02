<script type="text/javascript" src="js/commoninf.js"></script> 
<?

die();

if(!defined('GAME'))
{
	die();
}
session_start();
// реализуем странички http://www.php.su/articles/?cat=examples&page=062

function bug_user($id){
	// Загружаем информацию об авторе
	$user = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id` = '".mysql_real_escape_string($id)."' LIMIT 1;"));
	return $user;
}
function bug_clan($id){
	// Догружаем информацию об клане
	$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '".mysql_real_escape_string($id)."' LIMIT 1;"));
	return $clan;
}

function bug_moder($align){  // Проверка склонности на модератора
	$data = mysql_fetch_array(mysql_query("SELECT `id` FROM `moder` WHERE `align` = '".mysql_real_escape_string($align)."' LIMIT 1;"));
	return $data;
}


function bug_classPost($admin, $id, $data, $readUser, $readAdmin, $readModer, $new){
	if($admin==1){
		$checkPost='admPost'; 
	} elseif(isset($data) AND $data!=0) {
		$checkPost='admPost moder';
	} else {
		$checkPost='userPost';
	}
	
	$user2=bug_user($id);
	$data2=bug_moder($user2['align']);
	
	if($new>='1'){
		$checkPost.=' new';
	} elseif($readAdmin==1 OR $readModer==1 OR $readUser==1){
		if($user2['admin']==1 AND $readAdmin>='1') {$checkPost.=' hot ad ';}
		
		if($readModer=='1' AND (isset($data2) AND $data2!=0)){$checkPost.=' hot m';}
		
		if($readUser=='1' AND ($user2['admin']==0 AND (!isset($data2) OR $data2==0))){$checkPost.=' hot u';} else {$checkPost.='';}
	} 
	return $checkPost;
}
function bug_type($type){
	if(isset($type)){
		if($type==1){$tiType="Игровые вопросы";}
		elseif($type==2){$tiType="Ошибки мироздания";}
		elseif($type==3){$tiType="Орден света";}
		elseif($type==4){$tiType="Предложения";}
		elseif($type==5){$tiType="Другое";}
		else {$tiType='Нет';}
	} else {
		$tiType='Тема не задана';
	}
	return $tiType;
}



function bug_userinfo($login, $id, $level, $align, $clanname){ 	
	// Проверяем на существование персонажа и выводим информацию о нем в HTML код
	if(isset($login)){
		$userinfo = "<SCRIPT>drwfl('".$login."', '".$id."','".$level."','".$align."','".$clanname."')</SCRIPT>"; 
	} else { 
		$userinfo = '<span style=" FONT-WEIGHT: bold;color: #000;font-size: 10pt;">[Игрок не найден]</span>';
	}
	return $userinfo;
}

function bug_upView($id) {				
	//$q = mysql_query("UPDATE `bags` SET readUser='0' WHERE id='".$id."'") or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error ());
}

function bug_filter_area($id, $page){				
	if(isset($_POST['opt1']) OR $_GET['type']==1){$f1=" checked";}
	if(isset($_POST['opt2']) OR $_GET['type']==2){$f2=" checked";}
	if(isset($_POST['opt3']) OR $_GET['type']==3){$f3=" checked";}
	if(isset($_POST['opt4']) OR $_GET['type']==4){$f4=" checked";}
	if(isset($_POST['opt5']) OR $_GET['type']==5){$f5=" checked";} 
	if($_POST['delete']==1 OR $_COOKIE['delete']==1){$f6=" checked";}
	
	$filter="<style>input[type=\"checkbox\"]{padding:5px;}</style>";
	$filter.="<form  style=\"padding-top:4px;display:inline-block; margin:6px 30px;\" method=\"post\" action=\"main.php?bagreport=1\">";
	$filter.="<input type=\"checkbox\" name=\"opt1\" id=\"check1\" value=\"1\" ".$f1."><label for=\"check1\">[Игровые вопросы]</label><br/>";
	$filter.="<input type=\"checkbox\" id=\"check2\" name=\"opt2\" value=\"2\" ".$f2."><label for=\"check2\">[Ошибки мироздания]</label><br/>";
	$filter.="<input type=\"checkbox\" name=\"opt3\" value=\"3\" id=\"check3\" ".$f3."><label for=\"check3\">[Орден света(и жалобы на них)]</label><br/>";
	$filter.="<input type=\"checkbox\"  id=\"check4\" name=\"opt4\" value=\"4\" ".$f4."><label for=\"check4\">[Предложения(без ответов)]</label><br/>";
	$filter.="<input type=\"checkbox\"  id=\"check5\" name=\"opt5\" value=\"5\" ".$f5."><label for=\"check5\">[Другое]</label><br/><br/>";
	$filter.="<input type=\"checkbox\"  id=\"delete1\" name=\"delete\" value=\"1\" ".$f6."><label for=\"delete1\">Отображать закрытые темы?</label><br/><br/>";
	$filter.="&nbsp; &nbsp;на странице: <select name=\"limit\" onchange='this.form.submit()'><option value=\"".$_POST['limit']."\">".$_POST['limit']."</option>";
	if ($_POST['limit']>5){$filter.="<option value=\"5\">5</option> ";}$filter.="<option value=\"10\">10</option><option value=\"20\">20</option><option value=\"30\">30</option><option value=\"40\">40</option><option value=\"50\">50</option></select>";
	
	$filter.="&nbsp; &nbsp;страница: <select name=\"page\" onchange='this.form.submit()'>";
	
	for($i=1;$i<=$page;$i++){
		if($_POST['page']==$i){
			$filter.="<option value=\"".$i."\" selected=\"selected\">".$i."</option>"; 
		} else {
			$filter.="<option value=\"".$i."\">".$i."</option>"; 
		}
	} 
	
	$filter.="</select>";
	
	$filter.="<br/><input type=\"submit\" value=\"Применить\"></form>";
	return $filter;
}

function bug_filter($opt, $f, $id, $t) {
	$where="";
	$pre=0;
	// Фильтр WHERE для поиска
	
	if($opt!=NULL){
		for($i=0;$i<count($opt);$i++){
			if(isset($opt[$i])){
				if($i==0){
					if($pre==0){$pre=1;}
					$where.='(';
				}
				
				if($i>=1 && $pre==1){
					$where.=" OR `type`=".$opt[$i];
				} else { 
					$where.="`type`=".$opt[$i]." ";
				}
				if($i==count($opt)-1){$where.=')';}
			}
		}
	}
	
	if($opt==NULL AND $t==0){$where .="(`type`=1 OR `type`=2 OR `type`=3 OR `type`=4 OR `type`=5)";$_POST['opt1']='1';$_POST['opt2']='2';$_POST['opt3']='3';$_POST['opt4']='4';$_POST['opt5']='5';unset($_POST['delete']);}
	
	if(isset($_GET['type'])) {$where = "WHERE `type`=".$_GET['type']." ";}
	
	
	if(empty($_POST['limit'])){ $_POST['limit']='5';}
	if(empty($_POST['page'])){ $_POST['page']='1';}
	$current_page = (($_POST['limit']*$_POST['page'])-$_POST['limit']);
	if(isset($_POST['limit'])){ $limit="LIMIT ".$current_page.",".$_POST['limit']." ";} else { $limit="LIMIT 5";} 
	if(isset($_GET['post']) AND $f==0) { $where=" `id`=".$_GET['post']." "; $limit="LIMIT 1"; } //adm
	if(isset($_GET['post']) AND $f==1) { $where=" `id`=".$_GET['post']." "; $limit="LIMIT 1"; } //user
	//echo "".."".."";
	
	
	if($f==1){
		if($t==1){$limit='';}
		if($_POST['delete']==0 && !isset($_GET['post'])){
			$where.= " AND `delete`=0 ";
		}
		$bugs = mysql_query("SELECT * FROM `bags` WHERE uid='".$id."' AND ".$where." ORDER BY time DESC ".$limit." "); 
		
	} else {
		if($t==1){$limit='';}
		if($_POST['delete']==0 && empty($where) && !isset($_GET['post'])){
			$where.= " WHERE `delete`=0 ";
		} elseif ($_POST['delete']==0 && !isset($_GET['post'])) {
			$where.= " AND `delete`=0 ";
		}
		$bugs = mysql_query("SELECT * FROM `bags` WHERE ".$where." ORDER BY time DESC ".$limit." "); 
	}
	

	return $bugs;
}

if($u->error!=''){ echo '<font color="red"><b>'.$u->error.'</b></font><br>'; }
$page ="";
$post = round($_GET['post']);
$data = bug_moder($u->info['align']);
$atp = 'Приветствую тебя, ';
//$opt = array($_POST['opt1'],$_POST['opt2'],$_POST['opt3'],$_POST['opt4'],$_POST['opt5']);
if(isset($_POST['opt1'])){ $opt[]=$_POST['opt1'];}if(isset($_POST['opt2'])){ $opt[]=$_POST['opt2'];}if(isset($_POST['opt3'])){ $opt[]=$_POST['opt3'];}if(isset($_POST['opt4'])){ $opt[]=$_POST['opt4'];}if(isset($_POST['opt5'])){ $opt[]=$_POST['opt5'];}


/*
if($_POST['delete']==1){
	$_POST['delete']=1;
} else {
	$_POST['delete']=0;
	setcookie("delete", 0);
}
if($_POST['delete']==1 && isset($_COOKIE['delete'])){setcookie("delete", $_POST['delete'], time()+3600);}
else {setcookie("delete", 0);}
echo $_POST['delete']."-".$_COOKIE['delete'];
*/

?>
<style>
html,body {margin: 0px!important; padding: 0px !important; background-color:#404040;}
#adm { display:block; width:100%; height:100%; background-color:#404040;}
#adm * { font-family: Arial, Helvetica, Tahoma, sans-serif; }
#adm .title { font-size:15px; padding:6px 20px 2px 20px;background-color:white; border-bottom:1px solid #8c8c8c; height:24px; }
#adm .title .left { float:left;}
#adm .title .right { float:right;}
#adm .title .button { border:1px solid #bebebe; font-family:"Minion Pro", Times, "Times New Roman", serif; margin:0px 3px; padding: 1px 15px; font-size: 13px; border-radius:12px; background:url('//img.xcombats.com/admin/adm_inputs_bg.png') no-repeat center center;}
#adm .menu { font-size:16px; padding:0px 20px;background:url('//img.xcombats.com/admin/adm_menu_bg.png') repeat-x top; height:28px;}
#adm .menu a { font-weight: normal; text-decoration:none; padding:4px 8px; margin:0px 3px; color: #404040;height:20px; display:inline-block; }
#adm .menu a:hover, #adm .menu a.active { text-decoration:underline; color:#f7f7f7; background:url('//img.xcombats.com/admin/adm_menu_bg-active.png') repeat-x top;}
#adm .content {  margin: 8px 0px; border:0px; width:100%; display: table;}
#adm .content .cell { vertical-align:top; margin:0px; padding:0px; }
#adm .content .cell .t { background:#eaeaea; margin:0px;}
#adm .content .cell.right { width:350px; }
#adm .content .cell .t.right { margin:0px 10px !important; }
#adm .content .cell .t.right .f { background:url('//img.xcombats.com/admin/adm_bg-create.png') no-repeat top right;   padding:10px 14px; width:350px; }
#adm .content .cell .t.left { padding:10px 20px 10px 0px !important; }
form .newpost input, form .newpost textarea, form .newpost select{ padding:2px 4px; border-radius: 5px;color: #7E4007;font-size: 10pt;}
.linegrad_answer {
	background: rgb(224,224,224); /* Old browsers */
	background: -moz-linear-gradient(left, rgba(214,214,214,1) 0%, rgba(239,239,239,1) 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(214,214,214,1)), color-stop(100%,rgba(239,239,239,1))); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(left, rgba(214,214,214,1) 0%,rgba(239,239,239,1) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(left, rgba(214,214,214,1) 0%,rgba(239,239,239,1) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(left, rgba(214,214,214,1) 0%,rgba(239,239,239,1) 100%); /* IE10+ */
	background: linear-gradient(to right, rgba(214,214,214,1) 0%,rgba(239,239,239,1) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d6d6d6', endColorstr='#efefef',GradientType=1 ); /* IE6-9 */
	margin: 8px auto;
	padding: 0px;
	border-bottom: 1px solid #DBDBDB;
	border-right: 3px solid #B3B3B3;
}
.linegrad { 
	background: rgb(210,210,210); /* Old browsers */
	background: -moz-linear-gradient(left, rgba(200,200,200,1) 0%, rgba(234,234,234,1) 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(200,200,200,1)), color-stop(100%,rgba(234,234,234,1))); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(left, rgba(200,200,200,1) 0%,rgba(234,234,234,1) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(left, rgba(200,200,200,1) 0%,rgba(234,234,234,1) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(left, rgba(200,200,200,1) 0%,rgba(234,234,234,1) 100%); /* IE10+ */
	background: linear-gradient(to right, rgba(200,200,200,1) 0%,rgba(234,234,234,1) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#bfbfbf', endColorstr='#eaeaea',GradientType=1 ); /* IE6-9 */
	margin: 8px auto;
	padding: 0px;
	border-bottom: 1px solid #DBDBDB;
	border-right: 3px solid #B3B3B3;
}
.userPost { padding: 8px 20px; background:url('//img.xcombats.com/admin/user_post-old.png') no-repeat top right; }
.userPost.hot{ background:url('//img.xcombats.com/admin/user_post-hot.png') no-repeat top right; }
.userPost.new { background:url('//img.xcombats.com/admin/user_post-new.png') no-repeat top right; }
.admPost { padding: 8px 20px; background:url('//img.xcombats.com/admin/adm_post-old.png') no-repeat bottom left; }
.admPost.hot{ background:url('//img.xcombats.com/admin/adm_post-hot.png') no-repeat bottom left; }
.admPost.new { background:url('//img.xcombats.com/admin/adm_post-new.png') no-repeat bottom left; }
.moderPost { padding: 8px 20px; background:url('//img.xcombats.com/admin/moder_post-old.png') no-repeat bottom left; }
.moderPost.hot{ background:url('//img.xcombats.com/admin/moder_post-hot.png') no-repeat bottom left; }
.moderPost.new { background:url('//img.xcombats.com/admin/moder_post-new.png') no-repeat bottom left; }
</style>
<? 
if(!isset($_GET['post'])) { $formTitle='СОЗДАТЬ СООБЩЕНИЕ'; $formButton='Задать вопрос';} else { $postLink='&post='.$_GET['post']; $formTitle='ОТВЕТИТЬ В ТЕМЕ'; $formButton='Ответить в теме';}
$formcreate='<h2 style="font-size:16px; padding-left:20px; font-weight:bold; color: #404040; margin:0px; text-transform:uppercase;">'.$formTitle.'</h2>
<form style="color: #3F2A11;" method="post" action="main.php?bagreport=1&action=create'.$postLink.'">
<table class="newpost" width="100%">
	<tr>
		<td style="padding-right:7px;" align="right" width="85px"> Персонаж:</td>';
		if(isset($u->info['login'])){$c=strlen($u->info['login']);$formcreate.='<td><input type="text" name="name" size="'.$c.'" value="'.$u->info['login'].'" disabled/></td>';}else{$formcreate.='<td><input type="text" name="name" size="24" value=""/></td>';}
	$formcreate.='</tr>';

if(!isset($_GET['post'])){	$formcreate.='
	<tr>
		<td style="padding-right:7px;width: 75px;" align="right" >Категория:</td>
		<td>
			<select name="type"> 
				<option value="1">Игровые вопросы</option>
				<option value="3">Орден Света</option>
				<option value="2">Ошибки Мироздания</option>
				<option value="4">Предложения</option>
				<option value="5" selected>Другое</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="padding-right:7px;" align="right" >Тема:</td>
		<td><input type="text" size="32" name="title" /></td>
	</tr>'; }
$formcreate.='
	<tr>
		<td style="vertical-align:top; padding-right:10px;" align="right" >Текст:</td>
		<td><textarea name="description" style="width:238px; height:100px; "></textarea></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" style="margin-top: 20px;padding: 6px 10px;font-weight: bold;color: #89634e;" value="Отправить" /></td>
	</tr>
</table>
</form>';





//if($data OR $u->info['admin']>0 OR $u->info['login']=='Хорс'){ // if moder or admin
if($data OR $u->info['admin']>0){ // if moder or admin
	if(isset($_POST['limit'])){
		$num = $_POST['limit'];
	}else{$num=5;}
	
	$limitposts=round($limit); 
	$posts=mysql_num_rows(bug_filter($opt, 0, $u->info['id'], 1));
	$bugs=bug_filter($opt, 0, $u->info['id'], 0); 
	$total=intval(($posts - 1) / $num) + 1; 
	$filter=bug_filter_area($u->info['admin'], $total); 
	
	// Отображение списка записей
	$page.= '<h2 style="font-size:16px; padding-left:20px; font-weight:bold; color: #404040; margin:0px; text-transform:uppercase;">ЗАЯВКИ</h2>';
	while ($r = mysql_fetch_array($bugs)) { // Вывод списка  
		// Сокращение содержимого текста, возвращает[$text]
		if(strlen($r['text'])>=100 && empty($_GET['post'])){$text=substr($r['text'], 0, 100)."...";} else {$text=$r['text'];}
		
		$author = $r['uid'];
		$user = bug_user($author);
		$clan = bug_clan($user['clan']);		
		$userinfo = bug_userinfo($user['login'], $user['id'], $user['level'], $user['align'], $clan['name_mini']);
 		$tiType = bug_type($r['type']); 
		$data = bug_moder($user['align']); 
		$checkPost = bug_classPost($user['admin'],$u->info['id'], $data, $r['readUser'], $r['readAdmin'], $r['readModer'], $r['up']); 
			
		if(strlen($r['title'])>=1){ $title="<b>«".$r['title']."»</b>"; } else { $title="<b>«Отсутствует»</b>"; }

		$func="";
		$func.="<a href=\"main.php?bagreport=1&post=".$r['id']."&action=read\"><img src=\"//img.xcombats.com/admin/read.png\" height=\"16\"/></a>"; // Отметить - Я прочитал
		if($u->info['admin']>0 OR $data)$func.="<a href=\"main.php?bagreport=1&post=".$r['id']."&action=update\"><img src=\"//img.xcombats.com/admin/update.png\" height=\"16\"/></a>"; // Вернуть отметку - Я не прочел
		//if($u->info['admin']>0 OR $data)$func.="<a href=\"main.php?bagreport=1&post=".$r['id']."&action=edit\"><img src=\"//img.xcombats.com/admin/edit.png\" height=\"16\"/></a>";
		$func.="<a href=\"main.php?bagreport=1&post=".$r['id']."&action=delete\"><img src=\"//img.xcombats.com/admin/close.png\" height=\"16\"/></a>";

		
		$bug_sid = mysql_query("SELECT * FROM `bags` WHERE `sid`=".$r['id']." ORDER BY time ASC");
		if($r['delete']==1){$thisDelete="<span style='color:red'>Тема закрыта</span> &nbsp; | &nbsp;";} else {$thisDelete=" ";}
		$reCount=mysql_num_rows($bug_sid);
		$result= "<div class='linegrad'>
			<div class='".$checkPost."'>
				<div><a href='main.php?bagreport=1&type=".$r['type']."' title='Вернуться в рубрику'><b>".$tiType."</b></a> > <a href='main.php?bagreport=1&post=".$r['id']."' title='Подробнее...'>".$title."</a> от ".$userinfo." <span style='float:right; text-align:right;'>".$thisDelete."Сообщений: ".$reCount." (<i class='date'>".date("G:i, d.m.Y",$r['time'])."</i>) <br/>".$func."</span></div>
				<div style='padding:6px 0px; width: 90%;'>".$text."</div>
			</div>
		</div>";
		if(isset($_GET['post'])){
			while($s = mysql_fetch_array($bug_sid)) {
				$user = bug_user($s['uid']);
				$clan = bug_clan($user['clan']);		
				$userinfo = bug_userinfo($user['login'], $user['id'], $user['level'], $user['align'], $clan['name_mini']);
				$data = bug_moder($user['align']);
				$checkPost = bug_classPost($user['admin'],$u->info['id'], $data, $s['readUser'], $s['readAdmin'], $s['readModer'], $s['up']); 
				
				$result.= "<div  class='linegrad_answer' ><div class='".$checkPost."'><div style='font-size:12px'>Ответ от ".$userinfo." <span style='float:right;'>(<i class='date'>".date("G:i, d.m.Y",$s['time'])."</i>)</span></div><div style='padding:6px 0px; width: 90%;'>".$s['text']."</div></div></div>";

				if($s['uid']!=$u->info['id']){
					bug_upView($s['id']);
				}
				if($r['uid']!=$u->info['id']){
					bug_upView($r['id']);
				}
			}
		}
		$i++;
		$page .= $result;
		if(isset($_GET['post']) AND $r['id']==$_GET['post']) break;
	}
 
} else {
// if user
	if(isset($_POST['limit'])){
		$num = $_POST['limit'];
	} else { $num=5;}
	
	$limitposts = round($limit); 
	$posts = mysql_num_rows(bug_filter($opt, 1, $u->info['id'], 1));
	$bugs = bug_filter($opt, 1, $u->info['id'], 0);
	$total = intval(($posts - 1) / $num) + 1; 
	$filter=bug_filter_area($u->info['admin'], $total);

	// Отображение списка записей пользователя
	$page.= '<h2 style="font-size:16px; font-weight:bold; color: #404040; padding-left:20px; margin:0px; text-transform:uppercase;">ВАШИ СООБЩЕНИЯ</h2> ';
	while ($r = mysql_fetch_array($bugs)) {
		// Сокращение содержимого текста, возвращает[$text]
		if(strlen($r['title'])>=1){ $title="<b>«".$r['title']."»</b>"; } else { $title="<b>«Отсутствует»</b>"; }
		if(strlen($r['text'])>=47 && empty($_GET['post'])){$text=substr($r['text'], 0, 47)."...";} else {$text=$r['text'];}

		$author = $r['uid'];
		$user = bug_user($author);
		$clan = bug_clan($user['clan']);		
		$userinfo = bug_userinfo($user['login'], $user['id'], $user['level'], $user['align'], $clan['name_mini']);
		$tiType = bug_type($r['type']); 
		$data = bug_moder($user['align']); 
		$checkPost = bug_classPost($user['admin'],$u->info['id'], $data, $r['readUser'], $r['readAdmin'], $r['readModer'], $r['up']); 
		
		$func="";
		$func.="<a href=\"main.php?bagreport=1&post=".$r['id']."&action=read\"><img src=\"//img.xcombats.com/admin/read.png\" height=\"16\"/></a>"; // Отметить - Я прочитал
		$func.="<a href=\"main.php?bagreport=1&post=".$r['id']."&action=update\"><img src=\"//img.xcombats.com/admin/update.png\" height=\"16\"/></a>"; // Вернуть отметку - Я не прочел
		$func.="<a href=\"main.php?bagreport=1&post=".$r['id']."&action=delete\"><img src=\"//img.xcombats.com/admin/close.png\" height=\"16\"/></a>"; // Закрыть мою тему

		if($r['delete']==1){$thisDelete="<span style='color:red'>Тема закрыта</span> &nbsp; | &nbsp;";} else {$thisDelete="";}
		$bug_sid = mysql_query("SELECT * FROM `bags` WHERE `sid`=".$r['id']." ORDER BY time ASC");
		$reCount=mysql_num_rows($bug_sid);
		$result= "<div class='linegrad'><div class='".$checkPost."'><div><span style='color:darkred; font-size:11px; font-style:italic;'>".$tiType."</span> > <a href='main.php?bagreport=1&post=".$r['id']."' title='Подробнее...'>".$title."</a> <span style='float:right; text-align:right;'>".$thisDelete." Ответов в теме: ".$reCount." (<i class='date'>".date("G:i, d.m.Y",$r['time'])."</i>) <br/>".$func."</span></div><div style='padding:6px 0px;'>".$text."</div></div></div>";
		 
		if(isset($_GET['post'])){
			if($r['delete']==1){
				if ($_GET['action']=='delete' OR empty($_GET['action'])){
					$formcreate='<h2 style="font-size:16px; padding-left:20px; font-weight:bold; color: red; margin:0px; text-transform:uppercase;">Тема закрыта</h2>';
				}elseif($_GET['action']=='edit') {
					$formcreate='';
				}
			}
			while($s = mysql_fetch_array($bug_sid)) {
				$text=$s['text'];
				$user = bug_user($s['uid']);
				$clan = bug_clan($user['clan']);		
				$userinfo = bug_userinfo($user['login'], $user['id'], $user['level'], $user['align'], $clan['name_mini']);
				$data = bug_moder($user['align']);  
				$checkPost = bug_classPost($user['admin'],$u->info['id'], $data, $s['readUser'], $s['readAdmin'], $s['readModer'], $s['up']); 
				
				$result.= "<div  class='linegrad_answer' ><div class='".$checkPost."'><div style='font-size:12px'>Ответ от ".$userinfo." <span style='float:right;'>(<i class='date'>".date("G:i, d.m.Y",$s['time'])."</i>)</span></div><div style='padding:6px 0px;'>".$text."</div></div></div>";
				/*if($s['uid']!=$u->info['id']){
					// Если сообщение не мое, отмечаем как прочитанное
					$query = "UPDATE `bags` SET readUser='0' WHERE id='".$s['id']."'";
					$q = mysql_query($query) or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error ()); 
				}*/
			}
		}
		$i++;
		$page .= $result; 
		if(isset($_GET['post'])) break;
	}
	
	// Вывод сообщений пользователя
}


if($_GET['action']=='create'){
	if(isset($u->info['id'])){$id=$u->info['id'];} else { $usLogin = mysql_fetch_array(mysql_query("SELECT `login`,`id` FROM `users` WHERE `login` = '".mysql_real_escape_string($_POST['name'])."' LIMIT 1;"));$id=$usLogin['id'];}
	$title=htmlspecialchars(mysql_real_escape_string($_POST['title']),NULL,'cp1251');
	$type=$_POST['type']; 
	$ip=$_SERVER['REMOTE_ADDR']; 
	$descr=htmlspecialchars(mysql_real_escape_string($_POST['description']),NULL,'cp1251');
	
	if ($u->info['admin']>0) { $readAdmin="0"; $readUser="1";  } else{ $readAdmin="1"; $readUser="0";}
	if ($data) { $readModer="0";  $readUser="1"; } else  { $readModer="1"; $readUser="0"; } 

// Отмечает предыдущие сообщения как прочитанные
	$query = "UPDATE `bags` SET readAdmin='0', readModer='0', readUser='0' WHERE sid='".$post."'";
	$q = mysql_query($query) or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error ()); 
	
// Добавление новое сообщение
	$query = "INSERT INTO `bags` (`uid`, `title`, `sid`, `text`, `type`, `time`, `ip`, `fast`, `readAdmin`, `readModer`, `readUser`) VALUES ('".$id."', '".$title."', '".$_GET['post']."', '".htmlspecialchars($descr,NULL,'cp1251')."', '".$type."', '".time()."', '".$ip."', '0', '".$readAdmin."', '".$readModer."', '".$readUser."');";
	$q = mysql_query($query) or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error ()); 
	
// Обновление статуса темы 
	$thead = mysql_fetch_array(mysql_query("SELECT `delete`,`readUser`,`readModer`,`readAdmin`, `uid` FROM `bags` WHERE `id` = '".mysql_real_escape_string($_GET['post'])."' LIMIT 1;"));
	if($u->info['admin']>0){
		$query = "UPDATE `bags` SET `delete`='0', `readUser`=1, `readModer`=0, `readAdmin`=0, `time`='".time()."' WHERE  `id`='".$post."';";
	}elseif($data){
		$query = "UPDATE `bags` SET `delete`='0', `readUser`=1, `readModer`=0, `readAdmin`=0, `time`='".time()."' WHERE  `id`='".$post."';";
	}else{
		$query = "UPDATE `bags` SET `delete`='0', `readUser`=0, `readModer`=1, `readAdmin`=1, `time`='".time()."' WHERE  `id`='".$post."';";
	}
	//$query = "UPDATE `bags` SET readUser='1', readAdmin='1', time='".time()."'  WHERE id='".$post."'";
	$q = mysql_query($query) or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error ()); 
	 
	$answer.="<div style=\"text-align:center; width:100%;\"><span style='margin-top:30px;color:green; font-weight:bold'>Тема успешно создана</span>.</div>";
	$answer.='<META HTTP-EQUIV="refresh" CONTENT="0; url=/main.php?bagreport=1&post='.$post.'">';
	//redirect to post
	$answer.=$formcreate;
} elseif($_GET['action']=='edit'){
/**/ 
	$formTitle='РЕДАКТИРОВАНИЕ ТЕМЫ'; 
	$formButton='Сохранить изменения';
	$postLink='post='.$_GET['post'].'&'; 
	
	$formcreate='<h2 style="font-size:16px; padding-left:20px; font-weight:bold; color: #404040; margin:0px; text-transform:uppercase;">'.$formTitle.'</h2>
	<form style="color: #3F2A11;" method="post" action="main.php?bagreport=1&'.$postLink.'action=edit">
	<table class="newpost" width="100%">
	<tr>
	<td style="padding-right:7px;" align="right" width="85px"> Персонаж:</td>';
	if(isset($u->info['login'])){$c=strlen($u->info['login']);
	$formcreate.='<td><input type="text" name="name" size="'.$c.'" value="'.$u->info['login'].'" disabled/></td>';}else{$formcreate.='<td><input type="text" name="name" size="24" value=""/></td>';}
	$formcreate.='</tr>';
	$formcreate.='
		<tr>
			<td style="padding-right:7px;width: 75px;" align="right" >Категория:</td>
			<td>
				<select name="type" selected="4"> 
					<option value="1">Игровые вопросы</option>
					<option value="3">Орден Света</option>
					<option value="2">Ошибки Мироздания</option>
					<option value="4">Предложения</option>
					<option value="5" selected>Другое</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="padding-right:7px;" align="right" >Тема:</td>
			<td><input type="text" size="32" name="title" /></td>
		</tr>';
	$formcreate.='
		<tr>
			<td style="vertical-align:top; padding-right:10px;" align="right" >Текст:</td>
			<td><textarea name="description" style="width:238px; height:100px; "></textarea></td>
		</tr>
		<tr>
			<td colspan=2><input type="submit" style="margin-top: 20px;padding: 6px 10px;font-weight: bold;color: #89634e;" value="'.$formButton.'" /></td>
		</tr>
	</table>
	</form>';
	$answer.=$formcreate;
/**/
} elseif($_GET['action']=='delete'){
	$dlt = mysql_fetch_array(mysql_query("SELECT `delete`, `uid` FROM `bags` WHERE `id` = '".mysql_real_escape_string($_GET['post'])."' LIMIT 1;"));
	if($dlt['delete']=='1'){$dlt=0;}
	if($dlt['delete']=='0'){$dlt=1;}
	
	if($u->info['admin']>0){
		$query = "UPDATE `bags` SET `delete`='".$dlt."', `readModer`='0', `readAdmin`='0', `readUser`='0' WHERE `id`='".$_GET['post']."';";
	}
	if($data){
		$query = "UPDATE `bags` SET `delete`='".$dlt."', `readModer`=0, `readAdmin`=0, `readUser`=0 WHERE `id`='".$_GET['post']."';";
	}
	if(isset($data) AND $data!=0) {
		$query = "UPDATE `bags` SET `delete`='".$dlt."', readModer=0, readAdmin=0, readUser=0 WHERE  `id`='".$_GET['post']."';";
	}
	if($dlt['uid']==$author){
		if($dlt['uid']==$author){
			$query = "UPDATE `bags` SET `delete`='".$dlt."', `readUser`=0, `readModer`=0, `readAdmin`=0 WHERE  `id`='".$post."';";
		}
	}
	$q = mysql_query($query) or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error()); 
	$answer.=$formcreate;
} elseif($_GET['action']=='update'){
	if($u->info['admin']>0){
		$query = "UPDATE `bags` SET readAdmin='1', time='".time()."'  WHERE id='".$post."'";
	} elseif(isset($data) AND $data!=0) {
		$query = "UPDATE `bags` SET readModer='1', time='".time()."'  WHERE id='".$post."'";
	} else {
		$query = "UPDATE `bags` SET readUser='1', time='".time()."'  WHERE id='".$post."'";
	}
	$q = mysql_query($query) or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error ()); 
	$answer.="<div style=\"text-align:center; width:100%;\"><span style='margin-top:30px;color:#8f0000; font-weight:bold'>Прочесть позже</span>.</div>";
	$answer.='<META HTTP-EQUIV="refresh" CONTENT="0; url=/main.php?bagreport=1&post='.$post.'">';
} elseif($_GET['action']=='read'){
	if($u->info['admin']>0){
		$query = "UPDATE `bags` SET readAdmin='0', time='".time()."'  WHERE id='".$post."'";
	} elseif(isset($data) AND $data!=0) {
		$query = "UPDATE `bags` SET readAdmin='0', time='".time()."'  WHERE id='".$post."'";
	} else {
		$query = "UPDATE `bags` SET readUser='0', time='".time()."'  WHERE id='".$post."'";
	}
	$q = mysql_query($query) or die ("<span style='color:red'>Ошибка при выполнении запроса</span>: ".mysql_error ()); 
	$answer.=$formcreate;
} else {
	$answer.=$formcreate;
}
?> 

<div id="adm">
	<div class="title">
		<div class="left"><?=$atp;?><?$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."' LIMIT 1;"));?><SCRIPT>drwfl("<?=$u->info['login']?>",<?=$u->info['id']?>,"<?=$u->info['level']?>",<?=$u->info['align']?>,"<?=$clan['name_mini'];?>")</SCRIPT>!</div>
		<div class="right"> 
			<INPUT TYPE="button" class='button' onclick="location.href='main.php?bagreport=1';" value="Обновить" title="Обновить">
			<INPUT TYPE="button" class='button' onclick="location.href='main.php';" value="Вернуться" title="Вернуться">
		</div>
	</div>
	<div class="menu"> 
		<a href='#' onclick="location.href='main.php?bagreport=1';" <?if(isset($_GET['bagreport']))echo"class='active'";?>>Служба поддержки</a>
	</div>
	<table class="content">
		<tr style="display:table-row;">
			<td class='cell'>
				<div class='t left'>
					<?=$page?>
				</div>
			</td>
			<td class='cell right'>
				<div class='t right'>
					<div class="f">
						<h2 style="font-size:16px; padding-left:20px; font-weight:bold; color: #404040; margin:0px; text-transform:uppercase;">Фильтр</h2>
						<?=$filter?> 
					</div>
				</div><br/>
				<div class='t right'>
					<div class="f">
						<?=$answer?> 
					</div>
				</div>
			</td>
		</tr>
	</table>
</div> 

 
