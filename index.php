<?php 
require_once('lib/database.php');
require_once('lib/user.php');
require_once('lib/dish.php');
require_once('lib/sale.php');
$db = new Database();
$db->initDatabase();

session_start(); 
$errors = array();


if(count($_POST) > 0){  //for the first direct, put $_POST into $_SESSION
	$order = array();  //order: [id] = qty
	foreach ($_POST as $key => $value) {
		if(strcmp("username", $key) == 0){
			$_SESSION['username'] = $_POST['username'];
		}else if(strcmp("paid", $key) == 0){
			$_SESSION['paid'] = $_POST['paid'];
		}else if(strcmp("change", $key) == 0){
			$_SESSION['change'] = $_POST['change'];
		}else{
			if($value != 0){
				$order[$key] = $value; 
			}
		}
	}
	$_SESSION['order'] = serialize($order);
	print_r($_SESSION['order']);


	header("HTTP/1.1 303 See Other");
    header("Location: http://$_SERVER[HTTP_HOST]/~lisaxu/meal/index.php");
    die();
}

$subtitle = "Record Sales";
require_once('inc/header.php');


if(isset($_SESSION['username'])){  //for the second direct, read from $_SESSION
	//form processing
	$errors = array();
	$orders = unserialize($_SESSION['order']);
	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 1){
		Sale::recordSale($_SESSION['username'], $orders, $_SESSION['paid'] , $_SESSION['change'] , "regular sale through web");
	}else{
		$errors[] = "Error: You need to first log in to get the permission to place order";
	}
	//print_r($order); 
	//if(!is_numeric($_SESSION['balance'])){
	//	$errors[0] = "User balance must be a number";
	//}
	//if(empty($errors)){
	//	User::updateORregister($_SESSION['username'] ,$_SESSION['balance']);
	//}
	//session_unset();
	//session_destroy();
	unset($_SESSION['username']);
}

if(count($errors) > 0){ 
	echo "<div class=\"alert alert-danger\">";
	foreach($errors as $field => $error){
		echo "<li>$error</li>";
	} 
	echo "</div>";
}
?>
<form class="form-horizontal" method="post" action="index.php">
  <div class="form-group">
    <label for="username" class="col-sm-3 control-label">Username</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="username" name="username" placeholder="name" onkeyup="showRecord(true)" autocomplete="off"  required>
    </div>
		<label for="username" class="col-sm-5 control-label" id="dynamic"> </label>
  </div>
  <hr>
  <div>Meals</div>
	<?php 
		$collection = Dish::getAllMeals();
		foreach ($collection as $d){ 
			if($d[4]){?>
				<div class="form-group">
				<label for="meal" class="col-sm-5 control-label"><?php echo Dish::convertName($d[1]) ?></label>
				<div class="col-sm-2 formSelect">
					<select class="form-control" name="<?php echo $d[0] ?>" onchange="updateTotal()">
						<option value="0" selected>0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>

				</select>
				</div>
				</div>
	<?php 
			}
		}
	?>	
 
  
  
  <hr>
 <div>Appetizer</div>
 <?php 
		$collection = Dish::getAllAppetizers();
		foreach ($collection as $d){ 
			if($d[4]){?>
			<div class="form-group">
				<label for="meal" class="col-sm-5 control-label"><?php echo Dish::convertName($d[1]) ?></label>
				<div class="col-sm-2 formSelect">
					<select class="form-control" name="<?php echo $d[0] ?>" onchange="updateTotal()">
						<option value="0" selected>0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>		
				</div>
			</div>
	<?php 
			}
		}
	?>
  <hr>
 <div>Speed Key</div>
 <?php 
		$collection = Dish::getAllSpeedKey();
		foreach ($collection as $d){ 
			if($d[4]){?>
			<div class="form-group">
				<label for="meal" class="col-sm-5 control-label"><?php echo Dish::convertName($d[1]) ?></label>
				<div class="col-sm-2 formSelect">
					<select class="form-control" name="<?php echo $d[0] ?>" onchange="updateTotal()">
						<option value="0" selected>0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
					</select>		
				</div>
			</div>
	<?php 
			}
		}
	?>		
  <hr>
  
	<div class="form-group">
		<label for="total" class="col-sm-5 control-label">Total $</label>
		<div class="col-sm-1">
		  <p class="form-control-static" id="total">0.00</p>
		</div>
	</div>
	
  <div class="form-group">
    <label for="paid" class="col-sm-5 control-label">Paid</label>
    <div class="input-group col-sm-2">
		<div class="input-group-addon">$</div>
      <input type="text" class="form-control" id="paid" placeholder="paid" name="paid" onkeyup="showChange()" autocomplete="off" required>
    </div>
	<div class="col-sm-5">
		<p class="form-control-static" id="checkPaidNaN"></p>
	</div>
  </div>
	
	<div class="form-group">
		<label for="change" class="col-sm-5 control-label">Change</label>
		<div class="input-group col-sm-2">
			<div class="input-group-addon">$</div>
		  <input type="text" class="form-control" id="change" placeholder="change" name="change" value=0>
		</div>
		<div class="col-sm-5">
		  <p class="form-control-static" id="suggestChange"></p>
		</div>
	</div>


  <div class="form-group">
    <div class="col-sm-offset-5 col-sm-5">
      <button type="submit" class="btn btn-info">Submit</button>
    </div>
  </div>
</form>




<?php 
require_once('inc/footer.php');
?>