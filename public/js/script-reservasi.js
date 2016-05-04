var url = document.URL;
var session = String(url.match(/\/[0-9a-zA-Z]{30,}/));
session = session.replace(/[^0-9a-zA-Z]/g,'');

$(function(){
	$('a[href="#"]').click(function(event){
		event.preventDefault();
	});
	
	$('.footable').footable();
	
	$('.res-select').chosen({
		width: '300px'
	});
	
	$('.datepick').keydown(false);
	
	$('.datepick').datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0, 
		maxDate: "+2M"
	});    
			
	$('.datepick').datepicker("option", "duration", 50).datepicker("option", "dateFormat","dd MM yy");
	
	var indic = 2;
	
	$('a.gotopanel').click(function () {
		var type = $(this).attr('id');	
		if(type=='next'){
			if(indic<5){
				var send_data='';var url='';var is_updated = false;
				switch(indic){
					case 2:
						if(selectedSeats.length==0){ show_overlay('warning','Anda belum memilih kursi',true); return false; }
						send_data = {'seats': selectedSeats};
						url = 'save_seats/'+session;
					break;
					case 3:
						var name = $('#buyer_name');
						var address = $('#address');
						var phone = $('#phone');
						var mail = $('#mail');
						var seats_name = new Array();
						var seat_valid = true;
						for(i=0,len=selectedSeats.length;i<len;i++){
							var seat = selectedSeats[i];
							var seat_name = $('#seat_name_'+seat); 
							if(seat_name.val()!=''){
								var obj = {};
								obj['no'] = seat;
								obj['nama'] = seat_name.val();
								seats_name.push(obj);
							}
							else{
								seat_valid = false;
								input_warning(seat_name,'Tidak boleh kosong');
							}
						}
						if(name.val()!=''&&address.val()!=''&&is_phone(phone.val())&&is_mail(mail.val())&&seat_valid){	
							send_data = {'name': name.val(),'address': address.val(),
													'phone': phone.val(),'mail': mail.val(),'seats_name': seats_name};
							url = 'save_customer/'+session;
						}
						else{
							show_overlay('warning','Mohon cek kembali data anda',true);
							validate_input(name,address,phone,mail);
							return false;
						}
					break;
					case 4:
						if($('#agree').is(":checked")){
							url = 'save_reservation/'+session;
						}
						else{
							show_overlay('warning','Anda belum <i>mencetang</i> persetujuan',true);
							return false;
						}
						updated[4] = true;
					break;
					case 5: 
						console.log('Lima');
					break;
				}
				is_updated = updated[indic];
				if(is_updated){
					show_overlay();
					var request = $.ajax({
	    			type: "POST",
	    			url: base_url('ajax/'+url),
				    dataType: "json",
				    data: send_data,
				    cache: false,
				    timeout: 60000,
				    context: this,
				    success: function(data){
				    	if(data.status=='success'){
				    		if(indic==4){
				    			window.location.href = base_url('success');
				    			return false;
				    		}
				    		$('.overlay').fadeOut(100);
				    		update_panel(indic,data.data);
				    		updated[indic] = false;
					    	indic = slide_panel($(this),'next',indic);
				    	}
				    	else{
				    		show_overlay('error',data.message);
				    		if(typeof data.reserved_seats!='undefined'){
				    			update_seats(data.reserved_seats);
				    			indic = slide_panel('','prev',3);
				    			$('#panel-slider').scrollTo($('#panel-2'), 400);
				    		}
				    	}
				    },
				    error: function(x,t,m){
				    	if(t=='timeout') show_overlay('timeout');
				    }
				  });
			  	button_close(request);
			  }
			  else{
					indic = slide_panel($(this),'next',indic);
			  }
			}
		}
		else{
			indic = slide_panel($(this),'prev',indic);
		}
		return false;
	});
	
	var isload = false;
	$('#btn-res-window').click(function(){
		if(!isload) {get_city_list();isload=true;}
		$('#res-form-2').slideToggle(450);
	});

	$('.seat-ava,.seat-sel').click(function(){		
		var seat_no = $(this).html();
		var index = $.inArray(seat_no,selectedSeats);
		if(index==-1&&selectedSeats.length<5){
			selectedSeats.push(seat_no);
			$(this).addClass('seat-sel').removeClass('seat-ava');
		}
		else if(index!=-1){
			selectedSeats.splice(index,1);
			$(this).addClass('seat-ava').removeClass('seat-sel');
		}
		else{
			show_overlay('warning','Jumlah kursi anda sudah melebihi batas',true);
		}
		updated[2] = true;
		update_info();
	});

	identity_onkeyup();

});

function update_info(){
	var price = parseInt($('#harga').html().replace(/[^0-9]/g,''));
	var diskon = parseInt($('#diskon').html());
	var jumlah = selectedSeats.length;
	var total = price*jumlah;
	var bayar = total-(total*diskon/100);
	$('#total').html(format_number(total));
	$('#bayar').html(format_number(bayar));
	$('#kursi').html(jumlah);
	$('#kursi_sel').html(selectedSeats.join(', '));
}

function update_seats(data){
	$('.seat-reserved').addClass('seat-ava');
	$('.seat-reserved').removeClass('seat-reserved');
	for(i=0,len=data.length;i<len;i++){
		$('#seat_'+data[i]).removeClass('seat-sel');
		$('#seat_'+data[i]).addClass('seat-reserved');
		var index = $.inArray(data[i],selectedSeats);
		if(index!=-1){
			$('input#seat_name_'+data[i]).parent().remove();
			selectedSeats.splice(index,1);
		}
	}
	update_info();
}

function format_number(number){
	return number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace(/\.00/,'');
}

function validate_input(name,address,phone,mail){
	if(name.val()=='') input_warning(name,'Nama tidak boleh kosong');
	if(address.val()=='') input_warning(address,'Alamat tidak boleh kosong');
	if(phone.val()=='') input_warning(phone,'Telepon tidak boleh kosong');
	else if(!is_phone(phone.val())) input_warning(phone,'Nomor telepon tidak valid');
	if(mail.val()=='') input_warning(mail,'E-mail tidak boleh kosong');
	else if(!is_mail(mail.val())) input_warning(mail,'Email tidak valid');
}

function is_phone(number){
	var digit = number.match(/\d/g);
	if(number==''||!digit) return false;
	if(digit.length>=10&&digit.length<=13){
		return true;
	}
	else{
		return false;
	}
}

function is_mail(mail){
	var reg = /^(([^<>()[\]\,;:\s@\"]+(\.[^<>()[\]\,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return reg.test(mail);
}

function input_warning(elem,message){
	elem.next('span').remove();
	elem.after('<span><i class="fa fa-warning"></i>'+message+'</span>');
}

function identity_onkeyup(){
	$('#panel-3 input,#panel-3 textarea').keyup(function(){
		updated[3] = true;
		$(this).next('span').remove();
	});
	$('#seat-name input:last').keydown(function (e) {
    if (e.which == 9) e.preventDefault();
  });
}

function update_panel(indic,data){
	switch(indic){
		case 2:
			selectedSeats = selectedSeats.sort();
			var len = selectedSeats.length; 
			if(len<=5){
				var input = '';
				for(var i=0;i<len;i++){
					var sname = selectedSeats[i];
					input += '<div><label>Nomor '+sname
										+'</label><input type="text" id="seat_name_'
										+sname+'" maxlength="30"></div>';
				}
				$('#seat-name').html(input);
				$('#customer-id').find('span').remove();
				identity_onkeyup();
			}
		break;
		case 3:
			var cus = data.customer;
			var res = '<tr><td>Nama</td><td>:</td><td>'+cus.nama+'</td></tr>'
								+'<tr><td>Alamat</td><td>:</td><td>'+cus.alamat+'</td></tr>'
								+'<tr><td>Telepon</td><td>:</td><td>'+cus.telp+'</td></tr>'
								+'<tr><td>Email</td><td>:</td><td>'+cus.email+'</td></tr>';
			$('#info-customer table').html(res);
			$('#info-customer').fadeIn(100);
			$('#next').html('Konfirmasi');
		break;
	}
}

function slide_panel(elem,type,indic){
	$('a.gotopanel').removeClass('selected');
	$(elem).addClass('selected');
	$('#panel-slider').scrollTo($(elem).attr('href'), 600);
	if(type=='next'){
		$('#side-indicator ul li:nth-child('+indic+') a').removeClass('current-indic').addClass('success-indic');
		indic++;
		$('#side-indicator ul li:nth-child('+indic+') a').addClass('current-indic');
		if(indic<5)
			$('#next').attr('href','#panel-'+(indic+1));
		$('#prev').attr('href','#panel-'+(indic-1));
		if(indic==4){
			$('#next').html('Konfirmasi');
		}
	}
	else if(type=='prev'){
		$('#side-indicator ul li:nth-child('+indic+') a').removeClass('current-indic');
		indic--;
		$('#side-indicator ul li:nth-child('+indic+') a').removeClass('success-indic').addClass('current-indic');
		if(indic==2)
			$('#prev').attr('href',base_url('page/departure'));
		else
			$('#prev').attr('href','#panel-'+(indic-1));
		$('#next').attr('href','#panel-'+(indic+1));
		$('#next').html('Selanjutnya');
	}
	return indic;
}

var selectedSeats = new Array();
var updated = [];
updated[2] = false;
updated[3] = false;