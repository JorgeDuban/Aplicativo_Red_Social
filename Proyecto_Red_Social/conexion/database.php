<?php
    function ConexionDB()
    {
      $server = 'localhost';
      $username = 'root';
      $password = '';
      $database = 'proyecto_linea_2_database';	
  
      $sql = "mysql:host=$server;dbname=$database;";
      $user_name = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
      // Create a new connection to the MySQL database using PDO, $conn is an object
      try { 
        $conn = new PDO($sql, $username, $password, $user_name);
        return $conn;
      } catch (PDOException $error) {
        //echo 'Connection error: ' . $error->getMessage();
        return NULL;
      }
    }
  ?>

 