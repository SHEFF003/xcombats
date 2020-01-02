var ni = {
	reting_select:function(id) {
		var i = 1;
		while( i <= 4 ) {
			$('.reting_m'+i+'sel').addClass('reting_m'+i+'');
			$('.reting_m'+i+'sel').removeClass('reting_m'+i+'sel');
			$('#reting_l'+i).hide();
			i++;
		}
		$('.reting_m'+id+'').addClass('reting_m'+id+'sel');
		$('.reting_m'+id+'').removeClass('reting_m'+id+'');
		$('#reting_l'+id).show();
	}
};