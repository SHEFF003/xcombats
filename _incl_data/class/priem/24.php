<?
if(!defined('GAME')) {
	die();
}
/*
	�����: ���������
*/
$pvr = array();

//�������� ��� �����
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil, $inlog ) {
		return round($yron);
	};
}elseif( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) && isset($pr_moment) && isset($btl->info['id']) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		return $at;
	};
	unset( $pr_used_this );
}elseif(isset($btl->info['id'])) {
	//�������� ��� �����
	/*
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
		'���������',
		'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).'',
		($btl->hodID+1)
	);
	*/
				//
				$pvr['mp'] = round($u->stats['mpAll']*0.10);
				$pvr['mpSee'] = 0;
				$pvr['mpNow'] = floor($u->stats['mpNow']);
				$pvr['mpAll'] = $u->stats['mpAll'];
				$pvr['mpTr'] = $pvr['mpAll'] - $pvr['mpNow'];
				
				//$pvr['mp'] = $btl->hphe( $u->info['id'] , $pvr['hp'] );
				
				if( $pvr['mpTr'] > 0 ) {
					//��������� ����
					if( $pvr['mpTr'] < $pvr['mp'] ) {
						$pvr['mp'] = $pvr['mpTr'];
					}
					$pvr['mpSee'] = '+'.$pvr['mp'];
					$pvr['mpNow'] += $pvr['mp'];
				}					
				if( $pvr['mpNow'] > $pvr['mpAll'] ) {
					$pvr['mpNow'] = $pvr['mpAll'];
				}elseif( $pvr['mpNow'] < 0 ) {
					$pvr['mpNow'] = 0;
				}
				//
				//$btl->users[$btl->uids[$u->info['id']]]['last_hp'] = $pvr['hp'];
				//
				$u->stats['mpNow'] = $pvr['mpNow'];	
				$u->info['mpNow'] = $pvr['mpNow'];	
				$btl->stats[$btl->uids[$u->info['id']]]['mpNow'] = $pvr['mpNow'];
				$btl->users[$btl->uids[$u->info['id']]]['mpNow'] = $pvr['mpNow'];				
				mysql_query('UPDATE `stats` SET `mpNow` = "'.$u->stats['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//
				$btl->priemAddLogFast( $u->info['id'], 0, "���������",
					'{tm1} '.$btl->addlt(1 , 21 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' �� <font Color=#006699><b>'.$pvr['mpSee'].'</b></font> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (����)',
				1, time() );					
				//
	echo '<font color=red><b>�� ������� ������������ ����� &quot;���������&quot;</b></font>';
	$this->addEffPr($pl,$id);
}

unset($pvr);
?>