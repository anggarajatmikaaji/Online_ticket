<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	private $delay = 1;
	public function __construct() {
		parent::__construct();
		//if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$this->load->helper('url');
		$this->load->model('reservation_model','',TRUE);
		$this->load->library('session');
	}

	public function index() {
		//exit('No access allowed');
		print_r($this->session->all_userdata());
	}

	public function request_city_list() {
		if($this->input->post('city')==NULL){
			$lokasi = 'asal';
			$query = $this->reservation_model->get_departure_city();
		}
		else{
			$lokasi = 'tujuan';
			$query = $this->reservation_model->get_destination_city($this->input->post('city',TRUE));
		}
		if($query->num_rows()>0){
			$data = array('status'=>'success','cities'=>array());
			foreach($query->result() as $row) {
				$data['cities'][$row->provinsi][] = array('id_kota'=>$row->id_kota,'nama_kota'=>$row->nama_kota);
			}
		}
		else{
			$data = array('status'=>'failed','message'=>'Kota '.$lokasi.' tidak ditemukan');
		}
		echo json_encode($data);
	}

	public function get_agents() {
		$id_city = $this->input->post('id_city',TRUE);
		$query = $this->reservation_model->get_data_agents($id_city);
		if($query->num_rows()>0){
			$data = array('status'=>'success','agents'=>$query->result_array());
		}
		else{
			$data = array('status'=>'failed','message'=>'Data agen tidak ditemukan');
		}
		echo json_encode($data);
	}

	public function check_departure() {
		$dept = $this->input->post('departure',TRUE);
		$dest = $this->input->post('destination',TRUE);
		$date = $this->input->post('date',TRUE);
		if($dept!=NULL&&$dest!=NULL&&$date!=NULL) {
			$this->load->helper('date');
			$date = convert_date($date);
			$query = $this->reservation_model->check_bus_departure($dept,$dest,$date);
			$result = $query->result();
			if($result[0]->tersedia > 0){
				$new_session = array('search'=>array('id_city_dept'=>$dept,'id_city_dest'=>$dest,'date'=>$date));
				$this->session->set_userdata($new_session);
				$data = array('status'=>'success');
			}
			else{
				$data = array('status'=>'failed','message'=>'Jadwal keberangkatan tidak ditemukan');
			}
		}
		else{
			$data = array('status'=>'failed','message'=>'Form tidak boleh kosong');
		}
		sleep($this->delay); //test
		echo json_encode($data);
	}

	public function set_reservation(){
		$id = $this->input->post('id',TRUE);
		$class = $this->input->post('class',TRUE);
		if($id!=NULL){
			$ip = intval(preg_replace("/[^0-9]/", '', $this->input->ip_address()));
			$session = md5(intval((strtotime(date('Y-m-d H:i:s'))+$ip)/rand(1,99)));
			$new_session = array('departure'=>array('id'=>$id,'class'=>$class),'session'=>$session);
			$this->session->set_userdata($new_session);
			$this->session->unset_userdata('customer');
			$data = array('status'=>'success','session'=>$session);
		}
		else{
			$data = array('status'=>'failed','message'=>'Gagal memproses permintaan, silakan coba lagi');
		}
		sleep($this->delay); //test
		echo json_encode($data);
	}

	public function save_seats($session=NULL){
		if($session===$this->session->userdata('session')){
			$seats = $this->input->post('seats',TRUE);
			$num_seats = count($seats);
			if($num_seats<=5){
				$new_session = array('seats'=>$seats);
				$this->session->set_userdata($new_session);
				$data = array('status'=>'success','data'=>NULL,'link'=>FALSE);
			}
			else{
				$data = array('status'=>'failed','message'=>'Jumlah kursi melebihi batas');
			}
		}
		else{
			$data = array('status'=>'failed','message'=>'Otentikasi <i>session</i> gagal, silakan coba lagi');
		}
		sleep($this->delay); //test
		echo json_encode($data);
	}

	public function save_customer($session=NULL){
		if($session===$this->session->userdata('session')){
			$name = $this->input->post('name',TRUE);
			$address = $this->input->post('address',TRUE);
			$phone = $this->input->post('phone',TRUE);
			$email = $this->input->post('mail',TRUE);
			$seats = $this->input->post('seats_name',TRUE);
			if($name!=NULL&&$address!=NULL&&$phone!=NULL&&$email!=NULL&&$seats!=NULL){
				if(count($seats)<=5){
					ksort($seats);
					preg_match('/\d+/',$phone,$telp);
					$new_session = array('customer'=>array('nama'=>$name,'alamat'=>$address,
															'telp'=>$telp[0],'email'=>$email),'seats'=>$seats);
					$this->session->set_userdata($new_session);
					$data = array('status'=>'success','data'=>$new_session,'link'=>FALSE);
				}
				else{
					$data = array('status'=>'failed','message'=>'Jumlah Kursi melebihi batas');
				}
			}
			else{
				$data = array('status'=>'failed','message'=>'Data tidak boleh kosong');
			}
		}
		else{
			$data = array('status'=>'failed','message'=>'Otentikasi <i>session</i> gagal, silakan coba lagi');
		}
		sleep($this->delay); //test
		echo json_encode($data);
	}

	public function save_reservation($session=NULL){
		if($session===$this->session->userdata('session')){
			$this->load->helper('key_id');
			$id_dept = $this->session->userdata('departure')['id'];
			$seats = $this->session->userdata('seats');
			$customer = $this->session->userdata('customer');
			$booked = array_map('current',$seats);

			$query = $this->reservation_model->get_seat_reserved($id_dept);
			$reserved = array_map('current',$query->result_array());

			$check = array_intersect($booked,$reserved);

			if(count($check)==0){
				$date = date('Y-m-d H:i:s');
				$limit = date('Y-m-d H:i:s',strtotime("+5 hours",strtotime($date)));
				$customer['waktu'] = $date;
				$customer['batas'] = $limit;
				$query = $this->reservation_model->insert_customer($customer);
				$id_cust = $query;
				if($id_cust!=false){
					$passengers = [];
					foreach($seats as $seat){
						$passengers[] = ['id_pemesan'=>$id_cust,'id_keberangkatan'=>$id_dept,'id_agen'=>'',
													'no_kursi'=>$seat['no'],'nama_kursi'=>$seat['nama'],'status'=>'0'];
					}
					$query = $this->reservation_model->insert_passengers($passengers);
					if($query){
						$link = base_url('invoice/'.$id_cust.'/'.key_id($id_cust));

						$unset = array('search'=>'','departure'=>'','session'=>'','seats'=>'','customer'=>'');
						$this->session->unset_userdata($unset);
						$invoice = array('invoice'=>array('link'=>$link,'limit'=>$limit));
						$this->session->sess_expiration = 300;
						$this->session->set_userdata($invoice);

						//S:Test SMS
						$this->load->library('smsGateway');
						$sms = new SmsGateway('ibe.eleven@gmail.com', 'ikhsan11');
						$deviceID = 8680;
						$number = $customer['telp'];
						$message = 'Terima kasih, pemesanan tiket anda berhasil diproses. Cetak bukti pemesanan : '.$link;
						$options = ['send_at' => strtotime('+0 seconds'),
						    'expires_at' => strtotime('+10 minutes')
						    ];
						$result = $sms->sendMessageToNumber($number, $message, $deviceID, $options);
						//E:Test SMS*/

						$data = array('status'=>'success','data'=>NULL);
					}
					else{
						$data = array('status'=>'failed','message'=>'Gagal menyimpan penumpang');
					}
				}
				else{
					$data = array('status'=>'failed','message'=>'Gagal menyimpan data pemesan');
				}
			}
			else{
				$fail = implode(', ',$check);
				$data = array('status'=>'failed','message'=>'Pemesanan gagal, kursi nomor <b>'.$fail.'</b> lebih dahulu terisi.'.
												'<br style="line-height:30px">Silakan pilih kursi kembali','reserved_seats'=>$reserved);
			}
		}
		else{
			$data = array('status'=>'failed','message'=>'Otentikasi <i>session</i> gagal, silakan coba lagi');
		}
		sleep($this->delay); //test
		echo json_encode($data);
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */