<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Linea extends REST_Controller {

	function index_get(){

		$this->load->model('api/linea_pedido_model', 'lineaPedidoModel');
		
		$linea = $this->lineaPedidoModel->getWhere(array(
			"idPedido" 	=> $this->get('pedido'),
			"idLinea"  	=> $this->get('linea')));

		$this->response($linea, ($linea) ? 200 : 404);
	}

	public function pedido_count_get(){
		$this->load->model('api/linea_pedido_model', 'lineaPedidoModel');

		if(!$this->get('idPedido')) {
			$this->response('', 400);
			return;
		}

		$numLineas = $this->listaModel->count($this->get('idPedido'));

		$this->response(array(
			"numLineas" => $numLineas
		), 200);
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

		$this->load->model('api/linea_pedido_model', 'lineaPedidoModel');

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
		foreach ($bulk as $i => $lineaPedido) {
			$success[] = array(
				"rowsChanged"	=> $this->lineaPedidoModel->insert($lineaPedido)
			);
			usleep(1);
		}
		$this->response($success, 200);
	}
}