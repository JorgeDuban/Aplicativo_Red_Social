<?php
	function MostrarErrores(){
		error_reporting(E_ALL);     
		ini_set('display_errors', '1');
	}
	
	function LimpiarCadena($cadena){ 
	$patron = array('/<script>.*<\/script>/');
	//, '/..\\*/', '/..\/.*/'
		$cadena = preg_replace($patron, '', $cadena);
		$cadena = htmlspecialchars($cadena);
		return $cadena;
	}
   
	function LimpiarEntradas(){
		if (isset($_POST)) {
			foreach ($_POST as $key => $value) {
				$_POST[$key] = LimpiarCadena($value);
			}
		}
	}
	function LimpiarArray($entradas){
			foreach ($entradas as $key => $value) {
				$entradas[$key] = LimpiarCadena($value);
			}
			return $entradas;
	}
	function AntiCSRF(){

		if (!isset($_POST['btn_accion'])){
			$_SESSION['AntiCSRF'] = md5(random_int(10000, 100000));
			return;
		}
		if ($_SERVER['REQUEST_METHOD']!='POST'){
			echo 'No se puede procesar la petición';
			http_response_code(401);
			exit();
		}

		if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['AntiCSRF']){
			echo 'No se puede procesar la petición';
			http_response_code(401);
			exit();
		}
		$_SESSION['AntiCSRF'] = md5(random_int(10000, 100000));
	}
?>