<?
if(!defined('GAME')){
	die();
}

if($u->room['file']=='magportal_podval') {
?>
<style type="text/css">
.pH3 {COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt; padding:20px 10px; FONT-WEIGHT: bold; }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
			<div align="center" class="pH3"><?=$u->room['name']?></div> 
		</td>
		<td width="280" valign="top"><table align="right" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%">&nbsp;</td>
				<td>
					<table  border="0" cellpadding="0" cellspacing="0">
						<tr align="right" valign="top">
							<td>
								<!-- -->
								<? echo $goLis; ?>
								<!-- -->
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td nowrap="nowrap">
											<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
												<tr>
													<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td> 
													<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.321&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.321',1); ?>">Покинуть помещение</a></td>
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
		</td>
	</tr>
	<tr>
		<td >
			<div>
				<i>— Как хорошо, что вы очнулись! <strong>Страж Подземелья</strong> нашел вас без сознания и принес в эту комнату.</i><br/>
			</div>
		</td>
		<td></td>
	</tr>
</table>
<?
}
?>