<div id="side-indicator">
	<ul>
		<li><a href="#" class="current-indic" title="Pilih Keberangkatan">1</a></li>
  	<li><a href="#" title="Pilih Kursi">2</a></li>
  	<li><a href="#" title="Isi Identitas">3</a></li>
  	<li><a href="#" title="Konfirmasi">4</a></li>
	</ul>
</div>
    
    <!--S:Reservasi 1-->
<div id="panel-1">
	<div id="hasil-cari">
		<div>
			<b>Hasil Pencarian : &nbsp; </b><span>
			<em id="city-dept"><?= $departure; ?></em> ke 
			<em id="city-dest"><?= $destination; ?></em> &nbsp;&nbsp;|&nbsp;&nbsp;
			<em id="res-date"><?= $date; ?></em></span>
		</div>
		<a id="btn-res-window" href="#">Ganti pencarian</a>
	</div>
	<div id="res-form-2">
		<div class="res-field" id="field-ke">
			<label>Dari</label><br />
			<select id="sel-dept" data-placeholder="Kota asal" class="res-select" tabindex="5">
				<option value=""></option>
				<option value="" disabled>Mohon tunggu, sedang mengambil data ...</option>
			</select>
		</div>
		<div class="res-field" id="field-ke">
			<label>Ke</label><br />
			<select id="sel-dest" data-placeholder="Kota asal" class="res-select" tabindex="5">
				<option value=""></option>
				<option value="" disabled>Mohon pilih kota asal dulu</option>
			</select>
		</div>
		<div class="res-field" id="field-tanggal">
			<label>Tanggal Berangkat</label><input type="text" class="res-input datepick" id="tanggal"/>
			<i class="fa fa-calendar cal-icon"></i>
		</div>
		<button class="btn btn-cari"><i class="fa fa-search"></i>Cari Tiket</button>
	</div>
	<div id="res-result">
		<table class="footable" data-page-size="8">
			<thead>
				<tr>
					<th data-sort-ignore="true"></th>
					<th data-sort-ignore="true">Kode</th>
					<th>Kelas</th>
					<th>Harga Tiket</th>
					<th>Diskon</th>
					<th>Harga Akhir</th>
					<th data-sort-ignore="true">Fasilitas</th>
					<th data-sort-ignore="true">Lokasi Berangkat</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($departure_data as $row){
						$fasilitas = str_replace(',', ', ', $row['fasilitas']);
						$id = $row['id_keberangkatan'];
					?>
					<tr id="_<?= $id;?>" style="cursor:pointer;" onclick="choose_ticket('<?= $id;?>')">
						<td><a href="#" onclick="choose_ticket('<?= $id;?>')" class="pesan-icon">Pesan</a></td>
						<td><?= $id; ?></td>
						<td><?= $row['kelas']; ?></td>
						<td><?= number_format($row['tarif']); ?></td>
						<td><?= $row['diskon']; ?>%</td>
						<td><?= number_format($row['tarif_akhir']); ?></td>
						<td><?= $fasilitas; ?></td>
						<td class="td-location">
						<?php
							foreach($row['lokasi'] as $lks){
								$exp = explode('|',$lks);
								$loc = (strlen($exp[0])>18) ? substr($exp[0], 0, 18).'...' : $exp[0];
								if(count($exp)>1)
									$time = substr($exp[1], 0, 5);
								else
									$time = '';
								echo '<div title="'.$exp[0].' pukul '.$time.'">'.$loc.'<span>'.$time.'</span></div>';
							}
						?>
						</td>
					</tr>
					<?php
					}
				?>
			</tbody>
			<tfoot class="hide-if-no-paging">
				<tr>
					<td colspan="8">
						<div class="pagination pagination-centered"></div>
				</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<!--E:Reservasi 1-->