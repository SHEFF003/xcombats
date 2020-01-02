<?

namespace Logic;

use \Core\View as view;

class Admin {
	
	/*
	@ ������� ����� ����� ��������� ������
	@ ����� ���������� ����������� ���� ������, � ���-��
	@ �������� ����������� ��������� ������� ������
	@ ��� ������������� ������ ������ ���� ������������!
	*/
	public static function defaultAction() {
		
		//���������� ������
		\Core\User::connect();
		\Core\User::room();
		
		if (\Core\User::$data == false ) {
			//��� �������, �������� �� ������������� ��� ������������
			echo '��������������� ����� <a href="/index.php">������� ��������</a>.';
		}elseif( stristr($_SERVER['HTTP_ACCEPT'],'application/json') == true ) {
			echo self::getJSON();
		}else{
			echo self::getHTML();
		}
	}
	
	/*
	@ ����� ��������� HTML-������� �� ������� ������������
	@ ����� ���������� ������������
	*/
	public static function getHTML() {		
		//PC ������ ������� ��������
		//
		return view::generateTpl( 'admin', array(
			'title'		=> COPY . ' :: ������ ������',
			
			//�������� ������ �������
			'user'		=> \Core\User::$data,
			'stats'		=> \Core\User::$stats,
			'room'		=> \Core\User::$room,
			
			'OK'		=> OK,
			'copy'		=> COPY,
			'rights'	=> RIGHTS,
						
			'ver'		=> '0.0.1'
		) );
	}
	
	/*
	@ ����� ��������� JSON-������� �� ������� ������������
	@ ���������� ������� �� ���������� self::$JSON
	*/
	public static function getJSON() {		
		$r = array();
				
		return \Core\Utils::jsonencode( $r );
	}
	
}

?>