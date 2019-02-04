<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Programacion extends REST_Controller {
 
	function encendido_get() {

		$this->load->model('api/programacion_model', 'programacionModel');


		$encendido = $this->programacionModel->getEncendido();

		$this->response(($encendido) ? $encendido : '', ($encendido) ? 200 : 404);
		
	}
	
	function apagado_get() {

		$this->load->model('api/programacion_model', 'programacionModel');

		$apagado = $this->programacionModel->getApagado();

		$this->response(($apagado) ? $apagado : '', ($apagado) ? 200 : 404);
	}	 

}