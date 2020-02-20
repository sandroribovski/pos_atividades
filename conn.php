<?php

$serverName = "10.238.176.114,1433";
$connectionOptions = array("Database"=>"monitoracao", "Uid"=>"Sandro", "PWD"=>"12310");

 
 $conn = sqlsrv_connect($serverName, $connectionOptions);
 
 if($conn){
	//echo "Database Connected.<br />";
}else{
 echo "Fail.<br />";
die( print_r(sqlsrv_errors(), true));
}
	
?>

