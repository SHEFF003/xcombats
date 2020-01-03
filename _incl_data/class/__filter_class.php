<?
if(!defined('GAME'))
{
	die();
}

class Filter {
	//
	public $spamtxt = 'acreshoperu|shambalru|oldcombats|oldbk2|oldbk|sebkru|oldbknet|welcombats|mycombats|vipbk|goldbk|recombats|legbkcom|febkru|skycombats|cambats|zagadnet';
	public $abuse = array(
		"лох","мудак","гандон","пидарас","чмо","хуй","хуйня","хуйни","шлюха","паскуда","бля",'блять','блядь',
		'еблан','шалава','ебал','ебать','дрочить'
		,'уебище'
	);
	//
	public function str_count($str,$col) 
	{ 
		if (strlen($str) > $col) 
		{ 
			$str = substr($str,0,$col); 
		} 
		return ($str); 
	}
	//
	//Смайлики
	public $sm = array("laugh"=>1,"fingal"=>1,"eek"=>1,"smoke"=>1,"hi"=>1,"bye"=>1,"king"=>1,"king2"=>1,"boks2"=>1,"boks"=>1,"gent"=>1,"lady"=>1,"tongue"=>1,"smil"=>1,"rotate"=>1,"ponder"=>1,"bow"=>1,"angel"=>1,"angel2"=>1,"hello"=>1,"dont"=>1,"idea"=>1,"mol"=>1,"super"=>1,"beer"=>1,"drink"=>1,"baby"=>1,"tongue2"=>1,"sword"=>1,"agree"=>1,"loveya"=>1,"kiss"=>1,"kiss2"=>1,"kiss3"=>1,"kiss4"=>1,"rose"=>1,"love"=>1,"love2"=>1,"confused"=>1,"yes"=>1,"no"=>1,"shuffle"=>1,"nono"=>1,"maniac"=>1,"privet"=>1,"ok"=>1,"ninja"=>1,"pif"=>1,"smash"=>1,"alien"=>1,"pirate"=>1,"gun"=>1,"trup"=>1,"mdr"=>1,"sneeze"=>1,"mad"=>1,"friday"=>1,"cry"=>1,"grust"=>1,"rupor"=>1,"fie"=>1,"nnn"=>1,"row"=>1,"red"=>1,"lick"=>1,"help"=>1,"wink"=>1,"jeer"=>1,"tease"=>1,"nunu"=>1,"inv"=>1,"duel"=>1,"susel"=>1,"nun"=>1,"kruger"=>1,"flowers"=>1,"horse"=>1,"hug"=>1,"str"=>1,"alch"=>1,"pal"=>1,"mag"=>1,"sniper"=>1,"vamp"=>1,"doc"=>1,"doc2"=>1,"sharp"=>1,"naem"=>1,"naem2"=>1,"naem3"=>1,"invis"=>1,"chtoza"=>1,"beggar"=>1,"sorry"=>1,"sorry2"=>1,
	"creator"=>1,"grace"=>1,"dustman"=>1,"carreat"=>1,"lordhaos"=>1,"ura"=>1,"elix"=>1,"dedmoroz"=>1,"snegur"=>1,"showng"=>1,"superng"=>1,"podz"=>1,"sten"=>1,"devil"=>1,"cat"=>1,"owl"=>1,"lightfly"=>1,"snowfight"=>1,"rocket"=>1,"ball"=>1,"smile"=>1,"fuck"=>1);

	public function e($t) {
		mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("#'.date('d.m.Y').' %'.date('H:i:s').': <b>'.mysql_real_escape_string($t).'</b>","capitalcity","Игромир","6","1","-1")');
	}

	public function setOnline($online,$uid,$afk,$gj = false)
	{
		if( $gj == true ) {
			$add = 0;
			
			if(time()-$online>=1)
			{
				if(time()-$online < 60) {
					$add += time()-$online;
				}else{
					$add += 60;
				}
			}
			
			//$this->e('test');
			
			echo '['.(time()-$online).']';
			
			$afk = 0;
			if($add > 0)
			{
				$on = mysql_fetch_array(mysql_query('SELECT * FROM `online` WHERE `uid` = "'.$uid.'" LIMIT 1'));
				if(isset($on['id']))
				{
					$mt = 0;
					$lst = time();
					if(date('d',$on['lastUp'])!=date('d',$lst))
					{
						$on['time_today'] = $add;
					}else{
						$on['time_today'] += $add;
					}
					$add = $on['time_all']+$add;
					$afkNow = 0;
					$afkAll = 0;
					if($afk==1)
					{
						$mt = time();
					}
					mysql_query('UPDATE `online` SET `mainTime` = "'.$mt.'",`time_today` = "'.$on['time_today'].'",`lastUp` = "'.$lst.'",`time_all` = "'.$add.'" WHERE `id` = "'.$on['id'].'" LIMIT 1');
				}
			}
		}
	}

	public function mystr($string)
	{
		$str = strtolower($string);
		if(strtolower('S')!='s')
		{
			$ru  = 'АБВГДЕЁЖЗИЙКЛМНОРПСТУФХЦЧШЩЪЬЫЭЮЯ';
			$ru2 = 'абвгдеёжзийклмнорпстуфхцчшщъьыэюя';
			$en  = 'QWERTYUIOPASDFGHJKLZXCVBNM';
			$en2 = 'qwertyuiopasdfghjklzxcvbnm';
			$i = 0;
			while($i<33)
			{
				if(isset($ru[$i]))
				{
					$str = strtr($str,$ru[$i],$ru2[$i]);
				}
				if(isset($en[$i]))
				{
					$str = strtr($str,$en[$i],$en2[$i]);
				}
				$i++;
			}
		}
		return $str;
	}
	
	public function reverse_i($str) 
	{ 
		/*$newstr = '';
		for ($i=1; $i<=strlen($str); $i++) 
		{ 
			$newstr .= substr($str, -$i, 1); 
		} */
		$newstr = $str;
		return $newstr; 
	}
	
	public function antimat($txt) {
		global $u;
		$txt = ' '.$txt.' ';
		//$vc = iconv( 'windows-1251' , 'UTF-8' , 'ВЦ');
		/*if( $u->info['admin'] > 0 ) {
			$i = 0;
			while( $i < count( $this->abuse ) ) {
				$txt = iconv( 'windows-1251' , 'UTF-8' , $txt );
				//
				$word = $this->abuse[$i];
				$word = iconv( 'windows-1251' , 'UTF-8' , $word);
				//
				$txt = str_replace(' #'.$word.'#is '," <i><f c=".$word." />&lt;".$vc."&gt;</i> ",$txt);
				$txt = iconv( 'UTF-8' , 'windows-1251' , $txt );
				$i++;
			}
		}else{*/
			$i = 0;
			while( $i < count( $this->abuse ) ) {
				$txt = str_ireplace(' '.$this->abuse[$i].' ',' <i><f c='.$this->abuse[$i].' />&lt;ВЦ&gt;</i> ',$txt);
				$i++;
			}
		//}
		return $txt;
	}
	
	public function spamFiltr($txt)
	{
		
		$txt = str_replace('ё','е',$txt);
		
		$nospam = 0;
		$txt = str_replace('&gt;','',$txt);
		$txt = str_replace('&lt;','',$txt);
		$txt = str_replace('&quot;','',$txt);
		$txt = $this->mystr($txt);
		$i = 0;
		$j = 0;
		while($i <= count($this->sm))
		{
			if(isset($this->sm[$i]))
			{
				$txt = preg_replace('/:'.$this->sm[$i].':/',"",$txt);
			}
			$i++;
		}
		/* фильтр */
		//$spam = $this->spamtxt;	
		$spam = mysql_fetch_array(mysql_query('SELECT * FROM `spam_word` WHERE `id` = 1 LIMIT 1'));
		$spam = $spam['data'];
		//	
		$testEN = preg_replace('/[^a-z]*/i', '', $txt);
		$testEN2 = $txt;
		
		$testEN2 = str_replace('&quot;','',$testEN2);
		$testEN2 = str_replace('&nbsp;','',$testEN2);		
		$testEN2 = str_replace('а','a',$testEN2);
		$testEN2 = str_replace('б','b',$testEN2);
		$testEN2 = str_replace('с','c',$testEN2);
		$testEN2 = str_replace('в','b',$testEN2);
		$testEN2 = str_replace('е','e',$testEN2);
		$testEN2 = str_replace('т','t',$testEN2);
		$testEN2 = str_replace('о','o',$testEN2);
		$testEN2 = str_replace('р','p',$testEN2);
		$testEN2 = str_replace('м','m',$testEN2);
		$testEN2 = str_replace('н','h',$testEN2);
		$testEN2 = str_replace('у','y',$testEN2);
		$testEN2 = str_replace('к','k',$testEN2);
		$testEN2 = str_replace('и','u',$testEN2);
		$testEN2 = str_replace('х','x',$testEN2);
		$testEN2 = str_replace('я','9',$testEN2);
		$testEN2 = str_replace('()','o',$testEN2);
		$testEN2 = str_replace('0','o',$testEN2);
		$testEN2 = preg_replace('/[^a-z]*/i', '', $testEN2);		
		$testRU = preg_replace('/[^а-я]*/i', '', $txt);
		
		$testRU2 = $txt;
		$testRU2 = str_replace('a','а',$testRU2);
		$testRU2 = str_replace('b','б',$testRU2);
		$testRU2 = str_replace('c','с',$testRU2);
		$testRU2 = str_replace('b','в',$testRU2);
		$testRU2 = str_replace('e','е',$testRU2);
		$testRU2 = str_replace('t','т',$testRU2);
		$testRU2 = str_replace('o','о',$testRU2);
		$testRU2 = str_replace('p','р',$testRU2);
		$testRU2 = str_replace('m','м',$testRU2);
		$testRU2 = str_replace('h','н',$testRU2);
		$testRU2 = str_replace('y','у',$testRU2);
		$testRU2 = str_replace('k','к',$testRU2);
		$testRU2 = str_replace('x','х',$testRU2);
		$testRU2 = str_replace('u','и',$testRU2);
		$testRU2 = str_replace('()','о',$testRU2);
		$testRU2 = str_replace('0','о',$testRU2);
		$testRU2 = preg_replace('/[^а-я]*/i', '', $testRU2);
		$i = 0; $spe = explode('|',$spam);
		while($i<=count($spe))
		{
			if(isset($spe[$i]) && $spe[$i]!='' && $spe[$i] != 'xcombats.com')
			{
				if( stristr($testEN,$spe[$i]) == true ) {
					$nospam .= '%'.$spe[$i];
				}elseif( stristr($testRU,$spe[$i]) == true ) {
					$nospam .= '%'.$spe[$i];
				}
				/*if(preg_match("/".($spe[$i])."/i",($testEN)))
				{
					$nospam .= '%'.$spe[$i];
				}elseif(preg_match("/".($spe[$i])."/i",($testRU)))
				{
					$nospam .= '%'.$spe[$i];
				}*//*elseif(preg_match("/".($spe[$i])."/i",($testRU2)))
				{
					$nospam .= '%'.$spe[$i];
				}elseif(preg_match("/".($spe[$i])."/i",($testEN2)))
				{
					$nospam .= '%'.$spe[$i];
				}*/
			}
			$i++;
		}
		return $nospam;
	}
	
	public function getSmiles($txt,$lg)
	{
		global $c,$u;
		$i = 0;
		$j = 0;
		$txt = ' '.$txt;
		$h = explode(':',$txt);
		$user_sm = array();
		if($lg!=false)
		{			
			$k = 0;
			$ke = explode(',',$u->info['add_smiles']);
			while($k<count($ke))
			{
					if(isset($ke[$k]) && $ke[$k]!='')
				{
					$user_sm[$ke[$k]] = 1;
				}					
				$k++;
			}
		}
		while($i <= count($this->sm))
		{
			if(isset($h[$i]))
			{
				if(isset($this->sm[$h[$i]]) || isset($user_sm[$h[$i]]))
				{
					if($j<3)
					{
						$clk = 'onClick=\"top.addSm(\''.$h[$i].'\');\" style=\"cursor:pointer;\"';
						if(isset($user_sm[$h[$i]]))
						{
							$clk = 'title=\"Именной смайлик\"';
						}
						$h[$i] = '*not_dbl_ponts*<img '.$clk.' src=\"http://img.xcombats.com/i/smile/'.$h[$i].'.gif\">*not_dbl_ponts*';
						$j++;
					}
				}
			}
			$i++;
		}
		$txt = implode($h,':');
		$txt = str_replace(':*not_dbl_ponts*','',$txt);
		$txt = str_replace('*not_dbl_ponts*:','',$txt);
		$txt = str_replace('*not_dbl_ponts*','',$txt);
		$txt = trim($txt,' ');
		return $txt;
	}
	
	public function __clone()
	{
		trigger_error('Дублирование не допускается.', E_USER_ERROR);
	}
}

$filter = new Filter();
?>