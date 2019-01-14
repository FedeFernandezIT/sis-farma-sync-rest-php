<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Entregas extends REST_Controller {
 	
	function index_get() {
				
		if((!$this->get('venta') && $this->get('venta') != 0) || (!$this->get('linea') && $this->get('linea'))) {
			$this->response('', 400);
			return;
		}
				
		$this->load->model('api/entregas_model', 'entregaModel');

		$data = $this->entregaModel->getWhere(array(
			'idventa' => $this->get('venta'),
			'idnlinea' => $this->get('linea')
		));

		if($data) {
			$this->response($data, 200);
			return;
		}
		
		$this->response('', 404);		
	}
	
	function create_post() {
		$this->load->model('api/entregas_model', 'entregaModel');
		$body = $this->post();
		
		$this->entregaModel->insert($body);
		
		$this->response('',201);					
		return;
		
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

		$idsToInsert = array();
		foreach ($ids as $id) {
			if (!$this->clientesHuecosModel->huecoExists($id))
				$idsToInsert[] = array("hueco" => $id);
		}

		if (count($idsToInsert)) {
			$this->response(array(
				"rowsAffected" => $this->clientesHuecosModel->insert($idsToInsert))
			,200);			
			return;
		}

		$this->response('', 400);
	}
	
	
	
	// TODO: actualiza valor de campo
	function campo_put() {

		if(!$this->put('campo') || !$this->put('valor')) {
			$this->response('', 400);
			return;
		}

		$this->load->model('api/configuracion_model', 'configModel');

		$this->configModel->setData(			 
			array("valor" => $this->put('valor')),
			array("campo" => $this->put('campo'))
		);

		$this->response('', 200);

	}
	 

}