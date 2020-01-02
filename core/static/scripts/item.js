// JavaScript Document
var itmjs = {
	
	city: {
		'capitalcity' : 'Capital City'
	},
	
	explode:function ( delimiter, string ) {	// Split a string by string
		// 
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: kenneth
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	
		var emptyArray = { 0: '' };
	
		if ( arguments.length != 2
			|| typeof arguments[0] == 'undefined'
			|| typeof arguments[1] == 'undefined' )
		{
			return null;
		}
	
		if ( delimiter === ''
			|| delimiter === false
			|| delimiter === null )
		{
			return false;
		}
	
		if ( typeof delimiter == 'function'
			|| typeof delimiter == 'object'
			|| typeof string == 'function'
			|| typeof string == 'object' )
		{
			return emptyArray;
		}
	
		if ( delimiter === true ) {
			delimiter = '1';
		}
	
		return string.toString().split ( delimiter.toString() );
	},
	
	lookStats:function( data ) {
		var r = { },de = [];
		var di = this.explode('|',data);
		var i = 0;
		while( i != -1 ) {
			if( di[i] != undefined ) {
				de = this.explode('=',di[i]);
				r[de[0]] = de[1];
			}else{
				i = -2;
			}
			i++;
		}
		return r;
	},
	
	st_tr:['sex','align','lvl','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','a1','a2','a3','a4','a5','a6','a7','mg1','mg2','mg3','mg4','mg5','mg6','mg7','mall','m2all','aall','rep','align_bs'],
	st_all:['exp','align_bs','nopryh','puti','align','hpAll','mpAll','enAll','sex','lvl','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','a1','a2','a3','a4','a5','a6','a7','aall','mall','m2all','mg1','mg2','mg3','mg4','mg5','mg6','mg7','tj','lh','minAtack','maxAtack','m10','m11','m11a','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','zm','zma','za1','za2','za3','za4','zm1','zm2','zm3','zm4','zm5','zm6','zm7','magic_cast','pza','pzm','pza1','min_heal_proc','notravma','yron_min','yron_max','zaproc','zmproc','zm2proc','pza2','pza3','pza4','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','speedhp','speedmp','tya1','tya2','tya3','tya4','tym1','mg2static_points','tym2','tym3','tym4','hpProc','mpProc','tym5','tym6','tym7','min_use_mp','pog','pog2','pog2p','pog2mp','maxves','bonusexp','speeden','yza','yzm','yzma','yza1','yza2','yza3','yza4','yzm1','yzm2','yzm3','yzm4','yzm5','yzm6','yzm7','rep'],
	st_add:['exp','enemy_am1','hod_minmana','yhod','noshock_voda','yza','yzm','yzma','yza1','yza2','yza3','yza4','yzm1','yzm2','yzm3','yzm4','yzm5','yzm6','yzm7','notuse_last_pr','yrn_mg_first','antishock','nopryh','speed_dungeon','naemnik','mg2static_points','yrnhealmpprocmg3','nousepriem','notactic','seeAllEff','100proboi1','pog2','pog2p','magic_cast','min_heal_proc','no_yv1','no_krit1','no_krit2','no_contr1','no_contr2','no_bl1','no_pr1','no_yv2','no_bl2','no_pr2','silver','pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','yron_min','yron_max','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','hpVinos','mpVinos','mpAll','enAll','hpProc','mpProc','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','m11a','zona','zonb','maxves','minAtack','maxAtack','bonusexp','speeden'],
	st_sv:['pza','pza1','pza2','pza3','pza4','pzm','pzm1','pzm2','pzm3','pzm4','pzm5','pzm6','pzm7','notravma','min_zonb','min_zona','nokrit','pog','min_use_mp','za1proc','za2proc','za3proc','za4proc','zaproc','zmproc','zm1proc','zm2proc','zm3proc','zm4proc','shopSale','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','aall','a1','a2','a3','a4','a5','a6','a7','m2all','mall','mg1','mg2','mg3','mg4','mg5','mg6','mg7','hpAll','mpAll','enAll','m1','m2','m3','m4','m5','m6','m7','m8','m9','m14','m15','m16','m17','m18','m19','m20','pa1','pa2','pa3','pa4','pm1','pm2','pm3','pm4','pm5','pm6','pm7','za','za1','za2','za3','za4','zma','zm','zm1','zm2','zm3','zm4','zm5','zm6','zm7','mib1','mab1','mib2','mab2','mib3','mab3','mib4','mab4','speedhp','speedmp','m10','m11','zona','zonb','maxves','minAtack','maxAtack','speeden'],
	infoItem:function( data , i , module ) {
		var r = '', itm = data[i];
		//
		if( itm[6] != itm[12] && itm[12] > 1 ) {
			itm[6] = itm[12];
			if( data['g'][itm['iid']] != undefined && itm[21] > 0 && data['g'][itm['iid']][itm[21]] > 0 ) {
				itm[6] * data['g'][itm['iid']][itm[21]];
			}
		}
		var po = this.lookStats( itm[20] );
		r += '<a href="http://xcombats.com/item/' + itm['iid'] + '" target="_blank">' + itm[1] + '';
		if( module == 'comission' || module == 'comission_pick' ) {
			if( itm['x'] > 1 ) {
				r += ' (x' + itm['x'] + ')';
			}
		}else{
			if( itm[21] > 0 && data['g'][itm['iid']] != undefined && data['g'][itm['iid']][itm[21]] > 0 ) {
				if( data['g'][itm['iid']][itm[21]] > 1 ) {
					r += ' (x' + data['g'][itm['iid']][itm[21]] + ')';
				}
			}
		}
		r += '</a>';
		r += ' &nbsp;&nbsp; (Масса: ' + itm[10] + ')';
		if( po['art'] != undefined ) {
			r += ' <img title="Артефакт" src="http://img.xcombats.com/i/artefact.gif" width="18" height="16">';				
		}
		if( itm[17] != '' ) {
			r += ' <img title="Вы не можете передать этот предмет кому-либо. Подарок от ' + itm[17] + '" src="http://img.xcombats.com/i/desteny.gif" width="16" height="18">';				
		}
		if( po['sudba'] != undefined ) {
			if( po['sudba'] == 0 ) {
				r += ' <img title="Предмет будет связан общей судьбой с первым, кто наденет его. Никто другой не сможет его использовать." src="http://img.xcombats.com/destiny0.gif" width="16" height="18">';				
			}else if( po['sudba'] == 1 ) {
				r += ' <img title="Предмет будет связан общей судьбой с первым, кто получит его. Никто другой не сможет его использовать." src="http://img.xcombats.com/destiny_pickup.png" width="16" height="18">';
			}else{
				r += ' <img title="Предмет связан общей судьбой с ' + po['sudba'] + '. Никто другой не сможет его использовать." src="http://img.xcombats.com/desteny.gif" width="16" height="18">';
			}
		}
		r += '<br>';
		if( module == 'comission' || module == 'comission_pick' ) {
			r += '<b>Цена: ' + itm['prc'] + ' кр.</b> ';
			r += ' &nbsp;&nbsp; (Гос.Цена. ' + (itm['x'] * itm[6]) + ' кр.)';
			//r += ' <small>(Количество: 1)</small>';
			r += '<br>'
		}else{
			if( itm[6] > 0 ) {
				r += '<b>Цена: ' + itm[6] + ' кр.</b>';
				r += '<br>'
			}
		}
		if( Math.ceil(itm[16]) > 0 ) {
			r += 'Долговечность: ' + Math.ceil(itm[15]) + '/' + Math.ceil(itm[16]) + '<br>';
		}
		//
		var tr = '';
			
			var i = 0;
			while( i != -1 ) {
				if( this.st_tr[i] != undefined ) {
					if( po['tr_' + this.st_tr[i] ] != undefined && this.st_is[this.st_tr[i]][0] != undefined && this.st_is[this.st_tr[i]][0] != '' ) {
						tr += '<br>&bull; ' + this.st_is[this.st_tr[i]][0] + ': ' + po['tr_' + this.st_tr[i] ] + '';
					}
				}else{
					i = -2;
				}
				i++;
			}
			
			
		if( tr != '' ) {
			r += '<b>Требует минимальное:</b>' + tr + '<br>';
			tr = '';
		}
		//
		var add = '';
		
			var i = 0;
			while( i != -1 ) {
				if( this.st_add[i] != undefined ) {
					if( po['add_' + this.st_add[i] ] != undefined && this.st_is[this.st_add[i]] != undefined && this.st_is[this.st_add[i]][0] != undefined && this.st_is[this.st_add[i]][0] != '' ) {
						add += '<br>&bull; ' + this.st_is[this.st_add[i]][0] + ': ';
						if( po['add_' + this.st_add[i] ] > 0 ) {
							add += '+';
						}
						add += '' + po['add_' + this.st_add[i] ] + '';
					}
				}else{
					i = -2;
				}
				i++;
			}
			if( po['add_minAtack'] != undefined ) {
				add += '<br>&bull; Минимальный урон: ';
				if( po['add_minAtack'] > 0 ) {
					add += '+';
				}
				add += po['add_minAtack'];
			}
			if( po['add_maxAtack'] != undefined ) {
				add += '<br>&bull; Максимальный урон: ';
				if( po['add_maxAtack'] > 0 ) {
					add += '+';
				}
				add += po['add_minAtack'];
			}
			if( po['add_mib1'] != undefined ) {
				add += '<br>&bull; Броня головы: ' + po['add_mib1'] + '-' + po['add_mab1'] + '';
			}
			if( po['add_mib2'] != undefined ) {
				add += '<br>&bull; Броня корпуса: ' + po['add_mib2'] + '-' + po['add_mab2'] + '';
			}
			if( po['add_mib3'] != undefined ) {
				add += '<br>&bull; Броня пояса: ' + po['add_mib3'] + '-' + po['add_mab3'] + '';
			}
			if( po['add_mib4'] != undefined ) {
				add += '<br>&bull; Броня ног: ' + po['add_mib4'] + '-' + po['add_mab4'] + '';
			}
		
		if( add != '' ) {
			r += '<b>Действует на:</b>' + add + '<br>';
			add = '';
		}
		//
		var sv = '';

			if( po['sv_yron_min'] != undefined ) {
				sv += '<br>&bull; Урон: ' + po['sv_yron_min'] + '-' + po['sv_yron_max'] + '';
			}
			var i = 0;
			while( i != -1 ) {
				if( this.st_sv[i] != undefined ) {
					if( po['sv_' + this.st_sv[i] ] != undefined && this.st_is[this.st_sv[i]][0] != undefined && this.st_is[this.st_sv[i]][0] != '' ) {
						sv += '<br>&bull; ' + this.st_is[this.st_sv[i]][0] + ': ';
						if( po['sv_' + this.st_sv[i] ] > 0 ) {
							sv += '+';
						}
						sv += '' + po['sv_' + this.st_sv[i] ] + '';
					}
				}else{
					i = -2;
				}
				i++;
			}
			if( itm[4] > 0 ) {
				sv += '<br>&bull; Второе оружие';
			}
			if( itm[3] > 0 ) {
				sv += '<br>&bull; Двуручное оружие';
			}
			if( po['zonb'] != undefined ) {
				var pozonb = '';
				if( po['zonb'] > 0 ) {
					var i = 0;
					while( i < po['zonb'] ) {
						pozonb += '+';
						i++;
					}
				}else if( po['zonb'] < 0 ) {
					var i = 0;
					while( i > po['zonb'] ) {
						pozonb += '-';
						i--;
					}
				}else{
					po['zonb'] += '??';
				}
				sv += '<br>&bull; Зоны блокирования: ' + pozonb + '';
			}

		if( sv != '' ) {
			r += '<b>Свойства предмета:</b>' + sv + '<br>';
			sv = '';
		}
		//
		var yl = '';
		
		if( yl != '' ) {
			r += '<b>Улучшения предмета:</b>' + yl + '<br>';
			yl = '';
		}
		//
		var os = '';
		var i = 1;
		while( i <= 11 ) {
			if( i < 5 ) {
				if( po['tya' + i] != undefined ) {
					os += '<br>&bull; ' + this.st_is['tya' + i][0] + ': ' + this.tympar(po['tya' + i]);
				}
			}else{
				if( po['tym' + i] != undefined ) {
					os += '<br>&bull; ' + this.st_is['tym' + i][0] + ': ' + this.tympar(po['tym' + i]);
				}
			}
			i++;
		}
		if( os != '' ) {
			r += '<b>Особенности предмета:</b>' + os + '<br>';
			os = '';
		}
		//
		var sd = '';
		
		if( itm[9] != '' ) {
			sd += '<b>Описание:</b><br>' + itm[9] + '<br>';
		}
		
		if( itm[19] != '' ) {
			sd += 'Сделано в ' + this.city[itm[19]] + '<br>';
		}
		//
		if( po['nosale'] != undefined ) {
			sd += '<font color="brown">Предмет нельзя продать</font><br>';
		}
		if( po['noremont'] != undefined ) {
			sd += '<font color="brown">Предмет не подлежит ремонту</font><br>';
		}
		if( po['zazuby'] != undefined ) {
			sd += '<font color="brown">Предмет куплен за зубы</font><br>';
		}
		if( po['frompisher'] != undefined ) {
			sd += '<font color="brown">Предмет из подземелья</font><br>';
		}
		//
		if( sd != '' ) {
			r += '<small>' + sd + '</small>';
			sd = '';
		}
		return r;
	},
	
	tympar:function(val) {
		var r = '';
		if( val >= 100 ) {
			r = 'Всегда';	
		}else if( val > 89 ) {
			r = 'Часты'
		}else if( val > 69 ) {
			r = 'Регулярны';
		}else if( val > 39 ) {
			r = 'Временами';
		}else if( val > 19 ) {
			r = 'Малы';
		}else if( val > 9 ) {
			r = 'Редки';
		}else{
			r = 'Ничтожно редки';
		}
		r += ' (' + val + '%)';							
		return r;
	},
	
	st_is:{
		'exp':['Получаемый опыт (%)'],
		'align_bs':['Служитель закона'],
		'nopryh':['Прямое поподание'],
		'puti':['Запрет перемещения'],
		'align':['Склонность'],
		'hpAll':['Уровень жизни (HP)'],
		'mpAll':['Уровень маны'],
		'enAll':['Уровень энергии'],
		'sex':['Пол'],
		'lvl':['Уровень'],
		's1':['Сила'],
		's2':['Ловкость'],
		's3':['Интуиция'],
		's4':['Выносливость'],
		's5':['Интеллект'],
		's6':['Мудрость'],
		's7':['Духовность'],
		's8':['Воля'],
		's9':['Свобода духа'],
		's10':['Божественность'],
		's11':['Энергия'],
		'm1':['Мф. критического удара (%)'],
		'm2':['Мф. против критического удара (%)'],
		'm3':['Мф. мощности крит. удара (%)'],
		'm4':['Мф. увертывания (%)'],
		'm5':['Мф. против увертывания (%)'],
		'm6':['Мф. контрудара (%)'],
		'm7':['Мф. парирования (%)'],
		'm8':['Мф. блока щитом (%)'],
		'm9':['Мф. удара сквозь броню (%)'],
		'm14':['Мф. абс. критического удара (%)'],
		'm15':['Мф. абс. увертывания (%)'],
		'm16':['Мф. абс. парирования (%)'],
		'm17':['Мф. абс. контрудара (%)'],
		'm18':['Мф. абс. блока щитом (%)'],
		'm19':['Мф. абс. магический промах (%)'],
		'm20':['Мф. удача (%)'],
		'a1':['Мастерство владения ножами, кинжалами'],
		'a2':['Мастерство владения топорами, секирами'],
		'a3':['Мастерство владения дубинами, молотами'],
		'a4':['Мастерство владения мечами'],
		'a5':['Мастерство владения магическими посохами'],
		'a6':['Мастерство владения луками'],
		'a7':['Мастерство владения арбалетами'],
		'aall':['Мастерство владения оружием'],
		'mall':['Мастерство владения магией стихий'],
		'm2all':['Мастерство владения магией'],
		'mg1':['Мастерство владения магией огня'],
		'mg2':['Мастерство владения магией воздуха'],
		'mg3':['Мастерство владения магией воды'],
		'mg4':['Мастерство владения магией земли'],
		'mg5':['Мастерство владения магией Света'],
		'mg6':['Мастерство владения магией Тьмы'],
		'mg7':['Мастерство владения серой магией'],
		'tj':['Тяжелая броня'],
		'lh':['Легкая броня'],
		'minAtack':['Минимальный урон'],
		'maxAtack':['Максимальный урон'],
		'm10':['Мф. мощности урона'],
		'm11':['Мф. мощности магии стихий'],
		'm11a':['Мф. мощности магии'],
		'pa1':['Мф. мощности колющего урона'],
		'pa2':['Мф. мощности рубящего урона'],
		'pa3':['Мф. мощности дробящий урона'],
		'pa4':['Мф. мощности режущий урона'],
		'pm1':['Мф. мощности магии огня'],
		'pm2':['Мф. мощности магии воздуха'],
		'pm3':['Мф. мощности магии воды'],
		'pm4':['Мф. мощности магии земли'],
		'pm5':['Мф. мощности магии Света'],
		'pm6':['Мф. мощности магии Тьмы'],
		'pm7':['Мф. мощности серой магии'],
		'za':['Защита от урона'],
		'zm':['Защита от магии стихий'],
		'zma':['Защита от магии'],
		'za1':['Защита от колющего урона'],
		'za2':['Защита от рубящего урона'],
		'za3':['Защита от дробящего урона'],
		'za4':['Защита от режущего урона'],
		'zm1':['Защита от магии огня'],
		'zm2':['Защита от магии воздуха'],
		'zm3':['Защита от магии воды'],
		'zm4':['Защита от магии земли'],
		'zm5':['Защита от магии Света'],
		'zm6':['Защита от магии Тьмы'],
		'zm7':['Защита от серой магии'],
		'magic_cast':['Дополнительный каст за ход'],
		'pza':['Понижение защиты от урона'],
		'pzm':['Понижение защиты от магии'],
		'pza1':['Понижение защиты от колющего урона'],
		'min_heal_proc':['Эффект лечения (%)'],
		'notravma':['Защита от травм'],
		'yron_min':['Минимальный урон'],
		'yron_max':['Максимальный урон'],
		'zaproc':['Защита от урона (%)'],
		'zmproc':['Защита от магии стихий (%)'],
		'zm2proc':['Защита от магии Воздуха (%)'],
		'pza2':['Понижение защиты от рубящего урона'],
		'pza3':['Понижение защиты от дробящего урона'],
		'pza4':['Понижение защиты от режущего урона'],
		'pzm1':['Понижение защиты от магии огня'],
		'pzm2':['Понижение защиты от магии воздуха'],
		'pzm3':['Понижение защиты от магии воды'],
		'pzm4':['Понижение защиты от магии земли'],
		'pzm5':['Понижение защиты от магии Света'],
		'pzm6':['Понижение защиты от магии Тьмы'],
		'pzm7':['Понижение защиты от серой магии'],
		'speedhp':['Регенерация здоровья (%)'],
		'speedmp':['Регенерация маны (%)'],
		'tya1':['Колющие атаки'],
		'tya2':['Рубящие атаки'],
		'tya3':['Дробящие атаки'],
		'tya4':['Режущие атаки'],
		'tym1':['Огненные атаки'],
		'mg2static_points':['Уровень заряда (Воздух)'],
		'tym2':['Электрические атаки'],
		'tym3':['Ледяные атаки'],
		'tym4':['Земляные атаки'],
		'hpProc':['Уровень жизни (%)'],
		'mpProc':['Уровень маны (%)'],
		'tym5':['Атаки Света'],
		'tym6':['Атаки Тьмы'],
		'tym7':['Серые атаки'],
		'min_use_mp':['Уменьшает расход маны'],
		'pog':['Поглощение урона'],
		'pog2':['Поглощение урона'],
		'pog2p':['Процент поглощение урона'],
		'pog2mp':['Цена поглощение урона'],
		'maxves':['Увеличивает рюкзак'],
		'bonusexp':['Увеличивает получаемый опыт'],
		'speeden':['Регенерация энергии (%)'],
		'yza':['Уязвимость физическому урона (%)'],
		'yzm':['Уязвимость магии стихий (%)'],
		'yzma':['Уязвимость магии (%)'],
		'yza1':['Уязвимость колющему урона (%)'],
		'yza2':['Уязвимость рубящему урона (%)'],
		'yza3':['Уязвимость дробящему урона (%)'],
		'yza4':['Уязвимость режущему урона (%)'],
		'yzm1':['Уязвимость магии огня (%)'],
		'yzm2':['Уязвимость магии воздуха (%)'],
		'yzm3':['Уязвимость магии воды (%)'],
		'yzm4':['Уязвимость магии земли (%)'],
		'yzm5':['Уязвимость магии (%)'],
		'yzm6':['Уязвимость магии (%)'],
		'yzm7':['Уязвимость магии (%)'],
		'rep':['Репутация Рыцаря']
	}
	
};