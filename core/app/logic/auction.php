<?

namespace Logic;

use \Core\View as view;

class Auction {
	
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
		}elseif( \Core\User::$room['name'] != 'Аукцион' ) {
			echo 'Вы находитесь в другой локации.';
		}elseif( stristr($_SERVER['HTTP_ACCEPT'],'application/json') == true ) {
			\Core\User::$stats = \Core\User::getStats( \Core\User::$data['id'] );
			echo self::getJSON();
		}else{
			\Core\User::$stats = \Core\User::getStats( \Core\User::$data['id'] );
			echo self::getHTML();
		}
	}
	
	/*
	@ Метод выводящий HTML-контент на сторону пользователя
	@ Через конкретный шаблонизатор
	*/
	public static function getHTML() {		
		//PC версия главной страницы
		return view::generateTpl( 'auction', array(
			'title'		=> COPY . ' :: Аукцион',
			
			//Передаем данные пакетов
			'user'		=> \Core\User::$data,
			'stats'		=> \Core\User::$stats,
			'room'		=> \Core\User::$room,
			
			'OK'		=> OK,
			'copy'		=> COPY,
			'rights'	=> RIGHTS,
			
			'ver'		=> '1.2.5'
		) );
	}
	
	/*
	@ Метод выводящий JSON-контент на сторону пользователя
	@ Информация берется из переменной self::$JSON
	*/
	public static function getJSON() {
		$r = array( 'type' => $_GET['type'],'item_data' => array( 'i' => 0 , 'g' => array() ) );
		
		//Информация
		$r['money'] = 0+\Core\User::$data['money'];
		$r['massaNow'] = 0+\Core\User::$data['massaNow'];
		$r['massaMax'] = 0+\Core\User::$data['massaNow'];
		$r['timeGo'] = 0+\Core\User::$stats['timeGo'];
		$r['timeGoL'] = 0+\Core\User::$stats['timeGoL'];
		$r['timeNow'] = OK;
		//
			
		if(isset($_GET['buy_item']) && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK) {
			//Меняем ставку
			$itm_auc = \Core\Database::query( 'SELECT * FROM `items_auc` WHERE `id` = :item_id AND `time_end` = 0 AND `time` > :time LIMIT 1' , array(
				'item_id' => (int)$_GET['buy_item'],
				'time'	=> OK - 86400
			) , true );
			if(isset($itm_auc['id'])) {
				$price = round($_GET['kr'],2);
				$price_min = round(($itm_auc['price'] + $itm_auc['price']*0.01),2);
				$price_max = round(($itm_auc['price'] * 2),2);
				if( $itm_auc['uid'] == \Core\User::$data['id'] ) {
					$r['error'] = 6;
				}elseif( $itm_auc['user_buy'] != \Core\User::$data['id'] ) {
					if( $price > \Core\User::$data['money'] ) {
						$r['error'] = 5; //У вас недостаточно денег
					}elseif( $price - $price_min >= 0 ) {
						//if( $price - $price_max < 1 ) {
							/*
							Забираем кр. , если были ставки до этого возвращаем кр. на почту персонажу который делал ставку
							*/
							if( $itm_auc['user_buy'] > 0 ) {
								//Выдаем кр. обратно прошлому игроку который сделал ставку
								$user = \Core\Database::query( 'SELECT `id`,`login` FROM `users` WHERE `id` = :uid LIMIT 1' , array(
									'uid' => $itm_auc['user_buy']
								) , true );
								if( isset($user['id']) ) {
									\Core\Chat::send_system( $user['login'] , 'Персонаж <b>' . \Core\User::$data['login'] . '</b> перебил вашу ставку на Аукционе, предмет &quot;'.$itm_auc['name'].'&quot;. Сумма вашей ставки отправлена к вам на почту.' );
									//
									$tmgo = 0;
									\Core\Database::query( 'INSERT INTO `items_users` (`item_id`,`1price`,`uid`,`delete`,`lastUPD`) VALUES (
										1220 , :money , :uid , 0 , :time
									)' , array(
										'uid'	=> '-51' . $user['id'],
										'money'	=> $itm_auc['price'],
										'time'	=> OK + $tmgo * 60
									));
									$txt = 'Деньги от <b>'.$itm_auc['login'].'</b>: '.$itm_auc['price'].' кр. Прибытие: '.date('d.m.Y H:i',(OK + $tmgo*60)).'';
									\Core\Database::query( 'INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES 
									( :user_to , :user_from , :time , :money , :txt )' , array(
										'user_from' => $itm_auc['uid'],
										'user_to'	=> $user['id'],
										'time'		=> OK,
										'txt' 		=> $txt,
										'money'		=> $itm_auc['price']
									));
									$txt = 'Деньги к <b>'.$user['login'].'</b>: '.$itm_auc['price'].' кр. Прибытие: '.date('d.m.Y H:i',(OK + $tmgo*60)).'';
									\Core\Database::query( 'INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES 
									( :user_from , :user_to , :time , :money , :txt )' , array(
										'user_from' => $itm_auc['uid'],
										'user_to'	=> $user['id'],
										'time'		=> OK,
										'txt' 		=> $txt,
										'money'		=> $tim_auc['price']
									));
									//
								}
							}
							
							//Забираем деньги у текущего игрока и записываем в тело аукциона
							\Core\User::$data['money'] -= $price;
							\Core\Database::query( 'UPDATE `users` SET `money` = :money WHERE `id` = :uid LIMIT 1', array(
								'uid' 	=> \Core\User::$data['id'],
								'money'	=> \Core\User::$data['money']
							) );
							//
							$itm_auc['price'] = $price;
							$itm_auc['login_buy'] = \Core\User::$data['login'];
							$itm_auc['user_buy'] = \Core\User::$data['id'];
							$itm_auc['time'] += 15*60; //+15 мин к аукциону
							//
							\Core\Database::query( 'UPDATE `items_auc` SET `price` = :price , `time` = :time , `login_buy` = :login_buy , `user_buy` = :user_buy WHERE `id` = :id LIMIT 1', array(
								'id' 	=> $itm_auc['id'],
								'price'	=> $itm_auc['price'],
								'time'	=> $itm_auc['time'],
								'login_buy'	=> $itm_auc['login_buy'],
								'user_buy'	=> $itm_auc['user_buy']
							) );					
							//
							//
							$r['info'] = true;
							//
						//}else{
						//	$r['error'] = 4; //Превышена максимальная ставка в 2 раза от текущей ставки
						//}
					}else{
						$r['error'] = 3; //Минимальная ставка - 1% от текущей ставки
					}
				}else{
					$r['error'] = 2;
				}
			}else{
				$r['error'] = 1;
			}
		}elseif(isset($_GET['add_item']) && \Core\User::$data['invBlock'] < OK && \Core\User::$data['allLock'] < OK) {
			//Добавляем предмет на аукцион (от игрока в аукцион)
			$itm_user = \Core\Database::query( 'SELECT `a`.* , `b`.* , `a`.`id` AS `uiid` FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`inOdet` = 0 AND `a`.`uid` = :uid AND `a`.`id` = :item_id AND `a`.`delete` = 0 AND `a`.`gift` = "" AND `a`.`data` NOT LIKE "%sudba=%" AND `a`.`data` NOT LIKE "%zazuby=%" AND `a`.`data` NOT LIKE "%|notransfer=%" AND `a`.`inTransfer` = 0 AND `a`.`inShop` = 0 LIMIT 1' , array(
				'uid' => \Core\User::$data['id'],
				'item_id' => (int)$_GET['add_item']
			) , true );
			if( isset($itm_user['id']) ) {
				$price = round($_GET['price'],2);
				//
				if( \Core\User::$stats['st']['silver'] >= 4 ) {
					$price_com = round(($price/100*2.5),2);
				}else{
					$price_com = round(($price/100*10),2);
				}
				//
				if( $price < 1 ) {
					$r['error'] = 2;
				}elseif( $price > 1000000 ) {
					$r['error'] = 3;
				}elseif( $price_com > \Core\User::$data['money'] ) {
					$r['error'] = 4;
				}elseif( \Core\User::$data['align'] == 2 ) {
					$r['error'] = 5;
				}else{
					//
					$count = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_users` WHERE `uid` = :uid AND `item_id` = :item_id AND ( ( `inGroup` > 0 AND `inGroup` = :group ) OR ( `inGroup` = 0 AND `id` = :id ) ) AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1', array(
						'uid' => \Core\User::$data['id'],
						'id' => $itm_user['uiid'],
						'item_id' => $itm_user['id'],
						'group' => $itm_user['inGroup']
					) , true );
					$count = $count[0];
					//
					$upd = \Core\Database::query( 'UPDATE `items_users` SET `uid` = 0, `inGroup` = :id WHERE `uid` = :uid AND `item_id` = :item_id AND ( ( `inGroup` > 0 AND `inGroup` = :group ) OR ( `inGroup` = 0 AND `id` = :id ) ) AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0' ,
						array(
							'uid' => \Core\User::$data['id'],
							'id' => $itm_user['uiid'],
							'item_id' => $itm_user['id'],
							'group' => $itm_user['inGroup']
						)
					);
					//
					if($upd == true) {
						$itm_user_data = \Core\Item::lookStats($itm_user['data']);
						//
						\Core\Database::query( 'INSERT INTO `items_auc`
						(
							`login`,`uid`,`item_id`,`items_id`,`time`,`time_end`,`img`,`name`,`level`,`x`,`price_start`,`price`,`massa`,`iznosNOW`,`iznosMAX`,`user_buy`,`type`,`delete`
						) VALUES (
							:login , :uid , :item_id , :items_id , :time , 0 , :img , :name , :level , :x , :price , :price , :massa , :iznosNOW , :iznosMAX , 0 , :type , 0
						)', array(
							'login'		=> \Core\User::$data['login'],
							'uid'		=> \Core\User::$data['id'],
							'item_id'	=> $itm_user['uiid'],
							'items_id'	=> $itm_user['id'],
							'time'		=> OK,
							'time_end'	=> OK + 86400,
							'img'		=> $itm_user['img'],
							'name'		=> $itm_user['name'],
							'level'		=> $itm_user_data['tr_lvl'],
							'x'			=> (0+$count),
							'price'		=> $price,
							'massa'		=> $itm_user['massa'],
							'iznosNOW'	=> $itm_user['iznosNOW'],
							'iznosMAX'	=> $itm_user['iznosMAX'],
							'type'		=> $itm_user['type']														
						));
						//
						$r['info'] = array(
							'itm_name' => $itm_user['name'],
							'x' => $count,
							'price' => $price,
							'id' => $itm_user['uiid']
						);
						//
					}else{
						$r['error'] = 6;
					}
					//
				}
			}else{
				$r['error'] = 1;
			}
		}elseif(isset($_GET['option_item']) && ($_GET['option_item'] == 1 || $_GET['option_item'] == 2 || $_GET['option_item'] == 3 || $_GET['option_item'] == 4 || $_GET['option_item'] == 5)) {
			//Просматриваем предметы которые находятся в аукционе и в инвентаре игрока
			$id = (int)$_GET['option_item'];
			if( $id == 5 ) {
				//Торги (уникальные вещи)
				$r['item_data']['x'] = 0;
				$pla = \Core\Database::query( 'SELECT * FROM `items_auc` WHERE `time_end` = 0 AND `uniq` = 1 ORDER BY `id` DESC' , array(
				
				) , true , true );
				//
				$pages_all = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_auc` WHERE `time_end` = 0 AND `uniq` = 1 LIMIT 1', array(
					
				) , true );
				//
				$pages_all = $pages_all['i'];
				$pages_all = ceil($pages_all/20); //20 страниц
				//
				$page_now = 0 + round((int)$_GET['page']);				
				if( $page_now < 1 ) {
					$page_now = 1;
				}elseif( $page_now > $pages_all ) {
					$page_now = $pages_all;
				}
				//
				$r['toppages'] = $pages_all;
				$r['page'] = $page_now;
				//
				$i = 0;
				$j = 0;
				while( $i < count($pla) ) {
					//
					if( $i < ($page_now) * 20 && $i >= ($page_now-1) * 20 ) {
						$pl = \Core\Database::query( 'SELECT `a`.* , `b`.* , `a`.`id` AS `uiid` FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`id` = :item_id LIMIT 1' , array(
							'item_id' => $pla[$i]['item_id']
						) , true );
						//
						$r['item_data'][$j] = \Core\Item::infoItem($pl);
						$r['item_data'][$j]['iid'] 	= $pl['item_id'];
						$r['item_data'][$j]['user_ow'] 	= $pla[$i]['login'];
						$r['item_data'][$j]['item_id'] 	= $pla[$i]['items_id'];
						$r['item_data'][$j]['name'] 	= $pl['name'];
						$r['item_data'][$j]['prc'] 	= $pla[$i]['price'];
						$r['item_data'][$j]['prc2'] = round(($pla[$i]['price']+$pla[$i]['price']*0.01),2);
						$r['item_data'][$j]['cid'] 	= $pl['id'];
						$r['item_data'][$j]['aid'] 	= $pla[$i]['id'];
						//
						$r['item_data'][$j]['buy']  = $pla[$i]['login_buy'];
						//$r['item_data'][$j]['buy']  = $pl['login_buy'];
						//
						$r['item_data'][$j]['x'] 	= $count;
						$r['item_data'][$j]['iznosNOW'] = $pl['iznosNOW'];
						$r['item_data'][$j]['iznosMAX'] = $pl['iznosMAX'];
						$r['item_data'][$j]['massa'] 	= $pl['massa'];
						$r['item_data'][$j]['time'] 	= \Core\Utils::timeOut($pla[$i]['time']+86400-time());
						$j++;
						//
						$r['item_data']['x']++;
						$r['item_data']['i']++;
					}
					$i++;
				}
				//
			}elseif( $id == 1 ) {
				//Торги
				$r['item_data']['x'] = 0;
				$pla = \Core\Database::query( 'SELECT * FROM `items_auc` WHERE `time_end` = 0 AND `uniq` = 0 ORDER BY `id` DESC' , array(
				
				) , true , true );
				//
				$pages_all = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_auc` WHERE `time_end` = 0 AND `uniq` = 0 LIMIT 1', array(
					
				) , true );
				//
				$pages_all = $pages_all['i'];
				$pages_all = ceil($pages_all/20); //20 страниц
				//
				$page_now = 0 + round((int)$_GET['page']);				
				if( $page_now < 1 ) {
					$page_now = 1;
				}elseif( $page_now > $pages_all ) {
					$page_now = $pages_all;
				}
				//
				$r['toppages'] = $pages_all;
				$r['page'] = $page_now;
				//
				$i = 0;
				$j = 0;
				while( $i < count($pla) ) {
					//
					if( $i < ($page_now) * 20 && $i >= ($page_now-1) * 20 ) {
						$pl = \Core\Database::query( 'SELECT `a`.* , `b`.* , `a`.`id` AS `uiid` FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`id` = :item_id LIMIT 1' , array(
							'item_id' => $pla[$i]['item_id']
						) , true );
						//
						$r['item_data'][$j] = \Core\Item::infoItem($pl);
						$r['item_data'][$j]['iid'] 	= $pl['item_id'];
						$r['item_data'][$j]['user_ow'] 	= $pla[$i]['login'];
						$r['item_data'][$j]['item_id'] 	= $pla[$i]['items_id'];
						$r['item_data'][$j]['name'] 	= $pl['name'];
						$r['item_data'][$j]['prc'] 	= $pla[$i]['price'];
						$r['item_data'][$j]['prc2'] = round(($pla[$i]['price']+$pla[$i]['price']*0.01),2);
						$r['item_data'][$j]['cid'] 	= $pl['id'];
						$r['item_data'][$j]['aid'] 	= $pla[$i]['id'];
						//
						$r['item_data'][$j]['buy']  = $pla[$i]['login_buy'];
						//$r['item_data'][$j]['buy']  = $pl['login_buy'];
						//
						$r['item_data'][$j]['x'] 	= $count;
						$r['item_data'][$j]['iznosNOW'] = $pl['iznosNOW'];
						$r['item_data'][$j]['iznosMAX'] = $pl['iznosMAX'];
						$r['item_data'][$j]['massa'] 	= $pl['massa'];
						$r['item_data'][$j]['time'] 	= \Core\Utils::timeOut($pla[$i]['time']+86400-time());
						$j++;
						//
						$r['item_data']['x']++;
						$r['item_data']['i']++;
					}
					$i++;
				}
				//
			}elseif( $id == 2 ) {
				//Ставки
				$r['item_data']['x'] = 0;
				$pla = \Core\Database::query( 'SELECT * FROM `items_auc` WHERE `time_end` = 0 AND `user_buy` = :uid ORDER BY `id` DESC' , array(
					'uid' => \Core\User::$data['id']
				) , true , true );
				//
				$pages_all = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_auc` WHERE `time_end` = 0 AND `user_buy` = :uid LIMIT 1', array(
					'uid' => \Core\User::$data['id']
				) , true );
				//
				$pages_all = $pages_all['i'];
				$pages_all = ceil($pages_all/20); //20 страниц
				//
				$page_now = 0 + round((int)$_GET['page']);				
				if( $page_now < 1 ) {
					$page_now = 1;
				}elseif( $page_now > $pages_all ) {
					$page_now = $pages_all;
				}
				//
				$r['toppages'] = $pages_all;
				$r['page'] = $page_now;
				//
				$i = 0;
				$j = 0;
				while( $i < count($pla) ) {
					//
					if( $i < ($page_now) * 20 && $i >= ($page_now-1) * 20 ) {
						$pl = \Core\Database::query( 'SELECT `a`.* , `b`.* , `a`.`id` AS `uiid` FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`id` = :item_id LIMIT 1' , array(
							'item_id' => $pla[$i]['item_id']
						) , true );
						//
						$r['item_data'][$j] = \Core\Item::infoItem($pl);
						$r['item_data'][$j]['iid'] 	= $pl['item_id'];
						$r['item_data'][$j]['user_ow'] 	= $pla[$i]['login'];
						$r['item_data'][$j]['item_id'] 	= $pla[$i]['items_id'];
						$r['item_data'][$j]['name'] 	= $pl['name'];
						$r['item_data'][$j]['prc'] 	= $pla[$i]['price'];
						$r['item_data'][$j]['prc2'] = round(($pla[$i]['price']+$pla[$i]['price']*0.01),2);
						$r['item_data'][$j]['cid'] 	= $pl['id'];
						$r['item_data'][$j]['aid'] 	= $pla[$i]['id'];
						//
						$r['item_data'][$j]['buy']  = $pla[$i]['login_buy'];
						//$r['item_data'][$j]['buy']  = $pl['login_buy'];
						//
						$r['item_data'][$j]['x'] 	= $count;
						$r['item_data'][$j]['iznosNOW'] = $pl['iznosNOW'];
						$r['item_data'][$j]['iznosMAX'] = $pl['iznosMAX'];
						$r['item_data'][$j]['massa'] 	= $pl['massa'];
						$r['item_data'][$j]['time'] 	= \Core\Utils::timeOut($pla[$i]['time']+86400-time());
						$j++;
						//
						$r['item_data']['x']++;
						$r['item_data']['i']++;
					}
					$i++;
				}
				//
			}elseif( $id == 3 ) {
				//Ваши предметы (уже на торгах)
				$r['item_data']['x'] = 0;
				$pla = \Core\Database::query( 'SELECT * FROM `items_auc` WHERE `uid` = :uid AND `time_end` = 0 ORDER BY `id` DESC' , array(
					'uid' => \Core\User::$data['id']
				) , true , true );
				//
				$pages_all = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_auc` WHERE `uid` = :uid AND `time_end` = 0 LIMIT 1', array(
					'uid'		=> \Core\User::$data['id']
				) , true );
				//
				$pages_all = $pages_all['i'];
				$pages_all = ceil($pages_all/20); //20 страниц
				//
				$page_now = 0 + round((int)$_GET['page']);				
				if( $page_now < 1 ) {
					$page_now = 1;
				}elseif( $page_now > $pages_all ) {
					$page_now = $pages_all;
				}
				//
				$r['toppages'] = $pages_all;
				$r['page'] = $page_now;
				//
				$i = 0;
				$j = 0;
				while( $i < count($pla) ) {
					//
					if( $i < ($page_now) * 20 && $i >= ($page_now-1) * 20 ) {
						$pl = \Core\Database::query( 'SELECT `a`.* , `b`.* , `a`.`id` AS `uiid` FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`id` = :item_id LIMIT 1' , array(
							'item_id' => $pla[$i]['item_id']
						) , true );
						//
						$r['item_data'][$j] = \Core\Item::infoItem($pl);
						$r['item_data'][$j]['iid'] 	= $pl['item_id'];
						$r['item_data'][$j]['item_id'] 	= $pla[$i]['items_id'];
						$r['item_data'][$j]['name'] 	= $pl['name'];
						$r['item_data'][$j]['prc'] 	= $pla[$i]['price'];
						$r['item_data'][$j]['cid'] 	= $pl['id'];
						//
						$r['item_data'][$j]['buy']  = $pla[$i]['login_buy'];
						//$r['item_data'][$j]['buy']  = $pl['login_buy'];
						//
						$r['item_data'][$j]['x'] 	= $count;
						$r['item_data'][$j]['iznosNOW'] = $pl['iznosNOW'];
						$r['item_data'][$j]['iznosMAX'] = $pl['iznosMAX'];
						$r['item_data'][$j]['massa'] 	= $pl['massa'];
						$r['item_data'][$j]['time'] 	= \Core\Utils::timeOut($pla[$i]['time']+86400-time());
						$j++;
						//
						$r['item_data']['x']++;
						$r['item_data']['i']++;
					}
					$i++;
				}
				//
			}elseif( $id == 4 ) {
				//Выставить предметы на торги								
				$r['item_data']['x'] = 0;
				$pl = \Core\Database::query( 'SELECT `a`.* , `b`.* , `a`.`id` AS `uiid` FROM `items_users` AS `a` LEFT JOIN `items_main` AS `b` ON `a`.`item_id` = `b`.`id` WHERE `a`.`inOdet` = 0 AND `a`.`uid` = :uid AND `a`.`delete` = 0 AND `a`.`gift` = "" AND `a`.`data` NOT LIKE "%sudba=%" AND `a`.`data` NOT LIKE "%zazuby=%" AND `a`.`data` NOT LIKE "%|notransfer=%" AND `a`.`inTransfer` = 0 AND `a`.`inShop` = 0 ORDER BY `a`.`lastUPD` DESC' , array(
					'uid' => \Core\User::$data['id']
				) , true , true );
				//
				$pages_all = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_users` WHERE `inOdet` = 0 AND `uid` = :uid AND `delete` = 0 AND `inGroup` = 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `gift` = "" AND `data` NOT LIKE "%|zazuby=%" AND `data` NOT LIKE "%|notransfer=%" AND `data` NOT LIKE "%|sudba=%" LIMIT 1', array(
					'uid'		=> \Core\User::$data['id']
				) , true );
				$pages_group = \Core\Database::query( 'SELECT COUNT(*) AS `i` FROM `items_users` WHERE `inOdet` = 0 AND `uid` = :uid AND `delete` = 0 AND `inGroup` > 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `gift` = "" AND `data` NOT LIKE "%|zazuby=%" AND `data` NOT LIKE "%|notransfer=%" AND `data` NOT LIKE "%|sudba=%" GROUP BY `inGroup` , `item_id` LIMIT 1', array(
					'uid'		=> \Core\User::$data['id']
				) , true );
				//
				$pages_all = $pages_all['i'];
				$pages_group = $pages_group['i'];
				//
				$pages_all += $pages_group;
				$pages_all = ceil($pages_all/20); //20 страниц
				//
				$page_now = 0 + round((int)$_GET['page']);				
				if( $page_now < 1 ) {
					$page_now = 1;
				}elseif( $page_now > $pages_all ) {
					$page_now = $pages_all;
				}
				//
				$r['toppages'] = $pages_all;
				$r['page'] = $page_now;
				//
				$i = 0;
				$j = 0;
				$stst = array();
				while( $i < count($pl) ) {
					//
					//if( $j < ($page_now) * 20 && $j >= ($page_now-1) * 20 ) {
					if(!isset($stst[$pl[$i]['item_id']])) {
						$stst[$pl[$i]['item_id']] = \Core\Database::query( 'SELECT `id` FROM `items_auc_ban` WHERE `item_id` = :id LIMIT 1' , array(
							'id' => $pl[$i]['item_id']
						) , true );
					}
					
					if(!isset($stst[$pl[$i]['item_id']]['id'])) {
						//
						if( $pl[$i]['inGroup'] == 0 || !isset($r['item_data']['g'][$pl[$i]['item_id']][$pl[$i]['inGroup']]) ) {
							$r['item_data'][$j] = \Core\Item::infoItem($pl[$i]);
							$r['item_data'][$j]['iid'] 	= $pl[$i]['item_id'];
							$r['item_data'][$j]['prc'] 	= $pl[$i]['1price'];
							$r['item_data'][$j]['cid'] 	= $pl[$i]['id'];
							$r['item_data'][$j]['x'] 	= $count;
							$j++;
						}
						//
						$r['item_data']['x']++;
						$r['item_data']['i']++;
					//}
						if( $pl[$i]['inGroup'] > 0 ) {
							$r['item_data']['g'][$pl[$i]['item_id']][$pl[$i]['inGroup']]++;
						}
					}
					$i++;
				}
				$pages_all = 1;
				$r['toppages'] = $pages_all;
				$r['page'] = $page_now;
				//
			}
		}
						
		return \Core\Utils::jsonencode( $r );
	}
	
}

?>