<?php
      require_once "librerias/sesion_segura.php";
	  	require_once "librerias/libreria.php";
    	require_once "conexion/database.php";
    	require_once "logica_registro.php";
      AntiCSRF();
      //$_POST = LimpiarArray($_POST);
    
        $message = "";
        $tipo_mensaje = "";
          function borrar_articulo(){
            global $message;
            $conexion=mysqli_connect('localhost','root','','proyecto_linea_2_database');
            try{
                    if (isset($_POST['btn_borrar']))
                    {
                      if(empty($_POST['borrar']))
                      {
                        $message .='No se ha seleccionado ningun registro.';
                        $tipo_mensaje = "";
                      }
                      else{
                        foreach ($_POST['borrar'] as $id_borrar){
                          $borrar_registro= $conexion->query("DELETE from articulos WHERE id = '$id_borrar'");
                          //$borrar_registro->bindParam(':id_borrar', $id_borrar);
                          $message .='Registro eliminado con Éxito.</br>';
                          $tipo_mensaje = "";
                        }
                      }
                    }
                  }catch (\Throwable $th) {
                  } 
          }
          if (isset($_POST) && isset($_POST['btn_borrar'])) {
            borrar_articulo();
          }
          function publicar__articulo(){
            global $message;
            $conexion=mysqli_connect('localhost','root','','proyecto_linea_2_database');
            try{
                    if (isset($_POST['btn_publicar']))
                    {
                      if(empty($_POST['borrar']))
                      {
                        $message .='No se ha seleccionado ningun registro.';
                        $tipo_mensaje = "";
                      }
                      else{
                        foreach ($_POST['borrar'] as $id_publicar){
                          $publicar= $conexion->query("UPDATE articulos SET publico ='on' WHERE id = '$id_publicar'");
                          $message .='Registro publicado con Éxito.</br>';
                          $tipo_mensaje = "";
                        }
                      }
                    }
                  }catch (\Throwable $th) {
                  } 
          }
          if (isset($_POST) && isset($_POST['btn_publicar'])) {
            publicar__articulo();
          }
          function despublicar__articulo(){
            global $message;
            $conexion=mysqli_connect('localhost','root','','proyecto_linea_2_database');
                try{
                    if (isset($_POST['btn_despublicar']))
                    {
                      if(empty($_POST['borrar']))
                      {
                        $message .='No se ha seleccionado ningun registro.';
                        $tipo_mensaje = "";
                      }
                      else{
                        $despublico ='';
                        foreach ($_POST['borrar'] as $id_despublicar){
                          $despublicar= $conexion->query("UPDATE articulos SET publico ='$despublico' WHERE id = '$id_despublicar'");
                          $message .='Registro despublicado con Éxito.</br>';
                          $tipo_mensaje = "";
                        }
                      }
                    }
                  }catch (\Throwable $th) {
                  }
          }
          if (isset($_POST) && isset($_POST['btn_despublicar'])) {
            despublicar__articulo();
          }
    ?>
    <?php 
    $conexion=mysqli_connect('localhost','root','','proyecto_linea_2_database');
    //$sql="SELECT foto FROM usuarios WHERE usuario = '$user'";
    //$result=mysqli_query($conexion,$sql);
    ?>
	
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Mis Artículos</title>
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
  </head>
  <body>
        <div>
        <?php require_once "partials/header.php" ;
        try{
        $sesion=$_SESSION['user_name'];
        $sql="SELECT foto FROM usuarios WHERE usuario = '$sesion'";
        $result=mysqli_query($conexion,$sql);
          while($mostrar=mysqli_fetch_array($result)){
            ?>
            <?php echo "<img class='f_perfil' src='".$mostrar['foto']."' width='70'>"?>
            <?php 
            }
            echo '<div class="user"> '.LimpiarCadena($_SESSION['user_name']) . '.</div>
            <br>';
          }catch (\Throwable $th) {
          }
          ?>
          
        </div>
        <h1></h1>
        <a href="all_articulos.php"><button  class="btn_titulo">Todos los Artículos</button></a>
        <a href="all_mis_articulos.php"><button class="btn_titulo">Mis Artículos</button></a>
        <a href="registro_articulo.php"><button value="Prueba" name="btn_accion" class="btn_titulo">Crear Artículo</button></a>
          <br>
        <?php
            if ($message != "") 
            {
              echo '<div class="error_">' . $message . '</div>';
            }
        ?>
    
<!--------------------------------------------------------------------------------------------------->
 <form class ="form_art" method="post">
    </div>
    <br>
        <table border="1" >
          <thead>
            <tr>
                <th class="art"></th>
                <th>Foto</th>
                <th>Usuario</th>
                <th>Artículo</th>
                <th>Fecha de Publicación</th>
                <th>¿Público?(on=si - Campo Vacio=no)</th>
            </tr>
            </thead>
            <?php 
            $sql="SELECT t.id as id, autor, articulo, fecha_publicacion, publico, foto FROM  articulos as t inner join usuarios on autor = usuario WHERE `autor` = '$sesion'";
            $result=mysqli_query($conexion,$sql);
            while($mostrar=mysqli_fetch_array($result)){
              $foto = $mostrar['foto'];
            ?>
            <tr>
                    <td class="art"> <input class="tab art_check" type="checkbox" name="borrar[]" value="<?php echo $mostrar['id']?>"></input></td>
                    <td><img src="<?php echo $foto?>" width="80"></td>
                    <td><?php echo $mostrar['autor'] ?></td>        
                    <td><?php echo $mostrar['articulo'] ?></td>
                    <td><?php echo $mostrar['fecha_publicacion'] ?></td>
                    <td><?php echo $mostrar['publico'] ?></td>
            </tr>
        <?php 
        }
        ?>
        </table>
        <br>
        <a href="all_mis_articulos.php"><input class="btn" type="submit" class="button" name="btn_publicar" value="Publicar"></a>
        <a href="all_mis_articulos.php"><input class="btn" type="submit" class="button" name="btn_despublicar" value="Despublicar"></a>
        <a href="all_mis_articulos.php"><input class="btn" type="submit" class="button" name="btn_borrar" value="Borrar"></a>
        <input type="hidden" name="csrf_token" id="token_AntiCSRF" value="<?php echo $_SESSION['AntiCSRF'];?>">
      </form>
  </body>
  <script type="text/javascript" src="librerias/js/evitar_reenvio.js"></script>
</html>