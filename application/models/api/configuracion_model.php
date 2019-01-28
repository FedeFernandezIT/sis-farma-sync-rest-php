<?php 

require(APPPATH.'libraries/REST_Model.php');

class Configuracion_Model extends CI_Model {

	const TABLE = 'configuracion';

	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	function getValorByColumn($column = array()) {

		$this->db->select('valor');
		
		if (count($column))
			$this->db->where($column); 

		$row = $this->db->get(self::TABLE)->row();

		return count($row) ? $row->valor : null;
	}
	
	function setData($data = array(), $where = array()) {		
		$this->db->update(self::TABLE, $data, $where);		
	}
	
	function esFarmazul() {     
		$databaseCurrent =  $this->db->database;
		if ($databaseCurrent == 'fisiotes_grupofarmazul' || $databaseCurrent == 'fisiotes_demofarmazul')
			return true;
		else {                              
			$sql = "SHOW DATABASES LIKE 'fisiotes_grupofarmazul';";

			$query = $this->db->query($sql);

			if ($query->num_rows() == 0) 
				return false;
			else {
				$sql = "SELECT * FROM fisiotes_grupofarmazul.dbdisponibles WHERE nombre = '".str_replace('fisiotes_', '', $databaseCurrent)."'";
				 
				$query = $this->db->query($sql);

				if ($query->num_rows() > 0)
					return true;
				else
					return false;
			}
		}
	}

}