<?
	if(!defined('GAME'))
	{
		die();
	}
	
	//воин
	$itmadd = array(
		//суперсвиток
		0 => array(911,1172,2143,2144,1173),
		//простые свитки
		1 => array(3043,2545,2709,874,2391),
		//гарантированные
		2 => array(3044)
	);
	
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
	
	if( $u->info['level'] > 8 ) {	
		$i3[0] = $this->addItem(2143,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,($u->info['level']*2-10));
		$i3[1] = $this->addItem(2144,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,($u->info['level']*2-10));
	}
	
	$i3[3] = $this->addItem(4020,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,($u->info['level']*2-10));
	$i3[4] = $this->addItem(4035,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,($u->info['level']*2-10));
	$i3[5] = $this->addItem(3101,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,($u->info['level']*2-10));	
	$i3[6] = $this->addItem(4041,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,($u->info['level']*2-10));
	$i3[7] = $this->addItem(4541,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,1);
	$i3[8] = $this->addItem(4542,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,1);
	$i3[9] = $this->addItem(3048,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,1);
	$i3[10] = $this->addItem(3041,$this->info['id'],'|nosale=1|notr=1|sudba='.$this->info['login'],NULL,1);
	mysql_query('UPDATE `items_users` SET `gift` = "Ангел",`gtxt1` = "С Днем Рождения! Поздравляем! Удачи в боях и жизни!" WHERE
	`id` = "'.$i3[8].'" OR `id` = "'.$i3[9].'" OR `id` = "'.$i3[10].'"
	LIMIT 1');
	 
	if($io == '') {
		$io = 'Какие-то предметы добавлены к Вам в инвентарь...';	
	}
	unset($itmadd,$i3,$i4);
?>