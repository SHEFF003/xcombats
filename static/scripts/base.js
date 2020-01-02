var base = {
	
	/* Конфигурации */
	config: {
		'type':'none'
	},
	
	/* Хранилище данных */
	init:function( p, v) {
		if( v == null ) {
			delete this.config[p];
		}else{
			this.config[p] = v;
		}
	},	
	
	/* Хранилище данных , NONE */
	storage: {  },
	
	/* Получение данных */
	out:function( q ) {
		var obj = false;
		if( this.config['type'] == 'none' ) {
			if( !this.storage[q] ) {
				obj = false;
			}else{
				obj = this.storage[q];
			}
		}else if( this.config['type'] == 'flash' ) {
			
		}else if( this.config['type'] == 'html5' ) {
			
		}
		return obj;
	},
	
	/* Сохранение данных */	
	inc:function( q, obj , type ) {
		if( this.config['type'] == 'none' ) {
			if( type != true ) {
				obj = $.parseJSON( obj );
			}
			if( !this.storage[q] ) {
				//Сохраняем
				this.storage[q] = obj;
			}else{
				//Перезаписываем
				this.storage[q] = obj;
			}
		}else if( this.config['type'] == 'flash' ) {
			
		}else if( this.config['type'] == 'html5' ) {
			
		}
	},
	
	/* Удаление данных */
	deleted:function( q ) {
		if( this.config['type'] == 'none' ) {
			if( !this.storage[q] ) {
				//Удаляем
				
			}else{
				//Перезаписываем
				delete this.storage[q];
			}
			
		}else if( this.config['type'] == 'flash' ) {
			
		}else if( this.config['type'] == 'html5' ) {
			
		}
	}
	
};