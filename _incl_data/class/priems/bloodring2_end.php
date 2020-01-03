<?
if(!defined('GAME'))
{
	die();
}
if($ple['file_finish']=='bloodring2_end' && $this->users[$i]['team'] == $this->info['team_win'])
{	
	if($this->info['razdel'] == 5) {
		$i1k = 0;
		if($ple['x'] > 6) {
			$ple['x'] = 6;
		}
		while($i1k < $ple['x']) {
			$u->addItem(3136,$this->users[$i]['id'],'|sudba='.$this->users[$i]['login']);
			$i1k++;
		}
		if($ple['x'] > 1) {
			$ple['xz'] = ' (x'.$ple['x'].')';
		}else{
			$ple['xz'] = '';
		}
		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$i]['city']."','0','','".$this->users[$i]['login']."','Вы получили предмет &quot;<b>Кровавый Рубин</b>".$ple['xz']."&quot;','-1','6','0')");
	}
}
?>