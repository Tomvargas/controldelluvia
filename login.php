<?php
  error_reporting(E_ERROR | E_PARSE);
  
  include('input.php');
  session_start();

  if (isset($_SESSION['USR_ID'])) {
    header('Location: /index.php');
  }
  require 'database.php';

  $email= test_input($_POST['email']);
  $password= test_input($_POST['password']);

  if (!empty($email) && !empty($password)) {
    $consulta = "SELECT USR_ID, USR_NOMBRE, USR_PASS, USR_TIPO FROM usuario where USR_NOMBRE = '$email'";
    $values=mysqli_query($conn, $consulta);
    $ROW = mysqli_fetch_array($values);
    $Id = $ROW['USR_ID'];
    $Email = $ROW['USR_NOMBRE'];
    $Pass = $ROW['USR_PASS'];
    $Type = $ROW['USR_TIPO'];

    if ($password == $Pass) {
      $_SESSION['USR_ID'] = $Id;
      header("Location: /control");
    } else {
      $message = 'No está registrado...';
    }
  }

?> 

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Iniciar sesión</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <h1>INICIAR SESIÓN</h1>
    <span>¿No tienes una cuenta? Pide a tu administrador una.</span>

    <form action="login.php" method="POST">
      <input name="email" type="text" placeholder="Nombre de Usuario">
      <input name="password" type="password" placeholder="Contraseña">
      <input style="background-color: green;" type="submit" value="INICIAR SESIÓN">
    </form>
  </body>
</html>
