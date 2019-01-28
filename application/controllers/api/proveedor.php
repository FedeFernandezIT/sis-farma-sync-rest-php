<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Proveedor extends REST_Controller {
 
	public function index_get() {
		$this->load->model('api/proveedor_model', 'proveedorModel');
	
	
		$proveedor = $this->proveedorModel->getWhere(array(
			'idProveedor'  => $this->get('proveedor'),
			'nombre'	   => $this->get('nombre')));

		$this->response($proveedor, ($proveedor) ? 200 : 404);
	}
	
	public function createUpdate_post() {

		$this->load->model('api/proveedor_model', 'proveedorModel');

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
		
		$success = array("rowsChanged" => 0);

		foreach ($bulk as $i => $proveedor) {
			$success["rowsChanged"] += $this->proveedorModel->setData($proveedor, ($proveedor->id) ? $proveedor->id : null, true);
			usleep(1);
		}
		$this->response($success, 200);
	}
	
	
	public function historial_get() {
		$this->load->model('api/proveedor_historico_model', 'proveedorHistorialModel');
		
		$fechaMax = $this->proveedorHistorialModel->getMaxByFecha();
		$this->response($fechaMax, 200);

	}
	
	
	public function historial_createUpdate_post() {

		$this->load->model('api/proveedor_historico_model', 'proveedorHistorialModel');

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
		
		$success = array("rowsChanged" => 0);

		foreach ($bulk as $i => $historico) {
			$success["rowsChanged"] += $this->proveedorHistorialModel->insert($historico);
			usleep(1);
		}
		$this->response($success, 200);
	}
	
		
}