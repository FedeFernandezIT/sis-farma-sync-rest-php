<?php 

require(APPPATH.'libraries/REST_Model.php');

class Pedido_Model extends CI_Model {

	const TABLE = 'pedidos';

	const ID = "idPedido";

	const COLUMNS = array(
		'idPedido'
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

	// public function count($idPedido){
		// $this->db->from(self::TABLE);
		// $this->db->where(self::ID, $idPedido); 
		// return $this->db->count_all_results();
	// }
	
	public function truncate() {
		$this->db->truncate(self::TABLE);
	}

	public function delete($ids) {
		if (!count($ids)) return false;

		$this->db->where_in(self::ID, $ids);
		$this->db->delete(self::TABLE);

		return $this->db->affected_rows();
	}
	
	public function count(){
		return (int)$this->db->count_all(self::TABLE);
	}
	
	public function getLast(){		
		$this->db->select_max(self::ID);		
		$row = $this->db->get(self::TABLE, 1)->row();

		return ($row->idPedido) ? $row : null;		
	}
}