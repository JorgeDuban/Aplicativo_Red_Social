
<?php
    require_once "librerias/sesion_segura.php";
	  require_once "librerias/libreria.php";
    require_once "conexion/database.php";
    require_once "logica_registro.php";
    AntiCSRF();
    $_POST = LimpiarArray($_POST);
    
        $message = "";
        $tipo_mensaje = "";
            function RegistrarArticulo(){
                global $message;
                try{
                $btn_accion = $_POST['btn_accion'];
              
                $registrado = FALSE;
                if (isset($btn_accion) && $btn_accion == 'Crear') {
                  $articulo = $_POST['articulo'];
                  $publico = $_POST['publico'];
                    $conn = ConexionDB();
                    if ($conn != NULL) {					
                      registrar_articulo_seguro($conn, $articulo, $publico);
                        $registrado = TRUE;
                        $message .='Articulo registrado.';
                        $tipo_mensaje = "";
                    }
                    else {
                        $registrado = TRUE;
                        $message .='Articulo NO registrado.';
                        $tipo_mensaje = "error";
                    }				
                }
              } catch (\Throwable $th) {
              }
          }
            if (isset($_POST) && isset($_POST['btn_accion'])) {
                LimpiarEntradas();
                RegistrarArticulo();
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
    <title>Registrar Artículo</title>
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

        <a href="all_articulos.php"><button  class="btn_titulo">Todos los Articulos</button></a>
        <a href="all_mis_articulos.php"><button class="btn_titulo">Mis Articulos</button></a>
        <a href="registro_articulo.php"><button value="Prueba" name="btn_accion" class="btn_titulo">Crear Articulo</button></a>

        <?php
            if ($message != "") 
            {
              echo '<div class="error_">' . $message . '</div>';
            }
        ?>
<!--------------------------------------------------------------------------------------------------->
    <form method="post">
      <div>
    <label for="articulo" class="form-element">Articulo:</label>
    <input class="input_texto" name="articulo" type="text" placeholder="Ingrese descripción" required>
    <label for="publico" name="publico" class="form-element">Es público:</label>
    <input class="chek" type="checkbox" name="publico">
    </div>
    <br>
    <input class="btn" type="submit" class="button" name="btn_accion" value="Crear">
    </div>
    <input type="hidden" name="csrf_token" id="token_AntiCSRF" value="<?php echo $_SESSION['AntiCSRF'];?>">
    </form>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>