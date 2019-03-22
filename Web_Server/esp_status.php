<?php
require('vars.php');
if(isset($_GET["id"]) & isset($_GET["gold"]) & isset($_GET["pid_in"]) & isset($_GET["pid_out"])){
 try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	if(mysqli_connect_errno())
	{
        echo "false";
	}
	else{
		$id = mysqli_real_escape_string($sql, $_GET["id"]);
        $in = mysqli_real_escape_string($sql, $_GET["pid_in"]);
        $out = mysqli_real_escape_string($sql, $_GET["pid_out"]);
        $gold = mysqli_real_escape_string($sql, $_GET["gold"]);
        $result = mysqli_query($sql,"INSERT INTO `esp_stats`(`id_esp`, `gold`, `pid_in`, `pid_out`) VALUES (" . $id . "," . $gold . "," . $in . "," . $out . ")");
        if($result) echo("valid");
        else echo("false");	
		}
	}
	catch(Exception $e){
        echo "false";	
	}
}else{
    echo "false";
}
?>
