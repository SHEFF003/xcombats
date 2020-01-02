<?php
die();
if(!defined('GAME')){
	die();
}
$StaticMSG = array(
	'your-position'=>iconv("WINDOWS-1251", "UTF-8", 'Ваше местоположение'),
	'move-bk'=>iconv("WINDOWS-1251", "UTF-8", 'Проход через &quot;<b>Бойцовский клуб</b>&quot;'),
	'move-zalu4'=>iconv("WINDOWS-1251", "UTF-8", 'Проход через &quot;<b>Зал паладинов</b>&quot;'),
	'move-map_halls'=>iconv("WINDOWS-1251", "UTF-8", 'Проход через &quot;<b>Залы</b>&quot;'),
	'move-to-sek4'=>iconv("WINDOWS-1251", "UTF-8", 'Проход через &quot;<b>Рыцарский</b>&quot; или &quot;<b>Торговый</b>&quot; зал'),
	'move-sek5'=>iconv("WINDOWS-1251", "UTF-8", 'Проход через &quot;<b>Торговый зал</b>&quot;'),
	'move-sek6'=>iconv("WINDOWS-1251", "UTF-8", 'Проход через &quot;<b>Рыцарский зал</b>&quot;'),
);
$Response = array('status'=>'update');
switch($u->room['file']){
	case'bk':// Бойцовский клуб
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig',
				'left'=>'241',
				'top'=>'128',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_bk','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_klub1','params'=>thisInfRm('1.180.0.15', NULL, true)),
				array('id'=>'map_klub2','params'=>thisInfRm('1.180.0.220', NULL, true)),
				array('id'=>'map_klub3','params'=>thisInfRm('1.180.0.2', NULL, true)),
				array('id'=>'map_klub4','params'=>thisInfRm('1.180.0.4', NULL, true)),
				array('id'=>'map_klub5','params'=>thisInfRm('1.180.0.5', NULL, true)),
				array('id'=>'map_klub6','params'=>thisInfRm('1.180.0.7', NULL, true)),
				array('id'=>'map_klub7','params'=>thisInfRm('1.180.0.9', NULL, true))
			),
			'buttons'=>array(
				array(iconv("WINDOWS-1251", "UTF-8", 'Возврат'),'main.php?homeworld=true'),
				array(iconv("WINDOWS-1251", "UTF-8", 'Казино'),'main.php?loc=1.180.0.225'),
				array(iconv("WINDOWS-1251", "UTF-8", 'Карта клуба'),'main.php?clubmap=true'),
				array(iconv("WINDOWS-1251", "UTF-8", 'Форум'),"window.open('http://" . $c['forum'] . "/', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')"),
				array(iconv("WINDOWS-1251", "UTF-8", 'Подсказка'),"window.open('/encicl/help/top1.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')")
			)
		);
	break;
	case'zv1':// Зал Воинов 1
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig',
				'left'=>'154',
				'top'=>'148',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_bk','params'=>thisInfRm('1.180.0.3', NULL, true)),
				array('id'=>'map_klub1','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub2','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub3','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub4','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_klub5','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub6','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub7','params'=>array(false, $StaticMSG['move-bk']))
			)
		);
	break;
	case'zv2':// Зал Воинов 2
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig',
				'left'=>'395',
				'top'=>'142',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_bk','params'=>thisInfRm('1.180.0.3', NULL, true)),
				array('id'=>'map_klub1','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub2','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub3','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_klub4','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub5','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub6','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub7','params'=>array(false, $StaticMSG['move-bk']))
			)
		);
	break;
	case'zv3':// Зал Воинов 3
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig',
				'left'=>'337',
				'top'=>'79',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_bk','params'=>thisInfRm('1.180.0.3', NULL, true)),
				array('id'=>'map_klub1','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub2','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub3','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub4','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub5','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_klub6','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub7','params'=>array(false, $StaticMSG['move-bk']))
			)
		);
	break;
	case'zv4':// Будуар
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig',
				'left'=>'139',
				'top'=>'79',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_bk','params'=>thisInfRm('1.180.0.3', NULL, true)),
				array('id'=>'map_klub1','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub2','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub3','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub4','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub5','params'=>array(false, $StaticMSG['move-bk'])),
				array('id'=>'map_klub6','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_klub7','params'=>array(false, $StaticMSG['move-bk']))
			)
		);
	break;
	case'zalu':// Залы
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig1',
				'left'=>'337',
				'top'=>'117',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_halls','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_zalu3','params'=>array(false, $StaticMSG['move-zalu4'])),
				array('id'=>'map_zalu4','params'=>thisInfRm('1.180.0.16', NULL, true)),
				array('id'=>'map_zalu6','params'=>array(false, $StaticMSG['move-zalu4'])),
				array('id'=>'map_zalu7','params'=>thisInfRm('1.180.0.3', NULL, true))
				
			)
		);
	break;
	case'zalu_pal':// Залы
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig1',
				'left'=>'163',
				'top'=>'28',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_halls','params'=>thisInfRm('1.180.0.15', NULL, true)),
				array('id'=>'map_zalu3','params'=>thisInfRm('1.180.0.xx', NULL, true)),
				array('id'=>'map_zalu4','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_zalu6','params'=>thisInfRm('1.180.0.0', NULL, true)),
				array('id'=>'map_zalu7','params'=>array(false, $StaticMSG['move-map_halls']))
			)
		);
	break;
	case'bk2':// Залы
		$Response = array(
			'status'=>'success',
			'location'=>array(
				'bg'=>'navig3',
				'left'=>'162',
				'top'=>'125',
				'name'=>iconv("WINDOWS-1251", "UTF-8", $u->room['name']),
				'tgo'=>($tmGo*10),
				'tgo1'=>($tmGol*10)
			),
			'goto'=>array(
				array('id'=>'map_2stair','params'=>array(false, $StaticMSG['your-position'])),
				array('id'=>'map_sec1','params'=>thisInfRm('1.180.0.3', NULL, true)),
				array('id'=>'map_sec2','params'=>thisInfRm('1.180.0.xx', NULL, true)),
				array('id'=>'map_sec3','params'=>array(false, $StaticMSG['move-sek5'])),
				array('id'=>'map_sec4','params'=>array(false, $StaticMSG['move-to-sek4'])),
				array('id'=>'map_sec5','params'=>thisInfRm('1.180.0.221', NULL, true)),
				array('id'=>'map_sec6','params'=>thisInfRm('1.180.0.224', NULL, true)),
				array('id'=>'map_sec7','params'=>array(false, $StaticMSG['move-sek6']))
			)
		);
	break;
	
}
$Response;