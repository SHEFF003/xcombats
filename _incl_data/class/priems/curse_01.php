<?
if( !defined('GAME') ) { die(); }
/*
	���������� ��������: ��������� �������
	������������ ���� ����� ���� �������� �������� �������������� ������������ ����������. ���� ��������� ���� � ����, 5% ����������� ������������ ���������.
	�������� � ���������� 5 ������������, 5 ��������, 5 ��������, 5 ����, 5 ���������� �� 5 ��������.
	���� ������������: 5%
*/

if($e['bm_a1']=='curse_01'){
	if(rand(0,10000)<500){
		//������� ���� ���������� �������
		//$hpmin = rand(5,10);
		//$hpmin = $priem->testPower($this->stats[$this->uids[$uid1]],$this->stats[$this->uids[$uid2]],$hpmin,3,2);	
		//$hpmin = round($hpmin);
		//if($hpmin < 0) { $hpmin = 0; }
		//$hp2 = $this->stats[$this->uids[$uid2]]['hpNow'];
		//
		////������ ����� ������
		//
		//$hp2 -= $hpmin;
		//if($hp2<0)
		//{
		//	$hp2 = 0;
		//}elseif($hp2>$this->stats[$this->uids[$uid2]]['hpNow'])
		//{
		//	$hp2 = $this->stats[$this->uids[$uid2]]['hpNow'];
		//}
		//
		//$this->takeExp($u->info['id'],$hpmin,$uid1,$uid2);
		//
		////�������� ��
		//$this->users[$this->uids[$uid2]]['hpNow'] = $hp2;
		//$this->stats[$this->uids[$uid2]]['hpNow'] = $hp2;
		//mysql_query('UPDATE `stats` SET `hpNow` = '.$hp2.' WHERE `id` = "'.$uid2.'" LIMIT 1');
		//
		////������� � ��� ���
		//$vLog = 'time1='.time().'||s1='.$this->users[$this->uids[$uid1]]['sex'].'||t1='.$this->users[$this->uids[$uid1]]['team'].'||login1='.$this->users[$this->uids[$uid1]]['login'].'||s2='.$this->users[$this->uids[$uid2]]['sex'].'||t2='.$this->users[$this->uids[$uid2]]['team'].'||login2='.$this->users[$this->uids[$uid2]]['login'].'';
		//$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
		//if($hpmin>0)
		//{
		//	$hpmin = '-'.$hpmin;
		//}else{
		//	$hpmin = '--';
		//}
		//$mas1['text'] = '{tm1} {u2} ������� ����������� �� ����������� �������� &quot;<b>'.$itm['name'].'</b>&quot;. <b title=���&nbsp;�����:&nbsp;����� ><font color=#0000FF>'.$hpmin.'</font></b> ['.ceil($hp2).'/'.$this->stats[$this->uids[$uid2]]['hpAll'].']';	
		//$this->add_log($mas1);
	}
}


?>