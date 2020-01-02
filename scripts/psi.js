var psi = {
	text:function(text) {
		var r = '';
		
		return r;
	},
	testForm:function() {
		$.ajax({
			type:"POST",
			dataType: "json",
			url:'register.php' ,
			data:{
				'ajax_reg':true,
				'id':$('#reg_id').val(),
				'login':$('#register_login' + $('#reg_id').val()).val(),
				'pass':$('#register_pass' + $('#reg_id').val()).val(),
				'pass2':$('#register_pass2' + $('#reg_id').val()).val(),
				'sex':$('#psi_register_sex' + $('#reg_id').val()).val(),
				'dd':$('#register_dd' + $('#reg_id').val()).val(),
				'mm':$('#register_mm' + $('#reg_id').val()).val(),
				'yy':$('#register_yyyy' + $('#reg_id').val()).val(),
				'mail':$('#register_mail' + $('#reg_id').val()).val(),
				'align':$('#register_align' + $('#reg_id').val()).val(),
				'rules':$('#psi_register_rules' + $('#reg_id').val()).val(),
				'keycode':$('#register_key' + $('#reg_id').val()).val(),
				'refu':$('#refu' + $('#reg_id').val()).val()
			} ,
			success:function(data) { 
				psi.testFormData(data);
			}
		});
	},
	testFormData:function(data) {
		//login
		if( data[0] == 0 ) {
			$('#register_login' + $('#reg_id').val()).attr('class','psi_input1_none');
			$('#login_error_text').html('');
			$('#login_error').css({ 'display':'none' });
		}else if( data[0] == 1 ) {
			$('#register_login' + $('#reg_id').val()).attr('class','psi_input1_green');
			$('#login_error_text').html('');
			$('#login_error').css({ 'display':'none' });
		}else{
			$('#register_login' + $('#reg_id').val()).attr('class','psi_input1_red');
			$('#login_error_text').html(data[0]);
			$('#login_error').css({ 'display':'' });			
		}
		//pass
		if( data[1] == 0 ) {
			$('#register_pass' + $('#reg_id').val()).attr('class','psi_input1_none');
			$('#register_pass2' + $('#reg_id').val()).attr('class','psi_input1_none');
			$('#pass_error_text').html('');
			$('#pass_error').css({ 'display':'none' });
		}else if( data[1] == 1 ) {
			$('#register_pass' + $('#reg_id').val()).attr('class','psi_input1_green');
			$('#register_pass2' + $('#reg_id').val()).attr('class','psi_input1_green');
			$('#pass_error_text').html('');
			$('#pass_error').css({ 'display':'none' });
		}else{
			$('#register_pass' + $('#reg_id').val()).attr('class','psi_input1_red');
			$('#register_pass2' + $('#reg_id').val()).attr('class','psi_input1_red');
			$('#pass_error_text').html(data[1]);
			$('#pass_error').css({ 'display':'' });			
		}
		//pass
		if( data[2] == 0 ) {
			$('#1register_dd' + $('#reg_id').val()).attr('class','psi_input1_none psi_list');
			$('#1register_mm' + $('#reg_id').val()).attr('class','psi_input1_none psi_list');
			$('#1register_yyyy' + $('#reg_id').val()).attr('class','psi_input1_none psi_list');
			$('#bd_error_text').html('');
			$('#bd_error').css({ 'display':'none' });
		}else if( data[2] == 1 ) {
			$('#1register_dd' + $('#reg_id').val()).attr('class','psi_input1_green psi_list');
			$('#1register_mm' + $('#reg_id').val()).attr('class','psi_input1_green psi_list');
			$('#1register_yyyy' + $('#reg_id').val()).attr('class','psi_input1_green psi_list');
			$('#bd_error_text').html('');
			$('#bd_error').css({ 'display':'none' });
		}else{
			$('#1register_dd' + $('#reg_id').val()).attr('class','psi_input1_red psi_list');
			$('#1register_mm' + $('#reg_id').val()).attr('class','psi_input1_red psi_list');
			$('#1register_yyyy' + $('#reg_id').val()).attr('class','psi_input1_red psi_list');
			$('#bd_error_text').html(data[2]);
			$('#bd_error').css({ 'display':'' });			
		}
		//rules
		if( data[3] == 0 ) {
			//$('#register_login' + $('#reg_id').val()).attr('class','psi_input1_none');
			$('#rules_error_text').html('');
			$('#rules_error').css({ 'display':'none' });
		}else if( data[3] == 1 ) {
			//$('#register_login' + $('#reg_id').val()).attr('class','psi_input1_green');
			$('#rules_error_text').html('');
			$('#rules_error').css({ 'display':'none' });
		}else{
			//$('#register_login' + $('#reg_id').val()).attr('class','psi_input1_red');
			$('#rules_error_text').html(data[3]);
			$('#rules_error').css({ 'display':'' });			
		}
		//mail
		if( data[4] == 0 ) {
			$('#register_mail' + $('#reg_id').val()).attr('class','psi_input1_none');
			$('#mail_error_text').html('');
			$('#mail_error').css({ 'display':'none' });
		}else if( data[4] == 1 ) {
			$('#register_mail' + $('#reg_id').val()).attr('class','psi_input1_green');
			$('#mail_error_text').html('');
			$('#mail_error').css({ 'display':'none' });
		}else{
			$('#register_mail' + $('#reg_id').val()).attr('class','psi_input1_red');
			$('#mail_error_text').html(data[4]);
			$('#mail_error').css({ 'display':'' });			
		}
		//key
		if( data[5] == 0 ) {
			$('#register_key' + $('#reg_id').val()).attr('class','psi_input1_none');
			$('#key_error_text').html('');
			$('#key_error').css({ 'display':'none' });
		}else if( data[5] == 1 ) {
			$('#register_key' + $('#reg_id').val()).attr('class','psi_input1_green');
			$('#key_error_text').html('');
			$('#key_error').css({ 'display':'none' });
		}else{
			$('#register_key' + $('#reg_id').val()).attr('class','psi_input1_red');
			$('#key_error_text').html(data[5]);
			$('#key_error').css({ 'display':'' });			
		}
		//Завершение регистрации
		if( data[6] == 1 ) {
			location.href = 'http://xcombats.com/bk';
		}
	},
	input:function( id , name , value , valueHide , type , className , styleData ) {
		var r = '';
		r += '<input';
		if( id != null ) {
			r += ' id="' + id + '"';
		}
		if( name != null ) {
			r += ' name="' + name + '"';
		}
		if( value != null ) {
			r += ' value="' + value + '"';
		}
		if( valueHide != null ) {
			r += ' onfocus="if ( \'' + valueHide + '\' == value ) { value = \'\'; } " onblur="if ( \'\' == value ) { value = \'' + valueHide + '\'; } "';
		}
		if( type != null ) {
			r += ' type="' + type + '"';
		}else{
			r += ' type="text"';
		}
		if( className != null ) {
			r += ' class="' + className + '_none"';
		}
		if( styleData != null ) {
			r += ' style="' + styleData + '"';
		}
		r += ' />';
		return r;
	},
	inputPrint:function( id , name , value , valueHide , type , className , styleData ) {
		document.write( this.input( id , name , value , valueHide , type , className , styleData ) );
	},
	startTestingData:function(beat,formID) {
		
	},
	check:function( id , name , block ) {
		var r = '';
		if( block != null ) {
			$('#' + id + 'block').click(function(){
				psi.checkPress(id);
			});
			r += '<input type="hidden" name="psi_' + id + '" id="psi_' + id + '" value="0"><img id="' + id + '" name="' + name + '" src="images/1x1.png" width="19" height="19" class="psi_check1">';
		}else{
			r += '<input type="hidden" name="psi_' + id + '" id="psi_' + id + '" value="0"><img onclick="psi.checkPress(\'' + id + '\');" id="' + id + '" src="images/1x1.png" width="19" height="19" class="psi_check1">';
		}
		return r;
	},
	checkPring:function( id , name , block ) {
		document.write( this.check( id , name , block ) );
	},
	checkPress:function( id ) {
		if( $('#' + id).attr('class') == 'psi_check1' ) {
			$('#' + id).attr('class','psi_check1s');
			$('#psi_' + id).attr('value',1);
		}else{
			$('#' + id).attr('class','psi_check1');
			$('#psi_' + id).attr('value',0);
		}
	},
	radioPress:function( id , obj , last ) {
		//$(obj).attr('id')
		var i = 1;
		while( i <= this.radioNum[id] ) {
			$('#' + id + '_' + i).attr('class','psi_radio1');
			i++;
		}
		//if( $(obj).attr('class') == 'psi_radio1' ) {
			$(obj).attr('class','psi_radio1s');
			$('#psi_' + id).attr('value',$(obj).attr('valusitem'));
		//}else{
			//$(obj).attr('class','psi_radio1');
		//}
	},
	radioNum:{ },
	lastRadio:{ },
	radio:function( id , name , block , last , title ) {
		var r = '';
		if( last != null ) {
			this.lastRadio[id] = last;
			r += '<input type="hidden" name="psi_' + id + '" id="psi_' + id + '" value="1">';
		}
		if( this.radioNum[id] == undefined ) {
			this.radioNum[id] = 0;
		}
		this.radioNum[id]++;
		r += '<span onclick="psi.radioPress(\'' + id + '\',this,' + last + ');" valusitem="' + this.radioNum[id] + '" id="' + id + '_' + this.radioNum[id] + '" class="psi_radio1"><img src="/images/1x1.png" width="1" height="14"> &nbsp; &nbsp; &nbsp; &nbsp; ' + title + '</span>';
		return r;
	},
	radioPring:function( id , name , block , last , title ) {
		document.write( this.radio( id , name , block , last , title ) );
	}
};

/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);

$(function() {
 $('a.linktip').wrap('<span class="tip" />'); //оборачиваем соответствующие элементы в контейнер
  $('span.tip').each(function(){
       myTip = $(this),
       tipLink = myTip.children('a'),
       tBlock = myTip.children('span').length, //подсчитываем дочерние SPAN элементы внутри контейнера
       tTitle = tipLink.attr('title') != 0, //определяем наличие тега TITLE
       tipText = tipLink.attr('title'); //берем текст из тега TITLE

     tipLink.removeAttr("title"); //скрываем обычный TITLE
   //условие - если внутри нет доч. SPAN и есть TITLE,
   //добавляем соответствующий SPAN с текстом взятым из TITLE
     if(tBlock === 0 && tTitle === true){myTip.append('<span class="answer">' + tipText + '</span>')};

     var tip = myTip.find('span.answer , span.answer-left').hide(); //найдем и скроем блоки с подсказками

 //при наличии у ссылки тега EM подсказка будет появляется по клику
 //также сразу добавим и "крестик" закрытия
     tipLink.has('em').click(showTip).siblings('span').append('<b class="close">X</b>');

 //если тага EM нет, подсказка будет появляться при наведении курсора
    tipLink.not($('em').parent()).hoverIntent(
       showTip,
     function(){
       tip.fadeOut(200);}
    );
 //закрытие подсказки при клике на "крестик"
    tip.on('click', '.close', function(){
       tip.fadeOut(200);}
    );

 //функция вывода и появления подсказки на экран
 //вне зависимости от размеров окна,
 //наличия горизонтальной или вертикальной прокрутки
 //подсказка всегда будет в видимой области
    function showTip(e){
       xM = e.pageX,
       yM = e.pageY,
       tipW = tip.width(),
       tipH = tip.height(),
       winW = $(window).width(),
       winH = $(window).height(),
       scrollwinH = $(window).scrollTop(),
       scrollwinW = $(window).scrollLeft(),
       curwinH = $(window).scrollTop() + $(window).height();
    if ( xM > scrollwinW + tipW * 2 ) {tip.removeClass('answer').addClass('answer-left');}
       else {tip.removeClass('answer-left').addClass('answer');}
    if ( yM > scrollwinH + tipH && yM > curwinH / 2 ) {tip.addClass('a-top');}
       else {tip.removeClass('a-top');}
    tip.fadeIn(100).css('display','block');
   e.preventDefault();
   };
 });
});/*конец*/