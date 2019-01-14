<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Faltas extends REST_Controller {
 
	function ultimo_get(){
		$this->load->model('api/faltas_model', 'faltasModel');

		$idPedido = $this->faltasModel->getLast();

		$this->response($idPedido, ($idPedido) ? 200 : 404);
	}


	function ultimaLinea_get(){
		$this->load->model('api/faltas_model', 'faltasModel');

		if(!$this->get('pedido')) {
			$this->response('', 400);
			return;
		}

		$idLinea = $this->faltasModel->getMaxIdLineaByPedido($this->get('pedido'));

		$this->response($idLinea, ($idLinea) ? 200 : 404);
	}


	function exists_get(){
		$this->load->model('api/faltas_model', 'faltasModel');

		if(!$this->get('pedido') || !$this->get('cod_nacional')) {
			$this->response('', 400);
			return;
		}

		$falta = $this->faltasModel->getWhere(array(
			"idPedido"		=> $this->get('pedido'),
			"cod_nacional"	=> $this->get('cod_nacional')
		));

		$this->response(array(
			"idPedido" 		=> $this->get('pedido'),
			"exists" 		=> count($falta) ? 1 : 0
		), 200);
	}


	function createUpdate_post() {

		$this->load->model('api/faltas_model', 'faltasModel');

		if (!$this->post("bulk")){
			$this->response('', 400);
			return;
		}

		$bulk = json_decode($this->post("bulk"));

		if (!$bulk){
			$this->load->helper("api/json_helper");
			$this->response(array(
				"error" => getLastJSONError()
			), 400);
			return;
		}

		$success = array();
		foreach ($bulk as $i => $faltas) {
			$success[] = array(
				"cod"	=> $faltas->cod,
				"rowsChanged"	=> $this->faltasModel->setData($faltas, $faltas->cod, true)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}
	

}