<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Cliente extends REST_Controller {
 
	function index_get(){

		$this->load->model('api/clientes_model', 'clientesModel');

		// TODO: 0 es falso y enviaba un BadRequest en vez de NotFound
		// if(!$this->get('dni')) {
			// $this->response('', 400);
			// return;
		// }

		$cliente = $this->clientesModel->getByDni($this->get('dni'));

		$this->response($cliente, ($cliente) ? 200 : 404);
	}

	function index_put() {

		$this->load->model('api/clientes_model', 'clientesModel');

		if(!$this->get('dni')) {
			$this->response('', 400);
			return;
		}

		$success = $this->clientesModel->setDataByDni(array(						
			'dni_tra'			=> $this->put('dni_tra'),									
			'nombre_tra'		=> $this->put('nombre_tra'),								
			'tarjeta'			=> $this->put('tarjeta'),
			'apellidos'			=> $this->put('apellidos'),
			'telefono'			=> $this->put('telefono'),
			'direccion'			=> $this->put('direccion'),
			'movil'				=> $this->put('movil'),
			'email'				=> $this->put('email'),
			'fecha_nacimiento'	=> $this->put('fecha_nacimiento'),
			'puntos'			=> $this->put('puntos'),
			'sexo'				=> $this->put('sexo'),
			'fechaAlta'			=> $this->put('fechaAlta'),
			'baja'				=> $this->put('baja'),
			'lopd'				=> $this->put('lopd'),
			'dni'				=> $this->get('dni'),
		), $this->get('dni') , true);

		$this->response('', 200);
	}

	function createUpdate_post() {

		$this->load->model('api/clientes_model', 'clientesModel');

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
		if ($this->get("setCeroClientes") != false)
			$this->clientesModel->setCeroClientes();

		$success = array();
		foreach ($bulk as $i => $cliente) {
			$success[] = array(
				"dni"			=> $cliente->dni,
				"rowsChanged"	=> $this->clientesModel->setDataByDni($cliente, $cliente->dni, true)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}

	function update_put() {

		$this->load->model('api/clientes_model', 'clientesModel');

		if (!$this->put("bulk")){
			$this->response('', 400);
			return;
		}

		$bulk = json_decode(json_encode($this->put("bulk")));

		if (!$bulk){
			$this->load->helper("api/json_helper");
			$this->response(array(
				"error" => getLastJSONError()
			), 400);
			return;
		}

		foreach ($bulk as $cliente) {
			$success[] = array(
				"dni"			=> $cliente->dni,
				"rowsChanged"	=> $this->clientesModel->setDataByDni($cliente, $cliente->dni)
			);
			usleep(1);
		}

		$this->response($success, 200);		
	}

	function ultimo_get() {
		$this->load->model('api/clientes_model', 'clientesModel');

		$now = date('Hi');

		if ($now == "1500" || $now == "2300"){
			$this->response(array(
				'dni' => 0), 200);
			return;
		}

		$lastUpdated = $this->clientesModel->getLastUpdated();
		$lastUpdated = array(
			'dni' => $lastUpdated ? $lastUpdated : 0
		);

		$this->response($lastUpdated, 200);
	}

	function setCeroClientes_put() {
		$this->load->model('api/clientes_model', 'clientesModel');

		$this->clientesModel->setCeroClientes();

		$this->response('', 200);
	}

	function exists_get(){
		$this->load->model('api/clientes_huecos_model', 'clientesHuecosModel');

		if(!$this->get('hueco')) {
			$this->response('', 400);
			return;
		}

		$this->response(array(
			"hueco" 	=> $this->get('hueco'),
			"exists" 	=> $this->clientesHuecosModel->huecoExists($this->get('hueco'))
		), 200);
	}

	function hueco_get(){
		$this->load->model('api/clientes_huecos_model', 'clientesHuecosModel');

		$this->response($this->clientesHuecosModel->getAllHueco(), 200);
	}

	function hueco_put(){
		$this->load->model('api/clientes_huecos_model', 'clientesHuecosModel');

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

	
	// TODO: cambié a PUT porque de delete no puede leer
	function huecoeliminar_put() {
		$this->load->model('api/clientes_huecos_model', 'clientesHuecosModel');
				
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
				"rowsAffected" => $this->clientesHuecosModel->delete($ids)
			), 200);
			return;
		}
	}

}