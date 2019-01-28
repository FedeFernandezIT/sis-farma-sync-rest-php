<?php 

require(APPPATH.'libraries/REST_Model.php');

class Pendiente_Puntos_Model extends CI_Model {

	const TABLE = 'pendiente_puntos';

	const ID = "idventa";

	const COLUMNS = array(
	);

	private $limit = 1;
	private $order;

	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function countLineaByIdVenta($idVenta) {
		if (!$idVenta) return null;

		$this->db->select("count(idnlinea) AS numLineas");
		$this->db->where(self::ID, $idVenta); 
		$row = $this->db->get(self::TABLE)->row();

		return count($row) ? $row->numLineas : 0;
	}

	// TODO: en VB se consultaba por recetaPendiente
	function getNoActualizados() {
		$this->db->distinct();
		$this->db->select(self::ID);
		$this->db->where(array(
			'actualizado'			=> '0',
			'YEAR(fechaVenta) >='	=> '2016'
		)); 
		$this->db->get(self::TABLE, 1000)->result();

		return $this->db->get(self::TABLE)->result();
	}
	
	//TODO: se agrego ger por redencion
	function getSinRedencion() {
		$this->db->where(array(
			'redencion'				=> null,
			'YEAR(fechaVenta) >='	=> '2015'
		));
		
		return $this->db->get(self::TABLE)->result();		
	}
	
	
	function getTicketCero(){
		$this->db->distinct();
		$this->db->select(self::ID);
		$this->db->where(array(
			'numTicket'				=> '0',
			'YEAR(fechaVenta) >='	=> '2016'
		)); 
		$this->db->get(self::TABLE, 1000)->result();

		return $this->db->get(self::TABLE)->result();
	}

	function getMaxByYear($year) {
		if (!$year) return null;

		$this->db->select_max(self::ID, self::ID);
		$this->db->where("YEAR(fechaVenta)", $year); 
		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row) ? $row : null;
	}

	function getUltimaVenta() {		
		$this->db->select_max(self::ID, self::ID);		
		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row) ? $row : null;
	}
	
	
	function getMinFechaVenta() {
		$this->db->select(self::ID, self::ID);
		$this->db->select_min('fechaVenta', 'fechaVenta');
		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row) ? $row : null;
	}

	function ventaExists($venta) {
		if (!$venta) return null;

		$this->db->select(self::ID);
		$this->db->where(self::ID, $venta); 

		$row = $this->db->get(self::TABLE, 1)->row();

		return (count($row));
	}

	function getMinVentaPagoPendiente($venta) {
		$this->db->select_min(self::ID, self::ID);
		$this->db->where(array(
			"idventa >=" => $venta,
			"pago" => null
		)); 
		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row) ? $row : null;	
	}

	function getSumPuntosByDni($dni) {

		$this->db->select_sum('puntos', 'puntos');
		$this->db->where("dni", $dni); 
		$row = $this->db->get(self::TABLE, 1)->row();
		
		return count($row->puntos) ? $row->puntos : 0;
		return count($row) ? $row : null;
	}
	
	function getByItem($venta, $linea) {		
		$this->db->where(array(
			"idventa" => $venta,
			"idnlinea" => $linea
		)); 
		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row) ? $row : null;	
	}
	
	function getByEjercicioAndGreatThanOrEqualFechaVenta($ejercicio, $fechaVenta) {
		$this->db->where(array(
			"YEAR(fechaVenta)" => $ejercicio,
			"fechaVenta >=" => $fechaVenta
		));
		$row = $this->db->get(self::TABLE, 1)->row();
		return count($row) ? $row : null;	
	}

	function setData($data = array(), $where = null, $insertIfNotExist = false){
		if (!$where) return false;
		
		$this->db->select('cod');

		if (is_array($where))
			$this->db->where($where); 
		else			
			$this->db->where(self::ID, $where); 

		if ($this->db->get(self::TABLE, 1)->num_rows()){
			$this->db->update(self::TABLE, 
				$data, 
				is_array($where) ? $where : array(self::ID => $where)
			);
			return $this->db->affected_rows();
		}
		if ($insertIfNotExist){
			$this->db->insert(self::TABLE, $data);
			return $this->db->affected_rows();
		}

		return 0;
	}

	function delete($idventa) {
		if (!count($idventa)) return false;

		$this->db->where_in(self::ID, $idventa);
		$this->db->delete(self::TABLE);

		return $this->db->affected_rows();
	}
}