<?php 
$subtitle = "Log in/Out";
session_start(); 
require_once('inc/header.php');


if(count($_POST) > 0){
	if(isset($_POST['login'])){
		if(strcmp(md5($_POST['password']), "**hidden_for_security_purpose**") ==0
		 || strcmp(md5($_POST['password']), "**hidden_for_security_purpose**")){
			$_SESSION['loggedIn'] = 1;
			echo "<div class=\"alert alert-success\">Logged in!   Redirecting to the main page...</div>";
			echo "<script>setTimeout(\"location.href = 'http://www.cs.colostate.edu/~lisaxu/meal/index.php';\",2300);</script>";
		}else{
			echo "<div class=\"alert alert-danger\">Password incorrect</div>";
		} 
	}
	else{
		unset($_SESSION['loggedIn']);
		echo "<div class=\"alert alert-success\">Logged out! </div>";
	}
}
?>	
<h1>Welcome, XiaoSi</h1>
<hr>
<form class="form-horizontal" method="post" action="login.php">
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">password</label>
    <div class="col-sm-3">
      <input type="password" class="form-control" name="password" id="password" placeholder="password">
    </div>
  </div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-info" name="login">Log in</button>
		  <button type="submit" class="btn btn-primary" name="logout">Log out</button>
		</div>
	</div>
</form>


<?php 
require_once('inc/footer.php');
?>