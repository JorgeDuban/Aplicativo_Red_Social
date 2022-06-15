
<?php
    require_once "librerias/sesion_segura.php";
    require_once "librerias/libreria.php";
    require_once "conexion/database.php";
    require_once "logica_registro.php";
    AntiCSRF();
    $_POST = LimpiarArray($_POST);

    $message = "";
    $tipoMensaje = "";

        function ActualizarClave(){
            global $message;
            $btn_accion = $_POST['btn_accion'];
            $registrado = FALSE;
            if (isset($btn_accion) && $btn_accion == 'Actualizar') {
              $password = md5($_POST['password']);
              $password2 = md5($_POST['password2']);
              $password3 = md5($_POST['password3']);
                $ncon = ConexionDB();
                if ($ncon != NULL) {
                  if (isset($_POST['password']) == $password && $password2==$password3){					
                    $user = $_SESSION['user_name'];
                    if(actualizar_clave_seguro($ncon, $password, $password2, $password3)!= "");
                    
                    $registrado = TRUE;
                    $message .='Calve actualizada.';
                    $tipoMensaje = "";
                }else{
                  $registrado = TRUE;
                    $message .='Claves no coinciden.';
                    $tipoMensaje = "error";
                }
              }
                else {
                    $registrado = TRUE;
                    $message .='Clave NO Coinciden.';
                    $tipoMensaje = "error";
                }
            }				     
      }
        if (isset($_POST) && isset($_POST['btn_accion'])) {
            LimpiarEntradas();
            ActualizarClave();
        }
?>
<?php 
    $conexion=mysqli_connect('localhost','root','','proyecto_linea_2_database');
    $user = $_SESSION['user_name'];
    $sql="SELECT foto FROM usuarios WHERE usuario = '$user'";
    $result=mysqli_query($conexion,$sql);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Cambiar Clave</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
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
    
    <h1>Cambio de Clave</h1>

    <?php
      if ($message != "") 
        {
          echo '<div class="error_"' . $tipoMensaje . ' ">' . $message . '</div>';
        }
    ?>

    <form class="cambio_clave" action="" method="POST" enctype="multipart/form-data">
      <div>
      <label class="clave" for="password">Clave Actual:</label>
      <input name="password" type="password" required>
      </div>
      <div>
      <label class="clave" for="password2">Clave Nueva:</label>
      <input name="password2" type="password" required>
      </div>
      <div>
      <label class="clave" for="password3">Repetir Clave:</label>
      <input name="password3" type="password" required>
      </div>
      <div>
        <br>
      <input class="btnActualizar" type="submit" name="btn_accion" value="Actualizar">
      </div>
      <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['AntiCSRF'];?>">
    </form>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>