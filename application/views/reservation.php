<div id="side-indicator">
	<ul>
		<li><a href="#" class="success-indic" title="Pilih Keberangkatan">1</a></li>
		<li><a href="#" class="current-indic" title="Pilih Kursi">2</a></li>
		<li><a href="#" title="Isi Identitas">3</a></li>
		<li><a href="#" title="Konfirmasi">4</a></li>
	</ul>
</div>

<!--S:Reservasi 2-->
<div id="panel-reservasi">
	<div id="panel-slider">
		<div id="mask">
			<div class="panel" id="panel-2">
				<a name="panel-2"></a>
				<div class="panel-container">
					<div class="panel-header">Pilih Kursi</div>
					<div class="keterangan">
						<i class="fa fa-info-circle"></i>
						Silahkan pilih tempat duduk. Maksimal 5 tempat sekali transaksi.
					</div>
					<div id="seat-container">
						<ul>
						<?php 
						if($seats==NULL){ 
							echo '<li>Data tidak ditemukan</li>';
						}
						else {
	    				$num_row = $seats['right_seat']+$seats['left_seat'];
	    				for($i=1;$i<=$num_row;$i++){
	    					$space = ($i==$seats['right_seat']) ? ' new-row' : '';
	    					$c = 1;
	    					$r = chr(64+$i);
	    					for($j=$i;$j<=$seats['num_seat'];$j+=$num_row){
	    						$seat_no = $r.$c;
	    						$stat = (in_array($seat_no, $seats['reserved'])) ? 'seat-reserved' : 'seat-ava';
	    						echo '<li id="seat_'.$seat_no.'" class="seat '.$stat.$space.'">'.$seat_no.'</li>';
	    						$c++;
	    					}
	    					echo '<br/>';
	    				}
						}
						?>
						</ul>
					</div>
					<div class="seat-label">
						<ul>
							<li class="seat seat-ava seat-dis"></li><span>Kursi tersedia</span>
							<li class="seat seat-reserved"></li><span>Kursi terpesan</span>
						</ul>
					</div>
				</div>
			</div>
			<div class="panel" id="panel-3">
				<a name="panel-3"></a>
				<div class="panel-container">
					<div class="panel-header">Identitas Anda</div>
					<div class="keterangan"><i class="fa fa-info-circle"></i>Isi sesuai identitas anda yang sebenarnya</div>
					<div id="id-container">
						<h4>Identitas Pemesan</h4>
						<div class="identity" id="customer-id">
							<div>
								<label>Nama</label><input type="text" id="buyer_name" value="<?=$customer['name'];?>" maxlength="30">
							</div>
							<div>
								<label>Alamat</label><textarea id="address" maxlength="65"><?=$customer['address'];?></textarea>
							</div>
							<div>
								<label>Telepon</label><input type="text" id="phone" value="<?=$customer['phone'];?>">
							</div>
							<div>
								<label>Email</label><input type="text" id="mail" value="<?=$customer['email'];?>">
							</div>
						</div>
						<h4>Nama Kursi</h4>
						<div class="identity" id="seat-name">
						</div>
					</div>
				</div>
			</div>
			<div class="panel" id="panel-4">
				<a name="panel-4"></a>
				<div class="panel-container">
					<div class="panel-header">Konfirmasi</div>
					<div class="keterangan"><i class="fa fa-info-circle"></i>
							Terima kasih, mohon lakukan konfirmasi sesuai ketentuan berikut.
					</div>
					<div id="confirm-container">
						Ketentuan : 
						<ol>
							<li>Pihak pemesan menyatakan bahwa informasi disebelah ini adalah benar dan bersedia melakukan 
									pembayaran sesuai dengan yang tertera.
							</li>
							<li>
								Pembayaran dapat dilakukan melalui seluruh agen terdaftar dengan menunjukkan bukti pemesanan.
							</li>
							<li>
								Batas pembayaran adalah 1 hari sebelum keberangkatan atau 3 jam sebelum keberangkatan untuk pemesanan dihari yang sama.
							</li>
							<li>
								Apabila sampai batas tersebut pembayaran tidak dilakukan, maka pemesanan dianggap hangus.
							</li>
							<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
									sed do eiusmod tempor.</li>
						</ol>
						<input type="checkbox" id="agree" value="setuju">&nbsp; <b>Saya menyetujui ketentuan tersebut.</b>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="side-info">
		<div id="info-header"><i class="fa fa-shopping-cart"></i>&nbsp;<strong>Informasi Pemesanan</strong></div>
		<div id="info-container">
			<div id="info-city">
				<h4>Keberangkatan</h4>
				<div><label>Kota Asal</label>: <?= $departure->kota_asal.', '.$departure->provinsi_asal;?></div>
				<div><label>Kota Tujuan</label>: <?= $departure->kota_tujuan.', '.$departure->provinsi_tujuan;?></div>
				<div><label>Tgl Berangkat</label>: <?= $date;?></div>
				<div><label>ID Keberangkatan</label>: <?= $departure->id_keberangkatan;?> (<?= $class;?>)</div>
				<div><label>Harga Tiket</label>: Rp <span id="harga"><?= number_format($departure->tarif);?></span></div>
			</div>
			<div id="info-customer">
				<h4>Pemesan</h4>
				<table></table>
			</div>
			<div id="info-total">
				<h4>Biaya</h4>
				<div><label>Kursi</label>: <span id="kursi_sel"></span> &nbsp;(<span id="kursi">0</span>)</div>
				<div><label>Total harga</label>: Rp <span id="total">0</span></div>
				<div><label>Diskon</label>: <span id="diskon"><?= $departure->diskon;?></span>%</div>
				<div><label>Pembayaran</label>: Rp <span id="bayar">0</span></div>
			</div>
		</div>
	</div>
</div>
<div id="side-control">
	<a href="<?= base_url('page/departure') ;?>" id="prev" class="btn btn-prev gotopanel">Kembali</a>
	<a href="#panel-3" id="next" class="btn btn-next gotopanel">Selanjutnya</a>
</div>