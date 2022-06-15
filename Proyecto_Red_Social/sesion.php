<?php
  require_once "librerias/sesion_segura.php";
  require_once "conexion/database.php";
  require_once "librerias/libreria.php";

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
    <title>Inicio</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <?php require 'partials/header2.php' ?>
    </br>
    </br>
    <?php if(!isset($_SESSION['user_name'])){
      echo'
      <a href="login.php"></a>'
      ;} 
    else {
			echo '<div class="tex"> Bienvenido ' . LimpiarCadena($_SESSION['user_name']) . '.</div>';
     
            while($mostrar=mysqli_fetch_array($result)){
              ?>
              <?php echo "<img class='f_perfil' src='".$mostrar['foto']."' width='70'>"?>
              <?php 
        }
      }
        ?>
        
    </br>
    </br>
    </br>
    <a class="btn" href="all_articulos.php">Ver Articulos</a>
    <a class="btn" href="mensajes_recibidos.php">Ver Mensajes</a>
    <a class="btn" href="perfil.php">Mi Perfil</a>
      
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>