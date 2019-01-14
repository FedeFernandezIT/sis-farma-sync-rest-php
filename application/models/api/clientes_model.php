<?php 

require(APPPATH.'libraries/REST_Model.php');

class Clientes_Model extends CI_Model {

	const TABLE = 'clientes';

	const ID = "dni";

	const COLUMNS = array(
		'dni_tra',
		'tarjeta',
		'dni',
		'apellidos',
		'telefono',
		'direccion',
		'movil',
		'email',
		'fecha_nacimiento',
		'sexo',
		'tipo',
		'fechaAlta',
		'baja',
		'estado_civil',
		'lopd'
	);
	
	const DEFAULT_VALUES = array(
		'tipo'				=> 'cliente',
		'tarjeta_tra'		=> '',						
		'nombre'			=> '',
		'observaciones'		=> '',
		'facebook'			=> '',
		'maquillaje'		=> '',
		'fumar'				=> '',
		'piel'				=> '',
		'higiene_corporal'	=> '',
		'higiene_bucal'		=> '',
		'higiene_dental'	=> '',
		'homeopatia'		=> '',
		'cosmetica'			=> '',
		'nutricion_infantil'=> '',
		'infantil'			=> '',
		'nutricionydietetica'=> '',
		'dermofarmacia'		=> '',
		'cuidado_capilar'	=> '',
		'control_peso'		=> '',
		'herboristeria'		=> '',
		'diabetes'			=> '',
		'hipertension'		=> '',
		'colesterol'		=> '',
		'cuidado_pies'		=> '',
		'nivel'				=> 0,
		'nivel_virtual'		=> 0,
		'twitter'			=> '',
		'seguridad_social'	=> '',
		'estado_civil'		=> '',
		'hijos'				=> 0,
		'hijo1'				=> '',
		'fecha1'			=> 0,
		'hijo2'				=> '',
		'fecha2'			=> 0,
		'hijo3'				=> '',
		'fecha3'			=> 0,
		'hijo4'				=> '',
		'fecha4'			=> 0,
		'hijo5'				=> '',
		'fecha5'			=> 0,
		'dependiente'		=> '',
		'frecuencia'		=> '',
		'medicamentos'		=> '',
		'todo'				=> '',
		'faltan'			=> '',
		'farmacia_habitual'	=> '',
		'habitual'			=> '',
		'rostro'			=> '',						
		'pies'				=> '',
		'manos'				=> '',
		'cabellos'			=> '',
		'dientes'			=> '',
		'piel2'				=> '',
		'peso'				=> '',
		'otros'				=> '',
		'medico'			=> '',
		'otros2'			=> '',						
		'medico2'			=> '',
		'actividad_fisica'	=> '',
		'deporte'			=> '',
		'analitica'			=> '',
		'alimentacion'		=> '',
		'anteriores'		=> '',
		'maquillaje2'		=> '',
		'dermocosmetica'	=> '',
		'higiene_corporal2'	=> '',						
		'higiene_bucal2'	=> '',
		'infantil2'			=> '',
		'higiene_capilar'	=> '',
		'dietetica'			=> '',
		'productos_naturales'=> '',
		'ortopedia'			=> '',
		'optica'			=> '',
		'herboristeria2'	=> '',
		'control_diabetes'	=> '',						
		'dermocosmetica2'	=> '',
		'consejos_dieteticos'=> '',
		'dietas_personalizadas'=> '',
		'infantil3'			=> '',
		'incontinencia'		=> '',
		'colesterol2'		=> '',
		'complementos_nutricionales'=> '',
		'cuidado_vista'		=> '',
		'homeopatia2'		=> '',
		'embarazo'			=> '',
		'deportes'			=> '',
		'intolerancias_alimentarias'=> '',
		'fitoterapia'		=> '',						
		'trastornos_alimentarios'=> '',
		'psicologia'		=> '',
		'salud_sexual'		=> '',
		'alergias'			=> '',
		'problemas_circulatorios'=> '',
		'ninguno'				=> '',
		'otros3'			=> '',
		'correo'			=> '',
		'email2'			=> '',
		'sms'				=> '',
		'charlas_formativas'=> '',
		'en_farmacia'		=> '',
		'dejar_fumar'		=> '',
		'cigarrillos_dia'	=> 0,
		'ayudas_moviles'	=> '',
	);

	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function getByDni($dni = null){
		if (!$dni) return null;

		$this->db->select(implode(", ",self::COLUMNS));
		$this->db->where(self::ID, $dni); 

		$row = $this->db->get(self::TABLE, 1)->row();

		return count($row) ? $row : null;
	}

	public function setDataByDni($data = array(), $dni = null, $insertIfNotExist = false){
		if (!$dni) return false;

		$this->db->select('cod');
		$this->db->where(self::ID, $dni);

		if ($this->db->get(self::TABLE, 1)->num_rows()){			
			$this->db->update(self::TABLE, 
				$data, 
				array(self::ID => $dni));
			return $this->db->affected_rows();
		}
		if ($insertIfNotExist){			
		    $new = array_merge($data, self::DEFAULT_VALUES);
			$this->db->insert(self::TABLE, $new);
			return $this->db->affected_rows();
		}

		return 0;
	}

	public function setCeroClientes(){
		$this->db->update(self::TABLE, array("dni_tra" => 0));
	}

	public function getLastUpdated(){

		$this->db->select(self::ID);
		$this->db->where('dni_tra', 1); 

		$lastUpdated = $this->db
					->get(self::TABLE, 2)
					->next_row();

		return count($lastUpdated) ? $lastUpdated->dni : null;
	}

}