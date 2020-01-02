<?

namespace Logic;

use \Core\View as view;

class Comission {
	
	/*
	@ Базовый метод начал генирации модуля
	@ Здесь происходит определение типа данных, а так-же
	@ проверка возможности просмотра данного модуля
	@ все поддключаемые классы должны быть НЕОБХОДИМЫМИ!
	*/
	public static function defaultAction() {
		
		//Подключаем пакеты
		\Core\User::connect();
		\Core\User::room();
		
		if (\Core\User::$data == false ) {
			//Нет доступа, персонаж не авторизирован или заблокирован
			echo 'Авторизируйтесь через <a href="/index.php">главную страницу</a>.';
		}elseif( \Core\User::$data['battle'] > 0 ) {
			header('location:main.php?inv');	
		}elseif( \Core\User::$room['name'] != 'Комиссионный магазин' ) {
			echo 'Вы находитесь в другой локации.';
		}elseif( stristr($_SERVER['HTTP_ACCEPT'],'application/json') == true ) {
			echo self::getJSON();
		}else{
			echo self::getHTML();
		}
	}
	
	/*
	@ Метод выводящий HTML-контент на сторону пользователя
	@ Через конкретный шаблонизатор
	*/
	public static function getHTML() {		
		//PC версия главной страницы
		return view::generateTpl( 'comission', array(
			'title'		=> COPY . ' :: Комиссионный магазин',
			
			//Передаем данные пакетов
			'user'		=> \Core\User::$data,
			'stats'		=> \Core\User::$stats,
			'room'		=> \Core\User::$room,
			
			'OK'		=> OK,
			'copy'		=> COPY,
			'rights'	=> RIGHTS,
			
			'ver'		=> '1.1.3'
		) );
	}
	
	/*
	@ Метод выводящий JSON-контент на сторону пользователя
	@ Информация берется из переменной self::$JSON
	*/
	public static function getJSON() {
		$r = array( 'type' => $_GET['type'],'it' => array( 'i' => 0 , 'g' => array() ) );
		
		//Информация
		$r['money'] = 0+\Core\User::$data['money'];
		//
		$ves = \Core\User::ves(\Core\User::$data['id']);
		//
		$r['massaNow'] = 0+$ves['now'];
		$r['massaMax'] = 0+$ves['max'];
		$r['timeGo'] = 0+\Core\User::$stats['timeGo'];
		$r['timeGoL'] = 0+\Core\User::$stats['timeGoL'];
		$r['timeNow'] = OK;
		//
		if( isset($_GET['newprice_item']) && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK ) {
			//Обновляем стоимость предмета
			$comiss = 0.10; //Комиссия
			//
			$itm_user = \Core\Database::query( 'SELECT * FROM `items_com` WHERE `item_id` = :id AND `uid` = :uid AND `city` =:city AND `delete` = "0" LIMIT 1' , array(
					'id'	=> $_GET['newprice_item'],
					'uid'	=> \Core\User::$data['id'],
					'city'	=> \Core\User::$data['city']
			) , true );
			//
			$itm_useri = \Core\Database::query( 'SELECT *, `id` AS `uiid` FROM `items_users` WHERE `id` = :id AND `uid` = :uid AND `gift` = "" AND `data` NOT LIKE "%sudba=%" AND `data` NOT LIKE "%zazuby=%" AND `data` NOT LIKE "%|notransfer=%" AND `inOdet` = 0 AND `delete` = 0 AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1' , array(
					'id'	=> $itm_user['item_id'],
					'uid'	=> 0
			) , true );
			//
			$itm_main = \Core\Database::query( 'SELECT * FROM `items_main` WHERE `id` = :id LIMIT 1' , array(
				'id'	=> $itm_user['items_id']
			) , true );
			//
			if( $itm_useri['1price'] == 0 ) {
				$itm_useri['1price'] = $itm_main['price1'];
			}
			if( $itm_useri['1price'] == 0 ) {
				$itm_useri['1price'] = 1;
			}
			//
			$prc = round($_GET['newprice_price'],2);
			//
			if(!isset($itm_user['id']) || !isset($itm_useri['id']) || !isset($itm_main['id'])) {
				$r['newprice_error'] = 1;
			}elseif( $prc < 1 ) {
				$r['newprice_error'] = 2;
			}elseif( $prc > ( $itm_user['group'] * $itm_useri['1price'] ) * 10 ) {
				$r['newprice_error'] = 3;
			}elseif( $prc < round( (((( $itm_user['group'] * $itm_useri['1price'] ) / $itm_main['iznosMAXi'] ) * $itm_useri['iznosMAX']) / 2) , 2) ) {
				$r['newprice_error'] = 4;
				$r['newprice_error_min'] = round( (((( $itm_user['group'] * $itm_useri['1price'] ) / $itm_main['iznosMAXi'] ) * $itm_useri['iznosMAX']) / 2) , 2);
			}else{
				$itm_user['price'] = $prc;
				//
				$r['newprice_price_comission'] = $comiss;
				$r['newprice_item'] = $itm_user['id'];
				$r['newprice_uiid'] = $itm_user['item_id'];
				$r['newprice_name'] = $itm_user['name'];
				$r['newprice_x']	= $itm_user['group'];
				$r['newprice_price']= $itm_user['price'];
				//
				\Core\User::$data['money'] -= $comiss; //$comiss Комиссия
				//
				\Core\Database::query( 'UPDATE `users` SET `money` = :money WHERE `id` = :uid LIMIT 1' ,
					array(
						'uid' => \Core\User::$data['id'],
						'money' => \Core\User::$data['money']
					)
				);
				//
				\Core\Database::query( 'UPDATE `items_com` SET `price` = :newprice WHERE `id` = :id LIMIT 1', array(
					'id'		=> $itm_user['id'],
					'newprice'	=> $itm_user['price']
				) );
			}
			//
		}elseif( isset($_GET['pick_item']) && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK ) {
			//Забираем предмет
			$itm_user = \Core\Database::query( 'SELECT * FROM `items_com` WHERE `city` = :city AND `id` = :id AND `uid` = :uid AND `delete` = "0" LIMIT 1' , array(
					'id'	=> $_GET['pick_item'],
					'uid'	=> \Core\User::$data['id'],
					'city'	=> \Core\User::$data['city']
			) , true );
			//
			if(!isset($itm_user['id'])) {
				$r['pick_error'] = 1;
			}else{
				$r['pick_item'] = $itm_user['id'];
				$r['pick_uiid'] = $itm_user['item_id'];
				$r['pick_name'] = $itm_user['name'];
				$r['pick_x']	= $itm_user['group'];
				\Core\Database::query( 'UPDATE `items_com` SET `delete` = :time WHERE `id` = :id LIMIT 1', array(
					'id'	=> $itm_user['id'],
					'time'	=> OK
				) );
				\Core\Database::query( 'UPDATE `items_users` SET `inGroup` = 0 , `uid` = :uid , `lastUPD` = :time WHERE `id` = :id OR ( `inGroup` = :id AND `item_id` = :item_id AND `uid` = 0 )', array(
					'id'	=> $itm_user['item_id'],
					'item_id' => $itm_user['items_id'],
					'uid' 	=> $itm_user['uid'],
					'time'	=> OK
				) );
			}
			//
		}elseif( isset($_GET['give_item']) && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK ) {
			//Сдаем предмет
			$itm_user = \Core\Database::query( 'SELECT *, `id` AS `uiid` FROM `items_users` WHERE `id` = :id AND `uid` = :uid AND `gift` = "" AND `data` NOT LIKE "%sudba=%" AND `data` NOT LIKE "%zazuby=%" AND `data` NOT LIKE "%|notransfer=%" AND `inOdet` = 0 AND `delete` = 0 AND `inTransfer` = 0 AND `inShop` = 0 LIMIT 1' , array(
					'id'	=> $_GET['give_item'],
					'uid'	=> \Core\User::$data['id']
			) , true );
			//
			$comiss = 1; //Комиссия в кр.
			$prc = round($_GET['give_price'],2); //Какую сумму выставляем предмету
			//
			$itm_x = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_users` WHERE `inGroup` = :group AND `inGroup` > 0 AND `item_id` = :item_id AND `uid` = :uid AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1' , array(
				'item_id'	=> $itm_user['item_id'],
				'group' 	=> $itm_user['inGroup'],
				'uid'		=> \Core\User::$data['id']
			) , true );
			if( $itm_x['i'] == 0 ) {
				$itm_x['i'] = 1;
			}
			//
			$itm_main = \Core\Database::query( 'SELECT * FROM `items_main` WHERE `id` = :id LIMIT 1' , array(
				'id'	=> $itm_user['item_id']
			) , true );
			if( $itm_user['1price'] == 0 ) {
				$itm_user['1price'] = $itm_main['price1'];
			}
			if( $itm_user['1price'] == 0 ) {
				$itm_user['1price'] = 1;
			}
			//
			if( !isset($itm_user['id']) ) {
				$r['give_error'] = 1;
			}elseif( $comiss > \Core\User::$data['money'] && true == false ) {
				$r['give_error'] = 2;
			}elseif( \Core\User::$data['align'] == 2 ) {
				$r['give_error'] = 3;
			}elseif( \Core\User::$data['level'] < 4 ) {
				$r['give_error'] = 4;
			}elseif( $prc > 1000000 ) {
				$r['give_error'] = 5;
			}elseif( $prc < 1 ) {
				$r['give_error'] = 6;
			}elseif( $prc > ( $itm_x['i'] * $itm_user['1price'] ) * 10 && true == false ) {
				$r['give_error'] = 7;
			}elseif( $prc < round( (((( $itm_x['i'] * $itm_user['1price'] ) / $itm_main['iznosMAXi'] ) * $itm_user['iznosMAX']) / 2) , 2) && true == false ) {
				$r['give_error'] = 8;
				$r['give_error_min'] = round( (((( $itm_x['i'] * $itm_user['1price'] ) / $itm_main['iznosMAXi'] ) * $itm_user['iznosMAX']) / 2) , 2);
			}else{
				//Все окей
				$r['give_item'] = $itm_user['id'];
				$r['give_name'] = $itm_main['name'];
				$r['give_x']	= $itm_x['i'];
				$r['give_price'] = $prc;
				$r['give_price_comission'] = $comiss.'.00';
				//
				$upd = \Core\Database::query( 'UPDATE `items_users` SET `uid` = 0, `inGroup` = :id WHERE `uid` = :uid AND `item_id` = :item_id AND ( ( `inGroup` > 0 AND `inGroup` = :group ) OR ( `inGroup` = 0 AND `id` = :id ) ) AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0' ,
					array(
						'uid' => \Core\User::$data['id'],
						'id' => $itm_user['uiid'],
						'item_id' => $itm_main['id'],
						'group' => $itm_user['inGroup']
					)
				);
				if( $upd == true ) {
					//
					\Core\User::$data['money'] -= $comiss; //$comiss Комиссия
					//
					\Core\Database::query( 'UPDATE `users` SET `money` = :money WHERE `id` = :uid LIMIT 1' ,
						array(
							'uid' => \Core\User::$data['id'],
							'money' => \Core\User::$data['money']
						)
					);
					//
					/*
					=========================================
					Типы предметов: /////////////////////////
					=========================================
					31 - руна
					32 - ресурсы
					33 - мусор
					34 - прочее
					35 - сумка
					36 - усиление
					37 - упаковка
					38 - подарок
					39 - подарок (требует упаковку)
					40 - книжный прием
					41 - приглашение
					42 - билет
					43 - слот смены
					44 - пергамент (с текстом)
					45 - сумка
					46 - заточка
					47 - усиление 1
					48 - усиление 2 (временное)
					49 - корм для животного						
					60 - бумага
					61 - чек
					62 - чарка
					63 - открытка
					64 - какие-то подарки (пока не придумал)
					*/
					$point = 24;
					$arr_point = array(
						1 => 10,
						2 => 24, // венки на голову
						3 => 11,
						4 => 7,
						5 => 8,
						6 => 9,
						7 => 24, // плащи
						8 => 12,
						9 => 15,
						10 => 16,
						11 => 17,
						12 => 6,
						13 => 14,
						14 => 13,
						15 => 5,
						
						18 => 0,
						19 => 1,
						20 => 2,
						21 => 3,
						22 => 4,
						
						29 => 18,
						30 => 19,
						
						31 => 21,
						62 => 20,
						
						32 => 22,
						
						49 => 23
					);
					if( isset($arr_point[$itm_main['type']]) ) {
						$point = $arr_point[$itm_main['type']];
					}
					//
					$itm_po = \Core\Item::lookStats( $itm_user['data'] );
					//
					\Core\Database::query( 'INSERT INTO `items_com` (
						`uid`,`item_id`,`items_id`,`name`,`level`,`img`,`massa`,`point`,`price`,`time`,`city`,`buy`,`delete`,`iznosNow`,`iznosMax`,`tr_align`,`group`
					) VALUES (
						:uid , :item_id , :items_id , :name , :level , :img , :massa , :point , :price , :time , :city , 0 , 0 , :iznosNOW , :iznosMAX , :align , :group
					)' , array(
						'uid' => \Core\User::$data['id'],
						'item_id' => $itm_user['uiid'],
						'items_id' => $itm_main['id'],
						'name' => $itm_main['name'],
						'level' => $itm_po['tr_lvl'],
						'img' => $itm_main['img'],
						'massa' => ($itm_x['i'] * $itm_main['massa']),
						'point' => $point,
						'price' => $r['give_price'],
						'time' => OK,
						'city' => \Core\User::$data['city'],
						'align' => $itm_po['tr_align'],
						'iznosNOW' => $itm_user['iznosNOW'],
						'iznosMAX' => $itm_user['iznosMAX'],
						'group' => $r['give_x']
					));
				}else{
					$r['give_error'] = 9;
				}
			}
			
			//
		}elseif( isset($_GET['buy_item']) && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK ) {
			//Продать предмет
			$itm = \Core\Database::query( 'SELECT * FROM `items_com` WHERE `id` = :cid AND `city` = :city AND `items_id` = :items_id AND `delete` = 0 LIMIT 1' , array(
					'cid' 		=> $_GET['buy_item'],
					'items_id'	=> $_GET['items_id'],
					'city' 		=> \Core\User::$data['city']
			) , true );
			$itm_user = \Core\Database::query( 'SELECT * FROM `items_users` WHERE `id` = :id AND `uid` = 0 LIMIT 1' , array(
					'id'	=> $itm['item_id']
			) , true );
			$user = \Core\Database::query( 'SELECT * FROM `users` WHERE `id` = :id ORDER BY `id` ASC LIMIT 1' , array(
				'id' => $itm['uid']
			) , true );
			if( !isset($itm['id']) || !isset($itm_user['id']) ) {
				//Предмет не найден, возможно его кто-то уже купил
				if( isset($itm['id']) ) {
					\Core\Database::query( 'UPDATE `items_com` SET `buy` = :uid, `delete` = :delete WHERE `id` = :id LIMIT 1' , array(
						'id' => $itm['id'],
						'uid' => 111,
						'delete' => OK
					));
				}
				$r['buy_error'] = 1;				
			}elseif( \Core\User::$data['align'] == 2 ) {
				//Хаосникам нельзя пользоваться комиссионным магазином
				$r['buy_error'] = 3;
			}elseif( \Core\User::$data['level'] < 1 ) {
				//Персонажам ниже 1-го уровня запрещено пользоваться комиссионным магазином
				$r['buy_error'] = 4;
			}elseif( $itm['price'] > \Core\User::$data['money']) {
				//У вас недостаточно денег
				$r['buy_error'] = 2;
			}else{
				//Без ошибок, купили предмет
				\Core\Database::query( 'UPDATE `items_com` SET `buy` = :uid, `delete` = :delete WHERE `id` = :id LIMIT 1' , array(
					'id' => $itm['id'],
					'uid' => \Core\User::$data['id'],
					'delete' => OK
				));
				//
				\Core\Database::query( 'UPDATE `items_users` SET `uid` = :uid, `lastUPD` = :time, `inGroup` = 0 WHERE `id` = :id LIMIT 1' , array(
					'id' => $itm['item_id'],
					'uid' => \Core\User::$data['id'],
					'time' => OK
				));
				//
				\Core\Database::query( 'UPDATE `items_users` SET `uid` = :uid, `lastUPD` = :time, `inGroup` = 0 WHERE `inGroup` = :id AND `uid` = 0 LIMIT ' . $itm['group'] , array(
					'id' => $itm['item_id'],
					'uid' => \Core\User::$data['id'],
					'time' => OK
				));
				\Core\User::$data['money'] -= $itm['price'];
				\Core\Database::query( 'UPDATE `users` SET `money` = :money WHERE `id` = :id ORDER BY `id` ASC LIMIT 1' , array(
					'id' => \Core\User::$data['id'],
					'money' => \Core\User::$data['money']
				));
				//
				$post_money = round($itm['price']*0.9,2);
				if( $itm['group'] > 1 ) {
					$itm['name'] .= ' (x'.$itm['group'].')';
				}
				\Core\Chat::send_system( $user['login'] , 'Персонаж <b>' . \Core\User::$data['login'] . '</b> приобрел вашу вещь &quot;'.$itm['name'].'&quot; из комиссионного магазина за '.$post_money.' кр. (+1 кр. комиссионных). Деньги отправлены к вам на почту.' );
				$post_money += 1; //Комиссионные
				//
				$tmgo = 1;
				\Core\Database::query( 'INSERT INTO `items_users` (`item_id`,`1price`,`uid`,`delete`,`lastUPD`) VALUES (
					1220 , :money , :uid , 0 , :time
				)' , array(
					'uid'	=> '-51' . $user['id'],
					'money'	=> $post_money,
					'time'	=> OK + $tmgo * 60
				));
				$txt = 'Деньги от <b>'.\Core\User::$data['login'].'</b>: '.$post_money.' кр. Прибытие: '.date('d.m.Y H:i',(OK + $tmgo*60)).'';
				\Core\Database::query( 'INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES 
				( :user_to , :user_from , :time , :money , :txt )' , array(
					'user_from' => \Core\User::$data['id'],
					'user_to'	=> $user['id'],
					'time'		=> OK,
					'txt' 		=> $txt,
					'money'		=> $post_money
				));
				$txt = 'Деньги к <b>'.$user['login'].'</b>: '.$post_money.' кр. Прибытие: '.date('d.m.Y H:i',(OK + $tmgo*60)).'';
				\Core\Database::query( 'INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES 
				( :user_from , :user_to , :time , :money , :txt )' , array(
					'user_from' => \Core\User::$data['id'],
					'user_to'	=> $user['id'],
					'time'		=> OK,
					'txt' 		=> $txt,
					'money'		=> $post_money
				));
				//
				$r['buy_error'] = 0;
				$r['buy_item'] = $itm['id'];
				$r['buy_name'] = $itm['name'];
				$r['buy_price'] = $itm['price'];
				$r['buy_massa'] = $itm['massa'];
				//
				$itm_last = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_com` WHERE `city` = :city AND `items_id` = :items_id AND `delete` = 0 LIMIT 1' , array(
					'items_id'	=> $_GET['items_id'],
					'city' 		=> \Core\User::$data['city']
				) , true );
				$r['buy_last'] = $itm_last['i'];
			}
		}elseif($_GET['point'] == 'give' && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK) {
			//Положить предмет
			$pl = \Core\Database::query( 'SELECT `id`,`inGroup`,`item_id` FROM `items_users` WHERE `uid` = :uid AND `gift` = "" AND `data` NOT LIKE "%sudba=%" AND `data` NOT LIKE "%zazuby=%" AND `data` NOT LIKE "%|notransfer=%" AND `inOdet` = 0 AND `delete` = 0 AND `inTransfer` = 0 AND `inShop` = 0 ORDER BY `lastUPD` DESC' , array(
					'uid' 	=> \Core\User::$data['id']
			) , true , true );
			//
			$i = 0; $j = 0;
			while( $i < count($pl) ) {
				if( !isset($r['it']['g'][$pl[$i]['item_id']][$pl[$i]['inGroup']]) ) {
					$itm = \Core\Item::getItemUser( $pl[$i]['id'] );
					$itm = \Core\Item::infoItem( $itm );
					$itm['iid'] = $pl[$i]['item_id'];
					$r['it'][] = $itm;
					$j++;
				}
				if( $pl[$i]['inGroup'] > 0 ) {
					$r['it']['g'][$pl[$i]['item_id']][$pl[$i]['inGroup']]++;
				}
				$i++;
			}
			$r['it']['i'] = $j;
			//
		}elseif($_GET['point'] == 'pick' && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK) {
			//Забрать предмет раздел
			$pl = \Core\Database::query( 'SELECT * FROM `items_com` WHERE `uid` = :uid AND `city` = :city AND `delete` = 0 ORDER BY `time` DESC' , array(
					'uid' 		=> \Core\User::$data['id'],
					'city' 		=> \Core\User::$data['city']
			) , true , true );
			//
			$i = 0;
			//
			while( $i < count($pl) ) {
				$itm = \Core\Item::getItemUser( $pl[$i]['item_id'] );
				if( !isset($r['it']['n']) ) {
					$r['it']['n'] = $itm['name'];
				}
				$itm = \Core\Item::infoItem( $itm );
				$itm['iid'] = $pl[$i]['items_id'];
				$itm['prc'] = $pl[$i]['price'];
				$itm['cid'] = $pl[$i]['id'];
				$itm['x'] = $pl[$i]['group'];
				$itm['timeend'] = date('d.m.Y H:i',( $pl[$i]['time'] + 86400 * 14 ));
				$r['it'][] = $itm;
				$i++;
			}
			//
			$r['it']['l'] = $_GET['lookIt'];
			$r['it']['i'] = $i;
		}elseif( $_GET['type'] == 1 ) {
			//Просмотр предметов
			$pl = \Core\Database::query( 'SELECT * FROM `items_com` WHERE `items_id` = :items_id AND `city` = :city AND `delete` = 0 ORDER BY `price` ASC' , array(
					'items_id' 	=> $_GET['lookIt'],
					'city' 		=> \Core\User::$data['city']
			) , true , true );
			//
			$i = 0;
			//
			while( $i < count($pl) ) {
				$itm = \Core\Item::getItemUser( $pl[$i]['item_id'] );
				if( !isset($r['it']['n']) ) {
					$r['it']['n'] = $itm['name'];
				}
				$itm = \Core\Item::infoItem( $itm );
				$itm['iid'] = $pl[$i]['items_id'];
				$itm['prc'] = $pl[$i]['price'];
				$itm['cid'] = $pl[$i]['id'];
				$itm['x'] = $pl[$i]['group'];
				$r['it'][] = $itm;
				$i++;
			}
			//
			$r['it']['l'] = $_GET['lookIt'];
			$r['it']['i'] = $i;
		}elseif( $_GET['type'] == 0 ) {
			//Просмотр раздела
			$pl = \Core\Database::query( 'SELECT * FROM `items_com` WHERE `point` = :point AND `city` = :city AND `delete` = 0 GROUP BY `items_id` ORDER BY `time` DESC' , array(
					'point' 	=> $_GET['point'],
					'city' 		=> \Core\User::$data['city']
			) , true , true );
			//
			$i = 0;
			while( $i < count($pl) ) {
				//
				$col = \Core\Database::query( 'SELECT COUNT(`id`) AS `i` FROM `items_com` WHERE `items_id` = :iid AND `city` = :city AND `delete` = 0', array(
					'iid' => $pl[$i]['items_id'],
					'city' => \Core\User::$data['city']
				),true); //количество предметов
				$col = $col['i'];
				//
				$prs1 = \Core\Database::query( 'SELECT `price` AS `i` FROM `items_com` WHERE `items_id` = :iid AND `city` = :city AND `delete` = 0 ORDER BY `price` ASC', array(
					'iid' => $pl[$i]['items_id'],
					'city' => \Core\User::$data['city']
				),true); //Минимальная цена предмета
				$prs1 = $prs1['i'];
				//
				$prs2 = \Core\Database::query( 'SELECT `price` AS `i` FROM `items_com` WHERE `items_id` = :iid AND `city` = :city AND `delete` = 0 ORDER BY `price` DESC', array(
					'iid' => $pl[$i]['items_id'],
					'city' => \Core\User::$data['city']
				),true); //Максимальная цена предмета
				$prs2 = $prs2['i'];
				//
				$izns1 = \Core\Database::query( 'SELECT `iznosNow` AS `i`,`iznosMax` AS `j` FROM `items_com` WHERE `items_id` = :iid AND `city` = :city AND `delete` = 0 ORDER BY `iznosMax` ASC', array(
					'iid' => $pl[$i]['items_id'],
					'city' => \Core\User::$data['city']
				),true); //Минимальная цена предмета
				//
				$izns2 = \Core\Database::query( 'SELECT `iznosNow` AS `i`,`iznosMax` AS `j` FROM `items_com` WHERE `items_id` = :iid AND `city` = :city AND `delete` = 0 ORDER BY `iznosMax` DESC', array(
					'iid' => $pl[$i]['items_id'],
					'city' => \Core\User::$data['city']
				),true); //Максимальная цена предмета
				//
				$r['it'][] = array(
					$pl[$i]['id'],
					$pl[$i]['items_id'],
					array($izns1['i'],$izns2['i']),
					array($izns1['j'],$izns2['j']),
					$pl[$i]['name'],
					$pl[$i]['img'],
					$pl[$i]['massa'],
					array($prs1,$prs2),
					$col,
					$pl[$i]['level']
				);
				$i++;
			}
			$r['it']['i'] = $i;
		}
				
		return \Core\Utils::jsonencode( $r );
	}
	
}

?>