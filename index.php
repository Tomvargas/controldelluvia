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
  $cmt= test_input($_POST['comentario']);
  if (!empty($cmt)){
    require 'userdb.php';

    $sql2 = "UPDATE registro SET REG_COMENTARIO = '$cmt' WHERE REG_ID = (SELECT max(REG_ID) FROM registro);";
    mysqli_query($conn, $sql2);
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Lluvias ácidas</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    .txtarea{
      width: 90%;
    }
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
      .responsive {
        width: 99%;
        height: auto;
      }
      .btnIdx{
        text-decoration: none;
        color: white; background-color: green;
        padding: 20px;
        border-radius: 40px;
        margin: 30px;
        position: relative;
        top: -200px;
        box-shadow: 0px 0px 40px black;
      }
      .log{
        padding: 3px;
        background-color: rgb(67, 255, 49);
        text-align: right;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        }
      .container {
        padding: 20px;
        padding-top: 0px;
        margin-top: -10px;
      }

        .update{
          text-decoration: none;
          padding: 5px;
          padding-inline: 20px;
          border-radius: 20px;
          background-color: green;
          color: white;
          font-weight: bold;
        }
        

        .bx {
            display: inline-block;
            margin-inline: 50px;
            background-color: white;            
            width: 260px;
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
            border-radius: 30px;
            box-shadow: 0px 0px 10px grey;
            font-size: 22px;
        }

        .bx-lluvia {
          background-image: url("./assets/rain.gif");
          background-size:cover;
          color: white;
          font-weight: bold;
          text-shadow: 0px 0px 4px black;
        }
        .bx-techo {
          background-image: url("./assets/techo.gif");
          background-size:cover;
          color: white;
          font-weight: bold;
          text-shadow: 0px 0px 4px black;
        }
        .bx-ph {
          background-image: url("./assets/ph.png");
          background-size:cover;
          color: white;
          font-weight: bold;
          text-shadow: 0px 0px 4px black;
        }


    </style>
    
  </head>
  <body>
    <?php require 'partials/header.php'; ?>

    <?php if(!empty($Email)): 
      require 'userdb.php';         
                     
    ?>

        <?php if($Type_usr == "adm"): ?> 

          <a class="nav-options" href="logout.php"> CERRAR SESIÓN </a>
          <a class="nav-options" href="/control"> INICIO </a>
          <a class="nav-options" href="signup.php"> REGISTRAR USUARIO </a>
          <a class="nav-options" href="./adm_pages/usersreg.php"> LISTA DE USUARIOS </a>
          <a class="nav-options" href="./adm_pages/reportes.php"> REPORTES </a>

          <h3 class="btn"> Bienvenido <spam style="color: rgb(67, 255, 49) ;"> <?= $Email ?> </spam></h3>
          <div>
            <a class="update" href="/control">actualizar</a>

            <div class="fecha">
                <h4>FECHA</h4>
                <?php echo $fecha ?>
              </div>

              <div class="bx bx-techo">
                <h5>ESTADO DEL TECHO</h5>
                <?php if($techo == 1){
                  echo "Abierto";
                }else{
                  echo "Cerrado"; 
                } ?>
              </div>

              <div class="bx bx-lluvia">
                <h5>ESTADO DE LA LLUVIA</h5>
                <?php if($lluvia == 1){
                  echo "Lluvia detectada";
                }else{
                  echo "No hay lluvia"; 
                } ?>
              </div>

              <div class="bx bx-ph">
                <h5>VALOR DE PH</h5>
                
                  <?php if($ph == 25){
                    echo "Sin lectura";
                  }else{
                    echo $ph; 
                  } 
                  ?>

              </div>



            <form action="index.php" method="POST">
              <textarea class="txtarea" name="comentario" placeholder="observación del cultivo"><?php 
                $sql2 = "SELECT REG_COMENTARIO FROM registro WHERE REG_ID = (SELECT max(REG_ID) FROM registro);";
                $values2=mysqli_query($conn, $sql2);
                $ROW2 = mysqli_fetch_array($values2);
                echo $ROW2['REG_COMENTARIO'];
              ?></textarea>
              <button class="nav-options" type="submit" name="btncmnt">GUARDAR COMENTARIO</button>
            </form>
          </div>

        <?php elseif($Type_usr == "usr"): ?>

          <a class="nav-options" href="logout.php"> CERRAR SESIÓN </a>
          <a class="nav-options" href="./adm_pages/reportes.php"> REPORTES </a>
          <h3 class="btn"> Bienvenido <spam style="color: rgb(67, 255, 49) ;"> <?= $Email ?> </spam></h3>

          <div>
            <a class="update" href="/control">actualizar</a>

            <div class="fecha">
                <h4>FECHA</h4>
                <?php echo $fecha ?>
              </div>

              <div class="bx bx-techo">
                <h5>ESTADO DEL TECHO</h5>
                <?php if($techo == 1){
                  echo "Abierto";
                }else{
                  echo "Cerrado"; 
                } ?>
              </div>

              <div class="bx bx-lluvia">
                <h5>ESTADO DE LA LLUVIA</h5>
                <?php if($lluvia == 1){
                  echo "Lluvia detectada";
                }else{
                  echo "No hay lluvia"; 
                } ?>
              </div>

              <div class="bx bx-ph">
                <h5>VALOR DE PH</h5>
                
                  <?php if($ph == 25){
                    echo "Sin lectura";
                  }else{
                    echo $ph; 
                  } 
                  ?>
              </div>
              </div>
            <p>Observación del administrador</p>
            <textarea disabled class="txtarea" name="comentario" placeholder="observación del cultivo"><?php 
                  $sql2 = "SELECT REG_COMENTARIO FROM registro WHERE REG_ID = (SELECT max(REG_ID) FROM registro);";
                  $values2=mysqli_query($conn, $sql2);
                  $ROW2 = mysqli_fetch_array($values2);
                  echo $ROW2['REG_COMENTARIO'];
                ?></textarea>
          </div>

        <?php else: ?>
          <h3>SE HA DETECTADO UN ERROR EN SU CUENTA, POR FAVOR COMUNICARSE CON EL ADMINISTRADOR</h3>
          <a class="log" href="logout.php"> CERRAR SESIÓN </a>
          <?php endif; ?>      
    <?php else: ?>
      <h3>COMIENZA A USAR LA PLATAFORMA</h3>
      <img src="./assets/suelo.jpg" class="responsive" alt="">
      <a class="btnIdx" href="login.php">INICIAR SESIÓN</a>
    <?php endif; ?>
  </body>
</html>
