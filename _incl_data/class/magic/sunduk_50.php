<?
if(!defined('GAME'))
{
	die();
}	
	if($tr['var_id'] != '') {
		 
		$i4 = 0;
		$i5 = explode('@',$tr['var_id']);
		while( $i4 < count($i5) ) {
			$i3 = explode('-',$i5[$i4]);
			if( $i3 == 'ekr' ) {
				//������ ���.				
			}elseif( $i3[0] == 'artefact' ) {
				//������ ��������� ��������.	
				if( rand(0,100) <= $i3[1] ) {
					$io = '�� ���������� ��������� �������� � �����-�� ��������...';
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
						$this->addItem($arts_1,$this->info['id'],'|sroknext=1|nosale=1|srok='.(1*86400).'',NULL,75);
					}
				}
			}else{
				//������ �������
				$this->addItem($i3[0],$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,$i3[1]);
			}
			$i4++;
		}
		
		if($io == '') {
			$io .= '�����-�� �������� ��������� � ��� � ���������...';	
		}
		
	}
	unset($i5,$i3,$i4);
?>