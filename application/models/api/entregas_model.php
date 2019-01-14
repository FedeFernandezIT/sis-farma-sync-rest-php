<?php 

require(APPPATH.'libraries/REST_Model.php');

class Entregas_Model extends CI_Model {

	const TABLE = 'entregas_clientes';
	const ID = 'cod';
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function getWhere($where = array()) {
		if (!$where) return null;
		
		if (is_array($where))
			$this->db->where($where); 
		else
			$this->db->where(self::ID, $where); 

		$row = $this->db->get(self::TABLE)->result();

		return count($row) ? $row : null;
	}
	
	function insert($data) {
		$this->db->insert(self::TABLE, $data);
	}
	
	function setData($data = array(), $where = array()) {		
		$this->db->update(self::TABLE, $data, $where);		
	}

}