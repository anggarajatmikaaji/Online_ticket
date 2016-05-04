<div class="invoice-form">
	<div>
		<table cellpadding="2px">
			<tr>
				<td width="30px" rowspan="2">[ML]</td>
				<td width="330px" style="font-weight: bolder;font-size: 15px">PO Maju Lancar</td>
			</tr>
			<tr style="line-height: 22px">
				<td style="font-size: 8px;">Jl Jogja-Wonosari km 3 Yogyakarta, DI Yogyakarta, Indonesia (0274-443101)</td>
			</tr>
		</table>
		<hr width="360px">
	</div>
	<div>
		<span style="font-size: 20px"></span>
		<strong style="font-size: 12px">Bukti Pemesanan Tiket</strong> (Bukan Tiket)
		<div>
			<br>
			<table cellpadding="2px">
				<tr>
					<td style="line-height: 14px" width="100px">No. Pesanan</td>
					<td style="line-height: 14px" width="10px">:</td>
					<td style="font-size: 13px;font-weight: bolder" width="155px"><?= $booking->id_pemesan;?></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>:</td>
					<td><?= format_datetime($booking->waktu);?></td>
				</tr>
				<?php
					//$expired = date('Y-m-d H:i:s',strtotime('-3 hours',strtotime($departure->tanggal)));
				?>
				<tr>
					<td>Batas Masa Berlaku</td>
					<td>:</td>
					<td><?= format_datetime($booking->batas);?></td>
				</tr>
			</table>
		</div>
		<div>
			<strong>Informasi Pemesan</strong>
			<br><span style="font-size: 14px"></span>
			<table cellpadding="2px">
				<tr>
					<td width="100px">Nama</td>
					<td width="10px">:</td>
					<td width="245px"><?= $booking->nama;?></td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td>:</td>
					<td><?= $booking->alamat;?></td>
				</tr>
				<tr>
					<td>Telepon</td>
					<td>:</td>
					<td><?= $booking->telp;?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td>:</td>
					<td><?= $booking->email;?></td>
				</tr>
			</table>
		</div>
		<div>
			<?php
				$penumpang = explode(',',$booking->penumpang);
			?>
			<strong>Informasi Penumpang</strong>
			<br><span style="font-size: 14px"></span>
			<table cellpadding="2px">
				<tr>
					<th style="border-bottom: 1px solid black" width="25px">No</th>
					<th style="border-bottom: 1px solid black" width="220px">Nama</th>
					<th style="border-bottom: 1px solid black" width="50px">No Kursi</th>
				</tr>
				<?php
					$i = 1;
					foreach ($penumpang as $seatn) {
						$seat = explode('|',$seatn);
						?>
						<tr>
							<th><?= $i; ?></th>
							<th><?= $seat[0];?></th>
							<th><?= $seat[1];?></th>
						</tr>
						<?php
						$i++;
					}
				?>
			</table>
		</div>
		<div>
			<strong>Informasi Keberangkatan</strong>
			<br><span style="font-size: 14px"></span>
			<table cellpadding="2px">
				<tr>
					<td width="100px">Trayek</td>
					<td width="10px">:</td>
					<td width="245px"><?= $departure->kota_asal.' - '.$departure->kota_tujuan;?></td>
				</tr>
				<?php
					$tanggal = date('Y-m-d', strtotime($departure->tanggal));
				?>
				<tr>
					<td>Tanggal</td>
					<td>:</td>
					<td><?= format_date($tanggal);?></td>
				</tr>
				<tr>
					<td>Kode Keberangkatan</td>
					<td>:</td>
					<td><?= $departure->id_keberangkatan;?></td>
				</tr>
				<tr>
					<td>Kelas</td>
					<td>:</td>
					<td><?= $bus->kelas_bus;?></td>
				</tr>
				<?php
					$locations = '';
					if($departure->lokasi!=''){
						$lokasi = explode(',',$departure->lokasi);
						foreach($lokasi as $loc){
							$bag = explode('|',$loc);
							$jam = substr($bag[1],0,5);
							$locations .= $bag[0].' (Jam '.$jam.')<br>'; 
						}
						$locations = rtrim($locations,'<br>');
					}
				?>
				<tr>
					<td>Lokasi Berangkat</td>
					<td>:</td>
					<td><?= $locations; ?></td>
				</tr>
			</table>
		</div>
		<div>
			<strong>Rincian Biaya</strong>
			<br><span style="font-size: 14px"></span>
			<?php
				$npass = count($penumpang);
				$total = $departure->tarif * $npass;
				$bayar = $total - ($total*$departure->diskon/100);
			?>
			<table cellpadding="2px">
				<tr>
					<td width="100px">Total Biaya</td>
					<td width="10px">:</td>
					<td width="100px">
						Rp <?= number_format($departure->tarif); ?> &nbsp;&nbsp;x&nbsp;&nbsp; <?= $npass;?>
					</td>
					<td width="80px" align="right">Rp <?= number_format($total);?></td>
				</tr>
				<tr>
					<td>Diskon</td>
					<td>:</td>
					<td style="border-bottom: 1px solid black"></td>
					<td align="right" style="border-bottom: 1px solid black"><?= $departure->diskon;?>%</td>
				</tr>
				<tr>
					<td>Pembayaran</td>
					<td>:</td>
					<td></td>
					<td align="right" style="font-weight: bolder">Rp <?= number_format($bayar); ?></td>
				</tr>
			</table>
		</div>
		<div>
			<strong>Keterangan</strong>
			<ul style="font-size: 8px;line-height: 10px">
				<li style="line-height: 14px">Form ini hanya berlaku sebagai bukti pemesanan tiket, bukan sebagai tiket.</li>
				<li style="line-height: 14px">Lakukan pembayaran melalui agen Maju Lancar terdekat sesuai dengan <br>harga yang tertera di atas dengan menunjukan form ini.</li>
				<li style="line-height: 14px">Pemesanan dianggap hangus apabila sampai batas masa berlaku tidak <br>dilakukan pembayaran.</li>
			</ul>
		</div>
	</div>
</div>
