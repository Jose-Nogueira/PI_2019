<?php
if ( stripos( $_SERVER[ 'REQUEST_URI' ], basename( __FILE__ ) ) !== FALSE ) {
  header( 'HTTP/1.0 404 Forbidden' );
  require('../404.html' );
  die();
  $_SERVER['REDIRECT_STATUS'] = 404;
}
//////////////////////////
// Log file register
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
/////////////////////////
///
/// Login functions
///
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
// criar novo utilizador
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
/////////////////////////////
///
/// Power analize
///
function w_setor_data($id=0,$data_size=1000){
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		$list = '';
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$id_ = mysqli_real_escape_string($sql, $id);
			$size = mysqli_real_escape_string($sql, $data_size);
			$result = mysqli_query($sql,"SELECT * FROM `kw_h` WHERE `id_setor`=".$id_." ORDER BY `id` DESC LIMIT 0 , ".$size."");
			if(!$result){
				mysqli_close($sql);
				return false;
			}
			else{
				$i = 0;
				while($rr = mysqli_fetch_array($result)){
						$ll[$i] = array($rr['w'], $rr['time']);
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
function total_w_setor_data($data_size=1000){
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		$list = '';
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$size = mysqli_real_escape_string($sql, $data_size);
			$result = mysqli_query($sql,"SELECT SUM(`w`) As totais, `time`, CONCAT(DATE(`time`),':',HOUR(`time`)) As hora_
			FROM `kw_h` L
			INNER JOIN
			(SELECT `id`, CONCAT(DATE(`time`),':',HOUR(`time`),'-', `id_setor`) As day_
			FROM `kw_h`
			GROUP BY day_
			Order BY `time`
			DESC LIMIT 0, " . $size . ") As t
			on L.id = t.id
			GROUP BY hora_
			Order BY `time` ASC");
			if(!$result){
				mysqli_close($sql);
				return false;
			}
			else{
				$i = 0;
				while($rr = mysqli_fetch_array($result)){
						$ll[$i] = array($rr['totais'], $rr['time']);
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
/////////////////////////////
///
/// Setor functions
///
function get_setors_mode(){
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		$list = '';
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$result = mysqli_query($sql,"SELECT * FROM `setor`");
			if(!$result){
				mysqli_close($sql);
				return false;
			}
			else{
				$i=0;
				while($rr = mysqli_fetch_array($result)){
						$ll[$i] = array($rr['id_out_pin'], $rr['mode']);
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

function get_setor_id($id = 0){
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		$list = '';
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$id_ = mysqli_real_escape_string($sql, $id);
			$result = mysqli_query($sql,"SELECT * FROM `setor` WHERE `id_out_pin`=" . $id_);
			if(!$result){
				mysqli_close($sql);
				return false;
			}
			else{
				while($rr = mysqli_fetch_array($result)){
						$ll = array($rr['id_out_pin'], $rr['mode'], $rr['Nome']);
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

function set_setor_mode($id=0, $mode="auto"){
	if(testlog()){
	require('vars.php');
	logreg("set_setor_mode(", @$_SESSION['email'] . ", id:" . $id . ", mode:" . $mode);
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		if($mode != "auto" && $mode != "on" && $mode != "off"){
			mysqli_close($sql);
			return false;
		}
		$id_ = mysqli_real_escape_string($sql, $id);
		$result = mysqli_query($sql,"UPDATE `setor` SET `mode`='" . $mode . "' WHERE `id_out_pin`=".$id_);
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
/////////////////////////////
///
/// ESP functions
///

function esp_data($id=0,$data_size=1000){
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		$list = '';
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$id_ = mysqli_real_escape_string($sql, $id);
			$size = mysqli_real_escape_string($sql, $data_size);
			$result = mysqli_query($sql,"SELECT * FROM `esp_stats` WHERE `id_esp`=".$id_." ORDER BY `id` DESC LIMIT 0 , ".$size."");
			if(!$result){
				mysqli_close($sql);
				return false;
			}
			else{
				$i = 0;
				while($rr = mysqli_fetch_array($result)){
						$ll[$i] = array($rr['time'], $rr['gold'], $rr['pid_in'], $rr['pid_out']);
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

function get_esps_info(){
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		$list = '';
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$result = mysqli_query($sql,"SELECT * FROM `esplist`");
			if(!$result){
				mysqli_close($sql);
				return false;
			}
			else{
				$i=0;
				while($rr = mysqli_fetch_array($result)){
						$ll[$i] = array($rr['id'], $rr['Nome'], $rr['gold'], $rr['Setor_id']);
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

function get_esp_id($id = 0){
	require('vars.php');
	try{
		$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
		$list = '';
		if(mysqli_connect_errno())
		{
			return false;
		}
		else{
			$id_ = mysqli_real_escape_string($sql, $id);
			$result = mysqli_query($sql,"SELECT * FROM `esplist` WHERE `id`=" . $id_);
			if(!$result){
				mysqli_close($sql);
				return false;
			}
			else{
				while($rr = mysqli_fetch_array($result)){
						$ll = array($rr['id'], $rr['Nome'], $rr['gold'], $rr['Setor_id']);
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

function set_esp_gold($id=0, $gold="850"){
	if(testlog()){
	require('vars.php');
	logreg("set_esp_gold(", @$_SESSION['email'] . ", id:" . $id . ", gold:" . $gold);
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$id_ = mysqli_real_escape_string($sql, $id);
		$gold_ = mysqli_real_escape_string($sql, $gold);
		$result = mysqli_query($sql,"UPDATE `esplist` SET `gold`=" . $gold_ . " WHERE `id`=".$id_);
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

function set_esp_setor_id($id=0, $setor="0"){
	if(testlog()){
	require('vars.php');
	logreg("set_esp_setor_id(", @$_SESSION['email'] . ", id:" . $id . ", setor:" . $setor);
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$id_ = mysqli_real_escape_string($sql, $id);
		$setor_ = mysqli_real_escape_string($sql, $setor);
		$result = mysqli_query($sql,"UPDATE `esplist` SET `Setor_id`=" . $setor_ . " WHERE `id`=".$id_);
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

function set_esp_name($id=0, $name="esp"){
	if(testlog()){
	require('vars.php');
	logreg("set_esp_name(", @$_SESSION['email'] . ", id:" . $id . ", name:" . $name);
	try{
	$sql = mysqli_connect($sql_server,$sql_user,$sql_pass,$sql_bd);
	$list = '';
	if(mysqli_connect_errno())
	{
		return false;
	}
	else{
		$id_ = mysqli_real_escape_string($sql, $id);
		$name_ = mysqli_real_escape_string($sql, $name);
		$result = mysqli_query($sql,"UPDATE `esplist` SET `Nome`='" . $name_ . "' WHERE `id`=".$id_);
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
//////////////////////////
///
///not used
///
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
?>