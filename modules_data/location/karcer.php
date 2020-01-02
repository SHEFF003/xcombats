<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='karcer')
{
	if(isset($_GET['karcer_back'])) {
		if( $u->info['jail'] > time() ) {
			$u->error = 'Вы не можете выйти из карцера раньше срока';
		}else{
			//выпускаем
				$upd = mysql_query('UPDATE `users` SET `jail` = "0", `room`="9", `city`="capitalcity" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				if($upd)
				{
					mysql_query('UPDATE `items_users` SET `delete` = "0" WHERE `uid` = '.$u->info['id'].' AND `delete` = "1357908642"');
					$u->error = 'Вы успешно вышли из тюрьмы';
				}else{
					$u->error = 'Не удалось использовать данное заклятие';
				}
		}
	}
?>
<style type="text/css">
.pH3 {COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><div align="center" class="pH3"><?=$u->room['name']?></div>
    <br />
    <font color=red><b><?=$u->error?></b></font><br />
    <? if( $u->info['jail'] > time() ) { ?>
    Вы заточены в карцере до <?=date('d.m.Y H:i',$u->info['jail'])?>
    <? } ?>
    </td>
    <td width="280" valign="top"><table align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="100%">&nbsp;</td>
        <td><table  border="0" cellpadding="0" cellspacing="0">
          <tr align="right" valign="top">
            <td><!-- -->
              <? echo $goLis; ?>
              <!-- -->
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                    <tr>
                      <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                      <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?karcer_back=1';">Выйти из карцера</a></td>
                    </tr>
                  </table>
                  </td>
                </tr>
              </table>
              </td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
      <div><br />
        <p>&nbsp;</p>
        <p> <br />
          <br />
        </p>
      </div></td>
  </tr>
</table>
<?
}
?>