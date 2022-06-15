
<?php
    require_once "librerias/sesion_segura.php";
    require_once "librerias/libreria.php";
    require_once "conexion/database.php";
    require_once "logica_registro.php";

    AntiCSRF();

    $message = "";
    $tipoMensaje = "";
    $registrado = FALSE;
    
        function RegistrarUsuario(){
            global $message;
            
            $registrado = FALSE;
            $foto = "";
            if (isset($_FILES['archivo'])) {
              $fileTmpPath = $_FILES['archivo']['tmp_name'];
              $fileName = $_FILES['archivo']['name'];
              $fileSize = $_FILES['archivo']['size'];
              $fileType = $_FILES['archivo']['type'];
              $fileNameCmps = explode(".", $fileName);
              $fileExtension = strtolower(end($fileNameCmps));
          
              if ($fileName != '') {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $allowedextension_archivos = array('jpg', 'jpeg', 'gif', 'png');		

                $uploadFileDir = './uploaded_files/';
                $dest_path = $uploadFileDir . $newFileName;
                
                if(move_uploaded_file($fileTmpPath, $dest_path))
                {
                  $message ='Archivo subido.';
                }
                $foto = $dest_path;
              }
              else{
                $message ='Archivo NO subido.';
              }
            }
            $btn_accion = $_POST['btn_accion'];
            if (isset($btn_accion) && $btn_accion == 'Registrar') {
              $nombre = $_POST['nombre'];
              $apellidos = $_POST['apellidos'];
              $correo = $_POST['correo'];
              $direccion = $_POST['direccion'];
              $numero_hijos = $_POST['numero_hijos'];
              $estado_civil = $_POST['estado_civil'];
              $usuario = $_POST['usuario'];
              $password = md5($_POST['password']);
                $conn = ConexionDB();
                if ($conn != NULL) {					
                  $registrado = RegistrarUsuarioDB($conn, $nombre, $apellidos, $correo, $direccion, $numero_hijos, $estado_civil, $usuario, $password, $foto);
                    
                  try {
                    $path = "$foto";
                    if (file_exists($path)) {
                      $img = imagecreatefromjpeg ($path);
                      imagejpeg ($img, $path, 100);
                      imagedestroy ($img);
                  } else {
                      echo "";
                  }
                    } catch (\Throwable $th) {
                    }
                    
                  if ($registrado) {
                    $message .='<br/>Usuario actualizado.';
                    $tipoMensaje = "ok";
                  }
                }				
              }
              if (!$registrado) {
                $message .='<br/>Usuario NO actualizado.';
                $tipoMensaje = "error";
              }
            }

        if (isset($_POST) && isset($_POST['btn_accion'])) {
            LimpiarEntradas();
            RegistrarUsuario();
        }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Registro de Usuario</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
  </head>
  <body>

    <?php require_once "partials/header2.php" ?>
    
    <h1>Registro</h1>

    <?php
      if ($message != "") 
        {
          echo '<div class="error_">' . $message . '</div>';
        }
    ?>

    <form action="registro_usuario.php" method="POST" enctype="multipart/form-data">
    <div>
    <label for="nombre">Nombre:</label>
      <input name="nombre" type="text"  pattern="[a-zA-Z]+" required>
      </div>
      <div>
      <label for="apellidos">Apellidos:</label>
      <input name="apellidos" type="text" pattern="[a-zA-Z]+">
      </div>
      <div>
      <label for="correo">Correo:</label>
      <input name="correo" type="mail" required>
      </div>
      <div>
      <label for="direccion">Dirección:</label>
      <input name="direccion" type="text" pattern="[a-zA-Z0-9]+" required>
      </div>
      <div>
      <label for="numero_hijos">Núm. Hijos:</label>
      <input name="numero_hijos" type="number" min ="0" required pattern="[0-9]+">
      </div>
      <div>
      <label for="estadp_civil">Estado Civil:</label>
      <select name="estado_civil" id="estado_civil">
        <option value="Soltero">Soltero/a</option>
        <option value="Casado">Casado/a</option>
        <option value="Divorciado">Divorciado/a</option>
      </select>
      </div>
      <div>
      <label for="usuario">Usuario:</label>
      <input name="usuario" type="text" required pattern="[a-zA-Z0-9]+">
      </div>
      <div>
      <label for="password">Password:</label>
      <input name="password" type="password" required>
      </div>
      <div>
      <label class="form-element" id='foto' for="archivo">Foto Perfil:</label>
      <input class ="foto" type="file" name="archivo" accept="image/*" required/>
      <div>
        <br>
      <input class="btn" type="submit" name="btn_accion" value="Registrar">
      </div>
      <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['AntiCSRF'];?>">
    </form>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>