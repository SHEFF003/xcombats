<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ������� �����
*/
$pvr = array();

	//
	if( $btl->stats[$btl->uids[$u->info['id']]]['hpNow'] < round($btl->stats[$btl->uids[$u->info['id']]]['hpAll']/100*34) ) {
		$prv['upd'] = $this->rezadEff($u->info['id'],'wis_fire');
		if( $prv['upd'] == false ) {
			$cup = true;
		}else{
			$prv['color2'] = '000000';
			$prv['text'] = $btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
			$prv['text2'] = '{tm1} '.$prv['text'].'.';
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], 0,
				'<font color^^^^#'.$prv['color2'].'>������� �����</font>',
				$prv['text2'],
				($btl->hodID + 1)
			);	
			//�������� �������
			$this->mintr($pl);
		}
	}else{
		$cup = true;
		echo '<font color=red><b>��� ������������� ������� ������ ������� ������ �������� ������ ���� ���� 33%</b></font>';
	}

unset($pvr);
?>