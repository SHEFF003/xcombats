<?
if(!defined('GAME'))
{
	die();
}

$uen = $u->info['enemy']; //�� ���� ���������� �����

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
//���������� ���������
}elseif($pl['id']==181){
//������� [7]
		$hpadd = 183;
}elseif($pl['id']==182){
//������� [8]
		$hpadd = 219;
}elseif($pl['id']==183){ 
//������� [9]
		$hpadd = 263;
}elseif($pl['id']==184){
//������� [10]
		$hpadd = 316;
}elseif($pl['id']==185){
//������� [11]
		$hpadd = 380;
}
	//��������� �� � ����
	if($hpadd>0)
	{
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicRegen($ue,$hpadd,5,$pl,$eff);	
		}else{
			$cup = true; //�� ������� ������������ �����	
		}
	}
	//�������� �� � ����������
	if($hpmin>0)
	{
		if($uen>0 && $btl->stats[$btl->uids[$uen]]['hpNow']>0)
		{
			$this->magicAtack($ue,$hpmin,5,$pl,$eff);	
		}else{
			$cup = true; //�� ������� ������������ �����	
		}
	}
?>