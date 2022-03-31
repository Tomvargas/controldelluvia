<?php 

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'controllluvia';

$conn= mysqli_connect($servername, $username, $password, $database);

if (!$conn){
	die("Conexion fallida: ".mysqli_connect_error());
}

?>
