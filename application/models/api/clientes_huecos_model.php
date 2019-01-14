<?php 

require(APPPATH.'libraries/REST_Model.php');

class Clientes_Huecos_Model extends CI_Model {

	const TABLE = 'clientes_huecos';

	const ID = "hueco";

	const COLUMNS = array(
		'hueco'
	);

	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	function getAllHueco(){
		$this->db->select(implode(", ",self::COLUMNS));

		return $this->db->get(self::TABLE)->result();
	}

	function huecoExists($hueco) {
		if (!$hueco) return null;

		$this->db->select(self::ID);
		$this->db->where(self::ID, $hueco); 

		$row = $this->db->get(self::TABLE, 1)->row();

		return (count($row));
	}

	function insert($hueco) {
		if (!$hueco) return false;

		if (isset($hueco[0]) && is_array($hueco[0]))
			$this->db->insert_batch(self::TABLE, $hueco);
		else	
			$this->db->insert(self::TABLE, $hueco);
		
		return $this->db->affected_rows();
	}

	function delete($hueco) {
		if (!count($hueco)) return false;

		$this->db->where_in(self::ID, $hueco);
		$this->db->delete(self::TABLE);

		return $this->db->affected_rows();
	}

}