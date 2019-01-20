<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Encargo extends REST_Controller {
 
	function ultimo_get(){
		$this->load->model('api/encargos_model', 'encargosModel');

		$last = $this->encargosModel->getLast();

		$this->response(($last), ($last) ? 200 : 404);
	}


	function index_get(){

		$this->load->model('api/encargos_model', 'encargosModel');
		
		$pedido = $this->encargosModel->getWhere(array(
			"idEncargo" => $this->get('encargo')));

		$this->response($pedido, ($pedido) ? 200 : 404);
	}
	
	function exists_get(){
		$this->load->model('api/encargos_model', 'encargosModel');

		if(!$this->get('id')) {
			$this->response('', 400);
			return;
		}

		$encargo = $this->encargosModel->getWhere($this->get('id'));

		$this->response(array(
			"idEncargo" 	=> $this->get('id'),
			"exists" 		=> count($encargo) ? 1 : 0
		), 200);
	}

	function createUpdate_post() {

		$this->load->model('api/encargos_model', 'encargosModel');

		if (!$this->post("bulk")){
			$this->response('', 400);
			return;
		}

		$bulk = json_decode(json_encode($this->post("bulk")));

		if (!$bulk){
			$this->load->helper("api/json_helper");
			$this->response(array(
				"error" => getLastJSONError()
			), 400);
			return;
		}

		$success = array();
		foreach ($bulk as $i => $encargo) {
			$success[] = array(
				"encargo"	=> $encargo->idEncargo,
				"rowsChanged"	=> $this->encargosModel->setData($encargo, $encargo->idEncargo, true)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}
	

}