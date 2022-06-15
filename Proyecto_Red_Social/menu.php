<?php
    require_once "librerias/sesion_segura.php";
	  require_once "librerias/libreria.php";
    require_once "conexion/database.php";
    require_once "logica_registro.php"; 

    AntiCSRF();

    $_POST = LimpiarArray($_POST);

		$mensaje1 = ""; 
		$mensaje = ""; 

		function Validacion(){
      
			global $mensaje1, $mensaje;
			$usuario = $_POST['usuario'];
			$password = md5($_POST['password']);
			$btn_accion = $_POST['btn_accion'];
			unset($_SESSION['user_name']);
			if (isset($btn_accion) && $btn_accion == 'Ingresar') {
        if(isset($_POST['g-recaptcha-response'])){

          //Clave del sitio: 6LcgoQ4bAAAAANhhpwsFnCZSWyU1_XV6OkWfHuOl
          //Clave secreta: 6LcgoQ4bAAAAAIg0l_qX81Ml9hS_TKn-FH_8JmAW

					$secret = '6LcgoQ4bAAAAAIg0l_qX81Ml9hS_TKn-FH_8JmAW';

					$query = http_build_query(array('secret'=>$secret, 'response'=>$_POST['g-recaptcha-response']));

					$url = "https://www.google.com/recaptcha/api/siteverify?" . $query;

					$res_google = file_get_contents($url);

					$res = json_decode($res_google);

					if ($res->success){
						$autorizado = 1; //es valido
					}
					else{
						$autorizado =2; //es terminator
					}
				}
				else{
					$autorizado = 3; //No intento resolver el captcha
				}
			}

			echo '<br>' . $autorizado . ': ';
			switch($autorizado){
				case 1:
					$ncon = ConexionDB();
				if ($ncon != NULL) {
					if (Login($ncon, $usuario, $password) != "") {
						$_SESSION['user_name'] = $usuario;
           				 
						header("Location: sesion.php");
						exit();
					}
					else {
						$mensaje1 = 'error';
						$mensaje = 'Datos erroneos';
            			http_response_code(401);
					}
				}
					break;

				case 2:
					echo 'Apruebe el Captcha';
					break;

				case 3:
					echo 'Ni intento';
					break;
			}
		if (isset($_POST) && isset($_POST['btn_accion'])) {
			LimpiarEntradas();
			Validacion();
		}
    ?>
	

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
    <script async src="https://www.google.com/recaptcha/api.js"></script>
    
  </head>

  <body>
   
        <?php if(!empty($message)): ?>
        <p> <?= $message ?></p>
        <?php endif; ?>

        <h1></h1>

        <?php
            if ($mensaje != "") 
            {
            echo '<div class="leyend_' . $mensaje1 . ' ">' . $mensaje . '</div>';
            }
        ?>

        <form method="post">
        </div class= "error">
        <label for="usuario" class="form-element">Usuario:</label>
      <input id="usuario" name="usuario" type="text" placeholder="Ingrese su usuario" pattern="[a-zA-Z0-9]+" required>
      <div class="form-element">
      </div>
      <label for="usuario" class="form-element">Clave:</label>
      <input name="password" type="password" placeholder="Ingrese su contraseña" required>
      <div class="form-element">
      <!--<label for="txtCaptcha">Ingrese 

        <?php
          $captcha_text = rand(1000, 9999);
          echo $captcha_text;
        ?>
      </label>
      <input type="text" name="txtCaptcha" pattern="<?php echo $captcha_text; ?>" required/>-->
      <div class="g-recaptcha" data-sitekey="6LcgoQ4bAAAAANhhpwsFnCZSWyU1_XV6OkWfHuOl"></div>
    </div>
      <input class="btn" type="submit" name="btn_accion" value="Ingresar">
      <a class="btn" type="submit" href="registro_usuario.php">Registrar</a>
      <div class="form-element">
      <input type="hidden" name="csrf_token" id="token_AntiCSRF" value="<?php echo $_SESSION['AntiCSRF'];?>">
    </form>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>