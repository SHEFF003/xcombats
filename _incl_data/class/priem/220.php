<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ������ �� ����������
	������� ��� ������
*/
$pvr = array();
if( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- ������ ������
		global $u, $btl;	
		//
		//��������� ������
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {	
				mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
				unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
		}
		//
		// -- ����� ������
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) && isset($pr_moment) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- ������ ������
		global $u, $btl, $priem;	
		//
		//��������� ������
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( $a > 0 ) {

			if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$u1][$j_id]) ) {
				//
				$btl->priemAddLogFast( $u1, 0, "������ �� ����������",
					'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).'',
				1, time() );					
				//
				mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][3].'" AND `uid` = "'.$u1.'" LIMIT 1');
				unset($btl->stats[$btl->uids[$u1]]['u_priem'][$j_id]);
				//
				$pvr['sp'] = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u2.'" AND `delete` = 0
				AND `v1` = "priem" AND `v2` != 201 AND `v2` != 238 AND `v2` != 139 AND `v2` != 211 AND `v2` != 233 AND `v2` != 223 AND `v2` != 222
				LIMIT 20');
				while($pvr['pl'] = mysql_fetch_array($pvr['sp'])) {
					$pvr['pl']['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pvr['pl']['v2'].'" LIMIT 1'));
					if( isset($pvr['pl']['priem']) ) {
						mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u1.'" AND `v2` = "'.$pvr['pl']['v2'].'" AND `delete` = "0" LIMIT 1');
						mysql_query('UPDATE `eff_users` SET `uid` = "'.$u1.'" WHERE `id` = "'.$pvr['pl']['id'].'" LIMIT 1');
						//$btl->delPriem($pvr['pl'],$btl->users[$btl->uids[$uid1]],100);
					}
				}
				//
			}	
		}
		// -- ����� ������
		return $at;
	};
	unset( $pr_used_this );
}else{
	//�������� ��� �����
	$this->addEffPr($pl,$id);
}
unset($pvr);
?>