<?php

namespace Core;

class User {
	
	public static $data = false , $stats = false , $room = false;
	
	public static $is = array(
		'exp' => '���������� ���� (%)', 'align_bs' => '��������� ������',
		'nopryh' => '������ ���������', 'puti'=>'������ �����������','align'=>'����������','hpAll'=>'������� ����� (HP)','mpAll'=>'������� ����','enAll'=>'������� �������','sex'=>'���','lvl'=>'�������','s1'=>'����','s2'=>'��������','s3'=>'��������','s4'=>'������������','s5'=>'���������','s6'=>'��������','s7'=>'����������','s8'=>'����','s9'=>'������� ����','s10'=>'��������������','s11'=>'�������','m1'=>'��. ������������ ����� (%)','m2'=>'��. ������ ������������ ����� (%)','m3'=>'��. �������� ����. ����� (%)','m4'=>'��. ����������� (%)','m5'=>'��. ������ ����������� (%)','m6'=>'��. ���������� (%)','m7'=>'��. ����������� (%)','m8'=>'��. ����� ����� (%)','m9'=>'��. ����� ������ ����� (%)','m14'=>'��. ���. ������������ ����� (%)','m15'=>'��. ���. ����������� (%)','m16'=>'��. ���. ����������� (%)','m17'=>'��. ���. ���������� (%)','m18'=>'��. ���. ����� ����� (%)','m19'=>'��. ���. ���������� ������ (%)','m20'=>'��. ����� (%)','a1'=>'���������� �������� ������, ���������','a2'=>'���������� �������� ��������, ��������','a3'=>'���������� �������� ��������, ��������','a4'=>'���������� �������� ������','a5'=>'���������� �������� ����������� ��������','a6'=>'���������� �������� ������','a7'=>'���������� �������� ����������','aall'=>'���������� �������� �������','mall'=>'���������� �������� ������ ������','m2all'=>'���������� �������� ������','mg1'=>'���������� �������� ������ ����','mg2'=>'���������� �������� ������ �������','mg3'=>'���������� �������� ������ ����','mg4'=>'���������� �������� ������ �����','mg5'=>'���������� �������� ������ �����','mg6'=>'���������� �������� ������ ����','mg7'=>'���������� �������� ����� ������','tj'=>'������� �����','lh'=>'������ �����','minAtack'=>'����������� ����','maxAtack'=>'������������ ����','m10'=>'��. �������� �����','m11'=>'��. �������� ����� ������','m11a'=>'��. �������� �����','pa1'=>'��. �������� �������� �����','pa2'=>'��. �������� �������� �����','pa3'=>'��. �������� �������� �����','pa4'=>'��. �������� ������� �����','pm1'=>'��. �������� ����� ����','pm2'=>'��. �������� ����� �������','pm3'=>'��. �������� ����� ����','pm4'=>'��. �������� ����� �����','pm5'=>'��. �������� ����� �����','pm6'=>'��. �������� ����� ����','pm7'=>'��. �������� ����� �����','za'=>'������ �� �����','zm'=>'������ �� ����� ������','zma'=>'������ �� �����','za1'=>'������ �� �������� �����','za2'=>'������ �� �������� �����','za3'=>'������ �� ��������� �����','za4'=>'������ �� �������� �����','zm1'=>'������ �� ����� ����','zm2'=>'������ �� ����� �������','zm3'=>'������ �� ����� ����','zm4'=>'������ �� ����� �����','zm5'=>'������ �� ����� �����','zm6'=>'������ �� ����� ����','zm7'=>'������ �� ����� �����','magic_cast'=>'�������������� ���� �� ���','pza'=>'��������� ������ �� �����','pzm'=>'��������� ������ �� �����','pza1'=>'��������� ������ �� �������� �����','min_heal_proc'=>'������ ������� (%)','notravma'=>'������ �� �����','yron_min'=>'����������� ����','yron_max'=>'������������ ����','zaproc'=>'������ �� ����� (%)','zmproc'=>'������ �� ����� ������ (%)','zm2proc'=>'������ �� ����� ������� (%)','pza2'=>'��������� ������ �� �������� �����','pza3'=>'��������� ������ �� ��������� �����','pza4'=>'��������� ������ �� �������� �����','pzm1'=>'��������� ������ �� ����� ����','pzm2'=>'��������� ������ �� ����� �������','pzm3'=>'��������� ������ �� ����� ����','pzm4'=>'��������� ������ �� ����� �����','pzm5'=>'��������� ������ �� ����� �����','pzm6'=>'��������� ������ �� ����� ����','pzm7'=>'��������� ������ �� ����� �����','speedhp'=>'����������� �������� (%)','speedmp'=>'����������� ���� (%)','tya1'=>'������� �����','tya2'=>'������� �����','tya3'=>'�������� �����','tya4'=>'������� �����','tym1'=>'�������� �����','mg2static_points'=>'������� ������ (������)','tym2'=>'������������� �����','tym3'=>'������� �����','tym4'=>'�������� �����','hpProc'=>'������� ����� (%)','mpProc'=>'������� ���� (%)','tym5'=>'����� �����','tym6'=>'����� ����','tym7'=>'����� �����','min_use_mp'=>'��������� ������ ����','pog'=>'���������� �����','pog2'=>'���������� �����','pog2p'=>'������� ���������� �����','pog2mp'=>'���� ���������� �����','maxves'=>'����������� ������','bonusexp'=>'����������� ���������� ����','speeden'=>'����������� ������� (%)',
	'yza' => '���������� ����������� ����� (%)','yzm' => '���������� ����� ������ (%)','yzma' => '���������� ����� (%)'
	,'yza1' => '���������� �������� ����� (%)','yza2' => '���������� �������� ����� (%)','yza3' => '���������� ��������� ����� (%)','yza4' => '���������� �������� ����� (%)'
	,'yzm1' => '���������� ����� ���� (%)','yzm2' => '���������� ����� ������� (%)','yzm3' => '���������� ����� ���� (%)','yzm4' => '���������� ����� ����� (%)','yzm5' => '���������� ����� (%)','yzm6' => '���������� ����� (%)','yzm7' => '���������� ����� (%)','rep'=> '��������� ������'
	);
	public static $items = array(
		'tr'  => array('sex','align','lvl','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','a1','a2','a3','a4','a5','a6','a7','mg1','mg2','mg3','mg4','mg5','mg6','mg7','mall','m2all','aall','rep', 'align_bs'),
		'add' => array(
		'exp','enemy_am1','hod_minmana','yhod','noshock_voda',
		'yza','yzm','yzma','yza1','yza2','yza3','yza4','yzm1','yzm2','yzm3','yzm4','yzm5','yzm6','yzm7',
		'notuse_last_pr','yrn_mg_first','antishock','nopryh','speed_dungeon','naemnik','mg2static_points','yrnhealmpprocmg3','nousepriem','notactic','seeAllEff','100proboi1','pog2','pog2p','magic_cast','min_heal_proc','no_yv1','no_krit1','no_krit2','no_contr1','no_contr2','no_bl1','no_pr1','no_yv2','no_bl2','no_pr2','silver','pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','yron_min','yron_max','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','hpVinos','mpVinos','mpAll','enAll','hpProc','mpProc','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','m11a','zona','zonb','maxves','minAtack','maxAtack','bonusexp','speeden'),
		'sv' => array('pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','enAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','min_use_mp','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack','speeden')
	);
	
	//���������� ������������
	public static function connect() {		
		self::$data = \Core\Database::query( 'SELECT * FROM `users` WHERE `login` = :login ORDER BY `id` ASC LIMIT 1' , array(
			'login' => \Core\Utils::cookie('login')
		) , true );
		//�������� ������
		if( self::$data['pass'] != \Core\Utils::cookie('pass') ) {
			self::$data = false;
		}
		//�������� ������������
		if( self::$data['banned'] > 0 ) {
			self::$data = false;
		}
		//���������� �����
		if( isset( self::$data['id'] ) ) {
			self::$stats = \Core\Database::query( 'SELECT * FROM `stats` WHERE `id` = :uid LIMIT 1' , array(
				'uid' => self::$data['id']
			) , true );
		}
	}
	
	//������� ��� ���������
	public static function ves( $uid ) {		
		$r = array('now' => 0,'max' => 0);	
		//����� �������
		$r['now'] = \Core\Database::query( 'SELECT SUM(`b`.`massa`) AS `m` FROM `items_users` AS `a` LEFT JOIN `items_main`AS `b` ON `b`.`id` = `a`.`item_id` WHERE `a`.`uid` = :uid AND `a`.`delete` = 0 AND `a`.`inShop` = 0 AND `inOdet` = 0' , array(
			'uid' => $uid
		) , true );
		$r['now'] = 0 + $r['now']['m'];
		//
		$r['max'] = 0;
		//
		return $r;		
	}
	
	//������� ��������������
	public static function getStats( $uid ) {
		$r = array(
			'st' => array( ), //��������������
			'sl' => array( ), //����������� ����� ( 0 id �������� , 1 ��� �������� )
			'ms' => array( 'now' => 0 , 'max ' => 0 , 'itm' => 0 ) //�����
		);
		//		
		$user = \Core\Database::query( 'SELECT * FROM `users` WHERE `id` = :uid LIMIT 1' , array(
			'uid' => $uid
		), true );	
		//	
		if( isset($user['id']) ) {
			//
			$stats = \Core\Database::query( 'SELECT * FROM `stats` WHERE `id` = :uid LIMIT 1' , array(
				'uid' => $uid
			), true );
			//
			//������� ����������
			$r['st']['hpNow'] = $stats['hpNow'];
			$r['st']['mpNow'] = $stats['mpNow'];
			//
			//�������������� �� ���������
			$pl = \Core\Database::query( 'SELECT * FROM `items_users` WHERE `uid` = :uid AND `inOdet` > 0 AND `delete` = 0' , array(
				'uid' => $user['id']
			), true, true);
			$i = 0;
			while( $i < count($pl) ) {
				$r['st'] = self::plusStatsData( $r['st'] , $pl[$i]['data'] );
				$i++;
			}			
			unset($pl,$i);
			//
			//�������������� �� ��������
			$pl = \Core\Database::query( 'SELECT * FROM `eff_users` WHERE `uid` = :uid AND `delete` = 0' , array(
				'uid' => $user['id']
			), true, true);
			$i = 0;
			while( $i < count($pl) ) {
				$r['st'] = self::plusStatsData( $r['st'] , $pl[$i]['data'] );
				$i++;
			}			
			unset($pl,$i);
			//
			//�������������� �� ������
			$r['st']['hpAll'] 	+= $r['st']['s4'] * 6;  	//�������� �� ������������
			$r['st']['mpAll'] 	+= $r['st']['s6'] * 10; 	//���� �� ��������
			$r['st']['m1']		+= $r['st']['s3'] * 5;		//�������� ���� ��. �����
			$r['st']['m2']		+= $r['st']['s3'] * 5;		//�������� ���� ��. ������ �����
			$r['st']['m4']		+= $r['st']['s2'] * 5;		//�������� ���� ��. �������
			$r['st']['m5']		+= $r['st']['s2'] * 5;		//�������� ���� ��. ������ �������
			$r['st']['za']		+= $r['st']['s4'] * 1.5;	//������������ ���� ������ �� �����
			$r['st']['zm']		+= $r['st']['s4'] * 1.5;	//������������ ���� ������ �� �����
			//
			//������ ����������
			
			//
			//������ ������
			
			//
		}		
		return $r;		
	}
	
	//���������� ������
	public static function plusStatsData( $st , $data ) {
		$data = \Core\Utils::lookStats( $data );
		$i = 0;
		while( $i < count(self::$items['add']) ) {
			if( $data['add_' . self::$items['add'][$i]] != 0 ) {
				$st[self::$items['add'][$i]] += $data['add_' . self::$items['add'][$i]];
			}
			$i++;
		}
		return $st;
	}
	
	//���������� ������� � ������� ��������� ������������
	public static function room() {
		if( isset(self::$data['id']) ) {
			self::$room = \Core\Database::query( 'SELECT * FROM `room` WHERE `id` = :id LIMIT 1' , array(
				'id' => self::$data['room']
			) , true );
		}
	}
	
	public static function redirect($url) {
		header('Location: ' . $url);		
	}
	
	public static function ErrorPage404() {
		die('�������� �� �������');
	}
	
	public static function ErrorClass404($name) {
		die('���������� '. $name .' �� �������');
	}
}

?>