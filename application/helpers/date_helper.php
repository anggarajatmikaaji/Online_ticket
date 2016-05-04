<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('convert_date')) {
	function convert_date($date){
		$months = array('Januari'=>'01','Februari'=>'02','Maret'=>'03','April'=>'04','Mei'=>'05','Juni'=>'06',
								'Juli'=>'07','Agustus'=>'08','September'=>'09','Oktober'=>'10','November'=>'11','Desember'=>'12');
	
		$exp = explode(" ", $date);
		if(array_key_exists($exp[1], $months)) {
			return $exp[2].'-'.$months[$exp[1]].'-'.$exp[0];
		}
		else {
			return false;
		}
	}
}

if ( ! function_exists('format_date')) {
	function format_date($date){
  	$months = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
									'07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
  	$days = array('0'=>'Minggu','1'=>'Senin','2'=>'Selasa','3'=>'Rabu','4'=>'Kamis','5'=>'Jumat','6'=>'Sabtu');
  	$exp = explode('-',$date);
  	$day = date('w',strtotime($date));
  	$result = $days[$day].', '.$exp[2].' '.$months[$exp[1]].' '.$exp[0];
  	return $result;
  }
}

if ( ! function_exists('format_datetime')) {
	function format_datetime($datetime){
  	$months = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
									'07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
  	$date = explode(' ',$datetime);
  	$exp = explode('-',$date[0]);
  	$result = $exp[2].' '.$months[$exp[1]].' '.$exp[0].' '.$date[1];
  	return $result;
  }
}