<?php 

require(APPPATH.'libraries/REST_Model.php');

class Sinonimos_Model extends CI_Model {

	const TABLE = 'sinonimos';

	const ID = "cod_barras";

	const COLUMNS = array(
		'cod_barras'
	);

	private $limit = 1;
	private $order;

	function __construct() {
		parent::__construct();
		$this->load->database();
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

	public function insert($data = array()){
		$this->db->insert(self::TABLE, $data);
		return $this->db->affected_rows();
	}

	public function count(){
		return (int)$this->db->count_all(self::TABLE);
	}
	
	public function truncate() {
		$this->db->truncate(self::TABLE);
	}

}