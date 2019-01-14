<?php defined('BASEPATH') or exit('No direct script access allowed');

abstract class REST_Model extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->load->config('rest');
		
		
		$user = $this->config->item('rest_db_name');

		$users = $this->config->item('rest_user_db');
		if ( !array_key_exists($user, $users) || empty($users[$user])){
			$database = 'default';
		}

		$database = $users[$user];
                
		$this->load->database($database);
	}
}