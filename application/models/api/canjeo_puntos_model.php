<?php 

require(APPPATH.'libraries/REST_Model.php');

class Canjeo_Puntos_Model extends CI_Model {

	const TABLE = 'canjeo_puntos';

	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function getTotalPuntosCanjeados($dni) {
		if (!$dni) return null;

		$this->db->select('SUM(ABS(puntosCanjear)) AS totalPuntosCanjeados', false );
		$this->db->where("dniCliente", $dni);

		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row->totalPuntosCanjeados) ? $row->totalPuntosCanjeados : 0;
	}

}