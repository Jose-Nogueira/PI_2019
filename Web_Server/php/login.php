<?php
if ( stripos( $_SERVER[ 'REQUEST_URI' ], basename( __FILE__ ) ) !== FALSE ) {
  header( 'HTTP/1.0 404 Forbidden' );
  require('../404.html' );
  die();
  $_SERVER['REDIRECT_STATUS'] = 404;
}
function logreg($funct , $args = "", $file = "php/log.php"){
	$a = file_get_contents($file);
	$date = date_create(date('c'), timezone_open(date('e')));
	date_timezone_set($date, timezone_open('Europe/London'));
	$dd = date_format($date, 'Y-m-d H:i:sP');
	$args = str_replace("<?php","",$args);
	$args = str_replace("<?","",$args);
	$args = str_replace("?>","",$args);
	$sav = $a . "\n [".$dd."] : " . $funct . "(" . $args . ");";//time,new line, inform log
	file_put_contents($file ,$sav);
}
//ver se a sessao esta ativa(compara cookies com session(sessid))
function testlog(){
	if((isset($_SESSION['sessid']) ? $_SESSION['sessid'] :'false') == @$_COOKIE['sessid'] && @$_SESSION['user-agent'] == @$_SERVER['HTTP_USER_AGENT']){
		return true;	
	}
	return false;
}
//ver se o email esta registado e devolve linha
function scemail($email){
	if(strlen($email)>5 && (strrpos($email, ' ') == false && strrpos($email, '=') == false)){
	logreg("scemail", $email);
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$email = mysqli_real_escape_string($sql, $email);
			$result = mysqli_query($sql,"SELECT * FROM users WHERE email='".$email."'");
			while($rr = mysqli_fetch_array($result)){
				if($rr['email'] == $email){
					mysqli_close($sql);
					return array($rr['id'], $email, $rr['pass'], $rr['log']);	
				}
			}
		}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;
}
//id
function scid($id){
	if($id >= 0){
	require('vars.php');
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$result = mysqli_query($sql,"SELECT * FROM users WHERE id='".$id."'");
		while($rr = mysqli_fetch_array($result)){
			if($rr['id'] == $id){
				mysqli_close($sql);
				return array($id, $rr['email'], $rr['pass'], $rr['log']);	
			}
		}
	}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;
}
//editar senha e login
function editpass($email, $newpas){
	if($email && $newpas){
	logreg("editpass", $email);
	require('vars.php');
	try{
	$list = scemail($email);
	if($list){
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$email = mysqli_real_escape_string($sql, $email);
		$newpas = mysqli_real_escape_string($sql, $newpas);
		$result = mysqli_query($sql,"UPDATE users SET pass='".$newpas."' WHERE id=".$list[0]);
		mysqli_close($sql);
		return true;
	}
	}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;	
}
//manter iniciado
function loginlock($id, $userid){
	if($id && $userid){
	logreg("loginlock", "id:" . $id. ", Userid:" . $userid);
	require('vars.php');
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$result = mysqli_query($sql,"UPDATE users SET log='".$userid."' WHERE id=".$id);
		mysqli_close($sql);
		return true;
	}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;	
}
//caso de manter iniciado reiniciar sessao
function iniclog(){
	$loo =scemail(@$_COOKIE['email']);
	if($loo){
		logreg("iniclog", @$_COOKIE['email']);
		if(@$_COOKIE['sessid'] == @$loo[3]){
			setcookie('sessid',@$_COOKIE['sessid'],time() +60*60*24*15);
			setcookie('email',@$_COOKIE['email'],time() +60*60*24*15);
			$_SESSION['sessid'] = @$loo[3];
			$_SESSION['userid'] = @$loo[0];
			$_SESSION['email'] = @$loo[1];
			$_SESSION['user-agent'] = @$_SERVER['HTTP_USER_AGENT'];
			return true;
		}
	}
	return false;	
}
//terminar sessao
function logout(){
	logreg("logout", @$_SESSION['email']);
	unset($_SESSION['sessid']);
	unset($_SESSION['email']);
	unset($_SESSION['user-agent']);
	setcookie('PHPSESSID', '' , time()-3600);
	setcookie('sessid','',time()-3600);
	setcookie('email','',time()-3600);
	session_destroy();
	return false;
}

function randid(){
	$ret_str = "";
	$aValores = array();
	foreach(range(49, 57) as $val)
		array_push ($aValores, $val);
	foreach(range (65, 90) as $val)
		array_push ($aValores, $val);
	foreach(range(97, 122) as $val)
		array_push($aValores, $val);
	if(count($aValores)> 0)
	{
		for ($i = 0; $i < 10; $i++)
			$ret_str .= chr($aValores[rand(0, count($aValores) - 1)]);
	}
	return md5($ret_str);
}

//not used
function antibot($id, $val){
	logreg("antibot", $id . ", " . $val);
	require('vars.php');
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$result = mysqli_query($sql,"UPDATE `users` SET `antibot`=".$val." WHERE id=".$id);
		mysqli_close($sql);
		if($result){
			return true;	
		}
	}
	}
	catch(Exception $e){
		return false;	
	}
	return false;	
}

function publicar($titulo, $file, $dfile, $noticia){
	if(testlog()){
	require('vars.php');
	$img = uploadimg($file, $dfile);
	logreg("publicar", @$_SESSION['email'] . ", Titulo:" . $titulo . ", Noticia:" . $noticia . ", Img:" . $img);
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$titulo1 = mysqli_real_escape_string($sql, $titulo);
		$noticia1 = mysqli_real_escape_string($sql, $noticia);
		$date = date_create(date('c'), timezone_open(date('e')));
		date_timezone_set($date, timezone_open('Europe/London'));
		$dd = date_format($date, 'Y-m-d H:i:sP');
		
		$result = mysqli_query($sql,"INSERT INTO `noticias`(`id`, `titulo`, `img`, `noticia`,`data`) VALUES (".$_SESSION['userid'].",'".$titulo1."','".$img."','".$noticia1."','".$dd."')");
		if(!$result){
			mysqli_close($sql);
			return false;
		}
		else{
			mysqli_close($sql);
			return true;
		}
		
	}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;
}

function edit_pub($id, $titulo, $file, $dfile, $filename, $noticia){
	if(testlog()){
	require('vars.php');
	$img = uploadimg($file, $dfile, $filename);
	logreg("edit_pub", @$_SESSION['email'] . ", Titulo:" . $titulo . ", Noticia:" . $noticia . ", Img:" . $img);
	if($img == false)
		$img = $filename;
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$titulo1 = mysqli_real_escape_string($sql, $titulo);
		$noticia1 = mysqli_real_escape_string($sql, $noticia);
		$date = date_create(date('c'), timezone_open(date('e')));
		date_timezone_set($date, timezone_open('Europe/London'));
		$dd = date_format($date, 'Y-m-d H:i:sP');
		
		$result = mysqli_query($sql,"UPDATE `noticias` SET `titulo`='" . $titulo1 . "',`img`='" . $img . "',`noticia`='" . $noticia1 . "',`data`='" . $dd . "' WHERE `idn`=".$id);
		if(!$result){
			mysqli_close($sql);
			return false;
		}
		else{
			mysqli_close($sql);
			return true;
		}
		
	}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;
}	

function list_not($page=0,$vperpage=10){
	require('vars.php');
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$result = mysqli_query($sql,"SELECT * FROM `noticias` WHERE 1");
		$ll[0] = mysqli_num_rows($result);
		$result = mysqli_query($sql,"SELECT * FROM `noticias` WHERE 1 ORDER BY `idn` DESC LIMIT ".$page*$vperpage." , ".$vperpage."");
		if(!$result){
			mysqli_close($sql);
			return false;
		}
		else{
			$i = 1;
			while($rr = mysqli_fetch_array($result)){
					$ll[$i] = array($rr['idn'], $rr['titulo'],$rr['img'],$rr['noticia'],$rr['data'],$rr['id'],$rr['idi'], $rr['mark']);
					$i++;
			}
			return $ll;
		}
		
	}
	}
	catch(Exception $e){
		return false;	
	}
	return false;
}

function delnot($id){
	if(testlog()){
		require('vars.php');
		logreg("delnot", @$_SESSION['email'] . ", id not:" . $id);
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$result = mysqli_query($sql,"DELETE FROM `noticias` WHERE (`id`=" . $_SESSION['userid'] . ") AND (`idn`=" . $id . ")");
		if(!$result){
			mysqli_close($sql);
			return false;
		}
		else{
			mysqli_close($sql);
			return true;
		}
		
	}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;		
	
}

function id_not($id){
	require('vars.php');
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	$ll = false;
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$result = mysqli_query($sql,"SELECT * FROM `noticias` WHERE `idn`=".$id);
		if(!$result){
			mysqli_close($sql);
			return false;
		}
		else{
			while($rr = mysqli_fetch_array($result)){
				if($rr['idn'] == $id)
					$ll = array($rr['idn'], $rr['titulo'],$rr['img'],$rr['noticia'],$rr['data'],$rr['id'],$rr['idi'],$rr['mark']);
			}
			return $ll;
		}
		
	}
	}
	catch(Exception $e){
		return false;	
	}
	return false;
}

function reguser($email, $pass){
	if(strlen($email)>5 && strlen($pass) >100){
	logreg("reguser", "email:" . $email);
	require('vars.php');
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$email = mysqli_real_escape_string($sql, $email);
		$pass = mysqli_real_escape_string($sql, $pass);
		$result = mysqli_query($sql,"INSERT INTO `users`(`email`, `pass`) VALUES ('".$email."','".$pass."')");
		if(!$result){
			mysqli_close($sql);
			return false;
		}
		mysqli_close($sql);
		return true;
	}
	}
	catch(Exception $e){
		return false;	
	}
	}
	return false;
}
?>