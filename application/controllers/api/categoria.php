<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Categoria extends REST_Controller {

	function exists_familia_get(){
		$this->load->model('api/categoria_model', 'categoriaModel');

		if(!$this->get('padre')) {
			$this->response('', 400);
			return;
		}

		$search = array('padre' => $this->get('padre'));

		if ($this->get('categoria')){
			$search['categoria'] = $this->get('categoria');
			$search['tipo'] = 'Familia';
		}

		$encargo = $this->categoriaModel->getWhere($search);

		$this->response(array(
			"id" 		=> $this->get('id'),
			"exists" 	=> count($encargo) ? 1 : 0
		), 200);
	}
	
	function index_get(){

		$this->load->model('api/categoria_model', 'categoriaModel');
							
	    if (!$this->get('categoria')) {
			$pedido = $this->categoriaModel->getWhere(array(			
			"padre"		=> $this->get('padre')));

			$this->response($pedido, ($pedido) ? 200 : 404);
			return;
		}
			
		$pedido = $this->categoriaModel->getWhere(array(
			"categoria" => $this->get('categoria'),
			"padre"		=> $this->get('padre')));

		$this->response($pedido, ($pedido) ? 200 : 404);
	}

	function exists_categoria_get(){
		$this->load->model('api/categoria_model', 'categoriaModel');

		if(!$this->get('padre')) {
			$this->response('', 400);
			return;
		}

		$search = array('padre' => $this->get('padre'));

		if ($this->get('categoria')){
			$search['categoria'] = $this->get('categoria');
			$search['tipo'] = 'Categoria';
		}

		$encargo = $this->categoriaModel->getWhere($search);

		$this->response(array(
			"id" 		=> $this->get('id'),
			"exists" 	=> count($encargo) ? 1 : 0
		), 200);
	}

	function prestashopPadreId_get(){
		$this->load->model('api/categoria_model', 'categoriaModel');

		if(!$this->get('padre')) {
			$this->response('', 400);
			return;
		}

		$prestashopPadreId = $this->categoriaModel->getWhere(array('padre' => $this->get('padre')));

		$this->response(($prestashopPadreId) ? $prestashopPadreId : null, ($prestashopPadreId) ? 200 : 404);
	}	

	function createUpdate_post() {

		$this->load->model('api/categoria_model', 'categoriaModel');

		if (!$this->post("categorias")){
			$this->response('', 400);
			return;
		}

		$categorias = json_decode(json_encode($this->post("categorias")));

		if (!$categorias){
			$this->load->helper("api/json_helper");
			$this->response(array(
				"error" => getLastJSONError()
			), 400);
			return;
		}

		$success = array();
		foreach ($categorias as $categoria) {
			$success[] = array(
				"idventa"		=> $categoria->categoria,
				"rowsChanged"	=> $this->categoriaModel->insertData(array(
					'categoria' => $categoria->categoria,
					'padre' => $categoria->padre,
					'prestashopPadreId' => ($categoria->prestashopPadreId) ? $categoria->prestashopPadreId : null
				))
			);
			usleep(1);
		}
		$this->response($success, 200);
	}	
}