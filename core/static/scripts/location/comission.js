// JavaScript Document
var locjs = {
	
	data:{ },
	
	type:0, //0 - �������� �������� , 1 - �������� �������� � ����������� �������� , 2 - �������� ����� ����� (�����) , 3 - �������� ����� ����� (�������)
	
	start:function() {
		
		//this.htmlTitle();
		//this.htmlMenu();
		//this.htmlContent();
		
		this.selectPoint(0,false);
		//this.error('������ ����������� � ����.');
	},
	
	reflech:function() {
		if( this.lookIt == 0 ) {
			this.selectPoint( this.selectPointLast , false );
		}else{
			this.getBaseContent();
		}
	},
	
	refleshData:function( data ) {
		if( data.money != undefined ) {
			this.data.user.money = data.money;
			this.data.user.massaNow = data.massaNow;
			this.data.user.massaMax = data.massaMax;
			//
			this.data.user.timeGo = data.timeGo;
			this.data.user.timeGoL = data.timeGoL;
			this.data.user.timeNow = data.timeNow;
			this.htmlMenu();
		}
		locline.lineRefleshFinish();
	},
	
	htmlMenu:function() {
		var r = '';		
		r += '<div align="right">';
		//
		r += '<div>' + locline.line( this.data.user.timeGo, this.data.user.timeGoL , this.data.user.timeNow , 'locjs.reflech();' ) +
		  '' + locline.room( this.data.locations ) + ''
		  +  '</div>';
		//
		r += '<small>';
		r += '<span style="display:none">�����: <span id="u_massa">' + this.data.user.massaNow + '/' + this.data.user.massaMax + '</span><br></span>';
		r += '� ��� � �������: <b style="color:#339900;" id="u_money">' + this.data.user.money + ' ��.</b><br><br>';
		r += '</small></div>';
			
		r += '<center>';
		
		if( this.data.user.level >= 4 ) {
			r += '<input id="comission_give" onclick="locjs.selectPoint(\'give\',true)" style="width:40%" class="btnnew';
			if( this.selectPointLast == 'give' ) {
				r += '2';
			}
			r += '" type="button" value="����� ����" />';
			r += '<input id="comission_pick" onclick="locjs.selectPoint(\'pick\',true)" style="width:40%" class="btnnew';
			if( this.selectPointLast == 'pick' ) {
				r += '2';
			}
			r += '" type="button" value="������� ����" />';
		}
		
		r += '<div style="background-color:#a5a5a5;padding:2px;margin-top:4px;" align="center"><b>������ ��������</b></div>';		
		r += '</center>';
		
		//���������� ����
		for (var i = 0; i < this.data.menu.length; i++) {
			r += '<div id="comission_pid' + i + '" title="������ �' + i + '" onclick="locjs.selectPoint(' + i + ',true)" class="comission_menu_point';
			if( this.selectPointLast == i ) {
				r += ' comission_unhide';
			}
			r += '">';
			if( this.data.menu[i][2] == 1 ) {
				r += '&nbsp; &nbsp; &nbsp;';
			}
			r += '<a>' + this.data.menu[i][0] + '</a>';
			r += '</div>';
		}
				
		r += '<div><small><br><font color="red"><b>��������!</b> ������� ����������� ������������ ���������:</font><br>';
		r += '&bull; � ��������� ��������� ����� � ���� <b>1 ��.</b> ��� �������� ���� � �������.';
		r += '<br>&bull; ����� �� ������������, ���� ���� �� ����� ������� � ������� ���� ������ (���� ���� ��������� � ��������������� ������� �� 50% � ������ ������ ��������).';
		r += '<br>&bull; ���� ���� ���������, ��������� ������������� ������ �� ����� �� ������� ������������ 10%. ����� � 1 ��. ������������.';
		r += '<br>&bull; ������������� �� ����� ��������������� �� ������ ����� � ������ ���� �������� ��������.';
		r += '<br>&bull; ���� �� ����� ��� ��� �������, �������� �� ���� ����� <a href="http://xcombats.com/forum/index.php?read=477" target="_blank">���� � ������������ ��������</a>.';
		r += '</small></div><br><br>';

		return $('#comission_menu').html( r ) + locline.lineTimer()
		//document.getElementById('comission_menu').innerHTML = r;
		//return 'true';
	},
		
	selectPointLast:0,
	selectPoint:function( p , clearError ) {
		if( clearError == true ) {
			this.error('');
		}
		this.baseconnect = true;
		this.lookIt = 0;
		this.type = 0;
		$('#comission_pid' + this.selectPointLast + '').removeClass('comission_unhide');
		this.selectPointLast = p;
		//�������� ������ ���������
		//$('#comission_pick').removeClass('btnnew');
		//$('#comission_give').removeClass('btnnew');
		//$('#comission_pick').removeClass('btnnew2');
		//$('#comission_give').removeClass('btnnew2');
		if( p == 'give' ) {
			//����� ����
			this.data.base = this.getBaseContent();
			//$('#comission_pick').addClass('btnnew');
			//$('#comission_give').addClass('btnnew2');
			this.htmlTitle();
			this.htmlContent();
		}else if( p == 'pick' ) {
			//��������
			this.data.base = this.getBaseContent();
			//$('#comission_give').addClass('btnnew');
			//$('#comission_pick').addClass('btnnew2');
			this.htmlTitle();
			this.htmlContent();
		}else{
			//
			this.data.base = this.getBaseContent();
			//$('#comission_give').addClass('btnnew');
			//$('#comission_pick').addClass('btnnew');
			//
			this.htmlTitle();
			//this.htmlContent();
			//this.htmlMenu();
			//
			$('#comission_pid' + this.selectPointLast + '').addClass('comission_unhide');
			$("body").animate({"scrollTop":0},0);
		}
	},
	
	htmlTitle:function() {
		var r = '';
		if( this.selectPointLast == 'give' ) {
			r += '����� ����� � ������������ �������';
		}else if( this.selectPointLast == 'pick' ) {
			r += '������� ���� ���� �� ������������� ��������';
		}else{
			r += this.data.menu[this.selectPointLast][0];
			if( r == '' ) {
				r += '<i>����� �������� ������</i>';
			}else{
				r = r.charAt(0).toUpperCase() + r.substr(1);
				r = '������: &quot;' + r + '&quot;';
			}
		}
		return $('#comission_title').html( r );
	},
	
	baseconnect:false,
	getBaseContent:function() {
		//if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			$.getJSON('/core/comission/', {
				'point':this.selectPointLast, 'type':this.type, 'lookIt':this.lookIt
			},function(data) {
				locjs.refleshData( data );
				locjs.baseconnect = false;
				locjs.data.base = data;
				locjs.htmlContent();
			});
		//}
	},
	
	lookIt:0,
	lookItems:function( p ) {
		this.lookIt = p;
		this.type = 1;
		this.getBaseContent();
	},
	
	buy_error:[ '','������� �� ������, �������� ��� ���-�� ��� �����','� ��� ������������ �����','��������� ������ ������������ ������������ ���������','���������� ���� 1-�� ������ ��������� ������������ ������������ ���������' ],
	buy:function( cid , item_id ) {
		if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			this.baseconnect = true;
			$.getJSON('/core/comission/', {
				'buy_item':cid, 'items_id':item_id
			},function(data) {
				locjs.refleshData( data );
				locjs.baseconnect = false;
				if( data.buy_item != undefined && (data.buy_error == undefined || data.buy_error == 0) ) {
					$("body").animate({"scrollTop":0},0);
					$('#itm_com_' + data.buy_item).remove();
					locjs.data.user.money -= parseFloat(data.buy_price).toFixed(2);
					$('#u_money').html( locjs.data.user.money.toFixed(2) + ' ��.' );
					locjs.data.user.massaNow += parseFloat(data.buy_massa).toFixed(2);
					$('#u_massa').html( locjs.data.user.massaNow + '/' + locjs.data.user.massaMax );
					locjs.error( '�� ������� ������ ������� &quot;' + data.buy_name + '&quot; �� ' + data.buy_price + ' ��.' );
					if( data.buy_last < 1 ) {
						locjs.selectPoint( locjs.selectPointLast , false );
					}
				}else if( locjs.buy_error[data.buy_error] != undefined ) {
					$("body").animate({"scrollTop":0},0);
					locjs.error( '' + locjs.buy_error[data.buy_error] );
				}
			});
		}
	},
	
	newprice_error:[
		'','������� �� ������ � ��������','���� �� ����� ���� ���� 1 ��.','��� ������� �������� ��� ������� ������� ���������...','��� ������� �������� ��� ������� ��������� ���������, �������� ��������������� ������� � ����� ��� � ��������������� �������...'
	],
	newprice:function( id , price ) {
		if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			this.baseconnect = true;
			$.getJSON('/core/comission/', {
				'newprice_item':id , 'newprice_price':price
			},function(data) {
				locjs.refleshData( data );
				locjs.baseconnect = false;
				if( data.newprice_item != undefined && (data.newprice_error == undefined || data.newprice_error == 0) ) {
					$("body").animate({"scrollTop":0},0);
					locjs.data.user.money -= parseFloat(data.newprice_price_comission).toFixed(2);
					$('#u_money').html( locjs.data.user.money.toFixed(2) + ' ��.' );
					locjs.error( '�� �������� ���� &quot;' + data.newprice_name + '&quot; x' + data.newprice_x + ' ��. �� ' + data.newprice_price + ' ��. �� ' + data.newprice_price_comission + ' ��.' );
				}else if( locjs.newprice_error[data.newprice_error] != undefined ) {
					$("body").animate({"scrollTop":0},0);
					if( data.newprice_error_min != undefined && data.newprice_error_min != '' ) {
						locjs.error( '' + locjs.newprice_error[data.newprice_error] + ' (����������� ����: ' + data.newprice_error_min + ' ��.)' );
					}else{
						locjs.error( '' + locjs.newprice_error[data.newprice_error] );
					}
				}
			});
		}
	},
	
	give_error:['','������� �� ������ � ���������','������������ ����� ��� ������ ��������','��������� ������ ������������ ������������ ���������','���������� ���� 4-�� ������ ��������� ���������� ���� � ������������ ��������',
	'���� �� ����� ���� ������ 1 000 000 ��.','���� �� ����� ���� ���� 1 ��.','��� ������� �������� ��� ������� ������� ���������...','��� ������� �������� ��� ������� ��������� ���������, �������� ��������������� ������� � ����� ��� � ��������������� �������...'
	,'������������ ������� �� ������ ���� ����'
	],
	give:function( id , price ) {
		if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			this.baseconnect = true;
			$.getJSON('/core/comission/', {
				'give_item':id , 'give_price':price
			},function(data) {
				locjs.refleshData( data );
				locjs.baseconnect = false;
				if( data.give_item != undefined && (data.give_error == undefined || data.give_error == 0) ) {
					$("body").animate({"scrollTop":0},0);
					$('#itm_usr_' + data.give_item).remove();
					locjs.data.user.money -= parseFloat(data.give_price_comission).toFixed(2);
					$('#u_money').html( locjs.data.user.money.toFixed(2) + ' ��.' );
					locjs.data.user.massaNow -= parseFloat(data.give_massa).toFixed(2);
					$('#u_massa').html( locjs.data.user.massaNow + '/' + locjs.data.user.massaMax );
					locjs.error( '�� ����� � ������� &quot;' + data.give_name + '&quot; x' + data.give_x + ' ��. �� ����� ' + data.give_price + ' ��. �� ' + data.give_price_comission + ' ��.' );
					if( data.give_last < 1 ) {
						alert('�������� � ��������� ����������� :)');
					}
				}else if( locjs.give_error[data.give_error] != undefined ) {
					$("body").animate({"scrollTop":0},0);
					if( data.give_error_min != undefined && data.give_error_min != '' ) {
						locjs.error( '' + locjs.give_error[data.give_error] + ' (����������� ����: ' + data.give_error_min + ' ��.)' );
					}else{
						locjs.error( '' + locjs.give_error[data.give_error] );
					}
				}
			});
		}
	},
	pick_error:[ '',
	'������� �� ������ � ��������'
	],
	pick:function( id ) {
		if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			this.baseconnect = true;
			$.getJSON('/core/comission/', {
				'pick_item':id
			},function(data) {
				locjs.refleshData( data );
				locjs.baseconnect = false;
				if( data.pick_item != undefined && (data.pick_error == undefined || data.pick_error == 0) ) {
					$("body").animate({"scrollTop":0},0);
					$('#itm_usr_' + data.pick_uiid).remove();
					locjs.data.user.massaNow += parseFloat(data.pick_massa).toFixed(2);
					$('#u_massa').html( locjs.data.user.massaNow + '/' + locjs.data.user.massaMax );
					locjs.error( '�� ������� �� �������� &quot;' + data.pick_name + '&quot; x' + data.pick_x + ' ��.' );
					if( data.pick_last < 1 ) {
						alert('�������� � �������� ����������� :)');
					}
				}else if( locjs.pick_error[data.pick_error] != undefined ) {
					$("body").animate({"scrollTop":0},0);
					locjs.error( '' + locjs.pick_error[data.pick_error] );
				}
			});
		}
	},
	
	
	console_sale:function(name, txt, kr) {
		var s = prompt("����� � ������� \""+txt+"\" (����� 1.00 ��.). ������� ����:", kr);
		if ((s != null) && (s != '') && (s >= 0)) {
			locjs.give( name , s );
		}
	},
	
	console_change:function(name, txt, id, category, kr) {
		var s = prompt("������� ���� ��� �������� \""+txt+"\". ������� ����� ����:", kr);
		if ((s != null) && (s != '') && (s>=1)) {
			locjs.newprice( name , s );
		}
	},
	
	htmlContent:function() {
		var r = '';
		
		if( this.data.base != undefined ) {
			//
			//this.error( '<hr>type: ' + this.type + ' , option: ' + this.selectPointLast + '<hr>' );
			//
			if( this.selectPointLast == 'give' ) {
					
				var i = 0, color = 'c8c8c8', left = '', right = '';
				r += '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
				while( i < this.data.base.it.i ) {
					left = ''; right = ''; if( color == 'c8c8c8' ) { color = 'd4d4d4'; }else{ color = 'c8c8c8'; }
					left += '<img src="http://img.xcombats.com/i/items/' + this.data.base.it[i][2] + '"><br>';
					if( this.data.base.it[i][22] > 0 ) {
						left += '<small>(id' + this.data.base.it[i][22] + ')</small><br><br>';
					}
					//
					if( this.data.base.it[i][6] != this.data.base.it[i][12] && this.data.base.it[i][12] > 1 ) {
						this.data.base.it[i][6] = this.data.base.it[i][12];
					}
					if( this.data.base.it[i][21] > 0 && this.data.base.it['g'][this.data.base.it[i]['iid']][this.data.base.it[i][21]] > 0 ) {
						this.data.base.it[i][6] = this.data.base.it[i][6] * parseFloat(this.data.base.it['g'][this.data.base.it[i]['iid']][this.data.base.it[i][21]]).toFixed(2);
						this.data.base.it[i][6] = this.data.base.it[i][6].toFixed(2);
					}
					//
					left += '<a href="javascript:void(0)" onclick="locjs.console_sale(' + this.data.base.it[i][22] + ',\'' + this.data.base.it[i][1] + '\',\'' + this.data.base.it[i][6] + '\');">����� � �������</a>';
					//
					right += itmjs.infoItem( this.data.base.it , i , 'comission_give' );
					//
					r += '<tr bgcolor="#' + color + '" id="itm_usr_'+this.data.base.it[i][22]+'"><td width="160" align="center" class="borderitem px3px brd1top" valign="middle">' + left + '</td>';
					r += '<td valign="top" class="px3px brd1top">' + right + '</td></tr>';
					i++;
				}
				r += '</table>';
					
			}else if( this.selectPointLast == 'pick' ) {

				var i = 0, color = 'c8c8c8', left = '', right = '';
				r += '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
				while( i < this.data.base.it.i ) {
					left = ''; right = ''; if( color == 'c8c8c8' ) { color = 'd4d4d4'; }else{ color = 'c8c8c8'; }
					left += '<img src="http://img.xcombats.com/i/items/' + this.data.base.it[i][2] + '"><br>';
					if( this.data.base.it[i][22] > 0 ) {
						left += '<small>(id' + this.data.base.it[i][22] + ')</small><br><br>';
					}
					//
					left += '<a href="javascript:void(0)" onclick="locjs.pick( ' + this.data.base.it[i]['cid'] + ');">������� �������</a><br>';
					left += '<a href="javascript:void(0)" onclick="locjs.console_change(' + this.data.base.it[i][22] + ',\'' + this.data.base.it[i][1] + '\',\'' + this.data.base.it[i][6] + '\');">������� ���� �� 0.10 ��.</a>';
					left += '<small><br><br>�� ' + this.data.base.it[i]['timeend'] + '</small>';
					//
					right += itmjs.infoItem( this.data.base.it , i , 'comission_pick' );
					//
					r += '<tr bgcolor="#' + color + '" id="itm_usr_'+this.data.base.it[i][22]+'"><td width="160" align="center" class="borderitem px3px brd1top" valign="middle">' + left + '</td>';
					r += '<td valign="top" class="px3px brd1top">' + right + '</td></tr>';
					i++;
				}
				r += '</table>';
				
			}else{
				
				if( this.data.base.type == 1 ) {
					
					var i = 0, color = 'c8c8c8', left = '', right = '';
					r += '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
					while( i < this.data.base.it.i ) {
						left = ''; right = ''; if( color == 'c8c8c8' ) { color = 'd4d4d4'; }else{ color = 'c8c8c8'; }
						left += '<img src="http://img.xcombats.com/i/items/' + this.data.base.it[i][2] + '"><br>';
						left += '<a href="javascript:void(0)" onclick="locjs.buy( ' + this.data.base.it[i]['cid'] + ' , ' + this.data.base.it[i]['iid'] + ' );">������</a>';
						//
						right += itmjs.infoItem( this.data.base.it , i , 'comission' );
						//
						r += '<tr bgcolor="#' + color + '" id="itm_com_'+this.data.base.it[i]['cid']+'"><td width="160" align="center" class="borderitem px3px brd1top" valign="middle">' + left + '</td>';
						r += '<td valign="top" class="px3px brd1top">' + right + '</td></tr>';
						i++;
					}
					r += '</table>';
					
				}else if( this.data.base.type == 0 ) {
					
					var i = 0, color = 'c8c8c8', left = '', right = '';
					r += '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
					while( i < this.data.base.it.i ) {
						left = ''; right = ''; if( color == 'c8c8c8' ) { color = 'd4d4d4'; }else{ color = 'c8c8c8'; }
						left += '<img src="http://img.xcombats.com/i/items/' + this.data.base.it[i][5] + '"><br><a href="javascript:void(0);" onclick="locjs.lookItems(' + this.data.base.it[i][1] + ');">���������</a>';
						//
						right += '<a href="http://xcombats.com/item/' + this.data.base.it[i][1] + '" target="_blank">' + this.data.base.it[i][4] + '</a>';
						right += ' &nbsp;&nbsp; (�����: ' + this.data.base.it[i][6] + ')<br>';
						right += '<b>����: ' + this.data.base.it[i][7][0] + ' - ' + this.data.base.it[i][7][1] + ' ��.</b>';
						right += ' &nbsp; <small>(����������: ' + this.data.base.it[i][8] + ')</small><br>';
						right += '�������������: ' + this.data.base.it[i][2][0] + '-' + this.data.base.it[i][3][0] + ' / ' + this.data.base.it[i][2][1] + '-' + this.data.base.it[i][3][1] + '';
						right += '<br><b>��������� �����������:</b><br>&bull; �������: ' + this.data.base.it[i][9] + '';
						//
						r += '<tr bgcolor="#' + color + '"><td width="160" align="center" class="borderitem px3px brd1top" valign="middle">' + left + '</td>';
						r += '<td valign="top" class="px3px brd1top">' + right + '</td></tr>';
						i++;
					}
					r += '</table>';
				}
			}
			//
		}else{
			r += '<center class="borderandpadding5px"><i>�������� ������ �����</i></center>';
		}

		
		
		if( this.selectPointLast == 'give' ) {
			if( this.data.user.level < 4 ) {
				r += '<center class="borderandpadding5px">����� ����� � ������������ ������� ��������� � 4-�� ������</center>';
			}else if( r == '' ) {
				r += '<center class="borderandpadding5px">� ��� ��� ��������� ������� ����� ����� � ������������ �������</center>';
			}else{
				
			}
		}else if( this.selectPointLast == 'pick' ) {
			if( r == '' ) {
				r += '<center class="borderandpadding5px">� ��� ��� ��������� ������� � ������������ �������</center>';
			}else{
				
			}
		}else{			
			if( this.data.base == undefined || this.data.base.it.i == 0 ) {
				r = '';
			}
			if( r == '' && this.baseconnect == false ) {
				r += '<center class="borderandpadding5px">�������� �������� ����</center>';
			}else{
				
			}
		}
		if( r != '' ) {
			$('#comission_content').html( r );
		}
	},
	
	error:function( text ) {
		$('#error').html( text );
		if( text == '' ) {
			$('#error').hide();
		}else{
			$('#error').show();
		}
	}
};