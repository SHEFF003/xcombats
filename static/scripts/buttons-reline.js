 var ReLine = {
	v:[0,0],
	start:function() {
		
		$('#elements').html('<div id="bline" style="display:none" onselectstart="return false"></div><div id="hline" onselectstart="return false"><img class="hlinelz" src="http://'+cfg.host+'/1x1.gif" width="8" height="4" /><img class="hlinerz" src="http://'+cfg.host+'/1x1.gif" width="10" height="4" /><img class="dn" src="http://'+cfg.host+'/1x1.gif" width="100%" height="1" /></div><div id="vline" onselectstart="return false"><img class="dn" onselectstart="return false" src="http://'+cfg.host+'/1x1.gif" width="1" height="50%" /></div>');
		
		$('#hline').bind('mousedown',function() {
			top.ReLine.startHline();
		});	
		
		$('#vline').bind('mousedown',function() {
			top.ReLine.startVline();
		});	
		
		$('#bline').mouseup(function() {
			top.ReLine.stopVline();
			top.ReLine.stopHline();
		});
		
		$(window).resize(function(){top.ReLine.resetHVLine()});
		
		this.resetHVLine();	
	},
	resetHVLine:function(e) {
		$('#hline').css({
			'top':($('#chat_online').offset().top)+'px'
		});
		$('#vline').css({
			'left':($('#online').offset().left)+'px',
			'top':($('#online').offset().top)+'px',
			'height':($('#online').height())+'px'
		});
		$('#fm_main').height( ($(window).height()-$('#online').height()-55-18) + 'px' );
		$('#main').height( ($(window).height()-$('#online').height()-55-18) + 'px' );
	},
	goHVLine:function(e) {
		if(this.v[0] == 1) {
			chat.testScrollMessages();
			//hline
			var hp = Math.floor(($(window).height()-$('#hline').offset().top+25)/$(window).height()*100);

			$('#fm_main').height('0%');
			$('#fmain').height('0%');
			$('#fm_chat_online').height('0%');
			$('#chat_list').height('1px');
			$('#canals').height('1px');
			$('#online_list').height('1px');
			if(hp > 97) {
				if($('#fm_main').css('display') != 'none') {
					$('#fm_main').css({'display':'none'});
					$('#fm_main_l').css({'display':'none'});
					$('#fm_main_r').css({'display':'none'});
				}
				if($('#chat').css('display') == 'none'){
					$('#chat').css({'display':''});
					$('#online').css({'display':''});
					$('#vline').css({'display':''});
					$('#send_btns_h').css({'display':''});
					$('#send_btns_h2').css({'display':'none'});
				}
				hp = 100;
			}else if($('#fm_main').css('display') == 'none'){
				$('#fm_main').css({'display':''});
				$('#fm_main_l').css({'display':''});
				$('#fm_main_r').css({'display':''});
			} if(hp < 8) {
				hp = 8;
				if($('#chat').css('display') != 'none') {
						$('#chat').css({'display':'none'});
					$('#send_btns_h').css({'display':'none'});
					$('#send_btns_h2').css({'display':''});
					$('#online').css({'display':'none'});
					$('#vline').css({'display':'none'});
					GameEngine.timeStempReflesh();
				}
				if($('#fm_main').css('display') == 'none'){
					$('#fm_main').css({'display':'none'});
					$('#fm_main_l').css({'display':'none'});
					$('#fm_main_r').css({'display':'none'});
				}
				$('#fm_chat_online').height('1px');
			}else if($('#chat').css('display') == 'none'){
				$('#chat').css({'display':''});
				$('#online').css({'display':''});
				$('#vline').css({'display':''});
				$('#send_btns_h').css({'display':''});
				$('#send_btns_h2').css({'display':'none'});
			}
			
			if($('#chat').css('display') != 'none'){
				$('#fm_main').height((100-hp)+'%');
				$('#fmain').height(($('#fm_main').height()-13)+'px');
			}else{
				$('#fm_main').height((100-hp)+'%');
				$('#fmain').height(($('#fm_main').height()+13)+'px');
			}
			
			if($('#chat').css('display') != 'none'){
				if($.browser.msie) {
					//Этот неловкий момент когда понимаешь что пользователь сидит с IE
					var ie_h = 0;
					if($('#fm_main').css('display') == 'none'){
						ie_h = 16;
					}
					$('#fm_chat_online').height(( (100-($('#fm_main').height()+55+ie_h)/$(window).height()*100) )+'%');
				}else{
					$('#fm_chat_online').height(( Math.ceil(100-($('#fm_main').height()+55+4)/$(window).height()*100) )+'%');
				}
				$('#online_list').height(($('#fm_chat_online').height()-0)+'px');
				$('#chat_list').height(($('#fm_chat_online').height()-0)+'px');	
			}
			delete hp;
		}
		
		if(this.v[1] == 1) {
			//vline
			var vp = Math.floor(($('#chat_online').width()-$('#vline').offset().left+15)/$('#chat_online').width()*100);
			if(vp < 99 && vp > 1) {
				$('#online').width('0%');
				$('#chat').width('0%');	
				$('#chat_list').width('1px');
				$('#online_list').width('1px');
				$('#chat').width((100-vp)+'%');					
				$('#online_list').width(($('#online').width()-0)+'px');
				$('#chat_list').width(($('#chat').width()-0)+'px');
			}
			delete vp;
		}
		
		this.resetHVLine();
	},
	startHline:function() {
		if(this.v[0] == 0) {
			//включаем подставной блок
			$('#bline').css({'display':'block'});
			$('#bline').unbind('mousemove');
			$('#bline').mousemove(function(e) {
				$('#hline').css({
						'top':(e.pageY - $('#bline').offset().top)+'px'
				});
				top.ReLine.goHVLine(e);				
			});
			this.v[0] = 1;
		}else{
			this.stopHline();
		}
		chat.testScrollMessages();
	},
	stopHline:function(e) {
		//выключаем подставной блок
		$('#bline').css({'display':'none'});
		$('#bline').unbind('mousemove');	
		this.goHVLine(e);
		this.v[0] = 0;
		chat.testScrollMessages();
	},
	startVline:function() {
		if(this.v[1] == 0) {
			//включаем подставной блок
			$('#bline').css({'display':'block'});
			$('#bline').unbind('mousemove');
			$('#bline').mousemove(function(e) {
				$('#vline').css({
					'left':(e.pageX - $('#bline').offset().left)+'px'
				});
				top.ReLine.goHVLine(e);				
			});
			this.v[1] = 1;
		}else{
			this.stopVline();
		}
	},
	stopVline:function(e) {
		//выключаем подставной блок
		$('#bline').css({'display':'none'});
		$('#bline').unbind('mousemove');	
		this.goHVLine(e);
		this.v[1] = 0;
	},
	rebase:function() {
		chat.testScrollMessages();
		//сброс фреймов
			var hp = Math.floor(($(window).height()-$('#hline').offset().top+25)/$(window).height()*100);

			$('#fm_main').height('0%');
			$('#fmain').height('0%');
			$('#fm_chat_online').height('0%');
			$('#chat_list').height('1px');
			$('#canals').height('1px');
			$('#online_list').height('1px');
			if(hp > 97) {
				if($('#fm_main').css('display') != 'none') {
					$('#fm_main').css({'display':'none'});
					$('#fm_main_l').css({'display':'none'});
					$('#fm_main_r').css({'display':'none'});
				}
				if($('#chat').css('display') == 'none'){
					$('#chat').css({'display':''});
					$('#online').css({'display':''});
					$('#vline').css({'display':''});
					$('#send_btns_h').css({'display':''});
					$('#send_btns_h2').css({'display':'none'});
				}
				hp = 100;
			}else if($('#fm_main').css('display') == 'none'){
				$('#fm_main').css({'display':''});
				$('#fm_main_l').css({'display':''});
				$('#fm_main_r').css({'display':''});
			} if(hp < 8) {
				hp = 8;
				if($('#chat').css('display') != 'none') {
						$('#chat').css({'display':'none'});
					$('#send_btns_h').css({'display':'none'});
					$('#send_btns_h2').css({'display':''});
					$('#online').css({'display':'none'});
					$('#vline').css({'display':'none'});
					GameEngine.timeStempReflesh();
				}
				if($('#fm_main').css('display') == 'none'){
					$('#fm_main').css({'display':'none'});
					$('#fm_main_l').css({'display':'none'});
					$('#fm_main_r').css({'display':'none'});
				}
				$('#fm_chat_online').height('1px');
			}else if($('#chat').css('display') == 'none'){
				$('#chat').css({'display':''});
				$('#online').css({'display':''});
				$('#vline').css({'display':''});
				$('#send_btns_h').css({'display':''});
				$('#send_btns_h2').css({'display':'none'});
			}
			
			if($('#chat').css('display') != 'none'){
				$('#fm_main').height((100-hp)+'%');
				$('#fmain').height(($('#fm_main').height()-13)+'px');
			}else{
				$('#fm_main').height((100-hp)+'%');
				$('#fmain').height(($('#fm_main').height()+13)+'px');
			}
			
			if($('#chat').css('display') != 'none'){
				if($.browser.msie) {
					//Этот неловкий момент когда понимаешь что пользователь сидит с IE
					var ie_h = 0;
					if($('#fm_main').css('display') == 'none'){
						ie_h = 16;
					}
					$('#fm_chat_online').height(( (100-($('#fm_main').height()+55+ie_h+6)/$(window).height()*100) )+'%');
				}else{
					$('#fm_chat_online').height(( Math.ceil(100-($('#fm_main').height()+55+4)/$(window).height()*100) )+'%');
				}
				$('#online_list').height(($('#fm_chat_online').height()-0)+'px');
				$('#chat_list').height(($('#fm_chat_online').height()-0)+'px');	
			}
	}
 };