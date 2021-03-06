<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Pedido extends REST_Controller {

	public function isEmpty_get() {
		$this->load->model('api/pedido_model', 'pedidoModel');
		
		$this->response(array(
			"count" => $this->pedidoModel->count(),
			"isEmpty" => ($this->pedidoModel->count() == 0)
		), 200);

	}
	
	function index_get(){

		$this->load->model('api/pedido_model', 'pedidoModel');
		
		$pedido = $this->pedidoModel->getWhere(array(
			"idPedido" => $this->get('idPedido')));

		$this->response($pedido, ($pedido) ? 200 : 404);
	}
	
	public function ultimo_get() {
		$this->load->model('api/pedido_model', 'pedidoModel');
		
		$last = $this->pedidoModel->getLast();
		$this->response($last, ($last) ? 200 : 404);
	}

	public function index_delete() {
		$this->load->model('api/linea_pedido_model', 'lineaPedidoModel');
		
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
				"rowsAffected" => $this->lineaPedidoModel->delete($ids)
			), 200);
			return;
		}

		$this->response('', 400);
	}

	public function createUpdate_post() {

		$this->load->model('api/pedido_model', 'pedidoModel');

		if (!$this->post("bulk")){
			$this->response('', 400);
			return;
		}

		$bulk = json_decode(json_encode($this->post("bulk")));

		if (!$bulk){
			$this->load->helper("api/json_helper");
			$this->response(array(				
				"error" => $bulk
			), 400);
			return;
		}

		$success = array();
		foreach ($bulk as $i => $pedido) {
			$success[] = array(
				"rowsChanged"	=> $this->pedidoModel->insert($pedido)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}
	
	public function createUpdateLinea_post() {

		$this->load->model('api/linea_pedido_model', 'lineaPedidoModel');

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
		foreach ($bulk as $i => $lineaPedido) {
			$success[] = array(
				"rowsChanged"	=> $this->lineaPedidoModel->insert($lineaPedido)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}
}