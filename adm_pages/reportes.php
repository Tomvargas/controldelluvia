<?php
  error_reporting(E_ERROR | E_PARSE);
  session_start();
  include('../input.php');
  require '../database.php';
  $id = $_SESSION['USR_ID'];
  if (isset($_SESSION['USR_ID'])) {
    $sql = "SELECT USR_ID , USR_NOMBRE, USR_PASS, USR_TIPO FROM usuario WHERE USR_ID  = $id";
    $values=mysqli_query($conn, $sql);
    $ROW = mysqli_fetch_array($values);
    $Id = $ROW['USR_ID '];
    $Email = $ROW['USR_NOMBRE'];
    $Type_usr = $ROW['USR_TIPO'];
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Administrar Usuarios</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            box-shadow: 0px 0px 10px grey;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        tr:first-child {
            background-color: green;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
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
      #btnExport {
        padding: 5px;
        padding-inline: 30px;
      }
    </style>
    <script>
      
    </script>
</head>

<body>
    <?php require '../partials/header.php'; ?>

    <?php  
      require '../userdb.php';            
    ?>

        <?php if($Type_usr == "adm"): ?>

          <a class="nav-options" href="../logout.php"> CERRAR SESIÓN </a>
          <a class="nav-options" href="/control"> INICIO </a>
          <a class="nav-options" href="../signup.php"> REGISTRAR USUARIO </a>
          <a class="nav-options" href="usersreg.php"> ADMINISTRAR USUARIOS </a>
          <a class="nav-options-active" href="#"> REPORTES </a>

          <h2 class="btn"> REGISTROS </h2>

          <input type="button" id="btnExport" value="GENERAR PDF" onclick="Open()" />
          <input type="button" id="btnExport" value="DESCARGAR REGISTRO" onclick="Export()" />

          <table id="usuarios" cellspacing="0" cellpadding="0">
          <hr/>
            
            <tr>
                <th>FECHA</th>
                <th>LLUVIA REGISTRADA</th>
                <th>PH DE LLUVIA</th>
                <th>ESTADO DEL TECHO</th>
                <th>OBSERVACIONES DEL ADMINISTRADOR</th>
            </tr>
            <?php
                $consulta = "SELECT * FROM registro";
                $values=mysqli_query($conn, $consulta);
                while ($ROW = mysqli_fetch_array($values)){

                    $FECHA = $ROW['REG_FECHA'];
                    $LLUVIA = $ROW['REG_LLUVIA'];
                    $PH = $ROW['REG_PH'];
                    $TECHO = $ROW['REG_TECHO'];
                    $COMENTARIO = $ROW['REG_COMENTARIO'];

                    if($LLUVIA == "1"){
                        $LLUVIA = "SI";
                    }elseif($LLUVIA == "0"){
                        $LLUVIA = "NO";
                        $PH = "SIN REGISTRO";
                    }else{
                        $LLUVIA = "DESCONOCIDO";
                    }

                    if($TECHO == "1"){
                      $TECHO = "ABIERTO";
                  }elseif($TECHO == "0"){
                      $TECHO = "CERRADO";
                  }else{
                      $TECHO = "DESCONOCIDO";
                  }

                    echo "<tr>";
                        echo "<th>$FECHA</th>";
                        echo "<th>$LLUVIA</th>";
                        echo "<th>$PH</th>";
                        echo "<th>$TECHO</th>";   
                        echo "<th>$COMENTARIO</th>";                       
                    echo "</tr>";
                }
            ?>
          </table>

        <?php elseif($Type_usr == "usr"): ?>

            <a class="nav-options" href="../logout.php"> CERRAR SESIÓN </a>
          <a class="nav-options" href="/control"> INICIO </a>
          <a class="nav-options-active" href="#"> REPORTES </a>

          <h2 class="btn"> REGISTROS </h2>

          <table id="usuarios" cellspacing="0" cellpadding="0">
          <hr/>
            
            <tr>
                <th>FECHA</th>
                <th>LLUVIA REGISTRADA</th>
                <th>PH DE LLUVIA</th>
                <th>ESTADO DEL TECHO</th>
                <th>OBSERVACIONES DEL ADMINISTRADOR</th>
            </tr>
            <?php
                $consulta = "SELECT * FROM registro";
                $values=mysqli_query($conn, $consulta);
                while ($ROW = mysqli_fetch_array($values)){

                    $FECHA = $ROW['REG_FECHA'];
                    $LLUVIA = $ROW['REG_LLUVIA'];
                    $PH = $ROW['REG_PH'];
                    $TECHO = $ROW['REG_TECHO'];
                    $COMENTARIO = $ROW['REG_COMENTARIO'];

                    if($LLUVIA == "1"){
                        $LLUVIA = "SI";
                    }elseif($LLUVIA == "0"){
                        $LLUVIA = "NO";
                        $PH = "SIN REGISTRO";
                    }else{
                        $LLUVIA = "DESCONOCIDO";
                    }

                    if($TECHO == "1"){
                      $TECHO = "ABIERTO";
                  }elseif($TECHO == "0"){
                      $TECHO = "CERRADO";
                  }else{
                      $TECHO = "DESCONOCIDO";
                  }

                    echo "<tr>";
                        echo "<th>$FECHA</th>";
                        echo "<th>$LLUVIA</th>";
                        echo "<th>$PH</th>";
                        echo "<th>$TECHO</th>";   
                        echo "<th>$COMENTARIO</th>";                       
                    echo "</tr>";
                }
            ?>
          </table>

        <?php else: ?>
            <?php header("Location: /control"); ?>
        <?php endif; ?>

          <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
          <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
          <script>
            function Export() {
              const months = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
              const d = new Date();
              Y = d.getFullYear();
              M = months[d.getMonth()];
              D = d.getDay();
              FILENAME = "Registro-"+D+"-"+M+"-"+Y+".pdf"
              html2canvas(document.getElementById('usuarios'), {
                  onrendered: function (canvas) {
                      var data = canvas.toDataURL();
                      var docDefinition = {
                          content: [{
                              image: data,
                              width: 500
                          }]
                      };
                      pdfMake.createPdf(docDefinition).download(FILENAME);
                  }
              });
            }

            function Open() {
              const months = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
              const d = new Date();
              Y = d.getFullYear();
              M = months[d.getMonth()];
              D = d.getDay();
              FILENAME = "Registro-"+D+"-"+M+"-"+Y+".pdf"
              //var win = window.open('', '_blank');
              html2canvas(document.getElementById('usuarios'), {
                  onrendered: function (canvas) {
                      var data = canvas.toDataURL();
                      var docDefinition = {
                          content: [{
                              image: data,
                              width: 500
                          }]
                      };
                      pdfMake.createPdf(docDefinition).open();
                  }
              });
            }

            
          </script>
   
  </body>
</html>