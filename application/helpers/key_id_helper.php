<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('key_id')) {
	function key_id($id){
		$id = strrev(strval($id));
    $salt = 'mJuL@nC4r';
    $key = md5($salt.$id);
    return $key;
	}
}