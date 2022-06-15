<?php
    require_once "librerias/sesion_segura.php";
		require_once "librerias/libreria.php";
    require_once "conexion/database.php";
    require_once "logica_registro.php";
      
        $tipo_mensaje = "";
        
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
    <title>Mensajes Recibidos</title>
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
    
  </head>
  <body>
  <div >
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


        <section>
    </div>
    <br>
        <table border="1" >
            <thead>
            <tr>
                <th>Foto</th>
                <th>Usuario</th>
                <th>Mensaje</th>
                <th>Fecha de Envio</th>
                <th>Archivo</th>
            </tr>
            </thead>
            <?php 
            $user = $_SESSION['user_name'];
            //$sql="SELECT autor, mensaje, fecha_envio, foto FROM  mensajes WHERE `destinario` = '$user'";

            $sql="SELECT u.foto as foto_u, autor, mensaje, fecha_envio, m.foto as foto_m, usuario FROM  mensajes m inner join usuarios u on destinario = usuario WHERE destinario='$user'";
            $result=mysqli_query($conexion,$sql);

            while($mostrar=mysqli_fetch_array($result)){
              $foto_u = $mostrar['foto_u'];
              $foto_m = $mostrar['foto_m'];
            ?>

            <tr>
                    <td> <img src="<?php echo $foto_u?>" width="80"></td>
                    <td><?php echo $mostrar['autor'] ?></td>
                    <td><?php echo $mostrar['mensaje'] ?></td>
                    <td><?php echo $mostrar['fecha_envio'] ?></td>
                    <td> <img src="<?php echo $foto_m?>" width="80"></td>
            </tr>
        <?php 
        }
        ?>
        </table>
        </section>
        <br>
      </div>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>