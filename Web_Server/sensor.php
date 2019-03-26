<?php
require('vars.php');
if(isset($_GET["id"])){
try{
$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
if(mysqli_connect_errno())
{
echo "false";
return false;
}
else{
$count=0;
$id_setor=100;
$id = mysqli_real_escape_string($sql, $_GET["id"]);
$result = mysqli_query($sql,"SELECT * FROM esplist WHERE id=".$id);
while($rr = mysqli_fetch_array($result)){
if($rr['id'] == $id){
//mysqli_close($sql);
echo ("setor_id:" . $rr["Setor_id"]);
echo ("&gold:" . $rr["gold"]);
$id_setor=$rr["Setor_id"];
$count++;
break;
}
}
$result = mysqli_query($sql,"SELECT * FROM setor WHERE id_out_pin=".$id_setor);
while($rr = mysqli_fetch_array($result)){
if($rr['id_out_pin'] == $id_setor){
mysqli_close($sql);
echo ("&mode:" . $rr["mode"]);
$count++;
break;
}
}
if($count < 2)
echo "false";
}
}
catch(Exception $e){
echo "false";
}
}else{
echo "false";
}
?>
