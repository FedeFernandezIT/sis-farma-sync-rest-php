<?php 

require(APPPATH.'libraries/REST_Model.php');

class Familia_Model extends CI_Model {

	const TABLE = 'familia';

	const ID = "cod";

	const COLUMNS = array(
		'cod',
		'familia',
		'puntos',
		'nivel1',
		'nivel2',
		'nivel3',
		'nivel4'
	);

	private $limit = 1;
	private $order;

	function __construct() {
		parent::__construct();
		$this->load->database();
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

	public function getWhere($where, $customWhere = false) {
		if (!$where) return null;

		$this->db->select(implode(", ",self::COLUMNS));

		if (is_array($where))
			$this->db->where($where); 
		else
			$this->db->where(self::ID, $where); 

		if ($customWhere){
			$this->db->where($customWhere);
		}

		if ($this->order)
			$this->db->order_by(self::ID, $this->order); 

		if ($this->limit == 1)
			$row = $this->db->get(self::TABLE, $this->limit)->row();
		else
			$row = $this->db->get(self::TABLE, $this->limit)->result();

		return count($row) ? $row : null;
	}

	public function exists($hueco) {
		if (!$hueco) return null;

		$this->db->select(self::ID);
		$this->db->where(self::ID, $hueco); 

		$row = $this->db->get(self::TABLE, 1)->row();

		return (count($row));
	}

	public function setData($data = array(), $familia = null, $insertIfNotExist = false){
		if (!$familia) return false;

		$this->db->select(self::ID);
		$this->db->where("familia", $familia);

		if ($this->db->get(self::TABLE, 1)->num_rows()){
			$this->db->update(self::TABLE, 
				$data, 
				array("familia" => $familia));
			return $this->db->affected_rows();
		}
		if ($insertIfNotExist){
			$this->db->insert(self::TABLE, $data);
			return $this->db->affected_rows();
		}

		return 0;
	}

	function delete($medicamento) {
		if (!count($medicamento)) return false;

		$this->db->where_in(self::ID, $medicamento);
		$this->db->delete(self::TABLE);

		return $this->db->affected_rows();
	}
	
	function hasColumnTipo() {
		$sqlQuery = "SHOW COLUMNS FROM familia LIKE 'tipo'";
		$query = $this->db->query($sqlQuery);
		$rows = $query->result_array();
		return count($rows) ? true : false;		
	}
}