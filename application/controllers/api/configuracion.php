<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Configuracion extends REST_Controller {
 
	function clasificacion_get() {

		$this->load->model('api/configuracion_model', 'configModel');


		$configValor = $this->configModel->getValorByColumn(array(
			'campo' => 'clasificar'
		));

		$this->response(($configValor) ? $configValor : 'Familia', 200);
	}

	function index_get() {

		if(!$this->get('campo')) {
			$this->response('', 400);
			return;
		}

		$this->load->model('api/configuracion_model', 'configModel');

		$configValor = $this->configModel->getValorByColumn(array(
			'campo' => $this->get('campo')
		));

		$this->response(($configValor) ? $configValor : '', 200);

	}
	
	
	// TODO: actualiza valor de campo
	function campo_put() {

		if(!$this->put('campo') || (!$this->put('valor') && $this->put('valor') != 0)) {
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