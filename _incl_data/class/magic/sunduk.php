<?
if(!defined('GAME'))
{
	die();
}

	if($tr['var_id'] > 4) {
		//ìàã
		$itmadd = array(
			//3053 - âå÷íîñòü , 3052 - ÌĞ 1000
			//ñóïåğñâèòîê
			0 => array(2142,2143,2144,2141,3052),
			//ïğîñòûå ñâèòêè
			1 => array(3053,3043,2545,2709,874,2391),
			//ãàğàíòèğîâàííûå
			2 => array(3044)
		);
		$tr['var_id'] -= 4;
	}else{
		//âîèí
		$itmadd = array(
			//ñóïåğñâèòîê
			0 => array(911,1172,2143,2144,1173),
			//ïğîñòûå ñâèòêè
			1 => array(3043,2545,2709,874,2391),
			//ãàğàíòèğîâàííûå
			2 => array(3044)
		);
	}
	
	if($tr['var_id'] > 4) {
		//Ìàã
		$i3[9] = $this->addItem(4676,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login']);
	}else{
		//Âîèí
		$i3[9] = $this->addItem(2870,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login']);
	}
	
	if($tr['var_id'] == 1) {
		// 200 åêğ. - 1 ñóïåğñâèòîê 0/15, 2 ïğîñòûõ 0/75 + İññåíöèÿ Çäîğîâüÿ 0/1 
		$i4 = array(
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)]
		);
		
		$i3 = array();
		
		$i3[0] = $this->addItem($i4[0],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,15);
				
		$i3[1] = $this->addItem($i4[1],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,75);
		$i3[2] = $this->addItem($i4[2],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,75);
				
		$i3[3] = $this->addItem(3044,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,1);	
		
		if($io == '') {
			$io = 'Êàêèå-òî ïğåäìåòû äîáàâëåíû ê Âàì â èíâåíòàğü...';	
		}
		
	}elseif($tr['var_id'] == 2) {
		// 400 åêğ. - 2 ñóïåğñâèòêà 0/15, 4 ïğîñòûõ 0/75 + İññåíöèÿ Çäîğîâüÿ 0/2 + íîâîãîäíèé ıëèêñèğ 0/7
		$i4 = array(
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)]
		);
		
		$i3 = array();
		
		$i3[0] = $this->addItem($i4[0],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,15);
		$i3[1] = $this->addItem($i4[1],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,15);
				
		$i3[2] = $this->addItem($i4[2],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,75);
		$i3[3] = $this->addItem($i4[3],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,75);	
		$i3[4] = $this->addItem($i4[4],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,75);
		$i3[5] = $this->addItem($i4[5],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,75);
		
		$i3[6] = $this->addItem(3044,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,2);	
		
		if($io == '') {
			$io = 'Êàêèå-òî ïğåäìåòû äîáàâëåíû ê Âàì â èíâåíòàğü...';	
		}
		
	}elseif($tr['var_id'] == 3) {
		// 600 åêğ. - 2 ñóïåğñâèòêà 0/20, 4 ïğîñòûõ 0/100 + İññåíöèÿ Çäîğîâüÿ 0/2 + íîâîãîäíèé ıëèêñèğ 0/10 
		$i4 = array(
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)]
		);
		
		$i3 = array();
		
		$i3[0] = $this->addItem($i4[0],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,20);
		$i3[1] = $this->addItem($i4[1],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,20);
				
		$i3[2] = $this->addItem($i4[2],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);
		$i3[3] = $this->addItem($i4[3],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);	
		$i3[4] = $this->addItem($i4[4],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);
		$i3[5] = $this->addItem($i4[5],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);
		
		$i3[6] = $this->addItem(3044,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,2);	
		
		if($io == '') {
			$io = 'Êàêèå-òî ïğåäìåòû äîáàâëåíû ê Âàì â èíâåíòàğü...';	
		}

	}elseif($tr['var_id'] == 4) {
		// 1000 åêğ. - 3-4 ñóïåğñâèòêà 0/20, 4 ïğîñòûõ 0/100 + İññåíöèÿ Çäîğîâüÿ 0/3 + íîâîãîäíèé ıëèêñèğ 0/10
		$i4 = array(
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[0][rand(0,count($itmadd[0])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)],
			$itmadd[1][rand(0,count($itmadd[1])-1)]
		);
		
		$i3 = array();
		
		$i3[0] = $this->addItem($i4[0],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,20);
		$i3[1] = $this->addItem($i4[1],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,20);
		$i3[3] = $this->addItem($i4[2],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,20);
		
		if(rand(0,1000) > 250 && rand(0,1000) < 750) {
			$i3[9] = $this->addItem($i4[3],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,20);
		}
				
		$i3[4] = $this->addItem($i4[4],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);
		$i3[5] = $this->addItem($i4[5],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);	
		$i3[6] = $this->addItem($i4[6],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);
		$i3[7] = $this->addItem($i4[7],$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,100);
		
		$i3[8] = $this->addItem(3044,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login'],NULL,3);	
		
		if($io == '') {
			$io = 'Êàêèå-òî ïğåäìåòû äîáàâëåíû ê Âàì â èíâåíòàğü...';	
		}
		
	}
	unset($itmadd,$i3,$i4);
?>