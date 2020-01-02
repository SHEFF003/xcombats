<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='secret')
{
?>
<style type="text/css">
.pH3 {COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><div align="center" class="pH3">Секретная комната</div>
    <br /></td>
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
                      <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.3&amp;rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.3',1); ?>">Бойцовский клуб</a></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
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