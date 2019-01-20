<?php 

require(APPPATH.'libraries/REST_Model.php');

class Categoria_Model extends CI_Model {

	const TABLE = 'ps_categorias';

	const ID = "id";

	const COLUMNS = array(
		"categoria",
		"padre",
		"prestashopPadreId"
	);

	private $limit = 1;
	private $order;

	function __construct() {
		parent::__construct();
		$this->load->database();
	}


	function getLast() {
		$this->db->select_max(self::ID, self::ID);
		$row = $this->db->get(self::TABLE, 1)->row();
		
		return count($row) ? $row->{self::ID} : null;
	}

	function getMaxIdLineaByPedido($idEncargo) {
		$this->db->select_max('idLinea', 'idLinea');
		$this->db->where('idEncargo', $idEncargo); 

		$row = $this->db->get(self::TABLE, 1)->row();
		
		return count($row) ? $row->idLinea : 1;
	}

	public function setOrder($order) {	
		if (in_array(strtolower($order), array('asc', 'desc')))
			$this->order = $order;
		return $this;
	}

	public function setLimit($limit = 1) {
		if (!is_numeric($limit)) return $this;
		$this->limit = (int)$limit;
		return $this;
	}

	public function getWhere($where) {
		if (!$where) return null;

		$this->db->select(implode(", ",self::COLUMNS));

		if (is_array($where))
			$this->db->where($where); 
		else
			$this->db->where(self::ID, $where); 

		if ($this->order)
			$this->db->order_by(self::ID, $this->order); 

		if ($this->limit == 1)
			$row = $this->db->get(self::TABLE, $this->limit)->row();
		else
			$row = $this->db->get(self::TABLE, $this->limit)->result();

		return count($row) ? $row : null;
	}

	public function insertData($data = array()){
		$this->db->insert(self::TABLE, $data);
		return $this->db->affected_rows();
	}

	function delete($medicamento) {
		if (!count($medicamento)) return false;

		$this->db->where_in(self::ID, $medicamento);
		$this->db->delete(self::TABLE);

		return $this->db->affected_rows();
	}

}