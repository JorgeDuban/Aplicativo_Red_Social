
<?php
    require_once "librerias/sesion_segura.php";
		require_once "librerias/libreria.php";
  	require_once "conexion/database.php";
    require_once "logica_registro.php";
    AntiCSRF();
    $_POST = LimpiarArray($_POST);
      
        $message = "";
            function EnviarMensaje(){
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
                $allowedextension_archivos = array('jpg', 'jpeg', 'gif', 'png', 'txt', 'php');		
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
            $registrado = FALSE;
            if (isset($btn_accion) && $btn_accion == 'Enviar') {
              $destinario = $_POST['destinario'];
              $mensaje = $_POST['mensaje'];
                $conn = ConexionDB();
                if ($conn != NULL) {					
                  $registrado = enviar_mensaje_seguro($conn, $destinario, $mensaje, $foto);
                  try {
                    $path = "$foto";
                    } catch (\Throwable $th) {
                    }
                  if ($registrado) {
                    $message .='<br/>Mensaje Enviado.';
                    $tipoMensaje = "ok";
                  }
                }				
              }
              if (!$registrado) {
                $message .='<br/>Mensaje no enviado.';
                $tipoMensaje = "error";
              }
            }
              if (isset($_POST) && isset($_POST['btn_accion'])) {
                LimpiarEntradas();
                EnviarMensaje();
            }
    ?>
    <?php 
    $conexion=mysqli_connect('localhost','root','','proyecto_linea_2_database');
    try{
    $user = $_SESSION['user_name'];
    } catch (\Throwable $th) {
    }
    $sql="SELECT foto FROM usuarios WHERE usuario = '$user'";
    $result=mysqli_query($conexion,$sql);
    ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Enviar Mensaje</title>
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
    
  </head>
  <body>
        <div>
        <?php require_once "partials/header.php" ;
          while($mostrar=mysqli_fetch_array($result)){
            ?>
            <?php echo "<img class='f_perfil' src='".$mostrar['foto']."' width='70'>"?>
            <?php 
            }
            echo '<div class="user"> '.LimpiarCadena($_SESSION['user_name']) . '.</div>
            <br>';
          ?>
        </div>
        <h1></h1>
        
        <a href="mensajes_recibidos.php"><button class="btn_titulo">Mensajes Recibidos</button></a>
        <a href="mensajes_enviados.php"><button class="btn_titulo">Mensajes Enviados</button></a>
        <a href="registro_mensajes.php"><button class="btn_titulo">Crear Mensaje</button></a>

        <?php
            if ($message != "") 
            {
              echo '<div class="error_">' . $message . '</div>';
            }
        ?>

    <form method="post" enctype="multipart/form-data">
        <div>
        <label for="destinario" name="destinario" class="form-element">Destinatario:</label>
        <select  name="destinario" id="destinario">
        <option value=""></option>
        <?php 
            $sql="SELECT nombre, apellidos, usuario FROM usuarios";
            $result=mysqli_query($conexion,$sql);
            while($mostrar=mysqli_fetch_array($result)){
        
            $nombre = $mostrar ['nombre'];
            $apellidos = $mostrar ['apellidos'];
            $usuario = $mostrar['usuario'];

            ?>
            <option value="<?php echo $usuario; ?>"><?php echo $nombre." ".$apellidos?></option>
          
          <?php
        }
        ?>
        </select>
        </div>
        <div>
      <input class="input_texto" name="mensaje" type="text" placeholder="Ingrese descripción" required>
      </div>
      <div>
      <label class="form-element" id='foto' for="archivo">Arch. Adjunto:</label>
      <input class ="foto" type="file" name="archivo" required/>
      </div>
        <br>
        <div>
      <input class="btn" type="submit" name="btn_accion" value="Enviar">
      </div>
      <input type="hidden" name="csrf_token" id="token_AntiCSRF" value="<?php echo $_SESSION['AntiCSRF'];?>">
      </form>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>