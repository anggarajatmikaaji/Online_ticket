<div id="res-window" class="flow">
	<!--<div class="res-head">
		<ul>
			<li id="res-head-tiket" class="res-here"><a href="#">Pesan Tiket</a></li>
			<li id="res-head-jadwal"><a href="#">Lihat Jadwal</a></li>
		</ul>
	</div>-->
	<div class="res-head-2">Pesan Tiket</div>
	<div class="res-body">
		<div id="res-body-tiket">
			<div class="res-field" id="field-dari">
				<label>Dari</label><br />
				<select id="sel-dept" data-placeholder="Kota asal" class="res-select" tabindex="5">
					<option value=""></option>
					<option value="" disabled>Mohon tunggu, sedang mengambil data ...</option>
				</select>
			</div>
			<div class="res-field" id="field-ke">
				<label>Ke</label><br />
				<select id="sel-dest" data-placeholder="Kota tujuan" class="res-select" tabindex="5">
					<option value=""></option>
					<option value="" disabled>Mohon pilih kota asal dulu</option>
				</select>
			</div>
			<div class="res-field" id="field-tanggal">
				<label>Tanggal Berangkat</label><br /><input type="text" class="res-input datepick" id="tanggal"/>
				<i class="fa fa-calendar" id="cal-icon1"></i>
			</div>
			<!--<div class="res-field" id="field-tanggal2">
				<label>Sampai Tanggal</label><br /><input type="text" class="res-input datepick" id="tanggal2"/>
				<i class="fa fa-calendar" id="cal-icon2"></i>
			</div>-->
			<button class="btn btn-cari"><i class="fa fa-search"></i>Cari Tiket</button>
			<!--<button class="btn btn-lihat"><i class="fa fa-list-alt"></i>Lihat Jadwal</button>-->
		</div>
	</div>
</div>

<div id="home-container">
	<div class="home-content" id="welcome">
		<h4 style="font-weight: lighter;font-size: 24px;">Selamat Datang di PO Maju Lancar</h4>
		<p style="margin-top: 10px;">
			Terima kasih atas kepercayaan dalam menggunakan Maju Lancar sebagai sarana terpercaya dalam menyediakan transportasi bis di Indonesia.
			Kami melayani pembelian tiket secara online untuk jurusan ke Sumatra-Jawa-Bali.
		</p>
	</div>
	<div class="home-content" id="profil-div">
		<!--<div class="home-icon"><span id="profil-icon" class="icon-container"><i class="fa fa-bus"></i></span></div>-->
		<h4>
			<i class="fa fa-quote-left"></i>
			Kami adalah perusahaan angkutan bis yang melayani kebutuhan transportasi umum, penyewaan bis pariwisata, 
			angkutan karyawan dan menyediakan jasa pengantaran cargo/paket.
			<i class="fa fa-quote-right"></i>
		</h4>
		<div class="home-icon tip" data-tip="<b>Alamat Kantor</b>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>">
			<span id="profil2-icon" class="icon-container"><i class="fa fa-building"></i></span>
		</div>
		<div class="home-icon tip" data-tip="<b>Jenis Bus</b>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>">
			<span id="profil-icon" class="icon-container"><i class="fa fa-bus"></i></span>
		</div>
		<div class="home-icon tip" data-tip="<b>Visi dan Misi</b>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>">
			<span id="profil3-icon" class="icon-container"><i class="fa fa-sitemap"></i></span>
		</div>
	</div><br /><br />
	<div class="home-content" id="agent-div">
		<h2>Informasi Agen</h2>
	</div>
	<div class="home-content" id="content-agent-div">
		<div id="agent-select" style="width: 300px">
			<label>Agen di kota : </label>
			<select id="sel-agent" class="res-select" tabindex="5" onchange="get_agents()">
				<option value=""></option>
				<option value="" disabled>Mohon tunggu, sedang mengambil data ...</option>
			</select>
		</div>
		<div id="agent-result">
			<table></table>
		</div>
	</div><br /><br />
	<div class="home-content" id="contact-div">
		<p>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
		</p>
	</div><br /><br />
</div>