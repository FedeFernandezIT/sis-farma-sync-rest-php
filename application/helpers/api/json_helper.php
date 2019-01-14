<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getLastJSONError(){
	switch(json_last_error()) {
		case JSON_ERROR_NONE:
			return false;
		break;
		case JSON_ERROR_DEPTH:
			return 'Excedido tamaño máximo de la pila';
		break;
		case JSON_ERROR_STATE_MISMATCH:
			return 'Desbordamiento de buffer o los modos no coinciden';
		break;
		case JSON_ERROR_CTRL_CHAR:
			return 'Encontrado carácter de control no esperado';
		break;
		case JSON_ERROR_SYNTAX:
			return 'Error de sintaxis, JSON mal formado';
		break;
		case JSON_ERROR_UTF8:
			return 'Caracteres UTF-8 malformados, posiblemente están mal codificados';
		break;
		default:
			return 'Error desconocido';
		break;
	}
}
