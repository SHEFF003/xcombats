<?
if(!defined('GAME')) { die(); }

ini_set('memory_limit','256M');

class battleClass {
	
	public $mncolor = array(1=>'006699',2=>'006699',3=>'006699',4=>'006699',5=>'006699',6=>'006699',7=>'006699'); //�� ����
	public $mcolor = array(1=>'A00000',2=>'008080',3=>'0000FF',4=>'A52A2A',5=>'006699',6=>'006699',7=>'006699'); //�� ����
	public $mname = array('�����' => 1 , '����' => 3 , '������' => 2 , '�����' => 4 , '����' => 5 , '����' => 6 , '�����' => 7 );
	public $prm = array(
		/*
			act:	1 - ����� �������� �������� �����������
					2 - ����� �������� ������� ����
			type_of:	1 - ������
						2 - ����
						3 - �����
						4 - ������
						5 - ������
		*/
		1   => array( 'name'	=> '����������', 'act'	=> 1 , 'type_of' => 5 ),
		2   => array( 'name'	=> '�������', 'act'	=> 2 , 'type_of' => 3 ),
		4   => array( 'name'	=> '������� ����', 'act' => 2 , 'type_of' => 3 ),		
		7   => array( 'name'	=> '�������� ������', 'act'	=> 1 , 'type_of' => 4 ),
        
        
		290   => array('name'	=> '����������� ����', 'act'	=> 1 , 'type_of' => 4 ),
		
		//����� �����
		291 => array( 'name'	=> '������ ���', 'act' => 2, 'type_of' => 4 ),
        
		//�����������
		294	=> array( 'name'	=> '��������� ����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 ),
		295	=> array( 'name'	=> '���������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 ),
		296	=> array( 'name'	=> '������ �����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 ),
		297 => array( 'name'	=> '���������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 3 ),
		298   => array( 'name'	=> '������ ��������', 'act'	=> 2 , 'type_of' => 3 ),
		//
        
		141 => array( 'name'	=> '���������� ������', 'act' => 2, 'type_of' => 4 ),
		147 => array( 'name'	=> '���������� ������', 'act' => 2, 'type_of' => 4 ),
		148 => array( 'name'	=> '���������� �����', 'act' => 2, 'type_of' => 4 ),
		149 => array( 'name'	=> '���������� ����', 'act' => 2, 'type_of' => 4 ),
		150 => array( 'name'	=> '���������� ������', 'act' => 2, 'type_of' => 4 ),
		
		142 => array( 'name'	=> '���������� �����', 'act' => 2, 'type_of' => 4 ),
		144 => array( 'name'	=> '���������� ������', 'act' => 2, 'type_of' => 4 ),
		146 => array( 'name'	=> '���������� �����', 'act' => 2, 'type_of' => 4 ),
		145 => array( 'name'	=> '���������� ����', 'act' => 2, 'type_of' => 4 ),
		
		8   => array( 'name'	=> '����� �����', 'act'	=> 1 , 'type_of' => 1 ),	
		9	=> array( 'name'	=> '����� �����', 'act'	=> 2 , 'type_of' => 3 ),
		10  => array( 'name'	=> '�����������', 'act'	=> 1 , 'type_of' => 1 ),	
		11  => array( 'name'	=> '������� ����', 'act' => 2 , 'type_of' => 3 ),
				
		45  => array( 'name'	=> '������ ������', 'act' => 1, 'type_of' => 4 ),
		
		47  => array( 'name'	=> '������ �����', 'act'	=> 2 , 'type_of' => 2 ),
		48  => array( 'name'	=> '����� ������', 'act'	=> 1 , 'type_of' => 4 ),	 // ����_�� = 1 ���
		49  => array( 'name'	=> '������ �������', 'act'	=> 1 , 'type_of' => 1 ),
		138 => array( 'name'	=> '����������� ����', 'act'	=> 2 , 'type_of' => 3 ),
		140 => array( 'name'	=> '���������� ������', 'act' => 1 , 'type_of' => 4 ),
		193 => array( 'name'	=> '��������� �����', 'act' => 2 , 'type_of' => 3 ),
		204 => array( 'name'	=> '������������', 'act' => 2 , 'type_of' => 5 ),
		211 => array( 'name'	=> '����������� ������', 'act' => 1 , 'type_of' => 4 ),
		213 => array( 'name'	=> '�������� ����', 'act'	=> 1 , 'type_of' => 4 ),
		215 => array( 'name'	=> '������� ��������', 'act'	=> 1 , 'type_of' => 4 ), //type_of = 1 ���	
		216 => array( 'name'	=> '������� ����', 'act'	=> 2 , 'type_of' => 2 ),
		217 => array( 'name'	=> '��������� �������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 3 ),
		219 => array( 'name'	=> '������ ����', 'act' => 2 , 'type_of' => 3 ),
		220 => array( 'name'	=> '������ �� ����������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 3 ),
		222 => array( 'name'	=> '��������� ����', 'act'	=> 2 , 'type_of' => 3 ),
		225 => array( 'name'	=> '���������� ������', 'act' => 1 , 'type_of' => 4 ),
		226 => array( 'name'	=> '���������', 'act' => 1 , 'type_of' => 4 ),
		231 => array( 'name'	=> '������ ������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 3 ),
		234 => array( 'name'	=> '������������', 'act' => 2, 'type_of' => 4 ),
		235 => array( 'name'	=> '���������� ����', 'act'	=> 2 , 'type_of' => 3 ),
		237 => array( 'name'	=> '�������� ����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 3 ),
		239 => array( 'name'	=> '������� ������', 'act' => 2 , 'type_of' => 3 ),
		240	=> array( 'name'	=> '�������� �����', 'act'	=> 2 , 'type_of' => 5 ),
		327 => array( 'name'	=> '���������� �����', 'act' => 2 , 'type_of' => 3 )
		
		//����������
		,21	=> array( 'name'	=> '���������� [4]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		,73	=> array( 'name'	=> '���������� [5]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		,74	=> array( 'name'	=> '���������� [6]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		,75	=> array( 'name'	=> '���������� [7]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		,76	=> array( 'name'	=> '���������� [8]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		,77	=> array( 'name'	=> '���������� [9]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		,78	=> array( 'name'	=> '���������� [10]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		,79	=> array( 'name'	=> '���������� [11]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )	
		
		//����������
		,22	=> array( 'name'	=> '���������� [6]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,80	=> array( 'name'	=> '���������� [7]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,81	=> array( 'name'	=> '���������� [8]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,82	=> array( 'name'	=> '���������� [9]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,83	=> array( 'name'	=> '���������� [10]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,84	=> array( 'name'	=> '���������� [11]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		
		//�����������
		,36	=> array( 'name'	=> '����������� [5]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,85	=> array( 'name'	=> '����������� [6]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,86	=> array( 'name'	=> '����������� [7]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,87	=> array( 'name'	=> '����������� [8]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,88	=> array( 'name'	=> '����������� [9]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,89	=> array( 'name'	=> '����������� [10]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,90	=> array( 'name'	=> '����������� [11]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		
		//�������� ������
		,23	=> array( 'name'	=> '�������� ������ [8]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,70	=> array( 'name'	=> '�������� ������ [9]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,71	=> array( 'name'	=> '�������� ������ [10]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,72	=> array( 'name'	=> '�������� ������ [11]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		
		//����
		,269 => array( 'name'	=> '���� [7]', 'act' => 2, 'type_of' => 9 )
		,276 => array( 'name'	=> '���� [8]', 'act' => 2, 'type_of' => 9 )
		,277 => array( 'name'	=> '���� [9]', 'act' => 2, 'type_of' => 9 )
		
		//���� ����
		,270 => array( 'name'	=> '���� ����', 'act'	=> 2 , 'type_of' => 5 , 'type_sec' => 5 )
		
		//������ ����
		,280 => array( 'name'	=> '������ ����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		
		//������ ����
		,281 => array( 'name'	=> '������ ����', 'act' => 2, 'type_of' => 5 )
		
		//������� ��������
		,282 => array( 'name'	=> '������� ��������', 'act' => 2, 'type_of' => 5 )
		
		//
		//
		
		//���������
		,24 => array( 'name'	=> '���������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 3 )
		
		//���������� ������
		,210 => array( 'name'	=> '���������� ������ [4]', 'act' => 2, 'type_of' => 8 )
		,209 => array( 'name'	=> '���������� ������ [7]', 'act' => 2, 'type_of' => 8 )
		,208 => array( 'name'	=> '���������� ������ [8]', 'act' => 2, 'type_of' => 8 )
		,207 => array( 'name'	=> '���������� ������ [9]', 'act' => 2, 'type_of' => 8 )
		,206 => array( 'name'	=> '���������� ������ [10]', 'act' => 2, 'type_of' => 8 )
		,284 => array( 'name'	=> '���������� ������ [11]', 'act' => 2, 'type_of' => 8 )
		
		//������� ����
		,175 => array( 'name'	=> '���������� ������ [7]', 'act' => 2, 'type_of' => 8 )
		,176 => array( 'name'	=> '���������� ������ [8]', 'act' => 2, 'type_of' => 8 )
		,177 => array( 'name'	=> '���������� ������ [9]', 'act' => 2, 'type_of' => 8 )
		,178 => array( 'name'	=> '���������� ������ [10]', 'act' => 2, 'type_of' => 8 )
		,179 => array( 'name'	=> '���������� ������ [11]', 'act' => 2, 'type_of' => 8 )
		
		//
		//
		
		//��������
		,42		=> array( 'name'	=> '�������� [6]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,121	=> array( 'name'	=> '�������� [7]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,122	=> array( 'name'	=> '�������� [8]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,123	=> array( 'name'	=> '�������� [9]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,124	=> array( 'name'	=> '�������� [10]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,125	=> array( 'name'	=> '�������� [11]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		
		//�������� ���
		,249 => array( 'name'	=> '�������� ���', 'act' => 2, 'type_of' => 4 )
		
		//��������� ���
		,248 => array( 'name'	=> '�������� ���', 'act' => 2, 'type_of' => 4 )
		
		//����������
		,251 => array( 'name'	=> '����������: ����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,252 => array( 'name'	=> '����������: �����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		
		//
		//
		
		//�������� �����
		,273 => array( 'name'	=> '�������� ����� [10]', 'act'	=> 1 , 'type_of' => 4 )
		,286 => array( 'name'	=> '�������� ����� [9]', 'act'	=> 1 , 'type_of' => 4 )
		,287 => array( 'name'	=> '�������� ����� [8]', 'act'	=> 1 , 'type_of' => 4 )
		,288 => array( 'name'	=> '�������� ����� [7]', 'act'	=> 1 , 'type_of' => 4 )
		
		//
		,255 => array( 'name'	=> '��������� ���', 'act' => 2, 'type_of' => 8 )
		
		//
		//
		
		//���������� �����
		,33	=> array( 'name'	=> '���������� ����� [6]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,56	=> array( 'name'	=> '���������� ����� [7]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,57	=> array( 'name'	=> '���������� ����� [8]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,58	=> array( 'name'	=> '���������� ����� [9]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,59	=> array( 'name'	=> '���������� ����� [10]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,60	=> array( 'name'	=> '���������� ����� [11]', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		
		//�������� ���
		,245 => array( 'name'	=> '�������� ���', 'act' => 2, 'type_of' => 9 )
		
		//1 ��������, �����
		,299 => array( 'name'	=> '�������� ����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,300 => array( 'name'	=> '������������ �����', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,301 => array( 'name'	=> '������ �������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,302 => array( 'name'	=> '�����������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,303 => array( 'name'	=> '����� ����������', 'act' => 2, 'type_of' => 4 )
		,304 => array( 'name'	=> '��������� ���������!', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
		,305 => array( 'name'	=> '�������������', 'act'	=> 2 , 'type_of' => 0 , 'moment' => 0 , 'moment_end' => 3 )
	);
	
	//��������� ��� � �����
		public function saveLogs( $id , $type ) {
			//if( $type == 'all' ) {
			//	$type = '';	
			//}
			//mysql_query('INSERT INTO `battle_logs_save` SELECT `id`,`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type` FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'" AND `id_hod` <= '.($this->hodID-2).'');
			//mysql_query('DELETE FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'" '.$type.'');
		}
	
	public function hphe( $uid , $hp , $false_t7 = false ) {
		global $u;
		if( !isset($this->stats[$this->uids[$uid]]) ) {
			echo 'WARNING! ������! �������� ���������� �������� ���������!';
		}else{
			$hpnow = floor($this->stats[$this->uids[$uid]]['hpNow']);
			$hpall = $this->stats[$this->uids[$uid]]['hpAll'];
			if( $hp > 0 ) {
				//������
				if( $hpnow + $hp > $hpall ) {
					$hpli = $hpnow+$hp-$hpall;
					$hp -= $hpli; 
				}
				if( isset($this->stats[$this->uids[$uid]]['min_heal_proc']) && $this->stats[$this->uids[$uid]]['min_heal_proc'] < -99 ) {
					$hp = 0;
				}else{
					$hp = $hp/100*(100+$this->stats[$this->uids[$uid]]['min_heal_proc']);
				}
				//�������� �������										
				if( $false_t7 == false ) {
					if( $this->users[$this->uids[$uid]]['tactic7'] <= 0 ) {
						$hp = 0;
						$this->users[$this->uids[$uid]]['tactic7'] = 0;
						$this->stats[$this->uids[$uid]]['tactic7'] = $this->users[$this->uids[$uid]]['tactic7'];
					}else{
						$gdhh = round($hp/$this->stats[$this->uids[$uid]]['hpAll']*10,2);
						$gdhd = round($this->users[$this->uids[$uid]]['tactic7']/$gdhh*100);
						$this->users[$this->uids[$uid]]['tactic7'] = round(($this->users[$this->uids[$uid]]['tactic7']-$gdhh),2);
						if( $this->users[$this->uids[$uid]]['tactic7'] < 0 ) {
							$this->users[$this->uids[$uid]]['tactic7'] = 0;
						}
						$this->stats[$this->uids[$uid]]['tactic7'] = $this->users[$this->uids[$uid]]['tactic7'];
						if( $gdhd < 100 ) {
							$hp = floor($hp/100*$gdhd);
						}
					}
				}
			}elseif( $hp < 0 ) {
				//���������
				if( $hpnow + $hp < 0) {
					$hpli = $hpnow + $hp;
					$hp += -($hpli);
				}
			}
			
			$this->stats[$this->uids[$uid]]['last_hp'] = -$hp;	
			mysql_query('UPDATE `stats` SET
			`last_hp` = "'.$this->users[$this->uids[$uid]]['last_hp'].'",
			`tactic7` = "'.$this->users[$this->uids[$uid]]['tactic7'].'"
			WHERE `id` = "'.$uid.'" LIMIT 1');
					
		}
		
		return floor($hp);
	}
	
	public function deleffm($pid , $uid , $id) {
		if( $id > 0 ) {
			if(!mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.mysql_real_escape_string($id).'" AND `v1` = "priem" AND `v2` != "0" LIMIT 1')) {
				echo '[*������ �������� �����['.$id.','.$pid.','.$uid.']]';
			}
		}else{
			if(!mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `v1` = "priem" AND `v2` = "'.$pid.'"')) {

			}
		}
		//echo '['.$id.','.$pid.','.$uid.']';
	}
	
	
	public $e,                 //������ (�����)
		   $cached = false,	   //����������� ������
		   $expCoef    = 0	,    # % ����� � ���
		   $aBexp      = 0,    //���������� ���� � ����
		   $mainStatus = 1,    //���������� ������� ���� (1 - ����� ����, 2 - ������� ��� ����������, 3 - ���������. ������� ���������� ��������)
		   $info = array(),    //���������� � ��������
		   $users = array(),   //���������� � ������������� � ���� ���
		   $stats = array(),   //���������� � ������ ������������� � ���� ���
		   $uids = array(),    //������ ������������� � �� id � stats ��� users ������ id ������������ = 555 , �� $uids[555] ������ ��� ���������� ����� � ������� users \ stats
		   $atacks = array(),  //������ ������ � ���� ��� (�����������)
		   $ga = array(),      //������ uid ��� ����� ���� � �� ����  $ga[ {id ��� ������} ][ {id ���� ������} ]
		   $ag = array(),      //������ uid ��� ����� ���� � �� ����  $ga[ {id ���� �������} ][ {id ��� ������} ] 
		   $na = 1,            //����������� ������������ ����
		   $np = 1,            //����������� ������������ ������
		   $nm = 1,            //����������� ������������ ��������
		   $hodID = 0,
		   $stnZbVs = 0,
		   $bots = array(),    // ID �����
		   $iBots = array(),   // i ����
		   $stnZb = array(),
		   $uAtc = array('id'=>0,'a'=>array(1=>0,2=>0,3=>0,4=>0,5=>0),'b'=>0), //���� ����� ����� ����
		   $lg_itm = array(0 => array('������','������ ����','����','�������','�����','����� �����','������ �����','�������'),1 => array('�����','������� �������� ������ ����','�������� ����','������� ����'),2 => array('���������� ������','�������','������� �������','�������','�������� ������'),3 => array('�������','�������','������� ������','���������','������� ��������','������� �������'),4 => array('�������','������','�����','������� ����','�������� ����','����� �������','������ �������� ����','�������� �����'),5 => array('���������� ������','�������','������� �������','������� �������','���������� ������'), 22=> array('��������')), // ��� ������
		   $lg_zon = array(1 => array ('� ���','� ����','� �������','�� ����������','� �����','�� �������','� ������ ����','� ����� ����','� �����'),2 => array ('� �����','� ������','� ��������� ���������','� ������','� ������� �������'),3 => array ('� ���','�� �������','�� ����� ����','�� ������ ����'),4 => array ('�� <�������� ��������>','� ���','� �����������','�� ����� �������','�� ������ �������'),5 => array ('�� �����','� ������� ������ �����','� ������� ����� �����','�� �������� �������','�� �����')); // ���� ������
	public $is = array(),$items = array();
		
	//������� ���� ��� ...
		public $uclearc = array(),$ucleari = array();
		public function clear_cache($uid) {
			if( $uid > 0 && !isset($this->uclearc[$uid]) ) {
				$this->uclearc[$uid] = true;
				$this->ucleari[] = $uid;
			}
		}
		
		public function clear_cache_start() {
			$i = 0;
			while( $i < count($this->ucleari) ) {
				mysql_query('DELETE FROM `battle_cache` WHERE `uid` = "'.mysql_real_escape_string($this->ucleari[$i]).'"');
				$i++;
			}
		}
		
	//�������� ���� ������� ��� ���������
		public function testYronPriemAttack( $pid , $u1 , $u2 , $hp ) {
			
			//����� 1 ���� �� ������ 2 ��� ������ ������ � pid �� hp ��. ��������
			
			/*
				������ ������
			*/
			//�������� ������ � ������� ����� ����� ���������
			$eff = $this->stats[$this->uids[$u2]]['effects'];
			$j = 0;				
			while( $j <= count($eff) ) {
				if( isset($eff[$j]) && $eff[$j]['id_eff'] == 22 && $eff[$j]['v1'] == 'priem' && $eff[$j]['v2'] > 0 ) {
					// id ����� $eff[$j]['v2']
					if( $eff[$j]['v2'] == 140 || $eff[$j]['v2'] == 45 || $eff[$j]['v2'] == 211 ) {
						//������ �� ������� ���� = 1 , �� ���� ������ 0
						$hp['y'] = -1;
						$hp['r'] = 1;
						$hp['k'] = 2;
						$hp['m_y'] = 1;
						$hp['m_k'] = 2;	
					}
				}
				$j++;
			}
			unset($eff);
			
			return $hp;
		}
		
	//������ ���.�����
		public function magKrit($l2,$t)
		{
			$r = 0;
			$r = $l2*2-7;
			if($r>$t)
			{
				//���������� ������ (����� ����, � 2 ���� ������) 6%
				//250 ��. ������ �� ����� ���� 1% ����� ���������� �� �����
				//$r = -1; , ������ --
				$r = 0;
			}else{
				//������ �������� ���� 3% ���� �����
				$r = ceil($t*0.75);
				if($r>30)
				{
					$r = 30;
				}
				if(rand(0,10000)<$r*100)
				{
					//���� ����
					$r = 1;
				}else{
					$r = 0;	
				}
			}
			return $r;
		}
		
		
	//���������� ��
		public function hpRef() {
			
		}
		
	//������ �����
		public function testExp($y,$s1,$s2,$id1,$id2)
		{
			global $u,$c;

			if($y < 0){ $y = 0; }
			if( $s2['hpNow'] < 0){
				//echo '[f]';
				$y = 0;
			}
			if( $s2['hpNow'] < $y){
				//echo '[d]';
				$y = $s2['hpNow'];
			}
			if($y < 0){ /*echo '[r]';*/ $y = 0; }
			//
			$addExp = 0+(($y/$s2['hpAll'])*100);
			if( $s2['hpAll']-$y <= 0){
				//echo '[a]';
				$addExp = 100;
			}
			
			//if($this->users[$this->uids[$s2['id']]]['host_reg'] == 'real_bot_user') {
			//	$addExp = floor($addExp*0.76);
			//}
			
			if($addExp < 0){ $addExp = 0; }						
			if( $s2['levels']!='undefined' && $this->users[$this->uids[$s2['id']]]['pass'] != 'saintlucia' ){
				//$doexp = mysql_fetch_array(mysql_query('SELECT SUM(`items_main`.`price1`) FROM `items_users`,`items_main` WHERE `items_users`.`inOdet` > 0 AND `items_main`.`inSlot` < 50 AND `items_users`.`uid` = "'.$id2.'" AND `items_users`.`delete` = 0 AND `items_main`.`id` = `items_users`.`item_id` ORDER BY `items_main`.`inSlot` ASC LIMIT 50'));
				//if($doexp[0]>0) {
				//	$doexp = floor($doexp[0]/15);
				//}else{
				//	$doexp = 0;
				//}
				//$doexp = floor(($this->users[$this->uids[$id2]]['btl_cof']-$this->users[$this->uids[$id1]]['btl_cof']*0.80)/5);
				/*if( $this->users[$this->uids[$id2]]['btl_cof'] > $this->users[$this->uids[$s2['id']]]['level']*350 ) {
					//������
					$doexp = floor($this->users[$this->uids[$s2['id']]]['level']*350 + ($this->users[$this->uids[$id2]]['btl_cof']/20));
				}else{
					//�� ������
					$doexp = floor(($this->users[$this->uids[$id2]]['btl_cof']));
				}*/				
				
				if( $doexp < 0 ) {
					$doexp = 0;
				}
				//$addExp = $addExp*(1+($s2['levels']['expBtlMax']+$s2['irka'])+($doexp*1.01/10))/100;
				
				if( $this->info['type'] == 1 ) {
					$addExp = $addExp*($s2['levels']['expBtlMax']/10)/100;
				}else{
					$xty = ($s2['reting']-$s1['reting']/2);
					if( $xty < 0 ) {
						$xty = 0;
					}
					$addExp = $addExp*(($s2['levels']['expBtlMax']+$xty))/100;
				}
				//echo '(������� ����: '.$s2['levels']['expBtlMax'].')';
				//
				/*
				if($this->info['razdel'] != 5 && $c['exp_limit_many'] == true) {
					$texp = mysql_fetch_array(mysql_query('SELECT COUNT(`a`.`id`) FROM `battle_users` AS `a` WHERE `a`.`uid` = "'.$id1.'" AND `a`.`battle` IN ( SELECT `b`.`battle` FROM `battle_users` AS `b` WHERE `b`.`uid` = "'.$id2.'" AND `b`.`team` != `a`.`team` AND `b`.`time` > "'.(time()-86400).'" ) LIMIT 1'));
					$texp = $texp[0];
					//
					if($texp > 5) {
						$addExp = $addExp*0.00;
					}elseif($texp > 4) {
						$addExp = $addExp*0.50;
					}elseif($texp > 3) {
						$addExp = $addExp*0.75;
					}elseif($texp > 2) {
						$addExp = $addExp*1.00;
					}elseif($texp > 1) {
						$addExp = $addExp*1.00;
					}else{
						$addExp = $addExp*1.00;
					}
				}
				*/
				//
				if( $this->users[$this->uids[$s2['id']]]['bot'] > 0 ) {
					//$addExp = round($addExp/5);
				}
				unset($doexp);
			}else{
				$addExp = 0;
			}
			
			/*
			if($s1['level'] > $s2['level']){
				$minProc = 100 - 33*( $s1['level']-$s2['level'] );
				if($minProc < 1) {
					$minProc = 1;
				}
				$addExp = round($addExp/100*$minProc);
			}
			*/
			
			if( $this->users[$this->uids[$s2['id']]]['bot_id'] == 0 && $this->stats[$this->uids[$s2['id']]]['itmslvl'] == 0 ) {
				//$addExp = 0;
			}
			
			if( $this->info['typeBattle'] == 9 ) {
				//���������
				//�� 8 � ���� �� ���� ����
				/*if( $this->users[$this->uids[$s1['id']]]['level'] > $this->users[$this->uids[$s2['id']]]['level'] ) {
					if( $this->users[$this->uids[$s2['id']]]['level'] <= 8 ) {
						$addExp = 0;
					}
				}*/
			}
			
			return $addExp;
		}
	
	//��������� ���� \ ���������� ����
		public function takeExp($id,$y,$id1,$id2,$mgregen = false,$nobattle_uron = false){
			global $u;
			if(isset($this->users[$this->uids[$id]])){
				$s1 = $this->stats[$this->uids[$id1]];
				$s2 = $this->stats[$this->uids[$id2]];
				if($id1!=$id2){
					$e = $this->testExp($y,$s1,$s2,$id1,$id2);
				}else{
					$e = 0;
				}
				
				if( $this->users[$this->uids[$id1]]['level'] > $this->users[$this->uids[$id2]]['level'] ) {
					$rez = $this->users[$this->uids[$id1]]['level']-$this->users[$this->uids[$id2]]['level'];
					$e = round($e/100*(21-$rez*3));
					if( $e < 0 ) {
						$e = 0;
					}
				}

				if( (int)$this->users[$this->uids[$id1]]['bot_id'] == 0 && $this->users[$this->uids[$id1]]['dnow'] != 0 && $this->info['dungeon'] != 1 ) {
					$dun_limitForLevel = array( // �������� ��� ������� ������.
						4 => 750,
						5 => 1500,
						6 => 3500,
						7 => 8000,
						8 => 25000,
						9 => 50000,
						10 => 75000,
						11 => 125000,
						12 => 250000,
						13 => 500000,
						14 => 750000
					);
					$dun_expFactor = array( // �������� ��� ������� ������.
						4 => 5,
						5 => 5,
						6 => 5,
						7 => 5,
						8 => 5,
						9 => 3,
						10 => 1,
						11 => 1,
						12 => 1,
						13 => 1,
						14 => 1
					);
					
					if( isset($dun_expFactor[(int)$this->users[$this->uids[$id1]]['level']]) ){
						$e = $e * $dun_expFactor[(int)$this->users[$this->uids[$id1]]['level']];
					}

					if($this->info['dungeon'] > 1 && $this->users[$this->uids[$id1]]['battle'] > 0 ) { // �������� ����� 
						$dun_exp = array(); // ������� ����� ����� ������ � �����������.
						$rep = mysql_fetch_array(mysql_query('SELECT `dungeonexp`,`id` FROM `rep` WHERE `id` = "'.$this->users[$this->uids[$id1]]['id'].'" LIMIT 1'));
						$rep = explode(',',$rep['dungeonexp']);
						foreach ($rep as $key=>$val) {
							$val = explode('=',$val);
							if( isset($val[0]) && isset($val[1]) && $val[0] !='' && $val[1] != 0 ) $dun_exp[(int)$val[0]] = (int)$val[1]; // ������� ����� ����� � �������� 
						} unset($rep);
						
						if( !isset($dun_exp[$this->info['dungeon']]) ) $dun_exp[$this->info['dungeon']] = 0;
						if( !isset($dun_limitForLevel[(int)$this->users[$this->uids[$id1]]['level']]) ){ // ���� ����� �� �����, ���� �� ����.
							$e = 0;
						} elseif(
							isset($dun_exp[$this->info['dungeon']]) &&
							$dun_exp[$this->info['dungeon']] >= $dun_limitForLevel[(int)$this->users[$this->uids[$id1]]['level']]
						) { // ���� ����� ��� ���������, ���� �� ����.
							$e = 0;
						} elseif(
							isset($dun_exp[$this->info['dungeon']]) &&
							$dun_limitForLevel[(int)$this->users[$this->uids[$id1]]['level']] > $dun_exp[$this->info['dungeon']]
						) { // ���� ������� ��������� �� �������� ������.
							if( ( $dun_exp[$this->info['dungeon']] + $e ) > $dun_limitForLevel[(int)$this->users[$this->uids[$id1]]['level']] ) {
								// ���� ����� ������� ����������, ��� ���������� ������.
								$e = abs($e - abs( $dun_limitForLevel[(int)$this->users[$this->uids[$id1]]['level']] - ( $e + $dun_exp[$this->info['dungeon']] ) ));
								$dun_exp[$this->info['dungeon']] += $e; 
							} elseif( $dun_limitForLevel[(int)$this->users[$this->uids[$id1]]['level']] > ( $dun_exp[$this->info['dungeon']] + $e ) ) {
								// ���� ����� ������������, ��� ���������� ������.
								$e = $e;
								$dun_exp[$this->info['dungeon']] += $e;
							} else {
								$e = 0;
							}
						} else { // � ����� ���������� ��������.
							$e = 0;
						}
					} else $e = $e; // ���� � �������.
					if( $this->info['dungeon'] == 102 && (int)$this->users[$this->uids[$id1]]['bot_id'] == 0 ) {
						$e = floor($e * 0.002);
					}
				} 
						
				if( (int)$this->users[$this->uids[$id1]]['bot_id'] == 0 && $this->users[$this->uids[$id1]]['dnow'] != 0 && $this->info['dungeon'] != 1 ) {
					if($this->users[$this->uids[$id1]]['level'] >= 7 ) {
						$itmsCfc = ($s1['itmsCfc']+$s2['itmsCfc'])/26;
						$itmsCfc = round($itmsCfc,5);
						$e = $e*$itmsCfc;
					}
				}
				
				if( (int)$this->users[$this->uids[$id1]]['bot_id'] == -1 ) {
					//��������� ��� ����
					$e = $e/2;
				}

				$this->users[$this->uids[$id1]]['battle_exp'] += round($e,5);
				//echo '[['.$id1.']+'.$e.']';
				//
				if($mgregen == false && $nobattle_uron == false) {
					$this->users[$this->uids[$id1]]['battle_yron'] += floor($y);
					if($this->stats[$this->uids[$id1]]['notactic'] != 1) {
						if( $s2['hpAll'] <= 1000 ) {
							if( $this->stats[$this->uids[$id2]]['this_animal'] == 0 ) {
								$this->users[$this->uids[$id1]]['tactic6'] += round(0.1*(floor($y)/$s2['hpAll']*100),10);
							}else{
								$this->users[$this->uids[$id1]]['tactic6'] += round(0.1*(floor($y)/$s2['hpAll']*100),10)/3;
							}
						}else{
							if( $this->stats[$this->uids[$id2]]['this_animal'] == 0 ) {
								$this->users[$this->uids[$id1]]['tactic6'] += round(0.1*(floor($y)/1000*100),10);	
							}else{
								$this->users[$this->uids[$id1]]['tactic6'] += round(0.1*(floor($y)/1000*100),10)/3;
							}
						}						
					}
				}
								
				//if($y != 0) {
				//	$this->users[$this->uids[$id1]]['tactic6'] = -$y;
				//} 
				//if($u->info['admin'] > 0 ) {
				//	echo '['.$id1.' ������ '.$id2.' � ������� +'.$y.' � ����������� ����� � +'.$e.' �����]';
				//}
				 
				$upd = mysql_query('UPDATE `stats` SET `last_hp` = "'.$this->users[$this->uids[$id1]]['last_hp'].'",`tactic6` = "'.$this->users[$this->uids[$id1]]['tactic6'].'",`battle_yron` = "'.$this->users[$this->uids[$id1]]['battle_yron'].'",`battle_exp` = "'.$this->users[$this->uids[$id1]]['battle_exp'].'" WHERE `id` = "'.((int)$id1).'" LIMIT 1');
				if(!$upd){
					echo '[�� ����� ��� ������������� ������]';	
				}else{
					$this->stats[$this->uids[$id1]]['battle_exp'] = $this->users[$this->uids[$id1]]['battle_exp'];
					$this->clear_cache( $id1 );
					$this->stats[$this->uids[$id1]]['tactic6'] = $this->users[$this->uids[$id1]]['tactic6'];
					if($id1==$u->info['id'])
					{
						$u->info['tactic6'] = $this->users[$this->uids[$id1]]['tactic6'];
						$u->stats['tactic6'] = $this->users[$this->uids[$id1]]['tactic6'];
						$u->info['battle_exp'] = $this->users[$this->uids[$id1]]['battle_exp'];
						$u->info['battle_yron'] = $this->users[$this->uids[$id1]]['battle_yron'];
						$u->info['notactic'] = $this->users[$this->uids[$id1]]['notactic'];
						$u->stats['notactic'] = $this->users[$this->uids[$id1]]['notactic'];
					}
				}
				unset($s1,$s2);
			}
		}
	//��������� ���������� ����
		public function takeYronNow($uid,$y){
			global $u;
			$this->users[$this->uids[$uid]]['battle_yron'] += floor($y);
			$this->stats[$this->uids[$uid]]['battle_yron'] += floor($y);
			if( $uid == $u->info['id'] ) {
				$u->info['battle_yron'] += floor($y);
				$u->stats['battle_yron'] += floor($y);
			}
			mysql_query('UPDATE `stats` SET `battle_yron` = `battle_yron` + "'.mysql_real_escape_string(floor($y)).'" WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1');
		}
	//JS ���������� � ������
		public function myInfo($id,$t)
		{
			global $c,$u;
			if(isset($this->users[$this->uids[$id]]) || $u->info['id'] == $id)
			{
				if($u->info['id'] == $id || ($u->info['enemy'] == $id && $id > 0)) {
					//������ ���������
					$this->users[$this->uids[$id]] = mysql_fetch_array(mysql_query('SELECT 
					
					`u`.`zag`,`u`.`id`,`u`.`login`,`u`.`real`,`u`.`login2`,`u`.`online`,`u`.`admin`,`u`.`city`,`u`.`cityreg`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`clan`,
					`u`.`level`,`u`.`money`,`u`.`money3`,`u`.`money4`,`u`.`battle`,`u`.`sex`,`u`.`obraz`,`u`.`win`,`u`.`win_t`,
					`u`.`lose`,`u`.`lose_t`,`u`.`nich`,`u`.`timeMain`,`u`.`invis`,`u`.`bot_id`,`u`.`animal`,`u`.`type_pers`,
					`u`.`notrhod`,`u`.`bot_room`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`activ`,`u`.`stopexp`,`u`.`real`,
										
					`st`.*
					
					FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id` = "'.$id.'" LIMIT 1'));
					$this->stats[$this->uids[$id]] = $u->getStats($this->users[$this->uids[$id]],0,0,false,false,true);
					$this->stats[$this->uids[$id]]['items'] = $this->stats[$this->uids[$id]]['items'];
					$this->stats[$this->uids[$id]]['effects'] = $this->stats[$this->uids[$id]]['effects'];
					/*
					$ur   = $this->users[$this->uids[$id]];
					$st   = $this->stats[$this->uids[$id]];
					$itm  = $this->stats[$this->uids[$id]]['items'];
					$eff  = $this->stats[$this->uids[$id]]['effects'];
					*/
				}
					
				//ssecho '['.$id.' -> '.$this->users[$this->uids[$id]]['last_hp'].']';
								
				$ur   = $this->users[$this->uids[$id]];
				$st   = $this->stats[$this->uids[$id]];
				$itm  = $this->stats[$this->uids[$id]]['items'];
				$eff  = $this->stats[$this->uids[$id]]['effects'];
				$ef   = '';
				$i    = 0;
				while($i!=-1)
				{
					$nseef = 0;
					if($this->users[$this->uids[$ur['id']]]['id'] != $u->info['id'] && $ur['id'] != 0)
					{
						if($this->stats[$this->uids[$ur['id']]]['seeAllEff'] != 1) {
							$nseef = 1;
							if($eff[$i]['v1']=='priem')
							{
								$eff[$i]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i]['v2'].'" LIMIT 1'));
							}
							if(isset($eff[$i]['priem']['id']) && $eff[$i]['priem']['neg']==1)
							{
								$nseef = 0;
							}
						}
					}
					
					if(isset($eff[$i]) && $eff[$i] != 'delete')
					{
						if($nseef == 0)
						{
							
							$ei = '<b><u>'.$eff[$i]['name'].'</u></b>';
							if($eff[$i]['x']>1)
							{
								//$ei .= ' [x'.$eff[$i]['x'].'] ';	
								$ei .= ' x'.$eff[$i]['x'].' ';	
							}
							if($eff[$i]['type1']>0 && $eff[$i]['type1']<7)
							{
								$ei .= ' (�������)';
							}elseif(($eff[$i]['type1']>6 && $eff[$i]['type1']<11) || $eff[$i]['type1']==16)
							{
								$ei .= ' (��������)';
							}elseif($eff[$i]['type1']==14)
							{
								$ei .= ' (�����)';
							}elseif($eff[$i]['type1']==15)
							{
								$ei .= ' (��������)';
							}elseif($eff[$i]['type1']==17)
							{
								$ei .= ' (���������)';
							}elseif($eff[$i]['type1']==18 || $e['type1']==19)
							{
								$ei .= ' (������)';
							}elseif($eff[$i]['type1']==20)
							{
								$ei .= ' (�����������)';
							}elseif($eff[$i]['type1']==22)
							{
								$ei .= ' (��������)';
							}else{
								$ei .= ' (������)';
							}
							$ei .= '<br>';
							
							$out = '';
							$time_still = ($eff[$i]['timeUse']+($eff[$i]['timeAce']-$eff[$i]['timeUse'])+$eff[$i]['actionTime']);
							if($eff[$i]['timeAce'] == 0) {
								$time_still += $eff[$i]['timeUse'];
							}
							$time_still -= time();
							if($eff[$i]['bp']==0 && $eff[$i]['timeUse']!=77)
							{
								if($eff[$i]['type1']!=13)
								{
									/*$tmp = floor($time_still/2592000);
									$id=0;
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." ���. ";}
										$time_still = $time_still-$tmp*2592000;
									}
									$tmp = floor($time_still/604800);
									if ($tmp > 0) { 
									$id++;
									if ($id<3) {$out .= $tmp." ���. ";}
									$time_still = $time_still-$tmp*604800;
									}
									$tmp = floor($time_still/86400);
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." ��. ";}
										$time_still = $time_still-$tmp*86400;
									}
									$tmp = floor($time_still/3600);
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." �. ";}
										$time_still = $time_still-$tmp*3600;
									}
									$tmp = floor($time_still/60);
									if ($tmp > 0) { 
										$id++;
										if ($id<3) {$out .= $tmp." ���. ";}
									}
									if($out=='')
									{
										$out = $time_still.' ���.';
									}*/
									$ei .= '��������: '.$u->timeOut($time_still).'';
								}
							}else{
								if($eff[$i]['timeUse']!=77 && $eff[$i]['hod'] < 1)
								{
									$ei .= '��������: '.$u->timeOut($time_still).'';
									//$ei .= '�������: '.$out.'<br>';
								}elseif($eff[$i]['hod']>=0)
								{
									$ei .= '�������: '.$eff[$i]['hod'].'';	
								}
							}
							
							if($eff[$i]['user_use']!='')
							{
								if($this->users[$this->uids[$eff[$i]['user_use']]]['login2']!='') {
									$ei .= '<br>�����: <b>'.$this->users[$this->uids[$eff[$i]['user_use']]]['login2'].'</b>';
								}elseif($this->users[$this->uids[$eff[$i]['user_use']]]['login']!='') {
									$ei .= '<br>�����: <b>'.$this->users[$this->uids[$eff[$i]['user_use']]]['login'].'</b>';
								}
							}
							
							//�������� �������
							$tr = '';
							$ti = $u->items['add'];
							$x = 0; $ed = $u->lookStats($eff[$i]['data']);	
							while($x<count($ti))
							{
								$n = $ti[$x];
								if(isset($ed['add_'.$n],$u->is[$n]) && $n!='pog')
								{
									$z = '';
									if($ed['add_'.$n]>0)
									{
										$z = '+';
									}
									$tr .= '<br>'.$u->is[$n].': '.$z.''.$ed['add_'.$n];
								}
								$x++;
							}
							
							if( $ed['add_mib1'] != 0  ) {
								if( $ed['add_mab1'] != 0 ) {						
									$tr .= '<br>����� ������: '.$ed['add_mib1'].'-'.$ed['add_mab1'].'';
								}else{
									$m1l = $ed['add_mib1'];	
									if( $m1l > 0 ) {
										$m1l = '+'.$m1l;
									}
									$tr .= '<br>����� ������: '.$m1l.'';
								}
							}
							if( $ed['add_mib2'] != 0  ) {
								if( $ed['add_mab2'] != 0 ) {						
									$tr .= '<br>����� �������: '.$ed['add_mib2'].'-'.$ed['add_mab2'].'';
								}else{
									$m1l = $ed['add_mib2'];	
									if( $m1l > 0 ) {
										$m1l = '+'.$m1l;
									}
									$tr .= '<br>����� �������: '.$m1l.'';
								}
							}
							if( $ed['add_mib3'] != 0  ) {
								if( $ed['add_mab3'] != 0 ) {						
									$tr .= '<br>����� �����: '.$ed['add_mib3'].'-'.$ed['add_mab3'].'';
								}else{
									$m1l = $ed['add_mib3'];	
									if( $m1l > 0 ) {
										$m1l = '+'.$m1l;
									}
									$tr .= '<br>����� �����: '.$m1l.'';
								}
							}
							if( $ed['add_mib4'] != 0  ) {
								if( $ed['add_mab4'] != 0 ) {						
									$tr .= '<br>����� ���: '.$ed['add_mib4'].'-'.$ed['add_mab4'].'';
								}else{
									$m1l = $ed['add_mib4'];	
									if( $m1l > 0 ) {
										$m1l = '+'.$m1l;
									}
									$tr .= '<br>����� ���: '.$m1l.'';
								}
							}
							
							$efix = 0;
							if( isset($ed['add_pog2']) && $ed['add_pog2'] > 0 ) {
								$efix = $ed['add_pog2'];
							}
							if(isset($ed['add_pog']))
							{
								$tr .= '<br>���������� ������ �������� ��������� ��� <b>'.$ed['add_pog'].'</b> ��. �����';
							}
							if(isset($ed['add_pog2']))
							{
								$tr .= '<br>���������� ������ �������� ��������� ��� <b>'.$ed['add_pog2'].'</b> ��. ����� <small>('.$ed['add_pog2p'].'%)</small>';
							}
							
							if($tr!='')
							{
								$ei .= $tr;
							}
							if($eff[$i]['info']!='')
							{
								$ei .= '<br><i>����������:</i><br>'.$eff[$i]['info'];
							}

							//$ef .= '<img width=\"38\" style=\"margin:1px;display:block;float:left;\" src=\"http://img.xcombats.com/i/eff/'.$eff[$i]['img'].'\" onMouseOver=\"top.hi(this,\''.$ei.'\',event,0,1,1,1,\'\');\" onMouseOut=\"top.hic();\" onMouseDown=\"top.hic();\" />';
							$ef .= '<div class=\"pimg\" pog=\"'.$efix.'\" col=\"'.$eff[$i]['x'].'\" stl=\"0\" stt=\"'.$ei.'\"><img src=\"http://img.xcombats.com/i/eff/'.$eff[$i]['img'].'\"/></div>';
							unset($efix);
						}
					}elseif($eff[$i]!='delete')
					{
						$i = -2;
					}
					$i++;
				}
				
				if( $st['itmslvl'] == 0 && $ur['bot_id'] == 0) {
					//$ef .= '<div class=\"pimg\" pog=\"0\" col=\"0\" stl=\"0\" stt=\"<b><u>������ ����������</u></b> (������)<br>��������: <i>����������</i>\"><img src=\"http://img.xcombats.com/i/eff/light_armor.gif\"/></div>';
				}
				
				$ca = '';
				if($ur['clan']>0)
				{
					$cl = mysql_fetch_array(mysql_query('SELECT * FROM `clan` WHERE `id` = "'.$ur['clan'].'" LIMIT 1'));
					if(isset($cl['id']))
					{
						$ca = '<img src=\"http://img.xcombats.com/i/clan/'.$cl['name_mini'].'.gif\" title=\"'.$cl['name'].'\">';
					}
				}
				if($ur['align']>0)
				{
					$ca = '<img src=\"http://img.xcombats.com/i/align/align'.$ur['align'].'.gif\">'.$ca;
				}
				if($ur['login2']=='')
				{
					$ur['login2'] = $ur['login'];
				}
				if(floor($st['hpNow'])>$st['hpAll'])
				{
					$st['hpNow'] = $st['hpAll'];
				}
				if(floor($st['mpNow'])>$st['mpAll'])
				{
					$st['mpNow'] = $st['mpAll'];
				}
				$stsua  = '<b>'.$ur['login2'].'</b>';
				$stsua .= '<br>����: '.$st['s1'];
				$stsua .= '<br>��������: '.$st['s2'];
				$stsua .= '<br>��������: '.$st['s3'];
				$stsua .= '<br>������������: '.$st['s4'];
				if($st['s5'] != 0) {
					$stsua .= '<br>��������: '.$st['s5'];
				}
				if($st['s6'] != 0) {
					$stsua .= '<br>��������: '.$st['s6'];
				}
				if($u->info['admin']>0){
					if(isset($ur['align']) && $ur['align'] == 9){
						$align = $ur['align'];
					} else {
						$align = $ur['align'];
					}
				}
				$tp_img = array(
					1 => 4,
					2 =>5,
					14 => 6,
					3 => 7,
					5 => 8,
					7 => 9,
					17 => 10,
					16 => 11,
					13 => 12,
					10 => 13,
					9 => 14,
					8 => 15,
					11 => 17, //������ 2
					12 => 18 //������ 3		
				);
				$info = 'info_reflesh('.$t.','.$ur['id'].',"'.$ca.'<a href=\"javascript:void(0)\" onclick=\"top.chat.addto(\''.$ur['login2'].'\',\'to\');return false;\">'.$ur['login2'].'</a> ['.$ur['level'].']<a href=\"info/'.$ur['id'].'\" target=\"_blank\"><img src=\"http://img.xcombats.com/i/inf_'.$ur['cityreg'].'.gif\" title=\"���. � '.$ur['login2'].'\"></a>&nbsp;","'.$ur['obraz'].'",'.floor($st['hpNow']).','.floor($st['hpAll']).','.floor($st['mpNow']).','.floor($st['mpAll']).',0,'.$ur['sex'].',"'.$ef.'","'.$stsua.'", "'.$align.'", "'.$ur['zag'].'");shpb();';
				$i = 0;
				while($i<count($itm))
				{
					//
					if(isset($st['items_img'][$tp_img[$itm[$i]['inOdet']]])) {
						$itm[$i]['img'] = $st['items_img'][$tp_img[$itm[$i]['inOdet']]];
					}
					//���������� ��������
					$ttl = '<b>'.$itm[$i]['name'].'</b>';
					$td = $u->lookStats($itm[$i]['data']);				
					$lvar = '';
					if($td['add_hpAll']>0)
					{
						if($td['add_hpAll']>0)
						{
							$td['add_hpAll'] = '+'.$td['add_hpAll'];
						}
						$lvar .= '<br>������� �����: '.$td['add_hpAll'].'';
					}
					if($td['sv_yron_max']>0 || $td['sv_yron_min']>0)
					{
						$lvar .= '<br>����: '.(0+$td['sv_yron_min']).'-'.(0+$td['sv_yron_max']).'';
					}
					if($td['add_mab1']>0)
					{
						if($td['add_mib1']==$td['add_mab1'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab1']<0){ $m1l = ''; }
							$lvar .= '<br>����� ������: '.$m1l.''.(0+$td['add_mab1']).'';
						}else{
							$lvar .= '<br>����� ������: '.(0+$td['add_mib1']).'-'.(0+$td['add_mab1']).'';
						}
					}
					if($td['add_mab2']>0)
					{
						if($td['add_mib2']==$td['add_mab2'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab2']<0){ $m1l = ''; }
							$lvar .= '<br>����� �������: '.$m1l.''.(0+$td['add_mab2']).'';
						}else{
							$lvar .= '<br>����� �������: '.(0+$td['add_mib2']).'-'.(0+$td['add_mab2']).'';
						}
					}
					if($td['add_mab3']>0)
					{
						if($td['add_mib3']==$td['add_mab3'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab3']<0){ $m1l = ''; }
							$lvar .= '<br>����� �����: '.$m1l.''.(0+$td['add_mab3']).'';
						}else{
							$lvar .= '<br>����� �����: '.(0+$td['add_mib3']).'-'.(0+$td['add_mab3']).'';
						}
					}
					if($td['add_mab4']>0)
					{
						if($td['add_mib4']==$td['add_mab4'] && $pl['geniration']==1)
						{
							$m1l = '+'; if($td['add_mab4']<0){ $m1l = ''; }
							$lvar .= '<br>����� ���: '.$m1l.''.(0+$td['add_mab4']).'';
						}else{
							$lvar .= '<br>����� ���: '.(0+$td['add_mib4']).'-'.(0+$td['add_mab4']).'';
						}
					}				
					if($itm[$i]['iznosMAX']>0)
					{
						if($itm[$i]['iznosMAXi'] == 999999999) {
							$lvar .= '<br>�������������: <font color=brown>�����������</font>';
						}else{
							$lvar .= '<br>�������������: '.floor($itm[$i]['iznosNOW']).'/'.floor($itm[$i]['iznosMAX']);
						}
					}
					$ttl .= $lvar;
					$ccv = '';
					
					if($itm[$i]['magic_inci']!='' || $itm[$i]['magic_inc']!='') {
						if($itm[$i]['magic_inc'] == '') {
							$itm[$i]['magic_inc'] = $itm[$i]['magic_inci'];
						}
						$mgi = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$itm[$i]['magic_inc'].'" AND `type1` = "12345" LIMIT 1'));
						if(isset($mgi['id2'])) {
							$mgilog = '';
							$ccv .= 'top.useMagicBattle(\''.$mgi['mname'].'\','.$itm[$i]['id'].',\''.$mgi['img'].'\',1,2);';
						}
					}
					
					$info .= 'abitms('.(0+$t).','.(0+$itm[$i]['uid']).','.(0+$itm[$i]['id']).','.(0+$itm[$i]['inOdet']).',"'.$itm[$i]['name'].'","'.$ttl.'","'.$itm[$i]['img'].'","'.$ccv.'");';
					$i++;
				}
				
				return $info;
			}else{
				return false;
			}
		}
		
	//�������� �� ��������
		public function testUsersLive() {
			$r = false;
			$tl = 0;
			$i = 0;
			$j = 0;
			while($i<count($this->uids))
			{
				if($this->stats[$i]['id']>0)
				{
					if( floor($this->stats[$i]['hpNow']) < 1 ) {
						$this->stats[$i]['hpNow'] = 0;
					}
					$hp[$this->users[$i]['team']] += floor($this->stats[$i]['hpNow']);
					if(!isset($tml[$this->users[$i]['team']]) && floor($this->stats[$i]['hpNow']) >= 1)
					{
						$tml[$this->users[$i]['team']] = 1;
						$tmv[$j] = $this->users[$i]['team'];
						$tl++;
					}
				}
				$i++;
			}
			if( $tl > 1 ) {
				$r = true;
			}
			return $r;
		}
			
	//���� ���
		public function miniLogAdd( $user , $text ) {
			$txt = $text;									
			$vLog = 'at1=00000||at2=00000||zb1=0||zb2=0||bl1=0||bl2=0||time1='.time().'||time2='.time().'||s1='.$user['sex'].'||t1='.$user['team'].'||login1='.$user['login'].'||';									
			$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>($this->hodID+1),'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');									
			$mas1['text'] = $txt;
			$this->add_log($mas1);
		}
				
	//��������� ���������� ���
		public function testFinish()
		{
			global $u;
			//
			mysql_query('START TRANSACTION;');
			//
			$test = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `team_win` = -1 LIMIT 1 FOR UPDATE'));
			//mysql_query('UPDATE `battle` SET `testfinish` = "'.$u->info['id'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
			//
			mysql_query('COMMIT;');
			//
			if($this->info['team_win'] == -1 && isset($test['id']))
			{
								
				$hp = array();
				$tml = array();
				$tmv = array();
				$tl = 0;
				$i = 0;
				$j = 0;
				while($i<count($this->uids))
				{
					if($this->stats[$i]['id']>0)
					{
						if( floor($this->stats[$i]['hpNow']) < 1 ) {
							$this->stats[$i]['hpNow'] = 0;
						}
						$hp[$this->users[$i]['team']] += floor($this->stats[$i]['hpNow']);
						if(!isset($tml[$this->users[$i]['team']]) && floor($this->stats[$i]['hpNow']) >= 1)
						{
							$tml[$this->users[$i]['team']] = 1;
							$tmv[$j] = $this->users[$i]['team'];
							$tl++;
						}
					}
					$i++;
				}
				
				if($tl<=1) {
					//���.��������
					$tmHpNow = array();
					$tmNow = array();
					$sp = mysql_query('SELECT `u`.`login`,`u`.`id`,`u`.`battle`,`s`.`team`,`s`.`hpNow` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `u`.`battle` = "'.$this->info['id'].'"');
					while( $pl = mysql_fetch_array($sp) ) {
						if( !isset($tmHpNow[$pl['team']])) {
							$tmHpNow[$pl['team']] = 0;
							$tmNow[] = $pl['team'];
						}
						$hpTm = floor($pl['hpNow']);
						if( $hpTm < 0 ) {
							$hpTm = 0;
						}
						if( $hpTm > 0 ) {
							$tmHpNow[$pl['team']] += $pl['hpNow'];
						}
					}
					$gdj = 0;
					$i = 0;
					while( $i < count($tmNow) ) {
						if( isset($tmNow[$i]) ) {
							$j = $tmNow[$i];
							if( $tmHpNow[$j] > 0 ) {
								$gdj++;
							}
						}
						$i++;
					}
					if( $gdj > 1 ) {
						$tl = $gdj;
						echo '�������� ����� ����������� �� ���������... (�������� ������������� �� ����)';
					}
				}

				if($tl <= 1) {					
					//��������� ��������, ���-�� ���� �������, ���� �����
					
					$i = 0; $tmwin = 0;
					while($i<count($tmv))
					{
						if($tmv[$i]>=1 && $tml[$tmv[$i]]>0)
						{
							$tmwin = $tmv[$i];
						}
						$i++;
					}

					if($this->info['izlom'] == 0) {
						$rs = ''; $ts = array(); $tsi = 0;
						if($this->info['id']>0)
						{
							//������ � ������� � ���
							unset($this->users,$this->stats,$this->uids,$this->bots,$this->iBots);
							$trl = mysql_query('SELECT `u`.`no_ip`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`lider`,`st`.`btl_cof`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`st`.`last_pr`,`u`.`sex`,`u`.`money`,`u`.`money3`,`u`.`bot_id` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'" ORDER BY `st`.`bot` DESC');
							$ir = 0; $bi = 0;
							$this->users = NULL;
							$this->stats = NULL;
							$this->uids = NULL;
							$this->bots = NULL;
							$this->iBots = NULL;
							while($pl = mysql_fetch_array($trl))
							{
								//���������� ������
								if($pl['login2']=='')
								{
									$pl['login2'] = $pl['login'];
								}
								$this->users[$ir] = $pl;
								$this->uids[$pl['id']] = $ir;
								if($pl['bot']>0)
								{
									$this->bots[$bi] = $pl['id'];
									$this->iBots[$pl['id']] = $bi;
									$bi++;
								}
								//���������� �����
								$this->stats[$ir] = $u->getStats($pl,0,0,false,false,true);
								$ir++;
							}
						}
					}elseif(!isset($this->uids[$u->info['id']])) {
						$rs = ''; $ts = array(); $tsi = 0;
						if($this->info['id']>0)
						{
							//������ � ������� � ���
							$trl = mysql_query('SELECT `u`.`no_ip`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`lider`,`st`.`btl_cof`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`st`.`last_pr`,`u`.`sex`,`u`.`money`,`u`.`bot_id`,`u`.`money3` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id` = "'.$this->info['id'].'" LIMIT 1');
							$pl = mysql_fetch_array($trl);
								//���������� ������
								if($pl['login2']=='')
								{
									$pl['login2'] = $pl['login'];
								}
								$this->users[count($this->users)] = $pl;
								$this->uids[$pl['id']] = $ir;
								if($pl['bot']>0)
								{
									$this->bots[count($this->bots)] = $pl['id'];
									$this->iBots[$pl['id']] = $bi;
								}
								//���������� �����
								$this->stats[count($this->stats)] = $u->getStats($pl,0,0,false,false,true);
						}
					}
					

					
					if($this->info['izlom']>0 && $tmwin==1)
					{					
						// ���������� ����� �� ���
						$i = 0; $dlt = ''; $dlt2 = '';
						$sp = mysql_query('SELECT `users`.`id`,`stats`.`bot`,`stats`.`team` FROM `users`,`stats` WHERE `users`.`battle` = "'.$this->info['id'].'" AND `stats`.`id` = `users`.`id` LIMIT 10000');
						while($pl = mysql_fetch_array($sp))
						{
							if($pl['bot']==1 &&$pl['team'] != $u->info['team'])
							{
								$dlt .= ' `id`="'.$pl['id'].'" OR';
								$dlt2 .= ' `uid`="'.$pl['id'].'" OR';
								$i++;
							}
							
						}
						
						if($i > 0) {
							$dlt = trim($dlt,'OR');
							$dlt2 = trim($dlt2,'OR');
							mysql_query('DELETE FROM `users` WHERE '.$dlt.' LIMIT '.$i);
							mysql_query('DELETE FROM `stats` WHERE '.$dlt.' LIMIT '.$i);
							mysql_query('DELETE FROM `items_users` WHERE '.$dlt2.' LIMIT '.($i*100));
							mysql_query('DELETE FROM `eff_users` WHERE '.$dlt2.' LIMIT '.($i*100));
						}
						
						unset($i,$dlt,$dlt2);
						
						$j = 0; $k = 0; $obr = 0;
						
						//��� �����, ��������� ��� �����
							if($this->get_chanse(10) == true ) {
								//���������� �������
								if( $this->info['izlomLvl'] == 8 ) {
									$bots = array( '������������ �������','�������� �����','��������','��� �����','������� �����' );
									//$bots = array( '������������ �������' );
								}
								$logins_bot = array();
								//
								echo '<center><b><font color=red>������������ �����...</font></b></center>';
								//
								$id2 = rand(0,(count($bots)-1));			
								$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
								$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL,round($this->info['izlomRoundSee']));
								if(isset($id['id']) && $bot != false) {
									//
									$btxt = '';
									if( $id['align'] > 0 ) {
										$btxt = $btxt.'<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$id['align'].'.gif >';
									}
									if( $id['clan'] > 0 ) {
										$btxt = $btxt.'<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$id['clan'].'.gif >';
									}
									$btxt = $btxt.'<b>{u1}</b>['.$id['level'].']<a href=info/'.$id['id'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
									if( $id['sex'] == 1 ) {
										$btxt = $btxt.' ��������� � ��������.';
									}else{
										$btxt = $btxt.' �������� � ��������.';
									}
									$this->miniLogAdd(array(
										'login' => $id['login'],
										'sex'	=> $id['sex'],
										'team'	=> 0
									),'{tm1} '.$btxt);
									//
									$logins_bot = $bot['logins_bot'];
									mysql_query('UPDATE `users` SET `battle`="'.$this->info['id'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
									if(rand(0,10000) < 1500){ $obr++; }
									$j++;
								}
							}else{
								//������� �������
								if( $this->info['izlomLvl'] == 8 ) {
									$bots = array( '��������','��������� ������','��������� ������','��������� ������','������� ����','���������� ����','������ ����' );
								}
								$logins_bot = array();
								//
								$id2 = rand(0,(count($bots)-1));			
								$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `level` <= "'.$u->info['level'].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
								$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL,$this->info['izlomRoundSee']);
								if(isset($id['id']) && $bot != false) {
									//
									$btxt = '';
									if( $id['align'] > 0 ) {
										$btxt = $btxt.'<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$id['align'].'.gif >';
									}
									if( $id['clan'] > 0 ) {
										$btxt = $btxt.'<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$id['clan'].'.gif >';
									}
									$btxt = $btxt.'<b>{u1}</b>['.$id['level'].']<a href=info/'.$id['id'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
									if( $id['sex'] == 1 ) {
										$btxt = $btxt.' ��������� � ��������.';
									}else{
										$btxt = $btxt.' �������� � ��������.';
									}
									$this->miniLogAdd(array(
										'login' => $id['login'],
										'sex'	=> $id['sex'],
										'team'	=> 0
									),'{tm1} '.$btxt);
									//
									$logins_bot = $bot['logins_bot'];
									mysql_query('UPDATE `users` SET `battle`="'.$this->info['id'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
									if(rand(0,10000) < 1500){ $obr++; }
									$j++;
								}
								//
								$id2 = rand(0,(count($bots)-1));			
								$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `level` <= "'.$u->info['level'].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
								$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL,$this->info['izlomRoundSee']);
								if(isset($id['id']) && $bot != false) {
									//
									$btxt = '';
									if( $id['align'] > 0 ) {
										$btxt = $btxt.'<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$id['align'].'.gif >';
									}
									if( $id['clan'] > 0 ) {
										$btxt = $btxt.'<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$id['clan'].'.gif >';
									}
									$btxt = $btxt.'<b>{u1}</b>['.$id['level'].']<a href=info/'.$id['id'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
									if( $id['sex'] == 1 ) {
										$btxt = $btxt.' ��������� � ��������.';
									}else{
										$btxt = $btxt.' �������� � ��������.';
									}
									$this->miniLogAdd(array(
										'login' => $id['login'],
										'sex'	=> $id['sex'],
										'team'	=> 0
									),'{tm1} '.$btxt);
									//
									$logins_bot = $bot['logins_bot'];
									mysql_query('UPDATE `users` SET `battle`="'.$this->info['id'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
									if(rand(0,10000) < 1500){ $obr++; }
									$j++;
								}
								//
								if( rand(0,100) < 70 ) {
									$id2 = rand(0,(count($bots)-1));			
									$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `level` <= "'.$u->info['level'].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
									$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL,$this->info['izlomRoundSee']);
									if(isset($id['id']) && $bot != false) {
									//
									$btxt = '';
									if( $id['align'] > 0 ) {
										$btxt = $btxt.'<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$id['align'].'.gif >';
									}
									if( $id['clan'] > 0 ) {
										$btxt = $btxt.'<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$id['clan'].'.gif >';
									}
									$btxt = $btxt.'<b>{u1}</b>['.$id['level'].']<a href=info/'.$id['id'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
									if( $id['sex'] == 1 ) {
										$btxt = $btxt.' ��������� � ��������.';
									}else{
										$btxt = $btxt.' �������� � ��������.';
									}
									$this->miniLogAdd(array(
										'login' => $id['login'],
										'sex'	=> $id['sex'],
										'team'	=> 0
									),'{tm1} '.$btxt);
									//
										$logins_bot = $bot['logins_bot'];
										mysql_query('UPDATE `users` SET `battle`="'.$this->info['id'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
										mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
										if(rand(0,10000) < 1500){ $obr++; }
										$j++;
									}
								}
								//������ 50 ��� = +1 ������ 
								$irb = floor($this->info['izlomRoundSee']/50);
								while( $irb > 0 ) {
									//
									if( rand(0,100) < 20 ) {
										$id2 = rand(0,(count($bots)-1));			
										$id = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `login` = "'.$bots[$id2].'" AND `level` <= "'.$u->info['level'].'" AND `pishera` != "" AND `active` = "1" ORDER BY `level` DESC LIMIT 1'));
										$bot = $u->addNewbot($id['id'],NULL,NULL,$logins_bot,NULL,$this->info['izlomRoundSee']);
										if(isset($id['id']) && $bot != false) {
											//
											$btxt = '';
											if( $id['align'] > 0 ) {
												$btxt = $btxt.'<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$id['align'].'.gif >';
											}
											if( $id['clan'] > 0 ) {
												$btxt = $btxt.'<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$id['clan'].'.gif >';
											}
											$btxt = $btxt.'<b>{u1}</b>['.$id['level'].']<a href=info/'.$id['id'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
											if( $id['sex'] == 1 ) {
												$btxt = $btxt.' ��������� � ��������.';
											}else{
												$btxt = $btxt.' �������� � ��������.';
											}
											$this->miniLogAdd(array(
												'login' => $id['login'],
												'sex'	=> $id['sex'],
												'team'	=> 0
											),'{tm1} '.$btxt);
											//
											$logins_bot = $bot['logins_bot'];
											mysql_query('UPDATE `users` SET `battle`="'.$this->info['id'].'" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
											mysql_query('UPDATE `stats` SET `team`="2" WHERE `id` = "'.$bot['id'].'" LIMIT 1');
											if(rand(0,10000) < 1500){ $obr++; }
											$j++;
										}
									}
									$irb--;
								}
							}
							//
							unset($logins_bot);
						//
						//
						//
						/*if( true == false ) {
							$mz = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `izlom` WHERE `izlom` = "'.$this->info['izlom'].'" AND `level` = "'.$this->info['izlomLvl'].'" LIMIT 50'));
							$mz = $mz[0];
							$pz = $this->info['izlomRound']+rand(1,3);
							if($pz/$mz>1){
								$zz = floor($pz/$mz);
								$pz = $pz-($zz*$mz);
							}
							$iz = mysql_fetch_array(mysql_query('SELECT * FROM `izlom` WHERE `izlom` = "'.$this->info['izlom'].'" AND `level` = "'.$this->info['izlomLvl'].'" AND `round` = "'.$pz.'" LIMIT 1'));
							$i = 0; $bots = $iz['bots']; $bots = explode('|',$bots); $j = 0; $k = 0; $obr = 0;
							$logins_bot = array();
							while($i<count($bots))
							{
								if($bots[$i]>0)
								{
									$k = $u->addNewbot($bots[$i],NULL,NULL,$logins_bot);
									if($k!=false)
									{
										$logins_bot = $k['logins_bot'];
										$upd = mysql_query('UPDATE `users` SET `battle` = "'.$this->info['id'].'" WHERE `id` = "'.$k['id'].'" LIMIT 1');
										if($upd)
										{
											$upd = mysql_query('UPDATE `stats` SET `team` = "2" WHERE `id` = "'.$k['id'].'" LIMIT 1');
											if($upd)
											{
												$j++; if(rand(0,10000) < 1500){ $obr++; }
											}
										}
									}
								}
								$i++;
							}	
							unset($logins_bot);
						}*/
						//
						//
						//
						if($j==0)
						{
							//����� ������
							$this->finishBattle($tml,$tmv,NULL,$tl);	
							$fin1 = mysql_query('INSERT INTO `izlom_rating` (`uid`,`time`,`voln`,`level`,`bots`,`rep`,`obr`,`btl`) VALUES ("'.$u->info['id'].'","'.time().'","'.$this->info['izlomRoundSee'].'","'.$this->info['izlomLvl'].'","0","0","'.($this->info['izlomObr']-$this->info['izlomObrNow']).'","'.$this->info['id'].'")');
						}else{
							$this->info['izlomRound'] = $iz['round'];
							mysql_query('UPDATE `battle` SET `izlomObrNow` = '.$obr.',`izlomObr` = `izlomObr` + '.$obr.',`timeout` = (`timeout`+5),`izlomRound` = "'.($this->info['izlomRound']+1).'",`izlomRoundSee` = `izlomRoundSee`+1 WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
							$this->stats[$this->uids[$u->info['id']]]['hpNow'] += $this->stats[$this->uids[$u->info['id']]]['hpAll']*0.25;
							$this->stats[$this->uids[$u->info['id']]]['mpNow'] += $this->stats[$this->uids[$u->info['id']]]['mpAll']*0.25;
							$this->users[$this->uids[$u->info['id']]]['hpNow'] = $this->stats[$this->uids[$u->info['id']]]['hpAll'];
							$this->users[$this->uids[$u->info['id']]]['mpNow'] = $this->stats[$this->uids[$u->info['id']]]['mpAll'];
											$vLog = 'at1=00000||at2=00000||zb1='.$this->stats[$this->uids[$u1]]['zonb'].'||zb2='.$this->stats[$this->uids[$u2]]['zonb'].'||bl1='.$this->atacks[$id]['b'.$a].'||bl2='.$this->atacks[$id]['b'.$b].'||time1='.$this->atacks[$id]['time'].'||time2='.$this->atacks[$id]['time2'].'||s2='.$this->users[$this->uids[$u2]]['sex'].'||s1='.$this->users[$this->uids[$u1]]['sex'].'||t2='.$this->users[$this->uids[$u2]]['team'].'||t1='.$this->users[$this->uids[$u1]]['team'].'||login1='.$this->users[$this->uids[$u1]]['login2'].'||login2='.$this->users[$this->uids[$u2]]['login2'].'';
				
							$mas = array(
								'text' => '',
								'time' => time(),
								'vars' => '',
								'battle' => $this->info['id'],
								'id_hod' => ($this->hodID+1),
								'vars' => $vLog,
								'type' => 1
							);
							if( $u->info['sex'] == 1 ) {
								$mas['text'] = '<span class=date>'.date('H:i').'</span> <b>'.$u->info['login'].'</b> ��������������� ������� &quot;<b>���������</b>&quot;.';
							}else{
								$mas['text'] = '<span class=date>'.date('H:i').'</span> <b>'.$u->info['login'].'</b> �������������� ������� &quot;<b>���������</b>&quot;.';
							}
							if( $u->stats['hpNow'] < $u->stats['hpAll'] ) {
								$hpSks = floor(($u->stats['hpAll']*((rand(15,25))/100)));
								if( $hpSks > floor($u->stats['hpAll'] - $u->stats['hpNow']) ) {
									$hpSks = floor($u->stats['hpAll'] - $u->stats['hpNow']);
								}
								$mas['text'] .= ' <font color=#0066aa><b>+'.$hpSks.'</b></font>';
							}else{
								$hpSks = 0;
								$mas['text'] .= ' <font color=#0066aa><b>--</b></font>';
							}
							$mas['text'] .= ' ['.floor($u->info['hpNow']+$hpSks).'/'.$u->stats['hpAll'].']';
							$this->add_log($mas);
					
							mysql_query('UPDATE `stats` SET `hpNow` = "'.($u->info['hpNow']+($u->stats['hpAll']*((rand(15,25))/100))).'",`mpNow` = "'.($u->info['mpNow']+($u->stats['mpAll']*0.25)).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}				
					}else{
						//��������� ���
						$this->finishBattle($tml,$tmv,NULL,$tl);	
						if($this->info['izlom']>0)
						{
							$fin1 = mysql_query('INSERT INTO `izlom_rating` (`uid`,`time`,`voln`,`level`,`bots`,`rep`,`obr`,`btl`) VALUES ("'.$u->info['id'].'","'.time().'","'.$this->info['izlomRoundSee'].'","'.$this->info['izlomLvl'].'","0","0","'.($this->info['izlomObr']-$this->info['izlomObrNow']).'","'.$this->info['id'].'")');
						}
					}
					if(isset($fin1))
					{
						mysql_query('INSERT INTO `eff_users` (`no_Ace`,`id_eff`,`overType`,`uid`,`name`,`data`,`timeUse`) VALUES ("1","31","23","'.$u->info['id'].'","������� �����","|nofastfinisheff=1|","'.time().'")');
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','�� ������� ������� &quot;�������&quot;x".($this->info['izlomObr']-$this->info['izlomObrNow'])."','".time()."','6','0')");
						$i01 = 1;
						while($i01<=($this->info['izlomObr']-$this->info['izlomObrNow']))
						{				
							$u->addItem(1226,$u->info['id'],'|sudba='.$u->info['login']);
							$i01++;
						}
						unset($fin1);
					}
				}
			}else{
				mysql_query('START TRANSACTION;');
				$test = mysql_fetch_array(mysql_query('SELECT `id`,`team_win` FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `team_win` != -1 LIMIT 1 FOR UPDATE'));
				mysql_query('COMMIT;');
				if(isset($test['id'])) {
					$this->finishBattle(NULL,NULL,10,$tl);
				}
			}
			//
			//mysql_query('UPDATE `battle` SET `testfinish` = "0" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
		}
		
	//�������� ������ ����
		public function testBotQuest($uid,$bot_id) {
			$r = false;
			//
			/*$sp = mysql_query('SELECT `vars` FROM `actions` WHERE `uid` = "'.$uid.'" AND `vars` LIKE "%start_quest%" AND `vals` = "go"');
			while( $pl = mysql_fetch_array($sp) ) {
				if( $r == false ) {
					$qst_id = str_replace('start_quest','',$pl['vars']);
					$qst = mysql_fetch_array(mysql_query('SELECT `act_date` FROM `quests` WHERE `id` = "'.$qst_id.'" LIMIT 1'));
					if(isset($qst['id'])) {
						$act = explode(':|:',$qst['act_date']);
						$i = 0;
						while( $i < count($act) ) {
							$act2 = explode(':=:',$act[$i]);
							$bots = explode(',',$act2[1]);						
							$j = 0;
							while( $j < count($bots) ) {
								$bot = explode('=',$bots[$j]);
								if( $bot[0] == $bot_id ) {
									//
									$r = true;
									//
									$i = count($act);
									$j = count($bots);
								}
								$j++;
							}						
							$i++;
						}					
					}
				}
			}*/
			$r = true;
			//
			return $r;
		}
		
	//���������� ��������
		public function finishBattle($t,$v,$nl,$tl)
		{
			global $magic,$u,$q,$c;
			
			//if( $u->info['login'] == "LEL" ) {
				//die('!finish! �����...');
			//}
			
			$frtu = false;
			
			//$test = mysql_fetch_array(mysql_query('SELECT `id`,`testfinish` FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `team_win` = -1 LIMIT 1'));
			//if($test['testfinish'] != -1) {
			//mysql_query('UPDATE `battle` SET `testfinish` = "75" WHERE `id` = "'.$test['id'].'" LIMIT 1');
			//}
			/*
			$lock_file = 'lock/btl_finish_'.$this->info['id'].'.bk2'; 
			if ( !file_exists($lock_file) ) { 
				$fp_lock = fopen($lock_file, 'w');
				flock($fp_lock, LOCK_EX); 
			}else{
				$frtu = true;
			}
			*/	
			/*if(!isset($_GET['finish_battle_bot_core'])) {
				echo '<font color=red>�������� ���������� � ������� 5 ���! (�����)</font>';
			}else*/
			if($frtu == false) {
			
				$relbf = $this->info['team_win'];
				if($nl!=10)
				{
					$i = 0; $dnr = 0;
					if($this->info['team_win']==-1)
					{
						$this->info['team_win'] = 0;
						while($i<count($v))
						{
							if($v[$i]>=1 && $t[$v[$i]]>0)
							{
								$this->info['team_win'] = $v[$i];
							}
							$i++;
						}														
					}
				}
				//mysql_query('LOCK TABLES users,stats,battle,battle_last,battle_end,chat WRITE');
				
				//������ � ������� � ���
				$t = mysql_query('SELECT `u`.`stopexp`,`u`.`twink`,`u`.`city`,`u`.`room`,`u`.`no_ip`,`u`.`pass`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`lider`,`st`.`btl_cof`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`st`.`last_pr`,`u`.`sex`,`u`.`money`,`u`.`bot_id`,`u`.`money3` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'"');
				$i = 0; $bi = 0;
				while($pl = mysql_fetch_array($t))
				{
					//���������� ������
					if($pl['login2']=='')
					{
						$pl['login2'] = $pl['login'];
					}
					$this->users[$i] = $pl;
					$this->uids[$pl['id']] = $i;
					if($pl['bot']>0)
					{
						$this->bots[$bi] = $pl['id'];
						$this->iBots[$pl['id']] = $bi;
						$bi++;
					}
					//���������� �����
					$this->stats[$i] = $u->getStats($pl,0,0,false,false,true);
					$i++;
				}
			
			if($this->info['time_over']==0)
			{
				$tststrt = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `time_over` = "0" LIMIT 1'));
				if(isset($tststrt['id'])) {
					if( $this->info['inTurnir'] == 0 ) {
						mysql_query('UPDATE `battle` SET `time_over` = "'.time().'",`team_win` = "'.$this->info['team_win'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					}
					mysql_query('UPDATE `battle_stat` SET `team_win` = "'.$this->info['team_win'].'" WHERE `battle_id` = "'.$this->info['id'].'"');
					//������� ������ � ���������� ���
					$i = 0; $vl = ''; $vtvl = ''; $relu = 0;
					while($i<count($this->users))
					{
						/*if( $this->user[$i]['clon'] == 0 && $this->user[$i]['bot'] == 0 ) {
							$relu++;
						}*/
						$vl .= '("'.$this->users[$i]['login'].'","'.$this->users[$i]['city'].'","'.$this->info['id'].'","'.$this->users[$i]['id'].'","'.time().'","'.$this->users[$i]['team'].'","'.$this->users[$i]['level'].'","'.$this->users[$i]['align'].'","'.$this->users[$i]['clan'].'","'.$this->users[$i]['exp'].'","'.$this->users[$i]['bot'].'","'.$this->users[$i]['money'].'","'.$this->users[$i]['money3'].'"),';
						if($this->users[$i]['team'] == $this->info['team_win'] && $this->info['team_win'] > 0) {
							$vtvl .= '<b>'.$this->users[$i]['login'].'</b>, ';
						}
						$i++;
					}
					
					$this->info['players_c'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `login` NOT LIKE "%(�����%" AND `battle` = "'.$this->info['id'].'" LIMIT 1'));
					$this->info['players_c'] = $this->info['players_c'][0];
					
					mysql_query('UPDATE `battle` SET `players_c` = "'.$this->info['players_c'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					
					if($vtvl != '') {
						$vtvl = rtrim($vtvl,', ');
						$vtvl = str_replace('"','\\\\\"',$vtvl);
						$this->hodID++;
						$vLog = 'time1='.time();
						$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						$vtvl = '��� ��������, ������ �� '.$vtvl.'.';
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
					}else{
					$this->info['players_cc'] = mysql_fetch_array(mysql_query('SELECT COUNT(`u`.`id`) FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `s`.`hpNow` > 0 AND `u`.`battle` = "'.$this->info['id'].'" AND `s`.`team` != "'.$u->info['team'].'" LIMIT 1'));
					$this->info['players_cc'] = $this->info['players_cc'][0];
					$this->info['players_cc2'] = mysql_fetch_array(mysql_query('SELECT COUNT(`u`.`id`) FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `s`.`hpNow` >= 1 AND `u`.`battle` = "'.$this->info['id'].'" AND `s`.`team` != "'.$u->info['team'].'" LIMIT 1'));
					$this->info['players_cc2'] = $this->info['players_cc2'][0];
						$inf_test = ', users: '.$this->info['players_cc'].' and '.$this->info['players_cc2'].'';
						$this->hodID++;
						$vLog = 'time1='.time();
						$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						$vtvl = '��� ��������, �����. ( Information '.$inf_test.' )';
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
					}
					
					//$this->saveLogs( $this->info['id'] , 'all' );
						//$this->hodID++;
					
					if( $this->info['type'] == 99 ) {
						//$this->hodID++;
						$vLog = 'time1='.time();
						$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						$vtvl = '� ���������� ����� �������� �����������...';
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
						$i = 0; $vtvl = '';
						$tr_nm = array(
						 1 => '������',
						 2 => '�������',
						 3 => '�������'
						);
						while($i<count($this->users)) {
							if( $this->users[$i]['team'] != $this->info['team_win'] ) {
								$tr_pl = mysql_fetch_array(mysql_query('SELECT `id`,`v1` FROM `eff_users` WHERE `id_eff` = 4 AND `uid` = "'.$this->users[$i]['id'].'" AND `delete` = "0" ORDER BY `v1` DESC LIMIT 1'));
								if( !isset($tr_pl['id']) || $tr_pl['v1'] < 3 ) {
									$tr_tp = rand(1,3);
									if( isset($tr_pl['id']) ) {
										$tr_tp = rand(($tr_pl['v1']+1),3);
									}
									if( $this->users[$i]['sex'] == 1 ) {
										$vtvl = '<b>'.$this->users[$i]['login'].'</b> �������� �����������: <font color=red>'.$tr_nm[$tr_tp].' ������</font>.<br>'.$vtvl;
									}else{
										$vtvl = '<b>'.$this->users[$i]['login'].'</b> ������� �����������: <font color=red>'.$tr_nm[$tr_tp].' ������</font>.<br>'.$vtvl;
									}
									$this->addTravm($this->users[$i]['id'],$tr_tp,rand(3,5));
								}
							}
							$i++;
						}
						$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
					}
					
					if($vl!='')
					{
						$vl = rtrim($vl,',');
						mysql_query('INSERT INTO `battle_last` (`login`,`city`,`battle_id`,`uid`,`time`,`team`,`lvl`,`align`,`clan`,`exp`,`bot`,`money`,`money3`) VALUES '.$vl.'');
					}
					
					mysql_query('INSERT INTO `battle_end` (`battle_id`,`city`,`time`,`team_win`) VALUES ("'.$this->info['id'].'","'.$this->info['city'].'","'.$this->info['time_start'].'","'.$this->info['team_win'].'")');
				}
				
				
						$vLog = 'time1='.time();
						$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
						//$vtvl = '';
						//$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
						$i = 0; $vtvl = '';
						$tr_nm = array(
						 1 => '������',
						 2 => '�������',
						 3 => '�������'
						);
						while($i<count($this->users)) {
							if( $this->users[$i]['team'] != $this->info['team_win'] && $this->info['team_win'] > 0 ) {
								$tr_pl = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `v1` = "priem" AND `v2` = 292 AND `uid` = "'.$this->users[$i]['id'].'" AND `delete` = "0" LIMIT 1'));
								
								$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl2.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');

								if( isset($tr_pl['id']) ) {
									if( rand(0,100) < $tr_pl['data'] ) {
										$tr_tp = rand(1,3);
										if( isset($tr_pl['id']) ) {
											$tr_tp = rand(($tr_pl['v1']+1),3);
										}
										if( $this->users[$i]['sex'] == 1 ) {
											$vtvl = '<b>'.$this->users[$i]['login'].'</b> �������� ����������� (����������, �����: <b>'.$this->users[$this->uids[$tr_pl['user_use']]]['login'].'</b>): <font color=red>'.$tr_nm[$tr_tp].' ������</font>.<br>'.$vtvl;
										}else{
											$vtvl = '<b>'.$this->users[$i]['login'].'</b> ������� ����������� (����������, �����: <b>'.$this->users[$this->uids[$tr_pl['user_use']]]['login'].'</b>): <font color=red>'.$tr_nm[$tr_tp].' ������</font>.<br>'.$vtvl;
										}
										$this->addTravm($this->users[$i]['id'],$tr_tp,rand(3,5));
									}
								}
							}
							$i++;
						}
						if( $vtvl != '' ) {
							if( $this->info['type'] != 99 ) {
								$vtvl2 = '� ���������� ����� �������� �����������...';
								$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl2.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
							}
							$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$vtvl.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
						}
								
				//������ ��
				if( $this->info['inTurnir'] > 0 && $this->info['dungeon'] != 15 && $u->info['room'] != 413 ) {
					$bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `id` = "'.$this->info['inTurnir'].'" LIMIT 1'));
					$i = 0; $j = 0;
					while($i<count($this->users)) {
						if( $this->stats[$i]['hpNow'] < 1 && $this->users[$i]['clone'] == 0 && $this->stats[$i]['clone'] == 0 ) {
							//������� �� ��
							//echo '['.$this->users[$i]['login'].']';
							//��������� � ��� ��
							if( $this->users[$i]['sex'] == 0 ) {
								$text .= '{u1} �������� � �������� �� �������';
							}else{
								$text .= '{u1} ��������� � �������� �� �������';
							}
							//
							//���������� �������� � ���������
							$spik = mysql_query('SELECT `id`,`item_id` FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `delete` ="0"');
							while( $plik = mysql_fetch_array($spik) ) {
								/*
								��-1
								mysql_query('INSERT INTO `bs_items` (`x`,`y`,`bid`,`count`,`item_id`) VALUES (
									"'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'","'.$bs['id'].'","'.$bs['count'].'","'.$plik['item_id'].'"
								)');
								*/
								mysql_query('INSERT INTO `dungeon_items` (`dn`,`item_id`,`time`,`x`,`y`) VALUES (
									"'.$this->users[$i]['dnow'].'","'.$plik['item_id'].'","'.(time()-600).'","'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'"
								)');
							}
							unset($spik,$plik);
							//
							$usrreal = '';
							$usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `login` = "'.$this->users[$i]['login'].'" AND `inUser` = "'.$this->users[$i]['id'].'" LIMIT 1'));
							if( !isset($usr_real['id']) ) {
								$usr_real = $this->users[$i];
							}
							if( isset($usr_real['id'])) {
								$usrreal = '';
								if( $usr_real['align'] > 0 ) {
									$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >';
								}
								if( $usr_real['clan'] > 0 ) {
									$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >';
								}
								$usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
							}else{
								$mereal = '<i>���������</i>[??]';
							}
							$text = str_replace('{u1}',$usrreal,$text);
							mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
								"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
								"'.round($bs['money']*0.85,2).'","'.$i.'"
							)');
							//
							//�������� �����
							mysql_query('DELETE FROM `users` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							mysql_query('DELETE FROM `stats` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$this->users[$i]['id'].'"');
							//���������� ���������
							mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `login` = "'.$this->users[$i]['login'].'" OR `inUser` = "'.$this->users[$i]['id'].'" LIMIT 1');
							//��������� ������
							mysql_query('UPDATE `bs_zv` SET `off` = "'.time().'" WHERE `inBot` = "'.$this->users[$i]['id'].'" AND `off` = "0" LIMIT 1');
							unset($text,$usrreal,$usr_real);
							if( $this->users[$i]['pass'] != 'bstowerbot' ) {
								$bs['users']--;
								$bs['users_finish']++;
							}else{
								$bs['arhiv']--;
							}
							$j++;
						}
						$i++;
					}
					if( $j > 0 ) {
						mysql_query('UPDATE `bs_turnirs` SET `arhiv` = "'.$bs['arhiv'].'",`users` = "'.$bs['users'].'",`users_finish` = "'.$bs['users_finish'].'" WHERE `id` = "'.$bs['id'].'" LIMIT 1');
					}
					unset($bs,$j);
				}
				
				//������� �� �������
				if( $this->info['type'] == 500 && isset($tststrt['id']) ) {
					
					//�������� ������� �������� � �����
					
					$i = 0;
					while($i<count($this->users)) {
						if( $this->users[$i]['no_ip'] == 'trupojor' ) {
							$mon = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_monsters` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 1'));
							if( isset($mon['id']) ) {
								if( $this->info['team_win'] == 0 ) {
									//�����
									mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$i]['hpAll'].'",`mpNow` = "'.$this->stats[$i]['mpAll'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>��������!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$this->users[$i]['login'].'</b> ['.$this->users[$i]['level'].']<a target=_blank href=info/'.$this->users[$i]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>',$mon['nich_text'])).' ","'.$this->users[$i]['city'].'","","6","1","'.time().'")');
								}elseif( $this->info['team_win'] != $this->users[$i]['team'] ) {
									//��������
									$j = 0; $usrwin = '';
									while($j < count($this->users)) {
										if( $this->users[$j]['no_ip'] != 'trupojor' && $this->users[$j]['bot'] == 0 ) {
											//������ ��������
											//addGlobalItems($uid,$itm,$eff,$ico,$exp,$cr,$ecr)
																		
											$this->addGlobalItems($this->users[$i]['id'],$this->users[$j]['id'],$mon['win_itm'],$mon['win_eff'],$mon['win_ico'],$mon['win_exp'],$mon['win_money1'],$mon['win_money2']);
											if( $this->stats[$j]['hpNow'] > 0 ) {
												$usrwin .= ', ';
												if( $this->users[$j]['align'] > 0 ) {
													$usrwin .= '<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$this->users[$j]['align'].'.gif >';
												}
												if( $this->users[$j]['clan'] > 0 ) {
													$usrwin .= '<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$this->users[$j]['clan'].'.gif >';
												}
												$usrwin .= '<b>'.$this->users[$j]['login'].'</b> ['.$this->users[$j]['level'].']<a target=_blank href=info/'.$this->users[$j]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
											}
										}
										$j++;
									}
									if( $usrwin != '' ) {
										$usrwin = ltrim($usrwin,', ');
									}else{
										$usrwin = '<i>������� ������</i>';
									}
									mysql_query('UPDATE `users` SET `room` = "303" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('UPDATE `stats` SET `res_x` = "'.(time() + 60*$mon['time_restart']).'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>��������!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$this->users[$i]['login'].'</b> ['.$this->users[$i]['level'].']<a target=_blank href=info/'.$this->users[$i]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>', str_replace('{u}',$usrwin,$mon['win_text'])  )).' ","'.$this->users[$i]['city'].'","","6","1","'.time().'")');
									unset($usrwin);
								}else{
									//���������
									if( $mon['win_back'] == 1 ) {
										mysql_query('UPDATE `users` SET `room` = "303" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									}
									mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$i]['hpAll'].'",`mpNow` = "'.$this->stats[$i]['mpAll'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=red>��������!</font> '.mysql_real_escape_string(str_replace('{b}','<b>'.$this->users[$i]['login'].'</b> ['.$this->users[$i]['level'].']<a target=_blank href=info/'.$this->users[$i]['id'].' ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>',$mon['lose_text'])).' ","'.$this->users[$i]['city'].'","","6","1","'.time().'")');
								}
							}
						}
						$i++;
					}
				}
			}
			
			// ���������� ����� �� ���
			$i = 0; $botsi = 0;
			while($i<count($this->users))
			{
				if($this->users[$i]['bot']>0) {
					$this->users[$i]['battle'] = 0;
					mysql_query('UPDATE `users` SET `battle` = "0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `zv` = "0",`team` = "0",`exp` = `exp` + `battle_exp`,`battle_exp` = "0",`timeGo` = "'.time().'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 100');
				}elseif($this->users[$i]['bot']==2) {
					$this->users[$i]['battle'] = 0;
					mysql_query('UPDATE `users` SET `battle` = "0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `zv` = "0",`team` = "0",`exp` = `exp` + `battle_exp`,`battle_exp` = "0",`timeGo` = "'.time().'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 100');
				}elseif($this->users[$i]['bot']==1){
					$botsi++;
					mysql_query('DELETE FROM `users` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `stats` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 100');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'" LIMIT 100');
				}
				if($this->users[$i]['clone']>0 && $this->users[$i]['bot']>0 && isset($this->users[$this->uids[$this->users[$i]['clone']]]['id']) && $this->users[$this->uids[$this->users[$i]['clone']]]['team']!=$this->users[$i]['team']){
					//��������� ��� ���� ��������
					/*
						if($this->users[$this->uids[$this->users[$i]['clone']]]['team']==$this->info['team_win']){
							$u->addAction(time(),'win_bot_clone','',$this->users[$i]['clone']);
						}elseif($this->info['team_win']==0){
							$u->addAction(time(),'nich_bot_clone','',$this->users[$i]['clone']);
						}else{
							$u->addAction(time(),'lose_bot_clone','',$this->users[$i]['clone']);
						}
					*/
				}elseif($this->users[$i]['bot']>0 && $this->users[$i]['bot_id']>0){
					//��������� ��� ���� ��������
					$j = 0;
					while($j<count($this->users)){
						if($this->users[$j]['bot']==0 && $this->users[$j]['team']!=$this->users[$i]['team']){
							if( $this->testBotQuest($this->users[$i]['id'],$this->users[$i]['bot_id']) == true ) {
								if($this->users[$j]['team']==$this->info['team_win']){
									$u->addAction(time(),'win_bot_'.$this->users[$i]['bot_id'],'',$this->users[$j]['id']);
									//
									mysql_query('UPDATE `dialog_act` SET `now` = `now` + 1 WHERE `uid` = "'.$this->users[$j]['id'].'" AND 
									( `btl_bot` LIKE "'.$this->users[$i]['bot_id'].'!%" OR `btl_bot` LIKE "%!'.$this->users[$i]['bot_id'].'!%" ) 
									AND `val` != 1
									LIMIT 1');
									//
								}elseif($this->info['team_win']==0){
									//$u->addAction(time(),'nich_bot_'.$this->users[$i]['bot_id'],'',$this->users[$j]['id']);
								}else{
									//$u->addAction(time(),'lose_bot_'.$this->users[$i]['bot_id'],'',$this->users[$j]['id']);
								}
							}
						}
						$j++;
					}
				}
				if( $this->users[$i]['bot'] == 1 ) {
					//�������� �����
					mysql_query('DELETE FROM `users` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `stats` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$this->users[$i]['id'].'"');
					mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'"');
					mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'"');
					mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$this->users[$i]['id'].'"');
				}
				$i++;
			}
			$botss = array();
			if(true==true){				
				if($nl!=10){				
					//�� ���� ������ ��������
					$jbx = 0;
					$jbx2 = 0;	
					if($this->info['dungeon']>0){
						if($this->info['team_win']==$u->info['team'] && $this->info['dungeon'] == 102){
							$j1 = mysql_fetch_array(mysql_query('SELECT * FROM `laba_obj` WHERE `type` = 2 AND `lib` = "'.$this->info['dn_id'].'" AND `x` = "'.$this->info['x'].'" AND `y` = "'.$this->info['y'].'" LIMIT 1'));
							if( isset($j1['id']) ) {
								mysql_query('DELETE FROM `laba_obj` WHERE `id` = "'.$j1['id'].'" LIMIT 1');
								//�������� ������
								mysql_query('INSERT INTO `laba_obj` (`use`,`lib`,`time`,`type`,`x`,`y`,`vars`) VALUES (
									"0","'.$j1['lib'].'","'.time().'","6","'.$j1['x'].'","'.$j1['y'].'","'.(0+$botsi).'"
								)');
							}
						} elseif($this->info['team_win']==$u->info['team']) {
							//�������� ����, ���������� �������� �� �����	
			
							$j1 = mysql_query('SELECT * FROM `dungeon_bots` WHERE `dn` = "'.$this->info['dn_id'].'" AND `for_dn` = "0" AND `x` = "'.$this->info['x'].'" AND `delete` = "0" AND `y`= "'.$this->info['y'].'" LIMIT 100');
							while($tbot = mysql_fetch_array($j1)) {
								$jbx += $tbot['colvo'];
								$j2 = 0;
								while( $j2 < $tbot['colvo'] ) {
									if( isset($tbot['id2']) ) {
										$tbot2 = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$tbot['id_bot'].'" LIMIT 1'));
										if($tbot2['align'] == 9) {
											$jbx2 += 1;
										}
										$itms = explode('|',$tbot2['p_items']);
										$tii = 0;
										while($tii<count($itms)){
											$itmz = explode('=',$itms[$tii]);
											if($itmz[0]>0) {
												if( isset($itmz[2]) && $itmz[2] != '' ) { // $itmz[2] == quest888 
													$questDrop = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `vars` LIKE "%'.$itmz[2].'%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
												}
												if( isset($questDrop['id']) ) { // ���� ����� ����, ������� ����� ���� �������
												} elseif( isset($itmz[2]) && $itmz[2] != '' ) $itmz[1]=0; // ���� ������� ���������, � ������ � ������ ���, �� ������� ������� � ������������ 0
												unset($questDrop);
																								
												//��������� ���� ������� � ���� � � �
												if( $itmz[1] * 100000 >= rand(1,10000000) ) {
													$tou = 0; //������ ����� �������������
													/* �������� ���������� ����� �� ������� */
													$itmnm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$itmz[0].'" LIMIT 1'));
													$itmnm = $itmnm['name'];
													
													$rtxt = '� <b>'.$tbot2['login'].'</b> ��� ������� &quot;'.$itmnm.'&quot; � ��� ������ ����� ������� ���';
													mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$this->info['dn_id']."','".$this->users[0]['city']."','".$this->users[0]['room']."','','','".$rtxt."','".time()."','6','0','1','1')");
													
													$ins = mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`) VALUES (
													"'.$this->info['dn_id'].'",
													"'.$tou.'",
													"'.$itmz[0].'",
													"'.time().'",
													"'.$this->info['x'].'",
													"'.$this->info['y'].'")');
												}
											}
											$tii++;	
										}
									}
									$j2++;
								}
								//
								//����� 1-15 ��������, 
								if( date('m') == 9 && date('d') < 15 ) {
									if($this->get_chanse(99) == true || isset($this->info['dungeon_1sep'])) {
										//�� ������
									}elseif($this->info['dungeon'] == 1 || $this->info['dungeon'] == 12 || $this->info['dungeon'] == 101) {
										//
										$this->info['dungeon_1sep'] = true;
										// 
										$tou = 0; //������ ����� �������������
										/* �������� ���������� ����� �� ������� */
										$itmz = array(
											rand(4745,4751) , 100
										);
										//
										$itmnm = mysql_fetch_array(mysql_query('SELECT `name` FROM `items_main` WHERE `id` = "'.$itmz[0].'" LIMIT 1'));
										$itmnm = $itmnm['name'];
														
										$rtxt = '� <b>'.$tbot2['login'].'</b> ��� ������� &quot;'.$itmnm.'&quot; � ��� ������ ����� ������� ���';
										mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$this->info['dn_id']."','".$this->users[0]['city']."','".$this->users[0]['room']."','','','".$rtxt."','".time()."','6','0','1','1')");
														
										$ins = mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`) VALUES (
										"'.$this->info['dn_id'].'",
										"'.$tou.'",
										"'.$itmz[0].'",
										"'.time().'",
										"'.$this->info['x'].'",
										"'.$this->info['y'].'")');
									}
								}
								//
							}
							
							//������� ������ �������
							//
							$j51 = 0;
							while($j51 < count($this->users)){
								if($this->users[$j51]['bot']==0 && $this->info['team_win']==$this->users[$j51]['team']){
									mysql_query('UPDATE `dailybonus` SET `dun_kill` = `dun_kill` + "'.$jbx.'" , `dun_kill_mar` = `dun_kill_mar` + "'.$jbx2.'" WHERE `date_finish` != "'.date('d.m.Y').'" AND 
									`uid` = "'.$this->users[$j51]['id'].'" LIMIT 1');
								}
								$j51++;
							}
							
							# mysql_query('UPDATE `dungeon_bots` SET `delete` = "'.time().'" WHERE `dn` = "'.$this->info['dn_id'].'" AND `for_dn` = "0" AND `x` = "'.$this->info['x'].'" AND `y`= "'.$this->info['y'].'" AND `delete` = "0" ');
							mysql_query('UPDATE `dungeon_bots` SET `delete` = "'.time().'" AND `inBattle` = "'.$this->info['id'].'" WHERE `dn` = "'.$this->info['dn_id'].'" AND `for_dn` = "0" AND `delete` = "0" '); 
							
						}else{
							//���������� ���� ������� � ������ RESTART
							$dnr = 1;
							if( $this->info['dungeon'] != 102 ) {
								mysql_query('UPDATE `dungeon_bots` SET `inBattle` = "0" WHERE `dn` = "'.$this->info['dn_id'].'" AND `for_dn` = "0" AND `x` = "'.$this->info['x'].'" AND `y`= "'.$this->info['y'].'"');
							}
						}
					}
				}
				
				$gm = array(); $gms = array();
				$bm = array(); $bms = array();
				
				//��������� ������
					$i = 0;
					while($i < count($this->users)){
						if($this->users[$i]['bot']==0 && $this->users[$i]['id'] == $u->info['id']){
							$q->bfinuser($this->users[$i],$this->info['id'],$this->info['team_win']);
						}
						$i++;
					}
				//��������� ������				
				
				//��������� ��������
				$i = $this->uids[$u->info['id']];
				
				if( $this->info['team_win'] >= 0 ) {
					//�������� ����� � ������ +100% �����
					//if(round(date('m')) >= 5 && round(date('m')) < 9) {
						//if(round(date('w')) == 0 || round(date('w')) == 6) {
						//	$this->stats[$i]['exp'] += 100;
						//}
					//}
					
					/*if( date('d.m') == '15.05' ) {
						//������ 40 ��� (54, �� ���������� 40)
						//$this->stats[$i]['exp'] += 100;
					}elseif( date('d.m') == '13.06' ) {
						//���� �����
						//$this->stats[$i]['exp'] += 100;
					}*/
					
					/*if( $this->info['razdel'] == 5 ) {
						if( $c['w'] == 0 || $c['w'] == 6 ) {
							$this->expCoef += 50;
						}
					}*/
					
					$this->stats[$i]['exp'] += $this->expCoef;
																	
					$this->stats[$i]['exp'] += $this->aBexp + $c['exp'];
					
					if( $this->stats[$i]['os4'] > 0 ) {
						$this->stats[$i]['exp'] += $this->stats[$i]['os4'];
					}
					
					$this->users[$i]['bonus_db'] = mysql_fetch_array(mysql_query('SELECT * FROM `users_bonus` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1'));
					if(!isset($this->users[$i]['bonus_db']['id'])) {
						mysql_query('INSERT INTO `users_bonus` (`id`) VALUES ("'.$this->users[$i]['id'].'")');
						$this->users[$i]['bonus_db'] = mysql_fetch_array(mysql_query('SELECT * FROM `users_bonus` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1'));
					}
					//
					if($this->info['dungeon'] == 0 && $this->info['izlom'] == 0) {
						$this->stats[$i]['exp'] += $this->users[$i]['bonus_db']['haot'];
					}
					
					/*if($this->stats[$i]['silver']>0) {
						$this->stats[$i]['exp'] += 5*$this->stats[$i]['silver'];
						if($this->stats[$i]['bonusexp'] > 1) { // ��� ������� ����� (�������� ��������)
							$this->stats[$i]['exp'] += 1000*$this->stats[$i]['bonusexp'];
						}
						//if($this->stats[$i]['speeden']>20) { // ��� �������������� ������� (�������� ��������)
							//$this->stats[$i]['enNow'] += $this->stats[$i]['speeden'];
							
						//$upd2 = mysql_query('UPDATE `stats` SET `enNow` = "'.$this->users[$i]['enNow'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
						//}
					}*/
					
					$act01 = 0;
					$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']+($this->users[$i]['battle_exp']/100*(1+$this->info['addExp']+$this->stats[$i]['exp'])));
					
					if( $this->info['type'] == 564 ) {
						//��� � ������
						$this->users[$i]['battle_exp'] = 0;
					}
					
					if( $this->info['dungeon'] == 104 ) {
						$this->users[$i]['battle_exp'] = ($u->info['level']*2)*(count($this->uids)-1);
					}elseif($this->info['dungeon'] > 0 && $this->users[$i]['dnow'] != 0 && $this->info['dungeon'] != 1  && $this->users[$i]['team']==$this->info['team_win']) {
						$dun_limitForLevel = array( 4 => 750, 5 => 1500, 6 => 3500, 7 => 8000, 8 => 25000, 9 => 50000, 10 => 75000, 11 => 125000, 12 => 250000, 13 => 500000, 14 => 750000 );
						// �������� ��� ������� ������. 
						
						if( $this->users[$i]['battle_exp'] > 0 ) {
							$dun_exp = array(); // ������� ����� ����� ������ � �����������.
							$rep = mysql_fetch_array(mysql_query('SELECT `dungeonexp`,`id` FROM `rep` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1'));
							$rep = explode(',',$rep['dungeonexp']);
							foreach ($rep as $key=>$val) { 
								$val = explode('=',$val); // ������� ����� ����� � �������� 
								if( isset($val[0]) && isset($val[1]) && $val[0] !='' && $val[1] != 0 ) $dun_exp[(int)$val[0]] = (int)$val[1];
							}
							unset($rep);
						}
						
						if( !isset($dun_exp[$this->info['dungeon']]) ) $dun_exp[$this->info['dungeon']] = 0;
						
						if( !isset($dun_limitForLevel[(int)$this->users[$i]['level']]) ){ // ���� ����� �� �����, ���� �� ����.
							$this->users[$i]['battle_exp'] = 0;
						} elseif(
							isset($dun_exp[$this->info['dungeon']]) &&
							$dun_exp[$this->info['dungeon']] >= $dun_limitForLevel[(int)$this->users[$i]['level']]
						) { // ���� ����� ��� ���������, ���� �� ����.
							$this->users[$i]['battle_exp'] = 0;
						} elseif(
							isset($dun_exp[$this->info['dungeon']]) &&
							$dun_limitForLevel[(int)$this->users[$i]['level']] > $dun_exp[$this->info['dungeon']]
						) { // ���� ������� ��������� �� �������� ������.
							if( ( $dun_exp[$this->info['dungeon']] + $this->users[$i]['battle_exp'] ) > $dun_limitForLevel[(int)$this->users[$i]['level']] ) {
								// ���� ����� ������� ����������, ��� ���������� ������.
								$this->users[$i]['battle_exp'] = abs($this->users[$i]['battle_exp'] - abs( $dun_limitForLevel[(int)$this->users[$i]['level']] - ( $this->users[$i]['battle_exp'] + $dun_exp[$this->info['dungeon']] ) ));
								$dun_exp[$this->info['dungeon']] += $this->users[$i]['battle_exp']; 
							} elseif( $dun_limitForLevel[(int)$this->users[$i]['level']] > ( $dun_exp[$this->info['dungeon']] + $this->users[$i]['battle_exp'] ) ) {
								// ���� ����� ������������, ��� ���������� ������.
								$this->users[$i]['battle_exp'] = $this->users[$i]['battle_exp'];
								$dun_exp[$this->info['dungeon']] += $this->users[$i]['battle_exp']; 
							} else {
								$this->users[$i]['battle_exp'] = 0;
							}
						} else { // � ����� ���������� ��������.
							$this->users[$i]['battle_exp'] = 0;
						}
						
						
						if( $this->users[$i]['battle_exp'] > 0 && isset($dun_exp[$this->info['dungeon']]) && $dun_exp[$this->info['dungeon']] > 0 ){
							$dunexp = array();
							foreach ($dun_exp as $key=>$val) {
								$dunexp[$key] = $key.'='.$val; // ������� ����� ����� � ��������
							}
							$dun_exp = implode(",",$dunexp);
							mysql_query('UPDATE `rep` SET `dungeonexp` = "'.$dun_exp.'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							unset($dunexp,$dun_exp);
						}
						unset($dun_limitForLevel);
					}
					
					$sinf = '';
					
					if( $c['exp_mega'] == true ) {
						$this->users[$i]['battle_exp'] += floor($this->users[$i]['battle_exp']/100*$c['exp_mega_val'][$this->users[$i]['level']]);
					}
					
					/*if( $this->users[$i]['level'] < 8 ) {
						$this->users[$i]['battle_exp'] = $this->users[$i]['battle_exp']*10;
					}elseif( $this->users[$i]['level'] < 9 ) {
						//$this->users[$i]['battle_exp'] = $this->users[$i]['battle_exp']*2;
					}*/
					//
					if($this->info['team_win']==0 && $this->info['type'] != 564){
						//�����
						if( $this->info['razdel'] == 5 ) {
							mysql_query('INSERT INTO `users_reting` (`uid`,`time`,`val`) VALUES (
							"'.$this->users[$i]['id'].'","'.time().'","10")');
						}
						//$sinf .= ' ����� ';
							if($this->users[$i]['level'] <= 1){
								$this->users[$i]['battle_exp'] = floor($this->users[$i]['battle_exp']*(0.5/100));
							}else{
								$this->users[$i]['battle_exp'] = 0;
								/*if( $this->info['dungeon'] > 0 ) {
									$this->users[$i]['battle_exp'] = -(round($this->users[$i]['exp']*0.001));
								}else{
									$this->users[$i]['battle_exp'] = -(round($this->users[$i]['exp']*0.005));
								}*/
							}
							
							if( $this->info['razdel'] == 5 ) {
								$this->users[$i]['bonus_db']['haot_daily']++;
								if( $this->users[$i]['bonus_db']['haot'] > 0 ) {
									$this->users[$i]['bonus_db']['haot']--;
								}
							}
							if( $this->info['razdel'] > 0 ) {
								$this->users[$i]['bonus_db']['fight_daily']++;
							}
							
							$this->users[$i]['nich'] += 1;
							//
							mysql_query('UPDATE `users` SET `swin` = 0, `slose` = 0 WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							//
					}elseif($this->users[$i]['team']==$this->info['team_win'] && $this->info['type'] != 564){
						//�������
						if( $this->info['razdel'] == 5 ) {
							mysql_query('INSERT INTO `users_reting` (`uid`,`time`,`val`) VALUES (
							"'.$this->users[$i]['id'].'","'.time().'","30")');
						}
						//$sinf .= ' �������� ';
							$gm[$i] = $this->info['money'];
							
							$gms[$i] = $this->info['money3'];
							
							$this->users[$i]['win'] += 1;
							$act01 = 1;
							//
							if( $this->info['razdel'] == 5 ) {
								$this->users[$i]['bonus_db']['haot_daily']++;
								$this->users[$i]['bonus_db']['winhaot_daily']++;
								if( $this->users[$i]['bonus_db']['haot'] < 200 ) {
									$this->users[$i]['bonus_db']['haot']++;
								}
							}
							if( $this->info['razdel'] > 0 ) {
								$this->users[$i]['bonus_db']['winfight_daily']++;
							}
							mysql_query('UPDATE `users` SET `swin` = `swin` + 1, `slose` = 0 WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							//
					}elseif( $this->info['type'] != 564 ) {
						//��������
						if( $this->info['razdel'] == 5 ) {
							mysql_query('INSERT INTO `users_reting` (`uid`,`time`,`val`) VALUES (
							"'.$this->users[$i]['id'].'","'.time().'","10")');
						}
						//$sinf .= ' ��������� ';
							if( $this->info['dungeon'] > 0 ) {
								//$this->users[$i]['battle_exp'] = -(round($this->users[$i]['exp']*(0.1/100)));
								$this->users[$i]['battle_exp'] = 0;
							}else{
								//$this->users[$i]['battle_exp'] = -(round($this->users[$i]['exp']*(1.5/100)));
								$this->users[$i]['battle_exp'] = 0;
							}
							$bm[$i] = $this->info['money'];
							
							$bms[$i] = $this->info['money3'];
							
							if( $this->info['razdel'] == 5 ) {
								$this->users[$i]['bonus_db']['haot_daily']++;
								if( $this->users[$i]['bonus_db']['haot'] > 0 ) {
									$this->users[$i]['bonus_db']['haot']--;
								}
							}
							if( $this->info['razdel'] > 0 ) {
								$this->users[$i]['bonus_db']['fight_daily']++;
							}
							
							$this->users[$i]['lose'] += 1;
							//��������� ������ ����������
							if($this->users[$i]['level']>=4 && $this->info['dungeon'] == 0 && $this->stats[$i]['silver'] < 2){
								$noOsl = 0;
								$nn = 0;
								while($nn<count($this->stats[$i]['effects'])){
									if($this->stats[$i]['effects'][$nn]['id_eff']==5){
										$noOsl = 1;
									}
									$nn++;
								}
								if($noOsl==0){
									if($this->users[$i]['id'] != 3874647) {
									  $magic->oslablenie($this->users[$i]['id']);
									}
									
								}
							}
							//
							mysql_query('UPDATE `users` SET `swin` = 0, `slose` = `slose` + 1 WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							//
							$act01 = 2;
					}
					//
					mysql_query('UPDATE `users_bonus` SET `haot` = "'.$this->users[$i]['bonus_db']['haot'].'" WHERE `id` = "'.$this->users[$i]['bonus_db']['id'].'" LIMIT 1');
					if(isset($this->users[$i]['bonus_db']['winhaot_daily'])) {
						mysql_query('UPDATE `dailybonus` SET `haot` = `haot` + 1 , `winhaot` = `winhaot` + 1 WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$this->users[$i]['id'].'" LIMIT 1');
					}elseif(isset($this->users[$i]['bonus_db']['haot_daily'])) {
						mysql_query('UPDATE `dailybonus` SET `haot` = `haot` + 1 WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$this->users[$i]['id'].'" LIMIT 1');
					}
					if(isset($this->users[$i]['bonus_db']['winfight_daily'])) {
						mysql_query('UPDATE `dailybonus` SET `fight` = `fight` + 1 , `winfight` = `winfight` + 1 WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$this->users[$i]['id'].'" LIMIT 1');
					}elseif(isset($this->users[$i]['bonus_db']['fight_daily'])) {
						mysql_query('UPDATE `dailybonus` SET `fight` = `fight` + 1 WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$this->users[$i]['id'].'" LIMIT 1');
					}
					//		
					if( $this->users[$i]['level'] < 4 ) {
						//$this->users[$i]['battle_exp'] = $this->users[$i]['battle_exp']*3;
					}
					//$sinf .= ' '.$this->info['id'].'-'.$this->info['team_win'].'-'.$relbf.'-'.$this->users[$i]['team'].'-'.$tl	.' ';
					//
					//������������ ���-�� ���������� ���� � ��� ������� ������� (��� ���.)
					if( $this->info['money3'] > 0 && isset($gms[$i])  && $this->info['type'] != 564 ) {
						$mn = array(
							'l'  => 0, //������� ����������� �������
							'w'  => 0, //������� ���������� �������
							'm' => 0   //����� �������� (�����)
						);
						if( $act01 == 1 ) {
							$mn['l'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `battle_users` WHERE `battle` = "'.$this->info['id'].'" AND `team` != "'.$this->users[$i]['team'].'" LIMIT 1'));
							$mn['l'] = $mn['l'][0];
							$mn['w'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `battle_users` WHERE `battle` = "'.$this->info['id'].'" AND `team` = "'.$this->users[$i]['team'].'" LIMIT 1'));
							$mn['w'] = $mn['w'][0];
							$mn['m'] = round(($mn['l']*$this->info['money3'])/100*87,2);
							$gms[$i] = round(($mn['m']/$mn['w']),2);
						}
					}
					//
					//������� ������ � ��
					//������� ���������
						$lom = 0;
						$lomx = round( $this->stats[$i]['hpNow']/$this->stats[$i]['hpAll'] , 2);
						if( $lomx > 1 ) {
							$lomx = 0;
						}elseif( $lomx < 0 ) {
							$lomx = 1;
						}
						if($act01==1){
							//������
							//if( $this->stats[$i]['hpNow'] < 1 ) {
								$lom = 0.05;
							//}
							if( $this->users[$i]['dnow'] > 0 ) {
								$lom = 0.02;
							}
						}elseif($act01==2){
							//���������
							//if( $this->stats[$i]['hpNow'] < 1 ) {
								$lom = 0.55;
							//}
						}else{
							//�����
							//if( $this->stats[$i]['hpNow'] < 1 ) {
								$lom = 0.55;
							//}
						}
						$lom = round( $lom * $lomx , 2);
						//$lom = round($lom*2.75,2);
						//$nlom = array(0=>rand(0,18),1=>rand(0,18),2=>rand(0,18),3=>rand(0,18));
						/*
						if(  $this->info['type'] == 564 ) {
							$lom = 0;
						}
						if( $this->stats[$i]['silver'] >= 4 ) {
							$lom = 0;
						}
						*/
						mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW`+'.$lom.' WHERE `inOdet` < "18" AND `inOdet` > "0" AND `uid` = "'.$this->users[$i]['id'].'" AND `inOdet`!="0"');
											
						$prc = '';						
						if($this->users[$i]['align'] == 2){
							if( $this->users[$i]['battle_exp'] > 0 ) {
								$this->users[$i]['battle_exp'] = floor($this->users[$i]['battle_exp']/2);
							}else{
								$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']*2);
							}
						}
						if($this->users[$i]['animal']>0){
							$ulan = $u->testAction('`uid` = "'.$this->users[$i]['id'].'" AND `vars` = "animal_use'.$this->info['id'].'" LIMIT 1',1);
							if(isset($ulan['id']) && $this->users[$i]['team']==$this->info['team_win'] && $this->users[$i]['level'] > $ulan['vals']){
								$a004 = mysql_fetch_array(mysql_query('SELECT `max_exp` FROM `users_animal` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `id` = "'.$this->users[$i]['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
								//33% �� ����� ��������� �����, �� �� ����� ���������
								$aexp = (round($this->users[$i]['battle_exp']/100*33));
								if($aexp > $a004['max_exp'])
								{
									$aexp = $a004['max_exp'];
								}
								unset($ulan);
								$upd = mysql_query('UPDATE `users_animal` SET `exp` = `exp` + '.$aexp.' WHERE `id` = "'.$this->users[$i]['animal'].'" AND `level` < '.$this->users[$i]['level'].' LIMIT 1');
								if($upd) {
									$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']/100*67); 
									$this->info['addExp'] -= 33.333;
								}
							}
						}
						//
						if($this->users[$i]['align']==2 || $this->users[$i]['haos'] > time()) {
							$this->stats[$i]['exp'] = -($this->info['addExp']+50);
						}
						if($this->info['addExp']+$this->stats[$i]['exp']!=0)
						{
							$prc = ' ('.(100+$this->info['addExp']+$this->stats[$i]['exp']).'%)';
						}
						if($this->info['money']>0) {
							if(isset($gm[$i])) {
								$prc .= ' �� �������� <b>'.$gm[$i].' ��.</b> �� ���� ���.';
								$u->addDelo(4,$this->users[$i]['id'],'&quot;<font color="olive">System.battle</font>&quot;: �������� ������� <b>'.$gm[$i].' ��.</b> (� ��� �'.$this->info['id'].').',time(),$this->info['city'],'System.battle',0,0);
								$this->users[$i]['money'] += $gm[$i];
							} elseif(isset($bm[$i])) {
								$prc .= ' �� ��������� <b>'.$bm[$i].' ��.</b> �� ���� ���.';
								$u->addDelo(4,$this->users[$i]['id'],'&quot;<font color="olive">System.battle</font>&quot;: �������� <i>��������</i> <b>'.$gm[$i].' ��.</b> (� ��� �'.$this->info['id'].').',time(),$this->info['city'],'System.battle',0,0);
								$this->users[$i]['money'] -= $bm[$i];
							}
						}
						//
						if( $c['money_haot'] == true ) {
							if( $this->info['razdel'] == 5 ) {
								$bnsv = 0.50;
								if( $this->info['otmorozok_use'] > 0 ) {
									$bnsv += 1;
								}
								if( $c['w'] == 0 || $c['w'] == 6 ) {
									$bnsv += 0.50;
								}
								$admnb = round($bnsv+(($this->stats[$i]['prckr']/100)*$c['money_haot_proc']),2);
								if($act01!=1) {
									$admnb = round($admnb/10,2);
								}
								if( $admnb >= 0.01 ) {
									$adexp = array(
										0 => 5,
										1 => 5,
										2 => 5,
										3 => 10,
										4 => 15,
										5 => 25,
										6 => 35,
										7 => 50,
										8 => 100,
										9 => 150,
										10 => 250,
										11 => 350,
										12 => 500,
										13 => 750,
										14 => 1000,
										15 => 1250,
										16 => 1500,
										17 => 1750,
										18 => 2250,
										19 => 2500,
										20 => 2750,
										21 => 5000
									);
									$adexp = $adexp[$this->users[$i]['level']];	
									if($act01!=1) {
										$adexp = round($adexp/10);	
									}
									$uzrbtl = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `battle_users` WHERE `battle` = "'.$this->info['id'].'" AND `login` NOT LIKE "%(%)&" AND `align` != "" LIMIT 1'));
									$uzrbtl = $uzrbtl[0];
									if( $this->users[$i]['battle_exp'] < $adexp ) {
										//��� ������� �.�. �� ������� ������������ �����
										$prc .= ', ������� <b>0 ��.</b> �� ���� ���, ��������� ������ �����.';
									}elseif( $uzrbtl < 4 ) {
										//��� ������� �.�. �� ��������� 4 ������ � ����
										$prc .= ', ������� <b>0 ��.</b> �� ���� ���, ��������� ������ ����� � ����.';
									}else{
										$prc .= ', ������� <b>'.$admnb.' ��.</b> �� ���� ���.';
										$u->addDelo(4,$this->users[$i]['id'],'&quot;<font color="olive">System.battle</font>&quot;: �������� ������� <b>'.$admnb.' ��.</b> (� ��� �'.$this->info['id'].').',time(),$this->info['city'],'System.battle',0,0);
										$this->users[$i]['money'] += $admnb;
									}
									//
									$minpsh = 0;
									if($act01==1){
										//������
										$minpsh = 25;
									}elseif($act01==2){
										//���������
										$minpsh = 15;
									}else{
										//�����
										$minpsh = 15;
									}
									if( $c['w'] == 0 || $c['w'] == 6 ) {
										$minpsh += 10;
									}
									if( $minpsh > 0 ) {
										mysql_query('UPDATE `actions` SET `time` = `time` - "'.($minpsh*60).'" WHERE `vars` LIKE "psh0" AND `uid` = "'.$this->users[$i]['id'].'"');
										$prc .= ', �������� ��������� ����� ��������� �� '.$minpsh.' �����.';
									}
									//
								}
							}
						}
						//
						if($this->info['money3'] > 0) {
							if(isset($gms[$i])) {
								$prc .= ' �� �������� <b>'.$gms[$i].' $.</b> �� ���� ���.';
								$u->addDelo(4,$this->users[$i]['id'],'&quot;<font color="olive">System.battle</font>&quot;: �������� ������� <b>'.$gms[$i].' $.</b> (� ��� �'.$this->info['id'].').',time(),$this->info['city'],'System.battle',0,0);
								$this->users[$i]['money3'] += $gms[$i];
								mysql_query('UPDATE `users` SET `money3` = `money3` + "'.$gms[$i].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							} elseif(isset($bms[$i])) {
								$prc .= ' �� ��������� <b>'.$bms[$i].' $.</b> �� ���� ���.';
								$u->addDelo(4,$this->users[$i]['id'],'&quot;<font color="olive">System.battle</font>&quot;: �������� <i>��������</i> <b>'.$gms[$i].' $.</b> (� ��� �'.$this->info['id'].').',time(),$this->info['city'],'System.battle',0,0);
								$this->users[$i]['money3'] -= $bms[$i];
								mysql_query('UPDATE `users` SET `money3` = `money3` - "'.$bms[$i].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							}
						}
						if($this->info['kingfight']==1 && $this->info['type'] != 564 ) {
							//�������� ��������
							if($this->info['team_win'] == 0) {
								
							}elseif($this->users[$i]['team'] == $this->info['team_win']){
								$bnks = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$this->users[$i]['id'].'" ORDER BY `useNow` DESC LIMIT 1'));
								if(isset($bnks['id'])) {
									$bnks['msn'] = round($this->user[$i]['level']/50,2);
									$bnks['msn'] = 0.15;
									mysql_query('UPDATE `bank` SET `money2` = `money2` + "'.mysql_real_escape_string($bnks['msn']).'" WHERE `id` = "'.$bnks['id'].'" LIMIT 1');
									$prc .= ' <img src=http://img.xcombats.com/king.gif width=20 height=20> �� �������� ����: '.$bnks['msn'].' ���., ���� �'.$bnks['id'].'';
									
								}
								unset($bnks);
							}
						}
						
						/*
	7�� - 10800
	8�� - 36000
	9�� - 56000
	10�� - 86000
	if($this->users[$i]['battle_exp'] > (1+$this->users[$i]['level']*$this->users[$i]['level'])*4755) {
							$this->users[$i]['battle_exp'] = (1+$this->users[$i]['level']*$this->users[$i]['level'])*4755;
						}
						*/
						
						//$lime = array(8=>18000,9=>28000,10=>84000,11=>150000);
						$lime = array(8=>58000,9=>23000,10=>84000,11=>100);
						
						if($this->users[$i]['level'] < 8) {
							$lime = 5400;
						}else{
							$lime = $lime[$this->users[$i]['level']];
						}
						
						if( $this->stats[$i]['silver'] >= 5 ) {
							$lime += round($lime);
						}
						
						/*if( $this->stats[$i]['silver'] > 0 ) {
							$lime += floor($lime/100*(10*$this->stats[$i]['silver']));
						}*/
	
						/*if( $this->users[$i]['twink'] > 0 || $this->users[$i]['stopexp'] == 1 ) {
							if( $this->users[$i]['twink'] > 0 ) {
								if($lime < $this->users[$i]['battle_exp']) {
									$this->users[$i]['battle_exp'] = $lime;
								}
								//��������� ��������������
								if( $this->info['dungeon'] == 0 ) {
									if( $this->users[$i]['battle_exp'] >= 0 ) {
										$rzbvo = floor($this->users[$i]['battle_exp']/200);
										$prc .= ' �� �������� '.$rzbvo.' �������������� �� ���� ���';
										mysql_query('UPDATE `rep` SET `rep3` = `rep3` + "'.$rzbvo.'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									}
								}
							}
							$lime = 0;
						}*/
						
						if($lime < $this->users[$i]['battle_exp'] && $c['limitedexp'] == true) {
							$this->users[$i]['battle_exp'] = $lime;
						}
						//
						/*if( $this->users[$i]['battle_exp'] > 100 && $this->info[$i]['level'] == 6 ) {
							$this->users[$i]['battle_exp'] = 100;
						}*/
						//
						unset($lime);
						
						
						if(100+$this->info['addExp']+$this->stats[$i]['exp'] > 1000) {
							//$prc .= ' (������� �����)';
						}
						
						if($this->info['dungeon'] == 1 && $this->users[$i]['team']==$this->info['team_win']) {
							//����������� �����
							$rep = mysql_fetch_array(mysql_query('SELECT `dl1`,`id` FROM `rep` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1'));
							if($rep['dl'.$this->info['dungeon']] > 0) {
								$this->users[$i]['battle_exp'] += 3*count($this->users);
								if($rep['dl'.$this->info['dungeon']] > $this->users[$i]['battle_exp']) {
									$rep['dl'.$this->info['dungeon']] -= $this->users[$i]['battle_exp'];
								}else{
									$this->users[$i]['battle_exp'] = $rep['dl'.$this->info['dungeon']];
									$rep['dl'.$this->info['dungeon']] = 0;
								}
								mysql_query('UPDATE `rep` SET `dl'.$this->info['dungeon'].'` = "'.$rep['dl'.$this->info['dungeon']].'" WHERE `id` = "'.$rep['id'].'" LIMIT 1');
							}else{
								$this->users[$i]['battle_exp'] = 0;
							}
						}
						
						if($this->users[$i]['battle_exp'] < 1) {
						//	$this->users[$i]['battle_exp'] = 0;
						}
						
						if($this->users[$i]['battle_exp'] < 1 && $this->users[$i]['twink'] == 0) {
							if( $this->info['money'] == 0 && $this->info['money3'] == 0 && $this->info['kingfight'] == 0 && (!isset($admnb) || $admnb == 0)) {
								$prc = '';
							}
						}
						
						if($this->user[$i]['host_reg'] == 'real_bot_user') {
							$this->users[$i]['battle_exp'] = round($this->users[$i]['battle_exp']/3);
						}
						
						if( $sinf != '' ) {
							$sinf = ' ( '.$sinf.' )';
						}
						
						/*if( $this->users[$i]['level'] >= 8 ) {
							if( $this->users[$i]['battle_exp'] > 1000 ) {
								$this->users[$i]['battle_exp'] = 1000;
							}
						}*/
						
						if( $this->users[$i]['battle_exp'] < 0 ) {
							if( $this->users[$i]['level'] < 7 ) {
								$this->users[$i]['battle_exp'] = 0;
							}elseif( $this->users[$i]['exp'] > 30000 && $this->users[$i]['exp'] + $this->users[$i]['battle_exp'] < 30000 ) {
								$this->users[$i]['battle_exp'] = -($this->users[$i]['exp'] - 30000);
							}
						}
						
						if( $this->users[$i]['battle_exp'] < 0 ) {
							$this->users[$i]['battle_text'] = '��� ��������. ����� ���� �������� �����: <b>'.floor($this->users[$i]['battle_yron']).' HP</b>. ��������� �����: <font color=red><b>'.(0+$this->users[$i]['battle_exp']).'</b> (�� �� ������ ��������)</font>'.$prc.'.'.$sinf; //stats
						}else{
							mysql_query('UPDATE `dailybonus` SET `expopen` = `expopen` + "'.$this->users[$i]['battle_exp'].'" WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$this->users[$i]['id'].'" LIMIT 1');
							$this->users[$i]['battle_text'] = '��� ��������. ����� ���� �������� �����: <b>'.floor($this->users[$i]['battle_yron']).' HP</b>. �������� �����: <b>'.(0+$this->users[$i]['battle_exp']).'</b>'.$prc.'.'.$sinf; //stats
						}
											
						/*��������� ����� � ����� ��� */
						if($c['nolevel'] == true) {
							$rex95 = substr($this->users[$i]['exp'], -1);
							if( $this->users[$i]['money4'] < 1000 && $this->users[$i]['exp'] == 12499 ) {
								$rex95 = 6;
								$rex95 = 5;
							}
						}else{
							$rex95 = 5;
						}
						
						
						
						//echo '['.$rex95.']';
						if($c['zuby'] == true && $this->info['dungeon'] == 0 && ($this->info['clone'] == 0 || $this->users[$i]['level'] < 5) && $rex95 != 9 && $this->info['type'] != 564) {
							if( $this->users[$i]['align'] != 2 && $this->users[$i]['level'] >= 0 && $this->users[$i]['level'] < 8 && $this->users[$i]['battle_exp'] > 0 ) {
								$rzb = 0;

								if($this->get_chanse(50) == true) {
									$rzb += rand(1,3);
								}else{
									if( $this->users[$i]['battle_exp'] > 5 ) {
										$rzb += 1;
									}
								}
								
								$chzbs = 100;
								
								if( $this->stats[$i]['silver'] > 0 ) {
									$chzbs = 150;
								}
								
								if( $this->users[$i]['battle_exp'] > 9 ) {
									if( $this->users[$i]['level'] > 1 ) {
										//���������� ���
										if($this->get_chanse(25/100*$chzbs) == true) {
											$rzb += 10;
										}
									}elseif( $this->users[$i]['level'] == 1 ) {
										if($this->get_chanse(30/100*$chzbs) == true) {
											$rzb += 10;
										}
									}
									
									if( $this->users[$i]['level'] > 3 ) {
										if($this->get_chanse(5/100*$chzbs) == true) {
											$rzb += 100;
										}
									}
								}
								
								if($this->users[$i]['team']==$this->info['team_win']){
									//��������
									if( $this->users[$i]['level'] < 8 ) {
										$rzb += 1;
									}
								}elseif($this->users[$i]['team']==0){
									//�����
									if($this->get_chanse(25/100*$chzbs) == true && $this->users[$i]['battle_exp'] > 2) {
										$rzb = rand(0,1);
									}else{
										$rzb = 0;
									}
								}else{
									//���������
									if( $this->users[$i]['level'] >= 1 ) {
										$rzb = 0;
									}else{
										if( $this->users[$i]['battle_exp'] < 3 ) {
											$rzb = 0;
										}elseif( $this->user[$i]['lose'] > $this->user[$i]['win'] ) {
											if($this->get_chanse(5/100*$chzbs) == true) {
												$rzb = rand(0,1);
											}else{
												$rzb = 0;
											}
										}elseif($this->get_chanse(10/100*$chzbs) == true) {
											$rzb = rand(0,1);
										}
									}
									$rzb = 0;
								}
								
								if( $rex95 == 6 /*|| $this->users[$i]['exp'] == 12499 */ ) {
									if( $this->users[$i]['battle_exp'] > 10 ) {
										$rzb = rand(0,1);
									}else{
										$rzb = 0;
									}
								}
								
								if( $rzb > 0 && rand(0,1000) <= 500 ) {
									$rzb = rand(1,3);
								}
								if($rzb > 0) {
									if( $this->stats[$i]['silver'] >= 5 ) {
										$rzb = $rzb*2;
									}
									mysql_query('UPDATE `users` SET `money4` = `money4` + "'.$rzb.'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
									$this->users[$i]['battle_text'] .= ' �� �������� <small>'.$u->zuby($rzb,1).'</small> �� ���� ���.';
								}
							}
						}
						
						if( $this->info['priz'] > 0 ) {
							//�������� ����
							/*
								[4754]
								2-3 ��� = 1 �����
								4-5 ��� = 1 ������
								6-7 ��� = 2 �������
								8-9 ��� = 3 �������
								� ������ ������ (�3)
								
							*/
							$tmon = array(
								2 => 1,
								3 => 1,
								4 => 1,
								5 => 1,
								6 => 1,
								7 => 2,
								8 => 3,
								9 => 3,
								10 => 5,
								11 => 5
							);
							$tmon = $tmon[$this->users[$i]['level']];
							//
							$tmonc = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle` WHERE `id` IN ( SELECT `battle_id` FROM `battle_last` WHERE `uid` = "'.$this->users[$i]['id'].'" ) AND `priz` > 0 AND `time_start` > "'.strtotime(date('d.m.Y')).'"'));
							$tmonc = $tmonc[0];
							//
							if($this->users[$i]['team']==$this->info['team_win']){
								//��� �������� �����������
								if( $tmonc < 1 ) {
									$tmonc = 1;
								}elseif( $tmonc > 96 ) {
									$tmonc = 96;
								}
								$tmon = ( $tmon * $tmonc );
							}
							//
							$tmoni = 1;
							while( $tmoni <= floor($tmon/2) ) {
								$u->addItem(4754,$this->users[$i]['id'],'');
								$tmoni++;
							}
							//
							$this->users[$i]['battle_text'] .= ' �� �������� ���� �� ��������� <b>�������� ����� (x'.floor($tmon/2).')</b>. (��� ������ �������� ������ �� ������� �� �����, ��� ������ ����� ���������� ������� ��� ������! �������� ������ �������� ������� '.($tmonc).'/96 )';
							//
							unset($tmon,$tmoni);
						}
						
						if( $this->info['razdel'] == 5 || $this->info['razdel'] == 4 ) {
							if( date('d.m') == '31.10' || (date('m') == 11 && date('d') < 8) ) {
								//�������� 4504
								$this->users[$i]['battle_text'] .= ' �� ��������� <b>����� (x1)</b>.';
								$u->addItem(4504,$this->users[$i]['id'],'|sudba=1');
							}
						}
						
						//��������� ��������������
						/*if( $this->info['dungeon'] == 0 && $this->info['razdel'] == 5 && $this->users[$i]['exp'] >= 2500 /*($this->users[$i]['exp'] < 12499 || $this->users[$i]['exp'] > 12500)*/
						/* ) {
							/*if( $this->users[$i]['battle_exp'] > 100 * $this->users[$i]['level'] ) {
								$rzbvo = 0;
								if( $this->info['players_c'] > 4 ) {
									$rzbvo = 2*$this->info['players_c'];
								}
								$this->users[$i]['battle_text'] .= ' �� �������� '.$rzbvo.' �������������� �� ���� ���.';
								mysql_query('UPDATE `rep` SET `rep3` = `rep3` + "'.$rzbvo.'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							}
						}*/
						if( $this->stats[$i]['hpNow'] >= 1 ) {
							$this->stats[$i]['test_heal'] = mysql_fetch_array(mysql_query('SELECT SUM(`yrn`) FROM `battle_stat` WHERE `uid2` = "'.$this->users[$i]['id'].'" AND `battle` = "'.$this->info['id'].'" LIMIT 1'));
							$this->stats[$i]['test_heal'] = $this->stats[$i]['test_heal'][0];
							$this->stats[$i]['test_start'] = mysql_fetch_array(mysql_query('SELECT `hpStart` FROM `battle_users` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `battle` = "'.$this->info['id'].'" LIMIT 1'));
							$this->stats[$i]['test_start'] = $this->stats[$i]['test_start']['hpStart'];
							//������� �� ����� ���
							//$this->stats[$i]['hpNow'] = ( $this->stats[$i]['test_start']-$this->stats[$i]['test_heal'] );
							if( $this->stats[$i]['hpNow'] < 1 ) {
								$this->stats[$i]['hpNow'] = 1;
							}
						}else{
							$this->stats[$i]['hpNow'] = 0;
						}
						/*
						$this->stats[$i]['test_heal'] = ($this->stats[$i]['hpNow']-$this->stats[$i]['hpAll']) + $this->stats[$i]['test_heal'];
						if($this->users[$i]['team']==$this->info['team_win']){
							//��������� �� ����� ����
							$this->stats[$i]['hpNow'] -= $this->stats[$i]['test_heal'];
							$this->users[$i]['hpNow'] = $this->stats[$i]['hpNow'];
							if(floor($this->stats[$i]['hpNow']) < 0) {
								$this->stats[$i]['hpNow'] = 0;
							}
						}else{
							$this->stats[$i]['hpNow'] = 0;
						}
						*/
						unset($this->stats[$i]['test_heal']);
						
						$this->users[$i]['last_b'] = $this->info['id']; //stats
						$this->users[$i]['last_a'] = $act01;
						$this->users[$i]['battle'] = -1; //users
						$this->users[$i]['battle_yron'] = 0; //stats
							
						$this->users[$i]['exp']  += $this->users[$i]['battle_exp']; //users
						
						/*if($this->stats[$i]['speeden']>2) { // ��� �������������� ������� (�������� ��������)
							$this->users[$i]['enNow']+= $this->stats[$i]['enNow']; //users
							$upd2 = mysql_query('UPDATE `stats` SET `enNow` = "'.$this->users[$i]['enNow'].'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
						}*/
						//��������� ���� ���� (��� ����� �����)
						
						if($this->users[$i]['clan'] > 0) {
							$cpr = 1;
							if($this->info['typeBattle'] == 9) {
								$cpr = 25;
							}elseif($this->info['typeBattle'] == 50) {
								$cpr = 65;
							}
							if( $this->stats[$i]['silver'] >= 5 ) {
								$cpr = floor($cpr/100*150);
							}
							mysql_query('UPDATE `clan` SET `exp` = `exp` + "'.round($this->users[$i]['battle_exp']/100*$cpr).'" WHERE `id` = "'.$this->users[$i]['clan'].'" LIMIT 1');
						}
						
						$this->users[$i]['battle_exp'] = 0; //stats
						
						if($this->users[$i]['team']==$this->info['team_win']) {
							mysql_query('UPDATE `rep` SET `n_capitalcity` = `n_capitalcity` + '.$this->users[$i]['bn_capitalcity'].' ,`n_demonscity` = `n_demonscity` + '.$this->users[$i]['bn_demonscity'].' ,`n_demonscity` = `n_demonscity` + '.$this->users[$i]['bn_suncity'].' WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
						}
						
						//��������� 
						$this->users[$i]['bn_demonscity'] = 0;
						$this->users[$i]['bn_capitalcity'] = 0;
						$this->users[$i]['bn_suncity'] = 0;
					//���������� �������� � �������
						$spe = mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `file_finish` != "" AND `v1` = "priem" LIMIT 30');
						while($ple = mysql_fetch_array($spe)) {
							if(file_exists('../../_incl_data/class/priems/'.$ple['file_finish'].'.php'))	{
								require('../../_incl_data/class/priems/'.$ple['file_finish'].'.php');
							}
						}
					//��������� ������
						mysql_query('DELETE FROM `eff_users` WHERE `v1` = "priem" AND `uid` = "'.$this->users[$i]['id'].'" LIMIT 50');
						if($dnr==1){
							if( $this->users[$i]['room'] == 370 ) {
								$dies = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `dn` = "'.$this->users[$i]['dnow'].'" AND `vars` = "dielaba" LIMIT 1'));
								$dies = $dies[0];
								mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
									"'.$this->users[$i]['dnow'].'","'.$this->users[$i]['id'].'","'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'","'.time().'","dielaba",""
								)');
							}else{
								$dies = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `dungeon_actions` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `dn` = "'.$this->users[$i]['dnow'].'" AND `vars` = "die" LIMIT 1'));
								$dies = $dies[0];
								mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`x`,`y`,`time`,`vars`,`vals`) VALUES (
									"'.$this->users[$i]['dnow'].'","'.$this->users[$i]['id'].'","'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'","'.time().'","die",""
								)');
							}
							if( $dies < 2 || $this->info['dungeon'] == 15 ) {
								//
								$tshbn = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `delete` = 0 AND `item_id` = "4910" LIMIT 1'));								
								if(isset($tshbn['id'])) {
									//����������� �����
									mysql_query('DELETE FROM `items_users` WHERE `id` = "'.$tshbn['id'].'" LIMIT 1');
									//
									mysql_query('INSERT INTO `dungeon_obj` (
										`name`,`dn`,`x`,`y`,`img`,`delete`,`action`,`for_dn`,
										`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`
									) VALUES (
										"�����","'.$this->info['dn_id'].'","'.$this->users[$i]['x'].'","'.$this->users[$i]['y'].'","shaiba.png","0","fileact:15/shaiba","0",
										"0","120","220","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt1:69,rl1:-47,rt2:74,rl2:126,rt3:76,rl3:140,rt4:80,rl4:150}"
									)');
									//
								}
								//������������� � ������� (���������� 0�0)
								$this->users[$i]['x'] = $this->users[$i]['res_x'];
								$this->users[$i]['y'] = $this->users[$i]['res_y'];
								$this->users[$i]['s'] = $this->users[$i]['res_s'];
								$r_n = mysql_fetch_array(mysql_query('SELECT `name` FROM `room` WHERE `id` = "'.(int)$this->users[$i]['room'].'" LIMIT 1'));
								if( $this->users[$i]['room'] == 370 ) {
									if( $this->users[$i]['sex'] == 0 ) {
										$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ����� � ��������� � ������ ���������';
									}else{
										$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ������� � ��������� � ������ ���������';
									}
								}else{
									if( $this->users[$i]['sex'] == 0 ) {
										$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ����� � ��������� � ������� &quot;'.$r_n['name'].'&quot;';
									}else{
										$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ������� � ��������� � ������� &quot;'.$r_n['name'].'&quot;';
									}
								}
							}elseif( $this->info['dungeon'] == 102 ) {
								$nld = '';
								$lab = mysql_fetch_array(mysql_query('SELECT `id`,`users` FROM `laba_now` WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1'));
								if( $lab['users'] < 2 ) {
									//������� ����������
									mysql_query('DELETE FROM `laba_now` WHERE `id` = "'.$lab['id'].'" LIMIT 1');
									mysql_query('DELETE FROM `laba_map` WHERE `id` = "'.$lab['id'].'" LIMIT 1');
									mysql_query('DELETE FROM `laba_obj` WHERE `lib` = "'.$lab['id'].'"');
									mysql_query('DELETE FROM `laba_act` WHERE `lib` = "'.$lab['id'].'"');
									mysql_query('DELETE FROM `laba_itm` WHERE `lib` = "'.$lab['id'].'"');
								}else{
									$lab['users']--;
									mysql_query('UPDATE `laba_now` SET `users` = "'.$lab['users'].'" WHERE `id` = "'.$lab['id'].'" LIMIT 1');
								}
								mysql_query('UPDATE `stats` SET `dnow` = "0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
								mysql_query('UPDATE `users` SET `room` = "369" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
								//������� ��� �������� ������� ��������� ����� ������ �� ������
								mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$this->users[$i]['id'].'" AND `delete` < 1234567890 AND `inShop` = "0" AND (`dn_delete` = "1" OR `data` LIKE "%fromlaba=1%")');
								if( $this->users[$i]['login'] != '' ) {
									if( $this->users[$i]['sex'] == 0 ) {
										$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ����� ��� ����� �� ����������� � �������� ����������'.$nld;
									}else{
										$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ������� ��� ����� �� ����������� � �������� ����������'.$nld;
									}
								}
							}else{
								$tinf = mysql_fetch_array(mysql_query('SELECT `uid` FROM `dungeon_now` WHERE `id` = "'.$this->info['dn_id'].'" LIMIT 1'));
								$nld = '';
								if( $tinf['uid'] == $this->users[$i]['id'] ) {
									$tinf = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dnow` = "'.$this->info['dn_id'].'" AND `hpNow` >= 1 LIMIT 1'));
									if( isset($tinf['id']) ) {
										$tinf = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.$tinf['id'].'" LIMIT 1'));
										$nld .= ', ����� ������� ���������� &quot;'.$tinf['login'].'&quot;';
										mysql_query('UPDATE `dungeon_now` SET `uid` = "'.$tinf['id'].'" WHERE `id` = "'.$this->info['dn_id'].'" LIMIT 1');
									}
								}
								$rooms = array(
									374 => 372,		//��� (������� ����)
									189 => 188,		//�������� (������� ����)
									392 => 393,		//����� (������� ����)
									
									398 => 397,		//����� (������ ����)
									243 => 395,		//�������� (������ ����)
									360 => 242,		//������ (������ ����)
									
									19 => 293		//���������
								);
								$n_rm = $rooms[$this->users[$i]['room']];
								mysql_query('UPDATE `stats` SET `dnow` = "0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
								mysql_query('UPDATE `users` SET `room` = "'.$n_rm.'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
								//������� ��� �������� ������� ��������� ����� ������ �� ������
								mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$this->users[$i]['id'].'" AND `dn_delete` = "1" LIMIT 1000');
								mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `item_id` = "1189" OR `item_id` = "4447" OR `item_id` = "1174") LIMIT 1000');
								if( $this->users[$i]['sex'] == 0 ) {
									$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ����� ��� ����� �� ����������� � �������� ����������'.$nld;
								}else{
									$rtxt = '<b>'.$this->users[$i]['login'].'</b> ���������� ������� ��� ����� �� ����������� � �������� ����������'.$nld;
								}
							}
							if( $rtxt != '' ) {
								mysql_query("INSERT INTO `chat` (`dn`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$this->info['dn_id']."','".$this->users[$i]['city']."','".$this->users[$i]['room']."','','','".$rtxt."','".time()."','6','0','1','1')");
							}
						}
						
						mysql_query('UPDATE `users` SET `login2` = "" WHERE `battle` = "'.$this->info['id'].'"');
						$upd  = mysql_query('UPDATE `users` SET `login2` = "", `money` = "'.$this->users[$i]['money'].'",`win` = "'.$this->users[$i]['win'].'",`lose` = "'.$this->users[$i]['lose'].'",`nich` = "'.$this->users[$i]['nich'].'",`battle` = "-1" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
						
						if( $u->info['id'] == $this->users[$i]['id'] ) {
							$u->info['battle_text'] = $this->users[$i]['battle_text'];
						}
						
						$upd2 = mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$i]['hpNow'].'",`mpNow` = "'.$this->stats[$i]['mpNow'].'",`bn_capitalcity` = 0,`bn_demonscity` = 0,`smena` = 3,`tactic7` = "-100",`x`="'.$this->users[$i]['x'].'",`y`="'.$this->users[$i]['y'].'",`priems_z`="0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0",`last_pr`="0",`tactic1`="0",`tactic2`="0",`tactic3`="0",`tactic4`="0",`tactic5`="0",`tactic6`="0.00000000",`tactic7`="10",`exp` = "'.$this->users[$i]['exp'].'",`battle_exp` = "'.$this->users[$i]['battle_exp'].'",`battle_text` = "'.$this->users[$i]['battle_text'].'",`battle_yron` = "0",`enemy` = "0",`last_b`="'.$this->info['id'].'",`regHP` = "'.time().'",`regMP` = "'.time().'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					if($this->info['turnir'] == 0) {
					//����� � ���
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$i]['city']."','".$this->users[$i]['room']."','','".$this->users[$i]['login']."','".$this->users[$i]['battle_text']."','-1','6','0')");				
					}else{
						mysql_query('UPDATE `turnirs` SET `winner` = "'.$this->info['team_win'].'" WHERE `id` = "'.$this->info['turnir'].'" LIMIT 1');
					}
					//��������� ��� ���				
					$upd3  = mysql_query('UPDATE `battle` SET `time_over` = "'.time().'",`team_win` = "'.$this->info['team_win'].'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					
					//���� ��� �� (������ ������)
					/*$tinf = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_now` WHERE `id` = "'.$this->info['dn_id'].'" LIMIT 1'));
					if(isset($tinf['id']) && $tinf['bsid']>0)
					{
						$bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `city` = "'.$u->info['city'].'" AND `id` = "'.$tinf['bsid'].'" AND `time_start` = "'.$tinf['time_start'].'" LIMIT 1'));
						if(isset($bs['id']))
						{
							$u->bsfinish($bs,$this->users,$this->info);				
						}
					}*/
					// ����� �����
					if($this->users[$i]['animal'] > 0) {
						$a = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `id` = "'.$this->users[$i]['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
						if(isset($a['id'])) {
							if($a['eda']<1) {
								$u->send('',$this->users[$i]['room'],$this->users[$i]['city'],'',$this->users[$i]['login'],'<b>'.$a['name'].'</b> ��������� � ���...',time(),6,0,0,0,1);
							}
						}
					}
					mysql_query('UPDATE `stats` SET `battle_text` = "",`last_b`="0" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
					mysql_query('UPDATE `battle_users` SET `finish` = 1 WHERE `uid` = "'.$this->users[$i]['id'].'"');
					//mysql_query('DELETE FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'"');
					$this->saveLogs( $this->info['id'] , 'all' );
					if( $u->info['battle'] != 0 && !isset($u->info['battle_lsto']) ) {
						echo '<script>document.getElementById(\'teams\').style.display=\'none\';var battleFinishData = "'.$u->info['battle_text'].'";</script>';
					}
					//mysql_query('UNLOCK TABLES');
				}else{
					//echo '<font color=red><b>�������� ����������, �� �� ������� ��� � 0-�� ������, �������� �������������!</b></font>';
				}
			}
			mysql_query('UPDATE `battle` SET `testfinish` = "-1" WHERE `id` = "'.$test['id'].'" LIMIT 1');
			//unlink($lock_file);
			}
		}
		
	//������ ��������
	//$this->addGlobalItems($this->user[$i]['id'],$this->user[$j]['id'],$mon['win_itm'],$mon['win_eff'],$mon['win_ico'],$mon['win_exp'],$mon['win_money'],$mon['win_money2']);
		public $ainm = array();
		public function addGlobalItems($bid,$uid,$itm,$eff,$ico,$exp,$cr,$ecr) {
			global $u;
			//
			//��������� ����� �� ��
			if( $bid == 1008 ) {
				//������ ����� ���
				$jit = 0;
				$iit = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `online` > '.(time()-120).''));
				$iit = floor($iit[0]/20);
				$iit = rand(1,$iit);
				while( $jit < $iit ) {
					if( rand(0,100) < 50 ) {
						$svtk = array(
							1000,1000,1000,1000,1000,1000,1000,1000,
							1000,1000,1000,1000,1000,1000,1000,1000,1000,
							1000,1000,1000,1000,1000,1000,1000,1000,1000,
							1000,1000,1000,1000,1000,1000,1000,1000,
							1461,1462,1463,
							4037,4038,4039,4040,
							911,1172,1173,2142,2141,2143,2870,2144,1000,
							1000,1000,1000,1000,1000,1000,1000,1000,1000,
							1000,1000,1000,1000,1000,1000,1000,1000,1000,
							1000,1000,1000,1000,1000,1000,1000,1000,1000,
							1000,1000,1000,1000,1000,1000,1000,1000,1000				
						);
						$svtk = $svtk[rand(0,count($svtk)-1)];
						if( $svtk == 1000 ) {
							mysql_query('INSERT INTO `items_local`
							( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
							(
								"'.$this->users[$this->uids[$uid]]['room'].'",
								"'.time().'",
								"'.$svtk.'",
								"|nosale=1|srok=259200",
								"'.$this->users[$this->uids[$uid]]['login'].'",
								"1"
							),(
								"'.$this->users[$this->uids[$uid]]['room'].'",
								"'.time().'",
								"'.$svtk.'",
								"|nosale=1|srok=259200",
								"'.$this->users[$this->uids[$uid]]['login'].'",
								"1"
							),(
								"'.$this->users[$this->uids[$uid]]['room'].'",
								"'.time().'",
								"'.$svtk.'",
								"|nosale=1|srok=259200",
								"'.$this->users[$this->uids[$uid]]['login'].'",
								"1"
							),(
								"'.$this->users[$this->uids[$uid]]['room'].'",
								"'.time().'",
								"'.$svtk.'",
								"|nosale=1|srok=259200",
								"'.$this->users[$this->uids[$uid]]['login'].'",
								"1"
							),(
								"'.$this->users[$this->uids[$uid]]['room'].'",
								"'.time().'",
								"'.$svtk.'",
								"|nosale=1|srok=259200",
								"'.$this->users[$this->uids[$uid]]['login'].'",
								"1"
							),(
								"'.$this->users[$this->uids[$uid]]['room'].'",
								"'.time().'",
								"'.$svtk.'",
								"|nosale=1|srok=259200",
								"'.$this->users[$this->uids[$uid]]['login'].'",
								"1"
							)');
						}
						mysql_query('INSERT INTO `items_local`
						( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
						(
							"'.$this->users[$this->uids[$uid]]['room'].'",
							"'.time().'",
							"'.$svtk.'",
							"|nosale=1|srok=259200",
							"'.$this->users[$this->uids[$uid]]['login'].'",
							"1"
						)');
					}
					$jit++;
				}
				unset($svtk);
			}elseif( $bid == 1007 ) {
				//��������, ������������ CAPITAL CITY
				$jit = 0;
				$iit = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `online` > '.(time()-120).''));
				$iit = floor($iit[0]/20);
				$iit = rand(1,$iit);
				while( $jit < $iit ) {
					if( rand(0,100) < 50 ) {
						mysql_query('INSERT INTO `items_local`
						( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
						(
							"'.$this->users[$this->uids[$uid]]['room'].'",
							"'.time().'",
							"4504",
							"",
							"'.$this->users[$this->uids[$uid]]['login'].'",
							"1"
						)');
					}
					$jit++;
				}
			}elseif( $bid == 1006 ) {
				//�������� CAPITAL CITY
				if( rand(0,100) < 10 ) {
					mysql_query('INSERT INTO `items_local`
					( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
					(
						"'.$this->users[$this->uids[$uid]]['room'].'",
						"'.time().'",
						"4451",
						"srok=86400",
						"'.$this->users[$this->uids[$uid]]['login'].'",
						"1"
					)');
				}
			}elseif( $bid == 1000 ) {
				//�������� CAPITAL CITY
				//if( rand(0,100) < 10 ) {
					mysql_query('INSERT INTO `items_local`
					( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
					(
						"'.$this->users[$this->uids[$uid]]['room'].'",
						"'.time().'",
						"4460",
						"srok=2592000",
						"'.$this->users[$this->uids[$uid]]['login'].'",
						"1"
					)');
				//}
			}elseif( $bid == 1001 ) {
				//�������� CAPITAL CITY
				//if( rand(0,100) < 10 ) {
					mysql_query('INSERT INTO `items_local`
					( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
					(
						"'.$this->users[$this->uids[$uid]]['room'].'",
						"'.time().'",
						"4461",
						"srok=2592000",
						"'.$this->users[$this->uids[$uid]]['login'].'",
						"1"
					)');
				//}
			}elseif( $bid == 1002 ) {
				//�������� CAPITAL CITY
				//if( rand(0,100) < 10 ) {
					mysql_query('INSERT INTO `items_local`
					( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
					(
						"'.$this->users[$this->uids[$uid]]['room'].'",
						"'.time().'",
						"4462",
						"srok=2592000",
						"'.$this->users[$this->uids[$uid]]['login'].'",
						"1"
					)');
				//}
			}elseif( $bid == 1003 ) {
				//�������� CAPITAL CITY
				//if( rand(0,100) < 10 ) {
					mysql_query('INSERT INTO `items_local`
					( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
					(
						"'.$this->users[$this->uids[$uid]]['room'].'",
						"'.time().'",
						"4463",
						"srok=2592000",
						"'.$this->users[$this->uids[$uid]]['login'].'",
						"1"
					)');
				//}
			}elseif( $bid == 1004 ) {
				//�������� CAPITAL CITY
				//if( rand(0,100) < 10 ) {
					mysql_query('INSERT INTO `items_local`
					( `room` , `time`,`item_id`,`data`,`tr_login`,`colvo` ) VALUES
					(
						"'.$this->users[$this->uids[$uid]]['room'].'",
						"'.time().'",
						"4459",
						"srok=2592000",
						"'.$this->users[$this->uids[$uid]]['login'].'",
						"1"
					)');
				//}
			}
			//			
			if( $exp > 0 ) {
				$this->users[$this->uids[$uid]]['battle_exp'] += round($exp*$this->users[$this->uids[$uid]]['battle_yron']/$this->stats[$this->uids[$bid]]['hpAll']);
				mysql_query('UPDATE `stats` SET `battle_exp` =  "'.mysql_real_escape_string($this->users[$this->uids[$uid]]['battle_exp']).'" WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1');
			}
			//
			if( $cr != '' && $cr > 0 ) {
				if( $this->stats[$this->uids[$uid]]['hpNow'] > 0 ) {
					mysql_query('UPDATE `users` SET `money` = (`money` + '.mysql_real_escape_string($cr).') WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>�� �������� �������:</b> ".mysql_real_escape_string($cr)." <b>��.</b></font>','-1','6','0')");
				}
			}
			//
			if( $ecr != '' && $ecr > 0 ) {
				if( $this->stats[$this->uids[$uid]]['hpNow'] > 0 ) {
					$bnkt = mysql_fetch_array(mysql_query('SELECT `id` FROM `bank` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `block` = "0" ORDER BY `id` DESC LIMIT 1'));
					if(mysql_query('UPDATE `bank` SET `money2` = `money2` + "'.mysql_real_escape_string($ecr).'" WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `block` = "0" ORDER BY `id` DESC LIMIT 1')) {
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>�� �������� ����-�������:</b> ".mysql_real_escape_string($ecr)." <b>���.</b> (���� �".$bnkt['id'].")</font>','-1','6','0')");
					}else{
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>�� ����� �������� ����-������� �� ���� ���, �� � ��� ��� ����������� �����.</b> ��� ����� ������� �� ������������ ��. � ������ ����� �� ��������� ���������� ��������.</font>','-1','6','0')");
					}
				}
			}
			//
			if( $ico != '' ) {
				/*
				0(���, 1 - ������, 2 - ������)@
				1(����� � �������)@
				2(�������� ��������)@
				3(��������)@
				4(������� �������� � ����� 0 ��� 1, ���� ����� ���� -1)@
				5(������� ������ � ����� ����� � % �������� 0.001)@
				6(�������� ��������: add_s1=5|add_hpAll=50)@
				7(������� ������ ������, �������� ��������)@
				8(������� ������ 0 ��� 1)@
				9(������� ������� ������ 0 or 1)
				*/
				$i = 0;
				$txt = '';
				$ico = explode('#',$ico);
				while( $i < count($ico) ) {
					$ico_e = explode('@',$ico[$i]);
					if(isset($ico_e[3])) {
						//
						$add = 1;
						if($ico_e[4] == 1 && floor($this->stats[$this->uids[$uid]]['hpNow']) < 1) {
							$add = 0;
						}
						if( $add == 1 ) {
							$ins = false;
							if( $ico_e[8] == 0 ) {
								$ins = true;
								if( $ico_e[9] == 1 ) {
									mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `img` = "'.mysql_real_escape_string($ico_e[2]).'"');
								}
							}else{
								$old_ico = mysql_fetch_array(mysql_query('SELECT `id` FROM `users_ico` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND (`endTime` > "'.time().'" OR `endTime` = 0) AND `img` = "'.mysql_real_escape_string($ico_e[2]).'" LIMIT 1'));
								if( !isset($old_ico['id'])) {
									$ins = true;
								}else{
									if( $old_ico['id'] > 0 ) {
										$txt .= ', &quot;'.$ico_e[3].' (<small>����������</small>)&quot;';
										mysql_query('UPDATE `users_ico` SET `x` = `x` + 1,`endTime` = "'.mysql_real_escape_string(time()+$ico_e[1]*60).'" WHERE `id` = "'.$old_ico['id'].'" LIMIT 1');
									}else{
										$ins = true;
									}
								}
								unset($old_ico);
							}
							if($ins == true) {
								if( $ico_e[9] == 1 ) {
									mysql_query('DELETE FROM `users_ico` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `img` = "'.mysql_real_escape_string($ico_e[2]).'"');
								}
								mysql_query('INSERT INTO `users_ico` (`uid`,`time`,`text`,`img`,`endTime`,`type`,`bonus`) VALUES (
									"'.mysql_real_escape_string($uid).'",
									"'.time().'",
									"'.mysql_real_escape_string($ico_e[3]).'",
									"'.mysql_real_escape_string($ico_e[2]).'",
									"'.mysql_real_escape_string(time()+$ico_e[1]*60).'",
									"'.mysql_real_escape_string($ico_e[0]).'",
									"'.mysql_real_escape_string($ico_e[6]).'"
								)');
								$txt .= ', &quot;'.$ico_e[3].'&quot;';
							}
						}
						//
					}
					$i++;
				}
				if( $txt != '' ) {
					$txt = ltrim($txt,', ');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>�� ��������� ������:</b> ".mysql_real_escape_string($txt)."</font>','-1','6','0')");
				}
			}
			//
			if( $itm != '' ) {
				$i = 0;
				$txt = '';
				$itm = explode(',',$itm);
				while($i < count($itm)) {
					$itm_e = explode('@',$itm[$i]);
					if($itm_e[0] > 0) {
						$j = 0;
						while( $j < $itm_e[1] ) {
							$u->addItem($itm_e[0],$uid,'|'.$itm_e[2]);
							$j++;
						}
						if( !isset($this->ainm[$itm_e[0]]) ) {
							$this->ainm[$itm_e[0]] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($itm_e[0]).'" LIMIT 1'));
						}
						if( isset($this->ainm[$itm_e[0]]['id']) ) {
							//��������� ����� � ���������� ��������
							$txt .= ', <b>'.$this->ainm[$itm_e[0]]['name'].'</b>';
							if( $itm_e[1] > 1 ) {
								$txt .= ' <b>(x'.$itm_e[1].')</b>';
							}
						}
					}
					$i++;
				}
				if( $txt != '' ) {
					$txt = ltrim($txt,', ');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$this->users[$this->uids[$uid]]['city']."','".$this->users[$this->uids[$uid]]['room']."','','".$this->users[$this->uids[$uid]]['login']."','<font color=#cb0000><b>�� �������� ��������:</b> ".mysql_real_escape_string($txt)."</font>','-1','6','0')");
				}
			}
			//
		}
	
	//�������� ����
		public function addNewAtack()
		{
			global $u;
			if(!isset($this->ga[$u->info['id']][$u->info['enemy']]))
			{
				if($this->stats[$this->uids[$u->info['id']]]['hpNow']>0)	
				{
					/*$us = $this->stats[$this->uids[$u->info['id']]];
					$i = 1; $no = 0;
					if($us['weapon1']!=1 && $us['weapon2']==1)
					{
						$uz['zona'] += 1;
					}
					while($i<=$uz['zona'])
					{
						if($this->uAtc['a'][$i]==0)
						{	
							$no = 1;
						}
						$i++;
					}*/
					$us = $this->stats[$this->uids[$u->info['id']]];
					$i = 1; $no = 0;
                    
                    while($i <= $us['zona']) {
                      if($this->uAtc['a'][$i] == 0) {
                        $no = 1;
                      }
                      $i++;
                    }
					
					
					if($this->uAtc['b'] == 0)
					{
						$no = 1;
					}

					
					if($no==0)
					{
						//������� ����
						if($u->info['enemy'] > 0)
						{
		
							if(!isset($this->ga[$u->info['enemy']][$u->info['id']]))
							{
								if($this->stats[$this->uids[$u->info['id']]]['hpNow']>=1 && $this->stats[$this->uids[$u->info['enemy']]]['hpNow']>=1)
								{
									//������� ����� ����
									$a = $this->uAtc['a'][1].''.$this->uAtc['a'][2].''.$this->uAtc['a'][3].''.$this->uAtc['a'][4].''.$this->uAtc['a'][5];
									$b = $this->uAtc['b'];	
									mysql_query('DELETE FROM `battle_act` WHERE `battle` = "'.$this->info['id'].'" AND ((`uid2` = "'.$u->info['id'].'" AND `uid1` = "'.$u->info['enemy'].'") OR (`uid1` = "'.$u->info['id'].'" AND `uid2` = "'.$u->info['enemy'].'")) LIMIT 2');						
									$d = mysql_query('INSERT INTO `battle_act` (`battle`,`time`,`uid1`,`uid2`,`a1`,`b1`) VALUES ("'.$this->info['id'].'","'.time().'","'.$u->info['id'].'","'.$u->info['enemy'].'","'.$a.'","'.$b.'")');
									if(!$d)
									{
										$this->e = '�� ������� ������� ���� �� ����������...';
									}else{
										$this->ga[$u->info['id']][$u->info['enemy']] = mysql_insert_id();
									}
								}								
							}else{
								//�������� �� ���� ����������
								if($this->stats[$this->uids[$u->info['id']]]['hpNow']>=1 && $this->stats[$this->uids[$u->info['enemy']]]['hpNow']>=1)
								{
									if(isset($this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['id']))
									{
										$this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['a2'] = $this->uAtc['a'][1].''.$this->uAtc['a'][2].''.$this->uAtc['a'][3].''.$this->uAtc['a'][4].''.$this->uAtc['a'][5];
										$this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['b2'] = $this->uAtc['b'];
										$this->startAtack($this->atacks[$this->ga[$u->info['enemy']][$u->info['id']]]['id']);
									}
								}
							}
						}else{
							//�������� ���� ���������� (��� ����������� ����������)
							
						}
					}else{
						$this->e = '�������� ���� ����� � �����';
					}
				}else{
					$this->e = '��� ��� �������� ��������, �������� ���� �������� ������...';
				}
			}else{
				//��� ������� ����������, �������� ����
				
			}	
		}
	
	//��������� ���������� ��������, ���� � ��� ���-�� ��������
		public function magicItems($uid1,$uid2,$end)
		{
			global $u,$priem,$c,$code;
			if(isset($this->stats[$this->uids[$uid1]]))
			{
				$i = 0;
				while($i<count($this->stats[$this->uids[$uid1]]['items']))
				{
					$itm = $this->stats[$this->uids[$uid1]]['items'][$i];
					if(isset($itm['id']))
					{
						$e = $u->lookStats($itm['data']);
						if(isset($e['bm_a1']))
						{
							if( $end > 0 ) {
								if(file_exists('../../_incl_data/class/priems/'.$e['bm_a1'].'.end.php'))
								{
									require('../../_incl_data/class/priems/'.$e['bm_a1'].'.end.php');
								}
							}else{
								if(file_exists('../../_incl_data/class/priems/'.$e['bm_a1'].'.php'))
								{
									require('../../_incl_data/class/priems/'.$e['bm_a1'].'.php');
								}
							}
						}
					}
					$i++;
				}
				unset($itm);
			}
		}
	//���������� �����
		public function testPog($uid,$yr)
		{
			//$yr = round($yr*1.25);
			$yr2 = $yr;
			if($yr > 0) {
			$testmana=false;
			global $u,$priem;
			$i = 0;			
			$ypg22 = 0;
			while($i < count($this->stats[$this->uids[$uid]]['set_pog2']))
			{			
				$j = $this->stats[$this->uids[$uid]]['set_pog2'][$i];                
				$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog2='.$j['y'],'add_pog2=$',$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
				$dt3 = $u->lookStats($this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);				
				if(isset($dt3['add_pog2mp'])) {
					$priem->minMana($uid,round(round($yr2/100*(100-$dt3['add_pog2p']))*$dt3['add_pog2mp']));
				}				
				$j['y'] -= $yr2; // �������� ��� ����������			
				if(isset($dt3['add_pog2p'])) {
					$yr2 = round($yr2/100*(100-$dt3['add_pog2p']));
					//echo '[���������: '.($dt3['add_pog2p']).'% �� '.(round(round($yr2/100*(100-$dt3['add_pog2p']))*$dt3['add_pog2mp'])).'MP]';
				}
				unset($dt3);				
				if($j['y'] < 0 || ($this->stats[$this->uids[$uid]]['mpNow'] <= 0 && $dt3['add_pog2mp'] > 0)) {					
					$dt2 = $u->lookStats($this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
					if(isset($dt2['endPog']) && $dt2['endPog'] == 1)
					{
						//������� �����
						$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['v2'].'" LIMIT 1'));
						$this->delPriem($this->stats[$this->uids[$uid]]['effects'][$j['id']-1],$this->users[$this->uids[$uid]],4,$uid);
						$this->stats[$this->uids[$uid]]['effects'][$j['id']-1] = 'delete';
					}
					unset($dt2);
					$yr2 = -($j['y']);
					$j['y'] = 0;
				}
								
				$this->stats[$this->uids[$uid]]['set_pog'][$i]['y'] = $j['y'];	
				$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog2=$','add_pog2='.$j['y'],$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
				$upd = mysql_query('UPDATE `eff_users` SET `data` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'].'" WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['id'].'" LIMIT 1');
				if($upd)
				{
					
				}
				$i++;
			}
			
			}
			return $yr2;

		}
		public $rehodeff = array();
		
	//���������� �����
		public $poglast = array();
		
		public function testPogB($uid,$yr,$pliid,$test = 0)
		{
			//$yr = round($yr*1.25);
			$yr2 = $yr;
			if($yr > 0) {
			$testmana=false;
			global $u,$priem;
			$i = 0;			
			$ypg22 = 0;
			while($i < count($this->stats[$this->uids[$uid]]['set_pog2']))
			{			
				$j = $this->stats[$this->uids[$uid]]['set_pog2'][$i];              
				if( $this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['id'] == $pliid || $test == 1 ) {
					$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog2='.$j['y'],'add_pog2=$',$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
					$dt3 = $u->lookStats($this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);				
					//
					$dt30 = 0;
					$dt30 = floor($j['y']/$yr2*100);
					//
					//echo '['.$j['y'].'|'.$dt3['add_pog2'].'|'.$yr2.' -> '.$dt30.'/'.$dt3['add_pog2p'].'] ';
					//
					if( $dt30 < $dt3['add_pog2p'] ) {
						$dt3['add_pog2p'] = $dt30;
					}
					//
					unset($dt30);
					//
					if(isset($dt3['add_pog2mp'])) {
						if( (round(round($yr2/100*(100-$dt3['add_pog2p']))*$dt3['add_pog2mp'])) > $this->stats[$this->uids[$uid]]['mpNow']) {
							//�� ������� ����, ������� ������� ������ % �� ������������ �����
							$j['yhj'] = $this->stats[$this->uids[$uid]]['mpNow'] / (round(round($yr2/100*(100-$dt3['add_pog2p']))*$dt3['add_pog2mp']))*100;
							$j['yhj'] = floor($j['yhj']); //������� % �� ����� ���������
							$dt3['add_pog2p'] = floor($dt3['add_pog2p']/100*$j['yhj']);	
							//echo '[!]';						
						}
						if( $test == 1 ) {
							$priem->minMana($uid,round(round($yr2/100*$dt3['add_pog2p'])*$dt3['add_pog2mp']));
						}
					}	
					if(!isset($this->poglast[$uid])) {
						$this->poglast[$uid] = 0;
					}
					$this->poglast[$uid] += $yr2;
					if( $test == 1 ) {
						//$j['y'] -= $this->poglast[$uid]; // �������� ��� ����������	
						$j['y'] -= round($this->poglast[$uid]/100*$dt3['add_pog2p']);
						$priem->minMana($uid,round(round($this->poglast[$uid]/100*$dt3['add_pog2p'])*$dt3['add_pog2mp']));
					}
					if(isset($dt3['add_pog2p'])) {
						$yr2 = round($yr2/100*(100-$dt3['add_pog2p']));
						//echo '[���������: '.($dt3['add_pog2p']).'% ( '.$yr2/100*$dt3['add_pog2p'].' �� '.$yr2.' ��. ) �� '.(round(round($yr2/100*(100-$dt3['add_pog2p']))*$dt3['add_pog2mp'])).'MP , ������� ��: '.$this->stats[$this->uids[$uid]]['mpNow'].']';
					}			
					//unset($dt3);				
					if($j['y'] < 0 || ($this->stats[$this->uids[$uid]]['mpNow'] <= 0 && $dt3['add_pog2mp'] > 0)) {					
						$dt2 = $u->lookStats($this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
						if(isset($dt2['endPog']) && $dt2['endPog'] == 1)
						{
							//������� �����
							//��������� � ���
							$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['v2'].'" LIMIT 1'));
							$this->delPriem($this->stats[$this->uids[$uid]]['effects'][$j['id']-1],$this->users[$this->uids[$uid]],4,$uid);
							$this->stats[$this->uids[$uid]]['effects'][$j['id']-1] = 'delete';
						}
						unset($dt2);
						//$yr2 += -($j['y']);
						$j['y'] = 0;
					}	
					$this->stats[$this->uids[$uid]]['set_pog'][$i]['y'] = $j['y'];
					$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'] = str_replace('add_pog2=$','add_pog2='.$j['y'],$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data']);
					//echo '['.$j['id'].'!'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['id'].']';
					$upd = mysql_query('UPDATE `eff_users` SET `data` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'].'" WHERE `id` = "'.$this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['id'].'" LIMIT 1');
					if($upd) {
						//echo '['.$j['y'].'->'.$yr2.']';
						//echo $this->stats[$this->uids[$uid]]['effects'][$j['id']-1]['data'];
					}
					if( $j['y'] - $this->poglast[$uid]+$yr2 < 0 ) {
						//echo '['.$yr.']';
						$yr -= $yr + ($j['y'] - $this->poglast[$uid]+$yr2);
						//echo '['.$this->poglast[$uid].','.$yr2.','.$j['y'].']';
						$yr2 = $yr;
						$i = count($this->stats[$this->uids[$uid]]['set_pog2'])+1;
					}
				}
				$i++;
			}
			
			}
			return $yr2;

		}

		
	//�������� ��� ����
		public function testHowRazmen($id) {
			$r = array(
				1 => 0, 2 => 0
			);
			if(isset($this->atacks[$id])) {
				if($this->atacks[$id]['out1']>0 && $this->atacks[$id]['out2']>0)
				{
					//����� 1 ��������� ���
					if($this->atacks[$id]['out1'] == 100) {
						//�� �����
						$r[1] = -2;
					}else{
						//�����
						$r[1] = -1;
					}
					//����� 2 ��������� ���
					if($this->atacks[$id]['out2'] == 100) {
						//�� �����
						$r[2] = -2;
					}else{
						//�����
						$r[2] = -1;
					}
				}elseif($this->atacks[$id]['out1']>0)
				{
					//����� 1 ��������� ���
					if($this->atacks[$id]['out1'] == 100) {
						//��������� ��� �� �����
						$r[1] = -2;
					}else{
						//��������� ��� �� �����
						$r[1] = -1;
					}
					//����� 2 ����
					$r[2] = 1;
				}elseif($this->atacks[$id]['out2']>0)
				{
					//����� 2 ��������� ���
					if($this->atacks[$id]['out2'] == 100) {
						//��������� ��� �� �����
						$r[2] = -2;
					}else{
						//��������� ��� �� �����
						$r[2] = -1;
					}
					//����� 1 ����
					$r[1] = 1;
				}else{
					//������ ����� 1 ���� �� ����� 2 , � ����� 2 ���� �� ����� 1
					$r[1] = 1;
					$r[2] = 1;
				}
			}
			return $r;
		}
		
	//��������� ����� � �.�
		public function newRazmen($id,$at) {
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
						
			if( $this->atacks[$id]['out1'] == 0 ) {
				$at[1] = $this->usersTestAtack($id,$uid1,$uid2);
			}else{
				//echo '['. $this->users[$this->uids[$uid1]]['login'] .' ��������� ���]';
				$at[1] = array( 0 );
			}
			if( $this->atacks[$id]['out2'] == 0 ) {
				$at[2] = $this->usersTestAtack($id,$uid2,$uid1);
			}else{
				//echo '['. $this->users[$this->uids[$uid2]]['login'] .' ��������� ���]';
				$at[2] = array( 0 );
			}
			
			return $at;
		}
	
	//�����1 ������� ���� �����2
		public function usersTestAtack($id,$uid1,$uid2) {
			$r = array();			
			$block = array(
				0,
				0,
				0,
				0,
				0,
				0
			);
			
			//�������� ������
				$i = 1;
				if( $uid1 == $this->atacks[$id]['uid1'] ) {
					$a = 2;
					$j = $this->atacks[$id]['b2'];
					$atack = array(
						0,
						$this->atacks[$id]['a1'][0],
						$this->atacks[$id]['a1'][1],
						$this->atacks[$id]['a1'][2],
						$this->atacks[$id]['a1'][3],
						$this->atacks[$id]['a1'][4]
					);
				}elseif( $uid2 == $this->atacks[$id]['uid1'] ) {
					$a = 1;
					$j = $this->atacks[$id]['b1'];
					$atack = array(
						0,
						$this->atacks[$id]['a2'][0],
						$this->atacks[$id]['a2'][1],
						$this->atacks[$id]['a2'][2],
						$this->atacks[$id]['a2'][3],
						$this->atacks[$id]['a2'][4]
					);
				}
				if( $this->atacks[$id]['out' . $a ] == 0 ) {
					while( $i <= $this->stats[$this->uids[$uid2]]['zonb'] ) {
						//echo '{'.$j.'}';
						$block[$j] = 1;
						$j++;
						if( $j > 5 || $j < 1 ) {
							$j = 1;
						}
						$i++;
					}
				}
			//�������� ������
				$i = 1;
				while( $i <= $this->stats[$this->uids[$uid1]]['zona'] ) {
					if( !isset($atack[$i]) || $atack[$i] == 0 ) {
						$atack[$i] = rand(1,5);
					}
					if( $atack[$i] > 0 ) {
						if( $block[$atack[$i]] == 1 ) {
							//���� ��� ������������
							// ���� ��� , ��� �����
							$r['atack'][] = array( $atack[$i] , 3 , 0 );
						}else{
							//���� ������
							// ���� ��� , ��� �����
							$r['atack'][] = array( $atack[$i] , 1 , 0 );
						}
					}
					$i++;
				}
			return $r;
		}
		
	//�������� ���� � �����
		public function testRazmenblock1($id,$uid1,$uid2,$atack) {
			$r = false;
			//�������� ������
				$i = 1;
				if( $uid1 == $this->atacks[$id]['uid1'] ) {
					$j = $this->atacks[$id]['b2'];
				}elseif( $uid2 == $this->atacks[$id]['uid1'] ) {
					$j = $this->atacks[$id]['b1'];
				}
				if( $this->atacks[$id]['out2'] == 0 ) {
					while( $i <= $this->stats[$this->uids[$uid2]]['zonb'] ) {
						//echo '{'.$j.'}';
						$block[$j] = 1;
						$j++;
						if( $j > 5 || $j < 1 ) {
							$j = 1;
						}
						$i++;
					}
				}
			//�������� ������
				if( $atack > 0 ) {
					if( $block[$atack] == 1 ) {
						//���� ��� ������������
						// ���� ��� , ��� �����
						$r = true;
					}else{
						//���� ������
						// ���� ��� , ��� �����
						$r = false;
					}
				}
			return $r;
		}
		
	//��������� ������ ��. �������� (������)
		public function firstRazmen($id,$at) {
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				//������ ������� ���� �� ����������
				
					
				$i++;
			}
			
			return $at;
		}
		
	//�������� ����� ����� � ������
		public function yhod_user( $uid1, $uid2, $type ) {
			// 1 - ��� ���� .  2 - � ���� ���� . ���
			//���� ����� ����� � ����������
			if( $this->import_user == 0 ) {
				$r = $uid1;
				$rand_user = false;
				if( $type == 2 ) {
					//��������� �������� �� ����� ������� (� ��� ����� �����)
					$i = 0;
					while( $i < count( $this->users ) ) {
						if( $this->users[$i]['team'] == $this->users[$this->uids[$uid2]]['team'] ) {
							$rand_user[] = $this->users[$i]['id'];
						}
						$i++;
					}
				}elseif( $type == 4 ) {
					//��������� ��������, �����
					$i = 0;
					while( $i < count( $this->users ) ) {
						//if( $this->users[$i]['team'] == $this->users[$this->uids[$uid1]]['team'] ) {
							$rand_user[] = $this->users[$i]['id'];
						//}
						$i++;
					}
				}elseif( $type == 5 ) {
					//��������� ��������, ����� (����� ������)
					$i = 0;
					while( $i < count( $this->users ) ) {
						if( $this->users[$i]['team'] == $this->users[$this->uids[$uid2]]['team'] && $uid2 != $this->users[$i]['id'] ) {
							$rand_user[] = $this->users[$i]['id'];
						}
						$i++;
					}
				}elseif( $type == 6 ) {
					//��������� �������� �� ������� ����������
					$i = 0;
					while( $i < count( $this->users ) ) {
						if( $this->users[$i]['team'] != $this->users[$this->uids[$uid2]]['team'] ) {
							$rand_user[] = $this->users[$i]['id'];
						}
						$i++;
					}
				}elseif( $type > 100 ) {
					//���� ���� � ����������� ������
					if( !isset($this->users[$this->uids[$type]]) || $this->users[$this->uids[$type]]['id'] != $type ) {
						$r = $uid2;	
					}else{
						$r = $type;
					}
				}
				if( $rand_user != false && count($rand_user) > 0 ) {
					$r = $rand_user[rand(0,( count($rand_user) - 1 ))];
				}
				$this->import_user = $r;
			}else{
				$r = $this->import_user;
			}
			return $r;
		}
		
	//������ ������� �������
		public function mf1Razmen($id,$at,$v,$pat = false,$rjd = 0) {
			
			global $u;
				
			if( $pat == true ) {
				$pat = $at;
				$at = $pat['p'];
			}else{
				unset($pat);
			}
				
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
						
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$uid1 = $this->yhod_user($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod']);
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$uid2 = $this->yhod_user($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod']);
			}
			
			$i = 1;
			while( $i <= 2 ) {				
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				//������ ������� ���� (u2) �� ���������� (u1)
				//print_r( $at[$i] );
				$j = 0; $wp01 = 3; $k01 = 0;
				if( $rjd > 0 ) {
					$j = $rjd-1;
				}
				while($j < count($at[$a]['atack']) && $j < 8) {
					// ���� ��� , ��� �����
					if( $k01 == 0 && isset($this->stats[$this->uids[$u1]]['wp3id']) ) {
						//����� ����
						$wp01 = 3;
						$k01 = 1;
					}else{
						//������ ����
						if( isset($this->stats[$this->uids[$u1]]['wp14id']) && $this->stats[$this->uids[$u1]]['items'][$this->stats[$this->uids[$u1]]['wp14id']]['type'] != 13 ) {
							$wp01 = 14;	
						}else{
							if( isset($this->stats[$this->uids[$u1]]['wp3id']) ) {
								$wp01 = 3;
							}else{
								//��� ������

								$wp01 = 3;
							}
						}
						$k01 = 0;
					}
					$witm01 = 0;
					$witm_type01 = 0;		
					if( $wp01 > 0 ) {
						$witm01 = $this->stats[$this->uids[$u1]]['items'][$this->stats[$this->uids[$u1]]['wp' . $wp01 . 'id']];
						$witm_data01 = $u->lookStats($witm01['data']);
						//$r['wt'] = $witm['type'];
					}
					//
					if( $at[$a]['atack'][$j][2] == $v ) {
						//if( $wp01 > 0 && $witm01['type'] == 20 ) {
						//	$tyv = $this->mfs( 2 , array( 'mf' => $this->stats[$this->uids[$u2]]['m4']*1.25 , 'amf' => $this->stats[$this->uids[$u1]]['m5'] + $witm_data01['sv_m5']  ) );
						//}else{
							$tyv = $this->mfs( 2 , array( 'u1' => $u1 , 'u2' => $u2 , 'mf' => $this->stats[$this->uids[$u2]]['m4'] , 'amf' => ($this->stats[$this->uids[$u1]]['m5'] + $witm_data01['sv_m5']) ) , $this->users[$this->uids[$u1]]['level'] , $this->users[$this->uids[$u2]]['level'] );
						//}
						if( $tyv == 1 && $this->atacks[$id]['out'.$b] == 0 ) {
							//���������, ��� :)
							$this->stats[$this->uids[$u1]]['nopryh'] = floor(0+(int)$this->stats[$this->uids[$u1]]['nopryh']);
							if( !isset($this->stats[$this->uids[$u1]]['nopryh']) || $this->stats[$this->uids[$u1]]['nopryh'] <= 0 ) {
								$at[$a]['atack'][$j][1] = 2;
							}else{
								$this->stats[$this->uids[$u1]]['nopryh']--;
								$this->stats[$this->uids[$u1]]['nopryh_act']++;
							}
						}
					}
					$j++;
				}
					
				$i++;
			}
			unset($witm01,$witm_type01,$wp01,$k01);
			
			if( isset($pat) && $pat != false ) {
				$pat['p'] = $at;
				$at = $pat;
			}
			
			return $at;
		}
		
	//������ ����� �������
		public function mf2Razmen($id,$at,$v,$pat = false,$rjd = 0) {
			
			global $u;
			
			if( $pat == true ) {
				$pat = $at;
				$at = $pat['p'];
			}else{
				unset($pat);
			}
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$uid1 = $this->yhod_user($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod']);
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$uid2 = $this->yhod_user($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod']);
			}
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				//������ ����� ���������� (u1) �� ���� (u2)
				//print_r( $at[$i] );
				$j = 0; $wp01 = 0; $k01 = 0;
				if( $rjd > 0 ) {
					$j = $rjd-1;
				}
				while($j < count($at[$a]['atack']) && $j <= 8) {
					// ���� ��� , ��� �����
					if( $k01 == 0 && isset($this->stats[$this->uids[$u1]]['wp3id']) ) {
						//����� ����
						$wp01 = 3;
						$k01 = 1;
					}else{
						//������ ����
						if( isset($this->stats[$this->uids[$u1]]['wp14id']) && $this->stats[$this->uids[$u1]]['items'][$this->stats[$this->uids[$u1]]['wp14id']]['type'] != 13 ) {
							$wp01 = 14;	
						}else{
							if( isset($this->stats[$this->uids[$u1]]['wp3id']) ) {
								$wp01 = 3;
							}else{
								//��� ������
								$wp01 = 3;
							}
						}
						$k01 = 0;
					}
					$witm01 = 0;
					$witm_type01 = 0;		
					if( $wp01 > 0 ) {
						$witm01 = $this->stats[$this->uids[$u1]]['items'][$this->stats[$this->uids[$u1]]['wp' . $wp01 . 'id']];
						$witm_data01 = $u->lookStats($witm01['data']);
						//$r['wt'] = $witm['type'];
					}
					//
					//if( $at[$a]['atack'][$j][2] == $v ) {					
						if( $this->mfs( 1 , array( 'u1' => $u1 , 'u2' => $u2 , 'mf' => $this->stats[$this->uids[$u1]]['m1'] + $witm_data01['sv_m1'] , 'amf' => $this->stats[$this->uids[$u2]]['m2'] , 'aamf1' => (int)$this->stats[$this->uids[$u2]]['enemy_am1']  )  , $this->users[$this->uids[$u1]]['level'] , $this->users[$this->uids[$u2]]['level'] ) == 1 || ($this->get_chanse($this->stats[$this->uids[$u1]]['m14']) == true && $this->stats[$this->uids[$u1]]['m14'] > 1) ) {
							//��������, ��� :)
							if( $at[$a]['atack'][$j][1] == 3 ) {
								//� ����
								if( rand(0,100000) < 50000 ) {
									$at[$a]['atack'][$j][1] = 4;
								}
							}else{
								//������� ����
								$at[$a]['atack'][$j][1] = 5;
							}
						}
					//}
					$j++;
				}
					
				$i++;
			}
			unset($witm01,$witm_type01,$k01,$wp01);
			
			if( isset($pat) && $pat != false ) {
				$pat['p'] = $at;
				$at = $pat;
			}
			
			return $at;
		}
		
	//������ ����������� �������
		public function mf3Razmen($id,$at,$v,$pat = false,$rjd = 0) {
			
			if( $pat == true ) {
				$pat = $at;
				$at = $pat['p'];
			}else{
				unset($pat);
			}
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$uid1 = $this->yhod_user($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod']);
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$uid2 = $this->yhod_user($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod']);
			}
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				//������ ����������� ���� (u2) �� ���������� (u1)
				//print_r( $at[$i] );
				$j = 0;
				if( $rjd > 0 ) {
					$j = $rjd-1;
				}
				while($j < count($at[$a]['atack']) && $j < 8) {
					// ���� ��� , ��� �����
					if( (!isset($this->stats[$this->uids[$u2]]['no_pr1']) || $this->stats[$this->uids[$u2]]['no_pr1'] == 0) && $at[$a]['atack'][$j][2] == $v ) {
						if( $this->mfs( 3 , array( 'u1' => $u1 , 'u2' => $u2 , '1' => $this->stats[$this->uids[$u2]]['m7'] , '2' => $this->stats[$this->uids[$u1]]['m7']  )  , $this->users[$this->uids[$u1]]['level'] , $this->users[$this->uids[$u2]]['level'] ) == 1 && $this->atacks[$id]['out'.$b] == 0 ) {
							//���������, ��� :)
							$this->stats[$this->uids[$u1]]['nopryh'] = floor(0+(int)$this->stats[$this->uids[$u1]]['nopryh']);
							if( ( !isset($this->stats[$this->uids[$u1]]['nopryh']) || $this->stats[$this->uids[$u1]]['nopryh'] == 0 ) && $this->stats[$this->uids[$u1]]['nopryh_act'] < 1 ) {
								$at[$a]['atack'][$j][1] = 6;
								$this->stats[$this->uids[$u1]]['nopryh']--;
								$this->stats[$this->uids[$u1]]['nopryh_act']++;
							}
						}
					}
					$j++;
				}
					
				$i++;
			}
			
			if( isset($pat) && $pat != false ) {
				$pat['p'] = $at;
				$at = $pat;
			}
			
			return $at;
		}
		
	//������ ����� ����� �������
		public function mf4Razmen($id,$at,$v,$pat = false,$rjd = 0) {
			
			if( $pat == true ) {
				$pat = $at;
				$at = $pat['p'];
			}else{
				unset($pat);
			}
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$uid1 = $this->yhod_user($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod']);
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$uid2 = $this->yhod_user($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod']);
			}
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				if( $this->stats[$this->uids[$u2]]['sheld1'] > 0 ) {
					//������ ����� ����� ���� (u2) �� ���������� (u1)
					//print_r( $at[$i] );
					$j = 0;
					while($j < count($at[$a]['atack']) && $j < 8) {
						// ���� ��� , ��� �����
						if( $at[$a]['atack'][$j][2] == $v ) {
							if( $this->mfs( 5 , ($this->stats[$this->uids[$u2]]['m8']/2)  , $this->users[$this->uids[$u1]]['level'] , $this->users[$this->uids[$u2]]['level'] ) == 1 && $this->atacks[$id]['out'.$b] == 0 ) {
								//���������� �����, ��� :)
								if( !isset($this->stats[$this->uids[$u1]]['nopryh']) || $this->stats[$this->uids[$u1]]['nopryh'] == 0 ) {
									$at[$a]['atack'][$j][1] = 7;
									$this->stats[$this->uids[$u1]]['nopryh']--;
								}
							}
						}
						$j++;
					}
				}					
				$i++;
			}
			
			if( isset($pat) && $pat != false ) {
				$pat['p'] = $at;
				$at = $pat;
			}
			
			return $at;
		}
		
	//������ ���������� �������
		public function mf5Razmen($id,$at,$v,$rjd = 0) {
			global $u;
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$uid1 = $this->yhod_user($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod']);
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$uid2 = $this->yhod_user($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod']);
			}
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				//������ ���������� ���� (u2) �� ���������� (u1)
				//print_r( $at[$i] );
				$j = 0;
				if( $rjd > 0 ) {
					$j = $rjd-1;
				}
				while($j < count($at[$a]['atack']) && $j < 8) {
					// ���� ��� , ��� �����
					if( $at[$a]['atack'][$j][2] == $v ) {
						if( $at[$a]['atack'][$j][1] == 2 ) {
							if( $this->mfs( 6 , array( 'u1' => $u1 , 'u2' => $u2 , 'a' => $this->stats[$this->uids[$u2]]['m6'] , 'b' => $this->stats[$this->uids[$u1]]['m6'] )  , $this->users[$this->uids[$u1]]['level'] , $this->users[$this->uids[$u2]]['level'] ) == 1 ) {
								//���������, ��� :)
								$at[$a]['atack'][$j][1] = 8;
								$rnd_a = rand(1,5);
								$rjd = count($at[$b]['atack']);
								if( $this->testRazmenblock1($id,$u2,$u1,$rnd_a) == false ) {
									//$at[$b]['atack'][] = array( $rnd_a , 1 , 0 , 1 ); // 3 , 0 , 1
									$at[$b]['atack'][] = $at[$b]['atack'][(count($at[$b]['atack'])-1)]; // 3 , 0 , 1
								}else{
									//$at[$b]['atack'][] = array( $rnd_a , 3 , 0 , 1 ); // 3 , 0 , 1
									$at[$b]['atack'][] = $at[$b]['atack'][(count($at[$b]['atack'])-1)]; // 3 , 0 , 1
								}
								$at = $this->contrRestart($id,$at,1,$rjd);
							}
						}
					}
					$j++;
				}
					
				$i++;
			}
			
			return $at;
		}
		
	//�������� (��� �������)
		public function seeRazmen($id,$at) {
			$r = '';
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			$i = 1;
			while($i <= 2) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				if( !isset($at[$a]['atack']) ) {
					$r .= 'u1 ��������� ���� ���';
				}else{
					$j = 0;
					while($j < count($at[$a]['atack']) && $j < 8) {
						if( $at[$a]['atack'][$j][1] == 1 ) {
							//u1 ������ ������� ������ u2
							$r .= 'u1 ������ ������� ������ u2';
						}elseif( $at[$a]['atack'][$j][1] == 2 ) {
							//u2 ��������� �� u1
							$r .= 'u2 ��������� �� u1';
						}elseif( $at[$a]['atack'][$j][1] == 3 ) {
							//u2 ������������ ���� u1
							$r .= 'u2 ������������ ���� u1';
						}elseif( $at[$a]['atack'][$j][1] == 4 ) {
							//u1 ������ ���� u2 ������
							$r .= 'u1 ������ ���� u2 ������';
						}elseif( $at[$a]['atack'][$j][1] == 5 ) {
							//u1 ������ ����������� ������ u2
							$r .= 'u1 ������ ����������� ������ u2';
						}elseif( $at[$a]['atack'][$j][1] == 6 ) {
							//u2 ��������� ���� u1
							$r .= 'u2 ��������� ���� u1';
						}elseif( $at[$a]['atack'][$j][1] == 7 ) {
							//u2 ���������� ����� ���� u1
							$r .= 'u2 ���������� ����� ���� u1';
						}elseif( $at[$a]['atack'][$j][1] == 8 ) {
							//u2 ��������� �� ����� u1 � ����� �� ���� ���������
							$r .= 'u2 ��������� �� ����� u1 � ����� �� ���� ���������';
						}
						if( $at[$a]['atack'][$j][3] == 1 ) {
							$r .= ' (���������)';
						}
						if( isset($at[$a]['atack'][$j]['yron']) ) {
							$r .= ' '.$at[$a]['atack'][$j]['yron']['r'].'';
							if( $at[$a]['atack'][$j]['yron']['w'] == 3 ) {
								$r .= ' (������ ����)';
							}elseif( $at[$a]['atack'][$j]['yron']['w'] == 14 ) {
								$r .= ' (����� ����)';
							}
						}
						if( isset($at[$a]['atack'][$j]['yron']['hp']) ) {
							$r .= ' ['.floor($at[$a]['atack'][$j]['yron']['hp']).'/'.floor($at[$a]['atack'][$j]['yron']['hpAll']).']';
						}
						$r .= ',<br>';
						$j++;
					}
				}
				
				$r = str_replace('u1','<b>'.$this->users[$this->uids[$u1]]['login'].'</b>',$r);
				$r = str_replace('u2','<b>'.$this->users[$this->uids[$u2]]['login'].'</b>',$r);

				$r .= '|<br>';
				$i++;
			}
			
			return $r;
		}
		
	//��������� �� ��� ������
		public function addlt( $a , $id , $s , $rnd ) {
			global $log_text;
			if( $rnd == NULL ) {
				$rnd = rand(0,(count($log_text[$s][$id])-1));
			}
			return '{'.$a.'x'.$id.'x'.$rnd.'}';
		}
		
	//��������� ����������
		public function addNewStat($stat) {
			if( isset($stat[1]) ) {
				mysql_query('INSERT INTO `battle_stat`
				( `battle`,`uid1`,`uid2`,`time`,`type`,`a`,`b`,`ma`,`mb`,`type_a`,`type_b`,`yrn`,`yrn_krit`,`tm1`,`tm2` ) VALUES (
					"'.$this->info['id'].'",
					"'.$stat[1]['uid1'].'",
					"'.$stat[1]['uid2'].'",
					"'.$stat[1]['time'].'",
					"'.$stat[1]['type'].'",
					"'.$stat[1]['a'].'",
					"'.$stat[1]['b'].'",
					"'.$stat[1]['ma'].'",
					"'.$stat[1]['mb'].'",
					"'.$stat[1]['type_a'].'",
					"'.$stat[1]['type_b'].'",
					"'.$stat[1]['yrn'].'",
					"'.$stat[1]['yrn_krit'].'",
					"'.$stat[1]['tm1'].'",
					"'.$stat[1]['tm2'].'"
				)');
			}
			if( isset($stat[2]) ) {
				mysql_query('INSERT INTO `battle_stat`
				( `battle`,`uid1`,`uid2`,`time`,`type`,`a`,`b`,`ma`,`mb`,`type_a`,`type_b`,`yrn`,`yrn_krit`,`tm1`,`tm2` ) VALUES (
					"'.$this->info['id'].'",
					"'.$stat[2]['uid1'].'",
					"'.$stat[2]['uid2'].'",
					"'.$stat[2]['time'].'",
					"'.$stat[2]['type'].'",
					"'.$stat[2]['a'].'",
					"'.$stat[2]['b'].'",
					"'.$stat[1]['ma'].'",
					"'.$stat[2]['mb'].'",
					"'.$stat[2]['type_a'].'",
					"'.$stat[2]['type_b'].'",
					"'.$stat[2]['yrn'].'",
					"'.$stat[2]['yrn_krit'].'",
					"'.$stat[2]['tm1'].'",
					"'.$stat[2]['tm2'].'"
				)');
			}
		}
		
		public $prlog = array();
		
	//��������� ���������� ������
		public function asr($u1,$u2,$type) {
			mysql_query('INSERT INTO `battle_static` (
				`uid1`,`uid2`,`hod`,`type`,`time`,`bid`
			) VALUES (
				"'.$u1.'","'.$u2.'","'.$this->hodID.'","'.$type.'","'.time().'","'.$this->info['id'].'"
			)');
		}
		
	//��������� ������� � ���
		public function addlogRazmen($id,$at) {
			global $u;
			
			$r = '';
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			$this->hodID++;
			
			$dies = array(
				1 => 0,
				2 => 0
			);
			
			//������ ��� ����������
			$stat = array(
				1 => array(
					'uid1' => 0,
					'uid2' => 0,
					'time' => time(),
					'type' => 0,
					'a' => '00000',
					'b' => '0',
					'type_a' => '',
					'type_b' => '0',
					'yrn' => 0,
					'yrn_krit' => 0,
					'ma' => 0,
					'mb' => 0,
					'tm1' => 0,
					'tm2' => 0				
				),
				2 => array(
					'uid1' => 0,
					'uid2' => 0,
					'time' => time(),
					'type' => 0,
					'a' => '00000',
					'b' => '0',
					'type_a' => '',
					'type_b' => '0',
					'yrn' => 0,
					'yrn_krit' => 0,
					'ma' => 0,
					'mb' => 0,
					'tm1' => 0,
					'tm2' => 0
				)
			);
			
			//if( $u->info['admin'] > 0 ) {
				//echo '[a: '.count($at[1]['atack']).'/'.count($at['p'][1]['atack']).', b: '.count($at[2]['atack']).'/'.count($at['p'][2]['atack']).']';
			//}
			
			$i = 1;
			while($i <= 2) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				if( $this->stats[$this->uids[$u1]]['yhod'] > 0 ) {
					//$u1 = $u2;
				}elseif( $this->stats[$this->uids[$u2]]['yhod'] > 0 ) {
					$u2 = $this->yhod_user($u1,$u2,$this->stats[$this->uids[$u2]]['yhod']);
				}
								
				$s1 = $this->users[$this->uids[$u1]]['sex'];
				$s2 = $this->users[$this->uids[$u2]]['sex'];
				
				$stat[$a]['uid1'] = $u1;
				$stat[$a]['uid2'] = $u2;
				$stat[$a]['ma'] = $this->stats[$this->uids[$u1]]['zona'];
				$stat[$a]['mb'] = $this->stats[$this->uids[$u1]]['zonb'];
				$stat[$a]['tm1'] = $this->users[$this->uids[$u1]]['team'];
				$stat[$a]['tm2'] = $this->users[$this->uids[$u2]]['team'];
				$stat[$a]['a'] = $this->atacks[$id]['a'.$a];
				$stat[$a]['b'] = $this->atacks[$id]['b'.$a];
				
				$vLog = 'at1=00000||at2=00000||zb1='.$this->stats[$this->uids[$u1]]['zonb'].'||zb2='.$this->stats[$this->uids[$u2]]['zonb'].'||bl1='.$this->atacks[$id]['b'.$a].'||bl2='.$this->atacks[$id]['b'.$b].'||time1='.$this->atacks[$id]['time'].'||time2='.$this->atacks[$id]['time2'].'||s'.$a.'='.$s1.'||s'.$b.'='.$s2.'||t2='.$this->users[$this->uids[$u2]]['team'].'||t1='.$this->users[$this->uids[$u1]]['team'].'||login1='.$this->users[$this->uids[$u1]]['login2'].'||login2='.$this->users[$this->uids[$u2]]['login2'].'';
				
				$mas = array(
					'text' => '',
					'time' => time(),
					'vars' => '',
					'battle' => $this->info['id'],
					'id_hod' => $this->hodID,
					'vars' => $vLog,
					'type' => 1
				);
				
				if( !isset($at[$a]['atack']) ) {
					if( $this->atacks[$id]['tpo'.$a] == 2 ) {
						$mas['text'] .= '{u1} �������� ���� ��� �� �����.';
					}else{
						//
						if( $this->info['razdel'] == 0 && $this->info['dn_id'] == 0 && $this->info['izlom'] == 0 ) {
							mysql_query('INSERT INTO `battle_out` (`battle`,`uid1`,`uid2`,`time`,`out`) VALUES (
								"'.$this->info['id'].'","'.$u1.'","'.$u2.'","'.time().'","1"
							)');
						}
						//
						$mas['text'] .= '{u1} ��������� ���� ���.';
					}
					$mas['text'] = '{tm1} ' . $mas['text'];
					$this->add_log($mas);
				}else{
					$j = 0;
					while($j < count($at[$a]['atack']) && $j < 8) {	
						//
						$mas['text'] = '';
						//
						$wt = array(
							21 => 4,
							22 => 5,
							20 => 2,
							28 => 2,
							19 => 3,
							18 => 1,
							26 => 22
						);
						$par = array(
							'zona' => '{zn2_'.$at[$a]['atack'][$j][0].'} ',
							'kyda' => $this->lg_zon[$at[$a]['atack'][$j][0]][rand(0,(count($this->lg_zon[$at[$a]['atack'][$j][0]])-1))],
							'chem' => $this->lg_itm[$wt[$at[$a]['atack'][$j]['wt']]][rand(0,(count($this->lg_itm[$wt[$at[$a]['atack'][$j]['wt']]])-1))]
						);
						//
						$this->atacks[$id]['uid_'.$u1.'_t'.$at[$a]['atack'][$j][1]]++;
						//
						//
						$this->asr( $u1 , $u2 , $at[$a]['atack'][$j][1] );
						//
						if( $at[$a]['atack'][$j][1] == 1 ) {
							//u1 ������ ������� ������ u2
							$mas['text'] .= $par['zona'] . '{u2} ' . $this->addlt($b,1,$s2,NULL) . '' . $this->addlt($b,2,$s2,NULL) . '' . $this->addlt($a,3,$s1,NULL) . ' {u1} ' . $this->addlt($a,4,$s1,NULL) . '' . $this->addlt($a,5,$s1,NULL) . '' . $this->addlt($a,6,$s1,NULL) . ' ' . $this->addlt(1,7,$s1,$at[$a]['atack'][$j]['yron']['t']) . ' '.$par['chem'].' '.$par['kyda'].'. ';
						}elseif( $at[$a]['atack'][$j][1] == 2 ) {
							//u2 ��������� �� u1
							$mas['text'] .= $par['zona'] . '{u1} ' . $this->addlt($a,8,$s1,NULL) . '' . $this->addlt($a,9,$s1,NULL) . ' {u2} <font color=#0071a3><b>' . $this->addlt($b,11,$s2,NULL) . '</b></font> '.$par['chem'].' '.$par['kyda'].'. ';
						}elseif( $at[$a]['atack'][$j][1] == 3 ) {
							//u2 ������������ ���� u1
							$mas['text'] .= $par['zona'] . '{u1} ' . $this->addlt($a,8,$s1,NULL) . '' . $this->addlt($a,9,$s1,NULL) . ' {u2} <font color=#356d37><b>' . $this->addlt($b,10,$s2,NULL) . '</b></font> ' . $this->addlt(1,7,0,$s1,$at[$a]['atack'][$j]['yron']['t']) . ' '.$par['chem'].' '.$par['kyda'].'. ';
						}elseif( $at[$a]['atack'][$j][1] == 4 ) {
							//u1 ������ ���� u2 ������
							$mas['text'] .= $par['zona'] . '{u2} ' . $this->addlt($b,1,$s2,NULL) . '' . $this->addlt($b,2,$s2,NULL) . '' . $this->addlt($a,3,$s1,NULL) . ' {u1} ' . $this->addlt($a,4,$s1,NULL) . '' . $this->addlt($a,5,$s1,NULL) . ', <u><font color=red>������ ����</font></u>, ' . $this->addlt($a,6,$s1,NULL) . ' ' . $this->addlt(1,7,$s1,$at[$a]['atack'][$j]['yron']['t']) . ' '.$par['chem'].' '.$par['kyda'].'. ';
						}elseif( $at[$a]['atack'][$j][1] == 5 ) {
							//u1 ������ ����������� ������ u2
							$mas['text'] .= $par['zona'] . '{u2} ' . $this->addlt($b,1,$s2,NULL) . '' . $this->addlt($b,2,$s2,NULL) . '' . $this->addlt($a,3,$s1,NULL) . ' {u1} ' . $this->addlt($a,4,$s1,NULL) . '' . $this->addlt($a,5,$s1,NULL) . '' . $this->addlt($a,6,$s1,NULL) . ' ' . $this->addlt(1,7,$s1,$at[$a]['atack'][$j]['yron']['t']) . ' '.$par['chem'].' '.$par['kyda'].'. ';
						}elseif( $at[$a]['atack'][$j][1] == 6 ) {
							//u2 ��������� ���� u1
							$mas['text'] .= $par['zona'] . '{u1} ' . $this->addlt($a,8,$s1,NULL) . '' . $this->addlt($a,9,$s1,NULL) . ' {u2} ���������� <b><font color=#c59400>���������</font></b> ' . $this->addlt(1,7,0,$s1,$at[$a]['atack'][$j]['yron']['t']) . ' '.$par['chem'].' '.$par['kyda'].'. ';
						}elseif( $at[$a]['atack'][$j][1] == 7 ) {
							//u2 ���������� ����� ���� u1
							$mas['text'] .= $par['zona'] . '{u1} ' . $this->addlt($a,8,$s1,NULL) . '' . $this->addlt($a,9,$s1,NULL) . ' {u2}, ���������������� <u><font color=#356d37>����� �����</font></u>, ' . $this->addlt($b,10,$s2,NULL) . ' ' . $this->addlt(1,7,0,$s1,$at[$a]['atack'][$j]['yron']['t']) . ' '.$par['chem'].' '.$par['kyda'].'. ';
						}elseif( $at[$a]['atack'][$j][1] == 8 ) {
							//u2 ��������� �� ����� u1 � ����� �� ���� ���������
							$mas['text'] .= $par['zona'] . '{u1} ' . $this->addlt($a,8,$s1,NULL) . '' . $this->addlt($a,9,$s1,NULL) . ' {u2} <font color=#0071a3><b>' . $this->addlt($b,11,$s2,NULL) . '</b></font> '.$par['chem'].' '.$par['kyda'].' � ����� ���������. ';
						}
						
						$stat[$a]['type_a'] .= ''.$at[$a]['atack'][$j][1].'';
						if( (!isset($this->stats[$this->uids[$u2]]['notravma']) || $this->stats[$this->uids[$u2]]['notravma'] == 0 )  && isset($at[$a]['atack'][$j]['yron']['travma']) && $at[$a]['atack'][$j]['yron']['travma'][0] > 0 && floor($at[$a]['atack'][$j]['yron']['hp']) <= 0 ) {
							$tr_pl = mysql_fetch_array(mysql_query('SELECT `id`,`v1` FROM `eff_users` WHERE `id_eff` = 4 AND `uid` = "'.$u2.'" AND `delete` = "0" ORDER BY `v1` DESC LIMIT 1'));
							if( !isset($tr_pl['id']) || $tr_pl['v1'] < 3 ) {
								//263
								if( isset($tr_pl['id']) ) {
									$at[$a]['atack'][$j]['yron']['travma'][0] = rand(($tr_pl['v1']+1),3);
								}
								$tr_pl2 = mysql_fetch_array(mysql_query('SELECT `id` FROM `eff_users` WHERE `uid` = "'.$u2.'" AND `delete` = "0" AND `name` LIKE "%������ �� �����%" LIMIT 1'));
								if( !isset($tr_pl2['id']) && $at[$a]['atack'][$j]['yron']['travma'][0] <= 3 ) {
									$mas['text'] = rtrim($mas['text'],'. ');
									$mas['text'] .= ', <font color=red>������ ���������� <b>';							
									if( $at[$a]['atack'][$j]['yron']['travma'][0] == 1 ) {
										$mas['text'] .= '������';
										$this->addTravm($u2,1,rand(3,5));
									}elseif( $at[$a]['atack'][$j]['yron']['travma'][0] == 2 ) {
										$mas['text'] .= '�������';
										$this->addTravm($u2,2,rand(3,5));
									}elseif( $at[$a]['atack'][$j]['yron']['travma'][0] == 3 ) {
										$mas['text'] .= '�������';
										$this->addTravm($u2,3,rand(3,5));
									}
									$mas['text'] .= ' ������</b></font>. ';	
								}
							}
							unset($tr_pl);
						}
						if( $at[$a]['atack'][$j]['yron']['pb'] == 1 && isset($at[$a]['atack'][$j]['yron']['hp']) ) {
							$mas['text'] = rtrim($mas['text'],'. ');
							$mas['text'] .= ' <i>������ �����</i>. ';
						}
						if( $at[$a]['atack'][$j][3] == 1 ) {
							$mas['text'] .= '(���������) ';
						}
						if( isset($at[$a]['atack'][$j]['yron']) ) {
							if( $at[$a]['atack'][$j]['yron']['w'] == 3 ) {
								$mas['textWP'] = '(������&nbsp;����)';
							}elseif( $at[$a]['atack'][$j]['yron']['w'] == 14 ) {
								$mas['textWP'] = '(�����&nbsp;����)';
							}else{
								$mas['textWP'] = '(���������&nbsp;���)';
							}
							if( $at[$a]['atack'][$j][1] == 4 || $at[$a]['atack'][$j][1] == 5 || $at[$a]['atack'][$j][1] == 1 ) {
								if( $at[$a]['atack'][$j]['yron']['y'] < 1 ) {
									$at[$a]['atack'][$j]['yron']['r'] = '--';
								}
							}
							if( $at[$a]['atack'][$j][1] == 4 || $at[$a]['atack'][$j][1] == 5 ) {
								$stat[$a]['yrn_krit'] += -$at[$a]['atack'][$j]['yron']['r'];
								$mas['text'] .= ' <font title='.$mas['textWP'].' color=#ff0000><b>'.$at[$a]['atack'][$j]['yron']['r'].'</b></font>';
							}else{
								$mas['text'] .= ' <font title='.$mas['textWP'].' color=#0066aa><b>'.$at[$a]['atack'][$j]['yron']['r'].'</b></font>';
							}
							$stat[$a]['yrn'] += -$at[$a]['atack'][$j]['yron']['r'];
						}
						if( isset($at[$a]['atack'][$j]['yron']['hp']) ) { 
							if($this->users[$this->uids[$u2]]['align']==9){
								$at[$a]['atack'][$j]['yron']['hp'] = $at[$a]['atack'][$j]['yron']['hp']/($at[$a]['atack'][$j]['yron']['hpAll']/100);
								$at[$a]['atack'][$j]['yron']['hpAll'] = '100%';
							}
							$mas['text'] .= ' ['.floor($at[$a]['atack'][$j]['yron']['hp']).'/'.floor($at[$a]['atack'][$j]['yron']['hpAll']).']';
						}
						//
						if( $mas['text'] != '' ) {
							$mas['text'] = '{tm1} ' . $mas['text'];
						}
						/*
						'.$mass['time'].'",
						"'.$mass['battle'].'",
						"'.$mass['id_hod'].'",
						"'.$mass['text'].'",
						"'.$mass['vars'].'",
						"'.$mass['zona1'].'",
						"'.$mass['zonb1'].'",
						"'.$mass['zona2'].'",
						"'.$mass['zonb2'].'",
						"'.$mass['type'].'
						*/
						//
						if( count($at[$a]['atack'][$j]['yron']['plog']) > 0 ) {
							$il = 0;
							while( $il <= count($at[$a]['atack'][$j]['yron']['plog']) ) {
								if( isset($at[$a]['atack'][$j]['yron']['plog'][$il]) ) {
									eval($at[$a]['atack'][$j]['yron']['plog'][$il]);
								}
								$il++;
							}
						}
						$this->add_log($mas);
						$j++;
					}
				}
				$i++;
			}
			
			//��������� ���������� + ���������� � �����_����� �� �������
			$this->addNewStat( $stat );
			
			//����� � ��� ������ ���������
			if( floor($this->stats[$this->uids[$u1]]['hpNow']) < 1 ) {
				$dies[1] = 1;
			}
			if( floor($this->stats[$this->uids[$u2]]['hpNow']) < 1 ) {
				$dies[2] = 1;
			}
			if( $dies[1] > 0 || $dies[2] > 0 ) {
				$s1 = $this->users[$this->uids[$u1]]['sex'];
				$s2 = $this->users[$this->uids[$u2]]['sex'];
				
				$vLog = 'at1=00000||at2=00000||zb1='.$this->stats[$this->uids[$u1]]['zonb'].'||zb2='.$this->stats[$this->uids[$u2]]['zonb'].'||bl1='.$this->atacks[$id]['b'.$a].'||bl2='.$this->atacks[$id]['b'.$b].'||time1='.$this->atacks[$id]['time'].'||time2='.$this->atacks[$id]['time2'].'||s2='.$this->users[$this->uids[$u2]]['sex'].'||s1='.$this->users[$this->uids[$u1]]['sex'].'||t2='.$this->users[$this->uids[$u2]]['team'].'||t1='.$this->users[$this->uids[$u1]]['team'].'||login1='.$this->users[$this->uids[$u1]]['login2'].'||login2='.$this->users[$this->uids[$u2]]['login2'].'';
				
				$mas = array(
					'text' => '',
					'time' => time(),
					'vars' => '',
					'battle' => $this->info['id'],
					'id_hod' => $this->hodID,
					'vars' => $vLog,
					'type' => 1
				);
	
				$rtngwin = array( 1 , 2 , 3 , 5 , 10 , 20 , 40 , 80 , 120 , 160 , 200 , 240 );
				$rtnglos = array( 0 , 0 , 0 , -1 , -2 , -5 , -10 , -20 , -40 , -60 , -80 , -100 );
				
				if( $dies[1] == 1 ) {
					if( $this->info['dn_id'] > 0 ) {
						//�� ������ ���������	
					}else{
						$rtng1 += $rtnglos[$this->users[$this->uids[$u1]]['level']];
						$rtng2 += $rtngwin[$this->users[$this->uids[$u1]]['level']];
					}
					//�������� 1 ����� �� ��� �������� 2
					mysql_query('DELETE FROM `battle_act` WHERE `uid1` = "'.$u1.'" OR `uid2` = "'.$u1.'"');
					if( $this->stats[$this->uids[$u1]]['spasenie'] > 0 ) {
						//������ ��������
						mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$u1.'" AND `id_eff` = 22 AND `v1` = "priem" AND `v2` = 324');
						//
						$this->stats[$this->uids[$u1]]['hpNow'] = floor($this->stats[$this->uids[$u1]]['hpAll']/50*($this->stats[$this->uids[$u1]]['s7']));
						if( $this->stats[$this->uids[$u1]]['hpNow'] < 1 ) {
							$this->stats[$this->uids[$u1]]['hpNow'] = 1;
						}
						if( $this->stats[$this->uids[$u1]]['hpNow'] > $this->stats[$this->uids[$u1]]['hpAll'] ) {
							$this->stats[$this->uids[$u1]]['hpNow'] = floor($this->stats[$this->uids[$u1]]['hpAll']);
						}
						//
						$this->stats[$this->uids[$u1]]['mpNow'] = floor($this->stats[$this->uids[$u1]]['mpAll']/50*($this->stats[$this->uids[$u1]]['s7']));
						if( $this->stats[$this->uids[$u1]]['mpNow'] < 1 ) {
							$this->stats[$this->uids[$u1]]['mpNow'] = 1;
						}
						if( $this->stats[$this->uids[$u1]]['mpNow'] > $this->stats[$this->uids[$u1]]['mpAll'] ) {
							$this->stats[$this->uids[$u1]]['mpNow'] = floor($this->stats[$this->uids[$u1]]['mpAll']);
						}
						//
						mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$this->uids[$u1]]['hpNow'].'",`mpNow` = "'.$this->stats[$this->uids[$u1]]['mpNow'].'" WHERE `id` = "'.$u1.'" LIMIT 1');
						//
						if( $this->stats[$this->uids[$u1]]['s7'] > 24 ) {
							//���� ��������� 
							mysql_query("INSERT INTO `eff_users` 
							(`id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
							(22, '".$u1."', '���������� ������', '', 0, 77, 0, '".$u1."', 0, 'priem', 141, 'spirit_block25.gif', 1, 1, '0', 0, 0, '', 0, 0, 0, 0, 0);");
						}
						if( $this->stats[$this->uids[$u1]]['s7'] > 99 ) {
							//������ ������� ��� �� ���������� �������� ����������, ���������, �������� � ���� � ������� ���
							
						}
						$mas['text'] = '{tm1} <b>'.$this->stats[$this->uids[$u1]]['login'].'</b> ����...<b>'.$this->stats[$this->uids[$u1]]['login'].'</b> ��� ������. ';
						$this->add_log($mas);
						//
					}else{
						mysql_query('UPDATE `stats` SET `hpNow` = "0",`mpNow` = "0" WHERE `id` = "'.$u1.'" LIMIT 1');
						$mas['text'] = '{tm1} <b>'.$this->stats[$this->uids[$u1]]['login'].'</b> �����.';
						$this->add_log($mas);
					}
				}
				if( $dies[2] == 1 ) {
					if( $this->info['dn_id'] > 0 ) {
						//�� ������ ���������	
					}else{
						$rtng1 += $rtngwin[$this->users[$this->uids[$u2]]['level']];
						$rtng2 += $rtnglos[$this->users[$this->uids[$u2]]['level']];
					}
					//�������� 2 ����� �� ��� �������� 1
					mysql_query('DELETE FROM `battle_act` WHERE `uid1` = "'.$u2.'" OR `uid2` = "'.$u2.'"');
					if( $this->stats[$this->uids[$u2]]['spasenie'] > 0 ) {
						//������ ��������
						mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$u2.'" AND `id_eff` = 22 AND `v1` = "priem" AND `v2` = 324');
						//
						$this->stats[$this->uids[$u2]]['hpNow'] = floor($this->stats[$this->uids[$u2]]['hpAll']/50*($this->stats[$this->uids[$u2]]['s7']));
						if( $this->stats[$this->uids[$u2]]['hpNow'] < 1 ) {
							$this->stats[$this->uids[$u2]]['hpNow'] = 1;
						}
						if( $this->stats[$this->uids[$u2]]['hpNow'] > $this->stats[$this->uids[$u2]]['hpAll'] ) {
							$this->stats[$this->uids[$u2]]['hpNow'] = floor($this->stats[$this->uids[$u2]]['hpAll']);
						}
						//
						$this->stats[$this->uids[$u2]]['mpNow'] = floor($this->stats[$this->uids[$u2]]['mpAll']/50*($this->stats[$this->uids[$u2]]['s7']));
						if( $this->stats[$this->uids[$u2]]['mpNow'] < 1 ) {
							$this->stats[$this->uids[$u2]]['mpNow'] = 1;
						}
						if( $this->stats[$this->uids[$u2]]['mpNow'] > $this->stats[$this->uids[$u2]]['mpAll'] ) {
							$this->stats[$this->uids[$u2]]['mpNow'] = floor($this->stats[$this->uids[$u2]]['mpAll']);
						}
						//
						mysql_query('UPDATE `stats` SET `hpNow` = "'.$this->stats[$this->uids[$u2]]['hpNow'].'",`mpNow` = "'.$this->stats[$this->uids[$u2]]['mpNow'].'" WHERE `id` = "'.$u2.'" LIMIT 1');
						//
						if( $this->stats[$this->uids[$u2]]['s7'] > 24 ) {
							//���� ��������� 
							mysql_query("INSERT INTO `eff_users` 
							(`id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
							(22, '".$u2."', '���������� ������', '', 0, 77, 0, '".$u2."', 0, 'priem', 141, 'spirit_block25.gif', 1, 1, '0', 0, 0, '', 0, 0, 0, 0, 0);");
						}
						if( $this->stats[$this->uids[$u2]]['s7'] > 99 ) {
							//������ ������� ��� �� ���������� �������� ����������, ���������, �������� � ���� � ������� ���
							
						}
						$mas['text'] = '{tm1} <b>'.$this->stats[$this->uids[$u2]]['login'].'</b> ����...<b>'.$this->stats[$this->uids[$u2]]['login'].'</b> ��� ������. ';
						$this->add_log($mas);
						//
					}else{
						mysql_query('UPDATE `stats` SET `hpNow` = "0",`mpNow` = "0" WHERE `id` = "'.$u2.'" LIMIT 1');
						$mas['text'] = '{tm1} <b>'.$this->stats[$this->uids[$u2]]['login'].'</b> �����.';
						$this->add_log($mas);
					}					
				}
				//���������� �������
				if( $this->info['typeBattle'] == 99 ) {
					$rtng1 = $rtng1*2;
					$rtng2 = $rtng2*2;
					if( $this->info['razdel'] == 5 ) {
						if( $rtng1 < 0 ) {
							$rtng1 = 0;
						}
						if( $rtng2 < 0 ) {
							$rtng2 = 0;
						}
					}
				}
				//
				if( $this->info['type'] != 564 && $this->stats[$this->uids[$u1]]['inTurnir'] == 0 && $this->stats[$this->uids[$u2]]['inTurnir'] == 0 && $this->info['dn_id'] == 0 && $this->info['izlom'] == 0 ) {
					//
					if( $this->users[$this->uids[$u2]]['bot'] == 0 ) {
						$rtng = mysql_fetch_array(mysql_query('SELECT * FROM `users_rating` WHERE `uid` = "'.$u1.'" LIMIT 1'));
						if(!isset($rtng['id'])) {
							mysql_query('INSERT INTO `users_rating` (`uid`,`rating`,`time`) VALUES (
								"'.$u1.'","'.$rtng1.'","'.time().'"
							)');
						}else{
							$rtng['rating'] += $rtng1;
							mysql_query('UPDATE `users_rating` SET `rating` = "'.$rtng['rating'].'",`time` = "'.time().'" WHERE `uid` = "'.$u1.'" LIMIT 1');
						}
					}
					unset($rtng);
					if( $this->users[$this->uids[$u1]]['bot'] == 0 ) {
						$rtng = mysql_fetch_array(mysql_query('SELECT * FROM `users_rating` WHERE `uid` = "'.$u2.'" LIMIT 1'));
						if(!isset($rtng['id'])) {
							mysql_query('INSERT INTO `users_rating` (`uid`,`rating`,`time`) VALUES (
								"'.$u2.'","'.$rtng2.'","'.time().'"
							)');
						}else{
							$rtng['rating'] += $rtng2;
							mysql_query('UPDATE `users_rating` SET `rating` = "'.$rtng['rating'].'",`time` = "'.time().'" WHERE `uid` = "'.$u2.'" LIMIT 1');
						}
					}
				}
				unset($rtng1,$rtng2);
			}
			
			return true;
		}
		
	//��������� � ��� �������� ������
		public function priemAddLog($id,$a,$b,$u1,$u2,$prm,$text,$hodID,$tm1 = 0, $tm2 = 0) {
			if( $tm1 == 0 ) {
				if(isset($this->atacks[$id])) {
					$tm1 = $this->atacks[$id]['time'];
				}else{
					$tm1 = time();
				}
			}
			if( $tm2 == 0 ) {
				if(isset($this->atacks[$id])) {
					$tm2 = $this->atacks[$id]['time2'];
				}else{
					$tm2 = time();
				}
			}
			$vLog = 'prm='.$prm.'||at1=00000||at2=00000||zb1='.$this->stats[$this->uids[$u1]]['zonb'].'||zb2='.$this->stats[$this->uids[$u2]]['zonb'].'||bl1='.$this->atacks[$id]['b'.$a].'||bl2='.$this->atacks[$id]['b'.$b].'||time1='.$tm1.'||time2='.$tm2.'||s'.$a.'='.$this->users[$this->uids[$u1]]['sex'].'||s'.$b.'='.$this->users[$this->uids[$u2]]['sex'].'||t2='.$this->users[$this->uids[$u2]]['team'].'||t1='.$this->users[$this->uids[$u1]]['team'].'||login1='.$this->users[$this->uids[$u1]]['login2'].'||login2='.$this->users[$this->uids[$u2]]['login2'].'';	
			$mas = array(
				'text' => $text,
				'time' => time(),
				'vars' => '',
				'battle' => $this->info['id'],
				'id_hod' => $hodID,
				'vars' => $vLog,
				'type' => 1
			);
			$this->add_log($mas);	
		}
		
	//��������� � ��� �������� ������ (��� �����)
		public function priemAddLogFast($u1,$u2,$prm,$text,$hodID,$tm) {
			$vLog = 'prm='.$prm.'||time1='.$tm.'||time2='.$tm.'||s1='.$this->users[$this->uids[$u1]]['sex'].'||s2='.$this->users[$this->uids[$u2]]['sex'].'||t2='.$this->users[$this->uids[$u2]]['team'].'||t1='.$this->users[$this->uids[$u1]]['team'].'||login1='.$this->users[$this->uids[$u1]]['login2'].'||login2='.$this->users[$this->uids[$u2]]['login2'].'';	
			$mas = array(
				'text' => $text,
				'time' => time(),
				'vars' => '',
				'battle' => $this->info['id'],
				'id_hod' => ($this->hodID + $hodID),
				'vars' => $vLog,
				'type' => 1
			);
			$this->add_log($mas);	
		}
	
	//������� ������
		public function contrRestart($id,$at,$v,$rjd = 0) {
			//
			/*$at['p'] = $at;
			//����
			$at = $this->mf2Razmen($id,$at,$v,true);
			//���� ����� (���� ���� ���, �������)
			$at = $this->mf4Razmen($id,$at,$v,true);
			//������
			$at = $this->mf1Razmen($id,$at,$v,true);
			//�����������
			$at = $this->mf3Razmen($id,$at,$v,true);
			//���������
			//$at = $this->mf5Razmen($id,$at,$v);
			//��������� ����
			$at = $this->yronRazmen($id,$at,true);
			//$at = $this->yronRazmen($id,$at);
			
			$at = $at['p'];*/
			
			//����
			$at = $this->mf2Razmen($id,$at,$v,true,$rjd);
			//���� ����� (���� ���� ���, �������)
			$at = $this->mf4Razmen($id,$at,$v,true,$rjd);
			//������
			$at = $this->mf1Razmen($id,$at,$v,true,$rjd);
			//�����������
			$at = $this->mf3Razmen($id,$at,$v,true,$rjd);
			//���������
			//$at = $this->mf5Razmen($id,$at,$v,true);
			//��������� ����
			//$at = $this->yronRazmen($id,$at);
			$at = $this->yronRazmen($id,$at,false,$rjd);
						
			return $at;
		}
		
	//������� ������
	/*	public function contrRestart($id,$at,$v) {
			//
			$at['p'] = $at;
			//����
			//$at = $this->mf2Razmen($id,$at,$v,true);
			//���� ����� (���� ���� ���, �������)
			$at = $this->mf4Razmen($id,$at,$v,true);
			//������
			$at = $this->mf1Razmen($id,$at,$v,true);
			//�����������
			$at = $this->mf3Razmen($id,$at,$v,true);
			//���������
			//$at = $this->mf5Razmen($id,$at,$v);
			//��������� ����
			//$at = $this->yronRazmen($id,$at,true);
			$at = $this->yronRazmen($id,$at);
			
			$at = $at['p'];
						
			return $at;
		}
	*/
		
	//����������� ����� ��� ���������� ���� �����
		public function yronGetrazmenStats( $s , $z ) {
			global $u;
			/*
			1     - ����
			2     - ������
			3     - ������ (������ ����)
			4     - ������
			5     - �����
			6     - ����
			7     - ����
			8     - ������
			9     - ������
			10-12 - ������
			13    - ��������
			14    - ������ / ��� (����� ����)
			16    - ������
			17    - �������
			*/
			$zi = array( //�������� �������� �� ����
				1 => array( 1 , 8 , 9 , 52 ), //������
				2 => array( 4 , 5 , 6 ), //�����
				3 => array( 2 , 4 , 5 , 6 , 13 ), //�����
				4 => array( 7 , 16 , 10 , 11 , 12 ), //����
				5 => array( 17 )  //����
			);
			//
			$zi = $zi[$z];
			$i = 0;
			//
			while( $i < count($zi) ) {
				//
				$t = $u->items['add'];
				$ii = 0;
				//
				while( $ii < count($s['items']) ) {
					if( isset($s['items'][$ii]) && $s['items'][$ii]['inOdet'] == $zi[$i] ) {
						$po = $u->lookStats($s['items'][$ii]['data']);
						//
						$x = 0;
						while( $x < count($t) ) {
							$n = $t[$x];
							if(isset($po['sv_'.$n])) {
								$s[$n] += $po['sv_'.$n];
								if( $n == 'za' ) {
									$iii = 1;
									while($iii <= 4) {
										$s['za'.$iii] += $po['sv_'.$n];
										$iii++;
									}
								}elseif( $n == 'zm' ) {
									$iii = 1;
									while($iii <= 4) {
										$s['zm'.$iii] += $po['sv_'.$n];
										$iii++;
									}
								}elseif( $n == 'zma' ) {
									$iii = 1;
									while($iii <= 7) {
										$s['zma'.$iii] += $po['sv_'.$n];
										$iii++;
									}
								}
							}
							$x++;	
						}
						//
					}
					$ii++;
				}
				//
				$i++;
			}
			//
			return $s;
		}
		
	//����������� ��. �����
		public function yronGetrazmen( $uid1,$uid2,$wp,$zona ) {
			global $u;
			//$oldst1 = $this->stats[$this->uids[$uid1]];
			//$oldst2 = $this->stats[$this->uids[$uid2]];	
			//�������� ����� ������ 1 � 2 ��� ���������� ����
			//$this->stats[$this->uids[$uid1]] = $this->yronGetrazmenStats( $this->stats[$this->uids[$uid1]] , $zona );
			//$this->stats[$this->uids[$uid2]] = $this->yronGetrazmenStats( $this->stats[$this->uids[$uid2]] , $zona );
			//$this->a_save_stats($uid1);
			//$this->a_save_stats($uid2);
			//
			//$this->a_testing_stats($uid1,$zona);
			$this->a_testing_stats($uid2,$zona);
			//
			$r = array(
				'y' => 0,
				'r' => '--'
			);			
			//���������� ��� �����
			/*
				�������
				�������
				�������
				��������
			*/
			$witm = 0;
			$witm_type = 0;

			if( $wp > 0 ) {
				$witm = $this->stats[$this->uids[$uid1]]['items'][$this->stats[$this->uids[$uid1]]['wp' . $wp . 'id']];
				$witm_data = $u->lookStats($witm['data']);
				$witm_type = $this->weaponTx($witm);
				//$r['wt'] = $witm['type'];
			}
			if( $witm_type == 0 || $witm_type == 12 ) {
				$witm_type2 = '';
			}else{
				$witm_type2 = $witm_type;
			}
			$r['t'] = $witm_type2;
			//������ �����
			/*
				������
				�����
				�����
				����
				����
			*/
			$bron = array(
				1 => array( $this->stats[$this->uids[$uid2]]['mib1'] , $this->stats[$this->uids[$uid2]]['mab1'] ),
				2 => array( $this->stats[$this->uids[$uid2]]['mib2'] , $this->stats[$this->uids[$uid2]]['mab2'] ),
				3 => array( $this->stats[$this->uids[$uid2]]['mib2'] , $this->stats[$this->uids[$uid2]]['mab2'] ),
				4 => array( $this->stats[$this->uids[$uid2]]['mib3'] , $this->stats[$this->uids[$uid2]]['mab3'] ),
				5 => array( $this->stats[$this->uids[$uid2]]['mib4'] , $this->stats[$this->uids[$uid2]]['mab4'] )
			);
			//
			//����������� ��������� �� �������� ������ ������� ����
			//$witm_data 
			$iii = 1;
			while($iii <= 7) {
				if(isset($witm_data['sv_a'.$iii]) && $witm_data['sv_a'.$iii] != 0) {
					$this->stats[$this->uids[$uid1]]['a'.$iii] += $witm_data['sv_a'.$iii];
				}
				if(isset($witm_data['sv_mg'.$iii]) && $witm_data['sv_mg'.$iii] != 0) {
					$this->stats[$this->uids[$uid1]]['mg'.$iii] += $witm_data['sv_mg'.$iii];
				}
				$iii++;
			}
			$iii = 1;
			while($iii <= 4) {
				if(isset($witm_data['sv_aall']) && $witm_data['sv_aall'] != 0) {
					$this->stats[$this->uids[$uid1]]['a'.$iii] += $witm_data['sv_aall'];
				}
				$iii++;
			}
			$iii = 1;
			while($iii <= 4) {
				if(isset($witm_data['sv_mall']) && $witm_data['sv_mall'] != 0) {
					$this->stats[$this->uids[$uid1]]['mg'.$iii] += $witm_data['sv_mall'];
				}
				$iii++;
			}
			$iii = 1;
			while($iii <= 7) {
				if(isset($witm_data['sv_m2all']) && $witm_data['sv_m2all'] != 0) {
					$this->stats[$this->uids[$uid1]]['mg'.$iii] += $witm_data['sv_m2all'];
				}
				$iii++;
			}
			
			if(isset($witm_data['sv_m3']) && $witm_data['sv_m3'] != 0) {
				$this->stats[$this->uids[$uid1]]['m3'] += $witm_data['sv_m3'];
			}
			
			$iii = 1;
			while($iii <= 7) {
				if(isset($witm_data['sv_pa'.$iii]) && $witm_data['sv_pa'.$iii] != 0) {
					$this->stats[$this->uids[$uid1]]['pa'.$iii] += $witm_data['sv_pa'.$iii]+$witm_data['sv_m10'];
				}
				if(isset($witm_data['sv_pm'.$iii]) && $witm_data['sv_pm'.$iii] != 0) {
					$this->stats[$this->uids[$uid1]]['pm'.$iii] += $witm_data['sv_pm'.$iii]+$witm_data['sv_m11a'];
					if( $iii < 5 ) {
						$this->stats[$this->uids[$uid1]]['pm'.$iii] += $witm_data['sv_m11'];
					}
				}
				$iii++;
			}
			//
			//�������� + ���������� �������� �����������
			$wAp = 0;
			$w3p  = 0;
			$w14p = 0;
/*
			������ �������, �� ���� �������� �������� ��� �������� ����� ����������� ������ �������� �����
			if($witm_type==12) {
				//���� �������
				$wAp += $this->stats[$this->uids[$uid1]]['m10'];
				if($this->users[$this->uids[$uid1]]['align']==7) {
					$wAp += 15;
				}
			}elseif($witm_type < 5) {
				$wAp += $this->stats[$this->uids[$uid1]]['pa'.$witm_type.''] + $this->stats[$this->uids[$uid1]]['m10'] + $witm_data['sv_pa'.$witm_type.''];
				$wAp -= $this->stats[$this->uids[$uid2]]['antpa'.$witm_type.''];
			}else{
				$wAp += $this->stats[$this->uids[$uid1]]['pm'.($witm_type-4).''] + $this->stats[$this->uids[$uid1]]['m11a'] + $witm_data['sv_pm'.($witm_type-4).''];
				$wAp -= $this->stats[$this->uids[$uid2]]['antpm'.($witm_type-4).''];
			}
*/
			if($witm_type==12) {
				//���� �������
				$wAp += $this->stats[$this->uids[$uid1]]['m10'];
				if($this->users[$this->uids[$uid1]]['align']==7) {
					$wAp += 15;
				}
			}elseif($witm_type < 5) {
				$wAp += $this->stats[$this->uids[$uid1]]['pa'.$witm_type.'']/* + $this->stats[$this->uids[$uid1]]['m10']*/ + $witm_data['sv_pa'.$witm_type.''];
				$wAp -= $this->stats[$this->uids[$uid2]]['antpa'.$witm_type.''];
			}else{
				$wAp += $this->stats[$this->uids[$uid1]]['m10'] + $this->stats[$this->uids[$uid1]]['pm'.($witm_type-4).''] + $this->stats[$this->uids[$uid1]]['m11a'] + $witm_data['sv_pm'.($witm_type-4).''];
				$wAp -= $this->stats[$this->uids[$uid2]]['antpm'.($witm_type-4).''];
			}
			//

			//
			//�������� ������ �������
			$vladenie = 0;
						
			//������ �����
			$proboi = 0;
			if( $this->mfs(4, $witm_data['sv_m9']  , $this->users[$this->uids[$uid1]]['level'] , $this->users[$this->uids[$uid2]]['level']) == 1 ) {
				$proboi = $witm_data['sv_m9'];
				$r['pb'] = 1;
			}
			
			//������ ��������� �� % ������ � ������ ���� (������)
			/*if( $witm_data['sv_m9'] > 0 ) {
				$proboi = $witm_data['sv_m9'];
				$r['pb'] = 1;
			}*/
			//������ ��������� �� % ������ � ������ ���� (�����)
			
			if( $witm['2h'] > 0 ) {
				//$witm_data['sv_yron_min'] = $witm_data['sv_yron_min']*2;
				//$witm_data['sv_yron_max'] = $witm_data['sv_yron_max']*2;
			}
			
			$y = $this->yrn(
				//$st1, $st2, $u1, $u2, $level, $level2, $type, $min_yron, $max_yron, $min_bron, $max_bron,
				//$vladenie, $power_yron, $power_krit, $zashita, $ozashita, $proboi, $weapom_damage
				$this->stats[$this->uids[$uid1]],
				$this->stats[$this->uids[$uid2]],
				$this->users[$this->uids[$uid1]],
				$this->users[$this->uids[$uid2]],
				$this->users[$this->uids[$uid1]]['level'],
				$this->users[$this->uids[$uid2]]['level'],
				//
				$witm_type,
				$this->stats[$this->uids[$uid1]]['minAtack'], //���. ���� (����������)
				$this->stats[$this->uids[$uid1]]['maxAtack'], //����. ����
				$bron[$zona][0], //����� ���.
				$bron[$zona][1], //����� ����
				//
				$vladenie, //��������
				(($wAp + $w3p + $w14p)), //�������� �����
				($this->stats[$this->uids[$uid1]]['m3']), //�������� �����
				(($this->stats[$this->uids[$uid2]]['za' . $witm_type2]) - $this->stats[$this->uids[$uid1]]['pza']), //������ �� �����
				$this->stats[$this->uids[$uid1]]['ozash'], //���������� ������
				$proboi, //������ �����
				0, //��
				($witm_data['sv_yron_min']+$this->stats[$this->uids[$uid1]]['yron_min']),
				($witm_data['sv_yron_max']+$this->stats[$this->uids[$uid1]]['yron_max']),
				$this->stats[$this->uids[$uid2]]['zaproc'],
				$this->stats[$this->uids[$uid2]]['zmproc'],
				(($this->stats[$this->uids[$uid2]]['zm' . ($witm_type2-4)]) - $this->stats[$this->uids[$uid1]]['pzm']), //������ �� �����
				$this->stats[$this->uids[$uid1]]['omzash'], //���������� ������
				$witm['type'],
				$witm
			);		
			
			$r['y'] = round(rand($y['min'],$y['max']));
			$r['k'] = round(rand($y['Kmin'],$y['Kmax']));
			
			$r['m_y'] = $y['max'];
			$r['m_k'] = $y['Kmax'];
			
			$r['bRND'] = $y['bRND'];
			
			$r['w_type'] = $witm_type;
			
			//���� ������ ������ - ���� ���� �� 50%
			$wp1 = $this->stats[$this->uids[$uid1]]['items'][$this->stats[$this->uids[$uid1]]['wp3id']];
			$wp2 = $this->stats[$this->uids[$uid1]]['items'][$this->stats[$this->uids[$uid1]]['wp14id']];
			/*if( $wp == 14 ) {				
				if( $wp1['level'] > $wp2['level'] ) {
					$r['y'] = floor( $r['y'] * 0.5 );
					$r['k'] = floor( $r['k'] * 0.5 );
				}
			}elseif( $wp == 3 ) {			
				if( $wp2['level'] > $wp1['level'] ) {
					$r['y'] = floor( $r['y'] * 0.5 );
					$r['k'] = floor( $r['k'] * 0.5 );
				}
			}*/
			
			if( $r['y'] < 1 ) {
				$r['y'] = 1;
			}			
			if( $r['k'] < 1 ) {
				$r['k'] = 1;
			}	
			//$this->a_restart_stats($uid1,1);
			//$this->a_testing_stats($uid2,1);	
			return $r;
		}
		
	//������� ����
		public function yronRazmen($id,$at,$pat = false,$rjd = 0) {
						
			if( $pat == true ) {
				$pat = $at;
				$at = $pat['p'];
			}else{
				unset($pat);
			}
						
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$yhod = array( 1 => 1 , 2 => 0 );
				//$uid1 = $this->yhod_user($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod']);
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$yhod = array( 1 => 0 , 2 => 1 );
				//$uid2 = $this->yhod_user($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod']);
			}
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				//������� �������� �� ���������
				
				
				//������ ����� (u2) �� (u1)
				//print_r( $at[$i] );
				$j = 0; $k = 0; $wp = 3;
				if( $rjd > 0 ) {
					$j = $rjd - 1;
				}
				while($j < count($at[$a]['atack']) && $j < 8) {
					// ���� ��� , ��� �����
					if( $k == 0 && isset($this->stats[$this->uids[$u1]]['wp3id']) ) {
						//����� ����
						$wp = 3;
						$k = 1;
					}else{
						//������ ����
						if( isset($this->stats[$this->uids[$u1]]['wp14id']) && $this->stats[$this->uids[$u1]]['items'][$this->stats[$this->uids[$u1]]['wp14id']]['type'] != 13 ) {
							$wp = 14;	
						}else{
							if( isset($this->stats[$this->uids[$u1]]['wp3id']) ) {
								$wp = 3;
							}else{
								//��� ������
								$wp = 3;
							}
						}
						$k = 0;
					}
					if( $wp > 0 ) {
						$witm = $this->stats[$this->uids[$u1]]['items'][$this->stats[$this->uids[$u1]]['wp' . $wp . 'id']];
						$witm_type = $this->weaponTx($witm);
						$at[$a]['atack'][$j]['wt'] = $witm['type'];
					}
					//
					$at[$a]['atack'][$j]['yhod'] = $yhod[$a];					
					//
					
					if( !isset($at[$a]['atack'][$j]['yron']) && (
						$at[$a]['atack'][$j][1] == 1 ||
						$at[$a]['atack'][$j][1] == 4 ||
						$at[$a]['atack'][$j][1] == 5 )
					) {
							//
							$at[$a]['atack'][$j]['yron'] = $this->yronGetrazmen($u1,$u2,$wp,$at[$a]['atack'][$j][0]);
							if( $at[$a]['atack'][$j][1] == 4 ) {
								$at[$a]['atack'][$j]['yron']['y_old'] = $at[$a]['atack'][$j]['yron']['y'];
								$at[$a]['atack'][$j]['yron']['y'] = round($at[$a]['atack'][$j]['yron']['k']/2);
							}elseif( $at[$a]['atack'][$j][1] == 5 ) {
								$at[$a]['atack'][$j]['yron']['y_old'] = $at[$a]['atack'][$j]['yron']['y'];
								$at[$a]['atack'][$j]['yron']['y'] = $at[$a]['atack'][$j]['yron']['k'];
							}
							$at[$a]['atack'][$j]['yron']['2h'] = $witm['2h'];
							$at[$a]['atack'][$j]['yron']['w'] = $wp;
							if( $at[$a]['atack'][$j]['yron']['y'] < 1 ) {
								$at[$a]['atack'][$j]['yron']['r'] = '--';
							}else{
								$at[$a]['atack'][$j]['yron']['r'] = '-' . $at[$a]['atack'][$j]['yron']['y'];
							}
							//
					}else{
							//
							$at[$a]['atack'][$j]['block'] = $this->yronGetrazmen($u1,$u2,$wp,$at[$a]['atack'][$j][0]);
							if( $at[$a]['atack'][$j][1] == 4 ) {
								$at[$a]['atack'][$j]['block']['y_old'] = $at[$a]['atack'][$j]['block']['y'];
								$at[$a]['atack'][$j]['block']['y'] = round($at[$a]['atack'][$j]['block']['k']/2);
							}elseif( $at[$a]['atack'][$j][1] == 5 ) {
								$at[$a]['atack'][$j]['block']['y_old'] = $at[$a]['atack'][$j]['block']['y'];
								$at[$a]['atack'][$j]['block']['y'] = $at[$a]['atack'][$j]['block']['k'];
							}
							$at[$a]['atack'][$j]['block']['2h'] = $witm['2h'];
							$at[$a]['atack'][$j]['block']['w'] = $wp;
							if( $at[$a]['atack'][$j]['block']['y'] < 1 ) {
								$at[$a]['atack'][$j]['block']['r'] = '--';
							}else{
								$at[$a]['atack'][$j]['block']['r'] = '-' . $at[$a]['atack'][$j]['block']['y'];
							}
							//
					}
					$j++;
				}
					
				$i++;
			}
			
			if( isset($pat) && $pat != false ) {
				$pat['p'] = $at;
				$at = $pat;
			}
			
			return $at;
		}
		
	//���������� ��������
		public function updateHealth($id,$at) {
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$uid1 = $this->yhod_user($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod']);
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$uid2 = $this->yhod_user($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod']);
			}
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
						
				//������� �������������� ������
				if( $this->atacks[$id]['out'.$b] > 0 ) {
					//����� ${'u'.$a} �������� �������, ��������
					if( rand(0,100) < min(floor($this->stats[$this->uids[$u1]]['m6']/5),20) ) {
						//������ ������� ����������
						if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
							$this->users[$this->uids[$u1]]['tactic3']++;
						}
					}
					if( rand(0,100) < min(floor($this->stats[$this->uids[$u1]]['m8']/4),20) ) {
						//������ ������� ����
						if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
							$this->users[$this->uids[$u1]]['tactic4']++;
						}
					}
				}
								
				//������ ����� ���� (u2) �� ���������� (u1)
				//print_r( $at[$i] );
				$j = 0; $k = 0; $wp = 3;
				while($j < count($at[$a]['atack']) && $j < 8 ) {
					//��������� �������
					//$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['tactic1']
					if( $at[$a]['atack'][$j][1] == 1 ) {
						//u1 ������ ������� ������ u2
						if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
							$this->users[$this->uids[$u1]]['tactic1']++;
						}
						//��������
						if( $at[$a]['atack'][$j]['yron']['2h'] == 1 ) {
							if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
								$this->users[$this->uids[$u1]]['tactic1'] += 2;
							}
						}
					}elseif( $at[$a]['atack'][$j][1] == 2 ) {
						//u2 ��������� �� u1
					}elseif( $at[$a]['atack'][$j][1] == 3 ) {
						//u2 ������������ ���� u1
						if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
							$this->users[$this->uids[$u2]]['tactic4']++;
						}
					}elseif( $at[$a]['atack'][$j][1] == 4 ) {
						//u1 ������ ���� u2 ������
						if( !isset($at[$a]['atack'][$j]['notactic2']) ) {
							if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
								$this->users[$this->uids[$u1]]['tactic2']++;
							}
						}
						$this->users[$this->uids[$u2]]['tactic4']++; //������� �� ������ ����
					}elseif( $at[$a]['atack'][$j][1] == 5 ) {
						//u1 ������ ����������� ������ u2
						if( !isset($at[$a]['atack'][$j]['notactic2']) ) {
							if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
								$this->users[$this->uids[$u1]]['tactic2'] += 2;
							}
							//��������
							if( $at[$a]['atack'][$j]['yron']['2h'] == 1 ) {
								if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
									$this->users[$this->uids[$u1]]['tactic2'] += 1;
								}
							}
						}
					}elseif( $at[$a]['atack'][$j][1] == 6 ) {
						//u2 ��������� ���� u1
						if( !isset($at[$a]['atack'][$j]['notactic5']) ) {
							if( $this->stats[$this->uids[$u1]]['this_animal'] == 0 ) {
								$this->users[$this->uids[$u2]]['tactic5']++;
							}
						}
					}elseif( $at[$a]['atack'][$j][1] == 7 ) {
						//u2 ���������� ����� ���� u1						
					}elseif( $at[$a]['atack'][$j][1] == 8 ) {
						//u2 ��������� �� ����� u1 � ����� �� ���� ���������
						if( $this->stats[$this->uids[$u1]]['this_animal'] == 0 ) {
							$this->users[$this->uids[$u2]]['tactic3']++;
						}
					}
					// ���� ��� , ��� �����
					if( isset($at[$a]['atack'][$j]['yron']) && (
					$at[$a]['atack'][$j][1] == 1 ||
					$at[$a]['atack'][$j][1] == 4 ||
					$at[$a]['atack'][$j][1] == 5 )) {
						//
						if( $at[$a]['atack'][$j]['yron']['y'] < 0 ) {
							$at[$a]['atack'][$j]['yron']['y'] = 1;
							$at[$a]['atack'][$j]['yron']['r'] = -1;
							$at[$a]['atack'][$j]['yron']['k'] = 1;
						}
						//��������� ���������� ���� � ����
						//$this->users[$this->uids[$u1]]['battle_yron'] += $at[$a]['atack'][$j]['yron']['y'];
						$this->takeExp( $u1, $at[$a]['atack'][$j]['yron']['y'], $u1, $u2) ;
						//�������� ��
						$this->stats[$this->uids[$u2]]['hpNow'] -= $at[$a]['atack'][$j]['yron']['y'];
						$this->users[$this->uids[$u2]]['last_hp'] = -$at[$a]['atack'][$j]['yron']['y'];
						
						//echo '['.$u1.' -> '.$u2.']';
						$at[$a]['atack'][$j]['yron']['hp'] = $this->stats[$this->uids[$u2]]['hpNow'];
						if( $at[$a]['atack'][$j]['yron']['hp'] < 1 ) {
							$at[$a]['atack'][$j]['yron']['hp'] = 0;
						}
						$at[$a]['atack'][$j]['yron']['hpAll'] = $this->stats[$this->uids[$u2]]['hpAll'];
						if( $at[$a]['atack'][$j]['yron']['hp'] > $at[$a]['atack'][$j]['yron']['hpAll'] ) {
							$at[$a]['atack'][$j]['yron']['hp'] = $at[$a]['atack'][$j]['yron']['hpAll'];
						}
						//
						//�������������
						if( rand(0,100) <= 50 ) {
							if( $at[$a]['atack'][$j][1] == 4 || $at[$a]['atack'][$j][1] == 5 ) {
								if( !isset($at[$a]['atack'][$j]['yron']['travma']) && rand(0,1000) < 500 && $this->users[$this->uids[$u2]]['level'] > 1 && $this->stats[$this->uids[$u1]] > 1 ) {
									$trvm_chns = floor(rand(0,200)/10);
									if( $trvm_chns > 3 || $trvm_chns < 1 ) {
										$trvm_chns = 0;
									}
									$at[$a]['atack'][$j]['yron']['travma'] = array( $trvm_chns , '������������ ������' );
									unset($trvm_chns);
								}
							}
						}
					}
					$j++;
				}
					
				$i++;
			}
			
			return $at;
		}
		
	//��������� ����� ���������� ������
		public function addstatuser($id) {
			if( $id > 0 ) {
				$uid = $id;
				$id = $this->uids[$uid];
				mysql_query('INSERT INTO `battle_users`
					( `battle`,`uid`,`time_enter`,`login`,`level`,`align`,`clan`,`hpAll`,`hp`,`hpStart`,`team` )
				VALUES
				(
					"'.$this->info['id'].'",
					"'.$uid.'",
					"'.time().'","'.$this->users[$id]['login'].'",
					"'.$this->users[$id]['level'].'",
					"'.$this->users[$id]['align'].'",
					"'.$this->users[$id]['clan'].'",
					"'.$this->stats[$id]['hpAll'].'",
					"'.$this->stats[$id]['hp'].'",
					"'.$this->stats[$id]['hpNow'].'",
					"'.$this->users[$id]['team'].'"
				)');
			}
		}
		
	//��������� ������
		public function priemsRazmen($id, $at) {
			if( $at == 'fast' ) {
				$uid1 = $id[0];
				$uid2 = $id[1];
			}else{
				$uid1 = $this->atacks[$id]['uid1'];
				$uid2 = $this->atacks[$id]['uid2'];
			}
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				//�������� ������ � ������� ����� ����� ���������
				$eff = $this->stats[$this->uids[$u1]]['effects'];
				$this->stats[$this->uids[$u1]]['u_priem'] = array();
				$j = 0;				
				while( $j <= count($eff) ) {
					if( isset($eff[$j]) && $eff[$j]['id_eff'] == 22 && $eff[$j]['v1'] == 'priem' && $eff[$j]['v2'] > 0 ) {
						$this->stats[$this->uids[$u1]]['u_priem'][] = array( $j , $eff[$j]['v2'] , $this->prm[$eff[$j]['v2']]['act'] , $eff[$j]['id'] , $this->prm[$eff[$j]['v2']]['type_of'] , $this->prm[$eff[$j]['v2']]['moment'] , $this->prm[$eff[$j]['v2']]['moment_end'] , $this->prm[$eff[$j]['v2']]['type_sec'] );
					}
					$j++;
				}

				$i++;
			}
			//
		}
		
	//������ ������� ������������ �����������
		public function priemsRazmenMoment( $id, $at ) {
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			$i = 1;
			while( $i <= 2 ) {
				
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}				
				if( !isset($at['p']['p_cast']) ) {
					$at['p'] = $at;
					$at['p']['p_cast'] = true;
				}
				//
				
				//������ ����� �� �����
				if(!isset($this->stats[$this->uids[$u2]]['nopryh']) || $this->stats[$this->uids[$u2]]['nopryh'] == 0) {
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0  && $this->stats[$this->uids[$u1]]['u_priem'][$j][5] == 1 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
								$pr_used_this = $u1;
								$pr_moment = true;
								require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
								$this->stats[$this->uids[$u2]]['nopryh']--;
							}
						}
						$j++;
					}
				}
				//������ �����
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][5] == 2 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//������ �����
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][5] == 3 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							if( isset($fx_priem) ) {
								$at = $fx_priem($id,$at,$u1,$j);
							}
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][5] == 4 ) {
						if( $this->testPriemVarTrueZash( $i , 1 , $this->stats[$this->uids[$u1]]['u_priem'][$j][1] , $a , $b , $u1 , $u2 ) == false ) {
							
						}elseif(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][5] == 5 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//
				$i++;
			}
			//
			return $at;
		}

	//������ ������� ������������ ����������� (� ����� ����)
		public function priemsRazmenMomentEnd( $id, $at ) {
			
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			$i = 1;
			while( $i <= 2 ) {
				
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}				
				if( !isset($at['p']['p_cast']) ) {
					$at['p'] = $at;
					$at['p']['p_cast'] = true;
				}
				//
				
				//������ ����� �� �����
				if(!isset($this->stats[$this->uids[$u2]]['nopryh']) || $this->stats[$this->uids[$u2]]['nopryh'] == 0) {
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0  && $this->stats[$this->uids[$u1]]['u_priem'][$j][6] == 1 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
								$pr_used_this = $u1;
								$pr_moment = true;
								require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
								$this->stats[$this->uids[$u2]]['nopryh']--;
							}
						}
						$j++;
					}
				}
				//������ �����
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][6] == 2 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//������ �����
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][6] == 3 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							if(isset($fx_priem)) {
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
							}
						}
					}
					$j++;
				}
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][6] == 4 ) {
						if( $this->testPriemVarTrueZash( $i , 2 , $this->stats[$this->uids[$u1]]['u_priem'][$j][1] , $a , $b , $u1 , $u2 ) == false ) {
								
						}elseif(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][6] == 5 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							$pr_moment = true;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//
				$i++;
			}
			//
			return $at;
		}

		
	//���������� ������
		public function priemsTestRazmen( $id, $at ) {
			$uid1 = $this->atacks[$id]['uid1'];
			$uid2 = $this->atacks[$id]['uid2'];
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				if( !isset($at['p']['p_cast']) ) {
					$at['p'] = $at;
					$at['p']['p_cast'] = true;
				}
				
				//������ ����� �� �����
				if(!isset($this->stats[$this->uids[$u2]]['nopryh']) || $this->stats[$this->uids[$u2]]['nopryh'] == 0) {
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0  && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 1 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
								$pr_used_this = $u1;
								require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
								$this->stats[$this->uids[$u2]]['nopryh']--;
							}
						}
						$j++;
					}
				}
				//������ �����
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 2 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				
				//������ �����
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 3 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							if( isset($fx_priem) ) {
								$at = $fx_priem($id,$at,$u1,$j);
							}
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 4 ) {
						if( $this->testPriemVarTrueZash( $i , 3 , $this->stats[$this->uids[$u1]]['u_priem'][$j][1] , $a , $b , $u1 , $u2 ) == false ) {

						}elseif(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							if( isset($fx_priem) ) {
								$at = $fx_priem($id,$at,$u1,$j);
							}
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				
				/*
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u2]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u2]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u2]]['u_priem'][$j][4] == 4 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u2;
							require('priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php');
							if( isset($fx_priem) ) {
								$at = $fx_priem($id,$at,$u2,$j);
							}
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				*/
				
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 5 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							if( isset($fx_priem) ) {
								$at = $fx_priem($id,$at,$u1,$j);
							}
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				//������ ������
				/*$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 8 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}*/
					
				$i++;
			}
			
			$i = 1;
			while( $i <= 2 ) {
				if( $i == 1 ) {
					$a = 1;
					$b = 2;
					$u1 = ${'uid1'};
					$u2 = ${'uid2'};
				}else{
					$a = 2;
					$b = 1;
					$u1 = ${'uid2'};
					$u2 = ${'uid1'};
				}
				
				if( !isset($at['p']['p_cast']) ) {
					$at['p'] = $at;
					$at['p']['p_cast'] = true;
				}
				
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 8 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
				
				//������ ������
				$j = 0;
				while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
					if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 9 ) {
						if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
							$pr_used_this = $u1;
							require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
							$at = $fx_priem($id,$at,$u1,$j);
							unset(${'fx_priem'});
						}
					}
					$j++;
				}
					
				$i++;
			}

			return $at;
		}
		
	//��������� �������� �������
		public function priemsRestartRazmen( $id, $at ) {
			if( isset($at['p']) ) {
				//
				//��������
				$uid1 = $this->atacks[$id]['uid1'];
				$uid2 = $this->atacks[$id]['uid2'];
				
				/*
					���� ��������� �������� � ��������, �������� ���������� ��������, ������ �� �������� ���:					
						���� 1. ��������� ������������
						���� 2 ������ ����� 1. ��������� ������ ������� ������������ �����������
					�������� �������:
						���� 1. ��������� ������������
						���� 2 ������ ����� 1. ��������� ������ �������
						���� 3. ��������� ������������
						���� 4 ������ ����� 3. ��������� ������ �����
						� �.�.
				*/
				
				$i = 1;
				while( $i <= 2 ) {
					if( $i == 1 ) {
						$a = 1;
						$b = 2;
						$u1 = ${'uid1'};
						$u2 = ${'uid2'};
					}else{
						$a = 2;
						$b = 1;
						$u1 = ${'uid2'};
						$u2 = ${'uid1'};
					}
					
					if( !isset($at['p']['p_cast']) ) {
						$at['p'] = $at;
						$at['p']['p_cast'] = true;
					}
					
					//������ ����� �� �����
					if(!isset($this->stats[$this->uids[$u2]]['nopryh']) || $this->stats[$this->uids[$u2]]['nopryh'] == 0) {
						$j = 0;
						while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
							if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0  && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 1 ) {
								if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
									$pr_tested_this = $u1;
									require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
									$at = $fx_priem($id,$at,$u1,$j);
									unset(${'fx_priem'});
									$this->stats[$this->uids[$u2]]['nopryh']--;
								}
							}
							$j++;
						}
					}
					//������ �����
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 2 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
								$pr_tested_this = $u1;
								require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
							}
						}
						$j++;
					}
					//������ �����
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 3 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
								$pr_tested_this = $u1;
								require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
							}
						}
						$j++;
					}
					//������ ������
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 4 ) {
							if( $this->testPriemVarTrueZash( $i , 4 , $this->stats[$this->uids[$u1]]['u_priem'][$j][1] , $a , $b , $u1 , $u2 ) == false ) {

							}elseif(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
								$pr_tested_this = $u1;
								require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
							}
						}
						$j++;
					}
					//������ ������
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 5 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
								$pr_tested_this = $u1;
								require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u1,$j);
								unset(${'fx_priem'});
							}
						}
						$j++;
					}
					//������ ������
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u2]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u2]]['u_priem'][$j][4] == 8 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php')) {
								$pr_tested_this = $u2;
								require('priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u2,$j);
								unset(${'fx_priem'});
							}
						}
						$j++;
					}
					//������ ������
					$j = 0;
					while( $j <= count( $this->stats[$this->uids[$u2]]['u_priem'] ) ) {
						if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u2]]['u_priem'][$j][4] == 9 ) {
							if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php')) {
								$pr_tested_this = $u2;
								require('priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php');
								$at = $fx_priem($id,$at,$u2,$j);
								unset(${'fx_priem'});
							}
						}
						$j++;
					}
						
					$i++;
				}
				//
				$at = $at['p'];				
				unset($at['p']);	
			}
			return $at;
		}
		
	//�������� �������� ������
		public function testPriemVarTrueZash( $i , $id , $pid , $a , $b , $u1 , $u2 ) {
			$r = true;
			//echo '['.$pid.','.$i.','.$a.','.$b.']';
			$g = array( 
				45 => array( '221' => true )
			);
			if(isset($g[$pid])) {
				//echo 1;
				if( $g[$pid][$i.$a.$b] == true ) {
					$r = true;
				}else{
				//	$r = false;
				}
			}
			return $r;
		}
		
	//�������� ����� �������
		public $um_priem  = array();
		public function testYronPriem( $uid1, $uid2, $priem, $yron, $profil, $stabil, $test = false, $inlog = 0 ) {
			/*
				profil = {
					-1 - ���� �������
					-2 - ���� ������
					 0 - ����������
					 1-4  - ���������� ������
					 5-12 - ���������� �����
				}
				stabil - ���� �� ��������� ��������� � �.�
			*/
			//��������
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
			
			//��������� ������ ������ ������ $u1 �� ���� ������ $u2
			//�������� ������ � ������� ����� ����� ���������
			if(!isset($this->stats[$this->uids[$u2]]['u_priem'])) {
				$eff = $this->stats[$this->uids[$u2]]['effects'];
				$j = 0;				
				while( $j <= count($eff) ) {
					if( isset($eff[$j]) && $eff[$j]['id_eff'] == 22 && $eff[$j]['v1'] == 'priem' && $eff[$j]['v2'] > 0 ) {
						$this->stats[$this->uids[$u2]]['u_priem'][] = array( $j , $eff[$j]['v2'] , $this->prm[$eff[$j]['v2']]['act'] , $eff[$j]['id'] , $this->prm[$eff[$j]['v2']]['type_of'] , $this->prm[$eff[$j]['v2']]['moment'] );
					}
					$j++;
				}
				unset($eff);
			}
			//������ ������
			$j = 0;
			while( $j <= count( $this->stats[$this->uids[$u2]]['u_priem'] ) ) {
				if( $this->stats[$this->uids[$u2]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u2]]['u_priem'][$j][4] == 4 ) {
					if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php')) {
						//if( $test == false ) {
						//
						$pr_momental_this = $u2;
						require('priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php');
						$yron = $fx_moment($u2,$u1,$j,$yron,$profil);
						unset(${'fx_moment'});
						//
						/*}else{
							echo '[('.$u2.')';
							print_r($this->stats[$this->uids[$u2]]['um_priem']);
							echo '!]';
							echo '[USED:'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].']';
						}*/
					}
				}
				$j++;
			}
			
			//������ ������
			$j = 0;
			while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
				if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][4] == 5 ) {
					if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
						$pr_momental_this = $u1;
						require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
						if( isset($fx_moment) ) {
							$yron = $fx_moment($u1,$u2,$j,$yron,$profil,$inlog);
						}
						unset(${'fx_moment'});
						/*$pr_tested_this = $u1;
						require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
						$at = $fx_priem($id,$at,$u1,$j);
						unset(${'fx_priem'});*/
					}
				}
				$j++;
			}
			
			//������ ������ (������ �� ���� �� ������������ �������)
			$j = 0;
			while( $j <= count( $this->stats[$this->uids[$u1]]['u_priem'] ) ) {
				if( $this->stats[$this->uids[$u1]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u1]]['u_priem'][$j][7] == 5 ) {
					if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php')) {
						$pr_momental_this_seven = $u1;
						require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
						if( isset($fx_moment_seven) ) {
							$yron = $fx_moment_seven($u1,$u2,$j,$yron,$profil,$inlog);
						}
						unset(${'fx_moment_seven'});
					}
				}
				$j++;
			}
			
			//������ ������
			$j = 0;
			while( $j <= count( $this->stats[$this->uids[$u2]]['u_priem'] ) ) {
				if( $this->stats[$this->uids[$u2]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u2]]['u_priem'][$j][4] == 8 ) {
					if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php')) {
						$pr_momental_this = $u2;
						require('priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php');
						if( isset($fx_moment) ) {
							$yron = $fx_moment($u2,$u1,$j,$yron,$profil,$inlog);
						}
						unset(${'fx_moment'});
						/*$pr_tested_this = $u1;
						require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
						$at = $fx_priem($id,$at,$u1,$j);
						unset(${'fx_priem'});*/
					}
				}
				if( $this->stats[$this->uids[$u2]]['u_priem'][$j][2] > 0 && $this->stats[$this->uids[$u2]]['u_priem'][$j][4] == 9 ) {
					if(file_exists('../../_incl_data/class/priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php')) {
						$pr_momental_this = $u2;
						require('priem/'.$this->stats[$this->uids[$u2]]['u_priem'][$j][1].'.php');
						if( isset($fx_moment) ) {
							$yron = $fx_moment($u2,$u1,$j,$yron,$profil,$inlog);
						}
						unset(${'fx_moment'});
						/*$pr_tested_this = $u1;
						require('priem/'.$this->stats[$this->uids[$u1]]['u_priem'][$j][1].'.php');
						$at = $fx_priem($id,$at,$u1,$j);
						unset(${'fx_priem'});*/
					}
				}
				$j++;
			}
			
			/*if( $this->stats[$this->uids[$u2]]['zmproc'] > 0 ) {
				$yron = floor($yron/100*(100-$this->stats[$this->uids[$u2]]['zmproc']));
				if( $yron < 1 ) {
					$yron = 1;
				}
			}*/
			
			return $yron;
		}
		
	//���� + ������� ���� �� ���� �������\������
		public function priemYronSave($u1,$u2,$yron,$type) {
			//$type 0 - ���� , 1 - ���
			
			$this->testUserInfoBattle($u1);
			$this->testUserInfoBattle($u2);
						
			if( isset($this->uids[$u1]) ) {
				
				if( $this->stats[$this->uids[$u2]]['hpAll'] <= 1000 ) {
					$adt6 = round(0.1*(floor($yron)/$this->stats[$this->uids[$u2]]['hpAll']*100),10);
				}else{
					$adt6 = round(0.1*(floor($yron)/1000*100),10);
				}				
				
				if( $yron > $this->stats[$this->uids[$u2]]['hpNow'] ) {
					$yron = $this->stats[$this->uids[$u2]]['hpNow'];
				}
				
				if( $yron > 0 ) {
					$this->users[$this->uids[$u1]]['battle_yron'] += $yron;
					$this->users[$this->uids[$u1]]['battle_exp'] += round(1*$this->testExp($yron,$this->stats[$this->uids[$u1]],$this->stats[$this->uids[$u2]],$u1,$u2));
					if( $this->stats[$this->uids[$u2]]['this_animal'] == 0 ) {
						$this->users[$this->uids[$u1]]['tactic6'] += $adt6;
						$this->stats[$this->uids[$u1]]['tactic6'] += $adt6;
					}else{
						$this->users[$this->uids[$u1]]['tactic6'] += $adt6/3;
						$this->stats[$this->uids[$u1]]['tactic6'] += $adt6/3;
					}
				}else{
					$adt6 = 0;
				}
				//
				$this->users[$this->uids[$u2]]['last_hp'] = -$yron;
				//
				mysql_query('UPDATE `stats` SET	
					`tactic6`	  = `tactic6` + "'.$adt6.'",
					`battle_yron` = `battle_yron` + "'.$yron.'",
					`battle_exp`  = `battle_exp` + "'.round($this->testExp($yron,$this->stats[$this->uids[$u1]],$this->stats[$this->uids[$u2]],$u1,$u2)).'"					
				WHERE `id` = "'.$u1.'" LIMIT 1');
				//
				mysql_query('UPDATE `stats` SET	
					`last_hp` 	  = "'.$this->users[$this->uids[$u2]]['last_hp'].'"					
				WHERE `id` = "'.$u2.'" LIMIT 1'); 
			}
			$this->addNewStat(
				array(
					1 => array(
						'battle'	=> $this->info['id'],
						'uid1'		=> $this->users[$this->uids[$u1]]['id'],
						'uid2'		=> $this->users[$this->uids[$u2]]['id'],
						'time'		=> time(),
						'type'		=> 0,
						'a'			=> '10000',
						'b'			=> 0,
						'type_a'	=> 1,
						'type_b'	=> 0,
						'ma'		=> 1,
						'mb'		=> 1,
						'yrn'		=> $yron,
						'yrn_krit'	=> 0,
						'tm1'		=> $this->users[$this->uids[$u1]]['team'],
						'tm2'		=> $this->users[$this->uids[$u2]]['team']
					)
				)
			);
			
		}
		
	//������� ���� ����� ��������
		public $restart_stats_data = array();
		
		public function a_restart_stats($uid1,$glob) {
			if($uid1 > 0 && isset($this->restart_stats_data[$uid1])) {
				$this->stats[$this->uids[$uid1]] = $this->restart_stats_data[$uid1];
				if($glob == 1) {
					unset($this->restart_stats_data[$uid1]);
				}
			}
		}
		
		public function a_save_stats($uid1) {
			if($uid1 > 0) {
				$this->restart_stats_data[$uid1] = $this->stats[$this->uids[$uid1]];
			}
		}
		
		public function a_testing_stats($uid1,$zona) {
			//$this->stats[$this->uids[$uid1]] = $this->yronGetrazmenStats( $this->stats[$this->uids[$uid1]] , $zona );
			if($uid1 > 0) {
				$this->stats[$this->uids[$uid1]] = $this->yronGetrazmenStats( $this->stats[$this->uids[$uid1]] , $zona );
			}
		}
		
		public $import_atack = array();
		public $contr = array();
		public $import_user = 0;
		public function startAtack($id)
		{
			global $c,$u,$log_text,$priem;
			//
			$this->prlog = array();
			//
			$this->inport_user = 0;
			//
			$vrm = array(
				'uid1' => $this->atacks[$id]['uid1'],
				'uid2' => $this->atacks[$id]['uid2']
			);
			//
			/*
			if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
				$this->atacks[$id]['uid1'] = $this->atacks[$id]['uid2'];
			}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
				$this->atacks[$id]['uid2'] = $this->atacks[$id]['uid1'];
			}
			*/
			if(isset($this->atacks[$id]) && $this->atacks[$id]['lock'] == 0) {
				
				//����� ��������� �������
				$i = 1; $j = 2; $k = 0;
				$unpr = '';
				while( $i <= 2 ) {
					$untac = mysql_fetch_array(mysql_query('SELECT `id` FROM `eff_users` WHERE `v1` = "priem" AND `v2` = "217" AND `uid` = "'.$this->atacks[$id]['uid'.$i].'" AND `delete` = 0 LIMIT 1'));
					if(isset($untac['id'])) {
						//
						$pvr['sp'] = mysql_query('SELECT `a`.* FROM `eff_users` AS `a` WHERE `a`.`uid` = "'.$this->atacks[$id]['uid'.$j].'" AND `a`.`delete` = 0 AND `a`.`v1` = "priem"
						
							AND `a`.`v2` != 222
							
							AND `a`.`id_eff` != 2
							
							AND `a`.`v2` != 228
							
							AND `a`.`v2` != 238
							
							AND `a`.`v2` != 229
							
							AND `a`.`v2` != 139
							AND `a`.`v2` != 188
							AND `a`.`v2` != 226
							AND `a`.`v2` != 211
							AND `a`.`v2` != 49
							AND `a`.`v2` != 233
							AND `a`.`v2` != 227
							AND `a`.`v2` != 220
							AND `a`.`v2` != 191
							AND `a`.`v2` != 235
							AND `a`.`v2` != 236
							
							AND `a`.`v2` != 201
							
							AND `a`.`v2` != 206 AND `a`.`v2` != 207 AND `a`.`v2` != 208 AND `a`.`v2` != 209
							AND `a`.`v2` != 210 AND `a`.`v2` != 284 AND `a`.`v2` != 261 AND `a`.`v2` != 262
							AND `a`.`v2` != 263 AND `a`.`v2` != 258 AND `a`.`v2` != 273 AND `a`.`v2` != 286
							
							AND `a`.`v2` != 287 AND `a`.`v2` != 288 AND `a`.`v2` != 29 AND `a`.`v2` != 30
							AND `a`.`v2` != 31 AND `a`.`v2` != 32 AND `a`.`v2` != 256 AND `a`.`v2` != 249
							AND `a`.`v2` != 248 AND `a`.`v2` != 187 AND `a`.`v2` != 245 AND `a`.`v2` != 175
							AND `a`.`v2` != 176 AND `a`.`v2` != 177 AND `a`.`v2` != 178 AND `a`.`v2` != 179
							AND `a`.`v2` != 285 AND `a`.`v2` != 36 AND `a`.`v2` != 85 AND `a`.`v2` != 86
							AND `a`.`v2` != 87 AND `a`.`v2` != 88 AND `a`.`v2` != 89 AND `a`.`v2` != 90
							AND `a`.`v2` != 269 AND `a`.`v2` != 276 AND `a`.`v2` != 277 AND `a`.`v2` != 270
							AND `a`.`v2` != 174							
							
							AND `name` NOT LIKE "%���������%"
						
						LIMIT 30');
						while($pvr['pl'] = mysql_fetch_array($pvr['sp'])) {
							$pvr['pl']['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pvr['pl']['v2'].'" LIMIT 1'));
							if( isset($pvr['pl']['priem']['id']) && $pvr['pl']['priem']['neg'] == 0 && $pvr['pl']['id_eff'] != 2 ) {
								$this->delPriem($pvr['pl'],$this->users[$this->uids[$this->atacks[$id]['uid'.$j]]],100);
							}
						}
						//
						$k++;
					}
					$j--;
					$i++;
				}
				if( $u->info['admin'] > 0 ) {
					//die('test');
				}
				//
				//UPDATE ... SET `lock` = 1
				//�������� ��������������
				//$this->a_save_stats($this->atacks[$id]['uid1']);
				//$this->a_save_stats($this->atacks[$id]['uid2']);
				//
													
				//�������������� ����� 1% �� ���
				if( $this->atacks[$id]['out1'] == 0 ) {
					if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['s6']/4 < $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['level'] ) {
						$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] += floor($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['level'] + $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hod_minmana'] );
					}else{
						$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] += floor($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['s6']/4 + $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hod_minmana'] );
					}
					$this->users[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'];
				}
				//
				if( $this->atacks[$id]['out2'] == 0 ) {
					if( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['s6']/4 < $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['level'] ) {
						$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] += floor($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['level'] + $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hod_minmana'] );
					}else{
						$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] += floor($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['s6']/4 + $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hod_minmana'] );
					}
					$this->users[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'];
				}
				//
				//$this->users[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'];
				//$this->users[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'];
				//
				
				//���������
				if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['antm3'] != 0 ) {
					$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['m3'] -= round($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['antm3']);
				}
				if( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['antm3'] != 0 ) {
					$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['m3'] -= round($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['antm3']);
				}
				//���� � ������� �������� ������ ������ - ��������� ������ ��,,
				/*if( $this->users[$this->uids[$this->atacks[$id]['uid1']]]['level'] == 7 ) {
					if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['s2'] > 55 ) {
						$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['m5'] += ($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['s2']-5)*6;
					}
				}
				
				if( $this->users[$this->uids[$this->atacks[$id]['uid2']]]['level'] == 7 ) {
					if( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['s2'] > 55 ) {
						$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['m5'] += ($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['s2']-5)*6;
					}
				}*/
				
				//
				$last_yrn = array(
					1 => $this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_yron'],
					2 => $this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_yron']
				);
				
				//������ ���������� ������ � �����������
				$this->testZonb($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
				
				//��������� ����� ���������
				$this->magicItems($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
				$this->magicItems($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1']);
					
				// �������� ������ �������
				$this->priemsRazmen($id,$at);
				$this->priemsRazmenMoment($id,$at);
				$this->priemsRazmen($id,$at);
								
				// ��������� ������� (�������� ���� ����� ������, ���� �����, ���� ����)
				if( $this->atacks[$id]['uid2'] == 1 ) {
				//	$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['zona'] += 1;
				//	$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['zona'] += 1;
				}
				$at = $this->newRazmen($id);
				
				// ��������� ����� ��� ����� ���� �������� ��� �����
				// ������, �����������, ����, ������� ����, ���� �����
				// ���� ����� (���� ���� ���, �������)
				/*
				$at = $this->mf4Razmen($id,$at,0);
				// ����
				$at = $this->mf2Razmen($id,$at,0);
				// ������
				$at = $this->mf1Razmen($id,$at,0);
				// �����������
				$at = $this->mf3Razmen($id,$at,0);
				// ���������
				$at = $this->mf5Razmen($id,$at,0);				
				// ������� ��� ����� � ����
				$at = $this->yronRazmen($id,$at);
				*/
				$at = $this->mf2Razmen($id,$at); //����			
				$at = $this->mf4Razmen($id,$at); //���� �����
				$at = $this->mf1Razmen($id,$at); //������
				$at = $this->mf3Razmen($id,$at); //�����������
				//������ ��� �����
				$at = $this->mf5Razmen($id,$at); //���������
				//
				$at = $this->yronRazmen($id,$at);  //������ �����
								
				// ��������� ������
				//['effects'][
				// �������� ������ �������
				$at = $this->priemsTestRazmen($id,$at);
				// �������� ������ (������������� � ����������� ���� � �.�)
				$at = $this->priemsRestartRazmen($id,$at); //��������� �������� ������� (���� ���������)
				//
				
				//�������� ����������� ����
				if( count($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['set_pog']) > 0 ) {
					$this->testPogB($this->atacks[$id]['uid1'],1,$id,1);
				}
				if( count($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['set_pog']) > 0 ) {
					$this->testPogB($this->atacks[$id]['uid2'],1,$id,1);
				}
				
				// ��������� �� � ��������� �������
				$at = $this->updateHealth($id,$at);		
				
				// ������� � ���� + ���������� ���������� ���
				$this->addlogRazmen($id,$at);							
				//echo $this->seeRazmen($id,$at);
				// NEW BATTLE SYSTEM	
				
				//��������� ����� ���������
				$this->magicItems($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2'],$id);
				$this->magicItems($this->atacks[$id]['uid2'],$this->atacks[$id]['uid1'],$id);
							
				/*
				if( $this->stats[$this->uids[$vrm['uid1']]]['yhod'] > 0 ) {
					$this->atacks[$id]['uid1'] = $vrm['uid1'];
				}elseif( $this->stats[$this->uids[$vrm['uid2']]]['yhod'] > 0 ) {
					$this->atacks[$id]['uid2'] = $vrm['uid2'];
				}
				*/
				
				//
				//��������� ���� �����
				$this->restZonb($this->atacks[$id]['uid1'],$this->atacks[$id]['uid2']);
				//��������� �������� �������
				$zd1 = explode('|',$this->users[$this->uids[$this->atacks[$id]['uid1']]]['priems_z']);
				$zd2 = explode('|',$this->users[$this->uids[$this->atacks[$id]['uid2']]]['priems_z']);
				$zd1id = explode('|',$this->users[$this->uids[$this->atacks[$id]['uid1']]]['priems']);
				$zd2id = explode('|',$this->users[$this->uids[$this->atacks[$id]['uid2']]]['priems']);
				//
				$prmos = array();
				//
				$i5 = 0;

				while($i5 < 51) {
					if(isset($zd1[$i5]) && $zd1[$i5]>0) {
						//���� ������ �� ������� ���
						//$tstpm = mysql_fetch_array(mysql_query('SELECT `id` FROM `priems` WHERE `id` = "'.$zd1id[$i5].'" AND `activ` = 1 AND `img` NOT LIKE "wis_%" LIMIT 1'));
						//
						//if(isset($tstpm['id'])) {
							//
							$zd1[$i5] -= 1;
						//}elseif( $this->users[$this->uids[$this->atacks[$id]['uid2']]]['id'] == $this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'] ) {
							//�������� ������ �� ��������� ���
						//	$zd1[$i5] -= 1;
						//}else{
							//�� �������� (������ �� ��� ������� ���)
						//	if(!isset($prmos[$zd1id[$i5]])) {
						//		$prmos[$zd1id[$i5]] = mysql_fetch_array(mysql_query('SELECT `id`,`tr_hod` FROM `priems` WHERE `id` = "'.$zd1id[$i5].'" LIMIT 1'));
						//	}
						//	if( $prmos[$zd1id[$i5]]['tr_hod'] > 0 ) {
						//		$zd1[$i5] -= 1;
						//	}
						//}
					}else{
						$zd1[$i5] = 0;
					}
					if(isset($zd2[$i5]) && $zd2[$i5]>0) {
						//���� ������ �� ������� ���
						//$tstpm = mysql_fetch_array(mysql_query('SELECT `id` FROM `priems` WHERE `id` = "'.$zd2id[$i5].'" AND `activ` = 1 AND `img` NOT LIKE "wis_%" LIMIT 1'));
						//
						//if(isset($tstpm['id'])) {
							//
							$zd2[$i5] -= 1;
						//}elseif( $this->users[$this->uids[$this->atacks[$id]['uid1']]]['id'] == $this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'] ) {
							//�������� ������ �� ��������� ���
						//	$zd2[$i5] -= 1;
						//}else{
							//�� �������� (������ �� ��� ������� ���)
						//	if(!isset($prmos[$zd2id[$i5]])) {
						//		$prmos[$zd2id[$i5]] = mysql_fetch_array(mysql_query('SELECT `id`,`tr_hod` FROM `priems` WHERE `id` = "'.$zd2id[$i5].'" LIMIT 1'));
						//	}
						//	if( $prmos[$zd2id[$i5]]['tr_hod'] > 0 ) {
						//		$zd2[$i5] -= 1;
						//	}
						//}
					}else{
						$zd2[$i5] = 0;
					}
					$i5++;
				}
				unset($prmos);
				
				if( $this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'] == $this->users[$this->uids[$this->atacks[$id]['uid2']]]['id'] ) {
					$this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'] = -$this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'];
				}
				
				if( $this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'] == $this->users[$this->uids[$this->atacks[$id]['uid1']]]['id'] ) {
					$this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'] = -$this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'];
				}
							
				$this->users[$this->uids[$this->atacks[$id]['uid1']]]['priems_z'] = implode('|',$zd1);
				$this->users[$this->uids[$this->atacks[$id]['uid2']]]['priems_z'] = implode('|',$zd2);	
				if($this->atacks[$id]['uid1']==$u->info['id']) {
					$u->info['priems_z'] = implode('|',$zd1);					
				} elseif($this->atacks[$id]['uid2']==$u->info['id']) {
					$u->info['priems_z'] = implode('|',$zd2);					
				}
				//
				//��������� �������
				$i = 1;
				while( $i <= 6 ) {
					if( $this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic'.$i] > 25 ) {
						$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic'.$i] = 25;
					}elseif( $this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic'.$i] <= 0 ) {
						$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic'.$i] = 0;
					}
					if( $this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic'.$i] > 25 ) {
						$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic'.$i] = 25;
					}elseif( $this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic'.$i] <= 0 ) {
						$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic'.$i] = 0;
					}
					$i++;
				}
				//
				//��������� �������� ���������
				mysql_query('UPDATE `items_users` SET `btl_zd` = `btl_zd` - 1 WHERE (`uid` = "'.$this->atacks[$id]['uid1'].'" OR `uid` = "'.$this->atacks[$id]['uid2'].'") AND `btl_zd` > 0 AND `inOdet` > 0 LIMIT 100');
					
				mysql_query('UPDATE `users` SET `notrhod` = "-1" WHERE `id` = "'.$this->atacks[$id]['uid1'].'" OR `id` = "'.$this->atacks[$id]['uid2'].'" LIMIT 2');
				
				//��������� �������� ��������
				mysql_query('UPDATE `pirogi` SET `hod` = `hod` - 1 WHERE `btl` = "'.$this->info['id'].'" AND (`uid` = "'.$this->atacks[$id]['uid1'].'" OR `uid` = "'.$this->atacks[$id]['uid2'].'")');
				mysql_query('DELETE FROM `pirogi` WHERE `btl` = "'.$this->info['id'].'" AND `hod` < 1');
				
				//��������� �������� ��������
				mysql_query('UPDATE `spells` SET `hod` = `hod` - 1 WHERE `btl` = "'.$this->info['id'].'" AND (`uid` = "'.$this->atacks[$id]['uid1'].'" OR `uid` = "'.$this->atacks[$id]['uid2'].'")');
				mysql_query('DELETE FROM `spells` WHERE `btl` = "'.$this->info['id'].'" AND `hod` < 1');
				
							
				//��������� ������ � battle_users
				mysql_query('UPDATE `battle_users` SET `hp` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow'].'"
					WHERE `battle` = "'.$this->info['id'].'" AND `uid` = "'.$this->atacks[$id]['uid1'].'" LIMIT 1');
				mysql_query('UPDATE `battle_users` SET `hp` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow'].'"
					WHERE `battle` = "'.$this->info['id'].'" AND `uid` = "'.$this->atacks[$id]['uid2'].'" LIMIT 1');
				//�������������� ����� 25% �� �������� �� ���
				
				
				//$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] += floor($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['s6']/4 /*+ $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hod_minmana']*/ );
				//$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] += floor($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['s6']/4 /*+ $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hod_minmana']*/ );
				//
				//$this->users[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'];
				//$this->users[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'] = $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'];
				//
				
				$last_yrn = array(
					1  => $last_yrn[1],
					2  => $last_yrn[2],
					10 => $this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_yron'],
					20 => $this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_yron']
				);
				
				$last_yrn[100] = floor($last_yrn[10]-$last_yrn[1]);
				$last_yrn[200] = floor($last_yrn[20]-$last_yrn[2]);				
				
				/*
				$this->users[$this->uids[$this->atacks[$id]['uid1']]]['last_yrn'] = $last_yrn[100];
				$this->users[$this->uids[$this->atacks[$id]['uid2']]]['last_yrn'] = $last_yrn[200];				
				unset($last_yrn);
				*/
				
				if( $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] > 0 ) {
					$this->save_stats( $this->yhod_user( $this->atacks[$id]['uid2'], $this->atacks[$id]['uid1'], $this->stats[$this->uids[$this->atacks[$id]['uid1']]]['yhod'] ) );
				}elseif( $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] > 0 ) {
					$this->save_stats( $this->yhod_user( $this->atacks[$id]['uid1'], $this->atacks[$id]['uid2'], $this->stats[$this->uids[$this->atacks[$id]['uid2']]]['yhod'] ) );
				}
				mysql_query('UPDATE `stats` SET
				
					`hpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow'].'",
					`mpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid1']]]['mpNow'].'",
					`tactic1` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic1'].'",
					`tactic2` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic2'].'",
					`tactic3` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic3'].'",
					`tactic4` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic4'].'",
					`tactic5` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic5'].'",
					`tactic6` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic6'].'",
					`tactic7` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['tactic7'].'",
					
					`enemy` 	  = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'].'",
					`battle_yron` = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_yron'].'",
					`last_hp` 	  = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['last_hp'].'",
					`battle_exp`  = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['battle_exp'].'",
					`priems_z`  = "'.$this->users[$this->uids[$this->atacks[$id]['uid1']]]['priems_z'].'"
					
				WHERE `id` = "'.$this->atacks[$id]['uid1'].'" LIMIT 1');
				//
				mysql_query('UPDATE `stats` SET
				
					`hpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow'].'",
					`mpNow` = "'.$this->stats[$this->uids[$this->atacks[$id]['uid2']]]['mpNow'].'",
					`tactic1` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic1'].'",
					`tactic2` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic2'].'",
					`tactic3` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic3'].'",
					`tactic4` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic4'].'",
					`tactic5` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic5'].'",
					`tactic6` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic6'].'",
					`tactic7` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['tactic7'].'",
					
					`enemy` 	  = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'].'",
					`battle_yron` = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_yron'].'",
					`last_hp` 	  = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['last_hp'].'",
					`battle_exp`  = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['battle_exp'].'",
					`priems_z`  = "'.$this->users[$this->uids[$this->atacks[$id]['uid2']]]['priems_z'].'"
					
				WHERE `id` = "'.$this->atacks[$id]['uid2'].'" LIMIT 1');
				//
				$this->priemsRazmenMomentEnd($id,$at);
				//
				//������� ��������� ������ ������ tr_life_user
				/*if( floor($this->stats[$this->uids[$this->atacks[$id]['uid1']]]['hpNow']) < 1 ) {
					$sp = mysql_query('SELECT * FROM `eff_users` WHERE `tr_life_user` = "'.$this->atacks[$id]['uid1'].'" AND `delete` = "0" LIMIT 50');
					while( $pl = mysql_fetch_array($sp) ) {
						$pl['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pl['v2'].'" LIMIT 1'));
						$this->delPriem($pl,$this->users[$this->uids[$this->atacks[$id]['uid1']]],3,$this->atacks[$id]['uid2']);
						echo 1;
					}
					echo 2;
				}
				if( floor($this->stats[$this->uids[$this->atacks[$id]['uid2']]]['hpNow']) < 1 ) {
					$sp = mysql_query('SELECT * FROM `eff_users` WHERE `tr_life_user` = "'.$this->atacks[$id]['uid2'].'" AND `delete` = "0" LIMIT 50');
					while( $pl = mysql_fetch_array($sp) ) {
						$pl['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$pl['v2'].'" LIMIT 1'));
						$this->delPriem($pl,$this->users[$this->uids[$this->atacks[$id]['uid2']]],3,$this->atacks[$id]['uid1']);
						echo 3;
					}
					echo 4;
				}*/
				//
				//�������� ����� ������ \ �������
				$j = 1; $jn = 1;
				while($j<=2) {
					$eff = $this->stats[$this->uids[$this->atacks[$id]['uid'.$j]]]['effects'];
					$i = 0; 
					while($i<count($eff)) {
						if(isset($eff[$i])) {
							if($eff[$i]['timeUse']==77 && $eff[$i]['hod']>-1) {
								$eff[$i]['hod']--;
								$eff[$i]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i]['v2'].'" LIMIT 1'));
								if( round($eff[$i]['priem']['minmana'] * $eff[$i]['x']) != 0 ) {
									//�������� ���� � ���� ��� ��������
									$priem->minMana($eff[$i]['user_use'],round($eff[$i]['priem']['minmana'] * $eff[$i]['x']));
									if( $this->stats[$this->uids[$eff[$i]['user_use']]]['mpNow'] <= 0 ) {
										$eff[$i]['hod'] = 0;
									}
								}
								if( strripos( $eff[$i]['data'] , 'minprocmanahod') == true ) {									
									$pvr = array(
										'x1'	=> 0,
										'x2'	=> 0,
										'd'		=> '',
										'i'		=> 0,
										'uid'	=> $eff[$i]['uid'],
										'color' => '',
										'color2'=> '',
										'effx'	=> '',
										'x'		=> $eff[$i]['name']
									);
									$pvr['d'] = explode('|',$eff[$i]['data']);
									while( $pvr['i'] < count($pvr['d']) ) {
										if( isset($pvr['d'][$pvr['i']])) {
											$pvr['d1'] = explode('=',$pvr['d'][$pvr['i']]);
											if( $pvr['d1'][0] == 'minprocmanahod' ) {
												$pvr['d1'] = explode('x',$pvr['d1'][1]);
												$pvr['x1'] = $pvr['d1'][0];
												$pvr['x2'] = $pvr['d1'][1];
											}
										}
										$pvr['i']++;
									}
																		
									$pvr['mp'] = round($this->stats[$this->uids[$pvr['uid']]]['mpAll']/100*rand($pvr['x1'],$pvr['x2']));
									$pvr['mpSee'] = 0;
									$pvr['mpNow'] = floor($this->stats[$this->uids[$pvr['uid']]]['mpNow']);
									$pvr['mpAll'] = $this->stats[$this->uids[$pvr['uid']]]['mpAll'];
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
									if( $pvr['mpSee'] == 0 ) {
										$pvr['mpSee'] = '--';
									}
									
									$btl->stats[$btl->uids[$pvr['uid']]]['mpNow'] = $pvr['mpNow'];
									$btl->users[$btl->uids[$pvr['uid']]]['mpNow'] = $pvr['mpNow'];				
									mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->stats[$btl->uids[$pvr['uid']]]['mpNow'].'" WHERE `id` = "'.$pvr['uid'].'" LIMIT 1');
									
									$pvr['text'] = $this->addlt(1 , 21 , $this->users[$this->uids[$pvr['uid']]]['sex'] , NULL);	
									$pvr['text2'] = '{tm1} '.$pvr['text'].' �� <font Color=#006699><b>'.$pvr['mpSee'].'</b></font> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (����)';
									$this->priemAddLog( $id, 1, 2, $pvr['uid'], 0,
										'<font color^^^^#'.$pvr['color2'].'>'.$pvr['x'].'</font>',
										$pvr['text2'],
										($this->hodID + 0)
									);
									//echo '[��������������� '.round(rand($pvr['x1'],$pvr['x2'])).'% ����.]';
									unset($pvr);
								}
								/*
								$re = $priem->hodUsePriem($eff[$i],$eff[$i]['priem']);
								if(isset($re['hod'])) {
									$eff[$i]['hod'] = $re['hod'];
								}
								*/
								if(isset($this->rehodeff[$eff[$i]['id']])) {
									$eff[$i]['hod'] = $this->rehodeff[$eff[$i]['id']];
								}
								if($eff[$i]['hod']>0) {
									$this->stats[$this->uids[$this->atacks[$id]['uid'.$j]]]['effects']['hod'] = $eff[$i]['hod'];
									mysql_query('UPDATE `eff_users` SET `hod` = "'.$eff[$i]['hod'].'" WHERE `id` = "'.$eff[$i]['id'].'" LIMIT 1');
								}else{
									//������� �����
									if($eff[$i]['v2']>0) {												
										if($j==1) {
											$jn = 2;
										}else{
											$jn = 1;	
										}
										$this->delPriem($eff[$i],$this->users[$this->uids[$this->atacks[$id]['uid'.$j]]],3,$this->atacks[$id]['uid'.$jn]);
									}
								}
							}elseif($eff[$i]['timeUse']==77 && $eff[$i]['hod']==-2) {
								$eff[$i]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i]['v2'].'" LIMIT 1'));
								$priem->hodUsePriem($eff[$i],$eff[$i]['priem']);
							}else{
								$eff[$i]['priem'] = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.$eff[$i]['v2'].'" LIMIT 1'));
								if( isset($eff[$i]['priem']['minmana']) && round($eff[$i]['priem']['minmana'] * $eff[$i]['x']) != 0 ) {
									//�������� ���� � ���� ��� ��������
									$priem->minMana($eff[$i]['user_use'],round($eff[$i]['priem']['minmana'] * $eff[$i]['x']));
									if( $this->stats[$this->uids[$eff[$i]['user_use']]]['mpNow'] <= 0 ) {
										$eff[$i]['hod'] = 0;
										if(isset($this->rehodeff[$eff[$i]['id']])) {
											$eff[$i]['hod'] = $this->rehodeff[$eff[$i]['id']];
										}
										if($eff[$i]['v2']>0) {												
											if($j==1) {
												$jn = 2;
											}else{
												$jn = 1;	
											}
											$this->delPriem($eff[$i],$this->users[$this->uids[$this->atacks[$id]['uid'.$j]]],3,$this->atacks[$id]['uid'.$jn]);
										}
									}
								}elseif($eff[$i]['timeUse'] > 100 && $eff[$i]['hod']>-1 && $c['effz'] > 0) {
									
									$eff[$i]['hod']--;
									if(isset($this->rehodeff[$eff[$i]['id']])) {
										$eff[$i]['hod'] = $this->rehodeff[$eff[$i]['id']];
									}
									if($eff[$i]['hod']>0) {
										$this->stats[$this->uids[$this->atacks[$id]['uid'.$j]]]['effects']['hod'] = $eff[$i]['hod'];
										mysql_query('UPDATE `eff_users` SET `hod` = "'.$eff[$i]['hod'].'" WHERE `id` = "'.$eff[$i]['id'].'" LIMIT 1');
									}else{
										//������� �����
										if($eff[$i]['v2']>0) {												
											if($j==1) {
												$jn = 2;
											}else{
												$jn = 1;	
											}
											$this->delPriem($eff[$i],$this->users[$this->uids[$this->atacks[$id]['uid'.$j]]],3,$this->atacks[$id]['uid'.$jn]);
										}
									}
								}
								//
							}
						}
						$i++;
					}
					$j++;
				}
				//				
				//
				//���������
				if( $c['propsk_die'] > 0 && $this->info['razdel'] == 0 && $this->info['dn_id'] == 0 && $this->info['izlom'] == 0 ) {
					$cn1 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle_out` WHERE `battle` = "'.$this->info['id'].'" AND `uid1` = "'.$this->atacks[$id]['uid1'].'" LIMIT 1'));			
					$cn2 = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle_out` WHERE `battle` = "'.$this->info['id'].'" AND `uid1` = "'.$this->atacks[$id]['uid2'].'" LIMIT 1'));
					if( $cn1[0] > 3 ) {
						//
						$pvr['text2'] = '{tm1} �������� {u1} ��� ���� ��-�� �������� ���������� ��������� ����.';
						$this->priemAddLog( $id, 1, 2, $this->atacks[$id]['uid1'], 0, '',
							$pvr['text2'],
							($this->hodID + 0)
						);
						unset($pvr);
						mysql_query('UPDATE `stats` SET `hpNow` = 0 WHERE `id` = "'.$this->atacks[$id]['uid1'].'" LIMIT 1');
					}
					if( $cn2[0] > $c['propsk_die'] ) {
						$pvr['text2'] = '{tm1} �������� {u1} ��� ���� ��-�� �������� ���������� ��������� ����.';
						$this->priemAddLog( $id, 1, 2, $this->atacks[$id]['uid2'], 0, '',
							$pvr['text2'],
							($this->hodID + 0)
						);
						unset($pvr);
						mysql_query('UPDATE `stats` SET `hpNow` = 0 WHERE `id` = "'.$this->atacks[$id]['uid2'].'" LIMIT 1');
					}
				}
				//
				//��������� �������� ����������
				if( $u->info['id'] == $this->atacks[$id]['uid1'] ) {
					$u->info['enemy'] = $this->users[$this->uids[$this->atacks[$id]['uid1']]]['enemy'];
				}
				if( $u->info['id'] == $this->atacks[$id]['uid2'] ) {
					$u->info['enemy'] = $this->users[$this->uids[$this->atacks[$id]['uid2']]]['enemy'];
				}
				//������� ������ �� ����
				mysql_query('DELETE FROM `battle_act` WHERE ( `uid1` = "'.$this->atacks[$id]['uid1'].'" AND `uid2` = "'.$this->atacks[$id]['uid2'].'" ) OR
				( `uid2` = "'.$this->atacks[$id]['uid1'].'" AND `uid1` = "'.$this->atacks[$id]['uid2'].'" )');

				//$this->a_restart_stats($this->atacks[$id]['uid1'],1);
				//$this->a_restart_stats($this->atacks[$id]['uid2'],1);

				unset($old_s1,$old_s2);
				unset($this->ga[$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->ga[$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']]);
				unset($this->ag[$this->atacks[$id]['uid1']][$this->atacks[$id]['uid2']],$this->ag[$this->atacks[$id]['uid2']][$this->atacks[$id]['uid1']]);
				unset($this->atacks[$id]);
				mysql_query('DELETE FROM `battle_act` WHERE `id` = "'.$id.'" LIMIT 1');
				//
				//���������� ������ ��������������
				/*
				$this->stats[$this->uids[$this->atacks[$id]['uid1']]] = $old_s1;
				$this->stats[$this->uids[$this->atacks[$id]['uid2']]] = $old_s2;
				*/
				unset($old_s1,$old_s2);
				//
			}
		}
		
	//���������� ������ 
		public function save_stats( $uid ) {
				mysql_query('UPDATE `stats` SET
				
					`hpNow` = "'.$this->stats[$this->uids[$uid]]['hpNow'].'",
					`mpNow` = "'.$this->stats[$this->uids[$uid]]['mpNow'].'",
					`tactic1` = "'.$this->users[$this->uids[$uid]]['tactic1'].'",
					`tactic2` = "'.$this->users[$this->uids[$uid]]['tactic2'].'",
					`tactic3` = "'.$this->users[$this->uids[$uid]]['tactic3'].'",
					`tactic4` = "'.$this->users[$this->uids[$uid]]['tactic4'].'",
					`tactic5` = "'.$this->users[$this->uids[$uid]]['tactic5'].'",
					`tactic6` = "'.$this->users[$this->uids[$uid]]['tactic6'].'",
					`tactic7` = "'.$this->users[$this->uids[$uid]]['tactic7'].'",
					
					`enemy` 	  = "'.$this->users[$this->uids[$uid]]['enemy'].'",
					`battle_yron` = "'.$this->users[$this->uids[$uid]]['battle_yron'].'",
					`last_hp` 	  = "'.$this->users[$this->uids[$uid]]['last_hp'].'",
					`battle_exp`  = "'.$this->users[$this->uids[$uid]]['battle_exp'].'",
					`priems_z`  = "'.$this->users[$this->uids[$uid]]['priems_z'].'"
					
				WHERE `id` = "'.$uid.'" LIMIT 1');
		}
		
	//����������� ��
		public function hpSee($now,$all,$type = 1) {
			$r = '['.$now.'/'.$all.']';
			if($all > 10000) {
				$type = 2;
			}
			if($type == 1) {
				
			}elseif($type == 2) {
				$p1 = floor($now/$all*100);
				$r = '['.$p1.'/100%]';
			}
			return $r;
		}
		
	//������� ���
		public function addFlog($t,$u1,$u2)
		{
				$vLog = '';
				if(isset($this->info[$this->uids[$u1]]['id']))
				{
					$vLog .= 'time1='.time().'||s1='.$this->users[$this->uids[$u1]]['id']['sex'].'||t1='.$this->users[$this->uids[$u1]]['team'].'||login1='.$this->users[$this->uids[$u1]]['login'].'||';
				}
				if(isset($this->info[$this->uids[$u2]]['id']))
				{
					$vLog .= 'time2='.time().'||s2='.$this->users[$this->uids[$u2]]['sex'].'||t2='.$this->users[$this->uids[$u2]]['team'].'||login2='.$this->users[$this->uids[$u2]]['login'].'';
				}
				$vLog = rtrim($vLog,'||');
				$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				$mas1['text'] = $t;
				$this->add_log($mas1);
		}
		
							
	//������� ��� ���
		public function lookLog()
		{
			global $c,$u,$log_text;
				$js = ''; $pll = 0;
				if($_POST['idlog']<1){ $_POST['idlog'] = 0; }
				//��������� ���������� ��� � ���������
				//mysql_query('INSERT INTO `battle_logs_save` SELECT * FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'" AND `id_hod` <= '.($this->hodID-2).'');
				//mysql_query('DELETE FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'" AND `id_hod` <= '.($this->hodID-2).'');
				$this->saveLogs( $this->info['id'] , 'AND `id_hod` <= '.($this->hodID-2).'' );
				//
				$sp = mysql_query('SELECT 
				`id`,`type`,`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zona2`,`zonb1`,`zonb2`
				FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'" AND `id` > '.mysql_real_escape_string($_POST['idlog']).' AND `id_hod` > '.($this->hodID-15).' LIMIT 100');
				//$sp = mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$this->info['id'].'" AND `id` > '.mysql_real_escape_string($_POST['idlog']).' AND `id_hod` > '.($this->hodID-10).' LIMIT 150');
				$jin = 0; $forYou2 = 0;
				while($pl = mysql_fetch_array($sp))
				{
					$jin++;
					if((true == false) && ($pl['type']==1 || $pl['type']==6)) {
						/*$dt = explode('||',$pl['vars']);
						$i = 0; $d = array();
						while($i<count($dt))
						{
							$r = explode('=',$dt[$i]);
							if($r[0]!='')
							{
								$d[$r[0]] = $r[1];
							}
							$i++;
						}
						//������� ����
						$rt = $pl['text'];
						
						$forYou = '';	 $forYou2 = 0;				
						if(($d['login1'] == $u->info['login2'] && $d['login1'] != '') || ($d['login1'] == $u->info['login'] && $d['login1'] != '')) {
							$forYou = 'forYou'; $forYou2 = 1;
						}elseif(($d['login2'] == $u->info['login2'] && $d['login2'] != '') || ($d['login2'] == $u->info['login'] && $d['login2'] != '')) {
							$forYou = 'forYou'; $forYou2 = 2;
						}
						
						//�������� ������
						$rt = str_replace('{u1}','<span onClick=\"top.chat.addto(\''.$d['login1'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$d['login1'].'\',event,\'chat\'); return false;\" class=\"CSSteam'.$d['t1'].'\">'.$d['login1'].'</span>',$rt);
						$rt = str_replace('{u2}','<span onClick=\"top.chat.addto(\''.$d['login2'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$d['login2'].'\',event,\'chat\'); return false;\" class=\"CSSteam'.$d['t2'].'\">'.$d['login2'].'</span>',$rt);
						$rt = str_replace('{pr}','<b>'.$d['prm'].'</b>',$rt);
						$rt = str_replace('^^^^','=',$rt);
						$rt = str_replace('{tm1}','<span class=\"date '.$forYou.'\">'.date('H:i',$d['time1']).'</span>',$rt);
						$rt = str_replace('{tm2}','<span class=\"date '.$forYou.'\">'.date('H:i',$d['time2']).'</span>',$rt);
						$rt = str_replace('{tm3}','<span class=\"date '.$forYou.'\">'.date('d.m.Y H:i',$d['time1']).'</span>',$rt);
						$rt = str_replace('{tm4}','<span class=\"date '.$forYou.'\">'.date('d.m.Y H:i',$d['time2']).'</span>',$rt);
						
						$k01 = 1;
						$zb1 = array(1=>0,2=>0,3=>0,4=>0,5=>0);
						$zb2 = array(1=>0,2=>0,3=>0,4=>0,5=>0);
						
						if($d['bl2']>0)
						{
							$b11 = 1;
							$b12 = $d['bl1'];
							while($b11<=$d['zb1'])
							{
								$zb1[$b12] = 1;
								if($b12>=5 || $b12<0)
								{
									$b12 = 0;
								}
								$b12++;
								$b11++;
							}
						}
						
						if($d['bl2']>0)
						{
							$b11 = 1;
							$b12 = $d['bl2'];
							while($b11<=$d['zb2'])
							{
								$zb2[$b12] = 1;
								if($b12>=5 || $b12<0)
								{
									$b12 = 0;
								}
								$b12++;
								$b11++;
							}
						}	
						
						while($k01<=5)
						{
							$zns01 = ''; $zns02 = '';
							$j01 = 1;
							while($j01<=5)
							{
								$zab1 = '0'; $zab2 = '0';
								if($j01==$k01)
								{
									$zab1 = '1';
									$zab2 = '1';
								}
						
								$zab1 .= $zb1[$j01];
								$zab2 .= $zb2[$j01];
									
								$zns01 .= '<img src=\"http://img.xcombats.com/i/zones/'.$d['t1'].'/'.$d['t2'].''.$zab1.'.gif\">';
								$zns02 .= '<img src=\"http://img.xcombats.com/i/zones/'.$d['t2'].'/'.$d['t1'].''.$zab2.'.gif\">';
								$j01++;
							}
							$rt = str_replace('{zn1_'.$k01.'}',$zns01,$rt);
							$rt = str_replace('{zn2_'.$k01.'}',$zns02,$rt);
							$k01++;
						}
	
						$j = 1;
						while($j<=21)
						{
							//������ R - ����� 1
							$r = $log_text[$d['s1']][$j];
							$k = 0;
							while($k<=count($r))
							{
								if(isset($log_text[$d['s1']][$j][$k]))
								{
									$rt = str_replace('{1x'.$j.'x'.$k.'}',$log_text[$d['s1']][$j][$k],$rt);
								}
								$k++;
							}
							//������ R - ����� 2
							$r = $log_text[$d['s2']][$j];
							$k = 0;
							while($k<=count($r))
							{
								if(isset($log_text[$d['s2']][$j][$k]))
								{
									$rt = str_replace('{2x'.$j.'x'.$k.'}',$log_text[$d['s2']][$j][$k],$rt);
								}
								$k++;
							}						
							$j++;
						}
						
						//��������� ������, ���� ����
						$rt = str_replace('{u1}','<span onClick=\"top.chat.addto(\''.$d['login1'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$d['login1'].'\',event,\'chat\'); return false;\" class=\"CSSteam'.$d['t1'].'\">'.$d['login1'].'</span>',$rt);
						$rt = str_replace('{u2}','<span onClick=\"top.chat.addto(\''.$d['login2'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$d['login2'].'\',event,\'chat\'); return false;\" class=\"CSSteam'.$d['t2'].'\">'.$d['login2'].'</span>',$rt);
						$rt = str_replace('{pr}','<b>'.$d['prm'].'</b>',$rt);
						$rt = str_replace('^^^^','=',$rt);
						$rt = str_replace('{tm1}','<span class=\"date '.$forYou.'\">'.date('H:i',$d['time1']).'</span>',$rt);
						$rt = str_replace('{tm2}','<span class=\"date '.$forYou.'\">'.date('H:i',$d['time2']).'</span>',$rt);
						$rt = str_replace('{tm3}','<span class=\"date '.$forYou.'\">'.date('d.m.Y H:i',$d['time1']).'</span>',$rt);
						$rt = str_replace('{tm4}','<span class=\"date '.$forYou.'\">'.date('d.m.Y H:i',$d['time2']).'</span>',$rt);
						
						//��������� ��������
						$pl['text'] = $rt;*/					
					}else{
						$rt = $pl['text'];
						//$rt = str_replace('^^^^','=',$rt);
						$pl['vars'] = str_replace('^^^^','rvnO',$pl['vars']);
						$rt = str_replace('{tm1}','<span class=\"date {fru}\">'.date('H:i',time()).'</span>',$rt);
						$rt = str_replace('{tm2}','<span class=\"date {fru}\">'.date('H:i',time()).'</span>',$rt);
						$rt = str_replace('{tm3}','<span class=\"date {fru}\">'.date('d.m.Y H:i',time()).'</span>',$rt);
						$rt = str_replace('{tm4}','<span class=\"date {fru}\">'.date('d.m.Y H:i',time()).'</span>',$rt);
						$pl['text'] = $rt;
					}
					unset($rt);
					if($pll < $pl['id']) {
						$pll = $pl['id'];
					}
					$js = 'add_log('.$pl['id'].','.$forYou2.',"'.$pl['text'].'",'.$pl['id_hod'].',0,0,"'.str_replace('"','&quot;',$pl['vars']).'");'.$js;
				}
				$js .= 'id_log='.$pll.';';
				//$js = bzcompress($js,9);
			return $js;
		}
		
	//��������� � ���
		public function add_log($mass)
		{
			if($mass['time']!='' && $mass['text'] != '')
			{
				//mysql_query('LOCK TABLES battle_logs WRITE');
				
				$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$mass['text'].'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
				
				//mysql_query('UNLOCK TABLES');
			}
		}
		
	///�����������
	public function get_comment(){
		$boycom = array ('� �������� �� �����.','� �� ���, � ������ ��� ������?','� �� ����� ��������� ������� �� ������?','�, ���� �����-��, �� ���� ��������� � ������? �� � ����! ����!','� ����� ��� ���� ������ �����.','� � ����� ����� �� �������� �����������. ��� ����� ��� �� �����������','� ���� �� ����� ����� ��������...','� ��� � ����� � �������� ���...','� �� � ��������� �� �������?','� �� ������, ����� ��� �� ����� ������!?','� �� ����� ��� ����� ������������ � ���������?','� ����-��, ��� �������:','� ��-�� ���� �� ���������� �������?','� ���� ����, �� ��� ��������� �� ������','� ��� ��������� ���� �� ���������� �� ���������� ������?!','������ ��� �� �����. �� �� �� ������?','��� ������������...','������ ����!','������ �� �� ������� ���������!','��� ����� ������� � ���, �� ����� �� ����� �������!','����� � ����� ��������� ������������.','����� 5 ����� �������, � �������, ������ ������� � 20-�� ������ ������...','���. � ��� ������ �� ����.','� ����� ������, ���-�� �������?','�� ����, � ������ �����?','������� ��� ������ ����... ������� � ��� ����� ������� ��������: ...','��� ������, ��� ������� ������� ���� �� ����?','��� �� ��� ������ �������, � �� ���� ��� �������','��� ������� �� � ���� � ��� ��� ����� ����� ����. �� �� ������ �� ����������... � �� ������, ��� �� ����� �������, ������ �� ������ �� ������','�� �� ����� ����� ���������������!','�� ��� ��� ������������? ��������, ����� ����� � ���� ���� ����� ��������� ������.','�� �� �����! ������ �������!','���� ��������!','��, ���� �� � ���� ��� ����������, �� ������� �� ����������� ������ `�� ���` ','�� ���� ��� ����?!','������� �������! �� ���� ��� ������� ������������.','������� ��������� ������� ���������. �? � �� ��� ��� ������� ����� ����� �������.','������� ��� ��������!','����, ���������� ������... ��!.. ���, ���� ����� �� ��������.','���� ��� ����� ������������, �� ����� �� ������!','���� �� � ���� ���� ������-�������, � �� � �� �������...','���� �� ���-�� ������� ������, �� ����� ������� :)','���������� �� �����.','����� ��� ������ ����� - ��� ������ ����� ���������!!!','���! ����! �����! �������!','�� ����� ��� ���� � ���� ����������!','������ ������ � ������������ �������� ������� �����? ��� � ����� ����� ������� ������� � ���� ����. ������ ������ � ������� ����, ����������� � ���.','����� ���� ��� ������ ���� � �����. ������ ����� ������� ������.','� �������� ����� ��� ����������...','��������� ���������...','����� ���!!!','���!? ��� �����?!','��� ��� ����� ������?','��������, ����...','����� ��������� ������������� �������������.','������� ����!','����� ����, ��� ���� - ����.','�����, �� ��� ���� ���� ����� �� ������ ��������, � � �����, ��������.','��� ��� ����� ��� ������!','�����, ��������-���� ��������� ���???','����� ����� � ������ �������, � ����� �������.','�� � ��� ���� �������� �����? ��� ���������� ������� � ���������!','��, ������ �� ��� �������� ������!','���� ������ ���� ������, ������ ���������� ������...','�� ����� ������� ������ ��������� �����. ����� ����������!','���! �� ���� �����! �... ����� ��������, ��� ����� �� ������.','���, �� ������ ���� �����, ������ �� ���� �����?','���, � ����������� ��� ��������������!','�� ����� ����������!','�� ������ �� �����, �� ���... �� ����, �� ���... ��� ����� �� ����� ����� ����� ��������?!','�� � ��� �� ���� ������ ������� �����?','�� � ����� � ���. �� ����� ������ ������� ������ �����.','��, ��� �� ��..? �� ��������. ���� ������, ��� �� ��� ������� �������.','������... ���� ���� ����.','��������!!!.... ������...','���! ������� ���� ��� �� ������.','���������! �������� �������, ��� �� �������������!','��� ��� ����???','������� ����...��� ���-�� ����������.','��, � ���������� � � ����...','�� �� �� ���������� ���� �� �� �������!','��-�����, ����-�� ������ ������������.','������� ��� ������, �� ������� �� ��� �� ��������.','���� ��� ��� ��������, ������� ���� ������?','��� ����� � ����� ���������� ��������-�������� ������.','�������, �� ������ �� � ���� ��� �� ������.','����������� ��� �����!','������� ��� ������, �� ������� �� ��� �� ��������.','������� ��� ��� �����... ���, � ����� �� ����������!','��������� ���� ����� ��� ����������� ����������...','������� ������� ������ ��!','������ ������!','������ ��� ���������','������ ��� �� ���, � ����� ��������������.','������� ������ ������, � ����� ������ � ����� �����.','��� ��� ���� ����� ���-������ �������.','��� �� ��� ��� ����� �����������!','� ���� � �������� ����� ��� �� ������ ����������','������ ���������...','�� ��, ����� �������!','������!! ���� �, ��� �� �������� ����� �������...','������ ���� ��� �� ���� ���� �������! ��� �� ����� �� ������.','��������, ���������� ����������!','������, ��������, ���� �� ������?','������, ��� � ���� �������� ������ ��� � ��� ����, � �� � �� � ���� � ���������� ������� �������� ��.','������� ���!','������� ����!','�����-���������!','��� �� ��� �� ������ �������?! ������� ����������!','��� � ��� ��������, ��� ����� �� � ���������','��� ���� �����-�� ����������� ��� �������...','��� �� ���������, � �� �����! ������ ������!','��� �� ���, ��� �������������� ��������.','��� �������� �����','��� � ��� ���� ���� `�` ?','��� ���� �����-�� ����������� ��� �������...','� �� ������������, - ����� ������.','� �� ������� ��������. � ����������� �� ������ ������ :)','� ��������, � �������, � �����, � ������. � ��� ��? �� ����-�� ������?!','� ���� ���� �������, �� �� ����...','(�������� ��������) � ��� �� ������� �����... �� ���� ���������!','<�������� ��������> ����� ��� � ���� <�������� ��������> ����� � <�������� ��������> � <�������� ��������>','<�������� ��������> ��������� ������');
		$act_com = array();
		if(rand(1,6) == rand(1,6))
		{
			$txt = '{tm1} <i>�����������: '.$boycom[rand(0,count($boycom)-1)].'</i>';
									
			$vLog = 'time1='.time().'';									
			$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');									
			$mas1['text'] = $txt;
			$this->add_log($mas1);
			
		} else {
			return false;
		}
	}
	
	//������ ���� ����� �� ������
		public function weaponTx($item)
		{
			global $u;
			$tp = 0;
				if(!isset($item['id'])) {
					/*$i = 1;
					while( $i <= 4 ) {
						$itm['tya'.$i] = 1;
						$i++;
					}*/
				}
				$itm = $u->lookStats($item['data']);
				//������� ����
				$t[2] = round(0+$itm['tya2']);
				//������� ����
				$t[1] = round(0+$itm['tya1']);
				//�������� ����
				$t[3] = round(0+$itm['tya3']);
				//������� ����
				$t[4] = round(0+$itm['tya4']);
				//���� ����
				$t[5] = round(0+$itm['tym1']);
				//���� ����
				$t[7] = round(0+$itm['tym3']);
				//���� �����
				$t[8] = round(0+$itm['tym4']);
				//���� �������
				$t[6] = round(0+$itm['tym2']);
				//���� �����
				$t[9] = round(0+$itm['tym5']);
				//���� ����
				$t[10] = round(0+$itm['tym6']);
				//���� ����� ������
				$t[11] = round(0+$itm['tym7']);

				//������ �������� �������
				
				$inr = array();
				$i = 1;
				while( $i <= 11 ) {
					if( $t[$i] > 0 ) {
						$j = 0;
						while( $j < $t[$i] ) {
							//if(isset($itm['tya'.$i]) && $itm['tya'.$i] > 0 && $i < 5 && $itm['tya'.$i] > 0) {
								//$inr[] = $i;
							//}elseif(isset($itm['tym'.($i-4)]) && $itm['tym'.($i-4)] > 0 && $i > 4 && $itm['tym'.($i-4)] > 0) {
								//$inr[] = $i;
							//}
							if( isset($itm['tya'.$i]) && $itm['tya'.$i] > 0 ) {
								$inr[] = $i;
								$rk .= '*'.$i.'='.$j.'%*|';
							}else{
								$rk .= ''.$i.'='.$j.'%|';
							}
							$j++;
						}
						//if( $u->info['admin'] > 0 ) {
							
						//}
					}
					$i++;
				}
				//shuffle($inr);
				$tp = $inr[rand(0,count($inr)-1)];
				if( !isset($itm['tya'.$tp]) || $itm['tya'.$tp] < 1 ) {
					if(isset($item['id'])) {
						$this->error($u->info['id'],'battle::weaponTx. ��� ������: id ��������: -'.$item['id'].'-b<a href="/logs.php?log='.$this->info['id'].'" target="_blank">'.$this->info['id'].'</a>.(��� �����: tya'.$tp.'['.$itm['tya'.$tp].']).(��������� ���� �����: '.$rk.').(���������� ��������� ����: '.count($inr).')');
					}
				}
			//}else{
			//	$tp = 12;
			//}	
			return $tp;
		}
		
	//������� ������
		public function error($login,$t) {
			mysql_query('INSERT INTO `chat_system` (`text`,`city`,`login`,`to`,`type`,`new`,`time`) VALUES ("<font color=#cb0000>'.mysql_real_escape_string($t).'</font>","capitalcity","<font color=green>�������</font>","'.$login.'","3","1","'.time().'")');
		}
		
		
	//������ ����� �� ������
		public function weaponAt($item,$st,$x)
		{
			$tp = 0;
			$tp20 = 0;
			if(isset($item['id']))
			{
				$itm = $this->lookStats($item['data']);				
				//�������� ������ �����
				$min = $itm['sv_yron_min']+$itm['yron_min']+$st['minAtack'];
				$max = $itm['sv_yron_max']+$itm['yron_max']+$st['maxAtack'];
				if($x!=0)
				{
					/*
					������� - 60% ���� � 40% ��������. 
					������� - 70% ���� 20% �������� � 20% ��������. 
					�������� - 100% ����. 
					������� - 60% ���� � 40% ��������.
					*/
					//��� �����: 0 - ��� �����, 1 - �������, 2 - �������, 3 - ��������, 4 - �������, 5 - �����, 6 - ������, 7 - ����, 8 - �����, 9 - ����, 10 - ����, 11 - �����
					if($x==1)
					{
						//�������
						$wst = $st['s1']*0.35+$st['s2']*0.35;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 1;
					}elseif($x==2)
					{
						//�������
						$wst = $st['s1']*0.45+$st['s2']*0.12+$st['s3']*0.13;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 2;
					}elseif($x==3)
					{
						//��������
						$wst = $st['s1']*0.65;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 3;
					}elseif($x==4)
					{
						//�������
						$wst = $st['s1']*0.45+$st['s3']*0.25;
						$min += 5+(ceil($wst*1.4)/1.25)+$st['minAtack'];
						$max += 7+(ceil(0.4+$min/0.9)/1.25)+$st['maxAtack'];
						$tp20 = 4;
					}elseif($x>=5 && $x<=22)
					{
						//���� ����� � ����� ������
						$wst = $st['s1']*0.01+$st['s2']*0.01+$st['s3']*0.01+$st['s5']*0.06;
						$min += 3+(ceil($wst*1.4)/2.25)+$st['minAtack'];
						$max += 5+(ceil(0.4+$min/0.9)/2.25)+$st['maxAtack'];
						$tp20 = 5;
					}else{
						//��� ����������� �����
						
					}
					
					$wst = ($st['s1']*0.02+$st['s2']*0.02+$st['s3']*0.05);
					$min1 = -2+ceil($wst*1.4)/1.25;
					$max2 = 4+ceil(0.4+$min1/0.9)/1.25;	
					
					$min =	round(($min+$min1));	
					$max =	round(($max+$max1));		
				}
				$tp = rand(($min+$max)/3.5,(($min+$max)/3.5 + (($min+$max)/3.5)/100*7));
			}
			return $tp;
		}
		
	//������ ����� �� ������
		public function weaponAt22($item,$st,$x)
		{
			$tp = 0;
			$tp20 = 0;
			if(isset($item['id']))
			{
				$itm = $this->lookStats($item['data']);				
				//�������� ������ �����
				$min = $itm['sv_yron_min']+$itm['yron_min']+$st['minAtack'];
				$max = $itm['sv_yron_max']+$itm['yron_max']+$st['maxAtack'];
			}
			return array($min,$max);
		}

		public function domino($itm) {
			$r = 0;
			//0 - inOdet , 1 - class , 2 - class-point , 3 - anti_class , 4 - antic_lass-point	 , 5 - level , 6 level_u
			//15 ���������
			$clss = array(
				1   => 100, //����
				2   => 80,  //������
				3   => 150, //������
				14  => 100, //���
				5   => 200, //�����
				7   => 50,  //����
				17  => 50,  //�������
				10  => 80,  //������
				11  => 80,  //������
				12  => 80,  //������
				9   => 100, //������
				8   => 100, //������
				4   => 50,  //������
				16  => 80,  //������
				6   => 50   //����
			);
			$r += $clss[$itm[0]];
			if($itm[10] > 0) {
				//���.�������
				if($itm[10] < 500) {
					//�� ��������
					$r += $clss[$itm[0]]*4;
				}else{
					//��������
					$r += $clss[$itm[0]]*8;
				}
			}
			return $r;
		}
		
		public function adomino($itm) {
			$r = 0;
			//0 - inOdet , 1 - class , 2 - class-point , 3 - anti_class , 4 - antic_lass-point	 , 5 - level , 6 level_u
			//15 ���������
			$clss = array(
				1   => 80, //����
				2   => 60,  //������
				3   => 130, //������
				14  => 80, //���
				5   => 180, //�����
				7   => 30,  //����
				17  => 30,  //�������
				10  => 50,  //������
				11  => 50,  //������
				12  => 50,  //������
				9   => 80, //������
				8   => 80, //������
				4   => 30,  //������
				16  => 50,  //������
				6   => 30   //����
			);
			$r += $clss[$itm[0]];
			return $r;
		}
		
		public function domino_lvl($r,$lvl,$lvl_itm) {
			if($lvl < $lvl_itm) {
				$r = $r*((50-$lvl+$lvl_itm)/100);
				//������ �����, ���� ���� ���������� ������ �� ������� ���.����� \ ����������, ���� ����������� ���������
				$r = ceil($r);
			}
			return $r;
		}
		/*
		public $bal = array(
			//������ ����� ������ X - Y
			// ���� , ������ , ���� , ������� , ��������� , ���
			'����'   	=> array(0,50,90,00,90,50,50), // ����
			'������' 	=> array(0,00,50,90,00,50,70), // ������
			'����'   	=> array(0,90,00,50,90,30,50), // ����
			'�������'   => array(0,00,90,00,50,50,50), // �������
			'���������' => array(0,50,30,90,00,50,70), // ���������
			'���' 		=> array(0,90,30,00,90,50,50)  // ���
		);
		*/
		
		/*
		public function domino_all($v1,$v2,$d1,$d2) {
			// �������� ������ 1 , �������� ������ 2 , ���� 1 , ���� 2
			//������ �������
			$mx = 0;
			$cs = array(NULL,'����','������','����','�������','���������','���');
			$r = array(
				0 => 0,
				'����'=>array(),
				'����'=>array(),
				'������'=>array(),
				'���������'=>array(),
				'�������'=>array(),
				'���'=>array()
			);
			$i = 0;
			while($i <= 7) {
				if(isset($v1[$i]) || isset($v2[$i])) {
					$r[$cs[$i]] = round(((1+($v1[$i]*1.3)-$v2[$i]+$d1[$i]+$d2[$i])/1300),2);
					if($v1[$i] > $mx) {
						$mx = $v1[$i];
						$r[0] = $cs[$i];
						$r[1] = $i;
					}
				}
				$i++;
			}
			return $r;
		}*/
 
		public function yronLvl($lvl1,$lvl2) {
			$r = array(
				1  => array(0,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				2  => array(0,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				3  => array(0,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				4  => array(0,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200),
				5  => array(0,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200,200,200),
				6  => array(0,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200,200,200),
				7  => array(0,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200,200,200),
				8  => array(0,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200,200,200),
				9  => array(0,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200,200,200),
				10 => array(0,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200,200,200),
				11 => array(0,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600,400,200),
				12 => array(0,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000,800,600),
				13 => array(0,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400,1200,1000),
				14 => array(0,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800,1600,1400),
				15 => array(0,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200,2000,1800),
				16 => array(0,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600,2400,2200),
				17 => array(0,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000,2800,2600),
				18 => array(0,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400,3200,3000),
				19 => array(0,7400,7200,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800,3600,3400),
				20 => array(0,7800,7600,7400,7200,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200,4000,3800),
				21 => array(0,8200,8000,7800,7600,7400,7200,7000,6800,6600,6400,6200,6000,5800,5600,5400,5200,5000,4800,4600,4400,4200)
			);
			$r = floor($r[$lvl1][$lvl2]/100);
			$r = 0;
			return $r;
		}
	//����	
	//������ ������
		public function zago($v) {
			if($v > 1000) {
				$v = 1000;
			}
			$r = (1-( pow(0.5, ($v/250) ) ))*100;		
			return $r;
		}
	//������ ������ (�����)
		public function zmgo($v) {
			//if($v > 1000) {
			//	$v = 1000;
			//}
			$r = (1-( pow(0.5, ($v/250) ) ))*100;		
			return $r;
		}
		
	/* ������ ����� */
		public function yrn_old($st1, $st2, $u1, $u2, $level, $level2, $type, $min_yron, $max_yron, $min_bron, $max_bron, $vladenie, $power_yron, $power_krit, $zashita, $ozashita, $proboi, $weapom_damage, $weapom_min, $weapom_max, $za_proc, $zm_proc, $zashitam , $ozashitam , $wp_type , $witm ) {

			global $u;
			
			//$min_bron += $min_bron/100*35;
			//$max_bron += $max_bron/100*35;
			
			$lvl = $level;
			if($level2 > $lvl) {
				$lvl = $level2;
			}
			
			$poglot = 0; //����������� ����
			
			if( $st2['pog'] > 0 ) {
				//$poglot = $st2['pog'];
			}
			if( $st2['pog2'] > 0 && $st2['pog2'] > $st2['pog'] ) {
				//$poglot = $st2['pog2'];
			}
			
			//$power_krit += 10; //���� 20
			
			//��������
			if( $zashita < 1 ) {
				$zashita = 0;
			}
			
			if( $min_yron < 1 ) {
			//	$min_yron = 1;
			}
			
			if( $max_yron < 1 ) {
			//	$max_yron = 1;
			}
			
			//��������� ��� ���������
			$r = array('min' => 0, 'max' => 0, 'type' => $type);
			$p = array(
				'Y'		=> 0,
				'B'		=> array(0 => 0, 1 => 0, 'rnd' => false),
				'L'		=> $level,
				'W'		=> array($weapom_min, $weapom_max, 'rnd' => false), //����������� ���� //������������ ���� ����������
				'U'		=> $vladenie, //�������� �������
				'M'		=> $power_yron, //�������� �����
				'K'		=> $power_krit, //�������� �����
				'S'		=> 0,  //������� ������ �� ����.����
				'T'		=> 1,   //��. ������
				'iT'	=> 1,	//�������� ���. ��. ������
				/*
					(S) - ������� ����� ������ �� ���������� ����
					�������: S = ���� * 0,6 + �������� * 0,4
					�������: S = ���� * 0,7 + �������� * 0,2 + �������� * 0,2
					��������: S = ���� * 1
					�������: S = ���� * 0,6 + �������� * 0,4
				*/
			);
			
			//���������� 1.33 ��� �������� � 1.00 ��� ����� ����
			if ($weapom_damage == 0) { $p['T'] = 1; }elseif($weapom_damage == 1) { $p['T'] = 2.33;$p['iT'] = 2.33;}
			//������
			if( $lvl <= 7 ) {
				//������ 0-5 ������
				if( $wp_type == 18 ) {
					//�������
					$p['U'] = $st1['a1'] * 1.00; //�������
					//$p['S'] = $st1['s1'] * 0.60 + $st1['s2'] * 0.40; 
					$p['S'] = $st1['s1'] * 0.1 + $st1['s2'] * 0.2; 
					//if( rand(0,100) < 50 ) { $r['type'] = 1; }
				}elseif( $wp_type == 19 ) {
					//������
					$p['U'] = $st1['a2'] * 1.00; //������
					//$p['S'] = $st1['s1'] * 0.70 + $st1['s2'] * 0.20 + $st1['s3'] * 0.20;
					$p['S'] = $st1['s1'] * 0.1 + $st1['s2'] * 0.1 + $st1['s3'] * 0.1;
					//if( rand(0,100) < 50 ) { $r['type'] = 2; }
				}elseif( $wp_type == 20 ) {
					//������
					$p['U'] = $st1['a3'] * 1.00; //������
					//$p['S'] = $st1['s1'] * 1.00;
					$p['S'] = $st1['s1'] * 0.5;
					//if( rand(0,100) < 50 ) { $r['type'] = 3; }
				}elseif( $wp_type == 21 ) {
					//����
					$p['U'] = $st1['a4'] * 1.00; //����
					//$p['S'] = $st1['s1'] * 0.60 + $st1['s3'] * 0.40;
					$p['S'] = $st1['s1'] * 0.001 + $st1['s3'] * 0.001;
					//if( rand(0,100) < 50 ) { $r['type'] = 4; }
				}elseif( $wp_type == 22 ) { //��� �����
					$p['U'] = $st1['a5'] * 1.00; //�������
					$p['S'] = ($st1['s1']*0.33);
				}else{
					$p['S'] = ($st1['s1']*0.8);
				}
			}else{
				//������ 6++ ������
				if( $wp_type == 18 ) {
					//�������
					$p['U'] = $st1['a1'] * 1.00; //�������
					$p['S'] = $st1['s1'] * 0.40 + $st1['s2'] * 0.30; 
					//if( rand(0,100) < 50 ) { $r['type'] = 1; }
				}elseif( $wp_type == 19 ) {
					//������
					$p['U'] = $st1['a2'] * 1.00; //������
					$p['S'] = $st1['s1'] * 0.7 + $st1['s2'] * 0.20 + $st1['s3'] * 0.2;
					//if( rand(0,100) < 50 ) { $r['type'] = 2; }
				}elseif( $wp_type == 20 ) {
					//������
					$p['U'] = $st1['a3'] * 1.00; //������
					$p['S'] = $st1['s1'] * 1.00;
					//if( rand(0,100) < 50 ) { $r['type'] = 3; }
				}elseif( $wp_type == 21 ) {
					//����
					$p['U'] = $st1['a4'] * 1.00; //����
					$p['S'] = $st1['s1'] * 0.60 + $st1['s3'] * 0.40;
					//if( rand(0,100) < 50 ) { $r['type'] = 4; }
				}elseif( $wp_type == 22 ) { //��� �����
					$p['U'] = $st1['a5'] * 1.00; //�������
					$p['S'] = ($st1['s1'] * 0.33);
				}else{
					$p['S'] = ($st1['s1']*0.8);
				}
			}
			
			$witm = $this->lookStats($witm['data']);
			/*
			$p['S'] = 0;
			$p['S'] += $witm['tya1'] * ($st1['s1'] * 0.60 + $st1['s2'] * 0.40) / 100;
			$p['S'] += $witm['tya2'] * ($st1['s1'] * 0.70 + $st1['s2'] * 0.20 + $st1['s3'] * 0.20) / 100;
			$p['S'] += $witm['tya3'] * ($st1['s1'] * 0.80) / 100;
			$p['S'] += $witm['tya4'] * ($st1['s1'] * 0.60 + $st1['s3'] * 0.40) / 100;
			$i = 1;
			while( $i < 8 ) {
				$p['S'] += $witm['tym'.$i] * ($st1['s1'] * 0.50 + $st1['s2'] * 0.50) / 100;
				$i++;
			}
			$p['S'] = round($p['S']/100*80);
			*/
			
			
			//
			
			//
			
			if( $wp_type == 22 ) {
			//	$p['S'] = round($p['S'] / 2);
			}
			
			if( $r['type'] == 3 /*&& $wp_type == 20*/ ) {
			//	$p['S'] = round($p['S'] / 100 * 80);
			}
			
			//$p['S'] = round($p['S']/100*80);
						
			//
						
			//������ ���� �����			
				//�������
						if($r['type'] == 1) {		//$p['S'] = $st1['s1'] * 0.60 + $st1['s2'] * 0.40; 
													//$p['U'] = $st1['a1'] * 1.00; //�������
				//�������
					}elseif($r['type'] == 2) {		//$p['S'] = $st1['s1'] * 0.70 + $st1['s2'] * 0.40 + $st1['s3'] * 0.40;
													//$p['U'] = $st1['a2'] * 1.00; //������
				//��������
					}elseif($r['type'] == 3) {		//$p['S'] = $st1['s1'] * 1.20;
													//$p['U'] = $st1['a3'] * 1.00; //������
				//�������
					}elseif($r['type'] == 4) {		//$p['S'] = $st1['s1'] * 0.60 + $st1['s3'] * 0.60;
													//$p['U'] = $st1['a4'] * 1.00; //����
				//����������
					}elseif($r['type'] >= 5){		/*$p['S'] = $st1['s1'] * 0.5 + $st1['s2'] * 0.5*/;	
													$p['U'] = $st1['mg'.($r['type']-4)] * 1.00; //������
													$p['Mage'] = true;					
					}else {
													//$p['S'] = ($st1['s1']*1);
													//$p['U'] = 0; // ��� ������(����� ������������ 
					}
			
				//
						
				//$s['S'] += $st1['s1']*0.15;				
				/*if( $r['type'] > 0 && $r['type'] != 12 ) {
					$p['SuS']  = $st1['s1'] * 0.60 + $st1['s2'] * 0.40;
					$p['SuS'] += $st1['s1'] * 0.70 + $st1['s2'] * 0.20 + $st1['s3'] * 0.20;
					$p['SuS'] += $st1['s1'] * 1.10;
					$p['SuS'] += $st1['s1'] * 0.60 + $st1['s3'] * 0.40;
					$p['SuS'] = round( $p['SuS']/4 , 2 );
					
					if( $p['S'] < $p['SuS'] ) {
						//$p['S'] = $p['SuS'];
					}
				}*/
					
					//$p['S'] = $p['S'];
			//����������� ����������	
				//
				if( $poglot > 0 ) {
					/*$min_bron = 0;
					$max_bron = 0;
					$r['za'] = round($r['za']/100*67);
					$r['zm'] = round($r['za']/100*67);*/
				}
				//	
				$r['bron'] = array($min_bron, $max_bron); //����� ���� ���� ����
				$r['bron']['rnd'] = rand($r['bron'][0],$r['bron'][1]);
				//$r['bron']['rnd'] += $r['bron']['rnd']*0.01; //������������ �������� ����� +1%
				
				$r['za'] = $zashita; //������ �� �����
				$r['oza'] = $ozashita; //����������� ������ �� �����
				
				$r['zm'] = $zashitam; //������ �� �����
				$r['ozm'] = $ozashitam; //����������� ������ �� �����
				
				
			//��������� �������	
				
				if($p['S'] > 0) {
					$p['B'][0] = round((ceil($p['S']*1)/1.25)+2);
				}else{
					$p['B'][0] = round((ceil($st1['s1']*1)/1.25)+2);
				}
				$p['B'][1] = round(5+ceil(0.4+($p['B'][0]-0)/0.9)/1.25);			
				
				//$p['B'][0] = 5;
				//$p['B'][1] = 9;
				$p['B']['rnd'] = rand($p['B'][0],$p['B'][1]);
				
				//$r['bron']['rnd'] += round($r['bron']['rnd']*0.15);
				
				$p['W']['rnd'] = rand($p['W'][0],$p['W'][1]);
				
				//$p['U'] = $p['U']/2;
				
			//������� ����
				$p['Mstart'] = 0; //��������� ��������
				//$r['min']  = (($p['B'][0]+$p['L']+$p['S']+$p['W']['rnd']+1)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				//$r['max']  = (($p['B'][1]+$p['L']+$p['S']+$p['W']['rnd']+1)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				
				if( $wp_type == 22 ) {
					//���� �����, �� �������� ��������� �� 75%
					//����
					$p['M'] = $p['M']*0.1;
				}
				
				$r['min']  = (($p['B'][0]+$p['S']+$weapom_min)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				$r['max']  = (($p['B'][1]+$p['S']+$weapom_max)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
								
				$r['minm'] = $r['min']*0.05;
				$r['maxm'] = $r['max']*0.05;
				
				if( $wp_type == 22 ) {
					//������
					$r['min'] = $r['min']/2;	
					$r['max'] = $r['max']/2;
				}

			//����������� ����
				//�����
				$r['Kmin'] = $r['min'];
				$r['Kmax'] = $r['max'];
											
				/*$r['min'] += $weapom_min;
				$r['max'] += $weapom_max;
				$r['Kmin'] += $weapom_min*2;
				$r['Kmax'] += $weapom_max*2;*/
				
			//����������� �������� �����
				$r['min_'] = floor($r['min']*0.05);
				$r['max_'] = floor($r['max']*0.05);
				$r['Kmin_'] = floor($r['Kmin']*0.05);
				$r['Kmax_'] = floor($r['Kmax']*0.05);
																																		
			//����������� ������				
				$r['ozash_rnd'] = $r['oza'][$r['type']][1]; /*rand($r['oza'][$r['type']][0],$r['oza'][$r['type']][1]);*/
				
				if($r['ozash_rnd'] > 80) { $r['ozash_rnd'] = 80; }
				if($r['ozash_rnd'] < 0) { $r['ozash_rnd'] = 0; }
				

				$r['ozash_rnd'] = 100-$r['ozash_rnd'];				
				
				//$p['iT']
				$r['min'] = floor($r['min']*$p['iT'] + $weapom_min);
				$r['max'] = floor($r['max']*$p['iT'] + $weapom_max);
				$r['Kmin'] = floor($r['Kmin']*$p['iT'] + $weapom_min*2);
				$r['Kmax'] = floor($r['Kmax']*$p['iT'] + $weapom_max*2);
				$r['min_'] = floor($r['min_']*$p['iT'] + $weapom_min);
				$r['max_'] = floor($r['max_']*$p['iT'] + $weapom_max);
				$r['Kmin_'] = floor($r['Kmin_']*$p['iT'] + $weapom_min*2);
				$r['Kmax_'] = floor($r['Kmax_']*$p['iT'] + $weapom_max*2);
								
				$r['min'] -= ($r['min']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				$r['max'] -= ($r['max']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				
				//$r['Kmin'] -= ($r['Kmin']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				//$r['Kmax'] -= ($r['Kmax']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
								
				$r['bRND'] = $p['B']['rnd'];
					
				$r['min'] += $min_yron;
				$r['max'] += $max_yron;
					
				//
				
				if( $r['minm'] > $r['min'] ) {
					$r['min'] = $r['minm'];
				}
				if( $r['maxm'] > $r['max'] ) {
					$r['max'] = $r['maxm'];
				}
				
				$r['Kmin'] = ceil( ( 2 * ( 1 + $p['K']/100 ) ) * $r['min']);
				$r['Kmax'] = ceil( ( 2 * ( 1 + $p['K']/100 ) ) * $r['max']);
				//$r['Kmin'] = ceil( ( $r['min'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmin'] = ceil( ( $r['min'] ) * ( 2 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] ) * ( 2 + $p['K']/100 ));
				//
				$r['Kminm'] = $r['Kmin']*0.05;
				$r['Kmaxm'] = $r['Kmax']*0.05;
				
			//������ �����
				//��� ��������
				if( $r['type'] < 5) {
					$r['min_abron'] = round($r['min']*0.01);
					$r['max_abron'] = round($r['max']*0.01);
					
					if($proboi != 0) {
						$r['bron']['rndold'] = $r['bron']['rnd'];
						//$r['bron']['rnd'] = floor($r['bron']['rnd']/100*(100-$proboi));
						//if( $r['bron']['rnd'] < $r['bron'][0] ) {
							$r['bron']['rnd'] = $r['bron'][0];
						//}
						if( round($r['min']*0.45) < $r['bron']['rnd'] ) {
							$r['bron']['rnd'] = round($r['min']*0.55);
						}
						//
						$r['yrnrz'] = $r['max']-$r['min'];
						if( $r['yrnrz'] < 0 ) {
							$r['yrnrz'] = -$r['yrnrz'];
						}
					}
										
					//��� �����
					$r['Kmin_abron'] = round($r['Kmin']/1);
					$r['Kmax_abron'] = round($r['Kmax']/1);
					//����� ����������� � �����
					$r['Kmin'] -= $r['bron']['rnd']; //���� �������� �� 2
					$r['Kmax'] -= $r['bron']['rnd']; //���� �������� �� 2
					
					$r['min'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					$r['max'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					
					/*if($r['min'] < $r['min_']) {
						$r['min'] = $r['min_'];
					}
					if($r['max'] < $r['max_']) {
						$r['max'] = $r['max_'];
					}*/	

					if($proboi != 0) {
						//   $r['yrnrz']
						//   $r['bron']['rndold']						
					}
				}
			
				$r['min'] = floor($r['min']);
				$r['max'] = floor($r['max']);
				$r['Kmin'] = floor($r['Kmin']);
				$r['Kmax'] = floor($r['Kmax']);
				
			//������ ������ (�� ����� 80%)
				if( $r['type'] < 5 ) {
					$r['min'] = floor($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = floor($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = floor($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = floor($r['Kmax']/100*(100-$this->zago($r['za'])));
				}elseif( $r['type'] == 12 ) {
					$r['min'] = floor($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = floor($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = floor($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = floor($r['Kmax']/100*(100-$this->zago($r['za'])));
				}else{
					$r['min'] = floor($r['min']/100*(100-$this->zmgo($r['zm'])));
					$r['max'] = floor($r['max']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmin'] = floor($r['Kmin']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmax'] = floor($r['Kmax']/100*(100-$this->zmgo($r['zm'])));
				}
				
				if( $r['Kminm'] > $r['Kmin'] ) {
					$r['Kmin'] = $r['Kminm'];
				}
				if( $r['Kmaxm'] > $r['Kmax'] ) {
					$r['Kmax'] = $r['Kmaxm'];
				}
																							
				
				//$min_yrn = 33; //%
				if( $wp_type == 18 ) {
					//$min_yrn = 17;
				}
				
				//
				if( $p['M'] > 0 ) {
					//$min_yrn -= $p['M']/100*20;
				}
				//
				if( $p['Mage'] == true && $r['type'] != 1 ) {
					//$min_yrn += 30;
				}
				//
				if( $r['type'] >= 5 ) {
					$min_yrn += $zm_proc;
				}else{
					$min_yrn += $za_proc;
				}
				
				$r['min'] -= floor($r['min']/100*$min_yrn);
				$r['max'] -= floor($r['max']/100*$min_yrn);
				$r['Kmin'] -= floor($r['Kmin']/100*($min_yrn));
				$r['Kmax'] -= floor($r['Kmax']/100*($min_yrn));
				$r['min_'] -= floor($r['min_']/100*$min_yrn);
				$r['max_'] -= floor($r['max_']/100*$min_yrn);
				$r['Kmin_'] -= floor($r['Kmin_']/100*($min_yrn));
				$r['Kmax_'] -= floor($r['Kmax_']/100*($min_yrn));
																	
				/*
				if($r['min'] < round($weapom_min/2)) {
					$r['min'] = round($weapom_min/2);
				}
				if($r['max'] < round($weapom_max/2)) {
					$r['max'] = round($weapom_max/2);
				}
				if($r['Kmin'] < round($weapom_min)) {
					$r['Kmin'] = round($weapom_min);
				}
				if($r['Kmax'] < round($weapom_max)) {
					$r['Kmax'] = round($weapom_max);
				}
				if($r['min_'] < round($weapom_min/2)) {
					$r['min_'] = round($weapom_min/2);
				}
				if($r['max_'] < round($weapom_max/2)) {
					$r['max_'] = round($weapom_max/2);
				}
				if($r['Kmin_'] < round($weapom_min)) {
					$r['Kmin_'] = round($weapom_min);
				}
				if($r['Kmax_'] < round($weapom_max)) {
					$r['Kmax_'] = round($weapom_max);
				}
				*/			
					
				if( $r['Kmin'] < 2 ) {
					$r['Kmin'] = 2;
				}
				if( $r['Kmax'] < 2 ) {
					$r['Kmax'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmin_'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmax_'] = 2;
				}
				
				$r['m_k'] = $r['Kmax'];
					
			return $r;
		}
		
	/* ������ ����� */
		public function yrn2016($st1, $st2, $u1, $u2, $level, $level2, $type, $min_yron, $max_yron, $min_bron, $max_bron, $vladenie, $power_yron, $power_krit, $zashita, $ozashita, $proboi, $weapom_damage, $weapom_min, $weapom_max, $za_proc, $zm_proc, $zashitam , $ozashitam , $wp_type , $witm ) {

			global $u;
			
			//$min_bron += $min_bron/100*35;
			//$max_bron += $max_bron/100*35;
			
			$lvl = $level;
			if($level2 > $lvl) {
				$lvl = $level2;
			}
			
			$poglot = 0; //����������� ����
			
			if( $st2['pog'] > 0 ) {
				//$poglot = $st2['pog'];
			}
			if( $st2['pog2'] > 0 && $st2['pog2'] > $st2['pog'] ) {
				//$poglot = $st2['pog2'];
			}
			
			//$power_krit += 10; //���� 20
			
			//��������
			if( $zashita < 1 ) {
				$zashita = 0;
			}
			
			if( $min_yron < 1 ) {
			//	$min_yron = 1;
			}
			
			if( $max_yron < 1 ) {
			//	$max_yron = 1;
			}
			
			//��������� ��� ���������
			$r = array('min' => 0, 'max' => 0, 'type' => $type);
			$p = array(
				'Y'		=> 0,
				'B'		=> array(0 => 0, 1 => 0, 'rnd' => false),
				'L'		=> $level,
				'W'		=> array($weapom_min, $weapom_max, 'rnd' => false), //����������� ���� //������������ ���� ����������
				'U'		=> $vladenie, //�������� �������
				'M'		=> $power_yron, //�������� �����
				'K'		=> $power_krit, //�������� �����
				'S'		=> 0,  //������� ������ �� ����.����
				'T'		=> 1,   //��. ������
				'iT'	=> 1,	//�������� ���. ��. ������
				/*
					(S) - ������� ����� ������ �� ���������� ����
					�������: S = ���� * 0,6 + �������� * 0,4
					�������: S = ���� * 0,7 + �������� * 0,2 + �������� * 0,2
					��������: S = ���� * 1
					�������: S = ���� * 0,6 + �������� * 0,4
				*/
			);
			
			//���������� 1.33 ��� �������� � 1.00 ��� ����� ����
			if ($weapom_damage == 0) { $p['T'] = 1; }elseif($weapom_damage == 1) { $p['T'] = 2.33;$p['iT'] = 2.33;}
			//������
			if( $lvl < 7 ) {
				//������ 0-6 ������
				if( $wp_type == 18 ) {
					//�������
					$p['U'] = $st1['a1'] * 1.00; //�������
					//$p['S'] = $st1['s1'] * 0.60 + $st1['s2'] * 0.40; 
					$p['S'] = $st1['s1'] * 0.1 + $st1['s2'] * 0.2; 
					//if( rand(0,100) < 50 ) { $r['type'] = 1; }
				}elseif( $wp_type == 19 ) {
					//������
					$p['U'] = $st1['a2'] * 1.00; //������
					//$p['S'] = $st1['s1'] * 0.70 + $st1['s2'] * 0.20 + $st1['s3'] * 0.20;
					$p['S'] = $st1['s1'] * 0.1 + $st1['s2'] * 0.1 + $st1['s3'] * 0.1;
					//if( rand(0,100) < 50 ) { $r['type'] = 2; }
				}elseif( $wp_type == 20 ) {
					//������
					$p['U'] = $st1['a3'] * 1.00; //������
					//$p['S'] = $st1['s1'] * 1.00;
					$p['S'] = $st1['s1'] * 0.5;
					//if( rand(0,100) < 50 ) { $r['type'] = 3; }
				}elseif( $wp_type == 21 ) {
					//����
					$p['U'] = $st1['a4'] * 1.00; //����
					//$p['S'] = $st1['s1'] * 0.60 + $st1['s3'] * 0.40;
					$p['S'] = $st1['s1'] * 0.001 + $st1['s3'] * 0.001;
					//if( rand(0,100) < 50 ) { $r['type'] = 4; }
				}elseif( $wp_type == 22 ) { //��� �����
					$p['U'] = $st1['a5'] * 1.00; //�������
					$p['S'] = ($st1['s1']*0.33);
				}else{
					$p['S'] = ($st1['s1']*0.8);
				}
			}else{
				//������ 7++ ������
				if( $wp_type == 18 ) {
					//�������
					$p['U'] = $st1['a1'] * 0.75; //�������
					$p['S'] = $st1['s1'] * 0.10 + $st1['s2'] * 0.30; 
					//if( rand(0,100) < 50 ) { $r['type'] = 1; }
				}elseif( $wp_type == 19 ) {
					//������
					$p['U'] = $st1['a2'] * 1.00; //������
					$p['S'] = $st1['s1'] * 0.7 + $st1['s2'] * 0.20 + $st1['s3'] * 0.2;
					//if( rand(0,100) < 50 ) { $r['type'] = 2; }
				}elseif( $wp_type == 20 ) {
					//������
					$p['U'] = $st1['a3'] * 1.00; //������
					$p['S'] = $st1['s1'] * 0.80;
					//if( rand(0,100) < 50 ) { $r['type'] = 3; }
				}elseif( $wp_type == 21 ) {
					//����
					$p['U'] = $st1['a4'] * 1.00; //����
					$p['S'] = $st1['s1'] * 0.60 + $st1['s3'] * 0.40;
					//if( rand(0,100) < 50 ) { $r['type'] = 4; }
				}elseif( $wp_type == 22 ) { //��� �����
					$p['U'] = $st1['a5'] * 1.00; //�������
					$p['S'] = ($st1['s1'] * 0.33);
				}else{
					$p['S'] = ($st1['s1']*0.8);
				}
			}
			
			$witm = $this->lookStats($witm['data']);
			/*
			$p['S'] = 0;
			$p['S'] += $witm['tya1'] * ($st1['s1'] * 0.60 + $st1['s2'] * 0.40) / 100;
			$p['S'] += $witm['tya2'] * ($st1['s1'] * 0.70 + $st1['s2'] * 0.20 + $st1['s3'] * 0.20) / 100;
			$p['S'] += $witm['tya3'] * ($st1['s1'] * 0.80) / 100;
			$p['S'] += $witm['tya4'] * ($st1['s1'] * 0.60 + $st1['s3'] * 0.40) / 100;
			$i = 1;
			while( $i < 8 ) {
				$p['S'] += $witm['tym'.$i] * ($st1['s1'] * 0.50 + $st1['s2'] * 0.50) / 100;
				$i++;
			}
			$p['S'] = round($p['S']/100*80);
			*/
			
			
			//
			
			//
			
			if( $wp_type == 22 ) {
			//	$p['S'] = round($p['S'] / 2);
			}
			
			if( $r['type'] == 3 /*&& $wp_type == 20*/ ) {
			//	$p['S'] = round($p['S'] / 100 * 80);
			}
			
			//$p['S'] = round($p['S']/100*80);
						
			//
						
			//������ ���� �����			
				//�������
						if($r['type'] == 1) {		//$p['S'] = $st1['s1'] * 0.60 + $st1['s2'] * 0.40; 
													//$p['U'] = $st1['a1'] * 1.00; //�������
				//�������
					}elseif($r['type'] == 2) {		//$p['S'] = $st1['s1'] * 0.70 + $st1['s2'] * 0.40 + $st1['s3'] * 0.40;
													//$p['U'] = $st1['a2'] * 1.00; //������
				//��������
					}elseif($r['type'] == 3) {		//$p['S'] = $st1['s1'] * 1.20;
													//$p['U'] = $st1['a3'] * 1.00; //������
				//�������
					}elseif($r['type'] == 4) {		//$p['S'] = $st1['s1'] * 0.60 + $st1['s3'] * 0.60;
													//$p['U'] = $st1['a4'] * 1.00; //����
				//����������
					}elseif($r['type'] >= 5){		/*$p['S'] = $st1['s1'] * 0.5 + $st1['s2'] * 0.5*/;	
													$p['U'] = $st1['mg'.($r['type']-4)] * 1.00; //������
													$p['Mage'] = true;					
					}else {
													//$p['S'] = ($st1['s1']*1);
													//$p['U'] = 0; // ��� ������(����� ������������ 
					}
			
				//
						
				//$s['S'] += $st1['s1']*0.15;				
				/*if( $r['type'] > 0 && $r['type'] != 12 ) {
					$p['SuS']  = $st1['s1'] * 0.60 + $st1['s2'] * 0.40;
					$p['SuS'] += $st1['s1'] * 0.70 + $st1['s2'] * 0.20 + $st1['s3'] * 0.20;
					$p['SuS'] += $st1['s1'] * 1.10;
					$p['SuS'] += $st1['s1'] * 0.60 + $st1['s3'] * 0.40;
					$p['SuS'] = round( $p['SuS']/4 , 2 );
					
					if( $p['S'] < $p['SuS'] ) {
						//$p['S'] = $p['SuS'];
					}
				}*/
					
					//$p['S'] = $p['S'];
			//����������� ����������	
				//
				if( $poglot > 0 ) {
					/*$min_bron = 0;
					$max_bron = 0;
					$r['za'] = round($r['za']/100*67);
					$r['zm'] = round($r['za']/100*67);*/
				}
				//	
				$r['bron'] = array($min_bron, $max_bron); //����� ���� ���� ����
				$r['bron']['rnd'] = rand($r['bron'][0],$r['bron'][1]);
				//$r['bron']['rnd'] += $r['bron']['rnd']*0.01; //������������ �������� ����� +1%
				
				$r['za'] = $zashita; //������ �� �����
				$r['oza'] = $ozashita; //����������� ������ �� �����
				
				$r['zm'] = $zashitam; //������ �� �����
				$r['ozm'] = $ozashitam; //����������� ������ �� �����
				
				
			//��������� �������	
				/*if($p['S'] > 0) {
					$p['B'][0] = round((ceil($p['S']*1.4)/1.25)+2);
				}else{
					$p['B'][0] = round((ceil($st1['s1']*1.4)/1.25)+2);
				}
				$p['B'][1] = round(5+ceil(0.4+($p['B'][0]-0)/0.9)/1.25);			
				*/
				$p['B'][0] = round((ceil($p['S']*1.4)/1.25)+2);
				$p['B'][1] = round(5+ceil(0.4+($p['B'][0]-0)/0.9)/1.25);	
				//$p['B'][0] = 5;
				//$p['B'][1] = 9;
				$p['B']['rnd'] = rand($p['B'][0],$p['B'][1]);
				
				//$r['bron']['rnd'] += round($r['bron']['rnd']*0.15);
				
				$p['W']['rnd'] = rand($p['W'][0],$p['W'][1]);
				
				//$p['U'] = $p['U']/2;
				
			//������� ����
				$p['Mstart'] = 0; //��������� ��������
				//$r['min']  = (($p['B'][0]+$p['L']+$p['S']+$p['W']['rnd']+1)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				//$r['max']  = (($p['B'][1]+$p['L']+$p['S']+$p['W']['rnd']+1)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				
				if( $wp_type == 22 ) {
					//���� �����, �� �������� ��������� �� 75%
					//����
					$p['M'] = $p['M']*0.1;
				}
				
				$r['min']  = (($p['B'][0]+$p['S']+$weapom_min)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				$r['max']  = (($p['B'][1]+$p['S']+$weapom_max)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
													
				$r['minm'] = $r['min']*0.05;
				$r['maxm'] = $r['max']*0.05;
				
				if( $wp_type == 22 ) {
					//������
					$r['min'] = $r['min']/2;	
					$r['max'] = $r['max']/2;
				}

			//����������� ����
				//�����
				$r['Kmin'] = $r['min'];
				$r['Kmax'] = $r['max'];
											
				/*$r['min'] += $weapom_min;
				$r['max'] += $weapom_max;
				$r['Kmin'] += $weapom_min*2;
				$r['Kmax'] += $weapom_max*2;*/
				
			//����������� �������� �����
				$r['min_'] = floor($r['min']*0.05);
				$r['max_'] = floor($r['max']*0.05);
				$r['Kmin_'] = floor($r['Kmin']*0.05);
				$r['Kmax_'] = floor($r['Kmax']*0.05);
																																		
			//����������� ������				
				$r['ozash_rnd'] = $r['oza'][$r['type']][1]; /*rand($r['oza'][$r['type']][0],$r['oza'][$r['type']][1]);*/
				
				if($r['ozash_rnd'] > 80) { $r['ozash_rnd'] = 80; }
				if($r['ozash_rnd'] < 0) { $r['ozash_rnd'] = 0; }
				

				$r['ozash_rnd'] = 100-$r['ozash_rnd'];				
				
				//$p['iT']
								
				$r['min'] = floor($r['min']*$p['iT'] + $weapom_min);
				$r['max'] = floor($r['max']*$p['iT'] + $weapom_max);
				$r['Kmin'] = floor($r['Kmin']*$p['iT'] + $weapom_min*2);
				$r['Kmax'] = floor($r['Kmax']*$p['iT'] + $weapom_max*2);
				$r['min_'] = floor($r['min_']*$p['iT'] + $weapom_min);
				$r['max_'] = floor($r['max_']*$p['iT'] + $weapom_max);
				$r['Kmin_'] = floor($r['Kmin_']*$p['iT'] + $weapom_min*2);
				$r['Kmax_'] = floor($r['Kmax_']*$p['iT'] + $weapom_max*2);
								
								
				$r['min'] -= ($r['min']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				$r['max'] -= ($r['max']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				
				//$r['Kmin'] -= ($r['Kmin']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				//$r['Kmax'] -= ($r['Kmax']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
								
				$r['bRND'] = $p['B']['rnd'];
					
				$r['min'] += $min_yron;
				$r['max'] += $max_yron;
					
				//
				
				if( $r['minm'] > $r['min'] ) {
					$r['min'] = $r['minm'];
				}
				if( $r['maxm'] > $r['max'] ) {
					$r['max'] = $r['maxm'];
				}
				
				$r['Kmin'] = ceil( ( 2 * ( 1 + $p['K']/100 ) ) * $r['min']);
				$r['Kmax'] = ceil( ( 2 * ( 1 + $p['K']/100 ) ) * $r['max']);
				//$r['Kmin'] = ceil( ( $r['min'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmin'] = ceil( ( $r['min'] ) * ( 2 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] ) * ( 2 + $p['K']/100 ));
				//
				$r['Kminm'] = $r['Kmin']*0.05;
				$r['Kmaxm'] = $r['Kmax']*0.05;
				
			//������ �����
				//��� ��������
				if( $r['type'] < 5) {
					$r['min_abron'] = round($r['min']*0.01);
					$r['max_abron'] = round($r['max']*0.01);
					
					if($proboi != 0) {
						$r['bron']['rndold'] = $r['bron']['rnd'];
						//$r['bron']['rnd'] = floor($r['bron']['rnd']/100*(100-$proboi));
						//if( $r['bron']['rnd'] < $r['bron'][0] ) {
							$r['bron']['rnd'] = $r['bron'][0];
						//}
						if( round($r['min']*0.45) < $r['bron']['rnd'] ) {
							$r['bron']['rnd'] = round($r['min']*0.55);
						}
						//
						$r['yrnrz'] = $r['max']-$r['min'];
						if( $r['yrnrz'] < 0 ) {
							$r['yrnrz'] = -$r['yrnrz'];
						}
					}
										
					//��� �����
					$r['Kmin_abron'] = round($r['Kmin']/1);
					$r['Kmax_abron'] = round($r['Kmax']/1);
					//����� ����������� � �����
					if( $r['bron']['rnd'] > $r['min'] ) {
						$r['bron']['rnd'] = $r['min'];
					}
					//
					$r['Kmin'] -= $r['bron']['rnd']; //���� �������� �� 2
					$r['Kmax'] -= $r['bron']['rnd']; //���� �������� �� 2
					
					$r['min'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					$r['max'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					
					/*if($r['min'] < $r['min_']) {
						$r['min'] = $r['min_'];
					}
					if($r['max'] < $r['max_']) {
						$r['max'] = $r['max_'];
					}*/	

					if($proboi != 0) {
						//   $r['yrnrz']
						//   $r['bron']['rndold']						
					}
				}
			
				$r['min'] = floor($r['min']);
				$r['max'] = floor($r['max']);
				$r['Kmin'] = floor($r['Kmin']);
				$r['Kmax'] = floor($r['Kmax']);
				
			//������ ������ (�� ����� 80%)
				if( $r['type'] < 5 ) {
					$r['min'] = floor($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = floor($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = floor($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = floor($r['Kmax']/100*(100-$this->zago($r['za'])));
				}elseif( $r['type'] == 12 ) {
					$r['min'] = floor($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = floor($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = floor($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = floor($r['Kmax']/100*(100-$this->zago($r['za'])));
				}else{
					$r['min'] = floor($r['min']/100*(100-$this->zmgo($r['zm'])));
					$r['max'] = floor($r['max']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmin'] = floor($r['Kmin']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmax'] = floor($r['Kmax']/100*(100-$this->zmgo($r['zm'])));
				}
				
				if( $r['Kminm'] > $r['Kmin'] ) {
					$r['Kmin'] = $r['Kminm'];
				}
				if( $r['Kmaxm'] > $r['Kmax'] ) {
					$r['Kmax'] = $r['Kmaxm'];
				}
																							
				
				//$min_yrn = 33; //%
				if( $wp_type == 18 ) {
					//$min_yrn = 17;
				}
				
				//�������
				if($r['type'] == 1) {
					$min_yrn += 50;
				//�������
				}elseif($r['type'] == 2) {
					$min_yrn += 50;
				//��������
				}elseif($r['type'] == 3) {
					$min_yrn += 50;
				//�������
				}elseif($r['type'] == 4) {
					$min_yrn += 70;
				//����������
				}elseif($r['type'] >= 5){	
									
				}else {

				}
				
				//
				if( $p['M'] > 0 ) {
					//$min_yrn -= $p['M']/100*20;
				}
				//
				if( $p['Mage'] == true && $r['type'] != 1 ) {
					//$min_yrn += 30;
				}
				//
				if( $r['type'] >= 5 ) {
					$min_yrn += $zm_proc;
				}else{
					$min_yrn += $za_proc;
				}
				
				$r['min'] -= floor($r['min']/100*$min_yrn);
				$r['max'] -= floor($r['max']/100*$min_yrn);
				$r['Kmin'] -= floor($r['Kmin']/100*($min_yrn));
				$r['Kmax'] -= floor($r['Kmax']/100*($min_yrn));
				$r['min_'] -= floor($r['min_']/100*$min_yrn);
				$r['max_'] -= floor($r['max_']/100*$min_yrn);
				$r['Kmin_'] -= floor($r['Kmin_']/100*($min_yrn));
				$r['Kmax_'] -= floor($r['Kmax_']/100*($min_yrn));
				
				if($r['type'] == 4) {
					$r['Kmin'] -= floor($r['Kmin']/100*10);
					$r['Kmax'] -= floor($r['Kmax']/100*10);
					$r['Kmin_'] -= floor($r['Kmin_']/100*10);
					$r['Kmax_'] -= floor($r['Kmax_']/100*10);
				}
				
				$r['min'] += $st1['maxAtack'];
				$r['max'] += $st1['maxAtack'];
				$r['Kmin'] += $st1['maxAtack']*2;
				$r['Kmin'] += $st1['maxAtack']*2;
				
				/*$r['min_'] += $st1['maxAtack'];
				$r['max_'] += $st1['maxAtack'];
				$r['Kmin_'] += $st1['maxAtack']*2;
				$r['Kmin_'] += $st1['maxAtack']*2;*/
				
																	
				/*
				if($r['min'] < round($weapom_min/2)) {
					$r['min'] = round($weapom_min/2);
				}
				if($r['max'] < round($weapom_max/2)) {
					$r['max'] = round($weapom_max/2);
				}
				if($r['Kmin'] < round($weapom_min)) {
					$r['Kmin'] = round($weapom_min);
				}
				if($r['Kmax'] < round($weapom_max)) {
					$r['Kmax'] = round($weapom_max);
				}
				if($r['min_'] < round($weapom_min/2)) {
					$r['min_'] = round($weapom_min/2);
				}
				if($r['max_'] < round($weapom_max/2)) {
					$r['max_'] = round($weapom_max/2);
				}
				if($r['Kmin_'] < round($weapom_min)) {
					$r['Kmin_'] = round($weapom_min);
				}
				if($r['Kmax_'] < round($weapom_max)) {
					$r['Kmax_'] = round($weapom_max);
				}
				*/			
					
				if( $r['Kmin'] < 2 ) {
					$r['Kmin'] = 2;
				}
				if( $r['Kmax'] < 2 ) {
					$r['Kmax'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmin_'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmax_'] = 2;
				}
				
				$r['m_k'] = $r['Kmax'];
					
			return $r;
		}
		
	/* ������ ����� */
		public function yrn($st1, $st2, $u1, $u2, $level, $level2, $type, $min_yron, $max_yron, $min_bron, $max_bron, $vladenie, $power_yron, $power_krit, $zashita, $ozashita, $proboi, $weapom_damage, $weapom_min, $weapom_max, $za_proc, $zm_proc, $zashitam , $ozashitam , $wp_type , $witm ) {

			global $u;
			
			//��������
			if( $zashita < 1 ) {
				$zashita = 0;
			}
			
			if( $min_yron < 1 ) {
				$min_yron = 1;
			}
			
			if( $max_yron < 1 ) {
				$max_yron = 1;
			}
			
			if( $type == '' ) {
				$type = 12;
			}
			
			//��������� ��� ���������
			$r = array('min' => 0, 'max' => 0, 'type' => $type);
			$p = array(
				'Y'		=> 0,
				'B'		=> array(0 => 0, 1 => 0, 'rnd' => false),
				'L'		=> $level,
				'W'		=> array($weapom_min, $weapom_max, 'rnd' => false), //����������� ���� //������������ ���� ����������
				'U'		=> $vladenie, //�������� �������
				'M'		=> $power_yron, //�������� �����
				'K'		=> $power_krit, //�������� �����
				'S'		=> 0,  //������� ������ �� ����.����
				'T'		=> 1,   //��. ������
				'iT'	=> 1,	//�������� ���. ��. ������
				/*
					(S) - ������� ����� ������ �� ���������� ����
					�������: S = ���� * 0,3 + �������� * 0,7 
					�������: S = ���� * 0,5 + �������� * 0,25 + �������� * 0,25 
					��������: S = ���� * 1 
					�������: S = ���� * 0,3 + �������� * 0,7 
				*/
			);
			
			$p['M'] = $p['M'] * 0.67;
			$p['K'] = $p['K'] * 0.67;
			
			//���������� 1.33 ��� �������� � 1.00 ��� ����� ����
			if ($weapom_damage == 0) { $p['T'] = 1; }elseif($weapom_damage == 1) { $p['T'] = 2.33;$p['iT'] = 2.33;}	
					
			$witm = $this->lookStats($witm['data']);
									
			//������ ���� �����			
				//�������
					if($r['type'] == 1) {		$p['S'] = $st1['s1'] * 0.60 + $st1['s2'] * 0.40; 
												$p['U'] = $st1['a1'] * 0.50; //�������
				//�������
				}elseif($r['type'] == 2) {		$p['S'] = $st1['s1'] * 0.70 + $st1['s2'] * 0.20 + $st1['s3'] * 0.20;
												$p['U'] = $st1['a2'] * 1.00; //������
				//��������
				}elseif($r['type'] == 3) {		$p['S'] = $st1['s1'] * 0.60;
												$p['U'] = $st1['a3'] * 1.00; //������
				//�������
				}elseif($r['type'] == 4) {		$p['S'] = $st1['s1'] * 0.60 + $st1['s3'] * 0.40;
												$p['U'] = $st1['a4'] * 0.50; //����
				//����������
				}elseif($r['type'] >= 5 && $r['type'] < 12 ){		$p['S'] = $st1['s1'] * 0.5 + $st1['s2'] * 0.5;	
												$p['U'] = $st1['mg'.($r['type']-4)] * 1.00; //������
												$p['Mage'] = true;					
				}else {
												$p['S'] = 0;
												$p['U'] = 0; // ��� ������(����� ������������ 
				}
			
				//	
				$r['bron'] = array($min_bron, $max_bron); //����� ���� ���� ����
				$r['bron']['rnd'] = rand($r['bron'][0],$r['bron'][1]);
				//$r['bron']['rnd'] += $r['bron']['rnd']*0.01; //������������ �������� ����� +1%
				
				$r['za'] = $zashita; //������ �� �����
				$r['oza'] = $ozashita; //����������� ������ �� �����
				
				$r['zm'] = $zashitam; //������ �� �����
				$r['ozm'] = $ozashitam; //����������� ������ �� �����
				
				
				/*
	//������� ����
				r.B[0] = $('#v1').val() * 0.51;
				r.B[1] = r.B[0] + 3.73;	
				r.B[0] = Math.ceil(r.B[0]);
				r.B[1] = Math.floor(r.B[1]);	
				if( r.B[0] < 0 ) { r.B[0] = 0; }
				if( r.B[1] < 0 ) { r.B[1] = 0; }	
				r.B[0] += 5;
				r.B[1] += 6;
				//
				*/
				
			//��������� �������	 (���������)
				$p['B'][0] = $st1['s1']*0.51;
				$p['B'][1] = $p['B'][0] + 3.73;
				$p['B'][0] = ceil($p['B'][0]);
				$p['B'][1] = floor($p['B'][1]);
				$p['B'][0] += 5;
				$p['B'][1] += 6;	

				$p['B']['rnd'] = rand($p['B'][0],$p['B'][1]);				
				$p['W']['rnd'] = rand($p['W'][0],$p['W'][1]);
				
			//������� ����
				$p['Mstart'] = 0; //��������� ��������	
				if( $r['type'] == 12 ) {
					$r['min']  = ($p['B'][0]+$weapom_min)*$p['T']*(1+$p['M']/100);
					$r['max']  = ($p['B'][1]+$weapom_max)*$p['T']*(1+$p['M']/100);
				}else{
					$r['min']  = (($p['B'][0]+$p['S']+$weapom_min)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
					$r['max']  = (($p['B'][1]+$p['S']+$weapom_max)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				}
													
				$r['minm'] = $r['min']*0.13;
				$r['maxm'] = $r['max']*0.13;
				
				if( $wp_type == 22 ) {
					//������
					$r['min'] = $r['min']/2;	
					$r['max'] = $r['max']/2;
				}

			//����������� ����
				//�����
				$r['Kmin'] = $r['min'];
				$r['Kmax'] = $r['max'];
											
				/*$r['min'] += $weapom_min;
				$r['max'] += $weapom_max;
				$r['Kmin'] += $weapom_min*2;
				$r['Kmax'] += $weapom_max*2;*/
				
			//����������� �������� �����
				$r['min_'] = floor($r['min']*0.13);
				$r['max_'] = floor($r['max']*0.13);
				$r['Kmin_'] = floor($r['Kmin']*0.13);
				$r['Kmax_'] = floor($r['Kmax']*0.13);
																																		
			//����������� ������				
				//$r['ozash_rnd'] = $r['oza'][$r['type']][1]; /*rand($r['oza'][$r['type']][0],$r['oza'][$r['type']][1]);*/
				
				//if($r['ozash_rnd'] > 80) { $r['ozash_rnd'] = 80; }
				//if($r['ozash_rnd'] < 0) { $r['ozash_rnd'] = 0; }
				

				//$r['ozash_rnd'] = 100-$r['ozash_rnd'];				
				
				//$p['iT']
								
				$r['min'] = floor($r['min']*$p['iT'] + $weapom_min);
				$r['max'] = floor($r['max']*$p['iT'] + $weapom_max);
				$r['Kmin'] = floor($r['Kmin']*$p['iT'] + $weapom_min*2);
				$r['Kmax'] = floor($r['Kmax']*$p['iT'] + $weapom_max*2);
				$r['min_'] = floor($r['min_']*$p['iT'] + $weapom_min);
				$r['max_'] = floor($r['max_']*$p['iT'] + $weapom_max);
				$r['Kmin_'] = floor($r['Kmin_']*$p['iT'] + $weapom_min*2);
				$r['Kmax_'] = floor($r['Kmax_']*$p['iT'] + $weapom_max*2);
								
								
				//$r['min'] -= ($r['min']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				//$r['max'] -= ($r['max']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				
				//$r['Kmin'] -= ($r['Kmin']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				//$r['Kmax'] -= ($r['Kmax']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
								
				$r['bRND'] = $p['B']['rnd'];
					
				$r['min'] += $min_yron;
				$r['max'] += $max_yron;
					
				//
				
				if( $r['minm'] > $r['min'] ) {
					$r['min'] = $r['minm'];
				}
				if( $r['maxm'] > $r['max'] ) {
					$r['max'] = $r['maxm'];
				}
				
				$r['Kmin'] = ceil( ( 2 + ( 0 + $p['K']/100 ) ) * $r['min']);
				$r['Kmax'] = ceil( ( 2 + ( 0 + $p['K']/100 ) ) * $r['max']);
				
				//$r['Kmin'] = ceil( ( 1 + ( 1 + $p['K']/100 ) ) * $r['min']);
				//$r['Kmax'] = ceil( ( 1 + ( 1 + $p['K']/100 ) ) * $r['max']);
				
				//$r['Kmin'] = ceil( ( $r['min'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmin'] = ceil( ( $r['min'] ) * ( 2 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] ) * ( 2 + $p['K']/100 ));
				//
				$r['Kminm'] = $r['Kmin']*0.05;
				$r['Kmaxm'] = $r['Kmax']*0.05;
				
			//������ �����
				//��� ��������
				if( $r['type'] < 5 || $r['type'] == 12 ) {
					$r['min_abron'] = round($r['min']*0.05);
					$r['max_abron'] = round($r['max']*0.05);
					
					if($proboi != 0) {
						$r['bron']['rndold'] = $r['bron']['rnd'];
						$r['bron']['rnd'] = $r['bron'][0];
						//if( round($r['min']*0.45) < $r['bron']['rnd'] ) {
						//	$r['bron']['rnd'] = round($r['min']*0.55);
						//}
						//
						/*
						$r['yrnrz'] = $r['max']-$r['min'];
						if( $r['yrnrz'] < 0 ) {
							$r['yrnrz'] = -$r['yrnrz'];
						}
						*/
					}
										
					//����� ����������� � �����
					//if( $r['bron']['rnd'] > $r['min'] ) {
					//	$r['bron']['rnd'] = $r['min'];
					//}
					//
					
					$r['Kmin'] -= $r['bron']['rnd']; //���� �������� �� 2
					$r['Kmax'] -= $r['bron']['rnd']; //���� �������� �� 2
					
					$r['min'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					$r['max'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					
					if($proboi != 0) {
						//   $r['yrnrz']
						//   $r['bron']['rndold']						
					}
				}
			
				$r['min'] = round($r['min']);
				$r['max'] = round($r['max']);
				$r['Kmin'] = round($r['Kmin']);
				$r['Kmax'] = round($r['Kmax']);
				
			//������ ������ (�� ����� 80%)
				if( $r['type'] < 5 ) {
					$r['min'] = round($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = round($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = round($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = round($r['Kmax']/100*(100-$this->zago($r['za'])));
				}elseif( $r['type'] == 12 ) {
					$r['min'] = round($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = round($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = round($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = round($r['Kmax']/100*(100-$this->zago($r['za'])));
				}else{
					$r['min'] = round($r['min']/100*(100-$this->zmgo($r['zm'])));
					$r['max'] = round($r['max']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmin'] = round($r['Kmin']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmax'] = round($r['Kmax']/100*(100-$this->zmgo($r['zm'])));
				}
				
				if( $r['Kminm'] > $r['Kmin'] ) {
					$r['Kmin'] = $r['Kminm'];
				}
				if( $r['Kmaxm'] > $r['Kmax'] ) {
					$r['Kmax'] = $r['Kmaxm'];
				}
				
				if( $r['minm'] > $r['min'] ) {
					$r['min'] = $r['minm'];
				}
				if( $r['maxm'] > $r['max'] ) {
					$r['max'] = $r['maxm'];
				}
																							
				
				//$min_yrn = 33; //%
				if( $wp_type == 18 ) {
					//$min_yrn = 17;
				}
				
				//�������
				/*if($r['type'] == 1) {
					$min_yrn += 50;
				//�������
				}elseif($r['type'] == 2) {
					$min_yrn += 40;
				//��������
				}elseif($r['type'] == 3) {
					$min_yrn += 35;
				//�������
				}elseif($r['type'] == 4) {
					$min_yrn += 60;
				//����������
				}elseif($r['type'] >= 5){	
									
				}else {

				}
				
				//
				if( $p['M'] > 0 ) {
					//$min_yrn -= $p['M']/100*20;
				}
				//
				if( $p['Mage'] == true && $r['type'] != 1 ) {
					//$min_yrn += 30;
				}
				//
				if( $r['type'] >= 5 ) {
					$min_yrn += $zm_proc;
				}else{
					$min_yrn += $za_proc;
				}
				if($r['type'] < 5){
					//$min_yrn += 50;
				}*/
				
				$min_yrn = 33;
				
				$r['min'] -= floor($r['min']/100*$min_yrn);
				$r['max'] -= floor($r['max']/100*$min_yrn);
				$r['Kmin'] -= floor($r['Kmin']/100*($min_yrn));
				$r['Kmax'] -= floor($r['Kmax']/100*($min_yrn));
				$r['min_'] -= floor($r['min_']/100*$min_yrn);
				$r['max_'] -= floor($r['max_']/100*$min_yrn);
				$r['Kmin_'] -= floor($r['Kmin_']/100*($min_yrn));
				$r['Kmax_'] -= floor($r['Kmax_']/100*($min_yrn));
				
				/*
				if($r['type'] == 4) {
					$r['Kmin'] -= floor($r['Kmin']/100*10);
					$r['Kmax'] -= floor($r['Kmax']/100*10);
					$r['Kmin_'] -= floor($r['Kmin_']/100*10);
					$r['Kmax_'] -= floor($r['Kmax_']/100*10);
				}
				*/
				
				$r['min'] += $st1['maxAtack'];
				$r['max'] += $st1['maxAtack'];
				$r['Kmin'] += $st1['maxAtack']*2;
				$r['Kmin'] += $st1['maxAtack']*2;
				
				/*$r['min_'] += $st1['maxAtack'];
				$r['max_'] += $st1['maxAtack'];
				$r['Kmin_'] += $st1['maxAtack']*2;
				$r['Kmin_'] += $st1['maxAtack']*2;*/
				
																	
				/*
				if($r['min'] < round($weapom_min/2)) {
					$r['min'] = round($weapom_min/2);
				}
				if($r['max'] < round($weapom_max/2)) {
					$r['max'] = round($weapom_max/2);
				}
				if($r['Kmin'] < round($weapom_min)) {
					$r['Kmin'] = round($weapom_min);
				}
				if($r['Kmax'] < round($weapom_max)) {
					$r['Kmax'] = round($weapom_max);
				}
				if($r['min_'] < round($weapom_min/2)) {
					$r['min_'] = round($weapom_min/2);
				}
				if($r['max_'] < round($weapom_max/2)) {
					$r['max_'] = round($weapom_max/2);
				}
				if($r['Kmin_'] < round($weapom_min)) {
					$r['Kmin_'] = round($weapom_min);
				}
				if($r['Kmax_'] < round($weapom_max)) {
					$r['Kmax_'] = round($weapom_max);
				}
				*/			
					
				if( $r['Kmin'] < 2 ) {
					$r['Kmin'] = 2;
				}
				if( $r['Kmax'] < 2 ) {
					$r['Kmax'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmin_'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmax_'] = 2;
				}
				
				$r['m_k'] = $r['Kmax'];
					
			return $r;
		}

		
	/* ������ ����� */
		public function yrn001($st1, $st2, $u1, $u2, $level, $level2, $type, $min_yron, $max_yron, $min_bron, $max_bron, $vladenie, $power_yron, $power_krit, $zashita, $ozashita, $proboi, $weapom_damage, $weapom_min, $weapom_max, $za_proc, $zm_proc, $zashitam , $ozashitam , $wp_type , $witm ) {

			global $u;
			
			//��������
			if( $zashita < 1 ) {
				$zashita = 0;
			}
			
			if( $min_yron < 1 ) {
				$min_yron = 1;
			}
			
			if( $max_yron < 1 ) {
				$max_yron = 1;
			}
			
			if( $type == '' ) {
				$type = 12;
			}
			
			//��������� ��� ���������
			$r = array('min' => 0, 'max' => 0, 'type' => $type);
			$p = array(
				'Y'		=> 0,
				'B'		=> array(0 => 0, 1 => 0, 'rnd' => false),
				'L'		=> $level,
				'W'		=> array($weapom_min, $weapom_max, 'rnd' => false), //����������� ���� //������������ ���� ����������
				'U'		=> $vladenie, //�������� �������
				'M'		=> $power_yron, //�������� �����
				'K'		=> $power_krit, //�������� �����
				'S'		=> 0,  //������� ������ �� ����.����
				'T'		=> 1,   //��. ������
				'iT'	=> 1,	//�������� ���. ��. ������
				/*
					(S) - ������� ����� ������ �� ���������� ����
					�������: S = ���� * 0,3 + �������� * 0,7 
					�������: S = ���� * 0,5 + �������� * 0,25 + �������� * 0,25 
					��������: S = ���� * 1 
					�������: S = ���� * 0,3 + �������� * 0,7 
				*/
			);
			
			$p['M'] = $p['M'] * 0.67;
			$p['K'] = $p['K'] * 0.67;
			
			//���������� 1.33 ��� �������� � 1.00 ��� ����� ����
			if ($weapom_damage == 0) { $p['T'] = 1; }elseif($weapom_damage == 1) { $p['T'] = 2.33;$p['iT'] = 2.33;}	
					
			$witm = $this->lookStats($witm['data']);
									
			//������ ���� �����			
				//�������
					if($r['type'] == 1) {		$p['S'] = $st1['s1'] * 0.30 + $st1['s2'] * 0.70; 
												$p['U'] = $st1['a1'] * 1.00; //�������
				//�������
				}elseif($r['type'] == 2) {		$p['S'] = $st1['s1'] * 0.50 + $st1['s2'] * 0.25 + $st1['s3'] * 0.25;
												$p['U'] = $st1['a2'] * 1.00; //������
				//��������
				}elseif($r['type'] == 3) {		$p['S'] = $st1['s1'] * 0.5;
												$p['U'] = $st1['a3'] * 0.70; //������
				//�������
				}elseif($r['type'] == 4) {		$p['S'] = $st1['s1'] * 0.30 + $st1['s3'] * 0.70;
												$p['U'] = $st1['a4'] * 1.00; //����
				//����������
				}elseif($r['type'] >= 5 && $r['type'] < 12 ){		$p['S'] = $st1['s1'] * 0.5 + $st1['s2'] * 0.5;	
												$p['U'] = $st1['mg'.($r['type']-4)] * 1.00; //������
												$p['Mage'] = true;					
				}else {
												$p['S'] = 0;
												$p['U'] = 0; // ��� ������(����� ������������ 
				}
			
				//	
				$r['bron'] = array($min_bron, $max_bron); //����� ���� ���� ����
				$r['bron']['rnd'] = rand($r['bron'][0],$r['bron'][1]);
				//$r['bron']['rnd'] += $r['bron']['rnd']*0.01; //������������ �������� ����� +1%
				
				$r['za'] = $zashita; //������ �� �����
				$r['oza'] = $ozashita; //����������� ������ �� �����
				
				$r['zm'] = $zashitam; //������ �� �����
				$r['ozm'] = $ozashitam; //����������� ������ �� �����
				
				
				/*
	//������� ����
				r.B[0] = $('#v1').val() * 0.51;
				r.B[1] = r.B[0] + 3.73;	
				r.B[0] = Math.ceil(r.B[0]);
				r.B[1] = Math.floor(r.B[1]);	
				if( r.B[0] < 0 ) { r.B[0] = 0; }
				if( r.B[1] < 0 ) { r.B[1] = 0; }	
				r.B[0] += 5;
				r.B[1] += 6;
				//
				*/
				
			//��������� �������	 (���������)
				$p['B'][0] = $st1['s1']*0.51;
				$p['B'][1] = $p['B'][0] + 3.73;
				$p['B'][0] = ceil($p['B'][0]);
				$p['B'][1] = floor($p['B'][1]);
				$p['B'][0] += 5;
				$p['B'][1] += 6;	

				$p['B']['rnd'] = rand($p['B'][0],$p['B'][1]);				
				$p['W']['rnd'] = rand($p['W'][0],$p['W'][1]);
				
			//������� ����
				$p['Mstart'] = 0; //��������� ��������	
				if( $r['type'] == 12 ) {
					$r['min']  = ($p['B'][0]+$weapom_min)*$p['T']*(1+$p['M']/100);
					$r['max']  = ($p['B'][1]+$weapom_max)*$p['T']*(1+$p['M']/100);
				}else{
					$r['min']  = (($p['B'][0]+$p['S']+$weapom_min)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
					$r['max']  = (($p['B'][1]+$p['S']+$weapom_max)*$p['T']*(1+0.07*$p['U']))*(1+$p['M']/100);
				}
													
				$r['minm'] = $r['min']*0.13;
				$r['maxm'] = $r['max']*0.13;
				
				if( $wp_type == 22 ) {
					//������
					$r['min'] = $r['min']/2;	
					$r['max'] = $r['max']/2;
				}

			//����������� ����
				//�����
				$r['Kmin'] = $r['min'];
				$r['Kmax'] = $r['max'];
				
			//������ �����
				//��� ��������
				if( $r['type'] < 5 || $r['type'] == 12 ) {
					$r['min_abron'] = round($r['min']*0.05);
					$r['max_abron'] = round($r['max']*0.05);
					
					if($proboi != 0) {
						$r['bron']['rndold'] = $r['bron']['rnd'];
						$r['bron']['rnd'] = $r['bron'][0];
						/*
						if( round($r['min']*0.45) < $r['bron']['rnd'] ) {
							$r['bron']['rnd'] = round($r['min']*0.55);
						}
						*/
						//
						/*$r['yrnrz'] = $r['max']-$r['min'];
						if( $r['yrnrz'] < 0 ) {
							$r['yrnrz'] = -$r['yrnrz'];
						}*/
					}
										
					//����� ����������� � �����
					/*
					if( $r['bron']['rnd'] > $r['min'] ) {
						$r['bron']['rnd'] = $r['min'];
					}
					*/
					//
					
					$r['Kmin'] -= $r['bron']['rnd']*2; //���� �������� �� 2
					$r['Kmax'] -= $r['bron']['rnd']*2; //���� �������� �� 2
					
					$r['min'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					$r['max'] -= $r['bron']['rnd']; //�� ���� ������� �� �� ��.
					
					if($proboi != 0) {
						//   $r['yrnrz']
						//   $r['bron']['rndold']						
					}
				}
										
				/*$r['min'] += $weapom_min;
				$r['max'] += $weapom_max;
				$r['Kmin'] += $weapom_min*2;
				$r['Kmax'] += $weapom_max*2;*/
				
			//����������� �������� �����
				$r['min_'] = floor($r['min']*0.13);
				$r['max_'] = floor($r['max']*0.13);
				$r['Kmin_'] = floor($r['Kmin']*0.13);
				$r['Kmax_'] = floor($r['Kmax']*0.13);
																																		
			//����������� ������				
				//$r['ozash_rnd'] = $r['oza'][$r['type']][1]; /*rand($r['oza'][$r['type']][0],$r['oza'][$r['type']][1]);*/
				
				//if($r['ozash_rnd'] > 80) { $r['ozash_rnd'] = 80; }
				//if($r['ozash_rnd'] < 0) { $r['ozash_rnd'] = 0; }
				

				//$r['ozash_rnd'] = 100-$r['ozash_rnd'];				
				
				//$p['iT']
								
				$r['min'] = floor($r['min']*$p['iT'] + $weapom_min);
				$r['max'] = floor($r['max']*$p['iT'] + $weapom_max);
				$r['Kmin'] = floor($r['Kmin']*$p['iT'] + $weapom_min*2);
				$r['Kmax'] = floor($r['Kmax']*$p['iT'] + $weapom_max*2);
				$r['min_'] = floor($r['min_']*$p['iT'] + $weapom_min);
				$r['max_'] = floor($r['max_']*$p['iT'] + $weapom_max);
				$r['Kmin_'] = floor($r['Kmin_']*$p['iT'] + $weapom_min*2);
				$r['Kmax_'] = floor($r['Kmax_']*$p['iT'] + $weapom_max*2);
								
								
				//$r['min'] -= ($r['min']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				//$r['max'] -= ($r['max']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				
				//$r['Kmin'] -= ($r['Kmin']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
				//$r['Kmax'] -= ($r['Kmax']/(200+$r['ozash_rnd'])*$r['ozash_rnd']);
								
				$r['bRND'] = $p['B']['rnd'];
					
				$r['min'] += $min_yron;
				$r['max'] += $max_yron;
					
				//
				
				if( $r['minm'] > $r['min'] ) {
					$r['min'] = $r['minm'];
				}
				if( $r['maxm'] > $r['max'] ) {
					$r['max'] = $r['maxm'];
				}
				
				$r['Kmin'] = ceil( ( 2 + ( 0 + $p['K']/100 ) ) * $r['min']);
				$r['Kmax'] = ceil( ( 2 + ( 0 + $p['K']/100 ) ) * $r['max']);
				
				//$r['Kmin'] = ceil( ( 1 + ( 1 + $p['K']/100 ) ) * $r['min']);
				//$r['Kmax'] = ceil( ( 1 + ( 1 + $p['K']/100 ) ) * $r['max']);
				
				//$r['Kmin'] = ceil( ( $r['min'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] * 2 ) * ( 1 + $p['K']/100 ));
				//$r['Kmin'] = ceil( ( $r['min'] ) * ( 2 + $p['K']/100 ));
				//$r['Kmax'] = ceil( ( $r['max'] ) * ( 2 + $p['K']/100 ));
				//
				$r['Kminm'] = $r['Kmin']*0.05;
				$r['Kmaxm'] = $r['Kmax']*0.05;
							
				$r['min'] = round($r['min']);
				$r['max'] = round($r['max']);
				$r['Kmin'] = round($r['Kmin']);
				$r['Kmax'] = round($r['Kmax']);
				
			//������ ������ (�� ����� 80%)
				if( $r['type'] < 5 ) {
					$r['min'] = round($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = round($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = round($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = round($r['Kmax']/100*(100-$this->zago($r['za'])));
				}elseif( $r['type'] == 12 ) {
					$r['min'] = round($r['min']/100*(100-$this->zago($r['za'])));
					$r['max'] = round($r['max']/100*(100-$this->zago($r['za'])));
					$r['Kmin'] = round($r['Kmin']/100*(100-$this->zago($r['za'])));
					$r['Kmax'] = round($r['Kmax']/100*(100-$this->zago($r['za'])));
				}else{
					$r['min'] = round($r['min']/100*(100-$this->zmgo($r['zm'])));
					$r['max'] = round($r['max']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmin'] = round($r['Kmin']/100*(100-$this->zmgo($r['zm'])));
					$r['Kmax'] = round($r['Kmax']/100*(100-$this->zmgo($r['zm'])));
				}
								
				if( $r['Kminm'] > $r['Kmin'] ) {
					$r['Kmin'] = $r['Kminm'];
				}
				if( $r['Kmaxm'] > $r['Kmax'] ) {
					$r['Kmax'] = $r['Kmaxm'];
				}
				
				if( $r['minm'] > $r['min'] ) {
					$r['min'] = $r['minm'];
				}
				if( $r['maxm'] > $r['max'] ) {
					$r['max'] = $r['maxm'];
				}
																							
				
				//$min_yrn = 33; //%
				if( $wp_type == 18 ) {
					//$min_yrn = 17;
				}
				
				//�������
				/*if($r['type'] == 1) {
					$min_yrn += 50;
				//�������
				}elseif($r['type'] == 2) {
					$min_yrn += 40;
				//��������
				}elseif($r['type'] == 3) {
					$min_yrn += 35;
				//�������
				}elseif($r['type'] == 4) {
					$min_yrn += 60;
				//����������
				}elseif($r['type'] >= 5){	
									
				}else {

				}
				
				//
				if( $p['M'] > 0 ) {
					//$min_yrn -= $p['M']/100*20;
				}
				//
				if( $p['Mage'] == true && $r['type'] != 1 ) {
					//$min_yrn += 30;
				}
				//
				if( $r['type'] >= 5 ) {
					$min_yrn += $zm_proc;
				}else{
					$min_yrn += $za_proc;
				}
				if($r['type'] < 5){
					//$min_yrn += 50;
				}*/
				
				$min_yrn = 33;
				
				
				//��� � ������, �������
				if( $this->info['type'] == 28 ) {
					if( $u1['bot'] > 0 ) {
						$min_yrn = 66;
					}elseif( $u1['bot'] == 0 ) {
						$min_yrn = 0;
					}
				}
				
				$r['min'] -= floor($r['min']/100*$min_yrn);
				$r['max'] -= floor($r['max']/100*$min_yrn);
				$r['Kmin'] -= floor($r['Kmin']/100*($min_yrn));
				$r['Kmax'] -= floor($r['Kmax']/100*($min_yrn));
				$r['min_'] -= floor($r['min_']/100*$min_yrn);
				$r['max_'] -= floor($r['max_']/100*$min_yrn);
				$r['Kmin_'] -= floor($r['Kmin_']/100*($min_yrn));
				$r['Kmax_'] -= floor($r['Kmax_']/100*($min_yrn));
				
				/*
				if($r['type'] == 4) {
					$r['Kmin'] -= floor($r['Kmin']/100*10);
					$r['Kmax'] -= floor($r['Kmax']/100*10);
					$r['Kmin_'] -= floor($r['Kmin_']/100*10);
					$r['Kmax_'] -= floor($r['Kmax_']/100*10);
				}
				*/
				
				$r['min'] += $st1['maxAtack'];
				$r['max'] += $st1['maxAtack'];
				$r['Kmin'] += $st1['maxAtack']*2;
				$r['Kmin'] += $st1['maxAtack']*2;
				
				/*$r['min_'] += $st1['maxAtack'];
				$r['max_'] += $st1['maxAtack'];
				$r['Kmin_'] += $st1['maxAtack']*2;
				$r['Kmin_'] += $st1['maxAtack']*2;*/
				
																	
				/*
				if($r['min'] < round($weapom_min/2)) {
					$r['min'] = round($weapom_min/2);
				}
				if($r['max'] < round($weapom_max/2)) {
					$r['max'] = round($weapom_max/2);
				}
				if($r['Kmin'] < round($weapom_min)) {
					$r['Kmin'] = round($weapom_min);
				}
				if($r['Kmax'] < round($weapom_max)) {
					$r['Kmax'] = round($weapom_max);
				}
				if($r['min_'] < round($weapom_min/2)) {
					$r['min_'] = round($weapom_min/2);
				}
				if($r['max_'] < round($weapom_max/2)) {
					$r['max_'] = round($weapom_max/2);
				}
				if($r['Kmin_'] < round($weapom_min)) {
					$r['Kmin_'] = round($weapom_min);
				}
				if($r['Kmax_'] < round($weapom_max)) {
					$r['Kmax_'] = round($weapom_max);
				}
				*/			
					
				if( $r['Kmin'] < 2 ) {
					$r['Kmin'] = 2;
				}
				if( $r['Kmax'] < 2 ) {
					$r['Kmax'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmin_'] = 2;
				}
				if( $r['Kmin_'] < 2 ) {
					$r['Kmax_'] = 2;
				}
				
				$r['m_k'] = $r['Kmax'];
					
			return $r;
		}
		
		public $pr_not_use = array(),$pr_reset = array(),$pr_yrn = false,$prnt = array();
	//���������� �������� ������
	// pl �����
	// u1 ���� �����
	// t1 ��� ������ 
	// 99 = �������� ������
	// u2 
	//$this->delPriem($pd[$k2][1][$k],${'p'.$k2},1,${'p'.$k2jn});
		public $del_val = array(),$re_pd = array();
		public function delPriem($pl,$u1,$t = 1,$u2 = false,$rznm = '���������� ������',$k2nm,$yrn,$yrnt)
		{
			global $u,$priem;
			if(isset($pl['priem']['id']) && !isset($this->del_val['eff'][$pl['priem']['id']]))
			{	
				if($pl['x'] > 1) {
					$pl['name'] = $pl['name'].' x'.$pl['x'].'';
				}
				if($pl['timeUse']==77)
				{
					//��������� �����
					mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
				$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'';
				if(isset($u2['id'])) {
					$vLog .= '||s2='.$u2['sex'].'||t2='.$u2['team'].'||login2='.$u2['login'].'';
				}
				$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				if($t==4)
				{
					$mas1['id_hod']++;
					$t = 2;
				}
				if($t==1)
				{
					$mas1['id_hod']++;
					if($pl['priem']['file']!='0')
					{						
						if(file_exists('../../_incl_data/class/priems/'.$pl['priem']['file'].'.php'))
						{
							require('priems/'.$pl['priem']['file'].'.php');
						}
					}elseif($pl['priem']['file3']!='0')
					{						
						if(file_exists('../../_incl_data/class/priems/'.$pl['priem']['file3'].'.php'))
						{
							require('priems/'.$pl['priem']['file3'].'.php');
						}
					}else{
						$mas1['text'] = '{tm1} {u1} {1x16x0} ����� &quot;<b>'.$pl['name'].'</b>&quot;.';
						$this->del_val['eff'][$pl['priem']['id']] = true;
					}
				}elseif($t==2)
				{
					$mas1['text'] = '{tm1} � ��������� {u1} ����������� �������� ����� &quot;<b>'.$pl['name'].'</b>&quot;.';
				}elseif($t==99){
				    $mas1['text'] = '{u1} ���� ����� &quot;<b>'.$pl['name'].'</b>&quot; � ������� <b>'.$rznm.'</b> .';
				}else{
					if( $t == 100 ) {
						$mas1['id_hod']++;
					}
					$mas1['text'] = '{tm1} ����������� �������� ������� &quot;<b>'.$pl['name'].'</b>&quot; ��� {u1}.';
				}
				if( $pl['priem']['id'] != 24 ) {
					$this->add_log($mas1);
				}
				$this->stats[$this->uids[$pl['uid']]] = $u->getStats($pl['uid'],0,0,false,false,true);
			}else{
				//�� ������� ������� ����� ��� ������
			}
		}
		public function hodUserPriem($pl,$u1,$t = 1,$u2 = false,$rznm = '���������� ������',$k2nm,$yrn,$yrnt)
		{
			global $u,$priem;
			if(isset($pl['priem']['id']) && !isset($this->del_val['eff'][$pl['priem']['id']]))
			{

				if($yrnt == 1)
				{
					//������� ����
					$yrn = round($yrn);
				}elseif($yrnt == 6)
				{
					//��������� ��������� �� �����
					$yrn = 0;
				}elseif($yrnt == 9)
				{
					//��������� ��������� ����
					$yrn = 0;
				}elseif($yrnt == 3)
				{
					//�� ������� ����-����
					$yrn = round($yrn*1.95)+ceil($yrn/125*$this->stats[$this->uids[$u1['id']]]['m3']);
				}elseif($yrnt == 4)
				{
					//�� ������� ����-���� ����� ����
					$yrn = round($yrn*0.45)+ceil($yrn/125*$this->stats[$this->uids[$u1['id']]]['m3']);
				}else{
					//����������� ����
					$yrn = 0;
				}
				
				if($pl['x'] > 1) {
					$pl['name'] = $pl['name'].' x'.$pl['x'].'';
				}
				$vLog = 'time1='.time().'||s1='.$u1['sex'].'||t1='.$u1['team'].'||login1='.$u1['login'].'';
				if(isset($u2['id'])) {
					$vLog .= '||s2='.$u2['sex'].'||t2='.$u2['team'].'||login2='.$u2['login'].'';
				}
				$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
				if($t==4)
				{
					$mas1['id_hod']++;
					$t = 2;
				}if($t==1)
				{
					$mas1['id_hod']++;
					if($pl['priem']['file']!='0')
					{						
						if(file_exists('../../_incl_data/class/priems/'.$pl['priem']['file'].'.php'))
						{
							require('priems/'.$pl['priem']['file'].'.php');
						}
					}else{
						$mas1['text'] = '{tm1} {u1} {1x16x0} ����� &quot;<b>'.$pl['name'].'</b>&quot;.';
						$this->del_val['eff'][$pl['priem']['id']] = true;
					}
				}
				$this->add_log($mas1);
				$this->stats[$this->uids[$pl['uid']]] = $u->getStats($pl['uid'],0,0,false,false,true);
			}else{
				//�� ������� ������� ����� ��� ������
			}
		}
		
	//������ ������
		public function aPower($za,$za1,$yrn)
		{
			$z = 0;
			/*$z = ($za+$za1)*0.35;
			$z = round($yrn/$z*100);*/
			
			$z = (1-( pow(0.5, (($za+$za1)/250) ) ))*100; 			
			return $z;
		}
		
	//������ �����
		public function bronGo($min,$max)
		{
			$v = 0;
			//$v = ceil(($min+$max)/2,$max);
			$v = ceil($min,$max);
			return $v;
		}	
		
	//������ ����� (test)
		public function bronGoTest($min,$max)
		{
			$v = 0;
			//$v = ceil(($min+$max)/2,$max);
			$v = ceil(round($min,$max));
			return $v;
		}
	
	//��������� ������ �� �������
		public function lookStats($m)
		{
			$ist = array();
			$di = explode('|',$m);
			$i = 0; $de = false;
			while($i<count($di))
			{
				$de = explode('=',$di[$i]);
				$ist[$de[0]] = $de[1];
				$i++;
			}
			return $ist;
		}
		
	//������ ����������� �������
		public function mfsgo1($a,$b) {
			
			$r = $this->form_mf($a,$b);
			return $r;
		}
		
	//������ ����������� �����
		public function mfsgo2($a,$b) {
			
			$r = $this->form_mf($a,$b);
			return $r;
		}
		
	//������ ��. (�����)
		public function form_mf($u,$au) {
			$v = $u*5.1 - $au*5.1;
			if($v < 0) {
				$v = 0; 
			}
			$r = (1-( pow(99/100, (($v)/100) ) ))*100;	
			$r = round($r);
			return $r;
		}
		
	//������������ ������� (�����) ��� 5�� �������.
		public function msf_st2( $mf , $lvl1 , $lvl2 ) {
			$r = 0;
			
			if($lvl1 > $lvl2) {
				$lvl = $lvl1;
			}else{
				$lvl = $lvl2;
			}
			
			/*
			1-��: �� 0 �� 50 - �������� ��������� ����� �� 0 �� 25%
			2-��: �� 51 �� 151 - �������� ��������� ����� �� 25% �� 50%
			3-��: �� 151 �� 400 - �������� ��������� ����� �� 50% �� 75%
			4-��: ����� 400 - 75% + 0,01 * (������� � � �� - 1000)
			����	��		�������	�������
			350		50			300		85
			350		100			250		
			350		150			200		85
			350		175			175		
			350		200			150		
			350		225			125		
			350		250			100		65
								50		35

			
			
			*/
			//������
			if( $mf < 0 ) {
				$mf = 0;
			}
			if( $lvl <= 7 ) {
				//������ 0-7 ������
				if( $mf <= 100 ) { //0-35
					$prc = $mf;
					$r = 35/50*$prc;
				}elseif( $mf <= 200 ) { //35-65
					$prc = $mf-101;
					$r = $prc/99*30;
					$r += 35;
				}elseif( $mf <= 400 ) { //65-85		
					$prc = $mf-201;
					$r = $prc/199*20;
					$r += 65;
				}else{ //> 75
					$r = 85 + 0.01 * abs(($mf-1000));
				}
			}else{
				//������ ��������� �������
				if( $mf <= 100 ) { //0-45
					$prc = $mf;
					$r = 45/100*$prc;
				}elseif( $mf <= 300 ) { //45-65
					$prc = $mf-101;
					$r = $prc/199*20;
					$r += 45;
				}elseif( $mf <= 450 ) { //65-75		
					$prc = $mf-301;
					$r = $prc/149*10;
					$r += 65;
				}else{ //> 75
					$r = 85;
					//$r = 75 + 0.01 * abs(($mf-1000));
				}
			}
			
			if($r < 0) {
				$r = 0;
			}
			
			return $r;
		}
		
	//������������ ������� (������)
		public function msf_st( $mf , $lvl1 , $lvl2 ) {
			$r = 0;
			
			if($lvl1 > $lvl2) {
				$lvl = $lvl1;
			}else{
				$lvl = $lvl2;
			}
			
			/*
			1-��: �� 0 �� 100 - �������� ��������� ����� �� 0 �� 25%
			2-��: �� 101 �� 400 - �������� ��������� ����� �� 35% �� 70%
			3-��: �� 401 �� 1000 - �������� ��������� ����� �� 70% �� 85%
			4-��: ����� 1000 - 85% + 0,01 * (������� � � �� - 1000)
			*/
			/* ����������� ��� ��������� �������. 150 �� � ������ ������, 350 � �������.
			
			��	� 	�������	�������
			75	750	675	85
			100	750	650	70
			150	750	600	
			200	750	550	55

			300	750	450	50
			350	750	400	50
			
			���� 700 �, �������� 750 � ������������� �������. ��������� ��� ����� ������� �.
			*/
			
			if( $mf < 0 ) {
				$mf = 0;
			}
			//������
			if( $lvl <= 7 ) {
				//������ 0-7 ������
				if( $mf <= 200 ) { //0-60
					$prc = $mf;
					$r = 60/200*$prc;
				}elseif( $mf <= 550 ) { //60-70
					$prc = $mf-201;
					$r = $prc/349*10;
					$r += 60;
				}elseif( $mf <= 675 ) { //70-75
					$prc = $mf-551;
					$r = $prc/124*5;
					$r += 70;
				}else{ //> 85
					$r = 76; 
					//$r = 85 + 0.01 * abs(($mf-1000));	
				}
			}
			else{
				//������ ���� ��������� �������
				/* ����������� ��� ��������� �������. 150 �� � ������ ������, 350 � �������.
			
			��	� 	�������	�������
			50	950		1000	85
			100	950		850
			150	950		800
			250	950		700	80

			300	950		650	75
			400	950		550	70
			
			
			������� ������� 100 ����� � � �� - ���� 50, ��������� ������� ���������. ����� ������������ �������� �� ����� ��� �������.
			*/
				if( $mf <= 250 ) { //0-35
					$prc = $mf;
					$r = 35/250*$prc;
				}elseif( $mf <= 600 ) { //35-50
					$prc = $mf-251;
					$r = $prc/349*15;
					$r += 35;
				}elseif( $mf <= 800 ) { //50-70	
					$prc = $mf-601;
					$r = $prc/199*20;
					$r += 50;
				}else{ //> 85
					$r = 75;
					//$r = 75 + 0.01 * abs(($mf-1000));	
				}
			}
			
			if($r < 0) {
				$r = 0;
			}
			
			
			return floor($r*1.25);
		}
				
	//������ ��		
		public function mfs001($type, $mf, $lvl1 , $lvl2)
		{
			$rval = 0;
			switch($type) {
				case 1:
					if($mf['amf'] < 1){ $mf['amf'] = 1; }
					if($mf['mf'] < 1){ $mf['mf'] = 1; }
					//if($mf['amf'] > $mf['mf']) {
					//	$mf['amf'] = $mf['mf'];
					//}
					$rval = min( (( 1 - ( ( $mf['amf']*0.7 + 50 ) / ( $mf['mf']*0.7 + 50 ) ) ) * 100), 80); //����. ����
					//$rval = min($this->mfsgo2(($mf['mf']),$mf['amf']),80);
					if($rval < 1) {
						$rval = 0;
					}
					if($rval > 80) {
						$rval = 80;
					}	
				break;
				case 2:
					if($mf['amf'] < 1){ $mf['amf'] = 1; }
					if($mf['mf'] < 1){ $mf['mf'] = 1; }
					/*
					if($mf['amf'] > $mf['mf']) {
						$mf['amf'] = $mf['mf'];
					}
					*/
					$rval = min( ( ( 1 - ( ( $mf['amf']*0.7 + 50 ) / ( $mf['mf']*0.8 + 50 ) ) ) * 100 ), 80 ); //������

					//$rval = min($this->mfsgo1($mf['mf'],$mf['amf']),60);
					//$rval += 10;
					if($rval < 1) {
						$rval = 0;
					}
					if($rval > 80) {
						$rval = 80;
					}					
				break;
				case 3:
					$mf[1] -= 4;
					$mf[2] -= 4;
					if($mf[1] < 1){ $mf[1] = 1; }
					if($mf[2] < 1){ $mf[2] = 1; }
					
					//$rval = $mf[1] - $mf[2]; //�����������
					$rval = $mf[1]/80*100;
					if( $rval > 100 ) {
						$rval = 100;
					}
					$rval = round($rval/3);
					if( $rval < 1 ) {
						$rval = 0;
					}
					
					//$rval = (1-( pow(0.75, ($rval/125) ) ))*100;
					
					//if( $rval > 60 ) {
					//	$rval = 60;
					//}	
					
				break;
				case 4:
					$mf = round($mf*0.6);
					if($mf < 1){ $mf = 0; }
					if($mf > 100){ $mf = 100; }
					//$mf = (1-( pow(0.5, ($mf/200) ) ))*100;
					$rval = min( $mf , 100 ); //������ �����
				break;
				case 5:
					if($mf < 1){ $mf = 0; }
					$rval = min( $mf, 40 ); //���� �����
				break;
				case 6:
					/*$mf = $mf/80*100;
					$mf = round($mf/2);
					if($mf < 1){ $mf = 0; }
					if($mf > 50){ $mf = 50; }
					//$mf = (1-( pow(0.5, ($mf/75) ) ))*100;
					$rval = $mf; //���������*/
					
					$rval = $mf['a']/80*100;
					$rval = round($rval/2);
					//$rval -= round($mf['b']/2);
					if($rval < 1){ $rval = 0; }
					if($rval > 50){ $rval = 50; }
					//$rval = $mf; //���������
					
				break;
			}
			if($this->get_chanse($rval) == true) {
				$rval = 1;
			}else{
				$rval = 0;
			}
			return $rval;
		}		
	
	//������ ��		
		public function mfs($type, $mf)
		{
			$rval = 0;
			switch($type) {
				case 1:
					if($mf['amf'] < 1){ $mf['amf'] = 1; }
					if($mf['mf'] < 1){ $mf['mf'] = 1; }
					//if($mf['amf'] > $mf['mf']) {
					//	$mf['amf'] = $mf['mf'];
					//}
					$rval = min( (( 1 - ( ( $mf['amf']*0.8 + 50 ) / ( $mf['mf']*0.8 + 50 ) ) ) * 100), 80); //����. ����
					//$rval = min($this->mfsgo2(($mf['mf']),$mf['amf']),80);
					if($rval < 1) {
						$rval = 0;
					}
					if($rval > 80) {
						$rval = 80;
					}	
				break;
				case 2:
					if($mf['amf'] < 1){ $mf['amf'] = 1; }
					if($mf['mf'] < 1){ $mf['mf'] = 1; }
					/*
					if($mf['amf'] > $mf['mf']) {
						$mf['amf'] = $mf['mf'];
					}
					*/
					$rval = min( ( ( 1 - ( ( $mf['amf']*0.8 + 50 ) / ( $mf['mf']*0.8 + 50 ) ) ) * 100 ), 80 ); //������

					//$rval = min($this->mfsgo1($mf['mf'],$mf['amf']),60);
					//$rval += 10;
					if($rval < 1) {
						$rval = 0;
					}
					if($rval > 80) {
						$rval = 80;
					}					
				break;
				case 3:
					$mf[1] -= 4;
					$mf[2] -= 4;
					if($mf[1] < 1){ $mf[1] = 1; }
					if($mf[2] < 1){ $mf[2] = 1; }
					
					//$rval = $mf[1] - $mf[2]; //�����������
					$rval = $mf[1]/80*100;
					if( $rval > 100 ) {
						$rval = 100;
					}
					$rval = round($rval/3);
					if( $rval < 1 ) {
						$rval = 0;
					}
					
					//$rval = (1-( pow(0.75, ($rval/125) ) ))*100;
					
					//if( $rval > 60 ) {
					//	$rval = 60;
					//}	
					
				break;
				case 4:
					$mf = round($mf*0.6);
					if($mf < 1){ $mf = 0; }
					if($mf > 100){ $mf = 100; }
					//$mf = (1-( pow(0.5, ($mf/200) ) ))*100;
					$rval = min( $mf , 100 ); //������ �����
				break;
				case 5:
					if($mf < 1){ $mf = 0; }
					$rval = min( $mf, 40 ); //���� �����
				break;
				case 6:
					$mf = $mf['a']/80*100;
					$mf = round($mf/2);
					if($mf < 1){ $mf = 0; }
					if($mf > 50){ $mf = 50; }
					//$mf = (1-( pow(0.5, ($mf/75) ) ))*100;
					$rval = $mf; //���������
				break;
			}
			if($this->get_chanse($rval) == true) {
				$rval = 1;
			}else{
				$rval = 0;
			}
			return $rval;
		}		
	
		public function dodge($a, $b) {
			$i = 0;
			$arr = array();     //������ ��� ������ ���������� ��������� �����
			while($i < ($b - $a)) {
				while(in_array($rand, $arr)) {  
					$rand = mt_rand(1, 100);
				}
				$arr[] = $rand;
				$i++;
			}
			
			$n = mt_rand(1, 100);
			return(!!array_search($n, $arr));
		}

		public function get_chanse($percent)
		{
			/*$a = 101+$percent;
			$b = 100-$percent;
			$i = 1;
			if(($a-$b)>0){
				while($i<=$a-$b){
					$conp[] = rand(1,100);  
					//$conp[] = mt_rand(1,100);  
					if( $i > 100 ) {
						$i = ($a-$b+1);
					}
					$i++;   
				}
			}
			$t = count($conp);
			$prob = round($percent);
			if(@array_search($prob,$conp)!=false){
				$critical = true;
			}else{
				$critical = false;
			}*/
			/*if( rand(0,100) <= $percent ) {
				$critical = true;
			}else{
				$critical = false;
			}*/
			if( $percent >  100 ) {
				$percent = 100;
			}elseif( $percent == 0 ) {
				$percent = 0;
			}
			$critical = $this->dodge(1, $percent);
			return $critical;
		}
	
	//������ �����
		public function get_chanse_new($persent)
		{		
			$mm = 1;
			if(mt_rand($mm,100*$mm)<=$persent*$mm)
			{
				return true;
			}else{
				return false;
			}
		}
	
	//����� ����������
		public function smena($uid,$auto = false,$lastdie = false)
		{
			global $u;
			if(($auto == false && $u->info['smena'] > 0) || $auto == true) {
				if($this->stats[$this->uids[$u->info['id']]]['hpNow']>=1)
				{
					if(isset($this->uids[$uid]) && $uid!=$u->info['id'] && $this->users[$this->uids[$uid]]['team']!=$this->users[$this->uids[$u->info['id']]]['team'])
					{
						if(!isset($this->ga[$u->info['id']][$uid]) || $lastdie == true)
						{
							if(ceil($this->stats[$this->uids[$uid]]['hpNow'])>=1)
							{
								//������ ����������
								if($auto == false) {
									$u->info['smena']--;
								}
								$upd = mysql_query('UPDATE `stats` SET `enemy` = "'.$uid.'",`smena` = "'.$u->info['smena'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								$u->info['enemy'] = $uid;
								$this->users[$this->uids[$uid]]['smena'] = $u->info['smena'];
								$this->users[$this->uids[$u->info['id']]]['enemy'] = $uid;
								return 1;
							}else{
								return '������ �������, ��������� ��� �����';
							}
						}else{
							return '������ ������� �� ��������� ����!';
						}
					}else{
						return '������ ������� �� ��������� ���� []';
					}
				}else{
					return '��� ��� �������� ��������, �������� ���� �������� ������...';
				}
			}else{
				return '� ��� ����������� ����� ����������';
			}
		}
	
	//����-����� ����������
		public function autoSmena()
		{
			global $u;
			$ms = array();
			$ms_all = array();
			$i = 0; $j = 0;
			while($i<count($this->users))
			{
				if(isset($this->users[$i]) && $this->users[$i]['id']!=$u->info['id'] && $this->users[$i]['team']!=$u->info['team'] && $this->stats[$i]['hpNow']>=1 && -($u->info['enemy']) != $this->users[$i]['id'])
				{
					if(!isset($this->ga[$u->info['id']][$this->users[$i]['id']]))
					{
						$ms[$j] = $this->users[$i]['id'];
						$j++;
					}
					if( !isset($this->uids[(-($u->info['enemy']))]) ) {
						$ms_all[] = $this->users[$i]['id'];
					}
				}
				$i++;
			}
			$msh = array();
			if( $j == 0 ) {
				if( (!isset($this->uids[(-($u->info['enemy']))]) || $this->stats[$this->uids[(-($u->info['enemy']))]]['hpNow'] < 1) && $u->info['enemy'] < 0 ) {
					$i = 0; $j = 0;
					while($i<count($this->users))
					{
						if(isset($this->users[$i]) && $this->users[$i]['id']!=$u->info['id'] && $this->users[$i]['team']!=$u->info['team'] && $this->stats[$i]['hpNow']>=1 && -($u->info['enemy']) != $this->users[$i]['id'])
						{
							$ms[$j] = $this->users[$i]['id'];
							$msh[$ms[$j]] = true;
							$j++;
						}
						$i++;
					}
				}
			}

			$ms = $ms[rand(0,$j-1)];
			if($j > 0) {
				if( isset($msh[$ms]) ) {
					$this->smena($ms,true,true);
				}else{
					$this->smena($ms,true);
				}
			}else{
				if( $u->info['enemy'] < 0 ) {
					$smnr5 = $this->smena(-($u->info['enemy']),true);
					if( $smnr5 != 1 ) {
						if( !isset($this->uids[(-($u->info['enemy']))]) ) {
							$u->info['enemy'] = $ms_all[rand(0,(count($ms_all)-1))];
							mysql_query('UPDATE `stats` SET `enemy` = "'.$u->info['enemy'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
					}
					unset($smnr5);
				}
			}
		}
			
	//�������� ���� (�����)
		public function botAtack($uid,$pl,$tp)
		{
			//global $c,$u,$code;
			//��� ���������� �����, ���� ��� ��������
			/*$ij = 0;
			$bp = explode('|',$this->users[$this->uids[$uid]]['priems']);
			$bpz = explode('|',$this->users[$this->uids[$uid]]['priems_z']);
			while($i<count($bp))
			{
				$plj = mysql_fetch_array(mysql_query('SELECT * FROM `priems` WHERE `id` = "'.((int)$bp[$ij]).'" AND `activ` > "0" LIMIT 1'));
				if(isset($plj['id']))
				{
					$notrj = 0;

					

					if($bpz[$ij]<=0 && $bp[$ij]>0 && $notrj==0)
					{
						//����� ������������
						
					}
				}
				$ij++;
			}*/
			//$pl - ���� 
			$test_atack = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle_act` WHERE `battle` = "'.$this->info['id'].'" AND ((
				`uid1` = "'.$pl.'" AND `uid2` = "'.$uid.'"
			) OR (
				`uid2` = "'.$pl.'" AND `uid1` = "'.$uid.'"
			)) LIMIT 1'));
			if($tp==1 && !isset($test_atack['id']))
			{
				//��� ��� ������ ����
				//if(rand(0,1)==1)
				//{
					$a = rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5);
					$b = rand(1,5);
					//mysql_query('LOCK TABLES battle_act WRITE');
					
					//mysql_query('DELETE FROM `battle_act` WHERE `battle` = "'.$this->info['id'].'" AND ((`uid2` = "'.$pl.'" AND `uid1` = "'.$uid.'") OR (`uid1` = "'.$pl.'" AND `uid2` = "'.$uid.'")) LIMIT 2');
					
					$d = mysql_query('INSERT INTO `battle_act` (`battle`,`time`,`uid1`,`uid2`,`a1`,`b1`) VALUES ("'.$this->info['id'].'","'.time().'","'.$pl.'","'.$uid.'","'.$a.'","'.$b.'")');
					
					//mysql_query('UNLOCK TABLES');
				//}
			}elseif($tp==2)
			{
				//��� �������� �� ����
				$bot = $this->users[$this->uids[$pl['uid2']]];
				$na = array('id'=>0,'a'=>array(1=>0,2=>0,3=>0,4=>0,5=>0),'b'=>0);
				$a222 = rand(1,5).'_'.rand(1,5).'_'.rand(1,5).'_'.rand(1,5).'_'.rand(1,5);
				$a = explode('_',$a222);
				$i = 1;
				$na['id'] = time();
				while($i<=5)
				{
					if(isset($a[$i-1]))
					{
						$a[$i-1] = intval(round($a[$i-1]));
						if($a[$i-1]>=1 && $a[$i-1]<=5)
						{
							$na['a'][$i] = $a[$i-1];
						}else{
							$na['a'][$i] = 0;
						}
					}
					$i++;
				}				
				$na['b'] = rand(1,5);
				//�������� ����
				$this->atacks[$pl['id']]['a2'] = $a222;
				$this->atacks[$pl['id']]['b2'] = $na['b'];
				$this->startAtack($pl['id']);
			}
		}
	
	//��������� �����, ������, ������, ������
		public function testActions()
		{
			//��������� �����
				$m = mysql_query('SELECT * FROM `battle_act` WHERE `battle` = "'.$this->info['id'].'" ORDER BY `id` ASC LIMIT 100');
				$i = 0;
				$botA = array();
				$botR = array();
				while($pl = mysql_fetch_array($m))
				{
					mysql_query('DELETE FROM `battle_act` WHERE `battle` = "'.$this->info['id'].'" AND `uid1` = "'.$pl['uid2'].'" AND `uid2` = "'.$pl['uid1'].'"');
					if($this->stats[$this->uids[$pl['uid1']]]['hpNow']<=0 || $this->stats[$this->uids[$pl['uid2']]]['hpNow']<=0)
					{
						mysql_query('DELETE FROM `battle_act` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					}elseif($pl['time']+$this->info['timeout']>time())
					{
						//���� �� �������� �� �����, ������ ������� ������
						$this->atacks[$pl['id']] = $pl;
						$this->ga[$pl['uid1']][$pl['uid2']] = $pl['id'];
						$this->ag[$pl['uid2']][$pl['uid1']] = $pl['id'];
						if(isset($this->iBots[$pl['uid1']]))
						{
							//������ ��� � ��� ������
							$botA[$pl['uid1']] = $pl['id'];
						}elseif(isset($this->iBots[$pl['uid2']]))
						{
							//������� ���� � �� �� �������
							$botR[$pl['uid2']] = $pl['id'];	
							if( $this->users[$this->uids[$pl['uid2']]]['timeGo'] < time() ) {
								$this->botAtack($pl['uid1'],$pl,2);
							}
						}
					}else{
						//������� �� �����
						if($pl['a1']==0 && $pl['a2']==0)
						{
							//����� 1 ��������� �� �����
							$pl['out1'] = time();	
							$pl['tout1'] = 1;	
							//����� 2 ��������� �� �����
							$pl['out2'] = time();	
							$pl['tout2'] = 1;	
						}elseif($pl['a1']==0)
						{
							//����� 1 ��������� �� �����
							$pl['out1'] = time();	
							$pl['tout1'] = 1;					
						}elseif($pl['a2']==0)
						{
							//����� 2 ��������� �� �����
							$pl['out2'] = time();	
							$pl['tout2'] = 1;							
						}
						//������� ���� �� ��������
						$this->atacks[$pl['id']] = $pl;
						$this->startAtack($pl['id']);
					}
				}
			//���� �����
				if($this->uAtc['id']>0)
				{
					if($this->na==1)
					{
						if( $pl['out1'] == 0 && $pl['out2'] == 0 ) { 
							//����� ����������� ������ ��� ��� ���
							if( $pl['uid1'] == $u->info['id'] || $pl['uid2'] == $u->info['id'] ) {
								$this->addNewAtack();
							}
						}else{
							$this->addNewAtack();
						}
					}
				}
			//���� ������������� ��������
				
			//���� ������������� �������
				
			//����, ��� ������ �����
				$i = 0;
				while($i<count($this->bots))
				{				
					$bot = $this->bots[$i];
					if(isset($bot) && $this->stats[$this->uids[$bot]]['hpNow'] >= 1)
					{
						//mysql_query('UPDATE `stats` SET `timeGo` = "'.$tnbot.'" WHERE `id` = "'.$this->users[$this->uids[$bot]]['id'].'" LIMIT 1');
						$j = 0;
						while($j<count($this->users))
						{
							//$tnbot = time() + rand(3,10);
							if( $this->info['razdel'] == 0 ) {
								$tnbot = time();
							}else{
								$tnbot = time() + rand(2,4);
							}
							if( $this->users[$j]['timeGo'] >= time() || $this->users[$this->uids[$bot]]['timeGo'] >= time() ) {
								
							}elseif($this->users[$j]['hpNow'] >= 1 && $this->users[$this->uids[$bot]]['team'] != $this->users[$j]['team']) {
								if(isset($this->users[$j]) && $this->stats[$j]['hpNow']>=1 && $this->stats[$this->uids[$bot]]['hpNow']>=1 && !isset($this->ga[$bot][$this->users[$j]['id']]) && !isset($this->ag[$bot][$this->users[$j]['id']]) && $this->users[$j]['id']!=$bot && $this->users[$j]['team']!=$this->users[$this->uids[$bot]]['team'])
								{
									if($this->users[$j]['timeGo'] < time() && $this->users[$this->uids[$bot]]['timeGo']<time()) {
										$this->botAtack($this->users[$j]['id'],$bot,1);
										mysql_query('UPDATE `stats` SET `timeGo` = "'.$tnbot.'" WHERE `id` = "'.$this->users[$this->uids[$bot]]['id'].'" LIMIT 1');
									}
								}elseif(isset($this->users[$i]) && $this->users[$i]['bot']>0 && $this->stats[$i]['hpNow']>=1 && $this->stats[$this->uids[$bot]]['hpNow']>=1 && $this->users[$i]['id']!=$bot && $this->users[$i]['team']!=$this->users[$this->uids[$bot]]['team']){
									if($this->users[$j]['timeGo'] < time() && $this->users[$this->uids[$bot]]['timeGo']<time()) {
										if($this->botAct($bot)==true)
										{
											if(!isset($this->ga[$bot][$this->users[$i]['id']]) && $this->users[$this->uids[$bot]]['timeGo']<time() && !isset($this->ag[$bot][$this->users[$i]['id']]))
											{
												$this->botAtack($this->users[$i]['id'],$bot,1);
												mysql_query('UPDATE `stats` SET `timeGo` = "'.$tnbot.'" WHERE `id` = "'.$this->users[$this->uids[$bot]]['id'].'" LIMIT 1');
												}elseif(isset($this->ag[$bot][$this->users[$i]['id']]))
											{
											}elseif(isset($this->ga[$bot][$this->users[$i]['id']]) && $this->users[$this->uids[$bot]]['timeGo']<time())
											{
												$this->botAtack($bot,$this->users[$i]['id'],1);
												mysql_query('UPDATE `stats` SET `timeGo` = "'.$tnbot.'" WHERE `id` = "'.$this->users[$this->uids[$bot]]['id'].'" LIMIT 1');
											}
										}
									}
								}else{
									//����� ����� ������
									//
									if($this->users[$j]['timeGo'] < time() && $this->users[$this->uids[$bot]]['timeGo']<time()) {
										$this->atacks[$this->ga[$bot][$this->users[$j]['id']]]['a1'] = rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5);
										$this->atacks[$this->ga[$bot][$this->users[$j]['id']]]['b1'] = rand(1,5);
										$this->atacks[$this->ga[$bot][$this->users[$j]['id']]]['a2'] = rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5);
										$this->atacks[$this->ga[$bot][$this->users[$j]['id']]]['b2'] = rand(1,5);
										$this->atacks[$this->ag[$bot][$this->users[$j]['id']]]['a1'] = rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5);
										$this->atacks[$this->ag[$bot][$this->users[$j]['id']]]['b1'] = rand(1,5);
										$this->atacks[$this->ag[$bot][$this->users[$j]['id']]]['a2'] = rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5).''.rand(1,5);
										$this->atacks[$this->ag[$bot][$this->users[$j]['id']]]['b2'] = rand(1,5);
										if(isset($this->ga[$bot][$this->users[$j]['id']]) && $this->users[$j]['bot']>0)
										{
											if($this->users[$j]['timeGo'] < time() && $this->users[$this->uids[$bot]]['timeGo']<time())
											{
												//$tnbot = time() + rand(3,7);
												if( $this->info['type'] == 329 ) { // �������� ���
													$tnbot = time()-1;
												}
												$this->startAtack($this->ga[$bot][$this->users[$j]['id']]);
												$this->users[$this->uids[$bot]]['timeGo'] = $tnbot;
												mysql_query('UPDATE `stats` SET `timeGo` = "'.$tnbot.'" WHERE `id` = "'.$this->users[$this->uids[$bot]]['id'].'" LIMIT 1');
											}
										}elseif(isset($this->ag[$bot][$this->users[$j]['id']]) && $this->users[$j]['bot']>0){
											if($this->users[$this->uids[$bot]]['timeGo']<time() && $this->users[$j]['timeGo']<time())
											{
												$this->startAtack($this->ag[$bot][$this->users[$j]['id']]);
												//$tnbot = time() + rand(3,7);
												if( $this->info['type'] == 329 ) { // �������� ���
													$tnbot = time()-1;
												}
												$this->users[$this->uids[$bot]]['timeGo'] = $tnbot;
												mysql_query('UPDATE `stats` SET `timeGo` = "'.$tnbot.'" WHERE `id` = "'.$this->users[$this->uids[$bot]]['id'].'" LIMIT 1');
											}
										}
									}
									//
								}
							}
							$j++;
						}
					}
					$i++;
				}
		}
		
	//�������� ����
		public function botAct($uid)
		{
			$r = false;
			if($this->users[$this->uids[$uid]]['bot']>0)
			{
				if($this->users[$this->uids[$uid]]['online'] < time()-3)
				{
					$r = true;
					$this->users[$this->uids[$uid]]['online'] = time();
					mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.((int)$uid).'" LIMIT 1');
				}else{
					if(rand(0,2)==1)
					{
						$r = true;
					}
				}
			}
			return $r;
		}
		
	//�������� ������ � ��������
		public function battleInfo($id)
		{
			$b = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
			if(isset($b['id']))
			{
				$this->hodID = mysql_fetch_array(mysql_query('SELECT `id_hod` FROM `battle_logs` WHERE `battle` = "'.$b['id'].'" ORDER BY `id` DESC LIMIT 1'));
				if(isset($this->hodID['id_hod']))
				{
					$this->hodID = $this->hodID['id_hod'];
				}else{
					$this->hodID = 0;
				}	
				return $b;
			}else{
				return false;
			}
		}
	
	//������� ���� ����������
		public function addAtack()
		{
			global $js;
			if(isset($_POST['atack'],$_POST['block']))
			{
				$na = array('id'=>0,'a'=>array(1=>0,2=>0,3=>0,4=>0,5=>0),'b'=>0);
				$a = explode('_',$_POST['atack']);
				$i = 1;
				$na['id'] = time();
				while($i<=5)
				{
					if(isset($a[$i-1]))
					{
						$a[$i-1] = intval(round($a[$i-1]));
						if($a[$i-1]>=1 && $a[$i-1]<=5)
						{
							$na['a'][$i] = $a[$i-1];
						}else{
							$na['a'][$i] = 0;
						}
					}
					$i++;
				}
				
				$na['b'] = intval(round($_POST['block']));
				if($na['b']<1 || $na['b']>5)
				{
					$na['b'] = 0;
				}
				
				$this->uAtc = $na;
				$js .= 'testClearZone();';
			}else{
				$this->e = '�������� ���� ����� � �����';
			}
		}
		
	//�������� �������������
		public function teamsTake()
		{
			global $u;
			$rs = ''; $ts = array(); $tsi = 0;
			if($this->info['id']>0)
			{
				//������ � ������� � ���
				$nxtlg = array();
				$t = mysql_query('SELECT `u`.`room`,`u`.`no_ip`,`u`.`twink`,`u`.`stopexp`,`u`.`id`,`u`.`notrhod`,`u`.`login`,`u`.`login2`,`u`.`sex`,`u`.`online`,`u`.`admin`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`clan`,`u`.`level`,`u`.`battle`,`u`.`obraz`,`u`.`win`,`u`.`lose`,`u`.`nich`,`u`.`animal`,`st`.`stats`,`st`.`hpNow`,`st`.`mpNow`,`st`.`exp`,`st`.`dnow`,`st`.`team`,`st`.`battle_yron`,`st`.`battle_exp`,`st`.`enemy`,`st`.`battle_text`,`st`.`upLevel`,`st`.`timeGo`,`st`.`timeGoL`,`st`.`bot`,`st`.`lider`,`st`.`btl_cof`,`st`.`tactic1`,`st`.`tactic2`,`st`.`tactic3`,`st`.`tactic4`,`st`.`tactic5`,`st`.`tactic6`,`st`.`tactic7`,`st`.`x`,`st`.`y`,`st`.`battleEnd`,`st`.`priemslot`,`st`.`priems`,`st`.`priems_z`,`st`.`bet`,`st`.`clone`,`st`.`atack`,`st`.`bbexp`,`st`.`res_x`,`st`.`res_y`,`st`.`res_s`,`st`.`id`,`st`.`last_hp`,`st`.`last_pr`,`u`.`sex`,`u`.`money`,`u`.`bot_id`,`u`.`money3` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'"');
				$i = 0; $bi = 0; $up = '';
				if($this->info['start2']==0) {
					$tststrt = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `start2` = "0" LIMIT 1'));
					if(isset($tststrt['id'])) {
						$upd = mysql_query('UPDATE `battle` SET `start2` = "'.time().'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
					}else{
						$this->info['start2'] = time();
					}

				}
				while($pl = mysql_fetch_array($t))
				{					
					//���������� ������
					if($pl['login2']=='')
					{
						$pl['login2'] = $pl['login'];
					}
					$this->users[$i] = $pl;
					$this->uids[$pl['id']] = $i;
					if($pl['bot']>0)
					{
						$this->bots[$bi] = $pl['id'];
						$this->iBots[$pl['id']] = $bi;
						$bi++;
					}
					//���������� �����
					$this->stats[$i] = $u->getStats($pl,0,0,false,$this->cached,true);
					//������� �����
					if($this->info['start2'] == 0 )
					{
						if(!isset($ts[$this->users[$i]['team']]))
						{
							$tsi++;
							$ts[$this->users[$i]['team']] = $tsi;
						}
						
						if($this->users[$i]['level'] <= 7) {
							$this->users[$i]['tactic7'] = floor(10/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						} elseif($this->users[$i]['level'] == 8) {
							$this->users[$i]['tactic7'] = floor(20/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						} elseif($this->users[$i]['level'] == 9) {
							$this->users[$i]['tactic7'] = floor(30/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						} elseif($this->users[$i]['level'] >= 10) {
							$this->users[$i]['tactic7'] = floor((40+$this->stats[$i]['s7'])/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
						}
						
						if( $this->stats[$i]['s7'] > 49 ) {
							mysql_query("
								INSERT INTO `eff_users` ( `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
								( 22, '".$this->stats[$i]['id']."', '��������', 'add_spasenie=1', 0, 77, 0, '".$this->stats[$i]['id']."', 0, 'priem', 324, 'preservation.gif', 1, -1, '��������', 0, 0, '', 0, 0, 0, 1, 0);
							");
						}
						

						
                        #��� ����� ��������� ������� ����) Ost. Costa
                        #$this->users[$i]['tactic7'] += $this->stats[$i]['s7'];
                        #####
						// ���� ����� animal_bonus
						if($this->users[$i]['animal'] > 0) {

							$a = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `uid` = "'.$this->users[$i]['id'].'" AND `id` = "'.$this->users[$i]['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
							if(isset($a['id'])) {
								if($a['eda']>=1) {
									$anl = mysql_fetch_array(mysql_query('SELECT `bonus` FROM `levels_animal` WHERE `type` = "'.$a['type'].'" AND `level` = "'.$a['level'].'" LIMIT 1'));
									$anl = $anl['bonus'];
									
									$tpa = array(1=>'cat',2=>'owl',3=>'wisp',4=>'demon',5=>'dog',6=>'pig',7=>'dragon');
									$tpa2 = array(1=>'����',2=>'����',3=>'��������',4=>'�������',5=>'���',6=>'�����',7=>'�������');
									$tpa3 = array(1=>'������� ��������',2=>'�������� ����',3=>'���� ������',4=>'������������ ����',5=>'����',6=>'������ �����',7=>'�������');
									
									mysql_query('INSERT INTO `eff_users` (`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`,`user_use`) VALUES ("-1","201","summon_pet_'.$tpa[$a['type']].'.gif",22,"'.$this->users[$i]['id'].'","'.$tpa3[$a['type']].' ['.$a['level'].']","'.$anl.'","0","77","priem","'.$this->users[$i]['id'].'")');
									
									$anl = $u->lookStats($anl);
									
									$vLog = 'time1='.time().'||s1='.$this->users[$i]['sex'].'||t1='.$this->users[$i]['team'].'||login1='.$this->users[$i]['login'].'';
									$vLog .= '||s2=1||t2='.$this->users[$i]['team'].'||login2='.$a['name'].' (����� '.$this->users[$i]['login'].')';
									
									$mas1 = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>1,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
									
									$ba = '';
									$i6 = 0;
									while($i6<count($u->items['add'])) {
										if(isset($anl['add_'.$u->items['add'][$i6]])) {
											if($anl['add_'.$u->items['add'][$i6]] > 0) {
												if( $u->items['add'][$i6] == 'mib1') {
													$ba .= '����� ������: +'.$anl['add_'.$u->items['add'][$i6]].', ';
												}elseif( $u->items['add'][$i6] == 'mib2') {
													$ba .= '����� �������: +'.$anl['add_'.$u->items['add'][$i6]].', ';
												}elseif( $u->items['add'][$i6] == 'mib3') {
													$ba .= '����� �����: +'.$anl['add_'.$u->items['add'][$i6]].', ';
												}elseif( $u->items['add'][$i6] == 'mib4') {
													$ba .= '����� ���: +'.$anl['add_'.$u->items['add'][$i6]].', ';
												}elseif( $u->items['add'][$i6] == 'mab1' || $u->items['add'][$i6] == 'mab2' || $u->items['add'][$i6] == 'mab3' || $u->items['add'][$i6] == 'mab4' ) {
													
												}else{
													$ba .= $u->is[$u->items['add'][$i6]].': +'.$anl['add_'.$u->items['add'][$i6]].', ';
												}
											}
										}
										$i6++;
									}
									$ba = trim($ba,', ');
									if($ba == '') {
										$ba = '������ ����������';
									}
									
									$mas1['text'] = '{tm1} {u2} ������� �� ���������, � ������� �������� &quot;<b>'.$tpa3[$a['type']].' ['.$a['level'].']</b>&quot; �� {u1}. ('.$ba.')';
									$nxtlg[count($nxtlg)] = $mas1;
									mysql_query('UPDATE `users_animal` SET `eda` = `eda` - 1 WHERE `id` = "'.$a['id'].'" LIMIT 1');
									//$this->add_log($mas1);
									$this->get_comment();
								}else{
									//$u->send('',$this->users[$i]['room'],$this->users[$i]['city'],'',$this->users[$i]['login'],'<b>'.$a['name'].'</b> ��������� � ���...',time(),6,0,0,0,1);
								}
							}
						}
												
						mysql_query('UPDATE `stats` SET `last_hp` = "0",`tactic1`="0",`tactic2`="0",`tactic3`="0",`tactic4`="0",`tactic5`="0",`tactic6`="0",`tactic7` = "'.($this->users[$i]['tactic7']).'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');

						$rs[$this->users[$i]['team']] .= $u->microLogin($this->users[$i],2).', ';
					}
					$up .= '`uid` = "'.$pl['id'].'" OR';
					//battle-user (����������, ���������)
					$mybu = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle_users` WHERE `battle` = "'.$this->info['id'].'" AND `uid` = "'.mysql_real_escape_string($pl['id']).'" LIMIT 1'));
					if(!isset($mybu['id'])) {
						//������ �������� ���������� ��� ������� ��������� �� ������� ���
						$this->addstatuser($pl['id']);
					}
					$i++;
				}
				
				/*
				if($i == 0) {
					$t = mysql_query('SELECT `u`.*,`st`.* FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`battle` = "'.$this->info['id'].'" AND `st`.`hpNow` > 0');
					$i = 0; $bi = 0; $up = '';
					while($pl = mysql_fetch_array($t))
					{
						//���������� ������
						if($pl['login2']=='')
						{
							$pl['login2'] = $pl['login'];
						}
						$this->users[$i] = $pl;
						$this->uids[$pl['id']] = $i;
						if($pl['bot']>0)
						{
							$this->bots[$bi] = $pl['id'];
							$this->iBots[$pl['id']] = $bi;
							$bi++;
						}
						//���������� �����
						$this->stats[$i] = $u->getStats($pl,0);
						//������� �����
						if($this->info['start1']==0)
						{
							if(!isset($ts[$this->users[$i]['team']]))
							{
								$tsi++;
								$ts[$this->users[$i]['team']] = $tsi;
							}
							
							if($this->users[$i]['level']<=7)
							{
								$this->users[$i]['tactic7'] = floor(10/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}elseif($this->users[$i]['level']==8)
							{
								$this->users[$i]['tactic7'] = floor(20/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}elseif($this->users[$i]['level']==9)
							{
								$this->users[$i]['tactic7'] = floor(30/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}elseif($this->users[$i]['level']>=10)
							{
								$this->users[$i]['tactic7'] = floor(40/$this->stats[$i]['hpAll']*$this->stats[$i]['hpNow']);
							}
							
							$this->users[$i]['tactic7'] += $this->stats[$i]['s7'];
							
							mysql_query('UPDATE `stats` SET `tactic1`="0",`tactic2`="0",`tactic3`="0",`tactic4`="0",`tactic5`="0",`tactic6`="0",`tactic7`="0",`tactic7` = "'.($this->users[$i]['tactic7']).'" WHERE `id` = "'.$this->users[$i]['id'].'" LIMIT 1');
							
							$rs[$tsi] .= $u->microLogin($this->users[$i],2).', ';
						}
						$up .= '`uid` = "'.$pl['id'].'" OR';
						$i++;
					}
				}
				*/
				
				$up = rtrim($up,' OR');
				//mysql_query('UPDATE `eff_users` SET `timeAce` = "0" WHERE ('.$up.') AND `delete` = "0"');
				//echo '<hr><hr><hr>';
				
				//������� � ��� ������ ��������
				
				if($this->info['start1']==0)
				{
					$tststrt = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$this->info['id'].'" AND `start1` = "0" LIMIT 1'));
					if(isset($tststrt['id'])) {
						$upd = mysql_query('UPDATE `battle` SET `start1` = "'.time().'" WHERE `id` = "'.$this->info['id'].'" LIMIT 1');
						if($upd)
						{
							$i = 0; $r = '';
							while($i <= $tsi)
							{
								if(isset($rs[$i]) && $rs[$i] != '') {
									$r .= rtrim($rs[$i],', ').' � ';
								}
								$i++;
							}
							$r = rtrim($r,' � ');
							$r = str_replace('"','\\\\\"',$r);
							$this->hodID++;
							$vLog = 'time1='.time().'||';
							$mass = array('time'=>time(),'battle'=>$this->info['id'],'id_hod'=>$this->hodID,'text'=>'test','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
							$r = '���� ���������� <span class=\\\\\"date\\\\\">'.date('d.m.Y H:i',$this->info['time_start']).'</span>, ����� '.$r.' ������� ����� ���� �����.';
							$ins = mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.$mass['time'].'","'.$mass['battle'].'","'.$mass['id_hod'].'","'.$r.'","'.$mass['vars'].'","'.$mass['zona1'].'","'.$mass['zonb1'].'","'.$mass['zona2'].'","'.$mass['zonb2'].'","'.$mass['type'].'")');
							if(!$ins)
							{
								//echo $r;
							}
							$this->info['start1'] = time();
						}
					}
					
					//
					
					if(count($nxtlg) > 0) {
						$i = 0;
						while($i < count($nxtlg)) {
							$this->add_log($nxtlg[$i]);
							$i++;
						}
					}
					
					//
				}
			}
		}
	
	//��������� ���� ����� �� ���������
		public function restZonb($uid1,$uid2)
		{
			if($this->stnZbVs[$uid1]>0)
			{
				$this->stats[$this->uids[$uid1]]['zonb'] = $this->stnZbVs[$uid1];
			}
			if($this->stnZbVs[$uid2]>0)
			{
				$this->stats[$this->uids[$uid1]]['zonb'] = $this->stnZbVs[$uid2];
			}
		}
	
	//�������� ����� (����������)
		public function testZonbVis()
		{
			global $u;
			if($this->stnZbVs==0)
			{
				$zb = $this->stats[$this->uids[$u->info['id']]]['zonb'];
				$this->stnZbVs = $zb;
			}else{
				$zb = $this->stnZb;
			}
			$eu = $this->users[$this->uids[$u->info['id']]]['enemy'];
			if($zb>3){ $zb = 3; }
			if($eu!='' && $eu!=0)
			{
				if($this->stats[$this->uids[$eu]]['weapon1']==1 || $this->stats[$this->uids[$eu]]['weapon2']==1)
				{
					if($this->stats[$this->uids[$u->info['id']]]['weapon1']!=1 && $this->stats[$this->uids[$u->info['id']]]['weapon2']!=1)
					{
						$zb -= 1;		
					}				
				}
			}
			if($zb<1){ $zb = 1; }
			return $zb;
		}
		
	//�������� �����
		public function testZonb($uid,$uid2)
		{
			global $u;
			$zba = array(1=>0,2=>0);
			
			$zba[1] = $this->stats[$this->uids[$uid]]['zonb'];
			$zba[2] = $this->stats[$this->uids[$uid2]]['zonb'];
			
			if($this->stnZb[$uid]==0)
			{
				$zba[1] = $this->stats[$this->uids[$uid]]['zonb'];
				$this->stnZb[$uid] = $zba[1];
			}else{
				$zba[1] = $this->stnZb[$uid];
			}
			
			if($this->stnZb[$uid2]==0)
			{
				$zba[2] = $this->stats[$this->uids[$uid2]]['zonb'];
				$this->stnZb[$uid] = $zba[2];
			}else{
				$zba[2] = $this->stnZb[$uid2];
			}
			
			if($zba[1]>3){ $zba[1] = 3; }
			if($zba[2]>3){ $zba[2] = 3; }
			
			//����� ������ 1
			if($this->stats[$this->uids[$uid2]]['weapon1']==1 || $this->stats[$this->uids[$uid2]]['weapon2']==1)
			{
				if($this->stats[$this->uids[$uid]]['weapon1']!=1 && $this->stats[$this->uids[$uid]]['weapon2']!=1)
				{
					$zba[1] -= 1;		
				}				
			}
			
			//����� ������ 2
			if($this->stats[$this->uids[$uid]]['weapon1']==1 || $this->stats[$this->uids[$uid]]['weapon2']==1)
			{
				if($this->stats[$this->uids[$uid2]]['weapon1']!=1 && $this->stats[$this->uids[$uid2]]['weapon2']!=1)
				{
					$zba[2] -= 1;		
				}				
			}			
			
			if($zba[1]<1){ $zba[1] = 1; }
			if($zba[2]<1){ $zba[2] = 1; }
						
			$this->stats[$this->uids[$uid]]['zonb'] = $zba[1];
			$this->stats[$this->uids[$uid2]]['zonb'] = $zba[2];
			if($this->stats[$this->uids[$uid]]['min_zonb']>0 && $this->stats[$this->uids[$uid]]['zonb']<$this->stats[$this->uids[$uid]]['min_zonb'])
			{
				$this->stats[$this->uids[$uid]]['zonb'] = $this->stats[$this->uids[$uid]]['min_zonb'];
			}
			if($this->stats[$this->uids[$uid2]]['min_zonb']>0 && $this->stats[$this->uids[$uid2]]['zonb']<$this->stats[$this->uids[$uid2]]['min_zonb'])
			{
				$this->stats[$this->uids[$uid2]]['zonb'] = $this->stats[$this->uids[$uid2]]['min_zonb'];
			}	
		}
	
	//���������� �������
		public function genTeams($you)
		{
			$ret = '';
			$teams = array( );
			//�������� �������������
			$i = 0;	 $j = 1; $tms =	array( );
			//if( $this->users[$this->uids[$you]]['team'] > 0 && $this->stats[$this->uids[$you]]['hpNow'] > 0 ) {
				$teams[$this->users[$this->uids[$you]]['team']] = '';
				$tms[0] = $this->users[$this->uids[$you]]['team'];
			//}
			while($i<count($this->uids))
			{
				if($this->stats[$i]['hpNow']>0)
				{
					if(!isset($teams[$this->users[$i]['team']]))
					{
						$tms[$j] = $this->users[$i]['team'];
						$j++;
					}	
					if($this->stats[$i]['hpNow']<0){ $this->stats[$i]['hpNow'] = 0; }
					$a1ms = '';
					if(isset($this->ga[$this->users[$i]['id']][$you]) && $this->ga[$this->users[$i]['id']][$you]!=false)
					{
						$a1mc = '';
						$ac = mysql_fetch_array(mysql_query('SELECT * FROM `battle_act` WHERE `id` = "'.$this->ga[$this->users[$i]['id']][$you].'" LIMIT 1'));
						if(isset($ac) && $ac['time']+$this->info['timeout']-15<time())
						{
							$a1mc = 'color:red;';
						}
						$a1ms = 'style=\"text-decoration: underline; '.$a1mc.'\"';
					}elseif(isset($this->ag[$this->users[$i]['id']][$you]) && $this->ag[$this->users[$i]['id']][$you]!=false)
					{
						$a1mc = '';
						$ac = mysql_fetch_array(mysql_query('SELECT * FROM `battle_act` WHERE `id` = "'.$this->ag[$this->users[$i]['id']][$you].'" LIMIT 1'));
						if(isset($ac) && $ac['time']+$this->info['timeout']-15<time())
						{
							$a1mc = 'color:green;';
						}
						$a1ms = 'style=\"text-decoration: overline; '.$a1mc.'\"';
					}		
					if($this->users[$i]['login2']=='')
					{
						$this->users[$i]['login2'] = $this->users[$i]['login'];
					}
					if ($this->users[$i]['align']==9){
						$this->stats[$i]['hpNow'] = $this->stats[$i]['hpNow']/($this->stats[$i]['hpAll']/100); 
						$this->stats[$i]['hpAll'] = '100%';
					}
					$ldr = '';
					if( $this->users[$i]['lider'] == $this->info['id'] ) {
						$ldr = '<img width=24 height=15 title=�����&nbsp;������ src=http://img.xcombats.com/i/lead'.$this->users[$i]['team'].'.gif \>';	
					}
					$teams[$this->users[$i]['team']] .= ', '.$ldr.'<span '.$a1ms.' class=\"CSSteam'.$this->users[$i]['team'].'\" onClick=\"top.chat.addto(\''.$this->users[$i]['login2'].'\',\'to\'); return false;\" oncontextmenu=\"top.infoMenu(\''.$this->users[$i]['login2'].'\',event,\'main\'); return false;\">'.$this->users[$i]['login2'].'</span><small> ['.floor($this->stats[$i]['hpNow']).'/'.$this->stats[$i]['hpAll'].']</small>';
				}
				$i++;				
			}
			
			//���������� �������
			$i = 0;
			while($i<count($tms))
			{
				$teams[$tms[$i]] = ltrim($teams[$tms[$i]],', ');
				if( $teams[$tms[$i]] != '' ) {
					if($u->info['team'] == $tms[$i]) {
						$teams[$tms[$i]] = '<img src=\"http://img.xcombats.com/i/lock3.gif\" style=\"cursor:pointer\" width=\"20\" height=\"15\" onClick=\"top.chat.addto(\'team'.$tms[$i].'\',\'private\'); return false;\"> '.$teams[$tms[$i]];
					}else{
						$teams[$tms[$i]] = '<img src=\"http://img.xcombats.com/i/lock3.gif\" style=\"cursor:pointer\" width=\"20\" height=\"15\" onClick=\"top.chat.addto(\'team\',\'private\'); return false;\"> '.$teams[$tms[$i]];
					}
					$ret .= $teams[$tms[$i]];				
					if(count($tms)>$i+1)
					{
						$ret .= ' <span class=\"CSSvs\">&nbsp; ������ &nbsp;</span> ';
					}
				}
				$i++;
			}
			return 'genteam("'.$ret.'");';
		}
		
		
		public function addTravm($uid,$type,$lvl)
		{
		    global $u;
			$t=$type;
		    if($t==1){
			        $name='������ ������';
			        $stat=rand(1, 3); // ���� ��� ����������
					$timeEnd=rand(1,3);// ����� ������ �� 1.30 �� 6 �����
					$data='add_s'.$stat.'=-'.$lvl;
					$img = 'eff_travma1.gif';
					$v1=1;
					//echo '<b><font color=red>'.$name.'</font></b>';
			}elseif($t==2){
			        $name='������� ������';
			        $stat=rand(1, 3); // ���� ��� ����������
					$timeEnd=rand(3,5);// ����� ������ �� 6 �� 12 �����
					$data='add_s'.$stat.'=-'.($lvl*2);
					$v1=2;
					$img = 'eff_travma2.gif';
			}
			elseif($t==3){
			        $name='������� ������';
			        $stat=rand(1, 3); // ���� ��� ����������
					$timeEnd=rand(5,7);// ����� ������ �� 12 �� 6 �����
					$data='add_s'.$stat.'=-'.($lvl*3);
					$v1=3;
					$img = 'eff_travma3.gif';
			}
			$timeEnd = $timeEnd*3600;
			$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES ("0","'.time().'","-1","'.$name.'","'.$data.'","'.$uid.'", "4", "'.$img.'","'.$timeEnd.'", "'.$v1.'")');
			//$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES ("0","'.time().'","-1","���������: ������ �� �����","add_notravma=1","'.$uid.'", "263", "cure1.gif","21600", "")');
		}
		
		public function testUserInfoBattle($uid) {
			global $u;
			if(!isset($this->uids[$uid])) {
				//������� ������ �� ������ ����� (������ �� ����� � ��� ������ � ����)
				if(!isset($this->uids[$uid])) {
					$this->uids[$uid] = count($this->users);
				}
				$this->users[$this->uids[$uid]] = mysql_fetch_array(mysql_query('SELECT
								
				`u`.`id`,`u`.`login`,`u`.`real`,`u`.`login2`,`u`.`online`,`u`.`admin`,`u`.`city`,`u`.`cityreg`,`u`.`align`,`u`.`align_lvl`,`u`.`align_exp`,`u`.`clan`,
				`u`.`level`,`u`.`money`,`u`.`money3`,`u`.`money4`,`u`.`battle`,`u`.`sex`,`u`.`obraz`,`u`.`win`,`u`.`win_t`,
				`u`.`lose`,`u`.`lose_t`,`u`.`nich`,`u`.`timeMain`,`u`.`invis`,`u`.`bot_id`,`u`.`animal`,`u`.`type_pers`,
				`u`.`notrhod`,`u`.`bot_room`,`u`.`inUser`,`u`.`inTurnir`,`u`.`inTurnirnew`,`u`.`activ`,`u`.`stopexp`,`u`.`real`,
										
				`st`.*				
				
				FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`id` = "'.$uid.'" LIMIT 1'));
				$this->stats[$this->uids[$uid]] = $u->getStats($this->users[$this->uids[$uid]],0,0,false,false,true);
			}
		}
		
}

$btl = new battleClass;
?>