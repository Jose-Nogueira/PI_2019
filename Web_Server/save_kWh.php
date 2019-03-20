<?php
require('vars.php');
if(isset($_GET["id"]) & isset($_GET["w"])){
 try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$id = mysqli_real_escape_string($sql, $_GET["id"]);
		$w = mysqli_real_escape_string($sql, $_GET["w"]);
		$result = mysqli_query($sql,"insert into `kw_h`(`id_setor`, `w`) values (".$id.",".$w.") ");
		if($result) echo("valid");
else echo("false");	
		}
	}
	catch(Exception $e){
		return false;	
	}
}else{
echo "false";
}
?>