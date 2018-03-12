<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password)*/
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mhu3a');

//define('DB_SERVER', '10.169.0.158');
//define('DB_USERNAME', 'millhill1_mhv');
//define('DB_PASSWORD', 'Andy2rook');
//define('DB_NAME', 'millhill1_mhv'); 
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>