<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Sinonimos extends REST_Controller {
 
	public function isEmpty_get() {
		$this->load->model('api/sinonimos_model', 'sinonimosModel');
		
		$this->response(array(
			"count" => $this->sinonimosModel->count(),
			"isEmpty" => ($this->sinonimosModel->count() == 0)
		), 200);

	}
	
	public function empty_put() {
		$this->load->model('api/sinonimos_model', 'sinonimosModel');
		
		$this->sinonimosModel->truncate();
		
		$this->response('', 200);

	}


	public function createUpdate_post() {

		$this->load->model('api/sinonimos_model', 'sinonimosModel');

		if (!$this->post("bulk")){
			$this->response('', 400);
			return;
		}

		// TODO: lo agrgegé así porque sino no funciona!!
		$bulk = json_decode(json_encode($this->post("bulk")));

		if (!$bulk){
			$this->load->helper("api/json_helper");
			$this->response(array(
				"error" => getLastJSONError()
			), 400);
			return;
		}

		if ($this->post("truncate") == '1'){
			$this->sinonimosModel->truncate();
		}


		$success = array("rowsChanged" => 0);

		foreach ($bulk as $i => $sinonimo) {
			$success["rowsChanged"] += $this->sinonimosModel->insert($sinonimo);
			usleep(1);
		}
		$this->response($success, 200);
	}

	
}