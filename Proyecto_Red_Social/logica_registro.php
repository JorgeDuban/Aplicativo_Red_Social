<?php
   require_once "conexion/database.php";

   function Login($conn, $usuario, $clave)
    {
        $stm = 
            $conn->prepare("SELECT `usuario`, nombre FROM `usuarios` WHERE `usuario` = :Usuario and `password` = :Password");
        
        $stm->bindParam(':Usuario', $usuario); 
        $stm->bindParam(':Password', $clave);
        $stm->execute();
        $user =$stm->fetch();
        if($user){
            return $user['usuario'];
        }
        return '';
    }

   function Login__($conn, $usuario, $clave)
    {
        $token = '';
        $stament = 
            $conn->prepare("SELECT `usuario` FROM `usuarios` WHERE `usuario` = :usuario and `password` = :password");
        $stament->execute(['usuario' => $usuario, 'password' => $clave]);
        $user = $stament->fetch();		
        if ($user) {
            $token = md5(time() . $usuario);
            //$query = "UPDATE usuarioss  SET token = '$token' WHERE 'usuario'='$usuario';";
            //$verifica = parent::nonQuery($query);
            //$ins = $conn->prepare("UPDATE 'usuarioss' SET 'token' ='$token' WHERE 'usuario'='$usuario'");       
            //$ins->execute();
            // $user = $ins->fetch();
        }
        echo $token;
        return $token;
    }

    function registrar_usuario($conn, $nombre, $apellidos, $correo, $direccion, $numero_hijos, $estado_civil, $usuario, $password){
		$Token = '';
		$stm = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, correo, direccion, numero_hijos, estado_civil, usuario, password)  VALUES ('$nombre', '$apellidos', '$correo', '$direccion', '$numero_hijos', '$estado_civil', '$usuario', '$password')");
		
		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function RegistrarUsuarioDB($conn, $nombre, $apellidos, $correo, $direccion, $numero_hijos, $estado_civil, $usuario, $password, $foto){
        try{
        $stm = 
            $conn->prepare("INSERT INTO `usuarios` (nombre, apellidos, correo, direccion, numero_hijos, estado_civil, usuario, password, foto) VALUES (:nombre, :apellidos, :correo, :direccion, :numero_hijos, :estado_civil, :usuario, :password, :foto)");

        $stm->bindParam(':nombre', $nombre);
        $stm->bindParam(':apellidos', $apellidos); 
        $stm->bindParam(':correo', $correo);
		$stm->bindParam(':direccion', $direccion);
        $stm->bindParam(':numero_hijos', $numero_hijos);
        $stm->bindParam(':estado_civil', $estado_civil); 
        $stm->bindParam(':usuario', $usuario); 
        $stm->bindParam(':password', $password);
        $stm->bindParam(':foto', $foto);   
        }
        catch (\Throwable $th) {
        }
        try {           
            if ($stm->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

    function actualizar_usuario($conn, $nombre, $apellidos, $correo, $direccion, $numero_hijos, $estado_civil, $usuario, $foto){
        $user = $_SESSION['user_name'];
        $stm = 
            $conn->prepare("UPDATE usuarios SET nombre='$nombre', apellidos='$apellidos', correo='$correo', direccion='$direccion', numero_hijos='$numero_hijos', estado_civil='$estado_civil', foto='$foto' WHERE usuario='$user'");

            if ($stm->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
    }

    function actualizar_usuario_seguro($conn, $nombre, $apellidos, $correo, $direccion, $numero_hijos, $estado_civil, $usuario, $foto){
        $user = $_SESSION['user_name'];
        $stm = 
            $conn->prepare("UPDATE usuarios SET nombre=:nombre, apellidos=:apellidos, correo=:correo, direccion=:direccion, numero_hijos=:numero_hijos, estado_civil=:estado_civil, foto=:foto WHERE usuario=:usuario");

        $stm->bindParam(':nombre', $nombre);
        $stm->bindParam(':apellidos', $apellidos); 
        $stm->bindParam(':correo', $correo);
		$stm->bindParam(':direccion', $direccion);
        $stm->bindParam(':numero_hijos', $numero_hijos);
        $stm->bindParam(':estado_civil', $estado_civil); 
        $stm->bindParam(':usuario', $user);
        $stm->bindParam(':foto', $foto);   
        
        try {           
            if ($stm->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

    function actualizar_clave($conn, $password, $password2, $password3){
        $user = $_SESSION['user_name'];
        $stament = 
            $conn->prepare("UPDATE usuarios SET password='$password3' WHERE `usuario` = '$user'");	

        if ($stament->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function actualizar_clave_seguro($conn, $password, $password2, $password3){
        $user = $_SESSION['user_name'];
        $stm = 
            $conn->prepare("UPDATE usuarios SET password=:password3 WHERE `usuario` = :user");

        $stm->bindParam(':password3', $password3);
        $stm->bindParam(':user', $user);
        
        try {           
            if ($stm->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

    function mostrar_articulos($conn, $autor, $articulo, $fechca_publicacion){
		$stm = $conn->prepare("SELECT autor, articulo, fecha_publicacion FROM  articulos");

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function mostrar_mis_articulos($conn, $articulo, $fechca_publicacion, $publico){
        $user = $_SESSION['user_name'];
		$stm = $conn->prepare("SELECT articulo, fecha_publicacion, publico FROM  articulos WHERE `autor` = '$user'");

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function publicar_articulo($conn){
        $user = $_SESSION['user_name'];
        $id = $conn->prepare("SELECT id FROM articulos WHERE `autor` = '$user'");

		if ($id->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
        $publico='on';
		$stm = $conn->prepare("UPDATE artiulos SET publico='$publico' WHERE `id` = '$id'");

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function despublicar_articulo($conn){
        $user = $_SESSION['user_name'];
        $id = $conn->prepare("SELECT id FROM articulos WHERE `autor` = '$user'");

		if ($id->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
        $publico='';
		$stm = $conn->prepare("UPDATE artiulos SET publico='$publico' WHERE `id` = '$id'");

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    /*function borrar_articulo($conn, $articulo, $fechca_publicacion, $publico){
        $user = $_SESSION['user_name'];
		$stm = $conn->prepare("DELETE * FROM articulos WHERE `autor` = '$user'");

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }*/

    function registrar_articulo($conn, $articulo, $publico){
        $user = $_SESSION['user_name'];
        $fecha_publicacion = date("Y-m-d h:i:s A");
		$stm = $conn->prepare("INSERT INTO articulos (autor, articulo, publico, fecha_publicacion)  VALUES ('$user', '$articulo', '$publico', '$fecha_publicacion')");
		
		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function registrar_articulo_seguro($conn, $articulo, $publico){
        $user = $_SESSION['user_name'];
        $fecha_envio = date("Y-m-d h:i:s A");
        $stm = 
            $conn->prepare("INSERT INTO `articulos` (autor, articulo, publico, fecha_publicacion) VALUES (:user, :articulo, :publico, :fecha_envio)");

        $stm->bindParam(':user', $user);
        $stm->bindParam(':articulo', $articulo);
		$stm->bindParam(':publico', $publico);
        $stm->bindParam(':fecha_envio', $fecha_envio);
        
        try {           
            if ($stm->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

    function mostrar_mensajes_recibidos($conn, $autor, $mensaje, $fecha_envio){
        $user = $_SESSION['user_name'];
		$stm = $conn->prepare("SELECT autor, mensaje, fecha_envio FROM  mensajes WHERE `destinario` = 'Duban'");

        //$stm1->$stm;

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function mostrar_mensajes_enviados($conn, $destinario, $mensaje, $fecha_envio){
        $user = $_SESSION['user_name'];
		$stm = $conn->prepare("SELECT destinario, mensaje, fecha_envio FROM  mensajes WHERE `autor` = '$user'");

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function enviar_mensaje($conn, $destinario, $mensaje, $foto){
        $user = $_SESSION['user_name'];
        $fecha_envio = date("Y-m-d h:i:s A");
		$stm = $conn->prepare("INSERT INTO mensajes (autor, destinario, mensaje, fecha_envio, foto)  VALUES ('$user', '$destinario', '$mensaje', '$fecha_envio', '$foto')");

		if ($stm->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function enviar_mensaje_seguro($conn, $destinario, $mensaje, $foto){
        $user = $_SESSION['user_name'];
        $fecha_envio = date("Y-m-d h:i:s A");
        $stm = 
            $conn->prepare("INSERT INTO `mensajes` (autor, destinario, mensaje, fecha_envio, foto) VALUES (:user, :destinario, :mensaje, :fecha_envio, :foto)");

        
        $stm->bindParam(':user', $user);
        $stm->bindParam(':destinario', $destinario);
		$stm->bindParam(':mensaje', $mensaje);
        $stm->bindParam(':fecha_envio', $fecha_envio);
        $stm->bindParam(':foto', $foto);
        try {           
            if ($stm->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

?>