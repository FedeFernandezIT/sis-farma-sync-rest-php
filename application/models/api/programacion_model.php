<?php 

require(APPPATH.'libraries/REST_Model.php');

class Programacion_Model extends CI_Model {

	const TABLE = 'programacion_sincronizador';

	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	function getEncendido() {
						
		$this->db->where(array (
			'LOWER(estado)'	=> strtolower('encendido'),
			'activo'		=> 1));			

		$row = $this->db->get(self::TABLE)->row();

		return count($row) ? $row : null;
	}
	
	function getApagado() {
						
		$this->db->where(array (
			'LOWER(estado)'	=> strtolower('apagado'),
			'activo'		=> 1));			

		$row = $this->db->get(self::TABLE)->row();

		return count($row) ? $row : null;
	}	
}