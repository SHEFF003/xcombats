<?php
if(!defined('GAME')) {
	die();
}


?>
<style>
body {
	background-color:#e9e9e9;
}
.b_nml {
	min-width: 175px;
	background-color: #f3f0f1;
	text-align: center;
	font-weight: bolder;
	padding:2px;	
}
.b_tbl {
	border:1px solid #f3f0f1;	
}
.fl {
	float:left;
}
.fr {
	float:right;
}
.uerror {
	color:#E20000;
	font-weight: bolder;
}
.upersi {
	border-right:1px solid #666666;
	border-bottom:1px solid #666666;
	border-top:1px solid #ffffff;
	border-left:1px solid #ffffff;
	padding:2px;
}
.CSSteam0{font-weight:bold;cursor:pointer;}
.CSSteam1{font-weight:bold;color:#6666CC;cursor:pointer;}
.CSSteam2{font-weight:bold;color:#B06A00;cursor:pointer;}
.CSSteam3{font-weight:bold;color:#269088;cursor:pointer;}
.CSSteam4{font-weight:bold;color:#A0AF20;cursor:pointer;}
.CSSteam5{font-weight:bold;color:#0F79D3;cursor:pointer;}
.CSSteam6{font-weight:bold;color:#D85E23;cursor:pointer;}
.CSSteam7{font-weight:bold;color:#5C832F;cursor:pointer;}
.CSSteam8{font-weight:bold;color:#842B61;cursor:pointer;}
.CSSteam9{font-weight:bold;color:navy;cursor:pointer;}
.CSSvs{font-weight:bold;}
</style>
<script type="text/javascript" src="http://xcombats.com/js/jquery.js"></script>
<script>
var fight = {
	base:{
			 users:[],
			priems:[ ],
			 teams:[ ]		
		},
	data:{
			me:{
				id:<?=$u->info['id']?>,
				login:"<?=$u->info['login']?>",
				enemy:<?=$u->info['enemy']?>,
				priems:[<?=str_replace('|',',',$u->info['priems'])?>],
				priemz:[<?=str_replace('|',',',$u->info['priems_z'])?>]			
			},
			timeout:1		
		},
	genmain:function() {
			//Генирация персонажа
			$('#u1').html( '<div id="uinf_' + this.data.me.id + '"></div>' );
			this.returnUserInfo(this.data.me.id,false);
			$('#u2').html( '<div id="uinf_' + this.data.me.enemy + '"></div>' );
			this.returnUserInfo(this.data.me.enemy,false);
			//Генирация команды
			this.returnTeamInfo( false );
		},
	returnUserKey:function(id) {
			var r = -1;
			for (var i = 0; i < this.base.users.length; i++) {
				if( this.base.users[i].info != undefined && this.base.users[i].info.id == id ) {
					r = i;
				}
			}
			return r;
		},
	updateUser:function(id, html) {
			$.ajax({
				url:'/fight/take.user.php',
				data:{
					'uid':id
				},
				success:function( data ) {
					data = $.parseJSON(data);
					if( data.id > 0 ) {
						var nid = fight.base.users.length;
						fight.base.users[nid] = {
							info:data
						};
						if( html == true ) {
							fight.returnUserInfo( data.id , true );
						}
					}
				}
			});
		},
	updateTeam:function( html ) {
			$.ajax({
				url:'/fight/take.team.php',
				data:{ },
				success:function( data ) {
					data = $.parseJSON(data);
					fight.base.teams = data;
					if( html == true ) {
						fight.returnTeamInfo( true );
					}
				}
			});
		},
	returnTeamInfo:function( double ) {
			if( this.base.teams.length > 0 ) {
				var r = '', t1 = {}, t2 = [];
				for (var i = 0; i < this.base.teams.length; i++) {
					if( this.base.teams[i] != undefined ) {
						var rud = '' + this.returnTeamUser( this.base.teams[i] ) + '';						
						if( t1[this.base.teams[i].team] == undefined ) {
							t1[ this.base.teams[i].team ] = '';
							t2[ t2.length ] = this.base.teams[i].team;
						}else{
							t1[ this.base.teams[i].team ] += ', ';
						}
						t1[ this.base.teams[i].team ] += rud;
					}
				}
				var i = 0;
				while( i < t2.length ) {
					if( t2[i] != undefined && t1[ t2[i] ] != undefined ) {
						if( i != 0 ) {
							r += ' &nbsp;<span class="CSSvs">против</span>&nbsp; ';
						}
						r += t1[ t2[i] ];
					}
					i++;
				}
				$('#u_teams').html( r );
			}else{
				if( double == false ) {
					this.updateTeam( true );
				}
			}
		},
	returnTeamUser:function( user ) {
		var r = '';
		if( user.id != undefined ) {
			user.hpNow = Math.floor(user.hpNow);
			if( user.hpNow < 0 ) {
				user.hpNow = 0;
			}else if( user.hpAll < user.hpNow ) {
				user.hpNow = user.hpAll;
			}
			r += '<span class="CSSteam' + user.team + '">' + user.login + '</span><small> [' + user.hpNow + '/' + user.hpAll + ']</small>';
		}else{
			r = '<i>Персонаж ?</i>';
		}
		return r;
	},
	returnUserInfo:function(id , double) {
			var uid = this.returnUserKey(id);
			if( uid >= 0 ) {
				$('#uinf_' + this.base.users[uid].info.id).html('<div>' + this.userPers( uid ) + '</div>');
				if( this.data.me.id == this.base.users[uid].info.id ) {
					$('#u_log1').html( this.userInf( uid ) );
				}else if( this.data.me.enemy == this.base.users[uid].info.id ) {
					$('#u_log2').html( this.userInf( uid ) );
				}
			}else{
				if( double == false ) {
					this.updateUser(id,true);
				}else{
					$('#uinf_' + id).html('Error: User #' + id + ' is not found');
				}
			}
		},
	userPers:function( uid ) {
			var r = '';
			if( this.base.users[uid] != undefined ) {
				var itms = {
					1 : '<img src="http://img.xcombats.com/i/items/w/w9.gif" width="60" height="60">',
					2 : '<img src="http://img.xcombats.com/i/items/w/w13.gif" width="60" height="40">',
					3 : '<img src="http://img.xcombats.com/i/items/w/w3.gif" width="60" height="60">',
					4 : '<img src="http://img.xcombats.com/i/items/w/w4.gif" width="60" height="80">',
					5 : '<img src="http://img.xcombats.com/i/items/w/w5.gif" width="60" height="40">',
					6 : '<img src="http://img.xcombats.com/i/items/w/w1.gif" width="60" height="20">',
					7 : '<img src="http://img.xcombats.com/i/items/w/w2.gif" width="60" height="20">',
					8 : '<img src="http://img.xcombats.com/i/items/w/w6.gif" width="20" height="20">',
					9 : '<img src="http://img.xcombats.com/i/items/w/w6.gif" width="20" height="20">',
					10: '<img src="http://img.xcombats.com/i/items/w/w6.gif" width="20" height="20">',
					11: '<img src="http://img.xcombats.com/i/items/w/w11.gif" width="60" height="40">',
					12: '<img src="http://img.xcombats.com/i/items/w/w10.gif" width="60" height="60">',
					13: '<img src="http://img.xcombats.com/i/items/w/w19.gif" width="60" height="80">',
					14: '<img src="http://img.xcombats.com/i/items/w/w12.gif" width="60" height="40">',
					//
					15: '<img src="http://img.xcombats.com/i/items/w/w15.gif" width="40" height="20">',
					16: '<img src="http://img.xcombats.com/i/items/w/w15.gif" width="40" height="20">',
					17: '<img src="http://img.xcombats.com/i/items/w/w15.gif" width="40" height="20">',
					18: '<img src="http://img.xcombats.com/i/items/w/w20.gif" width="40" height="20">',
					19: '<img src="http://img.xcombats.com/i/items/w/w20.gif" width="40" height="20">',
					20: '<img src="http://img.xcombats.com/i/items/w/w20.gif" width="40" height="20">'
				};
				
				r += '<table class="upersi" width="240" height="280" border="0" cellspacing="0" cellpadding="0">'+
					  '<tr>'+
						'<td width="60" align="center" valign="top"><table width="60" border="0" cellspacing="0" cellpadding="0">'+
						  '<tr>'+
							'<td height="60">' + itms[1] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="40">' + itms[2] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="60">' + itms[3] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="80">' + itms[4] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="40">' + itms[5] + '</td>'+
						  '</tr>'+
						'</table></td>'+
						'<td width="120"><table width="120" border="0" cellspacing="0" cellpadding="0">'+
						  '<tr>'+
							'<td height="20" style="background-color:#d7d7d7" valign="middle"></td>'+
							'</tr>'+
						  '<tr>'+
							'<td style="background-image:url(\'http://img.xcombats.com/i/obraz/' + this.base.users[uid].info['sex'] + '/' + this.base.users[uid].info['obraz'] + '\')" height="220" align="left" valign="top">&nbsp;</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="40"><table width="120" border="0" cellpadding="0" cellspacing="0">'+
							  '<tr>'+
								'<td height="20">' + itms[15] + '</td>'+
								'<td>' + itms[16] + '</td>'+
								'<td>' + itms[17] + '</td>'+
							  '</tr>'+
							  '<tr>'+
								'<td height="20">' + itms[18] + '</td>'+
								'<td>' + itms[19] + '</td>'+
								'<td>' + itms[20] + '</td>'+
							  '</tr>'+
							'</table></td>'+
						  '</tr>'+
						'</table></td>'+
						'<td width="60" align="center" valign="top"><table width="60" border="0" cellspacing="0" cellpadding="0">'+
						  '<tr>'+
							'<td height="20">' + itms[6] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="20">' + itms[7] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="20"><table width="60" height="20" border="0" cellspacing="0" cellpadding="0">'+
							  '<tr>'+
								'<td width="20">' + itms[8] + '</td>'+
								'<td>' + itms[9] + '</td>'+
								'<td width="20">' + itms[10] + '</td>'+
							  '</tr>'+
							'</table></td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="40">' + itms[11] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="60">' + itms[12] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="80">' + itms[13] + '</td>'+
						  '</tr>'+
						  '<tr>'+
							'<td height="40">' + itms[14] + '</td>'+
						  '</tr>'+
						'</table></td>'+
					  '</tr>'+
					'</table>';
			}
			return r;
		},
	userInf:function( uid ) {
			var r = '';
			if( this.base.users[uid] != undefined ) {
				if( this.base.users[uid].info['align'] > 0 ) {
					r += '<img src="http://img.xcombats.com/i/align/align' + this.base.users[uid].info['align'] + '.gif" width="12" height="15">';
				}
				if( this.base.users[uid].info['clan'] > 0 ) {
					r += '<img src="http://img.xcombats.com/i/clan/' + this.base.users[uid].info['clan'] + '.gif" width="24" height="15">';
				}
				r += '<a href="javascript:void(0)" onclick="alert(\'Menu is disabled\');">' + this.base.users[uid].info['login'] + '</a>[' + this.base.users[uid].info['level'] + ']';
				r += '<a href="http://xcombats.com/info/' + this.base.users[uid].info['id'] + '" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" width="12" height="11"></a>';
			}
			return r;	
		},
	f5:function() {
			location.href = location.href;
		},
	start:function() {
			this.genmain();
			$('#ua_2').css('display','none');
			$('#ua_3').css('display','none');
			$('#ua_4').css('display','none');
			$('#ua_5').css('display','none');
		}
};
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="250" height="300" align="left" valign="top"><div id="u1"></div></td>
        <td align="center" valign="top">
        <!-- -->
        <div id="u_logins">
        	<div id="u_log1" class="fl"></div>
            <div id="u_log2" class="fr"></div>
        </div>
        <!-- -->
        <div style="padding-top:10px">&nbsp;</div>
        <!-- -->
        <div id="u_error" class="uerror"><?=date('d.m.Y H:i:s')?> Произошла ошибка подключения к серверу</div>
        <!-- -->
        <div id="u_magics">&nbsp;</div>
        <!-- -->
        <div id="u_main">
            <table class="b_tbl" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                <!-- -->
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><div class="b_nml">Атака</div></td>
                    <td width="10">&nbsp;</td>
                    <td><div class="b_nml">Защита</div></td>
                  </tr>
                  <tr>
                    <td align="center">
                    <!-- -->
                    <table id="ua_1" class="fl" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="radio" name="ua1" id="ua11" value="1" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua1" id="ua12" value="2" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua1" id="ua13" value="3" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua1" id="ua14" value="4" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua1" id="ua15" value="5" /></td>
                      </tr>
                    </table>
                    <!-- -->
                    <table id="ua_2" class="fl" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="radio" name="ua2" id="ua11" value="1" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua2" id="ua22" value="2" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua2" id="ua23" value="3" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua2" id="ua24" value="4" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua2" id="ua25" value="5" /></td>
                      </tr>
                    </table>
                    <!-- -->
                    <table id="ua_3" class="fl" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="radio" name="ua3" id="ua31" value="1" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua3" id="ua32" value="2" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua3" id="ua33" value="3" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua3" id="ua34" value="4" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua3" id="ua35" value="5" /></td>
                      </tr>
                    </table>
                    <!-- -->
                    <table id="ua_4" class="fl" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="radio" name="ua4" id="ua41" value="1" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua4" id="ua42" value="2" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua4" id="ua43" value="3" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua4" id="ua44" value="4" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua4" id="ua45" value="5" /></td>
                      </tr>
                    </table>
                    <!-- -->
                    <table id="ua_5" class="fl" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="radio" name="ua5" id="ua51" value="1" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua5" id="ua52" value="2" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua5" id="ua53" value="3" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua5" id="ua54" value="4" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ua5" id="ua55" value="5" /></td>
                      </tr>
                    </table>
                    <!-- -->
                    <table class="fl" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><label for="ua11">удар в голову</label></td>
                      </tr>
                      <tr>
                        <td><label for="ua12">удар в грудь</label></td>
                      </tr>
                      <tr>
                        <td><label for="ua13">удар в живот</label></td>
                      </tr>
                      <tr>
                        <td><label for="ua14">удар в пояс(пах)</label></td>
                      </tr>
                      <tr>
                        <td><label for="ua15">удар по ногам</label></td>
                      </tr>
                    </table>
                    <!-- -->
                    </td>
                    <td>&nbsp;</td>
                    <td align="center">
                    <!-- -->
                    <table class="fr" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><label for="ub11">блок головы и груди</label></td>
                      </tr>
                      <tr>
                        <td><label for="ub12">блок груди и живота</label></td>
                      </tr>
                      <tr>
                        <td><label for="ub13">блок живота и пояса</label></td>
                      </tr>
                      <tr>
                        <td><label for="ub14">блок пояса и ног</label></td>
                      </tr>
                      <tr>
                        <td><label for="ub15">блок ног и головы</label></td>
                      </tr>
                    </table>
                    <!-- -->
                    <table class="fl" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="radio" name="ub1" id="ub11" value="1" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ub1" id="ub12" value="2" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ub1" id="ub13" value="3" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ub1" id="ub14" value="4" /></td>
                      </tr>
                      <tr>
                        <td><input type="radio" name="ub1" id="ub15" value="5" /></td>
                      </tr>
                    </table>
                    <!-- -->
                    </td>
                  </tr>
                </table>
                <!-- -->
                </td>
              </tr>
              <tr>
                <td class="b_nml">
                <!-- -->
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="20">&nbsp;</td>
                    <td width="20">&nbsp;</td>
                    <td align="center" valign="middle">
                    	<input type="button" value="Вперёд!!!" />
                    </td>
                    <td width="20">&nbsp;</td>
                    <td width="20"><img onclick="fight.f5()" title="Обновить" src="http://img.xcombats.com/i/ico_refresh.gif" width="16" height="19" /></td>
                  </tr>
                  </table>
                <!-- -->
                </td>
              </tr>
              </table>
        </div>
        <!-- -->
        </td>
        <td width="250" align="right" valign="top"><div id="u2"></div></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td align="center">
    	<div id="u_teams"></div>
    </td>
  </tr>
  <tr>
    <td>
    	<div style="float:left">На данный момент вами нанесено урона: <b><span id="u_atackhp">0</span> HP</b>.</div>
        <div style="float:right">(Бой идет с таймаутом <b id="u_timeout">1</b> мин.)</div>
    </td>
  </tr>
</table>
<script>fight.start();</script>