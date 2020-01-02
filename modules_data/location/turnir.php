<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='turnir')
{

	include('_incl_data/class/__turnir.php');
	
	$tur->locationSee();

}

?>