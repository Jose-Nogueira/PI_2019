<?php
iniclog();
$login_flag = false;
if(testlog()){
	?>
	<script type="text/javascript">window.location = window.location.href.substring(0,window.location.href.indexOf("?"));</script>
	<?php
}
else if($_POST['submit'] == 'true'){
    $list = true;
    if(strlen(@$_POST['email'])>5){
        $list = scemail(@$_POST['email']);
        if(strtolower ($_POST['pass']) == strtolower ($list[2])){
            $login_flag = true;
            $user = randid();
            $_SESSION['sessid'] = $user;
            $_SESSION['userid'] = $list[0];
            $_SESSION['email'] = $list[1];
            $_SESSION['user-agent'] = @$_SERVER['HTTP_USER_AGENT'];
            echo "pass:".$_SESSION['sessid']."<br>pass:".  $_SESSION['email'] . $_SESSION['userid'];
            setcookie('sessid',@$_SESSION['sessid'],time() +60*60*24*15);
            setcookie('email',@$_SESSION['email'],time() +60*60*24*15);
            if(@$_POST['look']){
                loginlock($list[0], $user);
            }
            ?>
                <script type="text/javascript">
                <?php  if(isset($_GET['go']))
                $_SESSION['go'] = @$_GET['go'];
                ?>
                window.location = window.location.href.substring(0,window.location.href.indexOf("?"));
                //window.location.reload(true);</script>
            <?php
        }
    }

}
if(!$login_flag){
    require("Signin.html");
}


?>