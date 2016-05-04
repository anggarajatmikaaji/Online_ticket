<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
	protected $_ci;

	function __construct(){
		$this->_ci =& get_instance();
	}

	function homepage($data=NULL){
		$data['_header'] = $this->_ci->load->view('template/header',$data,TRUE);
		$data['_head'] = $this->_ci->load->view('template/head/homepage',$data,TRUE);
		$data['_content'] = $this->_ci->load->view('homepage',$data,TRUE);
		$data['_script'] = $this->_ci->load->view('template/script/homepage',NULL,TRUE);
		$data['_footer'] = $this->_ci->load->view('template/footer',$data,TRUE);
		$data['_slider'] = $this->_ci->load->view('template/slider',NULL,TRUE);
		$this->_ci->load->view('template',$data);
	}

	function page($container,$data=NULL){
		$data['_header'] = $this->_ci->load->view('template/header',$data,TRUE);
		$data['_head'] = $this->_ci->load->view('template/head/reservation',$data,TRUE);
		$data['_content'] = $this->_ci->load->view($container,$data,TRUE);
		$data['_script'] = $this->_ci->load->view('template/script/reservation',NULL,TRUE);
		$data['_footer'] = $this->_ci->load->view('template/footer',$data,TRUE);
		$this->_ci->load->view('template',$data);
	}

	function success($data){
		$data['_header'] = $this->_ci->load->view('template/header',$data,TRUE);
		$data['_head'] = $this->_ci->load->view('template/head/success',$data,TRUE);
		$data['_content'] = $this->_ci->load->view('success',$data,TRUE);
		$data['_script'] = NULL;
		$data['_footer'] = $this->_ci->load->view('template/footer',$data,TRUE);
		$this->_ci->load->view('template',$data);
	}
}