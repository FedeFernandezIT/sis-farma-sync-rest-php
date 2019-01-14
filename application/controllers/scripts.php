<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scripts extends CI_Controller {

    public function index() {

    }
        
    public function probarAPI() {
        $curl = curl_init();
        
        $keyAPI = 'demo:demo';
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => APP_URL_BASE."api/cliente/index/dni/1/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".base64_encode($keyAPI)
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2
        ));
        
        $response = curl_exec($curl);
        
		$err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
    }
}