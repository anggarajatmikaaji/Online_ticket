$(function(){
	$('.btn-cari').click(function(){
		var dept = $('#sel-dept').val();
		var dest = $('#sel-dest').val();
		var date = $('#tanggal').val();
		var send_data = {'departure': dept,'destination': dest,'date': date};
		if(dept!=''&&dest!=''&&date!=''){
			show_overlay();
			var request = $.ajax({
		    type: "POST",
		    url: base_url('ajax/check_departure'),
		    dataType: "json",
		    data: send_data,
		    timeout: 60000,
		    cache: false,
		    success: function(data){
		    	if(data.status=='success'){
		    		window.location.href = base_url('departure');
		    	}
		    	else{
		    		if(document.URL.match(/\/departure/gi)){
							var tdept = $('#sel-dept').find('option:selected').text();
							var tdest = $('#sel-dest').find('option:selected').text();
							$('#city-dept').html(tdept);
							$('#city-dest').html(tdest);
							$('#res-date').html(date);
							$('#res-result table tbody').empty().html('<tr><td></td><td colspan="8">Tidak ditemukan</td></tr>');
						}
		    		show_overlay('info',data.message);
		    	}
		    },
		    error: function(x,t,m){
		    	if(t=='timeout') show_overlay('timeout');
		    }
  		});
			button_close(request);
		}
		else{
			show_overlay('warning','Semua form harus diisi',true);
		}
	});

	$('#sel-dept').change(function(){
		$('#sel-dest').html('<option value=""><option>'
												+'<option value="" disabled>Mohon tunggu, sedang mengambil data ...</option>');
		$('#sel-dest').trigger('chosen:updated');
		var city_id = $(this).val();
		get_city_list(city_id);
	});

	button_close();

});

function button_close(req){
	$('.btn-cancel').click(function(){
		$('.overlay').fadeOut(100);
		if(typeof req != 'undefined')
			req.abort();
	})
}

function get_city_list(id){
	if(typeof id == 'undefined'){
		id = null;
		var elem = 'dept';
	}
	else{
		var elem = 'dest';
	}

	var send_data = {'city': id};

  $.ajax({
    type: "POST",
    url: base_url('ajax/request_city_list'),
    dataType: "json",
    data: send_data,
    timeout: 60000,
    cache: false,
    success: function(data){
    	var option = '<option value=""></option>';
    	if(data.status == 'success'){
	    	for (var key in data.cities) {
	    		if (data.cities.hasOwnProperty(key)) {
	    			option += '<optgroup label="'+key+'">';
		  			var data2 = data.cities[key];
		  			for(i=0,len=data2.length;i<len;i++) {
		  				option += '<option value="'+data2[i].id_kota+'">'+data2[i].nama_kota+'</option>';
		  			}
		  			option += '</optgroup>';
		  		}
				}
			}
			else{
				option += '<option value="" disabled>'+data.message+'</option>';
			}
			$('#sel-'+elem).html(option);
			$('#sel-'+elem).trigger('chosen:updated');
			if(elem=='dept'&&!document.URL.match(/\/departure/)){
				$('#sel-agent').html(option);
				$('#sel-agent optgroup:eq(0) option:eq(0)').attr('selected',true);
				$('#sel-agent').trigger('chosen:updated');
				get_agents();
			}
    },
    error: function(x,t,m){
    	if(t=='timeout') $('#sel-'+elem).html(m);
			$('#sel-'+elem).trigger('chosen:updated');
    }
  });
}

/*function request_seat(){
	$('#seat-container ul').html('<li>Mohon tunggu, sedang mengambil data ...</li>');
    
  $.ajax({
    type: "POST",
    url: base_url('ajax/request_seats'),
    dataType: "json",
    timeout: 60000,
    cache: false,
    success: function(data){
    	if(data.status=='success'){
	    	var num_row = data.right_seat+data.left_seat;
	    	var result = '';
	    	for(var i=1;i<=num_row;i++){
	    		var space = (i==data.right_seat) ? ' new-row' : '';
	    		var c = 1;
	    		var r = String.fromCharCode(64+i);
	    		for(var j=i;j<=data.num_seat;j+=num_row){
	    			var seat_no = r+c;
	    			var stat = ($.inArray(seat_no,data.booked)==-1) ? 'seat-ava' : 'seat-booked';
	    			var seat = '<li class="seat '+stat+space+'">'+seat_no+'</li>';
	    			result += seat;
	    			c++;
	    		}
	    		result += '<br>';
	    	}
	    	$('#seat-container ul').html(result);
	    	add_onclick();
    	}
    	else{
    		$('#seat-container ul').html('<li>'+data.message+'</li>');
    	}
    },
    error: function(x,t,m){
    	$('#seat-container ul').html('<li>'+m+'</li>');
    }
  });
}*/

function choose_ticket(id){
	show_overlay();
	var cls = $('#_'+id).find('td:nth-child(3)').html();
	var send_data = {'id': id, 'class': cls};
    
  var request = $.ajax({
    type: "POST",
    url: base_url('ajax/set_reservation'),
    dataType: "json",
    data: send_data,
    timeout: 60000,
    cache: false,
    success: function(data){
    	if(data.status=='success'){
	    	window.location.href = base_url('reservation/'+data.session);
	    }
	    else{
	    	show_overlay('info',data.message);
	    }
	  },
    error: function(x,t,m){
    	if(t=='timeout') show_overlay('timeout');
    }
  });
  button_close(request);
}

function show_overlay(header,message,show){
	if(typeof header=='undefined' && typeof message=='undefined'){
		$('.overlay-container div').html('<i class="fa fa-refresh fa-spin"></i>'
																		+'Mohon tunggu, permintaan sedang diproses ...');
		$('.btn-cancel').html('Batal');
		$('.overlay').fadeIn(100);
	}
	else{
		switch(header){
			case 'error':
				var icon='fa-warning'; var text='Kesalahan';
			break;
			case 'info':
				var icon='fa-info-circle'; var text='Informasi';
			break;
			case 'warning':
				var icon='fa-warning'; var text='Perhatian';
			break;
			case 'timeout':
				var icon='fa-warning'; var text='Kesalahan'; message = 'Respon server terlalu lama, silahkan coba lagi';
			break;
			default: 
				var icon='';var text='';
		}
		var head = '<h4><i class="fa '+icon+'"></i> '+text+'</h4>';
		$('.overlay-container div').html(head+message);
		$('.btn-cancel').html('Tutup').focus();
		if(typeof show!='undefined'&&show)
			$('.overlay').fadeIn(100);
	}
}