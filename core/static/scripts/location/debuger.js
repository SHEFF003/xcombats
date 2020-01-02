var bug = {
	
	menu:[
		[1,'Приемы',0]	
	],
	
	skills:{},
	
	start:function() {
		var html = '';
		//
		var i = 0;
		while( i != -1 ) {
			if( this.menu[i] != undefined ) {
				html += this.menuAdd( this.menu[i] );
			}else{
				i = -2;
			}
			i++;
		}
		//
		$('#mainblock').html( html );
	},
	
	menuAdd:function( m ) {
		var r = '';
		//
		r += 'test';
		//
		return r;
	}
	
};