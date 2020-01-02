<?
if(!defined('GAME'))
{
	die();
}

	if($tr['var_id'] > 4) {
		//маг
		$itmadd = array(
			//3053 - вечность , 3052 - МР 1000
			//суперсвиток
			0 => array(2142,2143,2144,2141,3052),
			//простые свитки
			1 => array(3053,3043,2545,2709,874,2391),
			//гарантированные
			2 => array(3044)
		);
		$tr['var_id'] -= 4;
	}else{
		//воин
		$itmadd = array(
			//суперсвиток
			0 => array(911,1172,2143,2144,1173),
			//простые свитки
			1 => array(3043,2545,2709,874,2391),
			//гарантированные
			2 => array(3044)
		);
	}
	
	if($tr['var_id'] > 4) {
		//Маг
		$i3[9] = $this->addItem(4676,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login']);
	}else{
		//Воин
		$i3[9] = $this->addItem(2870,$this->info['id'],'|notr=1|nosale=1|sudba='.$this->info['login']);
	}
	
	if($tr['var_id'] == 1) {
		// 200 екр. - 1 суперсвиток 0/15, 2 простых 0/75 + Эссенция Здоровья 0/1 
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
			$io = 'Какие-то предметы добавлены к Вам в инвентарь...';	
		}
		
	}elseif($tr['var_id'] == 2) {
		// 400 екр. - 2 суперсвитка 0/15, 4 простых 0/75 + Эссенция Здоровья 0/2 + новогодний эликсир 0/7
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
			$io = 'Какие-то предметы добавлены к Вам в инвентарь...';	
		}
		
	}elseif($tr['var_id'] == 3) {
		// 600 екр. - 2 суперсвитка 0/20, 4 простых 0/100 + Эссенция Здоровья 0/2 + новогодний эликсир 0/10 
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
			$io = 'Какие-то предметы добавлены к Вам в инвентарь...';	
		}

	}elseif($tr['var_id'] == 4) {
		// 1000 екр. - 3-4 суперсвитка 0/20, 4 простых 0/100 + Эссенция Здоровья 0/3 + новогодний эликсир 0/10
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
			$io = 'Какие-то предметы добавлены к Вам в инвентарь...';	
		}
		
	}
	unset($itmadd,$i3,$i4);
?>