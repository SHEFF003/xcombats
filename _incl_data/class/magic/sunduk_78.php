<?
if(!defined('GAME'))
{
	die();
}	
	if($tr['var_id'] != '') {
		 
		$io = '';
		
		/*
			1. �������� �������� ������ ��������� - 1% , ��������� , �� ��������� , ������ , 3 ��. ���� ��������
			2. �������� ���� 5 ���. ������, ���� ������ +200% ����� �� 3 ���� (��� ���������)
			3. 1 �� 4 ���������� +15 (���.)
			4. ��� 20 ��.
		*/
		$trgos = false;
		
		$key1 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4460 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		$key2 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4461 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		$key3 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4462 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		$key4 = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 4463 AND `uid` = '.$this->info['id'].' AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
		
		if( isset($key1['id']) && isset($key2['id']) && isset($key3['id']) && isset($key4['id']) ) {
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = '.$key1['id'].' OR `id` = '.$key2['id'].' OR `id` = '.$key3['id'].' OR `id` = '.$key4['id'].' LIMIT 4');
			$trgos = true;
		}
		
		if( $trgos == true ) {
			if( rand(0,100) == 57 || $this->info['admin'] > 0 ) {
				//������ ��������
				$io .= '<i>��������� ��������</i>, ';
				$arts_1 = array();
				$arts_lvl = $this->info['level'];
				if( $arts_lvl < 4 ) {
					$arts_lvl = 4;
				}elseif( $arts_lvl > 10 ) {
					$arts_lvl = 10;
				}
				$sp1 = mysql_query('SELECT `items_id` FROM `items_main_data` WHERE `data` LIKE "%|art=%" AND `data` LIKE "%tr_lvl='.$arts_lvl.'%"');
				while( $pl1 = mysql_fetch_array($sp1) ) {
					$arts_1[] = $pl1['items_id'];
				}
				$arts_1 = $arts_1[rand(0,count($arts_1)-1)];
				if( $arts_1 > 0 ) {
					$this->addItem($arts_1,$this->info['id'],'|nosale=1|srok='.(3*86400).'',NULL,50);
				}
				//echo '['.$arts_1[rand(0,count($arts_1)-1)].'!'.count($arts_1).'!'.$arts_lvl.']';
			}
			
			if( rand(0,1) == 1 ) {
				//������ ��� +5 ���.
				$io .= '<i>��� �� 5 ���.</i>, ';
				$this->addItem(4465,$this->info['id'],'|sudba='.$this->info['login']);
			}else{
				//������ ������ +200%
				$io .= '<i>������ +100% �����</i>, ';
				$this->addItem(4466,$this->info['id'],'|sudba='.$this->info['login']);
			}
			
			$elik_1 = array(
				4037,4040,4038,4039
			);
			$elik_1 = $elik_1[rand(0,3)];
			if( $elik_1 > 0 ) {
				//������ 1 �� 4 ������
				$io .= '<i>������� +15 ������</i>, ';
				$this->addItem($elik_1,$this->info['id'],'|nosale=1|sudba='.$this->info['login'],NULL,1);
			}
			
			//������ ��� �� 20 ��.
			$io .= '<i>��� �� 20 ��.</i> ';
			$this->addItem(4464,$this->info['id'],'|sudba='.$this->info['login']);
		}else{
			$no_open_itm = true;		
			$this->error = '��������� 4 ��������� ����� � ���� �����.';
		}
		
	}
	unset($i5,$i3,$i4);
?>