var u = {
	wName:{
			0:['����������',20,20],
			1:['������',60,20],
			2:['��������'],
			3:['������'],
			4:['�����'],
			5:['slot5'],
			6:['slot6'],
			7:['������'],
			8:['������'],
			9:['������'],
			10:['����'],
			11:['slot11'],
			12:['��������'],
			13:['�����'],
			14:['���']
	},
	userHealthData:{},
	userHealth:function( timeStart , hp , mp , hpAll , mpAll , minHP , minMP , block ) {
		var html = '';
			
		/*
		if( timeStart == 0 ) {
			timeStart = GameEngine.timenow();
		}
		*/
		
		//��������� ������� �� � �� �� ������ ������
		var timeLast = GameEngine.timenow()-timeStart;
		timeStart = GameEngine.timenow();
		
		GameEngine.c( hp );
		
		hp += hpAll/100*((timeLast/(minHP*60))*100);	
		//
		if( hp > hpAll ) {
			hp = hpAll;
		}else if( hp < 0 ) {
			hp = 0;	
		}
		
		if( $(block) != undefined && hp < hpAll ) {
			clearTimeout(this.userHealthData[$(block).attr('id')]);
			this.userHealthData[$(block).attr('id')] = setTimeout(function(){ u.userHealth( timeStart , hp , mp , hpAll , mpAll , minHP , minMP , block ); },500);
		}
		
		var hpl = Math.floor( Math.floor(hp) / Math.floor(hpAll) * 100 );
		var hpr = 100-hpl;
		
		html += '<table border="0" cellspacing="0" cellpadding="0">'+
				  '<tr height="10">'+
					'<td width="14"><img class="wib" src="/static/images/herz.gif" width="10" height="9"></td>'+
					'<td width="150">';
						//
		if( hpl < 33 ) {
			html += '<img class="wib" src="/static/images/1red.gif" width="' + hpl + '%" height="10">';
		}else if( hpl < 66 ) {
			html += '<img class="wib" src="/static/images/1yellow.gif" width="' + hpl + '%" height="10">';
		}else{
			html += '<img class="wib" src="/static/images/1green.gif" width="' + hpl + '%" height="10">';
		}
						//
		html += '<img class="wib" src="/static/images/1silver.gif" width="' + hpr + '%" height="10">'+
						//
					'</td>'+
					'<td>:&nbsp;' + Math.floor(hp) + '/' + hpAll + '</td>'+
				  '</tr>'+
				  '<tr>'+
					'<td></td>'+
					'<td></td>'+
					'<td></td>'+
				  '</tr>'+
				'</table>';
		
		if( block != null ) {
			$(block).html( html );
		}else{
			return html;
		}
	},
	userInfoLogin:function( id , login , level , align , clan ) {
		var html = '';
		html += '<b>' + login + '</b> [' + level + ']';
		html += '<a href="/userinfo/' + id + '" target="_blank"><img src="/static/images/inf.gif" width="12" height="11"></a>';
		return html;
	},
	userInformation:function( data , block ) {
		/*
		0 - id
		1 - �����
		2 - �������
		3 - ���
		4 - �����
		5 - ����������
		6 - ����
		7 - �������� �� ���������
		*/
		var html = '';
		
		html += '<div align="center">' + this.userInfoLogin( data[0] , data[1] , data[2] , data[4] , data[5] ) + '</div>';
		
		html += '<table width="250" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="top">';
		html += '<div id="hpblock' + data[0] + '"></div>';
		html += '<div class="ws_border">';
		html += '<div class="w_l">';
		//
		html += this.wDisplay( data[7] , 1 );
		html += this.wDisplay( data[7] , 2 );
		html += this.wDisplay( data[7] , 3 );
		html += this.wDisplay( data[7] , [ 4 , 5 , 6 ] );
		html += this.wDisplay( data[7] , 7 );
		html += this.wDisplay( data[7] , 8 );
		html += this.wDisplay( data[7] , 9 );
		//
		html += '</div>';
		//
		html += '<div class="w_c">';
		//
		html += this.oDisplay( data[5] , 0 );
		//
		html += '</div>';
		//
		html += '<div class="w_l">';
		//
		html += this.wDisplay( data[7] , [ 10 , 11 ] );
		html += this.wDisplay( data[7] , 12 );
		html += this.wDisplay( data[7] , 14 );
		html += this.wDisplay( data[7] , 13 );
		//
		html += '</div>';
		html += '</div>';
		html += '</td></tr></table>';
		
		if( html == '' ) {
			html = '<div align="center">Undefined data</div>';
		}
		$(block).html( html );
		
		//��������� ����� �� \ ��
		this.userHealth( data[8][0] , data[8][1] , data[8][3] , data[8][2] , data[8][4] , data[8][5] , data[8][6] , $(block).find('#hpblock' + data[0] + '') );
	},
	wDisplay:function( itm , w ) {
		var html = '';
		
		if( html == '' ) {
			if( typeof w == 'object' ) {
				w = w[0];
			}
			html = '<img title="������ ���� ' + this.wName[w][0] + '" class="wi" width="' + this.wName[w][1] + '" height="' + this.wName[w][2] + '" src="/static/images/items/w/w' + w + '.gif">';
		}
		return html;
	},
	oDisplay:function( img , sex ) {
		var html = '';
		html += '<img width="76" height="209" src="/static/images/chars/' + sex + '/' + img + '">';
		return html;
	}
};