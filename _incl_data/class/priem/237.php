<?
if(!defined('GAME')) {
	die();
}
/*
	�����: �������� ����
	���������� ��� �������� ������ �� ���������� 5 �����
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
		$uid1 = $btl->atacks[$id]['uid2'];
		$uid2 = $btl->atacks[$id]['uid1'];	
		//echo '['.$uid1.'|'.$uid2.'|*'.$uid.'*]';		
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
			$uid1 = $btl->atacks[$id]['uid1'];
			$uid2 = $btl->atacks[$id]['uid2'];
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
			$uid1 = $btl->atacks[$id]['uid2'];
			$uid2 = $btl->atacks[$id]['uid1'];
		}
		if( $a > 0 ) {

			$j = 0; $k = 0; $wp = 3;
			/*while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 ))
				{*/
					if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {
						//
						$btl->priemAddLogFast( $uid2, 0, "�������� ����",
							'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$uid2]]['sex'] , NULL).'',
						1, time() );					
						//
						mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid2]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid2.'" LIMIT 1');
						unset($btl->stats[$btl->uids[$uid2]]['u_priem'][$j_id]);
						$priem->addPriem($uid1,238,'add_seeAllEff=1',0,77,4,$uid2,5,'������������');
						$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
						//
					}
				/*}
				$j++;
			}*/
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