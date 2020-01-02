var dress = {
	start:function() {
		//Верхушка
		$('#main1').html( this.htmlMainOne() );
	},
	//
	titleMenu:function(data) {
		
	},
	//
	htmlMainOne:function() {
		var html = '';
		//
		var effs = '';
		//
		html += '<table width="240" border="0" cellspacing="0" cellpadding="0">'+
				  '<tr>'+
					'<td width="60"><table width="60" border="0" cellspacing="0" cellpadding="0">'+
					  '<tr>'+
						'<td width="60" height="60">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="40">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="60">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="60">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="60">&nbsp;</td>'+
					  '</tr>'+
					'</table></td>'+
					'<td width="120"><table width="120" border="0" cellspacing="0" cellpadding="0">'+
					  '<tr>'+
						'<td width="120" height="10"></td>'+
					  '</tr>'+
					  '<tr>'+
						'<td width="120" height="10"></td>'+
					  '</tr>'+
					  '<tr>'+
						'<td width="120" onclick="dress.titleMenu(['+
						'[\'Сменить логин\',\'dress.changeLogin();\'],'+
						'[\'Сменить пол\',\'dress.changeSex();\']'+
						']);" height="220" style="background-image:url(\'http://img.xcombats.com/i/obraz/' + u.info.sex + '/' + u.info.shadow + '\');" valign="top">'+effs+'</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td width="120" height="40">&nbsp;</td>'+
					  '</tr>'+
					'</table></td>'+
					'<td width="60"><table width="60" border="0" cellspacing="0" cellpadding="0">'+
					  '<tr>'+
						'<td width="60" height="20">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="20">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="20">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="40">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="60">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="60">&nbsp;</td>'+
					  '</tr>'+
					  '<tr>'+
						'<td height="60">&nbsp;</td>'+
					  '</tr>'+
					'</table></td>'+
				  '</tr>'+
				  '</table>';
		//
		return html;
	}
};