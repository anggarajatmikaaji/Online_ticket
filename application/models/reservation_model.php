<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reservation_model extends CI_Model {
  private $table_name = 'keberangkatan';
  
  function __construct() {
    parent::__construct();
  }

	function get_bus_seats($id_berangkat){
		return $this->db->query("SELECT a.id_keberangkatan,c.jumlah_kursi,c.seat,
														GROUP_CONCAT(IF((NOW() < e.batas OR d.status='1'),d.no_kursi,NULL) SEPARATOR ',') AS kursi_terpesan 
														FROM keberangkatan AS a LEFT JOIN bus AS b ON a.id_bus=b.id_bus 
														LEFT JOIN tipe_bus AS c ON b.id_tipe=c.id_tipe 
														LEFT JOIN tiket AS d ON d.id_keberangkatan=a.id_keberangkatan 
														LEFT JOIN pemesan AS e ON d.id_pemesan=e.id_pemesan 
														WHERE a.id_keberangkatan='$id_berangkat'");
	}

	function get_departure_city(){
		return $this->db->query("SELECT a.id_kota,a.nama_kota,a.provinsi FROM kota AS a 
														 INNER JOIN trayek AS b ON a.id_kota=b.id_kota_asal 
														 GROUP BY a.id_kota ORDER BY provinsi,nama_kota");
	}

	function get_destination_city($city){
		return $this->db->query("SELECT a.id_kota,a.nama_kota,a.provinsi FROM kota AS a 
														INNER JOIN trayek AS b ON a.id_kota=b.id_kota_tujuan 
														WHERE b.id_kota_asal='$city' 
														GROUP BY a.id_kota ORDER BY provinsi,nama_kota");
	}

	function check_bus_departure($dept,$dest,$date){
		return $this->db->query("SELECT COUNT(a.id_keberangkatan) AS tersedia 
														FROM keberangkatan AS a 
														LEFT JOIN trayek AS b ON a.id_trayek=b.id_trayek 
														LEFT JOIN bus AS c ON a.id_bus=c.id_bus 
														LEFT JOIN tipe_bus AS d ON d.id_tipe=c.id_tipe 
														WHERE a.tanggal='$date' AND b.id_kota_asal='$dept' 
														AND b.id_kota_tujuan='$dest'");
	}

	function get_data_agents($id_city){
		return $this->db->query("SELECT nama_agen,alamat,no_telp,email FROM agen WHERE id_kota='$id_city'");
	}

	function get_all_departure($dept,$dest,$date){
		return $this->db->query("SELECT a.id_keberangkatan,a.tarif,a.diskon,d.kelas_bus,d.fasilitas,
														GROUP_CONCAT(CONCAT_WS('|',e.alamat,e.jam) SEPARATOR ',') AS lokasi 
														FROM keberangkatan AS a LEFT JOIN trayek AS b ON a.id_trayek=b.id_trayek 
														LEFT JOIN bus AS c ON a.id_bus=c.id_bus 
														LEFT JOIN tipe_bus AS d ON d.id_tipe=c.id_tipe 
														LEFT JOIN lokasi_keberangkatan AS e ON a.id_keberangkatan=e.id_keberangkatan 
														WHERE a.tanggal='$date' AND b.id_kota_asal='$dept' 
														AND b.id_kota_tujuan='$dest' GROUP BY a.id_keberangkatan");
	}

	function get_departure_detail($id_dept){
		return $this->db->query("SELECT a.id_keberangkatan,CONCAT_WS(' ',a.tanggal,e.jam) AS tanggal,
														c.nama_kota AS kota_asal,c.provinsi AS provinsi_asal,
														d.nama_kota AS kota_tujuan,d.provinsi AS provinsi_tujuan,a.tarif,a.diskon,
														GROUP_CONCAT(CONCAT_WS('|',e.alamat,e.jam) SEPARATOR ',') AS lokasi 
														FROM keberangkatan AS a LEFT JOIN trayek AS b ON a.id_trayek=b.id_trayek 
														LEFT JOIN kota AS c ON c.id_kota=b.id_kota_asal 
														LEFT JOIN kota AS d ON b.id_kota_tujuan=d.id_kota 
														LEFT JOIN lokasi_keberangkatan AS e ON a.id_keberangkatan=e.id_keberangkatan 
														WHERE a.id_keberangkatan='$id_dept' ORDER BY e.jam ASC");
	}

	function get_trayek_detail($dept,$dest){
		return $this->db->query("SELECT id_trayek,b.nama_kota AS kota_asal,b.provinsi AS provinsi_asal,
														c.nama_kota AS kota_tujuan,c.provinsi AS provinsi_tujuan FROM trayek AS a 
														LEFT JOIN kota AS b ON a.id_kota_asal=b.id_kota
														LEFT JOIN kota AS c ON a.id_kota_tujuan=c.id_kota 
														WHERE a.id_kota_asal='$dept' AND a.id_kota_tujuan='$dest'");
	}

	function get_city_list(){
		return $this->db->query("SELECT a.id_trayek,a.id_kota_asal,b.nama_kota AS nama_kota_asal,
														b.provinsi AS provinsi_asal,a.id_kota_tujuan,c.nama_kota AS nama_kota_tujuan,
														c.provinsi AS provinsi_tujuan FROM trayek AS a 
														LEFT JOIN kota AS b ON a.id_kota_asal=b.id_kota 
														LEFT JOIN kota AS c ON a.id_kota_tujuan=c.id_kota");
	}

	function get_bus_by_dept($id_dept){
		return $this->db->query("SELECT a.id_bus,c.kelas_bus,c.ukuran_bus,c.jumlah_kursi,c.fasilitas FROM bus AS a 
														LEFT JOIN keberangkatan AS b ON a.id_bus=b.id_bus 
														LEFT JOIN tipe_bus AS c ON a.id_tipe=c.id_tipe 
														WHERE b.id_keberangkatan='$id_dept'");
	}

	function get_booking_detail($id){
		return $this->db->query("SELECT a.id_pemesan,a.nama,a.alamat,a.telp,a.email,a.waktu,a.batas,b.id_keberangkatan,
														GROUP_CONCAT(CONCAT_WS('|',b.nama_kursi,b.no_kursi) SEPARATOR ',') AS penumpang 
														FROM pemesan AS a LEFT JOIN tiket AS b ON a.id_pemesan=b.id_pemesan 
														WHERE a.id_pemesan='$id'");
	}

	function get_seat_reserved($id_dept){
		return $this->db->query("SELECT no_kursi FROM tiket AS a INNER JOIN pemesan AS b ON a.id_pemesan=b.id_pemesan 
														WHERE id_keberangkatan='$id_dept' AND (NOW() < b.batas OR a.status='1')");
	}

	function insert_customer($customer){
		$this->db->insert('pemesan',$customer);
		if($this->db->affected_rows() == 1){
			return str_pad($this->db->insert_id(), 8, '0', STR_PAD_LEFT);
		}
		else{
			return false;
		}
	}

	function insert_passengers($passenger){
		$this->db->insert_batch('tiket',$passenger);
		return $this->db->affected_rows() > 0;
	}
}