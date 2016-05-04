<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('template');
		$this->load->library('session');
		$this->load->model('reservation_model','',TRUE);
	}

	public function index() {
		$this->template->homepage(NULL);
	}

  public function departure() {
  	$search = $this->session->userdata('search');
    if($search==NULL){ header("Location: ".base_url()); exit;}
    $dept = $search['id_city_dept'];
    $dest = $search['id_city_dest'];
    $date = $search['date'];
  	$query = $this->reservation_model->get_all_departure($dept,$dest,$date);
  	$data['departure_data'] = array();
  	foreach($query->result() as $row){
  		$lokasi = explode(',',$row->lokasi);
  		$tarif_akhir = round($row->tarif-($row->tarif*$row->diskon/100));
			$data['departure_data'][] = array('id_keberangkatan'=>$row->id_keberangkatan,
																		'tarif'=>$row->tarif,'diskon'=>$row->diskon,'kelas'=>$row->kelas_bus,
																		'tarif_akhir'=>$tarif_akhir,'fasilitas'=>$row->fasilitas,'lokasi'=>$lokasi); 
  	}
  	$query = $this->reservation_model->get_trayek_detail($dept,$dest);
  	foreach ($query->result() as $row) {
  		$data['departure'] = $row->kota_asal;
  		$data['destination'] = $row->kota_tujuan;
  	}
    $this->load->helper('date');
  	$data['date'] = ($date!=NULL) ? format_date($date) : NULL;
    $this->template->page('departure',$data);
  }

  public function reservation($session) {
    if($session!=$this->session->userdata('session')){ header("Location: ".base_url()); exit;}
    $id = $this->session->userdata('departure')['id'];
    if($id!=NULL){
      $query = $this->reservation_model->get_bus_seats($id);
      foreach ($query->result() as $row) {
        if($row->id_keberangkatan!=NULL){
          $name = '';$address='';$phone='';$email='';
          $seat_type = explode('-',$row->seat);
          $reserved = explode(',',$row->kursi_terpesan);
          $customer = $this->session->userdata('customer');
          if($customer!=NULL){
            $name = $customer['nama'];
            $address = $customer['alamat'];
            $phone = $customer['telp'];
            $email = $customer['email'];
          }
          $this->load->helper('date');
          $dept = $this->session->userdata('search')['id_city_dept'];
          $dest = $this->session->userdata('search')['id_city_dest'];
          $class = $this->session->userdata('departure')['class'];
          $query2 = $this->reservation_model->get_departure_detail($id);

          $data['seats'] = array('right_seat'=>intval($seat_type[0]),'left_seat'=>intval($seat_type[1]),
                          'num_seat'=>intval($row->jumlah_kursi),'reserved'=>$reserved);
          $data['customer'] = array('name'=>$name,'address'=>$address,'phone'=>$phone,'email'=>$email);
          $data['departure'] = $query2->result()[0];
          $data['class'] = $class;
          $data['date'] = format_date($this->session->userdata('search')['date']);
        }
        else {
          $data['seats'] = NULL;
        }
      }
    }
    else{
      header("Location: ".base_url()); exit;
    }
    $this->template->page('reservation',$data);
  }

  public function success(){
    $invoice = $this->session->userdata('invoice');
    if($invoice){ 
      $this->load->helper('date');
      $this->template->success($invoice);
    }
    else{
      header("Location: ".base_url()); exit;
    }
  }

  public function invoice($id=NULL,$key=NULL) {
    $this->load->helper('key_id');
    if(key_id($id)===$key) {
      $this->load->library('pdf');
      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $pdf->SetProtection(array('modify', 'copy'), '', null, 0, null);
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor('PO Maju Lancar');
      $pdf->SetTitle('Bukti Pemesanan Tiket');
      $pdf->SetSubject('Bukti Pemesanan');

      $pdf->SetFont('dejavusans', '', 9, '', true); 
      $pdf->SetPrintHeader(false);
      $pdf->setPrintFooter(false);
      $pdf->SetMargins(10, 5, 10);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->AddPage();

      $this->load->helper('date');
      $data['booking'] = $this->reservation_model->get_booking_detail($id)->result()[0];
      $id_dept = $data['booking']->id_keberangkatan;
      $data['departure'] = $this->reservation_model->get_departure_detail($id_dept)->result()[0];
      $data['bus'] = $this->reservation_model->get_bus_by_dept($id_dept)->result()[0];
      $html = $this->load->view('invoice_form',$data,TRUE);
      $pdf->writeHTML($html, true, false, true, false, '');

      $style = array('border'=>0,'vpadding'=>'auto','hpadding'=>'auto','fgcolor'=>array(0,0,0),'bgcolor'=>false,
                    'module_width'=>1,'module_height'=>1);

      $pdf->write2DBarcode($id, 'QRCODE,H', 100, 29, 35, 35, $style, 'N');
      $pdf->SetFont('helvetica', '', 8);
      $pdf->Text(10,268,date('Y/m/d/H:i:s'));
      $filename = 'bukti_pemesanan_'.$id.'.pdf';
      $pdf->Output($filename, 'I');
    }
    else {
      echo 'Error::Gagal otentikasi kunci';
    }    
  }
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */