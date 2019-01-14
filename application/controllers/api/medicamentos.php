<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Medicamentos extends REST_Controller {
 
 	function index_get(){

		$this->load->model('api/medicamentos_model', 'medicamentosModel');

		if(!$this->get('id') && $this->get('id') != 0) {
			$this->response('', 400);
			return;
		}

		$medicamento = $this->medicamentosModel->getWhere($this->get('id'));

		$this->response($medicamento, ($medicamento) ? 200 : 404);
	}

	// TODO: cambie accion porque no lee el body
	function eliminar_put() {
		$this->load->model('api/medicamentos_model', 'medicamentosModel');
				
		$ids = array();

		if ($this->put("ids")) {
			$ids = json_decode($this->put("ids"));

			if (!$ids){
				$this->load->helper("api/json_helper");
				$this->response(array(
					"error" => getLastJSONError()
				), 400);
				return;
			}
		}

		if ($this->put("id")) 
			$ids = array($this->put("id"));

		if (count($ids)) {
			$this->response(array(
				"rowsAffected" => $this->medicamentosModel->delete($ids)
			), 200);
			return;
		}

		$this->response('', 400);
	}

	
	// TODO: Debe preguntar por el field WEB si existe o no ?
	function gteq_get(){
		$this->load->model('api/medicamentos_model', 'medicamentosModel');

		if(!$this->get('id') && $this->get('id') != 0) {
			$this->response('', 400);
			return;
		}
		if ((int)$this->get('limit') > 0) {
			$this->medicamentosModel->setLimit($this->get('limit'));
		}

		if ($this->get('order')){
			$this->medicamentosModel->setOrder($this->get('order'));	
		}

		$medicamento = $this->medicamentosModel->getWhere(array(
			'cod_nacional >=' => $this->get('id')
		));

		$this->response($medicamento, ($medicamento) ? 200 : 404);
	}

	function exists_get(){

		$this->load->model('api/medicamentos_model', 'medicamentosModel');

		if(!$this->get('id')) {
			$this->response('', 400);
			return;
		}

		$this->response(array(
			"cod_nacional" 		=> $this->get('id'),
			"exists" 	=> $this->medicamentosModel->exists($this->get('id'))
		), 200);
	}

	function createUpdate_post() {

		$this->load->model('api/medicamentos_model', 'medicamentosModel');

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
		foreach ($bulk as $i => $medicamento) {
			$success[] = array(
				"cod_nacional"	=> $medicamento->cod_nacional,
				"rowsChanged"	=> $this->medicamentosModel->setData($medicamento, $medicamento->cod_nacional, true)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}
	
	function resetSinStock_put() {
		$this->load->model('api/medicamentos_model', 'medicamentosModel');
		
		$this->medicamentosModel->reset(array(
			"porDondeVoySinStock" => 0
		));
		
		$this->response("", 200);
	}
	
	function resetDondeVoy_put() {
		$this->load->model('api/medicamentos_model', 'medicamentosModel');
		
		$this->medicamentosModel->reset(array(
			"porDondeVoy" => 0
		));
		
		$this->response("", 200);
	}

}