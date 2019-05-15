<?php
require('vars.php');
try{
    $sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
    if(mysqli_connect_errno())
    {
        echo "false";
        return false;
    }
    else{
        $result = mysqli_query($sql,"SELECT * FROM ofline_times");
        $time_now = date("H:i:s");
        while($rr = mysqli_fetch_array($result)){
            if($time_now > $rr['start'] && $time_now < $rr['stop']){
                echo("ofline_time");
                return;
            }
        }


        $result = mysqli_query($sql,"SELECT * FROM setor");
        while($rr = mysqli_fetch_array($result)){
            echo ("!!id:". $rr['id_out_pin'] . "&mode:" . $rr["mode"]);
        }
        mysqli_close($sql);
    }
}
catch(Exception $e){
    echo "false";
}
?>