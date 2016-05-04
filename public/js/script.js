$(function(){
	$('a[href="#"]').click(function(event){
		event.preventDefault();
	});

	$('.res-select').chosen({
		width: '100%'
	});

	get_city_list();

	$('#slides').responsiveSlides({
		auto: true,
		speed: 1500,
		timeout: 8000,
		pager: true,
		nav: false,
		random: false,
		pause: false,
		manualControls: "",
		pauseControls: true
	});

	$('#cal-icon1').click(function(){
		if($('#ui-datepicker-div').is(':visible')){
			$('#tanggal').blur();  
		}
		else{
			$('#tanggal').focus();
		}
	});

	$('#cal-icon2').click(function(){
		if($('#ui-datepicker-div').is(':visible')){
			$('#tanggal2').blur();  
		}
		else{
			$('#tanggal2').focus();
		}
	});

	$('.datepick').keydown(false);

	$('.datepick').datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0, 
		maxDate: "+2M"
	});    
		
	$('.datepick').datepicker("option", "duration", 50).datepicker("option", "dateFormat","dd MM yy");

	$('.tip').tipr({
		'mode': 'top'
	});

	/*$('#res-head-tiket').click(function(){
		$('.res-head ul li').removeClass('res-here');
		$(this).addClass('res-here');
		$('#field-tanggal label').html('Tanggal Berangkat');
		$('#field-tanggal2').hide();
		$('#tanggal,#tanggal2').val('');
		$('.btn-lihat').hide();
		$('.btn-cari').show();
	});

	$('#res-head-jadwal').click(function(){
		$('.res-head ul li').removeClass('res-here');
		$(this).addClass('res-here');
		$('#field-tanggal label').html('Dari Tanggal');
		$('#field-tanggal2').show();
		$('#tanggal').val('');
		$('.btn-lihat').show();
		$('.btn-cari').hide();
	});*/

	$(window).scroll(function(){
		//var pos = $('#container').offset().top - $(window).scrollTop();
		var pos = $(window).scrollTop();
		/*if(pos < 320){
			$('#res-window').fadeOut(500); 
		}
		else{
			$('#res-window').fadeIn(500);
		}*/
		if(pos == 0){
			$('.caption').show(200);
			$('#res-window').fadeIn(0);
			$('#welcome').fadeIn(0);
		}
		else if (pos >= 150 && pos <= 200){
			$('.caption').hide(800);
			$('#res-window').fadeOut(500);
		}
		else{
			$('#welcome').fadeOut(0);
		}
	});

	$('#nav-cari').click(function(){
		action_nav('#search');
		return false;
	});

	$('#nav-profil').click(function(){
		action_nav('#profil');
		return false;
	});

	$('#nav-agen').click(function(){
		action_nav('#agent');
		return false;
	});

	$('#nav-kontak').click(function(){
		action_nav('#contact');
		return false;
	});

	var url = document.URL;
	var elem = url.match(/\#[a-z]+/g);
	if(elem){
		$('#welcome').hide(0);
		action_nav(elem);
	}
	
});

function action_nav(elem){
	if(elem=='#search'){
		$('html, body').animate({
        scrollTop: 0
    }, 500);
    $('#res-window').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
	}
	else if(elem=='#profil'){
		if($('#welcome').is(':visible')){
			$('#welcome').hide(0);
			$('.caption').hide(800);
		}
		$('html, body').animate({
        scrollTop: $("#profil-div").offset().top-360
    }, 800);
    $('#res-window').fadeOut(500); 
	}
	else if(elem=='#agent'){
		$('html, body').animate({
        scrollTop: $("#agent-div").offset().top-65
    }, 800);
	}
	else if(elem=='#contact'){
		$('html, body').animate({
        scrollTop: $("#contact-div").offset().top-65
    }, 800);
	}
}

function get_agents(){
	var city = $('#sel-agent').val();
	$('#agent-result table').html('<tr><td><i class="fa fa-spinner fa-spin"></i></td></tr>');
	$.ajax({
		type: "POST",
		url: base_url('ajax/get_agents'),
    dataType: "json",
    data: {'id_city': city},
    cache: false,
    timeout: 60000,
    success: function(data){
    	if(data.status=='success'){
    		var result = '';
    		var j = 1;
    		for(i=0,len=data.agents.length;i<len;i++){
    			var agen = data.agents[i];
    			var td = '<td><b>'+agen.nama_agen+'</b><br>'+agen.alamat+'<br>'+agen.no_telp+'<br>'+agen.email+'</td>';
    			if(j==1){
    				td = '<tr>'+td;
    			}
    			j++;
    			if(j==4||i==(len-1)){
    				td += '</tr>';
    				j = 1;
    			}
    			result += td;
    		}
    		$('#agent-result table').html(result);
    	}
    	else{
    		$('#agent-result table').html('<tr><td>'+data.message+'</td></tr>');
    	}
    }
  });
}