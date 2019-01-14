<?php 

require(APPPATH.'libraries/REST_Model.php');

class Configuracion_Model extends CI_Model {

	const TABLE = 'configuracion';

	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	function getValorByColumn($column = array()) {

		$this->db->select('valor');
		
		if (count($column))
			$this->db->where($column); 

		$row = $this->db->get(self::TABLE)->row();

		return count($row) ? $row->valor : null;
	}
	
	function setData($data = array(), $where = array()) {		
		$this->db->update(self::TABLE, $data, $where);		
	}

}