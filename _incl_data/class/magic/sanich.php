<?
if(!defined('GAME'))
{
	die();
}

	if($tr['var_id'] == 1) {
		//��������� �����
		$pgs = array('all' => 0, 'sudba' => 0);
		$sp_pg = mysql_query('SELECT `id`,`item_id`,`gift` FROM `items_users` WHERE `item_id` >= 3143 AND `item_id` <= 3192 AND `delete` = 0 AND `inSHop` = 0 AND `inTransfer` = 0 AND `uid` = "'.$this->info['id'].'"');
		while($pl_pg = mysql_fetch_array($sp_pg)) {
			$pg_id = $pl_pg['item_id']-3142;
			if(!isset($pgs[$pg_id])) {
				$pgs[$pg_id] = $pl_pg['id'];
				if($pl_pg['gift'] != '') {
					$pgs['sudba']++;
				}
				$pgs['all']++;
			}
		}
		$lk = 1;
		while($lk <= 50) {
			if($pgs[$lk] < 1) {
				$npgs .= ', '.$lk;
			}
			$lk++;
		}
		
		if($pgs['all'] < 50) {
			$npgs = ltrim($npgs,', ');
			$io .= '�� ������� ������� �����, ���������� ������� ���� �������. ['.$pgs['all'].'/50]<br>����������� ��������: '.$npgs;
			$no_open_itm = true;
		}else{
			//�������� ��������
			$pgs['delete'] = '';
			$sp_pg = 1;
			while($sp_pg <= 50) {
				$pgs['delete'] .= '`id` = "'.$pgs[$sp_pg].'" OR ';
				$sp_pg++;
			}
			
			if($pgs['delete'] != '') {
				$pgs['delete'] = rtrim($pgs['delete'],' OR ');
				$pgs['delete'] = '('.$pgs['delete'].') AND `uid` = "'.$this->info['id'].'" LIMIT 50';
				mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE '.$pgs['delete']);
			}
			
			$sz = $this->testAction('`vars` = "gold_sanich_bonus" LIMIT 1',1);
			/*if(!isset($sz['id'])) {
				$this->addAction(time(),'gold_sanich_bonus','gold_sanich_bonus');
				//������ �����
				if($pgs['sudba'] > 0) {
					//�����������
					$this->addItem(3198,$this->info['id'],'|sudba='.$this->info['login']);
				}else{
					//�� �����������
					$this->addItem(3198,$this->info['id']);
				}
				$io .= '�� �������� ������� &quot;������� �����&quot;<br>�� ������� ����� ������ ����� �� ������ ������� ��������� ������� ����� ������ ���������! ;)';
			}else{
				$sz = $this->testAction('`vars` = "silver_sanich_bonus" LIMIT 1',1);
				if(!isset($sz['id'])) {
					$this->addAction(time(),'silver_sanich_bonus','silver_sanich_bonus');
					//������ �����
					if($pgs['sudba'] > 0) {
						//�����������
						$this->addItem(3197,$this->info['id'],'|sudba='.$this->info['login']);
					}else{
						//�� �����������
						$this->addItem(3197,$this->info['id']);
					}
					$io .= '�� �������� ������� &quot;���������� �����&quot;<br>�� ������� ����� ������ ����� �� ������ ������� ��������� ���������� ����� ������ ���������! ;)';
				}else{*/
					//������ �����
					if($pgs['sudba'] > 0) {
						//�����������
						$this->addItem(3196,$this->info['id'],'|sudba='.$this->info['login']);
					}else{
						//�� �����������
						$this->addItem(3196,$this->info['id']);
					}
					$io .= '�� �������� ������� &quot;��������� �����&quot;';
				//}
			//}

		}
	}else{
		$io .= '������ ������� ������ ������������!';
		$no_open_itm = true;
	}
?>