<?php
ob_start();
session_start();
require("php/login.php");
if(!testlog()){
	require("login.php");
}else{
    require("Main_Menu.php");
    if(isset($_GET['logout'])){
        logout();
        echo '<meta http-equiv="refresh" content="0; url=?n='.time().'" />';
    }	
}
ob_end_flush();
?>