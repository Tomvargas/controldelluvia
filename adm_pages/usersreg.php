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

        #btnExport {
        padding: 5px;
        padding-inline: 30px;
      }
    </style>
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
          <a class="nav-options-active" href="#"> ADMINISTRAR USUARIOS </a>
          <a class="nav-options" href="reportes.php"> REPORTES </a>

          <h3 class="btn"> USUARIOS REGISTRADOS </h3>
          <input type="button" id="btnExport" value="GENERAR PDF" onclick="Open()" />
          <input type="button" id="btnExport" value="DESCARGAR LISTA" onclick="Export()" />

          <hr/>

          <table id="usuarios">
            <tr>
                <th>ID</th>
                <th>Nombre de usuario</th>
                <th>Tipo de usuario</th>
            </tr>
            <?php
                $consulta = "SELECT * FROM usuario";
                $values=mysqli_query($conn, $consulta);
                while ($ROW = mysqli_fetch_array($values)){

                    $ID = $ROW['USR_ID'];
                    $NOMBRE = $ROW['USR_NOMBRE'];
                    $TIPO = $ROW['USR_TIPO'];
                    if($TIPO == "adm"){
                        $TIPO = "ADMINISTRADOR";
                    }elseif($TIPO == "usr"){
                        $TIPO = "USUARIO NORMAL";
                    }else{
                        $TIPO = "INDEFINIDO";
                    }

                    echo "<tr>";
                        echo "<th>$ID</th>";
                        echo "<th>$NOMBRE</th>";
                        echo "<th>$TIPO</th>";                        
                    echo "</tr>";
                }
            ?>
          </table>

        <?php elseif($Type_usr == "usr"): ?>

            <?php header("Location: /control"); ?>

        <?php else: ?>
            <?php header("Location: /control"); ?>
        <?php endif; ?>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
          <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
          <script>
            function Export() {
             FILENAME = "USUARIOS.pdf"
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
