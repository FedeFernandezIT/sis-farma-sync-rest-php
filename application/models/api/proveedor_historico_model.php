<?php 

require(APPPATH.'libraries/REST_Model.php');

class Proveedor_Historico_Model extends CI_Model {

	const TABLE = 'historico_proveedores';

	const ID = "id";

	const COLUMNS = array(
	);

	private $limit = 1;
	private $order;

	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function getMaxByFecha() {		
		$this->db->select_max('fecha', 'fecha');		
		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row) ? $row : null;
	}
	
	public function insert($data = array()){
		$this->db->insert(self::TABLE, $data);
		return $this->db->affected_rows();
	}
		
}