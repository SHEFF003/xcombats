<?php
# ������ �������� ��
# ������ ����� ������� �� ������������ ������� � ������� 3 �����
# ��� ����� ��� ������ ������ 5 ����� �� ������� ��������.

# �������� IP
function getIPblock() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# ��������� �������� ������������. 

if(!isset($_GET['kill'])) {
	if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' && getIPblock() != '91.228.154.180' ) {	die('Hello pussy!');   }
	if(getIPblock() != $_SERVER['SERVER_ADDR'] && getIPblock() != '127.0.0.1' && getIPblock() != '' && getIPblock() != '91.228.154.180') {
		die(getIPblock().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}


define('GAME', true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
//include('_incl_data/class/__user.php');
//include('_incl_data/class/__dungeon.php');
 
# ������ �������.
function start() {
    # �������� ������� 0.0000
    $mtime = microtime();$mtime = explode(" ",$mtime);$tstart = $mtime[1] + $mtime[0];
    
    # �������� ���� �����.
    # � ������� ��������: ������� ����, ����������� ���� �� ����� ����, ���������� �� ����� �����, ��� ���������� � � �������� �� ��.
    $query = mysql_query(
    "SELECT
		`dn`.time_start as dn_start,
		`dn`.city as city,
		`dn`.id as dn_id,
		`uc`.countUsers as u_count
	FROM `dungeon_now` as `dn`
	LEFT JOIN `stats` as `st` ON `st`.id = `dn`.uid
	LEFT JOIN `users` as `u` ON `u`.id = `dn`.uid
	LEFT JOIN (SELECT dnow, count(id) as countUsers FROM `stats` group by dnow ) as `uc` ON `uc`.dnow = `dn`.id
    
    WHERE
		`st`.dnow > 0 AND
		`st`.dnow != '' AND 
		`u`.online < ".(time()-10800)." AND
		`dn`.time_start < ".(time()-18000)." AND
		`dn`.time_finish = '0' AND `dn`.`bsid` = '0'
	GROUP BY `dn`.id
	ORDER BY `dn`.id DESC;"
    );

    while( $dungeon = mysql_fetch_array( $query ) ) {
		if(isset($dungeon['dn_id']) && $dungeon['dn_id'] != 15) {
			# [1] ���������� ������� � ���������� � ���������� ��� `382` ���������� ���������(���.�������)
			$users = mysql_query('SELECT `id` FROM `stats` WHERE `dnow` = "'.$dungeon['dn_id'].'" LIMIT 10');
			while( $cur = mysql_fetch_array($users) ) {
				mysql_query('UPDATE `stats` SET `dnow` = "0" WHERE `id` = "'.$cur['id'].'" LIMIT 1');
				//
				$podval_room = 382;
				//
				print_r($dungeon);
				if( $dungeon['city'] == 'capitalcity' ) {
					//$podval_room = 372;
					$podval_room = 321;
				}elseif( $dungeon['city'] == 'angelscity' ) {
					$podval_room = 242;
				}
				
				mysql_query('UPDATE `users` SET `room` = "'.$podval_room.'" WHERE `id` = "'.$cur['id'].'" LIMIT 1');
				#echo '��������� ������ �'.$cur['id'].' � ���������� '.$dungeon['dn_id'].' � ����������� � <strong>���������� ���������</strong><br/>';
				mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$cur['id'].'" AND `dn_delete` = "1"');
				# echo '����������� �������� �������� � ������ �'.$cur['id'].' <br/>';
			}
			unset( $cur );
			# [2] ������� �������.
			mysql_query('DELETE FROM `dungeon_obj` WHERE `dn` = "'.$dungeon['dn_id'].'" AND `for_dn` = "0"');
			# echo '������� ������� � ������ �'.$dungeon['dn_id'].'<br/>';

			# [3] ������� ��������.
			mysql_query('DELETE FROM `dungeon_bots` WHERE `dn` = "'.$dungeon['dn_id'].'" AND `for_dn` = "0"');
			# echo '������� �������� � ������ �'.$dungeon['dn_id'].'<br/>';

			# [4] ������� ��������.
			mysql_query('DELETE FROM `dungeon_items` WHERE `dn` = "'.$dungeon['dn_id'].'" AND `for_dn` = "0"');
			# echo '������� �������� � ������ �'.$dungeon['dn_id'].'<br/>';

			# [5] ������� �������� (actions)
			mysql_query('DELETE FROM `dungeon_actions` WHERE `dn` = "'.$dungeon['dn_id'].'"');
			# echo '������� �������� � ������ �'.$dungeon['dn_id'].'<br/>';

			# [6] ��������� ���������� Dungeon_Now - time_finish = time();
			mysql_query('UPDATE `dungeon_now` SET `time_finish` = "'.time().'" WHERE `id` = "'.$dungeon['dn_id'].'" LIMIT 1');
			
			echo '������� ������ �'.$dungeon['dn_id'].'<br/><br/>';
		} else {
			echo '��� ������ ��� ���������.<br/><br/>';
		}
	}
    unset($query,$dungeon,$users);
    
    $mtime = microtime();
    $mtime = explode(" ",$mtime);$mtime = $mtime[1] + $mtime[0];$totaltime = ($mtime - $tstart);
    printf ("�������� ������������� �� %f ������ !", $totaltime);
}

# ��������� ���������� ��������.
start();
