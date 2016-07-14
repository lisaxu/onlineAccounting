<?php 
require_once('lib/database.php');
require_once('lib/user.php');

session_start(); 
$errors = array();


if(count($_POST) > 0){  //for the first direct
	if(isset($_POST['delete'])){
		$_SESSION['mode'] = "delete";
		$_SESSION['username'] = $_POST['username'];
	}else if(isset($_POST['update'])){
		$_SESSION['mode'] = "update";
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['balance'] = $_POST['balance'];
	}
	header("HTTP/1.1 303 See Other");
    header("Location: http://$_SERVER[HTTP_HOST]/~lisaxu/meal/userManage.php");
    die();
}

$subtitle = "User Management";
require_once('inc/header.php');

if(isset($_SESSION['mode'])){  //for the second direct
	//form processing
	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 1){
		switch($_SESSION['mode']){
			case "update":
				if(!is_numeric($_SESSION['balance'])){
					$errors[0] = "User balance must be a number";
				}
				if(empty($errors)){
					User::updateORregister($_SESSION['username'] ,$_SESSION['balance']);
				}
			break;
			case "delete":
				User::deleteByName($_SESSION['username']);
			break;
		}
	}else{
		$errors[] = "Error: You need to first log in to get the permission to manage user info";
	}
}
		unset($_SESSION['mode']);
		unset($_SESSION['username']);
		unset($_SESSION['balance']);

if(count($errors) > 0){ 
	echo "<div class=\"alert alert-danger\">";
	foreach($errors as $field => $error){
		echo "<li>$error</li>";
	} 
	echo "</div>";
}
?>

<form class="form-horizontal" method="post" action="userManage.php">
	<div class="form-group">
		<label for="username" class="col-sm-5 control-label">username</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="username" name="username" placeholder="name" onkeyup="showRecord(false)" autocomplete="off" required>
		</div>
		<label for="username" class="col-sm-5 control-label" id="dynamic"> </label>
	</div>

	<div class="form-group">
		<label for="balance" class="col-sm-5 control-label">Balance change</label>
		<div class="input-group col-sm-2"> 
			<div class="input-group-addon">$</div>
			<input type="text" class="form-control" id="balance" name = "balance" placeholder="+ or -">
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-5 col-sm-7">
			<input type="hidden" name="submitted" value="1" />
			<button type="submit" class="btn btn-info" name="update">Register / Update</button>
			<button type="submit" class="btn btn-danger" name="delete">Delete</button>
		</div>
	</div>
</form>

<br><hr>

<h3> List of all users </h3>
<table class="table table-condensed table-hover">		
	<thead>
		<tr>
			<th>UserId</th>
			<th>Username</th>
			<th>Balance</th>
			<th>Last action</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$collection = User::getAllUsers();
		foreach ($collection as $u){ ?>
		<tr>
			<td><?php echo $u[0] ?></td>
			<td><?php echo $u[1] ?></td>
			<td><?php echo $u[2] ?></td>
			<td><?php echo "U" ?></td>
		</tr>	
	<?php 
		}
	?>
	</tbody>
</table>
<p>*All input names will be converted to lower case and with whitespace trimmed</p>

<?php 
require_once('inc/footer.php');
?>
