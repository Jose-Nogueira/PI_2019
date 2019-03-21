<?php
session_start();
require("php/login.php");
if(!testlog()){
	require("Signin.php");
}else{
    require("Main Menu.php");
    /*
	if(@$_GET['page']=='relatorio')
		require("relat.php");
	elseif(@$_GET['page']=='logout'){
		logout();
		echo '<meta http-equiv="refresh" content="0; url=?n='.time().'" />';
		}
	else
        require("new_n.php");
        */
		
}
?>
</div>
</body>
</html>