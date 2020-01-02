// JavaScript Document
var locline = {
	
	lineRefleshStart:function() {
		$('#line_rel1').hide();
		$('#line_rel2').show();
		this.finish = 0;
	},
	lineRefleshFinish:function() {
		$('#line_rel2').hide();
		$('#line_rel1').show();
		this.finish = 1;
	},
	finish:1,
	line:function( a , b , c , reflesh ) {
		var r = '';
		this.a = a;
		this.b = b;
		this.c = c;
		var d1 = '', d2 = 'none';
		if( this.finish == 0 ) {
			d2 = '';
			d1 = 'none';
		}
		r += '<table height="15" border="0" cellspacing="0" cellpadding="0">' + 
               '<tr>'+
               '<td id="locobobr" rowspan="3" valign="bottom">' +
			   '<a id="line_rel1" style="display:' +d1+ ';cursor:pointer" onclick="locline.lineRefleshStart();' + reflesh + '"><img style="display:block;" src="http://img.xcombats.com/i/move/rel_1.gif" width="15" height="16" title="Обновить" border="0" /></a>'+
			   '<a id="line_rel2" style="display:' +d2+ ';cursor:pointer"><img style="display:block;" src="http://img.xcombats.com/i/move/rel_2.gif" width="15" height="16" title="Обновить" border="0" /></a>'+
			   '</td>'+
               '<td colspan="3"><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_462s.gif" width="80" height="4" /></td>'+
               '</tr>'+
               '<tr>'+
               '<td><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_481.gif" width="9" height="8" /></td>'+
               		'<td width="64" bgcolor="black"><img src="http://img.xcombats.com/1x1.gif" style="background:url(http://img.xcombats.com/i/move/wait2.gif) 0px 0px repeat-y;height:6px;display:block;" id="MoveLine" height="8" style="width:33px;" /></td>'+
               '<td><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_50.gif" width="7" height="8" /></td>'+
               '</tr>'+
               '<tr>'+
              		'<td colspan="3"><img style="display:block;" src="http://img.xcombats.com/i/move/navigatin_tt1_532.gif" width="80" height="4" /></td>'+
               '</tr>'+
               '</table>';
		return r;
	},
	timer:null,
	b:0,
	a:0,
	c:0,
	lineTimer:function() {
		if(document.getElementById('MoveLine')!=undefined) {
			var prc = Math.ceil( (this.a - this.c) );
			if( prc < 0 ) {
				prc = 0;
			}
			$('#MoveLine').animate({'width':'63px'}, prc * 1000 , "linear" );
		}
		return '';
	},
	room:function( data ) {
		var r = '';
		/*
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
<tr>
	<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&rnd=1';" title="Центральная площадь
Сейчас в комнате 0 чел.">Центральная Площадь</a></td>
	</tr>
		</table>
		*/
		r +=  '<table border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">';
		var i = 0;
		while( i != - 1 ) {
			if( data[i] != undefined ) {
				r += '<tr><td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>';
				r += '<td bgcolor="#D3D3D3" nowrap><a style="cursor:pointer;" id="greyText" class="menutop" onclick="location=\'/main.php?loc=' + data[i][0] + '\';" title="' + data[i][2] + '">' + data[i][2] + '</a></td></tr>';
			}else{
				i = -2;
			}
			i++;
		}
		r += '</table>';
		return r;
	}
	
};