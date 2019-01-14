<?php 

require(APPPATH.'libraries/REST_Model.php');

class Medicamentos_Model extends CI_Model {

	const TABLE = 'medicamentos';

	const ID = "cod_nacional";

	const COLUMNS = array(
		'cod_nacional',
		'cod_barras',
		'nombre',
		'familia',
		'precio',
		'descripcion',
		'laboratorio',
		'pvpSinIva',
		'iva',
		'stock',
		'puc'
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

	public function exists($hueco) {
		if (!$hueco) return null;

		$this->db->select(self::ID);
		$this->db->where(self::ID, $hueco); 

		$row = $this->db->get(self::TABLE, 1)->row();

		return (count($row));
	}

	public function setData($data = array(), $codNacional = null, $insertIfNotExist = false){
		if (!$codNacional) return false;

		$this->db->select(self::ID);
		$this->db->where(self::ID, $codNacional);

		if ($this->db->get(self::TABLE, 1)->num_rows()){
			$this->db->update(self::TABLE, 
				$data, 
				array(self::ID => $codNacional));
			return $this->db->affected_rows();
		}
		if ($insertIfNotExist){
			$this->db->insert(self::TABLE, $data);
			return $this->db->affected_rows();
		}

		return 0;
	}
	
	public function reset($data = array()) {
		$this->db->update(self::TABLE, $data);
	}

	function delete($medicamento) {
		if (!count($medicamento)) return false;

		$this->db->where_in(self::ID, $medicamento);
		$this->db->delete(self::TABLE);

		return $this->db->affected_rows();
	}

}