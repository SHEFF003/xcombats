<?
if(!defined('GAME'))
{
	die();
}
$tattack = '';

if($u->room['file']=='ab/izlom16')
{
	if(date("H")>=6 && date("H")<22) {
	$now = 'day';
	} else { $now = 'night'; }
	if($u->info['level'] >= 4)
	{
		if(date("H")>=6 && date("H")<22) 
		{
		$tattack = '<span onMouseMove="this.runtimeStyle.color = \'white\';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="">��������� �������� � 22 �� 6 �.</span>';
		} else {
		if(isset($_POST['attack'])) 
		{
		$magic->magicCentralAttack();
		}
		$tattack = '<span onMouseMove="this.runtimeStyle.color = \'white\';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="findlogin(\'�������\',\'attack\',\'\',\'\');">�������</span>';
		}
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; }  ?>
    </td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right">
      <table  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td>
                <? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <div style="position:relative; cursor: pointer;" id="ione">
                      <img src="http://img.xcombats.com/i/images/300x225/ap_bg_iz_npc100500.jpg" alt="" name="img_ione" width="500" height="268" border="1" id="img_ione"/>
                      <div id="buttons_on_image" style="cursor:pointer; font-weight:bold; color:#D8D8D8; font-size:10px;">&nbsp;
                          <span onMouseMove="this.runtimeStyle.color = 'white';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="window.open('http://xcombats.com/forum', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')">�����</span> &nbsp;
                      </div>
                      <script language="javascript" type="text/javascript">
                        <!--
                        if(document.getElementById('ione'))
                        {
                            document.getElementById('ione').appendChild(document.getElementById('buttons_on_image'));
                            document.getElementById('buttons_on_image').style.position = 'absolute';
                            document.getElementById('buttons_on_image').style.bottom = '8px';
                            document.getElementById('buttons_on_image').style.right = '23px';
                        }else{
                            document.getElementById('buttons_on_image').style.display = 'none';
                        }
                        -->
                      </script>
                      <div style="position: absolute; left: 430px; top: 196px; width: 48px; height: 33px; z-index: 94;"><img <? thisInfRm('3.180.0.268'); ?> src="http://img.xcombats.com/i/images/300x225/ab_iz_exit.gif" width="48" height="33" class="aFilter" /></div>
                      <div style="position: absolute; left: 278px; top: 53px; width: 167px; height: 94px; z-index: 94;"><img <? thisInfRm('3.180.0.269'); ?> src="http://img.xcombats.com/i/images/300x225/ab_iz_gate.gif" width="167" height="94" class="aFilter" /></div>
					  <div style="position: absolute; left: 22px; top: 161px; width: 75px; height: 68px; z-index: 94;"><img <? thisInfRm('3.180.0.??'); ?> src="http://img.xcombats.com/i/images/300x225/ab_iz_shop.gif" width="75" height="68" class="aFilter" /></div>
                      <div style="position: absolute; left: 112px; top: 13px; width: 75px; height: 68px; z-index: 94;"><img onclick="location.href='main.php?talk=4'" src="http://img.xcombats.com/i/images/300x225/ab_iz_npc.gif" width="110" height="177" class="aFilter" title="������ � �����" /></div>
                      <div id="snow"></div>
                        <? echo $goline; ?>
                      </div>
                    </td>
                  </tr>
                </table>   
                <div style="display:none; height:0px " id="moveto"></div>     
              <div align="right" style="padding: 3px;"><small>&laquo;<? echo $c['title3']; ?>&raquo; ������������ ���, <b><? echo $u->info['login']; ?></b>. �� �������� �� ����������� ������� Abandoned Plain.<br />
              </small></div></td>
          <td>
              <!-- <br /><span class="menutop"><nobr>������� ��� ��������</nobr></span>-->
          </td>
        </tr>
      </table>
      	<small>
        <HR>
        <? $hgo = $u->testHome(); if(!isset($hgo['id'])){ ?><INPUT onclick="location.href='main.php?homeworld=<? echo $code; ?>';" class="btn" value="�������" type="button" name="combats2"><? } unset($hgo); ?>
        <INPUT id="forum" class="btn" onclick="window.open('http://xcombats.com/forum/', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')" value="�����" type="button" name="forum">
          <INPUT class="btn" onclick="window.open('/encicl/help/top1.html', 'help', 'height=300,width=500,location=no,menubar=no,status=no,toolbar=no,scrollbars=yes')" value="���������" type="button">
          <INPUT class="btn" value="�������" type="button">
        <br />
        <strong>��������!</strong> ������� � ������ �� �������� ������ �� ������ ���������. �� ������� ������ �� ������ ������, ���� "����� �����", "�������", "���, ��� ��� ���� �� ������". ������ �� ����� �� ���������, �� ������, �� �������������, <U>������ ����������</U> ��� ����� ������ �����.<BR>
        <em>�������������.</em></small> <BR>
        <? echo $rowonmax; ?><BR>
        
      </div></td>
  </tr>
</table>
<?
}

?>