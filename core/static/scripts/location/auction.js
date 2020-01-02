// JavaScript Document
var locjs = {
	
	data:{ },
	
	type:0, //0 - просмотр разделов , 1 - просмотр разделов и конкретного предмета , 2 - просмотр своих вещей (сдача) , 3 - просмотр своих вещей (забрать)
	
	baseconnect:false,
	
	start:function() {
		this.getMenuHtml();
		this.getTopMenu();
		this.getDataItems(1);
		//
		locline.lineRefleshFinish();
	},
	
	reflesh:function() {
		locline.lineRefleshFinish();
	},
	
	option_error:{
		
	},
	
	page_item:1,
	option_item:1,
	getDataItems:function( id ) {
		this.option_item = id;
		if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			this.baseconnect = true;
			$.getJSON('/core/auction/', {
				'option_item':id,
				'page':locjs.page_item,
			},function(data) {
				locjs.baseconnect = false;
				//
				locjs.data.user.massaNow = parseFloat(data.massaNow).toFixed(2);
				locjs.data.user.massaMax = parseFloat(data.massaMax).toFixed(2);
				locjs.data.user.money = parseFloat(data.money).toFixed(2);
				//
				if( data.toppages != undefined && data.toppages > 0 ) {
					var tphtml = '';
					var i = 1;
					while( i <= data.toppages ) {
						if( data.page == i ) {
							tphtml = tphtml + '<a href="javascript:void(0);" onclick="locjs.page_item='+i+';locjs.getDataItems(locjs.option_item);" style="text-decoration: underline;">' + i + '</a> ';
						}else{
							tphtml = tphtml + '<a href="javascript:void(0);" onclick="locjs.page_item='+i+';locjs.getDataItems(locjs.option_item);">' + i + '</a> ';
						}
						i++;
					}
					$('#toppages_line').show();
					$('#toppages').html( tphtml );
				}else{
					$('#toppages_line').hide();
					$('#toppages').html( '--' );
				}
				//
				if( data.item_data != undefined && data.item_data != 0 ) {
					locjs.getItemsAuction( id , data.item_data );
				}else{
					locjs.getItemsAuction( id , {'x':0} );
				}
				//
				$('#u_money').html( locjs.data.user.money + ' кр.' );
				$('#u_massa').html( locjs.data.user.massaNow + '/' + locjs.data.user.massaMax );
				//
				locline.lineRefleshFinish();
				//
			});
		}
	},
	
	console_sale:function(id, txt, kr) {
		var s = prompt("Сделать ставку на \""+txt+"\". Укажите цену:", kr);
		if ((s != null) && (s != '') && (s >= 0)) {
			locjs.user_buy( id, s );
		}
	},
	
	buyItemsAuctionError:{
		0:'',
		1:'Предмет не найден, возможно торги уже закончились',
		2:'Вы уже сделали ставку на этот предмет, ожидайте пока её перебьет кто-то другой',
		3:'Минимальная ставка 101% от текущей ставки',
		4:'Максимальная ставка 200% от текущей ставки',
		5:'У вас недостаточно денег для ставки',
		6:'Вы не можете участвовать в ставках на свои предметы'
	},
	user_buy:function( id, kr ) {
		//
		//this.option_item = id;
		if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			this.baseconnect = true;
			$.getJSON('/core/auction/', {
				'buy_item':id,
				'kr':kr,
				'page':locjs.page_item,
			},function(data) {
				locjs.baseconnect = false;
				//
				if( data.error != undefined ) {
					if( locjs.buyItemsAuctionError[ data.error ] != undefined ) {
						$('#error').html( locjs.buyItemsAuctionError[ data.error ] );
					}else{
						$('#error').html( 'Неизвестная ошибка. Код #'+data.error+'' );
					}
					$('#error').show();
					$("body").animate({"scrollTop":0},0);
				}else{
					if( data.info != undefined ) {
						alert('Вы успешно сделали ставку!');
					}
					$('#error').html('');
					$('#error').hide();
				}
				//
				locline.lineRefleshFinish();
				//
			});
		}
		//
	},
	
	getItemsAuction:function( id , data ) {
		var r = '';
		if( id == 5 ) {
			r += '<table width="100%" border="0" style="border:1px solid #a5a5a5;margin-top:5px;" cellspacing="0" cellpadding="0"><tr>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Предмет</td>'+
					'<td width="144" align="center" valign="middle" bgcolor="#a5a5a5">&nbsp;</td>'+
					'<td width="50%" align="center" valign="middle" bgcolor="#a5a5a5">&nbsp;</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Уровень</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Владелец</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Ставка</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Покупатель</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Осталось времени</td>'+
				  '</tr>';
				  //предмет
			if( data.x != undefined && data.x > 0 ) {
				var i = 0;
				while( i < data.x ) {
					if( data[i] != undefined ) {
						var itm_data = itmjs.lookStats( data[i][20] );
						if( itm_data['tr_lvl'] == undefined ) {
							itm_data['tr_lvl'] = 0;
						}
						if( data[i].buy == '' ) {
							data[i].buy = 'Нет покупателя';
						}
						if( data[i].user_ow == '' ) {
							data[i].user_ow = '<i>Неизвестно</i>';
						}
						if( data[i].time == '0 сек.' ) {
							data[i].time = 'Торг закрыт.';
						}
						r += '<tr id="itm_auction' + data[i][22] + '">'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><small>(id' + data[i][22] + ')</small></td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><button onclick="locjs.console_sale('+data[i].aid+',\''+data[i].name+'\',\'' + (data[i].prc2) + '\');" class="btnnew">Сделать ставку</button></td>'+
							//'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><img src="http://img.xcombats.com/auc_watch_off.png" title="Отслеживание (будет отображаться в разделе [Ставки])" class="cp"> <img src="http://img.xcombats.com/auc_bet1.png" title="Сделать ставку" class="cp"></td>'+
							'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle"><a onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" href="http://xcombats.com/item/' + data[i].item_id + '" target="_blank">' + data[i].name + '</a> &nbsp; (Масса: ' + data[i].massa + ')<br>Долговечность: ' + Math.ceil(data[i].iznosNOW) + '/' + Math.ceil(data[i].iznosMAX) + '</td>'+
							//'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle">--</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + itm_data['tr_lvl'] + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].user_ow + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].prc + ' кр.</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].buy + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].time + '</td>'+
						  '</tr>';
						  //
					}
					i++;
				}
			}
			r += '</table>';
			if( data.x == undefined || data.x < 1 ) {
				r += '<div style="border:1px solid #a5a5a5; padding:10px;" align="center">Ничего не найдено</div>';
			}
		}else if( id == 1 ) {
			r += '<table width="100%" border="0" style="border:1px solid #a5a5a5;margin-top:5px;" cellspacing="0" cellpadding="0"><tr>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Предмет</td>'+
					'<td width="144" align="center" valign="middle" bgcolor="#a5a5a5">&nbsp;</td>'+
					'<td width="50%" align="center" valign="middle" bgcolor="#a5a5a5">&nbsp;</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Уровень</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Владелец</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Ставка</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Покупатель</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Осталось времени</td>'+
				  '</tr>';
				  //предмет
			if( data.x != undefined && data.x > 0 ) {
				var i = 0;
				while( i < data.x ) {
					if( data[i] != undefined ) {
						var itm_data = itmjs.lookStats( data[i][20] );
						if( itm_data['tr_lvl'] == undefined ) {
							itm_data['tr_lvl'] = 0;
						}
						if( data[i].buy == '' ) {
							data[i].buy = 'Нет покупателя';
						}
						if( data[i].user_ow == '' ) {
							data[i].user_ow = '<i>Неизвестно</i>';
						}
						if( data[i].time == '0 сек.' ) {
							data[i].time = 'Торг закрыт.';
						}
						r += '<tr id="itm_auction' + data[i][22] + '">'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><small>(id' + data[i][22] + ')</small></td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><button onclick="locjs.console_sale('+data[i].aid+',\''+data[i].name+'\',\'' + (data[i].prc2) + '\');" class="btnnew">Сделать ставку</button></td>'+
							//'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><img src="http://img.xcombats.com/auc_watch_off.png" title="Отслеживание (будет отображаться в разделе [Ставки])" class="cp"> <img src="http://img.xcombats.com/auc_bet1.png" title="Сделать ставку" class="cp"></td>'+
							'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle"><a onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" href="http://xcombats.com/item/' + data[i].item_id + '" target="_blank">' + data[i].name + '</a> &nbsp; (Масса: ' + data[i].massa + ')<br>Долговечность: ' + Math.ceil(data[i].iznosNOW) + '/' + Math.ceil(data[i].iznosMAX) + '</td>'+
							//'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle">--</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + itm_data['tr_lvl'] + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].user_ow + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].prc + ' кр.</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].buy + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].time + '</td>'+
						  '</tr>';
						  //
					}
					i++;
				}
			}
			r += '</table>';
			if( data.x == undefined || data.x < 1 ) {
				r += '<div style="border:1px solid #a5a5a5; padding:10px;" align="center">Ничего не найдено</div>';
			}
		}else if( id == 2 ) {
			r += '<table width="100%" border="0" style="border:1px solid #a5a5a5;margin-top:5px;" cellspacing="0" cellpadding="0"><tr>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Предмет</td>'+
					'<td width="50%" align="center" valign="middle" bgcolor="#a5a5a5">&nbsp;</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Уровень</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Владелец</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Ставка</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Покупатель</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Осталось времени</td>'+
				  '</tr>';
				  //предмет
			if( data.x != undefined && data.x > 0 ) {
				var i = 0;
				while( i < data.x ) {
					if( data[i] != undefined ) {
						var itm_data = itmjs.lookStats( data[i][20] );
						if( itm_data['tr_lvl'] == undefined ) {
							itm_data['tr_lvl'] = 0;
						}
						if( data[i].buy == '' ) {
							data[i].buy = 'Нет покупателя';
						}
						if( data[i].user_ow == '' ) {
							data[i].user_ow = '<i>Неизвестно</i>';
						}
						if( data[i].time == '0 сек.' ) {
							data[i].time = 'Торг закрыт.';
						}
						r += '<tr id="itm_auction' + data[i][22] + '">'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><small>(id' + data[i][22] + ')</small></td>'+
							//'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><img src="http://img.xcombats.com/auc_watch_off.png" title="Отслеживание (будет отображаться в разделе [Ставки])" class="cp"> <img src="http://img.xcombats.com/auc_bet1.png" title="Сделать ставку" class="cp"></td>'+
							'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle"><a onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" href="http://xcombats.com/item/' + data[i].item_id + '" target="_blank">' + data[i].name + '</a> &nbsp; (Масса: ' + data[i].massa + ')<br>Долговечность: ' + Math.ceil(data[i].iznosNOW) + '/' + Math.ceil(data[i].iznosMAX) + '</td>'+
							//'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle">--</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + itm_data['tr_lvl'] + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].user_ow + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].prc + ' кр.</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].buy + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].time + '</td>'+
						  '</tr>';
						  //
					}
					i++;
				}
			}
			r += '</table>';
			if( data.x == undefined || data.x < 1 ) {
				r += '<div style="border:1px solid #a5a5a5; padding:10px;" align="center">Ничего не найдено</div>';
			}
		}else if( id == 3 ) {
			r += '<table width="100%" border="0" style="border:1px solid #a5a5a5;margin-top:5px;" cellspacing="0" cellpadding="0"><tr>'+
					'<td width="120" align="center" valign="middle" bgcolor="#a5a5a5">Предмет</td>'+
					'<td width="50%" align="center" valign="middle" bgcolor="#a5a5a5">&nbsp;</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Уровень</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Ставка</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Покупатель</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Осталось времени</td>'+
				  '</tr>';
				  //предмет
			if( data.x != undefined && data.x > 0 ) {
				var i = 0;
				while( i < data.x ) {
					if( data[i] != undefined ) {
						var itm_data = itmjs.lookStats( data[i][20] );
						if( itm_data['tr_lvl'] == undefined ) {
							itm_data['tr_lvl'] = 0;
						}
						if( data[i].buy == '' ) {
							data[i].buy = 'Нет покупателя';
						}
						if( data[i].user_ow == '' ) {
							data[i].user_ow = '<i>Неизвестно</i>';
						}
						if( data[i].time == '0 сек.' ) {
							data[i].time = 'Торг закрыт.';
						}
						r += '<tr id="itm_auction' + data[i][22] + '">'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><small>(id' + data[i][22] + ')</small></td>'+
							//'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><img src="http://img.xcombats.com/auc_watch_off.png" title="Отслеживание (будет отображаться в разделе [Ставки])" class="cp"> <img src="http://img.xcombats.com/auc_bet1.png" title="Сделать ставку" class="cp"></td>'+
							'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle"><a onMouseOver="top.hi(this,\'' + this.replaceslashhint(itmjs.infoItem( data , i , 'auction_info' )) + '\',event,0,0,1,1,\'max-width:307px\')" onMouseOut="top.hic();" onMouseDown="top.hic();" href="http://xcombats.com/item/' + data[i].item_id + '" target="_blank">' + data[i].name + '</a> &nbsp; (Масса: ' + data[i].massa + ')<br>Долговечность: ' + Math.ceil(data[i].iznosNOW) + '/' + Math.ceil(data[i].iznosMAX) + '</td>'+
							//'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle">--</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + itm_data['tr_lvl'] + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].prc + ' кр.</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].buy + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + data[i].time + '</td>'+
						  '</tr>';
						  //
					}
					i++;
				}
			}
			r += '</table>';
			if( data.x == undefined || data.x < 1 ) {
				r += '<div style="border:1px solid #a5a5a5; padding:10px;" align="center">Ничего не найдено</div>';
			}
		}else if( id == 4 ) {
			if( locjs.data.user.silver >= 4 ) {
				prcauc = 2.5;
			}else{
				prcauc = 10;
			}
			r += '<table width="100%" border="0" style="border:1px solid #a5a5a5;margin-top:5px;" cellspacing="0" cellpadding="0"><tr>'+
					'<td width="120" align="center" valign="middle" bgcolor="#a5a5a5">Предмет</td>'+
					'<td width="50%" align="center" valign="middle" bgcolor="#a5a5a5">&nbsp;</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Уровень</td>'+
					'<td align="center" valign="middle" bgcolor="#a5a5a5">Начальная ставка<br><small>(Выставляется на 24 часа. +' + prcauc + '% комиссия)</small></td>'+
				  '</tr>';
				  //предмет
			if( data.x != undefined && data.x > 0 ) {
				var i = 0;
				while( i < data.x ) {
					if( data[i] != undefined ) {
						var itm_data = itmjs.lookStats( data[i][20] );
						if( itm_data['tr_lvl'] == undefined ) {
							itm_data['tr_lvl'] = 0;
						}
						r += '<tr id="itm_auction' + data[i][22] + '">'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><small>(id' + data[i][22] + ')</small></td>'+
							//'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><img style="padding:10px;" src="http://img.xcombats.com/i/items/' + data[i][2] + '"><br><img src="http://img.xcombats.com/auc_watch_off.png" title="Отслеживание (будет отображаться в разделе [Ставки])" class="cp"> <img src="http://img.xcombats.com/auc_bet1.png" title="Сделать ставку" class="cp"></td>'+
							//'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle"><a href="http://xcombats.com/item/' + data[i].item_id + '" target="_blank">' + data[i].name + '</a> &nbsp; (Масса: ' + data[i].massa + ')<br>Долговечность: ' + Math.ceil(data[i].iznosNOW) + '/' + Math.ceil(data[i].iznosMAX) + '</td>'+
							'<td align="left" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;padding-left:10px;" valign="middle">' + itmjs.infoItem( data , i , 'auction_inventory' ) + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle">' + itm_data['tr_lvl'] + '</td>'+
							'<td align="center" style="border-top:1px solid #a5a5a5;border-right:1px solid #a5a5a5;" valign="middle"><input style="padding:5px;width:44px;text-align:center;" type="text" id="itm_add'+data[i][22]+'_price" value="0.00" /> кр. <input onclick="locjs.addItemsAuction(' + data[i][22] + ')" type="button" value="Выставить" class="btnnew" /></td>'+
						  '</tr>';
						  //
					}
					i++;
				}
			}
			r += '</table>';
			if( data.x == undefined || data.x < 1 ) {
				r += '<div style="border:1px solid #a5a5a5; padding:10px;" align="center">Ничего не найдено</div>';
			}
		}
		
		$('#auction_content').html( r );
	},
	
	addItemsAuctionError:{
		0:'Все прошло успешно',
		1:'Подходящий предмет не найден у вас в инвентаре',
		2:'Нельзя продать предмет дешевле 1 кр.',
		3:'Нельзя продать предмет дороже 1000000 кр.',
		4:'У вас недостаточно кр. для выставления предмета. Требуется 10% от вашей ставки.'
	},
	addItemsAuction:function(id) {
		//
		this.option_item = id;
		if( this.baseconnect == false ) {
			locline.lineRefleshStart();
			this.baseconnect = true;
			$.getJSON('/core/auction/', {
				'add_item':id,
				'price':$('#itm_add' + id + '_price').val(),
				'page':locjs.page_item,
			},function(data) {
				locjs.baseconnect = false;
				//
				if( data.error != undefined ) {
					if( locjs.addItemsAuctionError[ data.error ] != undefined ) {
						$('#error').html( locjs.addItemsAuctionError[ data.error ] );
					}else{
						$('#error').html( 'Неизвестная ошибка. Код #'+data.error+'' );
					}
					$('#error').show();
					$("body").animate({"scrollTop":0},0);
				}else{
					if( data.info != undefined ) {
						if(data.info.x > 0) {
							data.info.itm_name = data.info.itm_name + ' (x' + (1+data.info.x) + ')';
						}
						//$("body").animate({"scrollTop":0},0);
						//$('#error').html( 'Вы успешно выставили предмет &quot;' + data.info.itm_name + '&quot; на продажу за ' + data.info.price + ' кр.' );
						//$('#error').show();
						alert('Вы успешно выставили предмет "' + data.info.itm_name + '" на продажу за ' + data.info.price + ' кр.');
						$('#itm_auction' + data.info.id).hide();
					}
					$('#error').html('');
					$('#error').hide();
				}
				//
				locline.lineRefleshFinish();
				//
			});
		}
		//
	},
	
	getTopMenu:function() {
		var r = '';
		
		r += '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
		//
		r += '<td align="left"><span class="maroontext">Филиал Аукциона</span> <b><u>(тестовая версия)</u></b></td>';
		r += '<td align="right" id="toppages_line">Страницы: <span id="toppages">--</span> &nbsp; <button onclick="locjs.getDataItems(locjs.option_item);" class="btnnew">Обновить</button></td>';
		//
		r += '</tr><tr>';
		//
		r += '<td align="left" valign="bottom"><table border="0" cellspacing="0" cellpadding="0"><tr>'
		r += '<td style="padding:0 10px 0 10px;"><b>Залы:</b></td>';
		r += '<td><div id="stylemenu1" class="asel"><a href="javascript:locjs.selectMenu(1);">Торги</a></div></td>';
		r += '<td><div id="stylemenu2" class="unasel"><a href="javascript:locjs.selectMenu(2);">Ставки</a></div></td>';
		r += '<td><div id="stylemenu3" class="unasel"><a href="javascript:locjs.selectMenu(3);">Ваш предмет</a></div></td>';
		r += '<td><div id="stylemenu4" class="unasel"><a href="javascript:locjs.selectMenu(4);">Выставить предмет</a></div></td>';
		r += '<td><div id="stylemenu5" class="unasel"><a href="javascript:locjs.selectMenu(5);">Редкие предметы</a></div></td>';
		r += '</tr></table></td>';
		//
		r += '<td style="display:none" align="right">Имя: <input type="text" value=""> ';
		r += '<select id="searchtype">'+
			  '<option value="0">Все</option>'+
			  '<option value="1">серьги</option>'+
			  '<option value="2">ожерелья</option>'+
			  '<option value="3">оружие</option>'+
			  '<option value="4">броня</option>'+
			  '<option value="5">пояс</option>'+
			  '<option value="6">кольцо</option>'+
			  '<option value="7">шлем</option>'+
			  '<option value="8">щит</option>'+
			  '<option value="9">перчатки</option>'+
			  '<option value="10">обувь</option>'+
			  '<option value="11">наручи</option>'+
			  '<option value="12">рубаха</option>'+
			  '<option value="13">амуниция</option>'+
			  '<option value="14">заклинания</option>'+
			  '<option value="15">эликсиры</option>'+
			  '<option value="16">материалы</option>'+
			  '<option value="17">Остальные</option>'+
			'</select>';
		r += ', уровень <input style="width:20px;text-align:center;" type="text" value="0">-<input style="width:20px;text-align:center;" type="text" value="21"> <button class="btnnew" >Показать</button>';
		r += '</td>'
		//
		r += '</tr></table>';
		//		
		$('#auction_title').html( r );
		
		//
		//
		//
	},
	
	getMenuHtml:function() {
		var r = '';
		r += '<div align="right">' + locline.line( this.data.user.timeGo, this.data.user.timeGoL , this.data.user.timeNow , 'locjs.reflesh();' ) +
			'' + locline.room( this.data.locations ) + ''
			+  '</div>';
		//	
		r += '<br><small><div>У вас в наличии: <b id="u_money" style="color:#339900;">' + this.data.user.money + ' кр.</b></div><div>Масса: <span id="u_massa">' + parseInt(this.data.user.massaNow).toFixed(2) + '/' + parseInt(this.data.user.massaMax).toFixed(2) + '</span></div></small>';
		//	
		$('#auction_menu').html( r );
		locline.lineTimer();
	},
	
	selectMenu:function( id ) {
		if( $('#stylemenu' + id).attr('id') == 'stylemenu' + id ) {
			this.getDataItems( id );
			var i = 1
			while( i != - 1 ) {
				if( $('#stylemenu' + i).attr('id') == 'stylemenu' + i ) {
					//
					$('#stylemenu' + i).removeClass('unasel');
					$('#stylemenu' + i).removeClass('asel');
					//
					if( $('#stylemenu' + i).attr('id') == 'stylemenu' + id ) {
						$('#stylemenu' + i).addClass('asel');
					}else{
						$('#stylemenu' + i).addClass('unasel');
					}
					//
				}else{
					i = -2;
				}
				i++;
			}
		}else{
			alert('Раздел не найден');
		}
	},
	
	error:function( text ) {
		$('#error').html( text );
		if( text == '' ) {
			$('#error').hide();
		}else{
			$('#error').show();
		}
	},
	
	replaceslashhint:function(data) {
		data = this.str_replace('"','',data);
		data = this.str_replace("'","",data);
		return data;
	},
	
	str_replace:function(search, replace, subject, count) {	
	  var i = 0,
		j = 0,
		temp = '',
		repl = '',
		sl = 0,
		fl = 0,
		f = [].concat(search),
		r = [].concat(replace),
		s = subject,
		ra = Object.prototype.toString.call(r) === '[object Array]',
		sa = Object.prototype.toString.call(s) === '[object Array]';
	  s = [].concat(s);
	  if (count) {
		this.window[count] = 0;
	  }
	
	  for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') {
		  continue;
		}
		for (j = 0, fl = f.length; j < fl; j++) {
		  temp = s[i] + '';
		  repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
		  s[i] = (temp)
			.split(f[j])
			.join(repl);
		  if (count && s[i] !== temp) {
			this.window[count] += (temp.length - s[i].length) / f[j].length;
		  }
		}
	  }
	  return sa ? s : s[0];
	}
};