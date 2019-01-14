<?php 

require(APPPATH.'libraries/REST_Model.php');

class Lista_Model extends CI_Model {

	const TABLE = 'listas';

	const ID = "cod";

	const COLUMNS = array(
		'cod'
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

	public function setData($data = array(), $cod = null, $insertIfNotExist = false){
		if (!$cod) return false;

		$this->db->select(self::ID);
		$this->db->where(self::ID, $cod);

		if ($this->db->get(self::TABLE, 1)->num_rows()){
			$this->db->update(self::TABLE, 
				$data, 
				array(self::ID => $cod));
			return $this->db->affected_rows();
		}
		if ($insertIfNotExist){
			$this->db->insert(self::TABLE, $data);
			return $this->db->affected_rows();
		}

		return 0;
	}

	public function updateData($data, $where = null){
		if (is_array($where))
			$this->db->update(self::TABLE, 
				$data, 
				$where);
		else
			$this->db->update(self::TABLE, 
				$data);

		return $this->db->affected_rows();
	}

	function delete($ids) {
		if (!count($ids)) return false;

		$this->db->where_in(self::ID, $ids);
		$this->db->delete(self::TABLE);

		return $this->db->affected_rows();
	}

}