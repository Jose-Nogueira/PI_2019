<?php
require('vars.php');
if(isset($_GET["id"]) & isset($_GET["status"]) & isset($_GET["mode"])){
 try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	if(mysqli_connect_errno())
	{
        echo "false";
	}
	else{
		$id = mysqli_real_escape_string($sql, $_GET["id"]);
        $status = mysqli_real_escape_string($sql, $_GET["status"]);
        $mode = mysqli_real_escape_string($sql, $_GET["mode"]);
        $result = mysqli_query($sql,"INSERT INTO `status`(`id_setor`, `status`, `mode`) VALUES (" . $id . "," . $status . "," . $mode . ")");
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