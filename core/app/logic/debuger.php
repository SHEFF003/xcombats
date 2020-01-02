<?

namespace Logic;

use \Core\View as view;

class Debuger {
	
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
		$skills = array();
		$pl = \Core\Database::query( 'SELECT * FROM `priems` WHERE `activ` = 1 ORDER BY `img` ASC', array(
			//
		), true , true );
		$i = 0;
		while( $i != -1 ) {
			if(!isset($pl[$i])) {
				$i = -2;
			}else{
				$skills .= ',[' . $pl[$i]['id'] . ',"' . $pl[$i]['img'] . '","' . $pl[$i]['name'] . '"]';
			}
			$i++;
		}
		$skills = ltrim($skills,',');
		//
		return view::generateTpl( 'debuger', array(
			'title'		=> COPY . ' :: ������� �� ���� � ����� �����',
			
			//�������� ������ �������
			'user'		=> \Core\User::$data,
			'stats'		=> \Core\User::$stats,
			'room'		=> \Core\User::$room,
			
			'OK'		=> OK,
			'copy'		=> COPY,
			'rights'	=> RIGHTS,
			
			'skills'	=> $skills,			
			
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