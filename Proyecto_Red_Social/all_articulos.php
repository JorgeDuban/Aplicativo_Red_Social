<?php
      require_once "librerias/sesion_segura.php";
	  	require_once "librerias/libreria.php";
    	require_once "conexion/database.php";
    	require_once "logica_registro.php";

      $_POST = LimpiarArray($_POST);
    
    ?>
    <?php 
    $conexion=mysqli_connect('localhost','root','','proyecto_linea_2_database');
    ?>
	
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Todos los Artículos</title>
    <!--<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">-->
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
  </head>
  <body>
        <div>
          <?php require_once "partials/header.php" ;
            $user = $_SESSION['user_name'];
            $sql="SELECT foto FROM usuarios WHERE usuario = '$user'";
            $result=mysqli_query($conexion,$sql);
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

        <?php if(!empty($message)): ?>
        <p> <?= $message ?></p>
        <?php endif; ?>

        <a href="all_articulos.php"><button  class="btn_titulo">Todos los Articulos</button></a>
        <a href="all_mis_articulos.php"><button class="btn_titulo">Mis Articulos</button></a>
        <a href="registro_articulo.php"><button value="Prueba" name="btn_accion" class="btn_titulo">Crear Articulo</button></a>
        
<!--------------------------------------------------------------------------------------------------->

<section>
    <br>
        <table border="1" >
          <thead>
            <tr>
                <th>Foto</th>
                <th>Usuario</th>
                <th>Articulo</th>
                <th>Fecha de Publicación</th>
            </tr>
            </thead>
            <?php 
            $user = $_SESSION['user_name'];
            $sql="SELECT autor, articulo, fecha_publicacion, publico, foto FROM  articulos inner join usuarios on autor = usuario WHERE `publico` = 'on' AND autor=usuario";
            $result=mysqli_query($conexion,$sql);
            while($mostrar=mysqli_fetch_array($result)){
              $foto = $mostrar['foto'];
            ?>
            <tr>  
                    <td><img src="<?php echo $foto?>" width="80"></td>
                    <td><?php echo $mostrar['autor'] ?></td>        
                    <td><?php echo $mostrar['articulo'] ?></td>
                    <td><?php echo $mostrar['fecha_publicacion'] ?></td>
            </tr>
            
        <?php 
        }
        ?>
        </table>
        <br>
        </section>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>