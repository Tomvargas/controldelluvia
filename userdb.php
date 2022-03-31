<?php
require 'database.php';
session_start();

$fecha = "empty";
$lluvia = "empty";
$ph = "empty";
$techo = "empty";
$comentario = "empty";

if (isset($_SESSION['USR_ID'])) {
	$id = $_SESSION['USR_ID'];
    $consulta = "SELECT REG_FECHA, REG_LLUVIA, REG_PH, REG_TECHO, REG_COMENTARIO FROM registro where REG_ID  = (SELECT max(REG_ID) FROM registro)";
    //echo "<script> alert(' user : '+'$id') </script>";
	$values=mysqli_query($conn, $consulta);
	$ROW = mysqli_fetch_array($values);
	$fecha = $ROW['REG_FECHA'];
	$lluvia = $ROW['REG_LLUVIA'];
    $ph = $ROW['REG_PH'];
    $techo = $ROW['REG_TECHO'];
    $comentario = $ROW['REG_COMENTARIO'];

    //echo "<script> alert(' user : '+'$userdb') </script>";
        
}

?>