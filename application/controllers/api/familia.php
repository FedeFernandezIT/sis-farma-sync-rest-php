<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Familia extends REST_Controller {
 
 	function index_get(){

		$this->load->model('api/familia_model', 'familiaModel');

		if(!$this->get('id')) {
			$this->response('', 400);
			return;
		}

		$familia = $this->familiaModel->getWhere($this->get('id'));

		$this->response($familia, ($familia) ? 200 : 404);
	}

	function cod_get(){

		$this->load->model('api/familia_model', 'familiaModel');

		if(!$this->get('familia')) {
			$this->response('', 400);
			return;
		}

		if(!$this->get('tipo')) {
			$familia = $this->familiaModel->getWhere(array(
				'familia' => $this->get('familia')
			));
		}else{
			$where = array('familia' => $this->get('familia'));
			
			$tipo = $this->get('tipo');
			$customWhere = false;	
			if ($tipo == 'Familia')
				$customWhere = "tipo = 'Familia' OR tipo IS NULL";
			else
				$where['tipo'] = $tipo;

			$familia = $this->familiaModel->getWhere($where, $customWhere);
		}

		$this->response($familia, ($familia) ? 200 : 404);
	}

	function puntos_get(){

		$this->load->model('api/familia_model', 'familiaModel');

		if(!$this->get('familia')) {
			$this->response('', 400);
			return;
		}

		if(!$this->get('tipo')) {
			$familia = $this->familiaModel->getWhere(array(
				'familia' => $this->get('familia')
			));
		}else{
			$where = array('familia' => $this->get('familia'));
			
			$tipo = $this->get('tipo');
			$customWhere = false;	
			if ($tipo == 'Familia')
				$customWhere = "tipo = 'Familia' OR tipo IS NULL";
			else
				$where['tipo'] = $tipo;

			$familia = $this->familiaModel->getWhere($where, $customWhere);
		}

		$puntos = "0.00";
		if ($familia && $familia->puntos)
			$puntos = $familia->puntos;

		$this->response($puntos, ($familia) ? 200 : 404);
	}

	function index_delete() {
		$this->load->model('api/familia_model', 'familiaModel');
		
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
				"rowsAffected" => $this->familiaModel->delete($ids)
			), 200);
			return;
		}

		$this->response('', 400);
	}

	function exists_get(){
		$this->load->model('api/familia_model', 'familiaModel');

		if(!$this->get('familia')) {
			$this->response('', 400);
			return;
		}

		$this->familiaModel->setLimit(1);

		if(!$this->get('tipo')) {
			$familia = $this->familiaModel->getWhere(array(
				'familia' => $this->get('familia')
			));
		}else{
			$where = array('familia' => $this->get('familia'));
			
			$tipo = $this->get('tipo');
			$customWhere = false;	
			if ($tipo == 'Familia')
				$customWhere = "tipo = 'Familia' OR tipo IS NULL";
			else
				$where['tipo'] = $tipo;

			$familia = $this->familiaModel->getWhere($where, $customWhere);
		}

		$this->response(array(
			"exists" 	=> (bool)$familia
		), ($familia) ? 200 : 404);
	}

	function createUpdate_post() {

		$this->load->model('api/familia_model', 'familiaModel');

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
		foreach ($bulk as $i => $familia) {
			$success[] = array(
				"familia"		=> $familia->familia,
				"rowsChanged"	=> $this->familiaModel->setData($familia, $familia->familia, true)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}

}