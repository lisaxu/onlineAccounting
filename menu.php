<?php 
require_once('lib/database.php');
require_once("lib/dish.php");
$subtitle = "Menu Management";
session_start(); 
$errors = array();


if(count($_POST) > 0){  //for the first direct
	if(isset($_POST['create'])){
		$_SESSION['mode'] = "create";
		$_SESSION['dishname'] = $_POST['dishname'];
		$_SESSION['price'] = $_POST['price'];
		$_SESSION['type'] = $_POST['type'];
		$_SESSION['available'] = $_POST['available'];
	}else if(isset($_POST['update'])){
		$_SESSION['mode'] = "update";
		$_SESSION['dishId'] = $_POST['dishId'];
		$_SESSION['newPrice'] = $_POST['newPrice'];
		$_SESSION['newType'] = $_POST['newType'];
		$_SESSION['newAvailable'] = $_POST['newAvailable'];
	}else if(isset($_POST['delete'])){
		$_SESSION['mode'] = "delete";
		$_SESSION['dishId'] = $_POST['dishId'];
	}else if(isset($_POST['restore'])){
		$_SESSION['mode'] = "restore";
	}
	header("HTTP/1.1 303 See Other");
    header("Location: http://$_SERVER[HTTP_HOST]/~lisaxu/meal/menu.php");
    die();
}

require_once('inc/header.php');


if(isset($_SESSION['mode'])){  //for the second direct
	//form processing
	$errors = array();
	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 1){
		switch($_SESSION['mode']){
			case "create":
				if(!is_numeric($_SESSION['price'])){
				$errors[0] = "Price must be a number";
				}
				if(empty($errors)){
				Dish::addDish($_SESSION['dishname'], $_SESSION['price'], $_SESSION['type'], $_SESSION['available']);
				}
			break;
			case "update":
				if(!is_numeric($_SESSION['newPrice'])){
				$errors[0] = "The new price must be a number";
				}
				if(empty($errors)){
				Dish::updateDish($_SESSION['dishId'],  $_SESSION['newType'], $_SESSION['newPrice'], $_SESSION['newAvailable']);
				}
			break;
			case "delete":
				Dish::deleteById($_SESSION['dishId']);
			break;
			case "restore":
				Dish::deleteAndInitialize();
			break;
		}
		unset($_SESSION['mode']);
	}else{
		$errors[] = "Error: You need to first log in to get the permission to manage menu";
	}
}

if(count($errors) > 0){ 
	echo "<div class=\"alert alert-danger\">";
	foreach($errors as $field => $error){
		echo "<li>$error</li>";
	} 
	echo "</div>";
}

?>
<div class="row">
<div class="col-sm-6">
   <form class="form-horizontal" method="post" action="menu.php">
	<div class="form-group">
		<label for="dishname" class="col-sm-5 control-label">dish Name</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="dishname" name="dishname" required>
		</div>
	</div>

	<div class="form-group">
		<label for="price" class="col-sm-5 control-label">Price</label>
		<div class="input-group col-sm-3"> 
			<div class="input-group-addon">$</div>
			<input type="text" class="form-control" id="price" name="price" placeholder="Price" required>
		</div>
	</div>
 
	<div class="form-group">
		<div class="col-sm-offset-5 col-sm-7">
			<label class="radio-inline">
			<input type="radio" name="type" id="type1" value="1" checked="checked"> Meal
			</label>
			<label class="radio-inline">
			<input type="radio" name="type" id="type2" value="2"> Appetizer
			</label>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-5 col-sm-7">
			<label class="radio-inline">
			<input type="radio" name="available" id="availableTrue" value="1" checked="checked"> Currently available
			</label>
			<label class="radio-inline">
			<input type="radio" name="available" id="availableFalse" value="0"> Not available
			</label>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-5 col-sm-7">
			<button type="submit" class="btn btn-info" name="create">Add dish</button>
		</div>
	</div>
  </form>
</div>
<!-- -------------------------------update-------------------------------------------------------->
<div class="col-sm-6">
   <form class="form-horizontal" method="post" action="menu.php">
	<div class="form-group">
		<label for="dishId" class="col-sm-4 control-label">dish ID</label>
		<div class="col-sm-3">
			<input type="text" class="form-control" id="dishId" name="dishId" required>
		</div>
	</div>

	<div class="form-group">
		<label for="newPrice" class="col-sm-4 control-label">New Price</label>
		<div class="input-group col-sm-3"> 
			<div class="input-group-addon">$</div>
			<input type="text" class="form-control" id="newPrice" name="newPrice" placeholder="Price">
		</div>
	</div>
 
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-7">
			<label class="radio-inline">
			<input type="radio" name="newType" id="newType1" value="1" checked="checked"> Meal
			</label>
			<label class="radio-inline">
			<input type="radio" name="newType" id="newType2" value="2"> Appetizer
			</label>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-7">
			<label class="radio-inline">
			<input type="radio" name="newAvailable" id="NewAvailableTrue" value="1" checked="checked"> Currently available
			</label>
			<label class="radio-inline">
			<input type="radio" name="newAvailable" id="NewAvailableFalse" value="0"> Not available
			</label>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-7">
			<button type="submit" class="btn btn-primary" name="update">Update</button>
			<button type="submit" class="btn btn-danger" name="delete">Delete</button>
		</div>
	</div>
  </form>
</div>
</div>

<br><hr>
<h3> List of all Dishes </h3>
<table class="table table-condensed table-hover">		
	<thead>
		<tr>
			<th>DishID</th>
			<th>Chinese</th>
			<th>Dishname</th>
			<th>Price</th>
			<th>Type</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$collection = Dish::getAllDishes();
		foreach ($collection as $u){ ?>
		<tr>
			<td><?php echo $u[0] ?></td>
			<td><?php echo Dish::convertName($u[1]) ?></td>
			<td><?php echo $u[1] ?></td>
			<td><?php echo $u[2] ?></td>

			<td>
				<?php 
					if($u[3] == 1)
						echo "Meal";
					else if ($u[3] == 2)
						echo "Appetizer";
					else if ($u[3] == 3)
						echo "SpeedKey";
				?>
			</td>
			<td>
				<?php 
					if($u[4] == 1)
						echo "Available";
					else
						echo "Not available";		
				?>
			</td>
		</tr>	
	<?php 
		}
	?>
	</tbody>
</table>
<form class="form-horizontal" method="post" action="menu.php">
	<div class="form-group">
	<div class="col-sm-7">
		<button type="submit" class="btn btn-info" name="restore">Restore</button>
	</div>
	</div> 
</form>
<?php 
require_once('inc/footer.php');
?>