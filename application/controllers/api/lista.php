<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Lista extends REST_Controller {
 
 	public function iteracion_get(){

		$this->load->model('api/lista_model', 'listaModel');

		$lista = $this->listaModel->getWhere(array(
			'porDondeVoy' => 1
		));

		$this->response(($lista) ? $lista : -1, ($lista) ? 200 : 404);
	}


	public function iteracion_reset_post(){

		$this->load->model('api/lista_model', 'listaModel');

		$lista = $this->listaModel->updateData(array(
			'porDondeVoy' => 0
		));

		$this->response($lista, ($lista) ? 200 : 404);
	}

	public function createUpdate_post() {

		$this->load->model('api/lista_model', 'listaModel');

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
		foreach ($bulk as $i => $lista) {			
			$success[] = array(
				"cod"	=> $lista->cod,
				"rowsChanged"	=> $this->listaModel->setData($lista, $lista->cod, true)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}
	
	public function index_get(){

		$this->load->model('api/lista_model', 'listaModel');

		$lista = $this->listaModel->getWhere(array(
			'cod' => $this->get('codigo')
		));

		$this->response($lista, ($lista) ? 200 : 404);
	}

	public function index_delete() {
		$this->load->model('api/lista_model', 'listaModel');
		
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
				"rowsAffected" => $this->listaModel->delete($ids)
			), 200);
			return;
		}

		$this->response('', 400);
	}


	public function articulo_createUpdate_post() {

		$this->load->model('api/lista_articulo_model', 'listaArticuloModel');

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
		foreach ($bulk as $i => $articulo) {
			$success[] = array(
				"codLista"		=> $articulo->cod_lista,
				"rowsChanged"	=> $this->listaArticuloModel->insert($articulo)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}

	public function articulo_eliminar_put() {
		$this->load->model('api/lista_articulo_model', 'listaArticuloModel');
		
		$ids = array();

		if ($this->put("ids")) {
			$ids = json_decode(json_encode($this->put("ids")));

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
				"rowsAffected" => $this->listaArticuloModel->delete($ids)
			), 200);
			return;
		}

		$this->response('', 400);
	}
}