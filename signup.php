<?php
  error_reporting(E_ERROR | E_PARSE);
  session_start();
  include('input.php');
  require 'database.php';
  $id = $_SESSION['USR_ID'];
  if (isset($_SESSION['USR_ID'])) {
    $sql = "SELECT USR_ID , USR_NOMBRE, USR_PASS, USR_TIPO FROM usuario WHERE USR_ID  = $id";
    $values=mysqli_query($conn, $sql);
    $ROW = mysqli_fetch_array($values);
    $Id = $ROW['USR_ID '];
    $Email = $ROW['USR_NOMBRE'];
    $Type_usr = $ROW['USR_TIPO'];
  }

  $message = '';
  //$email= test_input($_POST['email']);
  $nombre= test_input($_POST['username']);
  $password= test_input($_POST['pass']);
  $Cpassword= test_input($_POST['cpass']);
  $typeUsr= test_input($_POST['type']);

  if (!empty($nombre) && !empty($password) && !empty($Cpassword) ) {
    
      $sql = "INSERT INTO usuario ( USR_NOMBRE, USR_PASS, USR_TIPO ) VALUES ('$nombre', '$password', '$typeUsr')";
      try{
        mysqli_query($conn, $sql);
        $message = "Registro exitoso";
      }
      catch(Exception $e){
        $message = "Registro fallido";
      }
    }
    else{
      $message = "Por favor no dejar campos vacios.";
    }    
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>SignUp</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style> 
      .nav-options{
        padding-inline: 50px;
        text-decoration: none;
        color: green;
        font-weight: bold;
      }
      .nav-options:hover{
        color: white;
        background-color: green;
        border-radius: 10px;
        transition-duration: 2s;
      }
      .nav-options-active{
        padding-inline: 50px;
        text-decoration: none;
        font-weight: bold;
        color: white;
        background-color: green;
      }
    </style>
  </head>
  <body>

    <?php require 'partials/header.php';
    //echo "<script> alert('tipo:'+'$typeUsr') </script>";
      if(!empty($Email)):
    ?>      

    <?php if($Type_usr == "adm"): ?>
      
          <a class="nav-options" href="logout.php"> CERRAR SESIÓN </a>
          <a class="nav-options" href="/control"> INICIO </a>
          <a class="nav-options-active" href="signup.php"> REGISTRAR USUARIO </a>
          <a class="nav-options" href="./adm_pages/usersreg.php"> LISTA DE USUARIOS </a>
          <a class="nav-options" href="./adm_pages/reportes.php"> REPORTES </a> 

          <h1>REGISTRO DE USUARIO</h1>
          
          <?php if(!empty($message)){
            echo "<p> $message </p>";
            } ?>
      
          <form action="signup.php" method="POST">

            <input name="username" type="text" placeholder="Nombre del usuario">
            <input name="pass" type="password" placeholder="contraseña">
            <input name="cpass" type="password" placeholder="Confirmar contraseña">
            ROL DE USUARIO
            <select id="typeusr" name="type">
              <option value="adm">Administrador</option>
              <option value="usr">Usuario</option>
            </select>
            <input style="background-color: green" type="submit" value="REGISTRAR">
          </form>
    <?php elseif($Type_usr == "usr"): ?>
          <?php header("Location: /control"); ?>
    <?php endif; ?> 

    <?php else: ?>
      <?php header("Location: /control"); ?>
    <?php endif; ?>

  </body>
</html>
