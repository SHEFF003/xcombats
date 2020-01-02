<?
if(!defined('GAME'))
{
	die();
}

$uen = $u->info['enemy']; //на кого используем прием

if(isset($_POST['useon']))
{
	$ue = $this->ue;
	if(isset($ue['id']))
	{
		$uen = $ue['id'];
	}else{
		$uen = 0;	
	}
}
if($pl['id']==9999){
//Магическое исцеление
}elseif($pl['id']==181){
//лечение [7]
		$hpadd = 183;
}elseif($pl['id']==182){
//лечение [8]
		$hpadd = 219;
}elseif($pl['id']==183){ 
//лечение [9]
		$hpadd = 263;
}elseif($pl['id']==184){
//лечение [10]
		$hpadd = 316;
}elseif($pl['id']==185){
//лечение [11]
		$hpadd = 380;
}
	//добавляем НР к цели
	if($hpadd>0)
	{
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicRegen($ue,$hpadd,5,$pl,$eff);	
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
	//отнимаем НР у противника
	if($hpmin>0)
	{
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicAtack($ue,$hpmin,5,$pl,$eff);	
		}else{
			$cup = true; //не удалось использовать прием	
		}
	}
?>