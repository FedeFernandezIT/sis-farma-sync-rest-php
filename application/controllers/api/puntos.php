<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Puntos extends REST_Controller {

	function index_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if(!$this->get('dni')) {
			$this->response('', 400);
			return;
		}		

		$suma = $this->puntosModel->getSumPuntosByDni($this->get('dni'));

		$this->response($suma ? $suma : 0, 200);
	}

	function exists_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if(!$this->get('venta')) {
			$this->response('', 400);
			return;
		}		

		$this->response(array(
			"venta" => $this->get('venta'),
			"exists" => $this->puntosModel->ventaExists($this->get('venta'))
		), 200);
	}

	function ventasNoActualizado_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		$idVentas = $this->puntosModel->getNoActualizados();

		$this->response($idVentas, 200);
	}

	function ventasNumeroDeTicketCero_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		$idVentas = $this->puntosModel->getTicketCero();

		$this->response($idVentas, 200);
	}

 	function ultimo_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if(!$this->get('year')) {
			$this->response('', 400);
			return;
		}		

		$idVenta = $this->puntosModel->getMaxByYear($this->get('year'));

		$this->response($idVenta, ($idVenta) ? 200 : 404);
	}

	function hayPendiente_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if(!$this->get('venta')) {
			$this->response('', 400);
			return;
		}		

		$idVenta = $this->puntosModel->getMinVentaPagoPendiente($this->get('venta'));

		$this->response($idVenta, ($idVenta) ? 200 : 404);
	}
	
	function sinRedencion_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		$data = $this->puntosModel->getSinRedencion();

		$this->response($data, ($data) ? 200 : 404);
	}

	function primerFechaVenta_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		$idVenta = $this->puntosModel->getMinFechaVenta();

		$this->response($idVenta ? $idVenta : array('idventa' => '20130'), 200);
	}

	function numLineas_get() {

		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if(!$this->get('idVenta')) {
			$this->response('', 400);
			return;
		}		

		$numLineas = array(
			'idVenta'	=> $this->get('idVenta'),
			'numLineas'	=> $this->puntosModel->countLineaByIdVenta($this->get('idVenta'))
		);

		$this->response($numLineas, ($numLineas) ? 200 : 404);
	}
	
	function item_get() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if((!$this->get('venta') && $this->get('venta') != 0) || (!$this->get('linea') && $this->get('linea') != 0)) {
			$this->response('', 400);
			return;
		}		
		
		$data = $this->puntosModel->getByItem($this->get('venta'), $this->get('linea'));

		$this->response($data, ($data) ? 200 : 404);
		return;		
	}

	function createUpdate_post() {

		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if (!$this->post("puntos")){
			$this->response('', 400);
			return;
		}

		$puntos = json_decode(json_encode($this->post("puntos")));

		if (!$puntos){
			$this->load->helper("api/json_helper");
			$this->response(array(
				"error" => getLastJSONError()
			), 400);
			return;
		}
		
		$success = array(
				"idventa"		=> $puntos->where->idventa,
				"rowsChanged"	=> $this->puntosModel->setData($puntos->set, json_decode(json_encode($puntos->where), true), true)
			);
			
		// foreach ($puntos as $punto) {
			// $success[] = array(
				// "idventa"		=> $punto->where->idventa,
				// "rowsChanged"	=> $this->puntosModel->setData($punto->set, $punto->where, true)
			// );
			// usleep(1);
		// }
		$this->response($success, 200);
	}

	function update_put() {

		$this->load->model('api/pendiente_puntos_model', 'puntosModel');

		if (!$this->put("puntos")){
			$this->response('', 400);
			return;
		}
		
		// TODO directamente no funciona
		$puntosEncode = json_encode($this->put("puntos"));
		$puntos = json_decode($puntosEncode);
		
		if (!$puntos){
			$this->load->helper("api/json_helper");
			$this->response(array(
				"error" => getLastJSONError()
			), 400);
			return;
		}

		//foreach ($puntos as $punto) {
			$success[] = array(
				"idventa"		=> $puntos->where->idventa,
				"rowsChanged"	=> $this->puntosModel->setData($puntos->set, json_decode(json_encode($puntos->where), true))
			);
			//usleep(1);
		//}

		$this->response($success, 200);		
	}

	function index_delete() {
		$this->load->model('api/pendiente_puntos_model', 'puntosModel');
		
		$ids = array();

		if ($this->delete("ids")) {
			$ids = json_decode($this->delete("ids"));

			if (!$ids){
				$this->load->helper("api/json_helper");
				$this->response(array(
					"error" => getLastJSONError()
				), 400);
				return;
			}
		}

		if ($this->delete("id")) 
			$ids = array($this->delete("id"));

		if (count($ids)) {
			$this->response(array(
				"rowsAffected" => $this->puntosModel->delete($ids)
			), 200);
			return;
		}

		$this->response('', 400);
	}


	function canjeados_get() {
		$this->load->model('api/canjeo_puntos_model', 'canjeoPuntosModel');

		if(!$this->get('dni')) {
			$this->response('', 400);
			return;
		}

		$suma = $this->canjeoPuntosModel->getTotalPuntosCanjeados($this->get('dni'));

		$this->response($suma ? $suma : 0, 200);	
	}

}