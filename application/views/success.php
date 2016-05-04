<div class="success-container">
	<div class="bg-container">
		<img class="success-bg" src="<?= base_url('public/img/example-slide-1.jpg');?>">
	</div>
	<div class="success-head">
		<p><i class="fa fa-check"></i> <strong>Terima kasih</strong>, pesanan anda berhasil diproses</p>
	</div>
	<div class="success-ket">
		<a class="invoice-link" href="<?= $link; ?>">Cetak Bukti Pemesanan</a>
		<h4>Catatan : </h4>
		<?php
			$lim = explode(' ',$limit);
		?>
		<ul>
			<li>Silakan lakukan konfirmasi dan pembayaran dengan menyerahkan bukti pemesanan di agen 
				Maju Lancar terdekat sebelum <strong><?= format_date($lim[0]).' pukul '.$lim[1];?></strong></li>
			<li>Bukti pemesanan di atas tidak berlaku sebagai tiket.</li>
			<li>Informasi kontak dan agen dapat anda lihat dengan mengunjungi alamat 
				<a href="<?= base_url('#agent');?>">berikut</a>.</li>
		</ul>
	</div>
</div>